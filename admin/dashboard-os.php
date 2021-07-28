<?php require_once('../Connections/saha.php'); ?>
<?php require_once('../inc-main.php'); ?>
<?php 
header('Content-Type: text/html; charset=utf-8');
?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

   // $theValue = function_exists("mysqli_real_escape_string") ? mysqli_real_escape_string($saha,$theValue) : mysqli_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

if (!isset($_SESSION)) {
  session_start();
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>

<!-- Chart -->
<script src="chart/Chart.bundle.js"></script>
<script src="chart/utils.js"></script>
    <style>
    canvas {
        -moz-user-select: none;
        -webkit-user-select: none;
        -ms-user-select: none;
    }
    </style>
<!-- End Chart -->
</head>

<body>
    <div id="canvas-holder" style="width:100%">
        <canvas id="chart-area" />
    </div>

<script>
    var randomScalingFactor = function() {
        return Math.round(Math.random() * 100);
    };

    var config = {
        type: 'pie',
        data: {
            datasets: [{
                data: [
<?php
mysqli_select_db($saha, $database_saha);
$query_selectedURL = "SELECT * FROM url_views GROUP BY url_views.os";
$selectedURL = mysqli_query($saha, $query_selectedURL) or die(mysqli_error($saha));
$row_selectedURL = mysqli_fetch_assoc($selectedURL);
$totalRows_selectedURL = mysqli_num_rows($selectedURL);

$i=0;
 do{
$i++;	
$BROWSER=$row_selectedURL['os'];

mysqli_select_db($saha, $database_saha);
$query_selectedURL1 = "SELECT * FROM url_views WHERE url_views.os='$BROWSER'";
$selectedURL1 = mysqli_query($saha, $query_selectedURL1) or die(mysqli_error($saha));
$row_selectedURL1 = mysqli_fetch_assoc($selectedURL1);
$totalRows_selectedURL1 = mysqli_num_rows($selectedURL1);
 
	 ?>
                    <?php echo $totalRows_selectedURL1; ?><?php if($i!=$totalRows_selectedURL){?>,<?php }?>
<?php } while ($row_selectedURL = mysqli_fetch_assoc($selectedURL));?>
                ],
                backgroundColor: [
                    window.chartColors.red,
                    window.chartColors.orange,
                    window.chartColors.yellow,
                    window.chartColors.green,
                    window.chartColors.blue,
                ],
                label: 'Dataset 1'
            }],
            labels: [
<?php 
mysqli_select_db($saha, $database_saha);
$query_selectedURL = "SELECT * FROM url_views GROUP BY url_views.os";
$selectedURL = mysqli_query($saha, $query_selectedURL) or die(mysqli_error($saha));
$row_selectedURL = mysqli_fetch_assoc($selectedURL);
$totalRows_selectedURL = mysqli_num_rows($selectedURL);

$i=0;
 do{
$i++;	 
?>
                "<?php echo $row_selectedURL['os']; ?>"<?php if($i!=$totalRows_selectedURL){?>,<?php }?>
<?php } while ($row_selectedURL = mysqli_fetch_assoc($selectedURL));?>
            ]
        },
        options: {
            responsive: true
        }
    };

    window.onload = function() {
        var ctx = document.getElementById("chart-area").getContext("2d");
        window.myPie = new Chart(ctx, config);
    };

    document.getElementById('randomizeData').addEventListener('click', function() {
        config.data.datasets.forEach(function(dataset) {
            dataset.data = dataset.data.map(function() {
                return randomScalingFactor();
            });
        });

        window.myPie.update();
    });

    var colorNames = Object.keys(window.chartColors);
    document.getElementById('addDataset').addEventListener('click', function() {
        var newDataset = {
            backgroundColor: [],
            data: [],
            label: 'New dataset ' + config.data.datasets.length,
        };

        for (var index = 0; index < config.data.labels.length; ++index) {
            newDataset.data.push(randomScalingFactor());

            var colorName = colorNames[index % colorNames.length];;
            var newColor = window.chartColors[colorName];
            newDataset.backgroundColor.push(newColor);
        }

        config.data.datasets.push(newDataset);
        window.myPie.update();
    });

    document.getElementById('removeDataset').addEventListener('click', function() {
        config.data.datasets.splice(0, 1);
        window.myPie.update();
    });
    </script>

</body>
</html>