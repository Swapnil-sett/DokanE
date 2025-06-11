<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php?error=unauthorized_access");
    exit;
}

include 'connect.php';
include 'navbar.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $emp_name = $_POST['emp_name'];
    $emp_phone = $_POST['emp_phone'];
    $emp_position = $_POST['emp_position'];
    $emp_salary = $_POST['emp_salary'];

    if (empty($emp_name) || empty($emp_phone) || empty($emp_position) || empty($emp_salary)) {
        echo "<div class='bg-red-100 text-red-700 p-3 rounded mb-4'>All fields are required.</div>";
    } else {
        $sql = "INSERT INTO employeelists (emp_name, emp_phone, emp_position, emp_salary, created_at, updated_at)
                VALUES (?, ?, ?, ?, NOW(), NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $emp_name, $emp_phone, $emp_position, $emp_salary);

        if ($stmt->execute()) {
            header("Location: employee_list.php");
            exit();
        } else {
            echo "<div class='bg-red-100 text-red-700 p-3 rounded mb-4'>Error: " . $stmt->error . "</div>";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Employee</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-10">

<div class="bg-white shadow-md rounded-lg p-6 max-w-2xl mx-auto">
  <h2 class="text-2xl font-bold mb-6">Add Employee</h2>
  
  <form method="POST">
    <div class="mb-4">
      <label class="block text-gray-700">Name</label>
      <input type="text" name="emp_name" required class="w-full mt-2 p-2 border rounded" placeholder="Enter employee name">
    </div>

    <div class="mb-4">
      <label class="block text-gray-700">Phone Number</label>
      <input type="text" name="emp_phone" required class="w-full mt-2 p-2 border rounded" placeholder="Enter phone number">
    </div>

    <div class="mb-4">
      <label class="block text-gray-700">Position</label>
      <input type="text" name="emp_position" required class="w-full mt-2 p-2 border rounded" placeholder="Enter position">
    </div>

    <div class="mb-4">
      <label class="block text-gray-700">Salary</label>
      <input type="number" name="emp_salary" required class="w-full mt-2 p-2 border rounded" placeholder="Enter salary">
    </div>
    <div class="mt-6">
    <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700">Add Employee</button>
  </form>


    <a href="employee_list.php" class="bg-red-600 text-white px-6 py-2 rounded hover:bg-red-700">Cancel</a>
  </div>
</div>

</body>
</html>

<?php $conn->close(); ?>