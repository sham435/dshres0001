<!DOCTYPE html>
<html lang="si" dir="ltr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>සංගම් සහ සමාජ - නාච්චාදූව මුස්ලිම් මහා විද්‍යාලය</title>
<meta name="description" content="නාච්චාදූව මුස්ලිම් මහා විද්‍යාලය - සංගම් සහ සමාජ">
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
.club-card { transition: all 0.2s; }
.club-card:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,.1); }
.category-header { border-bottom: 3px solid var(--color-accent); padding-bottom: 8px; display: inline-block; }
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
      <span class="hidden md:inline"><i class="bi bi-telephone"></i> 025-2223030</span>
      <a href="https://www.facebook.com/p/Nachchaduwa-muslim-maha-vidyala-100057583623835/" target="_blank" class="text-white hover:text-[var(--color-accent)]"><i class="bi bi-facebook"></i></a>
      <a href="https://www.youtube.com/@anuradhapurazonaleducation" target="_blank" class="text-white hover:text-[var(--color-accent)]"><i class="bi bi-youtube"></i></a>
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
      <a href="/album.php" class="text-white/85 py-4 px-3.5 text-sm hover:text-white hover:bg-white/10">ඡායාරූප ගැලරිය</a>
      <a href="/" class="text-white/85 py-4 px-3.5 text-sm hover:text-white hover:bg-white/10">ප්‍රතිඵල</a>
      <a href="/" class="text-white/85 py-4 px-3.5 text-sm hover:text-white hover:bg-white/10">පුවත්</a>
      <a href="/contact" class="text-white/85 py-4 px-3.5 text-sm hover:text-white hover:bg-white/10">අමතන්න</a>
    </div>
    <button class="md:hidden text-white p-3" onclick="document.getElementById('mobileNav').style.display=document.getElementById('mobileNav').style.display==='block'?'none':'block'">☰</button>
  </div>
  <div id="mobileNav" class="md:hidden bg-[var(--color-primary)]" style="display:none;">
    <a href="/" class="block py-3 px-5 text-white/85 border-b border-white/10">මුල්පිටුව</a>
    <a href="/history-si.php" class="block py-3 px-5 text-white/85 border-b border-white/10">අප ගැන</a>
    <a href="/clubs-si.php" class="block py-3 px-5 text-white/85 border-b border-white/10">සංගම් සහ සමාජ</a>
    <a href="/album.php" class="block py-3 px-5 text-white/85 border-b border-white/10">ඡායාරූප ගැලරිය</a>
    <a href="/" class="block py-3 px-5 text-white/85 border-b border-white/10">ප්‍රතිඵල</a>
    <a href="/" class="block py-3 px-5 text-white/85 border-b border-white/10">පුවත්</a>
    <a href="/contact" class="block py-3 px-5 text-white/85 border-b border-white/10">අමතන්න</a>
  </div>
</nav>

<!-- BREADCRUMB -->
<div class="bg-white border-b py-3 px-4">
  <div class="max-w-7xl mx-auto text-sm text-gray-500">
    <a href="/" class="text-[var(--color-primary)] hover:underline">Home</a> &gt; <span class="font-semibold" style="color:var(--color-primary);">Clubs & Societies</span>
  </div>
</div>

<!-- HERO -->
<section class="py-12" style="background:linear-gradient(135deg,var(--color-primary),#7c3aed);">
  <div class="max-w-7xl mx-auto px-4 text-center text-white">
    <h1 class="text-2xl md:text-4xl font-bold mb-4">සංගම් සහ සමාජ</h1>
    <p class="text-sm md:text-lg text-white/85 max-w-2xl mx-auto">Every Club & Society at our school has something unique to offer, kindling passion, fostering unity, and producing all-round characters.</p>
  </div>
</section>

<!-- INTRO -->
<section class="py-10 bg-white">
  <div class="max-w-4xl mx-auto px-4 text-center">
    <h2 class="text-xl md:text-2xl font-bold mb-4" style="color:var(--color-primary);">Discover Your Passions</h2>
    <p class="text-sm text-gray-600 leading-relaxed">There are over 20 active student organizations at the school, all of which promise different invaluable lessons and experiences. With multiple avenues to accommodate the wide variety of endeavours, there is a Club or Society to drive forth the advancement of every aspect of the school and the nation.</p>
  </div>
</section>

<!-- CLUB CATEGORIES -->
<section class="py-10" style="background:var(--color-background);">
  <div class="max-w-7xl mx-auto px-4">
    <h2 class="text-xl md:text-2xl font-bold mb-8" style="color:var(--color-primary);border-bottom:3px solid var(--color-accent);padding-bottom:8px;display:inline-block;">Club Categories</h2>

    <!-- Aesthetic -->
    <div class="mb-8">
      <h3 class="text-lg font-bold mb-3" style="color:var(--color-primary);">Aesthetic Clubs</h3>
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
        <div class="club-card bg-white rounded-xl p-4 border" style="border-color:rgba(0,0,0,.07);"><div class="font-semibold text-sm" style="color:var(--color-primary);">Art Circle</div></div>
        <div class="club-card bg-white rounded-xl p-4 border" style="border-color:rgba(0,0,0,.07);"><div class="font-semibold text-sm" style="color:var(--color-primary);">Dancing Society</div></div>
        <div class="club-card bg-white rounded-xl p-4 border" style="border-color:rgba(0,0,0,.07);"><div class="font-semibold text-sm" style="color:var(--color-primary);">Music Society</div></div>
        <div class="club-card bg-white rounded-xl p-4 border" style="border-color:rgba(0,0,0,.07);"><div class="font-semibold text-sm" style="color:var(--color-primary);">Drama Society</div></div>
      </div>
    </div>

    <!-- Academic -->
    <div class="mb-8">
      <h3 class="text-lg font-bold mb-3" style="color:var(--color-primary);">Educational Clubs</h3>
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
        <div class="club-card bg-white rounded-xl p-4 border" style="border-color:rgba(0,0,0,.07);"><div class="font-semibold text-sm" style="color:var(--color-primary);">Science Club</div></div>
        <div class="club-card bg-white rounded-xl p-4 border" style="border-color:rgba(0,0,0,.07);"><div class="font-semibold text-sm" style="color:var(--color-primary);">Mathematics Society</div></div>
        <div class="club-card bg-white rounded-xl p-4 border" style="border-color:rgba(0,0,0,.07);"><div class="font-semibold text-sm" style="color:var(--color-primary);">Commerce Society</div></div>
        <div class="club-card bg-white rounded-xl p-4 border" style="border-color:rgba(0,0,0,.07);"><div class="font-semibold text-sm" style="color:var(--color-primary);">History Club</div></div>
      </div>
    </div>

    <!-- Community Service -->
    <div class="mb-8">
      <h3 class="text-lg font-bold mb-3" style="color:var(--color-primary);">Community Service Clubs</h3>
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
        <div class="club-card bg-white rounded-xl p-4 border" style="border-color:rgba(0,0,0,.07);"><div class="font-semibold text-sm" style="color:var(--color-primary);">Interact Club</div></div>
        <div class="club-card bg-white rounded-xl p-4 border" style="border-color:rgba(0,0,0,.07);"><div class="font-semibold text-sm" style="color:var(--color-primary);">Red Cross Society</div></div>
        <div class="club-card bg-white rounded-xl p-4 border" style="border-color:rgba(0,0,0,.07);"><div class="font-semibold text-sm" style="color:var(--color-primary);">Environmental Club</div></div>
        <div class="club-card bg-white rounded-xl p-4 border" style="border-color:rgba(0,0,0,.07);"><div class="font-semibold text-sm" style="color:var(--color-primary);">Social Services League</div></div>
      </div>
    </div>

    <!-- Language -->
    <div class="mb-8">
      <h3 class="text-lg font-bold mb-3" style="color:var(--color-primary);">Language Clubs</h3>
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
        <div class="club-card bg-white rounded-xl p-4 border" style="border-color:rgba(0,0,0,.07);"><div class="font-semibold text-sm" style="color:var(--color-primary);">English Literary Association</div></div>
        <div class="club-card bg-white rounded-xl p-4 border" style="border-color:rgba(0,0,0,.07);"><div class="font-semibold text-sm" style="color:var(--color-primary);">Tamil Literary Association</div></div>
        <div class="club-card bg-white rounded-xl p-4 border" style="border-color:rgba(0,0,0,.07);"><div class="font-semibold text-sm" style="color:var(--color-primary);">Sinhala Literary Unit</div></div>
        <div class="club-card bg-white rounded-xl p-4 border" style="border-color:rgba(0,0,0,.07);"><div class="font-semibold text-sm" style="color:var(--color-primary);">Debating Society</div></div>
      </div>
    </div>

    <!-- Technology -->
    <div class="mb-8">
      <h3 class="text-lg font-bold mb-3" style="color:var(--color-primary);">Technological Clubs</h3>
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
        <div class="club-card bg-white rounded-xl p-4 border" style="border-color:rgba(0,0,0,.07);"><div class="font-semibold text-sm" style="color:var(--color-primary);">Computer Society</div></div>
        <div class="club-card bg-white rounded-xl p-4 border" style="border-color:rgba(0,0,0,.07);"><div class="font-semibold text-sm" style="color:var(--color-primary);">Science Club</div></div>
        <div class="club-card bg-white rounded-xl p-4 border" style="border-color:rgba(0,0,0,.07);"><div class="font-semibold text-sm" style="color:var(--color-primary);">Young Inventors Club</div></div>
      </div>
    </div>
  </div>
</section>

<!-- FELICITATION -->
<section class="py-10 bg-white">
  <div class="max-w-4xl mx-auto px-4 text-center">
    <h2 class="text-xl md:text-2xl font-bold mb-4" style="color:var(--color-primary);">සංගම් සහ සමාජ ඇගයීම් උළෙල</h2>
    <p class="text-sm text-gray-600">A night of salutations where those who persevered are bestowed a laurel to affirm and encourage the sacrifices made on their path to help society blossom. The Clubs and Societies Felicitation Ceremony is an annual affair that summons the College's finest to inspire yet another generation of bright eyed leaders.</p>
  </div>
</section>

<!-- FOOTER -->
<footer style="background:var(--color-dark);padding:40px 0 20px;color:#cbd5e1;">
  <div class="max-w-7xl mx-auto px-4">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-6">
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
        <a href="/contact" class="block text-sm mb-1" style="color:rgba(255,255,255,.6);">අමතන්න</a>
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