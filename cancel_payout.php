<?php @session_start() ?>
<?php include('db_connect.php') ?>
<?php
$twhere ="";
if($_SESSION['login_type'] != 1)
  $twhere = "  ";
?>

<?php 
 $payOutID = $_GET['id'];
 $error = "";
if(isset($_POST['cancelpayment']))
    $save = $conn->query("UPDATE payouts SET status=2 WHERE id='$payOutID'");
    if($save)
        echo '<div class="alert alert-success text-center" role="alert"> Payout Request Canceled</div>';
?>

<div class="row">
    
    <div class="col-md-4 m-auto">
  
    <div class="card card-outline card-primary">
    <div class="card-body">
    <form action="#" method="POST">
    <div class="row">
        <div class="col-md-12">
        <div id="msg" class=""></div>
        <div class="row">
            <div class="col-sm-12 form-group ">
          
            <label for="" class="control-label">Transaction ID</label>
                 <p>Do you really want to Cancel this payout request?</p>
            </div>
        </div>
        </div>
    </div>
    <input type="submit" name="cancelpayment" class="btn btn-sm btn-success" value="Yes">
    </form>
</div>