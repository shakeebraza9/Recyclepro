<?php
$pageTitle = $pageTitle ?? 'Page Not Found - Recycle Pro';
$missingSlug = $missingSlug ?? '';
$missingMessage = $missingMessage ?? 'The page you are looking for could not be found.';

if (!defined('RECYCLEPRO_404_EMBED')) {
    http_response_code(404);
    include __DIR__ . '/includes/header.php';
}
?>

<main class="not-found-page">
    <section class="content-body-section">
        <div class="container">
            <article class="content-body">
                <span class="not-found-card__code">404</span>
                <h1>Page not found</h1>
                <p><?php echo htmlspecialchars($missingMessage, ENT_QUOTES, 'UTF-8'); ?></p>

                <?php if ($missingSlug): ?>
                    <p class="not-found-card__slug">
                        Missing page:
                        <strong><?php echo htmlspecialchars($missingSlug, ENT_QUOTES, 'UTF-8'); ?></strong>
                    </p>
                <?php endif; ?>

                <div class="not-found-card__actions">
                    <a href="/shop/" class="btn btn-dark btn-lg">Back to Home</a>
                    <a href="/shop/category" class="btn btn-outline-dark btn-lg">Browse Shop</a>
                </div>
            </article>
        </div>
    </section>
</main>

<?php
if (!defined('RECYCLEPRO_404_EMBED')) {
    include __DIR__ . '/includes/footer.php';
}
?>
