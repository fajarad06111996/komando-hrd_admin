<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_icon extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('ModelGlobal');
    }

    // CONTOH LAIN MODEL2 DATATABLE SERVERSIDE USER
    // start datatables
    var $column_order = array(
        null,
        'icon_name',
        'icon_class',
        'icon_class',
        null
    ); //set column field database for datatable orderable
    var $column_search = array(
        'icon_name',
        'icon_class',
    ); //set column field database for datatable searchable
    var $order = array('idx' => 'asc'); // default order 
  
    private function _get_datatables_query() {
        $this->db->select('*');
        $this->db->from('icomoon');
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
        $this->db->from('icomoon');
        return $this->db->count_all_results();
    }
    // end datatables
    // CONTOH LAIN MODEL2 DATATABLE SERVERSIDE USER END

    public function insertIcon($data, $table)
    {
        $this->db->insert($table, $data);
        if($this->db->affected_rows()>0){
            return true;
        }else{
            return false;
        }
    }

    public function getIcon($class)
    {
        $result = $this->db->get_where('icomoon', ['icon_class' => $class]);
        return $result->num_rows();
    }

    public function get()
    {
        $this->db->order_by('idx', 'desc');
        $result = $this->db->get('icomoon');
        return $result->result();
    }
}
?>
