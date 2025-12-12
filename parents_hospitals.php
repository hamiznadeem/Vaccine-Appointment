<?php
$title = 'Hospitals';
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

    if(isset($_POST['search']) && !empty($_POST['squery'])){
        $query = safe_input($_POST['squery']);
        $sql = "SELECT * FROM hospitals WHERE (hospital_name LIKE '%{$query}%' OR hospital_address LIKE '%{$query}%' )";
        $result = mysqli_query($conn, $sql);
    }else{
        $sql = "SELECT * FROM hospitals";
        $result = mysqli_query($conn, $sql);
    }

?>
<!-- Main Content -->
<div class="pt-32 pb-20 px-6 md:px-20 max-w-[1400px] mx-auto flex-grow w-full fade-in">
    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-sm text-gray-400 mb-6">
        <a href="index.php" class="hover:text-primary">Home</a>
        <i data-lucide="chevron-right" class="w-4 h-4"></i>
        <span class="text-gray-800 font-medium">Hospitals</span>
    </div>

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-end gap-6 mb-12">
        <div>
            <span class="text-primary font-bold text-xs tracking-widest uppercase mb-2 block">Healthcare Centers</span>
            <h1 class="text-3xl md:text-5xl font-bold text-darkblue leading-tight">Find a <span class="text-primary">Hospital</span></h1>
            <p class="text-gray-500 mt-3 max-w-lg">Locate nearest government authorized vaccination centers. All centers are verified for child safety.</p>
        </div>

        <!-- Search -->
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']?>" class="relative group w-full md:w-auto">
            <input type="text" name="squery" placeholder="Search hospital by name" class="pl-10 pr-4 py-3 bg-white border border-gray-200 rounded-xl shadow-sm focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition w-full sm:w-72">
            <i data-lucide="search" class="absolute left-3 top-3.5 w-5 h-5 text-gray-400 group-focus-within:text-primary transition"></i>
            <button type="submit" name="search" class="w-full sm:w-24 mt-2 md:mt-0 bg-primary text-white py-2.5 px-3 rounded-xl">Search</button>
        </form>
    </div>

        <?php
        if (mysqli_num_rows($result) > 0) {
            ?>
                <!-- Hospital Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Hospital Card -->
                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <div>
                        <a href="parents_book_appointment.php?id=<?php echo $row['hospital_id'] ?>" class="block bg-white p-4 rounded-[2rem] shadow-lg shadow-gray-100/50 border border-gray-100 transition hover:-translate-y-2 h-full">
                        <div class="h-48 flex items-center justify-center rounded-[1.5rem] overflow-hidden bg-slate-100 relative mb-5">
                            <?php if(!empty($row['hospital_img'])){?>
                            <img src="<?php echo $row['hospital_img'] ?>" alt="Hospital image" class="w-full h-full object-cover">
                            <?php }else{?>
                                <i data-lucide="hospital" class="w-12 h-12 text-primary"></i>
                            <?php } ?>
                            <div class="absolute bottom-4 left-4 bg-green-500 text-white text-[10px] font-bold px-2 py-1 rounded-md shadow-md">Free Camp Active</div>
                        </div>
                        <div class="px-2 pb-2">
                            <h4 class="font-bold text-xl text-gray-800"><?php echo $row['hospital_name'] ?></h4>
                            <p class="text-xs text-gray-400 mt-1 font-medium"><?php echo $row['hospital_address'] ?></p>
                            <div class="mt-5 flex justify-between items-center">
                                <button class="bg-darkblue text-white text-xs px-6 py-3 rounded-xl font-bold shadow-lg shadow-blue-900/20 hover:bg-blue-900 transition">Book Free Slot</button>
                                <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center bg-primary text-white shadow-sm border border-blue-50">
                                    <i data-lucide="shield-check" class="w-5 h-5"></i>
                                </div>
                            </div>
                        </div>
                        </a>
                    </div>
                    <?php } ?>
                </div>
        <?php
        } else {?>
        <div>
            <div colspan="6" class="px-6 py-12 text-center text-gray-400">
                <i data-lucide="hospital" class="w-10 h-10 mx-auto mb-4"></i>
                <div class="font-bold"> No hospital found</div>
                <div class="text-xs mt-1">Try adjusting your filters or search query.</div>
            </div>
        </div>
        <?php }?>

</div>
<?php
include 'partials/inc_footer.php';
?>