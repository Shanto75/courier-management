<?php @session_start() ?>
<?php include'db_connect.php' ?>
<?php
$addstatus = false;
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['parcelid'])) {
        
	$id = $_POST["parcelid"];
	$status = $_POST["status"];
    // $stat = intval($_POST["status"]) ;

	$sql = "UPDATE parcels set status = '$status' where `parcels`.`id` = '$id'";
	$result = mysqli_query($conn, $sql);

    $qry = "SELECT reference_number, recipient_contact from parcels where `parcels`.`id` = '$id'";
    $result = mysqli_query($conn, $qry);
    $row = mysqli_fetch_assoc($result);
    $ref = $row['reference_number'];
    $phone = $row['recipient_contact'];
    
    $qry = "INSERT INTO parcel_tracks set status = '$status', parcel_id= '$ref' ";
	$result = mysqli_query($conn, $qry);
  
    $riderid = $_SESSION['login_id'];
    $qry = "SELECT * from users where id = $riderid";
    $result = mysqli_query($conn, $qry);
    $row = mysqli_fetch_assoc($result);

    $name = $row['firstname'].'%20'.$row['lastname'];
    $id = $row['staff_id'];
    $deliveryManPhone = $row['phone'];

    if($status == 8){
        $statusText = "Arrived%20at%20Destination";
        
        // $msg = "Your%20Parcel%20Status%20".$statusText."%20Reference%20".$ref."%20For%20Support%20Contact%20With%2001924001500";
        $msg = "Our%20Delivery%20Man%20Name:%20".$name."%20ID:%20".$id."%20Phone%20Number:%20".$deliveryManPhone."%20has%20picked%20up%20your%20order%20for%20delivery.";

        try{
            $url = "http://api.boom-cast.com/boomcast/WebFramework/boomCastWebService/externalApiSendTextMessage.php?masking=NOMASK&userName=Times&password=4efb9ec959512abf408b4a33f5fcc6cb&MsgType=TEXT&receiver=$phone&message=$msg";
    
            $ch = curl_init( $url );
            curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt( $ch, CURLOPT_HEADER, 0);
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);
            $response = curl_exec( $ch );
        }catch(Exception $e){}
    }

	if ($result) {
    	$addstatus = "Status successfully Updated!";
    }
    else{
        $addstatus = "Faild to Updated Status!";
    }
}

$status_arr = array("Item Accepted by Courier","Ready to Pickup","Picked-up","Collected","Shipped","In-Transit","Hold","Arrived At Destination","Delivered","Unsuccessfull Delivery Attempt/Return");

?>

<div class="modal fade mx-auto" id="statusmodal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form class=" mx-auto p-5" action="index.php?page=rider_parcel" method="post">
                <h5 class="text-center">Update Status</h5>
                <input type="hidden" name="parcelid" id="parcelid">
                <select name="status" id="status" class="custom-select custom-select-sm ">
                    <?php foreach($status_arr as $k => $v): ?>
                    <option value="<?php echo $k+1 ?>"><?php echo $v; ?></option>
                    <?php endforeach; ?>
                </select>
                
				<div class="d-flex align-items-center justify-content-center ">
                    <button type="submit" class="py-1 mt-4 px-3 btn btn-outline-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

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
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php

					$logged_in_user_id = $_SESSION['login_id'];
                    $qry = $conn->query("SELECT * from parcels where riderid = $logged_in_user_id order by date_created DESC"); 
					
                    
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
                        <td class="text-center">
                            <div class="">
                                <?php if($row['status']!=9):?>
                                <button type="button" id="<?php echo $row['id'] ?>" onclick="update(this.id)" class="btn btn-sm btn-warning btn-flat update"
									data-id="<?php echo $row['id'] ?>">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <?php endif;?>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<style>
table td {
    vertical-align: middle !important;
}
</style>
<script>

function update(id){

    parcelid.value = id;
	console.log(parcelid.value);
    $('#statusmodal').modal('toggle');
}

$(document).ready(function() {
    $('#list').DataTable()
})

var addstatus = '<?php echo $addstatus ?>';
if(addstatus){
	$("#statusalert").text(addstatus).fadeOut(4500);
}
</script>