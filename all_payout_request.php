<?php @session_start() ?>
<?php include('db_connect.php') ?>
<?php
$twhere ="";
if($_SESSION['login_type'] != 1)
  $twhere = "  ";
?>

<?php
$alert = false; 
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['parcelid'])) {
    $ref = $_POST['parcelid'];
    $billidnumber = $_POST['billnumber'];
    $sql = "UPDATE payouts set billid = '$billidnumber' where parcelref = '$ref' ";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        $alert = "Bill id Successfully Added !!!";
    }
    else {
        $alert = "Failed to add bill id !!!";
    }
}
?>


<div class="modal fade mx-auto" id="addbillid" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form class=" mx-auto p-5" action="index.php?page=all_payout_request" method="post">
                <h5 class="text-center">Enter Bill ID</h5>
				<input type="hidden" name="parcelid" id="parcelid">
                <input name="billnumber"  id="billnumber" class="form-control" type="text" >
				<div class="d-flex align-items-center justify-content-center ">
                    <button type="submit" class="py-1 mt-4 px-3 btn btn-outline-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="card card-outline card-primary">
    <div class="card-header">

        <div class="">
            <div class="row">
                <div class="col-md-4">
                    <p>Payment History</p>
                </div>
            </div>
        </div>
    </div>
    <?php if($alert){
                echo '<div id="alert" class="text-center text-bold p-2 bg-yellow">' . $alert . '</div>';
            }
    ?>
    <div class="card-body">
        <table class="table tabe-hover table-bordered table-sm text-sm text-break" id="list">
            <thead>
                <tr>
                    <th class="text-center">#</th>
                    <th>Parcel reference</th>
                    <th>Merchent Id</th>
                    <th>Payout Method</th>
                    <th>Amount</th>
                    <th>Payout Status</th>
                    <th>Bill ID</th>
                    <th>Request Date</th>
                    <th>Payment Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $query = $conn->query("SELECT * FROM payouts order by created_at DESC");
                    $index = 0;
                    while($row = $query->fetch_assoc()) { $index++; ?>
                <tr>
                    <td> <?php  echo $index?></td>
                    <td> <?php  echo $row['parcelref']?></td>
                    <td> <?php  echo $row['merchent_id']?></td>
                    <td> <?php  echo $row['payout_method'].' <br> '.$row['mobilebanking'].' <br> '. $row['account']?></td>
                    
                    <td> <?php  echo $row['amount']?></td>
                    <td>
                        <?php  
                            $status = '';
                            if($row['status'] == 0){
                                $status = '<h4><span class="badge badge-warning">Pending...</span></h4>';
                            }else if($row['status'] == 1){
                                $status = '<h4><span class="badge badge-success">Completd</span></h4>';
                            }else if($row['status'] == 2){
                                $status = '<h4><span class="badge badge-danger">Canceled</span></h4>';
                            }else{
                                $status = '<h4><span class="badge badge-secondary">Unknown</span></h4>';
                            }
                            echo $status;
                            ?>
                    </td>
                    <td> <?php  echo $row['billid']?></td>
                    <td> <?php  echo $row['created_at']?></td>
                    <td> <?php  echo $row['paid_at']?></td>
                    <td>
                        <button onclick="addbillidbtn(this.id)" type="button" class="btn btn-sm btn-primary"
                            id="<?php echo $row['parcelref'] ?>">
                            Add Bill Id
                        </button>
                        <button onclick="confirm_payout(this.id)" type="button" class="btn btn-sm btn-success confirm"
                            id="<?php echo $row['parcelref'] ?>">
                            Confirm
                        </button>
                        <button onclick="cancel_payout(this.id)" type="button" class="btn btn-sm btn-danger cancel" id="<?php echo $row['parcelref'] ?>">
                            Cancel
                        </button>
                    </td>
                </tr>
                <?php }?>
            </tbody>
        </table>
    </div>
</div>

<script>

function addbillidbtn(id){
    parcelid.value = id;
    $('#addbillid').modal('toggle');
}

function confirm_payout($id) {
    start_load()
    $.ajax({
        url: 'ajax.php?action=confirm_payout',
        method: 'POST',
        data: {
            id: $id
        },
        success: function(resp) {
            if (resp == 1) {
                alert_toast("Payout successfully Confirmed", 'success')
                setTimeout(function() {
                    location.reload()
                }, 1500)

            }
            if (resp == 2) {
                alert_toast("Failed to confirm payout request !! Add your bill id.", 'warning')
                setTimeout(function() {
                    location.reload()
                }, 3000)
            }
        }
    })
}

function cancel_payout($id) {
    start_load()
    $.ajax({
        url: 'ajax.php?action=cancel_payout',
        method: 'POST',
        data: {
            id: $id
        },
        success: function(resp) {
            if (resp == 1) {
                alert_toast("Payout successfully Canceled", 'success')
                setTimeout(function() {
                    location.reload()
                }, 1500)

            }
        }
    })
}
$("#alert").fadeOut(6000);
</script>