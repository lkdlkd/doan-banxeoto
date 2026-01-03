<?php 
if (!defined('BASE_URL')) { require_once __DIR__ . '/../../../../config/config.php'; }

require_once __DIR__ . '/../../../models/UserModel.php';
require_once __DIR__ . '/../../../models/OrderModel.php';

$userModel = new UserModel();
$orderModel = new OrderModel();

$users = $userModel->getAll();
$totalUsers = count($users);

// Tính số lượng admin và user thường
$adminCount = count(array_filter($users, fn($u) => ($u['role'] ?? 'user') === 'admin'));
$userCount = $totalUsers - $adminCount;

// Xử lý form
$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'add') {
        $fullName = trim($_POST['full_name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $password = $_POST['password'] ?? '';
        $role = $_POST['role'] ?? 'user';
        $avatar = '';
        
        // Xử lý avatar
        if (!empty($_FILES['avatar_file']['name'])) {
            $uploadDir = BASE_PATH . '/assets/images/avatars/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $fileName = time() . '_' . basename($_FILES['avatar_file']['name']);
            $targetFile = $uploadDir . $fileName;
            if (move_uploaded_file($_FILES['avatar_file']['tmp_name'], $targetFile)) {
                $avatar = BASE_URL . '/assets/images/avatars/' . $fileName;
            }
        } elseif (!empty($_POST['avatar_url'])) {
            $avatar = trim($_POST['avatar_url']);
        }
        
        if (empty($fullName) || empty($email) || empty($password)) {
            $message = 'Vui lòng điền đầy đủ thông tin bắt buộc';
            $messageType = 'error';
        } elseif ($userModel->emailExists($email)) {
            $message = 'Email đã được sử dụng';
            $messageType = 'error';
        } else {
            $userModel->createUser([
                'full_name' => $fullName,
                'email' => $email,
                'phone' => $phone,
                'password' => $password,
                'role' => $role,
                'avatar' => $avatar
            ]);
            $message = 'Thêm người dùng thành công!';
            $messageType = 'success';
            
            $users = $userModel->getAll();
            $totalUsers = count($users);
            $adminCount = count(array_filter($users, fn($u) => ($u['role'] ?? 'user') === 'admin'));
            $userCount = $totalUsers - $adminCount;
        }
    } elseif ($action === 'edit') {
        $id = intval($_POST['id']);
        $fullName = trim($_POST['full_name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $role = $_POST['role'] ?? 'user';
        
        $data = [
            'full_name' => $fullName,
            'email' => $email,
            'phone' => $phone,
            'role' => $role
        ];
        
        // Xử lý avatar nếu có
        if (!empty($_FILES['avatar_file']['name'])) {
            $uploadDir = BASE_PATH . '/assets/images/avatars/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $fileName = time() . '_' . basename($_FILES['avatar_file']['name']);
            $targetFile = $uploadDir . $fileName;
            if (move_uploaded_file($_FILES['avatar_file']['tmp_name'], $targetFile)) {
                $data['avatar'] = BASE_URL . '/assets/images/avatars/' . $fileName;
            }
        } elseif (!empty($_POST['avatar_url'])) {
            $data['avatar'] = trim($_POST['avatar_url']);
        }
        
        // Cập nhật password nếu có
        if (!empty($_POST['password'])) {
            $data['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
        }
        
        $userModel->update($id, $data);
        $message = 'Cập nhật người dùng thành công!';
        $messageType = 'success';
        
        $users = $userModel->getAll();
    } elseif ($action === 'delete') {
        $id = intval($_POST['id']);
        $userModel->delete($id);
        $message = 'Xóa người dùng thành công!';
        $messageType = 'success';
        
        $users = $userModel->getAll();
        $totalUsers = count($users);
        $adminCount = count(array_filter($users, fn($u) => ($u['role'] ?? 'user') === 'admin'));
        $userCount = $totalUsers - $adminCount;
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý người dùng - AutoCar Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin-common.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin-users.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin-modal.css">
</head>
<body>
    <?php $activePage = 'users'; include __DIR__ . '/../layouts/sidebar.php'; ?>

    <main class="admin-main">
        <header class="admin-header">
            <h1>Quản lý người dùng</h1>
            <div class="header-profile">
                <img src="https://ui-avatars.com/api/?name=Admin&background=D4AF37&color=fff" alt="Admin">
            </div>
        </header>

        <div class="admin-content">
            <div class="page-header">
                <h2>Danh sách người dùng (<?= $totalUsers ?>)</h2>
                <button class="btn-primary" onclick="openAddModal()">
                    <i class="fas fa-user-plus"></i> Thêm người dùng
                </button>
            </div>

            <!-- Stats -->
            <div class="stats-row">
                <div class="stat-card">
                    <div class="stat-icon blue"><i class="fas fa-users"></i></div>
                    <div class="stat-info">
                        <h3><?= $totalUsers ?></h3>
                        <p>Tổng tài khoản</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon green"><i class="fas fa-user"></i></div>
                    <div class="stat-info">
                        <h3><?= $userCount ?></h3>
                        <p>Khách hàng</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon gold"><i class="fas fa-user-shield"></i></div>
                    <div class="stat-info">
                        <h3><?= $adminCount ?></h3>
                        <p>Quản trị viên</p>
                    </div>
                </div>
            </div>

            <!-- Search -->
            <div class="filters-bar">
                <div class="filter-group">
                    <label>Vai trò:</label>
                    <select id="filterRole">
                        <option value="">Tất cả</option>
                        <option value="admin">Admin</option>
                        <option value="user">User</option>
                    </select>
                </div>
                <div class="filter-search">
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchUser" placeholder="Tìm theo tên, email...">
                </div>
            </div>

            <?php if ($totalUsers === 0): ?>
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-users"></i>
                </div>
                <h3>Chưa có người dùng nào</h3>
                <p>Bắt đầu thêm người dùng mới.</p>
                <button class="btn-primary" onclick="openAddModal()">
                    <i class="fas fa-user-plus"></i> Thêm người dùng đầu tiên
                </button>
            </div>
            <?php else: ?>
            <!-- Users Table -->
            <div class="card">
                <table class="users-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Người dùng</th>
                            <th>Email</th>
                            <th>Số điện thoại</th>
                            <th>Vai trò</th>
                            <th>Ngày đăng ký</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                        <tr data-role="<?= $user['role'] ?? 'user' ?>">
                            <td><span class="user-id">#<?= $user['id'] ?></span></td>
                            <td>
                                <div class="user-info">
                                    <img src="<?= $user['avatar'] ?: 'https://ui-avatars.com/api/?name=' . urlencode($user['full_name'] ?? 'User') . '&background=D4AF37&color=fff' ?>" alt="">
                                    <div class="user-info-text">
                                        <h4><?= htmlspecialchars($user['full_name'] ?? 'Chưa đặt tên') ?></h4>
                                    </div>
                                </div>
                            </td>
                            <td><span class="user-email"><?= htmlspecialchars($user['email']) ?></span></td>
                            <td><span class="user-phone"><?= htmlspecialchars($user['phone'] ?? 'Chưa cập nhật') ?></span></td>
                            <td>
                                <span class="user-role <?= $user['role'] ?? 'user' ?>">
                                    <?= ($user['role'] ?? 'user') === 'admin' ? 'Admin' : 'User' ?>
                                </span>
                            </td>
                            <td><span class="user-date"><?= date('d/m/Y', strtotime($user['created_at'])) ?></span></td>
                            <td>
                                <div class="action-btns">
                                    <button class="action-btn edit" title="Sửa" onclick="openEditModal(<?= htmlspecialchars(json_encode($user)) ?>)">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <?php if (($user['role'] ?? 'user') !== 'admin'): ?>
                                    <button class="action-btn delete" title="Xóa" onclick="openDeleteModal(<?= $user['id'] ?>, '<?= addslashes($user['full_name'] ?? $user['email']) ?>')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </main>

    <!-- Add/Edit Modal -->
    <div class="modal" id="userModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modalTitle"><i class="fas fa-user-plus"></i> Thêm người dùng</h3>
                <button class="modal-close" onclick="closeModal('userModal')">&times;</button>
            </div>
            <form id="userForm" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" id="formAction" value="add">
                <input type="hidden" name="id" id="userId">
                
                <div class="modal-body">
                    <!-- Avatar Upload -->
                    <div class="image-upload-section">
                        <label>Ảnh đại diện</label>
                        <div class="upload-tabs">
                            <button type="button" class="upload-tab active" onclick="switchUploadTab('file')">
                                <i class="fas fa-upload"></i> Tải từ máy
                            </button>
                            <button type="button" class="upload-tab" onclick="switchUploadTab('url')">
                                <i class="fas fa-link"></i> Dùng link
                            </button>
                        </div>
                        
                        <div class="upload-panel active" id="filePanel">
                            <div class="file-upload-area" id="dropZone">
                                <input type="file" name="avatar_file" id="avatarFile" accept="image/*">
                                <div class="upload-icon">
                                    <i class="fas fa-user-circle"></i>
                                </div>
                                <h4>Chọn ảnh đại diện</h4>
                                <p>PNG, JPG tối đa 2MB</p>
                            </div>
                        </div>
                        
                        <div class="upload-panel" id="urlPanel">
                            <div class="url-input-wrapper">
                                <input type="url" name="avatar_url" id="avatarUrl" placeholder="https://example.com/avatar.jpg">
                                <i class="fas fa-link"></i>
                            </div>
                        </div>
                        
                        <div class="image-preview-container" id="imagePreview" style="display: none;">
                            <div class="image-preview-wrapper">
                                <img id="previewImg" src="" alt="Preview">
                                <div class="preview-actions">
                                    <button type="button" class="preview-action-btn remove" onclick="removeImage()">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Họ và tên <span class="required">*</span></label>
                        <input type="text" name="full_name" id="userFullName" required placeholder="Nguyễn Văn A">
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Email <span class="required">*</span></label>
                            <input type="email" name="email" id="userEmail" required placeholder="email@example.com">
                        </div>
                        <div class="form-group">
                            <label>Số điện thoại</label>
                            <input type="tel" name="phone" id="userPhone" placeholder="0901234567">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Mật khẩu <span class="required" id="pwdRequired">*</span></label>
                            <input type="password" name="password" id="userPassword" placeholder="Tối thiểu 6 ký tự">
                            <p class="form-info" id="pwdInfo" style="display: none;"><i class="fas fa-info-circle"></i> Để trống nếu không đổi mật khẩu</p>
                        </div>
                        <div class="form-group">
                            <label>Vai trò</label>
                            <select name="role" id="userRole">
                                <option value="user">User</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="button" class="btn-secondary" onclick="closeModal('userModal')">
                        <i class="fas fa-times"></i> Hủy
                    </button>
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save"></i> Lưu
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal" id="deleteModal">
        <div class="modal-content modal-confirm">
            <div class="modal-body">
                <div class="confirm-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <h4>Xác nhận xóa?</h4>
                <p>Bạn có chắc chắn muốn xóa người dùng</p>
                <p class="item-name" id="deleteName"></p>
                <p style="color: #999; font-size: 0.85rem;">Hành động này không thể hoàn tác</p>
            </div>
            <form method="POST" class="form-actions">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" id="deleteId">
                <button type="button" class="btn-secondary" onclick="closeModal('deleteModal')">
                    <i class="fas fa-times"></i> Hủy
                </button>
                <button type="submit" class="btn-danger">
                    <i class="fas fa-trash"></i> Xóa
                </button>
            </form>
        </div>
    </div>

    <!-- Toast Notification -->
    <?php if ($message): ?>
    <div class="toast <?= $messageType ?>" id="toast">
        <i class="fas fa-<?= $messageType === 'success' ? 'check-circle' : 'exclamation-circle' ?>"></i>
        <span><?= htmlspecialchars($message) ?></span>
    </div>
    <?php endif; ?>

    <script>
        <?php if ($message): ?>
        document.addEventListener('DOMContentLoaded', function() {
            const toast = document.getElementById('toast');
            setTimeout(() => toast.classList.add('show'), 100);
            setTimeout(() => toast.classList.remove('show'), 4000);
        });
        <?php endif; ?>

        // Open Add Modal
        function openAddModal() {
            document.getElementById('modalTitle').innerHTML = '<i class="fas fa-user-plus"></i> Thêm người dùng';
            document.getElementById('formAction').value = 'add';
            document.getElementById('userId').value = '';
            document.getElementById('userFullName').value = '';
            document.getElementById('userEmail').value = '';
            document.getElementById('userPhone').value = '';
            document.getElementById('userPassword').value = '';
            document.getElementById('userPassword').required = true;
            document.getElementById('pwdRequired').style.display = 'inline';
            document.getElementById('pwdInfo').style.display = 'none';
            document.getElementById('userRole').value = 'user';
            removeImage();
            document.getElementById('userModal').classList.add('active');
        }

        // Open Edit Modal
        function openEditModal(user) {
            document.getElementById('modalTitle').innerHTML = '<i class="fas fa-edit"></i> Sửa người dùng';
            document.getElementById('formAction').value = 'edit';
            document.getElementById('userId').value = user.id;
            document.getElementById('userFullName').value = user.full_name || '';
            document.getElementById('userEmail').value = user.email;
            document.getElementById('userPhone').value = user.phone || '';
            document.getElementById('userPassword').value = '';
            document.getElementById('userPassword').required = false;
            document.getElementById('pwdRequired').style.display = 'none';
            document.getElementById('pwdInfo').style.display = 'block';
            document.getElementById('userRole').value = user.role || 'user';
            
            if (user.avatar) {
                document.getElementById('avatarUrl').value = user.avatar;
                document.getElementById('previewImg').src = user.avatar;
                document.getElementById('imagePreview').style.display = 'block';
            } else {
                removeImage();
            }
            
            document.getElementById('userModal').classList.add('active');
        }

        // Open Delete Modal
        function openDeleteModal(id, name) {
            document.getElementById('deleteId').value = id;
            document.getElementById('deleteName').textContent = name;
            document.getElementById('deleteModal').classList.add('active');
        }

        // Close Modal
        function closeModal(modalId) {
            document.getElementById(modalId).classList.remove('active');
        }

        // Switch Upload Tab
        function switchUploadTab(tab) {
            document.querySelectorAll('.upload-tab').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.upload-panel').forEach(p => p.classList.remove('active'));
            
            if (tab === 'file') {
                document.querySelector('.upload-tab:first-child').classList.add('active');
                document.getElementById('filePanel').classList.add('active');
            } else {
                document.querySelector('.upload-tab:last-child').classList.add('active');
                document.getElementById('urlPanel').classList.add('active');
            }
        }

        // File Upload Preview
        const avatarFile = document.getElementById('avatarFile');
        const dropZone = document.getElementById('dropZone');
        
        avatarFile.addEventListener('change', function(e) {
            if (e.target.files[0]) {
                previewFile(e.target.files[0]);
            }
        });

        dropZone.addEventListener('dragover', function(e) {
            e.preventDefault();
            this.classList.add('dragover');
        });
        dropZone.addEventListener('dragleave', function(e) {
            e.preventDefault();
            this.classList.remove('dragover');
        });
        dropZone.addEventListener('drop', function(e) {
            e.preventDefault();
            this.classList.remove('dragover');
            const files = e.dataTransfer.files;
            if (files[0] && files[0].type.startsWith('image/')) {
                avatarFile.files = files;
                previewFile(files[0]);
            }
        });

        function previewFile(file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('previewImg').src = e.target.result;
                document.getElementById('imagePreview').style.display = 'block';
            }
            reader.readAsDataURL(file);
        }

        document.getElementById('avatarUrl').addEventListener('input', function(e) {
            if (e.target.value) {
                document.getElementById('previewImg').src = e.target.value;
                document.getElementById('imagePreview').style.display = 'block';
            }
        });

        function removeImage() {
            document.getElementById('previewImg').src = '';
            document.getElementById('imagePreview').style.display = 'none';
            document.getElementById('avatarFile').value = '';
            document.getElementById('avatarUrl').value = '';
        }

        // Filter
        document.getElementById('filterRole').addEventListener('change', filterUsers);
        document.getElementById('searchUser').addEventListener('input', filterUsers);

        function filterUsers() {
            const role = document.getElementById('filterRole').value;
            const search = document.getElementById('searchUser').value.toLowerCase();
            
            const rows = document.querySelectorAll('.users-table tbody tr');
            rows.forEach(row => {
                const matchRole = !role || row.dataset.role === role;
                const matchSearch = !search || row.textContent.toLowerCase().includes(search);
                row.style.display = matchRole && matchSearch ? '' : 'none';
            });
        }

        // Close modal when clicking outside
        document.querySelectorAll('.modal').forEach(modal => {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    this.classList.remove('active');
                }
            });
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.querySelectorAll('.modal.active').forEach(m => m.classList.remove('active'));
            }
        });
    </script>
</body>
</html>
