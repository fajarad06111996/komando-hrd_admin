<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class M_level extends CI_Model 
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
            '(SELECT office_name FROM master_office WHERE idx = a.office_idx) as office_name',
            '(SELECT status_name FROM status_user_type WHERE status = a.level_user_type) as userType'
            
        );//set column field database for datatable select
        $column_order = array(
            null,
            null,
            'a.level_name',
            'a.level_alias',
            '(CASE WHEN 1=0 THEN 0 ELSE (SELECT `status_name` FROM `status_user_type` WHERE `status` = `a`.`level_user_type`) END)',
            '(CASE WHEN 1=0 THEN 0 ELSE (SELECT `office_name` FROM `master_office` WHERE `idx` = `a`.`office_idx`) END)',
            'a.level_active',
            null,
            'a.level_remark',
    
        ); //set column field database for datatable orderable
        $column_search = array(
            'a.level_name',
            'a.level_alias',
            'IF(1=0,0,(SELECT `office_name` FROM `master_office` WHERE `idx` = `a`.`office_idx`))',
            'IF(1=0,0,(SELECT `status_name` FROM `status_user_type` WHERE `status` = `a`.`level_user_type`))',
            'IF(a.level_active=1,"Aktif","Non Aktif")',
            'a.level_remarks',
        ); //set column field database for datatable searchable
        $order = array('a.level_id' => 'desc'); // default order
        $this->db->select($select_column);
        $this->db->from('app_level_access a');
        if($this->level == 1){
            $this->db->where('a.level_deletion', 'N');
        }else{
            if($this->tOffice==2){
                $this->db->where(['a.level_deletion'=> 'N', 'level_id <> '=>1,'office_idx'=>$this->office]);
            }else{
                $this->db->where(['a.level_deletion'=> 'N', 'level_id <> '=>1]);
            }
        }
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
            
        if($_POST['order']['0']['column']!='0') { // here order processing
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
        $this->db->from('app_level_access a');
        if($this->level == 1){
            $this->db->where('a.level_deletion', 'N');
        }else{
            if($this->tOffice==2){
                $this->db->where(['a.level_deletion'=> 'N', 'level_id <> '=>1,'office_idx'=>$this->office]);
            }else{
                $this->db->where(['a.level_deletion'=> 'N', 'level_id <> '=>1]);
            }
        }
        return $this->db->count_all_results();
    }
    // end datatables
    // CONTOH LAIN MODEL2 DATATABLE SERVERSIDE USER END
    var $select_column = array(
        'level_id',
        'level_name',
        'level_alias',
        'level_active',
        'level_deletion',
        'level_stamp_user',
        'level_stamp_date',
        'level_remark'
    );  
    var $order_column = array(
        '',
        'level_name',
        'level_alias',
        'level_active'
    );
    function make_query()
    { 
        $dbs = $this->load->database('dbdata', TRUE);
        $where = array(
        'level_deletion'  =>  'N'
        );
        $where2 = array(
        'level_deletion'  =>  'N',
        'level_id <>'  =>  1
        );
        $this->db->select($this->select_column);  
        $this->db->from('app_level_access');
        if($this->level == 1){
        $this->db->where($where);
        }else{
        $this->db->where($where2);
        }
        if(isset($_POST["search"]["value"])){  
        $this->db->like("level_name", $_POST["search"]["value"]);  
        $this->db->or_like("level_alias", $_POST["search"]["value"]);  
        }  
        if(isset($_POST["order"])){  
        $this->db->order_by($this->order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);  
        }else{  
        $this->db->order_by('level_id', 'ASC');  
        }  
    }
    public function showAllLevel()
    {
        $dbs = $this->load->database('dbdata', TRUE);
        $this->make_query();  
        if($_POST['length'] != -1){  
        $this->db->limit($_POST['length'], $_POST['start']);  
        }  
        $this->db->limit($_POST['length'], $_POST['start']);  
        $query = $this->db->get();  
        return $query->result();  
        }
    public function allUserShow($number,$offset,$keyword = null)
    {
        $dbs = $this->load->database('dbdata', TRUE);
        if($keyword){
        $this->db->select($this->select_column);  
        $this->db->from('user_account');  
        // $this->db->join('app_company', 'user_account.user_company = app_company.company_id', 'left');
        // $this->db->join($dbs->database.'.jtracemasterdept d', 'user_account.user_dept = d.masterdeptid', 'left');
        $this->db->limit($number,$offset);
        $this->db->like('fullname',$keyword);
        $this->db->or_like('user_name',$keyword);
        $this->db->or_like('email_id',$keyword);
        $this->db->order_by('fullname', 'ASC');
        $query = $this->db->get();  
        return $query->result();  
        }else{
        $this->db->select($this->select_column);  
        $this->db->from('user_account');  
        // $this->db->join('app_company', 'user_account.user_company = app_company.company_id', 'left');
        // $this->db->join($dbs->database.'.jtracemasterdept d', 'user_account.user_dept = d.masterdeptid', 'left');
        $this->db->limit($number,$offset);
        $this->db->order_by('fullname', 'ASC');
        $query = $this->db->get();  
        return $query->result();
        }
        }
    function showLevel()
    {
        $where = array(
        'level_deletion'  =>  'Y'
        );
        $this->db->select($this->select_column);  
        $this->db->from('app_level_access');
        $this->db->where($where);
        $query  = $this->db->get();
        return  $query->result();
    }
    function countUser($keyword = null){
        $dbs = $this->load->database('dbdata', TRUE);
        $this->db->from('user_account');
        // $this->db->join('app_company', 'user_account.user_company = app_company.company_id', 'left');
        // $this->db->join($dbs->database.'.jtracemasterdept d', 'user_account.user_dept = d.masterdeptid', 'left');
        $this->db->like('fullname',$keyword);
        $this->db->or_like('user_name',$keyword);
        $this->db->or_like('email_id',$keyword);
        $this->db->order_by('fullname', 'ASC');
        $query  = $this->db->count_all_results();
        return $query;
    }
    function get_filtered_data()
    {
        $dbs = $this->load->database('dbdata', TRUE);
        $this->make_query();  
        $query = $this->db->get();  
        return $query->num_rows();  
    }       
    function get_all_data()
    {
        $dbs = $this->load->database('dbdata', TRUE);
        $where = array(
        'level_deletion'  =>  'N'
        );
        $where2 = array(
        'level_deletion'  =>  'N',
        'level_id <>'     => 1
        );
        $this->db->select("*");  
        $this->db->from('app_level_access');
        if($this->level == 1){
        $this->db->where($where);
        }else{
        $this->db->where($where2);
        }
        return $this->db->count_all_results();  
    }
    var $order_access = array('','b.level_name', 'b.level_alias', 'c.menu_alias');  
    function makeaccess_query()
    {
        $dbs = $this->load->database('dbdata', TRUE);
        $this->db->select('b.level_name,b.level_alias,c.menu_alias,c.menu_parent_id,a.*');  
        $this->db->from('app_uaccess a');  
        $this->db->join('app_level_access b', 'a.access_level_id=b.level_id', 'inner');
        $this->db->join('app_menu c', 'a.access_menu_id=c.menu_id', 'inner');
        if(isset($_POST["search"]["value"])){  
        $this->db->like("b.level_alias", $_POST["search"]["value"]);  
        $this->db->or_like("c.menu_alias", $_POST["search"]["value"]);
        $this->db->or_like("c.menu_title", $_POST["search"]["value"]);
        $this->db->or_like("a.access_permissions_id", $_POST["search"]["value"]);
        }  
        if(isset($_POST["order"])){  
        $this->db->order_by($this->order_access[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);  
        }else{  
        $this->db->order_by('b.level_id', 'ASC');  
        }  
    }
    public function showAllAccessLevel()
    {
        $dbs = $this->load->database('dbdata', TRUE);
        $this->makeaccess_query();  
        if($_POST["length"] != -1){  
        $this->db->limit($_POST['length'], $_POST['start']);  
        }  
        $query = $this->db->get();  
        return $query->result();  
        }
    public function allShowAccessUser($number,$offset,$keyword = null)
    {
        $dbs = $this->load->database('dbdata', TRUE);
        $this->db->select('b.fullname,b.user_name,c.menu_alias,c.menu_parent_id,a.*');  
        $this->db->from('app_uaccess a');  
        $this->db->join('user_account b', 'a.access_username=b.user_name', 'inner');
        $this->db->join('app_menu c', 'a.access_menu_id=c.menu_id', 'inner'); 
        $this->db->limit($number,$offset);
        if($keyword != NULL){
        $this->db->like("b.user_fullname", $keyword);
        $this->db->or_like("b.user_name", $keyword);
        $this->db->or_like("c.menu_alias", $keyword);
        $this->db->or_like("c.menu_parent_id", $keyword);
        }
        $this->db->order_by('b.fullname', 'ASC');   
        $query = $this->db->get();  
        return $query->result();  
        }
    public function countAccessUser($keyword = null)
    {
        $dbs = $this->load->database('dbdata', TRUE);
        $this->db->select('b.fullname,b.user_name,c.menu_alias,c.menu_parent_id,a.*');  
        $this->db->from('app_uaccess a');  
        $this->db->join('user_account b', 'a.access_username=b.user_name', 'inner');
        $this->db->join('app_menu c', 'a.access_menu_id=c.menu_id', 'inner');
        $this->db->like("b.user_fullname", $keyword);
        $this->db->or_like("b.user_name", $keyword);
        $this->db->or_like("c.menu_alias", $keyword);
        $this->db->or_like("c.menu_parent_id", $keyword);
        $this->db->order_by('b.fullname', 'ASC');   
        $query  = $this->db->count_all_results(); 
        return $query;  
        }
    function getaccess_filtered_data()
    {
        $dbs = $this->load->database('dbdata', TRUE);
        $this->makeaccess_query();  
        $query = $this->db->get();  
        return $query->num_rows();  
    }       
    function getaccess_all_data()
    {
        $dbs = $this->load->database('dbdata', TRUE);
        $this->db->select('b.level_name,b.level_alias,c.menu_alias,c.menu_parent_id,a.*');  
        $this->db->from('app_uaccess a');  
        $this->db->join('app_level_access b', 'a.access_level_id=b.level_id', 'inner');
        $this->db->join('app_menu c', 'a.access_menu_id=c.menu_id', 'inner');
        return $this->db->count_all_results();
    }
    function insertData($data, $table)
    {
        if ($this->db->insert($table, $data)) {
        return $this->db->affected_rows(); 
        } else { 
        return false; 
        }
        // $this->db->insert($table, $data);
    }
    public function editLevel($id)
    {
        $this->db->select('*');
        $this->db->from('app_level_access a');
        $this->db->where('level_id', $id);
        $query = $this->db->get();
        if($query->num_rows() > 0){
            $cek = $query->row_array();
            $push = [
                'idx' => $cek['level_user_type']==null?null:$this->secure->enc($cek['level_user_type']),
                'office_enidx' => $cek['office_idx']==null?null:$this->secure->enc($cek['office_idx']),
            ];
            $datanya = array_merge($push,$cek);
            return $datanya;
        }else{
            return false;
        }
    }
    function updateData($where, $data, $table)
    {
        $this->db->where($where);
        // $this->db->update($table, $data);
        if($this->db->update($table, $data)) {
        return $this->db->affected_rows(); 
        } else { 
        return false; 
        }
    }
    public function editAccessUser ()
    {
        $id = $this->input->get('id');
        $this->db->select('*');
        $this->db->from('app_uaccess');
        $this->db->where('access_id', $id);
        $query = $this->db->get();
            // $query = $this->db->get('user_account');
            if($query->num_rows() > 0){
                return $query->row();
            }else{
                return false;
            }
        }
    function cekUserLevel()
    {
        $id = $this->input->get('id');
        $this->db->select('*')
        ->from('user_account')
        ->where('user_level_id', $id);
        $query = $this->db->get();
        return $query->num_rows();
    }
    function cekAccessLevel()
    {
        $id = $this->input->get('id');
        $this->db->select('*')
        ->from('app_uaccess')
        ->where('access_level_id', $id);
        $query = $this->db->get();
        return $query->num_rows();
    }
    function getUserLevel()
    {
        $id = $this->input->get('id');
        $this->db->select('*')
        ->from('user_account')
        ->where('user_level_id', $id);
        $query = $this->db->get();
        return $query->result();
    }
    function getAccessLevel()
    {
        $id = $this->input->get('id');
        $this->db->select('*')
        ->from('app_uaccess a')
        ->join('app_menu b', 'a.access_menu_id=b.menu_id','left')
        ->where('access_level_id', $id);
        $query = $this->db->get();
        return $query->result();
    }
        function deleteLevel()
    {
        $data = array(
        'level_deletion'  =>  'Y'
        );
            $id = $this->input->get('id');
            $this->db->where('level_id', $id);
            $this->db->update('app_level_access',$data);
            if($this->db->affected_rows() > 0){
                return true;
            }else{
                return false;
            }
        }
        function deleteAccessUser()
    {
        $dbs = $this->load->database('dbdata', TRUE);
            $id = $this->input->get('id');
            $this->db->where('access_id', $id);
            $this->db->delete('app_uaccess');
            if($this->db->affected_rows() > 0){
                return true;
            }else{
                return false;
            }
        }
        public function update($data, $id) 
    {
        $dbs = $this->load->database('dbdata', TRUE);
            $this->db->where("id_admin", $id);
            $this->db->update("t_admin", $data);
            return $this->db->affected_rows();
    }
    function getUsername($uname)
    {
        $dbs = $this->load->database('dbdata', TRUE);
        $this->db->where('user_name', $uname);
        $query = $this->db->get('user_account');
        if ($query->num_rows() > 0) {
        return true;
        } else {
        return false;
        }
    }
    function getLevel($level)
    {
        $this->db->where('level_name', $level);
        $query = $this->db->get('app_level_access');
        if ($query->num_rows() > 0) {
        return true;
        } else {
        return false;
        }
    }
    function getMenuAlias($id)
    {
        $dbs = $this->load->database('dbdata', TRUE);
        $this->db->select('*');
        $this->db->from('app_menu');
        $this->db->where('menu_id', $id);
        $result =  $this->db->get();
        if ($result->num_rows() > 0) {
        foreach ($result->result() as $row) {
            return $row->menu_alias;
        }
        }else{
        return 'NULL';
        }
    }
    // CONTOH LAIN MODEL DATATABLE SERVERSIDE
    function allposts_count()
    {
        if($this->level == 1){
        $where = array(
            'level_deletion' => 'N'
        );
        }else{
        $where = array(
            'level_deletion' => 'N',
            'level_id <>'    => 1
        );
        }
        $query = $this
                ->db
                ->get_where('app_level_access',$where);
        return $query->num_rows();  
    }
    function allposts($limit,$start,$col,$dir)
    {
      if($this->level == 1){
        $this->db->where(['level_deletion'=>'N']);
      }else{
        $this->db->where(['level_deletion'=>'N', 'level_id <>'=>1]);
      }
      $this->db->limit($limit,$start);
      $this->db->order_by($col,$dir);
      $query = $this->db->get('app_level_access');
      if($query->num_rows()>0)
      {
          return $query->result(); 
      }
      else
      {
          return null;
      }
    }
    function posts_search($limit,$start,$search,$col,$dir)
    {
      if($this->level == 1){
        $where = ['level_deletion'=>'N'];
      }else{
        $where = ['level_deletion'=>'N', 'level_id <>'=>1];
      }
      $query = $this
              ->db
              ->where($where)
              ->group_start()
              ->like('level_name',$search)
              ->or_like('level_alias',$search)
              ->group_end()
              ->limit($limit,$start)
              ->order_by($col,$dir)
              ->get('app_level_access');
      if($query->num_rows()>0)
      {
          return $query->result();  
      }
      else
      {
          return null;
      }
    }
    function posts_search_count($search)
    {
      if($this->level == 1){
        $where = ['level_deletion'=>'N'];
      }else{
        $where = ['level_deletion'=>'N', 'level_id <>'=>1];
      }
      $query = $this
              ->db
              ->where($where)
              ->group_start()
              ->like('level_name',$search)
              ->or_like('level_alias',$search)
              ->group_end()
              ->get('app_level_access');
      return $query->num_rows();
    }
    // CONTOH LAIN MODEL DATATABLE SERVERSIDE END
    public function changeStatus($data, $level_id)
    {
        $this->db->trans_begin();
        $this->db->where('level_id', $level_id);
        $this->db->update('app_level_access', $data);
        if($this->db->trans_status() == false)
        {
            $this->db->trans_rollback();
            return 0;
        }else{
            $this->db->trans_commit();
            return 1;
        }
    }

    public function listUserType()
    {
        if($this->level == 1){
            $this->db->select('status as statusx,status_name as status_name')
            ->from('status_user_type')
            ->order_by('status_name', 'ASC');
            $result = $this->db->get()->result();
        }else{
            $this->db->select('status as statusx,status_name as status_name')
            ->from('status_user_type')
            ->where_not_in('status', [99])
            ->order_by('status_name', 'ASC');
            $result = $this->db->get()->result();
        }
        return $result;
    }

    public function getOffice()
    {
        $this->db->select('office_name, idx')
        ->from('master_office')
        ->where('status', 1);
        $result = $this->db->get()->result();
        return $result;
    }

    public function updateAccessLevel($level_id, $updateData)
    {
        $this->db->trans_begin();
        $this->db->where('level_id', $level_id);
        $this->db->update('app_level_access', $updateData);
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
    public function updateAccessSettings($level_id, $updateData)
    {
        $this->db->trans_begin();
        $this->db->where('level_id', $level_id);
        $this->db->update('app_level_access', $updateData);
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
/* End of file M_admin.php */
/* Location: ./application/models/M_admin.php */