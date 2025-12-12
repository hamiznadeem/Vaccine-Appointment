<?php
ob_start();
include 'admin_partials/inc_header.php';

// messages
$message = '';
$messageType = '';


// Handle GET actions: delete
if (isset($_GET['action'], $_GET['id'])) {
    $action = $_GET['action'];
    $id = intval($_GET['id']);
    if ($id > 0) {
        if ($action === 'delete') {
            $sql = "DELETE FROM childrens WHERE child_id={$id} LIMIT 1";
            mysqli_query($conn, $sql);
            header('Location:' . $_SERVER['PHP_SELF']);
            exit;
        }
    }
}

// If editing, fetch record to prefill
$edit_child = null;
if (isset($_GET['edit_id'])) {
    $eid = intval($_GET['edit_id']);
    if ($eid > 0) {
        $res = mysqli_query($conn, "SELECT * FROM childrens WHERE child_id={$eid} LIMIT 1");
        if ($res && mysqli_num_rows($res) > 0) $edit_child = mysqli_fetch_assoc($res);
    }
}


$status = isset($_GET['status']) ? $_GET['status'] : 'all';
$q = isset($_GET['q']) ? trim($_GET['q']) : '';

$where = [];
if ($status === 'male') {
    $where[] = "child_gender = 'Male'";
} elseif ($status === 'female') {
    $where[] = "child_gender = 'Female'";
}
if ($q !== '') {
    $safe_q = mysqli_real_escape_string($conn, $q);
    $where[] = "(child_id LIKE '%{$safe_q}%' OR child_name LIKE '%{$safe_q}%' OR father_name LIKE '%{$safe_q}%' OR mother_name LIKE '%{$safe_q}%')";
}

$where_sql = '';
if (count($where) > 0) $where_sql = 'WHERE ' . implode(' AND ', $where);

$sql = "SELECT * FROM childrens {$where_sql} ORDER BY child_id DESC";
$result = mysqli_query($conn, $sql);
?>
    <style>
        /* Status Badges */
        .badge-blood { background-color: #74070722; color: #ff0000ff; } /* Yellow */

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
                    <h2 class="text-slate-800 font-bold text-lg pl-2">Registered Childrens</h2>
                    
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
                        <a href="childrens.php?status=all" class="px-6 py-2 <?php echo $status==='all' ? 'bg-darkblue text-white' : 'bg-white text-gray-500 hover:text-primary hover:bg-gray-50'; ?> rounded-lg text-sm font-bold transition whitespace-nowrap">All Childs</a>
                        <a href="childrens.php?status=male" class="px-6 py-2 <?php echo $status==='male' ? 'bg-darkblue text-white' : 'bg-white text-gray-500 hover:text-primary hover:bg-gray-50'; ?> rounded-lg text-sm font-bold transition whitespace-nowrap">Male</a>
                        <a href="childrens.php?status=female" class="px-6 py-2 <?php echo $status==='female' ? 'bg-darkblue text-white' : 'bg-white text-gray-500 hover:text-primary hover:bg-gray-50'; ?> rounded-lg text-sm font-bold transition whitespace-nowrap">Female</a>
                    </div>

                    <!-- Search & Filter -->
                    <form method="GET" action="childrens.php" class="flex flex-col sm:flex-row gap-3 w-full xl:w-auto">
                        <input type="hidden" name="status" value="<?php echo htmlspecialchars($status); ?>">
                        <div class="relative w-full sm:w-64">
                            <input type="text" name="q" value="<?php echo htmlspecialchars($q); ?>" placeholder="Search by name or parent name..." class="w-full pl-10 pr-4 py-2.5 bg-white border border-gray-200 rounded-xl shadow-sm focus:outline-none focus:border-primary transition text-sm">
                            <i data-lucide="search" class="absolute left-3 top-3 w-4 h-4 text-gray-400"></i>
                        </div>
                        <button type="submit" name="search" class="px-4 py-2.5 bg-darkblue text-white rounded-xl text-sm font-bold hover:bg-blue-800 transition shadow-md flex items-center justify-center gap-2 whitespace-nowrap">
                            <i data-lucide="Search" class="w-4 h-4"></i> Search
                        </button>
                    </form>
                </div>

                <?php if ($message){ ?>
                    <div class="p-4">
                        <div class="<?php echo $messageType == 'success' ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700'; ?> rounded-xl p-3 text-sm font-semibold">
                            <?php echo htmlspecialchars($message); ?>
                        </div>
                    </div>
                <?php }; ?>

                <!-- Children Table -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="overflow-x-auto min-h-[300px]">
                        <table class="w-full text-left text-sm text-gray-600">
                            <thead class="bg-gray-50 text-gray-800 font-bold uppercase text-xs tracking-wider">
                                <tr>
                                    <th class="px-6 py-4">Child Name</th>
                                    <th class="px-6 py-4">Parent Name</th>
                                    <th class="px-6 py-4">Age / DOB</th>
                                    <th class="px-6 py-4">Gender</th>
                                    <th class="px-6 py-4">Blood</th>
                                    <th class="px-6 py-4 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <?php
                                if ($result && mysqli_num_rows($result) > 0) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                         // Age Calculation
                                        $dob = new DateTime($row['child_dob']);
                                        $now = new DateTime();
                                        $diff = $now->diff($dob);
                                        $age = ($diff->y > 0) ? $diff->y . " Years" : $diff->m . " Months";
                                ?>
                                <tr class="hover:bg-blue-50/50 transition group">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <?php if($row['child_gender'] === 'male'){ 
                                                $bgClass = 'bg-primary/40 text-primary';
                                            } elseif($row['child_gender'] === 'female'){ 
                                                $bgClass = 'bg-pink-500/40 text-pink-500';
                                            }?>
                                            <div class="w-9 h-9 rounded-full <?php echo $bgClass ?> flex items-center justify-center font-bold text-xs"><i data-lucide="baby" class="w-4 h-4"></i></div>
                                            <div>
                                                <div class="font-bold text-slate-800"><?php echo htmlspecialchars($row['child_name']); ?></div>
                                                <div class="text-[10px] text-gray-400">ID: #CH-<?php echo htmlspecialchars($row['child_id']); ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-slate-700 font-medium"><?php echo htmlspecialchars($row['father_name'] . ' / ' . $row['mother_name']); ?></td>
                                    <td class="px-6 py-4">
                                        <div class="text-xs font-medium"><?php echo htmlspecialchars($row['child_dob']); ?></div>
                                        <div class="text-xs font-medium"><?php echo htmlspecialchars($age); ?></div>
                                    </td>
                                    <td class="px-6 py-4 text-xs"><?php echo htmlspecialchars($row['child_gender']); ?></td>
                                    <td class="px-6 py-4"><span class="flex items-center w-16 gap-2 badge-blood px-3 py-1.5 rounded-full text-xs font-bold"><i data-lucide="droplets" class="w-4 h-4"></i> <?php echo htmlspecialchars($row['child_bloodg']); ?></span></td>
                                    <td class="px-6 py-4 text-center relative">
                                        <button onclick="toggleDropdown(this)" class="p-2 hover:bg-gray-100 rounded-lg text-gray-500 transition focus:outline-none">
                                            <i data-lucide="more-vertical" class="w-4 h-4"></i>
                                        </button>
                                        <div class="dropdown-menu absolute right-8 top-8 w-40 bg-white rounded-xl shadow-xl border border-gray-100 z-10 overflow-hidden">
                                            <a href="child_view.php?id=<?php echo $row['child_id']; ?>" class="block px-4 py-2 text-left text-xs font-medium text-gray-700 hover:bg-gray-50 hover:text-primary flex items-center gap-2">
                                                <i data-lucide="View" class="w-3 h-3"></i> View
                                            </a>
                                            <a href="?action=delete&id=<?php echo $row['child_id']; ?>" class="block px-4 py-2 text-left text-xs font-medium text-red-500 hover:bg-red-50 flex items-center gap-2">
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
                                        <div class="font-bold">No children found.</div>
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
            // Toggle Dropdown
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