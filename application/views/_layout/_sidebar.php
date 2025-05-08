<!-- Main Sidebar Container -->
<aside class="main-sidebar <?= $rBar['sidevariant']; ?> elevation-4">
    <!-- Brand Logo -->
    <a href="<?= base_url(); ?>" class="brand-link <?= $rBar['blogovariant']; ?>">
        <input name="user_idx" type="hidden" value="<?= $this->session->userdata('JTidx'); ?>" />
        <input name="base_url" type="hidden" value="<?= base_url(); ?>" />
        <img src="<?= logo_apps_white(); ?>" alt="<?= brand_name();?>" class="brand-image" style="opacity: .8">
        <span class="brand-text font-weight-light">HRD</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="<?= $this->session->userdata('JTphoto')==null?site_url('assets/images/RIDER.png'):($this->session->userdata('JTphoto')=='default.png'?site_url('assets/images/RIDER.png'):$this->session->userdata('JTphoto')); ?>" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block"><?= !empty($this->session->userdata("JTusername"))?$this->session->userdata("JTusername"):'';?></a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <?php 
            $Qname	    = $this->session->userdata('JTlevel');
            $level      = $this->secure->dec($Qname);
			$offId      = $this->secure->dec($this->session->userdata('JToffice_id')); 
			$hubId      = $this->secure->dec($this->session->userdata('JTorigin_hub_id'));
			$counterId  = $this->secure->dec($this->session->userdata('JTcounter_id'));
		?>
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                    with font-awesome or any other icon font library -->
                <li class="nav-header">home</li>
                <li class="nav-item">
                    <a href="#" class="nav-link bCompany">
                        <i class="nav-icon far fa-circle text-danger"></i>
                        <p class="text"><?= $companyData != null? $companyData['company_name'] :'NON OFFICE' ; ?></p>
                    </a>
                </li>
                <li class="nav-header">Menu</li>
                <?php 
					$Qmenu	= $this->session->userdata('JTapp_access');
					// $jtrace_sub		= $this->session->userdata('sub_access');
					// var_dump('<pre>');var_dump($Qmenu);die;
					if($Qmenu):
					foreach($Qmenu['menu'] AS $k=>$v){
						$idParent				= $v;
						$Qsubmenu 		= $Qmenu['submenu'][$k];
						$info 					= $this->mMenu->getMenuDetail($v)->row_array();
						// var_dump('<pre>');
						// var_dump($info);
						// var_dump('</pre>');
				?>
                <li class="nav-item <?= ($info['menu_parent_active']==1)?'has-treeview':'';?> <?= ($uri1 == strtolower(str_replace(" ","",$info['menu_title'])) && $info['menu_parent_active']==1)?'menu-open':''; ?>">
                    <a href="<?= ($info['menu_parent_active']==1)?'#':site_url(strtolower(str_replace(" ","",$info['menu_title'])));?>" class="nav-link <?= (strtolower($uri1) == strtolower(str_replace(" ","",$info['menu_title'])) && $info['menu_parent_active']!=1)?'active':((strtolower($uri1) == strtolower(str_replace(" ","",$info['menu_title'])) && $info['menu_parent_active']==1)?'active':''); ?>">
                        <i class="nav-icon <?= !empty($info['menu_icon'])?$info['menu_icon']:'icon-insert-template'; ?>"></i>
                        <p class="<?= ($info['menu_parent_active']==1)?'':'text';?>">
                            <?= $info['menu_alias']; ?>
                            <?php if($info['menu_parent_active']==1): ?>
                            <i class="right fas fa-angle-left"></i>
                            <?php endif; ?>
                        </p>
                    </a>
                    <?php if($info['menu_parent_active']==1){ ?>
                    <ul class="nav nav-treeview">
                        <?php 
                            $child	= $this->mMenu->getSubModul($Qsubmenu)->result();
                            foreach($child AS $c){ 
                                $idChild		=	$c->menu_id;
                                $childAktif	= $c->menu_parent_active;
                        ?>
                        <li class="nav-item <?= ($childAktif==1)?'has-treeview':'';?> <?= ($pChild==$c->menu_id && $childAktif==1 )?'menu-open':''; ?>">
                            <a href="<?= ($childAktif==1)?'#':site_url(strtolower(str_replace(" ","",$info['menu_title']))).'/'.strtolower(str_replace(" ","",$c->menu_title));?>" class="nav-link <?= (strtolower($uri2) == strtolower(str_replace(" ","",$c->menu_title)) && $childAktif!=1)?'active':''; ?>">
                                <i class="<?= !empty($c->menu_icon)?$c->menu_icon:'far fa-circle text-danger'; ?> nav-icon"></i>
                                <p>
                                    <?= ucwords(strtolower($c->menu_alias));?>
                                    <?php if($childAktif==1): ?>
                                    <i class="right fas fa-angle-left"></i>
                                    <?php endif; ?>
                                </p>
                            </a>
                            <?php if($childAktif==1){ ?>
                            <ul class="nav nav-treeview">
                                <?php 
									$activechild	= $this->mMenu->getActiveChild($idChild,$Qname)->row_array();
									if($activechild):
									$subchild	= $this->load->mMenu->getChildSubModul($activechild['access_submenu_id'])->result();
									foreach($subchild AS $s){ 
										$schildAktif	= $s->menu_parent_active;
								?>
                                <li class="nav-item">
                                    <a href="<?= ($schildAktif==1)?'#':site_url(strtolower(str_replace(" ","",$info['menu_title']))).'/'.strtolower(str_replace(" ","",$s->menu_title));?>" class="nav-link <?= ($uri2 == strtolower(str_replace(" ","",$s->menu_title)) && $schildAktif!=1)?'active':''; ?>">
                                        <i class="<?= !empty($s->menu_icon)?$s->menu_icon:'far fa-dot-circle'; ?> nav-icon"></i>
                                        <p><?= ucwords(strtolower($s->menu_alias)); ?></p>
                                    </a>
                                </li>
                                <?php }endif; ?>
                            </ul>
                            <?php } ?>
                        </li>
                        <?php } ?>
                    </ul>
                    <?php } ?>
                </li>
                <?php } endif; ?>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
<script src="<?= base_url(); ?>assets/layout1/js/layout/sidebar.js?v=0.1" params='<?= $sideBarParams; ?>'></script>