<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_shifting extends CI_Model 
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
            'a.*'
        );//set column field database for datatable select
        $column_order = array(
            null,
            'a.shift_name',
            'a.description'
        ); //set column field database for datatable orderable
        $column_search = array(
            'a.shift_name',
            'a.description'
        ); //set column field database for datatable searchable
        $order = array('a.idx' => 'desc'); // default order
        $this->db->select($select_column);
        $this->db->from('master_shift a');
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
            
        if(!empty($_POST['order']) && $_POST['order']['0']['column'] != '0') { // here order processing
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
        $this->db->from('master_shift a');
        return $this->db->count_all_results();
    }
    // end datatables
    // CONTOH LAIN MODEL2 DATATABLE SERVERSIDE USER END

    // CONTOH LAIN MODEL2 DATATABLE SERVERSIDE USER
    // start datatables 

    private function _get_dataTolerance_query($shfIdx) {
        $select_column = array(
            'a.*',
            '(SELECT shift_name FROM master_shift WHERE idx = a.shift_idx) as shift_name'
        );//set column field database for datatable select
        $column_order = array(
            null,
            null,
            'a.status',
            '(CASE WHEN 1 = 0 THEN 0 ELSE (SELECT `shift_name` FROM `master_shift` WHERE `idx` = `a`.`shift_idx`)END)',
            'a.tolerance_in',
            'a.tolerance_out',
            'a.description'
        ); //set column field database for datatable orderable
        $column_search = array(
            '(CASE WHEN 1 = 0 THEN 0 ELSE (SELECT `shift_name` FROM `master_shift` WHERE `idx` = `a`.`shift_idx`)END)',
            'a.tolerance_in',
            'a.tolerance_out',
            'a.description',
            'a.status'
        ); //set column field database for datatable searchable
        $order = array('a.idx' => 'asc'); // default order
        $this->db->select($select_column);
        $this->db->from('attendance_setup a');
        $this->db->where(['a.shift_idx' => $shfIdx]);
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
    function get_dataTolerance($shfIdx) {
        $this->_get_dataTolerance_query($shfIdx);
        if(@$_POST['length'] != -1)
        $this->db->limit(@$_POST['length'], @$_POST['start']);
        $query = $this->db->get()->result();
        return $query;
    }
    function tolerance_filtered($shfIdx) {
        $this->_get_dataTolerance_query($shfIdx);
        $query = $this->db->get()->num_rows();
        return $query;
    }
    function tolerance_all($shfIdx) {
        $this->db->from('attendance_setup a');
        $this->db->where(['a.shift_idx' => $shfIdx]);
        return $this->db->count_all_results();
    }
    // end datatables
    // CONTOH LAIN MODEL2 DATATABLE SERVERSIDE USER END

    // CONTOH LAIN MODEL2 DATATABLE SERVERSIDE USER
    // start datatables 

    private function _get_cycleMode_query($shfIdx) {
        $select_column = array(
            'a.*',
            '(SELECT organization_name FROM master_organization WHERE idx = a.organization_idx) as organization_name',
            '(SELECT department_name FROM master_department WHERE idx = a.department_idx) as department_name',
            '(SELECT designation_name FROM master_designation WHERE idx = a.department_idx) as designation_name'
        );//set column field database for datatable select
        $column_order = array(
            null,
        ); //set column field database for datatable orderable
        $column_search = array(
            null,
        ); //set column field database for datatable searchable
        $order = array('a.idx' => 'asc'); // default order
        $this->db->select($select_column);
        $this->db->from('master_employee a');
        $this->db->where("EXISTS(SELECT 1 FROM `dynamic_shift` WHERE `employee_id` = `a`.`employee_id` AND `shift_idx` = $shfIdx)", NULL);
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
            
        if(isset($_POST['order'])) { // here order processing
            $this->db->order_by($column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        }  else if(isset($order)) {
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }
    function get_cycleMode($shfIdx) {
        $this->_get_cycleMode_query($shfIdx);
        if(@$_POST['length'] != -1)
        $this->db->limit(@$_POST['length'], @$_POST['start']);
        // $query = $this->db->get_compiled_select();
        $query = $this->db->get()->result();
        return $query;
    }
    function cycle_filtered($shfIdx) {
        $this->_get_cycleMode_query($shfIdx);
        $query = $this->db->get()->num_rows();
        return $query;
    }
    function cycle_all($shfIdx) {
        $this->db->from('master_employee a');
        $this->db->where("EXISTS(SELECT 1 FROM `dynamic_shift` WHERE `employee_id` = `a`.`employee_id` AND `shift_idx` = $shfIdx)", NULL);
        return $this->db->count_all_results();
    }
    // end datatables
    // CONTOH LAIN MODEL2 DATATABLE SERVERSIDE USER END

    public function getDepartment()
    {
        $this->db->select('*')
        ->from('master_department');
        $hasilnya = $this->db->get()->result();
        return $hasilnya;
    }

    public function getEmployeeShift($shift_idx)
    {
        $this->db->select('a.employee_id, a.employee_name')
        ->from('master_employee a')
        ->where('a.office_shift', $shift_idx);
        $hasilnya = $this->db->get()->result();
        return $hasilnya;
    }

    public function getShiftName($id)
    {
        $this->db->select('a.shift_name,a.work_days,a.off_days')
        ->from('master_shift a')
        ->where('a.idx', $id);
        $hasilnya = $this->db->get()->row();
        return $hasilnya;
    }

    public function cekExsistDynamic($employee_id, $shf_idx, $current_date)
    {
        $this->db->select('*')
        ->from('dynamic_shift')
        ->where(['employee_id'=> $employee_id, 'date' => $current_date, 'shift_idx' => $shf_idx, 'company_idx' => $this->office]);
        $hasilnya = $this->db->get()->row();
        return $hasilnya;
    }

    public function getCycle($employee_id,$start_date)
    {
        $this->db->select('*')
        ->from('dynamic_shift')
        ->group_start()
        ->where(["date >= DATE_FORMAT('$start_date', '%Y-%m-01')"=> null, "date < DATE_FORMAT('$start_date' + INTERVAL 1 MONTH, '%Y-%m-01')"=>null])
        ->group_end()
        ->where(['employee_id'=> $employee_id, 'company_idx' => $this->office]);
        // $hasilnya = $this->db->get_compiled_select();
        $hasilnya = $this->db->get()->result();
        return $hasilnya;
    }

    public function editShift($id)
    {
        $this->db->select('*')
        ->from('master_shift')
        ->where('idx',$id);
        $hasilnya = $this->db->get()->row_array();
        return $hasilnya;
    }

    public function insertShift($data)
    {
        $this->db->trans_begin();
        $this->db->insert('master_shift', $data);
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

    public function updateShift($data, $idx)
    {
        $this->db->trans_begin();
        $this->db->where('idx', $idx);
        $this->db->update('master_shift', $data);
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
        $this->db->update('master_designation', $data);
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }
    public function editTolerance($id)
    {
        $this->db->select('*')
        ->from('attendance_setup')
        ->where('idx',$id);
        $hasilnya = $this->db->get()->row_array();
        return $hasilnya;
    }
    public function insertTolerance($data)
    {
        $this->db->trans_begin();
        $this->db->insert('attendance_setup', $data);
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

    public function updateTolerance($data, $id)
    {
        $this->db->trans_begin();
        $this->db->where('idx', $id);
        $this->db->update('attendance_setup', $data);
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

    public function insertCycle($insert="", $update="")
    {
        $this->db->trans_begin();
        if(!empty($insert)){
            $this->db->query($insert);
        }
        if(!empty($update)){
            $this->db->query($update);
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
    public function deleteToleran($idx)
    {
        $this->db->trans_begin();
        $this->db->where('idx', $idx);
        $this->db->delete('attendance_setup');
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }
}

/* End of file M_organization.php */
/* Location: ./application/models/core/M_organization.php */