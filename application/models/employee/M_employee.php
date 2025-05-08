<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_employee extends CI_Model 
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
            '(SELECT organization_name FROM master_organization WHERE idx = a.organization_idx) as organization_name',
            '(SELECT department_name FROM master_department WHERE idx = a.department_idx) as department_name',
            '(SELECT designation_name FROM master_designation WHERE idx = a.designation_idx) as designation_name',
            '(SELECT status_name FROM status_gender WHERE status = a.gender) as genderx',
            '(SELECT status_name FROM status_payslip WHERE status = a.payslip_type) as payslip_typex',
            '(SELECT shift_name FROM master_shift WHERE idx = a.office_shift) as shift'
        );//set column field database for datatable select
        $column_order = array(
            null,
            null,
            null,
            null,
            null,
        ); //set column field database for datatable orderable
        $column_search = array(
            'a.username',
            'a.employee_name'
        ); //set column field database for datatable searchable
        $order = array('a.idx' => 'desc'); // default order
        $this->db->select($select_column);
        $this->db->from('master_employee a');
        $this->db->where(['a.company_idx' => $this->office, 'a.status_delete' => 0]);
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
    function get_datatables() {
        $this->_get_datatables_query();
        if(@$_POST['length'] != -1)
        $this->db->limit(@$_POST['length'], @$_POST['start']);
        // $query = $this->db->get_compiled_select();
        $query = $this->db->get()->result();
        return $query;
    }
    function count_filtered() {
        $this->_get_datatables_query();
        $query = $this->db->get()->num_rows();
        return $query;
    }
    function count_all() {
        $this->db->from('master_employee a');
        $this->db->where(['a.company_idx' => $this->office, 'a.status_delete' => 0]);
        return $this->db->count_all_results();
    }
    // end datatables
    // CONTOH LAIN MODEL2 DATATABLE SERVERSIDE USER END

    // REKENING AJAX
    // start datatables

    private function _get_rekening_query($emp_id) {
        $select_column = array(
            'a.*',
            '(SELECT bank_name FROM master_bank WHERE idx = a.bank_id) as bank_name'
        );//set column field database for datatable select
        $column_order = array(
            null,
            null,
            null,
            null,
            null
        ); //set column field database for datatable orderable
        $column_search = array(
            null,
            null,
            null,
            null
        ); //set column field database for datatable searchable
        $order = array('a.idx' => 'asc'); // default order
        $this->db->select($select_column);
        $this->db->from('bank_account a');
        $this->db->where(['a.employee_id'=> $emp_id]);
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

    function get_rekening($emp_id) {
        $this->_get_rekening_query($emp_id);
        if(@$_POST['length'] != -1)
        $this->db->limit(@$_POST['length'], @$_POST['start']);
        $query = $this->db->get()->result();
        return $query;
    }

    function count_filtered_rekening($emp_id) {
        $this->_get_rekening_query($emp_id);
        $query = $this->db->get()->num_rows();
        return $query;
    }

    function count_all_rekening($emp_id) {
        $this->db->from('bank_account a');
        $this->db->where(['a.employee_id'=> $emp_id]);
        return $this->db->count_all_results();
    }
    // end datatables
    // COMODITY AJAX END

    public function getEmployee($id)
    {
        $this->db->select('*')
        ->from('master_employee')
        ->where('idx', $id);
        $hasilnya = $this->db->get()->row_array();
        return $hasilnya;
    }

    public function getRekening($id)
    {
        $this->db->select('*')
        ->from('bank_account')
        ->where('employee_id', $id);
        $hasilnya = $this->db->get()->row_array();
        return $hasilnya;
    }

    public function getDataRekening($idx)
    {
        $this->db->select('*')
        ->from('bank_account')
        ->where('idx', $idx);
        $hasilnya = $this->db->get()->row_array();
        return $hasilnya;
    }

    public function getBank($search, $type)
    {
        $this->db->select('*')
        ->from('master_bank')
        ->like('bank_name', $search);
        if($type == 'data'){
            return $this->db->get()->result();
        }else{
            return $this->db->get()->num_rows();
        }
    }
    public function getBankId($idx, $type)
    {
        $this->db->select('*')
        ->from('master_bank')
        ->where('idx', $idx);
        if($type == 'data'){
            return $this->db->get()->result_array();
        }else{
            return $this->db->get()->num_rows();
        }
    }

    public function statusNPWP()
    {
        $this->db->select('*')
        ->from('status_npwp');
        return $this->db->get()->result();
    }
    public function statusPendidikan()
    {
        $this->db->select('*')
        ->from('status_pendidikan');
        return $this->db->get()->result();
    }
    public function statusBlood()
    {
        $this->db->select('*')
        ->from('status_blood');
        return $this->db->get()->result();
    }
    public function statusPegawai()
    {
        $this->db->select('*')
        ->from('status_pegawai');
        return $this->db->get()->result();
    }

    public function getDokumen($empId)
    {
        $this->db->select('*')
        ->from('employee_attachment')
        ->where(['employee_id' => $empId, 'status' => 1]);
        $hasilnya = $this->db->get()->result();
        return $hasilnya;
    }

    public function getDepartment()
    {
        $this->db->select('*')
        ->from('master_department');
        $hasilnya = $this->db->get()->result();
        return $hasilnya;
    }

    public function getOrganization()
    {
        $this->db->select('*')
        ->from('master_organization')
        ->where(['status' => 1, 'company_id' => $this->office, 'status_delete' => 0]);
        $hasilnya = $this->db->get()->result();
        return $hasilnya;
    }

    public function getOffice()
    {
        $this->db->select('*')
        ->from('master_office')
        ->where('company_idx', $this->office);
        $hasilnya = $this->db->get()->result();
        return $hasilnya;
    }

    public function getOfficeByCompany($company_idx)
    {
        $this->db->select('a.company_name')
        ->from('master_company a')
        ->where('a.idx', $company_idx);
        $hasilnya = $this->db->get()->row_array();
        if(empty($hasilnya)){
            return $data = [
                'company_name' => NULL,
                'data_office' => array()
            ];
        }else{
            $this->db->select('a.*')
            ->from('master_office a')
            ->where('a.company_idx', $company_idx);
            $hasilnya2 = $this->db->get()->row_array();
            if(empty($hasilnya2)){
                return $data = [
                    'company_name' => $hasilnya['company_name'],
                    'data_office' => array()
                ];
            }else{
                return $data = [
                    'company_name' => $hasilnya['company_name'],
                    'data_office' => $hasilnya2
                ];
            }
        }
    }

    public function getCompany()
    {
        $this->db->select('*')
        ->from('master_company')
        ->where('status', 1);
        $hasilnya = $this->db->get()->result();
        return $hasilnya;
    }
    
    public function getDesignation()
    {
        $this->db->select('*')
        ->from('master_designation');
        $hasilnya = $this->db->get()->result();
        return $hasilnya;
    }

    public function getShift()
    {
        $this->db->select('*')
        ->from('master_shift');
        $hasilnya = $this->db->get()->result();
        return $hasilnya;
    }

    public function getMasterOt()
    {
        $this->db->select('*')
        ->from('master_ot');
        $hasilnya = $this->db->get()->result();
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

    public function insertEmployee($data, $insertBank)
    {
        $this->db->trans_begin();
        $this->db->insert('master_employee', $data);
        $this->db->insert_batch('bank_account', $insertBank);
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

    public function insertDocEmployee($data, $dataUpdate="")
    {
        $this->db->trans_begin();
        $this->db->insert_batch('employee_attachment', $data);
        if(!empty($dataUpdate)){
            $this->db->query($dataUpdate);
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

    public function updateDocEmployee($dataUpdate)
    {
        $this->db->trans_begin();
        $this->db->query($dataUpdate);
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

    public function deleteDocEmployee($dataUpdate, $id)
    {
        $this->db->trans_begin();
        $this->db->where('idx', $id);
        $this->db->update('employee_attachment', $dataUpdate);
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

    public function updateEmployee($id, $data, $employee_id, $updateAtt="")
    {
        $this->db->trans_begin();
        $this->db->where('idx', $id);
        $this->db->update('master_employee', $data);
        if(!empty($updateAtt)){
            $this->db->where('employee_id', $employee_id);
            $this->db->update('attendance_employee', $updateAtt);
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

    public function deleteEmployee($id)
    {
        $this->db->trans_begin();
        $this->db->where('idx', $id);
        $this->db->update('master_employee', ['status_delete'=>1]);
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

    public function changeStatus($data, $idx)
    {
        $this->db->trans_begin();
        $this->db->where('idx', $idx);
        $this->db->update('master_employee', $data);
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

    public function insertRekening($data)
    {
        $this->db->trans_begin();
        $this->db->insert('bank_account', $data);
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            return 0;
        } else {
            $this->db->trans_commit();
            return 1;
        }
    }

    public function updateRekening($data, $idx)
    {
        $this->db->trans_begin();
        $this->db->where('idx', $idx);
        $this->db->update('bank_account', $data);
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            return 0;
        } else {
            $this->db->trans_commit();
            return 1;
        }
    }

    public function createAsUser($data, $id_employee)
    {
        // $pass = password_hash('123456', PASSWORD_DEFAULT);
        $query = "insert into user_account (
                user_id,
                user_name,
                user_type,
                photo,
                address,
                city,
                postal_code,
                province,
                country,
                email_id,
                mobile_phone,
                telephone,
                employee_id,
                company_idx,
                status,
                status_password,
                status_delete,
                created_by,
                created_on)
            select
                employee_code,
                employee_name,
                2,
                photo,
                address,
                city,
                postal_code,
                province,
                country,
                email_id,
                mobile_phone,
                telephone,
                employee_id,
                ".$this->office.",
                1,
                0,
                'N',
                ".$this->idx.",
                '".date("Y-m-d H:i:s")."'
            from master_employee
            where idx=$id_employee";
        $this->db->trans_begin();
        $this->db->query($query);
        $this->db->where('idx', $id_employee);
        $this->db->update('master_employee', $data);
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            return false;
        }
        else
        {
            $this->db->trans_commit();
            return true;
        }
    }
}

/* End of file M_employee.php */
/* Location: ./application/models/core/M_employee.php */