<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

$config['log_versi'] = '<p><pre>
/*
Log Versi
v1.0	    => Create Modul Sub Modul
v4.0.A	  	=> Themes dimodify sesuai kebutuhan
v4.1	    => Enggine PHP Codeigniter mulai bersatu
v4.1.A	  	=> Modify script extjs dan php
v4.2	    => Sudah Connect database beserta enkripsi nama host,database,user,pass


*/</pre></p>';
$config['app_versi'] 			= 'v7.4.D';
$config['app_title'] 			= ".: GED Integrated System (GIS):. " . $config['app_versi'];
$config['title'] 				= "GIS";
$config['tools_title']        	= ".: GED Integrated System :. " . $config['app_versi'];
$config['login_title_header']   = ".: GED Integrated System :.";
$config['login_title_footer']   = " GED Integrated System</br> " . $config['app_versi'];
$config['tools_header']        	= ".: GED Integrated System :.<br>(GIS) " . $config['app_versi'] . "<br>&copy;" . date("Y");
$config['copyright']        	= "&copy;2016-" . date("Y") . " Developer. All Rights Reserved";
$config['activasi']				= array('NO AKTIF', 'AKTIF');
$config['limit_header']			= 3;
$config['access']				= array('NO PUBLISH', 'PUBLISH');
$config['publish']				= array('NO PUBLISH', 'PUBLISH');
$config['hidden_karakter']		= array('a', 'i', 'u', 'e', 'o', '1', '2', '3', '4', '5', '6', '7', '8', '9', '0');
$config['batas_upload']			= 3145728; // 3 kilobyte file upload => 3 mega => 3 X 1024 X 1024
$config['limit_page']			= 20;
$config['limit_input']			= 5;
$config['limit_box']			= 2;
$config['timer']				= 30;
$config['resolusi_width']		= 250;
$config['resolusi_height']		= 180;
$config['popwin_width']			= $config['resolusi_width'] + 100;
$config['popwin_height']		= $config['resolusi_height'] + 100;
$config['full_width']			= 50;
$config['full_height']			= 150;
$config['send_protocol']		= "smtp";
$config['send_host']			= "";
$config['send_port']			= 25;
$config['send_user']			= "";
$config['send_pass']			= "";
$config['send_to_cs']			= "arab.emo@gmail.com";
$config['app_expire']     		= 3000; // second
$config['singlesignon'] 		= "jtetrace";
$config['sett_generateaccount']	= 1000;
$config['sett_addloop']         = 1;
$config['sett_delay']         	= 1;
$config['sett_subaccount']		= array("0" => "Company", "1" => "Divisi", "2" => "Department", "3" => "Unit Busnis", "9" => "Other");
$config['sett_corporatecode']	= array("GLI" => "GLI", "TNC" => "TNC");
$config['sett_currency']		= array("IDR" => "Rupiah", "US" => "Dollar US");
$config['sett_directory']		= array("Kab." => "KABUPATEN", "Kota." => "KOTA");
$config['sett_direcGroup']		= array("" => "Pilihan", "DOM" => "Domestik", "INT" => "Internasional");
$config['sett_yesno']			= array("Y" => "Yes", "N" => "No");
$config['sett_benua']			= array("" => " ", "ASI" => "Benua Asia", "ERO" => "Benua Eropa", "AMR" => "Benua Amerika", "AFR" => "Benua Afrika", "AUS" => "Benua Australia");
$config['sett_MasterinvRevisi']	= array("" => "Pilih --", "1" => "1", "2" => "2", "3" => "3");
$config['sett_grouptvendor']	= array(
	'Delivery' 	=> 'Vendor Delivery',
	'Kalog'			=> 'KALOG',
	'Darat'			=> 'Vendor Darat',
	'Laut'			=> 'Vendor Laut',
	'Udara'			=> 'Vendor Udara',
	'Other'			=> 'Vendor Other'
);


$config['sett_typedelivery'] 		= array(
	0 => "DELIVERY ONLY",
	1 => "COLLECT",
	2 => "CHARTER"
);
$config['sett_typedelivery_mini'] 		= array(
	0 => "Delivery",
	1 => "Collect"
);

$config['sett_statusinvoice'] 				= array(
	0 => "NO STATUS",
	1 => "WAITING",
	2 => "PROCESS",
	3 => "RECEIVED",
	9 => "CLOSE"
);

$config['sett_typecollect'] 		= array(
	0 => "CASH ON DELIVERY",
	1 => "DEBET",
	2	=> "CREDIT",
	3	=> "OTHERS"
);
$config['sett_typepayment'] 		= array(
	0 	=> "CREATE INVOICE",
	1 	=> "MODIFY INVOICE",
	10 	=> "PRINTED INVOICE",
	11 	=> "DELIVERED INVOICE",
	99	=> "FINISHING INVOICE"
);

$config['sett_ppn']					= array(
	'0' 		=> '0',
	'1' 		=> '1',
	'10' 		=> '10'
);
$config['sett_materai']					= array(
	'0' 		=> '0',
	'3000' 		=> '3000',
	'6000' 		=> '6000'
);

$config['dir_upload_kurir']        				= "assets/uploads/kurir/";
$config['dir_upload_signature']        			= "assets/uploads/signature/";
$config['dir_upload_pickurir']        			= "assets/uploads/pic_kurir/";
$config['dir_upload_website_banner']        	= "assets/uploads/website/banner/";
$config['dir_upload_website_news']        		= "assets/uploads/website/news/";
$config['dir_upload_website_header']        	= "assets/uploads/website/header/";
$config['dir_upload_website_gallery']      		= "assets/uploads/website/gallery/";
$config['dir_upload_excel']        			    = "assets/uploads/excel/";
$config['dir_upload_network']        			= "assets/uploads/network/";
$config['dir_upload_wallpaper']        			= "assets/images/wallpapers/";
$config['dir_upload_sdr']        				= "assets/uploads/sdr/";
$config['dir_upload_csis']         				= "assets/uploads/csis/";
$config['sett_koma'] 							= array(",", ".");
//LIST
$config['metadata']			=
	'
  <meta name="description" content="PT.TRIMUDA NUANSA CITRA ( GED ), and Transport Systems" />
  <meta name="keywords" content="GIS Network, Tracing, and Transport Systems" />
  <meta name="author" content="Andhi Sulistyanto - http://www.andhiequest.com"/>
  <link rel="shortcut icon" href="assets/images/favicon.png" type="image/x-icon" />
';
$config['sett_hour']					= 2;
$config['sett_leadtimehour']			= 8;
$config['sett_leadtimeminute']			= 30;
$config['sett_afterday']				= 3;
$config['sett_afterdhour1']				= 2;
$config['sett_afterdhour2']				= 4;
$config['sett_afterdhour3']				= 6;
$config['volume_pesawat']				= 6000;
$config['detail_coli']					= 5;
$config['default_package']				= "SPS";
$config['default_payment']				= "CR";
$config['default_service']				= "REG";
$config['default_transport']			= "UDARA";
$config['default_volume']				= "6000";
$config['default_status']				= "SELF";
$config['mininum_asuransi']				= 1500;
$config['diskon_asuransi']				= 0.003;
$config['number_parameter']				= 100000000000; //11 digit
$config['number_generate']				= 1000000; //6 digit
$config['number_invoice']				= 100000; //5 digit
$config['account_bypass']        		= "10015110020";
$config['sett_dtraceinvoice'] 			= array(
	0 => "NO STATUS",
	1 => "WAITING",
	2 => "PROCESS",
	3 => "RECEIVED",
	4 => "CLOSE"
);
$config['sett_dtraceinvoiceorg'] 		= array(
	0 => "NO STATUS",
	1 => "WAITING"
);
$config['sett_dtraceinvoicedest'] 		= array(
	2 => "PROCESS",
	3 => "RECEIVED"
);
$config['sett_dtraceinvoicefinish'] 		= array(
	3 => "RECEIVED",
	4 => "CLOSE"
);
$config['default_minkg']				= 1.00;
$config['notifikasi_day']				= 30;
$config['duedate_day']					= 7;
$config['default_angdom']        		= "5100-02";
//$config['flagpostingcost']        		= array("5100-01","5100-02","5100-03");
$config['flagpostingcost']        		= array("5100-01", "5100-02");


$config['sett_podcustomer'] 			= array(
	0 => "CREATE POD CUSTOMER",
	1 => "SEND POD CUSTOMER",
	2 => "READ POD CUSTOMER"
);
$config['type_upload']					= array("image/jpeg", "image/jpg", "image/JPG", "application/octet-stream");

$config['sett_curr'] 					= array(
	"IDR" => "Curr Rupiah",
	"US" => "Curr Dollar"
);
$config['sett_bound'] 					= array(
	"IN" => "In Bound",
	"OUT" => "Out Bound"
);

$config['sett_transportasi'] 		= array(
	0 	=> "",
	1 	=> "Motor",
	2	=> "Mobil",
	3	=> "Blind van",
	4	=> "CDD",
	5	=> "CDE",
	6	=> "Other"
);
$config['sett_pod'] 				= array(
	0 	=> "ALL",
	1 	=> "OVERDUE POD",
	2	=> "COLLECT POD",
	3	=> "ON DELIVERY",
	4	=> "INPUT IOD",
	5	=> "DONE"
);

$config['sett_case'] 					= array(
	"0" => "OPEN CASE",
	"9" => "CLOSE CASE"
);

$config['default_transportasi']		= 5;
$config['default_leadtime_arsip']			= 7;
$config['sett_whereinvoice'] 		= array(
	"" => "Search By All",
	"(trnDeliveredByName IS NOT NULL AND trnDeliveredByName<>'')" => "Search By Delivered Is Not Null",
	"(trnTypePayment='CR')" => "Search By Payment Credit",
	"(trnTypePayment='CS')" => "Search By Payment Cash"
);


$config['sett_fieldtrace'] 		= array(
	"NO ACCOUNT"				=> "trnAccCustomer",
	"NAME ACCOUNT"				=> "custName",
	"SHIPPER NAME"				=> "trnShipperName",
	"SHIPPER CITY"				=> "trnShipperCity",
	"CONSIGNEE"					=> "trnConsigneeName",
	"CONSIGNEE ADDRESS"			=> "CONCAT(trnConsigneeAlm1,@ @,trnConsigneeAlm2,@ @,trnConsigneeAlm3)",
	"CONSIGNEE CITY"			=> "trnConsigneeCity",
	"RECEIVED NAME"				=> "trnDeliveredByName",
	"RECEIVED DATE"				=> "DATE_FORMAT(trnDeliveredDate, @%e-%b-%Y@)",
	"PICKUP DATE"				=> "DATE_FORMAT(trnPickupDate, @%e-%b-%Y@)",
	"NO MANIFEST"				=> "trnManifestNumber",
	"NO SMU"					=> "trnSmuNumber",
	"NO INVOICE"				=> "trnInvoiceNumber",
	"SERVICE"					=> "trnTypeService",
	"PACKAGE"					=> "trnTypePackage",
	"PAYMENT"					=> "trnTypePayment",
	"VOLUME"					=> "trnTypeVolume",
	"DISKON"					=> "REPLACE(REPLACE(REPLACE(FORMAT(trnDisc,2), @,@, @:@), @.@, @,@), @:@, @.@)",
	"COLLY"						=> "REPLACE(REPLACE(REPLACE(FORMAT(trnActualColi,2), @,@, @:@), @.@, @,@), @:@, @.@)",
	"W.VOLUME"					=> "REPLACE(REPLACE(REPLACE(FORMAT(trnsVolumeKg,2), @,@, @:@), @.@, @,@), @:@, @.@)",
	"W.ACTUAL"					=> "REPLACE(REPLACE(REPLACE(FORMAT(trnWeight,2), @,@, @:@), @.@, @,@), @:@, @.@)",
	"W.CHARGE"					=> "REPLACE(REPLACE(REPLACE(FORMAT(trnsActualKg,2), @,@, @:@), @.@, @,@), @:@, @.@)",
	"FREIGHT CHARGES (Price)"	=> "REPLACE(REPLACE(REPLACE(FORMAT(trnTotalCharge,2), @,@, @:@), @.@, @,@), @:@, @.@)",
	"OTHERS (Price)"			=> "REPLACE(REPLACE(REPLACE(FORMAT((trnChargePacking+trnChargeInsurance+trnChargeOthers),2), @,@, @:@), @.@, @,@), @:@, @.@)",
	"TOTAL PRICE (Price)"		=> "REPLACE(REPLACE(REPLACE(FORMAT(trnTotalAll,2), @,@, @:@), @.@, @,@), @:@, @.@)",
	"LEADTIME DELIVERY"			=> "trnLeadTimeDelivery",
	"LEADTIME POD"				=> "trnLeadTimePod",
	"DIFFERENCE"				=> "to_days(trnDeliveredDate)-to_days(trnDate)",
	"PACKING"					=> "REPLACE(REPLACE(REPLACE(FORMAT(trnChargePacking,2), @,@, @:@), @.@, @,@), @:@, @.@)",
	"CREATED"					=> "CONCAT(trnCreateDate,@ @,trnCreateUser)",
	"UPDATED"					=> "CONCAT(trnUpdateDate,@ @,trnUpdateUser)",
	"SPS INSTRUCTION"			=> "trnSpecialInstruction",
	"REFF CUSTOMER"				=> "trnReffInstruction",
	"ARSIP POD NUMBER"			=> "trnArsipPodNumber",
	"ARSIP POD DATE"			=> "DATE_FORMAT(arsip.masterDate,  @%e-%b-%Y@)",
	"ARSIP CUSTOMER NUMBER"		=> "(SELECT masterNumber FROM dbgedtrace.manifestcustomerdetail custdetail LEFT JOIN dbgedtrace.manifestcustomermaster custmaster on custmaster.masterId=custdetail.detailMaster WHERE custdetail.detailNumberawb=a.trnNumberAwb LIMIT 1)",
	"ARSIP CUSTOMER DATE"		=> "(SELECT DATE_FORMAT(custmas.masterDate, @%e-%b-%Y@) FROM dbgedtrace.manifestcustomerdetail custdet LEFT JOIN dbgedtrace.manifestcustomermaster custmas on custmas.masterId=custdet.detailMaster WHERE custdet.detailNumberawb=a.trnNumberAwb LIMIT 1)",
	"POSTING NUMBER"			=> "1",
	"POSTING ANGDOM"			=> "(SELECT sum(treePriceTotal) FROM dbgedtrace.postingpaymenttree postingangdom WHERE  postingangdom.treeConnote=a.trnNumberAwb and  postingangdom.treeCoa	=@5100-02@ GROUP BY  postingangdom.treeConnote)",
	"POSTING SMU"				=> "(SELECT sum(treePriceTotal) FROM dbgedtrace.postingpaymenttree postingsmu WHERE  postingsmu.treeConnote=a.trnNumberAwb and  postingsmu.treeCoa	=@5100-01@ GROUP BY  postingsmu.treeConnote)"
);

/*
"REPLACE(REPLACE(REPLACE(FORMAT(trnActualColi,2), @,@, @:@), @.@, @,@), @:@, @.@)"		=> "COLLY",
"REPLACE(REPLACE(REPLACE(FORMAT(trnsVolumeKg,2), @,@, @:@), @.@, @,@), @:@, @.@)"		=> "W.VOLUME",
"REPLACE(REPLACE(REPLACE(FORMAT(trnWeight,2), @,@, @:@), @.@, @,@), @:@, @.@)"			=> "W.ACTUAL",
"REPLACE(REPLACE(REPLACE(FORMAT(trnsActualKg,2), @,@, @:@), @.@, @,@), @:@, @.@)"		=> "W.CHARGE",
*/

$config['sett_flight']					= array(
	'0'	=> 'No Prioritas',
	'1' => 'Prioritas 1',
	'2' => 'Prioritas 2',
	'3' => 'Others',
	'4' => 'Kalog',
	'5' => 'Vaksin Case',
	'6' => 'GTJ-01',
	'7' => 'GTJ-02',
	'8' => 'GTJ-03',
	'9' => 'GTJ-04'
);

$config['sett_filterbox']					= array(
	'CollyHub' 		=> 'Filter By Hub',
	'CollyAccount'	=> 'Filter By Account',
	'CollyConnote'	=> 'Filter By Connote',
);
$config['default_filterbox']					= 'CollyHub';
$config['max_createconnote']					= 10000;

$config['sett_kategoricoa'] 			= array(
	"Hutang Lancar Lainnya",
	"Aktiva Lancar Lainnya",
	"Harga Pokok Penjualan",
	"Kas/Bank",
	"Piutang Usaha",
	"Persediaan",
	"Aktiva Lancar Lainnya",
	"Aktiva Tetap",
	"Akumulasi Penyusutan",
	"Hutang Usaha",
	"Hutang Lancar Lainnya",
	"Hutang Jangka Panjang",
	"Ekuitas",
	"Harga Pokok Penjualan",
	"Beban",
	"Beban Lainnya",
	"Pendapatan Lainnya"
);
$config['sett_groupcoa'] 			= array(
	"D" => "DEBET",
	"K" => "KREDIT"
);

$config['header_company_trace'] = null;

$config['name_company'] 		      = "PT. JASA TITIPAN EKPRES";
$config['address_company'] 	      = "JL.Bendungan Jago No. 36 RT.9/RW.1 Serdang, Kemayoran,<br/> Jakarta pusat 10630";
$config['telp_company'] 		      = "(021)83703700";
$config['fax_company'] 			      = "(021)83703800";
$config['npwp_company'] 		      = "";
$config['website_company']	      = "https://www.jte.co.id";
$config['bank_company'] 		      = "PT. Trimuda Nuansa Citra<br>BCA ASIA AFRIKA A/C : 0083053116 (GED CABANG BDO)<br>6380008431 BCA (CAB SUNTER -GED CABANG BPN)";
$config['bank_company12'] 	      = "PT. JASA TITIPAN EKPRES<br>BCA KCP SOEPOMO A/C : 600-009-0962  (GED CABANG BPN,UPG,MES,JOG,SRG)";
$config['bank_company11'] 	      = "PT. JASA TITIPAN EKPRES<br>BCA KCP JUANDA A/C  : 667-095-0008  (GED CABANG SUB)";
$config['bank_company10'] 	      = "PT. JASA TITIPAN EKPRES<br>BCA ASIA AFRIKA A/C : 008-368-7841  (GED CABANG BDO)";
$config['bank_company9'] 		      = "PT. JASA TITIPAN EKPRES<br>BCA CABANG SOEPOMO A/C : 6000336139 (GED CABANG JKT)";
$config['bank_company8'] 		      = "PT. Trimuda Nuansa Citra<br>BCA ASIA AFRIKA A/C : 0083053116 (GED CABANG BDO)";
$config['bank_company7'] 		      = "PT. Trimuda Nuansa Citra<br>BCA PUCANG ANOM A/C : 0640353775 (GED CABANG SUB)";
$config['bank_company6'] 		      = "PT. Trimuda Nuansa Citra<br>BCA SUNTER A/C : 6380008431 (GED CABANG BPN,JOG,SQRG,MES)";
$config['bank_company5'] 		      = "PT. Trimuda Nuansa Citra<br>MANDIRI TEBET SOEPOMO  A/C : 1240005446258 (GED CABANG BPN)";
$config['bank_company4'] 		      = "PT. Trimuda Nuansa Citra<br>COMMONWEALTH WISMA KODEL A/C : 1066411835 (GED CABANG JKT)";
$config['bank_company3'] 		      = "PT. Trimuda Nuansa Citra<br>UOB PLAZA THAMRIN A/C : 3273033295 (GED CABANG JKT)";
$config['bank_company2'] 		      = "PT. Trimuda Nuansa Citra<br>SINARMAS ITC AMBASADOR A/C : 0223788818 (GED CABANG JKT)";
$config['bank_company1'] 		      = "PT. Trimuda Nuansa Citra<br>BCA SOEPOMO A/C : 6000301246 (GED UTAMA JKT)";
$config['rekbank_companygli'] 	  = "6000336139 BCA SOEPOMO (JKT), 008-368-7841 BCA ASIA AFRIKA (BDO),667-095-0008 BCA KCP JUANDA (SUB), 600-009-0962 BCA KCP SOEPOMO (GED CABANG BPN,UPG,MES,JOG,SRG)";
$config['rekbank_company'] 	      = "6000301246 BCA SOEPOMO I, 6000369266 BCA SOEPOMO II,015044919 BNI, 208001000051306 BRI,1066411835 COMMONWEALTH, 1185006877 PANIN,0171000050 PUNDI,3273033295 UOB,0023788818 SINARMAS,2930100125008 CIMB NIAGA,4200200009008 CIMB NIAGA,0083053116 BCA (GED BDO),6380008431 BCA (CAB SUNTER - GED BPN),Bank BCA A/C : 0640353775 (Cab. Pucang Anom . GED Cabang SUB)";
$config['direktur_company'] 	    = array("ACHMAD MULYANA", "DINO SURONO", "BUDI MULYANI", "RICHARDO", "FAJAR PUSPITANINGRUM", "HARI", "EKO WAHYUDI", 'HASMAN');
$config['code_company'] 		      = "JTE";
$config['code_company1'] 		      = "GLI";
$config['ppn_company'] 			      = 1;
$config['pajak_company'] 		      = 10;
$config['duedate']				        = 14;
$config['sett_pajak_company']	    = array("040", "010");
$config['sett_pajak_persentasi']  = array("040" => 1, "010" => 10);
$config['sett_customer_nopajak']  = array("10016140020");
$config['sett_selisihday']		    = 1;
$config['sett_nameday']			      = array("0" => "Minggu", "1" => "Senin", "2" => "Selasa", "3" => "Rabu", "4" => "Kamis", "5" => "Jum'at", "6" => "Sabtu");
$config['sett_ofservice']		      = array("1" => "DARAT", "2" => "LAUT", "3" => "UDARA");
$config['sett_ofgoods']		        = array("1" => "Barang Pecah Belah", "2" => "Barang Berbahaya", "3" => "Lainnya");
$config['coverings']			        = array('Pilih --','YES', 'NO');
$config['coverings_sub']		      = array('INSURANCE POLICY', 'NON-POLICY');
$config['repacking']			        = array('NO', 'YES', 'AS REQUSTED');
$config['pickupRequest']		      = array('TIME', 'DAILY', 'AS REQUSTED');
$config['sett_ofdelivery']		    = array("1" => "HARIAN", "2" => "MINGGUAN", "3" => "BULANAN");
$config['sett_statuscompany']	    = array("0" => "Pilih --", "1" => "PERUSAHAAN KENA PAJAK(PKP)", "2" => "PERUSAHAAN TIDAK KENA PAJAK(NON-PKP)");
$config['default_cityname'] 	    = "Tebet, Kota. Jakarta Selatan, Jakarta, Indonesia @ID-JK*476";
$config['default_citycode'] 	    = "ID-JK*476";
$config['default_cityhub'] 		    = "JKT";
$config['default_startperiode']	  = "01/01/2019";
$config['default_mulaiperiode']	  = "2019-01-01";
$config['listofPayment']		      = array('Pilih --','CASH', 'CREDIT');
$config['listofPaymentop']		    = array('Pilih --','7 DAYS', '14 DAYS' , '30 DAYS');


$url = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https" : "http");
$url .= "://" . $_SERVER['HTTP_HOST'];
$url .= str_replace(basename($_SERVER['SCRIPT_NAME']), "", $_SERVER['SCRIPT_NAME']);

$config['ecconote_logo'] 		= $url . "assets/images/logo/logo_awb.png";
$config['ecconote_header']		= "";
$config['ecconote_address1']	= "Wisma Intra Asia";
$config['ecconote_address2']	= "Ground Floor, Jl. Prof. Dr. Supomo SH No 58";
$config['ecconote_address3']	= "Jakarta 12870";
$config['ecconote_telp']		= "(021)83703700";
$config['ecconote_fax']			= "(021)83703800";
$config['ecconote_website']		= "www.ged.co.id";

$config['sett_validasimonth']  	= 1;
#$config['sett_validasimonth']   =4;
ini_set('memory_limit', '64M');
