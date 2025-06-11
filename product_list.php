<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php?error=unauthorized_access");
    exit;
}
?>

<?php
include'connect.php';
include'navbar.php';

if (isset($_GET['delete'])) {
    $deleteId = intval($_GET['delete']);
    $conn->query("DELETE FROM products WHERE id = $deleteId");
    header("Location: product_list.php");
    exit();
}

$search = "";
if (isset($_GET['search'])) {
    $search = $conn->real_escape_string($_GET['search']);
    $sql = "SELECT * FROM products WHERE ProdName LIKE '%$search%' OR ProdCat LIKE '%$search%' OR ProdSubcat LIKE '%$search%'";
} else {
    $sql = "SELECT * FROM products";
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Products List</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <style>
    .table-container {
      max-height: 400px;
      overflow-y: auto;
    }
    .table-container thead th {
      position: sticky;
      top: 0;
      background: white;
      z-index: 10;
    }
  </style>
</head>
<body class="bg-gray-100 p-10">

<div class="bg-white shadow rounded-lg p-6">
  <div class="flex justify-between items-center mb-4">
    <h2 class="text-2xl font-bold">Products</h2>
    
    <div class="flex space-x-4 ml-auto"> 
        <a href="add_product.php" class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700">Add Product</a>
        <a href="cart.php" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Go to cart</a>
    </div>
  </div>

  <form method="GET" class="mb-4">
    <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Search by name/category..." class="p-2 border rounded w-full">
  </form>

  <div class="table-container ">
    <table class="min-w-full table-auto">
      <thead>
        <tr class="bg-gray-100">
          <th class="px-4 py-2 text-left">Product</th>
          <th class="px-4 py-2 text-left">Size</th>
          <th class="px-4 py-2 text-left">Price</th>
          <th class="px-4 py-2 text-left">Category</th>
          <th class="px-4 py-2 text-left">Subcategory</th>
          <th class="px-4 py-2 text-left">Actions</th>
          <th class="px-4 py-2 text-left">Purchase</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-200">
        <?php if ($result->num_rows > 0): ?>
          <?php while($row = $result->fetch_assoc()): ?>
            <tr>
              <td class="flex items-center px-4 py-3">
                <div class="font-bold"><?= htmlspecialchars($row['ProdName']); ?></div>
              </td>
              <td class="px-4 py-3"><?= htmlspecialchars($row['ProdSize']); ?></td>
              <td class="px-4 py-3">৳<?= htmlspecialchars($row['ProdPrice']); ?></td>
              <td class="px-4 py-3"><?= htmlspecialchars($row['ProdCat']); ?></td>
              <td class="px-4 py-3"><?= htmlspecialchars($row['ProdSubcat']); ?></td>
              <td class="px-4 py-3">
                <a href="edit_product.php?id=<?= $row['id']; ?>" class="text-blue-600 hover:underline mr-2">Edit</a>
                <a href="product_list.php?delete=<?= $row['id']; ?>" class="text-red-600 hover:underline" onclick="return confirm('Are you sure to delete this product?')">Delete</a>
              </td>
              <td><form action="add_cart.php" method="POST" class="inline">
                  <input type="hidden" name="id" value="<?= $row['id']; ?>">
                  <input type="number" name="purchase" min="1" required class="border rounded p-1 w-16 text-center" placeholder="Qty">
                  <button type="submit" class="text-green-600 hover:underline ml-2">Add to Cart</button>
                  </form>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="6" class="px-4 py-4 text-center text-gray-500">No products found</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

</body>
</html>
<?php $conn->close(); ?>
