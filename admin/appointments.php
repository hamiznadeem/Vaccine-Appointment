<?php
ob_start();
include 'admin_partials/inc_header.php';

// messages
$message = '';
$messageType = '';

// Handle POST: approve/reject/cancel
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['approve_appointment'])) {
        $id = intval($_POST['schedule_id'] ?? 0);
        if ($id > 0) {
            $sql = "UPDATE schedule SET status='approved' WHERE schedule_id={$id} LIMIT 1";
            if (mysqli_query($conn, $sql)) {
                $message = 'Appointment approved successfully.';
                $messageType = 'success';
            } else {
                $message = 'DB Error: ' . mysqli_error($conn);
                $messageType = 'error';
            }
        }
        header('Location:' . $_SERVER['PHP_SELF']);
        exit;
    } elseif (isset($_POST['reject_appointment'])) {
        $id = intval($_POST['schedule_id'] ?? 0);
        if ($id > 0) {
            $sql = "UPDATE vaccination_schedules SET status='cancelled' WHERE schedule_id={$id} LIMIT 1";
            if (mysqli_query($conn, $sql)) {
                $message = 'Appointment rejected.';
                $messageType = 'success';
            } else {
                $message = 'DB Error: ' . mysqli_error($conn);
                $messageType = 'error';
            }
        }
        header('Location:' . $_SERVER['PHP_SELF']);
        exit;
    } elseif (isset($_POST['cancel_appointment'])) {
        $id = intval($_POST['schedule_id'] ?? 0);
        if ($id > 0) {
            $sql = "UPDATE vaccination_schedules SET status='cancelled' WHERE schedule_id={$id} LIMIT 1";
            if (mysqli_query($conn, $sql)) {
                $message = 'Appointment cancelled.';
                $messageType = 'success';
            } else {
                $message = 'DB Error: ' . mysqli_error($conn);
                $messageType = 'error';
            }
        }
        header('Location:' . $_SERVER['PHP_SELF']);
        exit;
    } elseif (isset($_POST['remove_appointment'])) {
        $id = intval($_POST['schedule_id'] ?? 0);
        if ($id > 0) {
            $sql = "DELETE FROM vaccination_schedules WHERE schedule_id={$id}";
            if (mysqli_query($conn, $sql)) {
                $message = 'Appointment Removed.';
                $messageType = 'success';
            } else {
                $message = 'DB Error: ' . mysqli_error($conn);
                $messageType = 'error';
            }
        }
        header('Location:' . $_SERVER['PHP_SELF']);
        exit;
    }
}

// Get filter parameters
$status = isset($_GET['status']) ? $_GET['status'] : 'all';
$q = isset($_GET['q']) ? trim($_GET['q']) : '';

// Build WHERE clause
$where = [];
if ($status !== 'all') {
    $safe_status = mysqli_real_escape_string($conn, $status);
    $where[] = "s.status = '{$safe_status}'";
}
if ($q !== '') {
    $safe_q = mysqli_real_escape_string($conn, $q);
    $where[] = "(s.schedule_id LIKE '%{$safe_q}%' OR c.child_name LIKE '%{$safe_q}%' OR p.parent_fname LIKE '%{$safe_q}%' OR h.hospital_name LIKE '%{$safe_q}%')";
}

$where_sql = '';
if (count($where) > 0) $where_sql = 'WHERE ' . implode(' AND ', $where);

// Query with JOINs to get all related data
$sql = "SELECT s.*, c.child_name, p.fname, h.hospital_name, v.vaccine_name 
        FROM vaccination_schedules s
        LEFT JOIN childrens c ON s.child_id = c.child_id
        LEFT JOIN parents p ON s.parent_id = p.parent_id
        LEFT JOIN hospitals h ON s.hospital_id = h.hospital_id
        LEFT JOIN vaccines v ON s.vaccine_id = v.vaccine_id
        {$where_sql}
        ORDER BY s.schedule_id DESC";

$result = mysqli_query($conn, $sql);
?>
<style>
     /* Status Badges */
        .badge-pending { background-color: #FEF3C7; color: #D97706; } /* Yellow */
        .badge-approved { background-color: #D1FAE5; color: #059669; } /* Green */
        .badge-completed { background-color: #D1FAE5; color: #059669; } /* Green */
        .badge-cancelled { background-color: #FEE2E2; color: #DC2626; } /* Red */

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
                    <h2 class="text-slate-800 font-bold text-lg pl-2">Appointments</h2>
                    
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
                <div class="flex flex-col  xl:flex-row justify-between items-start xl:items-center gap-6">
                    
                    <!-- Tabs -->
                    <div class="bg-white p-1 no-scrollbar rounded-xl border border-gray-200 flex overflow-x-auto w-full xl:w-auto">
                        <a href="appointments.php?status=all" class="px-6 py-2 <?php echo $status==='all' ? 'bg-darkblue text-white' : 'bg-white text-gray-500 hover:text-primary hover:bg-gray-50'; ?> rounded-lg text-sm font-bold transition whitespace-nowrap">All Bookings</a>
                        <a href="appointments.php?status=pending" class="px-6 py-2 <?php echo $status==='pending' ? 'bg-darkblue text-white' : 'bg-white text-gray-500 hover:text-primary hover:bg-gray-50'; ?> rounded-lg text-sm font-bold transition whitespace-nowrap">Pending</a>
                        <a href="appointments.php?status=approved" class="px-6 py-2 <?php echo $status==='approved' ? 'bg-darkblue text-white' : 'bg-white text-gray-500 hover:text-primary hover:bg-gray-50'; ?> rounded-lg text-sm font-bold transition whitespace-nowrap">Approved</a>
                        <a href="appointments.php?status=cancelled" class="px-6 py-2 <?php echo $status==='cancelled' ? 'bg-darkblue text-white' : 'bg-white text-gray-500 hover:text-primary hover:bg-gray-50'; ?> rounded-lg text-sm font-bold transition whitespace-nowrap">Cancelled</a>
                    </div>

                    <!-- Search & Filter -->
                    <form method="GET" action="appointments.php" class="flex flex-col sm:flex-row gap-3 w-full xl:w-auto">
                        <input type="hidden" name="status" value="<?php echo htmlspecialchars($status); ?>">
                        <div class="relative w-full sm:w-64">
                            <input type="text" name="q" value="<?php echo htmlspecialchars($q); ?>" placeholder="Search booking ID or name..." class="w-full pl-10 pr-4 py-2.5 bg-white border border-gray-200 rounded-xl shadow-sm focus:outline-none focus:border-primary transition text-sm">
                            <i data-lucide="search" class="absolute left-3 top-3 w-4 h-4 text-gray-400"></i>
                        </div>
                        <button type="submit" class="px-4 py-2.5 bg-darkblue text-white rounded-xl text-sm font-bold hover:bg-blue-800 transition shadow-md flex items-center justify-center gap-2 whitespace-nowrap">
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

                <!-- Bookings Table -->
                <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-gray-600">
                            <thead class="bg-gray-50 text-gray-800 font-bold uppercase text-xs tracking-wider">
                                <tr>
                                    <th class="px-6 py-4">ID</th>
                                    <th class="px-6 py-4">Child Name</th>
                                    <th class="px-6 py-4">Parent</th>
                                    <th class="px-6 py-4">Hospital</th>
                                    <th class="px-6 py-4">Vaccine</th>
                                    <th class="px-6 py-4">Date & Time</th>
                                    <th class="px-6 py-4">Status</th>
                                    <th class="px-6 py-4 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <?php
                                if ($result && mysqli_num_rows($result) > 0) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $apt_status = $row['status'];
                                        $badgeClass = 'badge-' . $apt_status;
                                        $initials = strtoupper(substr($row['child_name'] ?? 'N/A', 0, 2));
                                ?>
                                <tr class="hover:bg-blue-50/50 transition group <?php echo ($apt_status === 'cancelled') ? 'opacity-75 bg-red-50/10' : ''; ?>">
                                    <td class="px-4 py-4 font-medium text-xs text-primary">#VS-<?php echo htmlspecialchars($row['schedule_id']); ?></td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center font-bold text-xs"><?php echo $initials; ?></div>
                                            <span class="font-bold text-xs text-slate-800"><?php echo htmlspecialchars($row['child_name'] ?? 'N/A'); ?></span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-xs"><?php echo htmlspecialchars($row['fname'] ?? 'N/A'); ?></td>
                                    <td class="px-6 py-4 text-xs"><?php echo htmlspecialchars($row['hospital_name'] ?? 'N/A'); ?></td>
                                    <td class="px-6 py-4"><span class="px-2 py-1 bg-purple-50 text-purple-600 rounded-md text-xs font-bold"><?php echo htmlspecialchars($row['vaccine_name'] ?? 'N/A'); ?></span></td>
                                    <td class="px-6 py-4 text-xs font-medium"><?php echo htmlspecialchars($row['scheduled_date'] . ' ' . $row['scheduled_time']); ?></td>
                                    <td class="px-6 py-4"><span class="<?php echo $badgeClass; ?> px-3 py-1 rounded-full text-[.6rem] font-bold capitalize"><?php echo htmlspecialchars($apt_status); ?></span></td>
                                    <td class="px-6 py-4 text-center relative">
                                        <button onclick="toggleDropdown(this)" class="p-2 hover:bg-gray-100 rounded-lg text-gray-500 transition focus:outline-none">
                                            <i data-lucide="more-vertical" class="w-4 h-4"></i>
                                        </button>
                                        <div class="dropdown-menu absolute right-8 top-8 w-40 bg-white rounded-xl shadow-xl border border-gray-100 z-10 overflow-hidden">
                                            <?php if ($apt_status === 'pending') { ?>
                                                <form method="POST" class="inline">
                                                    <input type="hidden" name="schedule_id" value="<?php echo $row['schedule_id']; ?>">
                                                    <button type="submit" name="approve_appointment" class="w-full block px-4 py-2 text-left text-xs font-medium text-green-600 hover:bg-green-50 flex items-center gap-2">
                                                        <i data-lucide="check" class="w-3 h-3"></i> Approve
                                                    </button>
                                                </form>
                                                <form method="POST" class="inline">
                                                    <input type="hidden" name="schedule_id" value="<?php echo $row['schedule_id']; ?>">
                                                    <button type="submit" name="reject_appointment" class="w-full block px-4 py-2 text-left text-xs font-medium text-red-500 hover:bg-red-50 flex items-center gap-2">
                                                        <i data-lucide="x" class="w-3 h-3"></i> Reject
                                                    </button>
                                                </form>
                                            <?php } elseif ($apt_status === 'approved') { ?>
                                                <form method="POST" class="inline">
                                                    <input type="hidden" name="schedule_id" value="<?php echo $row['schedule_id']; ?>">
                                                    <button type="submit" name="cancel_appointment" class="w-full block px-4 py-2 text-left text-xs font-medium text-red-500 hover:bg-red-50 flex items-center gap-2" >
                                                        <i data-lucide="x-circle" class="w-3 h-3"></i> Cancel
                                                    </button>
                                                </form>
                                            <?php } elseif ($apt_status === 'cancelled'|| $apt_status === 'completed') { ?>
                                                <form method="POST" class="inline">
                                                    <input type="hidden" name="schedule_id" value="<?php echo $row['schedule_id']; ?>">
                                                    <button type="submit" name="remove_appointment" class="w-full block px-4 py-2 text-left text-xs font-medium text-red-500 hover:bg-red-50 flex items-center gap-2" >
                                                        <i data-lucide="trash-2" class="w-3 h-3"></i> Removed
                                                    </button>
                                                </form>
                                            <?php } ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php
                                    }
                                } else {
                                ?>
                                <tr>
                                    <td colspan="8" class="px-6 py-12 text-center text-gray-400">
                                        <i data-lucide="calendar" class="w-10 h-10 mx-auto mb-4"></i>
                                        <div class="font-bold">No appointments found.</div>
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