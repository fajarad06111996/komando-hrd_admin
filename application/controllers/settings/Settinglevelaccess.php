<?php
defined('BASEPATH') or exit('No direct script access allowed');
require 'vendor/autoload.php';

use Nullix\CryptoJsAes\CryptoJsAes;
class Settinglevelaccess extends AUTH_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_auth');
        $this->load->model('M_level', 'mLevel');
        $this->load->library('security_function');
        $this->link	    = site_url('settings/').strtolower(get_class($this));
        $this->filename	= strtolower(get_class($this));
        $this->office   = $this->secure->dec($this->session->userdata('JToffice_id'));
        $this->tOffice  = $this->secure->dec($this->session->userdata('JToffice_type'));
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
            $data['write']  = '<a href="javascript:void(0)" id="btnAdd" class="pull-right text-white small" title="Add New Access User" data-placement="right" data-popup="tooltip"><i class="icon-plus-circle2"></i></a>';
            $data['lock']   = 1;
        }else{
            $data['write']  = '<i class="icon-lock pull-right pt-1 " title="Locked New Access User" data-placement="right" data-popup="tooltip"></i>';
            $data['lock']   = 0;
        }
        $data['listUserType']   = $this->mLevel->listUserType();
        $data['listOffice']     = $this->mLevel->getOffice();
        $data['oType']          = $this->tOffice;
        $data['filename']       = $this->filename;
        $data["link"]           = $this->link;
        $data['userdata']       = $this->userdata;
        $params     	        = '{"link": "'.$this->link.'", "csrf": "'.$this->session->csrf_token.'", "permW": "'.$this->security_function->permissions($this->filename . "-w").'", "permC": "'.$this->security_function->permissions($this->filename . "-c").'", "permX": "'.$this->security_function->permissions($this->filename . "-x").'", "base_url": "'.base_url().'", "lock": "'.$data['lock'].'"}';
		// encrypt
		$encrypted          = CryptoJsAes::encrypt($params, $this->enkey);
		$data['params']     = $encrypted;
        $this->template->views('pengaturan/v_level', $data);
    }
    public function showAllLevel()
    {
        $fetch_data = $this->mLevel->showAllLevel();
        $data       = array();
        $i          = $_POST['start'] + 1;
        foreach ($fetch_data as $row) {
        $sub_array = array();
        if ($row->level_active == '1') {
            $statusU = '<span class="badge badge-success">Aktif</span>';
        } else {
            $statusU = '<span class="badge badge-danger">Non Aktif</span>';
        }
        if ($row->level_deletion == 'N') {
            $statusD = '<span class="badge badge-success">No Delete</span>';
        } else {
            $statusD = '<span class="badge badge-danger">Yes Delete</span>';
        }
        if(!empty($this->security_function->permissions($this->filename . "-c"))){
            $change = "<a href='javascript:void(0);' id='$row->level_id' class='bEdit text-center' data-popup='tooltip' title='Edit Access User' data-placement='right'><i class='fa fa-edit fa-lg'></i>&nbsp;</a>";
            $accCount = "<h5><a href='javascript:void(0);' id='$client_enid' class='bCounter text-center badge badge-primary' data-popup='tooltip' title='Edit Counter Viewer' data-placement='right'><i class='icon-folder-search'></i></a></h5>";
        }else{
            $change = '<i class="fa fa-lock fa-lg text-warning mr-2" data-popup="tooltip" title="Locked Edit Access User" data-placement="right"></i>&nbsp;';
            $accCount = '<h5><span class="badge badge-warning bLockStatus"><i class="icon-lock" data-popup="tooltip" title="Locked Edit Counter Viewer" data-placement="right"></i></span></h5>';
        }
        if(!empty($this->security_function->permissions($this->filename . "-x"))){
            $execute  = "<a href='javascript:void(0);' id='$row->level_id' data='$row->level_alias' class='bDelete text-danger text-center' data-popup='tooltip' title='Delete Access User' data-placement='right'><i class='fa fa-trash-o fa-lg'></i>&nbsp;</a>";
        }else{
            $execute  = '<i class="mi-lock text-warning font-weight-black" data-popup="tooltip" title="Locked Delete Access User" data-placement="right"></i>&nbsp';
        }
        $sub_array[] = $i++;
        $sub_array[] = $row->level_name;
        $sub_array[] = $row->level_alias;
        $sub_array[] = $statusU;
        $sub_array[] = $statusD;
        $sub_array[] = $row->level_stamp_user;
        $sub_array[] = $row->level_stamp_date;
        $sub_array[] = $row->level_remark;
        $sub_array[] = "<div class='btn-group'>$change$execute</div>";
        $data[]      = $sub_array;
        }
        $output = array(
        "draw"            => intval($_POST["draw"]),
        "recordsTotal"    => $this->mLevel->get_all_data(),
        "recordsFiltered" => $this->mLevel->get_filtered_data(),
        "data"            => $data
        );
        echo json_encode($output);
    }
    // CONTOH LAIN CONTROLLER POST DATATABLE SERVERSIDE
    public function posts()
    {
        $csrfName = $this->input->post('CSRFToken');
        $csrfToken  = $this->session->csrf_token;
        if($csrfName != $csrfToken){
        }
            $columns = array( 
            0 =>  'level_id', 
            1 =>  'level_name',
            2 =>  'level_active',
            5 =>  'level_stamp_date',
            6 =>  'level_remark'
        );
        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = $columns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];
        $totalData = $this->mLevel->allposts_count();
        $totalFiltered = $totalData; 
        if(empty($this->input->post('search')['value']))
        {
        if($csrfName != $csrfToken){
            $posts = null;
        }else{
            $posts = $this->mLevel->allposts($limit,$start,$order,$dir);
        }
        }
        else {
        $search = $this->input->post('search')['value']; 
        if($csrfName != $csrfToken){
            $posts = null;
            $totalFiltered = 0;
        }else{
            $posts =  $this->mLevel->posts_search($limit,$start,$search,$order,$dir);
            $totalFiltered = $this->mLevel->posts_search_count($search);
        }
        }
        $data = array();
        if(!empty($posts))
        {
        foreach ($posts as $post)
        {
            if(!empty($this->security_function->permissions($this->filename . "-c"))){
                $change = "<h5 class='m-0'><a href='javascript:void(0);' id='$post->level_id' class='bEdit text-center badge badge-primary' data-popup='tooltip' title='Edit Access User' data-placement='right'><i class='icon-pencil5'></i></a></h5>";
                if($post->level_active == 1)
                {
                    $statusU = "<h5 class='m-0'><a href='javascript:void(0);' id='$post->level_id' data='$post->level_alias' code='1' class='bStatus text-center badge badge-success' data-popup='tooltip' title='Change Status' data-placement='right'>Aktif</a></h5>";
                } else {
                    $statusU = "<h5 class='m-0'><a href='javascript:void(0);' id='$post->level_id' data='$post->level_alias' code='0' class='bStatus text-center badge badge-danger' data-popup='tooltip' title='Change Status' data-placement='right'>Non Aktif</a></h5>";
                }
            }else{
                $change = "<h5 class='m-0'><span class='bEdit text-center badge badge-warning' data-popup='tooltip' title='Locked Edit Access User' data-placement='right'><i class='icon-lock'></i></span></h5>";
                if($post->level_active == 1)
                {
                    $statusU = '<span class="badge badge-success">Aktif</span>';
                } else {
                    $statusU = '<span class="badge badge-danger">Non Aktif</span>';
                }
            }
            if(!empty($this->security_function->permissions($this->filename . "-x"))){
                $execute  = "<h5 class='m-0'><a href='javascript:void(0);' id='$post->level_id' data='$post->level_alias' class='bDelete text-center badge badge-danger' data-popup='tooltip' title='Delete Access User' data-placement='left'><i class='icon-bin'></i></a></h5>";
            }else{
                $execute  = "<h5 class='m-0'><span class='bEdit text-center badge badge-warning' data-popup='tooltip' title='Locked Delete Access User' data-placement='left'><i class='icon-lock'></i></span></h5>";
            }
            $nestedData['level_id']         = ++$start;
            $nestedData['level_name']       = $post->level_name;
            $nestedData['level_alias']      = $post->level_alias;
            $nestedData['level_active']     = $statusU;
            $nestedData['level_stamp_date'] = date('d-M-Y H:i',strtotime($post->level_stamp_date)).'#<span class="text-primary">'.$post->level_stamp_user.'</span>';
            $nestedData['level_remark']     = $post->level_remark;
            $nestedData['aksi']             = "<div class='btn-group'>$change $execute</div>";
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
    function get_ajax() 
    {
        $csrfName   = $this->input->post('CSRFToken');
        $csrfToken  = validate_csrf_token();
        if($csrfToken == false){
            $list = array();
        }else{
            $list = $this->mLevel->get_datatables();
        }
        $data = array();
        $no = @$_POST['start'];
        $order = @$_POST['order'];
        foreach ($list as $item) {
            $idx = $this->secure->enc($item->level_id);
            if(!empty($this->security_function->permissions($this->filename . "-c"))){
                $change = "<h5 class='m-0'><a href='javascript:void(0);' id='$idx' class='bEdit text-center badge badge-primary' data-popup='tooltip' title='Edit Access User' data-placement='right'><i class='icon-pencil5'></i></a></h5>";
                $accCount = "<h5><a href='javascript:void(0);' id='$idx' class='bCounter text-center badge badge-primary' data-popup='tooltip' title='Edit Counter Viewer' data-placement='right'><i class='icon-folder-search'></i></a></h5>";
                $accSettings = "<h5><a href='javascript:void(0);' id='$idx' class='bSettings text-center badge badge-primary' data-popup='tooltip' title='Edit Settings' data-placement='right'><i class='icon-folder-search'></i></a></h5>";
                if($item->level_active == 1)
                {
                    $statusU = "<h5 class='m-0'><a href='javascript:void(0);' id='$idx' data='$item->level_alias' code='1' class='bStatus text-center badge badge-success' data-popup='tooltip' title='Change Status' data-placement='right'>Aktif</a></h5>";
                } else {
                    $statusU = "<h5 class='m-0'><a href='javascript:void(0);' id='$idx' data='$item->level_alias' code='0' class='bStatus text-center badge badge-danger' data-popup='tooltip' title='Change Status' data-placement='right'>Non Aktif</a></h5>";
                }
            }else{
                $change = '<h5><span class="badge badge-warning"><i class="icon-lock" data-popup="tooltip" title="Locked Edit Payment" data-placement="right"></i></span></h5>';
                $accCount = '<h5><span class="badge badge-warning bLockStatus"><i class="icon-lock" data-popup="tooltip" title="Locked Edit Counter Viewer" data-placement="right"></i></span></h5>';
                $accSettings = '<h5><span class="badge badge-warning bLockStatus"><i class="icon-lock" data-popup="tooltip" title="Locked Edit Settings" data-placement="right"></i></span></h5>';
                if($item->status == 1)
                {
                    $statusU = "<span class='text-center badge badge-success'>Aktif</span>";
                }else{
                    $statusU = "<span class='text-center badge badge-danger'>Non Aktif</span>";
                }
            }
            if(!empty($this->security_function->permissions($this->filename . "-x"))){
                $execute  = "<h5 class='m-0'><a href='javascript:void(0);' id='$idx' data='$item->level_alias' class='bDelete text-center badge badge-danger' data-popup='tooltip' title='Delete Access User' data-placement='left'><i class='icon-bin'></i></a></h5>";
            }else{
                $execute  = "<h5 class='m-0'><span class='bEdit text-center badge badge-warning' data-popup='tooltip' title='Locked Delete Access User' data-placement='left'><i class='icon-lock'></i></span></h5>";
            }
            $no++;
            $row = array();
            $row[] = "<div class='btn-group'>$change</div>";
            $row[] = $no.".";
            $row[] = $item->level_name;
            $row[] = $item->level_alias;
            $row[] = $item->userType==null?"<h5 class='m-0'><a href='javascript:void(0);' class='bStatusx text-center badge badge-danger' data-popup='tooltip' title='Unset' data-placement='right'>Unset</a></h5>":$item->userType;
            $row[] = $item->office_name;
            $row[] = $statusU;
            // $row[] = "<div class='btn-group'>$accCount</div>";
            // $row[] = "<div class='btn-group'>$accSettings</div>";
            $row[] = date('d-M-Y H:i',strtotime($item->level_stamp_date)).'<br>#<span class="text-primary">'.$item->level_stamp_user.'</span>';
            $row[] = $item->level_remark;
            $data[] = $row;
        }
        $output = array(
            "draw"            => @$_POST['draw'],
            "recordsTotal"    => $this->mLevel->count_all(),
            "recordsFiltered" => $this->mLevel->count_filtered(),
            "data"            => $data,
        );
        // output to json format
        echo json_encode($output);
    }
    public function checkResponse()
    {
        $levname = $this->input->post('levname', TRUE);
        if($this->mLevel->getLevel($levname)) {
            echo '0';
        } else {
            echo '1';
        }
        // if($this->mLevel->getLevel($_POST['levname'])) {
        //     echo '0';
        // } else {
        //     echo '1';
        // }
    }
    public function addLevel()
    {
        $now = date('Y-m-d H:i:s');
        $csrftoken    = $this->input->post('csrftoken');
        $level_name   = $this->input->post('level_name', TRUE);
        $level_alias  = $this->input->post('level_alias', TRUE);
        $level_user_t   = $this->secure->dec($this->input->post('user_type', TRUE));
        $level_active = $this->input->post('level_active', TRUE);
        $level_remark = $this->input->post('level_remark');
        $office     = $this->secure->dec($this->input->post('office'));
        $this->form_validation->set_rules('level_name', 'Level Name', 'trim|required');
        $this->form_validation->set_rules('level_alias', 'Level Alias', 'trim|required');
        $this->form_validation->set_rules('level_active', 'Status Level', 'trim|required');
        if($this->tOffice!=2){
            $this->form_validation->set_rules('office', 'Office', 'trim|required');
        }
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
        if($this->tOffice==2){
            $field = array(
                'level_name'        => $level_name,
                'level_alias'       => $level_alias,
                'level_user_type'   => $level_user_t,
                'level_active'      => $level_active,
                'level_stamp_user'  => $this->username,
                'level_stamp_date'  => $now,
                'level_remark'      => $level_remark,
                'office_idx'        => $this->office
            );
        }else{
            $field = array(
                'level_name'        => $level_name,
                'level_alias'       => $level_alias,
                'level_user_type'   => $level_user_t,
                'level_active'      => $level_active,
                'level_stamp_user'  => $this->username,
                'level_stamp_date'  => $now,
                'level_remark'      => $level_remark,
                'office_idx'        => $office
            );
        }
        $cekOnline = $this->M_auth->onlineUpdateAll($this->username);
        if($cekOnline == false){
            $msg['text']      = 'Error update username online to offline';
            $msg['success']   = false;
            echo json_encode($msg);die;
        }else{
            $cek  = $this->mLevel->insertData($field, 'app_level_access');
            if($cek){
                $msg['success'] = true;
                $msg['type']    = 'add';
                echo json_encode($msg);die;
            }else{
                $msg['status']  = false;
                echo json_encode($msg);die;
            }
        }
    }
    public function editLevel()
    {
        $id = $this->secure->dec($this->input->get('id'));
        if($this->input->get('CSRFToken') != $this->session->csrf_token){
            $result = false;
        }else{
            $result = $this->mLevel->editLevel($id);
        }
        echo json_encode($result);
    }
    public function updateLevel($idx="")
    {
        $id             = $this->secure->dec($idx);
        $now            = date('Y-m-d H:i:s');
        $csrftoken      = $this->input->post('csrftoken', TRUE);
        $level_name     = trim($this->input->post('level_name', TRUE));
        $level_alias    = trim($this->input->post('level_alias', TRUE));
        $level_user_t   = $this->secure->dec($this->input->post('user_type', TRUE));
        $level_active   = $this->input->post('level_active', TRUE);
        $level_remark   = $this->input->post('level_remark', TRUE);
        $office         = $this->secure->dec($this->input->post('office'));
        $this->form_validation->set_rules('level_name', 'Level Name', 'trim|required');
        $this->form_validation->set_rules('level_alias', 'Level Alias', 'trim|required');
        $this->form_validation->set_rules('level_active', 'Status Level', 'trim|required');
        if($this->tOffice!=2){
            $this->form_validation->set_rules('office', 'Office', 'trim|required');
        }
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
            'level_id' => $id
        );
        if($this->tOffice==2){
            $field = array(
                'level_name'        => $level_name,
                'level_alias'       => $level_alias,
                'level_user_type'   => $level_user_t,
                'level_active'      => $level_active,
                'level_stamp_user'  => $this->username,
                'level_stamp_date'  => $now,
                'level_remark'      => $level_remark
            );
        }else{
            $field = array(
                'level_name'        => $level_name,
                'level_alias'       => $level_alias,
                'level_user_type'   => $level_user_t,
                'level_active'      => $level_active,
                'level_stamp_user'  => $this->username,
                'level_stamp_date'  => $now,
                'level_remark'      => $level_remark,
                'office_idx'        => $office
            );
        }
        $cekOnline = $this->M_auth->onlineUpdateAll($this->username);
        if($cekOnline == false){
            $msg['text']      = 'Error update username online to offline';
            $msg['success']   = false;
            echo json_encode($msg);die;
        }else{
            $cek  = $this->mLevel->updateData($where, $field, 'app_level_access');
            if($cek){
                $msg['type']    = 'update';
                $msg['success'] = true;
                echo json_encode($msg);die;
            }else{
                $msg['status']  = true;
                echo json_encode($msg);die;
            }
        }
    }
    // public function deleteLevel()
    // {
    //   $id = $this->input->get('id');
    //   $where = array(
    //     'level_id'  => $id
    //   );
    //   $field = array (
    //     'level_deletion'  => 'Y'
    //   );
    //   $result         = $this->mAccess->updateData($where, $field, 'app_level_access');
    //   $msg['success'] = false;
    //   if ($result) {
    //     $msg['success'] = true;
    //   }
    //   echo json_encode($msg);
    // }
    public function getAccessModul()
    {
        $msg = array('error' => false);
        $msg = array('status' => false);
        $this->form_validation->set_rules('tUname', '', 'trim|required');
        if ($this->form_validation->run() == false) {
            $msg['error']   = true;
            $msg['message'] = validation_errors();
            echo json_encode($msg);die;
        }
        $tUser      = $this->input->post('tUname', TRUE);
        $tMenu      = $this->input->post('tMenu', TRUE);
        $tSubmenu   = $this->input->post('tSubmenu', TRUE);
        if(!empty($tMenu)){
            $resMenu  = $this->mAccess->readtable('app_menu', '',array('menu_parent_id'=>$tMenu),'','',array('menu_alias'=>'ASC'))->result();
            if(count($resMenu)!=0){
                foreach($resMenu AS $r){
                    $resUser	= $this->mAccess->getTable('app_uaccess',array('access_username'=> $tUser,'access_menu_id'=> $tMenu))->result();
                    $modul    = strtolower(str_replace(' ','',$r->menu_title));
                    if($resUser){
                        foreach($resUser AS $u){
                            $checkMenu 		    = explode(',',$u->access_submenu_id);
                            $checkPermissions = explode(',',$u->access_permissions_id);
                            if (in_array($r->menu_id, $checkMenu)):
                                $chekedModul="checked='checked'";
                            else:
                                $chekedModul='';
                            endif;
                            $permR  = strtolower(str_replace(' ','',$r->menu_title)).'-r';
                            $permW  = strtolower(str_replace(' ','',$r->menu_title)).'-w';
                            $permC  = strtolower(str_replace(' ','',$r->menu_title)).'-c';
                            $permX  = strtolower(str_replace(' ','',$r->menu_title)).'-x';
                            $checkR = in_array($permR, $checkPermissions)?"checked='checked'":'';
                            $checkW = in_array($permW, $checkPermissions)?"checked='checked'":'';
                            $checkC = in_array($permC, $checkPermissions)?"checked='checked'":'';
                            $checkX = in_array($permX, $checkPermissions)?"checked='checked'":'';
                            $tr = "
                                <tr>
                                <td><input type='checkbox' id='$modul' $chekedModul name='tModul[]' value='$r->menu_id' selected onclick='getClick(this)'><span class='pl-2 pt-0'>$r->menu_alias</span></td>
                                <td class='text-center'><input type='checkbox' name='tsub[]' $checkR value='$modul-r' class='$modul'></td>
                                <td class='text-center'><input type='checkbox' name='tsub[]' $checkW value='$modul-w' class='$modul'></td>
                                <td class='text-center'><input type='checkbox' name='tsub[]' $checkC value='$modul-c' class='$modul'></td>
                                <td class='text-center'><input type='checkbox' name='tsub[]' $checkX value='$modul-x' class='$modul'></td>
                                </tr> ";
                            $gettr[] = $tr;
                        }
                    }else{
                        $tr = "
                            <tr>
                                <td><input type='checkbox' id='$modul' name='tModul[]'  value='$r->menu_id' selected onclick='getClick(this)'><span class='pl-2 pt-0'>$r->menu_alias</span></td>
                                <td class='text-center'><input type='checkbox' name='tsub[]' value='$modul-r' class='$modul'></td>
                                <td class='text-center'><input type='checkbox' name='tsub[]' value='$modul-w' class='$modul'></td>
                                <td class='text-center'><input type='checkbox' name='tsub[]' value='$modul-c' class='$modul'></td>
                                <td class='text-center'><input type='checkbox' name='tsub[]' value='$modul-x' class='$modul'></td>
                            </tr> ";
                        $gettr[] = $tr;
                    }
                }
                $msg['res_tr']  = $gettr;
            }else{
                $msg['status']  = true;
            }
        }else{
            $resMenu	= $this->mAccess->readtable('app_menu', '',array('menu_parent_id'=>$tSubmenu),'','',array('menu_alias'=>'ASC'))->result();
            if(count($resMenu)!=0){
                foreach($resMenu AS $r){
                    $resUser	= $this->mAccess->getTable('app_uaccess',array('access_username'=> $tUser,'access_menu_id'=> $tSubmenu))->result();
                    $modul    = strtolower(str_replace(' ','',$r->menu_title));
                    if($resUser){
                        foreach($resUser AS $u){
                            $checkMenu 		    = explode(',',$u->access_submenu_id);
                            $checkPermissions = explode(',',$u->access_permissions_id);
                            if (in_array($r->menu_id, $checkMenu)):
                                $chekedModul="checked='checked'";
                            else:
                                $chekedModul='';
                            endif;
                            $permR  = strtolower(str_replace(' ','',$r->menu_title)).'-r';
                            $permW  = strtolower(str_replace(' ','',$r->menu_title)).'-w';
                            $permC  = strtolower(str_replace(' ','',$r->menu_title)).'-c';
                            $permX  = strtolower(str_replace(' ','',$r->menu_title)).'-x';
                            $checkR = in_array($permR, $checkPermissions)?"checked='checked'":'';
                            $checkW = in_array($permW, $checkPermissions)?"checked='checked'":'';
                            $checkC = in_array($permC, $checkPermissions)?"checked='checked'":'';
                            $checkX = in_array($permX, $checkPermissions)?"checked='checked'":'';
                            $tr = "
                                <tr>
                                <td><input type='checkbox' id='$modul' $chekedModul name='tModul[]' value='$r->menu_id' selected onclick='getClick(this)'><span class='pl-2 pt-0'>$r->menu_alias</span></td>
                                <td class='text-center'><input type='checkbox' name='tsub[]' $checkR value='$modul-r' class='$modul'></td>
                                <td class='text-center'><input type='checkbox' name='tsub[]' $checkW value='$modul-w' class='$modul'></td>
                                <td class='text-center'><input type='checkbox' name='tsub[]' $checkC value='$modul-c' class='$modul'></td>
                                <td class='text-center'><input type='checkbox' name='tsub[]' $checkX value='$modul-x' class='$modul'></td>
                                </tr> ";
                            $gettr[] = $tr;
                        }
                    }else{
                        $tr = "
                            <tr>
                                <td><input type='checkbox' id='$modul' name='tModul[]'  value='$r->menu_id' selected onclick='getClick(this)'><span class='pl-2 pt-0'>$r->menu_alias</span></td>
                                <td class='text-center'><input type='checkbox' name='tsub[]' value='$modul-r' class='$modul'></td>
                                <td class='text-center'><input type='checkbox' name='tsub[]' value='$modul-w' class='$modul'></td>
                                <td class='text-center'><input type='checkbox' name='tsub[]' value='$modul-c' class='$modul'></td>
                                <td class='text-center'><input type='checkbox' name='tsub[]' value='$modul-x' class='$modul'></td>
                            </tr> ";
                        $gettr[] = $tr;
                    }
                }
                $msg['res_tr']  = $gettr;
            }else{
                $msg['status']  = true;
            }
        }
        echo json_encode($msg);
    }
    public function fActAccessUser()
    {
        $msg = array('error' => false);
        $msg = array('status' => false);
        $tUname = $this->input->post('tUname', TRUE);
        $tMenu  = $this->input->post('tMenu', TRUE);
        $tSubmenu = $this->input->post('tSubmenu', TRUE);
        $this->form_validation->set_rules('tUname', '', 'trim|required');
        if ($this->form_validation->run() == false) {
            $msg['error']   = true;
            $msg['message'] = validation_errors();
            echo json_encode($msg);die;
        }
        $urutParent = 99;
        $urutSub    = 99;
        $tModul     = ""; 
        $modul 	    = $this->input->post('tModul');
        if(!empty($modul)){
            foreach($modul as $user_modul){
                $tModul.=trim($user_modul).",";
            }
            $tModul = substr($tModul,0,strlen($tModul)-1); 
        }
        $tsub       =""; 
            $permission = $this->input->post('tsub');
        if(!empty($permission)){
            foreach($permission as $user_permission){
                $tsub.=trim($user_permission).",";
            }
            $tsub = substr($tsub,0,strlen($tsub)-1);  
        }
        if(!empty($tMenu)){
            $iUrut      = $this->mAccess->getTable('app_menu',array('menu_id' => $tMenu))->row_array();
            $urutParent = !empty($iUrut)?$iUrut['menu_urutan']:'99';
        }else{
            $iUrut      = $this->mAccess->getTable('app_menu',array('menu_id' => $tSubmenu))->row_array();
            $urutSub    = !empty($iUrut)?$iUrut['menu_urutan']:'99';
        }
        $field1 = array(
            'access_username'         => $tUname,
            'access_menu_id'          => $tMenu,
            'access_menu_urutan'      => $urutParent,
            'access_submenu_id'       => !empty($tModul)?$tModul:null,
            'access_rolemenu'         => 1,
            'access_permissions_id'   => !empty($tsub)?$tsub:null,
            'access_ip_address'       => null,
        );
        $field2 = array(
            'access_username'         => $tUname,
            'access_menu_id'          => $tSubmenu,
            'access_menu_urutan'      => $urutSub,
            'access_submenu_id'       => !empty($tModul)?$tModul:null,
            'access_rolemenu'         => 2,
            'access_permissions_id'   => !empty($tsub)?$tsub:null,
            'access_ip_address'       => null,
        );
        if(!empty($tMenu)){
            $cekCount  = $this->mAccess->getTable('app_uaccess',array('access_menu_id' => $tMenu,'access_username' => $tUname))->num_rows();
            if($cekCount>0){
                $cekOnline = $this->M_auth->onlineUpdateAll($this->username);
                if($cekOnline == false){
                    $msg['text']      = 'Error update username online to offline';
                    $msg['success']   = false;
                }else{
                    $cek          = $this->mAccess->updateData(array('access_menu_id' => $tMenu,'access_username' => $tUname), $field1, 'app_uaccess');
                    $msg['type']  = 'update';
                }
            }else{
                $cekOnline = $this->M_auth->onlineUpdateAll($this->username);
                if($cekOnline == false){
                    $msg['text']      = 'Error update username online to offline';
                    $msg['success']   = false;
                }else{
                    $cek          = $this->mAccess->insertData($field1, 'app_uaccess');
                    $msg['type']  = 'add';
                }
            }
        }else{
            $cekCount  = $this->mAccess->getTable('app_uaccess',array('access_menu_id' => $tSubmenu,'access_username' => $tUname))->num_rows();
            if($cekCount>0){
                $cekOnline = $this->M_auth->onlineUpdateAll($this->username);
                if($cekOnline == false){
                    $msg['text']      = 'Error update username online to offline';
                    $msg['success']   = false;
                }else{
                    $cek          = $this->mAccess->updateData(array('access_menu_id' => $tSubmenu,'access_username' => $tUname), $field2, 'app_uaccess');
                    $msg['type']  = 'update';
                }
            }else{
                $cekOnline = $this->M_auth->onlineUpdateAll($this->username);
                if($cekOnline == false){
                    $msg['text']      = 'Error update username online to offline';
                    $msg['success']   = false;
                }else{
                    $cek          = $this->mAccess->insertData($field2, 'app_uaccess');
                    $msg['type']  = 'add';
                }
            }
        }
        if($cek){
            $msg['success'] = true;
        }else{
            $msg['status']  = true;
        }
        echo json_encode($msg);
    }
    public function editAccessUser()
    {
        $id     = $this->input->get('id');
        $result = $this->mAccess->getFromDatabase($id,'app_uaccess','access_id');
        echo json_encode($result);
    }
    public function updateUser($id="")
    {
        $msg = array('error' => false);
        $msg = array('status' => false);
        $tNama      = $this->input->post('tNama', TRUE);
        $tUsername  = $this->input->post('tUsername', TRUE);
        $tEmail     = $this->input->post('tEmail', TRUE);
        $tStatus    = $this->input->post('tStatus', TRUE);
        $tCompany   = $this->input->post('tCompany', TRUE);
        $tHub       = $this->input->post('tHub', TRUE);
        $this->form_validation->set_rules('tNama', '', 'trim|required');
        $this->form_validation->set_rules('tUsername', 'Username Login', 'trim|required');
        $this->form_validation->set_rules('tCompany', 'Company User', 'trim|required');
        $this->form_validation->set_rules('tHub', 'Group User', 'trim|required');
        $this->form_validation->set_rules('tStatus', 'Status User', 'trim|required');
        if ($this->form_validation->run() == false) { 
            $msg['error']   = true;
            $msg['message'] = validation_errors();
            echo json_encode($msg);die;
        }
        $where  = array(
            'user_id' => $id
        );
        $field1 = array(
            'user_fullname'   => $tNama,
            'user_name'       => $tUsername,
            'user_email'      => $tEmail,
            'user_active'     => $tStatus,
            'user_company'    => $tCompany,
            'user_hub'        => $tHub
        );
        $cekOnline = $this->M_auth->onlineUpdateAll($this->username);
        if($cekOnline == false){
            $msg['text']      = 'Error update username online to offline';
            $msg['success']   = false;
            echo json_encode($msg);die;
        }
        $cek  = $this->mAccess->updateData($where, $field1, 'app_user');
        if($cek){
            $msg['type']    = 'update';
            $msg['success'] = true;
        }else{
            $msg['status']  = true;
        }
        echo json_encode($msg);
    }
    public function deleteMenuLevel()
    {
        $cekU           = $this->mLevel->cekUserLevel();
        $getA           = $this->mLevel->getAccessLevel();
        $cekA           = $this->mLevel->cekAccessLevel();
        $getU           = $this->mLevel->getUserLevel();
        $a = "";
        $b = "";
        foreach($getU as $g){
            $a.=trim($g->user_fullname).", ";
        }
        $a = substr($a,0,strlen($a)-1);
        foreach($getA as $ga){
            $b.=trim($ga->menu_alias).", ";
        }
        $b = substr($b,0,strlen($b)-1);
        if($this->input->get('CSRFToken') != $this->session->csrf_token){
            $msg['text']      = 'You can\'t hacked this site.';
            $msg['success']   = false;
            echo json_encode($msg);die;
        }
        if($cekU>0){
            $msg['text']   = 'Level masih ada relasi dengan user : '.$a;
            $msg['success']   = false;
            echo json_encode($msg);die;
        }
        if($cekA>0){
            $msg['text']   = 'Level masih ada relasi dengan menu : '.$b;
            $msg['success']   = false;
            echo json_encode($msg);die;
        }
        $cekOnline = $this->M_auth->onlineUpdateAll($this->username);
        if($cekOnline == false){
            $msg['text']      = 'Error update username online to offline';
            $msg['success']   = false;
            echo json_encode($msg);die;
        }
        $result         = $this->mLevel->deleteLevel();
        if ($result) {
            $msg['success'] = true;
        }else{
            $msg['success'] = false;
        }
        echo json_encode($msg);
    }
    public function changeStatus()
    {
        $level_id   = $this->secure->dec($this->input->post('tId'));
        $id_status  = $this->input->post('tClCode');
        $csrf       = validate_csrf_token();
        if($csrf==false){
            $msg['status']  = true;
            $msg['text']    = 'Invalid Token.';
            echo json_encode($msg);die;
        }
        // var_dump($id_status);die;
        if($id_status == '1'){
            $changeId = 0;
        } else {
            $changeId = 1;
        }
        $data = [
            'level_active'      => $changeId
        ];
        $cek = $this->mLevel->changeStatus($data, $level_id);
        if($cek == 1)
        {
            $msg['type']    = 'change';
            $msg['success'] = true;
            echo json_encode($msg);die;
        }else{
            $msg['status']   = true;
            echo json_encode($msg);die;
        }
    }
    public function getAccessCounter()
    {
        $level_id   = $this->secure->dec($this->input->post('level_id', TRUE));
        if($this->tOffice==2){
            $checkOff   = $this->mAccess->readtable('master_counter', '',array('status' => 1,'office_idx'=>$this->office),'','','')->result();
        }else{
            $checkOff   = $this->mAccess->readtable('master_counter', '',array('status' => 1),'','','')->result();
        }
        if($checkOff){
            foreach($checkOff as $off){
                $resLevel	= $this->mAccess->getTable('app_level_access',array('level_id'=> $level_id,'office_idx' => $this->office))->result();
                $counter_idx     = $off->idx;
                $encounter_idx   = $this->secure->enc($off->idx);
                $counter_name    = $off->counter_name;
                if($resLevel){
                    foreach($resLevel AS $x){
                        $checkHub 		= explode(',',$x->counter_viewer);
                        if (in_array($counter_idx, $checkHub)):
                            $chekedHub="checked='checked'";
                        else:
                            $chekedHub='';
                        endif;
                            $tr2 = "
                            <tr>
                                <td class='text-center'><span class='text-center'>$counter_name</span></td>
                                <td class='text-center'><input type='checkbox' name='tCounter[]' $chekedHub value='$encounter_idx'></td>
                            </tr> ";
                        // $gettr[] = $checkMenu;
                        $gettr2[] = $tr2;
                    }
                }else{
                    $tr2 = "
                        <tr>
                            <td class='text-center'><span class='text-center'>$counter_name</span></td>
                            <td class='text-center'><input type='checkbox' name='tCounter[]' value='$encounter_idx'></td>
                        </tr> ";
                    $gettr2[] = $tr2;
                }
            }
        }else{
            $tr2 = "
            <tr>
                <td colspan='2' class='text-center'><span class='text-center text-danger'>No Data</span></td>
            </tr> ";
            $gettr2[] = $tr2;
        }
        $msg['res_tr2']  = $gettr2;
        echo json_encode($msg);
    }
    public function fActAccessCounter()
    {
        $this->form_validation->set_rules('tLevel', '', 'trim|required');
        if ($this->form_validation->run() == false) {
            $msg['error']   = true;
            $msg['message'] = validation_errors();
            echo json_encode($msg);die;
        }
        $tLevel         = $this->secure->dec($this->input->post('tLevel', TRUE));
        $tCounter        = $this->input->post('tCounter');
        $isCounter       = "";
        if(!empty($tCounter)){
            foreach($tCounter as $a){
                $off_idx    = $this->secure->dec($a);
                $isCounter.=trim($off_idx).",";
            }
            $isCounter = substr($isCounter,0,strlen($isCounter)-1); 
        }
        $updateData = [
            'counter_viewer' => $isCounter
        ];
        $updateLevel = $this->mLevel->updateAccessLevel($tLevel,$updateData);
        if($updateLevel){
            $msg['success'] = true;
            $msg['text']    = 'Update access counter success.';
            echo json_encode($msg);die;
        }else{
            $msg['status']  = true;
            $msg['text']    = 'Update access counter failed.';
            echo json_encode($msg);die;
        }
    }

    public function getAccessSettings()
    {
        $level_id    = $this->secure->dec($this->input->post('level_id', TRUE));
        $checkOff   = $this->mAccess->readtable('app_level_access', '',array('level_id' => $level_id),'','','')->row_array();
        if(!$checkOff){
            $tr = "
            <tr>
                <td colspan='2' class='text-center'><span class='text-center text-danger'>No Data</span></td>
            </tr> ";
            $gettr[] = $tr;
            $tr2 = "
            <tr>
                <td colspan='2' class='text-center'><span class='text-center text-danger'>No Data</span></td>
            </tr> ";
            $gettr2[] = $tr2;
        }else{
            $enabler = $checkOff['status_assign_driver'];
            $enabler2 = $checkOff['status_open_close_bo'];
            if ($enabler==1):
                $chekedHub1="checked='checked'";
                $chekedHub2='';
            else:
                $chekedHub1='';
                $chekedHub2="checked='checked'";
            endif;
            if ($enabler2==1):
                $chekedBo1="checked='checked'";
                $chekedBo2='';
            else:
                $chekedBo1='';
                $chekedBo2="checked='checked'";
            endif;
            $tr = "
            <tr>
                <td class='text-center'><div class='form-check form-check-inline'>
                <input class='form-check-input' type='radio' name='tStatus' id='inlineRadio1' value='1' $chekedHub1>
                <label class='form-check-label' for='inlineRadio1'>Enable</label>
              </div>
              <div class='form-check form-check-inline'>
                <input class='form-check-input' type='radio' name='tStatus' id='inlineRadio2' value='0' $chekedHub2>
                <label class='form-check-label' for='inlineRadio2'>Disable</label>
              </div></td>
            </tr> ";
            $tr2 = "
            <tr>
                <td class='text-center'><div class='form-check form-check-inline'>
                <input class='form-check-input' type='radio' name='tStatusBo' id='inlineRadio3' value='1' $chekedBo1>
                <label class='form-check-label' for='inlineRadio3'>Enable</label>
              </div>
              <div class='form-check form-check-inline'>
                <input class='form-check-input' type='radio' name='tStatusBo' id='inlineRadio4' value='0' $chekedBo2>
                <label class='form-check-label' for='inlineRadio4'>Disable</label>
              </div></td>
            </tr> ";
            $gettr[] = $tr;
            $gettr2[] = $tr2;
        }
        $msg['res_tr']  = $gettr;
        $msg['res_tr2'] = $gettr2;
        echo json_encode($msg);
    }
    public function fActAccessSettings()
    {
        $this->form_validation->set_rules('tLevel', '', 'trim|required');
        $this->form_validation->set_rules('tStatus', '', 'trim|required');
        $this->form_validation->set_rules('tStatusBo', '', 'trim|required');
        if ($this->form_validation->run() == false) {
            $msg['error']   = true;
            $msg['message'] = validation_errors();
            echo json_encode($msg);die;
        }
        $tLevel   = $this->secure->dec($this->input->post('tLevel', TRUE));
        $tStatus    = $this->input->post('tStatus');
        $tStatusBo  = $this->input->post('tStatusBo');
        $updateData = [
            'status_assign_driver' => $tStatus,
            'status_open_close_bo' => $tStatusBo
        ];
        $updateLevel = $this->mLevel->updateAccessSettings($tLevel,$updateData);
        if($updateLevel){
            $msg['success'] = true;
            $msg['text']    = 'Update Assign Driver success.';
            echo json_encode($msg);die;
        }else{
            $msg['status']  = true;
            $msg['text']    = 'Update Assign Driver failed.';
            echo json_encode($msg);die;
        }
    }
}
/* End of file user.php */
/* Location: ./application/controllers/user.php */
