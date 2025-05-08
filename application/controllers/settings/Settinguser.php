<?php
defined('BASEPATH') or exit('No direct script access allowed');
require 'vendor/autoload.php';

use Nullix\CryptoJsAes\CryptoJsAes;
class Settinguser extends AUTH_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('settings/M_user', 'mUser');
        $this->load->library('security_function');
        $this->link	    = site_url('settings/').strtolower(get_class($this));
        $this->filename	= strtolower(get_class($this));
        $this->office   = $this->secure->dec($this->session->userdata('JToffice_id'));
        $this->hub      = $this->secure->dec($this->session->userdata('JThub_id'));
        $this->idx      = $this->secure->dec($this->session->userdata('JTidx'));
        $this->username = $this->session->userdata('JTuser_id');
        $this->level    = $this->secure->dec($this->session->userdata('JTlevel'));
        $this->enkey  	= $this->config->item('encryption_key');
    }
    public function index()
    {
        $access = $this->security_function->permissions($this->filename . "-r");
        if (empty($access)) die("<center><h1>You can not get the right to access this module</h1></center>");
        if(!empty($this->security_function->permissions($this->filename . "-w"))){
            $data['write']  = '<a href="javascript:void(0)" id="btnAdd" class="pull-right text-white small" title="Add New Daftar User" data-placement="right" data-popup="tooltip"><i class="icon-plus-circle2"></i></a>';
            $data['lock']   = 1;
        }else{
            $data['write']  = '<i class="icon-lock pull-right pt-1 " title="Locked New Daftar User" data-placement="right" data-popup="tooltip"></i>';
            $data['lock']   = 0;
        }
        $data['filename'] = $this->filename;
        $data["link"]     = $this->link;
        $data["levelnya"]   = $this->level;
        $data['userdata'] = $this->userdata;
        if($this->level == '1'){
            $data['level']    = $this->mAccess->getData('app_level_access', 'level_id', 'ASC','')->result();
        }else{
            $data['level']    = $this->mAccess->getData('app_level_access', 'level_id', 'ASC','',array('level_id <>'=>1))->result();
        }
        $data['office']     = $this->mAccess->getData('master_company', 'idx', 'ASC','',array())->result();
        $data['hub']        = $this->mAccess->getData('master_hub', 'idx', 'ASC','',array())->result();
        // var_dump('<pre>');var_dump($data['counterx']);die;
        $params     	    = '{"link": "'.$this->link.'", "csrf": "'.$this->session->csrf_token.'", "permW": "'.$this->security_function->permissions($this->filename . "-w").'", "permC": "'.$this->security_function->permissions($this->filename . "-c").'", "permX": "'.$this->security_function->permissions($this->filename . "-x").'", "base_url": "'.base_url().'", "lock": "'.$data['lock'].'", "levelnya": "'.$data["levelnya"].'"}';
        // encrypt
        $encrypted          = CryptoJsAes::encrypt($params, $this->enkey);
        $data['params']     = $encrypted;
        $this->template->views('pengaturan/v_user', $data);
    }
    public function showAllUser()
    {
        $fetch_data = $this->mUser->showAllUser();
        $data       = array();
        $i          = $_POST['start'] + 1;
        foreach ($fetch_data as $row) {
            $sub_array = array();
            if ($row->user_active == '1') {
                $statusU = '<span class="badge badge-success">Aktif</span>';
            } else {
                $statusU = '<span class="badge badge-danger">Non Aktif</span>';
            }
            if(!empty($this->security_function->permissions($this->filename . "-c"))){
                $change = "<a href='javascript:void(0);' id='$row->user_id' class='bEdit' data-popup='tooltip' title='Edit Data User' data-placement='right'><i class='fa fa-edit fa-lg'></i>&nbsp;</a>";
            }else{
                $change = '<i class="fa fa-lock fa-lg text-warning mr-2" data-popup="tooltip" title="Locked Edit Data User" data-placement="right"></i>&nbsp;';
            }
            if(!empty($this->security_function->permissions($this->filename . "-x"))){
                $execute  = "<a href='javascript:void(0);' id='$row->user_id' data='$row->user_name' class='bDelete text-danger' data-popup='tooltip' title='Delete Data User' data-placement='right'><i class='fa fa-trash-o fa-lg'></i>&nbsp;</a>";
            }else{
                $execute  = '<i class="mi-lock text-warning font-weight-black" data-popup="tooltip" title="Locked Delete Data User" data-placement="right"></i>&nbsp';
            }
            $sub_array[] = $i++;
            $sub_array[] = $row->fullname;
            $sub_array[] = $row->user_name;
            $sub_array[] = $row->level_alias;
            $sub_array[] = $statusU;
            $sub_array[] = !empty($row->created_on)?date("d-m-Y H:i:s", strtotime($row->user_created_on)).'#'.$row->modified_by:'';
            $sub_array[] = "$change $execute";
            $data[]      = $sub_array;
        }
        $output = array(
            "draw"            => intval($_POST["draw"]),
            "recordsTotal"    => $this->mUser->get_all_data(),
            "recordsFiltered" => $this->mUser->get_filtered_data(),
            "data"            => $data
        );
        echo json_encode($output);
    }
    // CONTOH LAIN CONTROLLER POST DATATABLE SERVERSIDE
    // public function posts2()
    // {
    //   $posts = $this->mUser->allposts();
    //   var_dump('<pre>');
    //   var_dump($posts);
    //   var_dump('</pre>');
    // }
    public function posts()
    {
        $columns = array(
            0 =>  null,
            1 =>  'user_name',
            2 =>  'fullname',
            3 =>  'level',
            4 =>  'address',
            5 =>  'email_id',
            6 =>  'mobile_phone',
            7 =>  'status',
            8 =>  'cr_by',
            9 =>  'created_on',
        );
        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = $columns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];
        $totalData = $this->mUser->allposts_count();
        $totalFiltered = $totalData;
        if(empty($this->input->post('search')['value']))
        {
            $posts = $this->mUser->allposts($limit,$start,$order,$dir);
        }
        else {
            $search = $this->input->post('search')['value'];
            $posts =  $this->mUser->posts_search($limit,$start,$search,$order,$dir);
            $totalFiltered = $this->mUser->posts_search_count($search);
        }
        $data = array();
        if(!empty($posts))
        {
            foreach ($posts as $post)
            {
                if($post->status == 1)
                {
                    $statusU = '<span class="badge badge-success">Aktif</span>';
                } else {
                    $statusU = '<span class="badge badge-danger">Non Aktif</span>';
                }
                if(!empty($this->security_function->permissions($this->filename . "-c"))){
                    $change = "<a href='javascript:void(0);' id='$post->idx' class='bEdit text-center' data-popup='tooltip' title='Edit Access User' data-placement='right'><i class='fa fa-edit fa-lg'></i>&nbsp;</a>";
                }else{
                    $change = '<i class="fa fa-lock fa-lg text-warning mr-2" data-popup="tooltip" title="Locked Edit Access User" data-placement="right"></i>&nbsp;';
                }
                if(!empty($this->security_function->permissions($this->filename . "-x"))){
                    $execute  = "<a href='javascript:void(0);' id='$post->idx' data='$post->fullname' class='bDelete text-danger text-center' data-popup='tooltip' title='Delete Access User' data-placement='right'><i class='fa fa-trash-o fa-lg'></i>&nbsp;</a>";
                }else{
                    $execute  = '<i class="mi-lock text-warning font-weight-black" data-popup="tooltip" title="Locked Delete Access User" data-placement="right"></i>&nbsp';
                }
                $nestedData['idx']              = ++$start;
                $nestedData['user_name']        = $post->user_name;
                $nestedData['fullname']         = $post->fullname;
                $nestedData['level']            = $post->level;
                $nestedData['address']          = $post->address;
                $nestedData['email']            = $post->email_id;
                $nestedData['mobile_phone']     = $post->mobile_phone;
                $nestedData['user_active']      = $statusU;
                $nestedData['user_stamp']       = date('d-M-Y H:i',strtotime($post->created_on)).'#<span class="text-primary">'.$post->cr_by.'</span>';
                $nestedData['aksi']             = "$change $execute";
                $data[] = $nestedData;
            }
        }
        $json_data = array(
            "draw"            => intval($this->input->post('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        );
        echo json_encode($json_data);
    }
    // CONTOH LAIN CONTROLLER POST DATATABLE SERVERSIDE END
    // CONTOH LAIN CONTROLLER2 POST DATATABLE SERVERSIDE
    function get_ajax()
    {
        $company    = $this->secure->dec($this->input->post('companyX'));
        $csrfToken  = validate_csrf_token();
        if($csrfToken == false){
            $list = array();
        }else{
            $list = $this->mUser->get_datatables($company);
        }
        $data = array();
        $no = @$_POST['start'];
        // var_dump('<pre>');var_dump($list);die;
        foreach ($list as $item) {
            $idx = $this->secure->enc($item->idx);
            if($item->status == 1)
            {
                $statusU = '<span class="badge badge-success">Aktif</span>';
            } else {
                $statusU = '<span class="badge badge-danger">Non Aktif</span>';
            }
            if(!empty($this->security_function->permissions($this->filename . "-c"))){
                $change = "<h5 class='m-0'><a href='javascript:void(0);' id='$idx' class='bEdit text-center badge badge-primary' data-popup='tooltip' title='Edit Access User' data-placement='right'><i class='icon-pencil5'></i></a></h5>";
                $reset = "<h5 class='m-0'><a href='javascript:void(0);' id='$idx' data='$item->user_name' class='bReset text-center badge badge-warning' data-popup='tooltip' title='Reset Password' data-placement='right'><i class='icon-rotate-ccw2'></i></a></h5>";
                if($item->status == 1)
                {
                    $statusU = "<h5 class='m-0'><a href='javascript:void(0);' id='$idx' data='$item->user_name' code='1' class='bStatus text-center badge badge-success' data-popup='tooltip' title='Change Status' data-placement='right'>Aktif</a></h5>";
                } else {
                    $statusU = "<h5 class='m-0'><a href='javascript:void(0);' id='$idx' data='$item->user_name' code='0' class='bStatus text-center badge badge-danger' data-popup='tooltip' title='Change Status' data-placement='right'>Non Aktif</a></h5>";
                }
            }else{
                $change = '<i class="icon-lock text-warning mr-2" data-popup="tooltip" title="Locked Edit Access User" data-placement="right"></i>&nbsp;';
                $reset = "";
                if($item->status == 1)
                {
                    $statusU = '<span class="badge badge-success">Aktif</span>';
                } else {
                    $statusU = '<span class="badge badge-danger">Non Aktif</span>';
                }
            }
            if(!empty($this->security_function->permissions($this->filename . "-x"))){
                $execute  = "<h5 class='m-0'><a href='javascript:void(0);' id='$idx' data='$item->fullname' class='bDelete text-center badge badge-danger' data-popup='tooltip' title='Delete Access User' data-placement='right'><i class='icon-bin'></i></a></h5>";
            }else{
                $execute  = '<i class="mi-lock text-warning font-weight-black" data-popup="tooltip" title="Locked Delete Access User" data-placement="right"></i>&nbsp';
            }
            $no++;
            $row = array();
            $row[] = $no.".";
            $row[] = "<div class='btn-group'>$change$execute$reset</div>";
            $row[] = $statusU;
            $row[] = $item->user_id;
            $row[] = $item->user_name;
            $row[] = $item->level;
            $row[] = $item->company;
            $row[] = $item->address;
            $row[] = $item->email_id;
            $row[] = $item->mobile_phone;
            $row[] = date('d-M-Y H:i',strtotime($item->created_on)).'#<span class="text-primary">'.$item->cr_by.'</span>';
            // add html for action
            $data[] = $row;
        }
        $output = array(
            "draw"            => @$_POST['draw'],
            "recordsTotal"    => $this->mUser->count_all($company),
            "recordsFiltered" => $this->mUser->count_filtered($company),
            "data"            => $data
        );
        // output to json format
        echo json_encode($output);
    }
    // CONTOH LAIN CONTROLLER2 POST DATATABLE SERVERSIDE END
    public function checkResponse()
    {
        $username = $this->input->post('resname');
        if($this->mUser->getUsername($username)) {
            echo '0';
        } else {
            echo '1';
        }
    }
    public function addUser()
    {
        $this->form_validation->set_rules('tNama', 'Fullname', 'trim|required');
        $this->form_validation->set_rules('tUsername', 'Username Login', 'trim|required');
        $this->form_validation->set_rules('tAddress', 'Address', 'trim|required');
        $this->form_validation->set_rules('tStatus', 'Status User', 'trim|required');
        $this->form_validation->set_rules('tLevel', 'Level Access', 'trim|required');
        $this->form_validation->set_rules('company', 'Company', 'trim|required');
        if ($this->form_validation->run() == false) {
            $msg['error']   = true;
            $msg['message'] = validation_errors();
            echo json_encode($msg);die;
        }
        $cekCsrf = validate_csrf_token();
        
        if ($cekCsrf == false) {
            $msg['status']  = true;
            $msg['text']    = "Please try again later.";
            echo json_encode($msg);die;
        }
        $fullname       = trim(filter_var($this->input->post('tNama',TRUE), FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_HIGH));
        $user_name      = trim(filter_var($this->input->post('tUsername',TRUE), FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_HIGH));
        $address        = trim(filter_var($this->input->post('tAddress', TRUE), FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_HIGH));
        $email          = trim(filter_var($this->input->post('tEmail', TRUE), FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_HIGH));
        $phone          = trim(filter_var($this->input->post('tPhone', TRUE), FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_HIGH));

        $userLevelId    = $this->secure->dec($this->input->post('tLevel', TRUE));
        $officeId       = $this->secure->dec($this->input->post('company', TRUE));
        $hub_id         = $this->secure->dec($this->input->post('tHub', TRUE));
        $counter_id     = $this->secure->dec($this->input->post('tCounter', TRUE));
        $status         = $this->input->post('tStatus', TRUE);

        $cekUser    = $this->mUser->getUsername($user_name);
        if($cekUser == true){
            $msg['status']  = true;
            $msg['text']    = "Username <b>$user_name</b> is Already Exist.";
            echo json_encode($msg);die;
        }

        $uType      = $this->mUser->uType($userLevelId);
        if(!$uType){
            $msg['status']  = true;
            $msg['text']    = "Please try again later.";
            echo json_encode($msg);die;
        }

        $field = array(
            'fullname'      => $fullname,
            'user_name'     => $fullname,
            'user_id'       => $user_name,
            'address'       => $address,
            'user_type'     => $uType['level_user_type'],
            'email_id'      => $email,
            'mobile_phone'  => $phone,
            'user_level_id' => $userLevelId,
            'company_idx'   => $officeId,
            'status'        => $status,
            'password'      => password_hash('123456', PASSWORD_DEFAULT),
            'created_on'    => date('Y-m-d H:i:s'),
            'created_by'    => $this->idx
        );
        // var_dump('<pre>');var_dump($field);die;
        $cek  = $this->mUser->insertData($field, 'user_account');
        if($cek){
            $msg['success'] = true;
            $msg['type']    = 'add';
            echo json_encode($msg);die;
        }else{
            $msg['status']  = true;
            $msg['text']    = "Please try again later.";
            echo json_encode($msg);die;
        }
        echo json_encode($msg);
    }
    public function editUser()
    {
        $id = $this->input->get('id');
        $idx = $this->secure->dec($id);
        $result = $this->mUser->editUser($idx);
        echo json_encode($result);
    }
    public function updateUser($id)
    {
        $idx = $this->secure->dec($id);
        $this->form_validation->set_rules('tNama', '', 'trim|required');
        $this->form_validation->set_rules('tUsername', 'Username Login', 'trim|required');
        $this->form_validation->set_rules('tStatus', 'Status User', 'trim|required');
        $this->form_validation->set_rules('tLevel', 'Level Access', 'trim|required');
        $this->form_validation->set_rules('company', 'Company', 'trim|required');
        if ($this->form_validation->run() == false) {
            $msg['error']   = true;
            $msg['message'] = validation_errors();
            echo json_encode($msg);die;
        }
        $csrf       = validate_csrf_token();
        if($csrf==false){
            $msg['status']  = true;
            $msg['text']    = 'Invalid Token.';
            echo json_encode($msg);die;
        }
        $where  = array(
            'idx' => $idx
        );
        $fullname       = trim(filter_var($this->input->post('tNama',TRUE), FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_HIGH));
        $address        = trim(filter_var($this->input->post('tAddress', TRUE), FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_HIGH));
        $email          = trim(filter_var($this->input->post('tEmail', TRUE), FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_HIGH));
        $phone          = trim(filter_var($this->input->post('tPhone', TRUE), FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_HIGH));
        $userLevelId    = $this->secure->dec($this->input->post('tLevel', TRUE));
        $officeId       = $this->secure->dec($this->input->post('company', TRUE));
        $status         = $this->input->post('tStatus', TRUE);

        $field1 = array(
            'fullname'      => $fullname,
            'address'       => $address,
            'email_id'      => $email,
            'mobile_phone'  => $phone,
            'user_level_id' => $userLevelId,
            'company_idx'   => $officeId,
            'status'        => $status,
            'modified_on'   => date('Y-m-d H:i:s'),
            'modified_by'   => $this->idx
        );
        // var_dump('<pre>');var_dump($field1);die;
        $cek  = $this->mAccess->updateData($where, $field1, 'user_account');
        if($cek){
            $msg['type']    = 'update';
            $msg['success'] = true;
            echo json_encode($msg);
            die;
        }else{
            $msg['status']  = true;
            $msg['text']    = "PLease try again later.";
            echo json_encode($msg);
            die;
        }
    }
    public function deleteUser()
    {
        $id = $this->input->get('id');
        $idx = $this->secure->dec($id);
        $result = $this->mUser->deleteUser($idx);
        if ($result == 1) {
        $msg['success'] = true;
        }else{
        $msg['status'] = true;
        }
        echo json_encode($msg);
    }
    public function hash_password1($password) {
        $this->load->library('PasswordHash', array('iteration_count_log2' => 8, 'portable_hashes' => FALSE));
        return $this->passwordhash->HashPassword($password);
    }
    private function hash_password($pass_user) {
        return password_hash($pass_user, PASSWORD_BCRYPT);
    }
    public function changeStatus()
    {
        $id = $this->input->post('tId');
        $idx = $this->secure->dec($id);
        $id_status = $this->input->post('tClCode');
        $csrf       = validate_csrf_token();
        if($csrf==false){
            $msg['status']  = true;
            $msg['text']    = 'Invalid Token.';
            echo json_encode($msg);die;
        }
        if($id_status == '1'){
            $changeId = 0;
        } else {
            $changeId = 1;
        }
        $data = [
            'status'      => $changeId
        ];
        $cek = $this->mUser->changeStatus($data, $idx);
        if($cek == 1)
        {
            $msg['type']    = 'change';
            $msg['success'] = true;
        }else{
            $msg['status']   = true;
        }
        echo json_encode($msg);
    }
    public function resetPassword()
    {
        $id   = $this->secure->dec($this->input->post('tId'));
        $name = $this->input->post('tCode');
        $csrfCek  = validate_csrf_token();
        if($csrfCek ==false){
            $msg['status']   = true;
            $msg['text']     = "Token expired,<br>Refresh page & try again.";
            echo json_encode($msg);die;
        }
        $result = $this->mUser->resetPassword($id);
        if($result == false){
            $msg['status'] = true;
            $msg['text']   = "Please try again later !";
            echo json_encode($msg);
            die;
        }else{
            $msg['type']    = 'change';
            $msg['success'] = true;
            echo json_encode($msg);
            die;
        }
    }
}
/* End of file user.php */
/* Location: ./application/controllers/user.php */
