<!--Modal crop image-->
<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true" data-backdrop="static">
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
                    <input type="hidden" name="initialData">
                    <img id="image_cropper" src="<?= base_url(); ?>assets/images/ICON/no_image.png" style="max-width: 100% !important;">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="crop">Crop</button>
            </div>
        </div>
    </div>
</div>
<!--End Modal crop image-->
<div class="row">
    <div class="col-md-12">
        <div class="alert alert-danger alert-dismissible mb-1 p-2 notifValidasi text-danger" style="display:none">
            <button type="button" class="close aClose p-2 text-white"><span>&times;</span></button>
            <span class="font-weight-semibold text-white">Informasi!</span>
            <div class="print-error-msg text-white"><p class="mb-0 pb-0">The No Awb field is required.</p><p>The No Awb field is required.</p></div>
        </div>
        <div class="card panel panel-blue">
            <div class="header-elements-inline panel-heading">
                <?= $title; ?>
                <div class="header-elements">
                    <div class="list-icons">
                        <a href="<?=$link;?>" class="list-icons-item" title="Back To List Data" data-placement="right" data-popup="tooltip">
                            <i class="icon-undo2 pr-1" style="font-size:0.8em;padding-top: 4px;"></i>
                        </a>
                        <a class="list-icons-item" data-action="reload" title="Refresh" data-placement="right" data-popup="tooltip"></a>
                        <a class="list-icons-item" data-action="fullscreen" title="Fullscreen" data-placement="right" data-popup="tooltip"></a>
                    </div>
                </div>
            </div>
            <div class="alert alert-success" style="display: none;"></div>
            <form action="<?= $formact; ?>" id="myForm" class="form-horizontal form-validasi" accept-charset="utf-8" enctype="multipart/form-data" method="post">
            <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group row mb-1">
                                <label class="col-form-label col-sm-3">Kode Jabatan<sup><b class="text-danger">*</b></sup></label>
                                <div class="col-sm-2 accNo1">
                                    <input id="accNo1" name="accNo1" type="text" value="<?= !empty($acc) ?$acc['organization_number_parent']:$kode_jabatan; ?>" placeholder="Nomor ke 1" class="form-control form-control-sm" readonly required/>
                                </div>
                                <div class="col-sm-1 accNo2" style="display:none">
                                    <input id="accNo2" name="accNo2" type="text" value="<?= !empty($acc) ?$acc['organization_number_child']:''; ?>" placeholder="ekor" class="form-control form-control-sm"/>
                                </div>
                            </div>
                            <div class="form-group row mb-1">
                                <label class="col-form-label col-sm-3">Nama Jabatan<sup><b class="text-danger">*</b></sup></label>
                                <div class="col-md-9">
                                    <?= csrf_input(); ?>
                                    <input type="hidden" name="parent_idx" value="<?= !empty($acc) ?$this->secure->enc($acc['parent_idx']):''; ?>">
                                    <input id="organization_name" name="organization_name" type="text" value="<?= !empty($acc) ?$acc['organization_name']:''; ?>" placeholder="Nama Jabatan" class="form-control form-control-sm text-uppercase" required/>
                                </div>
                            </div>
                            <div class="form-group row mb-1">
                                <label class="col-form-label col-sm-3">Posisi</label>
                                <div class="col-md-9">
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label">
                                            <input type="radio" value="0" class="form-check-input-styled" name="tPosition" id="xOne" <?= !empty($acc) && $acc['organization_segment']==1?'checked':''; ?>>Independent
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label">
                                            <input type="radio" value="1" class="form-check-input-styled" name="tPosition" id="xTwo" <?= !empty($acc) && $acc['organization_segment']==1?'':'checked'; ?>>Turunan
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row mb-1 tSubAccount" style="display: none;">
                                <label class="col-form-label col-sm-3 ">Induk Turunan<sup><b class="text-danger">*</b></sup></label>
                                <div class="col-md-9">
                                    <select data-placeholder="Pilih Akun" id="tSubAccount" name="tSubAccount" class="form-control form-control-sm select-search">
                                        <option value="">-- Pilih Akun--</option>
                                        <?php
                                            foreach($subAcc as $sub){
                                                $dSelected2 = !empty($acc) && $acc['parent_idx'] == $sub->idx?'selected': '';
                                        ?>
                                        <option value="<?= $this->secure->enc($sub->idx); ?>" data-chained="<?= $this->secure->enc($sub->organization_type); ?>" accno="<?= $sub->organization_number; ?>" data-segment="<?= $sub->organization_segment; ?>"><?= $sub->organization_name; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row mb-1">
                                <label class="col-form-label col-sm-3">Status</label>
                                <div class="col-md-9">
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label">	<!-- tBnaktif -->
                                            <input type="radio" value="1" class="form-check-input-styled" name="tStatus" id="xTrue" <?= !empty($acc) && $acc['status'] == 1?'checked':''; ?>>Aktif
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label">	<!-- tBaktif -->
                                            <input type="radio" value="0" class="form-check-input-styled" name="tStatus" id="xFalse" <?= !empty($acc) && $acc['status'] == 0?'checked':''; ?>>Non-Aktif
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row mb-1">
                                <label class="col-form-label col-sm-3">Set Kepala Kabatan</label>
                                <div class="col-md-9">
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label">	<!-- tBnaktif -->
                                            <input type="radio" value="0" idx="<?= !empty($acc)?$this->secure->enc($acc['idx']):''; ?>" class="form-check-input-styled" name="setHead" id="headA" <?= !empty($acc) && $acc['status_head'] == 0?'checked':''; ?>>Manual
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label">	<!-- tBaktif -->
                                            <input type="radio" value="1" idx="<?= !empty($acc)?$this->secure->enc($acc['idx']):''; ?>" class="form-check-input-styled" name="setHead" id="headB" <?= !empty($acc) && $acc['status_head'] == 1?'checked':''; ?>>Dari List
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row mb-1 headOrganization" style="display: none;">
                                <label class="col-form-label col-sm-3 ">Kepala Jabatan</label>
                                <div class="col-md-9">
                                    <select data-placeholder="Pilih Karyawan" id="head_organization" name="head_organization" class="form-control form-control-sm select-search">
                                        <option value="">-- Pilih Karyawan--</option>
                                        <?php
                                            foreach($allEmployee as $a){
                                                $selected = !empty($acc) && $acc['employee_id'] == $a->employee_id?'selected':'';
                                        ?>
                                        <option value="<?= $this->secure->enc($a->employee_id); ?>" $selected><?= $a->employee_name; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row mb-1 headName">
                                <label class="col-form-label col-sm-3 ">Kepala Jabatan</label>
                                <div class="col-md-9">
                                    <input id="head_name" name="head_name" type="text" value="<?= !empty($acc) ?$acc['head_name']:''; ?>" placeholder="Nama Jabatan" class="form-control form-control-sm"/>
                                </div>
                            </div>
                            <div class="form-group row mb-1">
                                <label class="col-form-label col-sm-3">Unggah Foto</label>
                                <div class="col-md-9">
                                    <input type="file" id="cmd_browse" name="cmd_browse" accept="image/*" style="display:none;">
                                    <input type="hidden" id="url_image" name="url_image" value="<?= !empty($acc)&&$acc['photo'] !=''?$acc['photo']:''; ?>">
                                    <input type="hidden" id="temp_image" name="temp_image" value="0">
                                    <img src="<?= !empty($acc)?($acc['photo']==null?base_url('assets/media/photos/foto_uploadx.png'):$acc['photo']):base_url('assets/media/photos/foto_uploadx.png'); ?>" id="img_photo" class="rounded" style="width: 250px;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-end align-items-center">
                        <!--<button type="button" data-dismiss="modal" class="btn btn-outline-secondary">Close</button>-->
                        <button type="button" class="btn btn-primary btn-ladda btn-ladda-spinner bSbmit" data-spinner-color="#333" data-style="slide-down">Save changes</button>
                        <button type="submit" id="bSubmit" style="display: none;"></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="<?= base_url(); ?>assets/global_assets/js/plugins/pickers/datetimepicker/jquery.datetimepicker.full.min.js"></script>
<script src='https://cdn.jsdelivr.net/npm/tesseract.js@5/dist/tesseract.min.js'></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.js" integrity="sha512-Zt7blzhYHCLHjU0c+e4ldn5kGAbwLKTSOTERgqSNyTB50wWSI21z0q6bn/dEIuqf6HiFzKJ6cfj2osRhklb4Og==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="<?= base_url(); ?>assets/layout1/js/firebase.js"></script>
<script src="<?= base_url(); ?>assets/layout1/js/core/v_formorg.js?v=0.2" params='<?= $params; ?>'></script>