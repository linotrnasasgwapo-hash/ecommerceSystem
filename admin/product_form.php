<?php
/**
 * Admin — Add / Edit Product
 */
$pageTitle = 'Product Form';
require_once __DIR__ . '/includes/admin_header.php';

$editId = (int)($_GET['id'] ?? 0);
$product = null;

if ($editId > 0) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$editId]);
    $product = $stmt->fetch();
    if (!$product) {
        setFlash('error', 'Product not found.');
        redirect(baseUrl('admin/products.php'));
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name        = trim($_POST['name'] ?? '');
    $categoryId  = (int)($_POST['category_id'] ?? 0);
    $description = trim($_POST['description'] ?? '');
    $price       = (float)($_POST['price'] ?? 0);
    $stock       = (int)($_POST['stock'] ?? 0);
    
    $uploadedImagePath = '';
    if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../assets/img/products/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $fileExt = strtolower(pathinfo($_FILES['image_file']['name'], PATHINFO_EXTENSION));
        $allowedExts = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        if (in_array($fileExt, $allowedExts)) {
            $newFileName = uniqid('prod_') . '.' . $fileExt;
            $destination = $uploadDir . $newFileName;
            if (move_uploaded_file($_FILES['image_file']['tmp_name'], $destination)) {
                $uploadedImagePath = baseUrl("assets/img/products/" . $newFileName);
            }
        } else {
            setFlash('error', 'Invalid image file type. Allowed: jpg, png, webp, gif.');
            redirect(baseUrl('admin/product_form.php' . ($editId > 0 ? '?id=' . $editId : '')));
        }
    }

    $image = $uploadedImagePath ?: trim($_POST['image'] ?? '');

    if (empty($name) || $categoryId <= 0 || $price <= 0) {
        setFlash('error', 'Please fill in all required fields.');
    } else {
        if ($editId > 0) {
            $stmt = $pdo->prepare("UPDATE products SET name=?, category_id=?, description=?, price=?, stock=?, image=? WHERE id=?");
            $stmt->execute([$name, $categoryId, $description, $price, $stock, $image, $editId]);
            setFlash('success', 'Product updated.');
        } else {
            $stmt = $pdo->prepare("INSERT INTO products (name, category_id, description, price, stock, image) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$name, $categoryId, $description, $price, $stock, $image]);
            setFlash('success', 'Product added.');
        }
        redirect(baseUrl('admin/products.php'));
    }
}

$categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();
?>

<div class="admin-header">
    <h1><?= $editId ? 'Edit Product' : 'Add Product' ?></h1>
    <a href="<?= baseUrl('admin/products.php') ?>" class="btn btn-outline btn-sm">
        <i class="fas fa-arrow-left"></i> Back
    </a>
</div>

<div class="admin-form-card">
    <form method="POST" enctype="multipart/form-data" data-validate>
        <div class="form-group">
            <label for="name">Product Name *</label>
            <input type="text" id="name" name="name" class="form-control" value="<?= sanitize($product['name'] ?? '') ?>" required>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="category_id">Category *</label>
                <select id="category_id" name="category_id" class="form-control" required>
                    <option value="">Select Category</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= ($product['category_id'] ?? 0) == $cat['id'] ? 'selected' : '' ?>>
                            <?= sanitize($cat['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="price">Price ($) *</label>
                <input type="number" id="price" name="price" class="form-control" step="0.01" min="0.01" value="<?= $product['price'] ?? '' ?>" required>
            </div>
        </div>

        <div class="form-group">
            <label for="stock">Stock Quantity</label>
            <input type="number" id="stock" name="stock" class="form-control" min="0" value="<?= $product['stock'] ?? 0 ?>">
        </div>

        <div class="form-group">
            <label for="image_file">Upload Image file</label>
            <input type="file" id="image_file" name="image_file" class="form-control" accept="image/*">
            <small style="color: var(--text-muted); display: block; margin-top: 4px;">OR</small>
        </div>

        <div class="form-group">
            <label for="image">Image URL</label>
            <input type="url" id="image" name="image" class="form-control" placeholder="https://..." value="<?= sanitize($product['image'] ?? '') ?>">
            <?php if (!empty($product['image'])): ?>
                <div style="margin-top: 10px;">
                    <img src="<?= sanitize($product['image']) ?>" alt="Current Image" style="height: 60px; border-radius: 4px; border: 1px solid var(--border-color);">
                </div>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" class="form-control"><?= sanitize($product['description'] ?? '') ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> <?= $editId ? 'Update Product' : 'Add Product' ?>
        </button>
    </form>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
