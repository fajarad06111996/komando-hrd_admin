<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.css" integrity="sha512-bs9fAcCAeaDfA4A+NiShWR886eClUcBtqhipoY5DM60Y1V3BbVQlabthUBal5bq8Z8nnxxiyb1wfGX2n76N1Mw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<style>
    .select-sm.select2-selection--single {
        line-height: 1.6 !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered{
        margin-top: unset !important;
    }
    .select2-container--default .select2-selection--single {
        height: 2rem !important;
    }
    span.error{
        outline: none;
        border: 1px solid #800000;
        box-shadow: 0 0 5px 1px #800000;
    }
</style>
<!--Modal change status-->
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
                    <div id="savingClass"></div>
                    <img id="image" src="<?= base_url(); ?>assets/images/ICON/no_image.png" style="max-width: 100% !important;">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="crop">Crop</button>
            </div>
        </div>
    </div>
</div>
<!--End Modal change status-->
<!--Modal change status-->
<div class="modal fade" id="modalDoc" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Nama Dokumen</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="" class="form-validasi2" enctype="multipart/form-data" method="post" id="docForm">
                <div class="modal-body">
                    <div class="img-container">
                        <input type="text" name="docName" class="form-control" placeholder="Input Nama Dokumen" data-msg="Nama Dokumen wajib di isi." required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--End Modal change status-->
<div class="alert alert-danger text-white alert-dismissible mb-1 p-2 notifValidasi" style="display:none">
    <button type="button" class="close aClose p-2"><span>&times;</span></button>
    <span class="font-weight-semibold">Informasi!</span>
    <div class="print-error-msg text-white"><p class="mb-0 pb-0">The No Awb field is required.</p><p>The No Awb field is required.</p></div>
</div>
<div class="card panel panel-blue">
    <div class=" header-elements-inline panel-heading">
        <?= $title; ?>
        <div class="header-elements">
            <div class="list-icons">
                <input type="hidden" name="base_url_get" value="<?= base_url(); ?>">
                <a href="<?=$link;?>" class="list-icons-item" title="Back To List Data" data-placement="right" data-popup="tooltip">
                    <i class="icon-undo2 pr-1" style="font-size:0.8em;padding-top: 4px;"></i>
                </a>
                <a href="javascript:void(0)" class="list-icons-item bSaved" title="Save Data" data-placement="right" data-popup="tooltip"><i class="fa fa-floppy-o fa-xs pr-1"></i></a>
            </div>
        </div>
    </div>
    <form action="<?= $formact; ?>" class="form-validasi" enctype="multipart/form-data" method="post" id="myForm">
        <div class="panel_body">
            <div class="row">
                <div class="col-md-12 mt-3 pr-0">
                    <ul class="nav nav-tabs  nav-tabs-solid bg-slate border-0 nav-tabs-component rounded mb-0" style="justify-content: space-between;">
                        <li class="nav-item"><a href="#info-tab1" class="nav-link active font-weight-bold pt-1 pb-1 pl-2 pr-2 iGeneral" data-toggle="tab">Dokumen</a></li>
                        <div class="list-icons pr-3">
                            <a href="javascript:void(0)" class="list-icons-item bAdd" title="Tambah Dokumen" data-placement="right" data-popup="tooltip-white"><i class="icon-plus-circle2" style="font-size:1.3em;"></i></a>
                        </div>
                    </ul>
                    <div class="tab-content card card-body border-top-0 rounded-top-0 mb-0 ">
                        <div class="tab-pane fade show active " id="info-tab1">
                            <h5 class=""><b>Unggah Dokumen</b></h5>
                            <hr>
                            <div class="row ml-3 mr-3" id="appendData">
                                <input type="hidden" name="employee_id" value="<?= $employeeId; ?>">
                            </div>
                        </div>
                    </div>
                    <p class="pull-right font-size-sm text-purple">Entry By : Admin </p>
                </div>
            </div>
        </div>
        <div class="card-footer d-none">
            <button type="submit" class="bSave" id="savingOrder"> <i class="icon-floppy-disk"></i> Save Data</button>
        </div>
    </form>
</div>
<script src="<?= base_url(); ?>assets/global_assets/js/plugins/pickers/datetimepicker/jquery.datetimepicker.full.min.js"></script>
<script src='https://cdn.jsdelivr.net/npm/tesseract.js@5/dist/tesseract.min.js'></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.js" integrity="sha512-Zt7blzhYHCLHjU0c+e4ldn5kGAbwLKTSOTERgqSNyTB50wWSI21z0q6bn/dEIuqf6HiFzKJ6cfj2osRhklb4Og==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<!-- <script src="https://www.gstatic.com/firebasejs/live/3.0/firebase.js"></script> -->
<script src="<?= base_url(); ?>assets/layout1/js/firebase.js"></script>
<script src="<?= base_url(); ?>assets/layout1/js/employee/v_formDocEmployee.js?v=0.2" params='<?= $params; ?>'></script>