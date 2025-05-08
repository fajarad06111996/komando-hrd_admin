<!-- Main navbar -->
<div class="navbar navbar-expand-md navbar-navy fixed-top">
	<div class="navbar-brand" style="margin-right:-73px;">
		<a href="<?php echo base_url(); ?>" class="d-inline-block">
			<!-- <img src="<?=site_url(); ?>assets/images/LogoJNEbiru-trans.png" class="" style="margin-top: -15px;margin-bottom: -30px;height: 45px;" alt=""> -->
			<img src="<?=site_url(); ?>assets/images/logo/JTEW.png" class="" style="margin-top: -15px;margin-bottom: -30px;height: 45px;" alt="">
		</a>
	</div>
	<div class="d-md-none">
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-mobile">
		<i class="icon-tree5"></i>
		</button>
		<button class="navbar-toggler sidebar-mobile-main-toggle" type="button">
			<i class="icon-paragraph-justify3"></i>
		</button>
	</div>
	<div class="collapse navbar-collapse" id="navbar-mobile">
		<ul class="navbar-nav">
				<a href="#" class="navbar-nav-link sidebar-control sidebar-main-hide d-none d-md-block" data-container="body" data-trigger="hover">
					<i class="icon-lan3"></i>
				</a>
			<li class="nav-item">
				<a href="#" class="navbar-nav-link sidebar-control sidebar-main-toggle d-none d-md-block"><i class="icon-paragraph-justify3"></i></a>
			</li>
		</ul>
		<span class="badge bg-success ml-md-1 mr-md-auto">Online</span>
		<ul class="navbar-nav">
			<li class="nav-item dropdown dropdown-user">
				<a href="#" class="navbar-nav-link d-flex align-items-center dropdown-toggle" data-toggle="dropdown">
				<img src="<?= $this->session->userdata('JTphoto')==null?site_url('assets/images/RIDER.png'):($this->session->userdata('JTphoto')=='default.png'?site_url('assets/images/RIDER.png'):$this->session->userdata('JTphoto')); ?>" class="rounded-circle mr-2 bg-indigo" height="34" alt="">
				<span><?= !empty($this->session->userdata('JTusername'))?$this->session->userdata('JTusername'):'';?></span>
				</a>
				<div class="dropdown-menu dropdown-menu-right">
					<a href="<?php echo base_url(); ?>home/myprofile" class="dropdown-item"><i class="icon-user-plus"></i> My profile</a>
					<!-- <a href="#" class="dropdown-item"><i class="icon-coins"></i> My balance</a>
					<a href="#" class="dropdown-item"><i class="icon-comment-discussion"></i> Messages <span class="badge badge-pill bg-blue ml-auto">58</span></a> -->
					<div class="dropdown-divider"></div>
					<!-- <a href="<?php echo base_url(); ?>home/myprofile" class="dropdown-item"><i class="icon-cog5"></i> Account settings</a> -->
					<a href="<?php echo base_url(); ?>Auth/Logout" class="dropdown-item"><i class="icon-switch2"></i> Logout</a>
				</div>
			</li>
		</ul>
	</div>
</div>
<!-- /main navbar -->