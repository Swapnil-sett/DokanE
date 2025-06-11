<?php
include 'connect.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

  <div class="bg-white p-8 rounded shadow-md w-full max-w-sm">
    <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Login</h2>
    
    <form class="space-y-4" method="POST" action="login_process.php">
      <div>
        <label for="username" class="block text-gray-700 font-semibold mb-1">Username</label>
        <input type="text" name="username" id="username" required
               class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"/>
      </div>
      
      <div>
        <label for="password" class="block text-gray-700 font-semibold mb-1">Password</label>
        <input type="password" name="password" id="password" required
               class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"/>
      </div>

      <div>
        <input type="submit" value="Log In"
               class="w-full bg-blue-600 text-white font-semibold py-2 rounded hover:bg-blue-700 transition"/>
      </div>
      <div class="mt-2">
        <a href="index.php" class="w-full block text-center bg-red-600 text-white font-semibold py-2 rounded hover:bg-red-700 transition">
          Cancel
        </a>
      </div>

    </form>
  </div>

</body>
</html>
