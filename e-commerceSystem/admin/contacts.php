<?php
/**
 * Admin — Contact Inquiries
 */
$pageTitle = 'Contacts';
require_once __DIR__ . '/includes/admin_header.php';

// Handle delete
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM contacts WHERE id = ?");
    $stmt->execute([$id]);
    setFlash('success', 'Contact deleted.');
    redirect(baseUrl('admin/contacts.php'));
}

// Handle mark as read
if (isset($_GET['read'])) {
    $id = (int) $_GET['read'];
    $stmt = $pdo->prepare("UPDATE contacts SET is_read = 1 WHERE id = ?");
    $stmt->execute([$id]);
    redirect(baseUrl('admin/contacts.php'));
}

$contacts = $pdo->query("SELECT * FROM contacts ORDER BY created_at DESC")->fetchAll();
?>

<div class="admin-header">
    <h1>Contact Inquiries</h1>
</div>

<div class="admin-table-wrapper">
    <?php if (empty($contacts)): ?>
        <div class="empty-state" style="padding: 40px;">
            <i class="fas fa-envelope"></i>
            <h2>No inquiries</h2>
        </div>
    <?php else: ?>
    <table class="admin-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Subject</th>
                <th>Message</th>
                <th>Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($contacts as $c): ?>
            <tr style="<?= !$c['is_read'] ? 'font-weight: 600;' : '' ?>">
                <td><?= sanitize($c['name']) ?></td>
                <td><?= sanitize($c['email']) ?></td>
                <td><?= sanitize($c['subject']) ?></td>
                <td>
                    <span title="<?= sanitize($c['message']) ?>">
                        <?= sanitize(mb_strimwidth($c['message'], 0, 60, '...')) ?>
                    </span>
                </td>
                <td><?= date('M d, Y', strtotime($c['created_at'])) ?></td>
                <td>
                    <?php if ($c['is_read']): ?>
                        <span class="status-badge status-delivered">Read</span>
                    <?php else: ?>
                        <span class="status-badge status-pending">New</span>
                    <?php endif; ?>
                </td>
                <td>
                    <div class="table-actions">
                        <?php if (!$c['is_read']): ?>
                            <a href="<?= baseUrl('admin/contacts.php?read=' . $c['id']) ?>" class="action-view">Mark Read</a>
                        <?php endif; ?>
                        <a href="<?= baseUrl('admin/contacts.php?delete=' . $c['id']) ?>" class="action-delete confirm-delete">Delete</a>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
