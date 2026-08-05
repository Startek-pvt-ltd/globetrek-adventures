<?php
$pageTitle = 'Package Details | GlobeTrek Adventures';
$activePage = 'packages';
require_once __DIR__ . '/includes/header.php';

$packageId = (int) ($_GET['package_id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM packages WHERE package_id = ? AND status = 'active'");
$stmt->execute([$packageId]);
$package = $stmt->fetch();
if (!$package) {
    http_response_code(404);
    exit('Package not found.');
}

$itineraryStmt = $pdo->prepare('SELECT * FROM itinerary WHERE package_id = ? ORDER BY day_number');
$itineraryStmt->execute([$packageId]);
$itinerary = $itineraryStmt->fetchAll();
?>
<section class="detail-hero">
    <div class="container two-col">
        <div>
            <?php if (!empty($package['image_path'])): ?>
                <img class="detail-image" src="/globetrek_adventures/<?= e($package['image_path']) ?>" alt="<?= e($package['title']) ?>">
            <?php endif; ?>
            <p class="eyebrow"><?= e($package['destination']) ?></p>
            <h1><?= e($package['title']) ?></h1>
            <p><?= e($package['description']) ?></p>
            <div class="meta">
                <span class="pill"><?= e($package['duration_days']) ?> days</span>
                <span class="pill"><?= e($package['accommodation']) ?></span>
                <span class="pill"><?= e($package['transportation']) ?></span>
            </div>
            <p><strong>Activities:</strong> <?= e($package['activities'] ?? 'Guided sightseeing') ?></p>
        </div>
        <div class="panel">
            <h2><?= e(format_lkr($package['price'])) ?></h2>
            <p>Per person. Maximum <?= e($package['max_persons']) ?> travelers.</p>
            <form action="/globetrek_adventures/booking.php" method="get" data-validate>
                <input type="hidden" name="package_id" value="<?= e($package['package_id']) ?>">
                <div class="field"><label for="travel_date">Travel Date</label><input id="travel_date" type="date" name="travel_date" data-future required></div>
                <div class="field"><label for="num_persons">Persons</label><input id="num_persons" type="number" name="num_persons" value="1" min="1" max="<?= e($package['max_persons']) ?>" data-persons data-price="<?= e($package['price']) ?>" data-max="<?= e($package['max_persons']) ?>" required></div>
                <p>Total: <strong data-total-output></strong></p>
                <button type="submit">Book Now</button>
            </form>
        </div>
    </div>
</section>
<section class="section">
    <div class="container two-col">
        <div>
            <p class="eyebrow">Day by day</p>
            <h2>Itinerary</h2>
            <?php foreach ($itinerary as $item): ?>
                <div class="itinerary-item">
                    <h3>Day <?= e($item['day_number']) ?>: <?= e($item['title']) ?></h3>
                    <p><?= e($item['description']) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="panel">
            <h2>Package Includes</h2>
            <p><strong>Accommodation:</strong> <?= e($package['accommodation']) ?></p>
            <p><strong>Transportation:</strong> <?= e($package['transportation']) ?></p>
            <p><strong>Activities:</strong> <?= e($package['activities'] ?? 'Guided sightseeing') ?></p>
            <p><strong>Pricing:</strong> <?= e(format_lkr($package['price'])) ?> per person</p>
            <p><strong>Group size:</strong> Up to <?= e($package['max_persons']) ?> travelers</p>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
