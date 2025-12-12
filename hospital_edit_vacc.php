<?php
$title = 'Edit Vaccine';
include 'partials/inc_header.php';
if (!isset($_SESSION['isLogin'])) {
    header('Location: login.php');
    exit;
} elseif ($user_role != 3) {
    header('Location: index.php');
    exit;
}

$hospital_id = $_SESSION['user_id'];
$message = '';

if($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])){
    $vaccine_id = safe_input($_GET['id']);
    $sql = " SELECT * FROM vaccines WHERE vaccine_id = '$vaccine_id' ";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_vacc_btn'])) {
    $vaccine_id = safe_input($_POST['vaccine_id']);
    if (empty($_POST['vacc_name'])) {
        $message = 'Vaccine name Required!';
    } elseif (empty($_POST['vacc_batch'])) {
        $message = 'Vaccine batch number Required!';
    } elseif (empty($_POST['stock'])) {
        $message = 'Select Available Stock';
    } elseif (empty($_POST['m_date'])) {
        $message = 'Manufactured Date Required';
    } elseif (empty($_POST['exp_date'])) {
        $message = 'Expiry Date Required';
    } elseif (empty($_POST['dose'])) {
        $message = "Vaccine Dose's Required";
    } elseif (empty($_POST['vacc_type'])) {
        $message = "Vaccine Type Required";
    } elseif (empty($_POST['target_age'])) {
        $message = "Vaccine target age Required";
    } else {
        $vaccine_id = safe_input($_POST['vaccine_id']);
        $vacc_name = safe_input($_POST['vacc_name']);
        $vacc_batch = safe_input($_POST['vacc_batch']);
        $dose = safe_input($_POST['dose']);
        $vacc_type = safe_input($_POST['vacc_type']);
        $target_age = safe_input($_POST['target_age']);
        $m_date = safe_input($_POST['m_date']);
        $exp_date = safe_input($_POST['exp_date']);
        $stock = safe_input($_POST['stock']);
        $batch_info = safe_input($_POST['batch_info']);

        $sql = " UPDATE `vaccines` SET `vaccine_name` = '$vacc_name', 
        `batch_no` = '$vacc_batch', 
        `doses` = '$dose', 
        `vaccine_type` = '$vacc_type', 
        `target_age` = '$target_age', 
        `m_date` = '$m_date', 
        `exp_date` = '$exp_date', 
        `stock_status` = '$stock', 
        `batch_info` = '$batch_info' WHERE vaccine_id = '$vaccine_id'";
        if ($result = mysqli_query($conn, $sql)) {
            header('location: hospital_inven.php');
            exit;
        }
    }
}



?>
<!-- Main Content -->
    <div class="pt-32 pb-20 px-6 md:px-20 max-w-[1400px] mx-auto flex-grow w-full fade-in">
        <!-- Breadcrumb -->
            <div class="flex items-center gap-2 text-sm text-gray-400 mb-6">
                <a href="index.php" class="hover:text-primary">home</a>
                <i data-lucide="chevron-right" class="w-4 h-4"></i>
                <a href="hospital_inven.php" class="hover:text-primary">Inventory</a>
                <i data-lucide="chevron-right" class="w-4 h-4"></i>
                <span class="text-gray-800 font-medium">Add Vaccine</span>
            </div>

            <div class="bg-white rounded-[2.5rem] p-8 md:p-10 shadow-lg border border-gray-100 max-w-4xl mx-auto">
                
                <div class="flex items-center gap-4 mb-8 pb-8 border-b border-gray-100">
                    <div class="w-16 h-16 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center shadow-sm">
                        <i data-lucide="shield" class="w-8 h-8"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-darkblue">Update Vaccine</h1>
                        <p class="text-gray-500 text-sm">Enter details to Update Vaccine in inventory</p>
                    </div>
                </div>

                <?php if(!empty($message)){ ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                        <span class="block sm:inline"><?php echo $message; ?></span>
                    </div>
                <?php } ?>

                <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>" class="space-y-8">
                    <input type="hidden" name="vaccine_id" value="<?php echo $vaccine_id?>">
                    <div class="space-y-4">
                        <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                            <i data-lucide="info" class="w-5 h-5 text-primary"></i> Basic Information
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2 tracking-wider">Vaccine Name</label>
                                <input type="text" name="vacc_name" value="<?php echo  $row['vaccine_name'] ?? $_POST['vacc_name']; ?>" placeholder="Vaccine Name" class="w-full px-5 py-4 bg-white border border-gray-200 rounded-2xl text-sm font-mano text-slate-800 focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2 tracking-wider">Batch Number</label>
                                <input type="text" name="vacc_batch" value="<?php echo $row['batch_no'] ?? $_POST['vacc_batch']; ?>" placeholder="e.g. BATCH-2024-A1" class="w-full px-5 py-4 bg-white border border-gray-200 rounded-2xl text-sm font-mono text-slate-800 focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition">
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                            <i data-lucide="package" class="w-5 h-5 text-primary"></i> Stock Details
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2 tracking-wider">Stock Status</label>
                                <select name="stock" class="w-full px-5 py-4 bg-white border border-gray-200 rounded-2xl text-sm font-medium text-slate-600 focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition appearance-none">
                                    <option value="">Select Stock</option>
                                    <option value="high" <?php if(($row['stock_status'] ?? $_POST['stock']) == 'high') echo 'selected'; ?>>In Stock (High)</option>
                                    <option value="low" <?php if(($row['stock_status'] ?? $_POST['stock']) == 'low') echo 'selected'; ?>>Low Stock</option>
                                    <option value="out" <?php if(($row['stock_status'] ?? $_POST['stock']) == 'out') echo 'selected'; ?>>Out of Stock</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2 tracking-wider">Manufacturing Date</label>
                                <input type="date" name="m_date" value="<?php echo $row['m_date'] ?? $_POST['m_date']; ?>" class="w-full px-5 py-4 bg-white border border-gray-200 rounded-2xl text-sm font-medium text-slate-600 focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2 tracking-wider">Expiry Date</label>
                                <input type="date" name="exp_date" value="<?php echo $row['exp_date'] ?? $_POST['exp_date']; ?>" class="w-full px-5 py-4 bg-white border border-gray-200 rounded-2xl text-sm font-medium text-slate-600 focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition">
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                            <i data-lucide="syringe" class="w-5 h-5 text-primary"></i> Vaccine Details
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2 tracking-wider">Quantity (Doses)</label>
                                <input type="number" name="dose" value="<?php echo $row['doses'] ?? $_POST['dose']; ?>" min="1" max="12" placeholder="0" class="w-full px-5 py-4 bg-white border border-gray-200 rounded-2xl text-sm font-bold text-slate-800 focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2 tracking-wider">Vaccine Type</label>
                                <select name="vacc_type" class="w-full px-5 py-4 bg-white border border-gray-200 rounded-2xl text-sm font-medium text-slate-600 focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition appearance-none">
                                    <option value="">Select type</option>
                                    <option value="Oral" <?php if(($row['vaccine_type'] ?? $_POST['vacc_type']) == 'Oral') echo 'selected'; ?>>Oral Drops</option>
                                    <option value="Injection" <?php if(($row['vaccine_type'] ?? $_POST['vacc_type']) == 'Injection') echo 'selected'; ?>>Injection</option>
                                    <option value="Nasal" <?php if(($row['vaccine_type'] ?? $_POST['vacc_type']) == 'Nasal') echo 'selected'; ?>>Nasal Spray</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2 tracking-wider">Targeted Age</label>
                                <input type="text" name="target_age" value="<?php echo $row['target_age'] ?? $_POST['target_age']; ?>" placeholder="e.g. 1-6 YEAR's" class="w-full px-5 py-4 bg-white border border-gray-200 rounded-2xl text-sm font-mono font-medium text-slate-600 focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition">
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2 tracking-wider">Vaccine Info (Description)</label>
                        <textarea rows="4" name="batch_info" placeholder="Any specific Infomation about this Vaccine for Parents..." class="w-full px-5 py-4 bg-white border border-gray-200 rounded-2xl text-sm font-medium text-slate-600 focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition resize-none"><?php echo $row['batch_info'] ?? $_POST['batch_info']; ?></textarea>
                    </div>

                    <div class="pt-6 flex flex-col sm:flex-row gap-4 border-t border-gray-100">
                        <a href="hospital_inven.php" class="w-full sm:w-auto px-8 py-4 border border-gray-200 text-gray-500 rounded-2xl text-sm font-bold hover:bg-gray-50 transition text-center">Cancel</a>
                        <button type="submit" name="edit_vacc_btn" class="w-full sm:w-auto px-8 py-4 bg-primary text-white rounded-2xl text-sm font-bold shadow-lg shadow-blue-900/20 hover:bg-cyan-500 transition flex items-center justify-center gap-2 ml-auto">
                            <i data-lucide="save" class="w-4 h-4"></i> Save & Update Stock
                        </button>
                    </div>

                </form>

            </div>
    </div>
<?php
include 'partials/inc_footer.php';
?>
