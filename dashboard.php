<?php
require_once('./Antares.php');

Antares::init([
  "PLATFORM_URL" => 'https://platform.antares.id:8443', // TODO: Change this to your platform URL
  // URL for Peruri On Premise https://iot.peruri.co.id:8443
  "ACCESS_KEY" => '2943a4cc68ecba07:99a731b711abe367' // TODO: Change this to your access key
  //abcdefgh12345678:abcdefgh12345678
]);
?>
<!DOCTYPE html> 
<html lang="en">
<head>
  <meta charset="utf-8" http-equiv="refresh" content="5"/>
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="assets/img/favicon.png">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <link href="assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="assets/css/nucleo-svg.css" rel="stylesheet" />
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <link id="pagestyle" href="assets/css/argon-dashboard.css?v=2.0.4" rel="stylesheet" />
  <script src="https://cdn.anychart.com/releases/8.11.0/js/anychart-base.min.js"></script>

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
    <!-- End Navbar -->
    <div class="container-fluid py-4">
      <div class="row">
      <div class="col-xl-6 col-sm-12 mb-xl-0 mb-4">
          <div class="card">
              <div class="card-body p-3">
                  <div class="row">
                      <div class="col-8">
                          <div class="numbers">
                              <p class="text-sm mb-0 text-uppercase font-weight-bold">Latest Temperature</p>
                              <?php
                              try {
                                  // Assume Antares::getInstance() is your Antares instance
                                  $cnt = Antares::getInstance()->get('/antares-cse/antares-id/mdhkrmd-project/inkubator');

                                  // Get the latest content instance
                                  $latestInstance = $cnt->getLatestContentInstace();
                                  $data = json_decode($latestInstance->con);

                                  // Get the latest temperature value
                                  $temperature = property_exists($data, 'temperature') ? $data->temperature : 'N/A';

                                  // Display the latest temperature value
                                  echo "<h5 class='font-weight-bolder'>$temperature</h5>";
                              } catch (Exception $e) {
                                  echo "<p>Error: " . $e->getMessage() . "</p>";
                              }
                              ?>
                          </div>
                      </div>
                      <div class="col-4 text-end">
                          <div class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle">
                              <i class="ni ni-money-coins text-lg opacity-10" aria-hidden="true"></i>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>


      <div class="col-xl-6 col-sm-12 mb-xl-0 mb-4">
          <div class="card">
              <div class="card-body p-3">
                  <div class="row">
                      <div class="col-8">
                          <div class="numbers">
                              <p class="text-sm mb-0 text-uppercase font-weight-bold">Latest Humidity</p>
                              <?php
                              try {
                                  // Assume Antares::getInstance() is your Antares instance
                                  $cnt = Antares::getInstance()->get('/antares-cse/antares-id/mdhkrmd-project/inkubator');

                                  // Get the latest content instance
                                  $latestInstance = $cnt->getLatestContentInstace();
                                  $data = json_decode($latestInstance->con);

                                  // Get the latest humidity value
                                  $humidity = property_exists($data, 'humidity') ? $data->humidity : 'N/A';

                                  // Display the latest humidity value
                                  echo "<h5 class='font-weight-bolder'>$humidity</h5>";
                              } catch (Exception $e) {
                                  echo "<p>Error: " . $e->getMessage() . "</p>";
                              }
                              ?>
                          </div>
                      </div>
                      <div class="col-4 text-end">
                          <div class="icon icon-shape bg-gradient-danger shadow-danger text-center rounded-circle">
                            <i class="ni ni-world text-lg opacity-10" aria-hidden="true"></i>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>

      </div>

      <?php
        try {
            $resp = Antares::getInstance()->get('/antares-cse/antares-id/mdhkrmd-project/inkubator');
            $first10 = $resp->listContentInstanceUris(5);

            $temperatureData = array();

            foreach ($first10 as $uri) {
                $payload = Antares::getInstance()->get($uri);

                // Tanggal
                $date = strtotime($payload->getCreationTime());
                $formattedDate = date('Y-m-d h:i:s', $date);

                // Data JSON
                $data = json_decode($payload->getContent());

                // Menangani properti suhu
                $temperature = property_exists($data, 'temperature') ? $data->temperature : 'N/A';

                // Add data to the array
                $temperatureData[] = array($formattedDate, $temperature);
            }

        } catch (Exception $e) {
            echo ($e->getMessage());
        }
        ?>

        <?php
          try {
              $resp = Antares::getInstance()->get('/antares-cse/antares-id/mdhkrmd-project/inkubator');
              $first10 = $resp->listContentInstanceUris(5);

              $humidityData = array();

              foreach ($first10 as $uri) {
                  $payload = Antares::getInstance()->get($uri);

                  // Tanggal
                  $date = strtotime($payload->getCreationTime());
                  $formattedDate = date('Y-m-d h:i:s', $date);

                  // Data JSON
                  $data = json_decode($payload->getContent());

                  // Menangani properti suhu
                  $humidity = property_exists($data, 'humidity') ? $data->humidity : 'N/A';

                  // Add data to the array
                  $humidityData[] = array($formattedDate, $humidity);
              }

          } catch (Exception $e) {
              echo ($e->getMessage());
          }
        ?>

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

      <br>
      <br>

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
                <tbody>
                    <?php
                      try {
                          $resp = Antares::getInstance()->get('/antares-cse/antares-id/mdhkrmd-project/inkubator');
                          $first10 = $resp->listContentInstanceUris(5);

                          echo '<tbody id="temperatureTableBody">'; // Add an ID to the table body
                          foreach ($first10 as $uri) {
                              $payload = Antares::getInstance()->get($uri);
                              echo "<tr>";

                              // Tanggal
                              echo "<td class='align-middle text-left'>";
                              $date = strtotime($payload->getCreationTime());
                              echo "<span class='text-secondary text-xs font-weight-bold'>" . date('Y-m-d h:i:s', $date) . "</span>";
                              echo "</td>";

                              // Data JSON
                              $data = json_decode($payload->getContent());

                              // Menangani properti suhu
                              $temperature = property_exists($data, 'temperature') ? $data->temperature : 'N/A';
                              echo "<td class='align-middle text-left'>";
                              echo "<span class='text-secondary text-xs font-weight-bold'>" . $temperature . "</span>";
                              echo "</td>";

                              // Menangani properti kelembapan
                              $humidity = property_exists($data, 'humidity') ? $data->humidity : 'N/A';
                              echo "<td class='align-middle text-left'>";
                              echo "<span class='text-secondary text-xs font-weight-bold'>" . $humidity . "</span>";
                              echo "</td>";

                              echo "</tr>";
                          }
                          echo '</tbody>';
                      } catch (Exception $e) {
                          echo ($e->getMessage());
                      }
                      ?>

                </tbody>
            </table>
        </div>
    </div>

  </main> 
</body>
  <!--   Core JS Files   -->
  <script src="assets/js/core/popper.min.js"></script>
  <script src="assets/js/core/bootstrap.min.js"></script>
  <script src="assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="assets/js/plugins/smooth-scrollbar.min.js"></script>
  <script src="assets/js/plugins/chartjs.min.js"></script>
  <script>
        anychart.onDocumentReady(function () {
            // PHP data
            var phpData = <?php echo json_encode($temperatureData); ?>;

            // create a data set
            var dataSet = anychart.data.set(phpData);

            // map the data for the series
            var seriesData = dataSet.mapAs({ x: 0, value: 1 });

            // create a line chart
            var chart = anychart.line();

            // create the series and name it
            var series = chart.line(seriesData);
            series.name("Temperature");

            // add a legend
            chart.legend().enabled(true);

            // specify where to display the chart
            chart.container("chartTemp");

            // draw the resulting chart
            chart.draw();
        });
    </script>

<script>
        anychart.onDocumentReady(function () {
            // PHP data
            var phpData = <?php echo json_encode($humidityData); ?>;

            // create a data set
            var dataSet = anychart.data.set(phpData);

            // map the data for the series
            var seriesData = dataSet.mapAs({ x: 0, value: 1 });

            // create a line chart
            var chart = anychart.line();

            // create the series and name it
            var series = chart.line(seriesData);
            series.name("Humidity");

            // add a legend
            chart.legend().enabled(true);

            // specify where to display the chart
            chart.container("chartHumd");

            // draw the resulting chart
            chart.draw();
        });
    </script>

  <script>
document.addEventListener('DOMContentLoaded', function () {
    // Ambil elemen canvas untuk grafik suhu
    var temperatureCtx = document.getElementById('chart-temperature').getContext('2d');
    var temperatureChart = createChart(temperatureCtx, 'Temperature', 'rgba(255, 99, 132, 1)');

    // Ambil elemen canvas untuk grafik kelembapan
    var humidityCtx = document.getElementById('chart-humidity').getContext('2d');
    var humidityChart = createChart(humidityCtx, 'Humidity', 'rgba(75, 192, 192, 1)');

    function createChart(ctx, label, borderColor) {
        return new Chart(ctx, {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: label,
                    data: [],
                    borderColor: borderColor,
                    borderWidth: 2,
                    fill: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        type: 'linear',
                        position: 'bottom'
                    },
                    y: {
                        min: 0,
                        max: 100,
                    }
                }
            }
        });
    }

    function addData(chart, label, data) {
        chart.data.labels.push(label);
        chart.data.datasets[0].data.push(data);
        chart.update();
    }

    // Panggil fungsi fetchDataAndAddToChart setiap beberapa detik (sesuaikan sesuai kebutuhan)
    setInterval(function () {
        // Fetch and add dummy data to the temperature chart
        addData(temperatureChart, new Date().toLocaleTimeString(), Math.random() * 100);

        // Fetch and add dummy data to the humidity chart
        addData(humidityChart, new Date().toLocaleTimeString(), Math.random() * 100);
    }, 5000); // Contoh: ambil data setiap 5 detik
});


</script>

  