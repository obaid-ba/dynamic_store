<?php
include 'connectDB.php';

$id = $_GET['id'];
$product_id = $_GET['product_id'];


try {
  // Get image path before deleting
  $stmt = $pdo->prepare("SELECT image_url FROM product_descriptions WHERE id = ?");
  $stmt->execute([$id]);
  $result = $stmt->fetch(PDO::FETCH_ASSOC);
  if ($result) {
    $image_path = $result['image_url'];
    // Delete from database
    $stmt = $pdo->prepare("DELETE FROM product_descriptions WHERE id = ?");
    $stmt->execute([$id]);

    // Delete image file
    if (file_exists($image_path)) {
      unlink($image_path);
    }
  }

  // Redirect back
  header("Location: main.php?id=" . $product_id);
  exit;

} catch (PDOException $e) {
  die("Error: " . $e->getMessage());
}
?>