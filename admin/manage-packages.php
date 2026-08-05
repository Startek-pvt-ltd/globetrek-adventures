<?php
$pageTitle = 'Manage Packages | GlobeTrek Admin';
require_once __DIR__ . '/../includes/auth.php';
require_login('admin');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $action = $_POST['action'] ?? '';
    if ($action === 'save') {
        $data = [
            trim($_POST['title'] ?? ''),
            trim($_POST['destination'] ?? ''),
            trim($_POST['activities'] ?? ''),
            trim($_POST['description'] ?? ''),
            (int) ($_POST['duration_days'] ?? 1),
            (float) ($_POST['price'] ?? 0),
            trim($_POST['accommodation'] ?? ''),
            trim($_POST['transportation'] ?? ''),
            (int) ($_POST['max_persons'] ?? 1),
            trim($_POST['image_path'] ?? ''),
            $_POST['status'] ?? 'active',
        ];
        if (!empty($_POST['package_id'])) {
            $stmt = $pdo->prepare('UPDATE packages SET title = ?, destination = ?, activities = ?, description = ?, duration_days = ?, price = ?, accommodation = ?, transportation = ?, max_persons = ?, image_path = ?, status = ? WHERE package_id = ?');
            $data[] = (int) $_POST['package_id'];
            $stmt->execute($data);
            set_flash('success', 'Package updated.');
        } else {
            $stmt = $pdo->prepare('INSERT INTO packages (title, destination, activities, description, duration_days, price, accommodation, transportation, max_persons, image_path, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
            $stmt->execute($data);
            set_flash('success', 'Package added.');
        }
    } elseif ($action === 'delete') {
        $stmt = $pdo->prepare('DELETE FROM packages WHERE package_id = ?');
        $stmt->execute([(int) $_POST['package_id']]);
        set_flash('success', 'Package deleted.');
    }
    header('Location: /globetrek_adventures/admin/manage-packages.php');
    exit;
}

$packages = $pdo->query('SELECT * FROM packages ORDER BY created_at DESC')->fetchAll();
require_once __DIR__ . '/../includes/header.php';
?>
<section class="section alt">
    <div class="container">
        <div class="section-head"><div><p class="eyebrow">Admin</p><h1>Manage Packages</h1></div><a class="btn btn-outline" href="/globetrek_adventures/dashboard/admin-dashboard.php">Admin Dashboard</a></div>
        <form class="panel grid grid-3" method="post" data-validate>
            <?= csrf_field() ?><input type="hidden" name="action" value="save">
            <input name="title" placeholder="Title" required>
            <input name="destination" placeholder="Destination" required>
            <input name="activities" placeholder="Activities" required>
            <input type="number" name="duration_days" min="1" placeholder="Days" required>
            <input type="number" step="0.01" name="price" min="0" placeholder="Price" required>
            <input name="accommodation" placeholder="Accommodation" required>
            <input name="transportation" placeholder="Transportation" required>
            <input type="number" name="max_persons" min="1" placeholder="Max persons" required>
            <input name="image_path" placeholder="Image path, e.g. assets/images/packages/sigiriya.png">
            <select name="status"><option value="active">active</option><option value="inactive">inactive</option></select>
            <textarea name="description" placeholder="Description" required></textarea>
            <button type="submit">Add Package</button>
        </form>
        <div class="panel" style="margin-top: 24px; overflow-x: auto;">
            <table><thead><tr><th>Package</th><th>Destination</th><th>Price</th><th>Details</th><th>Status</th><th>Actions</th></tr></thead><tbody>
                <?php foreach ($packages as $package): ?>
                    <?php $formId = 'admin-package-form-' . (int) $package['package_id']; ?>
                    <tr>
                        <td><form id="<?= e($formId) ?>" method="post"><?= csrf_field() ?><input type="hidden" name="action" value="save"><input type="hidden" name="package_id" value="<?= e($package['package_id']) ?>"></form><input form="<?= e($formId) ?>" name="title" value="<?= e($package['title']) ?>"></td>
                        <td><input form="<?= e($formId) ?>" name="destination" value="<?= e($package['destination']) ?>"></td>
                        <td><input form="<?= e($formId) ?>" type="number" step="0.01" name="price" value="<?= e($package['price']) ?>"></td>
                        <td><input form="<?= e($formId) ?>" name="activities" value="<?= e($package['activities'] ?? '') ?>"><input form="<?= e($formId) ?>" type="number" name="duration_days" value="<?= e($package['duration_days']) ?>"><input form="<?= e($formId) ?>" name="accommodation" value="<?= e($package['accommodation']) ?>"><input form="<?= e($formId) ?>" name="transportation" value="<?= e($package['transportation']) ?>"><input form="<?= e($formId) ?>" type="number" name="max_persons" value="<?= e($package['max_persons']) ?>"><input form="<?= e($formId) ?>" name="image_path" value="<?= e($package['image_path'] ?? '') ?>"><textarea form="<?= e($formId) ?>" name="description"><?= e($package['description']) ?></textarea></td>
                        <td><select form="<?= e($formId) ?>" name="status"><option value="active" <?= $package['status'] === 'active' ? 'selected' : '' ?>>active</option><option value="inactive" <?= $package['status'] === 'inactive' ? 'selected' : '' ?>>inactive</option></select></td>
                        <td class="actions"><button form="<?= e($formId) ?>" type="submit">Save</button><form method="post"><?= csrf_field() ?><input type="hidden" name="action" value="delete"><input type="hidden" name="package_id" value="<?= e($package['package_id']) ?>"><button class="btn-outline" type="submit">Delete</button></form></td>
                    </tr>
                <?php endforeach; ?>
            </tbody></table>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
