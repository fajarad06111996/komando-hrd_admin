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
    <body><table border="0" cellpadding="0" width="100%">
			<tr>
				<td rowspan="5" width="16%">
					<img src="<?= logo_apps(); ?>" alt="" style="width:100px;margin-left:0;">
				</td>
                <td border="0" width="40%">
					<b><?= $company['company_name']; ?></b>
				</td>
			</tr>
			<tr>
				<td border="0" width="40%">
					<span style="font-size:11px;"><?= $company['address']; ?></span>
				</td>
			</tr>
			<tr>
				<td border="0" width="40%">
					<span style="font-size:11px;"></span>
				</td>
			</tr>
			<tr>
				<td border="0" width="40%">
					<span style="font-size:11px;"></span>
				</td>
			</tr>
		</table><table cellspacing="0" cellpadding="5" border="1" style="font-size:15px; width:100%">
			<tr style="color:#000;">
				<td align="center" style="width:100%;"><b style="text-align:center;text-decoration: underline;">Slip Gaji Bulan AGUSTUS 2024</b></td>
			</tr>
		</table>
		<br><br>
		<table cellspacing="0" cellpadding="4" style="font-size:12px; width:100%" border="1">
			<tr>
				<td style="width:100%"><table border="0">
						<tr>
							<td width="17%">ID KARYAWAN</td>
							<td width="3%">:</td>
							<td width="72%"><?= $dataDetail->employee_code; ?></td>
						</tr>
						<tr>
							<td width="17%">NAMA KARYAWAN</td>
							<td width="3%">:</td>
							<td width="72%"><?= $dataDetail->employee_name; ?></td>
						</tr>
						<tr>
							<td width="17%">DEPARTEMEN</td>
							<td width="3%">:</td>
							<td width="72%"><?= $dataDetail->department_name; ?></td>
						</tr>
						<tr>
							<td width="17%">JABATAN</td>
							<td width="3%">:</td>
							<td width="72%"><?= $dataDetail->designation_name; ?></td>
						</tr>
					</table>
				</td>
			</tr>
            <tr>
                <td style="width:100%"><table cellspacing="0" cellpadding="4" border="0">
						<tr>
							<td width="40%"><b>PENERIMAAN</b></td>
							<td width="15%"></td>
							<td width="45%"><b>POTONGAN</b></td>
						</tr>
					</table>
                </td>
            </tr>
            <tr>
                <td style="width:50%"><table cellspacing="0" cellpadding="2" border="0">
						<tr>
							<td width="23%">Gaji Pokok</td>
							<td width="3%">:</td>
							<td width="64%" align="right"><?= empty($dataDetail->basic_salary)?'-':number_format($dataDetail->basic_salary); ?></td>
                            <td width="10%"></td>
						</tr>
						<tr>
							<td width="23%">Tunj. Jabatan</td>
							<td width="3%">:</td>
							<td width="64%" align="right"><?= empty($dataDetail->position_allowance)?'-':number_format($dataDetail->position_allowance); ?></td>
                            <td width="10%"></td>
						</tr>
						<tr>
							<td width="23%">Lembur</td>
							<td width="3%">:</td>
							<td width="64%" align="right">-</td>
                            <td width="10%"></td>
						</tr>
						<tr>
							<td width="23%">Transport</td>
							<td width="3%">:</td>
							<td width="64%" align="right">-</td>
                            <td width="10%"></td>
						</tr>
						<tr>
							<td width="23%">Uang Makan</td>
							<td width="3%">:</td>
							<td width="64%" align="right">-</td>
                            <td width="10%"></td>
						</tr>
						<tr>
							<td width="23%">Bonus</td>
							<td width="3%">:</td>
							<td width="64%" align="right">-</td>
                            <td width="10%"></td>
						</tr>
					</table>
                </td>
                <td style="width:50%"><table cellspacing="0" cellpadding="2" border="0">
                        <tr>
                            <td width="10%"></td>
							<td width="23%"></td>
							<td width="3%"></td>
							<td></td>
						</tr>
						<tr>
                            <td width="10%"></td>
							<td width="23%">BPJS</td>
							<td width="3%">:</td>
							<td width="64%" align="right"><?= empty($dataDetail->bpjs)?'-':number_format($dataDetail->bpjs); ?></td>
						</tr>
						<tr>
                            <td width="10%"></td>
							<td width="23%">JHT</td>
							<td width="3%">:</td>
							<td width="64%" align="right">-</td>
						</tr>
						<tr>
                            <td width="10%"></td>
							<td width="23%">JKK</td>
							<td width="3%">:</td>
							<td width="64%" align="right">-</td>
						</tr>
						<tr>
                            <td width="10%"></td>
							<td width="23%">JKN</td>
							<td width="3%">:</td>
							<td width="64%" align="right">-</td>
						</tr>
						<tr>
                            <td width="10%"></td>
							<td width="23%">PPH 21</td>
							<td width="3%">:</td>
							<td width="64%" align="right">-</td>
						</tr>
					</table>
                </td>
            </tr>
            <tr>
                <td style="width:100%"><table cellspacing="0" cellpadding="4" border="0">
						<tr>
                            <td width="50%"><table cellspacing="0" cellpadding="2" border="0">
                                    <tr>
                                        <td width="50%"><b>TOTAL PENERIMAAN</b></td>
                                        <td width="40%" align="right"><?= empty($dataDetail->allowance_value)?'-':number_format($dataDetail->allowance_value); ?></td>
                                        <td width="10%"></td>
                                    </tr>
                                </table>
                            </td>
							<td width="50%"><table cellspacing="0" cellpadding="2" border="0">
                                    <tr>
                                    <td width="10%"></td>
                                        <td width="40%"><b>TOTAL POTONGAN</b></td>
                                        <td width="50%" align="right">-</td>
                                    </tr>
                                </table>
                            </td>
						</tr>
					</table>
                </td>
            </tr>
        </table>
		<br>
		<table cellspacing="0" cellpadding="4" style="font-size:12px; width:100%" border="0">
            <tr>
                <td width="15%" align="center"><b>THP</b></td>
                <td width="15%" align="right" style="border-bottom: 2px solid #000;"><?= empty($dataDetail->allowance_value)?'-':number_format($dataDetail->allowance_value); ?></td>
            </tr>
		</table>
		<br><br>
		<table border="0" cellspacing="1" cellpadding="0" style="font-size:9px; width:100%">
			<tr>
				<td style="width:27%" align="center">Mengetahui,</td>
				<td style="width:46%" align="left"></td>
				<td style="width:27%" align="center">Diterima Oleh,</td>
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
				<td style="width:27%" align="center">( &nbsp;&nbsp;&nbsp;Syawal Adrevi Putra Purnomo&nbsp;&nbsp;&nbsp; )</td>
				<td style="width:46%" align="left"></td>
				<td style="width:27%" align="center">( &nbsp;&nbsp;&nbsp;<?= $dataDetail->employee_name; ?>&nbsp;&nbsp;&nbsp;)</td>
			</tr>
			<tr>
				<td style="width:27%" align="center">&nbsp;&nbsp;&nbsp;Direktur Utama&nbsp;&nbsp;&nbsp;</td>
				<td style="width:46%" align="left"></td>
				<td style="width:27%" align="center"></td>
			</tr>
		</table>
    </body>
</html>