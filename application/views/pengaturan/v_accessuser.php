<!--Modal Responsive-->
<div id="mForm" data-backdrop="static" data-keyboard="false" class="modal fade">
	<div class="modal-dialog " style="max-width: 700px;">
		<div class="modal-content">
			<div class="modal-header pt-2 pb-2 bg-blue-700">
				<h4 class="modal-title"></h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<form action="<?= $link; ?>/fActAccessUser" id="myForm" class="form-horizontal form-validasi" method="post">
				<div class="modal-body">
				<h5><span style="display: none;" id="tMessage" class="badge badge-flat border-danger text-danger-600 mb-1">Block badge</span></h5>
					<div class="form-group row mb-1">
						<label class="col-form-label col-sm-3">LEVEL ACCESS<sup><b class="text-danger">*</b></sup></label>
						<div class="col-md-6">
							<!-- <select name="tUname" data-placeholder="Select a Level" class="form-control form-control-sm" required> -->
							<input type="hidden" name="csrf" value="<?= $this->session->csrf_token; ?>">
							<select name="tUname" data-placeholder="Select a Level" class="form-control form-control-sm select-search"  data-container-css-class="select-sm" data-fouc  required>
								<option value=""></option>
								<?php foreach ($level as $l) { ?>
                				<option value="<?= $l->level_id; ?>" ><?= $l->level_name.' ('.$l->level_alias.' )'; ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
					<?php //if($isLevel == 1): ?>
					<div class="form-group row mb-1">
						<label class="col-form-label col-sm-3">COMPANY<sup><b class="text-danger">*</b></sup></label>
						<div class="col-md-6">
							<!-- <select name="tUname" data-placeholder="Select a Level" class="form-control form-control-sm" required> -->
							<select name="tCompany" data-placeholder="Select a Company" class="form-control form-control-sm select-search"  data-container-css-class="select-sm" data-fouc  required>
								<option value=""></option>
								<?php foreach ($company as $o) { ?>
                				<option value="<?= $o->idx; ?>" ><?= $o->company_name; ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
					<?php //endif; ?>
					<div class="form-group row mb-1">
						<label class="col-form-label col-sm-3">MENU PARENT<sup><b class="text-danger">*</b></sup></label>
						<div class="col-md-6">
							<select name="tMenu"  class="form-control form-control-sm select-search tMenu" data-placeholder="Select a Menu Parent" data-fouc required>
								<option value="" disabled>Select a Menu Parent</option>
								<?php foreach ($menu as $m) { ?>
                				<option value="<?= $m->menu_id; ?>" ><?= $m->menu_alias; ?></option>
								<?php } ?>
							</select>
						</div>
						<div class="col-md-3">
							<button type="button" class="btn btn-primary btn-ladda btn-ladda-spinner btn-sm pt-1 bGetdata" data-spinner-color="#333" data-style="slide-down">
								<i class="icon-search4"></i> <span class="ladda-label">Show Data</span>
							</button>
						</div>
					</div>
					<!-- <div class="form-group row mb-1">
						<label class="col-form-label col-sm-3">SUB MODUL</label>
						<div class="col-md-6">
							<select name="tSubmenu" class="form-control form-control-sm tSubmenu select-search">
                			<option value="" disabled>Select a Sub Modul</option>
								<?php //foreach ($submodul as $s) { ?>
                				<option value="<?//= $s->menu_id; ?>" ><?//= $s->menu_alias; ?></option>
								<?php //} ?>
							</select>
						</div>
					</div> -->
					<div class="table-responsive pre-scrollable " id="tGetTable" style="height: 300px;display: none;">
						<table id="example1" class="table table-striped table-bordered table-hover table-sm1">
							<thead>
								<tr>
									<th rowspan="4">NAMA MODUL</th>
									<th colspan="7" class="text-center">PERMISSION ONLY</th>
								</tr>
								<tr>
									<th class="text-center">Read</th>
									<th class="text-center">Write</th>
									<th class="text-center">Change</th>
									<th class="text-center">Execute</th>
								</tr>
							</thead>
							<tbody id="result">
							</tbody>
						</table>
					</div>
				</div>
				<div class="card-footer" id="bHidden">
					<div class="d-flex justify-content-between align-items-center">
						<button type="button" data-dismiss="modal" class="btn btn-outline-secondary">Close</button>
						<button type="submit" class="btn btn-primary btn-ladda btn-ladda-spinner" data-spinner-color="#333" data-style="slide-down">Save changes</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="panel panel-blue card">
	<div class="panel-heading">Daftar Access User
		<?=$write; ?>
  	</div>
	<div class="panel_body">
		<div class="table-responsive">
			<table id="example2" class="table table-striped table-bordered table-hover table-sm1">
				<thead>
					<tr>
						<th style="text-align: center;width: 20px;">#</th>
						<th>LEVEL NAME</th>
						<th>MENU/SUB</th>
						<th>SUB MENU/CHILD</th>
						<th>PERMISSION SUB MENU</th>
						<th style="width:7%;text-align: center;">Aksi</th>
					</tr>
				</thead>
				<tbody>

				</tbody>
			</table>
		</div>
	</div>
</div>
<!--loading bootstrap js-->
<script src="<?= base_url(); ?>assets/layout1/js/pengaturan/v_accessuser.js?v=0.1" params='<?= $params; ?>'></script>