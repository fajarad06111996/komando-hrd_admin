<?php
defined('BASEPATH') or exit('No direct script access allowed');
require "vendor/autoload.php";
use Nullix\CryptoJsAes\CryptoJsAes;

class Presence extends AUTH_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('attendance/M_presence', 'mPre');
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
            $data['write']  = '<a href="javascript:void(0);" id="bSync" class="pull-right text-white small" title="Syncron Mesin Absen" data-placement="right" data-popup="tooltip"><i class="icon-spinner10"></i></a>';
            $data['lock']   = 1;
        }else{
            $data['write']  = '';
            $data['lock']   = 0;
        }
        $data['employee']   = $this->mPre->getEmployee();
        $data['formact']    = $this->link.'/getPresence';
        $data['filename']   = $this->filename;
        $data['judul']      = 'Presensi';
        $data['page']       = 'Presensi';
        $data["link"]       = $this->link;
        $data["mesin1"]     = $this->mesin1;
        $data["mesin2"]     = $this->mesin2;
        $data['post']       = 0;
        $params             = '{"link": "'.$this->link.'", "csrf": "'.$this->session->csrf_token.'", "permW": "'.$this->security_function->permissions($this->filename . "-w").'", "permC": "'.$this->security_function->permissions($this->filename . "-c").'", "permX": "'.$this->security_function->permissions($this->filename . "-x").'", "base_url": "'.base_url().'", "post": 0, "employee_id": 0}';
        // encrypt
        $encrypted          = CryptoJsAes::encrypt($params, $this->enkey);
        $data['params']     = $encrypted;
        $this->template->views('attendance/v_presence', $data);
    }

    function getPresence(){
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
            redirect('attendance/presence');die;
        }
        $csrfToken  = validate_csrf_token();
        if($csrfToken == false){
            $this->session->set_flashdata('message',"notiferror_a('Token expired,<br>Refresh page & try again.')");
            redirect('attendance/presence');die;
        }
        ini_set ('max_execution_time', '0'); 
        ini_set ('memory_limit', '256M'); 
        $tAwal              = date("Y-m-d", strtotime($this->input->post('tStartdate')));
        $tAkhir   	        = date("Y-m-d", strtotime($this->input->post('tUntildate')));
        $tipeAbsen         = $this->input->post('tTipeAbsen', TRUE);
        
        if(empty($this->input->post('tEmployee'))){
            $employee_enid      = 0;
            $employee_id        = '';
        }else{
            $employee_enid      = $this->input->post('tEmployee');
            $employee_id        = $this->secure->dec($employee_enid);
        }
        $data['filename']   = $this->filename;
        $data["link"]       = $this->link;
        $data['userdata']   = $this->userdata;
        $data['formact']    = $this->link.'/getPresence';
        // untuk ambil data absensi berdasarkan dengan parameter tanggal awal, akhir dan id karyawan
        $result             = $this->mPre->getAttendanceEmployeeAll($tAwal,$tAkhir,$tipeAbsen,$employee_id);
        $format_data = [];
        
        $data['tipeAbsen']      = $tipeAbsen;
        $data['dataAbsen']      = $result;
        $data['employee']       = $employee_enid;

        foreach($result as $i => $v){
            $att_idx    = (int)$v['att_idx'];
            $hari       = strtolower(date('l', strtotime($v['calendar_date'])));
            $tanggal    = $v['calendar_date'];
            $tanggalV   = date('d-F-Y', strtotime($v['calendar_date']));
            $checkIn    = date('H:i:s', strtotime($v['check_in']));
            $cekInDay   = strtolower(date('l', strtotime($v['check_in'])));
            $cycle      = $this->mPre->getCycle($v['employee_id'], $v['shift_idx'], $tanggal);
            // var_dump('<pre>');var_dump();die;

            /* id untuk master shift 
                1. pagi         -> shift mode = 0
                2. siang        -> shift mode = 0
                3. sore         -> shift mode = 0
                4. multi shift  -> shift mode = 1
            */
            if($v['shift_mode']==1){
                if(empty($cycle)){
                    $this->session->set_flashdata('message', "notiferror_a('Jadwal Multi Shift Untuk Karyawan [<b>".$v['employee_name']."</b>] belum di seting.')");
                    redirect('attendance/presence');die;
                }
                if(count($cycle)> 1){
                    $cycleFilterx = array_filter($cycle, function($cycle) use ($tanggal) {
                        return $cycle['date'] === $tanggal;
                    });
                    $cycleFilter = reset($cycleFilterx);
                    $diffCheckIn        = strtotime($checkIn) - strtotime($cycleFilter['check_in']);
                }else{
                    $diffCheckIn        = strtotime($checkIn) - strtotime($cycle[0]['check_in']);
                }
                // ke tabel dynamic_shift
                $holiday = $this->mPre->check_holiday_cycle($tanggal, $v['employee_id'], $v['shift_idx']);
                $shift = $v['dynamic_check_in'];
            }else{
                $diffCheckIn        = strtotime($checkIn) - strtotime($v[$cekInDay]);
                // ke tabel calendar_event_master
                $holiday = $this->mPre->check_holiday($tanggal);
                $shift = $v[$hari];
            }
            // end shift mode
            
            // ======= untuk toleransi keterlambatan =========== /////
            // ke tabel attendance_setup
            $tolerance = $this->mPre->getTolerance($v['shift_idx']);
            if(empty($tolerance)){
                $this->session->set_flashdata('message',"notiferror_a('Shift ".$v['employee_name'].",<br>Belum di set.')");
                redirect('attendance/presence');die;
            }
            $diffInMinutes  = floor($diffCheckIn / 60);
            $terlambat      = 0;
            $itol = 1;
            foreach($tolerance as $tl){
                $pattern = '/\[([^\]]+)\]/';
                preg_match_all($pattern, $tl['rumus'], $matches);
                if(count($matches[1])>1){
                    $this->session->set_flashdata('message',"notiferror_a('Rumus tidak sesuai di menu <b>Master->Jadwal Kerja</b>.')");
                    redirect('attendance/presence');die;
                }
                $pengkali = $matches[1][0];
                if($diffInMinutes <= 0){
                    // var_dump('<pre>');var_dump($diffInMinutes);var_dump($tl['tolerance_in_end']);var_dump($tl['tolerance_in_start']);die;
                    $terlambat = 0;
                    break;
                }elseif($tl['tolerance_in_end']==0 && $diffInMinutes > $tl['tolerance_in_start']){
                    // var_dump('<pre>');var_dump($diffInMinutes);var_dump($tl['tolerance_in_start']);var_dump($tl['tolerance_in_end']);die;
                    $terlambat = 2;
                    break;
                }elseif($diffInMinutes > $tl['tolerance_in_start'] && $diffInMinutes <= $tl['tolerance_in_end']){
                    // var_dump('<pre>');var_dump($diffInMinutes);var_dump($tl['tolerance_in_start']);var_dump($tl['tolerance_in_end']);die;
                    if($itol==2){
                        $terlambat = 1;
                    }else{
                        $terlambat = 0;
                    }
                    break;
                }
                $itol++;
            }
            // end looping toleransi keterlambatan

            $employee_name = $v['employee_name'];
            $masuk = $v['check_in'];
            $jamMasuk = date('H:i', strtotime($v['check_in']));
            $masukDate = date('Y-m-d', strtotime($v['check_in']));
            $keluar = $v['check_out'];
            $jamKeluar = date('H:i', strtotime($v['check_out']));
            $todayServer = date('Y-m-d');
            $point_checkin = $v['point_center_check_in'];
            $point_checkout = $v['point_center_check_out'];
            $sub_status = $v['sub_type'];
            $sub_status_name = $v['sub_type_name'];
            $status_except = (int)$v['status_except_um'];
            $status_absen = 0;
            if($holiday>0){
                if($shift==null){
                    if($masuk==null){
                        if($keluar==null){
                            $absenView = "<span class='badge badge-danger'>Libur</span>";
                            $status = "libur";
                            $status_absen = 0;
                        }else{
                            $absenView = "<span class='badge badge-warning'>Lupa Cekin</span> - <a href='https://maps.google.com?q=$point_checkout' class='text-dark' target='_BLANK'>$jamKeluar</a>|<span class='badge badge-warning'>Lembur</span>";
                            $status = "lupa cekin";
                            $status_absen = 1;
                        }
                    }else{
                        if($keluar==null){
                            if($todayServer==$masukDate){
                                $absenView = "<a href='https://maps.google.com?q=$point_checkin' class='text-dark' target='_BLANK'>$jamMasuk</a> - <span class='badge badge-warning'>Sedang Berlangsung</span>|<span class='badge badge-warning'>Lembur</span>";
                                $status = "lembur";
                                $status_absen = 1;
                            }else{
                                $absenView = "<a href='https://maps.google.com?q=$point_checkin' class='text-dark' target='_BLANK'>$jamMasuk</a> - <span class='badge badge-warning'>Lupa Cekout</span>|<span class='badge badge-warning'>Lembur</span>";
                                $status = "lupa cekout";
                                $status_absen = 1;
                            }
                        }else{
                            $absenView = "<a href='https://maps.google.com?q=$point_checkin' class='text-dark' target='_BLANK'>$jamMasuk</a> - <a href='https://maps.google.com?q=$point_checkout' class='text-dark' target='_BLANK'>$jamKeluar</a>|<span class='badge badge-warning'>Lembur</span>";
                            $status = "lembur";
                            $status_absen = 1;
                        }
                    }
                }else{
                    if($masuk==null){
                        if($keluar==null){
                            $absenView = "<span class='badge badge-danger'>Libur</span>";
                            $status = "libur";
                            $status_absen = 0;
                        }else{
                            $absenView = "<span class='badge badge-warning'>Lupa Cekin</span> - <a href='https://maps.google.com?q=$point_checkout' class='text-dark' target='_BLANK'>$jamKeluar</a>|<span class='badge badge-warning'>Lembur</span>";
                            $status = "lupa cekin";
                            $status_absen = 1;
                        }
                    }else{
                        if($keluar==null){
                            if($todayServer==$masukDate){
                                $absenView = "<a href='https://maps.google.com?q=$point_checkin' class='text-dark' target='_BLANK'>$jamMasuk</a> - <span class='badge badge-warning'>Sedang Berlangsung</span>|<span class='badge badge-warning'>Lembur</span>";
                                $status = "lembur";
                                $status_absen = 1;
                            }else{
                                $absenView = "<a href='https://maps.google.com?q=$point_checkin' class='text-dark' target='_BLANK'>$jamMasuk</a> - <span class='badge badge-warning'>Lupa Cekout</span>|<span class='badge badge-warning'>Lembur</span>";
                                $status = "lupa cekout";
                                $status_absen = 1;
                            }
                        }else{
                            $absenView = "<a href='https://maps.google.com?q=$point_checkin' class='text-dark' target='_BLANK'>$jamMasuk</a> - <a href='https://maps.google.com?q=$point_checkout' class='text-dark' target='_BLANK'>$jamKeluar</a>|<span class='badge badge-warning'>Lembur</span>";
                            $status = "lembur";
                            $status_absen = 1;
                        }
                    }
                }
            }else{
                if($shift==null){
                    if($masuk==null){
                        if($keluar==null){
                            $absenView = "<span class='badge badge-danger'>Libur</span>";
                            $status = "libur";
                            $status_absen = 0;
                        }else{
                            $absenView = "<span class='badge badge-warning'>Lupa Cekin</span> - <a href='https://maps.google.com?q=$point_checkout' class='text-dark' target='_BLANK'>$jamKeluar</a>|<span class='badge badge-warning'>Lembur</span>";
                            $status = "lupa cekin";
                            $status_absen = 1;
                        }
                    }else{
                        if($keluar==null){
                            if($todayServer==$masukDate){
                                $absenView = "<a href='https://maps.google.com?q=$point_checkin' class='text-dark' target='_BLANK'>$jamMasuk</a> - <span class='badge badge-warning'>Sedang Berlangsung</span>|<span class='badge badge-warning'>Lembur</span>";
                                $status = "lembur";
                                $status_absen = 1;
                            }else{
                                $absenView = "<a href='https://maps.google.com?q=$point_checkin' class='text-dark' target='_BLANK'>$jamMasuk</a> - <span class='badge badge-warning'>Lupa Cekout</span>|<span class='badge badge-warning'>Lembur</span>";
                                $status = "lupa cekout";
                                $status_absen = 1;
                            }
                        }else{
                            $absenView = "<a href='https://maps.google.com?q=$point_checkin' class='text-dark' target='_BLANK'>$jamMasuk</a> - <a href='https://maps.google.com?q=$point_checkout' class='text-dark' target='_BLANK'>$jamKeluar</a>|<span class='badge badge-warning'>Lembur</span>";
                            $status = "hadir";
                            $status_absen = 1;
                        }
                    }
                }else{
                    if($masuk==null){
                        if($keluar==null){
                            $absenView = $sub_status==0?"<span class='text-danger'>Mangkir</span>":"<span class='text-dark'>".strtolower($sub_status_name)."</span>";
                            $status = $sub_status==0?'mangkir':strtolower($sub_status_name);
                            $status_absen = 0;
                        }else{
                            $absenView = "<span class='badge badge-warning'>Lupa Cekin</span> - <a href='https://maps.google.com?q=$point_checkout' class='text-dark' target='_BLANK'>$jamKeluar</a>";
                            $status = "lupa cekin";
                            $status_absen = 1;
                        }
                    }else{
                        if($keluar==null){
                            if($todayServer==$masukDate){
                                $absenView = "<a href='https://maps.google.com?q=$point_checkin' class='text-dark' target='_BLANK'>$jamMasuk</a> - <span class='badge badge-warning'>Sedang Berlangsung</span>";
                                $status = "hadir";
                                $status_absen = 1;
                            }else{
                                $absenView = "<a href='https://maps.google.com?q=$point_checkin' class='text-dark' target='_BLANK'>$jamMasuk</a> - <span class='badge badge-warning'>Lupa Cekout</span>";
                                $status = "lupa cekout";
                                $status_absen = 1;
                            }
                        }else{
                            $absenView = "<a href='https://maps.google.com?q=$point_checkin' class='text-dark' target='_BLANK'>$jamMasuk</a> - <a href='https://maps.google.com?q=$point_checkout' class='text-dark' target='_BLANK'>$jamKeluar</a>";
                            $status = "hadir";
                            $status_absen = 1;
                        }
                    }
                }
            }

            // untuk cetak data berupa laporan ke view v_presence
            $format_data[$employee_name][$tanggal] = [$absenView, $sub_status, $sub_status_name, $status_absen, $terlambat, $att_idx, $employee_name, $tanggalV, $status_except];
        } // end looping perulangan absensi


        if(!empty($this->security_function->permissions($this->filename . "-w"))){
            $data['write']  = '';
            $data['write']  = '<a href="javascript:void(0);" id="bSync" class="pull-right text-white small" title="Syncron Mesin Absen" data-placement="right" data-popup="tooltip"><i class="icon-spinner10"></i></a>';
            $data['lock']   = 1;
        }else{
            $data['write']  = '';
            $data['lock']   = 0;
        }
        $data['result']     = $format_data;
        // var_dump('<pre>');var_dump($format_data);die;
        $data['awal'] 	    = $this->input->post('tStartdate');
        $data['akhir'] 	    = $this->input->post('tUntildate');
        $data['employee_id']    = $employee_enid;
        $data['post']       = 1;
        $data["mesin1"]     = $this->mesin1;
        $data["mesin2"]     = $this->mesin2;
        $params             = '{"link": "'.$this->link.'", "csrf": "'.$this->session->csrf_token.'", "permW": "'.$this->security_function->permissions($this->filename . "-w").'", "permC": "'.$this->security_function->permissions($this->filename . "-c").'", "permX": "'.$this->security_function->permissions($this->filename . "-x").'", "awal": "'.$tAwal.'", "akhir": "'.$tAkhir.'", "post": 1, "employee_id": "'.$employee_enid.'"}';
        $encrypted          = CryptoJsAes::encrypt($params, $this->enkey);
        $data['params']     = $encrypted;
        // $data['data'] = [
        //             'mulaiTgl' => $this->input->post('tStartdate'), 
        //             'sampaiTgl'=> $this->input->post('tUntildate'),
        //             'jenisAbsen'=> $this->input->post('tJenisabsensi')
        //             ];
        $this->template->views('attendance/v_presence', $data);
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
        $from       = $this->input->post('from');
        $to         = $this->input->post('to');
        $mesin      = $this->input->post('tMesin');
        $ip_address = $this->input->ip_address();
        $trx_id     = $this->ModelGenId->genIdUnlimited('TRXFS', $this->idx);
        $data = [
            'trans_id' => $trx_id,
            'cloud_id' => $mesin,
            'start_date' => $from,
            'end_date' => $to
        ];
        $cekSync = $this->mPre->syncPresence($data);
        // var_dump('<pre>');var_dump($cekSync);die;
        if(empty($cekSync)){
            $msg['status']  = false;
            $msg['text']    = 'Error Connection<br>Please try again later.';
            echo json_encode($msg);die;
        }

        $cek = json_decode($cekSync);
        // var_dump('<pre>');var_dump($cek);die;
        if($cek->success == false){
            $msg['status']  = false;
            $msg['text']    = $cek->message;
            echo json_encode($msg);die;
        }

        $thatResult = $this->removeDuplicatesByNameAndDate($cek->data);
        if(empty($thatResult)){
            $msg['status']  = false;
            $msg['text']    = 'Error Connection<br>Please try again later.';
            echo json_encode($msg);die;
        }
        // echo json_encode($thatResult);die;
        // var_dump('<pre>');var_dump($thatResult);die;
        $insertIn = '';
        $insertOut = '';
        $updateIn = '';
        $updateOut = '';
        $now = date('Y-m-d H:i:s');
        $id_login = $this->idx;
        $office = $this->office;
        foreach($thatResult as $x){
            $scanDate = date('Y-m-d', strtotime($x->scan_date));
            $getShift = $this->mPre->getShiftWithPin((int)$x->pin);
            if(!empty($getShift)){
                $piketIn        = "piket_in";
                $piketOut       = "piket_out";
                $scanDayIn      = strtolower(date('l', strtotime($x->scan_date))).'_in';
                $scanDayOut     = strtolower(date('l', strtotime($x->scan_date))).'_out';
                $targetShiftIn  = empty($getShift[$scanDayIn])?$getShift[$piketIn]:$getShift[$scanDayIn];
                $targetIn       = $scanDate .' '. $targetShiftIn;
                $targetShiftOut = empty($getShift[$scanDayOut])?$getShift[$piketOut]:$getShift[$scanDayOut];
                $targetOut      = $scanDate .' '. $targetShiftOut;
                $startTimeIn    = date("Y-m-d H:i:s", strtotime($targetIn . " -4 hours"));
                $endTimeIn      = date("Y-m-d H:i:s", strtotime($targetIn . " +3 hours"));
                // Konversi waktu menjadi timestamp
                $timeTimestamp  = strtotime($x->scan_date);
                $startTimestamp = strtotime($startTimeIn);
                $endTimestamp   = strtotime($endTimeIn);
                $getUser = $this->mPre->cekAbsenNowx($x->pin, $scanDate);
                // var_dump('<pre>');var_dump((int)$x->pin);die;
                if(empty($getUser)){
                    $att_id = $this->ModelGenId->genIdUnlimited('ETTK', 1);
                    $att_no = "ETTK" . date("y") . date("m") . str_pad($att_id, 12, "0", STR_PAD_LEFT);
                    $emp_id_shift = (int)$getShift['employee_id'];
                    $shift_idx = (int)$getShift['idx'];
                    if($x->status_scan==0){
                        if ($timeTimestamp > $startTimestamp && $timeTimestamp < $endTimestamp) {
                            $insert = [
                                'attendance_id' => $att_id, 
                                'attendance_no' => $att_no, 
                                'attendance_date' => $scanDate, 
                                'employee_id' => $emp_id_shift, 
                                'shift_idx' => $shift_idx, 
                                'target_in' => $targetIn, 
                                'check_in' => $x->scan_date, 
                                'stamp_in' => $x->scan_date, 
                                'verify' => $x->verify, 
                                'company_idx' => $office, 
                                'office_idx' => $office, 
                                'status' => 1, 
                                'created_on' => $now, 
                                'created_by' => $id_login
                            ];
                        }else{
                            $insert = [
                                'attendance_id' => $att_id, 
                                'attendance_no' => $att_no, 
                                'attendance_date' => $scanDate, 
                                'employee_id' => $emp_id_shift, 
                                'shift_idx' => $shift_idx, 
                                'target_out' => $targetOut, 
                                'check_out' => $x->scan_date, 
                                'stamp_out' => $x->scan_date, 
                                'verify' => $x->verify, 
                                'company_idx' => $office, 
                                'office_idx' => $office, 
                                'status' => 1, 
                                'created_on' => $now, 
                                'created_by' => $id_login
                            ];
                        }
                    }else{
                        if ($timeTimestamp > $startTimestamp && $timeTimestamp < $endTimestamp) {
                            $insert = [
                                'attendance_id' => $att_id, 
                                'attendance_no' => $att_no, 
                                'attendance_date' => $scanDate, 
                                'employee_id' => $emp_id_shift, 
                                'shift_idx' => $shift_idx, 
                                'target_in' => $targetIn, 
                                'check_in' => $x->scan_date, 
                                'stamp_in' => $x->scan_date, 
                                'verify' => $x->verify, 
                                'company_idx' => $office, 
                                'office_idx' => $office, 
                                'status' => 1, 
                                'created_on' => $now, 
                                'created_by' => $id_login
                            ];
                        }else{
                            $insert = [
                                'attendance_id' => $att_id, 
                                'attendance_no' => $att_no, 
                                'attendance_date' => $scanDate, 
                                'employee_id' => $emp_id_shift, 
                                'shift_idx' => $shift_idx, 
                                'target_out' => $targetOut, 
                                'check_out' => $x->scan_date, 
                                'stamp_out' => $x->scan_date, 
                                'verify' => $x->verify, 
                                'company_idx' => $office, 
                                'office_idx' => $office, 
                                'status' => 1, 
                                'created_on' => $now, 
                                'created_by' => $id_login
                            ];
                        }
                    }
                    $result = $this->mPre->syncInsertPresence($insert);
                    if($result==0){
                        $msg['status']  = false;
                        $msg['text']    = 'Error Connection<br>Please try again later.';
                        echo json_encode($msg);die;
                    }
                }else{
                    $att_idx        = (int)$getUser['att_idx'];
                    $att_pin        = (int)$getUser['att_pin'];
                    $check_in       = $getUser['check_in'];
                    $check_out      = $getUser['check_out'];

                    // if((int)$x->pin==46){
                    //     if ($timeTimestamp > $startTimestamp && $timeTimestamp < $endTimestamp) {
                    //         var_dump('<pre>');var_dump([
                    //             'cek' => 'Masuk',
                    //             'target_in' => $targetIn,
                    //             'toleran_min' => $startTimeIn,
                    //             'toleran_max' => $endTimeIn,
                    //             'scan' => $x->scan_date
                    //         ]);die;
                    //     }else{
                    //         var_dump('<pre>');var_dump([
                    //             'cek' => 'Keluar',
                    //             'target_in' => $targetIn,
                    //             'toleran_min' => $startTimeIn,
                    //             'toleran_max' => $endTimeIn,
                    //             'scan' => $x->scan_date
                    //         ]);die;
                    //     }
                    // }
                    
                    if((int)$x->pin==$att_pin){
                        if($x->status_scan==0){
                            // Validasi apakah $time berada dalam rentang waktu
                            if ($timeTimestamp > $startTimestamp && $timeTimestamp < $endTimestamp) {
                                if(empty($check_in)){
                                    $update = [
                                        'target_in' => $targetIn,
                                        'check_in' => $x->scan_date,
                                        'stamp_in' => $x->scan_date,
                                        'target_out' => NULL,
                                        'check_out' => NULL,
                                        'stamp_out' => NULL,
                                        'verify' => $x->verify,
                                        'status' => 1,
                                        'modified_on' => $now,
                                        'modified_by' => $id_login
                                    ];
                                }else{
                                    if($check_in==$x->scan_date){
                                        $update = [
                                            'target_out' => NULL,
                                            'check_out' => NULL,
                                            'stamp_out' => NULL,
                                            'verify' => $x->verify,
                                            'status' => 1,
                                            'modified_on' => $now,
                                            'modified_by' => $id_login
                                        ];
                                    }else{
                                        $update = [
                                            'target_in' => $targetIn,
                                            'check_in' => $x->scan_date,
                                            'stamp_in' => $x->scan_date,
                                            'target_out' => NULL,
                                            'check_out' => NULL,
                                            'stamp_out' => NULL,
                                            'verify' => $x->verify,
                                            'status' => 1,
                                            'modified_on' => $now,
                                            'modified_by' => $id_login
                                        ];
                                    }
                                }
                            } else {
                                if(empty($check_out)){
                                    $update = [
                                        'target_out' => $targetOut,
                                        'check_out' => $x->scan_date,
                                        'stamp_out' => $x->scan_date,
                                        'verify' => $x->verify,
                                        'status' => 1,
                                        'modified_on' => $now,
                                        'modified_by' => $id_login
                                    ];
                                }else{
                                    if($check_out==$x->scan_date){
                                        $update = [];
                                    }else{
                                        $update = [
                                            'target_out' => $targetOut,
                                            'check_out' => $x->scan_date,
                                            'stamp_out' => $x->scan_date,
                                            'verify' => $x->verify,
                                            'status' => 1,
                                            'modified_on' => $now,
                                            'modified_by' => $id_login
                                        ];
                                    }
                                }
                            }
                        }else{
                            if ($timeTimestamp > $startTimestamp && $timeTimestamp < $endTimestamp) {
                                if(empty($check_in)){
                                    $update = [
                                        'target_in' => $targetIn,
                                        'check_in' => $x->scan_date,
                                        'stamp_in' => $x->scan_date,
                                        'target_out' => NULL,
                                        'check_out' => NULL,
                                        'stamp_out' => NULL,
                                        'verify' => $x->verify,
                                        'status' => 1,
                                        'modified_on' => $now,
                                        'modified_by' => $id_login
                                    ];
                                }else{
                                    if($check_in==$x->scan_date){
                                        $update = [
                                            'target_out' => NULL,
                                            'check_out' => NULL,
                                            'stamp_out' => NULL,
                                            'verify' => $x->verify,
                                            'status' => 1,
                                            'modified_on' => $now,
                                            'modified_by' => $id_login
                                        ];
                                    }else{
                                        $update = [
                                            'target_in' => $targetIn,
                                            'check_in' => $x->scan_date,
                                            'stamp_in' => $x->scan_date,
                                            'target_out' => NULL,
                                            'check_out' => NULL,
                                            'stamp_out' => NULL,
                                            'verify' => $x->verify,
                                            'status' => 1,
                                            'modified_on' => $now,
                                            'modified_by' => $id_login
                                        ];
                                    }
                                }
                            }else{
                                if(empty($check_out)){
                                    $update = [
                                        'target_out' => $targetOut,
                                        'check_out' => $x->scan_date,
                                        'stamp_out' => $x->scan_date,
                                        'verify' => $x->verify,
                                        'status' => 1,
                                        'modified_on' => $now,
                                        'modified_by' => $id_login
                                    ];
                                }else{
                                    if($check_out==$x->scan_date){
                                        $update = [];
                                    }else{
                                        $update = [
                                            'target_out' => $targetOut,
                                            'check_out' => $x->scan_date,
                                            'stamp_out' => $x->scan_date,
                                            'verify' => $x->verify,
                                            'status' => 1,
                                            'modified_on' => $now,
                                            'modified_by' => $id_login
                                        ];
                                    }
                                }
                            }
                        }
                        if(!empty($update)){
                            $result = $this->mPre->syncUpdatePresence($update, $att_idx);
                            if($result==0){
                                $msg['status']  = false;
                                $msg['text']    = 'Error Connection<br>Please try again later.';
                                echo json_encode($msg);die;
                            }
                        }
                    }
                }
            }
        }


        $data_attlog = [
            'cloud_id' => $mesin,
            'trans_id' => $trx_id,
            'start_date' => $from,
            'end_date' => $to,
            'data_log' => $cekSync,
            'ip_address' => $ip_address,
            'status' => 1,
            'created_on' => $now,
            'created_by' => $id_login
        ];

        $result = $this->mPre->insertLogSync($data_attlog);
        if($result==1){
            $msg['status']  = true;
            $msg['text']    = 'Syncrone success.';
            echo json_encode($msg);die;
        }else{
            $msg['status']  = false;
            $msg['text']    = 'Error Connection<br>Please try again later.';
            echo json_encode($msg);die;
        }
    }

    // untuk get data karyawan dari elemen select option pada view v_presence
    public function getDataAjaxRemote()
    {
        $search = $this->input->post('search');
        $results = $this->mPre->getDataAjaxRemote($search, 'data');
        $countresults = $this->mPre->getDataAjaxRemote($search, 'count');
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
        if(empty($id) || $id==="0"){
            $countresults   = 1;
            $selectajax[] = array(
                'id' => 0,
                'text' => "SEMUA"
            );
        }else{
            $empId          = $this->secure->dec($id);
            $results        = $this->mPre->getDataAjaxRemoteId($empId, 'data');
            $countresults   = $this->mPre->getDataAjaxRemoteId($empId, 'count');
            foreach($results as $row){
                $selectajax[] = array(
                    'id' => $this->secure->enc($row['employee_id']),
                    'text' => $row['employee_name']
                );
            }
        }
        $select['items'] = $selectajax;
        $select['total_count'] = $countresults;
        $this->output->set_content_type('application/json')->set_output(json_encode($select));
    }
    public function exceptUm($att_id)
    {
        $att_idx    = $this->secure->dec($att_id);
        $id_status  = $this->input->post('tStatus');
        $csrfToken  = validate_csrf_token();
        if($csrfToken == FALSE){
            $msg['status']   = false;
            $msg['text']     = 'Please refresh page and try again.!';
            echo json_encode($msg);die;
        }
        if($id_status == '1'){
            $changeId = 1;
        } else {
            $changeId = 0;
        }
        $data = [
            'status_except_um'  => $changeId,
            'modified_by'       => $this->idx,
            'modified_on'       => date("Y-m-d H:i:s")
        ];
        $cek = $this->mPre->exceptUm($data, $att_idx);
        if($cek == true)
        {
            $msg['text']    = 'Berhasil di kecualikan.';
            $msg['status']  = true;
            echo json_encode($msg);die;
        }else{
            $msg['text']    = 'Gagal di kecualikan.';
            $msg['status']   = false;
            echo json_encode($msg);die;
        }
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
