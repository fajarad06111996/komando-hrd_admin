<style>
    .wraping-text {
        text-wrap: nowrap !important;
    }
    .bg-sakit {
        background-color: #28a745;
    }
    .bg-izin-cuti {
        background-color: #fbf49d;
    }
    .bg-piket {
        background-color: #bd8edb;
    }
    .bg-cekin {
        background-color: #a5a5a5;
    }
    .bg-telat1 {
        background-color: #c7a644;
    }
    .bg-telat2 {
        background-color: #007bff;
    }
    .bg-lembur {
        background-color: #62c9d9;
    }
    .bg-libur {
        background-color: #dc354575;
    }
</style>

<!--Modal choose econnote-->
<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="card panel panel-green">
            <div class="header-elements-inline panel-heading">
                Data UMT Periode Header <?= date('d-F-Y', strtotime($from_date)); ?> sampai <?= date('d-F-Y', strtotime($to_date)); ?>
            </div>
            <div class="panel_body pan">
                <form action="<?= $formact; ?>" id="myForm" class="form-horizontal form-validasi" accept-charset="utf-8" enctype="multipart/form-data" method="post">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group row mb-1">
                                    <label class="col-form-label col-xs-3 col-sm-12">UMT CODE<sup><b class="text-danger">*</b></sup></label>
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
                                <!-- <div class="form-group row mb-1">
                                    <label class="col-form-label col-xs-3 col-sm-12">Rapel<sup><b class="text-danger">*</b></sup></label>
                                    <div class="form-group col-xs-4 col-sm-6">
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
                                    <label class="col-form-label col-xs-3 col-sm-12">Deskripsi<sup><b class="text-danger">*</b></sup></label>
                                    <div class="form-group col-xs-9 col-sm-12">
                                        <textarea name="description" type="text" rows="3" class="form-control form-control-sm" placeholder="Description" data-msg="Deskripsi wajib di isi." required></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="d-flex justify-content-end align-items-center">
                                    <button type="submit" class="btn btn-primary btn-ladda btn-ladda-spinner" data-spinner-color="#333" data-style="slide-down">Save changes</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12" style="font-size:10px;">
        <div class="card panel panel-green" style="font-size:10px;">
            <div class="header-elements-inline panel-heading">
                Data UMT Periode Detail <?= date('d-F-Y', strtotime($from_date)); ?> sampai <?= date('d-F-Y', strtotime($to_date)); ?>
                <div class="header-elements">
                    <div class="list-icons">
                        <?= $write; ?>
                    </div>
                </div>
            </div>
            <div class="panel_body pan">
                <div class="table-responsive">
                    <table id="umtx" class="table table-striped">
                        <thead>
                            <tr>
                                <th rowspan="2" style="vertical-align: middle;text-wrap: nowrap;">#</th>
                                <th rowspan="2" style="vertical-align: middle;text-wrap: nowrap;">Nama Karyawan</th>
                                <?php 
                                    $startDate = new DateTime($from_date);
                                    $endDate = new DateTime($to_date);
                                    $endDate = $endDate->modify('+1 day'); // Modify end date to include the last day
                                    
                                    $interval = new DateInterval('P1D'); // 1-day interval
                                    $dateRange = new DatePeriod($startDate, $interval, $endDate);
                                    foreach($dateRange as $date){
                                ?>
                                <th style="text-wrap: nowrap;"><?= $date->format("d-M-Y"); ?></th>
                                <?php } ?>
                                <th rowspan="2" style="vertical-align: middle;text-wrap: nowrap;">Jumlah Kehadiran</th>
                                <th rowspan="2" style="vertical-align: middle;text-wrap: nowrap;">Nominal UMUT</th>
                                <th rowspan="2" style="vertical-align: middle;text-wrap: nowrap;">Lembur</th>
                                <th rowspan="2" style="vertical-align: middle;text-wrap: nowrap;">Total Insentif</th>
                                <th rowspan="2" style="vertical-align: middle;text-wrap: nowrap;">DLK</th>
                                <th rowspan="2" style="vertical-align: middle;text-wrap: nowrap;">Rapel</th>
                                <th rowspan="2" style="vertical-align: middle;text-wrap: nowrap;">Piket</th>
                                <th rowspan="2" style="vertical-align: middle;text-wrap: nowrap;">Potongan Telat</th>
                                <th rowspan="2" style="vertical-align: middle;text-wrap: nowrap;">Sub Total</th>
                            </tr>
                            <tr>
                                <?php 
                                    $startDate = new DateTime($from_date);
                                    $endDate = new DateTime($to_date);
                                    $endDate = $endDate->modify('+1 day'); // Modify end date to include the last day
                                    
                                    $interval = new DateInterval('P1D'); // 1-day interval
                                    $dateRange = new DatePeriod($startDate, $interval, $endDate);
                                    foreach($dateRange as $date){
                                        if(strtolower($date->format("l"))=='monday'){
                                            $dayx = 'S';
                                        }elseif(strtolower($date->format("l"))=='tuesday'){
                                            $dayx = 'S';
                                        }elseif(strtolower($date->format("l"))=='wednesday'){
                                            $dayx = 'R';
                                        }elseif(strtolower($date->format("l"))=='thursday'){
                                            $dayx = 'K';
                                        }elseif(strtolower($date->format("l"))=='friday'){
                                            $dayx = 'J';
                                        }elseif(strtolower($date->format("l"))=='saturday'){
                                            $dayx = 'S';
                                        }elseif(strtolower($date->format("l"))=='sunday'){
                                            $dayx = 'M';
                                        }else{
                                            $dayx = 'X';
                                        }
                                ?>
                                <th style="text-wrap: nowrap;" class="text-center"><?= $dayx; ?></th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <!-- <table id="example2" class="table table-striped table-bordered table-hover table-sm1"> -->
            </div>
        </div>
    </div>
</div>
<!-- END Full Table -->
<script src="<?= base_url(); ?>assets/layout1/js/payroll/v_umt_detail.js?v=0.2" params='<?= $params; ?>'></script>