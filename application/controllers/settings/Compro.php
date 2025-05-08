<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Compro extends AUTH_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('setup/M_compro', 'mCompro');
        $this->load->library('security_function');
        $this->link		  = site_url('settings/').strtolower(get_class($this));
        $this->filename	= strtolower(get_class($this));
        $this->office   = $this->secure->dec($this->session->userdata('JToffice_id'));
        $this->hub      = $this->secure->dec($this->session->userdata('JThub_id'));
        $this->idx      = $this->secure->dec($this->session->userdata('JTidx'));
        $this->username = $this->session->userdata('JTuser_id');
    }
    public function index()
    {
        $access = $this->security_function->permissions($this->filename . "-r");
        if (empty($access)) die("<center><h1>You can not get the right to access this module</h1></center>");
        if(!empty($this->security_function->permissions($this->filename . "-w"))){
            $data['write']  = '<a href="javascript:void(0)" id="btnAdd" class="pull-right text-white small" title="Add New Daftar User" data-placement="right" data-popup="tooltip"><i class="icon-plus-circle2"></i></a>';
            $data['lock']   = 1;
        }else{
            $data['write']  = '<i class="icon-lock pull-right pt-1 " title="Locked New Daftar User" data-placement="right" data-popup="tooltip"></i>';
            $data['lock']   = 0;
        }
        $data['filename']       = $this->filename;
        $data["link"]           = $this->link;
        $data['userdata']       = $this->userdata;
        $data['formact']        = $this->link.'/formActCompro';
        $data['com']            = $this->mCompro->getCompro();
        $data['bank']           = $this->mCompro->getBank();
        // var_dump($data['com']);
        // die;
        $data['post']           = 0;
        $this->template->views('setup/v_company_info', $data);
    }
    public function getDataAjaxRemote()
    {
        $search = $this->input->post('search');
        $results = $this->mReport->getDataAjaxRemote($search, 'data');
        $countresults = $this->mReport->getDataAjaxRemote($search, 'count');
        $selectajax[] = array();
        foreach($results as $row){
            $selectajax[] = array(
                'id'    => $row['client_id'],
                'text'  => $row['client_name']
            );
        }
        $select['items'] = $selectajax;
        $select['total_count'] = $countresults;
        $this->output->set_content_type('application/json')->set_output(json_encode($select));
    }
    public function getDataAjaxRemoteId($id)
    {
        $search         = $this->input->post('search');
        $results        = $this->mReport->getDataAjaxRemoteId($id, 'data');
        $countresults   = $this->mReport->getDataAjaxRemoteId($id, 'count');
        $selectajax[]   = array();
        foreach($results as $row){
            $selectajax[] = array(
                'id'    => $row['client_id'],
                'text'  => $row['client_name']
            );
        }
        $select['items'] = $selectajax;
        $select['total_count'] = $countresults;
        $this->output->set_content_type('application/json')->set_output(json_encode($select));
    }
    public function getCompro()
    {
        cek_get_csrf();
        $result = $this->mCompro->getCompro();
        if($result != null){
            $data = array(
                'tax_date_pkpx' => date('Y-m-d',strtotime($result['tax_date_pkp']))
            );
            $resultdata = array_merge($result,$data);
            echo json_encode($resultdata);
            die;
        }else{
            $data = array(
                'tax_date_pkpx' => null
            );
            echo json_encode($result);
            die;
        }
    }
    function formActCompro()
    {
        cek_csrf();
        $this->form_validation->set_rules('cName', 'Nama Perusahaan', 'trim|required',
            ['required' => 'Nama Perusahaan wajib di isi.']
        );
        $this->form_validation->set_rules('cAddress', 'Alamat', 'trim|required',
            ['required' => 'Alamat wajib di isi.']
        );
        $this->form_validation->set_rules('cCity', 'Kota', 'trim|required',
            ['required' => 'Kota wajib di isi.']
        );
        if ($this->form_validation->run() == false) {
            $msg['error']   = true;
            $msg['message'] = validation_errors();
            echo json_encode($msg);die;
        }
        $company_name           = $this->input->post('cName', TRUE);
        $company_address        = $this->input->post('cAddress', TRUE);
        $company_city           = $this->input->post('cCity', TRUE);
        $company_email          = $this->input->post('cEmail', TRUE);
        $company_phone          = $this->input->post('cPhone', TRUE);
        $company_postal_code    = $this->input->post('cPostcode', TRUE);
        $company_fax            = $this->input->post('cFax', TRUE);
        $company_country        = $this->input->post('cCountry', TRUE);
        $company_currency       = $this->input->post('cCurrency', TRUE);
        $company_bank_name      = $this->input->post('cBankName', TRUE);
        $company_bank           = $this->input->post('cBank', TRUE);
        $company_bank_account   = $this->input->post('cAccountName', TRUE);
        $company_bank_code      = $this->input->post('cAccountCode', TRUE);
        $pic_invoice            = $this->input->post('cPolis', TRUE);
        $tax_company            = $this->input->post('ctName', TRUE);
        $tax_address            = $this->input->post('ctAddress', TRUE);
        $tax_postal_code        = $this->input->post('ctPostcode', TRUE);
        $tax_serial_no          = $this->input->post('ctSerial_no', TRUE);
        $tax_number             = $this->input->post('ctTax_number', TRUE);
        $tax_pkp                = $this->input->post('ctPkp', TRUE);
        $tax_date_pkp           = $this->input->post('ctDatePkp', TRUE);
        $tax_branch_code        = '';
        $tax_business_type      = $this->input->post('ctBusinessType', TRUE);
        $tax_spt                = $this->input->post('ctKLUSPT', TRUE);
        $cekEksis               = $this->mCompro->cekEksis();
        if(empty($cekEksis)){
            $data = array(
                'account_number_transfer'   => $company_bank,
                'company_name'              => $company_name,
                'company_address'           => $company_address,
                'company_city'              => $company_city,
                'company_email'             => $company_email,
                'company_phone'             => $company_phone,
                'company_postal_code'       => $company_postal_code,
                'company_fax'               => $company_fax,
                'company_country'           => $company_country,
                'company_currency'          => $company_currency,
                'company_bank_name'         => $company_bank_name,
                'company_bank_account'      => $company_bank_account,
                'company_bank_code'         => $company_bank_code,
                'pic_invoice'               => $pic_invoice,
                'tax_company'               => $tax_company,
                'tax_address'               => $tax_address,
                'tax_postal_code'           => $tax_postal_code,
                'tax_serial_no'             => $tax_serial_no,
                'tax_number'                => $tax_number,
                'tax_pkp'                   => $tax_pkp,
                'tax_date_pkp'              => $tax_date_pkp,
                'tax_branch_code'           => $tax_branch_code,
                'tax_business_type'         => $tax_business_type,
                'tax_spt'                   => $tax_spt,
                'office_idx'                => $this->office
            );
    
            $insert = $this->mCompro->insertCompro($data);
            if($insert == 1){
                $msg['success'] = true;
                $msg['text']    = 'Update Info Perusahaan berhasil.';
                echo json_encode($msg);die;
            }else{
                $msg['status']  = true;
                $msg['text']    = 'Update Gagal,<br>Cobalah beberapa saat lagi.';
                echo json_encode($msg);die;
            }
        }else{
            $data = array(
                'account_number_transfer'   => $company_bank,
                'company_name'              => $company_name,
                'company_address'           => $company_address,
                'company_city'              => $company_city,
                'company_email'             => $company_email,
                'company_phone'             => $company_phone,
                'company_postal_code'       => $company_postal_code,
                'company_fax'               => $company_fax,
                'company_country'           => $company_country,
                'company_currency'          => $company_currency,
                'company_bank_name'         => $company_bank_name,
                'company_bank_account'      => $company_bank_account,
                'company_bank_code'         => $company_bank_code,
                'pic_invoice'               => $pic_invoice,
                'tax_company'               => $tax_company,
                'tax_address'               => $tax_address,
                'tax_postal_code'           => $tax_postal_code,
                'tax_serial_no'             => $tax_serial_no,
                'tax_number'                => $tax_number,
                'tax_pkp'                   => $tax_pkp,
                'tax_date_pkp'              => $tax_date_pkp,
                'tax_branch_code'           => $tax_branch_code,
                'tax_business_type'         => $tax_business_type,
                'tax_spt'                   => $tax_spt
            );
    
            $insert = $this->mCompro->updateCompro($data);
            if($insert == 1){
                $msg['success'] = true;
                $msg['text']    = 'Update Info Perusahaan berhasil.';
                echo json_encode($msg);die;
            }else{
                $msg['status']  = true;
                $msg['text']    = 'Update Gagal,<br>Cobalah beberapa saat lagi.';
                echo json_encode($msg);die;
            }
        }
    }
}
/* End of file Compro.php */
/* Location: ./application/controllers/setup/Compro.php */