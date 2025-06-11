<?php
include 'connect.php';
include 'nav.php';


$sql = "SELECT * FROM products ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Shop Homepage</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-10">

<div class="max-w-7xl mx-auto">
  <h2 class="text-3xl font-bold mb-6 text-center">Browse Products</h2>

  <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
    
    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
        <div class="w-60 h-80 bg-gray-50 p-3 flex flex-col gap-1 rounded-2xl">
          <!-- Placeholder White Image Area -->
          <div class="h-48 bg-white border rounded-xl"></div>
          
          <div class="flex flex-col gap-4">
            <div class="flex flex-row justify-between">
              <div class="flex flex-col">
                <span class="text-xl font-bold"><?php echo htmlspecialchars($row['ProdName']); ?></span>
                <p class="text-xs text-gray-700">ID: <?php echo htmlspecialchars($row['id']); ?></p>
              </div>
              <span class="font-bold text-red-600">৳<?php echo number_format($row['ProdPrice'], 2); ?></span>
            </div>
            <a href="product_details.php?id=<?php echo htmlspecialchars($row['id']); ?>" 
            class="hover:bg-sky-700 text-gray-50 bg-sky-800 py-2 rounded-md block text-center">View Details</a>          
        </div>
        </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p class="text-center text-gray-500">No products available.</p>
    <?php endif; ?>
    
  </div>
</div>

</body>
</html>

<?php $conn->close(); ?>