<?php
defined('BASEPATH') or exit('No direct script access allowed');
require "vendor/autoload.php";
use Nullix\CryptoJsAes\CryptoJsAes;
class Workpermission extends AUTH_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('core/M_workpermission', 'mWper');
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
            // $data['write']  = '<a href="javascript:void(0);" id="btnAdd" class="pull-right text-white small" title="Tambah Jabatan" data-placement="right" data-popup="tooltip"><i class="icon-plus-circle2"></i></a>';
            $data['write'] = '';
            $data['lock']   = 1;
        }else{
            // $data['write']  = '<i class="icon-lock pull-right pt-1 " title="Tambah Jabatan Terkunci" data-placement="right" data-popup="tooltip"></i>';
            $data['write'] = '';
            $data['lock']   = 0;
        }
        $data['access']     = $access;
        $data['filename']   = $this->filename;
        $data["link"]       = $this->link;
        $params             = '{"link": "'.$this->link.'", "csrf": "'.$this->session->csrf_token.'", "permC": "'.$this->security_function->permissions($this->filename . "-c").'", "permX": "'.$this->security_function->permissions($this->filename . "-x").'"}';
        $encrypted          = CryptoJsAes::encrypt($params, $this->enkey);
        $data['params']     = $encrypted;
        $this->template->views('core/v_workpermission', $data);
    }

    // UNTUK LOAD DATA JQUERY KE VIEW
    function get_ajax()
    {
        $csrfToken  = validate_csrf_token();
        if($csrfToken == false){
            $list = array();
        }else{
            $list = $this->mWper->get_datatables();
        }
        $data = array();
        $no = @$_POST['start'];
        foreach ($list as $item) {
            $idx            = $this->secure->enc($item->idx);
            if(!empty($this->security_function->permissions($this->filename . "-c"))){
                $change = "<h5 class='m-0'><a href='javascript:void(0);' id='$idx' class='bEdit text-center badge badge-info' data-popup='tooltip' title='Edit Ijin Kerja' data-placement='right'><i class='icon-pencil5'></i></a></h5>";
                $setup = "<h5 class='m-0'><a href='javascript:void(0);' id='$idx' class='bSetup text-center badge badge-primary' data-popup='tooltip' title='Toleransi Kehadiran' data-placement='left'><i class='icon-folder-search'></i></a></h5>";
            }else{
                $change = '<h5 class="m-0"><span class="badge badge-warning bLockStatus"><i class="icon-lock" data-popup="tooltip" title="Locked Edit Jabatan" data-placement="right"></i></span></h5>';
                $setup = '<h5 class="m-0"><span class="badge badge-warning bLockStatus"><i class="icon-lock" data-popup="tooltip" title="Locked Toleransi Kehadiran" data-placement="left"></i></span></h5>';
                if($item->status == 1)
                {
                    $statusU = "<span class='text-center badge badge-success'>Aktif</span>";
                }else{
                    $statusU = "<span class='text-center badge badge-danger'>Non Aktif</span>";
                }
            }
            if(!empty($this->security_function->permissions($this->filename . "-x"))){
                $execute  = "<h5 class='m-0'><a href='javascript:void(0);' id='$idx' class='bDelete text-center badge badge-danger' data-popup='tooltip' title='Hapus Jabatan' data-placement='right'><i class='icon-bin'></i></a></h5>";
            }else{
                $execute  = '<h5 class="m-0"><span class="badge badge-warning"><i class="icon-lock" data-popup="tooltip" title="Hapus Jabatan Terkunci" data-placement="right"></i></span></h5>';
            }

            // tanggal mulai dan sampai ijin kerja
            !empty($item->start_date) ? $start_date = date('d-m-Y', strtotime($item->start_date)) : $start_date = '';
            !empty($item->end_date) ? $end_date = date('d-m-Y', strtotime($item->end_date)) : $end_date = '';
            
            // untuk status ijin kerja
            switch ($item->status) {
                case 1: $statusName = '<a href="javascript:void(0);" id="'.$idx.'" data-employee_name="'.ucwords($item->employee_name).'" data-date="'.$start_date.'_'.$end_date.'" data-remarks="'.$item->remarks.'" code="1" class="bStatus text-center badge badge-warning" data-popup="tooltip" title="" data-placement="right">Tunggu ACC Atasan</a>'; break;
                case 2: $statusName = '<a href="javascript:void(0);" id="'.$idx.'" data="" code="1" class="bStatus text-center badge badge-info" data-popup="tooltip" title="" data-placement="right">Tunggu ACC HRD</a>'; break;
                default: $statusName = '<a href="javascript:void(0);" id="'.$idx.'" data="" code="1" class="bStatus text-center badge badge-success" data-popup="tooltip" title="" data-placement="right">Ijin Di Approve</a>'; break;
            }

            $no++;
            $row = array();
            $row[] = $no.".";
            $row[] = "<div class='btn-group'>
                        <button type='button' class='btn btn-danger btn-sm dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'><i class='icon-more2'></i></button>
                        <div class='dropdown-menu p-2' style='min-width: auto !important;'>
                            <div class='btn-group'>$change&nbsp;$execute</div>
                        </div>
                    </div>";
            $row[] = $statusName;
            $row[] = !empty($item->employee_name) ? ucwords($item->employee_name) : '';
            $row[] = $start_date.' s/d '.$end_date;
            $row[] = !empty($item->remarks) ? ucfirst($item->remarks) : '';
            // add html for action
            $data[] = $row;
        }
        $output = array(
            "draw"            => @$_POST['draw'],
            "recordsTotal"    => $this->mWper->count_all(),
            "recordsFiltered" => $this->mWper->count_filtered(),
            "data"            => $data,
        );
        // output to json format
        echo json_encode($output);
    }
    
}
