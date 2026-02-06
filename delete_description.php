<?php
include 'connectDB.php';

$id = $_POST['id'];
$product_id = $_POST['product_id'];


try {
  $stmt =  $pdo->prepare("SELECT description_json FROM products WHERE id = ?");
  $stmt->execute([$product_id]);
  $product = $stmt->fetch(PDO::FETCH_BOTH);

  if (!$product || empty($product['description_json'])) {
    die(json_encode(['success' => false, 'message' => 'Product not found']));
  }
  $description_data = json_decode($product['description_json'], true);
  $id = (int)$id;
    
  if(!isset($description_data['sections'][$id])){
    die(json_encode(['success' => false, 'message' => 'Section not found']));
  }
  $image_path = $description_data['sections'][$id]['image'];
  array_splice($description_data['sections'], $id, 1);
  $stmt = $pdo->prepare("UPDATE products SET description_json = ? WHERE id = ?");
  $stmt->execute([
    json_encode($description_data),
    $product_id
  ]);
  if (file_exists($image_path)) {
    unlink($image_path);
  }
  echo json_encode(['success' => true]);
  exit;
} catch (PDOException $e) {
  echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>