<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('content') ?>
<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Apply for Leave</h4>
            <p class="text-muted mb-0">Submit a new leave request</p>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <?= form_open('/leave_requests/store', ['enctype' => 'multipart/form-data']) ?>
                    <?= csrf_field() ?>
                    
                    <div class="mb-3">
                        <label class="form-label">Leave Type <span class="text-danger">*</span></label>
                        <select name="leave_type_id" class="form-select" required>
                            <option value="">Select Leave Type</option>
                            <?php foreach ($leave_types as $type): ?>
                                <option value="<?= $type['id'] ?>"><?= $type['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Start Date <span class="text-danger">*</span></label>
                            <input type="date" name="start_date" class="form-control" required min="<?= date('Y-m-d') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Start Half</label>
                            <select name="start_half" class="form-select">
                                <option value="full">Full Day</option>
                                <option value="morning">Morning Only</option>
                                <option value="afternoon">Afternoon Only</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">End Date <span class="text-danger">*</span></label>
                            <input type="date" name="end_date" class="form-control" required min="<?= date('Y-m-d') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">End Half</label>
                            <select name="end_half" class="form-select">
                                <option value="full">Full Day</option>
                                <option value="morning">Morning Only</option>
                                <option value="afternoon">Afternoon Only</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Reason <span class="text-danger">*</span></label>
                        <textarea name="reason" class="form-control" rows="4" placeholder="Provide a reason for your leave request..." required></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Emergency Contact</label>
                        <input type="text" name="emergency_contact" class="form-control" placeholder="Phone number for emergency">
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label">Attachment (if required)</label>
                        <input type="file" name="attachment" class="form-control">
                        <small class="text-muted">Upload supporting documents if required by leave type</small>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-2"></i>
                            Submit Request
                        </button>
                        <a href="<?= base_url('/leave_requests/my') ?>" class="btn btn-outline-secondary">
                            Cancel
                        </a>
                    </div>
                    
                    <?= form_close() ?>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Leave Guidelines</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-3">
                            <i class="bi bi-info-circle text-primary me-2"></i>
                            Check your leave balance before applying
                        </li>
                        <li class="mb-3">
                            <i class="bi bi-info-circle text-primary me-2"></i>
                            Submit requests at least 14 days in advance
                        </li>
                        <li class="mb-3">
                            <i class="bi bi-info-circle text-primary me-2"></i>
                            Attach supporting documents if required
                        </li>
                        <li class="mb-3">
                            <i class="bi bi-info-circle text-primary me-2"></i>
                            You can cancel draft requests anytime
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
