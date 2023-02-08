<?php @session_start() ?>
<?php include('db_connect.php') ?>
<?php
$twhere ="";
if($_SESSION['login_type'] != 3)
  $twhere = "  ";
?>
<?php
$alert = false; 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if(isset($_POST['parcelref']) && !empty($_POST['payout_method'])){
        $ref = $_POST['parcelref'];
    $sql = "select * from payouts where parcelref='$ref' ";
    $result = mysqli_query($conn, $sql);
    $num = mysqli_num_rows($result);
    if($num > 0){
        $alert = "Payout Request Failed !!! You Have Already Requested for this parcel ";
    }
    else{
        extract($_POST);
            $data = '';
            foreach($_POST as $k => $v){
                if(!is_numeric($k)){
                    if(empty($data)){
                        $data .= " $k='$v' ";
                    }else{
                        $data .= ", $k='$v' ";
                    }
                }
            }
            
        $sql = "INSERT INTO payouts set $data";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            $alert = "Payout Request Successfull !!!";
        }
        else {
            $alert = "Payout Request Failed !!!";
        }
    }

    }else {
        $alert = "Payout Request Failed !!!";
    }
}

?>
<div class="modal fade mx-auto" id="payout" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form class=" mx-auto p-5" action="index.php?page=merchent_request_payout" method="post">
                <h2 class="text-center">Request Payout</h2>
                <h5 id="request_amount" class="text-center"></h5>
                <input type="hidden" name="parcelref" id="parcelref">
                <input type="hidden" name="merchent_id" id="merchent_id" value="<?php echo $_SESSION['login_id']?>">
                <input type="hidden" name="amount" id="amount">

                <label class="control-label">Payout Method</label>
                <select onchange="selectPayout(this.value)" name="payout_method" id="payout_method"
                    class="form-select form-control my-2" aria-label="Select Your payout" required>
                    <option selected disabled>Select Your Payout Method</option>
                    <option value="Mobile banking">Mobile banking</option>
                    <option value="Bank">Bank</option>
                    <option value="Cash">Cash</option>
                </select>
                <div id="addmobilebanking"></div>
                <div id="addaccount"></div>

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
            <?php if($alert){
                echo '<div id="alert" class="text-center text-bold p-2 bg-yellow">' . $alert . '</div>';
            }
            ?>
            <table class="table tabe-hover table-bordered table-sm text-break text-sm text-center" id="list">
                <thead>
                    <tr class="text-center">
                        <th>#</th>
                        <th>Tracking Number</th>
                        <th>Order Info</th>
                        <th>Status</th>
                        <th>Collected Amount</th>
                        <th>COD</th>
                        <th>Delivery Charge</th>
                        <th>Total Charge</th>
                        <th>Paid Out</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
					$logged_in_user_id = $_SESSION['login_id'];
                    $qry = $conn->query("SELECT * from parcels where user_id = $logged_in_user_id and status = 9"); 
                    
					$index = 0;
                     
                        
                    while($row= $qry->fetch_assoc()):
                        $index++;
					?>

                    <tr class="text-break">
                        <td class="text-center"><?php echo $index ?></td>
                        <td class="text-center"><b><?php echo $row['reference_number'] ?></b></td>
                        <td><b><?php echo ($row['recipient_name'].'<br>'.$row['recipient_address']) ?></b></td>
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
                        <td>
                            <b><?php $collectedAmount=$row['price'] + $row['cod'] + $row['delivery_cost'];
                            echo $collectedAmount ?></b>
                        </td>
                        <td><b><?php echo $row['cod'] ?></b></td>
                        <td><b><?php echo $row['delivery_cost'] ?></b></td>
                        <td><b><?php echo $row['cod'] + $row['delivery_cost'] ?></b></td>
                        <td><b><?php echo $collectedAmount - ($row['cod'] + $row['delivery_cost']) ?></b></td>

                        <td class="text-center">
                            <div>
                                <button onClick="payoutbtn(this.id)" type="button"
                                    class="btn btn-sm btn-warning btn-flat" id="<?php echo $index ?>">
                                    <i class="fas fa-money-bill-wave"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
function payoutbtn(id) {

    table = document.getElementById("list").rows[id];

    parcelref.value = table.cells[1].innerText;
    amount.value = parseInt(table.cells[8].innerText);
    request_amount.innerText = "Your Request Amount: " + table.cells[8].innerText;

    // console.log(document.getElementById("list").rows[id].cells[1].innerText);
    console.log(amount.value);

    $('#payout').modal('toggle');
}


function selectPayout(payout) {
    document.getElementById("addmobilebanking").innerHTML = "";
    document.getElementById("addaccount").innerHTML = "";
    if (payout === "Mobile banking") {
        var value =
            '<select onchange="selectmobilebank(this.value)" name="mobilebanking" id="mobilebanking" class="form-select form-control my-2" aria-label="Select Your payout"> <option selected disabled>Mobile banking List</option> <option value="Bkash">Bkash</option> <option value="Nogod">Nogod</option> </select>';
        document.getElementById("addmobilebanking").innerHTML = value;
    }
    if (payout === "Bank") {
        var value =
            '<input class="form-control my-2" name="account" id="account" type="text" placeholder="Enter account number">';
        document.getElementById("addaccount").innerHTML = value;
    }
}

function selectmobilebank(mobilebank) {
    if (mobilebank === "Bkash") {
        var value =
            '<input class="form-control my-2" name="account" id="account" type="text" placeholder="Enter account number">';
        document.getElementById("addaccount").innerHTML = value;
    }
    if (mobilebank === "Nogod") {
        var value =
            '<input class="form-control my-2" name="account" id="account" type="text" placeholder="Enter account number">';
        document.getElementById("addaccount").innerHTML = value;
    }
}
$("#alert").fadeOut(6000);
$(document).ready(function() {
    $('#list').DataTable()
})
</script>