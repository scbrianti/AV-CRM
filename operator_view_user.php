<?php
  include 'header.php';

?>
 


<script type="text/javascript">
$(document).ready(function(){

 $("#datetimepicker1").hide();

})
  
</script>
    <div class="container-fluid">

      <div class="admin-outer jumbotron" >

        <?php
        if(isset($_SESSION['operator_username'])){
        if(isset($_GET['user_id'])){
          
          $result = op_view_user($con,$_GET['user_id'],$_SESSION['operator_username']);
    if(!$result)
      die('Error: ' . mysqli_error());
    else{
      $array=mysqli_fetch_array($result);
          if($array){
            ?>

<div class="row">

 <div class="col-md-7 ">

<div class="panel panel-default">
 
   <div class="panel-body">
       
    <div class="box box-primary">
        
            <div class="box-body">
            
            <div class="col-sm-6">
            <h4 style="color:green;"><?php echo $array[8]?> </h4></span>
              <span><p></p></span>            
            </div>
            <div class="clearfix"></div>
            <hr style="margin:5px 0 5px 0;">
    
              
<div class="col-sm-5 col-xs-6 tital " >Full Name:</div><div class="col-sm-7 col-xs-6 "><?php echo $array[8]?></div>
     <div class="clearfix"></div>
<div class="bot-border"></div>

<div class="col-sm-5 col-xs-6 tital " >Email:</div><div class="col-sm-7"> <?php echo $array[9]?></div>
  <div class="clearfix"></div>
<div class="bot-border"></div>


<div class="col-sm-5 col-xs-6 tital " >Registered:</div><div class="col-sm-7"><?php echo $array[11]?></div>

  <div class="clearfix"></div>
<div class="bot-border"></div>

<div class="col-sm-5 col-xs-6 tital " >Phone Number:</div><div class="col-sm-7"><?php echo $array[10]?>
  <a id="callbtn" style="color:black;text-decoration: none;cursor: pointer;" onclick="window.location.href='sip:<?php echo $array[10]; ?>'" > <img src="images/phone.png" height="30px">
                        </a>
</div>

  <div class="clearfix"></div>

<div class="bot-border"></div>
                 <div class="panel-footer">
                             <?php 
                                  $nextquery= "SELECT * FROM jobs WHERE operator='".$_SESSION['operator_username']."'AND  id < '".$_GET['user_id']."' ORDER BY id DESC LIMIT 1 "; 
                                  $nextresult = mysqli_query($con,$nextquery);
                                  if(mysqli_num_rows($nextresult) > 0)
                                  {
                                      $nextrow = mysqli_fetch_array($nextresult);
                                      
                                      $nextid  = $nextrow['id'];
                                  } else{
                                      $nextid=$_GET['user_id'];
                                  }
                             ?>
                             
                             <a  class="btn btn-danger" href="operator.php">close</a>
                             <a class="btn btn-primary"   href="operator_view_user.php?user_id=<?php echo $nextid;?>">next</a> 
                              <span class="pull-right">

                            <a href="operator_user_edit.php?user_id=<?php echo $_GET['user_id'] ?>" data-original-title="Edit this user" data-toggle="tooltip" type="button" class="btn btn-sm btn-warning"><i class="glyphicon glyphicon-edit"></i></a>
                            </span>
                            

                    </div>



            <!-- /.box-body -->
          </div>
          <!-- /.box -->

        </div>
       
            
    </div> 
    </div>
</div>  
    <script>
              $(function() {
    $('#profile-image1').on('click', function() {
        $('#profile-image-upload').click();
    });
});       
              </script> 
       
         <style type="text/css">
                         input.hidden {
    position: absolute;
    left: -9999px;
}

#profile-image1 {
    cursor: pointer;
  
     width: 100px;
    height: 100px;
  border:2px solid #03b1ce ;}
  .tital{ font-size:16px; font-weight:500;}
   .bot-border{ border-bottom:1px #f8f8f8 solid;  margin:5px 0  5px 0}  
         </style>
 

<div  class="col-md-4">


<?php

        
          $result1 = op_user_jobs($con,(int)$_GET['user_id']);
    if(!$result1)
    die('Error: ' . mysqli_error());
    else{
      
      if(mysqli_num_rows($result1) > 0){
       while($row = $result1->fetch_assoc()){
          

if (isset($row['meet'])){ 
 
 $date_arr= explode(" ", $row['meet']);
                    $date= $date_arr[0];
                   $time= $date_arr[1];
   ?>
            
   <div class="panel panel-danger">
  <div class="panel-heading">Meeting: <span style="float: right;color: black"> <?php echo $row['status'] ?></span> </div>
  <div class="panel-body">

    <center>
  <div>

  <span class="alert alert-info"><?php echo $time; ?></span>
            <span class="alert alert-success"><?php echo $date ?></span>
             
 <a style='position:absolute;right:17px;' class="btn btn-warning" href="operator_view_user_meet_edit.php?user_id=<?php echo $_GET['user_id']; ?>">Edit</a>

</div>
</center>

  </div>
</div>



<?php } else{ ?>
<div class="panel panel-danger">
  <div class="panel-heading">Meeting: <span style="float: right;color: black"> <?php echo $row['status'] ?></span> </div>
  <div class="panel-body">
<center>
<form method="POST" action="meet.php" >
<select id="status" name="status" required="" style="height: 34px;border: 1px solid #ccc;border-radius: 4px;color:#555">
<option  disabled="disabled" selected="selected" value="" >Select Status</option>
<option value="Potential">Potential</option>
<option value="Follow Up">Follow Up</option>
<option value="Interested">Interested</option>
<option value="Non Interested">Non Interested</option>

<option value="Non Answer">Non Answer</option>
 <option value="Call Failed">Call Failed</option>
<option value="Secretary">Secretary</option>
 <option value="Deposit">Deposit</option>

</select>
<script type="text/javascript">
$(function () {
  $("#status").change(function() {
    var val = $(this).val();
    if(val === "Potential" || val==="Follow Up" || val==="Interested") {
       
      
            $('#s111').html("<input type='text' name='dt' id='dat<?php echo $_GET['user_id']; ?>' class='form-control' placeholder='Time & Date' required  />")
            $('#dat<?php echo $_GET['user_id']; ?>').datetimepicker();
 $("#datetimepicker1").show();
    }
    else{
      $('#s111').html('');
      $("#datetimepicker1").hide();
    }
    
  });
});
</script>

             <div class='input-group date' id='datetimepicker1'>

      <div id="s111">  </div>
           <input type='hidden' name='id'  value='<?php echo $_GET['user_id']; ?>'/> 
        
</div>
                  <input class="btn btn-default" style="width: 100px" type="submit" value="OK">
                

                </form>


        <script type="text/javascript">
            
        </script>
       </center>
</div>
</div>

 <?php  } } } } ?>

Leave a note:
<div class="input-group" style="width: 90%"> 
<form method="POST" action="addnote.php">
<input type="text" class="form-control" name="notetoadd" required="" autocomplete="off"></input>
<input type="hidden" name="user_id" value="<?php echo $_GET['user_id'] ?>"></input>
<input type="hidden" name="admin" value="<?php echo $_SESSION['operator_username'] ?>"></input>
 <span class="input-group-btn">
<input class="btn btn-default" style="position: absolute;" type="submit" value="OK">
</span>


</form>

</div>


<div style="overflow-y: scroll;height: 400px;">
</br>
<ul class="timeline">
<?php

          
         $query1 = "SELECT * from note  WHERE id='".(int)$_GET['user_id']."'  ORDER BY `date` DESC";
          $result1 = mysqli_query($con,$query1);
    if(!$result1)
      die('Error: ' . mysqli_error());
    else{
      $num=mysqli_num_rows($result1);
      
         while($row = $result1->fetch_assoc()){
            ?>

           <li>
              <i class="fa fa-comments bg-yellow"></i>

              <div class="timeline-item">

                <span class="time"><i class="fa fa-clock-o"></i> <?php echo $row['date'] ;?><button class="rmNote" onclick="removeNote(<?php echo $row['def'] ;?>)">X</button><?php echo" ".$row['admin'] ?></span>

                <style type="text/css">
                  .rmNote{

                    margin-left: 5px;
                    border: 1px;
                  }
                  .rmNote:hover{ 
                    background: #dd4b39;
                    color: white;
                  }
                </style>

                <div class="timeline-body">
                   <?php echo $row['note'] ;?>
                </div>
                
              </div>
            </li>



<?php }  } ?>
</ul>
</div>
</div>

</div>
</div>





      </div>


          </div>

   <?php 
      include 'menu_operator.php';
      include 'footer.php'; 
   ?>

</body>
  
        <?php
          }
    }

        } }
        ?>
<script type="text/javascript">

  $('#callbtn').click(function(){
    $.ajax({
        url : 'operator_count_calls.php', 
        type : 'post',
        data:{
          id:"<?php echo (int)$_GET['user_id'] ?>",
          admin:"<?php echo $_SESSION['operator_username'] ?>"
          
             },  
        success : function(data){
          console.log(data);
        }
    });
});

function removeNote(def){

  $.post("addnote.php?remove=true",{def:def},function(data){
    console.log(data);
    window.location.reload();

  });


}




</script>




      
    <style type="text/css">
      .main-footer{
        
       
         width: 100%;

      }
      footer{
        position: absolute;
      }
     
    </style>
</html>
