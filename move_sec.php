<?php
	
	include 'db_connect.php';
	$mv = json_decode($_POST['mv']);

	foreach ($mv as $key => $id) {
		$query = "UPDATE user SET sec='".(int)$_POST['movein']."' WHERE id='".(int)$id."' ";

		$result = mysqli_query($con,$query);
		if(!$result)
			die('Error: ' . mysqli_error());
		else
			echo "success";
		//remove status --optional
		$setStatus=mysqli_query($con,"UPDATE user SET op_status='No Status' WHERE id='".(int)$id."'");
		
	}
mysqli_close($con);
?>