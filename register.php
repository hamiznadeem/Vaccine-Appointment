<?php
require 'database/db_conn.php';

$activeTab = 'parent';
$message = "";
$messageType = "";

// initialize error variables
$parNameErr = $parCnicErr = $parPhoneErr = $parEmailErr = $parPassErr = $parConfirmPassErr = $parTermsErr = " ";
$hosNameErr = $hosLcErr = $hosPhoneErr = $hosEmailErr = $hosAddErr = $hosPassErr = $hosConfirmPassErr = $hosTermsErr = " ";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Handle Parent Registration
    if (isset($_POST['register_parent'])) {
        $activeTab = 'parent';
        $all_fields_filled = true;

        // validate required fields

        // --------Parent Name-------
        if (empty($_POST['parent_name'])) {
            $parNameErr = "Full Name is required.";
            $all_fields_filled = false;
        } else {
            $name = safe_input($_POST['parent_name']);
        }

        if (!empty($name) && !preg_match('/^[A-Za-z\s]+$/', $name)) {
            $parNameErr = "Name must contain letters and spaces only.";
            $all_fields_filled = false;
        }

        
        // --------Parent CNIC-------
        if (empty($_POST['parent_cnic'])) {
            $parCnicErr = "CNIC number is required.";
            $all_fields_filled = false;
        } else {
            $cnic = safe_input($_POST['parent_cnic']);
        }
        if (!empty($cnic)) {
            $cnic_digits = preg_replace('/\D/', '', $cnic);
            if (strlen($cnic_digits) != 13) {
                $parCnicErr = "CNIC must be 13 digits.";
                $all_fields_filled = false;
            } else {
                $cnic = $cnic_digits; 
            }
        }
        if(!empty($cnic)){
            $sql = "SELECT cnic FROM parents WHERE cnic='$cnic'";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                $parCnicErr = "CNIC already registered.";
                $all_fields_filled = false;
            }
        }
        
        
        // --------Parent Phone-------
        if (empty($_POST['parent_phone'])) {
            $parPhoneErr = "Phone number is required.";
            $all_fields_filled = false;
        } else {
            $phone = safe_input($_POST['parent_phone']);
        }
        if (!empty($phone)) {
            $phone_digits = preg_replace('/\D/', '', $phone);
            if (strlen($phone_digits) != 11) {
                $parPhoneErr = "Phone must be 11 digits.";
                $all_fields_filled = false;
            } else {
                $phone = $phone_digits;
            }
        }
        if(!empty($phone)){
            $sql = "SELECT phone FROM parents WHERE phone='$phone'";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                $parPhoneErr = "Phone Number already registered.";
                $all_fields_filled = false;
            }
        }
        
        
        // --------Parent Email------
        if (empty($_POST['parent_email'])) {
            $parEmailErr = "Email address is required.";
            $all_fields_filled = false;
        } else {
            $email = safe_input($_POST['parent_email']);
        }
        if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $parEmailErr = "Invalid email address.";
            $all_fields_filled = false;
        }
        if(!empty($email)){
            $sql = "SELECT email FROM parents WHERE email='$email'";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                $parEmailErr = "Email already registered.";
                $all_fields_filled = false;
            }
        }

        
        // passwords: compare raw values before hashing
        if (empty($_POST['parent_password'])) {
            $parPassErr = "Password is required.";
            $all_fields_filled = false;
        } else {
            $rawPass = $_POST['parent_password'];
        }
        if (!empty($rawPass) && !preg_match('/(?=.*[A-Z])(?=.*[a-z]).{6,}/', $rawPass)) {
            $parPassErr = "Password must be at least 6 characters and contain both uppercase and lowercase letters.";
            $all_fields_filled = false;
        }

         // ------confirm password------
        if (empty($_POST['parent_conf_pass'])) {
            $parConfirmPassErr = "Confirm password is required.";
            $all_fields_filled = false;
        } else {
            $rawConfirm = $_POST['parent_conf_pass'];
        }

        // passwords: compare raw values before hashing 
        if (!empty($rawPass) && !empty($rawConfirm)) {
            if ($rawPass !== $rawConfirm) {
                $parConfirmPassErr = "Confirm password does not match.";
                $all_fields_filled = false;
            }
        }

        // Terms and Privacy Policy
        if (!isset($_POST['terms_parent'])) {
            $parTermsErr = "You must agree to the Terms & Privacy Policy.";
            $all_fields_filled = false;
        }


        if ($all_fields_filled) {
            // escape values to reduce injection risk
            $name = mysqli_real_escape_string($conn, $name);
            $cnic = mysqli_real_escape_string($conn, $cnic);
            $phone = mysqli_real_escape_string($conn, $phone);
            $email = mysqli_real_escape_string($conn, $email);
            $password = password_hash($rawPass, PASSWORD_DEFAULT);

            $sql = "INSERT INTO parents (fname, cnic, phone, email, password, role_id) VALUES ('$name', '$cnic', '$phone', '$email', '$password' , 4)";
            if (mysqli_query($conn, $sql) === TRUE) {
                $message = "Parent registered successfully! Please Login.";
                $messageType = "success";
            } else {
                $message = "Error: " . mysqli_error($conn);
                $messageType = "error";
            }
        } else {
            $message = "All required fields must be filled correctly.";
            $messageType = "error";
        }
    }

    // Handle Hospital Registration
    if (isset($_POST['register_hospital'])) {
        $activeTab = 'hospital';
        $all_fields_filled = true;

        // validate required fields

        
        // --------hospital Name-------
        if (empty($_POST['hos_name'])) {
            $hosNameErr = "Hospital name is required.";
            $all_fields_filled = false;
        } else {
            $hos_name = safe_input($_POST['hos_name']);
        }
        if (!empty($hos_name) && !preg_match('/^[A-Za-z\s]+$/', $hos_name)) {
            $hosNameErr = "Hospital name must contain letters and spaces only.";
            $all_fields_filled = false;
        }


        // --------hospital lc or Rg-------
        if (empty($_POST['hos_license'])) {
            $hosLcErr = "License is required.";
            $all_fields_filled = false;
        } else {
            $hos_license = safe_input($_POST['hos_license']);
        }
        if (!empty($hos_license)) {
            $hos_lc_valid = preg_replace('/\W-/', '', $hos_license);
            if (strlen($hos_lc_valid) < 8) {
                $hosLcErr = "License or Registration number must be valid.";
                $all_fields_filled = false;
            } else {
                $hos_license = $hos_lc_valid;
            }
        }
        if(!empty($hos_license)){
            $sql = "SELECT lc_rg_no FROM hospitals WHERE lc_rg_no='$hos_license'";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                $hosLcErr = "license number already registered.";
                $all_fields_filled = false;
            }
        }

        
        // --------hospital phone-------
        if (empty($_POST['hos_phone'])) {
            $hosPhoneErr = "Phone is required.";
            $all_fields_filled = false;
        } else {
            $hos_phone = safe_input($_POST['hos_phone']);
        }
        if (!empty($hos_phone)) {
            $hos_phone_digits = preg_replace('/\D/', '', $hos_phone);
            if (strlen($hos_phone_digits) != 11) {
                $hosPhoneErr = "Phone must be 11 digits.";
                $all_fields_filled = false;
            } else {
                $hos_phone = $hos_phone_digits;
            }
        }
        if(!empty($hos_phone)){
            $sql = "SELECT phone FROM hospitals WHERE phone='$hos_phone'";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                $hosPhoneErr = "phone number already registered.";
                $all_fields_filled = false;
            }
        }

        
        // --------hospital Email-------
        if (empty($_POST['hos_email'])) {
            $hosEmailErr = "Email is required.";
            $all_fields_filled = false;
        } else {
            $hos_email = safe_input($_POST['hos_email']);
        }
        if (!empty($hos_email) && !filter_var($hos_email, FILTER_VALIDATE_EMAIL)) {
            $hosEmailErr = "Invalid email address.";
            $all_fields_filled = false;
        }
        if(!empty($hos_email)){
            $sql = "SELECT email FROM hospitals WHERE email='$hos_email'";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                $hosEmailErr = "phone number already registered.";
                $all_fields_filled = false;
            }
        }

        
        // --------hospital Address-------
        if (empty($_POST['hos_address'])) {
            $hosAddErr = "Address is required.";
            $all_fields_filled = false;
        } else {
            $hos_address = safe_input($_POST['hos_address']);
        }



        // --------hospital password-------
        if (empty($_POST['hos_password'])) {
            $hosPassErr = "Password is required.";
            $all_fields_filled = false;
        } else {
            $hos_rawPass = $_POST['hos_password'];
        }
        if (!empty($hos_rawPass) && !preg_match('/(?=.*[A-Z])(?=.*[a-z]).{6,}/', $hos_rawPass)) {
            $hosPassErr = "Password must be at least 6 characters and contain both uppercase and lowercase letters.";
            $all_fields_filled = false;
        }

        // ------confirm password------
        if (empty($_POST['hos_conf_pass'])) {
            $hosConfirmPassErr = "Confirm password is required.";
            $all_fields_filled = false;
        } else {
            $hos_rawConfirm = $_POST['hos_conf_pass'];
        }

        // passwords: compare raw values before hashing
        if (!empty($hos_rawPass) && !empty($hos_rawConfirm)) {
            if ($hos_rawPass !== $hos_rawConfirm) {
                $hosConfirmPassErr = "Confirm password does not match.";
                $all_fields_filled = false;
            }
        }

        // Terms and Privacy Policy
        if (!isset($_POST['terms-hospital'])) {
            $hosTermsErr = "You must agree to the Terms & Privacy Policy.";
            $all_fields_filled = false;
        }

        if ($all_fields_filled) {
            $hos_name = mysqli_real_escape_string($conn, $hos_name);
            $hos_license = mysqli_real_escape_string($conn, $hos_license);
            $hos_phone = mysqli_real_escape_string($conn, $hos_phone);
            $hos_email = mysqli_real_escape_string($conn, $hos_email);
            $hos_address = mysqli_real_escape_string($conn, $hos_address);
            $hos_password = password_hash($hos_rawPass, PASSWORD_DEFAULT);

            $sql = "INSERT INTO hospitals (hospital_name, lc_rg_no, phone, email, hospital_address, password, role_id ) VALUES ('$hos_name', '$hos_license', '$hos_phone', '$hos_email', '$hos_address', '$hos_password', 3)";
            if (mysqli_query($conn, $sql) === TRUE) {
                $message = "Hospital registered successfully! Please Login.";
                $messageType = "success";
            } else {
                $message = "Error: " . mysqli_error($conn);
                $messageType = "error";
            }
        } else {
            $message = "All required fields must be filled correctly.";
            $messageType = "error";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Vaccining</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts: Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            scroll-behavior: smooth;
        }

        /* Remove number arrows cross-browser */
        input[type=number] {
            -moz-appearance: textfield;
            appearance: none;
        }

        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Tab Active State */
        .tab-btn.active {
            background-color: #eff6ff;
            /* blue-50 */
            color: #0B153C;
            /* darkblue */
            border-color: #17C2EC;
            /* primary */
        }

        /* New Grid Pattern */
        .bg-grid-slate-200 {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32' width='32' height='32' fill='none' stroke='%23e2e8f0'%3e%3cpath d='M0 .5H31.5V32'/%3e%3c/svg%3e");
        }
    </style>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        darkblue: '#0B153C',
                        primary: '#17C2EC',
                        carddark: '#1E293B'
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-slate-50 text-slate-800 flex flex-col min-h-screen relative overflow-x-hidden">
    <div class="fixed inset-0 -z-10 overflow-hidden pointer-events-none bg-slate-50">

        <!-- Grid Pattern -->
        <div class="absolute inset-0 bg-grid-slate-200 [mask-image:linear-gradient(0deg,white,rgba(255,255,255,0.6))] bg-center"></div>

        <!-- Center Glow -->
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-full h-full max-w-[800px] max-h-[800px] bg-white/60 blur-3xl rounded-full -z-10"></div>
    </div>


    <!-- Main Content -->
    <div class="min-h-screen flex items-center justify-center px-6 pt-6 pb-10">

        <div class="bg-white rounded-[2.5rem] shadow-2xl border border-gray-100 w-full max-w-lg p-8 md:p-10 fade-in relative overflow-hidden">

            <!-- Decorative Circle -->
            <div class="absolute -top-10 -left-10 w-40 h-40 bg-blue-50 rounded-full blur-xl"></div>

            <div class="text-center mb-8 relative z-10">
                <h1 class="text-3xl font-bold text-darkblue">Create Account</h1>
                <p class="text-gray-500 mt-2 text-sm">Join Vaccining to manage immunizations.</p>
                <?php if ($message): ?>
                    <div class="mt-4 p-3 rounded-xl text-sm font-bold <?php echo $messageType == 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'; ?>">
                        <?php echo $message; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Role Tabs -->
            <div class="flex bg-gray-50 p-1.5 rounded-xl mb-8 border border-gray-100 relative z-10">
                <button onclick="switchTab('parent')" id="tab-parent" class="tab-btn <?php echo $activeTab == 'parent' ? 'active' : ''; ?> flex-1 py-2.5 rounded-lg text-xs font-bold text-gray-500 transition flex items-center justify-center gap-2 border border-transparent">
                    <i data-lucide="baby" class="w-4 h-4"></i> Parent
                </button>
                <button onclick="switchTab('hospital')" id="tab-hospital" class="tab-btn <?php echo $activeTab == 'hospital' ? 'active' : ''; ?> flex-1 py-2.5 rounded-lg text-xs font-bold text-gray-500 transition flex items-center justify-center gap-2 border border-transparent">
                    <i data-lucide="hospital" class="w-4 h-4"></i> Hospital
                </button>
            </div>

            <!-- PARENT Registration Form -->
            <form id="form-parent" class="space-y-5 relative z-10 <?php echo $activeTab == 'parent' ? 'block' : 'hidden'; ?>" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">

                <!-- Full Name -->
                <div>
                    <label for="parent_name" class="block text-xs font-bold text-gray-500 uppercase mb-2 tracking-wider">Full Name</label>
                    <div class="relative mb-1">
                        <input type="text" id="parent_name" name="parent_name" placeholder="John Doe" class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium text-slate-800 focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition <?php echo $parNameErr == " " ? '' : 'border-red-700 ring-2 ring-red-700/30'; ?>">
                        <i data-lucide="user" class="absolute left-3 top-3.5 w-4 h-4 text-gray-400"></i>
                    </div>
                    <span class="text-xs text-red-700 ml-2"><?php echo $parNameErr; ?></span>
                </div>

                <!-- CNIC -->
                <div>
                    <label for="parent_cnic" class="block text-xs font-bold text-gray-500 uppercase mb-2 tracking-wider">CNIC Number</label>
                    <div class="relative mb-1">
                        <input type="number" id="parent_cnic" name="parent_cnic" placeholder="42101-xxxxxxx-x" class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium text-slate-800 focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition <?php echo $parCnicErr == " " ? '' : 'border-red-700 ring-2 ring-red-700/30'; ?>">
                        <i data-lucide="credit-card" class="absolute left-3 top-3.5 w-4 h-4 text-gray-400"></i>
                    </div>
                    <span class="text-xs text-red-700 ml-2"><?php echo $parCnicErr; ?></span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Phone -->
                    <div>
                        <label for="parent_phone" class="block text-xs font-bold text-gray-500 uppercase mb-2 tracking-wider">Phone Number</label>
                        <div class="relative mb-1">
                            <input type="tel" id="parent_phone" name="parent_phone" placeholder="0300-1234567" class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium text-slate-800 focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition <?php echo $parPhoneErr != " " ? 'border-red-700 ring-2 ring-red-700/30' : ''; ?>">
                            <i data-lucide="phone" class="absolute left-3 top-3.5 w-4 h-4 text-gray-400"></i>
                        </div>
                        <span class="text-xs text-red-700 ml-2"><?php echo $parPhoneErr; ?></span>
                    </div>
                    <!-- Email -->
                    <div>
                        <label for="parent_email" class="block text-xs font-bold text-gray-500 uppercase mb-2 tracking-wider">Email Address</label>
                        <div class="relative mb-1">
                            <input type="email" id="parent_email" name="parent_email" placeholder="john@email.com" class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium text-slate-800 focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition <?php echo $parEmailErr != " " ? 'border-red-700 ring-2 ring-red-700/30' : ''; ?>">
                            <i data-lucide="mail" class="absolute left-3 top-3.5 w-4 h-4 text-gray-400"></i>
                        </div>
                        <span class="text-xs text-red-700 ml-2"><?php echo $parEmailErr; ?></span>
                    </div>
                </div>

                <!-- Password -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="parent_password" class="block text-xs font-bold text-gray-500 uppercase mb-2 tracking-wider">Password</label>
                        <div class="relative mb-1">
                            <input type="password" id="parent_password" name="parent_password" placeholder="••••••••" class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium text-slate-800 focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition <?php echo $parPassErr != " " ? 'border-red-700 ring-2 ring-red-700/30' : ''; ?>">
                            <i data-lucide="lock" class="absolute left-3 top-3.5 w-4 h-4 text-gray-400"></i>
                        </div>
                        <span class="text-xs text-red-700 ml-2"><?php echo $parPassErr; ?></span>
                    </div>
                    <!-- Confirm Password -->
                    <div>
                        <label for="parent_conf_pass" class="block text-xs font-bold text-gray-500 uppercase mb-2 tracking-wider">Confirm Password</label>
                        <div class="relative mb-1">
                            <input type="password" id="parent_conf_pass" name="parent_conf_pass" placeholder="••••••••" class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium text-slate-800 focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition <?php echo $parConfirmPassErr != " " ? 'border-red-700 ring-2 ring-red-700/30' : ''; ?>">
                            <i data-lucide="check-square" class="absolute left-3 top-3.5 w-4 h-4 text-gray-400"></i>
                        </div>
                        <span class="text-xs text-red-700 ml-2"><?php echo $parConfirmPassErr; ?></span>
                    </div>
                </div>

                <div class="flex items-center gap-2 pt-2">
                    <input type="checkbox" id="terms-parent" name="terms_parent" class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary <?php echo $parTermsErr != " " ? 'border-red-700 ring-2 ring-red-700/30' : ''; ?>">
                    <label for="terms-parent" class="text-xs text-gray-500">I agree to the <a href="terms&condition.php" class="text-primary font-bold hover:underline">Terms</a> & <a href="privacy&policy.php" class="text-primary font-bold hover:underline">Privacy Policy</a></label>
                </div>
                <span class="text-xs text-red-700 ml-2"><?php echo $parTermsErr; ?></span>

                <button type="submit" name="register_parent" class="w-full py-3.5 bg-primary text-white rounded-xl font-bold shadow-lg shadow-blue-900/20 hover:bg-cyan-500 transition flex items-center justify-center gap-2">
                    Register as Parent <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </button>
            </form>


            <!-- HOSPITAL Registration Form -->
            <form id="form-hospital" class="space-y-5 relative z-10 <?php echo $activeTab == 'hospital' ? 'block' : 'hidden'; ?>" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">

                <!-- Hospital Name -->
                <div>
                    <label for="hos_name" class="block text-xs font-bold text-gray-500 uppercase mb-2 tracking-wider">Hospital Name</label>
                    <div class="relative mb-1">
                        <input type="text" name="hos_name" placeholder="e.g. City General Hospital" class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium text-slate-800 focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition <?php echo $hosNameErr != " " ? 'border-red-700 ring-2 ring-red-700/30' : ''; ?>">
                        <i data-lucide="building-2" class="absolute left-3 top-3.5 w-4 h-4 text-gray-400"></i>
                    </div>
                    <span class="text-xs text-red-700 ml-2"><?php echo $hosNameErr; ?></span>
                </div>

                <!-- License & Phone -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="hos_license" class="block text-xs font-bold text-gray-500 uppercase mb-2 tracking-wider">License / Reg No.</label>
                        <div class="relative mb-1">
                            <input type="text" name="hos_license" placeholder="HOS-REG-1234" class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium text-slate-800 focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition <?php echo $hosLcErr != " " ? 'border-red-700 ring-2 ring-red-700/30' : ''; ?>">
                            <i data-lucide="file-badge" class="absolute left-3 top-3.5 w-4 h-4 text-gray-400"></i>
                        </div>
                        <span class="text-xs text-red-700 ml-2"><?php echo $hosLcErr; ?></span>
                    </div>
                    <div>
                        <label for="hos_phone" class="block text-xs font-bold text-gray-500 uppercase mb-2 tracking-wider">Help Line / Phone</label>
                        <div class="relative mb-1">
                            <input type="tel" name="hos_phone" placeholder="021-1234567" class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium text-slate-800 focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition <?php echo $hosPhoneErr != " " ? 'border-red-700 ring-2 ring-red-700/30' : ''; ?>">
                            <i data-lucide="phone" class="absolute left-3 top-3.5 w-4 h-4 text-gray-400"></i>
                        </div>
                        <span class="text-xs text-red-700 ml-2"><?php echo $hosPhoneErr; ?></span>
                    </div>
                </div>

                <!-- Email -->
                <div>
                    <label for="hos_email" class="block text-xs font-bold text-gray-500 uppercase mb-2 tracking-wider">Official Email</label>
                    <div class="relative mb-1">
                        <input type="email" name="hos_email" placeholder="admin@hospital.com" class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium text-slate-800 focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition <?php echo $hosEmailErr != " " ? 'border-red-700 ring-2 ring-red-700/30' : ''; ?>">
                        <i data-lucide="mail" class="absolute left-3 top-3.5 w-4 h-4 text-gray-400"></i>
                    </div>
                    <span class="text-xs text-red-700 ml-2"><?php echo $hosEmailErr; ?></span>
                </div>

                <!-- Address -->
                <div>
                    <label for="hos_address" class="block text-xs font-bold text-gray-500 uppercase mb-2 tracking-wider">Address</label>
                    <div class="relative mb-1">
                        <input type="text" name="hos_address" placeholder="Sector 11-A, North Karachi" class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium text-slate-800 focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition <?php echo $hosAddErr != " " ? 'border-red-700 ring-2 ring-red-700/30' : ''; ?>">
                        <i data-lucide="map-pin" class="absolute left-3 top-3.5 w-4 h-4 text-gray-400"></i>
                    </div>
                    <span class="text-xs text-red-700 ml-2"><?php echo $hosAddErr; ?></span>
                </div>

                <!-- Password -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="hos_password" class="block text-xs font-bold text-gray-500 uppercase mb-2 tracking-wider">Password</label>
                        <div class="relative mb-1">
                            <input type="password" id="hos_password" name="hos_password" placeholder="••••••••" class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium text-slate-800 focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition <?php echo $hosPassErr != " " ? 'border-red-700 ring-2 ring-red-700/30' : ''; ?>">
                            <i data-lucide="lock" class="absolute left-3 top-3.5 w-4 h-4 text-gray-400"></i>
                        </div>
                        <span class="text-xs text-red-700 ml-2"><?php echo $hosPassErr; ?></span>
                    </div>
                    <!-- Confirm Password -->
                    <div>
                        <label for="hos_conf_pass" class="block text-xs font-bold text-gray-500 uppercase mb-2 tracking-wider">Confirm Password</label>
                        <div class="relative mb-1">
                            <input type="password" id="hos_conf_pass" name="hos_conf_pass" placeholder="••••••••" class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium text-slate-800 focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition <?php echo $hosConfirmPassErr != " " ? 'border-red-700 ring-2 ring-red-700/30' : ''; ?>">
                            <i data-lucide="check-square" class="absolute left-3 top-3.5 w-4 h-4 text-gray-400"></i>
                        </div>
                        <span class="text-xs text-red-700 ml-2"><?php echo $hosConfirmPassErr; ?></span>
                    </div>
                </div>

                <div class="flex items-center gap-2 pt-2">
                    <input type="checkbox" id="terms-hospital" name="terms-hospital" class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary <?php echo $hosTermsErr != " " ? 'border-red-700 ring-2 ring-red-700/30' : ''; ?>">
                    <label for="terms-hospital" class="text-xs text-gray-500">I agree to the <a href="terms_of_service.html" class="text-primary font-bold hover:underline">Terms</a> & <a href="privacy_policy.html" class="text-primary font-bold hover:underline">Privacy Policy</a></label>
                </div>
                <span class="text-xs text-red-700 ml-2"><?php echo $hosTermsErr; ?></span>

                <button type="submit" name="register_hospital" class="w-full py-3.5 bg-darkblue text-white rounded-xl font-bold shadow-lg shadow-blue-900/20 hover:bg-slate-800 transition flex items-center justify-center gap-2">
                    Register Hospital <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </button>
            </form>

            <!-- Footer -->
            <div class="mt-8 text-center pt-6 border-t border-gray-100 relative z-10">
                <p class="text-sm text-gray-500">Already have an account? <a href="login.php" class="text-primary font-bold hover:underline">Login</a></p>
            </div>

        </div>
    </div>

    <script>
        // Initialize Icons
        lucide.createIcons();

        // Tab Switching Logic
        function switchTab(role) {
            // persist choice for non-POST refreshes
            try { localStorage.setItem('activeTab', role); } catch(e){}
            // Hide all forms
            document.getElementById('form-parent').classList.add('hidden');
            document.getElementById('form-hospital').classList.add('hidden');

            // Remove active class from all tabs
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });

            // Show selected form
            document.getElementById(`form-${role}`).classList.remove('hidden');

            // Add active class to clicked tab
            const activeBtn = document.getElementById(`tab-${role}`);
            activeBtn.classList.add('active');
        }

        // Restore active tab on load. If last request was a POST, prefer server-side decision.
        const serverActiveTab = <?php echo json_encode($activeTab); ?>;
        const wasPost = <?php echo json_encode($_SERVER['REQUEST_METHOD'] == 'POST'); ?>;

        document.addEventListener('DOMContentLoaded', function () {
            if (!wasPost) {
                const stored = (function(){ try { return localStorage.getItem('activeTab'); } catch(e){ return null;} })();
                if (stored) {
                    switchTab(stored);
                    return;
                }
            }
            // Fallback to server decision
            switchTab(serverActiveTab);
        });
    </script>
</body>

</html>