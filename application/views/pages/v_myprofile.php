<!--Modal change status-->
<div id="xPassword" data-backdrop="static" data-keyboard="false" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header pt-2 pb-2 bg-blue-700">
				<h4 class="modal-title"></h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<?php //var_dump($origin_office); ?>
			<form action="" id="cPassword" class="form-horizontal f-validasi" method="post">
				<div class="modal-body">
					<div class="form-group row mb-1">
						<label class="col-form-label col-sm-4 text-dark">New Password<sup><b class="text-danger">*</b></sup></label>
						<div class="col-md-6">
							<!-- <select name="tUname" data-placeholder="Select a Level" class="form-control form-control-sm" required> -->
							<?= csrf_input(); ?>
							<!-- <input type="password" id="tPass1" name="tPass1" placeholder="New Password" class="form-control form-control-md" required style="padding-left: 0.8rem !important;">
							<div class="form-control-feedbackx" onclick="myFunction()" style="right: 0.5rem !important;">
								<i id="icon" class="icon-eye"></i>
							</div> -->
                            <div class="input-group mb-3">
                                <input id="tPass1" name="tPass1" type="password" class="form-control" placeholder="Password">
                                <div class="input-group-append" onclick="myChangePass()">
                                    <div class="input-group-text">
                                        <span id="icon" class="icon-eye"></span>
                                    </div>
                                </div>
                            </div>
						</div>
					</div>
					<div class="form-group row mb-1">
						<label class="col-form-label col-sm-4 text-dark">Repeat Password<sup><b class="text-danger">*</b></sup></label>
						<div class="col-md-6">
							<!-- <select name="tUname" data-placeholder="Select a Level" class="form-control form-control-sm" required> -->
							<!-- <input type="password" id="tPass2" name="tPass2" placeholder="Repeat Password" class="form-control form-control-md" required style="padding-left: 0.8rem !important;">
							<div class="form-control-feedbackx" onclick="myFunction2()" style="right: 0.5rem !important;">
								<i id="icon2" class="icon-eye"></i>
							</div> -->
                            <div class="input-group mb-3">
                                <input id="tPass2" name="tPass2" type="password" class="form-control" placeholder="Password">
                                <div class="input-group-append" onclick="myChangePass2()">
                                    <div class="input-group-text">
                                        <span id="icon2" class="icon-eye"></span>
                                    </div>
                                </div>
                            </div>
						</div>
					</div>
				</div>
			    <div class="card-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <button type="button" data-dismiss="modal" class="btn btn-outline-secondary">Close</button>
                        <button type="submit" class="btn btn-danger btn-ladda btn-ladda-spinner tBtn" data-spinner-color="#333" data-style="slide-down">changes Office</button>
                    </div>
				</div>
			</form>
		</div>
	</div>
</div>
<!--End Modal change status-->
<div class="panel panel-blue">
	<div class="panel-heading">My Profile
        <a href="<?= base_url('home') ?>" class="pull-right text-white small" title="Back" data-placement="right" data-popup="tooltip"><i class="icon-undo2"></i></a>
  	</div>
	<div class="panel_body">
        <?php //var_dump('<pre>'); ?>
        <?php //var_dump($profile); ?>
        <div class="container bg-white p-3">
			<?php if($this->agent->is_mobile() == TRUE){?>
			<div class="row justify-content-center" style="border: 1px solid #4e4e4e;">
				<div class="col-sm-6 p-0 d-flex justify-content-center">
                    <img src="<?= $profile['photo'] == "default.png" || $profile['photo'] == ""?site_url('assets/images/RIDER.png'):$profile['photo']; ?>" class="rounded mr-2 bg-indigo" width="200" alt="">
                </div>
				<div class="col-sm-6 p-2">
                    <table border="0" style="font-size: 12px;margin-left: auto;margin-right: auto;">
                        <tr>
                            <td width="35%"><b>Name</b></td>
                            <td width="3%">:</td>
                            <td><?= $this->session->JTusername; ?></td>
                        </tr>
						<tr>
							<td width="20%"><b>Email</b></td>
							<td width="3%">:</td>
							<td><?= $profile['email_id']; ?></td>
						</tr>
						<tr>
                            <td width="20%"><b>Office</b></td>
                            <td width="3%">:</td>
                            <td><?= $profile['office_name']; ?></td>
                        </tr>
						<tr>
                            <td width="20%"><b>Hub</b></td>
                            <td width="3%">:</td>
                            <td><?= $profile['hub_name']; ?></td>
                        </tr>
						<tr>
                            <td width="20%"><b>Level</b></td>
                            <td width="3%">:</td>
                            <td><?= $profile['level_name']; ?></td>
                        </tr>
						<tr>
                            <td width="20%"><b>Mobile Phone</b></td>
                            <td width="3%">:</td>
                            <td><?= $profile['mobile_phone']; ?></td>
                        </tr>
                        <tr>
                            <td width="20%"><b>Address</b></td>
                            <td width="3%">:</td>
                            <td><?= $profile['address']; ?></td>
                        </tr>
                        <tr>
                            <td width="20%"><b>Since</b></td>
                            <td width="3%">:</td>
                            <td><?= date('d-F-Y',strtotime($profile['created_on'])); ?></td>
                        </tr>
						<tr>
                            <td colspan="3"><button class="btn btn-outline-info bChange">Change Password</button></td>
                        </tr>
					</table>
                </div>
			</div>
			<?php }else{ ?>
            <div class="row justify-content-between" style="border: 1px solid #4e4e4e;">
                <div class="col-sm-3 d-flex justify-content-center p-0">
                    <img src="<?= $profile['photo'] == "default.png" || $profile['photo'] == ""?site_url('assets/images/RIDER.png'):$profile['photo']; ?>" class="rounded mr-2 bg-indigo" width="200" alt="">
                </div>
                <div class="col-sm-4 d-flex justify-content-center p-2">
                    <table border="0" style="font-size: 18px;">
                        <tr>
                            <td width="20%"><b>Name</b></td>
                            <td width="3%">:</td>
                            <td><?= $this->session->JTusername; ?></td>
                        </tr>
                        <tr>
                            <td width="20%"><b>Email</b></td>
                            <td width="3%">:</td>
                            <td><?= $profile['email_id']; ?></td>
                        </tr>
                        <tr>
                            <td width="20%"><b>Office</b></td>
                            <td width="3%">:</td>
                            <td><?= $profile['office_name']; ?></td>
                        </tr>
                        <tr>
                            <td width="20%"><b>Hub</b></td>
                            <td width="3%">:</td>
                            <td><?= $profile['hub_name']; ?></td>
                        </tr>
                        <tr>
                            <td colspan="3"></td>
                        </tr>
                    </table>
                </div>
                <div class="col-sm-4 d-flex justify-content-center p-2">
                    <table border="0" style="font-size: 18px;">
                        <tr>
                            <td width="20%"><b>Level</b></td>
                            <td width="3%">:</td>
                            <td><?= $profile['level_name']; ?></td>
                        </tr>
                        <tr>
                            <td width="20%"><b>Mobile Phone</b></td>
                            <td width="3%">:</td>
                            <td><?= $profile['mobile_phone']; ?></td>
                        </tr>
                        <tr>
                            <td width="20%"><b>Address</b></td>
                            <td width="3%">:</td>
                            <td><?= $profile['address']; ?></td>
                        </tr>
                        <tr>
                            <td width="20%"><b>Since</b></td>
                            <td width="3%">:</td>
                            <td><?= date('d-F-Y',strtotime($profile['created_on'])); ?></td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="row justify-content-between">
                <div class="col-sm-12 d-flex justify-content-center p-2">
                    <table border="0" style="font-size: 18px;">
                        <tr>
                            <td><button class="btn btn-outline-info bChange">Change Password</button></td>
                        </tr>
                    </table>
                </div>
            </div>
			<?php } ?>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $('.bChange').click(function(){
            $('#xPassword').find('.modal-title').text('Change Password');
            $('#xPassword').find('.tBtn').text('Confirm ?');
            $('#cPassword').attr('action', '<?= $link; ?>/changePassword');
            $('#cPassword')[0].reset();
            $('#xPassword').modal('show');
        });
        $('#cPassword').submit(function(e) {
			e.preventDefault();
			var url = $('#cPassword').attr('action');
			var data = $('#cPassword').serialize();
			$.ajax({
				type: 'ajax',
				method: 'post',
				url: url,
				data: data,
				async: true,
				dataType: 'json',
				success: function(response) {
					console.log(response);
					if (response.error) {
						// $('#mForm').modal('hide');
						// notiferror_a('Error Update User To Database1');
					}else if(response.status){
						$('#xPassword').modal('hide');
						notiferror_a(response.text);
					}else{
            			$('#xPassword').modal('hide');
						// notifsukses(response.text);
						Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            html: response.text,
                            allowOutsideClick: false
                        }).then((result) => {
                            /* Read more about isConfirmed, isDenied below */
                            if (result.isConfirmed) {
                                location.reload(1);
                            }
                        });
						// setTimeout(function () { location.reload(1); }, 1000);
						$("#cPassword").data('validator').resetForm();
						$('#cPassword')[0].reset();
					}
				},
				error: function(error) {
					console.log(error.responseText);
					$('#xPassword').modal('hide');
          			// notiferror('KNP ERROR?');
          			notiferror('Network failure,<br> Please try again later.');
				}
			});
		});
    });
    function myChangePass() 
    {
        var x = document.getElementById("tPass1");
        if (x.type === "password") { x.type = "text"; } 
        else { x.type = "password"; }
		var y = document.getElementById("icon");
		if(x.type === "password")
		{
            // console.log(y.classList);
			y.classList.remove("icon-eye-blocked");
			y.classList.add("icon-eye");
		}else{
			y.classList.remove("icon-eye");
			y.classList.add("icon-eye-blocked");
		}
    }
    function myChangePass2() 
    {
        var x = document.getElementById("tPass2");
        if (x.type === "password") { x.type = "text"; } 
        else { x.type = "password"; }
		var y = document.getElementById("icon2");
		if(x.type === "password")
		{
            // console.log(y.classList);
			y.classList.remove("icon-eye-blocked");
			y.classList.add("icon-eye");
		}else{
			y.classList.remove("icon-eye");
			y.classList.add("icon-eye-blocked");
		}
    }
</script>