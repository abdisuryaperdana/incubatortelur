<?php
require_once('./Antares.php');

// Fungsi untuk mengirimkan data ke client
function sendUpdate($data) {
    echo "data: $data\n\n";
    ob_flush();
    flush();
}

// Inisialisasi Antares
Antares::init([
    "PLATFORM_URL" => 'https://platform.antares.id:8443',
    "ACCESS_KEY" => '2943a4cc68ecba07:99a731b711abe367'
]);

// Set header untuk Server-Sent Events
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
header('Connection: keep-alive');

try {
    while (true) {
        // Ambil data terbaru dari Antares
        $cnt = Antares::getInstance()->get('/antares-cse/antares-id/mdhkrmd-project/inkubator');
        $latestInstance = $cnt->getLatestContentInstace();
        $data = json_decode($latestInstance->con);

        // Ambil suhu dan kelembapan
        $temperature = property_exists($data, 'temperature') ? $data->temperature : 'N/A';
        $humidity = property_exists($data, 'humidity') ? $data->humidity : 'N/A';

        // Kirim data ke client
        sendUpdate(json_encode(['temperature' => $temperature, 'humidity' => $humidity]));

        // Tunggu beberapa detik sebelum mengambil data lagi
        sleep(5); // Sesuaikan dengan kebutuhan Anda
    }
} catch (Exception $e) {
    // Tangani kesalahan jika terjadi
    sendUpdate(json_encode(['error' => $e->getMessage()]));
}

ob_end_flush();
?>
