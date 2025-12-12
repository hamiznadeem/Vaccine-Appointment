<?php 
$title = 'Hospital Profile Edit';
    include 'partials/inc_header.php'; 
    if (!isset($_SESSION['isLogin'])) {
    header('Location: login.php');
    exit;
} elseif ($user_role != 3) {
    header('Location: index.php');
    exit;
}

    if(isset($_GET['id'])){ 
        $hospital_id = $_GET['id'];
        $sql = "SELECT * FROM hospitals WHERE hospital_id='$hospital_id' ";
        $result = mysqli_query($conn, $sql);
    }

    if(isset($_POST['update_hospital'])){
        $hospital_id = $_POST['hospital_id'];
        $name = safe_input($_POST['name']);
        $lc = safe_input($_POST['lc']);
        $phone = safe_input($_POST['phone']);
        $email = safe_input($_POST['email']);
        $address = safe_input($_POST['address']);
        $password = safe_input($_POST['pass']); 
        $password = password_hash($password, PASSWORD_DEFAULT);
        if(!empty($password)){
            $sql = "UPDATE hospitals SET hospital_name='$name', lc_rg_no='$lc', phone='$phone', email='$email', hospital_address='$address'  WHERE hospital_id='$hospital_id' ";
        }else{
            $sql = "UPDATE hospitals SET hospital_name='$name', lc_rg_no='$lc', phone='$phone', email='$email', hospital_address='$address', password='$password'  WHERE hospital_id='$hospital_id' ";
        }
        $result = mysqli_query($conn, $sql);
        if($result){

        }
    }

    if (isset($_POST['update_hospital'])) {
    $hospital_id = $_POST['hospital_id'];
    $filename = $_FILES["image"]["name"];
    $tempname = $_FILES["image"]["tmp_name"];
    $folder = "asset/images/" . $filename;

    if (!empty($filename)) {
        $sql = "UPDATE `hospitals` SET `hospital_img` = '$filename' WHERE `hospital_id` = '$hospital_id' ";
        
        if (mysqli_query($conn, $sql)) {
            if (move_uploaded_file($tempname, $folder)) {
                header('Location: hospital_profile.php');
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
?>
<div>


<!-- Main Content -->
    <div class="pt-32 pb-20 px-6 md:px-20 max-w-[900px] mx-auto flex-grow w-full fade-in">
    

        <!-- Form Container -->
        <div class="bg-white rounded-[2.5rem] p-8 md:p-12 shadow-xl border border-gray-100">
            
            <div class="text-center mb-10">
                <h1 class="text-3xl font-bold text-darkblue">Edit Hospital Profile</h1>
                <p class="text-gray-500 mt-2 text-sm">Update your hospital information</p>
            </div>

            <form method="POST" action="hospital_profile_edit.php" enctype="multipart/form-data"  class="space-y-8">
                
                <?php $row = mysqli_fetch_assoc($result);?>
            <!-- Logo Upload (Clickable) -->
                <div class="flex justify-center mb-8">
                            <div class="relative group cursor-pointer" onclick="document.getElementById('logoInput').click()">
                                <div class="w-32 h-32 rounded-3xl bg-slate-100 p-1 border-2 border-primary/20 overflow-hidden">
                                    <img id="logoPreview" src="./asset/images/<?php echo $row['hospital_img'] ?>" class="w-full h-full object-cover rounded-2xl group-hover:opacity-75 transition">
                                </div>
                                <div class="absolute bottom-0 right-0 bg-darkblue text-white p-2 rounded-xl shadow-md group-hover:bg-primary transition">
                                    <i data-lucide="camera" class="w-4 h-4"></i>
                                </div>
                                <span class="text-xs text-gray-400 mt-2 block text-center group-hover:text-primary transition">Change Logo</span>
                            </div>
                            <input type="file" name="image" id="logoInput" class="hidden" accept="image/*" onchange="previewImage(event)">
                </div>

                <!-- Personal Details -->
                <div class="space-y-6">
                    <h3 class="text-lg font-bold text-slate-800 border-b border-gray-100 pb-2 flex items-center gap-2">
                        <i data-lucide="hospital" class="w-5 h-5 text-primary"></i> Hospital Details
                    </h3>
                    <input type="hidden" name="hospital_id" value="<?php echo $row['hospital_id']?>">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- hospital Name -->
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Hospital Name</label>
                            <input type="text" name="name" value="<?php echo $row['hospital_name'] ?>" class="w-full px-5 py-4 bg-white border border-gray-200 rounded-2xl text-sm font-bold text-slate-800 focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition">
                        </div>

                        <!-- license (Read Only) -->
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">License no<span class="text-red-400 text-[10px] lowercase font-normal">(cannot be changed)</span></label>
                            <input type="text" value="<?php echo $row['lc_rg_no'] ?>" class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-mono text-gray-500 focus:outline-none cursor-not-allowed" disabled>
                            <input type="hidden" name="lc" value="<?php echo $row['lc_rg_no'] ?>" class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-mono text-gray-500 focus:outline-none cursor-not-allowed">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Phone -->
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Helpline</label>
                            <input type="tel" name="phone" value="<?php echo $row['phone'] ?>" class="w-full px-5 py-4 bg-white border border-gray-200 rounded-2xl text-sm font-medium text-slate-800 focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition">
                        </div>

                        <!-- Email -->
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Email Address</label>
                            <input type="email" name="email" value="<?php echo $row['email'] ?>" class="w-full px-5 py-4 bg-white border border-gray-200 rounded-2xl text-sm font-medium text-slate-800 focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition">
                        </div>

                        <!-- address -->
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Hospital Address</label>
                            <textarea type="text" name="address" class="w-full px-5 py-4 bg-white border border-gray-200 rounded-2xl text-sm font-medium text-slate-800 focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition"> <?php echo $row['hospital_address'] ?></textarea>
                        </div>
                    </div>

                <!-- Security Settings -->
                <div class="space-y-6 pt-4">
                    <h3 class="text-lg font-bold text-slate-800 border-b border-gray-100 pb-2 flex items-center gap-2">
                        <i data-lucide="lock" class="w-5 h-5 text-primary"></i> Security
                    </h3>

                    <div class="bg-orange-50 border border-orange-100 rounded-2xl p-4 mb-4">
                        <p class="text-xs text-orange-600 font-medium flex items-center gap-2">
                            <i data-lucide="alert-circle" class="w-4 h-4"></i> Leave blank if you don't want to change password.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- New Password -->
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">New Password</label>
                            <input type="password" name="pass" placeholder="••••••••" class="w-full px-5 py-4 bg-white border border-gray-200 rounded-2xl text-sm font-bold text-slate-800 focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition">
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="pt-6 border-t border-gray-100 flex flex-col sm:flex-row gap-4">
                    <a href="hospital_profile.php" class="w-full sm:w-auto px-8 py-4 border border-gray-200 text-gray-500 rounded-2xl text-sm font-bold hover:bg-gray-50 transition text-center">Cancel</a>
                    <button type="submit" name="update_hospital" class="w-full sm:w-auto px-10 py-4 bg-primary text-white rounded-2xl text-sm font-bold shadow-lg shadow-blue-900/20 hover:bg-cyan-500 transition flex items-center justify-center gap-2 ml-auto">
                        <i data-lucide="save" class="w-4 h-4"></i> Save Changes
                    </button>
                </div>

            </form>
        </div>
    </div>

    </div>

    <script>
         // Image Preview
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function(){
                const output = document.getElementById('logoPreview');
                output.src = reader.result;
            }
            reader.readAsDataURL(event.target.files[0]);
        }   
    </script>
    <?php include './partials/inc_footer.php'?>