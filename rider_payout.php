<?php @session_start() ?>
<?php include('db_connect.php') ?>
<?php
$twhere ="";
if($_SESSION['login_type'] != 4)
  $twhere = "  ";
?>
<div class="col-lg-12">
    <div class="card card-outline card-primary">
        <div class="card-header">

            <div class="">
                <div class="row">
                    <div class="col-md-4">
                        <p>Payment Status and History</p>
                    </div>
                    <div class="col-md-6 justify-content-right">
                    </div>
                    <div class="col-md-2">
                        <a class="btn btn-sm btn-default btn-flat border-primary "
                            href="./index.php?page=rider_payout_request"><i class="fa fa-money-bill"></i> Request For
                            Payment</a>
                    </div>
                </div>

            </div>
        </div>
        <div class="card-body">
            <table class="table tabe-hover table-bordered table-sm text-sm text-break" id="list">
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th>Parcel reference</th>
                        <th>Bill Id</th>
                        <th>Payout Method</th>
                        <th>Mobile banking</th>
                        <th>Bank Account</th>
                        <th>Amount</th>
                        <th>Payout Status</th>
                        <th>Request Date</th>
                        <th>Payment Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                            $query = $conn->query("SELECT * FROM rider_payout WHERE rider_id='".$_SESSION['login_id']."' order by created_at DESC ");
                            $index = 0;
                            while($row = $query->fetch_assoc()) { $index++; ?>
                    <tr>
                        <td> <?php  echo $index?></td>
                        <td> <?php  echo $row['parcelref']?></td>
                        <td> <?php  echo $row['billid']?></td>
                        <td> <?php  echo $row['payout_method']?></td>
                        <td> <?php  echo $row['mobilebanking']?></td>
                        <td> <?php  echo $row['account']?></td>
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
                        <td> <?php  echo $row['created_at']?></td>
                        <td> <?php  echo $row['paid_at']?></td>
                    </tr>
                    <?php }?>
                </tbody>
            </table>
        </div>
    </div>
</div>


