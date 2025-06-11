<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php?error=unauthorized_access");
    exit;
}

include 'connect.php';
include 'navbar.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM employeelists WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $employee = $result->fetch_assoc();
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $emp_name = $_POST['emp_name'];
    $emp_phone = $_POST['emp_phone'];
    $emp_position = $_POST['emp_position'];
    $emp_salary = $_POST['emp_salary'];

    $sql = "UPDATE employeelists SET emp_name=?, emp_phone=?, emp_position=?, emp_salary=?, updated_at=NOW() WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssdi", $emp_name, $emp_phone, $emp_position, $emp_salary, $id);

    if ($stmt->execute()) {
        header("Location: employee_list.php");
        exit();
    } else {
        echo "<div class='bg-red-100 text-red-700 p-3 rounded mb-4'>Error updating record: " . $stmt->error . "</div>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Employee</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-10">
<div class="bg-white shadow-md rounded-lg p-6 max-w-2xl mx-auto">
  <h2 class="text-2xl font-bold mb-6">Edit Employee</h2>

  <form method="POST">
    <input type="hidden" name="id" value="<?php echo $employee['id']; ?>">

    <div class="mb-4">
      <label class="block text-gray-700">Name</label>
      <input type="text" name="emp_name" value="<?php echo htmlspecialchars($employee['emp_name']); ?>" required class="w-full mt-2 p-2 border rounded">
    </div>

    <div class="mb-4">
      <label class="block text-gray-700">Phone</label>
      <input type="text" name="emp_phone" value="<?php echo htmlspecialchars($employee['emp_phone']); ?>" required class="w-full mt-2 p-2 border rounded">
    </div>

    <div class="mb-4">
      <label class="block text-gray-700">Position</label>
      <input type="text" name="emp_position" value="<?php echo htmlspecialchars($employee['emp_position']); ?>" required class="w-full mt-2 p-2 border rounded">
    </div>

    <div class="mb-4">
      <label class="block text-gray-700">Salary</label>
      <input type="number" name="emp_salary" value="<?php echo htmlspecialchars($employee['emp_salary']); ?>" required class="w-full mt-2 p-2 border rounded">
    </div>

    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Update Employee</button>
    <a href="employee_list.php" class="ml-4 text-red-600 hover:underline">Cancel</a>
  </form>
</div>
</body>
</html>

<?php $conn->close(); ?>