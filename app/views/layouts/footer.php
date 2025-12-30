    <!-- Footer -->
    <footer class="bg-dark text-white mt-5 py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <h5><i class="fas fa-car me-2"></i>Bán Xe Ô Tô</h5>
                    <p class="text-muted">Nền tảng mua bán xe ô tô uy tín và chất lượng hàng đầu Việt Nam.</p>
                    <div class="social-links">
                        <a href="#" class="text-white me-3"><i class="fab fa-facebook fa-lg"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-twitter fa-lg"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-instagram fa-lg"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-youtube fa-lg"></i></a>
                    </div>
                </div>
                <div class="col-md-2 mb-3">
                    <h6>Sản phẩm</h6>
                    <ul class="list-unstyled">
                        <li><a href="/cars" class="text-muted text-decoration-none">Xe bán</a></li>
                        <li><a href="/cars?type=new" class="text-muted text-decoration-none">Xe mới</a></li>
                        <li><a href="/cars?type=used" class="text-muted text-decoration-none">Xe đã qua sử dụng</a></li>
                    </ul>
                </div>
                <div class="col-md-2 mb-3">
                    <h6>Hỗ trợ</h6>
                    <ul class="list-unstyled">
                        <li><a href="/about" class="text-muted text-decoration-none">Giới thiệu</a></li>
                        <li><a href="/contact" class="text-muted text-decoration-none">Liên hệ</a></li>
                        <li><a href="/faq" class="text-muted text-decoration-none">FAQ</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-3">
                    <h6>Liên hệ</h6>
                    <ul class="list-unstyled text-muted">
                        <li><i class="fas fa-map-marker-alt me-2"></i>123 Đường ABC, Quận XYZ, TP.HCM</li>
                        <li><i class="fas fa-phone me-2"></i>0123 456 789</li>
                        <li><i class="fas fa-envelope me-2"></i>info@banxeoto.com</li>
                    </ul>
                </div>
            </div>
            <hr class="border-secondary">
            <div class="text-center text-muted">
                <p class="mb-0">&copy; <?= date('Y') ?> Bán Xe Ô Tô. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Auto hide alerts after 5 seconds -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
            alerts.forEach(alert => {
                setTimeout(() => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            });
        });
    </script>
</body>
</html>
