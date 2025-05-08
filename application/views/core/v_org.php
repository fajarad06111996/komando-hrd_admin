<!--Modal Responsive-->
<style>
	.validation-invalid-labelx,
	.validation-valid-labelx {
		margin-top: 0.5rem;
		margin-bottom: 0.5rem;
		display: block;
		color: #F44336;
		position: relative;
		padding-left: 1.625rem;
	}
	.validation-invalid-labelx:before {
		content: "\ed6b";
	}
	.validation-invalid-labelx:before,
	.validation-valid-labelx:before {
		font-family: "icomoon";
		font-size: 1rem;
		position: absolute;
		top: 0.12502rem;
		left: 0;
		display: inline-block;
		line-height: 1;
		-webkit-font-smoothing: antialiased;
		-moz-osx-font-smoothing: grayscale;
	}
</style>
<div class="panel panel-red">
	<div class="panel-heading">Master Organisasi
    	<?= $write; ?>
  	</div>
	<div class="alert alert-success" style="display: none;"></div>
	<div class="panel_body">
		<div class="table-responsive">
			<table class="tree table table-striped table-bordered table-hover table-sm1">
				<thead>
					<tr>
						<th class="text-center"><b>AKSI</b></th>
						<th class="text-center"><b>KODE JABATAN</b></th>
						<th class="text-center"><b>NAMA JABATAN</b></th>
						<th class="text-center"><b>KEPALA JABATAN</b></th>
						<th class="text-center"><b>FOTO KEPALA JABATAN</b></th>
					</tr>
				</thead>
				<tbody>
					<?php if(empty($dataAkun)){ ?>
					<tr>
						<td colspan="3" class='text-center'>No Data</td>
					</tr>
					<?php 
						}
						foreach($dataAkun as $d):
							$adding_space = "";
							for ($iz=0; $iz < $d->organization_segment; $iz++) { 
								$adding_space .= '';
								// $adding_space .= '&nbsp;&nbsp;';
							}
							if($d->parent_active==1){
								if($d->organization_segment == 1){
									$org_code = '<b class="text-primary">'.$d->organization_number.'</b>';
								}else{
									$org_code = $adding_space.'<b><span class="text-danger">'.$d->organization_number.'</span></b>';
								}
							}else{
								if($d->organization_segment == 1){
									$org_code = '<span>'.$d->organization_number.'</span>';
								}else{
									$org_code = $adding_space.'<span>'.$d->organization_number.'</span>';
								}
							}
							if($d->status_head==1){
								$head_name = $d->employee_name;
								if(empty($d->photo)){
									$foto_head = $d->employee_photo;
								}else{
									$foto_head = $d->photo;
								}
							}else{
								$head_name = $d->head_name;
								$foto_head = $d->photo;
							}
					?>
					<tr class="treegrid-<?= $d->idx; ?><?= !empty($d->parent_idx)?' treegrid-parent-'.$d->parent_idx:''; ?>">
						<td class='text-center'>
							<div class='btn-group'>
								<?php if(!empty($this->security_function->permissions($this->filename . "-c"))){ ?>
								<h5 class='m-0'><a href='javascript:void(0);' id='<?= $this->secure->enc($d->idx); ?>' class='bEdit text-center badge badge-info' data-popup='tooltip' title='Edit <?= $d->organization_name; ?>' data-placement='right'><i class='icon-pencil5'></i></a></h5>
								<?php }else{ ?>
								<h5 class="m-0"><span class="badge badge-warning"><i class="icon-lock" data-popup="tooltip" title="Locked Edit <?= $d->organization_name; ?>" data-placement="right"></i></span></h5>
								<?php } ?>
								<?php if(!empty($this->security_function->permissions($this->filename . "-x"))){ ?>
								<h5 class="m-0"><a href="javascript:void(0);" id="<?= $this->secure->enc($d->idx); ?>" data="<?= $d->organization_name; ?>" class="bDelete text-center badge badge-danger" data-popup="tooltip" title="Delete <?= $d->organization_name; ?>" data-placement="right"><i class="icon-bin"></i></a></h5>
								<?php }else{ ?>
								<h5 class="m-0"><span class="badge badge-warning"><i class="icon-lock" data-popup="tooltip" title="Locked Delete <?= $d->organization_name; ?>" data-placement="right"></i></span></h5>
								<?php } ?>
							</div>
						</td>
						<td><?= $org_code; ?></td>
						<td><?= $d->parent_active==1 && $d->organization_segment == 1?'<b class="text-primary">'.$d->organization_name.'</b>':($d->parent_active==1 && $d->organization_segment == 2?'<b class="text-danger">'.$d->organization_name.'</b>':($d->parent_active==1 && $d->organization_segment == 3?'<b>'.$d->organization_name.'</b>':$d->organization_name)); ?></td>
						<td><?= $d->parent_active==1 && $d->organization_segment == 1?'<b class="text-primary">'.$head_name.'</b>':($d->parent_active==1 && $d->organization_segment == 2?'<b class="text-danger">'.$head_name.'</b>':($d->parent_active==1 && $d->organization_segment == 3?'<b>'.$head_name.'</b>':$head_name)); ?></td>
						<td ><img src="<?= $foto_head; ?>" width="100px" style="vertical-align: middle;border-style: none;display: block;margin: 0 auto;" alt=""></td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<div id="insert_form"></div>
<script src="<?= base_url(); ?>assets/global_assets/js/treegrid/jquery.treegrid.js"></script>
<script src="<?= base_url(); ?>assets/global_assets/js/treegrid/jquery.treegrid.bootstrap3.js"></script>
<script src="<?= base_url(); ?>assets/layout1/js/core/v_org.js?v=0.1" params='<?= $params; ?>'></script>