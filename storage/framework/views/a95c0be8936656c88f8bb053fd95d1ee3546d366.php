

<?php $__env->startSection('title', 'Workout Plans - Fitness Tracker'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div>
        <h1 class="page-title">📋 Workout Plans</h1>
        <p class="page-subtitle">Create and manage workout plans</p>
    </div>
    <div>
        <a href="<?php echo e(route('workout-plans.create')); ?>" class="btn-primary">➕ Create Plan</a>
    </div>
</div>

<!-- Search and Filter Bar -->
<div class="search-filter-bar">
    <form method="GET" action="<?php echo e(route('workout-plans.index')); ?>" class="flex items-center gap-4">
        <select name="user_id" class="filter-select">
            <option value="">All Users</option>
            <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($user->id); ?>" <?php echo e(request('user_id') == $user->id ? 'selected' : ''); ?>>
                    <?php echo e($user->first_name); ?> <?php echo e($user->last_name); ?>

                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>

        <select name="plan_type" class="filter-select">
            <option value="">All Types</option>
            <option value="strength" <?php echo e(request('plan_type') == 'strength' ? 'selected' : ''); ?>>Strength</option>
            <option value="cardio" <?php echo e(request('plan_type') == 'cardio' ? 'selected' : ''); ?>>Cardio</option>
            <option value="hybrid" <?php echo e(request('plan_type') == 'hybrid' ? 'selected' : ''); ?>>Hybrid</option>
            <option value="bodyweight" <?php echo e(request('plan_type') == 'bodyweight' ? 'selected' : ''); ?>>Bodyweight</option>
            <option value="sport_specific" <?php echo e(request('plan_type') == 'sport_specific' ? 'selected' : ''); ?>>Sport Specific</option>
            <option value="rehabilitation" <?php echo e(request('plan_type') == 'rehabilitation' ? 'selected' : ''); ?>>Rehabilitation</option>
        </select>

        <select name="difficulty_level" class="filter-select">
            <option value="">All Difficulty Levels</option>
            <option value="1" <?php echo e(request('difficulty_level') == '1' ? 'selected' : ''); ?>>⭐ Beginner</option>
            <option value="2" <?php echo e(request('difficulty_level') == '2' ? 'selected' : ''); ?>>⭐⭐ Easy</option>
            <option value="3" <?php echo e(request('difficulty_level') == '3' ? 'selected' : ''); ?>>⭐⭐⭐ Intermediate</option>
            <option value="4" <?php echo e(request('difficulty_level') == '4' ? 'selected' : ''); ?>>⭐⭐⭐⭐ Advanced</option>
            <option value="5" <?php echo e(request('difficulty_level') == '5' ? 'selected' : ''); ?>>⭐⭐⭐⭐⭐ Expert</option>
        </select>

        <label class="flex items-center gap-2">
            <input type="checkbox" name="show_templates" value="1" <?php echo e(request('show_templates') ? 'checked' : ''); ?>>
            Show Templates Only
        </label>

        <button type="submit" class="btn-primary">🔍 Filter</button>
        <a href="<?php echo e(route('workout-plans.index')); ?>" class="btn-secondary">🔄 Clear</a>
    </form>
</div>

<!-- Workout Plans Table -->
<div class="table-container">
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>User</th>
                <th>Type</th>
                <th>Difficulty</th>
                <th>Duration</th>
                <th>Frequency</th>
                <th>Exercises</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $workoutPlans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><strong><?php echo e($plan->name); ?></strong></td>
                    <td>
                        <?php if($plan->is_template): ?>
                            <span class="template-badge">📋 Template</span>
                        <?php else: ?>
                            <?php echo e($plan->user->first_name); ?> <?php echo e($plan->user->last_name); ?>

                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if($plan->plan_type): ?>
                            <span class="type-badge"><?php echo e(ucfirst(str_replace('_', ' ', $plan->plan_type))); ?></span>
                        <?php else: ?>
                            <span class="text-muted">N/A</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if($plan->difficulty_level): ?>
                            <?php for($i = 1; $i <= 5; $i++): ?>
                                <?php if($i <= $plan->difficulty_level): ?>
                                    ⭐
                                <?php else: ?>
                                    ☆
                                <?php endif; ?>
                            <?php endfor; ?>
                            (<?php echo e($plan->difficulty_level); ?>/5)
                        <?php else: ?>
                            N/A
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if($plan->duration_weeks): ?>
                            <?php echo e($plan->duration_weeks); ?> weeks
                        <?php else: ?>
                            N/A
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if($plan->frequency_per_week): ?>
                            <?php echo e($plan->frequency_per_week); ?>x/week
                        <?php else: ?>
                            N/A
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if($plan->planExercises->count() > 0): ?>
                            <span class="exercise-count"><?php echo e($plan->planExercises->count()); ?> exercises</span>
                        <?php else: ?>
                            <span class="text-muted">No exercises</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if($plan->is_active): ?>
                            <span class="text-success">✅ Active</span>
                        <?php else: ?>
                            <span class="text-muted">❌ Inactive</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="flex gap-4">
                            <a href="<?php echo e(route('workout-plans.show', $plan)); ?>" class="btn-secondary">👁️ View</a>
                            <a href="<?php echo e(route('workout-plans.edit', $plan)); ?>" class="btn-primary">✏️ Edit</a>
                            <?php if($plan->is_template): ?>
                                <a href="<?php echo e(route('workout-plans.copy-template', $plan)); ?>" class="btn-success">📋 Copy</a>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="9" class="text-center text-muted">
                        No workout plans found. <a href="<?php echo e(route('workout-plans.create')); ?>">Create your first plan</a>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Pagination -->
<?php if($workoutPlans->hasPages()): ?>
    <div class="pagination">
        <?php echo e($workoutPlans->links()); ?>

    </div>
<?php endif; ?>

<style>
.template-badge {
    display: inline-block;
    padding: 4px 8px;
    background-color: #667eea;
    color: white;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 500;
}

.type-badge {
    display: inline-block;
    padding: 4px 8px;
    background-color: #38a169;
    color: white;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 500;
}

.exercise-count {
    font-weight: 500;
    color: #4a5568;
}
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Fitness-tracker\resources\views/workout-plans/index.blade.php ENDPATH**/ ?>