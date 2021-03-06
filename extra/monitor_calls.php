<!--

alter dialed -> live_sip_channels; 
User phone_login;

/etc/asterisk/extensions.conf [general]:
	;Channel spy config
	exten => _*222XX!.,1,NoCDR
	exten => _*222XX!.,n,Wait(1)
	exten => _*222XX!.,n,ChanSpy(SIP/${EXTEN:4},q)
	exten => _*222XX!.,n,Hangup

/usr/share/AST_update.pl:
	:898 $dialed
	:965 or :991 dialed $dialed
	
-->

<?php
include_once('../db_connect.php');
include_once('../session.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>CRM</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

</head>
<style>
.notice {
    padding: 15px;
    background-color: #fafafa;
    border-left: 6px solid #7f7f84;
    margin-bottom: 10px;
    -webkit-box-shadow: 0 5px 8px -6px rgba(0,0,0,.2);
       -moz-box-shadow: 0 5px 8px -6px rgba(0,0,0,.2);
            box-shadow: 0 5px 8px -6px rgba(0,0,0,.2);
            width: 50%;
    float: left;
}
.notice-sm {
    padding: 10px;
    font-size: 80%;
}
.notice-lg {
    padding: 35px;
    font-size: large;
}
.notice-success {
    border-color: #80D651;
}
.notice-success>strong {
    color: #80D651;
}
.notice-info {
    border-color: #45ABCD;
}
.notice-info>strong {
    color: #45ABCD;
}
.notice-warning {
    border-color: #FEAF20;
}
.notice-warning>strong {
    color: #FEAF20;
}
.notice-danger {
    border-color: #d73814;
}
.notice-danger>strong {
    color: #d73814;
}
#myProgress {
  width: 100%;
  background-color: #ddd;
}

</style>
<body>

<center>




</center>
<div>
 <?php
 	
	
	
	$sql_query="SELECT DISTINCT channel,server_ip,extension,channel_data,dialed FROM live_sip_channels where extension !='ring'";
	$result=mysqli_query($dbconfig,$sql_query);
	
	
  while($row = mysqli_fetch_assoc($result)){	

  			$ext_match="/^([a-z]{7})/"; //match abisnet2 or abisnet for direct only use &&

			$match = "/^([A-Z]{3}?\/[0-9]{4}?\-[0-9a-z]{8})$/";
			$match1="/^([A-Z]{3}?\/[0-9]{3}?\-[0-9a-z]{8})$/";
			### STRING: SIP/cc390-00024989 /nqs nuk jane 8 karaktere ne fund, zvendeso {8} me *
			//$match1='/^([A-Z]{3}?\/[0-9a-z]{8}?\-[0-9a-z]{8})$/';
			# per SIP/abisnet2-0000138a
			if ((preg_match($match, $row['channel']) || preg_match($match1, $row['channel'])) || preg_match($ext_match, $row['extension']) ){
				//print_r($row['extension']);
				
				$exp = explode('/',$row['channel']); #SIP/cc390-00024989 explode me '/'
				$display = explode('-',$exp[1]); #cc390-00024989 explode me '-'
				$kanali = $exp[1];					
				$channel = $display[0];
//phone login field as number
$stmt="select DISTINCT user_id,full_name from vicidial_users where phone_login='".$channel."'";
	if(!$rslt=mysqli_query($dbconfig,$stmt)){
		echo "error:$stmt".mysqli_error();
	}
	while($rows=mysqli_fetch_assoc($rslt)){
		//print_r($rows);
		$user=strtolower($rows['user']);
		$full_name=$rows['full_name'];

		
		//check if number is encoded
		$dialed=$row['dialed'];
		if (preg_match('/^-/',$dialed)) {//if start with -
	
			$dialed=substr($dialed, 4); //remove -123
			
			$dialed = base64_decode($dialed);//decode number
			if(substr($dialed,0,4)=='0000') {//if line start with 0000
			$dialed=substr($dialed, 4);//remove 0000
			}
		} else{
			$dialed=$row['dialed'];
			
		}
	

		//$custom_one=$rows['custom_one'];
	
//if($custom_one[''.$channel] != 'retention'){


									if (isset($_SESSION['login_username']) && $dialed[0]!='*') { // if is not monitoring and admin login
										if ($row['dialed'][0]==8) { //vicidial session
											echo '<div class="notice notice-warning">';
											echo '<strong><a href="sip:*222'.$kanali.'@192.168.1.80">'.$full_name.'</a></strong>';
										} else { //direct number
											echo '<div class="notice notice-info">';
											echo '<strong><a href="sip:*222'.$kanali.'@192.168.1.80">'.$full_name.'</a></strong>';
											}
										} elseif (isset($_SESSION['operator_username']) && $dialed[0]!='*') { // if is not monitoring and operATOR login
											echo '<div class="notice notice-info">';
											echo '<strong><a href="sip:*222'.$kanali.'@192.168.1.80">'.$full_name.'</a></strong>';
										}
				

					
								if (isset($_SESSION['login_username']) && $dialed[0]!='*') { //admin login
												if ($row['dialed'][0]==8) {//vicidial
													echo '<font style="cursor:initial;float: right;" class="text-muted"><span>Vicidial</span></font>';
												} else {//number
												echo '<font style="float:right" id="nr'.$kanali.'" class="text-muted"><span>'.$row['dialed'].'</span></font>';
												}

											
										
											
										$dname=mysqli_query($con,"select * from user where phone_no='".$dialed."' ");//crm lead if exist
											while ($get=mysqli_fetch_assoc($dname)){
												if (isset($get['id'])) {
		                                         //print_r("name");
													 ?>
													<script type="text/javascript">
														
															$( document ).ready(function() {
														   		 $('#nr<?php echo $kanali ?>').remove();
															});
													</script>

														
													<font style="cursor:pointer;float: right;" onclick="top.show_profile(<?php echo $get['id'] ?>)" class="text-muted"> <?php echo $get['name'] ?></font>


											<?php 	} else { 
													//print_r("number");
												?>

													<p class="text-muted"><span>'.$row['dialed'].'</span></p>

													<?php 

												}
											}
								} 

								?>


								    </div>




							<?php 

			
	
//}	

			}
		}
	
}
	
 
?>
</div>


</body>
</html>