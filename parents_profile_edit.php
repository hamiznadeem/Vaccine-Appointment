<?php 
$title = 'Parent Profile Edit';
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
    if(isset($_GET['id'])){ 
        $parent_id = $_GET['id'];
        $sql = "SELECT * FROM parents WHERE parent_id='$parent_id' ";
        $result = mysqli_query($conn, $sql);
    }

    if(isset($_POST['update_parent'])){
        $parent_id = safe_input($_POST['parent_id']);
        $fname = safe_input($_POST['fname']);
        $cnic = safe_input($_POST['cnic']);
        $phone = safe_input($_POST['phone']);
        $email = safe_input($_POST['email']);
        $password = safe_input($_POST['pass']); 
        $password = password_hash($password, PASSWORD_DEFAULT);
        if(!empty($password)){
            $sql = "UPDATE parents SET fname='$fname', cnic='$cnic', phone='$phone', email='$email'  WHERE parent_id='$parent_id' ";
        }else{
            $sql = "UPDATE parents SET fname='$fname', cnic='$cnic', phone='$phone', email='$email', password='$password'  WHERE parent_id='$parent_id' ";
        }
        $result = mysqli_query($conn, $sql);
        if($result){
            header("Location: parents_profile.php");
        }
    }
?>
<div>


<!-- Main Content -->
    <div class="pt-32 pb-20 px-6 md:px-20 max-w-[900px] mx-auto flex-grow w-full fade-in">
        
        <!-- Breadcrumb -->
        <div class="flex items-center gap-2 text-sm text-gray-400 mb-6">
            <a href="index.html" class="hover:text-primary transition">Home</a>
            <i data-lucide="chevron-right" class="w-4 h-4"></i>
            <a href="parent_profile.html" class="hover:text-primary transition">Profile</a>
            <i data-lucide="chevron-right" class="w-4 h-4"></i>
            <span class="text-gray-800 font-medium">Edit Profile</span>
        </div>

        <!-- Form Container -->
        <div class="bg-white rounded-[2.5rem] p-8 md:p-12 shadow-xl border border-gray-100">
            
            <div class="text-center mb-10">
                <h1 class="text-3xl font-bold text-darkblue">Edit Profile</h1>
                <p class="text-gray-500 mt-2 text-sm">Update your personal information and account settings.</p>
            </div>

            <form method="POST" action="parents_profile_edit.php" class="space-y-8">
                
                <div class="flex justify-center mb-8">
                    <div class="relative group">
                        <div class="w-32 h-32 flex items-center justify-center rounded-full bg-slate-100 p-1 border-2 border-primary/20 overflow-hidden">
                            <i data-lucide="user" class="w-12 h-12 text-primary"></i>
                        </div>
                    </div>
                </div>

                <!-- Personal Details -->
                <div class="space-y-6">
                    <h3 class="text-lg font-bold text-slate-800 border-b border-gray-100 pb-2 flex items-center gap-2">
                        <i data-lucide="user" class="w-5 h-5 text-primary"></i> Personal Details
                    </h3>
                    <?php $row = mysqli_fetch_assoc($result);?>
                    <input type="hidden" name="parent_id" value="<?php echo $row['parent_id']?>">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Full Name -->
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Full Name</label>
                            <input type="text" name="fname" value="<?php echo $row['fname'] ?>" class="w-full px-5 py-4 bg-white border border-gray-200 rounded-2xl text-sm font-bold text-slate-800 focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition">
                        </div>

                        <!-- CNIC (Read Only) -->
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">CNIC / ID Number <span class="text-red-400 text-[10px] lowercase font-normal">(cannot be changed)</span></label>
                            <input type="text" value="<?php echo $row['cnic'] ?>" class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-mono text-gray-500 focus:outline-none cursor-not-allowed" disabled>
                            <input type="hidden" name="cnic" value="<?php echo $row['cnic'] ?>" class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-mono text-gray-500 focus:outline-none cursor-not-allowed">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Phone -->
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Phone Number</label>
                            <input type="tel" name="phone" value="<?php echo $row['phone'] ?>" class="w-full px-5 py-4 bg-white border border-gray-200 rounded-2xl text-sm font-medium text-slate-800 focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition">
                        </div>

                        <!-- Email -->
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Email Address</label>
                            <input type="email" name="email" value="<?php echo $row['email'] ?>" class="w-full px-5 py-4 bg-white border border-gray-200 rounded-2xl text-sm font-medium text-slate-800 focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition">
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
                    <a href="parents_profile.php" class="w-full sm:w-auto px-8 py-4 border border-gray-200 text-gray-500 rounded-2xl text-sm font-bold hover:bg-gray-50 transition text-center">Cancel</a>
                    <button type="submit" name="update_parent" class="w-full sm:w-auto px-10 py-4 bg-primary text-white rounded-2xl text-sm font-bold shadow-lg shadow-blue-900/20 hover:bg-cyan-500 transition flex items-center justify-center gap-2 ml-auto">
                        <i data-lucide="save" class="w-4 h-4"></i> Save Changes
                    </button>
                </div>

            </form>
        </div>
    </div>

    </div>
    
    <?php include './partials/inc_footer.php'?>