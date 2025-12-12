<?php
include 'admin_partials/inc_header.php';

// --- Authentication Check ---
if (!isset($_SESSION['isLogin'])) {
    header('Location: ../login.php');
    exit;
} elseif ($user_role != 1 && $user_role != 2) {
    header('Location: ../index.php');
    exit;
}

// --- 2. Handle Approve/Reject Actions ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_booking'])) {
    $schedule_id = $_POST['schedule_id'];
    $new_status = $_POST['status']; // 'approved' or 'cancelled'

    $update_sql = "UPDATE vaccination_schedules SET status = '$new_status' WHERE schedule_id = '$schedule_id'";
    mysqli_query($conn, $update_sql);
    // Page refresh to show changes
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// --- 3. Fetch Counts for Stats Cards ---
$parents_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM parents"))['count'];
$hospitals_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM hospitals"))['count'];
$children_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM childrens"))['count'];
$pending_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM vaccination_schedules WHERE status = 'pending'"))['count'];

// --- 4. Fetch Recent Pending Requests ---
                $recent_sql = "SELECT vs.*, c.child_name, c.child_dob, v.vaccine_name, h.hospital_name 
                FROM vaccination_schedules vs
                JOIN childrens c ON vs.child_id = c.child_id
                JOIN vaccines v ON vs.vaccine_id = v.vaccine_id
                JOIN hospitals h ON vs.hospital_id = h.hospital_id
                WHERE vs.status = 'pending' 
                ORDER BY vs.scheduled_date ASC ";
                $recent_result = mysqli_query($conn, $recent_sql);

// --- 5. Fetch All appointments for Table ---
            $all_sql = "SELECT vs.*, c.child_name, v.vaccine_name, h.hospital_name 
            FROM vaccination_schedules vs
            JOIN childrens c ON vs.child_id = c.child_id
            JOIN vaccines v ON vs.vaccine_id = v.vaccine_id
            JOIN hospitals h ON vs.hospital_id = h.hospital_id
            ORDER BY vs.schedule_id DESC";
            $all_result = mysqli_query($conn, $all_sql);

?>
<main class="relative lg:ml-64">

    <div class="sticky top-6 z-30 px-6 lg:px-10 mb-8 hidden lg:block">
        <div class="bg-white/80 backdrop-blur-xl border border-white/60 rounded-full px-6 py-3 shadow-sm flex justify-between items-center">
            <h2 class="text-slate-800 font-bold text-lg pl-2">Dashboard Overview</h2>

            <div class="flex items-center gap-6 pr-1">
                <button class="relative text-slate-500 hover:text-primary transition">
                    <i data-lucide="bell" class="w-5 h-5"></i>
                    <?php if ($pending_count > 0): ?>
                        <span class="absolute -top-1 -right-1 w-2.5 h-2.5 bg-red-500 rounded-full border-2 border-white"></span>
                    <?php endif; ?>
                </button>

                <div class="flex items-center gap-3 pl-6 border-l border-gray-200">
                    <div class="text-right hidden md:block">
                        <div class="text-xs font-bold text-slate-800"><?php echo $_SESSION['user_name']; ?></div>
                        <div class="text-[10px] text-slate-500 uppercase"><?php echo $_SESSION['admin_name']; ?></div>
                    </div>
                    <div class="w-9 h-9 rounded-full bg-darkblue text-white flex items-center justify-center font-bold text-sm shadow-md">
                        AD
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="px-6 lg:px-10 pb-10 space-y-8 fade-in">

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
            <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100 flex items-center gap-4 hover:shadow-md transition">
                <div class="w-14 h-14 rounded-2xl bg-blue-50 text-primary flex items-center justify-center">
                    <i data-lucide="users" class="w-7 h-7"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-400 font-medium">Parents</p>
                    <h3 class="text-2xl font-bold text-slate-800"><?php echo $parents_count; ?></h3>
                </div>
            </div>

            <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100 flex items-center gap-4 hover:shadow-md transition">
                <div class="w-14 h-14 rounded-2xl bg-purple-50 text-purple-500 flex items-center justify-center">
                    <i data-lucide="hospital" class="w-7 h-7"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-400 font-medium">Hospitals</p>
                    <h3 class="text-2xl font-bold text-slate-800"><?php echo $hospitals_count; ?></h3>
                </div>
            </div>

            <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100 flex items-center gap-4 hover:shadow-md transition">
                <div class="w-14 h-14 rounded-2xl bg-green-50 text-green-500 flex items-center justify-center">
                    <i data-lucide="check-circle" class="w-7 h-7"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-400 font-medium">Childrens</p>
                    <h3 class="text-2xl font-bold text-slate-800"><?php echo $children_count; ?></h3>
                </div>
            </div>

            <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100 flex items-center gap-4 hover:shadow-md transition">
                <div class="w-14 h-14 rounded-2xl bg-orange-50 text-orange-500 flex items-center justify-center">
                    <i data-lucide="calendar-clock" class="w-7 h-7"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-400 font-medium">Pending Requests</p>
                    <h3 class="text-2xl font-bold text-slate-800"><?php echo $pending_count; ?></h3>
                </div>
            </div>
        </div>

        <div>
            <h3 class="text-xl font-bold text-slate-800 mb-4 flex items-center gap-2">
                <span class="w-2 h-6 bg-primary rounded-full"></span>
                Recent Booking Requests
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                <?php
                if (mysqli_num_rows($recent_result) > 0):
                    while ($row = mysqli_fetch_assoc($recent_result)):
                        // Age Calculation
                        $dob = new DateTime($row['child_dob']);
                        $now = new DateTime();
                        $diff = $now->diff($dob);
                        $age = ($diff->y > 0) ? $diff->y . " Years" : $diff->m . " Months";

                        // Date Format
                        $date_display = date("d M, h:i A", strtotime($row['scheduled_date'] . ' ' . $row['scheduled_time']));

                        // Initials
                        $initials = strtoupper(substr($row['child_name'], 0, 2));
                ?>
                        <div class="bg-white p-5 rounded-[1.5rem] border border-gray-100 shadow-sm relative overflow-hidden">
                            <div class="absolute top-0 left-0 w-1 h-full bg-orange-400"></div>
                            <div class="flex justify-between items-start mb-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center font-bold text-slate-600">
                                        <?php echo $initials; ?>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-sm text-slate-800"><?php echo $row['child_name']; ?></h4>
                                        <p class="text-xs text-gray-400">Age: <?php echo $age; ?></p>
                                    </div>
                                </div>
                                <span class="badge-pending text-[10px] font-bold px-2 py-1 rounded-lg uppercase">Pending</span>
                            </div>
                            <div class="text-xs text-gray-500 mb-4 space-y-1">
                                <p><strong class="text-slate-700">Hospital:</strong> <?php echo $row['hospital_name']; ?></p>
                                <p><strong class="text-slate-700">Vaccine:</strong> <?php echo $row['vaccine_name']; ?></p>
                                <p><strong class="text-slate-700">Date:</strong> <?php echo $date_display; ?></p>
                            </div>
                            <div class="flex gap-2">
                                <form method="POST" class="flex-1">
                                    <input type="hidden" name="schedule_id" value="<?php echo $row['schedule_id']; ?>">
                                    <input type="hidden" name="status" value="approved">
                                    <button type="submit" name="update_booking" class="w-full py-2 bg-green-50 text-green-600 rounded-xl text-xs font-bold hover:bg-green-100 transition">Approve</button>
                                </form>
                                <form method="POST" class="flex-1">
                                    <input type="hidden" name="schedule_id" value="<?php echo $row['schedule_id']; ?>">
                                    <input type="hidden" name="status" value="cancelled">
                                    <button type="submit" name="update_booking" class="w-full py-2 bg-red-50 text-red-600 rounded-xl text-xs font-bold hover:bg-red-100 transition">Reject</button>
                                </form>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p class="text-gray-500 col-span-3 text-center py-4">No pending requests at the moment.</p>
                <?php endif; ?>

            </div>
        </div>

        <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <h3 class="text-xl font-bold text-slate-800">Booking Details</h3>

                <div class="flex gap-3">
                    <div class="relative">
                        <input type="text" placeholder="Search child name..." class="pl-10 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-primary w-full md:w-64">
                        <i data-lucide="search" class="absolute left-3 top-2.5 w-4 h-4 text-gray-400"></i>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600">
                    <thead class="bg-gray-50 text-gray-800 font-bold uppercase text-xs tracking-wider">
                        <tr>
                            <th class="px-6 py-4">Booking ID</th>
                            <th class="px-6 py-4">Child Name</th>
                            <th class="px-6 py-4">Hospital</th>
                            <th class="px-6 py-4">Vaccine</th>
                            <th class="px-6 py-4">Date</th>
                            <th class="px-6 py-4">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php
                        if (mysqli_num_rows($all_result) > 0):
                            while ($tbl = mysqli_fetch_assoc($all_result)):
                                $date_tbl = date("d M, h:i A", strtotime($tbl['scheduled_date'] . ' ' . $tbl['scheduled_time']));
                                $status = strtolower($tbl['status']);

                                // Dynamic Badge Logic
                                $badgeClass = "";
                                $badgeText = ucfirst($status);
                                if ($status == 'approved') {
                                    $badgeClass = "bg-green-100 text-green-700";
                                } elseif ($status == 'pending') {
                                    $badgeClass = "bg-orange-100 text-orange-700";
                                } elseif ($status == 'cancelled') {
                                    $badgeClass = "bg-red-100 text-red-700";
                                } else {
                                    $badgeClass = "bg-blue-100 text-blue-700"; // Completed
                                }
                        ?>
                                <tr class="hover:bg-blue-50/50 transition">
                                    <td class="px-6 py-4 font-medium text-primary">#BK-<?php echo $tbl['schedule_id']; ?></td>
                                    <td class="px-6 py-4 font-bold text-slate-800"><?php echo $tbl['child_name']; ?></td>
                                    <td class="px-6 py-4"><?php echo $tbl['hospital_name']; ?></td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 bg-purple-50 text-purple-600 rounded-md text-xs font-bold"><?php echo $tbl['vaccine_name']; ?></span>
                                    </td>
                                    <td class="px-6 py-4"><?php echo $date_tbl; ?></td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 rounded-full text-xs font-bold <?php echo $badgeClass; ?>"><?php echo $badgeText; ?></span>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center py-4">No bookings found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</main>

<?php include 'admin_partials/inc_footer.php'; ?>