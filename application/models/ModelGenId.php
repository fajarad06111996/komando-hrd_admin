<?php 
defined('BASEPATH') or exit('No direct script access allowed');
class ModelGenId extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('secure');
    }
    public function getValue($config_id)
    {
        $cekConfigId = $this->db->get_where('configuration', ['config_id' => strtoupper($config_id)])->row_array();
        if(empty($cekConfigId)){
            $result = $this->db->query("insert into configuration (
                            config_id,
                            config_value,
                            config_date,
                            modified_by,
                            modified_on)
                values (
                    '".strtoupper($config_id)."',
                    1,
                    '".date("Y-m-d H:i:s")."',
                    '$id_login',
                    '".date("Y-m-d H:i:s")."'
                )");
            if(!$result){
                return 0;
            }else{
                $result = 1;
            }
        }else{
            $result = $cekConfigId['config_value'] + 1;
        }
        return $result;
    }
    public function genIdYear($config_id, $id_login)
    {   
        $cekConfigId = $this->db->query("select * from configuration where config_id = '" . strtoupper($config_id) . "'")->row_array();
        if($cekConfigId) 
        {
            $cekConfigYear = $this->db->query("select * from configuration where config_id = '" . strtoupper($config_id) . "' AND YEAR(config_date) = '" . date('Y') . "'")->row_array();
            if($cekConfigYear) 
            {
                $result = $cekConfigYear['config_value'] + 1;
                $this->db->query("update configuration set
                                                config_value = config_value + 1,
                                                config_date = '" . date("Y-m-d H:i:s") . "',
                                                modified_by = " . $id_login . ",
                                                modified_on = '" . date("Y-m-d H:i:s") . "'
                                    where config_id = '" . strtoupper($config_id) . "'");
            }
			else 
			{ 
			    $this->db->query("update configuration set
                                                config_value = 1,
                                                config_date = '" . date("Y-m-d H:i:s") . "',
                                                modified_by = " . $id_login . ",
                                                modified_on = '" . date("Y-m-d H:i:s") . "'
                                    where config_id = '" . strtoupper($config_id) . "'");
                $result = 1;
			}
        } 
        else 
        {
            $this->db->query("insert into configuration (
                                                config_id,
                                                config_value,
                                                config_date,
                                                modified_by,
                                                modified_on)
                                        values (
                                            '".strtoupper($config_id)."',
                                            1,
                                            '".date("Y-m-d H:i:s")."',
                                            $id_login,
                                            '".date("Y-m-d H:i:s")."'
                                        )");
            $result = 1;
        }
        return $result;
    }
    public function genIdMonth($config_id, $id_login)
    {
        $cekConfigId = $this->db->query("select * from configuration where config_id = '" . strtoupper($config_id) . "'")->row_array();
        if($cekConfigId)
        {
            $cekConfigMonth = $this->db->query("select * from configuration where config_id = '" . strtoupper($config_id) . "' AND MONTH(config_date) = '" . date('m') . "'")->row_array();
            if($cekConfigMonth)
            {
                $result = $cekConfigMonth['config_value'] + 1;
                $this->db->query("update configuration set
                                    config_value = config_value + 1,
                                    config_date = '" . date("Y-m-d H:i:s") . "',
                                    modified_by = " . $id_login . ",
                                    modified_on = '" . date("Y-m-d H:i:s") . "'
                        where config_id = '" . strtoupper($config_id) . "'");
            }else{
                $this->db->query("update configuration set
                                    config_value = 1,
                                    config_date = '" . date("Y-m-d H:i:s") . "',
                                    modified_by = " . $id_login . ",
                                    modified_on = '" . date("Y-m-d H:i:s") . "'
                        where config_id = '" . strtoupper($config_id) . "'");
                $result = 1;
			}
        }else{
            $this->db->query("insert into configuration (
                                                config_id,
                                                config_value,
                                                config_date,
                                                modified_by,
                                                modified_on)
                                        values (
                                            '".strtoupper($config_id)."',
                                            1,
                                            '".date("Y-m-d H:i:s")."',
                                            $id_login,
                                            '".date("Y-m-d H:i:s")."'
                                        )");
            $result = 1;
        }
        return $result;
    }
    public function genId($config_id, $id_login)
    {
        $cekConfigId = $this->db->get_where('configuration', ['config_id' => strtoupper($config_id)])->row_array();
        if($cekConfigId) { //jika ada
            $cekConfigDate = $this->db->get_where('configuration', ['YEAR(config_date)' => date("Y"), 'config_id' => strtoupper($config_id)])->row_array();
            if(!$cekConfigDate) {
                $queryUpdate = $this->db->query("update configuration set 
                                                config_value = 1,
                                                config_date = '".date("Y-m-d")."',
                                                modified_by = '$id_login',
                                                modified_on = '".date("Y-m-d H:i:s")."'
                                            where config_id = '".strtoupper($config_id)."'");
                if(!$queryUpdate){
                    return 0;
                }else{
                    $result = 1;
                }
            }else{
                //get config value
                $result = $cekConfigId['config_value'] + 1;
                $this->db->query("update configuration set
                                                config_value = config_value + 1,
                                                config_date = '" . date("Y-m-d H:i:s") . "',
                                                modified_by = " . $id_login . ",
                                                modified_on = '" . date("Y-m-d H:i:s") . "'
                                    where config_id = '" . strtoupper($config_id) . "'");
            }
        } else {
            $query = $this->db->query("insert into configuration (
                                                config_id, 
                                                config_value, 
                                                config_date, 
                                                modified_by, 
                                                modified_on)
                                        values (
                                            '".strtoupper($config_id)."',
                                            1,
                                            '".date("Y-m-d")."',
                                            '$id_login',
                                            '".date("Y-m-d H:i:s")."'
                                        )");
            //get config value
            if(!$query){
                return 0;
            }
            //get config value
            $result = 1;
        }
        return $result;
    }
    public function genIdUnlimited($config_id, $id_login)
    {
        $cekConfigId = $this->db->get_where('configuration', ['config_id' => strtoupper($config_id)])->row_array();
        if(!$cekConfigId) {
           $result = $this->db->query("insert into configuration (
                                                 config_id,
                                                 config_value,
                                                 config_date,
                                                 modified_by,
                                                 modified_on)
                                        values (
                                            '".strtoupper($config_id)."',
                                            1,
                                            '".date("Y-m-d H:i:s")."',
                                            '$id_login',
                                            '".date("Y-m-d H:i:s")."'
                                        )");
            if(!$result){
                return 0;
            }else{
                $result = 1;
            }
        }else{
            //get config value
            $result = $cekConfigId['config_value'] + 1;
            $this->db->query("update configuration set
                                                config_value = config_value + 1,
                                                config_date = '" . date("Y-m-d H:i:s") . "',
                                                modified_by = " . $id_login . ",
                                                modified_on = '" . date("Y-m-d H:i:s") . "'
                                    where config_id = '" . strtoupper($config_id) . "'");
        }
        return $result;
    }
    public function updateId($config_id, $id_login)
    {
        $query = $this->db->query("update configuration set 
                                        config_value = config_value + 1,
                                        config_date = '".date("Y-m-d")."',
                                        modified_by = '$id_login',
                                        modified_on = '".date("Y-m-d H:i:s")."'       
                                    where config_id = '".strtoupper($config_id)."'");
        if(!$query){
            return 0;
        }
    }
}
?>