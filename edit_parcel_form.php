<?php if(!isset($conn)){ include 'db_connect.php'; } ?>
<?php
    date_default_timezone_set("Asia/Dhaka");
?>
<style>
textarea {
    resize: none;
}
</style>
<div class="col-lg-12">
    <div class="card card-outline card-primary">
        <div class="card-body">
            <form action="" id="manage-parcel">
                <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
                <div id="msg" class=""></div>
                <div class="row">
                    <div class="col-md-6">
                        <b>Sender Information</b>
                        <div class="form-group">
                            <label for="" class="control-label">Name</label>
                            <input type="text" name="sender_name" id="" class="form-control form-control-sm"
                                value="<?php echo isset($sender_name) ? $sender_name : '' ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="" class="control-label">Address</label>
                            <input type="text" name="sender_address" id="" class="form-control form-control-sm"
                                value="<?php echo isset($sender_address) ? $sender_address : '' ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="" class="control-label">Contact #</label>
                            <input type="text" name="sender_contact" id="" class="form-control form-control-sm"
                                value="<?php echo isset($sender_contact) ? $sender_contact : '' ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="" class="control-label">Merchant Order ID</label>
                            <input type="text" name="merchant_order_id" id="" class="form-control form-control-sm"
                                value="<?php echo isset($merchant_order_id) ? $merchant_order_id : '' ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <b>Recipient Information</b>
                        <div class="form-group">
                            <label for="" class="control-label">Name</label>
                            <input type="text" name="recipient_name" id="" class="form-control form-control-sm"
                                value="<?php echo isset($recipient_name) ? $recipient_name : '' ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="" class="control-label">Contact #</label>
                            <input type="text" name="recipient_contact" id="" class="form-control form-control-sm"
                                value="<?php echo isset($recipient_contact) ? $recipient_contact : '' ?>" required>
                            <input type="hidden" name="reference_number" id="" class="form-control form-control-sm"
                                value="<?php echo isset($reference_number) ? $reference_number : '' ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="" class="control-label">Address</label>
                            <input type="text" name="recipient_address" id="" class="form-control form-control-sm"
                                value="<?php echo isset($recipient_address) ? $recipient_address : '' ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="" class="control-label">Police Station</label>
                            <input type="text" name="station" id="" class="form-control form-control-sm"
                                value="<?php echo isset($station) ? $station : '' ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="" class="control-label">District</label>
                            <input type="text" name="district" id="" class="form-control form-control-sm"
                                value="<?php echo isset($district) ? $district : '' ?>" required>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="dtype">Type</label>
                            <input type="checkbox" name="type" id="dtype"
                                <?php echo isset($type) && $type == 1 ? 'checked' : '' ?> data-bootstrap-switch
                                data-toggle="toggle" data-on="Deliver" data-off="Pickup"
                                class="switch-toggle status_chk" data-size="xs" data-offstyle="info" data-width="5rem"
                                value="1">
                            <small>Deliver = Deliver to Recipient Address</small>
                            <small>, Pickup = Pickup to nearest Branch</small>
                        </div>
                        <h3><b>Parcel Reference Number:</b> <span
                                class="badge badge-pill badge-info"><?php echo $reference_number; ?></span> </h3>
                    </div>
                    <div class="col-md-6" id="" <?php echo isset($type) && $type == 1 ? 'style="display: none"' : '' ?>>

                        <div class="row">
                            <div class="col-md-6">
                                <label for="" class="control-label">Delivery Cost</label>
                                <input type="number" placeholder="00" name="delivery_cost" id="delivery_cost"
                                    class="form-control form-control-sm"
                                    value="<?php echo isset($delivery_cost) ? $delivery_cost : '' ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="" class="control-label">COD</label>
                                <input type="number" placeholder="00" name="cod" id="cod"
                                    class="form-control form-control-sm" value="<?php echo isset($cod) ? $cod : '' ?>"
                                    required>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <b>Parcel Information</b>
                <table class="table table-bordered" id="parcel-items">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Weight</th>
                            <th>Height</th>
                            <th>Length</th>
                            <th>Width</th>
                            <th>Price</th>
                            <?php if(!isset($id)): ?>
                            <th></th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><input class="form-control form-control-sm" type="text" name='item_name[]'
                                    value="<?php echo isset($item_name) ? $item_name :'' ?>" required></td>
                            <td><input class="form-control form-control-sm" type="text" name='weight[]'
                                    value="<?php echo isset($weight) ? $weight :'' ?>" required></td>
                            <td><input class="form-control form-control-sm" type="text" name='height[]'
                                    value="<?php echo isset($height) ? $height :'' ?>"></td>
                            <td><input class="form-control form-control-sm" type="text" name='length[]'
                                    value="<?php echo isset($length) ? $length :'' ?>"></td>
                            <td><input class="form-control form-control-sm" type="text" name='width[]'
                                    value="<?php echo isset($width) ? $width :'' ?>"></td>
                            <td><input class="form-control form-control-sm" type="text" class="text-right number"
                                    name='price[]' value="<?php echo isset($price) ? $price :'' ?>" required></td>
                            <?php if(!isset($id)): ?>
                            <td><button class="btn btn-sm btn-danger" type="button"
                                    onclick="$(this).closest('tr').remove() && calc()"><i
                                        class="fa fa-times"></i></button></td>
                            <?php endif; ?>
                        </tr>
                    </tbody>
                    <?php if(!isset($id)): ?>
                    <tfoot>
                        <th colspan="4" class="text-right">Total</th>
                        <th class="text-right" id="tAmount">0.00</th>
                        <th></th>
                    </tfoot>
                    <?php endif; ?>
                </table>
                <?php if(!isset($id)): ?>
                <div class="row">
                    <div class="col-md-12 d-flex justify-content-end">
                        <button class="btn btn-sm btn-primary bg-gradient-primary" type="button" id="new_parcel"><i
                                class="fa fa-item"></i> Add Item</button>
                    </div>
                </div>
                <?php endif; ?>
                <div class="mb-3 col-md-6">
                    <label for="note" class="form-label">Note/Description</label>
                    <textarea class="form-control" name="note" id="note" rows="3"
                        value="<?php echo isset($note) ? $note : '' ?>"> <?php echo isset($note) ? $note : '' ?></textarea>
                </div>
            </form>
        </div>
        <div class="card-footer border-top border-info">
            <div class="d-flex w-100 justify-content-center align-items-center">
                <button class="btn btn-flat  bg-gradient-primary mx-2" form="manage-parcel">Save</button>
                <a class="btn btn-flat bg-gradient-secondary mx-2" href="./index.php?page=parcel_list">Cancel</a>
            </div>
        </div>
    </div>
</div>
<div id="ptr_clone" class="d-none">
    <table>
        <tr>
            <td><input class="form-control form-control-sm" type="text" name='item_name[]' required></td>
            <td><input class="form-control form-control-sm" type="text" name='weight[]' required></td>
            <td><input class="form-control form-control-sm" type="text" name='height[]'></td>
            <td><input class="form-control form-control-sm" type="text" name='length[]'></td>
            <td><input class="form-control form-control-sm" type="text" name='width[]'></td>
            <td><input class="form-control form-control-sm" type="text" class="text-right number" name='price[]'
                    required></td>
            <td><button class="btn btn-sm btn-danger" type="button"
                    onclick="$(this).closest('tr').remove() && calc()"><i class="fa fa-times"></i></button></td>
        </tr>
    </table>
</div>
<script>
$('#dtype').change(function() {
    if ($(this).prop('checked') == true) {
        $('#tbi-field').hide()
    } else {
        $('#tbi-field').show()
    }
})
$('[name="price[]"]').keyup(function() {
    calc()
})
$('#new_parcel').click(function() {
    var tr = $('#ptr_clone tr').clone()
    $('#parcel-items tbody').append(tr)
    $('[name="price[]"]').keyup(function() {
        calc()
    })
    $('.number').on('input keyup keypress', function() {
        var val = $(this).val()
        val = val.replace(/[^0-9]/, '');
        val = val.replace(/,/g, '');
        val = val > 0 ? parseFloat(val).toLocaleString("en-US") : 0;
        $(this).val(val)
    })

})
$('#manage-parcel').submit(function(e) {
    e.preventDefault()
    start_load()
    if ($('#parcel-items tbody tr').length <= 0) {
        alert_toast("Please add atleast 1 parcel information.", "error")
        end_load()
        return false;
    }
    $.ajax({
        url: 'ajax.php?action=save_parcel',
        data: new FormData($(this)[0]),
        cache: false,
        contentType: false,
        processData: false,
        method: 'POST',
        type: 'POST',
        success: function(resp) {
            // if(resp){
            //       resp = JSON.parse(resp)
            //       if(resp.status == 1){
            //         alert_toast('Data successfully saved',"success");
            //         end_load()
            //         var nw = window.open('print_pdets.php?ids='+resp.ids,"_blank","height=700,width=900")
            //       }
            // }
            if (resp == 1) {
                alert_toast('Data successfully saved', "success");
                setTimeout(function() {
                    location.href = 'index.php?page=parcel_list';
                }, 2000)

            }
        }
    })
})

function displayImgCover(input, _this) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#cover').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}

function calc() {

    var total = 0;
    $('#parcel-items [name="price[]"]').each(function() {
        var p = $(this).val();
        p = p.replace(/,/g, '')
        p = p > 0 ? p : 0;
        total = parseFloat(p) + parseFloat(total)
    })
    if ($('#tAmount').length > 0)
        $('#tAmount').text(parseFloat(total).toLocaleString('en-US', {
            style: 'decimal',
            maximumFractionDigits: 2,
            minimumFractionDigits: 2
        }))
}
</script>