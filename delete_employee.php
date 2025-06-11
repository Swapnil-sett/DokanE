<?php
include 'connect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "DELETE FROM employeelists WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: employee_list.php");
        exit();
    } else {
        echo "<div class='bg-red-100 text-red-700 p-3 rounded mb-4'>Error deleting record: " . $stmt->error . "</div>";
    }
    $stmt->close();
} else {
    echo "<div class='bg-red-100 text-red-700 p-3 rounded mb-4'>No ID specified.</div>";
}

$conn->close();
?>