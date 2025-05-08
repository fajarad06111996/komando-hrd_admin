<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_company extends CI_Model
{
  
  public function editCompany()
  {
    $id = $this->input->get('id');
    $this->db->where('company_id', $id);
    $query = $this->db->get('app_company');
    if ($query->num_rows() > 0) {
        return $query->row();
    } else {
        return false;
    }
  }

  function deleteCompany()
  {
    $id = $this->input->get('id');
    $this->db->where('company_id', $id);
    $this->db->delete('app_company');
    if ($this->db->affected_rows() > 0) {
        return true;
    } else {
        return false;
    }
  }

}

/* End of file M_admin.php */
/* Location: ./application/models/M_admin.php */
