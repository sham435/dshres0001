<!DOCTYPE html>
<html lang="si" dir="ltr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>පුවත් - නාච්චාදූව මුස්ලිම් මහා විද්‍යාලය</title>
<meta name="description" content="නාච්චාදූව මුස්ලිම් මහා විද්‍යාලය - පුවත්">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Sinhala:wght@400;500;600;700&family=Noto+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<style>
:root {
  --color-primary: #3d1a78;
  --color-accent: #d4a017;
  --color-background: #f9f5ff;
  --color-dark: #250f4a;
  --color-text-dark: #3d1a78;
}
body { font-family: 'Noto Sans', sans-serif; background: var(--color-background); color: var(--color-text-dark); margin: 0; }
:lang(si) { font-family: 'Noto Sans Sinhala', sans-serif; }
[x-cloak] { display: none !important; }
.news-card { transition: all 0.3s; }
.news-card:hover { transform: translateY(-4px); box-shadow: 0 8px 24px rgba(0,0,0,.12); }
</style>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="/css/main.css">
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
</head>
<body>

<!-- TOP UTILITY BAR -->
<div class="bg-[var(--color-primary)] text-white py-2 px-4">
  <div class="max-w-7xl mx-auto flex flex-wrap justify-between items-center gap-2">
    <div class="flex items-center gap-2">
      <img src="https://anueducation.lk/images/emblem.png" alt="Emblem" class="h-8">
      <div>
        <div class="font-bold text-[var(--color-accent)] text-sm md:text-lg">අ/ නාච්චාදූව මුස්ලිම් මහා විද්‍යාලය</div>
        <div class="text-white/80 text-xs">අනුරාධපුර දිස්ත්‍රික්කය, උතුරු මැද පළාත</div>
      </div>
    </div>
    <div class="flex items-center gap-3 text-sm">
      <span class="hidden md:inline"><i class="bi bi-telephone"></i> +942280311</span>
      <a href="https://www.facebook.com/p/Nachchaduwa-muslim-maha-vidyala-100057583623835/" target="_blank" class="text-white hover:text-[var(--color-accent)]"><i class="bi bi-facebook"></i></a>
      <div class="flex gap-1">
        <a href="/news-si.php" class="px-2 py-0.5 rounded text-xs bg-[var(--color-accent)] text-[var(--color-primary)] font-bold">සිං</a>
        <a href="/news-en.php" class="px-2 py-0.5 rounded text-xs border border-white/40 hover:bg-white/10">EN</a>
        <a href="/news-ta.php" class="px-2 py-0.5 rounded text-xs border border-white/40 hover:bg-white/10">த</a>
      </div>
    </div>
  </div>
</div>

<!-- STICKY NAV -->
<nav class="sticky top-0 z-50 bg-[var(--color-dark)] shadow-lg">
  <div class="max-w-7xl mx-auto px-5 flex justify-between items-center">
    <div class="hidden md:flex">
      <a href="/" class="text-white/85 py-4 px-3.5 text-sm hover:text-white hover:bg-white/10">මුල්පිටුව</a>
      <a href="/history-si.php" class="text-white/85 py-4 px-3.5 text-sm hover:text-white hover:bg-white/10">අප ගැන</a>
      <a href="/clubs-si.php" class="text-white/85 py-4 px-3.5 text-sm hover:text-white hover:bg-white/10">සංගම් සහ සමාජ</a>
      <a href="/anthem-si.php" class="text-white/85 py-4 px-3.5 text-sm hover:text-white hover:bg-white/10">ගීතිකාව</a>
      <a href="/album.php" class="text-white/85 py-4 px-3.5 text-sm hover:text-white hover:bg-white/10">ඡායාරූප ගැලරිය</a>
      <a href="/news-si.php" class="text-white/85 py-4 px-3.5 text-sm hover:text-white hover:bg-white/10">පුවත්</a>
      <a href="/contact.php" class="text-white/85 py-4 px-3.5 text-sm hover:text-white hover:bg-white/10">අමතන්න</a>
    </div>
    <button class="md:hidden text-white p-3" onclick="document.getElementById('mobileNav').style.display=document.getElementById('mobileNav').style.display==='block'?'none':'block'">☰</button>
  </div>
  <div id="mobileNav" class="md:hidden bg-[var(--color-primary)]" style="display:none;">
    <a href="/" class="block py-3 px-5 text-white/85 border-b border-white/10">මුල්පිටුව</a>
    <a href="/history-si.php" class="block py-3 px-5 text-white/85 border-b border-white/10">අප ගැන</a>
    <a href="/clubs-si.php" class="block py-3 px-5 text-white/85 border-b border-white/10">සංගම් සහ සමාජ</a>
    <a href="/anthem-si.php" class="block py-3 px-5 text-white/85 border-b border-white/10">ගීතිකාව</a>
    <a href="/album.php" class="block py-3 px-5 text-white/85 border-b border-white/10">ඡායාරූප ගැලරිය</a>
    <a href="/news-si.php" class="block py-3 px-5 text-white/85 border-b border-white/10">පුවත්</a>
    <a href="/contact.php" class="block py-3 px-5 text-white/85 border-b border-white/10">අමතන්න</a>
  </div>
</nav>

<!-- BREADCRUMB -->
<div class="bg-white border-b py-3 px-4">
  <div class="max-w-7xl mx-auto text-sm text-gray-500">
    <a href="/" class="text-[var(--color-primary)] hover:underline">Home</a> &gt; <span class="font-semibold" style="color:var(--color-primary);">පුවත්</span>
  </div>
</div>

<!-- PAGE TITLE -->
<section class="py-8 bg-white border-b">
  <div class="max-w-7xl mx-auto px-4">
    <h1 class="text-2xl md:text-3xl font-bold" style="color:var(--color-primary);">පුවත් සහ යාවත්කාලීන</h1>
  </div>
</section>

<!-- NEWS GRID (Lyceum style) -->
<section class="py-12" style="background:var(--color-background);">
  <div class="max-w-7xl mx-auto px-4">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      
      <!-- News 1 -->
      <div class="news-card bg-white rounded-lg overflow-hidden shadow-sm border" style="border-color:rgba(0,0,0,.07);">
        <a href="#" class="block">
          <img src="https://anueducation.lk/storage/sliders/01M099Q7PNT8WTCJ28R6TM2BC9.jpg" alt="News 1" class="w-full h-48 object-cover">
        </a>
        <div class="p-4">
          <div class="flex items-center gap-2 text-xs text-gray-500 mb-2">
            <span><i class="bi bi-person"></i> Admin</span>
            <span>•</span>
            <span><i class="bi bi-calendar"></i> 2026.01.15</span>
            <span>•</span>
            <span>2 min read</span>
          </div>
          <h3 class="text-sm font-semibold mb-2" style="color:var(--color-primary);">Annual Sports Meet 2026 - Registration Open</h3>
          <p class="text-xs text-gray-600 mb-3">Registration for the annual sports meet is now open. Students can register through their class teachers.</p>
          <div class="flex gap-2 flex-wrap">
            <span class="px-2 py-0.5 rounded-full text-xs" style="background:var(--color-primary);color:white;">Events</span>
            <span class="px-2 py-0.5 rounded-full text-xs" style="background:var(--color-accent);color:var(--color-primary);">News</span>
          </div>
        </div>
      </div>

      <!-- News 2 -->
      <div class="news-card bg-white rounded-lg overflow-hidden shadow-sm border" style="border-color:rgba(0,0,0,.07);">
        <a href="#" class="block">
          <img src="https://anueducation.lk/storage/sliders/01M099VY59EPBB6FXK1F2QBVHB.jpg" alt="News 2" class="w-full h-48 object-cover">
        </a>
        <div class="p-4">
          <div class="flex items-center gap-2 text-xs text-gray-500 mb-2">
            <span><i class="bi bi-person"></i> Admin</span>
            <span>•</span>
            <span><i class="bi bi-calendar"></i> 2026.01.10</span>
            <span>•</span>
            <span>3 min read</span>
          </div>
          <h3 class="text-sm font-semibold mb-2" style="color:var(--color-primary);">Prize Giving Ceremony 2025</h3>
          <p class="text-xs text-gray-600 mb-3">The annual prize giving ceremony will be held on January 25, 2026. All parents are invited to attend.</p>
          <div class="flex gap-2 flex-wrap">
            <span class="px-2 py-0.5 rounded-full text-xs" style="background:var(--color-primary);color:white;">Events</span>
            <span class="px-2 py-0.5 rounded-full text-xs" style="background:var(--color-accent);color:var(--color-primary);">Celebrations</span>
          </div>
        </div>
      </div>

      <!-- News 3 -->
      <div class="news-card bg-white rounded-lg overflow-hidden shadow-sm border" style="border-color:rgba(0,0,0,.07);">
        <a href="#" class="block">
          <img src="https://img.youtube.com/vi/8mmY3ZMm9BQ/mqdefault.jpg" alt="News 3" class="w-full h-48 object-cover">
        </a>
        <div class="p-4">
          <div class="flex items-center gap-2 text-xs text-gray-500 mb-2">
            <span><i class="bi bi-person"></i> Admin</span>
            <span>•</span>
            <span><i class="bi bi-calendar"></i> 2026.01.05</span>
            <span>•</span>
            <span>1 min read</span>
          </div>
          <h3 class="text-sm font-semibold mb-2" style="color:var(--color-primary);">Sports Meet 2025 Highlights</h3>
          <p class="text-xs text-gray-600 mb-3">Highlights from the annual sports meet held on December 15, 2025. Congratulations to all winners!</p>
          <div class="flex gap-2 flex-wrap">
            <span class="px-2 py-0.5 rounded-full text-xs" style="background:var(--color-primary);color:white;">Sports</span>
            <span class="px-2 py-0.5 rounded-full text-xs" style="background:var(--color-accent);color:var(--color-primary);">News</span>
          </div>
        </div>
      </div>

      <!-- News 4 -->
      <div class="news-card bg-white rounded-lg overflow-hidden shadow-sm border" style="border-color:rgba(0,0,0,.07);">
        <a href="#" class="block">
          <img src="https://anueducation.lk/storage/sliders/01M099Z41E98R69RK5AZSK4YGC.jpg" alt="News 4" class="w-full h-48 object-cover">
        </a>
        <div class="p-4">
          <div class="flex items-center gap-2 text-xs text-gray-500 mb-2">
            <span><i class="bi bi-person"></i> Admin</span>
            <span>•</span>
            <span><i class="bi bi-calendar"></i> 2025.12.20</span>
            <span>•</span>
            <span>4 min read</span>
          </div>
          <h3 class="text-sm font-semibold mb-2" style="color:var(--color-primary);">Science Exhibition 2025</h3>
          <p class="text-xs text-gray-600 mb-3">The annual science exhibition showcased innovative projects by students from grades 6-13.</p>
          <div class="flex gap-2 flex-wrap">
            <span class="px-2 py-0.5 rounded-full text-xs" style="background:var(--color-primary);color:white;">Academic</span>
            <span class="px-2 py-0.5 rounded-full text-xs" style="background:var(--color-accent);color:var(--color-primary);">Projects</span>
          </div>
        </div>
      </div>

      <!-- News 5 -->
      <div class="news-card bg-white rounded-lg overflow-hidden shadow-sm border" style="border-color:rgba(0,0,0,.07);">
        <a href="#" class="block">
          <img src="https://anueducation.lk/images/logo.png" alt="News 5" class="w-full h-48 object-cover" style="background:var(--color-primary);">
        </a>
        <div class="p-4">
          <div class="flex items-center gap-2 text-xs text-gray-500 mb-2">
            <span><i class="bi bi-person"></i> Admin</span>
            <span>•</span>
            <span><i class="bi bi-calendar"></i> 2025.12.15</span>
            <span>•</span>
            <span>2 min read</span>
          </div>
          <h3 class="text-sm font-semibold mb-2" style="color:var(--color-primary);">New Computer Lab Inauguration</h3>
          <p class="text-xs text-gray-600 mb-3">A new computer lab with 30 modern workstations was inaugurated for student use.</p>
          <div class="flex gap-2 flex-wrap">
            <span class="px-2 py-0.5 rounded-full text-xs" style="background:var(--color-primary);color:white;">Facilities</span>
            <span class="px-2 py-0.5 rounded-full text-xs" style="background:var(--color-accent);color:var(--color-primary);">News</span>
          </div>
        </div>
      </div>

      <!-- News 6 -->
      <div class="news-card bg-white rounded-lg overflow-hidden shadow-sm border" style="border-color:rgba(0,0,0,.07);">
        <a href="#" class="block">
          <img src="https://anueducation.lk/images/emblem.png" alt="News 6" class="w-full h-48 object-cover" style="background:var(--color-primary);">
        </a>
        <div class="p-4">
          <div class="flex items-center gap-2 text-xs text-gray-500 mb-2">
            <span><i class="bi bi-person"></i> Admin</span>
            <span>•</span>
            <span><i class="bi bi-calendar"></i> 2025.12.01</span>
            <span>•</span>
            <span>1 min read</span>
          </div>
          <h3 class="text-sm font-semibold mb-2" style="color:var(--color-primary);">Term End Examination Schedule</h3>
          <p class="text-xs text-gray-600 mb-3">Term end examinations will be held from December 10-20, 2025. Timetable available in the office.</p>
          <div class="flex gap-2 flex-wrap">
            <span class="px-2 py-0.5 rounded-full text-xs" style="background:var(--color-primary);color:white;">Academic</span>
            <span class="px-2 py-0.5 rounded-full text-xs" style="background:var(--color-accent);color:var(--color-primary);">Exams</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Load More -->
    <div class="text-center mt-8">
      <button class="px-6 py-2 rounded-lg font-semibold text-white" style="background:var(--color-primary);">Load More</button>
    </div>
  </div>
</section>

<!-- FOOTER -->
<footer style="background:var(--color-dark);padding:40px 0 20px;color:#cbd5e1;">
  <div class="max-w-7xl mx-auto px-4">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-6">
      <div>
        <div class="font-bold mb-3" style="color:var(--color-accent);">නාච්චාදූව මුස්ලිම් මහා විද්‍යාලය</div>
        <div class="text-sm space-y-1" style="color:rgba(255,255,255,.6);">
          <div>+942280311</div>
          <div>nachchaduwa.mv@gmail.com</div>
          <div>නව නගරය, නාච්චාදූව</div>
        </div>
      </div>
      <div>
        <h6 class="text-white font-bold mb-3">ඉක්මන් සබැඳි</h6>
        <a href="/" class="block text-sm mb-1" style="color:rgba(255,255,255,.6);">මුල් පිටුව</a>
        <a href="/history-si.php" class="block text-sm mb-1" style="color:rgba(255,255,255,.6);">අප ගැන</a>
        <a href="/clubs-si.php" class="block text-sm mb-1" style="color:rgba(255,255,255,.6);">සංගම් සහ සමාජ</a>
        <a href="/anthem-si.php" class="block text-sm mb-1" style="color:rgba(255,255,255,.6);">ගීතිකාව</a>
        <a href="/album.php" class="block text-sm mb-1" style="color:rgba(255,255,255,.6);">ඡායාරූප ගැලරිය</a>
        <a href="/news-si.php" class="block text-sm mb-1" style="color:rgba(255,255,255,.6);">පුවත්</a>
        <a href="/contact.php" class="block text-sm mb-1" style="color:rgba(255,255,255,.6);">අමතන්න</a>
      </div>
      <div>
        <h6 class="text-white font-bold mb-3">අප අනුගමනය කරන්න</h6>
        <div class="flex gap-2">
          <a href="https://www.facebook.com/p/Nachchaduwa-muslim-maha-vidyala-100057583623835/" target="_blank" class="w-9 h-9 rounded-lg flex items-center justify-center text-white" style="background:#1877f2;"><i class="bi bi-facebook"></i></a>
          <a href="https://www.youtube.com/@anuradhapurazonaleducation" target="_blank" class="w-9 h-9 rounded-lg flex items-center justify-center text-white" style="background:#ff0000;"><i class="bi bi-youtube"></i></a>
        </div>
      </div>
    </div>
    <div class="text-center text-sm pt-4" style="border-top:1px solid rgba(255,255,255,.08);color:rgba(255,255,255,.5);">
      <div class="-left">Copyright © 2026 A/Nachchaduwa Muslim Maha Vidyalaya. All Rights Reserved.</div>
      <div class="-right">Carefully handcrafted with &nbsp;&nbsp;<i class="icon ion ion-ios-heart love-heart"></i>&nbsp;&nbsp; by <a href="https://zuse.lk" target="_blank">iGN3 Technologies (Pvt) Ltd</a>.</div>
    </div>
  </div>
</footer>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script>AOS.init({duration:800,once:true,offset:50});</script>
</body>
</html>