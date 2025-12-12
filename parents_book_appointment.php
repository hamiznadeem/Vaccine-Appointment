<?php
$title = 'Book Appointments';
include 'partials/inc_header.php';

// Check Login & Role
if (!isset($_SESSION['isLogin'])) {
    header('Location: login.php');
    exit;
} elseif ($_SESSION['user_role'] != 4) { // 4 = Parent
    header('Location: index.php');
    exit;
}

$parent_id = $_SESSION['user_id'];

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (empty($_POST['child_id'])) {
        $hospital_id = $_POST['hospital_id'];
        $message = 'Please select a Child to proceed with the Appointment.';
    } elseif (empty($_POST['vaccine_id'])) {
        $hospital_id = $_POST['hospital_id'];
        $message = 'Please select a Vaccine to proceed with the Appointment.';
    } elseif (empty($_POST['date']) && empty($_POST['time'])) {
        $hospital_id = $_POST['hospital_id'];
        $message = 'Please select Date & Time to proceed with the Appointment.';
    } else {
        $hospital_id = safe_input($_POST['hospital_id']);
        $child_id = safe_input($_POST['child_id']);
        $parent_id = safe_input($_POST['parent_id']);
        $vaccine_id = safe_input($_POST['vaccine_id']);
        $date = safe_input($_POST['date']);
        $time = safe_input($_POST['time']);

        $sql = " INSERT INTO `vaccination_schedules` (`child_id`, `parent_id`, `hospital_id`, `vaccine_id`, `scheduled_date`, `scheduled_time`, `status`) VALUES ( $child_id, $parent_id, $hospital_id, $vaccine_id, '$date', '$time', 'pending');";
        if ($result = mysqli_query($conn, $sql)) {
            header('location: appointment_success.php');
            exit;
        }
    }
}

// Check Hospital ID
if (isset($_GET['id'])) {
    if(isset($_GET['vc'])){
        $vc_id = mysqli_real_escape_string($conn, $_GET['vc']);
    }
    $hospital_id = mysqli_real_escape_string($conn, $_GET['id']);
    $sql = "SELECT * FROM hospitals WHERE hospital_id = '$hospital_id' ";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    if (!$row) {
        header('location: parents_hospitals.php');
        exit;
    }
} elseif ($hospital_id) {
    $sql = "SELECT * FROM hospitals WHERE hospital_id = '$hospital_id' ";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    if (!$row) {
        header('location: parents_hospitals.php');
        exit;
    }
} else {
    header('location: parents_hospitals.php');
    exit;
}

// Timezone Setup
date_default_timezone_set('Asia/Karachi');
$current_time = time();
?>


<div class="pt-32 pb-20 px-6 md:px-20 max-w-[1400px]">

    <div class="flex items-center gap-2 text-sm text-gray-400 mb-6">
        <a href="index.php" class="hover:text-primary">Home</a>
        <i data-lucide="chevron-right" class="w-4 h-4"></i>
        <a href="hospitals.php" class="hover:text-primary">Hospitals</a>
        <i data-lucide="chevron-right" class="w-4 h-4"></i>
        <span class="text-gray-800 font-medium">Book Appointment</span>
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">

        <div class="lg:col-span-8 fade-in">

            <!-- Hospital Hero Card -->
            <div class="bg-white rounded-[2.5rem] p-6 shadow-sm border border-gray-100 mb-8">
                <div
                    class="h-64 md:h-80  flex items-center justify-center bg-slate-100 rounded-[2rem] overflow-hidden relative mb-6">
                    <?php if (!empty($row['hospital_img'])) { ?>
                    <img src="<?php echo $row['hospital_img'] ?>" alt="Hospital Image"
                        class="w-full h-full object-cover">
                    <?php } else { ?>
                    <i data-lucide="hospital" class="w-24 h-24 text-primary"></i>
                    <?php } ?>
                </div>

                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-4">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">
                            <?php echo $row['hospital_name'] ?>
                        </h1>
                        <div class="flex items-center gap-2 text-gray-500 text-sm">
                            <i data-lucide="map-pin" class="w-4 h-4 text-primary"></i>
                            <?php echo $row['hospital_address'] ?>
                        </div>
                    </div>
                    <a href="callto:<?php echo $row['phone'] ?>"
                        class="flex items-center gap-2 px-5 py-2.5 bg-green-50 text-green-600 rounded-xl font-medium hover:bg-green-100 transition border border-green-200">
                        <i data-lucide="message-circle" class="w-5 h-5"></i>
                        Contact Hospital
                    </a>
                </div>

                <hr class="border-gray-100 my-6">

                <div>
                    <h3 class="font-bold text-lg mb-3">About Hospital</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">
                        <?php echo $row['hospital_description'] ?>
                    </p>
                </div>
            </div>

            <?php if ($message) { ?>
            <div
                class="flex items-center gap-2 mt-2 mb-5 p-3 py-4 rounded-xl text-sm font-bold bg-red-100 text-red-700">
                <i data-lucide="circle-alert" class="w-4 h-4 text-red-700"> </i>
                <?php echo $message; ?>
            </div>
            <?php } ?>

            <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" id="appointment_form">
                <input type="hidden" name="hospital_id" value="<?php echo $hospital_id; ?>">
                <input type="hidden" name="parent_id" value="<?php echo $parent_id; ?>">
                <div class="bg-white rounded-[2.5rem] p-6 md:p-8 shadow-sm border border-gray-100">
                    <h2 class="text-2xl font-bold text-gray-900 mb-8 flex items-center gap-3">
                        <span
                            class="w-8 h-8 rounded-lg bg-darkblue text-white flex items-center justify-center text-sm">
                            <i data-lucide="info" class="w-5 h-5 text-white"></i></span>
                        Select Details
                    </h2>

                    <div class="mb-10">
                        <label class="block text-gray-700 font-bold mb-4">Select Child</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <?php
                            $sql_child = "SELECT * FROM childrens WHERE parent_id = '$parent_id' ";
                            $result_child = mysqli_query($conn, $sql_child);

                            if (mysqli_num_rows($result_child) > 0) {
                                while ($child_row = mysqli_fetch_assoc($result_child)) {

                                    if ($child_row['child_gender'] == 'male') {
                                        $bgClass = 'bg-primary/30';
                                        $txtClass = 'text-primary';
                                        $icon = 'mars';
                                    } elseif ($child_row['child_gender'] == 'female') {
                                        $bgClass = 'bg-pink-200';
                                        $txtClass = 'text-pink-600';
                                        $icon = 'venus';
                                    }
                                    $checked = '';
                                    if($_SERVER['REQUEST_METHOD'] == 'POST'){
                                    if($_POST['child_id'] == $child_row['child_id']){
                                        $checked = 'checked';
                                    }
                                    }
                            ?>
                            <label class="cursor-pointer relative group">
                                <input type="radio" name="child_id" <?php echo $checked ?> value="
                                <?php echo $child_row['child_id'] ?>"
                                data-name="
                                <?php echo $child_row['child_name'] ?>"
                                data-gender="
                                <?php echo $child_row['child_gender'] ?>"
                                class="custom-radio sr-only child-select">

                                <div
                                    class="p-4 rounded-2xl border-2 border-gray-100 hover:border-blue-100 transition flex items-center gap-4">
                                    <div
                                        class="w-12 h-12 flex items-center justify-center rounded-full <?php echo $bgClass ?> border-2 border-white shadow-sm flex-shrink-0">
                                        <i data-lucide="baby" class="w-5 h-5 text-gray-700"></i>
                                    </div>
                                    <div>
                                        <div class="font-bold text-gray-800">
                                            <?php echo $child_row['child_name'] ?>
                                        </div>
                                        <div
                                            class="text-xs flex items-center gap-1 py-1 px-1 rounded text-white <?php echo $bgClass ?>">
                                            <i data-lucide="<?php echo $icon ?>"
                                                class="w-3 h-3 <?php echo $txtClass ?>"></i>
                                            <?php echo $child_row['child_gender'] ?>
                                        </div>
                                    </div>
                                    <i data-lucide="check-circle-2"
                                        class="check-icon w-5 h-5 text-primary ml-auto opacity-0 transition"></i>
                                </div>
                            </label>
                            <?php }
                            } ?>

                            <!-- Add New Child Button -->
                            <a href="add_child.php"
                                class="p-4 rounded-2xl border-2 border-dashed border-gray-300 hover:border-primary hover:text-primary transition flex items-center justify-center gap-2 text-gray-400 bg-gray-50 h-full">
                                <i data-lucide="plus" class="w-5 h-5"></i> Add New Child
                            </a>
                        </div>
                    </div>

                    <div class="mb-10">
                        <label class="block text-gray-700 font-bold mb-4">Select Vaccine</label>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <?php
                            $sql_vaccine = "SELECT * FROM vaccines WHERE hospital_id = '$hospital_id' AND stock_status != 'out_of_stock' ";
                            $result_vaccine = mysqli_query($conn, $sql_vaccine);
                            if (mysqli_num_rows($result_vaccine) > 0) {
                                while ($vaccine_row = mysqli_fetch_assoc($result_vaccine)) {
                                    $checked = '';
                                    if($_SERVER['REQUEST_METHOD'] == 'POST'){
                                    if($_POST['vaccine_id'] == $vaccine_row['vaccine_id']){
                                        $checked = 'checked';
                                    }
                                    }
                                    if($_SERVER['REQUEST_METHOD'] == 'GET'){
                                    if(isset($_GET['vc']) && $_GET['vc'] == $vaccine_row['vaccine_id']){
                                        $checked = 'checked';
                                    }
                                    }

                                    $disabled = '';
                                    $disabled_class = '';
                                    $border_hover_class = 'hover:border-blue-100';
                                    if($vaccine_row['stock_status'] == 'low'){
                                        $disabled = 'disabled';
                                        $disabled_class = 'curser-not-allowed text-gray-200';
                                        $border_hover_class = 'hover:border-gray-100';
                                    }
                            ?>
                            <label class="cursor-pointer relative group">
                                <input type="radio" <?php echo $disabled ?> name="vaccine_id" <?php echo $checked?> value="
                                <?php echo $vaccine_row['vaccine_id'] ?>"
                                data-name="
                                <?php echo $vaccine_row['vaccine_name'] ?>"
                                class="custom-radio sr-only vaccine-select">

                                <div
                                    class="p-4 rounded-2xl border-2 border-gray-100 <?php echo $disabled_class?> <?php echo $border_hover_class?> transition flex items-center gap-4">
                                    <?php
                                        if($vaccine_row['vaccine_type'] == 'Oral'){
                                            $vacc_icon = 'droplet';
                                            $vacc_bg_class = 'bg-blue-50';
                                            $vacc_text_class = 'text-blue-600';
                                        }
                                        elseif($vaccine_row['vaccine_type'] == 'Injection'){
                                            $vacc_icon = 'syringe';
                                            $vacc_bg_class = 'bg-red-50';
                                            $vacc_text_class = 'text-red-600';
                                        }
                                        elseif($vaccine_row['vaccine_type'] == 'Nasal'){
                                            $vacc_icon = 'spray';
                                            $vacc_bg_class = 'bg-purple-50';
                                            $vacc_text_class = 'text-purple-600';
                                        }
                                    ?>
                                    <div
                                        class="w-10 h-10 rounded-xl <?php echo $vacc_bg_class?> <?php echo $vacc_text_class?> flex items-center justify-center flex-shrink-0">
                                        <i data-lucide="<?php echo $vacc_icon ?>" class="w-5 h-5"></i>
                                    </div>
                                    <div>
                                        <div class="font-bold text-gray-800 text-sm">
                                            <?php echo $vaccine_row['vaccine_name'] ?>
                                            <p class="text-xs font-thin">Dose <?php echo $vaccine_row['doses'] ?></p>
                                        </div>
                                    </div>
                                    <i data-lucide="check-circle-2"
                                        class="check-icon w-5 h-5 text-primary ml-auto opacity-0 transition"></i>
                                </div>
                            </label>
                            <?php }
                            } else {
                                echo '<div class="col-span-3 flex items-center gap-2  text-gray-400"> <i data-lucide="syringe" class="text-primary w-5 h-5"></i> No vaccines available currently in ' . $row['hospital_name'] . ' </div>';
                            } ?>
                        </div>
                    </div>

                    <div class="mb-10">
                        <div class="flex justify-between items-center mb-4">
                            <label class="block text-gray-700 font-bold">Select Date</label>
                            <div class="text-sm font-bold text-darkblue">
                                <?php echo date('F Y', $current_time); ?>
                            </div>
                        </div>
                        <div class="flex gap-4 pl-2 overflow-x-auto no-scrollbar pb-2 pt-2">
                            <?php
                            for ($i = 0; $i < 7; $i++) {
                                $calc_date = strtotime("+$i day", $current_time);
                                $day_name = date('D', $calc_date);
                                $day_num = date('d', $calc_date);
                                $input_value = date('Y-m-d', $calc_date);
                                $display_date = date('D, d M', $calc_date);
                                $is_checked = '';
                                if($_SERVER['REQUEST_METHOD'] == 'POST'){
                                    if($_POST['date'] == $input_value){
                                        $is_checked = 'checked';
                                    }
                                    }else{
                                        $is_checked = ($i == 0) ? 'checked' : '';
                                    }
                            ?>
                            <label class="flex-shrink-0 cursor-pointer group">
                                <input type="radio" name="date" value="<?php echo $input_value; ?>"
                                    data-display="<?php echo $display_date; ?>" class="peer sr-only date-select" <?php
                                    echo $is_checked; ?>>
                                <div
                                    class="w-16 h-20 rounded-2xl flex flex-col items-center justify-center border border-gray-200 transition-all duration-300 bg-white text-gray-600 
                                    peer-checked:bg-darkblue peer-checked:text-white peer-checked:border-darkblue peer-checked:scale-105 peer-checked:shadow-lg shadow-blue-900/30 group-hover:border-primary">
                                    <span class="text-xs font-medium opacity-80">
                                        <?php echo $day_name; ?>
                                    </span>
                                    <span class="text-xl font-bold mt-1">
                                        <?php echo $day_num; ?>
                                    </span>
                                </div>
                            </label>
                            <?php } ?>
                        </div>
                    </div>

                    <div>
                        <label class="block text-gray-700 font-bold mb-4">Select Time Slot</label>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                            <?php
                            $times = ["09:00 AM", "10:00 AM", "11:30 AM", "01:00 PM", "02:00 PM", "03:00 PM", "04:00 PM", "05:00 PM"];
                            foreach ($times as $time) {
                                $checked = '';
                                if($_SERVER['REQUEST_METHOD'] == 'POST'){
                                    if($_POST['time'] == $time){
                                        $checked = 'checked';
                                    }
                                }
                            ?>
                            <label class="cursor-pointer">
                                <input type="radio" name="time" <?php echo $checked ?> value="<?php echo $time ?>"
                                    class="custom-radio sr-only time-select">
                                <div
                                    class="py-2.5 rounded-xl border border-gray-200 text-center text-sm font-medium text-gray-600 hover:border-primary hover:text-primary transition">
                                    <?php echo $time ?>
                                </div>
                            </label>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="lg:col-span-4">
            <div class="bg-white rounded-[2.5rem] p-6 shadow-xl border border-gray-100 sticky top-32">
                <h3 class="text-md font-bold text-gray-900 mb-6">Booking Summary</h3>
                <div class="space-y-6">

                    <div class="flex gap-4 items-start pb-4 border-b border-gray-50">
                        <div
                            class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center flex-shrink-0 text-primary">
                            <i data-lucide="baby" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <div class="text-xs text-gray-400 font-medium">Child</div>
                            <div class="text-lg font-bold text-gray-800" id="summary-child-name">Select Child</div>
                            <div class="text-[10px] text-gray-400" id="summary-child-gender">--</div>
                        </div>
                    </div>

                    <div class="flex gap-4 items-start">
                        <div
                            class="w-10 h-10 rounded-xl bg-gray-50 flex items-center justify-center flex-shrink-0 text-gray-400">
                            <i data-lucide="hospital" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <div class="text-xs text-gray-400 font-medium">Hospital</div>
                            <div class="text-sm font-bold text-gray-800">
                                <?php echo $row['hospital_name'] ?>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-4 items-start">
                        <div
                            class="w-10 h-10 rounded-xl bg-gray-50 flex items-center justify-center flex-shrink-0 text-gray-400">
                            <i data-lucide="flask-conical" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <div class="text-xs text-gray-400 font-medium">Vaccine</div>
                            <div class="text-sm font-bold text-gray-800" id="summary-vaccine-name">Select Vaccine</div>
                        </div>
                    </div>

                    <div class="flex gap-4 items-start">
                        <div
                            class="w-10 h-10 rounded-xl bg-gray-50 flex items-center justify-center flex-shrink-0 text-gray-400">
                            <i data-lucide="calendar-clock" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <div class="text-xs text-gray-400 font-medium">Date & Time</div>
                            <div class="text-sm font-bold text-gray-800">
                                <span id="summary-date">Select Date</span> <br>
                                <span id="summary-time" class="text-primary">Select Time</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-5 pt-6 border-t border-gray-100">
                    <button name="confirm" id="confirm"
                        class="w-full py-4 bg-darkblue text-white rounded-2xl font-bold shadow-lg shadow-blue-900/20 hover:bg-blue-900 transition flex items-center justify-center gap-2">
                        Confirm Appointment <i data-lucide="arrow-right" class="w-5 h-5"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'partials/inc_footer.php'; ?>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        // Form Submit Handler
        document.getElementById('confirm').addEventListener('click', function () {
            var form = document.getElementById('appointment_form');
            form.submit();
        });

        // --- 1. Event Listeners for User Click (Change Events) ---

        // Child Selection
        document.querySelectorAll('.child-select').forEach(input => {
            input.addEventListener('change', function () {
                document.getElementById('summary-child-name').textContent = this.getAttribute('data-name');
                document.getElementById('summary-child-gender').textContent = this.getAttribute('data-gender').toUpperCase();
            });
        });

        // Vaccine Selection
        document.querySelectorAll('.vaccine-select').forEach(input => {
            input.addEventListener('change', function () {
                document.getElementById('summary-vaccine-name').textContent = this.getAttribute('data-name');
            });
        });

        // Date Selection
        const dateInputs = document.querySelectorAll('.date-select');
        function updateDate() {
            const checkedDate = document.querySelector('.date-select:checked');
            if (checkedDate) {
                document.getElementById('summary-date').textContent = checkedDate.getAttribute('data-display');
            }
        }
        dateInputs.forEach(input => input.addEventListener('change', updateDate));

        // Time Selection
        document.querySelectorAll('.time-select').forEach(input => {
            input.addEventListener('change', function () {
                document.getElementById('summary-time').textContent = this.value;
            });
        });


        // --- 2. Handling State After POST (Page Reload) ---
        // Yeh hissa check karega ke agar PHP ne kisi radio ko 'checked' kiya hai, 
        // to Summary box update ho jaye.

        // Check & Update Child on Load
        const preSelectedChild = document.querySelector('.child-select:checked');
        if (preSelectedChild) {
            document.getElementById('summary-child-name').textContent = preSelectedChild.getAttribute('data-name');
            document.getElementById('summary-child-gender').textContent = preSelectedChild.getAttribute('data-gender').toUpperCase();
        }

        // Check & Update Vaccine on Load
        const preSelectedVaccine = document.querySelector('.vaccine-select:checked');
        if (preSelectedVaccine) {
            document.getElementById('summary-vaccine-name').textContent = preSelectedVaccine.getAttribute('data-name');
        }

        // Check & Update Date on Load
        updateDate(); // Date logic already function mein thi, bas call kar di

        // Check & Update Time on Load
        const preSelectedTime = document.querySelector('.time-select:checked');
        if (preSelectedTime) {
            document.getElementById('summary-time').textContent = preSelectedTime.value;
        }

    });
</script>