<?php
// Tampilkan error PHP jika ada (hanya untuk debugging)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 1. Masukkan token secara manual di sini dulu untuk test
// Pastikan token INI SAMA PERSIS dengan SIDECAR_TOKEN di VPS
$token = '52a14f3d19d0a33bdf36476f0b15b5cbe23648572fb2b91ffa74ad09c47f5646'; 

// 2. URL VPS NAT yang sudah di-forward ke port 80
$url = "http://wa-sidecar.mtsn3tulungagung.sch.id/health";

echo "Mencoba koneksi ke: <b>" . htmlspecialchars($url) . "</b><br><br>";

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "Authorization: Bearer " . $token,
    "Content-Type: application/json"
));

$result = curl_exec($ch);
$error = curl_error($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "<h3>HTTP Status Code dari VPS: " . $httpCode . "</h3>";

if ($error) {
    echo "<h3>Error Jaringan cURL:</h3>" . htmlspecialchars($error);
} else {
    echo "<h3>Hasil Respon Backend:</h3>";
    echo "<pre>" . htmlspecialchars($result) . "</pre>";
}
?>