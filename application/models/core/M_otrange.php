<?php
defined('BASEPATH') or exit('No direct script access allowed');
class M_otrange extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('ModelGlobal');
        $this->load->library('Secure');
        $this->office   = $this->secure->dec($this->session->userdata('JToffice_id'));
        $this->idx      = $this->secure->dec($this->session->userdata('JTidx'));
        $this->username = $this->session->userdata('JTuser_id');
        $this->level    = $this->secure->dec($this->session->userdata('JTlevel'));
    }
    // CONTOH LAIN MODEL2 DATATABLE SERVERSIDE USER
    // start datatables
    var $select_column = array(
        'a.*'
    );//set column field database for datatable select
    var $column_order = array(
        null,
        null
    ); //set column field database for datatable orderable
    var $column_search = array(
    ); //set column field database for datatable searchable
    var $order = array('a.idx' => 'desc'); // default order
    private function _get_datatables_query()
    {
        $this->db->select($this->select_column);
        $this->db->from('master_ot a');
        $this->db->where(['a.company_idx'=> $this->office,'a.status_delete'=> 0]);
        $i = 0;
        foreach ($this->column_search as $item) { // loop column
            if(@$_POST['search']['value']) { // if datatable send POST for search
                if($i===0) { // first loop
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, trim($_POST['search']['value']));
                } else {
                    $this->db->or_like($item, trim($_POST['search']['value']));
                }
                if(count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }
        if(isset($_POST['order']) && $_POST['order']['0']['column'] != '0') { // here order processing
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
        // $query = $this->db->get_compiled_select();
        $query = $this->db->get()->result();
        return $query;
    }
    function count_filtered() {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }
    function count_all() {
        $this->db->from('master_ot a');
        $this->db->where(['a.company_idx'=> $this->office,'a.status_delete'=> 0]);
        return $this->db->count_all_results();
    }
    // end datatables
    // CONTOH LAIN MODEL2 DATATABLE SERVERSIDE USER END
    // QUERY TARIF PAGINATION
    public function queryTarifDom($limit, $start = 0,$keyword = null,$type = null)
    {
        if($keyword)
        {
            if($type == 1){
                $search_keyword = " and (IF(a.status = 1,'Aktif','Non Aktif') like '%$keyword%')";
            }elseif($type == 2){
                $search_keyword = " and ((select client_name from master_client where client_id = a.client_id) like '%$keyword%')";
            }elseif($type == 3){
                $search_keyword = " and ((select location_name from master_location where idx = a.origin_idx) like '%$keyword%')";
            }elseif($type == 4){
                $search_keyword = " and ((select district_name from master_location where idx = a.origin_idx) like '%$keyword%')";
            }elseif($type == 5){
                $search_keyword = " and ((select city_name from master_location where idx = a.origin_idx) like '%$keyword%')";
            }elseif($type == 6){
                $search_keyword = " and ((select postal_code from master_location where idx = a.origin_idx) like '%$keyword%')";
            }elseif($type == 7){
                $search_keyword = " and ((select location_code from master_location where idx = a.origin_idx) like '%$keyword%')";
            }elseif($type == 8){
                $search_keyword = " and ((select location_name from master_location where idx = a.destination_idx) like '%$keyword%')";
            }elseif($type == 9){
                $search_keyword = " and ((select district_name from master_location where idx = a.destination_idx) like '%$keyword%')";
            }elseif($type == 10){
                $search_keyword = " and ((select city_name from master_location where idx = a.destination_idx) like '%$keyword%')";
            }elseif($type == 11){
                $search_keyword = " and ((select postal_code from master_location where idx = a.destination_idx) like '%$keyword%')";
            }elseif($type == 12){
                $search_keyword = " and ((select location_code from master_location where idx = a.destination_idx) like '%$keyword%')";
            }elseif($type == 13){
                $search_keyword = " and ((select product_name from master_product where idx = a.product_idx) like '%$keyword%' or (CASE WHEN (select product_title from master_product where idx = a.product_idx) = 1 THEN 'DARAT' ELSE 'UDARA' END) like '%$keyword%')";
            }elseif($type == 14){
                $search_keyword = " and (a.description like '%$keyword%')";
            }else{
                $search_keyword = " and (a.tariff_value like '%$keyword%' or a.tariff_value_next like '%$keyword%')";
            }
        }else { $search_keyword = ""; }
        $result = "SELECT a.idx,
                    a.description,
                    a.remarks,
                    a.uom,
                    a.minimum_qty,
                    a.tariff_valuta,
                    a.type_tariff_value,
                    a.tariff_value,
                    a.type_tariff_value_next,
                    a.tariff_value_next,
                    a.status,
                    a.based_on_charge,
                    a.product_idx,
                    a.vehicle_type,
                    a.origin_idx,
                    a.destination_idx,
                    a.client_id,
                    a.lead_time,
                    a.latest,
                    (select status_name from status_based_on_charge where status = a.based_on_charge) as based_on,
                    (select product_name from master_product where idx = a.product_idx) as product_name,
                    (CASE WHEN (select product_title from master_product where idx = a.product_idx) = 1 THEN 'DARAT' ELSE 'UDARA' END) as product_title,
                    (select office_name from master_office where idx = a.office_idx) as office_name,
                    (select location_code from master_location where idx = a.origin_idx) as origin_code,
                    (select location_name from master_location where idx = a.origin_idx) as origin_name,
                    (select district_name from master_location where idx = a.origin_idx) as origin_district,
                    (select city_name from master_location where idx = a.origin_idx) as origin_city,
                    (select postal_code from master_location where idx = a.origin_idx) as origin_postcode,
                    (select location_name from master_location where idx = a.destination_idx) as dest_name,
                    (select district_name from master_location where idx = a.destination_idx) as dest_district,
                    (select city_name from master_location where idx = a.destination_idx) as dest_city,
                    (select postal_code from master_location where idx = a.destination_idx) as dest_postcode,
                    (select location_code from master_location where idx = a.destination_idx) as dest_code,
                    (CASE WHEN a.client_id = 0 THEN 'GENERAL'
                            ELSE
                                (select client_name from master_client where client_id = a.client_id)
                            END) as client_name
            FROM master_tariff_intercity a
            WHERE a.office_idx = " . $this->office ."
            AND a.status_delete = 'N' ". $search_keyword . "
            order by a.idx desc
            LIMIT ".$start.", ".$limit;
            $results = $this->db->query($result);
        return $results->result();
    }
    public function countTarifDom($keyword = null,$type = null)
    {
        if($keyword)
        {
            if($type == 1){
                $search_keyword = " and (IF(a.status = 1,'Aktif','Non Aktif') like '%$keyword%')";
            }elseif($type == 2){
                $search_keyword = " and ((select client_name from master_client where client_id = a.client_id) like '%$keyword%')";
            }elseif($type == 3){
                $search_keyword = " and ((select location_name from master_location where idx = a.origin_idx) like '%$keyword%')";
            }elseif($type == 4){
                $search_keyword = " and ((select district_name from master_location where idx = a.origin_idx) like '%$keyword%')";
            }elseif($type == 5){
                $search_keyword = " and ((select city_name from master_location where idx = a.origin_idx) like '%$keyword%')";
            }elseif($type == 6){
                $search_keyword = " and ((select postal_code from master_location where idx = a.origin_idx) like '%$keyword%')";
            }elseif($type == 7){
                $search_keyword = " and ((select location_code from master_location where idx = a.origin_idx) like '%$keyword%')";
            }elseif($type == 8){
                $search_keyword = " and ((select location_name from master_location where idx = a.destination_idx) like '%$keyword%')";
            }elseif($type == 9){
                $search_keyword = " and ((select district_name from master_location where idx = a.destination_idx) like '%$keyword%')";
            }elseif($type == 10){
                $search_keyword = " and ((select city_name from master_location where idx = a.destination_idx) like '%$keyword%')";
            }elseif($type == 11){
                $search_keyword = " and ((select postal_code from master_location where idx = a.destination_idx) like '%$keyword%')";
            }elseif($type == 12){
                $search_keyword = " and ((select location_code from master_location where idx = a.destination_idx) like '%$keyword%')";
            }elseif($type == 13){
                $search_keyword = " and ((select product_name from master_product where idx = a.product_idx) like '%$keyword%' or (CASE WHEN (select product_title from master_product where idx = a.product_idx) = 1 THEN 'DARAT' ELSE 'UDARA' END) like '%$keyword%')";
            }elseif($type == 14){
                $search_keyword = " and (a.description like '%$keyword%')";
            }else{
                $search_keyword = " and (a.tariff_value like '%$keyword%' or a.tariff_value_next like '%$keyword%')";
            }
        }else { $search_keyword = ""; }
        $result = "SELECT a.idx
            FROM master_tariff_intercity a
            WHERE a.office_idx = " . $this->office ."
            AND a.status_delete = 'N' ". $search_keyword . "
            order by a.idx desc";
            $results = $this->db->query($result);
        return $results->num_rows();
    }
    // QUERY TARIF PAGINATION END
    public function countTarif($keyword = null)
    {
        if($keyword)
        {
            $search_keyword = " and ((select location_name from master_location where idx = a.origin_idx) like   '%$keyword%' or
            (select location_name from master_location where idx = a.destination_idx) like '%$keyword%' or
            (CASE WHEN a.client_id = 0 THEN 'GENERAL'
            ELSE
                (select client_name from master_client where client_id = a.client_id)
            END) like '%$keyword%' or
            (CASE WHEN (select product_title from master_product where idx = a.product_idx) = 1 THEN 'DARAT' ELSE 'UDARA' END) like '%$keyword%' or
            (select product_name from master_product where idx = a.product_idx) like '%$keyword%' or
            (select status_name from status_based_on_charge where status = a.based_on_charge) like '%$keyword%')";
        }
        else { $search_keyword = ""; }
        $result = $this->db->query("select idx
                                    from master_tariff_intercity a
									where office_idx = " . $this->office . $search_keyword . "
                                    order by idx desc")->num_rows();
        return $result;
    }
    public function search_price($destination)
    {
        if ($destination !== ''){
            $this->db->select('*')
            ->from('master_tariff_intercity a')
            ->join('master_location b','a.destination_idx = b.idx', 'left')
            ->like('b.location_name', $destination , 'both')
            ->order_by('b.location_name', 'ASC');
            return $this->db->get()->result();
        }
    }
    // SELECT2 SERVERSIDE
    public function getDataAjaxRemote($search, $type)
    {
        $this->db->select('idx, location_name, location_code, district_name, city_name,postal_code');
        $this->db->from('master_location');
        $this->db->where(['status'=> 1, 'status_destination'=>1]);
        $this->db->group_start();
        $this->db->like('location_name', $search);
        $this->db->or_like('location_code', $search);
        $this->db->or_like('district_name', $search);
        $this->db->or_like('city_name', $search);
        $this->db->or_like('postal_code', $search);
        $this->db->group_end();
        if($type == 'data'){
            return $this->db->get()->result_array();
        }else{
            return $this->db->get()->num_rows();
        }
    }
    // SELECT2 SERVERSIDE END
    // SELECT2 SERVERSIDE
    public function getDataAjaxRemoteId($search, $type)
    {
        $this->db->select('idx, location_name, location_code, district_name, province_name, city_name,postal_code');
        $this->db->from('master_location');
        $this->db->where('idx', $search);
        if($type == 'data'){
            return $this->db->get()->result_array();
        }else{
            return $this->db->get()->num_rows();
        }
    }
    // SELECT2 SERVERSIDE END

    // COMODITY AJAX
    // start datatables

    private function _get_rangeDetail_query($ot_id) {
        $select_column = array(
            'a.*'
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
        $this->db->from('master_ot_detail a');
        $this->db->where(['a.ot_id'=> $ot_id, 'a.status' => 1]);
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

    function get_rangeDetail($ot_id) {
        $this->_get_rangeDetail_query($ot_id);
        if(@$_POST['length'] != -1)
        $this->db->limit(@$_POST['length'], @$_POST['start']);
        $query = $this->db->get()->result();
        return $query;
    }

    function count_filtered_rangeDetail($ot_id) {
        $this->_get_rangeDetail_query($ot_id);
        $query = $this->db->get()->num_rows();
        return $query;
    }

    function count_all_rangeDetail($ot_id) {
        $this->db->from('master_ot_detail a');
        $this->db->where(['a.ot_id'=> $ot_id, 'a.status' => 1]);
        return $this->db->count_all_results();
    }
    // end datatables
    // COMODITY AJAX END
    public function search_dest($destination)
    {
        $this->db->like('location_name', $destination , 'both');
        $this->db->order_by('location_name', 'ASC');
        $this->db->limit(10);
        return $this->db->get('master_location')->result();
    }
    public function listTariff($limit, $start = 0, $keyword = null)
    {
        if($keyword)
        {
            $search_keyword = " and ((select location_name from master_location where idx = a.origin_idx) like   '%$keyword%' or
            (select location_name from master_location where idx = a.destination_idx) like '%$keyword%' or
            (CASE WHEN a.client_id = 0 THEN 'GENERAL'
            ELSE
                (select client_name from master_client where client_id = a.client_id)
            END) like '%$keyword%' or
            (CASE WHEN (select product_title from master_product where idx = a.product_idx) = 1 THEN 'DARAT' ELSE 'UDARA' END) like '%$keyword%' or
            (select product_name from master_product where idx = a.product_idx) like '%$keyword%' or
            (select status_name from status_based_on_charge where status = a.based_on_charge) like '%$keyword%')";
        }
        else { $search_keyword = ""; }
        $result = $this->db->query("select a.idx,
                                    a.description,
                                    a.remarks,
                                    a.uom,
                                    a.minimum_qty,
                                    a.tariff_valuta,
                                    a.type_tariff_value,
                                    a.tariff_value,
                                    a.type_tariff_value_next,
                                    a.tariff_value_next,
                                    a.status,
                                    a.based_on_charge,
                                    a.product_idx,
                                    a.sub_product_idx,
                                    a.vehicle_type,
                                    a.origin_idx,
                                    a.destination_idx,
                                    a.client_id,
                                    (select status_name from status_based_on_charge where status = a.based_on_charge) as based_on,
                                    (select product_name from master_product where idx = a.product_idx) as product_name,
                                    (CASE WHEN (select product_title from master_product where idx = a.product_idx) = 1 THEN 'DARAT' ELSE 'UDARA' END) as product_title,
                                    (select office_name from master_office where idx = a.office_idx) as office_name,
                                    (select location_name from master_location where idx = a.origin_idx) as origin_name,
                                    (select location_name from master_location where idx = a.destination_idx) as destination_name,
                                    (CASE WHEN a.client_id = 0 THEN 'GENERAL'
                                            ELSE
                                                (select client_name from master_client where client_id = a.client_id)
                                            END) as client_name
                                    from master_tariff_intercity a
									where office_idx = " . $this->office . $search_keyword . "
                                    order by idx desc
                                    LIMIT $start, $limit")->result_array();
        return $result;
    }
    public function queryListMTariff($keyword = null)
    {
        if($keyword)
        {
            $search_keyword = " and ((CASE WHEN a.client_id = 0 THEN 'GENERAL'
														ELSE
															(select client_name from master_client where client_id = a.client_id)
														END) like '%$keyword%' or
                                    (select location_name from master_location where idx = a.origin_idx) like '%$keyword%' or
                                    (select location_name from master_location where idx = a.destination_idx) like '%$keyword%' or
                                    (select sub_product_name from master_sub_product where idx = a.sub_product_idx) like '%$keyword%' or
                                    (select product_name from master_product where idx = a.product_idx) like '%$keyword%' or
                                    (select vehicle_name from status_vehicle_type where status = a.vehicle_type) like '%$keyword%' or
                                    a.description like '%$keyword%' or
                                    (select status_name from status_based_on_charge where status = a.based_on_charge) like '%$keyword%' or
                                    a.uom like '%$keyword%' or
                                    a.minimum_qty like '%$keyword%')";
        } else { $search_keyword = ""; }
        $result = "select
                                                a.idx,
                                                a.description,
                                                a.remarks,
                                                a.uom,
                                                a.minimum_qty,
                                                a.tariff_valuta,
                                                a.type_tariff_value,
                                                a.tariff_value,
                                                a.type_tariff_value_next,
                                                a.tariff_value_next,
                                                a.status,
                                                a.based_on_charge,
                                                a.product_idx,
                                                a.sub_product_idx,
                                                a.vehicle_type,
                                                a.origin_idx,
                                                a.destination_idx,
                                                a.client_id,
                                                (select status_name from status_based_on_charge where status = a.based_on_charge) as based_on,
												(select product_name from master_product where idx = a.product_idx) as product_name,
												(select sub_product_name from master_sub_product where idx = a.sub_product_idx) as sub_product_name,
												(select vehicle_name from status_vehicle_type where status = a.vehicle_type) as vehicle_name,
                                                (select office_name from master_office where idx = a.office_idx) as office_name,
												(select location_name from master_location where idx = a.origin_idx) as origin_name,
												(select location_name from master_location where idx = a.destination_idx) as destination_name,
                                                (CASE WHEN a.client_id = 0 THEN 'GENERAL'
														ELSE
															(select client_name from master_client where client_id = a.client_id)
														END) as client_name
                                            from master_tariff a
                                            where a.office_idx = " . $this->office . $search_keyword . "
                                            order by a.idx asc";
        return $result;
    }
    public function insertOtRange($data, $dataRangeDet)
    {
        $this->db->trans_begin();
        $this->db->insert('master_ot', $data);
        $this->db->insert_batch('master_ot_detail', $dataRangeDet);
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
    public function updateOtRange($id, $data)
    {
        $this->db->trans_begin();
        $this->db->where('idx', $id);
        $this->db->update('master_ot', $data);
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
    public function insertOtDetail($dataRangeDet, $idxLast="", $updateLastRange="")
    {
        $this->db->trans_begin();
        $this->db->insert('master_ot_detail', $dataRangeDet);
        if(!empty($idxLast)){
            $this->db->where('idx', $idxLast);
            $this->db->update('master_ot_detail', $updateLastRange);
        }
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
    public function updateOtDetail($id, $updateRange)
    {
        $this->db->trans_begin();
        $this->db->where('idx', $id);
        $this->db->update('master_ot_detail', $updateRange);
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
    public function deleteOtDetail($id, $updateRange, $idxLast="", $updateLastRange="")
    {
        $this->db->trans_begin();
        $this->db->where('idx', $id);
        $this->db->update('master_ot_detail', $updateRange);
        if(!empty($idxLast)){
            $this->db->where('idx', $idxLast);
            $this->db->update('master_ot_detail', $updateLastRange);
        }
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
    public function importMTariff($insert,$insertD="")
    {
        $this->db->trans_begin();
        $this->db->query($insert);
        $this->db->query($insertD);
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
    public function importMTariffUpdate($update)
    {
        $this->db->trans_begin();
        $this->db->query($update);
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
    public function editOtDetail($id)
    {
        $this->db->select('a.*')
        ->from('master_ot_detail a')
        ->where('a.idx', $id);
        $get = $this->db->get()->row_array();
        return $get;
    }
    public function editTariff($id)
    {
        $this->db->select('a.*')
        ->from('master_tariff_intercity a')
        ->where('a.idx', $id);
        $get = $this->db->get();
        $get2 = $get->row_array();
        $inject = [
            'origin_enidx'      => $this->secure->encrypt_url($get2['origin_idx']),
            'destination_enidx' => $this->secure->encrypt_url($get2['destination_idx']),
            'product_enidx'     => $this->secure->encrypt_url($get2['product_idx']),
            'client_enid'       => $this->secure->encrypt_url($get2['client_id']),
        ];
        $combine = array_merge($get2,$inject);
        return $combine;
    }
    public function get_last_range($ot_id)
    {
        $column = [
            'idx',
            'min_hour',
            'max_hour',
            'type_of_value',
            'value',
            'status_end',
            'status_um'
        ];
        $this->db->select($column)
        ->from('master_ot_detail')
        ->where(['status' => 1, 'ot_id' => $ot_id])
        ->order_by('idx', 'DESC');
        $result = $this->db->get()->row_array();
        // $result = $this->db->get_compiled_select();
        return $result;
    }
    public function get_last_range2($ot_id, $id)
    {
        $column = [
            'idx',
            'min_hour',
            'max_hour',
            'type_of_value',
            'value',
            'status_end',
            'status_um'
        ];
        $this->db->select($column)
        ->from('master_ot_detail')
        ->where(['status' => 1, 'ot_id' => $ot_id, 'idx <> '=>$id, 'idx < '=> $id])
        ->order_by('idx', 'DESC');
        $result = $this->db->get()->row_array();
        // $result = $this->db->get_compiled_select();
        return $result;
    }
    public function getDest($id)
    {
        $this->db->select('a.*')
        ->from('master_location a')
        ->where('a.idx', $id);
        $get = $this->db->get();
        return $get->row_array();
    }
    public function listMTariff()
    {
        $dataMTariff = $this->db->query("select
                                                a.*,
                                                c.status_name,
                                                d.office_name,
                                                e.product_name,
                                                (select status_name from status_tarif_type where status = a.tariff_type) as tariff_type_name,
                                                (select status_name from status_tarif_type where status = a.tariff_type_2) as tariff_type_name2,
                                                (select status_name from status_tarif_type where status = a.tariff_type_3) as tariff_type_name3,
                                                (CASE
                                                    when a.client_id = 0 then 'GENERAL'
                                                    else
                                                        (select f.client_name
                                                            from master_client f
                                                            where f.client_id=a.client_id)
                                                END) as client_name,
                                                (CASE
                                                    when a.packages_idx = 0 then 'ALL SIZE'
                                                    else
                                                        (select packages_name
                                                            from master_packages
                                                            where idx=a.packages_idx)
                                                END) as packages_name,
                                                (CASE
                                                    when a.based_on_charge = 1 then 'GROSS WEIGHT'
                                                    when a.based_on_charge = 2 then 'VOLUME'
                                                    when a.based_on_charge = 3 then 'DISTANCE'
                                                    when a.based_on_charge = 4 then 'SHIPMENT'
                                                    when a.based_on_charge = 5 then 'DISTANCE PERIOD'
                                                end) as based_name
                                            from master_tariff a, status_shipment_type c, master_office d, master_product e
                                            where a.shipment_type=c.status
                                                and a.office_idx=d.idx
                                                and a.product_idx=e.idx
                                                and a.office_idx=$this->office
                                            order by a.idx desc")->result_array();
        return $dataMTariff;
    }
    public function updateMTariff($data, $id_tariff)
    {
        $this->db->trans_begin();
        $this->db->where('idx', $id_tariff);
        $this->db->update('master_tariff_intercity', $data);
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
    function getPackages()
    {
        $this->db->select('idx, packages_name')
        ->from('master_packages')
        ->where('status', 1);
        $hasil = $this->db->get();
        return $hasil->result();
    }
    function getShipmentType()
    {
        $this->db->select('*')
        ->from('status_shipment_type');
        $hasil = $this->db->get();
        return $hasil->result();
    }
    function getBasedOn()
    {
        $this->db->select('*')
        ->from('status_based_on_charge');
        $hasil = $this->db->get();
        return $hasil->result();
    }
    function getOffice()
    {
        $this->db->select('idx, office_name')
        ->from('master_office')
        ->where('status', 1);
        $query = $this->db->get();
        return $query->result();
    }
    function getProduct()
    {
        $this->db->select('idx, product_name, product_code, (CASE WHEN product_title = 1 THEN "DARAT" ELSE "UDARA" END) as product_title')
        ->from('master_product')
        ->where(['status_feature' => 2, 'status' => 1, 'product_tariff_type' => 2]);
        $query = $this->db->get();
        return $query->result();
    }
    function getLocation()
    {
        $this->db->select('idx,location_name')
        ->from('master_location')
        ->where('status', 1);
        $query = $this->db->get();
        return $query->result();
    }
    function getOrigin()
    {
        $this->db->select('*')
        ->from('master_location')
        ->where(['status'=> 1,'office_main_location'=>1]);
        $query = $this->db->get();
        return $query->result();
    }
    function getOriginx()
    {
        $this->db->select('*')
        ->from('master_location')
        ->where(['status'=> 1,'status_origin'=>1]);
        $query = $this->db->get();
        return $query->result();
    }
    function getClient()
    {
        $this->db->select('client_id, client_name, client_code');
        $this->db->from('master_client');
        if($this->offType==3){
            $this->db->where(['client_id' => 0,'status' => 1,'status_delete' => 'N']);
        }elseif($this->offType==2){
            $this->db->group_start();
            $this->db->where(['client_id' => 0,'status' => 1,'status_delete' => 'N']);
            $this->db->group_end();
            $this->db->or_group_start();
            $this->db->where(['office_idx' => $this->office,'status' => 1,'status_delete' => 'N']);
            $this->db->group_end();
        }else{
            $this->db->where(['office_idx' => $this->office,'status' => 1,'status_delete' => 'N']);
        }
        $query = $this->db->get()->result();
        return $query;
    }
    function getClientCounter($status)
    {
        $this->db->select('client_id, client_name, client_code');
        $this->db->from('master_client');
        if($this->offType==3){
            $this->db->where(['client_id' => 0,'status' => 1,'status_delete' => 'N']);
        }elseif($this->offType==2){
            $this->db->group_start();
            $this->db->where(['client_id' => 0,'status' => 1,'status_delete' => 'N']);
            $this->db->group_end();
            $this->db->or_group_start();
            $this->db->where(['office_idx' => $this->office,'status' => 1,'status_delete' => 'N']);
            $this->db->group_end();
        }else{
            if($status==0){
                $this->db->where(['client_id' => 0,'office_idx' => $this->office,'status' => 1,'status_delete' => 'N']);
            }else{
                $this->db->where(['office_idx' => $this->office,'status' => 1,'status_delete' => 'N']);
            }
        }
        $query = $this->db->get()->result();
        return $query;
    }
    function getStatusTariff($counter)
    {
        $this->db->select('status_enable_tariff');
        $this->db->from('master_counter');
        $this->db->where(['idx'=> $counter,'office_idx' => $this->office,'status' => 1,'status_delete' => 'N']);
        $query = $this->db->get()->row_array();
        return $query;
    }
    public function changeStatusOtHead($data, $id_tariff)
    {
        $this->db->where('idx', $id_tariff);
        $this->db->update('master_ot', $data);
        if($this->db->trans_status() === FALSE)
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
    public function deleteTariff($id)
    {
        $this->db->trans_begin();
        $this->db->where('idx', $id);
        $this->db->update('master_tariff', ['status_delete' => 'Y']);
        if($this->db->trans_status() === FALSE)
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

    public function tarifJTE()
    {
        $query = "
                select DISTINCT(j.city) as city,
                    (SELECT
                        COUNT(idx) as idx
                    FROM master_location
                    WHERE city_name = city
                    ORDER BY idx asc LIMIT 1) as count_idx,
                    (select province from tariff_jte where city = j.city order by idx desc LIMIT 1) as province,
                    (select value from tariff_jte where city = j.city order by idx desc LIMIT 1) as price,
                    (select leadtime from tariff_jte where city = j.city order by idx desc LIMIT 1) as leadtime,
                    (select latest from tariff_jte where city = j.city order by idx desc LIMIT 1) as latest,
                    (select minimum from tariff_jte where city = j.city order by idx desc LIMIT 1) as minimum
            from tariff_jte2 j
            where j.idx > 0
            having count_idx = 0
            order by count_idx asc
        ";
        $result = $this->db->query($query);
        return $result->result();
    }

    public function tarifJTEx($city)
    {
        $query = "
                select DISTINCT(j.city) as city,
                    (SELECT
                        COUNT(idx) as idx
                    FROM master_location
                    WHERE city_name = city
                    ORDER BY idx asc LIMIT 1) as count_idx,
                    (select province from tariff_jte where city = j.city order by idx desc LIMIT 1) as province,
                    (select value from tariff_jte where city = j.city order by idx desc LIMIT 1) as price,
                    (select leadtime from tariff_jte where city = j.city order by idx desc LIMIT 1) as leadtime,
                    (select latest from tariff_jte where city = j.city order by idx desc LIMIT 1) as latest,
                    (select minimum from tariff_jte where city = j.city order by idx desc LIMIT 1) as minimum
            from tariff_jte2 j
            where j.idx > 0
            and j.city = '".$city."'
            having count_idx = 0
            order by count_idx asc
        ";
        $result = $this->db->query($query);
        return $result->result();
    }

    public function cekIdx($city)
    {
        $query = "
            SELECT idx
            FROM master_location
            WHERE city_name = '".$city."'
            ORDER BY idx asc
        ";
        $result = $this->db->query($query);
        return $result->result();
    }

    public function cekIdxx($city, $district="")
    {
        $query = "
            SELECT 
                idx,
                location_name,
                district_name
            FROM master_location
            WHERE city_name LIKE '%".$city."%'
        ";
        $query2 = "
            AND district_name = '".$district."'
        ";
        $query3 = "
            ORDER BY idx asc
        ";
        if(empty($district)){
            $exe = $query.$query3;
        }else{
            $exe = $query.$query2.$query3;
        }
        $result = $this->db->query($exe);
        return $result->result();
    }

    public function insertTariffJTE($query)
    {
        $this->db->trans_begin();
        $cek = $this->db->query($query);
        if($this->db->trans_status() === FALSE)
    	{
    		$this->db->trans_rollback();
    		return $cek;
    	}
    	else
    	{
    		$this->db->trans_commit();
    		return true;
    	}
    }

    public function insertTariffxx($data)
    {
        $this->db->trans_begin();
        $cek = $this->db->insert('master_tariff_intercity',$data);
        if($this->db->trans_status() === FALSE)
    	{
    		$this->db->trans_rollback();
    		return $cek;
    	}
    	else
    	{
    		$this->db->trans_commit();
    		return true;
    	}
    }
    public function getClientId($client_code)
    {
        $this->db->select('*')
        ->from('master_client a')
        ->where('a.client_code', $client_code);
        $result = $this->db->get();
        if($result->num_rows() > 0){
            return $result->row_array();
        }else{
            return 0;
        }
    }
    public function getProductId($service)
    {
        $this->db->select('a.*, (CASE WHEN a.product_title = 1 THEN "DARAT" ELSE "UDARA" END) as product_titlex')
        ->from('master_product a')
        ->where('a.product_code', $service);
        $result = $this->db->get();
        if($result->num_rows() > 0){
            return $result->row_array();
        }else{
            return 0;
        }
    }
    public function locationCode($loc_code)
    {
        $this->db->select('*')
        ->from('master_location a')
        ->where('a.location_code', $loc_code);
        $result = $this->db->get();
        if($result->num_rows() > 0){
            return $result->row_array();
        }else{
            return 0;
        }
    }
    public function locationIdx($loc_code)
    {
        $this->db->select('*')
        ->from('master_location a')
        ->where('a.location_code', $loc_code);
        $result = $this->db->get();
        if($result->num_rows() > 0){
            return $result->row_array();
        }else{
            return 0;
        }
    }
    public function getBasOn($based_on_charge)
    {
        $this->db->select('*')
        ->from('status_based_on_charge a')
        ->where("REPLACE(`a`.`status_name`,' ','') = '$based_on_charge'", NULL);
        $result = $this->db->get();
        if($result->num_rows() > 0){
            return $result->row_array();
        }else{
            return 0;
        }
    }
    public function cekTarif($cek_exsist)
    {
        $this->db->select('idx')
        ->from('master_ot')
        ->where($cek_exsist);
        $result = $this->db->get()->row_array();
        return $result;
    }
    public function getTarifDom($client)
    {
        $this->db->select('a.*')
        ->from('master_tariff_intercity a')
        ->where(['a.office_idx'=> $this->office,'a.status_delete'=> 'N','client_id' => $client]);
        $result = $this->db->get();
        return $result->result();
    }
    public function origin_location()
    {
        $this->db->select('origin_location')
        ->from('master_office')
        ->where('idx', $this->office);
        $result = $this->db->get()->row_array();
        return $result;
    }
}
?>