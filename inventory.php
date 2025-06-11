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
    $sql = "SELECT * FROM supplies WHERE product_name LIKE ? OR category LIKE ? OR supplier_name LIKE ?";
    $stmt = $conn->prepare($sql);
    $searchTerm = "%$searchQuery%";
    $stmt->bind_param("sss", $searchTerm, $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
} else {
    $sql = "SELECT product_name, category, subcategory, SUM(quantity_supplied) AS total_stock, supplier_name FROM supplies GROUP BY product_name, supplier_name ORDER BY product_name ASC";
    $result = $conn->query($sql);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Inventory</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-10">

<div class="bg-white shadow rounded-lg p-6 w-full mx-auto">
  <div class="flex justify-between items-center mb-4">
    <h2 class="text-2xl font-bold">Inventory</h2>
    <a href="/add_sale.php" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Download report</a>
  </div>

  <!-- Search bar -->
  <form method="GET" class="mb-4">
    <input type="text" name="search" placeholder="Search by product, category, or supplier..." 
           value="<?php echo htmlspecialchars($searchQuery); ?>" 
           class="p-2 border rounded w-full">
    <button type="submit" class="mt-2 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
      Search
    </button>
  </form>

  <table class="w-full table-auto border">
    <thead>
      <tr class="bg-gray-100">
        <th class="px-4 py-2 text-left">Product Name</th>
        <!-- <th class="px-4 py-2 text-left">Category</th>
        <th class="px-4 py-2 text-left">Subcategory</th> -->
        <th class="px-4 py-2 text-left">Total In Stock</th>
        <th class="px-4 py-2 text-left">Supplier Name</th>
      </tr>
    </thead>
    <tbody class="divide-y divide-gray-200">

    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td class="px-4 py-2"><?php echo htmlspecialchars($row['product_name']); ?></td>
            <!-- <td class="px-4 py-2"><?php echo htmlspecialchars($row['category']); ?></td>
            <td class="px-4 py-2"><?php echo htmlspecialchars($row['subcategory']); ?></td> -->
            <td class="px-4 py-2"><?php echo htmlspecialchars($row['total_stock']); ?></td>
            <td class="px-4 py-2"><?php echo htmlspecialchars($row['supplier_name']); ?></td>
        </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr>
          <td colspan="5" class="px-4 py-4 text-center text-gray-500">No inventory records found</td>
        </tr>
    <?php endif; ?>

    </tbody>
  </table>
</div>

</body>
</html>

<?php $conn->close(); ?>