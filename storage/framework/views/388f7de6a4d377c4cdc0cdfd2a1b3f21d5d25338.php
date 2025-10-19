

<?php $__env->startSection('title', 'Goals - Fitness Tracker'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div>
        <h1 class="page-title">🎯 Goals</h1>
        <p class="page-subtitle">Track your fitness goals and progress</p>
    </div>
    <div>
        <a href="<?php echo e(route('goals.create')); ?>" class="btn-primary">➕ Set Goal</a>
    </div>
</div>

<!-- Search and Filter Bar -->
<div class="search-filter-bar">
    <form method="GET" action="<?php echo e(route('goals.index')); ?>" class="flex items-center gap-4">
        <select name="user_id" class="filter-select">
            <option value="">All Users</option>
            <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($user->id); ?>" <?php echo e(request('user_id') == $user->id ? 'selected' : ''); ?>>
                    <?php echo e($user->first_name); ?> <?php echo e($user->last_name); ?>

                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>

        <select name="goal_type" class="filter-select">
            <option value="">All Types</option>
            <option value="weight" <?php echo e(request('goal_type') == 'weight' ? 'selected' : ''); ?>>Weight</option>
            <option value="strength" <?php echo e(request('goal_type') == 'strength' ? 'selected' : ''); ?>>Strength</option>
            <option value="endurance" <?php echo e(request('goal_type') == 'endurance' ? 'selected' : ''); ?>>Endurance</option>
            <option value="flexibility" <?php echo e(request('goal_type') == 'flexibility' ? 'selected' : ''); ?>>Flexibility</option>
            <option value="body_fat" <?php echo e(request('goal_type') == 'body_fat' ? 'selected' : ''); ?>>Body Fat</option>
            <option value="muscle_mass" <?php echo e(request('goal_type') == 'muscle_mass' ? 'selected' : ''); ?>>Muscle Mass</option>
            <option value="distance" <?php echo e(request('goal_type') == 'distance' ? 'selected' : ''); ?>>Distance</option>
            <option value="time" <?php echo e(request('goal_type') == 'time' ? 'selected' : ''); ?>>Time</option>
            <option value="frequency" <?php echo e(request('goal_type') == 'frequency' ? 'selected' : ''); ?>>Frequency</option>
        </select>

        <select name="status" class="filter-select">
            <option value="">All Status</option>
            <option value="active" <?php echo e(request('status') == 'active' ? 'selected' : ''); ?>>Active</option>
            <option value="achieved" <?php echo e(request('status') == 'achieved' ? 'selected' : ''); ?>>Achieved</option>
            <option value="overdue" <?php echo e(request('status') == 'overdue' ? 'selected' : ''); ?>>Overdue</option>
        </select>

        <button type="submit" class="btn-primary">🔍 Filter</button>
        <a href="<?php echo e(route('goals.index')); ?>" class="btn-secondary">🔄 Clear</a>
    </form>
</div>

<!-- Goals Table -->
<div class="table-container">
    <table class="table">
        <thead>
            <tr>
                <th>User</th>
                <th>Goal</th>
                <th>Type</th>
                <th>Progress</th>
                <th>Target Date</th>
                <th>Priority</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $goals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $goal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($goal->user->first_name); ?> <?php echo e($goal->user->last_name); ?></td>
                    <td><strong><?php echo e($goal->title); ?></strong></td>
                    <td>
                        <span class="badge"><?php echo e(ucfirst(str_replace('_', ' ', $goal->goal_type))); ?></span>
                    </td>
                    <td>
                        <?php if($goal->target_value && $goal->current_value): ?>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: <?php echo e(min(100, ($goal->current_value / $goal->target_value) * 100)); ?>%"></div>
                                <span class="progress-text"><?php echo e(number_format(($goal->current_value / $goal->target_value) * 100, 1)); ?>%</span>
                            </div>
                            <small class="text-muted"><?php echo e($goal->current_value); ?> / <?php echo e($goal->target_value); ?> <?php echo e($goal->unit); ?></small>
                        <?php else: ?>
                            <span class="text-muted">No progress data</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if($goal->target_date): ?>
                            <?php echo e($goal->target_date->format('M d, Y')); ?>

                            <?php if($goal->target_date->isPast() && !$goal->is_achieved): ?>
                                <br><small class="text-danger">⚠️ Overdue</small>
                            <?php endif; ?>
                        <?php else: ?>
                            No target date
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if($goal->priority): ?>
                            <?php for($i = 1; $i <= 5; $i++): ?>
                                <?php if($i <= $goal->priority): ?>
                                    ⭐
                                <?php else: ?>
                                    ☆
                                <?php endif; ?>
                            <?php endfor; ?>
                        <?php else: ?>
                            N/A
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if($goal->is_achieved): ?>
                            <span class="text-success">✅ Achieved</span>
                            <?php if($goal->achieved_date): ?>
                                <br><small class="text-muted"><?php echo e($goal->achieved_date->format('M d, Y')); ?></small>
                            <?php endif; ?>
                        <?php elseif($goal->is_active): ?>
                            <span class="text-primary">🔄 Active</span>
                        <?php else: ?>
                            <span class="text-muted">⏸️ Inactive</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="flex gap-4">
                            <a href="<?php echo e(route('goals.show', $goal)); ?>" class="btn-secondary">👁️ View</a>
                            <a href="<?php echo e(route('goals.edit', $goal)); ?>" class="btn-primary">✏️ Edit</a>
                            <?php if(!$goal->is_achieved && $goal->is_active): ?>
                                <a href="<?php echo e(route('goals.update-progress', $goal)); ?>" class="btn-success">📈 Update Progress</a>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="8" class="text-center text-muted">
                        No goals found. <a href="<?php echo e(route('goals.create')); ?>">Set your first goal</a>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Pagination -->
<?php if($goals->hasPages()): ?>
    <div class="pagination">
        <?php echo e($goals->links()); ?>

    </div>
<?php endif; ?>

<style>
.progress-bar {
    position: relative;
    width: 100%;
    height: 20px;
    background-color: #e2e8f0;
    border-radius: 10px;
    overflow: hidden;
    margin-bottom: 5px;
}

.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, #667eea, #764ba2);
    transition: width 0.3s ease;
}

.progress-text {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: 12px;
    font-weight: bold;
    color: #2d3748;
}

.badge {
    display: inline-block;
    padding: 4px 8px;
    background-color: #667eea;
    color: white;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 500;
}
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Fitness-tracker\resources\views/goals/index.blade.php ENDPATH**/ ?>