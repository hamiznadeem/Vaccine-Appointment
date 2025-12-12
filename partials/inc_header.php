<?php
ob_start();
session_start();
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$user_role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;
$user_img = isset($_SESSION['user_img']) ? $_SESSION['user_img'] : null;

require './database/db_conn.php';

// determine current page for active nav highlighting
$current_page = basename($_SERVER['SCRIPT_NAME']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?></title>
    <!-- Favicon  -->
    <link rel="apple-touch-icon" sizes="180x180" href="./asset/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="./asset/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="./asset/favicon/favicon-16x16.png">
    <link rel="icon" type="ico"  href="asset/favicon/favicon.ico">
    <!-- main css files -->
    <link rel="stylesheet" href="./asset/css/base.css">
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <!-- Google Fonts: Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        darkblue: '#0B153C', // The main dark navy color
                        primary: '#17C2EC', // The bright cyan color
                        carddark: '#1E293B'
                    },
                    boxShadow: {
                        'glow': '0 0 20px rgba(23, 194, 236, 0.5)',
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-slate-50 text-slate-800 overflow-x-hidden flex flex-col min-h-screen relative">

    <!-- Navbar -->
    <nav class="fixed top-6 left-0 right-0 z-50 flex justify-center px-4 md:px-6">
        <div
            class="bg-indigo-50/90 backdrop-blur-xl border border-white/60 rounded-full px-4 md:px-6 py-3 shadow-2xl w-full max-w-6xl flex justify-between items-center">
            <a href="index.php"
                class="<?php echo $current_page === 'index.php' ? 'text-primary text-md md:text-xl font-bold tracking-tight pl-2' : 'text-darkblue text-md md:text-xl font-bold tracking-tight pl-2 hover:text-primary transition'; ?>" <?php if ($current_page === 'index.php') echo 'aria-current="page"'; ?>>Vaccining</a>

            <?php if (isset($_SESSION['isLogin']) && $_SESSION['isLogin'] === true) {

                if ($user_role == 4) { ?>
                    <div class="hidden md:flex items-center space-x-2 text-sm font-medium">
                        <a href="parents_hospitals.php" class="<?php echo $current_page === 'parents_hospitals.php' ? 'flex items-center gap-2 bg-primary/5 py-2 px-3 rounded-full text-primary font-semibold transition ease-in-out' : 'flex items-center gap-2 text-slate-600 hover:text-primary py-2 px-3 rounded-full transition'; ?>" <?php if ($current_page === 'parents_hospitals.php') echo 'aria-current="page"'; ?>><i data-lucide="hospital"
                                class="w-4 h-4"></i> Hospitals</a>
                        <a href="parents_vaccines.php" class="<?php echo $current_page === 'parents_vaccines.php' ? 'flex items-center gap-2 bg-primary/5 py-2 px-3 rounded-full text-primary font-semibold transition ease-in-out' : 'flex items-center gap-2 text-slate-600 hover:text-primary py-2 px-3 rounded-full transition'; ?>" <?php if ($current_page === 'parents_vaccines.php') echo 'aria-current="page"'; ?>><i data-lucide="syringe"
                                class="w-4 h-4"></i> Vaccines</a>
                        <a href="parents_appointments.php" class="<?php echo $current_page === 'parents_appointments.php' ? 'flex items-center gap-2 bg-primary/5 py-2 px-3 rounded-full text-primary font-semibold transition ease-in-out' : 'flex items-center gap-2 text-slate-600 hover:text-primary py-2 px-3 rounded-full transition'; ?>" <?php if ($current_page === 'parents_appointments.php') echo 'aria-current="page"'; ?>><i data-lucide="calendar"
                                class="w-4 h-4"></i>Appointments</a>
                        <a href="contact.php" class="<?php echo $current_page === 'contact.php' ? 'flex items-center gap-2 bg-primary/5 py-2 px-3 rounded-full text-primary font-semibold transition ease-in-out' : 'flex items-center gap-2 text-slate-600 hover:text-primary py-2 px-3 rounded-full transition'; ?>" <?php if ($current_page === 'contact.php') echo 'aria-current="page"'; ?>><i data-lucide="headset"
                                class="w-4 h-4"></i> Contact Us</a>
                    </div>
                <?php } elseif ($_SESSION['user_role'] == 3) { ?>
                    <div class="hidden md:flex items-center space-x-2 text-sm font-medium">
                        <a href="hospital_inven.php" class="<?php echo $current_page === 'hospital_inven.php' ? 'flex items-center gap-2 bg-primary/5 py-2 px-3 rounded-full text-primary font-semibold transition ease-in-out' : 'flex items-center gap-2 text-slate-600 hover:text-primary py-2 px-3 rounded-full transition'; ?>" <?php if ($current_page === 'hospitals.php') echo 'aria-current="page"'; ?>>
                            <i data-lucide="archive"
                                class="w-4 h-4"></i> Inventory</a>
                        <a href="hospital_appointment.php" class="<?php echo $current_page === 'hospital_appointment.php' ? 'flex items-center gap-2 bg-primary/5 py-2 px-3 rounded-full text-primary font-semibold transition ease-in-out' : 'flex items-center gap-2 text-slate-600 hover:text-primary py-2 px-3 rounded-full transition'; ?>" <?php if ($current_page === 'hospitals.php') echo 'aria-current="page"'; ?>>
                            <i data-lucide="calendar-check"
                                class="w-4 h-4"></i> Appointments</a>
                        <a href="contact.php" class="<?php echo $current_page === 'contact.php' ? 'flex items-center gap-2 bg-primary/5 py-2 px-3 rounded-full text-primary font-semibold transition ease-in-out' : 'flex items-center gap-2 text-slate-600 hover:text-primary py-2 px-3 rounded-full transition'; ?>" <?php if ($current_page === 'hospitals.php') echo 'aria-current="page"'; ?>>
                            <i data-lucide="headset"
                                class="w-4 h-4"></i> Contact Us</a>
                    </div>
                <?php }
            } else { ?>
                <!-- Login Button -->
                <div class="items-center gap-6 pl-1">
                    <a href="login.php"
                        class="text-xs px-4 ml-3 md:px-6 py-2.5 font-bold text-darkblue hover:text-primary tracking-wider uppercase transition duration-200">Login</a>
                    <!-- Register Button -->
                    <a href="register.php"
                        class="bg-darkblue text-white px-4 ml-3 md:px-6 py-2.5 rounded-lg font-bold text-xs tracking-wider shadow-lg hover:bg-primary transition uppercase transform duration-200 hover:-translate-y-0.5">
                        Register
                    </a>
                </div>
            <?php }

            if (isset($_SESSION['isLogin']) && $_SESSION['isLogin'] == true) { ?>
                <div class="flex items-center gap-4 ml-[100px]">

                    <div class=" dropdown dropdown-end">
                        <div tabindex="0" role="button" class="btn btn-ghost btn-circle avatar">
                            <div class="flex items-center justify-center bg-primary w-10 rounded-full">
                                <?php if ($_SESSION['user_role'] == 3) { 
                                    if(!$user_img == null){
                                    ?>
                                    <img src="./asset/images/<?php echo $user_img ?>" alt="Hospital image" class="w-full h-full rounded-full object-cover">
                                    <?php }else{?>
                                    <i data-lucide="hospital" class="w-4 h-4 text-white"></i>
                                    <?php } ?>
                                <?php } elseif ($_SESSION['user_role'] == 4) { ?>
                                    <i data-lucide="user" class="w-4 h-4 text-white"></i>
                                <?php } ?>
                            </div>
                        </div>
                        <?php if ($_SESSION['user_role'] == 3) { ?>
                            <ul tabindex="-1"
                                class="menu menu-md dropdown-content bg-slate-50 backdrop-blur-xl rounded-box z-1 mt-3 w-52 p-2 shadow">
                                <li class="rounded hover:bg-primary hover:text-slate-50 transition duration-200 ease-in-out">
                                    <a href="hospital_profile.php">
                                        <i data-lucide="user" class="w-4 h-4"></i>
                                        Profile
                                    </a>
                                </li>
                                <li class="rounded hover:bg-red-500 hover:text-slate-50 transition duration-200 ease-in-out">
                                    <a href="logout.php">
                                        <i data-lucide="log-out" class="w-4 h-4"></i>
                                        Logout
                                    </a>
                                </li>
                            </ul>
                        <?php } elseif ($_SESSION['user_role'] == 4) { ?>
                            <ul tabindex="-1"
                                class="menu menu-md dropdown-content bg-slate-50 backdrop-blur-xl rounded-box z-1 mt-3 w-52 p-2 shadow">
                                <li class="rounded hover:bg-primary hover:text-slate-50 transition duration-200 ease-in-out">
                                    <a href="parents_profile.php">
                                        <i data-lucide="user" class="w-4 h-4"></i>
                                        Profile
                                    </a>
                                </li>
                                <li class="rounded hover:bg-red-500 hover:text-slate-50 transition duration-200 ease-in-out">
                                    <a href="logout.php">
                                        <i data-lucide="log-out" class="w-4 h-4"></i>
                                        Logout
                                    </a>
                                </li>
                            </ul>
                        <?php } ?>

                    </div>

                    <!-- Mobile Menu Button -->
                    <button id="mobile-menu-btn" class="md:hidden text-slate-800 p-2 focus:outline-none"
                        onclick="openMenu()">
                        <i data-lucide="menu" class="w-6 h-6"></i>
                    </button>
                </div>
            <?php } ?>
        </div>
    </nav>

    <!-- Mobile Menu Overlay (Adjusted z-index to cover nav) -->
    <div id="mobile-menu"
        class="fixed inset-0 bg-darkblue z-[60] hidden flex-col justify-center items-center space-y-8 text-white text-xl font-medium fade-in lg:hidden">
        <div class="absolute top-6 left-6 text-white text-2xl font-bold tracking-wide">Vaccining</div>
        <button class="absolute top-6 right-6 text-white p-2 focus:outline-none hover:text-primary transition"
            onclick="closeMenu()">
            <i data-lucide="x" class="w-10 h-10"></i>
        </button>
        <a href="#" class="hover:text-primary flex items-center gap-2" onclick="closeMenu()"><i data-lucide="hospital"
                class="w-4 h-4"></i> Hospitals</a>
        <a href="#" class="hover:text-primary flex items-center gap-2" onclick="closeMenu()"><i data-lucide="calendar"
                class="w-4 h-4"></i> My Booking</a>
    </div>