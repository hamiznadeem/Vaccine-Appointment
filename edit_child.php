<?php
$title = 'Edit Child';
include 'partials/inc_header.php';
$parent_id = $_SESSION['user_id'];
$user_role = $_SESSION['user_role'];
if (!isset($_SESSION['isLogin'])) {
    header('Location: login.php');
    exit;
} elseif ($user_role != 4) {
    header('Location: index.php');
    exit;
}

$child_id = "";

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $child_id = $_GET['id'];
    $sql = "SELECT * FROM childrens WHERE child_id = '$child_id' ";
    $result = mysqli_query($conn, $sql);
}



if (isset($_POST['edit_child'])) {
    $child_id = safe_input($_POST['child_id']);
    $child_name = safe_input($_POST['child-name']);
    $father_name = safe_input($_POST['child_father']);
    $mother_name = safe_input($_POST['child_mother']);
    $child_dob = safe_input($_POST['child_dob']);
    $child_gender = safe_input($_POST['child_gender']);
    $child_bf_crc = safe_input($_POST['child_bf_crc']);
    $child_pob = safe_input($_POST['child_pob']);
    $child_blood = safe_input($_POST['child_bld']);
    $child_allergic = safe_input($_POST['child_allergic']);

    $sql = "UPDATE `childrens` SET `child_name` = '$child_name', `father_name` = '$father_name', `mother_name` = '$mother_name', `child_dob` = '$child_dob', `child_gender` = '$child_gender', `bf_crc_no` = '$child_bf_crc', `child_bloodg` = '$child_blood', `child_allergic` = '$child_allergic' WHERE `childrens`.`child_id` = '$child_id' ";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        header("Location: parents_profile.php");
    }
}

?>
<!-- Main Content -->
<div class="pt-32 pb-20 px-6 md:px-20 max-w-[900px] mx-auto flex-grow w-full fade-in">

    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-sm text-gray-400 mb-6">
        <a href="index.php" class="hover:text-primary transition">Home</a>
        <i data-lucide="chevron-right" class="w-4 h-4"></i>
        <a href="parents_profile.php" class="hover:text-primary transition">Profile</a>
        <i data-lucide="chevron-right" class="w-4 h-4"></i>
        <span class="text-gray-800 font-medium">Edit Child</span>
    </div>

    <!-- Form Container -->
    <div class="bg-white rounded-[2.5rem] p-8 md:p-12 shadow-xl border border-gray-100">

        <div class="text-center mb-10">
            <h1 class="text-3xl font-bold text-darkblue">Edit Child</h1>
            <p class="text-gray-500 mt-2 text-sm">Edit your child's details.</p>
        </div>

        <?php
        if ($result) {
            $child_row = mysqli_fetch_assoc($result);
        }
        ?>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" class="space-y-8">
            <!-- Personal Details -->
            <input type="hidden" name="child_id" value="<?php echo $child_row['child_id'] ?>">
            <div class="space-y-6">
                <h3 class="text-lg font-bold text-slate-800 border-b border-gray-100 pb-2 flex items-center gap-2">
                    <i data-lucide="baby" class="w-5 h-5 text-primary"></i> Personal Information
                </h3>

                <div class="grid grid-cols-1 gap-6">
                    <!-- Full Name -->
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Child Full Name</label>
                        <input type="text" name="child-name" value="<?php echo $child_row['child_name'] ?>" placeholder="Full name" class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-medium text-slate-800 focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <!-- Father Name -->
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Father Name</label>
                        <input type="text" name="child_father" value="<?php echo $child_row['father_name'] ?>" placeholder="Father name" class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-medium text-slate-800 focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition">
                    </div>

                    <!-- Father Name -->
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Mother Name</label>
                        <input type="text" name="child_mother" value="<?php echo $child_row['mother_name'] ?>" placeholder="Mother Name" class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-medium text-slate-800 focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Date of Birth -->
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Date of Birth</label>
                        <input type="date" name="child_dob" value="<?php echo $child_row['child_dob'] ?>" class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-medium text-slate-600 focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition">
                    </div>

                    <!-- Gender -->
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Gender</label>
                        <div class="grid grid-cols-2 gap-4">
                            <label class="cursor-pointer">
                                <input type="radio" name="child_gender" value="male" class="peer sr-only"
                                    <?php echo ($child_row['child_gender'] == 'male') ? 'checked' : ''; ?>>
                                <div class="px-4 py-3.5 border border-gray-200 rounded-xl text-center text-sm font-medium text-gray-500 peer-checked:border-primary peer-checked:bg-blue-50 peer-checked:text-primary transition hover:bg-gray-50">
                                    Male
                                </div>
                            </label>

                            <label class="cursor-pointer">
                                <input type="radio" name="child_gender" value="female" class="peer sr-only"
                                    <?php echo ($child_row['child_gender']  == 'female') ? 'checked' : ''; ?>>
                                <div class="px-4 py-3.5 border border-gray-200 rounded-xl text-center text-sm font-medium text-gray-500 peer-checked:border-primary peer-checked:bg-blue-50 peer-checked:text-primary transition hover:bg-gray-50">
                                    Female
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- B-Form Number -->
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">B-Form / CRC Number</label>
                        <input type="text" name="child_bf_crc" value="<?php echo $child_row['bf_crc_no'] ?>" placeholder="e.g. 42101-xxxxxxx-x" class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-mono text-slate-800 focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition">
                    </div>
                    <!-- Place of Birth -->
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Place of Birth</label>
                        <input type="text" name="child_pob" value="<?php echo $child_row['child_pob'] ?>" placeholder="City or Hospital Name" class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-medium text-slate-800 focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition">
                    </div>
                </div>
            </div>

            <!-- Medical Details -->
            <div class="space-y-6 pt-4">
                <h3 class="text-lg font-bold text-slate-800 border-b border-gray-100 pb-2 flex items-center gap-2">
                    <i data-lucide="activity" class="w-5 h-5 text-primary"></i> Medical Details
                </h3>

                <div class="grid grid-cols-1 gap-6">
                    <!-- Blood Group (Optional) -->
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Blood Group <span class="text-gray-300 font-normal lowercase">(optional)</span></label>
                        <select name="child_bld" class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-medium text-slate-600 focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition appearance-none">
                            <option value="">Select Group</option>

                            <option value="A+" <?php echo ($child_row['child_bloodg'] == 'A+') ? 'selected' : ''; ?>>A+</option>

                            <option value="A-" <?php echo ($child_row['child_bloodg'] == 'A-') ? 'selected' : ''; ?>>A-</option>

                            <option value="B+" <?php echo ($child_row['child_bloodg'] == 'B+') ? 'selected' : ''; ?>>B+</option>

                            <option value="B-" <?php echo ($child_row['child_bloodg'] == 'B-') ? 'selected' : ''; ?>>B-</option>

                            <option value="O+" <?php echo ($child_row['child_bloodg'] == 'O+') ? 'selected' : ''; ?>>O+</option>

                            <option value="O-" <?php echo ($child_row['child_bloodg'] == 'O-') ? 'selected' : ''; ?>>O-</option>

                            <option value="AB+" <?php echo ($child_row['child_bloodg'] == 'AB+') ? 'selected' : ''; ?>>AB+</option>

                            <option value="AB-" <?php echo ($child_row['child_bloodg'] == 'AB-') ? 'selected' : ''; ?>>AB-</option>
                        </select>
                    </div>
                </div>

                <!-- Medical Conditions -->
                <div class="space-y-2">
                    <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Allergies / Medical Conditions <span class="text-gray-300 font-normal lowercase">(if any)</span></label>
                    <textarea rows="3" name="child_allergic" placeholder="E.g. Allergic to eggs, Asthma..." class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-medium text-slate-800 focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition resize-none"> <?php echo $child_row['child_allergic'] ?> </textarea>
                </div>

            </div>

            <!-- Action Buttons -->
            <div class="pt-6 border-t border-gray-100 flex flex-col sm:flex-row gap-4">
                <a href="parents_profile.php" class="w-full sm:w-auto px-8 py-4 border border-gray-200 text-gray-500 rounded-2xl text-sm font-bold hover:bg-gray-50 transition text-center">Cancel</a>
                <button type="submit" name="edit_child" class="w-full sm:w-auto px-10 py-4 bg-primary text-white rounded-2xl text-sm font-bold shadow-lg shadow-blue-900/20 hover:bg-cyan-500 transition flex items-center justify-center gap-2 ml-auto">
                    <i data-lucide="check" class="w-4 h-4"></i> Update Child
                </button>
            </div>

        </form>
    </div>
</div>

<?php
include 'partials/inc_footer.php';
?>