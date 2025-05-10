<?php echo $__env->make('components.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<body class="bg-gradient-to-br from-blue-50 to-blue-100 min-h-screen flex flex-col text-gray-800">
  <main class="container mx-auto p-4 flex-grow">
    <section class="my-12 bg-white rounded-xl shadow-lg p-8">
      <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
        <h2 class="text-3xl text-blue-600 font-extrabold drop-shadow">Manage Courses</h2>
        <a href="<?php echo e(route('admin.createCourse')); ?>" class="bg-gradient-to-r from-blue-500 to-blue-700 hover:from-blue-600 hover:to-blue-800 text-white font-bold py-2 px-6 rounded-lg shadow transition duration-200">
          Add New Course
        </a>
      </div>
      <div class="overflow-x-auto">
        <table class="w-full border-collapse bg-white shadow">
          <thead>
            <tr>
              <th class="border-b-2 p-3 text-left text-blue-700">Name</th>
              <th class="border-b-2 p-3 text-left text-blue-700">Score</th>
              <th class="border-b-2 p-3 text-left text-blue-700">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <tr class="<?php echo e($loop->even ? 'bg-blue-50' : ''); ?>">
                <td class="p-3">
                  <a href="<?php echo e(route('admin.showCourse', $course->id)); ?>" class="hover:underline text-blue-600 font-semibold">
                    <?php echo e($course->name); ?>

                  </a>
                </td>
                <td class="p-3"><?php echo e($course->score); ?></td>
                <td class="p-3">
                  <a href="<?php echo e(route('admin.editCourse', $course->id)); ?>" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded transition">Edit</a>
                  <form action="<?php echo e(route('admin.deleteCourse', $course->id)); ?>" method="POST" class="inline">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded transition" onclick="return confirm('Are you sure?')">Delete</button>
                  </form>
                </td>
              </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </tbody>
        </table>
      </div>
    </section>
  </main>
</body><?php /**PATH /home/karim/Plateforme-de-Formation-en-Ligne-Interactive/resources/views/admin/courses.blade.php ENDPATH**/ ?>