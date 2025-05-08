<style>
    /* .fc, .fc-day-number {
        font-size: 12px !important;
    } */
    #calendar {
        max-width: 850px;
        margin: 40px auto;
    }
    .fc-sun .fc-day-number{
        color: red;
    }
</style>
<div class="container bg-white">
	<div class="row">
		<div class="col-lg-12">
			<h5 align="center">KALENDER</h5>
			<div id="calendar"></div>
			<!-- <div id="calendarx" class="fullcalendar-rtl"></div> -->
			<!-- <div id="external-events"></div>
			<div id="drop-remove"></div> -->
		</div>
	</div>
</div>
<!-- Start popup dialog box -->
<div class="modal fade" id="event_entry_modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true" data-backdrop="static">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modalLabel">Tambah even</h5>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
            <form action="" id="calForm" class="form-horizontal form-validasi" accept-charset="utf-8" enctype="multipart/form-data" method="post">
                <div class="modal-body">
                    <div class="img-container">
                        <div class="row">
                            <div class="col-sm-12">  
                                <div class="form-group">
                                <label for="event_name">Event name<sup><b class="text-danger">*</b></sup></label>
                                <input type="text" name="event_name" id="event_name" class="form-control" placeholder="Enter your event name" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">  
                                <div class="form-group">
                                <label for="event_date">Event date<sup><b class="text-danger">*</b></sup></label>
                                <input type="date" name="event_date" id="event_date" class="form-control onlydatepicker" placeholder="Event date" required>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="row">
                            <div class="col-sm-6">  
                                <div class="form-group">
                                <label for="event_start_date">Event start<sup><b class="text-danger">*</b></sup></label>
                                <input type="datetime-local" name="event_start_date" id="event_start_date" class="form-control onlydatepicker" placeholder="Event start date" required>
                                </div>
                            </div>
                            <div class="col-sm-6">  
                                <div class="form-group">
                                <label for="event_end_date">Event end<sup><b class="text-danger">*</b></sup></label>
                                <input type="datetime-local" name="event_end_date" id="event_end_date" class="form-control" placeholder="Event end date" required>
                                </div>
                            </div>
                        </div> -->
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <button type="button" data-dismiss="modal" class="btn btn-outline-secondary">Batal</button>
                        <button type="submit" class="btn btn-primary btn-ladda btn-ladda-spinner bSbmit" data-spinner-color="#333" data-style="slide-down">Simpan</button>
                    </div>
                    <!-- <button type="button" class="btn btn-primary" onclick="save_event()">Save Event</button> -->
                </div>
            </form>
		</div>
	</div>
</div>
<!-- End popup dialog box -->
<script src="<?= base_url(); ?>assets/layout1/js/core/v_calendar.js?v=0.1" params='<?= $params; ?>'></script>