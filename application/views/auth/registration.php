
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="description" content="Mau kirim barang murah dan cepat? Kirim Ke Jati Express Aja. PT Komando Global Logistik Jasa Kirim Barang Termurah Ke Sumatra, Jawa, Bali, Sulawesi dan seluruh nusantara." />
	<meta name="keywords" content="KGL, Pengiriman Barang, bootstrap 3, dashboard">
	<meta name="author" content="KGL" />

	<title>KGL - Registration System</title>
	<link rel="icon" type="image/png" href="<?=site_url(); ?>img/logo/logo_komando_color(1000).png" sizes="32x32" />
	<link rel="icon" type="image/png" href="<?=site_url(); ?>img/logo/logo_komando_color(1000).png" sizes="16x16" />
	<!-- Global stylesheets -->
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
	<link href="<?=base_url(); ?>assets/global_assets/css/icons/icomoon/styles.min.css" rel="stylesheet" type="text/css">
	<link href="<?=base_url(); ?>assets/layout1/css/bootstrap.min.css" rel="stylesheet" type="text/css">
	<link href="<?=base_url(); ?>assets/layout1/css/bootstrap_limitless.min.css" rel="stylesheet" type="text/css">
	<link href="<?=base_url(); ?>assets/layout1/css/layout.min.css" rel="stylesheet" type="text/css">
	<link href="<?=base_url(); ?>assets/layout1/css/components.min.css" rel="stylesheet" type="text/css">
	<link href="<?=base_url(); ?>assets/layout1/css/colors.min.css" rel="stylesheet" type="text/css">

	<script src="<?=base_url(); ?>assets/global_assets/js/main/jquery.min.js"></script>
	<script src="<?=base_url(); ?>assets/global_assets/js/plugins/forms/validation/validate.min.js"></script>
	<script src="<?=base_url(); ?>assets/global_assets/js/demo_pages/login_validation.js"></script>
</head>

<body class="bg-slate-800" style="min-height: 90vh;">
<div class="page-content">
	<div class="content-wrapper">
		<div class="content d-flex justify-content-center align-items-center">
			<form class="login-form form-validate"  method="post" action="<?=base_url('auth/RegistrasiSimpan'); ?>">
				<div class="card mb-0">
					<div class="card-body">
						<div class="text-center mb-3">
							<img src="<?=site_url('img/logo/logo_komando_color(1000).png')?>" width="160" height="60" class="" alt="img login">
							<h5 class="mb-0">Sign Up your account </h5>
							<span class="d-block text-muted">Enter your credentials below</span>
						</div>
						<?php
							date_default_timezone_set('Asia/Jakarta');
							$tahun = date('Y');
							echo $this->session->flashdata('notif');
						?>
						<div class="form-group form-group-feedback form-group-feedback-left">
							<input type="text" class="form-control" autocomplete="off" name="tUname" autofocus required placeholder="Username">
							<div class="form-control-feedback">
								<i class="icon-user text-muted"></i>
							</div>
						</div>
						<div class="form-group form-group-feedback form-group-feedback-left">
							<input type="password" class="form-control" minlength="4" name="tPass" required placeholder="Password">
							<div class="form-control-feedback">
								<i class="icon-lock2 text-muted"></i>
							</div>
						</div>
						<div class="form-group form-group-feedback form-group-feedback-left">
							<input type="password" class="form-control" minlength="4" name="tPass2" required placeholder="Confirm Password">
							<div class="form-control-feedback">
								<i class="icon-lock2 text-muted"></i>
							</div>
						</div>
						<div class="form-group form-group-feedback form-group-feedback-left">
							<input type="text" class="form-control" autocomplete="off" name="tFullname" autofocus required placeholder="Full Name">
							<div class="form-control-feedback">
								<i class="icon-user text-muted"></i>
							</div>
						</div>
						<div class="form-group form-group-feedback form-group-feedback-left">
							<input type="email" class="form-control" minlength="4" name="tEmail" required placeholder="Email">
							<div class="form-control-feedback">
								<i class="icon-envelop text-muted"></i>
							</div>
						</div>
						<div class="form-group form-group-feedback form-group-feedback-left">
							<input type="text" class="form-control" minlength="4" name="tUser_id" required placeholder="No HP">
							<div class="form-control-feedback">
								<i class="icon-phone2 text-muted"></i>
							</div>
						</div>
						<div class="form-group">
							<button type="submit" class="btn btn-primary btn-block">Sign up <i class="icon-circle-right2 ml-2"></i></button>
						</div>
						<div class="form-group">
							<a class="text-primary" href="<?= site_url('auth/login') ?>">Have an account? Sign In Here</a>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>

</body>
</html>
