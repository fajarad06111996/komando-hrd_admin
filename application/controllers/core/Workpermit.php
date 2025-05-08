<?php
defined('BASEPATH') or exit('No direct script access allowed');
require "vendor/autoload.php";
use Nullix\CryptoJsAes\CryptoJsAes;
class Workpermit extends AUTH_Controller
{
    public function __construct()
    {
        parent::__construct();
        // $this->load->model('core/M_position', 'mPos');
        // $this->load->model('ModelGenId');
        $this->load->library('security_function');
        $this->link	    = site_url('core/').strtolower(get_class($this));
        $this->filename = strtolower(get_class($this));
        $this->office   = $this->secure->dec($this->session->userdata('JToffice_id'));
        $this->tOffice  = $this->secure->dec($this->session->userdata('JToffice_type'));
        $this->hub      = $this->secure->dec($this->session->userdata('JThub_id'));
        $this->idx      = $this->secure->dec($this->session->userdata('JTidx'));
        $this->username = $this->session->userdata('JTuser_id');
        $this->enkey    = $this->config->item('encryption_key');
        // $this->_ci =&get_instance();

    }
    public function index()
    {
        $access = $this->security_function->permissions($this->filename . "-r");
        if (empty($access)) die("<center><h1>You can not get the right to access this module</h1></center>");
        if(!empty($this->security_function->permissions($this->filename . "-w"))){
            $data['write']  = '<a href="javascript:void(0);" id="btnAdd" class="pull-right text-white small" title="Tambah Jabatan" data-placement="right" data-popup="tooltip"><i class="icon-plus-circle2"></i></a>';
            $data['lock']   = 1;
        }else{
            $data['write']  = '<i class="icon-lock pull-right pt-1 " title="Tambah Jabatan Terkunci" data-placement="right" data-popup="tooltip"></i>';
            $data['lock']   = 0;
        }
        $data['access']     = $access;
        // $data['department'] = $this->mPos->getDepartment();
        $data['filename']   = $this->filename;
        $data["link"]       = $this->link;
        $params             = '{"link": "'.$this->link.'", "csrf": "'.$this->session->csrf_token.'", "permC": "'.$this->security_function->permissions($this->filename . "-c").'", "permX": "'.$this->security_function->permissions($this->filename . "-x").'"}';
        $encrypted          = CryptoJsAes::encrypt($params, $this->enkey);
        $data['params']     = $encrypted;
        $this->template->views('core/v_workpermit', $data);
    }
    // CONTOH LAIN CONTROLLER2 POST DATATABLE SERVERSIDE
    function get_ajax()
    {
        $csrfToken  = validate_csrf_token();
        if($csrfToken == false){
            $list = array();
        }else{
            $list = $this->mPos->get_datatables();
        }
        $data = array();
        $no = @$_POST['start'];
        foreach ($list as $item) {
            $idx            = $this->secure->enc($item->idx);
            if(!empty($this->security_function->permissions($this->filename . "-w"))){
            }else{
            }
            if(!empty($this->security_function->permissions($this->filename . "-c"))){
                $change = "<h5 class='m-0'><a href='javascript:void(0);' id='$idx' class='bEdit text-center badge badge-info' data-popup='tooltip' title='Edit Jabatan' data-placement='right'><i class='icon-pencil5'></i></a></h5>";
                $setup = "<h5 class='m-0'><a href='javascript:void(0);' id='$idx' class='bSetup text-center badge badge-primary' data-popup='tooltip' title='Toleransi Kehadiran' data-placement='left'><i class='icon-folder-search'></i></a></h5>";
                if($item->status == 1)
                {
                    $statusU = "<h5><a href='javascript:void(0);' id='$idx' data='$item->designation_name' code='1' class='bStatus text-center badge badge-success' data-popup='tooltip' title='Ubah Status' data-placement='right'>Aktif</a></h5>";
                // $statusU = '<span class="badge badge-success">Aktif</span>';
                } else {
                    $statusU = "<h5><a href='javascript:void(0);' id='$idx' data='$item->designation_name' code='0' class='bStatus text-center badge badge-danger' data-popup='tooltip' title='Ubah Status' data-placement='right'>Non Aktif</a></h5>";
                // $statusU = '<span class="badge badge-danger">Non Aktif</span>';
                }
            }else{
                $change = '<h5 class="m-0"><span class="badge badge-warning bLockStatus"><i class="icon-lock" data-popup="tooltip" title="Locked Edit Jabatan" data-placement="right"></i></span></h5>';
                $setup = '<h5 class="m-0"><span class="badge badge-warning bLockStatus"><i class="icon-lock" data-popup="tooltip" title="Locked Toleransi Kehadiran" data-placement="left"></i></span></h5>';
                if($item->status == 1)
                {
                    $statusU = "<span class='text-center badge badge-success'>Aktif</span>";
                }else{
                    $statusU = "<span class='text-center badge badge-danger'>Non Aktif</span>";
                }
            }
            if(!empty($this->security_function->permissions($this->filename . "-x"))){
                $execute  = "<h5 class='m-0'><a href='javascript:void(0);' id='$idx' data='$item->designation_name' class='bDelete text-center badge badge-danger' data-popup='tooltip' title='Hapus Jabatan' data-placement='right'><i class='icon-bin'></i></a></h5>";
            }else{
                $execute  = '<h5 class="m-0"><span class="badge badge-warning"><i class="icon-lock" data-popup="tooltip" title="Hapus Jabatan Terkunci" data-placement="right"></i></span></h5>';
            }
            $no++;
            $row = array();
            $row[] = $no.".";
            $row[] = "<div class='btn-group'>
                        <button type='button' class='btn btn-danger btn-sm dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'><i class='icon-more2'></i></button>
                        <div class='dropdown-menu p-2' style='min-width: auto !important;'>
                            <div class='btn-group'>$change&nbsp;$execute</div>
                        </div>
                    </div>";
            $row[] = $statusU;
            $row[] = $item->department_name;
            $row[] = $item->designation_name;
            $row[] = $item->description;
            // add html for action
            $data[] = $row;
        }
        $output = array(
            "draw"            => @$_POST['draw'],
            "recordsTotal"    => $this->mPos->count_all(),
            "recordsFiltered" => $this->mPos->count_filtered(),
            "data"            => $data,
        );
        // output to json format
        echo json_encode($output);
    }
    public function setupTolerance($id)
    {
        $idx = $this->secure->dec($id);
        $access = $this->security_function->permissions($this->filename . "-r");
        if (empty($access)) die("<center><h1>You can not get the right to access this module</h1></center>");
        if(!empty($this->security_function->permissions($this->filename . "-w"))){
            $backButton = '<a href="'.$this->link.'" class="list-icons-item" title="Back To List Data" data-placement="right" data-popup="tooltip"><i class="icon-undo2 pr-1"></i></a>';
            $addButton = '<a href="javascript:void(0);" id="btnAdd" class="list-icons-item" title="Tambah Jabatan" data-placement="right" data-popup="tooltip"><i class="icon-plus-circle2"></i></a>';
            $data['write']  = "<div class='list-icons'>$backButton$addButton</div>";
            $data['lock']   = 1;
        }else{
            $backButton = '<a href="'.$this->link.'" class="list-icons-item" title="Back To List Data" data-placement="right" data-popup="tooltip"><i class="icon-undo2 pr-1"></i></a>';
            $addButton = '<span class="list-icons-item" title="Tambah Jabatan Terkunci" data-placement="right" data-popup="tooltip"><i class="icon-lock"></i></span>';
            $data['write']  = "<div class='list-icons'>$backButton$addButton</div>";
            $data['lock']   = 0;
        }
        $designation = $this->mPos->getDesignationName($idx);
        $data['designation_idx']    = $id;
        $data['designation_name']   = $designation->designation_name;
        $data['filename']           = $this->filename;
        $data["link"]               = $this->link;
        $params                     = '{"link": "'.$this->link.'", "csrf": "'.$this->session->csrf_token.'", "permC": "'.$this->security_function->permissions($this->filename . "-c").'", "permX": "'.$this->security_function->permissions($this->filename . "-x").'", "designation_idx": "'.$id.'", "designation_name": "'.$designation->designation_name.'"}';
        $encrypted                  = CryptoJsAes::encrypt($params, $this->enkey);
        $data['params']             = $encrypted;
        $this->template->views('core/v_setup_tolerance', $data);
    }
    // CONTOH LAIN CONTROLLER2 POST DATATABLE SERVERSIDE
    function get_tolerance()
    {
        $desgIdx    = $this->input->post('designation_idx');
        $desg_idx   = $this->secure->dec($desgIdx);
        $csrfToken  = validate_csrf_token();
        if($csrfToken == false){
            $list = array();
        }else{
            $list = $this->mPos->get_dataTolerance($desg_idx);
        }
        // var_dump('<pre>');var_dump($list);die;
        $data = array();
        $no = @$_POST['start'];
        foreach ($list as $item) {
            $idx            = $this->secure->enc($item->idx);
            if(!empty($this->security_function->permissions($this->filename . "-w"))){
            }else{
            }
            if(!empty($this->security_function->permissions($this->filename . "-c"))){
                $change = "<h5 class='m-0'><a href='javascript:void(0);' id='$idx' class='bEdit text-center badge badge-info' data-popup='tooltip' title='Edit Jabatan' data-placement='right'><i class='icon-pencil5'></i></a></h5>";
                $setup = "<h5 class='m-0'><a href='javascript:void(0);' id='$idx' class='bSetup text-center badge badge-primary' data-popup='tooltip' title='Toleransi Kehadiran' data-placement='left'><i class='icon-folder-search'></i></a></h5>";
                if($item->status == 1)
                {
                    $statusU = "<h5><a href='javascript:void(0);' id='$idx' data='$item->designation_name' code='1' class='bStatus text-center badge badge-success' data-popup='tooltip' title='Ubah Status' data-placement='right'>Aktif</a></h5>";
                // $statusU = '<span class="badge badge-success">Aktif</span>';
                } else {
                    $statusU = "<h5><a href='javascript:void(0);' id='$idx' data='$item->designation_name' code='0' class='bStatus text-center badge badge-danger' data-popup='tooltip' title='Ubah Status' data-placement='right'>Non Aktif</a></h5>";
                // $statusU = '<span class="badge badge-danger">Non Aktif</span>';
                }
            }else{
                $change = '<h5 class="m-0"><span class="badge badge-warning bLockStatus"><i class="icon-lock" data-popup="tooltip" title="Locked Edit Jabatan" data-placement="right"></i></span></h5>';
                $setup = '<h5 class="m-0"><span class="badge badge-warning bLockStatus"><i class="icon-lock" data-popup="tooltip" title="Locked Toleransi Kehadiran" data-placement="left"></i></span></h5>';
                if($item->status == 1)
                {
                    $statusU = "<span class='text-center badge badge-success'>Aktif</span>";
                }else{
                    $statusU = "<span class='text-center badge badge-danger'>Non Aktif</span>";
                }
            }
            if(!empty($this->security_function->permissions($this->filename . "-x"))){
                $execute  = "<h5 class='m-0'><a href='javascript:void(0);' id='$idx' data='$item->designation_name' class='bDelete text-center badge badge-danger' data-popup='tooltip' title='Hapus Jabatan' data-placement='right'><i class='icon-bin'></i></a></h5>";
            }else{
                $execute  = '<h5 class="m-0"><span class="badge badge-warning"><i class="icon-lock" data-popup="tooltip" title="Hapus Jabatan Terkunci" data-placement="right"></i></span></h5>';
            }
            $no++;
            $row = array();
            $row[] = $no.".";
            $row[] = "<div class='btn-group'>$change$execute</div>";
            $row[] = $statusU;
            $row[] = $item->tolerance_in_start.'-'.$item->tolerance_in_end.' Menit';
            $row[] = $item->tolerance_out_start.'-'.$item->tolerance_out_end.' Menit';
            $row[] = $item->description;
            // add html for action
            $data[] = $row;
        }
        $output = array(
            "draw"            => @$_POST['draw'],
            "recordsTotal"    => (int)$this->mPos->tolerance_all($desg_idx),
            "recordsFiltered" => (int)$this->mPos->tolerance_filtered($desg_idx),
            "data"            => $data,
        );
        // output to json format
        echo json_encode($output);
    }
    public function addPosition()
    {
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
            'created_by'        => $this->idx,
            'created_on'        => date("Y-m-d H:i:s")
        ];
        $cek = $this->mPos->insertDesignation($data);
        if($cek == true){
            $msg['success'] = true;
            $msg['text']    = 'Jabatan berhasil di tambahkan.';
            echo json_encode($msg);die;
        }else{
            $msg['status']  = true;
            $msg['text']    = 'Error Connection,<br>Please try again later.';
            echo json_encode($msg);die;
        }
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
    public function addTolerance()
    {
        $this->form_validation->set_rules('in_start', 'Toleransi Masuk Awal', 'trim|required',[
            'required' => 'Toleransi Masuk Awal wajib di pilih.'
        ]);
        $this->form_validation->set_rules('in_end', 'Toleransi Masuk Akhir', 'trim|required',[
            'required' => 'Toleransi Masuk Akhir wajib di pilih.'
        ]);
        $this->form_validation->set_rules('out_start', 'Toleransi Pulang Awal', 'trim|required',[
            'required' => 'Toleransi Pulang Awal wajib di isi.'
        ]);
        $this->form_validation->set_rules('out_end', 'Toleransi Pulang Akhir', 'trim|required',[
            'required' => 'Toleransi Pulang Akhir wajib di isi.'
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
        $desg_idx       = trim($this->input->post('desg_idx', TRUE));
        $in_start       = trim($this->input->post('in_start', TRUE));
        $in_end         = trim($this->input->post('in_end', TRUE));
        $out_start      = trim($this->input->post('out_start', TRUE));
        $out_end        = trim($this->input->post('out_end', TRUE));
        $description    = trim($this->input->post('description', TRUE));
        $status         = trim($this->input->post('tStatus'));

        $designation_idx = $this->secure->dec($desg_idx);

        $cekInStart     = preg_replace('/[^0-9]/', '', $in_start);
        $cekInEnd       = preg_replace('/[^0-9]/', '', $in_end);
        $cekOutStart    = preg_replace('/[^0-9]/', '', $out_start);
        $cekOutEnd      = preg_replace('/[^0-9]/', '', $out_end);

        $data = [
            'designation_idx'       => $designation_idx,
            'tolerance_in_start'    => $cekInStart,
            'tolerance_in_end'      => $cekInEnd,
            'tolerance_out_start'   => $cekOutStart,
            'tolerance_out_end'     => $cekOutEnd,
            'description'           => $description,
            'status'                => $status,
            'created_by'            => $this->idx,
            'created_on'            => date("Y-m-d H:i:s")
        ];
        $cek = $this->mPos->insertTolerance($data);
        if($cek == true){
            $msg['success'] = true;
            $msg['text']    = 'Data Toleransi di tambahkan.';
            echo json_encode($msg);die;
        }else{
            $msg['status']  = true;
            $msg['text']    = 'Error Connection,<br>Please try again later.';
            echo json_encode($msg);die;
        }
    }
    public function editTolerance()
    {
        $id     = $this->secure->dec($this->input->get('id'));
        $result = $this->mPos->editTolerance($id);
        if($result){
            $push = [
                'en_idx' => $this->secure->enc($result['idx']),
                'desg_enidx' => $this->secure->enc($result['designation_idx']),
            ];
            $final = array_merge($push,$result);
        }else{
            $final = [];
        }
        echo json_encode($final);
    }
    public function updateTolerance($id)
    {
        $this->form_validation->set_rules('in_start', 'Toleransi Masuk Awal', 'trim|required',[
            'required' => 'Toleransi Masuk Awal wajib di pilih.'
        ]);
        $this->form_validation->set_rules('in_end', 'Toleransi Masuk Akhir', 'trim|required',[
            'required' => 'Toleransi Masuk Akhir wajib di pilih.'
        ]);
        $this->form_validation->set_rules('out_start', 'Toleransi Pulang Awal', 'trim|required',[
            'required' => 'Toleransi Pulang Awal wajib di isi.'
        ]);
        $this->form_validation->set_rules('out_end', 'Toleransi Pulang Akhir', 'trim|required',[
            'required' => 'Toleransi Pulang Akhir wajib di isi.'
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
        
        $in_start       = trim($this->input->post('in_start', TRUE));
        $in_end         = trim($this->input->post('in_end', TRUE));
        $out_start      = trim($this->input->post('out_start', TRUE));
        $out_end        = trim($this->input->post('out_end', TRUE));
        $description    = trim($this->input->post('description', TRUE));
        $status         = trim($this->input->post('tStatus'));

        $idx = $this->secure->dec($id);

        $cekInStart     = preg_replace('/[^0-9]/', '', $in_start);
        $cekInEnd       = preg_replace('/[^0-9]/', '', $in_end);
        $cekOutStart    = preg_replace('/[^0-9]/', '', $out_start);
        $cekOutEnd      = preg_replace('/[^0-9]/', '', $out_end);

        $data = [
            'tolerance_in_start'    => $cekInStart,
            'tolerance_in_end'      => $cekInEnd,
            'tolerance_out_start'   => $cekOutStart,
            'tolerance_out_end'     => $cekOutEnd,
            'description'           => $description,
            'status'                => $status,
            'modified_by'           => $this->idx,
            'modified_on'           => date("Y-m-d H:i:s")
        ];
        $cek = $this->mPos->updateTolerance($data, $idx);
        if($cek == true){
            $msg['success'] = true;
            $msg['text']    = 'Data Toleransi di ubah.';
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
        $cek = $this->mPos->changeStatus($data, $idx);
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
}
/* End of file user.php */
/* Location: ./application/controllers/user.php */