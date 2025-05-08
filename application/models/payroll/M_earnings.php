<?php 
defined('BASEPATH') or exit('No direct script access allowed');

class M_earnings extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('ModelGlobal');
        $this->load->library('Secure');
        $this->office   = $this->secure->dec($this->session->userdata('JToffice_id'));
        $this->hub      = $this->secure->dec($this->session->userdata('JThub_id'));
        $this->idx      = $this->secure->dec($this->session->userdata('JTidx'));
        $this->username = $this->session->userdata('JTuser_id');
        $this->level    = $this->secure->dec($this->session->userdata('JTlevel'));
    }
    // CONTOH LAIN MODEL2 DATATABLE SERVERSIDE USER
    // start datatables 
  
    private function _get_datatables_query() {
        $select_column = array(
            'a.*'
            
        );//set column field database for datatable select
        $column_order = array(
            null,
            'a.allowance_code',
            'a.total_item',
            'a.grandtotal',
            'a.description',
        ); //set column field database for datatable orderable
        $column_search = array(
            'a.allowance_code',
            'a.total_item',
            'a.grandtotal',
            'a.description',
        ); //set column field database for datatable searchable
        $order = array('a.idx' => 'asc'); // default order
        $this->db->select($select_column);
        $this->db->from('allowance_full a');
        $this->db->where('a.company_idx', $this->office);
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
        $query = $this->db->get();
        return $query->result();
    }
    function count_filtered() {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }
    function count_all() {
        $this->db->from('allowance_full a');
        $this->db->where('a.company_idx', $this->office);
        return $this->db->count_all_results();
    }
    // end datatables
    // CONTOH LAIN MODEL2 DATATABLE SERVERSIDE USER END

    // CONTOH LAIN MODEL2 DATATABLE SERVERSIDE USER
    // start datatables 
  
    private function _get_datatables_query2($all_id) {
        $select_column = array(
            'a.*'
        );//set column field database for datatable select
        $column_order = array(
            null,
            null,
            'a.employee_name',
            'a.employee_code',
            'a.meal_allowance',
            'a.transport_allowance',
            'a.overtime_allowance',
            'a.allowance_value',
            'a.description',
        ); //set column field database for datatable orderable
        $column_search = array(
            'a.employee_name',
            'a.employee_code',
            'a.description',
        ); //set column field database for datatable searchable
        $order = array('a.idx' => 'asc'); // default order
        $this->db->select($select_column);
        $this->db->from('allowance_full_detail a');
        $this->db->where(['a.company_idx' => $this->office, 'a.allowf_id' => $all_id]);
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
            
        // var_dump('<pre>');var_dump($_POST['order']);die;
        if(!empty($_POST['order']) && $_POST['order']['0']['column'] != '0') { // here order processing
            $this->db->order_by($column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        }  else if(isset($order)) {
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }
    function get_datatables2($all_id) {
        $this->_get_datatables_query2($all_id);
        if(@$_POST['length'] != -1)
        $this->db->limit(@$_POST['length'], @$_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }
    function count_filtered2($all_id) {
        $this->_get_datatables_query2($all_id);
        $query = $this->db->get();
        return $query->num_rows();
    }
    function count_all2($all_id) {
        $this->db->from('allowance_full_detail a');
        $this->db->where(['a.company_idx' => $this->office, 'a.allowf_id' => $all_id]);
        return $this->db->count_all_results();
    }
    // end datatables
    // CONTOH LAIN MODEL2 DATATABLE SERVERSIDE USER END
    
    public function getEarnData($from_date, $to_date, $company_idx){
        $this->db->select('a.*')
        ->from('attendance_employee a')
        ->where(['(DATE_FORMAT(a.check_in, "%Y-%m-%d") between "'.$from_date.'" and "'.$to_date.'")' => null, 'a.company_idx' => $company_idx, 'a.status_draft_full' => 0]);
        // $result = $this->db->get_compiled_select();
        $result = $this->db->get()->result();
        return $result;
    }

    public function getEarnDataEscDrafted($from_date, $to_date, $company_idx){
        $this->db->select('a.*')
        ->from('attendance_employee a')
        ->where(['(DATE_FORMAT(a.check_in, "%Y-%m-%d") between "'.$from_date.'" and "'.$to_date.'")' => null, 'a.company_idx' => $company_idx]);
        // $result = $this->db->get_compiled_select();
        $result = $this->db->get()->result();
        return $result;
    }

    public function get_employee_all(){
        $column = [
            'a.*'
        ];
        $this->db->select($column)
        ->from('master_employee a')
        ->where(['a.company_idx' => $this->office, 'a.status' => 1]);
        $result = $this->db->get()->result();
        return $result;
    }

    public function get_employee($empid){
        $column = [
            'a.*',
            '(SELECT monday_in FROM master_shift WHERE idx = a.office_shift) as monday',
            '(SELECT tuesday_in FROM master_shift WHERE idx = a.office_shift) as tuesday',
            '(SELECT wednesday_in FROM master_shift WHERE idx = a.office_shift) as wednesday',
            '(SELECT thursday_in FROM master_shift WHERE idx = a.office_shift) as thursday',
            '(SELECT friday_in FROM master_shift WHERE idx = a.office_shift) as friday',
            '(SELECT saturday_in FROM master_shift WHERE idx = a.office_shift) as saturday',
            '(SELECT sunday_in FROM master_shift WHERE idx = a.office_shift) as sunday'
        ];
        $this->db->select($column)
        ->from('master_employee a')
        ->where('a.employee_id', $empid);
        // $result = $this->db->get_compiled_select();
        $result = $this->db->get();
        if($result->num_rows()>0){
            $header = $result->row_array();
            $this->db->select('a.*')
            ->from('attendance_setup a')
            ->where(['a.status' => 1, 'a.shift_idx' => (int)$header['office_shift']]);
            $result2 = $this->db->get()->result_array();
            $data = [
                'data_tolerance' => $result2
            ];
            return array_merge($header, $data);
        }else{
            return $result->row_array();
        }
    }

    public function get_employee_on_full($empid, $all_id){
        $column = [
            'a.*'
        ];
        $this->db->select($column)
        ->from('allowance_full_detail a')
        ->where(['a.employee_id' => $empid, 'a.allowf_id' => $all_id, 'a.company_idx' => $this->office]);
        $result = $this->db->get()->row_array();
        return $result;
    }

    public function getAllowHeader($all_id)
    {
        $this->db->select('a.*')
        ->from('allowance_full a')
        ->where('allowf_id', $all_id);
        $result = $this->db->get()->row_array();
        return $result;
    }

    public function printSalaryDetail($idx)
    {
        $this->db->select('a.*,(SELECT designation_name FROM master_designation WHERE idx = (SELECT designation_idx FROM master_employee WHERE employee_id = a.employee_id)) as designation_name,(SELECT department_name FROM master_department WHERE idx = (SELECT department_idx FROM master_employee WHERE employee_id = a.employee_id)) as department_name')
        ->from('allowance_full_detail a')
        ->where('a.idx', $idx);
        $result = $this->db->get()->row();
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

    public function getToleranceAbsen($shift_idx){
        $this->db->select('a.*')
        ->from('attendance_setup a')
        ->where(['a.shift_idx' =>  $shift_idx, 'a.status' => 1]);
        // $result = $this->db->get_compiled_select();
        $result = $this->db->get()->result();
        return $result;
    }

    public function printEarnHeader($idx)
    {
        $this->db->select('a.*,(SELECT user_name FROM user_account WHERE idx = a.created_by) as user_name')
        ->from('allowance_full a')
        ->where('a.idx', $idx);
        $result = $this->db->get()->row_array();
        return $result;
    }

    public function printEarnDetail($all_id)
    {
        $this->db->select('a.*')
        ->from('allowance_full_detail a')
        ->where('allowf_id', $all_id);
        $result = $this->db->get()->result();
        return $result;
    }

    public function company()
    {
        $this->db->select('a.*')
        ->from('master_company a')
        ->where('a.idx', $this->office);
        $result = $this->db->get()->row_array();
        return $result;
    }

    public function getDataHeader($all_idx){
        $this->db->select('a.allowance_code, a.start, a.end, all_att_idx, a.description')
        ->from('allowance_full a')
        ->where(['a.allowf_id' =>  $all_idx, 'a.status' => 1]);
        $result = $this->db->get()->row_array();
        return $result;
    }
    
    public function insertEarnings($dataHeader, $dataDetail)
    {
        $this->db->trans_begin();
        $this->db->insert('allowance_full', $dataHeader);
        $this->db->query($dataDetail);
        if($this->db->trans_status() === FALSE){
    		$this->db->trans_rollback();
    		return 0;
    	}else{
    		$this->db->trans_commit();
    		return 1;
    	}
    }

    public function updateEarnings($all_id, $dataHeader, $dataDetail="")
    {
        $this->db->trans_begin();
        $this->db->where(['allowf_id' => $all_id]);
        $this->db->update('allowance_full', $dataHeader);
        if(!empty($dataDetail)){
            $this->db->query($dataDetail);
        }
        if($this->db->trans_status() === FALSE){
    		$this->db->trans_rollback();
    		return 0;
    	} else {
    		$this->db->trans_commit();
    		return 1;
    	}
    }

    public function publishEarnings($idx, $dataHeader, $queryUpdate="")
    {
        $this->db->trans_begin();
        $this->db->where(['idx' => $idx]);
        $this->db->update('allowance_full', $dataHeader);
        if(!empty($queryUpdate)){
            $this->db->query($queryUpdate);
        }
        if($this->db->trans_status() === FALSE){
    		$this->db->trans_rollback();
    		return 0;
    	} else {
    		$this->db->trans_commit();
    		return 1;
    	}
    }
}
?>