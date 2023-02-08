  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <div class="dropdown">
   	<a href="./" class="brand-link">
        <?php if($_SESSION['login_type'] == 1): ?>
        <h3 class="text-center p-0 m-0"><b>ADMIN</b></h3>
        <?php elseif($_SESSION['login_type'] == 3): ?>
        <h3 class="text-center p-0 m-0"><b>MERCHENT</b></h3>
        <?php elseif($_SESSION['login_type'] == 4): ?>
        <h3 class="text-center p-0 m-0"><b>RIDER</b></h3>
        <?php else: ?>
        <h3 class="text-center p-0 m-0"><b>STAFF</b></h3>
        <?php endif; ?>

    </a>
      
    </div>
    <div class="sidebar pb-4 mb-4">
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column nav-flat" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item dropdown">
            <a href="./" class="nav-link nav-home">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>     
          <?php if($_SESSION['login_type'] == 1): ?>
          <li class="nav-item">
            <a href="#" class="nav-link nav-edit_branch">
              <i class="nav-icon fas fa-building"></i>
              <p>
                Branch
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="./index.php?page=new_branch" class="nav-link nav-new_branch tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Add New</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./index.php?page=branch_list" class="nav-link nav-branch_list tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>List</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link nav-edit_staff">
              <i class="nav-icon fas fa-users"></i>
              <p>
                Branch Staff
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="./index.php?page=new_staff" class="nav-link nav-new_staff tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Add New</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./index.php?page=staff_list" class="nav-link nav-staff_list tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>List</p>
                </a>
              </li>
            </ul>
          </li>
        <?php endif; ?>
          <li class="nav-item">
          <?php if($_SESSION['login_type'] == 1 || $_SESSION['login_type'] == 2 || $_SESSION['login_type'] == 3){?>
            <a href="#" class="nav-link nav-edit_parcel">
              <i class="nav-icon fas fa-boxes"></i>
              <p>
                Parcels
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <?php } ?>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="./index.php?page=new_parcel" class="nav-link nav-new_parcel tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Add New</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./index.php?page=parcel_list" class="nav-link nav-parcel_list nav-sall tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>List All</p>
                </a>
              </li>
              <?php
              $status_arr = array("Item Accepted<br/>by Courier","Ready to Pickup","Picked-up","Collected","Shipped","In-Transit","Hold","Arrived At<br/>Destination","Delivered","Unsuccessfull<br/>Delivery Attempt/Return");
              foreach($status_arr as $k =>$v):
              ?>
              <li class="nav-item">
                <a href="./index.php?page=parcel_list&s=<?php echo $k+1 ?>" class="nav-link nav-parcel_list_<?php echo $k+1 ?> tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p><?php echo $v ?></p>
                </a>
              </li>
            <?php endforeach; ?>
            </ul>
          </li>
           <li class="nav-item dropdown">
            <a href="./index.php?page=track" class="nav-link nav-track">
              <i class="nav-icon fas fa-search"></i>
              <p>
                Track Parcel
              </p>
            </a>
          </li>  
           <li class="nav-item dropdown">
            <a href="./index.php?page=reports" class="nav-link nav-reports">
              <i class="nav-icon fas fa-file"></i>
              <p>
                Reports
              </p>
            </a>
          </li> 
          <li class="nav-item">
            <a href="#" class="nav-link nav-edit_merchent">
              <i class="nav-icon fas fa-users"></i>
              <p>
                Merchents
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
            <?php if($_SESSION['login_type'] == 3){?>
              <li class="nav-item">
                <a href="./index.php?page=merchants" class="nav-link nav-merchents tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Merchent Home</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./index.php?page=merchents_payout" class="nav-link nav-merchent_payout tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Merchent Payout</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./index.php?page=merchent_request_payout" class="nav-link nav-merchent_payout tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Merchent Request Payout</p>
                </a>
              </li>
              <?php } ?>
              <?php if($_SESSION['login_type'] == 1){?>
              <li class="nav-item">
                <a href="./index.php?page=new_merchent" class="nav-link nav-new_merchents tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Add Merchant</p>
                </a>
              </li>
             
              <li class="nav-item">
                <a href="./index.php?page=merchent_list" class="nav-link nav-merchents_list tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>All Merchants</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./index.php?page=merchent_report" class="nav-link nav-merchents_list tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Merchant Report</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./index.php?page=all_payout_request" class="nav-link nav-all_payout_request tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>All Payout Requests</p>
                </a>
              </li>
              <?php } ?>
            </ul>
          </li> 
          <li class="nav-item">
            <a href="#" class="nav-link nav-edit_merchent">
              <i class="nav-icon fas fa-users"></i>
              <p>
                Riders
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
            <?php if($_SESSION['login_type'] == 4){?>
              <li class="nav-item">
                <a href="./index.php?page=rider_home" class="nav-link nav-rider_home tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Rider Home</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./index.php?page=rider_report" class="nav-link nav-rider_report tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Rider Report</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./index.php?page=rider_parcel" class="nav-link nav-assigned_parcel_list tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Assigned Parcel List</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./index.php?page=rider_payout" class="nav-link nav-rider_payout tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Rider Payout</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./index.php?page=rider_payout_request" class="nav-link nav-rider_payout_request tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Rider Payout Request</p>
                </a>
              </li>
              <?php } ?>
              <?php if($_SESSION['login_type'] == 1){?>
              <li class="nav-item">
                <a href="./index.php?page=new_rider" class="nav-link nav-new_rider tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Add Rider</p>
                </a>
              </li>
             
              <li class="nav-item">
                <a href="./index.php?page=rider_list" class="nav-link nav-rider_list tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>All Riders</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./index.php?page=delivery_sheet" class="nav-link nav-rider_list tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Rider Delivery Sheet</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./index.php?page=all_rider_payout_request" class="nav-link nav-all_rider_payout_request tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>All Rider Payout Requests</p>
                </a>
              </li>
              <?php } ?>
            </ul>
          </li> 
        </ul>
      </nav>
    </div>
  </aside>
  <script>
  	$(document).ready(function(){
      var page = '<?php echo isset($_GET['page']) ? $_GET['page'] : 'home' ?>';
  		var s = '<?php echo isset($_GET['s']) ? $_GET['s'] : '' ?>';
      if(s!='')
        page = page+'_'+s;
  		if($('.nav-link.nav-'+page).length > 0){
             $('.nav-link.nav-'+page).addClass('active')
  			if($('.nav-link.nav-'+page).hasClass('tree-item') == true){
            $('.nav-link.nav-'+page).closest('.nav-treeview').siblings('a').addClass('active')
  				$('.nav-link.nav-'+page).closest('.nav-treeview').parent().addClass('menu-open')
  			}
        if($('.nav-link.nav-'+page).hasClass('nav-is-tree') == true){
          $('.nav-link.nav-'+page).parent().addClass('menu-open')
        }

  		}
     
  	})
  </script>