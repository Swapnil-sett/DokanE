<?php
include'connect.php';


if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "DELETE FROM dealers WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: dealer_list.php");
        exit();
    } else {
        echo "Error deleting record: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "No ID specified.";
}

$conn->close();
?>
