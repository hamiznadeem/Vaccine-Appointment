<?php
ob_start();
include 'admin_partials/inc_header.php';


$message = '';
$error = '';

// --- 2. Handle Form Submission ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_admin_btn'])) {
    
    // Clean Inputs
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $admin_name = $_POST['adminname'];
    $role = (int)$_POST['role']; // 1 = Super Admin, 2 = Manager

    // Validation
    if (empty($username) || empty($email) || empty($password)) {
        $error = "All fields are required!";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters long.";
    } else {
        // Check if Email already exists
        $check_email = mysqli_query($conn, "SELECT email FROM admins WHERE email = '$email'");
        if (mysqli_num_rows($check_email) > 0) {
            $error = "This email is already registered!";
        } else {
            // Password for Security
            $hashed_password = $password;

            // Insert Query
            $sql = "INSERT INTO admins (username, password, admin_name, email, role_id) 
                    VALUES ('$username', '$hashed_password', '$admin_name', '$email', '$role')";

            if (mysqli_query($conn, $sql)) {
                $message = "New Admin user created successfully!";
                // Optional: Clear form data logic can be added here
            } else {
                $error = "Database Error: " . mysqli_error($conn);
            }
        }
    }
}
?>

<main class="relative lg:ml-64 pt-6 lg:pt-10 pb-10 min-h-screen bg-slate-50">
    
    <div class="px-6 lg:px-10 mb-6 flex items-center gap-2 text-sm text-gray-400">
        <a href="admins.php" class="hover:text-primary transition">Admin Users</a>
        <i data-lucide="chevron-right" class="w-4 h-4"></i>
        <span class="text-slate-800 font-bold">Add New Admin</span>
    </div>

    <div class="px-6 lg:px-10 fade-in">

        <div class="max-w-2xl mx-auto bg-white rounded-[2.5rem] p-8 md:p-10 shadow-sm border border-gray-100">
            
            <div class="flex items-center gap-4 mb-8 pb-8 border-b border-gray-100">
                <div class="w-16 h-16 rounded-2xl bg-blue-50 text-primary flex items-center justify-center shadow-sm">
                    <i data-lucide="user-plus" class="w-8 h-8"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-darkblue">Create Admin Account</h1>
                    <p class="text-gray-500 text-sm">Add a new administrator to manage the portal.</p>
                </div>
            </div>

            <?php if (!empty($message)): ?>
                <div class="bg-green-50 text-green-700 p-4 rounded-xl mb-6 text-sm flex items-center gap-2 border border-green-200">
                    <i data-lucide="check-circle" class="w-5 h-5"></i> 
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($error)): ?>
                <div class="bg-red-50 text-red-600 p-4 rounded-xl mb-6 text-sm flex items-center gap-2 border border-red-200">
                    <i data-lucide="alert-circle" class="w-5 h-5"></i> 
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="space-y-6">
                
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2 tracking-wider">User Name</label>
                    <div class="relative">
                        <input type="text" name="username"  placeholder="e.g. Admin" class="w-full pl-12 pr-4 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-medium text-slate-800 focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition">
                        <i data-lucide="user" class="absolute left-4 top-4 w-5 h-5 text-gray-400"></i>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2 tracking-wider">Admin Name</label>
                    <div class="relative">
                        <input type="text" name="adminname"  placeholder="Name" class="w-full pl-12 pr-4 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-medium text-slate-800 focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition">
                        <i data-lucide="user" class="absolute left-4 top-4 w-5 h-5 text-gray-400"></i>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2 tracking-wider">Email Address</label>
                    <div class="relative">
                        <input type="email" name="email"  placeholder="admin@vaccining.com" class="w-full pl-12 pr-4 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-medium text-slate-800 focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition">
                        <i data-lucide="mail" class="absolute left-4 top-4 w-5 h-5 text-gray-400"></i>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2 tracking-wider">Assign Role</label>
                        <div class="relative">
                            <select name="role" class="w-full pl-12 pr-4 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-medium text-slate-800 focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition appearance-none cursor-pointer">
                                <option value="2">Admin</option>
                                <option value="1">Super Admin</option>
                            </select>
                            <i data-lucide="shield" class="absolute left-4 top-4 w-5 h-5 text-gray-400"></i>
                            <i data-lucide="chevron-down" class="absolute right-4 top-4 w-4 h-4 text-gray-400 pointer-events-none"></i>
                        </div>
                        <p class="text-[10px] text-gray-400 mt-2 ml-1">* Super Admins have full access.</p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2 tracking-wider">Password</label>
                        <div class="relative">
                            <input type="password" name="password"  placeholder="••••••••" class="w-full pl-12 pr-4 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-medium text-slate-800 focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition">
                            <i data-lucide="lock" class="absolute left-4 top-4 w-5 h-5 text-gray-400"></i>
                        </div>
                    </div>
                </div>

                <div class="pt-6 flex flex-col sm:flex-row gap-4 border-t border-gray-100 mt-4">
                    <a href="admins.php" class="w-full sm:w-auto px-8 py-4 border border-gray-200 text-gray-500 rounded-2xl text-sm font-bold hover:bg-gray-50 transition text-center">Cancel</a>
                    
                    <button type="submit" name="add_admin_btn" class="w-full sm:w-auto px-8 py-4 bg-primary text-white rounded-2xl text-sm font-bold shadow-lg shadow-blue-900/20 hover:bg-cyan-500 transition flex items-center justify-center gap-2 ml-auto">
                        <i data-lucide="plus" class="w-4 h-4"></i> Create Account
                    </button>
                </div>

            </form>
        </div>
    </div>
</main>

<?php 
include 'admin_partials/inc_footer.php'; 
ob_end_flush();
?>