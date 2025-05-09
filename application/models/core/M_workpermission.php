<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_workpermission extends CI_Model 
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
            'a.idx, a.permission_no AS permission_no,
                                            a.start_date AS start_date,
                                            a.end_date AS end_date,
                                            a.remarks AS remarks,
                                            a.url_attachment AS url_attachment,
                                            a.status AS status,
                                            (SELECT employee_name FROM master_employee WHERE employee_id = a.employee_id) AS employee_name,
                                            (SELECT status_name FROM status_permission_type WHERE status = a.permission_type) AS permission_type_name,
                                            (SELECT status_name FROM status_permission_employee WHERE status = a.status) AS status_name'
        );//set column field database for datatable select
        $column_order = array(
            null,
            'a.permission_no',
            'b.employee_name',
            'a.status'
        ); //set column field database for datatable orderable
        $column_search = array(
            'a.permission_no',
            'b.employee_name',
            'a.status'
        ); //set column field database for datatable searchable
        $order = array('a.idx' => 'desc'); // default order
        $this->db->select($select_column);
        $this->db->from('permission_employee a');
        $this->db->join('master_employee b', 'b.employee_id = a.employee_id');
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
        $this->db->from('permission_employee a');
        return $this->db->count_all_results();
    }
}
