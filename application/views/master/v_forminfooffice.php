<style>
    /* Always set the map height explicitly to define the size of the div
    * element that contains the map. */
    #map {
        height: 300px;
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
                <span class="block-title">CENTER POINT <?= strtoupper(trim($oName)); ?></span>
            </div>
            <div class="block-content block-content-full text-center">
                <div id="map"></div>
            </div>
            <div class="block-content font-size-sm mb-5">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table">
                            <tr>
                                <td class="text-right"><strong>Company</strong></td>
                                <td class="text-left">: <?= $off['company_name'] ?></td>
                            </tr>
                            <tr>
                                <td class="text-right"><strong>Office Code</strong></td>
                                <td class="text-left">: <?= $off['office_code'] ?></td>
                            </tr>
                            <tr>
                                <td class="text-right"><strong>Office Name</strong></td>
                                <td class="text-left">: <?= $off['office_name'] ?></td>
                            </tr>
                            <tr>
                                <td class="text-right"><strong>Email</strong></td>
                                <td class="text-left">: <?= $off['email_id'] ?></td>
                            </tr>
                            
                            <tr>
                                <td class="text-right"><strong>Telephone</strong></td>
                                <td class="text-left">: <?= $off['telephone'] ?></td>
                            </tr>
                            <tr>
                                <td class="text-right"><strong>Fax</strong></td>
                                <td class="text-left">: <?= $off['fax'] ?></td>
                            </tr>
                            <tr>
                                <td class="text-right"><strong>Tax Number</strong></td>
                                <td class="text-left">: <?= $off['tax_id'] ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table">
                            <tr>
                                <td class="text-right"><strong>Address</strong></td>
                                <td class="text-left">: <?= $off['address'] ?></td>
                            </tr>
                            <tr>
                                <td class="text-right"><strong>City</strong></td>
                                <td class="text-left">: <?= $off['city'] ?></td>
                            </tr>
                            <tr>
                                <td class="text-right"><strong>Province</strong></td>
                                <td class="text-left">: <?= $off['province'] ?></td>
                            </tr>
                            <tr>
                                <td class="text-right"><strong>Postal Code</strong></td>
                                <td class="text-left">: <?= $off['postal_code'] ?></td>
                            </tr>
                            <tr>
                                <td class="text-right"><strong>State Code</strong></td>
                                <td class="text-left">: <?= $off['state_code'] ?></td>
                            </tr>
                            <tr>
                                <td class="text-right"><strong>Country</strong></td>
                                <td class="text-left">: <?= $off['country'] ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?= base_url(); ?>assets/layout1/js/master/v_forminfooffice.js?v=0.1" params='<?= $params; ?>'></script>
<script src="https://maps.googleapis.com/maps/api/js?key=<?= key_google(); ?>&libraries=places&callback=initMap" async defer></script>