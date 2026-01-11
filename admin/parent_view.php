<?php
ob_start();
include 'admin_partials/inc_header.php';

// 1. Get ID from URL
if (isset($_GET['id'])) {
    $parent_id = $_GET['id'];

    // 2. Fetch Parent Details
    $sql = "SELECT * FROM parents WHERE parent_id = '$parent_id'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $parent = mysqli_fetch_assoc($result);
    } else {
        header("Location: parents.php?msg=notfound");   
        exit();
    }

    // 3. Fetch Children Details associated with this Parent
    $child_sql = "SELECT * FROM childrens WHERE parent_id = '$parent_id'";
    $child_result = mysqli_query($conn, $child_sql);
    $total_children = mysqli_num_rows($child_result);

} else {
    header("Location: parents.php");
    exit();
}

// 4. Status Toggle Logic (Block/Active)
if (isset($_GET['id']) && isset($_GET['action']) && $_GET['action'] == 'status') {
    $p_id = $_GET['id'];
    // Assuming 'isActive' column exists in parents table. If not, change column name.
    $sql = "UPDATE parents SET isActive = CASE WHEN isActive = 1 THEN 0 ELSE 1 END WHERE parent_id={$p_id} LIMIT 1";
    mysqli_query($conn, $sql);
    header('Location: parent_view.php?id=' . $p_id);
    exit;
}
?>

<style>
    /* Status Badges */
    .badge-active { background-color: #D1FAE5; color: #059669; border: 1px solid #A7F3D0; }
    .badge-blocked { background-color: #FEE2E2; color: #DC2626; border: 1px solid #FECACA; }
    
    /* Child Card Hover Effect */
    .child-card:hover { transform: translateY(-2px); }
</style>

<main class="relative lg:ml-64">
    
    <div class="sticky top-6 z-30 px-6 lg:px-10 mb-8 hidden lg:block">
        <div class="bg-white/80 backdrop-blur-xl border border-white/60 rounded-full px-6 py-3 shadow-sm flex justify-between items-center">
            <div class="flex items-center gap-3">
                <a href="parents.php" class="p-2 hover:bg-gray-100 rounded-full text-gray-500 transition"><i data-lucide="arrow-left" class="w-5 h-5"></i></a>
                <h2 class="text-slate-800 font-bold text-lg">Parent Details</h2>
            </div>
            
            <div class="flex items-center gap-6 pr-1">
              <div class="flex items-center gap-3 pl-6 border-l border-gray-200">
                    <div class="text-right hidden md:block">
                        <div class="text-xs font-bold text-slate-800"><?php echo $_SESSION['admin_name']; ?></div>
                        <div class="text-left text-[10px] text-slate-500 uppercase"><?php echo $_SESSION['user_name']; ?></div>
                    </div>
                    <div class="w-9 h-9 rounded-full bg-darkblue text-white flex items-center justify-center font-bold text-sm shadow-md">
                        <?php echo strtoupper(substr($_SESSION['admin_name'], 0, 2)); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="px-6 lg:px-10 pb-10 space-y-6 fade-in">
        
        <div class="bg-white rounded-2xl p-8 border border-gray-100 shadow-sm relative overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 bg-primary/5 rounded-full blur-3xl -mr-20 -mt-20"></div>
            
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 relative z-10">
                <div class="flex items-center gap-6">
                    <div class="w-24 h-24 rounded-3xl bg-blue-100 overflow-hidden border-4 border-white shadow-md flex items-center justify-center">
                        <?php if(!empty($parent['parent_img'])): ?>
                            <img src="uploads/<?php echo $parent['parent_img']; ?>" class="w-full h-full object-cover">
                        <?php else: ?>
                            <i data-lucide="user" class="w-8 h-8 text-gray-400"></i>
                        <?php endif; ?>
                    </div>
                    <div>
                        <div class="flex items-center gap-3 mb-1">
                            <h1 class="text-2xl font-bold text-darkblue"><?php echo $parent['fname']; ?></h1> 
                            
                            <?php if($parent['isActive'] == 1): ?>
                                <span class="badge-active px-3 py-0.5 rounded-full text-xs font-bold uppercase tracking-wide">Active</span>
                            <?php else: ?>
                                <span class="badge-blocked px-3 py-0.5 rounded-full text-xs font-bold uppercase tracking-wide">Blocked</span>
                            <?php endif; ?>
                        </div>
                        <div class="flex gap-4 mt-3">
                            <div class="flex items-center gap-1 text-xs font-bold text-slate-600 bg-gray-50 px-2 py-1 rounded-lg">
                                <i data-lucide="hash" class="w-3 h-3 text-primary"></i> ID: #<?php echo $parent['parent_id']; ?>
                            </div>
                            <div class="flex items-center gap-1 text-xs font-bold text-slate-600 bg-gray-50 px-2 py-1 rounded-lg">
                                <i data-lucide="calendar" class="w-3 h-3 text-primary"></i> Joined: <?php echo date('M d, Y', strtotime($parent['create_date'])); ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="flex gap-3">
                    <a href="parent_view.php?action=status&id=<?php echo $parent['parent_id']; ?>" class="px-5 py-2.5 border border-red-200 text-red-500 rounded-xl text-sm font-bold hover:bg-red-50 transition flex items-center gap-2">
                        <i data-lucide="ban" class="w-4 h-4"></i> <?php echo ($parent['isActive'] == 1) ? 'Block Parent' : 'Unblock Parent'; ?>
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <div class="lg:col-span-1 space-y-6">
                
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shadow-inner">
                            <i data-lucide="baby" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <span class="text-xs text-gray-400 font-bold uppercase tracking-wider">Total Children</span>
                            <h3 class="text-2xl font-bold text-slate-800 leading-none mt-1"><?php echo $total_children; ?></h3>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 h-fit">
                    <h3 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2">
                        <i data-lucide="info" class="w-5 h-5 text-primary"></i> Parent Details
                    </h3>
                    
                    <div class="space-y-5">
                        <div class="pb-4 border-b border-gray-50">
                            <label class="text-[10px] text-gray-400 font-bold uppercase tracking-wider block mb-1">Email Address</label>
                            <p class="text-sm font-bold text-slate-700 break-all"><?php echo $parent['email']; ?></p>
                        </div>
                        <div class="pb-4 border-b border-gray-50">
                            <label class="text-[10px] text-gray-400 font-bold uppercase tracking-wider block mb-1">Phone Number</label>
                            <p class="text-sm font-bold text-slate-700"><?php echo $parent['phone']; ?></p>
                        </div>
                        <div class="pb-4 border-b border-gray-50">
                            <label class="text-[10px] text-gray-400 font-bold uppercase tracking-wider block mb-1">CNIC / ID Number</label>
                            <p class="text-sm font-bold text-slate-700"><?php echo $parent['cnic']; ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 min-h-full">
                    <h3 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2">
                        <i data-lucide="baby" class="w-5 h-5 text-primary"></i> Registered Children
                    </h3>

                    <?php if($total_children > 0): ?>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <?php while($child = mysqli_fetch_assoc($child_result)): 
                                // Calculate Age roughly
                                $dob = new DateTime($child['child_dob']);
                                $now = new DateTime();
                                $interval = $now->diff($dob);
                                $age = $interval->y . "y " . $interval->m . "m";
                            ?>
                                <div class="child-card bg-slate-50 rounded-xl p-4 border border-gray-100 transition duration-300 flex items-center gap-4">
                                    <div class="w-14 h-14 rounded-full bg-white border-2 border-white shadow-sm flex-shrink-0 flex items-center justify-center overflow-hidden">
                                        <?php if(!empty($child['child_img'])): ?>
                                            <img src="uploads/<?php echo $child['child_img']; ?>" class="w-full h-full object-cover">
                                        <?php else: ?>
                                            <?php if(strtolower($child['child_gender']) == 'female'): ?>
                                                <i data-lucide="baby" class="w-6 h-6 text-pink-400"></i>
                                            <?php else: ?>
                                                <i data-lucide="baby" class="w-6 h-6 text-blue-400"></i>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="flex-grow">
                                        <h4 class="font-bold text-slate-800 text-sm"><?php echo $child['child_name']; ?></h4>
                                        <div class="flex items-center gap-2 text-xs text-gray-500 mt-1">
                                            <span class="bg-white px-2 py-0.5 rounded shadow-sm border border-gray-100 font-medium">
                                                <?php echo ucfirst($child['child_gender']); ?>
                                            </span>
                                            <span>•</span>
                                            <span><?php echo $age; ?> old</span>
                                        </div>
                                    </div>

                                    <a href="child_view.php?id=<?php echo $child['child_id']; ?>" class="p-2 bg-white rounded-lg text-gray-400 hover:text-primary hover:shadow-md transition">
                                        <i data-lucide="chevron-right" class="w-4 h-4"></i>
                                    </a>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-10 bg-slate-50 rounded-xl border border-dashed border-gray-200">
                            <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center mx-auto mb-3 shadow-sm">
                                <i data-lucide="baby" class="w-5 h-5 text-gray-300"></i>
                            </div>
                            <p class="text-gray-500 font-medium text-sm">No children registered yet.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>

    </div>
</main>

<?php
ob_end_flush();
include 'admin_partials/inc_footer.php';
?>