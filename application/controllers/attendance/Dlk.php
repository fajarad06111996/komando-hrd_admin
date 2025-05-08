<?php
defined('BASEPATH') or exit('No direct script access allowed');
require "vendor/autoload.php";
use Nullix\CryptoJsAes\CryptoJsAes;
class Dlk extends AUTH_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('attendance/M_dlk', 'mDlk');
        $this->load->model('ModelGenId');
        $this->load->library('security_function');
        $this->link	    = site_url('attendance/').strtolower(get_class($this));
        $this->filename = strtolower(get_class($this));
        $this->office   = $this->secure->dec($this->session->userdata('JToffice_id'));
        $this->officeCode   = $this->session->userdata('JToffice_code');
        $this->tOffice  = $this->secure->dec($this->session->userdata('JToffice_type'));
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
            $data['write']  = '<a href="javascript:void(0);" id="btnAdd" class="pull-right text-white small" title="Tambah Organisasi" data-placement="right" data-popup="tooltip"><i class="icon-plus-circle2"></i></a>';
            $data['lock']   = 1;
        }else{
            $data['write']  = '<i class="icon-lock pull-right pt-1 " title="Tambah Organisasi Terkunci" data-placement="right" data-popup="tooltip"></i>';
            $data['lock']   = 0;
        }
        // $data['employee']   = $this->mCal->getEmployee();
        $data['filename']   = $this->filename;
        $data['judul']      = 'Dinas Luar Kota';
        $data['page']       = 'Dinas Luar Kota';
        $data['employee']   = $this->mDlk->getEmployee();
        $data["link"]       = $this->link;
        // var_dump('<pre>');var_dump($this->session->csrf_token);die;
        $params             = '{"base_url": "'.base_url().'", "link": "'.$this->link.'", "csrf": "'.$this->session->csrf_token.'", "permW": "'.$this->security_function->permissions($this->filename . "-w").'", "permC": "'.$this->security_function->permissions($this->filename . "-c").'", "permX": "'.$this->security_function->permissions($this->filename . "-x").'", "key_firebase": '.key_firebase().'}';
        // encrypt
        $encrypted          = CryptoJsAes::encrypt($params, $this->enkey);
        $data['params']     = $encrypted;
        $this->template->views('attendance/v_dlk', $data);
    }

    function get_tb_dlk()
    {
        $dlkidx   = $this->input->post('dlkid');
        $csrfToken  = validate_csrf_token();
        if($csrfToken == false){
            $list = array();
            $draw = 1;
            $c_all = 0;
            $f_all = 0;
        }else{
            if(empty($dlkidx)){
                $list = array();
                $draw = 1;
                $c_all = 0;
                $f_all = 0;
            }else{
                $dlkid = $this->secure->dec($dlkidx);
                $list = $this->mDlk->get_datatables($dlkid);
                $draw = @$_POST['draw'];
                $c_all = (int)$this->mDlk->count_all($dlkid);
                $f_all = (int)$this->mDlk->count_filtered($dlkid);
            }
        }
        // var_dump('<pre>');var_dump($list);die;
        $data = array();
        $no = @$_POST['start'];
        foreach ($list as $item) {
            $idx        = $this->secure->enc($item->idx);
            $dlk_idx    = $this->secure->enc($item->dlk_idx);
            $dlk_date   = date('d-M-Y', strtotime($item->dlk_date));
            if(!empty($this->security_function->permissions($this->filename . "-w"))){
            }else{
            }
            if(!empty($this->security_function->permissions($this->filename . "-c"))){
                if($item->status_confirm==1){
                    $change = "";
                    $confirm = "<h5><a href='javascript:void(0);' class='text-center badge badge-success bConfirmed' data-popup='tooltip' title='Confirmed' data-placement='left'><i class='icon-checkmark'></i></a></h5>";
                }else{
                    $confirm = "<h5><a href='javascript:void(0);' id='$idx' data='$item->employee_name' dlkid='$dlk_idx' dlkdate='$dlk_date' class='bPublish text-center badge badge-warning' data-popup='tooltip' title='Konfirmasi Dlk' data-placement='left'><i class='icon-exclamation'></i></a></h5>";
                    $change = "<h5 class='m-0'><a href='javascript:void(0);' id='$idx' dlkid='$dlk_idx' dlkdate='$dlk_date' class='bEdit text-center badge badge-info' data-popup='tooltip' title='Edit Dlk' data-placement='left'><i class='icon-pencil5'></i></a></h5>";
                }
                $setup = "<h5 class='m-0'><a href='javascript:void(0);' id='$idx' class='bSetup text-center badge badge-primary' data-popup='tooltip' title='Toleransi Kehadiran' data-placement='left'><i class='icon-folder-search'></i></a></h5>";
                // if($item->status == 1)
                // {
                //     $statusU = "<h5><a href='javascript:void(0);' id='$idx' data='$item->employee_name' code='1' class='bStatus text-center badge badge-success' data-popup='tooltip' title='Ubah Status' data-placement='right'>Aktif</a></h5>";
                // // $statusU = '<span class="badge badge-success">Aktif</span>';
                // } else {
                //     $statusU = "<h5><a href='javascript:void(0);' id='$idx' data='$item->employee_name' code='0' class='bStatus text-center badge badge-danger' data-popup='tooltip' title='Ubah Status' data-placement='right'>Non Aktif</a></h5>";
                // // $statusU = '<span class="badge badge-danger">Non Aktif</span>';
                // }
            }else{
                if($item->status_confirm==1){
                    $change = "<h5><a href='javascript:void(0);' class='text-center badge badge-success bConfirmed' data-popup='tooltip' title='Confirmed' data-placement='left'><i class='icon-checkmark'></i></a></h5>";
                }else{
                    $change = '<h5 class="m-0"><span class="badge badge-warning bLockStatus"><i class="icon-lock" data-popup="tooltip" title="Locked Edit Dlk" data-placement="left"></i></span></h5>';
                }
                $confirm = "";
                $setup = '<h5 class="m-0"><span class="badge badge-warning bLockStatus"><i class="icon-lock" data-popup="tooltip" title="Locked Toleransi Kehadiran" data-placement="left"></i></span></h5>';
                // if($item->status == 1)
                // {
                //     $statusU = "<span class='text-center badge badge-success'>Aktif</span>";
                // }else{
                //     $statusU = "<span class='text-center badge badge-danger'>Non Aktif</span>";
                // }
            }
            if(!empty($this->security_function->permissions($this->filename . "-x"))){
                if($item->status_confirm==0){
                    $execute  = "<h5 class='m-0'><a href='javascript:void(0);' id='$idx' data='$item->employee_name' class='bDelete text-center badge badge-danger' data-popup='tooltip' title='Hapus Jabatan' data-placement='right'><i class='icon-bin'></i></a></h5>";
                }else{
                    $execute  = "";
                }
            }else{
                if($item->status_confirm==0){
                    $execute  = '<h5 class="m-0"><span class="badge badge-warning"><i class="icon-lock" data-popup="tooltip" title="Hapus Jabatan Terkunci" data-placement="right"></i></span></h5>';
                }else{
                    $execute  = "";
                }
            }
            $img = empty($item->url_prof)?base_url()."/assets/images/no_image.png":$item->url_prof;
            $no++;
            $row = array();
            $row[] = $no.".";
            $row[] = $item->employee_name;
            $row[] = number_format($item->value);
            $row[] = $item->description;
            $row[] = "<a href='$img' data-fancybox data-caption='$item->employee_name'><img src='$img' width='100px' class='bImage' caption='$item->employee_name' data-popup='tooltip' title='Click to preview' data-placement='right'>";
            $row[] = "<div class='btn-group'>$confirm&nbsp;$change&nbsp;$execute</div>";
            // add html for action
            $data[] = $row;
        }
        $output = array(
            "draw"            => $draw,
            "recordsTotal"    => $c_all,
            "recordsFiltered" => $f_all,
            "data"            => $data
        );
        // output to json format
        echo json_encode($output);
    }

    public function getDlk()
    {
        $month = $this->input->post('month');
        $year = $this->input->post('year');
        $data = $this->mDlk->getCalendarDlk($month, $year);
        $data_arr=array();
        if($data){
            foreach($data as $d){
                $e = [
                    'id' => $this->secure->enc($d->idx),
                    'title' => $d->dlk_code,
                    'start' => date("Y-m-d", strtotime($d->dlk_date)),
                    'end' => date("Y-m-d", strtotime($d->dlk_date))
                ];

                $data_arr[] = $e;
            }
            $msg['status']  = true;
            $msg['msg']     = 'successfully!';
            $msg['data']    = $data_arr;
            echo json_encode($data_arr);die;
        }else{
            $e = [
                'id' => null,
                'title' => null,
                'start' => null,
                'end' => null
            ];
            $data_arr[] = $e;
            $msg['status']  = false;
            $msg['msg']     = 'error!';
            $msg['data']    = $data_arr;
            echo json_encode($msg);die;
        }
    }

    public function checkDlkByDate()
    {
        $dateCalendar = $this->input->get('now');
        if(empty($dateCalendar)){
            $msg['status']  = false;
            $msg['msg']     = 'Tanggal Kalender wajib di isi.';
            echo json_encode($msg);die;
        }

        $cek = $this->mDlk->checkDlkByDate($dateCalendar);
        if($cek == 0){
            $msg['status']  = true;
            $msg['msg']     = 'Oke.';
            echo json_encode($msg);die;
        }else{
            $msg['status']  = false;
            $msg['msg']     = 'Data exsist.';
            echo json_encode($msg);die;
        }
    }

    public function save_dlk_header()
    {
        $this->form_validation->set_rules('dlk_date', 'Tanggal dlk', 'trim|required',[
            'required' => 'Tanggal dlk wajib di isi.'
        ]);
        $this->form_validation->set_rules('description', 'Deskripsi', 'trim|required',[
            'required' => 'Deskripsi wajib di isi.'
        ]);
        if ($this->form_validation->run() == false) {
            $msg['status']  = false;
            $msg['msg']     = validation_errors();
            echo json_encode($msg);die;
        }

        // $dlk_id   = $this->ModelGenId->genIdUnlimited('DLKID', $this->idx);
        $dlk_no   = $this->ModelGenId->genIdYear('DLKID', $this->idx);
        $dlk_code = $dlk_no.'/DLK/'.$this->officeCode.'/'.numberToRoman(date('m')).'/'.date('Y');
        $dlk_date = filter_var(trim($this->input->post('dlk_date')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $description    = filter_var(trim($this->input->post('description')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

        $data = [
            'dlk_code'          => $dlk_code,
            'dlk_date'          => $dlk_date,
            'description'       => $description,
            'company_idx'       => $this->office,
            'created_by'        => $this->idx,
            'created_on'        => date('Y-m-d'),
        ];

        $result = $this->mDlk->insertEvent($data);
        if($result==1){
            $msg['status']  = true;
            $msg['msg']     = 'Tambah dlk berhasil.';
            echo json_encode($msg);die;
        }else{
            $msg['status']  = false;
            $msg['msg']     = 'Tambah dlk gagal.';
            echo json_encode($msg);die;
        }
    }

    public function get_dlk()
    {
        $id = $this->input->get('id');
        $idx = $this->secure->dec($id);
        $data = $this->mDlk->getDlk($idx);
        if($data){
            $res = [
                'status' => 1,
                'dlk_idx' => $this->secure->enc((int)$data['idx']),
                'description' => $data['description'],
                'start' => $data['dlk_date'],
                'end' => $data['dlk_date']
            ];
        }else{
            $res = [
                'status' => 1,
                'dlk_idx' => null,
                'description' => null,
                'start' => null,
                'end' => null
            ];
        }
        echo json_encode($res);die;
    }

    public function get_dlk_detail()
    {
        $id = $this->input->get('id');
        $idx = $this->secure->dec($id);
        $data = $this->mDlk->getDlkDetail($idx);
        if(empty($data)){
            $result = null;
        }else{
            $add = [
                'employee_id_en' => $this->secure->enc($data['employee_id'])
            ];
    
            $result = array_merge($data, $add);
        }
        echo json_encode($result);die;
    }

    public function update_event($idx)
    {
        $id = $this->secure->dec($idx);
        $this->form_validation->set_rules('event_name', 'Event Name', 'trim|required',[
            'required' => 'Event Name wajib di isi.'
        ]);
        $this->form_validation->set_rules('event_date', 'Event date', 'trim|required',[
            'required' => 'Event date wajib di isi.'
        ]);
        // $this->form_validation->set_rules('event_start_date', 'Event start', 'trim|required',[
        //     'required' => 'Event start wajib di isi.'
        // ]);
        // $this->form_validation->set_rules('event_end_date', 'Event end', 'trim|required',[
        //     'required' => 'Event end wajib di isi.'
        // ]);
        if ($this->form_validation->run() == false) {
            $msg['status']  = false;
            $msg['msg']     = validation_errors();
            echo json_encode($msg);die;
        }

        $event_name         = $this->input->post('event_name');
        $event_date         = $this->input->post('event_date');
        // $event_start_date   = $this->input->post('event_start_date');
        // $event_end_date     = $this->input->post('event_end_date');

        $data = [
            'event_name' => $event_name,
            'event_date' => $event_date,
            // 'event_start_date' => $event_start_date,
            // 'event_end_date' => $event_end_date,
            'modified_by' => $this->idx,
            'modified_on' => date('Y-m-d'),
        ];

        $result = $this->mDlk->updateEvent($data, $id);
        if($result==1){
            $msg['status']  = true;
            $msg['msg']     = 'Ubah event berhasil.';
            echo json_encode($msg);die;
        }else{
            $msg['status']  = false;
            $msg['msg']     = 'Ubah event gagal.';
            echo json_encode($msg);die;
        }
    }

    public function add_dlk_detail()
    {
        $this->form_validation->set_rules('csrfsession', 'Token Page', 'trim|required',[
            'required' => 'Token Page wajib di isi.'
        ]);
        $this->form_validation->set_rules('dlk_idx', 'Dlk ID', 'trim|required',[
            'required' => 'Dlk ID wajib di isi.'
        ]);
        $this->form_validation->set_rules('dlk_date', 'Tanggal Dlk', 'trim|required',[
            'required' => 'Tanggal Dlk wajib di isi.'
        ]);
        $this->form_validation->set_rules('employee_id', 'Karyawan', 'trim|required',[
            'required' => 'Karyawan wajib di pilih.'
        ]);
        $this->form_validation->set_rules('value', 'Nilai Dlk', 'trim|required',[
            'required' => 'Nilai Dlk wajib di isi.'
        ]);
        $this->form_validation->set_rules('description', 'Deskripsi', 'trim|required',[
            'required' => 'Deskripsi wajib di isi.'
        ]);
        if ($this->form_validation->run() == false) {
            $msg['status']  = false;
            $msg['msg']     = validation_errors();
            echo json_encode($msg);die;
        }

        $csrfToken = validate_csrf_token();
        if($csrfToken==false){
            $msg['status']  = false;
            $msg['msg']     = 'Invalid Token.';
            echo json_encode($msg);die;
        }

        $dlk_id_enc     = filter_var(trim($this->input->post('dlk_idx')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $dlk_datex      = filter_var(trim($this->input->post('dlk_date')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $emp_id_enc     = filter_var(trim($this->input->post('employee_id')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $value          = filter_var(trim($this->input->post('value')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $description    = filter_var(trim($this->input->post('description')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $url_image      = trim($this->input->post('url_image'));
        $temp_image     = filter_var(trim($this->input->post('temp_image')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

        $dlk_idx        = $this->secure->dec($dlk_id_enc);
        $employee_id    = $this->secure->dec($emp_id_enc);
        $value          = preg_replace('/[^0-9.]/', '', $value);
        $dlk_date       = date('Y-m-d', strtotime($dlk_datex));

        $check          = $this->mDlk->cekExist($employee_id, $dlk_date);
        if(count($check)>0){
            $msg['status']  = false;
            $msg['msg']     = '<b>'.$check[0]->employee_name.'</b> sudah ada DLK di tanggal ini.';
            echo json_encode($msg);die;
        }

        if($temp_image==1){
            $dataInsert     = [
                'dlk_idx'           => $dlk_idx,
                'employee_id'       => $employee_id,
                'dlk_date'          => $dlk_date,
                'value'             => $value,
                'url_prof'          => $url_image,
                'description'       => $description,
                'company_idx'       => $this->office,
                'created_by'        => $this->idx,
                'created_on'        => date('Y-m-d H:i:s')
            ];
        }else{
            $dataInsert     = [
                'dlk_idx'           => $dlk_idx,
                'employee_id'       => $employee_id,
                'dlk_date'          => $dlk_date,
                'value'             => $value,
                'description'       => $description,
                'company_idx'       => $this->office,
                'created_by'        => $this->idx,
                'created_on'        => date('Y-m-d H:i:s')
            ];
        }
        // var_dump('<pre>');var_dump($dataInsert);die;
        $cek = $this->mDlk->insertIncDetail($dataInsert);
        if($cek==true){
            $msg['status']  = true;
            $msg['msg']     = 'Berhasil.';
            echo json_encode($msg);die;
        }else{
            $msg['status']  = false;
            $msg['msg']     = 'Gagal Insert.';
            echo json_encode($msg);die;
        }
    }
    public function update_dlk_detail($id)
    {
        $this->form_validation->set_rules('csrfsession', 'Token Page', 'trim|required',[
            'required' => 'Token Page wajib di isi.'
        ]);
        $this->form_validation->set_rules('employee_id', 'Karyawan', 'trim|required',[
            'required' => 'Karyawan wajib di pilih.'
        ]);
        $this->form_validation->set_rules('value', 'Nilai Dlk', 'trim|required',[
            'required' => 'Nilai Dlk wajib di isi.'
        ]);
        $this->form_validation->set_rules('description', 'Deskripsi', 'trim|required',[
            'required' => 'Deskripsi wajib di isi.'
        ]);
        if ($this->form_validation->run() == false) {
            $msg['status']  = false;
            $msg['msg']     = validation_errors();
            echo json_encode($msg);die;
        }

        $csrfToken = validate_csrf_token();
        if($csrfToken==false){
            $msg['status']  = false;
            $msg['msg']     = 'Invalid Token.';
            echo json_encode($msg);die;
        }
        
        $idx            = $this->secure->dec($id);
        $value          = filter_var(trim($this->input->post('value')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $description    = filter_var(trim($this->input->post('description')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $url_image      = trim($this->input->post('url_image'));
        $temp_image     = filter_var(trim($this->input->post('temp_image')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

        $value          = preg_replace('/[^0-9.]/', '', $value);

        if($temp_image==1){
            $dataUpdate     = [
                'value'             => $value,
                'url_prof'          => $url_image,
                'description'       => $description,
                'modified_by'       => $this->idx,
                'modified_on'       => date('Y-m-d H:i:s')
            ];
        }else{
            $dataUpdate     = [
                'value'             => $value,
                'description'       => $description,
                'modified_by'       => $this->idx,
                'modified_on'       => date('Y-m-d H:i:s')
            ];
        }
        // var_dump('<pre>');var_dump($dataUpdate);die;
        $cek = $this->mDlk->updateIncDetail($dataUpdate, $idx);
        if($cek==true){
            $msg['status']  = true;
            $msg['msg']     = 'Berhasil.';
            echo json_encode($msg);die;
        }else{
            $msg['status']  = false;
            $msg['msg']     = 'Gagal Insert.';
            echo json_encode($msg);die;
        }
    }

    public function confirmIncentif()
    {
        $idx = $this->secure->dec($this->input->post('tId'));
        $id_status = $this->input->post('tClCode');
        $csrfToken = validate_csrf_token();
        // var_dump($csrfToken);die;
        if($csrfToken == FALSE){
            $msg['status']   = false;
            $msg['text']     = 'Please refresh page and try again.!';
            echo json_encode($msg);die;
        }
        $data = [
            'status_confirm'    => 1,
            'confirm_date'      => date("Y-m-d H:i:s"),
            'modified_by'       => $this->idx,
            'modified_on'       => date("Y-m-d H:i:s")
        ];
        $cek = $this->mDlk->confirmIncentif($data, $idx);
        if($cek == true)
        {
            $msg['text']    = 'Dlk sudah terkonfirmasi';
            $msg['status']  = true;
            echo json_encode($msg);die;
        }else{
            $msg['text']     = 'Please refresh page and try again later.!';
            $msg['status']   = false;
            echo json_encode($msg);die;
        }
    }

    public function confirmAllIncentif()
    {
        $idx = $this->secure->dec($this->input->post('tId'));
        $id_status = $this->input->post('tClCode');
        $csrfToken = validate_csrf_token();
        // var_dump($csrfToken);die;
        if($csrfToken == FALSE){
            $msg['status']   = false;
            $msg['text']     = 'Please refresh page and try again.!';
            echo json_encode($msg);die;
        }
        $data = [
            'status_confirm'    => 1,
            'confirm_date'      => date("Y-m-d H:i:s"),
            'modified_by'       => $this->idx,
            'modified_on'       => date("Y-m-d H:i:s")
        ];
        $cek = $this->mDlk->confirmAllIncentif($data, $idx);
        if($cek == true)
        {
            $msg['text']    = 'Dlk sudah terkonfirmasi';
            $msg['status']  = true;
            echo json_encode($msg);die;
        }else{
            $msg['text']     = 'Please refresh page and try again later.!';
            $msg['status']   = false;
            echo json_encode($msg);die;
        }
    }
}
/* End of file Calendar.php */
/* Location: ./application/controllers/core/Calendar.php */
