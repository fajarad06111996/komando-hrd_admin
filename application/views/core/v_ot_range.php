<style>
	.dataTables_wrapper .table-bordered {
		border-top: 1px solid #dee2e6 !important;
	}
</style>
<div class="panel panel-blue">
	<div class="panel-heading">Setup Lembur
		<?= $write; ?>
	</div>
	<div class="panel_body">
		<div class="table-responsive">
			<table id="example2" class="table table-striped table-bordered table-hover table-sm1">
				<thead>
					<tr>
						<td class="text-center"><b>#</b></td>
                        <td class="text-center"><b>Action</b></td>
                        <td class="text-center"><b>Status</b></td>
                        <td class="text-center"><b>Kode</b></td>
                        <td class="text-center"><b>Deskripsi</b></td>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
	</div>
</div>
<!--loading bootstrap js-->
<script src="<?= base_url(); ?>assets/layout1/js/core/v_ot_range.js?v=0.1" params='<?= $params; ?>'></script>