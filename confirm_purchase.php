<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php?error=unauthorized_access");
    exit;
}

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header("Location: cart.php?error=Cart is empty");
    exit;
}

$username = $_SESSION['username'];

foreach ($_SESSION['cart'] as $item) {
    $product_id = $item['id'];
    $quantity_needed = $item['quantity'];

    $stmt = $conn->prepare("SELECT stock_quantity FROM inventory WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stock_data = $result->fetch_assoc();
    $stmt->close();

    if ($stock_data['stock_quantity'] < $quantity_needed) {
        header("Location: cart.php?error=Insufficient stock for {$item['product_name']}");
        exit;
    }
}

foreach ($_SESSION['cart'] as $item) {
    $product_id = $item['id'];
    $quantity_sold = $item['quantity'];
    $selling_price = $item['price_per_unit'];
    $total_price = $item['total_price'];

    $stmt = $conn->prepare("UPDATE inventory SET stock_quantity = stock_quantity - ? WHERE id = ?");
    $stmt->bind_param("ii", $quantity_sold, $product_id);
    $stmt->execute();

    $stmt = $conn->prepare("INSERT INTO sales (id, product_name, quantity, price_per_unit, total_price, sale_date, sold_by)
                            VALUES (?, ?, ?, ?, ?, NOW(), ?)");
    $stmt->bind_param("isidss", $product_id, $item['product_name'], $quantity_sold, $selling_price, $total_price, $username);
    $stmt->execute();
}

unset($_SESSION['cart']);

header("Location: cash_memo.php?success=Purchase completed");
exit;
?>