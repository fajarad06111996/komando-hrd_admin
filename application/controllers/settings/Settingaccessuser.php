<?php
defined('BASEPATH') or exit('No direct script access allowed');
require 'vendor/autoload.php';

use Nullix\CryptoJsAes\CryptoJsAes;
class Settingaccessuser extends AUTH_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('settings/M_user', 'mUser');
		$this->load->model('M_level', 'mLevel');
		$this->load->model('M_auth');
		$this->load->library('security_function');
		$this->link		  = site_url('settings/').strtolower(get_class($this));
		$this->filename	= strtolower(get_class($this));
		$this->office   = $this->secure->dec($this->session->userdata('JToffice_id'));
		$this->hub      = $this->secure->dec($this->session->userdata('JThub_id'));
		$this->idx      = $this->secure->dec($this->session->userdata('JTidx'));
		$this->username = $this->session->userdata('JTuser_id');
		$this->level    = $this->secure->dec($this->session->userdata('JTlevel'));
		$this->enkey  	= $this->config->item('encryption_key');
	}
	public function index()
	{
		$access = $this->security_function->permissions($this->filename . "-r");
		if (empty($access)) die("<center><h1>You can not get the right to access this module</h1></center>");
		if(!empty($this->security_function->permissions($this->filename . "-w"))){
			$data['write']  = '<a href="javascript:void(0)" id="btnAdd" class="pull-right text-white small" title="Add New Access User" data-placement="right" data-popup="tooltip"><i class="icon-plus-circle2"></i></a>';
			$data['lock']   = 1;
		}else{
			$data['write']  = '<i class="icon-lock pull-right pt-1 " title="Locked New Access User" data-placement="right" data-popup="tooltip"></i>';
			$data['lock']   = 0;
		}
		$in_company = $this->mAccess->getTable('app_oaccess',['access_level_id' => $this->level])->row_array();
		$isCompany = explode(",",$in_company['access_company_idx']);
		$data['filename']           = $this->filename;
		$data["link"]               = $this->link;
		$data['userdata']           = $this->userdata;
		$data['usname']             = $this->username;
		if($this->level == 1){
			$data['level']            = $this->mAccess->readtable('app_level_access', 'level_id,level_name,level_alias',array('level_active'=>1),'','',array('level_id'=>'ASC'))->result();
		}else{
			$data['level']            = $this->mAccess->readtable('app_level_access', 'level_id,level_name,level_alias',array('level_active'=>1,'level_id <>'=>1),'','',array('level_id'=>'ASC'))->result();
		}
		$data['menu']               = $this->mAccess->readtable('app_menu', 'menu_id,menu_title,menu_alias',array('menu_parent_sub'=>1,'menu_access'=>1),'','',array('menu_urutan'=>'ASC'))->result();
		$data['company']             = $this->mAccess->getWhereInAndWhere('master_company','idx',$isCompany,array('status'=>1))->result();
		$data['submodul']           = $this->mAccess->readtable('app_menu', 'menu_id,menu_title,menu_alias',array('menu_parent_sub'=>2,'menu_access'=>1),'','',array('menu_alias'=>'ASC'))->result();
		$data['isLevel']            = $this->level;
		$params     	    = '{"link": "'.$this->link.'", "csrf": "'.$this->session->csrf_token.'", "permW": "'.$this->security_function->permissions($this->filename . "-w").'", "permC": "'.$this->security_function->permissions($this->filename . "-c").'", "permX": "'.$this->security_function->permissions($this->filename . "-x").'", "base_url": "'.base_url().'", "lock": "'.$data['lock'].'"}';
		// encrypt
		$encrypted          = CryptoJsAes::encrypt($params, $this->enkey);
		$data['params']     = $encrypted;
		$this->template->views('pengaturan/v_accessuser', $data);
	}
	public function showAllAccessUser()
	{
		$fetch_data = $this->mUser->showAllAccessUser();
		$data       = array();
		$i          = $_POST['start'] + 1;
		foreach ($fetch_data as $row) {
			$sub_array = array();
			if ($row->access_rolemenu == '1') {
				$menuAlias  = $row->menu_alias;
			} else {
				$param      = $this->mAccess->getTable('app_menu',array('menu_id' => $row->menu_parent_id))->row_array();
				$menuAlias  =$param['menu_alias'].' -> '.$row->menu_alias;
			}
			$submenu="";
			$arr_submenu = explode(',',$row->access_submenu_id);
			foreach ($arr_submenu AS $k):
				if(!empty($k)):
					$nama    = $this->mUser->getMenuAlias($k);
					$submenu.= $nama." ,";
				else:
					$submenu = "";
				endif;  
			endforeach;
			if(!empty($this->security_function->permissions($this->filename . "-c"))){
				$change = "<a href='javascript:void(0);' id='$row->access_id' class='bEdit' data-popup='tooltip' title='Edit Access User' data-placement='right'><i class='fa fa-edit fa-lg'></i>&nbsp;</a>";
			}else{
				$change = '<i class="fa fa-lock fa-lg text-warning mr-2" data-popup="tooltip" title="Locked Edit Access User" data-placement="right"></i>&nbsp;';
			}
			if(!empty($this->security_function->permissions($this->filename . "-x"))){
				$execute  = "<a href='javascript:void(0);' id='$row->access_id' data='$menuAlias' class='bDelete' data-popup='tooltip' title='Delete Access User' data-placement='right'><i class='fa fa-trash-o fa-lg'></i>&nbsp;</a>";
			}else{
				$execute  = '<i class="mi-lock text-warning font-weight-black" data-popup="tooltip" title="Locked Delete Access User" data-placement="right"></i>&nbsp';
			}
			$sub_array[] = $i++;
			$sub_array[] = $row->level_name;
			$sub_array[] = $row->level_alias;
			$sub_array[] = $menuAlias;
			$sub_array[] = $submenu;
			$sub_array[] = $row->access_permissions_id;
			$sub_array[] = "$change $execute";
			$data[]      = $sub_array;
		}
		$output = array(
			"draw"            => intval($_POST["draw"]),
			"recordsTotal"    => $this->mUser->getaccess_all_data(),
			"recordsFiltered" => $this->mUser->getaccess_filtered_data(),
			"data"            => $data
		);
		echo json_encode($output);
	}
	// CONTOH LAIN CONTROLLER POST DATATABLE SERVERSIDE
	public function posts()
	{
		$columns = array( 
			0 =>  'access_id', 
			1 =>  'level_alias',
			2 =>  'menu_alias',
			3 =>  'access_permissions_id',
			4 =>  'access_id'
		);
		$limit = $this->input->post('length');
		$start = $this->input->post('start');
		$csrfToken  = validate_csrf_token();
		$order = $columns[$this->input->post('order')[0]['column']];
		$dir = $this->input->post('order')[0]['dir'];
		// var_dump('<pre>');
		// var_dump($order);
		// var_dump($dir);
		// die();
		$totalData = $this->mUser->allposts_countAcc();
		$totalFiltered = $totalData; 
		if(empty($this->input->post('search')['value']))
		{            
			$posts = $this->mUser->allpostsAcc($limit,$start,$order,$dir);
		} else {
			$search = $this->input->post('search')['value']; 
			$posts =  $this->mUser->posts_searchAcc($limit,$start,$search,$order,$dir);
			$totalFiltered = $this->mUser->posts_search_countAcc($search);
		}
		$data = array();
		// var_dump('<pre>');
		// var_dump($posts);
		// var_dump('</pre>');
		if(!empty($posts))
		{
			foreach ($posts as $post)
			{
				if ($post->access_rolemenu == '1') {
					$menuAlias  = $post->menu_alias;
				} else {
					$param      = $this->mAccess->getTable('app_menu',array('menu_id' => $post->menu_parent_id))->row_array();
					$menuAlias  =$param['menu_alias'].' -> '.$post->menu_alias;
				}
				$submenu="";
				$arr_submenu = explode(',',$post->access_submenu_id);
				foreach ($arr_submenu AS $k):
					if(!empty($k)):
						$nama    = $this->mUser->getMenuAlias($k);
						$submenu.= $nama." ,";
					else:
						$submenu = "";
					endif;  
				endforeach;
				if(!empty($this->security_function->permissions($this->filename . "-c"))){
					$change = "<a href='javascript:void(0);' id='$post->access_id' class='bEdit text-center' data-popup='tooltip' title='Edit Access User' data-placement='right'><i class='fa fa-edit fa-lg'></i>&nbsp;</a>";
				}else{
					$change = '<i class="fa fa-lock fa-lg text-warning mr-2" data-popup="tooltip" title="Locked Edit Access User" data-placement="right"></i>&nbsp;';
				}
				if(!empty($this->security_function->permissions($this->filename . "-x"))){
					$execute  = "<a href='javascript:void(0);' id='$post->access_id' data='$post->level_alias' class='bDelete text-danger text-center' data-popup='tooltip' title='Delete Access User' data-placement='right'><i class='fa fa-trash-o fa-lg'></i>&nbsp;</a>";
				}else{
					$execute  = '<i class="mi-lock text-warning font-weight-black" data-popup="tooltip" title="Locked Delete Access User" data-placement="right"></i>&nbsp';
				}
				$nestedData['level_id']         = ++$start;
				$nestedData['level_alias']      = $post->level_alias;
				$nestedData['menu_alias']       = $menuAlias;
				$nestedData['submenu']          = $submenu;
				$nestedData['permissions']      = $post->access_permissions_id;
				$nestedData['aksi']             = "$change $execute";
				$data[] = $nestedData;
			}
		}
		if($csrfToken == false){
			$json_data = array(
				"draw"            => 0,
				"recordsTotal"    => 0,
				"recordsFiltered" => 0,
				"data"            => array()
			);
		}else{
			$json_data = array(
				"draw"            => intval($this->input->post('draw')),
				"recordsTotal"    => intval($totalData),
				"recordsFiltered" => intval($totalFiltered),
				"data"            => $data
			);
		}
		echo json_encode($json_data); 
	}
	public function showAllAccessLevel()
	{
		$fetch_data = $this->mLevel->showAllAccessLevel();
		$data       = array();
		$i          = $_POST['start'];
		foreach ($fetch_data as $row) {
		$sub_array = array();
		if ($row->access_rolemenu == '1') {
			$menuAlias  = $row->menu_alias;
		} else {
			$param      = $this->mAccess->getTable('app_menu',array('menu_id' => $row->menu_parent_id))->row_array();
			$menuAlias  =$param['menu_alias'].' -> '.$row->menu_alias;
		}
		$submenu="";
		$arr_submenu = explode(',',$row->access_submenu_id);
		foreach ($arr_submenu AS $k):
			if(!empty($k)):
			$nama    = $this->mLevel->getMenuAlias($k);
			$submenu.= $nama." ,";
			else:
			$submenu = "";
			endif;  
		endforeach;
		if(!empty($this->security_function->permissions($this->filename . "-c"))){
			$change = "<a href='javascript:void(0);' id='$row->access_id' class='bEdit' data-popup='tooltip' title='Edit Access User' data-placement='right'><i class='fa fa-edit fa-lg'></i>&nbsp;</a>";
		}else{
			$change = '<i class="fa fa-lock fa-lg text-warning mr-2" data-popup="tooltip" title="Locked Edit Access User" data-placement="right"></i>&nbsp;';
		}
		if(!empty($this->security_function->permissions($this->filename . "-x"))){
			$execute  = "<a href='javascript:void(0);' id='$row->access_id' data='$menuAlias' class='bDelete text-danger' data-popup='tooltip' title='Delete Access User' data-placement='right'><i class='fa fa-trash-o fa-lg'></i>&nbsp;</a>";
		}else{
			$execute  = '<i class="mi-lock text-warning font-weight-black" data-popup="tooltip" title="Locked Delete Access User" data-placement="right"></i>&nbsp';
		}
		$sub_array[] = ++$i;
		$sub_array[] = $row->level_alias;
		$sub_array[] = $menuAlias;
		$sub_array[] = $submenu;
		$sub_array[] = $row->access_permissions_id;
		$sub_array[] = "$change $execute";
		$data[]      = $sub_array;
		}
		$output = array(
		"draw"            => intval($_POST["draw"]),
		"recordsTotal"    => $this->mLevel->getaccess_all_data(),
		"recordsFiltered" => $this->mLevel->getaccess_filtered_data(),
		"data"            => $data
		);
		echo json_encode($output);
	}
	public function getAccessModul()
	{
		$msg = array('error' => false);
		$msg = array('status' => false);
		$this->form_validation->set_rules('tUname', 'Level Access', 'trim|required',
		['required' => 'Level Access wajib di pilih.']
		);
		$this->form_validation->set_rules('tCompany', 'Company', 'trim|required',
		['required' => 'Company wajib di pilih.']
		);
		$this->form_validation->set_rules('tMenu', 'Menu Parent', 'trim|required',
		['required' => 'Menu Parent wajib di pilih.']
		);
		if ($this->form_validation->run() == false) {
			$msg['error']   = true;
			$msg['message'] = validation_errors();
			echo json_encode($msg);die;
		}
		$tUser      = $this->input->post('tUname', TRUE);
		$tMenu      = $this->input->post('tMenu', TRUE);
		$tCompany  	= $this->input->post('tCompany', TRUE);
		$tSubmenu   = $this->input->post('tSubmenu', TRUE);
		if(!empty($tCompany)){
			$checkingParent = $this->mAccess->readtable('app_menu', '',array('menu_id'=>$tMenu, 'menu_parent_active' => 0,'menu_access'=>1,'menu_deletion'=>'N'),'','','')->num_rows();
			if($checkingParent > 0){
				// $msg['status']  = true;
				$resMenux	= $this->mAccess->readtable('app_menu', '',array('menu_id'=>$tMenu,'menu_access'=>1,'menu_deletion'=>'N'),'','','')->row_array();
				if($resMenux['menu_id'] === '195'){
					if($this->level === '1'){
						$resMenu  = $this->mAccess->readtable('app_menu', '',array('menu_parent_id'=>$tMenu,'menu_access'=>1,'menu_deletion'=>'N'),'','',array('menu_alias'=>'ASC'))->result();
					}else{
						$resMenu  = $this->mAccess->getWhereNotInAndWhere2('app_menu', '','','menu_id',array(13,15,20),array('menu_parent_id'=>$tMenu,'menu_access'=>1,'menu_deletion'=>'N'),array('menu_alias'=>'ASC'))->result();
					}
					if(count($resMenu)!=0){
						foreach($resMenu AS $r){
							$resUser	= $this->mAccess->getTable('app_uaccess',array('access_level_id'=> $tUser,'access_menu_id'=> $tMenu, 'access_company_id' => $tCompany))->result();
							$modul    = strtolower(str_replace(' ','',$r->menu_title));
							if($resUser){
								foreach($resUser AS $u){
									$checkMenu 		    = explode(',',$u->access_submenu_id);
									$checkPermissions = explode(',',$u->access_permissions_id);
									if (in_array($r->menu_id, $checkMenu)):
										$chekedModul="checked='checked'";
									else:
										$chekedModul='';
									endif;
									$permR  = strtolower(str_replace(' ','',$r->menu_title)).'-r';
									$permW  = strtolower(str_replace(' ','',$r->menu_title)).'-w';
									$permC  = strtolower(str_replace(' ','',$r->menu_title)).'-c';
									$permX  = strtolower(str_replace(' ','',$r->menu_title)).'-x';
									$checkR = in_array($permR, $checkPermissions)?"checked='checked'":'';
									$checkW = in_array($permW, $checkPermissions)?"checked='checked'":'';
									$checkC = in_array($permC, $checkPermissions)?"checked='checked'":'';
									$checkX = in_array($permX, $checkPermissions)?"checked='checked'":'';
									$tr = "
										<tr>
										<td><input type='checkbox' id='$modul' $chekedModul name='tModul[]' value='$r->menu_id' selected onclick='getClick(this)'><span class='pl-2 pt-0'>$r->menu_alias</span></td>
										<td class='text-center'><input type='checkbox' name='tsub[]' $checkR value='$modul-r' class='$modul' onclick='get2Click(this)'></td>
										<td class='text-center'><input type='checkbox' name='tsub[]' $checkW value='$modul-w' class='$modul' onclick='get2Click(this)'></td>
										<td class='text-center'><input type='checkbox' name='tsub[]' $checkC value='$modul-c' class='$modul' onclick='get2Click(this)'></td>
										<td class='text-center'><input type='checkbox' name='tsub[]' $checkX value='$modul-x' class='$modul' onclick='get2Click(this)'></td>
										</tr> ";
									$gettr[] = $tr;
								}
							}else{
								$tr = "
									<tr>
										<td><input type='checkbox' id='$modul' name='tModul[]'  value='$r->menu_id' selected onclick='getClick(this)'><span class='pl-2 pt-0'>$r->menu_alias</span></td>
										<td class='text-center'><input type='checkbox' name='tsub[]' value='$modul-r' class='$modul' onclick='get2Click(this)'></td>
										<td class='text-center'><input type='checkbox' name='tsub[]' value='$modul-w' class='$modul' onclick='get2Click(this)'></td>
										<td class='text-center'><input type='checkbox' name='tsub[]' value='$modul-c' class='$modul' onclick='get2Click(this)'></td>
										<td class='text-center'><input type='checkbox' name='tsub[]' value='$modul-x' class='$modul' onclick='get2Click(this)'></td>
									</tr> ";
								$gettr[] = $tr;
							}
						}
						$msg['res_tr']  = $gettr;
					}else{
						// $msg['status']  = true;
						$resMenu	= $this->mAccess->readtable('app_menu', '',array('menu_id'=>$tMenu,'menu_access'=>1,'menu_deletion'=>'N'),'','','')->row_array();
						$resUser	= $this->mAccess->getTable('app_uaccess',array('access_level_id'=> $tUser,'access_menu_id'=> $tMenu, 'access_company_id' => $tCompany))->result();
						$modul    = strtolower(str_replace(' ','',$resMenu['menu_title']));
						$menu_id        = $resMenu['menu_id'];
						$menu_alias     = $resMenu['menu_alias'];
						$parent_active  = $resMenu['menu_parent_active'];
						if($resUser){
							foreach($resUser AS $u){
								$checkMenu 		    = explode(',',$u->access_submenu_id);
								$checkPermissions = explode(',',$u->access_permissions_id);
								if ($checkMenu):
									$chekedModul="checked='checked'";
								else:
									$chekedModul='';
								endif;
								// if (in_array($r->menu_id, $checkMenu)):
								//   $chekedModul="checked='checked'";
								// else:
								//   $chekedModul='';
								// endif;
								$permR  = strtolower(str_replace(' ','',$resMenu['menu_title'])).'-r';
								$permW  = strtolower(str_replace(' ','',$resMenu['menu_title'])).'-w';
								$permC  = strtolower(str_replace(' ','',$resMenu['menu_title'])).'-c';
								$permX  = strtolower(str_replace(' ','',$resMenu['menu_title'])).'-x';
								$checkR = in_array($permR, $checkPermissions)?"checked='checked'":'';
								$checkW = in_array($permW, $checkPermissions)?"checked='checked'":'';
								$checkC = in_array($permC, $checkPermissions)?"checked='checked'":'';
								$checkX = in_array($permX, $checkPermissions)?"checked='checked'":'';
								if($parent_active ==1){
									$inputR="<i class='fa fa-times text-danger'></i>";
									$inputW="<i class='fa fa-times text-danger'></i>";
									$inputC="<i class='fa fa-times text-danger'></i>";
									$inputX="<i class='fa fa-times text-danger'></i>";
								}else{
									$inputR="<input type='checkbox' name='tsub[]' $checkR value='$modul-r' class='$modul' onclick='get2Click(this)'>";
									$inputW="<input type='checkbox' name='tsub[]' $checkW value='$modul-w' class='$modul' onclick='get2Click(this)'>";
									$inputC="<input type='checkbox' name='tsub[]' $checkC value='$modul-c' class='$modul' onclick='get2Click(this)'>";
									$inputX="<input type='checkbox' name='tsub[]' $checkX value='$modul-x' class='$modul' onclick='get2Click(this)'>";
								}
								$tr = "
								<tr>
									<td><input type='checkbox' id='$modul' $chekedModul name='tModul[]' value='$menu_id' selected onclick='getClick(this)'><span class='pl-2 pt-0'>$menu_alias</span></td>
									<td class='text-center'>$inputR</td>
									<td class='text-center'>$inputW</td>
									<td class='text-center'>$inputC</td>
									<td class='text-center'>$inputX</td>
								</tr> ";
								// $gettr[] = $checkMenu;
								$gettr[] = $tr;
							}
						}else{
							$tr = "
								<tr>
								<td><input type='checkbox' id='$modul' name='tModul[]'  value='$menu_id' selected onclick='getClick(this)'><span class='pl-2 pt-0'>$menu_alias</span></td>
								<td class='text-center'><input type='checkbox' name='tsub[]' value='$modul-r' class='$modul' onclick='get2Click(this)'></td>
								<td class='text-center'><input type='checkbox' name='tsub[]' value='$modul-w' class='$modul' onclick='get2Click(this)'></td>
								<td class='text-center'><input type='checkbox' name='tsub[]' value='$modul-c' class='$modul' onclick='get2Click(this)'></td>
								<td class='text-center'><input type='checkbox' name='tsub[]' value='$modul-x' class='$modul' onclick='get2Click(this)'></td>
								</tr> ";
							$gettr[] = $tr;
						}
						$msg['res_tr']  = $gettr;
					}
				}else{
					$resMenu	= $this->mAccess->readtable('app_menu', '',array('menu_id'=>$tMenu,'menu_access'=>1,'menu_deletion'=>'N'),'','','')->row_array();
					// if($this->level == 1){
					// }else{
					//   $resMenu	= $this->mAccess->getWhereNotInAndWhere('app_menu','','','menu_id',array(13,15,20),'','','')->row_array();
					// }
					// $resUser	= "";
					// $resUser	= $this->mAccess->getWhereNotInAndWhere('app_uaccess','','','access_menu_id',array(13,15,20),array('access_level_id'=> $tUser,'access_menu_id'=> $resMenu['menu_id'], 'access_office_id' => $tCompany),'','')->result();
					$resUser	= $this->mAccess->getTable('app_uaccess',array('access_level_id'=> $tUser,'access_menu_id'=> $resMenu['menu_id'], 'access_company_id' => $tCompany))->result();
					$modul    = strtolower(str_replace(' ','',$resMenu['menu_title']));
					$menu_id        = $resMenu['menu_id'];
					$menu_alias     = $resMenu['menu_alias'];
					$parent_active  = $resMenu['menu_parent_active'];
					if($resUser){
						foreach($resUser AS $u){
							$checkMenu 		    = explode(',',$u->access_submenu_id);
							$checkPermissions = explode(',',$u->access_permissions_id);
							if ($checkMenu):
								$chekedModul="checked='checked'";
							else:
								$chekedModul='';
							endif;
							// if (in_array($r->menu_id, $checkMenu)):
							//   $chekedModul="checked='checked'";
							// else:
							//   $chekedModul='';
							// endif;
							$permR  = strtolower(str_replace(' ','',$resMenu['menu_title'])).'-r';
							$permW  = strtolower(str_replace(' ','',$resMenu['menu_title'])).'-w';
							$permC  = strtolower(str_replace(' ','',$resMenu['menu_title'])).'-c';
							$permX  = strtolower(str_replace(' ','',$resMenu['menu_title'])).'-x';
							$checkR = in_array($permR, $checkPermissions)?"checked='checked'":'';
							$checkW = in_array($permW, $checkPermissions)?"checked='checked'":'';
							$checkC = in_array($permC, $checkPermissions)?"checked='checked'":'';
							$checkX = in_array($permX, $checkPermissions)?"checked='checked'":'';
							if($parent_active ==1){
								$inputR="<i class='fa fa-times text-danger'></i>";
								$inputW="<i class='fa fa-times text-danger'></i>";
								$inputC="<i class='fa fa-times text-danger'></i>";
								$inputX="<i class='fa fa-times text-danger'></i>";
							}else{
								$inputR="<input type='checkbox' name='tsub[]' $checkR value='$modul-r' class='$modul' onclick='get2Click(this)'>";
								$inputW="<input type='checkbox' name='tsub[]' $checkW value='$modul-w' class='$modul' onclick='get2Click(this)'>";
								$inputC="<input type='checkbox' name='tsub[]' $checkC value='$modul-c' class='$modul' onclick='get2Click(this)'>";
								$inputX="<input type='checkbox' name='tsub[]' $checkX value='$modul-x' class='$modul' onclick='get2Click(this)'>";
							}
							$tr = "
							<tr>
							<td><input type='checkbox' id='$modul' $chekedModul name='tModul[]' value='$menu_id' selected onclick='getClick(this)'><span class='pl-2 pt-0'>$menu_alias</span></td>
							<td class='text-center'>$inputR</td>
							<td class='text-center'>$inputW</td>
							<td class='text-center'>$inputC</td>
							<td class='text-center'>$inputX</td>
							</tr> ";
							// $gettr[] = $checkMenu;
							$gettr[] = $tr;
						}
					}else{
						$tr = "
							<tr>
								<td><input type='checkbox' id='$modul' name='tModul[]'  value='$menu_id' selected onclick='getClick(this)'><span class='pl-2 pt-0'>$menu_alias</span></td>
								<td class='text-center'><input type='checkbox' name='tsub[]' value='$modul-r' class='$modul' onclick='get2Click(this)'></td>
								<td class='text-center'><input type='checkbox' name='tsub[]' value='$modul-w' class='$modul' onclick='get2Click(this)'></td>
								<td class='text-center'><input type='checkbox' name='tsub[]' value='$modul-c' class='$modul' onclick='get2Click(this)'></td>
								<td class='text-center'><input type='checkbox' name='tsub[]' value='$modul-x' class='$modul' onclick='get2Click(this)'></td>
							</tr> ";
						$gettr[] = $tr;
					}
					$msg['res_tr']  = $gettr;
				}
			}else{
				if($this->level == 1){
					$resMenu  = $this->mAccess->readtable('app_menu', '',array('menu_parent_id'=>$tMenu,'menu_access'=>1,'menu_deletion'=>'N'),'','',array('menu_alias'=>'ASC'))->result();
				}else{
					$resMenu  = $this->mAccess->getWhereNotInAndWhere2('app_menu', '','','menu_id',array(13,15,20),array('menu_parent_id'=>$tMenu,'menu_access'=>1,'menu_deletion'=>'N'),array('menu_alias'=>'ASC'))->result();
				}
				if(count($resMenu)!=0){
					foreach($resMenu AS $r){
						$resUser	= $this->mAccess->getTable('app_uaccess',array('access_level_id'=> $tUser,'access_menu_id'=> $tMenu, 'access_company_id' => $tCompany))->result();
						$modul    = strtolower(str_replace(' ','',$r->menu_title));
						if($resUser){
							foreach($resUser AS $u){
								$checkMenu 		    = explode(',',$u->access_submenu_id);
								$checkPermissions = explode(',',$u->access_permissions_id);
								if (in_array($r->menu_id, $checkMenu)):
									$chekedModul="checked='checked'";
								else:
									$chekedModul='';
								endif;
								$permR  = strtolower(str_replace(' ','',$r->menu_title)).'-r';
								$permW  = strtolower(str_replace(' ','',$r->menu_title)).'-w';
								$permC  = strtolower(str_replace(' ','',$r->menu_title)).'-c';
								$permX  = strtolower(str_replace(' ','',$r->menu_title)).'-x';
								$checkR = in_array($permR, $checkPermissions)?"checked='checked'":'';
								$checkW = in_array($permW, $checkPermissions)?"checked='checked'":'';
								$checkC = in_array($permC, $checkPermissions)?"checked='checked'":'';
								$checkX = in_array($permX, $checkPermissions)?"checked='checked'":'';
								$tr = "
									<tr>
										<td><input type='checkbox' id='$modul' $chekedModul name='tModul[]' value='$r->menu_id' selected onclick='getClick(this)'><span class='pl-2 pt-0'>$r->menu_alias</span></td>
										<td class='text-center'><input type='checkbox' name='tsub[]' $checkR value='$modul-r' class='$modul' onclick='get2Click(this)'></td>
										<td class='text-center'><input type='checkbox' name='tsub[]' $checkW value='$modul-w' class='$modul' onclick='get2Click(this)'></td>
										<td class='text-center'><input type='checkbox' name='tsub[]' $checkC value='$modul-c' class='$modul' onclick='get2Click(this)'></td>
										<td class='text-center'><input type='checkbox' name='tsub[]' $checkX value='$modul-x' class='$modul' onclick='get2Click(this)'></td>
									</tr> ";
								$gettr[] = $tr;
							}
						}else{
							$tr = "
								<tr>
								<td><input type='checkbox' id='$modul' name='tModul[]'  value='$r->menu_id' selected onclick='getClick(this)'><span class='pl-2 pt-0'>$r->menu_alias</span></td>
								<td class='text-center'><input type='checkbox' name='tsub[]' value='$modul-r' class='$modul' onclick='get2Click(this)'></td>
								<td class='text-center'><input type='checkbox' name='tsub[]' value='$modul-w' class='$modul' onclick='get2Click(this)'></td>
								<td class='text-center'><input type='checkbox' name='tsub[]' value='$modul-c' class='$modul' onclick='get2Click(this)'></td>
								<td class='text-center'><input type='checkbox' name='tsub[]' value='$modul-x' class='$modul' onclick='get2Click(this)'></td>
								</tr> ";
							$gettr[] = $tr;
						}
					}
					$msg['res_tr']  = $gettr;
				}else{
					// $msg['status']  = true;
					$resMenu	= $this->mAccess->readtable('app_menu', '',array('menu_id'=>$tMenu,'menu_access'=>1,'menu_deletion'=>'N'),'','','')->row_array();
					$resUser	= $this->mAccess->getTable('app_uaccess',array('access_level_id'=> $tUser,'access_menu_id'=> $tMenu, 'access_company_id' => $tCompany))->result();
					$modul    = strtolower(str_replace(' ','',$resMenu['menu_title']));
					$menu_id        = $resMenu['menu_id'];
					$menu_alias     = $resMenu['menu_alias'];
					$parent_active  = $resMenu['menu_parent_active'];
					if($resUser){
						foreach($resUser AS $u){
							$checkMenu 		    = explode(',',$u->access_submenu_id);
							$checkPermissions = explode(',',$u->access_permissions_id);
							if ($checkMenu):
								$chekedModul="checked='checked'";
							else:
								$chekedModul='';
							endif;
							// if (in_array($r->menu_id, $checkMenu)):
							//   $chekedModul="checked='checked'";
							// else:
							//   $chekedModul='';
							// endif;
							$permR  = strtolower(str_replace(' ','',$resMenu['menu_title'])).'-r';
							$permW  = strtolower(str_replace(' ','',$resMenu['menu_title'])).'-w';
							$permC  = strtolower(str_replace(' ','',$resMenu['menu_title'])).'-c';
							$permX  = strtolower(str_replace(' ','',$resMenu['menu_title'])).'-x';
							$checkR = in_array($permR, $checkPermissions)?"checked='checked'":'';
							$checkW = in_array($permW, $checkPermissions)?"checked='checked'":'';
							$checkC = in_array($permC, $checkPermissions)?"checked='checked'":'';
							$checkX = in_array($permX, $checkPermissions)?"checked='checked'":'';
							if($parent_active ==1){
								$inputR="<i class='fa fa-times text-danger'></i>";
								$inputW="<i class='fa fa-times text-danger'></i>";
								$inputC="<i class='fa fa-times text-danger'></i>";
								$inputX="<i class='fa fa-times text-danger'></i>";
							}else{
								$inputR="<input type='checkbox' name='tsub[]' $checkR value='$modul-r' class='$modul' onclick='get2Click(this)'>";
								$inputW="<input type='checkbox' name='tsub[]' $checkW value='$modul-w' class='$modul' onclick='get2Click(this)'>";
								$inputC="<input type='checkbox' name='tsub[]' $checkC value='$modul-c' class='$modul' onclick='get2Click(this)'>";
								$inputX="<input type='checkbox' name='tsub[]' $checkX value='$modul-x' class='$modul' onclick='get2Click(this)'>";
							}
							$tr = "
							<tr>
							<td><input type='checkbox' id='$modul' $chekedModul name='tModul[]' value='$menu_id' selected onclick='getClick(this)'><span class='pl-2 pt-0'>$menu_alias</span></td>
							<td class='text-center'>$inputR</td>
							<td class='text-center'>$inputW</td>
							<td class='text-center'>$inputC</td>
							<td class='text-center'>$inputX</td>
							</tr> ";
							// $gettr[] = $checkMenu;
							$gettr[] = $tr;
						}
					}else{
						$tr = "
							<tr>
								<td><input type='checkbox' id='$modul' name='tModul[]'  value='$menu_id' selected onclick='getClick(this)'><span class='pl-2 pt-0'>$menu_alias</span></td>
								<td class='text-center'><input type='checkbox' name='tsub[]' value='$modul-r' class='$modul' onclick='get2Click(this)'></td>
								<td class='text-center'><input type='checkbox' name='tsub[]' value='$modul-w' class='$modul' onclick='get2Click(this)'></td>
								<td class='text-center'><input type='checkbox' name='tsub[]' value='$modul-c' class='$modul' onclick='get2Click(this)'></td>
								<td class='text-center'><input type='checkbox' name='tsub[]' value='$modul-x' class='$modul' onclick='get2Click(this)'></td>
							</tr> ";
						$gettr[] = $tr;
					}
					$msg['res_tr']  = $gettr;
				}
			}
		}else{
			$checkingParent = $this->mAccess->readtable('app_menu', '',array('menu_id'=>$tMenu, 'menu_parent_active' => 0,'menu_access'=>1,'menu_deletion'=>'N'),'','','')->num_rows();
			if($checkingParent > 0){
				// $msg['status']  = true;
				$resMenux	= $this->mAccess->readtable('app_menu', '',array('menu_id'=>$tMenu,'menu_access'=>1,'menu_deletion'=>'N'),'','','')->row_array();
				if($resMenux['menu_id'] == 195){
					if($this->level == 1){
						$resMenu  = $this->mAccess->readtable('app_menu', '',array('menu_parent_id'=>$tMenu,'menu_access'=>1,'menu_deletion'=>'N'),'','',array('menu_alias'=>'ASC'))->result();
					}else{
						$resMenu  = $this->mAccess->getWhereNotInAndWhere2('app_menu', '','','menu_id',array(13,15,20),array('menu_parent_id'=>$tMenu,'menu_access'=>1,'menu_deletion'=>'N'),array('menu_alias'=>'ASC'))->result();
					}
					if(count($resMenu)!=0){
						foreach($resMenu AS $r){
							$resUser	= $this->mAccess->getTable('app_uaccess',array('access_level_id'=> $tUser,'access_menu_id'=> $tMenu, 'access_company_id' => $this->office))->result();
							$modul    = strtolower(str_replace(' ','',$r->menu_title));
							if($resUser){
								foreach($resUser AS $u){
									$checkMenu 		    = explode(',',$u->access_submenu_id);
									$checkPermissions = explode(',',$u->access_permissions_id);
									if (in_array($r->menu_id, $checkMenu)):
										$chekedModul="checked='checked'";
									else:
										$chekedModul='';
									endif;
									$permR  = strtolower(str_replace(' ','',$r->menu_title)).'-r';
									$permW  = strtolower(str_replace(' ','',$r->menu_title)).'-w';
									$permC  = strtolower(str_replace(' ','',$r->menu_title)).'-c';
									$permX  = strtolower(str_replace(' ','',$r->menu_title)).'-x';
									$checkR = in_array($permR, $checkPermissions)?"checked='checked'":'';
									$checkW = in_array($permW, $checkPermissions)?"checked='checked'":'';
									$checkC = in_array($permC, $checkPermissions)?"checked='checked'":'';
									$checkX = in_array($permX, $checkPermissions)?"checked='checked'":'';
									$tr = "
										<tr>
										<td><input type='checkbox' id='$modul' $chekedModul name='tModul[]' value='$r->menu_id' selected onclick='getClick(this)'><span class='pl-2 pt-0'>$r->menu_alias</span></td>
										<td class='text-center'><input type='checkbox' name='tsub[]' $checkR value='$modul-r' class='$modul' onclick='get2Click(this)'></td>
										<td class='text-center'><input type='checkbox' name='tsub[]' $checkW value='$modul-w' class='$modul' onclick='get2Click(this)'></td>
										<td class='text-center'><input type='checkbox' name='tsub[]' $checkC value='$modul-c' class='$modul' onclick='get2Click(this)'></td>
										<td class='text-center'><input type='checkbox' name='tsub[]' $checkX value='$modul-x' class='$modul' onclick='get2Click(this)'></td>
										</tr> ";
									$gettr[] = $tr;
								}
							}else{
								$tr = "
									<tr>
										<td><input type='checkbox' id='$modul' name='tModul[]'  value='$r->menu_id' selected onclick='getClick(this)'><span class='pl-2 pt-0'>$r->menu_alias</span></td>
										<td class='text-center'><input type='checkbox' name='tsub[]' value='$modul-r' class='$modul' onclick='get2Click(this)'></td>
										<td class='text-center'><input type='checkbox' name='tsub[]' value='$modul-w' class='$modul' onclick='get2Click(this)'></td>
										<td class='text-center'><input type='checkbox' name='tsub[]' value='$modul-c' class='$modul' onclick='get2Click(this)'></td>
										<td class='text-center'><input type='checkbox' name='tsub[]' value='$modul-x' class='$modul' onclick='get2Click(this)'></td>
									</tr> ";
								$gettr[] = $tr;
							}
						}
						$msg['res_tr']  = $gettr;
					}else{
						// $msg['status']  = true;
						$resMenu	= $this->mAccess->readtable('app_menu', '',array('menu_id'=>$tMenu,'menu_access'=>1,'menu_deletion'=>'N'),'','','')->row_array();
						$resUser	= $this->mAccess->getTable('app_uaccess',array('access_level_id'=> $tUser,'access_menu_id'=> $tMenu, 'access_company_id' => $this->office))->result();
						$modul    = strtolower(str_replace(' ','',$resMenu['menu_title']));
						$menu_id        = $resMenu['menu_id'];
						$menu_alias     = $resMenu['menu_alias'];
						$parent_active  = $resMenu['menu_parent_active'];
						if($resUser){
							foreach($resUser AS $u){
								$checkMenu 		    = explode(',',$u->access_submenu_id);
								$checkPermissions = explode(',',$u->access_permissions_id);
								if ($checkMenu):
									$chekedModul="checked='checked'";
								else:
									$chekedModul='';
								endif;
								// if (in_array($r->menu_id, $checkMenu)):
								//   $chekedModul="checked='checked'";
								// else:
								//   $chekedModul='';
								// endif;
								$permR  = strtolower(str_replace(' ','',$resMenu['menu_title'])).'-r';
								$permW  = strtolower(str_replace(' ','',$resMenu['menu_title'])).'-w';
								$permC  = strtolower(str_replace(' ','',$resMenu['menu_title'])).'-c';
								$permX  = strtolower(str_replace(' ','',$resMenu['menu_title'])).'-x';
								$checkR = in_array($permR, $checkPermissions)?"checked='checked'":'';
								$checkW = in_array($permW, $checkPermissions)?"checked='checked'":'';
								$checkC = in_array($permC, $checkPermissions)?"checked='checked'":'';
								$checkX = in_array($permX, $checkPermissions)?"checked='checked'":'';
								if($parent_active ==1){
									$inputR="<i class='fa fa-times text-danger'></i>";
									$inputW="<i class='fa fa-times text-danger'></i>";
									$inputC="<i class='fa fa-times text-danger'></i>";
									$inputX="<i class='fa fa-times text-danger'></i>";
								}else{
									$inputR="<input type='checkbox' name='tsub[]' $checkR value='$modul-r' class='$modul' onclick='get2Click(this)'>";
									$inputW="<input type='checkbox' name='tsub[]' $checkW value='$modul-w' class='$modul' onclick='get2Click(this)'>";
									$inputC="<input type='checkbox' name='tsub[]' $checkC value='$modul-c' class='$modul' onclick='get2Click(this)'>";
									$inputX="<input type='checkbox' name='tsub[]' $checkX value='$modul-x' class='$modul' onclick='get2Click(this)'>";
								}
								$tr = "
								<tr>
									<td><input type='checkbox' id='$modul' $chekedModul name='tModul[]' value='$menu_id' selected onclick='getClick(this)'><span class='pl-2 pt-0'>$menu_alias</span></td>
									<td class='text-center'>$inputR</td>
									<td class='text-center'>$inputW</td>
									<td class='text-center'>$inputC</td>
									<td class='text-center'>$inputX</td>
								</tr> ";
								// $gettr[] = $checkMenu;
								$gettr[] = $tr;
							}
						}else{
							$tr = "
								<tr>
								<td><input type='checkbox' id='$modul' name='tModul[]'  value='$menu_id' selected onclick='getClick(this)'><span class='pl-2 pt-0'>$menu_alias</span></td>
								<td class='text-center'><input type='checkbox' name='tsub[]' value='$modul-r' class='$modul' onclick='get2Click(this)'></td>
								<td class='text-center'><input type='checkbox' name='tsub[]' value='$modul-w' class='$modul' onclick='get2Click(this)'></td>
								<td class='text-center'><input type='checkbox' name='tsub[]' value='$modul-c' class='$modul' onclick='get2Click(this)'></td>
								<td class='text-center'><input type='checkbox' name='tsub[]' value='$modul-x' class='$modul' onclick='get2Click(this)'></td>
								</tr> ";
							$gettr[] = $tr;
						}
						$msg['res_tr']  = $gettr;
					}
				}else{
					$resMenu	= $this->mAccess->readtable('app_menu', '',array('menu_id'=>$tMenu,'menu_access'=>1,'menu_deletion'=>'N'),'','','')->row_array();
					$resUser	= $this->mAccess->getTable('app_uaccess',array('access_level_id'=> $tUser,'access_menu_id'=> $tMenu, 'access_company_id' => $this->office))->result();
					$modul    = strtolower(str_replace(' ','',$resMenu['menu_title']));
					$menu_id        = $resMenu['menu_id'];
					$menu_alias     = $resMenu['menu_alias'];
					$parent_active  = $resMenu['menu_parent_active'];
					if($resUser){
						foreach($resUser AS $u){
							$checkMenu 		    = explode(',',$u->access_submenu_id);
							$checkPermissions = explode(',',$u->access_permissions_id);
							if ($checkMenu):
								$chekedModul="checked='checked'";
							else:
								$chekedModul='';
							endif;
							// if (in_array($r->menu_id, $checkMenu)):
							//   $chekedModul="checked='checked'";
							// else:
							//   $chekedModul='';
							// endif;
							$permR  = strtolower(str_replace(' ','',$resMenu['menu_title'])).'-r';
							$permW  = strtolower(str_replace(' ','',$resMenu['menu_title'])).'-w';
							$permC  = strtolower(str_replace(' ','',$resMenu['menu_title'])).'-c';
							$permX  = strtolower(str_replace(' ','',$resMenu['menu_title'])).'-x';
							$checkR = in_array($permR, $checkPermissions)?"checked='checked'":'';
							$checkW = in_array($permW, $checkPermissions)?"checked='checked'":'';
							$checkC = in_array($permC, $checkPermissions)?"checked='checked'":'';
							$checkX = in_array($permX, $checkPermissions)?"checked='checked'":'';
							if($parent_active ==1){
								$inputR="<i class='fa fa-times text-danger'></i>";
								$inputW="<i class='fa fa-times text-danger'></i>";
								$inputC="<i class='fa fa-times text-danger'></i>";
								$inputX="<i class='fa fa-times text-danger'></i>";
							}else{
								$inputR="<input type='checkbox' name='tsub[]' $checkR value='$modul-r' class='$modul' onclick='get2Click(this)'>";
								$inputW="<input type='checkbox' name='tsub[]' $checkW value='$modul-w' class='$modul' onclick='get2Click(this)'>";
								$inputC="<input type='checkbox' name='tsub[]' $checkC value='$modul-c' class='$modul' onclick='get2Click(this)'>";
								$inputX="<input type='checkbox' name='tsub[]' $checkX value='$modul-x' class='$modul' onclick='get2Click(this)'>";
							}
							$tr = "
							<tr>
							<td><input type='checkbox' id='$modul' $chekedModul name='tModul[]' value='$menu_id' selected onclick='getClick(this)'><span class='pl-2 pt-0'>$menu_alias</span></td>
							<td class='text-center'>$inputR</td>
							<td class='text-center'>$inputW</td>
							<td class='text-center'>$inputC</td>
							<td class='text-center'>$inputX</td>
							</tr> ";
							// $gettr[] = $checkMenu;
							$gettr[] = $tr;
						}
					}else{
						$tr = "
							<tr>
								<td><input type='checkbox' id='$modul' name='tModul[]'  value='$menu_id' selected onclick='getClick(this)'><span class='pl-2 pt-0'>$menu_alias</span></td>
								<td class='text-center'><input type='checkbox' name='tsub[]' value='$modul-r' class='$modul' onclick='get2Click(this)'></td>
								<td class='text-center'><input type='checkbox' name='tsub[]' value='$modul-w' class='$modul' onclick='get2Click(this)'></td>
								<td class='text-center'><input type='checkbox' name='tsub[]' value='$modul-c' class='$modul' onclick='get2Click(this)'></td>
								<td class='text-center'><input type='checkbox' name='tsub[]' value='$modul-x' class='$modul' onclick='get2Click(this)'></td>
							</tr> ";
						$gettr[] = $tr;
					}
					$msg['res_tr']  = $gettr;
				}
			}else{
				if($this->level == 1){
					$resMenu  = $this->mAccess->readtable('app_menu', '',array('menu_parent_id'=>$tMenu,'menu_access'=>1,'menu_deletion'=>'N'),'','',array('menu_alias'=>'ASC'))->result();
				}else{
					$resMenu  = $this->mAccess->getWhereNotInAndWhere2('app_menu', '','','menu_id',array(13,15,20),array('menu_parent_id'=>$tMenu,'menu_access'=>1,'menu_deletion'=>'N'),array('menu_alias'=>'ASC'))->result();
				}
				if(count($resMenu)!=0){
					foreach($resMenu AS $r){
						$resUser	= $this->mAccess->getTable('app_uaccess',array('access_level_id'=> $tUser,'access_menu_id'=> $tMenu, 'access_company_id' => $this->office))->result();
						$modul    = strtolower(str_replace(' ','',$r->menu_title));
						if($resUser){
							foreach($resUser AS $u){
								$checkMenu 		    = explode(',',$u->access_submenu_id);
								$checkPermissions = explode(',',$u->access_permissions_id);
								if (in_array($r->menu_id, $checkMenu)):
									$chekedModul="checked='checked'";
								else:
									$chekedModul='';
								endif;
								$permR  = strtolower(str_replace(' ','',$r->menu_title)).'-r';
								$permW  = strtolower(str_replace(' ','',$r->menu_title)).'-w';
								$permC  = strtolower(str_replace(' ','',$r->menu_title)).'-c';
								$permX  = strtolower(str_replace(' ','',$r->menu_title)).'-x';
								$checkR = in_array($permR, $checkPermissions)?"checked='checked'":'';
								$checkW = in_array($permW, $checkPermissions)?"checked='checked'":'';
								$checkC = in_array($permC, $checkPermissions)?"checked='checked'":'';
								$checkX = in_array($permX, $checkPermissions)?"checked='checked'":'';
								$tr = "
									<tr>
										<td><input type='checkbox' id='$modul' $chekedModul name='tModul[]' value='$r->menu_id' selected onclick='getClick(this)'><span class='pl-2 pt-0'>$r->menu_alias</span></td>
										<td class='text-center'><input type='checkbox' name='tsub[]' $checkR value='$modul-r' class='$modul' onclick='get2Click(this)'></td>
										<td class='text-center'><input type='checkbox' name='tsub[]' $checkW value='$modul-w' class='$modul' onclick='get2Click(this)'></td>
										<td class='text-center'><input type='checkbox' name='tsub[]' $checkC value='$modul-c' class='$modul' onclick='get2Click(this)'></td>
										<td class='text-center'><input type='checkbox' name='tsub[]' $checkX value='$modul-x' class='$modul' onclick='get2Click(this)'></td>
									</tr> ";
								$gettr[] = $tr;
							}
						}else{
							$tr = "
								<tr>
								<td><input type='checkbox' id='$modul' name='tModul[]'  value='$r->menu_id' selected onclick='getClick(this)'><span class='pl-2 pt-0'>$r->menu_alias</span></td>
								<td class='text-center'><input type='checkbox' name='tsub[]' value='$modul-r' class='$modul' onclick='get2Click(this)'></td>
								<td class='text-center'><input type='checkbox' name='tsub[]' value='$modul-w' class='$modul' onclick='get2Click(this)'></td>
								<td class='text-center'><input type='checkbox' name='tsub[]' value='$modul-c' class='$modul' onclick='get2Click(this)'></td>
								<td class='text-center'><input type='checkbox' name='tsub[]' value='$modul-x' class='$modul' onclick='get2Click(this)'></td>
								</tr> ";
							$gettr[] = $tr;
						}
					}
					$msg['res_tr']  = $gettr;
				}else{
					// $msg['status']  = true;
					$resMenu	= $this->mAccess->readtable('app_menu', '',array('menu_id'=>$tMenu,'menu_access'=>1,'menu_deletion'=>'N'),'','','')->row_array();
					$resUser	= $this->mAccess->getTable('app_uaccess',array('access_level_id'=> $tUser,'access_menu_id'=> $tMenu, 'access_company_id' => $this->office))->result();
					$modul    = strtolower(str_replace(' ','',$resMenu['menu_title']));
					$menu_id        = $resMenu['menu_id'];
					$menu_alias     = $resMenu['menu_alias'];
					$parent_active  = $resMenu['menu_parent_active'];
					if($resUser){
						foreach($resUser AS $u){
							$checkMenu 		    = explode(',',$u->access_submenu_id);
							$checkPermissions = explode(',',$u->access_permissions_id);
							if ($checkMenu):
								$chekedModul="checked='checked'";
							else:
								$chekedModul='';
							endif;
							// if (in_array($r->menu_id, $checkMenu)):
							//   $chekedModul="checked='checked'";
							// else:
							//   $chekedModul='';
							// endif;
							$permR  = strtolower(str_replace(' ','',$resMenu['menu_title'])).'-r';
							$permW  = strtolower(str_replace(' ','',$resMenu['menu_title'])).'-w';
							$permC  = strtolower(str_replace(' ','',$resMenu['menu_title'])).'-c';
							$permX  = strtolower(str_replace(' ','',$resMenu['menu_title'])).'-x';
							$checkR = in_array($permR, $checkPermissions)?"checked='checked'":'';
							$checkW = in_array($permW, $checkPermissions)?"checked='checked'":'';
							$checkC = in_array($permC, $checkPermissions)?"checked='checked'":'';
							$checkX = in_array($permX, $checkPermissions)?"checked='checked'":'';
							if($parent_active ==1){
								$inputR="<i class='fa fa-times text-danger'></i>";
								$inputW="<i class='fa fa-times text-danger'></i>";
								$inputC="<i class='fa fa-times text-danger'></i>";
								$inputX="<i class='fa fa-times text-danger'></i>";
							}else{
								$inputR="<input type='checkbox' name='tsub[]' $checkR value='$modul-r' class='$modul' onclick='get2Click(this)'>";
								$inputW="<input type='checkbox' name='tsub[]' $checkW value='$modul-w' class='$modul' onclick='get2Click(this)'>";
								$inputC="<input type='checkbox' name='tsub[]' $checkC value='$modul-c' class='$modul' onclick='get2Click(this)'>";
								$inputX="<input type='checkbox' name='tsub[]' $checkX value='$modul-x' class='$modul' onclick='get2Click(this)'>";
							}
							$tr = "
							<tr>
							<td><input type='checkbox' id='$modul' $chekedModul name='tModul[]' value='$menu_id' selected onclick='getClick(this)'><span class='pl-2 pt-0'>$menu_alias</span></td>
							<td class='text-center'>$inputR</td>
							<td class='text-center'>$inputW</td>
							<td class='text-center'>$inputC</td>
							<td class='text-center'>$inputX</td>
							</tr> ";
							// $gettr[] = $checkMenu;
							$gettr[] = $tr;
						}
					}else{
						$tr = "
							<tr>
								<td><input type='checkbox' id='$modul' name='tModul[]'  value='$menu_id' selected onclick='getClick(this)'><span class='pl-2 pt-0'>$menu_alias</span></td>
								<td class='text-center'><input type='checkbox' name='tsub[]' value='$modul-r' class='$modul' onclick='get2Click(this)'></td>
								<td class='text-center'><input type='checkbox' name='tsub[]' value='$modul-w' class='$modul' onclick='get2Click(this)'></td>
								<td class='text-center'><input type='checkbox' name='tsub[]' value='$modul-c' class='$modul' onclick='get2Click(this)'></td>
								<td class='text-center'><input type='checkbox' name='tsub[]' value='$modul-x' class='$modul' onclick='get2Click(this)'></td>
							</tr> ";
						$gettr[] = $tr;
					}
					$msg['res_tr']  = $gettr;
				}
			}
		}
		echo json_encode($msg);
	}
	public function fActAccessUser()
	{
		$this->form_validation->set_rules('tUname', '', 'trim|required');
		$this->form_validation->set_rules('tMenu', '', 'trim|required');
		if($this->level == 1){
			$this->form_validation->set_rules('tCompany', '', 'trim|required');
		}
		if ($this->form_validation->run() == false) {
			$msg['error']   = true;
			$msg['message'] = validation_errors();
			echo json_encode($msg);die;
		}
		$tMenu      = $this->input->post('tMenu', TRUE);
		$tCompany  	= $this->input->post('tCompany', TRUE);
		$tSubmenu   = $this->input->post('tSubmenu', TRUE);
		$tUname     = $this->input->post('tUname', TRUE);
		$urutParent = 99;
		$urutSub    = 99;
		$tModul     = ""; 
		$modul 	    = $this->input->post('tModul');
		if(!empty($modul)){
			foreach($modul as $user_modul){
				$tModul.=trim($user_modul).",";
			}
			$tModul = substr($tModul,0,strlen($tModul)-1); 
		}
		$tsub       =""; 
		$permission = $this->input->post('tsub');
		if(!empty($permission)){
			foreach($permission as $user_permission){
				$tsub.=trim($user_permission).",";
			}
			$tsub = substr($tsub,0,strlen($tsub)-1);  
		}
		if(!empty($tMenu)){
			$iUrut      = $this->mAccess->getTable('app_menu',array('menu_id' => $tMenu))->row_array();
			$urutParent = !empty($iUrut)?$iUrut['menu_urutan']:'99';
		}else{
			$iUrut      = $this->mAccess->getTable('app_menu',array('menu_id' => $tSubmenu))->row_array();
			$urutSub    = !empty($iUrut)?$iUrut['menu_urutan']:'99';
		}
		if(!empty($tCompany)){
			$field1 = array(
				'access_level_id'     	=> $tUname,
				'access_company_id'   	=> $tCompany,
				'access_menu_id'      	=> $tMenu,
				'access_menu_urutan'  	=> $urutParent,
				'access_submenu_id'   	=> !empty($tModul)?$tModul:null,
				'access_rolemenu'   	=> 1,
				'access_permissions_id'	=> !empty($tsub)?$tsub:null,
				'access_ip_address'   	=> null,
			);
			$field2 = array(
				'access_level_id'     	=> $tUname,
				'access_company_id'   	=> $tCompany,
				'access_menu_id'      	=> $tSubmenu==null?$tModul:$tSubmenu,
				'access_menu_urutan'   	=> $urutSub,
				'access_submenu_id'   	=> !empty($tModul)?$tModul:null,
				'access_rolemenu'      	=> 2,
				'access_permissions_id'	=> !empty($tsub)?$tsub:null,
				'access_ip_address'   	=> null,
			);
			if(!empty($tMenu)){
				$cekCount  = $this->mAccess->getTable('app_uaccess',array('access_menu_id' => $tMenu,'access_level_id' => $tUname , 'access_company_id' => $tCompany))->num_rows();
				if($cekCount>0){
					$cekOnline = $this->M_auth->onlineUpdateAll($this->username);
					if($cekOnline == false){
						$msg['text']      = 'Error update username online to offline';
						$msg['success']   = false;
						echo json_encode($msg);die;
					}
					$cek          = $this->mAccess->updateData(array('access_menu_id' => $tMenu,'access_level_id' => $tUname, 'access_company_id' => $tCompany), $field1, 'app_uaccess');
					$msg['type']  = 'update';
				}else{
					$cekOnline = $this->M_auth->onlineUpdateAll($this->username);
					if($cekOnline == false){
						$msg['text']      = 'Error update username online to offline';
						$msg['success']   = false;
						echo json_encode($msg);die;
					}
					$cek          = $this->mAccess->insertData($field1, 'app_uaccess');
					$msg['type']  = 'add';
				}
			}else{
				$cekCount  = $this->mAccess->getTable('app_uaccess',array('access_menu_id' => $tSubmenu,'access_level_id' => $tUname, 'access_company_id' => $tCompany))->num_rows();
				if($cekCount>0){
					$cekOnline = $this->M_auth->onlineUpdateAll($this->username);
					if($cekOnline == false){
						$msg['text']      = 'Error update username online to offline';
						$msg['success']   = false;
						echo json_encode($msg);die;
					}
					$cek          = $this->mAccess->updateData(array('access_menu_id' => $tSubmenu,'access_level_id' => $tUname, 'access_company_id' => $tCompany), $field2, 'app_uaccess');
					$msg['type']  = 'update';
				}else{
					$cekOnline = $this->M_auth->onlineUpdateAll($this->username);
					if($cekOnline == false){
						$msg['text']      = 'Error update username online to offline';
						$msg['success']   = false;
						echo json_encode($msg);die;
					}
					$cek          = $this->mAccess->insertData($field2, 'app_uaccess');
					$msg['type']  = 'add';
				}
			}
		}else{
			$field1 = array(
				'access_level_id'     	=> $tUname,
				'access_company_id'    	=> $this->office,
				'access_menu_id'      	=> $tMenu,
				'access_menu_urutan'  	=> $urutParent,
				'access_submenu_id'    	=> !empty($tModul)?$tModul:null,
				'access_rolemenu'      	=> 1,
				'access_permissions_id'	=> !empty($tsub)?$tsub:null,
				'access_ip_address'   	=> null,
			);
			$field2 = array(
				'access_level_id'      	=> $tUname,
				'access_company_id'   	=> $this->office,
				'access_menu_id'      	=> $tSubmenu==null?$tModul:$tSubmenu,
				'access_menu_urutan'   	=> $urutSub,
				'access_submenu_id'    	=> !empty($tModul)?$tModul:null,
				'access_rolemenu'      	=> 2,
				'access_permissions_id'	=> !empty($tsub)?$tsub:null,
				'access_ip_address'    	=> null,
			);
			if(!empty($tMenu)){
				$cekCount  = $this->mAccess->getTable('app_uaccess',array('access_menu_id' => $tMenu,'access_level_id' => $tUname , 'access_company_id' => $this->office))->num_rows();
				if($cekCount>0){
					$cek          = $this->mAccess->updateData(array('access_menu_id' => $tMenu,'access_level_id' => $tUname, 'access_company_id' => $this->office), $field1, 'app_uaccess');
					$msg['type']  = 'update';
				}else{
					$cek          = $this->mAccess->insertData($field1, 'app_uaccess');
					$msg['type']  = 'add';
				}
			}else{
				$cekCount  = $this->mAccess->getTable('app_uaccess',array('access_menu_id' => $tSubmenu,'access_level_id' => $tUname, 'access_company_id' => $this->office))->num_rows();
				if($cekCount>0){
					$cek          = $this->mAccess->updateData(array('access_menu_id' => $tSubmenu,'access_level_id' => $tUname, 'access_company_id' => $this->office), $field2, 'app_uaccess');
					$msg['type']  = 'update';
				}else{
					$cek          = $this->mAccess->insertData($field2, 'app_uaccess');
					$msg['type']  = 'add';
				}
			}
		}
		if($cek==true){
			$msg['success'] = true;
		}else{
			var_dump($cek);die;
			$msg['status']  = true;
		}
		echo json_encode($msg);die;
	}
	public function editAccessUser()
	{
		$id     = $this->input->get('id');
		$result = $this->mAccess->getFromDatabase($id,'app_uaccess','access_id');
		echo json_encode($result);
	}
	public function updateUser($id="")
	{
		$tNama      = $this->input->post('tNama', TRUE);
		$tUsername  = $this->input->post('tUsername', TRUE);
		$tEmail     = $this->input->post('tEmail', TRUE);
		$tStatus    = $this->input->post('tStatus', TRUE);
		$tCompany   = $this->input->post('tCompany', TRUE);
		$tHub       = $this->input->post('tHub', TRUE);
		$this->form_validation->set_rules('tNama', '', 'trim|required');
		$this->form_validation->set_rules('tUsername', 'Username Login', 'trim|required');
		$this->form_validation->set_rules('tCompany', 'Company User', 'trim|required');
		$this->form_validation->set_rules('tHub', 'Group User', 'trim|required');
		$this->form_validation->set_rules('tStatus', 'Status User', 'trim|required');
		if ($this->form_validation->run() == false) { 
			$msg['error']   = true;
			$msg['message'] = validation_errors();
			echo json_encode($msg);die;
		}
		$where  = array(
			'user_id' => $id
		);
		$field1 = array(
			'user_fullname'   => $tNama,
			'user_name'       => $tUsername,
			'user_email'      => $tEmail,
			'user_active'     => $tStatus,
			'user_company'    => $tCompany,
			'user_hub'        => $tHub
		);
		$cekOnline = $this->M_auth->onlineUpdateAll($this->username);
		if($cekOnline == false){
			$msg['text']      = 'Error update username online to offline';
			$msg['success']   = false;
			echo json_encode($msg);die;
		}
		$cek  = $this->mAccess->updateData($where, $field1, 'app_uaccess');
		if($cek){
			$msg['type']    = 'update';
			$msg['success'] = true;
			echo json_encode($msg);die;
		}else{
			$msg['status']  = true;
			echo json_encode($msg);die;
		}
	}
	public function deleteAccessUser()
	{
		// $cekU           = $this->mLevel->cekUserLevel();
		// $getA           = $this->mLevel->getAccessLevel();
		// $cekA           = $this->mLevel->cekAccessLevel();
		// $getU           = $this->mLevel->getUserLevel();
		// $a = "";
		// $b = "";
		// foreach($getU as $g){
		//   $a.=trim($g->user_fullname).", ";
		// }
		// $a = substr($a,0,strlen($a)-1);
		// foreach($getA as $ga){
		//   $b.=trim($ga->menu_alias).", ";
		// }
		// $b = substr($b,0,strlen($b)-1);
		$cekOnline = $this->M_auth->onlineUpdateAll($this->username);
		if($cekOnline == false){
			$msg['text']      = 'Error update username online to offline';
			$msg['success']   = false;
			echo json_encode($msg);die;
		}
		$result           = $this->mUser->deleteAccessUser();
		if ($result) {
			$msg['success'] = true;
			echo json_encode($msg);die;
		}else{
			$msg['success'] = false;
			echo json_encode($msg);die;
		}
	}
}
/* End of file user.php */
/* Location: ./application/controllers/user.php */
