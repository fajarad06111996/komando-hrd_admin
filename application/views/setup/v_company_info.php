<div class="card panel panel-pink">
    <div class=" header-elements-inline panel-heading">
        Info Perusahaan
        <div class="header-elements">
            <div class="list-icons">
                <a class="list-icons-item" data-action="reload" title="Refresh" data-placement="right" data-popup="tooltip"></a>
                <a class="list-icons-item" data-action="fullscreen" title="Fullscreen" data-placement="right" data-popup="tooltip"></a>
            </div>
        </div>
    </div>
    <input type="hidden" name="spinner">
    <form action="<?= $formact; ?>" class="f-validasi" enctype="multipart/form-data" method="post" id="myForm">
        <div class="panel_body">
            <div class="row">
                <div class="col-md-10 mt-1 pr-0">
                    <ul class="nav nav-tabs  nav-tabs-solid bg-slate border-0 nav-tabs-component rounded mb-0">
                        <li class="nav-item"><a href="#info-tab1" class="nav-link active font-weight-bold pt-1 pb-1 pl-2 pr-2 iGeneral" data-toggle="tab">Umum</a></li>
                        <li class="nav-item"><a href="#info-tab2" class="nav-link font-weight-bold pt-1 pb-1 pl-2 pr-2 iMilestones" data-toggle="tab">Pajak</a></li>
                    </ul>
                    <div class="tab-content card card-body border-top-0 rounded-top-0 mb-0 ">
                        <div class="tab-pane fade show active " id="info-tab1">
                            <table class="table table-sm1" style="margin-top: -18px;">
                                <tr>
                                    <td style="width: 5%;" class="text-default font-size-sm text-right">Nama Perusahaan<sup class="text-danger">*</sup></td>
                                    <td style="width: 30%;">
                                        <?= csrf_input(); ?>
                                        <input type="text" name="cName" tabindex="17"  class="textbox-xs col-sm-12" value="" placeholder="Nama Perusahaan" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-default font-size-sm text-right" style="vertical-align: top;">Alamat<sup class="text-danger">*</sup></td>
                                    <td>
                                        <textarea rows="6" name="cAddress"  class="textbox-xs col-sm-12" placeholder="Alamat" required></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-default font-size-sm text-right" style="vertical-align: top;">Kota<sup class="text-danger">*</sup></td>
                                    <td>
                                        <input type="text" name="cCity" tabindex="11"  value="" class="textbox-xs col-sm-12 mReadonly" placeholder="Kota">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-default font-size-sm text-right">Kode Pos</td>
                                    <td>
                                        <input type="text" name="cPostcode" tabindex="11"  value="" class="textbox-xs col-sm-12 mReadonly" placeholder="Kode Pos">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-default font-size-sm text-right">Email</td>
                                    <td>
                                        <input type="email" name="cEmail" tabindex="11"  value="" class="textbox-xs col-sm-12 mReadonly" placeholder="Email">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-default font-size-sm text-right">Telephone</td>
                                    <td>
                                        <input type="text" name="cPhone" tabindex="13" value="" class="textbox-xs col-sm-12 mReadonly" placeholder="Telephone">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-default font-size-sm text-right">Fax</td>
                                    <td>
                                        <input type="text" name="cFax" tabindex="13" value="" class="textbox-xs col-sm-12 mReadonly" placeholder="Fax">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-default font-size-sm text-right">Negara</td>
                                    <td>
                                        <input type="text" name="cCountry" tabindex="15" value="" class="textbox-xs col-sm-12 mReadonly" placeholder="Negara">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-default font-size-sm text-right">Mata Uang</td>
                                    <td>
                                        <select name="cCurrency" id="cCurrency" data-placeholder="Pilih Mata Uang" class="form-control form-control-xs select-search"  data-container-css-class="select-xs" data-fouc>
                                            <option value="IDR">IDR</option>
                                            <option value="USD">USD</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-default font-size-sm text-right">PIC Keuangan</td>
                                    <td>
                                        <input type="text" name="cPolis" tabindex="15" value="" class="textbox-xs col-sm-12" placeholder="PIC Keuangan">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-default font-size-sm text-right">Nama Bank</td>
                                    <td>
                                        <input type="text" name="cBankName" tabindex="15" value="" class="textbox-xs col-sm-12" placeholder="Nama Bank">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-default font-size-sm text-right">Nama Rekening</td>
                                    <td>
                                        <input type="text" name="cAccountName" tabindex="15" value="" class="textbox-xs col-sm-12" placeholder="Nama Rekening">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-default font-size-sm text-right">No. Rekening</td>
                                    <td>
                                        <input type="text" name="cAccountCode" tabindex="15" value="" class="textbox-xs col-sm-12" placeholder="No. Rekening">
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="info-tab2">
                            <table class="table table-sm1" style="margin-top: -18px;">
                                <tr>
                                    <td style="width: 5%;" class="text-default font-size-sm text-right">Nama Perusahaan</td>
                                    <td style="width: 30%;">
                                        <input type="text" name="ctName" tabindex="17"  class="textbox-xs col-sm-12" value="" placeholder="Nama Perusahaan">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-default font-size-sm text-right" style="vertical-align: top;">Alamat</td>
                                    <td>
                                        <textarea rows="6" name="ctAddress"  class="textbox-xs col-sm-12" placeholder="Alamat"></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-default font-size-sm text-right">Kode Pos</td>
                                    <td>
                                        <input type="text" name="ctPostcode" tabindex="11"  value="" class="textbox-xs col-sm-12" placeholder="Kode Pos">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-default font-size-sm text-right">No Seri Faktur Pajak</td>
                                    <td>
                                        <input type="text" name="ctSerial_no" tabindex="13" value="" class="textbox-xs col-sm-12" placeholder="No Seri Faktur Pajak">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-default font-size-sm text-right">No Pokok Wajib Pajak</td>
                                    <td>
                                        <input type="text" name="ctTax_number" id="ctTax_number" tabindex="13" value="" class="textbox-xs col-sm-12" placeholder="No Pokok Wajib Pajak">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-default font-size-sm text-right">No Pengukuhan PKP</td>
                                    <td>
                                        <input type="text" name="ctPkp" tabindex="15" value="" class="textbox-xs col-sm-12" placeholder="No Pengukuhan PKP">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-default font-size-sm text-right">Tgl Pengukuhan PKP</td>
                                    <td>
                                        <input type="text" name="ctDatePkp" id="ctDatePkp" tabindex="15" value="" class="textbox-xs col-sm-12" placeholder="No Pengukuhan PKP">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-default font-size-sm text-right">Jenis Usaha</td>
                                    <td>
                                        <input type="text" name="ctBusinessType" id="ctBusinessType" tabindex="15" value="" class="textbox-xs col-sm-12" placeholder="No Pengukuhan PKP">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-default font-size-sm text-right">KLU SPT</td>
                                    <td>
                                        <input type="text" name="ctKLUSPT" id="ctKLUSPT" tabindex="15" value="" class="textbox-xs col-sm-12" placeholder="No Pengukuhan PKP">
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="d-flex justify-content-end align-items-end">
                <!-- <button type="button" data-dismiss="modal" class="btn btn-outline-secondary">Close</button> -->
                <button type="submit" id="sbmit" class="btn btn-primary btn-ladda btn-ladda-spinner" data-spinner-color="#333" data-style="slide-down"><i class="icon-floppy-disk"></i> Simpan</button>
            </div>
        </div>
    </form>
</div>
<script src="<?= base_url(); ?>assets/global_assets/js/plugins/pickers/datetimepicker/jquery.datetimepicker.full.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    getData();
    var notif;
    $('#ctDatePkp').datetimepicker({
        timepicker:false,
        format:'Y-m-d',
        formatDate:'Y-m-d',
    });
    $('#myForm').submit(async function(e) {
        e.preventDefault();
        var url = $('#myForm').attr('action');
        var data = $('#myForm').serialize();
        try {
            const myForm = await submitForm('post', url, data);
            if (myForm.error) {
                notiferror_a(myForm.message);
            }else if(myForm.status){
                notiferror_a(myForm.text);
            }else{
                getData();
                notifsukses(myForm.text);
            }
        } catch (error) {
            console.log(error);
            notiferror('Error Connection,<br>Please check your connection internet.');
        }
    });
});

async function getData()
{
    var r = $('input[name=spinner]');
    spinnersdark(r[0]);
    try {
        const data = await submitForm('get', '<?= $link; ?>/getCompro', {csrfsession: '<?= $this->session->csrf_token; ?>'});
        stopspinnersdark();
        if(data != null){
            $('input[name=cName]').val(data.company_name);
            $('textarea[name=cAddress]').val(data.company_address);
            $('input[name=cCity]').val(data.company_city);
            $('input[name=cPostcode]').val(data.company_postal_code);
            $('input[name=cEmail]').val(data.company_email);
            $('input[name=cPhone]').val(data.company_phone);
            $('input[name=cFax]').val(data.company_fax);
            $('input[name=cCountry]').val(data.company_country);
            $('input[name=cPolis]').val(data.pic_invoice);
            $('input[name=cBankName]').val(data.company_bank_name);
            $('input[name=cAccountName]').val(data.company_bank_account);
            $('input[name=cAccountCode]').val(data.company_bank_code);
            $('input[name=cPolis]').val(data.pic_invoice);
            $("#cCurrency").select2({width: '100%'}).val(data.company_currency).trigger('change.select2');
            $("#cBank").select2({width: '100%'}).val(data.account_number_transfer).trigger('change.select2');
            $('input[name=ctName]').val(data.tax_company);
            $('textarea[name=ctAddress]').val(data.tax_address);
            $('input[name=ctPostcode]').val(data.tax_postal_code);
            $('input[name=ctSerial_no]').val(data.tax_serial_no);
            $('input[name=ctTax_number]').val(data.tax_number);
            $('input[name=ctPkp]').val(data.tax_pkp);
            $('input[name=ctDatePkp]').val(data.tax_date_pkpx);
            $('input[name=ctBusinessType]').val(data.tax_business_type);
            $('input[name=ctKLUSPT]').val(data.tax_spt);
        }
    } catch (error) {
        stopspinnersdark();
        console.log(error);
        notiferror('Error Connection,<br>Please check your connection internet.');
    }
}
jQuery(function($){
    $("#ctTax_number").mask("99.999.999.9-999.999");
});
</script>