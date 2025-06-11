<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php?error=unauthorized_access");
    exit;
}

include'connect.php';
include'navbar.php';

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['prodName'])) {
    $prodName = $_GET['prodName'];
    $prodDesc = $_GET['prodDesc'];
    $prodImg = $_GET['prodImg'];
    $prodSize = $_GET['prodSize'];
    $prodPrice = $_GET['prodPrice'];
    $prodCat = $_GET['prodCat'];
    $prodSubcat = $_GET['prodSubcat'];

    $sql = "INSERT INTO products (ProdName, ProdDesc, ProdImg, ProdSize, ProdPrice, ProdCat, ProdSubcat, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $prodName, $prodDesc, $prodImg, $prodSize, $prodPrice, $prodCat, $prodSubcat);

    if ($stmt->execute()) {
        echo "<div class='bg-green-100 text-green-700 p-3 rounded mb-4'>Product added successfully!</div>";
        header("Location: product_list.php");
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
  <title>Add Product</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-10">

<div class="bg-white shadow-md rounded-lg p-6 max-w-2xl mx-auto">
  <h2 class="text-2xl font-bold mb-6">Add New Product</h2>

  <form method="GET" action="">
    <div class="mb-4">
      <label class="block text-gray-700">Product Name</label>
      <input type="text" name="prodName" required class="w-full mt-2 p-2 border rounded" placeholder="Enter product name">
    </div>

    <div class="mb-4">
      <label class="block text-gray-700">Product Description</label>
      <textarea name="prodDesc" class="w-full mt-2 p-2 border rounded" placeholder="Enter description"></textarea>
    </div>

    <div class="mb-4">
      <label class="block text-gray-700">Product Image URL</label>
      <input type="text" name="prodImg" required class="w-full mt-2 p-2 border rounded" placeholder="Enter image URL">
    </div>

    <div class="mb-4">
      <label class="block text-gray-700">Product Size</label>
      <input type="text" name="prodSize" required class="w-full mt-2 p-2 border rounded" placeholder="Enter size">
    </div>

    <div class="mb-4">
      <label class="block text-gray-700">Product Price</label>
      <input type="number" step="0.01" name="prodPrice" required class="w-full mt-2 p-2 border rounded" placeholder="Enter price">
    </div>

    <div class="mb-4">
      <label class="block text-gray-700">Product Category</label>
      <input type="text" name="prodCat" required class="w-full mt-2 p-2 border rounded" placeholder="Enter category">
    </div>

    <div class="mb-4">
      <label class="block text-gray-700">Product Subcategory</label>
      <input type="text" name="prodSubcat" required class="w-full mt-2 p-2 border rounded" placeholder="Enter subcategory">
    </div>

    <button type="submit" class="bg-purple-600 text-white px-6 py-2 rounded hover:bg-purple-700">Add Product</button>
  </form>

  <div class="mt-6">
    <a href="product_list.php" class="bg-red-600 text-white px-6 py-2 rounded hover:bg-red-700">cancel</a>
  </div>
</div>

</body>
</html>

<?php $conn->close(); ?>
