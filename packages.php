<?php
$pageTitle = 'Sri Lankan Tour Packages | GlobeTrek Adventures';
$activePage = 'packages';
require_once __DIR__ . '/includes/header.php';

$q = trim($_GET['q'] ?? '');
$destination = trim($_GET['destination'] ?? '');
$minPrice = $_GET['min_price'] ?? '';
$maxPrice = $_GET['max_price'] ?? '';
$duration = $_GET['duration'] ?? '';
$page = max(1, (int) ($_GET['page'] ?? 1));
$perPage = 6;
$offset = ($page - 1) * $perPage;

$where = ["status = 'active'"];
$params = [];
if ($q !== '') {
    $where[] = '(title LIKE ? OR destination LIKE ? OR activities LIKE ? OR description LIKE ?)';
    $params[] = "%{$q}%";
    $params[] = "%{$q}%";
    $params[] = "%{$q}%";
    $params[] = "%{$q}%";
}
if ($destination !== '') {
    $where[] = 'destination = ?';
    $params[] = $destination;
}
if ($minPrice !== '' && is_numeric($minPrice)) {
    $where[] = 'price >= ?';
    $params[] = $minPrice;
}
if ($maxPrice !== '' && is_numeric($maxPrice)) {
    $where[] = 'price <= ?';
    $params[] = $maxPrice;
}
if ($duration === '1-3') {
    $where[] = 'duration_days BETWEEN 1 AND 3';
} elseif ($duration === '4-7') {
    $where[] = 'duration_days BETWEEN 4 AND 7';
} elseif ($duration === '8+') {
    $where[] = 'duration_days >= 8';
}

$whereSql = implode(' AND ', $where);
$countStmt = $pdo->prepare("SELECT COUNT(*) FROM packages WHERE {$whereSql}");
$countStmt->execute($params);
$totalRows = (int) $countStmt->fetchColumn();
$totalPages = max(1, (int) ceil($totalRows / $perPage));

$stmt = $pdo->prepare("SELECT * FROM packages WHERE {$whereSql} ORDER BY created_at DESC LIMIT {$perPage} OFFSET {$offset}");
$stmt->execute($params);
$packages = $stmt->fetchAll();
$destinations = $pdo->query("SELECT DISTINCT destination FROM packages WHERE status = 'active' ORDER BY destination")->fetchAll();
?>
<section class="section alt">
    <div class="container">
        <div class="layout">
            <aside class="sidebar">
                <h2>Filters</h2>
                <form class="filter-form" method="get" data-validate>
                    <div class="field"><label for="q">Search</label><input id="q" name="q" value="<?= e($q) ?>" placeholder="Search Sri Lankan places"></div>
                    <div class="field">
                        <label for="destination">Destination</label>
                        <select id="destination" name="destination">
                            <option value="">All destinations</option>
                            <?php foreach ($destinations as $row): ?>
                                <option value="<?= e($row['destination']) ?>" <?= $destination === $row['destination'] ? 'selected' : '' ?>><?= e($row['destination']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="two-col">
                        <div class="field"><label for="min_price">Min</label><input id="min_price" name="min_price" type="number" min="0" value="<?= e($minPrice) ?>"></div>
                        <div class="field"><label for="max_price">Max</label><input id="max_price" name="max_price" type="number" min="0" value="<?= e($maxPrice) ?>"></div>
                    </div>
                    <div class="field">
                        <label for="duration">Duration</label>
                        <select id="duration" name="duration">
                            <option value="">Any duration</option>
                            <option value="1-3" <?= $duration === '1-3' ? 'selected' : '' ?>>1-3 Days</option>
                            <option value="4-7" <?= $duration === '4-7' ? 'selected' : '' ?>>4-7 Days</option>
                            <option value="8+" <?= $duration === '8+' ? 'selected' : '' ?>>8+ Days</option>
                        </select>
                    </div>
                    <button type="submit">Apply Filters</button>
                    <a class="btn btn-outline" href="/globetrek_adventures/packages.php" style="margin-top: 10px;">Clear</a>
                </form>
            </aside>
            <div>
                <div class="section-head">
                    <div>
                        <p class="eyebrow">Showing <?= e($totalRows) ?> result<?= $totalRows === 1 ? '' : 's' ?></p>
                        <h1>Sri Lankan Visiting Places</h1>
                    </div>
                </div>
                <div class="grid grid-3">
                    <?php foreach ($packages as $package): ?>
                        <article class="card package-card">
                            <?php if (!empty($package['image_path'])): ?>
                                <img class="package-image" src="/globetrek_adventures/<?= e($package['image_path']) ?>" alt="<?= e($package['title']) ?>">
                            <?php else: ?>
                                <div class="image-placeholder"><?= e($package['destination']) ?></div>
                            <?php endif; ?>
                            <div class="card-body">
                                <h3><?= e($package['title']) ?></h3>
                                <p><?= e(substr($package['description'], 0, 110)) ?>...</p>
                                <div class="meta"><span class="pill"><?= e($package['duration_days']) ?> days</span><span class="pill"><?= e($package['destination']) ?></span></div>
                                <p><strong>Activities:</strong> <?= e($package['activities'] ?? 'Guided sightseeing') ?></p>
                                <div class="price-row">
                                    <span class="price"><?= e(format_lkr($package['price'])) ?></span>
                                    <a class="btn" href="/globetrek_adventures/package-details.php?package_id=<?= e($package['package_id']) ?>">View Details</a>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
                <nav class="pagination">
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <?php $query = array_merge($_GET, ['page' => $i]); ?>
                        <a class="<?= $i === $page ? 'active' : '' ?>" href="?<?= e(http_build_query($query)) ?>"><?= $i ?></a>
                    <?php endfor; ?>
                </nav>
            </div>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
