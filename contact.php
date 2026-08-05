<?php
require_once __DIR__ . '/includes/auth.php';
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if ($name === '' || $email === '' || $subject === '' || $message === '') $errors[] = 'All fields are required.';
    if ($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Enter a valid email address.';

    if (!$errors) {
        $stmt = $pdo->prepare('INSERT INTO queries (user_id, name, email, subject, message) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([$_SESSION['user_id'] ?? null, $name, $email, $subject, $message]);
        set_flash('success', 'Your query has been submitted. Our team will respond soon.');
        header('Location: /globetrek_adventures/contact.php');
        exit;
    }
}

$pageTitle = 'Contact | GlobeTrek Adventures';
$activePage = 'contact';
require_once __DIR__ . '/includes/header.php';
?>
<section class="section alt">
    <div class="container two-col">
        <div>
            <p class="eyebrow">Contact</p>
            <h1>Ask Us Anything About Your Trip</h1>
            <p>Send a planning question, booking concern, or destination request. Logged-in travelers can also track queries from the dashboard.</p>
            <img class="section-image" src="/globetrek_adventures/assets/images/sections/contact.png" alt="GlobeTrek Sri Lanka travel support">
        </div>
        <form class="panel" method="post" data-validate>
            <?= csrf_field() ?>
            <?php foreach ($errors as $error): ?><div class="flash error"><?= e($error) ?></div><?php endforeach; ?>
            <div class="field"><label for="name">Name</label><input id="name" name="name" required value="<?= e($_POST['name'] ?? $_SESSION['name'] ?? '') ?>"></div>
            <div class="field"><label for="email">Email</label><input id="email" type="email" name="email" required value="<?= e($_POST['email'] ?? '') ?>"></div>
            <div class="field"><label for="subject">Subject</label><input id="subject" name="subject" required value="<?= e($_POST['subject'] ?? '') ?>"></div>
            <div class="field"><label for="message">Message</label><textarea id="message" name="message" required><?= e($_POST['message'] ?? '') ?></textarea></div>
            <button type="submit">Submit Query</button>
        </form>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
