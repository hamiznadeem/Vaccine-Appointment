<?php
require '../database/db_conn.php';
ob_start();
session_start();
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$user_role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;

// --- Authentication Check ---
if (!isset($_SESSION['isLogin'])) {
    header('Location: ../login.php');
    exit;
} elseif ($user_role != 1 && $user_role != 2) {
    header('Location: ../index.php');
    exit;
}

// current page filename to highlight active nav link
$current_page = basename($_SERVER['SCRIPT_NAME']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Vaccining</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts: Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

                /* Custom Scrollbar Styling */
        .overflow-x-auto::-webkit-scrollbar {
            height: 3px;
            background-color: transparent;
        }

        .overflow-x-auto::-webkit-scrollbar-track {
            background-color: #f1f5f9;
            border-radius: 10px;
        }

        .overflow-x-auto::-webkit-scrollbar-thumb {
            background-color: #04216bff;
            border-radius: 10px;
        }

        .overflow-x-auto::-webkit-scrollbar-thumb:hover {
            background-color: #0a1e5f;
        }

        /* Firefox */
        .overflow-x-auto {
            scrollbar-color: #04216bff #f1f5f9;
            scrollbar-width: thin;
        }
        
        /* Sidebar Transition */
        .sidebar {
            transition: transform 0.3s ease-in-out;
        }
        
        .fade-in {
            animation: fadeIn 0.5s ease-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Status Badges */
        .badge-pending { background-color: #FEF3C7; color: #D97706; } /* Yellow */
        .badge-approved { background-color: #D1FAE5; color: #059669; } /* Green */
        .badge-rejected { background-color: #FEE2E2; color: #DC2626; } /* Red */

        
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
<body class="bg-slate-50 text-slate-800 ">

    <!-- Mobile Header -->
    <div class="lg:hidden fixed top-0 left-0 right-0 z-50 bg-white border-b border-gray-200 px-6 py-4 flex justify-between items-center">
        <div class="text-darkblue text-xl font-bold tracking-tight">Vaccining Admin</div>
        <button onclick="toggleSidebar()" class="text-slate-600 focus:outline-none">
            <i data-lucide="menu" class="w-6 h-6"></i>
        </button>
    </div>

    <!-- Main Wrapper -->
    <div class="min-h-screen pt-16 lg:pt-0">

        <!-- Sidebar -->
        <aside id="sidebar" class="sidebar fixed inset-y-0 left-0 z-[60] w-64 bg-darkblue text-white transform -translate-x-full lg:translate-x-0 overflow-y-auto no-scrollbar flex flex-col justify-between transition-transform duration-300 ease-in-out">
            <div>
                <!-- Brand -->
                <div class="p-8">
                    <div class="text-2xl font-bold tracking-wide flex items-center gap-2">
                        <i data-lucide="shield" class="w-8 h-8 text-primary"></i>
                        Vaccining
                    </div>
                    <div class="text-xs text-blue-300 mt-1 uppercase tracking-wider font-semibold">Admin Panel</div>
                </div>

                <!-- Navigation -->
                <nav class="px-4 space-y-2">
                    <a href="dashboard.php" class="<?php echo $current_page === 'dashboard.php' ? 'flex items-center gap-3 px-4 py-3 bg-primary/10 text-primary rounded-xl font-medium transition' : 'flex items-center gap-3 px-4 py-3 text-gray-400 hover:bg-white/5 hover:text-white rounded-xl font-medium transition'; ?>">
                        <i data-lucide="layout-dashboard" class="w-5 h-5"></i> Dashboard
                    </a>
                    <a href="hospitals.php" class="<?php echo $current_page === 'hospitals.php' ? 'flex items-center gap-3 px-4 py-3 bg-primary/10 text-primary rounded-xl font-medium transition' : 'flex items-center gap-3 px-4 py-3 text-gray-400 hover:bg-white/5 hover:text-white rounded-xl font-medium transition'; ?>">
                        <i data-lucide="hospital" class="w-5 h-5"></i> Hospitals
                    </a>
                    <a href="childrens.php" class="<?php echo $current_page === 'childrens.php' ? 'flex items-center gap-3 px-4 py-3 bg-primary/10 text-primary rounded-xl font-medium transition' : 'flex items-center gap-3 px-4 py-3 text-gray-400 hover:bg-white/5 hover:text-white rounded-xl font-medium transition'; ?>">
                        <i data-lucide="baby" class="w-5 h-5"></i> Childrens
                    </a>
                    <a href="parents.php" class="<?php echo $current_page === 'parents.php' ? 'flex items-center gap-3 px-4 py-3 bg-primary/10 text-primary rounded-xl font-medium transition' : 'flex items-center gap-3 px-4 py-3 text-gray-400 hover:bg-white/5 hover:text-white rounded-xl font-medium transition'; ?>">
                        <i data-lucide="users" class="w-5 h-5"></i> Parents
                    </a>
                    <a href="appointments.php" class="<?php echo $current_page === 'appointments.php' ? 'flex items-center gap-3 px-4 py-3 bg-primary/10 text-primary rounded-xl font-medium transition' : 'flex items-center gap-3 px-4 py-3 text-gray-400 hover:bg-white/5 hover:text-white rounded-xl font-medium transition'; ?>">
                        <i data-lucide="calendar-check" class="w-5 h-5"></i> Appointments
                    </a>
                    <a href="vaccines.php" class="<?php echo $current_page === 'vaccines.php' ? 'flex items-center gap-3 px-4 py-3 bg-primary/10 text-primary rounded-xl font-medium transition' : 'flex items-center gap-3 px-4 py-3 text-gray-400 hover:bg-white/5 hover:text-white rounded-xl font-medium transition'; ?>">
                        <i data-lucide="flask-conical" class="w-5 h-5"></i> Vaccines
                    </a>
                    <a href="inquiries.php" class="<?php echo $current_page === 'inquiries.php' ? 'flex items-center gap-3 px-4 py-3 bg-primary/10 text-primary rounded-xl font-medium transition' : 'flex items-center gap-3 px-4 py-3 text-gray-400 hover:bg-white/5 hover:text-white rounded-xl font-medium transition'; ?>">
                        <i data-lucide="message-square-more" class="w-5 h-5"></i> Inquiries
                    </a>
                    <?php if($user_role == 1) ?>
                    <a href="admins.php" class="<?php echo $current_page === 'admins.php' ? 'flex items-center gap-3 px-4 py-3 bg-primary/10 text-primary rounded-xl font-medium transition' : 'flex items-center gap-3 px-4 py-3 text-gray-400 hover:bg-white/5 hover:text-white rounded-xl font-medium transition'; ?>">
                        <i data-lucide="shield-user" class="w-5 h-5"></i> Admins
                    </a>
                </nav>
            </div>

            <!-- Profile / Logout -->
            <div class="p-4 border-t border-white/10">
                <a href="../logout.php" class="flex items-center gap-3 px-4 py-3 text-red-400 hover:bg-red-500/10 rounded-xl font-medium transition">
                    <i data-lucide="log-out" class="w-5 h-5"></i> Logout
                </a>
            </div>
        </aside>

        <!-- Overlay for Mobile Sidebar -->
        <div id="sidebar-overlay" onclick="toggleSidebar()" class="fixed inset-0 bg-black/50 z-[55] hidden lg:hidden"></div>
