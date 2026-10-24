<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: sans-serif; font-size: 11px; }
        h2 { margin-bottom: 2px; }
        .meta { color: #555; margin-bottom: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #999; padding: 4px 6px; text-align: left; }
        th { background: #334155; color: #fff; }
        td.num { text-align: right; }
    </style>
</head>
<body>
<h2>{{ __('marks.inv_check_report') }}: {{ $session->session_name }}</h2>
<div class="meta">{{ $school->school_name }} — {{ $session->check_date }} — {{ __('marks.inv_conducted_by') }} {{ $session->conducted_by ?? __('marks.inv_na') }} — {{ __('marks.inv_status') }}: {{ __('marks.inv_st_' . $session->status) }}</div>
<table>
    <thead><tr><th>#</th><th>{{ __('marks.inv_item') }}</th><th>{{ __('marks.inv_inventory_no') }}</th><th>{{ __('marks.inv_book') }}</th><th>{{ __('marks.inv_real') }}</th><th>{{ __('marks.inv_surplus') }}</th><th>{{ __('marks.inv_deficiency') }}</th><th>{{ __('marks.inv_remarks') }}</th><th>{{ __('marks.inv_status') }}</th></tr></thead>
    <tbody>
        @foreach($session->items as $i => $item)
            <tr>
                <td>{{ $i + 1 }}</td><td>{{ $item->item_name }}</td><td>{{ $item->inventory_number }}</td>
                <td class="num">{{ $item->book_balance }}</td><td class="num">{{ $item->real_balance }}</td>
                <td class="num">{{ $item->surplus }}</td><td class="num">{{ $item->deficiency }}</td>
                <td>{{ $item->remarks }}</td><td>{{ __('marks.inv_opt_' . $item->checked_status) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
</body>
</html>
