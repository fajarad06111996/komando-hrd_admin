<?php
defined('BASEPATH') or exit('No direct script access allowed');
require "vendor/autoload.php";
use Nullix\CryptoJsAes\CryptoJsAes;
class Calendar extends AUTH_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('core/M_calendar', 'mCal');
        $this->load->model('ModelGenId');
        $this->load->library('security_function');
        $this->link	    = site_url('core/').strtolower(get_class($this));
        $this->filename = strtolower(get_class($this));
        $this->office   = $this->secure->dec($this->session->userdata('JToffice_id'));
        $this->tOffice  = $this->secure->dec($this->session->userdata('JToffice_type'));
        $this->hub      = $this->secure->dec($this->session->userdata('JThub_id'));
        $this->idx      = $this->secure->dec($this->session->userdata('JTidx'));
        $this->username = $this->session->userdata('JTuser_id');
        $this->enkey    = $this->config->item('encryption_key');
    }
    public function index()
    {
        $access = $this->security_function->permissions($this->filename . "-r");
        if (empty($access)) die("<center><h1>You can not get the right to access this module</h1></center>");
        if(!empty($this->security_function->permissions($this->filename . "-w"))){
            $data['write']  = '<a href="javascript:void(0);" id="btnAdd" class="pull-right text-white small" title="Tambah Organisasi" data-placement="right" data-popup="tooltip"><i class="icon-plus-circle2"></i></a>';
            $data['lock']   = 1;
        }else{
            $data['write']  = '<i class="icon-lock pull-right pt-1 " title="Tambah Organisasi Terkunci" data-placement="right" data-popup="tooltip"></i>';
            $data['lock']   = 0;
        }
        // $data['employee']   = $this->mCal->getEmployee();
        $data['filename']   = $this->filename;
        $data['judul']      = 'Calendar';
        $data['page']       = 'Calendar';
        $data["link"] = $this->link;
        $params             = '{"link": "'.$this->link.'", "csrf": "'.$this->session->csrf_token.'", "permW": "'.$this->security_function->permissions($this->filename . "-w").'", "permC": "'.$this->security_function->permissions($this->filename . "-c").'", "permX": "'.$this->security_function->permissions($this->filename . "-x").'"}';
        // encrypt
        $encrypted          = CryptoJsAes::encrypt($params, $this->enkey);
        $data['params']     = $encrypted;
        $this->template->views('core/v_calendar', $data);
    }

    public function getCalendar()
    {
        $month = $this->input->post('month');
        $year = $this->input->post('year');
        $data = $this->mCal->getCalendarEvent($month, $year);
        if($data){
            $data_arr=array();
            foreach($data as $d){
                $e = [
                    'id' => $this->secure->enc($d->event_id),
                    'title' => $d->event_name,
                    'start' => date("Y-m-d", strtotime($d->event_date)),
                    'end' => date("Y-m-d", strtotime($d->event_date))
                    // 'allDay' => false,
                    // 'color' => '#'.substr(uniqid(),-6),
                    // 'color' => '#af1d1d'
                    // 'url' => '#'
                ];

                $data_arr[] = $e;
            }
            $msg['status']  = true;
            $msg['msg']     = 'successfully!';
            $msg['data']    = $data_arr;
            echo json_encode($data_arr);die;
        }else{
            $msg['status']  = false;
            $msg['msg']     = 'error!';
            $msg['data']    = $data;
            echo json_encode($msg);die;
        }
    }

    public function save_event()
    {
        $this->form_validation->set_rules('event_name', 'Event Name', 'trim|required',[
            'required' => 'Event Name wajib di isi.'
        ]);
        $this->form_validation->set_rules('event_date', 'Event date', 'trim|required',[
            'required' => 'Event date wajib di isi.'
        ]);
        // $this->form_validation->set_rules('event_start_date', 'Event start', 'trim|required',[
        //     'required' => 'Event start wajib di isi.'
        // ]);
        // $this->form_validation->set_rules('event_end_date', 'Event end', 'trim|required',[
        //     'required' => 'Event end wajib di isi.'
        // ]);
        if ($this->form_validation->run() == false) {
            $msg['status']  = false;
            $msg['msg']     = validation_errors();
            echo json_encode($msg);die;
        }

        $event_name         = $this->input->post('event_name');
        $event_date         = $this->input->post('event_date');
        // $event_start_date   = $this->input->post('event_start_date');
        // $event_end_date     = $this->input->post('event_end_date');

        $data = [
            'event_name' => $event_name,
            'event_date' => $event_date,
            // 'event_start_date' => $event_start_date,
            // 'event_end_date' => $event_end_date,
            'company_idx' => $this->office,
            'created_by' => $this->idx,
            'created_on' => date('Y-m-d'),
        ];

        $result = $this->mCal->insertEvent($data);
        if($result==1){
            $msg['status']  = true;
            $msg['msg']     = 'Tambah event berhasil.';
            echo json_encode($msg);die;
        }else{
            $msg['status']  = false;
            $msg['msg']     = 'Tambah event gagal.';
            echo json_encode($msg);die;
        }
    }

    public function get_event()
    {
        $id = $this->input->get('id');
        $idx = $this->secure->dec($id);
        $data = $this->mCal->getEvent($idx);
        if($data){
            $res = [
                'status' => 1,
                'event_id' => $this->secure->enc((int)$data['event_id']),
                'event_name' => $data['event_name'],
                'start' => $data['event_date'],
                'end' => $data['event_date']
            ];
        }else{
            $res = [
                'status' => 0,
                'event_id' => null,
                'event_name' => null,
                'start' => null,
                'end' => null
            ];
        }
        echo json_encode($res);die;
    }

    public function update_event($idx)
    {
        $id = $this->secure->dec($idx);
        $this->form_validation->set_rules('event_name', 'Event Name', 'trim|required',[
            'required' => 'Event Name wajib di isi.'
        ]);
        $this->form_validation->set_rules('event_date', 'Event date', 'trim|required',[
            'required' => 'Event date wajib di isi.'
        ]);
        // $this->form_validation->set_rules('event_start_date', 'Event start', 'trim|required',[
        //     'required' => 'Event start wajib di isi.'
        // ]);
        // $this->form_validation->set_rules('event_end_date', 'Event end', 'trim|required',[
        //     'required' => 'Event end wajib di isi.'
        // ]);
        if ($this->form_validation->run() == false) {
            $msg['status']  = false;
            $msg['msg']     = validation_errors();
            echo json_encode($msg);die;
        }

        $event_name         = $this->input->post('event_name');
        $event_date         = $this->input->post('event_date');
        // $event_start_date   = $this->input->post('event_start_date');
        // $event_end_date     = $this->input->post('event_end_date');

        $data = [
            'event_name' => $event_name,
            'event_date' => $event_date,
            // 'event_start_date' => $event_start_date,
            // 'event_end_date' => $event_end_date,
            'modified_by' => $this->idx,
            'modified_on' => date('Y-m-d'),
        ];

        $result = $this->mCal->updateEvent($data, $id);
        if($result==1){
            $msg['status']  = true;
            $msg['msg']     = 'Ubah event berhasil.';
            echo json_encode($msg);die;
        }else{
            $msg['status']  = false;
            $msg['msg']     = 'Ubah event gagal.';
            echo json_encode($msg);die;
        }
    }

    public function updateDrop_event()
    {
        $this->form_validation->set_rules('date', 'Event date', 'trim|required',[
            'required' => 'Event date wajib di isi.'
        ]);
        // $this->form_validation->set_rules('end', 'Event end', 'trim|required',[
        //     'required' => 'Event end wajib di isi.'
        // ]);
        if ($this->form_validation->run() == false) {
            $msg['status']  = false;
            $msg['msg']     = validation_errors();
            echo json_encode($msg);die;
        }

        $event_id           = $this->secure->dec($this->input->post('id'));
        $event_date         = $this->input->post('date');
        // $event_start_date   = $this->input->post('start');
        // $event_end_date     = $this->input->post('end');

        $data = [
            'event_date' => $event_date,
            // 'event_start_date' => $event_start_date,
            // 'event_end_date' => $event_end_date,
            'modified_by' => $this->idx,
            'modified_on' => date('Y-m-d'),
        ];

        $result = $this->mCal->updateEvent($data, $event_id);
        if($result==1){
            $msg['status']  = true;
            $msg['msg']     = 'Ubah event berhasil.';
            echo json_encode($msg);die;
        }else{
            $msg['status']  = false;
            $msg['msg']     = 'Ubah event gagal.';
            echo json_encode($msg);die;
        }
    }
}
/* End of file Calendar.php */
/* Location: ./application/controllers/core/Calendar.php */
