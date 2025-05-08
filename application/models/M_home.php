<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_home extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('ModelGlobal');
        $this->bulan = array (1 => 'Januari',
			'Februari',
			'Maret',
			'April',
			'Mei',
			'Juni',
			'Juli',
			'Agustus',
			'September',
			'Oktober',
			'November',
			'Desember'
		);

    }
    public function cekRightbar($user)
    {
        $this->db->select('*')
        ->from('setting_rightbar')
        ->where('user_idx', $user);
        $result = $this->db->get();
        return $result->row_array();
    }
    public function updateRightbar($user, $data)
    {
        $this->db->trans_begin();
        $this->db->where('user_idx', $user);
        $this->db->update('setting_rightbar', $data);
        if($this->db->trans_status() === FALSE)
    	{
    		$this->db->trans_rollback();
    		$this->db->select('*')
            ->from('setting_rightbar')
            ->where('user_idx', $user);
            $result = $this->db->get();
            $updated = array(
                'updated' => false
            );
            $resultx = array_merge($updated,$result->row_array());
            return $resultx;
    	}
    	else
    	{
    		$this->db->trans_commit();
            $this->db->select('*')
            ->from('setting_rightbar')
            ->where('user_idx', $user);
            $result = $this->db->get();
            $updated = array(
                'updated' => true
            );
            $resultx = array_merge($updated,$result->row_array());
            return $resultx;
    	}
    }

    public function myprofile($idx){
        $this->db->select('
            a.*,
            (SELECT office_name FROM master_office where idx = a.office_id) as office_name,
            (SELECT hub_name FROM master_hub where idx = a.hub_id) as hub_name,
            (SELECT level_name FROM app_level_access where level_id = a.user_level_id) as level_name
        ');
        $this->db->from('user_account a');
        $this->db->where('idx',$idx);
        $result = $this->db->get();
        return $result->row_array();
    }

    public function changePassword($data, $idx)
    {
        $this->db->trans_begin();

        $this->db->where('idx', $idx);
        $this->db->update('user_account', $data);

        if ($this->db->trans_status() === FALSE)
        {
                $this->db->trans_rollback();
                return 0;
        }
        else
        {
                $this->db->trans_commit();
                return 1;
        }
    }
    public function getTolerance($shiftIdx)
    {
        $this->db->select('a.*')
        ->from('attendance_setup a')
        ->where(['a.status' => 1, 'a.shift_idx' => $shiftIdx]);
        $result = $this->db->get()->result_array();
        return $result;
    }
    public function getCycle($empid, $shiftIdx, $attDate)
    {
        $this->db->select('b.*')
        ->from('dynamic_shift b')
        ->where(['b.employee_id' => $empid, 'b.shift_idx' => $shiftIdx, 'b.company_idx' => $this->office, 'b.date' => $attDate]);
        $result = $this->db->get()->result_array();
        return $result;
    }
    public function check_holiday($cekin){
        $this->db->select('a.*')
        ->from('calendar_event_master a')
        ->where('a.event_date', $cekin);
        // $result = $this->db->get_compiled_select();
        $result = $this->db->get()->num_rows();
        return $result;
    }

    public function check_holiday_cycle($cekin, $emp_id, $shf_idx){
        $this->db->select('a.*')
        ->from('dynamic_shift a')
        ->where(['a.date'=> $cekin, 'a.employee_id' => $emp_id, 'shift_idx' => $shf_idx, 'work_day' => 0]);
        // $result = $this->db->get_compiled_select();
        $result = $this->db->get()->num_rows();
        return $result;
    }
    public function getEmployeePerDept($office)
    {
        $this->db->select('a.organization_number, a.organization_name, (SELECT COUNT(idx) FROM master_employee WHERE organization_idx = a.idx AND `status` = 1 AND company_idx = '.$office.') as total_karyawan')
        ->from('master_organization a')
        ->where('a.status', 1);
        return $this->db->get()->result();
    }
    public function getEduAll($office)
    {
        $this->db->select('a.status_name, (SELECT COUNT(idx) FROM master_employee WHERE education = a.status AND `status` = 1 AND company_idx = '.$office.') as total_edu')
        ->from('status_pendidikan a')
        ->where('IF(0=1,0,(SELECT COUNT(`idx`) FROM `master_employee` WHERE `education` = `a`.`status` AND `status` = 1)) <> 0', null);
        return $this->db->get()->result();
    }
    public function getAtt($office)
    {
        $now = date('Y-m-d');
        $query = "SELECT 'SAKIT' as category, COUNT(a.idx) as total FROM master_employee a CROSS JOIN calendar b LEFT JOIN attendance_employee c ON b.cal_date = c.attendance_date AND a.employee_id = c.employee_id WHERE b.cal_date = '$now' AND a.status_delete = 0 AND (a.company_idx = $office OR a.company_idx IS NULL) AND IF((SELECT sub_type FROM submission_detail WHERE sub_date = b.cal_date AND employee_id = a.employee_id AND status_confirm = 1) IS NULL, 0, (SELECT sub_type FROM submission_detail WHERE sub_date = b.cal_date AND employee_id = a.employee_id AND status_confirm = 1)) = 1";
    }
    public function getAttendanceEmployeeAll($now,$employee_id="")
    {
        if(empty($employee_id)){
            $where = "";
        }else{
            $where = "AND a.employee_id = $employee_id ";
        }
        $order = "ORDER BY a.employee_id, b.cal_date";
        $query = "SELECT 
                c.idx AS att_idx,
                b.cal_date as calendar_date,
                a.employee_id,
                a.employee_name,
                c.attendance_no,
                c.attendance_date,
                IF(c.shift_idx IS NULL, a.office_shift, c.shift_idx) as shift_idx,
                c.target_in,
                c.check_in,
                c.check_out,
                c.target_out,
                c.target_out,
                c.point_center_check_in,
                c.point_center_check_out,
                c.url_photo_check_in,
                c.url_photo_check_out,
                c.status_permission,
                c.status_overtime,
                c.reason_overtime,
                c.company_idx,
                c.`status`,
                c.status_except_um,
                (SELECT check_in FROM dynamic_shift WHERE shift_idx = a.office_shift AND `date` = b.cal_date AND employee_id = a.employee_id) as dynamic_check_in,
                (SELECT check_out FROM dynamic_shift WHERE shift_idx = a.office_shift AND `date` = b.cal_date AND employee_id = a.employee_id) as dynamic_check_out,
                d.shift_mode,
                d.monday_in as monday,
                d.tuesday_in as tuesday,
                d.wednesday_in as wednesday,
                d.thursday_in as thursday,
                d.friday_in as friday,
                d.saturday_in as saturday,
                d.sunday_in as sunday,
                IF((SELECT sub_type FROM submission_detail WHERE sub_date = b.cal_date AND employee_id = a.employee_id AND status_confirm = 1) IS NULL, 0, (SELECT sub_type FROM submission_detail WHERE sub_date = b.cal_date AND employee_id = a.employee_id AND status_confirm = 1)) as sub_type,
                IF((SELECT status_name FROM status_submission_type WHERE status = (SELECT sub_type FROM submission_detail WHERE sub_date = b.cal_date AND employee_id = a.employee_id AND status_confirm = 1)) IS NULL, 'MASUK', (SELECT status_name FROM status_submission_type WHERE status = (SELECT sub_type FROM submission_detail WHERE sub_date = b.cal_date AND employee_id = a.employee_id AND status_confirm = 1))) as sub_type_name
            FROM master_employee a
            CROSS JOIN calendar b
            LEFT JOIN attendance_employee c ON b.cal_date = c.attendance_date AND a.employee_id = c.employee_id
            LEFT JOIN master_shift d ON d.idx = a.office_shift
            WHERE b.cal_date = '$now'
            AND a.status_delete = 0
            AND (a.company_idx = $this->office OR a.company_idx IS NULL) ".$where.$order;
        $hasilnya = $this->db->query($query)->result_array();
        return $hasilnya;
    }
    public function getCount($office)
    {
        $startDate  = date('Y-m-d',strtotime("-2 month"));
        $endDate    = date('Y-m-d',strtotime("now +1 days"));
        $now        = date('Y-m-d');
        $timeNow    = date('H:i:s');
        $today      = strtolower(date('l'));
        $queryUnion = "SELECT 'EMPLOYEE' as category, COUNT(idx) as total FROM master_employee WHERE company_idx = ".$office." AND `status` = 1
        UNION
        SELECT 'DEPARTMENT' as category, COUNT(idx) as total FROM master_organization WHERE `status` = 1 AND status_delete = 0
        UNION
        SELECT 'MANGKIR' AS category, COUNT(a.idx) as total FROM master_employee a WHERE (SELECT COUNT(employee_id) FROM attendance_employee WHERE employee_id = a.employee_id AND attendance_date = '".$now."') = 0
        UNION
        SELECT 'IZIN' AS category, COUNT(a.idx) as total FROM master_employee a
        WHERE (CASE WHEN '$today' = 'monday' THEN (SELECT monday_in FROM master_shift WHERE idx = a.office_shift)
            WHEN '$today' = 'tuesday' THEN (SELECT tuesday_in FROM master_shift WHERE idx = a.office_shift)
            WHEN '$today' = 'wednesday' THEN (SELECT wednesday_in FROM master_shift WHERE idx = a.office_shift)
            WHEN '$today' = 'thursday' THEN (SELECT thursday_in FROM master_shift WHERE idx = a.office_shift)
            WHEN '$today' = 'friday' THEN (SELECT friday_in FROM master_shift WHERE idx = a.office_shift)
            WHEN '$today' = 'saturday' THEN (SELECT saturday_in FROM master_shift WHERE idx = a.office_shift)
            ELSE (SELECT sunday_in FROM master_shift WHERE idx = a.office_shift) END) < '".$timeNow."' 
        AND 
            (SELECT DATE_FORMAT(check_out,'%H:%i:%s') FROM attendance_employee WHERE employee_id = a.employee_id AND attendance_date = '".$now."') < '".$timeNow."'";
        $query = $this->db->query($queryUnion)->result_array();
        return $query;
    }
}