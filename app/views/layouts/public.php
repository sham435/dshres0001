<!DOCTYPE html>
<html lang="<?= $_SESSION['language'] ?? 'en' ?>" data-bs-theme="<?= $_SESSION['theme'] ?? 'light' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? config('app_name'), ENT_QUOTES) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="/public/css/style.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="/"><i class="bi bi-mortarboard-fill"></i> <?= config('app_name') ?></a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="/">මුල්පිටුව</a></li>
                    <li class="nav-item"><a class="nav-link" href="/about">පරිපාලනය</a></li>
                    <li class="nav-item"><a class="nav-link" href="/about">කොට්ඨාස</a></li>
                    <li class="nav-item"><a class="nav-link" href="/about">පාසල්</a></li>
                    <li class="nav-item"><a class="nav-link" href="/about">ප්‍රතිඵල</a></li>
                    <li class="nav-item"><a class="nav-link" href="/about">අදිති</a></li>
                    <li class="nav-item"><a class="nav-link" href="/contact">අමතන්න</a></li>
                    <li class="nav-item"><a class="nav-link btn btn-outline-light ms-2" href="/login">ඇතුල් වන්න</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <main class="container py-4">
        <?= $content ?? '' ?>
    </main>
    <footer class="bg-dark text-white text-center py-3 mt-5">
        <small>&copy; <?= date('Y') ?> <?= config('app_name') ?>. All rights reserved.</small>
    </footer>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>