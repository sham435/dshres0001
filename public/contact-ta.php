<!DOCTYPE html>
<html lang="ta" dir="ltr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>தொடர்பு - நாச்சாடுவ முஸ்லிம் மகா வித்தியாலயம்</title>
<meta name="description" content="நாச்சாடுவ முஸ்லிம் மகா வித்தியாலயம் - தொடர்பு">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Tamil:wght@400;500;600;700&family=Noto+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
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
.info-card { background: white; border-radius: 1rem; padding: 24px; box-shadow: 0 1px 8px rgba(0,0,0,.05); border: 1px solid rgba(0,0,0,.07); }
[data-bs-theme="dark"] .info-card { background: #1e293b; border-color: rgba(255,255,255,.1); color: #e2e8f0; }
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
      <a href="https://www.youtube.com/@anuradhapurazonaleducation" target="_blank" class="text-white hover:text-[var(--color-accent)]"><i class="bi bi-youtube"></i></a>
      <div class="flex gap-1">
        <a href="/contact.php" class="px-2 py-0.5 rounded text-xs bg-[var(--color-accent)] text-[var(--color-primary)] font-bold">SI</a>
        <a href="/contact-en.php" class="px-2 py-0.5 rounded text-xs border border-white/40 hover:bg-white/10">EN</a>
        <a href="/contact-ta.php" class="px-2 py-0.5 rounded text-xs border border-white/40 hover:bg-white/10">த</a>
      </div>
    </div>
  </div>
</div>

<!-- STICKY NAV -->
<nav class="sticky top-0 z-50 bg-[var(--color-dark)] shadow-lg">
  <div class="max-w-7xl mx-auto px-5 flex justify-between items-center">
    <div class="hidden md:flex">
      <a href="/" class="text-white/85 py-4 px-3.5 text-sm hover:text-white hover:bg-white/10">முகப்பு</a>
      <a href="/" class="text-white/85 py-4 px-3.5 text-sm hover:text-white hover:bg-white/10">எங்களை பற்றி</a>
      <a href="/clubs-si.php" class="text-white/85 py-4 px-3.5 text-sm hover:text-white hover:bg-white/10">சங்கங்கள் மற்றும் சமூகங்கள்</a>
      <a href="/history-si.php" class="text-white/85 py-4 px-3.5 text-sm hover:text-white hover:bg-white/10">வரலாறு</a>
      <a href="/contact.php" class="text-white/85 py-4 px-3.5 text-sm hover:text-white hover:bg-white/10">தொடர்பு</a>
    </div>
    <button class="md:hidden text-white p-3" onclick="document.getElementById('mobileNav').style.display=document.getElementById('mobileNav').style.display==='block'?'none':'block'">☰</button>
  </div>
  <div id="mobileNav" class="md:hidden bg-[var(--color-primary)]" style="display:none;">
    <a href="/" class="block py-3 px-5 text-white/85 border-b border-white/10">முகப்பு</a>
    <a href="/" class="block py-3 px-5 text-white/85 border-b border-white/10">எங்களை பற்றி</a>
    <a href="/clubs-si.php" class="block py-3 px-5 text-white/85 border-b border-white/10">சங்கங்கள் மற்றும் சமூகங்கள்</a>
    <a href="/history-si.php" class="block py-3 px-5 text-white/85 border-b border-white/10">வரலாறு</a>
    <a href="/contact.php" class="block py-3 px-5 text-white/85 border-b border-white/10">தொடர்பு</a>
  </div>
</nav>

<!-- HERO -->
<section class="py-12" style="background:linear-gradient(135deg,var(--color-primary),#7c3aed);">
  <div class="max-w-7xl mx-auto px-4 text-center text-white">
    <h1 class="text-2xl md:text-4xl font-bold mb-4">தொடர்பு</h1>
    <p class="text-sm md:text-lg text-white/85 max-w-2xl mx-auto">நாச்சாடுவ முஸ்லிம் மகா வித்தியாலயம் - தொடர்பு கொள்ளுங்கள்</p>
  </div>
</section>

<!-- CONதCT CONTENT -->
<section class="py-12" style="background:var(--color-background);">
  <div class="max-w-7xl mx-auto px-4">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
      <!-- Contact Form -->
      <div class="info-card">
        <h3 class="text-xl font-bold mb-4" style="color:var(--color-primary);">எங்களுக்கு செய்தி அனுப்புங்கள்</h3>
        <form method="POST" action="/contact" class="space-y-4">
          <div>
            <label class="block text-sm font-medium mb-1">பெயர்</label>
            <input type="text" name="name" class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]" required>
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">மின்னஞ்சல்</label>
            <input type="email" name="email" class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]" required>
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">செய்தி</label>
            <textarea name="message" rows="5" class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]" required></textarea>
          </div>
          <button type="submit" class="px-6 py-2 rounded-lg font-semibold text-white" style="background:var(--color-primary);">அனுப்பு</button>
        </form>
      </div>

      <!-- Contact Info -->
      <div class="info-card">
        <h3 class="text-xl font-bold mb-4" style="color:var(--color-primary);">தொடர்பு மற்றும் இருப்பிடம்</h3>
        <div class="space-y-4">
          <div class="flex items-start gap-3">
            <div class="w-10 h-10 rounded-full flex items-center justify-center" style="background:var(--color-primary);color:white;"><i class="bi bi-geo-alt"></i></div>
            <div>
              <div class="font-semibold">முகவரி</div>
              <div class="text-sm text-gray-600">புதிய நகரம், நாச்சாடுவ</div>
            </div>
          </div>
          <div class="flex items-start gap-3">
            <div class="w-10 h-10 rounded-full flex items-center justify-center" style="background:var(--color-primary);color:white;"><i class="bi bi-telephone"></i></div>
            <div>
              <div class="font-semibold">தொலைபேசி</div>
              <div class="text-sm text-gray-600">+942280311</div>
            </div>
          </div>
          <div class="flex items-start gap-3">
            <div class="w-10 h-10 rounded-full flex items-center justify-center" style="background:var(--color-primary);color:white;"><i class="bi bi-envelope"></i></div>
            <div>
              <div class="font-semibold">மின்னஞ்சல்</div>
              <div class="text-sm text-gray-600">nachchaduwa.mv@gmail.com</div>
            </div>
          </div>
          <div class="flex items-start gap-3">
            <div class="w-10 h-10 rounded-full flex items-center justify-center" style="background:var(--color-primary);color:white;"><i class="bi bi-clock"></i></div>
            <div>
              <div class="font-semibold">நேரம்</div>
              <div class="text-sm text-gray-600">திங்கள்-வியாழன்: 7.00 AM - 2.00 PM</div>
              <div class="text-sm text-gray-600">வெள்ளி: 7.00 AM - 11.35 AM</div>
            </div>
          </div>
          <div class="flex items-start gap-3">
            <div class="w-10 h-10 rounded-full flex items-center justify-center" style="background:var(--color-primary);color:white;"><i class="bi bi-map"></i></div>
            <div>
              <div class="font-semibold">இருப்பிடம்</div>
              <div class="text-sm text-gray-600">8.24697153, 80.46342566</div>
              <a href="https://www.google.com/maps/dir/?api=1&destination=8.24697153,80.46342566" class="text-sm" style="color:var(--color-primary);" target="_blank">வழிகளைப் பெறுங்கள் →</a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Map -->
    <div class="mt-8">
      <div class="info-card">
        <h3 class="text-xl font-bold mb-4" style="color:var(--color-primary);">இருப்பிட வரைபடம்</h3>
        <div id="contact-map" style="width:100%;height:400px;border-radius:0.75rem;"></div>
      </div>
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
        <a href="/contact.php" class="block text-sm mb-1" style="color:rgba(255,255,255,.6);">தொடர்பு</a>
      </div>
      <div>
        <h6 class="text-white font-bold mb-3">எங்களை பின்தொடருங்கள்</h6>
        <a href="https://www.facebook.com/p/Nachchaduwa-muslim-maha-vidyala-100057583623835/" target="_blank" class="text-white hover:text-[var(--color-accent)]"><i class="bi bi-facebook"></i> Facebook</a>
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
<script>
  // Leaflet map
  const map = L.map('contact-map').setView([8.24697153, 80.46342566], 15);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors'
  }).addTo(map);
  L.circleMarker([8.24697153, 80.46342566], {
    radius: 10, fillColor: '#3d1a78', color: '#fff', weight: 2, fillOpacity: 0.9
  }).addTo(map).bindPopup('<b>Nachchaduwa Muslim Maha Vidyalaya</b><br>New Town, Nachchaduwa');
</script>
<script>AOS.init({duration:800,once:true,offset:50});</script>
</body>
</html>