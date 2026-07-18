<h3>Status Code: {{ $httpCode }}</h3>

@if (! empty($data['qr']))
    <h3>Silakan Scan QR Code ini:</h3>
    <img src="{{ $data['qr'] }}" alt="QR Code WhatsApp">
    <p>Status Session: <b>{{ $data['status'] ?? '-' }}</b></p>
@else
    <h3>Respon dari VPS:</h3>
    <pre>{{ json_encode($data, JSON_PRETTY_PRINT) }}</pre>
    <p>Refresh halaman ini jika QR belum muncul.</p>
@endif