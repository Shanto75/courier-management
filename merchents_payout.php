<?php @session_start() ?>
<?php include('db_connect.php') ?>
<?php
$twhere ="";
if($_SESSION['login_type'] != 3)
  $twhere = "  ";
?>
<!-- Info boxes -->
<?php if($_SESSION['login_type'] == 3): ?>
<!-- <div class="row m-auto">
            <div class="col-12 col-sm-6 col-md-3">
                <div class="small-box bg-primary shadow-sm border">
                <div class="inner">
                    <?php $data = $conn->query("SELECT price, reference_number FROM parcels WHERE user_id='".$_SESSION['login_id']."'")->fetch_array(); ?>
                    <?php 
                        $totalPrice = 0;
                        $totalDeliveryCost = 0;
                        $ref_numbers = [];
                        for($i=0;$i<count($data); $i++){
                            $totalPrice += $data['price'];
                            $ref_number = $data['reference_number'];
                            array_push($ref_numbers, $ref_number);
                        }
                        // Getting unique ref_numbers
                        $fresh_refs = array_unique($ref_numbers);
                        // print_r($fresh_refs);
                        // Getting total delivary_cost from unique refs
                        for($i=0; $i< count($fresh_refs); $i++){
                            $dcost = $conn->query("SELECT delivery_cost FROM parcels WHERE reference_number='".$fresh_refs[$i]."'")->fetch_array()['delivery_cost'];
                            $totalDeliveryCost += $dcost;
                        }
                    ?>
                    <h3><?php echo ($totalPrice + $totalDeliveryCost); ?> TK</h3>
                    <p>Total Amount</p>
                </div>
                <div class="icon">
                    <i class="fa fa-boxes"></i>
                </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-2">
                <div class="small-box bg-warning shadow-sm border">
                <div class="inner">
                    <h3><?php echo $totalDeliveryCost; ?> TK</h3>
                    <p>Total Delivery Cost</p>
                </div>
                <div class="icon">
                    <i class="fa fa-boxes"></i>
                </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-2">
                <div class="small-box bg-success shadow-sm border">
                <div class="inner">
                    <h3><?php $received = $conn->query("SELECT sum(amount) FROM payouts WHERE status=1 and  merchent_id='".$_SESSION['login_id']."'")->fetch_array()['sum(amount)']; ?> <?php echo $received; ?> TK</h3>
                    <p>Payment Received</p>
                </div>
                <div class="icon">
                    <i class="fa fa-boxes"></i>
                </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-2 ">
                <div class="small-box bg-info shadow-sm border">
                <div class="inner">
                    <?php $pendingAmount = 0; ?>
                    <h3>
                        <?php 
                            $pending = $conn->query("SELECT sum(amount) FROM payouts WHERE status=0 and  merchent_id='".$_SESSION['login_id']."'")->fetch_array()['sum(amount)'];
                            if($pending > 0){
                                $pendingAmount = $pending;
                            }
                            echo $pendingAmount;
                        ?>
                    TK</h3>
                    <p>Payments Pending</p>
                </div>
                <div class="icon">
                    <i class="fa fa-building"></i>
                </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-3 ">
                <div class="small-box bg-secondary shadow-sm border">
                <div class="inner">
                    <h3><?php echo (($totalPrice - $received) - $pendingAmount); ?> Tk</h3>
                    <p>Payments Receivable</p>
                </div>
                <div class="icon">
                    <i class="fa fa-building"></i>
                </div>
                </div>
            </div>
        </div> -->
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
                            href="./index.php?page=merchent_request_payout"><i class="fa fa-money-bill"></i> Request For
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
                        <th>Merchent Id</th>
                        <th>Payout Method</th>
                        <th>Mobile banking</th>
                        <th>Bank Account</th>
                        <th>Amount</th>
                        <th>Bill ID</th>
                        <th>Payout Status</th>
                        <th>Request Date</th>
                        <th>Payment Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                            $query = $conn->query("SELECT * FROM payouts WHERE  merchent_id='".$_SESSION['login_id']."'");
                            $index = 0;
                            while($row = $query->fetch_assoc()) { $index++; ?>
                    <tr>
                        <td> <?php  echo $index?></td>
                        <td> <?php  echo $row['parcelref']?></td>
                        <td> <?php  echo $row['merchent_id']?></td>
                        <td> <?php  echo $row['payout_method']?></td>
                        <td> <?php  echo $row['mobilebanking']?></td>
                        <td> <?php  echo $row['account']?></td>
                        <td> <?php  echo $row['amount']?></td>
                        <td> <?php  echo $row['billid']?></td>
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


<?php else: ?>
<div class="col-12">
    <div class="card">
        <div class="card-body">
            Welcome <?php echo $_SESSION['login_name'] ?>!
        </div>
    </div>
</div>

<?php endif; ?>