<?php
ob_start();
include 'admin_partials/inc_header.php';

// 1. Get ID from URL
if (isset($_GET['id'])) {
    $child_id = $_GET['id'];

    // 2. Fetch Hospital Details
    $sql = "SELECT * FROM childrens WHERE child_id = '$child_id'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $child = mysqli_fetch_assoc($result);
    } else {
        // Redirect if not found
        header("Location: childrens.php?msg=notfound");
        exit();
    }
} else {
    header("Location: childrens.php");
    exit();
}

if (isset($_GET['id']) && isset($_GET['action']) && $_GET['action'] == 'status') {
    $hospital_id = $_GET['id'];
            $sql = "UPDATE childrens SET isActive = CASE WHEN isActive = 1 THEN 0 ELSE 1 END WHERE child_id={$child_id} LIMIT 1";
            mysqli_query($conn, $sql);
            header('Location: child_view.php?id=' . $child_id);
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
                <a href="childrens.php" class="p-2 hover:bg-gray-100 rounded-full text-gray-500 transition"><i data-lucide="arrow-left" class="w-5 h-5"></i></a>
                <h2 class="text-slate-800 font-bold text-lg">Child Details</h2>
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
                    <?php if($child['child_gender'] === 'male'){ 
                        $bgClass = 'bg-primary/40';
                    } elseif($child['child_gender'] === 'female'){ 
                        $bgClass = 'bg-pink-500/40';
                    }?>
                    <div class="w-24 h-24 rounded-3xl <?php echo $bgClass?> overflow-hidden border-4 border-white shadow-md flex items-center justify-center">
                            <i data-lucide="baby" class="w-8 h-8 text-white"></i>
                        
                    </div>
                    <div>
                        <div class="flex items-center gap-3 mb-1">
                            <h1 class="text-2xl font-bold text-darkblue"><?php echo $child['child_name']; ?></h1> 
                            
                            <?php if($child['isActive'] == 1): ?>
                                <span class="badge-active px-3 py-0.5 rounded-full text-xs font-bold uppercase tracking-wide">Active</span>
                            <?php else: ?>
                                <span class="badge-blocked px-3 py-0.5 rounded-full text-xs font-bold uppercase tracking-wide">Blocked</span>
                            <?php endif; ?>
                        </div>
                        <div class="sm:flex gap-4 mt-3">
                            <div class="flex items-center gap-1 text-xs font-bold text-darkblue bg-gray-50 px-2 py-1 rounded-lg">
                                <i data-lucide="hash" class="w-3 h-3 text-primary"></i> ID: #<?php echo $child['child_id']; ?>
                            </div>
                            <div class="flex items-center gap-1 text-xs font-bold text-darkblue bg-gray-50 px-2 py-1 rounded-lg">
                                <i data-lucide="calendar-clock" class="w-3 h-3 text-primary"></i> Chlid Added Date : <?php echo $child['create_date']; ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="flex gap-3">
                    <a href="child_view.php?action=status&id=<?php echo $child['child_id']; ?>" class="px-5 py-2.5 border border-red-200 text-red-500 rounded-xl text-sm font-bold hover:bg-red-50 transition flex items-center gap-2">
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
                            <?php 
                            $sql = "SELECT * FROM parents WHERE parent_id = '$child[parent_id]'";
                            $result = mysqli_query($conn, $sql);
                            $parent = mysqli_fetch_assoc($result);
                            ?>
                <div class="grid grid-cols-1 sm:grid-cols-1 gap-4">
                    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center"><i data-lucide="user-star" class="w-4 h-4"></i></div>
                            <span class="text-xs text-darkblue font-medium sm:font-bold uppercase">Parent ID #<?php echo $parent['parent_id']; ?></span>
                            <div class="flex items-center gap-1 text-xs font-medium sm:font-bold text-slate-600 bg-gray-50 px-2 py-1 rounded-lg">
                                <i data-lucide="calendar-clock" class="w-3 h-3 text-primary"></i> Registration Date : <?php echo $parent['create_date']; ?>
                            </div>
                        </div>
                        <h3 class="ml-4 sm:ml-10 text-md md:text-xl font-bold text-slate-800">
                            <?php echo $parent['fname']; ?>
                        </h3>
                        <p class="ml-4 sm:ml-10 flex items-center gap-2 text-sm font-semibold text-darkblue mt-2">
                            <i data-lucide="phone" class="w-3 h-3 text-primary"></i> <?php echo $parent['phone']; ?>
                        </p>
                        <p class="ml-4 sm:ml-10 flex items-center gap-2 text-sm font-semibold text-darkblue mt-2">
                            <i data-lucide="mail" class="w-3 h-3 text-primary"></i> <?php echo $parent['email']; ?>
                        </p>
                        <a href="parent_view.php?id=<?php echo $parent['parent_id']; ?>" class="w-24 px-5 py-2.5 mt-2 md:float-right md:-mt-10 border border-primary text-primary rounded-xl text-sm font-bold hover:bg-primary/20 transition flex items-center gap-2">
                            <i data-lucide="view" class="w-4 h-4"></i> view
                        </a>
                    </div>
                </div>

                <!-- Contact & General Info -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <h3 class="text-lg font-bold text-darkblue mb-6 flex items-center gap-2">
                        <i data-lucide="info" class="w-5 h-5 text-primary"></i> General Information
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-x-8 gap-y-6">
                        <div>
                            <label class="text-[10px] text-darkblue font-bold uppercase tracking-wider block mb-1">Father Name</label>
                            <p class="text-sm font-bold text-darkblue"><?php echo $child['father_name']; ?></p>
                        </div>
                        <div>
                            <label class="text-[10px] text-darkblue font-bold uppercase tracking-wider block mb-1">Mother Name</label>
                            <p class="text-sm font-bold text-darkblue"><?php echo $child['mother_name']; ?></p>
                        </div>
                        <div>
                            <label class="text-[10px] text-darkblue font-bold uppercase tracking-wider block mb-1">Child DOB</label>
                            <p class="text-sm font-bold text-darkblue"><?php echo $child['child_dob']; ?></p>
                        </div>
                        <div>
                            <label class="text-[10px] text-darkblue font-bold uppercase tracking-wider block mb-1">Child Gender</label>
                            <p class="text-sm font-bold text-darkblue"><?php echo $child['child_gender']; ?></p>
                        </div>
                        <div>
                            <label class="text-[10px] text-darkblue font-bold uppercase tracking-wider block mb-1">Bform/CRC NO</label>
                            <p class="text-sm font-bold text-darkblue"><?php echo $child['bf_crc_no']; ?></p>
                        </div>
                        <div>
                            <label class="text-[10px] text-darkblue font-bold uppercase tracking-wider block mb-1">Place of Birth</label>
                            <p class="text-sm font-bold text-darkblue"><?php echo $child['child_pob']; ?></p>
                        </div>
                        <div>
                            <label class="text-[10px] text-darkblue font-bold uppercase tracking-wider block mb-1">BLood Group</label>
                            <p class="text-sm font-bold text-darkblue"><?php echo $child['child_bloodg']; ?></p>
                        </div>
                        <div class="md:col-span-1">
                            <label class="text-[10px] text-darkblue font-bold uppercase tracking-wider block mb-1">Child Allergic</label>
                            <p class="text-sm font-bold text-darkblueleading-relaxed">
                                <?php echo $child['child_allergic'] ?>
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