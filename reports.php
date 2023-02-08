<?php include 'db_connect.php' ?>
<?php $status = isset($_GET['status']) ? $_GET['status'] : 'all' ?>
<div class="col-lg-12">
    <div class="card card-outline card-primary">
        <div class="card-body">
            <div class="d-flex w-100 px-1 py-2 justify-content-center align-items-center">
                <?php
			// if(isset($_GET['type'])){
			// }
			$merchant = array();
			$merchantid = array();

			$riders = array();
			$ridersid = array();

			$staff = array();
			$staffid = array();

			$qry = $conn->query("SELECT *,concat(firstname,' ',lastname,' ',staff_id) as name FROM users WHERE type=4 order by concat(lastname, firstname) asc");
				 while($row= $qry->fetch_assoc()){
					array_push($riders,$row['name']);
					array_push($ridersid,$row['id']);
				}
			$qry = $conn->query("SELECT *,concat(firstname,' ',lastname,' ',staff_id) as name FROM users WHERE type=3 order by concat(lastname, firstname) asc");
			while($row= $qry->fetch_assoc()){
			   array_push($merchant,$row['name']);
			   array_push($merchantid,$row['id']);
			}
			$qry = $conn->query("SELECT *,concat(firstname,' ',lastname,' ',staff_id) as name FROM users WHERE type=2 order by concat(lastname, firstname) asc");
			while($row= $qry->fetch_assoc()){
			   array_push($staff,$row['name']);
			   array_push($staffid,$row['id']);
			}

			$status_arr = array("Item Accepted by Courier","Ready to Pickup","Picked-up","Collected","Shipped","In-Transit","Hold","Arrived At Destination","Delivered","Unsuccessfull Delivery Attempt/Return"); ?>

                <label for="date_from" class="mx-1">Type</label>
                <select onchange="selectName(this.value)" name="" id="type" class="custom-select custom-select-sm col-sm-1">
					<!-- <option selected disabled>Select Type</option> -->
                    <option selected value="all">All Type</option>
					<option value="staff">Staff</option>
					<option value="merchant">Merchant</option>
                    <option value="rider">Rider</option>
                </select>

                <label for="date_from" class="mx-1">Name</label>
                <select name="" id="name" class="custom-select custom-select-sm col-sm-1">
                    <option selected value="all">All Name</option>
                </select>

                <label for="date_from" class="mx-1">Status</label>
                <select name="" id="status" class="custom-select custom-select-sm col-sm-1">
                    <option value="all" <?php echo $status == 'all' ? "selected" :'' ?>>All</option>
                    <?php foreach($status_arr as $k => $v): ?>
                    <option value="<?php echo $k+1 ?>"
                        <?php echo $status != 'all' && $status == $k+1 ? "selected" :'' ?>><?php echo $v; ?></option>
                    <?php endforeach; ?>
                </select>
                <label for="date_from" class="mx-1">From</label>
                <input type="date" id="date_from" class="form-control form-control-sm col-sm-1"
                    value="<?php echo isset($_GET['date_from']) ? date("Y-m-d",strtotime($_GET['date_from'])) : '' ?>">
                <label for="date_to" class="mx-1">To</label>
                <input type="date" id="date_to" class="form-control form-control-sm col-sm-1"
                    value="<?php echo isset($_GET['date_to']) ? date("Y-m-d",strtotime($_GET['date_to'])) : '' ?>">
                <button class="btn btn-sm btn-primary mx-1 bg-gradient-primary" type="button" id='view_report'>View
                    Report</button>
				<a class="btn btn-sm btn-primary mx-1 bg-gradient-primary" href="index.php?page=reports">Refresh/View New Report</a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 ">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <button type="button" class="btn btn-success float-right" style="display: none"
                                id="print"><i class="fa fa-print"></i> Print</button>
                        </div>
                    </div>

                    <table class="table table-bordered table-sm text-sm text-break text-center" id="report-list">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Order Info</th>
                                <th>Invoice Type</th>
                                <th>Marchant Order Id</th>
								<th>Collected Amount</th>
                                <th>COD</th>
                                <th>Delivery Charge</th>
                                <th>Total Charge</th>
								<th>Paid Out</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>

                    <table class=" float-right table table-sm text-sm text-break text-center" id="total_details">
                        <thead>
                            <tr>
								<th id="total_collected_amount"></th>
                                <th id="total_cod"></th>
                                <th id="total_delivery_charge"></th>
                                <th id="total_amount"></th>
								<th id="total_paid_out"></th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>

                    <!-- <div class="float-right py-4 text-bold" id="total_details">
						<p id="total_cod"></p>
						<p id="total_delivery_charge"></p>
						<p id="total_amount"></p>
					</div> -->

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
    <h3 class="text-center"><b>Report</b></h3>
</noscript>
<div class="details d-none">
    <p><b>Date Range:</b> <span class="drange"></span></p>
    <p><b>Status:</b> <span class="status-field">All</span></p>
</div>
<script>

var merchant = new Array();
<?php foreach($merchant as $key => $val){ ?>
merchant.push('<?php echo $val; ?>');
<?php } ?>

var merchantid = new Array();
<?php foreach($merchantid as $key => $val){ ?>
	merchantid.push('<?php echo $val; ?>');
<?php } ?>

var rider = new Array();
<?php foreach($riders as $key => $val){ ?>
	rider.push('<?php echo $val; ?>');
<?php } ?>

var riderid = new Array();
<?php foreach($ridersid as $key => $val){ ?>
	riderid.push('<?php echo $val; ?>');
<?php } ?>

var staff = new Array();
<?php foreach($staff as $key => $val){ ?>
	staff.push('<?php echo $val; ?>');
<?php } ?>

var staffid = new Array();
<?php foreach($staffid as $key => $val){ ?>
	staffid.push('<?php echo $val; ?>');
<?php } ?>

function selectName(type) {
    var options = "";
	if(type === 'merchant'){
		merchant.forEach(myFunction);
		function myFunction(value, index) {
			options += "<option value="+merchantid[index]+">" +value+ "</option>";
		}
	}
	if(type === 'rider'){
		rider.forEach(myFunction);
		function myFunction(value, index) {
			options += "<option value="+riderid[index]+">" +value+ "</option>";
		}
	}

	if(type === 'staff'){
		staff.forEach(myFunction);
		function myFunction(value, index) {
			options += "<option value="+staffid[index]+">" +value+ "</option>";
		}
	}

    document.getElementById("name").innerHTML = options;
}

function load_report() {
    start_load()
    var date_from = $('#date_from').val()
    var date_to = $('#date_to').val()
    var status = $('#status').val()
    var type = $('#type').val()
	var name = $('#name').val()

	var totalCollectedAmount = 0;
    var total_cod = 0;
    var total_delivery_charge = 0;
    var totalAmount = 0;
	var totalPaidout = 0;

    $.ajax({
        url: 'ajax.php?action=get_report',
        method: 'POST',
        data: {
            status: status,
            type: type,
			name:name,
            date_from: date_from,
            date_to: date_to
        },
        error: err => {
            console.log(err)
            alert_toast("An error occured", 'error')
            end_load()
        },
        success: function(resp) {
            if (typeof resp === 'object' || Array.isArray(resp) || typeof JSON.parse(resp) === 'object') {
                resp = JSON.parse(resp)
                if (Object.keys(resp).length > 0) {
                    $('#report-list tbody').html('')
                    var i = 1;
                    Object.keys(resp).map(function(k) {
                        var tr = $('<tr></tr>')
                        tr.append('<td>' + (i++) + '</td>')
                        tr.append('<td>' + (resp[k].reference_number) + '<br>' + (resp[k]
                                .recipient_name) + '<br>' + (resp[k].recipient_address) +
                            '</td>')
                        tr.append('<td>' + (resp[k].status) + '</td>')
                        tr.append('<td>' + (resp[k].merchant_order_id) + '</td>')
                        CollectedAmount = parseInt(resp[k].price) + parseInt(resp[k].cod) + parseInt(resp[k].delivery_cost);
						tr.append('<td>' + (CollectedAmount) + '</td>')
						totalCollectedAmount += CollectedAmount;
                        tr.append('<td>' + (resp[k].cod) + '</td>')
                        tr.append('<td>' + (resp[k].delivery_cost) + '</td>')
                        var totalCharge = parseInt(resp[k].cod) + parseInt(resp[k].delivery_cost);
                        total_cod += parseInt(resp[k].cod);
                        total_delivery_charge += parseInt(resp[k].delivery_cost);
                        totalAmount += totalCharge;
                        tr.append('<td>' + (totalCharge) + '</td>')
						var paidout = CollectedAmount - totalCharge;
						totalPaidout += paidout;
						tr.append('<td>' + (paidout) + '</td>')
                        $('#report-list tbody').append(tr)
                    })
					$('#total_collected_amount').html('Total Collected Amount: ' + totalCollectedAmount)
                    $('#total_cod').html('Total COD: ' + total_cod)
                    $('#total_delivery_charge').html('Total Delivery Charge : ' + total_delivery_charge)
                    $('#total_amount').html('Total Amount: ' + totalAmount)
					$('#total_paid_out').html('Total Paid Out: ' + totalPaidout)
                    $('#print').show()
                } else {
                    $('#report-list tbody').html('')
                    var tr = $('<tr></tr>')
                    tr.append('<th class="text-center" colspan="6">No result.</th>')
                    $('#report-list tbody').append(tr)
                    $('#print').hide()
                }
            }
        },
        complete: function() {
            end_load()
        }
    })
}
$('#view_report').click(function() {
    if ($('#date_from').val() == '' || $('#date_to').val() == ''  || $('#name').val() == '') {
        alert_toast("Please select dates first.", "error")
        return false;
    }
    load_report()
    var date_from = $('#date_from').val()
    var date_to = $('#date_to').val()
    var status = $('#status').val()
    var type = $('#type').val()
	var name = $('#name').val()
    var target = './index.php?page=reports&filtered&date_from=' + date_from + '&date_to=' + date_to +
        '&status=' + status + '&type=' + type+ '&name=' + name;
    window.history.pushState({}, null, target);
})

$(document).ready(function() {
    if ('<?php echo isset($_GET['filtered']) ?>' == 1)
        load_report()
})
$('#print').click(function() {
    start_load()
    var ns = $('noscript').clone()
    var details = $('.details').clone()
    var content = $('#report-list').clone()
    var contentTotalAmount = $('#total_details').clone()
    var date_from = $('#date_from').val()
    var date_to = $('#date_to').val()
    var status = $('#status').val()
    var stat_arr = '<?php echo json_encode($status_arr) ?>';
    stat_arr = JSON.parse(stat_arr);
    details.find('.drange').text(date_from + " to " + date_to)
    if (status > -1)
        details.find('.status-field').text(stat_arr[status])
    ns.append(details)

    ns.append(content)
    ns.append(contentTotalAmount)
    var nw = window.open('', '', 'height=700,width=900')
    nw.document.write(ns.html())
    nw.document.close()
    nw.print()
    setTimeout(function() {
        nw.close()
        end_load()
    }, 750)

})
</script>