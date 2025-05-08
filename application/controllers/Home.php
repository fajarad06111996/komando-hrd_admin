<?php
defined('BASEPATH') or exit('No direct script access allowed');
require "vendor/autoload.php";
use Nullix\CryptoJsAes\CryptoJsAes;

class Home extends AUTH_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_home');
		// $this->load->library('Secure');
		$this->load->library('security_function');
        $this->link		= site_url().strtolower(get_class($this));
        $this->filename	= strtolower(get_class($this));
        $this->office 	= $this->secure->dec($this->session->userdata('JToffice_id'));
        $this->hub    	= $this->secure->dec($this->session->userdata('JThub_id'));
        $this->idx    	= $this->secure->dec($this->session->userdata('JTidx'));
		$this->level  	= $this->secure->dec($this->session->userdata('JTlevel'));
		$this->userdata = $this->session->userdata('JTuserdata');
		$this->enkey   	= $this->config->item('encryption_key');
	}

	public function index()
	{
	    if(strtotime(date('H:i')) >= strtotime("00:00") && strtotime(date('H:i')) < strtotime("11:59")){
	        $data['nowIs']           = "Good Morning"; 
	    }elseif(strtotime(date('H:i')) > strtotime("12:00") && strtotime(date('H:i')) < strtotime("16:59")){
	        $data['nowIs']           = "Good Afternoon"; 
	    }elseif(strtotime(date('H:i')) > strtotime("17:00") && strtotime(date('H:i')) < strtotime("19:59")){
	        $data['nowIs']           = "Good Evening";
	    }else{
	        $data['nowIs']           = "Good Night";
	    }
		$data['userdata'] 		= $this->userdata;
		$data['companyData']	= $this->mAccess->getCompany();
		$data['page'] 			= "DASHBOARD";
		$data['judul'] 			= "DASHBOARD";
		$data['breadcrumb']		= false;
		$data['link']			= $this->link;
		$data['filename'] 		= $this->filename;
		$params             	= '{"link": "'.$this->link.'", "csrf": "'.$this->session->csrf_token.'", "permW": "'.$this->security_function->permissions($this->filename . "-w").'", "permC": "'.$this->security_function->permissions($this->filename . "-c").'", "permX": "'.$this->security_function->permissions($this->filename . "-x").'", "base_url": "'.base_url().'"}';
        // encrypt
        $encrypted          = CryptoJsAes::encrypt($params, $this->enkey);
        $data['params']     = $encrypted;
		if(!empty($this->security_function->permissions($this->filename . "-r"))){
			$this->template->views('pages/v_dashboard', $data);
		}else{
			$this->template->views('pages/v_home', $data);
		}
		// $this->template->views('pages/v_mainContent', $data);
	}
	public function getEmployeePerDept()
	{
		$result = $this->M_home->getEmployeePerDept($this->office);
		echo json_encode($result);
	}
	public function getEduAll()
	{
		$result = $this->M_home->getEduAll($this->office);
		echo json_encode($result);
	}
	public function getAtt()
	{
		// $now 			= '2024-12-27';
		$now 			= date('Y-m-d');
		$result   		= $this->M_home->getAttendanceEmployeeAll($now);
        $format_data 	= [];
		// echo json_encode($result);die;

        foreach($result as $i => $v){
            $att_idx    = (int)$v['att_idx'];
            $hari       = strtolower(date('l', strtotime($v['calendar_date'])));
            $tanggal    = $v['calendar_date'];
            $tanggalV   = date('d-F-Y', strtotime($v['calendar_date']));
            $checkIn    = date('H:i:s', strtotime($v['check_in']));
            $cekInDay   = strtolower(date('l', strtotime($v['check_in'])));
            $cycle      = $this->M_home->getCycle($v['employee_id'], $v['shift_idx'], $tanggal);
            // var_dump('<pre>');var_dump();die;
            if($v['shift_mode']==1){
                if(empty($cycle)){
					$msg['status'] 	= false;
					$msg['msg'] 	= "Jadwal Multi Shift Untuk Karyawan [<b>".$v['employee_name']."</b>] belum di seting.";
					echo json_encode($msg);die;
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
                $holiday = $this->M_home->check_holiday_cycle($tanggal, $v['employee_id'], $v['shift_idx']);
                $shift = $v['dynamic_check_in'];
            }else{
                $diffCheckIn        = strtotime($checkIn) - strtotime($v[$cekInDay]);
                $holiday = $this->M_home->check_holiday($tanggal);
                $shift = $v[$hari];
            }
            $tolerance = $this->M_home->getTolerance($v['shift_idx']);
            if(empty($tolerance)){
				$msg['status'] 	= false;
				$msg['msg'] 	= "Shift ".$v['employee_name'].",<br>Belum di set.";
				echo json_encode($msg);die;
            }
            $diffInMinutes  = floor($diffCheckIn / 60);
            $terlambat      = 0;
            $itol = 1;
            foreach($tolerance as $tl){
                $pattern = '/\[([^\]]+)\]/';
                preg_match_all($pattern, $tl['rumus'], $matches);
                if(count($matches[1])>1){
					$msg['status'] 	= false;
					$msg['msg'] 	= "Rumus tidak sesuai di menu <b>Master->Jadwal Kerja</b>.";
					echo json_encode($msg);die;
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
            $employee_name = $v['employee_name'];
            $masuk = $v['check_in'];
            $jamMasuk = date('H:i', strtotime($v['check_in']));
            $masukDate = date('Y-m-d', strtotime($v['check_in']));
            $keluar = $v['check_out'];
            $jamKeluar = date('H:i', strtotime($v['check_out']));
            $todayServer = date('Y-m-d');
            $point_checkin = $v['point_center_check_in'];
            $point_checkout = $v['point_center_check_out'];
            $sub_status = (int)$v['sub_type'];
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
            $format_data[$employee_name] = [$absenView, $sub_status, $sub_status_name, $status_absen, $terlambat, $att_idx, $employee_name, $tanggalV, $status_except];
        }
		$msg['status'] 	= true;
		$msg['msg'] 	= "Oke.";
		$msg['data'] 	= $format_data;
		echo json_encode($msg);die;
	}
	public function getCount()
	{
		$result = $this->M_home->getCount($this->office);
		echo json_encode($result);
	}
	public function getDrvPickupOn()
	{
		$result = $this->M_home->getKPIDriverPickupOntime($this->office);
		echo json_encode($result);
	}
	public function getDrvPickupLte()
	{
		$result = $this->M_home->getKPIDriverPickupLate($this->office);
		echo json_encode($result);
	}
	public function getDrvDelivOn()
	{
		$result = $this->M_home->getKPIDriverDeliveryOntime($this->office);
		echo json_encode($result);
	}
	public function getDrvDelivLte()
	{
		$result = $this->M_home->getKPIDriverDeliveryLite($this->office);
		echo json_encode($result);
	}
	public function autoRevProduct()
	{
		$result = $this->M_home->autoRevProduct($this->office);
		echo $result;
	}
	public function autoTotalProduct()
	{
		$result = $this->M_home->autoTotalProduct($this->office);
		echo $result;
	}
	public function autoTotalPayment()
	{
		$result = $this->M_home->autoTotalPayment($this->office);
		echo $result;
	}
	public function getRevValue()
	{
		$result = $this->M_home->getRevValue($this->office);
		echo $result;
	}
	public function getRevMonth()
	{
		$result = $this->M_home->getRevMonth($this->office);
		echo $result;
	}
	public function getRevOrder($status)
	{
		$result = $this->M_home->getRevOrder($this->office, $status);
		echo $result;
	}
	public function getRevProduct($product_idx)
	{
		$result = $this->M_home->getRevProduct($this->office,$product_idx);
		echo $result;
	}
	public function home404()
	{
		$data['userdata'] 	= $this->userdata;
		$data['page'] 		= "DASBOARD";
		$data['judul'] 		= "PROSES BISNIS";
		$this->template->views('pages/404', $data);
	}

	public function myprofile()
	{
		$ses = array(
			'csrf_token' => hash('sha1',time())
		);
		$this->session->set_userdata($ses);
		$data['userdata'] 	= $this->userdata;
		$data['page'] 		= "HOME";
		$data['judul'] 		= "MY PROFILE";
		$data['link'] 		= $this->link;
		$data['profile']	= $this->M_home->myprofile($this->idx);
		$this->template->views('pages/v_myprofile', $data);
	}

	public function cekRightbar($user)
	{
		$userx 	= $this->secure->dec($user);
		if($this->input->post('name')){
			$name = $this->input->post('name', true);
			$value	= $this->input->post('value', true);
			$data = array(
				$name => $value
			);
			$result = $this->M_home->updateRightbar($userx, $data);
		}else{
			$result = $this->M_home->cekRightbar($userx);
		}
		echo json_encode($result);
	}

	public function cekRightbarx($user)
	{
		$userx 	= $this->secure->dec($user);
		if($this->input->post('name')){
			$name = $this->input->post('name', true);
			$value	= $this->input->post('value', true);
			$data = array(
				$name => $value
			);
			$result = $this->M_home->updateRightbar($userx, $data);
		}else{
			$result = $this->M_home->cekRightbar($userx);
		}
		echo $result;
	}

	public function changePassword()
	{
		cek_csrf();
		$pass1 = $this->input->post('tPass1', TRUE);
		$pass2 = $this->input->post('tPass2', TRUE);
		if($pass1 != $pass2)
		{
			$msg['status'] 	= true;
			$msg['text'] 	= "password does'nt match.";
			echo json_encode($msg);
			die;
		}
		$data = [
			'password' 		=> password_hash($pass1, PASSWORD_DEFAULT),
			'modified_on' 	=> date('Y-m-d H:i:s'),
			'modified_by'	=> $this->idx
		];
		// var_dump('<pre>');
		// var_dump($data);
		// die();
		$cek = $this->M_home->changePassword($data, $this->idx);
		if($cek == 0)
		{
			$msg['status'] 	= true;
			$msg['text'] 	= "Network failure,<br> Please try again later.";
			echo json_encode($msg);
			die;
		}else{
			$msg['success'] = true;
			$msg['text'] 	= "Change password successfully.";
			echo json_encode($msg);
			die;
		}
	}

}

/* End of file Home.php */
/* Location: ./application/controllers/Home.php */
