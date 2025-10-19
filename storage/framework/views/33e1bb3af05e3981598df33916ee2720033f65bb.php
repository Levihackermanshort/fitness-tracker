

<?php $__env->startSection('title', 'Nutrition Logs - Fitness Tracker'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div>
        <h1 class="page-title">🍎 Nutrition Logs</h1>
        <p class="page-subtitle">Track your daily nutrition intake</p>
    </div>
    <div>
        <a href="<?php echo e(route('nutrition-logs.create')); ?>" class="btn-primary">➕ Log Food</a>
        <a href="<?php echo e(route('nutrition-logs.daily-summary')); ?>" class="btn-secondary">📊 Daily Summary</a>
    </div>
</div>

<!-- Search and Filter Bar -->
<div class="search-filter-bar">
    <form method="GET" action="<?php echo e(route('nutrition-logs.index')); ?>" class="flex items-center gap-4">
        <select name="user_id" class="filter-select">
            <option value="">All Users</option>
            <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($user->id); ?>" <?php echo e(request('user_id') == $user->id ? 'selected' : ''); ?>>
                    <?php echo e($user->first_name); ?> <?php echo e($user->last_name); ?>

                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>

        <select name="meal_type" class="filter-select">
            <option value="">All Meals</option>
            <option value="breakfast" <?php echo e(request('meal_type') == 'breakfast' ? 'selected' : ''); ?>>Breakfast</option>
            <option value="lunch" <?php echo e(request('meal_type') == 'lunch' ? 'selected' : ''); ?>>Lunch</option>
            <option value="dinner" <?php echo e(request('meal_type') == 'dinner' ? 'selected' : ''); ?>>Dinner</option>
            <option value="snack" <?php echo e(request('meal_type') == 'snack' ? 'selected' : ''); ?>>Snack</option>
            <option value="pre_workout" <?php echo e(request('meal_type') == 'pre_workout' ? 'selected' : ''); ?>>Pre-Workout</option>
            <option value="post_workout" <?php echo e(request('meal_type') == 'post_workout' ? 'selected' : ''); ?>>Post-Workout</option>
        </select>

        <input type="date" name="start_date" value="<?php echo e(request('start_date')); ?>" class="form-input" placeholder="Start Date">
        <input type="date" name="end_date" value="<?php echo e(request('end_date')); ?>" class="form-input" placeholder="End Date">

        <button type="submit" class="btn-primary">🔍 Filter</button>
        <a href="<?php echo e(route('nutrition-logs.index')); ?>" class="btn-secondary">🔄 Clear</a>
    </form>
</div>

<!-- Nutrition Logs Table -->
<div class="table-container">
    <table class="table">
        <thead>
            <tr>
                <th>Date</th>
                <th>User</th>
                <th>Meal</th>
                <th>Food</th>
                <th>Quantity</th>
                <th>Calories</th>
                <th>Protein</th>
                <th>Carbs</th>
                <th>Fat</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $nutritionLogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($log->date->format('M d, Y')); ?></td>
                    <td><?php echo e($log->user->first_name); ?> <?php echo e($log->user->last_name); ?></td>
                    <td>
                        <?php if($log->meal_type): ?>
                            <span class="meal-badge"><?php echo e(ucfirst($log->meal_type)); ?></span>
                        <?php else: ?>
                            <span class="text-muted">N/A</span>
                        <?php endif; ?>
                    </td>
                    <td><strong><?php echo e($log->food_name); ?></strong></td>
                    <td><?php echo e($log->quantity); ?> <?php echo e($log->unit); ?></td>
                    <td>
                        <?php if($log->calories): ?>
                            <span class="calorie-text"><?php echo e(number_format($log->calories)); ?></span>
                        <?php else: ?>
                            <span class="text-muted">N/A</span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo e($log->protein_g ? number_format($log->protein_g, 1) . 'g' : 'N/A'); ?></td>
                    <td><?php echo e($log->carbs_g ? number_format($log->carbs_g, 1) . 'g' : 'N/A'); ?></td>
                    <td><?php echo e($log->fat_g ? number_format($log->fat_g, 1) . 'g' : 'N/A'); ?></td>
                    <td>
                        <div class="flex gap-4">
                            <a href="<?php echo e(route('nutrition-logs.show', $log)); ?>" class="btn-secondary">👁️ View</a>
                            <a href="<?php echo e(route('nutrition-logs.edit', $log)); ?>" class="btn-primary">✏️ Edit</a>
                        </div>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="10" class="text-center text-muted">
                        No nutrition logs found. <a href="<?php echo e(route('nutrition-logs.create')); ?>">Log your first meal</a>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Pagination -->
<?php if($nutritionLogs->hasPages()): ?>
    <div class="pagination">
        <?php echo e($nutritionLogs->links()); ?>

    </div>
<?php endif; ?>

<style>
.meal-badge {
    display: inline-block;
    padding: 4px 8px;
    background-color: #38a169;
    color: white;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 500;
}

.calorie-text {
    font-weight: bold;
    color: #e53e3e;
}
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Fitness-tracker\resources\views/nutrition-logs/index.blade.php ENDPATH**/ ?>