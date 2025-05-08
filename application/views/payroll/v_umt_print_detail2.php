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
            .bg-sakit {
                background-color: #28a745;
            }
            .bg-izin-cuti {
                background-color: #fbf49d;
            }
            .bg-piket {
                background-color: #bd8edb;
            }
            .bg-cekin {
                background-color: #a5a5a5;
            }
            .bg-telat1 {
                background-color: #c7a644;
            }
            .bg-telat2 {
                background-color: #007bff;
            }
            .bg-lembur {
                background-color: #62c9d9;
            }
            .bg-libur {
                background-color: #ff4557;
            }
            .bg-pulang-cepat {
                background-color: #ffa8c8;
            }
            .bg-mangkir {
                background-color: #5a6268;
            }
            .bg-kosong {
                background-color: #d1d1d1;
            }
            .bg-header {
                background-color: #85c0ff;
            }
		</style>
    </head>
    <body>
		<table cellspacing="0" cellpadding="3" border="0" style="font-size:5.5px; width:100%">
            <?php
                // Tanggal mulai dan tanggal akhir
                $startDatex = new DateTime($from2);
                $endDatex = new DateTime($to2);

                // Menghitung selisih hari
                $intervalx = $startDatex->diff($endDatex);
                $daysx = $intervalx->days;
                $headerCount = $daysx+12;
                $footerCount = $daysx+4;
            ?>
			<tr style="color:#000;">
				<td colspan="26" class="border-full" align="center" style="width:100%;font-size:9px;"><b><?= $company['company_name']; ?></b></td>
			</tr>
			<tr style="color:#000;">
				<td rowspan="2" class="border-full" align="center" style="width:1.5%;line-height: 20px;"><b>#</b></td>
				<td rowspan="2" class="border-full" align="center" style="width:5.5%;line-height: 20px;text-align:center;"><b>NAMA</b></td>
				<?php 
                    $startDate = new DateTime($from2);
                    $endDate = new DateTime($to2);
                    $endDate = $endDate->modify('+1 day'); // Modify end date to include the last day
                    
                    $dCountx = $startDate->diff($endDate);
                    $dCount = (int)$dCountx->days;
                    $tot = 15 - $dCount;
                    $endDaten = $endDate->modify("+$tot day"); // Modify end date to include the last day
                    $interval = new DateInterval('P1D'); // 1-day interval
                    $dateRange = new DatePeriod($startDate, $interval, $endDaten);
                    foreach($dateRange as $date){
                ?>
                <th class="border-full bg-header" align="center" style="width:3.6%;"><?= $date->format("d"); ?></th>
                <?php } ?>
                <th rowspan="2" class="border-full" align="center" style="width:2.1%;line-height: 20px;">Jumlah</th>
                <th rowspan="2" class="border-full" align="center" style="width:3.7%;line-height: 20px;">Nominal UMUT</th>
                <th rowspan="2" class="border-full" align="center" style="width:3.7%;line-height: 20px;">Lembur</th>
                <th rowspan="2" class="border-full" align="center" style="width:3.7%;line-height: 20px;">Total Insentif</th>
                <th rowspan="2" class="border-full" align="center" style="width:3.7%;line-height: 20px;">DLK</th>
                <th rowspan="2" class="border-full" align="center" style="width:3.7%;line-height: 20px;">Rapel</th>
                <th rowspan="2" class="border-full" align="center" style="width:3.7%;line-height: 20px;">Piket</th>
                <th rowspan="2" class="border-full" align="center" style="width:3.7%;line-height: 10px;">Potongan TELAT</th>
                <th rowspan="2" class="border-full" align="center" style="width:3.7%;line-height: 20px;">Total Akhir</th>
                <th rowspan="2" class="border-full" align="center" style="width:3.7%;line-height: 20px;">Paraf</th>
			</tr>
            <tr>
                <?php 
                    foreach($dateRange as $date2){
                        if(strtolower($date2->format("l"))=='monday'){
                            $dayx = 'S';
                        }elseif(strtolower($date2->format("l"))=='tuesday'){
                            $dayx = 'S';
                        }elseif(strtolower($date2->format("l"))=='wednesday'){
                            $dayx = 'R';
                        }elseif(strtolower($date2->format("l"))=='thursday'){
                            $dayx = 'K';
                        }elseif(strtolower($date2->format("l"))=='friday'){
                            $dayx = 'J';
                        }elseif(strtolower($date2->format("l"))=='saturday'){
                            $dayx = 'S';
                        }elseif(strtolower($date2->format("l"))=='sunday'){
                            $dayx = 'M';
                        }else{
                            $dayx = 'X';
                        }
                ?>
                <th class="border-full bg-header" align="center" style="width:3.6%;"><?= $dayx; ?></th>
                <?php } ?>
            </tr>
            <?php 
                $i  = 1;
                $gTotal = 0;
                foreach($dataDetail2 as $k => $v){
                    $gTotal += $v['grand_total'][2];
            ?>
            <tr>
                <td class="border-full" align="center"><?= $i++; ?></td>
                <td class="border-full" align="center"><?= $k; ?></td>
                <?php 
                    foreach($v as $absen):
                    if($absen[1]==2){
                        $coloring = 'bg-sakit';
                    }else if($absen[1]==3 || $absen[1]==4){
                        $coloring = 'bg-izin-cuti';
                    }else if($absen[1]==6){
                        $coloring = 'bg-telat1';
                    }else if($absen[1]==7){
                        $coloring = 'bg-telat2';
                    }else if($absen[1]==8){
                        $coloring = 'bg-cekin';
                    }else if($absen[1]==10){
                        $coloring = 'bg-lembur';
                    }else if($absen[1]==11){
                        $coloring = 'bg-piket';
                    }else if($absen[1]==0){
                        $coloring = 'bg-libur';
                    }else if($absen[1]==90){
                        $coloring = '';
                        // $coloring = 'bg-kosong';
                    }else{
                        $coloring = '';
                    }
                ?>
                    <td class="border-full <?= $coloring; ?>" align="center"><?= $absen[0]; ?></td>
                <?php endforeach; ?>
                <td class="border-full" align="center"></td>
            </tr>
            <?php } ?>
            <tr>
                <td colspan="18" align="left" style="width:66.7%;font-size:9px;">Jakarta, <?= date('d-F-Y', strtotime($dataHeader['created_on'])); ?></td>
                <td colspan="6" class="border-full bg-header" align="center" style="width:25.9%;font-size:9px;"><b>TOTAL UANG MAKAN</b></td>
                <td colspan="2" class="border-full" align="center" style="width:7.4%;font-size:9px;"><b><?= number_format($gTotal); ?></b></td>
            </tr>
		</table>
		<table border="0" cellspacing="1" cellpadding="0" style="font-size:9px; width:100%">
			<tr>
				<td style="width:12%" align="left">Mengajukan,</td>
				<td style="width:7.9%" align="center"></td>
				<td style="width:10%" align="left"><b>Keterangan :</b></td>
                <td style="width:2%" align="center"></td>
				<td style="width:4%" align="left"></td>
                <td style="width:5%" align="center"></td>
                <td style="width:10%" align="center"></td>
                <td style="width:2%" align="center"></td>
                <td style="width:4%" align="center"></td>
			</tr>
			<tr>
				<td style="width:12%" align="left"></td>
				<td style="width:7.9%" align="center"></td>
				<td style="width:10%" align="left">Sakit</td>
				<td style="width:2%" align="center"></td>
				<td class="bg-sakit" style="width:4%" align="left"></td>
                <td style="width:5%" align="center"></td>
                <td style="width:10%" align="left">Izin Pulang Cepat</td>
                <td style="width:2%" align="center"></td>
                <td class="bg-pulang-cepat" style="width:4%" align="center"></td>
			</tr>
			<tr>
				<td style="width:12%" align="left"></td>
                <td style="width:7.9%" align="center"></td>
				<td style="width:10%" align="left">Izin Terlambat & Tidak Masuk</td>
                <td style="width:2%" align="center"></td>
				<td class="bg-izin-cuti" style="width:4%" align="left"></td>
                <td style="width:5%" align="center"></td>
                <td style="width:10%" align="left">Lembur</td>
                <td style="width:2%" align="center"></td>
                <td class="bg-lembur" style="width:4%" align="center"></td>
			</tr>
			<tr>
				<td style="width:12%" align="left"></td>
                <td style="width:7.9%" align="center"></td>
				<td style="width:10%" align="left">Tidak Masuk / Libur</td>
                <td style="width:2%" align="center"></td>
				<td class="bg-libur" style="width:4%" align="left"></td>
                <td style="width:5%" align="center"></td>
                <td style="width:10%" align="left">Tidak Absen</td>
                <td style="width:2%" align="center"></td>
                <td class="bg-mangkir" style="width:4%" align="center"></td>
			</tr>
			<tr>
				<td class="border-bottom" style="width:12%" align="left"><?= $dataHeader['user_name']; ?></td>
                <td style="width:7.9%" align="center"></td>
				<td style="width:10%" align="left">Datang Terlambat 9.16-9.59</td>
                <td style="width:2%" align="center"></td>
				<td class="bg-telat1" style="width:4%" align="left"></td>
                <td style="width:5%" align="center"></td>
                <td style="width:10%" align="left">Piket</td>
                <td style="width:2%" align="center"></td>
                <td class="bg-piket" style="width:4%" align="center"></td>
			</tr>
			<tr>
				<td style="width:12%" align="left">HRD</td>
                <td style="width:7.9%" align="center"></td>
				<td style="width:10%" align="left">Datang Terlambat 10.00</td>
                <td style="width:2%" align="center"></td>
				<td class="bg-telat2" style="width:4%" align="left"></td>
                <td style="width:5%" align="center"></td>
                <td style="width:10%" align="left"></td>
                <td style="width:2%" align="center"></td>
                <td style="width:4%" align="center"></td>
			</tr>
		</table>
    </body>
</html>