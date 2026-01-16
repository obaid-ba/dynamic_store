<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./style/addProduct.style.css">
  <title>Edit Product</title>
</head>

<body>
  <?php
  include 'connectDB.php';
  $id = $_GET['id'];
  $sgl = "SELECT * FROM products WHERE id = $id";
  $result = $pdo->query($sgl);
  $product = $result->fetch(PDO::FETCH_BOTH);
  ?>
  <div class="luxury-header">
    <h1><i class="fas fa-gem"></i> edit product </h1>
  </div>
  <section class="addProduct-container ">
    <form action="editProducts.php?id=<?php echo $product['id']; ?>" method="post">
      <div class="mb-3">
        <label class="form-check-label form-label">Name:</label>
        <input type="text" class="form-control" name="name" value="<?php echo $product['name']; ?>">
      </div>
      <div class="mb-3">
        <label class="form-check-label form-label">category:</label>
        <input type="text" class="form-control" name="cat" value="<?php echo $product['category']; ?>">
      </div>
      <div class="mb-3">
        <label class="form-check-label form-label">description:</label>
        <textarea class="form-control" rows="5" cols="50" name="des"><?php echo $product['description']; ?></textarea>
      </div>
      <div class="mb-3">
        <label class="form-check-label form-label">price:</label>
        <input type="text" class="form-control" name="prix" value="<?php echo $product['price']; ?>">
      </div>
      <button type="submit" class="btn btn-luxury">Submit</button>
    </form>
  </section>
</body>

</html>