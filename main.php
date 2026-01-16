<?php
include 'connectDB.php';

try {
  $id = $_GET['id'];
  // Get product details
  $stmt = $pdo->prepare("SELECT * FROM products WHERE id = :id");
  $stmt->execute(['id' => $id]);
  $product = $stmt->fetch(PDO::FETCH_ASSOC);

  // Get custom descriptions for this product
  $stmt = $pdo->prepare("SELECT * FROM product_descriptions WHERE product_id = :id ORDER BY display_order ASC, created_at ASC");
  $stmt->execute(['id' => $id]);
  $descriptions = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  die("Database query failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./style/style.css">
  <title>Main Product</title>
</head>

<body>
  <div class="product-card">
    <div class="product-header">
      <div class="product-category"><?php echo $product['category']; ?></div>
      <h1 class="product-name"><?php echo $product['name']; ?></h1>
      <div class="rating">
        ★★★★★ <span>(4.9 out of 5)</span>
      </div>
    </div>

    <!-- Content -->
    <div class="product-content">
      <div class="product-image">
        <div class="image-container">
          <img src="<?php echo $product['url']; ?>" alt="">
        </div>
      </div>

      <div class="product-details">
        <!-- Specifications -->
        <div class="specs-section">
          <h2 class="specs-title">Technical Specifications</h2>
          <p class="description">
            <?php echo $product['description']; ?>
          </p>
        </div>

        <!-- Purchase Section -->
        <div class="purchase-section">
          <div class="quantity-control">
            <div class="quantity-label">Quantity:</div>
            <button class="qty-btn" id="decreaseBtn">-</button>
            <div class="qty-display" id="quantity">1</div>
            <button class="qty-btn" id="increaseBtn">+</button>
          </div>

          <div class="price-section">
            <div class="price">$<span id="price"><?php echo $product['price']; ?></span></div>
            <button class="buy-btn" id="buyBtn">Buy Now</button>
          </div>
        </div>

        <!-- Policies -->
        <div class="policies">
          Free shipping on orders over $100 • 30-day return policy
        </div>
      </div>
    </div>
  </div>
  <div class="custom-description">
    <?php foreach ($descriptions as $desc): ?>
      <div class="description-section description-<?php echo $desc['template_style']; ?>">
        <?php if ($desc['template_style'] == 'style-1'): ?>
          <div class="desc-image-wrapper">
            <img src="<?php echo $desc['image_url']; ?>" alt="<?php echo $desc['title']; ?>" class="desc-image">
          </div>
          <div class="desc-content">
            <h3><?php echo $desc['title']; ?></h3>
            <p><?php echo $desc['content']; ?></p>
            <button class="delete-description"  onclick="deleteDescription(<?php echo $desc['id']; ?>, <?php echo $id; ?>)">Delete</button>
          </div>
        <?php elseif ($desc['template_style'] == 'style-2'): ?>
          <img src="<?php echo $desc['image_url']; ?>" alt="<?php echo $desc['title']; ?>" class="desc-image">
          <div class="desc-content">
            <h3><?php echo $desc['title']; ?></h3>
            <p><?php echo $desc['content']; ?></p>
            <button class="delete-description"  onclick="deleteDescription(<?php echo $desc['id']; ?>, <?php echo $id; ?>)">Delete</button>
          </div>
        <?php elseif ($desc['template_style'] == 'style-3'): ?>
          <div class="desc-content">
            <h3><?php echo $desc['title']; ?></h3>
            <p><?php echo $desc['content']; ?></p>
            <button class="delete-description"  onclick="deleteDescription(<?php echo $desc['id']; ?>,<?php echo $id; ?>)">Delete</button>
          </div>
          <div class="desc-image-wrapper">
            <img src="<?php echo $desc['image_url']; ?>" alt="<?php echo $desc['title']; ?>" class="desc-image">
          </div>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
  </div>
  <div class="add-model">
    <button>+</button>
  </div>
  <div id="descriptionModal" class="modal">
    <div class="modal-content">
      <span class="close-modal" onclick="closeModal()">&times;</span>
      <h2 class="modal-title">Add New Description Section</h2>

      <form id="descriptionForm" action="add_description.php" method="POST" enctype="multipart/form-data" >
        <input type="hidden" name="product_id" value="<?php echo $id; ?>">
        <div class="form-group">
          <label>Select Style:</label>
          <div class="template-selector">
            <div class="template-option selected" data-style="style-1" onclick="selectStyle('style-1')">
              <div>
                <img style="width: 200px; height: 200px;" src="./img/style-1.png" alt="">
              </div>
              <div>Image Left</div>
            </div>
            <div class="template-option" data-style="style-2" onclick="selectStyle('style-2')">
              <div>
                <img style="width: 200px; height: 200px;" src="./img/style-3.png" alt="">
              </div>
              <div>Image Top</div>
            </div>
            <div class="template-option" data-style="style-3" onclick="selectStyle('style-3')">
              <div>
                <img style="width: 200px; height: 200px;" src="./img/style-2.png" alt="">
              </div>
              <div>Image Right</div>
            </div>
          </div>
          <input type="hidden" name="template_style" id="selectedStyle" value="style-1">
        </div>

        <div class="form-group">
          <label>Title:</label>
          <input type="text" name="title" required placeholder="Enter section title...">
        </div>

        <div class="form-group">
          <label>Content:</label>
          <textarea name="content" required placeholder="Enter description content..."></textarea>
        </div>

        <div class="form-group">
          <label>Image:</label>
          <input type="file" name="image" accept="image/*" required>
        </div>

        <button type="submit" class="btn-submit">Add Description Section</button>
      </form>
    </div>
  </div>
  <script src="./script.js"></script>
  <!-- <script>
    function deleteDescription(id) {
      if (confirm('Are you sure you want to delete this description section?')) {
        window.location.href = `delete_description.php?id=${id}&product_id=<?php echo $id; ?>`;
      }
    }
  </script> -->
</body>

</html>