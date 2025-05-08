<!--Modal Responsive-->
<?php //var_dump('<pre>');var_dump($counterx); ?>
<div id="mForm" data-backdrop="static" data-keyboard="false" class="modal fade">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header pt-2 pb-2 bg-blue-700">
				<h4 class="modal-title"></h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<form action="" id="myForm" class="form-horizontal form-validasi" method="post">
				<div class="modal-body">
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group row mb-1">
								<label class="col-form-label col-sm-3 text-uppercase">Username Login</label>
								<div class="col-md-4">
									<input id="tUsername" name="tUsername" type="text" placeholder="Username Login" class="form-control form-control-sm" style="text-transform:lowercase" required/>
								</div>
								<span id="response_result" class="mt-1"></span>
							</div>
							<div class="form-group row mb-1">
								<label class="col-form-label col-sm-3 text-uppercase">Fullname</label>
								<div class="col-md-9">
									<?= csrf_input(); ?>
									<input type="hidden" name="tId" />
									<input name="tNama" type="text" placeholder="Fullname" class="form-control form-control-sm" required/>
								</div>
							</div>
							<div class="form-group row mb-1">
								<label class="col-form-label col-sm-3 text-uppercase">Address</label>
								<div class="col-md-9">
									<textarea name="tAddress" type="text" placeholder="Address" class="form-control form-control-sm" required></textarea>
								</div>
							</div>
							<div class="form-group row mb-1">
								<label for="inputEmail" class="col-form-label col-sm-3 text-uppercase">E-mail</label>
								<div class="col-md-9">
									<input type="text" name="tEmail" placeholder="E-mail" class="form-control form-control-sm" />
								</div>
							</div>
							<div class="form-group row mb-1">
								<label for="inputEmail" class="col-form-label col-sm-3 text-uppercase">No HP</label>
								<div class="col-md-9">
									<input type="text" name="tPhone" placeholder="No HP" class="form-control form-control-sm" />
								</div>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group row mb-1">
								<label class="col-form-label col-sm-3 text-uppercase">Level Access</label>
								<div class="col-md-9">
									<select name="tLevel" data-placeholder="Select a Level Access" class="form-control form-control-sm select-search"  data-container-css-class="select-sm" data-fouc required>
										<option></option>
										<?php foreach ($level as $l) { ?>
											<option value="<?= $this->secure->enc($l->level_id); ?>" ><?= $l->level_alias; ?></option>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="form-group row mb-1">
								<label class="col-form-label col-sm-3 text-uppercase">Company</label>
								<div class="col-md-9">
									<select name="company" data-placeholder="Select a Company" class="form-control form-control-sm select-search"  data-container-css-class="select-sm" data-fouc required>
										<option></option>
										<?php foreach ($office as $o) { ?>
											<option value="<?= $this->secure->enc($o->idx); ?>" ><?= $o->company_name; ?></option>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="form-group row mb-1">
								<label class="col-form-label col-sm-3">Status User</label>
								<div class="col-md-9">
									<div class="form-check form-check-inline">
										<label class="form-check-label">	<!-- tBnaktif -->
											<input type="radio" value="1" class="form-check-input-styled" name="tStatus" id="xTrue">Aktif
										</label>
									</div>
									<div class="form-check form-check-inline">
										<label class="form-check-label">	<!-- tBaktif -->
											<input type="radio" value="0" class="form-check-input-styled" name="tStatus" id="xFalse">Non-Aktif
										</label>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="card-footer">
					<div class="d-flex justify-content-between align-items-center">
						<button type="button" data-dismiss="modal" class="btn btn-secondary">Close</button>
						<button type="submit" class="btn btn-primary btn-ladda btn-ladda-spinner" data-spinner-color="#333" data-style="slide-down">Save changes</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="panel panel-blue">
	<div class="panel-heading">List Daftar User
	<?= $write; ?>
  	</div>
	<div class="panel_body pan">
		<div class="form-body">
			<div class="form-group row mb-1">
				<label class="col-form-label col-sm-1 ">Company</label>
				<div class="col-md-5">
					<?= csrf_input(); ?>
					<select name="company_show" id="company_show" data-placeholder="Select a Company" class="form-control form-control-xs select-search"  data-container-css-class="select-xs" data-fouc>
						<?php
							foreach ($office as $o) {
						?>
							<option value="<?= $this->secure->enc($o->idx); ?>" data-icon="<?= $o->company_code; ?>"><?= $o->company_name; ?></option>
						<?php } ?>
					</select>
				</div>
			</div>
		</div>
	</div>
	<div class="panel_body">
		<div class="table-responsive">
			<table id="example2" class="table table-striped table-bordered table-hover table-sm1">
				<thead>
					<tr>
						<th class="text-center">#</th>
						<th class="text-center">Aksi</th>
						<th class="text-center">STATUS</th>
						<th class="text-center">USERNAME</th>
						<th class="text-center">FULLNAME</th>
						<th class="text-center">LEVEL NAME</th>
						<th class="text-center">COMPANY</th>
						<th class="text-center">ADDRESS</th>
						<th class="text-center">EMAIL</th>
						<th class="text-center">HP</th>
						<th class="text-center">STAMP</th>
					</tr>
				</thead>
				<tbody>

				</tbody>
			</table>
		</div>
	</div>
</div>
<!--loading bootstrap js-->
<script src="<?= base_url(); ?>assets/layout1/js/pengaturan/v_user.js?v=0.2" params='<?= $params; ?>'></script>