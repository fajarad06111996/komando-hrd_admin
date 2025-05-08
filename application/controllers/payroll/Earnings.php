<?php
defined('BASEPATH') or exit('No direct script access allowed');
require "vendor/autoload.php";
use Nullix\CryptoJsAes\CryptoJsAes;

class Earnings extends AUTH_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('payroll/M_earnings', 'mEar');
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
        $this->template->views('payroll/v_ear', $data);
    }
    // url for ajax datatable serverside
    function get_ajax() {
        $csrfToken  = validate_csrf_token();
        if($csrfToken == false){
            $list = null;
        }else{
            $list = $this->mEar->get_datatables();
        }
        // $list = $this->mEar->get_datatables();
        $data = array();
        $no = @$_POST['start'];
        foreach ($list as $item) {
            $idx = $this->secure->enc($item->idx);
            $all_id = $this->secure->enc($item->allowf_id);
            if($item->total_item>0){
                $print = "<h5><a href='".$this->link."/printEar/".$idx."' target='_BLANK' class='bPrint text-center badge badge-danger' data-popup='tooltip' title='Print Gaji' data-placement='right'><i class='icon-printer2'></i></a></h5>";
            }else{
                $print  = "";
            }
            if(!empty($this->security_function->permissions($this->filename . "-c"))){
                if($item->status == 1){
                    $change = "<h5><a href='javascript:void(0);' id='$all_id' class='bEdit text-center badge badge-info' data-popup='tooltip' title='Edit Gaji Detail' data-placement='right'><i class='icon-pencil7'></i></a></h5>";
                    $statusU = "<h5><span class='text-center badge badge-danger'>Draft</span></h5>";
                    $publish = "<h5><a href='javascript:void(0);' id='$idx' data='$item->allowance_code' allid='$all_id' class='bPublish text-center badge badge-warning' data-popup='tooltip' title='Publish Gaji' data-placement='right'><i class='icon-cloud-upload2'></i></a></h5>";
                }elseif($item->status == 2){
                    $change = "";
                    $statusU = "<h5><span class='text-center badge badge-success'>Publish</span></h5>";
                    $publish = "<h5><span class='badge badge-success bPublished'><i class='icon-checkmark' data-popup='tooltip' title='Gaji Terpublish' data-placement='right'></i></span></h5>";
                }
            }else{
                if($item->status == 1){
                    $change = '<h5><span class="badge badge-warning"><i class="icon-lock" data-popup="tooltip" title="Locked Edit Office" data-placement="right"></i></span></h5>';
                    $statusU = "<h5><span class='text-center badge badge-danger'>Draft</span></h5>";
                    $publish = "<h5><span class='badge badge-warning'><i class='icon-lock' data-popup='tooltip' title='Locked Publish Gaji' data-placement='right'></i></span></h5>";
                }elseif($item->status == 2){
                    $change = '';
                    $statusU = "<h5><span class='text-center badge badge-success'>Publish</span></h5>";
                    $publish = "<h5><span class='badge badge-success bPublished'><i class='icon-checkmark' data-popup='tooltip' title='Gaji Terpublish' data-placement='right'></i></span></h5>";
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
            $row[] = '<b>'.bulan(date('M', strtotime($item->start))).'/'.date('Y', strtotime($item->start)).'</b>';
            $row[] = $item->total_item;
            $row[] = 'Rp. '.number_format($item->grandtotal);
            $row[] = $item->description;
            // add html for action
            $data[] = $row;
        }
        $output = array(
            "draw"            => @$_POST['draw'],
            "recordsTotal"    => $this->mEar->count_all(),
            "recordsFiltered" => $this->mEar->count_filtered(),
            "data"            => $data,
        );
        // output to json format
        echo json_encode($output);
    }

    // url for ajax datatable serverside
    function get_ajax2() {
        $all_id     = $this->input->post('all_id');
        $all_idx    = $this->secure->dec($all_id);
        $csrfToken  = validate_csrf_token();
        if($csrfToken == false){
            $list = null;
        }else{
            $list = $this->mEar->get_datatables2($all_idx);
        }
        // $list = $this->mEar->get_datatables();
        $data = array();
        $no = @$_POST['start'];
        foreach ($list as $item) {
            $idx = $this->secure->enc($item->idx);
            $all_id = $this->secure->enc($item->allowf_id);
            $info = "<h5><a href='javascript:void(0);' id='$all_id' data='$item->employee_code' code='1' class='bInfo text-center badge badge-primary' data-popup='tooltip' title='Gaji' data-placement='right'><i class='icon-price-tag2'></i></a></h5>";
            $print = "<h5><a href='".$this->link."/printSalary/".$idx."' target='_BLANK' class='bPrint text-center badge badge-danger' data-popup='tooltip' title='Print Slip Gaji' data-placement='right'><i class='icon-printer2'></i></a></h5>";
            if(!empty($this->security_function->permissions($this->filename . "-c"))){
                $change = "<h5><a href='javascript:void(0);' id='$all_id' class='bEdit text-center badge badge-info' data-popup='tooltip' title='Edit Gaji Detail' data-placement='right'><i class='icon-pencil7'></i></a></h5>";
                if($item->status == 1)
                {
                    $statusU = "<h5><a href='javascript:void(0);' id='$all_id' data='$item->employee_code' code='1' class='bStatus text-center badge badge-success' data-popup='tooltip' title='Change Status' data-placement='left'>Aktif</a></h5>";
                // $statusU = '<span class="badge badge-success">Aktif</span>';
                } else {
                    $statusU = "<h5><a href='javascript:void(0);' id='$all_id' data='$item->employee_code' code='0' class='bStatus text-center badge badge-danger' data-popup='tooltip' title='Change Status' data-placement='left'>Non Aktif</a></h5>";
                // $statusU = '<span class="badge badge-danger">Non Aktif</span>';
                }
            }else{
                $change = '<h5><span class="badge badge-warning"><i class="icon-lock" data-popup="tooltip" title="Locked Edit Office" data-placement="right"></i></span></h5>';
                if($item->status == 1)
                {
                    $statusU = "<span class='text-center badge badge-success'>Aktif</span>";
                }else{
                    $statusU = "<span class='text-center badge badge-danger'>Non Aktif</span>";
                }
            }
            if(!empty($this->security_function->permissions($this->filename . "-x"))){
                $execute  = "<h5><a href='javascript:void(0);' id='$idx' data='$item->employee_code' class='bDelete text-center badge badge-danger' data-popup='tooltip' title='Delete Office' data-placement='right'><i class='icon-bin'></i></a></h5>";
            }else{
                $execute  = '<h5><span class="badge badge-warning"><i class="mi-lock font-weight-black" data-popup="tooltip" title="Locked Delete Office" data-placement="right"></i></span></h5>';
            }
            $no++;
            $row = array();
            // $row[] = "<div class='btn-group'>$change$execute$info</div>";
            $row[] = $no.".";
            // $row[] = $statusU;
            $row[] = $item->employee_name;
            $row[] = $item->employee_code;
            $row[] = 'Rp. '.number_format($item->basic_salary);
            $row[] = 'Rp. '.number_format($item->position_allowance);
            $row[] = 'Rp. '.number_format($item->bpjs);
            $row[] = 'Rp. '.number_format($item->allowance_value);
            $row[] = $item->description;
            $row[] = "<div class='btn-group'>$print</div>";
            // add html for action
            $data[] = $row;
        }
        $output = array(
            "draw"            => @$_POST['draw'],
            "recordsTotal"    => $this->mEar->count_all2($all_idx),
            "recordsFiltered" => $this->mEar->count_filtered2($all_idx),
            "data"            => $data,
        );
        // output to json format
        echo json_encode($output);
    }

    // Proccess adding new Data
    public function selectEarnings($all_id="")
    {
		$access = $this->security_function->permissions($this->filename . "-r");
		if (empty($access)) die("<center><h1>You can not get the right to access this module</h1></center>");
		if(empty($all_id)){
    		// $all_idx 	= $this->secure->dec($all_id);
    		$from_date 	= filter_var(trim($this->input->post('from',TRUE)), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
    		$desc 	    = filter_var(trim($this->input->post('description',TRUE)), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$all_id_new = $this->ModelGenId->genIdUnlimited('ALLWFID', $this->idx);
			$idx        = $this->ModelGenId->genIdYear('ALLWF', $this->idx);
			$all_no     = $idx.'/EAR/'.$this->officeCode.'/'.numberToRoman(date('m')).'/'.date('Y');
            $login      = $this->idx;
            $company    = $this->office;
            $now        = date('Y-m-d H:i:s');

            $getEarn    = $this->mEar->get_employee_all();
            if(empty($getEarn)){
                $this->session->set_flashdata('message', "notiferror_a('Error connection,<br>Please try again later.')");
			    redirect($this->link);die;
            }

            $valEarnings    = array();
            $valPosition    = array();
            $tangal         = array();
            $updateAttendance ='';
            $attIdx = '';
            $allFullDetail ='';
            $grand_total = 0;
            $i = 0;
            foreach ($getEarn as $g) {
                $gaji = (float)$g->basic_salary;
                $tunjangan = (float)$g->position_allowance;
                $valEarnings[$g->employee_id] = $gaji;
                $valPosition[$g->employee_id] = $tunjangan;
                $total = $gaji + $tunjangan;
                $allFullDetail .= "(".trim($all_id_new).", ".trim($g->employee_id).", '$g->employee_name','$g->employee_code', $gaji, $tunjangan, 0, $total, '$desc',$company, 1, $login, '$now'),";
                $grand_total += $total;
                $i++;
            }
            // var_dump('<pre>');var_dump($allFullDetail);die;

            $insert_earn_header = [
                'allowf_id' => $all_id_new,
                'allowance_code' => $all_no,
                'allowance_date' => $from_date,
                'grandtotal' => $grand_total,
                'start' => $from_date,
                'end' => $from_date,
                'total_item' => $i,
                'description' => $desc,
                'company_idx' => $company,
                'status' => 1,
                'created_on' => $now,
                'created_by' => $login
            ];

            // $updateAttendance = substr($updateAttendance,0,strlen($updateAttendance)-1);
            $allFullDetail = substr($allFullDetail,0,strlen($allFullDetail)-1);
            // $updateAttendanceQuery = "INSERT INTO attendance_employee (idx, status_draft_full, modified_by, modified_on) VALUES $updateAttendance ON DUPLICATE KEY UPDATE status_draft_full=VALUES(status_draft_full), modified_by=VALUES(modified_by), modified_on=VALUES(modified_on)";
            $insertAllFullDetail = "INSERT INTO allowance_full_detail (allowf_id, employee_id, employee_name, employee_code, basic_salary, position_allowance, bpjs, allowance_value, `description`, company_idx, `status`, created_by, created_on) VALUES $allFullDetail";
            // var_dump('<pre>');var_dump($insert_earn_header);die;

			$cek = $this->mEar->insertEarnings($insert_earn_header, $insertAllFullDetail);
			if($cek == 1){
			    $encrypt_all_id = $this->secure->enc($all_id_new);
			    redirect($this->link.'/selectEarnings/'.$encrypt_all_id);
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
			$data['header'] 	    = $this->mEar->getAllowHeader($all_idx);
			// var_dump('<pre>');var_dump($all_idx);var_dump($data['header']);die;
			$data['allowh_id'] 		= $all_id;
			$data['from_date'] 		= $data['header']['start'];
			$data['to_date'] 		= $data['header']['end'];
			$data['formact'] 		= $this->link.'/updateDataEarnings/'.$all_id;
			$data['title'] 			= "Job Bebas";
			$data['link'] 			= $this->link;
			$data['link2'] 			= $this->link.'/unSelectClient';
            $params                 = '{"link": "'.$this->link.'", "csrf": "'.$this->session->csrf_token.'", "permW": "'.$this->security_function->permissions($this->filename . "-w").'", "permC": "'.$this->security_function->permissions($this->filename . "-c").'", "permX": "'.$this->security_function->permissions($this->filename . "-x").'", "lock": "'.$data['lock'].'", "base_url": "'.base_url().'", "allowh_id": "'.$all_id.'", "from": "'.$data['header']['start'].'", "to": "'.$data['header']['end'].'"}';
            // encrypt
            $encrypted          = CryptoJsAes::encrypt($params, $this->enkey);
            $data['params']     = $encrypted;
			$this->template->views('payroll/v_ear_detail', $data);
		}
    }

    public function getDataHeader($all_id)
    {
        $all_idx    = $this->secure->dec($all_id);
        $result     = $this->mEar->getDataHeader($all_idx);
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

    public function updateDataEarnings($all_id)
    {
        $all_idx    = $this->secure->dec($all_id);
        $from_date 	= filter_var(trim($this->input->post('from',TRUE)), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $to_date 	= filter_var(trim($this->input->post('to',TRUE)), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $desc 	    = filter_var(trim($this->input->post('description',TRUE)), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $login      = $this->idx;
        $company    = $this->office;
        $now        = date('Y-m-d H:i:s');

        $getEarnHeader = $this->mEar->getDataHeader($all_idx);
        if(empty($getEarnHeader)){
            $msg['status']  = false;
            $msg['msg']     = 'Data absen tidak ditemukan.';
            echo json_encode($msg);die;
        }

        if($getEarnHeader['start'] != $from_date || $getEarnHeader['end'] != $to_date){
            $getUmt = $this->mEar->getSalaryDataEscDrafted($from_date, $to_date, $this->office);
            if(empty($getEarn)){
                $msg['status']  = false;
                $msg['msg']     = 'Data absen tidak ditemukan.';
                echo json_encode($msg);die;
            }
    
            $valMeal    = array();
            $valTransp  = array();
            $oTime      = array();
            $tangal     = array();
            $attIdx     = '';
            $allFullDetail ='';
            foreach ($getEarn as $g) {
                $cekInDay           = strtolower(date('l', strtotime($g->check_in)));
                $cekInDate          = strtolower(date('Y-m-d', strtotime($g->check_in)));
                $empData            = $this->mEar->get_employee($g->employee_id);
                $holiday            = $this->mEar->check_holiday($cekInDate);
                $checkIn            = date('H:i:s', strtotime($g->check_in));
                $diffCheckIn        = strtotime($checkIn) - strtotime($empData[$cekInDay]);
                $diffInMinutes      = floor($diffCheckIn / 60);
                $status_overtime    = (int)$g->status_overtime;
                $tolerance          = $empData['data_tolerance'];
                $mealAl             = 0;
                foreach($tolerance as $tl){
                    $pattern = '/\[([^\]]+)\]/';
                    preg_match_all($pattern, $tl['rumus'], $matches);
                    $umtFix = null;
                    if(count($matches[1])>1){
                        $msg['status']  = false;
                        $msg['msg']     = 'Rumus tidak sesuai di menu <b>Master->Jadwal Kerja</b> .';
                        echo json_encode($msg);die;
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
                                $msg['status']  = false;
                                $msg['msg']     = 'Jenis potongan tidak ditemukan .';
                                echo json_encode($msg);die;
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
                                $msg['status']  = false;
                                $msg['msg']     = 'Jenis potongan tidak ditemukan .';
                                echo json_encode($msg);die;
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
                                $msg['status']  = false;
                                $msg['msg']     = 'Jenis potongan tidak ditemukan .';
                                echo json_encode($msg);die;
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
                $attIdx .= $g->idx.',';
            }
            $grand_total = 0;
            $i = 0;
            foreach($valMeal as $k => $v){
                $lembur = $oTime[$k];
                $transport = $valTransp[$k];
                $total = $lembur + $transport + $v;
                $empData2 = $this->mEar->get_employee_on_full($k, $all_idx);
                $fullDidx = $empData2['idx'];
                $employee_name = $empData2['employee_name'];
                $employee_code = $empData2['employee_code'];
                $allFullDetail .= "(".trim($fullDidx).", $v, $lembur, $transport, $total, '$desc', $login, '$now'),";
                $grand_total += $total;
                $i++;
            }

            $attIdx = substr($attIdx,0,strlen($attIdx)-1);
            $update_earn_header = [
                'start' 	    => $from_date,
                'end' 		    => $to_date,
                'all_att_idx'   => $attIdx,
                'description'   => $desc,
                'modified_on'   => date("Y-m-d H:i:s"),
                'modified_by'   => $this->idx
            ];
    
            $allFullDetail = substr($allFullDetail,0,strlen($allFullDetail)-1);
            $updateAllFullDetail = "INSERT INTO allowance_full_detail (idx, meal_allowance, overtime_allowance, transport_allowance, allowance_value, `description`, modified_by, modified_on) VALUES $allFullDetail ON DUPLICATE KEY UPDATE meal_allowance=VALUES(meal_allowance), overtime_allowance=VALUES(overtime_allowance), transport_allowance=VALUES(transport_allowance), allowance_value=VALUES(allowance_value), `description`=VALUES(`description`), modified_by=VALUES(modified_by), modified_on=VALUES(modified_on)";
            // var_dump('<pre>');var_dump($insert_umt_header);die;
            $cek = $this->mEar->updateEarnings($all_idx, $update_earn_header, $updateAllFullDetail);
            if($cek == 1){
                $msg['status']  = true;
                $msg['msg']     = 'Update Gaji Berhasil .';
                $msg['link']    = $this->link.'/selectEarnings/'.$all_id;
                echo json_encode($msg);die;
            }else{
                $msg['status']  = false;
                $msg['msg']     = "notiferror_a('Error connection,<br>Please try again later.";
                echo json_encode($msg);die;
            }
        }else{
            $update_umt_header = [
                'description' 			=> $desc,
                'modified_on' 			=> date("Y-m-d H:i:s"),
                'modified_by' 			=> $this->idx
            ];
            $cek = $this->mEar->updateGaji($all_idx, $update_umt_header);
            if($cek == 1){
                $msg['status']  = true;
                $msg['msg']     = 'Update Gaji Berhasil .';
                $msg['link']    = $this->link.'/selectSalary/'.$all_id;
                echo json_encode($msg);die;
            }else{
                $msg['status']  = false;
                $msg['msg']     = "notiferror_a('Error connection,<br>Please try again later.";
                echo json_encode($msg);die;
            }
        }
    }

    public function printSalary($id)
	{
		ob_start();
        ini_set('memory_limit', '2048M');
        ini_set('max_execution_time', 36000);
		$idx = $this->secure->dec($id);
        // $dataHeader         = $this->mEar->printUmtHeader($idx);
        // $all_id             = (int)$dataHeader['allowf_id'];
        $data['company']  	= $this->mEar->company();
		// $data['dataHeader'] = $dataHeader;
		$data['dataDetail'] = $this->mEar->printSalaryDetail($idx);
        // var_dump('<pre>');var_dump($data['dataDetail']);die;
		$html=$this->load->view('payroll/v_salary_print',$data, true);
		$this->load->library('Tpdf');
		// create new PDF document
		$pdf = new TCPDF('P', PDF_UNIT, 'A4', true, 'UTF-8', false);
		// set document information

		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('');
		$pdf->SetTitle('');
		$pdf->SetSubject('TCPDF Tutorial');
		$pdf->SetKeywords('TCPDF, PDF, example, test, guide');
		// set default header data
		//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 'PT QOURIER', $profile_jte, array(0,0,0), array(255,255,255));
		$pdf->SetHeaderData('access.png', 15,  'PT E TITIK TIGA KOMANDO', '', array(0,0,0), array(255,255,255));
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
		$pdf->Output('gaji.pdf', 'I');
    }

    public function publishSalary()
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
            $msg['text']    = 'SALARY id is required.';
            echo json_encode($msg);die;
        }

        $getHeader = $this->mEar->getDataHeader($all_id);
        if(empty($getHeader)){
            $msg['status']  = false;
            $msg['text']    = 'Data tidak ditemukan.';
            echo json_encode($msg);die;
        }

        $all_att_idx = $getHeader['all_att_idx'];
        // var_dump('<pre>');var_dump($getHeader['all_att_idx']);die;

        $queryUpdate = "UPDATE attendance_employee SET status_post_full = 1 WHERE idx IN($all_att_idx)";

        $updateSalary = [
            'status' => 2,
            'modified_by' => $this->idx,
            'modified_on' => date('Y-m-d H:i:s')
        ];

        $cek = $this->mEar->publishSalary($idx, $updateSalary, $queryUpdate);
        // var_dump('<pre>');var_dump($data);die;
        if($cek == 1){
            $msg['status']  = true;
            $msg['text']    = "SALARY ".$getHeader['allowance_code']." Berhasil di publish.";
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
