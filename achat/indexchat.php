<?php  
  $connect = mysqli_connect("localhost", "root", "", "db");
 //entry.php  
 session_start();
if (!isset($_SESSION['shopping_cart']))
{ 
  $_SESSION['shopping_cart'] = array(); 
}
 
if(empty($_SESSION['user'])){
 header('location:login.php');
}
 ?>  
 

<!DOCTYPE html>
<html lang="en">
<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Users Online | Mary Grace Foods Inc.,</title>
      <link rel="icon" href="../images/logoo.png">

  <!-- Custom fonts for this template-->
      <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

  <!-- Page level plugin CSS-->


  <!-- Custom styles for this template-->
      <link href="../css/sb-admin.css" rel="stylesheet">
      <link rel="stylesheet" href="https://cdn.rawgit.com/mervick/emojionearea/master/dist/emojionearea.min.css">
      <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
      <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
      <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

      <script src="https://cdn.rawgit.com/mervick/emojionearea/master/dist/emojionearea.min.js"></script>

  <script type="text/javascript">
  function myFunction() {
    $.ajax({
      url: "view_notification.php",
      type: "POST",
      processData:false,
      success: function(data){
        $("#notification-count").remove();          
        $("#notification-latest").show();$("#notification-latest").html(data);
      },
      error: function(){}           
    });
   }
   
   $(document).ready(function() {
    $('body').click(function(e){
      if ( e.target.orderid != 'notification-icon'){
        $("#notification-latest").hide();
      }
    });
  });
  
  
  function myFunction1() {
    $.ajax({
      url: "view_notification_bakery.php",
      type: "POST",
      processData:false,
      success: function(data){
        $("#notification-count1").remove();          
        $("#notification-latest1").show();$("#notification-latest1").html(data);
      },
      error: function(){}           
    });
   }
   
   $(document).ready(function() {
    $('body').click(function(e){
      if ( e.target.orderid != 'notification-icon1'){
        $("#notification-latest1").hide();
      }
    });
  });
    
  </script>
</head>

<body id="page-top" background="../images/bg2.png">

  <nav class="navbar navbar-expand navbar-dark bg-dark static-top">
    <a class="navbar-brand mr-1" href="../../index.php"><i class="fa fa-home" aria-hidden="true"></i> MGFI</a>
    <button class="btn btn-link btn-sm text-white order-1 order-sm-0" id="sidebarToggle" href="#">
      <i class="fas fa-bars"></i>
    </button>
    
    <!-- Navbar Search -->
    <form class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0">
     <!-- <div class="input-group">
        <input type="text" class="form-control" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
        <div class="input-group-append">
          <button class="btn btn-primary" type="button">
            <i class="fas fa-search"></i>
          </button>
        </div>
      </div> -->
    </form>

    <!-- Navbar -->
    <ul class="navbar-nav ml-auto ml-md-0">
     
   

  
   
  <!-- <li class="nav-item dropdown no-arrow mx-1">
        <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fas fa-bell fa-fw"></i>
          <span class="badge badge-danger">9+</span>
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="alertsDropdown">
          <a class="dropdown-item" href="#">Action</a>
          <a class="dropdown-item" href="#">Another action</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="#">Something else here</a>
        </div>
      </li>  -->
    
    
   <?php
     if($_SESSION['user']['userlevel'] == 'Admin')
        {
        ?>

          <?php
    $conn = new mysqli("localhost","root","","db");
    $count=0;
      $sql2="SELECT * FROM orderprod WHERE status = 0";
      $result=mysqli_query($conn, $sql2);
      $count=mysqli_num_rows($result);
?>


    <li class="nav-item dropdown no-arrow mx-1">
    <div id="notification-header">
      <a class="nav-link dropdown-toggle" id="userDropdown" name="button" onclick="myFunction()" class="dropbtn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <span class="badge badge-danger" id="notification-count"><?php if($count>0) { echo $count; } ?></span>
        <i class="fas fa-bell fa-fw"></i>         
      </a>
      <div class="dropdown-menu dropdown-menu-right" aria-labelledby="alertsDropdown">
        <div class="dropdown-item" id="notification-latest">
        </div>
        
        </div>
        </div>
      </li> 
  
    <?php
  }
    ?>
  
  
   <?php
     if($_SESSION['user']['userlevel'] == 'Admin1')
        {
        ?>

          <?php
    $conn = new mysqli("localhost","root","","db");
    $count=0;
      $sql2="SELECT * FROM orderprod_bakery WHERE status = 0";
      $result=mysqli_query($conn, $sql2);
      $count=mysqli_num_rows($result);
?>


    <li class="nav-item dropdown no-arrow mx-1">
    <div id="notification-header">
      <a class="nav-link dropdown-toggle" id="userDropdown" name="button" onclick="myFunction1()" class="dropbtn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <span class="badge badge-danger" id="notification-count1"><?php if($count>0) { echo $count; } ?></span>
        <i class="fas fa-bell fa-fw"></i>         
      </a>
      <div class="dropdown-menu dropdown-menu-right" aria-labelledby="alertsDropdown">
        <div class="dropdown-item" id="notification-latest1">
        </div>
        
        </div>
        </div>
      </li> 
  
    <?php
  }
    ?>
  
  
    
    <!--  <li class="nav-item dropdown no-arrow mx-1">
        <a class="nav-link dropdown-toggle" href="viewcart.php">
           <span class="badge badge-danger"><?php echo count($_SESSION['shopping_cart']); ?></span>
       <i class="fa fa-shopping-cart" aria-hidden="true"></i>
          
        </a>
        
      </li>  -->
    

      <li class="nav-item dropdown no-arrow">
        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fas fa-user-circle fa-fw"></i>
                 <span><?php echo $_SESSION['user']['username'];?></span>
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
          <a class="dropdown-item" href="#">Settings</a>
          <a class="dropdown-item" href="indexchat.php">Activity Log</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">Logout</a>
        </div>
      </li>
    </ul>

  </nav>

  <div id="wrapper">

    <!-- Sidebar Start -->
    <ul class="sidebar navbar-nav ">
      <li class="nav-item active">
        <a class="nav-link" href="../home.php">
         <i class="fa fa-industry" aria-hidden="true"></i>
          <span>Dashboard</span>
        </a>
      </li>
    
    
   
     
     <?php
        if($_SESSION['user']['userlevel'] != 'Admin' AND 
          $_SESSION['user']['userlevel'] != 'Admin1' AND 
          $_SESSION['user']['userlevel'] != 'Admin2' AND 
          $_SESSION['user']['userlevel'] != 'Admin3' AND 
          $_SESSION['user']['userlevel'] != 'Admin4' AND
          $_SESSION['user']['userlevel'] != 'Admin5' AND
       $_SESSION['user']['userlevel'] != 'Admin6' AND
          $_SESSION['user']['userlevel'] != 'User4' AND
          $_SESSION['user']['userlevel'] != 'User3' ) 
      {
      ?>   
    
     <!-- Add Order -->
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          
      <i class="fa fa-cart-plus" aria-hidden="true"></i>
          <span>Add Order</span>
        </a>
        <div class="dropdown-menu" aria-labelledby="pagesDropdown">
         <h6 class="dropdown-header">Order:</h6>
      <div class="dropdown-divider"></div>
      <a class="dropdown-item" href="../addorder.php">Commissary</a>
          <a class="dropdown-item" href="../addorder_bakery.php">Bakery</a>
        </div>
      </li>
    
    <?php
          }
       ?>
    
    
     <!-- Add Order Commissary -->
     
     <?php
        if($_SESSION['user']['userlevel'] == 'Admin' OR 
          $_SESSION['user']['userlevel'] == 'Admin3' OR 
          $_SESSION['user']['userlevel'] == 'Admin4' OR 
          $_SESSION['user']['userlevel'] == 'Admin5' OR 
          $_SESSION['user']['userlevel'] == 'Admin6'  OR 
          $_SESSION['user']['userlevel'] == 'User3')
      {
      ?>
      
      <li class="nav-item dropdown">
        <a class="nav-link" href="addorder.php"> 
      <i class="fa fa-cart-plus" aria-hidden="true"></i>
          <span>Add Order</span>
        </a>
      </li>
    
    <?php
          }
       ?>
     
    
    
     <!-- Add Order Production -->
     
     <?php
        if($_SESSION['user']['userlevel'] == 'Admin1' OR $_SESSION['user']['userlevel'] == 'Admin2' OR $_SESSION['user']['userlevel'] == 'User4')
      {
      ?>
      
      <li class="nav-item dropdown">
        <a class="nav-link " href="addorder_bakery.php">
      <i class="fa fa-cart-plus" aria-hidden="true"></i>
          <span>Add Order</span>
        </a>
      </li>
    
    <?php
          }
       ?>
         
    
    <!-- CONSOLIDATION -->
    
      <?php
        if($_SESSION['user']['userlevel'] == 'Master') 
      {
      ?>   
    
     <!-- MASTER -->
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          
     <i class="fa fa-truck" aria-hidden="true"></i>
          <span>Consolidation</span>
        </a>
        <div class="dropdown-menu" aria-labelledby="pagesDropdown">
         <h6 class="dropdown-header">Order:</h6>
      <div class="dropdown-divider"></div>
      <a class="dropdown-item" href="consolidation.php">Commissary</a>
          <a class="dropdown-item" href="consolidation_bakery.php">Bakery</a>
        </div>
      </li>
    
    <?php
          }
       ?>
     
    <!-- COMMI CONSO -- >
    
    <!-- ADMIN COMMISSARY -->
       <?php
      if($_SESSION['user']['userlevel'] == 'Admin'  
        )
      {
      ?>
      
       
     <li class="nav-item">
        <a class="nav-link" href="consolidation.php?PCluster1D=Pcommissary1">
    <i class="fa fa-truck" aria-hidden="true"></i>
          <span>Consolidation</span></a>
      </li>
    
      <?php
          }
       ?>
     
     <!-- FG -->
       <?php
      if($_SESSION['user']['userlevel'] == 'Admin3' 
        )
      {
      ?>
      
       
     <li class="nav-item">
        <a class="nav-link" href="consolidation.php?PCluster1D=Pcommissary1">
    <i class="fa fa-truck" aria-hidden="true"></i>
          <span>Consolidation</span></a>
      </li>
    
      <?php
          }
       ?>
     
     <!-- TW -->
       <?php
      if($_SESSION['user']['userlevel'] == 'Admin4' 
        )
      {
      ?>
      
       
     <li class="nav-item">
        <a class="nav-link" href="consolidation.php?PCluster1D=Pcommissary1">
    <i class="fa fa-truck" aria-hidden="true"></i>
          <span>Consolidation</span></a>
      </li>
    
      <?php
          }
       ?>
     
     <!-- WH -->
       <?php
      if($_SESSION['user']['userlevel'] == 'Admin5' 
        )
      {
      ?>
      
       
     <li class="nav-item">
        <a class="nav-link" href="consolidation.php?PCluster1D=Pcommissary1">
    <i class="fa fa-truck" aria-hidden="true"></i>
          <span>Consolidation</span></a>
      </li>
    
      <?php
          }
       ?>
     
     <!-- END -->
     
     
     
     <?php
        if($_SESSION['user']['userlevel'] == 'Admin1')
      {
      ?>
     
       <!-- BAKERY -->
     <li class="nav-item">
        <a class="nav-link" href="consolidation_bakery.php">
    <i class="fa fa-truck" aria-hidden="true"></i>
          <span>Consolidation</span></a>
      </li>
    
     <?php
          }
       ?>
  
    
    </ul>


<?php

include('database_connection.php');


if(!isset($_SESSION['userid']))
{
  header('location:login.php');
}


?>



    <div id="content-wrapper" >
      <div class="container-fluid">
        <!-- Breadcrumbs-->
        <ol class="breadcrumb">
                    <li class="dropdown messages-dropdown">
                        <a style="color:black"><i class="fa fa-calendar"></i>  
                          <?php
                            $Today=date('y:m:d');
                            $new=date('l, F d, Y',strtotime($Today));
                            echo $new; ?></a>
          </li>
        </ol>

        <div class="container">
      <br />
      <h3 align="center">Users Online</h3><br />
      <br />
      <div class="row">
        <div class="col-md-8 col-sm-6">
          <h4>Online User</h4>
        </div>
<!--         <div class="col-md-2 col-sm-3">
          <input type="hidden" id="is_active_group_chat_window" value="no" />
          <button type="button" name="group_chat" id="group_chat" class="btn btn-warning btn-xs">Group Chat</button>
        </div> -->
        <div class="col-md-2 col-sm-3">
          <p align="right">Hi - <?php echo $_SESSION['username']; ?></p>
        </div>
      </div>
      <div class="table-responsive">
        
        <div id="user_details"></div>
        <div id="user_model_details"></div>
      </div>
      <br />
      <br />
      
    </div>
<style>

.chat_message_area
{
  position: sticky;
  width: 100%;
  height: 100%;
  background-color: #FFF;
  border: 1px solid #CCC;
  border-radius: 3px;
}
#group_chat_message
{
  width: 100%;
  height: auto;
  min-height: 80px;
  overflow: auto;
  padding:6px 24px 6px 12px;
}
.image_upload
{
  position: absolute;
  top:3px;
  right:3px;
}
.image_upload > form > input
{
    display: none;
}

.image_upload img
{
    width: 24px;
    cursor: pointer;
}

</style>  


<div id="group_chat_dialog" title="Group Chat Window">
  <div id="group_chat_history" style="height:400px; border:1px solid #ccc; overflow-y: scroll; margin-bottom:24px; padding:16px;">

  </div>
  <div class="form-group">
    <!--<textarea name="group_chat_message" id="group_chat_message" class="form-control"></textarea>!-->
    <div class="chat_message_area">
      <div id="group_chat_message" contenteditable class="form-control">

      </div>
      <div class="image_upload">
        <form id="uploadImage" method="post" action="upload.php">
          <label for="uploadFile"><img src="upload.png" /></label>
          <input type="file" name="uploadFile" id="uploadFile" accept=".jpg, .png" />
        </form>
      </div>
    </div>
  </div>
  <div class="form-group" align="right">
    <button type="button" name="send_group_chat" id="send_group_chat" class="btn btn-info">Send</button>
  </div>
</div>




    

      
 </div>
</div>  
      <!-- /.container-fluid -->
      <!-- Sticky Footer -->
      <footer class="sticky-footer  bg-transparent">
        <div class="container my-auto  bg-transparent">
          <div class="copyright text-center my-auto ">
            <span>Copyright © Mary Grace Foods Inc. 2019</span>
          </div>
        </div>
      </footer>

    </div>
    <!-- /.content-wrapper -->

  </div>
  <!-- /#wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>
  
  

  <!-- Logout Modal-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
          <a class="btn btn-danger" href="../logout.php">Logout</a>
        </div>
      </div>
    </div>
  </div>


<script>  
$(document).ready(function(){

  fetch_user();

  setInterval(function(){
    update_last_activity();
    fetch_user();
    update_chat_history_data();
    fetch_group_chat_history();
  }, 5000);

  function fetch_user()
  {
    $.ajax({
      url:"fetch_user.php",
      method:"POST",
      success:function(data){
        $('#user_details').html(data);
      }
    })
  }

  function update_last_activity()
  {
    $.ajax({
      url:"update_last_activity.php",
      success:function()
      {

      }
    })
  }

  function make_chat_dialog_box(to_user_id, to_user_name)
  {
    var modal_content = '<div id="user_dialog_'+to_user_id+'" class="user_dialog" title="You have chat with '+to_user_name+'">';
    modal_content += '<div style="height:300px; border:1px solid #ccc; overflow-y: scroll; margin-bottom:24px; padding:16px; class="chat_history" data-touserid="'+to_user_id+'" id="chat_history_'+to_user_id+'">';
    modal_content += fetch_user_chat_history(to_user_id);
    modal_content += '</div>';
    modal_content += '<div class="form-group">';
    modal_content += '<textarea name="chat_message_'+to_user_id+'" id="chat_message_'+to_user_id+'" class="form-control chat_message"></textarea>';
    modal_content += '</div><div class="form-group" align="right">';
    modal_content+= '<button type="button" name="send_chat" id="'+to_user_id+'" class="btn btn-info send_chat">Send</button></div></div>';
    $('#user_model_details').html(modal_content);
  }

  $(document).on('click', '.start_chat', function(){
    var to_user_id = $(this).data('touserid');
    var to_user_name = $(this).data('tousername');
    make_chat_dialog_box(to_user_id, to_user_name);
    $("#user_dialog_"+to_user_id).dialog({
      autoOpen:false,
      width:400
    });
    $('#user_dialog_'+to_user_id).dialog('open');
    $('#chat_message_'+to_user_id).emojioneArea({
      pickerPosition:"top",
      toneStyle: "bullet"
    });
  });

  $(document).on('click', '.send_chat', function(){
    var to_user_id = $(this).attr('id');
    var chat_message = $.trim($('#chat_message_'+to_user_id).val());
    if(chat_message != '')
    {
      $.ajax({
        url:"insert_chat.php",
        method:"POST",
        data:{to_user_id:to_user_id, chat_message:chat_message},
        success:function(data)
        {
          //$('#chat_message_'+to_user_id).val('');
          var element = $('#chat_message_'+to_user_id).emojioneArea();
          element[0].emojioneArea.setText('');
          $('#chat_history_'+to_user_id).html(data);
        }
      })
    }
    else
    {
      alert('Type something');
    }
  });

  function fetch_user_chat_history(to_user_id)
  {
    $.ajax({
      url:"fetch_user_chat_history.php",
      method:"POST",
      data:{to_user_id:to_user_id},
      success:function(data){
        $('#chat_history_'+to_user_id).html(data);
      }
    })
  }

  function update_chat_history_data()
  {
    $('.chat_history').each(function(){
      var to_user_id = $(this).data('touserid');
      fetch_user_chat_history(to_user_id);
    });
  }

  $(document).on('click', '.ui-button-icon', function(){
    $('.user_dialog').dialog('close').remove();
    $('#is_active_group_chat_window').val('no');
  });

  $(document).on('focus', '.chat_message', function(){
    var is_type = 'yes';
    $.ajax({
      url:"update_is_type_status.php",
      method:"POST",
      data:{is_type:is_type},
      success:function()
      {

      }
    })
  });

  $(document).on('blur', '.chat_message', function(){
    var is_type = 'no';
    $.ajax({
      url:"update_is_type_status.php",
      method:"POST",
      data:{is_type:is_type},
      success:function()
      {
        
      }
    })
  });

  $('#group_chat_dialog').dialog({
    autoOpen:false,
    width:400
  });

  $('#group_chat').click(function(){
    $('#group_chat_dialog').dialog('open');
    $('#is_active_group_chat_window').val('yes');
    fetch_group_chat_history();
  });

  $('#send_group_chat').click(function(){
    var chat_message = $.trim($('#group_chat_message').html());
    var action = 'insert_data';
    if(chat_message != '')
    {
      $.ajax({
        url:"group_chat.php",
        method:"POST",
        data:{chat_message:chat_message, action:action},
        success:function(data){
          $('#group_chat_message').html('');
          $('#group_chat_history').html(data);
        }
      })
    }
    else
    {
      alert('Type something');
    }
  });

  function fetch_group_chat_history()
  {
    var group_chat_dialog_active = $('#is_active_group_chat_window').val();
    var action = "fetch_data";
    if(group_chat_dialog_active == 'yes')
    {
      $.ajax({
        url:"group_chat.php",
        method:"POST",
        data:{action:action},
        success:function(data)
        {
          $('#group_chat_history').html(data);
        }
      })
    }
  }

  $('#uploadFile').on('change', function(){
    $('#uploadImage').ajaxSubmit({
      target: "#group_chat_message",
      resetForm: true
    });
  });

  $(document).on('click', '.remove_chat', function(){
    var chat_message_id = $(this).attr('id');
    if(confirm("Are you sure you want to remove this chat?"))
    {
      $.ajax({
        url:"remove_chat.php",
        method:"POST",
        data:{chat_message_id:chat_message_id},
        success:function(data)
        {
          update_chat_history_data();
        }
      })
    }
  });
  
});  
</script>

  <!-- Bootstrap core JavaScript-->
  <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>
