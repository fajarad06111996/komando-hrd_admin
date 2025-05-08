<?php
defined('BASEPATH') or exit('No direct script access allowed');
require "vendor/autoload.php";
use Nullix\CryptoJsAes\CryptoJsAes;
class Office extends AUTH_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('master/M_office', 'mOff');
        $this->load->model('ModelGenId');
        $this->load->library('security_function');
        $this->link	    = site_url('master/').strtolower(get_class($this));
        $this->filename	= strtolower(get_class($this));
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
            $data['write']  = '<a href="'.$this->link.'/formAdd" id="btnAdd" class="pull-right text-white small" title="Add New Office" data-placement="right" data-popup="tooltip"><i class="icon-plus-circle2"></i></a>';
            $data['lock']   = 1;
        }else{
            $data['write']  = '<i class="icon-lock pull-right pt-1 " title="Locked New Office" data-placement="right" data-popup="tooltip"></i>';
            $data['lock']   = 0;
        }
        $data['filename']   = $this->filename;
        $data["link"]       = $this->link;
        $params             = '{"link": "'.$this->link.'", "csrf": "'.$this->session->csrf_token.'", "permW": "'.$this->security_function->permissions($this->filename . "-w").'", "permC": "'.$this->security_function->permissions($this->filename . "-c").'", "permX": "'.$this->security_function->permissions($this->filename . "-x").'", "base_url":"'.base_url().'", "lock": "'.$data['lock'].'"}';
        $encrypted          = CryptoJsAes::encrypt($params, $this->enkey);
        $data['params']     = $encrypted;
        $this->template->views('master/v_office', $data);
    }
    // CONTOH LAIN CONTROLLER2 POST DATATABLE SERVERSIDE
    function get_ajax() {
        $csrfToken  = validate_csrf_token();
        if($csrfToken == false){
            $list = array();
        }else{
            $list = $this->mOff->get_datatables();
        }
        // $list = $this->mOff->get_datatables();
        $data = array();
        $no = @$_POST['start'];
        foreach ($list as $item) {
            $idx = $this->secure->enc($item->idx);
            $info = "<h5><a href='javascript:void(0);' id='$idx' data='$item->office_name' code='1' class='bInfo text-center badge badge-primary' data-popup='tooltip' title='Hub Office' data-placement='right'><i class='icon-price-tag2'></i></a></h5>";
            if(!empty($this->security_function->permissions($this->filename . "-c"))){
                if($item->office_type==2){
                    $account = '';
                    if($item->account_number == NULL){
                        $puAccount = "<h5><a href='javascript:void(0);' id='$idx' data='$item->office_name' code='$item->city' class='bPuAccount text-center badge badge-warning' data-popup='tooltip' title='Create PU Account' data-placement='right'><i class='icon-database-add'></i></a></h5>";
                    }else{
                        $puAccount = '<h5><span class="badge badge-success bCreated" data-popup="tooltip" title="PU Account Created" data-placement="right"><i class="icon-database-check"></i></span></h5>';
                    }
                }else{
                    $puAccount = "";
                    if($item->account_number == NULL){
                        $account = "<h5><a href='javascript:void(0);' id='$idx' data='$item->office_name' code='$item->city' class='bAccount text-center badge badge-warning' data-popup='tooltip' title='Create Account' data-placement='right'><i class='icon-database-add'></i></a></h5>";
                    }else{
                        $account = '<h5><span class="badge badge-success bCreated" data-popup="tooltip" title="Account Created" data-placement="right"><i class="icon-database-check"></i></span></h5>';
                    }
                }
                $change = "<h5><a href='javascript:void(0);' id='$idx' class='bEdit text-center badge badge-info' data-popup='tooltip' title='Edit Office' data-placement='right'><i class='icon-pencil7'></i></a></h5>";
                if($item->status == 1)
                {
                    $statusU = "<h5><a href='javascript:void(0);' id='$idx' data='$item->office_name' code='1' class='bStatus text-center badge badge-success' data-popup='tooltip' title='Change Status' data-placement='left'>Aktif</a></h5>";
                // $statusU = '<span class="badge badge-success">Aktif</span>';
                } else {
                    $statusU = "<h5><a href='javascript:void(0);' id='$idx' data='$item->office_name' code='0' class='bStatus text-center badge badge-danger' data-popup='tooltip' title='Change Status' data-placement='left'>Non Aktif</a></h5>";
                // $statusU = '<span class="badge badge-danger">Non Aktif</span>';
                }
            }else{
                if($item->office_type==2){
                    $account = '';
                }else{
                    $account = '<h5><span class="badge badge-warning bLock" data-popup="tooltip" title="Account Locked" data-placement="right"><i class="icon-lock"></i></span></h5>';
                }
                $change = '<h5><span class="badge badge-warning"><i class="icon-lock" data-popup="tooltip" title="Locked Edit Office" data-placement="right"></i></span></h5>';
                if($item->status == 1)
                {
                    $statusU = "<span class='text-center badge badge-success'>Aktif</span>";
                }else{
                    $statusU = "<span class='text-center badge badge-danger'>Non Aktif</span>";
                }
            }
            if(!empty($this->security_function->permissions($this->filename . "-x"))){
                $execute  = "<h5><a href='javascript:void(0);' id='$idx' data='$item->office_name' class='bDelete text-center badge badge-danger' data-popup='tooltip' title='Delete Office' data-placement='right'><i class='icon-bin'></i></a></h5>";
            }else{
                $execute  = '<h5><span class="badge badge-warning"><i class="mi-lock font-weight-black" data-popup="tooltip" title="Locked Delete Office" data-placement="right"></i></span></h5>';
            }
            $no++;
            $row = array();
            $row[] = $no.".";
            $row[] = $item->office_code;
            $row[] = $item->office_name;
            $row[] = $item->address;
            $row[] = $item->email_id;
            $row[] = $statusU;
            // add html for action
            $row[] = "<div class='btn-group'>$puAccount$change$execute$info</div>";
            $data[] = $row;
        }
        $output = array(
            "draw"            => @$_POST['draw'],
            "recordsTotal"    => $this->mOff->count_all(),
            "recordsFiltered" => $this->mOff->count_filtered(),
            "data"            => $data,
        );
        // output to json format
        echo json_encode($output);
    }
    public function showatuh()
    {
        $hasil = $this->mClient->get_datatables();
        var_dump('<pre>');
        var_dump($hasil);
    }
    public function formData()
    {
        $office_enid    = $this->input->post('id_edit');
        // $id = $this->secure->dec($idx);
        if(empty($office_enid)){
            $id_enedit = $this->session->tempdata('id_edit');
            if(empty($id_enedit)){
                redirect($this->link);die;
                $center_point = "";
                $office_name = "";
            }else{
                $id_edit            = $this->secure->dec($id_enedit);
                $data['title']      = "Edit Data Office";
                $data['off']	    = $this->mAccess->getTable('master_office',array('idx' => $id_edit))->row_array();
                $data['formact']    = $this->link.'/formActCust/'.$id_enedit;
                $center_point       = $data['off']['center_point'];
                $office_name        = $data['off']['office_name'];
            }
        }else{
            $office_id          = $this->secure->dec($office_enid);
            $this->session->set_tempdata('id_edit',$office_enid, 100);
            $data['title']      = "Edit Data Office";
            $data['off']	    = $this->mAccess->getTable('master_office',array('idx' => $office_id))->row_array();
            $data['formact']    = $this->link.'/formActCust/'.$office_enid;
            $center_point       = $data['off']['center_point'];
            $office_name        = $data['off']['office_name'];
        }
        $data['company']        = $this->mOff->getCompany();
        $data['link']           = $this->link;
        $data['page']           = "news";
        $data['judul']          = "Posting";
        $data['deskripsi']      = "Data News";
        $params                 = '{"link": "'.$this->link.'", "csrf": "'.$this->session->csrf_token.'", "permW": "'.$this->security_function->permissions($this->filename . "-w").'", "permC": "'.$this->security_function->permissions($this->filename . "-c").'", "permX": "'.$this->security_function->permissions($this->filename . "-x").'", "base_url":"'.base_url().'", "center_point": "'.$center_point.'", "office_name": "'.$office_name.'"}';
        $encrypted              = CryptoJsAes::encrypt($params, $this->enkey);
        $data['params']         = $encrypted;
        $this->template->views('master/v_formoffice', $data);
    }

    public function formAdd()
    {
        $data['formact']        = $this->link.'/formActCust';
        $data['title']          = "Add New Data Office";
        $data['company']        = $this->mOff->getCompany();
        $data['link']           = $this->link;
        $data['page']           = "news";
        $data['judul']          = "Posting";
        $data['deskripsi']      = "Data News";
        $params                 = '{"link": "'.$this->link.'", "csrf": "'.$this->session->csrf_token.'", "permW": "'.$this->security_function->permissions($this->filename . "-w").'", "permC": "'.$this->security_function->permissions($this->filename . "-c").'", "permX": "'.$this->security_function->permissions($this->filename . "-x").'", "base_url":"'.base_url().'", "center_point": "-6.175557765244164,106.82715278255613", "office_name": "name"}';
        $encrypted              = CryptoJsAes::encrypt($params, $this->enkey);
        $data['params']         = $encrypted;
        $this->template->views('master/v_formoffice', $data);
    }
    public function formInfo()
    {
        $office_enid    = $this->input->post('id_edit');
        // $id = $this->secure->dec($idx);
        if(empty($office_enid)){
            $id_enedit = $this->session->tempdata('id_edit');
            if(empty($id_enedit)){
                redirect($this->link);die;
                $center_point       = "";
                $office_name        = '';
            }else{
                $id_edit            = $this->secure->dec($id_enedit);
                $data['off']	    = $this->mOff->getOffice($id_edit);
                $center_point       = $data['off']['center_point'];
                $office_name        = $data['off']['office_name'];
            }
        }else{
            $office_id          = $this->secure->dec($office_enid);
            $this->session->set_tempdata('id_edit',$office_enid, 100);
            $data['off']	    = $this->mOff->getOffice($office_id);
            $center_point       = $data['off']['center_point'];
            $office_name        = $data['off']['office_name'];
        }
        $data['title']      = "Data Office Detail";
        $data['link']       = $this->link;
        $data['page']       = "news";
        $data['oName']      = $data['off']['office_name'];
        $data['judul']      = "Posting";
        $data['deskripsi']  = "Data News";
        $params             = '{"link": "'.$this->link.'", "csrf": "'.$this->session->csrf_token.'", "permW": "'.$this->security_function->permissions($this->filename . "-w").'", "permC": "'.$this->security_function->permissions($this->filename . "-c").'", "permX": "'.$this->security_function->permissions($this->filename . "-x").'", "base_url":"'.base_url().'", "center_point": "'.$center_point.'", "office_name": "'.$office_name.'"}';
        $encrypted          = CryptoJsAes::encrypt($params, $this->enkey);
        $data['params']     = $encrypted;
        $this->template->views('master/v_forminfooffice', $data);
    }
    public function formActCust($idx="")
    {
        $id = $this->secure->dec($idx);
        if(empty($idx)){
            $this->form_validation->set_rules('center_point', 'Titik Maps', 'trim|required',
                [
                    'required' => 'You must select the maps point address'
                ]
            );
            // $this->form_validation->set_rules('office_code', 'Office Code', 'trim|required|is_unique[master_office.office_code]',
            //     [
            //         'is_unique' => 'Office Code is allready used',
            //         'required' => 'Office Code is required'
            //     ]
            // );
        }
        $this->form_validation->set_rules('company_idx', 'Company', 'trim|required');
        $this->form_validation->set_rules('office_name', 'Office Name', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required');
        $this->form_validation->set_rules('telephone', 'Telephone', 'trim|required');
        $this->form_validation->set_rules('address', 'Address', 'trim|required');
        $this->form_validation->set_rules('country', 'Country', 'trim|required');
        $this->form_validation->set_rules('province', 'Province', 'trim|required');
        $this->form_validation->set_rules('city', 'City', 'trim|required');
        $this->form_validation->set_rules('postal_code', 'Postal Code', 'trim|required');
        // $this->form_validation->set_rules('tUrutan', 'No Urut', 'trim|required');
        if($this->form_validation->run() == false){
            $msg['error']   = true;
            $msg['message'] = validation_errors();
            echo json_encode($msg);die;
        }
        $cekToken = validate_csrf_token();
        if($cekToken==false){
            $msg['status']  = true;
            $msg['text']    = 'Token invalid.';
            echo json_encode($msg);die;
        }
        $company        = $this->secure->dec($this->input->post('company_idx'));
        $office_code   = strtoupper(trim($this->input->post('office_code', TRUE)));
        $office_name   = strtoupper(trim($this->input->post('office_name', TRUE)));
        $center_point  = trim($this->input->post('center_point'));
        $point_lat     = trim($this->input->post('lat_point'));
        $point_long    = trim($this->input->post('long_point'));
        $email_id      = trim($this->input->post('email', TRUE));
        $telephone     = trim($this->input->post('telephone', TRUE));
        $fax           = trim($this->input->post('fax', TRUE));
        $tax_id        = trim($this->input->post('tax_id', TRUE));
        $address       = trim($this->input->post('address', TRUE));
        $country       = trim($this->input->post('country', TRUE));
        $province      = trim($this->input->post('province', TRUE));
        $city          = trim($this->input->post('city', TRUE));
        $postal_code   = trim($this->input->post('postal_code', TRUE));
        $state_code    = trim($this->input->post('state_code', TRUE));
        $status        = trim($this->input->post('tBlock'));

        if(empty($id)){
            $checkOfficeCode = $this->mOff->checkOfficeCode($office_code, $company);
            if($checkOfficeCode>0){
                $msg['status']  = true;
                $msg['text']    = 'Office Code [<b class="text-danger">'.$office_code.'</b>] Already exist.';
                echo json_encode($msg);die;
            }
            
            $data = [
                'company_idx'   => $company,
                'office_code'   => $office_code,
                'office_name'   => $office_name,
                'center_point'  => $center_point,
                'point_lat'     => $point_lat,
                'point_long'    => $point_long,
                'email_id'      => $email_id,
                'telephone'     => $telephone,
                'fax'           => $fax,
                'tax_id'        => $tax_id,
                'address'       => $address,
                'country'       => $country,
                'province'      => $province,
                'city'          => $city,
                'postal_code'   => $postal_code,
                'state_code'    => $state_code,
                'status'        => $status,
                'created_by'    => $this->idx,
                'created_on'    => date("Y-m-d H:i:s")
            ];
            $result = $this->mOff->insertMOffice($data);
            if($result == 1)
            {
                $msg['ledit']   = $this->link;
                $msg['success'] = true;
                $msg['text']    = 'Data Office Added Successfully.';
                echo json_encode($msg);die;
            }else{
                //show an error page or error message about the failed insert
                $msg['status']  = true;
                $msg['text']    = 'Koneksi error<br> Silahkan cek internet anda.';
                echo json_encode($msg);die;
            }
        }else{
            $data = [
                'company_idx'   => $company,
                'office_name'   => $office_name,
                'center_point'  => $center_point,
                'point_lat'     => $point_lat,
                'point_long'    => $point_long,
                'email_id'      => $email_id,
                'telephone'     => $telephone,
                'fax'           => $fax,
                'tax_id'        => $tax_id,
                'address'       => $address,
                'country'       => $country,
                'province'      => $province,
                'city'          => $city,
                'postal_code'   => $postal_code,
                'state_code'    => $state_code,
                'status'        => $status,
                'modified_by'   => $this->idx,
                'modified_on'   => date("Y-m-d H:i:s")
            ];
            $result = $this->mOff->updateMOffice($data, $id);
            if($result == 1)
            {
                $msg['ledit']   = $this->link;
                $msg['success'] = true;
                $msg['text']    = 'Data Office Updated Successfully.';
                echo json_encode($msg);die;
            }else{
                //show an error page or error message about the failed insert
                $msg['status']  = true;
                $msg['text']    = 'Koneksi error<br> Silahkan cek internet anda.';
                echo json_encode($msg);die;
            }
        }
    }
    public function kaluman()
    {
        $office_enid    = $this->input->post('id_edit');
        // $id = $this->secure->dec($idx);
        if(empty($office_enid)){
            $id_enedit = $this->session->tempdata('id_edit');
            if(empty($id_enedit)){
                redirect($this->link);die;
            }else{
                $id_edit            = $this->secure->dec($id_enedit);
                $data['title']      = "Edit Data Office";
                $data['off']	    = $this->mAccess->getTable('master_office',array('idx' => $id_edit))->row_array();
                $data['formact']    = $this->link.'/tambahKaluman/'.$id_enedit;
            }
        }else{
            $office_id          = $this->secure->dec($office_enid);
            $this->session->set_tempdata('id_edit',$office_enid, 100);
            $data['title']      = "Edit Data Office";
            $data['off']	    = $this->mAccess->getTable('master_office',array('idx' => $office_id))->row_array();
            $data['formact']    = $this->link.'/tambahKaluman/'.$office_enid;
        }
        $data['company']        = $this->mOff->getCompany();
        $data['link']           = $this->link;
        $data['page']           = "news";
        $data['judul']          = "Posting";
        $data['deskripsi']      = "Data News";
        $this->template->views('master/v_formoffice', $data);
    }
    public function tambahKaluman()
    {
        var_dump('<pre>');var_dump($_FILES['cmd_browse']);die;
        $upload_aset = $_FILES['cmd_browse']['name'];
        $extAset = pathinfo($upload_aset, PATHINFO_EXTENSION);
        $new_nameAset = time() . 'office.' . $extAset;

        if ($upload_aset) {
            $config['allowed_types'] = 'gif|jpg|jpeg|bmp|png';
            $config['max_size']     = '20480';
            $config['file_name']    = $new_nameAset;
            $dir_aset = FCPATH."img/office/";
            if (!is_dir($dir_aset)) {
                mkdir($dir_aset, 0777, TRUE);
            }
            $config['upload_path'] = $dir_aset;

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('cmd_browse')) {
                $new_aset = $new_nameAset;
                $source_path = $dir_aset . $new_aset;
                $target_path = $dir_aset;
                $config_manip = array(
                    'image_library' => 'gd2',
                    'source_image' => $source_path,
                    'new_image' => $target_path,
                    'maintain_ratio' => TRUE,
                    'width' => 500,
                );

                $this->load->library('image_lib', $config_manip);
                if (!$this->image_lib->resize()) {
                    $this->image_lib->clear();
                    $msg['status']   = true;
                    $msg['text']   = $this->image_lib->display_errors();
                    echo json_encode($msg);die;
                }else{
                    $this->image_lib->clear();
                    $msg['success']   = true;
                    $msg['text']    = 'Rebes bos.';
                    $msg['ledit']   = $this->link.'/kaluman/'.$this->session->tempdata('id_edit');
                    echo json_encode($msg);die;
                }
            } else {
                $msg['status']   = true;
                $msg['text']   = $this->upload->display_errors();
                echo json_encode($msg);die;
            }
        }else{
            $msg['status']   = true;
            $msg['text']   = 'Ga ada gambar nye.';
            echo json_encode($msg);die;
        }
    }
    public function changeStatus()
    {
        $id_office = $this->secure->dec($this->input->post('tId'));
        $id_status = $this->input->post('tClCode');
        $cekToken = validate_csrf_token();
        if($cekToken==false){
            $msg['status']  = true;
            $msg['text']    = 'Token invalid.';
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
        $cek = $this->mOff->changeStatusMOffice($data, $id_office);
        if($cek == true)
        {
            $msg['type']    = 'change';
            $msg['success'] = true;
        }else{
            $msg['status']   = true;
        }
        echo json_encode($msg);
    }

    public function createAccount()
    {
        $idx            = $this->secure->dec($this->input->get('id'));
        $office_name    = $this->input->get('name', true);
        $city           = $this->input->get('city', true);
        $noSegPpn       = $this->mOff->getAccNoSeg3('4100.01');
        $noSegNonPpn    = $this->mOff->getAccNoSeg3('4100.02');

        $acc_no         = str_pad(++$noSegPpn['part_three_account_number'],2,"0",STR_PAD_LEFT);
        $parent         = (int)$noSegPpn['parent_idx'];
        $partOne        = $noSegPpn['part_one_account_number'];
        $partTwo        = $noSegPpn['part_two_account_number'];
        $accType        = (int)$noSegPpn['account_type'];

        $acc_no2        = str_pad(++$noSegNonPpn['part_three_account_number'],2,"0",STR_PAD_LEFT);
        $parent2        = (int)$noSegNonPpn['parent_idx'];
        $partOne2       = $noSegNonPpn['part_one_account_number'];
        $partTwo2       = $noSegNonPpn['part_two_account_number'];
        $accType2       = (int)$noSegNonPpn['account_type'];
        $dataAccount = [
            [
                'parent_idx'                => $parent,
                'account_number'            => '4100.01.'.$acc_no,
                'account_name'              => 'PENDAPATAN '.strtoupper($office_name),
                'account_type'              => $accType,
                'account_segment'           => 3,
                'part_one_account_number'   => $partOne,
                'part_two_account_number'   => $partTwo,
                'part_three_account_number' => $acc_no,
                'starting_date'             => date('Y-m-d H:i:s'),
                'status'                    => 1,
                'company_id'                => $this->office,
                'office_idx'                => $this->office,
                'created_by'                => $this->idx,
                'created_on'                => date('Y-m-d H:i:s')
            ],
            [
                'parent_idx'                => $parent2,
                'account_number'            => '4100.02.'.$acc_no2,
                'account_name'              => 'PENDAPATAN '.strtoupper($office_name),
                'account_type'              => $accType2,
                'account_segment'           => 3,
                'part_one_account_number'   => $partOne2,
                'part_two_account_number'   => $partTwo2,
                'part_three_account_number' => $acc_no2,
                'starting_date'             => date('Y-m-d H:i:s'),
                'status'                    => 1,
                'company_id'                => $this->office,
                'office_idx'                => $this->office,
                'created_by'                => $this->idx,
                'created_on'                => date('Y-m-d H:i:s')
            ]
        ];
        // var_dump('<pre>');var_dump($dataAccount);die;
        $data = [
            'account_number'        => '4100.01.'.$acc_no,
            'account_number_noppn'  => '4100.02.'.$acc_no2,
            'modified_by'           => $this->idx,
            'modified_on'           => date("Y-m-d H:i:s")
        ];
        $cek = $this->mOff->createAccountMOffice($data, $dataAccount, $idx);
        if($cek == true)
        {
            $msg['text']    = $office_name.'<br> Sudah dibuat akun.';
            $msg['success'] = true;
        }else{
            $msg['text']    = 'Koneksi error<br> Silahkan cek internet anda.';
            $msg['status']  = true;
        }
        echo json_encode($msg);
    }

    public function createPuAccount()
    {
        $idx            = $this->secure->dec($this->input->get('id'));
        $office_name    = $this->input->get('name', true);
        $city           = $this->input->get('city', true);
        $noSegPpn       = $this->mOff->getAccNoSeg2x('1130');

        $acc_no         = str_pad(++$noSegPpn['part_two_account_number'],2,"0",STR_PAD_LEFT);
        $parent         = (int)$noSegPpn['parent_idx'];
        $partOne        = $noSegPpn['part_one_account_number'];
        $partTwo        = $noSegPpn['part_two_account_number'];
        $accType        = (int)$noSegPpn['account_type'];
        $dataAccount = [
            'parent_idx'                => $parent,
            'account_number'            => $partOne.'.'.$acc_no,
            'account_name'              => 'PU-'.strtoupper($office_name),
            'account_type'              => $accType,
            'account_segment'           => 2,
            'part_one_account_number'   => $partOne,
            'part_two_account_number'   => $acc_no,
            'starting_date'             => date('Y-m-d H:i:s'),
            'status'                    => 1,
            'company_id'                => $this->office,
            'office_idx'                => $this->office,
            'created_by'                => $this->idx,
            'created_on'                => date('Y-m-d H:i:s')
        ];
        // var_dump('<pre>');var_dump($dataAccount);die;
        $data = [
            'account_number'        => $partOne.'.'.$acc_no,
            'modified_by'           => $this->idx,
            'modified_on'           => date("Y-m-d H:i:s")
        ];
        $cek = $this->mOff->createPuAccountMOffice($data, $dataAccount, $idx);
        if($cek == true)
        {
            $msg['text']    = $office_name.'<br> Sudah dibuat akun.';
            $msg['success'] = true;
        }else{
            $msg['text']    = 'Koneksi error<br> Silahkan cek internet anda.';
            $msg['status']  = true;
        }
        echo json_encode($msg);
    }
}
/* End of file Office.php */
/* Location: ./application/controllers/Office.php */
