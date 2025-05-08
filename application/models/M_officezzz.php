<?php 
defined('BASEPATH') or exit('No direct script access allowed');

class M_officezzz extends CI_Model
{
    // CONTOH LAIN MODEL2 DATATABLE SERVERSIDE USER
    // start datatables
    var $select_column4 = array(
        'a.idx',
        'a.location_code',
        'a.location_name',
        'a.postal_code',
        'a.office_idx',
        'a.office_main_location',
        'a.status_origin',
        'a.status_destination',
        'a.status',
        'a.created_by',
        'a.created_on',
        'a.modified_by',
        'a.modified_on',
        'IF(a.office_main_location = 1, "YES", "NO") as main_location',
        'IF(a.status_origin = 1, "YES", "NO") as origin_name',
        'IF(a.status_destination = 1, "YES", "NO") as destination_name',
        'b.office_name'
        
    );//set column field database for datatable select
    var $column_order = array(
        null,
        'a.office_code',
        'a.office_name',
        null,
        null,
        null,
        'a.address',
        'a.city',
        'a.postal_code',
        'a.state_code',
        'a.province',
        'a.country',
        'a.attention',
        'a.email_id',
        'a.telephone',
        'a.fax',
        'a.tax_id',
        'a.status',
        null,
        null,
        'a.created_by',
        'a.created_on',
        'a.modified_by',
        'a.modified_on'
    ); //set column field database for datatable orderable
    var $column_search = array(
        'a.office_code',
        'a.office_name',
        'a.address',
        'a.city',
        'a.postal_code',
        'a.state_code',
        'a.province',
        'a.country',
        'a.attention',
        'a.email_id',
        'a.telephone',
        'a.fax',
        'a.tax_id',
        'a.status'
    ); //set column field database for datatable searchable
    var $order = array('a.office_name' => 'asc'); // default order 
  
    private function _get_datatables_query() {
    $this->db->select('*');
    $this->db->from('master_office a');
    $i = 0;
    foreach ($this->column_search as $item) { // loop column 
        if(@$_POST['search']['value']) { // if datatable send POST for search
            if($i===0) { // first loop
                $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                $this->db->like($item, $_POST['search']['value']);
            } else {
                $this->db->or_like($item, $_POST['search']['value']);
            }
            if(count($this->column_search) - 1 == $i) //last loop
                $this->db->group_end(); //close bracket
        }
        $i++;
    }
        
    if(isset($_POST['order'])) { // here order processing
        $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
    }  else if(isset($this->order)) {
        $order = $this->order;
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
        $this->db->from('master_office a');
        return $this->db->count_all_results();
    }
    // end datatables
    // CONTOH LAIN MODEL2 DATATABLE SERVERSIDE USER END

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
    
    public function searchHub($idx)
    {
        $result = $this->db->query("select * from master_hub where idx = '$idx'")->row_array();
        return $result;
    }

    public function searchCounter($idx)
    {
        $result = $this->db->query("select * from master_counter where idx = '$idx'")->row_array();
        return $result;
    }
    
    public function getMOffice($office_code)
    {
        $result = $this->db->query("select idx from master_office where office_code='$office_code'")->row_array();
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
}
?>