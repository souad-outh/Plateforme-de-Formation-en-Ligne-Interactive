<?php echo $__env->make('components.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<body class="bg-gradient-to-br from-blue-50 to-blue-100 min-h-screen flex flex-col text-gray-800">
  <main class="container mx-auto p-4 flex-grow">
    <section class="my-12 bg-white rounded-xl shadow-lg p-8">
      <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
        <h2 class="text-3xl text-blue-600 font-extrabold drop-shadow">Manage Quizzes</h2>
        <a href="<?php echo e(route('admin.createQuiz')); ?>" class="bg-gradient-to-r from-blue-500 to-blue-700 hover:from-blue-600 hover:to-blue-800 text-white font-bold py-2 px-6 rounded-lg shadow transition duration-200">
          Add New Quiz
        </a>
      </div>
      <div class="overflow-x-auto">
        <table class="w-full border-collapse bg-white shadow text-center">
          <thead >
            <tr>
              <th class="border-b-2 p-3 text-blue-700 w-[15%]">ID</th>
              <th class="border-b-2 p-3 text-blue-700 w-[15%]">Name</th>
              <th class="border-b-2 p-3 text-blue-700 w-[15%]">Course</th>
              <th class="border-b-2 p-3 text-blue-700 w-[15%]">Category</th>
              <th class="border-b-2 p-3 text-blue-700 w-[25%]">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if($quizzes->isEmpty()): ?>
              <tr class="bg-blue-50">
                <td colspan="3" class="border p-2">No quizzes available at the moment.</td>
              </tr>
            <?php endif; ?>
            <?php $__currentLoopData = $quizzes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $quiz): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <tr class="<?php echo e($loop->even ? 'bg-blue-50' : ''); ?>">
                <td class="p-3"><?php echo e($quiz->id); ?></td>
                <td class="p-3"><?php echo e($quiz->name); ?></td>
                <td class="p-3"><?php echo e($quiz->course->name); ?></td>
                <td class="p-3"><?php echo e($quiz->course->category->name); ?></td>
                <td class="p-3">
                  <a href="<?php echo e(route('admin.quizQuestions', $quiz->id)); ?>" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-1 px-2 rounded transition">Questions</a>
                  <a href="<?php echo e(route('admin.editQuiz', $quiz->id)); ?>" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded transition">Edit</a>
                  <form action="<?php echo e(route('admin.deleteQuiz', $quiz->id)); ?>" method="POST" style="display:inline;">
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
</body><?php /**PATH /home/karim/Plateforme-de-Formation-en-Ligne-Interactive/resources/views/admin/quizzes.blade.php ENDPATH**/ ?>