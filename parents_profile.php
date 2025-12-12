<?php
$title = 'Parent Profile';
include 'partials/inc_header.php';
$parent_id = $_SESSION['user_id'];
$user_role = $_SESSION['user_role'];
if(!isset($_SESSION['isLogin'])){
    header('Location: login.php');
    exit;
}elseif($user_role != 4){
    header('Location: index.php');
    exit;
}

    if (isset($_GET['id'], $_GET['action'])) {
    $action = $_GET['action'];
    $id = intval($_GET['id']);
    if ($id > 0) {
        if ($action === 'delete') {
            $sql = "DELETE FROM childrens WHERE child_id={$id} LIMIT 1";
            mysqli_query($conn, $sql);
            header('Location:' . $_SERVER['PHP_SELF']);
            exit;
        }
    }
}

$sql = "SELECT * FROM parents WHERE parent_id = '$parent_id' ";
$result = mysqli_query($conn, $sql);
?>
    <!-- Main Content -->
    <div class="pt-32 pb-20 px-6 md:px-20 max-w-[1400px] mx-auto flex-grow w-full fade-in">
        
        <!-- Breadcrumb -->
        <div class="flex items-center gap-2 text-sm text-gray-400 mb-6">
            <a href="index.php" class="hover:text-primary transition">Home</a>
            <i data-lucide="chevron-right" class="w-4 h-4"></i>
            <span class="text-gray-800 font-medium">Profile</span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- Left Column: Parent Info -->
            <?php $row = mysqli_fetch_assoc($result) ?>
            <div class="lg:col-span-4">
                <div class="bg-white rounded-[2.5rem] p-8 shadow-xl border border-gray-100 sticky top-32 text-center">
                    <div class="relative w-28 h-28 mx-auto mb-6">
                        <div class="w-full h-full flex items-center justify-center rounded-full bg-slate-100 p-1 border-2 border-primary/20">
                            <i data-lucide="user" class="w-12 h-12 text-primary"></i>
                        </div>
                    </div>

                    <h2 class="text-2xl font-bold text-slate-800 mb-1"><?php echo $row['fname']?></h2>
                    <p class="text-sm text-gray-500 mb-6">Parent / Guardian</p>

                    <div class="space-y-8 text-left bg-gray-50 p-5 rounded-2xl border border-gray-100">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center text-primary shadow-sm"><i data-lucide="credit-card" class="w-4 h-4"></i></div>
                            <div>
                                <p class="text-[10px] text-gray-400 uppercase font-bold">CNIC / ID</p>
                                <p class="text-sm font-bold text-slate-700"><?php echo $row['cnic']?></p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center text-primary shadow-sm"><i data-lucide="phone" class="w-4 h-4"></i></div>
                            <div>
                                <p class="text-[10px] text-gray-400 uppercase font-bold">Phone</p>
                                <p class="text-sm font-bold text-slate-700"><?php echo $row['phone']?></p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center text-primary shadow-sm"><i data-lucide="mail" class="w-4 h-4"></i></div>
                            <div>
                                <p class="text-[10px] text-gray-400 uppercase font-bold">Email</p>
                                <p class="text-sm font-bold text-slate-700"><?php echo $row['email']?></p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center text-primary shadow-sm"><i data-lucide="calendar-check" class="w-4 h-4"></i></div>
                            <div>
                                <p class="text-[10px] text-gray-400 uppercase font-bold">Registered Since</p>
                                <p class="text-sm font-bold text-slate-700"><?php echo $row['create_date']?></p>
                            </div>
                        </div>
                    </div>

                    <a href="parents_profile_edit.php?id=<?php echo $row['parent_id']?>" class="w-full mt-6 py-3 border border-gray-200 text-gray-600 rounded-xl text-xs font-bold hover:bg-gray-50 transition flex items-center justify-center gap-2">
                        <i data-lucide="edit-2" class="w-3 h-3"></i> Edit Profile
                    </a>
                </div>
            </div>

            <!-- Right Column: Children & Stats -->
            <div class="lg:col-span-8">
                
                <!-- Header -->
                <div class="flex flex-col md:flex-row justify-between items-end gap-6 mb-8">
                    <div>
                        <h1 class="text-3xl font-bold text-darkblue">My <span class="text-primary">Children</span></h1>
                        <p class="text-gray-500 mt-2 text-sm">Manage profiles and vaccination records of your children.</p>
                    </div>
                    <a href="add_child.php" class="px-6 py-3 bg-primary text-white rounded-xl font-bold shadow-lg shadow-blue-900/20 hover:bg-cyan-500 transition flex items-center gap-2 text-sm">
                        <i data-lucide="plus" class="w-4 h-4"></i> Add New Child
                    </a>
                </div>

                <!-- Stats -->
                <div class="grid grid-cols-1 gap-4 mb-8">
                    <div class="bg-white p-5 rounded-[1.5rem] border border-gray-100 flex items-center gap-4 shadow-sm">
                        <div class="w-12 h-12 bg-blue-50 text-primary rounded-xl flex items-center justify-center">
                            <i data-lucide="baby" class="w-6 h-6"></i>
                        </div>
                        <?php 
                        $sql = "SELECT * FROM childrens WHERE parent_id='$parent_id' ";
                        $child_result = mysqli_query($conn, $sql);
                        $child_count = mysqli_num_rows($child_result);
                        ?>
                        <div>
                            <h3 class="text-2xl font-bold text-slate-800"><?php echo $child_count?></h3>
                            <p class="text-xs text-gray-400 font-medium uppercase">Children Registered</p>
                        </div>
                    </div>
                </div>

                <!-- Children List -->
                <div class="space-y-6">

                    <?php
                    if(mysqli_num_rows($child_result) > 0){
                        while($child_row = mysqli_fetch_assoc($child_result)){
                        ?>
                    <!-- Child Card  -->
                    <div class="bg-white rounded-[2rem] p-6 border border-gray-100 shadow-sm hover:shadow-lg transition duration-300">
                        <div class="flex flex-col md:flex-row gap-6 items-start justify-between">
                            
                            <!-- Identity -->
                            <?php 
                                if($child_row['child_gender'] == 'male'){
                                    $bgClass = 'bg-primary/30';
                                }elseif($child_row['child_gender'] == 'female'){
                                    $bgClass = 'bg-pink-200';
                                }
                            ?>
                            <div class="flex items-center gap-4 min-w-[200px]">
                                <div class="w-16 h-16 flex items-center justify-center text-white rounded-full <?php echo $bgClass?> p-1 border-2 border-blue-50">
                                    <i data-lucide="baby" class="w-6 h-6"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-slate-800"><?php echo $child_row['child_name']?></h3>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="text-xs text-gray-500 bg-gray-100 px-2 py-0.5 rounded"><?php echo $child_row['child_gender']?></span>
                                        <span class="text-xs text-white <?php echo $bgClass?> px-2 py-0.5 rounded font-bold"><?php echo $child_row['child_dob']?></span>
                                    </div>
                                    <div class="mt-2">
                                        <span class="text-xs text-white <?php echo $bgClass?> px-2 py-0.5 rounded font-bold">BF/CRC : <?php echo $child_row['bf_crc_no']?></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Details Grid -->
                            <div class="flex-1 grid grid-cols-2 sm:grid-cols-2 gap-4 text-sm w-full">
                                <div>
                                    <p class="text-[10px] text-gray-400 uppercase font-bold mb-1">Date of Birth</p>
                                    <p class="font-bold text-slate-700"><?php echo $child_row['child_dob']?></p>
                                </div>
                                <div>
                                    <p class="text-[10px] text-gray-400 uppercase font-bold mb-1">Blood Group</p>
                                    <p class="font-bold text-slate-700"><?php echo $child_row['child_bloodg']?></p>
                                </div>
                                
                                <!-- Vaccination Status -->
                                <div class="col-span-2 sm:col-span-3 mt-2">
                                    <div class="w-full bg-gray-100 rounded-full h-2">
                                        <div class="<?php echo $bgClass ?> h-2 rounded-full" style="width: 100%"></div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Next Due -->
                            <div class="flex flex-col items-end gap-1 min-w-[120px]">
                                <div class="badge-full text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wide flex items-center gap-1">
                                    <i data-lucide="check-circle" class="w-3 h-3"></i> Completed
                                </div>
                                <p class="text-[10px] text-gray-400 mt-1 text-right">Created at <?php echo $child_row['create_date']?></p>
                            </div>
                        </div>

                        <div class="mt-6 pt-4 border-t border-gray-50 flex gap-3">
                            <a href="edit_child.php?id=<?php echo $child_row['child_id']?>" class="flex-1 px-4 py-2.5 <?php echo $bgClass?> justify-center border border-gray-200 text-gray-600 rounded-xl text-xs font-bold transition flex items-center gap-2">
                                <i data-lucide="edit" class="w-3 h-3"></i> Edit
                            </a>
                            <a href="parents_profile.php?action=delete&id=<?php echo $child_row['child_id']?>" class="flex-1 px-4 py-2.5 bg-red-100 text-red-500 justify-center border border-gray-200 text-gray-600 rounded-xl text-xs font-bold transition flex items-center gap-2">
                                <i data-lucide="trash" class="w-3 h-3"></i> Remove
                            </a>
                        </div>
                    </div>
                    <?php
                        }
                    } else {?>
                        <div>
                            <div colspan="6" class="px-6 py-12 text-center text-gray-400">
                                <i data-lucide="users" class="w-10 h-10 mx-auto mb-4"></i>
                                <div class="font-bold">No children found.</div>
                            </div>
                        </div>
                    <?php }?>
                </div>

                <!-- Recent Activity -->
                <div class="mt-10">
                    <h3 class="text-lg font-bold text-slate-800 mb-4">Recent Activity</h3>
                    <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm space-y-4">
                        <div class="flex items-start gap-3 pb-4 border-b border-gray-50">
                            <div class="w-8 h-8 bg-green-50 rounded-full flex items-center justify-center text-green-600 mt-0.5"><i data-lucide="check" class="w-4 h-4"></i></div>
                            <div>
                                <p class="text-sm font-bold text-slate-700">Vaccination Completed</p>
                                <p class="text-xs text-gray-500">Ali Khan received Pentavalent 3rd dose.</p>
                                <p class="text-[10px] text-gray-400 mt-1">20 Oct, 2024</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 bg-blue-50 rounded-full flex items-center justify-center text-blue-600 mt-0.5"><i data-lucide="calendar" class="w-4 h-4"></i></div>
                            <div>
                                <p class="text-sm font-bold text-slate-700">Appointment Booked</p>
                                <p class="text-xs text-gray-500">Scheduled Measles vaccine for Zara Khan.</p>
                                <p class="text-[10px] text-gray-400 mt-1">Yesterday</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
<?php
include 'partials/inc_footer.php';
?>