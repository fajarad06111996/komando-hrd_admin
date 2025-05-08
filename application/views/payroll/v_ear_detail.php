<!--Modal choose econnote-->
<div class="row">
    <div class="col-lg-4 col-md-12">
        <div class="card panel panel-green">
            <div class="header-elements-inline panel-heading">
                Header Data Gaji Bulan <?= bulan(date('M', strtotime($from_date))).'/'.date('Y', strtotime($from_date)); ?>
            </div>
            <div class="panel_body pan">
                <form action="<?= $formact; ?>" id="myForm" class="form-horizontal form-validasi" accept-charset="utf-8" enctype="multipart/form-data" method="post">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group row mb-1">
                                    <label class="col-form-label col-xs-3 col-sm-12">KODE<sup><b class="text-danger">*</b></sup></label>
                                    <div class="col-xs-9 col-sm-12">
                                        <?= csrf_input(); ?>
                                        <input id="allowhD_id" name="allowhD_id" type="hidden" value="<?= $allowh_id; ?>"/>
                                        <input id="allowance_code" name="allowance_code" type="text" value="" class="form-control form-control-sm bg-brown-700" readonly/>
                                    </div>
                                </div>
                                <div class="form-group row mb-1">
                                    <label class="col-form-label col-xs-3 col-sm-12">Periode<sup><b class="text-danger">*</b></sup></label>
                                    <div class="form-group col-xs-4 col-sm-6">
                                        <input name="from" type="date" class="form-control form-control-sm" value="" required data-msg="Tanggal dimulai wajib di isi."/>
                                    </div>
                                    <div class="form-group col-xs-4 col-sm-6">
                                        <input name="to" type="date" class="form-control form-control-sm" value="" required data-msg="Tanggal berakhir wajib di isi."/>
                                    </div>
                                </div>
                                <div class="form-group row mb-1">
                                    <label class="col-form-label col-xs-3 col-sm-12">Deskripsi<sup><b class="text-danger">*</b></sup></label>
                                    <div class="form-group col-xs-9 col-sm-12">
                                        <textarea name="description" type="text" rows="3" class="form-control form-control-sm" placeholder="Description" data-msg="Deskripsi wajib di isi." required></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-content-end align-items-center">
                            <button type="submit" class="btn btn-primary btn-ladda btn-ladda-spinner" data-spinner-color="#333" data-style="slide-down">Save changes</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-8 col-md-12" style="font-size:10px;">
        <div class="card panel panel-green" style="font-size:10px;">
            <div class="header-elements-inline panel-heading">
                Detail Data Gaji Bulan <?= bulan(date('M', strtotime($from_date))).'/'.date('Y', strtotime($from_date)); ?>
                <div class="header-elements">
                    <div class="list-icons">
                        <?= $write; ?>
                    </div>
                </div>
            </div>
            <div class="panel_body pan">
                <div class="table-responsive">
                    <table id="example2" class="table table-striped table-bordered table-hover table-sm1">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Nama Karyawan</th>
                                <th class="text-center">Kode Karyawan</th>
                                <th class="text-center">Gaji Pokok</th>
                                <th class="text-center">Tunjangan Jabatan</th>
                                <th class="text-center">Potongan Bpjs</th>
                                <th class="text-center">Total</th>
                                <th class="text-center">Deskripsi</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END Full Table -->
<script src="<?= base_url(); ?>assets/layout1/js/payroll/v_ear_detail.js?v=1.0" params='<?= $params; ?>'></script>