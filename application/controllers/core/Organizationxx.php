<?php
defined('BASEPATH') or exit('No direct script access allowed');
require "vendor/autoload.php";
use Nullix\CryptoJsAes\CryptoJsAes;
class Organization extends AUTH_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('core/M_organization', 'mOrg');
        $this->load->model('ModelGenId');
        $this->load->library('security_function');
        $this->link	    = site_url('core/').strtolower(get_class($this));
        $this->filename = strtolower(get_class($this));
        $this->office   = $this->secure->dec($this->session->userdata('JToffice_id'));
        $this->tOffice  = $this->secure->dec($this->session->userdata('JToffice_type'));
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
            $view  = '<a href="'.$this->link.'/organizationView" class="pull-right text-white small" title="Struktur Organisasi" data-placement="right" data-popup="tooltip"><i class="icon-tree6"></i></a>';
            $write  = '<a href="javascript:void(0);" id="btnAdd" class="pull-right text-white small" title="Tambah Organisasi" data-placement="right" data-popup="tooltip"><i class="icon-plus-circle2"></i></a>';
            $data['write']  = "<div class='btn-group pull-right'>$view&nbsp;&nbsp;$write</div>";
            $data['lock']   = 1;
        }else{
            $data['write']  = '<i class="icon-lock pull-right pt-1 " title="Tambah Organisasi Terkunci" data-placement="right" data-popup="tooltip"></i>';
            $data['lock']   = 0;
        }
        $data['employee']   = $this->mOrg->getEmployee();
        $data['filename']   = $this->filename;
        $data['judul']      = 'Organization';
        $data['page']       = 'Organization';
        $data["link"]       = $this->link;
        $params             = '{"link": "'.$this->link.'", "csrf": "'.$this->session->csrf_token.'", "permW": "'.$this->security_function->permissions($this->filename . "-w").'", "permC": "'.$this->security_function->permissions($this->filename . "-c").'", "permX": "'.$this->security_function->permissions($this->filename . "-x").'"}';
        $encrypted          = CryptoJsAes::encrypt($params, $this->enkey);
        $data['params']     = $encrypted;
        $this->template->views('core/v_organization', $data);
    }
    // CONTOH LAIN CONTROLLER2 POST DATATABLE SERVERSIDE
    function get_ajax()
    {
        $csrfToken  = validate_csrf_token();
        if($csrfToken == false){
            $list = array();
        }else{
            $list = $this->mOrg->get_datatables();
        }
        $data = array();
        $no = @$_POST['start'];
        foreach ($list as $item) {
            $idx            = $this->secure->enc($item->idx);
            if(!empty($this->security_function->permissions($this->filename . "-w"))){
            }else{
            }
            if(!empty($this->security_function->permissions($this->filename . "-c"))){
                $change = "<h5 class='m-0'><a href='javascript:void(0);' id='$idx' class='bEdit text-center badge badge-info' data-popup='tooltip' title='Edit Department' data-placement='right'><i class='icon-pencil5'></i></a></h5>";
                if($item->status == 1)
                {
                    $statusU = "<h5><a href='javascript:void(0);' id='$idx' data='$item->department_name' code='1' class='bStatus text-center badge badge-success' data-popup='tooltip' title='Change Status' data-placement='right'>Aktif</a></h5>";
                // $statusU = '<span class="badge badge-success">Aktif</span>';
                } else {
                    $statusU = "<h5><a href='javascript:void(0);' id='$idx' data='$item->department_name' code='0' class='bStatus text-center badge badge-danger' data-popup='tooltip' title='Change Status' data-placement='right'>Non Aktif</a></h5>";
                // $statusU = '<span class="badge badge-danger">Non Aktif</span>';
                }
            }else{
                $change = '<h5 class="m-0"><span class="badge badge-warning bLockStatus"><i class="icon-lock" data-popup="tooltip" title="Locked Edit Customer" data-placement="right"></i></span></h5>';
                if($item->status == 1)
                {
                    $statusU = "<span class='text-center badge badge-success'>Aktif</span>";
                }else{
                    $statusU = "<span class='text-center badge badge-danger'>Non Aktif</span>";
                }
            }
            if(!empty($this->security_function->permissions($this->filename . "-x"))){
                $execute  = "<h5 class='m-0'><a href='javascript:void(0);' id='$idx' data='$item->department_name' class='bDelete text-center badge badge-danger' data-popup='tooltip' title='Delete Department' data-placement='right'><i class='icon-bin'></i></a></h5>";
            }else{
                $execute  = '<h5 class="m-0"><span class="badge badge-warning"><i class="icon-lock" data-popup="tooltip" title="Locked Delete Department" data-placement="right"></i></span></h5>';
            }
            $no++;
            $row = array();
            $row[] = $no.".";
            $row[] = "<div class='btn-group'>
                        <button type='button' class='btn btn-danger btn-sm dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'><i class='icon-more2'></i></button>
                        <div class='dropdown-menu p-2' style='min-width: auto !important;'>
                            <div class='btn-group'>$change$execute</div>
                        </div>
                    </div>";
            $row[] = $statusU;
            $row[] = $item->department_code;
            $row[] = $item->department_name;
            $row[] = empty($item->employee_name)?'<span class="text-danger">Unset</span>':$item->employee_name;
            $row[] = $item->description;
            // add html for action
            $data[] = $row;
        }
        $output = array(
            "draw"            => @$_POST['draw'],
            "recordsTotal"    => $this->mOrg->count_all(),
            "recordsFiltered" => $this->mOrg->count_filtered(),
            "data"            => $data,
        );
        // output to json format
        echo json_encode($output);
    }
    public function organizationView()
    {
        $access = $this->security_function->permissions($this->filename . "-r");
        if (empty($access)) die("<center><h1>You can not get the right to access this module</h1></center>");
        if(!empty($this->security_function->permissions($this->filename . "-w"))){
            $data['write']  = '<a href="javascript:void(0);" id="btnAdd" class="pull-right text-white small" title="Tambah Organisasi" data-placement="right" data-popup="tooltip"><i class="icon-plus-circle2"></i></a>';
            $data['lock']   = 1;
        }else{
            $data['write']  = '<i class="icon-lock pull-right pt-1 " title="Tambah Organisasi Terkunci" data-placement="right" data-popup="tooltip"></i>';
            $data['lock']   = 0;
        }
        $data['employee']   = $this->mOrg->getEmployee();
        $data['filename']   = $this->filename;
        $data['judul']      = 'Struktur Organisasi';
        $data['page']       = 'Struktur Organisasi';
        $data["link"] = $this->link;
        $data['params']     = '{"link": "'.$this->link.'", "csrf": "'.$this->session->csrf_token.'", "permW": "'.$this->security_function->permissions($this->filename . "-w").'", "permC": "'.$this->security_function->permissions($this->filename . "-c").'", "permX": "'.$this->security_function->permissions($this->filename . "-x").'"}';
        $this->template->views('core/v_organization_chart', $data);
    }
    public function addOrganization()
    {
        $this->form_validation->set_rules('department_code', 'Kode Organisasi', 'trim|required',[
            'required' => 'Kode Organisasi wajib di isi.'
        ]);
        $this->form_validation->set_rules('department_name', 'Nama Organisasi', 'trim|required',[
            'required' => 'Nama Organisasi wajib di isi.'
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
        $department_code    = trim($this->input->post('department_code', TRUE));
        $department_name    = trim($this->input->post('department_name', TRUE));
        $dept_head_idx      = $this->secure->dec(trim($this->input->post('dept_head_idx', TRUE)));
        $description        = trim($this->input->post('description', TRUE));
        $status             = trim($this->input->post('tStatus'));

        $cekDeptCode        = $this->mOrg->cekDeptCode($department_code);
        if($cekDeptCode){
            $msg['status']  = true;
            $msg['text']    = 'Kode '.$department_code.' Sudah ada.';
            echo json_encode($msg);die;
        }

        $data = [
            'department_code'   => $department_code,
            'department_name'   => $department_name,
            'dept_head_idx'     => $dept_head_idx,
            'description'       => $description,
            'status'            => $status,
            'created_by'        => $this->idx,
            'created_on'        => date("Y-m-d H:i:s")
        ];
        $cek = $this->mOrg->insertDepartment($data);
        if($cek == true){
            $msg['success'] = true;
            $msg['text']    = 'Organisasi berhasil di tambahkan.';
            echo json_encode($msg);die;
        }else{
            $msg['status']  = true;
            $msg['text']    = 'Error Connection,<br>Please try again later.';
            echo json_encode($msg);die;
        }
    }
    public function updateOrganization($id)
    {
        $idx = $this->secure->dec($id);
        $this->form_validation->set_rules('department_code', 'Kode Organisasi', 'trim|required',[
            'required' => 'Kode Organisasi wajib di isi.'
        ]);
        $this->form_validation->set_rules('department_name', 'Nama Organisasi', 'trim|required',[
            'required' => 'Nama Organisasi wajib di isi.'
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
        $department_code    = trim($this->input->post('department_code', TRUE));
        $department_name    = trim($this->input->post('department_name', TRUE));
        $dept_head_idx      = $this->secure->dec(trim($this->input->post('dept_head_idx', TRUE)));
        $description        = trim($this->input->post('description', TRUE));
        $status             = trim($this->input->post('tStatus'));

        $cekDeptCode        = $this->mOrg->cekDeptCode($department_code);
        if($cekDeptCode){
            $msg['status']  = true;
            $msg['text']    = 'Kode '.$department_code.' Sudah ada.';
            echo json_encode($msg);die;
        }

        $data = [
            'department_code'   => $department_code,
            'department_name'   => $department_name,
            'dept_head_idx'     => $dept_head_idx,
            'description'       => $description,
            'status'            => $status,
            'modified_by'       => $this->idx,
            'modified_on'       => date("Y-m-d H:i:s")
        ];
        $cek = $this->mOrg->updateDepartment($data, $idx);
        if($cek == true){
            $msg['success'] = true;
            $msg['text']    = 'Organisasi berhasil diubah.';
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
        $cek = $this->mOrg->changeStatus($data, $idx);
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
    public function editDepartment()
    {
        $id     = $this->secure->dec($this->input->get('id'));
        $result = $this->mOrg->editDepartment($id);
        if($result){
            $push = [
                'en_idx' => $this->secure->enc($result['idx']),
                'dept_head_enidx' => $this->secure->enc($result['dept_head_idx']),
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
