<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('content') ?>
<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">My Leave Requests</h4>
            <p class="text-muted mb-0">View and manage your leave requests</p>
        </div>
        <a href="<?= base_url('/leave_requests/create') ?>" class="btn btn-primary">
            <i class="bi bi-plus-lg me-2"></i>
            New Request
        </a>
    </div>
    
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Leave Type</th>
                            <th>Period</th>
                            <th>Days</th>
                            <th>Status</th>
                            <th>Applied</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($leave_requests)): ?>
                            <?php foreach ($leave_requests as $request): ?>
                                <tr>
                                    <td>
                                        <span class="badge" style="background: <?= $request['leave_type_color'] ?? '#3581B8' ?>; color: white;">
                                            <?= $request['leave_type_name'] ?? 'Leave' ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?= date('M d', strtotime($request['start_date'])) ?> - 
                                        <?= date('M d, Y', strtotime($request['end_date'])) ?>
                                    </td>
                                    <td><?= $request['total_days'] ?></td>
                                    <td>
                                        <span class="badge-status badge-<?= $request['status'] ?>">
                                            <?= ucfirst(str_replace('_', ' ', $request['status'])) ?>
                                        </span>
                                    </td>
                                    <td><?= date('M d, Y', strtotime($request['created_at'])) ?></td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="<?= base_url('/leave_requests/view/' . $request['id']) ?>" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <?php if (in_array($request['status'], ['draft', 'returned'])): ?>
                                                <a href="<?= base_url('/leave_requests/submit/' . $request['id']) ?>" class="btn btn-sm btn-outline-success" onclick="return confirm('Submit this request?')">
                                                    <i class="bi bi-send"></i>
                                                </a>
                                            <?php endif; ?>
                                            <?php if (in_array($request['status'], ['draft', 'submitted', 'pending_l1', 'pending_l2', 'pending_l3'])): ?>
                                                <a href="<?= base_url('/leave_requests/cancel/' . $request['id']) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Cancel this request?')">
                                                    <i class="bi bi-x-lg"></i>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox d-block mb-2" style="font-size: 40px;"></i>
                                    <p class="mb-2">No leave requests yet</p>
                                    <a href="<?= base_url('/leave_requests/create') ?>" class="btn btn-primary btn-sm">Apply for Leave</a>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
