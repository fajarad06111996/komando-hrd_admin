<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.css" integrity="sha512-bs9fAcCAeaDfA4A+NiShWR886eClUcBtqhipoY5DM60Y1V3BbVQlabthUBal5bq8Z8nnxxiyb1wfGX2n76N1Mw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<style>
    /* .fc, .fc-day-number {
        font-size: 12px !important;
    } */
    #calendar {
        max-width: 850px;
        margin: 40px auto;
    }
    .fc-sun .fc-day-number{
        color: red;
    }
    .fc-title {
        text-wrap: balance;
    }
    .fc-day-grid-event {
        margin: 0.5rem !important;
        padding: 0.25rem 0.25rem !important;
    }
    .fc-event {
        font-size: .65rem !important;
    }
    .swal2-backdrop-show {
        z-index: 9999 !important;
    }
    .bg-upload {
        background-color: #760000b3;
    }
</style>
<div class="container bg-white">
	<div class="row">
		<div class="col-lg-12">
			<h5 align="center">PENGAJUAN</h5>
			<div id="calendar"></div>
			<!-- <div id="calendarx" class="fullcalendar-rtl"></div> -->
			<!-- <div id="external-events"></div>
			<div id="drop-remove"></div> -->
		</div>
	</div>
</div>
<!-- Modal Crop Image -->
<div class="modal fade" id="modalCropper" data-backdrop="static" data-keyboard="false" style="z-index: 9999 !important;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Crop the image</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="img-container">
                    <img id="imageCropper" src="<?= base_url(); ?>assets/images/ICON/no_image.png" style="max-width: 100% !important;">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="crop">Crop</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal Crop Image End -->
<!-- Start popup dialog box -->
<div class="modal fade" id="header_sub_modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true" data-backdrop="static">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modalLabel">Tambah Pengajuan</h5>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
            <form action="" id="subHForm" class="form-horizontal form-validasi" accept-charset="utf-8" enctype="multipart/form-data" method="post">
                <div class="modal-body">
                    <div class="img-container">
                        <div class="row">
                            <div class="col-sm-12">  
                                <div class="form-group">
                                    <label for="sub_date">Tanggal Pengajuan<sup><b class="text-danger">*</b></sup></label>
                                    <input type="date" name="sub_date" id="sub_date" class="form-control onlydatepicker" placeholder="Event date" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">  
                                <div class="form-group">
                                    <label for="event_name">Description<sup><b class="text-danger">*</b></sup></label>
                                    <textarea rows="4" id="description" name="description" type="text" placeholder="Deskripsi" class="form-control form-control-sm" required></textarea>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="row">
                            <div class="col-sm-6">  
                                <div class="form-group">
                                <label for="event_start_date">Event start<sup><b class="text-danger">*</b></sup></label>
                                <input type="datetime-local" name="event_start_date" id="event_start_date" class="form-control onlydatepicker" placeholder="Event start date" required>
                                </div>
                            </div>
                            <div class="col-sm-6">  
                                <div class="form-group">
                                <label for="event_end_date">Event end<sup><b class="text-danger">*</b></sup></label>
                                <input type="datetime-local" name="event_end_date" id="event_end_date" class="form-control" placeholder="Event end date" required>
                                </div>
                            </div>
                        </div> -->
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <button type="button" data-dismiss="modal" class="btn btn-outline-secondary">Batal</button>
                        <button type="submit" class="btn btn-primary btn-ladda btn-ladda-spinner bSbmit" data-spinner-color="#333" data-style="slide-down">Simpan</button>
                    </div>
                    <!-- <button type="button" class="btn btn-primary" onclick="save_event()">Save Event</button> -->
                </div>
            </form>
		</div>
	</div>
</div>
<!-- Modal Table Detail -->
<div class="modal fade" id="detail_sub_modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true" data-backdrop="static">
	<div class="modal-dialog modal-super" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modalLabel">Detail Pengajuan</h5>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
            <div class="modal-body">
                <!-- Detail Goods -->
                <div class="panel panel-blue">
                    <div class="panel-heading">Detail Pengajuan <span class="tSUB"></span>
                        <div class="pull-right block-options">
                            <a href="javascript:void(0);" id="btnAdd" subid="" subdate="" class="pull-right text-white small" title="Tambah Pengajuan" data-placement="right" data-popup="tooltip"><i class="icon-plus-circle2"></i></a>
                            <div class="block-options-item">
                                <span class="badge badge-success tTotal"></span>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table id="tbSUB" class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th style="width: 50px;">No</th>
                                        <th>Nama Karyawan</th>
                                        <th>Status</th>
                                        <th>Reimbursement</th>
                                        <th>Deskripsi</th>
                                        <th>Bukti Pengajuan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- END Detail Goods -->
            </div>
            <div class="modal-footer">
                <div class="d-flex justify-content-between align-items-center">
                    <button type="button" data-dismiss="modal" class="btn btn-outline-secondary">Batal</button>&nbsp;&nbsp;&nbsp;
                    <button type="submit" class="btn btn-primary btn-ladda btn-ladda-spinner bSbmit" data-spinner-color="#333" data-style="slide-down">Konfirmasi Semua</button>
                </div>
                <!-- <button type="button" class="btn btn-primary" onclick="save_event()">Save Event</button> -->
            </div>
		</div>
	</div>
</div>
<!-- Modal Table Detail End -->
<!-- Modal Table Add Insentif -->
<div id="add_detail_sub_modal" data-backdrop="static" data-keyboard="false" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header pt-2 pb-2 bg-blue-700">
                <h4 class="modal-title"></h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="" id="subForm" class="form-horizontal form-validasi2" accept-charset="utf-8" enctype="multipart/form-data" method="post">
                <div class="modal-body">
                    <div class="row justify-content-center">
                        <div class="col-sm-6">  
                            <div class="form-group row mb-1">
                                <?= csrf_input(); ?>
                                <input type="hidden" name="sub_idx" value="">
                                <input type="hidden" name="sub_date" value="">
                                <label class="col-form-label col-sm-4 text-right">Karyawan<sup><b class="text-danger">*</b></sup></label>
                                <div class="col-md-8">
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
                                <label class="col-form-label col-sm-4 text-right">Tipe<sup><b class="text-danger">*</b></sup></label>
                                <div class="col-md-8">
                                    <select id="sub_type" name="sub_type" data-placeholder="Karyawan"
                                        class="form-control form-control-sm select-search"
                                        data-container-css-class="select-sm" data-fouc required>
                                        <?php foreach ($subType as $s) { ?>
                                        <option value="<?= $this->secure->enc($s->status); ?>"><?= $s->status_name; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row mb-1">
                                <label class="col-form-label col-sm-4 text-right">Reimbursement</label>
                                <div class="form-group col-md-8">
									<input name="value" type="text" class="form-control form-control-sm moneyx" placeholder="0"/>
								</div>
                            </div>
                            <div class="form-group row mb-1">
                                <label class="col-form-label col-lg-4 col-sm-12 text-right">Deskripsi<sup><b class="text-danger">*</b></sup></label>
								<div class="col-lg-8">
									<textarea rows="4" id="description" name="description" type="text" placeholder="Deskripsi" class="form-control form-control-sm"></textarea>
								</div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <h5 class=""><b>Unggah Bukti</b></h5>
                            <div class="form-group row justify-content-center mb-1">
                                <div class="col-sm-8 text-left p-3">
                                    <input type="file" id="cmd_browse" name="cmd_browse" accept="image/*" style="display:none;">
                                    <input type="hidden" id="url_image" name="url_image" value="">
                                    <input type="hidden" id="temp_image" name="temp_image" value="0">
                                    <img src="<?= base_url('assets/images/no_image.png'); ?>" id="img_photo" class="rounded" style="width: 100%;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <button type="button" data-dismiss="modal" class="btn btn-outline-secondary">Batal</button>&nbsp;&nbsp;&nbsp;
                        <button type="submit" subid="" class="btn btn-primary btn-ladda btn-ladda-spinner bSbmit" data-spinner-color="#333" data-style="slide-down">Simpan</button>
                    </div>
                    <!-- <button type="button" class="btn btn-primary" onclick="save_event()">Save Event</button> -->
                </div>
            </form>
		</div>
	</div>
</div>
<!-- Modal Table Add Insentif -->
<!-- End popup dialog box -->
<script src='https://cdn.jsdelivr.net/npm/tesseract.js@5/dist/tesseract.min.js'></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.js" integrity="sha512-Zt7blzhYHCLHjU0c+e4ldn5kGAbwLKTSOTERgqSNyTB50wWSI21z0q6bn/dEIuqf6HiFzKJ6cfj2osRhklb4Og==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="<?= base_url(); ?>assets/layout1/js/firebase.js"></script>
<script src="<?= base_url(); ?>assets/layout1/js/attendance/v_sub.js?v=0.2" params='<?= $params; ?>'></script>