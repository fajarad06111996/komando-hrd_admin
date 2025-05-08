<?php
defined('BASEPATH') or exit('No direct script access allowed');
require "vendor/autoload.php";
use Nullix\CryptoJsAes\CryptoJsAes;
class Bank extends AUTH_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('settings/M_bank', 'mBank');
        $this->load->model('ModelGenId');
        $this->load->library('security_function');
        $this->link	    = site_url('settings/').strtolower(get_class($this)); // get_class($this) fungsinya untuk mendapatkan nama class dari sebuah objek
        $this->filename = strtolower(get_class($this));
        $this->office   = $this->secure->dec($this->session->userdata('JToffice_id'));
        $this->hub      = $this->secure->dec($this->session->userdata('JThub_id'));
        $this->idx      = $this->secure->dec($this->session->userdata('JTidx'));
        $this->enkey    = $this->config->item('encryption_key');
    }
    
    // tampilan utama
    public function index()
    {
        $access = $this->security_function->permissions($this->filename . "-r");
        if (empty($access)) die("<center><h1>You can not get the right to access this module</h1></center>");
        if(!empty($this->security_function->permissions($this->filename . "-w"))){
            $data['write']  = '<a href="javascript:void(0)" id="btnAdd" class="pull-right text-white small" title="Add New Data Location" data-placement="right" data-popup="tooltip"><i class="icon-plus-circle2"></i></a>';
            $data['lock']   = 1;
        }else{
            $data['write']  = '<i class="icon-lock pull-right pt-1 " title="Locked New Data Location" data-placement="right" data-popup="tooltip"></i>';
            $data['lock']   = 0;
        }
        $data['filename']   = $this->filename;
        $data["link"]       = $this->link;
        $params             = '{"link": "'.$this->link.'", "csrf": "'.$this->session->csrf_token.'", "permW": "'.$this->security_function->permissions($this->filename . "-w").'", "permC": "'.$this->security_function->permissions($this->filename . "-c").'", "permX": "'.$this->security_function->permissions($this->filename . "-x").'", "base_url":"'.base_url().'", "lock": "'.$data['lock'].'", "key_firebaase": '.key_firebase().'}';
        $encrypted          = CryptoJsAes::encrypt($params, $this->enkey);
        $data['params']     = $encrypted;
        $this->template->views('pengaturan/v_bank', $data);
    }
    
    // load data dengan ajax
    function get_ajax() 
    {
        $csrfToken  = validate_csrf_token();
        if($csrfToken == false){
            $list = array();
        }else{
            $list = $this->mBank->get_datatables();
        }
        // $list = $this->mProduct->get_datatables();
        $data = array();
        $no = @$_POST['start'];
        foreach ($list as $item) {
            $idx = $this->secure->enc($item->idx);
            if(!empty($this->security_function->permissions($this->filename . "-c"))){
                $change = "<h5><a href='javascript:void(0);' id='$idx' class='bEdit text-center badge badge-info' data-popup='tooltip' title='Edit Bank' data-placement='right'><i class='icon-pencil7'></i></a></h5>";
                if($item->status == 1)
                {
                    $statusU = "<h5><a href='javascript:void(0);' id='$idx' data='$item->bank_name' code='1' class='bStatus text-center badge badge-success' data-popup='tooltip' title='Change Status' data-placement='right'>Aktif</a></h5>";
                } else {
                    $statusU = "<h5><a href='javascript:void(0);' id='$idx' data='$item->bank_name' code='0' class='bStatus text-center badge badge-danger' data-popup='tooltip' title='Change Status' data-placement='right'>Non Aktif</a></h5>";
                }
            }else{
                $change = '<h5><span class="badge badge-warning"><i class="icon-lock" data-popup="tooltip" title="Locked Edit Bank" data-placement="right"></i></span></h5>';
                if($item->status == 1)
                {
                    $statusU = "<span class='text-center badge badge-success'>Aktif</span>";
                }else{
                    $statusU = "<span class='text-center badge badge-danger'>Non Aktif</span>";
                }
            }
            if(!empty($this->security_function->permissions($this->filename . "-x"))){
                $execute  = "<h5><a href='javascript:void(0);' id='$idx' data='$item->bank_name' class='bDelete text-center badge badge-danger' data-popup='tooltip' title='Delete Bank' data-placement='right'><i class='icon-bin'></i></a></h5>";
            }else{
                $execute  = '<h5><span class="badge badge-warning"><i class="icon-lock" data-popup="tooltip" title="Locked Delete Bank" data-placement="right"></i></span></h5>';
            }
            $no++;
            $row = array();
            $row[] = $no.".";
            $row[] = "<div class='btn-group'>$change</div>";
            $row[] = $statusU;
            $row[] = $item->bank_code;
            $row[] = $item->bank_name;
            $row[] = $item->remark;
            // add html for action
            $data[] = $row;
        }
        $output = array(
            "draw"            => intval(@$_POST['draw']),
            "recordsTotal"    => intval($this->mBank->count_all()),
            "recordsFiltered" => intval($this->mBank->count_filtered()),
            "data"            => $data,
        );
        // output to json format
        echo json_encode($output);
    }
    public function addBank()
    {
        $this->form_validation->set_rules('bank_code', 'Bank Code', 'trim|required|is_unique[master_bank.bank_code]');
        $this->form_validation->set_rules('bank_name', 'Bank Name', 'trim|required');
        $this->form_validation->set_rules('remark', 'Remark', 'trim|required');
        if ($this->form_validation->run() == false) {
            $msg['error']   = true;
            $msg['message'] = validation_errors();
            echo json_encode($msg);die;
        }
        $csrf = validate_csrf_token();
        if($csrf==false){
            $msg['status']  = true;
            $msg['text']    = 'token invalid.';
            echo json_encode($msg);die;
        }
        ini_set ('max_execution_time', '0');
        ini_set ('memory_limit', '256M');
        $bank_code      = strtoupper(trim($this->input->post('bank_code', TRUE)));
        $bank_name      = strtoupper(trim($this->input->post('bank_name', TRUE)));
        $remark         = trim($this->input->post('remark', TRUE));
        $status         = trim($this->input->post('tStatus'));

        $cekBankCode    = $this->mBank->cekBankCode($bank_code);
        if ($cekBankCode > 0) {
            $msg['status']  = true;
            $msg['text']    = 'Bank Code '.$bank_code.' already exist.';
            echo json_encode($msg);die;
        }
        $data = [
            'bank_code'     => $bank_code,
            'bank_name'     => $bank_name,
            'remark'        => $remark,
            'status'        => $status,
            'created_by'    => $this->idx,
            'created_on'    => date("Y-m-d H:i:s")
        ];
        $cek = $this->mBank->insertBank($data);
        if($cek == true){
            $msg['success'] = true;
            $msg['type']    = 'add';
            $msg['text']    = 'Insert new product successfully.';
            echo json_encode($msg);
            die;
        }else{
            $msg['status']  = true;
            $msg['text']    = 'Error Connection,<br>Please try again later.';
            echo json_encode($msg);
            die;
        }
    }
    public function editBank()
    {
        $id     = $this->secure->dec($this->input->get('id'));
        $result = $this->mBank->editBank($id);
        echo json_encode($result);
    }
    public function updateBank($idx)
    {
        $id = $this->secure->dec($idx);
        $this->form_validation->set_rules('bank_code', 'Bank Code', 'trim|required');
        $this->form_validation->set_rules('bank_name', 'Bank Name', 'trim|required');
        $this->form_validation->set_rules('remark', 'Remark', 'trim|required');
        if ($this->form_validation->run() == false) {
            $msg['error']   = true;
            $msg['message'] = validation_errors();
            echo json_encode($msg);die;
        }
        $csrf = validate_csrf_token();
        if($csrf==false){
            $msg['status']  = true;
            $msg['text']    = 'token invalid.';
            echo json_encode($msg);die;
        }
        ini_set ('max_execution_time', '0');
        ini_set ('memory_limit', '256M');
        $bank_code      = strtoupper(trim($this->input->post('bank_code', TRUE)));
        $bank_name      = strtoupper(trim($this->input->post('bank_name', TRUE)));
        $remark         = trim($this->input->post('remark', TRUE));
        $status         = trim($this->input->post('tStatus'));
        $cekBankCode    = $this->mBank->cekBankCodeUpdate($id, $bank_code);
        if ($cekBankCode > 0) {
            $msg['status']  = true;
            $msg['text']    = 'Bank Code '.$bank_code.' already exist.';
            echo json_encode($msg);die;
        }
        $data = [
            'bank_code'     => $bank_code,
            'bank_name'     => $bank_name,
            'remark'        => $remark,
            'status'        => $status,
            'created_by'    => $this->idx,
            'created_on'    => date("Y-m-d H:i:s")
        ];
        $cek = $this->mBank->updateBank($data, $id);
        if($cek == true){
            $msg['success'] = true;
            $msg['text']    = 'Bank updated successfully.';
            echo json_encode($msg);die;
        }else{
            $msg['status']  = true;
            $msg['text']    = 'Error Connection,<br>Please try again later.';
            echo json_encode($msg);die;
        }
    }
    public function checkResponse()
    {
        $cek = $this->mProduct->getMProduct($_POST['resname']);
        if($cek > 0) {
            echo '0';
        } else {
            echo '1';
        }
    }
    public function deleteProduct()
    {
        cek_get_csrf();
        $id     = $this->secure->dec($this->input->get('id'));
        $result = $this->mProduct->deleteProduct($id);
        if ($result == true) {
            $msg['success'] = true;
        }else{
            $msg['success']   = false;
        }
        echo json_encode($msg);
    }
    public function changeStatus()
    {
        $id_bank    = $this->secure->dec($this->input->post('tId'));
        $id_status  = $this->input->post('tClCode');
        $csrf       = validate_csrf_token();
        if($csrf==false){
            $msg['status']  = true;
            $msg['text']    = 'Change status bank successfully';
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
        $cek = $this->mBank->changeStatusBank($data, $id_bank);
        if($cek == true)
        {
            $msg['success'] = true;
            $msg['text']    = 'Change status bank successfully';
            echo json_encode($msg);die;
        }else{
            $msg['status']  = true;
            $msg['text']    = 'Change status bank failed';
            echo json_encode($msg);die;
        }
    }
}
/* End of file Product.php */
/* Location: ./application/controllers/Product.php */
