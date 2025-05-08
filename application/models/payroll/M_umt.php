<?php 
defined('BASEPATH') or exit('No direct script access allowed');

class M_umt extends CI_Model
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
            'a.*'
            
        );//set column field database for datatable select
        $column_order = array(
            null,
            'a.allowance_code',
            'a.total_item',
            'a.grandtotal',
            'a.description',
        ); //set column field database for datatable orderable
        $column_search = array(
            'a.allowance_code',
            'a.total_item',
            'a.grandtotal',
            'a.description',
        ); //set column field database for datatable searchable
        $order = array('a.idx' => 'asc'); // default order
        $this->db->select($select_column);
        $this->db->from('allowance_half a');
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
        $this->db->from('allowance_half a');
        $this->db->where('a.company_idx', $this->office);
        return $this->db->count_all_results();
    }
    // end datatables
    // CONTOH LAIN MODEL2 DATATABLE SERVERSIDE USER END

    // CONTOH LAIN MODEL2 DATATABLE SERVERSIDE USER
    // start datatables 
  
    private function _get_datatables_query2($all_id, $from, $to) {
        $office = (int)$this->office;
        $select_column = array(
            'b.cal_date as calendar_date',
            'a.employee_id',
            'a.employee_name',
            'a.employee_code',
            'a.office_shift AS shift_idx',
            '(SELECT shift_mode FROM master_shift WHERE idx = a.office_shift) AS shift_mode',
            'a.meal_allowance as basic_meal',
            "(SELECT meal_allowance FROM allowance_half_detail WHERE allowh_date = b.cal_date AND employee_id = a.employee_id AND company_idx = $office AND allowh_id = $all_id) as meal_allowance",
            "(SELECT meal_cutting FROM allowance_half_detail WHERE allowh_date = b.cal_date AND employee_id = a.employee_id AND company_idx = $office AND allowh_id = $all_id) as meal_cutting",
            "(SELECT rapel_sub FROM allowance_half_detail WHERE allowh_date = b.cal_date AND employee_id = a.employee_id AND company_idx = $office AND allowh_id = $all_id) as rapel_sub",
            "(SELECT rapel_sub_date FROM allowance_half_detail WHERE allowh_date = b.cal_date AND employee_id = a.employee_id AND company_idx = $office AND allowh_id = $all_id) as rapel_sub_date",
            "(SELECT incentive_allowance FROM allowance_half_detail WHERE allowh_date = b.cal_date AND employee_id = a.employee_id AND company_idx = $office AND allowh_id = $all_id) as incentive_allowance",
            "(SELECT rapel_inc FROM allowance_half_detail WHERE allowh_date = b.cal_date AND employee_id = a.employee_id AND company_idx = $office AND allowh_id = $all_id) as rapel_inc",
            "(SELECT rapel_inc_date FROM allowance_half_detail WHERE allowh_date = b.cal_date AND employee_id = a.employee_id AND company_idx = $office AND allowh_id = $all_id) as rapel_inc_date",
            "(SELECT dlk_allowance FROM allowance_half_detail WHERE allowh_date = b.cal_date AND employee_id = a.employee_id AND company_idx = $office AND allowh_id = $all_id) as dlk_allowance",
            "(SELECT rapel_dlk FROM allowance_half_detail WHERE allowh_date = b.cal_date AND employee_id = a.employee_id AND company_idx = $office AND allowh_id = $all_id) as rapel_dlk",
            "(SELECT rapel_dlk_date FROM allowance_half_detail WHERE allowh_date = b.cal_date AND employee_id = a.employee_id AND company_idx = $office AND allowh_id = $all_id) as rapel_dlk_date",
            "(SELECT overtime_allowance FROM allowance_half_detail WHERE allowh_date = b.cal_date AND employee_id = a.employee_id AND company_idx = $office AND allowh_id = $all_id) as overtime_allowance",
            "(SELECT rapel_ot FROM allowance_half_detail WHERE allowh_date = b.cal_date AND employee_id = a.employee_id AND company_idx = $office AND allowh_id = $all_id) as rapel_ot",
            "(SELECT rapel_ot_date FROM allowance_half_detail WHERE allowh_date = b.cal_date AND employee_id = a.employee_id AND company_idx = $office AND allowh_id = $all_id) as rapel_ot_date",
            "(SELECT allowance_value FROM allowance_half_detail WHERE allowh_date = b.cal_date AND employee_id = a.employee_id AND company_idx = $office AND allowh_id = $all_id) as allowance_value",
            "(SELECT description FROM allowance_half_detail WHERE allowh_date = b.cal_date AND employee_id = a.employee_id AND company_idx = $office AND allowh_id = $all_id) as description",
            "(SELECT status_absen FROM allowance_half_detail WHERE allowh_date = b.cal_date AND employee_id = a.employee_id AND company_idx = $office AND allowh_id = $all_id) as status_absen",
            "(SELECT check_in FROM attendance_employee WHERE attendance_date = b.cal_date AND employee_id = a.employee_id AND company_idx = $office) as check_in",
            "(SELECT status_piket FROM attendance_employee WHERE attendance_date = b.cal_date AND employee_id = a.employee_id AND company_idx = $office) as status_piket",
            "(SELECT check_out FROM attendance_employee WHERE attendance_date = b.cal_date AND employee_id = a.employee_id AND company_idx = $office) as check_out",
            "(SELECT sub_type FROM submission_detail WHERE sub_date = b.cal_date AND employee_id = a.employee_id AND company_idx = $office) as sub_type",
            "(SELECT status_confirm FROM submission_detail WHERE sub_date = b.cal_date AND employee_id = a.employee_id AND company_idx = $office) as status_confirm"
        );//set column field database for datatable select
        $column_order = array(
            null
        ); //set column field database for datatable orderable
        $column_search = array(
            'a.employee_name'
        ); //set column field database for datatable searchable
        $order = array(
            'a.employee_id' => '',
            'b.cal_date' => ''
        ); // default order must have key and value, if not use ASC/DESC just set the value with empty string
        $this->db->select($select_column);
        $this->db->from('master_employee a');
        $this->db->join('calendar b', 'x', 'cross');
        $this->db->where(["b.cal_date BETWEEN '$from' AND '$to' "=>null, 'a.status_delete' => 0, 'a.company_idx' => $this->office]);
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
            foreach($order as $k => $v){
                $this->db->order_by($k, $v);
            }
        }
    }
    function get_datatables2($all_id, $from, $to) {
        $this->_get_datatables_query2($all_id, $from, $to);
        // if(@$_POST['length'] != -1)
        // $this->db->limit(@$_POST['length'], @$_POST['start']);
        // $query = $this->db->get_compiled_select();
        $query = $this->db->get()->result_array();
        $format_data = [];
        foreach($query as $i=>$v){
            $tanggal = $v['calendar_date'];
            $employee_name = $v['employee_name'];
            if (!isset($format_data[$employee_name])) {
                // $attendance[$record->employee_name] = array_fill_keys($dates, 0); // Initialize dates with 0
                // $format_data[$employee_name]['umt_total'] = 0;
                $countKehadiran = 0;
                $otTotal = 0;
                $pkTotal = 0;
                $rapelTotal = 0;
                $umtTotal = 0;
                $umtCutTotal = 0;
                $insTotal = 0;
                $dlkTotal = 0;
                $gTotal = 0;
                $check_in_count = 0;
                $check_out_count = 0;
            }
            $check_in = $v['check_in'];
            $check_out = $v['check_out'];
            $sub_type = (int)$v['sub_type'];
            $status_confirm = (int)$v['status_confirm'];
            $status_piket = (int)$v['status_piket'];
            $shift_mode = (int)$v['shift_mode'];
            $status_absen = (int)$v['status_absen'];
            if(empty($check_out) && empty($check_in)){
                if($status_confirm==1){
                    if($sub_type==1){
                        $countKehadiran += 1;
                        $status_absenx = 2;
                    }elseif($sub_type==2){
                        $countKehadiran += 1;
                        $status_absenx = 3;
                    }elseif($sub_type==3){
                        $status_absenx = 4;
                    }else{
                        $status_absenx = $status_absen;
                    }
                }else{
                    $status_absenx = $status_absen;
                }
            }elseif(empty($check_in)){
                $status_absenx = 8;
            }else{
                $status_absenx = $status_absen;
            }
            // if($v['employee_id']==1 && $tanggal == '2024-10-03'){
            //     var_dump('<pre>');var_dump($countKehadiran);var_dump($sub_type);var_dump($status_confirm);die;
            // }
            $basic_mealx = (float)$v['basic_meal'];
            $meal_allowancex = (float)$v['meal_allowance'];
            $meal_cuttingx = (float)$v['meal_cutting'];
            $rapel_subx = (float)$v['rapel_sub'];
            $rapel_otx = (float)$v['rapel_ot'];
            $rapel_incx = (float)$v['rapel_inc'];
            $rapel_dlkx = (float)$v['rapel_dlk'];
            $rapel_sub_date = $v['rapel_sub_date'];
            $rapel_ot_date = $v['rapel_ot_date'];
            $rapel_inc_date = $v['rapel_inc_date'];
            $rapel_dlk_date = $v['rapel_dlk_date'];
            $incentive_allowancex = (float)$v['incentive_allowance'];
            $dlk_allowancex = (float)$v['dlk_allowance'];
            if($status_piket==1 || $shift_mode==1){
                $piket_allowancex = (float)$v['overtime_allowance'];
                $piket_allowance = number_format($v['overtime_allowance']);
                $piket_allowanceV = empty($piket_allowance)?'':"<br>PK: $piket_allowance";
                $overtime_allowancex = 0;
                $overtime_allowance = number_format(0);
                $overtime_allowanceV = empty($overtime_allowance)?'':"<br>OT: $overtime_allowance";
            }else{
                $piket_allowancex = 0;
                $piket_allowance = number_format(0);
                $piket_allowanceV = empty($piket_allowance)?'':"<br>PK: $piket_allowance";
                $overtime_allowancex = (float)$v['overtime_allowance'];
                $overtime_allowance = number_format($v['overtime_allowance']);
                $overtime_allowanceV = empty($overtime_allowance)?'':"<br>OT: $overtime_allowance";
            }
            $allowance_valuex = (float)$v['allowance_value'];
            $basic_meal = number_format($v['basic_meal']);
            $meal_allowance = number_format($v['meal_allowance']);
            $meal_cutting = number_format($v['meal_cutting']);
            $incentive_allowance = number_format($v['incentive_allowance']);
            $dlk_allowance = number_format($v['dlk_allowance']);
            $rapel_sub = number_format($v['rapel_sub']);
            $rapel_ot = number_format($v['rapel_ot']);
            $rapel_dlk = number_format($v['rapel_dlk']);
            $rapel_inc = number_format($v['rapel_inc']);
            $allowance_value = number_format($v['allowance_value']);
            $check_inV = empty($check_in)?'<span class="text-danger">xx:xx</span> - ':date('H:i', strtotime($check_in)).' - ';
            $check_outV = empty($check_out)?(empty($check_in)?'<span class="text-danger">xx:xx</span>':'<span class="text-danger">xx:xx</span>'):date('H:i', strtotime($check_out));
            $meal_allowanceV = empty($meal_allowance)?'':"<br>UM: $meal_allowance";
            $incentive_allowanceV = empty($incentive_allowance)?'':"<br>INS: $incentive_allowance";
            $dlk_allowanceV = empty($dlk_allowance)?'':"<br>DLK: $dlk_allowance";
            $rapel_subV = empty($rapel_subx)?'':"<br>RPL_UM: $rapel_sub";
            $rapel_otV = empty($rapel_otx)?'':"<br>RPL_OT/PK: $rapel_ot";
            $rapel_dlkV = empty($rapel_dlkx)?'':"<br>RPL_DLK: $rapel_dlk";
            $rapel_incV = empty($rapel_incx)?'':"<br>RPL_INS: $rapel_inc";
            // $isiNya = "$tanggal";
            $isiNya = "$check_inV$check_outV$meal_allowanceV$incentive_allowanceV$dlk_allowanceV$overtime_allowanceV$piket_allowanceV$rapel_subV$rapel_otV$rapel_dlkV$rapel_incV";
            
            $check_in_count += empty($check_in)?0:1; 
            $check_out_count += empty($check_out)?0:1; 
            $otTotal += $overtime_allowancex;
            $pkTotal += $piket_allowancex;
            $umtTotal += $meal_allowancex;
            $umtCutTotal += $meal_cuttingx;
            $insTotal += $incentive_allowancex;
            $dlkTotal += $dlk_allowancex;
            $rapelTotal += $rapel_subx + $rapel_otx + $rapel_dlkx + $rapel_incx;
            $gTotalCh = ($check_in_count>$check_out_count?$check_in_count:$check_out_count)+$countKehadiran;
            $gTotal += $allowance_valuex;
            $format_data[$employee_name][$tanggal] = [$isiNya, $status_absenx];
            $format_data[$employee_name]['total_check'] = $gTotalCh;
            $format_data[$employee_name]['basic_meal'] = $basic_meal;
            $format_data[$employee_name]['ot_total'] = number_format($otTotal);
            // $format_data[$employee_name]['umt_total'] = number_format($umtTotal);
            $format_data[$employee_name]['insentif_total'] = number_format($insTotal);
            $format_data[$employee_name]['dlk_total'] = number_format($dlkTotal);
            $format_data[$employee_name]['rapel_total'] = number_format($rapelTotal);
            $format_data[$employee_name]['pk_total'] = number_format($pkTotal);
            $format_data[$employee_name]['umt_cut_total'] = number_format($umtCutTotal);
            $format_data[$employee_name]['grand_total'] = number_format($gTotal);
        }
        foreach($format_data as $employee_name => $data){
            $total_check = $data['total_check'];
            $basic_meal_fix = $data['basic_meal'];
            $ot_total = $data['ot_total'];
            $rapel_total = $data['rapel_total'];
            $total_ins = $data['insentif_total'];
            $total_dlk = $data['dlk_total'];
            $pk_total = $data['pk_total'];
            $total_umtC = $data['umt_cut_total'];
            $grand_total = $data['grand_total'];
            unset($format_data[$employee_name]['total_check'], $format_data[$employee_name]['basic_meal'], $format_data[$employee_name]['ot_total'], $format_data[$employee_name]['pk_total'], $format_data[$employee_name]['umt_cut_total'], $format_data[$employee_name]['insentif_total'], $format_data[$employee_name]['dlk_total'], $format_data[$employee_name]['rapel_total'], $format_data[$employee_name]['grand_total']); // Remove temporarily
            $format_data[$employee_name]['total_check'] = [$total_check, 99]; // Re-add to ensure it's last
            $format_data[$employee_name]['basic_meal'] = [$basic_meal_fix, 99]; // Re-add to ensure it's last
            $format_data[$employee_name]['ot_total'] = [$ot_total, 99]; // Re-add to ensure it's last
            // $format_data[$employee_name]['umt_total'] = $total_umt; // Re-add to ensure it's last
            $format_data[$employee_name]['insentif_total'] = [$total_ins, 99]; // Re-add to ensure it's last
            $format_data[$employee_name]['dlk_total'] = [$total_dlk, 99]; // Re-add to ensure it's last
            $format_data[$employee_name]['rapel_total'] = [$rapel_total, 99]; // Re-add to ensure it's last
            $format_data[$employee_name]['pk_total'] = [$pk_total, 99]; // Re-add to ensure it's last
            $format_data[$employee_name]['umt_cut_total'] = [$total_umtC, 99]; // Re-add to ensure it's last
            $format_data[$employee_name]['grand_total'] = [$grand_total, 99]; // Re-add to ensure it's last
        }
        return $format_data;
    }
    // function count_filtered2($all_id, $from, $to) {
    //     $this->_get_datatables_query2($all_id, $from, $to);
    //     $query = $this->db->get();
    //     return $query->num_rows();
    // }
    // function count_all2($all_id, $from, $to) {
    //     $this->db->from('master_employee a');
    //     $this->db->join('calendar b', 'x', 'cross');
    //     $this->db->where(["b.cal_date BETWEEN '$from' AND '$to' "=>null, 'a.status_delete' => 0]);
    //     return $this->db->count_all_results();
    // }
    // end datatables
    // CONTOH LAIN MODEL2 DATATABLE SERVERSIDE USER END

    // CONTOH LAIN MODEL2 DATATABLE SERVERSIDE USER
    // start datatables 
  
    private function _get_detail_query($all_id, $from, $to) {
        $office = (int)$this->office;
        $select_column = array(
            'b.cal_date as calendar_date',
            'a.employee_id',
            'a.employee_name',
            'a.employee_code',
            'a.office_shift AS shift_idx',
            '(SELECT shift_mode FROM master_shift WHERE idx = a.office_shift) AS shift_mode',
            'a.meal_allowance as basic_meal',
            "(SELECT meal_allowance FROM allowance_half_detail WHERE allowh_date = b.cal_date AND employee_id = a.employee_id AND company_idx = $office AND allowh_id = $all_id) as meal_allowance",
            "(SELECT meal_cutting FROM allowance_half_detail WHERE allowh_date = b.cal_date AND employee_id = a.employee_id AND company_idx = $office AND allowh_id = $all_id) as meal_cutting",
            "(SELECT incentive_allowance FROM allowance_half_detail WHERE allowh_date = b.cal_date AND employee_id = a.employee_id AND company_idx = $office AND allowh_id = $all_id) as incentive_allowance",
            "(SELECT dlk_allowance FROM allowance_half_detail WHERE allowh_date = b.cal_date AND employee_id = a.employee_id AND company_idx = $office AND allowh_id = $all_id) as dlk_allowance",
            "(SELECT overtime_allowance FROM allowance_half_detail WHERE allowh_date = b.cal_date AND employee_id = a.employee_id AND company_idx = $office AND allowh_id = $all_id) as overtime_allowance",
            "(SELECT allowance_value FROM allowance_half_detail WHERE allowh_date = b.cal_date AND employee_id = a.employee_id AND company_idx = $office AND allowh_id = $all_id) as allowance_value",
            "(SELECT description FROM allowance_half_detail WHERE allowh_date = b.cal_date AND employee_id = a.employee_id AND company_idx = $office AND allowh_id = $all_id) as description",
            "(SELECT status_absen FROM allowance_half_detail WHERE allowh_date = b.cal_date AND employee_id = a.employee_id AND company_idx = $office AND allowh_id = $all_id) as status_absen",
            "(SELECT rapel_ot FROM allowance_half_detail WHERE allowh_date = b.cal_date AND employee_id = a.employee_id AND company_idx = $office AND allowh_id = $all_id) as rapel_ot",
            "(SELECT rapel_sub FROM allowance_half_detail WHERE allowh_date = b.cal_date AND employee_id = a.employee_id AND company_idx = $office AND allowh_id = $all_id) as rapel_sub",
            "(SELECT rapel_inc FROM allowance_half_detail WHERE allowh_date = b.cal_date AND employee_id = a.employee_id AND company_idx = $office AND allowh_id = $all_id) as rapel_inc",
            "(SELECT rapel_dlk FROM allowance_half_detail WHERE allowh_date = b.cal_date AND employee_id = a.employee_id AND company_idx = $office AND allowh_id = $all_id) as rapel_dlk",
            "(SELECT check_in FROM attendance_employee WHERE attendance_date = b.cal_date AND employee_id = a.employee_id AND company_idx = $office) as check_in",
            "(SELECT status_piket FROM attendance_employee WHERE attendance_date = b.cal_date AND employee_id = a.employee_id AND company_idx = $office) as status_piket",
            "(SELECT check_out FROM attendance_employee WHERE attendance_date = b.cal_date AND employee_id = a.employee_id AND company_idx = $office) as check_out",
            "(SELECT sub_type FROM submission_detail WHERE sub_date = b.cal_date AND employee_id = a.employee_id AND company_idx = $office) as sub_type",
            "(SELECT status_confirm FROM submission_detail WHERE sub_date = b.cal_date AND employee_id = a.employee_id AND company_idx = $office) as status_confirm"
        );//set column field database for datatable select
        $column_order = array(
            null
        ); //set column field database for datatable orderable
        $column_search = array(
            'a.employee_name'
        ); //set column field database for datatable searchable
        $order = array(
            'a.employee_id' => '',
            'b.cal_date' => ''
        ); // default order must have key and value, if not use ASC/DESC just set the value with empty string
        $this->db->select($select_column);
        $this->db->from('master_employee a');
        $this->db->join('calendar b', 'x', 'cross');
        $this->db->where(["b.cal_date BETWEEN '$from' AND '$to' "=>null, 'a.status_delete' => 0]);
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
            foreach($order as $k => $v){
                $this->db->order_by($k, $v);
            }
        }
    }
    function get_detail($all_id, $from, $to) {
        $this->_get_detail_query($all_id, $from, $to);
        // if(@$_POST['length'] != -1)
        // $this->db->limit(@$_POST['length'], @$_POST['start']);
        // $query = $this->db->get_compiled_select();
        $query = $this->db->get()->result_array();
        $startDate = new DateTime($from);
        $endDate = new DateTime($to);
        $interval = $startDate->diff($endDate);
        $days = $interval->days;
        $kurang = 15 - $days;
        $format_data = [];
        foreach($query as $i=>$v){
            $tanggal = $v['calendar_date'];
            $employee_name = $v['employee_name'];
            if (!isset($format_data[$employee_name])) {
                // $attendance[$record->employee_name] = array_fill_keys($dates, 0); // Initialize dates with 0
                // $format_data[$employee_name]['umt_total'] = 0;
                $countKehadiran = 0;
                $otTotal = 0;
                $pkTotal = 0;
                $umtTotal = 0;
                $umtCutTotal = 0;
                $insTotal = 0;
                $dlkTotal = 0;
                $rplTotal = 0;
                $gTotal = 0;
                $check_in_count = 0;
                $check_out_count = 0;
            }
            $check_in = $v['check_in'];
            $check_out = $v['check_out'];
            $sub_type = (int)$v['sub_type'];
            $status_confirm = (int)$v['status_confirm'];
            $status_piket = (int)$v['status_piket'];
            $shift_mode = (int)$v['shift_mode'];
            $status_absen = (int)$v['status_absen'];
            if(empty($check_out) && empty($check_in)){
                if($status_confirm==1){
                    if($sub_type==1){
                        $countKehadiran += 1;
                        $status_absenx = 2;
                    }elseif($sub_type==2){
                        $countKehadiran += 1;
                        $status_absenx = 3;
                    }elseif($sub_type==3){
                        $status_absenx = 4;
                    }else{
                        $status_absenx = $status_absen;
                    }
                }else{
                    $status_absenx = $status_absen;
                }
            }elseif(empty($check_in)){
                $status_absenx = 8;
            }else{
                $status_absenx = $status_absen;
            }
            // if($v['employee_id']==1 && $tanggal == '2024-10-03'){
            //     var_dump('<pre>');var_dump($countKehadiran);var_dump($sub_type);var_dump($status_confirm);die;
            // }
            $basic_mealx = (float)$v['basic_meal'];
            $meal_allowancex = (float)$v['meal_allowance'];
            $meal_cuttingx = (float)$v['meal_cutting'];
            $incentive_allowancex = (float)$v['incentive_allowance'];
            $dlk_allowancex = (float)$v['dlk_allowance'];
            $rapel_otx = (float)$v['rapel_ot'];
            $rapel_subx = (float)$v['rapel_sub'];
            $rapel_incx = (float)$v['rapel_inc'];
            $rapel_dlkx = (float)$v['rapel_dlk'];
            if($status_piket==1 || $shift_mode==1){
                $piket_allowancex = (float)$v['overtime_allowance'];
                $piket_allowance = number_format($v['overtime_allowance']);
                $piket_allowanceV = empty($piket_allowance)?'':"<br>PK: $piket_allowance";
                $overtime_allowancex = 0;
                $overtime_allowance = number_format(0);
                $overtime_allowanceV = empty($overtime_allowance)?'':"<br>OT: $overtime_allowance";
            }else{
                $piket_allowancex = 0;
                $piket_allowance = number_format(0);
                $piket_allowanceV = empty($piket_allowance)?'':"<br>PK: $piket_allowance";
                $overtime_allowancex = (float)$v['overtime_allowance'];
                $overtime_allowance = number_format($v['overtime_allowance']);
                $overtime_allowanceV = empty($overtime_allowance)?'':"<br>OT: $overtime_allowance";
            }
            $allowance_valuex = (float)$v['allowance_value'];
            $basic_meal = number_format($v['basic_meal']);
            $meal_allowance = number_format($v['meal_allowance']);
            $meal_cutting = number_format($v['meal_cutting']);
            $incentive_allowance = number_format($v['incentive_allowance']);
            $dlk_allowance = number_format($v['dlk_allowance']);
            $rapel_ot = number_format($v['rapel_ot']);
            $rapel_sub = number_format($v['rapel_sub']);
            $rapel_inc = number_format($v['rapel_inc']);
            $rapel_dlk = number_format($v['rapel_dlk']);
            $allowance_value = number_format($v['allowance_value']);
            $check_inV = empty($check_in)?'<span class="text-danger">xx:xx</span> - ':date('H:i', strtotime($check_in)).' - ';
            $check_outV = empty($check_out)?(empty($check_in)?'<span class="text-danger">xx:xx</span>':'<span class="text-danger">xx:xx</span>'):date('H:i', strtotime($check_out));
            $meal_allowanceV = empty($meal_allowancex)?'':"<br>UM: $meal_allowance";
            $incentive_allowanceV = empty($incentive_allowancex)?'':"<br>INS: $incentive_allowance";
            $dlk_allowanceV = empty($dlk_allowancex)?'':"<br>DLK: $dlk_allowance";
            $rapelOt = empty($rapel_otx)?'':"<br>RPL_OT/PK: $rapel_ot";
            $rapelSub = empty($rapel_subx)?'':"<br>RPL_UMT: $rapel_sub";
            $rapelInc = empty($rapel_incx)?'':"<br>RPL_INS: $rapel_inc";
            $rapelDlk = empty($rapel_dlkx)?'':"<br>RPL_DLK: $rapel_dlk";
            // $isiNya = "$tanggal";
            $isiNya = "$check_inV$check_outV$meal_allowanceV$incentive_allowanceV$dlk_allowanceV$overtime_allowanceV$piket_allowanceV$rapelOt$rapelSub$rapelInc$rapelDlk";
            
            $check_in_count += empty($check_in)?0:1; 
            $check_out_count += empty($check_out)?0:1; 
            $otTotal += $overtime_allowancex;
            $pkTotal += $piket_allowancex;
            $umtTotal += $meal_allowancex;
            $umtCutTotal += $meal_cuttingx;
            $insTotal += $incentive_allowancex;
            $dlkTotal += $dlk_allowancex;
            $rplTotal += $rapel_otx+$rapel_subx+$rapel_incx+$rapel_dlkx;
            $gTotalCh = ($check_in_count>$check_out_count?$check_in_count:$check_out_count)+$countKehadiran;
            $gTotal += $allowance_valuex;
            $format_data[$employee_name][$tanggal] = [$isiNya, $status_absenx, 0];
            if($days<15){
                for($ix = 1; $ix<=$kurang; $ix++){
                    $format_data[$employee_name][$ix] = ['-', 90, 0];
                }
            }
            $format_data[$employee_name]['total_check'] = $gTotalCh;
            $format_data[$employee_name]['basic_meal'] = $basic_mealx;
            $format_data[$employee_name]['ot_total'] = $otTotal;
            // $format_data[$employee_name]['umt_total'] = $umtTotal;
            $format_data[$employee_name]['insentif_total'] = $insTotal;
            $format_data[$employee_name]['dlk_total'] = $dlkTotal;
            $format_data[$employee_name]['rpl_total'] = $rplTotal;
            $format_data[$employee_name]['pk_total'] = $pkTotal;
            $format_data[$employee_name]['umt_cut_total'] = $umtCutTotal;
            $format_data[$employee_name]['grand_total'] = $gTotal;
        }
        // var_dump('<pre>');var_dump($format_data);die;
        foreach($format_data as $employee_name => $data){
            $test = [];
            if($days<15){
                for($ix2 = 1; $ix2<=$kurang; $ix2++){
                    unset($format_data[$employee_name][$ix2]);
                    $test[$ix2] = $data[$ix2];
                    $format_data[$employee_name][$ix2] = $data[$ix2];
                }
            }
            $total_check = $data['total_check'];
            $basic_meal_fix = $data['basic_meal'];
            $ot_total = $data['ot_total'];
            // $total_umt = $data['umt_total'];
            $total_ins = $data['insentif_total'];
            $total_dlk = $data['dlk_total'];
            $total_rpl = $data['rpl_total'];
            $pk_total = $data['pk_total'];
            $total_umtC = $data['umt_cut_total'];
            $grand_total = $data['grand_total'];
            unset($format_data[$employee_name]['total_check'], $format_data[$employee_name]['basic_meal'], $format_data[$employee_name]['ot_total'], $format_data[$employee_name]['pk_total'], $format_data[$employee_name]['umt_cut_total'], $format_data[$employee_name]['insentif_total'], $format_data[$employee_name]['dlk_total'], $format_data[$employee_name]['rpl_total'], $format_data[$employee_name]['grand_total']); // Remove temporarily
            $format_data[$employee_name]['total_check'] = [number_format($total_check), 99, $total_check]; // Re-add to ensure it's last
            $format_data[$employee_name]['basic_meal'] = [number_format($basic_meal_fix), 99, $basic_meal_fix]; // Re-add to ensure it's last
            $format_data[$employee_name]['ot_total'] = [number_format($ot_total), 99, $ot_total]; // Re-add to ensure it's last
            // $format_data[$employee_name]['umt_total'] = $total_umt; // Re-add to ensure it's last
            $format_data[$employee_name]['insentif_total'] = [number_format($total_ins), 99, $total_ins]; // Re-add to ensure it's last
            $format_data[$employee_name]['dlk_total'] = [number_format($total_dlk), 99, $total_dlk]; // Re-add to ensure it's last
            $format_data[$employee_name]['rpl_total'] = [number_format($total_rpl), 99, $total_rpl]; // Re-add to ensure it's last
            $format_data[$employee_name]['pk_total'] = [number_format($pk_total), 99, $pk_total]; // Re-add to ensure it's last
            $format_data[$employee_name]['umt_cut_total'] = [number_format($total_umtC), 99, $total_umtC]; // Re-add to ensure it's last
            $format_data[$employee_name]['grand_total'] = [number_format($grand_total), 99, $grand_total]; // Re-add to ensure it's last
        }
        return $format_data;
    }
    function get_detail_only($all_id, $from, $to) {
        $this->_get_detail_query($all_id, $from, $to);
        // if(@$_POST['length'] != -1)
        // $this->db->limit(@$_POST['length'], @$_POST['start']);
        // $query = $this->db->get_compiled_select();
        $query = $this->db->get()->result_array();
        $startDate = new DateTime($from);
        $endDate = new DateTime($to);
        $interval = $startDate->diff($endDate);
        $days = $interval->days;
        $kurang = 15 - $days;
        $format_data = [];
        foreach($query as $i=>$v){
            $tanggal = $v['calendar_date'];
            $employee_name = $v['employee_name'];
            if (!isset($format_data[$employee_name])) {
                // $attendance[$record->employee_name] = array_fill_keys($dates, 0); // Initialize dates with 0
                // $format_data[$employee_name]['umt_total'] = 0;
                $countKehadiran = 0;
                $otTotal = 0;
                $pkTotal = 0;
                $umtTotal = 0;
                $umtCutTotal = 0;
                $insTotal = 0;
                $dlkTotal = 0;
                $rplTotal = 0;
                $gTotal = 0;
                $check_in_count = 0;
                $check_out_count = 0;
            }
            $check_in = $v['check_in'];
            $check_out = $v['check_out'];
            $sub_type = (int)$v['sub_type'];
            $status_confirm = (int)$v['status_confirm'];
            $status_piket = (int)$v['status_piket'];
            $shift_mode = (int)$v['shift_mode'];
            $status_absen = (int)$v['status_absen'];
            if(empty($check_out) && empty($check_in)){
                if($status_confirm==1){
                    if($sub_type==1){
                        $countKehadiran += 1;
                        $status_absenx = 2;
                    }elseif($sub_type==2){
                        $countKehadiran += 1;
                        $status_absenx = 3;
                    }elseif($sub_type==3){
                        $status_absenx = 4;
                    }else{
                        $status_absenx = $status_absen;
                    }
                }else{
                    $status_absenx = $status_absen;
                }
            }elseif(empty($check_in)){
                $status_absenx = 8;
            }else{
                $status_absenx = $status_absen;
            }
            // if($v['employee_id']==1 && $tanggal == '2024-10-03'){
            //     var_dump('<pre>');var_dump($countKehadiran);var_dump($sub_type);var_dump($status_confirm);die;
            // }
            $basic_mealx = (float)$v['basic_meal'];
            $meal_allowancex = (float)$v['meal_allowance'];
            $meal_cuttingx = (float)$v['meal_cutting'];
            $incentive_allowancex = (float)$v['incentive_allowance'];
            $dlk_allowancex = (float)$v['dlk_allowance'];
            $rapel_otx = (float)$v['rapel_ot'];
            $rapel_subx = (float)$v['rapel_sub'];
            $rapel_incx = (float)$v['rapel_inc'];
            $rapel_dlkx = (float)$v['rapel_dlk'];
            if($status_piket==1 || $shift_mode==1){
                $piket_allowancex = (float)$v['overtime_allowance'];
                $piket_allowance = number_format($v['overtime_allowance']);
                $piket_allowanceV = empty($piket_allowance)?'':"<br>PK: $piket_allowance";
                $overtime_allowancex = 0;
                $overtime_allowance = number_format(0);
                $overtime_allowanceV = empty($overtime_allowance)?'':"<br>OT: $overtime_allowance";
            }else{
                $piket_allowancex = 0;
                $piket_allowance = number_format(0);
                $piket_allowanceV = empty($piket_allowance)?'':"<br>PK: $piket_allowance";
                $overtime_allowancex = (float)$v['overtime_allowance'];
                $overtime_allowance = number_format($v['overtime_allowance']);
                $overtime_allowanceV = empty($overtime_allowance)?'':"<br>OT: $overtime_allowance";
            }
            $allowance_valuex = (float)$v['allowance_value'];
            $basic_meal = number_format($v['basic_meal']);
            $meal_allowance = number_format($v['meal_allowance']);
            $meal_cutting = number_format($v['meal_cutting']);
            $incentive_allowance = number_format($v['incentive_allowance']);
            $dlk_allowance = number_format($v['dlk_allowance']);
            $rapel_ot = number_format($v['rapel_ot']);
            $rapel_sub = number_format($v['rapel_sub']);
            $rapel_inc = number_format($v['rapel_inc']);
            $rapel_dlk = number_format($v['rapel_dlk']);
            $allowance_value = number_format($v['allowance_value']);
            $check_inV = empty($check_in)?'<span class="text-danger">xx:xx</span> - ':date('H:i', strtotime($check_in)).' - ';
            $check_outV = empty($check_out)?(empty($check_in)?'<span class="text-danger">xx:xx</span>':'<span class="text-danger">xx:xx</span>'):date('H:i', strtotime($check_out));
            $meal_allowanceV = empty($meal_allowancex)?'':"<br>UM: $meal_allowance";
            $incentive_allowanceV = empty($incentive_allowancex)?'':"<br>INS: $incentive_allowance";
            $dlk_allowanceV = empty($dlk_allowancex)?'':"<br>DLK: $dlk_allowance";
            $rapelOt = empty($rapel_otx)?'':"<br>RPL_OT/PK: $rapel_ot";
            $rapelSub = empty($rapel_subx)?'':"<br>RPL_UMT: $rapel_sub";
            $rapelInc = empty($rapel_incx)?'':"<br>RPL_INS: $rapel_inc";
            $rapelDlk = empty($rapel_dlkx)?'':"<br>RPL_DLK: $rapel_dlk";
            // $isiNya = "$tanggal";
            $isiNya = "$check_inV$check_outV";
            
            $check_in_count += empty($check_in)?0:1; 
            $check_out_count += empty($check_out)?0:1; 
            $otTotal += $overtime_allowancex;
            $pkTotal += $piket_allowancex;
            $umtTotal += $meal_allowancex;
            $umtCutTotal += $meal_cuttingx;
            $insTotal += $incentive_allowancex;
            $dlkTotal += $dlk_allowancex;
            $rplTotal += $rapel_otx+$rapel_subx+$rapel_incx+$rapel_dlkx;
            $gTotalCh = ($check_in_count>$check_out_count?$check_in_count:$check_out_count)+$countKehadiran;
            $gTotal += $allowance_valuex;
            $format_data[$employee_name][$tanggal] = [$isiNya, $status_absenx, 0];
            if($days<15){
                for($ix = 1; $ix<=$kurang; $ix++){
                    $format_data[$employee_name][$ix] = ['-', 90, 0];
                }
            }
            $format_data[$employee_name]['total_check'] = $gTotalCh;
            $format_data[$employee_name]['basic_meal'] = $basic_mealx;
            $format_data[$employee_name]['ot_total'] = $otTotal;
            // $format_data[$employee_name]['umt_total'] = $umtTotal;
            $format_data[$employee_name]['insentif_total'] = $insTotal;
            $format_data[$employee_name]['dlk_total'] = $dlkTotal;
            $format_data[$employee_name]['rpl_total'] = $rplTotal;
            $format_data[$employee_name]['pk_total'] = $pkTotal;
            $format_data[$employee_name]['umt_cut_total'] = $umtCutTotal;
            $format_data[$employee_name]['grand_total'] = $gTotal;
        }
        // var_dump('<pre>');var_dump($format_data);die;
        foreach($format_data as $employee_name => $data){
            $test = [];
            if($days<15){
                for($ix2 = 1; $ix2<=$kurang; $ix2++){
                    unset($format_data[$employee_name][$ix2]);
                    $test[$ix2] = $data[$ix2];
                    $format_data[$employee_name][$ix2] = $data[$ix2];
                }
            }
            $total_check = $data['total_check'];
            $basic_meal_fix = $data['basic_meal'];
            $ot_total = $data['ot_total'];
            // $total_umt = $data['umt_total'];
            $total_ins = $data['insentif_total'];
            $total_dlk = $data['dlk_total'];
            $total_rpl = $data['rpl_total'];
            $pk_total = $data['pk_total'];
            $total_umtC = $data['umt_cut_total'];
            $grand_total = $data['grand_total'];
            unset($format_data[$employee_name]['total_check'], $format_data[$employee_name]['basic_meal'], $format_data[$employee_name]['ot_total'], $format_data[$employee_name]['pk_total'], $format_data[$employee_name]['umt_cut_total'], $format_data[$employee_name]['insentif_total'], $format_data[$employee_name]['dlk_total'], $format_data[$employee_name]['rpl_total'], $format_data[$employee_name]['grand_total']); // Remove temporarily
            $format_data[$employee_name]['total_check'] = [number_format($total_check), 99, $total_check]; // Re-add to ensure it's last
            $format_data[$employee_name]['basic_meal'] = [number_format($basic_meal_fix), 99, $basic_meal_fix]; // Re-add to ensure it's last
            $format_data[$employee_name]['ot_total'] = [number_format($ot_total), 99, $ot_total]; // Re-add to ensure it's last
            // $format_data[$employee_name]['umt_total'] = $total_umt; // Re-add to ensure it's last
            $format_data[$employee_name]['insentif_total'] = [number_format($total_ins), 99, $total_ins]; // Re-add to ensure it's last
            $format_data[$employee_name]['dlk_total'] = [number_format($total_dlk), 99, $total_dlk]; // Re-add to ensure it's last
            $format_data[$employee_name]['rpl_total'] = [number_format($total_rpl), 99, $total_rpl]; // Re-add to ensure it's last
            $format_data[$employee_name]['pk_total'] = [number_format($pk_total), 99, $pk_total]; // Re-add to ensure it's last
            $format_data[$employee_name]['umt_cut_total'] = [number_format($total_umtC), 99, $total_umtC]; // Re-add to ensure it's last
            $format_data[$employee_name]['grand_total'] = [number_format($grand_total), 99, $grand_total]; // Re-add to ensure it's last
        }
        return $format_data;
    }

    function get_detail2($all_id, $from, $to) {
        $this->_get_detail_query($all_id, $from, $to);
        // if(@$_POST['length'] != -1)
        // $this->db->limit(@$_POST['length'], @$_POST['start']);
        // $query = $this->db->get_compiled_select();
        $query = $this->db->get()->result_array();
        $startDate = new DateTime($from);
        $endDate = new DateTime($to);
        $interval = $startDate->diff($endDate);
        $days = $interval->days;
        $kurang = 14 - $days;
        $format_data = [];
        $i = 1;
        foreach($query as $i=>$v){
            $tanggal = $v['calendar_date'];
            $employee_name = $v['employee_name'];
            if (!isset($format_data[$employee_name])) {
                // $attendance[$record->employee_name] = array_fill_keys($dates, 0); // Initialize dates with 0
                // $format_data[$employee_name]['umt_total'] = 0;
                $countKehadiran = 0;
                $otTotal = 0;
                $pkTotal = 0;
                $umtTotal = 0;
                $umtCutTotal = 0;
                $insTotal = 0;
                $dlkTotal = 0;
                $rplTotal = 0;
                $gTotal = 0;
                $check_in_count = 0;
                $check_out_count = 0;
            }
            $check_in = $v['check_in'];
            $check_out = $v['check_out'];
            $sub_type = (int)$v['sub_type'];
            $status_confirm = (int)$v['status_confirm'];
            $status_piket = (int)$v['status_piket'];
            $shift_mode = (int)$v['shift_mode'];
            $status_absen = (int)$v['status_absen'];
            if(empty($check_out) && empty($check_in)){
                if($status_confirm==1){
                    if($sub_type==1){
                        $countKehadiran += 1;
                        $status_absenx = 2;
                    }elseif($sub_type==2){
                        $countKehadiran += 1;
                        $status_absenx = 3;
                    }elseif($sub_type==3){
                        $status_absenx = 4;
                    }else{
                        $status_absenx = $status_absen;
                    }
                }else{
                    $status_absenx = $status_absen;
                }
            }elseif(empty($check_in)){
                $status_absenx = 8;
            }else{
                $status_absenx = $status_absen;
            }
            // if($v['employee_id']==1 && $tanggal == '2024-10-03'){
            //     var_dump('<pre>');var_dump($countKehadiran);var_dump($sub_type);var_dump($status_confirm);die;
            // }
            $basic_mealx = (float)$v['basic_meal'];
            $meal_allowancex = (float)$v['meal_allowance'];
            $meal_cuttingx = (float)$v['meal_cutting'];
            $incentive_allowancex = (float)$v['incentive_allowance'];
            $dlk_allowancex = (float)$v['dlk_allowance'];
            $rapel_otx = (float)$v['rapel_ot'];
            $rapel_subx = (float)$v['rapel_sub'];
            $rapel_incx = (float)$v['rapel_inc'];
            $rapel_dlkx = (float)$v['rapel_dlk'];
            if($status_piket==1 || $shift_mode==1){
                $piket_allowancex = (float)$v['overtime_allowance'];
                $piket_allowance = number_format($v['overtime_allowance']);
                $piket_allowanceV = empty($piket_allowance)?'':"<br>PK: $piket_allowance";
                $overtime_allowancex = 0;
                $overtime_allowance = number_format(0);
                $overtime_allowanceV = empty($overtime_allowance)?'':"<br>OT: $overtime_allowance";
            }else{
                $piket_allowancex = 0;
                $piket_allowance = number_format(0);
                $piket_allowanceV = empty($piket_allowance)?'':"<br>PK: $piket_allowance";
                $overtime_allowancex = (float)$v['overtime_allowance'];
                $overtime_allowance = number_format($v['overtime_allowance']);
                $overtime_allowanceV = empty($overtime_allowance)?'':"<br>OT: $overtime_allowance";
            }
            $allowance_valuex = (float)$v['allowance_value'];
            $basic_meal = number_format($v['basic_meal']);
            $meal_allowance = number_format($v['meal_allowance']);
            $meal_cutting = number_format($v['meal_cutting']);
            $incentive_allowance = number_format($v['incentive_allowance']);
            $dlk_allowance = number_format($v['dlk_allowance']);
            $rapel_ot = number_format($v['rapel_ot']);
            $rapel_sub = number_format($v['rapel_sub']);
            $rapel_inc = number_format($v['rapel_inc']);
            $rapel_dlk = number_format($v['rapel_dlk']);
            $allowance_value = number_format($v['allowance_value']);
            $check_inV = empty($check_in)?'<span class="text-danger">xx:xx</span> - ':date('H:i', strtotime($check_in)).' - ';
            $check_outV = empty($check_out)?(empty($check_in)?'<span class="text-danger">xx:xx</span>':'<span class="text-danger">xx:xx</span>'):date('H:i', strtotime($check_out));
            $meal_allowanceV = empty($meal_allowancex)?'':"<br>UM: $meal_allowance";
            $incentive_allowanceV = empty($incentive_allowancex)?'':"<br>INS: $incentive_allowance";
            $dlk_allowanceV = empty($dlk_allowancex)?'':"<br>DLK: $dlk_allowance";
            $rapelOt = empty($rapel_otx)?'':"<br>RPL_OT/PK: $rapel_ot";
            $rapelSub = empty($rapel_subx)?'':"<br>RPL_UMT: $rapel_sub";
            $rapelInc = empty($rapel_incx)?'':"<br>RPL_INS: $rapel_inc";
            $rapelDlk = empty($rapel_dlkx)?'':"<br>RPL_DLK: $rapel_dlk";
            // $isiNya = "$tanggal";
            $isiNya = "$check_inV$check_outV$meal_allowanceV$incentive_allowanceV$dlk_allowanceV$overtime_allowanceV$piket_allowanceV$rapelOt$rapelSub$rapelInc$rapelDlk";
            
            $check_in_count += empty($check_in)?0:1; 
            $check_out_count += empty($check_out)?0:1; 
            $otTotal += $overtime_allowancex;
            $pkTotal += $piket_allowancex;
            $umtTotal += $meal_allowancex;
            $umtCutTotal += $meal_cuttingx;
            $insTotal += $incentive_allowancex;
            $dlkTotal += $dlk_allowancex;
            $rplTotal += $rapel_otx+$rapel_subx+$rapel_incx+$rapel_dlkx;
            $gTotalCh = ($check_in_count>$check_out_count?$check_in_count:$check_out_count)+$countKehadiran;
            $gTotal += $allowance_valuex;
            $format_data[$employee_name][$tanggal] = [$isiNya, $status_absenx, 0];
            if($days<15){
                for($ix = 1; $ix<=$kurang; $ix++){
                    $format_data[$employee_name][$ix] = ['-', 90, 0];
                }
            }
            $format_data[$employee_name]['total_check'] = $gTotalCh;
            $format_data[$employee_name]['basic_meal'] = $basic_mealx;
            $format_data[$employee_name]['ot_total'] = $otTotal;
            // $format_data[$employee_name]['umt_total'] = $umtTotal;
            $format_data[$employee_name]['insentif_total'] = $insTotal;
            $format_data[$employee_name]['dlk_total'] = $dlkTotal;
            $format_data[$employee_name]['rpl_total'] = $rplTotal;
            $format_data[$employee_name]['pk_total'] = $pkTotal;
            $format_data[$employee_name]['umt_cut_total'] = $umtCutTotal;
            $format_data[$employee_name]['grand_total'] = $gTotal;
            $i++;
        }
        // var_dump('<pre>');var_dump($format_data);die;
        foreach($format_data as $employee_name => $data){
            $test = [];
            if($days<15){
                for($ix2 = 1; $ix2<=$kurang; $ix2++){
                    unset($format_data[$employee_name][$ix2]);
                    $test[$ix2] = $data[$ix2];
                    $format_data[$employee_name][$ix2] = $data[$ix2];
                }
            }
            $total_check = $data['total_check'];
            $basic_meal_fix = $data['basic_meal'];
            $ot_total = $data['ot_total'];
            // $total_umt = $data['umt_total'];
            $total_ins = $data['insentif_total'];
            $total_dlk = $data['dlk_total'];
            $total_rpl = $data['rpl_total'];
            $pk_total = $data['pk_total'];
            $total_umtC = $data['umt_cut_total'];
            $grand_total = $data['grand_total'];
            unset($format_data[$employee_name]['total_check'], $format_data[$employee_name]['basic_meal'], $format_data[$employee_name]['ot_total'], $format_data[$employee_name]['pk_total'], $format_data[$employee_name]['umt_cut_total'], $format_data[$employee_name]['insentif_total'], $format_data[$employee_name]['dlk_total'], $format_data[$employee_name]['rpl_total'], $format_data[$employee_name]['grand_total']); // Remove temporarily
            $format_data[$employee_name]['total_check'] = [number_format($total_check), 99, $total_check]; // Re-add to ensure it's last
            $format_data[$employee_name]['basic_meal'] = [number_format($basic_meal_fix), 99, $basic_meal_fix]; // Re-add to ensure it's last
            $format_data[$employee_name]['ot_total'] = [number_format($ot_total), 99, $ot_total]; // Re-add to ensure it's last
            // $format_data[$employee_name]['umt_total'] = $total_umt; // Re-add to ensure it's last
            $format_data[$employee_name]['insentif_total'] = [number_format($total_ins), 99, $total_ins]; // Re-add to ensure it's last
            $format_data[$employee_name]['dlk_total'] = [number_format($total_dlk), 99, $total_dlk]; // Re-add to ensure it's last
            $format_data[$employee_name]['rpl_total'] = [number_format($total_rpl), 99, $total_rpl]; // Re-add to ensure it's last
            $format_data[$employee_name]['pk_total'] = [number_format($pk_total), 99, $pk_total]; // Re-add to ensure it's last
            $format_data[$employee_name]['umt_cut_total'] = [number_format($total_umtC), 99, $total_umtC]; // Re-add to ensure it's last
            $format_data[$employee_name]['grand_total'] = [number_format($grand_total), 99, $grand_total]; // Re-add to ensure it's last
        }
        return $format_data;
    }

    function get_detail_only2($all_id, $from, $to) {
        $this->_get_detail_query($all_id, $from, $to);
        // if(@$_POST['length'] != -1)
        // $this->db->limit(@$_POST['length'], @$_POST['start']);
        // $query = $this->db->get_compiled_select();
        $query = $this->db->get()->result_array();
        $startDate = new DateTime($from);
        $endDate = new DateTime($to);
        $interval = $startDate->diff($endDate);
        $days = $interval->days;
        $kurang = 14 - $days;
        $format_data = [];
        $i = 1;
        foreach($query as $i=>$v){
            $tanggal = $v['calendar_date'];
            $employee_name = $v['employee_name'];
            if (!isset($format_data[$employee_name])) {
                // $attendance[$record->employee_name] = array_fill_keys($dates, 0); // Initialize dates with 0
                // $format_data[$employee_name]['umt_total'] = 0;
                $countKehadiran = 0;
                $otTotal = 0;
                $pkTotal = 0;
                $umtTotal = 0;
                $umtCutTotal = 0;
                $insTotal = 0;
                $dlkTotal = 0;
                $rplTotal = 0;
                $gTotal = 0;
                $check_in_count = 0;
                $check_out_count = 0;
            }
            $check_in = $v['check_in'];
            $check_out = $v['check_out'];
            $sub_type = (int)$v['sub_type'];
            $status_confirm = (int)$v['status_confirm'];
            $status_piket = (int)$v['status_piket'];
            $shift_mode = (int)$v['shift_mode'];
            $status_absen = (int)$v['status_absen'];
            if(empty($check_out) && empty($check_in)){
                if($status_confirm==1){
                    if($sub_type==1){
                        $countKehadiran += 1;
                        $status_absenx = 2;
                    }elseif($sub_type==2){
                        $countKehadiran += 1;
                        $status_absenx = 3;
                    }elseif($sub_type==3){
                        $status_absenx = 4;
                    }else{
                        $status_absenx = $status_absen;
                    }
                }else{
                    $status_absenx = $status_absen;
                }
            }elseif(empty($check_in)){
                $status_absenx = 8;
            }else{
                $status_absenx = $status_absen;
            }
            // if($v['employee_id']==1 && $tanggal == '2024-10-03'){
            //     var_dump('<pre>');var_dump($countKehadiran);var_dump($sub_type);var_dump($status_confirm);die;
            // }
            $basic_mealx = (float)$v['basic_meal'];
            $meal_allowancex = (float)$v['meal_allowance'];
            $meal_cuttingx = (float)$v['meal_cutting'];
            $incentive_allowancex = (float)$v['incentive_allowance'];
            $dlk_allowancex = (float)$v['dlk_allowance'];
            $rapel_otx = (float)$v['rapel_ot'];
            $rapel_subx = (float)$v['rapel_sub'];
            $rapel_incx = (float)$v['rapel_inc'];
            $rapel_dlkx = (float)$v['rapel_dlk'];
            if($status_piket==1 || $shift_mode==1){
                $piket_allowancex = (float)$v['overtime_allowance'];
                $piket_allowance = number_format($v['overtime_allowance']);
                $piket_allowanceV = empty($piket_allowance)?'':"<br>PK: $piket_allowance";
                $overtime_allowancex = 0;
                $overtime_allowance = number_format(0);
                $overtime_allowanceV = empty($overtime_allowance)?'':"<br>OT: $overtime_allowance";
            }else{
                $piket_allowancex = 0;
                $piket_allowance = number_format(0);
                $piket_allowanceV = empty($piket_allowance)?'':"<br>PK: $piket_allowance";
                $overtime_allowancex = (float)$v['overtime_allowance'];
                $overtime_allowance = number_format($v['overtime_allowance']);
                $overtime_allowanceV = empty($overtime_allowance)?'':"<br>OT: $overtime_allowance";
            }
            $allowance_valuex = (float)$v['allowance_value'];
            $basic_meal = number_format($v['basic_meal']);
            $meal_allowance = number_format($v['meal_allowance']);
            $meal_cutting = number_format($v['meal_cutting']);
            $incentive_allowance = number_format($v['incentive_allowance']);
            $dlk_allowance = number_format($v['dlk_allowance']);
            $rapel_ot = number_format($v['rapel_ot']);
            $rapel_sub = number_format($v['rapel_sub']);
            $rapel_inc = number_format($v['rapel_inc']);
            $rapel_dlk = number_format($v['rapel_dlk']);
            $allowance_value = number_format($v['allowance_value']);
            $check_inV = empty($check_in)?'<span class="text-danger">xx:xx</span> - ':date('H:i', strtotime($check_in)).' - ';
            $check_outV = empty($check_out)?(empty($check_in)?'<span class="text-danger">xx:xx</span>':'<span class="text-danger">xx:xx</span>'):date('H:i', strtotime($check_out));
            $meal_allowanceV = empty($meal_allowancex)?'':"<br>UM: $meal_allowance";
            $incentive_allowanceV = empty($incentive_allowancex)?'':"<br>INS: $incentive_allowance";
            $dlk_allowanceV = empty($dlk_allowancex)?'':"<br>DLK: $dlk_allowance";
            $rapelOt = empty($rapel_otx)?'':"<br>RPL_OT/PK: $rapel_ot";
            $rapelSub = empty($rapel_subx)?'':"<br>RPL_UMT: $rapel_sub";
            $rapelInc = empty($rapel_incx)?'':"<br>RPL_INS: $rapel_inc";
            $rapelDlk = empty($rapel_dlkx)?'':"<br>RPL_DLK: $rapel_dlk";
            // $isiNya = "$tanggal";
            $isiNya = "$check_inV$check_outV";
            
            $check_in_count += empty($check_in)?0:1; 
            $check_out_count += empty($check_out)?0:1; 
            $otTotal += $overtime_allowancex;
            $pkTotal += $piket_allowancex;
            $umtTotal += $meal_allowancex;
            $umtCutTotal += $meal_cuttingx;
            $insTotal += $incentive_allowancex;
            $dlkTotal += $dlk_allowancex;
            $rplTotal += $rapel_otx+$rapel_subx+$rapel_incx+$rapel_dlkx;
            $gTotalCh = ($check_in_count>$check_out_count?$check_in_count:$check_out_count)+$countKehadiran;
            $gTotal += $allowance_valuex;
            $format_data[$employee_name][$tanggal] = [$isiNya, $status_absenx, 0];
            if($days<15){
                for($ix = 1; $ix<=$kurang; $ix++){
                    $format_data[$employee_name][$ix] = ['-', 90, 0];
                }
            }
            $format_data[$employee_name]['total_check'] = $gTotalCh;
            $format_data[$employee_name]['basic_meal'] = $basic_mealx;
            $format_data[$employee_name]['ot_total'] = $otTotal;
            // $format_data[$employee_name]['umt_total'] = $umtTotal;
            $format_data[$employee_name]['insentif_total'] = $insTotal;
            $format_data[$employee_name]['dlk_total'] = $dlkTotal;
            $format_data[$employee_name]['rpl_total'] = $rplTotal;
            $format_data[$employee_name]['pk_total'] = $pkTotal;
            $format_data[$employee_name]['umt_cut_total'] = $umtCutTotal;
            $format_data[$employee_name]['grand_total'] = $gTotal;
            $i++;
        }
        // var_dump('<pre>');var_dump($format_data);die;
        foreach($format_data as $employee_name => $data){
            $test = [];
            if($days<15){
                for($ix2 = 1; $ix2<=$kurang; $ix2++){
                    unset($format_data[$employee_name][$ix2]);
                    $test[$ix2] = $data[$ix2];
                    $format_data[$employee_name][$ix2] = $data[$ix2];
                }
            }
            $total_check = $data['total_check'];
            $basic_meal_fix = $data['basic_meal'];
            $ot_total = $data['ot_total'];
            // $total_umt = $data['umt_total'];
            $total_ins = $data['insentif_total'];
            $total_dlk = $data['dlk_total'];
            $total_rpl = $data['rpl_total'];
            $pk_total = $data['pk_total'];
            $total_umtC = $data['umt_cut_total'];
            $grand_total = $data['grand_total'];
            unset($format_data[$employee_name]['total_check'], $format_data[$employee_name]['basic_meal'], $format_data[$employee_name]['ot_total'], $format_data[$employee_name]['pk_total'], $format_data[$employee_name]['umt_cut_total'], $format_data[$employee_name]['insentif_total'], $format_data[$employee_name]['dlk_total'], $format_data[$employee_name]['rpl_total'], $format_data[$employee_name]['grand_total']); // Remove temporarily
            $format_data[$employee_name]['total_check'] = [number_format($total_check), 99, $total_check]; // Re-add to ensure it's last
            $format_data[$employee_name]['basic_meal'] = [number_format($basic_meal_fix), 99, $basic_meal_fix]; // Re-add to ensure it's last
            $format_data[$employee_name]['ot_total'] = [number_format($ot_total), 99, $ot_total]; // Re-add to ensure it's last
            // $format_data[$employee_name]['umt_total'] = $total_umt; // Re-add to ensure it's last
            $format_data[$employee_name]['insentif_total'] = [number_format($total_ins), 99, $total_ins]; // Re-add to ensure it's last
            $format_data[$employee_name]['dlk_total'] = [number_format($total_dlk), 99, $total_dlk]; // Re-add to ensure it's last
            $format_data[$employee_name]['rpl_total'] = [number_format($total_rpl), 99, $total_rpl]; // Re-add to ensure it's last
            $format_data[$employee_name]['pk_total'] = [number_format($pk_total), 99, $pk_total]; // Re-add to ensure it's last
            $format_data[$employee_name]['umt_cut_total'] = [number_format($total_umtC), 99, $total_umtC]; // Re-add to ensure it's last
            $format_data[$employee_name]['grand_total'] = [number_format($grand_total), 99, $grand_total]; // Re-add to ensure it's last
        }
        return $format_data;
    }

    public function getAttendanceEmployeeAll($tAwal,$tAkhir,$employee_id="")
    {
        if(empty($employee_id)){
            $where = "";
        }else{
            $where = "AND a.employee_id = $employee_id ";
        }
        $order = "ORDER BY a.employee_id, b.cal_date";
        $query = "SELECT 
                b.cal_date as calendar_date,
                a.employee_id,
                a.employee_name,
                c.attendance_no,
                c.attendance_date,
                c.shift_idx,
                c.target_in,
                c.check_in,
                c.check_out,
                c.target_out,
                c.target_out,
                c.point_center_check_in,
                c.point_center_check_out,
                c.url_photo_check_in,
                c.url_photo_check_out,
                c.status_permission,
                c.status_overtime,
                c.reason_overtime,
                c.company_idx,
                c.`status`,
                (SELECT check_in FROM dynamic_shift WHERE shift_idx = a.office_shift AND `date` = b.cal_date AND employee_id = a.employee_id) as dynamic_check_in,
                (SELECT check_out FROM dynamic_shift WHERE shift_idx = a.office_shift AND `date` = b.cal_date AND employee_id = a.employee_id) as dynamic_check_out,
                d.shift_mode,
                d.monday_in as monday,
                d.tuesday_in as tuesday,
                d.wednesday_in as wednesday,
                d.thursday_in as thursday,
                d.friday_in as friday,
                d.saturday_in as saturday,
                d.sunday_in as sunday
            FROM master_employee a
            CROSS JOIN calendar b
            LEFT JOIN attendance_employee c ON b.cal_date = c.attendance_date AND a.employee_id = c.employee_id
            LEFT JOIN master_shift d ON d.idx = a.office_shift
            WHERE b.cal_date BETWEEN '$tAwal' AND '$tAkhir'
            AND a.status_delete = 0
            AND (a.company_idx = 1 OR a.company_idx IS NULL) ".$where.$order;
        $hasilnya = $this->db->query($query)->result_array();
        return $hasilnya;
    }
    
    public function getUmtDatax($from_date, $to_date, $company_idx){
        $this->db->select('a.*')
        ->from('attendance_employee a')
        ->where(['(DATE_FORMAT(a.check_in, "%Y-%m-%d") between "'.$from_date.'" and "'.$to_date.'")' => null, 'a.company_idx' => $company_idx, 'a.status_draft_half' => 0, 'IF(0=1,0,(SELECT `status_delete` FROM `master_employee` WHERE `employee_id` = `a`.`employee_id`)) =' => 0]);
        // $result = $this->db->get_compiled_select();
        $result = $this->db->get()->result();
        return $result;
    }

    public function getUmtData($from_date, $to_date, $company_idx){
        $query = "SELECT 
                a.idx,
                NULL AS rapel_ot_idx,
                NULL AS rapel_sub_idx,
                NULL AS rapel_dlk_idx,
                NULL AS rapel_inc_idx,
                a.attendance_date,
                a.employee_id,
                b.sub_type,
                b.status_confirm,
                a.check_in,
                a.check_out,
                a.shift_idx,
                'att' AS get_name,
                (SELECT check_in FROM dynamic_shift WHERE shift_idx = a.shift_idx AND `date` = a.attendance_date AND employee_id = a.employee_id) AS dynamic_check_in,
                a.value_overtime,
                a.overtime_confirm_date,
                (SELECT overtime_allowance FROM master_employee WHERE employee_id = a.employee_id) AS overtime_allowance,
                a.status_overtime,
                a.status_piket,
                a.status_except_um,
                0 AS rapel_ot,
                NULL as rapel_ot_date,
                0 AS rapel_sub,
                NULL AS rapel_sub_date,
                0 AS rapel_dlk,
                NULL AS rapel_dlk_date,
                0 AS rapel_inc,
                NULL AS rapel_inc_date,
                (SELECT shift_mode FROM master_shift WHERE idx = a.shift_idx) AS shift_mode,
                (SELECT monday_in FROM master_shift WHERE idx = a.shift_idx) AS monday,
                (SELECT tuesday_in FROM master_shift WHERE idx = a.shift_idx) AS tuesday,
                (SELECT wednesday_in FROM master_shift WHERE idx = a.shift_idx) AS wednesday,
                (SELECT thursday_in FROM master_shift WHERE idx = a.shift_idx) AS thursday,
                (SELECT friday_in FROM master_shift WHERE idx = a.shift_idx) AS friday,
                (SELECT saturday_in FROM master_shift WHERE idx = a.shift_idx) AS saturday,
                (SELECT sunday_in FROM master_shift WHERE idx = a.shift_idx) AS sunday
            FROM 
                    attendance_employee a
            LEFT JOIN 
                    submission_detail b
            ON a.attendance_date = b.sub_date AND a.employee_id = b.employee_id
            WHERE (DATE_FORMAT(a.check_in, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date')
            AND b.idx IS NULL
            AND a.status_draft_half = 0
            AND a.company_idx = $company_idx
            AND IF(0=1,0,(SELECT `status_delete` FROM `master_employee` WHERE `employee_id` = `a`.`employee_id`)) = 0

            UNION

            SELECT 
                NULL as idx,
                NULL AS rapel_ot_idx,
                NULL AS rapel_sub_idx,
                NULL AS rapel_dlk_idx,
                NULL AS rapel_inc_idx,
                g.sub_date AS attendance_date,
                g.employee_id,
                g.sub_type,
                g.status_confirm,
                NULL as check_in,
                NULL as check_out,
                (SELECT office_shift FROM master_employee WHERE employee_id = g.employee_id) as shift_idx,
                'sub' AS get_name,
                (SELECT check_in FROM dynamic_shift WHERE shift_idx = (SELECT office_shift FROM master_employee WHERE employee_id = g.employee_id) AND `date` = g.sub_date AND employee_id = g.employee_id) AS dynamic_check_in,
                0 AS value_overtime,
                NULL AS overtime_confirm_date,
                0 AS overtime_allowance,
                0 AS status_overtime,
                0 AS status_piket,
                0 AS status_except_um,
                0 AS rapel_ot,
                NULL as rapel_ot_date,
                0 AS rapel_sub,
                NULL AS rapel_sub_date,
                0 AS rapel_dlk,
                NULL AS rapel_dlk_date,
                0 AS rapel_inc,
                NULL AS rapel_inc_date,
                (SELECT shift_mode FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = g.employee_id)) AS shift_mode,
                (SELECT monday_in FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = g.employee_id)) as monday,
                (SELECT tuesday_in FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = g.employee_id)) as tuesday,
                (SELECT wednesday_in FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = g.employee_id)) as wednesday,
                (SELECT thursday_in FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = g.employee_id)) as thursday,
                (SELECT friday_in FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = g.employee_id)) as friday,
                (SELECT saturday_in FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = g.employee_id)) as saturday,
                (SELECT sunday_in FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = g.employee_id)) as sunday
            FROM 
                submission_detail g
            LEFT JOIN 
                attendance_employee h
            ON g.sub_date = h.attendance_date 
            AND g.employee_id = h.employee_id
            WHERE (g.sub_date BETWEEN '$from_date' AND '$to_date')
            AND g.company_idx = $company_idx
            AND h.idx IS NULL
            AND IF(0=1,0,(SELECT `status_delete` FROM `master_employee` WHERE `employee_id` = `g`.`employee_id`)) = 0
            
            UNION 
            
            SELECT 
                NULL AS idx,
                NULL AS rapel_ot_idx,
                a.idx AS rapel_sub_idx,
                NULL AS rapel_dlk_idx,
                NULL AS rapel_inc_idx,
                DATE_FORMAT(a.confirm_date, '%Y-%m-%d') AS attendance_date,
                a.employee_id,
                a.sub_type,
                a.status_confirm,
                NULL AS check_in,
                NULL AS check_out,
                (SELECT office_shift FROM master_employee WHERE employee_id = a.employee_id) as shift_idx,
                'rpl_sub' AS get_name,
                (SELECT check_in FROM dynamic_shift WHERE shift_idx = (SELECT office_shift FROM master_employee WHERE employee_id = a.employee_id) AND `date` = DATE_FORMAT(a.confirm_date, '%Y-%m-%d') AND employee_id = a.employee_id LIMIT 1) AS dynamic_check_in,
                0 AS value_overtime,
                NULL AS overtime_confirm_date,
                0 AS overtime_allowance,
                0 AS status_overtime,
                0 AS status_piket,
                0 AS status_except_um,
                0 AS rapel_ot,
                NULL as rapel_ot_date,
                (SELECT meal_allowance FROM master_employee WHERE employee_id = a.employee_id) AS rapel_sub,
                a.sub_date AS rapel_sub_date,
                0 AS rapel_dlk,
                NULL AS rapel_dlk_date,
                0 AS rapel_inc,
                NULL AS rapel_inc_date,
                (SELECT shift_mode FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = a.employee_id) LIMIT 1) AS shift_mode,
                (SELECT monday_in FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = a.employee_id) LIMIT 1) as monday,
                (SELECT tuesday_in FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = a.employee_id) LIMIT 1) as tuesday,
                (SELECT wednesday_in FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = a.employee_id) LIMIT 1) as wednesday,
                (SELECT thursday_in FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = a.employee_id) LIMIT 1) as thursday,
                (SELECT friday_in FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = a.employee_id) LIMIT 1) as friday,
                (SELECT saturday_in FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = a.employee_id) LIMIT 1) as saturday,
                (SELECT sunday_in FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = a.employee_id) LIMIT 1) as sunday
            FROM 
                submission_detail a
            LEFT JOIN 
                attendance_employee b
            ON DATE_FORMAT(b.attendance_date, '%Y-%m-%d') = DATE_FORMAT(a.confirm_date, '%Y-%m-%d')
            AND a.employee_id = b.employee_id
            LEFT JOIN 
                submission_detail c
            ON DATE_FORMAT(c.sub_date, '%Y-%m-%d') = DATE_FORMAT(a.confirm_date, '%Y-%m-%d')
            AND a.employee_id = c.employee_id
            WHERE (DATE_FORMAT(a.confirm_date, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date')
            AND a.sub_date < '$from_date'
            AND a.status_confirm = 1
            AND a.status_draft_paid = 0
            AND a.status_paid = 0
            AND a.status_rapel = 0
            AND a.company_idx = $company_idx
            AND IF(0=1,0,(SELECT `status_delete` FROM `master_employee` WHERE `employee_id` = `a`.`employee_id`)) = 0
            
            UNION 
            
            SELECT 
                NULL AS idx,
                a.idx AS rapel_ot_idx,
                NULL AS rapel_sub_idx,
                NULL AS rapel_dlk_idx,
                NULL AS rapel_inc_idx,
                DATE_FORMAT(a.overtime_confirm_date, '%Y-%m-%d') AS attendance_date,
                a.employee_id,
                NULL AS sub_type,
                NULL AS status_confirm,
                NULL AS check_in,
                NULL AS check_out,
                (SELECT office_shift FROM master_employee WHERE employee_id = a.employee_id) as shift_idx,
                'rpl_ot' AS get_name,
                (SELECT check_in FROM dynamic_shift WHERE shift_idx = (SELECT office_shift FROM master_employee WHERE employee_id = a.employee_id) AND `date` = DATE_FORMAT(a.overtime_confirm_date, '%Y-%m-%d') AND employee_id = a.employee_id LIMIT 1) AS dynamic_check_in,
                0 AS value_overtime,
                NULL AS overtime_confirm_date,
                0 AS overtime_allowance,
                0 AS status_overtime,
                0 AS status_piket,
                0 AS status_except_um,
                a.value_overtime AS rapel_ot,
                a.attendance_date as rapel_ot_date,
                0 AS rapel_sub,
                NULL AS rapel_sub_date,
                0 AS rapel_dlk,
                NULL AS rapel_dlk_date,
                0 AS rapel_inc,
                NULL AS rapel_inc_date,
                (SELECT shift_mode FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = a.employee_id) LIMIT 1) AS shift_mode,
                (SELECT monday_in FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = a.employee_id) LIMIT 1) as monday,
                (SELECT tuesday_in FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = a.employee_id) LIMIT 1) as tuesday,
                (SELECT wednesday_in FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = a.employee_id) LIMIT 1) as wednesday,
                (SELECT thursday_in FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = a.employee_id) LIMIT 1) as thursday,
                (SELECT friday_in FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = a.employee_id) LIMIT 1) as friday,
                (SELECT saturday_in FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = a.employee_id) LIMIT 1) as saturday,
                (SELECT sunday_in FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = a.employee_id) LIMIT 1) as sunday
            FROM 
                attendance_employee a
            LEFT JOIN 
                attendance_employee b
            ON DATE_FORMAT(b.attendance_date, '%Y-%m-%d') = DATE_FORMAT(a.overtime_confirm_date, '%Y-%m-%d')
            AND a.employee_id = b.employee_id
            LEFT JOIN 
                submission_detail c
            ON DATE_FORMAT(c.sub_date, '%Y-%m-%d') = DATE_FORMAT(a.overtime_confirm_date, '%Y-%m-%d')
            AND a.employee_id = c.employee_id
            WHERE (DATE_FORMAT(a.overtime_confirm_date, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date')
            AND a.attendance_date < '$from_date'
            AND a.status_overtime = 1
            AND a.status_draft_paid_ot = 0
            AND a.status_paid_ot = 0
            AND a.company_idx = $company_idx
            AND IF(0=1,0,(SELECT `status_delete` FROM `master_employee` WHERE `employee_id` = `a`.`employee_id`)) = 0
            
            UNION
            
            SELECT 
                NULL AS idx,
                NULL AS rapel_ot_idx,
                NULL AS rapel_sub_idx,
                NULL AS rapel_dlk_idx,
                a.idx AS rapel_inc_idx,
                DATE_FORMAT(a.confirm_date, '%Y-%m-%d') AS attendance_date,
                a.employee_id,
                NULL AS sub_type,
                NULL AS status_confirm,
                NULL AS check_in,
                NULL AS check_out,
                (SELECT office_shift FROM master_employee WHERE employee_id = a.employee_id) as shift_idx,
                'rpl_inc' AS get_name,
                (SELECT check_in FROM dynamic_shift WHERE shift_idx = (SELECT office_shift FROM master_employee WHERE employee_id = a.employee_id) AND `date` = DATE_FORMAT(a.confirm_date, '%Y-%m-%d') AND employee_id = a.employee_id LIMIT 1) AS dynamic_check_in,
                0 AS value_overtime,
                NULL AS overtime_confirm_date,
                0 AS overtime_allowance,
                0 AS status_overtime,
                0 AS status_piket,
                0 AS status_except_um,
                0 AS rapel_ot,
                NULL as rapel_ot_date,
                0 AS rapel_sub,
                NULL AS rapel_sub_date,
                0 AS rapel_dlk,
                NULL AS rapel_dlk_date,
                a.`value` AS rapel_inc,
                a.incentive_date AS rapel_inc_date,
                (SELECT shift_mode FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = a.employee_id) LIMIT 1) AS shift_mode,
                (SELECT monday_in FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = a.employee_id) LIMIT 1) as monday,
                (SELECT tuesday_in FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = a.employee_id) LIMIT 1) as tuesday,
                (SELECT wednesday_in FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = a.employee_id) LIMIT 1) as wednesday,
                (SELECT thursday_in FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = a.employee_id) LIMIT 1) as thursday,
                (SELECT friday_in FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = a.employee_id) LIMIT 1) as friday,
                (SELECT saturday_in FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = a.employee_id) LIMIT 1) as saturday,
                (SELECT sunday_in FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = a.employee_id) LIMIT 1) as sunday
            FROM 
                incentive_detail a
            LEFT JOIN 
                attendance_employee b
            ON DATE_FORMAT(b.attendance_date, '%Y-%m-%d') = DATE_FORMAT(a.confirm_date, '%Y-%m-%d')
            AND a.employee_id = b.employee_id
            LEFT JOIN 
                submission_detail c
            ON DATE_FORMAT(c.sub_date, '%Y-%m-%d') = DATE_FORMAT(a.confirm_date, '%Y-%m-%d')
            AND a.employee_id = c.employee_id
            WHERE (DATE_FORMAT(a.confirm_date, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date')
            AND a.incentive_date < '$from_date'
            AND a.status_confirm = 1
            AND a.status_draft_paid = 0
            AND a.status_paid = 0
            AND a.status_rapel = 0
            AND a.company_idx = $company_idx
            AND IF(0=1,0,(SELECT `status_delete` FROM `master_employee` WHERE `employee_id` = `a`.`employee_id`)) = 0
            
            UNION
            
            SELECT 
                NULL AS idx,
                NULL AS rapel_ot_idx,
                NULL AS rapel_sub_idx,
                a.idx AS rapel_dlk_idx,
                NULL AS rapel_inc_idx,
                DATE_FORMAT(a.confirm_date, '%Y-%m-%d') AS attendance_date,
                a.employee_id,
                NULL AS sub_type,
                NULL AS status_confirm,
                NULL AS check_in,
                NULL AS check_out,
                (SELECT office_shift FROM master_employee WHERE employee_id = a.employee_id) as shift_idx,
                'rpl_dlk' AS get_name,
                (SELECT check_in FROM dynamic_shift WHERE shift_idx = (SELECT office_shift FROM master_employee WHERE employee_id = a.employee_id) AND `date` = DATE_FORMAT(a.confirm_date, '%Y-%m-%d') AND employee_id = a.employee_id LIMIT 1) AS dynamic_check_in,
                0 AS value_overtime,
                NULL AS overtime_confirm_date,
                0 AS overtime_allowance,
                0 AS status_overtime,
                0 AS status_piket,
                0 AS status_except_um,
                0 AS rapel_ot,
                NULL as rapel_ot_date,
                0 AS rapel_sub,
                NULL AS rapel_sub_date,
                a.`value` AS rapel_dlk,
                a.dlk_date AS rapel_dlk_date,
                0 AS rapel_inc,
                NULL AS rapel_inc_date,
                (SELECT shift_mode FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = a.employee_id) LIMIT 1) AS shift_mode,
                (SELECT monday_in FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = a.employee_id) LIMIT 1) as monday,
                (SELECT tuesday_in FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = a.employee_id) LIMIT 1) as tuesday,
                (SELECT wednesday_in FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = a.employee_id) LIMIT 1) as wednesday,
                (SELECT thursday_in FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = a.employee_id) LIMIT 1) as thursday,
                (SELECT friday_in FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = a.employee_id) LIMIT 1) as friday,
                (SELECT saturday_in FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = a.employee_id) LIMIT 1) as saturday,
                (SELECT sunday_in FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = a.employee_id) LIMIT 1) as sunday
            FROM 
                dlk_detail a
            LEFT JOIN 
                attendance_employee b
            ON DATE_FORMAT(b.attendance_date, '%Y-%m-%d') = DATE_FORMAT(a.confirm_date, '%Y-%m-%d')
            AND a.employee_id = b.employee_id
            LEFT JOIN 
                submission_detail c
            ON DATE_FORMAT(c.sub_date, '%Y-%m-%d') = DATE_FORMAT(a.confirm_date, '%Y-%m-%d')
            AND a.employee_id = c.employee_id
            WHERE (DATE_FORMAT(a.confirm_date, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date')
            AND a.dlk_date < '$from_date'
            AND a.status_confirm = 1
            AND a.status_draft_paid = 0
            AND a.status_paid = 0
            AND a.status_rapel = 0
            AND a.company_idx = $company_idx
            AND IF(0=1,0,(SELECT `status_delete` FROM `master_employee` WHERE `employee_id` = `a`.`employee_id`)) = 0";
        // $this->db->select('a.*')
        // ->from('attendance_employee a')
        // ->where(['(DATE_FORMAT(a.check_in, "%Y-%m-%d") between "'.$from_date.'" and "'.$to_date.'")' => null, 'a.company_idx' => $company_idx, 'a.status_draft_half' => 0, 'IF(0=1,0,(SELECT `status_delete` FROM `master_employee` WHERE `employee_id` = `a`.`employee_id`)) =' => 0]);
        // $result = $this->db->get_compiled_select();
        // var_dump('<pre>');var_dump($query);die;
        $result = $this->db->query($query)->result();
        return $result;
    }

    public function getUmtDatax2($from_date, $to_date, $company_idx){
        $query = "SELECT 
                a.idx,
                IFNULL(a.attendance_date, b.sub_date) AS attendance_date,
                a.employee_id,
                b.sub_type,
                b.status_confirm,
                a.check_in,
                a.check_out,
                a.shift_idx,
                (SELECT check_in FROM dynamic_shift WHERE shift_idx = a.shift_idx AND `date` = IFNULL(a.attendance_date, b.sub_date) AND employee_id = a.employee_id) as dynamic_check_in,
                a.value_overtime,
                (SELECT overtime_allowance FROM master_employee WHERE employee_id = a.employee_id) AS overtime_allowance,
                a.status_overtime,
                a.status_piket,
                (SELECT shift_mode FROM master_shift WHERE idx = a.shift_idx) AS shift_mode,
                (SELECT monday_in FROM master_shift WHERE idx = a.shift_idx) as monday,
                (SELECT tuesday_in FROM master_shift WHERE idx = a.shift_idx) as tuesday,
                (SELECT wednesday_in FROM master_shift WHERE idx = a.shift_idx) as wednesday,
                (SELECT thursday_in FROM master_shift WHERE idx = a.shift_idx) as thursday,
                (SELECT friday_in FROM master_shift WHERE idx = a.shift_idx) as friday,
                (SELECT saturday_in FROM master_shift WHERE idx = a.shift_idx) as saturday,
                (SELECT sunday_in FROM master_shift WHERE idx = a.shift_idx) as sunday
            FROM 
                attendance_employee a
            LEFT JOIN 
                submission_detail b
            ON a.attendance_date = b.sub_date AND a.employee_id = b.employee_id
            WHERE DATE_FORMAT(a.check_in, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date'
            AND a.company_idx = $company_idx
            AND a.status_draft_half = 0
            AND IF(0=1,0,(SELECT `status_delete` FROM `master_employee` WHERE `employee_id` = `a`.`employee_id`)) = 0
            
            UNION
            
            SELECT 
                NULL AS idx,
                c.sub_date AS attendance_date,
                c.employee_id,
                c.sub_type,
                c.status_confirm,
                NULL AS check_in,
                NULL AS check_out,
                (SELECT office_shift FROM master_employee WHERE employee_id = c.employee_id) AS shift_idx,
                (SELECT check_in FROM dynamic_shift WHERE shift_idx = (SELECT office_shift FROM master_employee WHERE employee_id = c.employee_id) AND `date` = c.sub_date AND employee_id = c.employee_id) AS dynamic_check_in,
                0 AS value_overtime,
                0 AS overtime_allowance,
                0 AS status_overtime,
                0 AS status_piket,
                (SELECT shift_mode FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = c.employee_id)) AS shift_mode,
                (SELECT monday_in FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = c.employee_id)) as monday,
                (SELECT tuesday_in FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = c.employee_id)) as tuesday,
                (SELECT wednesday_in FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = c.employee_id)) as wednesday,
                (SELECT thursday_in FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = c.employee_id)) as thursday,
                (SELECT friday_in FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = c.employee_id)) as friday,
                (SELECT saturday_in FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = c.employee_id)) as saturday,
                (SELECT sunday_in FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = c.employee_id)) as sunday
            FROM 
                submission_detail c
            LEFT JOIN 
                attendance_employee d
            ON 
                c.sub_date = d.attendance_date 
                AND c.employee_id = d.employee_id
            WHERE c.sub_date BETWEEN '$from_date' AND '$to_date'
            AND	d.attendance_date IS NULL
            AND c.company_idx = $company_idx
            AND IF(0=1,0,(SELECT `status_delete` FROM `master_employee` WHERE `employee_id` = `c`.`employee_id`)) = 0";
        $this->db->select('a.*')
        ->from('attendance_employee a')
        ->where(['(DATE_FORMAT(a.check_in, "%Y-%m-%d") between "'.$from_date.'" and "'.$to_date.'")' => null, 'a.company_idx' => $company_idx, 'a.status_draft_half' => 0, 'IF(0=1,0,(SELECT `status_delete` FROM `master_employee` WHERE `employee_id` = `a`.`employee_id`)) =' => 0]);
        // $result = $this->db->get_compiled_select();
        $result = $this->db->get()->result();
        return $result;
    }

    public function getUmtDataEscDraftedx($from_date, $to_date, $company_idx){
        $this->db->select('a.*, (SELECT employee_name FROM master_employee WHERE employee_id = a.employee_id) as employee_name')
        ->from('attendance_employee a')
        // ->where('a.employee_id', 86)
        ->where(['(DATE_FORMAT(a.check_in, "%Y-%m-%d") between "'.$from_date.'" and "'.$to_date.'")' => null, 'a.company_idx' => $company_idx]);
        // $result = $this->db->get_compiled_select();
        $result = $this->db->get()->result();
        return $result;
    }

    public function getUmtDataEscDrafted($from_date, $to_date, $company_idx){
        $query = "SELECT 
                a.idx,
                NULL AS rapel_ot_idx,
                NULL AS rapel_sub_idx,
                NULL AS rapel_dlk_idx,
                NULL AS rapel_inc_idx,
                a.attendance_date,
                a.employee_id,
                b.sub_type,
                b.status_confirm,
                a.check_in,
                a.check_out,
                a.shift_idx,
                'att' AS get_name,
                (SELECT check_in FROM dynamic_shift WHERE shift_idx = a.shift_idx AND `date` = a.attendance_date AND employee_id = a.employee_id) AS dynamic_check_in,
                a.value_overtime,
                a.overtime_confirm_date,
                (SELECT overtime_allowance FROM master_employee WHERE employee_id = a.employee_id) AS overtime_allowance,
                a.status_overtime,
                a.status_piket,
                a.status_except_um,
                0 AS rapel_ot,
                NULL as rapel_ot_date,
                0 AS rapel_sub,
                NULL AS rapel_sub_date,
                0 AS rapel_dlk,
                NULL AS rapel_dlk_date,
                0 AS rapel_inc,
                NULL AS rapel_inc_date,
                (SELECT shift_mode FROM master_shift WHERE idx = a.shift_idx) AS shift_mode,
                (SELECT monday_in FROM master_shift WHERE idx = a.shift_idx) AS monday,
                (SELECT tuesday_in FROM master_shift WHERE idx = a.shift_idx) AS tuesday,
                (SELECT wednesday_in FROM master_shift WHERE idx = a.shift_idx) AS wednesday,
                (SELECT thursday_in FROM master_shift WHERE idx = a.shift_idx) AS thursday,
                (SELECT friday_in FROM master_shift WHERE idx = a.shift_idx) AS friday,
                (SELECT saturday_in FROM master_shift WHERE idx = a.shift_idx) AS saturday,
                (SELECT sunday_in FROM master_shift WHERE idx = a.shift_idx) AS sunday
            FROM 
                    attendance_employee a
            LEFT JOIN 
                    submission_detail b
            ON a.attendance_date = b.sub_date AND a.employee_id = b.employee_id
            WHERE (DATE_FORMAT(a.check_in, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date')
            AND b.idx IS NULL
            AND a.company_idx = $company_idx
            AND IF(0=1,0,(SELECT `status_delete` FROM `master_employee` WHERE `employee_id` = `a`.`employee_id`)) = 0

            UNION

            SELECT 
                NULL as idx,
                NULL AS rapel_ot_idx,
                NULL AS rapel_sub_idx,
                NULL AS rapel_dlk_idx,
                NULL AS rapel_inc_idx,
                g.sub_date AS attendance_date,
                g.employee_id,
                g.sub_type,
                g.status_confirm,
                NULL as check_in,
                NULL as check_out,
                (SELECT office_shift FROM master_employee WHERE employee_id = g.employee_id) as shift_idx,
                'sub' AS get_name,
                (SELECT check_in FROM dynamic_shift WHERE shift_idx = (SELECT office_shift FROM master_employee WHERE employee_id = g.employee_id) AND `date` = g.sub_date AND employee_id = g.employee_id) AS dynamic_check_in,
                0 AS value_overtime,
                NULL AS overtime_confirm_date,
                0 AS overtime_allowance,
                0 AS status_overtime,
                0 AS status_piket,
                0 AS status_except_um,
                0 AS rapel_ot,
                NULL as rapel_ot_date,
                0 AS rapel_sub,
                NULL AS rapel_sub_date,
                0 AS rapel_dlk,
                NULL AS rapel_dlk_date,
                0 AS rapel_inc,
                NULL AS rapel_inc_date,
                (SELECT shift_mode FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = g.employee_id)) AS shift_mode,
                (SELECT monday_in FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = g.employee_id)) as monday,
                (SELECT tuesday_in FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = g.employee_id)) as tuesday,
                (SELECT wednesday_in FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = g.employee_id)) as wednesday,
                (SELECT thursday_in FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = g.employee_id)) as thursday,
                (SELECT friday_in FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = g.employee_id)) as friday,
                (SELECT saturday_in FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = g.employee_id)) as saturday,
                (SELECT sunday_in FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = g.employee_id)) as sunday
            FROM 
                submission_detail g
            LEFT JOIN 
                attendance_employee h
            ON g.sub_date = h.attendance_date 
            AND g.employee_id = h.employee_id
            WHERE (g.sub_date BETWEEN '$from_date' AND '$to_date')
            AND g.company_idx = $company_idx
            AND h.idx IS NULL
            AND IF(0=1,0,(SELECT `status_delete` FROM `master_employee` WHERE `employee_id` = `g`.`employee_id`)) = 0
            
            UNION 
            
            SELECT 
                NULL AS idx,
                NULL AS rapel_ot_idx,
                a.idx AS rapel_sub_idx,
                NULL AS rapel_dlk_idx,
                NULL AS rapel_inc_idx,
                DATE_FORMAT(a.confirm_date, '%Y-%m-%d') AS attendance_date,
                a.employee_id,
                a.sub_type,
                a.status_confirm,
                NULL AS check_in,
                NULL AS check_out,
                (SELECT office_shift FROM master_employee WHERE employee_id = a.employee_id) as shift_idx,
                'rpl_sub' AS get_name,
                (SELECT check_in FROM dynamic_shift WHERE shift_idx = (SELECT office_shift FROM master_employee WHERE employee_id = a.employee_id) AND `date` = DATE_FORMAT(a.confirm_date, '%Y-%m-%d') AND employee_id = a.employee_id LIMIT 1) AS dynamic_check_in,
                0 AS value_overtime,
                NULL AS overtime_confirm_date,
                0 AS overtime_allowance,
                0 AS status_overtime,
                0 AS status_piket,
                0 AS status_except_um,
                0 AS rapel_ot,
                NULL as rapel_ot_date,
                (SELECT meal_allowance FROM master_employee WHERE employee_id = a.employee_id) AS rapel_sub,
                a.sub_date AS rapel_sub_date,
                0 AS rapel_dlk,
                NULL AS rapel_dlk_date,
                0 AS rapel_inc,
                NULL AS rapel_inc_date,
                (SELECT shift_mode FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = a.employee_id) LIMIT 1) AS shift_mode,
                (SELECT monday_in FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = a.employee_id) LIMIT 1) as monday,
                (SELECT tuesday_in FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = a.employee_id) LIMIT 1) as tuesday,
                (SELECT wednesday_in FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = a.employee_id) LIMIT 1) as wednesday,
                (SELECT thursday_in FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = a.employee_id) LIMIT 1) as thursday,
                (SELECT friday_in FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = a.employee_id) LIMIT 1) as friday,
                (SELECT saturday_in FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = a.employee_id) LIMIT 1) as saturday,
                (SELECT sunday_in FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = a.employee_id) LIMIT 1) as sunday
            FROM 
                submission_detail a
            LEFT JOIN 
                attendance_employee b
            ON DATE_FORMAT(b.attendance_date, '%Y-%m-%d') = DATE_FORMAT(a.confirm_date, '%Y-%m-%d')
            AND a.employee_id = b.employee_id
            LEFT JOIN 
                submission_detail c
            ON DATE_FORMAT(c.sub_date, '%Y-%m-%d') = DATE_FORMAT(a.confirm_date, '%Y-%m-%d')
            AND a.employee_id = c.employee_id
            WHERE (DATE_FORMAT(a.confirm_date, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date')
            AND a.sub_date < '$from_date'
            AND a.status_confirm = 1
            AND a.status_draft_paid = 0
            AND a.status_paid = 0
            AND a.status_rapel = 0
            AND a.company_idx = $company_idx
            AND IF(0=1,0,(SELECT `status_delete` FROM `master_employee` WHERE `employee_id` = `a`.`employee_id`)) = 0
            
            UNION 
            
            SELECT 
                NULL AS idx,
                a.idx AS rapel_ot_idx,
                NULL AS rapel_sub_idx,
                NULL AS rapel_dlk_idx,
                NULL AS rapel_inc_idx,
                DATE_FORMAT(a.overtime_confirm_date, '%Y-%m-%d') AS attendance_date,
                a.employee_id,
                NULL AS sub_type,
                NULL AS status_confirm,
                NULL AS check_in,
                NULL AS check_out,
                (SELECT office_shift FROM master_employee WHERE employee_id = a.employee_id) as shift_idx,
                'rpl_ot' AS get_name,
                (SELECT check_in FROM dynamic_shift WHERE shift_idx = (SELECT office_shift FROM master_employee WHERE employee_id = a.employee_id) AND `date` = DATE_FORMAT(a.overtime_confirm_date, '%Y-%m-%d') AND employee_id = a.employee_id LIMIT 1) AS dynamic_check_in,
                0 AS value_overtime,
                NULL AS overtime_confirm_date,
                0 AS overtime_allowance,
                0 AS status_overtime,
                0 AS status_piket,
                0 AS status_except_um,
                a.value_overtime AS rapel_ot,
                a.attendance_date as rapel_ot_date,
                0 AS rapel_sub,
                NULL AS rapel_sub_date,
                0 AS rapel_dlk,
                NULL AS rapel_dlk_date,
                0 AS rapel_inc,
                NULL AS rapel_inc_date,
                (SELECT shift_mode FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = a.employee_id) LIMIT 1) AS shift_mode,
                (SELECT monday_in FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = a.employee_id) LIMIT 1) as monday,
                (SELECT tuesday_in FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = a.employee_id) LIMIT 1) as tuesday,
                (SELECT wednesday_in FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = a.employee_id) LIMIT 1) as wednesday,
                (SELECT thursday_in FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = a.employee_id) LIMIT 1) as thursday,
                (SELECT friday_in FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = a.employee_id) LIMIT 1) as friday,
                (SELECT saturday_in FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = a.employee_id) LIMIT 1) as saturday,
                (SELECT sunday_in FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = a.employee_id) LIMIT 1) as sunday
            FROM 
                attendance_employee a
            LEFT JOIN 
                attendance_employee b
            ON DATE_FORMAT(b.attendance_date, '%Y-%m-%d') = DATE_FORMAT(a.overtime_confirm_date, '%Y-%m-%d')
            AND a.employee_id = b.employee_id
            LEFT JOIN 
                submission_detail c
            ON DATE_FORMAT(c.sub_date, '%Y-%m-%d') = DATE_FORMAT(a.overtime_confirm_date, '%Y-%m-%d')
            AND a.employee_id = c.employee_id
            WHERE (DATE_FORMAT(a.overtime_confirm_date, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date')
            AND a.attendance_date < '$from_date'
            AND a.status_overtime = 1
            AND a.status_draft_paid_ot = 0
            AND a.status_paid_ot = 0
            AND a.company_idx = $company_idx
            AND IF(0=1,0,(SELECT `status_delete` FROM `master_employee` WHERE `employee_id` = `a`.`employee_id`)) = 0
            
            UNION
            
            SELECT 
                NULL AS idx,
                NULL AS rapel_ot_idx,
                NULL AS rapel_sub_idx,
                NULL AS rapel_dlk_idx,
                a.idx AS rapel_inc_idx,
                DATE_FORMAT(a.confirm_date, '%Y-%m-%d') AS attendance_date,
                a.employee_id,
                NULL AS sub_type,
                NULL AS status_confirm,
                NULL AS check_in,
                NULL AS check_out,
                (SELECT office_shift FROM master_employee WHERE employee_id = a.employee_id) as shift_idx,
                'rpl_inc' AS get_name,
                (SELECT check_in FROM dynamic_shift WHERE shift_idx = (SELECT office_shift FROM master_employee WHERE employee_id = a.employee_id) AND `date` = DATE_FORMAT(a.confirm_date, '%Y-%m-%d') AND employee_id = a.employee_id LIMIT 1) AS dynamic_check_in,
                0 AS value_overtime,
                NULL AS overtime_confirm_date,
                0 AS overtime_allowance,
                0 AS status_overtime,
                0 AS status_piket,
                0 AS status_except_um,
                0 AS rapel_ot,
                NULL as rapel_ot_date,
                0 AS rapel_sub,
                NULL AS rapel_sub_date,
                0 AS rapel_dlk,
                NULL AS rapel_dlk_date,
                a.`value` AS rapel_inc,
                a.incentive_date AS rapel_inc_date,
                (SELECT shift_mode FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = a.employee_id) LIMIT 1) AS shift_mode,
                (SELECT monday_in FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = a.employee_id) LIMIT 1) as monday,
                (SELECT tuesday_in FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = a.employee_id) LIMIT 1) as tuesday,
                (SELECT wednesday_in FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = a.employee_id) LIMIT 1) as wednesday,
                (SELECT thursday_in FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = a.employee_id) LIMIT 1) as thursday,
                (SELECT friday_in FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = a.employee_id) LIMIT 1) as friday,
                (SELECT saturday_in FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = a.employee_id) LIMIT 1) as saturday,
                (SELECT sunday_in FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = a.employee_id) LIMIT 1) as sunday
            FROM 
                incentive_detail a
            LEFT JOIN 
                attendance_employee b
            ON DATE_FORMAT(b.attendance_date, '%Y-%m-%d') = DATE_FORMAT(a.confirm_date, '%Y-%m-%d')
            AND a.employee_id = b.employee_id
            LEFT JOIN 
                submission_detail c
            ON DATE_FORMAT(c.sub_date, '%Y-%m-%d') = DATE_FORMAT(a.confirm_date, '%Y-%m-%d')
            AND a.employee_id = c.employee_id
            WHERE (DATE_FORMAT(a.confirm_date, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date')
            AND a.incentive_date < '$from_date'
            AND a.status_confirm = 1
            AND a.status_draft_paid = 0
            AND a.status_paid = 0
            AND a.status_rapel = 0
            AND a.company_idx = $company_idx
            AND IF(0=1,0,(SELECT `status_delete` FROM `master_employee` WHERE `employee_id` = `a`.`employee_id`)) = 0
            
            UNION
            
            SELECT 
                NULL AS idx,
                NULL AS rapel_ot_idx,
                NULL AS rapel_sub_idx,
                a.idx AS rapel_dlk_idx,
                NULL AS rapel_inc_idx,
                DATE_FORMAT(a.confirm_date, '%Y-%m-%d') AS attendance_date,
                a.employee_id,
                NULL AS sub_type,
                NULL AS status_confirm,
                NULL AS check_in,
                NULL AS check_out,
                (SELECT office_shift FROM master_employee WHERE employee_id = a.employee_id) as shift_idx,
                'rpl_dlk' AS get_name,
                (SELECT check_in FROM dynamic_shift WHERE shift_idx = (SELECT office_shift FROM master_employee WHERE employee_id = a.employee_id) AND `date` = DATE_FORMAT(a.confirm_date, '%Y-%m-%d') AND employee_id = a.employee_id LIMIT 1) AS dynamic_check_in,
                0 AS value_overtime,
                NULL AS overtime_confirm_date,
                0 AS overtime_allowance,
                0 AS status_overtime,
                0 AS status_piket,
                0 AS status_except_um,
                0 AS rapel_ot,
                NULL as rapel_ot_date,
                0 AS rapel_sub,
                NULL AS rapel_sub_date,
                a.`value` AS rapel_dlk,
                a.dlk_date AS rapel_dlk_date,
                0 AS rapel_inc,
                NULL AS rapel_inc_date,
                (SELECT shift_mode FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = a.employee_id) LIMIT 1) AS shift_mode,
                (SELECT monday_in FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = a.employee_id) LIMIT 1) as monday,
                (SELECT tuesday_in FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = a.employee_id) LIMIT 1) as tuesday,
                (SELECT wednesday_in FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = a.employee_id) LIMIT 1) as wednesday,
                (SELECT thursday_in FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = a.employee_id) LIMIT 1) as thursday,
                (SELECT friday_in FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = a.employee_id) LIMIT 1) as friday,
                (SELECT saturday_in FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = a.employee_id) LIMIT 1) as saturday,
                (SELECT sunday_in FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = a.employee_id) LIMIT 1) as sunday
            FROM 
                dlk_detail a
            LEFT JOIN 
                attendance_employee b
            ON DATE_FORMAT(b.attendance_date, '%Y-%m-%d') = DATE_FORMAT(a.confirm_date, '%Y-%m-%d')
            AND a.employee_id = b.employee_id
            LEFT JOIN 
                submission_detail c
            ON DATE_FORMAT(c.sub_date, '%Y-%m-%d') = DATE_FORMAT(a.confirm_date, '%Y-%m-%d')
            AND a.employee_id = c.employee_id
            WHERE (DATE_FORMAT(a.confirm_date, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date')
            AND a.dlk_date < '$from_date'
            AND a.status_confirm = 1
            AND a.status_draft_paid = 0
            AND a.status_paid = 0
            AND a.status_rapel = 0
            AND a.company_idx = $company_idx
            AND IF(0=1,0,(SELECT `status_delete` FROM `master_employee` WHERE `employee_id` = `a`.`employee_id`)) = 0";
        $result = $this->db->query($query)->result();
        return $result;
    }

    public function getUmtDataEscDraftedRev1($from_date, $to_date, $company_idx){
        $query = "SELECT 
                    a.idx,
                    NULL AS rapel_ot_idx,
                    NULL AS rapel_sub_idx,
                    NULL AS rapel_dlk_idx,
                    NULL AS rapel_inc_idx,
                    IFNULL(a.attendance_date, b.sub_date) AS attendance_date,
                    a.employee_id,
                    b.sub_type,
                    b.status_confirm,
                    a.check_in,
                    a.check_out,
                    a.shift_idx,
                    'att' AS get_name,
                    (SELECT check_in FROM dynamic_shift WHERE shift_idx = a.shift_idx AND `date` = IFNULL(a.attendance_date, b.sub_date) AND employee_id = a.employee_id) AS dynamic_check_in,
                    a.value_overtime,
                    a.overtime_confirm_date,
                    (SELECT overtime_allowance FROM master_employee WHERE employee_id = a.employee_id) AS overtime_allowance,
                    a.status_overtime,
                    a.status_piket,
                    f.value_overtime AS rapel_ot,
                    f.attendance_dateL as rapel_ot_date,
                    e.value AS rapel_sub,
                    e.sub_date AS rapel_sub_date,
                    c.value AS rapel_dlk,
                    c.dlk_date AS rapel_dlk_date,
                    d.value AS rapel_inc,
                    d.incentive_date AS rapel_inc_date,
                    (SELECT shift_mode FROM master_shift WHERE idx = a.shift_idx) AS shift_mode,
                    (SELECT monday_in FROM master_shift WHERE idx = a.shift_idx) AS monday,
                    (SELECT tuesday_in FROM master_shift WHERE idx = a.shift_idx) AS tuesday,
                    (SELECT wednesday_in FROM master_shift WHERE idx = a.shift_idx) AS wednesday,
                    (SELECT thursday_in FROM master_shift WHERE idx = a.shift_idx) AS thursday,
                    (SELECT friday_in FROM master_shift WHERE idx = a.shift_idx) AS friday,
                    (SELECT saturday_in FROM master_shift WHERE idx = a.shift_idx) AS saturday,
                    (SELECT sunday_in FROM master_shift WHERE idx = a.shift_idx) AS sunday
            FROM 
                    attendance_employee a
            LEFT JOIN 
                    submission_detail b
            ON a.attendance_date = b.sub_date AND a.employee_id = b.employee_id
            LEFT JOIN
                    dlk_detail c
            ON a.attendance_date = DATE_FORMAT(c.confirm_date, '%Y-%m-%d') 
            AND a.employee_id = c.employee_id 
            AND c.dlk_date < '$from_date' 
            AND c.status_rapel = 0
            AND c.status_paid = 0
            AND c.status_draft_paid = 0
            LEFT JOIN
                    incentive_detail d
            ON a.attendance_date = DATE_FORMAT(d.confirm_date, '%Y-%m-%d') 
            AND a.employee_id = d.employee_id 
            AND d.incentive_date < '$from_date'
            AND d.status_rapel = 0
            AND d.status_paid = 0
            AND d.status_draft_paid = 0
            LEFT JOIN
                submission_detail e
            ON a.attendance_date = DATE_FORMAT(e.confirm_date, '%Y-%m-%d') 
            AND a.employee_id = e.employee_id 
            AND e.incentive_date < '$from_date'
            AND e.status_rapel = 0
            AND e.status_paid = 0
            AND e.status_draft_paid = 0
            LEFT JOIN
                attendance_employee f
            ON a.attendance_date = DATE_FORMAT(f.overtime_confirm_date, '%Y-%m-%d') 
            AND a.employee_id = f.employee_id 
            AND f.attendance_date < '$from_date'
            AND f.status_overtime = 0
            AND f.status_paid_ot = 0
            AND f.status_draft_paid_ot = 0
            WHERE (DATE_FORMAT(a.check_in, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date')
            AND a.company_idx = $company_idx
            AND IF(0=1,0,(SELECT `status_delete` FROM `master_employee` WHERE `employee_id` = `a`.`employee_id`)) = 0

            UNION

            SELECT 
                NULL as idx,
                (SELECT idx FROM attendance_employee WHERE DATE_FORMAT(overtime_confirm_date, '%Y-%m-%d') = g.sub_date AND employee_id = g.employee_id AND attendance_date < '$from_date' AND status_overtime  = 1 AND status_paid_ot = 0 AND status_draft_paid_ot = 0) AS rapel_ot_idx,
                (SELECT idx FROM submission_detail WHERE DATE_FORMAT(confirm_date, '%Y-%m-%d') = g.sub_date AND employee_id = g.employee_id AND sub_date < '$from_date' AND status_rapel = 0 AND status_draft_paid = 0 AND status_paid = 0) AS rapel_sub_idx,
                (SELECT idx FROM dlk_detail WHERE DATE_FORMAT(confirm_date, '%Y-%m-%d') = g.sub_date AND employee_id = g.employee_id AND dlk_date < '$from_date' AND status_rapel = 0 AND status_draft_paid = 0 AND status_paid = 0) AS rapel_dlk_idx,
                (SELECT idx FROM incentive_detail WHERE DATE_FORMAT(confirm_date, '%Y-%m-%d') = g.sub_date AND employee_id = g.employee_id AND incentive_date < '$from_date' AND status_rapel = 0 AND status_draft_paid = 0 AND status_paid = 0) AS rapel_inc_idx,
                g.sub_date AS attendance_date,
                g.employee_id,
                g.sub_type,
                g.status_confirm,
                NULL as check_in,
                NULL as check_out,
                (SELECT office_shift FROM master_employee WHERE employee_id = g.employee_id) as shift_idx,
                'sub' AS get_name,
                (SELECT check_in FROM dynamic_shift WHERE shift_idx = (SELECT office_shift FROM master_employee WHERE employee_id = g.employee_id) AND `date` = g.sub_date AND employee_id = g.employee_id) AS dynamic_check_in,
                0 AS value_overtime,
                NULL AS overtime_confirm_date,
                0 AS overtime_allowance,
                0 AS status_overtime,
                0 AS status_piket,
                (SELECT value_overtime FROM attendance_employee WHERE DATE_FORMAT(overtime_confirm_date, '%Y-%m-%d') = g.sub_date AND employee_id = g.employee_id AND attendance_date < '$from_date' AND status_overtime  = 1 AND status_paid_ot = 0 AND status_draft_paid_ot = 0) AS rapel_ot,
                (SELECT attendance_date FROM attendance_employee WHERE DATE_FORMAT(overtime_confirm_date, '%Y-%m-%d') = g.sub_date AND employee_id = g.employee_id AND attendance_date < '$from_date' AND status_overtime  = 1 AND status_paid_ot = 0 AND status_draft_paid_ot = 0) as rapel_ot_date,
                (SELECT (SELECT meal_allowance FROM master_employee WHERE employee_id = submission_detail.employee_id) FROM submission_detail WHERE DATE_FORMAT(confirm_date, '%Y-%m-%d') = g.sub_date AND employee_id = g.employee_id AND sub_date < '$from_date' AND status_rapel = 0 AND status_draft_paid = 0 AND status_paid = 0) AS rapel_sub,
                (SELECT sub_date FROM submission_detail WHERE DATE_FORMAT(confirm_date, '%Y-%m-%d') = g.sub_date AND employee_id = g.employee_id AND sub_date < '$from_date' AND status_rapel = 0 AND status_draft_paid = 0 AND status_paid = 0) AS rapel_sub_date,
                (SELECT `value` FROM dlk_detail WHERE DATE_FORMAT(confirm_date, '%Y-%m-%d') = g.sub_date AND employee_id = g.employee_id AND dlk_date < '$from_date' AND status_rapel = 0 AND status_draft_paid = 0 AND status_paid = 0) AS rapel_dlk,
                (SELECT dlk_date FROM dlk_detail WHERE DATE_FORMAT(confirm_date, '%Y-%m-%d') = g.sub_date AND employee_id = g.employee_id AND dlk_date < '$from_date' AND status_rapel = 0 AND status_draft_paid = 0 AND status_paid = 0) AS rapel_dlk_date,
                (SELECT `value` FROM incentive_detail WHERE DATE_FORMAT(confirm_date, '%Y-%m-%d') = g.sub_date AND employee_id = g.employee_id AND incentive_date < '$from_date' AND status_rapel = 0 AND status_draft_paid = 0 AND status_paid = 0) AS rapel_inc,
                (SELECT incentive_date FROM incentive_detail WHERE DATE_FORMAT(confirm_date, '%Y-%m-%d') = g.sub_date AND employee_id = g.employee_id AND incentive_date < '$from_date' AND status_rapel = 0 AND status_draft_paid = 0 AND status_paid = 0) AS rapel_inc_date,
                (SELECT shift_mode FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = g.employee_id)) AS shift_mode,
                (SELECT monday_in FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = g.employee_id)) as monday,
                (SELECT tuesday_in FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = g.employee_id)) as tuesday,
                (SELECT wednesday_in FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = g.employee_id)) as wednesday,
                (SELECT thursday_in FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = g.employee_id)) as thursday,
                (SELECT friday_in FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = g.employee_id)) as friday,
                (SELECT saturday_in FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = g.employee_id)) as saturday,
                (SELECT sunday_in FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = g.employee_id)) as sunday
            FROM 
                submission_detail g
            LEFT JOIN 
                attendance_employee h
            ON g.sub_date = h.attendance_date 
            AND g.employee_id = h.employee_id
            WHERE (g.sub_date BETWEEN '$from_date' AND '$to_date')
            AND h.idx IS NULL
            AND IF(0=1,0,(SELECT `status_delete` FROM `master_employee` WHERE `employee_id` = `g`.`employee_id`)) = 0";
        $result = $this->db->query($query)->result();
        return $result;
    }

    public function getUmtDataEscDraftedx2($from_date, $to_date, $company_idx){
        $query = "SELECT 
                a.idx,
                IFNULL(a.attendance_date, b.sub_date) AS attendance_date,
                a.employee_id,
                b.sub_type,
                b.status_confirm,
                a.check_in,
                a.check_out,
                a.shift_idx,
                (SELECT check_in FROM dynamic_shift WHERE shift_idx = a.shift_idx AND `date` = IFNULL(a.attendance_date, b.sub_date) AND employee_id = a.employee_id) AS dynamic_check_in,
                a.value_overtime,
                (SELECT overtime_allowance FROM master_employee WHERE employee_id = a.employee_id) AS overtime_allowance,
                a.status_overtime,
                a.status_piket,
                (SELECT shift_mode FROM master_shift WHERE idx = a.shift_idx) AS shift_mode,
                (SELECT monday_in FROM master_shift WHERE idx = a.shift_idx) AS monday,
                (SELECT tuesday_in FROM master_shift WHERE idx = a.shift_idx) AS tuesday,
                (SELECT wednesday_in FROM master_shift WHERE idx = a.shift_idx) AS wednesday,
                (SELECT thursday_in FROM master_shift WHERE idx = a.shift_idx) AS thursday,
                (SELECT friday_in FROM master_shift WHERE idx = a.shift_idx) AS friday,
                (SELECT saturday_in FROM master_shift WHERE idx = a.shift_idx) AS saturday,
                (SELECT sunday_in FROM master_shift WHERE idx = a.shift_idx) AS sunday
            FROM 
                attendance_employee a
            LEFT JOIN 
                submission_detail b
            ON a.attendance_date = b.sub_date AND a.employee_id = b.employee_id
            WHERE DATE_FORMAT(a.check_in, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date'
            AND a.company_idx = $company_idx
            AND IF(0=1,0,(SELECT `status_delete` FROM `master_employee` WHERE `employee_id` = `a`.`employee_id`)) = 0
            
            UNION
            
            SELECT 
                NULL as idx,
                c.sub_date AS attendance_date,
                c.employee_id,
                c.sub_type,
                c.status_confirm,
                NULL as check_in,
                NULL as check_out,
                (SELECT office_shift FROM master_employee WHERE employee_id = c.employee_id) as shift_idx,
                (SELECT check_in FROM dynamic_shift WHERE shift_idx = (SELECT office_shift FROM master_employee WHERE employee_id = c.employee_id) AND `date` = c.sub_date AND employee_id = c.employee_id) AS dynamic_check_in,
                0 AS value_overtime,
                0 AS overtime_allowance,
                0 AS status_overtime,
                0 AS status_piket,
                (SELECT shift_mode FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = c.employee_id)) AS shift_mode,
                (SELECT monday_in FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = c.employee_id)) as monday,
                (SELECT tuesday_in FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = c.employee_id)) as tuesday,
                (SELECT wednesday_in FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = c.employee_id)) as wednesday,
                (SELECT thursday_in FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = c.employee_id)) as thursday,
                (SELECT friday_in FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = c.employee_id)) as friday,
                (SELECT saturday_in FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = c.employee_id)) as saturday,
                (SELECT sunday_in FROM master_shift WHERE idx = (SELECT office_shift FROM master_employee WHERE employee_id = c.employee_id)) as sunday
            FROM 
                submission_detail c
            LEFT JOIN 
                attendance_employee d
            ON 
                c.sub_date = d.attendance_date 
                AND c.employee_id = d.employee_id
            WHERE c.sub_date BETWEEN '$from_date' AND '$to_date'
            AND	d.attendance_date IS NULL
            AND c.company_idx = $company_idx
            AND IF(0=1,0,(SELECT `status_delete` FROM `master_employee` WHERE `employee_id` = `c`.`employee_id`)) = 0";
        $result = $this->db->query($query)->result();
        return $result;
    }

    public function get_employee($empid){
        $column = [
            "a.*"
        ];
        $this->db->select($column)
        ->from('master_employee a')
        ->where(['a.employee_id' => $empid, 'a.company_idx' => $this->office]);
        // $result = $this->db->get_compiled_select();
        $result = $this->db->get()->row_array();
        return $result;
    }

    public function get_employee_data($empid, $shiftIdx, $attDate, $from_date, $to_date){
        $column = [
            "a.*",
            "(SELECT shift_mode FROM master_shift WHERE idx = $shiftIdx) as shift_mode",
            "(SELECT check_in FROM master_shift WHERE idx = $shiftIdx) as shift_check_in",
            "(SELECT monday_in FROM master_shift WHERE idx = $shiftIdx) as monday",
            "(SELECT tuesday_in FROM master_shift WHERE idx = $shiftIdx) as tuesday",
            "(SELECT wednesday_in FROM master_shift WHERE idx = $shiftIdx) as wednesday",
            "(SELECT thursday_in FROM master_shift WHERE idx = $shiftIdx) as thursday",
            "(SELECT friday_in FROM master_shift WHERE idx = $shiftIdx) as friday",
            "(SELECT saturday_in FROM master_shift WHERE idx = $shiftIdx) as saturday",
            "(SELECT sunday_in FROM master_shift WHERE idx = $shiftIdx) as sunday"
        ];
        $this->db->select($column)
        ->from('master_employee a')
        ->where(['a.employee_id' => $empid, 'a.company_idx' => $this->office]);
        // $result = $this->db->get_compiled_select();
        $result = $this->db->get();
        if($result->num_rows()>0){
            $header = $result->row_array();
            $this->db->select('a.*')
            ->from('attendance_setup a')
            ->where(['a.status' => 1, 'a.shift_idx' => $shiftIdx]);
            $result2 = $this->db->get()->result_array();
            $this->db->select('b.*')
            ->from('dynamic_shift b')
            ->where(['b.employee_id' => $empid, 'b.shift_idx' => $shiftIdx, 'b.company_idx' => $this->office, 'b.date' => $attDate]);
            $result3 = $this->db->get()->result_array();
            $this->db->select('c.*')
            ->from('incentive_detail c')
            ->where(["(DATE_FORMAT(c.confirm_date, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date')" => null, 'c.employee_id' => $empid, 'c.company_idx' => $this->office, 'c.incentive_date' => $attDate, 'c.status_confirm' => 1, 'status_draft_paid' => 0]);
            $result4 = $this->db->get()->result_array();
            // $result4 = $this->db->get_compiled_select();
            $this->db->select('d.*')
            ->from('dlk_detail d')
            ->where(["(DATE_FORMAT(d.confirm_date, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date')" => null, 'd.employee_id' => $empid, 'd.company_idx' => $this->office, 'd.dlk_date' => $attDate, 'd.status_confirm' => 1, 'status_draft_paid' => 0]);
            $result5 = $this->db->get()->result_array();
            // $this->db->select('e.*')
            // ->from('submission_detail e')
            // ->where(['e.employee_id' => $empid, 'e.company_idx' => $this->office, 'e.sub_date' => $attDate, 'e.status_confirm' => 1]);
            // $result6 = $this->db->get()->result_array();
            $data = [
                'data_tolerance' => empty($result2)?[]:$result2,
                'data_cycle' => empty($result3)?[]:$result3,
                'data_incentive' => empty($result4)?[]:$result4,
                'data_dlk' => empty($result5)?[]:$result5
                // 'data_sub' => $result6,
            ];
            return array_merge($header, $data);
        }else{
            $data = [
                'data_tolerance' => null,
                'data_cycle' => null,
                'data_incentive' => null,
                'data_dlk' => null
            ];
            return $result->row_array();
        }
    }

    public function get_rapel($employee_id, $date){

    }

    public function get_employee_on_half($empid, $all_id){
        $column = [
            'a.*'
        ];
        $this->db->select($column)
        ->from('allowance_half_detail a')
        ->where(['a.employee_id' => $empid, 'a.allowh_id' => $all_id, 'a.company_idx' => $this->office]);
        $result = $this->db->get()->row_array();
        return $result;
    }

    public function getAllowHeader($all_id)
    {
        $this->db->select('a.*')
        ->from('allowance_half a')
        ->where('allowh_id', $all_id);
        $result = $this->db->get()->row_array();
        return $result;
    }

    public function check_holiday($cekin){
        $this->db->select('a.*')
        ->from('calendar_event_master a')
        ->where('a.event_date', $cekin);
        // $result = $this->db->get_compiled_select();
        $result = $this->db->get()->num_rows();
        return $result;
    }

    public function check_holiday_cycle($cekin, $emp_id, $shf_idx){
        $this->db->select('a.*')
        ->from('dynamic_shift a')
        ->where(['a.date'=> $cekin, 'a.employee_id' => $emp_id, 'shift_idx' => $shf_idx, 'work_day' => 0]);
        // $result = $this->db->get_compiled_select();
        $result = $this->db->get()->num_rows();
        return $result;
    }

    public function getToleranceAbsen($shift_idx){
        $this->db->select('a.*')
        ->from('attendance_setup a')
        ->where(['a.shift_idx' =>  $shift_idx, 'a.status' => 1]);
        // $result = $this->db->get_compiled_select();
        $result = $this->db->get()->result();
        return $result;
    }

    public function printUmtHeader($idx)
    {
        $this->db->select('a.*,(SELECT user_name FROM user_account WHERE idx = a.created_by) as user_name')
        ->from('allowance_half a')
        ->where('a.idx', $idx);
        $result = $this->db->get()->row_array();
        return $result;
    }

    public function printUmtHeaderById($idx)
    {
        $this->db->select('a.*,(SELECT user_name FROM user_account WHERE idx = a.created_by) as user_name')
        ->from('allowance_half a')
        ->where('a.allowh_id', $idx);
        $result = $this->db->get()->row_array();
        return $result;
    }

    public function printUmtDetail($all_id)
    {
        $this->db->select('a.*')
        ->from('allowance_half_detail a')
        ->where('allowh_id', $all_id);
        $result = $this->db->get()->result();
        return $result;
    }

    public function company()
    {
        $this->db->select('a.*')
        ->from('master_company a')
        ->where('a.idx', $this->office);
        $result = $this->db->get()->row_array();
        return $result;
    }

    public function getDataHeader($all_idx){
        $this->db->select('a.allowance_code, a.start, a.end, a.all_att_idx, a.all_ot_idx, a.all_sub_idx, a.all_inc_idx, a.all_dlk_idx, a.status_rapel, a.description')
        ->from('allowance_half a')
        ->where(['a.allowh_id' =>  $all_idx, 'a.status' => 1]);
        $result = $this->db->get()->row_array();
        return $result;
    }
    
    public function insertUmt($dataHeader, $updateAttendanceQuery, $dataDetail, $updateOtQuery="", $updateDlkQuery="", $updateSubQuery="", $updateIncQuery="")
    {
        $this->db->trans_begin();
        $this->db->insert('allowance_half', $dataHeader);
        $this->db->query($updateAttendanceQuery);
        $this->db->query($dataDetail);
        if(!empty($updateOtQuery)){
            $this->db->query($updateOtQuery);
        }
        if(!empty($updateDlkQuery)){
            $this->db->query($updateDlkQuery);
        }
        if(!empty($updateSubQuery)){
            $this->db->query($updateSubQuery);
        }
        if(!empty($updateIncQuery)){
            $this->db->query($updateIncQuery);
        }
        
        if($this->db->trans_status() === FALSE){
    		$this->db->trans_rollback();
    		return 0;
    	}else{
    		$this->db->trans_commit();
    		return 1;
    	}
    }

    public function updateUmt($all_idx, $update_umt_header, $updateAttendanceQuery="", $insertAllHalfDetail="", $updateOtQuery="", $updateDlkQuery="", $updateSubQuery="", $updateIncQuery="", $updateOtOldQuery="", $updateDlkOldQuery="", $updateSubOldQuery="", $updateIncOldQuery="")
    {
        $this->db->trans_begin();
        $this->db->where(['allowh_id' => $all_idx]);
        $this->db->update('allowance_half', $update_umt_header);

        if(!empty($updateAttendanceQuery)){
            $this->db->query($updateAttendanceQuery);
        }

        if(!empty($insertAllHalfDetail)){
            $this->db->where(['allowh_id' => $all_idx]);
            $this->db->delete('allowance_half_detail');
    
    
            $this->db->query($insertAllHalfDetail);
        }

        if(!empty($updateOtQuery)){
            $this->db->query($updateOtQuery);
        }
        if(!empty($updateDlkQuery)){
            $this->db->query($updateDlkQuery);
        }
        if(!empty($updateSubQuery)){
            $this->db->query($updateSubQuery);
        }
        if(!empty($updateIncQuery)){
            $this->db->query($updateIncQuery);
        }
        if(!empty($updateDlkOldQuery)){
            $this->db->query($updateDlkOldQuery);
        }
        if(!empty($updateSubOldQuery)){
            $this->db->query($updateSubOldQuery);
        }
        if(!empty($updateIncOldQuery)){
            $this->db->query($updateIncOldQuery);
        }
        if($this->db->trans_status() === FALSE){
    		$this->db->trans_rollback();
    		return 0;
    	} else {
    		$this->db->trans_commit();
    		return 1;
    	}
    }

    public function publishUmt($idx, $dataHeader, $queryUpdate="")
    {
        $this->db->trans_begin();
        $this->db->where(['idx' => $idx]);
        $this->db->update('allowance_half', $dataHeader);
        if(!empty($queryUpdate)){
            $this->db->query($queryUpdate);
        }
        if($this->db->trans_status() === FALSE){
    		$this->db->trans_rollback();
    		return 0;
    	} else {
    		$this->db->trans_commit();
    		return 1;
    	}
    }
}
?>