<!--Modal Responsive-->
<div id="mForm" data-backdrop="static" data-keyboard="false" class="modal fade">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header pt-2 pb-2 bg-blue-700">
				<h4 class="modal-title"></h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<form action="" id="myForm" class="form-horizontal form-validasi" accept-charset="utf-8" enctype="multipart/form-data" method="post">
				<div class="modal-body">
					<div class="row">
						<div class="col-sm-12">
							<div class="form-group row mb-1">
								<label class="col-form-label col-sm-3 text-uppercase text-right">Bank Code<sup><b class="text-danger">*</b></sup></label>
								<div class="col-md-4">
									<input type="hidden" id="clear_quote_first" name="clear_quote_first" value="0">
									<input id="bank_code" name="bank_code" type="text" placeholder="Bank Code" class="form-control form-control-sm" required/>
								</div>
								<div class="col-md-5">
									<span id="response_result" class="mt-1"></span>
								</div>
							</div>
							<div class="form-group row mb-1">
								<label class="col-form-label col-sm-3 text-uppercase text-right">Bank Name<sup><b class="text-danger">*</b></sup></label>
								<div class="col-md-9">
									<?= csrf_input(); ?>
									<input type="hidden" name="tId" />
									<input id="bank_name" name="bank_name" type="text" placeholder="Bank Name" class="form-control form-control-sm" required/>
								</div>
							</div>
							<div class="form-group row mb-1">
								<label class="col-form-label col-sm-3 text-uppercase text-right">Remark</label>
								<div class="col-md-9">
									<textarea rows="4" id="remark" name="remark" type="text" placeholder="Remark" class="form-control form-control-sm" required></textarea>
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
                        <button type="button" data-dismiss="modal" class="btn btn-outline-secondary">Close</button>
                        <button type="submit" class="btn btn-primary btn-ladda btn-ladda-spinner bSbmit" data-spinner-color="#333" data-style="slide-down">Save changes</button>
                        <!-- <button type="submit" id="bSubmit" style="display: none;"></button> -->
                    </div>
				</div>
			</form>
		</div>
	</div>
</div>
<div class="panel panel-blue">
	<div class="panel-heading">List Daftar Bank
	<?= $write; ?>
  </div>
	<div class="panel_body">
		<div class="table-responsive">
			<table id="example2" class="table table-striped table-bordered table-hover table-sm1">
				<thead>
					<tr>
                        <th class="text-center">#</th>
                        <th class="text-center">Actions</th>
                        <th>Status</th>
                        <th>Bank Code</th>
                        <th>Bank Name</th>
                        <th>Remark</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
	</div>
</div>
<!--loading bootstrap js-->
<script src="https://www.gstatic.com/firebasejs/live/3.0/firebase.js"></script>

<!-- load file js -->
<script src="<?= base_url(); ?>assets/layout1/js/pengaturan/v_bank.js?v=0.1" params='<?= $params; ?>'></script>