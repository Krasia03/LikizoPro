<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('styles') ?>
<style>
    .welcome-card {
        background: linear-gradient(135deg, var(--primary-navy) 0%, #2a3f7c 100%);
        color: white;
        border-radius: 16px;
        padding: 32px;
    }
    
    .welcome-card h2 {
        font-weight: 700;
        margin-bottom: 8px;
    }
    
    .quick-action-btn {
        border-radius: 12px;
        padding: 16px;
        text-align: center;
        background: white;
        border: 1px solid var(--border-color);
        transition: all 0.2s;
        text-decoration: none;
        color: var(--primary-navy);
    }
    
    .quick-action-btn:hover {
        border-color: var(--accent-blue);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }
    
    .quick-action-btn i {
        font-size: 24px;
        color: var(--accent-blue);
        margin-bottom: 8px;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid p-0">
    <!-- Welcome Card -->
    <div class="welcome-card mb-4">
        <h2>Welcome back, <?= $user['first_name'] ?>!</h2>
        <p class="mb-0 opacity-75">Here's what's happening with your leave management today.</p>
    </div>
    
    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-value"><?= $stats['total_employees'] ?? 0 ?></div>
                        <div class="stat-label">Total Employees</div>
                    </div>
                    <div class="stat-icon" style="background: #DBEAFE; color: #1D4ED8;">
                        <i class="bi bi-people"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-value"><?= $stats['pending_requests'] ?? 0 ?></div>
                        <div class="stat-label">Pending Requests</div>
                    </div>
                    <div class="stat-icon" style="background: #FEF3C7; color: #D97706;">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <?php if (isset($stats['pending_approvals'])): ?>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-value"><?= $stats['pending_approvals'] ?></div>
                        <div class="stat-label">Pending Approvals</div>
                    </div>
                    <div class="stat-icon" style="background: #FEE2E2; color: #DC2626;">
                        <i class="bi bi-clipboard-check"></i>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
        
        <div class="col-md-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-value"><?= $stats['approved_today'] ?? 0 ?></div>
                        <div class="stat-label">Approved (All Time)</div>
                    </div>
                    <div class="stat-icon" style="background: #D1FAE5; color: #059669;">
                        <i class="bi bi-check-circle"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <h5 class="mb-3">Quick Actions</h5>
        </div>
        <div class="col-md-2">
            <a href="<?= base_url('/leave_requests/create') ?>" class="quick-action-btn d-block">
                <i class="bi bi-plus-circle d-block"></i>
                Apply Leave
            </a>
        </div>
        <div class="col-md-2">
            <a href="<?= base_url('/leave_requests/my') ?>" class="quick-action-btn d-block">
                <i class="bi bi-calendar-check d-block"></i>
                My Requests
            </a>
        </div>
        <div class="col-md-2">
            <a href="<?= base_url('/approvals') ?>" class="quick-action-btn d-block">
                <i class="bi bi-check2-circle d-block"></i>
                Approvals
            </a>
        </div>
        <div class="col-md-2">
            <a href="<?= base_url('/employees') ?>" class="quick-action-btn d-block">
                <i class="bi bi-people d-block"></i>
                Employees
            </a>
        </div>
        <div class="col-md-2">
            <a href="<?= base_url('/reports') ?>" class="quick-action-btn d-block">
                <i class="bi bi-bar-chart d-block"></i>
                Reports
            </a>
        </div>
    </div>
    
    <!-- Recent Requests -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center py-3">
                    <h5 class="mb-0">Recent Leave Requests</h5>
                    <a href="<?= base_url('/leave_requests') ?>" class="btn btn-sm btn-primary">View All</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Employee</th>
                                    <th>Leave Type</th>
                                    <th>Dates</th>
                                    <th>Days</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($recent_requests)): ?>
                                    <?php foreach (array_slice($recent_requests, 0, 5) as $request): ?>
                                        <tr>
                                            <td>
                                                <div class="fw-semibold"><?= $request['first_name'] . ' ' . $request['last_name'] ?></div>
                                                <small class="text-muted"><?= $request['employee_number'] ?></small>
                                            </td>
                                            <td>
                                                <span class="badge" style="background: <?= $request['leave_type_color'] ?? '#3581B8' ?>; color: white;">
                                                    <?= $request['leave_type_name'] ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?= date('M d, Y', strtotime($request['start_date'])) ?> - 
                                                <?= date('M d, Y', strtotime($request['end_date'])) ?>
                                            </td>
                                            <td><?= $request['total_days'] ?></td>
                                            <td>
                                                <span class="badge-status badge-<?= $request['status'] ?>">
                                                    <?= ucfirst(str_replace('_', ' ', $request['status'])) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <a href="<?= base_url('/leave_requests/view/' . $request['id']) ?>" class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">
                                            <i class="bi bi-inbox d-block mb-2" style="font-size: 32px;"></i>
                                            No recent leave requests
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
