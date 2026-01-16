<?php
$title = 'Appointments';
include 'partials/inc_header.php';

if (!isset($_SESSION['isLogin'])) {
    header('Location: login.php');
    exit;
} elseif ($user_role != 4) {
    header('Location: index.php');
    exit;
}

$parent_id = $_SESSION['user_id'];

$sql = "SELECT 
            vs.schedule_id, 
            vs.scheduled_date, 
            vs.scheduled_time, 
            vs.status, 
            vs.report, 
            c.child_name, 
            c.child_dob, 
            c.child_gender,
            v.vaccine_name, 
            v.doses, 
            h.hospital_name, 
            h.hospital_address
        FROM vaccination_schedules vs
        JOIN childrens c ON vs.child_id = c.child_id
        JOIN vaccines v ON vs.vaccine_id = v.vaccine_id
        JOIN hospitals h ON vs.hospital_id = h.hospital_id
        WHERE vs.parent_id = '$parent_id'
        ORDER BY vs.scheduled_date ASC, vs.scheduled_time ASC";

$result = mysqli_query($conn, $sql);

$upcoming_appointments = [];
$past_appointments = [];
$today = date('Y-m-d');

// --- 2. Process & Categorize Data ---
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        
        // Calculate Child's Age dynamically
        $dob = new DateTime($row['child_dob']);
        $now = new DateTime();
        $interval = $now->diff($dob);
        if ($interval->y > 0) {
            $row['age_display'] = $interval->y . ' Years';
        } else {
            $row['age_display'] = $interval->m . ' Months';
        }

        // Logic: If status is 'completed'/'cancelled' OR date is in the past -> History
        // Logic: If status is 'pending'/'approved' AND date is future/today -> Upcoming
        $status = strtolower($row['status']);
        
        if ($status == 'completed' || $status == 'cancelled' || $row['scheduled_date'] < $today) {
            $past_appointments[] = $row;
        } else {
            $upcoming_appointments[] = $row;
        }
    }
}
?>

<div class="pt-32 pb-20 px-6 md:px-20 max-w-[1400px] mx-auto flex-grow w-full fade-in">

    <div class="flex items-center gap-2 text-sm text-gray-400 mb-6">
        <a href="index.php" class="hover:text-primary transition">Home</a>
        <i data-lucide="chevron-right" class="w-4 h-4"></i>
        <span class="text-gray-800 font-medium">Appointments</span>
    </div>

    <div class="flex flex-col md:flex-row justify-between items-end gap-6 mb-10">
        <div>
            <span class="text-primary font-bold text-xs tracking-widest uppercase mb-2 block">Schedule</span>
            <h1 class="text-3xl md:text-5xl font-bold text-darkblue leading-tight">My <span class="text-primary">Appointments</span></h1>
            <p class="text-gray-500 mt-2 text-sm max-w-lg">Manage your upcoming vaccination slots and view history.</p>
        </div>

        <a href="parents_appointment.php" class="px-6 py-3 bg-darkblue text-white rounded-xl font-bold shadow-lg shadow-blue-900/20 hover:bg-cyan-500 transition flex items-center gap-2 text-sm group">
            <i data-lucide="plus" class="w-4 h-4 group-hover:rotate-90 transition duration-300"></i> Book New Slot
        </a>
    </div>

    <div class="space-y-6">

        <?php if (!empty($upcoming_appointments)) : ?>
            <?php foreach ($upcoming_appointments as $appt) : 
                // Format Date & Time
                $dateObj = DateTime::createFromFormat('Y-m-d', $appt['scheduled_date']);
                $month = $dateObj->format('M'); // e.g., Nov
                $day = $dateObj->format('d');   // e.g., 12
                $time = date('h:i A', strtotime($appt['scheduled_time'])); // e.g., 10:00 AM
                
                $status = strtolower($appt['status']); // pending, approved
                
                // UI Logic based on DB Status
                if ($status == 'approved') {
                    // Confirmed Style (Green)
                    $barColor = 'bg-green-500';
                    $dateBox = 'bg-green-50 text-green-700 border-green-100';
                    $shadowHover = 'hover:shadow-blue-500/5';
                    $statusIcon = 'check-circle';
                    $statusText = 'CONFIRMED';
                    $statusClass = 'text-green-600 bg-green-50'; 
                } else {
                    // Pending Style (Yellow/Orange)
                    $barColor = 'bg-yellow-400';
                    $dateBox = 'bg-orange-50 text-orange-600 border-orange-100';
                    $shadowHover = 'hover:shadow-yellow-500/5';
                    $statusIcon = 'clock';
                    $statusText = 'PENDING';
                    $statusClass = 'text-orange-600 bg-orange-50'; 
                }
            ?>
            <div class="bg-white rounded-[2rem] p-6 border border-gray-100 shadow-sm hover:shadow-xl <?php echo $shadowHover; ?> transition duration-300 relative overflow-hidden group">
                <div class="absolute top-0 left-0 w-1.5 h-full <?php echo $barColor; ?>"></div>

                <div class="flex flex-col lg:flex-row gap-6 lg:items-center justify-between">
                    
                    <div class="flex items-center gap-6 min-w-[220px]">
                        <div class="<?php echo $dateBox; ?> rounded-2xl p-4 text-center min-w-[80px] border">
                            <span class="block text-xs font-bold uppercase tracking-wider mb-1"><?php echo $month; ?></span>
                            <span class="block text-3xl font-bold"><?php echo $day; ?></span>
                        </div>
                        <div>
                            <div class="text-xs text-gray-400 font-bold uppercase tracking-wide mb-1">Time Slot</div>
                            <div class="text-xl font-bold text-slate-800"><?php echo $time; ?></div>
                            <div class="<?php echo $statusClass; ?> text-[10px] font-bold px-2.5 py-1 rounded-full inline-flex items-center gap-1 mt-2">
                                <i data-lucide="<?php echo $statusIcon; ?>" class="w-3 h-3"></i> <?php echo $statusText; ?>
                            </div>
                        </div>
                    </div>

                    <div class="flex-1 grid grid-cols-1 md:grid-cols-3 gap-6 border-l border-gray-100 pl-0 lg:pl-6">
                        <div>
                            <span class="text-xs text-gray-400 font-bold uppercase tracking-wide block mb-2">Child</span>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-blue-100 p-0.5 border border-blue-200 overflow-hidden">
                                    <div class="w-full h-full flex items-center justify-center text-blue-400"><i data-lucide="baby" class="w-5 h-5"></i></div>
                                </div>
                                <div>
                                    <p class="font-bold text-slate-800 text-sm"><?php echo $appt['child_name']; ?></p>
                                    <p class="text-[10px] text-gray-500 bg-gray-100 px-1.5 py-0.5 rounded inline-block mt-0.5"><?php echo $appt['age_display']; ?></p>
                                </div>
                            </div>
                        </div>
                        <div>
                            <span class="text-xs text-gray-400 font-bold uppercase tracking-wide block mb-2">Vaccine</span>
                            <p class="font-bold text-slate-800 text-sm flex items-center gap-2">
                                <span class="w-8 h-8 rounded-lg bg-blue-50 text-primary flex items-center justify-center"><i data-lucide="shield" class="w-4 h-4"></i></span>
                                <?php echo $appt['vaccine_name']; ?>
                            </p>
                            <p class="text-[10px] text-gray-500 ml-10 mt-1 truncate max-w-[150px]">Doses <?php echo $appt['doses']; ?></p>
                        </div>
                        <div>
                            <span class="text-xs text-gray-400 font-bold uppercase tracking-wide block mb-2">Hospital</span>
                            <p class="font-bold text-slate-800 text-sm flex items-center gap-2">
                                <span class="w-8 h-8 rounded-lg bg-gray-50 text-gray-500 flex items-center justify-center"><i data-lucide="map-pin" class="w-4 h-4"></i></span>
                                <?php echo $appt['hospital_name']; ?>
                            </p>
                            <p class="text-[10px] text-gray-500 ml-10 mt-1 truncate max-w-[150px]"><?php echo $appt['hospital_address']; ?></p>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3 mt-4 lg:mt-0 pt-4 lg:pt-0 border-t lg:border-t-0 border-gray-100">
                        <a href="cancel_appointment.php?id=<?php echo $appt['schedule_id']; ?>" onclick="return confirm('Are you sure you want to cancel this appointment?');" class="px-5 py-2.5 bg-white text-red-500 border border-red-100 rounded-xl text-xs font-bold hover:bg-red-50 transition flex items-center justify-center gap-2">
                            <i data-lucide="x" class="w-3 h-3"></i> Cancel
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="text-center py-12 bg-gray-50 rounded-[2rem] border border-dashed border-gray-300">
                <div class="w-16 h-16 bg-gray-100 text-gray-400 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="calendar-x" class="w-8 h-8"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-700">No Upcoming Appointments</h3>
                <p class="text-sm text-gray-500 mt-2">Book a new slot to get started.</p>
            </div>
        <?php endif; ?>


        <?php if (!empty($past_appointments)) : ?>
        <div class="pt-8">
            <h3 class="text-lg font-bold text-slate-700 mb-4 flex items-center gap-2">
                <i data-lucide="history" class="w-5 h-5 text-gray-400"></i> Past Appointments
            </h3>

            <?php foreach ($past_appointments as $appt) : 
                $dateObj = DateTime::createFromFormat('Y-m-d', $appt['scheduled_date']);
                $month = $dateObj->format('M');
                $day = $dateObj->format('d');
                $time = date('h:i A', strtotime($appt['scheduled_time']));
                $status = strtolower($appt['status']); // completed, cancelled

                // Status Icons & Colors
                if($status == 'cancelled'){
                    $icon = 'x';
                    $badgeClass = 'text-red-500 bg-red-50';
                } else {
                    $icon = 'check';
                    $badgeClass = 'text-green-600 bg-green-50';
                }
            ?>
            <div class="bg-gray-50/80 rounded-[2rem] p-6 border border-gray-200 shadow-none hover:bg-white hover:shadow-md transition duration-300 relative overflow-hidden group mb-4">
                <div class="flex flex-col lg:flex-row gap-6 lg:items-center justify-between">
                    
                    <div class="flex items-center gap-6 min-w-[220px]">
                        <div class="bg-white text-gray-400 rounded-2xl p-4 text-center min-w-[80px] border border-gray-200 group-hover:border-blue-100 group-hover:text-blue-400 transition-colors">
                            <span class="block text-xs font-bold uppercase tracking-wider mb-1"><?php echo $month; ?></span>
                            <span class="block text-3xl font-bold"><?php echo $day; ?></span>
                        </div>
                        <div>
                            <div class="text-xs text-gray-400 font-bold uppercase tracking-wide mb-1">Time Slot</div>
                            <div class="text-xl font-bold text-gray-600 group-hover:text-slate-800 transition-colors"><?php echo $time; ?></div>
                            <div class="<?php echo $badgeClass; ?> text-[10px] font-bold px-2.5 py-1 rounded-full inline-flex items-center gap-1 mt-2 uppercase">
                                <i data-lucide="<?php echo $icon; ?>" class="w-3 h-3"></i> <?php echo $status; ?>
                            </div>
                        </div>
                    </div>

                    <div class="flex-1 grid grid-cols-1 md:grid-cols-3 gap-6 border-l border-gray-200 pl-0 lg:pl-6">
                        <div>
                            <span class="text-xs text-gray-400 font-bold uppercase tracking-wide block mb-2">Child</span>
                            <p class="font-bold text-gray-600 text-sm group-hover:text-slate-800"><?php echo $appt['child_name']; ?></p>
                        </div>
                        <div>
                            <span class="text-xs text-gray-400 font-bold uppercase tracking-wide block mb-2">Vaccine</span>
                            <p class="font-bold text-gray-600 text-sm group-hover:text-slate-800"><?php echo $appt['vaccine_name']; ?></p>
                        </div>
                        <div>
                            <span class="text-xs text-gray-400 font-bold uppercase tracking-wide block mb-2">Hospital</span>
                            <p class="font-bold text-gray-600 text-sm group-hover:text-slate-800"><?php echo $appt['hospital_name']; ?></p>
                        </div>
                    </div>

                    <div class="flex gap-3 mt-4 lg:mt-0 pt-4 lg:pt-0 border-t lg:border-t-0 border-gray-200">
                        <?php if($status == 'completed'): ?>
                        <a href="./asset/reports/<?php echo $appt['report']?>" download class="px-5 py-2.5 bg-darkblue text-white rounded-xl text-xs font-bold hover:bg-blue-900 transition flex items-center justify-center gap-2 shadow-md shadow-blue-900/10">
                            <i data-lucide="download" class="w-3 h-3"></i> Report
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

    </div>
</div>

<?php include 'partials/inc_footer.php'; ?>