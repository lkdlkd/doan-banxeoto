// AutoCar - Main JavaScript

document.addEventListener('DOMContentLoaded', function() {
    
    // ===== Header Scroll Effect =====
    const mainHeader = document.querySelector('.main-header');
    
    window.addEventListener('scroll', function() {
        if (window.scrollY > 100) {
            mainHeader.classList.add('scrolled');
        } else {
            mainHeader.classList.remove('scrolled');
        }
    });

    // ===== Scroll to Top Button =====
    const scrollTopBtn = document.getElementById('scrollTop');
    
    window.addEventListener('scroll', function() {
        if (window.scrollY > 300) {
            scrollTopBtn.classList.add('visible');
        } else {
            scrollTopBtn.classList.remove('visible');
        }
    });
    
    scrollTopBtn.addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });

    // ===== Newsletter Form Handler =====
    const newsletterForm = document.querySelector('.newsletter-form');
    
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const emailInput = this.querySelector('input[type="email"]');
            const email = emailInput.value.trim();
            
            if (email) {
                // Show success message
                alert('Cảm ơn bạn đã đăng ký nhận tin! Chúng tôi sẽ gửi thông tin mới nhất đến ' + email);
                emailInput.value = '';
            }
        });
    }

    // ===== Smooth Scroll for Anchor Links =====
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const targetId = this.getAttribute('href');
            if (targetId !== '#') {
                e.preventDefault();
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    targetElement.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            }
        });
    });

    // ===== Mobile Menu Toggle =====
    const menuToggle = document.querySelector('.menu-toggle');
    const navMenu = document.querySelector('.nav-menu');
    
    if (menuToggle && navMenu) {
        menuToggle.addEventListener('click', function() {
            navMenu.classList.toggle('active');
            this.classList.toggle('active');
        });
    }

    // ===== Active Navigation Link =====
    const currentPath = window.location.pathname;
    const navLinks = document.querySelectorAll('.nav-menu a');
    
    navLinks.forEach(link => {
        if (link.getAttribute('href') === currentPath) {
            link.classList.add('active');
        }
    });

    // ===== Brand Category Tabs =====
    const tabBtns = document.querySelectorAll('.tab-btn');
    const brandPanels = document.querySelectorAll('.brands-panel');

    tabBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const category = this.dataset.category;

            // Remove active from all tabs
            tabBtns.forEach(tab => tab.classList.remove('active'));
            // Add active to clicked tab
            this.classList.add('active');

            // Hide all panels
            brandPanels.forEach(panel => panel.classList.remove('active'));
            // Show selected panel
            const targetPanel = document.querySelector(`.brands-panel[data-panel="${category}"]`);
            if (targetPanel) {
                targetPanel.classList.add('active');
            }
        });
    });

    // ===== Cart Functions =====
    
    // Update cart count display
    function updateCartCount() {
        fetch('/cart/info')
            .then(response => response.json())
            .then(data => {
                const cartCounts = document.querySelectorAll('.cart-count');
                cartCounts.forEach(el => {
                    el.textContent = data.count;
                    if (data.count > 0) {
                        el.classList.add('has-items');
                    } else {
                        el.classList.remove('has-items');
                    }
                });
            })
            .catch(err => console.log('Cart info error:', err));
    }
    
    // Initialize cart count
    updateCartCount();
    
    // Add to cart
    window.addToCart = function(carId, btn) {
        if (btn) btn.disabled = true;
        
        fetch('/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'car_id=' + carId
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Đã thêm xe vào giỏ hàng!', 'success');
                updateCartCount();
                if (btn) {
                    btn.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg> Đã thêm';
                    btn.classList.add('added');
                }
            } else {
                showNotification(data.message, 'warning');
                if (btn) btn.disabled = false;
            }
        })
        .catch(err => {
            console.error('Add to cart error:', err);
            showNotification('Có lỗi xảy ra!', 'error');
            if (btn) btn.disabled = false;
        });
    };
    
    // Remove from cart via AJAX
    window.removeFromCartAjax = function(carId, element) {
        if (!confirm('Bạn có chắc muốn xóa xe này khỏi giỏ hàng?')) return;
        
        fetch('/cart/remove', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'car_id=' + carId
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Đã xóa xe khỏi giỏ hàng!', 'success');
                updateCartCount();
                // Remove item from DOM
                const cartItem = element.closest('.cart-item');
                if (cartItem) {
                    cartItem.style.opacity = '0';
                    cartItem.style.transform = 'translateX(50px)';
                    setTimeout(() => cartItem.remove(), 300);
                }
                // Update total if exists
                if (data.total !== undefined) {
                    const totalEl = document.querySelector('.summary-row.total .value');
                    if (totalEl) {
                        totalEl.textContent = new Intl.NumberFormat('vi-VN').format(data.total) + ' ₫';
                    }
                }
            } else {
                showNotification(data.message, 'error');
            }
        })
        .catch(err => {
            console.error('Remove from cart error:', err);
            showNotification('Có lỗi xảy ra!', 'error');
        });
    };
    
    // Notification system
    window.showNotification = function(message, type = 'info') {
        // Remove existing notification
        const existing = document.querySelector('.notification');
        if (existing) existing.remove();
        
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.innerHTML = `
            <span class="notification-message">${message}</span>
            <button class="notification-close">&times;</button>
        `;
        
        document.body.appendChild(notification);
        
        // Show animation
        setTimeout(() => notification.classList.add('show'), 10);
        
        // Close button
        notification.querySelector('.notification-close').addEventListener('click', () => {
            notification.classList.remove('show');
            setTimeout(() => notification.remove(), 300);
        });
        
        // Auto close
        setTimeout(() => {
            if (notification.parentNode) {
                notification.classList.remove('show');
                setTimeout(() => notification.remove(), 300);
            }
        }, 4000);
    };

    // ===== Compare Functions =====
    
    // Update compare count display
    function updateCompareCount() {
        fetch('/compare/info')
            .then(response => response.json())
            .then(data => {
                const compareCounts = document.querySelectorAll('.compare-count');
                compareCounts.forEach(el => {
                    el.textContent = data.count + '/' + data.max;
                    if (data.count > 0) {
                        el.classList.add('has-items');
                    } else {
                        el.classList.remove('has-items');
                    }
                });
            })
            .catch(err => console.log('Compare info error:', err));
    }
    
    // Initialize compare count
    updateCompareCount();
    
    // Add to compare
    window.addToCompare = function(carId, btn) {
        if (btn) btn.disabled = true;
        
        fetch('/compare/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'car_id=' + carId
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Đã thêm xe vào danh sách so sánh!', 'success');
                updateCompareCount();
                if (btn) {
                    btn.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg> Đã thêm';
                    btn.classList.add('added');
                }
            } else {
                showNotification(data.message, 'warning');
                if (btn) btn.disabled = false;
            }
        })
        .catch(err => {
            console.error('Add to compare error:', err);
            showNotification('Có lỗi xảy ra!', 'error');
            if (btn) btn.disabled = false;
        });
    };
    
    // Remove from compare via AJAX
    window.removeFromCompareAjax = function(carId, element) {
        if (!confirm('Bạn có chắc muốn xóa xe này khỏi danh sách so sánh?')) return;
        
        fetch('/compare/remove', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'car_id=' + carId
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Đã xóa xe khỏi danh sách so sánh!', 'success');
                updateCompareCount();
                // Reload page if on compare page
                if (window.location.pathname === '/compare') {
                    window.location.reload();
                }
            } else {
                showNotification(data.message, 'error');
            }
        })
        .catch(err => {
            console.error('Remove from compare error:', err);
            showNotification('Có lỗi xảy ra!', 'error');
        });
    };

});
