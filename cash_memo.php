<?php
require('fpdf/fpdf.php');
include('connect.php');

class PDF extends FPDF
{
    function Header()
    {
        $this->Image('logob&w_rbg.png', 160, 10, 30, 30);

        $this->SetFont('Arial', 'B', 12);
        $this->Cell(100, 6, 'Dokan_E Web Application', 0, 1, 'L');
        $this->SetFont('Arial', '', 11);
        $this->Cell(100, 6, 'Address: Dhaka, Bangladesh', 0, 1, 'L');
        $this->Cell(100, 6, 'Contact: 01234567890', 0, 1, 'L');

        $this->Ln(10);

        $this->SetFont('Arial', 'B', 16);
        $this->Cell(0, 10, 'Cash Memo', 0, 1, 'C');
        $this->Ln(5);

        $this->SetFont('Arial', 'B', 12);
        $this->Cell(30, 10, 'Sale Date', 1);
        $this->Cell(50, 10, 'Product Name', 1);
        $this->Cell(20, 10, 'Quantity', 1);
        $this->Cell(30, 10, 'Unit Price', 1);
        $this->Cell(30, 10, 'Total Price', 1);
        $this->Cell(40, 10, 'Sold By', 1);
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
$pdf->SetFont('Arial', '', 12);

$sql = "SELECT sale_date, product_name, quantity, price_per_unit, total_price, sold_by FROM sales ORDER BY sale_date DESC LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $pdf->Cell(30, 10, date("Y-m-d", strtotime($row['sale_date'])), 1);
        $pdf->Cell(50, 10, $row['product_name'], 1);
        $pdf->Cell(20, 10, $row['quantity'], 1);
        $pdf->Cell(30, 10, '৳' . number_format($row['price_per_unit'], 2), 1);
        $pdf->Cell(30, 10, '৳' . number_format($row['total_price'], 2), 1);
        $pdf->Cell(40, 10, $row['sold_by'], 1);
        $pdf->Ln();
    }
} else {
    $pdf->Cell(0, 10, 'No recent purchase data found.', 1, 1, 'C');
}

$pdf->Output('DokanE_Cash_Memo.pdf', 'D');
?>