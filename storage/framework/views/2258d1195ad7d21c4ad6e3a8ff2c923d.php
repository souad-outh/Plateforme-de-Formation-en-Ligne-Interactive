<?php echo $__env->make('components.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<body class="bg-gradient-to-br from-blue-50 to-blue-100 min-h-screen flex flex-col text-gray-800">
  <main class="container mx-auto p-4 flex-grow">
    <section class="my-12 bg-white rounded-xl shadow-lg p-8">
      <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
        <h2 class="text-3xl text-blue-600 font-extrabold drop-shadow">Manage Categories</h2>
        <a href="<?php echo e(route('admin.createCategory')); ?>" class="bg-gradient-to-r from-green-500 to-green-700 hover:from-green-600 hover:to-green-800 text-white font-bold py-2 px-6 rounded-lg shadow transition duration-200">
          Add New Category
        </a>
      </div>
      <div class="overflow-x-auto">
        <table class="w-full border-collapse bg-white shadow">
          <thead>
            <tr>
              <th class="border-b-2 p-3 text-left text-blue-700">Name</th>
              <th class="border-b-2 p-3 text-left text-blue-700">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <tr class="<?php echo e($loop->even ? 'bg-blue-50' : ''); ?>">
                <td class="p-3"><?php echo e($category->name); ?></td>
                <td class="p-3">
                  <a href="<?php echo e(route('admin.editCategory', $category->id)); ?>" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded transition">Edit</a>
                  <form action="<?php echo e(route('admin.deleteCategory', $category->id)); ?>" method="POST" class="inline">
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
</body><?php /**PATH /home/karim/Plateforme-de-Formation-en-Ligne-Interactive/resources/views/admin/categories.blade.php ENDPATH**/ ?>