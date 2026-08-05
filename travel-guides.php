<?php
$pageTitle = 'Sri Lanka Travel Guides | GlobeTrek Adventures';
$activePage = 'guides';
require_once __DIR__ . '/includes/header.php';

$guides = [
    ['title' => 'Best Time to Visit', 'body' => 'The south and west coasts are best from December to April. The east coast is usually best from May to September. Hill country can be cooler year-round.'],
    ['title' => 'What to Pack', 'body' => 'Bring light clothing, comfortable walking shoes, sunscreen, modest temple clothing, a reusable water bottle, and a light rain jacket.'],
    ['title' => 'Temple Etiquette', 'body' => 'Cover shoulders and knees, remove shoes and hats, and avoid turning your back to Buddha statues when taking photos.'],
    ['title' => 'Travel Pace', 'body' => 'Sri Lankan roads are scenic but slower than expected, so our packages keep daily travel distances realistic.'],
    ['title' => 'Currency and Payments', 'body' => 'Prices are shown in LKR. Carry some cash for small shops, tips, and rural stops, while hotels often accept cards.'],
    ['title' => 'Local Support', 'body' => 'Drivers, guides, and staff coordinate pickups, ticket timing, accommodation check-ins, and activity schedules.'],
];
?>
<section class="section alt">
    <div class="container">
        <p class="eyebrow">Before you travel</p>
        <h1>Sri Lanka Travel Guides</h1>
        <p>Practical notes for planning a smoother island journey.</p>
        <div class="grid grid-3" style="margin-top: 28px;">
            <?php foreach ($guides as $index => $guide): ?>
                <?php $images = ['assets/images/packages/mirissa.png', 'assets/images/sections/travel-guides.png', 'assets/images/packages/kandy.png', 'assets/images/packages/ella.png', 'assets/images/packages/sigiriya.png', 'assets/images/sections/contact.png']; ?>
                <article class="card">
                    <img class="card-image" src="/globetrek_adventures/<?= e($images[$index]) ?>" alt="<?= e($guide['title']) ?>">
                    <div class="card-body">
                        <h2><?= e($guide['title']) ?></h2>
                        <p><?= e($guide['body']) ?></p>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
