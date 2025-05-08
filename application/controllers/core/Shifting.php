<?php
defined('BASEPATH') or exit('No direct script access allowed');
require "vendor/autoload.php";
use Nullix\CryptoJsAes\CryptoJsAes;
class Shifting extends AUTH_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('core/M_shifting', 'mShift');
        $this->load->model('ModelGenId');
        $this->load->library('security_function');
        $this->link	    = site_url('core/').strtolower(get_class($this));
        $this->filename = strtolower(get_class($this));
        $this->office   = $this->secure->dec($this->session->userdata('JToffice_id'));
        $this->tOffice  = $this->secure->dec($this->session->userdata('JToffice_type'));
        $this->hub      = $this->secure->dec($this->session->userdata('JThub_id'));
        $this->idx      = $this->secure->dec($this->session->userdata('JTidx'));
        $this->username = $this->session->userdata('JTuser_id');
        $this->enkey    = $this->config->item('encryption_key');
    }
    /*
    |--------------------------------------------------------------------------
    | Main Index
    |--------------------------------------------------------------------------
    |
    | Note: Main Table master_shift.
    |
    */
    public function index()
    {
        $access = $this->security_function->permissions($this->filename . "-r");
        if (empty($access)) die("<center><h1>You can not get the right to access this module</h1></center>");
        if(!empty($this->security_function->permissions($this->filename . "-w"))){
            $data['write']  = '<a href="javascript:void(0);" id="btnAdd" class="pull-right text-white small" title="Tambah Jabatan" data-placement="right" data-popup="tooltip"><i class="icon-plus-circle2"></i></a>';
            $data['lock']   = 1;
        }else{
            $data['write']  = '<i class="icon-lock pull-right pt-1 " title="Tambah Jabatan Terkunci" data-placement="right" data-popup="tooltip"></i>';
            $data['lock']   = 0;
        }
        $data['department'] = $this->mShift->getDepartment();
        $data['filename']   = $this->filename;
        $data["link"]       = $this->link;
        $params             = '{"link": "'.$this->link.'", "csrf": "'.$this->session->csrf_token.'", "permW": "'.$this->security_function->permissions($this->filename . "-w").'", "permC": "'.$this->security_function->permissions($this->filename . "-c").'", "permX": "'.$this->security_function->permissions($this->filename . "-x").'"}';
        $encrypted          = CryptoJsAes::encrypt($params, $this->enkey);
        $data['params']     = $encrypted;
        $this->template->views('core/v_shifting', $data);
    }
    // CONTOH LAIN CONTROLLER2 POST DATATABLE SERVERSIDE
    function get_ajax()
    {
        $csrfToken  = validate_csrf_token();
        if($csrfToken == false){
            $list = array();
        }else{
            $list = $this->mShift->get_datatables();
        }
        // echo json_encode($list); die;
        $data = array();
        $no = @$_POST['start'];
        foreach ($list as $item) {
            $idx            = $this->secure->enc($item->idx);
            if(!empty($this->security_function->permissions($this->filename . "-w"))){
            }else{
            }
            if(!empty($this->security_function->permissions($this->filename . "-c"))){
                $change = "<h5 class='m-0'><a href='javascript:void(0);' id='$idx' class='bEdit text-center badge badge-info' data-popup='tooltip' title='Edit Jabatan' data-placement='right'><i class='icon-pencil5'></i></a></h5>";
                if($item->shift_mode==1){
                    $cycle = "<h5 class='m-0'><a href='$this->link/cycle_mode/$idx' id='$idx' class='bCycle text-center badge badge-warning' data-popup='tooltip' title='Edit Siklus Mode' data-placement='left'><i class='icon-calendar2'></i></a></h5>";
                }else{
                    $cycle = "";
                }
                $setup = "<h5 class='m-0'><a href='javascript:void(0);' id='$idx' class='bSetup text-center badge badge-primary' data-popup='tooltip' title='Toleransi Kehadiran' data-placement='left'><i class='icon-folder-search'></i></a></h5>";
            }else{
                $change = '<h5 class="m-0"><span class="badge badge-warning bLockStatus"><i class="icon-lock" data-popup="tooltip" title="Locked Edit Jabatan" data-placement="right"></i></span></h5>';
                if($item->shift_mode==1){
                    $cycle = '<h5 class="m-0"><span class="badge badge-warning bLockStatus"><i class="icon-lock" data-popup="tooltip" title="Edit Siklus Terkunci" data-placement="left"></i></span></h5>';
                }else{
                    $cycle = "";
                }
                $setup = '<h5 class="m-0"><span class="badge badge-warning bLockStatus"><i class="icon-lock" data-popup="tooltip" title="Locked Toleransi Kehadiran" data-placement="left"></i></span></h5>';
            }
            if(!empty($this->security_function->permissions($this->filename . "-x"))){
                $execute  = "<h5 class='m-0'><a href='javascript:void(0);' id='$idx' data='$item->shift_name' class='bDelete text-center badge badge-danger' data-popup='tooltip' title='Hapus Jabatan' data-placement='right'><i class='icon-bin'></i></a></h5>";
            }else{
                $execute  = '<h5 class="m-0"><span class="badge badge-warning"><i class="icon-lock" data-popup="tooltip" title="Hapus Jabatan Terkunci" data-placement="right"></i></span></h5>';
            }
            $start_ot = $item->shift_mode==1?'<span class="text-danger">-</span>':(empty($item->start_overtime)?'<span class="text-danger">-</span>':$item->start_overtime);
            $monday = $item->shift_mode==1?'<span class="text-danger">-</span>':(empty($item->monday_in)?'<span class="badge badge-success">Holiday</span>':(empty($item->monday_out)?'<span class="badge badge-success">Holiday</span>':$item->monday_in . ' - '.$item->monday_out));
            $tuesday = $item->shift_mode==1?'<span class="text-danger">-</span>':(empty($item->tuesday_in)?'<span class="badge badge-success">Holiday</span>':(empty($item->tuesday_out)?'<span class="badge badge-success">Holiday</span>':$item->tuesday_in . ' - '.$item->tuesday_out));
            $wednesday = $item->shift_mode==1?'<span class="text-danger">-</span>':(empty($item->wednesday_in)?'<span class="badge badge-success">Holiday</span>':(empty($item->wednesday_out)?'<span class="badge badge-success">Holiday</span>':$item->wednesday_in . ' - '.$item->wednesday_out));
            $thursday = $item->shift_mode==1?'<span class="text-danger">-</span>':(empty($item->thursday_in)?'<span class="badge badge-success">Holiday</span>':(empty($item->thursday_out)?'<span class="badge badge-success">Holiday</span>':$item->thursday_in . ' - '.$item->thursday_out));
            $friday = $item->shift_mode==1?'<span class="text-danger">-</span>':(empty($item->friday_in)?'<span class="badge badge-success">Holiday</span>':(empty($item->friday_out)?'<span class="badge badge-success">Holiday</span>':$item->friday_in . ' - '.$item->friday_out));
            $saturday = $item->shift_mode==1?'<span class="text-danger">-</span>':(empty($item->saturday_in)?'<span class="badge badge-success">Holiday</span>':(empty($item->saturday_out)?'<span class="badge badge-success">Holiday</span>':$item->saturday_in . ' - '.$item->saturday_out));
            $sunday = $item->shift_mode==1?'<span class="text-danger">-</span>':(empty($item->sunday_in)?'<span class="badge badge-success">Holiday</span>':(empty($item->sunday_out)?'<span class="badge badge-success">Holiday</span>':$item->sunday_in . ' - '.$item->sunday_out));
            $piket = $item->shift_mode==1?'<span class="text-danger">-</span>':(empty($item->piket_in)?'<span class="badge badge-success">Holiday</span>':(empty($item->piket_out)?'<span class="badge badge-success">Holiday</span>':$item->piket_in . ' - '.$item->piket_out));
            $no++;
            $row = array();
            $row[] = $no.".";
            $row[] = "<div class='btn-group'>
                        <button type='button' class='btn btn-danger btn-sm dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'><i class='icon-more2'></i></button>
                        <div class='dropdown-menu p-2' style='min-width: auto !important;'>
                            <div class='btn-group'>$change&nbsp;$execute</div>
                        </div>
                    </div>";
            $row[] = "<div class='btn-group'>$setup&nbsp;$cycle</div>";
            $row[] = $item->shift_name;
            $row[] = $start_ot;
            $row[] = $monday;
            $row[] = $tuesday;
            $row[] = $wednesday;
            $row[] = $thursday;
            $row[] = $friday;
            $row[] = $saturday;
            $row[] = $sunday;
            $row[] = $piket;
            $row[] = $item->description;
            // add html for action
            $data[] = $row;
        }
        $output = array(
            "draw"            => @$_POST['draw'],
            "recordsTotal"    => $this->mShift->count_all(),
            "recordsFiltered" => $this->mShift->count_filtered(),
            "data"            => $data,
        );
        // output to json format
        echo json_encode($output);
    }
    public function addShift()
    {
        $this->form_validation->set_rules('shift_name', 'Nama Shift', 'trim|required',[
            'required' => 'Nama Shift wajib di isi.'
        ]);
        $this->form_validation->set_rules('description', 'Deskripsi', 'trim|required',[
            'required' => 'Deskripsi wajib di isi.'
        ]);
        if ($this->form_validation->run() == false) {
            $msg['error']   = true;
            $msg['message'] = validation_errors();
            echo json_encode($msg);die;
        }
        $csrf = validate_csrf_token();
        if($csrf==false){
            $msg['status']  = true;
            $msg['text']    = 'Token invalid.';
            echo json_encode($msg);die;
        }
        // ini_set ('max_execution_time', '0');
        // ini_set ('memory_limit', '256M');
        $shift_name     = filter_var(trim($this->input->post('shift_name', TRUE)), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $shift_mode     = filter_var(trim($this->input->post('shift_mode', TRUE)), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $cycle_mode     = filter_var(trim($this->input->post('cycle_mode', TRUE)), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $work_days      = filter_var(trim($this->input->post('work_days', TRUE)), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $off_days       = filter_var(trim($this->input->post('off_days', TRUE)), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $check_in       = filter_var(trim($this->input->post('check_in')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH)==''?NULL:filter_var(trim($this->input->post('check_in')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $check_out      = filter_var(trim($this->input->post('check_out')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH)==''?NULL:filter_var(trim($this->input->post('check_out')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $start_ot       = filter_var(trim($this->input->post('start_overtime')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH)==''?NULL:filter_var(trim($this->input->post('start_overtime')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $monday_in      = filter_var(trim($this->input->post('monday_in')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH)==''?NULL:filter_var(trim($this->input->post('monday_in')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $monday_out     = filter_var(trim($this->input->post('monday_out')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH)==''?NULL:filter_var(trim($this->input->post('monday_out')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $tuesday_in     = filter_var(trim($this->input->post('tuesday_in')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH)==''?NULL:filter_var(trim($this->input->post('tuesday_in')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $tuesday_out    = filter_var(trim($this->input->post('tuesday_out')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH)==''?NULL:filter_var(trim($this->input->post('tuesday_out')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $wednesday_in   = filter_var(trim($this->input->post('wednesday_in')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH)==''?NULL:filter_var(trim($this->input->post('wednesday_in')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $wednesday_out  = filter_var(trim($this->input->post('wednesday_out')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH)==''?NULL:filter_var(trim($this->input->post('wednesday_out')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $thursday_in    = filter_var(trim($this->input->post('thursday_in')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH)==''?NULL:filter_var(trim($this->input->post('thursday_in')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $thursday_out   = filter_var(trim($this->input->post('thursday_out')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH)==''?NULL:filter_var(trim($this->input->post('thursday_out')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $friday_in      = filter_var(trim($this->input->post('friday_in')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH)==''?NULL:filter_var(trim($this->input->post('friday_in')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $friday_out     = filter_var(trim($this->input->post('friday_out')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH)==''?NULL:filter_var(trim($this->input->post('friday_out')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $saturday_in    = filter_var(trim($this->input->post('saturday_in')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH)==''?NULL:filter_var(trim($this->input->post('saturday_in')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $saturday_out   = filter_var(trim($this->input->post('saturday_out')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH)==''?NULL:filter_var(trim($this->input->post('saturday_out')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $sunday_in      = filter_var(trim($this->input->post('sunday_in')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH)==''?NULL:filter_var(trim($this->input->post('sunday_in')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $sunday_out     = filter_var(trim($this->input->post('sunday_out')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH)==''?NULL:filter_var(trim($this->input->post('sunday_out')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $piket_in       = filter_var(trim($this->input->post('piket_in')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH)==''?NULL:filter_var(trim($this->input->post('piket_in')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $piket_out      = filter_var(trim($this->input->post('piket_out')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH)==''?NULL:filter_var(trim($this->input->post('piket_out')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $description    = trim($this->input->post('description', TRUE));

        $now            = date("Y-m-d H:i:s");

        $insert_shift = [
            'shift_name'        => $shift_name,
            'shift_mode'        => $shift_mode,
            'cycle_mode'        => $cycle_mode,
            'work_days'         => $work_days,
            'off_days'          => $off_days,
            'description'       => $description,
            'check_in'          => $check_in,
            'check_out'         => $check_out,
            'start_overtime'    => $start_ot,
            'monday_in'         => $monday_in,
            'monday_out'        => $monday_out,
            'tuesday_in'        => $tuesday_in,
            'tuesday_out'       => $tuesday_out,
            'wednesday_in'      => $wednesday_in,
            'wednesday_out'     => $wednesday_out,
            'thursday_in'       => $thursday_in,
            'thursday_out'      => $thursday_out,
            'friday_in'         => $friday_in,
            'friday_out'        => $friday_out,
            'saturday_in'       => $saturday_in,
            'saturday_out'      => $saturday_out,
            'sunday_in'         => $sunday_in,
            'sunday_out'        => $sunday_out,
            'piket_in'          => $piket_in,
            'piket_out'         => $piket_out,
            'created_on'        => $now,
            'created_by'        => $this->idx
        ];
        $cek = $this->mShift->insertShift($insert_shift);
        if($cek == true){
            $msg['success'] = true;
            $msg['text']    = 'Shift Baru berhasil di tambahkan.';
            echo json_encode($msg);die;
        }else{
            $msg['status']  = true;
            $msg['text']    = 'Error Connection,<br>Please try again later.';
            echo json_encode($msg);die;
        }
    }
    public function updateShift($id)
    {
        $idx = $this->secure->dec($id);
        $this->form_validation->set_rules('shift_name', 'Nama Shift', 'trim|required',[
            'required' => 'Nama Shift wajib di isi.'
        ]);
        $this->form_validation->set_rules('description', 'Deskripsi', 'trim|required',[
            'required' => 'Deskripsi wajib di isi.'
        ]);
        if ($this->form_validation->run() == false) {
            $msg['error']   = true;
            $msg['message'] = validation_errors();
            echo json_encode($msg);die;
        }
        $csrf = validate_csrf_token();
        if($csrf==false){
            $msg['status']  = true;
            $msg['text']    = 'Token invalid.';
            echo json_encode($msg);die;
        }
        // ini_set ('max_execution_time', '0');
        // ini_set ('memory_limit', '256M');
        $shift_name     = filter_var(trim($this->input->post('shift_name', TRUE)), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $shift_mode     = filter_var(trim($this->input->post('shift_mode', TRUE)), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $cycle_mode     = filter_var(trim($this->input->post('cycle_mode', TRUE)), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $work_days      = filter_var(trim($this->input->post('work_days', TRUE)), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $off_days       = filter_var(trim($this->input->post('off_days', TRUE)), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $check_in       = filter_var(trim($this->input->post('check_in')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH)==''?NULL:filter_var(trim($this->input->post('check_in')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $check_out      = filter_var(trim($this->input->post('check_out')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH)==''?NULL:filter_var(trim($this->input->post('check_out')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $start_ot       = filter_var(trim($this->input->post('start_overtime')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH)==''?NULL:filter_var(trim($this->input->post('start_overtime')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $monday_in      = filter_var(trim($this->input->post('monday_in')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH)==''?NULL:filter_var(trim($this->input->post('monday_in')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $monday_out     = filter_var(trim($this->input->post('monday_out')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH)==''?NULL:filter_var(trim($this->input->post('monday_out')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $tuesday_in     = filter_var(trim($this->input->post('tuesday_in')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH)==''?NULL:filter_var(trim($this->input->post('tuesday_in')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $tuesday_out    = filter_var(trim($this->input->post('tuesday_out')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH)==''?NULL:filter_var(trim($this->input->post('tuesday_out')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $wednesday_in   = filter_var(trim($this->input->post('wednesday_in')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH)==''?NULL:filter_var(trim($this->input->post('wednesday_in')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $wednesday_out  = filter_var(trim($this->input->post('wednesday_out')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH)==''?NULL:filter_var(trim($this->input->post('wednesday_out')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $thursday_in    = filter_var(trim($this->input->post('thursday_in')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH)==''?NULL:filter_var(trim($this->input->post('thursday_in')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $thursday_out   = filter_var(trim($this->input->post('thursday_out')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH)==''?NULL:filter_var(trim($this->input->post('thursday_out')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $friday_in      = filter_var(trim($this->input->post('friday_in')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH)==''?NULL:filter_var(trim($this->input->post('friday_in')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $friday_out     = filter_var(trim($this->input->post('friday_out')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH)==''?NULL:filter_var(trim($this->input->post('friday_out')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $saturday_in    = filter_var(trim($this->input->post('saturday_in')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH)==''?NULL:filter_var(trim($this->input->post('saturday_in')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $saturday_out   = filter_var(trim($this->input->post('saturday_out')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH)==''?NULL:filter_var(trim($this->input->post('saturday_out')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $sunday_in      = filter_var(trim($this->input->post('sunday_in')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH)==''?NULL:filter_var(trim($this->input->post('sunday_in')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $sunday_out     = filter_var(trim($this->input->post('sunday_out')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH)==''?NULL:filter_var(trim($this->input->post('sunday_out')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $piket_in       = filter_var(trim($this->input->post('piket_in')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH)==''?NULL:filter_var(trim($this->input->post('piket_in')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $piket_out      = filter_var(trim($this->input->post('piket_out')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH)==''?NULL:filter_var(trim($this->input->post('piket_out')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $description    = trim($this->input->post('description', TRUE));

        $now            = date("Y-m-d H:i:s");

        $update_shift = [
            'shift_name'        => $shift_name,
            'shift_mode'        => $shift_mode,
            'cycle_mode'        => $cycle_mode,
            'work_days'         => $work_days,
            'off_days'          => $off_days,
            'description'       => $description,
            'check_in'          => $check_in,
            'check_out'         => $check_out,
            'start_overtime'    => $start_ot,
            'monday_in'         => $monday_in,
            'monday_out'        => $monday_out,
            'tuesday_in'        => $tuesday_in,
            'tuesday_out'       => $tuesday_out,
            'wednesday_in'      => $wednesday_in,
            'wednesday_out'     => $wednesday_out,
            'thursday_in'       => $thursday_in,
            'thursday_out'      => $thursday_out,
            'friday_in'         => $friday_in,
            'friday_out'        => $friday_out,
            'saturday_in'       => $saturday_in,
            'saturday_out'      => $saturday_out,
            'sunday_in'         => $sunday_in,
            'sunday_out'        => $sunday_out,
            'piket_in'          => $piket_in,
            'piket_out'         => $piket_out,
            'modified_on'       => $now,
            'modified_by'       => $this->idx
        ];

        $cek = $this->mShift->updateShift($update_shift, $idx);
        if($cek == true){
            $msg['success'] = true;
            $msg['text']    = 'Shift berhasil diubah.';
            echo json_encode($msg);die;
        }else{
            $msg['status']  = true;
            $msg['text']    = 'Error Connection,<br>Please try again later.';
            echo json_encode($msg);die;
        }
    }
    public function editShift()
    {
        $id     = $this->secure->dec($this->input->get('id'));
        $result = $this->mShift->editShift($id);
        if($result){
            $push = [
                'en_idx' => $this->secure->enc($result['idx'])
            ];
            $final = array_merge($push,$result);
        }else{
            $final = [];
        }
        echo json_encode($final);
    }
    /*
    |--------------------------------------------------------------------------
    | Sub Index Range Tolerance Attendance In Minute
    |--------------------------------------------------------------------------
    |
    | Note: Foreign Table attendance_setup.
    |
    */
    public function setupTolerance($id)
    {
        $idx = $this->secure->dec($id);
        $access = $this->security_function->permissions($this->filename . "-r");
        if (empty($access)) die("<center><h1>You can not get the right to access this module</h1></center>");
        if(!empty($this->security_function->permissions($this->filename . "-w"))){
            $backButton = '<a href="'.$this->link.'" class="list-icons-item" title="Back To List Data" data-placement="right" data-popup="tooltip"><i class="icon-undo2 pr-1"></i></a>';
            $addButton = '<a href="javascript:void(0);" id="btnAdd" class="list-icons-item" title="Tambah Toleransi" data-placement="right" data-popup="tooltip"><i class="icon-plus-circle2"></i></a>';
            $data['write']  = "<div class='list-icons'>$backButton$addButton</div>";
            $data['lock']   = 1;
        }else{
            $backButton = '<a href="'.$this->link.'" class="list-icons-item" title="Back To List Data" data-placement="right" data-popup="tooltip"><i class="icon-undo2 pr-1"></i></a>';
            $addButton = '<span class="list-icons-item" title="Tambah Toleransi Terkunci" data-placement="right" data-popup="tooltip"><i class="icon-lock"></i></span>';
            $data['write']  = "<div class='list-icons'>$backButton$addButton</div>";
            $data['lock']   = 0;
        }
        $shift = $this->mShift->getShiftName($idx);
        $data['shift_idx']  = $id;
        $data['shift_name'] = $shift->shift_name;
        $data['filename']   = $this->filename;
        $data["link"]       = $this->link;
        $params             = '{"link": "'.$this->link.'", "csrf": "'.$this->session->csrf_token.'", "permW": "'.$this->security_function->permissions($this->filename . "-w").'", "permC": "'.$this->security_function->permissions($this->filename . "-c").'", "permX": "'.$this->security_function->permissions($this->filename . "-x").'", "shift_idx": "'.$id.'"}';
        $encrypted          = CryptoJsAes::encrypt($params, $this->enkey);
        $data['params']     = $encrypted;
        $this->template->views('core/v_setup_tolerance', $data);
    }
    // CONTOH LAIN CONTROLLER2 POST DATATABLE SERVERSIDE
    function get_tolerance()
    {
        $shfIdx     = $this->input->post('shift_idx');
        $shf_idx    = $this->secure->dec($shfIdx);
        $csrfToken  = validate_csrf_token();
        if($csrfToken == false){
            $list = array();
        }else{
            $list = $this->mShift->get_dataTolerance($shf_idx);
        }
        $data = array();
        $no = @$_POST['start'];
        foreach ($list as $item) {
            $idx            = $this->secure->enc($item->idx);
            if(!empty($this->security_function->permissions($this->filename . "-w"))){
            }else{
            }
            if(!empty($this->security_function->permissions($this->filename . "-c"))){
                $change = "<h5 class='m-0'><a href='javascript:void(0);' id='$idx' class='bEdit text-center badge badge-info' data-popup='tooltip' title='Edit Jabatan' data-placement='right'><i class='icon-pencil5'></i></a></h5>";
                $setup = "<h5 class='m-0'><a href='javascript:void(0);' id='$idx' class='bSetup text-center badge badge-primary' data-popup='tooltip' title='Toleransi Kehadiran' data-placement='left'><i class='icon-folder-search'></i></a></h5>";
                if($item->status == 1)
                {
                    $statusU = "<h5><a href='javascript:void(0);' id='$idx' data='$item->shift_name' code='1' class='bStatus text-center badge badge-success' data-popup='tooltip' title='Ubah Status' data-placement='right'>Aktif</a></h5>";
                // $statusU = '<span class="badge badge-success">Aktif</span>';
                } else {
                    $statusU = "<h5><a href='javascript:void(0);' id='$idx' data='$item->shift_name' code='0' class='bStatus text-center badge badge-danger' data-popup='tooltip' title='Ubah Status' data-placement='right'>Non Aktif</a></h5>";
                // $statusU = '<span class="badge badge-danger">Non Aktif</span>';
                }
            }else{
                $change = '<h5 class="m-0"><span class="badge badge-warning bLockStatus"><i class="icon-lock" data-popup="tooltip" title="Locked Edit Jabatan" data-placement="right"></i></span></h5>';
                $setup = '<h5 class="m-0"><span class="badge badge-warning bLockStatus"><i class="icon-lock" data-popup="tooltip" title="Locked Toleransi Kehadiran" data-placement="left"></i></span></h5>';
                if($item->status == 1)
                {
                    $statusU = "<span class='text-center badge badge-success'>Aktif</span>";
                }else{
                    $statusU = "<span class='text-center badge badge-danger'>Non Aktif</span>";
                }
            }
            if(!empty($this->security_function->permissions($this->filename . "-x"))){
                $execute  = "<h5 class='m-0'><a href='javascript:void(0);' id='$idx' data='$item->shift_name' class='bDelete text-center badge badge-danger' data-popup='tooltip' title='Hapus Toleransi' data-placement='right'><i class='icon-bin'></i></a></h5>";
            }else{
                $execute  = '<h5 class="m-0"><span class="badge badge-warning"><i class="icon-lock" data-popup="tooltip" title="Hapus Toleransi Terkunci" data-placement="right"></i></span></h5>';
            }
            $no++;
            $row = array();
            $row[] = $no.".";
            $row[] = "<div class='btn-group'>$change$execute</div>";
            $row[] = $statusU;
            $row[] = $item->tolerance_in_start.'-'.$item->tolerance_in_end.' Menit';
            $row[] = $item->tolerance_out_start.'-'.$item->tolerance_out_end.' Menit';
            $row[] = $item->description;
            // add html for action
            $data[] = $row;
        }
        $output = array(
            "draw"            => @$_POST['draw'],
            "recordsTotal"    => (int)$this->mShift->tolerance_all($shf_idx),
            "recordsFiltered" => (int)$this->mShift->tolerance_filtered($shf_idx),
            "data"            => $data,
        );
        // output to json format
        echo json_encode($output);
    }
    public function addTolerance()
    {
        $this->form_validation->set_rules('in_start', 'Toleransi Masuk Awal', 'trim|required',[
            'required' => 'Toleransi Masuk Awal wajib di pilih.'
        ]);
        $this->form_validation->set_rules('in_end', 'Toleransi Masuk Akhir', 'trim|required',[
            'required' => 'Toleransi Masuk Akhir wajib di pilih.'
        ]);
        $this->form_validation->set_rules('out_start', 'Toleransi Pulang Awal', 'trim|required',[
            'required' => 'Toleransi Pulang Awal wajib di isi.'
        ]);
        $this->form_validation->set_rules('out_end', 'Toleransi Pulang Akhir', 'trim|required',[
            'required' => 'Toleransi Pulang Akhir wajib di isi.'
        ]);
        $this->form_validation->set_rules('description', 'Deskripsi', 'trim|required',[
            'required' => 'Deskripsi wajib di isi.'
        ]);
        if ($this->form_validation->run() == false) {
            $msg['error']   = true;
            $msg['message'] = validation_errors();
            echo json_encode($msg);die;
        }
        $csrf = validate_csrf_token();
        if($csrf==false){
            $msg['status']  = true;
            $msg['text']    = 'Token invalid.';
            echo json_encode($msg);die;
        }
        // ini_set ('max_execution_time', '0');
        // ini_set ('memory_limit', '256M');
        $shift_idx      = trim($this->input->post('shift_idx', TRUE));
        $in_start       = trim($this->input->post('in_start', TRUE));
        $in_end         = trim($this->input->post('in_end', TRUE));
        $out_start      = trim($this->input->post('out_start', TRUE));
        $out_end        = trim($this->input->post('out_end', TRUE));
        $description    = trim($this->input->post('description', TRUE));
        $status         = trim($this->input->post('tStatus'));

        $shf_idx = $this->secure->dec($shift_idx);

        $cekInStart     = preg_replace('/[^0-9]/', '', $in_start);
        $cekInEnd       = preg_replace('/[^0-9]/', '', $in_end);
        $cekOutStart    = preg_replace('/[^0-9]/', '', $out_start);
        $cekOutEnd      = preg_replace('/[^0-9]/', '', $out_end);

        $data = [
            'shift_idx'             => $shf_idx,
            'tolerance_in_start'    => $cekInStart,
            'tolerance_in_end'      => $cekInEnd,
            'tolerance_out_start'   => $cekOutStart,
            'tolerance_out_end'     => $cekOutEnd,
            'description'           => $description,
            'status'                => $status,
            'created_by'            => $this->idx,
            'created_on'            => date("Y-m-d H:i:s")
        ];
        $cek = $this->mShift->insertTolerance($data);
        if($cek == true){
            $msg['success'] = true;
            $msg['text']    = 'Data Toleransi di tambahkan.';
            echo json_encode($msg);die;
        }else{
            $msg['status']  = true;
            $msg['text']    = 'Error Connection,<br>Please try again later.';
            echo json_encode($msg);die;
        }
    }
    public function editTolerance()
    {
        $id     = $this->secure->dec($this->input->get('id'));
        $result = $this->mShift->editTolerance($id);
        if($result){
            $push = [
                'en_idx' => $this->secure->enc($result['idx']),
                'shf_enidx' => $this->secure->enc($result['shift_idx']),
            ];
            $final = array_merge($push,$result);
        }else{
            $final = [];
        }
        echo json_encode($final);
    }
    public function updateTolerance($id)
    {
        $this->form_validation->set_rules('in_start', 'Toleransi Masuk Awal', 'trim|required',[
            'required' => 'Toleransi Masuk Awal wajib di pilih.'
        ]);
        $this->form_validation->set_rules('in_end', 'Toleransi Masuk Akhir', 'trim|required',[
            'required' => 'Toleransi Masuk Akhir wajib di pilih.'
        ]);
        $this->form_validation->set_rules('out_start', 'Toleransi Pulang Awal', 'trim|required',[
            'required' => 'Toleransi Pulang Awal wajib di isi.'
        ]);
        $this->form_validation->set_rules('out_end', 'Toleransi Pulang Akhir', 'trim|required',[
            'required' => 'Toleransi Pulang Akhir wajib di isi.'
        ]);
        $this->form_validation->set_rules('description', 'Deskripsi', 'trim|required',[
            'required' => 'Deskripsi wajib di isi.'
        ]);
        if ($this->form_validation->run() == false) {
            $msg['error']   = true;
            $msg['message'] = validation_errors();
            echo json_encode($msg);die;
        }
        $csrf = validate_csrf_token();
        if($csrf==false){
            $msg['status']  = true;
            $msg['text']    = 'Token invalid.';
            echo json_encode($msg);die;
        }
        
        $in_start       = trim($this->input->post('in_start', TRUE));
        $in_end         = trim($this->input->post('in_end', TRUE));
        $out_start      = trim($this->input->post('out_start', TRUE));
        $out_end        = trim($this->input->post('out_end', TRUE));
        $description    = trim($this->input->post('description', TRUE));
        $status         = trim($this->input->post('tStatus'));

        $idx = $this->secure->dec($id);

        $cekInStart     = preg_replace('/[^0-9]/', '', $in_start);
        $cekInEnd       = preg_replace('/[^0-9]/', '', $in_end);
        $cekOutStart    = preg_replace('/[^0-9]/', '', $out_start);
        $cekOutEnd      = preg_replace('/[^0-9]/', '', $out_end);

        $data = [
            'tolerance_in_start'    => $cekInStart,
            'tolerance_in_end'      => $cekInEnd,
            'tolerance_out_start'   => $cekOutStart,
            'tolerance_out_end'     => $cekOutEnd,
            'description'           => $description,
            'status'                => $status,
            'modified_by'           => $this->idx,
            'modified_on'           => date("Y-m-d H:i:s")
        ];
        $cek = $this->mShift->updateTolerance($data, $idx);
        if($cek == true){
            $msg['success'] = true;
            $msg['text']    = 'Data Toleransi di ubah.';
            echo json_encode($msg);die;
        }else{
            $msg['status']  = true;
            $msg['text']    = 'Error Connection,<br>Please try again later.';
            echo json_encode($msg);die;
        }
    }
    /*
    |--------------------------------------------------------------------------
    | Sub Index Shift cycle shift for security etc.
    |--------------------------------------------------------------------------
    |
    | Note: Foreign Table dynamic_shift.
    |
    */
    public function cycle_mode($id)
    {
        $idx = $this->secure->dec($id);
        $access = $this->security_function->permissions($this->filename . "-r");
        if (empty($access)) die("<center><h1>You can not get the right to access this module</h1></center>");
        if(!empty($this->security_function->permissions($this->filename . "-w"))){
            $backButton = '<a href="'.$this->link.'" class="list-icons-item" title="Back To List Data" data-placement="right" data-popup="tooltip"><i class="icon-undo2 pr-1"></i></a>';
            $addButton = '<a href="javascript:void(0);" id="btnAdd" class="list-icons-item" title="Tambah Toleransi" data-placement="right" data-popup="tooltip"><i class="icon-plus-circle2"></i></a>';
            $data['write']  = "<div class='list-icons'>$backButton$addButton</div>";
            $data['lock']   = 1;
        }else{
            $backButton = '<a href="'.$this->link.'" class="list-icons-item" title="Back To List Data" data-placement="right" data-popup="tooltip"><i class="icon-undo2 pr-1"></i></a>';
            $addButton = '<span class="list-icons-item" title="Tambah Toleransi Terkunci" data-placement="right" data-popup="tooltip"><i class="icon-lock"></i></span>';
            $data['write']  = "<div class='list-icons'>$backButton$addButton</div>";
            $data['lock']   = 0;
        }
        $shift = $this->mShift->getShiftName($idx);
        $data['employee']   = $this->mShift->getEmployeeShift($idx);
        $data['shift_idx']  = $id;
        $data['shift_name'] = $shift->shift_name;
        $data['work_days']  = $shift->work_days;
        $data['off_days']   = $shift->off_days;
        $data['filename']   = $this->filename;
        $data["link"]       = $this->link;
        $params             = '{"link": "'.$this->link.'", "csrf": "'.$this->session->csrf_token.'", "permW": "'.$this->security_function->permissions($this->filename . "-w").'", "permC": "'.$this->security_function->permissions($this->filename . "-c").'", "permX": "'.$this->security_function->permissions($this->filename . "-x").'", "shift_idx": "'.$id.'", "work_days": "'.$shift->work_days.'", "off_days": "'.$shift->off_days.'"}';
        $encrypted          = CryptoJsAes::encrypt($params, $this->enkey);
        $data['params']     = $encrypted;
        $this->template->views('core/v_cycle_mode', $data);
    }
    function get_cycleMode()
    {
        $shfIdx     = $this->input->post('shift_idx');
        $shf_idx    = $this->secure->dec($shfIdx);
        $csrfToken  = validate_csrf_token();
        if($csrfToken == false){
            $list = array();
        }else{
            $list = $this->mShift->get_cycleMode($shf_idx);
            // $list = [];
        }
        // var_dump('<pre>');var_dump($list);die;
        $data = array();
        $no = @$_POST['start'];
        foreach ($list as $item) {
            $idx            = $this->secure->enc($item->idx);
            $employee_id    = $this->secure->enc($item->employee_id);
            if(!empty($this->security_function->permissions($this->filename . "-w"))){
            }else{
            }
            if(!empty($this->security_function->permissions($this->filename . "-c"))){
                $change = "<h5 class='m-0'><a href='javascript:void(0);' id='$idx' class='bEdit text-center badge badge-info' data-popup='tooltip' title='Edit Jadwal' data-placement='right'><i class='icon-pencil5'></i></a></h5>";
                $setup = "<h5 class='m-0'><a href='javascript:void(0);' id='$idx' data='$item->employee_name' empid='$employee_id' class='bSetup text-center badge badge-primary' data-popup='tooltip' title='Detail Jadwal' data-placement='right'><i class='icon-folder-search'></i></a></h5>";
                if($item->status == 1)
                {
                    $statusU = "<h5><a href='javascript:void(0);' id='$idx' data='' code='1' class='bStatus text-center badge badge-success' data-popup='tooltip' title='Ubah Status' data-placement='right'>Aktif</a></h5>";
                // $statusU = '<span class="badge badge-success">Aktif</span>';
                } else {
                    $statusU = "<h5><a href='javascript:void(0);' id='$idx' data='' code='0' class='bStatus text-center badge badge-danger' data-popup='tooltip' title='Ubah Status' data-placement='right'>Non Aktif</a></h5>";
                // $statusU = '<span class="badge badge-danger">Non Aktif</span>';
                }
            }else{
                $change = '<h5 class="m-0"><span class="badge badge-warning bLockStatus"><i class="icon-lock" data-popup="tooltip" title="Locked Edit Jadwal" data-placement="right"></i></span></h5>';
                $setup = '<h5 class="m-0"><span class="badge badge-warning bLockStatus"><i class="icon-lock" data-popup="tooltip" title="Locked Detail Jadwal" data-placement="right"></i></span></h5>';
                if($item->status == 1)
                {
                    $statusU = "<span class='text-center badge badge-success'>Aktif</span>";
                }else{
                    $statusU = "<span class='text-center badge badge-danger'>Non Aktif</span>";
                }
            }
            if(!empty($this->security_function->permissions($this->filename . "-x"))){
                $execute  = "<h5 class='m-0'><a href='javascript:void(0);' id='$idx' data='' class='bDelete text-center badge badge-danger' data-popup='tooltip' title='Hapus Toleransi' data-placement='right'><i class='icon-bin'></i></a></h5>";
            }else{
                $execute  = '<h5 class="m-0"><span class="badge badge-warning"><i class="icon-lock" data-popup="tooltip" title="Hapus Toleransi Terkunci" data-placement="right"></i></span></h5>';
            }
            $no++;
            $row = array();
            $row[] = $no.".";
            $row[] = "<div class='btn-group'>$setup</div>";
            // $row[] = $statusU;
            $row[] = $item->employee_code;
            $row[] = $item->employee_name;
            $row[] = empty($item->organization_name)?'<span class="text-danger">Unset</span>':$item->organization_name;
            // add html for action
            $data[] = $row;
        }
        $output = array(
            "draw"            => @$_POST['draw'],
            "recordsTotal"    => (int)$this->mShift->cycle_all($shf_idx),
            "recordsFiltered" => (int)$this->mShift->cycle_filtered($shf_idx),
            "data"            => $data,
        );
        // output to json format
        echo json_encode($output);
    }
    public function addCycle()
    {
        $this->form_validation->set_rules('shift_idx', 'Shift Id', 'trim|required',[
            'required' => 'Shift Id unset.'
        ]);
        $this->form_validation->set_rules('work_days', 'Count Work Days', 'trim|required',[
            'required' => 'Count Work Days unset.'
        ]);
        $this->form_validation->set_rules('off_days', 'Count Off Days', 'trim|required',[
            'required' => 'Count Off Days unset.'
        ]);
        $this->form_validation->set_rules('shift_idx', 'Shift Id', 'trim|required',[
            'required' => 'Shift Id unset.'
        ]);
        $this->form_validation->set_rules('employee_id', 'Karyawan', 'trim|required',[
            'required' => 'Karyawan wajib di pilih.'
        ]);
        $this->form_validation->set_rules('cycle_mode', 'Model Siklus', 'trim|required',[
            'required' => 'Model Siklus wajib di pilih.'
        ]);
        $this->form_validation->set_rules('check_in', 'Jam Masuk', 'trim|required',[
            'required' => 'Jam Masuk wajib di isi.'
        ]);
        $this->form_validation->set_rules('check_out', 'Jam Masuk', 'trim|required',[
            'required' => 'Jam Pulang wajib di isi.'
        ]);
        $this->form_validation->set_rules('from', 'Periode Dimulai', 'trim|required',[
            'required' => 'Periode Berakhir wajib di isi.'
        ]);
        $this->form_validation->set_rules('to', 'Periode Berakhir', 'trim|required',[
            'required' => 'Periode Berakhir wajib di isi.'
        ]);
        if ($this->form_validation->run() == false) {
            $msg['error']   = true;
            $msg['message'] = validation_errors();
            echo json_encode($msg);die;
        }
        $csrf = validate_csrf_token();
        if($csrf==false){
            $msg['status']  = true;
            $msg['text']    = 'Token invalid.';
            echo json_encode($msg);die;
        }
        // ini_set ('max_execution_time', '0');
        // ini_set ('memory_limit', '256M');
        $shift_idx      = filter_var(trim($this->input->post('shift_idx', TRUE)), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $work_days      = filter_var(trim($this->input->post('work_days', TRUE)), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $off_days       = filter_var(trim($this->input->post('off_days', TRUE)), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $eEmployee_id   = filter_var(trim($this->input->post('employee_id', TRUE)), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $cycle_mode     = filter_var(trim($this->input->post('cycle_mode', TRUE)), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $check_in       = filter_var(trim($this->input->post('check_in')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH)==''?NULL:filter_var(trim($this->input->post('check_in')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $check_out      = filter_var(trim($this->input->post('check_out')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH)==''?NULL:filter_var(trim($this->input->post('check_out')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $from           = filter_var(trim($this->input->post('from', TRUE)), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $to             = filter_var(trim($this->input->post('to', TRUE)), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $description    = filter_var(trim($this->input->post('description', TRUE)), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

        $shf_idx        = $this->secure->dec($shift_idx);
        $employee_id    = $this->secure->dec($eEmployee_id);

        $fromTime       = strtotime($from);
        $toTime         = strtotime($to);

        $dataInsert = '';
        $dataUpdate = '';
        $insertData = "";
        $updateData = "";
        $Now = date("Y-m-d H:i:s");
        // Loop dari start_date ke end_date
        for ($i = $fromTime; $i <= $toTime; $i = strtotime('+1 day', $i)) {
            // Format tanggal menjadi YYYY-MM-DD
            $current_date = date('Y-m-d', $i);

            // Memeriksa status kerja/libur pada tanggal tersebut
            $status = $this->getShiftStatus($current_date, (int)$work_days, (int)$off_days, $cycle_mode);

            $cekExsist = $this->mShift->cekExsistDynamic($employee_id, $shf_idx, $current_date);
            if(empty($cekExsist)){
                $dataInsert .= "($shf_idx, '".$current_date."', $employee_id, $cycle_mode, '$check_in', '$check_out', $status, '$description', $this->office, $this->idx, '$Now'),";
            }else{
                $dataUpdate .= "($cekExsist->idx, $cycle_mode, '$check_in', '$check_out', $status, '$description', $this->idx, '$Now'),";
            }
        }

        if(!empty($dataInsert)){
            $dataInsert = substr($dataInsert,0,strlen($dataInsert)-1);
            $insertData .= "INSERT INTO dynamic_shift (shift_idx, `date`, employee_id, cycle_mode, check_in, check_out, work_day, `description`, company_idx, created_by, created_on) VALUES $dataInsert";
        }

        if(!empty($dataUpdate)){
            $dataUpdate = substr($dataUpdate,0,strlen($dataUpdate)-1);
            $updateData .= "INSERT INTO dynamic_shift (idx, cycle_mode, check_in, check_out, work_day, `description`, modified_by, modified_on) VALUES $dataUpdate ON DUPLICATE KEY UPDATE cycle_mode=VALUES(cycle_mode), check_in=VALUES(check_in), check_out=VALUES(check_out), work_day=VALUES(work_day), `description`=VALUES(`description`), modified_by=VALUES(modified_by), modified_on=VALUES(modified_on)";
        }
    
        $cek = $this->mShift->insertCycle($insertData, $updateData);
        if($cek == true){
            $msg['success'] = true;
            $msg['text']    = 'Data Siklus di tambahkan.';
            echo json_encode($msg);die;
        }else{
            $msg['status']  = true;
            $msg['text']    = 'Error Connection,<br>Please try again later.';
            echo json_encode($msg);die;
        }
    }
    public function changeStatus()
    {
        $idx = $this->secure->dec($this->input->post('tId'));
        $id_status = $this->input->post('tClCode');
        $csrfToken = validate_csrf_token();
        // var_dump($csrfToken);die;
        if($csrfToken == FALSE){
            $msg['status']   = true;
            $msg['text']     = 'Please refresh page and try again.!';
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
        $cek = $this->mPos->changeStatus($data, $idx);
        if($cek == true)
        {
            $msg['type']    = 'change';
            $msg['success'] = true;
            echo json_encode($msg);die;
        }else{
            $msg['status']   = true;
            echo json_encode($msg);die;
        }
    }

    public function deleteToleran()
    {
        $id = $this->input->post('id');
        $idx = $this->secure->dec($id);
        $csrfToken = validate_csrf_token();
        // var_dump($csrfToken);die;
        if($csrfToken == FALSE){
            $msg['status']   = true;
            $msg['text']     = 'Please refresh page and try again.!';
            echo json_encode($msg);die;
        }
        $cek = $this->mShift->deleteToleran($idx);
        if($cek == true)
        {
            $msg['success'] = true;
            $msg['text']    = 'Hapus toleransi sukses.';
            echo json_encode($msg);die;
        }else{
            $msg['status']   = true;
            $msg['text']    = 'Gagal hapus toleransi.';
            echo json_encode($msg);die;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Other Function helper
    |--------------------------------------------------------------------------
    |
    | Note: .
    |
    */
    // Fungsi untuk menghasilkan jadwal shift secara dinamis
    public function getShiftSchedule() {
        $start_date = $this->input->post('start_date');
        $employee_id = empty($this->input->post('employee_id'))?0:$this->secure->dec($this->input->post('employee_id'));
        
        $schedule = [
            [
                'id' => null,
                'title' => null,
                'description' => null,
                'start' => null,
                'end' => null,
                'work_day' => null
            ]
        ]; // Array untuk menyimpan jadwal sementara
        $getCycle = $this->mShift->getCycle($employee_id, $start_date);
        // var_dump('<pre>');var_dump($getCycle);die;
        if(empty($getCycle)){
            echo json_encode($schedule);die;
        }
        
        foreach($getCycle as $c){
            $schedule[] = [
                'id' => $c->idx,
                'title' => $c->work_day==1?$c->check_in.'-'.$c->check_out:'Libur',
                'description' => $c->work_day==1?$c->check_in.' - '.$c->check_out:'Libur',
                'start' => $c->date,
                'end' => '',
                'work_day' => $c->work_day
            ];
        }

        // echo $text;
        echo json_encode($schedule);die;
    }

    // Fungsi untuk menghasilkan jadwal shift secara dinamis
    public function generateShiftSchedule() {
        $start_date = $this->input->post('start_date');
        $work_days = $this->input->post('work_days');
        $off_days = $this->input->post('off_days');
        $group = empty($this->input->post('group'))?0:$this->input->post('group');
        $datac = [
            'start_date' => $start_date,
            'work_days' => $work_days,
            'off_days' => $off_days,
            'group' => $group
        ];
        // var_dump('<pre>');var_dump($datac);die;
        $thisMonth = date('Y-m-d', strtotime($start_date));
        $days = date('t', strtotime($start_date));
        $schedule = []; // Array untuk menyimpan jadwal sementara
        // $text = '';
        // Menampilkan status kerja/libur dari tanggal hari ini hingga 30 hari ke depan
        for ($i = 0; $i < (int)$days; $i++) {
            // Hitung tanggal yang akan diperiksa (hari ini + i hari)
            $check_date = date('Y-m-d', strtotime("+$i days", strtotime($thisMonth)));
            
            // Memeriksa status kerja/libur pada tanggal tersebut
            $status = $this->getShiftStatus($check_date, (int)$work_days, (int)$off_days, $group);

            // Menambahkan tanggal dan status ke array
            if($status==0){
                $schedule[] = [
                    'id' => strtotime($check_date),
                    'title' => 'Libur',
                    'start' => $check_date,
                    'end' => $check_date
                    // 'start' => date("Y-m-d", strtotime($check_date))."T".date("H:i:s", strtotime($check_date)),
                    // 'end' => date("Y-m-d", strtotime($check_date))."T".date("H:i:s", strtotime($check_date))
                ];
            }

            // $text .= "Tanggal: " . $check_date . " - Status: " . $status . "<br>";
        }

        // echo $text;
        echo json_encode($schedule);die;
    }

    // Fungsi untuk mendapatkan status kerja/libur pada tanggal tertentu berdasarkan pola dinamis
    function getShiftStatus($check_date, $work_days, $off_days, $group) {
        // $dataxx = [$check_date, $work_days, $off_days, $group];
        // var_dump('<pre>');var_dump($dataxx);die;
        // Mengubah tanggal mulai dan tanggal yang akan dicek menjadi timestamp
        if($group==0){
            $start_timestamp = strtotime('2024-01-01');
        }else{
            $start_timestamp = strtotime("+$group days", strtotime('2024-01-01'));
        }
        $check_timestamp = strtotime($check_date);

        // Hitung selisih hari antara tanggal mulai dan tanggal yang akan dicek
        $day_diff = ($check_timestamp - $start_timestamp) / (60 * 60 * 24);

        // Total panjang siklus kerja/libur
        $cycle_length = $work_days + $off_days;
        
        // Gunakan modulus dengan panjang siklus untuk menentukan status
        $day_mod = $day_diff % $cycle_length;

        // Jika berada dalam rentang hari kerja, statusnya "Kerja", sisanya "Libur"
        if ($day_mod < $work_days) {
            return 1;
        } else {
            return 0;
        }
    }
}
/* End of file user.php */
/* Location: ./application/controllers/user.php */
