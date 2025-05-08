<!-- Main sidebar -->

<div class="sidebar sidebar-dark sidebar-main sidebar-fixed sidebar-expand-sm">
	<!-- Sidebar mobile toggler -->
	<div class="sidebar-mobile-toggler text-center">
		<a href="#" class="sidebar-mobile-main-toggle"><i class="icon-arrow-left8"></i></a>
		Navigation <a href="#" class="sidebar-mobile-expand">
		<i class="icon-screen-full"></i>
		<i class="icon-screen-normal"></i>
		</a>
	</div>
	<!-- /sidebar mobile toggler -->
	<!-- Sidebar content -->
	<div class="sidebar-content">
		<!-- User menu -->
		<div class="sidebar-user">
			<div class="card-body">
				<div class="media">
					<div class="mr-3">
						<a href="#"><img src="<?=site_url('img/bang_jek.png'); ?>" width="38" height="38" class="rounded-circle bg-indigo" alt=""></a>
					</div>
					<div class="media-body">
						<div class="media-title font-weight-semibold"><?= !empty($this->session->userdata("jtetrace_fullname"))?$this->session->userdata("jtetrace_fullname"):'';?></div>
						<div class="font-size-xs opacity-50">
							<i class="icon-email font-size-sm"></i> &nbsp;<?= !empty($this->session->userdata("jtetrace_fullname"))?$this->session->userdata("jtetrace_fullname"):'';?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- /user menu -->
		<!-- Main navigation -->
		<div class="card card-sidebar-mobile">
			<ul class="nav nav-sidebar" data-nav-type="accordion">
				<!-- Main -->
				<li class="nav-item-header">
					<div class="text-uppercase font-size-xs line-height-xs">Main </div>
					<i class="icon-menu" title="Main"></i>
				</li>
				<li class="nav-item">
					<a href="<?= site_url(); ?>home/dashboard" class="nav-link <?php //if ($page == 'dashboard') { echo "active"; } ?>">
					<i class="icon-home4"></i><span>Dashboard </span></a>
				</li>

				<?php 
					$jtrace_menu	= $this->session->userdata('jtetrace_app_access');
					// foreach($modparent AS $p){ 
						foreach($jtrace_menu['menu'] AS $k=>$v){
						$idParent				=	$v;
						$jtrace_submenu =$jtrace_menu['submenu'][$k];
						// $modAktif	=	$jtrace_menu['parent_active'][$p];
						// $modAktif	=	$p->menu_parent_active;
						// var_dump($jtrace_submenu);exit();
						$info = $this->mMenu->getMenuDetail($v)->row_array();
						// var_dump($jtrace_submenu);exit();

				?>
				<li class="nav-item <?= ($info['menu_parent_active']==1)?'nav-item-submenu':'';?> <?= ($uri1 == strtolower(str_replace(" ","",$info['menu_title'])) && $info['menu_parent_active']==1)?'nav-item-expanded nav-item-open':''; ?>">
					<a href="<?= ($info['menu_parent_active']==1)?'#':site_url(strtolower(str_replace(" ","",$info['menu_title'])));?>" class="nav-link <?= ($uri1 == strtolower(str_replace(" ","",$info['menu_title'])) && $info['menu_parent_active']!=1)?'active':''; ?>"><i class="<?= !empty($p->menu_icon)?$p->menu_icon:'icon-insert-template'; ?>"></i> <span><?= $info['menu_alias']; ?> </span></a>
					<?php if($info['menu_parent_active']==1){ ?>
					<ul class="nav nav-group-sub" data-submenu-title="Animations">
					<?php 
						$child	= $this->load->mMenu->getSubModul($jtrace_submenu)->result();
						foreach($child AS $c){ 
							$idChild		=	$c->menu_id;
							$childAktif	= $c->menu_parent_active;
					?>
						<li class="nav-item <?= ($childAktif==1)?'nav-item-submenu':'';?> <?= ($pChild==$c->menu_id && $childAktif==1 )?'nav-item-expanded nav-item-open':''; ?>">
							<a href="<?= ($childAktif==1)?'#':site_url(strtolower(str_replace(" ","",$info['menu_title']))).'/'.strtolower(str_replace(" ","",$c->menu_title));?>" class="nav-link <?= ($uri2 == strtolower(str_replace(" ","",$c->menu_title)) && $childAktif!=1)?'active':''; ?>"><i class="<?= !empty($c->menu_icon)?$c->menu_icon:'fa fa-angle-double-right '; ?> mr-2"></i> <?= ucwords(strtolower($c->menu_alias));?> </a>
							<?php if($childAktif==1){ ?>
							<ul class="nav nav-group-sub">
								<?php 
									$subchild	= $this->load->mMenu->getSubChild($idChild)->result();
									foreach($subchild AS $s){ 
										$schildAktif	= $s->menu_parent_active;
								?>
								<li class="nav-item"><a href="<?= ($schildAktif==1)?'#':site_url(strtolower(str_replace(" ","",$info['menu_title']))).'/'.strtolower(str_replace(" ","",$s->menu_title));?>" class="nav-link <?= ($uri2 == strtolower(str_replace(" ","",$s->menu_title)) && $schildAktif!=1)?'active':''; ?>"><i class="<?= !empty($s->menu_icon)?$s->menu_icon:'fa fa-angle-double-right'; ?> mr-2"></i> <?= ucwords(strtolower($s->menu_alias)); ?></a></li>
								<?php } ?>
							</ul>
							<?php } ?>
						</li>
					<?php }  ?>
					</ul>
					<?php } ?>
				</li>
				<?php } ?>
				
				<!-- /page kits -->
			</ul>
		</div>
	</div>
</div>