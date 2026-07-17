<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Token harus sama dengan SIDECAR_TOKEN di VPS
$token = '52a14f3d19d0a33bdf36476f0b15b5cbe23648572fb2b91ffa74ad09c47f5646'; 
$session_id = 'default'; // Nama session id bawaan package

// Endpoint untuk START session berdasarkan kode index.js VPS kamu
$url = "http://wa-sidecar.mtsn3tulungagung.sch.id/sessions/{$session_id}/start";

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true); // Harus POST sesuai index.js
curl_setopt($ch, CURLOPT_TIMEOUT, 30); // Beri waktu agak lama karena puppeteer sedang loading browser
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "Authorization: Bearer " . $token,
    "Content-Type: application/json"
));

$result = curl_exec($ch);
$error = curl_error($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "<h3>Status Code: $httpCode</h3>";

if ($error) {
    echo "Error cURL: " . htmlspecialchars($error);
} else {
    $response = json_decode($result, true);
    
    // Jika berhasil mengembalikan data QR string Base64
    if (isset($response['qr']) && !empty($response['qr'])) {
        echo "<h3>Silakan Scan QR Code ini:</h3>";
        echo '<img src="' . $response['qr'] . '" alt="QR Code WhatsApp" />';
        echo "<br><p>Status Session: <b>" . $response['status'] . "</b></p>";
    } else {
        echo "<h3>Respon dari VPS:</h3>";
        echo "<pre>" . htmlspecialchars($result) . "</pre>";
        echo "<p>Jalankan ulang halaman ini jika QR belum muncul (kemungkinan Puppeteer masih inisialisasi).</p>";
    }
}
?>