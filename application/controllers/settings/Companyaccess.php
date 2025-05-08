<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Companyaccess extends AUTH_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('settings/M_companyaccess', 'mComAccess');
        $this->load->model('M_auth');
        $this->load->library('security_function');
        $this->link	    = site_url('settings/').strtolower(get_class($this));
        $this->filename = strtolower(get_class($this));
        $this->office   = $this->secure->dec($this->session->userdata('JToffice_id'));
        $this->hub      = $this->secure->dec($this->session->userdata('JThub_id'));
        $this->idx      = $this->secure->dec($this->session->userdata('JTidx'));
        $this->username = $this->session->userdata('JTuser_id');
        $this->level    = $this->secure->dec($this->session->userdata('JTlevel'));
    }
    public function index()
    {
        $access = $this->security_function->permissions($this->filename . "-r");
        if (empty($access)) die("<center><h1>You can not get the right to access this module</h1></center>");
        if(!empty($this->security_function->permissions($this->filename . "-w"))){
            $data['write']  = '<a href="javascript:void(0)" id="btnAdd" class="pull-right text-white small" title="Add New Office Access" data-placement="left" data-popup="tooltip"><i class="icon-plus-circle2"></i></a>';
            $data['lock']   = 1;
        }else{
            $data['write']  = '<i class="icon-lock pull-right pt-1 " title="Locked New Office Access" data-placement="left" data-popup="tooltip"></i>';
            $data['lock']   = 0;
        }
        $data['filename'] = $this->filename;
        $data["link"]     = $this->link;
        $data['userdata'] = $this->userdata;
        if($this->level == 1){
            $data['user']    = $this->mAccess->getData('app_level_access', 'level_id', 'ASC','',array('level_active'=>1,'level_deletion'=>'N'))->result();
        // $data['user']    = $this->mAccess->getData('user_account', 'idx', 'ASC','',array('client_id'=>0))->result();
        }else{
            $data['user']    = $this->mAccess->getWhereNotInAndWhere('app_level_access', '', '','level_id',array(1),'','level_id','ASC')->result();
        }
        $data['office']  = $this->mAccess->getData('master_hub', 'idx', 'ASC')->result();
        $this->template->views('pengaturan/v_companyaccess', $data);
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
                $change = "<a href='javascript:void(0);' id='$row->user_id' class='bEdit' data-popup='tooltip' title='Edit Data User' data-placement='top'><i class='fa fa-edit fa-lg'></i>&nbsp;</a>";
            }else{
                $change = '<i class="fa fa-lock fa-lg text-warning mr-2" data-popup="tooltip" title="Locked Edit Data User" data-placement="top"></i>&nbsp;';
            }
            if(!empty($this->security_function->permissions($this->filename . "-x"))){
                $execute  = "<a href='javascript:void(0);' id='$row->user_id' data='$row->user_name' class='bDelete text-danger' data-popup='tooltip' title='Delete Data User' data-placement='top'><i class='fa fa-trash-o fa-lg'></i>&nbsp;</a>";
            }else{
                $execute  = '<i class="mi-lock text-warning font-weight-black" data-popup="tooltip" title="Locked Delete Data User" data-placement="top"></i>&nbsp';
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
        // $csrfName = $this->input->post('CSRFToken');
        // $csrfToken  = $this->session->csrf_token;
        // if($csrfToken != $csrfName){
        //     $list = null;
        // }else{
        // }
        $list = $this->mComAccess->get_datatables();
        $data = array();
        $no = @$_POST['start'];
        foreach ($list as $item) {
            $inOffice   = "";
            $aOffice    = explode(',',$item->access_office_idx);
            $idx        = $this->secure->enc($item->idx);
            foreach($aOffice as $a):
                if(!empty($a)):
                    $nameO = $this->mComAccess->getOfficeName($a);
                    $inOffice .= $nameO." ,";
                else :
                    $inOffice = "";
                endif;
            endforeach;
            if(!empty($this->security_function->permissions($this->filename . "-c"))){
                $change = "<h5><a href='javascript:void(0);' id='$idx' class='bEdit badge badge-primary text-center' data-popup='tooltip' title='Edit Access Office' data-placement='left'><i class='fa fa-edit fa-lg'></i></a><h5>";
            }else{
                $change = '<h5><span class="badge badge-warning"><i class="fa fa-lock fa-lg text-warning mr-2" data-popup="tooltip" title="Locked Edit Access Office" data-placement="left"></i></span></h5>';
            }
            if(!empty($this->security_function->permissions($this->filename . "-x"))){
                $execute  = "<h5><a href='javascript:void(0);' id='$idx' data='$item->level_name' class='bDelete badge badge-danger' data-popup='tooltip' title='Delete Access Office' data-placement='left'><i class='fa fa-trash-o fa-lg'></i></a></h5>";
            }else{
                $execute  = '<h5><span class="badge badge-danger"><i class="mi-lock text-warning font-weight-black" data-popup="tooltip" title="Locked Delete Access Office" data-placement="left"></i></span></h5>';
            }
            $no++;
            $row = array();
            $row[] = $no.".";
            $row[] = $item->level_name;
            $row[] = empty($inOffice)?"<span class='text-danger'>Tidak Mempunyai Akses Office</span>":$inOffice;
            // add html for action
            $row[] = "<div class='btn-group'>$change $execute</div>";
            $data[] = $row;
        }
        $output = array(
            "draw"            => @$_POST['draw'],
            "recordsTotal"    => intval($this->mComAccess->count_all()),
            "recordsFiltered" => intval($this->mComAccess->count_filtered()),
            "data"            => $data,
        );
        // output to json format
        echo json_encode($output);
    }
    // CONTOH LAIN CONTROLLER2 POST DATATABLE SERVERSIDE END
    public function checkResponse()
    {
        if($this->mUser->getUsername($_POST['resname'])) {
            echo '0';
        } else {
            echo '1';
        }
    }
    public function getAccessCompany()
    {
        $this->form_validation->set_rules('tUname', 'Level Access', 'trim|required',
        ['required' => 'Level Access Is Require.']
        );
        if ($this->form_validation->run() == false) {
            $msg['error']   = true;
            $msg['message'] = validation_errors();
            echo json_encode($msg);die;
        }
        $tUser      = $this->secure->dec($this->input->post('tUname', TRUE));
        $resCompany  = $this->mAccess->readtable('master_company', '','','','',array('company_name'=>'ASC'))->result();
        if(!$resCompany){
            $tr = "
            <tr>
                <td colspan='2' class='text-center'><span class='text-danger'>No Data</span></td>
            </tr> ";
            $gettr[] = $tr;
            $msg['res_tr']  = $gettr;
            echo json_encode($msg);die;
        }
        foreach($resCompany AS $r){
            $resUser	= $this->mAccess->getTable('app_oaccess',array('access_level_id' => $tUser))->result();
            $modul    = $this->secure->enc($r->idx);
            if($resUser){
                foreach($resUser AS $u){
                    $checkCompany 		= explode(',',$u->access_company_idx);
                    if (in_array($r->idx, $checkCompany)):
                        $chekedCompany="checked='checked'";
                    else:
                        $chekedCompany='';
                    endif;
                    $tr = "
                        <tr>
                            <td class='text-center'><span class='text-center'>$r->company_name</span></td>
                            <td class='text-center'><input type='checkbox' name='tCompany[]' $chekedCompany value='$modul'></td>
                        </tr> ";
                    $gettr[] = $tr;
                }
            }else{
                $tr = "
                    <tr>
                        <td class='text-center'><span class='text-center'>$r->company_name</span></td>
                        <td class='text-center'>
                            <input type='checkbox' name='tCompany[]' value='$modul'>
                        </td>
                    </tr> ";
                $gettr[] = $tr;
            }
        }
        $msg['res_tr']  = $gettr;
        echo json_encode($msg);die;
    }
    public function fActAccessCompany()
    {
        $this->form_validation->set_rules('tUname', '', 'trim|required');
        if ($this->form_validation->run() == false) {
            $msg['error']   = true;
            $msg['message'] = validation_errors();
            echo json_encode($msg);die;
        }
        $tUname       = $this->secure->dec($this->input->post('tUname', TRUE));
        $tCompany      = $this->input->post('tCompany', TRUE);
        $isCompany     = "";
        $isCompanyName = "";
        $field1       = array();
        $field2       = array(
            'access_level_id' => $tUname
        );
        if(!empty($tCompany)){
            foreach($tCompany as $o){
                $com_idx = $this->secure->dec($o);
                $getCompany = $this->mAccess->getTable('master_company', ['idx'=>$com_idx])->row_array();
                $isCompany.=trim($com_idx).",";
                $isCompanyName.=trim($getCompany['company_name']).","; 
            }
            $isCompany = substr($isCompany,0,strlen($isCompany)-1);
            $isCompanyName = substr($isCompanyName,0,strlen($isCompanyName)-1);
        }
        $where = array(
            'access_level_id'   => $tUname
        );
        if(!empty($tCompany)){
            $field1['access_company_idx']  = $isCompany;
            $field1['access_company_name'] = $isCompanyName;
            $field2['access_company_idx']  = $isCompany;
            $field2['access_company_name'] = $isCompanyName;
        }else{
            $field1['access_company_idx']  = null;
            $field1['access_company_name'] = null;
            $field2['access_company_idx']  = null;
            $field2['access_company_name'] = null;
        }
        $cekCount  = $this->mAccess->getTable('app_oaccess',array('access_level_id' => $tUname))->num_rows();
        if($cekCount>0){
            $cekOnline = $this->M_auth->onlineUpdateAll($this->username);
            if($cekOnline == false){
                $msg['text']    = 'Error update username online to offline';
                $msg['status']  = true;
                echo json_encode($msg);die;
            }
            $cek          = $this->mAccess->updateData($where, $field1, 'app_oaccess');
            if(!$cek){
                $msg['text']    = 'Error Update Access Company To Database.';
                $msg['status']  = true;
                echo json_encode($msg);die;
            }
            $msg['text']    = 'Data Access Company Updated Successfully.';
            $msg['success'] = true;
            echo json_encode($msg);die;
        }else{
            $cekOnline = $this->M_auth->onlineUpdateAll($this->username);
            if($cekOnline == false){
                $msg['text']    = 'Error update username online to offline';
                $msg['status']  = true;
                echo json_encode($msg);die;
            }
            $cek          = $this->mAccess->insertData($field2, 'app_oaccess');
            if(!$cek){
                $msg['text']    = 'Error Insert Access Company To Database.';
                $msg['status']  = true;
                echo json_encode($msg);die;
            }
            $msg['text']    = 'Data Access Company Added Successfully.';
            $msg['success'] = true;
            echo json_encode($msg);die;
        }
    }
    public function editAccessOffice()
    {
        $id     = $this->secure->dec($this->input->get('id', TRUE));
        $result = $this->mComAccess->editAccessOffice($id);
        $add = ['access_level_id_en' => $this->secure->enc($result['access_level_id'])];
        $data = array_merge($result, $add);
        echo json_encode($data);die;
    }
    public function addUser()
    {
        $msg = array('error' => false);
        $msg = array('status' => false);
        $this->form_validation->set_rules('tNama', 'Fullname', 'trim|required');
        $this->form_validation->set_rules('tUsername', 'Username Login', 'trim|required');
        $this->form_validation->set_rules('tStatus', 'Status User', 'trim|required');
        $this->form_validation->set_rules('tLevel', 'Level Access', 'trim|required');
        $this->form_validation->set_rules('tOffice', 'Office', 'trim|required');
        if ($this->form_validation->run() == false) {
        $msg['error']   = true;
        $msg['message'] = validation_errors();
        } else {
        $field = array(
            'fullname'        => htmlspecialchars($this->input->post('tNama')),
            'user_name'       => $this->input->post('tUsername', TRUE),
            'email_id'        => $this->input->post('tEmail', TRUE),
            'mobile_phone'    => $this->input->post('tPhone', TRUE),
            'user_level_id'   => $this->input->post('tLevel', TRUE),
            'office_id'       => $this->input->post('tOffice', TRUE),
            'status'          => $this->input->post('tStatus', TRUE),
            'password'        => password_hash('123456', PASSWORD_DEFAULT),
            'created_on'      => date('Y-m-d H:i:s'),
            'created_by'      => $this->idx
        );
        $cek  = $this->mUser->insertData($field, 'user_account');
        if($cek){
            $msg['success'] = true;
            $msg['type']    = 'add';
        }else{
            $msg['status']  = true;
        }
        // $msg['error']   = true;
        // $msg['message'] = $field;
        }
        echo json_encode($msg);
    }
    public function editUser()
    {
        $result = $this->mUser->editUser();
        echo json_encode($result);
    }
    public function updateUser($id="")
    {
        $msg = array('error' => false);
        $msg = array('status' => false);
        $this->form_validation->set_rules('tNama', '', 'trim|required');
        $this->form_validation->set_rules('tUsername', 'Username Login', 'trim|required');
        $this->form_validation->set_rules('tStatus', 'Status User', 'trim|required');
        $this->form_validation->set_rules('tLevel', 'Level Access', 'trim|required');
        $this->form_validation->set_rules('tOffice', 'Office', 'trim|required');
        if ($this->form_validation->run() == false) { 
        $msg['error']   = true;
        $msg['message'] = validation_errors();
        }else{
        $where  = array(
            'idx' => $id
        );
        $field1 = array(
            'fullname'        => $this->input->post('tNama', TRUE),
            'user_name'       => $this->input->post('tUsername', TRUE),
            'email_id'        => $this->input->post('tEmail', TRUE),
            'mobile_phone'    => $this->input->post('tPhone', TRUE),
            'user_level_id'   => $this->input->post('tLevel', TRUE),
            'office_id'       => $this->input->post('tOffice', TRUE),
            'status'          => $this->input->post('tStatus', TRUE),
            'created_on'      => date('Y-m-d H:i:s'),
            'created_by'      => $this->idx
        );
        // $field2 = array(
        //     'fullname'        => $this->input->post('tNama', TRUE),
        //     'user_name'       => $this->input->post('tUsername', TRUE),
        //     'email_id'        => $this->input->post('tEmail', TRUE),
        //     'telephone'       => $this->input->post('tPhone', TRUE),
        //     'status'          => $this->input->post('tStatus', TRUE),
        //     'password'        => $this->hash_password($this->input->post("tPass", true)),
        //     'created_on'      => date('Y-m-d H:i:s'),
        //     'created_by'      => $this->idx
        // );
        // if(empty($_POST['tPass'])){
        //   $cek  = $this->mAccess->updateData($where, $field1, 'user_account');
        // }else{
        // }
        $cek  = $this->mAccess->updateData($where, $field1, 'user_account');
        if($cek){
            $msg['type']    = 'update';
            $msg['success'] = true;
        }else{
            $msg['status']  = true;
        }
        }
        echo json_encode($msg);
    }
    public function deleteUser()
    {
        $result         = $this->mUser->deleteUser();
        $msg['success'] = false;
        if ($result) {
        $msg['success'] = true;
        }
        echo json_encode($msg);
    }
    public function deleteAccessOffice()
    {
        $result         = $this->mComAccess->deleteAccessOffice();
        if ($result == 1) {
        $msg['success'] = true;
        }else{
        $msg['status']  = true;
        $msg['text']    = $result;
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
}
/* End of file user.php */
/* Location: ./application/controllers/user.php */
