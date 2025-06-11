<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php?error=unauthorized_access");
    exit;
}

include'connect.php';
include'navbar.php';

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['delname'])) {
    $delname = $_GET['delname'];
    $delmobile = $_GET['delmobile'];
    $delemail = $_GET['delemail'];
    $delcompany = $_GET['delcompany'];
    
    $sql = "INSERT INTO dealers (delname, delmobile, delemail, delcompany, created_at, updated_at)
            VALUES (?, ?, ?, ?, NOW(), NOW())";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $delname, $delmobile, $delemail, $delcompany);

    if ($stmt->execute()) {
        echo "<div class='bg-green-100 text-green-700 p-3 rounded mb-4'>Employee added successfully!</div>";
        header("Location: dealer_list.php");
        exit();
    } else {
        echo "<div class='bg-red-100 text-red-700 p-3 rounded mb-4'>Error: " . $stmt->error . "</div>";
    }

    $stmt->close();
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
  <a href="add_dealer.php" class="text-2xl font-bold mb-6 block hover:text-blue-600">Add New Dealer</a>

  <form method="GET" action="">
    <div class="mb-4">
      <label class="block text-gray-700">Name</label>
      <input type="text" name="delname" required class="w-full mt-2 p-2 border rounded" placeholder="Enter employee name">
    </div>

    <div class="mb-4">
      <label class="block text-gray-700">Phone Number</label>
      <input type="text" name="delmobile" required class="w-full mt-2 p-2 border rounded" placeholder="Enter phone number">
    </div>

    <div class="mb-4">
      <label class="block text-gray-700">Email</label>
      <input type="text" name="delemail" required class="w-full mt-2 p-2 border rounded" placeholder="Enter Email">
    </div>

    <div class="mb-4">
      <label class="block text-gray-700">Company</label>
      <input type="text" name="delcompany" required class="w-full mt-2 p-2 border rounded" placeholder="Company">
    </div>

    <!-- <div class="mb-4">
      <label class="block text-gray-700">Joining Date</label>
      <input type="date" name="joiningDate" required class="w-full mt-2 p-2 border rounded">
    </div> -->
  <div class="mt-6">
    <button type="submit" name="save" class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700">Add Dealer</button>
  </form>

  <div class="mt-6">
    <a href="dealer_list.php" class="bg-red-600 text-white px-6 py-2 rounded hover:bg-red-700">Cancel</a>
  </div>
</div>

</body>
</html>

<?php $conn->close(); ?>
