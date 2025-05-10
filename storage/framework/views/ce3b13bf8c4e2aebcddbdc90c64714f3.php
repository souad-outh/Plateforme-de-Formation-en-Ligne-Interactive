<?php echo $__env->make('components.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<body class="bg-gradient-to-br from-blue-50 to-blue-100 min-h-screen flex flex-col text-gray-800">
  <main class="container mx-auto p-4 flex-grow">
    <section class="my-12">
      <h2 class="text-4xl text-blue-600 font-extrabold mb-10 text-center drop-shadow">
        Welcome, <?php echo e(Auth::user()->username); ?>!
      </h2>
      <div class="grid md:grid-cols-3 gap-10">
        <div class="bg-white rounded-xl shadow-lg p-8 text-center border-l-4 border-blue-400 transition-transform transform hover:scale-105 hover:shadow-2xl duration-200">
          <div class="flex flex-col items-center mb-4">
            <div class="bg-blue-100 rounded-full w-16 h-16 flex items-center justify-center mb-2">
              <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M12 14l9-5-9-5-9 5 9 5z" />
                <path d="M12 14l6.16-3.422A12.083 12.083 0 0112 21.5a12.083 12.083 0 01-6.16-10.922L12 14z" />
              </svg>
            </div>
            <h3 class="text-xl text-blue-600 font-bold mb-1">My Courses</h3>
          </div>
          <p class="text-gray-600 mb-6">View courses you attempted their quizzes.</p>
          <button onclick="window.location.href='<?php echo e(route('student.myCourses')); ?>'" class="bg-gradient-to-r from-blue-500 to-blue-700 hover:from-blue-600 hover:to-blue-800 text-white font-bold py-2 px-6 rounded-lg shadow transition duration-200">
            My Courses
          </button>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-8 text-center border-l-4 border-blue-400 transition-transform transform hover:scale-105 hover:shadow-2xl duration-200">
          <div class="flex flex-col items-center mb-4">
            <div class="bg-blue-100 rounded-full w-16 h-16 flex items-center justify-center mb-2">
              <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M12 14l9-5-9-5-9 5 9 5z" />
                <path d="M12 14l6.16-3.422A12.083 12.083 0 0112 21.5a12.083 12.083 0 01-6.16-10.922L12 14z" />
              </svg>
            </div>
            <h3 class="text-xl text-blue-600 font-bold mb-1">All Courses</h3>
          </div>
          <p class="text-gray-600 mb-6">View all available courses.</p>
          <button onclick="window.location.href='<?php echo e(route('student.courses')); ?>'" class="bg-gradient-to-r from-blue-500 to-blue-700 hover:from-blue-600 hover:to-blue-800 text-white font-bold py-2 px-6 rounded-lg shadow transition duration-200">
            All Courses
          </button>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-8 text-center border-l-4 border-blue-400 transition-transform transform hover:scale-105 hover:shadow-2xl duration-200">
          <div class="flex flex-col items-center mb-4">
            <div class="bg-blue-100 rounded-full w-16 h-16 flex items-center justify-center mb-2">
              <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M12 14l9-5-9-5-9 5 9 5z" />
                <path d="M12 14l6.16-3.422A12.083 12.083 0 0112 21.5a12.083 12.083 0 01-6.16-10.922L12 14z" />
              </svg>
            </div>
            <h3 class="text-xl text-blue-600 font-bold mb-1">Progress</h3>
          </div>
          <p class="text-gray-600 mb-6">Track your learning milestones.</p>
          <button onclick="window.location.href='<?php echo e(route('student.progress')); ?>'" class="bg-gradient-to-r from-blue-500 to-blue-700 hover:from-blue-600 hover:to-blue-800 text-white font-bold py-2 px-6 rounded-lg shadow transition duration-200">
            View Progress
          </button>
        </div>
      </div>
    </section>
  </main>
</body>
</html>
<?php /**PATH /home/karim/Plateforme-de-Formation-en-Ligne-Interactive/resources/views/student/LearnerDashboard.blade.php ENDPATH**/ ?>