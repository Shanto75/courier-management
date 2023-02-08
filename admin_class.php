<?php
session_start();
//ini_set('display_errors', 1);
Class Action {
	private $db;

	public function __construct() {
		ob_start();
   	include 'db_connect.php';
    
    $this->db = $conn;
	}
	function __destruct() {
	    $this->db->close();
	    ob_end_flush();
	}

	function login(){
		extract($_POST);
			$qry = $this->db->query("SELECT *,concat(firstname,' ',lastname) as name FROM users where email = '".$email."' and password = '".md5($password)."'  ");
		if($qry->num_rows > 0){
			foreach ($qry->fetch_array() as $key => $value) {
				if($key != 'password' && !is_numeric($key))
					$_SESSION['login_'.$key] = $value;
					$_SESSION['login_success'] = true;
			}
				return 1;
		}else{
			return 2;
		}
	}
	function logout(){
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		unset($_SESSION['login_success']);
		header("location:login.php");
	}
	function login2(){
		extract($_POST);
			$qry = $this->db->query("SELECT *,concat(lastname,', ',firstname,' ',middlename) as name FROM students where student_code = '".$student_code."' ");
		if($qry->num_rows > 0){
			foreach ($qry->fetch_array() as $key => $value) {
				if($key != 'password' && !is_numeric($key))
					$_SESSION['rs_'.$key] = $value;
					$_SESSION['login_success'] = true;
			}
				return 1;
		}else{
			return 3;
		}
	}
	function save_user(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k, array('id','cpass','password')) && !is_numeric($k)){
				if(empty($data)){
					$data .= " $k='$v' ";
				}else{
					$data .= ", $k='$v' ";
				}
			}
		}
		if(!empty($password)){
					$data .= ", password=md5('$password') ";
		}
		$check = $this->db->query("SELECT * FROM users where email ='$email' ".(!empty($id) ? " and id != {$id} " : ''))->num_rows;
		if($check > 0){
			return 2;
			exit;
		}
		if(empty($id)){
			$save = $this->db->query("INSERT INTO users set $data");
		}else{
			$save = $this->db->query("UPDATE users set $data where id = $id");
		}
		if($save){
			return 1;
		}
	}
	function signup(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k, array('id','cpass')) && !is_numeric($k)){
				if($k =='password'){
					if(empty($v))
						continue;
					$v = md5($v);

				}
				if(empty($data)){
					$data .= " $k='$v' ";
				}else{
					$data .= ", $k='$v' ";
				}
			}
		}

		$check = $this->db->query("SELECT * FROM users where email ='$email' ".(!empty($id) ? " and id != {$id} " : ''))->num_rows;
		if($check > 0){
			return 2;
			exit;
		}
		if(isset($_FILES['img']) && $_FILES['img']['tmp_name'] != ''){
			$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
			$move = move_uploaded_file($_FILES['img']['tmp_name'],'../assets/uploads/'. $fname);
			$data .= ", avatar = '$fname' ";

		}
		if(empty($id)){
			$save = $this->db->query("INSERT INTO users set $data");

		}else{
			$save = $this->db->query("UPDATE users set $data where id = $id");
		}

		if($save){
			if(empty($id))
				$id = $this->db->insert_id;
			foreach ($_POST as $key => $value) {
				if(!in_array($key, array('id','cpass','password')) && !is_numeric($key))
					$_SESSION['login_'.$key] = $value;
			}
					$_SESSION['login_id'] = $id;
			return 1;
		}
	}

	function update_user(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k, array('id','cpass','table')) && !is_numeric($k)){
				if($k =='password')
					$v = md5($v);
				if(empty($data)){
					$data .= " $k='$v' ";
				}else{
					$data .= ", $k='$v' ";
				}
			}
		}
		if($_FILES['img']['tmp_name'] != ''){
			$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
			$move = move_uploaded_file($_FILES['img']['tmp_name'],'assets/uploads/'. $fname);
			$data .= ", avatar = '$fname' ";

		}
		$check = $this->db->query("SELECT * FROM users where email ='$email' ".(!empty($id) ? " and id != {$id} " : ''))->num_rows;
		if($check > 0){
			return 2;
			exit;
		}
		if(empty($id)){
			$save = $this->db->query("INSERT INTO users set $data");
		}else{
			$save = $this->db->query("UPDATE users set $data where id = $id");
		}

		if($save){
			foreach ($_POST as $key => $value) {
				if($key != 'password' && !is_numeric($key))
					$_SESSION['login_'.$key] = $value;
			}
			if($_FILES['img']['tmp_name'] != '')
			$_SESSION['login_avatar'] = $fname;
			return 1;
		}
	}
    function save_payout_request(){
        // TODO: Save payout Request
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
		if(!isset($account)){
			$account = "";
		}
		if(!isset($mobilebanking)){
			$mobilebanking = "";
		}
        
        $save = $this->db->query("INSERT INTO payouts set account='".$account."', mobilebanking='".$mobilebanking."', merchent_id='".$merchant_id."', payout_method='".$payout_method."', amount='".$amount."', status=0");
        if($save){
            return 1;
        }else{
            return 2;
        }
    }
    function save_payout_setting(){
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
        $save = $this->db->query("INSERT INTO merchents_payment_methods set $data");
        if($save){
            return 1;
        }else{
            return 2;
        }
    }
    function delete_payout_method(){
        extract($_POST);
        $delete = $this->db->query("DELETE FROM merchents_payment_methods WHERE id = ".$id);
        if($delete)
            return 1;
    }
	function delete_user(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM users where id = ".$id);
		if($delete)
			return 1;
	}
	function save_system_settings(){
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
		if($_FILES['cover']['tmp_name'] != ''){
			$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['cover']['name'];
			$move = move_uploaded_file($_FILES['cover']['tmp_name'],'../assets/uploads/'. $fname);
			$data .= ", cover_img = '$fname' ";

		}
		$chk = $this->db->query("SELECT * FROM system_settings");
		if($chk->num_rows > 0){
			$save = $this->db->query("UPDATE system_settings set $data where id =".$chk->fetch_array()['id']);
		}else{
			$save = $this->db->query("INSERT INTO system_settings set $data");
		}
		if($save){
			foreach($_POST as $k => $v){
				if(!is_numeric($k)){
					$_SESSION['system'][$k] = $v;
				}
			}
			if($_FILES['cover']['tmp_name'] != ''){
				$_SESSION['system']['cover_img'] = $fname;
			}
			return 1;
		}
	}
	function save_image(){
		extract($_FILES['file']);
		if(!empty($tmp_name)){
			$fname = strtotime(date("Y-m-d H:i"))."_".(str_replace(" ","-",$name));
			$move = move_uploaded_file($tmp_name,'../assets/uploads/'. $fname);
			$protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https'?'https':'http';
			$hostName = $_SERVER['HTTP_HOST'];
			$path =explode('/',$_SERVER['PHP_SELF']);
			$currentPath = '/'.$path[1]; 
			if($move){
				return $protocol.'://'.$hostName.$currentPath.'/assets/uploads/'.$fname;
			}
		}
	}
	function save_branch(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k, array('id')) && !is_numeric($k)){
				if(empty($data)){
					$data .= " $k='$v' ";
				}else{
					$data .= ", $k='$v' ";
				}
			}
		}
		if(empty($id)){
			$chars = '0123456789';
			$fixcode = 'FnF01';
			$i = 0;
			while($i == 0){
				$randomcode = substr(str_shuffle($chars), 0, 2);
				$bcode = $fixcode.$randomcode;
				$chk = $this->db->query("SELECT * FROM branches where branch_code = '$bcode'")->num_rows;
				if($chk <= 0){
					$i = 1;
				}
			}
			$data .= ", branch_code='$bcode' ";
			$save = $this->db->query("INSERT INTO branches set $data");
		}else{
			$save = $this->db->query("UPDATE branches set $data where id = $id");
		}
		if($save){
			return 1;
		}
	}
	function delete_branch(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM branches where id = $id");
		if($delete){
			return 1;
		}
	}
	function save_parcel(){
		extract($_POST);
		foreach($price as $k => $v){
			$data = "";
			foreach($_POST as $key => $val){
				if(!in_array($key, array('id','item_name','weight','height','width','length','price')) && !is_numeric($key)){
					if(empty($data)){
						$data .= " $key='$val' ";
					}else{
						$data .= ", $key='$val' ";
					}
				}
			}
			if(!isset($type)){
				$data .= ", type='2' ";
			}
                $data .= ", item_name='{$item_name[$k]}' ";
				$data .= ", height='{$height[$k]}' ";
				$data .= ", width='{$width[$k]}' ";
				$data .= ", length='{$length[$k]}' ";
				$data .= ", weight='{$weight[$k]}' ";
				$price[$k] = str_replace(',', '', $price[$k]);
				$data .= ", price='{$price[$k]}' ";
			if(empty($id)){
				$i = 0;
				while($i == 0){
					$ref = sprintf("%'012d",mt_rand(0, 999999999999));
					$chk = $this->db->query("SELECT * FROM parcels where reference_number = '$ref'")->num_rows;
					if($chk <= 0){
						$i = 1;
					}
				}
				// $data .= ", reference_number='$ref' ";
                // $data .= ", reference_number='{$parcel_number[$k]}' ";
				if($save[] = $this->db->query("INSERT INTO parcels set $data"))
					$ids[]= $this->db->insert_id;
			}else{
				if($save[] = $this->db->query("UPDATE parcels set $data where id = $id"))
					$ids[] = $id;
			}
		}


		// remove delivery charge and cod for more items
		$chk = $this->db->query("SELECT * FROM parcels where reference_number = '$reference_number'")->num_rows;
		if($chk > 1){
			$sql = $this->db->query("SELECT * FROM parcels where reference_number = '$reference_number'");
			$i = 0;
			while($row = $sql->fetch_assoc()){
				if($i!=0){
					$id = $row['id'];
					$this->db->query("UPDATE parcels set delivery_cost=0, cod=0 where id = $id");
				}
				$i++;
			}
		}

		if(isset($save) && isset($ids)){
			// return json_encode(array('ids'=>$ids,'status'=>1));
			return 1;
		}
	}
	function delete_parcel(){
		extract($_POST);
        // $reference = $this->db->query("SELECT reference_number FROM parcels where id= $id")->fetch_array()['reference_number'];
		$delete = $this->db->query("DELETE FROM parcels where id = $id");
		if($delete){
			return 1;
		}
	}
    function getStatusText($id){
        switch ($id) {
            case 1:
                return "Item-Accepted-by-Courier";
                break;
			case 2:
				return "Ready-to-Pickup";
				break;
			case 3:
				return "Picked-up";
				break;
			case 4:
				return "Collected";
				break;
            case 5:
               return "Shipped";
                break;
            case 6:
                return "In-Transit";
                break;
			case 7:
				return "Hold";
				break;
            case 8:
                return "Arrived-At-Destination";
                break;
            case 9:
                return "Delivered";
                break;
            
            case 10:
                return "Unsuccessfull-Delivery-Attempt/Return";
                break;
        }
    }
    function getPhoneNumber($uid){
        
        $phone_number_user = $this->db->query("SELECT phone FROM users where id=$uid")->fetch_array()['phone'];
        return $phone_number_user;
    }

    function sendStatusChangeSms($name, $id, $deliveryManPhone, $phone){
        // $statusText = $this->getStatusText($status);
        // $msg = "Your%20Parcel%20Status%20".$statusText."%20Reference%20".$refn."%20For%20Support%20Contact%20With%2001924001500";
		$msg = "Our%20Delivery%20Man%20Name:%20".$name."%20ID:%20".$id."%20Phone%20Number:%20".$deliveryManPhone."%20has%20picked%20up%20your%20order%20for%20delivery.";

        try{
            $url = "http://api.boom-cast.com/boomcast/WebFramework/boomCastWebService/externalApiSendTextMessage.php?masking=NOMASK&userName=Times&password=4efb9ec959512abf408b4a33f5fcc6cb&MsgType=TEXT&receiver=$phone&message=$msg";

            $ch = curl_init( $url );
            curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt( $ch, CURLOPT_HEADER, 0);
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);
            $response = curl_exec( $ch );
        }catch(Exception $e){}
    }

	function update_parcel(){
		extract($_POST);
        // $merchant_user_id = $this->db->query("SELECT * FROM parcels where id = '$id'")->fetch_array()['user_id'];;
        // $phone_number = $this->getPhoneNumber($merchant_user_id);
        $reference = $this->db->query("SELECT reference_number FROM parcels where id= $id")->fetch_array()['reference_number'];
       
		$update = $this->db->query("UPDATE parcels set status= $status where reference_number = '".$reference."'");
        // ToDo Here some update needed too
		$save = $this->db->query("INSERT INTO parcel_tracks set status = $status , parcel_id = '".$reference."'");
        
		if($status == 8){
			include 'db_connect.php';
			$qry = "SELECT * from parcels where id = $id";
    		$result = mysqli_query($conn, $qry);
    		$row = mysqli_fetch_assoc($result);

			$phone = $row['recipient_contact'];
			$riderid = $row['riderid'];
			$qry = "SELECT * from users where id = $riderid";
    		$result = mysqli_query($conn, $qry);
    		$row = mysqli_fetch_assoc($result);

    		$name = $row['firstname'].'%20'.$row['lastname'];
    		$id = $row['staff_id'];
    		$deliveryManPhone = $row['phone'];
            // Send SMS
			$this->sendStatusChangeSms($name, $id, $deliveryManPhone, $phone);
		}
		
		if($update && $save)
			return 1;  
	}
	function confirm_payout(){
		extract($_POST);

		$checkbillid = $this->db->query("SELECT billid FROM payouts where parcelref = '$id'")->fetch_array()['billid'];
		if($checkbillid == NULL){
			return 2;
		}
		else{

			$update = $this->db->query("UPDATE payouts set paid_at=CURRENT_TIMESTAMP(), status=1 where parcelref = '$id'");
			if($update){
				return 1;
			}
		}

	}
	function cancel_payout(){
		extract($_POST);
		$update = $this->db->query("UPDATE payouts set status=2, paid_at=null, billid=null where parcelref = '$id'");
		if($update){
			return 1;
		}
	}

	function rider_confirm_payout(){
		extract($_POST);

		$checkbillid = $this->db->query("SELECT billid FROM rider_payout where parcelref = '$id'")->fetch_array()['billid'];
		if($checkbillid == NULL){
			return 2;
		}
		else{

			$update = $this->db->query("UPDATE rider_payout set paid_at=CURRENT_TIMESTAMP(), status=1 where parcelref = '$id'");
			if($update){
				return 1;
			}
		}
	}
	function rider_cancel_payout(){
		extract($_POST);
		$update = $this->db->query("UPDATE rider_payout set status=2, paid_at=null, billid=null where parcelref = '$id'");
		if($update){
			return 1;
		}
	}

	function get_parcel_heistory(){
		extract($_POST);
		$data = array();
		$parcel = $this->db->query("SELECT * FROM parcels where reference_number = '$ref_no'");
		if($parcel->num_rows <=0){
			return 2;
		}else{
			$parcel = $parcel->fetch_array();
			// $data[] = '';
			$rider = $parcel['riderid'];
			$numrow = $this->db->query("SELECT * FROM users where id = '$rider'")->num_rows;
			$riderinfo = $this->db->query("SELECT * FROM users where id = '$rider'")->fetch_array();
			if($numrow > 0){
				$ridername = $riderinfo['firstname'].' '.$riderinfo['lastname'];
			}
			$data[] = array('status'=>'Item accepted by Courier','date_created'=>date("M d, Y h:i A",strtotime($parcel['date_created'])));
			// $history = $this->db->query("SELECT * FROM parcel_tracks where parcel_id = {$parcel['id']}");
			$history = $this->db->query("SELECT * FROM parcel_tracks where parcel_id = '".$ref_no."'");
			$status_arr = array("","Item Accepted by Courier","Ready to Pickup","Picked-up","Collected","Shipped","In-Transit","Hold","Arrived At Destination","Delivered","Unsuccessfull Delivery Attempt/Return");

			while($row = $history->fetch_assoc()){
				$row['date_created'] = date("M d, Y h:i A",strtotime($row['date_created']));
				if($row['status'] == 9 && $numrow > 0){
					$row['ridername']=$ridername;
				}
				elseif($row['status'] == 10 && $numrow > 0){
					$row['ridername']=$ridername;
				}
				$row['status'] = $status_arr[$row['status']];
				$data[] = $row;
			}
			return json_encode($data);
		}
	}
	function get_report(){
		extract($_POST);
		$data = array();
        $logged_in_user_id = $_SESSION['login_id'];

        if($_SESSION['login_type'] == 1 || $_SESSION['login_type'] == 2){
			if($type==="rider"){
				$get = $this->db->query("SELECT * FROM parcels where riderid=$name and date(date_created) BETWEEN '$date_from' and '$date_to' ".($status != 'all' ? " and status = $status " : "")." order by unix_timestamp(date_created) asc");
			} elseif($type==="all"){
				$get = $this->db->query("SELECT * FROM parcels where date(date_created) BETWEEN '$date_from' and '$date_to' ".($status != 'all' ? " and status = $status " : "")." order by unix_timestamp(date_created) asc");
			}
			else{
				$get = $this->db->query("SELECT * FROM parcels where user_id=$name and date(date_created) BETWEEN '$date_from' and '$date_to' ".($status != 'all' ? " and status = $status " : "")." order by unix_timestamp(date_created) asc");
			}
		}
		else{
            $get = $this->db->query("SELECT * FROM parcels where user_id='".$logged_in_user_id."' and date(date_created) BETWEEN '$date_from' and '$date_to' ".($status != 'all' ? " and status = $status " : "")." order by unix_timestamp(date_created) asc");
        }
		
		$status_arr = array("","Item Accepted by Courier","Ready to Pickup","Picked-up","Collected","Shipped","In-Transit","Hold","Arrived At Destination","Delivered","Unsuccessfull Delivery Attempt/Return");
		
        while($row=$get->fetch_assoc()){
            $row['reference_number'] = ucwords($row['reference_number']);
			$row['sender_name'] = ucwords($row['sender_name']);
			$row['recipient_name'] = ucwords($row['recipient_name']);
			$row['date_created'] = date("M d, Y",strtotime($row['date_created']));
			$row['status'] = $status_arr[$row['status']];
			// $row['price'] = number_format($row['price'],2);
			$row['price'] = $row['price'];
			$data[] = $row;
		}
		return json_encode($data);
	}
}