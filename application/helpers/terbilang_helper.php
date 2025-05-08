<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
function penyebut($nilai) {
  $nilai = abs($nilai);
  $huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
  $temp = "";
  if ($nilai < 12) {
    $temp = " ". $huruf[$nilai];
  } else if ($nilai <20) {
    $temp = penyebut($nilai - 10). " belas";
  } else if ($nilai < 100) {
    $temp = penyebut($nilai/10)." puluh". penyebut($nilai % 10);
  } else if ($nilai < 200) {
    $temp = " seratus" . penyebut($nilai - 100);
  } else if ($nilai < 1000) {
    $temp = penyebut($nilai/100) . " ratus" . penyebut($nilai % 100);
  } else if ($nilai < 2000) {
    $temp = " seribu" . penyebut($nilai - 1000);
  } else if ($nilai < 1000000) {
    $temp = penyebut($nilai/1000) . " ribu" . penyebut($nilai % 1000);
  } else if ($nilai < 1000000000) {
    $temp = penyebut($nilai/1000000) . " juta" . penyebut($nilai % 1000000);
  } else if ($nilai < 1000000000000) {
    $temp = penyebut($nilai/1000000000) . " milyar" . penyebut(fmod($nilai,1000000000));
  } else if ($nilai < 1000000000000000) {
    $temp = penyebut($nilai/1000000000000) . " trilyun" . penyebut(fmod($nilai,1000000000000));
  }
  return $temp;
}

function number_to_words($nilai) {
  if($nilai<0) {
    $hasil = "minus ". trim(penyebut($nilai));
  } else {
    $hasil = trim(penyebut($nilai));
  }
  return $hasil;
}

function numberToRoman($number) {
  $map = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
  $returnValue = '';
  while ($number > 0) {
      foreach ($map as $roman => $int) {
          if($number >= $int) {
              $number -= $int;
              $returnValue .= $roman;
              break;
          }
      }
  }
  return $returnValue;
}

function bulan($bulan){
  switch($bulan){
  case 'Nov':
      $bulan_ini = "November";
  break;
  case 'Dec':			
      $bulan_ini = "Desember";
  break;
  case 'Jan':
      $bulan_ini = "Januari";
  break;
  case 'Feb':
      $bulan_ini = "Februari";
  break;
  case 'Mar':
      $bulan_ini = "Maret";
  break;
  case 'Apr':
      $bulan_ini = "April";
  break;
  case 'May':
      $bulan_ini = "Mei";
  break;
  case 'Jun':
      $bulan_ini = "Juni";
  break;
  case 'Jul':
      $bulan_ini = "Juli";
  break;
  case 'Aug':
      $bulan_ini = "Agustus";
  break;
  case 'Sep':
      $bulan_ini = "September";
  break;
  case 'Oct':
      $bulan_ini = "Oktober";
  break;
  default:
      $bulan_ini = "Tidak di ketahui";		
  break;
  }
  return $bulan_ini;
}

function hari($hari){
  switch($hari){
  case 'Sunday':
      $hari_ini = "Minggu";
  break;
  case 'Monday':			
      $hari_ini = "Senin";
  break;
  case 'Tuesday':
      $hari_ini = "Selasa";
  break;
  case 'Wednesday':
      $hari_ini = "Rabu";
  break;
  case 'Thursday':
      $hari_ini = "Kamis";
  break;
  case 'Friday':
      $hari_ini = "Jumat";
  break;
  case 'Saturday':
      $hari_ini = "Sabtu";
  break;
  default:
      $hari_ini = "Tidak di ketahui";		
  break;
  }
  return $hari_ini;
}