<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php?error=unauthorized_access");
    exit;
}

include 'connect.php';
include 'navbar.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_name = $_POST['product_name'];
    // $category = $_POST['category'];
    // $subcategory = $_POST['subcategory'];
    $quantity_supplied = $_POST['quantity_supplied'];
    $supplier_name = $_POST['supplier_name'];
    $supply_date = $_POST['supply_date'];
    $received_by = $_POST['received_by'];

    if (empty($product_name) || empty($quantity_supplied) || empty($supplier_name) || empty($supply_date) || empty($received_by)) {
        echo "<div class='bg-red-100 text-red-700 p-3 rounded mb-4'>All fields are required.</div>";
    } else {
        $sql = "INSERT INTO supplies (product_name, quantity_supplied, supplier_name, supply_date, received_by, created_at, updated_at)
                VALUES (?, ?, ?, ?, ?, NOW(), NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sisss", $product_name, $quantity_supplied, $supplier_name, $supply_date, $received_by);

        if ($stmt->execute()) {
            
            $updateSQL = "INSERT INTO inventory (product_name, stock_quantity, supplier_name)
                          VALUES (?, ?, ?)
                          ON DUPLICATE KEY UPDATE stock_quantity = stock_quantity + VALUES(stock_quantity), last_updated = NOW()";
            $updateStmt = $conn->prepare($updateSQL);
            $updateStmt->bind_param("sii", $product_name, $quantity_supplied, $supplier_name);
            $updateStmt->execute();
            $updateStmt->close();

            header("Location: supply_record.php?message=Stock received successfully.");
            exit();
        } else {
            echo "<div class='bg-red-100 text-red-700 p-3 rounded mb-4'>Error: " . $stmt->error . "</div>";
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Receive Stock</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-10">

<div class="bg-white shadow-md rounded-lg p-6 max-w-2xl mx-auto">
  <h2 class="text-2xl font-bold mb-6">Receive Stock</h2>
  
  <form method="POST">
    <div class="mb-4">
      <label class="block text-gray-700">Product Name</label>
      <input type="text" name="product_name" required class="w-full mt-2 p-2 border rounded" placeholder="Enter product name">
    </div>

    <!-- <div class="mb-4">
      <label class="block text-gray-700">Category</label>
      <input type="text" name="category" required class="w-full mt-2 p-2 border rounded" placeholder="Enter category">
    </div>

    <div class="mb-4">
      <label class="block text-gray-700">Subcategory</label>
      <input type="text" name="subcategory" required class="w-full mt-2 p-2 border rounded" placeholder="Enter subcategory">
    </div> -->

    <div class="mb-4">
      <label class="block text-gray-700">Quantity Supplied</label>
      <input type="number" name="quantity_supplied" required class="w-full mt-2 p-2 border rounded" placeholder="Enter quantity">
    </div>

    <div class="mb-4">
      <label class="block text-gray-700">Total Cost</label>
      <input type="number" name="total_cost" step="0.01" required class="w-full mt-2 p-2 border rounded" placeholder="Enter total cost">
    </div>

    <div class="mb-4">
      <label class="block text-gray-700">Supplier Name</label>
      <input type="text" name="supplier_name" required class="w-full mt-2 p-2 border rounded" placeholder="Enter supplier name">
    </div>

    <div class="mb-4">
      <label class="block text-gray-700">Supply Date</label>
      <input type="date" name="supply_date" required class="w-full mt-2 p-2 border rounded">
    </div>

    <div class="mb-4">
      <label class="block text-gray-700">Received By</label>
      <input type="text" name="received_by" required class="w-full mt-2 p-2 border rounded" placeholder="Enter receiver's name">
    </div>

    <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700">Save Supply Record</button>
  </form>

  <div class="mt-6">
    <a href="supply_record.php" class="bg-red-600 text-white px-6 py-2 rounded hover:bg-red-700">Cancel</a>
  </div>
</div>

</body>
</html>

<?php $conn->close(); ?>