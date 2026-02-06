<?php
include 'connectDB.php';
$id = $_GET['id'];
$name = $_POST['name'];
$cat = $_POST['cat'];
$cat2 = $_POST['cat2'];
$description = $_POST['des'];
$price = $_POST['prix'];
$categories_json = json_encode([$cat, $cat2]);
if($id <= 0 || empty($name) || empty($cat) || empty($description) || $price < 0){
  die("Error: All fields are required and price must be positive");
}
try{
  $stmt = $pdo->prepare("SELECT url FROM products WHERE id = ?");
  $stmt->execute([$id]);
  $product = $stmt->fetch(PDO::FETCH_BOTH);
  if (!$product) {
    die("Error: Product not found");
  }
  $image_url = $product['url'];
  $old_image_url = $image_url;
  if (isset($_FILES['img']) && $_FILES['img']['error'] === UPLOAD_ERR_OK) {
    $file_tmp = $_FILES['img']['tmp_name'];
    $file_name = $_FILES['img']['name'];

      $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

      $upload_dir = 'uploads/products/';
      if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
      }
      $new_filename = uniqid('product_', true) . '.' . $file_ext;
      $upload_path = $upload_dir . $new_filename;
      if (move_uploaded_file($file_tmp, $upload_path)) {
        $image_url = $upload_path;
        if (!empty($old_image_url) && file_exists($old_image_url) && $old_image_url !== $image_url) {
          unlink($old_image_url);
        }
      } else {
        die("Error: Failed to upload image");
      }
  }
  $stmt = $pdo->prepare("
    UPDATE products 
    SET name = ?, category = ?, description = ?, price = ?, url = ?
    WHERE id = ?");
  $stmt->execute([
    $name,
    $categories_json,
    $description,
    $price,
    $image_url,
    $id
  ]);
  header("Location: editPage.php?id=" . $id . "&success=product_updated");
  exit;
}catch (PDOException $e) {
  if (isset($upload_path) && file_exists($upload_path) && $upload_path !== $old_image_url) {
      unlink($upload_path);
  }
  die("Database error: " . $e->getMessage());
}
?>