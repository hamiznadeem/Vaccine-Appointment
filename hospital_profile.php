<?php
$title = 'Hospital profile';
include 'partials/inc_header.php';
if (!isset($_SESSION['isLogin'])) {
    header('Location: login.php');
    exit;
} elseif ($user_role != 3) {
    header('Location: index.php');
    exit;
}

$hospital_id = $_SESSION['user_id'];

if (isset($_POST['upload_btn'])) {
    $vs_id = $_POST['id']; 
    $filename = $_FILES["image"]["name"];
    $tempname = $_FILES["image"]["tmp_name"];
    $folder = "asset/images/" . $filename;

    if (!empty($filename)) {
        $sql = "UPDATE `hospitals` SET `hospital_img` = '$filename' WHERE `hospital_id` = '$hospital_id' ";
        
        if (mysqli_query($conn, $sql)) {
            if (move_uploaded_file($tempname, $folder)) {
                header('Location:' . $_SERVER['PHP_SELF'] );
            } else {
                $message = "Database saved, but file move failed.";
                $msg_type = "error";
            }
        } else {
            $message = "Database Error: " . mysqli_error($conn);
            $msg_type = "error";
        }
    } else {
        $message = "Please select a file first.";
        $msg_type = "error";
    }
}

if(isset($_POST['des_web'])){
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $website = mysqli_real_escape_string($conn, $_POST['website']);
    $update_sql = "UPDATE hospitals SET hospital_description = '$description', `hospital-website` = '$website' WHERE hospital_id = '$hospital_id' ";
    $result_update = mysqli_query($conn, $update_sql);
    if($result_update){
        header("Location: hospital_profile.php");
        exit();
    }
}

$sql = "SELECT * FROM hospitals WHERE hospital_id = '$hospital_id' ";
$result = mysqli_query($conn, $sql);
?>
    <!-- Main Content -->
    <div class="pt-32 pb-20 px-6 md:px-20 max-w-[1400px] mx-auto flex-grow w-full fade-in">
    

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

            <!-- Left Column: Hospital Info -->
            <?php $row = mysqli_fetch_assoc($result) ?>
            <div class="lg:col-span-4">
                <div class="bg-white rounded-[2.5rem] p-8 shadow-xl border border-gray-100 sticky top-32 text-center">
                    <div class="relative w-28 h-28 mx-auto mb-6">
                        <div class="w-full h-full flex items-center justify-center rounded-full bg-slate-100 p-1 border-2 border-primary/20">
                            <?php if(!$row['hospital_img'] == null){?>
                            <img src="./asset/images/<?php echo $row['hospital_img']?>" alt="Hospital Image" class="w-full h-full rounded-full object-cover ">
                            <?php }else{ ?>
                            <i data-lucide="hospital" class="w-12 h-12 text-primary"></i>
                            <?php }?>
                        </div>
                    </div>

                    <h2 class="text-xl font-bold text-slate-800 mb-1"><?php echo $row['hospital_name']?></h2>
                    <p class="text-sm text-green-600 font-medium mb-6 bg-green-50 inline-block px-3 py-1 rounded-full">Verified • Government</p>

                    <div class="space-y-4 text-left bg-gray-50 p-5 rounded-2xl border border-gray-100">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center text-primary shadow-sm"><i data-lucide="file-badge" class="w-4 h-4"></i></div>
                            <div>
                                <p class="text-[10px] text-gray-400 uppercase font-bold">License No.</p>
                                <p class="text-sm font-bold text-slate-700"><?php echo $row['lc_rg_no']?></p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center text-primary shadow-sm"><i data-lucide="phone" class="w-4 h-4"></i></div>
                            <div>
                                <p class="text-[10px] text-gray-400 uppercase font-bold">Help Line</p>
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
                            <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center text-primary shadow-sm"><i data-lucide="map-pin" class="w-4 h-4"></i></div>
                            <div>
                                <p class="text-[10px] text-gray-400 uppercase font-bold">Address</p>
                                <p class="text-sm font-bold text-slate-700"><?php echo $row['hospital_address']?></p>
                            </div>
                        </div>
                    </div>

                    <a href="hospital_profile_edit.php?id=<?php echo $row['hospital_id']?>" class="w-full mt-6 py-3 border border-gray-200 text-gray-600 rounded-xl text-xs font-bold hover:bg-gray-50 transition flex items-center justify-center gap-2">
                        <i data-lucide="edit-2" class="w-3 h-3"></i> Edit Details
                    </a>
                </div>
            </div>

            <!-- Right Column: Overview -->
            <div class="lg:col-span-8">
                
                <!-- Header -->
                <div class="flex flex-col md:flex-row justify-between items-end gap-6 mb-8">
                    <div>
                        <h1 class="text-3xl font-bold text-darkblue">Hospital <span class="text-primary">Profile</span></h1>
                        <p class="text-gray-500 mt-2 text-sm">Manage public information and staff details.</p>
                    </div>
                </div>

                <!-- General Info -->
                <div class="bg-white rounded-[2rem] p-8 border border-gray-100 shadow-sm mb-8">
                    <h3 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2">
                        <i data-lucide="info" class="w-5 h-5 text-primary"></i> General Information
                    </h3>
                    
                    <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>" class="space-y-6">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2 tracking-wider">About Hospital</label>
                            <textarea rows="3" name="description" class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-medium text-slate-600 focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition resize-none"><?php echo $row['hospital_description']?></textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-1 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2 tracking-wider">Website</label>
                                <input name="website" type="text" placeholder="www.example.com" value="<?php echo $row['hospital-website']?>" class="w-full px-5 py-4 bg-white border border-gray-200 rounded-2xl text-sm font-medium text-gray-600 focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition">
                            </div>
                        </div>
                        <div class="flex gap-3">
                        <button name="des_web" class="px-4 py-2 bg-primary text-white rounded-xl text-sm font-bold shadow-lg shadow-blue-900/20 hover:bg-cyan-500 transition">Save Changes</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php
include 'partials/inc_footer.php';
?>