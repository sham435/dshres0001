<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Imports\StudentMarkImport;
use Maatwebsite\Excel\Facades\Excel;

class MarksImport extends Command
{
    protected $signature = 'marks:import {file : Path to the Excel file}';
    protected $description = 'Import student marks from an Excel file';

    public function handle()
    {
        $filePath = $this->argument('file');

        if (!file_exists($filePath)) {
            $this->error(__('marks.import_error') . " File not found: {$filePath}");
            return 1;
        }

        $this->info(__('marks.import') . ": {$filePath}");

        try {
            $import = new StudentMarkImport();
            Excel::import($import, $filePath);

            $this->info(__('marks.import_success'));
            $this->line(__('marks.records_imported') . ": {$import->getRowCount()}");
        } catch (\Exception $e) {
            $this->error(__('marks.import_error') . ' ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
