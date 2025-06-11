<?php
session_start();
include 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'], $_POST['purchase'])) {
    $product_id = intval($_POST['id']);
    $quantity = intval($_POST['purchase']);

    if ($quantity <= 0) {
        header("Location: product_list.php?error=Invalid Quantity");
        exit();
    }

    $stmt = $conn->prepare("SELECT ProdName, ProdPrice FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    $stmt->close();

    if ($product) {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        $found = false;
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['id'] == $product_id) {
                $item['quantity'] += $quantity;
                $item['total_price'] = $item['quantity'] * $item['price_per_unit'];
                $found = true;
                break;
            }
        }

        if (!$found) {
            $_SESSION['cart'][] = [
                'id' => $product_id,
                'product_name' => $product['ProdName'],
                'quantity' => $quantity,
                'price_per_unit' => $product['ProdPrice'],
                'total_price' => $quantity * $product['ProdPrice']
            ];
        }

        header("Location: product_list.php?success=Item added to cart");
        exit();
    } else {
        header("Location: product_list.php?error=Product not found");
        exit();
    }
} else {
    header("Location: product_list.php?error=Invalid request");
    exit();
}
?>