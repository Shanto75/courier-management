<?php @session_start() ?>
<?php include('db_connect.php') ?>
<?php
$twhere ="";
if($_SESSION['login_type'] != 3)
  $twhere = "  ";
?>
 <!--  $_SESSION['login_id']; -->
<!-- Info boxes -->
<?php if($_SESSION['login_type'] == 3): ?>
        <div class="row m-auto">
            <div class="col-12 col-sm-6 col-md-6">
                <div class="small-box bg-success shadow-sm border">
                <div class="inner">
                    <h3><?php echo $conn->query("SELECT * FROM parcels WHERE user_id='".$_SESSION['login_id']."'")->num_rows; ?></h3>
                    <p>Total Parcels</p>
                </div>
                <div class="icon">
                    <i class="fa fa-boxes"></i>
                </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-md-6">
                <div class="small-box bg-success shadow-sm border">
                <div class="inner">
                    <h3><?php echo $conn->query("SELECT * FROM payouts WHERE merchent_id='".$_SESSION['login_id']."' and status=0 ")->num_rows; ?></h3>
                    <p>Total Pending Payment</p>
                </div>
                <div class="icon">
                    <i class="fa fa-boxes"></i>
                </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-6">
                <div class="small-box bg-success shadow-sm border">
                <div class="inner">
                    <h3><?php echo $conn->query("SELECT * FROM payouts WHERE merchent_id='".$_SESSION['login_id']."' and status=1 ")->num_rows; ?></h3>
                    <p>Total Completed Payment</p>
                </div>
                <div class="icon">
                    <i class="fa fa-boxes"></i>
                </div>
                </div>
            </div>
        </div>
        <div class="row">
       
          <hr>
          <?php 
              $status_arr = array("Item Accepted by Courier","Ready to Pickup","Picked-up","Collected","Shipped","In-Transit","Hold","Arrived At Destination","Delivered","Unsuccessfull Delivery Attempt/Return");
               foreach($status_arr as $k =>$v):
          ?>
          <div class="col-12 col-sm-6 col-md-3">
            <div class="small-box bg-secondary shadow-sm border">
              <div class="inner">
                  <!-- SELECT COUNT( DISTINCT city) as cities FROM customer; -->
                <h3><?php echo($conn->query("SELECT COUNT( DISTINCT reference_number) as rfnum FROM parcels where status = $k+1 and user_id='".$_SESSION['login_id']."'")->fetch_array()[0]); ?></h3>

                <p><?php echo $v ?></p>
              </div>
              <div class="icon">
                <i class="fa fa-boxes"></i>
              </div>
            </div>
          </div>
            <?php endforeach; ?>
      </div>

<?php else: ?>
	 <div class="col-12">
          <div class="card">
          	<div class="card-body">
          		Welcome <?php echo $_SESSION['login_name'] ?>!
          	</div>
          </div>
      </div>
          
<?php endif; ?>
