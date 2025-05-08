<!--Modal Responsive-->
<div id="mForm" data-backdrop="static" data-keyboard="false" class="modal fade">
	<div class="modal-dialog modal-lg" style="max-width: 700px;">
		<div class="modal-content">
			<div class="modal-header pt-2 pb-2 bg-blue-700">
				<h4 class="modal-title"></h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<form action="" id="myForm" class="form-horizontal form-validasi" method="post">
				<div class="modal-body">
					<h5><span style="display: none;" id="tMessage" class="badge badge-flat border-danger text-danger-600 mb-1">Block badge</span></h5>
					<div class="form-group row mb-1">
						<label class="col-form-label col-sm-3">LEVEL ACCESS COMPANY<b class="text-danger">*</b></label>
						<div class="col-md-6">
							<!-- <select name="tUname" data-placeholder="Select a Level" class="form-control form-control-sm" required> -->
							<select name="tUname" data-placeholder="Select a Level" class="form-control form-control-sm select-search sel2"  data-container-css-class="select-sm" data-fouc  required>
								<option value=""></option>
								<?php foreach ($user as $l) { ?>
                				<option value="<?= $this->secure->enc($l->level_id); ?>" data-username="<?= $l->level_name; ?>"><?= $l->level_name; ?></option>
								<?php } ?>
							</select>
						</div>
                        <div class="col-md-3">
							<button type="button" class="btn btn-primary btn-ladda btn-ladda-spinner btn-sm pt-1 bGetdata" data-spinner-color="#333" data-style="slide-down">
								<i class="icon-search4"></i> <span class="ladda-label">Show Data</span>
							</button>
						</div>
					</div>
                    <div class="row">
                        <div class="col-sm-11">
                            <div class="table-responsive pre-scrollable " id="tGetTable" style="max-height:300px;display: none;">
                                <table id="example1" class="table table-striped table-bordered table-hover table-sm1">
                                    <thead>
                                        <tr>
                                            <th class="text-center">COMPANY</th>
                                            <th class="text-center">PERMISSION ONLY</th>
                                        </tr>
                                    </thead>
                                    <tbody id="result">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
				</div>
				<div class="card-footer" id="bHidden">
                    <div class="d-flex justify-content-between align-items-center">
                        <button type="button" data-dismiss="modal" class="btn btn-outline-secondary">Close</button>
                        <button type="submit" class="btn btn-primary btn-ladda btn-ladda-spinner" data-spinner-color="#333" data-style="slide-down">Save changes</button>
                    </div>
				</div>
			</form>
		</div>
	</div>
</div>
<div class="panel panel-blue card">
	<div class="panel-heading">Daftar Access User
		<?=$write; ?>
  	</div>
	<div class="panel_body">
		<div class="table-responsive">
			<table id="example2" class="table table-striped table-bordered table-hover table-sm1">
				<thead>
					<tr>
						<th class="text-center">#</th>
						<th class="text-center">LEVEL NAME</th>
						<th class="text-center">COMPANY</th>
						<th class="text-center">Aksi</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
	</div>
</div>
<!--loading bootstrap js-->
<script type="text/javascript">
	var notif, submit;
	async function getDataOffice(){
		var data = $('#myForm').serialize();
		// console.log(data);
		// return;
		try {
			submit = await submitForm('post', '<?= $link; ?>/getAccessCompany', data);
			if(submit.error){
				document.getElementById("tMessage").style.display = "";
				// $('#tFeedbackUname').html('Error This Field Is Required');
				$('#tMessage').html('Failed!! <br>'+submit.message);
			}else if(submit.status){
				document.getElementById("tMessage").style.display = "";
				$('#tMessage').html('Data Is Not Found');
				$('#result').html('');
				document.getElementById("bHidden").style.display = "none";
				document.getElementById("tGetTable").style.display = "none";
				// document.getElementById("tGetTable2").style.display = "none";
			}else{
				document.getElementById("bHidden").style.display = "";
				document.getElementById("tGetTable").style.display = "";
				// document.getElementById("tGetTable2").style.display = "";
				document.getElementById("tMessage").style.display = "none";
				$('#result').html(submit.res_tr);
				// $('#result2').html(submit.res_tr2);
			}
		} catch (error) {
			console.log(error);
			notiferror('Error Get Modul To Database');
		}
	}
	$(document).ready(function() {
		<?php if($lock==1): ?>
        function formatText (username) {
            function user(){
                if($(username.element).data('username') == undefined){
                    return "";
                }else{
                    if($(username.element).data('username') == ""){
                        return "";
                    }else{
                        return "("+$(username.element).data('username')+")";
                    }
                }
            }
            return $('<span>' + username.text + ' <span class="float-right top-top">' + user() + '</span></span>');
        };
		$('#btnAdd').click(function() {
			$('#mForm').modal('show');
			$('#mForm').find('.modal-title').text('Add New Daftar Access Office');
            $('#myForm').attr('action', '<?= $link; ?>/fActAccessOffice');
			$("#myForm").data('validator').resetForm();
			$('#myForm')[0].reset();
			$('#btnAdd').tooltip('hide');
			$(".sel2").select2({
                templateSelection: formatText,
				templateResult: formatText
            }).val('').trigger('change.select2');
            $(".tOffice").select2().val('').trigger('change.select2');
			document.getElementById("tGetTable").style.display = "none";
			// document.getElementById("tGetTable2").style.display = "none";
			document.getElementById("bHidden").style.display = "none";
			document.getElementById("tMessage").style.display = "none";
		});
        $('.sel2').on('change',function() {
            $(".tOffice").select2().val('').trigger('change.select2');
            document.getElementById("tGetTable").style.display = "none";
            // document.getElementById("tGetTable2").style.display = "none";
            document.getElementById("bHidden").style.display = "none";
            document.getElementById("tMessage").style.display = "none";
        });
        $('.tOffice').on('change',function() {
            document.getElementById("tGetTable").style.display = "none";
            // document.getElementById("tGetTable2").style.display = "none";
            document.getElementById("bHidden").style.display = "none";
            document.getElementById("tMessage").style.display = "none";
        });
        $('.bGetdata').click(function() {
            getDataOffice();
        });
        <?php endif; ?>
		$('#myForm').submit(function(e) {
			e.preventDefault();
			var url = $('#myForm').attr('action');
			var data = $('#myForm').serialize();
			$.ajax({
				type: 'ajax',
				method: 'post',
				url: url,
				data: data,
				async: true,
				dataType: 'json',
				success: function(response) {
					// console.log(response);
					if (response.error) {
					}else if(response.status){
            			$('#mForm').modal('hide');
						if(response.text){
							notiferror(response.text);
						}else{
							notiferror_a('Error Update Access Office To Database');
						}
					}else{
            			$('#mForm').modal('hide');
						if (response.type == 'add') {
							var type = 'Added'
							// console.log(response.message);
						} else if (response.type == 'update') {
							var type = "Updated"
            			}
						notifsukses('Data Access Office ' + type + ' Successfully');
						dataTable.ajax.reload();
						// setTimeout(function () { location.reload(1); }, 2000);
						$("#myForm").data('validator').resetForm();
						$('#myForm')[0].reset();
					}
				},
                error: function(xhr, error, code) {
					console.log(xhr);
					console.log(error);
					console.log(code);
					$('#mForm').modal('hide');
          			notiferror('Error Update To Database');
				}
			});
		});
		<?php if(!empty($this->security_function->permissions($filename . "-c"))): ?>
		$('#example2').on('click', '.bEdit',async function() {
			var id = $(this).attr('id');
			$('.bEdit').tooltip('hide');
			try {
				submit = await submitForm('get', '<?= $link; ?>/editAccessOffice', {id: id});
				if(submit===false){
					errordatabase();
				}else{
					$('#mForm').find('.modal-title').text('Edit Daftar Access Office');
					$('#myForm').attr('action', '<?= $link; ?>/fActAccessOffice');
					$(".sel2").select2({
						templateSelection: formatText,
						templateResult: formatText
					}).val(submit.access_level_id_en).trigger('change.select2');
					document.getElementById("bHidden").style.display = "";
					document.getElementById("tGetTable").style.display = "";
					document.getElementById("tMessage").style.display = "none";
					getDataOffice();
					$('#mForm').modal('show');
				}
			} catch (error) {
				console.log(error);
				$('#mForm').modal('hide');
				notiferror('Error Update To Database');
			}
		});
	<?php endif; ?>
	<?php if(!empty($this->security_function->permissions($filename . "-x"))): ?>
		$('#example2').on('click', '.bDelete', function() {
			var id    = $(this).attr('id');
			var Name = $(this).attr('data');
      		$('#deleteModal').modal('show');
			$('#deleteModal').find('.infoDelete').text('Menu : '+Name);
			$('#btnDelete').unbind().click(function() {
				$.ajax({
					type: 'ajax',
					method: 'get',
					async: true,
					url: '<?= $link; ?>/deleteAccessOffice',
					data: {
						id: id
					},
					dataType: 'json',
					success: function(response) {
						$('#deleteModal').modal('hide');
						if(response.success){
							notifsukses('Access Level <strong>'+Name+'</strong> Delete Successfully');
							dataTable.ajax.reload();
							// setTimeout(function () { location.reload(1); }, 2000);
						}else{
							if(response.text){
								notiferror(response.text);
							}else{
								console.log(response);
								console.log(response.text);
								notiferror('Error Delete Access User To Database');
							}
						}
					},
					error: function(error) {
						console.log(error);
						$('#deleteModal').modal('hide');
						notiferror('Error Delete To Database');
					}
				});
			});
		});
	<?php endif; ?>
		// var dataTable = $('#example2').DataTable({ 
		// 	"processing": true, 
		// 	"serverSide": true, 
		// 	"scrollX"	: true,
		// 	"responsive": false,
		// 	"order": [],
		// 	"ajax": {
		// 		"url": "<?= $link; ?>/showAllAccessLevel",
		// 		"type": "POST"
		// 	},
		// 	"columnDefs": [{ 
		// 		"className": "text-center",
		// 		"targets": [5],
		// 		"orderable": false 
		// 	},],
		// });
		// CONTOH MODEL LAIN DATATABLE SERVERSIDE
		var dataTable = $('#example2').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax":{
				"url": "<?= $link; ?>/get_ajax",
				"dataType": "json",
				"type": "POST",
				"error": function(error) {
					console.log(error);
				},
		    },
			"columnDefs": [
                {"className": "text-center","targets": [0,-1],"orderable": false, }	 
            ],
            "fnDrawCallback": function( oSettings ) {
                $('.bEdit').each(function () {
                    $(this).tooltip({
                        html: true
                    });
                });
                $('.bDelete').each(function () {
                    $(this).tooltip({
                        html: true
                    });
                });
            }
	    });
	});
	function getClick(index) {
		var checkboxes = document.getElementsByClassName($(index).attr('id'));
		if (index.checked) {
			for (var i = 0; i < checkboxes.length; i++) {
				if (checkboxes[i].type == 'checkbox' ) {
					// if(!$(checkboxes).attr('disabled')){
					// 	checkboxes[i].checked = true;
					// }
					checkboxes[i].checked = true;
				}
			}
		}else{
			for (var i = 0; i < checkboxes.length; i++) {
				if (checkboxes[i].type == 'checkbox') {
				checkboxes[i].checked = false;
				}
			}
		}
	}
	function get2Click(index) {
		// var checkboxes = document.getElementsById($(index).attr('class'));
		let checkboxes2 = $('#'+$(index).attr('class')).attr('id');
		// let checkboxes = $('#'+$(index).attr('class'));
		// console.log(checkboxes)
		if (index.checked) {
			let ini = document.getElementById(checkboxes2);
			ini.checked = true;
		}
	}
</script>