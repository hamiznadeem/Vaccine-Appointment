<?php
ob_start();
include 'admin_partials/inc_header.php';

// messages
$message = '';
$messageType = '';

// Handle GET actions: toggle block/unblock or delete
if (isset($_GET['action'], $_GET['id'])) {
    $action = $_GET['action'];
    $id = intval($_GET['id']);
    if ($id > 0) {
        if ($action === 'block') {
            $sql = "UPDATE parents SET isActive = CASE WHEN isActive = 1 THEN 0 ELSE 1 END WHERE parent_id={$id} LIMIT 1";
            mysqli_query($conn, $sql);
            header('Location:' . $_SERVER['PHP_SELF']);
            exit;
        }
        if ($action === 'delete') {
            $sql = "DELETE FROM parents WHERE parent_id={$id} LIMIT 1";
            mysqli_query($conn, $sql);
            header('Location:' . $_SERVER['PHP_SELF']);
            exit;
        }
    }
}



$status = isset($_GET['status']) ? $_GET['status'] : 'all';
$q = isset($_GET['q']) ? trim($_GET['q']) : '';

$where = [];
if ($status === 'active') {
    $where[] = "isActive = 1";
} elseif ($status === 'blocked') {
    $where[] = "isActive = 0";
}
if ($q !== '') {
    $safe_q = mysqli_real_escape_string($conn, $q);
    $where[] = "(parent_id LIKE '%{$safe_q}%' OR fname LIKE '%{$safe_q}%' OR phone LIKE '%{$safe_q}%' OR email LIKE '%{$safe_q}%' OR cnic LIKE '%{$safe_q}%')";
}

$where_sql = '';
if (count($where) > 0) $where_sql = 'WHERE ' . implode(' AND ', $where);

$sql = "SELECT * FROM parents {$where_sql} ORDER BY parent_id DESC";
$result = mysqli_query($conn, $sql);

?>

<style>
      /* Status Badges */
        .badge-active { background-color: #D1FAE5; color: #059669; } /* Green */
        .badge-blocked { background-color: #FEE2E2; color: #DC2626; } /* Red */

        /* Dropdown Animation */
        .dropdown-menu {
            transform-origin: top right;
            transition: transform 0.1s ease-out, opacity 0.1s ease-out;
            transform: scale(0.95);
            opacity: 0;
            pointer-events: none;
        }
        .dropdown-menu.show {
            transform: scale(1);
            opacity: 1;
            pointer-events: auto;
        }
</style>

<!-- Main Content -->
        <main class="relative lg:ml-64 pt-6 lg:pt-10 pb-10 min-h-screen bg-slate-50">
            
            <!-- Navbar -->
            <div class="sticky top-6 z-30 px-6 lg:px-10 mb-8 hidden lg:block">
                <div class="bg-white/80 backdrop-blur-xl border border-white/60 rounded-full px-6 py-3 shadow-sm flex justify-between items-center">
                    <h2 class="text-slate-800 font-bold text-lg pl-2">Registered Parents</h2>
                    
                    <div class="flex items-center gap-6 pr-1">
                        <button class="relative text-slate-500 hover:text-primary transition">
                            <i data-lucide="bell" class="w-5 h-5"></i>
                            <span class="absolute -top-1 -right-1 w-2.5 h-2.5 bg-red-500 rounded-full border-2 border-white"></span>
                        </button>

                       <div class="flex items-center gap-3 pl-6 border-l border-gray-200">
                    <div class="text-right hidden md:block">
                        <div class="text-xs font-bold text-slate-800"><?php echo $_SESSION['admin_name']; ?></div>
                        <div class="text-[10px] text-slate-500 uppercase"><?php echo $_SESSION['user_name']; ?></div>
                    </div>
                    <div class="w-9 h-9 rounded-full bg-darkblue text-white flex items-center justify-center font-bold text-sm shadow-md">
                        <?php echo strtoupper(substr($_SESSION['admin_name'], 0, 2)); ?>
                    </div>
                </div>
                    </div>
                </div>
            </div>

            <div class="px-6 lg:px-10 pb-10 space-y-6 fade-in">
                
                <!-- Action Bar & Tabs -->
                <div class="flex flex-col xl:flex-row justify-between items-start xl:items-center gap-6">
                    
                    <!-- Tabs -->
                    <div class="bg-white p-1 no-scrollbar rounded-xl border border-gray-200 flex overflow-x-auto w-full xl:w-auto">
                        <a href="parents.php?status=all" class="px-6 py-2 <?php echo $status==='all' ? 'bg-darkblue text-white' : 'bg-white text-gray-500 hover:text-primary hover:bg-gray-50'; ?> rounded-lg text-sm font-bold transition whitespace-nowrap">All Parents</a>
                        <a href="parents.php?status=active" class="px-6 py-2 <?php echo $status==='active' ? 'bg-darkblue text-white' : 'bg-white text-gray-500 hover:text-primary hover:bg-gray-50'; ?> rounded-lg text-sm font-bold transition whitespace-nowrap">Active</a>
                        <a href="parents.php?status=blocked" class="px-6 py-2 <?php echo $status==='blocked' ? 'bg-darkblue text-white' : 'bg-white text-gray-500 hover:text-primary hover:bg-gray-50'; ?> rounded-lg text-sm font-bold      transition whitespace-nowrap">Blocked</a>
                    </div>

                    <!-- Search & Filter (GET form) -->
                    <form method="GET" action="parents.php" class="flex flex-col sm:flex-row gap-3 w-full xl:w-auto">
                        <input type="hidden" name="status" value="<?php echo htmlspecialchars($status); ?>">
                        <div class="relative w-full sm:w-64">
                            <input type="text" name="q" value="<?php echo htmlspecialchars($q); ?>" placeholder="Search by name or phone..." class="w-full pl-10 pr-4 py-2.5 bg-white border border-gray-200 rounded-xl shadow-sm focus:outline-none focus:border-primary transition text-sm">
                            <i data-lucide="search" class="absolute left-3 top-3 w-4 h-4 text-gray-400"></i>
                        </div>
                        <button type="submit" name="search" class="px-4 py-2.5 bg-darkblue text-white rounded-xl text-sm font-bold hover:bg-blue-800 transition shadow-md flex items-center justify-center gap-2 whitespace-nowrap">
                            <i data-lucide="Search" class="w-4 h-4"></i> Search
                        </button>
                    </form>
                </div>

                <?php if ($message): ?>
                    <div class="p-4">
                        <div class="<?php echo $messageType == 'success' ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700'; ?> rounded-xl p-3 text-sm font-semibold">
                            <?php echo htmlspecialchars($message); ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Parents Table -->
                <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
                    <div class="overflow-x-auto min-h-[300px]">
                        <table class="w-full text-left text-sm text-gray-600">
                            <thead class="bg-gray-50 text-gray-800 font-bold uppercase text-xs tracking-wider">
                                <tr>
                                    <th class="px-6 py-4">Parent Name</th>
                                    <th class="px-6 py-4">CNIC / ID</th>
                                    <th class="px-6 py-4">Phone Number</th>
                                    <th class="px-6 py-4">Children</th>
                                    <th class="px-6 py-4">Status</th>
                                    <th class="px-6 py-4 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <?php

                                if ($result && mysqli_num_rows($result) > 0) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                ?>
                                <tr class="hover:bg-blue-50/50 transition group">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center font-bold text-xs"><?php echo strtoupper(substr($row['fname'],0,2)); ?></div>
                                            <div>
                                                <div class="font-bold text-slate-800"><?php echo htmlspecialchars($row['fname']); ?></div>
                                                <div class="text-[10px] text-gray-400">ID: #P-<?php echo htmlspecialchars($row['parent_id']); ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-xs font-mono"><?php echo htmlspecialchars($row['cnic'] ?? 'N/A'); ?></td>
                                    <td class="px-6 py-4 text-xs font-medium text-slate-700"><?php echo htmlspecialchars($row['phone']); ?></td>
                                    <td class="px-6 py-4">
                                        <span class="bg-gray-100 text-gray-600 px-3 py-1 rounded-lg text-xs font-bold flex items-center gap-1 w-fit">
                                            <i data-lucide="baby" class="w-3 h-3"></i> <?php 
                                                $parent_id = intval($row['parent_id']);
                                                $child_res = mysqli_query($conn, "SELECT COUNT(*) AS child_count FROM childrens WHERE parent_id={$parent_id}");
                                                $child_count = 0;
                                                if ($child_res && mysqli_num_rows($child_res) > 0) {
                                                    $child_row = mysqli_fetch_assoc($child_res);
                                                    $child_count = intval($child_row['child_count']);
                                                }
                                                echo $child_count;
                                                ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4"><span class="<?php echo ($row['isActive'] ?? 0) ? 'badge-active' : 'badge-blocked'; ?> px-3 py-1 rounded-full text-xs font-bold"><?php echo ($row['isActive'] ?? 0) ? 'Active' : 'Blocked'; ?></span></td>
                                    <td class="px-6 py-4 text-center relative">
                                        <button onclick="toggleDropdown(this)" class="p-2 hover:bg-gray-100 rounded-lg text-gray-500 transition focus:outline-none">
                                            <i data-lucide="more-vertical" class="w-4 h-4"></i>
                                        </button>
                                        <div class="dropdown-menu absolute right-8 top-8 w-40 bg-white rounded-xl shadow-xl border border-gray-100 z-10 overflow-hidden">
                                            <a href="?edit_id=<?php echo $row['parent_id']; ?>" class="block px-4 py-2 text-left text-xs font-medium text-gray-700 hover:bg-gray-50 hover:text-primary flex items-center gap-2">
                                                <i data-lucide="view" class="w-3 h-3"></i> View
                                            </a>
                                            <a href="?action=block&id=<?php echo $row['parent_id']; ?>" class="block px-4 py-2 text-left text-xs font-medium <?php echo ($row['isActive'] ?? 0) ? 'text-red-500' : 'text-green-600'; ?> hover:bg-red-50 flex items-center gap-2" onclick="return confirm('Are you sure?');">
                                                <i data-lucide="ban" class="w-3 h-3"></i> <?php echo ($row['isActive'] ?? 0) ? 'Block' : 'Unblock'; ?>
                                            </a>
                                            <a href="?action=delete&id=<?php echo $row['parent_id']; ?>" class="block px-4 py-2 text-left text-xs font-medium text-red-500 hover:bg-red-50 flex items-center gap-2" onclick="return confirm('Delete this parent record?');">
                                                <i data-lucide="trash-2" class="w-3 h-3"></i> Remove
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php
                                    }
                                } else {
                                ?>
                                <!-- Fallback row -->
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                        <i data-lucide="users" class="w-10 h-10 mx-auto mb-4"></i>
                                        <div class="font-bold">No parents found.</div>
                                        <div class="text-xs mt-1">Try adjusting your filters or search query.</div>
                                    </td>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    
                </div>

            </div>
        </main>

        <script>
            // Toggle Dropdown (Simulated)
            function toggleDropdown(btn) {
                // Close all other dropdowns
                const allDropdowns = document.querySelectorAll('.dropdown-menu');
                allDropdowns.forEach(d => {
                    if (d !== btn.nextElementSibling) d.classList.remove('show');
                });

                // Toggle current
                const dropdown = btn.nextElementSibling;
                dropdown.classList.toggle('show');
            }

            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!e.target.closest('td.relative')) {
                    document.querySelectorAll('.dropdown-menu').forEach(d => d.classList.remove('show'));
                }
            });
        </script>

<?php
include 'admin_partials/inc_footer.php';
ob_end_flush();
?>