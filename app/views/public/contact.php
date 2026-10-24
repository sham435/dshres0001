<div class="page-header">
    <h2><i class="bi bi-envelope"></i> Contact Us</h2>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><h5 class="mb-0">Send us a message</h5></div>
            <div class="card-body">
                <form method="POST" action="/contact">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Message</label>
                        <textarea class="form-control" name="message" rows="5" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-send"></i> Send Message</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><h5 class="mb-0">Contact Information</h5></div>
            <div class="card-body">
                <p><i class="bi bi-geo-alt text-primary"></i> <strong>Address:</strong><br>New Town, Nachchaduwa</p>
                <p><i class="bi bi-telephone text-primary"></i> <strong>Phone:</strong><br>+942280311</p>
                <p><i class="bi bi-envelope text-primary"></i> <strong>Email:</strong><br>nachchaduwa.mv@gmail.com</p>
                <p><i class="bi bi-clock text-primary"></i> <strong>Office Hours:</strong><br>Mon-Thu: 7.00 AM - 2.00 PM<br>Fri: 7.00 AM - 11.35 AM</p>
                <hr>
                <a href="https://www.google.com/maps/dir/?api=1&destination=8.24697153,80.46342566" class="btn btn-outline-primary" target="_blank">
                    <i class="bi bi-map"></i> View on Google Maps
                </a>
            </div>
        </div>
    </div>
</div>