<?php include'db_connect.php' ?>
<?php
$alert = false; 
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['parcelid'])) {
        
	$id = $_POST["parcelid"];
	$riderid = $_POST["riderlist"];
	$sql = "UPDATE parcels set riderid = '$riderid' where `parcels`.`id` = '$id'";
	$result = mysqli_query($conn, $sql);
	if ($result) {
    	$alert = "Rider successfully Added!";
    }
}

$riders = array();
$ridersid = array();

$qry = $conn->query("SELECT *,concat(firstname,' ',lastname,' ',staff_id) as name FROM users WHERE type=4 order by concat(lastname, firstname) asc");
	 while($row= $qry->fetch_assoc()){
		array_push($riders,$row['name']);
		array_push($ridersid,$row['id']);
	}
?>
<div class="modal fade mx-auto" id="addrider" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form class=" mx-auto p-5" action="index.php?page=parcel_list" method="post">
                <h5 class="text-center">Add or Update Rider</h5>
                <input type="hidden" name="parcelid" id="parcelid">
                <select name="riderlist" id="riderlist" class="custom-select">
                </select>
                <div class="d-flex align-items-center justify-content-center ">
                    <button type="submit" class="py-1 mt-4 px-3 btn btn-outline-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="col-lg-12 overflow-auto">
    <div class="">
        <?php if($alert){
                echo '<div id="alert" class="text-center text-bold p-2 bg-yellow">' . $alert . '</div>';
            }
            ?>

        <a class="btn btn-outline-primary float-right my-2 " href="./index.php?page=new_parcel"><i
                class="fa fa-plus"></i> Add New</a>

        <h3 class="text-center text-success" id="rideralert"></h3>
        <div class=" py-5 ">
            <table class="table tabe-hover table-bordered table-sm text-wrap text-sm text-center" id="list">
                <thead>
                    <tr class="text-center">
                        <th class="text-center">#</th>
                        <th>Tracking Number</th>
                        <th>Delivery-Time</th>
                        <th>Sender Name</th>
                        <th>Receiver Name</th>
                        <th>Receiver-Phone</th>
                        <th>Address</th>
                        <th>Product</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php

                    $wanted_status = $_GET['s'];
					$logged_in_user_id = $_SESSION['login_id'];
					// if($_SESSION['login_type'] == 1){
					// 	if(empty($wanted_status)){
					// 		$qry = $conn->query("SELECT MIN(id) AS id, reference_number,status, sender_name, delivery_within, sender_contact, sender_address, recipient_name, recipient_contact, recipient_address, type, from_branch_id, to_branch_id, item_name , date_created FROM parcels  GROUP BY date_created DESC"); 
					// 	}else{
					// 		echo "Status Requirement Found: ".$wanted_status;
					// 		$qry = $conn->query("SELECT MIN(id) AS id, reference_number,status,  sender_name, sender_contact, delivery_within, sender_address, recipient_name, recipient_contact, recipient_address, type, from_branch_id, to_branch_id, item_name , date_created FROM parcels WHERE status='".$wanted_status."'  GROUP BY date_created DESC");
					// 	}
					// }else{
					// 	if(empty($wanted_status)){
					// 		$qry = $conn->query("SELECT MIN(id) AS id, reference_number,status, sender_name, delivery_within, sender_contact, sender_address, recipient_name, recipient_contact, recipient_address, type, from_branch_id, to_branch_id, item_name , date_created FROM parcels WHERE user_id='".$logged_in_user_id."' GROUP BY date_created DESC"); 
					// 	}else{
					// 		// echo "Status Requirement Found: ".$wanted_status;
					// 		$qry = $conn->query("SELECT MIN(id) AS id, reference_number,status,  sender_name, sender_contact, delivery_within, sender_address, recipient_name, recipient_contact, recipient_address, type, from_branch_id, to_branch_id, item_name , date_created FROM parcels WHERE status='".$wanted_status."' and user_id='".$logged_in_user_id."' GROUP BY date_created DESC");
					// 	}
					// }

                    if($_SESSION['login_type'] == 1){
						if(empty($wanted_status)){
							$qry = $conn->query("SELECT * FROM parcels  ORDER BY date_created DESC"); 
						}else{
							echo "Status Requirement Found: ".$wanted_status;
							$qry = $conn->query("SELECT * FROM parcels WHERE status='".$wanted_status."' ORDER BY date_created  DESC");
						}
					}else{
						if(empty($wanted_status)){
							$qry = $conn->query("SELECT * FROM parcels WHERE user_id='".$logged_in_user_id."' ORDER BY date_created DESC"); 
						}else{
							// echo "Status Requirement Found: ".$wanted_status;
							$qry = $conn->query("SELECT * FROM parcels WHERE status='".$wanted_status."' and user_id='".$logged_in_user_id."' ORDER BY date_created DESC");
						}
					}
                    
					$index = 0;
                     
                        
                    while($row= $qry->fetch_assoc()):
                        $index++;
                        $delivery_time = $row['delivery_within'];
					?>

                    <tr class="text-break">
                        <td class="text-center"><?php echo $index ?></td>
                        <td><b><?php echo ($row['reference_number']) ?></b></td>
                        <td><b><?php
                                switch($row['delivery_within']){
                                    case 6:
                                        echo "<p class='text-danger'>6 Hours</p>";
                                        break;
                                    case 12:
                                        echo "<p class='text-warning'>Same Day</p>";
                                        break;
                                    case 24:
                                        echo "<p class='text-primary'>24 Hours</p>";
                                        break;
                                    case 0:
                                        echo "<p class='text-danger'>Regular</p>";
                                        break;
                                    default:
                                        echo "<p class='text-success'>Regular</p>";
                                        break;
                                }
                        ?></b></td>
                        <td><b><?php echo ucwords($row['sender_name']) ?></b></td>
                        <td><b><?php echo ucwords($row['recipient_name']) ?></b></td>
                        <td><b><?php echo ucwords($row['recipient_contact']) ?></b></td>
                        <td><b><?php echo ucwords($row['recipient_address']) ?></b></td>
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
									echo "<span class='badge badge-pill badge-info'> Picked-up</span>";
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

                                <?php
                                if($_SESSION['login_type'] == 1){?>
                                <button type="button" class="btn btn-sm btn-success btn-flat print_invoice"
                                    data-id="<?php echo $row['id'] ?>">
                                    <i class="fas fa-print"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-info btn-flat view_parcel"
                                    data-id="<?php echo $row['id'] ?>">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <a href="index.php?page=edit_parcel&id=<?php echo $row['id'] ?>"
                                    class="btn btn-sm btn-primary btn-flat ">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" id="<?php echo $row['id'] ?>" onclick="riderbtn(this.id)"
                                    class="btn btn-sm btn-warning btn-flat select_rider"
                                    data-id="<?php echo $row['id'] ?>">
                                    <i class="fas fa-truck"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-danger btn-flat delete_parcel"
                                    data-id="<?php echo $row['id'] ?>">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <?php }?>

                                <?php
                                if($_SESSION['login_type'] == 3){?>
                                <button type="button" class="btn btn-sm btn-success btn-flat print_invoice"
                                    data-id="<?php echo $row['id'] ?>">
                                    <i class="fas fa-print"></i>
                                </button>
                                <?php
                                        if($row['status'] == 1){?>
                                <a href="index.php?page=edit_parcel&id=<?php echo $row['id'] ?>"
                                    class="btn btn-sm btn-primary btn-flat ">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <?php }?>
                                <?php }?>
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
var rider = new Array();
<?php foreach($riders as $key => $val){ ?>
rider.push('<?php echo $val; ?>');
<?php } ?>

var riderid = new Array();
<?php foreach($ridersid as $key => $val){ ?>
riderid.push('<?php echo $val; ?>');
<?php } ?>

function riderbtn(id) {
    var options = "";
    rider.forEach(myFunction);

    function myFunction(value, index) {
        options += "<option value=" + riderid[index] + ">" + value + "</option>";
    }
    document.getElementById("riderlist").innerHTML = options;

    parcelid.value = id;
    console.log(parcelid.value);

    $('#addrider').modal('toggle');

}

// var select_riders = document.getElementsByClassName('select_rider');
// Array.from(select_riders).forEach((element) => {
//     element.addEventListener("click", (e) => {

// 		var options = "";
// 		rider.forEach(myFunction);
// 		function myFunction(value, index) {
// 			options += "<option value="+riderid[index]+">" +value+ "</option>";
// 		}
// 		document.getElementById("riderlist").innerHTML = options;

// 		parcelid.value = e.target.id;
// 		console.log(parcelid.value);

//         $('#addrider').modal('toggle');
//     })
// })

$(document).ready(function() {
    // $('#list').DataTable()
    $('.view_parcel').click(function() {
        uni_modal("Parcel's Details", "view_parcel.php?id=" + $(this).attr('data-id'), "large")
    })
    $('.print_invoice').click(function() {

        // window.open("/software/invoice/index.php?id="+$(this).attr('data-id'), 'Print');
        window.open("invoice/index.php?id=" + $(this).attr('data-id'), 'Print');
    })
    $('.delete_parcel').click(function() {
        _conf("Are you sure to delete this parcel?", "delete_parcel", [$(this).attr('data-id')])
    })
    $('#list').DataTable()
})

function delete_parcel($id) {
    start_load()
    $.ajax({
        url: 'ajax.php?action=delete_parcel',
        method: 'POST',
        data: {
            id: $id
        },
        success: function(resp) {
            if (resp == 1) {
                alert_toast("Data successfully deleted", 'success')
                setTimeout(function() {
                    location.reload()
                }, 1500)

            }
        }
    })
}
var addrider = '<?php echo $addrider ?>';

$("#alert").fadeOut(5000);
</script>