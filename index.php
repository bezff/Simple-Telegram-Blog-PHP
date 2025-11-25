<?php
require_once __DIR__ . '/core/functions.php';
$posts = getPosts();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Мой Блог</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<div class="container">
    <header>
        <h1>Blog</h1>
        <p>Мой   блог</p>
    </header>

    <main>
        <?php if (empty($posts)): ?>
            <div class="empty-state">Постов пока нет. Зайдите в бота, чтобы создать первый!</div>
        <?php else: ?>
            <?php foreach ($posts as $post): ?>
                <article class="post">
                    <div class="post-header">
                        <span class="date"><?= htmlspecialchars($post['date']) ?></span>
                    </div>
                    
                    <?php if (!empty($post['image'])): ?>
                        <div class="post-image">
                            <img src="assets/uploads/<?= htmlspecialchars($post['image']) ?>" alt="Post Image">
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($post['text'])): ?>
                        <div class="post-content">
                            <?= nl2br(htmlspecialchars($post['text'])) ?>
                        </div>
                    <?php endif; ?>
                </article>
            <?php endforeach; ?>
        <?php endif; ?>
    </main>

    <footer>
        <p>&copy; <?= date('Y') ?> TgBlogByStarfolio</p>
    </footer>
</div>

</body>
</html>