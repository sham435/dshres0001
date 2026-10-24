<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: sans-serif; font-size: 10px; }
        h2 { margin-bottom: 2px; }
        .meta { color: #555; margin-bottom: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #999; padding: 3px 5px; text-align: left; }
        th { background: #334155; color: #fff; }
    </style>
</head>
<body>
<h2>{{ __('marks.inv_reports_title') }}</h2>
<div class="meta">{{ $school->school_name }} — {{ __('marks.inv_pdf_meta', ['date' => date('d M Y H:i'), 'count' => $items->count()]) }}</div>
<table>
    <thead><tr><th>#</th><th>{{ __('marks.inv_item') }}</th><th>{{ __('marks.inv_inventory_no') }}</th><th>{{ __('marks.inv_section') }}</th><th>{{ __('marks.inv_incharge') }}</th><th>{{ __('marks.inv_received') }}</th><th>{{ __('marks.inv_condition') }}</th><th>{{ __('marks.inv_status') }}</th></tr></thead>
    <tbody>
        @foreach($items as $i => $item)
            <tr>
                <td>{{ $i + 1 }}</td><td>{{ $item->item_name }}</td><td>{{ $item->inventory_number }}</td>
                <td>{{ optional($item->section)->section_name }}</td><td>{{ optional($item->incharge)->officer_name }}</td>
                <td>{{ $item->received_date }}</td><td>{{ __('marks.inv_cond_' . $item->condition_status) }}</td><td>{{ __('marks.inv_' . $item->statusLabel()) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
</body>
</html>
