<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php?error=unauthorized_access");
    exit;
}

include 'connect.php';
include 'navbar.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-6">

<div class="grid grid-cols-3 gap-6">
    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-2xl font-bold">Total Sells</h2>
        <p class="text-green-600 text-xl">৳ 
            <?php
            $result = $conn->query("SELECT SUM(total_price) AS total_sales FROM sales");
            echo ($row = $result->fetch_assoc()) ? number_format($row['total_sales'], 2) : "0.00";
            ?>
        </p>
    </div>

    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-2xl font-bold">Inventory Status</h2>
        <p class="text-blue-600 text-xl">
            <?php
            $result = $conn->query("SELECT COUNT(*) AS total_products FROM inventory");
            echo ($row = $result->fetch_assoc()) ? $row['total_products'] . " Items" : "0 Items";
            ?>
        </p>
    </div>

    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-2xl font-bold">Recent Orders</h2>
        <p class="text-gray-700 text-xl">
            <?php
            $result = $conn->query("SELECT COUNT(*) AS total_orders FROM sales WHERE sale_date >= CURDATE()");
            echo ($row = $result->fetch_assoc()) ? $row['total_orders'] . " Today" : "0 Orders";
            ?>
        </p>
    </div>
</div>

<div class="mt-6">
    <a href="sell_record.php" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Sells Report</a>
    <a href="inventory.php" class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700">Manage Inventory</a>
    <a href="supply_record.php" class="bg-purple-600 text-white px-6 py-2 rounded hover:bg-purple-700">Suppliers</a>
</div>

</body>
</html>

<?php $conn->close(); ?>