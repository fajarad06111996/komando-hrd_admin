<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Menuapps extends AUTH_Controller

{

  public function __construct()

  {

    parent::__construct();

    $this->load->model('M_menu_apps', 'mMenuApps');

    $this->load->model('ModelGenId');

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

      $data['write']  = '<a href="javascript:void(0);" id="btnAdd" class="pull-right text-white small" title="Add New Menu Apps" data-placement="right" data-popup="tooltip"><i class="icon-plus-circle2"></i></a>';

      $data['lock']   = 1;

    }else{

      $data['write']  = '<i class="icon-lock pull-right pt-1 " title="Locked New Menu Apps" data-placement="right" data-popup="tooltip"></i>';

      $data['lock']   = 0;

    }



    $ses = array(

        'csrf_token' => hash('sha1',time())

    );

    $this->session->set_userdata($ses);

    $data['filename']   = $this->filename;

    $data["link"] = $this->link;

    $this->template->views('pengaturan/v_menu_apps', $data);

  }



  // CONTOH LAIN CONTROLLER2 POST DATATABLE SERVERSIDE

  function get_ajax() {

    $csrfName = $this->input->post('CSRFToken');

    $csrfToken  = $this->session->csrf_token;

    if($csrfToken != $csrfName){

      $list = null;

    }else{

        $list = $this->mMenuApps->get_datatables();

    }

    $data = array();

    $no = @$_POST['start'];
    $order = @$_POST['order'];
    // var_dump($order);
    // die();
    foreach ($list as $item) {

        $idx = $this->secure->enc($item->idx);
        $info = "<h5><a href='javascript:void(0);' id='$idx' data='$item->menu_name' code='1' class='bInfo text-center badge badge-primary' data-popup='tooltip' title='Payment Info' data-placement='right'><i class='icon-price-tag2'></i></a></h5>";



      if(!empty($this->security_function->permissions($this->filename . "-w"))){
      }else{
      }
      if(!empty($this->security_function->permissions($this->filename . "-c"))){

        $change = "<h5><a href='javascript:void(0);' id='$idx' class='bEdit text-center badge badge-info' data-popup='tooltip' title='Edit Menu Apps' data-placement='right'><i class='icon-pencil5'></i></a></h5>";

        if($item->status == 1)

        {

          $statusU = "<h5><a href='javascript:void(0);' id='$idx' data='$item->menu_name' code='1' class='bStatus text-center badge badge-success' data-popup='tooltip' title='Change Status' data-placement='right'>Aktif</a></h5>";

          // $statusU = '<span class="badge badge-success">Aktif</span>';

        } else {

          $statusU = "<h5><a href='javascript:void(0);' id='$idx' data='$item->menu_name' code='0' class='bStatus text-center badge badge-danger' data-popup='tooltip' title='Change Status' data-placement='right'>Non Aktif</a></h5>";

          // $statusU = '<span class="badge badge-danger">Non Aktif</span>';

        }

      }else{

        $change = '<h5><span class="badge badge-warning"><i class="icon-lock" data-popup="tooltip" title="Locked Edit Payment" data-placement="right"></i></span></h5>';

        if($item->status == 1)

        {

          $statusU = "<span class='text-center badge badge-success'>Aktif</span>";

        }else{

          $statusU = "<span class='text-center badge badge-danger'>Non Aktif</span>";

        }

      }



      if(!empty($this->security_function->permissions($this->filename . "-x"))){

        $execute  = "<h5><a href='javascript:void(0);' id='$idx' data='$item->menu_name' class='bDelete text-center badge badge-danger' data-popup='tooltip' title='Delete Menu Apps' data-placement='right'><i class='icon-bin'></i></a></h5>";

      }else{

        $execute  = '<h5><span class="badge badge-warning"><i class="icon-lock" data-popup="tooltip" title="Locked Delete Payment" data-placement="right"></i></span></h5>';

      }



      $no++;

      $row = array();

      $row[] = $no.".";

      $row[] = $item->menu_code;
      
      $row[] = $item->menu_name;

      $row[] = $item->image_menu==""?"<img src='".base_url()."/assets/images/no_image.png' width='100px' class='bImage' caption='$item->menu_name' data-popup='tooltip' title='Click to preview' data-placement='right'>":"<img src='$item->image_menu' width='100px' class='bImage' caption='$item->menu_name' data-popup='tooltip' title='Click to preview' data-placement='right'>";
      
      $row[] = $item->remarks;

      $row[] = $statusU;

      $row[] = date('d-F-Y',strtotime($item->created_on))."<br>#<span class='text-info'>$item->user_id</span>";

      // add html for action

      $row[] = "<div class='btn-group'>$change$execute</div>";

      $data[] = $row;

    }

    $output = array(

      "draw"            => @$_POST['draw'],

      "recordsTotal"    => $this->mMenuApps->count_all(),

      "recordsFiltered" => $this->mMenuApps->count_filtered(),

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

  public function addMenuapps()

  {
    // $id = $this->secure->dec($idx);
    $msg = array('error'  => false);

    $msg = array('status' => false);
    
    $this->form_validation->set_rules('menu_code', 'Menu Code', 'trim|required|is_unique[setting_menu_appsclient.menu_code]',

        [

            'is_unique' => 'Menu Code is allready used',

            'required' => 'Menu Code is required'

        ]

    );

    $this->form_validation->set_rules('menu_name', 'Menu Name', 'trim|required');

    $this->form_validation->set_rules('remarks', 'Remarks', 'trim|required');

    // $this->form_validation->set_rules('tUrutan', 'No Urut', 'trim|required');



    if($this->form_validation->run() == false){

      $msg['error']   = true;

      $msg['message'] = validation_errors();

    }else{
        $menu_code        = strtoupper(trim($this->input->post('menu_code', TRUE)));
        $menu_name        = strtoupper(trim($this->input->post('menu_name', TRUE)));
        $remarks          = trim($this->input->post('remarks', TRUE));
        $url_image        = htmlentities($this->input->post('url_image'));
        $temp_image       = $this->input->post('temp_image', TRUE);
        $status           = trim($this->input->post('tStatus'));

        $csrfName = $this->input->post('csrf');

        $csrfToken  = $this->session->csrf_token;

        if($csrfToken != $csrfName){

            $msg['status']   = true;

            $msg['text']  = "Token expired,<br>refresh page and try again.";

        }else{

          if($temp_image == 1){

              $data = [
                  'menu_code'   => $menu_code,
                  'menu_name'   => $menu_name,
      
                  'remarks'     => $remarks,
      
                  'image_menu'  => $url_image,
      
                  'status'      => $status,
      
                  'created_by'  => $this->idx,
      
                  'created_on'  => date("Y-m-d H:i:s")
              ];
          }else{
              $data = [
                'menu_code'   => $menu_code,
                'menu_name'   => $menu_name,
      
                'remarks'     => $remarks,
    
                'status'      => $status,
    
                'created_by'  => $this->idx,
    
                'created_on'  => date("Y-m-d H:i:s")
              ];
          }
          
        //   var_dump('<pre>');
        //   var_dump($data);
        //   die();
          $result = $this->mMenuApps->insertMenuapps($data);
  
          if($result == 1)
  
          {
              $msg['type']    = 'add';
  
              $msg['ledit']   = $this->link;
  
              $msg['success'] = true;
              $msg['text']    = "Data Payment insert successfully.";
  
          }else{
  
              //show an error page or error message about the failed insert
  
              $msg['status']    = true;
              $msg['text']      = "Error connection,<br>Please try again later.";
  
          }
        }
    }

    echo json_encode($msg);

  }

  public function checkResponse()

  {

    $cek = $this->mMenuApps->getMenuApps($_POST['resname']);

      if($cek > 0) {

          echo '0';

      } else {

          echo '1';

      }

  }

  public function editMenuapps()
  {
    $id = $this->secure->dec($this->input->get('id'));
    $result = $this->mMenuApps->editMenuapps($id);
    echo json_encode($result);
  }

  public function updateMenuapps($idx)
  {
    $id = $this->secure->dec($idx);
    $msg = array('error'  => false);

    $msg = array('status' => false);

    $this->form_validation->set_rules('menu_name', 'Menu Name', 'trim|required');

    $this->form_validation->set_rules('remarks', 'Remarks', 'trim|required');

    // $this->form_validation->set_rules('tUrutan', 'No Urut', 'trim|required');



    if($this->form_validation->run() == false){

      $msg['error']   = true;

      $msg['message'] = validation_errors();

    }else{
      $menu_name        = strtoupper(trim($this->input->post('menu_name', TRUE)));
      $remarks          = trim($this->input->post('remarks', TRUE));
      $url_image        = htmlentities($this->input->post('url_image'));
      $temp_image       = $this->input->post('temp_image', TRUE);
      $status           = trim($this->input->post('tStatus'));

      $csrfName = $this->input->post('csrf');

      $csrfToken  = $this->session->csrf_token;

      if($csrfToken != $csrfName){

          $msg['status']   = true;

          $msg['text']      = "Token expired,<br>refresh page and try again.";

      }else{
        if($temp_image == 1){

          $data = [

            'menu_name'   => $menu_name,
      
            'remarks'     => $remarks,

            'image_menu'  => $url_image,

            'status'      => $status,

            'created_by'  => $this->idx,

            'created_on'  => date("Y-m-d H:i:s")
          ];
        }else{
            $data = [

              'menu_name'   => $menu_name,
      
              'remarks'     => $remarks,
  
              'status'      => $status,
  
              'created_by'  => $this->idx,
  
              'created_on'  => date("Y-m-d H:i:s")
            ];
        }

        $result = $this->mMenuApps->updateMenuapps($id,$data);

        if($result == 1)

        {

            $msg['type']    = 'update';

            $msg['ledit']   = $this->link;

            $msg['success'] = true;
            $msg['text']    = "Data Payment update successfully.";

        }else{

            //show an error page or error message about the failed insert

            $msg['status']   = true;
            $msg['text']    = "Error connection,<br>Please try again later.";

        }
      }
    }
      echo json_encode($msg);
  }

  public function deleteMenuapps()
  {
    $id = $this->secure->dec($this->input->get('id'));
    $result           = $this->mMenuApps->deleteMenuapps($id);

    if ($result == true) {

        $msg['success'] = true;
        $msg['text']    = "Menu Apps deleted successfully.";

    }else{

        $msg['status']  = true;
        $msg['text']    = "Error connection,<br>Please try again later.";
    }

    echo json_encode($msg);

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
        $id_payment = $this->secure->dec($this->input->post('tId'));

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



          $cek = $this->mMenuApps->changeStatusMenuapps($data, $id_payment);

          if($cek == true)

          {

              $msg['type']    = 'change';

              $msg['success'] = true;
              $msg['text']    = "Status updated successfully.";

          }else{

              $msg['status']  = true;
              $msg['text']    = "Error connection,<br>Please try again later.";

          }
        }
        echo json_encode($msg);
    }



    public function createAsUser()

    {

        $id_login = $this->idx;

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

}



/* End of file Payment.php */

/* Location: ./application/controllers/Payment.php */

