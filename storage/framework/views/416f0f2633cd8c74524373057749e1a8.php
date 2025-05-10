<body class="bg-gradient-to-br from-blue-50 to-blue-100 min-h-screen flex flex-col text-gray-800">
  <?php echo $__env->make('components.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
  <main class="container mx-auto p-4 flex-grow">
    <h2 class="text-4xl text-blue-600 font-extrabold mb-10 text-center drop-shadow">All Courses</h2>
    <section class="grid md:grid-cols-2 lg:grid-cols-3 gap-8 my-8">
      <?php if(count($courses) > 0): ?>
        <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <div class="bg-white rounded-xl shadow-lg p-8 text-center border-l-4 border-blue-400 transition-transform transform hover:scale-105 hover:shadow-2xl duration-200">
            <div class="flex flex-col items-center mb-4">
              <div class="bg-blue-100 rounded-full w-16 h-16 flex items-center justify-center mb-2">
                <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path d="M12 14l9-5-9-5-9 5 9 5z" />
                  <path d="M12 14l6.16-3.422A12.083 12.083 0 0112 21.5a12.083 12.083 0 01-6.16-10.922L12 14z" />
                </svg>
              </div>
              <h3 class="text-xl text-blue-600 font-bold mb-1"><?php echo e($course->title ?? $course->name); ?></h3>
            </div>
            <p class="text-gray-600 mb-2"><?php echo e($course->description); ?></p>
            <p class="text-gray-500 mb-4">Score: <span class="font-bold"><?php echo e($course->score ?? '-'); ?> Pt</span></p>
            <a href="<?php echo e(route('student.showCourse', $course->id)); ?>" class="bg-gradient-to-r from-blue-500 to-blue-700 hover:from-blue-600 hover:to-blue-800 text-white font-bold py-2 px-6 rounded-lg shadow transition duration-200">
              View Course
            </a>
          </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      <?php else: ?>
        <div class="col-span-3 text-center text-gray-500">
          No courses available at the moment.
        </div>
      <?php endif; ?>
    </section>
  </main>
</body>
<?php /**PATH /home/karim/Plateforme-de-Formation-en-Ligne-Interactive/resources/views/student/courses.blade.php ENDPATH**/ ?>