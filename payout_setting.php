<?php if(!isset($conn)){ include 'db_connect.php'; } ?>
<style>
  textarea{
    resize: none;
  }
</style>
<div class="col-lg-6 m-auto">
	<div class="card card-outline card-primary">
		<div class="card-body">
			<form action="" id="payout-setting">
        <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
        <input type="hidden" name="merchant_id" value="<?php echo $_SESSION['login_id'] ?>" />
        <div class="row">
          <div class="col-md-12">
            <div id="msg" class=""></div>
            <div class="row">
              <div class="col-sm-12 form-group ">
                <label for="" class="control-label">Name</label>
                <input type="text" name="name" id="name" placeholder="Datch Bangla Bank" class="form-control form-control-sm" required>
              </div>
              <div class="col-sm-12 form-group ">
                <label for="" class="control-label">Details</label>
                <textarea name="info" id="info" class="form-control info-area" rows="6" placeholder="Bank Name, Account Holder Name, Account Number, Branch, Account Type, Swift Code"></textarea>
              </div>
            </div>
          </div>
        </div>
      </form>
  	</div>
  	<div class="card-footer border-top border-info">
  		<div class="d-flex w-100 justify-content-center align-items-center">
  			<button class="btn btn-flat  bg-gradient-primary mx-2" form="payout-setting">Save</button>
  			<a class="btn btn-flat bg-gradient-secondary mx-2" href="./index.php?page=payout-setting">Cancel</a>
  		</div>
  	</div>
	</div>
</div>
<div class="col-lg-12">
	<div class="card card-outline card-primary">       
		<div class="card-body">
			<table class="table tabe-hover table-bordered" id="list">
				<thead>
					<tr>
						<th class="text-center">#</th>
						<th>Name</th>
						<th>Details</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
                <?php
                     
                     $i = 1;
                     $qry = $conn->query("SELECT * FROM merchents_payment_methods WHERE merchant_id='".$_SESSION['login_id']."'");
                     while($row= $qry->fetch_assoc()):
                     ?>
					<tr>
						<td class="text-center"><?php echo $i++ ?></td>
						<td><b><?php echo ucwords($row['name']) ?></b></td>
						<td><b><?php echo ($row['info']) ?></b></td>
						<td class="text-center">
		                    <div class="btn-group">
		                        <button type="button" class="btn btn-danger btn-flat delete_payout_method" data-id="<?php echo $row['id'] ?>">
		                          <i class="fas fa-trash"></i>
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
    $('#payout-setting').submit(function(e){
		e.preventDefault()
		start_load()
		$.ajax({
			url:'ajax.php?action=payout_setting',
			data: new FormData($(this)[0]),
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    type: 'POST',
			success:function(resp){
				if(resp == 1){
					alert_toast('Payout Method successfully Added!',"success");
					setTimeout(function(){
              location.href = 'index.php?page=payout_setting'
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