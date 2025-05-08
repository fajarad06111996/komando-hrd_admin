<style>
    /* Always set the map height explicitly to define the size of the div
    * element that contains the map. */
    /* #description {
        font-family: Roboto;
        font-size: 15px;
        font-weight: 300;
    } */
    #txt_originsearchaddress:focus {
        border-color: #4d90fe;
    }
    #title {
        color: #fff;
        background-color: #4d90fe;
        font-size: 25px;
        font-weight: 500;
        padding: 6px 12px;
    }
    .form-control-placeholdericon {
        font-family: Verdana, FontAwesome, 'Material Icons';
    }
    .field-icon {
        float: right;
        margin-left: -25px;
        margin-top: -28px;
        margin-right: 0px;
        position: relative;
        z-index: 2;
    }
    #mFormDet .validation-invalid-label,
    #mFormDet .validation-invalid-label:before {
        font-size: 10px !important;
    }
</style>
<!--Modal Responsive-->
<div id="mFormDet" data-backdrop="static" data-keyboard="false" class="modal fade">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header pt-2 pb-2 bg-blue-700">
				<h4 class="modal-title"></h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<form action="" id="myFormDet" class="form-horizontal form-validasi2" accept-charset="utf-8" enctype="multipart/form-data" method="post">
				<div class="modal-body">
					<div class="row">
                        <div class="col-sm-12">
                            <table id="itux" class="table table-sm1 col-sm-12 text-center mt-2">
                                <thead>
                                    <tr>
                                        <th width="5%">#</th>
                                        <th width="18%">MIN JAM<sup><b class="text-danger">*</b></sup></th>
                                        <th width="18%">MAX JAM<sup><b class="text-danger">*</b></sup></th>
                                        <th>TIPE SETUP<sup><b class="text-danger">*</b></sup></th>
                                        <th>NILAI<sup><b class="text-danger">*</b></sup></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="nohx">
                                        <td>1</td>
                                        <td>
                                            <?= csrf_input(); ?>
                                            <input type="hidden" name="ot_id" value="<?= empty($dataRange)?'':$this->secure->enc($dataRange['ot_id']); ?>">
                                            <input type="number" name="tMinx" class="textbox-xxs col-sm-11 text-center tMin" placeholder="0" value="" required>
                                        </td>
                                        <td>
                                            <input type="number" name="tMaxx" class="textbox-xxs col-sm-11 text-center tMax" placeholder="0" value="" required>
                                        </td>
                                        <td>
                                            <select name="tTotx" class="textbox-xs">
                                                <option value="0" >x1</option>
                                                <option value="1" >xJam</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" name="tValuex" class="textbox-xxs col-sm-6 text-center tValue" placeholder="0" value="" required>&nbsp;Or&nbsp;
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="tUmx" value="1" id="defaultCheckx1">
                                                <label class="form-check-label" for="defaultCheckx1">
                                                    UM
                                                </label>
                                            </div>
                                            <input type="hidden" class="tEndHidden" name="tEndHiddenx" value="1">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
					</div>
				</div>
				<div class="card-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <button type="button" data-dismiss="modal" class="btn btn-outline-secondary">Close</button>
                        <button type="submit" class="btn btn-primary btn-ladda btn-ladda-spinner bSbmit" data-spinner-color="#333" data-style="slide-down">Save changes</button>
                        <!-- <button type="submit" id="bSubmit" style="display: none;"></button> -->
                    </div>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- End Modal add -->
<div class="row">
    <div class="col-md-12">
        <div class="card panel panel-blue">
            <div class="header-elements-inline panel-heading">
                <?= $title; ?>
                <div class="header-elements">
                    <div class="list-icons">
                        <a href="<?=$link;?>" class="list-icons-item" title="Back To List Data" data-placement="right" data-popup="tooltip">
                        <i class="icon-undo2 pr-1" style="font-size:0.8em;padding-top: 4px;"></i>
                        </a>
                        <a class="list-icons-item" data-action="reload" title="Refresh" data-placement="right" data-popup="tooltip"></a>
                        <a class="list-icons-item" data-action="fullscreen" title="Fullscreen" data-placement="right" data-popup="tooltip"></a>
                    </div>
                </div>
            </div>
            <form action="<?= $formact; ?>" class="form-validasi form-horizontal" accept-charset="utf-8" enctype="multipart/form-data" method="post" id="myForm">
                <div class="panel_body pan">
                    <div class="form-body">
                        <div class="row justify-content-center">
                            <div class="col-sm-6">
                                <div class="form-group row mb-1">
                                    <label class="col-form-label col-sm-3 text-uppercase text-right">Setup Code<sup><b class="text-danger">*</b></sup></label>
                                    <div class="col-md-6">
                                        <?= csrf_input(); ?>
                                        <input id="ot_code" name="ot_code" type="text" placeholder="Setup Code" class="form-control form-control-sm" value="<?= empty($dataRange)?'':$dataRange['ot_code']; ?>" readonly/>
                                    </div>
                                </div>
                                <div class="form-group row mb-1">
                                    <label class="col-form-label col-sm-3 text-uppercase text-right">Description<sup><b class="text-danger">*</b></sup></label>
                                    <div class="col-md-6">
                                        <textarea rows="4" id="description" name="description" type="text" placeholder="Description" class="form-control form-control-sm" required><?= empty($dataRange)?'':$dataRange['description']; ?></textarea>
                                    </div>
                                </div>
                                <div class="form-group row mb-1">
                                    <label class="col-form-label col-sm-3 text-right">STATUS</label>
                                    <div class="col-md-9">
                                        <div class="form-check form-check-inline">
                                            <label class="form-check-label">	<!-- tBnaktif -->
                                                <input type="radio" value="1" class="form-check-input-styled" name="tStatus" id="xTrue" <?= empty($dataRange)?'checked':($dataRange['status']==1?'checked':''); ?>>Aktif
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <label class="form-check-label">	<!-- tBaktif -->
                                                <input type="radio" value="0" class="form-check-input-styled" name="tStatus" id="xFalse" <?= empty($dataRange)?'':($dataRange['status']==0?'checked':''); ?>>Non-Aktif
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-9">
                                <?php if(empty($dataRange)){ ?>
                                <table id="itu" class="table table-sm1 col-sm-12 text-center mt-2">
                                    <thead>
                                        <tr>
                                            <th width="5%">#</th>
                                            <th width="18%">MIN JAM<sup><b class="text-danger">*</b></sup></th>
                                            <th width="18%">MAX JAM<sup><b class="text-danger">*</b></sup></th>
                                            <th>TIPE SETUP<sup><b class="text-danger">*</b></sup></th>
                                            <th>NILAI<sup><b class="text-danger">*</b></sup></th>
                                            <th style="width: 10%;">
                                                <button type="button" class="btn btn-success loopForm" data-popup="tooltip" title="Add Detail" data-placement="right"><i class="icon-plus-circle2"></i></button>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody id="rangeBody">
                                        <tr class="noh">
                                            <td>1</td>
                                            <td>
                                                <input type="number" name="tMin[]" class="textbox-xxs col-sm-11 text-center tMin" placeholder="0" value="">
                                            </td>
                                            <td>
                                                <input type="number" name="tMax[]" class="textbox-xxs col-sm-11 text-center tMax" placeholder="0" value="">
                                            </td>
                                            <td>
                                                <select name="tTot[]" class="textbox-xs">
                                                    <option value="0" >x1</option>
                                                    <option value="1" >xJAM</option>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" name="tValue[]" class="textbox-xxs col-sm-6 text-center tValue" placeholder="0" value="">&nbsp;Or&nbsp;
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="checkbox" name="tUm[]" value="1" id="defaultCheck1">
                                                    <label class="form-check-label" for="defaultCheck1">
                                                        UM
                                                    </label>
                                                </div>
                                                <input type="hidden" class="tEndHidden" name="tEndHidden[]" value="1">
                                            </td>
                                            <td>
                                                <div class='btn-group'>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <?php }else{ ?>
                                <div class="table-responsive">
                                    <table id="rangeDetail" class="table table-sm1 text-center mt-2" width="100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>MIN JAM<sup><b class="text-danger">*</b></sup></th>
                                                <th>MAX JAM<sup><b class="text-danger">*</b></sup></th>
                                                <th>TIPE SETUP<sup><b class="text-danger">*</b></sup></th>
                                                <th>NILAI<sup><b class="text-danger">*</b></sup></th>
                                                <th>
                                                    <button type="button" class="btn btn-success bAddDetail" data-popup="tooltip" title="Add Detail" data-placement="right"><i class="icon-plus-circle2"></i></button>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <!-- <button type="submit" class="btn btn-light">Cancel</button> -->
                        <a href="<?=$link;?>" class="btn btn-light" title="Back To List Data" data-placement="top" data-popup="tooltip">Cancel</a>
                        <button type="submit" class="btn bg-blue ml-3 btn-ladda btn-ladda-spinner" data-spinner-color="#333" data-style="slide-down"> <i class="icon-floppy-disk"></i> Save Data</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="<?= base_url(); ?>assets/layout1/js/core/v_formotrange.js?v=0.1" params='<?= $params; ?>'></script>