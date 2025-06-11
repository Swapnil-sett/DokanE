<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php?error=unauthorized_access");
    exit;
}

include'connect.php';
include'navbar.php';

$search = "";
if (isset($_GET['search'])) {
    $search = trim($_GET['search']);
    $stmt = $conn->prepare("SELECT * FROM dealers WHERE delname LIKE ? OR delcompany LIKE ? ORDER BY created_at DESC");
    $like = "%$search%";
    $stmt->bind_param("ss", $like, $like);
} else {
    $stmt = $conn->prepare("SELECT * FROM dealers ORDER BY created_at DESC");
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dealer List</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-10">
<div class="w-full mx-auto bg-white p-6 rounded-lg shadow-md">
  <div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold">Supplier List</h2>
    <a href="add_dealer.php" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Add Supplier</a>
  </div>

  <form method="GET" class="mb-6">
    <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Search by name or company"
           class="w-full p-3 border rounded shadow-sm" />
  </form>

  <div class="overflow-x-auto">
    <table class="min-w-full text-sm text-left border">
      <thead class="bg-gray-200">
        <tr>
          <th class="p-3">Sl.</th>
          <th class="p-3">Name</th>
          <th class="p-3">Phone</th>
          <th class="p-3">Email</th>
          <th class="p-3">Company</th>
          <th class="p-3">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $i = 1;
        while ($row = $result->fetch_assoc()) {
          echo "<tr class='border-t'>";
          echo "<td class='p-3'>{$i}</td>";
          echo "<td class='p-3'>{$row['delname']}</td>";
          echo "<td class='p-3'>{$row['delmobile']}</td>";
          echo "<td class='p-3'>{$row['delemail']}</td>";
          echo "<td class='p-3'>{$row['delcompany']}</td>";
          echo "<td class='p-3'>
                  <a href='edit_dealer.php?id={$row['id']}' class='text-blue-600 hover:underline mr-3'>Edit</a>
                  <a href='delete_dealer.php?id={$row['id']}' onclick=\"return confirm('Are you sure?')\" class='text-red-600 hover:underline'>Delete</a>
                </td>";
          echo "</tr>";
          $i++;
        }
        ?>
      </tbody>
    </table>
  </div>
</div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
