<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_ot extends CI_Model 
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('ModelGlobal');
        $this->load->library('Secure');
        $this->office   = $this->secure->dec($this->session->userdata('JToffice_id'));
        $this->tOffice  = $this->secure->dec($this->session->userdata('JToffice_type'));
        $this->hub      = $this->secure->dec($this->session->userdata('JThub_id'));
        $this->idx      = $this->secure->dec($this->session->userdata('JTidx'));
        $this->username = $this->session->userdata('JTuser_id');
        $this->level    = $this->secure->dec($this->session->userdata('JTlevel'));
    }
    // CONTOH LAIN MODEL2 DATATABLE SERVERSIDE USER
    // start datatables 

    private function _get_datatables_query() {
        $select_column = array(
            'a.*',
            '(SELECT department_name FROM master_department WHERE idx = a.department_idx) as department_name',
            '(SELECT designation_name FROM master_designation WHERE idx = a.designation_idx) as designation_name',
            '(SELECT status_name FROM status_gender WHERE status = a.gender) as genderx',
            '(SELECT status_name FROM status_payslip WHERE status = a.payslip_type) as payslip_typex',
            '(SELECT shift_name FROM master_shift WHERE idx = a.office_shift) as shift',
            '(SELECT idx FROM presence WHERE employee_id = a.employee_id AND DATE(date) = CURDATE()) as presence_idx',
            '(SELECT `in` FROM presence WHERE employee_id = a.employee_id AND DATE(date) = CURDATE()) as in_absen',
            '(SELECT `out` FROM presence WHERE employee_id = a.employee_id AND DATE(date) = CURDATE()) as out_absen'
        );//set column field database for datatable select
        $column_order = array(
            null,
            null,
            null,
            null,
            null
        ); //set column field database for datatable orderable
        $column_search = array(
            null
        ); //set column field database for datatable searchable
        $order = array('a.idx' => 'desc'); // default order
        $this->db->select($select_column);
        $this->db->from('master_employee a');
        $i = 0;
        foreach ($column_search as $item) { // loop column 
            if(@$_POST['search']['value']) { // if datatable send POST for search
                if($i===0) { // first loop
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, trim($_POST['search']['value']));
                } else {
                    $this->db->or_like($item, trim($_POST['search']['value']));
                }
                if(count($column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }
            
        if($_POST['order']['0']['column'] != '0') { // here order processing
            $this->db->order_by($column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        }  else if(isset($order)) {
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }
    function get_datatables() {
        $this->_get_datatables_query();
        if(@$_POST['length'] != -1)
        $this->db->limit(@$_POST['length'], @$_POST['start']);
        $query = $this->db->get()->result();
        return $query;
    }
    function count_filtered() {
        $this->_get_datatables_query();
        $query = $this->db->get()->num_rows();
        return $query;
    }
    function count_all() {
        $this->db->from('master_employee a');
        return $this->db->count_all_results();
    }
    // end datatables
    // CONTOH LAIN MODEL2 DATATABLE SERVERSIDE USER END

    // SELECT2 SERVERSIDE
    public function getDataAjaxRemote($search, $type)
    {
        $this->db->select('*');
        $this->db->from('master_employee');
        $this->db->where(['status'=>1,'company_idx'=>$this->office]);
        $this->db->like('employee_name', $search);
        if($type == 'data'){
            return $this->db->get()->result_array();
        }else{
            return $this->db->get()->num_rows();
        }
    }
    // SELECT2 SERVERSIDE END
    // SELECT2 SERVERSIDE
    public function getDataAjaxRemoteId($search, $type)
    {
        $this->db->select('*');
        $this->db->from('master_employee');
        $this->db->where('employee_id', $search);
        if($type == 'data'){
            return $this->db->get()->result_array();
        }else{
            return $this->db->get()->num_rows();
        }
    }
    // SELECT2 SERVERSIDE END
    public function getEmployee()
    {
        $this->db->select('*')
        ->from('master_employee');
        $hasilnya = $this->db->get()->result();
        return $hasilnya;
    }
    public function getEmployeeById($emp_id)
    {
        $this->db->select('*')
        ->from('master_employee')
        ->where('employee_id', $emp_id);
        $hasilnya = $this->db->get()->row();
        return $hasilnya;
    }
    public function countPiket($emp_id, $month, $year)
    {
        $this->db->select('*')
        ->from('attendance_employee')
        ->where(['employee_id' => $emp_id, 'status_piket' => 1, "MONTH(attendance_date) = '$month'" => null, "YEAR(attendance_date) = '$year'" => null]);
        // $hasilnya = $this->db->get_compiled_select();
        $hasilnya = $this->db->count_all_results();
        return $hasilnya;
    }
    public function cekWeeklyPiket($emp_id, $hari_senin, $hari_minggu)
    {
        $this->db->select('*')
        ->from('attendance_employee')
        ->where(['employee_id' => $emp_id, 'status_piket' => 1, "attendance_date BETWEEN '$hari_senin' AND '$hari_minggu'" => null]);
        $hasilnya = $this->db->get()->result();
        // $hasilnya = $this->db->get_compiled_select();
        return $hasilnya;
    }
    public function syncPresence($data)
    {
        $url = 'https://developer.fingerspot.io/api/get_attlog';
        $authorization = "Authorization: Bearer 40N6WOEI21SL7LO8";

        $response = null;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            $response = curl_error($ch);
        } else {
            $response = $result;
        }
        curl_close($ch);
        return json_decode($response);
    }
    public function cekAbsenNowx($emp_id, $now)
	{
		$this->db->select('*')
		->from('attendance_employee')
		->where(['employee_id' => $emp_id, 'attendance_date' => $now]);
		return $this->db->get()->row_array();
	}
    public function cekAbsenNow($pin, $cloud_id, $now)
	{
		$this->db->select('a.employee_id, a.office_shift, a.office_idx, a.company_idx, b.att_idx')
		->from('master_employee a')
        ->join('attendance_employee b', 'a.employee_id = b.employee_id', 'left')
		->where(['a.att_pin' => $pin, 'a.att_code' => $cloud_id, 'b.attendance_date' => "2024-09-13"]);
		return $this->db->get()->row();
	}
    public function getShift($shift_idx)
	{
		$this->db->select('*')
		->from('master_shift')
		->where('idx', $shift_idx);
		return $this->db->get()->row_array();
	}
    public function cekDeptCode($department_code)
    {
        $this->db->select('department_code')
        ->from('master_department')
        ->where('department_code', $department_code);
        $hasilnya = $this->db->get()->result();
        return $hasilnya;
    }

    public function editDepartment($id)
    {
        $this->db->select('*')
        ->from('master_department')
        ->where('idx',$id);
        $hasilnya = $this->db->get()->row_array();
        return $hasilnya;
    }

    public function insertDepartment($data)
    {
        $this->db->trans_begin();
        $this->db->insert('master_department', $data);
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

    public function updateDepartment($data, $idx)
    {
        $this->db->trans_begin();
        $this->db->where('idx', $idx);
        $this->db->update('master_department', $data);
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

    public function updateOt($data, $idx, $updateOldOt="")
    {
        $this->db->trans_begin();
        $this->db->where('idx', $idx);
        $this->db->update('attendance_employee', $data);
        if(!empty($updateOldOt)){
            $this->db->query($updateOldOt);
        }
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

    public function changeStatus($data, $idx)
    {
        $this->db->trans_begin();
        $this->db->where('idx', $idx);
        $this->db->update('master_department', $data);
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

    public function getAttendanceEmployeeAll($tAwal,$tAkhir,$employee_id="")
    {
        if(empty($employee_id)){
            $where = "";
        }else{
            $where = "AND a.employee_id = $employee_id ";
        }
        $order = "ORDER BY a.employee_id, b.cal_date";
        $query = "SELECT 
                b.cal_date as calendar_date,
                a.employee_id,
                a.ot_id,
                a.employee_name,
                c.attendance_no,
                c.attendance_date,
                c.shift_idx,
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
                (SELECT check_in FROM dynamic_shift WHERE shift_idx = a.office_shift AND `date` = b.cal_date AND employee_id = a.employee_id) as dynamic_check_in,
                (SELECT check_out FROM dynamic_shift WHERE shift_idx = a.office_shift AND `date` = b.cal_date AND employee_id = a.employee_id) as dynamic_check_out,
                d.shift_mode,
                d.monday_in as monday,
                d.tuesday_in as tuesday,
                d.wednesday_in as wednesday,
                d.thursday_in as thursday,
                d.friday_in as friday,
                d.saturday_in as saturday,
                d.sunday_in as sunday
            FROM master_employee a
            CROSS JOIN calendar b
            LEFT JOIN attendance_employee c ON b.cal_date = c.attendance_date AND a.employee_id = c.employee_id
            LEFT JOIN master_shift d ON d.idx = a.office_shift
            WHERE b.cal_date BETWEEN '$tAwal' AND '$tAkhir'
            AND (a.company_idx = 1 OR a.company_idx IS NULL) ".$where.$order;
        $hasilnya = $this->db->query($query)->result_array();
        return $hasilnya;
    }

    public function getOtEmployeeAll($tAwal,$tAkhir,$employee_id="")
    {
        if(empty($employee_id)){
            $where = "";
        }else{
            $where = "AND a.employee_id = $employee_id ";
        }
        $order = "ORDER BY a.employee_id, b.cal_date";
        $query = "SELECT 
                b.cal_date as calendar_date,
                a.employee_id,
                a.ot_id,
                a.employee_name,
                c.attendance_no,
                c.attendance_date,
                c.shift_idx,
                c.target_in,
                c.check_in,
                c.check_out,
                c.check_out_confirm,
                c.target_out,
                c.target_out,
                c.point_center_check_in,
                c.point_center_check_out,
                c.url_photo_check_in,
                c.url_photo_check_out,
                c.status_permission,
                c.status_overtime,
                c.reason_overtime,
                c.overtime_hour,
                c.company_idx,
                c.`status`,
                c.idx as att_idx,
                TIME(c.check_out) as check_out_time,
                (CASE WHEN (SELECT shift_mode FROM master_shift WHERE idx = c.shift_idx) = 1 THEN (SELECT check_out FROM dynamic_shift WHERE employee_id = c.employee_id AND shift_idx = c.shift_idx AND date = c.attendance_date) ELSE (SELECT tuesday_out FROM master_shift WHERE idx = c.shift_idx) END) as shift_time,
                (SELECT check_in FROM dynamic_shift WHERE shift_idx = a.office_shift AND `date` = b.cal_date AND employee_id = a.employee_id) as dynamic_check_in,
                (SELECT check_out FROM dynamic_shift WHERE shift_idx = a.office_shift AND `date` = b.cal_date AND employee_id = a.employee_id) as dynamic_check_out,
                d.shift_mode,
                d.start_overtime,
                d.piket_in,
                d.piket_out,
                d.monday_in,
                d.tuesday_in,
                d.wednesday_in,
                d.thursday_in,
                d.friday_in,
                d.saturday_in,
                d.sunday_in,
                d.monday_out,
                d.tuesday_out,
                d.wednesday_out,
                d.thursday_out,
                d.friday_out,
                d.saturday_out,
                d.sunday_out,
                TIMESTAMPDIFF(HOUR,(
                CASE WHEN 
                        d.shift_mode = 1 
                    THEN 
                        CONCAT(c.attendance_date, ' ', (SELECT check_out FROM dynamic_shift WHERE employee_id = c.employee_id AND shift_idx = c.shift_idx AND date = c.attendance_date))
                    ELSE 
                        CONCAT(c.attendance_date, ' ', d.start_overtime)
                END), c.check_out) as over_time,
                TIMESTAMPDIFF(HOUR,(
                CASE WHEN 
                        d.shift_mode = 1 
                    THEN 
                        CONCAT(c.attendance_date, ' ', (SELECT check_out FROM dynamic_shift WHERE employee_id = c.employee_id AND shift_idx = c.shift_idx AND date = c.attendance_date)) 
                    ELSE 
                        CONCAT(c.attendance_date, ' ', d.start_overtime)
                END), c.check_out) as over_time_confirm
            FROM master_employee a
            CROSS JOIN calendar b
            LEFT JOIN attendance_employee c ON b.cal_date = c.attendance_date AND a.employee_id = c.employee_id
            LEFT JOIN master_shift d ON d.idx = a.office_shift AND d.idx = c.shift_idx
            WHERE b.cal_date BETWEEN '$tAwal' AND '$tAkhir'
            -- AND TIMESTAMPDIFF(HOUR,(
            --     CASE WHEN 
            --                     (SELECT shift_mode FROM master_shift WHERE idx = c.shift_idx) = 1 
            --                 THEN 
            --                     (SELECT check_out FROM dynamic_shift WHERE employee_id = c.employee_id AND shift_idx = c.shift_idx AND date = c.attendance_date) 
            --                 ELSE 
            --                     (SELECT tuesday_out FROM master_shift WHERE idx = c.shift_idx) 
            --     END), TIME(c.check_out)) >= 1
            AND (a.company_idx = 1 OR a.company_idx IS NULL) ".$where.$order;
        $hasilnya = $this->db->query($query)->result_array();
        return $hasilnya;
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

    public function getAttendanceEmployeeAllx($tAwal,$tAkhir,$employee_id="")
    {
        $column = [
            '*',
            '(SELECT monday_in FROM master_shift WHERE idx = b.office_shift) as monday',
            '(SELECT tuesday_in FROM master_shift WHERE idx = b.office_shift) as tuesday',
            '(SELECT wednesday_in FROM master_shift WHERE idx = b.office_shift) as wednesday',
            '(SELECT thursday_in FROM master_shift WHERE idx = b.office_shift) as thursday',
            '(SELECT friday_in FROM master_shift WHERE idx = b.office_shift) as friday',
            '(SELECT saturday_in FROM master_shift WHERE idx = b.office_shift) as saturday',
            '(SELECT sunday_in FROM master_shift WHERE idx = b.office_shift) as sunday'
        ];
        $this->db->select($column);
        $this->db->from('attendance_employee a');
        $this->db->join('master_employee b', 'a.employee_id = b.employee_id');
        $this->db->group_start();
        $this->db->where("a.created_on BETWEEN '$tAwal' AND '$tAkhir'", null);
        $this->db->group_end();
        if(empty($employee_id)){
            $this->db->where('a.company_idx', $this->office);
        }else{
            $this->db->where(['a.company_idx' => $this->office, 'a.employee_id' => $employee_id]);
        }
        $this->db->order_by('a.created_on', 'ASC');
        // $hasilnya = $this->db->get_compiled_select();
        $hasilnya = $this->db->get()->result_array();
        return $hasilnya;
    }

    public function getValueOt($ot_id)
    {
        $this->db->select('*')
        ->from('master_ot_detail')
        ->where(['ot_id' => $ot_id, 'status' => 1]);
        $result = $this->db->get()->result();
        return $result;
    }
}

/* End of file M_organization.php */
/* Location: ./application/models/core/M_organization.php */