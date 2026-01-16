<?php
include 'connectDB.php';
$id = $_GET['id'];
$name = $_POST['name'];
$cat = $_POST['cat'];
$description = $_POST['des'];
$price = $_POST['prix'];
$requet = "UPDATE products SET name = :name, category = :cat, description = :description, price = :price WHERE id = :id";
$sth = $pdo->prepare($requet)->execute([
  ':id' => $id,
  ':name' => $name,
  ':cat' => $cat,
  ':description' => $description,
  ':price' => $price
]);
if ($sth == false) {
  echo "Erreur lors de la modification.";
}
header("Location: products.php");
?>