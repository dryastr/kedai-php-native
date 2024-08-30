<?php
require_once('vendor/tecnickcom/tcpdf/tcpdf.php');
include "proses/connect.php";
date_default_timezone_set('Asia/Jakarta');

$queryDetail = mysqli_query($conn, "SELECT tb_order.*, tb_bayar.*, nama, SUM(harga*jumlah) AS harganya
                                    FROM tb_order
                                    LEFT JOIN tb_user ON tb_user.id = tb_order.pelayan
                                    LEFT JOIN tb_list_order ON tb_list_order.kode_order = tb_order.id_order
                                    LEFT JOIN tb_daftar_menu ON tb_daftar_menu.id = tb_list_order.menu
                                    JOIN tb_bayar ON tb_bayar.id_bayar = tb_order.id_order
                                    GROUP BY tb_order.id_order
                                    ORDER BY tb_order.waktu_order ASC");

$result = mysqli_fetch_all($queryDetail, MYSQLI_ASSOC);

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Kedai');
$pdf->SetTitle('Laporan Semua Order');
$pdf->SetSubject('Laporan Semua Order');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE . ' 006', PDF_HEADER_STRING);

$pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

$pdf->SetFont('helvetica', '', 10);

$pdf->AddPage();

$html = '<h2>Laporan Semua Order</h2>';
$html .= '<table border="1" cellpadding="4">
             <thead>
                 <tr>
                     <th>No</th>
                     <th>Kode Order</th>
                     <th>Waktu Order</th>
                     <th>Waktu Bayar</th>
                     <th>Pelanggan</th>
                     <th>Tempat</th>
                     <th>Total Harga</th>
                     <th>Pelayan</th>
                 </tr>
             </thead>
             <tbody>';

$no = 1;
$totalHargaKeseluruhan = 0;
foreach ($result as $row) {
    $totalHargaKeseluruhan += $row['harganya'];
    $html .= '<tr>
                 <td>' . $no++ . '</td>
                 <td>' . $row['id_order'] . '</td>
                 <td>' . $row['waktu_order'] . '</td>
                 <td>' . $row['waktu_bayar'] . '</td>
                 <td>' . $row['pelanggan'] . '</td>
                 <td>' . $row['tempat'] . '</td>
                 <td>' . number_format($row['harganya'], 0, ',', '.') . '</td>
                 <td>' . $row['nama'] . '</td>
              </tr>';
}

$html .= '<tr>
             <td colspan="6" align="right"><strong>Total</strong></td>
             <td colspan="2"><strong>' . number_format($totalHargaKeseluruhan, 0, ',', '.') . '</strong></td>
          </tr>';

$html .= '</tbody></table>';

$pdf->writeHTML($html, true, false, true, false, '');

$pdf->Output('Laporan_Semua_Order.pdf', 'I');
