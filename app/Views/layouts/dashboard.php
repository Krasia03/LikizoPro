<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'LIKIZOPRO' ?></title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        :root {
            --bg-color: #FBFBFF;
            --accent-blue: #3581B8;
            --primary-navy: #111D4A;
            --text-muted: #6B7280;
            --border-color: #E5E7EB;
            --success-color: #10B981;
            --warning-color: #F59E0B;
            --danger-color: #EF4444;
            --info-color: #3B82F6;
            --sidebar-width: 260px;
        }
        
        body {
            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
            background-color: var(--bg-color);
            color: var(--primary-navy);
        }
        
        .sidebar {
            width: var(--sidebar-width);
            min-height: 100vh;
            background: linear-gradient(180deg, var(--primary-navy) 0%, #1a2a5c 100%);
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1000;
        }
        
        .sidebar-brand {
            padding: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .sidebar-brand h2 {
            color: white;
            font-weight: 800;
            font-size: 22px;
            margin: 0;
        }
        
        .sidebar-brand span {
            color: var(--accent-blue);
        }
        
        .sidebar-menu {
            padding: 16px 0;
        }
        
        .sidebar-menu .nav-link {
            color: rgba(255, 255, 255, 0.7);
            padding: 12px 20px;
            border-radius: 8px;
            margin: 2px 12px;
            transition: all 0.2s;
            font-size: 14px;
        }
        
        .sidebar-menu .nav-link:hover,
        .sidebar-menu .nav-link.active {
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }
        
        .sidebar-menu .nav-link i {
            margin-right: 10px;
            width: 20px;
        }
        
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
        }
        
        .top-bar {
            background: white;
            padding: 16px 30px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .page-title {
            font-size: 20px;
            font-weight: 700;
            color: var(--primary-navy);
            margin: 0;
        }
        
        .user-menu {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--accent-blue);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }
        
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            transition: all 0.2s;
        }
        
        .card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }
        
        .btn-primary {
            background: var(--accent-blue);
            border-color: var(--accent-blue);
        }
        
        .btn-primary:hover {
            background: #2d6a9e;
            border-color: #2d6a9e;
        }
        
        .badge-status {
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 500;
            font-size: 12px;
        }
        
        .badge-draft { background: #E5E7EB; color: #374151; }
        .badge-submitted { background: #DBEAFE; color: #1D4ED8; }
        .badge-pending_l1, .badge-pending_l2, .badge-pending_l3 { background: #FEF3C7; color: #D97706; }
        .badge-approved { background: #D1FAE5; color: #059669; }
        .badge-rejected { background: #FEE2E2; color: #DC2626; }
        .badge-cancelled { background: #F3F4F6; color: #6B7280; }
        
        .table th {
            font-weight: 600;
            color: var(--text-muted);
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid var(--border-color);
        }
        
        .table td {
            vertical-align: middle;
            padding: 16px 12px;
        }
        
        .stat-card {
            border-radius: 12px;
            padding: 20px;
            background: white;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }
        
        .stat-card .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }
        
        .stat-card .stat-value {
            font-size: 28px;
            font-weight: 700;
            color: var(--primary-navy);
        }
        
        .stat-card .stat-label {
            font-size: 13px;
            color: var(--text-muted);
        }
    </style>
    
    <?= $this->renderSection('styles') ?>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-brand">
            <h2>LIKIZO<span>PRO</span></h2>
        </div>
        
        <nav class="sidebar-menu">
            <?php foreach ($menu as $item): ?>
                <?php if (!isset($item['children'])): ?>
                    <a href="<?= base_url($item['route']) ?>" class="nav-link <?= (uri_string() === $item['route'] || uri_string() === $item['route'] . '/') ? 'active' : '' ?>">
                        <i class="<?= $item['icon'] ?>"></i>
                        <?= $item['title'] ?>
                    </a>
                <?php else: ?>
                    <div class="dropdown">
                        <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#">
                            <i class="<?= $item['icon'] ?>"></i>
                            <?= $item['title'] ?>
                        </a>
                        <ul class="dropdown-menu">
                            <?php foreach ($item['children'] as $child): ?>
                                <li><a class="dropdown-item" href="<?= base_url($child['route']) ?>"><?= $child['title'] ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
            
            <a href="<?= base_url('/auth/logout') ?>" class="nav-link" style="margin-top: 40px;">
                <i class="bi bi-box-arrow-left"></i>
                Logout
            </a>
        </nav>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Bar -->
        <div class="top-bar">
            <h4 class="page-title"><?= str_replace(' - LIKIZOPRO', '', $title ?? 'Dashboard') ?></h4>
            
            <div class="user-menu">
                <div class="text-end">
                    <div class="fw-semibold"><?= $user['first_name'] . ' ' . $user['last_name'] ?></div>
                    <small class="text-muted"><?= session()->get('role_name') ?? 'User' ?></small>
                </div>
                <div class="user-avatar">
                    <?= strtoupper(substr($user['first_name'], 0, 1) . substr($user['last_name'], 0, 1)) ?>
                </div>
            </div>
        </div>
        
        <!-- Content -->
        <div class="p-4">
            <!-- Flash Messages -->
            <?php if (session()->get('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>
                    <?= session()->get('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <?php if (session()->get('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle me-2"></i>
                    <?= session()->get('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <?= $this->renderSection('content') ?>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <?= $this->renderSection('scripts') ?>
</body>
</html>
