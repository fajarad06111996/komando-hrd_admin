<!--Modal Responsive-->
<div id="mForm" data-backdrop="static" data-keyboard="false" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header pt-2 pb-2 bg-blue-700">
				<h4 class="modal-title"></h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<form action="" id="myForm" class="form-horizontal form-validasi" method="post">
				<div class="modal-body">
					<?php if($oType!=2){ ?>
					<div class="form-group row mb-1">
						<label class="col-form-label col-sm-3 text-uppercase">Office<sup class="text-danger">*</sup></label>
						<div class="col-md-9">
							<select name="office" data-placeholder="Select a Office" class="form-control form-control-sm select-search"  data-container-css-class="select-sm" data-fouc required>
								<option></option>
								<?php foreach ($listOffice as $o) { ?>
									<option value="<?= $this->secure->enc($o->idx); ?>" ><?= $o->office_name; ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
					<?php } ?>
					<div class="form-group row mb-1">
						<label class="col-form-label col-sm-3 text-uppercase">Level Name<sup class="text-danger">*</sup></label>
						<div class="col-md-9">
							<input id="tLevelname" name="level_name" type="text" placeholder="Level Name" class="form-control form-control-sm" required/>
							<span id="response_result" class="mt-1"></span>
						</div>
					</div>
					<div class="form-group row mb-1">
						<label class="col-form-label col-sm-3 text-uppercase">Level Alias<sup class="text-danger">*</sup></label>
						<div class="col-md-9">
							<?= csrf_input(); ?>
							<input name="level_alias" type="text" placeholder="Level Alias" class="form-control form-control-sm" required/>
						</div>
					</div>
					<div class="form-group row mb-1">
						<label class="col-form-label col-sm-3 text-uppercase">Level User Type<sup class="text-danger">*</sup></label>
						<div class="col-md-9">
							<select name="user_type" data-placeholder="Select a Level User Type" class="form-control form-control-sm select-search"  data-container-css-class="select-sm" data-fouc required>
								<option></option>
								<?php foreach ($listUserType as $l) { ?>
									<option value="<?= $this->secure->enc($l->statusx); ?>" ><?= $l->status_name; ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="form-group row mb-1">
						<label class="col-form-label col-sm-3">Status Level</label>
						<div class="col-md-9">
							<div class="form-check form-check-inline">
								<label class="form-check-label">	<!-- tBnaktif -->
									<input type="radio" value="1" class="form-check-input-styled" name="level_active" id="xTrue">Aktif
								</label>
							</div>
							<div class="form-check form-check-inline">
								<label class="form-check-label">	<!-- tBaktif -->
									<input type="radio" value="0" class="form-check-input-styled" name="level_active" id="xFalse">Non-Aktif
								</label>
							</div>
						</div>
					</div>
					<div class="form-group row mb-1">
						<label class="col-form-label col-sm-3 text-uppercase">Remark</label>
						<div class="col-md-9">
							<textarea type="text" name="level_remark" placeholder="Remark" class="form-control form-control-sm"></textarea>
						</div>
					</div>
				</div>
				<div class="card-footer">
					<div class="d-flex justify-content-between align-items-center">
						<button type="button" data-dismiss="modal" class="btn btn-outline-secondary">Close</button>
						<button type="submit" class="btn btn-primary btn-ladda btn-ladda-spinner" data-spinner-color="#333" data-style="slide-down">Save changes</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<!--Modal Responsive END-->
<!--Modal Responsive Counter Viewer-->
<div id="mAccCount" data-backdrop="static" data-keyboard="false" class="modal fade">
	<div class="modal-dialog modal-lg" style="max-width: 700px;">
		<div class="modal-content">
			<div class="modal-header pt-2 pb-2 bg-blue-700">
				<h4 class="modal-title"></h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<form action="" id="myAccCount" class="form-horizontal form-validasi2" method="post">
				<div class="modal-body">
                    <div class="row">
						<input type="hidden" name="tLevel">
                        <div class="col-sm-12">
                            <div class="table-responsive pre-scrollable " id="tGetTable" style="max-height:300px;">
                                <table id="hAccessCOunter" class="table table-striped table-bordered table-hover table-sm1">
                                    <thead>
                                        <tr>
                                            <th class="text-center">COUNTER</th>
                                            <th class="text-center">PERMISSION ONLY</th>
                                        </tr>
                                    </thead>
                                    <tbody id="result">
                                    </tbody>
                                </table>
                            </div>
                        </div>
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
<!--End Modal Counter Viewer END-->
<!--Modal Responsive Settings-->
<div id="mSettings" data-backdrop="static" data-keyboard="false" class="modal fade">
	<div class="modal-dialog modal-lg" style="max-width: 700px;">
		<div class="modal-content">
			<div class="modal-header pt-2 pb-2 bg-blue-700">
				<h4 class="modal-title"></h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<form action="" id="mySettings" class="form-horizontal form-validasi2" method="post">
				<div class="modal-body">
                    <div class="row">
						<input type="hidden" name="tLevel">
                        <div class="col-sm-12">
                            <div class="table-responsive pre-scrollable " id="tGetTable" style="max-height:300px;">
                                <table id="hAccessSettings" class="table table-striped table-bordered table-hover table-sm1">
                                    <thead>
                                        <tr>
                                            <th class="text-center">SETTING ASSIGN DRIVER</th>
                                        </tr>
                                    </thead>
                                    <tbody id="result">
                                    </tbody>
                                </table>
                                <table id="hAccessSettings2" class="table table-striped table-bordered table-hover table-sm1">
                                    <thead>
                                        <tr>
                                            <th class="text-center">SETTING STATUS BO</th>
                                        </tr>
                                    </thead>
                                    <tbody id="result2">
                                    </tbody>
                                </table>
                            </div>
                        </div>
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
<!--End Modal Settings END-->
<div class="panel panel-blue">
	<div class="panel-heading">List Daftar Level
	<?= $write; ?>
  </div>
  <div class="panel_body">
	  <!-- <input type="text" name="token" class="form-control form-control-sm" value="<?//= $this->session->csrf_token;?>"> -->
		<div class="table-responsive">
			<table id="example1" class="table table-striped table-bordered table-hover table-sm1">
				<thead>
					<tr>
						<th class="text-center">Aksi</th>
						<th class="text-center">#</th>
						<th class="text-center">LEVELNAME</th>
						<th class="text-center">ALIAS</th>
						<th class="text-center">USER TYPE</th>
						<th class="text-center">OFFICE NAME</th>
						<th class="text-center">STATUS</th>
						<th class="text-center">STAMP</th>
						<th class="text-center">REMARK</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
	</div>
</div>
<!--loading bootstrap js-->
<script src="<?= base_url(); ?>assets/layout1/js/pengaturan/v_level.js?v=0.1" params='<?= $params; ?>'></script>