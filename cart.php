<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php?error=unauthorized_access");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shopping Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-10">

<div class="bg-white shadow rounded-lg p-6 w-full mx-auto">
    <h2 class="text-2xl font-bold mb-4">Shopping Cart</h2>

    <table class="w-full table-auto border">
        <thead>
            <tr class="bg-gray-100">
                <th class="px-4 py-2 text-left">Product Name</th>
                <th class="px-4 py-2 text-left">Quantity</th>
                <th class="px-4 py-2 text-left">Unit Price</th>
                <th class="px-4 py-2 text-left">Total Price</th>
                <th class="px-4 py-2 text-left">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($_SESSION['cart'])): ?>
                <?php foreach ($_SESSION['cart'] as $key => $item): ?>
                <tr>
                    <td class="px-4 py-2"><?= htmlspecialchars($item['product_name']); ?></td>
                    <td class="px-4 py-2">
                        <form action="update_cart.php" method="POST">
                            <input type="hidden" name="id" value="<?= $item['id']; ?>">
                            <input type="number" name="quantity" value="<?= $item['quantity']; ?>" min="1" required class="border rounded p-1 w-16 text-center">
                            <button type="submit" class="ml-2 bg-blue-600 text-white px-2 py-1 rounded hover:bg-blue-700">Update</button>
                        </form>
                    </td>
                    <td class="px-4 py-2">৳<?= number_format($item['price_per_unit'], 2); ?></td>
                    <td class="px-4 py-2">৳<?= number_format($item['total_price'], 2); ?></td>
                    <td class="px-4 py-2">
                        <a href="remove_cart.php?id=<?= $item['id']; ?>" class="text-red-600 hover:underline">Remove</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="px-4 py-4 text-center text-gray-500">Cart is empty.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="mt-6">
        <a href="confirm_purchase.php" class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700">Confirm Purchase</a>
        <a href="product_list.php" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Continue Shopping</a>
    </div>
</div>

</body>
</html>