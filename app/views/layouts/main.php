<!DOCTYPE html>
<html lang="<?= $_SESSION['language'] ?? 'en' ?>" data-bs-theme="<?= $_SESSION['theme'] ?? 'light' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES) ?>">
    <title><?= htmlspecialchars($title ?? config('app_name'), ENT_QUOTES) ?></title>

    <!-- Bootstrap 5.3.8 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="/public/css/style.css" rel="stylesheet">
</head>
<body>
    <?php if (is_auth()): ?>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="/dashboard">
                <i class="bi bi-mortarboard-fill"></i> <?= config('app_name') ?>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="/dashboard"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="/students"><i class="bi bi-people"></i> Students</a></li>
                    <li class="nav-item"><a class="nav-link" href="/teachers"><i class="bi bi-person-badge"></i> Teachers</a></li>
                    <li class="nav-item"><a class="nav-link" href="/attendance"><i class="bi bi-calendar-check"></i> Attendance</a></li>
                    <li class="nav-item"><a class="nav-link" href="/exams"><i class="bi bi-file-earmark-text"></i> Exams</a></li>
                    <li class="nav-item"><a class="nav-link" href="/timetable"><i class="bi bi-calendar-week"></i> Timetable</a></li>
                    <li class="nav-item"><a class="nav-link" href="/notices"><i class="bi bi-megaphone"></i> Notices</a></li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i> <?= htmlspecialchars(auth_user()['name'] ?? 'User', ENT_QUOTES) ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="/settings"><i class="bi bi-gear"></i> Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="/logout"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <button class="btn btn-outline-light btn-sm ms-2" id="themeToggle" title="Toggle Theme">
                            <i class="bi bi-moon-fill" id="themeIcon"></i>
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <?php endif; ?>

    <main class="container-fluid py-4">
        <?php if (has_flash('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= htmlspecialchars(flash('success'), ENT_QUOTES) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        <?php if (has_flash('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= htmlspecialchars(flash('error'), ENT_QUOTES) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        <?php if (has_flash('warning')): ?>
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <?= htmlspecialchars(flash('warning'), ENT_QUOTES) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?= $content ?? '' ?>
    </main>

    <footer class="bg-light text-center py-3 mt-4 border-top">
        <small class="text-muted">&copy; <?= date('Y') ?> <?= config('app_name') ?>. All rights reserved.</small>
    </footer>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Bootstrap 5.3.8 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="/public/js/app.js"></script>
    <script>
        // Theme toggle
        document.getElementById('themeToggle')?.addEventListener('click', function() {
            const html = document.documentElement;
            const current = html.getAttribute('data-bs-theme');
            const next = current === 'dark' ? 'light' : 'dark';
            html.setAttribute('data-bs-theme', next);
            document.getElementById('themeIcon').className = next === 'dark' ? 'bi bi-sun-fill' : 'bi bi-moon-fill';
            fetch('/theme/' + next);
        });

        // Set initial theme icon
        (function() {
            const theme = document.documentElement.getAttribute('data-bs-theme');
            const icon = document.getElementById('themeIcon');
            if (icon) icon.className = theme === 'dark' ? 'bi bi-sun-fill' : 'bi bi-moon-fill';
        })();
    </script>
</body>
</html>