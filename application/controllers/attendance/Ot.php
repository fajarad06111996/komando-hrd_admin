<?php
defined('BASEPATH') or exit('No direct script access allowed');
use Nullix\CryptoJsAes\CryptoJsAes;

class Ot extends AUTH_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('attendance/M_ot', 'mOt');
        $this->load->model('ModelGenId');
        $this->load->library('security_function');
        $this->link	    = site_url('attendance/').strtolower(get_class($this));
        $this->filename = strtolower(get_class($this));
        $this->office   = $this->secure->dec($this->session->userdata('JToffice_id'));
        $this->tOffice  = $this->secure->dec($this->session->userdata('JToffice_type'));
        $this->hub      = $this->secure->dec($this->session->userdata('JThub_id'));
        $this->idx      = $this->secure->dec($this->session->userdata('JTidx'));
        $this->username = $this->session->userdata('JTuser_id');
        $this->enkey    = $this->config->item('encryption_key');
        $this->mesin1   = $this->config->item('mesin1');
		$this->mesin2   = $this->config->item('mesin2');
    }
    public function index()
    {
        $access = $this->security_function->permissions($this->filename . "-r");
        if (empty($access)) die("<center><h1>You can not get the right to access this module</h1></center>");
        if(!empty($this->security_function->permissions($this->filename . "-w"))){
            $data['write']  = '';
            // $data['write']  = '<a href="javascript:void(0);" id="bSync" class="pull-right text-white small" title="Syncron Mesin Absen" data-placement="right" data-popup="tooltip"><i class="icon-spinner10"></i></a>';
            $data['lock']   = 1;
        }else{
            $data['write']  = '';
            $data['lock']   = 0;
        }
        $data['employee']   = $this->mOt->getEmployee();
        $data['formact']    = $this->link.'/getOvertime';
        $data['filename']   = $this->filename;
        $data['judul']      = 'Presensi';
        $data['page']       = 'Presensi';
        $data["link"]       = $this->link;
        $data['post']       = 0;
        $params             = '{"link": "'.$this->link.'", "csrf": "'.$this->session->csrf_token.'", "permW": "'.$this->security_function->permissions($this->filename . "-w").'", "permC": "'.$this->security_function->permissions($this->filename . "-c").'", "permX": "'.$this->security_function->permissions($this->filename . "-x").'", "base_url": "'.base_url().'", "post": 0, "employee_id": 0}';
        // encrypt
        $encrypted          = CryptoJsAes::encrypt($params, $this->enkey);
        $data['params']     = $encrypted;
        $this->template->views('attendance/v_ot', $data);
    }

    function getOvertime(){
        $this->form_validation->set_rules('tStartdate', 'Start Date', 'trim|required');
        $this->form_validation->set_rules('tUntildate', 'End Date', 'trim|required');
        if ($this->form_validation->run() == false) {
            $error = validation_errors();
            $error = str_replace("\r", '', $error);
            $error = str_replace("\n", '', $error);
            $error = htmlentities($error);
            $this->session->set_flashdata('message', "Swal.fire({
                icon: 'error',
                title: 'Oops...',
                html: '".$error."'
            })");
            redirect('attendance/ot');die;
        }
        
        $csrfToken  = validate_csrf_token();
        if($csrfToken == false){
            $this->session->set_flashdata('message',"notiferror_a('Token expired,<br>Refresh page & try again.')");
            redirect('attendance/ot');die;
        }
        ini_set ('max_execution_time', '0'); 
        ini_set ('memory_limit', '256M'); 
        $tAwal              = date("Y-m-d", strtotime($this->input->post('tStartdate')));
        $tAkhir   	        = date("Y-m-d", strtotime($this->input->post('tUntildate')));
        $employee_id        = $this->secure->dec($this->input->post('tEmployee'));
        $data['filename']   = $this->filename;
        $data["link"]       = $this->link;
        $data['userdata']   = $this->userdata;
        $data['formact']    = $this->link.'/getOvertime';
        $result             = $this->mOt->getOtEmployeeAll($tAwal,$tAkhir,$employee_id);
        // $cek                = $this->createDateRangeArray($tAwal, $tAkhir);
        // var_dump('<pre>');var_dump($result);die;
        $format_data = [];
        foreach($result as $i => $v){
            $hari = strtolower(date('l', strtotime($v['calendar_date'])));
            $tanggal = $v['calendar_date'];
            // var_dump('<pre>');var_dump();die;
            if($v['shift_mode']==1){
                $holiday = $this->mOt->check_holiday_cycle($tanggal, $v['employee_id'], $v['shift_idx']);
                $offDay = $v['dynamic_check_out'];
                $shift = $v['dynamic_check_out'];
            }else{
                $holiday = $this->mOt->check_holiday($tanggal);
                $offDay = $v[$hari.'_out'];
                $shift = $v['start_overtime'];
            }
            $employee_name = $v['employee_name'];
            $ot_id = $this->secure->enc($v['ot_id']);
            $emp_idx = $this->secure->enc($v['employee_id']);
            $masuk = $v['check_in'];
            $oTime = $v['over_time'];
            $oTimeFix = $v['overtime_hour'];
            $att_idx = $this->secure->enc($v['att_idx']);
            $jamMasuk = date('H:i', strtotime($v['check_in']));
            $masukDate = date('Y-m-d', strtotime($v['check_in']));
            $mCekin = date('m', strtotime($v['check_in']));
            $yCekin = date('Y', strtotime($v['check_in']));
            $keluar = $v['check_out'];
            $jamKeluar = date('H:i', strtotime($v['check_out']));
            $jamKeluarConf = date('H:i', strtotime($v['check_out_confirm']));
            $jamKeluarx = date('H:i:s', strtotime($v['check_out']));
            $todayServer = date('Y-m-d');
            $point_checkin = $v['point_center_check_in'];
            $point_checkout = $v['point_center_check_out'];
            $oTimeView = $oTime>0?"<br><span class='text-info'>Lembur : <span class='text-danger'>$oTime</span> Jam</span>":'';
            $oTimeHoliday = "<br><span class='text-info'>Lembur <span class='text-danger'>Piket</span></span>";
            $piket = 0;
            if($holiday>0){
                $piket = 1;
            }else{
                if($offDay==null){
                    $piket = 1;
                }
            }
            $countPiket = 0;
            if($piket==1){
                $cekJumlahPiket = $this->mOt->countPiket($v['employee_id'], $mCekin, $yCekin);
                if(empty($cekJumlahPiket) && $cekJumlahPiket != 0){
                    $this->session->set_flashdata('message',"notiferror_a('Count Piket Error.')");
                    redirect('attendance/ot');die;
                }
                $countPiket = $cekJumlahPiket;
                $shiftx = $v['piket_in'];
                $popupButton = "Lembur piket";
            }else{
                $popupButton = "Lembur $oTimeFix Jam \n $shiftx-$jamKeluarConf";
                $shiftx = $shift;
            }
            if($v['status_overtime']==1){
                $confirmOt = "<br><h5 class='m-0'><a href='javascript:void(0);' class='bInfo text-center badge badge-success' data-popup='tooltip' title='$popupButton' data-placement='right'>Terkonfirmasi</a></h5>";
            }else{
                $confirmOt = "<br><h5 class='m-0'><a href='javascript:void(0);' id='$att_idx' cout='$jamKeluarx' mot='$ot_id' cout-date='$masukDate' ot-hour='$oTime' countpiket='$countPiket' ch-shift='$shiftx' empid='$emp_idx' empname='$employee_name' piket='$piket' class='bConfirm text-center badge badge-warning' data-popup='tooltip' title='Konfirmasi Lembur' data-placement='right'>Konfirmasi</a></h5>";
            }
            if($oTime>0){
                if($holiday>0){
                    if($offDay==null){
                        if($masuk==null){
                            if($keluar==null){
                                $absenView = "<span class='text-danger'>-</span>";
                                $status = "libur";
                            }else{
                                $absenView = "<span class='badge badge-warning'>Lupa Cekin</span> - <a href='https://maps.google.com?q=$point_checkout' class='text-dark' target='_BLANK'>$jamKeluar</a>$oTimeHoliday$confirmOt";
                                $status = "lupa cekin";
                            }
                        }else{
                            if($keluar==null){
                                if($todayServer==$masukDate){
                                    $absenView = "<a href='https://maps.google.com?q=$point_checkin' class='text-dark' target='_BLANK'>$jamMasuk</a> - <span class='badge badge-warning'>Sedang Berlangsung</span>$oTimeHoliday$confirmOt";
                                    $status = "lembur";
                                }else{
                                    $absenView = "<a href='https://maps.google.com?q=$point_checkin' class='text-dark' target='_BLANK'>$jamMasuk</a> - <span class='badge badge-warning'>Lupa Cekout</span>$oTimeHoliday$confirmOt";
                                    $status = "lupa cekout";
                                }
                            }else{
                                $absenView = "<a href='https://maps.google.com?q=$point_checkin' class='text-dark' target='_BLANK'>$jamMasuk</a> - <a href='https://maps.google.com?q=$point_checkout' target='_BLANK'>$jamKeluar</a>$oTimeHoliday$confirmOt";
                                $status = "lembur";
                            }
                        }
                    }else{
                        if($masuk==null){
                            if($keluar==null){
                                $absenView = "<span class='badge badge-danger'>Libur</span>";
                                $status = "libur";
                            }else{
                                $absenView = "<span class='badge badge-warning'>Lupa Cekin</span> - <a href='https://maps.google.com?q=$point_checkout' class='text-dark' target='_BLANK'>$jamKeluar</a>$oTimeHoliday$confirmOt";
                                $status = "lupa cekin";
                            }
                        }else{
                            if($keluar==null){
                                if($todayServer==$masukDate){
                                    $absenView = "<a href='https://maps.google.com?q=$point_checkin' class='text-dark' target='_BLANK'>$jamMasuk</a> - <span class='badge badge-warning'>Sedang Berlangsung</span>$oTimeHoliday$confirmOt";
                                    $status = "lembur";
                                }else{
                                    $absenView = "<a href='https://maps.google.com?q=$point_checkin' class='text-dark' target='_BLANK'>$jamMasuk</a> - <span class='badge badge-warning'>Lupa Cekout</span>$oTimeHoliday$confirmOt";
                                    $status = "lupa cekout";
                                }
                            }else{
                                $absenView = "<a href='https://maps.google.com?q=$point_checkin' class='text-dark' target='_BLANK'>$jamMasuk</a> - <a href='https://maps.google.com?q=$point_checkout' target='_BLANK'>$jamKeluar</a>$oTimeHoliday$confirmOt";
                                $status = "lembur";
                            }
                        }
                    }
                }else{
                    if($offDay==null){
                        if($masuk==null){
                            if($keluar==null){
                                $absenView = "<span class='text-danger'>-</span>";
                                $status = "libur";
                            }else{
                                $absenView = "<span class='badge badge-warning'>Lupa Cekin</span> - <a href='https://maps.google.com?q=$point_checkout' class='text-dark' target='_BLANK'>$jamKeluar</a>$oTimeHoliday$confirmOt";
                                $status = "lupa cekin";
                            }
                        }else{
                            if($keluar==null){
                                if($todayServer==$masukDate){
                                    $absenView = "<a href='https://maps.google.com?q=$point_checkin' class='text-dark' target='_BLANK'>$jamMasuk</a> - <span class='badge badge-warning'>Sedang Berlangsung</span>$oTimeHoliday$confirmOt";
                                    $status = "lembur";
                                }else{
                                    $absenView = "<a href='https://maps.google.com?q=$point_checkin' class='text-dark' target='_BLANK'>$jamMasuk</a> - <span class='badge badge-warning'>Lupa Cekout</span>$oTimeHoliday$confirmOt";
                                    $status = "lupa cekout";
                                }
                            }else{
                                $absenView = "<a href='https://maps.google.com?q=$point_checkin' class='text-dark' target='_BLANK'>$jamMasuk</a> - <a href='https://maps.google.com?q=$point_checkout' class='text-dark' target='_BLANK'>$jamKeluar</a>$oTimeHoliday$confirmOt";
                                $status = "hadir";
                            }
                        }
                    }else{
                        if($masuk==null){
                            if($keluar==null){
                                $absenView = "<span class='text-danger'>-</span>";
                                $status = "mangkir";
                            }else{
                                $absenView = "<span class='badge badge-warning'>Lupa Cekin</span> - <a href='https://maps.google.com?q=$point_checkout' class='text-dark' target='_BLANK'>$jamKeluar</a>$oTimeView";
                                $status = "lupa cekin";
                            }
                        }else{
                            if($keluar==null){
                                if($todayServer==$masukDate){
                                    $absenView = "<a href='https://maps.google.com?q=$point_checkin' class='text-dark' target='_BLANK'>$shift</a> - <span class='badge badge-warning'>Sedang Berlangsung</span>$oTimeView";
                                    $status = "hadir";
                                }else{
                                    $absenView = "<a href='https://maps.google.com?q=$point_checkin' class='text-dark' target='_BLANK'>$shift</a> - <span class='badge badge-warning'>Lupa Cekout</span>$oTimeView";
                                    $status = "lupa cekout";
                                }
                            }else{
                                $absenView = "<a href='https://maps.google.com?q=$point_checkin' class='text-dark' target='_BLANK'>$shift</a> - <a href='https://maps.google.com?q=$point_checkout' class='text-dark' target='_BLANK'>$jamKeluar</a>$oTimeView$confirmOt";
                                $status = "hadir";
                            }
                        }
                    }
                }
            }else{
                if($holiday>0){
                    if($offDay==null){
                        if($masuk==null){
                            if($keluar==null){
                                $absenView = "<span class='text-danger'>-</span>";
                                $status = "libur";
                            }else{
                                $absenView = "<span class='badge badge-warning'>Lupa Cekin</span> - <a href='https://maps.google.com?q=$point_checkout' class='text-dark' target='_BLANK'>$jamKeluar</a>$oTimeHoliday$confirmOt";
                                $status = "lupa cekin";
                            }
                        }else{
                            if($keluar==null){
                                if($todayServer==$masukDate){
                                    $absenView = "<a href='https://maps.google.com?q=$point_checkin' class='text-dark' target='_BLANK'>$jamMasuk</a> - <span class='badge badge-warning'>Sedang Berlangsung</span>$oTimeHoliday$confirmOt";
                                    $status = "lembur";
                                }else{
                                    $absenView = "<a href='https://maps.google.com?q=$point_checkin' class='text-dark' target='_BLANK'>$jamMasuk</a> - <span class='badge badge-warning'>Lupa Cekout</span>$oTimeHoliday$confirmOt";
                                    $status = "lupa cekout";
                                }
                            }else{
                                $absenView = "<a href='https://maps.google.com?q=$point_checkin' class='text-dark' target='_BLANK'>$jamMasuk</a> - <a href='https://maps.google.com?q=$point_checkout' target='_BLANK'>$jamKeluar</a>$oTimeHoliday$confirmOt";
                                $status = "lembur";
                            }
                        }
                    }else{
                        if($masuk==null){
                            if($keluar==null){
                                $absenView = "<span class='badge badge-danger'>Libur</span>";
                                $status = "libur";
                            }else{
                                $absenView = "<span class='badge badge-warning'>Lupa Cekin</span> - <a href='https://maps.google.com?q=$point_checkout' class='text-dark' target='_BLANK'>$jamKeluar</a>$oTimeHoliday$confirmOt";
                                $status = "lupa cekin";
                            }
                        }else{
                            if($keluar==null){
                                if($todayServer==$masukDate){
                                    $absenView = "<a href='https://maps.google.com?q=$point_checkin' class='text-dark' target='_BLANK'>$jamMasuk</a> - <span class='badge badge-warning'>Sedang Berlangsung</span>$oTimeHoliday$confirmOt";
                                    $status = "lembur";
                                }else{
                                    $absenView = "<a href='https://maps.google.com?q=$point_checkin' class='text-dark' target='_BLANK'>$jamMasuk</a> - <span class='badge badge-warning'>Lupa Cekout</span>$oTimeHoliday$confirmOt";
                                    $status = "lupa cekout";
                                }
                            }else{
                                $absenView = "<a href='https://maps.google.com?q=$point_checkin' class='text-dark' target='_BLANK'>$jamMasuk</a> - <a href='https://maps.google.com?q=$point_checkout' target='_BLANK'>$jamKeluar</a>$oTimeHoliday$confirmOt";
                                $status = "lembur";
                            }
                        }
                    }
                }else{
                    if($offDay==null){
                        if($masuk==null){
                            if($keluar==null){
                                $absenView = "<span class='text-danger'>-</span>";
                                $status = "libur";
                            }else{
                                $absenView = "<span class='badge badge-warning'>Lupa Cekin</span> - <a href='https://maps.google.com?q=$point_checkout' class='text-dark' target='_BLANK'>$jamKeluar</a>$oTimeHoliday$confirmOt";
                                $status = "lupa cekin";
                            }
                        }else{
                            if($keluar==null){
                                if($todayServer==$masukDate){
                                    $absenView = "<a href='https://maps.google.com?q=$point_checkin' class='text-dark' target='_BLANK'>$jamMasuk</a> - <span class='badge badge-warning'>Sedang Berlangsung</span>$oTimeHoliday$confirmOt";
                                    $status = "lembur";
                                }else{
                                    $absenView = "<a href='https://maps.google.com?q=$point_checkin' class='text-dark' target='_BLANK'>$jamMasuk</a> - <span class='badge badge-warning'>Lupa Cekout</span>$oTimeHoliday$confirmOt";
                                    $status = "lupa cekout";
                                }
                            }else{
                                $absenView = "<a href='https://maps.google.com?q=$point_checkin' class='text-dark' target='_BLANK'>$jamMasuk</a> - <a href='https://maps.google.com?q=$point_checkout' target='_BLANK'>$jamKeluar</a>$oTimeHoliday$confirmOt";
                                $status = "lembur";
                            }
                        }
                    }else{
                        $absenView = "<span class='text-danger'>-</span>";
                        $status = "mangkir";
                    }
                }
            }
            $format_data[$employee_name][$tanggal] = [$absenView];
        }
        if(!empty($this->security_function->permissions($this->filename . "-w"))){
            $data['write']  = '';
            // $data['write']  = '<a href="javascript:void(0);" id="bSync" class="pull-right text-white small" title="Syncron Mesin Absen" data-placement="right" data-popup="tooltip"><i class="icon-spinner10"></i></a>';
            $data['lock']   = 1;
        }else{
            $data['write']  = '';
            $data['lock']   = 0;
        }
        $data['result']     = $format_data;
        // var_dump('<pre>');var_dump($format_data);die;
        $data['awal'] 	    = $this->input->post('tStartdate');
        $data['akhir'] 	    = $this->input->post('tUntildate');
        $employee_enid      = $this->input->post('tEmployee');
        $data['employee_id']    = $employee_enid;
        $data['post']       = 1;
        $params             = '{"link": "'.$this->link.'", "csrf": "'.$this->session->csrf_token.'", "permW": "'.$this->security_function->permissions($this->filename . "-w").'", "permC": "'.$this->security_function->permissions($this->filename . "-c").'", "permX": "'.$this->security_function->permissions($this->filename . "-x").'", "awal": "'.$tAwal.'", "akhir": "'.$tAkhir.'", "post": 1, "employee_id": "'.$employee_enid.'"}';
        $encrypted          = CryptoJsAes::encrypt($params, $this->enkey);
        $data['params']     = $encrypted;
        $this->template->views('attendance/v_ot', $data);
    }
    // CONTOH LAIN CONTROLLER2 POST DATATABLE SERVERSIDE
    function get_ajax()
    {
        $csrfName   = $this->input->post('CSRFToken');
        $csrfToken  = $this->session->csrf_token;
        // var_dump($csrfName);
        // var_dump($csrfToken);
        // die;
        if($csrfToken != $csrfName){
            $list = null;
        }else{
            $list = $this->mOrg->get_datatables();
        }
        $data = array();
        $no = @$_POST['start'];
        foreach ($list as $item) {
            $idx            = $this->secure->enc( $item->idx);
            $presence_idx   = empty($item->presence_idx)?0:$this->secure->enc($item->presence_idx);
            if(!empty($this->security_function->permissions($this->filename . "-w"))){
            }else{
            }
            if(!empty($this->security_function->permissions($this->filename . "-c"))){
                $change = "<h5 class='m-0'><a href='javascript:void(0);' p_id='$presence_idx' id='$idx' class='bEdit text-center badge badge-info' data-popup='tooltip' title='Update Presensi' data-placement='right'><i class='icon-pencil5'></i></a></h5>";
                if($item->status == 1)
                {
                    $statusU = "<h5><a href='javascript:void(0);' id='$idx' data='$item->employee_name' code='1' class='bStatus text-center badge badge-success' data-popup='tooltip' title='Ubah Status' data-placement='right'>Aktif</a></h5>";
                // $statusU = '<span class="badge badge-success">Aktif</span>';
                } else {
                    $statusU = "<h5><a href='javascript:void(0);' id='$idx' data='$item->employee_name' code='0' class='bStatus text-center badge badge-danger' data-popup='tooltip' title='Ubah Status' data-placement='right'>Non Aktif</a></h5>";
                // $statusU = '<span class="badge badge-danger">Non Aktif</span>';
                }
            }else{
                $change = '<h5 class="m-0"><span class="badge badge-warning bLockStatus"><i class="icon-lock" data-popup="tooltip" title="Update Presensi Terkunci" data-placement="right"></i></span></h5>';
                if($item->status == 1)
                {
                    $statusU = "<span class='text-center badge badge-success'>Aktif</span>";
                }else{
                    $statusU = "<span class='text-center badge badge-danger'>Non Aktif</span>";
                }
            }
            if(!empty($this->security_function->permissions($this->filename . "-x"))){
                $execute  = "<h5 class='m-0'><a href='javascript:void(0);' id='$idx' data='$item->employee_name' class='bDelete text-center badge badge-danger' data-popup='tooltip' title='Hapus Karyawan' data-placement='right'><i class='icon-bin'></i></a></h5>";
            }else{
                $execute  = '<h5 class="m-0"><span class="badge badge-warning"><i class="icon-lock" data-popup="tooltip" title="Hapus Karyawan Terkunci" data-placement="right"></i></span></h5>';
            }
            $img = empty($item->photo)?base_url()."/assets/images/no_image.png":$item->photo;
            $no++;
            $row = array();
            $row[] = $no.".";
            $row[] = "<div class='btn-group'>$change</div>";
            // $row[] = "<div class='btn-group'>
            //             <button type='button' class='btn btn-danger btn-sm dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'><i class='icon-more2'></i></button>
            //             <div class='dropdown-menu p-2' style='min-width: auto !important;'>
            //                 <div class='btn-group'>$change$execute</div>
            //             </div>
            //         </div>";
            $row[] = $statusU;
            $row[] = "<a href='$img' data-fancybox data-caption='$item->employee_name'><img src='$img' width='100px' class='bImage' caption='$item->employee_name' data-popup='tooltip' title='Click to preview' data-placement='right'>";
            $row[] = $item->employee_name." ".$item->presence_idx;
            $row[] = $item->employee_code;
            $row[] = $item->department_name;
            $row[] = $item->designation_name;
            $row[] = "<h5><span class='badge badge-success'>$item->shift</span></h5>";
            $row[] = empty($item->in_absen)?"-":$item->in_absen;
            $row[] = empty($item->out_absen)?"-":$item->out_absen;
            // add html for action
            $data[] = $row;
        }
        $output = array(
            "draw"            => @$_POST['draw'],
            "recordsTotal"    => $this->mOrg->count_all(),
            "recordsFiltered" => $this->mOrg->count_filtered(),
            "data"            => $data,
        );
        // output to json format
        echo json_encode($output);
    }
    public function addOrganization()
    {
        $this->form_validation->set_rules('department_code', 'Kode Organisasi', 'trim|required',[
            'required' => 'Kode Organisasi wajib di isi.'
        ]);
        $this->form_validation->set_rules('department_name', 'Nama Organisasi', 'trim|required',[
            'required' => 'Nama Organisasi wajib di isi.'
        ]);
        $this->form_validation->set_rules('description', 'Deskripsi', 'trim|required',[
            'required' => 'Deskripsi wajib di isi.'
        ]);
        if ($this->form_validation->run() == false) {
            $msg['error']   = true;
            $msg['message'] = validation_errors();
            echo json_encode($msg);die;
        }
        $csrf = cek_csrf_return();
        if($csrf==false){
            $msg['status']  = true;
            $msg['text']    = 'Token invalid.';
            echo json_encode($msg);die;
        }
        // ini_set ('max_execution_time', '0');
        // ini_set ('memory_limit', '256M');
        $department_code    = trim($this->input->post('department_code', TRUE));
        $department_name    = trim($this->input->post('department_name', TRUE));
        $dept_head_idx      = $this->secure->dec(trim($this->input->post('dept_head_idx', TRUE)));
        $description        = trim($this->input->post('description', TRUE));
        $status             = trim($this->input->post('tStatus'));

        $cekDeptCode        = $this->mOrg->cekDeptCode($department_code);
        if($cekDeptCode){
            $msg['status']  = true;
            $msg['text']    = 'Kode '.$department_code.' Sudah ada.';
            echo json_encode($msg);die;
        }

        $data = [
            'department_code'   => $department_code,
            'department_name'   => $department_name,
            'dept_head_idx'     => $dept_head_idx,
            'description'       => $description,
            'status'            => $status,
            'created_by'        => $this->idx,
            'created_on'        => date("Y-m-d H:i:s")
        ];
        $cek = $this->mOrg->insertDepartment($data);
        if($cek == true){
            $msg['success'] = true;
            $msg['text']    = 'Organisasi berhasil di tambahkan.';
            echo json_encode($msg);die;
        }else{
            $msg['status']  = true;
            $msg['text']    = 'Error Connection,<br>Please try again later.';
            echo json_encode($msg);die;
        }
    }
    public function updatePresence($id="")
    {
        $this->form_validation->set_rules('in_absen', 'In', 'trim|required',[
            'required' => 'In wajib di isi.'
        ]);
        $this->form_validation->set_rules('out_absen', 'Out', 'trim|required',[
            'required' => 'Out wajib di isi.'
        ]);
        if ($this->form_validation->run() == false) {
            $msg['error']   = true;
            $msg['message'] = validation_errors();
            echo json_encode($msg);die;
        }
        $csrf = cek_csrf_return();
        if($csrf==false){
            $msg['status']  = true;
            $msg['text']    = 'Token invalid.';
            echo json_encode($msg);die;
        }
        // ini_set ('max_execution_time', '0');
        // ini_set ('memory_limit', '256M');
        $in_absen       = trim($this->input->post('in_absen', TRUE));
        $out_absen      = trim($this->input->post('out_absen', TRUE));
        $note           = trim($this->input->post('note', TRUE));

        if(empty($id)){
            $data = [
                'in'    => $in_absen,
                'out'   => $out_absen,
                'dept_head_idx'     => $dept_head_idx,
                'description'       => $description,
                'status'            => $status,
                'modified_by'       => $this->idx,
                'modified_on'       => date("Y-m-d H:i:s")
            ];
        }else{
            $idx = $this->secure->dec($id);
        }

        $data = [
            'department_code'   => $department_code,
            'department_name'   => $department_name,
            'dept_head_idx'     => $dept_head_idx,
            'description'       => $description,
            'status'            => $status,
            'modified_by'       => $this->idx,
            'modified_on'       => date("Y-m-d H:i:s")
        ];
        $cek = $this->mOrg->updateDepartment($data, $idx);
        if($cek == true){
            $msg['success'] = true;
            $msg['text']    = 'Organisasi berhasil diubah.';
            echo json_encode($msg);die;
        }else{
            $msg['status']  = true;
            $msg['text']    = 'Error Connection,<br>Please try again later.';
            echo json_encode($msg);die;
        }
    }
    public function syncPresence()
    {
        $from = $this->input->post('from');
        $to = $this->input->post('to');
        $trx_id = $this->ModelGenId->genIdUnlimited('TRXFS', $this->idx);
        $data = [
            'trans_id' => $trx_id,
            'cloud_id' => $this->mesin1,
            'start_date' => $from,
            'end_date' => $to
        ];
        $cek = $this->mOt->syncPresence($data);
        if(empty($cek)){
            $msg['status']  = false;
            $msg['text']    = 'Error Connection<br>Please try again later.';
            echo json_encode($msg);die;
        }
        
        if($cek->success == false){
            $msg['status']  = false;
            $msg['text']    = $cek->message;
            echo json_encode($msg);die;
        }

        $thatResult = $this->removeDuplicatesByNameAndDate($cek->data);
        echo json_encode($thatResult);die;
        // var_dump('<pre>');var_dump(json_encode($thatResult));die;
        if($cek->success == true){
            $insert = '';
            $update = '';
            $now = date('Y-m-d H:i:s');
            $id_login = $this->idx;
            foreach($cek->data as $x){
                $getUser = $this->mOt->cekAbsenNow($x->pin, $this->mesin1, date('Y-m-d'));
                if(empty($getUser)){
                    $getShift = $this->mWh->getShift((int)$getUser->office_shift);
                    $nowDay = strtolower(date('l')).'_in';
                    $att_id = $this->M_genId->genIdUnlimited('ETTK', 1);
                    $att_no = "ETTK" . date("y") . date("m") . str_pad($att_id, 12, "0", STR_PAD_LEFT);
                    $scanDate = date('Y-m-d', strtotime($x->scan_date));
                    $targetIn = $scanDate .' '. $getShift[$nowDay];
                    $insert .= "($att_id, '$att_no', '$scanDate', $getUser->employee_id, $getUser->office_shift, '$targetIn', '$x->scan_date', '$x->scan_date', $x->verify, $x->company_idx, $x->office_idx, 1, '$now', $id_login),";
                }else{
                    $update = "()";
                }
                echo json_encode($getUser);die;
            }

            $insert = substr($insert,0,strlen($insert)-1);
            $update = substr($update,0,strlen($update)-1);
            $insertAttEmpl = "INSERT INTO attendance_employee (attendance_id, attendance_no, attendance_date, employee_id, shift_idx, target_in, check_in, stamp_in, verify, company_idx, office_idx, status, created_on, created_by) values $insert";
            $updateAttEmpl = "INSERT INTO attendance_employee (idx, target_out, check_out, stamp_out, verify, status, modified_on, modified_by) values $update ON DUPLICATE KEY UPDATE target_out=VALUES(target_out),check_out=VALUES(check_out),stamp_out=VALUES(stamp_out),verify=VALUES(verify),status=VALUES(status),modified_on=VALUES(modified_on),modified_by=VALUES(modified_by)";
        }
        var_dump('<pre>');var_dump($cek);die;
    }
    public function confirmOt()
    {
        $this->form_validation->set_rules('att_idx', 'Attendance Id', 'trim|required',[
            'required' => 'Attendance Id wajib di isi.'
        ]);
        $this->form_validation->set_rules('ch_out_old', 'Jam Pulang Lama', 'trim|required',[
            'required' => 'Jam Pulang Lama wajib di isi.'
        ]);
        $this->form_validation->set_rules('ch_out_date', 'Tanggal Jam Pulang', 'trim|required',[
            'required' => 'Tanggal Jam Pulang wajib di isi.'
        ]);
        $this->form_validation->set_rules('check_out', 'Jam Pulang Baru', 'trim|required',[
            'required' => 'Jam Pulang Baru wajib di isi.'
        ]);
        $this->form_validation->set_rules('ch_shift', 'Jam Pulang Standar', 'trim|required',[
            'required' => 'Jam mulai lembur belum di setting di menu Jadwal Kerja.'
        ]);
        $this->form_validation->set_rules('emp_id', 'ID Karyawan', 'trim|required',[
            'required' => 'ID Karyawan tidak ada.'
        ]);
        $this->form_validation->set_rules('ot_id', 'Kode Lembur', 'trim|required',[
            'required' => 'Kode Lembur belum di seting.'
        ]);
        $this->form_validation->set_rules('piket', 'Status Piket', 'trim|required',[
            'required' => 'Status Piket kosong.'
        ]);
        // $this->form_validation->set_rules('ot_hour', 'Jam Lembur', 'trim|required',[
        //     'required' => 'Jam Lembur wajib di isi.'
        // ]);
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
        
        $check_out          = trim($this->input->post('check_out', TRUE));
        $ot_hour            = trim($this->input->post('ot_hour', TRUE));
        $ch_out_old         = trim($this->input->post('ch_out_old', TRUE));
        $ch_out_date        = trim($this->input->post('ch_out_date', TRUE));
        $ch_shift           = trim($this->input->post('ch_shift', TRUE));
        $overtime_reason    = trim($this->input->post('overtime_reason', TRUE));
        $piket              = trim($this->input->post('piket', TRUE));
        $att_idx            = $this->secure->dec(trim($this->input->post('att_idx', TRUE)));
        $emp_id             = $this->secure->dec(trim($this->input->post('emp_id', TRUE)));
        $ot_id              = $this->secure->dec(trim($this->input->post('ot_id', TRUE)));

        if($piket==0){
            if(empty($ot_hour)){
                $msg['status']  = true;
                $msg['text']    = 'Jam Lembur wajib di isi.';
                echo json_encode($msg);die;
            }
        }

        $chekOutStd = $ch_out_date.' '.$ch_shift;
        $chekOut = $ch_out_date.' '.$check_out;
        $chekOutOld = $ch_out_date.' '.$ch_out_old;

        $mCekin = date('m', strtotime($ch_out_date));
        $yCekin = date('Y', strtotime($ch_out_date));

        $stampStd = strtotime($chekOutStd);
        $stampOut = strtotime($chekOut);

        $selisihDetik = $stampOut - $stampStd;

        $otHour = floor($selisihDetik / 3600);

        if($otHour > 0){
            $otHourFix = $otHour;
        }else{
            $otHourFix = 0;
        }

        $getEmp = $this->mOt->getEmployeeById($emp_id);
        if(empty($getEmp)){
            $msg['status']  = true;
            $msg['text']    = 'Karyawan tidak ditemukan.';
            echo json_encode($msg);die;
        }
        $umVal = (float)$getEmp->meal_allowance;
        $otVal = (float)$getEmp->overtime_allowance;
        $org_idx = (float)$getEmp->organization_idx;

        // Cari tanggal Senin pada minggu ini
        $hari_senin = date('Y-m-d', strtotime('monday this week', strtotime($ch_out_date)));

        // Cari tanggal Minggu pada minggu ini
        $hari_minggu = date('Y-m-d', strtotime('sunday this week', strtotime($ch_out_date)));

        $cekWeeklyPiket = $this->mOt->cekWeeklyPiket($emp_id, $hari_senin, $hari_minggu);

        
        $countWeeklyPiket = count($cekWeeklyPiket);
        // var_dump('<pre>');var_dump($countWeeklyPiket);die;

        $updateOldOt = "";
            
        $value_ot = 0;
        if($piket==1){
            $value_ot = $otVal;
            if($this->office==2){
                if($countWeeklyPiket<2){
                    if(!empty($cekWeeklyPiket)){
                        $oldIdx = "";
                        foreach($cekWeeklyPiket as $c){
                            $oldIdx .= $c->idx.",";
                        }
                        $oldIdx = substr($oldIdx,0,strlen($oldIdx)-1);
                        $updateOldOt .= "UPDATE attendance_employee SET value_overtime = 0 WHERE idx IN($oldIdx)";
                    }
                }
            }else{
                if($org_idx==6){
                    if($countWeeklyPiket<2){
                        if(!empty($cekWeeklyPiket)){
                            $oldIdx = "";
                            foreach($cekWeeklyPiket as $c){
                                $oldIdx .= $c->idx.",";
                            }
                            $oldIdx = substr($oldIdx,0,strlen($oldIdx)-1);
                            $updateOldOt .= "UPDATE attendance_employee SET value_overtime = 0 WHERE idx IN($oldIdx)";
                            $value_ot = $value_ot+17000;
                        }
                    }
                }
            }
            $dataUpdate = [
                'check_out_confirm'     => $ch_out_date.' '.$check_out,
                'status_overtime'       => 1,
                'status_piket'          => 1,
                'value_overtime'        => $value_ot,
                'reason_overtime'       => empty($overtime_reason)?'Lembur Piket':$overtime_reason,
                'overtime_hour'         => $otHourFix,
                'overtime_confirm_date' => date("Y-m-d H:i:s"),
                'modified_by'           => $this->idx,
                'modified_on'           => date("Y-m-d H:i:s")
            ];
        }else{
            $getVal = $this->mOt->getValueOt($ot_id);
            foreach($getVal as $gv){
                $minHour    = $gv->min_hour;
                $maxHour    = $gv->max_hour;
                $tov        = $gv->type_of_value;
                $val        = $gv->value;
                $stat_end   = $gv->status_end;
                $stat_um    = $gv->status_um;
                if($stat_end==1){
                    if($tov==1){
                        if($stat_um==1){
                            if($otHourFix>=$minHour){
                                $value_ot = $otHourFix*$umVal;
                            }
                        }else{
                            if($otHourFix>=$minHour){
                                $value_ot = $otHourFix*$val;
                            }
                        }
                    }else{
                        if($stat_um==1){
                            if($otHourFix>=$minHour){
                                $value_ot = 1*$umVal;
                            }
                        }else{
                            if($otHourFix>=$minHour){
                                $value_ot = 1*$val;
                            }
                        }
                    }
                }else{
                    if($tov==1){
                        if($stat_um==1){
                            if($otHourFix>=$minHour && $otHourFix<$maxHour){
                                $value_ot = $otHourFix*$umVal;
                            }
                        }else{
                            if($otHourFix>=$minHour && $otHourFix<$maxHour){
                                $value_ot = $otHourFix*$val;
                            }
                        }
                    }else{
                        if($stat_um==1){
                            if($otHourFix>=$minHour && $otHourFix<$maxHour){
                                $value_ot = 1*$umVal;
                            }
                        }else{
                            if($otHourFix>=$minHour && $otHourFix<$maxHour){
                                $value_ot = 1*$val;
                            }
                        }
                    }
                }
            }
            $dataUpdate = [
                'check_out_confirm'     => $ch_out_date.' '.$check_out,
                'status_overtime'       => 1,
                'value_overtime'        => $value_ot,
                'reason_overtime'       => empty($overtime_reason)?'Lembur Kerja':$overtime_reason,
                'overtime_hour'         => $otHourFix,
                'overtime_confirm_date' => date("Y-m-d H:i:s"),
                'modified_by'           => $this->idx,
                'modified_on'           => date("Y-m-d H:i:s")
            ];
        }
        
        $cek = $this->mOt->updateOt($dataUpdate, $att_idx, $updateOldOt);
        if($cek == true){
            $msg['success'] = true;
            $msg['text']    = 'Lembur berhasil di update.';
            echo json_encode($msg);die;
        }else{
            $msg['status']  = true;
            $msg['text']    = 'Error Connection,<br>Please try again later.';
            echo json_encode($msg);die;
        }
    }
    public function getDataAjaxRemote()
    {
        $search = $this->input->post('search');
        $results = $this->mOt->getDataAjaxRemote($search, 'data');
        $countresults = $this->mOt->getDataAjaxRemote($search, 'count');
        $selectajax[] = array(
            'id' => 0,
            'text' => "ALL"
        );
        foreach($results as $row){
            $selectajax[] = array(
                'id' => $this->secure->enc($row['employee_id']),
                'text' => $row['employee_name']
            );
        }
        $select['items'] = $selectajax;
        $select['total_count'] = $countresults;
        $this->output->set_content_type('application/json')->set_output(json_encode($select));
    }
    public function getDataAjaxRemoteId($id="")
    {
        $search         = $this->input->post('search');
        $selectajax[] = array();
        if(empty($id)){
            $countresults   = 1;
            $selectajax[] = array(
                'id' => 0,
                'text' => "ALL"
            );
        }else{
            if($id === "0"){
                $countresults   = 1;
                $selectajax[] = array(
                    'id' => 0,
                    'text' => "ALL"
                );
            }else{
                $empId          = $this->secure->dec($id);
                $results        = $this->mOt->getDataAjaxRemoteId($empId, 'data');
                $countresults   = $this->mOt->getDataAjaxRemoteId($empId, 'count');
                foreach($results as $row){
                    $selectajax[] = array(
                        'id' => $this->secure->enc($row['employee_id']),
                        'text' => $row['employee_name']
                    );
                }
            }
        }
        $select['items'] = $selectajax;
        $select['total_count'] = $countresults;
        $this->output->set_content_type('application/json')->set_output(json_encode($select));
    }
    public function changeStatus()
    {
        $idx = $this->secure->dec($this->input->post('tId'));
        $id_status = $this->input->post('tClCode');
        $csrfToken = cek_csrf_return();
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
        $cek = $this->mOrg->changeStatus($data, $idx);
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
    public function editPresence()
    {
        $id     = $this->secure->dec($this->input->get('p_id'));
        $result = $this->mOrg->editDepartment($id);
        if($result){
            $push = [
                'en_idx' => $this->secure->enc($result['idx']),
                'dept_head_enidx' => $this->secure->enc($result['dept_head_idx']),
            ];
            $final = array_merge($push,$result);
        }else{
            $final = [];
        }
        echo json_encode($final);
    }

    function createDateRangeArray($start, $end) {
        $range = [];
        $startDate = new DateTime($start);
        $endDate = new DateTime($end);
    
        while($startDate <= $endDate) {
            $range[] = $startDate->format('Y-m-d');
            $startDate->modify('+1 day');
        }
    
        return $range;
    }

    function removeDuplicatesByProperty($array, $property) {
        $unique = [];
        $nonunique = [];
        $result = [];
        $result2 = [];
    
        foreach ($array as $item) {
            // Check if the property value has already been added
            if (!in_array($item->$property, $unique)) {
                // If not, add it to the unique array and keep the object
                $unique[] = $item->$property;
                $result[] = $item;
            }else{
                $nonunique[] = $item->$property;
                $result2[] = $item;
            }
        }
        
        $ex = [
            'filtered' => $result,
            'othfiltered' => $result2
        ];
        return $ex;
    }
    
    // Function to extract the date part from datetime (converting to Y-m-d)
    function extractDate($datetime) {
        $d = DateTime::createFromFormat('Y-m-d H:i:s', $datetime);
        return $d ? $d->format('Y-m-d') : false;
    }

    // Function to extract the time part from datetime (converting to H:i:s)
    // function extractTime($datetime) {
    //     $d = DateTime::createFromFormat('Y-m-d H:i:s', $datetime);
    //     return $d ? $d->format('H:i:s') : false;
    // }


    // Function to remove duplicates by 'name' and date (ignoring time part of datetime)
    function removeDuplicatesByNameAndDate($array) {
        // Sort the array by date and time in descending order
        function extractTime($datetime) {
            $d = DateTime::createFromFormat('Y-m-d H:i:s', $datetime);
            return $d ? $d->format('H:i:s') : false;
        }
        function extractDate($datetime) {
            $d = DateTime::createFromFormat('Y-m-d H:i:s', $datetime);
            return $d ? $d->format('Y-m-d') : false;
        }
        function sortByDateTimeAscending($a, $b) {
            $dateTimeA = extractTime($a->scan_date);
            $dateTimeB = extractTime($b->scan_date);

            if ($dateTimeA == $dateTimeB) {
                return 0;
            }

            return ($dateTimeA < $dateTimeB) ? -1 : 1;
        }

        // Group the data by name and date
        $groupedData = [];
        foreach ($array as $item) {
            $date = extractDate($item->scan_date);
            $key = $item->pin . '|' . $date; // Group by name and date

            if (!isset($groupedData[$key])) {
                $groupedData[$key] = [];
            }

            $groupedData[$key][] = $item;
        }

        // Process each group to keep only the first and last item by time (ascending to descending)
        $finalResult = [];
        foreach ($groupedData as $group) {
            // Sort the group by time in ascending order
            usort($group, 'sortByDateTimeAscending');

            // Add the first and last item of the sorted group
            if (count($group) > 0) {
                $finalResult[] = $group[0]; // First item (earliest time)
            }
            if (count($group) > 1) {
                $finalResult[] = $group[count($group) - 1]; // Last item (latest time)
            }
        }

        // Convert the unique array to a list of objects
        return $finalResult;
    }
}
/* End of file user.php */
/* Location: ./application/controllers/user.php */
