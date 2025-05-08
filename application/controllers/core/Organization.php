<?php
defined('BASEPATH') or exit('No direct script access allowed');
require 'vendor/autoload.php';
use Nullix\CryptoJsAes\CryptoJsAes;
class Organization extends AUTH_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('core/M_organization', 'mAccount');
        $this->load->model('ModelGenId');
        $this->load->library('security_function');
        $this->link	    = site_url('core/').strtolower(get_class($this));
        $this->filename	= strtolower(get_class($this));
        $this->office   = $this->secure->dec($this->session->userdata('JToffice_id'));
        $this->tOffice  = $this->secure->dec($this->session->userdata('JToffice_type'));
        $this->hub      = $this->secure->dec($this->session->userdata('JThub_id'));
        $this->idx      = $this->secure->dec($this->session->userdata('JTidx'));
        $this->username = $this->session->userdata('JTuser_id');
        $this->enkey  	= $this->config->item('encryption_key');
    }
    public function index()
    {
        $access = $this->security_function->permissions($this->filename . "-r");
        if (empty($access)) die("<center><h1>You can not get the right to access this module</h1></center>");
        if(!empty($this->security_function->permissions($this->filename . "-w"))){
            $write = '<a href="'.$this->link.'/formdata" id="btnAdd" class="badge text-white small" title="Add New Account" data-placement="right" data-popup="tooltip"><i class="icon-plus-circle2"></i></a>';
            $print = "<a href='".$this->link."/account_pdf' target='_BLANK' class='badge bPrint text-center text-white' data-popup='tooltip' title='Print Account' data-placement='right'><i class='icon-printer2'></i></a>";
            $data['write']  = "<div class='pull-right btn-group'>$write</div>";
            $data['lock']   = 1;
        }else{
            $write = "<i class='icon-lock pull-right pt-1 ' title='Locked New Account' data-placement='right' data-popup='tooltip'></i>";
            $print = "<a href='".$this->link."/account_pdf' target='_BLANK' class='bPrint text-center text-white' data-popup='tooltip' title='Print Account' data-placement='right'><i class='icon-printer2'></i></a>";
            $data['write']  = "<div class='pull-right btn-group'>$write</div>";
            $data['lock']   = 0;
        }
        $data['dataAkun']     = $this->mAccess->readtable('master_organization','*,(SELECT employee_name FROM master_employee WHERE employee_id = master_organization.employee_id) as employee_name,(SELECT photo FROM master_employee WHERE employee_id = master_organization.employee_id) as employee_photo',array('status' => 1, 'company_id'=>$this->office, 'status_delete' => 0),'','',array('organization_number' => 'asc'))->result();
        $data['accType']        = $this->mAccess->getData('status_account_type')->result();
        $data['subAcc']         = $this->mAccess->getData('master_organization','','','',array('organization_segment' => 1, 'status' => 1, 'company_id'=>$this->office, 'status_delete' => 0))->result();
        $data['filename']       = $this->filename;
        $data["link"]           = $this->link;
        $params   			    = '{"base_url": "'.base_url().'", "link": "'.$this->link.'", "csrf": "'.$this->session->csrf_token.'", "permW": "'.$this->security_function->permissions($this->filename . "-w").'", "permC": "'.$this->security_function->permissions($this->filename . "-c").'", "permX": "'.$this->security_function->permissions($this->filename . "-x").'", "lock": "'.$data['lock'].'"}';
		$encrypted 			    = CryptoJsAes::encrypt($params, $this->enkey);
		$data['params']   	    = $encrypted;
        // var_dump('<pre>');var_dump($data['params']);die;
        $this->template->views('core/v_org', $data);
    }
    // CONTOH LAIN CONTROLLER2 POST DATATABLE SERVERSIDE
    function get_ajax()
    {
        $csrfName = $this->input->post('CSRFToken');
        $csrfToken  = $this->session->csrf_token;
        if($csrfToken != $csrfName){
        $list = null;
        }else{
            $list = $this->mAccount->get_datatables();
        }
        // $list = $this->mOffice->get_datatables();
        $data = array();
        $no = @$_POST['start'];
        $order = @$_POST['order'];
        // var_dump($order);
        // die();
        foreach ($list as $item) {
            $idx = $this->secure->enc($item->idx);
            $info = "<h5 class='m-0'><a href='javascript:void(0);' id='$idx' data='$item->account_name' code='1' class='bInfo text-center badge badge-primary' data-popup='tooltip' title='Account Info' data-placement='right'><i class='icon-price-tag2'></i></a></h5>";
            if(!empty($this->security_function->permissions($this->filename . "-c"))){
                $change = "<h5 class='m-0'><a href='javascript:void(0);' id='$idx' class='bEdit text-center badge badge-info' data-popup='tooltip' title='Edit Account' data-placement='right'><i class='icon-pencil5'></i></a></h5>";
                if($item->status == 1)
                {
                    $statusU = "<h5><a href='javascript:void(0);' id='$idx' data='$item->account_name' code='1' class='bStatus text-center badge badge-success' data-popup='tooltip' title='Change Status' data-placement='right'>Aktif</a></h5>";
                // $statusU = '<span class="badge badge-success">Aktif</span>';
                } else {
                    $statusU = "<h5><a href='javascript:void(0);' id='$idx' data='$item->account_name' code='0' class='bStatus text-center badge badge-danger' data-popup='tooltip' title='Change Status' data-placement='right'>Non Aktif</a></h5>";
                // $statusU = '<span class="badge badge-danger">Non Aktif</span>';
                }
            }else{
                $change = '<h5 class="m-0"><span class="badge badge-warning"><i class="icon-lock" data-popup="tooltip" title="Locked Edit Account" data-placement="right"></i></span></h5>';
                if($item->status == 1)
                {
                    $statusU = "<span class='text-center badge badge-success'>Aktif</span>";
                }else{
                    $statusU = "<span class='text-center badge badge-danger'>Non Aktif</span>";
                }
            }
            if(!empty($this->security_function->permissions($this->filename . "-x"))){
                $execute  = "<h5 class='m-0'><a href='javascript:void(0);' id='$idx' data='$item->account_name' class='bDelete text-center badge badge-danger' data-popup='tooltip' title='Delete Account' data-placement='right'><i class='icon-bin'></i></a></h5>";
            }else{
                $execute  = '<h5 class="m-0"><span class="badge badge-warning"><i class="icon-lock" data-popup="tooltip" title="Locked Delete Account" data-placement="right"></i></span></h5>';
            }
            $no++;
            $row = array();
            $row[] = "<div class='btn-group'>$change</div>";
            $row[] = $item->organization_segment==1?"<strong>$item->organization_number</strong>":($item->organization_segment==3?"<span class='ml-2'>".$item->organization_number."</span>":"<span class='ml-1'>".$item->organization_number."</span>");
            $row[] = $item->organization_segment==1?"<strong>$item->account_name</strong>":($item->organization_segment==3?"<span class='ml-2'>".$item->account_name."</span>":"<span class='ml-1'>".$item->account_name."</span>");
            $row[] = $item->organization_segment==1?"<strong>$item->account_typex</strong>":$item->account_typex;
            $row[] = $item->organization_segment==1?"<strong>Rp".number_format($item->ending_balance)."</strong>":"Rp".number_format($item->ending_balance);
            $data[] = $row;
        }
        $output = array(
            "draw"            => @$_POST['draw'],
            "recordsTotal"    => $this->mAccount->count_all(),
            "recordsFiltered" => $this->mAccount->count_filtered(),
            "data"            => $data
        );
        // output to json format
        echo json_encode($output);
    }
    public function checkResponse()
    {
        $cek = $this->mAccount->getAccCode($_POST['resname']);
        if($cek > 0) {
            echo '0';
        } else {
            echo '1';
        }
    }
    public function getAccNo()
    {
        $accNo = $this->secure->dec($this->input->post('data', true));
        $segment = $this->input->post('segment', true);
        $this_idx = $this->secure->dec($this->input->post('this_idx', true));
        $segmentx = (int)$segment + 1;
        $cek = $this->mAccount->getAccNoNew($accNo, $segmentx, $this_idx);
        if(empty($cek)){
            $data = $cek;
        }else{
            $en_idx = $this->secure->enc($cek['idx']);
            $cek['en_idx'] = $en_idx;
            $data = $cek;
        }
        echo json_encode($data);
    }
    public function getAccNoSeg2()
    {
        $accNo = $this->secure->dec($this->input->post('data'));
        $cek = $this->mAccount->getAccNoSeg2($accNo);
        echo json_encode($cek);
    }
    public function getAccNoSeg3()
    {
        $accNo = $this->secure->dec($this->input->post('data'));
        $cek = $this->mAccount->getAccNoSeg3($accNo);
        echo json_encode($cek);
    }
    public function getAccNoSeg4()
    {
        $accNo = $this->secure->dec($this->input->post('data'));
        $cek = $this->mAccount->getAccNoSeg4($accNo);
        echo json_encode($cek);
    }
    public function getTypeAcc()
    {
        $accType = $this->secure->dec($this->input->get('tAccType'));
        $cek = $this->mAccount->getTypeAcc($accType);
        $data = array();
        foreach($cek as $c){
            $data[] = [$this->secure->enc($c->idx) => $c->account_name];
        }
        echo json_encode($data);
    }
    public function formData($id = '')
    {
        $idx = $this->secure->dec($id);
        if(empty($id)){
            $data['dataAkun']     = $this->mAccess->readtable('master_organization','',array('status' => 1, 'company_id' => $this->office),'','',array('organization_number' => 'asc'))->result();
            // if(empty($data['dataAkun'])){
            // }else{
                
            // }
            $orgCodeId    = $this->ModelGenId->getValue('ORG'.$this->office);
            $orgCode        = "A".str_pad($orgCodeId,3,"0",STR_PAD_LEFT);
            if($orgCodeId==0){

            }
            $data['kode_jabatan'] = $orgCode;
            $data['subAcc']         = $this->mAccess->getData('master_organization','','','',array('status' => 1, 'company_id' => $this->office, 'status_delete' => 0))->result();
            $data['allEmployee']    = $this->mAccess->getData('master_employee','','','',array('status' => 1, 'company_idx' => $this->office))->result();
            $data['formact']  = $this->link.'/formActAcc';
            $data['title']    = "Add New Organization";
            $data['parent_idx'] = null;
            $data['number_parent'] = null;
            $data['number_child'] = null;
            $data['employee_id'] = null;
            $data['head_name'] = null;
            $data['idx'] = null;
            $data['acc']      = [];
            $data['edit']       = 0;
        }else{
            $data['dataAkun']     = $this->mAccess->readtable('master_organization','*',array('status' => 1, 'company_id' => $this->office, 'status_delete' => 0),'','',array('organization_number' => 'asc'))->result();
            $data['subAcc']         = $this->mAccess->getData('master_organization','','','',array('status' => 1, 'company_id' => $this->office, 'idx <> '=>$idx, 'status_delete' => 0))->result();
            $data['allEmployee']    = $this->mAccess->getData('master_employee','','','',array('status' => 1, 'company_idx' => $this->office, 'status_delete' => 0))->result();
            $data['acc']      = $this->getAccount($idx);
            // var_dump('<pre>');var_dump($data['acc']);die;
            if(empty($data['acc'])){
                $data['parent_idx'] = null;
            }else{
                $data['parent_idx'] = $data['acc']['parent_idxx'];
            }
            $data['number_parent'] = $data['acc']['organization_number_parent'];
            $data['number_child'] = $data['acc']['organization_number_child'];
            $data['employee_id'] = $data['acc']['en_employee_id'];
            $data['head_name'] = $data['acc']['head_name'];
            $data['idx'] = $id;
            $data['title']    = "Edit Organization";
            $data['edit']       = 1;
            $data['formact']  = $this->link.'/formActAcc/'.$id;
        }
        $cekAcc = empty($data['acc']);
        // $wherecust          = "custName NOT IN('0','')";
        // $sort               = array('custName'=>'ASC');
        $data['link']       = $this->link;
        $data['page']       = "news";
        $data['judul']      = "Posting";
        $data['deskripsi']  = "Data News";
        $params   		    = '{"base_url": "'.base_url().'", "link": "'.$this->link.'", "csrf": "'.$this->session->csrf_token.'", "permW": "'.$this->security_function->permissions($this->filename . "-w").'", "permC": "'.$this->security_function->permissions($this->filename . "-c").'", "permX": "'.$this->security_function->permissions($this->filename . "-x").'", "edit": "'.$data['edit'].'", "acc": "'.$cekAcc.'", "key_firebase": '.key_firebase().', "idx": "'.$id.'", "parent_idx": "'.$data['parent_idx'].'", "number_parent": "'.$data['number_parent'].'", "number_child": "'.$data['number_child'].'", "employee_id": "'.$data['employee_id'].'", "head_name": "'.$data['head_name'].'"}';
		$encrypted 		    = CryptoJsAes::encrypt($params, $this->enkey);
		$data['params']     = $encrypted;
        $this->template->views('core/v_formorg', $data);
    }
    public function addAccount()
    {
        $organization_segment   = trim($this->input->post('tSegment', TRUE));
        // $this->form_validation->set_rules('tAccType', 'Tipe Akun', 'trim|required',
        // [
        // 'required' => 'Tipe Akun wajib di isi.'
        // ]);
        if($organization_segment == 2){
        $this->form_validation->set_rules('accNo2', 'Nomor Akun', 'trim|required',
        [
            'required' => 'Nomor Akun wajib di isi.'
        ]);
        $this->form_validation->set_rules('tSubAccount', 'Bagian Dari Segment 1', 'trim|required',
        [
            'required' => 'Bagian Dari Segment 1 wajib di isi.'
        ]);
        }elseif($organization_segment == 3){
        $this->form_validation->set_rules('accNo3', 'Nomor Akun', 'trim|required',
        [
            'required' => 'Nomor Akun wajib di isi.'
        ]);
        $this->form_validation->set_rules('tSubAccount', 'Bagian Dari Segment 1', 'trim|required',
        [
            'required' => 'Bagian Dari Segment 1 wajib di isi.'
        ]);
        $this->form_validation->set_rules('tSubChildAccount', 'Bagian Dari Segment 2', 'trim|required',
        [
            'required' => 'Bagian Dari Segment 2 wajib di isi.'
        ]);
        }elseif($organization_segment == 4){
        $this->form_validation->set_rules('accNo4', 'Nomor Akun', 'trim|required',
        [
            'required' => 'Nomor Akun wajib di isi.'
        ]);
        $this->form_validation->set_rules('tSubAccount', 'Bagian Dari Segment 1', 'trim|required',
        [
            'required' => 'Bagian Dari Segment 1 wajib di isi.'
        ]);
        $this->form_validation->set_rules('tSubChildAccount', 'Bagian Dari Segment 2', 'trim|required',
        [
            'required' => 'Bagian Dari Segment 2 wajib di isi.'
        ]);
        $this->form_validation->set_rules('tSubSubChildAccount', 'Bagian Dari Segment 3', 'trim|required',
        [
            'required' => 'Bagian Dari Segment 3 wajib di isi.'
        ]);
        }else{
        $this->form_validation->set_rules('accNo1', 'Kode Jabatan', 'trim|required|is_unique[master_organization.organization_number]',
        [
            'is_unique' => 'Kode ini sudah digunakan.',
            'required' => 'Kode Jabatan wajib di isi.'
        ]);
        }
        $this->form_validation->set_rules('account_name', 'Nama Jabatan', 'trim|required',
        [
        'required' => 'Nama Jabatan wajib di isi.'
        ]);
        if($this->form_validation->run() == false){
            $msg['error']   = true;
            $msg['message'] = validation_errors();
            echo json_encode($msg);
            die;
        }
        // $account_type         = $this->secure->dec($this->input->post('tAccType', TRUE));
        $account_name         = strtoupper($this->input->post('account_name', TRUE));
        $accNo1               = trim($this->input->post('accNo1', TRUE));
        $accNo2               = $this->input->post('accNo2', TRUE)=='NaN'?null:$this->input->post('accNo2', TRUE);
        $accNo3               = $this->input->post('accNo3', TRUE)=='NaN'?null:$this->input->post('accNo3', TRUE);
        $accNo4               = $this->input->post('accNo4', TRUE)=='NaN'?null:$this->input->post('accNo4', TRUE);
        $tSubAccount          = $this->secure->dec($this->input->post('tSubAccount'));
        $tSubChildAccount     = $this->secure->dec($this->input->post('tSubChildAccount', TRUE));
        $tSubSubChildAccount  = $this->secure->dec($this->input->post('tSubSubChildAccount', TRUE));
        // $start_balance        = trim($this->input->post('start_balance', TRUE));
        // $ending_balance       = trim($this->input->post('ending_balance', TRUE));
        // $starting_date        = trim($this->input->post('starting_date', TRUE));
        $status               = trim($this->input->post('tStatus', TRUE));
        // $csrfName             = $this->input->post('csrf');
        // $csrfToken            = $this->session->csrf_token;
        // if($csrfToken != $csrfName){
        //     $msg['status']  = true;
        //     $msg['text']    = "Token expired,<br>refresh page and try again.";
        //     echo json_encode($msg);
        //     die;
        // }else{
        // }
        if($organization_segment == 2){
            $organization_number = $accNo1.'.'.$accNo2;
            $parent_idx     = $tSubAccount;
            $parent_active  = 0;
        }elseif($organization_segment == 3){
            $organization_number = $accNo1.'.'.$accNo3;
            $parent_idx     = $tSubChildAccount;
            $parent_active  = 0;
        }elseif($organization_segment == 4){
            $organization_number = $accNo1.'.'.$accNo4;
            $parent_idx     = $tSubSubChildAccount;
            $parent_active  = 0;
        }else{
            $organization_number = $accNo1;
            $parent_idx     = null;
            $parent_active  = 1;
        }
        $part_organization_number = explode('.',$accNo1);
        $data = [
            'account_name'              => $account_name,
            'organization_number'            => $organization_number,
            'parent_idx'                => $parent_idx,
            'parent_active'             => $parent_active,
            'organization_segment'           => $organization_segment,
            'part_one_organization_number'   => $part_organization_number[0],
            'part_two_organization_number'   => $accNo2,
            'part_three_organization_number' => $accNo3,
            'part_four_organization_number'  => $accNo4,
            'status'                    => $status,
            'company_id'                => $this->office,
            'created_by'                => $this->idx,
            'created_on'                => date("Y-m-d H:i:s")
        ];
        $dataWhereUpdate = [
            'idx'  => $parent_idx
        ];
        $dataUpdate = [
            'parent_active' => 1,
            'modified_by'   => $this->idx,
            'modified_on'   => date("Y-m-d H:i:s")
        ];
        // var_dump('<pre>');
        // var_dump($starting_date);
        // die();
        if($organization_segment == 1){
            $result = $this->mAccess->insertMultiData($data, 'master_organization');
        }else{
            $result = $this->mAccess->insertUpdate($data, 'master_organization',$dataUpdate,'master_organization',$dataWhereUpdate);
        }
        if($result == 1)
        {
            $msg['type']    = 'add';
            $msg['ledit']   = $this->link;
            $msg['success'] = true;
            $msg['text']    = 'Akun Berhasil ditambahkan.';
        }else{
            //show an error page or error message about the failed insert
            $msg['status']  = true;
            $msg['text']    = 'Koneksi error,<br>Silahkan cek koneksi internet anda.';
        }
        echo json_encode($msg);
    }
    public function editAccount()
    {
        $id = $this->secure->dec($this->input->get('id'));
        $result = $this->mAccount->editAccount($id);
        $data = array(
            'account_typex'     => $this->secure->enc($result['account_type']),
            'parent_idxx'       => $result['parent_idx'] == null?null:$this->secure->enc($result['parent_idx']),
            'parent_segtwox'    => $result['parent_segtwo'] == null?null:$this->secure->enc($result['parent_segtwo']),
            'parent_segthreex'  => $result['parent_segthree'] == null?null:$this->secure->enc($result['parent_segthree'])
        );
        $datax = array_merge($data,$result);
        echo json_encode($datax);
    }
    private function getAccount($id)
    {
        $result = $this->mAccount->getAccount($id);
        if(!empty($result)){
            $result['en_employee_id'] = empty($result['employee_id'])?null:$this->secure->enc($result['employee_id']);
            $result['parent_idxx'] = $result['parent_idx'] == null?null:$this->secure->enc($result['parent_idx']);
            $result['ccount_typex'] = $this->secure->enc($result['organization_type']);
        }
        return $result;
    }
    public function formActAcc($idx="")
    {
        $id = $this->secure->dec($idx);
        $organization_segment   = html_escape(trim($this->input->post('tPosition', TRUE)));
        if(empty($idx)){
            if($organization_segment == 1){
                $this->form_validation->set_rules('accNo2', 'Kode Jabatan', 'trim|required',
                [
                    'required' => 'Kode Jabatan wajib di isi.'
                ]);
                $this->form_validation->set_rules('tSubAccount', 'Induk Turunan', 'trim|required',
                [
                    'required' => 'Induk Turunan wajib di isi.'
                ]);
            }
        }
        $this->form_validation->set_rules('organization_name', 'Nama Jabatan', 'trim|required',
        [
            'required' => 'Nama Jabatan wajib di isi.'
        ]);
        if($this->form_validation->run() == false){
            $msg['error']   = true;
            $msg['message'] = validation_errors();
            echo json_encode($msg);die();
        }
        // $account_type         = $this->secure->dec($this->input->post('tAccType', TRUE));
        $org_name       = html_escape(trim(strtoupper($this->input->post('organization_name', TRUE))));
        $head_name      = html_escape(trim(strtoupper($this->input->post('head_name', TRUE))));
        $accNo1         = html_escape(trim($this->input->post('accNo1', TRUE)));
        $accNo2x        = html_escape(trim($this->input->post('accNo2', TRUE)));
        $accNo2         = $accNo2x=='NaN'||$accNo2x==''?null:$accNo2x;
        $tSubAccountx   = html_escape(trim($this->input->post('tSubAccount')));
        $head_orgx      = html_escape(trim($this->input->post('head_organization')));
        $tSubAccount    = $this->secure->dec($tSubAccountx);
        $head_org       = $this->secure->dec($head_orgx);
        $temp_image     = html_escape(trim($this->input->post('temp_image', TRUE)));
        $url_image      = trim($this->input->post('url_image'));
        $status         = html_escape(trim($this->input->post('tStatus', TRUE)));
        $setHead        = html_escape(trim($this->input->post('setHead', TRUE)));
        $lockedAccount  = [
            '1130',
            '2110'
        ];
        $accId                = "";
        $office			= $this->office;
		$login			= $this->idx;
		$Now			= date('Y-m-d H:i:s');
        validate_csrf_token();
        $part_organization_number = explode('.',$accNo1);
        if(empty($idx)){
            if($organization_segment == 0){
                $count_segment = count($part_organization_number);
                $orgCodeId    = $this->ModelGenId->genIdUnlimited('ORG'.$this->office, $this->idx);
                if($orgCodeId==0){
                    $msg['status']  = true;
                    $msg['text']    = 'Error update Gen id, ORG'.$this->office;
                    echo json_encode($msg);die();
                }
                $orgCode        = "A".str_pad($orgCodeId,3,"0",STR_PAD_LEFT);
                if(strtoupper($orgCode) != strtoupper($accNo1)){
                    $msg['status']  = true;
                    $msg['text']    = 'Kode jabatan tidak match dengan system.';
                    echo json_encode($msg);die();
                }
                $organization_number = $orgCode;
                $cekNomorAkun   = $this->mAccount->cekNomorAkun($organization_number, $organization_segment);
                if($cekNomorAkun){
                    $msg['status']  = true;
                    $msg['text']    = 'Nomor '.$organization_number.' sudah di pakai oleh akun '.$cekNomorAkun['organization_name'];
                    echo json_encode($msg);die();
                }
                $parent_idx     = null;
                $parent_active  = 1;
                $data = [
                    'organization_name'             => $org_name,
                    'organization_number'           => $organization_number,
                    'parent_idx'                    => $parent_idx,
                    'parent_active'                 => $parent_active,
                    'organization_segment'          => $count_segment,
                    'organization_number_parent'    => $organization_number,
                    'photo'                         => $url_image,
                    'status'                        => $status,
                    'head_name'                     => $head_name,
                    'status_head'                   => $setHead,
                    'employee_id'                   => $head_org,
                    'company_id'                    => $this->office,
                    'created_by'                    => $this->idx,
                    'created_on'                    => date("Y-m-d H:i:s")
                ];
            }else{
                $count_segment = count($part_organization_number);
                $organization_number = $accNo1.'.'.$accNo2;
                $cekNomorAkun   = $this->mAccount->cekNomorAkun($organization_number, $organization_segment);
                if($cekNomorAkun){
                    $msg['status']  = true;
                    $msg['text']    = 'Nomor '.$organization_number.' sudah di pakai oleh akun '.$cekNomorAkun['organization_name'];
                    echo json_encode($msg);die();
                }
                $parent_idx     = $tSubAccount;
                $cekNumeringCount = $this->mAccount->cekNumeringCount($parent_idx, $organization_segment);
                if(!empty($cekNumeringCount['idx'])){
                    $countNumberingAccount = strlen($cekNumeringCount['organization_number_child']);
                    $countInputNo = strlen($accNo2);
                    if($countNumberingAccount != $countInputNo){
                        $msg['status']  = true;
                        $msg['text']    = 'Jumlah penomoran harus '.$countNumberingAccount.' digit, <br>Sesuai penomoran sebelumnya.';
                        echo json_encode($msg);die();
                    }
                }
                $parent_active  = 0;
                $parentWhere = [
                    'idx'  => $parent_idx
                ];
                $updateParentAmount = [
                    'parent_active'             => 1,
                    'modified_by'               => $this->idx,
                    'modified_on'               => date("Y-m-d H:i:s")
                ];
                $data = [
                    'organization_name'             => $org_name,
                    'organization_number'           => $organization_number,
                    'parent_idx'                    => $parent_idx,
                    'parent_active'                 => $parent_active,
                    'organization_segment'          => $count_segment+1,
                    'organization_number_parent'    => $accNo1,
                    'organization_number_child'     => $accNo2,
                    'photo'                         => $url_image,
                    'status'                        => $status,
                    'head_name'                     => $head_name,
                    'status_head'                   => $setHead,
                    'employee_id'                   => $head_org,
                    'company_id'                    => $this->office,
                    'created_by'                    => $this->idx,
                    'created_on'                    => date("Y-m-d H:i:s")
                ];
            }
            // var_dump('<pre>');var_dump($data);die;
            if($organization_segment == 0){
                $result = $this->mAccess->insertMultiData($data, 'master_organization');
            }else{
                $result = $this->mAccess->insertMultiUpdate($data, 'master_organization',$updateParentAmount,'master_organization',$parentWhere);
            }
            if($result == 1)
            {
                $msg['type']    = 'add';
                $msg['link']    = $this->link;
                $msg['success'] = true;
                $msg['text']    = 'Akun Berhasil ditambahkan.';
                echo json_encode($msg);die();
            }else{
                //show an error page or error message about the failed insert
                $msg['status']  = true;
                $msg['text']    = 'Koneksi error,<br>Silahkan cek koneksi internet anda.';
                echo json_encode($msg);die();
            }
        }else{
            $parent_idxx = html_escape(trim($this->input->post('parent_idx', TRUE)));
            $old_parent_idx = $this->secure->dec($parent_idxx);
            $count_segment = count($part_organization_number);
            $get_account = $this->mAccount->getAccount($id);
            $getHierarchy = $this->mAccount->getHierarchy($id);
            if(empty($get_account)){
                $msg['status']  = true;
                $msg['text']    = 'Data tidak ditemukan,<br>Silahkan Lapor IT.';
                echo json_encode($msg);die();
            }
            $jumlahChild = (int)$get_account['total_child'];
            $own_segment = (int)$get_account['organization_segment'];
            if($tSubAccount==$old_parent_idx){
                $parentWhere = [];
                $updateParentAmount = [];
                $where = [
                    'idx' => $id
                ];
                $data = [
                    'organization_name'             => $org_name,
                    'photo'                         => $url_image,
                    'status'                        => $status,
                    'head_name'                     => $head_name,
                    'status_head'                   => $setHead,
                    'employee_id'                   => $head_org,
                    'company_id'                    => $this->office,
                    'modified_by'                   => $this->idx,
                    'modified_on'                   => date("Y-m-d H:i:s")
                ];
                $updateChildNumber = "";
            }else{
                if($own_segment == 0){
                    $parentWhere = [];
                    $updateParentAmount = [];
                    $where = [
                        'idx' => $id
                    ];
                    $data = [
                        'organization_name'             => $org_name,
                        'photo'                         => $url_image,
                        'status'                        => $status,
                        'head_name'                     => $head_name,
                        'status_head'                   => $setHead,
                        'employee_id'                   => $head_org,
                        'company_id'                    => $this->office,
                        'modified_by'                   => $this->idx,
                        'modified_on'                   => date("Y-m-d H:i:s")
                    ];
                    $updateChildNumber = "";
                }else{
                    if(((int)$get_account['organization_segment']-1) != $count_segment){
                        $msg['status']  = true;
                        $msg['text']    = 'Beda segment tidak di izinkan.';
                        echo json_encode($msg);die();
                    }
                    $organization_number = $accNo1.'.'.$accNo2;
                    $cekNomorAkun   = $this->mAccount->cekNomorAkun($organization_number, $organization_segment);
                    if($cekNomorAkun){
                        $msg['status']  = true;
                        $msg['text']    = 'Nomor '.$organization_number.' sudah di pakai oleh akun '.$cekNomorAkun['organization_name'];
                        echo json_encode($msg);die();
                    }
                    $parent_idx     = $tSubAccount;
                    $cekNumeringCount = $this->mAccount->cekNumeringCount($parent_idx, $organization_segment);
                    if(!empty($cekNumeringCount['idx'])){
                        $countNumberingAccount = strlen($cekNumeringCount['organization_number_child']);
                        $countInputNo = strlen($accNo2);
                        if($countNumberingAccount != $countInputNo){
                            $msg['status']  = true;
                            $msg['text']    = 'Jumlah penomoran harus '.$countNumberingAccount.' digit, <br>Sesuai penomoran sebelumnya.';
                            echo json_encode($msg);die();
                        }
                    }
                    $childUpdate = "";
                    $now = date("Y-m-d H:i:s");
                    $idLogin = $this->idx;
                    if(!empty($getHierarchy)){
                        foreach($getHierarchy as $g){
                            if($g->idx != $id){
                                $arr_index = $own_segment - 2;
                                $orgExplode = explode('.', $g->organization_number);
                                $filteredData = array_slice($orgExplode, $own_segment);
                                $orgExplodeClear = implode('.', $filteredData);
                                $renew_parent_number = $organization_number.'.'.$orgExplodeClear;
                                $orgParentExplode = explode('.', $g->organization_number_parent);
                                $filteredDataParent = array_slice($orgParentExplode, $own_segment);
                                $orgParentExplodeClear = implode('.', $filteredDataParent);
                                if(empty($orgParentExplodeClear)){
                                    $renew_parent2_number = $organization_number;
                                }else{
                                    $renew_parent2_number = $organization_number.'.'.$orgParentExplodeClear;
                                }
                                $childUpdate .= "($g->idx, '$renew_parent_number', '$renew_parent2_number', $idLogin, '$now'),";
                            }
                        }
                    }
                    // var_dump('<pre>');var_dump($childUpdate);die;
                    $parent_active  = 0;
                    $parentWhere = [];
                    $updateParentAmount = [];
                    $where = [
                        'idx' => $id
                    ];
                    $data = [
                        'organization_name'             => $org_name,
                        'organization_number'           => $organization_number,
                        'parent_idx'                    => $parent_idx,
                        'organization_number_parent'    => $accNo1,
                        'organization_number_child'     => $accNo2,
                        'photo'                         => $url_image,
                        'status'                        => $status,
                        'status_head'                   => $setHead,
                        'employee_id'                   => $head_org,
                        'company_id'                    => $this->office,
                        'modified_by'                   => $this->idx,
                        'modified_on'                   => date("Y-m-d H:i:s")
                    ];

                    if(!empty($childUpdate)){
                        $childUpdate    = substr($childUpdate,0,strlen($childUpdate)-1);
                        $updateChildNumber = "INSERT INTO master_organization (idx, organization_number, organization_number_parent, modified_by, modified_on) values $childUpdate ON DUPLICATE KEY UPDATE organization_number=VALUES(organization_number),organization_number_parent=VALUES(organization_number_parent),modified_by=VALUES(modified_by),modified_on=VALUES(modified_on)";
                    }else{
                        $updateChildNumber = "";
                    }
                }
            }
            // var_dump('<pre>');var_dump($data);die;
            if($own_segment == 0){
                $result = $this->mAccount->updatedAuto($data, $where, $updateChildNumber);
            }else{
                $result = $this->mAccount->updatedAuto($data, $where, $updateChildNumber);
            }
            if($result == 1)
            {
                $msg['type']    = 'update';
                $msg['link']    = $this->link;
                $msg['success'] = true;
                $msg['text']    = 'Akun Berhasil diubah.';
                echo json_encode($msg);die();
            }else{
                //show an error page or error message about the failed insert
                $msg['status']  = true;
                $msg['text']    = 'Koneksi error,<br>Silahkan cek koneksi internet anda.';
                echo json_encode($msg);die();
            }
        }
    }
    public function deleteAccount()
    {
        $id     = $this->secure->dec($this->input->get('id', true));
        $get_account = $this->mAccount->getAccount($id);
        if(empty($get_account)){
            $msg['status']  = true;
            $msg['text']    = 'Data tidak ditemukan,<br>Silahkan Lapor IT.';
            echo json_encode($msg);die();
        }
        $organization_name = $get_account['organization_name'];
        $jumlahChild = (int)$get_account['total_child'];
        $jumlahEmployee = (int)$get_account['total_employee'];
        if($jumlahChild>0){
            $msg['status']  = true;
            $msg['text']    = '<b>'.$organization_name.'</b> masih mempunyai '.$jumlahChild.' sub.';
            echo json_encode($msg);die();
        }
        if($jumlahEmployee>0){
            $msg['status']  = true;
            $msg['text']    = '<b>'.$organization_name.'</b> masih mempunyai '.$jumlahChild.' karyawan.';
            echo json_encode($msg);die();
        }
        $result         = $this->mAccount->deleteOrganization($id);
        if ($result == 1) {
            $msg['success'] = true;
            $msg['text']    = 'Organisasi Berhasil dihapus.';
            echo json_encode($msg);die();
        }else{
            $msg['status']  = true;
            $msg['text']    = 'Koneksi error,<br>Silahkan cek koneksi internet anda.';
            echo json_encode($msg);die();
        }
    }
    public function created_api_key()
    {
        $client_id      = $this->input->post('tId');
        $client_code    = $this->input->post('tClCode');
        $update_api_master_client= [
            'status_api'    => 1,
            'modified_by'   => $this->idx,
            'modified_on'   => date("Y-m-d H:i:s")
        ];
        $insert_api_client = [
            'client_id'     => $client_id,
            'key'           => hash('sha256', $client_id.$client_code),
            'level'         => 1,
            'date_created'  => date("Y-m-d H:i:s")
        ];
        $insert = $this->mClient->createdAPIClient($client_id,$update_api_master_client,$insert_api_client);
        if($insert == 1)
        {
            $msg['type']    = 'create';
            $msg['success'] = true;
        }else{
            $msg['status']   = true;
        }
        echo json_encode($msg);
    }
    public function changeStatus()
    {
        $id_agent = $this->secure->dec($this->input->post('tId'));
        $id_status = $this->input->post('tClCode');
        $csrfName = $this->input->post('csrf');
        $csrfToken  = $this->session->csrf_token;
        if($csrfToken != $csrfName){
            $msg['status']   = true;
            $msg['message']  = "Token expired,<br>refresh page and try again.";
        }else{
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
            $cek = $this->mAgent->changeStatusMAgent($data, $id_agent);
            if($cek == true)
            {
                $msg['type']    = 'change';
                $msg['success'] = true;
            }else{
                $msg['status']   = true;
            }
        }
        echo json_encode($msg);
    }
    public function createAsUser()
    {
        $id_agent = $this->secure->dec($this->input->post('tId'));
        $client_code = $this->input->post('tClCode');
        $csrfName = $this->input->post('csrf');
        $csrfToken  = $this->session->csrf_token;
        if($csrfToken != $csrfName){
            $msg['status']   = true;
            $msg['message']  = "Token expired,<br>refresh page and try again.";
        }else{
            $data = [
                'status_user' => 1,
                'modified_by' => $this->idx,
                'modified_on' => date("Y-m-d H:i:s")
            ];
            $query = "
                insert into user_account (
                    user_id,
                    user_type,
                    user_name,
                    fullname,
                    email_id,
                    mobile_phone,
                    telephone,
                    tax_id,
                    address,
                    country,
                    province,
                    city,
                    postal_code,
                    state_code,
                    password,
                    status,
                    office_id,
                    created_by,
                    created_on
                )
                select
                    mobile_phone,
                    7,
                    agent_name,
                    agent_name,
                    email_id,
                    mobile_phone,
                    telephone,
                    tax_id,
                    address,
                    country,
                    province,
                    city,
                    postal_code,
                    state_code,
                    '".password_hash('123456', PASSWORD_DEFAULT)."',
                    1,
                    ".$this->office.",
                    ".$this->idx.",
                    '".date("Y-m-d H:i:s")."'
                from master_agent
                where idx = ".$id_agent."
                and status_user = 0
                and office_id = ".$this->office."
            ";
            $cek = $this->mAgent->createAsUserMAgent($data, $query, $id_agent);
            if($cek == true)
            {
                $msg['type']    = 'create';
                $msg['success'] = true;
            }else{
                $msg['status']   = true;
            }
        }
        echo json_encode($msg);
    }
    public function send_api_client_email()
    {
      $key = $this->input->post('tClCode');
      $name = $this->input->post('tName');
        // Konfigurasi email
        $config = [
            'mailtype'  => 'html',
            // 'charset'   => 'utf-8',
            'charset'   => 'iso-8859-1',
            'protocol'  => 'smtp',
            'smtp_host' => 'ssl://smtp.googlemail.com',
            'smtp_user' => 'prayogawixi95@gmail',  // Email gmail
            'smtp_pass'   => 'xxxxx',  // Password gmail
            'smtp_crypto' => 'ssl',
            'smtp_port'   => 465,
            'crlf'    => "\r\n",
            'newline' => "\r\n"
        ];
        // Load library email dan konfigurasinya
        $this->load->library('email', $config);
        $this->email->initialize($config);
        // Email dan nama pengirim
        $this->email->from('prayogawixi95@gmail', 'Test');
        // Email penerima
        $this->email->to('anwar.jte@gmail.com'); // Ganti dengan email tujuan
        // Subject email
        $this->email->subject('Kirim Email dengan SMTP Gmail CodeIgniter | Wixi');
        // Isi email
        $this->email->message($key." This API Key For ".$name);
        // Tampilkan pesan sukses atau error
        // if ($this->email->send()) {
        //     echo 'Sukses! email berhasil dikirim.';
        // } else {
        //     echo 'Error! email tidak dapat dikirim.';
        // }
        if($this->email->send())
        {
            $msg['type']    = 'send';
            $msg['success'] = true;
        }else{
            $msg['status']   = true;
        }
        echo json_encode($msg);
    }
    public function account_pdf()
    {
        include_once APPPATH.'/third_party/phpqrcode/qrlib.php';
        $profile_jte = '';
        $data['dataHeader'] = $this->mAccess->readtable('master_office', '', array('status' => 1, 'idx' => $this->office))->row_array();
        $data['dataDetail'] = $this->mAccess->readtable('master_organization', '', array('status' => 1),'','',array('organization_number' => 'asc'))->result();
        $html=$this->load->view('master/account_pdf',$data, true);
        $this->load->library('Pdf');
        // create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('KGL');
        $pdf->SetTitle('KGL');
        $pdf->SetSubject('TCPDF Tutorial');
        $pdf->SetKeywords('TCPDF, PDF, example, test, guide');
        // set default header data
        //$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 'PT QOURIER', $profile_jte, array(0,0,0), array(255,255,255));
        $pdf->SetHeaderData('access.png', 15, 'KGL', $profile_jte, array(0,0,0), array(255,255,255));
        // set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, 5, PDF_MARGIN_RIGHT);
        // $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        // Add Header
        $pdf->AddPage();
        // output the HTML content
        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->Output('CHART OF ACCOUNT'.'.pdf', 'I');
    }
}
/* End of file Office.php */
/* Location: ./application/controllers/Office.php */