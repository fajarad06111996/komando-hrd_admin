<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_compro extends CI_Model
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

    public function getCompro()
    {
        $this->db->select('*')
        ->from('company_info')
        ->where('office_idx',$this->office);
        $hasil = $this->db->get()->row_array();
        return $hasil;
    }

    public function getBank()
    {
        $this->db->select('*')
        ->from('master_account')
        ->where(['account_type'=>1,'part_one_account_number'=>'1120','parent_active'=>0]);
        $hasil = $this->db->get();
        return $hasil->result();
    }
    public function cekEksis()
    {
        $this->db->select('*')
        ->from('company_info')
        ->where('office_idx',$this->office);
        $hasil = $this->db->get()->row_array();
        return $hasil;
    }

    public function insertCompro($data)
    {
        $this->db->trans_begin();
        $this->db->insert('company_info', $data);
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
    public function updateCompro($data)
    {
        $this->db->trans_begin();
        $this->db->where('office_idx', $this->office);
        $this->db->update('company_info', $data);
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
}
?>