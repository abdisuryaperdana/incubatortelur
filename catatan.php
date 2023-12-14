<?php
require_once('./Antares.php');

Antares::init([
  "PLATFORM_URL" => 'https://platform.antares.id:8443',
  "ACCESS_KEY" => '2943a4cc68ecba07:99a731b711abe367'
]);

try {
  $resp = Antares::getInstance()->get('/antares-cse/antares-id/mdhkrmd-project/inkubator');
  $first10 = $resp->listContentInstanceUris(5);

  $temperatureData = array();
  $humidityData = array();

  foreach ($first10 as $uri) {
    $payload = Antares::getInstance()->get($uri);
    $date = strtotime($payload->getCreationTime());
    $formattedDate = date('Y-m-d h:i:s', $date);
    $data = json_decode($payload->getContent());

    $temperature = property_exists($data, 'temperature') ? $data->temperature : 'N/A';
    $humidity = property_exists($data, 'humidity') ? $data->humidity : 'N/A';

    $temperatureData[] = array($formattedDate, $temperature);
    $humidityData[] = array($formattedDate, $humidity);
  }
} catch (Exception $e) {
  echo ($e->getMessage());
}
?>

<!DOCTYPE html> 
<html lang="id">

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="assets/img/favicon.png">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <link href="assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="assets/css/nucleo-svg.css" rel="stylesheet" />
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <link id="pagestyle" href="assets/css/argon-dashboard.css?v=2.0.4" rel="stylesheet" />
  <script src="https://cdn.anychart.com/releases/8.11.0/js/anychart-base.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

  <title>Antares GET/POST</title>
  <script src="https://code.highcharts.com/highcharts.js"></script> 
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <style type="text/css">      
    html, body, #chartTemp, #chartHumd { 
      width: 100%; height: 100%; margin: 0; padding: 0; 
    } 
  </style>
</head>


<body>
<div class="min-height-300 bg-primary position-absolute w-100"></div>
  <main class="main-content position-relative border-radius-lg ">
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl " id="navbarBlur" data-scroll="false">
      <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white" href="javascript:;">Proyek</a></li>
            <li class="breadcrumb-item text-sm text-white active" aria-current="page">Sistem Tertanam</li>
          </ol>
          <h4 class="font-weight-bolder text-white mb-0">Inkubator Telur</h4>
        </nav>
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
        </div>
      </div>
    </nav>

  <!-- Latest Temperature Card -->
  <div class="container-fluid py-4">
  <div class="row">
  <!-- Temperature Chart -->
<div class="row mt-4">
  <div class="col-lg-6 mb-lg-0 mb-4">
    <div class="card z-index-2 h-100">
      <div class="card-header pb-0 pt-3 bg-transparent">
        <h6 class="text-capitalize">Temperature</h6>
      </div>
      <div class="card-body p-3">
        <div id="chartTemp"></div>
      </div>
    </div>
  </div>

  <!-- Humidity Chart -->
  <div class="col-lg-6 mb-lg-0 mb-4">
    <div class="card z-index-2 h-100">
      <div class="card-header pb-0 pt-3 bg-transparent">
        <h6 class="text-capitalize">Humidity</h6>
      </div>
      <div class="card-body p-3">
        <div id="chartHumd"></div>
      </div>
    </div>
  </div>
</div>

  </div>
  </div>

  <!-- Data Table -->
  <div class="row">
    <div class="col-md-12">
      <table class="table align-items-center mb-0 custom-table">
        <thead>
          <tr>
            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Waktu</th>
            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Suhu</th>
            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Kelembapan</th>
          </tr>
        </thead>
        <tbody id="temperatureTableBody">
          <?php
          foreach ($first10 as $uri) {
            $payload = Antares::getInstance()->get($uri);
            echo "<tr>";
            $date = strtotime($payload->getCreationTime());
            echo "<td class='align-middle text-left'>";
            echo "<span class='text-secondary text-xs font-weight-bold'>" . date('Y-m-d H:i:s', $date) . "</span>";
            echo "</td>";
            $data = json_decode($payload->getContent());
            $temperature = property_exists($data, 'temperature') ? $data->temperature : 'N/A';
            echo "<td class='align-middle text-left'>";
            echo "<span class='text-secondary text-xs font-weight-bold'>" . $temperature . "</span>";
            echo "</td>";
            $humidity = property_exists($data, 'humidity') ? $data->humidity : 'N/A';
            echo "<td class='align-middle text-left'>";
            echo "<span class='text-secondary text-xs font-weight-bold'>" . $humidity . "</span>";
            echo "</td>";
            echo "</tr>";
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Footer scripts -->
  <script src="assets/js/core/popper.min.js"></script>
  <script src="assets/js/core/bootstrap.min.js"></script>
  <script src="https://cdn.anychart.com/releases/8.11.0/js/anychart-base.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  <!-- Include other necessary scripts -->
  <script src="https://code.highcharts.com/highcharts.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <!-- Your custom scripts -->
  <!-- Your custom scripts -->
<script>
  // Temperature Chart
  Highcharts.chart('chartTemp', {
    chart: {
      type: 'spline',
    },
    title: {
      text: 'Temperature Chart',
    },
    xAxis: {
      type: 'datetime',
    },
    yAxis: {
      title: {
        text: 'Temperature (°C)',
      },
    },
    series: [{
      name: 'Temperature',
      data: <?php echo json_encode($temperatureData); ?>,
    }],
  });

  // Humidity Chart
  Highcharts.chart('chartHumd', {
    chart: {
      type: 'spline',
    },
    title: {
      text: 'Humidity Chart',
    },
    xAxis: {
      type: 'datetime',
    },
    yAxis: {
      title: {
        text: 'Humidity (%)',
      },
    },
    series: [{
      name: 'Humidity',
      data: <?php echo json_encode($humidityData); ?>,
    }],
  });
</script>
<!-- Your custom scripts -->
<!-- Your custom scripts -->
<!-- Your custom scripts -->
<script>
  function updateData() {
    // Gunakan AJAX untuk memperbarui data dari server
    $.ajax({
      url: 'sse.php', // Ganti dengan path yang benar
      method: 'GET',
      success: function(data) {
        // Perbarui data grafik dan tabel
        processData(data);
      },
      error: function(error) {
        console.error('Error updating data:', error);
      }
    });
  }

  function updateTemperatureChart(newData) {
    // Perbarui grafik suhu dengan data terbaru
    Highcharts.charts[0].series[0].setData(newData, true, true, true);
  }

  function updateHumidityChart(newData) {
    // Perbarui grafik kelembapan dengan data terbaru
    Highcharts.charts[1].series[0].setData(newData, true, true, true);
  }

  function updateTable(newData) {
    // Perbarui tabel dengan data terbaru
    // Hapus data yang sudah ada
    $("#temperatureTableBody").empty();

    // Tambahkan data baru
    newData.forEach(function(entry) {
      var formattedDate = entry[0];
      var temperature = entry[1];
      var humidity = entry[2];

      var row = "<tr>" +
                  "<td class='align-middle text-left'>" +
                    "<span class='text-secondary text-xs font-weight-bold'>" + formattedDate + "</span>" +
                  "</td>" +
                  "<td class='align-middle text-left'>" +
                    "<span class='text-secondary text-xs font-weight-bold'>" + temperature + "</span>" +
                  "</td>" +
                  "<td class='align-middle text-left'>" +
                    "<span class='text-secondary text-xs font-weight-bold'>" + humidity + "</span>" +
                  "</td>" +
                "</tr>";

      $("#temperatureTableBody").append(row);
    });
  }

  function processData(data) {
    // Proses data yang diterima dari server
    var newData = [];

    // Implementasikan cara memproses data yang sesuai dengan struktur data dari server
    // Misalnya, jika data adalah array objek dengan properti 'date', 'temperature', dan 'humidity':
    for (var i = 0; i < data.length; i++) {
      var entry = [
        data[i].date,
        data[i].temperature,
        data[i].humidity
      ];
      newData.push(entry);
    }

    // Panggil fungsi-fungsi update
    updateTemperatureChart(newData);
    updateHumidityChart(newData);
    updateTable(newData);
  }

  // Panggil fungsi updateData setiap 5 detik
  setInterval(updateData, 5000);
</script>

</body>

</html>
