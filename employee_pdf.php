
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
        $this->Cell(0, 10, 'Employee List', 0, 1, 'C');
        $this->Ln(5);

        $this->SetFont('Arial', 'B', 12);
        $this->Cell(50, 10, 'Name', 1);
        $this->Cell(40, 10, 'Phone', 1);
        $this->Cell(50, 10, 'Position', 1);
        $this->Cell(50, 10, 'Salary', 1);
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

$sql = "SELECT emp_name, emp_phone, emp_position, emp_salary FROM employeelists";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $pdf->Cell(50, 10, $row['emp_name'], 1);
        $pdf->Cell(40, 10, $row['emp_phone'], 1);
        $pdf->Cell(50, 10, $row['emp_position'], 1);
        $pdf->Cell(50, 10, '$' . number_format($row['emp_salary'], 2), 1);
        $pdf->Ln();
    }
} else {
    $pdf->Cell(0, 10, 'No employee data found.', 1, 1, 'C');
}

$pdf->Output('DokanE_Employee_List.pdf', 'D');
?>
