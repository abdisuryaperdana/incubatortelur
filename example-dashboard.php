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
    <title>Antares GET/POST</title> 
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <style>
    .container {
      padding-top:100px;
    }
    table {
      border-collapse: collapse;
      width: 100%;
    }
    th, td {
      padding: 8px;
      text-align: left;
      border-bottom: 1px solid #ddd;
    }
    h1 {
      text-align: center;
    }
    input { 
      text-align: center; 
    }
    </style>
  </head> 
  <body>
  <!-- <center><h1>Setpoint saat ini: 
    <?php
      try {
        // Assume Antares::getInstance() is your Antares instance
        $cnt = Antares::getInstance()->get('/antares-cse/antares-id/mdhkrmd-project/inkubator');

        // Get the latest content instance
        $latestInstance = $cnt->getLatestContentInstace();
        echo $latestInstance->con;
        header("example-dashboard.php");
    } catch (Exception $e) {
        echo "<p>Error: " . $e->getMessage() . "</p>";
    }
    ?>
  </h1></center> -->

  <br>

  <center>
    <!-- <h2>Insert Data</h2>
      <?php
          if ($_SERVER["REQUEST_METHOD"] == "POST") {
              try {
                  // Assume Antares::getInstance() is your Antares instance
                  $cnt = Antares::getInstance()->get('/antares-cse/antares-id/mdhkrmd-project/inkubator');
                  
                  // Create the JSON structure
                  $data = json_encode(['setpoint' => $_POST['setpoint']]);
                  
                  // Insert the content instance
                  $cnt->insertContentInstance($data, 'application/json');
                  echo "<p>Data inserted successfully.</p>";
              } catch (Exception $e) {
                  echo "<p>Error inserting data: " . $e->getMessage() . "</p>";
              }
          }
      ?> -->
      <!-- <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
          <label for="setpoint">Setpoint:</label>
          <input type="number" name="setpoint" placeholder="Input setpoint baru" required>
          <br>
          <input type="submit" value="Insert Data">
      </form> -->
    </center>

    <div class="container">
      <div class="row">        
        <div class="col-md-12">
          <table>
            <tr>
              <th>Time (WIB)</th>
              <!-- <th>Resource Index (ri)</th> -->
              <th>Data</th>
            </tr>
            <?php
            try {
              $resp = Antares::getInstance()->get('/antares-cse/antares-id/mdhkrmd-project/inkubator'); // TODO: Change this to your container uri
              // example /antares-cse/antares-id/Ursalink/EM500-PT100-232877
              $first10 = $resp->listContentInstanceUris(5); //total data request
              foreach ($first10 as $uri) {
              $payload = Antares::getInstance()->get($uri);
              echo "<tr>";
                echo "<td>";
                  $date=strtotime($payload->getCreationTime());
                  echo date('Y-m-d h:i:s', $date);
                echo "</td>";
                // echo "<td>";
                //   $resuri=$payload->ri;
                //   echo $resuri;
                // echo "</td>";
                echo "<td>";
                  $data=json_decode($payload->getContent());
                  $encoded = json_encode($data, JSON_PRETTY_PRINT);
                  echo "<pre>".$encoded."<pre/>";
                echo "</td>";
              echo "</tr>";
              }
            }
          catch (Exception $e) {
            echo($e->getMessage());
          }
            ?>
            </td>
          </table>         
        </div>
      </div>
    </div>  
    <br>
    <br>

    

  </body> 
</html>