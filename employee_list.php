<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php?error=unauthorized_access");
    exit;
}

include 'connect.php';
include 'navbar.php';

$searchQuery = "";
if (isset($_GET['search'])) {
    $searchQuery = $_GET['search'];
    $sql = "SELECT * FROM employeelists WHERE emp_name LIKE ? OR emp_phone LIKE ? OR emp_position LIKE ?";
    $stmt = $conn->prepare($sql);
    $searchTerm = "%$searchQuery%";
    $stmt->bind_param("sss", $searchTerm, $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
} else {
    $sql = "SELECT * FROM employeelists";
    $result = $conn->query($sql);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Employee List</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-10">

<div class="bg-white shadow rounded-lg p-6">
<div class="flex justify-between items-center mb-4">
    <h2 class="text-2xl font-bold">Employee List</h2>
    <div class="flex space-x-4 ml-auto"> 
        <a href="employee_pdf.php" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Download Employee Data</a>
        <a href="add_employee.php" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Add Employee</a>
    </div>
</div>

  <form method="GET" class="mb-4">
    <input type="text" name="search" placeholder="Search by name, phone, or position..." 
           value="<?php echo htmlspecialchars($searchQuery); ?>" 
           class="p-2 border rounded w-full">
    <button type="submit" class="mt-2 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
      Search
    </button>
  </form>

  <table class="min-w-full table-auto">
    <thead>
      <tr class="bg-gray-100">
        <th class="px-4 py-2 text-left">Employee Name</th>
        <th class="px-4 py-2 text-left">Phone</th>
        <th class="px-4 py-2 text-left">Position</th>
        <th class="px-4 py-2 text-left">Salary</th>
        <th class="px-4 py-2 text-left">Actions</th>
      </tr>
    </thead>
    <tbody class="divide-y divide-gray-200">

    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td class="px-4 py-2"><?php echo htmlspecialchars($row['emp_name']); ?></td>
            <td class="px-4 py-2"><?php echo htmlspecialchars($row['emp_phone']); ?></td>
            <td class="px-4 py-2"><?php echo htmlspecialchars($row['emp_position']); ?></td>
            <td class="px-4 py-2">$<?php echo number_format($row['emp_salary'], 2); ?></td>
            <td class="px-4 py-2">
                <a href="edit_employee.php?id=<?php echo $row['id']; ?>" class="text-blue-600 hover:underline">Edit</a>
                |
                <a href="delete_employee.php?id=<?php echo $row['id']; ?>" class="text-red-600 hover:underline"
                   onclick="return confirm('Are you sure you want to delete this employee?');">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr>
          <td colspan="5" class="px-4 py-4 text-center text-gray-500">No employees found</td>
        </tr>
    <?php endif; ?>

    </tbody>
  </table>
</div>

</body>
</html>

<?php $conn->close(); ?>