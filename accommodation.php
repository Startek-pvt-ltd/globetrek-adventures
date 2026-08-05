<?php
$pageTitle = 'Accommodation Options | GlobeTrek Adventures';
$activePage = 'accommodation';
require_once __DIR__ . '/includes/header.php';

$options = [
    ['type' => 'Heritage & Cultural Hotels', 'places' => 'Sigiriya, Kandy, Galle', 'price' => 'From ' . format_lkr(18000), 'details' => 'Comfortable local hotels close to temples, forts, and cultural landmarks.'],
    ['type' => 'Boutique Hill-Country Stays', 'places' => 'Ella and tea country', 'price' => 'From ' . format_lkr(22000), 'details' => 'Small scenic guesthouses with mountain views, breakfast, and easy access to walking trails.'],
    ['type' => 'Safari Lodges', 'places' => 'Yala and southern parks', 'price' => 'From ' . format_lkr(26000), 'details' => 'Nature-focused lodges near park gates with early safari pickup support.'],
    ['type' => 'Beach Resorts', 'places' => 'Mirissa and southern coast', 'price' => 'From ' . format_lkr(24000), 'details' => 'Relaxed coastal resorts near beaches, seafood restaurants, and boat departures.'],
];
?>
<section class="section alt">
    <div class="container">
        <p class="eyebrow">Where you stay</p>
        <h1>Accommodation Options</h1>
        <p>Choose stays that fit the destination: heritage hotels, boutique hill-country rooms, safari lodges, and beach resorts.</p>
        <div class="grid grid-4" style="margin-top: 28px;">
            <?php foreach ($options as $index => $option): ?>
                <?php $images = ['assets/images/sections/accommodation.png', 'assets/images/packages/ella.png', 'assets/images/packages/yala.png', 'assets/images/packages/mirissa.png']; ?>
                <article class="card">
                    <img class="card-image" src="/globetrek_adventures/<?= e($images[$index]) ?>" alt="<?= e($option['type']) ?>">
                    <div class="card-body">
                        <h2><?= e($option['type']) ?></h2>
                        <p><strong>Best for:</strong> <?= e($option['places']) ?></p>
                        <p><?= e($option['details']) ?></p>
                        <p class="price"><?= e($option['price']) ?></p>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
