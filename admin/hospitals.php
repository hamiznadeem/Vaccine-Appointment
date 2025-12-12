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
            $sql = "UPDATE hospitals SET isActive = CASE WHEN isActive = 1 THEN 0 ELSE 1 END WHERE hospital_id={$id} LIMIT 1";
            mysqli_query($conn, $sql);
            header('Location:' . $_SERVER['PHP_SELF']);
            exit;
        }
        if ($action === 'delete') {
            $sql = "DELETE FROM hospitals WHERE hospital_id={$id} LIMIT 1";
            mysqli_query($conn, $sql);
            header('Location:' . $_SERVER['PHP_SELF']);
            exit;
        }
    }
}

// If editing, fetch record to prefill
$edit_hospital = null;
if (isset($_GET['edit_id'])) {
    $eid = intval($_GET['edit_id']);
    if ($eid > 0) {
        $res = mysqli_query($conn, "SELECT * FROM hospitals WHERE hospital_id={$eid} LIMIT 1");
        if ($res && mysqli_num_rows($res) > 0) $edit_hospital = mysqli_fetch_assoc($res);
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
    $where[] = "(hospital_name LIKE '%{$safe_q}%' OR hospital_address LIKE '%{$safe_q}%' OR phone LIKE '%{$safe_q}%' OR email LIKE '%{$safe_q}%' OR lc_rg_no LIKE '%{$safe_q}%')";
}

$where_sql = '';
if (count($where) > 0) $where_sql = 'WHERE ' . implode(' AND ', $where);

$sql = "SELECT * FROM hospitals {$where_sql} ORDER BY hospital_id DESC";
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

        /* Custom Scrollbar Styling */
        .overflow-x-auto::-webkit-scrollbar {
            height: 3px;
            background-color: transparent;
        }

        .overflow-x-auto::-webkit-scrollbar-track {
            background-color: #f1f5f9;
            border-radius: 10px;
        }

        .overflow-x-auto::-webkit-scrollbar-thumb {
            background-color: #04216bff;
            border-radius: 10px;
        }

        .overflow-x-auto::-webkit-scrollbar-thumb:hover {
            background-color: #0a1e5f;
        }

        /* Firefox */
        .overflow-x-auto {
            scrollbar-color: #04216bff #f1f5f9;
            scrollbar-width: thin;
        }
</style>

<!-- Main Content -->
        <main class="relative lg:ml-64 pt-6 lg:pt-10 pb-10 min-h-screen bg-slate-50 overflow-x-auto">
            
            <!-- Navbar -->
            <div class="sticky top-6 z-30 px-6 lg:px-10 mb-8 hidden lg:block">
                <div class="bg-white/80 backdrop-blur-xl border border-white/60 rounded-full px-6 py-3 shadow-sm flex justify-between items-center">
                    <h2 class="text-slate-800 font-bold text-lg pl-2">Registered Hospitals</h2>
                    
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
                        <a href="hospitals.php?status=all" class="px-6 py-2 <?php echo $status==='all' ? 'bg-darkblue text-white' : 'text-gray-500 hover:text-primary hover:bg-gray-50'; ?> rounded-lg text-sm font-bold  transition whitespace-nowrap">All Hospital</a>
                        <a href="hospitals.php?status=active" class="px-6 py-2 <?php echo $status==='active' ? 'bg-darkblue text-white' : 'text-gray-500 hover:text-primary hover:bg-gray-50'; ?> rounded-lg text-sm font-bold transition whitespace-nowrap">Active</a>
                        <a href="hospitals.php?status=blocked" class="px-6 py-2 <?php echo $status==='blocked' ? 'bg-darkblue text-white' : 'text-gray-500 hover:text-primary hover:bg-gray-50'; ?> rounded-lg text-sm font-bold transition whitespace-nowrap">Blocked</a>
                    </div>
                    <!-- Search & Filter (GET form) -->
                    <form method="GET" action="hospitals.php" class="flex flex-col sm:flex-row gap-3 w-full xl:w-auto">
                        <input type="hidden" name="status" value="<?php echo htmlspecialchars($status); ?>">
                        <div class="relative w-full sm:w-64">
                            <input type="text" name="q" value="<?php echo htmlspecialchars($q); ?>" placeholder="Search hospital name..." class="w-full pl-10 pr-4 py-2.5 bg-white border border-gray-200 rounded-xl shadow-sm focus:outline-none focus:border-primary transition text-sm">
                            <i data-lucide="search" class="absolute left-3 top-3 w-4 h-4 text-gray-400"></i>
                        </div>
                        <button type="submit" class="px-4 py-2.5 bg-darkblue border border-gray-200 text-white rounded-xl text-sm font-bold flex items-center justify-center gap-2 shadow-sm whitespace-nowrap">
                            <i data-lucide="search" class="w-4 h-4"></i> Search
                        </button>
                    </form>
                </div>

                <?php if ($message != ''){?>

                    <div class="p-4">
                        <div class="<?php echo $messageType == 'success' ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700'; ?> rounded-xl p-3 text-sm font-semibold">
                            <?php echo htmlspecialchars($message); ?>
                        </div>
                    </div>
                    <?php } ?>

                <!-- Hospitals Table -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="min-h-[300px] overflow-x-auto">
                        <table class="w-full text-left text-sm text-gray-600">
                            <thead class="bg-gray-50 text-gray-800 font-bold uppercase text-xs tracking-wider">
                                <tr>
                                    <th class="px-6 py-4">ID</th>
                                    <th class="px-6 py-4">Hospital Name</th>
                                    <th class="px-6 py-4">Location</th>
                                    <th class="px-6 py-4">Contact</th>
                                    <th class="px-6 py-4">Lc/Rg</th>
                                    <th class="px-6 py-4">Status</th>
                                    <th class="px-6 py-4 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <?php
                                if ($result && mysqli_num_rows($result) > 0){
                                while($hos_row = mysqli_fetch_assoc($result)){
                                ?>
                                <!-- Row 1 -->
                                <tr class="hover:bg-blue-50/50 transition group">
                                    <td class="px-4 py-4 text-xs font-medium text-primary">#HOS-<?php echo $hos_row['hospital_id']?></td>
                                    <td class="px-4 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-12 h-12 rounded-3xl bg-blue-100 overflow-hidden border-4 border-white shadow-md flex items-center justify-center">
                                                <?php if(!empty($hospital['hospital_img'])): ?>
                                                    <img src="<?php echo htmlspecialchars($hos_row['hospital_img']); ?>" class="w-full h-full object-cover" alt="Hospital">
                                                <?php else: ?>
                                                <i data-lucide="hospital" class="w-4 h-4 text-gray-400"></i>
                                                <?php endif; ?>
                                            </div>
                                            <span class="font-bold text-xs text-slate-800"><?php echo htmlspecialchars($hos_row['hospital_name']); ?></span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-xs"><?php echo htmlspecialchars($hos_row['hospital_address']); ?></td>
                                    <td class="px-4 py-4"><?php echo htmlspecialchars($hos_row['phone']); ?></td>
                                    <td class="px-4 py-4"><?php echo htmlspecialchars($hos_row['lc_rg_no']); ?></td>
                                    <td class="px-4 py-4">
                                        <?php if ($hos_row['isActive']): ?>
                                            <span class="badge-active px-3 py-1 rounded-full text-xs font-bold">Active</span>
                                        <?php else: ?>
                                            <span class="badge-blocked px-3 py-1 rounded-full text-xs font-bold">Blocked</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-4 py-4 text-center relative">
                                        <button onclick="toggleDropdown(this)" class="p-2 hover:bg-gray-100 rounded-lg text-gray-500 transition focus:outline-none">
                                            <i data-lucide="more-vertical" class="w-4 h-4"></i>
                                        </button>
                                        <!-- Dropdown Menu -->
                                        <div class="dropdown-menu absolute right-8 top-8 w-32 bg-white rounded-xl shadow-xl border border-gray-100 z-10 overflow-hidden">
                                            <a href="hospital_view.php?id=<?php echo $hos_row['hospital_id']; ?>" class="block px-4 py-2 text-left text-xs font-bold text-gray-700 hover:bg-gray-50 hover:text-primary flex items-center gap-2">
                                                <i data-lucide="view" class="w-3 h-3"></i> View
                                            </a>
                                            <a href="?action=block&id=<?php echo $hos_row['hospital_id']; ?>" class="block px-4 py-2 text-left text-xs font-bold <?php echo $hos_row['isActive'] ? 'text-red-500' : 'text-green-600'; ?> hover:bg-red-50 flex items-center gap-2">
                                                <i data-lucide="ban" class="w-3 h-3"></i> <?php echo $hos_row['isActive'] ? 'Block' : 'Unblock'; ?>
                                            </a>
                                            <a href="?action=delete&id=<?php echo $hos_row['hospital_id']; ?>" class="block px-4 py-2 text-left text-xs font-bold text-red-500 hover:bg-red-50 flex items-center gap-2">
                                                <i data-lucide="trash-2" class="w-3 h-3"></i> Remove
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php }
                                }else {
                                ?>
                                <!-- Fallback row -->
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                        <i data-lucide="users" class="w-10 h-10 mx-auto mb-4"></i>
                                        <div class="font-bold">No hospital found.</div>
                                        <div class="text-xs mt-1">Try adjusting your filters or search query.</div>
                                    </td>
                                </tr>
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