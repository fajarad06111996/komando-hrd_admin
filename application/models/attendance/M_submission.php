<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_submission extends CI_Model 
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

    // start datatables 
    private function _get_datatables_query($idx) {
        $select_column = array(
            'a.*',
            '(SELECT status_name FROM status_submission_type WHERE status = a.sub_type) as sub_type_name',
            '(SELECT sub_date FROM submission_header WHERE idx = a.sub_idx) as sub_date',
            '(SELECT employee_name FROM master_employee WHERE employee_id = a.employee_id) as employee_name'
        );//set column field database for datatable select
        $column_order = array(
            null,
        ); //set column field database for datatable orderable
        $column_search = array(
            'IF(1=0,0,(SELECT `employee_name` FROM `master_employee` WHERE `employee_id` = `a`.`employee_id`))',
            'a.description'
        ); //set column field database for datatable searchable
        $order = array('a.idx' => 'desc'); // default order
        $this->db->select($select_column);
        $this->db->from('submission_detail a');
        $this->db->where('a.sub_idx', $idx);
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
            
        if(isset($_POST['order']) && $_POST['order']['0']['column'] != '0') { // here order processing
            $this->db->order_by($column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        }  else if(isset($order)) {
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }
    function get_datatables($idx) {
        $this->_get_datatables_query($idx);
        if(@$_POST['length'] != -1)
        $this->db->limit(@$_POST['length'], @$_POST['start']);
        // $query = $this->db->get_compiled_select();
        $query = $this->db->get()->result();
        return $query;
    }
    function count_filtered($idx) {
        $this->_get_datatables_query($idx);
        $query = $this->db->get()->num_rows();
        return $query;
    }
    function count_all($idx) {
        $this->db->from('submission_detail a');
        $this->db->where('a.sub_idx', $idx);
        return $this->db->count_all_results();
    }
    // end datatables

    public function getEmployee()
    {
        $this->db->select('*')
        ->from('master_employee')
        ->where(['company_idx' => $this->office, 'status_delete' => 0]);
        $result = $this->db->get()->result();
        return $result;
    }

    public function getCalendarSub($month, $year)
    {
        $this->db->select('idx,sub_code,sub_date,start_date,end_date')
        ->from('submission_header')
        ->where(['company_idx' => $this->office, "MONTH(sub_date) = $month" => null, "YEAR(sub_date) = $year" => null]);
        // $result = $this->db->get_compiled_select();
        $result = $this->db->get()->result();
        return $result;
    }

    public function checkSubByDate($dateCalendar)
    {
        $this->db->select('idx,sub_code,sub_date,start_date,end_date')
        ->from('submission_header')
        ->where(['company_idx' => $this->office, "sub_date" => $dateCalendar]);
        // $result = $this->db->get_compiled_select();
        $result = $this->db->get()->num_rows();
        return $result;
    }

    public function getSubmissionType()
    {
        return $this->db->get('status_submission_type')->result();
    }

    public function getSub($idx)
    {
        return $this->db->get_where('submission_header', ['idx' => $idx, 'company_idx' => $this->office])->row_array();
    }
    public function getSubDetail($idx)
    {
        return $this->db->get_where('submission_detail', ['idx' => $idx])->row_array();
    }
    public function cekExist($employee_id, $sub_date)
    {
        $this->db->select('a.*,(SELECT employee_name FROM master_employee WHERE employee_id = a.employee_id) as employee_name')
        ->from('submission_detail a')
        ->where(['a.employee_id' => $employee_id, 'a.sub_date' => $sub_date]);
        $result = $this->db->get()->result();
        return $result;
    }
    public function insertEvent($data)
    {
        $this->db->trans_begin();
        $this->db->insert('submission_header', $data);
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }
    public function insertIncDetail($dataInsert)
    {
        $this->db->trans_begin();
        $this->db->insert('submission_detail', $dataInsert);
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }
    
    public function updateIncDetail($data, $id)
    {
        $this->db->trans_begin();
        $this->db->where('idx', $id);
        $this->db->update('submission_detail', $data);
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

    public function deleteSubmission($id)
    {
        $this->db->trans_begin();
        $this->db->where('idx', $id);
        $this->db->delete('submission_detail');
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

    public function confirmSub($data, $id)
    {
        $this->db->trans_begin();
        $this->db->where('idx', $id);
        $this->db->update('submission_detail', $data);
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

    public function confirmAllSub($data, $id)
    {
        $this->db->trans_begin();
        $this->db->where('sub_idx', $id);
        $this->db->update('submission_detail', $data);
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

/* End of file M_calendar.php */
/* Location: ./application/models/core/M_calendar.php */