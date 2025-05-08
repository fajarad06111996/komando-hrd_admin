<style>
    /* Always set the map height explicitly to define the size of the div
    * element that contains the map. */
    #map {
        height: 500px;
        top:7px;
    }
    #description {
        font-family: Roboto;
        font-size: 15px;
        font-weight: 300;
    }
    #infowindow-content .title {
        font-weight: bold;
    }
    #infowindow-content {
        display: none;
    }
    #map #infowindow-content {
        display: inline;
    }
    .pac-card {
        margin: 10px 10px 0 0;
        border-radius: 2px 0 0 2px;
        box-sizing: border-box;
        -moz-box-sizing: border-box;
        outline: none;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
        background-color: #fff;
        font-family: Roboto;
    }
    #pac-container {
        padding-bottom: 12px;
        margin-right: 12px;
    }
    .pac-controls {
        padding: 5px 11px;
    }
    .pac-controls label {
        font-family: Roboto;
        font-size: 13px;
        font-weight: 300;
    }
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
</style>
<div class="pac-card" id="pac-card"></div>
<div id="infowindow-content">
    <img src="" width="16" height="16" id="place-icon">
    <span id="place-name"  class="title"></span><br>
    <span id="place-address"></span>
</div>
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
            <div class="alert alert-success" style="display: none;"></div>
            <div class="block-header text-center">
                <span class="block-title">CENTER POINT <?= !empty($off)?$off['office_name']:'';?></span>
            </div>
            <div class="block-content block-content-full text-center">
                <div id="map"></div>
            </div>
            <form action="<?= $formact; ?>" class="form-validasi form-horizontal" accept-charset="utf-8" enctype="multipart/form-data" method="post" id="myForm">
                <div class="panel_body pan">
                    <div class="form-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group row mb-1">
                                    <label class="col-form-label col-sm-3 text-right">Office Code<sup><b class="text-danger">*</b></sup></label>
                                    <div class="col-md-9">
                                        <?= csrf_input(); ?>
                                        <input name="idx" type="hidden" value="<?= !empty($off)?$off['idx']:'';?>"/>
                                        <input type="hidden" id="center_point" name="center_point" value="<?= !empty($off)?$off['center_point']:'';?>">
                                        <input type="hidden" id="lat_point" name="lat_point" value="<?= !empty($off)?$off['point_lat']:'';?>">
                                        <input type="hidden" id="long_point" name="long_point" value="<?= !empty($off)?$off['point_long']:'';?>">
                                        <input type="hidden" name="office_idx" value="<?= !empty($off)?$this->secure->enc($off['idx']):'';?>">
                                        <input name="office_code" type="text" value="<?= !empty($off)?$off['office_code']:'';?>" placeholder="Office Code" class="form-control form-control-sm" <?= empty($off)?'':'readonly';?> required data-msg="Office Code is required."/>
                                    </div>
                                </div>
                                <div class="form-group row mb-1">
                                    <label class="col-form-label col-sm-3 text-right">Office Name<sup><b class="text-danger">*</b></sup></label>
                                    <div class="col-md-9">
                                        <input name="office_name" type="text" value="<?= !empty($off)?$off['office_name']:'';?>" placeholder="Office Name" class="form-control form-control-sm" required data-msg="Office Name is required."/>
                                    </div>
                                </div>
                                <div class="form-group row mb-1">
                                    <label class="col-form-label col-sm-3 text-right">Company<b class="text-danger">*</b></label>
                                    <div class="col-md-9">
                                        <select data-placeholder="Select a Company" name="company_idx" id="company_idx" class="form-control form-control-sm select-search" required data-msg="Company is required.">
                                            <option value="">-- Select a Company --</option>
                                            <?php foreach($company as $o) : ?>
                                            <option value="<?= $this->secure->enc($o->idx);?>" <?= (!empty($off) && $off['company_idx']==$o->idx)?'selected':'';?>><?= $o->company_name;?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row mb-1">
                                    <label class="col-form-label col-sm-3 text-right">Email<sup><b class="text-danger">*</b></sup></label>
                                    <div class="col-md-9">
                                        <input name="email" type="email" value="<?= !empty($off)?$off['email_id']:'';?>" placeholder="Email" class="form-control form-control-sm" required data-msg="Email is required."/>
                                    </div>
                                </div>
                                <div class="form-group row mb-1">
                                    <label class="col-form-label col-sm-3 text-right">Telephone<sup><b class="text-danger">*</b></sup></label>
                                    <div class="col-md-9">
                                        <input name="telephone" type="number" value="<?= !empty($off)?$off['telephone']:'';?>" placeholder="Telephone" class="form-control form-control-sm" required data-msg="Telephone is required."/>
                                    </div>
                                </div>
                                <div class="form-group row mb-1">
                                    <label class="col-form-label col-sm-3 text-right">Fax</label>
                                    <div class="col-md-9">
                                        <input name="fax" type="text" value="<?= !empty($off)?$off['fax']:'';?>" placeholder="Fax" class="form-control form-control-sm" />
                                    </div>
                                </div>
                                <div class="form-group row mb-1">
                                    <label class="col-form-label col-sm-3 text-right">Tax Number</label>
                                    <div class="col-md-9">
                                        <input id="tNpwp" name="tax_id" type="text" value="<?= !empty($off)?$off['tax_id']:'';?>" placeholder="Tax Number" class="form-control form-control-sm" />
                                    </div>
                                </div>
                                <div class="form-group row mb-1">
                                    <label class="col-form-label col-sm-3 text-right">Status Hub</label>
                                    <div class="col-md-9">
                                        <div class="form-check form-check-inline">
                                            <label class="form-check-label">
                                                <input type="radio" value="1" class="form-check-input-styled" <?= (!empty($off) && $off['status']=='1')?'checked':'checked';?> name="tBlock" id="tAktifb">Aktif
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <label class="form-check-label">
                                                <input type="radio" value="0" class="form-check-input-styled" <?= (!empty($off) && $off['status']=='0')?'checked':'';?> name="tBlock" id="tNaktifb">Non-Aktif
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group row mb-1">
                                    <label class="col-form-label col-sm-3 text-right">Address<sup><b class="text-danger">*</b></sup></label>
                                    <div class="col-md-9">
                                        <textarea rows="4" id="address" name="address" class="form-control form-control-sm elastic" placeholder="Address" required data-msg="Address is required."><?= !empty($off)?$off['address']:'';?></textarea>
                                    </div>
                                </div>
                                <div class="form-group row mb-1">
                                    <label class="col-form-label col-sm-3 text-right">City<sup><b class="text-danger">*</b></sup></label>
                                    <div class="col-md-9">
                                        <input id="city" name="city" type="text" value="<?= !empty($off)?$off['city']:'';?>" placeholder="City" class="form-control form-control-sm" required data-msg="City is required."/>
                                    </div>
                                </div>
                                <div class="form-group row mb-1">
                                    <label class="col-form-label col-sm-3 text-right">Province<sup><b class="text-danger">*</b></sup></label>
                                    <div class="col-md-9">
                                        <input id="province" name="province" type="text" value="<?= !empty($off)?$off['province']:'';?>" placeholder="Province" class="form-control form-control-sm" required data-msg="Province is required."/>
                                    </div>
                                </div>
                                <div class="form-group row mb-1">
                                    <label class="col-form-label col-sm-3 text-right">Postal Code<sup><b class="text-danger">*</b></sup></label>
                                    <div class="col-md-9">
                                        <input id="postal_code" name="postal_code" type="text" value="<?= !empty($off)?$off['postal_code']:'';?>" placeholder="Postal Code" class="form-control form-control-sm" required data-msg="Postal Code is required."/>
                                    </div>
                                </div>
                                <div class="form-group row mb-1">
                                    <label class="col-form-label col-sm-3 text-right">State Code</label>
                                    <div class="col-md-9">
                                        <input id="state_code" name="state_code" type="text" value="<?= !empty($off)?$off['state_code']:'';?>" placeholder="State Code" class="form-control form-control-sm" />
                                    </div>
                                </div>
                                <div class="form-group row mb-1">
                                    <label class="col-form-label col-sm-3 text-right">Country<b class="text-danger">*</b></label>
                                    <div class="col-md-9">
                                        <input id="country" name="country" type="text" value="<?= !empty($off)?$off['country']:'';?>" placeholder="Country" class="form-control form-control-sm" required data-msg="Country is required."/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <!-- <button type="submit" class="btn btn-light">Cancel</button> -->
                        <a href="<?=$link;?>" class="btn btn-light" title="Back To List Data" data-placement="top" data-popup="tooltip">Cancel</a>
                        <button type="submit" class="btn bg-blue ml-3 btn-ladda btn-ladda-spinner bSubmit" data-spinner-color="#333" data-style="slide-down"> <i class="icon-floppy-disk"></i> Save Data</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="<?= base_url(); ?>assets/layout1/js/master/v_formoffice.js?v=0.1" params='<?= $params; ?>'></script>
<script src="https://maps.googleapis.com/maps/api/js?key=<?= key_google(); ?>&libraries=places&callback=initMap" async defer></script>