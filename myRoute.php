<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
    <title></title>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
</head>

<body>
    <form id="desForm">
	    <ul id="ul" class="list-group mb-3">
	    	<!-- 原始碼 -->
		    <!-- <li class="list-group-item d-flex justify-content-between lh-condensed">
	      		<div>
	        		<h6 class="my-0">Product name</h6>
					<a class="btn btn-sm btn-primary" href="../../components/navbar/" role="button">開始導航 »</a>
	      		</div>
		    </li> -->
	  	</ul>
	  	<input type="submit">
    </form>
    <script type="text/javascript">
   		let latitude;
		let longitude;
		const account = '<?php echo $_GET["account"]?>';
		const map = '<?php echo $_GET["map"]?>';
    	$(document).ready(function() {
    		setForm();
    		getLocation();
    		generateView();
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
				async:false
			})
			.done(function(){
				latitude = position.coords.latitude;
				longitude = position.coords.longitude;
			})
			.always(function() {
				console.log("complete");
			});
	    }

	    function setForm(){
	    	$("#desForm").submit(function(event) {
    			event.preventDefault();
    			$.ajax({
    				url: './handler.php',
    				type: 'POST',
    				dataType: 'json',
    				data: {act:'put',account:account,map:map,data:$("#desForm").serializeArray()},
    			})
    			.done(function(data) {
    				console.log(data);
    				history.go(0);
    			})
    			.fail(function() {
    				console.log("error");
    			})
    			.always(function() {
    				console.log("complete");
    			});
    			
    		});
	    }

	    function generateView(){
    		$.ajax({
    			url: './handler.php',
    			type: 'POST',
    			dataType: 'json',
    			data: {act: 'get' ,account:account,map:map},
    		})
    		.done(function(data) {
    			// console.log(data);
    			if(data=="no"){
	    			let input = '<input type="text" name="des" value="" required="required">';
    				$("#desForm").append(input);
    			}else if(data=="error"){
    				alert("對應編號有誤");
    				history.go(-1);
    			}else{
    				/*
					<li class="list-group-item d-flex justify-content-between lh-condensed">
			      		<div>
			        		<h6 class="my-0">Product name</h6>
							<a class="btn btn-sm btn-primary" href="../../components/navbar/" role="button">開始導航 »</a>
			      		</div>
				    </li>
    				*/
    				$.each(data, function(index, val) {
	    				// let a = '<a href="./map.php?des='+val.address+'">導航</a>';
	    				let li = '<li class="list-group-item d-flex justify-content-between lh-condensed"><div><h6 class="my-0">'+val.address+'</h6><a class="btn btn-sm btn-primary" target="_blank" jstcache="7" href="https://maps.google.com/maps?ll='+latitude+','+longitude+'&amp;z=9&amp;t=m&amp;hl=zh-TW&amp;gl=US&amp;mapclient=embed&amp;saddr=407%E5%8F%B0%E4%B8%AD%E5%B8%82%E8%A5%BF%E5%B1%AF%E5%8D%80%E8%BB%8D%E5%92%8C%E8%A1%9790%E8%99%9F&amp;daddr='+val.address+'&amp;dirflg=d" jsaction="mouseup:directionsCard.moreOptions" role="button">開始導航 »</a></div></li>'
	    				$("#ul").append(li);
	    			});
    			}
    		})
    		.fail(function() {
    			console.log("error");
    		})
    		.always(function() {
    			// console.log("complete");
    		});
	    }
    </script>
</body>

</html>