<?php
include 'connectDB.php';



$product_id = $_POST['product_id'];
$title = $_POST['title'];
$content = $_POST['content'];
$template_style = $_POST['template_style'];

if ($product_id <= 0 || empty($title) || empty($content)) {
  die("Error: All fields are required");
}

// Handle image upload
$image_path = '';
$upload_success = false;

if (isset($_FILES['image'])) {
  $file_tmp = $_FILES['image']['tmp_name'];
  $file_name = $_FILES['image']['name'];
  $file_size = $_FILES['image']['size'];

  // Get file extension
  $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

  // Create upload directory
  $upload_dir = 'uploads/descriptions/';
  if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0755, true);
  }

  // Generate unique filename
  $new_filename = uniqid('desc_', true) . '.' . $file_ext;
  $upload_path = $upload_dir . $new_filename;

  // Move file
  if (move_uploaded_file($file_tmp, $upload_path)) {
    $image_path = $upload_path;
    $upload_success = true;
  } else {
    die("Error: Failed to upload image");
  }
}

if (!$upload_success) {
  die("Error: Image is required");
}


try {
  $stmt = $pdo->prepare("SELECT description_json FROM products WHERE id = :id");
  $stmt->execute(['id' => $product_id]);
  $product = $stmt->fetch(PDO::FETCH_ASSOC);

  $descriptions_data = [];
  if(!empty($product['description_json'])){
    $description_data = json_decode($product['description_json'], true);
  }

  if (!isset($description_data['sections'])) {
    $description_data['sections'] = [];
  }
  $description_data['sections'][] = [
    'type' => $template_style,
    'title' => $title,
    'content' => $content,
    'image' => $image_path
  ];
  $stmt = $pdo->prepare("UPDATE products SET description_json = ? WHERE id = ?");
  $stmt->execute([json_encode($description_data), $product_id]);
  header('Location: main.php?id=' . $product_id."&style=".$template_style);
  exist;
  
} catch (PDOException $e) {
  // Delete uploaded image if database insert fails
  if (file_exists($image_path)) {
    unlink($image_path);
  }
  die("Database error: " . $e->getMessage());
}