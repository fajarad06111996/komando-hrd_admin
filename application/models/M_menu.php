<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_menu extends CI_Model
{
    // CONTOH LAIN MODEL2 DATATABLE SERVERSIDE USER
    // start datatables
    var $select_column4 = array(
        'a.idx',
        'a.client_id',
        'a.client_code',
        'a.client_name',
        'IF(a.client_type = 1, "PERSONAL", "CORPORATE") as type',
        'a.email_id',
        'a.mobile_phone',
        'a.status',
        'a.status_user',
        'a.status_api',
        'b.key',
        'a.client_type',
        'a.telephone',
        'a.tax_id',
        'a.fax',
        'a.country',
        'a.province',
        'a.city',
        'a.postal_code',
        'a.state_code',
        'a.attention',
        'a.address'
        
    );//set column field database for datatable select
    var $column_order = array(
        'a.menu_id',
        'a.menu_title',
        'a.menu_alias',
        'a.menu_parent_id',
        'a.menu_icon',
        'a.menu_access',
        'a.menu_urutan',
        'a.menu_parent_active',
        'a.menu_parent_sub',
        'a.menu_deletion',
        'mTitle'
    ); //set column field database for datatable orderable
    var $column_search = array(
        // 'a.menu_title',
        'a.menu_alias',
        'a.menu_icon',
        'b.menu_title',
    ); //set column field database for datatable searchable
    var $order = array('a.menu_id' => 'asc'); // default order 

    private function _get_datatables_query($id = "", $parent = "") {
        if(empty($parent)){
        $select_col = array('a.*','b.menu_title AS mTitle');
        $col_search = array('a.menu_alias','a.menu_icon','b.menu_title',);
        }else{
        $select_col = array('a.*');
        $col_search = array('a.menu_alias','a.menu_icon','a.menu_title',);
        }
    $this->db->select($select_col);
    $this->db->from('app_menu a');
    if(empty($parent)){
        $this->db->join('app_menu b', 'a.menu_parent_id=b.menu_id', 'inner');
    }
    if(!empty($id)){
        $this->db->where('a.menu_parent_sub', $id);
    }
    if(!empty($parent)){
        $this->db->where('a.menu_parent_id', null);
    }
    $i = 0;
    foreach ($col_search as $item) { // loop column 
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
    function get_datatables($id = "",$parent = "") {
        $this->_get_datatables_query($id,$parent);
        if(@$_POST['length'] != -1)
        $this->db->limit(@$_POST['length'], @$_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }
    function count_filtered($id = "", $parent = "") {
        $this->_get_datatables_query($id,$parent );
        $query = $this->db->get();
        return $query->num_rows();
    }
    function count_all($id = "", $parent = "") {
        $this->db->from('app_menu a');
        if(!empty($id)){
            $this->db->where('a.menu_parent_sub', $id);
        }
        if(!empty($parent)){
            $this->db->where('a.menu_parent_id', null);
        }
        return $this->db->count_all_results();
    }
    // end datatables
    // CONTOH LAIN MODEL2 DATATABLE SERVERSIDE USER END

    function select_all()
    {
        return $this->db->query("SELECT
        (case when LOWER(REPLACE(a.menu_title,' ',''))='byakgansdfsdfsdfy' then a.menu_id end) AS menuId
        FROM app_menu a LIMIT 1;")->result();
    }

    function editMenuParents()
    {
        $id = $this->input->get('id');
        $this->db->where('menu_id', $id);
        $query = $this->db->get('app_menu');
        if ($query->num_rows() > 0) {
        return $query->row();
        } else {
        return false;
        }
    }

    function editMenuChild()
    {
        $id = $this->input->get('id');
        $this->db->where('menu_id', $id);
        $query = $this->db->get('app_menu');
        if ($query->num_rows() > 0) {
        return $query->row();
        } else {
        return false;
        }
    }

    function cekParentId($id)
    {
        $this->db->select('*')
        ->from('app_menu')
        ->where('menu_parent_id', $id);
        $query = $this->db->get();
        return $query->num_rows();
    }

    function cekHakAccess($id)
    {
        $this->db->select('*')
        ->from('app_uaccess a')
        ->where('a.access_menu_id', $id);
        $query = $this->db->get();
        return $query->num_rows();
    }

    function cekHakSubAccess($id)
    {
        $this->db->select('*')
        ->from('app_uaccess a')
        ->where('a.access_menu_id', $id);
        $query = $this->db->get();
        return $query->num_rows();
    }
    
    function getHakAccess($id)
    {
        $this->db->select('*')
        ->from('app_uaccess a')
        ->join('app_level_access b','a.access_level_id=b.level_id', 'inner')
        ->where('a.access_menu_id', $id);
        $query = $this->db->get();
        return $query->result();
    }

    function getHakSubAccess()
    {
        $this->db->select('*')
        ->from('app_uaccess a')
        ->join('app_level_access b','a.access_level_id=b.level_id', 'inner');
        $query = $this->db->get();
        return $query->result();
    }

    function cekSubMenu($id)
    {
        $this->db->select('*')
        ->from('app_menu')
        ->where('menu_parent_id', $id);
        $query = $this->db->get();
        return $query->result();
    }

    function updateData($where, $data, $table)
    {
        $this->db->where($where);
        $this->db->update($table, $data);
        if($this->db->affected_rows() > 0) {
        return true; 
        } else { 
        return false; 
        }
    }

    function deleteMenuParents()
    {
        $id = $this->input->get('id');
        $this->db->where('menu_id', $id);
        $this->db->delete('app_menu');
        if ($this->db->affected_rows() > 0) {
        return true;
        } else {
        return false;
        }
    }

    function getModParent()
    {
        $this->db->select('*');
        $this->db->from('app_menu a');
        $this->db->where('a.menu_parent_sub', 1);
        $this->db->where('a.menu_access', 1);
        $this->db->order_by('a.menu_urutan', 'ASC');
        return $this->db->get();
    }

    function getSubModul($idParent = "")
    {
        $this->db->select('a.*,b.menu_title AS mTitle');
        $this->db->from('app_menu a');
        $this->db->join('app_menu b', 'a.menu_parent_id=b.menu_id', 'inner');
        $this->db->where('a.menu_parent_sub', 2);
        if (!empty($idParent)) {
        $replace  = str_replace(",", "','", $idParent);
        $where  = "a.menu_id IN('$replace') AND a.menu_access='1'";
        $this->db->where($where);
        }
        $this->db->order_by('a.menu_parent_id,a.menu_urutan,a.menu_id', 'ASC');
        return $this->db->get();
    }

    function getActiveChild($id, $usernamex)
    {
        $username = $this->secure->dec($usernamex);
        $this->db->select('access_submenu_id');
        $this->db->from('app_uaccess');
        $this->db->where('access_menu_id', $id);
        $this->db->where('access_level_id', $username);
        return $this->db->get();
    }

    function getChildSubModul($idParent = "")
    {
        $this->db->select('a.*,b.menu_title AS mTitle');
        $this->db->from('app_menu a');
        $this->db->join('app_menu b', 'a.menu_parent_id=b.menu_id', 'inner');
        $this->db->where('a.menu_parent_sub', 3);
        if (!empty($idParent)) {
        $replace  = str_replace(",", "','", $idParent);
        $where  = "a.menu_id IN('$replace') AND a.menu_access='1' ";
        $this->db->where($where);
        }
        $this->db->order_by('a.menu_parent_id,a.menu_urutan,a.menu_id', 'ASC');
        return $this->db->get();
    }

    function getSubChild($idChlid = "")
    {
        $this->db->select('a.*,b.menu_title AS mTitle');
        $this->db->from('app_menu a');
        $this->db->join('app_menu b', 'a.menu_parent_id=b.menu_id', 'inner');
        $this->db->where('a.menu_parent_sub', 3);
        if (!empty($idChlid)) {
        $this->db->where('a.menu_parent_id', $idChlid);
        $this->db->where('a.menu_access', 1);
        }
        $this->db->order_by('a.menu_urutan', 'ASC');
        return $this->db->get();
    }

    function countSubChild($keyword = null,$idChlid = "")
    {
        $this->db->select('a.*,b.menu_title AS mTitle');
        $this->db->from('app_menu a');
        $this->db->join('app_menu b', 'a.menu_parent_id=b.menu_id', 'inner');
        $this->db->where('a.menu_parent_sub', 3);
        $this->db->like('a.menu_title', $keyword);
        if (!empty($idChlid)) {
        $this->db->where('a.menu_parent_id', $idChlid);
        $this->db->where('a.menu_access', 1);
        }
        return $this->db->get()->num_rows();
    }

    function getMenuDetail($id)
    {
        $this->db->select('*');
        $this->db->from('app_menu');
        $this->db->where('menu_id', $id);
        return $this->db->get();
    }

    function getIdSubModul($uriSegment = "")
    {
        return  $this->db->query("SELECT a.menu_title,a.menu_alias,a.menu_parent_id AS pChild,c.menu_alias AS title1,b.menu_alias AS title2,a.menu_alias AS title3
                                FROM app_menu a INNER JOIN app_menu b ON a.menu_parent_id=b.menu_id
                                LEFT JOIN app_menu c ON b.menu_parent_id=c.menu_id
                                WHERE (LOWER(REPLACE(a.menu_title,' ',''))='$uriSegment')");
    }

    function SelectMenuParent($sub)
    {
        if (!empty($sub)) :
        $_query = "SELECT * FROM app_menu WHERE menu_id='$sub' AND (menu_parent_id IS NULL OR menu_parent_id=0)";
        else :
        $_query = "SELECT * FROM app_menu WHERE menu_parent_id IS NULL OR menu_parent_id=0";
        endif;
        return $this->db->query($_query);
    }

    function getMenu($menu)
    {
        $this->db->select('*');
        $this->db->from('app_menu');
        $this->db->where('menu_title', $menu);
        return $this->db->get();
    }

    function getSubMenu($parent)
    {
        $this->db->select('*');
        $this->db->from('app_menu');
        $this->db->where('menu_parent_id', $parent);
        return $this->db->get();
    }

    function SelectMenuChildName($data)
    {
        $_query = "
            SELECT a.*,b.MENU_TITLE AS TITLE FROM " . $this->NAME_DB . "app_menu_trace 
            a LEFT JOIN " . $this->NAME_DB . "app_menu_trace b ON a.menu_parent_id=b.menu_id
            WHERE a.MENU_ID in(" . $data . ") AND a.menu_parent_id is not null ORDER BY a.menu_parent_id,a.menu_urutan,a.menu_id asc";
        //echo $_query;die();
        return $this->DB->query($_query);
    }

    function selectAccessDetail($username)
    {
        $this->db->select('*');
        $this->db->from('app_uaccess');
        $this->db->where('access_level_id', $username);
        $this->db->where('access_submenu_id<>', '');
        $this->db->where('access_rolemenu', 1);
        return $this->db->get();
    }

    function companyAccess($level)
    {
        $this->db->select('*');
        $this->db->from('app_oaccess');
        $this->db->where('access_level_id', $level);
        return $this->db->get();
    }

    function counterAccess($level, $office)
    {
        $this->db->select('*');
        $this->db->from('app_counter_access');
        $this->db->where(['access_level_id'=> $level, 'office_idx' => $office]);
        return $this->db->get();
    }

    function selectChildAccessDetail($username)
    {
        $this->db->select('*');
        $this->db->from('app_uaccess');
        $this->db->where('access_level_id', $username);
        $this->db->where('access_submenu_id<>', '');
        $this->db->where('access_rolemenu', 2);
        return $this->db->get();
    }
    public function cekRightbar($user)
    {
        $this->db->select('*')
        ->from('setting_rightbar')
        ->where('user_idx', $user);
        $result = $this->db->get();
        return $result->row_array();
    }
    public function updateRightbar($user, $data)
    {
        $this->db->trans_begin();
        $this->db->where('user_idx', $user);
        $this->db->update('setting_rightbar', $data);
        if($this->db->trans_status() === FALSE)
        {
        $this->db->trans_rollback();
        $this->db->select('*')
            ->from('setting_rightbar')
            ->where('user_idx', $user);
            $result = $this->db->get();
            $updated = array(
                'updated' => false
            );
            $resultx = array_merge($updated,$result->row_array());
            return $resultx;
        }
        else
        {
        $this->db->trans_commit();
            $this->db->select('*')
            ->from('setting_rightbar')
            ->where('user_idx', $user);
            $result = $this->db->get();
            $updated = array(
                'updated' => true
            );
            $resultx = array_merge($updated,$result->row_array());
            return $resultx;
        }
    }

    public function getCounterList($level)
    {
        $this->db->select('*')
        ->from('master_counter')
        ->where(['status'=>1,'status_delete'=>'N','FIND_IN_SET(idx,(SELECT counter_viewer FROM app_level_access WHERE level_id = '.$level.')) <> '=>0]);
        // $result = $this->db->get_compiled_select();
        $result = $this->db->get()->result();
        return $result;
    }
}

/* End of file M_admin.php */
/* Location: ./application/models/M_admin.php */
