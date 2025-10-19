

<?php $__env->startSection('title', 'Exercise Types - Fitness Tracker'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div>
        <h1 class="page-title">💪 Exercise Types</h1>
        <p class="page-subtitle">Manage exercise types and categories</p>
    </div>
    <div>
        <a href="<?php echo e(route('exercise-types.create')); ?>" class="btn-primary">➕ Add Exercise</a>
    </div>
</div>

<!-- Search and Filter Bar -->
<div class="search-filter-bar">
    <form method="GET" action="<?php echo e(route('exercise-types.index')); ?>" class="flex items-center gap-4">
        <input type="text" name="search" placeholder="Search exercises..." value="<?php echo e(request('search')); ?>" class="search-input">
        
        <select name="category" class="filter-select">
            <option value="">All Categories</option>
            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($category); ?>" <?php echo e(request('category') == $category ? 'selected' : ''); ?>>
                    <?php echo e(ucfirst($category)); ?>

                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>

        <select name="difficulty_level" class="filter-select">
            <option value="">All Difficulty Levels</option>
            <option value="1" <?php echo e(request('difficulty_level') == '1' ? 'selected' : ''); ?>>⭐ Beginner</option>
            <option value="2" <?php echo e(request('difficulty_level') == '2' ? 'selected' : ''); ?>>⭐⭐ Easy</option>
            <option value="3" <?php echo e(request('difficulty_level') == '3' ? 'selected' : ''); ?>>⭐⭐⭐ Intermediate</option>
            <option value="4" <?php echo e(request('difficulty_level') == '4' ? 'selected' : ''); ?>>⭐⭐⭐⭐ Advanced</option>
            <option value="5" <?php echo e(request('difficulty_level') == '5' ? 'selected' : ''); ?>>⭐⭐⭐⭐⭐ Expert</option>
        </select>

        <select name="muscle_group" class="filter-select">
            <option value="">All Muscle Groups</option>
            <?php $__currentLoopData = $muscleGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $muscleGroup): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($muscleGroup); ?>" <?php echo e(request('muscle_group') == $muscleGroup ? 'selected' : ''); ?>>
                    <?php echo e(ucfirst($muscleGroup)); ?>

                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>

        <label class="flex items-center gap-2">
            <input type="checkbox" name="include_inactive" value="1" <?php echo e(request('include_inactive') ? 'checked' : ''); ?>>
            Include Inactive
        </label>

        <button type="submit" class="btn-primary">🔍 Filter</button>
        <a href="<?php echo e(route('exercise-types.index')); ?>" class="btn-secondary">🔄 Clear</a>
    </form>
</div>

<!-- Exercise Types Table -->
<div class="table-container">
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Category</th>
                <th>Muscle Groups</th>
                <th>Equipment</th>
                <th>Difficulty</th>
                <th>Calories/min</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $exerciseTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exercise): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><strong><?php echo e($exercise->name); ?></strong></td>
                    <td>
                        <span class="badge"><?php echo e(ucfirst($exercise->category)); ?></span>
                    </td>
                    <td>
                        <?php
                            $muscles = [];
                            if (is_array($exercise->muscle_groups)) {
                                $muscles = $exercise->muscle_groups;
                            } elseif (is_string($exercise->muscle_groups)) {
                                $decoded = json_decode($exercise->muscle_groups, true);
                                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                                    $muscles = $decoded;
                                } else {
                                    $muscles = array_filter(array_map('trim', explode(',', $exercise->muscle_groups)));
                                }
                            }
                        ?>
                        <?php if(count($muscles) > 0): ?>
                            <?php $__currentLoopData = $muscles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $muscle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <span class="muscle-tag"><?php echo e(ucfirst($muscle)); ?></span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                            <span class="text-muted">N/A</span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo e($exercise->equipment_needed ?? 'None'); ?></td>
                    <td>
                        <?php if($exercise->difficulty_level): ?>
                            <?php for($i = 1; $i <= 5; $i++): ?>
                                <?php if($i <= $exercise->difficulty_level): ?>
                                    ⭐
                                <?php else: ?>
                                    ☆
                                <?php endif; ?>
                            <?php endfor; ?>
                            (<?php echo e($exercise->difficulty_level); ?>/5)
                        <?php else: ?>
                            N/A
                        <?php endif; ?>
                    </td>
                    <td><?php echo e($exercise->calories_per_minute ? number_format($exercise->calories_per_minute, 1) : 'N/A'); ?></td>
                    <td>
                        <?php if($exercise->is_active): ?>
                            <span class="text-success">✅ Active</span>
                        <?php else: ?>
                            <span class="text-muted">❌ Inactive</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="flex gap-4">
                            <a href="<?php echo e(route('exercise-types.show', $exercise)); ?>" class="btn-secondary">👁️ View</a>
                            <a href="<?php echo e(route('exercise-types.edit', $exercise)); ?>" class="btn-primary">✏️ Edit</a>
                        </div>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="8" class="text-center text-muted">
                        No exercise types found. <a href="<?php echo e(route('exercise-types.create')); ?>">Add the first exercise</a>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Pagination -->
<?php if($exerciseTypes->hasPages()): ?>
    <div class="pagination">
        <?php echo e($exerciseTypes->links()); ?>

    </div>
<?php endif; ?>

<style>
.badge {
    display: inline-block;
    padding: 4px 8px;
    background-color: #667eea;
    color: white;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 500;
}

.muscle-tag {
    display: inline-block;
    padding: 2px 6px;
    background-color: #f7fafc;
    color: #4a5568;
    border: 1px solid #e2e8f0;
    border-radius: 3px;
    font-size: 11px;
    margin-right: 4px;
    margin-bottom: 2px;
}
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Fitness-tracker\resources\views/exercise-types/index.blade.php ENDPATH**/ ?>