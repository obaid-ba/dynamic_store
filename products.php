<?php
include 'connectDB.php';

try {
  $stmt = $pdo->prepare("SELECT * FROM products");
  $stmt->execute();
  $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  die("Database query failed: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Premium Collection | Luxury Products</title>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link
    href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600&display=swap"
    rel="stylesheet">
  <link rel="stylesheet" href="./style/products.style.css">
</head>

<body>
  <div class="goBack">
    <a href="./home.html"><i class="fa-solid fa-square-caret-left"></i></a>
  </div>
  <header class="luxury-header">
    <h1>ALL the Products</h1>
    <div class="subtitle">Curated Luxury Products</div>
  </header>

  <main class="products-grid">
    <?php if (empty($products)): ?>
      <div class="empty-state">
        <i class="fas fa-gem"></i>
        <h3>No Products Available</h3>
        <p>The collection is currently being curated. Please check back soon.</p>
      </div>
    <?php else: ?>
      <?php foreach ($products as $product): ?>
        <div class="luxury-card">
          <div class="card-image">
            <img src="<?php echo $product['url']; ?>">
          </div>

          <div class="card-content">
            <?php if (isset($product['price'])): ?>
              <div class="price-tag">$<?php echo number_format($product['price'], 2); ?></div>
            <?php endif; ?>

            <h3 class="product-title"><?php echo $product['name']; ?></h3>
            <!-- <?php foreach( json_decode($product['category']) as $cat ): ?>
              <span class="product-category"><?php echo $cat; ?></span>
            <?php endforeach; ?> -->
            <?php
            $categories = json_decode($product['category'], true);
            if (is_array($categories)) {
              foreach ($categories as $cat) {
                echo "<span class='product-category'>" . htmlspecialchars($cat) . "</span>";
              }
            }else {
              echo "<span class='product-category'>".$product['category']."</span>";
            }
            ?>
            <p class="product-description"><?php echo $product['description']; ?></p>

            <div class="card-actions">
              <a href="editPage.php?id=<?php echo $product['id']; ?>" class="btn-luxury btn-edit">
                <i class="fas fa-pen"></i> Edit
              </a>
              <a href="delete.php?id=<?php echo $product['id']; ?>" class="btn-luxury btn-delete">
                <i class="fas fa-trash"></i> Delete
              </a>
              <a href="main.php?id=<?php echo $product['id']; ?>" class="btn-luxury btn-more">
                <i class="fas fa-trash"></i> More
              </a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </main>


</body>

</html>