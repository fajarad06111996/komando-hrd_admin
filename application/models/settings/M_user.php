<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class M_user extends CI_Model
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
    var $select_column2 = array(
        'idx',
        'user_id',
        'user_name',
        'fullname',
        'status',
        'email_id',
        'user_level_id',
        'created_on',
        'created_by'
    );
    var $select_column = array(
        'a.idx',
        'a.user_id',
        'a.user_name',
        'a.fullname',
        'a.status',
        'a.email_id',
        'a.user_level_id',
        'a.created_on',
        'a.created_by',
        'b.level_alias'
    );
    var $order_column = array(
        '',
        'a.fullname',
        'a.user_name',
        'a.email_id',
        'a.status',
        'b.level_alias'
    );
    function make_query()
    {
        $query = "
        SELECT * FROM user_account a
        LEFT JOIN app_level_access b
        ON a.user_level_id = b.level_id
        WHERE user_level_id = 1
        ";
        $this->db->select($this->select_column)
        ->from('user_account a')
        ->join('app_level_access b', 'a.user_level_id = b.level_id', 'left')
        ->where(['office_idx'=> $this->office, 'status_delete'=>'N']);
        if(isset($_POST["search"]["value"])){
            $this->db->like("a.fullname", $_POST["search"]["value"]);
            $this->db->or_like("a.user_name", $_POST["search"]["value"]);
        }
        if(isset($_POST["order"])){
            $this->db->order_by($this->order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        }else{
            $this->db->order_by('a.fullname', 'ASC');
        }
    }
    public function showAllUser(){
        $this->make_query();
        if($_POST["length"] != -1){
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        $query = $this->db->get();
        return $query->result();
    }
    function get_filtered_data(){
        $this->make_query();
        $query = $this->db->get();
        return $query->num_rows();
    }
    function get_all_data(){
        $this->db->select("*");
        $this->db->from('user_account');
        // $this->db->join('app_company', 'app_user.user_company = app_company.company_id', 'left');
        // $this->db->join('dbjtetrace.jtracemasterdept d', 'app_user.user_dept = d.masterdeptid', 'left');
        return $this->db->count_all_results();
    }
    var $order_access = array('','fullname', 'user_name');
    function makeaccess_query(){
        $this->db->select('b.fullname,b.user_name,c.menu_alias,c.menu_parent_id,a.*');
        $this->db->from('app_uaccess a');
        $this->db->join('user_account b', 'a.access_username=b.user_name', 'inner');
        $this->db->join('app_menu c', 'a.access_menu_id=c.menu_id', 'inner');
        if(isset($_POST["search"]["value"])){
            $this->db->like("fullname", $_POST["search"]["value"]);
            $this->db->or_like("user_name", $_POST["search"]["value"]);
        }
        if(isset($_POST["order"])){
            $this->db->order_by($this->order_access[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        }else{
            $this->db->order_by('fullname', 'ASC');
        }
    }
    public function showAllAccessUser(){
        $this->makeaccess_query();
        if($_POST["length"] != -1){
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        $query = $this->db->get();
        return $query->result();
    }
    function getaccess_filtered_data(){
        $this->makeaccess_query();
        $query = $this->db->get();
        return $query->num_rows();
    }
    function getaccess_all_data(){
        $this->db->select('b.fullname,b.user_name,c.menu_alias,c.menu_parent_id,a.*');
        $this->db->from('app_uaccess a');
        $this->db->join('app_user b', 'a.access_username=b.user_name', 'inner');
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
    public function editUser($idx)
    {
        $this->db->select('*');
        $this->db->from('user_account a');
        $this->db->where('a.idx', $idx);
        $query = $this->db->get();
        if($query->num_rows() > 0){
            $a = $query->row_array();
            $b = [
                'user_level_idx' => $this->secure->enc($a['user_level_id']),
                'company_idx' => $this->secure->enc($a['company_idx'])
            ];
            $push = array_merge($a,$b);
            return $push;
        }else{
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
        // $query = $this->db->get('app_user');
        if($query->num_rows() > 0){
            return $query->row();
        }else{
            return false;
        }
    }
    function deleteUser($id)
    {
        $this->db->trans_begin();
        $this->db->where('idx', $id);
        $this->db->update('user_account',['status_delete'=> 'Y']);
        if($this->db->trans_status() == false){
            $this->db->trans_rollback();
                return 0;
        }else{
            $this->db->trans_commit();
                return 1;
        }
    }
    function deleteAccessUser()
    {
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
        $this->db->where("id_admin", $id);
        $this->db->update("t_admin", $data);
        return $this->db->affected_rows();
    }
    function getUsername($uname)
    {
        $this->db->where('user_id', $uname);
        $this->db->where_in('user_type', [99,6]);
        $query = $this->db->get('user_account');
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    function getMenuAlias($id)
    {
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
    public function updateMUserAccount($data, $id_useraccount)
    {
        $this->db->trans_begin();
        $this->db->where('idx', $id_useraccount);
        $this->db->update('user_account', $data);
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
    // CONTOH LAIN MODEL DATATABLE SERVERSIDE ACCESS
    function allposts_countAcc()
    {
        if($this->level == 1){
            $this->db->where(['access_company_id' => $this->office]);
        }else{
            $this->db->where(['access_company_id' => $this->office,'access_level_id <>'=>1]);
        }
        $query = $this->db->get('app_uaccess');
        return $query->num_rows();
    }
    function allpostsAcc($limit,$start,$col,$dir)
    {
        $this->db->select('*');
        $this->db->from('app_uaccess a');
        $this->db->join('app_level_access b','b.level_id=a.access_level_id','left');
        $this->db->join('app_menu c','c.menu_id=a.access_menu_id','left');
        if($this->level == 1){
            // $this->db->where_not_in('c.menu_id', [13,15,20]);
            $this->db->where(['a.access_company_id'=> $this->office,'c.menu_access' => 1]);
        }else{
            $this->db->where(['a.access_company_id'=> $this->office,'c.menu_access' => 1, 'b.level_id <>'=>1]);
        }
        $this->db->limit($limit,$start);
        $this->db->order_by($col,$dir);
        $query = $this->db->get();
        if($query->num_rows()>0)
        {
            return $query->result();
        }
        else
        {
            return null;
        }
    }
    function posts_searchAcc($limit,$start,$search,$col,$dir)
    {
        $like = array(
        'c.menu_alias'  => $search,
        'a.access_permissions_id' => $search
        );
        $this->db->select('*');
        $this->db->from('app_uaccess a');
        $this->db->join('app_level_access b','b.level_id=a.access_level_id','left');
        $this->db->join('app_menu c','c.menu_id=a.access_menu_id','left');
        if($this->level == 1){
            // $this->db->where_not_in('c.menu_id', [13,15,20]);
            $this->db->where(['a.access_company_id'=> $this->office,'c.menu_access' => 1]);
        }else{
            $this->db->where(['a.access_company_id'=> $this->office, 'c.menu_access' => 1, 'b.level_id <>'=>1]);
        }
        $this->db->like('b.level_alias',$search);
        $this->db->or_like($like);
        $this->db->limit($limit,$start);
        $this->db->order_by($col,$dir);
        $query = $this->db->get();
        if($query->num_rows()>0)
        {
            return $query->result();
        }
        else
        {
            return null;
        }
    }
    function posts_search_countAcc($search)
    {
        $like = array(
        'c.menu_alias'  => $search,
        'a.access_permissions_id' => $search
        );
        $this->db->select('*');
        $this->db->from('app_uaccess a');
        $this->db->join('app_level_access b','b.level_id=a.access_level_id','left');
        $this->db->join('app_menu c','c.menu_id=a.access_menu_id','left');
        if($this->level == 1){
            // $this->db->where_not_in('c.menu_id', [13,15,20]);
            $this->db->where(['a.access_company_id'=> $this->office, 'c.menu_access' => 1]);
        }else{
            $this->db->where(['a.access_company_id'=> $this->office, 'c.menu_access' => 1, 'b.level_id <>'=>1]);
        }
        $this->db->where('a.access_company_id', $this->office);
        $this->db->like('b.level_alias',$search);
        $this->db->or_like($like);
        $query = $this->db->get();
        return $query->num_rows();
    }
    // CONTOH LAIN MODEL DATATABLE SERVERSIDE ACCESS END
    // CONTOH LAIN MODEL DATATABLE SERVERSIDE USER
    var $select_column3 = array(
        'a.idx',
        'a.user_name',
        'a.fullname',
        '(SELECT level_alias FROM app_level_access WHERE level_id = a.user_level_id) as level',
        'a.address',
        'a.email_id',
        'a.mobile_phone',
        'a.status',
        '(SELECT fullname FROM user_account WHERE user_level_id = a.idx) as cr_by',
        'a.created_on'
    );
    function allposts_count()
    {
        $query = $this
                ->db
                ->where('status_delete !=', 'Y')
                ->get('user_account');
        return $query->num_rows();
    }
    function allposts($limit,$start,$col,$dir)
    {
    $query = $this
            ->db
            ->select($this->select_column3)
            ->from('user_account a')
            ->where('a.status_delete !=', 'Y')
            ->limit($limit,$start)
            ->order_by($col,$dir)
            ->get();
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
    $query = $this
            ->db
            ->select($this->select_column3)
            ->from('user_account a')
            ->group_start()
            ->where('a.status_delete !=', 'Y')
                ->group_start()
                ->like('a.fullname',$search)
                ->group_start()
                ->or_like('a.user_name',$search)
                    ->group_start()
                    ->or_like('a.email_id',$search)
                    ->group_start()
                        ->or_like('a.mobile_phone',$search)
                        ->group_start()
                        ->or_like('level',$search)
                        ->or_like('a.status',$search)
                        ->group_end()
                    ->group_end()
                    ->group_end()
                ->group_end()
                ->group_end()
            ->group_end()
            ->limit($limit,$start)
            ->order_by($col,$dir)
            ->get();
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
    $query2  = '
        SELECT  *
        FROM app_user a
        LEFT JOIN app_level_access b
        ON a.user_level_id=b.level_id
        AND a.user_deletion = "N"
        WHERE a.user_fullname LIKE "%'.$search.'%"
        OR a.user_name LIKE "%'.$search.'%"
        OR b.level_alias LIKE "%'.$search.'%"
    ';
    $query = $this
                ->db
                ->select($this->select_column3)
                ->from('user_account a')
                ->group_start()
                ->where('a.status_delete !=', 'Y')
                ->group_start()
                ->like('a.fullname',$search)
                    ->group_start()
                    ->or_like('a.user_name',$search)
                    ->group_start()
                    ->or_like('a.email_id',$search)
                        ->group_start()
                        ->or_like('a.mobile_phone',$search)
                        ->group_start()
                            ->or_like('level',$search)
                            ->or_like('a.status',$search)
                        ->group_end()
                        ->group_end()
                    ->group_end()
                    ->group_end()
                ->group_end()
                ->group_end()
                ->get();
    return $query->num_rows();
    }
    // CONTOH LAIN MODEL DATATABLE SERVERSIDE USER END
    // CONTOH LAIN MODEL2 DATATABLE SERVERSIDE USER
    // start datatables
    private function _get_datatables_query($company) {
        $select_column = array(
            'a.idx',
            'a.user_name',
            'a.user_id',
            'a.fullname',
            '(SELECT level_alias FROM app_level_access WHERE level_id = a.user_level_id LIMIT 1) as level',
            '(SELECT company_name FROM master_company WHERE idx = a.company_idx LIMIT 1) as company',
            'a.address',
            'a.email_id',
            'a.mobile_phone',
            'a.status',
            '(SELECT user_name FROM user_account WHERE idx = a.created_by LIMIT 1) as cr_by',
            'a.created_on'
        );//set column field database for datatable select
        $column_order = array(
            null,
            'a.user_name',
            'a.fullname',
            'a.level',
            'a.company_idx',
            'a.address',
            'a.email_id',
            'a.mobile_phone',
            'a.status',
            null,
            null
        ); //set column field database for datatable orderable
        $column_search = array(
            'a.user_id',
            'a.user_name',
            'a.fullname',
            'a.address',
            'a.email_id',
            'a.mobile_phone',
            'a.status'
        ); //set column field database for datatable searchable
        $order = array('a.idx' => 'desc'); // default order
        $this->db->select($select_column);
        $this->db->from('user_account a');
        if($this->level == '1'){
            $this->db->where(['a.status_delete'=>'N', 'a.company_idx'=>$company]);
            $this->db->where_in('a.user_type',[1,2,99]);
        }else{
            $this->db->where(['a.status_delete'=>'N', 'a.company_idx'=>$company]);
            $this->db->where_in('a.user_type',[1,2]);
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
        if(isset($_POST['order']) && $_POST['order']['0']['column']!='0') { // here order processing
            $this->db->order_by($column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        }  else if(isset($order)) {
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }
    function get_datatables($company) {
        $this->_get_datatables_query($company);
        if(@$_POST['length'] != -1)
        $this->db->limit(@$_POST['length'], @$_POST['start']);
        $query = $this->db->get()->result();
        // $query = $this->db->get_compiled_select();
        return $query;
    }
    function count_filtered($company) {
        $this->_get_datatables_query($company);
        $query = $this->db->get();
        return $query->num_rows();
    }
    function count_all($company) {
        $this->db->from('user_account a');
        if($this->level == '1'){
            $this->db->where(['a.status_delete'=>'N', 'a.company_idx'=>$company]);
            $this->db->where_in('a.user_type',[1,2,99]);
        }else{
            $this->db->where(['a.status_delete'=>'N', 'a.company_idx'=>$company]);
            $this->db->where_in('a.user_type',[1,2]);
        }
        return $this->db->count_all_results();
    }
    // end datatables
    // CONTOH LAIN MODEL2 DATATABLE SERVERSIDE USER END
    public function changeStatus($data, $idx)
    {
    $this->db->trans_begin();
    $this->db->where('idx', $idx);
    $this->db->update('user_account', $data);
    if($this->db->trans_status() == false)
    {
        $this->db->trans_rollback();
        return 0;
    }else{
        $this->db->trans_commit();
        return 1;
    }
    }
    public function resetPassword($id)
    {
    $this->db->where('idx', $id);
    $this->db->update('user_account',['password'=>password_hash('123456', PASSWORD_DEFAULT)]);
    if($this->db->affected_rows() > 0){
        return true;
    }else{
        return false;
    }
    }

    public function uType($level)
    {
        $this->db->select('*')
        ->from('app_level_access')
        ->where('level_id', $level);
        $result = $this->db->get()->row_array();
        return $result;
    }
}
/* End of file M_admin.php */
/* Location: ./application/models/M_admin.php */