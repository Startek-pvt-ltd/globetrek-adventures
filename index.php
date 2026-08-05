<?php
$pageTitle = 'GlobeTrek Adventures | Home';
$activePage = 'home';
require_once __DIR__ . '/includes/header.php';

$stmt = $pdo->query("SELECT * FROM packages WHERE status = 'active' ORDER BY created_at DESC LIMIT 6");
$featuredPackages = $stmt->fetchAll();
?>
<section class="hero animated-hero">
    <div class="hero-slides" aria-hidden="true">
        <span style="--slide-image: url('/globetrek_adventures/assets/images/packages/sigiriya.png');"></span>
        <span style="--slide-image: url('/globetrek_adventures/assets/images/packages/ella.png');"></span>
        <span style="--slide-image: url('/globetrek_adventures/assets/images/packages/mirissa.png');"></span>
        <span style="--slide-image: url('/globetrek_adventures/assets/images/packages/yala.png');"></span>
    </div>
    <div class="hero-map" aria-hidden="true">
        <span class="map-dot dot-one"></span>
        <span class="map-dot dot-two"></span>
        <span class="map-dot dot-three"></span>
        <span class="map-dot dot-four"></span>
    </div>
    <div class="container hero-content">
        <div class="hero-copy">
            <p class="eyebrow">Explore Sri Lanka</p>
            <h1>Discover Island Journeys</h1>
            <p>Curated Sri Lankan travel packages across heritage sites, hill country, wildlife parks, and golden southern beaches.</p>
            <form class="search-bar" action="/globetrek_adventures/packages.php" method="get" data-validate>
                <input type="search" name="q" placeholder="Search Sigiriya, Ella, Kandy..." aria-label="Search Sri Lankan packages">
                <input type="date" name="travel_date" data-future aria-label="Preferred date">
                <button type="submit">Search</button>
            </form>
            <div class="hero-tags" aria-label="Popular Sri Lankan experiences">
                <span>Sigiriya</span>
                <span>Ella Train</span>
                <span>Yala Safari</span>
                <span>Mirissa Beach</span>
            </div>
        </div>
        <aside class="hero-showcase" aria-label="Featured Sri Lanka route">
            <div class="showcase-card main">
                <img src="/globetrek_adventures/assets/images/packages/ella.png" alt="Ella tea country">
                <div>
                    <span class="pill">3 days</span>
                    <h2>Tea Country Escape</h2>
                    <p>Nine Arch Bridge, scenic train, waterfalls</p>
                </div>
            </div>
            <div class="showcase-row">
                <div class="showcase-card mini">
                    <img src="/globetrek_adventures/assets/images/packages/sigiriya.png" alt="Sigiriya rock fortress">
                    <strong>Culture</strong>
                </div>
                <div class="showcase-card mini">
                    <img src="/globetrek_adventures/assets/images/packages/yala.png" alt="Yala wildlife safari">
                    <strong>Safari</strong>
                </div>
            </div>
        </aside>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-head">
            <div>
                <p class="eyebrow">Sri Lankan visiting places</p>
                <h2>Handpicked Island Packages</h2>
            </div>
            <a class="btn btn-outline" href="/globetrek_adventures/packages.php">View All Sri Lanka Tours</a>
        </div>
        <div class="grid grid-3">
            <?php foreach ($featuredPackages as $package): ?>
                <article class="card package-card">
                    <?php if (!empty($package['image_path'])): ?>
                        <img class="package-image" src="/globetrek_adventures/<?= e($package['image_path']) ?>" alt="<?= e($package['title']) ?>">
                    <?php else: ?>
                        <div class="image-placeholder"><?= e($package['destination']) ?></div>
                    <?php endif; ?>
                    <div class="card-body">
                        <h3><?= e($package['title']) ?></h3>
                        <p><?= e(substr($package['description'], 0, 112)) ?>...</p>
                        <div class="meta">
                            <span class="pill"><?= e($package['duration_days']) ?> days</span>
                            <span class="pill"><?= e($package['destination']) ?></span>
                        </div>
                        <p><strong>Activities:</strong> <?= e($package['activities'] ?? 'Guided sightseeing') ?></p>
                        <div class="price-row">
                            <span class="price"><?= e(format_lkr($package['price'])) ?></span>
                            <a class="btn" href="/globetrek_adventures/package-details.php?package_id=<?= e($package['package_id']) ?>">Book Now</a>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section alt">
    <div class="container">
        <div class="section-head">
            <div>
                <p class="eyebrow">Why choose us</p>
                <h2>Local Travel Planning That Feels Grounded</h2>
            </div>
        </div>
        <div class="grid grid-3">
            <div class="card card-body"><img class="icon-image" src="/globetrek_adventures/assets/images/packages/sigiriya.png" alt="Sri Lankan route planning"><h3>Expert Routes</h3><p>Each itinerary is built around realistic Sri Lankan travel time, local expertise, and meaningful stops.</p></div>
            <div class="card card-body"><img class="icon-image" src="/globetrek_adventures/assets/images/sections/transportation.png" alt="Secure Sri Lanka travel booking"><h3>Secure Booking</h3><p>Accounts, bookings, and simulated payments use server-side validation and protected sessions.</p></div>
            <div class="card card-body"><img class="icon-image" src="/globetrek_adventures/assets/images/sections/contact.png" alt="Traveler support desk"><h3>Human Support</h3><p>Staff dashboards keep bookings and traveler queries moving from inquiry to confirmation.</p></div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <p class="eyebrow">Testimonials</p>
        <h2>Traveler Notes</h2>
        <div class="grid grid-3" style="margin-top: 28px;">
            <blockquote class="card"><img class="card-image" src="/globetrek_adventures/assets/images/packages/kandy.png" alt="Kandy traveler experience"><div class="card-body"><p>"Sigiriya and Kandy were planned beautifully, with enough time to enjoy each stop."</p><strong>Asha Perera</strong></div></blockquote>
            <blockquote class="card"><img class="card-image" src="/globetrek_adventures/assets/images/packages/ella.png" alt="Ella traveler experience"><div class="card-body"><p>"The Ella train ride and Nine Arch Bridge visit were exactly what we hoped for."</p><strong>Nimal Fernando</strong></div></blockquote>
            <blockquote class="card"><img class="card-image" src="/globetrek_adventures/assets/images/packages/mirissa.png" alt="Mirissa traveler experience"><div class="card-body"><p>"Mirissa was relaxed, cleanly organized, and easy from booking to pickup."</p><strong>Sarah Wijesinghe</strong></div></blockquote>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
