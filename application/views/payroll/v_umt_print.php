<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
        <title>UMT</title>
        <meta name="description" content="UMT">
        <meta name="author" content="pixelcave">
        <meta name="robots" content="noindex, nofollow">
        <!-- Icons -->
        <!-- The following icons can be replaced with your own, they are used by desktop and mobile browsers -->
        <link rel="shortcut icon" href="<?= logo_apps(); ?>">
        <link rel="icon" type="image/png" sizes="192x192" href="<?= logo_apps(); ?>">
        <link rel="apple-touch-icon" sizes="180x180" href="<?= logo_apps(); ?>">
		<style>
			.border-full {
				border: 1px solid #000;
			}
			.border-bottom {
				border-bottom: 1px solid #000;
			}
			.border-top {
				border-top: 1px solid #000;
			}
			.border-left {
				border-left: 1px solid #000;
			}
			.border-right {
				border-right: 1px solid #000;
			}
		</style>
    </head>
    <body>
		<table border="0" cellpadding="0">
			<tr>
				<td width="100%">
					<img src="<?= logo_apps(); ?>" alt="" style="width:100px;margin-left:0;">
				</td>
			</tr>
			<tr>
				<td border="0" width="40%">
					<b><?= $company['company_name']; ?></b>
				</td>
				<td border="0" width="60%">
					<b style="text-align:right;"></b>
				</td>
			</tr>
			<tr>
				<td border="0" width="40%">
					<span style="font-size:11px;"><?= $company['address']; ?></span>
				</td>
				<td border="0" width="60%">
					<b style="text-align:right;"></b>
				</td>
			</tr>
		</table>
        <table cellspacing="0" cellpadding="5" border="1" style="font-size:15px; width:100%">
			<tr style="color:#000;">
				<td align="center" style="width:100%;"><b style="text-align:center;text-decoration: underline;">List UMT</b></td>
			</tr>
		</table>
		<br>
		<table cellspacing="1" cellpadding="5" style="font-size:12px; width:100%" border="0">
			<tr>
				<td style="width:70%"><table border="0">
						<tr>
							<td width="15%">KODE UMT</td>
							<td width="3%">:</td>
							<td width="82%"><?= $dataHeader['allowance_code'] ?></td>
						</tr>
						<tr>
							<td width="15%">PERIODE</td>
							<td width="3%">:</td>
							<td width="82%"><?= date('d',strtotime($dataHeader['start'])); ?>/<?= bulan(date('M',strtotime($dataHeader['start']))); ?>/<?= date('Y',strtotime($dataHeader['start'])); ?> - <?= date('d',strtotime($dataHeader['end'])); ?>/<?= bulan(date('M',strtotime($dataHeader['end']))); ?>/<?= date('Y',strtotime($dataHeader['end'])); ?></td>
						</tr>
						<tr>
							<td width="15%">UP</td>
							<td width="3%">:</td>
							<td width="82%">Depart HRD</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		<br><br>
		<table cellspacing="0" cellpadding="3" border="0" style="font-size:8px; width:100%">
			<tr style="background-color: #00B0F0;color:#000;">
				<td class="border-full" align="center" style="width:5%;"><b>NO</b></td>
				<td class="border-full" align="center" style="width:15.5%;text-align:center;"><b>KODE KARYAWAN</b></td>
				<td class="border-full" align="center" style="width:15.5%;"><b>NAMA KARYAWAN</b></td>
				<td class="border-full" align="center" style="width:16%;"><b>UANG MAKAN</b></td>
				<td class="border-full" align="center" style="width:16%;"><b>TRANSPORT</b></td>
				<td class="border-full" align="center" style="width:16%;"><b>LEMBUR</b></td>
				<td class="border-full" align="center" style="width:16%;"><b>TOTAL</b></td>
			</tr>
			<?php $i = 1; ?>
            <?php foreach ($dataDetail as $row){ ?>
            <tr>
                <td class="border-full" align="center" style="width:5%;"><?= $i; ?>.</td>
				<td class="border-full" align="center" style="text-align:center;"><?= $row->employee_code; ?></td>
                <td class="border-full" align="center"><?= $row->employee_name; ?></td>
				<td class="border-left border-top border-bottom" align="right" width="3%">Rp.</td>
				<td class="border-right border-top border-bottom" align="right" width="13%"><?= number_format($row->meal_allowance,2) ?></td>
				<td class="border-left border-top border-bottom" align="right" width="3%">Rp.</td>
				<td class="border-right border-top border-bottom" align="right" width="13%"><?= number_format($row->transport_allowance,2) ?></td>
				<td class="border-left border-top border-bottom" align="right" width="3%">Rp.</td>
				<td class="border-right border-top border-bottom" align="right" width="13%"><?= number_format($row->overtime_allowance,2) ?></td>
				<td class="border-left border-top border-bottom" align="right" width="3%">Rp.</td>
				<td class="border-right border-top border-bottom" align="right" width="13%"><?= number_format($row->allowance_value,2) ?></td>
            </tr>
            <?php $i++; } ?>
			<tr>
				<td style="width:68%;">Terbilang :</td>
				<td align="left" style="width:16%;"><b>SUB TOTAL</b></td>
				<td align="right" width="3%"><b>Rp.</b></td>
				<td align="right" width="13%"><b><?= number_format($dataHeader['grandtotal']) ?></b></td>
			</tr>
			<tr>
				<td style="width:68%;"><b style="text-transform: uppercase;"><?= number_to_words(round($dataHeader['grandtotal'])) ?> RUPIAH</b></td>
				<td class="border-top" align="left" style="width:16%;"><b>GRAND TOTAL</b></td>
				<td class="border-top" align="right" width="3%"><b>Rp.</b></td>
				<td class="border-top" align="right" width="13%"><b><?= number_format($dataHeader['grandtotal']) ?></b></td>
			</tr>
		</table>
		<br><br>
		<table border="0" cellspacing="1" cellpadding="0" style="font-size:9px; width:100%">
			<tr>
				<td style="width:46%" align="left"></td>
				<td style="width:27%" align="center">Disetujui,</td>
				<td style="width:27%" align="center">Dibuat,</td>
			</tr>
			<tr>
				<td style="width:15%" align="left"></td>
				<td style="width:3%" align="center"></td>
				<td style="width:28%" align="left"></td>
			</tr>
			<tr>
				<td style="width:15%" align="left"></td>
				<td style="width:3%" align="center"></td>
				<td style="width:28%" align="left"></td>
			</tr>
			<tr>
				<td style="width:15%" align="left"></td>
				<td style="width:3%" align="center"></td>
				<td style="width:28%" align="left"></td>
			</tr>
			<tr>
				<td style="width:15%" align="left"></td>
				<td style="width:3%" align="center"></td>
				<td style="width:28%" align="left"></td>
			</tr>
			<tr>
				<td style="width:15%" align="left"></td>
				<td style="width:3%" align="center"></td>
				<td style="width:28%" align="left"></td>
			</tr>
			<tr>
				<td style="width:15%" align="left"></td>
				<td style="width:3%" align="center"></td>
				<td style="width:28%" align="left"></td>
			</tr>
			<tr>
				<td style="width:46%" align="left"></td>
				<td style="width:27%" align="center">( &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; )</td>
				<td style="width:27%" align="center">( &nbsp;&nbsp;&nbsp;<?= $dataHeader['user_name']; ?>&nbsp;&nbsp;&nbsp;)</td>
			</tr>
		</table>
    </body>
</html>