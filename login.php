<?php
ob_start();
session_start();
require 'database/db_conn.php';

$loginError = "";
$activeTab = 'parent';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Parent Login
    if (isset($_POST['login_parent'])) {
        $activeTab = 'parent';
        $email_or_phone = safe_input($_POST['parent_login_input']);
        $password = $_POST['parent_password'];

        if (empty($email_or_phone) || empty($password)) {
            $loginError = "Email/Phone and Password are required.";
        } else {
            // Check parent by email or phone
            $sql = "SELECT * FROM parents WHERE email='$email_or_phone' OR phone='$email_or_phone' LIMIT 1";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                // Verify password
                if (password_verify($password, $row['password'])) {
                    $_SESSION['user_id'] = $row['parent_id'];
                    $_SESSION['user_role'] = $row['role_id'];
                    $_SESSION['user_name'] = $row['fname'];
                    $_SESSION['user_email'] = $row['email'];
                    $_SESSION['isLogin'] = true;
                    header("Location: index.php");
                    exit();
                } else {
                    $loginError = "Invalid password.";
                }
            } else {
                $loginError = "Invalid Email/Phone number";
            }
        }
    }

    // Hospital Login
    if (isset($_POST['login_hospital'])) {
        $activeTab = 'hospital';
        $hospital_email = safe_input($_POST['hospital_login_email']);
        $password = $_POST['hospital_password'];

        if (empty($hospital_email) || empty($password)) {
            $loginError = "Email and Password are required.";
        } else {
            // Check hospital by email
            $sql = "SELECT * FROM hospitals WHERE email='$hospital_email' LIMIT 1";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                // Verify password
                if (password_verify($password, $row['password'])) {
                    $_SESSION['user_id'] = $row['hospital_id'];
                    $_SESSION['user_role'] = $row['role_id'];
                    $_SESSION['user_name'] = $row['hospital_name'];
                    $_SESSION['user_img'] = $row['hospital_img'];
                    $_SESSION['user_email'] = $row['email'];
                    $_SESSION['isLogin'] = true;
                    header("Location: hospital_inven.php");
                    exit();
                } else {
                    $loginError = "Invalid password.";
                }
            } else {
                $loginError = "Hospital account not found.";
            }
        }
    }

    // Admin Login
    if (isset($_POST['login_admin'])) {
        $activeTab = 'admin';
        $admin_username = safe_input($_POST['admin_login_username']);
        $password = $_POST['admin_password'];

        if (empty($admin_username) || empty($password)) {
            $loginError = "Username and Password are required.";
        } else {
            // Check admin by username and password
            $sql = "SELECT * FROM admins WHERE username='$admin_username' LIMIT 1";
            $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            if ($password == $row['password']) {
                    $_SESSION['user_id'] = $row['admin_id'];
                    $_SESSION['user_role'] = $row['role_id'];
                    $_SESSION['user_name'] = $row['username'];
                    $_SESSION['admin_name'] = $row['admin_name'];
                    $_SESSION['isLogin'] = true;
                    header("Location: admin/dashboard.php");
                    exit();
            } else {
                $loginError = "Invalid admin credentials.";
            }
        }else {
                $loginError = "Invalid admin credentials.";
            }
    }
}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Vaccining</title>
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
        

        /* Tab Active State */
        .tab-btn.active {
            background-color: #eff6ff; /* blue-50 */
            color: #0B153C; /* darkblue */
            border-color: #17C2EC; /* primary */
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
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
                        carddark: '#1E293B',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-slate-50 text-slate-800 flex flex-col min-h-screen relative overflow-x-hidden">

    <!-- Dynamic Background Decorations -->
    <div class="fixed inset-0 -z-10 overflow-hidden pointer-events-none bg-slate-50">
        
        <!-- Grid Pattern -->
        <div class="absolute inset-0 bg-grid-slate-200 [mask-image:linear-gradient(0deg,white,rgba(255,255,255,0.6))] bg-center"></div>
        
        <!-- Center Glow -->
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-full h-full max-w-[800px] max-h-[800px] bg-white/60 blur-3xl rounded-full -z-10"></div>
    </div>


    <!-- Main Content -->
    <div class="min-h-screen flex items-center justify-center px-6 pt-6 pb-10">
        
        <div class="bg-white/80 backdrop-blur-md rounded-[2.5rem] shadow-2xl border border-white/50 w-full max-w-md px-8 py-8 md:px-10 relative overflow-hidden">
            
            <!-- Glass Shine Effect -->
            <div class="absolute -top-24 -right-24 w-48 h-48 bg-gradient-to-br from-white/40 to-transparent rounded-full blur-2xl"></div>

            <div class="text-center mb-8 relative z-10">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-primary/10 text-primary mb-4 shadow-sm">
                    <i data-lucide="user-circle-2" class="w-8 h-8"></i>
                </div>
                <h1 class="text-3xl font-bold text-darkblue">Welcome Back</h1>
                <p class="text-gray-500 mt-2 text-sm">Securely login to manage vaccinations.</p>
                <?php if ($loginError): ?>
                    <div class="mt-4 p-3 rounded-xl text-sm font-bold bg-red-100 text-red-700">
                        <?php echo htmlspecialchars($loginError); ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Role Tabs -->
            <div class="flex bg-slate-100/80 p-1.5 rounded-xl mb-8 border border-slate-200 relative z-10">
                <button onclick="switchTab('parent')" id="tab-parent" class="tab-btn <?php echo $activeTab == 'parent' ? 'active' : ''; ?> flex-1 py-2.5 rounded-lg text-xs font-bold text-gray-500 transition-all duration-300 flex items-center justify-center gap-2 border border-transparent">
                    <i data-lucide="baby" class="w-4 h-4"></i> Parent
                </button>
                <button onclick="switchTab('hospital')" id="tab-hospital" class="tab-btn <?php echo $activeTab == 'hospital' ? 'active' : ''; ?> flex-1 py-2.5 rounded-lg text-xs font-bold text-gray-500 transition-all duration-300 flex items-center justify-center gap-2 border border-transparent">
                    <i data-lucide="hospital" class="w-4 h-4"></i> Hospital
                </button>
                <button onclick="switchTab('admin')" id="tab-admin" class="tab-btn <?php echo $activeTab == 'admin' ? 'active' : ''; ?> flex-1 py-2.5 rounded-lg text-xs font-bold text-gray-500 transition-all duration-300 flex items-center justify-center gap-2 border border-transparent">
                    <i data-lucide="shield-check" class="w-4 h-4"></i> Admin
                </button>
            </div>

            <!-- Forms Container -->
            <div class="relative z-10 min-h-[300px]">
                
                <!-- Parent Login Form -->
                <form id="form-parent" class="space-y-5 <?php echo $activeTab == 'parent' ? 'block' : 'hidden'; ?>" method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2 tracking-wider">Email / Phone Number</label>
                        <div class="relative group">
                            <input type="text" name="parent_login_input" placeholder="email/phone" class="w-full pl-10 pr-4 py-3 bg-white border border-gray-200 rounded-xl text-sm font-medium text-slate-800 focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all shadow-sm group-hover:border-primary/50">
                            <i data-lucide="user" class="absolute left-3 top-3.5 w-4 h-4 text-gray-400 group-hover:text-primary transition-colors"></i>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2 tracking-wider">Password</label>
                        <div class="relative group">
                            <input type="password" name="parent_password" placeholder="••••••••" class="w-full pl-10 pr-4 py-3 bg-white border border-gray-200 rounded-xl text-sm font-medium text-slate-800 focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all shadow-sm group-hover:border-primary/50">
                            <i data-lucide="lock" class="absolute left-3 top-3.5 w-4 h-4 text-gray-400 group-hover:text-primary transition-colors"></i>
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <a href="#" class="text-xs font-bold text-primary hover:underline">Forgot Password?</a>
                    </div>
                    <button type="submit" name="login_parent" class="w-full py-3.5 bg-primary text-white rounded-xl font-bold shadow-lg shadow-blue-900/20 hover:bg-cyan-500 hover:shadow-cyan-500/30 transition-all duration-300 flex items-center justify-center gap-2 transform active:scale-95">
                        Login as Parent <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </button>
                </form>

                <!-- Hospital Login Form -->
                <form id="form-hospital" class="space-y-5 <?php echo $activeTab == 'hospital' ? 'block' : 'hidden'; ?>" method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2 tracking-wider">Email</label>
                        <div class="relative group">
                            <input type="email" name="hospital_login_email" placeholder="Example@gmail.com" class="w-full pl-10 pr-4 py-3 bg-white border border-gray-200 rounded-xl text-sm font-medium text-slate-800 focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all shadow-sm group-hover:border-primary/50">
                            <i data-lucide="building-2" class="absolute left-3 top-3.5 w-4 h-4 text-gray-400 group-hover:text-primary transition-colors"></i>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2 tracking-wider">Password</label>
                        <div class="relative group">
                            <input type="password" name="hospital_password" placeholder="••••••••" class="w-full pl-10 pr-4 py-3 bg-white border border-gray-200 rounded-xl text-sm font-medium text-slate-800 focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all shadow-sm group-hover:border-primary/50">
                            <i data-lucide="lock" class="absolute left-3 top-3.5 w-4 h-4 text-gray-400 group-hover:text-primary transition-colors"></i>
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <a href="#" class="text-xs font-bold text-primary hover:underline">Forgot Password?</a>
                    </div>
                    <button type="submit" name="login_hospital" class="w-full py-3.5 bg-darkblue text-white rounded-xl font-bold shadow-lg shadow-blue-900/20 hover:bg-slate-800 hover:shadow-slate-800/30 transition-all duration-300 flex items-center justify-center gap-2 transform active:scale-95">
                        Login as Hospital <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </button>
                </form>

                <!-- Admin Login Form -->
                <form id="form-admin" class="space-y-5 <?php echo $activeTab == 'admin' ? 'block' : 'hidden'; ?>" method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2 tracking-wider">Admin Username</label>
                        <div class="relative group">
                            <input type="text" name="admin_login_username" placeholder="ADMIN" class="w-full pl-10 pr-4 py-3 bg-white border border-gray-200 rounded-xl text-sm font-medium text-slate-800 focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all shadow-sm group-hover:border-primary/50">
                            <i data-lucide="shield" class="absolute left-3 top-3.5 w-4 h-4 text-gray-400 group-hover:text-primary transition-colors"></i>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2 tracking-wider">Password</label>
                        <div class="relative group">
                            <input type="password" name="admin_password" placeholder="••••••••" class="w-full pl-10 pr-4 py-3 bg-white border border-gray-200 rounded-xl text-sm font-medium text-slate-800 focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all shadow-sm group-hover:border-primary/50">
                            <i data-lucide="lock" class="absolute left-3 top-3.5 w-4 h-4 text-gray-400 group-hover:text-primary transition-colors"></i>
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <a href="#" class="text-xs font-bold text-primary hover:underline">Forgot Password?</a>
                    </div>
                    <button type="submit" name="login_admin" class="w-full py-3.5 bg-slate-800 text-white rounded-xl font-bold shadow-lg shadow-slate-900/20 hover:bg-slate-900 hover:shadow-slate-900/30 transition-all duration-300 flex items-center justify-center gap-2 transform active:scale-95">
                        Login to Admin Panel <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </button>
                </form>

            </div>

            <!-- Footer -->
            <div class="text-center border-t border-gray-100 relative z-10">
                <p class="text-sm text-gray-500">Don't have an account? <a href="register.php" class="text-primary font-bold hover:underline">Register Here</a></p>
            </div>

        </div>
    </div>

    <script>
        // Initialize Icons
        lucide.createIcons();

        // Tab Switching Logic with Animation Reset
        function switchTab(role) {
            // persist choice for non-POST refreshes
            try { localStorage.setItem('activeTab', role); } catch(e){}
            
            const forms = ['parent', 'hospital', 'admin'];
            
            forms.forEach(r => {
                const form = document.getElementById(`form-${r}`);
                const btn = document.getElementById(`tab-${r}`);
                
                if (r === role) {
                    form.classList.remove('hidden');
                    // Trigger reflow to restart animation
                    void form.offsetWidth; 
                    
                    btn.classList.add('active');
                } else {
                    form.classList.add('hidden');
                    
                    btn.classList.remove('active');
                    btn.classList.remove('bg-white');
                }
            });
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