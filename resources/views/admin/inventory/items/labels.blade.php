<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: sans-serif; }
        .label { border: 1px solid #333; padding: 10px; margin-bottom: 12px; text-align: center; page-break-inside: avoid; }
        .label img { width: 180px; height: 180px; }
        .label .name { font-size: 14px; font-weight: bold; margin-top: 6px; }
        .label .number { font-size: 12px; color: #444; }
    </style>
</head>
<body>
@foreach($labels as $label)
    <div class="label">
        <img src="{{ $label['qr'] }}" alt="QR code">
        <div class="name">{{ $label['name'] }}</div>
        <div class="number">{{ $label['number'] }}</div>
    </div>
@endforeach
</body>
</html>
