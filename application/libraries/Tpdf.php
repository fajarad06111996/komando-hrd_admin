<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once dirname(__FILE__) . '/tcpdf/examples/tcpdf_include.php';
require_once dirname(__FILE__) . '/tcpdf/tcpdf.php';
class Tpdf extends TCPDF
{
  function __construct()
  {
    parent::__construct();
  }
}

/* End of file Pdf.php */
/* Location: ./application/libraries/Pdf.php */