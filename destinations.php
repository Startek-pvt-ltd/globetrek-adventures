<?php
$pageTitle = 'Sri Lankan Destinations | GlobeTrek Adventures';
$activePage = 'destinations';
require_once __DIR__ . '/includes/header.php';

$stmt = $pdo->query("SELECT destination, title, description, activities, price, duration_days, image_path FROM packages WHERE status = 'active' ORDER BY destination");
$destinations = $stmt->fetchAll();
?>
<section class="section alt">
    <div class="container">
        <p class="eyebrow">Where to go</p>
        <h1>Sri Lankan Destinations</h1>
        <p>Explore culture, beaches, wildlife, and hill-country routes with clear activities and starting prices.</p>
        <div class="grid grid-3" style="margin-top: 28px;">
            <?php foreach ($destinations as $destination): ?>
                <article class="card package-card">
                    <?php if (!empty($destination['image_path'])): ?>
                        <img class="package-image" src="/globetrek_adventures/<?= e($destination['image_path']) ?>" alt="<?= e($destination['destination']) ?>">
                    <?php endif; ?>
                    <div class="card-body">
                        <h2><?= e($destination['destination']) ?></h2>
                        <h3><?= e($destination['title']) ?></h3>
                        <p><?= e($destination['description']) ?></p>
                        <p><strong>Activities:</strong> <?= e($destination['activities']) ?></p>
                        <div class="price-row">
                            <span class="price">From <?= e(format_lkr($destination['price'])) ?></span>
                            <span class="pill"><?= e($destination['duration_days']) ?> days</span>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
