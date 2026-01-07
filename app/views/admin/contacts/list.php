<?php
if (!defined('BASE_URL')) {
    require_once __DIR__ . '/../../../../config/config.php';
}

require_once __DIR__ . '/../../../models/ContactModel.php';

$contactModel = new ContactModel();
$contacts = $contactModel->getAllContacts();
$totalContacts = count($contacts);

// Tính số lượng theo trạng thái
$newCount = count(array_filter($contacts, fn($c) => ($c['status'] ?? 'new') === 'new'));
$unreadCount = count(array_filter($contacts, fn($c) => ($c['status'] ?? 'unread') === 'unread'));
$readCount = count(array_filter($contacts, fn($c) => ($c['status'] ?? 'unread') === 'read'));
$repliedCount = count(array_filter($contacts, fn($c) => ($c['status'] ?? 'unread') === 'replied'));
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý liên hệ - AutoCar Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin-common.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin-contacts.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin-modal.css">
    <style>
        .stats-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 24px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            display: flex;
            align-items: center;
            gap: 18px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid rgba(212, 175, 55, 0.1);
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(212, 175, 55, 0.2);
            border-color: rgba(212, 175, 55, 0.3);
        }

        .stat-icon {
            width: 56px;
            height: 56px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, #f3f4f6, #e5e7eb);
            color: #374151;
        }

        .stat-icon::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, #D4AF37, #B8960B);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .stat-card:hover .stat-icon::before {
            opacity: 0.1;
        }

        .stat-icon.blue,
        .stat-icon.orange,
        .stat-icon.red,
        .stat-icon.green {
            background: linear-gradient(135deg, #1f2937, #374151);
            color: white;
        }

        .stat-info h3 {
            font-size: 32px;
            font-weight: 700;
            color: #1f2937;
            margin: 0 0 4px 0;
            font-family: 'Montserrat', sans-serif;
        }

        .stat-info p {
            margin: 0;
            color: #6b7280;
            font-size: 14px;
            font-weight: 500;
        }

        .filters-bar {
            background: white;
            padding: 20px 24px;
            border-radius: 12px;
            margin-bottom: 24px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            display: grid;
            grid-template-columns: auto auto 1fr;
            gap: 20px;
            align-items: center;
        }

        @media (max-width: 768px) {
            .filters-bar {
                grid-template-columns: 1fr;
            }
        }

        .filter-group {
            display: flex;
            align-items: center;
            gap: 10px;
            white-space: nowrap;
        }

        .filter-group label {
            font-weight: 600;
            color: #374151;
            font-size: 14px;
            min-width: fit-content;
        }

        .filter-group select {
            padding: 10px 36px 10px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 14px;
            color: #374151;
            background: white;
            cursor: pointer;
            transition: all 0.3s ease;
            min-width: 160px;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23374151' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
        }

        .filter-group select:hover {
            border-color: #D4AF37;
        }

        .filter-group select:focus {
            outline: none;
            border-color: #D4AF37;
            box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);
        }

        .filter-search {
            position: relative;
            min-width: 250px;
        }

        .filter-search i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: 14px;
            pointer-events: none;
        }

        .filter-search input {
            width: 100%;
            padding: 10px 16px 10px 44px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .filter-search input:hover {
            border-color: #D4AF37;
        }

        .filter-search input:focus {
            outline: none;
            border-color: #D4AF37;
            box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);
        }

        .bulk-actions-bar {
            background: linear-gradient(135deg, #1f2937, #374151);
            padding: 16px 24px;
            border-radius: 12px;
            margin-bottom: 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
        }

        .bulk-selected {
            display: flex;
            align-items: center;
            gap: 12px;
            color: white;
            font-weight: 600;
        }

        .bulk-selected input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        .bulk-buttons {
            display: flex;
            gap: 12px;
        }

        .btn-bulk {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
        }

        .btn-bulk:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
        }

        .btn-bulk.danger {
            background: linear-gradient(135deg, #ef4444, #dc2626);
        }

        .btn-bulk.danger:hover {
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
        }

        .contacts-list {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .contact-card {
            padding: 24px;
            border-bottom: 1px solid #f3f4f6;
            transition: all 0.3s ease;
            position: relative;
        }

        .contact-card:last-child {
            border-bottom: none;
        }

        .contact-card:hover {
            background: linear-gradient(to right, rgba(212, 175, 55, 0.03), transparent);
        }

        .contact-card.unread,
        .contact-card.new {
            background: linear-gradient(to right, rgba(59, 130, 246, 0.05), transparent);
            border-left: 3px solid #3b82f6;
        }

        .contact-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }

        .contact-sender {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .contact-sender input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: #D4AF37;
        }

        .contact-sender img {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            border: 2px solid rgba(212, 175, 55, 0.2);
        }

        .contact-sender-info h4 {
            margin: 0 0 4px 0;
            font-size: 16px;
            font-weight: 700;
            color: #1f2937;
        }

        .contact-sender-info p {
            margin: 0;
            font-size: 13px;
            color: #6b7280;
        }

        .contact-meta {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .contact-status {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .contact-status.new {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: white;
        }

        .contact-status.unread {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
        }

        .contact-status.read {
            background: linear-gradient(135deg, #6b7280, #4b5563);
            color: white;
        }

        .contact-status.replied {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
        }

        .contact-date {
            color: #6b7280;
            font-size: 13px;
        }

        .contact-subject {
            margin-bottom: 12px;
            font-size: 14px;
            color: #374151;
        }

        .contact-subject strong {
            color: #1f2937;
            font-weight: 700;
        }

        .contact-message {
            color: #6b7280;
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 16px;
            padding: 16px;
            background: #f9fafb;
            border-radius: 8px;
            border-left: 3px solid #D4AF37;
        }

        .contact-actions {
            display: flex;
            gap: 10px;
        }

        .action-btn {
            width: 38px;
            height: 38px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            transition: all 0.3s ease;
            background: #f3f4f6;
            color: #6b7280;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .action-btn.view {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: white;
        }

        .action-btn.view:hover {
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
        }

        .action-btn.delete {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
        }

        .action-btn.delete:hover {
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
        }

        .empty-state {
            text-align: center;
            padding: 80px 20px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .empty-icon {
            width: 100px;
            height: 100px;
            margin: 0 auto 24px;
            background: linear-gradient(135deg, #D4AF37, #B8960B);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
            color: white;
        }

        .empty-state h3 {
            margin: 0 0 12px 0;
            font-size: 24px;
            color: #1f2937;
        }

        .empty-state p {
            margin: 0;
            color: #6b7280;
            font-size: 16px;
        }
    </style>
</head>

<body>
    <?php $activePage = 'contacts';
    include __DIR__ . '/../layouts/sidebar.php'; ?>

    <main class="admin-main">
        <header class="admin-header">
            <h1><i class="fas fa-inbox" style="color: #D4AF37; margin-right: 12px;"></i>Quản lý liên hệ</h1>
            <div class="header-profile">
                <img src="https://ui-avatars.com/api/?name=Admin&background=D4AF37&color=fff" alt="Admin">
            </div>
        </header>

        <div class="admin-content">
            <div class="page-header">
                <h2>Hộp thư liên hệ (<?= $totalContacts ?>)</h2>
            </div>

            <!-- Stats -->
            <div class="stats-row">
                <div class="stat-card">
                    <div class="stat-icon blue"><i class="fas fa-inbox"></i></div>
                    <div class="stat-info">
                        <h3><?= $totalContacts ?></h3>
                        <p>Tổng tin nhắn</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon orange"><i class="fas fa-envelope-open"></i></div>
                    <div class="stat-info">
                        <h3><?= $newCount ?></h3>
                        <p>Tin nhắn mới</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon red"><i class="fas fa-envelope"></i></div>
                    <div class="stat-info">
                        <h3><?= $unreadCount ?></h3>
                        <p>Chưa đọc</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon green"><i class="fas fa-reply"></i></div>
                    <div class="stat-info">
                        <h3><?= $repliedCount ?></h3>
                        <p>Đã phản hồi</p>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="filters-bar">
                <div class="filter-group">
                    <label>Trạng thái:</label>
                    <select id="filterStatus">
                        <option value="">Tất cả</option>
                        <option value="new">Mới</option>
                        <option value="unread">Chưa đọc</option>
                        <option value="read">Đã đọc</option>
                        <option value="replied">Đã phản hồi</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label>Sắp xếp:</label>
                    <select id="sortBy">
                        <option value="newest">Mới nhất</option>
                        <option value="oldest">Cũ nhất</option>
                        <option value="name">Tên A-Z</option>
                    </select>
                </div>
                <div class="filter-search">
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchContact" placeholder="Tìm theo tên, email, nội dung...">
                </div>
            </div>

            <!-- Bulk Actions Bar -->
            <div class="bulk-actions-bar" id="bulkActionsBar" style="display: none;">
                <div class="bulk-selected">
                    <input type="checkbox" id="selectAll">
                    <span id="selectedCount">0</span> tin nhắn đã chọn
                </div>
                <div class="bulk-buttons">
                    <button class="btn-bulk" onclick="bulkMarkAsRead()">
                        <i class="fas fa-check"></i> Đánh dấu đã đọc
                    </button>
                    <button class="btn-bulk danger" onclick="bulkDelete()">
                        <i class="fas fa-trash"></i> Xóa đã chọn
                    </button>
                </div>
            </div>

            <?php if ($totalContacts === 0): ?>
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-envelope-open"></i>
                    </div>
                    <h3>Chưa có tin nhắn nào</h3>
                    <p>Tin nhắn từ khách hàng sẽ xuất hiện ở đây khi họ gửi liên hệ qua form trên website.</p>
                </div>
            <?php else: ?>
                <!-- Contacts List -->
                <div class="contacts-list">
                    <?php foreach ($contacts as $contact): ?>
                        <div class="contact-card <?= $contact['status'] ?? 'unread' ?>" data-status="<?= $contact['status'] ?? 'unread' ?>" data-date="<?= strtotime($contact['created_at']) ?>" data-name="<?= htmlspecialchars($contact['name']) ?>">
                            <div class="contact-header">
                                <div class="contact-sender">
                                    <input type="checkbox" class="contact-checkbox" value="<?= $contact['id'] ?>" onchange="updateBulkActions()">
                                    <img src="https://ui-avatars.com/api/?name=<?= urlencode($contact['name']) ?>&background=D4AF37&color=fff" alt="">
                                    <div class="contact-sender-info">
                                        <h4><?= htmlspecialchars($contact['name']) ?></h4>
                                        <p><?= htmlspecialchars($contact['email']) ?></p>
                                    </div>
                                </div>
                                <div class="contact-meta">
                                    <span class="contact-status <?= $contact['status'] ?? 'unread' ?>">
                                        <?php
                                        switch ($contact['status'] ?? 'unread') {
                                            case 'new':
                                                echo 'Mới';
                                                break;
                                            case 'unread':
                                                echo 'Chưa đọc';
                                                break;
                                            case 'read':
                                                echo 'Đã đọc';
                                                break;
                                            case 'replied':
                                                echo 'Đã phản hồi';
                                                break;
                                        }
                                        ?>
                                    </span>
                                    <span class="contact-date"><?= date('d/m/Y H:i', strtotime($contact['created_at'])) ?></span>
                                </div>
                            </div>
                            <div class="contact-subject">
                                <strong>Chủ đề:</strong> <?= htmlspecialchars($contact['subject'] ?? 'Không có tiêu đề') ?>
                            </div>
                            <div class="contact-message">
                                <?= nl2br(htmlspecialchars($contact['message'])) ?>
                            </div>
                            <div class="contact-actions">
                                <button class="action-btn view"
                                    data-id="<?= $contact['id'] ?>"
                                    data-name="<?= htmlspecialchars($contact['name']) ?>"
                                    data-email="<?= htmlspecialchars($contact['email']) ?>"
                                    data-phone="<?= htmlspecialchars($contact['phone'] ?? '') ?>"
                                    data-subject="<?= htmlspecialchars($contact['subject'] ?? 'Không có tiêu đề') ?>"
                                    data-message="<?= htmlspecialchars($contact['message']) ?>"
                                    data-status="<?= $contact['status'] ?? 'unread' ?>"
                                    data-date="<?= date('d/m/Y H:i', strtotime($contact['created_at'])) ?>"
                                    onclick="viewContactData(this)"
                                    title="Xem chi tiết">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <?php if (($contact['status'] ?? 'unread') === 'unread'): ?>
                                    <button class="action-btn" onclick="markAsRead(<?= $contact['id'] ?>)" title="Đánh dấu đã đọc">
                                        <i class="fas fa-check"></i>
                                    </button>
                                <?php endif; ?>
                                <button class="action-btn delete"
                                    data-id="<?= $contact['id'] ?>"
                                    data-name="<?= htmlspecialchars($contact['name']) ?>"
                                    onclick="confirmDeleteData(this)"
                                    title="Xóa">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <!-- Modal Xem Chi Tiết -->
    <div class="modal" id="viewModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2><i class="fas fa-envelope-open-text"></i> Chi tiết liên hệ</h2>
                <button class="modal-close" onclick="closeModal('viewModal')">&times;</button>
            </div>
            <div class="modal-body">
                <div class="contact-detail">
                    <div class="contact-detail-header">
                        <div class="contact-detail-avatar">
                            <img id="viewAvatar" src="" alt="">
                        </div>
                        <div class="contact-detail-info">
                            <h3 id="viewName"></h3>
                            <p><i class="fas fa-envelope"></i> <span id="viewEmail"></span></p>
                            <p><i class="fas fa-phone"></i> <span id="viewPhone"></span></p>
                        </div>
                        <span class="contact-status" id="viewStatus"></span>
                    </div>
                    <div class="contact-detail-subject">
                        <label>Chủ đề:</label>
                        <p id="viewSubject"></p>
                    </div>
                    <div class="contact-detail-message">
                        <label>Nội dung:</label>
                        <div id="viewMessage" class="message-content"></div>
                    </div>
                    <div class="contact-detail-date">
                        <i class="fas fa-clock"></i> Gửi lúc: <span id="viewDate"></span>
                    </div>
                </div>
            </div>
            <div class="form-actions">
                <button type="button" class="btn-secondary" onclick="closeModal('viewModal')">
                    <i class="fas fa-times"></i> Đóng
                </button>
                <button type="button" class="btn-primary" id="btnMarkRead" onclick="markAsReadFromModal()">
                    <i class="fas fa-check"></i> Đánh dấu đã đọc
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Xác Nhận Xóa -->
    <div class="modal" id="deleteModal">
        <div class="modal-content modal-small">
            <div class="modal-header">
                <h2><i class="fas fa-exclamation-triangle"></i> Xác nhận xóa</h2>
                <button class="modal-close" onclick="closeModal('deleteModal')">&times;</button>
            </div>
            <div class="modal-body">
                <div class="delete-warning">
                    <i class="fas fa-trash-alt"></i>
                    <p>Bạn có chắc chắn muốn xóa tin nhắn từ <strong id="deleteContactName"></strong>?</p>
                    <span class="warning-text">Hành động này không thể hoàn tác!</span>
                </div>
            </div>
            <div class="form-actions">
                <button type="button" class="btn-secondary" onclick="closeModal('deleteModal')">
                    <i class="fas fa-times"></i> Hủy
                </button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="contact_id" id="deleteContactId">
                    <button type="submit" class="btn-danger">
                        <i class="fas fa-trash"></i> Xóa tin nhắn
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div class="toast" id="toast"></div>

    <script>
        let currentContactId = null;
        let currentContactEmail = null;
        let currentContactStatus = null;

        // Open modal functions
        function openModal(modalId) {
            document.getElementById(modalId).classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.remove('active');
            document.body.style.overflow = '';
        }

        // View contact detail
        function viewContactData(btn) {
            const id = btn.dataset.id;
            const name = btn.dataset.name;
            const email = btn.dataset.email;
            const phone = btn.dataset.phone;
            const subject = btn.dataset.subject;
            const message = btn.dataset.message;
            const status = btn.dataset.status;
            const date = btn.dataset.date;

            viewContact(id, name, email, phone, subject, message, status, date);
        }

        function viewContact(id, name, email, phone, subject, message, status, date) {
            console.log('viewContact called', {
                id,
                name,
                email,
                phone,
                subject,
                message,
                status,
                date
            });

            currentContactId = id;
            currentContactEmail = email;
            currentContactStatus = status;

            document.getElementById('viewAvatar').src = 'https://ui-avatars.com/api/?name=' + encodeURIComponent(name) + '&background=D4AF37&color=fff&size=80';
            document.getElementById('viewName').textContent = name;
            document.getElementById('viewEmail').textContent = email;
            document.getElementById('viewPhone').textContent = phone || 'Không có';
            document.getElementById('viewSubject').textContent = subject || 'Không có tiêu đề';
            // Hiển thị message với line breaks
            document.getElementById('viewMessage').innerHTML = message.replace(/\n/g, '<br>');
            document.getElementById('viewDate').textContent = date;

            const statusEl = document.getElementById('viewStatus');
            statusEl.className = 'contact-status ' + status;
            switch (status) {
                case 'new':
                    statusEl.textContent = 'Mới';
                    break;
                case 'unread':
                    statusEl.textContent = 'Chưa đọc';
                    break;
                case 'read':
                    statusEl.textContent = 'Đã đọc';
                    break;
                case 'replied':
                    statusEl.textContent = 'Đã phản hồi';
                    break;
            }

            // Show/hide buttons based on status
            document.getElementById('btnMarkRead').style.display = (status === 'unread' || status === 'new') ? 'inline-flex' : 'none';

            openModal('viewModal');
        }

        function markAsRead(id) {
            fetch('<?= BASE_URL ?>/admin/contacts/update-status/' + id + '/read', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast('Đã đánh dấu đã đọc');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        showToast(data.message || 'Có lỗi xảy ra', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Có lỗi xảy ra', 'error');
                });
        }

        function markAsReadFromModal() {
            if (currentContactId) {
                markAsRead(currentContactId);
            }
        }

        function markAsReplied(id) {
            fetch('<?= BASE_URL ?>/admin/contacts/update-status/' + id + '/replied', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast('Đã đánh dấu đã phản hồi');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        showToast(data.message || 'Có lỗi xảy ra', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Có lỗi xảy ra', 'error');
                });
        }

        function markAsRepliedFromModal() {
            if (currentContactId) {
                markAsReplied(currentContactId);
            }
        }

        function replyContactData(btn) {
            const id = btn.dataset.id;
            const email = btn.dataset.email;
            console.log('replyContactData called', {
                id,
                email
            });
            replyContact(id, email);
        }

        function replyContact(id, email) {
            console.log('Opening mailto:', email);
            window.location.href = 'mailto:' + email + '?subject=Re: Phản hồi từ AutoCar';
        }

        function replyFromModal() {
            console.log('replyFromModal called', {
                currentContactId,
                currentContactEmail
            });
            if (currentContactEmail) {
                replyContact(currentContactId, currentContactEmail);
            }
        }

        function confirmDeleteData(btn) {
            const id = btn.dataset.id;
            const name = btn.dataset.name;
            console.log('confirmDeleteData called', {
                id,
                name
            });
            confirmDelete(id, name);
        }

        function confirmDelete(id, name) {
            console.log('confirmDelete called', {
                id,
                name
            });
            document.getElementById('deleteContactId').value = id;
            document.getElementById('deleteContactName').textContent = name;
            openModal('deleteModal');
        }

        // Handle delete form submission
        document.getElementById('deleteForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const contactId = document.getElementById('deleteContactId').value;

            console.log('Deleting contact ID:', contactId);
            console.log('Delete URL:', '<?= BASE_URL ?>/admin/contacts/delete/' + contactId);

            fetch('<?= BASE_URL ?>/admin/contacts/delete/' + contactId, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    }
                })
                .then(response => {
                    console.log('Delete response status:', response.status);
                    return response.json();
                })
                .then(data => {
                    console.log('Delete response data:', data);
                    if (data.success) {
                        showToast('Xóa tin nhắn thành công');
                        closeModal('deleteModal');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        showToast(data.message || 'Có lỗi xảy ra', 'error');
                    }
                })
                .catch(error => {
                    console.error('Delete error:', error);
                    showToast('Có lỗi xảy ra', 'error');
                });
        });

        // Close modal when clicking outside
        document.querySelectorAll('.modal').forEach(modal => {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeModal(this.id);
                }
            });
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.querySelectorAll('.modal.active').forEach(modal => {
                    closeModal(modal.id);
                });
            }
        });

        // Bulk Actions
        function updateBulkActions() {
            const checkboxes = document.querySelectorAll('.contact-checkbox:checked');
            const bulkBar = document.getElementById('bulkActionsBar');
            const selectedCount = document.getElementById('selectedCount');

            selectedCount.textContent = checkboxes.length;
            bulkBar.style.display = checkboxes.length > 0 ? 'flex' : 'none';

            // Update select all checkbox
            const selectAll = document.getElementById('selectAll');
            const allCheckboxes = document.querySelectorAll('.contact-checkbox');
            selectAll.checked = checkboxes.length === allCheckboxes.length && allCheckboxes.length > 0;
        }

        document.getElementById('selectAll')?.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.contact-checkbox');
            checkboxes.forEach(cb => cb.checked = this.checked);
            updateBulkActions();
        });

        function bulkMarkAsRead() {
            const checkboxes = document.querySelectorAll('.contact-checkbox:checked');
            const ids = Array.from(checkboxes).map(cb => cb.value);

            if (ids.length === 0) return;

            if (!confirm(`Đánh dấu đã đọc ${ids.length} tin nhắn?`)) return;

            Promise.all(ids.map(id =>
                    fetch('<?= BASE_URL ?>/admin/contacts/update-status/' + id + '/read', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        }
                    })
                ))
                .then(() => {
                    showToast('Đã cập nhật ' + ids.length + ' tin nhắn');
                    setTimeout(() => location.reload(), 1000);
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Có lỗi xảy ra', 'error');
                });
        }

        function bulkDelete() {
            const checkboxes = document.querySelectorAll('.contact-checkbox:checked');
            const ids = Array.from(checkboxes).map(cb => cb.value);

            if (ids.length === 0) return;

            if (!confirm(`Xóa ${ids.length} tin nhắn? Hành động này không thể hoàn tác!`)) return;

            Promise.all(ids.map(id =>
                    fetch('<?= BASE_URL ?>/admin/contacts/delete/' + id, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        }
                    })
                ))
                .then(() => {
                    showToast('Đã xóa ' + ids.length + ' tin nhắn');
                    setTimeout(() => location.reload(), 1000);
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Có lỗi xảy ra', 'error');
                });
        }

        // Sort functionality
        document.getElementById('sortBy').addEventListener('change', function() {
            const sortValue = this.value;
            const container = document.querySelector('.contacts-list');
            const cards = Array.from(container.querySelectorAll('.contact-card'));

            cards.sort((a, b) => {
                if (sortValue === 'newest') {
                    return parseInt(b.dataset.date) - parseInt(a.dataset.date);
                } else if (sortValue === 'oldest') {
                    return parseInt(a.dataset.date) - parseInt(b.dataset.date);
                } else if (sortValue === 'name') {
                    return a.dataset.name.localeCompare(b.dataset.name);
                }
                return 0;
            });

            cards.forEach(card => container.appendChild(card));
        });

        // Filters
        document.getElementById('filterStatus').addEventListener('change', filterContacts);
        document.getElementById('searchContact').addEventListener('input', filterContacts);

        function filterContacts() {
            const status = document.getElementById('filterStatus').value;
            const search = document.getElementById('searchContact').value.toLowerCase();

            const cards = document.querySelectorAll('.contact-card');
            cards.forEach(card => {
                const matchStatus = !status || card.dataset.status === status;
                const matchSearch = !search || card.textContent.toLowerCase().includes(search);
                card.style.display = matchStatus && matchSearch ? 'block' : 'none';
            });
        }

        // Toast notification
        function showToast(message, type = 'success') {
            const toast = document.getElementById('toast');
            toast.textContent = message;
            toast.className = 'toast ' + type + ' show';
            setTimeout(() => toast.classList.remove('show'), 3000);
        }

        <?php if (isset($_GET['success'])): ?>
            showToast('<?= htmlspecialchars($_GET['success']) ?>');
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            showToast('<?= htmlspecialchars($_GET['error']) ?>', 'error');
        <?php endif; ?>
    </script>
</body>

</html>