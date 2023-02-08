<?php include 'db_connect.php' ?>
<div class="col-lg-12">
    <div class="card card-outline card-primary">
        <div class="card-body">
            <div class="d-flex w-100 px-1 py-2 justify-content-center align-items-center">
                <?php

                $riders = array();
                $ridersid = array();

                $qry = $conn->query("SELECT * from users where type = 3");
                    while($row= $qry->fetch_assoc()){
                        $name = $row['firstname'].' '.$row['lastname'];
                        array_push($riders,$name);
                        array_push($ridersid,$row['id']);
                    }
                    ?>
                <form class=" mx-auto p-5 text-dark" action="index.php?page=merchent_report" method="post">
                    <label for="date_from" class="mx-1">Name</label>
                    <select name="riderid" id="riderid" class="custom-select custom-select-sm ">
                        <option selected disabled>Select Merchant Name</option>
                        <?php foreach($riders as $k => $v): ?>
                        <option value="<?php echo $ridersid[$k] ?>"><?php echo $v; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit" class="py-1 mt-4 px-3 btn btn-outline-primary">View Report</button>
                </form>
                <a class="btn btn-sm btn-primary mx-1 bg-gradient-primary"
                    href="index.php?page=merchent_report">Refresh/View New Report</a>

            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 ">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <button type="button" class="btn btn-success float-right" id="print"><i
                                    class="fa fa-print"></i> Print</button>
                        </div>
                    </div>

                    <table class="table table-bordered table-sm text-sm text-break text-center" id="report-list">
                        <thead>
                            <tr>
                                <th>Tracking</th>
                                <th>Recipient Address</th>
                                <th>Product Name</th>
                                <th>Total Price</th>
                                <th>Payment Status</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $merchantname='';
                            if( isset($_POST['riderid'])){
                                $id = $_POST['riderid'];
                                $merchantname = $conn->query("SELECT * from users where id = $id")->fetch_assoc();
                                $staffid = $merchantname['staff_id'];
                                $merchantname = $merchantname['firstname'].' '.$merchantname['lastname'];
                            }
                            
                            $subtotal = 0;
                            if( isset($_POST['riderid'])):
                                $id = $_POST['riderid'];

                                $status = array();
                                $statusref = array();
            
                            $qry = $conn->query("SELECT * from payouts where merchent_id = $id");
                                while($row= $qry->fetch_assoc()){
                                    array_push($status,$row['status']);
                                    array_push($statusref,$row['parcelref']);
                                }

                                $qry = $conn->query("SELECT * from parcels where user_id = $id and status=9 order by date_created ");
                                // $qry = $conn->query("SELECT * FROM parcels INNER JOIN payouts ON parcels.reference_number=payouts.parcelref and user_id = $id and status=9 order by date_created");

                                while($row= $qry->fetch_assoc()): $sl+=1; $subtotal+= $row['price'] ?>
                            <tr>
                                <td><?php echo $row['reference_number']?></td>
                                <td><?php echo $row['recipient_address'].', <br> Phone: '.$row['recipient_contact'] ?></td>
                                <td><?php echo $row['item_name'] ?></td>
                                <td><?php echo $row['price'] ?></td>
                                <td>
                                    <?php
                                    $paymentstatus = 0;
                                    for ($x = 0; $x <= sizeof($status); $x++) {
                                        if($statusref[$x] == $row['reference_number']){
                                            $paymentstatus = $status[$x];
                                        }
                                      }
                                      $stat = '';
                                      if($paymentstatus == 0){
                                        $stat = '<h4><span class="badge badge-warning">Pending...</span></h4>';
                                        }else if($paymentstatus == 1){
                                        $stat = '<h4><span class="badge badge-success">Completd</span></h4>';
                                        }else if($paymentstatus == 2){
                                        $stat = '<h4><span class="badge badge-danger">Canceled</span></h4>';
                                        }else{
                                        $stat = '<h4><span class="badge badge-secondary">Unknown</span></h4>';
                                        }
                                        echo $stat;

                                    ?>
                                </td>
                                <td></td>
                            </tr>
                            <?php endwhile; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                    <h5 id="total_price" class="float-right p-4"> Sub Total Price: <?php echo $subtotal; ?></h5>
                </div>
            </div>

        </div>
    </div>

</div>
<noscript>
    <style>
    table.table {
        width: 100%;
        border-collapse: collapse;
    }

    table.table tr,
    table.table th,
    table.table td {
        border: 1px solid;
    }

    .text-cnter {
        text-align: center;
    }
    </style>
    <h1 class="text-cnter"><b>Merchent Report</b></h1>
    <h3 class="text-cnter"><b>Merchent: <?php echo $merchantname;?> </b></h3>
    <h3 class="text-cnter"><b>Staff Id: <?php echo $staffid;?> </b></h3>
</noscript>
<script>
$('#print').click(function() {
    var ns = $('noscript').clone()
    var content = $('#report-list').clone()
    var subtotal = $('#total_price').clone()
    ns.append(content)
    ns.append(subtotal)
    var nw = window.open('', '', 'height=700,width=900')
    nw.document.write(ns.html())
    nw.document.close()
    nw.print()
})
</script>