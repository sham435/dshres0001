<!DOCTYPE html>
<html lang="si" dir="ltr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SaaS Platform - School Management & Inventory</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Sinhala:wght@400;500;600;700&family=Noto+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="/css/main.css">
<style>
:root { --color-primary: #3d1a78; --color-accent: #d4a017; --color-dark: #250f4a; --color-background: #f9f5ff; }
body { font-family: 'Noto Sans', sans-serif; background: var(--color-background); margin: 0; }
:lang(si) { font-family: 'Noto Sans Sinhala', sans-serif; }
.saas-card { transition: all 0.3s; }
.saas-card:hover { transform: translateY(-8px); box-shadow: 0 12px 40px rgba(0,0,0,.15); }
</style>
</head>
<body>

<!-- Hero -->
<div class="min-h-screen flex flex-col" style="background:linear-gradient(135deg,var(--color-primary),#7c3aed);">
  <div class="flex-1 flex items-center justify-center px-4 py-16">
    <div class="text-center text-white max-w-4xl">
      <h1 class="text-3xl md:text-5xl font-bold mb-4">School Management SaaS Platform</h1>
      <p class="text-lg text-white/80 mb-8">Unified platform for school administration, inventory management, and student tracking</p>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-12">
        <!-- School Management -->
        <a href="/" class="saas-card bg-white/10 backdrop-blur rounded-2xl p-8 text-white no-underline border border-white/20">
          <i class="bi bi-mortarboard-fill text-4xl mb-4 block" style="color:var(--color-accent);"></i>
          <h3 class="text-xl font-bold mb-2">School Management</h3>
          <p class="text-sm text-white/70">Students, teachers, attendance, exams, timetables, notices</p>
        </a>
        <!-- Inventory -->
        <a href="/inventory/" class="saas-card bg-white/10 backdrop-blur rounded-2xl p-8 text-white no-underline border border-white/20">
          <i class="bi bi-box-seam text-4xl mb-4 block" style="color:var(--color-accent);"></i>
          <h3 class="text-xl font-bold mb-2">IT Lab Inventory</h3>
          <p class="text-sm text-white/70">Equipment tracking, QR codes, reports, bulk upload</p>
        </a>
        <!-- Admin -->
        <a href="/login" class="saas-card bg-white/10 backdrop-blur rounded-2xl p-8 text-white no-underline border border-white/20">
          <i class="bi bi-shield-lock text-4xl mb-4 block" style="color:var(--color-accent);"></i>
          <h3 class="text-xl font-bold mb-2">Admin Panel</h3>
          <p class="text-sm text-white/70">User management, settings, reports, system config</p>
        </a>
      </div>
    </div>
  </div>
  <footer class="text-center text-white/50 py-4 text-sm">
    © 2026 A/Nachchaduwa Muslim Maha Vidyalaya. All Rights Reserved. | Powered by iGN3 Technologies
  </footer>
</div>
</body>
</html>