<?php
session_start();

function recyclepro_clean_slug($slug) {
    return preg_replace('/[^a-z0-9-]/', '', strtolower($slug));
}

$slug = '';

if (isset($_GET['slug'])) {
    $slug = recyclepro_clean_slug($_GET['slug']);
} elseif (!empty($_SERVER['REQUEST_URI']) && preg_match('/\/shop\/([a-z0-9-]+)\/?(\?.*)?$/i', $_SERVER['REQUEST_URI'], $matches)) {
    $slug = recyclepro_clean_slug($matches[1]);
}

if (!$slug) {
    define('RECYCLEPRO_404_EMBED', true);
    http_response_code(404);
    $pageTitle = 'Page Not Found - Recycle Pro';
    $missingMessage = 'No page slug was provided.';
    include __DIR__ . '/includes/header.php';
    include __DIR__ . '/404.php';
    include __DIR__ . '/includes/footer.php';
    exit;
}

$api_url = 'https://www.recyclepro.co.uk/rp-dashboard/wp-json/wp/v2/page/' . rawurlencode($slug);
$context = stream_context_create(['http' => ['timeout' => 8]]);
$response = @file_get_contents($api_url, false, $context);

if ($response === false) {
    define('RECYCLEPRO_404_EMBED', true);
    http_response_code(404);
    $pageTitle = 'Page Not Found - Recycle Pro';
    $missingSlug = $slug;
    $missingMessage = 'This page is not available yet or could not be found in WordPress.';
    include __DIR__ . '/includes/header.php';
    include __DIR__ . '/404.php';
    include __DIR__ . '/includes/footer.php';
    exit;
}

$page = json_decode($response, true);

if (!$page || !isset($page['title'])) {
    define('RECYCLEPRO_404_EMBED', true);
    http_response_code(404);
    $pageTitle = 'Page Not Found - Recycle Pro';
    $missingSlug = $slug;
    $missingMessage = 'This page is not available yet or could not be found in WordPress.';
    include __DIR__ . '/includes/header.php';
    include __DIR__ . '/404.php';
    include __DIR__ . '/includes/footer.php';
    exit;
}

$pageTitle = $page['title'] . ' - Recycle Pro';
include __DIR__ . '/includes/header.php';
?>

<main class="content-page">
    <section class="breadcrumb">
        <div class="container">
           
                    <nav class="content-breadcrumb" aria-label="Breadcrumb">
                                  <a href="/shop/"><i class="bi bi-house"></i></a>

                        <span>/</span>
                        <span><?php echo htmlspecialchars($page['title'], ENT_QUOTES, 'UTF-8'); ?></span>
                    </nav>

        </div>
    </section>

    <section class="content-body-section">
        <div class="container">
              
            <?php if (!empty($page['featured_image'])): ?>
                <img
                    src="<?php echo htmlspecialchars($page['featured_image'], ENT_QUOTES, 'UTF-8'); ?>"
                    alt="<?php echo htmlspecialchars($page['title'], ENT_QUOTES, 'UTF-8'); ?>"
                    class="content-featured-image"
                >
            <?php endif; ?>

            <article class="content-body">
                <h1><?php echo htmlspecialchars($page['title'], ENT_QUOTES, 'UTF-8'); ?></h1>
                <?php echo $page['content'] ?? ''; ?>
            </article>
        </div>
    </section>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>
