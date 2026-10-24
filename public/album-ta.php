<!DOCTYPE html>
<html lang="ta" dir="ltr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>புகைப்பட தொகுப்பு - நாச்சாடுவ முஸ்லிம் மகா வித்தியாலயம்</title>
<meta name="description" content="நாச்சாடுவ முஸ்லிம் மகா வித்தியாலயம் - புகைப்பட தொகுப்பு">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Tamil:wght@400;500;600;700&family=Noto+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
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
:lang(si) { font-family: 'Noto Sans Tamil', sans-serif; }
[x-cloak] { display: none !important; }
.album-card { transition: all 0.3s; }
.album-card:hover { transform: translateY(-4px); box-shadow: 0 8px 24px rgba(0,0,0,.12); }
.gallery-img { transition: transform 0.3s; }
.gallery-img:hover { transform: scale(1.05); }
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
        <div class="font-bold text-[var(--color-accent)] text-sm md:text-lg">நாச்சாடுவ முஸ்லிம் மகா வித்தியாலயம்</div>
        <div class="text-white/80 text-xs">அனுராதபுரம் மாவட்டம், வட மத்திய மாகாணம்</div>
      </div>
    </div>
    <div class="flex items-center gap-3 text-sm">
      <span class="hidden md:inline"><i class="bi bi-telephone"></i> +942280311</span>
      <a href="https://www.facebook.com/p/Nachchaduwa-muslim-maha-vidyala-100057583623835/" target="_blank" class="text-white hover:text-[var(--color-accent)]"><i class="bi bi-facebook"></i></a>
      <div class="flex gap-1">
        <a href="/album.php" class="px-2 py-0.5 rounded text-xs bg-[var(--color-accent)] text-[var(--color-primary)] font-bold">SI</a>
        <a href="/album-en.php" class="px-2 py-0.5 rounded text-xs border border-white/40 hover:bg-white/10">EN</a>
        <a href="/album-ta.php" class="px-2 py-0.5 rounded text-xs border border-white/40 hover:bg-white/10">த</a>
      </div>
    </div>
  </div>
</div>

<!-- STICKY NAV -->
<nav class="sticky top-0 z-50 bg-[var(--color-dark)] shadow-lg">
  <div class="max-w-7xl mx-auto px-5 flex justify-between items-center">
    <div class="hidden md:flex">
      <a href="/" class="text-white/85 py-4 px-3.5 text-sm hover:text-white hover:bg-white/10">முகப்பு</a>
      <a href="/history-si.php" class="text-white/85 py-4 px-3.5 text-sm hover:text-white hover:bg-white/10">எங்களை பற்றி</a>
      <a href="/clubs-si.php" class="text-white/85 py-4 px-3.5 text-sm hover:text-white hover:bg-white/10">சங்கங்கள் மற்றும் சமூகங்கள்</a>
      <a href="/history-si.php" class="text-white/85 py-4 px-3.5 text-sm hover:text-white hover:bg-white/10">வரலாறு</a>
      <a href="/album.php" class="text-white/85 py-4 px-3.5 text-sm hover:text-white hover:bg-white/10">புகைப்பட தொகுப்பு</a>
      <a href="/contact.php" class="text-white/85 py-4 px-3.5 text-sm hover:text-white hover:bg-white/10">தொடர்பு</a>
    </div>
    <button class="md:hidden text-white p-3" onclick="document.getElementById('mobileNav').style.display=document.getElementById('mobileNav').style.display==='block'?'none':'block'">☰</button>
  </div>
  <div id="mobileNav" class="md:hidden bg-[var(--color-primary)]" style="display:none;">
    <a href="/" class="block py-3 px-5 text-white/85 border-b border-white/10">முகப்பு</a>
    <a href="/history-si.php" class="block py-3 px-5 text-white/85 border-b border-white/10">எங்களை பற்றி</a>
    <a href="/clubs-si.php" class="block py-3 px-5 text-white/85 border-b border-white/10">சங்கங்கள் மற்றும் சமூகங்கள்</a>
    <a href="/history-si.php" class="block py-3 px-5 text-white/85 border-b border-white/10">வரலாறு</a>
    <a href="/album.php" class="block py-3 px-5 text-white/85 border-b border-white/10">புகைப்பட தொகுப்பு</a>
    <a href="/contact.php" class="block py-3 px-5 text-white/85 border-b border-white/10">தொடர்பு</a>
  </div>
</nav>

<!-- BREADCRUMB -->
<div class="bg-white border-b py-3 px-4">
  <div class="max-w-7xl mx-auto text-sm text-gray-500">
    <a href="/" class="text-[var(--color-primary)] hover:underline">Home</a> &gt; <span class="font-semibold" style="color:var(--color-primary);">புகைப்பட தொகுப்பு</span>
  </div>
</div>

<!-- PAGE TITLE -->
<section class="py-8 bg-white border-b">
  <div class="max-w-7xl mx-auto px-4">
    <h1 class="text-2xl md:text-3xl font-bold" style="color:var(--color-primary);">Photo Gallery</h1>
  </div>
</section>

<!-- ALBUM GRID (Lyceum style) -->
<section class="py-12" style="background:var(--color-background);">
  <div class="max-w-7xl mx-auto px-4">
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
      
      <!-- Album 1 -->
      <a href="#" class="block bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow border" style="border-color:rgba(0,0,0,.07);">
        <div class="relative">
          <img src="https://anueducation.lk/storage/sliders/01M099Q7PNT8WTCJ28R6TM2BC9.jpg" alt="Annual Sports Meet 2025" class="w-full h-48 object-cover">
        </div>
        <div class="p-4">
          <h3 class="text-sm font-semibold" style="color:var(--color-primary);">Annual Sports Meet 2025</h3>
        </div>
      </a>

      <!-- Album 2 -->
      <a href="#" class="block bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow border" style="border-color:rgba(0,0,0,.07);">
        <div class="relative">
          <img src="https://anueducation.lk/storage/sliders/01M099VY59EPBB6FXK1F2QBVHB.jpg" alt="Prize Giving 2025" class="w-full h-48 object-cover">
        </div>
        <div class="p-4">
          <h3 class="text-sm font-semibold" style="color:var(--color-primary);">Prize Giving 2025</h3>
        </div>
      </a>

      <!-- Album 3 -->
      <a href="#" class="block bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow border" style="border-color:rgba(0,0,0,.07);">
        <div class="relative">
          <img src="https://img.youtube.com/vi/8mmY3ZMm9BQ/mqdefault.jpg" alt="Sports Meet 2025" class="w-full h-48 object-cover">
        </div>
        <div class="p-4">
          <h3 class="text-sm font-semibold" style="color:var(--color-primary);">Sports Meet 2025</h3>
        </div>
      </a>

      <!-- Album 4 -->
      <a href="#" class="block bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow border" style="border-color:rgba(0,0,0,.07);">
        <div class="relative">
          <img src="https://anueducation.lk/storage/sliders/01M099Z41E98R69RK5AZSK4YGC.jpg" alt="Science Exhibition" class="w-full h-48 object-cover">
        </div>
        <div class="p-4">
          <h3 class="text-sm font-semibold" style="color:var(--color-primary);">Science Exhibition</h3>
        </div>
      </a>

      <!-- Album 5 -->
      <a href="#" class="block bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow border" style="border-color:rgba(0,0,0,.07);">
        <div class="relative">
          <img src="https://anueducation.lk/images/logo.png" alt="School Events" class="w-full h-48 object-cover" style="background:var(--color-primary);">
        </div>
        <div class="p-4">
          <h3 class="text-sm font-semibold" style="color:var(--color-primary);">School Events</h3>
        </div>
      </a>

      <!-- Album 6 -->
      <a href="#" class="block bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow border" style="border-color:rgba(0,0,0,.07);">
        <div class="relative">
          <img src="https://anueducation.lk/images/emblem.png" alt="Cultural Events" class="w-full h-48 object-cover" style="background:var(--color-primary);">
        </div>
        <div class="p-4">
          <h3 class="text-sm font-semibold" style="color:var(--color-primary);">Cultural Events</h3>
        </div>
      </a>

      <!-- Album 7 -->
      <a href="#" class="block bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow border" style="border-color:rgba(0,0,0,.07);">
        <div class="relative">
          <img src="https://img.youtube.com/vi/On2r2g5N9Ck/mqdefault.jpg" alt="Arts Festival" class="w-full h-48 object-cover">
        </div>
        <div class="p-4">
          <h3 class="text-sm font-semibold" style="color:var(--color-primary);">Arts Festival</h3>
        </div>
      </a>

      <!-- Album 8 -->
      <a href="#" class="block bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow border" style="border-color:rgba(0,0,0,.07);">
        <div class="relative">
          <img src="https://img.youtube.com/vi/bOogxpiCo0U/mqdefault.jpg" alt="TV Sathsar" class="w-full h-48 object-cover">
        </div>
        <div class="p-4">
          <h3 class="text-sm font-semibold" style="color:var(--color-primary);">TV Sathsar</h3>
        </div>
      </a>
    </div>
  </div>
</section>

<!-- FOOTER -->
<footer style="background:var(--color-dark);padding:40px 0 20px;color:#cbd5e1;">
  <div class="max-w-7xl mx-auto px-4">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-6">
      <div>
        <div class="font-bold mb-3" style="color:var(--color-accent);">நாச்சாடுவ முஸ்லிம் மகா வித்தியாலயம்</div>
        <div class="text-sm space-y-1" style="color:rgba(255,255,255,.6);">
          <div>+942280311</div>
          <div>nachchaduwa.mv@gmail.com</div>
          <div>புதிய நகரம், நாச்சாடுவ</div>
        </div>
      </div>
      <div>
        <h6 class="text-white font-bold mb-3">விரைவு இணைப்புகள்</h6>
        <a href="/" class="block text-sm mb-1" style="color:rgba(255,255,255,.6);">முகப்பு</a>
        <a href="/" class="block text-sm mb-1" style="color:rgba(255,255,255,.6);">எங்களை பற்றி</a>
        <a href="/clubs-si.php" class="block text-sm mb-1" style="color:rgba(255,255,255,.6);">சங்கங்கள் மற்றும் சமூகங்கள்</a>
        <a href="/history-si.php" class="block text-sm mb-1" style="color:rgba(255,255,255,.6);">வரலாறு</a>
        <a href="/album.php" class="block text-sm mb-1" style="color:rgba(255,255,255,.6);">புகைப்பட தொகுப்பு</a>
        <a href="/contact.php" class="block text-sm mb-1" style="color:rgba(255,255,255,.6);">தொடர்பு</a>
      </div>
      <div>
        <h6 class="text-white font-bold mb-3">எங்களை பின்தொடருங்கள்</h6>
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