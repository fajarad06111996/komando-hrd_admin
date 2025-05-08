

<!DOCTYPE html>

<html lang="en">

<head>

	<meta charset="utf-8">

	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">

	<meta http-equiv="X-UA-Compatible" content="IE=edge" />

	<meta name="description" content="Mau kirim barang murah dan cepat? Kirim Ke JTE Aja. JTE Jasa Kirim Barang Termurah Ke Sumatra, Jawa, Bali, Sulawesi dan seluruh nusantara." />

	<meta name="keywords" content="JTE, Pengiriman Barang, bootstrap 3, dashboard">

	<meta name="author" content="JTE" />



	<title>JTE - Reset Password</title>

	<link rel="icon" type="image/png" href="<?=site_url(); ?>assets/images/logo/logo-JTE.png" sizes="32x32" />

	<link rel="icon" type="image/png" href="<?=site_url(); ?>assets/images/logo/logo-JTE.png" sizes="16x16" />

	<!-- Global stylesheets -->

	<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">

	<link href="<?=base_url(); ?>assets/global_assets/css/icons/icomoon/styles.min.css" rel="stylesheet" type="text/css">

	<link href="<?=base_url(); ?>assets/layout1/css/bootstrap.min.css" rel="stylesheet" type="text/css">

	<link href="<?=base_url(); ?>assets/layout1/css/bootstrap_limitless.min.css" rel="stylesheet" type="text/css">

	<link href="<?=base_url(); ?>assets/layout1/css/layout.min.css?v=1.2" rel="stylesheet" type="text/css">

	<link href="<?=base_url(); ?>assets/layout1/css/components.min.css" rel="stylesheet" type="text/css">

	<link href="<?=base_url(); ?>assets/layout1/css/colors.min.css" rel="stylesheet" type="text/css">
	<link href="<?= base_url(); ?>assets/global_assets/css/extras/animates.min.css" rel="stylesheet" type="text/css">


	<script src="<?=base_url(); ?>assets/global_assets/js/main/jquery.min.js"></script>

	<script src="<?= base_url(); ?>assets/global_assets/js/main/bootstrap.bundle.min.js"></script>

	<script src="<?=base_url(); ?>assets/global_assets/js/plugins/forms/validation/validate.min.js"></script>

	<script src="<?=base_url(); ?>assets/global_assets/js/demo_pages/login_validation.js"></script>

</head>


<?php 
  if($this->session->flashdata('notif')){
    $flash = $this->session->flashdata('notif');
    sleep(3);
    $this->session->set_flashdata('notif', '');
  }else{
    $flash = "";
  }
?>
<body class="bg-slate-800" style="min-height: 90vh;" onload="<?= $flash; ?>">

<!--Modal change status-->
<div id="xForgot" data-backdrop="static" data-keyboard="false" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header pt-2 pb-2 bg-primary">
				<h4 class="modal-title"></h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<form action="" id="forgotForm" class="form-horizontal f-validasi" method="post">
                <div class="row mt-3 justify-content-center">
                    <div class="col-sm-8">
                        <input type="hidden" name="tId" value="" />
                        <input type="hidden" name="tClCode" value="" />
                        <input type="hidden" name="tClId" value="" />
                        <input type="text" name="email" class="form-control" placeholder="Enter Your Email"/>
                        <p class="text-center mt-3 mb-0 tContent"></p>
                    </div>
                    <div class="col-md-12">
                        <p class="text-center text-info"><b class="tName"></b></p>
                    </div>
                </div>
			    <div class="card-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <button type="button" data-dismiss="modal" class="btn btn-outline-secondary">Close</button>
                        <button type="submit" class="btn btn-danger btn-ladda btn-ladda-spinner tBtn" data-spinner-color="#333" data-style="slide-down">Save changes</button>
                    </div>
				</div>
			</form>
		</div>
	</div>
</div>
<!--End Modal change status-->

<div class="page-content">

	<div class="content-wrapper">

		<div class="content d-flex justify-content-center align-items-center">

			<form class="login-form form-validate"  method="post" action="<?=base_url('auth/changePassword'); ?>">

				<div class="card mb-0">

					<div class="card-body">

						<div class="text-center mb-3">

							<img src="<?=base_url(); ?>assets/images/logo/JTE.png" width="160" height="110" class="" alt="img login">

							<h5 class="mb-0">Reset Password </h5>

							<span class="d-block text-muted">Enter new password below</span>

						</div>

						<?php
							if($this->session->flashdata('message')){
								echo $this->session->flashdata('message');
								sleep(3);
    							$this->session->set_flashdata('message', '');
							}
						?>

						<div class="form-group form-group-feedback form-group-feedback-left">

							<input type="hidden" name="token" value="<?= $this->session->csrf_token;?>">
							<input type="hidden" name="tokenforgot" value="<?= $token;?>">

							<input id="login-password2" type="password" class="form-control" autocomplete="off" name="tPass1" autofocus required placeholder="Enter Password">

							<div class="form-control-feedback">

								<i class="icon-lock2 text-muted"></i>

							</div>
							<div class="form-control-feedbackx" onclick="myFunction2()">

								<i id="icon2" class="icon-eye"></i>

							</div>

						</div>

						<div class="form-group form-group-feedback form-group-feedback-left">

							<input id="login-password" type="password" class="form-control" minlength="4" name="tPass2" required placeholder="Verify Password">

							<div class="form-control-feedback">

								<i class="icon-lock2 text-muted"></i>

							</div>

							<div class="form-control-feedbackx" onclick="myFunction()">

								<i id="icon" class="icon-eye"></i>

							</div>

						</div>

						<div class="form-group">

							<button type="submit" class="btn btn-primary btn-block">Reset Password <i class="icon-circle-right2 ml-2"></i></button>

						</div>

					</div>

				</div>

			</form>

		</div>

	</div>

</div>
<script src="<?= base_url(); ?>assets/global_assets/js/plugins/tables/datatables/datatables.min.js"></script>
<script src="<?= base_url(); ?>assets/global_assets/js/bootstrap-filestyle.min.js"></script>
<script src="<?= base_url(); ?>assets/global_assets/js/plugins/buttons/spin.min.js"></script>
<script src="<?= base_url(); ?>assets/global_assets/js/plugins/buttons/ladda.min.js"></script>
<script src="<?= base_url(); ?>assets/global_assets/js/plugins/forms/selects/bootstrap_multiselect.js"></script>
<script src="<?= base_url(); ?>assets/global_assets/js/demo_pages/animations_css3.js"></script>
<script src="<?= base_url(); ?>assets/global_assets/js/plugins/notifications/jgrowl.min.js"></script>
<script src="<?= base_url(); ?>assets/global_assets/js/plugins/notifications/noty.min.js"></script>
<script src="<?= base_url(); ?>assets/layout1/js/notifikasi.js"></script>
<script src="<?= base_url(); ?>assets/layout1/js/custom.js?v=1.1"></script>
<script>
	$(document).ready(function(){
		$(this).on('click', function(e) {
			// console.log(e.target.classList[0]);
			if(e.target.classList[0] === 'modal') {
				var $dialog = $(this).find('.modal-dialog');
				$dialog.addClass('animated shake');
				$dialog.one('webkitAnimationEnd oanimationend msAnimationEnd animationend', function() {
				$dialog.removeClass('shake');
				});      
			}
		}); 
	});
    function myFunction()
    {
        var x = document.getElementById("login-password");
        if (x.type === "password") { x.type = "text"; } 
        else { x.type = "password"; }
		var y = document.getElementById("icon");
		if(x.type === "password")
		{
			y.classList.remove("icon-eye-blocked");
			y.classList.add("icon-eye");
		}else{
			y.classList.remove("icon-eye");
			y.classList.add("icon-eye-blocked");
		}
    }
	function myFunction2()
    {
        var x = document.getElementById("login-password2");
        if (x.type === "password") { x.type = "text"; } 
        else { x.type = "password"; }
		var y = document.getElementById("icon2");
		if(x.type === "password")
		{
			y.classList.remove("icon-eye-blocked");
			y.classList.add("icon-eye");
		}else{
			y.classList.remove("icon-eye");
			y.classList.add("icon-eye-blocked");
		}
    }

</script>

</body>

</html>

