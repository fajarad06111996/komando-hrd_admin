<style>
    .tooltip-inner {
        white-space: pre-line;
    }
</style>
<div id="mConfirmOt" data-backdrop="static" data-keyboard="false" class="modal fade bd-example-modal-xl">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header pt-2 pb-2 bg-info">
				<h4 class="modal-title"></h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<form action="" id="myConfirmOt" class="form-horizontal form-validasi2" method="post">
				<div class="modal-body">
                    <div class="row">
                        <div class="col-sm">
                            <div class="form-group row mb-1">
                                <?= csrf_input(); ?>
                                <input type="hidden" name="att_idx">
                                <input type="hidden" name="ot_hour">
                                <input type="hidden" name="ch_out_date">
                                <input type="hidden" name="ch_out_old">
                                <input type="hidden" name="ch_shift">
                                <input type="hidden" name="emp_id">
                                <input type="hidden" name="ot_id">
                                <input type="hidden" name="piket">
                                <label class="col-form-label col-sm-4 text-uppercase text-right">Jam Pulang</sup></label>
                                <div class="input-group col-sm-6">
                                    <input id="check_out" name="check_out" type="text" placeholder="Jam Pulang" class="form-control form-control-sm time_input" readonly/>
                                    <div class="input-group-prepend">
                                        <a href="javascript:void(0);" class="input-group-text form-control-sm bClean">
                                            <i class="icon-bin text-danger"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row mb-1">
								<label class="col-form-label col-sm-4 text-uppercase text-right">Alasan Lembur<sup><b class="text-danger">*</b></sup></label>
								<div class="col-md-8">
									<textarea rows="2" id="overtime_reason" name="overtime_reason" type="text" placeholder="Alasan Lembur" class="form-control form-control-sm" required></textarea>
								</div>
							</div>
                            <div class="form-group row mb-1">
								<span class='text-danger text-center col-md-12'><b class='warning-ot'></b></span>
							</div>
                        </div>
                    </div>
				</div>
				<div class="card-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <button type="button" data-dismiss="modal" class="btn btn-outline-secondary">Close</button>
                        <button type="submit" id="sbmit" class="btn btn-primary btn-ladda btn-ladda-spinner" data-spinner-color="#333" data-style="slide-down">Konfirmasi</button>
                    </div>
				</div>
			</form>
		</div>
	</div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-blue">
            <div class="panel-heading">FORM REPORT LEMBUR
                <?= $write; ?>
            </div>
            <div class="alert alert-success" style="display: none;"></div>
            <form action="<?= $formact; ?>" class="form-validasi form-horizontal"  method="post" id="myForm">
                <div class="panel_body pan">
                    <div class="form-body">
                        <div class="form-group row mb-1">
                            <label class="col-form-label col-sm-2 text-right">Dari<sup class="text-danger">*</sup></label>
                            <div class="col-md-2">
                                <?= csrf_input(); ?>
                                <input id="tStartdate" name="tStartdate" type="text" value="<?= set_value('tStartdate'); ?>"  placeholder="Start Date" class="form-control form-control-sm" required/>
                            </div>
                        </div>
                        <div class="form-group row mb-1">
                            <label class="col-form-label col-sm-2 text-right">Sampai<sup class="text-danger">*</sup></label>
                            <div class="col-md-2">
                                <input id="tUntildate" name="tUntildate" type="text" value="<?= set_value('tUntildate'); ?>" placeholder="Until Date" class="form-control form-control-sm" required/>
                            </div>
                        </div>
                        <div class="form-group row mb-1">
                            <label class="col-form-label col-sm-2 text-right">Karyawan</label>
                            <div class="col-md-5">
                                <select name="tEmployee" id="tEmployee" data-placeholder="- Pilih Karyawan -" class="form-control form-control-xs select-search js-data-example-ajax tEmployee"  data-container-css-class="select-xs" data-fouc>
                                    <option></option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer p-1">
                    <div class="d-flex justify-content-between align-items-center">
                        <div></div>
                        <button type="submit" class="btn bg-blue ml-3 btn-ladda btn-ladda-spinner" data-spinner-color="#333" data-style="slide-down"> <i class="icon-floppy-disk"></i> Proses</button>
                    </div>
                </div>
                <?php 
                    if(!empty($result)):
                ?>
                <div class="panel_body">
                    <div class="table-responsive">
                        <table id='ot_table' class="table table-striped table-bordered table-hover table-sm1 customTables-button">
                            <thead>
                                <tr>
                                    <th style="text-align: center;width: 20px;">#</th>
                                    <th>Nama Karyawan</th>
                                    <!-- <th class="text-center font-weight-bold">#</th>
                                    <th class="text-center font-weight-bold" style="white-space: nowrap;">Nama Karyawan</th> -->
                                    <?php foreach(array_keys(current($result)) as $r): ?>
                                        <th class="text-center font-weight-bold" style="white-space: nowrap;"><?= date('d-F-Y', strtotime($r)); ?></th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $i  = 1;
                                    foreach($result as $k => $v){
                                ?>
                                <tr>
                                    <td class="text-center"><?= $i++; ?></td>
                                    <td class="text-left"><?= $k; ?></td>
                                    <?php foreach($v as $absen): ?>
                                        <td class="text-center" style="white-space: nowrap;"><?= $absen[0]; ?></td>
                                    <?php endforeach; ?>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php endif; ?>
                <?php if($post == 1){ ?>
                <?php if(empty($result)){ ?>
                <div class="panel_body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover table-sm1 customTables-button">
                            <thead>
                                <tr>
                                    <th style="text-align: center;width: 20px;">#</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center alert alert-danger">No Data</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php } ?>
                <?php } ?>
            </form>
        </div>
    </div>
</div>
<script src="<?= base_url(); ?>assets/layout1/js/attendance/v_ot.js?v=0.2" params='<?= $params; ?>'></script>