<?php
defined('BASEPATH') or exit('No direct script access allowed');
class M_access extends CI_Model
{
    protected $office;
    protected $hub;
    protected $idx;
    protected $username;
    protected $level;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('ModelGlobal');
        $this->load->library('session');
        $this->load->library('Secure');
        $this->office   = $this->secure->dec($this->session->userdata('JToffice_id'));
        $this->hub      = $this->secure->dec($this->session->userdata('JThub_id'));
        $this->idx      = $this->secure->dec($this->session->userdata('JTidx'));
        $this->username = $this->session->userdata('JTuser_id');
        $this->level    = $this->secure->dec($this->session->userdata('JTlevel'));
    }

    function getData($table, $order = '', $by = '', $limit = '', $where = '', $group_by ='')
    {
        if (!empty($order)) {
            $this->db->order_by($order, $by);
        }
        if (!empty($limit)) {
            $this->db->limit($limit);
        }
        if (!empty($where)) {
            $this->db->where($where);
        }
        if(!empty($group_by)){
            $this->db->group_by($group_by);
        }
        return $this->db->get($table);
    }
    function getDataOr($table, $order = '', $by = '', $limit = '', $where = '', $orWhere = '', $group_by ='')
    {
        if (!empty($order)) {
            $this->db->order_by($order, $by);
        }
        if (!empty($limit)) {
            $this->db->limit($limit);
        }
        if (!empty($where)) {
            $this->db->where($where);
        }
        if (!empty($orWhere)) {
            $this->db->or_where($orWhere);
        }
        if(!empty($group_by)){
            $this->db->group_by($group_by);
        }
        return $this->db->get($table);
    }
    function getTable($table="", $where="")
    {
        return $this->db->get_where($table, $where);
    }
    function getWhereIn($table="",$where="",$aray="")
    {
        $this->db->where_in($where,$aray);
        return $this->db->get($table);
    }
    function getWhereInAndWhere($table="",$wherein="",$aray="",$where="")
    {
        if($wherein){
            $this->db->where_in($wherein,$aray);
        }
        if($where){
            $this->db->where($where);
        }
        return $this->db->get($table);
    }
    function getWhereNotInAndWhere($table="",$whereIn="",$arayIn="",$whereNotIn="",$arayNotIn="",$where="",$orderBy="",$orderType="")
    {
        if($whereIn){
            $this->db->where_in($whereIn,$arayIn);
        }
        if($whereNotIn){
            $this->db->where_not_in($whereNotIn,$arayNotIn);
        }
        if($where){
            $this->db->where($where);
        }
        if($orderBy){
            $this->db->order_by($orderBy,$orderType);
        }
        return $this->db->get($table);
    }
    function getWhereNotInAndWhere2($table="",$whereIn="",$arayIn="",$whereNotIn="",$arayNotIn="",$where="",$sort="")
    {
        if($whereIn){
            $this->db->where_in($whereIn,$arayIn);
        }
        if($whereNotIn){
            $this->db->where_not_in($whereNotIn,$arayNotIn);
        }
        if($where){
            $this->db->where($where);
        }
        if(is_array($sort) AND !empty($sort)){
            foreach($sort as $keys => $datas){
                $this->db->order_by($keys, $datas);
            }
        }
        return $this->db->get($table);
    }
    public function getCompany()
    {
        $column = [
            '*'
        ];
        $this->db->select()
        ->from('master_company')
        ->where(['idx' => $this->office, 'status' => 1]);
        $result = $this->db->get()->row_array();
        return $result;
    }
    public function getCompanyList()
    {
        $column = [
            '*'
        ];
        $this->db->select()
        ->from('master_company')
        ->where(['status' => 1]);
        $result = $this->db->get()->result();
        return $result;
    }
    function getMenuParent($number,$offset,$keyword = null)
    {
        $dbs = $this->load->database('dbdata', TRUE);
        $where = array(
            'menu_parent_id =' => NULL,
            // 'menu_parent_sub =' => 1
        );
        $this->db->select('*');
        $this->db->from('app_menu');
        $this->db->where('menu_parent_sub', '1');
        $this->db->like('menu_title', $keyword);
        $this->db->order_by('menu_urutan', 'ASC');
        $this->db->limit($number,$offset);
        $query = $this->db->get();
        return $query->result();
    }
    function getMenuParent2($username)
    {
        $where = array(
            'a.menu_parent_sub' => 1
            // 'menu_parent_sub =' => 1
        );
        $this->db->select('*');
        $this->db->from('app_menu a');
        $this->db->join('app_uaccess b', 'a.menu_id = b.access_menu_id', 'left');
        $this->db->where('a.menu_parent_sub', 1);
        $this->db->where('b.access_username', $username);
        $this->db->order_by('b.access_menu_urutan', 'ASC');
        $query = $this->db->get();
        return $query->result();
    }
    function getMenuChild($number,$offset,$keyword = null)
    {
        $dbs = $this->load->database('dbdata', TRUE);
        $this->db->select('*');
        $this->db->from('app_menu');
        $this->db->where('menu_parent_sub', '1');
        $this->db->like('menu_title', $keyword);
        $this->db->or_like('menu_alias', $keyword);
        $this->db->order_by('menu_urutan', 'ASC');
        $this->db->limit($number,$offset);
        $query = $this->db->get();
        return $query->result();
    }
    function countTable($keyword=null)
    {
        $dbs = $this->load->database('dbdata', TRUE);
        $where = array(
            'menu_parent_id =' => NULL,
            'menu_parent_sub =' => 1
        );
        $this->db->select('*')
        ->from('app_menu')
        ->where($where)
        ->like('menu_title', $keyword)
        ->order_by('menu_title', 'ASC');
        return $this->db->get()->num_rows();
    }
    function countMenuChild($keyword=null)
    {
        $dbs = $this->load->database('dbdata', TRUE);
        $this->db->select('*')
        ->from('app_menu')
        ->where('menu_parent_sub', 1)
        ->like('menu_title', $keyword)
        ->or_like('menu_alias', $keyword)
        ->order_by('menu_urutan', 'ASC');
        $query = $this->db->get();
        return $query->num_rows();
    }
    function insertData($data, $table)
    {
        $this->db->trans_begin();
        $this->db->insert($table, $data);
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }
    function updateData($where, $data, $table)
    {
        $this->db->trans_begin();
        $this->db->where($where);
        $this->db->update($table, $data);
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }
    public function getFromDatabase($id,$table,$field){
        $this->db->select('*');
        $this->db->from($table);
        $this->db->where($field, $id);
        $query = $this->db->get();
        // $query = $this->db->get('app_user');
        if($query->num_rows() > 0){
            return $query->row();
        }else{
            return false;
        }
    }
    function checkResponse($uname,$table,$field)
    {
        $this->db->where($field, $uname);
        $query = $this->db->get($table);
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    function checkResponses($table,$where)
    {
        $this->db->where($where);
        $query = $this->db->get($table);
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    function deleteToDatabase($id,$table,$field)
    {
        $this->db->trans_begin();
        $this->db->where($field, $id);
        $this->db->delete($table);
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }
    function deleteToDatabases($table,$where)
    {
        $this->db->trans_begin();
        $this->db->where($where);
        $this->db->delete($table);
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }
    function deleteData($where, $table)
    {
        $this->db->trans_begin();
        $this->db->where($where);
        $this->db->delete($table);
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }
    function readtable($tablename = '', $select='', $where = '', $join = '', $limit = '',$sort = '',$join_model = '',$group_by ='')
    {
        if(!empty($where)){
            $this->db->where($where);
        }
        if(!empty($group_by)){
            $this->db->group_by($group_by);
        }
        if(is_array($join) AND !empty($join)){
            foreach($join as $key => $data){
                $this->db->join($key, $data,$join_model);
            }
        }
        if(is_array($limit) AND !empty($limit)){
            $this->db->limit($limit[0], $limit[1]);
        }
        if(!empty($select)){
            $this->db->select($select);
        }
        if(is_array($sort) AND !empty($sort)){
            foreach($sort as $keys => $datas){
                $this->db->order_by($keys, $datas);
            }
        }
        $query = $this->db->get($tablename);
        return $query;
    }
    function inserttable($tablename = '', $data = '')
    {
        $this->db->insert($tablename, $data);
        return $this->db->insert_id();
    }
    function updatetable($tablename = '', $data = '', $where='')
    {
        $this->db->where($where);
        return $this->db->update($tablename, $data);
    }
    function deletetable($tablename = '',$where='')
    {
        return $this->db->delete($tablename, $where);
    }
    function insertMulti($tablename = '', $dataSet=''){
        $this->db->insert_batch($tablename, $dataSet);
        return $this->db->insert_id();
    }
    function insertMultiData($data, $table, $data2='', $table2='', $data3='', $table3='', $data4='', $table4='')
    {
        $this->db->trans_begin();
        $this->db->insert($table, $data);
        if(!empty($data2)){
            $this->db->insert($table2, $data2);
        }
        if(!empty($data3)){
            $this->db->insert($table3, $data3);
        }
        if(!empty($data4)){
            $this->db->insert($table4, $data4);
        }
        if($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            return 0;
        }else{
            $this->db->trans_commit();
            return 1;
        }
        // $this->db->insert($table, $data);
    }
    function insertMultiUpdate($dataInsert, $tableInsert, $dataUpdate1, $tableUpdate1, $arrayWhere1, $dataUpdate2 = "", $tableUpdate2 = "", $arrayWhere2 = "", $dataUpdate3 = "", $tableUpdate3 = "", $arrayWhere3 = "", $dataUpdate4 = "", $tableUpdate4 = "", $arrayWhere4 = "")
    {
        $this->db->trans_begin();
        $this->db->insert($tableInsert, $dataInsert);
        $this->db->where($arrayWhere1);
        $this->db->update($tableUpdate1, $dataUpdate1);
        if(!empty($dataUpdate2)){
            $this->db->where($arrayWhere2);
            $this->db->update($tableUpdate2, $dataUpdate2);
        }
        if(!empty($dataUpdate3)){
            $this->db->where($arrayWhere3);
            $this->db->update($tableUpdate3, $dataUpdate3);
        }
        if(!empty($dataUpdate4)){
            $this->db->where($arrayWhere4);
            $this->db->update($tableUpdate4, $dataUpdate4);
        }
        if($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            return 0;
        }else{
            $this->db->trans_commit();
            return 1;
        }
        // $this->db->insert($table, $data);
    }
    public function getLastId($field='',$table='',$orderfield='')
    {
        $this->db->select($field);
        $this->db->from($table);
        $this->db->order_by($orderfield, 'DESC');
        $query = $this->db->get();
        return $query->row();
    }
    public function getDataOrder($job_id)
    {
        $this->db->select('
            a.*,
            '.$this->ModelGlobal->selectDateFormat('job_date') . ' as job_date,
            CAST(a.job_date AS TIME) as job_timex,
            CAST(a.pickup_time AS TIME) as pickup_timex,
            (SELECT client_id FROM master_client WHERE idx = a.client_id) as client_id,
            (SELECT client_code FROM master_client WHERE idx = a.client_id) as client_code,
            (SELECT client_name FROM master_client WHERE idx = a.client_id) as client_name,
            (SELECT payment_mode FROM status_payment_mode WHERE status = a.payment_mode) as payment_mode,
            (SELECT status_name FROM status_payment_mode WHERE status = a.payment_mode) as payment_name,
            (SELECT product_name FROM master_product WHERE idx = a.product_idx) as product_name,
            (SELECT driver_name FROM master_driver WHERE driver_id = a.driver_id) as driver_name,
            (SELECT status_name FROM status_shipment_type WHERE status = a.shipment_type) as shipment_type,
            (CASE WHEN a.status_feature = 1 THEN a.origin_place_detail ELSE (SELECT location_name FROM master_location WHERE idx = a.origin_idx) END) as origin_name,
            (CASE WHEN a.status_feature = 1 THEN a.destination_place_detail ELSE (SELECT location_name FROM master_location WHERE idx = a.destination_idx) END) as destination_name,
            (SELECT office_name FROM master_office WHERE idx = a.office_idx) as name_office,
            (SELECT address FROM master_office WHERE idx = a.office_idx) as address_office,
            (SELECT telephone FROM master_office WHERE idx = a.office_idx) as telephone_office,
            (SELECT email_id FROM master_office WHERE idx = a.office_idx) as email_office,
            (SELECT SUM(qty) FROM job_order_commodity WHERE job_order_id = a.job_id) as total_qty,
            (SELECT SUM(gross_weight) FROM job_order_commodity WHERE job_order_id = a.job_id) as total_weight,
            (SELECT SUM(dimention) FROM job_order_commodity WHERE job_order_id = a.job_id) as total_dimention,
            (SELECT user_name FROM user_account WHERE idx = a.created_by) as xcreated_by
        ')
        ->from('job_order a')
        ->where('job_id',$job_id);
        $result = $this->db->get()->row_array();
        // $result = $this->db->query("select *, " . $this->ModelGlobal->selectDateFormat('job_date') . " as job_date from job_order where job_id = $job_id")->row_array();
        return $result;
    }
}
/* End of file M_cari.php */
/* Location: ./application/models/M_pemesanan.php */
