<?php
defined('BASEPATH') or exit('No direct script access allowed');
require "vendor/autoload.php";
use Nullix\CryptoJsAes\CryptoJsAes;
class Employee extends AUTH_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('employee/M_employee', 'mEmp');
        $this->load->model('ModelGenId');
        $this->load->library('security_function');
        $this->link	    = site_url().strtolower(get_class($this));
        $this->filename = strtolower(get_class($this));
        $this->office   = $this->secure->dec($this->session->userdata('JToffice_id'));
        $this->hub      = $this->secure->dec($this->session->userdata('JThub_id'));
        $this->idx      = $this->secure->dec($this->session->userdata('JTidx'));
        $this->username = $this->session->userdata('JTuser_id');
        $this->enkey    = $this->config->item('encryption_key');
    }
    public function index()
    {
        $access = $this->security_function->permissions($this->filename . "-r");
        if (empty($access)) die("<center><h1>You can not get the right to access this module</h1></center>");
        if(!empty($this->security_function->permissions($this->filename . "-w"))){
            $data['write']  = '<a href="'.$this->link.'/formAdd'.'" id="btnAdd" class="pull-right text-white small" title="Tambah Karyawan" data-placement="right" data-popup="tooltip"><i class="icon-plus-circle2"></i></a>';
            $data['lock']   = 1;
        }else{
            $data['write']  = '<i class="icon-lock pull-right pt-1 " title="Tambah Karyawan Terkunci" data-placement="right" data-popup="tooltip"></i>';
            $data['lock']   = 0;
        }
        $data['department'] = $this->mEmp->getDepartment();
        $data['filename']   = $this->filename;
        $data["link"]       = $this->link;
        $params             = '{"link": "'.$this->link.'", "csrf": "'.$this->session->csrf_token.'", "permW": "'.$this->security_function->permissions($this->filename . "-w").'", "permC": "'.$this->security_function->permissions($this->filename . "-c").'", "permX": "'.$this->security_function->permissions($this->filename . "-x").'", "base_url": "'.base_url().'", "lock": "'.$data['lock'].'"}';
        // encrypt
        $encrypted          = CryptoJsAes::encrypt($params, $this->enkey);
        $data['params']     = $encrypted;
        $this->template->views('employee/v_employee', $data);
    }
    // CONTOH LAIN CONTROLLER2 POST DATATABLE SERVERSIDE
    function get_ajax()
    {
        $csrfToken  = validate_csrf_token();
        if($csrfToken == false){
            $list = array();
        }else{
            $list = $this->mEmp->get_datatables(); // untuk data dan pagination table
        }
        $data = array();
        $no = @$_POST['start'];
        foreach ($list as $item) {
            $idx            = $this->secure->enc( $item->idx);
            // $idx            = $this->secure->enc($item->idx);
            if(!empty($this->security_function->permissions($this->filename . "-w"))){
            }else{
            }
            if(!empty($this->security_function->permissions($this->filename . "-c"))){
                $change = "<h5 class='m-0'><a href='javascript:void(0);' id='$idx' class='bEdit text-center badge badge-info' data-popup='tooltip' title='Edit Karyawan' data-placement='right'><i class='icon-pencil5'></i></a></h5>";
                $doc = "<h5 class='m-0'><a href='javascript:void(0);' id='$idx' class='bDoc text-center badge badge-success' data-popup='tooltip' title='Dokumen Karyawan' data-placement='right'><i class='icon-file-text2'></i></a></h5>";
                if($item->status == 1)
                {
                    $statusU = "<h5><a href='javascript:void(0);' id='$idx' data='$item->employee_name' code='1' class='bStatus text-center badge badge-success' data-popup='tooltip' title='Ubah Status' data-placement='right'>Aktif</a></h5>";
                // $statusU = '<span class="badge badge-success">Aktif</span>';
                } else {
                    $statusU = "<h5><a href='javascript:void(0);' id='$idx' data='$item->employee_name' code='0' class='bStatus text-center badge badge-danger' data-popup='tooltip' title='Ubah Status' data-placement='right'>Non Aktif</a></h5>";
                // $statusU = '<span class="badge badge-danger">Non Aktif</span>';
                }
                if($item->status_user_apps==1){
                    $CrUser = "";
                }else{
                    $CrUser = "<h5><a href='javascript:void(0);' id='$idx' data='$item->employee_name' class='bCreate text-center badge badge-success' data-popup='tooltip' title='Create as User Apps' data-placement='right'><i class='icon-user-plus'></i></a></h5>";
                }
            }else{
                $change = '<h5 class="m-0"><span class="badge badge-warning bLockStatus"><i class="icon-lock" data-popup="tooltip" title="Locked Edit Karyawan" data-placement="right"></i></span></h5>';
                $doc = '<h5 class="m-0"><span class="badge badge-warning bLockStatus"><i class="icon-lock" data-popup="tooltip" title="Locked Dokumen Karyawan" data-placement="right"></i></span></h5>';
                if($item->status == 1)
                {
                    $statusU = "<span class='text-center badge badge-success'>Aktif</span>";
                }else{
                    $statusU = "<span class='text-center badge badge-danger'>Non Aktif</span>";
                }
                $CrUser = "";
            }
            if(!empty($this->security_function->permissions($this->filename . "-x"))){
                $execute  = "<h5 class='m-0'><a href='javascript:void(0);' id='$idx' data='$item->employee_name' class='bDelete text-center badge badge-danger' data-popup='tooltip' title='Hapus Karyawan' data-placement='right'><i class='icon-bin'></i></a></h5>";
            }else{
                $execute  = '<h5 class="m-0"><span class="badge badge-warning"><i class="icon-lock" data-popup="tooltip" title="Hapus Karyawan Terkunci" data-placement="right"></i></span></h5>';
            }
            $img = empty($item->photo)?base_url()."/assets/images/no_image.png":$item->photo;
            $no++;
            $row = array();
            $row[] = $no.".";
            $row[] = "<div class='btn-group'>
                        <button type='button' class='btn btn-danger btn-sm dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'><i class='icon-more2'></i></button>
                        <div class='dropdown-menu p-2' style='min-width: auto !important;'>
                            <div class='btn-group'>$CrUser&nbsp;$doc&nbsp;$change&nbsp;$execute</div>
                        </div>
                    </div>";
            $row[] = $statusU;
            $row[] = "<a href='$img' data-fancybox data-caption='$item->employee_name'><img src='$img' width='100px' class='bImage' caption='$item->employee_name' data-popup='tooltip' title='Click to preview' data-placement='right'>";
            $row[] = $item->employee_name;
            $row[] = $item->employee_code;
            $row[] = empty($item->organization_name)?'<span class="text-danger">unset</span>':$item->organization_name;
            $row[] = $item->mobile_phone;
            $row[] = $item->email_id;
            $row[] = $item->genderx;
            // add html for action
            $data[] = $row;
        }
        if($csrfToken == false){
            $output = array(
                "draw"            => 1,
                "recordsTotal"    => 0,
                "recordsFiltered" => 0,
                "data"            => array(),
            );
        }else{
            $output = array(
                "draw"            => @$_POST['draw'],
                "recordsTotal"    => $this->mEmp->count_all(),
                "recordsFiltered" => $this->mEmp->count_filtered(),
                "data"            => $data,
            );
        }
        // output to json format
        echo json_encode($output);
    }
    public function formAdd()
    {
        $data['office']         = $this->mEmp->getOffice();
        $data['company']        = $this->mEmp->getCompany();
        $data['organization']   = $this->mEmp->getOrganization();
        $data['department']     = $this->mEmp->getDepartment();
        $data['designation']    = $this->mEmp->getDesignation();
        $data['shift']          = $this->mEmp->getShift();
        $data['npwp']           = $this->mEmp->statusNPWP();
        $data['pendidikan']     = $this->mEmp->statusPendidikan();
        $data['pegawai']        = $this->mEmp->statusPegawai();
        $data['blood']          = $this->mEmp->statusBlood();
        $data['setup']          = $this->mEmp->getMasterOt();
        // $data['bank']           = $this->mEmp->getBank();
        $data['link']           = $this->link;
        $data['formact']        = $this->link.'/addEmployee';
        $data['page']           = "TAMBAH KARYAWAN";
        $data['judul']          = "KARYAWAN";
        $data['title']          = "TAMBAH KARYAWAN";
        // $data['deskripsi']  = "TAMBAH KARYAWAN";
        $params             = '{"link": "'.$this->link.'", "csrf": "'.$this->session->csrf_token.'", "permW": "'.$this->security_function->permissions($this->filename . "-w").'", "permC": "'.$this->security_function->permissions($this->filename . "-c").'", "permX": "'.$this->security_function->permissions($this->filename . "-x").'", "base_url": "'.base_url().'", "key_firebase": '.key_firebase().'}';
        // encrypt
        $encrypted          = CryptoJsAes::encrypt($params, $this->enkey);
        $data['params']     = $encrypted;
        $this->template->views('employee/v_formemployee', $data);
    }
    public function formEdit($id)
    {
        $idx                    = $this->secure->dec($id);
        $data['office']         = $this->mEmp->getOffice();
        $data['company']        = $this->mEmp->getCompany();
        $data['organization']   = $this->mEmp->getOrganization();
        $data['department']     = $this->mEmp->getDepartment();
        $data['designation']    = $this->mEmp->getDesignation();
        $data['shift']          = $this->mEmp->getShift();
        $data['npwp']           = $this->mEmp->statusNPWP();
        $data['pendidikan']     = $this->mEmp->statusPendidikan();
        $data['pegawai']        = $this->mEmp->statusPegawai();
        $data['blood']          = $this->mEmp->statusBlood();
        $data['setup']          = $this->mEmp->getMasterOt();
        // $data['bank']           = $this->mEmp->getBank();
        $data['msg']            = $this->mEmp->getEmployee($idx);
        $data['rek']            = $this->mEmp->getRekening($idx);
        $data['link']           = $this->link;
        $data['employeeId']     = $this->secure->enc($data['msg']['employee_id']);
        $data['employeeName']   = $data['msg']['employee_name'];
        $data['formact']        = $this->link.'/updateEmployee/'.$id;
        $data['page']           = "EDIT KARYAWAN";
        $data['judul']          = "KARYAWAN";
        $data['title']          = "EDIT KARYAWAN <b class='text-warning'>".strtoupper($data['employeeName'])."</b>";
        // $data['deskripsi']  = "TAMBAH KARYAWAN";
        $params             = '{"link": "'.$this->link.'", "csrf": "'.$this->session->csrf_token.'", "permW": "'.$this->security_function->permissions($this->filename . "-w").'", "permC": "'.$this->security_function->permissions($this->filename . "-c").'", "permX": "'.$this->security_function->permissions($this->filename . "-x").'", "base_url": "'.base_url().'", "employee_id": "'.$data['employeeId'].'", "employee_name": "'.$data['employeeName'].'", "key_firebase": '.key_firebase().'}';
        // encrypt
        $encrypted          = CryptoJsAes::encrypt($params, $this->enkey);
        $data['params']     = $encrypted;
        $this->template->views('employee/v_formEditEmployee', $data);
    }
    public function formDoc($id)
    {
        $idx                    = $this->secure->dec($id);
        $data['msg']            = $this->mEmp->getEmployee($idx);
        $data['link']           = $this->link;
        $data['formact']        = $this->link.'/addDocEmployee/'.$id;
        $data['employeeId']     = $this->secure->enc($data['msg']['employee_id']);
        $data['employeeName']   = $data['msg']['employee_name'];
        $data['page']           = "TAMBAH DOKUMEN";
        $data['judul']          = "DOKUMEN KARYAWAN";
        $data['title']          = "TAMBAH DOKUMEN <b class='text-warning'>".strtoupper($data['employeeName'])."</b>";
        // $data['deskripsi']  = "TAMBAH KARYAWAN";
        $params             = '{"link": "'.$this->link.'", "csrf": "'.$this->session->csrf_token.'", "permW": "'.$this->security_function->permissions($this->filename . "-w").'", "permC": "'.$this->security_function->permissions($this->filename . "-c").'", "permX": "'.$this->security_function->permissions($this->filename . "-x").'", "base_url": "'.base_url().'", "employee_id": "'.$data['employeeId'].'", "employee_name": "'.$data['employeeName'].'", "key_firebase": '.key_firebase().'}';
        // encrypt
        $encrypted          = CryptoJsAes::encrypt($params, $this->enkey);
        $data['params']     = $encrypted;
        $this->template->views('employee/v_formDocEmployee', $data);
    }
    function getDokumen($empIdx)
    {
        $empId = $this->secure->dec($empIdx);
        $list = $this->mEmp->getDokumen($empId);
        $data = '';
        $i = 0;
        foreach ($list as $item) {
            $idx = $this->secure->enc($item->idx);
            $data .= '<div class="col-lg-6 nah">
                <div class="form-group row justify-content-center mb-1">
                    <label class="col-form-label text-right text-identifier" style="display: inline-grid;">'.strtoupper($item->name_file).'<a href="javascript:void(0);" class="bPupus" title="Hapus dokumen" data-placement="left" data-popup="tooltip" onclick="pupusx(this)"><i class="icon-bin text-danger"></i></a></label>
                    <div class="col-sm-8 text-left p-3">
                        <input type="file" class="cmdX" name="cmd_'.strtolower($item->name_file).'" accept="image/*" style="display:none;" onchange="browseChange(this)">
                        <input type="hidden" class="nameFileX" name="name_file[]" value="'.strtolower($item->name_file).'">
                        <input type="hidden" class="indexX" name="index_file[]" value="'.$idx.'">
                        <input type="hidden" class="updateX" name="update_file[]" value="0">
                        <input type="hidden" class="extensionX" name="name_extension[]" value="'.$item->name_photo.'">
                        <input type="hidden" class="urlZ" name="url_'.strtolower($item->name_file).'" value="'.$item->photo_doc.'">
                        <input type="hidden" class="urlX" id="url_'.strtolower($item->name_file).'" name="url_img[]" value="'.$item->photo_doc.'">
                        <input type="hidden" class="tempX" name="temp_'.strtolower($item->name_file).'" value="0">
                        <input type="hidden" class="tempR" name="temp_image[]" value="0">
                        <img src="'.$item->photo_doc.'" class="img_'.strtolower($item->name_file).' rounded" style="width: 100%;" onclick="openBrowse(this)">
                    </div>
                </div>
            </div>';
            $i++;
        }
        $output = array(
            "recordsTotal"    => intval($i),
            "data"            => $data
        );
        echo json_encode($output);die;
    }
    function getDokumen2($empIdx)
    {
        $empId = $this->secure->dec($empIdx);
        $list = $this->mEmp->getDokumen($empId);
        $data = '';
        $i = 0;
        foreach ($list as $item) {
            $idx = $this->secure->enc($item->idx);
            $data .= '<div class="col-lg-6 nah">
                <div class="form-group row justify-content-center mb-1">
                    <label class="col-form-label text-right text-identifier" style="display: inline-grid;">'.strtoupper($item->name_file).'</label>
                    <div class="col-sm-8 text-left p-3">
                        <input type="file" class="cmdX" name="cmd_'.strtolower($item->name_file).'" accept="image/*" style="display:none;">
                        <input type="hidden" class="nameFileX" name="name_file[]" value="'.strtolower($item->name_file).'">
                        <input type="hidden" class="indexX" name="index_file[]" value="'.$idx.'">
                        <input type="hidden" class="extensionX" name="name_extension[]" value="'.$item->name_photo.'">
                        <input type="hidden" class="urlZ" name="url_'.strtolower($item->name_file).'" value="'.$item->photo_doc.'">
                        <input type="hidden" class="urlX" id="url_'.strtolower($item->name_file).'" name="url_img[]" value="'.$item->photo_doc.'">
                        <input type="hidden" class="tempX" name="temp_'.strtolower($item->name_file).'" value="0">
                        <a href="'.$item->photo_doc.'" data-fancybox data-caption="ayu">
                            <img src="'.$item->photo_doc.'" class="img_'.strtolower($item->name_file).' rounded" style="width: 100%;">
                        </a>
                    </div>
                </div>
            </div>';
            $i++;
        }
        $output = array(
            "recordsTotal"    => intval($i),
            "data"            => $data
        );
        echo json_encode($output);die;
    }
    function get_rekening()
    {
        $emp_idx    = $this->input->post('emp_id');
        $emp_id     = $this->secure->dec($emp_idx);
        $csrfName   = $this->input->post('CSRFToken');
        $csrfToken  = $this->session->csrf_token;
        // if($csrfToken != $csrfName){
        //   $list = null;
        // }else{
        //     $list = $this->mOrder->get_datatables();
        // }
        $list = $this->mEmp->get_rekening($emp_id);
        $data = array();
        $no = @$_POST['start'];
        foreach ($list as $item) {
            $idx = $this->secure->enc($item->idx);
            if(!empty($this->security_function->permissions($this->filename . "-c"))){
                $change = "<h6><a href='javascript:void(0);' id='$idx' class='bEditDetail text-center badge badge-info' data-popup='tooltip' title='Edit Detail' data-placement='left'><i class='icon-pencil5'></i></a></h6>";
            }else{
                $change = '<h6><span class="badge badge-warning"><i class="icon-lock" data-popup="tooltip" title="Locked Edit Detail" data-placement="top"></i></span></h6>';
            }
            $no++;
            if(!empty($this->security_function->permissions($this->filename . "-x"))){
                $execute  = "<h6><a href='javascript:void(0);' id='$idx' data='$no' class='bDeleteDetail text-center badge badge-danger' data-popup='tooltip' title='Delete Detail' data-placement='left'><i class='icon-bin'></i></a></h6>";
            }else{
                $execute  = '<h6><span class="bDelete badge badge-warning"><i class="icon-lock" data-popup="tooltip" title="Locked Delete Detail" data-placement="left"></i></span></h6>';
            }
            $row = array();
            $row[] = "<input type='text' value='$item->bank_name' class='form-control form-control-sm' placeholder='Bank' readonly>";
            $row[] = "<input type='text' value='$item->branch' class='form-control form-control-sm' placeholder='Cabang' readonly>";
            $row[] = "<input type='text' value='$item->account_name' class='form-control form-control-sm' placeholder='Nama Pemilik' readonly>";
            $row[] = "<input type='text' value='$item->account_id' class='form-control form-control-sm' placeholder='Nomor Rekening' readonly>";
            // $row[] = "<input type='text' value='$item->account_id' class='form-control form-control-sm' placeholder='Nomor Rekening' readonly>";
            $row[] = "<div class='btn-group'>$change$execute</div>";
            $data[] = $row;
        }
        $output = array(
            "draw"            => @$_POST['draw'],
            "recordsTotal"    => intval($this->mEmp->count_all_rekening($emp_id)),
            "recordsFiltered" => intval($this->mEmp->count_filtered_rekening($emp_id)),
            "data"            => $data
        );
        echo json_encode($output);
    }
    public function addEmployee()
    {
        // $this->form_validation->set_rules('employee_id', 'ID Karyawan', 'trim|required',[
        //     'required' => 'ID Karyawan wajib di isi.'
        // ]);
        $this->form_validation->set_rules('employee_name', 'Nama Karyawan', 'trim|required',[
            'required' => 'Nama Karyawan wajib di isi.'
        ]);
        $this->form_validation->set_rules('place_of_birth', 'Tempat Lahir', 'trim|required',[
            'required' => 'Tempat Lahir wajib di isi.'
        ]);
        $this->form_validation->set_rules('date_of_birth', 'Tanggal Lahir', 'trim|required',[
            'required' => 'Tanggal Lahir wajib di isi.'
        ]);
        if ($this->form_validation->run() == false) {
            $msg['error']   = true;
            $msg['message'] = validation_errors();
            echo json_encode($msg);die;
        }
        // $csrf = validate_csrf_token();
        // if($csrf==false){
        //     $msg['status']  = true;
        //     $msg['text']    = 'Token invalid.';
        //     echo json_encode($msg);die;
        // }
        // ini_set ('max_execution_time', '0');
        // ini_set ('memory_limit', '256M');
        // $employee_id            = filter_var(trim($this->input->post('employee_id', TRUE)), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $employee_name          = filter_var(trim($this->input->post('employee_name', TRUE)), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $place_of_birth         = filter_var(trim($this->input->post('place_of_birth', TRUE)), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $date_of_birth          = filter_var(trim($this->input->post('date_of_birth')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $gender                 = filter_var(trim($this->input->post('gender')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $maritial_status        = filter_var(trim($this->input->post('maritial_status')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $blood_type             = filter_var(trim($this->input->post('blood_type')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $religion               = filter_var(trim($this->input->post('religion')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $nationality            = filter_var(trim($this->input->post('nationality')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $country                = filter_var(trim($this->input->post('country')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $education              = filter_var(trim($this->input->post('education')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $edu_institution_name   = filter_var(trim($this->input->post('edu_institution_name')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $study_program          = filter_var(trim($this->input->post('study_program')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $id_type                = filter_var(trim($this->input->post('id_type')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $id_number              = filter_var(trim($this->input->post('id_number')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $email_id               = filter_var(trim($this->input->post('email_id')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $mobile_phone           = filter_var(trim($this->input->post('mobile_phone')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $telephone              = filter_var(trim($this->input->post('telephone')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $address                = filter_var(trim($this->input->post('address')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $province               = filter_var(trim($this->input->post('province')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $city                   = filter_var(trim($this->input->post('city')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $address_2              = filter_var(trim($this->input->post('address_2')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $province_2             = filter_var(trim($this->input->post('province_2')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $city_2                 = filter_var(trim($this->input->post('city_2')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $emergency_phone_name   = filter_var(trim($this->input->post('emergency_phone_name')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $emergency_phone        = filter_var(trim($this->input->post('emergency_phone')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $employee_status        = filter_var(trim($this->input->post('employee_status')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $join_date              = filter_var(trim($this->input->post('join_date')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $exp_date               = filter_var(trim($this->input->post('expired_date')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $organization_id        = filter_var(trim($this->input->post('organization_idx')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $work_placement         = filter_var(trim($this->input->post('work_placement')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $work_radius            = filter_var(trim($this->input->post('work_radius')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $attendance_pin         = filter_var(trim($this->input->post('attendance_pin')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $company_idx            = filter_var(trim($this->input->post('company_idx')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $office_idx             = filter_var(trim($this->input->post('work_location')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $office_shift           = filter_var(trim($this->input->post('office_shift')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $temp_image             = filter_var(trim($this->input->post('temp_image')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $url_image              = filter_var(trim($this->input->post('url_image')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $tax_status             = filter_var(trim($this->input->post('tax_status')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $tax_number             = filter_var(trim($this->input->post('tax_number')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $tax_withholder         = filter_var(trim($this->input->post('tax_withholder')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $bpjs_tenagakerja       = filter_var(trim($this->input->post('bpjs_tenagakerja')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $tenagakerja_effective_date = filter_var(trim($this->input->post('tenagakerja_effective_date')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $bpjs_kesehatan         = filter_var(trim($this->input->post('bpjs_kesehatan')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $kesehatan_effective_date   = filter_var(trim($this->input->post('kesehatan_effective_date')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $gaji_pokok             = filter_var(trim($this->input->post('basic_salary')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $um                     = filter_var(trim($this->input->post('meal_allowance')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $ut                     = filter_var(trim($this->input->post('transport_allowance')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $ot                     = filter_var(trim($this->input->post('overtime_allowance')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $ot_en_id               = filter_var(trim($this->input->post('ot_id')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $jabatan                = filter_var(trim($this->input->post('position_allowance')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $tBankName              = $this->input->post('tBankName');
        $tBranch                = $this->input->post('tBranch');
        $tOwner                 = $this->input->post('tOwner');
        $tAccount               = $this->input->post('tAccount');
        $company_id             = $this->secure->dec($company_idx);
        $office_id              = $this->secure->dec($office_idx);
        $organization_idx       = $this->secure->dec($organization_id);
        $ot_id                  = $this->secure->dec($ot_en_id);

        $gaji_pokok             = preg_replace('/[^0-9.]/', '', $gaji_pokok);
        $um                     = preg_replace('/[^0-9.]/', '', $um);
        $ut                     = preg_replace('/[^0-9.]/', '', $ut);
        $ot                     = preg_replace('/[^0-9.]/', '', $ot);
        $jabatan                = preg_replace('/[^0-9.]/', '', $jabatan);
        $work_radius            = preg_replace('/[^0-9.]/', '', $work_radius);

        $emp_id                 = $this->ModelGenId->genIdUnlimited('EMPID', $this->idx);
        $emp_code               = "KG".str_pad($emp_id,4,"0",STR_PAD_LEFT);

        $insertBank = [];
        foreach($tBankName as $i => $v){
            $dataBank = [
                'employee_id' => $emp_id,
                'bank_id' => $this->secure->dec($v),
                'branch' => filter_var(trim($tBranch[$i]), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH),
                'account_name' => filter_var(trim($tOwner[$i]), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH),
                'account_id' => filter_var(trim($tAccount[$i]), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH),
            ];
            $insertBank[] = $dataBank;
        }

        $data = [
            'employee_id'                   => $emp_id,
            'employee_name'                 => $employee_name,
            'employee_code'                 => $emp_code,
            'place_of_birth'                => $place_of_birth,
            'date_of_birth'                 => date('Y-m-d',strtotime($date_of_birth)),
            'gender'                        => $gender,
            'maritial_status'               => $maritial_status,
            'blood_type'                    => $blood_type,
            'religion'                      => $religion,
            'nationality'                   => $nationality,
            'country'                       => $country,
            'education'                     => $education,
            'edu_institution_name'          => $edu_institution_name,
            'study_program'                 => $study_program,
            'id_type'                       => $id_type,
            'id_number'                     => $id_number,
            'email_id'                      => $email_id,
            'mobile_phone'                  => $mobile_phone,
            'telephone'                     => $telephone,
            'address'                       => $address,
            'province'                      => $province,
            'city'                          => $city,
            'address_2'                     => $address_2,
            'province_2'                    => $province_2,
            'city_2'                        => $city_2,
            'emergency_phone_name'          => $emergency_phone_name,
            'emergency_phone'               => $emergency_phone,
            'employee_status'               => $employee_status,
            'join_date'                     => date('Y-m-d',strtotime($join_date)),
            'expired_date'                  => date('Y-m-d',strtotime($exp_date)),
            'organization_idx'              => $organization_idx,
            'work_placement'                => $work_placement,
            'office_shift'                  => $office_shift,
            'photo'                         => $url_image,
            'tax_status'                    => $tax_status,
            'tax_number'                    => $tax_number,
            'tax_withholder'                => $tax_withholder,
            'bpjs_tenagakerja'              => $bpjs_tenagakerja,
            'tenagakerja_effective_date'    => empty($tenagakerja_effective_date)?null:date('Y-m-d',strtotime($tenagakerja_effective_date)),
            'bpjs_kesehatan'                => $bpjs_kesehatan,
            'kesehatan_effective_date'      => empty($kesehatan_effective_date)?null:date('Y-m-d',strtotime($kesehatan_effective_date)),
            'basic_salary'                  => $gaji_pokok,
            'meal_allowance'                => $um,
            'transport_allowance'           => $ut,
            'overtime_allowance'            => $ot,
            'position_allowance'            => $jabatan,
            'attendance_radius'             => $work_radius,
            'ot_id'                         => $ot_id,
            'company_idx'                   => $company_id,
            'office_idx'                    => $office_id,
            'att_pin'                       => $attendance_pin,
            'status'                        => 1,
            'created_by'                    => $this->idx,
            'created_on'                    => date("Y-m-d H:i:s")
        ];

        // $dataCuti = [
        //     'employee_id' => $emp_id,
        //     'cuti' => 12,
        //     'year' => date('Y'),
        //     'created_by' => $this->idx,
        //     'created_on' => date('Y-m-d H:i:s')
        // ];

        // $userAccount = [
        //     'user_id'       => $employee_id,
        //     'user_name'     => $employee_name,
        //     'user_type'     => 2,
        //     'fullname'      => $employee_name,
        //     'photo'         => $url_image,
        //     'address'       => $address,
        //     'province'      => $province,
        //     'city'          => $city,
        //     'country'       => 'Indonesia',
        //     'email_id'      => $email_id,
        //     'mobile_phone'  => $mobile_phone,
        //     'telephone'     => $telephone,
        //     'employee_id'   => $emp_id,
        //     'status'        => 1,
        //     'status_delete' => 'N',
        //     'company_idx'   => $this->office,
        //     'created_by'    => $this->idx,
        //     'created_on'    => date("Y-m-d H:i:s")
        // ];
        // var_dump('<pre>');var_dump($data);var_dump($insertBank);die;
        $cek = $this->mEmp->insertEmployee($data, $insertBank);
        if($cek == true){
            $msg['success'] = true;
            $msg['text']    = 'Karyawan berhasil di tambahkan.';
            $msg['url']     = $this->link;
            echo json_encode($msg);die;
        }else{
            $msg['status']  = true;
            $msg['text']    = 'Error Connection,<br>Please try again later.';
            echo json_encode($msg);die;
        }
    }
    public function addDocEmployee()
    {
        $this->form_validation->set_rules('employee_id', 'ID Karyawan', 'trim|required',[
            'required' => 'ID Karyawan kosong.'
        ]);
        if ($this->form_validation->run() == false) {
            $msg['error']   = true;
            $msg['message'] = validation_errors();
            echo json_encode($msg);die;
        }
        $employee_id        = filter_var(trim($this->input->post('employee_id', TRUE)), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $url_img            = $this->input->post('url_img');
        $name_file          = $this->input->post('name_file');
        $id_file            = $this->input->post('index_file');
        $update_file        = $this->input->post('update_file');
        $name_extension     = $this->input->post('name_extension');
        $temp_image         = $this->input->post('temp_image');

        $emp_id = $this->secure->dec($employee_id);

        $insertDoc = [];
        $updateDoc = '';
        $cekIndex = [];
        foreach($name_file as $i => $v){
            if($temp_image[$i]==1){
                if(empty($url_img[$i])){
                    $msg['status']  = true;
                    $msg['text']    = 'Source Gambar Kosong.';
                    echo json_encode($msg);die;
                }
                if(empty($v)){
                    $msg['status']  = true;
                    $msg['text']    = 'Nama File Kosong.';
                    echo json_encode($msg);die;
                }
                $urlPhoto   = htmlentities($url_img[$i]);
                $docName    = filter_var(trim($v), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
                $fileName   = filter_var(trim($name_extension[$i]), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
                $idLogin    = $this->idx;
                $now        = date("Y-m-d H:i:s");
                if($update_file[$i]==1){
                    $docId      = $this->secure->dec($id_file[$i]);
                    $updateDoc .= "(".trim($docId).", '$urlPhoto', '$docName', '$fileName', $idLogin, '$now'),";
                }else{
                    $dataDoc = [
                        'employee_id'   => $emp_id,
                        'photo_doc'     => $urlPhoto,
                        'name_file'     => $docName,
                        'name_photo'    => $fileName,
                        'status'        => 1,
                        'created_by'    => $idLogin,
                        'created_on'    => $now
                    ];
                    $insertDoc[] = $dataDoc;
                }
            }
        }

        if(empty($updateDoc)){
            $updateDocQuery = "";
        }else{
            $updateDoc = substr($updateDoc,0,strlen($updateDoc)-1);
            $updateDocQuery = "INSERT INTO employee_attachment (idx, photo_doc, name_file, name_photo, modified_by, modified_on) values $updateDoc ON DUPLICATE KEY UPDATE photo_doc=VALUES(photo_doc), name_file=VALUES(name_file), name_photo=VALUES(name_photo), modified_by=VALUES(modified_by), modified_on=VALUES(modified_on)";
        }
        // var_dump('<pre>');var_dump($updateDocQuery);die;
        if(empty($insertDoc)){
            $cek = $this->mEmp->updateDocEmployee($updateDocQuery);
        }else{
            $cek = $this->mEmp->insertDocEmployee($insertDoc, $updateDocQuery);
        }
        if($cek == true){
            $msg['success'] = true;
            $msg['text']    = 'Dokumen Karyawan berhasil di tambahkan.';
            $msg['url']     = $this->link;
            echo json_encode($msg);die;
        }else{
            $msg['status']  = true;
            $msg['text']    = 'Error Connection,<br>Please try again later.';
            echo json_encode($msg);die;
        }
    }
    public function deleteDocEmployee()
    {
        $id = $this->input->get('id');
        $idx = $this->secure->dec($id);

        $dataDelete = [
            'status' => 0,
            'modified_by' => $this->idx,
            'modified_on' => date("Y-m-d H:i:s")
        ];

        $cek = $this->mEmp->deleteDocEmployee($dataDelete, $idx);
        if($cek == true){
            $msg['success'] = true;
            $msg['text']    = 'Dokumen Karyawan berhasil di hapus.';
            $msg['url']     = $this->link;
            echo json_encode($msg);die;
        }else{
            $msg['status']  = true;
            $msg['text']    = 'Error Connection,<br>Please try again later.';
            echo json_encode($msg);die;
        }
    }
    public function updateEmployee($idx)
    {
        $id = $this->secure->dec($idx);
        $this->form_validation->set_rules('employee_id', 'ID Karyawan', 'trim|required',[
            'required' => 'ID Karyawan wajib di isi.'
        ]);
        $this->form_validation->set_rules('employee_name', 'Nama Karyawan', 'trim|required',[
            'required' => 'Nama Karyawan wajib di isi.'
        ]);
        $this->form_validation->set_rules('place_of_birth', 'Tempat Lahir', 'trim|required',[
            'required' => 'Tempat Lahir wajib di isi.'
        ]);
        $this->form_validation->set_rules('date_of_birth', 'Tanggal Lahir', 'trim|required',[
            'required' => 'Tanggal Lahir wajib di isi.'
        ]);
        if ($this->form_validation->run() == false) {
            $msg['error']   = true;
            $msg['message'] = validation_errors();
            echo json_encode($msg);die;
        }
        // $csrf = validate_csrf_token();
        // if($csrf==false){
        //     $msg['status']  = true;
        //     $msg['text']    = 'Token invalid.';
        //     echo json_encode($msg);die;
        // }
        // ini_set ('max_execution_time', '0');
        // ini_set ('memory_limit', '256M');
        $emp_idx                = filter_var(trim($this->input->post('emp_id', TRUE)), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $employee_id            = filter_var(trim($this->input->post('employee_id', TRUE)), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $employee_name          = filter_var(trim($this->input->post('employee_name', TRUE)), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $place_of_birth         = filter_var(trim($this->input->post('place_of_birth', TRUE)), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $date_of_birth          = filter_var(trim($this->input->post('date_of_birth')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $gender                 = filter_var(trim($this->input->post('gender')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $maritial_status        = filter_var(trim($this->input->post('maritial_status')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $blood_type             = filter_var(trim($this->input->post('blood_type')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $religion               = filter_var(trim($this->input->post('religion')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $nationality            = filter_var(trim($this->input->post('nationality')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $country                = filter_var(trim($this->input->post('country')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $education              = filter_var(trim($this->input->post('education')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $edu_institution_name   = filter_var(trim($this->input->post('edu_institution_name')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $study_program          = filter_var(trim($this->input->post('study_program')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $id_type                = filter_var(trim($this->input->post('id_type')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $id_number              = filter_var(trim($this->input->post('id_number')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $email_id               = filter_var(trim($this->input->post('email_id')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $mobile_phone           = filter_var(trim($this->input->post('mobile_phone')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $telephone              = filter_var(trim($this->input->post('telephone')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $address                = filter_var(trim($this->input->post('address')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $province               = filter_var(trim($this->input->post('province')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $city                   = filter_var(trim($this->input->post('city')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $address_2              = filter_var(trim($this->input->post('address_2')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $province_2             = filter_var(trim($this->input->post('province_2')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $city_2                 = filter_var(trim($this->input->post('city_2')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $emergency_phone_name   = filter_var(trim($this->input->post('emergency_phone_name')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $emergency_phone        = filter_var(trim($this->input->post('emergency_phone')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $employee_status        = filter_var(trim($this->input->post('employee_status')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $join_date              = filter_var(trim($this->input->post('join_date')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $exp_date               = filter_var(trim($this->input->post('expired_date')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $organization_id       = filter_var(trim($this->input->post('organization_idx')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $department_id          = filter_var(trim($this->input->post('department_idx')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $designation_id         = filter_var(trim($this->input->post('designation_idx')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $work_placement         = filter_var(trim($this->input->post('work_placement')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $work_radius            = filter_var(trim($this->input->post('work_radius')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $attendance_pin         = filter_var(trim($this->input->post('attendance_pin')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $company_idx            = filter_var(trim($this->input->post('company_idx')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $office_idx             = filter_var(trim($this->input->post('work_location')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $office_shift           = filter_var(trim($this->input->post('office_shift')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $temp_image             = filter_var(trim($this->input->post('temp_image')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $url_image              = filter_var(trim($this->input->post('url_image')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $tax_status             = filter_var(trim($this->input->post('tax_status')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $tax_number             = filter_var(trim($this->input->post('tax_number')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $tax_withholder         = filter_var(trim($this->input->post('tax_withholder')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $bpjs_tenagakerja       = filter_var(trim($this->input->post('bpjs_tenagakerja')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $tenagakerja_effective_date = filter_var(trim($this->input->post('tenagakerja_effective_date')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $bpjs_kesehatan         = filter_var(trim($this->input->post('bpjs_kesehatan')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $kesehatan_effective_date   = filter_var(trim($this->input->post('kesehatan_effective_date')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $gaji_pokok             = filter_var(trim($this->input->post('basic_salary')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $um                     = filter_var(trim($this->input->post('meal_allowance')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $ut                     = filter_var(trim($this->input->post('transport_allowance')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $ot                     = filter_var(trim($this->input->post('overtime_allowance')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $ot_en_id               = filter_var(trim($this->input->post('ot_id')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $jabatan                = filter_var(trim($this->input->post('position_allowance')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

        $emp_id                 = $this->secure->dec($emp_idx);
        $company_id             = $this->secure->dec($company_idx);
        $office_id              = $this->secure->dec($office_idx);
        $organization_idx       = $this->secure->dec($organization_id);
        $ot_id                  = $this->secure->dec($ot_en_id);
        
        $gaji_pokok             = preg_replace('/[^0-9.]/', '', $gaji_pokok);
        $um                     = preg_replace('/[^0-9.]/', '', $um);
        $ut                     = preg_replace('/[^0-9.]/', '', $ut);
        $ot                     = preg_replace('/[^0-9.]/', '', $ot);
        $jabatan                = preg_replace('/[^0-9.]/', '', $jabatan);
        $work_radius            = preg_replace('/[^0-9.]/', '', $work_radius);

        $cekExist               = $this->mEmp->getEmployee($id);
        if(empty($cekExist)){
            $msg['status']  = true;
            $msg['text']    = 'Karyawan tidak ditemukan.';
            echo json_encode($msg);die;
        }

        $company_old_id = $cekExist['company_idx'];

        if($company_old_id == $company_id){
            $data = [
                'employee_name'                 => $employee_name,
                // 'employee_code'                 => $employee_id,
                'place_of_birth'                => $place_of_birth,
                'date_of_birth'                 => date('Y-m-d',strtotime($date_of_birth)),
                'gender'                        => $gender,
                'maritial_status'               => $maritial_status,
                'blood_type'                    => $blood_type,
                'religion'                      => $religion,
                'nationality'                   => $nationality,
                'country'                       => $country,
                'education'                     => $education,
                'edu_institution_name'          => $edu_institution_name,
                'study_program'                 => $study_program,
                'id_type'                       => $id_type,
                'id_number'                     => $id_number,
                'email_id'                      => $email_id,
                'mobile_phone'                  => $mobile_phone,
                'telephone'                     => $telephone,
                'address'                       => $address,
                'province'                      => $province,
                'city'                          => $city,
                'address_2'                     => $address_2,
                'province_2'                    => $province_2,
                'city_2'                        => $city_2,
                'emergency_phone_name'          => $emergency_phone_name,
                'emergency_phone'               => $emergency_phone,
                'employee_status'               => $employee_status,
                'join_date'                     => date('Y-m-d',strtotime($join_date)),
                'expired_date'                  => date('Y-m-d',strtotime($exp_date)),
                'organization_idx'              => $organization_idx,
                'work_placement'                => $work_placement,
                'attendance_radius'             => $work_radius,
                'att_pin'                       => $attendance_pin,
                'office_shift'                  => $office_shift,
                'office_idx'                    => $office_id,
                'company_idx'                   => $company_id,
                'photo'                         => $url_image,
                'tax_status'                    => $tax_status,
                'tax_number'                    => $tax_number,
                'tax_withholder'                => $tax_withholder,
                'bpjs_tenagakerja'              => $bpjs_tenagakerja,
                'tenagakerja_effective_date'    => date('Y-m-d',strtotime($tenagakerja_effective_date)),
                'bpjs_kesehatan'                => $bpjs_kesehatan,
                'kesehatan_effective_date'      => date('Y-m-d',strtotime($kesehatan_effective_date)),
                'basic_salary'                  => $gaji_pokok,
                'meal_allowance'                => $um,
                'transport_allowance'           => $ut,
                'overtime_allowance'            => $ot,
                'ot_id'                         => $ot_id,
                'position_allowance'            => $jabatan,
                'status'                        => 1,
                'modified_by'                   => $this->idx,
                'modified_on'                   => date("Y-m-d H:i:s")
            ];
    
            $updateAtt = [];
        }else{
            $getOffice              = $this->mEmp->getOfficeByCompany($company_id);
            if(empty($getOffice['company_name'])){
                $msg['status']  = true;
                $msg['text']    = 'Company tidak ditemukan.';
                echo json_encode($msg);die;
            }
    
            if(empty($getOffice['data_office'])){
                $msg['status']  = true;
                $msg['text']    = '<b>'.$getOffice['company_name'].'</b> belum mempunyai office.';
                echo json_encode($msg);die;
            }

            $office_new_idx = $getOffice['data_office']['idx'];
            
            $data = [
                'employee_name'                 => $employee_name,
                // 'employee_code'                 => $employee_id,
                'place_of_birth'                => $place_of_birth,
                'date_of_birth'                 => date('Y-m-d',strtotime($date_of_birth)),
                'gender'                        => $gender,
                'maritial_status'               => $maritial_status,
                'blood_type'                    => $blood_type,
                'religion'                      => $religion,
                'nationality'                   => $nationality,
                'country'                       => $country,
                'education'                     => $education,
                'edu_institution_name'          => $edu_institution_name,
                'study_program'                 => $study_program,
                'id_type'                       => $id_type,
                'id_number'                     => $id_number,
                'email_id'                      => $email_id,
                'mobile_phone'                  => $mobile_phone,
                'telephone'                     => $telephone,
                'address'                       => $address,
                'province'                      => $province,
                'city'                          => $city,
                'address_2'                     => $address_2,
                'province_2'                    => $province_2,
                'city_2'                        => $city_2,
                'emergency_phone_name'          => $emergency_phone_name,
                'emergency_phone'               => $emergency_phone,
                'employee_status'               => $employee_status,
                'join_date'                     => date('Y-m-d',strtotime($join_date)),
                'expired_date'                  => date('Y-m-d',strtotime($exp_date)),
                'organization_idx'              => $organization_idx,
                'work_placement'                => $work_placement,
                'attendance_radius'             => $work_radius,
                'att_pin'                       => $attendance_pin,
                'office_shift'                  => $office_shift,
                'office_idx'                    => $office_new_idx,
                'company_idx'                   => $company_id,
                'photo'                         => $url_image,
                'tax_status'                    => $tax_status,
                'tax_number'                    => $tax_number,
                'tax_withholder'                => $tax_withholder,
                'bpjs_tenagakerja'              => $bpjs_tenagakerja,
                'tenagakerja_effective_date'    => date('Y-m-d',strtotime($tenagakerja_effective_date)),
                'bpjs_kesehatan'                => $bpjs_kesehatan,
                'kesehatan_effective_date'      => date('Y-m-d',strtotime($kesehatan_effective_date)),
                'basic_salary'                  => $gaji_pokok,
                'meal_allowance'                => $um,
                'transport_allowance'           => $ut,
                'overtime_allowance'            => $ot,
                'ot_id'                         => $ot_id,
                'position_allowance'            => $jabatan,
                'status'                        => 1,
                'modified_by'                   => $this->idx,
                'modified_on'                   => date("Y-m-d H:i:s")
            ];
    
            $updateAtt = [
                'company_idx'   => $company_id,
                'modified_by'   => $this->idx,
                'modified_on'   => date("Y-m-d H:i:s")
            ];
        }

        $cek = $this->mEmp->updateEmployee($id, $data, $emp_id, $updateAtt);
        if($cek == true){
            $msg['success'] = true;
            $msg['text']    = 'Karyawan berhasil di ubah.';
            $msg['url']     = $this->link;
            echo json_encode($msg);die;
        }else{
            $msg['status']  = true;
            $msg['text']    = 'Error Connection,<br>Please try again later.';
            echo json_encode($msg);die;
        }
    }
    public function deleteEmployee()
    {
        $idx = $this->input->get('id');
        $id = $this->secure->dec($idx);
        
        $cek = $this->mEmp->deleteEmployee($id);
        if($cek == true){
            $msg['success'] = true;
            $msg['text']    = 'Karyawan berhasil di hapus.';
            $msg['url']     = $this->link;
            echo json_encode($msg);die;
        }else{
            $msg['status']  = true;
            $msg['text']    = 'Error Connection,<br>Please try again later.';
            echo json_encode($msg);die;
        }
    }
    public function addBank()
    {
        $this->form_validation->set_rules('employee_idx', 'Employee Index', 'trim|required',[
            'required' => 'Index Karyawan wajib di isi.'
        ]);
        $this->form_validation->set_rules('tBankName', 'Bank', 'trim|required',[
            'required' => 'Bank wajib di pilih.'
        ]);
        $this->form_validation->set_rules('tBranch', 'Cabang', 'trim|required',[
            'required' => 'Cabang wajib di isi.'
        ]);
        $this->form_validation->set_rules('tOwner', 'Nama Pemilik', 'trim|required',[
            'required' => 'Nama Pemilik wajib di isi.'
        ]);
        $this->form_validation->set_rules('tAccount', 'Nomor Rekening', 'trim|required',[
            'required' => 'Nomor Rekening wajib di isi.'
        ]);
        if ($this->form_validation->run() == false) {
            $msg['error']   = true;
            $msg['message'] = validation_errors();
            echo json_encode($msg);die;
        }
        $csrf = validate_csrf_token();
        if($csrf==false){
            $msg['status']  = true;
            $msg['text']    = 'Token invalid.';
            echo json_encode($msg);die;
        }

        $emp_id     = $this->secure->dec($this->input->post('employee_idx'));
        $tBankName  = $this->secure->dec($this->input->post('tBankName'));
        $tBranch    = $this->input->post('tBranch');
        $tOwner     = $this->input->post('tOwner');
        $tAccount   = $this->input->post('tAccount');

        $data = [
            'employee_id'   => $emp_id,
            'bank_id'       => $tBankName,
            'branch'        => $tBranch,
            'account_name'  => $tOwner,
            'account_id'    => $tAccount,
            'created_by'    => $this->idx,
            'created_on'    => date('Y-m-d H:i:s')
        ];
        $result = $this->mEmp->insertRekening($data);
        if($result==1){
            $msg['success']     = true;
            $msg['text']        = 'Sukses tambah rekening baru.';
            echo json_encode($msg);die;
        }else{
            $msg['status']  = true;
            $msg['text']    = 'Gagal tambah rekening baru.';
            echo json_encode($msg);die;
        }
    }

    public function updateBank($id)
    {
        $idx = $this->secure->dec($id);
        $this->form_validation->set_rules('employee_idx', 'Employee Index', 'trim|required',[
            'required' => 'Index Karyawan wajib di isi.'
        ]);
        $this->form_validation->set_rules('tBankName', 'Bank', 'trim|required',[
            'required' => 'Bank wajib di pilih.'
        ]);
        $this->form_validation->set_rules('tBranch', 'Cabang', 'trim|required',[
            'required' => 'Cabang wajib di isi.'
        ]);
        $this->form_validation->set_rules('tOwner', 'Nama Pemilik', 'trim|required',[
            'required' => 'Nama Pemilik wajib di isi.'
        ]);
        $this->form_validation->set_rules('tAccount', 'Nomor Rekening', 'trim|required',[
            'required' => 'Nomor Rekening wajib di isi.'
        ]);
        if ($this->form_validation->run() == false) {
            $msg['error']   = true;
            $msg['message'] = validation_errors();
            echo json_encode($msg);die;
        }
        $csrf = validate_csrf_token();
        if($csrf==false){
            $msg['status']  = true;
            $msg['text']    = 'Token invalid.';
            echo json_encode($msg);die;
        }

        // $emp_id     = $this->secure->dec($this->input->post('employee_idx'));
        $tBankName  = $this->secure->dec($this->input->post('tBankName'));
        $tBranch    = $this->input->post('tBranch');
        $tOwner     = $this->input->post('tOwner');
        $tAccount   = $this->input->post('tAccount');

        $data = [
            // 'employee_id'   => $emp_id,
            'bank_id'       => $tBankName,
            'branch'        => $tBranch,
            'account_name'  => $tOwner,
            'account_id'    => $tAccount,
            'modified_by'   => $this->idx,
            'modified_on'   => date('Y-m-d H:i:s')
        ];
        $result = $this->mEmp->updateRekening($data, $idx);
        if($result==1){
            $msg['success']     = true;
            $msg['text']        = 'Sukses ubah rekening baru.';
            echo json_encode($msg);die;
        }else{
            $msg['status']  = true;
            $msg['text']    = 'Gagal ubah rekening baru.';
            echo json_encode($msg);die;
        }
    }
    public function getBank()
    {
        $search     = trim($this->input->post('search',true));
        $getBank    = $this->mEmp->getBank($search, 'data');
        $getCBank   = $this->mEmp->getBank($search, 'count');
        $get = array();
        foreach($getBank as $d){
            $data = [
                'id' => $this->secure->enc($d->idx),
                'text' => $d->bank_name
            ];
            $get[] = $data;
        }
        $result['items'] = $get;
        $result['search'] = $search;
        $this->output->set_content_type('application/json')->set_output(json_encode($result));
    }
    public function getBankId()
    {
        $idx        = $this->secure->dec($this->input->get('id',true));
        $getBank    = $this->mEmp->getBankId($idx, 'data');
        $getCBank   = $this->mEmp->getBankId($idx, 'count');
        $get = array();
        foreach($getBank as $d){
            $data = [
                'id' => $this->secure->enc($d['idx']),
                'text' => $d['bank_name']
            ];
            $get[] = $data;
        }
        $result['items'] = $get;
        $this->output->set_content_type('application/json')->set_output(json_encode($result));
    }
    public function getDataRekening()
    {
        $id     = $this->input->get('id');
        $idx    = $this->secure->dec($id);
        $data   = $this->mEmp->getDataRekening($idx);
        if($data){
            $inj = [
                'enidx' => $id,
                'bank_enid' => $this->secure->enc($data['bank_id'])
            ];
            $result = array_merge($data, $inj);
        }else{
            $result = null;
        }
        echo json_encode($result);die;
    }
    public function updatePosition($id)
    {
        $idx = $this->secure->dec($id);
        $this->form_validation->set_rules('dept_idx', 'Organisasi', 'trim|required',[
            'required' => 'Organisasi wajib di pilih.'
        ]);
        $this->form_validation->set_rules('designation_name', 'Nama Jabatan', 'trim|required',[
            'required' => 'Nama Jabatan wajib di isi.'
        ]);
        $this->form_validation->set_rules('description', 'Deskripsi', 'trim|required',[
            'required' => 'Deskripsi wajib di isi.'
        ]);
        if ($this->form_validation->run() == false) {
            $msg['error']   = true;
            $msg['message'] = validation_errors();
            echo json_encode($msg);die;
        }
        $csrf = validate_csrf_token();
        if($csrf==false){
            $msg['status']  = true;
            $msg['text']    = 'Token invalid.';
            echo json_encode($msg);die;
        }
        // ini_set ('max_execution_time', '0');
        // ini_set ('memory_limit', '256M');
        $designation_name   = trim($this->input->post('designation_name', TRUE));
        $dept_idx           = $this->secure->dec(trim($this->input->post('dept_idx', TRUE)));
        $description        = trim($this->input->post('description', TRUE));
        $status             = trim($this->input->post('tStatus'));

        $data = [
            'designation_name'  => $designation_name,
            'dept_idx'          => $dept_idx,
            'description'       => $description,
            'status'            => $status,
            'modified_by'       => $this->idx,
            'modified_on'       => date("Y-m-d H:i:s")
        ];
        $cek = $this->mPos->updateDesignation($data, $idx);
        if($cek == true){
            $msg['success'] = true;
            $msg['text']    = 'Jabatan berhasil diubah.';
            echo json_encode($msg);die;
        }else{
            $msg['status']  = true;
            $msg['text']    = 'Error Connection,<br>Please try again later.';
            echo json_encode($msg);die;
        }
    }
    public function changeStatus()
    {
        $idx = $this->secure->dec($this->input->post('tId'));
        $id_status = $this->input->post('tClCode');
        $csrfToken = validate_csrf_token();
        // var_dump($csrfToken);die;
        if($csrfToken == FALSE){
            $msg['status']   = true;
            $msg['text']     = 'Please refresh page and try again.!';
            echo json_encode($msg);die;
        }
        if($id_status == '1'){
            $changeId = 0;
        } else {
            $changeId = 1;
        }
        $data = [
            'status'      => $changeId,
            'modified_by' => $this->idx,
            'modified_on' => date("Y-m-d H:i:s")
        ];
        $cek = $this->mEmp->changeStatus($data, $idx);
        if($cek == true)
        {
            $msg['type']    = 'change';
            $msg['success'] = true;
            echo json_encode($msg);die;
        }else{
            $msg['status']   = true;
            echo json_encode($msg);die;
        }
    }
    public function editDesignation()
    {
        $id     = $this->secure->dec($this->input->get('id'));
        $result = $this->mPos->editDesignation($id);
        if($result){
            $push = [
                'en_idx' => $this->secure->enc($result['idx']),
                'dept_enidx' => $this->secure->enc($result['dept_idx']),
            ];
            $final = array_merge($push,$result);
        }else{
            $final = [];
        }
        echo json_encode($final);
    }
    public function createAsUser()
    {
        $id_employee = $this->secure->dec($this->input->post('tId'));
        $employee_name = $this->input->post('tClCode');
        $data = [
            'status_user_apps' => 1,
            'modified_by' => $this->idx,
            'modified_on' => date("Y-m-d H:i:s")
        ];
        $cek = $this->mEmp->createAsUser($data, $id_employee);
        if($cek == true)
        {
            $msg['success']     = true;
            $msg['text']        = '<b class="text-info">'.$employee_name.'</b> Create user berhasil.';
            echo json_encode($msg);die;
        }else{
            $msg['status']  = true;
            $msg['text']    = 'Gagal Create user.';
            echo json_encode($msg);die;
        }
    }
}
/* End of file user.php */
/* Location: ./application/controllers/user.php */
