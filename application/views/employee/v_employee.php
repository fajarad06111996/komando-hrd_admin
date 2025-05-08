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
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header pt-2 pb-2 bg-blue-700">
				<h4 class="modal-title"></h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<form action="" id="myForm" class="form-horizontal form-validasi" accept-charset="utf-8" enctype="multipart/form-data" method="post">
				<div class="modal-body">
					<div class="row justify-content-center">
						<div class="col-sm-10">
                            <div class="form-group row mb-1">
								<label class="col-form-label col-sm-3 text-uppercase text-right">Organisasi</label>
								<div class="col-md-9">
                                    <select id="dept_idx" name="dept_idx" data-placeholder="- Pilih Organisasi -" class="form-control form-control-sm select-search"  data-container-css-class="select-sm" data-fouc>
                                        <option>- Pilih Organisasi -</option>
                                        <?php foreach ($department as $e) { ?>
                                            <option value="<?= $this->secure->enc($e->idx); ?>" ><?= $e->department_name; ?></option>
                                        <?php } ?>
                                    </select>
								</div>
							</div>
							<div class="form-group row mb-1">
								<label class="col-form-label col-sm-3 text-uppercase text-right">Nama Jabatan<sup><b class="text-danger">*</b></sup></label>
								<div class="col-md-9">
									<?= csrf_input(); ?>
									<input type="hidden" name="tId" />
									<input id="designation_name" name="designation_name" type="text" placeholder="Nama Jabatan" class="form-control form-control-sm" required/>
								</div>
							</div>
							<div class="form-group row mb-1">
								<label class="col-form-label col-sm-3 text-uppercase text-right">Deskripsi<sup><b class="text-danger">*</b></sup></label>
								<div class="col-md-9">
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
	<div class="panel-heading">Karyawan
    	<?= $write; ?>
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
					<th>PHOTO</th>
					<th>NAMA KARYAWAN</th>
					<th>KODE KARYAWAN</th>
					<th>ORGANISASI</th>
					<th>NO HP</th>
					<th>EMAIL</th>
					<th>JENIS KELAMIN</th>
				</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
	</div>
</div>
<div id="insert_form"></div>
<script src="<?= base_url(); ?>assets/layout1/js/employee/v_employee.js?v=0.3" params='<?= $params; ?>'></script>