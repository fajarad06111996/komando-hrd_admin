<?php
defined('BASEPATH') or exit('No direct script access allowed');
require "vendor/autoload.php";
use Nullix\CryptoJsAes\CryptoJsAes;

class Umt extends AUTH_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('payroll/M_umt', 'mUmt');
        $this->load->model('ModelGenId');
        $this->load->library('security_function');
        $this->link	        = site_url('payroll/').strtolower(get_class($this));
        $this->filename	    = strtolower(get_class($this));
        $this->office       = $this->secure->dec($this->session->userdata('JToffice_id'));
        $this->officeCode   = $this->session->userdata('JToffice_code');
        $this->hub          = $this->secure->dec($this->session->userdata('JThub_id'));
        $this->idx          = $this->secure->dec($this->session->userdata('JTidx'));
        $this->username     = $this->session->userdata('JTuser_id');
        $this->enkey   	    = $this->config->item('encryption_key');
    }
    public function index()
    {
        $access = $this->security_function->permissions($this->filename . "-r");
        if (empty($access)) die("<center><h1>You can not get the right to access this module</h1></center>");
        if(!empty($this->security_function->permissions($this->filename . "-w"))){
            $data['write']  = '<a href="javascript:void(0);" id="btnAdd" class="pull-right text-white small" title="Tambah Pembayaran UMT" data-placement="right" data-popup="tooltip"><i class="icon-plus-circle2"></i></a>';
            $lock   = 1;
        }else{
            $data['write']  = '<i class="icon-lock pull-right pt-1 " title="Locked New Office" data-placement="right" data-popup="tooltip"></i>';
            $lock   = 0;
        }
        $data['filename']   = $this->filename;
        $data["link"]       = $this->link;
        $params             = '{"link": "'.$this->link.'", "csrf": "'.$this->session->csrf_token.'", "permW": "'.$this->security_function->permissions($this->filename . "-w").'", "permC": "'.$this->security_function->permissions($this->filename . "-c").'", "permX": "'.$this->security_function->permissions($this->filename . "-x").'", "lock": "'.$lock.'", "base_url": "'.base_url().'"}';
        // encrypt
        $encrypted          = CryptoJsAes::encrypt($params, $this->enkey);
        $data['params']     = $encrypted;
        $this->template->views('payroll/v_umt', $data);
    }
    // url for ajax datatable serverside
    function get_ajax() {
        $csrfToken  = validate_csrf_token();
        if($csrfToken == false){
            $list = array();
        }else{
            $list = $this->mUmt->get_datatables();
        }
        // $list = $this->mUmt->get_datatables();
        $data = array();
        $no = @$_POST['start'];
        foreach ($list as $item) {
            $idx = $this->secure->enc($item->idx);
            $all_id = $this->secure->enc($item->allowh_id);
            if($item->total_item>0){
                $print = "<h5><a href='".$this->link."/printUmt/".$idx."' target='_BLANK' class='bPrint text-center badge badge-danger' data-popup='tooltip' title='Print UMT' data-placement='right'><i class='icon-printer2'></i></a></h5>";
            }else{
                $print  = "";
            }
            if(!empty($this->security_function->permissions($this->filename . "-c"))){
                if($item->status == 1){
                    $change = "<h5><a href='javascript:void(0);' id='$all_id' class='bEdit text-center badge badge-info' data-popup='tooltip' title='Edit UMT Detail' data-placement='right'><i class='icon-pencil7'></i></a></h5>";
                    $statusU = "<h5><span class='text-center badge badge-danger'>Draft</span></h5>";
                    $publish = "<h5><a href='javascript:void(0);' id='$idx' data='$item->allowance_code' allid='$all_id' class='bPublish text-center badge badge-warning' data-popup='tooltip' title='Publish UMT' data-placement='right'><i class='icon-cloud-upload2'></i></a></h5>";
                }elseif($item->status == 2){
                    $change = "";
                    $statusU = "<h5><span class='text-center badge badge-success'>Publish</span></h5>";
                    $publish = "<h5><span class='badge badge-success bPublished'><i class='icon-checkmark' data-popup='tooltip' title='UMT Terpublish' data-placement='right'></i></span></h5>";
                }
            }else{
                if($item->status == 1){
                    $change = '<h5><span class="badge badge-warning"><i class="icon-lock" data-popup="tooltip" title="Locked Edit Office" data-placement="right"></i></span></h5>';
                    $statusU = "<h5><span class='text-center badge badge-danger'>Draft</span></h5>";
                    $publish = "<h5><span class='badge badge-warning'><i class='icon-lock' data-popup='tooltip' title='Locked Publish UMT' data-placement='right'></i></span></h5>";
                }elseif($item->status == 2){
                    $change = '';
                    $statusU = "<h5><span class='text-center badge badge-success'>Publish</span></h5>";
                    $publish = "<h5><span class='badge badge-success bPublished'><i class='icon-checkmark' data-popup='tooltip' title='UMT Terpublish' data-placement='right'></i></span></h5>";
                }
            }
            if(!empty($this->security_function->permissions($this->filename . "-x"))){
                $execute  = "<h5><a href='javascript:void(0);' id='$idx' data='$item->allowance_code' class='bDelete text-center badge badge-danger' data-popup='tooltip' title='Delete Office' data-placement='right'><i class='icon-bin'></i></a></h5>";
            }else{
                $execute  = '<h5><span class="badge badge-warning"><i class="mi-lock font-weight-black" data-popup="tooltip" title="Locked Delete Office" data-placement="right"></i></span></h5>';
            }
            $no++;
            $row = array();
            $row[] = $no.".";
            $row[] = "<div class='btn-group'>$change&nbsp;$print&nbsp;$publish</div>";
            $row[] = $statusU;
            $row[] = $item->allowance_code;
            $row[] = '<b>'.date('d', strtotime($item->start)).'/'.bulan(date('M', strtotime($item->start))).'/'.date('Y', strtotime($item->start)).'</b> - <b>'.date('d', strtotime($item->end)).'/'.bulan(date('M', strtotime($item->end))).'/'.date('Y', strtotime($item->end)).'</b>';
            $row[] = $item->total_item;
            $row[] = 'Rp. '.number_format($item->grandtotal);
            $row[] = $item->description;
            // add html for action
            $data[] = $row;
        }
        $output = array(
            "draw"            => @$_POST['draw'],
            "recordsTotal"    => $this->mUmt->count_all(),
            "recordsFiltered" => $this->mUmt->count_filtered(),
            "data"            => $data,
        );
        // output to json format
        echo json_encode($output);
    }

    function get_header() {
        $all_id     = $this->input->post('all_id');
        $from       = $this->input->post('from');
        $to         = $this->input->post('to');
        $all_idx    = $this->secure->dec($all_id);
        $csrfToken  = validate_csrf_token();
        if($csrfToken == false){
            $list = array();
        }else{
            $list = $this->mUmt->get_datatables2($all_idx, $from, $to);
        }

        $columns = ['No.','Nama Karyawan'];
        foreach(array_keys(current($list)) as $r){
            $columns[] = date('d-F-Y', strtotime($r));
        }

        $output = array(
            "columns"         => $columns
        );

        echo json_encode($output);
    }
    // url for ajax datatable serverside
    function get_ajax2() {
        $all_id     = $this->input->post('all_id');
        $from       = $this->input->post('from');
        $to         = $this->input->post('to');
        $all_idx    = $this->secure->dec($all_id);
        $csrfToken  = validate_csrf_token();
        if($csrfToken == false){
            $list = array();
        }else{
            $list = $this->mUmt->get_datatables2($all_idx, $from, $to);
        }
        // var_dump('<pre>');var_dump($list);die;
        // $format_data = [];
        // $columns = ['No.','Nama Karyawan'];
        // foreach(array_keys(current($list)) as $r){
        //     $columns[] = date('d-F-Y', strtotime($r));
        // }
        $no = 1;
        $data = array();
        foreach($list as $k => $v){
            $row = array();
            $row[] = [$no.".", 99];
            $row[] = [$k, 99];
            foreach($v as $absen){
                $row[] = [$absen[0], $absen[1]];
            }
            $data[] = $row;
            $no++;
        }
        // var_dump('<pre>');var_dump($_POST['draw']);die;
        // $itemsPerPage = 10;  // Number of items per page
        // if(@$_POST['length'] != -1){
        //     // $this->db->limit(@$_POST['length'], @$_POST['start']);
        //     $page = @$_POST['length'] != -1?@$_POST['start']:1;
        // }
        $itemsPerPage = (int)$_POST['length'];
        $page = (int)$_POST['start'];
        $totalItem = count($data);
        $totalPages = ceil($totalItem/$itemsPerPage);
        $page2 = ($page/$itemsPerPage)+1;
        // $page2 = max(1, min($page, $totalPages));
        $offset = ($page2 - 1) * $itemsPerPage;
        $pagedData = array_slice($data, $offset, $itemsPerPage);
        $pageFiltered = count($pagedData);
        $output = array(
            "draw"            => @$_POST['draw'],
            "recordsTotal"    => $totalItem,
            "recordsFiltered" => $totalItem,
            // "columns"         => $columns,
            "data"            => $pagedData
        );
        // $output = array(
        //     "draw"            => @$_POST['draw'],
        //     "recordsTotal"    => $this->mUmt->count_all2($all_idx, $from, $to),
        //     "recordsFiltered" => $this->mUmt->count_filtered2($all_idx, $from, $to),
        //     "columns"         => $columns,
        //     "data"            => $data,
        // );
        // var_dump('<pre>');var_dump(json_encode($output));die;
        // output to json format
        echo json_encode($output);
    }

    // Proccess adding new Data
    public function selectUmt($all_id="")
    {
		$access = $this->security_function->permissions($this->filename . "-r");
		if (empty($access)) die("<center><h1>You can not get the right to access this module</h1></center>");
		if(empty($all_id)){
    		// $all_idx 	= $this->secure->dec($all_id);
    		$from_date 	= filter_var(trim($this->input->post('from',TRUE)), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
    		$to_date 	= filter_var(trim($this->input->post('to',TRUE)), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
    		// $rapel 	    = filter_var(trim($this->input->post('rapel',TRUE)), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
    		$desc 	    = filter_var(trim($this->input->post('description',TRUE)), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$all_id_new = $this->ModelGenId->genIdUnlimited('ALLWHID', $this->idx);
			$idx        = $this->ModelGenId->genIdYear('ALLWH', $this->idx);
			$all_no     = $idx.'/UMT/'.$this->officeCode.'/'.numberToRoman(date('m')).'/'.date('Y');
            $login      = $this->idx;
            $company    = $this->office;
            $now        = date('Y-m-d H:i:s');

            $getUmt = $this->mUmt->getUmtData($from_date, $to_date, $this->office);
            if(empty($getUmt)){
                $this->session->set_flashdata('message', "notiferror_a('Data tidak di temukan,<br>Belum ada data di range ini.')");
			    redirect($this->link);die;
            }

            $valRapel   = array();
            $valMeal    = array();
            $valTransp  = array();
            $oTime      = array();
            $tangal     = array();
            $valLembur  = array();
            $updateAttendance ='';
            $updateOt ='';
            $updateDlk ='';
            $updateInc ='';
            $updateSub ='';
            $attIdx = '';
            $allHalfDetail ='';
            $newAllHalfDetail ='';
            $grand_total = 0;
            $i = 0;
            $result = [];
            foreach($getUmt as $a){
                $employee_id = $a->employee_id;
                $attendance_date = $a->attendance_date;

                $key = $employee_id.'_'.$attendance_date;

                // Jika kunci belum ada, inisialisasi array baru
                if (!isset($result[$key])) {
                    $result[$key] = [
                        "idx" => $a->idx,
                        "rapel_ot_idx" => NULL,
                        "rapel_sub_idx" => NULL,
                        "rapel_dlk_idx" => NULL,
                        "rapel_inc_idx" => NUll,
                        "attendance_date" => $a->attendance_date,
                        "employee_id" => $a->employee_id,
                        "sub_type" => $a->sub_type,
                        "status_confirm" => $a->status_confirm,
                        "check_in" => $a->check_in,
                        "check_out" => $a->check_out,
                        "shift_idx" => $a->shift_idx,
                        "get_name" => $a->get_name,
                        "dynamic_check_in" => $a->dynamic_check_in,
                        "value_overtime" => $a->value_overtime,
                        "overtime_confirm_date" => $a->overtime_confirm_date,
                        "overtime_allowance" => $a->overtime_allowance,
                        "status_overtime" => $a->status_overtime,
                        "status_piket" => $a->status_piket,
                        "status_except_um" => $a->status_except_um,
                        "rapel_ot" => 0,
                        "rapel_ot_date" => $a->rapel_ot_date,
                        "rapel_sub" => 0,
                        "rapel_sub_date" => $a->rapel_sub_date,
                        "rapel_dlk" => 0,
                        "rapel_dlk_date" => $a->rapel_dlk_date,
                        "rapel_inc" => 0,
                        "rapel_inc_date" => $a->rapel_inc_date,
                        "shift_mode" => $a->shift_mode,
                        "monday" => $a->monday,
                        "tuesday" => $a->tuesday,
                        "wednesday" => $a->wednesday,
                        "thursday" => $a->thursday,
                        "friday" => $a->friday,
                        "saturday" => $a->saturday,
                        "sunday" => $a->sunday,
                    ];
                }

                $result[$key]['rapel_ot_idx'] = trim($result[$key]['rapel_ot_idx'] . ',' . $a->rapel_ot_idx, ',');
                $result[$key]['rapel_sub_idx'] = trim($result[$key]['rapel_sub_idx'] . ',' . $a->rapel_sub_idx, ',');
                $result[$key]['rapel_dlk_idx'] = trim($result[$key]['rapel_dlk_idx'] . ',' . $a->rapel_dlk_idx, ',');
                $result[$key]['rapel_inc_idx'] = trim($result[$key]['rapel_inc_idx'] . ',' . $a->rapel_inc_idx, ',');

                $result[$key]['rapel_ot'] += $a->rapel_ot;
                $result[$key]['rapel_sub'] += $a->rapel_sub;
                $result[$key]['rapel_dlk'] += $a->rapel_dlk;
                $result[$key]['rapel_inc'] += $a->rapel_inc;
            }

            $getUmtNew = array_values($result);
            foreach ($getUmtNew as $g) {
                $mealAl             = 0;
                $cekInDay           = strtolower(date('l', strtotime($g['check_in'])));
                $cekInDate          = strtolower(date('Y-m-d', strtotime($g['check_in'])));
                $checkIn            = date('H:i:s', strtotime($g['check_in']));
                $attDate            = $g['attendance_date'];
                $employee_id        = $g['employee_id'];
                $empData            = $this->mUmt->get_employee_data($employee_id, $g['shift_idx'], $attDate, $from_date, $to_date);
                $cycle              = $empData['data_cycle'];
                $incentive          = $empData['data_incentive'];
                $dlk                = $empData['data_dlk'];
                // if($g->employee_id==1&&$attDate=='2024-10-03'){
                //     var_dump('<pre>');var_dump($sub);die;
                // }else{
                //     var_dump('<pre>');var_dump($sub);die;
                // }
                $employee_name      = $empData['employee_name'];
                $employee_code      = $empData['employee_code'];
                $sub_type           = empty($g['sub_type'])?NULL:(int)$g['sub_type'];
                $sub_status_confirm = empty($g['status_confirm'])?NULL:(int)$g['status_confirm'];
                $uTrans             = (float)$empData['transport_allowance'];
                $inc_val            = 0;
                $dlk_val            = 0;
                $status_absen       = 0;
                $rapel_sub          = (float)$g['rapel_sub'];
                $rapel_sub_date     = empty($g['rapel_sub_date'])?"NULL":"'".$g['rapel_sub_date']."'";
                $rapel_ot           = (float)$g['rapel_ot'];
                $rapel_ot_date      = empty($g['rapel_ot_date'])?"NULL":"'".$g['rapel_ot_date']."'";
                $rapel_inc          = (float)$g['rapel_inc'];
                $rapel_inc_date     = empty($g['rapel_inc_date'])?"NULL":"'".$g['rapel_inc_date']."'";
                $rapel_dlk          = (float)$g['rapel_dlk'];
                $rapel_dlk_date     = empty($g['rapel_dlk_date'])?"NULL":"'".$g['rapel_dlk_date']."'";
                if((int)$empData['shift_mode']==1){
                    if(!isset($valLembur[$g['employee_id']])){
                        $valLembur[$g['employee_id']] = 1;
                        $lemburx = (float)$g['overtime_allowance'];
                    }else{
                        $valLembur[$g['employee_id']] += 1;
                        $lemburx = 0;
                    }
                    // var_dump('<pre>');var_dump($cycle);die;
                    if(empty($cycle)){
                        $this->session->set_flashdata('message', "notiferror_a('Jadwal Multi Shift Untuk Karyawan [<b>$employee_name</b>] belum di seting.')");
                        redirect($this->link);die;
                    }
                    if(count($cycle)> 1){
                        $cycleFilterx = array_filter($cycle, function($cycle) use ($attDate) {
                            return $cycle['date'] === $attDate;
                        });
                        $cycleFilter    = reset($cycleFilterx);
                        $diffCheckIn    = strtotime($checkIn) - strtotime($cycleFilter['check_in']);
                    }else{
                        $diffCheckIn    = strtotime($checkIn) - strtotime($cycle[0]['check_in']);
                    }
                }else{
                    $lemburx        = (float)$g['value_overtime'];
                    $diffCheckIn    = strtotime($checkIn) - strtotime($empData[$cekInDay]);
                }
                if(!empty($incentive)){
                    if(count($incentive)> 1){
                        $incentiveFilterx = array_filter($incentive, function($incentive) use ($attDate) {
                            return $incentive['date'] === $attDate;
                        });
                        $incentiveFilter = reset($incentiveFilterx);
                        $inc_val = (float)$incentiveFilter['value'];
                    }else{
                        $inc_val = (float)$incentive[0]['value'];
                    }
                }
                if(!empty($dlk)){
                    if(count($dlk)> 1){
                        $dlkFilterx = array_filter($dlk, function($dlk) use ($attDate) {
                            return $dlk['date'] === $attDate;
                        });
                        $dlkFilter = reset($dlkFilterx);
                        $dlk_val = (float)$dlkFilter['value'];
                    }else{
                        $dlk_val = (float)$dlk[0]['value'];
                    }
                }
                $status_overtime    = (int)$g['status_overtime'];
                $status_piket       = (int)$g['status_piket'];
                $status_except_um   = (int)$g['status_except_um'];
                $diffInMinutes      = floor($diffCheckIn / 60);
                $tolerance          = $empData['data_tolerance'];
                $cek = '';
                $umtFix = 0;
                $mealCut = 0;
                if(!empty($sub_type)){
                    if($sub_type==1 && $sub_type==2){
                        if($sub_status_confirm==1){
                            $umtFix = (float)$empData['meal_allowance'];
                        }else{
                            $umtFix = 0;
                        }
                        $mealCut = 0;
                    }else{
                        $umtFix = 0;
                        $mealCut = 0;
                    }
                }else{
                    if($status_piket==1){
                        $mealAl += (float)$empData['meal_allowance'];
                        $umtFix = (float)$empData['meal_allowance'];
                        $status_absen = 11;
                        break;
                    }else{
                        $iTol = 1;
                        foreach($tolerance as $tl){
                            $pattern = '/\[([^\]]+)\]/';
                            preg_match_all($pattern, $tl['rumus'], $matches);
                            if(count($matches[1])>1){
                                $msg['status']  = false;
                                $msg['msg']     = 'Rumus tidak sesuai di menu <b>Master->Jadwal Kerja</b> .';
                                echo json_encode($msg);die;
                            }
                            $pengkali = $matches[1][0];
                            if($diffInMinutes <= 0){
                                // $cek .= 'masuk $diffInMinutes <= 0';
                                $mealAl += (float)$empData['meal_allowance'];
                                $umtFix = (float)$empData['meal_allowance'];
                                $mealCut = 0;
                                if($status_overtime==1){
                                    $status_absen = 10;
                                }else{
                                    $status_absen = 1;
                                }
                                break;
                            }elseif($tl['tolerance_in_end']==0 && $diffInMinutes > $tl['tolerance_in_start']){
                                // $cek .= 'masuk $tl["tolerance_in_end"]==0 && $diffInMinutes > $tl["tolerance_in_start"]';
                                $status_absen = 7;
                                if($status_except_um==1){
                                    $mealAl += (float)$empData['meal_allowance'];
                                    $umtFix = (float)$empData['meal_allowance'];
                                    $mealCut = 0;
                                }else{
                                    if(strtolower($pengkali)=='um'){
                                        $rumus = str_replace('[um]', (float)$empData['meal_allowance'], $tl['rumus']);
                                        $potongan = eval('return '.$rumus.';');
                                        $mealAl += (float)$potongan;
                                        $umtFix = (float)$potongan;
                                        $mealCut = (float)$empData['meal_allowance'] - (float)$potongan;
                                        if($mealCut<0){
                                            $mealCut = 0;
                                        }
                                    }elseif(strtolower($pengkali)=='ut'){
                                        $rumus = str_replace('[ut]', (float)$empData['transport_allowance'], $tl['rumus']);
                                        $potongan = eval('return '.$rumus.';');
                                        $mealAl += (float)$potongan;
                                        $umtFix = (float)$potongan;
                                        $mealCut = (float)$empData['meal_allowance'] - (float)$potongan;
                                        if($mealCut<0){
                                            $mealCut = 0;
                                        }
                                    }else{
                                        $msg['status']  = false;
                                        $msg['msg']     = 'Jenis potongan tidak ditemukan .';
                                        echo json_encode($msg);die;
                                    }
                                }
                                break;
                            }elseif($diffInMinutes > $tl['tolerance_in_start'] && $diffInMinutes <= $tl['tolerance_in_end']){
                                // $cek .= 'masuk $diffInMinutes > $tl["tolerance_in_start"] && $diffInMinutes <= $tl["tolerance_in_end"]';
                                if($iTol==2){
                                    $status_absen = 6;
                                }else{
                                    if($status_overtime==1){
                                        $status_absen = 10;
                                    }else{
                                        $status_absen = 1;
                                    }
                                }
                                if($status_except_um==1){
                                    $mealAl += (float)$empData['meal_allowance'];
                                    $umtFix = (float)$empData['meal_allowance'];
                                    $mealCut = 0;
                                }else{
                                    if(strtolower($pengkali)=='um'){
                                        $rumus = str_replace('[um]', (float)$empData['meal_allowance'], $tl['rumus']);
                                        $potongan = eval('return '.$rumus.';');
                                        $mealAl += (float)$potongan;
                                        $umtFix = (float)$potongan;
                                        $mealCut = (float)$empData['meal_allowance'] - (float)$potongan;
                                        if($mealCut<0){
                                            $mealCut = 0;
                                        }
                                    }elseif(strtolower($pengkali)=='ut'){
                                        $rumus = str_replace('[ut]', (float)$empData['transport_allowance'], $tl['rumus']);
                                        $potongan = eval('return '.$rumus.';');
                                        $mealAl += (float)$potongan;
                                        $umtFix = (float)$potongan;
                                        $mealCut = (float)$empData['meal_allowance'] - (float)$potongan;
                                        if($mealCut<0){
                                            $mealCut = 0;
                                        }
                                    }else{
                                        $msg['status']  = false;
                                        $msg['msg']     = 'Jenis potongan tidak ditemukan .';
                                        echo json_encode($msg);die;
                                    }
                                }
                                break;
                            }
                            $iTol++;
                        }
                    }
                }
                // var_dump('<pre>');var_dump($valRapel);die;
                // if(!isset($valMeal[$g->employee_id])) {
                //     $valMeal[$g->employee_id] = $mealAl;
                //     $valTransp[$g->employee_id] = (float)$empData['transport_allowance'];
                //     $oTime[$g->employee_id] = (float)$g->value_overtime;
                //     // if($status_overtime==1){
                //     // }else{
                //     //     $oTime[$g->employee_id] = (float)0;
                //     // }
                // } else {
                //     $valMeal[$g->employee_id] += $mealAl;
                //     $valTransp[$g->employee_id] += (float)$empData['transport_allowance'];
                //     $oTime[$g->employee_id] += (float)$g->value_overtime;
                //     // if($holiday>0){
                //     //     if($status_overtime==1){
                //     //         $oTime[$g->employee_id] += (float)$empData['overtime_allowance'];
                //     //     }else{
                //     //         $oTime[$g->employee_id] += (float)0;
                //     //     }
                //     // }else{
                //     //     if(empty($empData[$cekInDay])){
                //     //         if($status_overtime==1){
                //     //             $oTime[$g->employee_id] += (float)$empData['overtime_allowance'];
                //     //         }else{
                //     //             $oTime[$g->employee_id] += (float)0;
                //     //         }
                //     //     }else{
                //     //         $oTime[$g->employee_id] += (float)0;
                //     //     }
                //     // }
                // }
                $total = $umtFix + $lemburx + $uTrans + $inc_val + $dlk_val + $rapel_sub + $rapel_ot + $rapel_inc + $rapel_dlk;
                $allHalfDetail .= "(".trim($all_id_new).", $employee_id, '$employee_name','$employee_code', '$attDate', $umtFix, $mealCut, $rapel_sub, $rapel_sub_date, $lemburx, $rapel_ot, $rapel_ot_date, $uTrans, $inc_val, $rapel_inc, $rapel_inc_date, $dlk_val, $rapel_dlk, $rapel_dlk_date, $total, '$desc',$company, $status_absen, 1, $login, '$now'),";
                if(!empty($g['idx'])){
                    $updateAttendance .= "(".$g['idx'].", 1, $login, '$now'),";
                    $attIdx .= $g['idx'].",";
                }
                if(!empty($g['rapel_ot_idx'])){
                    $updateOt .= $g['rapel_ot_idx'].",";
                }
                if(!empty($g['rapel_dlk_idx'])){
                    $updateDlk .= $g['rapel_dlk_idx'].",";
                }
                if(!empty($g['rapel_inc_idx'])){
                    $updateInc .= $g['rapel_inc_idx'].",";
                }
                if(!empty($g['rapel_sub_idx'])){
                    $updateSub .= $g['rapel_sub_idx'].",";
                }
                $grand_total += $total;
                $i++;
            }
            // var_dump('<pre>');var_dump($updateOt);var_dump($updateDlk);var_dump($updateInc);var_dump($updateSub);die;
            // foreach($valMeal as $k => $v){
            //     $lembur = $oTime[$k];
            //     $transport = $valTransp[$k];
            //     $total = $lembur + $transport + $v;
            //     $empData2 = $this->mUmt->get_employee($k);
            //     $employee_name = $empData2['employee_name'];
            //     $employee_code = $empData2['employee_code'];
            //     $allHalfDetail .= "(".trim($all_id_new).", ".trim($k).", '$employee_name','$employee_code', $v, $lembur, $transport, $total, '$desc',$company, 1, $login, '$now'),";
            //     $grand_total += $total;
            //     $i++;
            // }

            $attIdx = substr($attIdx,0,strlen($attIdx)-1);

            $updateOtQuery = "";
            $updateDlkQuery = "";
            $updateIncQuery = "";
            $updateSubQuery = "";
            if(!empty($updateOt)){
                $updateOt = substr($updateOt,0,strlen($updateOt)-1);
                $updateOtQuery = "UPDATE attendance_employee SET status_draft_paid_ot = 1 WHERE idx IN($updateOt)";
            }
            if(!empty($updateDlk)){
                $updateDlk = substr($updateDlk,0,strlen($updateDlk)-1);
                $updateDlkQuery = "UPDATE dlk_detail SET status_draft_paid = 1 WHERE idx IN($updateDlk)";
            }
            if(!empty($updateSub)){
                $updateSub = substr($updateSub,0,strlen($updateSub)-1);
                $updateSubQuery = "UPDATE submission_detail SET status_draft_paid = 1 WHERE idx IN($updateSub)";
            }
            if(!empty($updateInc)){
                $updateInc = substr($updateInc,0,strlen($updateInc)-1);
                $updateIncQuery = "UPDATE incentive_detail SET status_draft_paid = 1 WHERE idx IN($updateInc)";
            }

            $insert_umt_header = [
                'allowh_id' => $all_id_new,
                'allowance_code' => $all_no,
                'grandtotal' => $grand_total,
                'start' => $from_date,
                'end' => $to_date,
                'total_item' => $i,
                'all_att_idx' => $attIdx,
                'all_sub_idx' => $updateSub,
                'all_ot_idx' => $updateOt,
                'all_inc_idx' => $updateInc,
                'all_dlk_idx' => $updateDlk,
                'description' => $desc,
                'company_idx' => $company,
                'status' => 1,
                'created_on' => $now,
                'created_by' => $login
            ];

            $updateAttendance = substr($updateAttendance,0,strlen($updateAttendance)-1);
            $allHalfDetail = substr($allHalfDetail,0,strlen($allHalfDetail)-1);
            $updateAttendanceQuery = "INSERT INTO attendance_employee (idx, status_draft_half, modified_by, modified_on) VALUES $updateAttendance ON DUPLICATE KEY UPDATE status_draft_half=VALUES(status_draft_half), modified_by=VALUES(modified_by), modified_on=VALUES(modified_on)";
            $insertAllHalfDetail = "INSERT INTO allowance_half_detail (allowh_id, employee_id, employee_name, employee_code, allowh_date, meal_allowance, meal_cutting, rapel_sub, rapel_sub_date, overtime_allowance, rapel_ot, rapel_ot_date, transport_allowance, incentive_allowance, rapel_inc, rapel_inc_date, dlk_allowance, rapel_dlk, rapel_dlk_date, allowance_value, `description`, company_idx, status_absen, `status`, created_by, created_on) VALUES $allHalfDetail";
            // var_dump('<pre>');var_dump($allHalfDetail);die;

			$cek = $this->mUmt->insertUmt($insert_umt_header, $updateAttendanceQuery, $insertAllHalfDetail, $updateOtQuery, $updateDlkQuery, $updateSubQuery, $updateIncQuery);
			if($cek == 1){
			    $encrypt_all_id = $this->secure->enc($all_id_new);
			    redirect($this->link.'/selectUmt/'.$encrypt_all_id);
			}else{
			    $this->session->set_flashdata('message', "notiferror_a('Error connection,<br>Please try again later.')");
			    redirect($this->link);
			}
		}else{
		    $all_idx = $this->secure->dec($all_id);
            $back = '<a href="'.$this->link.'" class="list-icons-item" title="Back To List Data" data-placement="right" data-popup="tooltip"><i class="icon-undo2 pr-1" style="font-size:0.8em;padding-top: 4px;"></i>
            </a>';
			if(!empty($this->security_function->permissions($this->filename . "-x"))){
                $add = '<a href="javascript:void(0);" id="btnAdd" class="pull-right text-white small bAdd" title="Add New Detail Invoice" data-placement="right" data-popup="tooltip"><i class="icon-plus-circle2"></i></a>';
				$data['write']  = "<div class='btn-group'>$back</div>";
				$data['lock']   = 1;
			}else{
                $add = '<i class="icon-lock pull-right pt-1 " title="Locked New Detail Invoice" data-placement="right" data-popup="tooltip"></i>';
				$data['write']  = "<div class='btn-group'>$back</div>";
				$data['lock']   = 0;
			}
			$data['header']     = $this->mUmt->getAllowHeader($all_idx);
			// var_dump('<pre>');var_dump($all_idx);var_dump($data['header']);die;
			$data['allowh_id']  = $all_id;
			$data['from_date']  = $data['header']['start'];
			$data['to_date']    = $data['header']['end'];
			$data['formact']    = $this->link.'/updateDataUmt/'.$all_id;
			$data['title'] 	    = "Job Bebas";
			$data['link'] 	    = $this->link;
			$data['link2'] 	    = $this->link.'/unSelectClient';
            $params             = '{"base_url": "'.base_url().'", "link": "'.$this->link.'", "csrf": "'.$this->session->csrf_token.'", "permW": "'.$this->security_function->permissions($this->filename . "-w").'", "permC": "'.$this->security_function->permissions($this->filename . "-c").'", "permX": "'.$this->security_function->permissions($this->filename . "-x").'", "post": 0, "allowh_id": "'.$all_id.'", "from": "'.$data['header']['start'].'", "to": "'.$data['header']['end'].'"}';
            // encrypt
            $encrypted          = CryptoJsAes::encrypt($params, $this->enkey);
            $data['params']     = $encrypted;
			$this->template->views('payroll/v_umt_detail', $data);
		}
    }

    public function selectUmtOld($all_id="")
    {
		$access = $this->security_function->permissions($this->filename . "-r");
		if (empty($access)) die("<center><h1>You can not get the right to access this module</h1></center>");
		if(empty($all_id)){
    		// $all_idx 	= $this->secure->dec($all_id);
    		$from_date 	= filter_var(trim($this->input->post('from',TRUE)), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
    		$to_date 	= filter_var(trim($this->input->post('to',TRUE)), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
    		$desc 	    = filter_var(trim($this->input->post('description',TRUE)), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$all_id_new = $this->ModelGenId->genIdUnlimited('ALLWHID', $this->idx);
			$idx        = $this->ModelGenId->genIdYear('ALLWH', $this->idx);
			$all_no     = $idx.'/UMT/'.$this->officeCode.'/'.numberToRoman(date('m')).'/'.date('Y');
            $login      = $this->idx;
            $company    = $this->office;
            $now        = date('Y-m-d H:i:s');

            $getUmt = $this->mUmt->getUmtData($from_date, $to_date, $this->office);
            if(empty($getUmt)){
                $this->session->set_flashdata('message', "notiferror_a('Error connection,<br>Please try again later.')");
			    redirect($this->link);die;
            }

            $valMeal    = array();
            $valTransp  = array();
            $oTime      = array();
            $tangal     = array();
            $updateAttendance ='';
            $attIdx = '';
            $allHalfDetail ='';
            foreach ($getUmt as $g) {
                $cekInDay = strtolower(date('l', strtotime($g->check_in)));
                $cekInDate = strtolower(date('Y-m-d', strtotime($g->check_in)));
                $empData = $this->mUmt->get_employee($g->employee_id);
                $holiday = $this->mUmt->check_holiday($cekInDate);
                $checkIn = date('H:i:s', strtotime($g->check_in));
                $diffCheckIn = strtotime($checkIn) - strtotime($empData[$cekInDay]);
                $diffInMinutes = floor($diffCheckIn / 60);
                $status_overtime = (int)$g->status_overtime;
                $tolerance = $empData['data_tolerance'];
                $mealAl = 0;
                foreach($tolerance as $tl){
                    $pattern = '/\[([^\]]+)\]/';
                    preg_match_all($pattern, $tl['rumus'], $matches);
                    $umtFix = null;
                    if(count($matches[1])>1){
                        $this->session->set_flashdata('message', "notiferror_a('Rumus tidak sesuai di menu <b>Master->Jadwal Kerja</b> .')");
			            redirect($this->link);die;
                    }
                    $pengkali = $matches[1][0];
                    if($tl['tolerance_in_end']==0){
                        if($diffInMinutes > $tl['tolerance_in_start']){
                            if(strtolower($pengkali)=='um'){
                                $rumus = str_replace('[um]', (float)$empData['meal_allowance'], $tl['rumus']);
                                $potongan = eval('return '.$rumus.';');
                                $umtFix = (float)$potongan;
                            }elseif(strtolower($pengkali)=='ut'){
                                $rumus = str_replace('[ut]', (float)$empData['transport_allowance'], $tl['rumus']);
                                $potongan = eval('return '.$rumus.';');
                                $umtFix = (float)$potongan;
                            }else{
                                $this->session->set_flashdata('message', "notiferror_a('Jenis potongan tidak ditemukan .')");
                                redirect($this->link);die;
                            }
                        }else{
                            $umtFix = 0;
                        }
                    }else{
                        if($diffInMinutes > $tl['tolerance_in_start'] && $diffInMinutes <= $tl['tolerance_in_end']){
                            if(strtolower($pengkali)=='um'){
                                $rumus = str_replace('[um]', (float)$empData['meal_allowance'], $tl['rumus']);
                                $potongan = eval('return '.$rumus.';');
                                $umtFix = (float)$potongan;
                            }elseif(strtolower($pengkali)=='ut'){
                                $rumus = str_replace('[ut]', (float)$empData['transport_allowance'], $tl['rumus']);
                                $potongan = eval('return '.$rumus.';');
                                $umtFix = (float)$potongan;
                            }else{
                                $this->session->set_flashdata('message', "notiferror_a('Jenis potongan tidak ditemukan .')");
                                redirect($this->link);die;
                            }
                        }elseif($diffInMinutes <= $tl['tolerance_in_start']){
                            if(strtolower($pengkali)=='um'){
                                $rumus = str_replace('[um]', (float)$empData['meal_allowance'], $tl['rumus']);
                                $potongan = eval('return '.$rumus.';');
                                $umtFix = (float)$potongan;
                            }elseif(strtolower($pengkali)=='ut'){
                                $rumus = str_replace('[ut]', (float)$empData['transport_allowance'], $tl['rumus']);
                                $potongan = eval('return '.$rumus.';');
                                $umtFix = (float)$potongan;
                            }else{
                                $this->session->set_flashdata('message', "notiferror_a('Jenis potongan tidak ditemukan .')");
                                redirect($this->link);die;
                            }
                        }else{
                            $umtFix = 0;
                        }
                    }
                    $mealAl += $umtFix;
                }
                if(!isset($valMeal[$g->employee_id])) {
                    $valMeal[$g->employee_id] = $mealAl;
                    $valTransp[$g->employee_id] = (float)$empData['transport_allowance'];
                    if($status_overtime==1){
                        $oTime[$g->employee_id] = (float)$empData['overtime_allowance'];
                    }else{
                        $oTime[$g->employee_id] = 0;
                    }
                } else {
                    $valMeal[$g->employee_id] += $mealAl;
                    $valTransp[$g->employee_id] += (float)$empData['transport_allowance'];
                    if($holiday>0){
                        if($status_overtime==1){
                            $oTime[$g->employee_id] += (float)$empData['overtime_allowance'];
                        }else{
                            $oTime[$g->employee_id] += 0;
                        }
                    }else{
                        if(empty($empData[$cekInDay])){
                            if($status_overtime==1){
                                $oTime[$g->employee_id] += (float)$empData['overtime_allowance'];
                            }else{
                                $oTime[$g->employee_id] += 0;
                            }
                        }else{
                            $oTime[$g->employee_id] += 0;
                        }
                    }
                }
                $updateAttendance .= "(".$g->idx.", 1, $login, '$now'),";
                $attIdx .= $g->idx.",";
            }
            $grand_total = 0;
            $i = 0;
            foreach($valMeal as $k => $v){
                $lembur = $oTime[$k];
                $transport = $valTransp[$k];
                $total = $lembur + $transport + $v;
                $empData2 = $this->mUmt->get_employee($k);
                $employee_name = $empData2['employee_name'];
                $employee_code = $empData2['employee_code'];
                $allHalfDetail .= "(".trim($all_id_new).", ".trim($k).", '$employee_name','$employee_code', $v, $lembur, $transport, $total, '$desc',$company, 1, $login, '$now'),";
                $grand_total += $total;
                $i++;
            }

            $attIdx = substr($attIdx,0,strlen($attIdx)-1);
            $insert_umt_header = [
                'allowh_id' => $all_id_new,
                'allowance_code' => $all_no,
                'grandtotal' => $grand_total,
                'start' => $from_date,
                'end' => $to_date,
                'total_item' => $i,
                'all_att_idx' => $attIdx,
                'description' => $desc,
                'company_idx' => $company,
                'status' => 1,
                'created_on' => $now,
                'created_by' => $login
            ];

            $updateAttendance = substr($updateAttendance,0,strlen($updateAttendance)-1);
            $allHalfDetail = substr($allHalfDetail,0,strlen($allHalfDetail)-1);
            $updateAttendanceQuery = "INSERT INTO attendance_employee (idx, status_draft_half, modified_by, modified_on) VALUES $updateAttendance ON DUPLICATE KEY UPDATE status_draft_half=VALUES(status_draft_half), modified_by=VALUES(modified_by), modified_on=VALUES(modified_on)";
            $insertAllHalfDetail = "INSERT INTO allowance_half_detail (allowh_id, employee_id, employee_name, employee_code, meal_allowance, overtime_allowance, transport_allowance, allowance_value, `description`, company_idx, `status`, created_by, created_on) VALUES $allHalfDetail";
            // var_dump('<pre>');var_dump($insert_umt_header);die;

			$cek = $this->mUmt->insertUmt($insert_umt_header, $updateAttendanceQuery, $insertAllHalfDetail);
			if($cek == 1){
			    $encrypt_all_id = $this->secure->enc($all_id_new);
			    redirect($this->link.'/selectUmt/'.$encrypt_all_id);
			}else{
			    $this->session->set_flashdata('message', "notiferror_a('Error connection,<br>Please try again later.')");
			    redirect($this->link);
			}
		}else{
		    $all_idx = $this->secure->dec($all_id);
            $back = '<a href="'.$this->link.'" class="list-icons-item" title="Back To List Data" data-placement="right" data-popup="tooltip"><i class="icon-undo2 pr-1" style="font-size:0.8em;padding-top: 4px;"></i>
            </a>';
			if(!empty($this->security_function->permissions($this->filename . "-x"))){
                $add = '<a href="javascript:void(0);" id="btnAdd" class="pull-right text-white small bAdd" title="Add New Detail Invoice" data-placement="right" data-popup="tooltip"><i class="icon-plus-circle2"></i></a>';
				$data['write']  = "<div class='btn-group'>$back</div>";
				$data['lock']   = 1;
			}else{
                $add = '<i class="icon-lock pull-right pt-1 " title="Locked New Detail Invoice" data-placement="right" data-popup="tooltip"></i>';
				$data['write']  = "<div class='btn-group'>$back</div>";
				$data['lock']   = 0;
			}
			$data['header'] 	    = $this->mUmt->getAllowHeader($all_idx);
			// var_dump('<pre>');var_dump($all_idx);var_dump($data['header']);die;
			$data['allowh_id'] 		= $all_id;
			$data['from_date'] 		= $data['header']['start'];
			$data['to_date'] 		= $data['header']['end'];
			$data['formact'] 		= $this->link.'/updateDataUmt/'.$all_id;
			$data['title'] 			= "Job Bebas";
			$data['link'] 			= $this->link;
			$data['link2'] 			= $this->link.'/unSelectClient';
			$this->template->views('payroll/v_umt_detail', $data);
		}
    }

    public function getDataHeader($all_id)
    {
        $all_idx    = $this->secure->dec($all_id);
        $result     = $this->mUmt->getDataHeader($all_idx);
        if(empty($result)){
            $msg['status']  = false;
            $msg['msg']     = 'Data tidak ditemukan.';
            echo json_encode($msg);die;
        }else{
            $msg['status']  = true;
            $msg['data']    = $result;
            echo json_encode($msg);die;
        }
    }

    public function updateDataUmt($all_id)
    {
        $all_idx    = $this->secure->dec($all_id);
        $from_date 	= filter_var(trim($this->input->post('from',TRUE)), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $to_date 	= filter_var(trim($this->input->post('to',TRUE)), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $desc 	    = filter_var(trim($this->input->post('description',TRUE)), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $login      = $this->idx;
        $company    = $this->office;
        $now        = date('Y-m-d H:i:s');
        $nowDate    = date('Y-m-d');

        $getUmtHeader = $this->mUmt->getDataHeader($all_idx);
        if(empty($getUmtHeader)){
            $msg['status']  = false;
            $msg['msg']     = 'Data absen tidak ditemukan.';
            echo json_encode($msg);die;
        }

        $all_att_idx_old = $getUmtHeader['all_att_idx'];
        $all_sub_idx_old = $getUmtHeader['all_sub_idx'];
        $all_ot_idx_old = $getUmtHeader['all_ot_idx'];
        $all_inc_idx_old = $getUmtHeader['all_inc_idx'];
        $all_dlk_idx_old = $getUmtHeader['all_dlk_idx'];

        if($getUmtHeader['start'] != $from_date || $getUmtHeader['end'] != $to_date){
            $getUmt = $this->mUmt->getUmtDataEscDrafted($from_date, $to_date, $this->office);
            // var_dump('<pre>');var_dump($getUmt);die;
            if(empty($getUmt)){
                $msg['status']  = false;
                $msg['msg']     = 'Data absen tidak ditemukan.';
                echo json_encode($msg);die;
            }
    
            $valRapel   = array();
            $valMeal    = array();
            $valTransp  = array();
            $oTime      = array();
            $tangal     = array();
            $valLembur  = array();
            $updateAttendance ='';
            $updateOt ='';
            $updateDlk ='';
            $updateInc ='';
            $updateSub ='';
            $attIdx = '';
            $allHalfDetail ='';
            $newAllHalfDetail ='';
            $grand_total = 0;
            $i = 0;
            $result = [];
            foreach($getUmt as $a){
                $employee_id = $a->employee_id;
                $attendance_date = $a->attendance_date;

                $key = $employee_id.'_'.$attendance_date;

                // Jika kunci belum ada, inisialisasi array baru
                if (!isset($result[$key])) {
                    $result[$key] = [
                        "idx" => $a->idx,
                        "rapel_ot_idx" => NULL,
                        "rapel_sub_idx" => NULL,
                        "rapel_dlk_idx" => NULL,
                        "rapel_inc_idx" => NUll,
                        "attendance_date" => $a->attendance_date,
                        "employee_id" => $a->employee_id,
                        "sub_type" => $a->sub_type,
                        "status_confirm" => $a->status_confirm,
                        "check_in" => $a->check_in,
                        "check_out" => $a->check_out,
                        "shift_idx" => $a->shift_idx,
                        "get_name" => $a->get_name,
                        "dynamic_check_in" => $a->dynamic_check_in,
                        "value_overtime" => $a->value_overtime,
                        "overtime_confirm_date" => $a->overtime_confirm_date,
                        "overtime_allowance" => $a->overtime_allowance,
                        "status_overtime" => $a->status_overtime,
                        "status_piket" => $a->status_piket,
                        "status_except_um" => $a->status_except_um,
                        "rapel_ot" => 0,
                        "rapel_ot_date" => $a->rapel_ot_date,
                        "rapel_sub" => 0,
                        "rapel_sub_date" => $a->rapel_sub_date,
                        "rapel_dlk" => 0,
                        "rapel_dlk_date" => $a->rapel_dlk_date,
                        "rapel_inc" => 0,
                        "rapel_inc_date" => $a->rapel_inc_date,
                        "shift_mode" => $a->shift_mode,
                        "monday" => $a->monday,
                        "tuesday" => $a->tuesday,
                        "wednesday" => $a->wednesday,
                        "thursday" => $a->thursday,
                        "friday" => $a->friday,
                        "saturday" => $a->saturday,
                        "sunday" => $a->sunday,
                    ];
                }

                $result[$key]['rapel_ot_idx'] = trim($result[$key]['rapel_ot_idx'] . ',' . $a->rapel_ot_idx, ',');
                $result[$key]['rapel_sub_idx'] = trim($result[$key]['rapel_sub_idx'] . ',' . $a->rapel_sub_idx, ',');
                $result[$key]['rapel_dlk_idx'] = trim($result[$key]['rapel_dlk_idx'] . ',' . $a->rapel_dlk_idx, ',');
                $result[$key]['rapel_inc_idx'] = trim($result[$key]['rapel_inc_idx'] . ',' . $a->rapel_inc_idx, ',');

                $result[$key]['rapel_ot'] += $a->rapel_ot;
                $result[$key]['rapel_sub'] += $a->rapel_sub;
                $result[$key]['rapel_dlk'] += $a->rapel_dlk;
                $result[$key]['rapel_inc'] += $a->rapel_inc;
            }

            $getUmtNew = array_values($result);

            // var_dump('<pre>');var_dump($getUmtNew);die;
            
            foreach ($getUmtNew as $g) {
                $mealAl             = 0;
                $cekInDay           = strtolower(date('l', strtotime($g['check_in'])));
                $cekInDate          = strtolower(date('Y-m-d', strtotime($g['check_in'])));
                $checkIn            = date('H:i:s', strtotime($g['check_in']));
                $attDate            = $g['attendance_date'];
                $employee_id        = $g['employee_id'];
                $shift_idx          = $g['shift_idx'];
                $empData            = $this->mUmt->get_employee_data($employee_id, $shift_idx, $attDate, $from_date, $to_date);
                if(empty($empData)){
                    $msg['status']  = false;
                    $msg['msg']     = "Data Absensi Karyawan [<b>$employee_name</b>] tidak ditemukan.<br>Silahkan hubungi IT.";
                    echo json_encode($msg);die;
                }
                $cycle              = $empData['data_cycle'];
                $incentive          = $empData['data_incentive'];
                $dlk                = $empData['data_dlk'];
                $employee_name      = $empData['employee_name'];
                $employee_code      = $empData['employee_code'];
                $sub_type           = empty($g['sub_type'])?NULL:(int)$g['sub_type'];
                $sub_status_confirm = empty($g['status_confirm'])?NULL:(int)$g['status_confirm'];
                $uTrans             = (float)$empData['transport_allowance'];
                $inc_val            = 0;
                $dlk_val            = 0;
                $status_absen       = 0;
                $rapel_sub          = (float)$g['rapel_sub'];
                $rapel_sub_date     = empty($g['rapel_sub_date'])?"NULL":"'".$g['rapel_sub_date']."'";
                $rapel_ot           = (float)$g['rapel_ot'];
                $rapel_ot_date      = empty($g['rapel_ot_date'])?"NULL":"'".$g['rapel_ot_date']."'";
                $rapel_inc          = (float)$g['rapel_inc'];
                $rapel_inc_date     = empty($g['rapel_inc_date'])?"NULL":"'".$g['rapel_inc_date']."'";
                $rapel_dlk          = (float)$g['rapel_dlk'];
                $rapel_dlk_date     = empty($g['rapel_dlk_date'])?"NULL":"'".$g['rapel_dlk_date']."'";
                if((int)$empData['shift_mode']==1){
                    if(!isset($valLembur[$employee_id])){
                        $valLembur[$employee_id] = 1;
                        $lemburx = (float)$g['overtime_allowance'];
                    }else{
                        $valLembur[$employee_id] += 1;
                        $lemburx = 0;
                    }
                    if(empty($cycle)){
                        $msg['status']  = false;
                        $msg['msg']     = "Jadwal Multi Shift Untuk Karyawan [<b>$employee_name</b>] belum di seting.";
                        echo json_encode($msg);die;
                    }
                    if(count($cycle)> 1){
                        $cycleFilterx = array_filter($cycle, function($cycle) use ($attDate) {
                            return $cycle['date'] === $attDate;
                        });
                        $cycleFilter = reset($cycleFilterx);
                        $diffCheckIn        = strtotime($checkIn) - strtotime($cycleFilter['check_in']);
                    }else{
                        $diffCheckIn        = strtotime($checkIn) - strtotime($cycle[0]['check_in']);
                    }
                }else{
                    if(date('Y-m-d', strtotime($g['overtime_confirm_date'])) <= $from_date && date('Y-m-d', strtotime($g['overtime_confirm_date'])) >= $to_date){
                        $lemburx    = (float)$g['value_overtime'];
                    }else{
                        $lemburx    = 0;
                    }
                    $diffCheckIn    = strtotime($checkIn) - strtotime($empData[$cekInDay]);
                }
                if(!empty($incentive)){
                    if(count($incentive)> 1){
                        $incentiveFilterx = array_filter($incentive, function($incentive) use ($attDate) {
                            return $incentive['date'] === $attDate;
                        });
                        $incentiveFilter = reset($incentiveFilterx);
                        $inc_val = (float)$incentiveFilter['value'];
                    }else{
                        $inc_val = (float)$incentive[0]['value'];
                    }
                }
                if(!empty($dlk)){
                    if(count($dlk)> 1){
                        $dlkFilterx = array_filter($dlk, function($dlk) use ($attDate) {
                            return $dlk['date'] === $attDate;
                        });
                        $dlkFilter = reset($dlkFilterx);
                        $dlk_val = (float)$dlkFilter['value'];
                    }else{
                        $dlk_val = (float)$dlk[0]['value'];
                    }
                }
                // if($g->employee_id==5 && $attDate == '2024-10-18'){
                //     var_dump('<pre>');var_dump($incentive);var_dump($rapel_inc);die;
                // }
                $status_overtime    = (int)$g['status_overtime'];
                $status_piket       = (int)$g['status_piket'];
                $status_except_um   = (int)$g['status_except_um'];
                $diffInMinutes      = floor($diffCheckIn / 60);
                $tolerance          = $empData['data_tolerance'];
                $cek = '';
                $umtFix = 0;
                $mealCut = 0;
                $iTol = 1;
                if(!empty($sub_type)){
                    if($sub_type==1 && $sub_type==2){
                        if($sub_status_confirm==1){
                            $umtFix = (float)$empData['meal_allowance'];
                        }else{
                            $umtFix = 0;
                        }
                        $mealCut = 0;
                    }else{
                        $umtFix = 0;
                        $mealCut = 0;
                    }
                }else{
                    foreach($tolerance as $tl){
                        $pattern = '/\[([^\]]+)\]/';
                        preg_match_all($pattern, $tl['rumus'], $matches);
                        if(count($matches[1])>1){
                            $msg['status']  = false;
                            $msg['msg']     = 'Rumus tidak sesuai di menu <b>Master->Jadwal Kerja</b> .';
                            echo json_encode($msg);die;
                        }
                        $pengkali = $matches[1][0];
                        if($status_piket==1){
                            $mealAl += (float)$empData['meal_allowance'];
                            $umtFix = (float)$empData['meal_allowance'];
                            $status_absen = 11;
                            break;
                        }else{
                            if($diffInMinutes <= 0){
                                $mealAl += (float)$empData['meal_allowance'];
                                $umtFix = (float)$empData['meal_allowance'];
                                $mealCut = 0;
                                if($status_overtime==1){
                                    $status_absen = 10;
                                }else{
                                    $status_absen = 1;
                                }
                                break;
                            }elseif($tl['tolerance_in_end']==0 && $diffInMinutes > $tl['tolerance_in_start']){
                                $status_absen = 7;
                                if($status_except_um==1){
                                    $mealAl += (float)$empData['meal_allowance'];
                                    $umtFix = (float)$empData['meal_allowance'];
                                    $mealCut = 0;
                                }else{
                                    if(strtolower($pengkali)=='um'){
                                        $rumus = str_replace('[um]', (float)$empData['meal_allowance'], $tl['rumus']);
                                        $potongan = eval('return '.$rumus.';');
                                        $mealAl += (float)$potongan;
                                        $umtFix = (float)$potongan;
                                        $mealCut = (float)$empData['meal_allowance'] - (float)$potongan;
                                        if($mealCut<0){
                                            $mealCut = 0;
                                        }
                                    }elseif(strtolower($pengkali)=='ut'){
                                        $rumus = str_replace('[ut]', (float)$empData['transport_allowance'], $tl['rumus']);
                                        $potongan = eval('return '.$rumus.';');
                                        $mealAl += (float)$potongan;
                                        $umtFix = (float)$potongan;
                                        $mealCut = (float)$empData['meal_allowance'] - (float)$potongan;
                                        if($mealCut<0){
                                            $mealCut = 0;
                                        }
                                    }else{
                                        $msg['status']  = false;
                                        $msg['msg']     = 'Jenis potongan tidak ditemukan .';
                                        echo json_encode($msg);die;
                                    }
                                }
                                break;
                            }elseif($diffInMinutes > $tl['tolerance_in_start'] && $diffInMinutes <= $tl['tolerance_in_end']){
                                if($iTol==2){
                                    $status_absen = 6;
                                }else{
                                    if($status_overtime==1){
                                        $status_absen = 10;
                                    }else{
                                        $status_absen = 1;
                                    }
                                }
                                if($status_except_um==1){
                                    $mealAl += (float)$empData['meal_allowance'];
                                    $umtFix = (float)$empData['meal_allowance'];
                                    $mealCut = 0;
                                }else{
                                    if(strtolower($pengkali)=='um'){
                                        $rumus = str_replace('[um]', (float)$empData['meal_allowance'], $tl['rumus']);
                                        $potongan = eval('return '.$rumus.';');
                                        $mealAl += (float)$potongan;
                                        $umtFix = (float)$potongan;
                                        $mealCut = (float)$empData['meal_allowance'] - (float)$potongan;
                                        if($mealCut<0){
                                            $mealCut = 0;
                                        }
                                    }elseif(strtolower($pengkali)=='ut'){
                                        $rumus = str_replace('[ut]', (float)$empData['transport_allowance'], $tl['rumus']);
                                        $potongan = eval('return '.$rumus.';');
                                        $mealAl += (float)$potongan;
                                        $umtFix = (float)$potongan;
                                        $mealCut = (float)$empData['meal_allowance'] - (float)$potongan;
                                        if($mealCut<0){
                                            $mealCut = 0;
                                        }
                                    }else{
                                        $msg['status']  = false;
                                        $msg['msg']     = 'Jenis potongan tidak ditemukan .';
                                        echo json_encode($msg);die;
                                    }
                                }
                                break;
                            }
                        }
                        $iTol++;
                    }
                }
                $total = $umtFix + $lemburx + $uTrans + $inc_val + $dlk_val + $rapel_sub + $rapel_ot + $rapel_inc + $rapel_dlk;
                $allHalfDetail .= "(".trim($all_idx).", $employee_id, '$employee_name', '$employee_code', '$attDate', $umtFix, $mealCut, $rapel_sub, $rapel_sub_date, $lemburx, $rapel_ot, $rapel_ot_date, $uTrans, $inc_val, $rapel_inc, $rapel_inc_date, $dlk_val, $rapel_dlk, $rapel_dlk_date, $total, '$desc', $company, $status_absen, 1, $login, '$now'),";
                if(!empty($g['idx'])){
                    $updateAttendance .= "(".$g['idx'].", 1, $login, '$now'),";
                    $attIdx .= $g['idx'].",";
                }
                if(!empty($g['rapel_ot_idx'])){
                    $updateOt .= $g['rapel_ot_idx'].",";
                }
                if(!empty($g['rapel_dlk_idx'])){
                    $updateDlk .= $g['rapel_dlk_idx'].",";
                }
                if(!empty($g['rapel_inc_idx'])){
                    $updateInc .= $g['rapel_inc_idx'].",";
                }
                if(!empty($g['rapel_sub_idx'])){
                    $updateSub .= $g['rapel_sub_idx'].",";
                }
                $grand_total += $total;
                $i++;
            }
            
            // var_dump('<pre>');var_dump($updateOt);var_dump($updateDlk);var_dump($updateInc);var_dump($updateSub);die;

            $attIdx = substr($attIdx,0,strlen($attIdx)-1);
    
            $updateOtQuery = "";
            $updateDlkQuery = "";
            $updateIncQuery = "";
            $updateSubQuery = "";
            $updateOtOldQuery = "";
            $updateDlkOldQuery = "";
            $updateIncOldQuery = "";
            $updateSubOldQuery = "";
            if(!empty($updateOt)){
                $updateOt = substr($updateOt,0,strlen($updateOt)-1);
                $updateOtQuery = "UPDATE attendance_employee SET status_draft_paid_ot = 1 WHERE idx IN($updateOt)";
            }
            if(!empty($updateDlk)){
                $updateDlk = substr($updateDlk,0,strlen($updateDlk)-1);
                $updateDlkQuery = "UPDATE dlk_detail SET status_draft_paid = 1 WHERE idx IN($updateDlk)";
            }
            if(!empty($updateSub)){
                $updateSub = substr($updateSub,0,strlen($updateSub)-1);
                $updateSubQuery = "UPDATE submission_detail SET status_draft_paid = 1 WHERE idx IN($updateSub)";
            }
            if(!empty($updateInc)){
                $updateInc = substr($updateInc,0,strlen($updateInc)-1);
                $updateIncQuery = "UPDATE incentive_detail SET status_draft_paid = 1 WHERE idx IN($updateInc)";
            }

            $update_umt_header = [
                'start' 	    => $from_date,
                'end' 		    => $to_date,
                'grandtotal'    => $grand_total,
                'total_item'    => $i,
                'all_att_idx'   => $attIdx,
                'all_sub_idx'   => empty($updateSub)?NULL:$updateSub,
                'all_ot_idx'    => empty($updateOt)?NULL:$updateOt,
                'all_inc_idx'   => empty($updateInc)?NULL:$updateInc,
                'all_dlk_idx'   => empty($updateDlk)?NULL:$updateDlk,
                'description'   => $desc,
                'modified_on'   => date("Y-m-d H:i:s"),
                'modified_by'   => $this->idx
            ];

            $allHalfDetail = substr($allHalfDetail,0,strlen($allHalfDetail)-1);
            $updateAttendanceQuery = "UPDATE attendance_employee SET status_draft_half = 0 WHERE idx IN($all_att_idx_old)";
            if(!empty($all_ot_idx_old)){
                $updateOtOldQuery = "UPDATE attendance_employee SET status_draft_paid_ot = 0 WHERE idx IN($all_ot_idx_old)";
            }
            if(!empty($all_sub_idx_old)){
                $updateSubOldQuery = "UPDATE submission_detail SET status_draft_paid = 0 WHERE idx IN($all_sub_idx_old)";
            }
            if(!empty($all_inc_idx_old)){
                $updateIncOldQuery = "UPDATE incentive_detail SET status_draft_paid = 0 WHERE idx IN($all_inc_idx_old)";
            }
            if(!empty($all_dlk_idx_old)){
                $updateDlkOldQuery = "UPDATE dlk_detail SET status_draft_paid = 0 WHERE idx IN($all_dlk_idx_old)";
            }
            // var_dump('<pre>');var_dump($updateOtOldQuery);var_dump($updateSubOldQuery);var_dump($updateIncOldQuery);var_dump($updateDlkOldQuery);die;
            $insertAllHalfDetail = "INSERT INTO allowance_half_detail (allowh_id, employee_id, employee_name, employee_code, allowh_date, meal_allowance, meal_cutting, rapel_sub, rapel_sub_date, overtime_allowance, rapel_ot, rapel_ot_date, transport_allowance, incentive_allowance, rapel_inc, rapel_inc_date, dlk_allowance, rapel_dlk, rapel_dlk_date, allowance_value, `description`, company_idx, status_absen, `status`, created_by, created_on) VALUES $allHalfDetail";

            $cek = $this->mUmt->updateUmt($all_idx, $update_umt_header, $updateAttendanceQuery, $insertAllHalfDetail, $updateOtQuery, $updateDlkQuery, $updateSubQuery, $updateIncQuery, $updateOtOldQuery, $updateDlkOldQuery, $updateSubOldQuery, $updateIncOldQuery);
            if($cek == 1){
                $msg['status']  = true;
                $msg['msg']     = 'Update UMT Berhasil .';
                $msg['link']    = $this->link.'/selectUmt/'.$all_id;
                echo json_encode($msg);die;
            }else{
                $msg['status']  = false;
                $msg['msg']     = "Error connection,<br>Please try again later.";
                echo json_encode($msg);die;
            }
        }else{
            $update_umt_header = [
                'description' 			=> $desc,
                'modified_on' 			=> date("Y-m-d H:i:s"),
                'modified_by' 			=> $this->idx
            ];
            $cek = $this->mUmt->updateUmt($all_idx, $update_umt_header);
            if($cek == 1){
                $msg['status']  = true;
                $msg['msg']     = 'Update UMT Berhasil .';
                $msg['link']    = $this->link.'/selectUmt/'.$all_id;
                echo json_encode($msg);die;
            }else{
                $msg['status']  = false;
                $msg['msg']     = "Error connection,<br>Please try again later.";
                echo json_encode($msg);die;
            }
        }
    }

    public function printUmt($id)
	{
		ob_start();
        ini_set('memory_limit', '2048M');
        ini_set('max_execution_time', 36000);
		$idx = $this->secure->dec($id);
        $dataHeader         = $this->mUmt->printUmtHeader($idx);
        $all_id             = (int)$dataHeader['allowh_id'];
        $data['company']  	= $this->mUmt->company();
		$data['dataHeader'] = $dataHeader;
		$data['dataDetail'] = $this->mUmt->printUmtDetail($all_id);
		$html=$this->load->view('payroll/v_umt_print',$data, true);
		$profile = $data['address'];
		$this->load->library('Tpdf');
		// create new PDF document
		$pdf = new TCPDF('P', PDF_UNIT, 'A4', true, 'UTF-8', false);
		// set document information

		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor($dataHeader['allowance_code']);
		$pdf->SetTitle($dataHeader['allowance_code']);
		$pdf->SetSubject('TCPDF Tutorial');
		$pdf->SetKeywords('TCPDF, PDF, example, test, guide');
		// set default header data
		//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 'PT QOURIER', $profile_jte, array(0,0,0), array(255,255,255));
		$pdf->SetHeaderData('access.png', 15,  'PT AJL', $profile, array(0,0,0), array(255,255,255));
		// set header and footer fonts
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		// set margins
		$pdf->SetMargins(5, 5, 5);
		// $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, 5);
		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);
		// Add Header
		$pdf->AddPage();
		// output the HTML content
		$pdf->writeHTML($html, true, false, true, false, '');
		ob_clean();
		$pdf->Output($dataHeader['allowance_code'] . '.pdf', 'I');
    }

    public function printUmtDetail($all_idx, $from, $to)
	{
		ob_start();
        ini_set('memory_limit', '2048M');
        ini_set('max_execution_time', 36000);
		$all_id = $this->secure->dec($all_idx);
        $dataHeader         = $this->mUmt->printUmtHeaderById($all_id);
        $data['company']  	= $this->mUmt->company();
        $startDate          = new DateTime($from);
        $endDate            = new DateTime($to);
        $endDatex           = new DateTime($to);
        $endDatex2          = new DateTime($to);
        $interval           = new DateInterval('P1D'); // 1-day interval
        $dateRange          = new DatePeriod($startDate, $interval, $endDate);
        // Menghitung selisih hari
        $interval           = $startDate->diff($endDate);
        $days               = $interval->days;
        $adjustableDate     = 15;
        if($days>$adjustableDate){
            $pass = 1;
            $selisihHari = $days-$adjustableDate;
            $selisihHari2 = ($days-$adjustableDate)-1;
            $endDatex->modify("-$selisihHari days");
            $endDatex2->modify("-$selisihHari2 days");
            var_dump('<pre>');var_dump([$all_id, $from, $endDatex->format('Y-m-d')]);die;
            $data['dataDetail'] = $this->mUmt->get_detail($all_id, $from, $endDatex->format('Y-m-d'));
            $data['dataDetail2'] = $this->mUmt->get_detail($all_id, $endDatex2->format('Y-m-d'), $to);
            $data['from']       = $from;
            $data['to']         = $endDatex->format('Y-m-d');
            $data['from2']      = $endDatex2->format('Y-m-d');
            $data['to2']        = $to;
        }else{
            $pass = 0;
            $data['dataDetail'] = $this->mUmt->get_detail($all_id, $from, $to);
            $data['dataDetail2'] = array();
            $data['from']       = $from;
            $data['to']         = $to;
            $data['from2']      = '';
            $data['to2']        = '';
        }
		$data['dataHeader'] = $dataHeader;
		$profile = $data['company']['address'];
		$this->load->library('Tpdf');
		// create new PDF document
		$pdf = new TCPDF('L', PDF_UNIT, 'LEGAL', true, 'UTF-8', false);
		// set document information

		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor($dataHeader['allowance_code']);
		$pdf->SetTitle($dataHeader['allowance_code']);
		$pdf->SetSubject('TCPDF Tutorial');
		$pdf->SetKeywords('TCPDF, PDF, example, test, guide');
		// set default header data
		//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 'PT QOURIER', $profile_jte, array(0,0,0), array(255,255,255));
		$pdf->SetHeaderData('access.png', 15,  'PT E TITIK TIGA KOMANDO', $profile, array(0,0,0), array(255,255,255));
		// set header and footer fonts
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		// set margins
		$pdf->SetMargins(3, 5, 5);
		// $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, 10);
		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);
		// Add Header
		$pdf->AddPage();
		// output the HTML content
        $html=$this->load->view('payroll/v_umt_print_detail',$data, true);
		$pdf->writeHTML($html, true, false, true, false, '');

        if($pass==1){
            $pdf->AddPage();
            // output the HTML content
            $html2=$this->load->view('payroll/v_umt_print_detail2',$data, true);
            $pdf->writeHTML($html2, true, false, true, false, '');
        }
		ob_clean();
		$pdf->Output($dataHeader['allowance_code'] . '.pdf', 'I');
    }

    public function printUmtDetail2($all_idx, $from, $to)
	{
		ob_start();
        ini_set('memory_limit', '2048M');
        ini_set('max_execution_time', 36000);
		$all_id = $this->secure->dec($all_idx);
        $dataHeader         = $this->mUmt->printUmtHeaderById($all_id);
        $data['company']  	= $this->mUmt->company();
        $startDate          = new DateTime($from);
        $endDate            = new DateTime($to);
        $endDate            = $endDate->modify('+1 day'); // Modify end date to include the last day
        $endDatex           = new DateTime($to);
        $endDatex2          = new DateTime($to);
        $interval           = new DateInterval('P1D'); // 1-day interval
        $dateRange          = new DatePeriod($startDate, $interval, $endDate);
        // Menghitung selisih hari
        $interval           = $startDate->diff($endDate);
        $days               = $interval->days;
        $adjustableDate     = 16;
        // var_dump('<pre>');var_dump($days);var_dump($adjustableDate);var_dump($days>$adjustableDate);die;
        if($days>$adjustableDate){
            $pass = 1;
            $selisihHari = $days-$adjustableDate;
            $selisihHari2 = ($days-$adjustableDate)-1;
            $endDatex->modify("-$selisihHari days");
            $endDatex2->modify("-$selisihHari2 days");
            $data['dataDetail'] = $this->mUmt->get_detail_only2($all_id, $from, $endDatex->format('Y-m-d'));
            $data['dataDetail2'] = $this->mUmt->get_detail_only2($all_id, $endDatex2->format('Y-m-d'), $to);
            $data['from']       = $from;
            $data['to']         = $endDatex->format('Y-m-d');
            $data['from2']      = $endDatex2->format('Y-m-d');
            $data['to2']        = $to;
        }else{
            $pass = 0;
            $data['dataDetail'] = $this->mUmt->get_detail_only($all_id, $from, $to);
            $data['dataDetail2'] = array();
            $data['from']       = $from;
            $data['to']         = $to;
            $data['from2']      = '';
            $data['to2']        = '';
        }
		$data['dataHeader'] = $dataHeader;
		$profile = $data['company']['address'];
		$this->load->library('Tpdf');
		// create new PDF document
		$pdf = new TCPDF('L', PDF_UNIT, 'LEGAL', true, 'UTF-8', false);
		// set document information

		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor($dataHeader['allowance_code']);
		$pdf->SetTitle($dataHeader['allowance_code']);
		$pdf->SetSubject('TCPDF Tutorial');
		$pdf->SetKeywords('TCPDF, PDF, example, test, guide');
		// set default header data
		//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 'PT QOURIER', $profile_jte, array(0,0,0), array(255,255,255));
		$pdf->SetHeaderData('access.png', 15,  'PT E TITIK TIGA KOMANDO', $profile, array(0,0,0), array(255,255,255));
		// set header and footer fonts
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		// set margins
		$pdf->SetMargins(3, 5, 5);
		// $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, 10);
		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);
		// Add Header
		$pdf->AddPage();
		// output the HTML content
        $html=$this->load->view('payroll/v_umt_print_detail',$data, true);
		$pdf->writeHTML($html, true, false, true, false, '');

        if($pass==1){
            $pdf->AddPage();
            // output the HTML content
            $html2=$this->load->view('payroll/v_umt_print_detail2',$data, true);
            $pdf->writeHTML($html2, true, false, true, false, '');
        }
		ob_clean();
		$pdf->Output($dataHeader['allowance_code'] . '.pdf', 'I');
    }

    public function publishUmt()
    {
        $idx    = $this->secure->dec(filter_var(trim($this->input->get('id')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH));
        $all_id = $this->secure->dec(filter_var(trim($this->input->get('all_id')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH));
        if(empty($idx)){
            $msg['status']  = false;
            $msg['text']    = 'index is required.';
            echo json_encode($msg);die;
        }
        if(empty($all_id)){
            $msg['status']  = false;
            $msg['text']    = 'UMT id is required.';
            echo json_encode($msg);die;
        }

        $getHeader = $this->mUmt->getDataHeader($all_id);
        if(empty($getHeader)){
            $msg['status']  = false;
            $msg['text']    = 'Data tidak ditemukan.';
            echo json_encode($msg);die;
        }

        $all_att_idx = $getHeader['all_att_idx'];
        // var_dump('<pre>');var_dump($getHeader['all_att_idx']);die;

        $queryUpdate = "UPDATE attendance_employee SET status_post_half = 1 WHERE idx IN($all_att_idx)";

        $updateUmt = [
            'status' => 2,
            'modified_by' => $this->idx,
            'modified_on' => date('Y-m-d H:i:s')
        ];

        $cek = $this->mUmt->publishUmt($idx, $updateUmt, $queryUpdate);
        // var_dump('<pre>');var_dump($data);die;
        if($cek == 1){
            $msg['status']  = true;
            $msg['text']    = "UMT ".$getHeader['allowance_code']." Berhasil di publish.";
            echo json_encode($msg);die;
        }else{
            $msg['status']  = false;
            $msg['text']    = 'Koneksi Error,<br>Silahkan coba lagi.';
            echo json_encode($msg);die;
        }
    }
}
/* End of file Office.php */
/* Location: ./application/controllers/Office.php */
