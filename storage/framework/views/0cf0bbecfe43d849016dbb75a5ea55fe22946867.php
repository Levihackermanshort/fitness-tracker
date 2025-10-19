

<?php $__env->startSection('title', 'Body Measurements - Fitness Tracker'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div>
        <h1 class="page-title">📏 Body Measurements</h1>
        <p class="page-subtitle">Track your body measurements and progress</p>
    </div>
    <div>
        <a href="<?php echo e(route('body-measurements.create')); ?>" class="btn-primary">➕ Record Measurement</a>
        <a href="<?php echo e(route('body-measurements.trends')); ?>" class="btn-secondary">📈 View Trends</a>
    </div>
</div>

<!-- Search and Filter Bar -->
<div class="search-filter-bar">
    <form method="GET" action="<?php echo e(route('body-measurements.index')); ?>" class="flex items-center gap-4">
        <select name="user_id" class="filter-select">
            <option value="">All Users</option>
            <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($user->id); ?>" <?php echo e(request('user_id') == $user->id ? 'selected' : ''); ?>>
                    <?php echo e($user->first_name); ?> <?php echo e($user->last_name); ?>

                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>

        <input type="date" name="start_date" value="<?php echo e(request('start_date')); ?>" class="form-input" placeholder="Start Date">
        <input type="date" name="end_date" value="<?php echo e(request('end_date')); ?>" class="form-input" placeholder="End Date">

        <button type="submit" class="btn-primary">🔍 Filter</button>
        <a href="<?php echo e(route('body-measurements.index')); ?>" class="btn-secondary">🔄 Clear</a>
    </form>
</div>

<!-- Body Measurements Table -->
<div class="table-container">
    <table class="table">
        <thead>
            <tr>
                <th>Date</th>
                <th>User</th>
                <th>Weight</th>
                <th>Body Fat %</th>
                <th>Muscle Mass</th>
                <th>BMI</th>
                <th>Chest</th>
                <th>Waist</th>
                <th>Hips</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $bodyMeasurements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $measurement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($measurement->measurement_date->format('M d, Y')); ?></td>
                    <td><?php echo e($measurement->user->first_name); ?> <?php echo e($measurement->user->last_name); ?></td>
                    <td>
                        <?php if($measurement->weight_kg): ?>
                            <span class="weight-text"><?php echo e(number_format($measurement->weight_kg, 1)); ?> kg</span>
                        <?php else: ?>
                            <span class="text-muted">N/A</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if($measurement->body_fat_percentage): ?>
                            <span class="bodyfat-text"><?php echo e(number_format($measurement->body_fat_percentage, 1)); ?>%</span>
                        <?php else: ?>
                            <span class="text-muted">N/A</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if($measurement->muscle_mass_kg): ?>
                            <?php echo e(number_format($measurement->muscle_mass_kg, 1)); ?> kg
                        <?php else: ?>
                            <span class="text-muted">N/A</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if($measurement->bmi): ?>
                            <span class="bmi-text"><?php echo e(number_format($measurement->bmi, 1)); ?></span>
                            <?php if($measurement->bmi < 18.5): ?>
                                <br><small class="text-info">Underweight</small>
                            <?php elseif($measurement->bmi < 25): ?>
                                <br><small class="text-success">Normal</small>
                            <?php elseif($measurement->bmi < 30): ?>
                                <br><small class="text-warning">Overweight</small>
                            <?php else: ?>
                                <br><small class="text-danger">Obese</small>
                            <?php endif; ?>
                        <?php else: ?>
                            <span class="text-muted">N/A</span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo e($measurement->chest_cm ? number_format($measurement->chest_cm, 1) . ' cm' : 'N/A'); ?></td>
                    <td><?php echo e($measurement->waist_cm ? number_format($measurement->waist_cm, 1) . ' cm' : 'N/A'); ?></td>
                    <td><?php echo e($measurement->hips_cm ? number_format($measurement->hips_cm, 1) . ' cm' : 'N/A'); ?></td>
                    <td>
                        <div class="flex gap-4">
                            <a href="<?php echo e(route('body-measurements.show', $measurement)); ?>" class="btn-secondary">👁️ View</a>
                            <a href="<?php echo e(route('body-measurements.edit', $measurement)); ?>" class="btn-primary">✏️ Edit</a>
                        </div>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="10" class="text-center text-muted">
                        No body measurements found. <a href="<?php echo e(route('body-measurements.create')); ?>">Record your first measurement</a>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Pagination -->
<?php if($bodyMeasurements->hasPages()): ?>
    <div class="pagination">
        <?php echo e($bodyMeasurements->links()); ?>

    </div>
<?php endif; ?>

<style>
.weight-text {
    font-weight: bold;
    color: #2d3748;
}

.bodyfat-text {
    font-weight: bold;
    color: #e53e3e;
}

.bmi-text {
    font-weight: bold;
    color: #667eea;
}

.text-info { color: #3182ce; }
.text-success { color: #38a169; }
.text-warning { color: #d69e2e; }
.text-danger { color: #e53e3e; }
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Fitness-tracker\resources\views/body-measurements/index.blade.php ENDPATH**/ ?>