<?php 
defined('BASEPATH') or exit('No direct script access allowed');

class M_bank extends CI_Model
{
    // CONTOH LAIN MODEL2 DATATABLE SERVERSIDE USER
    // start datatables
    private function _get_datatables_query() {
        $select_column = array(
            'a.*'
        );//set column field database for datatable select
        $column_order = array(
            null
        ); //set column field database for datatable orderable
        $column_search = array(
            'a.bank_code',
            'a.bank_name',
            'a.remark'
        ); //set column field database for datatable searchable
        $order = array('a.idx' => 'asc'); // default order 
        $this->db->select($select_column);
        $this->db->from('master_bank a');
        // $this->db->where(['a.status'=> 1]);
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
        $this->db->from('master_bank a');
        // $this->db->where(['a.status'=> 1]);
        return $this->db->count_all_results();
    }
    // end datatables
    // CONTOH LAIN MODEL2 DATATABLE SERVERSIDE USER END

    public function insertBank($data)
    {
        $this->db->trans_begin();
        $this->db->insert('master_bank', $data);
        if($this->db->trans_status() === FALSE)
    	{
    		$this->db->trans_rollback();
    		return false;
    	}else{
    		$this->db->trans_commit();
    		return true;
    	}
    }

    public function listMProduct()
    {
        $dataMProduct = $this->db->query("select * from master_product order by idx desc")->result_array();
        return $dataMProduct;
    }

    public function cekBankCode($bank_code)
    {
        $this->db->select('*')
        ->from('master_bank')
        ->where('bank_code', $bank_code);
        $hasil = $this->db->get()->num_rows();
        return $hasil;
    }

    public function cekBankCodeUpdate($idx, $bank_code)
    {
        $this->db->select('*')
        ->from('master_bank')
        ->where(['bank_code' => $bank_code, 'idx <> '=>$idx]);
        $hasil = $this->db->get()->num_rows();
        return $hasil;
    }

    public function getMProduct($product_code)
    {
        $this->db->select('*')
        ->from('master_product')
        ->where(['product_code' => $product_code, 'office_idx' => $this->office]);
        $hasil = $this->db->get();
        return $hasil->row_array();
    }

    public function cekProductCode($product_code)
    {
        $this->db->select('*')
        ->from('master_product')
        ->where(['product_code'=> $product_code, 'office_idx' => $this->office]);
        $hasil = $this->db->get();
        if($hasil->num_rows()>0){
            return false;
        }else{
            return true;
        }
    }

    public function editBank($id)
    {
        $this->db->select('*')
        ->from('master_bank')
        ->where('idx',$id);
        $hasilnya = $this->db->get();
        return $hasilnya->row_array();
    }

    public function updateBank($data, $id)
    {
        $this->db->trans_begin();
        $this->db->where('idx', $id);
        $this->db->update('master_bank', $data);
        if($this->db->trans_status() === FALSE)
    	{
    		$this->db->trans_rollback();
    		return false;
    	}else{
    		$this->db->trans_commit();
    		return true;
    	}
    }

    public function deleteProduct($id)
    {
        $this->db->trans_begin();
        $this->db->where('idx', $id);
        $this->db->update('master_product', ['status_delete' => 'Y']);
        if($this->db->trans_status() === FALSE)
    	{
    		$this->db->trans_rollback();
    		return false;
    	}else{
    		$this->db->trans_commit();
    		return true;
    	}
    }

    public function changeStatusBank($data, $idx)
    {
        $this->db->trans_begin();
        $this->db->where('idx', $idx);
        $this->db->update('master_bank', $data);
        if($this->db->trans_status() === FALSE)
    	{
    		$this->db->trans_rollback();
    		return false;
    	}else{
    		$this->db->trans_commit();
    		return true;
    	}
    }

    
}
?>