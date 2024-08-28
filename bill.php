<?php
session_start();
include("connection/database.php");
if (!isset($_SESSION['login_user'])) {
    header("location: index.php");
    exit;
}
$check_login_query = $mysqli->query("select * from user where user_id='{$_SESSION['login_user']}'");
if ($check_login_query->num_rows == 0) {
    header("location: logout.php");
    exit;
}
$id = $_GET['id'];
$booking_query = $mysqli->query("select * from booking where booking_id='{$id}' and user_id='{$_SESSION['login_user']}'");
if ($booking_query->num_rows == 0) {
    header("location: booking.php");
    exit;
}
$booking_result = $booking_query->fetch_assoc();
require_once('./pdf/config/tcpdf_config.php');
require_once('./pdf/tcpdf.php');
require_once('./pdf/lang/tha.php');
require_once('./bahttext.php');

class MYPDF extends TCPDF {
	public function ColoredTable($header, $data, $net_total) {
		// Colors, line width and bold font
		$this->setFillColor(27, 26, 85);
		$this->setTextColor(255);
		$this->setDrawColor(27, 26, 85);
		$this->setLineWidth(0.2);
		$this->setFont(PDF_FONT_NAME_MAIN, 'B');
		// Header
		$w = array(10, 100, 25, 20, 25);
		$num_headers = count($header);
		for($i = 0; $i < $num_headers; ++$i) {
			$this->Cell($w[$i], 8, $header[$i], 1, 0, 'C', 1);
		}
		$this->Ln();

		$this->setFillColor(255, 255, 255);
		$this->setTextColor(0);
		$this->setFont(PDF_FONT_NAME_DATA);

		foreach($data as $row) {
			$this->Cell($w[0], 6, $row[0], 'LR', 0, 'C');
			$this->Cell($w[1], 6, $row[1], 'LR', 0, 'L');
			$this->Cell($w[2], 6, $row[2], 'LR', 0, 'R');
			$this->Cell($w[3], 6, $row[3], 'LR', 0, 'C');
			$this->Cell($w[4], 6, $row[4], 'LR', 0, 'R');
			$this->Ln();
		}
		//$this->Cell(array_sum($w), 0, '', 'T');

		$this->setFont(PDF_FONT_NAME_MAIN, 'B');

		$this->Cell($w[0] + $w[1] + $w[2], 10, bahtText($net_total), 1, 0, 'C');

		$this->setFillColor(27, 26, 85);
		$this->setTextColor(255);
		$this->Cell($w[3], 10, 'รวมเงิน', 1, 0, 'C', 1);

		$this->setFillColor(255, 255, 255);
		$this->setTextColor(0);
		$this->Cell($w[4], 10, number_format($net_total,0), 1, 0, 'R');
	}
}

$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf->setCreator(PDF_CREATOR);
$pdf->setAuthor('Chanpalace');
$pdf->setFontSubsetting(true);

$pdf->setTitle('โรงแรมจันทร์พาเลส');
$pdf->setSubject('โรงแรมจันทร์พาเลส 610/7 ถ.ทางรถไฟตะวันตก ต.พระปฐมเจดีย์ อ.เมือง จ.นครปฐม 73000');

$pdf->SetPrintHeader(false);
$pdf->SetPrintFooter(false);

$pdf->setDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->setMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->setAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

$pdf->setLanguageArray($l);

// ---------------------------------------------------------
$pdf->AddPage();

$pdf->setTextColor(0);
$pdf->setDrawColor(255, 255, 255);
$pdf->setLineWidth(0);

$pdf->setFont(PDF_FONT_NAME_MAIN, 'B', 20);
$pdf->Cell(180, 10, 'โรงแรมจันทร์พาเลส', 0, 0, 'L');
$pdf->Ln();
$pdf->setFont(PDF_FONT_NAME_DATA, '', 10);
$pdf->Cell(180, 5, '610/7 ถ.ทางรถไฟตะวันตก ต.พระปฐมเจดีย์ อ.เมือง จ.นครปฐม 73000', 0, 0, 'L');
$pdf->Ln();
$pdf->Cell(180, 5, 'เบอร์โทร 034-363-561, 06-3894-0438', 0, 0, 'L');
$pdf->Ln(7);
$pdf->setDrawColor(0, 0, 0);
$pdf->Cell(180, 0, '', 'T');
$pdf->Ln();
$pdf->Ln();
$pdf->setDrawColor(255, 255, 255);

$pdf->setFont(PDF_FONT_NAME_DATA, '', 10.5);

$booking_date = date_create($booking_result['check_in']);
$date_check_in = date_format($booking_date, "d/m/Y");
$report_date = date_format($booking_date, "Ymd");

$pdf->Cell(130, 8, 'ชื่อลูกค้า ' . $booking_result['name'], 0, 0, 'L');
$pdf->Cell(50, 8, 'วันที่ ' . $date_check_in, 0, 0, 'L');
$pdf->Ln();

$pdf->Cell(75, 8, 'เลขประจำตัว ' . $booking_result['id_card'], 0, 0, 'L');
$pdf->Cell(55, 8, 'เบอร์ติดต่อ ' . $booking_result['phone_no'], 0, 0, 'L');
$pdf->Cell(50, 8, '', 0, 0, 'L');
$pdf->Ln();
$pdf->Ln();

$booking_room_query = $mysqli->query("select * from booking_room
	where booking_id='{$booking_result['booking_id']}' order by booking_room_id asc");
$data = [];
$net_total = 0;
$i = 0;
if ($booking_query->num_rows > 0) {
	$row = 0;
    while ($booking_room_result = $booking_room_query->fetch_assoc()) {
        $room_query = $mysqli->query("select * from room where room_id='{$booking_room_result['room_id']}'");
        $room_result = $room_query->fetch_assoc();
		$net_total += $booking_room_result['total'];
		$row++;
		$date_check_out = date_add($booking_date,date_interval_create_from_date_string($booking_room_result['amount'].($booking_room_result['type'] == "daily" ? " days" : " month")));
		$data[] = [
			$row,
			$room_result['room_name'] . ' (' . ($booking_room_result['type'] == "daily" ? "รายวัน" : "รายเดือน") . ')',
			number_format(($booking_room_result['extra_bed'] > 0 ? $booking_room_result['room_price'] + 250 : $booking_room_result['room_price']),0),
			$booking_room_result['amount'] . ($booking_room_result['type'] == "daily" ? " วัน" : " เดือน"),
			number_format($booking_room_result['total'],0)
		];
		if ($booking_room_result['extra_bed'] > 0) {
			$data[] = [
				'',
				$booking_room_result['type'] == "daily" ? "เตียงเสริม +250 บาท/วัน" : "เตียงเสริม +250 บาท/เดือน",
				'',
				'',
				''
			];
			$i += 1;
		}
		$data[] = [
			'',
			$date_check_in . ' - ' . date_format($date_check_out, "d/m/Y"),
			'',
			'',
			''
		];
		$i += 2;
	}
}
for ($j = $i; $j <= 15; $j++) {
	$data[] = ['', '', '', '', ''];
}
$header = array('ลำดับ', 'รายการ', 'ราคา', 'จำนวน', 'ราคารวม');
$pdf->ColoredTable($header, $data, $net_total);

// ---------------------------------------------------------
$pdf->Output('chanpalace_booking_' . $report_date . '.pdf', 'I');
