<?php
$pageTitle = 'Transportation Services | GlobeTrek Adventures';
$activePage = 'transportation';
require_once __DIR__ . '/includes/header.php';

$services = [
    ['service' => 'Private AC Car', 'route' => 'Airport, Negombo, Kandy, Galle', 'price' => 'From ' . format_lkr(14500) . ' per day', 'details' => 'Comfortable private car for couples and small families.'],
    ['service' => 'Private AC Van', 'route' => 'Island round tours and group transfers', 'price' => 'From ' . format_lkr(22000) . ' per day', 'details' => 'Best for groups, luggage, and multi-stop Sri Lankan itineraries.'],
    ['service' => 'Scenic Train Tickets', 'route' => 'Kandy, Nanu Oya, Ella', 'price' => 'From ' . format_lkr(4500) . ' per person', 'details' => 'Reserved train seats where available, coordinated with station transfers.'],
    ['service' => 'Safari Jeep', 'route' => 'Yala, Udawalawe, Minneriya', 'price' => 'From ' . format_lkr(18000) . ' per safari', 'details' => 'Park-ready jeep service with driver and early pickup options.'],
];
?>
<section class="section alt">
    <div class="container">
        <p class="eyebrow">How you move</p>
        <h1>Transportation Services</h1>
        <p>Reliable transport options for airport pickups, cultural routes, hill-country rail journeys, safari drives, and coastal transfers.</p>
        <div class="grid grid-4" style="margin-top: 28px;">
            <?php foreach ($services as $index => $service): ?>
                <?php $images = ['assets/images/sections/transportation.png', 'assets/images/packages/ella.png', 'assets/images/packages/yala.png', 'assets/images/packages/galle-fort.png']; ?>
                <article class="card">
                    <img class="card-image" src="/globetrek_adventures/<?= e($images[$index]) ?>" alt="<?= e($service['service']) ?>">
                    <div class="card-body">
                        <h2><?= e($service['service']) ?></h2>
                        <p><strong>Routes:</strong> <?= e($service['route']) ?></p>
                        <p><?= e($service['details']) ?></p>
                        <p class="price"><?= e($service['price']) ?></p>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
