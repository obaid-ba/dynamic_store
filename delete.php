<?php
include 'connectDB.php';
$id = $_GET['id'];

$sql = "DELETE FROM products WHERE id = $id";

if ($pdo->exec($sql)) {
  header("Location: products.php");
} else {
  echo "Error deleting product.";
}

?>