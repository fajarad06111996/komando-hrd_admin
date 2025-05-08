<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Menusubchild extends AUTH_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('M_auth');
		$this->load->library('security_function');
    $this->link		  = site_url('settings/').strtolower(get_class($this));
    $this->filename	= strtolower(get_class($this));
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
      $data['write']  = '<a href="javascript:void(0)" id="btnAdd" class="pull-right text-white small" title="Add New Child Sub Modul" data-placement="right" data-popup="tooltip"><i class="icon-plus-circle2"></i></a>';
      $data['lock']   = 1;
    }else{
      $data['write']  = '<i class="icon-lock pull-right pt-1 " title="Locked New Child Sub Modul" data-placement="right" data-popup="tooltip"></i>';
      $data['lock']   = 0;
    }

    $where = array(
      'menu_parent_sub' => 2,
      'menu_parent_active' => 1
    );

    $data['link']       = $this->link;
    $data['filename']   = $this->filename;
    $data['result']     = $this->mMenu->getSubChild()->result();
    $data['parent']     = $this->mAccess->getData('app_menu','menu_alias','ASC','',$where)->result();
    // var_dump($data['parent']);exit();
    $this->template->views('pengaturan/v_menusubchild', $data);
  }

  // CONTOH LAIN CONTROLLER2 POST DATATABLE SERVERSIDE
  function get_ajax() {
    $csrfName = $this->input->post('CSRFToken');
    $csrfToken  = $this->session->csrf_token;
    // if($csrfToken != $csrfName){
    //   $list = null;
    // }else{
    // }
    $list = $this->mMenu->get_datatables(3);
    $data = array();
    $no = @$_POST['start'];
    foreach ($list as $item) {

      if($item->menu_access==1){
        $access	='<span class="badge badge-success">Publish</span>';
      }else{
        $access	='<span class="badge badge-danger">No Publish</span>';
      }

      // if(!empty($this->security_function->permissions($this->filename . "-w"))){
      //   if($item->type == 'CORPORATE'){
      //     if($item->status_api == 0)
      //     {
      //         $CrUser = "<h5><a href='javascript:void(0);' id='$item->client_id' data='$item->client_name' code='$item->client_code' class='bCreate text-center badge badge-dark' data-popup='tooltip' title='Created API' data-placement='right'><i class='fa fa-plus-square fa-lg'></i></a></h5>";
      //     }else{
      //         $CrUser = "<h5><a href='javascript:void(0);' id='$item->client_id' data='$item->client_name' code='$item->client_code' key='$item->key' class='bSend text-center badge badge-dark' data-popup='tooltip' title='Send API' data-placement='right'><i class='fa fa-paper-plane fa-lg'></i></a></h5>";
      //     }
      //   }else{
      //     $CrUser ="";
      //   }
      //   if($item->status_user == 1){
      //     $addUser = "";
      //   }else{
      //     $addUser = "<h5><a href='javascript:void(0);' id='$item->idx' data='$item->client_name' code='$item->client_code' class='bCreate2 text-center badge badge-success' data-popup='tooltip' title='Create as User' data-placement='right'><i class='fa fa-user fa-lg'></i></a></h5>";
      //   }
      // }else{
      //   $CrUser = "";
      //   $addUser = "";
      // }

      if(!empty($this->security_function->permissions($this->filename . "-c"))){
        $change = "<h5><a href='javascript:void(0);' id='$item->menu_id' class='bEdit badge badge-info' data-popup='tooltip' title='Edit Child Sub Modul' data-placement='right'><i class='fa fa-edit fa-lg'></i></a></h5>";
      }else{
        $change = '<h5><span class="badge badge-warning"><i class="fa fa-lock fa-lg mr-2" data-popup="tooltip" title="Locked Edit Access User" data-placement="right"></i></span></h5>';
      }

      if(!empty($this->security_function->permissions($this->filename . "-x"))){
        $execute  = "<h5><a href='javascript:void(0);' id='$item->menu_id' data='$item->menu_title' class='bDelete text-center badge badge-danger' data-popup='tooltip' title='Delete Access User' data-placement='right'><i class='fa fa-trash-o fa-lg'></i></a></h5>";
      }else{
        $execute  = '<h5><span class="badge badge-warning"><i class="mi-lock font-weight-black" data-popup="tooltip" title="Locked Delete Access User" data-placement="right"></i></span></h5>';
      }

      $no++;
      $row = array();
      $row[] = $no.".";
      $row[] = $item->mTitle;
      $row[] = $item->menu_title;
      $row[] = $item->menu_alias;
      $row[] = $access;
      $row[] = $item->menu_urutan;
      $row[] = $item->menu_icon;
      // add html for action
      $row[] = "<div class='btn-group'>$change$execute</div>";
      $data[] = $row;
    }
    $output = array(
      "draw"            => @$_POST['draw'],
      "recordsTotal"    => $this->mMenu->count_all(3),
      "recordsFiltered" => $this->mMenu->count_filtered(3),
      "data"            => $data,
    );
    // output to json format
    echo json_encode($output);
  }

  public function addMenuSubChild()
  {
    $msg = array('error' => false);
    $this->form_validation->set_rules('tMenu', 'Nama Menu', 'trim|required');
    $this->form_validation->set_rules('tSubModul', 'Nama Sub Modul', 'trim|required');
    $this->form_validation->set_rules('tSubAlias', 'Nama Sub Alias', 'trim|required');
    $this->form_validation->set_rules('tUrutan', 'No Urut', 'trim|required');

    if($this->form_validation->run() == false){
      $msg['error']   = true;
      $msg['message'] = validation_errors();
    }else{
      $field = array(
        'menu_parent_id'      => $this->input->post('tMenu', TRUE),
        'menu_title'          => strtoupper($this->input->post('tSubModul', TRUE)),
        'menu_alias'          => strtoupper($this->input->post('tSubAlias', TRUE)),
        'menu_urutan'         => $this->input->post('tUrutan', TRUE),
        'menu_icon'           => $this->input->post('tIcon', TRUE),
        'menu_access'         => !empty($this->input->post('tAccess'))?$this->input->post('tAccess'):'0',
        'menu_parent_active'  => 0,
        'menu_parent_sub'     => 3
      );
      $cekOnline = $this->M_auth->onlineUpdateAll($this->username);
      if($cekOnline == false){
        $msg['text']      = 'Error update username online to offline';
        $msg['success']   = false;
      }else{
        $this->mAccess->insertData($field, 'app_menu');
        $msg['success'] = true;
        $msg['type']    = 'add';
      }
    }
    echo json_encode($msg);
  }
   
  public function editMenuChild()
  {
    $result = $this->mMenu->editMenuChild();
    echo json_encode($result);
  }

  public function updateMenuSubChild($id="")
  {
    $msg = array('error' => false);
    $this->form_validation->set_rules('tMenu', 'Nama Menu', 'trim|required');
    $this->form_validation->set_rules('tSubModul', 'Nama Sub Modul', 'trim|required');
    $this->form_validation->set_rules('tSubAlias', 'Nama Sub Alias', 'trim|required');
    $this->form_validation->set_rules('tUrutan', 'No Urut', 'trim|required');

    if($this->form_validation->run() == false) { 
      // $msg['error']   = true;
      // $msg['message'] = validation_errors();
    }else{
      $where  = array(
        'menu_id' => $id
      );
      $field = array(
        'menu_parent_id'      => $this->input->post('tMenu', TRUE),
        'menu_title'          => strtoupper($this->input->post('tSubModul', TRUE)),
        'menu_alias'          => strtoupper($this->input->post('tSubAlias', TRUE)),
        'menu_urutan'         => $this->input->post('tUrutan', TRUE),
        'menu_icon'           => $this->input->post('tIcon', TRUE),
        'menu_access'         => !empty($this->input->post('tAccess'))?$this->input->post('tAccess'):'0',
        'menu_parent_active'  => 0,
        'menu_parent_sub'     => 3
      );
      $cekOnline = $this->M_auth->onlineUpdateAll($this->username);
      if($cekOnline == false){
        $msg['text']      = 'Error update username online to offline';
        $msg['success']   = false;
      }else{
        $this->mAccess->updateData($where, $field, 'app_menu');

        $msg['success'] = true;
        $msg['type'] = 'update';
      }
      echo json_encode($msg);
    }
  }

  public function deleteMenuSubChild()
  {
    $id = $this->input->get('id');
    $cekhakaccess    = $this->mMenu->cekHakAccess($id);
    $gethakaccess    = $this->mMenu->getHakSubAccess();
    // echo '<prev>';
    // var_dump($gethakaccess);
    // echo '</prev>';
    $a = "";
    foreach ($gethakaccess as $g){
      // var_dump($g->level_name);
      $a.=trim($g->access_submenu_id).",";
    }
    $ex = explode(",",$a);
    if(in_array($id,$ex)) {
      $msg['text']   = 'Modul masih mempunyai hak akses aktif';
      $msg['success']   = false;
    }else{
      $cekOnline = $this->M_auth->onlineUpdateAll($this->username);
      if($cekOnline == false){
        $msg['text']      = 'Error update username online to offline';
        $msg['success']   = false;
      }else{
        $result           = $this->mMenu->deleteMenuParents();
        if ($result) {
          $msg['success'] = true;
        }else{
          $msg['success']   = false;
        }
      }
    }
    echo json_encode($msg);
  }

}

/* End of file user.php */
/* Location: ./application/controllers/user.php */
