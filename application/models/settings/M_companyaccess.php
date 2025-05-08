<?php 
defined('BASEPATH') or exit('No direct script access allowed');
class M_companyaccess extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('ModelGlobal');
        $this->load->library('Secure');
        $this->office   = $this->secure->dec($this->session->userdata('JToffice_id'));
        $this->hub      = $this->secure->dec($this->session->userdata('JThub_id'));
        $this->counter  = $this->secure->dec($this->session->userdata('JTcounter_id'));
        $this->idx      = $this->secure->dec($this->session->userdata('JTidx'));
        $this->username = $this->session->userdata('JTuser_id');
        $this->level    = $this->secure->dec($this->session->userdata('JTlevel'));
    }
    // DATATABLE SERVERSIDE OFFICE
    // start datatables
    private function _get_datatables_query() 
    {
        $select_column = "
            a.*,
            (SELECT level_name FROM app_level_access WHERE level_id = a.access_level_id) as level_name
            ";//set column field database for datatable select
        $column_order = array(
            null,
            'a.acces_level_id',
            'a.access_office_idx',
            'a.access_hub_idx',
            null
        ); //set column field database for datatable orderable
        $column_search = array(
            '(SELECT level_name FROM app_level_access WHERE level_id = a.access_level_id)',
            'a.access_office_name',
            'a.access_hub_name'
        ); //set column field database for datatable searchable
        $order = array('a.idx' => 'asc'); // default order 
        $this->db->select($select_column);
        $this->db->from('app_oaccess a');
        if($this->level != 1){
            $this->db->where('access_level_id <>', 1);
        }
        $i = 0;
        foreach ($column_search as $item) { // loop column 
            if(@$_POST['search']['value']) { // if datatable send POST for search
                if($i===0) { // first loop
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
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
        $this->db->from('app_oaccess a');
        if($this->level != 1){
            $this->db->where('access_level_id <>', 1);
        }
        return $this->db->count_all_results();
    }
    // end datatables
    // OFFICE END
    // DATATABLE SERVERSIDE HUB
    // start datatables
    var $select_column2 = "
        a.*,
        (SELECT level_name FROM app_level_access WHERE level_id = a.access_level_id) as levelname
        ";//set column field database for datatable select
    var $column_order2 = array(
        null,
        'a.access_level_id',
        'a.access_hub_idx',
        'a.access_hub_name',
        null
    ); //set column field database for datatable orderable
    var $column_search2 = array(
        '(SELECT user_name FROM user_account WHERE idx = a.access_user_id)',
        'a.access_office_name',
        'a.access_hub_name'
    ); //set column field database for datatable searchable
    var $order2 = array('a.idx' => 'asc'); // default order 
    private function _get_datatables_query2() {
        $this->db->select($this->select_column2);
        $this->db->from('app_haccess a');
        if($this->level == 1){
            $this->db->where('office_idx', $this->office);
        }else{
            $this->db->where(['office_idx'=> $this->office,'access_level_id <>'=> 1]);
        }
        // $i = 0;
        // foreach ($this->column_search2 as $item) { // loop column 
        //     if(@$_POST['search']['value']) { // if datatable send POST for search
        //         if($i===0) { // first loop
        //             $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
        //             $this->db->like($item, $_POST['search']['value']);
        //         } else {
        //             $this->db->or_like($item, $_POST['search']['value']);
        //         }
        //         if(count($this->column_search2) - 1 == $i) //last loop
        //             $this->db->group_end(); //close bracket
        //     }
        //     $i++;
        // }
        if(@$_POST['search']['value']) {
            $this->db->like('a.access_hub_name', $_POST['search']['value']);
            $this->db->having('levelname LIKE "%'.$_POST['search']['value'].'%"');
        }
        if(isset($_POST['order'])) { // here order processing
            $this->db->order_by($this->column_order2[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        }  else if(isset($this->order2)) {
            $order = $this->order2;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }
    function get_datatables2() {
        $this->_get_datatables_query2();
        if(@$_POST['length'] != -1)
        $this->db->limit(@$_POST['length'], @$_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }
    function count_filtered2() {
        $this->_get_datatables_query2();
        $query = $this->db->get();
        return $query->num_rows();
    }
    function count_all2() {
        $this->db->from('app_haccess a');
        if($this->level == 1){
            $this->db->where('office_idx', $this->office);
        }else{
            $this->db->where(['office_idx'=> $this->office,'access_level_id <>'=> 1]);
        }
        return $this->db->count_all_results();
    }
    // end datatables
    // DATATABLE SERVERSIDE HUB END
    // DATATABLE SERVERSIDE COUNTER
    // start datatables
    var $select_column3 = "
        a.*,
        (SELECT level_name FROM app_level_access WHERE level_id = a.access_level_id) as levelname
        ";//set column field database for datatable select
    var $column_order3 = array(
        null,
        'a.access_level_id',
        'a.access_counter_idx',
        'a.access_counter_name',
        null
    ); //set column field database for datatable orderable
    var $column_search3 = array(
        '(SELECT user_name FROM user_account WHERE idx = a.access_user_id)',
        'a.access_office_name',
        'a.access_counter_name'
    ); //set column field database for datatable searchable
    var $order3 = array('a.idx' => 'asc'); // default order 
    private function _get_datatables_query3() {
        $this->db->select($this->select_column3);
        $this->db->from('app_counter_access a');
        if($this->level == 1){
            $this->db->where('office_idx', $this->office);
        }else{
            $this->db->where(['office_idx'=> $this->office,'access_level_id <>'=> 1]);
        }
        $i3 = 0;
        foreach ($this->column_search3 as $item) { // loop column 
            if(@$_POST['search']['value']) { // if datatable send POST for search
                if($i3===0) { // first loop
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, trim($_POST['search']['value']));
                } else {
                    $this->db->or_like($item, trim($_POST['search']['value']));
                }
                if(count($this->column_search3) - 1 == $i3) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i3++;
        }
        if($_POST['order']['0']['column'] != '0') { // here order processing
            $this->db->order_by($this->column_order3[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        }  else if(isset($this->order3)) {
            $order = $this->order3;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }
    function get_datatables3() {
        $this->_get_datatables_query3();
        if(@$_POST['length'] != -1)
        $this->db->limit(@$_POST['length'], @$_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }
    function count_filtered3() {
        $this->_get_datatables_query3();
        $query = $this->db->get();
        return $query->num_rows();
    }
    function count_all3() {
        $this->db->from('app_counter_access a');
        if($this->level == 1){
            $this->db->where('office_idx', $this->office);
        }else{
            $this->db->where(['office_idx'=> $this->office,'access_level_id <>'=> 1]);
        }
        return $this->db->count_all_results();
    }
    // end datatables
    // DATATABLE SERVERSIDE HUB COUNTER
    public function queryMOffice($keyword = null)
    {
        if($keyword)
        {
            $search_keyword = " and (office_code like '%$keyword%' or
                                    office_name like '%$keyword%' or
                                    address like '%$keyword%' or
                                    email_id like '%$keyword%')";
        }
        else { $search_keyword = ""; }
        $result = "select *
                    from master_office
                    where idx > 0" . $search_keyword . "
                    order by office_name asc";
        return $result;
    }
    public function searchMOffice($idx)
    {
        $result = $this->db->query("select * from master_office where idx = '$idx'")->row_array();
        return $result;
    }
    public function getMOffice($office_code)
    {
        $result = $this->db->query("select idx from master_office where office_code='$office_code'")->row_array();
        return $result;
    }
    public function editAccessOffice($idx)
    {
        $column = ['a.*'];
        $this->db->select($column)
        ->from('app_oaccess a')
        ->where('a.idx', $idx);
        $result = $this->db->get()->row_array();
        return $result;
    }
    public function insertMOffice($data)
    {
        $this->db->trans_begin();
        $result = $this->db->insert('master_office', $data);
        if($this->db->trans_status() === FALSE)
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
    public function updateMOffice($data, $id_office)
    {
        $this->db->trans_begin();
        $this->db->where('idx', $id_office);
        $this->db->update('master_office', $data);
        if($this->db->trans_status() === FALSE)
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
    public function changeStatusMOffice($data, $id_office)
    {
        $this->db->where('idx', $id_office);
        $this->db->update('master_office', $data);
        return true;
    }
    function getOfficeName($id){
        $this->db->select('*');
        $this->db->from('master_office');
        $this->db->where('idx', $id);
        $result =  $this->db->get();
        if ($result->num_rows() > 0) {
          foreach ($result->result() as $row) {
            return $row->office_name;
          }
        }else{
          return 'NULL';
        }
    }
    function getHubName($id){
        $this->db->select('*');
        $this->db->from('master_hub');
        $this->db->where('idx', $id);
        $result =  $this->db->get();
        if ($result->num_rows() > 0) {
          foreach ($result->result() as $row) {
            return $row->hub_name;
          }
        }else{
          return 'NULL';
        }
    }
    function getCounterName($id){
        $this->db->select('*');
        $this->db->from('master_counter');
        $this->db->where('idx', $id);
        $result =  $this->db->get();
        if ($result->num_rows() > 0) {
          foreach ($result->result() as $row) {
            return $row->counter_name;
          }
        }else{
          return 'NULL';
        }
    }
    public function deleteAccessOffice()
    {
        $id = $this->input->post('id');
        $this->db->where('idx', $id);
        $result = $this->db->delete('app_oaccess');
        if($this->db->affected_rows()>0){
            return 1;
        }else{
            return $result;
        }
    }
}
?>