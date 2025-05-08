<?php
defined('BASEPATH') or exit('No direct script access allowed');
require "vendor/autoload.php";
use Nullix\CryptoJsAes\CryptoJsAes;
class Incentive extends AUTH_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('attendance/M_incentive', 'mInc');
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
        $data['judul']      = 'Insentif';
        $data['page']       = 'Insentif';
        $data['employee']   = $this->mInc->getEmployee();
        $data["link"]       = $this->link;
        // var_dump('<pre>');var_dump($this->session->csrf_token);die;
        $params             = '{"base_url": "'.base_url().'", "link": "'.$this->link.'", "csrf": "'.$this->session->csrf_token.'", "permW": "'.$this->security_function->permissions($this->filename . "-w").'", "permC": "'.$this->security_function->permissions($this->filename . "-c").'", "permX": "'.$this->security_function->permissions($this->filename . "-x").'", "key_firebase": '.key_firebase().'}';
        // encrypt
        $encrypted          = CryptoJsAes::encrypt($params, $this->enkey);
        $data['params']     = $encrypted;
        $this->template->views('attendance/v_incentive', $data);
    }

    function get_tb_incentive()
    {
        $incidx   = $this->input->post('incid');
        $csrfToken  = validate_csrf_token();
        if($csrfToken == false){
            $list = array();
            $draw = 1;
            $c_all = 0;
            $f_all = 0;
        }else{
            if(empty($incidx)){
                $list = array();
                $draw = 1;
                $c_all = 0;
                $f_all = 0;
            }else{
                $incid = $this->secure->dec($incidx);
                $list = $this->mInc->get_datatables($incid);
                $draw = @$_POST['draw'];
                $c_all = (int)$this->mInc->count_all($incid);
                $f_all = (int)$this->mInc->count_filtered($incid);
            }
        }
        // var_dump('<pre>');var_dump($list);die;
        $data = array();
        $no = @$_POST['start'];
        foreach ($list as $item) {
            $idx        = $this->secure->enc($item->idx);
            $inc_idx    = $this->secure->enc($item->inc_idx);
            $inc_date   = date('d-M-Y', strtotime($item->incentive_date));
            if(!empty($this->security_function->permissions($this->filename . "-w"))){
            }else{
            }
            if(!empty($this->security_function->permissions($this->filename . "-c"))){
                if($item->status_confirm==1){
                    $change = "";
                    $confirm = "<h5><a href='javascript:void(0);' class='text-center badge badge-success bConfirmed' data-popup='tooltip' title='Confirmed' data-placement='left'><i class='icon-checkmark'></i></a></h5>";
                }else{
                    $confirm = "<h5><a href='javascript:void(0);' id='$idx' data='$item->employee_name' incid='$inc_idx' incdate='$inc_date' class='bPublish text-center badge badge-warning' data-popup='tooltip' title='Konfirmasi Insentif' data-placement='left'><i class='icon-exclamation'></i></a></h5>";
                    $change = "<h5 class='m-0'><a href='javascript:void(0);' id='$idx' incid='$inc_idx' incdate='$inc_date' class='bEdit text-center badge badge-info' data-popup='tooltip' title='Edit Insentif' data-placement='left'><i class='icon-pencil5'></i></a></h5>";
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
                    $change = '<h5 class="m-0"><span class="badge badge-warning bLockStatus"><i class="icon-lock" data-popup="tooltip" title="Locked Edit Insentif" data-placement="left"></i></span></h5>';
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

    public function getIncentive()
    {
        $month = $this->input->post('month');
        $year = $this->input->post('year');
        $data = $this->mInc->getCalendarIncentive($month, $year);
        $data_arr=array();
        if($data){
            foreach($data as $d){
                $e = [
                    'id' => $this->secure->enc($d->idx),
                    'title' => $d->incentive_code,
                    'start' => date("Y-m-d", strtotime($d->incentive_date)),
                    'end' => date("Y-m-d", strtotime($d->incentive_date))
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

    public function checkIncentiveByDate()
    {
        $dateCalendar = $this->input->get('now');
        if(empty($dateCalendar)){
            $msg['status']  = false;
            $msg['msg']     = 'Tanggal Kalender wajib di isi.';
            echo json_encode($msg);die;
        }

        $cek = $this->mInc->checkIncentiveByDate($dateCalendar);
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

    public function save_incentive_header()
    {
        $this->form_validation->set_rules('incentive_date', 'Tanggal insentif', 'trim|required',[
            'required' => 'Tanggal insentif wajib di isi.'
        ]);
        $this->form_validation->set_rules('description', 'Deskripsi', 'trim|required',[
            'required' => 'Deskripsi wajib di isi.'
        ]);
        if ($this->form_validation->run() == false) {
            $msg['status']  = false;
            $msg['msg']     = validation_errors();
            echo json_encode($msg);die;
        }

        // $incentive_id   = $this->ModelGenId->genIdUnlimited('INCID', $this->idx);
        $incentive_no   = $this->ModelGenId->genIdYear('INCID', $this->idx);
        $incentive_code = $incentive_no.'/INC/'.$this->officeCode.'/'.numberToRoman(date('m')).'/'.date('Y');
        $incentive_date = filter_var(trim($this->input->post('incentive_date')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $description    = filter_var(trim($this->input->post('description')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

        $data = [
            'incentive_code'    => $incentive_code,
            'incentive_date'    => $incentive_date,
            'description'       => $description,
            'company_idx'       => $this->office,
            'created_by'        => $this->idx,
            'created_on'        => date('Y-m-d'),
        ];

        $result = $this->mInc->insertEvent($data);
        if($result==1){
            $msg['status']  = true;
            $msg['msg']     = 'Tambah insentif berhasil.';
            echo json_encode($msg);die;
        }else{
            $msg['status']  = false;
            $msg['msg']     = 'Tambah insentif gagal.';
            echo json_encode($msg);die;
        }
    }

    public function get_incentive()
    {
        $id = $this->input->get('id');
        $idx = $this->secure->dec($id);
        $data = $this->mInc->getIncentive($idx);
        if($data){
            $res = [
                'status' => 1,
                'incentive_idx' => $this->secure->enc((int)$data['idx']),
                'description' => $data['description'],
                'start' => $data['incentive_date'],
                'end' => $data['incentive_date']
            ];
        }else{
            $res = [
                'status' => 1,
                'incentive_idx' => null,
                'description' => null,
                'start' => null,
                'end' => null
            ];
        }
        echo json_encode($res);die;
    }

    public function get_incentive_detail()
    {
        $id = $this->input->get('id');
        $idx = $this->secure->dec($id);
        $data = $this->mInc->getIncentiveDetail($idx);
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

        $result = $this->mInc->updateEvent($data, $id);
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

    public function add_incentive_detail()
    {
        $this->form_validation->set_rules('csrfsession', 'Token Page', 'trim|required',[
            'required' => 'Token Page wajib di isi.'
        ]);
        $this->form_validation->set_rules('inc_idx', 'Insentif ID', 'trim|required',[
            'required' => 'Insentif ID wajib di isi.'
        ]);
        $this->form_validation->set_rules('inc_date', 'Tanggal Insentif', 'trim|required',[
            'required' => 'Tanggal Insentif wajib di isi.'
        ]);
        $this->form_validation->set_rules('employee_id', 'Karyawan', 'trim|required',[
            'required' => 'Karyawan wajib di pilih.'
        ]);
        $this->form_validation->set_rules('value', 'Nilai Insentif', 'trim|required',[
            'required' => 'Nilai Insentif wajib di isi.'
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

        $inc_id_enc     = filter_var(trim($this->input->post('inc_idx')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $inc_datex      = filter_var(trim($this->input->post('inc_date')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $emp_id_enc     = filter_var(trim($this->input->post('employee_id')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $value          = filter_var(trim($this->input->post('value')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $description    = filter_var(trim($this->input->post('description')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $url_image      = trim($this->input->post('url_image'));
        $temp_image     = filter_var(trim($this->input->post('temp_image')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

        $inc_idx        = $this->secure->dec($inc_id_enc);
        $employee_id    = $this->secure->dec($emp_id_enc);
        $value          = preg_replace('/[^0-9.]/', '', $value);
        $inc_date       = date('Y-m-d', strtotime($inc_datex));

        $check          = $this->mInc->cekExist($employee_id, $inc_date);
        if(count($check)>0){
            $msg['status']  = false;
            $msg['msg']     = '<b>'.$check[0]->employee_name.'</b> sudah ada Insentif di tanggal ini.';
            echo json_encode($msg);die;
        }

        if($temp_image==1){
            $dataInsert     = [
                'inc_idx'           => $inc_idx,
                'employee_id'       => $employee_id,
                'incentive_date'    => $inc_date,
                'value'             => $value,
                'url_prof'          => $url_image,
                'description'       => $description,
                'company_idx'       => $this->office,
                'created_by'        => $this->idx,
                'created_on'        => date('Y-m-d H:i:s')
            ];
        }else{
            $dataInsert     = [
                'inc_idx'           => $inc_idx,
                'employee_id'       => $employee_id,
                'incentive_date'    => $inc_date,
                'value'             => $value,
                'description'       => $description,
                'company_idx'       => $this->office,
                'created_by'        => $this->idx,
                'created_on'        => date('Y-m-d H:i:s')
            ];
        }
        // var_dump('<pre>');var_dump($dataInsert);die;
        $cek = $this->mInc->insertIncDetail($dataInsert);
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
    public function update_incentive_detail($id)
    {
        $this->form_validation->set_rules('csrfsession', 'Token Page', 'trim|required',[
            'required' => 'Token Page wajib di isi.'
        ]);
        $this->form_validation->set_rules('employee_id', 'Karyawan', 'trim|required',[
            'required' => 'Karyawan wajib di pilih.'
        ]);
        $this->form_validation->set_rules('value', 'Nilai Insentif', 'trim|required',[
            'required' => 'Nilai Insentif wajib di isi.'
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
        $cek = $this->mInc->updateIncDetail($dataUpdate, $idx);
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
        $cek = $this->mInc->confirmIncentif($data, $idx);
        if($cek == true)
        {
            $msg['text']    = 'Insentif sudah terkonfirmasi';
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
        $cek = $this->mInc->confirmAllIncentif($data, $idx);
        if($cek == true)
        {
            $msg['text']    = 'Insentif sudah terkonfirmasi';
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
