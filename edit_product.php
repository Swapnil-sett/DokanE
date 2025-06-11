<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php?error=unauthorized_access");
    exit;
}

include'connect.php';
include'navbar.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$sql = "SELECT * FROM products WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    die("Product not found");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['ProdName'];
    $size = $_POST['ProdSize'];
    $price = $_POST['ProdPrice'];
    $cat = $_POST['ProdCat'];
    $subcat = $_POST['ProdSubcat'];
    $img = $_POST['ProdImg']; // image URL or path

    $updateSql = "UPDATE products SET ProdName=?, ProdSize=?, ProdPrice=?, ProdCat=?, ProdSubcat=?, ProdImg=? WHERE id=?";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bind_param("ssisssi", $name, $size, $price, $cat, $subcat, $img, $id);

    if ($updateStmt->execute()) {
        header("Location: product_list.php");
        exit();
    } else {
        echo "Update failed: " . $updateStmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Product</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-10">

<div class="max-w-xl mx-auto bg-white shadow-lg rounded-lg p-6">
  <h2 class="text-2xl font-bold mb-6">Edit Product</h2>
  <form method="POST">
    <div class="mb-4">
      <label class="block mb-1 font-semibold">Product Name</label>
      <input type="text" name="ProdName" value="<?= htmlspecialchars($product['ProdName']) ?>" class="w-full border px-3 py-2 rounded" required>
    </div>

    <div class="mb-4">
      <label class="block mb-1 font-semibold">Size</label>
      <input type="text" name="ProdSize" value="<?= htmlspecialchars($product['ProdSize']) ?>" class="w-full border px-3 py-2 rounded">
    </div>

    <div class="mb-4">
      <label class="block mb-1 font-semibold">Price</label>
      <input type="number" step="0.01" name="ProdPrice" value="<?= htmlspecialchars($product['ProdPrice']) ?>" class="w-full border px-3 py-2 rounded" required>
    </div>

    <div class="mb-4">
      <label class="block mb-1 font-semibold">Category</label>
      <input type="text" name="ProdCat" value="<?= htmlspecialchars($product['ProdCat']) ?>" class="w-full border px-3 py-2 rounded">
    </div>

    <div class="mb-4">
      <label class="block mb-1 font-semibold">Subcategory</label>
      <input type="text" name="ProdSubcat" value="<?= htmlspecialchars($product['ProdSubcat']) ?>" class="w-full border px-3 py-2 rounded">
    </div>

    <!-- <div class="mb-4">
      <label class="block mb-1 font-semibold">Image URL / Path</label>
      <input type="text" name="ProdImg" value="<?= htmlspecialchars($product['ProdImg']) ?>" class="w-full border px-3 py-2 rounded">
    </div> -->

    <div class="flex justify-between">
      <a href="product_list.php" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Cancel</a>
      <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Update</button>
    </div>
  </form>
</div>

</body>
</html>
