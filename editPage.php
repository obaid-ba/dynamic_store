<?php
include 'connectDB.php';
$id = $_GET['id'];
$sgl = "SELECT * FROM products WHERE id = $id";
$result = $pdo->query($sgl);
$product = $result->fetch(PDO::FETCH_BOTH);
$descriptions = [];
if(!empty($product['description_json'])){
  $decoded = json_decode($product['description_json'], true);
  if ($decoded && isset($decoded['sections'])) {
    $descriptions = $decoded['sections'];
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="./style/addProduct.style.css">
  <title>Edit Product</title>
</head>

<body>
  <div class="goBack">
    <a href="products.php"><i class="fa-solid fa-square-caret-left"></i></a>
  </div>

  <div class="luxury-header">
    <h1><i class="fas fa-gem"></i> Edit Product</h1>
  </div>

  <!-- Formulaire de base du produit -->
  <section class="addProduct-container">
    <form action="editProducts.php?id=<?php echo $product['id']; ?>" method="post" enctype="multipart/form-data">
      <div class="mb-3">
        <label class="form-check-label form-label">Name:</label>
        <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
      </div>

      <div class="mb-3">
        <label class="form-check-label form-label">Category:</label>
        <select name="cat" id="firstCat" class="form-control" style="margin-bottom: 15px;" required>
          <option value="">Select Category</option>
          <option value="informatique">Informatique</option>
          <option value="gaming">Gaming</option>
          <option value="Telephonie">Téléphonie</option>
        </select>
        <select name="cat2" id="secondCat" class="form-control"></select>
      </div>

      <div class="mb-3">
        <label class="form-check-label form-label">Description:</label>
        <textarea class="form-control" rows="5" cols="50" name="des" required><?php echo htmlspecialchars($product['description']); ?></textarea>
      </div>

      <div class="mb-3">
        <label class="form-check-label form-label">Price:</label>
        <input type="number" step="0.01" class="form-control" name="prix" value="<?php echo htmlspecialchars($product['price']); ?>" required>
      </div>

      <div class="mb-3">
        <label class="form-check-label form-label">Current Image:</label>
        <div class="current-image">
          <img src="<?php echo htmlspecialchars($product['url']); ?>" style="width:200px;" alt="Current product image">
        </div>
      </div>

      <div class="mb-3">
        <label class="form-check-label form-label">Change Image (optional):</label>
        <input type="file" class="form-control" name="img" accept="image/*">
        <small class="text-muted">Leave empty to keep current image</small>
      </div>

      <button type="submit" class="btn btn-luxury">Update Product</button>
    </form>
  </section>

  <!-- Section des descriptions personnalisées -->
  <section class="descriptions-section">
    <div class="descriptions-header">
      <h2>Custom Descriptions</h2>
      <button class="btn-add-description" onclick="openAddModal()">
        <i class="fas fa-plus"></i> Add Description
      </button>
    </div>

    <div id="descriptionsContainer">
      <?php if (empty($descriptions)): ?>
        <div class="empty-descriptions">
          <i class="fas fa-box-open"></i>
          <p>No custom descriptions yet. Click "Add Description" to create one.</p>
        </div>
      <?php else: ?>
        <?php foreach ($descriptions as $index => $desc): ?>
          <div class="description-item">
            <div class="description-actions">
              <button class="btn-edit-desc" onclick="openEditModal(<?php echo $index; ?>)" title="Edit">
                <i class="fas fa-edit"></i>
              </button>
              <button class="btn-delete-desc" onclick="deleteDescription(<?php echo $index; ?>, <?php echo $id; ?>)" title="Delete">
                <i class="fas fa-trash"></i>
              </button>
            </div>

            <div class="description-preview <?php echo $desc['type']; ?>">
              <?php if ($desc['type'] === 'style-1'): ?>
                <div>
                  <img src="<?php echo htmlspecialchars($desc['image']); ?>" alt="<?php echo htmlspecialchars($desc['title']); ?>">
                </div>
                <div class="description-info">
                  <span class="style-badge">Image Left</span>
                  <h4><?php echo htmlspecialchars($desc['title']); ?></h4>
                  <p><?php echo htmlspecialchars($desc['content']); ?></p>
                </div>
              <?php elseif ($desc['type'] === 'style-2'): ?>
                <div>
                  <div class="description-info">
                    <span class="style-badge">Image Top</span>
                    <h4><?php echo htmlspecialchars($desc['title']); ?></h4>
                  </div>
                  <img src="<?php echo htmlspecialchars($desc['image']); ?>" alt="<?php echo htmlspecialchars($desc['title']); ?>">
                  <div class="description-info">
                    <p><?php echo htmlspecialchars($desc['content']); ?></p>
                  </div>
                </div>
              <?php elseif ($desc['type'] === 'style-3'): ?>
                <div class="description-info">
                  <span class="style-badge">Image Right</span>
                  <h4><?php echo htmlspecialchars($desc['title']); ?></h4>
                  <p><?php echo htmlspecialchars($desc['content']); ?></p>
                </div>
                <div>
                  <img src="<?php echo htmlspecialchars($desc['image']); ?>" alt="<?php echo htmlspecialchars($desc['title']); ?>">
                </div>
              <?php endif; ?>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </section>

  <!-- Modal pour ajouter une description -->
  <div id="addDescriptionModal" class="modal">
    <div class="modal-content">
      <span class="close-modal" onclick="closeAddModal()">&times;</span>
      <h2 class="modal-title">Add New Description Section</h2>

      <form action="add_description.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="product_id" value="<?php echo $id; ?>">
        
        <div class="form-group">
          <label>Select Style:</label>
          <div class="template-selector">
            <div class="template-option selected" data-style="style-1" onclick="selectAddStyle('style-1')">
              <div>
                <img style="width: 200px; height: 200px;" src="./img/style-1.png" alt="">
              </div>
              <div>Image Left</div>
            </div>
            <div class="template-option" data-style="style-2" onclick="selectAddStyle('style-2')">
              <div>
                <img style="width: 200px; height: 200px;" src="./img/style-3.png" alt="">
              </div>
              <div>Image Top</div>
            </div>
            <div class="template-option" data-style="style-3" onclick="selectAddStyle('style-3')">
              <div>
                <img style="width: 200px; height: 200px;" src="./img/style-2.png" alt="">
              </div>
              <div>Image Right</div>
            </div>
          </div>
          <input type="hidden" name="template_style" id="addSelectedStyle" value="style-1">
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

  <!-- Modal pour éditer une description -->
  <div id="editDescriptionModal" class="modal">
    <div class="modal-content">
      <span class="close-modal" onclick="closeEditModal()">&times;</span>
      <h2 class="modal-title">Edit Description Section</h2>

      <form action="edit_description.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="product_id" value="<?php echo $id; ?>">
        <input type="hidden" name="index" id="editIndex">
        
        <div class="form-group">
          <label>Select Style:</label>
          <div class="template-selector">
            <div class="template-option" data-style="style-1" onclick="selectEditStyle('style-1')">
              <img src="./img/style-1.png" alt="Style 1">
              <div>Image Left</div>
            </div>
            <div class="template-option" data-style="style-2" onclick="selectEditStyle('style-2')">
              <img src="./img/style-3.png" alt="Style 2">
              <div>Image Top</div>
            </div>
            <div class="template-option" data-style="style-3" onclick="selectEditStyle('style-3')">
              <img src="./img/style-2.png" alt="Style 3">
              <div>Image Right</div>
            </div>
          </div>
          <input type="hidden" name="template_style" id="editSelectedStyle">
        </div>

        <div class="form-group">
          <label>Title:</label>
          <input type="text" name="title" id="editTitle" required placeholder="Enter section title...">
        </div>

        <div class="form-group">
          <label>Content:</label>
          <textarea name="content" id="editContent" required placeholder="Enter description content..."></textarea>
        </div>

        <div class="form-group">
          <label>Current Image:</label>
          <div class="current-image">
            <img id="editCurrentImage" src="" alt="Current image">
          </div>
        </div>

        <div class="form-group">
          <label>Change Image (optional):</label>
          <input type="file" name="image" accept="image/*">
          <small class="text-muted">Leave empty to keep current image</small>
        </div>

        <button type="submit" class="btn-submit">Update Description Section</button>
      </form>
    </div>
  </div>

  <script>
    // Données des descriptions pour JavaScript
    const descriptions = <?php echo json_encode($descriptions); ?>;

    // Gestion catégories
    document.getElementById('firstCat').addEventListener('change', function() {
      const selectedValue = this.value;
      const secondCat = document.getElementById('secondCat');
      
      if (selectedValue === 'informatique') {
        secondCat.innerHTML = `
          <option value="Ordinateur Portable">Ordinateur Portable</option>
          <option value="Ordinateur Bureau">Ordinateur Bureau</option>
          <option value="Ecrans">Ecrans</option>
          <option value="Serveurs">Serveurs</option>
        `;
      } else if (selectedValue === 'gaming') {
        secondCat.innerHTML = `
          <option value="Ordinateur Portable Gamer">Ordinateur Portable Gamer</option>
          <option value="Ordinateur De Bureau Gamer">Ordinateur De Bureau Gamer</option>
          <option value="Setup Gaming">Setup Gaming</option>
          <option value="Ecran Gamer">Ecran Gamer</option>
        `;
      } else if (selectedValue === 'Telephonie') {
        secondCat.innerHTML = `
          <option value="Smartphone & Mobile">Smartphone & Mobile</option>
          <option value="Telephone Fixe">Telephone Fixe</option>
          <option value="Smartwatch">Smartwatch</option>
          <option value="Accessoires">Accessoires</option>
        `;
      } else {
        secondCat.innerHTML = '';
      }
    });

    // Pré-sélectionner les catégories
    const currentCategories = <?php echo $product['category']; ?>;
    if (currentCategories && Array.isArray(currentCategories)) {
      document.getElementById('firstCat').value = currentCategories[0] || '';
      document.getElementById('firstCat').dispatchEvent(new Event('change'));
      setTimeout(() => {
        document.getElementById('secondCat').value = currentCategories[1] || '';
      }, 100);
    }

    // Modal Ajouter
    function openAddModal() {
      document.getElementById('addDescriptionModal').classList.add('active');
    }

    function closeAddModal() {
      document.getElementById('addDescriptionModal').classList.remove('active');
    }

    function selectAddStyle(style) {
      document.querySelectorAll('#addDescriptionModal .template-option').forEach(opt => {
        opt.classList.remove('selected');
      });
      document.querySelector(`#addDescriptionModal [data-style="${style}"]`).classList.add('selected');
      document.getElementById('addSelectedStyle').value = style;
    }

    // Modal Éditer
    function openEditModal(index) {
      const desc = descriptions[index];
      
      document.getElementById('editIndex').value = index;
      document.getElementById('editTitle').value = desc.title;
      document.getElementById('editContent').value = desc.content;
      document.getElementById('editSelectedStyle').value = desc.type;
      document.getElementById('editCurrentImage').src = desc.image;
      
      // Sélectionner le style actuel
      document.querySelectorAll('#editDescriptionModal .template-option').forEach(opt => {
        opt.classList.remove('selected');
      });
      document.querySelector(`#editDescriptionModal [data-style="${desc.type}"]`).classList.add('selected');
      
      document.getElementById('editDescriptionModal').classList.add('active');
    }

    function closeEditModal() {
      document.getElementById('editDescriptionModal').classList.remove('active');
    }

    function selectEditStyle(style) {
      document.querySelectorAll('#editDescriptionModal .template-option').forEach(opt => {
        opt.classList.remove('selected');
      });
      document.querySelector(`#editDescriptionModal [data-style="${style}"]`).classList.add('selected');
      document.getElementById('editSelectedStyle').value = style;
    }

    // Supprimer description
    function deleteDescription(index, productId) {
      if (confirm('Are you sure you want to delete this description section?')) {
        const params = new URLSearchParams();
        params.append('product_id', productId);
        params.append('id', index);
        
        fetch('delete_description.php', {
          method: 'POST',
          body: params
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            window.location.reload();
          } else {
            alert('Error deleting section: ' + data.message);
          }
        })
        .catch(err => {
          console.error('Error:', err);
          alert('An error occurred while deleting the description.');
        });
      }
    }

    // Fermer modals en cliquant à l'extérieur
    window.onclick = function(event) {
      const addModal = document.getElementById('addDescriptionModal');
      const editModal = document.getElementById('editDescriptionModal');
      
      if (event.target === addModal) {
        closeAddModal();
      }
      if (event.target === editModal) {
        closeEditModal();
      }
    }
  </script>
</body>
</html>