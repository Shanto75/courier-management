<?php @session_start() ?>
<?php include('db_connect.php') ?>
<?php
$twhere ="";
if($_SESSION['login_type'] != 3)
  $twhere = "  ";
?>
<!-- Info boxes -->
<?php if($_SESSION['login_type'] == 3): ?>
        <div class="row m-auto">
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
        </div>
        <div class="col-lg-12">
	<div class="card card-outline card-primary">
		<div class="card-header">
           
			<div class="">
                <div class="row">
                    <div class="col-md-4">
                    <p>Request Payment</p>
                    </div>
                    <div class="col-md-6 justify-content-right">
                    </div>
                    <div class="col-md-2">
                    <a class="btn btn-sm btn-default btn-flat border-primary " href="./index.php?page=merchents_payout"><i class="fa fa-money-bill"></i> Payments History</a>
                    </div>
                </div>
          
			</div>
		</div>
		 
    </div>
    <?php 
        $maxPayoutAmount = ($totalPrice - $received) - $pendingAmount;
    ?>
    <div class="row">
        <div class="col-md-4 m-auto">
        <div class="card card-outline card-primary">
		<div class="card-body">
		<form action="" id="payment_request">
        <div class="row">
          <div class="col-md-12">
            <div id="msg" class=""></div>
            <div class="row">
              <div class="col-sm-12 form-group ">
              <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
              <input type="hidden" name="merchant_id" value="<?php echo $_SESSION['login_id'] ?>" />
              <input type="hidden" name="amount" value="<?php echo $maxPayoutAmount ?>" />
                <label for="" class="control-label">Payout Method</label>
                <select onchange="selectPayout(this.value)" name="payout_method" id="payout_method" class="form-select form-control my-2" aria-label="Select Your payout">
                    <option selected disabled>Select Your Payout Method</option>
                    <option value="Mobile banking">Mobile banking</option>
                    <option value="Bank">Bank</option>
                    <option value="Cash">Cash</option>
                </select>
                <div id="addmobilebanking"></div>
                <!-- <select name="mobilebanking" id="mobilebanking" class="form-select form-control my-2" aria-label="Select Your payout">
                    <option selected disabled>Mobile banking List</option>
                    <option value="1">Bkash</option>
                    <option value="2">Nogod</option>
                </select> -->
                <div id="addaccount"></div>
                <!-- <input class="form-control my-2" name="account" id="account" type="text" placeholder="Enter account number"> -->
              </div>
            </div>
          </div>
        </div>
      </form>
  	</div>
  	<div class="card-footer border-top border-info">
  		<div class="d-flex w-100 justify-content-center align-items-center">
  			<button class="btn btn-flat  bg-gradient-primary mx-2" form="payment_request">Request for Payout</button>
  			<a class="btn btn-flat bg-gradient-secondary mx-2" href="./index.php?page=merchants">Cancel</a>
  		</div>
  	</div>
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

function selectPayout(payout){
    document.getElementById("addmobilebanking").innerHTML = "";
    document.getElementById("addaccount").innerHTML = "";
    if(payout === "Mobile banking"){
        var value = '<select onchange="selectmobilebank(this.value)" name="mobilebanking" id="mobilebanking" class="form-select form-control my-2" aria-label="Select Your payout"> <option selected disabled>Mobile banking List</option> <option value="Bkash">Bkash</option> <option value="Nogod">Nogod</option> </select>';
        document.getElementById("addmobilebanking").innerHTML = value;
    }
    if(payout === "Bank"){
        var value = '<input class="form-control my-2" name="account" id="account" type="text" placeholder="Enter account number">';
        document.getElementById("addaccount").innerHTML = value;
    }
}

function selectmobilebank(mobilebank){
    if(mobilebank === "Bkash"){
        var value = '<input class="form-control my-2" name="account" id="account" type="text" placeholder="Enter account number">';
        document.getElementById("addaccount").innerHTML = value;
    }
    if(mobilebank === "Nogod"){
        var value = '<input class="form-control my-2" name="account" id="account" type="text" placeholder="Enter account number">';
        document.getElementById("addaccount").innerHTML = value;
    }
}
    $('#payment_request').submit(function(e){
		e.preventDefault()
		start_load()
        console.log("Clicked!")
		$.ajax({
			url:'ajax.php?action=payment_request',
			data: new FormData($(this)[0]),
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    type: 'POST',
			success:function(resp){
				if(resp == 1){
					alert_toast('Payout Request Successufll',"success");
					setTimeout(function(){
              location.href = 'index.php?page=merchents_payout'
					},2000)
				}else if(resp == 2){
                    alert_toast('Payout Method adding failed!',"error");
          end_load()
        }
			}
		})
	})
    $('.delete_payout_method').click(function(){
	_conf("Are you sure to delete this Payout Method?","delete_payout_method",[$(this).attr('data-id')])
	})
    function delete_payout_method($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_payout_method',
			method:'POST',
			data:{id:$id},
			success:function(resp){
				if(resp==1){
					alert_toast("Payout Method successfully deleted",'success')
					setTimeout(function(){
						location.reload()
					},1500)

				}
			}
		})
	}
</script>