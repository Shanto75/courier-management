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
if(isset($_POST['completepayment']))
    if(!empty($_POST['trnx_id'])){
        $trx_id =  $_POST['trnx_id'];
        $conn->query("UPDATE payouts SET transaction_id='$trx_id', status=1 WHERE id='$payOutID'");
        echo '<div class="alert alert-success text-center" role="alert"> Completed</div>';
    }else{
        echo '<div class="alert alert-danger text-center" role="alert"> You must enter transaction id</div>';
    }
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
            <?php 
                 if($payOutID){
                    $query = $conn->query("SELECT * FROM payouts WHERE id='".$payOutID."'");
                 }
            ?>
            <label for="" class="control-label">Transaction ID</label>
                 <textarea name="trnx_id" class="form-control"></textarea>
            </div>
        </div>
        </div>
    </div>
    <input type="submit" name="completepayment" class="btn btn-sm btn-success" value="Completed">
    </form>
</div>