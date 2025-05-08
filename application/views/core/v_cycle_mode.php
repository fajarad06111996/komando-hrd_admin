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

    @media (max-width: 991px) {
        .text-right {
            text-align: left !important;
        }

        .form-group [class*="col-lg-"]:not([class*="col-form-label"]) + [class*="col-lg-"] {
            margin-top: unset !important;
        }
    }
    .fc-title {
        text-wrap: balance;
    }
</style>
<!-- Modal Form Organization -->
<div id="mJadwal" data-backdrop="static" data-keyboard="false" class="modal fade">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header pt-2 pb-2 bg-blue-700">
				<h4 class="modal-title"></h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
            <div class="modal-body">
                <div class="row justify-content-center">
                    <div class="col-sm-12">
                        <div class="container bg-white mb-3">
                            <div class="row">
                                <div class="col-lg-12">
                                    <h5 align="center" class="emp_name">KALENDER</h5>
                                    <div id="calendar2"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
		</div>
	</div>
</div>
<!-- Modal Form Organization End -->

<!-- Modal Cycle -->
<div id="mCycle" data-backdrop="static" data-keyboard="false" class="modal fade">
	<div class="modal-dialog modal-super">
		<div class="modal-content">
			<div class="modal-header pt-2 pb-2 bg-blue-700">
				<h4 class="modal-title"></h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<form action="" id="myCycle" class="form-horizontal form-validasi2" accept-charset="utf-8" enctype="multipart/form-data" method="post">
				<div class="modal-body">
					<div class="row justify-content-center">
						<div class="col-sm-6">
                            <div class="form-group row mb-1">
                                <?= csrf_input(); ?>
                                <input type="hidden" name="shift_idx" value="<?= $shift_idx; ?>">
                                <input type="hidden" name="work_days" value="<?= $work_days; ?>">
                                <input type="hidden" name="off_days" value="<?= $off_days; ?>">
                                <label class="col-form-label col-sm-3 text-uppercase text-right">Karyawan<sup><b class="text-danger">*</b></sup></label>
                                <div class="col-md-9">
                                    <select id="employee_id" name="employee_id" data-placeholder="Karyawan"
                                        class="form-control form-control-sm select-search"
                                        data-container-css-class="select-sm" data-fouc required>
                                        <?php foreach ($employee as $e) { ?>
                                        <option value="<?= $this->secure->enc($e->employee_id); ?>"><?= $e->employee_name; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row mb-1">
                                <label class="col-form-label col-sm-3 text-uppercase text-right">Model Siklus<sup><b class="text-danger">*</b></sup></label>
                                <div class="col-md-9">
                                    <select id="cycle_mode" name="cycle_mode" data-placeholder="Model Siklus"
                                        class="form-control form-control-sm select-search"
                                        data-container-css-class="select-sm" data-fouc required>
                                        <option value="0">Model 1</option>
                                        <option value="1">Model 2</option>
                                        <option value="2">Model 3</option>
                                        <option value="3">Model 4</option>
                                        <option value="4">Model 5</option>
                                        <option value="5">Model 6</option>
                                        <option value="6">Model 7</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row mb-1">
                                <label class="col-form-label col-sm-3 text-uppercase text-right">Jam Kerja<sup><b class="text-danger">*</b></sup></label>
                                <div class="col-sm-4">
                                    <input id="check_in" name="check_in" type="text" placeholder="Jam Masuk" class="form-control form-control-sm time_input" readonly required/>
                                </div>
                                <div class="input-group col-sm-5">
                                    <input id="check_out" name="check_out" type="text" placeholder="Jam Pulang" class="form-control form-control-sm time_input" readonly required/>
                                    <div class="input-group-prepend">
                                        <a href="javascript:void(0);" class="input-group-text form-control-sm bClean">
                                            <i class="icon-bin text-danger"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row mb-1">
                                <label class="col-form-label col-sm-3 text-right">PERIODE<sup><b class="text-danger">*</b></sup></label>
                                <div class="form-group col-md-4">
									<input name="from" type="date" class="form-control form-control-sm" required data-msg="Tanggal dimulai wajib di isi."/>
								</div>
								<div class="form-group col-md-4">
									<input name="to" type="date" class="form-control form-control-sm" required data-msg="Tanggal berakhir wajib di isi."/>
                                </div>
                            </div>
							<div class="form-group row mb-1">
								<label class="col-form-label col-lg-3 col-sm-12 text-uppercase text-right">Deskripsi</label>
								<div class="col-lg-8">
									<textarea rows="4" id="description" name="description" type="text" placeholder="Deskripsi" class="form-control form-control-sm"></textarea>
								</div>
							</div>
						</div>
                        <div class="col-sm-6">
                            <div class="container bg-white mb-3">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <h5 align="center">KALENDER</h5>
                                        <div id="calendar"></div>
                                    </div>
                                </div>
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
<!-- Modal Cycle End -->

<div class="panel panel-blue">
	<div class="header-elements-inline panel-heading"><?= $shift_name; ?>
        <div class="header-elements">
            <?= $write; ?>
        </div>
  	</div>
	<div class="alert alert-success" style="display: none;"></div>
	<div class="panel_body">
		<div class="table-responsive">
			<table id="example1" class="table table-striped table-bordered table-hover table-sm1">
				<thead>
					<tr>
						<th>#</th>
						<th class="text-center">Aksi</th>
						<th class="text-center">ID Karyawan</th>
						<th class="text-center">Nama Karyawan</th>
						<th class="text-center">Jabatan</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
	</div>
</div>
<div id="insert_form"></div>
<script src="<?= base_url(); ?>assets/layout1/js/core/v_cycle_mode.js?v=0.3" params='<?= $params; ?>'></script>