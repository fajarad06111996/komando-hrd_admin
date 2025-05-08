<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_calendar extends CI_Model 
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

    public function getCalendarEvent($month, $year)
    {
        $this->db->select('event_id,event_name,event_date,event_start_date,event_end_date')
        ->from('calendar_event_master')
        ->where(['company_idx' => $this->office, "MONTH(event_date) = $month" => null, "YEAR(event_date) = $year" => null]);
        // $result = $this->db->get_compiled_select();
        $result = $this->db->get()->result();
        return $result;
    }

    public function getEvent($id)
    {
        return $this->db->get_where('calendar_event_master', ['event_id' => $id, 'company_idx' => $this->office])->row_array();
    }
    public function insertEvent($data)
    {
        $this->db->trans_begin();
        $this->db->insert('calendar_event_master', $data);
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

    public function updateEvent($data, $id)
    {
        $this->db->trans_begin();
        $this->db->where('event_id', $id);
        $this->db->update('calendar_event_master', $data);
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }
}

/* End of file M_calendar.php */
/* Location: ./application/models/core/M_calendar.php */