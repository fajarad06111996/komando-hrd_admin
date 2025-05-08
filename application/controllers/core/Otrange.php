<?php
defined('BASEPATH') or exit('No direct script access allowed');
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use Nullix\CryptoJsAes\CryptoJsAes;
class Otrange extends AUTH_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('core/M_otrange', 'mOt');
        $this->load->model('ModelGenId');
        $this->load->library('security_function');
        $this->link	        = site_url('core/').strtolower(get_class($this));
        $this->filename	    = strtolower(get_class($this));
        $this->office       = $this->secure->dec($this->session->userdata('JToffice_id'));
        $this->office_name  = $this->session->userdata('JToffice_name');
        $this->idx          = $this->secure->dec($this->session->userdata('JTidx'));
        $this->username     = $this->session->userdata('JTuser_id');
        $this->user_name    = $this->session->userdata('JTusername');
        $this->enkey  	    = $this->config->item('encryption_key');
    }
    public function index2($start = 0)
    {
        $access = $this->security_function->permissions($this->filename . "-r");
        if (empty($access)) die("<center><h1>You can not get the right to access this module</h1></center>");
        if(!empty($this->security_function->permissions($this->filename . "-w"))){
            $data['lock']   = 1;
        }else{
            $data['lock']   = 0;
        }
        $this->load->library('pagination');
        $config['per_page'] = 10;
        if($this->input->post('type',true)){
            $type = $this->input->post('type',true);
            $pattern = "/^['\"]*(.*?)['\"]*$/";
            $replace = '$1';
            $key1   = $this->input->post('keyword1', true);
            $keyx1  = preg_replace($pattern, $replace,$key1);
            $key2   = $this->input->post('keyword2', true);
            $keyx2  = preg_replace($pattern, $replace,$key2);
            $key3   = $this->input->post('keyword3', true);
            $keyx3  = preg_replace($pattern, $replace,$key3);
            $key4   = $this->input->post('keyword4', true);
            $keyx4  = preg_replace($pattern, $replace,$key4);
            $key5   = $this->input->post('keyword5', true);
            $keyx5  = preg_replace($pattern, $replace,$key5);
            $key6   = $this->input->post('keyword6', true);
            $keyx6  = preg_replace($pattern, $replace,$key6);
            $key7   = $this->input->post('keyword7', true);
            $keyx7  = preg_replace($pattern, $replace,$key7);
            $key8   = $this->input->post('keyword8', true);
            $keyx8  = preg_replace($pattern, $replace,$key8);
            $key9   = $this->input->post('keyword9', true);
            $keyx9  = preg_replace($pattern, $replace,$key9);
            $key10   = $this->input->post('keyword10', true);
            $keyx10  = preg_replace($pattern, $replace,$key10);
            $key11   = $this->input->post('keyword11', true);
            $keyx11  = preg_replace($pattern, $replace,$key11);
            $key12   = $this->input->post('keyword12', true);
            $keyx12  = preg_replace($pattern, $replace,$key12);
            $key13   = $this->input->post('keyword13', true);
            $keyx13  = preg_replace($pattern, $replace,$key13);
            $key14   = $this->input->post('keyword14', true);
            $keyx14  = preg_replace($pattern, $replace,$key14);
            $key15   = $this->input->post('keyword15', true);
            $keyx15  = preg_replace($pattern, $replace,$key15);
            if($type == 1){
                $data['keyword']    = $keyx1;
                $data['type']       = $type;
                $sesi = array(
                    'JTkeywordDom' => $data['keyword'],
                    'JTtypeDom'    => $data['type']
                );
                $this->session->set_userdata($sesi);
            }elseif($type == 2){
                $data['keyword']    = $keyx2;
                $data['type']       = $type;
                $sesi = array(
                    'JTkeywordDom' => $data['keyword'],
                    'JTtypeDom'    => $data['type']
                );
                $this->session->set_userdata($sesi);
            }elseif($type == 3){
                $data['keyword']    = $keyx3;
                $data['type']       = $type;
                $sesi = array(
                    'JTkeywordDom' => $data['keyword'],
                    'JTtypeDom'    => $data['type']
                );
                $this->session->set_userdata($sesi);
            }elseif($type == 4){
                $data['keyword']    = $keyx4;
                $data['type']       = $type;
                $sesi = array(
                    'JTkeywordDom' => $data['keyword'],
                    'JTtypeDom'    => $data['type']
                );
                $this->session->set_userdata($sesi);
            }elseif($type == 5){
                $data['keyword']    = $keyx5;
                $data['type']       = $type;
                $sesi = array(
                    'JTkeywordDom' => $data['keyword'],
                    'JTtypeDom'    => $data['type']
                );
                $this->session->set_userdata($sesi);
            }elseif($type == 6){
                $data['keyword']    = $keyx6;
                $data['type']       = $type;
                $sesi = array(
                    'JTkeywordDom' => $data['keyword'],
                    'JTtypeDom'    => $data['type']
                );
                $this->session->set_userdata($sesi);
            }elseif($type == 7){
                $data['keyword']    = $keyx7;
                $data['type']       = $type;
                $sesi = array(
                    'JTkeywordDom' => $data['keyword'],
                    'JTtypeDom'    => $data['type']
                );
                $this->session->set_userdata($sesi);
            }elseif($type == 8){
                $data['keyword']    = $keyx8;
                $data['type']       = $type;
                $sesi = array(
                    'JTkeywordDom' => $data['keyword'],
                    'JTtypeDom'    => $data['type']
                );
                $this->session->set_userdata($sesi);
            }elseif($type == 9){
                $data['keyword']    = $keyx9;
                $data['type']       = $type;
                $sesi = array(
                    'JTkeywordDom' => $data['keyword'],
                    'JTtypeDom'    => $data['type']
                );
                $this->session->set_userdata($sesi);
            }elseif($type == 10){
                $data['keyword']    = $keyx10;
                $data['type']       = $type;
                $sesi = array(
                    'JTkeywordDom' => $data['keyword'],
                    'JTtypeDom'    => $data['type']
                );
                $this->session->set_userdata($sesi);
            }elseif($type == 11){
                $data['keyword']    = $keyx11;
                $data['type']       = $type;
                $sesi = array(
                    'JTkeywordDom' => $data['keyword'],
                    'JTtypeDom'    => $data['type']
                );
                $this->session->set_userdata($sesi);
            }elseif($type == 12){
                $data['keyword']    = $keyx12;
                $data['type']       = $type;
                $sesi = array(
                    'JTkeywordDom' => $data['keyword'],
                    'JTtypeDom'    => $data['type']
                );
                $this->session->set_userdata($sesi);
            }elseif($type == 13){
                $data['keyword']    = $keyx13;
                $data['type']       = $type;
                $sesi = array(
                    'JTkeywordDom' => $data['keyword'],
                    'JTtypeDom'    => $data['type']
                );
                $this->session->set_userdata($sesi);
            }elseif($type == 14){
                $data['keyword']    = $keyx14;
                $data['type']       = $type;
                $sesi = array(
                    'JTkeywordDom' => $data['keyword'],
                    'JTtypeDom'    => $data['type']
                );
                $this->session->set_userdata($sesi);
            }else{
                $data['keyword']    = $keyx15;
                $data['type']       = $type;
                $sesi = array(
                    'JTkeywordDom' => $data['keyword'],
                    'JTtypeDom'    => $data['type']
                );
                $this->session->set_userdata($sesi);
            }
        }else{
            $data['keyword']    = $this->session->userdata('JTkeywordDom');
            $data['type']       = $this->session->userdata('JTtypeDom');
        }
        $config['base_url']     = $this->link . '/index';
        $config['total_rows']   = $this->mTariff->countTarifDom($data['keyword'],$data['type']);
        $this->pagination->initialize($config);
        $data['start']          = $start;//$this->uri->segment(4);
        $data['total_rows']     = $config['total_rows'];
        $data['listTariff']     = $this->mTariff->queryTarifDom($config['per_page'], $start, $data['keyword'],$data['type']);
        $data['filename']       = $this->filename;
        $data["link"]           = $this->link;
        $data['getOffice']          = $this->mTariff->getOffice();
        $data['getPackages']        = $this->mTariff->getPackages();
        $data['getProduct']         = $this->mTariff->getProduct();
        $data['getOrigin']          = $this->mTariff->getOriginx();
        $data['getClient']          = $this->mTariff->getClient();
        $data['getShipmentType']    = $this->mTariff->getShipmentType();
        $data['getBasedOn']         = $this->mTariff->getBasedOn();
        $data['page']               = strtoupper(get_class($this));
        $this->template->views('master/v_tariff_domestik', $data);
    }
    public function index()
    {
        $access = $this->security_function->permissions($this->filename . "-r");
        if (empty($access)) die("<center><h1>You can not get the right to access this module</h1></center>");
        if(!empty($this->security_function->permissions($this->filename . "-w"))){
            // $data['write']  = '<div class="list-icons pull-right"><a href="javascript:void(0)" id="btnAdd" class="pull-right text-white small" title="Tambah Tariff" data-placement="right" data-popup="tooltip"><i class="icon-plus-circle2"></i></a></div>';
            $add = '<a href="javascript:void(0)" id="btnAdd" class="pull-right text-white small" title="Tambah Setup Lembur" data-placement="right" data-popup="tooltip"><i class="icon-plus-circle2"></i></a>';
            $import = '<a href="javascript:void(0);" id="btnImport" class="text-white small" title="Import More Data Tarif" data-placement="right" data-popup="tooltip"><i class="icon-file-upload"></i></a>';
            $data['write']  = "<div class='list-icons pull-right'>$add</div>";
            // $data['write']  = "<div class='list-icons pull-right'>$import&nbsp;&nbsp;&nbsp;$add</div>";
            $data['lock']   = 1;
        }else{
            $add = '';
            $import = '';
            $data['write']  = '<i class="icon-lock pull-right pt-1 " title="Locked New Data Location" data-placement="right" data-popup="tooltip"></i>';
            $data['lock']   = 0;
        }
        $data['filename']   = $this->filename;
        $data["link"]       = $this->link;
        $params     	    = '{"link": "'.$this->link.'", "csrf": "'.$this->session->csrf_token.'", "permW": "'.$this->security_function->permissions($this->filename . "-w").'", "permC": "'.$this->security_function->permissions($this->filename . "-c").'", "permX": "'.$this->security_function->permissions($this->filename . "-x").'", "base_url": "'.base_url().'", "lock": "'.$data['lock'].'"}';
        // encrypt
        $encrypted          = CryptoJsAes::encrypt($params, $this->enkey);
        $data['params']     = $encrypted;
        $this->template->views('core/v_ot_range', $data);
    }
    public function formData($idx="")
    {
        if(empty($idx)){
            $data['formact']            = $this->link.'/addOtRange';
            $data['title']              = "Tambah Setup Range Baru";
            $data['post']               = 0;
            $data['dataRange']          = null;
            $ot_id                      = null;
        }else{
            $id = $this->secure->dec($idx);
            $data['title']              = "Edit Setup Range";
            $data['post']               = 1;
            $data['dataRange']	        = $this->mAccess->getTable('master_ot',array('idx' => $id))->row_array();
            // var_dump('<pre>');var_dump($data['dataRange']);die;
            $data['formact']  = $this->link.'/updateOtRange/'.$idx;
            $ot_id                      = $this->secure->enc($data['dataRange']['ot_id']);
        }
        $data['link']       = $this->link;
        $data['page']       = "news";
        $data['judul']      = "Posting";
        $data['deskripsi']  = "Data News";
        $params     	    = '{"link": "'.$this->link.'", "csrf": "'.$this->session->csrf_token.'", "permW": "'.$this->security_function->permissions($this->filename . "-w").'", "permC": "'.$this->security_function->permissions($this->filename . "-c").'", "permX": "'.$this->security_function->permissions($this->filename . "-x").'", "base_url": "'.base_url().'", "post": "'.$data['post'].'", "ot_id": "'.$ot_id.'"}';
        // encrypt
        $encrypted          = CryptoJsAes::encrypt($params, $this->enkey);
        $data['params']     = $encrypted;
        $this->template->views('core/v_formotrange', $data);
    }
    public function import()
    {
        $access = $this->security_function->permissions($this->filename . "-r");
        if (empty($access)) die("<center><h1>You can not get the right to access this module</h1></center>");

        $data['filename']   = $this->filename;
        $data['link']       = $this->link;
        $data['page']       = strtoupper(get_class($this));
        $data['judul']      = 'Import Tarif';
        // $data['menuadd']    = $this->menuadd;
        $params     	    = '{"link": "'.$this->link.'", "csrf": "'.$this->session->csrf_token.'", "permW": "'.$this->security_function->permissions($this->filename . "-w").'", "permC": "'.$this->security_function->permissions($this->filename . "-c").'", "permX": "'.$this->security_function->permissions($this->filename . "-x").'", "base_url": "'.base_url().'"}';
        // encrypt
        $encrypted          = CryptoJsAes::encrypt($params, $this->enkey);
        $data['params']     = $encrypted;
        $this->template->views('master/v_importtariffrange', $data);
    }
    // CONTOH LAIN CONTROLLER2 POST DATATABLE SERVERSIDE
    function get_ajax()
    {
        $csrfToken  = validate_csrf_token();
        if($csrfToken == false){
            $list = array();
            $draw = 0;
            $recordsTotal = 0;
            $recordsFiltered = 0;
        }else{
            $list = $this->mOt->get_datatables();
            $draw = (int)$_POST['draw'];
            $recordsTotal = (int)$this->mOt->count_all();
            $recordsFiltered = (int)$this->mOt->count_filtered();
        }
        // var_dump('<pre>');var_dump($list);die;
        $data = array();
        $no = @$_POST['start'];
        foreach ($list as $item) {
            $idx = $this->secure->enc($item->idx);
            // $info = "<h5><a href='javascript:void(0);' id='$item->idx' data='$item->hub_name' code='1' class='bInfo text-center badge badge-primary' data-popup='tooltip' title='Hub Info' data-placement='right'><i class='fa fa-info fa-lg'></i></a></h5>";
            if(!empty($this->security_function->permissions($this->filename . "-c"))){
                $change = "<h5><a href='javascript:void(0);' id='$idx' idx='$item->idx' class='bEdit text-center badge badge-info' data-popup='tooltip' title='Edit Setup' data-placement='right'><i class='icon-pencil7'></i></a></h5>";
                if($item->status == 1)
                {
                    $statusU = "<h5><a href='javascript:void(0);' id='$idx' data='$item->description' code='1' class='bStatus text-center badge badge-success' data-popup='tooltip' title='Change Status' data-placement='right'>Aktif</a></h5>";
                } else {
                    $statusU = "<h5><a href='javascript:void(0);' id='$idx' data='$item->description' code='0' class='bStatus text-center badge badge-danger' data-popup='tooltip' title='Change Status' data-placement='right'>Non Aktif</a></h5>";
                }
            }else{
                $change = '<h5><span class="badge badge-warning"><i class="icon-lock" data-popup="tooltip" title="Locked Edit Setup" data-placement="right"></i></span></h5>';
                if($item->status == 1)
                {
                    $statusU = "<span class='text-center badge badge-success'>Aktif</span>";
                }else{
                    $statusU = "<span class='text-center badge badge-danger'>Non Aktif</span>";
                }
            }
            if(!empty($this->security_function->permissions($this->filename . "-x"))){
                $execute  = "<h5><a href='javascript:void(0);' id='$idx' data='' class='bDelete text-center badge badge-danger' data-popup='tooltip' title='Delete Setup' data-placement='right'><i class='icon-bin'></i></a></h5>";
            }else{
                $execute  = '<h5><span class="badge badge-warning"><i class="icon-lock" data-popup="tooltip" title="Locked Delete Setup" data-placement="right"></i></span></h5>';
            }
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = "<div class='btn-group'>$change</div>";
            $row[] = $statusU;
            $row[] = $item->ot_code;
            $row[] = "$item->description";
            // add html for action
            $data[] = $row;
        }
        $output = array(
            "draw"            => $draw,
            "recordsTotal"    => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data"            => $data,
        );
        // output to json format
        echo json_encode($output);
    }
    function get_rangeDetail()
    {
        $ot_idx     = $this->input->post('ot_id');
        $ot_id      = $this->secure->dec($ot_idx);
        $csrfToken  = validate_csrf_token();
        if($csrfToken == false){
            $list = array();
            $recordsTotal = 0;
            $recordsFiltered = 0;
        }else{
            $list = $this->mOt->get_rangeDetail($ot_id);
            $recordsTotal = (int)$this->mOt->count_all_rangeDetail($ot_id);
            $recordsFiltered = (int)$this->mOt->count_filtered_rangeDetail($ot_id);
        }
        $data = array();
        $no = @$_POST['start'];
        $i = 1;
        foreach ($list as $item) {
            $idx = $this->secure->enc($item->idx);
            if(!empty($this->security_function->permissions($this->filename . "-c"))){
                $change = "<h6><a href='javascript:void(0);' id='$idx' class='bEditDetail text-center badge badge-info' data-popup='tooltip' title='Edit Detail' data-placement='left'><i class='icon-pencil5'></i></a></h6>";
            }else{
                $change = '<h6><span class="badge badge-warning"><i class="icon-lock" data-popup="tooltip" title="Locked Edit Detail" data-placement="left"></i></span></h6>';
            }
            if(!empty($this->security_function->permissions($this->filename . "-x"))){
                $execute  = "<h6><a href='javascript:void(0);' id='$idx' data='$no' job='$ot_idx' class='bDeleteDetail text-center badge badge-danger' data-popup='tooltip' title='Delete Detail' data-placement='left'><i class='icon-bin'></i></a></h6>";
            }else{
                $execute  = '<h6><span class="bDelete badge badge-warning"><i class="icon-lock" data-popup="tooltip" title="Locked Delete Detail" data-placement="left"></i></span></h6>';
                $cancel = "";
            }
            if($item->status_um==1){
                $nilai = "<div class='form-check form-check-inline'>
                            <input class='form-check-input' type='checkbox' name='tUmx' value='1' checked id='defaultCheck1' disabled>
                            <label class='form-check-label' for='defaultCheck1'>
                                UM
                            </label>
                        </div>";
            }else{
                $nilai = "<input type='text' value='$item->value' class='textbox-xxs col-sm-11 text-center tMetrikx' placeholder='VOL' readonly>";
            }
            $tot = $item->type_of_value==1?'xJam':'x1';
            $row = array();
            $row[] = $i;
            $row[] = "<input type='text' value='$item->min_hour' class='textbox-xxs col-sm-11 text-center' placeholder='P' readonly>";
            $row[] = "<input type='text' value='$item->max_hour' class='textbox-xxs col-sm-11 text-center' placeholder='L' readonly>";
            $row[] = "<input type='text' value='$tot' class='textbox-xxs col-sm-11 text-center' placeholder='T' readonly>";
            $row[] = $nilai;
            $row[] = "<div class='btn-group'>$change$execute</div>";
            $data[] = $row;
            $no++;
            $i++;
        }
        $output = array(
            "draw"            => @$_POST['draw'],
            "recordsTotal"    => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data"            => $data
        );
        echo json_encode($output);
    }
    public function getDataAjaxRemote()
    {
        cek_csrf();
        $search         = $this->input->post('search');
        $results        = $this->mTariff->getDataAjaxRemote($search, 'data');
        $countresults   = $this->mTariff->getDataAjaxRemote($search, 'count');
        $selectajax[]   = array();
        foreach($results as $row){
            $selectajax[] = array(
                'id'    => $this->secure->enc($row['idx']),
                'text'  => $row['location_name'].'-'.$row['district_name'].'-'.$row['city_name'].' || '.$row['location_code'].'-'.$row['postal_code']
            );
        }
        $select['items'] = $selectajax;
        $select['total_count'] = $countresults;
        $this->output->set_content_type('application/json')->set_output(json_encode($select));
    }
    public function getDataAjaxRemoteId($idx,$csrf_token)
    {
        $csrfToken  = $this->session->csrf_token;
        if($csrfToken != $csrf_token){
            die("<center><h1>You can not get the right to access this module</h1></center>");
        }
        $id             = $this->secure->dec($idx);
        $search         = $this->input->post('search');
        $results        = $this->mTariff->getDataAjaxRemoteId($id, 'data');
        $countresults   = $this->mTariff->getDataAjaxRemoteId($id, 'count');
        $selectajax[]   = array();
        foreach($results as $row){
            $selectajax[] = array(
                'id'    => $this->secure->enc($row['idx']),
                'text'  => $row['location_name'].'-'.$row['district_name'].'-'.$row['city_name'].' || '.$row['location_code'].'-'.$row['postal_code']
            );
        }
        $select['items']        = $selectajax;
        $select['total_count']  = $countresults;
        $this->output->set_content_type('application/json')->set_output(json_encode($select));
    }
    public function get_autocomplete(){
        if (isset($_GET['term'])) {
            $result = $this->mTariff->search_dest($_GET['term']);
            if (count($result) > 0) {
                foreach ($result as $row)
                    $arr_result[] = array(
                        'label'   => $row->nama
                    );
                    echo json_encode($arr_result);
            }
        }
    }
    public function addOtRange()
    {
        $this->form_validation->set_rules('description', 'Description', 'trim|required');
        if ($this->form_validation->run() == false) {
            $msg['error']   = true;
            $msg['msg']     = validation_errors();
            echo json_encode($msg);die;
        }
        $csrfToken  = validate_csrf_token();
        if($csrfToken==false){
            $msg['status']  = true;
            $msg['msg']     = "Token invalid.";
            echo json_encode($msg);die;
        }
        $description    = filter_var(trim($this->input->post('description', TRUE)), FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_HIGH);
        $status         = filter_var(trim($this->input->post('tStatus')), FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_HIGH);
        $tMin           = $this->input->post('tMin');
        $tMax           = $this->input->post('tMax');
        $tTot           = $this->input->post('tTot');
        $tValue         = $this->input->post('tValue');
        $tUm            = $this->input->post('tUm');
        $tEnd           = $this->input->post('tEndHidden');
        // var_dump($tEnd);die;
        $insertRangeDetail = [];
        $total_item = count($tTot);
        $ot_id      = $this->ModelGenId->genIdUnlimited('OTID', $this->idx);
        $ot_code    = "OT".str_pad($ot_id,3,"0",STR_PAD_LEFT);
        $data = [
            'ot_id'         => $ot_id,
            'ot_code'       => $ot_code,
            'description'   => $description,
            'company_idx'   => $this->office,
            'status'        => $status,
            'created_by'    => $this->idx,
            'created_on'    => date("Y-m-d H:i:s")
        ];
        $i = 1;
        foreach($tTot as $index => $c){
            $iBefore = $index - 1 < 0?0:$index - 1;
            $type_of_value = filter_var($c, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_HIGH);
            $min_qty = empty($tMin[$index])?0:filter_var($tMin[$index], FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_HIGH);
            $max_qty = empty($tMax[$index])?0:filter_var($tMax[$index], FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_HIGH);
            $value = empty($tValue[$index])?0:filter_var($tValue[$index], FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_HIGH);
            $status_end = empty($tEnd[$index])?0:filter_var($tEnd[$index], FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_HIGH);
            $status_um = empty($tUm[$index])?0:filter_var($tUm[$index], FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_HIGH);
            $noMinQty = $index + 1;
            if($index > 0){
                if($i != $total_item){
                    if($max_qty < $min_qty){
                        $msg['status']  = true;
                        // $msg['msg']     = "Max Qty $index tidak boleh lebih kecil dari Min Qty $total_item";
                        $msg['msg']     = "Max Qty $noMinQty tidak boleh lebih kecil dari Min Qty $noMinQty";
                        echo json_encode($msg);die;
                    }
                }
                if($min_qty < $tMax[$iBefore]){
                    $noMaxQty = $iBefore + 1;
                    $msg['status']  = true;
                    $msg['msg']     = "Min Qty $noMinQty tidak boleh lebih kecil dari Max Qty $noMaxQty";
                    echo json_encode($msg);die;
                }
            }else{
                if($max_qty < $min_qty){
                    $msg['status']  = true;
                    $msg['msg']     = "Max Qty $noMinQty tidak boleh lebih kecil dari Min Qty $noMinQty";
                    echo json_encode($msg);die;
                }
            }
            $dataRangeDet = [
                "ot_id"         => $ot_id,
                "min_hour"      => $min_qty,
                "max_hour"      => $max_qty,
                "type_of_value" => $type_of_value,
                "value"         => $value,
                "company_idx"   => $this->office,
                "status_end"    => $status_end,
                "status_um"     => $status_um,
                "status"        => 1,
                'created_by'    => $this->idx,
                'created_on'    => date('Y-m-d H:i:s')
            ];
            $insertRangeDetail[] = $dataRangeDet;
            $i++;
        }
        // var_dump('<pre>');var_dump($insertRangeDetail);die;
        $result = $this->mOt->insertOtRange($data, $insertRangeDetail);
        if($result == 1)
        {
            $msg['success'] = true;
            $msg['msg']     = 'Tambah setup berhasil.';
            $msg['link']    = $this->link;
            echo json_encode($msg);die;
        }
        else
        {
            $msg['status']  = true;
            $msg['msg']     = 'Tambah setup gagal.';
            echo json_encode($msg);die;
        }
    }
    public function updateOtRange($idx)
    {
        $this->form_validation->set_rules('description', 'Description', 'trim|required');
        if ($this->form_validation->run() == false) {
            $msg['error']   = true;
            $msg['msg']     = validation_errors();
            echo json_encode($msg);die;
        }
        $csrfToken  = validate_csrf_token();
        if($csrfToken==false){
            $msg['status']  = true;
            $msg['msg']     = "Token invalid.";
            echo json_encode($msg);die;
        }
        $id                         = $this->secure->dec($idx);
        $description                = filter_var(trim($this->input->post('description', TRUE)), FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_HIGH);
        $status                     = filter_var(trim($this->input->post('tStatus')), FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_HIGH);
        // var_dump($tEnd);die;
        $data = [
            'description'               => $description,
            'status'                    => $status,
            'modified_by'               => $this->idx,
            'modified_on'               => date("Y-m-d H:i:s")
        ];
        $i = 1;
        // var_dump('<pre>');var_dump($insertRangeDetail);die;
        $result = $this->mOt->updateOtRange($id, $data);
        if($result == 1)
        {
            $msg['success'] = true;
            $msg['msg']     = 'Ubah Setup berhasil.';
            $msg['link']    = $this->link;
            echo json_encode($msg);die;
        }
        else
        {
            $msg['status']  = true;
            $msg['msg']     = 'Ubah Setup gagal.';
            echo json_encode($msg);die;
        }
    }
    public function addOtDetail()
    {
        $this->form_validation->set_rules('ot_id', 'OT ID', 'trim|required');
        $this->form_validation->set_rules('tMinx', 'MIN JAM', 'trim|required');
        $this->form_validation->set_rules('tMaxx', 'MAX JAM', 'trim|required');
        $this->form_validation->set_rules('tTotx', 'TIPE SETUP', 'trim|required');
        $this->form_validation->set_rules('tValuex', 'NILAI', 'trim|required');
        $this->form_validation->set_rules('tEndHiddenx', 'STATUS END', 'trim|required');
        if ($this->form_validation->run() == false) {
            $msg['error']   = true;
            $msg['msg']     = validation_errors();
            echo json_encode($msg);die;
        }
        $csrf = validate_csrf_token();
        if($csrf==false){
            $msg['status']  = true;
            $msg['msg']     = "session invalid.";
            echo json_encode($msg);die;
        }
        $ot_idx = filter_var(trim($this->input->post('ot_id', true)), FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_HIGH);
        $min_qty = filter_var(trim($this->input->post('tMinx', true)), FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_HIGH);
        $max_qty = filter_var(trim($this->input->post('tMaxx', true)), FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_HIGH);
        $type_of_value = filter_var(trim($this->input->post('tTotx', true)), FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_HIGH);
        $tariff_value = filter_var(trim($this->input->post('tValuex', true)), FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_HIGH);
        $status_end = filter_var(trim($this->input->post('tEndHiddenx', true)), FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_HIGH);
        $status_um = filter_var(trim($this->input->post('tUmx', true)), FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_HIGH);
        $ot_id = $this->secure->dec($ot_idx);
        $getLast = $this->mOt->get_last_range($ot_id);
        if(!empty($getLast)){
            $idxLast = $getLast['idx'];
            // if($min_qty>$max_qty){
            //     $msg['status']  = true;
            //     $msg['msg']     = "Max Qty tidak boleh lebih kecil dari Min Qty";
            //     echo json_encode($msg);die;
            // }
            if($getLast['max_hour']==0 || $getLast['max_hour']<$min_qty){
                if($min_qty<$getLast['min_hour']){
                    $msg['status']  = true;
                    $msg['msg']     = "Min Qty terakhir harus lebih besar dari Min Qty";
                    echo json_encode($msg);die;
                }
                $updateLastRange = [
                    'max_hour'      => $min_qty,
                    "status_end"    => 0,
                    'modified_by'   => $this->idx,
                    'modified_on'   => date('Y-m-d H:i:s')
                ];
            }else{
                if($getLast['max_hour']!=$min_qty){
                    $msg['status']  = true;
                    $msg['msg']     = "Min Qty harus sama dengan Max Qty terakhir";
                    echo json_encode($msg);die;
                }
                $updateLastRange = [
                    "status_end"    => 0,
                    'modified_by'   => $this->idx,
                    'modified_on'   => date('Y-m-d H:i:s')
                ];
            }
        }else{
            $idxLast = "";
            $updateLastRange = [];
        }
        $dataRangeDet = [
            "ot_id"         => $ot_id,
            "min_hour"      => $min_qty,
            "max_hour"      => $max_qty,
            "type_of_value" => $type_of_value,
            "value"         => $tariff_value,
            "company_idx"   => $this->office,
            "status_end"    => 1,
            "status_um"     => empty($status_um)?0:$status_um,
            "status"        => 1,
            'created_by'    => $this->idx,
            'created_on'    => date('Y-m-d H:i:s')
        ];
        $result = $this->mOt->insertOtDetail($dataRangeDet, $idxLast, $updateLastRange);
        if($result == 1)
        {
            $msg['success'] = true;
            $msg['msg']     = 'Tambah setup detail berhasil.';
            $msg['link']    = $this->link;
            echo json_encode($msg);die;
        }
        else
        {
            $msg['status']  = true;
            $msg['msg']     = 'Tambah setup detail gagal.';
            echo json_encode($msg);die;
        }
    }
    public function editOtDetail()
    {
        $id     = $this->secure->dec($this->input->get('id'));
        $result = $this->mOt->editOtDetail($id);
        echo json_encode($result);
    }
    public function updateOtDetail($idx)
    {
        $this->form_validation->set_rules('ot_id', 'OT ID', 'trim|required');
        $this->form_validation->set_rules('tMinx', 'MIN JAM', 'trim|required');
        $this->form_validation->set_rules('tMaxx', 'MAX JAM', 'trim|required');
        $this->form_validation->set_rules('tTotx', 'TIPE SETUP', 'trim|required');
        $this->form_validation->set_rules('tValuex', 'NILAI', 'trim|required');
        $this->form_validation->set_rules('tEndHiddenx', 'STATUS END', 'trim|required');
        if ($this->form_validation->run() == false) {
            $msg['error']   = true;
            $msg['msg']     = validation_errors();
            echo json_encode($msg);die;
        }
        $csrf = validate_csrf_token();
        if($csrf==false){
            $msg['status']  = true;
            $msg['msg']     = "session invalid.";
            echo json_encode($msg);die;
        }
        $ot_idx = filter_var(trim($this->input->post('ot_id', true)), FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_HIGH);
        $min_qty = filter_var(trim($this->input->post('tMinx', true)), FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_HIGH);
        $max_qty = filter_var(trim($this->input->post('tMaxx', true)), FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_HIGH);
        $type_of_value = filter_var(trim($this->input->post('tTotx', true)), FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_HIGH);
        $tariff_value = filter_var(trim($this->input->post('tValuex', true)), FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_HIGH);
        $status_end = filter_var(trim($this->input->post('tEndHiddenx', true)), FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_HIGH);
        $status_um = filter_var(trim($this->input->post('tUmx', true)), FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_HIGH);
        $id         = $this->secure->dec($idx);
        $ot_id      = $this->secure->dec($ot_idx);
        $getLast    = $this->mOt->get_last_range2($ot_id, $id);
        // var_dump('<pre>');var_dump($getLast);die;
        if($getLast){
            if($getLast['max_hour']!=$min_qty){
                $msg['status']  = true;
                $msg['msg']     = "Min Jam harus sama dengan Max Jam terakhir";
                echo json_encode($msg);die;
            }
            if($status_end==0){
                if($min_qty>$max_qty){
                    $msg['status']  = true;
                    $msg['msg']     = "Max Jam tidak boleh lebih kecil dari Min Jam";
                    echo json_encode($msg);die;
                }
            }
        }
        $updateRange = [
            "min_hour"      => $min_qty,
            "max_hour"      => $max_qty,
            "type_of_value" => $type_of_value,
            "value"         => $tariff_value,
            'status_um'     => empty($status_um)?0:$status_um,
            'modified_by'   => $this->idx,
            'modified_on'   => date('Y-m-d H:i:s')
        ];
        $result = $this->mOt->updateOtDetail($id, $updateRange);
        if($result == 1)
        {
            $msg['success'] = true;
            $msg['msg']     = 'Update Setup Detail berhasil.';
            $msg['link']    = $this->link;
            echo json_encode($msg);die;
        }
        else
        {
            $msg['status']  = true;
            $msg['msg']     = 'Update Setup Detail gagal.';
            echo json_encode($msg);die;
        }
    }
    public function deleteOtDetail()
    {
        $this->form_validation->set_data($_GET);
        $this->form_validation->set_rules('id', 'ID', 'trim|required');
        $this->form_validation->set_rules('ot_id', 'OT ID', 'trim|required');
        $this->form_validation->set_rules('csrfsession', 'SESSION', 'trim|required');
        if ($this->form_validation->run() == false) {
            $msg['error']   = true;
            $msg['msg']     = validation_errors();
            echo json_encode($msg);die;
        }
        $csrf = validate_csrf_token();
        if($csrf==false){
            $msg['status']  = true;
            $msg['msg']     = "session invalid.";
            echo json_encode($msg);die;
        }
        $idx        = filter_var(trim($this->input->get('id', true)), FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_HIGH);
        $ot_idx     = filter_var(trim($this->input->get('ot_id', true)), FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_HIGH);
        $id         = $this->secure->dec($idx);
        $ot_id      = $this->secure->dec($ot_idx);
        $getLast    = $this->mOt->get_last_range2($ot_id, $id);
        // var_dump('<pre>');var_dump($getLast);die;
        if($getLast){
            $idxLast = $getLast['idx'];
            $updateLastRange = [
                'status_end'    => 1,
                'modified_by'   => $this->idx,
                'modified_on'   => date('Y-m-d H:i:s')
            ];
        }else{
            $idxLast = '';
            $updateLastRange = [];
        }

        $updateRange = [
            'status_end'    => 0,
            'status'        => 0,
            'modified_by'   => $this->idx,
            'modified_on'   => date('Y-m-d H:i:s')
        ];
        $result = $this->mOt->deleteOtDetail($id, $updateRange, $idxLast, $updateLastRange);
        if($result == 1)
        {
            $msg['success'] = true;
            $msg['msg']     = 'Hapus setup berhasil.';
            $msg['link']    = $this->link;
            echo json_encode($msg);die;
        }
        else
        {
            $msg['status']  = true;
            $msg['msg']     = 'Hapus setup gagal.';
            echo json_encode($msg);die;
        }
    }
    public function importTarif()
    {
        $upload_file = $_FILES['tFilename']['name'];
        $extension = pathinfo($upload_file, PATHINFO_EXTENSION);
        if($extension=='csv')
        {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
        }else if($extension=='xls'){
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
        }else{
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        }
        $spreadsheet    = $reader->load( $_FILES['tFilename']['tmp_name']);
        $sheetdata      = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
        // var_dump('<pre>');var_dump($sheetdata);die;
        $sheetcount     = count($sheetdata);
        $data           = array();
        $fail_upload    = array();
        $insertValue    = "";
        $insertValueD   = "";
        $updateValue    = "";
        $Now            = date('Y-m-d H:i:s');
        $id_login       = $this->idx;
        $user_name      = $this->user_name;
        $office         = $this->office;
        $office_name    = $this->office_name;
        $fail           = 0;
        $success        = 0;
        $i              = 1;
        $sheetdatax     = array_slice($sheetdata,1);
        foreach($sheetdatax as $r){
            // var_dump(empty($r['F']));die;
            // initialize data
            $client_code        = trim(htmlentities($r['A']));
            $service            = trim(htmlentities($r['B']));
            $org_code           = trim(htmlentities($r['C']));
            $dest_code          = trim(htmlentities($r['D']));
            $description        = trim(htmlentities($r['E']));
            $based_on_charge    = str_replace(' ', '', strtoupper(trim(htmlentities($r['F']))));
            $min_qty            = trim(htmlentities($r['G']));
            $max_qty            = trim(htmlentities($r['H']));
            $tarif_type         = trim(htmlentities($r['I']));
            $tarif              = trim(htmlentities($r['J']));
            $leadtime           = trim(htmlentities($r['K']));
            $latest             = trim(htmlentities($r['L']));

            // validation data
            if(empty($r['A'])){
                $fail_mains[]  = $fail + 1;
                $fail_upload[] = $dest_code;
                $insert[]      = $success;
                array_pop($insert);
                $fail_awb       = implode(", ",$fail_upload);
                $msg['awbfail'] = !empty($fail_mains)?count($fail_mains)." Tariff Gagal Import <i class='icon-point-right font-size-sm'> </i>". $fail_awb ." Baris".$i."A Tidak boleh kosong":'';
                $msg['status'] = true;
                $msg['message'] = !empty($insert)?'<strong>'.count($insert).'</strong> Data Terimport':'<strong>0</strong> Data Terimport';
                echo json_encode($msg);die;
            }else if(empty($r['B'])){
                $fail_mains[]  = $fail + 1;
                $fail_upload[] = $dest_code;
                $insert[]      = $success;
                array_pop($insert);
                $fail_awb       = implode(", ",$fail_upload);
                $msg['awbfail'] = !empty($fail_mains)?count($fail_mains)." Tariff Gagal Import <i class='icon-point-right font-size-sm'> </i>". $fail_awb ." Baris".$i."B Tidak boleh kosong":'';
                $msg['status'] = true;
                $msg['message'] = !empty($insert)?'<strong>'.count($insert).'</strong> Data Terimport':'<strong>0</strong> Data Terimport';
                echo json_encode($msg);die;
            }else if(empty($r['C'])){
                $fail_mains[]  = $fail + 1;
                $fail_upload[] = $dest_code;
                $insert[]      = $success;
                array_pop($insert);
                $fail_awb       = implode(", ",$fail_upload);
                $msg['awbfail'] = !empty($fail_mains)?count($fail_mains)." Tariff Gagal Import <i class='icon-point-right font-size-sm'> </i>". $fail_awb ." Baris".$i."C Tidak boleh kosong":'';
                $msg['status'] = true;
                $msg['message'] = !empty($insert)?'<strong>'.count($insert).'</strong> Data Terimport':'<strong>0</strong> Data Terimport';
                echo json_encode($msg);die;
            }else if(empty($r['D'])){
                $fail_mains[]  = $fail + 1;
                $fail_upload[] = $dest_code;
                $insert[]      = $success;
                array_pop($insert);
                $fail_awb       = implode(", ",$fail_upload);
                $msg['awbfail'] = !empty($fail_mains)?count($fail_mains)." Tariff Gagal Import <i class='icon-point-right font-size-sm'> </i>". $fail_awb ." Baris".$i."D Tidak boleh kosong":'';
                $msg['status'] = true;
                $msg['message'] = !empty($insert)?'<strong>'.count($insert).'</strong> Data Terimport':'<strong>0</strong> Data Terimport';
                echo json_encode($msg);die;
            }else if(empty($r['E'])){
                $fail_mains[]  = $fail + 1;
                $fail_upload[] = $dest_code;
                $insert[]      = $success;
                array_pop($insert);
                $fail_awb       = implode(", ",$fail_upload);
                $msg['awbfail'] = !empty($fail_mains)?count($fail_mains)." Tariff Gagal Import <i class='icon-point-right font-size-sm'> </i>". $fail_awb ." Baris".$i."E Tidak boleh kosong":'';
                $msg['status'] = true;
                $msg['message'] = !empty($insert)?'<strong>'.count($insert).'</strong> Data Terimport':'<strong>0</strong> Data Terimport';
                echo json_encode($msg);die;
            }else if(empty($r['F'])){
                $fail_mains[]  = $fail + 1;
                $fail_upload[] = $dest_code;
                $insert[]      = $success;
                array_pop($insert);
                $fail_awb       = implode(", ",$fail_upload);
                $msg['awbfail'] = !empty($fail_mains)?count($fail_mains)." Tariff Gagal Import <i class='icon-point-right font-size-sm'> </i>". $fail_awb ." Baris".$i."F Tidak boleh kosong":'';
                $msg['status'] = true;
                $msg['message'] = !empty($insert)?'<strong>'.count($insert).'</strong> Data Terimport':'<strong>0</strong> Data Terimport';
                echo json_encode($msg);die;
            }else if(empty($r['G'])){
                $fail_mains[]  = $fail + 1;
                $fail_upload[] = $dest_code;
                $insert[]      = $success;
                array_pop($insert);
                $fail_awb       = implode(", ",$fail_upload);
                $msg['awbfail'] = !empty($fail_mains)?count($fail_mains)." Tariff Gagal Import <i class='icon-point-right font-size-sm'> </i>". $fail_awb ." Baris".$i."G Tidak boleh kosong":'';
                $msg['status'] = true;
                $msg['message'] = !empty($insert)?'<strong>'.count($insert).'</strong> Data Terimport':'<strong>0</strong> Data Terimport';
                echo json_encode($msg);die;
            }else if(empty($r['H'])){
                $fail_mains[]  = $fail + 1;
                $fail_upload[] = $dest_code;
                $insert[]      = $success;
                array_pop($insert);
                $fail_awb       = implode(", ",$fail_upload);
                $msg['awbfail'] = !empty($fail_mains)?count($fail_mains)." Tariff Gagal Import <i class='icon-point-right font-size-sm'> </i>". $fail_awb ." Baris".$i."H Tidak boleh kosong":'';
                $msg['status'] = true;
                $msg['message'] = !empty($insert)?'<strong>'.count($insert).'</strong> Data Terimport':'<strong>0</strong> Data Terimport';
                echo json_encode($msg);die;
            }else if(empty($r['I'])){
                $fail_mains[]  = $fail + 1;
                $fail_upload[] = $dest_code;
                $insert[]      = $success;
                array_pop($insert);
                $fail_awb       = implode(", ",$fail_upload);
                $msg['awbfail'] = !empty($fail_mains)?count($fail_mains)." Tariff Gagal Import <i class='icon-point-right font-size-sm'> </i>". $fail_awb ." Baris".$i."I Tidak boleh kosong":'';
                $msg['status'] = true;
                $msg['message'] = !empty($insert)?'<strong>'.count($insert).'</strong> Data Terimport':'<strong>0</strong> Data Terimport';
                echo json_encode($msg);die;
            }else if(empty($r['J'])){
                $fail_mains[]  = $fail + 1;
                $fail_upload[] = $dest_code;
                $insert[]      = $success;
                array_pop($insert);
                $fail_awb       = implode(", ",$fail_upload);
                $msg['awbfail'] = !empty($fail_mains)?count($fail_mains)." Tariff Gagal Import <i class='icon-point-right font-size-sm'> </i>". $fail_awb ." Baris".$i."J Tidak boleh kosong":'';
                $msg['status'] = true;
                $msg['message'] = !empty($insert)?'<strong>'.count($insert).'</strong> Data Terimport':'<strong>0</strong> Data Terimport';
                echo json_encode($msg);die;
            }else if(empty($r['K'])){
                $fail_mains[]  = $fail + 1;
                $fail_upload[] = $dest_code;
                $insert[]      = $success;
                array_pop($insert);
                $fail_awb       = implode(", ",$fail_upload);
                $msg['awbfail'] = !empty($fail_mains)?count($fail_mains)." Tariff Gagal Import <i class='icon-point-right font-size-sm'> </i>". $fail_awb ." Baris".$i."K Tidak boleh kosong":'';
                $msg['status'] = true;
                $msg['message'] = !empty($insert)?'<strong>'.count($insert).'</strong> Data Terimport':'<strong>0</strong> Data Terimport';
                echo json_encode($msg);die;
            }else if(empty($r['L'])){
                $fail_mains[]  = $fail + 1;
                $fail_upload[] = $dest_code;
                $insert[]      = $success;
                array_pop($insert);
                $fail_awb       = implode(", ",$fail_upload);
                $msg['awbfail'] = !empty($fail_mains)?count($fail_mains)." Tariff Gagal Import <i class='icon-point-right font-size-sm'> </i>". $fail_awb ." Baris".$i."L Tidak boleh kosong":'';
                $msg['status'] = true;
                $msg['message'] = !empty($insert)?'<strong>'.count($insert).'</strong> Data Terimport':'<strong>0</strong> Data Terimport';
                echo json_encode($msg);die;
            }

            $cekLeadTime        = preg_match("/^[0-9]+$/", $leadtime);
            $cekLatest          = preg_match("/^[0-9]+$/", $latest);
            
            if($cekLeadTime == 0){
                $fail_mains[]  = $fail + 1;
                $fail_upload[] = $dest_code;
                $insert[]      = $success;
                array_pop($insert);
                $fail_awb       = implode(", ",$fail_upload);
                $msg['awbfail'] = !empty($fail_mains)?count($fail_mains)." Tarif Gagal Import <i class='icon-point-right font-size-sm'> </i>". $fail_awb ." Baris <strong class='text-danger'>".$i."K</strong> harus di isi angka":'';
                $msg['status'] = true;
                $msg['message'] = !empty($insert)?'<strong>'.count($insert).'</strong> Data Terimport':'<strong>0</strong> Data Terimport';
                echo json_encode($msg);
                die;
            }
            if($cekLatest == 0){
                $fail_mains[]  = $fail + 1;
                $fail_upload[] = $dest_code;
                $insert[]      = $success;
                array_pop($insert);
                $fail_awb       = implode(", ",$fail_upload);
                $msg['awbfail'] = !empty($fail_mains)?count($fail_mains)." Tarif Gagal Import <i class='icon-point-right font-size-sm'> </i>". $fail_awb ." Baris <strong class='text-danger'>".$i."L</strong> harus di isi angka":'';
                $msg['status'] = true;
                $msg['message'] = !empty($insert)?'<strong>'.count($insert).'</strong> Data Terimport':'<strong>0</strong> Data Terimport';
                echo json_encode($msg);
                die;
            }
            
            if(strtoupper($client_code)=='GENERAL'){
                $client_id      = 0;
                $client_name    = $client_code;
            }else{
                $getClient = $this->mTariff->getClientId($client_code);
                if($getClient == 0){
                    $fail_mains[]  = $fail + 1;
                    $fail_upload[] = $dest_code;
                    $insert[] = $success;
                    array_pop($insert);
                    $fail_awb       = implode(", ",$fail_upload);
                    $msg['awbfail'] = !empty($fail_mains)?count($fail_mains)." Tarif Gagal Import <i class='icon-point-right font-size-sm'> </i>". $fail_awb.' di baris '.$i.'A kode client tidak ditemukan.':'';
                    $msg['status'] = true;
                    $msg['message'] = !empty($insert)?'<strong>'.count($insert).'</strong> Data Terimport':'<strong>0</strong> Data Terimport';
                    echo json_encode($msg);
                    die;
                }
                $client_id      = $getClient['client_id'];
                $client_name    = $getClient['client_name'];
            }
            $getProduct = $this->mTariff->getProductId($service);
            if($getProduct == 0){
                $fail_mains[]  = $fail + 1;
                $fail_upload[] = $dest_code;
                $insert[] = $success;
                array_pop($insert);
                $fail_awb       = implode(", ",$fail_upload);
                $msg['awbfail'] = !empty($fail_mains)?count($fail_mains)." Tarif Gagal Import <i class='icon-point-right font-size-sm'> </i>". $fail_awb.' di baris '.$i.'B kode product tidak ditemukan.':'';
                $msg['status'] = true;
                $msg['message'] = !empty($insert)?'<strong>'.count($insert).'</strong> Data Terimport':'<strong>0</strong> Data Terimport';
                echo json_encode($msg);die;
            }
            $product_idx    = $getProduct['idx'];
            $product_name   = $getProduct['product_name'];
            $product_title  = $getProduct['product_titlex'];
            $productTType   = $getProduct['product_tariff_type'];
            if($productTType !=2){
                $fail_mains[]  = $fail + 1;
                $fail_upload[] = $dest_code;
                $insert[] = $success;
                array_pop($insert);
                $fail_awb       = implode(", ",$fail_upload);
                $msg['awbfail'] = !empty($fail_mains)?count($fail_mains)." Tarif Gagal Import <i class='icon-point-right font-size-sm'> </i>". $fail_awb.' di baris '.$i.'B kode product bukan type Range.':'';
                $msg['status'] = true;
                $msg['message'] = !empty($insert)?'<strong>'.count($insert).'</strong> Data Terimport':'<strong>0</strong> Data Terimport';
                echo json_encode($msg);die;
            }
            $orgId      = $this->mTariff->locationIdx($org_code);
            if($orgId == 0){
                $fail_mains[]  = $fail + 1;
                $fail_upload[] = $org_code;
                $insert[] = $success;
                array_pop($insert);
                $fail_awb       = implode(", ",$fail_upload);
                $msg['awbfail'] = !empty($fail_mains)?count($fail_mains)." Tarif Gagal Import <i class='icon-point-right font-size-sm'> </i>". $fail_awb.' di baris '.$i.'C kode tidak ditemukan.':'';
                $msg['status'] = true;
                $msg['message'] = !empty($insert)?'<strong>'.count($insert).'</strong> Data Terimport':'<strong>0</strong> Data Terimport';
                echo json_encode($msg);die;
            }
            $destId     = $this->mTariff->locationIdx($dest_code);
            if($destId == 0){
                $fail_mains[]  = $fail + 1;
                $fail_upload[] = $dest_code;
                $insert[] = $success;
                array_pop($insert);
                $fail_awb       = implode(", ",$fail_upload);
                $msg['awbfail'] = !empty($fail_mains)?count($fail_mains)." Tarif Gagal Import <i class='icon-point-right font-size-sm'> </i>". $fail_awb.' di baris '.$i.'D kode tidak ditemukan.':'';
                $msg['status'] = true;
                $msg['message'] = !empty($insert)?'<strong>'.count($insert).'</strong> Data Terimport':'<strong>0</strong> Data Terimport';
                echo json_encode($msg);die;
            }
            $org_idx        = $orgId['idx'];
            $org_loc        = $orgId['location_name'];
            $org_city       = $orgId['city_name'];
            $org_district   = $orgId['district_name'];
            $org_province   = $orgId['province_name'];
            $org_country    = 'INDONESIA';
            $org_pcode      = $orgId['postal_code'];
            $dest_idx       = $destId['idx'];
            $dest_loc       = $destId['location_name'];
            $dest_city      = $destId['city_name'];
            $dest_district  = $destId['district_name'];
            $dest_province  = $destId['province_name'];
            $dest_country   = 'INDONESIA';
            $dest_pcode     = $destId['postal_code'];
            $basOnCh    = $this->mTariff->getBasOn($based_on_charge);
            if($basOnCh == 0){
                $fail_mains[]  = $fail + 1;
                $fail_upload[] = $dest_code;
                $insert[] = $success;
                array_pop($insert);
                $fail_awb       = implode(", ",$fail_upload);
                $msg['awbfail'] = !empty($fail_mains)?count($fail_mains)." Tarif Gagal Import <i class='icon-point-right font-size-sm'> </i>". $fail_awb.' di baris '.$i.'F kode base on charge tidak ditemukan.':'';
                $msg['status'] = true;
                $msg['message'] = !empty($insert)?'<strong>'.count($insert).'</strong> Data Terimport':'<strong>0</strong> Data Terimport';
                echo json_encode($msg);die;
            }
            $boc        = $basOnCh['status'];

            if($this->tOffice==2){
                $cek_exsist = array(
                    'office_idx'              => $this->office,
                    'origin_idx'              => $org_idx,
                    'destination_idx'         => $dest_idx,
                    'product_idx'             => $product_idx,
                    'client_id'               => $client_id
                );
            }else{
                $cek_exsist = array(
                    'office_idx'              => 1,
                    'origin_idx'              => $org_idx,
                    'destination_idx'         => $dest_idx,
                    'product_idx'             => $product_idx,
                    'client_id'               => $client_id
                );
            }

            $cek = $this->mTariff->cekTarif($cek_exsist);
            // var_dump($cek);die;
            if($cek == null){
                $range_id       = $this->ModelGenId->genIdUnlimited('RANGEID', $this->idx);
                $insertValue .= "($range_id, $boc, '$based_on_charge', 'KGV', $org_idx, '$org_code', '$org_loc', '$org_city', '$org_district', '$org_province', '$org_country', '$org_pcode', $dest_idx, '$dest_code', '$dest_loc', '$dest_city', '$dest_district', '$dest_province', '$dest_country', '$dest_pcode', $product_idx, '$service', '$product_name', '$product_title', 'IDR', '$description', 1, 'Aktif', $office, '$office_name', $client_id, '$client_name', $leadtime, $latest, $id_login, '$user_name', '$Now'),";
            }else{
                $fail_mains[]  = $fail + 1;
                $fail_upload[] = $dest_code;
                $insert[] = $success;
                array_pop($insert);
                $fail_awb       = implode(", ",$fail_upload);
                $msg['awbfail'] = !empty($fail_mains)?count($fail_mains)." Tarif Gagal Import <i class='icon-point-right font-size-sm'> </i>". $fail_awb.' di baris '.$i." tarif $org_city - $dest_city Customer $client_name sudah ada.":'';
                $msg['status'] = true;
                $msg['message'] = !empty($insert)?'<strong>'.count($insert).'</strong> Data Terimport':'<strong>0</strong> Data Terimport';
                echo json_encode($msg);die;
            }

            $min_qty = explode(',',$min_qty);
            $max_qty = explode(',',$max_qty);
            $tarif_type = explode(',',$tarif_type);
            $tarif = explode(',',$tarif);

            if(count($min_qty) != count($max_qty)){
                $fail_mains[]  = $fail + 1;
                $fail_upload[] = $awb;
                $insert[] = $success;
                array_pop($insert);
                $fail_awb       = implode(", ",$fail_upload);
                $msg['awbfail'] = !empty($fail_mains)?count($fail_mains)." Kiriman Gagal Import <i class='icon-point-right font-size-sm'> </i>". $fail_awb.' di baris '.$i.'H jumlah Panjang tidak sama dengan jumlah Koli.':'';
                $msg['status'] = true;
                $msg['message'] = !empty($success)?'<strong>'.$success.'</strong> Data Terimport':'<strong>0</strong> Data Terimport';
                echo json_encode($msg);die;
            }
            if(count($min_qty) != count($tarif_type)){
                $fail_mains[]  = $fail + 1;
                $fail_upload[] = $awb;
                $insert[] = $success;
                array_pop($insert);
                $fail_awb       = implode(", ",$fail_upload);
                $msg['awbfail'] = !empty($fail_mains)?count($fail_mains)." Kiriman Gagal Import <i class='icon-point-right font-size-sm'> </i>". $fail_awb.' di baris '.$i.'I jumlah Panjang tidak sama dengan jumlah Koli.':'';
                $msg['status'] = true;
                $msg['message'] = !empty($success)?'<strong>'.$success.'</strong> Data Terimport':'<strong>0</strong> Data Terimport';
                echo json_encode($msg);die;
            }
            if(count($min_qty) != count($tarif)){
                $fail_mains[]  = $fail + 1;
                $fail_upload[] = $awb;
                $insert[] = $success;
                array_pop($insert);
                $fail_awb       = implode(", ",$fail_upload);
                $msg['awbfail'] = !empty($fail_mains)?count($fail_mains)." Kiriman Gagal Import <i class='icon-point-right font-size-sm'> </i>". $fail_awb.' di baris '.$i.'J jumlah Panjang tidak sama dengan jumlah Koli.':'';
                $msg['status'] = true;
                $msg['message'] = !empty($success)?'<strong>'.$success.'</strong> Data Terimport':'<strong>0</strong> Data Terimport';
                echo json_encode($msg);die;
            }
            $insert_range_detail = '';
            $total_detail = count($min_qty);
            $iD = 0;
            foreach($min_qty as $index => $c){
                ++$iD;
                if($iD == $total_detail){
                    $status_end = 1;
                }else{
                    $status_end = 0;
                }
                $min_qtyx       = trim($c);
                $max_qtyx       = trim($max_qty[$index]);
                $tarif_typex    = trim($tarif_type[$index]);
                $tarifx         = trim($tarif[$index]);
                $cekMinQty      = preg_match("/^[0-9]+(\.[0-9]{1,5})?$/", $min_qtyx);
                $cekMaxQty      = preg_match("/^[0-9]+(\.[0-9]{1,5})?$/", $max_qtyx);
                $cekTarifType   = preg_match("/^[0-9]+$/", $tarif_typex);
                $cekTarif       = preg_match("/^[0-9]+(\.[0-9]{1,5})?$/", $tarifx);
                if($cekMinQty == 0){
                    $fail_mains[]  = $fail + 1;
                    $fail_upload[] = $awb;
                    $insert[]      = $success;
                    array_pop($insert);
                    $fail_awb       = implode(", ",$fail_upload);
                    $msg['awbfail'] = !empty($fail_mains)?count($fail_mains)." Kiriman Gagal Import <i class='icon-point-right font-size-sm'> </i>". $fail_awb ." Baris ".$i."G harus di isi angka":'';
                    $msg['status'] = true;
                    $msg['message'] = !empty($success)?'<strong>'.$success.'</strong> Data Terimport':'<strong>0</strong> Data Terimport';
                    echo json_encode($msg);die;
                }
                if($cekMaxQty == 0){
                    $fail_mains[]  = $fail + 1;
                    $fail_upload[] = $awb;
                    $insert[]      = $success;
                    array_pop($insert);
                    $fail_awb       = implode(", ",$fail_upload);
                    $msg['awbfail'] = !empty($fail_mains)?count($fail_mains)." Kiriman Gagal Import <i class='icon-point-right font-size-sm'> </i>". $fail_awb ." Baris ".$i."H harus di isi angka":'';
                    $msg['status'] = true;
                    $msg['message'] = !empty($success)?'<strong>'.$success.'</strong> Data Terimport':'<strong>0</strong> Data Terimport';
                    echo json_encode($msg);die;
                }
                if($cekTarifType == 0){
                    $fail_mains[]  = $fail + 1;
                    $fail_upload[] = $awb;
                    $insert[]      = $success;
                    array_pop($insert);
                    $fail_awb       = implode(", ",$fail_upload);
                    $msg['awbfail'] = !empty($fail_mains)?count($fail_mains)." Kiriman Gagal Import <i class='icon-point-right font-size-sm'> </i>". $fail_awb ." Baris ".$i."I harus di isi angka":'';
                    $msg['status'] = true;
                    $msg['message'] = !empty($success)?'<strong>'.$success.'</strong> Data Terimport':'<strong>0</strong> Data Terimport';
                    echo json_encode($msg);die;
                }
                if($cekTarif == 0){
                    $fail_mains[]  = $fail + 1;
                    $fail_upload[] = $awb;
                    $insert[]      = $success;
                    array_pop($insert);
                    $fail_awb       = implode(", ",$fail_upload);
                    $msg['awbfail'] = !empty($fail_mains)?count($fail_mains)." Kiriman Gagal Import <i class='icon-point-right font-size-sm'> </i>". $fail_awb ." Baris ".$i."J harus di isi angka":'';
                    $msg['status'] = true;
                    $msg['message'] = !empty($success)?'<strong>'.$success.'</strong> Data Terimport':'<strong>0</strong> Data Terimport';
                    echo json_encode($msg);die;
                }

                $insertValueD .= "($range_id, $min_qtyx, $max_qtyx, $tarif_typex, $tarifx, $status_end, 1, $office, $id_login, '$Now'),";
            }
            $i++;
        }
        $insertValue    = substr($insertValue,0,strlen($insertValue)-1);
        $insertValueD   = substr($insertValueD,0,strlen($insertValueD)-1);
        $insertTarif = "INSERT INTO master_tariff_range (range_id, based_on_charge, based_on_charge_view, uom, origin_idx, origin_code, origin_location, origin_city, origin_district, origin_province, origin_country, origin_postal_code, destination_idx, destination_code, destination_location, destination_city, destination_district, destination_province, destination_country, destination_postal_code, product_idx, product_code, product_name, product_title, tariff_valuta, `description`, `status`, status_view, office_idx, office_name, client_id, client_name, lead_time, latest, created_by, created_by_view, created_on) values $insertValue";
        $insertDetail = "INSERT INTO master_tariff_range_detail (range_id, min_qty, max_qty, type_tariff_value, tariff_value, status_end, `status`, office_idx, created_by, created_on) values $insertValueD";
        // var_dump($insertValue);die;
        // var_dump($updateValue);die;
        $result         = $this->mTariff->importMTariff($insertTarif, $insertDetail);
        if($result == 1){
            $msg['success'] = true;
            $msg['message'] = 'Data Sukses Terimport.';
            $msg['link']    = $this->link;
            echo json_encode($msg);die;
        }else{
            $msg['status']  = true;
            $msg['message'] = 'Gagal import silahkan cek data filenya.';
            echo json_encode($msg);die;
        }
    }
    public function editTariff()
    {
        $id     = $this->secure->dec($this->input->get('id'));
        $result = $this->mTariff->editTariff($id);
        echo json_encode($result);
    }
    public function getDest()
    {
        $dest_idx   = $this->secure->dec($this->input->get('dest_id'));
        $result     = $this->mTariff->getDest($dest_idx);
        echo json_encode($result);
    }
    public function updateTariff($idx)
    {
        $id = $this->secure->dec($idx);
        $this->form_validation->set_rules('description', 'Description', 'trim|required');
        $this->form_validation->set_rules('origin', 'Origin', 'trim|required');
        $this->form_validation->set_rules('dest', 'Destination', 'trim|required');
        $this->form_validation->set_rules('min_charge', 'Minimum Charge', 'trim|required');
        $this->form_validation->set_rules('tariff_valuta', 'Tariff Valuta', 'trim|required');
        $this->form_validation->set_rules('tariff_type', 'Tariff Type 1st', 'trim|required');
        $this->form_validation->set_rules('tariff_value', 'Tariff Value 1st', 'trim|required');
        $this->form_validation->set_rules('product_idx', 'Product', 'trim|required');
        $this->form_validation->set_rules('client_id', 'Client', 'trim|required');
        $this->form_validation->set_rules('based_on_charge', 'Based On Charge', 'trim|required');
        if ($this->form_validation->run() == false) {
            $msg['error']   = true;
            $msg['message'] = validation_errors();
            echo json_encode($msg);
            die;
        }
        cek_csrf();
        $based_on_charge = $this->input->post('based_on_charge', true);
        if($based_on_charge == 1){
            $uom = "KG";
        }elseif($based_on_charge == 2){
            $uom = "VOL";
        }elseif($based_on_charge == 3){
            $uom = "KGV";
        }elseif($based_on_charge == 4){
            $uom = "SHP";
        }else{
            $uom = "OTH";
        }
        $client_name = trim($this->input->post('client_name', TRUE));
        $product_name = trim($this->input->post('product_name', TRUE));
        $product_code = trim($this->input->post('product_code', TRUE));
        $product_title = trim($this->input->post('product_title', TRUE));
        $origin_location = trim($this->input->post('origin_location', TRUE));
        $origin_city = trim($this->input->post('origin_city', TRUE));
        $origin_district = trim($this->input->post('origin_district', TRUE));
        $origin_province = trim($this->input->post('origin_province', TRUE));
        $origin_country = trim($this->input->post('origin_country', TRUE));
        $origin_postal_code = trim($this->input->post('origin_postal_code', TRUE));
        $origin_code = trim($this->input->post('origin_code', TRUE));
        $destination_location = trim($this->input->post('destination_location', TRUE));
        $destination_city = trim($this->input->post('destination_city', TRUE));
        $destination_district = trim($this->input->post('destination_district', TRUE));
        $destination_province = trim($this->input->post('destination_province', TRUE));
        $destination_country = trim($this->input->post('destination_country', TRUE));
        $destination_postal_code = trim($this->input->post('destination_postal_code', TRUE));
        $destination_code = trim($this->input->post('destination_code', TRUE));
        $based_on_charge_view = trim($this->input->post('based_on_charge_view', TRUE));
        $status = trim($this->input->post('tStatus'));
        $data = [
            'office_idx'                => $this->office,
            'office_name'               => $this->office_name,
            'origin_location'           => $origin_location,
            'origin_city'               => $origin_city,
            'origin_district'           => $origin_district,
            'origin_province'           => $origin_province,
            'origin_country'            => $origin_country,
            'origin_postal_code'        => $origin_postal_code,
            'origin_code'               => $origin_code,
            'destination_location'      => $destination_location,
            'destination_city'          => $destination_city,
            'destination_district'      => $destination_district,
            'destination_province'      => $destination_province,
            'destination_country'       => $destination_country,
            'destination_postal_code'   => $destination_postal_code,
            'destination_code'          => $destination_code,
            'client_name'               => $client_name,
            'product_code'              => $product_code,
            'product_name'              => $product_name,
            'product_title'             => $product_title,
            'based_on_charge_view'      => $based_on_charge_view,
            'description'               => trim($this->input->post('description', TRUE)),
            'origin_idx'                => trim($this->secure->dec($this->input->post('origin', TRUE))),
            'destination_idx'           => trim($this->secure->dec($this->input->post('dest', TRUE))),
            'minimum_qty'               => trim($this->input->post('min_charge', TRUE)),
            'tariff_valuta'             => trim($this->input->post('tariff_valuta')),
            'type_tariff_value'         => trim($this->input->post('tariff_type')),
            'type_tariff_value_next'    => trim($this->input->post('tariff_type_2', TRUE)),
            'tariff_value'              => trim($this->input->post('tariff_value')),
            'tariff_value_next'         => trim($this->input->post('tariff_value_2', TRUE)),
            'status'                    => $status,
            'status_view'               => $status==1?'Aktif':'Nonaktif',
            'created_by'                => $this->idx,
            'created_by_view'           => $this->user_name,
            'created_on'                => date("Y-m-d H:i:s"),
            'product_idx'               => trim($this->secure->dec($this->input->post('product_idx', true))),
            'client_id'                 => trim($this->secure->dec($this->input->post('client_id', true))),
            'lead_time'                 => trim($this->input->post('lead_time', TRUE)),
            'latest'                    => trim($this->input->post('latest', TRUE)),
            'based_on_charge'           => trim($based_on_charge),
            'uom'                       => $uom
        ];
        $result = $this->mTariff->updateMTariff($data, $id);
        if($result == 1)
        {
            $msg['success'] = true;
            $msg['type']    = 'update';
            echo json_encode($msg);
            die;
        }
        else
        {
            $msg['status']  = true;
            $msg['message']  = $result;
            echo json_encode($msg);
            die;
        }
    }
    public function checkResponse()
    {
        $cek = $this->mTariff->getMProduct($_POST['resname']);
        if($cek > 0) {
            echo '0';
        } else {
            echo '1';
        }
    }
    public function deleteTariff()
    {
        $id     = $this->secure->dec($this->input->get('id'));
        cek_get_csrf();
        $result = $this->mTariff->deleteTariff($id);
        if ($result == true) {
            $msg['success'] = true;
            echo json_encode($msg);
            die;
        }else{
            $msg['success']   = false;
            $msg['message']   = $result;
            echo json_encode($msg);
            die;
        }
    }
    public function changeStatus()
    {
        $id_tariff = $this->secure->dec($this->input->post('tId'));
        $id_status = $this->input->post('tClCode');
        $csrf = validate_csrf_token();
        if($csrf==false){
            $msg['status']  = true;
            $msg['msg']     = "session invalid.";
            echo json_encode($msg);die;
        }
        if($id_status == '1'){
            $changeId = 0;
            $changeText = 'Nonaktif';
        } else {
            $changeId = 1;
            $changeText = 'Aktif';
        }
        $data = [
            'status'      => $changeId,
            'modified_by' => $this->idx,
            'modified_on' => date("Y-m-d H:i:s")
        ];
        $cek = $this->mOt->changeStatusOtHead($data, $id_tariff);
        if($cek == true)
        {
            $msg['success'] = true;
            $msg['msg']     = 'Ubah status berhasil.';
            echo json_encode($msg);die;
        }else{
            $msg['status']  = true;
            $msg['msg']     = 'Ubah status gagal.';
            echo json_encode($msg);die;
        }
    }
    public function getTariffJTE()
    {
        ini_set('memory_limit', '2048M');
        $tarifJTE = $this->mTariff->tarifJTE();
        $i = 1;
        $xxx = '';
        $output = '';
        $lagi = array();
        foreach($tarifJTE as $x)
        {
            // $data = array(
            //     'no'        => $i,
            //     'minim'     => $x->minimum,
            //     'tarif'     => $x->price,
            //     'leadtime'  => $x->leadtime,
            //     'latest'    => $x->latest,
            //     'city'      => $x->city
            // );
            // $lagi[] = $data;
            // $i++;
            $cekIdx = $this->mTariff->cekIdx($x->city);
            foreach($cekIdx as $y)
            {
                $data = array(
                    'idx'                   => $i,
                    'based_on_charge'       => 3,
                    'uom'                   => 'KGV',
                    'minimum_qty'           => $x->minimum,
                    'origin_idx'            => 59882,
                    'destination_idx'       => $y->idx,
                    'order_type'            => 0,
                    'product_idx'           => 1,
                    'sub_product_idx'       => 0,
                    'vehicle_type'          => 0,
                    'description'           => 'Tariff UMUM Jakarta - '.$x->city,
                    'remarks'               => 'Tariff UMUM Jakarta - '.$x->city,
                    'tariff_valuta'         => 'IDR',
                    'type_tariff_value'     => 1,
                    'tariff_value'          => $x->price,
                    'type_tariff_value_next'=> 0,
                    'tariff_value_next'     => 0,
                    'status'                => 1,
                    'office_idx'            => 1,
                    'client_id'             => 0,
                    'lead_time'             => $x->leadtime,
                    'latest'                => $x->latest,
                    'created_on'            => date('Y-m-d H:i:s'),
                    'created_by'            => 0,
                    'status_delete'         => 'N'
                );
                // $lagi[] = $data;
                // var_dump('<pre>');
                // var_dump($data);
                // die;
                $insert =  $this->mTariff->insertTariffxx($data);
                if($insert == true){
                    $xxx = 'success';
                }else{
                    $xxx = 'error';
                    die;
                }
                $i++;
                $output .= $i.'. '.$xxx .'<br>';
            }
        }
        // echo $output;
        var_dump('<pre>');
        var_dump($output);
        die;
    }

    public function getTariffJTEx()
    {
        ini_set('memory_limit', '2048M');
        $tarifJTE = $this->mTariff->tarifJTEx('Tanjung Balai Kisaran');
        $i = 1;
        $xxx = '';
        $output = '';
        $lagi = [];
        foreach($tarifJTE as $x)
        {
            // $datax = array(
            //     'no'        => $i,
            //     'minim'     => $x->minimum,
            //     'tarif'     => $x->price,
            //     'leadtime'  => $x->leadtime,
            //     'latest'    => $x->latest,
            //     'city'      => $x->city
            // );
            // $lagi[] = $datax;
            // $i++;
            $cekIdx = $this->mTariff->cekIdxx('Tanjung Balai','');
            foreach($cekIdx as $y)
            {
                $data = array(
                    'based_on_charge'       => 3,
                    'uom'                   => 'KGV',
                    'minimum_qty'           => $x->minimum,
                    'origin_idx'            => 59882,
                    'destination_idx'       => $y->idx,
                    'order_type'            => 0,
                    'product_idx'           => 1,
                    'sub_product_idx'       => 0,
                    'vehicle_type'          => 0,
                    'description'           => 'Tariff UMUM Jakarta - '.$y->location_name,
                    'remarks'               => 'Tariff UMUM Jakarta - '.$y->location_name,
                    'tariff_valuta'         => 'IDR',
                    'type_tariff_value'     => 1,
                    'tariff_value'          => $x->price,
                    'type_tariff_value_next'=> 0,
                    'tariff_value_next'     => 0,
                    'status'                => 1,
                    'office_idx'            => 1,
                    'client_id'             => 0,
                    'lead_time'             => $x->leadtime,
                    'latest'                => $x->latest,
                    'created_on'            => date('Y-m-d H:i:s'),
                    'created_by'            => 0,
                    'status_delete'         => 'N'
                );
                // $lagi[] = $data;
                $insert =  $this->mTariff->insertTariffxx($data);
                if($insert == true){
                    $xxx = $y->location_name;
                }else{
                    $xxx = 'error';
                    die;
                }
                $i++;
                $output .= $i.'. '.$xxx .'<br>';
            }
            // var_dump('<pre>');
            // var_dump($lagi);
            // die;
        }
        // echo $output;
        var_dump('<pre>');
        // var_dump($lagi);
        var_dump($output);
        die;
    }

    public function excel_tariff($client)
    {
        $client_id      = $this->secure->dec($client);
        ini_set ('max_execution_time', '0'); 
        ini_set ('memory_limit', '256M');    
        $data           = $this->mTariff->getTarifDom($client_id);
		$spreadsheet    = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle("Master Tariff Domestic");
		$sheet->getRowDimension('1')->setRowHeight(40, 'px');
        $sheet->getRowDimension('2')->setRowHeight(30, 'px');
        $sheet->getRowDimension('3')->setRowHeight(40, 'px');
        $sheet->getRowDimension('4')->setRowHeight(40, 'px');
        $sheet->getRowDimension('5')->setRowHeight(40, 'px');
        $sheet->getRowDimension('6')->setRowHeight(40, 'px');
        $sheet->getRowDimension('7')->setRowHeight(21, 'px');
        $sheet->getRowDimension('8')->setRowHeight(30, 'px');
        $sheet->setCellValue('B2', 'Master Tariff Domestic');
		$sheet->getStyle('B2:M2')->getBorders()->getOutline()->setBorderStyle(Border::BORDER_MEDIUM)->setColor(new Color('00000000'));
		$styleArrayB2 = [
            'font' => [
                'bold' => true,
				'size' => 11,
				'color' => [
					'argb' => 'FFFFFFFF'
				]
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'rotation' => 90,
                'startColor' => [
                    'argb' => 'FF404040',
                ]
			]
        ];
        $sheet->getStyle('B2:M2')->applyFromArray($styleArrayB2);
        $sheet->mergeCells('B2:M2');
		$styleArrayB4 = [
            'font' => [
                'bold' => true,
				'color' => [
					'argb' => 'FFFFFFFF'
				]
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'rotation' => 90,
                'startColor' => [
                    'argb' => 'FF404040',
                ]
            ]
        ];
        $sheet->getStyle('B4:M4')->applyFromArray($styleArrayB4);
		$sheet->getColumnDimension('A')->setWidth(64, 'px');
		$sheet->getColumnDimension('B')->setWidth(50, 'px');
		$sheet->getColumnDimension('C')->setWidth(60, 'px');
		$sheet->getColumnDimension('D')->setWidth(280, 'px');
		$sheet->getColumnDimension('E')->setWidth(210, 'px');
		$sheet->getColumnDimension('F')->setWidth(190, 'px');
		$sheet->getColumnDimension('G')->setWidth(120, 'px');
		$sheet->getColumnDimension('H')->setWidth(120, 'px');
		$sheet->getColumnDimension('I')->setWidth(120, 'px');
		$sheet->getColumnDimension('J')->setWidth(70, 'px');
		$sheet->getColumnDimension('K')->setWidth(170, 'px');
		$sheet->getColumnDimension('L')->setWidth(170, 'px');
		$sheet->getColumnDimension('M')->setWidth(90, 'px');
		$sheet->setCellValue('B4', 'NO');
		$sheet->getStyle('B4')->getBorders()->getOutline()->setBorderStyle(Border::BORDER_MEDIUM)->setColor(new Color('00000000'));
		$sheet->setCellValue('C4', 'STATUS');
        $sheet->getStyle('C4')->getBorders()->getOutline()->setBorderStyle(Border::BORDER_MEDIUM)->setColor(new Color('00000000'));
		$sheet->setCellValue('D4', 'CUSTOMER');
        $sheet->getStyle('D4')->getBorders()->getOutline()->setBorderStyle(Border::BORDER_MEDIUM)->setColor(new Color('00000000'));
		$sheet->setCellValue('E4', 'ASAL');
        $sheet->getStyle('E4')->getBorders()->getOutline()->setBorderStyle(Border::BORDER_MEDIUM)->setColor(new Color('00000000'));
		$sheet->setCellValue('F4', 'TUJUAN');
        $sheet->getStyle('F4')->getBorders()->getOutline()->setBorderStyle(Border::BORDER_MEDIUM)->setColor(new Color('00000000'));
		$sheet->setCellValue('G4', 'SERVICE');
		$sheet->getStyle('G4')->getBorders()->getOutline()->setBorderStyle(Border::BORDER_MEDIUM)->setColor(new Color('00000000'));
		$sheet->setCellValue('H4', 'DESKRIPSI');
        $sheet->getStyle('H4')->getBorders()->getOutline()->setBorderStyle(Border::BORDER_MEDIUM)->setColor(new Color('00000000'));
		$sheet->setCellValue('I4', 'DASAR TARIF');
        $sheet->getStyle('I4')->getBorders()->getOutline()->setBorderStyle(Border::BORDER_MEDIUM)->setColor(new Color('00000000'));
		$sheet->setCellValue('J4', 'MINIM');
        $sheet->getStyle('J4')->getBorders()->getOutline()->setBorderStyle(Border::BORDER_MEDIUM)->setColor(new Color('00000000'));
		$sheet->setCellValue('K4', 'TARIF PERTAMA');
        $sheet->getStyle('K4')->getBorders()->getOutline()->setBorderStyle(Border::BORDER_MEDIUM)->setColor(new Color('00000000'));
		$sheet->setCellValue('L4', 'TARIF SELANJUTNYA');
        $sheet->getStyle('L4')->getBorders()->getOutline()->setBorderStyle(Border::BORDER_MEDIUM)->setColor(new Color('00000000'));
		$sheet->setCellValue('M4', 'ESTIMASI');
        $sheet->getStyle('M4')->getBorders()->getOutline()->setBorderStyle(Border::BORDER_MEDIUM)->setColor(new Color('00000000'));
		$qtTot 	= 0;
		$wTot 	= 0;
		$no 	= 1;
		$max	= COUNT($data);
		$row	= $no + 4;
		foreach ($data as $d) {
			$sheet->getRowDimension($row)->setRowHeight(40, 'px');
			$sheet->getStyle('B'.$row.':M'.$row)->getAlignment()->setVertical('center');
			$sheet->getStyle('B'.$row)->getBorders()->getOutline()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('00000000'));
			$sheet->getStyle('C'.$row)->getBorders()->getOutline()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('00000000'));
			$sheet->getStyle('D'.$row)->getBorders()->getOutline()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('00000000'));
			$sheet->getStyle('E'.$row)->getBorders()->getOutline()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('00000000'));
			$sheet->getStyle('F'.$row)->getBorders()->getOutline()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('00000000'));
			$sheet->getStyle('G'.$row)->getBorders()->getOutline()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('00000000'));
			$sheet->getStyle('H'.$row)->getBorders()->getOutline()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('00000000'));
			$sheet->getStyle('I'.$row)->getBorders()->getOutline()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('00000000'));
			$sheet->getStyle('J'.$row)->getBorders()->getOutline()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('00000000'));
			$sheet->getStyle('K'.$row)->getBorders()->getOutline()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('00000000'));
			$sheet->getStyle('L'.$row)->getBorders()->getOutline()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('00000000'));
			$sheet->getStyle('M'.$row)->getBorders()->getOutline()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('00000000'));
			$sheet->setCellValue('B'.$row, $no);
			$sheet->getStyle('B'.$row)->getAlignment()->setHorizontal('center');
			$sheet->setCellValue('C'.$row, $d->status_view);
			$sheet->getStyle('C'.$row)->getAlignment()->setHorizontal('center');
			$sheet->setCellValue('D'.$row, $d->client_name);
			$sheet->getStyle('D'.$row)->getAlignment()->setHorizontal('left');
			$sheet->setCellValue('E'.$row, "$d->origin_location - $d->origin_district - $d->origin_city || $d->origin_code - $d->origin_postal_code");
            $sheet->getStyle('E'.$row)->getAlignment()->setWrapText(true);
			$sheet->getStyle('E'.$row)->getAlignment()->setHorizontal('left');
			$sheet->setCellValue('F'.$row, "$d->destination_location - $d->destination_district - $d->destination_city || $d->destination_code - $d->destination_postal_code");
            $sheet->getStyle('F'.$row)->getAlignment()->setWrapText(true);
			$sheet->getStyle('F'.$row)->getAlignment()->setHorizontal('left');
			$sheet->setCellValue('G'.$row, "$d->product_code - $d->product_name");
			$sheet->getStyle('G'.$row)->getAlignment()->setHorizontal('left');
			$sheet->setCellValue('H'.$row, $d->description);
			$sheet->getStyle('H'.$row)->getAlignment()->setHorizontal('left');
			$sheet->setCellValue('I'.$row, "$d->based_on_charge_view");
			$sheet->getStyle('I'.$row)->getAlignment()->setHorizontal('left');
			$sheet->setCellValue('J'.$row, $d->minimum_qty);
			$sheet->getStyle('J'.$row)->getAlignment()->setHorizontal('center');
			$sheet->setCellValue('K'.$row, $d->tariff_value);
			$sheet->getStyle('K'.$row)->getNumberFormat()->setFormatCode('_("Rp"* #,##0.00_);_("Rp"* \(#,##0.00\);_("Rp"* "-"??_);_(@_)');
			$sheet->getStyle('K'.$row)->getAlignment()->setHorizontal('left');
			$sheet->setCellValue('L'.$row, $d->tariff_value_next);
			$sheet->getStyle('L'.$row)->getNumberFormat()->setFormatCode('_("Rp"* #,##0.00_);_("Rp"* \(#,##0.00\);_("Rp"* "-"??_);_(@_)');
			$sheet->getStyle('L'.$row)->getAlignment()->setHorizontal('left');
			$sheet->setCellValue('M'.$row, "$d->lead_time - $d->latest Day");
			$sheet->getStyle('M'.$row)->getAlignment()->setHorizontal('center');
			$no++;
			$row++;
		}
		header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="master_tariff_domestic.xlsx"');
		$writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }

    public function template_excel()
    {
		$spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle("format import");
		$sheet->getColumnDimension('A')->setWidth(110, 'px');
		$sheet->getColumnDimension('B')->setWidth(110, 'px');
		$sheet->getColumnDimension('C')->setWidth(110, 'px');
		$sheet->getColumnDimension('D')->setWidth(110, 'px');
		$sheet->getColumnDimension('E')->setWidth(110, 'px');
		$sheet->getColumnDimension('F')->setWidth(110, 'px');
		$sheet->getColumnDimension('G')->setWidth(110, 'px');
		$sheet->getColumnDimension('H')->setWidth(110, 'px');
		$sheet->getColumnDimension('I')->setWidth(110, 'px');
		$sheet->getColumnDimension('J')->setWidth(110, 'px');
		$sheet->getColumnDimension('K')->setWidth(110, 'px');
		$sheet->getColumnDimension('L')->setWidth(110, 'px');
        $sheet->getRowDimension('1')->setRowHeight(40);
        $sheet->getRowDimension('2')->setRowHeight(69);
        $sheet->getStyle('A1:L1')->getAlignment()->setWrapText(true);
        $sheet->getStyle('A1:L1')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A1:L1')->getAlignment()->setVertical('center');
        $sheet->getStyle('A2:L2')->getAlignment()->setWrapText(true);
        $sheet->getStyle('A2:L2')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A2:L2')->getAlignment()->setVertical('center');
        // Header
        $sheet->setCellValue('A1', 'Kode Customer');
        $sheet->setCellValue('B1', 'Kode Service');
        $sheet->setCellValue('C1', 'Kode Asal');
        $sheet->setCellValue('D1', 'Kode Tujuan');
        $sheet->setCellValue('E1', 'Deskripsi');
        $sheet->setCellValue('F1', 'Base On');
        $sheet->getStyle('G1')->getBorders()->getLeft()->setBorderStyle(Border::BORDER_THICK)->setColor(new Color('FF0000'));
        $sheet->getStyle('G1')->getBorders()->getTop()->setBorderStyle(Border::BORDER_THICK)->setColor(new Color('FF0000'));
        $sheet->getStyle('G1')->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THICK)->setColor(new Color('FF0000'));
        $sheet->setCellValue('G1', 'Min Qty');
        $sheet->getStyle('H1')->getBorders()->getTop()->setBorderStyle(Border::BORDER_THICK)->setColor(new Color('FF0000'));
        $sheet->getStyle('H1')->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THICK)->setColor(new Color('FF0000'));
        $sheet->setCellValue('H1', 'Max Qty');
        $sheet->getStyle('I1')->getBorders()->getTop()->setBorderStyle(Border::BORDER_THICK)->setColor(new Color('FF0000'));
        $sheet->getStyle('I1')->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THICK)->setColor(new Color('FF0000'));
        $sheet->setCellValue('I1', 'Tipe Tarif');
        $sheet->getStyle('J1')->getBorders()->getRight()->setBorderStyle(Border::BORDER_THICK)->setColor(new Color('FF0000'));
        $sheet->getStyle('J1')->getBorders()->getTop()->setBorderStyle(Border::BORDER_THICK)->setColor(new Color('FF0000'));
        $sheet->getStyle('J1')->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THICK)->setColor(new Color('FF0000'));
        $sheet->setCellValue('J1', 'Tarif PerQty');
        $sheet->setCellValue('K1', 'Paling Cepat');
        $sheet->setCellValue('L1', 'Paling Lambat');
        // Header
        // Detail 
        $sheet->getStyle('A2:L2')->getAlignment()->setWrapText(true); 
        $sheet->setCellValue('A2', 'CLT220000001');
        $sheet->setCellValue('B2', 'DOMREGDRT');
        $sheet->setCellValue('C2', 'CGK00001');
        $sheet->setCellValue('D2', 'TKG01490');
        $sheet->setCellValue('E2', 'Tarif Tujuan Lampung');
        $sheet->setCellValue('F2', 'WEIGHT / DIMENSION');
        $sheet->getCell('G2')->setValueExplicit(
            '10,1000',
            \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
        );
        $sheet->getCell('H2')->setValueExplicit(
            '1000,2000',
            \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
        );
        $sheet->getCell('I2')->setValueExplicit(
            '1,1',
            \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
        );
        $sheet->getCell('J2')->setValueExplicit(
            '1600,1400',
            \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
        );
        $sheet->setCellValue('K2', '12');
        $sheet->setCellValue('L2', '13');

		header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="format_import_tariff_range.xlsx"');
		$writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }
}
/* End of file Tariff.php */
/* Location: ./application/controllers/Tariff.php */
