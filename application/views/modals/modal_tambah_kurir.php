<form class="col-md-offset-1 col-md-10 col-md-offset-1" id="tu_form" role="dialog" method="POST" enctype="multipart/form-data">
	<div class="modal-dialog">
		<div class="box box-default">
			<div class="box-header with-border">
				<h3 class="box-title" id="tu_title">&nbsp;</h3>
				<div class="box-tools pull-right">
					<button type="button" class="btn btn-box-tool" data-dismiss="modal">&times;</button>
				</div>
			</div>
			<div class="box-body">
				<div class="form-group row">
					<div class="col-sm-3">No. KTP</div>
					<div class="col-sm-9">
						<input type="text" class="form-control" autocomplete="off" name="noktp" placeholder="Nomor KTP" required>
					</div>
				</div>
				<div class="form-group row">
					<div class="col-sm-3">Nama</div>
					<div class="col-sm-9">
						<input type="text" class="form-control" autocomplete="off" name="nama" placeholder="Nama" required>
					</div>
				</div>
			</div>
			<div class="box-footer">
				<button type="button" class="btn btn-secondary pull-right" title="RESET" id="tu_reset"><i class="fa fa-undo"></i></button>
				<button type="submit" class="btn btn-success pull-right" title="SIMPAN" id="tu_simpan"><i class="fa fa-save"></i></button>
			</div>
		</div>
	</div>
</form>