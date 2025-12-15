<?php
include 'admin_partials/inc_header.php';

if ($user_role == 2 ) {
    header('Location: dashboard.php');
    exit;
}



$message = '';

// --- 2. Handle Actions (Block, Unblock, Delete) ---
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action'])) {
    $target_id = $_GET['id'];
    $action = $_GET['action'];

        if ($action == 'block') {
            $sql = "UPDATE admins SET isActive = 0 WHERE admin_id = '$target_id'";
            mysqli_query($conn, $sql);
        } elseif ($action == 'unblock') {
            $sql = "UPDATE admins SET isActive = 1 WHERE admin_id = '$target_id'";
            mysqli_query($conn, $sql);
        } elseif ($action == 'delete') {
            $sql = "DELETE FROM admins WHERE admin_id = '$target_id'";
            mysqli_query($conn, $sql);
        }
        // Refresh to show changes
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }

// --- 3. Search Logic ---
$search_query = "";
$search_sql = "";
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $search_query = $search;
    $search_sql = " AND (username LIKE '%$search%' OR admin_name LIKE '%$search%')";
}

// --- 4. Fetch Stats ---
$total_admins = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM admins"))['count'];
$active_admins = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM admins WHERE isActive = true "))['count'];

// --- 5. Fetch Table Data ---
$sql = "SELECT * FROM admins WHERE 1=1 $search_sql ORDER BY admin_id ASC";
$result = mysqli_query($conn, $sql);

?>

<style>
    /* Status Badges */
    .badge-active { background-color: #D1FAE5; color: #059669; } /* Green */
    .badge-blocked { background-color: #FEE2E2; color: #DC2626; } /* Red */
    
    /* Role Badges */
    .role-super { background-color: #F3E8FF; color: #7E22CE; border: 1px solid #E9D5FF; } /* Purple */
    .role-manager { background-color: #DBEAFE; color: #1D4ED8; border: 1px solid #BFDBFE; } /* Blue */

    /* Dropdown Animation */
    .dropdown-menu {
        transform-origin: top right;
        transition: transform 0.1s ease-out, opacity 0.1s ease-out;
        transform: scale(0.95);
        opacity: 0;
        pointer-events: none;
        display: none; /* Hidden by default */
    }
    .dropdown-menu.show {
        transform: scale(1);
        opacity: 1;
        pointer-events: auto;
        display: block; /* Show when active */
    }
</style>

<main class="relative lg:ml-64 pt-6 lg:pt-10 pb-10 min-h-screen bg-slate-50">
        
    <div class="sticky top-6 z-30 px-6 lg:px-10 mb-8 hidden lg:block">
        <div class="bg-white/80 backdrop-blur-xl border border-white/60 rounded-full px-6 py-3 shadow-sm flex justify-between items-center">
            <h2 class="text-slate-800 font-bold text-lg pl-2">Admin Users</h2>
            
            <div class="flex items-center gap-6 pr-1">
                
                
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
        
        <div class="flex flex-col xl:flex-row justify-between items-start xl:items-center gap-6">
            
            <div class="flex gap-4">
                <div class="bg-white px-5 py-2 rounded-xl border border-gray-200 shadow-sm flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center"><i data-lucide="users" class="w-4 h-4"></i></div>
                    <div>
                        <p class="text-[10px] text-gray-400 font-bold uppercase">Total Admins</p>
                        <p class="text-sm font-bold text-slate-800"><?php echo $total_admins; ?></p>
                    </div>
                </div>
                <div class="bg-white px-5 py-2 rounded-xl border border-gray-200 shadow-sm flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-green-50 text-green-600 flex items-center justify-center"><i data-lucide="check-circle" class="w-4 h-4"></i></div>
                    <div>
                        <p class="text-[10px] text-gray-400 font-bold uppercase">Active</p>
                        <p class="text-sm font-bold text-slate-800"><?php echo $active_admins; ?></p>
                    </div>
                </div>
            </div>

            <form method="GET" class="flex flex-col sm:flex-row gap-3 w-full xl:w-auto">
                <div class="relative w-full sm:w-64">
                    <input type="text" name="search" value="<?php echo $search_query; ?>" placeholder="Search admin..." class="w-full pl-10 pr-4 py-2.5 bg-white border border-gray-200 rounded-xl shadow-sm focus:outline-none focus:border-primary transition text-sm">
                    <i data-lucide="search" class="absolute left-3 top-3 w-4 h-4 text-gray-400"></i>
                </div>
                <a href="add_admins.php" class="px-6 py-2.5 bg-primary text-white rounded-xl text-sm font-bold shadow-lg shadow-blue-900/20 hover:bg-cyan-500 transition flex items-center justify-center gap-2 whitespace-nowrap">
                    <i data-lucide="user-plus" class="w-4 h-4"></i> Add New Admin
                </a>
            </form>
        </div>

        <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-visible">
            <div class="overflow-x-auto overflow-y-visible">
                <table class="w-full text-left text-sm text-gray-600">
                    <thead class="bg-gray-50 text-gray-800 font-bold uppercase text-xs tracking-wider">
                        <tr>
                            <th class="px-6 py-4">Name</th>
                            <th class="px-6 py-4">Email</th>
                            <th class="px-6 py-4">Role</th>
                            <th class="px-6 py-4">Created</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        
                        <?php if (mysqli_num_rows($result) > 0) : ?>
                            <?php while ($row = mysqli_fetch_assoc($result)) : 
                                // Logic for Visuals
                                $initials = strtoupper(substr($row['username'], 0, 2));
                                $status = $row['isActive']; // active or blocked
                                
                                // Role Logic ( 1=Super Admin, 2=Admin)
                                $roleName = ($row['role_id'] == 1) ? 'Super Admin' : 'ADMIN';
                                $roleClass = ($row['role_id'] == 1) ? 'role-super' : 'ADMIN';
                                
                                // Status Colors
                                $statusClass = ($status == true) ? 'badge-active' : 'badge-blocked';
                                $rowBg = ($status == false) ? 'bg-red-50/20' : '';
                                
                                // Avatar Color
                                $avatarBg = ($status == false) ? 'bg-red-100 text-red-600' : 'bg-blue-100 text-blue-600';
                                if($row['role_id'] == 1) $avatarBg = 'bg-darkblue text-white';
                            ?>

                            <tr class="hover:bg-blue-50/50 transition group <?php echo $rowBg; ?>">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-full <?php echo $avatarBg; ?> flex items-center justify-center font-bold text-xs">
                                            <?php echo $initials; ?>
                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-800"><?php echo $row['username']; ?></div>
                                            <div class="text-[10px] text-gray-400"><?php echo $row['admin_name']; ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-xs font-mono"><?php echo $row['email']; ?></td>
                                <td class="px-6 py-4">
                                    <span class="<?php echo $roleClass; ?> px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wide">
                                        <?php echo $roleName; ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-xs">
                                    <?php echo date('d M Y', strtotime($row['created_at'] ?? 'now')); ?>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="<?php echo $statusClass; ?> px-3 py-1 rounded-full text-xs font-bold"><?php echo $status ? 'Active' : 'Blocked'; ?></span>
                                </td>
                                <td class="px-6 py-4 text-center relative">
                                    <?php if ($row['role_id'] == 1) : ?>
                                        <button disabled class="p-2 text-gray-300 cursor-not-allowed" title="You cannot edit yourself">
                                            <i data-lucide="lock" class="w-4 h-4"></i>
                                        </button>
                                    <?php else : ?>
                                        <div class="relative inline-block text-left">
                                            <button onclick="toggleDropdown(this)" class="p-2 hover:bg-gray-100 rounded-lg text-gray-500 transition focus:outline-none">
                                                <i data-lucide="more-vertical" class="w-4 h-4"></i>
                                            </button>
                                            
                                            <div class="dropdown-menu absolute right-0 top-full mt-2 w-36 bg-white rounded-xl shadow-xl border border-gray-100 z-50">
                                                <?php if($status): ?>
                                                    <a  href="admins.php?action=block&id=<?php echo $row['admin_id']?>" class="w-full px-4 py-2 text-left text-xs font-medium text-red-500 hover:bg-red-50 flex items-center gap-2">
                                                        <i data-lucide="ban" class="w-3 h-3"></i> Block
                                                    </a>
                                                <?php else: ?>
                                                    <a href="admins.php?action=unblock&id=<?php echo $row['admin_id']?>" class="w-full px-4 py-2 text-left text-xs font-medium text-green-500 hover:bg-green-50 flex items-center gap-2">
                                                        <i data-lucide="check-circle" class="w-3 h-3"></i> Unblock
                                                    </a>
                                                <?php endif; ?>
                                                <a href="admins.php?action=delete&id=<?php echo $row['admin_id']?>" class="w-full px-4 py-2 text-left text-xs font-medium text-red-500 hover:bg-red-50 flex items-center gap-2">
                                                    <i data-lucide="trash-2" class="w-3 h-3"></i> Remove
                                                </a>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-400">
                                    No admin users found.
                                </td>
                            </tr>
                        <?php endif; ?>

                    </tbody>
                </table>
            </div>
        </div>

    </div>
</main>

<script>
    function toggleDropdown(button) {
        // Close all other dropdowns first
        const allDropdowns = document.querySelectorAll('.dropdown-menu');
        const currentDropdown = button.nextElementSibling;

        allDropdowns.forEach(menu => {
            if (menu !== currentDropdown) {
                menu.classList.remove('show');
            }
        });

        // Toggle the clicked dropdown
        currentDropdown.classList.toggle('show');
    }

    // Close dropdowns when clicking outside
    window.onclick = function(event) {
        if (!event.target.closest('.dropdown-menu') && !event.target.closest('button')) {
            const dropdowns = document.getElementsByClassName("dropdown-menu");
            for (let i = 0; i < dropdowns.length; i++) {
                dropdowns[i].classList.remove('show');
            }
        }
    }
</script>

<?php        
include 'admin_partials/inc_footer.php';
ob_end_flush();
?>