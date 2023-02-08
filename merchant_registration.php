<?php @session_start() ?>
<!DOCTYPE html>
<html lang="en">
<?php 
 
include('./db_connect.php');
  ob_start();
   if(!isset($_SESSION['system'])){

    $system = $conn->query("SELECT * FROM system_settings")->fetch_array();
    foreach($system as $k => $v){
      $_SESSION['system'][$k] = $v;
    }
   }
  ob_end_flush();
?>
<?php 
if(isset($_SESSION['login_id']))
header("location:index.php?page=home");

?>
<?php include 'header.php' ?>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="#"><b><?php echo $_SESSION['system']['name'] ?></b></a>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
    <div class="alert alert-danger d-none" id="msgnonumber" role="alert">
        Phone number is required
    </div>
    <div class="alert alert-danger d-none" id="msgerror" role="alert">
        Verification code is not correct!
    </div>
      <form action="" method="POST" id="verify-phone-form">
        <div class="input-group mb-3" id="phone-box">
          <input type="phone" class="form-control" id="phone-number" name="Phone" placeholder="Phone">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-phone"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3 d-none" id="code-box">
          <input type="phone" class="form-control " id="verification-code" name="vcode" placeholder="Check your SMS and enter code here">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-phone"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <!-- /.col -->
          <div class="col-10 mx-auto">
            <button type="button" id="send-code" class="btn btn-primary btn-block">Send Verification Code</button>
            <button type="button" id="verify-code" class="btn btn-primary btn-block d-none">Verify Phone</button>
          </div>
          <!-- /.col -->
        </div>
      </form>
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->
<script>
    var VerificationCode = 0;
    var user_phone_number = '';
    function sendCode(number){
        $.ajax({
            url:'send_code.php?number='+ number,
            cache: false,
            contentType: false,
            processData: false,
            method: 'GET',
            success:function(resp){
                var jsonValue = JSON.parse(resp);
                VerificationCode = jsonValue.code
                // console.log(jsonValue.code)
            }
        })
    }
    $('#send-code').click(function(e){
        e.preventDefault()
        let number = $('#phone-number').val();
        if(number.length > 6){
            user_phone_number = number;
            sendCode(number);
            // disable
            $('#send-code').addClass('d-none')
            $('#phone-box').addClass('d-none')
            $('#msgnonumber').addClass('d-none')
            // enable
            $('#code-box').removeClass("d-none")
            $('#verify-code').removeClass("d-none")
        }else{
            $('#msgnonumber').removeClass("d-none")
        }
        

    })

    $('#verify-code').click(function(e){
        let userCode = $('#verification-code').val();

        if(VerificationCode !== 0 && VerificationCode == userCode){
            window.location.href = '/mreg.php?p='+btoa(user_phone_number)
        }else{
            $('#msgerror').removeClass("d-none")
        }
    })

 
</script>
<?php include 'footer.php' ?>

</body>
</html>
