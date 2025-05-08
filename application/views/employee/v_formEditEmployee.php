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
                    <img id="image" src=dp.base_url+"assets/images/ICON/no_image.png" style="max-width: 100% !important;">
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
<!--Modal Responsive-->
<div id="mFormDet" data-backdrop="static" data-keyboard="false" class="modal fade">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header pt-2 pb-2 bg-blue-700">
				<h4 class="modal-title"></h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<form action="" id="myFormDet" class="form-horizontal form-validasi2" accept-charset="utf-8" enctype="multipart/form-data" method="post">
				<div class="modal-body">
					<div class="row">
                        <div class="col-sm-12">
                            <table id="itux" class="table table-sm1 col-sm-12 text-center mt-2">
                                <thead>
                                    <tr>
                                        <th width="25%">Bank<b class="text-danger">*</b></sup></th>
                                        <th width="25%">Cabang<b class="text-danger">*</b></sup></th>
                                        <th width="25%">Nama Pemilik<b class="text-danger">*</b></sup></th>
                                        <th width="25%">Nomor Rekening<b class="text-danger">*</b></sup></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="nohx">
                                        <?= csrf_input(); ?>
                                        <input name="employee_idx" type="hidden" value="<?= $this->secure->enc($msg['employee_id']); ?>"/>
                                        <td class="remover">
                                            <select id="tBankName" name="tBankName" data-placeholder="Bank" class="form-control form-control-sm select-search searchBank"  data-container-css-class="select-sm" data-fouc data-msg="Bank wajib di pilih." required></select>
                                            <!-- <select id="tBankName" name="tBankName" data-placeholder="Bank" class="form-control form-control-sm" data-msg="Bank wajib di pilih." required>
                                                <option value="">- Select Bank -</option>
                                                <option value="1">A</option>
                                                <option value="2">B</option>
                                                <option value="3">C</option>
                                                <option value="4">D</option>
                                            </select> -->
                                        </td>
                                        <td>
                                            <input type="text" tabindex="17" name="tBranch" class="form-control form-control-sm" placeholder="Cabang" value="" data-msg="Cabang wajib diisi." required>
                                        </td>
                                        <td>
                                            <input type="text" tabindex="18" name="tOwner" class="form-control form-control-sm" placeholder="Nama Pemilik" value="" data-msg="Nama Pemilik wajib diisi." required>
                                        </td>
                                        <td>
                                            <input type="text" tabindex="19" name="tAccount" class="form-control form-control-sm" placeholder="Nomor Rekening" data-msg="Nomor Rekening wajib diisi." value="" required>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
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
<!-- End Modal add -->
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
                <input type="hidden" name="base_url_get" value=dp.base_url+"">
                <a href="<?=$link;?>" class="list-icons-item" title="Back To List Data" data-placement="right" data-popup="tooltip">
                    <i class="icon-undo2 pr-1" style="font-size:0.8em;padding-top: 4px;"></i>
                </a>
                <a href="javascript:void(0)" class="list-icons-item bSaved" title="Save Data" data-placement="right" data-popup="tooltip-white"><i class="fa fa-floppy-o fa-xs pr-1"></i></a>
            </div>
        </div>
    </div>
    <form action="<?= $formact; ?>" class="form-validasi" enctype="multipart/form-data" method="post" id="myForm">
        <div class="panel_body">
            <div class="row">
                <div class="col-md-12 mt-3 pr-0">
                    <ul class="nav nav-tabs  nav-tabs-solid bg-slate border-0 nav-tabs-component rounded mb-0">
                        <li class="nav-item"><a href="#info-tab1" class="nav-link active font-weight-bold pt-1 pb-1 pl-2 pr-2 iGeneral" data-toggle="tab">Personal</a></li>
                        <li class="nav-item"><a href="#info-tab2" class="nav-link  font-weight-bold pt-1 pb-1 pl-2 pr-2 iLog" data-toggle="tab">Kepegawaian</a></li>
                        <li class="nav-item"><a href="#info-tab3" class="nav-link  font-weight-bold pt-1 pb-1 pl-2 pr-2 iLink" data-toggle="tab">Payroll</a></li>
                    </ul>
                    <div class="tab-content card card-body border-top-0 rounded-top-0 mb-0 ">
                        <div class="tab-pane fade show active " id="info-tab1">
                            <div class="row ml-3 mr-3">
                                <div class="col-sm-6">
                                    <h5 class=""><b>Informasi Pribadi</b></h5>
                                    <div class="form-group mb-1">
                                        <label class="col-form-label text-right">ID Karyawan</label>
                                        <input type="hidden" name="emp_id" value="<?= $this->secure->enc($msg['employee_id']); ?>">
                                        <input name="employee_id" type="text" value="<?= $msg['employee_code'] ?>" placeholder="Auto" class="form-control form-control-sm" data-msg="ID Karyawan wajib di isi." readonly />
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="col-form-label text-right">Nama Karyawan<sup><b class="text-danger">*</b></sup></label>
                                        <input name="employee_name" type="text" value="<?= $msg['employee_name'] ?>" placeholder="Nama Karyawan" class="form-control form-control-sm" data-msg="Nama Karyawan wajib di isi." required />
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group mb-1">
                                                <label class="col-form-label text-right">Tempat Lahir<sup><b class="text-danger">*</b></sup></label>
                                                <input name="place_of_birth" type="text" value="<?= $msg['place_of_birth'] ?>" placeholder="Tempat Lahir" class="form-control form-control-sm" data-msg="Tempat Lahir wajib di isi." required />
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group mb-1">
                                                <label class="col-form-label text-right">Tanggal Lahir<sup><b class="text-danger">*</b></sup></label>
                                                <input name="date_of_birth" type="text" value="<?= date('d-m-Y', strtotime($msg['date_of_birth'])); ?>" placeholder="Tanggal Lahir" class="form-control form-control-sm date_input" onchange="removeError(this)" data-msg="Tanggal Lahir wajib di isi." onwheel="return false;" required />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <label class="col-form-label text-right">Jenis Kelamin<sup><b class="text-danger">*</b></sup></label>
                                            <div class="form-group mb-1">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="gender" id="inlineRadio1" value="1" <?= $msg['gender']==1?'checked':''; ?>>
                                                    <label class="form-check-label" for="inlineRadio1">Laki - Laki</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="gender" id="inlineRadio2" value="2" <?= $msg['gender']==2?'checked':''; ?>>
                                                    <label class="form-check-label" for="inlineRadio2">Perempuan</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group mb-1">
                                                <label class="col-form-label text-right">Status Perkawinan<sup><b class="text-danger">*</b></sup></label>
                                                <select name="maritial_status" data-placeholder="Status Perkawinan" class="form-control form-control-sm select-search"  data-container-css-class="select-sm" data-fouc required>
                                                    <option value="1" <?= empty($msg)?'':($msg['maritial_status']==1?'selected':'') ?>>Belum Menikah</option>
                                                    <option value="2" <?= empty($msg)?'':($msg['maritial_status']==2?'selected':'') ?>>Menikah</option>
                                                    <option value="3" <?= empty($msg)?'':($msg['maritial_status']==3?'selected':'') ?>>Janda</option>
                                                    <option value="4" <?= empty($msg)?'':($msg['maritial_status']==4?'selected':'') ?>>Duda</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group mb-1">
                                                <label class="col-form-label text-right">Golongan Darah<sup><b class="text-danger">*</b></sup></label>
                                                <select name="blood_type" data-placeholder="Golongan Darah" class="form-control form-control-sm select-search"  data-container-css-class="select-sm" data-fouc required>
                                                    <?php foreach($blood as $p): ?>
                                                    <option value="<?= $p->status; ?>" <?= empty($msg)?'':($msg['blood_type']==$p->status?'selected':'') ?>><?= $p->status_name; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group mb-1">
                                                <label class="col-form-label text-right">Agama<sup><b class="text-danger">*</b></sup></label>
                                                <select name="religion" data-placeholder="Agama" class="form-control form-control-sm select-search"  data-container-css-class="select-sm" data-fouc required>
                                                    <option value="1" <?= empty($msg)?'':($msg['religion']==1?'selected':'') ?>>Islam</option>
                                                    <option value="2" <?= empty($msg)?'':($msg['religion']==2?'selected':'') ?>>Kristen</option>
                                                    <option value="3" <?= empty($msg)?'':($msg['religion']==3?'selected':'') ?>>Budha</option>
                                                    <option value="4" <?= empty($msg)?'':($msg['religion']==4?'selected':'') ?>>Hindu</option>
                                                    <option value="5" <?= empty($msg)?'':($msg['religion']==5?'selected':'') ?>>Lainnnya</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group mb-1">
                                                <label class="col-form-label text-right">Kewarganegaraan<sup><b class="text-danger">*</b></sup></label>
                                                <select name="nationality" data-placeholder="Kewarganegaraan" class="form-control form-control-sm select-search"  data-container-css-class="select-sm" data-fouc required>
                                                    <option value="1" <?= empty($msg)?'':($msg['nationality']==1?'selected':'') ?>>WNI</option>
                                                    <option value="2" <?= empty($msg)?'':($msg['nationality']==2?'selected':'') ?>>WNA</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group mb-1">
                                                <label class="col-form-label text-right">&nbsp;</label>
                                                <input name="country" type="text" value="<?= $msg['country']; ?>" placeholder="Negara" class="form-control form-control-sm" data-msg="Negara wajib di isi." required />
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <h5 class=""><b>Pendidikan Terakhir</b></h5>
                                    <div class="form-group mb-1">
                                        <label class="col-form-label text-right">Jenjang Pendidikan Terakhir</label>
                                        <select name="education" data-placeholder="Jenjang Pendidikan Terakhir" class="form-control form-control-sm select-search"  data-container-css-class="select-sm" data-fouc required>
                                            <!-- <option value="" >- Pendidikan -</option> -->
                                            <?php foreach($pendidikan as $p): ?>
                                            <option value="<?= $p->status; ?>" <?= empty($msg)?'':($msg['education']==$p->status?'selected':'') ?>><?= $p->status_name; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group mb-1">
                                                <label class="col-form-label text-right">Nama Institusi Pendidikan<sup><b class="text-danger">*</b></sup></label>
                                                <input name="edu_institution_name" type="text" value="<?= $msg['edu_institution_name']; ?>" placeholder="Nama Institusi Pendidikan" class="form-control form-control-sm" data-msg="Nama Institusi wajib di isi." required />
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group mb-1">
                                                <label class="col-form-label text-right">Program Studi<sup><b class="text-danger">*</b></sup></label>
                                                <input name="study_program" type="text" value="<?= $msg['study_program']; ?>" placeholder="Program Studi" class="form-control form-control-sm" data-msg="Program Studi wajib di isi." required />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <h5 class=""><b>Informasi Kontak</b></h5>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group mb-1">
                                                <label class="col-form-label text-right">Tipe Kartu Identitas<sup><b class="text-danger">*</b></sup></label>
                                                <select name="id_type" data-placeholder="Kewarganegaraan" class="form-control form-control-sm select-search"  data-container-css-class="select-sm" data-fouc required>
                                                    <option value="1" <?= empty($msg)?'':($msg['id_type']==1?'selected':'') ?>>KTP</option>
                                                    <option value="2" <?= empty($msg)?'':($msg['id_type']==2?'selected':'') ?>>Passport</option>
                                                    <option value="3" <?= empty($msg)?'':($msg['id_type']==3?'selected':'') ?>>Kartu Izin Tinggal Terbatas (KITAS)</option>
                                                    <option value="4" <?= empty($msg)?'':($msg['id_type']==4?'selected':'') ?>>Kartu Izin Tinggal Tetap (KITAP)</option>
                                                    <option value="5" <?= empty($msg)?'':($msg['id_type']==5?'selected':'') ?>>Surat Izin Mengemudi (SIM)</option>
                                                    <option value="6" <?= empty($msg)?'':($msg['id_type']==6?'selected':'') ?>>Lainnya</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group mb-1">
                                                <label class="col-form-label text-right">ID Kartu Identitas<sup><b class="text-danger">*</b></sup></label>
                                                <input name="id_number" type="text" value="<?= $msg['id_number']; ?>" placeholder="ID Kartu Identitas" class="form-control form-control-sm" data-msg="ID Kartu Identitas wajib di isi." required />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="col-form-label text-right">Email</label>
                                        <input name="email_id" type="text" value="<?= $msg['email_id']; ?>" placeholder="Email" class="form-control form-control-sm" data-msg="Email wajib di isi."/>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group mb-1">
                                                <label class="col-form-label text-right">No. HP<sup><b class="text-danger">*</b></sup></label>
                                                <input name="mobile_phone" type="text" value="<?= $msg['mobile_phone']; ?>" placeholder="No. HP" class="form-control form-control-sm" data-msg="No. HP wajib di isi." required />
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group mb-1">
                                                <label class="col-form-label text-right">No. Telepon</label>
                                                <input name="telephone" type="text" value="<?= $msg['telephone']; ?>" placeholder="No. Telepon" class="form-control form-control-sm" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="col-form-label text-right">Alamat Kartu Identitas<sup><b class="text-danger">*</b></sup></label>
                                        <textarea name="address" type="text" row="3" placeholder="Alamat Kartu Identitas" class="form-control form-control-sm" data-msg="Alamat Kartu Identitas wajib di isi." required><?= $msg['address']; ?></textarea>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group mb-1">
                                                <label class="col-form-label text-right">Provinsi<sup><b class="text-danger">*</b></sup></label>
                                                <input name="province" type="text" value="<?= $msg['province']; ?>" placeholder="Provinsi" class="form-control form-control-sm" data-msg="Provinsi wajib di isi." required />
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group mb-1">
                                                <label class="col-form-label text-right">Kota</label>
                                                <input name="city" type="text" value="<?= $msg['city']; ?>" placeholder="Kota" class="form-control form-control-sm" data-msg="Kota wajib di isi." required/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="col-form-label text-right">Alamat Domisili<sup><b class="text-danger">*</b></sup></label>
                                        <textarea name="address_2" type="text" row="3" placeholder="Alamat Domisili" class="form-control form-control-sm" data-msg="Alamat Domisili wajib di isi." required><?= $msg['address_2']; ?></textarea>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group mb-1">
                                                <label class="col-form-label text-right">Provinsi Domisili<sup><b class="text-danger">*</b></sup></label>
                                                <input name="province_2" type="text" value="<?= $msg['province_2']; ?>" placeholder="Provinsi Domisili" class="form-control form-control-sm" data-msg="Provinsi Domisili wajib di isi." required />
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group mb-1">
                                                <label class="col-form-label text-right">Kota Domisili</label>
                                                <input name="city_2" type="text" value="<?= $msg['city_2']; ?>" placeholder="Kota Domisili" class="form-control form-control-sm" data-msg="Kota wajib di isi." required/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group mb-1">
                                                <label class="col-form-label text-right">Nama Kontak Darurat<sup><b class="text-danger">*</b></sup></label>
                                                <input name="emergency_phone_name" type="text" value="<?= $msg['emergency_phone_name']; ?>" placeholder="Nama Kontak Darurat" class="form-control form-control-sm" data-msg="Nama Kontak Darurat wajib di isi."/>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group mb-1">
                                                <label class="col-form-label text-right">No. Kontak Darurat<sup><b class="text-danger">*</b></sup></label>
                                                <input name="emergency_phone" type="text" value="<?= $msg['emergency_phone']; ?>" placeholder="No. Kontak Darurat" class="form-control form-control-sm" data-msg="No. Kontak Darurat wajib di isi."/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="info-tab2">
                            <div class="row ml-3 mr-3">
                                <div class="col-lg-6">
                                    <h5 class=""><b>Kepegawaian</b></h5>
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                            <div class="form-group mb-1">
                                                <label class="col-form-label text-right">Status Karyawan<sup><b class="text-danger">*</b></sup></label>
                                                <select name="employee_status" data-placeholder="Status Karyawan" class="form-control form-control-sm select-search"  data-container-css-class="select-sm" onchange="removeError(this)" data-fouc data-msg="Status Karyawan wajib di pilih." required>
                                                    <option value="" >- Status Karyawan -</option>
                                                    <?php foreach($pegawai as $p): ?>
                                                    <option value="<?= $p->status; ?>" <?= empty($msg)?'':($msg['employee_status']==$p->status?'selected':'') ?>><?= $p->status_name; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                            <div class="form-group mb-1">
                                                <label class="col-form-label text-right">Organisasi<sup><b class="text-danger">*</b></sup></label>
                                                <select name="organization_idx" id="organization_idx" data-placeholder="Organisasi" class="form-control form-control-sm select-search"  data-container-css-class="select-sm" onchange="removeError(this)" data-fouc data-msg="Organisasi wajib di pilih." required>
                                                    <option value="">- Organisasi -</option>
                                                    <?php foreach($organization as $or): ?>
                                                    <option value="<?= $this->secure->enc($or->idx); ?>" <?= empty($msg)?'':($msg['organization_idx']==$or->idx?'selected':'') ?>><?= $or->organization_name; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                            <div class="form-group mb-1">
                                                <label class="col-form-label text-right">Tanggal Bergabung<sup><b class="text-danger">*</b></sup></label>
                                                <input name="join_date" type="text" value="<?= date('d-m-Y',strtotime($msg['join_date'])); ?>" placeholder="Tanggal Bergabung" class="form-control form-control-sm date_input" onchange="removeError(this)" data-msg="Tanggal Bergabung wajib di isi." required onwheel="return false;"/>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                            <div class="form-group mb-1">
                                                <label class="col-form-label text-right">Tanggal Berakhir</label>
                                                <input name="expired_date" type="text" value="<?= empty($msg['expired_date'])?'':date('d-m-Y',strtotime($msg['expired_date'])); ?>" placeholder="Tanggal Bergabung" class="form-control form-control-sm date_input" onchange="removeError(this)" data-msg="Tanggal Berakhir wajib di isi." onwheel="return false;"/>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                            <div class="form-group mb-1">
                                                <label class="col-form-label text-right">Organisasi<sup><b class="text-danger">*</b></sup></label>
                                                <select name="department_idx" id="department_idx" data-placeholder="Organisasi" class="form-control form-control-sm select-search"  data-container-css-class="select-sm" onchange="removeError(this)" data-fouc data-msg="Organisasi wajib di pilih." required>
                                                    <option value="">- Organisasi -</option>
                                                    <?php //foreach($department as $dp): ?>
                                                    <option value="<?//= $this->secure->enc($dp->idx); ?>" <?//= empty($msg)?'':($msg['department_idx']==$dp->idx?'selected':'') ?>><?//= $dp->department_name; ?></option>
                                                    <?php //endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                            <div class="form-group mb-1">
                                                <label class="col-form-label text-right">Jabatan<sup><b class="text-danger">*</b></sup></label>
                                                <select name="designation_idx" id="designation_idx" data-placeholder="Jabatan" class="form-control form-control-sm select-search"  data-container-css-class="select-sm" onchange="removeError(this)" data-fouc data-msg="Jabatan wajib di pilih." required>
                                                    <option value="">- Jabatan -</option>
                                                    <?php //foreach($designation as $ds): ?>
                                                    <option value="<?//= $this->secure->enc($ds->idx); ?>" data-chained="<?//= $this->secure->enc($ds->dept_idx); ?>" <?//= empty($msg)?'':($msg['designation_idx']==$ds->idx?'selected':'') ?>><?//= $ds->designation_name; ?></option>
                                                    <?php //endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div> -->
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                            <div class="form-group mb-1">
                                                <label class="col-form-label text-right">Status Penempatan Kerja</label>
                                                <select name="work_placement" data-placeholder="Penempatan Kerja" class="form-control form-control-sm select-search"  data-container-css-class="select-sm" onchange="removeError(this)" data-fouc data-msg="Penempatan Kerja wajib di pilih." required>
                                                    <option value="" >- Penempatan Kerja -</option>
                                                    <option value="1" <?= empty($msg)?'':($msg['work_placement']==1?'selected':'') ?>>Baru Direkrut</option>
                                                    <option value="2" <?= empty($msg)?'':($msg['work_placement']==2?'selected':'') ?>>Demosi</option>
                                                    <option value="3" <?= empty($msg)?'':($msg['work_placement']==3?'selected':'') ?>>Diangkat Karyawan Tetap</option>
                                                    <option value="4" <?= empty($msg)?'':($msg['work_placement']==4?'selected':'') ?>>Mutasi</option>
                                                    <option value="5" <?= empty($msg)?'':($msg['work_placement']==5?'selected':'') ?>>Promosi</option>
                                                    <option value="6" <?= empty($msg)?'':($msg['work_placement']==6?'selected':'') ?>>Rotasi</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                            <div class="form-group mb-1">
                                                <label class="col-form-label text-right">Jadwal<sup><b class="text-danger">*</b></sup></label>
                                                <select name="office_shift" data-placeholder="Jadwal" class="form-control form-control-sm select-search"  data-container-css-class="select-sm" onchange="removeError(this)" data-fouc data-msg="Jadwal wajib di pilih." required>
                                                    <option value="">- Jabatan -</option>
                                                    <?php foreach($shift as $s): ?>
                                                    <option value="<?= $s->idx; ?>" <?= empty($msg)?'':($msg['office_shift']==$s->idx?'selected':'') ?>><?= $s->shift_name; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                            <div class="form-group mb-1">
                                                <label class="col-form-label text-right">Lokasi Kerja<sup><b class="text-danger">*</b></sup></label>
                                                <select name="work_location" data-placeholder="Lokasi Kerja" class="form-control form-control-sm select-search"  data-container-css-class="select-sm" onchange="removeError(this)" data-fouc data-msg="Lokasi Kerja wajib di pilih." required>
                                                    <!-- <option value="" >- Lokasi Kerja -</option> -->
                                                    <?php foreach($office as $o): ?>
                                                    <option value="<?= $this->secure->enc($o->idx); ?>" <?= empty($msg)?'':($msg['office_idx']==$o->idx?'selected':'') ?>><?= $o->office_name; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                            <div class="form-group mb-1">
                                                <label class="col-form-label text-right">Company<sup><b class="text-danger">*</b></sup></label>
                                                <select name="company_idx" data-placeholder="Company" class="form-control form-control-sm select-search"  data-container-css-class="select-sm" onchange="removeError(this)" data-fouc data-msg="Company wajib di pilih." required>
                                                    <!-- <option value="" >- Lokasi Kerja -</option> -->
                                                    <?php foreach($company as $c): ?>
                                                    <option value="<?= $this->secure->enc($c->idx); ?>" <?= empty($msg)?'':($msg['company_idx']==$c->idx?'selected':'') ?>><?= $c->company_name; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                            <div class="form-group mb-1">
                                                <label class="col-form-label text-right">Radius Kerja<sup><b class="text-danger">*</b></sup></label>
                                                <div class="input-group mb-3">
                                                    <input name="work_radius" type="number" value="<?= $msg['attendance_radius']; ?>" placeholder="Radius Kerja" class="form-control form-control-sm" onchange="removeError(this)" data-msg="Radius Kerja wajib di isi." required onwheel="return false;"/>
                                                    <div class="input-group-append">
                                                        <span class="input-group-text form-control form-control-sm" id="basic-addon2">meter</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                            <div class="form-group mb-1">
                                                <label class="col-form-label text-right">Pin Mesin Absen<sup><b class="text-danger">*</b></sup></label>
                                                <input name="attendance_pin" type="number" value="<?= $msg['att_pin']; ?>" placeholder="Pin Mesin Absen" class="form-control form-control-sm" onchange="removeError(this)" data-msg="Pin Mesin Absen wajib di isi." required onwheel="return false;"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <h5 class=""><b>Unggah Foto</b><b class="text-danger">*</b></h5>
                                    <div class="form-group row justify-content-center mb-1">
                                        <div class="col-sm-8 text-left p-3">
                                            <input type="file" id="cmd_browse" name="cmd_browse" accept="image/*" style="display:none;">
                                            <input type="hidden" id="url_image" name="url_image" value="<?= !empty($msg)&&$msg['photo'] !=''?$msg['photo']:''; ?>">
                                            <input type="hidden" id="temp_image" name="temp_image" value="0">
                                            <img src="<?= !empty($msg)?($msg['photo']==null?base_url('assets/media/photos/foto_uploadx.png'):$msg['photo']):base_url('assets/media/photos/foto_uploadx.png'); ?>" id="img_photo" class="rounded" style="width: 100%;">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <h5 class=""><b>Dokumen List</b><b class="text-danger">*</b></h5>
                                    <hr>
                                    <div class="row ml-3 mr-3" id="appendData">
                                        <input type="hidden" name="employee_idx" value="<?= $employeeId; ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="info-tab3">
                            <div class="row ml-3 mr-3">
                                <div class="col-lg-6 mb-3">
                                    <h5 class=""><b>Potongan</b></h5>
                                    <div class="row">
                                        <div class="col-lg-6 col-md-4 col-sm-6">
                                            <div class="form-group mb-1">
                                                <label class="col-form-label text-right">NPWP</label>
                                                <select name="tax_status" data-placeholder="NPWP" class="form-control form-control-sm select-search"  data-container-css-class="select-sm" onchange="removeError(this)" data-fouc data-msg="NPWP wajib di pilih." required>
                                                    <option value="" >- NPWP -</option>
                                                    <?php foreach($npwp as $n): ?>
                                                    <option value="<?= $n->status; ?>" <?= empty($msg)?'':($msg['tax_status']==$n->status?'selected':'') ?>><?= $n->status_code; ?> - <?= $n->status_name; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-4 col-sm-6">
                                            <div class="form-group mb-1">
                                                <label class="col-form-label text-right">&nbsp;</label>
                                                <input name="tax_number" type="text" value="<?= $msg['tax_number']; ?>" placeholder="12.345.678.9-012.345" class="form-control form-control-sm" data-msg="Nomor NPWP wajib di isi." required />
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-4 col-sm-6">
                                            <div class="form-group mb-1">
                                                <label class="col-form-label text-right">NPWP Pemotong</label>
                                                <select name="tax_withholder" data-placeholder="NPWP Pemotong" class="form-control form-control-sm select-search"  data-container-css-class="select-sm" onchange="removeError(this)" data-fouc data-msg="NPWP Pemotong wajib di pilih." required>
                                                    <option value="">- NPWP Pemotong -</option>
                                                    <option value="1" <?= empty($msg)?'':($msg['tax_withholder']==1?'selected':'') ?>>(E titik tiga)</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-3">
                                    <h5 class="">&nbsp;</h5>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group mb-1">
                                                <label class="col-form-label text-right">No KPJ BPJS Ketenagakerjaan</label>
                                                <input name="bpjs_tenagakerja" type="text" value="<?= $msg['bpjs_tenagakerja']; ?>" placeholder="No KPJ BPJS Ketenagakerjaan" class="form-control form-control-sm" data-msg="No KPJ BPJS Ketenagakerjaan wajib di isi."/>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group mb-1">
                                                <label class="col-form-label text-right" style="display: inline-flex;">Tanggal Efektif &nbsp;<svg viewBox="64 64 896 896" focusable="false" data-icon="question-circle" width="1em" height="1em" fill="currentColor" aria-hidden="true" style="align-self: center; cursor: help;" data-popup="tooltip" title="Tanggal mulai dihitungnya kepesertaan BPJS di perusahaan, perusahaan akan menghitung potongan berdasarkan tanggal tersebut" data-placement="top"><path d="M512 64C264.6 64 64 264.6 64 512s200.6 448 448 448 448-200.6 448-448S759.4 64 512 64zm0 820c-205.4 0-372-166.6-372-372s166.6-372 372-372 372 166.6 372 372-166.6 372-372 372z"></path><path d="M623.6 316.7C593.6 290.4 554 276 512 276s-81.6 14.5-111.6 40.7C369.2 344 352 380.7 352 420v7.6c0 4.4 3.6 8 8 8h48c4.4 0 8-3.6 8-8V420c0-44.1 43.1-80 96-80s96 35.9 96 80c0 31.1-22 59.6-56.1 72.7-21.2 8.1-39.2 22.3-52.1 40.9-13.1 19-19.9 41.8-19.9 64.9V620c0 4.4 3.6 8 8 8h48c4.4 0 8-3.6 8-8v-22.7a48.3 48.3 0 0130.9-44.8c59-22.7 97.1-74.7 97.1-132.5.1-39.3-17.1-76-48.3-103.3zM472 732a40 40 0 1080 0 40 40 0 10-80 0z"></path></svg></label>
                                                <input name="tenagakerja_effective_date" type="text" value="<?= date('d-m-Y', strtotime($msg['tenagakerja_effective_date'])); ?>" placeholder="Tanggal Efektif" class="form-control form-control-sm date_input" data-msg="Tanggal Efektif wajib di isi." onwheel="return false;"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group mb-1">
                                                <label class="col-form-label text-right">No KPJ BPJS Kesehatan</label>
                                                <input name="bpjs_kesehatan" type="text" value="<?= $msg['bpjs_kesehatan']; ?>" placeholder="No KPJ BPJS Kesehatan" class="form-control form-control-sm" data-msg="No KPJ BPJS Kesehatan wajib di isi."/>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group mb-1">
                                                <label class="col-form-label text-right" style="display: inline-flex;">Tanggal Efektif &nbsp;<svg viewBox="64 64 896 896" focusable="false" data-icon="question-circle" width="1em" height="1em" fill="currentColor" aria-hidden="true" style="align-self: center; cursor: help;" data-popup="tooltip" title="Tanggal mulai dihitungnya kepesertaan BPJS di perusahaan, perusahaan akan menghitung potongan berdasarkan tanggal tersebut" data-placement="top"><path d="M512 64C264.6 64 64 264.6 64 512s200.6 448 448 448 448-200.6 448-448S759.4 64 512 64zm0 820c-205.4 0-372-166.6-372-372s166.6-372 372-372 372 166.6 372 372-166.6 372-372 372z"></path><path d="M623.6 316.7C593.6 290.4 554 276 512 276s-81.6 14.5-111.6 40.7C369.2 344 352 380.7 352 420v7.6c0 4.4 3.6 8 8 8h48c4.4 0 8-3.6 8-8V420c0-44.1 43.1-80 96-80s96 35.9 96 80c0 31.1-22 59.6-56.1 72.7-21.2 8.1-39.2 22.3-52.1 40.9-13.1 19-19.9 41.8-19.9 64.9V620c0 4.4 3.6 8 8 8h48c4.4 0 8-3.6 8-8v-22.7a48.3 48.3 0 0130.9-44.8c59-22.7 97.1-74.7 97.1-132.5.1-39.3-17.1-76-48.3-103.3zM472 732a40 40 0 1080 0 40 40 0 10-80 0z"></path></svg></label>
                                                <input name="kesehatan_effective_date" type="text" value="<?= date('d-m-Y', strtotime($msg['kesehatan_effective_date'])); ?>" placeholder="Tanggal Efektif" class="form-control form-control-sm date_input" data-msg="Tanggal Efektif wajib di isi." onwheel="return false;"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <hr>
                                    <h5 class=""><b>Penerimaan</b></h5>
                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 col-sm-6">
                                            <div class="form-group mb-1">
                                                <label class="col-form-label text-right" style="display: inline-flex;">Gaji Pokok &nbsp;<svg viewBox="64 64 896 896" focusable="false" data-icon="question-circle" width="1em" height="1em" fill="currentColor" aria-hidden="true" style="align-self: center; cursor: help;" data-popup="tooltip" title="Jika tidak di isi maka Gaji Pokok akan di ambil dari master jabatan" data-placement="top"><path d="M512 64C264.6 64 64 264.6 64 512s200.6 448 448 448 448-200.6 448-448S759.4 64 512 64zm0 820c-205.4 0-372-166.6-372-372s166.6-372 372-372 372 166.6 372 372-166.6 372-372 372z"></path><path d="M623.6 316.7C593.6 290.4 554 276 512 276s-81.6 14.5-111.6 40.7C369.2 344 352 380.7 352 420v7.6c0 4.4 3.6 8 8 8h48c4.4 0 8-3.6 8-8V420c0-44.1 43.1-80 96-80s96 35.9 96 80c0 31.1-22 59.6-56.1 72.7-21.2 8.1-39.2 22.3-52.1 40.9-13.1 19-19.9 41.8-19.9 64.9V620c0 4.4 3.6 8 8 8h48c4.4 0 8-3.6 8-8v-22.7a48.3 48.3 0 0130.9-44.8c59-22.7 97.1-74.7 97.1-132.5.1-39.3-17.1-76-48.3-103.3zM472 732a40 40 0 1080 0 40 40 0 10-80 0z"></path></svg></label>
                                                <input name="basic_salary" type="text" value="<?= $msg['basic_salary']; ?>" placeholder="ex. 10,000,000" class="form-control form-control-sm moneyx" data-msg="Gaji Pokok wajib di isi." required />
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-4 col-sm-6">
                                            <div class="form-group mb-1">
                                                <label class="col-form-label text-right" id="umt" style="display: inline-flex;">Uang Makan &nbsp;<svg viewBox="64 64 896 896" focusable="false" data-icon="question-circle" width="1em" height="1em" fill="currentColor" aria-hidden="true" style="align-self: center; cursor: help;" data-popup="tooltip" title="Jika tidak di isi maka Uang Makan akan di ambil dari master jabatan" data-placement="top"><path d="M512 64C264.6 64 64 264.6 64 512s200.6 448 448 448 448-200.6 448-448S759.4 64 512 64zm0 820c-205.4 0-372-166.6-372-372s166.6-372 372-372 372 166.6 372 372-166.6 372-372 372z"></path><path d="M623.6 316.7C593.6 290.4 554 276 512 276s-81.6 14.5-111.6 40.7C369.2 344 352 380.7 352 420v7.6c0 4.4 3.6 8 8 8h48c4.4 0 8-3.6 8-8V420c0-44.1 43.1-80 96-80s96 35.9 96 80c0 31.1-22 59.6-56.1 72.7-21.2 8.1-39.2 22.3-52.1 40.9-13.1 19-19.9 41.8-19.9 64.9V620c0 4.4 3.6 8 8 8h48c4.4 0 8-3.6 8-8v-22.7a48.3 48.3 0 0130.9-44.8c59-22.7 97.1-74.7 97.1-132.5.1-39.3-17.1-76-48.3-103.3zM472 732a40 40 0 1080 0 40 40 0 10-80 0z"></path></svg></label>
                                                <input name="meal_allowance" type="text" value="<?= $msg['meal_allowance']; ?>" placeholder="ex. 50,000" class="form-control form-control-sm moneyx" data-msg="Uang Makan wajib di isi."/>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-4 col-sm-6">
                                            <div class="form-group mb-1">
                                                <label class="col-form-label text-right" id="umt" style="display: inline-flex;">Uang Transport &nbsp;<svg viewBox="64 64 896 896" focusable="false" data-icon="question-circle" width="1em" height="1em" fill="currentColor" aria-hidden="true" style="align-self: center; cursor: help;" data-popup="tooltip" title="Jika tidak di isi maka Uang Transport akan di ambil dari master jabatan" data-placement="top"><path d="M512 64C264.6 64 64 264.6 64 512s200.6 448 448 448 448-200.6 448-448S759.4 64 512 64zm0 820c-205.4 0-372-166.6-372-372s166.6-372 372-372 372 166.6 372 372-166.6 372-372 372z"></path><path d="M623.6 316.7C593.6 290.4 554 276 512 276s-81.6 14.5-111.6 40.7C369.2 344 352 380.7 352 420v7.6c0 4.4 3.6 8 8 8h48c4.4 0 8-3.6 8-8V420c0-44.1 43.1-80 96-80s96 35.9 96 80c0 31.1-22 59.6-56.1 72.7-21.2 8.1-39.2 22.3-52.1 40.9-13.1 19-19.9 41.8-19.9 64.9V620c0 4.4 3.6 8 8 8h48c4.4 0 8-3.6 8-8v-22.7a48.3 48.3 0 0130.9-44.8c59-22.7 97.1-74.7 97.1-132.5.1-39.3-17.1-76-48.3-103.3zM472 732a40 40 0 1080 0 40 40 0 10-80 0z"></path></svg></label>
                                                <input name="transport_allowance" type="text" value="<?= $msg['transport_allowance']; ?>" placeholder="ex. 50,000" class="form-control form-control-sm moneyx" data-msg="Uang Transport wajib di isi."/>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-4 col-sm-6">
                                            <div class="form-group mb-1">
                                                <label class="col-form-label text-right" id="umt" style="display: inline-flex;">Uang Piket &nbsp;<svg viewBox="64 64 896 896" focusable="false" data-icon="question-circle" width="1em" height="1em" fill="currentColor" aria-hidden="true" style="align-self: center; cursor: help;" data-popup="tooltip" title="Lembur Piket/masuk kerja di hari libur." data-placement="top"><path d="M512 64C264.6 64 64 264.6 64 512s200.6 448 448 448 448-200.6 448-448S759.4 64 512 64zm0 820c-205.4 0-372-166.6-372-372s166.6-372 372-372 372 166.6 372 372-166.6 372-372 372z"></path><path d="M623.6 316.7C593.6 290.4 554 276 512 276s-81.6 14.5-111.6 40.7C369.2 344 352 380.7 352 420v7.6c0 4.4 3.6 8 8 8h48c4.4 0 8-3.6 8-8V420c0-44.1 43.1-80 96-80s96 35.9 96 80c0 31.1-22 59.6-56.1 72.7-21.2 8.1-39.2 22.3-52.1 40.9-13.1 19-19.9 41.8-19.9 64.9V620c0 4.4 3.6 8 8 8h48c4.4 0 8-3.6 8-8v-22.7a48.3 48.3 0 0130.9-44.8c59-22.7 97.1-74.7 97.1-132.5.1-39.3-17.1-76-48.3-103.3zM472 732a40 40 0 1080 0 40 40 0 10-80 0z"></path></svg></label>
                                                <div class="input-group mb-3">
                                                    <input name="overtime_allowance" type="text" value="<?= $msg['overtime_allowance']; ?>" placeholder="ex. 25,000" class="form-control form-control-sm moneyx" data-msg="Uang Lembur wajib di isi."/>
                                                    <div class="input-group-append">
                                                        <span class="input-group-text form-control form-control-sm" id="basic-addon2">perhari</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-4 col-sm-6">
                                            <div class="form-group mb-1">
                                                <label class="col-form-label text-right" id="umt" style="display: inline-flex;">Tunjangan Jabatan &nbsp;<svg viewBox="64 64 896 896" focusable="false" data-icon="question-circle" width="1em" height="1em" fill="currentColor" aria-hidden="true" style="align-self: center; cursor: help;" data-popup="tooltip" title="Jika tidak di isi maka Tunjangan Jabatan akan di ambil dari master jabatan" data-placement="top"><path d="M512 64C264.6 64 64 264.6 64 512s200.6 448 448 448 448-200.6 448-448S759.4 64 512 64zm0 820c-205.4 0-372-166.6-372-372s166.6-372 372-372 372 166.6 372 372-166.6 372-372 372z"></path><path d="M623.6 316.7C593.6 290.4 554 276 512 276s-81.6 14.5-111.6 40.7C369.2 344 352 380.7 352 420v7.6c0 4.4 3.6 8 8 8h48c4.4 0 8-3.6 8-8V420c0-44.1 43.1-80 96-80s96 35.9 96 80c0 31.1-22 59.6-56.1 72.7-21.2 8.1-39.2 22.3-52.1 40.9-13.1 19-19.9 41.8-19.9 64.9V620c0 4.4 3.6 8 8 8h48c4.4 0 8-3.6 8-8v-22.7a48.3 48.3 0 0130.9-44.8c59-22.7 97.1-74.7 97.1-132.5.1-39.3-17.1-76-48.3-103.3zM472 732a40 40 0 1080 0 40 40 0 10-80 0z"></path></svg></label>
                                                <input name="position_allowance" type="text" value="<?= $msg['position_allowance']; ?>" placeholder="ex. 500,000" class="form-control form-control-sm moneyx" data-msg="Tunjangan Jabatan wajib di isi."/>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-4 col-sm-6">
                                            <div class="form-group mb-1">
                                                <label class="col-form-label text-right" style="display: inline-flex;">Kode Lembur &nbsp;<svg viewBox="64 64 896 896" focusable="false" data-icon="question-circle" width="1em" height="1em" fill="currentColor" aria-hidden="true" style="align-self: center; cursor: help;" data-popup="tooltip" title="Lembur harian/kelebihan jam kerja di hari kerja." data-placement="top"><path d="M512 64C264.6 64 64 264.6 64 512s200.6 448 448 448 448-200.6 448-448S759.4 64 512 64zm0 820c-205.4 0-372-166.6-372-372s166.6-372 372-372 372 166.6 372 372-166.6 372-372 372z"></path><path d="M623.6 316.7C593.6 290.4 554 276 512 276s-81.6 14.5-111.6 40.7C369.2 344 352 380.7 352 420v7.6c0 4.4 3.6 8 8 8h48c4.4 0 8-3.6 8-8V420c0-44.1 43.1-80 96-80s96 35.9 96 80c0 31.1-22 59.6-56.1 72.7-21.2 8.1-39.2 22.3-52.1 40.9-13.1 19-19.9 41.8-19.9 64.9V620c0 4.4 3.6 8 8 8h48c4.4 0 8-3.6 8-8v-22.7a48.3 48.3 0 0130.9-44.8c59-22.7 97.1-74.7 97.1-132.5.1-39.3-17.1-76-48.3-103.3zM472 732a40 40 0 1080 0 40 40 0 10-80 0z"></path></svg></label>
                                                <select name="ot_id" data-placeholder="Kode Lembur" class="form-control form-control-sm select-search"  data-container-css-class="select-sm" onchange="removeError(this)" data-fouc data-msg="Kode Lembur wajib di pilih." required>
                                                    <option value="">- Kode Lembur -</option>
                                                    <?php foreach($setup as $s): 
                                                        $selected = $msg['ot_id']==$s->ot_id?'selected':'';
                                                    ?>
                                                    <option class="option-oke" value="<?= $this->secure->enc($s->ot_id); ?>" title="<?= $s->description; ?>" <?= $selected; ?>><?= $s->ot_code; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <hr>
                                    <h5 class="mt-2"><b>Nomor Rekening</b></h5>
                                    <div class="table-responsive">
                                        <table id="mRekening" class="table table-sm1 text-center" width="100%">
                                            <thead>
                                                <tr>
                                                    <th width="22%">Bank<b class="text-danger">*</b></sup></th>
                                                    <th width="22%">Cabang<b class="text-danger">*</b></sup></th>
                                                    <th width="22%">Nama Pemilik<b class="text-danger">*</b></sup></th>
                                                    <th width="22%">Nomor Rekening<b class="text-danger">*</b></sup></th>
                                                    <th width="12%">
                                                        <button type="button" class="btn btn-success bDadd" data-popup="tooltip" title="Add Detail" data-placement="right"><i class="icon-plus-circle2"></i></button>
                                                    </th>
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
<script src="<?= base_url(); ?>assets/layout1/js/employee/v_formEditEmployee.js?v=0.3" params='<?= $params; ?>'></script>