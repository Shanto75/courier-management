<?php @session_start() ?>
<?php include('db_connect.php') ?>
<?php
$twhere ="";
if($_SESSION['login_type'] != 4)
  $twhere = "  ";
?>
<!--  $_SESSION['login_id']; -->
<!-- Info boxes -->
<?php if($_SESSION['login_type'] == 4): ?>

<div class="col-lg-12">
    <div class="card card-outline card-primary">
        <div class="card-body">
            <h3 id="statusalert" class="text-center"></h3>
            <table class="table tabe-hover table-bordered table-sm text-break text-sm text-center" id="list">
                <thead>
                    <tr class="text-center">
                        <th class="text-center">#</th>
                        <th>Reference Number</th>
                        <th>Sender Name</th>
                        <th>Receiver Name</th>
                        <th>Address</th>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php

					$logged_in_user_id = $_SESSION['login_id'];
                    $qry = $conn->query("SELECT * from parcels where riderid = $logged_in_user_id order by date_created DESC "); 
					
                    
					$index = 0;
                     
                        
                    while($row= $qry->fetch_assoc()):
                        $index++;
                        $address = $row['recipient_address'].', Phone: '.$row['recipient_contact']
					?>

                    <tr class="text-break">
                        <td class="text-center"><?php echo $index ?></td>
                        <td><b><?php echo ucwords($row['reference_number']) ?></b></td>
                        <td><b><?php echo ucwords($row['sender_name']) ?></b></td>
                        <td><b><?php echo ucwords($row['recipient_name']) ?></b></td>
                        <td><b><?php echo ucwords($address) ?></b></td>
                        <td><b><?php echo ucwords($row['item_name']) ?></b></td>
                        <td><b><?php echo ucwords($row['price']).' TK' ?></b></td>
                        <td class="text-center">
                            <?php 
							switch ($row['status']) {
								case '1':
									echo "<span class='badge badge-pill badge-info'> Item <br> Accepted <br> by Courier</span>";
									break;
								case '2':
									echo "<span class='badge badge-pill badge-primary'> Ready <br> to Pickup</span>";
									break;
								case '3':
									echo "<span class='badge badge-pill badge-success'> Picked-up</span>";
									break;
								case '4':
									echo "<span class='badge badge-pill badge-info'> Collected</span>";
									break;
								case '5':
									echo "<span class='badge badge-pill badge-info'> Shipped</span>";
									break;
								case '6':
									echo "<span class='badge badge-pill badge-primary'>In-Transit</span>";
									break;
								case '7':
									echo "<span class='badge badge-pill badge-primary'>Hold</span>";
									break;
								case '8':
									echo "<span class='badge badge-pill badge-primary'> Arrived at <br> Destination</span>";
									break;
								case '9':
									echo "<span class='badge badge-pill badge-success'>Delivered</span>";
									break;
								case '10':
									echo "<span class='badge badge-pill badge-danger'> Unsuccessfull <br> Delivery <br> Attempt/Return</span>";
									break;
							}

							?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
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
<script>
$(document).ready(function() {
    $('#list').DataTable()
})
</script>