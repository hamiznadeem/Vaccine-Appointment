<?php
ob_start();
include 'admin_partials/inc_header.php';

// 1. Get ID from URL
if (isset($_GET['id'])) {
    $hospital_id = $_GET['id'];

    // 2. Fetch Hospital Details
    $sql = "SELECT * FROM hospitals WHERE hospital_id = '$hospital_id'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $hospital = mysqli_fetch_assoc($result);
    } else {
        // Redirect if not found
        header("Location: hospitals.php?msg=notfound");
        exit();
    }
} else {
    header("Location: hospitals.php");
    exit();
}

if (isset($_GET['id']) && isset($_GET['action']) && $_GET['action'] == 'status') {
    $hospital_id = $_GET['id'];
            $sql = "UPDATE hospitals SET isActive = CASE WHEN isActive = 1 THEN 0 ELSE 1 END WHERE hospital_id={$hospital_id} LIMIT 1";
            mysqli_query($conn, $sql);
            header('Location: hospital_view.php?id=' . $hospital_id);
            exit;
    }


?>

<style>
    /* Status Badges */
    .badge-active { background-color: #D1FAE5; color: #059669; border: 1px solid #A7F3D0; }
    .badge-blocked { background-color: #FEE2E2; color: #DC2626; border: 1px solid #FECACA; }
</style>

<!-- Main Content -->
<main class="relative lg:ml-64">
    
    <!-- Navbar -->
    <div class="sticky top-6 z-30 px-6 lg:px-10 mb-8 hidden lg:block">
        <div class="bg-white/80 backdrop-blur-xl border border-white/60 rounded-full px-6 py-3 shadow-sm flex justify-between items-center">
            <div class="flex items-center gap-3">
                <a href="hospitals.php" class="p-2 hover:bg-gray-100 rounded-full text-gray-500 transition"><i data-lucide="arrow-left" class="w-5 h-5"></i></a>
                <h2 class="text-slate-800 font-bold text-lg">Hospital Details</h2>
            </div>
            
            <div class="flex items-center gap-6 pr-1">
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
        
        <!-- Main Header Card -->
        <div class="bg-white rounded-2xl p-8 border border-gray-100 shadow-sm relative overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 bg-primary/5 rounded-full blur-3xl -mr-20 -mt-20"></div>
            
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 relative z-10">
                <div class="flex items-center gap-6">
                    <div class="w-24 h-24 rounded-3xl bg-blue-100 overflow-hidden border-4 border-white shadow-md flex items-center justify-center">
                        <?php if(!empty($hospital['hospital_img'])): ?>
                            <img src="uploads/<?php echo $hospital['hospital_img']; ?>" class="w-full h-full object-cover">
                        <?php else: ?>
                            <i data-lucide="image" class="w-8 h-8 text-gray-400"></i>
                        <?php endif; ?>
                    </div>
                    <div>
                        <div class="flex items-center gap-3 mb-1">
                            <h1 class="text-2xl font-bold text-darkblue"><?php echo $hospital['hospital_name']; ?></h1> 
                            
                            <?php if($hospital['isActive'] == 1): ?>
                                <span class="badge-active px-3 py-0.5 rounded-full text-xs font-bold uppercase tracking-wide">Active</span>
                            <?php else: ?>
                                <span class="badge-blocked px-3 py-0.5 rounded-full text-xs font-bold uppercase tracking-wide">Blocked</span>
                            <?php endif; ?>
                        </div>
                        <p class="text-sm text-gray-500 flex items-center gap-1">
                            <i data-lucide="map-pin" class="w-3 h-3"></i> <?php echo $hospital['hospital_address']; ?> 
                        </p>
                        <div class="flex gap-4 mt-3">
                            <div class="flex items-center gap-1 text-xs font-bold text-slate-600 bg-gray-50 px-2 py-1 rounded-lg">
                                <i data-lucide="hash" class="w-3 h-3 text-primary"></i> ID: #<?php echo $hospital['hospital_id']; ?>
                            </div>
                            <div class="flex items-center gap-1 text-xs font-bold text-slate-600 bg-gray-50 px-2 py-1 rounded-lg">
                                <i data-lucide="calendar-clock" class="w-3 h-3 text-primary"></i> Registration Date : <?php echo $hospital['create_date']; ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="flex gap-3">
                    <a href="hospital_view.php?action=status&id=<?php echo $hospital['hospital_id']; ?>" class="px-5 py-2.5 border border-red-200 text-red-500 rounded-xl text-sm font-bold hover:bg-red-50 transition flex items-center gap-2">
                        <i data-lucide="ban" class="w-4 h-4"></i> Block
                    </a>
                </div>
            </div>
        </div>

        <!-- Detailed Stats & Info -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            <!-- Left: Info Grid -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Stats Row  -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center"><i data-lucide="calendar" class="w-4 h-4"></i></div>
                            <span class="text-xs text-gray-400 font-bold uppercase">Total Bookings</span>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-800">
                            <?php 
                            $sql = "SELECT COUNT(*) FROM vaccination_schedules WHERE hospital_id = '$hospital_id'";
                            $result = mysqli_query($conn, $sql);
                            $row = mysqli_fetch_array($result);
                            echo $row[0];
                            ?>
                        </h3>
                    </div>
                    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-8 h-8 rounded-lg bg-orange-50 text-orange-500 flex items-center justify-center"><i data-lucide="syringe" class="w-4 h-4"></i></div>
                            <span class="text-xs text-gray-400 font-bold uppercase">Vaccines</span>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-800">
                            <?php 
                            $sql = "SELECT COUNT(*) FROM vaccines WHERE hospital_id = '$hospital_id'";
                            $result = mysqli_query($conn, $sql);
                            $row = mysqli_fetch_array($result);
                            echo $row[0];
                            ?>
                        </h3>
                    </div>
                </div>

                <!-- Contact & General Info -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <h3 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2">
                        <i data-lucide="info" class="w-5 h-5 text-primary"></i> General Information
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                        <div>
                            <label class="text-[10px] text-gray-400 font-bold uppercase tracking-wider block mb-1">Email Address</label>
                            <p class="text-sm font-bold text-slate-700"><?php echo $hospital['email']; ?></p>
                        </div>
                        <div>
                            <label class="text-[10px] text-gray-400 font-bold uppercase tracking-wider block mb-1">Phone Number</label>
                            <p class="text-sm font-bold text-slate-700"><?php echo $hospital['phone']; ?></p>
                        </div>
                        <div>
                            <label class="text-[10px] text-gray-400 font-bold uppercase tracking-wider block mb-1">License Number</label>
                            <p class="text-sm font-bold text-slate-700"><?php echo $hospital['lc_rg_no']; ?></p>
                        </div>
                        <div>
                            <label class="text-[10px] text-gray-400 font-bold uppercase tracking-wider block mb-1">Address</label>
                            <p class="text-sm font-bold text-slate-700"><?php echo $hospital['hospital_address']; ?></p>
                        </div>
                        <div>
                            <label class="text-[10px] text-gray-400 font-bold uppercase tracking-wider block mb-1">Website</label>
                            <?php if (empty($hospital['hospital-website' === 'No website provided'])) { ?>
                                <p class="text-sm font-bold text-slate-700">No website provided</p>
                            <?php }else{ ?>
                            <a href="<?php echo $hospital['hospital-website']; ?>" class="text-sm font-bold text-slate-700"><?php echo $hospital['hospital-website']; ?></a>
                            <?php } ?>
                        </div>
                        <div class="md:col-span-1">
                            <label class="text-[10px] text-gray-400 font-bold uppercase tracking-wider block mb-1">About</label>
                            <p class="text-sm font-bold text-gray-500 leading-relaxed">
                                <?php echo $hospital['hospital_description'] ?? 'No description available.'; ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php
ob_end_flush();
include 'admin_partials/inc_footer.php';
?>