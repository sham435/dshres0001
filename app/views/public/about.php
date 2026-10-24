<?php
$_SESSION['language'] = 'si';
?>
<!DOCTYPE html>
<html lang="si" dir="ltr" data-bs-theme="<?= $_SESSION['theme'] ?? 'light' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About - Nachchaduwa Muslim Maha Vidyalaya</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Sinhala:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --color-primary: #1e40af;
            --color-dark: #1e293b;
            --color-gold: #f59e0b;
            --color-light: #f8fafc;
            --color-accent: #059669;
        }
        body { font-family: 'Noto Sans Sinhala', sans-serif; }
        .text-xl { font-size: 1.25rem; }
        .text-2xl { font-size: 1.5rem; }
        .text-4xl { font-size: 2.25rem; }
        .text-xs { font-size: 0.75rem; }
        .text-sm { font-size: 0.875rem; }
        .rounded-2xl { border-radius: 1rem; }
        .rounded-xl { border-radius: 0.75rem; }
        .rounded-full { border-radius: 9999px; }
        .shadow-soft { box-shadow: 0 1px 8px rgba(0,0,0,.05); }
        .shadow-heavy { box-shadow: 0 4px 24px rgba(0,0,0,.15); }
        .border-light { border: 1px solid rgba(0,0,0,.07); }
        .border-dark { border: 1px solid rgba(255,255,255,.1); }
        .tracking-widest { letter-spacing: 0.1em; }
        .uppercase { text-transform: uppercase; }

        .top-bar { background: var(--color-primary); color: white; padding: 8px 0; font-size: 0.85rem; }
        .top-bar a { color: white; text-decoration: none; }

        .sticky-nav { position: sticky; top: 0; z-index: 100; background: var(--color-dark); }
        .sticky-nav a { color: #cbd5e1; text-decoration: none; padding: 12px 16px; display: inline-block; }
        .sticky-nav a:hover { color: white; background: rgba(255,255,255,.1); }

        .hero-section {
            background: linear-gradient(135deg, var(--color-primary) 0%, #7c3aed 100%);
            color: white; padding: 80px 0; text-align: center; position: relative; overflow: hidden;
        }
        .hero-section::before {
            content: ''; position: absolute; inset: 0;
            background: url('https://anueducation.lk/images/emblem.png') no-repeat center;
            background-size: 200px; opacity: 0.05;
        }
        .hero-section h1 { font-size: 2.5rem; font-weight: 700; position: relative; }
        .hero-section p { font-size: 1.1rem; opacity: 0.9; position: relative; }

        .info-card { background: white; border-radius: 1rem; padding: 24px; box-shadow: 0 1px 8px rgba(0,0,0,.05); border: 1px solid rgba(0,0,0,.07); }
        [data-bs-theme="dark"] .info-card { background: #1e293b; border-color: rgba(255,255,255,.1); color: #e2e8f0; }
        .principal-card { background: white; border-radius: 1rem; padding: 24px; box-shadow: 0 4px 24px rgba(0,0,0,.15); text-align: center; }
        [data-bs-theme="dark"] .principal-card { background: #1e293b; color: #e2e8f0; }

        .stats-band { background: var(--color-primary); color: white; padding: 40px 0; }
        .stat-number { font-size: 2.5rem; font-weight: 700; color: var(--color-gold); }
        .stat-label { font-size: 0.85rem; opacity: 0.9; }

        .prog-card { border-radius: 0.75rem; overflow: hidden; box-shadow: 0 1px 8px rgba(0,0,0,.05); }
        .prog-card img { width: 100%; height: 160px; object-fit: cover; }

        .quick-links { background: var(--color-primary); padding: 40px 0; }
        .quick-tile { background: rgba(255,255,255,.1); border-radius: 0.75rem; padding: 20px; text-align: center; color: white; text-decoration: none; transition: transform 0.2s; display: block; }
        .quick-tile:hover { transform: translateY(-4px); background: rgba(255,255,255,.2); color: white; }
        .quick-tile i { font-size: 1.8rem; margin-bottom: 8px; display: block; }

        .site-footer { background: var(--color-dark); color: #cbd5e1; padding: 40px 0 20px; }
        .site-footer a { color: #94a3b8; text-decoration: none; }
        .site-footer a:hover { color: white; }
        .visitor-dot { width: 8px; height: 8px; background: #22c55e; border-radius: 50%; display: inline-block; animation: pulse 2s infinite; }
        @keyframes pulse { 0%,100% { opacity: 1; } 50% { opacity: .4; } }

        .message-quote { border-left: 4px solid var(--color-gold); padding-left: 16px; font-style: italic; }

        @media (max-width: 768px) {
            .hero-section h1 { font-size: 1.8rem; }
        }
    </style>
</head>
<body>
    <!-- Top Bar -->
    <div class="top-bar">
        <div class="container d-flex justify-content-between align-items-center flex-wrap">
            <div class="d-flex align-items-center">
                <img src="https://anueducation.lk/images/emblem.png" alt="Emblem" height="28" class="me-2">
                <div>
                    <div class="fw-bold" style="color: #f59e0b;">Nachchaduwa Muslim Maha Vidyalaya</div>
                    <small>Nachchaduwa, Anuradhapura District</small>
                </div>
            </div>
            <div class="d-flex align-items-center gap-3 mt-2 mt-md-0">
                <span><i class="bi bi-telephone"></i> +942280311</span>
                <span><i class="bi bi-envelope"></i> nachchaduwa.mv@gmail.com</span>
                <a href="https://www.facebook.com/zonaleucationanuradhapura/" target="_blank"><i class="bi bi-facebook"></i></a>
            </div>
        </div>
    </div>

    <!-- Sticky Nav -->
    <nav class="sticky-nav">
        <div class="container d-flex justify-content-between align-items-center">
            <div class="d-none d-md-flex">
                <a href="/">මුල්පිටුව</a>
                <a href="/about">අප ගැන</a>
                <a href="/about">පාසල</a>
                <a href="/about">ප්‍රතිඵල</a>
                <a href="/about">වැඩසටහන්</a>
                <a href="/contact">අමතන්න</a>
            </div>
            <button class="btn btn-outline-light d-md-none" onclick="document.getElementById('mobileNav').classList.toggle('show')">☰</button>
        </div>
        <div id="mobileNav" style="display:none;">
            <a href="/" class="d-block">මුල්පිටුව</a>
            <a href="/about" class="d-block">අප ගැන</a>
            <a href="/about" class="d-block">පාසල</a>
            <a href="/about" class="d-block">ප්‍රතිඵල</a>
            <a href="/about" class="d-block">වැඩසටහන්</a>
            <a href="/contact" class="d-block">අමතන්න</a>
        </div>
    </nav>

    <!-- Hero -->
    <div class="hero-section">
        <h1>නාච්චාදූව මුස්ලිම් මහා විද්‍යාලය</h1>
        <p>නව නගරය, නාච්චාදූව | අනුරාධපුර දිස්ත්‍රික්කය</p>
        <p class="mt-2">1C වර්ගය (ශ්‍රේණිය 1-13) | දෙමළ මාධ්‍ය | 1920 සිට</p>
        <a href="/login" class="btn btn-light btn-lg mt-3 rounded-full"><i class="bi bi-box-arrow-in-right"></i> ඇතුල් වන්න</a>
    </div>

    <!-- School Info -->
    <div class="container py-5">
        <div class="row g-4">
            <div class="col-md-6">
                <div class="info-card h-100">
                    <h4 style="color: var(--color-primary);"><i class="bi bi-info-circle"></i> පාසල් සාරාංශය</h4>
                    <table class="table table-borderless mt-3">
                        <tr><th>පාසල් නම</th><td>නාච්චාදූව මුස්ලිම් මහා විද්‍යාලය</th></tr>
                        <tr><th>පාසල් වර්ගය</th><td>1C (ශ්‍රේණිය 1-13, කලා/වාණිජ)</td></tr>
                        <tr><th>මාධ්‍යය</th><td>දෙමළ මාධ්‍ය</td></tr>
                        <tr><th>අයිතිය</th><td>පළාත්</td></tr>
                        <tr><th>ශ්‍රේණි පරාසය</th><td>ශ්‍රේණිය 1 - 13</td></tr>
                        <tr><th>ස්ථාපිත</th><td>1920</td></tr>
                        <tr><th>සංගණන අංකය</th><td>19508</td></tr>
                        <tr><th>ප්‍රා. ලේකම්</th><td>නාච්චාදූව</td></tr>
                        <tr><th>ග්‍රාම නිලධාරි</th><td>536, නාච්චාදූව නව නගරය</td></tr>
                        <tr><th>පහසුකම් මට්ටම</th><td>Easy</td></tr>
                    </table>
                </div>
            </div>
            <div class="col-md-6">
                <div class="info-card h-100">
                    <h4 style="color: var(--color-primary);"><i class="bi bi-geo-alt"></i> සම්බන්ධතා සහ ස්ථානය</h4>
                    <div class="mt-3">
                        <p><i class="bi bi-geo-alt text-primary"></i> <strong>ලිපිනය:</strong> නව නගරය, නාච්චාදූව</p>
                        <p><i class="bi bi-telephone text-primary"></i> <strong>දුරකථන:</strong> +942280311</p>
                        <p><i class="bi bi-envelope text-primary"></i> <strong>විද්‍යුත් තැපෑල:</strong> nachchaduwa.mv@gmail.com</p>
                        <p><i class="bi bi-clock text-primary"></i> <strong>වේලාවන්:</strong> සඳු-බ්‍රහස්පති 7.00 AM - 2.00 PM, සිකු 7.00 AM - 11.35 AM</p>
                        <p><i class="bi bi-map text-primary"></i> <strong>ස්ථානය:</strong> 8.24697153, 80.46342566</p>
                        <a href="https://www.google.com/maps/dir/?api=1&destination=8.24697153,80.46342566" class="btn btn-outline-primary btn-sm mt-2" target="_blank">
                            <i class="bi bi-map"></i> මාර්ග ලබා ගන්න
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Vision & Mission -->
    <div class="container py-4">
        <div class="row g-4">
            <div class="col-md-6">
                <div class="info-card h-100">
                    <h4 style="color: var(--color-primary);"><i class="bi bi-eye"></i> අපගේ දැක්ම</h4>
                    <p class="mt-3">ගුණාත්මක අධ්‍යාපනයක් සහ සම්පූර්ණ පුද්ගල වර්ධනයක් සහිත ශිෂ්‍යයන් බිහිකිරීම, ඔවුන්ගේ චරිතය, නිර්මාණශීලිත්වය සහ නායකත්වය වර්ධනය කිරීම.</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="info-card h-100">
                    <h4 style="color: var(--color-accent);"><i class="bi bi-bullseye"></i> අපගේ මෙහෙවර</h4>
                    <p class="mt-3">ගුණාත්මක හා ධනාත්මක සංවර්ධනයක් සඳහා අධ්‍යාපනයේ නව්‍ය මායිම් සොයන අතරම ගුරුවරුන්, සිසුන් සහ පාසල් ප්‍රජාවට ප්‍රශස්ත සේවාවක් සහ පහසුකම් සැපයීම.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Principal Message -->
    <div class="container py-4">
        <div class="info-card" style="border-left: 4px solid var(--color-gold);">
            <div class="row align-items-center">
                <div class="col-md-3 text-center">
                    <div style="width:100px;height:100px;border-radius:50%;background:var(--color-primary);display:flex;align-items:center;justify-content:center;margin:0 auto;">
                        <i class="bi bi-person-fill text-white" style="font-size:3rem;"></i>
                    </div>
                    <h6 class="mt-2">විදුහල්පතිතුමා</h6>
                    <p class="text-xs text-muted">නාච්චාදූව මුස්ලිම් මහා විද්‍යාලය</p>
                </div>
                <div class="col-md-9">
                    <h5 style="color: var(--color-gold);">විදුහල්පතිතුමාගේ පණිවිඩය</h5>
                    <p class="message-quote">"අධ්‍යාපනය යනු පුද්ගලයන්ගේත් සමාජයේත් ගතිකත්වය හා සංවර්ධනය හැඩගස්වන ප්‍රබලතම බලවේගයකි. විවිධ අභියෝග මධ්‍යයේ වුවද, එය අපේ රටට විශාල ප්‍රගතියක් අත්කර දී ඇත."</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Exam Results -->
    <div class="container py-4">
        <div class="info-card">
            <h4 style="color: var(--color-primary);"><i class="bi bi-award"></i> විභාග ප්‍රතිඵල (2025)</h4>
            <div class="row text-center mt-3">
                <div class="col-md-6">
                    <h5 class="text-primary">සා/පෙ (GCE O/L)</h5>
                    <div class="row">
                        <div class="col-4"><h3 class="text-primary">28</h3><p class="text-sm text-muted">සම්පූර්ණ සිසුන්</p></div>
                        <div class="col-4"><h3 class="text-success">82.1%</h3><p class="text-sm text-muted">සුදුසුකම් ලත්</p></div>
                        <div class="col-4"><h3 class="text-info">5</h3><p class="text-sm text-muted">සුදුසුකම් නොලත්</p></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <h5 class="text-primary">උ/පෙ (GCE A/L)</h5>
                    <div class="row">
                        <div class="col-4"><h3 class="text-primary">55</h3><p class="text-sm text-muted">සම්පූර්ණ සිසුන්</p></div>
                        <div class="col-4"><h3 class="text-success">87.3%</h3><p class="text-sm text-muted">සුදුසුකම් ලත්</p></div>
                        <div class="col-4"><h3 class="text-info">7</h3><p class="text-sm text-muted">සුදුසුකම් නොලත්</p></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="quick-links">
        <div class="container">
            <h3 class="text-center text-white mb-4">ඉක්මන් සබැඳි</h3>
            <div class="row g-3">
                <div class="col-6 col-md-2"><a href="/downloads" class="quick-tile d-block"><i class="bi bi-download"></i>බාගැනීම්</a></div>
                <div class="col-6 col-md-2"><a href="/notices" class="quick-tile d-block"><i class="bi bi-megaphone"></i>නිවේදන</a></div>
                <div class="col-6 col-md-2"><a href="/students" class="quick-tile d-block"><i class="bi bi-people"></i>සිසුන්</a></div>
                <div class="col-6 col-md-2"><a href="/exams" class="quick-tile d-block"><i class="bi bi-award"></i>ප්‍රතිඵල</a></div>
                <div class="col-6 col-md-2"><a href="/timetable" class="quick-tile d-block"><i class="bi bi-calendar-event"></i>කාලසටහන</a></div>
                <div class="col-6 col-md-2"><a href="/contact" class="quick-tile d-block"><i class="bi bi-envelope"></i>අමතන්න</a></div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="site-footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <h6 class="text-white">නාච්චාදූව මුස්ලිම් මහා විද්‍යාලය</h6>
                    <p class="text-sm"><i class="bi bi-telephone"></i> +942280311</p>
                    <p class="text-sm"><i class="bi bi-envelope"></i> nachchaduwa.mv@gmail.com</p>
                    <p class="text-sm"><i class="bi bi-geo-alt"></i> නව නගරය, නාච්චාදූව</p>
                </div>
                <div class="col-md-4 mb-3">
                    <h6 class="text-white">පාසල් තොරතුරු</h6>
                    <p class="text-sm">1C වර්ගය (ශ්‍රේණිය 1-13)</p>
                    <p class="text-sm">දෙමළ මාධ්‍ය</p>
                    <p class="text-sm">1920 සිට සේවයේ</p>
                    <p class="text-sm">සංගණන අංකය: 19508</p>
                </div>
                <div class="col-md-4 mb-3">
                    <h6 class="text-white">ඉක්මන් සබැඳි</h6>
                    <a href="/" class="d-block text-sm">මුල්පිටුව</a>
                    <a href="/students" class="d-block text-sm">සිසුන්</a>
                    <a href="/teachers" class="d-block text-sm">ගුරුවරුන්</a>
                    <a href="/attendance" class="d-block text-sm">පැමිණීම</a>
                    <a href="/exams" class="d-block text-sm">විභාග</a>
                    <a href="/contact" class="d-block text-sm">අමතන්න</a>
                </div>
            </div>
            <hr style="border-color: rgba(255,255,255,.1);">
            <p class="text-center text-sm mb-0">© 2026 Nachchaduwa Muslim Maha Vidyalaya. සියලු හිමිකම් ඇවිරිණි.</p>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('mobileNav')?.parentElement?.querySelector('button')?.addEventListener('click', function() {
            const nav = document.getElementById('mobileNav');
            nav.style.display = nav.style.display === 'block' ? 'none' : 'block';
        });
    </script>
</body>
</html>