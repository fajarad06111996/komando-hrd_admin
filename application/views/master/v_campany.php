<div id="mForm" data-backdrop="static" data-keyboard="false" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header pt-2 pb-2 bg-info">
				<h4 class="modal-title"></h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<form action="" id="myForm" class="form-horizontal form-validasi" method="post">
				<div class="modal-body">
					<div class="form-group row mb-1">
						<label class="col-form-label col-sm-3 text-uppercase">COMPANY NAME</label>
						<div class="col-md-9">
							<input name="tCompanyName" type="text" placeholder="Company Name" class="form-control form-control-sm text-uppercase" required/>
						</div>
					</div>
					<div class="form-group row mb-1">
						<label class="col-form-label col-sm-3 text-uppercase">COMPANY ADDRESS</label>
						<div class="col-md-9">
							<textarea name="tCompanyAddress" class="form-control form-control-sm " placeholder="Company Address" rows="4"></textarea>
						</div>
					</div>
					<div class="form-group row mb-1">
						<label class="col-form-label col-sm-3 text-uppercase">FILE</label>
						<div class="col-md-9">
							<input name="tFilename" type="file" placeholder="Company Address" required/>
						</div>
					</div>
				</div>
        <div class="card-footer">
          <div class="d-flex justify-content-between align-items-center">
            <button type="button" data-dismiss="modal" class="btn btn-default">Close</button>
            <button type="submit" class="btn btn-primary btn-ladda btn-ladda-spinner" data-spinner-color="#333" data-style="slide-down">Save changes</button>
          </div>
				</div>
			</form>
		</div>
	</div>
</div>


<div class="panel panel-green">
	<div class="panel-heading">List Data Company 
    <?= $write; ?>
  </div>
	<div class="alert alert-success" style="display: none;"></div>
	<div class="panel_body">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover table-sm1 fTable">
				<thead>
					<tr>
						<th style="text-align: center;width: 20px;">#</th>
						<th>COMPANY NAME</th>
						<th>COMPANY ADDRESS</th>
						<th style="width:7%;text-align: center;">Aksi</th>
					</tr>
				</thead>
				<tbody>
          <?php 
            $i  = 1;
            foreach($result as $r){
          ?>
          <tr>
            <td><?= $i++; ?></td>
            <td><?= $r->company_name; ?></td>
            <td><?= $r->company_address; ?></td>
            <td>
							<?php if(!empty($this->security_function->permissions($filename . "-c"))){ ?>
								<a href="javascript:void(0);" id="<?= $r->company_id; ?>" class="bEdit" data-popup="tooltip" title="Edit Company" data-placement="right"><i class="fa fa-edit fa-lg"></i>&nbsp;</a>
							<?php }else{ ?>
								<i class="fa fa-lock fa-lg  text-warning mr-2" data-popup="tooltip" title="Locked Edit Company" data-placement="right"></i>&nbsp;
							<?php } ?>

              <?php if(!empty($this->security_function->permissions($filename . "-x"))){ ?>
								<a href="javascript:void(0);" id="<?= $r->company_id; ?>" data="<?= $r->company_name; ?>" class="bDelete" data-popup="tooltip" title="Delete Company" data-placement="right"><i class="fa fa-trash-o fa-lg"></i>&nbsp;</a>
							<?php }else{ ?>
								<i class="mi-lock text-warning font-weight-black" data-popup="tooltip" title="Locked Delete Company" data-placement="right"></i>&nbsp;
							<?php } ?>
              
            </td>
          </tr>
          <?php } ?>
          
				</tbody>
			</table>
		</div>
	</div>
</div>
<!--loading bootstrap js-->
<script type="text/javascript">
	$(document).ready(function() {
		<?php if($lock==1): ?>
			$('#btnAdd').click(function() {
				$('#mForm').modal('show');
				$('#mForm').find('.modal-title').text('Add New Company');
				$('#myForm').attr('action', '<?= $link; ?>/addcompany');
				$("#myForm").data('validator').resetForm();
				$('#myForm')[0].reset();
				$('#btnAdd').tooltip('hide');
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
				success: function(response)  {
          // console.log(response);
					if (response.error) {
					}else if(response.status){
            notiferror_a('Error Update Company To Database');
					}else{
            $('#mForm').modal('hide');
						if (response.type == 'add') {
							var type = 'Added'
						} else if (response.type == 'update') {
							var type = "Updated"
            }
            notifsukses('Data Company ' + type + ' Successfully');
            setTimeout(function () { location.reload(1); }, 2000);
          }
				},
				error: function() {
          $('#mForm').modal('hide');
          notiferror('Error Update To Database');
				}
			});

		});

		<?php if(!empty($this->security_function->permissions($filename . "-c"))): ?>
		$('.fTable').on('click', '.bEdit', function() {
			var id    = $(this).attr('id');
      $('.bEdit').tooltip('hide');
			start();
			$.ajax({
				type: 'ajax',
				method: 'get',
				url: '<?= $link; ?>/editcompany',
				data: {
					id: id
				},
				async: true,
				dataType: 'json',
				success: function(data) {
					if(data===false){
            errordatabase();
          }else{
            $('#mForm').find('.modal-title').text('Edit Data Company');
            $('#myForm').attr('action', '<?= $link; ?>/updatecompany/'+id);
            $("#myForm").data('validator').resetForm();
            $('input[name=tCompanyName]').val(data.company_name);
            $('textarea[name=tCompanyAddress]').val(data.company_address);
						end();
						setTimeout(function() {$('#mForm').modal('show');}, lama_akses+500);
          }
				},
				error: function() {
					errordatabase();
				}
			});
		});

		<?php endif; ?>

		<?php if(!empty($this->security_function->permissions($filename . "-x"))): ?>
    $('.fTable').on('click', '.bDelete', function() {
			var id    = $(this).attr('id');
			var Name = $(this).attr('data');
      $('#deleteModal').modal('show');
			$('#deleteModal').find('.infoDelete').text('Company Name : '+Name);
			//prevent previous handler - unbind()
			$('#btnDelete').unbind().click(function() {
				$.ajax({
					type: 'ajax',
					method: 'get',
					async: true,
					url: '<?= $link; ?>/deletecompany',
					data: {
						id: id
					},
					dataType: 'json',
					success: function(response) {
            $('#deleteModal').modal('hide');
						if(response.success){
              notifsukses('Company Name <strong>'+Name+'</strong> Delete Successfully');
              setTimeout(function () { location.reload(1); }, 2000);
						}else{
              notiferror('Error Delete Company To Database');
						}
					},
					error: function() {
            $('#deleteModal').modal('hide');
            notiferror('Error Delete To Database');
					}
				});
			});
		});
    <?php endif; ?>
	});
</script>