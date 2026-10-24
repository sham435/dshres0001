<!DOCTYPE html>
<html lang="si" dir="ltr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ගීතිකාව - නාච්චාදූව මුස්ලිම් මහා විද්‍යාලය</title>
<meta name="description" content="නාච්චාදූව මුස්ලිම් මහා විද්‍යාලය - ගීතිකාව">
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
.anthem-verse { line-height: 2; }
.anthem-verse p { margin-bottom: 1rem; }
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
        <a href="/anthem-si.php" class="px-2 py-0.5 rounded text-xs bg-[var(--color-accent)] text-[var(--color-primary)] font-bold">සිං</a>
        <a href="/anthem-en.php" class="px-2 py-0.5 rounded text-xs border border-white/40 hover:bg-white/10">EN</a>
        <a href="/anthem-ta.php" class="px-2 py-0.5 rounded text-xs border border-white/40 hover:bg-white/10">த</a>
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
      <a href="/anthem-si.php" class="text-white/85 py-4 px-3.5 text-sm hover:text-white hover:bg-white/10">ගීතිකාව</a>
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
    <a href="/anthem-si.php" class="block py-3 px-5 text-white/85 border-b border-white/10">ගීතිකාව</a>
    <a href="/contact.php" class="block py-3 px-5 text-white/85 border-b border-white/10">අමතන්න</a>
  </div>
</nav>

<!-- BREADCRUMB -->
<div class="bg-white border-b py-3 px-4">
  <div class="max-w-7xl mx-auto text-sm text-gray-500">
    <a href="/" class="text-[var(--color-primary)] hover:underline">Home</a> &gt; <a href="/about" class="text-[var(--color-primary)] hover:underline">About</a> &gt; <span class="font-semibold" style="color:var(--color-primary);">School Anthem</span>
  </div>
</div>

<!-- PAGE TITLE -->
<section class="py-8 bg-white border-b">
  <div class="max-w-7xl mx-auto px-4">
    <h1 class="text-2xl md:text-3xl font-bold" style="color:var(--color-primary);">ගීතිකාව</h1>
    <p class="text-sm text-gray-500 mt-1">School Anthem</p>
  </div>
</section>

<!-- ANTHEM CONTENT -->
<section class="py-12" style="background:var(--color-background);">
  <div class="max-w-3xl mx-auto px-4">
    <div class="bg-white rounded-2xl p-8 md:p-12 shadow-sm border" style="border-color:rgba(0,0,0,.07);">
      <div class="anthem-verse text-center" style="font-size:1.1rem;line-height:2.2;">
        <p>Lyceum our Alma Mater, may thy torch of learning burn,<br>
        In body, mind and spirit for thy teaching we all yearn,<br>
        Like mighty Apollo let's stand with strength and courage bold<br>
        With wisdom from Aristotle, our future may thee mould.</p>

        <p>Silver and blue, the colours blend on the flag that bears our name,<br>
        Silver for the cold, cold ice and blue from the hottest flame.<br>
        We pledge to thee our loyalty, now till the end of days<br>
        Lyceum our own dear school, a school we proudly praise.</p>

        <p>Let's all unite and progress forth, walking hand in hand<br>
        Like children of one family in our dear Motherland<br>
        Teach us to lead and follow too, be honest, true and kind<br>
        Gain knowledge day by day and love all mankind.</p>

        <p>Let's respect every teacher and the rules of school obey,<br>
        Be loyal to our friends and seek the Truth always<br>
        Help us to face tomorrow and uphold thy glorious name<br>
        And always, wherever we are, your name to acclaim.</p>
      </div>
      <div class="mt-8 pt-6 border-t text-center" style="border-color:rgba(0,0,0,.07);">
        <p class="text-sm font-semibold" style="color:var(--color-primary);">Lyrics and Music: <span style="color:var(--color-accent);">Kalasuri Lylie Godridge</span></p>
        <p class="text-xs text-gray-500 mt-1">Year of Composition: 1993</p>
      </div>
    </div>
  </div>
</section>

<!-- QUICK LINKS -->
<section class="py-12" style="background:var(--color-primary);">
  <div class="max-w-7xl mx-auto px-4">
    <h3 class="text-center text-white text-xl font-bold mb-6">ඉක්මන් සබැඳි</h3>
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
      <a href="/" class="flex flex-col items-center gap-2 p-5 rounded-2xl no-underline transition-all duration-200 hover:-translate-y-1" style="background:rgba(255,255,255,.08);border:1px solid rgba(201,168,76,.2);">
        <i class="bi bi-house text-2xl" style="color:var(--color-accent);"></i>
        <span class="text-xs md:text-sm text-white/90 text-center font-medium">මුල් පිටුව</span>
      </a>
      <a href="/history-si.php" class="flex flex-col items-center gap-2 p-5 rounded-2xl no-underline transition-all duration-200 hover:-translate-y-1" style="background:rgba(255,255,255,.08);border:1px solid rgba(201,168,76,.2);">
        <i class="bi bi-info-circle text-2xl" style="color:var(--color-accent);"></i>
        <span class="text-xs md:text-sm text-white/90 text-center font-medium">අප ගැන</span>
      </a>
      <a href="/clubs-si.php" class="flex flex-col items-center gap-2 p-5 rounded-2xl no-underline transition-all duration-200 hover:-translate-y-1" style="background:rgba(255,255,255,.08);border:1px solid rgba(201,168,76,.2);">
        <i class="bi bi-people text-2xl" style="color:var(--color-accent);"></i>
        <span class="text-xs md:text-sm text-white/90 text-center font-medium">සංගම්</span>
      </a>
      <a href="/history-si.php" class="flex flex-col items-center gap-2 p-5 rounded-2xl no-underline transition-all duration-200 hover:-translate-y-1" style="background:rgba(255,255,255,.08);border:1px solid rgba(201,168,76,.2);">
        <i class="bi bi-clock-history text-2xl" style="color:var(--color-accent);"></i>
        <span class="text-xs md:text-sm text-white/90 text-center font-medium">ඉතිහාසය</span>
      </a>
      <a href="/album.php" class="flex flex-col items-center gap-2 p-5 rounded-2xl no-underline transition-all duration-200 hover:-translate-y-1" style="background:rgba(255,255,255,.08);border:1px solid rgba(201,168,76,.2);">
        <i class="bi bi-images text-2xl" style="color:var(--color-accent);"></i>
        <span class="text-xs md:text-sm text-white/90 text-center font-medium">ඡායාරූප</span>
      </a>
      <a href="/contact.php" class="flex flex-col items-center gap-2 p-5 rounded-2xl no-underline transition-all duration-200 hover:-translate-y-1" style="background:rgba(255,255,255,.08);border:1px solid rgba(201,168,76,.2);">
        <i class="bi bi-envelope text-2xl" style="color:var(--color-accent);"></i>
        <span class="text-xs md:text-sm text-white/90 text-center font-medium">අමතන්න</span>
      </a>
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
        <a href="/anthem-si.php" class="block text-sm mb-1" style="color:rgba(255,255,255,.6);">ගීතිකාව</a>
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