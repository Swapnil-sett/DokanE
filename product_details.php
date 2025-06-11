<?php
session_start();
include 'connect.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: product_list.php?error=Invalid Product ID");
    exit();
}

$product_id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT ProdName, ProdDesc, ProdPrice, ProdCat, ProdSubcat FROM products WHERE id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();
$stmt->close();

if (!$product) {
    header("Location: product_list.php?error=Product not found");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Product Details</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-10 flex justify-center items-center min-h-screen">

<div class="bg-white shadow-lg rounded-lg p-6 max-w-md w-full">
    <h2 class="text-3xl font-bold text-gray-800 text-center"><?= htmlspecialchars($product['ProdName']); ?></h2>
    
    <div class="border-t border-gray-200 my-4"></div> <!-- Separator Line -->
    
    <p class="text-gray-700"><strong>Category:</strong> <?= htmlspecialchars($product['ProdCat']); ?></p>
    <p class="text-gray-700"><strong>Subcategory:</strong> <?= htmlspecialchars($product['ProdSubcat']); ?></p>
    <p class="text-gray-700"><strong>Description:</strong> <?= htmlspecialchars($product['ProdDesc']); ?></p>

    <div class="border-t border-gray-200 my-4"></div> <!-- Separator Line -->

    <p class="text-xl text-green-600 font-bold text-center">৳<?= number_format($product['ProdPrice'], 2); ?></p>

    <div class="mt-6 flex justify-center">
        <a href="add_cart.php?id=<?= $product_id; ?>" class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700">Add to Cart</a>
        <a href="product_list.php" class="bg-gray-600 text-white px-6 py-2 rounded hover:bg-gray-700 ml-2">Back to Products</a>
    </div>
</div>

</body>
</html>

<?php $conn->close(); ?>