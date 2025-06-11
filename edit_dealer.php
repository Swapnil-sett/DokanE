<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php?error=unauthorized_access");
    exit;
}

include'connect.php';
include'navbar.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM dealers WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $dealer = $result->fetch_assoc();
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $delname = $_POST['delname'];
    $delmobile = $_POST['delmobile'];
    $delemail = $_POST['delemail'];
    $delcompany = $_POST['delcompany'];

    $sql = "UPDATE dealers SET delname=?, delmobile=?, delemail=?, delcompany=?, updated_at=NOW() WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $delname, $delmobile, $delemail, $delcompany, $id);

    if ($stmt->execute()) {
        header("Location: dealer_list.php");
        exit();
    } else {
        echo "Error updating record: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Dealer</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-10">
<div class="bg-white shadow-md rounded-lg p-6 max-w-2xl mx-auto">
  <h2 class="text-2xl font-bold mb-6">Edit Dealer</h2>
  <form method="POST">
    <input type="hidden" name="id" value="<?php echo $dealer['id']; ?>">

    <div class="mb-4">
      <label class="block text-gray-700">Name</label>
      <input type="text" name="delname" value="<?php echo $dealer['delname']; ?>" required class="w-full mt-2 p-2 border rounded">
    </div>

    <div class="mb-4">
      <label class="block text-gray-700">Phone</label>
      <input type="text" name="delmobile" value="<?php echo $dealer['delmobile']; ?>" required class="w-full mt-2 p-2 border rounded">
    </div>

    <div class="mb-4">
      <label class="block text-gray-700">Email</label>
      <input type="email" name="delemail" value="<?php echo $dealer['delemail']; ?>" required class="w-full mt-2 p-2 border rounded">
    </div>

    <div class="mb-4">
      <label class="block text-gray-700">Company</label>
      <input type="text" name="delcompany" value="<?php echo $dealer['delcompany']; ?>" required class="w-full mt-2 p-2 border rounded">
    </div>

    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Update</button>
    <a href="dealer_list.php" class="ml-4 text-red-600 hover:underline">Cancel</a>
  </form>
</div>
</body>
</html>

<?php $conn->close(); ?>
