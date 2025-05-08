<style>
    .dropdown-toggle::after {
        display: unset !important;
        margin-left: unset !important;
        vertical-align: unset !important;
        content: unset !important;
        border-top: unset !important;
        border-right: unset !important;
        border-bottom: unset !important;
        border-left: unset !important;
    }
</style>
<!-- Modal Form Organization -->
<div id="mForm" data-backdrop="static" data-keyboard="false" class="modal fade">
	<div class="modal-dialog modal-super">
		<div class="modal-content">
			<div class="modal-header pt-2 pb-2 bg-blue-700">
				<h4 class="modal-title"></h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<form action="" id="myForm" class="form-horizontal form-validasi" accept-charset="utf-8" enctype="multipart/form-data" method="post">
				<div class="modal-body">
					<div class="row justify-content-around">
						<div class="col-sm-5">
							<div class="form-group row mb-1">
								<label class="col-form-label col-sm-3 text-uppercase text-right">Mode Shift<sup><b class="text-danger">*</b></sup></label>
								<div class="col-md-9">
									<div class="form-check form-check-inline">
										<input class="form-check-input" type="radio" name="shift_mode" id="shift_mode1" value="0">
										<label class="form-check-label" for="inlineRadio1">Office Hour</label>
									</div>
									<div class="form-check form-check-inline">
										<input class="form-check-input" type="radio" name="shift_mode" id="shift_mode2" value="1">
										<label class="form-check-label" for="inlineRadio2">Cycle</label>
									</div>
								</div>
							</div>
							<div class="form-group row mb-1">
								<label class="col-form-label col-sm-3 text-uppercase text-right">Nama Shift<sup><b class="text-danger">*</b></sup></label>
								<div class="col-md-9">
									<?= csrf_input(); ?>
									<input type="hidden" name="tId" />
									<input id="shift_name" name="shift_name" type="text" placeholder="Nama Shift" class="form-control form-control-sm" required/>
								</div>
							</div>
							<div class="offHour">
								<div class="form-group row mb-1">
									<label class="col-form-label col-sm-3 text-uppercase text-right">Mulai Lembur</sup></label>
									<div class="input-group col-sm-5">
										<input id="start_overtime" name="start_overtime" type="text" placeholder="Mulai Lembur" class="form-control form-control-sm time_input" readonly/>
										<div class="input-group-prepend">
											<a href="javascript:void(0);" class="input-group-text form-control-sm bClean">
												<i class="icon-bin text-danger"></i>
											</a>
										</div>
									</div>
								</div>
							</div>
							<div class="form-group row mb-1">
								<label class="col-form-label col-sm-3 text-uppercase text-right">Deskripsi<sup><b class="text-danger">*</b></sup></label>
								<div class="col-md-9">
									<textarea rows="2" id="description" name="description" type="text" placeholder="Deskripsi" class="form-control form-control-sm" required></textarea>
								</div>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="offHour">
								<div class="form-group row mb-1">
									<label class="col-form-label col-sm-2 text-uppercase text-right">Senin</sup></label>
									<div class="col-sm-5">
										<input id="monday_in" name="monday_in" type="text" placeholder="Jam Masuk" class="form-control form-control-sm time_input" readonly/>
									</div>
									<div class="input-group col-sm-5">
										<input id="monday_out" name="monday_out" type="text" placeholder="Jam Pulang" class="form-control form-control-sm time_input" readonly/>
										<div class="input-group-prepend">
											<a href="javascript:void(0);" class="input-group-text form-control-sm bClean">
												<i class="icon-bin text-danger"></i>
											</a>
										</div>
									</div>
								</div>
								<div class="form-group row mb-1">
									<label class="col-form-label col-sm-2 text-uppercase text-right">Selasa</label>
									<div class="col-sm-5">
										<input id="tuesday_in" name="tuesday_in" type="text" placeholder="Jam Masuk" class="form-control form-control-sm time_input" readonly/>
									</div>
									<div class="input-group col-sm-5">
										<input id="tuesday_out" name="tuesday_out" type="text" placeholder="Jam Pulang" class="form-control form-control-sm time_input" readonly/>
										<div class="input-group-prepend">
											<a href="javascript:void(0);" class="input-group-text form-control-sm bClean">
												<i class="icon-bin text-danger"></i>
											</a>
										</div>
									</div>
								</div>
								<div class="form-group row mb-1">
									<label class="col-form-label col-sm-2 text-uppercase text-right">Rabu</sup></label>
									<div class="col-sm-5">
										<input id="wednesday_in" name="wednesday_in" type="text" placeholder="Jam Masuk" class="form-control form-control-sm time_input" readonly/>
									</div>
									<div class="input-group col-sm-5">
										<input id="wednesday_out" name="wednesday_out" type="text" placeholder="Jam Pulang" class="form-control form-control-sm time_input" readonly/>
										<div class="input-group-prepend">
											<a href="javascript:void(0);" class="input-group-text form-control-sm bClean">
												<i class="icon-bin text-danger"></i>
											</a>
										</div>
									</div>
								</div>
								<div class="form-group row mb-1">
									<label class="col-form-label col-sm-2 text-uppercase text-right">Kamis</label>
									<div class="col-sm-5">
										<input id="thursday_in" name="thursday_in" type="text" placeholder="Jam Masuk" class="form-control form-control-sm time_input" readonly/>
									</div>
									<div class="input-group col-sm-5">
										<input id="thursday_out" name="thursday_out" type="text" placeholder="Jam Pulang" class="form-control form-control-sm time_input" readonly/>
										<div class="input-group-prepend">
											<a href="javascript:void(0);" class="input-group-text form-control-sm bClean">
												<i class="icon-bin text-danger"></i>
											</a>
										</div>
									</div>
								</div>
								<div class="form-group row mb-1">
									<label class="col-form-label col-sm-2 text-uppercase text-right">Jum'at</label>
									<div class="col-sm-5">
										<input id="friday_in" name="friday_in" type="text" placeholder="Jam Masuk" class="form-control form-control-sm time_input" readonly/>
									</div>
									<div class="input-group col-sm-5">
										<input id="friday_out" name="friday_out" type="text" placeholder="Jam Pulang" class="form-control form-control-sm time_input" readonly/>
										<div class="input-group-prepend">
											<a href="javascript:void(0);" class="input-group-text form-control-sm bClean">
												<i class="icon-bin text-danger"></i>
											</a>
										</div>
									</div>
								</div>
								<div class="form-group row mb-1">
									<label class="col-form-label col-sm-2 text-uppercase text-right">Sabtu</label>
									<div class="col-sm-5">
										<input id="saturday_in" name="saturday_in" type="text" placeholder="Jam Masuk" class="form-control form-control-sm time_input" readonly/>
									</div>
									<div class="input-group col-sm-5">
										<input id="saturday_out" name="saturday_out" type="text" placeholder="Jam Pulang" class="form-control form-control-sm time_input" readonly/>
										<div class="input-group-prepend">
											<a href="javascript:void(0);" class="input-group-text form-control-sm bClean">
												<i class="icon-bin text-danger"></i>
											</a>
										</div>
									</div>
								</div>
								<div class="form-group row mb-1">
									<label class="col-form-label col-sm-2 text-uppercase text-right">Minggu</label>
									<div class="col-sm-5">
										<input id="sunday_in" name="sunday_in" type="text" placeholder="Jam Masuk" class="form-control form-control-sm time_input" readonly/>
									</div>
									<div class="input-group col-sm-5">
										<input id="sunday_out" name="sunday_out" type="text" placeholder="Jam Pulang" class="form-control form-control-sm time_input" readonly/>
										<div class="input-group-prepend">
											<a href="javascript:void(0);" class="input-group-text form-control-sm bClean">
												<i class="icon-bin text-danger"></i>
											</a>
										</div>
									</div>
								</div>
								<div class="form-group row mb-1">
									<label class="col-form-label col-sm-2 text-uppercase text-right">Piket</label>
									<div class="col-sm-5">
										<input id="piket_in" name="piket_in" type="text" placeholder="Jam Masuk" class="form-control form-control-sm time_input" readonly/>
									</div>
									<div class="input-group col-sm-5">
										<input id="piket_out" name="piket_out" type="text" placeholder="Jam Pulang" class="form-control form-control-sm time_input" readonly/>
										<div class="input-group-prepend">
											<a href="javascript:void(0);" class="input-group-text form-control-sm bClean">
												<i class="icon-bin text-danger"></i>
											</a>
										</div>
									</div>
								</div>
							</div>
							<!-- <div class="cHour">
								<div class="container bg-white mb-3">
									<div class="row">
										<div class="col-lg-12">
											<h5 align="center">KALENDER</h5>
											<div id="calendar"></div>
										</div>
									</div>
								</div>
							</div> -->
							<div class="cHour">
								<!-- <div class="form-group row mb-1">
									<label class="col-form-label col-sm-3 text-uppercase text-right">Model Siklus</label>
									<div class="col-md-9">
										<select id="cycle_mode" name="cycle_mode" data-placeholder="Model Siklus"
											class="form-control form-control-sm select-search"
											data-container-css-class="select-sm" data-fouc>
											<option value="0">Model 1</option>
											<option value="1">Model 2</option>
											<option value="2">Model 3</option>
											<option value="3">Model 4</option>
											<option value="4">Model 5</option>
											<option value="5">Model 6</option>
											<option value="6">Model 7</option>
										</select>
									</div>
								</div> -->
								<div class="form-group row mb-1">
									<label class="col-form-label col-sm-3 text-uppercase text-right">Jumlah Hari Kerja</label>
									<div class="col-sm-4">
										<input id="work_days" name="work_days" type="number" placeholder="Jumlah Hari Kerja" value="4" class="form-control form-control-sm"/>
									</div>
								</div>
								<div class="form-group row mb-1">
									<label class="col-form-label col-sm-3 text-uppercase text-right">Jumlah Hari Libur</label>
									<div class="col-sm-4">
										<input id="off_days" name="off_days" type="number" placeholder="Jumlah Hari Libur" value="1" class="form-control form-control-sm"/>
									</div>
								</div>
								<!-- <div class="form-group row mb-1">
									<label class="col-form-label col-sm-3 text-uppercase text-right">Jam Kerja</label>
									<div class="col-sm-4">
										<input id="check_in" name="check_in" type="text" placeholder="Jam Masuk" class="form-control form-control-sm time_input" readonly/>
									</div>
									<div class="input-group col-sm-5">
										<input id="check_out" name="check_out" type="text" placeholder="Jam Pulang" class="form-control form-control-sm time_input" readonly/>
										<div class="input-group-prepend">
											<a href="javascript:void(0);" class="input-group-text form-control-sm bClean">
												<i class="icon-bin text-danger"></i>
											</a>
										</div>
									</div>
								</div> -->
							</div>
						</div>
					</div>
				</div>
				<div class="card-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <button type="button" data-dismiss="modal" class="btn btn-outline-secondary">Batal</button>
                        <button type="submit" class="btn btn-primary btn-ladda btn-ladda-spinner bSbmit" data-spinner-color="#333" data-style="slide-down">Simpan</button>
                    </div>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- Modal Form Organization End -->

<div class="panel panel-blue">
	<div class="panel-heading">Master Shift
    	<?= $write; ?>
  	</div>
	<div class="alert alert-success" style="display: none;"></div>
	<div class="panel_body">
		<div class="table-responsive">
			<table id="example1" class="table table-striped table-bordered table-hover table-sm1">
				<thead>
				<tr>
					<th>#</th>
					<th>AKSI</th>
					<th>SETUP</th>
					<th>SHIFT</th>
					<th>MULAI LEMBUR</th>
					<th>SENIN</th>
					<th>SELASA</th>
					<th>RABU</th>
					<th>KAMIS</th>
					<th>JUM'AT</th>
					<th>SABTU</th>
					<th>MINGGU</th>
					<th>PIKET</th>
					<th>DESKRIPSI</th>
				</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
	</div>
</div>
<div id="insert_form"></div>
<script src="<?= base_url(); ?>assets/layout1/js/core/v_shifting.js?v=0.4" params='<?= $params; ?>'></script>