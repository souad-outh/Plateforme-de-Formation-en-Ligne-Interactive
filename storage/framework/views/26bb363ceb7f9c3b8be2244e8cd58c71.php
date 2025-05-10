<?php echo $__env->make('components.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<body class="bg-gradient-to-br from-blue-50 to-blue-100 min-h-screen flex flex-col text-gray-800">
  <main class="container mx-auto p-4 flex-grow">
    <section class="my-12 bg-white rounded-xl shadow-lg p-8">
      <h2 class="text-3xl text-blue-600 font-extrabold mb-6 text-center drop-shadow">Reclamation Oversight</h2>
      <div class="overflow-x-auto">
        <table class="w-full border-collapse bg-white shadow">
          <thead>
            <tr>
              <th class="border-b-2 p-3 text-left text-blue-700">User</th>
              <th class="border-b-2 p-3 text-left text-blue-700">Message</th>
              <th class="border-b-2 p-3 text-left text-blue-700">Status</th>
              <th class="border-b-2 p-3 text-left text-blue-700">Response</th>
              <th class="border-b-2 p-3 text-left text-blue-700">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if($reclamations->isEmpty()): ?>
              <tr>
                <td colspan="5" class="p-3 text-center text-gray-500">No reclamations found.</td>
              </tr>
            <?php endif; ?>
            <?php $__currentLoopData = $reclamations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reclamation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <tr class="<?php echo e($loop->even ? 'bg-blue-50' : ''); ?>">
                <td class="p-3 font-semibold"><?php echo e($reclamation->user->username ?? 'Unknown'); ?></td>
                <td class="p-3"><?php echo e($reclamation->message); ?></td>
                <td class="p-3">
                  <span class="inline-block px-3 py-1 rounded-full text-white <?php echo e($reclamation->status === 'resolved' ? 'bg-green-500' : 'bg-yellow-500'); ?>">
                    <?php echo e($reclamation->status); ?>

                  </span>
                </td>
                <td class="p-3"><?php echo e($reclamation->response ?? '-'); ?></td>
                <td class="p-3">
                <?php if($reclamation->status !== 'resolved'): ?>
                  <a href="<?php echo e(route('admin.respondReclamation', $reclamation->id)); ?>" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded transition">Respond</a>
                <?php else: ?>
                <p class="bg-gray-500 hover:bg-gray-700 cursor-not-allowed text-white font-bold py-1 px-2 rounded transition inline-block">Resolved</p>
                <?php endif; ?>
                <form action="<?php echo e(route('admin.deleteReclamation', $reclamation->id)); ?>" method="POST" class="inline">
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
</body><?php /**PATH /home/karim/Plateforme-de-Formation-en-Ligne-Interactive/resources/views/admin/reclamations.blade.php ENDPATH**/ ?>