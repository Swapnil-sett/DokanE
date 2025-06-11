<?php
require('fpdf/fpdf.php');
include('connect.php');

class PDF extends FPDF
{
    function Header()
    {
        $this->Image('logob&w_rbg.png', 160, 10, 30, 30);

        $this->SetFont('Arial', 'B', 12);
        $this->SetXY(10, 23);
        $this->Cell(100, 6, 'Dokan_E Web Application', 0, 1, 'L');
        $this->SetFont('Arial', '', 11);
        $this->Cell(100, 6, 'Address: Dhaka, Bangladesh', 0, 1, 'L');
        $this->Cell(100, 6, 'Contact: 01234567890', 0, 1, 'L');

        $this->Ln(10);

        $this->SetFont('Arial', 'B', 16);
        $this->Cell(0, 10, 'Supply Report', 0, 1, 'C');
        $this->Ln(5);

        $this->SetFont('Arial', 'B', 12);
        $this->Cell(40, 10, 'Product Name', 1);
        $this->Cell(25, 10, 'Quantity', 1);
        $this->Cell(30, 10, 'Total Cost', 1);
        $this->Cell(30, 10, 'Supplier', 1);
        $this->Cell(30, 10, 'Supply Date', 1);
        $this->Cell(30, 10, 'Received By', 1);
        $this->Ln();
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 10);
        $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
    }
}

$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 10);

$sql = "SELECT product_name, quantity_supplied, total_cost, supplier_name, supply_date, received_by FROM supplies ORDER BY supply_date DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $pdf->Cell(40, 10, $row['product_name'], 1);
        $pdf->Cell(25, 10, $row['quantity_supplied'], 1);
        $pdf->Cell(30, 10, '$' . number_format($row['total_cost'], 2), 1);
        $pdf->Cell(30, 10, $row['supplier_name'], 1);
        $pdf->Cell(30, 10, date("Y-m-d", strtotime($row['supply_date'])), 1);
        $pdf->Cell(30, 10, $row['received_by'], 1);
        $pdf->Ln();
    }
} else {
    $pdf->Cell(0, 10, 'No supply data found.', 1, 1, 'C');
}

$pdf->Output('DokanE_Supply_Report.pdf', 'D');
?>