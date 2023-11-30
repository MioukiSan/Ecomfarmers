<?php
$title = "Sales Report";
require('../fpdf/FPDF.php');
require '../connect.php';
$this_date = 0;
if (isset($_POST['between'])) {
    $start = $_POST['start_date'];
    $end = $_POST['end_date'];
    $query0 = "SELECT p.`ID`, p.`BillingID`, p.`ProductName`, p.`Quantity`, p.`Total`, b.`Fullname`, b.`Email`, b.`Contact`, b.address, b.`Address`, b.`PaymentMethod`, b.`Timestamp` 
    FROM `products` p 
    LEFT JOIN `billing` b ON p.`BillingID` = b.`ID`
    WHERE DATE(b.`Timestamp`) BETWEEN '$start' AND '$end'";
    $queryR = mysqli_query($conn, $query0);
    $totalSales = 0;
} else {
    $this_date = $_POST['this_date'];
    $query0 = "SELECT p.`ID`, p.`BillingID`, p.`ProductName`, p.`Quantity`, p.`Total`, b.`Fullname`, b.`Email`, b.`Contact`, b.address, b.`Address`, b.`PaymentMethod`, b.`Timestamp` 
    FROM `products` p 
    LEFT JOIN `billing` b ON p.`BillingID` = b.`ID`
    WHERE DATE(b.`Timestamp`) = '$this_date'";
    $queryR = mysqli_query($conn, $query0);
    $totalSales = 0;
}


// Check if there are order records
if ($queryR) {
    // Initialize PDF
    $pdf = new FPDF('P', 'mm', 'A4');
    $pdf->AddPage();

    // Set font
    $pdf->SetFont('Arial', 'B', 10);

    // Header
    $pdf->SetFont('Arial', 'B', 15);
    $pdf->Cell(0, 5, 'SAUD', 0, 1, 'C');
    $pdf->SetFont('Arial', '', 8);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 5, 'Sales Report', 0, 1, 'C');
    $pdf->SetFont('Arial', '', 10);
    if (isset($_POST['view'])) {
    $pdf->Cell(0, 5, 'DATE: ' . $start . ' - ' . $end, 0, 1, 'C');
    } else {
        $pdf->Cell(0, 5, 'DATE: ' . $this_date, 0, 1, 'C');
    }
    $pdf->Ln(5);

    // Table header
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->SetFillColor(62, 199, 98);
    $pdf->Cell(32, 10, 'CUSTOMER', 1, 0, 'C', true);
    $pdf->Cell(32, 10, 'ADDRESS', 1, 0, 'C', true);
    $pdf->Cell(35, 10, 'PAYMENT METHOD', 1, 0, 'C', true);
    $pdf->Cell(34, 10, 'PRODUCT NAME', 1, 0, 'C', true);
    $pdf->Cell(30, 10, 'QUANTITY', 1, 0, 'C', true);
    $pdf->Cell(27, 10, 'TOTAL', 1, 1, 'C', true);

    $pdf->SetFillColor(52, 162, 192);
    $pdf->SetFont('Arial', '', 9);

    foreach ($queryR as $row) {
        $totalSales += $row['Total'];
    
        $pdf->Cell(32, 10, $row['Fullname'], 1, 0, 'C');
        $pdf->Cell(32, 10, $row['address'], 1, 0, 'C');
        $pdf->Cell(35, 10, $row['PaymentMethod'], 1, 0, 'C');
        $pdf->Cell(34, 10, $row['ProductName'] . ' kg', 1, 0, 'C');
        $pdf->Cell(30, 10, $row['Quantity'] . ' kilo' , 1, 0, 'C');
        $pdf->Cell(27, 10, number_format($row['Total'], 2), 1, 1, 'R');

    }
    $pdf->SetFillColor(192, 192, 192);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(163, 8, 'Total Sales', 1, 0, 'C', true);
    $pdf->Cell(27, 8, number_format($totalSales, 2), 1, 1, 'R', true);
    $pdf->Ln(5);

    session_start();
    $user_id = $_SESSION['id'];
    $fullnameSQL = "SELECT fullname, usertype FROM account WHERE id = '$user_id'";
    $fullnameResult = mysqli_query($conn, $fullnameSQL);
    $fullnameRow = mysqli_fetch_assoc($fullnameResult);
    $fullname = $fullnameRow['fullname'];
    $type = $fullnameRow['usertype'];
    $now = date('Y-m-d');

    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(0, 2, 'Prepared by: ' . $fullname, 0, 1, 'R');
    $pdf->Cell(0, 5, 'Date Prepared: ' . $now, 0, 1, 'R');
} else {
    // Handle the case where there are no order records
    echo "No order records found.";
}
$pdf->Output();
?>
