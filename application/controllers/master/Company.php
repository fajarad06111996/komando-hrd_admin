<?php
defined('BASEPATH') or exit('No direct script access allowed');
require "vendor/autoload.php";
use Nullix\CryptoJsAes\CryptoJsAes;
class Company extends AUTH_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('master/M_company', 'mCompany');
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
        $params             = '{"link": "'.$this->link.'", "csrf": "'.$this->session->csrf_token.'", "permW": "'.$this->security_function->permissions($this->filename . "-w").'", "permC": "'.$this->security_function->permissions($this->filename . "-c").'", "permX": "'.$this->security_function->permissions($this->filename . "-x").'", "lock": "'.$data['lock'].'"}';
        $encrypted          = CryptoJsAes::encrypt($params, $this->enkey);
        $data['params']     = $encrypted;
        $this->template->views('master/v_company', $data);
    }
    // CONTOH LAIN CONTROLLER2 POST DATATABLE SERVERSIDE
    function get_ajax() {
        $csrfName = $this->input->post('CSRFToken');
        $csrfToken  = validate_csrf_token();
        if($csrfToken == false){
            $list = array();
        }else{
            $list = $this->mCompany->get_datatables();
        }
        // $list = $this->mCompany->get_datatables();
        $data = array();
        $no = @$_POST['start'];
        foreach ($list as $item) {
            $idx = $this->secure->enc($item->idx);
            $info = "<h5><a href='javascript:void(0);' id='$idx' data='$item->company_name' code='1' class='bInfo text-center badge badge-primary' data-popup='tooltip' title='Hub Office' data-placement='right'><i class='icon-price-tag2'></i></a></h5>";
            if(!empty($this->security_function->permissions($this->filename . "-c"))){
                $change = "<h5><a href='javascript:void(0);' id='$idx' class='bEdit text-center badge badge-info' data-popup='tooltip' title='Edit Office' data-placement='right'><i class='icon-pencil7'></i></a></h5>";
                if($item->status == 1)
                {
                    $statusU = "<h5><a href='javascript:void(0);' id='$idx' data='$item->company_name' code='1' class='bStatus text-center badge badge-success' data-popup='tooltip' title='Change Status' data-placement='left'>Aktif</a></h5>";
                // $statusU = '<span class="badge badge-success">Aktif</span>';
                } else {
                    $statusU = "<h5><a href='javascript:void(0);' id='$idx' data='$item->company_name' code='0' class='bStatus text-center badge badge-danger' data-popup='tooltip' title='Change Status' data-placement='left'>Non Aktif</a></h5>";
                // $statusU = '<span class="badge badge-danger">Non Aktif</span>';
                }
            }else{
                $change = '<h5><span class="badge badge-warning"><i class="icon-lock" data-popup="tooltip" title="Locked Edit Office" data-placement="right"></i></span></h5>';
                if($item->status == 1)
                {
                    $statusU = "<span class='text-center badge badge-success'>Aktif</span>";
                }else{
                    $statusU = "<span class='text-center badge badge-danger'>Non Aktif</span>";
                }
            }
            if(!empty($this->security_function->permissions($this->filename . "-x"))){
                $execute  = "<h5><a href='javascript:void(0);' id='$idx' data='$item->company_name' class='bDelete text-center badge badge-danger' data-popup='tooltip' title='Delete Office' data-placement='right'><i class='icon-bin'></i></a></h5>";
            }else{
                $execute  = '<h5><span class="badge badge-warning"><i class="mi-lock font-weight-black" data-popup="tooltip" title="Locked Delete Office" data-placement="right"></i></span></h5>';
            }
            $no++;
            $row = array();
            $row[] = $no.".";
            $row[] = $item->company_code;
            $row[] = $item->company_name;
            $row[] = $item->address;
            $row[] = $item->email_id;
            $row[] = $statusU;
            // add html for action
            $row[] = "<div class='btn-group'>$change$execute$info</div>";
            $data[] = $row;
        }
        $output = array(
            "draw"            => @$_POST['draw'],
            "recordsTotal"    => $this->mCompany->count_all(),
            "recordsFiltered" => $this->mCompany->count_filtered(),
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
            }else{
                $id_edit            = $this->secure->dec($id_enedit);
                $data['title']      = "Edit Data Company";
                $off	            = $this->mAccess->getTable('master_company',array('idx' => $id_edit))->row_array();
                $data['off']	    = $off;
                $data['formact']    = $this->link.'/formActCust/'.$id_enedit;
                $params             = '{"link": "'.$this->link.'", "csrf": "'.$this->session->csrf_token.'", "permW": "'.$this->security_function->permissions($this->filename . "-w").'", "permC": "'.$this->security_function->permissions($this->filename . "-c").'", "permX": "'.$this->security_function->permissions($this->filename . "-x").'", "center_point": "'.(empty($off)?'-6.175557765244164,106.82715278255613':(empty($off['center_point'])?'-6.175557765244164,106.82715278255613':$off['center_point'])).'", "company_name": "'.$off['company_name'].'", "base_url": "'.base_url().'"}';
            }
        }else{
            $office_id          = $this->secure->dec($office_enid);
            $this->session->set_tempdata('id_edit',$office_enid, 100);
            $data['title']      = "Edit Data Company";
            $off	            = $this->mAccess->getTable('master_company',array('idx' => $office_id))->row_array();
            $data['off']	    = $off;
            $data['formact']    = $this->link.'/formActCust/'.$office_enid;
            $params             = '{"link": "'.$this->link.'", "csrf": "'.$this->session->csrf_token.'", "permW": "'.$this->security_function->permissions($this->filename . "-w").'", "permC": "'.$this->security_function->permissions($this->filename . "-c").'", "permX": "'.$this->security_function->permissions($this->filename . "-x").'", "center_point": "'.(empty($off)?'-6.175557765244164,106.82715278255613':(empty($off['center_point'])?'-6.175557765244164,106.82715278255613':$off['center_point'])).'", "company_name": "'.$off['company_name'].'", "base_url": "'.base_url().'"}';
        }
        // $data['office_type']    = $this->mCompany->getOfficeType();
        // $data['getOrigin']      = $this->mCompany->getOriginx();
        $data['link']           = $this->link;
        $data['page']           = "news";
        $data['judul']          = "Posting";
        $data['deskripsi']      = "Data News";
        $encrypted              = CryptoJsAes::encrypt($params, $this->enkey);
        $data['params']         = $encrypted;
        $this->template->views('master/v_formcompany', $data);
    }
    public function formAdd()
    {
        $data['formact']        = $this->link.'/formActCust';
        $data['title']          = "Add New Data Company";
        // $data['office_type']    = $this->mCompany->getOfficeType();
        // $data['getOrigin']      = $this->mCompany->getOriginx();
        $data['link']           = $this->link;
        $data['page']           = "news";
        $data['judul']          = "Posting";
        $data['deskripsi']      = "Data News";
        $params                 = '{"link": "'.$this->link.'", "csrf": "'.$this->session->csrf_token.'", "permW": "'.$this->security_function->permissions($this->filename . "-w").'", "permC": "'.$this->security_function->permissions($this->filename . "-c").'", "permX": "'.$this->security_function->permissions($this->filename . "-x").'", "center_point": "-6.175557765244164,106.82715278255613", "company_name": "Company Center Point", "base_url": "'.base_url().'"}';
        $encrypted              = CryptoJsAes::encrypt($params, $this->enkey);
        $data['params']         = $encrypted;
        $this->template->views('master/v_formcompany', $data);
    }
    public function formInfo()
    {
        $office_enid    = $this->input->post('id_edit');
        // $id = $this->secure->dec($idx);
        if(empty($office_enid)){
            $id_enedit = $this->session->tempdata('id_edit');
            if(empty($id_enedit)){
                redirect($this->link);die;
                $center_point = "-6.175557765244164,106.82715278255613";
            }else{
                $id_edit            = $this->secure->dec($id_enedit);
                $data['off']	    = $this->mCompany->getCompany($id_edit);
                $center_point       = $data['off']['center_point'];
            }
        }else{
            $office_id          = $this->secure->dec($office_enid);
            $this->session->set_tempdata('id_edit',$office_enid, 100);
            $data['off']	    = $this->mCompany->getCompany($office_id);
            $center_point       = $data['off']['center_point'];
        }
        $data['title']      = "Data Company Detail";
        $data['link']       = $this->link;
        $data['page']       = "news";
        $data['oName']      = $data['off']['company_name'];
        $data['judul']      = "Posting";
        $data['deskripsi']  = "Data News";
        $params             = '{"link": "'.$this->link.'", "csrf": "'.$this->session->csrf_token.'", "permW": "'.$this->security_function->permissions($this->filename . "-w").'", "permC": "'.$this->security_function->permissions($this->filename . "-c").'", "permX": "'.$this->security_function->permissions($this->filename . "-x").'", "center_point": "'.$center_point.'", "company_name": "'.$data['oName'].'", "base_url": "'.base_url().'"}';
        $encrypted          = CryptoJsAes::encrypt($params, $this->enkey);
        $data['params']     = $encrypted;
        $this->template->views('master/v_forminfocompany', $data);
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
            $this->form_validation->set_rules('company_code', 'Company Code', 'trim|required|is_unique[master_company.company_code]',
                [
                    'is_unique' => 'Company Code is allready used',
                    'required' => 'Company Code is required'
                ]
            );
        }
        $this->form_validation->set_rules('company_name', 'Company Name', 'trim|required');
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
        $company_code   = strtoupper(trim($this->input->post('company_code', TRUE)));
        $company_name   = strtoupper(trim($this->input->post('company_name', TRUE)));
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
            $data = [
                'company_code'   => $company_code,
                'company_name'   => $company_name,
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
            $result = $this->mCompany->insertMOffice($data);
            if($result == 1)
            {
                $msg['ledit']   = $this->link;
                $msg['success'] = true;
                $msg['text']    = 'Data Company Added Successfully.';
                echo json_encode($msg);die;
            }else{
                //show an error page or error message about the failed insert
                $msg['status']  = true;
                $msg['text']    = 'Koneksi error<br> Silahkan cek internet anda.';
                echo json_encode($msg);die;
            }
        }else{
            $data = [
                'company_code'   => $company_code,
                'company_name'   => $company_name,
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
            $result = $this->mCompany->updateMOffice($data, $id);
            if($result == 1)
            {
                $msg['ledit']   = $this->link;
                $msg['success'] = true;
                $msg['text']    = 'Data Company Updated Successfully.';
                echo json_encode($msg);die;
            }else{
                //show an error page or error message about the failed insert
                $msg['status']  = true;
                $msg['text']    = 'Koneksi error<br> Silahkan cek internet anda.';
                echo json_encode($msg);die;
            }
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
        $cek = $this->mCompany->changeStatusMOffice($data, $id_office);
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
        $noSegPpn       = $this->mCompany->getAccNoSeg3('4100.01');
        $noSegNonPpn    = $this->mCompany->getAccNoSeg3('4100.02');

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
            ],
        ];
        // var_dump('<pre>');var_dump($dataAccount);die;
        $data = [
            'account_number'        => '4100.01.'.$acc_no,
            'account_number_noppn'  => '4100.02.'.$acc_no2,
            'modified_by'           => $this->idx,
            'modified_on'           => date("Y-m-d H:i:s")
        ];
        $cek = $this->mCompany->createAccountMOffice($data, $dataAccount, $idx);
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
        $noSegPpn       = $this->mCompany->getAccNoSeg2x('1130');

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
        $cek = $this->mCompany->createPuAccountMOffice($data, $dataAccount, $idx);
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
