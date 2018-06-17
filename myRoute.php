<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <title>等下去哪-路線圖</title>
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
    <style type="text/css">
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

        .list-group-item{
            padding: 1.5rem 2rem;
        }
        .edit{
            height: 4rem;
        }
    </style>
</head>

<body>
    <br>
    <form id="desForm" class="container">
        <!-- <input type="text" name="des"> -->
        <div class="col-md-12 order-md-2 mb-12">
            <h1 class="d-flex justify-content-between align-items-center mb-3">
            <span class="text-muted">路線圖<button onclick="addRoute(this)" type="button" class="btn btn-success btn-circle btn-sm"><i class="fas fa-plus"></i></button></span>
            <!-- <span class="badge badge-secondary badge-pill">3</span> -->
            </h1>
            <ul id="ul" class="list-group mb-3">
                <li id="example" class="list-group-item d-flex justify-content-between lh-condensed" style="display: none !important">
                    <div>
                        <h2>範例路線-崎下</h2>
                        <a class="btn btn-sm btn-primary" target="_blank" jstcache="7" href="https://maps.google.com/maps?ll='+latitude+','+longitude+'&amp;z=9&amp;t=m&amp;hl=zh-TW&amp;gl=US&amp;mapclient=embed&amp;saddr=407%E5%8F%B0%E4%B8%AD%E5%B8%82%E8%A5%BF%E5%B1%AF%E5%8D%80%E8%BB%8D%E5%92%8C%E8%A1%9790%E8%99%9F&amp;daddr=崎下&amp;dirflg=d" jsaction="mouseup:directionsCard.moreOptions" role="button">開始導航 »</a>
                    </div>
                    <!-- <span class="text-muted">$12</span> -->
                </li>
            </ul>
            <div class="spinner"></div>
            <input id="submit" type="submit" class="btn btn-success form-control" value="儲存變更">
            <!-- <button class="btn btn-info form-control">取消所有變更</button> -->
        </div>
    </form>
    <script type="text/javascript">
        let ori;
		const account = '<?php echo $_GET["account"]?>';
		const map = '<?php echo $_GET["map"]?>';

    	$(document).ready(function() {

            loadingEffect();
            getLocation();
    		setForm();
            keepCookie();
    		// generateView();

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
				dataType: 'json'
			})
			.done(function(data){
                console.log(data);
                if(data){
                    ori = data.results[0].formatted_address;
                    generateView();
                }
                else{
                    alert('發生異常 請重新載入');
                    history.go(0);
                }
			})
			.always(function() {
				// console.log("complete");
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
                    $("#submit").hide();
                    $("#example").show();
    			}else if(data=="error"){
    				alert("對應編號有誤");
    				history.go(-1);
    			}else{
    				$.each(data, function(index, val) {
                        const input = '<input type="hidden" name="des" value="'+val.address+'">'
                        const deleteButton = '<button onclick="delRoute(this)" type="button" class="btn btn-danger btn-circle btn-sm"><i class="fas fa-trash-alt"></i></button>';
                        const editButton = '<button onclick="editRoute(this)" type="button" class="btn btn-info btn-circle btn-sm"><i class="fas fa-pencil-alt"></i></button> ';
                        const li = '<li class="list-group-item d-flex justify-content-between lh-condensed"><div><h2>'+val.address+'</h2><a class="btn btn-sm btn-primary" target="_blank" jstcache="7" href="https://maps.google.com/maps?z=9&amp;t=m&amp;hl=zh-TW&amp;gl=US&amp;mapclient=embed&amp;saddr='+ori+'&amp;daddr='+val.address+'&amp;dirflg=d" jsaction="mouseup:directionsCard.moreOptions" role="button">開始導航 »</a></div><span class="text-muted">'+input+editButton+deleteButton+'</span></li>'
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

        function addRoute(self){
            $("#submit").show();
            console.log(self);
            const newRoute = '<input type="text" class="form-control" name="des" value="" required="required">';
            const deleteButton = '<button onclick="delRoute(this)" type="button" class="btn btn-danger btn-circle btn-sm"><i class="fas fa-trash-alt"></i></button>';
            let li = '<li class="list-group-item d-flex justify-content-between lh-condensed"><div><h6 class="my-0">'+newRoute+'</h6></div><span class="text-muted">'+deleteButton+'</span></li>'

            $("#ul").append(li);
            $('input[value=""]').focus();
            // $(self).before(newRoute+deleteButton);
        }

        function delRoute(self){
            $('#submit').show();
            const li = $(self).closest('li');
            li.remove();
            let liLength = $('li').length;
            if(liLength<1){
                $("#submit").hide();
                $("#example").show();
            }
        }

        function editRoute(self){
            $('#submit').show();
            const h2 = $(self).parent().siblings('div').children('h2');
            const a = $(self).parent().siblings('div').children('a');
            const hidden = $(self).siblings('input');
            h2.html('<input class="form-control edit" name="des" type="text" value="'+h2.text()+'">');
            hidden.remove();
            a.remove();
        }

        function getCookie(c_name) {
            if (document.cookie.length > 0) {
                c_start = document.cookie.indexOf(c_name + "=")
                if (c_start != -1) {
                    c_start = c_start + c_name.length + 1;
                    c_end = document.cookie.indexOf(";", c_start);
                    if (c_end == -1) c_end = document.cookie.length;
                    return unescape(document.cookie.substring(c_start, c_end));
                }
            }
            return "";
        }

        function setCookie(c_name, value, expiredays) {
            var exdate = new Date()
            // exdate.setTime(exdate.getTime() + 20 * 1000);
            exdate.setDate(exdate.getDate() + expiredays);
            document.cookie = c_name + "=" + escape(value) + ((expiredays == null) ? "" : ";expires=" + exdate.toGMTString());
        }

        //保持cookie狀態
        function keepCookie(){
            let account = "account";
            cookie = getCookie(account);
            // console.log(123,cookie);
            setCookie(account, cookie, 3);
            setTimeout(function(){ keepCookie(); }, 60000);
        }

        function loadingEffect() {
            const loading = $('.spinner');
            $('#submit').hide();
            $(document).ajaxStart(function () {
            }).ajaxStop(function () {
                loading.hide();
            });
        }

    </script>
</body>

</html>