<?php
 
$dataPoints = array();
$y = 5;
for($i = 0; $i < 10; $i++){
	$y += rand(-1, 3) * 1000000.1; 
	array_push($dataPoints, array("x" => $i, "y" => $y));
}
 
?>
<!DOCTYPE HTML>
<html>
<head>
<script>
window.onload = function() {
 
var dataPoints = <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>;
 
var chart = new CanvasJS.Chart("chartContainer", {
	theme: "light2",
	title: {
		text: "The usles graph"
	},
	axisX:{
		title: "Time in millisecond"
	},
	axisY:{
		suffix: " data"
	},
	data: [{
		type: "line",
		yValueFormatString: "#,##0.0#",
		toolTipContent: "{y} data(totaly not a random number)",
		dataPoints: dataPoints
	}]
});
chart.render();
 
var updateInterval = 1500;
setInterval(function () { updateChart() }, updateInterval);
 
var xValue = dataPoints.length;
var yValue = dataPoints[dataPoints.length - 1].y;
 
function updateChart() {
	yValue += (Math.random() - 0.5) * 1000000.1;
	dataPoints.push({ x: xValue, y: yValue });
	xValue++;
	chart.render();
};
 
}
</script>
</head>
<body>
<div id="chartContainer" style="height: 370px; width: 100%;"></div>
<script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
</body>
</html> 