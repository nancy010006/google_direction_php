<?php
	require_once("key.php");
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title></title>
	<style type="text/css">
		#map{
			height: 90vh;
			width: 90vw;
		}
	</style>
</head>
<body>
<?php
$des = $_GET["des"];
$ori = $_GET["ori"];
?>
<iframe id="map" frameborder="0" style="border:0" src="https://www.google.com/maps/embed/v1/directions?origin=<?php echo $ori ?>&destination=<?php echo $des?>&key=<?php echo $key ?>" allowfullscreen>
	
</iframe>
<!-- <iframe width="600" height="450" frameborder="0" style="border:0" src="http://maps.googleapis.com/maps/api/geocode/json?latlng=25.047908,121.517315&sensor=false&language=zh-tw allowfullscreen">
	
</iframe> -->

</body>
</html>
