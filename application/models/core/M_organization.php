<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_organization extends CI_Model
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
    var $select_column = array(
        'idx',
        'organization_number',
        'organization_name',
        'organization_type',
        'ending_balance',
        'status',
        'organization_segment',
        '(SELECT status_name FROM status_account_type WHERE status = master_organization.organization_type) as account_typex',
        '(SELECT employee_name FROM master_employee WHERE employee_id = master_organization.employee_id) as employee_name'
    ); //set column field database for datatable orderable
    var $column_order = array(
        'organization_number',
        'organization_name',
        'organization_type',
        'ending_balance'
    ); //set column field database for datatable orderable
    var $column_search = array(
        'organization_number',
        'organization_name',
        'organization_type',
        'ending_balance'
    ); //set column field database for datatable searchable
    var $order = array('organization_number' => 'asc'); // default order

    private function _get_datatables_query() {
        $this->db->select($this->select_column);
        $this->db->from('master_organization');
        $this->db->where(['status'=>1,'company_id'=>$this->office, 'status_delete' => 0]);
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

        $order = $this->order;
        if($_POST['order']['0']['column'] != '0') { // here order processing
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        }else if(isset($this->order)) {
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
        $this->db->from('master_organization');
        $this->db->where(['status'=>1,'company_id'=>$this->office, 'status_delete' => 0]);
        return $this->db->count_all_results();
    }
    // end datatables
    // CONTOH LAIN MODEL2 DATATABLE SERVERSIDE USER END

    public function getAccCode($account_code)
    {
        $this->db->select('*')
        ->from('master_organization')
        ->where('organization_number', $account_code);
        $hasil = $this->db->get();
        return $hasil->num_rows();
    }

    public function getCodJab()
    {
        $this->db->select('*')
        ->from('master_organization')
        ->where(['organization_segment' => 1, 'company_id' => $this->office]);
        $hasil = $this->db->get()->row_array();
        return $hasil;
    }

    public function getAccNo($account_idx)
    {
        $column = [
            'a.organization_number',
            '(CASE WHEN (SELECT MAX(organization_number_child) 
                        FROM master_organization 
                        WHERE organization_number_parent = a.organization_number_parent
                        AND organization_number_child NOT IN(99,999,9999,99999)
                        LIMIT 1
            ) IS NULL THEN 0 ELSE (SELECT MAX(organization_number_child) 
                        FROM master_organization 
                        WHERE organization_number_parent = a.organization_number_parent 
                        AND organization_number_child NOT IN(99,999,9999,99999)
                        LIMIT 1
            ) END) as part_two'
        ];
        $this->db->select($column)
        ->from('master_organization a')
        ->where('a.idx', $account_idx);
        // $hasil = $this->db->get_compiled_select();
        $hasil = $this->db->get()->row_array();
        return $hasil;
    }

    public function getAccNoNew($account_idx, $segment, $this_idx="")
    {
        if(empty($this_idx)){
            $column = [
                'a.organization_number',
                'a.idx',
                'IF(0=1,0,NULL) as this_number_parent',
                'IF(0=1,0,NULL) as this_number_child',
                "(CASE WHEN (SELECT MAX(organization_number_child) 
                            FROM master_organization 
                            WHERE parent_idx = a.idx
                            AND organization_number_child NOT IN(99,999,9999,99999) 
                            AND organization_segment = $segment
                            LIMIT 1
                ) IS NULL THEN 0 ELSE (SELECT MAX(organization_number_child) 
                            FROM master_organization 
                            WHERE parent_idx = a.idx 
                            AND organization_number_child NOT IN(99,999,9999,99999)
                            AND organization_segment = $segment
                            LIMIT 1
                ) END) as part_two"
            ];
        }else{
            $column = [
                'a.organization_number',
                'a.idx',
                "(SELECT organization_number_parent FROM master_organization WHERE idx = $this_idx) as this_number_parent",
                "(SELECT organization_number_child FROM master_organization WHERE idx = $this_idx) as this_number_child",
                "(CASE WHEN (SELECT MAX(organization_number_child) 
                            FROM master_organization 
                            WHERE parent_idx = a.idx
                            AND organization_number_child NOT IN(99,999,9999,99999) 
                            AND organization_segment = $segment
                            LIMIT 1
                ) IS NULL THEN 0 ELSE (SELECT MAX(organization_number_child) 
                            FROM master_organization 
                            WHERE parent_idx = a.idx 
                            AND organization_number_child NOT IN(99,999,9999,99999)
                            AND organization_segment = $segment
                            LIMIT 1
                ) END) as part_two"
            ];
        }
        $this->db->select($column)
        ->from('master_organization a')
        ->where('a.idx', $account_idx);
        // $hasil = $this->db->get_compiled_select();
        $hasil = $this->db->get()->row_array();
        return $hasil;
    }

    public function getAccNox($account_idx)
    {
        $column = [
            'a.organization_number',
            '(CASE WHEN (SELECT organization_number_child FROM master_organization WHERE organization_number_parent = a.organization_number_parent ORDER BY organization_number_child DESC LIMIT 1) IS NULL THEN 0 ELSE (SELECT organization_number_child FROM master_organization WHERE organization_number_parent = a.organization_number_parent ORDER BY organization_number_child DESC LIMIT 1) END) as part_two'
        ];
        $this->db->select($column)
        ->from('master_organization a')
        ->where('a.idx', $account_idx);
        $hasil = $this->db->get_compiled_select();
        // $hasil = $this->db->get()->row_array();
        return $hasil;
    }

    public function getAccNoSeg2($account_idx)
    {
        $this->db->select('MAX(organization_number_child) as organization_number_child')
        ->from('master_organization')
        ->where(['parent_idx' => $account_idx, 'organization_segment' => 2, 'company_id' => $this->office])
        ->where_not_in('organization_number_child', [99,999,9999,99999]);
        $hasil = $this->db->get()->result_array();
        return $hasil;
    }

    public function getAccNoSeg3($account_idx)
    {
        $this->db->select('organization_number')
        ->from('master_organization')
        ->where('idx', $account_idx);
        $hasil = $this->db->get();
        if($hasil->num_rows()>0){
            $hasil2 = $hasil->row_array();
            $datanya = explode('.',$hasil2['organization_number']);
            $this->db->select('part_three_organization_number')
            ->from('master_organization')
            ->where(['organization_number_parent' => $datanya[0],'organization_number_child' => $datanya[1],'organization_segment' => 3])
            ->order_by('part_three_organization_number', 'desc');
            $hasil3 = $this->db->get();
            return $hasil3->row_array();
        }else{
            return false;
        }
    }

    public function getAccNoSeg4($account_idx)
    {
        $this->db->select('organization_number')
        ->from('master_organization')
        ->where('idx', $account_idx);
        $hasil = $this->db->get();
        if($hasil->num_rows()>0){
            $hasil2 = $hasil->row_array();
            $datanya = explode('.',$hasil2['organization_number']);
            $this->db->select('part_four_organization_number')
            ->from('master_organization')
            ->where(['organization_number_parent' => $datanya[0],'part_three_organization_number' => $datanya[2],'organization_segment' => 4])
            ->order_by('part_four_organization_number', 'desc');
            $hasil3 = $this->db->get();
            return $hasil3->row_array();
        }else{
            return false;
        }
    }

    public function getTypeAcc($account_type)
    {
        $this->db->select('*')
        ->from('master_organization')
        ->where(['organization_type' => $account_type, 'organization_segment' => 1]);
        $hasil = $this->db->get();
        return $hasil->result();
    }

    public function editAccount($id)
    {
        $this->db->select('a.*,(SELECT parent_idx FROM master_organization WHERE idx = a.parent_idx) as parent_segtwo,(SELECT parent_idx FROM master_organization WHERE idx = (SELECT parent_idx FROM master_organization WHERE idx = a.parent_idx)) as parent_segthree')
        ->from('master_organization a')
        ->where('a.idx',$id);
        $hasilnya = $this->db->get();
        return $hasilnya->row_array();
    }

    public function getAccount($id)
    {
        $this->db->select('a.*, (SELECT COUNT(idx) FROM master_organization WHERE parent_idx = a.idx AND status = 1) as total_child, (SELECT COUNT(idx) FROM master_employee WHERE organization_idx = a.idx AND status = 1) as total_employee, (SELECT GROUP_CONCAT(idx) FROM master_organization WHERE parent_idx = a.idx AND status = 1) as list_child')
        ->from('master_organization a')
        ->where('a.idx',$id);
        $hasilnya = $this->db->get()->row_array();
        return $hasilnya;
    }

    public function getHierarchy($id)
    {
        $company = $this->office;
        $query = "WITH RECURSIVE OrganizationHierarchy AS (
                SELECT 
                    idx, 
                    parent_idx, 
                    parent_active,
                    organization_name,
                    organization_number,
                    organization_segment,
                    organization_number_parent,
                    organization_number_child,
                    head_name,
                    photo,
                    `status`,
                    status_head,
                    employee_id,
                    company_id
                FROM master_organization
                WHERE idx = $id
                AND `status` = 1
                AND company_id = $company
                AND status_delete = 0
            
                UNION ALL
            
                SELECT 
                    mo.idx, 
                    mo.parent_idx, 
                    mo.parent_active,
                    mo.organization_name,
                    mo.organization_number,
                    mo.organization_segment,
                    mo.organization_number_parent,
                    mo.organization_number_child,
                    mo.head_name,
                    mo.photo,
                    `mo`.`status`,
                    mo.status_head,
                    mo.employee_id,
                    mo.company_id
                FROM master_organization mo
                INNER JOIN OrganizationHierarchy oh ON mo.parent_idx = oh.idx
                AND `mo`.`status` = 1
                AND mo.company_id = $company
                AND mo.status_delete = 0
            )
            
            SELECT * FROM OrganizationHierarchy;";
        $result = $this->db->query($query)->result();
        return $result;
    }

    public function getAccNumber($id)
    {
        $this->db->select('a.organization_number, a.organization_name')
        ->from('master_organization a')
        ->where(['a.idx'=>$id, 'a.company_id' => $this->office]);
        $hasilnya = $this->db->get()->row_array();
        return $hasilnya;
    }

    public function cekSub($id)
    {
        $this->db->select('*')
        ->from('master_organization a')
        ->where('a.parent_idx', $id);
        return $this->db->get();
    }

    public function cekPChild($id)
    {
        $sql = "SELECT *
        FROM master_organization
        WHERE parent_idx = (SELECT parent_idx FROM master_organization WHERE idx = $id)";
        $result = $this->db->query($sql);
        return $result;
    }

    public function deleteUpdateAccount($idDelete, $idUpdate1 = '', $dataUpdate1 = '', $idUpdate2 = '', $dataUpdate2 = '', $idUpdate3 = '', $dataUpdate3 = '', $idUpdate4 = '', $dataUpdate4 = '')
    {
        $this->db->trans_begin();
        if(!empty($dataUpdate1) && !empty($idUpdate1)){
            $this->db->where('idx',$idUpdate1);
            $this->db->update('master_organization', $dataUpdate1);
        }
        if(!empty($dataUpdate2) && !empty($idUpdate2)){
            $this->db->where('idx',$idUpdate2);
            $this->db->update('master_organization', $dataUpdate2);
        }
        if(!empty($dataUpdate3) && !empty($idUpdate3)){
            $this->db->where('idx',$idUpdate3);
            $this->db->update('master_organization', $dataUpdate3);
        }
        if(!empty($dataUpdate4) && !empty($idUpdate4)){
            $this->db->where('idx',$idUpdate4);
            $this->db->update('master_organization', $dataUpdate4);
        }
        $this->db->where('idx',$idDelete);
        $this->db->delete('master_organization');
        if($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            return 0;
        }else{
            $this->db->trans_commit();
            return 1;
        }
    }

    public function deleteOrganization($id)
    {
        $this->db->trans_begin();
        $this->db->where('idx',$id);
        $this->db->update('master_organization', ['status_delete' => 1, 'modified_by'=> $this->idx, 'modified_on' => date('Y-m-d H:i:s')]);
        if($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            return 0;
        }else{
            $this->db->trans_commit();
            return 1;
        }
    }

    public function updatedAuto($data, $where, $updateAccount="")
    {
        $this->db->trans_begin();
        $this->db->where($where);
        $this->db->update('master_organization', $data);
        if(!empty($updateAccount)){
            $this->db->query($updateAccount);
        }
        if($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            return 0;
        }else{
            $this->db->trans_commit();
            return 1;
        }
    }

    public function updatedParent($data, $id, $updateParentAmount, $parent_idx)
    {
        $this->db->trans_begin();
        $this->db->where('idx',$id);
        $this->db->update('master_organization', $data);
        $this->db->where('idx',$parent_idx);
        $this->db->update('master_organization', $updateParentAmount);
        if($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            return 0;
        }else{
            $this->db->trans_commit();
            return 1;
        }
    }

    public function updatedGrandParent($data, $id, $updateParentAmount, $parent_idx, $updateGrandParentAmount, $grandparent_idx)
    {
        $this->db->trans_begin();
        $this->db->where('idx',$id);
        $this->db->update('master_organization', $data);
        $this->db->where('idx',$parent_idx);
        $this->db->update('master_organization', $updateParentAmount);
        $this->db->where('idx',$grandparent_idx);
        $this->db->update('master_organization', $updateGrandParentAmount);
        if($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            return 0;
        }else{
            $this->db->trans_commit();
            return 1;
        }
    }

    public function updatedGrandParentSub($data, $id, $updateParentAmount, $parent_idx, $updateGrandParentAmount, $grandparent_idx, $updateGrandParentSubAmount, $grandparentsub_idx)
    {
        $this->db->trans_begin();
        $this->db->where('idx',$id);
        $this->db->update('master_organization', $data);
        $this->db->where('idx',$parent_idx);
        $this->db->update('master_organization', $updateParentAmount);
        $this->db->where('idx',$grandparent_idx);
        $this->db->update('master_organization', $updateGrandParentAmount);
        $this->db->where('idx',$grandparentsub_idx);
        $this->db->update('master_organization', $updateGrandParentSubAmount);
        if($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            return 0;
        }else{
            $this->db->trans_commit();
            return 1;
        }
    }

    public function cekSegment($idx)
    {
        $sql = "SELECT organization_segment
        FROM master_organization
        WHERE idx = $idx";
        $result = $this->db->query($sql);
        $tot_amount = $result->row_array();
        return $tot_amount['organization_segment'];
    }
    
    public function getAccountType($idx)
    {
        $sql = "SELECT organization_segment,
            (SELECT status_transaction FROM status_account_type WHERE status = master_organization.organization_type) as status_transaction
        FROM master_organization
        WHERE idx = $idx";
        $result = $this->db->query($sql);
        return $result->row_array();
    }

    public function cekNomorAkun($organization_number, $organization_segment)
    {
        $this->db->select('*')
        ->from('master_organization')
        ->where(['organization_number' => $organization_number, 'organization_segment' => $organization_segment, 'company_id' => $this->office]);
        $result = $this->db->get()->row_array();
        return $result;
    }

    public function cekNumeringCount($parent_idx, $organization_segment)
    {
        $this->db->select('MAX(idx) as idx, MAX(organization_number) as organization_number, MAX(organization_number_parent) as organization_number_parent, MAX(organization_number_child) as organization_number_child')
        ->from('master_organization')
        ->where(['parent_idx' => $parent_idx, 'organization_segment' => $organization_segment, 'company_id' => $this->office]);
        $result = $this->db->get_compiled_select();
        // $result = $this->db->get()->row_array();
        return $result;
    }
}
?>