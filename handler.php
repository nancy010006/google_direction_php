<?php
	require 'dbconnect.php';
	// foreach ($_POST as $key => $value) {
	// 	echo 123;
	// }
    global $conn;
	switch ($_POST["act"]) {
		case 'put':
			$result =array();
			$account = mysqli_real_escape_string($conn,$_POST['account']);
			$map = mysqli_real_escape_string($conn,$_POST['map']);
    		$sql = "INSERT INTO user (account) VALUES ('$account') ON DUPLICATE KEY UPDATE account = '$account';";
    		mysqli_query($conn,$sql);
    		$sql = "delete from map where uid = '$account' and map = '$map'";
    		echo $sql;
    		mysqli_query($conn,$sql);
			array_push($result, $sql);	
    		foreach ($_POST["data"] as $key => $value) {
				$des = mysqli_real_escape_string($conn,$value["value"]);
    			$sql = "insert into map (uid,address,map) values ('$account','$des','$map')";
    			array_push($result, $sql);	
    			mysqli_query($conn,$sql);
    		}
    		echo json_encode($result);
			break;
		case 'get':
			$account = mysqli_real_escape_string($conn,$_POST['account']);
			$map = mysqli_real_escape_string($conn,$_POST['map']);
    		$sql = "select address from map where uid='$account' and map = '$map'";
    		$query = mysqli_query($conn,$sql);
    		$length = mysqli_num_rows($query);
    		if($length){
				$result = array();
				while ($data = mysqli_fetch_assoc($query)) {
					array_push($result, $data);
				}
				echo json_encode($result);
    		}else{
    			echo json_encode("no");
    		}

   //  		$sql = "insert into user (account,map) values ('$account','$map')";
			// echo json_encode($sql);
   //  		mysqli_query($conn,$sql);
   //  		foreach ($_POST["data"] as $key => $value) {
			// 	$des = mysqli_real_escape_string($conn,$value["value"]);
   //  			$sql = "insert into map (uid,address) values ('$account','$des')";
   //  			mysqli_query($conn,$sql);
   //  		}
			break;
		case 'rand':
			$rand = md5(rand(-2147483648,2147483647));
			$sql = "select account from user where account = '$rand'";
    		$result = mysqli_query($conn,$sql);
    		while (mysqli_num_rows($result)>0) {
				$rand = md5(rand(-16777216,16777216));
				$sql = "select account from user where account = '$rand'";
    			$result = mysqli_query($conn,$sql);
    		}
			echo json_encode($rand);
			break;
		default:
			# code...
			break;
	}
?>