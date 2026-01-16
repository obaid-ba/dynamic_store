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
  $stmt = $pdo->prepare("SELECT MAX(display_order) as max_order FROM product_descriptions WHERE product_id = ?");
  $stmt->execute([$product_id]);
  $result = $stmt->fetch(PDO::FETCH_BOTH);
  $next_order = ($result['max_order'] ?? 0) + 1;

  // Insert new description
  $stmt = $pdo->prepare("
        INSERT INTO product_descriptions (product_id, title, content, image_url, template_style, display_order) 
        VALUES (?, ?, ?, ?, ?, ?)
    ");

  $stmt->execute([
    $product_id,
    $title,
    $content,
    $image_path,
    $template_style,
    $next_order
  ]);

  // Redirect back to product page
  header("Location: main.php?id=" . $product_id);
  exit;

} catch (PDOException $e) {
  // Delete uploaded image if database insert fails
  if (file_exists($image_path)) {
    unlink($image_path);
  }
  die("Database error: " . $e->getMessage());
}