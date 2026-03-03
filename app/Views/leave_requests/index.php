<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('content') ?>
<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Leave Requests</h4>
            <p class="text-muted mb-0">Manage and track all leave requests</p>
        </div>
        <a href="<?= base_url('/leave_requests/create') ?>" class="btn btn-primary">
            <i class="bi bi-plus-lg me-2"></i>
            New Leave Request
        </a>
    </div>
    
    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="get" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All Statuses</option>
                        <option value="draft" <?= ($filters['status'] ?? '') === 'draft' ? 'selected' : '' ?>>Draft</option>
                        <option value="submitted" <?= ($filters['status'] ?? '') === 'submitted' ? 'selected' : '' ?>>Submitted</option>
                        <option value="pending_l1" <?= ($filters['status'] ?? '') === 'pending_l1' ? 'selected' : '' ?>>Pending L1</option>
                        <option value="pending_l2" <?= ($filters['status'] ?? '') === 'pending_l2' ? 'selected' : '' ?>>Pending L2</option>
                        <option value="pending_l3" <?= ($filters['status'] ?? '') === 'pending_l3' ? 'selected' : '' ?>>Pending L3</option>
                        <option value="approved" <?= ($filters['status'] ?? '') === 'approved' ? 'selected' : '' ?>>Approved</option>
                        <option value="rejected" <?= ($filters['status'] ?? '') === 'rejected' ? 'selected' : '' ?>>Rejected</option>
                        <option value="cancelled" <?= ($filters['status'] ?? '') === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Leave Type</label>
                    <select name="leave_type_id" class="form-select">
                        <option value="">All Types</option>
                        <?php foreach ($leave_types as $type): ?>
                            <option value="<?= $type['id'] ?>" <?= ($filters['leave_type_id'] ?? '') == $type['id'] ? 'selected' : '' ?>>
                                <?= $type['name'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-outline-primary w-100">
                        <i class="bi bi-filter me-2"></i>
                        Filter
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Leave Requests Table -->
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Employee</th>
                            <th>Leave Type</th>
                            <th>Period</th>
                            <th>Days</th>
                            <th>Status</th>
                            <th>Applied Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($leave_requests)): ?>
                            <?php foreach ($leave_requests as $request): ?>
                                <tr>
                                    <td>#<?= $request['id'] ?></td>
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
                                            <?php if ($request['status'] === 'draft'): ?>
                                                <a href="<?= base_url('/leave_requests/submit/' . $request['id']) ?>" class="btn btn-sm btn-outline-success" onclick="return confirm('Submit this request?')">
                                                    <i class="bi bi-send"></i>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox d-block mb-2" style="font-size: 40px;"></i>
                                    <p class="mb-0">No leave requests found</p>
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
