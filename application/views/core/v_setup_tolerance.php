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
</style>
<!-- Modal Form Organization -->
<div id="mForm" data-backdrop="static" data-keyboard="false" class="modal fade">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header pt-2 pb-2 bg-blue-700">
				<h4 class="modal-title"></h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<form action="" id="myForm" class="form-horizontal form-validasi5" accept-charset="utf-8" enctype="multipart/form-data" method="post">
				<div class="modal-body">
					<div class="row justify-content-center">
						<div class="col-sm-10">
                            <div class="row mb-1">
                                <?= csrf_input(); ?>
                                <input type="hidden" name="shift_idx" value="<?= $shift_idx; ?>">
                                <label class="col-form-label col-lg-3 col-sm-12 text-uppercase text-right">Toleransi Masuk<sup><b class="text-danger">*</b></sup></label>
                                <div class="form-group input-group col-lg-4 col-sm-6">
                                    <input name="in_start" type="number" class="form-control form-control-sm" placeholder="Awal" required/>
                                    <div class="input-group-append">
                                        <span class="input-group-text form-control form-control-sm">Menit</span>
                                    </div>
                                </div>
                                <div class="form-group input-group col-lg-4 col-sm-6">
                                    <input name="in_end" type="number" class="form-control form-control-sm" placeholder="Akhir" required/>
                                    <div class="input-group-append">
                                        <span class="input-group-text form-control form-control-sm">Menit</span>
                                    </div>
								</div>
                            </div>
                            <div class="row mb-1">
                                <label class="col-form-label col-lg-3 col-sm-12 text-uppercase text-right">Toleransi Pulang<sup><b class="text-danger">*</b></sup></label>
                                <div class="form-group input-group col-lg-4 col-sm-6">
                                    <input name="out_start" type="number" class="form-control form-control-sm" placeholder="Awal" required/>
                                    <div class="input-group-append">
                                        <span class="input-group-text form-control form-control-sm">Menit</span>
                                    </div>
                                </div>
                                <div class="form-group input-group col-lg-4 col-sm-6">
                                    <input name="out_end" type="number" class="form-control form-control-sm" placeholder="Akhir" required/>
                                    <div class="input-group-append">
                                        <span class="input-group-text form-control form-control-sm">Menit</span>
                                    </div>
								</div>
                            </div>
							<div class="form-group row mb-1">
								<label class="col-form-label col-lg-3 col-sm-12 text-uppercase text-right">Deskripsi<sup><b class="text-danger">*</b></sup></label>
								<div class="col-lg-8">
									<textarea rows="4" id="description" name="description" type="text" placeholder="Deskripsi" class="form-control form-control-sm" required></textarea>
								</div>
							</div>
							<div class="form-group row mb-1">
								<label class="col-form-label col-sm-3 text-right">STATUS</label>
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
	<div class="header-elements-inline panel-heading">Toleransi Shift <?= $shift_name; ?>
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
						<th>Aksi</th>
						<th>STATUS</th>
						<th>TOLERANSI MASUK</th>
						<th>TOLERANSI PULANG</th>
						<th class="text-center">DESKRIPSI</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
	</div>
</div>
<div id="insert_form"></div>
<script src="<?= base_url(); ?>assets/layout1/js/core/v_setupTolerance.js?v=0.2" params='<?= $params; ?>'></script>