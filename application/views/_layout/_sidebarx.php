<!-- Main sidebar -->

<div class="sidebar sidebar-navy sidebar-main sidebar-fixed sidebar-expand-sm">
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
						<a href="#"><img src="<?= $this->session->userdata('JTphoto')==null?site_url('assets/images/RIDER.png'):($this->session->userdata('JTphoto')=='default.png'?site_url('assets/images/RIDER.png'):$this->session->userdata('JTphoto')); ?>" width="38" height="38" class="rounded-circle bg-indigo" alt=""></a>
					</div>
					<div class="media-body">
						<div class="media-title font-weight-semibold"><?= !empty($this->session->userdata("JTusername"))?$this->session->userdata("JTusername"):'';?></div>
						<div class="font-size-xs opacity-50">
							<i class="icon-email font-size-sm"></i> &nbsp;<?= !empty($this->session->userdata("JTusername"))?$this->session->userdata("JTusername"):'';?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- /user menu -->
		<!-- Main navigation -->
		<?php 
			$offId = $this->secure->dec($this->session->userdata('JToffice_id')); 
			$hubId = $this->secure->dec($this->session->userdata('JTorigin_hub_id')); 
			$office = $this->mOffice->searchMOffice($offId);
			$hub = $this->mOffice->searchHub($hubId);
		?>
		<div class="card card-sidebar-mobile">
			<ul class="nav nav-sidebar" data-nav-type="accordion">
				<!-- office -->
				<li class="nav-item">
					<a href="javascript:void(0)" class="bOffice nav-link"><span><?= $office != null? $office['office_name'] :'NON OFFICE' ; ?></span></a>
				</li>
				<!--<li class="nav-item">-->
				<!--	<a href="javascript:void(0)" class="nav-link"><span><?//= $hub != null? $hub['hub_name'] :'NON OFFICE' ; ?></span></a>-->
				<!--</li>-->
				<!-- Main -->
				<li class="nav-item-header">
					<div class="text-uppercase font-size-xs line-height-xs">Main </div>
					<i class="icon-menu" title="Main"></i>
				</li>

				<?php 
					$Qname	= $this->session->userdata('JTlevel');
					$Qmenu	= $this->session->userdata('JTapp_access');
					// $jtrace_sub		= $this->session->userdata('sub_access');
					// var_dump('<pre>');
					// var_dump($Qmenu);
					// var_dump('</pre>');
					if($Qmenu):
					foreach($Qmenu['menu'] AS $k=>$v){
						$idParent				= $v;
						$Qsubmenu 		= $Qmenu['submenu'][$k];
						$info 					= $this->mMenu->getMenuDetail($v)->row_array();
						// var_dump('<pre>');
						// var_dump($Qsubmenu);
						// var_dump('</pre>');
				?>
				<li class="nav-item <?= ($info['menu_parent_active']==1)?'nav-item-submenu':'';?> <?= ($uri1 == strtolower(str_replace(" ","",$info['menu_title'])) && $info['menu_parent_active']==1)?'nav-item-expanded nav-item-open':''; ?>">
					<a href="<?= ($info['menu_parent_active']==1)?'#':site_url(strtolower(str_replace(" ","",$info['menu_title'])));?>" class="nav-link <?= ($uri1 == strtolower(str_replace(" ","",$info['menu_title'])) && $info['menu_parent_active']!=1)?'active':''; ?>"><i class="<?= !empty($info['menu_icon'])?$info['menu_icon']:'icon-insert-template'; ?>"></i> <span><?= $info['menu_alias']; ?> </span></a>
					<?php if($info['menu_parent_active']==1){ ?>
					<ul class="nav nav-group-sub" data-submenu-title="Animations">
					<?php 
						$child	= $this->mMenu->getSubModul($Qsubmenu)->result();
						foreach($child AS $c){ 
							$idChild		=	$c->menu_id;
							$childAktif	= $c->menu_parent_active;
					?>
						<li class="nav-item <?= ($childAktif==1)?'nav-item-submenu':'';?> <?= ($pChild==$c->menu_id && $childAktif==1 )?'nav-item-expanded nav-item-open':''; ?>">
							<a href="<?= ($childAktif==1)?'#':site_url(strtolower(str_replace(" ","",$info['menu_title']))).'/'.strtolower(str_replace(" ","",$c->menu_title));?>" class="nav-link <?= ($uri2 == strtolower(str_replace(" ","",$c->menu_title)) && $childAktif!=1)?'active':''; ?>"><i class="<?= !empty($c->menu_icon)?$c->menu_icon:'fa fa-angle-double-right '; ?> mr-2"></i> <?= ucwords(strtolower($c->menu_alias));?> </a>
							<?php if($childAktif==1){ ?>
							<ul class="nav nav-group-sub">
								<?php 
									$activechild	= $this->mMenu->getActiveChild($idChild,$Qname)->row_array();
									if($activechild):
									$subchild	= $this->load->mMenu->getChildSubModul($activechild['access_submenu_id'])->result();
									foreach($subchild AS $s){ 
										$schildAktif	= $s->menu_parent_active;
								?>
								<li class="nav-item"><a href="<?= ($schildAktif==1)?'#':site_url(strtolower(str_replace(" ","",$info['menu_title']))).'/'.strtolower(str_replace(" ","",$s->menu_title));?>" class="nav-link <?= ($uri2 == strtolower(str_replace(" ","",$s->menu_title)) && $schildAktif!=1)?'active':''; ?>"><i class="<?= !empty($s->menu_icon)?$s->menu_icon:'fa fa-angle-double-right'; ?> mr-2"></i> <?= ucwords(strtolower($s->menu_alias)); ?></a></li>
								<?php }endif; ?>
							</ul>
							<?php } ?>
						</li>
					<?php }  ?>
					</ul>
					<?php } ?>
				</li>
				<?php } endif; ?>
				
				<!-- /page kits -->
			</ul>
		</div>
	</div>
</div>

<script>
	$(document).ready(function(){
		// $(".sidebar-content").mouseenter(function(){
		// 	console.log('oke');
		// 	$('body').removeClass('sidebar-main-hidden');
		// 	$('body').removeClass('sidebar-xs');
		// });
		// $(".sidebar-content").mouseleave(function(){
		// 	$('body').addClass('sidebar-xs');
		// });
		$('.bOffice').on('click', function() {
			$('#xOffice').modal('show');
			$('#xOffice').find('.modal-title').text('Change OFFICE');
			$('#xOffice').find('#officeForm').attr('action', '<?= site_url('auth'); ?>/changeOffice');
			$(".select-search").select2({width: '100%'}).val('<?= $this->session->userdata('JToffice_id') ?>').trigger('change.select2');
			// if(code == 1){
			// 	$('#xStatement').find('.tBtn').text('Non Aktif ?');
			// 	$('#xStatement').find('.tContent').text('Change status to Non Aktif,');
			// }else{
			// 	$('#xStatement').find('.tBtn').text('Aktif ?');
			// 	$('#xStatement').find('.tContent').text('Change status to Aktif,');
			// }
			// $('#xStatement').find('.tName').text(data);
            // $('input[name=tId]').val(id);
			// $('input[name=tClCode]').val(code);
			// $("#stateForm").data('validator').resetForm();
			// $('#response_result').html('');
			// $('#stateForm')[0].reset();
			// document.getElementById("tUsername").readOnly = false;
			// xFalse();
		});

		$('#xOffice').submit(function(e) {
			e.preventDefault();
			var url = $('#xOffice').find('#officeForm').attr('action');
			var url2 = "<?= site_url(); ?>";
			var data = $('#xOffice').find('#officeForm').serialize();
			$.ajax({
				type: 'ajax',
				method: 'post',
				url: url,
				data: data,
				async: true,
				dataType: 'json',
				success: function(response) {
					// console.log(response);
					if (response.error) {
					}else if(response.status){
            			$('#xOffice').modal('hide');
						if(response.text){
							notiferror(response.text);
						}else{
							notiferror_a('Error Update Access User To Database');
						}
					}else{
            			$('#xOffice').modal('hide');
						notifsukses('Office ' + response.type + ' Successfully');
						// dataTable.ajax.reload();
						setTimeout(function () { window.location.replace(url2); }, lama_akses+500);
						$("#officeForm").data('validator').resetForm();
						$('#officeForm')[0].reset();
					}
				},
				error: function(error) {
					console.log(error);
					$('#xOffice').modal('hide');
          			notiferror('Error Update To Database');
				}
			});

		});
	});
</script>