<?php
	require_once("key.php");
	$des = $_GET["des"];
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title></title>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
	<style type="text/css">
		#map{
			height: 90vh;
			width: 90vw;
		}
		.spinner {
		  width: 40px;
		  height: 40px;
		  background-color: #333;

		  margin: 100px auto;
		  -webkit-animation: sk-rotateplane 1.2s infinite ease-in-out;
		  animation: sk-rotateplane 1.2s infinite ease-in-out;
		}

		@-webkit-keyframes sk-rotateplane {
		  0% { -webkit-transform: perspective(120px) }
		  50% { -webkit-transform: perspective(120px) rotateY(180deg) }
		  100% { -webkit-transform: perspective(120px) rotateY(180deg)  rotateX(180deg) }
		}

		@keyframes sk-rotateplane {
		  0% { 
		    transform: perspective(120px) rotateX(0deg) rotateY(0deg);
		    -webkit-transform: perspective(120px) rotateX(0deg) rotateY(0deg) 
		  } 50% { 
		    transform: perspective(120px) rotateX(-180.1deg) rotateY(0deg);
		    -webkit-transform: perspective(120px) rotateX(-180.1deg) rotateY(0deg) 
		  } 100% { 
		    transform: perspective(120px) rotateX(-180deg) rotateY(-179.9deg);
		    -webkit-transform: perspective(120px) rotateX(-180deg) rotateY(-179.9deg);
		  }
		}
	</style>
</head>
<body>
<div class="spinner"></div>
<iframe id="map" frameborder="0" style="border:0" src="" allowfullscreen>
	
</iframe>
<!-- <iframe width="600" height="450" frameborder="0" style="border:0" src="http://maps.googleapis.com/maps/api/geocode/json?latlng=25.047908,121.517315&sensor=false&language=zh-tw allowfullscreen">
	
</iframe> -->
	<script>
	 	$(document).ready(function() {
	 		loadingEffect();
	 		getLocation();
	 	});

	    function getLocation() {//取得 經緯度
	        if (navigator.geolocation) {//
	            navigator.geolocation.getCurrentPosition(showPosition);//有拿到位置就呼叫 showPosition 函式
	        } else { 
	            console.log("您的瀏覽器不支援 顯示地理位置 API ，請使用其它瀏覽器開啟 這個網址");
	        }
	    }
	    
	    function showPosition(position) {
	    	$.ajax({
				url: 'https://maps.googleapis.com/maps/api/geocode/json?latlng='+position.coords.latitude+','+position.coords.longitude+'&sensor=false&language=zh-tw',
				type: 'GET',
				dataType: 'json',
			})
			.done(function(data) {
				const ori = data.results[0].formatted_address;
				$("#map").attr('src', 'https://www.google.com/maps/embed/v1/directions?origin='+ori+'&destination=<?php echo $des?>&key=<?php echo $key ?>');
			})
			.fail(function() {
				console.log("error");
			})
			.always(function() {
				console.log("complete");
			});
	    }

	    function loadingEffect() {
            var loading = $('.spinner');
            $(document).ajaxStart(function () {
                loading.show();
            }).ajaxStop(function () {
                loading.hide();
            });
        }
	</script>
</body>
</html>
