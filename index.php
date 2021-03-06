<?php
include "weather_data.php";
?>

<html>
	<head>
		<title>Chance to Rain</title>
		<meta charset="UTF-8">
  		<meta name="description" content="The Chance of Rain App provides you a summary of today's weather and may suggest you whether to bring an umbrella or put on your sunscreen or not today.">
  		<meta name="keywords" content="Weather Forecast, Chance to Rain, PM2.5, UV Index">
  		<meta name="author" content="Daniel (RainRush) Hu">
  		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="shortcut icon" type="image/x-icon" href="images/favicon.ico"/>
		<link rel="stylesheet" href="https://hyhu.me/weather/css/styles.css">
		<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
	</head>
	<body>
		<div class="leading">
        	<p class="leading-bigtext">Chance to Rain</p>
      	</div>
		<div class="cards">
  			<div class="grid-item">
				<p>Location: <?php echo $city; ?></p>
				<p>Current Temperature: <?php echo round($temperature,1)?>&#8451</p>
				<p>Chance of Rain Today: <?php echo ($precipProbability*100)?>%</p>
				<p>Highest Temperature will be <?php echo round($todayTempHigh,1)?>&#8451 at <?php echo $highestTime?>:00</p>
				<p>The Temperature at 8:00 will be <?php echo round($eightAmTemp,1)?>&#8451</p>
				<p>The Temperature at 20:00 will be <?php echo round($eightPmTemp,1)?>&#8451</p>
				<p><?php echo $dailySummary?></p>
  			</div>
  			<div class="grid-item">
  				<canvas id="sevenDayTemp" width="500" height="350"></canvas>
  			</div>
  			<div class="grid-item">
  				<p>Current UV Index: <?php echo $currentUv ?>(<?php echo $curUvDes ?>)</p>
  				<p>Today's highest UV Index: <?php echo $todayHighestUv ?>(<?php echo $todayHighUvDes ?>)</p>
  				<canvas id="sevenDayUV" width="500" height="350"></canvas>
  			</div>  
  			<div class="grid-item">
  				<p>Most recent PM2.5: <?php echo $pm25 ?>(<?php echo $pmscale ?>)</p>
  			</div>
		</div>
	</body>

<script>
  	var ctx1 = document.getElementById( "sevenDayTemp" ),
  		temperature1 = new Chart(ctx1, {
  			type: "line", // chart type
  			data: {
  				labels: <?php echo json_encode($weekday) ?>,
        		datasets: [{
            		label: 'Highest Temperature (°C)',
            		fill: false,
            		backgroundColor: 'rgb(255, 99, 132)',
            		borderColor: 'rgb(255, 99, 132)',
            		data: <?php echo json_encode($weeklyHighTemp) ?>,
            		yAxisID: 'y-axis-1'
        		}, {
            		label: 'Lowest Temperature (°C)',
            		fill: false,
            		backgroundColor: 'rgb(60, 163, 232)',
            		borderColor: 'rgb(60, 163, 232)',
            		data: <?php echo json_encode($weeklyLowTemp) ?>,
            		yAxisID: 'y-axis-1'
        		}, {
            		label: 'Chance To Rain (%)',
            		fill: false,
            		backgroundColor: 'rgb(255, 204, 97)',
            		borderColor: 'rgb(255, 204, 97)',
            		data: <?php echo json_encode($weeklyChanceToRain) ?>,
            		yAxisID: 'y-axis-2'
        		}]
  			},
  			options: {
  				responsive: true,
  				scales: {
					yAxes: [{
						type: 'linear', // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
						display: true,
						position: 'left',
						id: 'y-axis-1',
					}, {
						type: 'linear', // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
						display: true,
						position: 'right',
						id: 'y-axis-2',
						ticks: {
							min: 0,
							max: 100
						},
						// grid line settings
						gridLines: {
							drawOnChartArea: false, // only want the grid lines for one axis to show up
						},
					}],
				}
  			}
  		});

	function colorize(data) {
			colorArray = [];
			for (var i in data){
				var v = data[i];
				var c = v < 2 ? 'rgb(82, 192, 191)'
					: v < 5 ? 'rgb(255, 204, 97)'
					: v < 7 ? 'rgb(253, 158, 74)'
					: v < 10? 'rgb(255, 99, 132)'
					: 'rgb(153, 108, 251)';	
				colorArray.push(c);
			}
			
			return colorArray;
		}

	var weekUV = <?php echo json_encode($weeklyUV) ?>;

  	var ctx2 = document.getElementById( "sevenDayUV" ),
  		uv2 = new Chart(ctx2, {
  			type: "bar", // chart type
  			data: {
  				labels: <?php echo json_encode($weekday) ?>,
        		datasets: [{
            		data: weekUV,
            		backgroundColor: colorize(weekUV),
        		}]
  			},
  			options: {
  				legend: false,
  				scales: {
  					yAxes: [{
  						display: true,
  						ticks: {
  							beginAtZero: true
  						}
  					}]
  				},
  			}
  		});
</script>
</html>