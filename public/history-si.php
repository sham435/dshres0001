<!DOCTYPE html>
<html lang="si" dir="ltr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ඉතිහාසය - නාච්චාදූව මුස්ලිම් මහා විද්‍යාලය</title>
<meta name="description" content="නාච්චාදූව මුස්ලිම් මහා විද්‍යාලය - ඉතිහාසය">
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
.history-card { transition: all 0.3s; }
.history-card:hover { transform: translateY(-4px); box-shadow: 0 8px 24px rgba(0,0,0,.12); }
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
      <a href="/" class="text-white/85 py-4 px-3.5 text-sm hover:text-white hover:bg-white/10">ප්‍රතිඵල</a>
      <a href="/contact" class="text-white/85 py-4 px-3.5 text-sm hover:text-white hover:bg-white/10">අමතන්න</a>
    </div>
    <button class="md:hidden text-white p-3" onclick="document.getElementById('mobileNav').style.display=document.getElementById('mobileNav').style.display==='block'?'none':'block'">☰</button>
  </div>
  <div id="mobileNav" class="md:hidden bg-[var(--color-primary)]" style="display:none;">
    <a href="/" class="block py-3 px-5 text-white/85 border-b border-white/10">මුල්පිටුව</a>
    <a href="/history-si.php" class="block py-3 px-5 text-white/85 border-b border-white/10">අප ගැන</a>
    <a href="/clubs-si.php" class="block py-3 px-5 text-white/85 border-b border-white/10">සංගම් සහ සමාජ</a>
    <a href="/anthem-si.php" class="block py-3 px-5 text-white/85 border-b border-white/10">ගීතිකාව</a>
    <a href="/album.php" class="block py-3 px-5 text-white/85 border-b border-white/10">ඡායාරූප ගැලරිය</a>
    <a href="/" class="block py-3 px-5 text-white/85 border-b border-white/10">ප්‍රතිඵල</a>
    <a href="/contact" class="block py-3 px-5 text-white/85 border-b border-white/10">අමතන්න</a>
  </div>
</nav>

<!-- BREADCRUMB -->
<div class="bg-white border-b py-3 px-4">
  <div class="max-w-7xl mx-auto text-sm text-gray-500">
    <a href="/" class="text-[var(--color-primary)] hover:underline">Home</a> &gt; <span class="font-semibold" style="color:var(--color-primary);">ඉතිහාසය</span>
  </div>
</div>

<!-- HERO -->
<section class="py-12" style="background:linear-gradient(135deg,var(--color-primary),#7c3aed);">
  <div class="max-w-7xl mx-auto px-4 text-center text-white">
    <h1 class="text-2xl md:text-4xl font-bold mb-4">ඉතිහාසය</h1>
    <p class="text-sm md:text-lg text-white/85 max-w-2xl mx-auto">නාච්චාදූව මුස්ලිම් මහා විද්‍යාලයේ ඉතිහාසය සහ උරුමය</p>
  </div>
</section>

<!-- POEM SECTION -->
<section class="py-12 bg-white">
  <div class="max-w-3xl mx-auto px-4 text-center">
    <h2 class="text-xl md:text-2xl font-bold mb-6" style="color:var(--color-primary);">Our College</h2>
    <div class="text-sm text-gray-700 leading-relaxed space-y-4" style="font-style:italic;">
      <p>God gave us Royal from of old<br>
      And adorned her panels with glory,<br>
      He gave her stalwarts, strong and bold<br>
      To shine in song and story.</p>
      <p>In this great province of Ceylon<br>
      Stands Royal's situation,<br>
      Its noble walls, its stately halls<br>
      Compel our admirations</p>
      <p>The worthy Mr. Corea is great,<br>
      A really fine Head Master<br>
      And through his studies, long and late,<br>
      Success comes fast and faster.</p>
      <p>Long flourish Royal, may she stand<br>
      Erect and strong for ever<br>
      And may her sons on every band<br>
      Be spurred to high endeavour.</p>
      <p>No Royalist can read without thrill<br>
      Of Royal's advancing glory,<br>
      And may her gallant victories still<br>
      Be told in song and story</p>
      <p class="text-xs text-gray-500 mt-4">R. S.<br>Royal College Magazine<br>Mid – Summer Term<br>1946</p>
    </div>
  </div>
</section>

<!-- FOUNDERS, HEADMASTERS & PRINCIPALS -->
<section class="py-12" style="background:var(--color-background);">
  <div class="max-w-7xl mx-auto px-4">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
      <div>
        <h2 class="text-xl md:text-2xl font-bold mb-4" style="color:var(--color-primary);">Founders, Headmasters & Principals</h2>
        <p class="text-sm text-gray-600 leading-relaxed">Illuminating the way for countless lives, these gentlemen were at the forefront of leading the institution to greatness. Sir Robert Wilmot Horton integrated the principles of his alma mater into the Colombo Academy, and thus the school was modeled upon the ideals of Eton. Similarly, each eminent luminary who took its reigns left its mark upon the foundation of the College, molding it into what it is today.</p>
        <a href="/" class="inline-block mt-4 px-6 py-2 rounded font-semibold text-sm" style="background:var(--color-accent);color:var(--color-primary);">Read More</a>
      </div>
      <div class="grid grid-cols-2 gap-4">
        <div class="rounded-2xl overflow-hidden shadow-sm">
          <img src="https://anueducation.lk/images/emblem.png" alt="Robert Wilmot Horton" class="w-full h-48 object-cover" style="background:var(--color-primary);">
          <div class="p-3 bg-white text-center text-sm font-semibold" style="color:var(--color-primary);">Robert Wilmot Horton</div>
        </div>
        <div class="rounded-2xl overflow-hidden shadow-sm">
          <img src="https://anueducation.lk/images/logo.png" alt="Harry Leslie Reed" class="w-full h-48 object-cover" style="background:var(--color-primary);">
          <div class="p-3 bg-white text-center text-sm font-semibold" style="color:var(--color-primary);">Harry Leslie Reed</div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- IN MEMORIAM -->
<section class="py-12 bg-white">
  <div class="max-w-7xl mx-auto px-4">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
      <div class="rounded-2xl overflow-hidden shadow-sm">
        <img src="https://anueducation.lk/images/emblem.png" alt="In Memoriam" class="w-full h-64 object-cover" style="background:var(--color-dark);">
      </div>
      <div>
        <h2 class="text-xl md:text-2xl font-bold mb-4" style="color:var(--color-primary);">In Memoriam</h2>
        <p class="text-sm text-gray-600 leading-relaxed">Having opened its gates nearly two centuries ago, the Royal College has had to endure its fair share of wars. Be it the 'War to end all Wars', the 'Second World War' or the 30 years of civil war within Sri Lanka, the College has given birth to countless decorated soldiers who have given life and limb for their country to lead the charge towards victory and provide us all with the blessing of peace.</p>
        <a href="/" class="inline-block mt-4 px-6 py-2 rounded font-semibold text-sm" style="background:var(--color-accent);color:var(--color-primary);">Read More</a>
      </div>
    </div>
  </div>
</section>

<!-- ARCHIVES -->
<section class="py-12" style="background:var(--color-background);">
  <div class="max-w-7xl mx-auto px-4">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
      <div>
        <h2 class="text-xl md:text-2xl font-bold mb-4" style="color:var(--color-primary);">Archives</h2>
        <p class="text-sm text-gray-600 leading-relaxed">Owing to the unwavering love for the College embedded in each Royalist's heart, accounts of nearly all occasions of note have been passed down through generations in meticulous detail. As such, the Royal College Archives is host to a near inconceivable amount of historic documents and memorabilia relating to The Royal College and The Royal College Preparatory School. Stepping foot over its threshold is akin to walking through the sands of time; the experience continues to ensure the legacy of this institution is never forgotten.</p>
      </div>
      <div class="rounded-2xl overflow-hidden shadow-sm">
        <img src="https://anueducation.lk/images/logo.png" alt="Archives" class="w-full h-64 object-cover" style="background:var(--color-primary);">
      </div>
    </div>
  </div>
</section>

<!-- QUICK LINKS -->
<section class="py-12" style="background:var(--color-primary);">
  <div class="max-w-7xl mx-auto px-4">
    <h3 class="text-center text-white text-xl font-bold mb-6">ඉක්මන් සබැඳි</h3>
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
      <a href="/downloads" class="flex flex-col items-center gap-2 p-5 rounded-2xl no-underline transition-all duration-200 hover:-translate-y-1" style="background:rgba(255,255,255,.08);border:1px solid rgba(201,168,76,.2);">
        <i class="bi bi-download text-2xl" style="color:var(--color-accent);"></i>
        <span class="text-xs md:text-sm text-white/90 text-center font-medium">බාගැනීම්</span>
      </a>
      <a href="/notices" class="flex flex-col items-center gap-2 p-5 rounded-2xl no-underline transition-all duration-200 hover:-translate-y-1" style="background:rgba(255,255,255,.08);border:1px solid rgba(201,168,76,.2);">
        <i class="bi bi-megaphone text-2xl" style="color:var(--color-accent);"></i>
        <span class="text-xs md:text-sm text-white/90 text-center font-medium">නිවේදන</span>
      </a>
      <a href="/students" class="flex flex-col items-center gap-2 p-5 rounded-2xl no-underline transition-all duration-200 hover:-translate-y-1" style="background:rgba(255,255,255,.08);border:1px solid rgba(201,168,76,.2);">
        <i class="bi bi-people text-2xl" style="color:var(--color-accent);"></i>
        <span class="text-xs md:text-sm text-white/90 text-center font-medium">සිසුන්</span>
      </a>
      <a href="/exams" class="flex flex-col items-center gap-2 p-5 rounded-2xl no-underline transition-all duration-200 hover:-translate-y-1" style="background:rgba(255,255,255,.08);border:1px solid rgba(201,168,76,.2);">
        <i class="bi bi-award text-2xl" style="color:var(--color-accent);"></i>
        <span class="text-xs md:text-sm text-white/90 text-center font-medium">ප්‍රතිඵල</span>
      </a>
      <a href="/timetable" class="flex flex-col items-center gap-2 p-5 rounded-2xl no-underline transition-all duration-200 hover:-translate-y-1" style="background:rgba(255,255,255,.08);border:1px solid rgba(201,168,76,.2);">
        <i class="bi bi-calendar-event text-2xl" style="color:var(--color-accent);"></i>
        <span class="text-xs md:text-sm text-white/90 text-center font-medium">කාලසටහන</span>
      </a>
      <a href="/contact" class="flex flex-col items-center gap-2 p-5 rounded-2xl no-underline transition-all duration-200 hover:-translate-y-1" style="background:rgba(255,255,255,.08);border:1px solid rgba(201,168,76,.2);">
        <i class="bi bi-envelope text-2xl" style="color:var(--color-accent);"></i>
        <span class="text-xs md:text-sm text-white/90 text-center font-medium">අමතන්න</span>
      </a>
    </div>
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
        <h6 class="text-white font-bold mb-3">පාසල් තොරතුරු</h6>
        <div class="text-sm space-y-1" style="color:rgba(255,255,255,.6);">
          <div>1C වර්ගය (ශ්‍රේණිය 1-13)</div>
          <div>දෙමළ මාධ්‍ය</div>
          <div>1920 සිට සේවයේ</div>
          <div>සංගණන අංකය: 19508</div>
        </div>
      </div>
      <div>
        <h6 class="text-white font-bold mb-3">ඉක්මන් සබැඳි</h6>
        <a href="/" class="block text-sm mb-1" style="color:rgba(255,255,255,.6);">මුල් පිටුව</a>
        <a href="/history-si.php" class="block text-sm mb-1" style="color:rgba(255,255,255,.6);">අප ගැන</a>
        <a href="/clubs-si.php" class="block text-sm mb-1" style="color:rgba(255,255,255,.6);">සංගම් සහ සමාජ</a>
        <a href="/anthem-si.php" class="block text-sm mb-1" style="color:rgba(255,255,255,.6);">ගීතිකාව</a>
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