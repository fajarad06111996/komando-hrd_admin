<style>
    .bg-sakit {
        background-color: #28a745;
    }

    .bg-izin-cuti {
        background-color: #fbf49d;
    }

    .bg-piket {
        background-color: #bd8edb;
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
</style>

<!-- untuk sinkronisasi data API mesin absensi online  -->
<div id="mSync" data-backdrop="static" data-keyboard="false" class="modal fade bd-example-modal-xl">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header pt-2 pb-2 bg-info">
                <h4 class="modal-title"></h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="" id="mySync" class="form-horizontal form-validasi2" method="post">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group row mb-1">
                                <label class="col-form-label col-sm-3">Mesin Absen<sup><b class="text-danger">*</b></sup></label>
                                <div class="form-group col-sm-5">
                                    <select name="tMesin" id="tMesin" data-placeholder="- Pilih Karyawan -" class="form-control form-control-xs select-search tMesin" data-container-css-class="select-xs" data-fouc required>
                                        <option value="<?= $mesin1; ?>">Mesin1</option>
                                        <option value="<?= $mesin2; ?>">Mesin2</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group row mb-1">
                                <label class="col-form-label col-sm-3">Periode<sup><b class="text-danger">*</b></sup></label>
                                <div class="form-group col-md-4">
                                    <input name="from" type="date" class="form-control form-control-sm" required data-msg="Tanggal dimulai wajib di isi." />
                                </div>
                                <div class="form-group col-md-4">
                                    <input name="to" type="date" class="form-control form-control-sm" required data-msg="Tanggal berakhir wajib di isi." />
                                </div>
                            </div>
                            <span class="text-danger"><sup>*</sup>Maximal periode 2 Hari.</span>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <button type="button" data-dismiss="modal" class="btn btn-outline-secondary">Close</button>
                        <button type="submit" id="sbmit" class="btn btn-primary btn-ladda btn-ladda-spinner" data-spinner-color="#333" data-style="slide-down">Syncron Data</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="mExcept" data-backdrop="static" data-keyboard="false" class="modal fade bd-example-modal-xl">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header pt-2 pb-2 bg-info">
                <h4 class="modal-title"></h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="" id="myExcept" class="form-horizontal form-validasi2" method="post">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm">
                            <h5 class="text-center" id="emp_name"></h5>
                            <h6 class="text-center text-danger"><sup>*</sup><span id="tglna"></span></h6>
                        </div>
                    </div>
                    <div class="form-group row mb-1">
                        <label class="col-form-label col-sm-3 text-right">Pengecualian</label>
                        <div class="col-md-9">
                            <?= csrf_input(); ?>
                            <div class="form-check form-check-inline">
                                <label class="form-check-label"> <!-- tBnaktif -->
                                    <input type="radio" value="1" class="form-check-input-styled" name="tStatus" id="xTrue">Ya
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <label class="form-check-label"> <!-- tBaktif -->
                                    <input type="radio" value="0" class="form-check-input-styled" name="tStatus" id="xFalse">Tidak
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <button type="button" data-dismiss="modal" class="btn btn-outline-secondary">Close</button>
                        <button type="submit" id="sbmit" class="btn btn-primary btn-ladda btn-ladda-spinner" data-spinner-color="#333" data-style="slide-down">Kecualikan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-blue">
            <div class="panel-heading">FORM REPORT KEHADIRAN
                <?= $write; ?>
            </div>
            <div class="alert alert-success" style="display: none;"></div>
            <form action="<?= $formact; ?>" class="form-validasi form-horizontal" method="post" id="myForm">
                <input type="hidden" value="<?= $formact ?>">
                <div class="panel_body pan">
                    <div class="form-body">
                        <div class="form-group row mb-1">
                            <label class="col-form-label col-sm-2 text-right">Dari<sup class="text-danger">*</sup></label>
                            <div class="col-md-2">
                                <?= csrf_input(); ?>
                                <input id="tStartdate" name="tStartdate" type="text" value="<?= set_value('tStartdate'); ?>" data-msg="Tanggal awal wajib diisi" placeholder="Start Date" class="form-control form-control-sm" required />
                            </div>
                        </div>
                        <div class="form-group row mb-1">
                            <label class="col-form-label col-sm-2 text-right">Sampai<sup class="text-danger">*</sup></label>
                            <div class="col-md-2">
                                <input id="tUntildate" name="tUntildate" type="text" value="<?= set_value('tUntildate'); ?>" data-msg="Tanggal akhir wajib diisi" placeholder="Until Date" class="form-control form-control-sm" required />
                            </div>
                        </div>
                        <!-- <div class="form-group row mb-1">
                            <label class="col-form-label col-sm-2 text-right">Status Kehadiran<sup class="text-danger">*</sup></label>
                            <div class="form-check col-sm-2">
                                <input class="form-check-input" type="checkbox" name="status_absen[]" value="0" <? //= $post==0?'checked':''; 
                                                                                                                ?> <? //= set_checkbox('status_absen[]', '0', true); 
                                                                                                                    ?>>
                                <label class="form-check-label badge badge-danger">Telat Masuk</label>
                            </div>
                            <div class="form-check col-sm-2">
                                <input class="form-check-input" type="checkbox" name="status_absen[]" value="1" <? //= $post==0?'checked':''; 
                                                                                                                ?> <? //= set_checkbox('status_absen[]', '1', true); 
                                                                                                                    ?>>
                                <label class="form-check-label badge badge-warning">Pulang Awal</label>
                            </div>
                            <div class="form-check col-sm-2">
                                <input class="form-check-input" type="checkbox" name="status_absen[]" value="2" <? //= $post==0?'checked':''; 
                                                                                                                ?> <? //= set_checkbox('status_absen[]', '2', true); 
                                                                                                                    ?>>
                                <label class="form-check-label badge badge-secondary">Tidak Hadir</label>
                            </div>
                        </div> -->
                        <div class="form-group row mb-1">
                            <label class="col-form-label col-sm-2 text-right">Karyawan</label>
                            <div class="col-md-5">
                                <select name="tEmployee" id="tEmployee" data-placeholder="- Pilih Karyawan -" class="form-control form-control-xs select-search js-data-example-ajax tEmployee" data-container-css-class="select-xs" data-fouc>
                                    <option></option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row mb-1">
                            <label class="col-form-label col-sm-2 text-right">Tipe Absensi</label>
                            <div class="col-md-5">
                                <select name="tTipeAbsen" data-placeholder="Tipe Absensi" class="form-control form-control-xs select-search" data-container-css-class="select-xs" data-fouc required>
                                    <option value="all">Semua</option>
                                    <option value="ma">Mesin Absen</option>
                                    <!-- <option value="2">Mesin 2</option> -->
                                    <option value="ol">Online</option>
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
                if (!empty($result)):
                ?>
                    <div class="panel_body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover table-sm1 customTables-button">
                                <thead>
                                    <tr>
                                        <th class="text-center font-weight-bold">#</th>
                                        <th class="text-center font-weight-bold" style="white-space: nowrap;">Nama Karyawan</th>
                                        <?php foreach (array_keys(current($result)) as $r): ?>
                                            <th class="text-center font-weight-bold" style="white-space: nowrap;"><?= date('d-F-Y', strtotime($r)); ?></th>
                                        <?php endforeach; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i  = 1;
                                    foreach ($result as $k => $v) {
                                    ?>
                                        <tr>
                                            <td class="text-center"><?= $i++; ?></td>
                                            <td class="text-left"><?= $k; ?></td>
                                            <?php foreach ($v as $absen):
                                                if ($absen[3] == 0) {
                                                    if ($absen[1] == 1) {
                                                        $coloring = "bg-sakit";
                                                    } elseif ($absen[1] == 2 || $absen[1] == 3) {
                                                        $coloring = "bg-izin-cuti";
                                                    } else {
                                                        $coloring = '';
                                                    }
                                                } else {
                                                    if ($absen[4] == 1) {
                                                        $coloring = "bg-telat1";
                                                    } elseif ($absen[4] == 2) {
                                                        $coloring = "bg-telat2";
                                                    } else {
                                                        $coloring = '';
                                                    }
                                                }
                                            ?>
                                                <td class="text-center kecualiin <?= $coloring; ?>" style="white-space: nowrap;" attid="<?= empty($absen[5]) ? '' : $this->secure->enc($absen[5]); ?>" empname="<?= $absen[6]; ?>" tgl="<?= $absen[7]; ?>" sexcept="<?= $absen[8]; ?>"><?= $absen[0]; ?></td>
                                            <?php endforeach; ?>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endif; ?>
                <?php if ($post == 1) { ?>
                    <?php if (empty($result)) { ?>
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
<script src="<?= base_url(); ?>assets/layout1/js/attendance/v_presence.js?v=0.4" params='<?= $params; ?>'></script>