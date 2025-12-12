<?php
$title = 'Inventory';
include 'partials/inc_header.php';
if (!isset($_SESSION['isLogin'])) {
    header('Location: login.php');
    exit;
} elseif ($user_role != 3) {
    header('Location: index.php');
    exit;
}
$hospital_id = $_SESSION['user_id'];

if($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])){
    $vaccine_id = safe_input($_GET['id']);
    $sql = "DELETE FROM vaccine WHERE vaccine_id = '$vaccine_id' " ;
    if($result = mysqli_query($conn, $sql)){
        header('location:' . $_SERVER['PHP_SELF']);
    }
}

$sql = " SELECT * FROM vaccines WHERE hospital_id = '$hospital_id' ";
$result = mysqli_query($conn, $sql)
?>
<!-- Main Content -->
<div class="pt-32 pb-20 px-6 md:px-20 max-w-[1400px] mx-auto flex-grow w-full fade-in">

    <!-- Header & Stats -->
    <div class="flex flex-col md:flex-row justify-between items-end gap-6 mb-10">
        <div>
            <span class="text-primary font-bold text-xs tracking-widest uppercase mb-2 block">Stock Management</span>
            <h1 class="text-3xl md:text-5xl font-bold text-darkblue leading-tight">Vaccine <span class="text-primary">Inventory</span></h1>
            <p class="text-gray-500 mt-2 text-sm">Monitor stock levels, expiry dates, and request new batches.</p>
        </div>
    </div>

    <!-- Action Bar -->
    <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-8">
        <div class="flex gap-3 w-full sm:w-auto">
            <a href="hospital_add_vacc.php" class="flex-1 sm:flex-none px-6 py-3 bg-darkblue text-white rounded-xl text-sm font-bold shadow-lg shadow-blue-900/20 hover:bg-cyan-500 transition flex items-center justify-center gap-2">
                <i data-lucide="plus" class="w-4 h-4"></i> Add Vaccine
            </a>
        </div>
    </div>

    <!-- Inventory Table -->
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-gray-800 font-bold uppercase text-xs tracking-wider">
                    <tr>
                        <th class="px-6 py-4 pl-8">Vaccine Name</th>
                        <th class="px-6 py-4">Batch No.</th>
                        <th class="px-6 py-4">Man Date</th>
                        <th class="px-6 py-4">Expiry Date</th>
                        <th class="px-6 py-4">Dose's</th>
                        <th class="px-6 py-4">Target Age</th>
                        <th class="px-6 py-4 w-48">Stock Level</th>
                        <th class="px-6 py-4 text-right pr-8">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">

                    <?php
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                            <!-- vaccine Row -->
                            <tr class="hover:bg-slate-50 transition group">
                                <td class="px-6 py-4 pl-8">
                                    <div class="flex items-center gap-3">
                                        <?php
                                        if ($row['vaccine_type'] == 'Oral') {
                                            $icon = 'droplet';
                                            $bg_class = 'bg-blue-50';
                                            $text_class = 'text-blue-600';
                                        } elseif ($row['vaccine_type'] == 'Injection') {
                                            $icon = 'syringe';
                                            $bg_class = 'bg-red-50';
                                            $text_class = 'text-red-600';
                                        } elseif ($row['vaccine_type'] == 'Nasal') {
                                            $icon = 'spray-can';
                                            $bg_class = 'bg-purple-50';
                                            $text_class = 'text-purple-600';
                                        }
                                        ?>
                                        <div class="w-10 h-10 rounded-xl <?php echo $bg_class?> <?php echo $text_class?> flex items-center justify-center shadow-sm">
                                            <i data-lucide="<?php echo $icon ?>" class="w-5 h-5"></i>
                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-800"><?php echo $row['vaccine_name'] ?></div>
                                            <div class="text-[10px] text-gray-400"><?php echo $row['vaccine_type'] ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 font-mono text-xs"><?php echo $row['batch_no'] ?></td>
                                <td class="px-6 py-4 text-xs font-medium"><?php echo $row['m_date'] ?></td>
                                <td class="px-6 py-4 text-xs font-medium"><?php echo $row['exp_date'] ?></td>
                                <td class="px-6 py-4 text-xs font-medium"><?php echo $row['doses'] ?></td>
                                <td class="px-6 py-4 text-xs font-medium"><?php echo $row['target_age'] ?></td>
                                <?php
                                if($row['stock_status'] == 'high'){
                                    $status = 'high';
                                }
                                elseif($row['stock_status'] == 'low'){
                                    $status = 'low';
                                }
                                elseif($row['stock_status'] == 'out'){
                                    $status = 'out';
                                }
                                ?>
                                <td class="px-6 py-4"><span class="badge-<?php echo $status ?> px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wide"><?php echo $row['stock_status']?></span></td>
                                <td class="px-6 py-4 text-right pr-8 relative">
                                    <button onclick="toggleDropdown(this)" class="p-2 hover:bg-gray-100 rounded-lg text-gray-500 transition focus:outline-none">
                                        <i data-lucide="more-vertical" class="w-4 h-4"></i>
                                    </button>
                                    <div class="dropdown-menu absolute right-8 top-2 w-32 bg-white rounded-xl shadow-xl border border-gray-100 z-10 overflow-hidden">
                                        <a href="hospital_edit_vacc.php?id=<?php echo $row['vaccine_id']?>" class="block px-4 py-2 text-left text-xs font-medium text-gray-700 hover:bg-gray-50 hover:text-primary flex items-center gap-2">
                                            <i data-lucide="edit-2" class="w-3 h-3"></i> Update
                                        </a>
                                        <a href="hospital_inven.php?id=<?php echo $row['vaccine_id']?>" class="block px-4 py-2 text-left text-xs font-medium text-red-500 hover:bg-red-50 flex items-center gap-2">
                                            <i data-lucide="trash" class="w-3 h-3"></i> Discard
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php }
                    } else { ?>
                        <!-- Fallback row -->
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center text-gray-400">
                                <i data-lucide="syringe" class="w-10 h-10 mx-auto mb-4"></i>
                                <div class="font-bold">No vaccines found.</div>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php
include 'partials/inc_footer.php';
?>