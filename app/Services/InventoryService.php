<?php

namespace App\Services;

use App\Models\InvCheckItem;
use App\Models\InvCheckSession;
use App\Models\InvItem;
use App\Models\InvOfficer;
use App\Models\InvRemoval;
use App\Models\InvRemovedArchive;
use App\Models\InvSchool;
use App\Models\InvSection;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class InventoryService
{
    public function school(): InvSchool
    {
        return InvSchool::firstOrFail();
    }

    // ---------- Images ----------

    public function storeItemImage(?UploadedFile $file): ?string
    {
        return $file ? $file->store('inventory/items', 'public') : null;
    }

    public function deleteImage(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    public function replaceItemImage(InvItem $item, ?UploadedFile $file, bool $remove): ?string
    {
        if ($remove) {
            $this->deleteImage($item->image_path);
            return null;
        }
        if ($file) {
            $this->deleteImage($item->image_path);
            return $this->storeItemImage($file);
        }
        return $item->image_path;
    }

    // ---------- Check sessions ----------

    public function openSession(int $schoolId, array $data): InvCheckSession
    {
        return DB::transaction(function () use ($schoolId, $data) {
            $session = InvCheckSession::create([
                'school_id' => $schoolId,
                'session_name' => $data['session_name'],
                'check_date' => $data['check_date'],
                'conducted_by' => $data['conducted_by'] ?? auth()->user()->name,
                'notes' => $data['notes'] ?? null,
                'status' => 'active',
            ]);

            $items = InvItem::where('school_id', $schoolId);
            if (!empty($data['section_id'])) {
                $items->where('section_id', $data['section_id']);
            }
            foreach ($items->get() as $item) {
                InvCheckItem::create([
                    'session_id' => $session->id,
                    'item_id' => $item->id,
                    'item_name' => $item->item_name,
                    'inventory_number' => $item->inventory_number,
                    'book_balance' => 1,
                ]);
            }
            return $session;
        });
    }

    public function recordCounts(InvCheckSession $session, array $rows): void
    {
        foreach ($rows as $checkId => $row) {
            $check = InvCheckItem::where('session_id', $session->id)->find($checkId);
            if (!$check) {
                continue;
            }
            $real = (int) $row['real_balance'];
            $check->update([
                'real_balance' => $real,
                'surplus' => max(0, $real - $check->book_balance),
                'deficiency' => max(0, $check->book_balance - $real),
                'remarks' => $row['remarks'] ?? null,
                'checked_status' => $row['checked_status'] ?? 'checked',
                'checked_at' => now(),
            ]);
        }
    }

    public function activeSession(int $schoolId): InvCheckSession
    {
        return InvCheckSession::where('school_id', $schoolId)->where('status', 'active')->latest()->first()
            ?? InvCheckSession::create([
                'school_id' => $schoolId,
                'session_name' => 'QR Scan Check',
                'check_date' => now()->toDateString(),
                'conducted_by' => auth()->user()->name,
                'status' => 'active',
            ]);
    }

    // ---------- Removals ----------

    public function approveRemoval(InvRemoval $rec, array $data): void
    {
        DB::transaction(function () use ($rec, $data) {
            $item = InvItem::where('school_id', $rec->school_id)->with(['section', 'incharge'])->find($rec->original_item_id);

            InvRemovedArchive::create([
                'original_item_id' => $rec->original_item_id,
                'school_id' => $rec->school_id,
                'item_name' => $rec->item_name,
                'brand_name' => $rec->brand_name,
                'inventory_number' => $rec->inventory_number,
                'serial_number' => $rec->serial_number,
                'section_id' => $rec->section_id,
                'section_name' => $item ? optional($item->section)->section_name : null,
                'book_number' => $rec->book_number,
                'incharge_officer_id' => $rec->incharge_officer_id,
                'incharge_officer_name' => $item && $item->incharge ? $item->incharge->officer_name : null,
                'condition_status' => $rec->condition_status,
                'removal_reason' => $rec->recommendation_reason,
                'removed_by' => $rec->recommended_by,
                'approval_status' => 'approved',
                'approved_by' => $data['approved_by'] ?? auth()->user()->name,
                'disposal_method' => $data['disposal_method'] ?? null,
                'notes' => $data['admin_notes'] ?? $rec->notes,
                'original_created_at' => $item ? $item->created_at : null,
                'original_updated_at' => $item ? $item->updated_at : null,
            ]);

            if ($item) {
                $this->deleteImage($item->image_path);
                $item->delete();
            }
            $rec->delete();
        });
    }

    // ---------- Officers ----------

    public function enforceSingleIncharge(int $schoolId, ?int $sectionId, ?int $exceptId = null): void
    {
        if (empty($sectionId)) {
            return;
        }
        $query = InvOfficer::where('school_id', $schoolId)->where('section_id', $sectionId);
        if ($exceptId) {
            $query->where('id', '!=', $exceptId);
        }
        $query->update(['is_incharge' => false]);
    }

    // ---------- Bulk import ----------

    public function parseCsvPreview(UploadedFile $file, int $schoolId): array
    {
        $rows = [];
        if (($handle = fopen($file->getRealPath(), 'r')) !== false) {
            $header = fgetcsv($handle);
            if ($header) {
                while (($line = fgetcsv($handle, 2000, ',')) !== false) {
                    if (count($line) === count($header)) {
                        $rows[] = array_combine($header, $line);
                    }
                }
            }
            fclose($handle);
        }

        $valid = [];
        $skipped = 0;
        foreach ($rows as $row) {
            $resolved = $this->resolveBulkRow(array_map('trim', $row), $schoolId);
            if ($resolved) {
                $valid[] = $resolved;
            } else {
                $skipped++;
            }
        }
        return ['valid' => $valid, 'skipped' => $skipped];
    }

    public function resolveBulkRow(array $row, int $schoolId): ?array
    {
        if (empty($row['item_name']) || empty($row['inventory_number']) || empty($row['book_number'])) {
            return null;
        }
        if (InvItem::where('school_id', $schoolId)->where('inventory_number', $row['inventory_number'])->exists()) {
            return null;
        }
        return [
            'item_name' => $row['item_name'],
            'brand_name' => $row['brand_name'] ?? null,
            'inventory_number' => $row['inventory_number'],
            'serial_number' => $row['serial_number'] ?? null,
            'section_id' => !empty($row['section_name'])
                ? InvSection::where('school_id', $schoolId)->where('section_name', $row['section_name'])->value('id') : null,
            'book_number' => $row['book_number'],
            'incharge_officer_id' => !empty($row['officer_name'])
                ? InvOfficer::where('school_id', $schoolId)->where('officer_name', $row['officer_name'])->value('id') : null,
            'received_date' => $row['received_date'] ?? null,
            'received_from' => $row['received_from'] ?? null,
            'condition_status' => in_array($row['condition_status'] ?? '', InvItem::CONDITIONS) ? $row['condition_status'] : 'working',
        ];
    }

    public function confirmBulkImport(array $items, int $schoolId): int
    {
        $count = 0;
        foreach ($items as $row) {
            $resolved = $this->resolveBulkRow($row, $schoolId);
            if (!$resolved) {
                continue;
            }
            $resolved['school_id'] = $schoolId;
            $resolved['is_transferred'] = false;
            InvItem::create($resolved);
            $count++;
        }
        return $count;
    }

    // ---------- QR ----------

    public function qrDataUri(InvItem $item, int $schoolId): string
    {
        $payload = json_encode(['id' => $item->id, 'school_id' => $schoolId, 'inventory_number' => $item->inventory_number]);
        $qr = new QrCode(data: $payload, size: 200, margin: 10);
        $result = (new PngWriter())->write($qr);
        return 'data:image/png;base64,' . base64_encode($result->getString());
    }
}
