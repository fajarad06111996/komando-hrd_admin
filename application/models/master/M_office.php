<?php 
defined('BASEPATH') or exit('No direct script access allowed');

class M_office extends CI_Model
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
            'a.*',
            '(SELECT status_name FROM status_office_type WHERE status = a.office_type) as office_typex'
            
        );//set column field database for datatable select
        $column_order = array(
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
        $column_search = array(
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
        $order = array('a.idx' => 'asc'); // default order
        $this->db->select($select_column);
        $this->db->from('master_office a');
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
        $this->db->from('master_office a');
        $this->db->where('a.company_idx', $this->office);
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
    public function checkOfficeCode($office_code, $company)
    {
        $result = $this->db->get_where('master_office', ['office_code' => $office_code, 'company_idx' => $company])->num_rows();
        return $result;
    }
    
    public function searchHub($idx)
    {
        $result = $this->db->query("select * from master_hub where idx = '$idx'")->row_array();
        return $result;
    }
    
    public function getMOffice($office_code)
    {
        $result = $this->db->query("select idx from master_office where office_code='$office_code'")->row_array();
        return $result;
    }

    public function getOffice($idx)
    {
        $column = [
            'a.*',
            '(SELECT company_name FROM master_company WHERE idx = a.company_idx) as company_name'
        ];
        $this->db->select($column)
        ->from('master_office a')
        ->where('a.idx', $idx);
        $result = $this->db->get()->row_array();
        return $result;
    }

    public function getOfficeType()
    {
        $result = $this->db->get('status_office_type')->result();
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

    public function createAccountMOffice($data, $dataAccount, $idx)
    {
        $this->db->trans_begin();
        $this->db->insert_batch('master_account', $dataAccount);
        $this->db->where('idx', $idx);
        $this->db->update('master_office', $data);
        if ($this->db->trans_status() === FALSE)
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

    public function createPuAccountMOffice($data, $dataAccount, $idx)
    {
        $this->db->trans_begin();
        $this->db->insert('master_account', $dataAccount);
        $this->db->where('idx', $idx);
        $this->db->update('master_office', $data);
        if ($this->db->trans_status() === FALSE)
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

    public function getAccNoSeg2($account_idx)
    {
        $this->db->select('account_number')
        ->from('master_account')
        ->where('idx', $account_idx);
        $hasil = $this->db->get();
        if($hasil->num_rows()>0){
            $hasil2 = $hasil->row_array();
            $this->db->select('part_two_account_number')
            ->from('master_account')
            ->where(['part_one_account_number' => $hasil2['account_number'],'account_segment' => 2])
            ->order_by('part_two_account_number', 'desc');
            $hasil3 = $this->db->get();
            if($hasil3->num_rows()>0){
                return $hasil3->row_array();
            }else{
                $data = [
                    'part_two_account_number' => '000'
                ];
                return $data;
            }
        }else{
            return false;
        }
    }

    public function getAccNoSeg2x($account_number)
    {
        $acc_num = explode(".",$account_number);
        $column = [
            'IFNULL(a.part_two_account_number, 000) as part_two_account_number',
            'a.part_one_account_number',
            'a.account_type',
            'a.parent_idx'
        ];
        $this->db->select($column)
        ->from('master_account a')
        ->where(['a.part_one_account_number' => $account_number, 'a.account_segment' => 2])
        ->order_by('a.part_two_account_number', 'desc');
        $hasil = $this->db->get()->row_array();
        return $hasil;
    }

    public function getAccNoSeg3($account_number)
    {
        $acc_num = explode(".",$account_number);
        if(count($acc_num)==2){
            $column = [
                'IFNULL(a.part_three_account_number, 000) as part_three_account_number',
                'a.part_two_account_number',
                'a.part_one_account_number',
                'a.account_type',
                'a.parent_idx'
            ];
            $this->db->select($column)
            ->from('master_account a')
            ->where(['a.part_one_account_number' => $acc_num[0], 'a.part_two_account_number' => $acc_num[1], 'a.account_segment' => 3])
            ->order_by('a.part_three_account_number', 'desc');
            $hasil = $this->db->get()->row_array();
            return $hasil;
        }else{
            return NULL;
        }
    }

    public function getCompany()
    {
        $column = [
            '*'
        ];
        $this->db->select($column)
        ->from('master_company')
        ->where(['status'=> 1]);
        $query = $this->db->get()->result();
        return $query;
    }

    public function getOriginx()
    {
        $column = [
            'a.idx',
            'a.location_code',
            'a.location_name',
            'a.district_name',
            'a.city_name',
            'a.province_name',
            'a.postal_code'
        ];
        $this->db->select($column)
        ->from('master_location a')
        ->where(['a.status'=> 1,'a.status_origin'=>1]);
        $query = $this->db->get()->result();
        return $query;
    }
    
}
?>