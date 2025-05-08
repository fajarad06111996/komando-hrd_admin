<!--Modal Responsive-->
<?php
	//var_dump('<pre>');
	// var_dump($listClient);
	// die;
?>
<div id="mForm" data-backdrop="static" data-keyboard="false" class="modal fade bd-example-modal-xl">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header pt-2 pb-2 bg-info">
				<h4 class="modal-title"></h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<form action="" id="myForm" class="form-horizontal form-validasi" method="post">
				<div class="modal-body">
                    <div class="row">
                        <div class="col-sm">
							<div class="form-group row mb-1">
                                <label class="col-form-label col-sm-3">Periode<sup><b class="text-danger">*</b></sup></label>
                                <div class="form-group col-md-4">
									<input name="from" type="date" class="form-control form-control-sm" required data-msg="Tanggal dimulai wajib di isi."/>
								</div>
								<div class="form-group col-md-4">
									<input name="to" type="date" class="form-control form-control-sm" required data-msg="Tanggal berakhir wajib di isi."/>
                                </div>
                            </div>
							<!-- <div class="form-group row mb-1">
								<label class="col-form-label col-sm-3">Rapel<sup><b class="text-danger">*</b></sup></label>
								<div class="col-md-8">
									<div class="form-check form-check-inline">
										<label class="form-check-label">
											<input type="radio" value="0" class="form-check-input-styled" checked="" name="rapel" id="xFalse">Exclude
										</label>
									</div>
									<div class="form-check form-check-inline">
										<label class="form-check-label">
											<input type="radio" value="1" class="form-check-input-styled" checked="" name="rapel" id="xTrue">Include
										</label>
									</div>
								</div>
							</div> -->
							<div class="form-group row mb-1">
								<label class="col-form-label col-sm-3">Deskripsi<sup><b class="text-danger">*</b></sup></label>
								<div class="form-group col-md-8">
									<textarea name="description" type="text" rows="3" class="form-control form-control-sm" placeholder="Description" data-msg="Deskripsi wajib di isi." required></textarea>
								</div>
							</div>
                        </div>
                    </div>
				</div>
				<div class="card-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <button type="button" data-dismiss="modal" class="btn btn-outline-secondary">Close</button>
                        <button type="submit" id="sbmit" class="btn btn-primary btn-ladda btn-ladda-spinner" data-spinner-color="#333" data-style="slide-down">Generate Umt</button>
                    </div>
				</div>
			</form>
		</div>
	</div>
</div>
<div class="panel panel-green">
	<div class="panel-heading">List Data UMT
	<?= $write; ?>
  </div>
	<div class="panel_body">
		<div class="table-responsive">
			<table id="example2" class="table table-striped table-bordered table-hover table-sm1">
				<thead>
					<tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Action</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Kode UMT</th>
                        <th class="text-center">Periode</th>
                        <th class="text-center">Jumlah Karyawan</th>
                        <th class="text-center">Total Umt</th>
						<th class="text-center">Deskripsi</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
	</div>
</div>
<!--loading bootstrap js-->
<script src="<?= base_url(); ?>assets/layout1/js/payroll/v_umt.js?v=0.3" params='<?= $params; ?>'></script>