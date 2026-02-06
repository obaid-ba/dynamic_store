<?php 
include 'connectDB.php'; // Database connection

$product_id = $_POST['product_id'];
$index = $_POST['index'];
$title = $_POST['title'];
$content = $_POST['content'];
$template_style = $_POST['template_style'];

if($product_id <= 0 || $index < 0 || empty($title) || empty($content) || empty($template_style)){
  die('Error: All fields are required');
}
try{
  $stmt = $pdo->prepare("SELECT description_json FROM products WHERE id = ?");
  $stmt->execute([$product_id]);
  $product = $stmt->fetch(PDO::FETCH_ASSOC);
  if (!$product) {
    die("Error: Product not found");
  }
  $description_data = [];
  if (!empty($product['description_json'])) {
    $description_data = json_decode($product['description_json'], true);
  }
  if (!isset($description_data['sections'][$index])) {
    die("Error: Description section not found");
  }
  $image_path = $description_data['sections'][$index]['image'];
  $old_image_path = $image_path;
  if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $file_tmp = $_FILES['image']['tmp_name'];
    $file_name = $_FILES['image']['name'];

    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    // Upload
    $upload_dir = 'uploads/descriptions/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    $new_filename = uniqid('desc_', true) . '.' . $file_ext;
    $upload_path = $upload_dir . $new_filename;

    if (move_uploaded_file($file_tmp, $upload_path)) {
        $image_path = $upload_path;
        
        // delete the old image if itha hiya mawjouda 
        if (!empty($old_image_path) && file_exists($old_image_path) && $old_image_path !== $image_path) {
          unlink($old_image_path);
        }
    } else {
        die("Error: Failed to upload image");
    }
  }
  // Update fileds 
  $description_data['sections'][$index] = [
    'type' => $template_style,
    'title' => $title,
    'content' => $content,
    'image' => $image_path
  ];
  $stmt = $pdo->prepare("UPDATE products SET description_json = ? WHERE id = ?");
  $stmt->execute([
    json_encode($description_data),
    $product_id
  ]);
  header('Location: editPage.php?id=' . $product_id );
  exit;
}catch(PDOException $e){
  if (isset($upload_path) && file_exists($upload_path) && $upload_path !== $old_image_path) {
    unlink($upload_path);
  }
  die("Database error: " . $e->getMessage());
}