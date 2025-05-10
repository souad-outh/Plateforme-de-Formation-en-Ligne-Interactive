<?php echo $__env->make('components.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<body class="bg-gradient-to-br from-blue-50 to-blue-100 min-h-screen flex flex-col text-gray-800">
  <main class="container mx-auto p-4 flex-grow">
    <section class="my-12">
      <h2 class="text-4xl text-blue-600 font-extrabold mb-10 text-center drop-shadow">Admin Dashboard</h2>
      <div class="grid md:grid-cols-3 gap-10 mb-12">
        <div class="bg-white rounded-xl shadow-lg p-8 text-center border-l-4 border-blue-400">
          <h3 class="text-xl text-blue-500 font-semibold mb-2">Total Users</h3>
          <p class="text-3xl text-gray-700 font-bold"><?php echo e($totalUsers); ?></p>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-8 text-center border-l-4 border-blue-400">
          <h3 class="text-xl text-blue-500 font-semibold mb-2">Active Courses</h3>
          <p class="text-3xl text-gray-700 font-bold"><?php echo e($activeCourses); ?></p>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-8 text-center border-l-4 border-blue-400">
          <h3 class="text-xl text-blue-500 font-semibold mb-2">Quizzes Taken</h3>
          <p class="text-3xl text-gray-700 font-bold"><?php echo e($quizzesTaken); ?></p>
        </div>
      </div>

      <div class="bg-white rounded-xl shadow-lg p-8 max-w-3xl mx-auto">
        <h3 class="text-2xl text-blue-600 font-bold mb-6 text-center">Top 10 Users Leaderboard</h3>
        <div class="overflow-x-auto">
          <table class="w-full border-collapse bg-white shadow">
            <thead>
              <tr>
                <th class="border-b-2 p-3 text-left text-blue-700">Rank</th>
                <th class="border-b-2 p-3 text-left text-blue-700">Name</th>
                <th class="border-b-2 p-3 text-left text-blue-700">Total Score</th>
                <th class="border-b-2 p-3 text-left text-blue-700">Quizzes Taken</th>
              </tr>
            </thead>
            <tbody>
              <?php $__currentLoopData = $leaders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr class="<?php echo e($index % 2 === 0 ? 'bg-blue-50' : ''); ?>">
                  <td class="p-3 font-bold text-blue-500"><?php echo e($index + 1); ?></td>
                  <td class="p-3 font-semibold"><?php echo e($user->username); ?></td>
                  <td class="p-3">
                    <span class="inline-block bg-blue-500 text-white px-4 py-1 rounded-full font-bold shadow"><?php echo e($user->total_score); ?></span>
                  </td>
                  <td class="p-3"><?php echo e($user->quizzes_count); ?></td>
                </tr>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              <?php if($leaders->isEmpty()): ?>
                <tr>
                  <td colspan="4" class="p-4 text-center text-gray-500">No leaderboard data available.</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </section>
  </main>
</body>
</html><?php /**PATH /home/karim/Plateforme-de-Formation-en-Ligne-Interactive/resources/views/admin/dashboard.blade.php ENDPATH**/ ?>