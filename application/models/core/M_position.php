<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_position extends CI_Model 
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
            '(SELECT department_name FROM master_department WHERE idx = a.dept_idx) as department_name'
        );//set column field database for datatable select
        $column_order = array(
            null,
            'a.designation_name',
            '(CASE WHEN 1 = 0 THEN 0 ELSE (SELECT `employee_name` FROM `master_employee` WHERE `idx` = `a`.`dept_head_idx`) END)',
            'a.description',
            'a.status'
        ); //set column field database for datatable orderable
        $column_search = array(
            'a.designation_name',
            '(CASE WHEN 1 = 0 THEN 0 ELSE (SELECT `department_name` FROM `master_department` WHERE `idx` = `a`.`dept_idx`) END)',
            'a.description',
            'a.status'
        ); //set column field database for datatable searchable
        $order = array('a.idx' => 'desc'); // default order
        $this->db->select($select_column);
        $this->db->from('master_designation a');
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
        $this->db->from('master_designation a');
        return $this->db->count_all_results();
    }
    // end datatables
    // CONTOH LAIN MODEL2 DATATABLE SERVERSIDE USER END
    // CONTOH LAIN MODEL2 DATATABLE SERVERSIDE USER
    // start datatables 

    private function _get_dataTolerance_query($desg_idx) {
        $select_column = array(
            'a.*',
            '(SELECT designation_name FROM master_designation WHERE idx = a.designation_idx) as designation_name'
        );//set column field database for datatable select
        $column_order = array(
            null,
            null,
            'a.status',
            '(CASE WHEN 1 = 0 THEN 0 ELSE (SELECT `designation_name` FROM `master_designation` WHERE `idx` = `a`.`designation_idx`)END)',
            'a.tolerance_in',
            'a.tolerance_out',
            'a.description'
        ); //set column field database for datatable orderable
        $column_search = array(
            '(CASE WHEN 1 = 0 THEN 0 ELSE (SELECT `designation_name` FROM `master_designation` WHERE `idx` = `a`.`designation_idx`)END)',
            'a.tolerance_in',
            'a.tolerance_out',
            'a.description',
            'a.status'
        ); //set column field database for datatable searchable
        $order = array('a.idx' => 'asc'); // default order
        $this->db->select($select_column);
        $this->db->from('attendance_setup a');
        $this->db->where(['a.designation_idx' => $desg_idx]);
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
    function get_dataTolerance($desg_idx) {
        $this->_get_dataTolerance_query($desg_idx);
        if(@$_POST['length'] != -1)
        $this->db->limit(@$_POST['length'], @$_POST['start']);
        $query = $this->db->get()->result();
        return $query;
    }
    function tolerance_filtered($desg_idx) {
        $this->_get_dataTolerance_query($desg_idx);
        $query = $this->db->get()->num_rows();
        return $query;
    }
    function tolerance_all($desg_idx) {
        $this->db->from('attendance_setup a');
        $this->db->where(['a.designation_idx' => $desg_idx]);
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

    public function getDesignationName($id)
    {
        $this->db->select('designation_name')
        ->from('master_designation')
        ->where('idx', $id);
        $hasilnya = $this->db->get()->row();
        return $hasilnya;
    }

    public function editDesignation($id)
    {
        $this->db->select('*')
        ->from('master_designation')
        ->where('idx',$id);
        $hasilnya = $this->db->get()->row_array();
        return $hasilnya;
    }

    public function editTolerance($id)
    {
        $this->db->select('*')
        ->from('attendance_setup')
        ->where('idx',$id);
        $hasilnya = $this->db->get()->row_array();
        return $hasilnya;
    }

    public function insertDesignation($data)
    {
        $this->db->trans_begin();
        $this->db->insert('master_designation', $data);
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

    public function updateDesignation($data, $idx)
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
}

/* End of file M_organization.php */
/* Location: ./application/models/core/M_organization.php */