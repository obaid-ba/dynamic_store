<?php
include 'connectDB.php';
$name = $_POST['name'];
$cat = $_POST['cat'];
$des = $_POST['des'];
$prix = $_POST['prix'];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = isset($_POST['name']) ? trim($_POST['name']) : '';
  $category = isset($_POST['cat']) ? trim($_POST['cat']) : '';
  $description = isset($_POST['des']) ? trim($_POST['des']) : '';
  $price = isset($_POST['prix']) ? floatval($_POST['prix']) : 0;
  if (empty($name) || empty($category) || empty($description) || $price < 0) {
    die("Error: All fields are required and price must be greater than 0");
  }
  $image_path = '';
  $upload_success = false;

  if (isset($_FILES['img'])) {
    $file_tmp = $_FILES['img']['tmp_name'];
    $file_name = $_FILES['img']['name'];
    $file_size = $_FILES['img']['size'];
    $file_error = $_FILES['img']['error'];
    //  this part is for the image to upload the image 
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    $upload_dir = 'uploads/products/';
    if (!is_dir($upload_dir)) {
      mkdir($upload_dir, 0755, true);
      // 0777 - Everyone has full access (less secure)
      // 0755 - Owner has full access, group can read/enter, others have no access
      // 0700 - Only owner has access
      // true - Enables recursive directory creation
    }
    $new_filename = uniqid('product_', true) . '.' . $file_ext;
    $upload_path = $upload_dir . $new_filename;
    if (move_uploaded_file($file_tmp, $upload_path)) {
      $image_path = $upload_path;
      $upload_success = true;
    } else {
      die("Error: Failed to upload image");
    }
  }
  if ($upload_success) {
    try {
      $stmt = $pdo->prepare("
                INSERT INTO products (name, category, description, price, url, created_at) 
                VALUES (?, ?, ?, ?, ?, NOW())
            ");

      $stmt->execute([
        $name,
        $category,
        $description,
        $price,
        $image_path
      ]);

      $product_id = $pdo->lastInsertId();

      // Success! Redirect to product detail page or show success message
      echo "<!DOCTYPE html>
            <html lang='en'>
            <head>
                <meta charset='UTF-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css' rel='stylesheet'>
                <title>Success</title>
                <style>
                    body {
                        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                        min-height: 100vh;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                    }
                    .success-card {
                        background: white;
                        border-radius: 20px;
                        padding: 40px;
                        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
                        text-align: center;
                        max-width: 500px;
                    }
                    .success-icon {
                        font-size: 80px;
                        color: #28a745;
                        margin-bottom: 20px;
                    }
                    .product-preview {
                        margin: 20px 0;
                        padding: 20px;
                        background: #f8f9fa;
                        border-radius: 10px;
                    }
                    .product-preview img {
                        max-width: 200px;
                        max-height: 200px;
                        border-radius: 10px;
                        margin: 10px 0;
                    }
                </style>
            </head>
            <body>
                <div class='success-card'>
                    <div class='success-icon'>✓</div>
                    <h1>Product Added Successfully!</h1>
                    <div class='product-preview'>
                        <h3>" . $name . "</h3>
                        <img src='" . $image_path . "' alt='Product Image'>
                        <p><strong>Category:</strong> " . $category . "</p>
                        <p><strong>Price:</strong> $" . $price . "</p>
                    </div>
                    <div class='mt-4'>
                        <a href='main.php?id=" . $product_id . "' class='btn btn-primary btn-lg me-2'>View Product</a>
                        <a href='addProduct.html' class='btn btn-secondary btn-lg'>Add Another</a>
                    </div>
                </div>
            </body>
            </html>";

    } catch (PDOException $e) {
      // Delete uploaded image if database insert fails
      if (file_exists($image_path)) {
        unlink($image_path);
      }
      die("Database error: " . $e->getMessage());
    }
  }
}