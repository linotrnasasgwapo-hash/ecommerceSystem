<?php
/**
 * Admin — Manage Categories
 */
$pageTitle = 'Categories';
require_once __DIR__ . '/includes/admin_header.php';

// Handle Add
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'add') {
    $name = trim($_POST['name'] ?? '');
    $desc = trim($_POST['description'] ?? '');
    if ($name) {
        $stmt = $pdo->prepare("INSERT INTO categories (name, description) VALUES (?, ?)");
        $stmt->execute([$name, $desc]);
        setFlash('success', 'Category added.');
    }
    redirect(baseUrl('admin/categories.php'));
}

// Handle Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'edit') {
    $id   = (int)($_POST['id'] ?? 0);
    $name = trim($_POST['name'] ?? '');
    $desc = trim($_POST['description'] ?? '');
    if ($id > 0 && $name) {
        $stmt = $pdo->prepare("UPDATE categories SET name = ?, description = ? WHERE id = ?");
        $stmt->execute([$name, $desc, $id]);
        setFlash('success', 'Category updated.');
    }
    redirect(baseUrl('admin/categories.php'));
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->execute([$id]);
    setFlash('success', 'Category deleted.');
    redirect(baseUrl('admin/categories.php'));
}

$categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();
$editCat = null;
if (isset($_GET['edit'])) {
    $editId = (int) $_GET['edit'];
    foreach ($categories as $c) {
        if ($c['id'] === $editId) { $editCat = $c; break; }
    }
}
?>

<div class="admin-header">
    <h1>Categories</h1>
</div>

<!-- Add / Edit Form -->
<div class="admin-form-card" style="margin-bottom: 30px;">
    <h2><?= $editCat ? 'Edit Category' : 'Add New Category' ?></h2>
    <form method="POST">
        <input type="hidden" name="action" value="<?= $editCat ? 'edit' : 'add' ?>">
        <?php if ($editCat): ?>
            <input type="hidden" name="id" value="<?= $editCat['id'] ?>">
        <?php endif; ?>
        <div class="form-group">
            <label for="name">Category Name *</label>
            <input type="text" id="name" name="name" class="form-control" value="<?= sanitize($editCat['name'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <input type="text" id="description" name="description" class="form-control" value="<?= sanitize($editCat['description'] ?? '') ?>">
        </div>
        <div style="display: flex; gap: 10px;">
            <button type="submit" class="btn btn-primary btn-sm">
                <i class="fas fa-save"></i> <?= $editCat ? 'Update' : 'Add Category' ?>
            </button>
            <?php if ($editCat): ?>
                <a href="<?= baseUrl('admin/categories.php') ?>" class="btn btn-outline btn-sm">Cancel</a>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- Category List -->
<div class="admin-table-wrapper">
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($categories as $cat): ?>
            <tr>
                <td><?= $cat['id'] ?></td>
                <td><?= sanitize($cat['name']) ?></td>
                <td><?= sanitize($cat['description'] ?? '—') ?></td>
                <td>
                    <div class="table-actions">
                        <a href="<?= baseUrl('admin/categories.php?edit=' . $cat['id']) ?>" class="action-edit">Edit</a>
                        <a href="<?= baseUrl('admin/categories.php?delete=' . $cat['id']) ?>" class="action-delete confirm-delete">Delete</a>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
