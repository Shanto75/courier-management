<?php @session_start(); ?>
<?php
include 'db_connect.php';
$qry = $conn->query("SELECT * FROM parcels where id = ".$_GET['id'])->fetch_array();
$parcel_id = trim($qry['reference_number']);
 
$allItems = [];
$sql = "SELECT * FROM parcels WHERE reference_number='".$parcel_id."'";
$result = $conn->query($sql);
$num_rows = $result->num_rows;

if ($num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $allItems[] = $row;
    }
}


foreach($qry as $k => $v){
	$$k = $v;
}
if($to_branch_id > 0 || $from_branch_id > 0){
	$to_branch_id = $to_branch_id  > 0 ? $to_branch_id  : '-1';
	$from_branch_id = $from_branch_id  > 0 ? $from_branch_id  : '-1';
$branch = array();
 $branches = $conn->query("SELECT *,concat(branch_name,', ',address,', ',station,', ',country) as address FROM branches where id in ($to_branch_id,$from_branch_id)");
    while($row = $branches->fetch_assoc()):
    	$branch[$row['id']] = $row['address'];
	endwhile;
}

?>
<div class="container-fluid">
	<div class="col-lg-12">
		<div class="row">
			<div class="col-md-12">
				<div class="callout callout-info">
					<dl>
						<dt>Tracking Number:</dt>
						<dd> <h4><b><?php echo $reference_number ?></b></h4></dd>
					</dl>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-4">
				<div class="callout callout-info">
					<b class="border-bottom border-primary">Sender Information</b>
					<dl>
						<dt>Name:</dt>
						<dd><?php echo ucwords($sender_name) ?></dd>
						<dt>Address:</dt>
						<dd><?php echo ucwords($sender_address) ?></dd>
						<dt>Contact:</dt>
						<dd><?php echo ucwords($sender_contact) ?></dd>
						<dt>Merchant Order Id:</dt>
						<dd><?php echo ucwords($merchant_order_id) ?></dd>
					</dl>
				</div>
				<div class="callout callout-info">
					<b class="border-bottom border-primary">Recipient Information</b>
					<dl>
						<dt>Name:</dt>
						<dd><?php echo ucwords($recipient_name) ?></dd>
						<dt>Contact:</dt>
						<dd><?php echo ucwords($recipient_contact) ?></dd>
						<dt>Address:</dt>
						<dd><?php echo ucwords($recipient_address) ?></dd>
						<dt>Police Station:</dt>
						<dd><?php echo ucwords($station) ?></dd>
						<dt>District:</dt>
						<dd><?php echo ucwords($district) ?></dd>
					</dl>
				</div>
			</div>
			<div class="col-md-8">
				<div class="callout callout-info">
					<b class="border-bottom border-primary">Parcel Details</b>
						<div class="row">
							 <div class="col-sm-10">
                             <table class="table table-striped">
                                <thead>
                                    <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Item Name</th>
                                    <th scope="col">weight</th>
                                    <th scope="col">height</th>
                                    <th scope="col">width</th>
                                    <th scope="col">length</th>
                                    <th scope="col">price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        $totalPrice = 0;
                                        $delivery_time = $allItems[0]['delivery_within'];
                                        for($i=0; $i<count($allItems); $i++){
                                            $index = $i+1;
                                            echo "<tr>";
                                            echo "<th scope='row'>". $index ."</th>";
                                            echo "<td>".$allItems[$i]['item_name']."</td>";
                                            echo "<td>".$allItems[$i]['weight']."</td>";
                                            echo "<td>".$allItems[$i]['height']."</td>";
                                            echo "<td>".$allItems[$i]['width']."</td>";
                                            echo "<td>".$allItems[$i]['length']."</td>";
                                            echo "<td>".$allItems[$i]['price']."TK </td>";
                                            echo "</tr>";
                                            $totalPrice += $allItems[$i]['price'];
                                        }
                                    ?>
                                </tbody>
                                </table>
                             </div>
						</div>
					<dl>
                        <?php 
                         $dttxt = "";

                         if($delivery_time == 6){
                             $dttxt = "6 Hours";
                         }else if($delivery_time == 12){
                             $dttxt = "Same Day";
                         }else if($delivery_time == 24){
                             $dttxt = "24 Hours";
                         }else if($delivery_time == 0){
                             $dttxt = "Regular";
                         }else{
                             $dttxt = "Regular";
                         }

                        ?>
                        <hr>
                        <dt>Delivery Time: </dt>
                        <dd><?php echo $dttxt ?></dd>
                        <dt>Total Price:</dt>
                        <dd><?php echo $totalPrice. "Tk" ?></dd>
						<!-- <dt>Branch Accepted the Parcel:</dt>
						<dd><?php echo ucwords($branch[$from_branch_id]) ?></dd>
						<?php if($type == 2): ?>
							<dt>Nearest Branch to Recipient for Pickup:</dt>
							<dd><?php echo ucwords($branch[$to_branch_id]) ?></dd>
						<?php endif; ?> -->
						<dt>Note/Description:</dt>
						<dd><?php echo ucwords($note) ?></dd>
						<dt>Status:</dt>
						<dd>
							<?php 
							switch ($status) {
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
                            <?php if($_SESSION['login_type'] == 1) { ?>
						 
								<span class="btn badge badge-primary bg-gradient-primary" id='update_status'><i class="fa fa-edit"></i> Update Status</span>
					 
							<?php } ?>
						</dd>

					</dl>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal-footer display p-0 m-0">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
</div>
<style>
	#uni_modal .modal-footer{
		display: none
	}
	#uni_modal .modal-footer.display{
		display: flex
	}
</style>
<noscript>
	<style>
		table.table{
			width:100%;
			border-collapse: collapse;
		}
		table.table tr,table.table th, table.table td{
			border:1px solid;
		}
		.text-cnter{
			text-align: center;
		}
	</style>
	<h3 class="text-center"><b>Student Result</b></h3>
</noscript>
<script>
	$('#update_status').click(function(){
		uni_modal("Update Status of: <?php echo $reference_number ?>","manage_parcel_status.php?id=<?php echo $id ?>&cs=<?php echo $status ?>","")
	})
</script>