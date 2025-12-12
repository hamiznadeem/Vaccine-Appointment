<?php
$title = 'Appointments';
include 'partials/inc_header.php';

// --- Authentication Check ---
if (!isset($_SESSION['isLogin'])) {
    header('Location: login.php');
    exit;
} elseif ($user_role != 3) {
    header('Location: index.php');
    exit;
}

$hospital_id = $_SESSION['user_id'];
$message = '';

if (isset($_POST['upload_btn'])) {
    $vs_id = $_POST['id']; 
    $filename = $_FILES["report"]["name"];
    $tempname = $_FILES["report"]["tmp_name"];
    $folder = "asset/reports/" . $filename;

    if (!empty($filename)) {
        $sql = "UPDATE `vaccination_schedules` SET `report` = '$filename' WHERE `schedule_id` = '$vs_id' ";
        
        if (mysqli_query($conn, $sql)) {
            if (move_uploaded_file($tempname, $folder)) {
                header('Location:' . $_SERVER['PHP_SELF'] );
            } else {
                $message = "Database saved, but file move failed.";
                $msg_type = "error";
            }
        } else {
            $message = "Database Error: " . mysqli_error($conn);
            $msg_type = "error";
        }
    } else {
        $message = "Please select a file first.";
        $msg_type = "error";
    }
}

// --- 1. Handle Actions (Check In / No Show) ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    $schedule_id = $_POST['schedule_id'];
    $new_status = $_POST['new_status']; // 'completed' or 'cancelled'

    // Update query
    $update_sql = "UPDATE vaccination_schedules SET status = '$new_status' WHERE schedule_id = '$schedule_id' AND hospital_id = '$hospital_id'";

    if (mysqli_query($conn, $update_sql)) {
        // Refresh page to show changes
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } else {
        $message = "Error updating status.";
    }
}

// --- 2. Fetch Data ---
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
            v.vaccine_type, 
            v.doses, 
            p.fname, 
            p.phone,
            p.email
        FROM vaccination_schedules vs
        JOIN childrens c ON vs.child_id = c.child_id
        JOIN vaccines v ON vs.vaccine_id = v.vaccine_id
        JOIN parents p ON vs.parent_id = p.parent_id
        WHERE vs.hospital_id = '$hospital_id'
        ORDER BY vs.scheduled_date ASC, vs.scheduled_time ASC";

$result = mysqli_query($conn, $sql);

$upcoming_appointments = [];
$past_appointments = [];
$today = date('Y-m-d');

// --- 3. Process & Categorize Data ---
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

        $status = strtolower($row['status']);

        if ($status == 'completed' || $status == 'cancelled') {
            $past_appointments[] = $row;
        } elseif ($row['scheduled_date'] < $today && $status != 'completed') {
            $past_appointments[] = $row;
        } else {
            $upcoming_appointments[] = $row;
        }
    }
}

// --- 4. Calculate Stats ---
$total_count = count($upcoming_appointments) + count($past_appointments);
$pending_count = count($upcoming_appointments);
$done_count = count($past_appointments);

?>

<style>
    /* Upload Area styling */
        .file-drop-area {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            max-width: 100%;
            padding: 25px;
            border: 2px dashed #ccc; /* Dashed Border */
            border-radius: 8px;
            transition: 0.2s;
            background-color: #fafafa;
            margin-bottom: 20px;
            box-sizing: border-box;
        }

        .file-drop-area:hover {
            border-color: #4a90e2;
            background-color: #f0f8ff;
        }

        /* Input file ko thora clean banana */
        input[type="file"] {
            font-size: 14px;
            color: #555;
            width: 100%;
        }

        /* Button Styling */
        .btn-upload {
            background: #4a90e2; /* Nice Blue */
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 30px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
            box-shadow: 0 4px 6px rgba(74, 144, 226, 0.3);
        }

        .btn-upload:hover {
            background: #357abd;
            transform: translateY(-2px); /* Thora upar uthay ga */
        }

        /* Message Styling */
        .msg-box {
            margin-top: 15px;
            padding: 10px;
            border-radius: 5px;
            font-size: 14px;
            display: none; /* Default hidden */
        }
        
        /* PHP se message aane par show karein */
        .msg-box.show { display: block; }
        .msg-box.success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .msg-box.error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
</style>

<div class="pt-32 pb-20 px-6 md:px-20 max-w-[1400px] mx-auto flex-grow w-full fade-in">

    <div class="flex flex-col md:flex-row justify-between items-end gap-6 mb-10">
        <div>
            <span class="text-primary font-bold text-xs tracking-widest uppercase mb-2 block">Portal</span>
            <h1 class="text-3xl md:text-5xl font-bold text-darkblue leading-tight">Manage <span class="text-primary">Bookings</span></h1>
            <p class="text-gray-500 mt-2 text-sm">View and manage upcoming vaccination appointments.</p>
        </div>

        <div class="flex gap-4">
            <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 min-w-[100px] text-center">
                <span class="text-xs text-gray-400 font-bold uppercase">Total</span>
                <p class="text-2xl font-bold text-slate-800"><?php echo $total_count; ?></p>
            </div>
            <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 min-w-[100px] text-center">
                <span class="text-xs text-gray-400 font-bold uppercase">Pending</span>
                <p class="text-2xl font-bold text-orange-500"><?php echo $pending_count; ?></p>
            </div>
            <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 min-w-[100px] text-center">
                <span class="text-xs text-gray-400 font-bold uppercase">History</span>
                <p class="text-2xl font-bold text-green-500"><?php echo $done_count; ?></p>
            </div>
        </div>
    </div>

    <div class="space-y-6">

        <?php if (!empty($upcoming_appointments)) { ?>
            <?php foreach ($upcoming_appointments as $appt) {
                // Format Date & Time
                $dateObj = DateTime::createFromFormat('Y-m-d', $appt['scheduled_date']);
                $formattedDate = $dateObj->format('M d'); // Example: Dec 12
                $time = date('h:i A', strtotime($appt['scheduled_time']));
            ?>
                <div class="bg-white rounded-[2rem] p-6 border border-gray-100 shadow-sm hover:shadow-xl hover:shadow-blue-500/5 transition duration-300 relative overflow-hidden group">
                    <div class="absolute top-0 left-0 w-1.5 h-full bg-blue-500"></div>

                    <div class="flex flex-col lg:flex-row gap-6 lg:items-center justify-between">

                        <div class="flex items-center gap-6 min-w-[200px]">
                            <div class="bg-blue-50 text-blue-700 rounded-2xl p-4 text-center min-w-[80px] border border-blue-100">
                                <span class="block text-xs font-bold uppercase tracking-wider mb-1">Date</span>
                                <span class="block text-xl font-bold"><?php echo $formattedDate; ?></span>
                            </div>
                            <div>
                                <div class="text-xs text-gray-400 font-bold uppercase tracking-wide mb-1">Time Slot</div>
                                <div class="text-xl font-bold text-slate-800"><?php echo $time; ?></div>
                                <div class="status-scheduled text-[10px] font-bold px-2.5 py-1 rounded-full inline-flex items-center gap-1 mt-2 text-blue-600 bg-blue-50">
                                    <?php echo strtoupper($appt['status']); ?>
                                </div>
                            </div>
                        </div>

                        <div class="flex-1 grid grid-cols-1 md:grid-cols-3 gap-6 border-l border-gray-100 pl-0 lg:pl-6">
                            <div>
                                <span class="text-xs text-gray-400 font-bold uppercase tracking-wide block mb-2">Patient (Child)</span>
                                <div class="flex items-center gap-3">
                                    <?php
                                    if ($appt['child_gender'] == 'male') {
                                        $bgClass = 'bg-primary/30';
                                        $textClass = 'text-primary/80';
                                    } elseif ($appt['child_gender'] == 'female') {
                                        $bgClass = 'bg-pink-200';
                                        $textClass = 'text-pink-500';
                                    }
                                    ?>
                                    <div class="w-10 h-10 rounded-full <?php echo $bgClass ?>  <?php echo $textClass ?> flex items-center justify-center font-bold text-xs">
                                        <i data-lucide="baby"></i>
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-800 text-sm"><?php echo htmlspecialchars($appt['child_name']); ?></p>
                                        <p class="text-[10px] text-gray-500 bg-gray-100 px-1.5 py-0.5 rounded inline-block mt-0.5"><?php echo $appt['age_display']; ?></p>
                                        <p class="text-[10px] text-gray-500 bg-gray-100 px-1.5 py-0.5 rounded inline-block mt-0.5"><?php echo $appt['child_gender']; ?></p>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <span class="text-xs text-gray-400 font-bold uppercase tracking-wide block mb-2">Vaccine</span>
                                <p class="font-bold text-slate-800 text-sm flex items-center gap-2">
                                    <?php
                                    if ($appt['vaccine_type'] == 'Oral') {
                                        $icon = 'droplet';
                                        $bg_class = 'bg-blue-50';
                                        $text_class = 'text-blue-600';
                                    } elseif ($appt['vaccine_type'] == 'Injection') {
                                        $icon = 'syringe';
                                        $bg_class = 'bg-red-50';
                                        $text_class = 'text-red-600';
                                    } elseif ($appt['vaccine_type'] == 'Nasal') {
                                        $icon = 'spray-can';
                                        $bg_class = 'bg-purple-50';
                                        $text_class = 'text-purple-600';
                                    }
                                    ?>
                                    <span class="w-8 h-8 rounded-lg <?php echo $bg_class ?> <?php echo $text_class ?>  flex items-center justify-center"><i data-lucide="<?php echo $icon ?>" class="w-4 h-4"></i></span>
                                    <?php echo htmlspecialchars($appt['vaccine_name']); ?>
                                </p>
                                <p class="text-[10px] text-gray-500 ml-10 mt-1">Dose <?php echo htmlspecialchars($appt['doses']); ?></p>
                            </div>
                            <div>
                                <span class="text-xs text-gray-400 font-bold uppercase tracking-wide block mb-2">Parent Contact</span>
                                <p class="font-bold text-slate-800 text-sm flex items-center gap-2">
                                    <span class="w-8 h-8 rounded-lg bg-gray-50 text-gray-500 flex items-center justify-center"><i data-lucide="phone" class="w-4 h-4"></i></span>
                                    <?php echo htmlspecialchars($appt['fname']); ?>
                                </p>
                                <p class="text-[10px] text-gray-500 ml-10 mt-1"><?php echo htmlspecialchars($appt['phone']); ?></p>
                                <p class="text-[10px] text-gray-500 ml-10 mt-1"><?php echo htmlspecialchars($appt['email']); ?></p>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-3 mt-4 lg:mt-0 pt-4 lg:pt-0 border-t lg:border-t-0 border-gray-100">
                            <form method="POST" action="">
                                <input type="hidden" name="schedule_id" value="<?php echo $appt['schedule_id']; ?>">
                                <input type="hidden" name="new_status" value="completed">
                                <button type="submit" name="update_status" class="w-full sm:w-auto px-5 py-2.5 bg-primary text-white rounded-xl text-xs font-bold hover:bg-cyan-500 transition flex items-center justify-center gap-2 shadow-md shadow-blue-200">
                                    <i data-lucide="check-circle" class="w-3 h-3"></i> Check In
                                </button>
                            </form>

                            <form method="POST" action="">
                                <input type="hidden" name="schedule_id" value="<?php echo $appt['schedule_id']; ?>">
                                <input type="hidden" name="new_status" value="cancelled">
                                <button type="submit" name="update_status" class="w-full sm:w-auto px-5 py-2.5 bg-white text-red-500 border border-red-100 rounded-xl text-xs font-bold hover:bg-red-50 transition flex items-center justify-center gap-2">
                                    <i data-lucide="x" class="w-3 h-3"></i> No Show
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php } ?>

        <?php } else { ?>
            <div class="text-center py-12 bg-gray-50 rounded-[2rem] border border-dashed border-gray-300">
                <div class="w-16 h-16 bg-gray-100 text-gray-400 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="calendar-x" class="w-8 h-8"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-700">No Upcoming Appointments</h3>
                <p class="text-sm text-gray-500 mt-2">Book a new slot to get started.</p>
            </div>
        <?php } ?>

        <?php if (!empty($past_appointments)) { ?>
            <div class="pt-8">
                <h3 class="text-lg font-bold text-slate-700 mb-4 flex items-center gap-2">
                    <i data-lucide="check-circle-2" class="w-5 h-5 text-green-500"></i> History / Completed
                </h3>

                <?php foreach ($past_appointments as $past) {
                    // Creating Date Object for history loop
                    $dateObj = DateTime::createFromFormat('Y-m-d', $past['scheduled_date']);
                    $formattedDate = $dateObj->format('M d');
                    $time = date('h:i A', strtotime($past['scheduled_time']));
                    $statusColor = ($past['status'] == 'completed') ? 'text-green-600 bg-green-50' : 'text-red-600 bg-red-50';
                    $borderColor = ($past['status'] == 'completed') ? 'group-hover:border-green-200 group-hover:text-green-600' : 'group-hover:border-red-200 group-hover:text-red-600';
                ?>
                    <div class="bg-gray-50/80 rounded-[2rem] p-6 border border-gray-200 shadow-none hover:bg-white hover:shadow-md transition duration-300 relative overflow-hidden group mb-4">
                        <div class="flex flex-col lg:flex-row gap-6 lg:items-center justify-between">

                            <div class="flex items-center gap-6 min-w-[200px]">
                                <div class="bg-white text-gray-400 rounded-2xl p-4 text-center min-w-[80px] border border-gray-200 <?php echo $borderColor; ?> transition-colors">
                                    <span class="block text-xs font-bold uppercase tracking-wider mb-1">Date</span>
                                    <span class="block text-xl font-bold"><?php echo $formattedDate; ?></span>
                                </div>
                                <div>
                                    <div class="text-xs text-gray-400 font-bold uppercase tracking-wide mb-1">Time</div>
                                    <div class="text-xl font-bold text-gray-600 group-hover:text-slate-800 transition-colors"><?php echo $time; ?></div>
                                    <div class="<?php echo $statusColor; ?> text-[10px] font-bold px-2.5 py-1 rounded-full inline-flex items-center gap-1 mt-2">
                                        <?php echo strtoupper($past['status']); ?>
                                    </div>
                                </div>
                            </div>

                            <div class="flex-1 grid grid-cols-1 md:grid-cols-3 gap-6 border-l border-gray-200 pl-0 lg:pl-6">
                                <div>
                                    <span class="text-xs text-gray-400 font-bold uppercase tracking-wide block mb-2">Patient</span>
                                    <p class="font-bold text-gray-600 text-sm group-hover:text-slate-800"><?php echo htmlspecialchars($past['child_name']); ?></p>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-400 font-bold uppercase tracking-wide block mb-2">Vaccine</span>
                                    <p class="font-bold text-gray-600 text-sm group-hover:text-slate-800"><?php echo htmlspecialchars($past['vaccine_name']); ?></p>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-400 font-bold uppercase tracking-wide block mb-2">Parent</span>
                                    <p class="font-bold text-gray-600 text-sm group-hover:text-slate-800"><?php echo htmlspecialchars($past['fname']); ?></p>
                                </div>
                            </div>

                            <div class="flex gap-3 mt-4 lg:mt-0 pt-4 lg:pt-0 border-t lg:border-t-0 border-gray-200">
                                <?php
                                if ($past['status'] === 'completed') {
                                    if ($past['report'] === null) {
                                ?>
                                        <button onclick="my_modal_5.showModal()" class="px-5 py-2.5 border bg-darkblue/90 hover:bg-darkblue  border-gray-200 text-white rounded-xl text-xs font-bold flex items-center justify-center gap-2">
                                            <i data-lucide="upload" class="w-3 h-3"></i>Upload Report
                                        </button>
                                        <dialog id="my_modal_5" class="modal modal-bottom sm:modal-middle">
                                            <div class="modal-box">
                                                <div class="upload-card">
                                                    <h2 class="flex items-center gap-1 mb-1"> <i data-lucide="upload" class="w-3 h-3"></i> Upload Report</h2>
                                                    <?php if ($message != ""){ ?>
                                                        <div class="msg-box show mb-1 <?php echo $msg_type; ?>">
                                                            <?php echo $message; ?>
                                                        </div>
                                                    <?php } ?>
                                                    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" enctype="multipart/form-data">
                                                        <div class="file-drop-area">
                                                            <input type="file" name="report" >
                                                            <input type="hidden" name="id" value="<?php echo $past['schedule_id'] ?>">
                                                        </div>
                                                        <button type="submit" name="upload_btn" class=" w-full py-2 rounded bg-darkblue text-white flex items-center justify-center "><i data-lucide="send" class="w-6 h-6"></i></button>
                                                    </form>
                                                </div>
                                                <form method="dialog">
                                                    <!-- if there is a button in form, it will close the modal -->
                                                    <button class="w-full py-2 flex items-center justify-center rounded mt-2 bg-primary text-white transition duration-200" ><i data-lucide="x" class="w-5 h-5"></i> cancel</button>
                                                </form>
                                            </div>
                            </div>
                            </dialog>
                        <?php } else { ?>
                            <button class="px-5 py-2.5 border bg-green-500  border-gray-200 text-white rounded-xl text-xs font-bold flex items-center justify-center gap-2" disabled>
                                <i data-lucide="check-check" class="w-4 h-4"></i>Report Uploaded
                            </button>
                    <?php }
                                } ?>
                        </div>
                    </div>
            </div>
        <?php } ?>
    </div>
<?php } ?>

</div>
</div>
<?php
include 'partials/inc_footer.php';
?>