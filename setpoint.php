<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Data yang akan dikirim
    $data = [
        "m2m:cin" => [
            "con" => json_encode(["setpoint" => $_POST["setpoint"]])
        ]
    ];

    // Convert data ke format JSON
    $jsonData = json_encode($data);

    // URL tujuan
    $url = "https://platform.antares.id:8443/~/antares-cse/cnt-vq61Tfp8PyosvKBb/";

    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    // Header yang diperlukan
    $headers = [
        "Access-Control-Allow-Origin: *",
        "Access-Control-Allow-Methods: POST",
        "Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With",
        "X-M2M-Origin: 2943a4cc68ecba07:99a731b711abe367",
        "Content-Type: application/json;ty=4",
        "Accept: application/json"
    ];

    // Konfigurasi request
    $options = [
        "http" => [
            "header" => implode("\r\n", $headers),
            "method" => "POST",
            "content" => $jsonData
        ]
    ];

    // Buat context stream
    $context = stream_context_create($options);

    // Kirim request
    $result = file_get_contents($url, false, $context);

    // Handle hasil respons jika perlu
    if ($result === FALSE) {
        echo "Error sending data";
    } else {
        echo "Data sent successfully!";
    }
} else {
    // Menunjukkan bahwa metode HTTP yang digunakan tidak diizinkan
    http_response_code(405);
    echo "Method Not Allowed";
}
?>
