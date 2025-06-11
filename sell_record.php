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
    $sql = "SELECT * FROM sales WHERE product_name LIKE ? OR customer_name LIKE ?";
    $stmt = $conn->prepare($sql);
    $searchTerm = "%$searchQuery%";
    $stmt->bind_param("ss", $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
} else {
    $sql = "SELECT * FROM sales ORDER BY sale_date DESC";
    $result = $conn->query($sql);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Sales Record</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-10">

<div class="bg-white shadow rounded-lg p-6 w-full mx-auto">
  <div class="flex justify-between items-center mb-4">
    <h2 class="text-2xl font-bold">Sells Record</h2>
    <a href="sells_report.php" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Download Report</a>
  </div>

  <form method="GET" class="mb-4">
    <input type="text" name="search" placeholder="Search by product or customer..." 
           value="<?php echo htmlspecialchars($searchQuery); ?>" 
           class="p-2 border rounded w-full">
    <button type="submit" class="mt-2 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
      Search
    </button>
  </form>

  <table class="w-full table-auto border">
    <thead>
      <tr class="bg-gray-100">
        <th class="px-4 py-2 text-left">Sale Date</th>
        <th class="px-4 py-2 text-left">Product Name</th>
        <th class="px-4 py-2 text-left">Quantity Sold</th>
        <th class="px-4 py-2 text-left">Selling Price</th>
        <th class="px-4 py-2 text-left">Total Price</th>
        <!-- <th class="px-4 py-2 text-left">Customer Name</th> -->
        <th class="px-4 py-2 text-left">Sold By</th>
      </tr>
    </thead>
    <tbody class="divide-y divide-gray-200">
      <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <td class="px-4 py-2"><?php echo date("Y-m-d", strtotime($row['sale_date'])); ?></td>
          <td class="px-4 py-2"><?php echo htmlspecialchars($row['product_name']); ?></td>
          <td class="px-4 py-2"><?php echo htmlspecialchars($row['quantity']); ?></td>
          <td class="px-4 py-2">৳<?php echo number_format($row['price_per_unit'], 2); ?></td>
          <td class="px-4 py-2">৳<?php echo number_format($row['total_price'], 2); ?></td>
          <!-- <td class="px-4 py-2"><?php echo htmlspecialchars($row['customer_name']); ?></td> -->
          <td class="px-4 py-2"><?php echo htmlspecialchars($row['sold_by']); ?></td>
        </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr>
          <td colspan="7" class="px-4 py-4 text-center text-gray-500">No sales records found</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

</body>
</html>

<?php $conn->close(); ?>