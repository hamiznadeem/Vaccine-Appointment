<?php
$title = 'Vaccines';
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

    if(isset($_POST['search']) && !empty($_POST['squery'])){
        $query = safe_input($_POST['squery']);
        $sql = "SELECT * FROM vaccines WHERE (vaccine_name LIKE '%{$query}%' OR vaccine_name LIKE '%{$query}%' )";
        $result = mysqli_query($conn, $sql);
    }else{
        $sql = "SELECT * FROM vaccines";
        $result = mysqli_query($conn, $sql);
    }

?>
<!-- Main Content -->
<div class="pt-32 pb-20 px-6 md:px-20 max-w-[1400px] mx-auto flex-grow w-full fade-in">

    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-sm text-gray-400 mb-6">
        <a href="index.php" class="hover:text-primary">Home</a>
        <i data-lucide="chevron-right" class="w-4 h-4"></i>
        <span class="text-gray-800 font-medium">Vaccines</span>
    </div>

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-end gap-6 mb-12">
        <div>
            <span class="text-primary font-bold text-xs tracking-widest uppercase mb-2 block">Immunization Program</span>
            <h1 class="text-3xl md:text-5xl font-bold text-darkblue leading-tight">Select a <span class="text-primary">Vaccine</span></h1>
            <p class="text-gray-500 mt-3 max-w-lg">Find government approved free vaccines. Click on any card to see nearby hospitals offering it.</p>
        </div>
        <!-- Search -->
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']?>" class="text-xs relative group w-full md:w-auto">
            <input type="text" name="squery" placeholder="Search Vaccine by name or hospital..." class="pl-10 pr-4 py-3 bg-white border border-gray-200 rounded-xl shadow-sm focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition w-full sm:w-72">
            <i data-lucide="search" class="absolute left-3 top-2.5 w-5 h-5 text-gray-400 group-focus-within:text-primary transition"></i>
            <button type="submit" name="search" class="w-full sm:w-24 mt-2 md:mt-0 bg-primary text-white py-2.5 px-3 rounded-xl">Search</button>
        </form>
    </div>

        <?php
        if (mysqli_num_rows($result) > 0) {
        ?>
    <!-- Vaccine Grid  -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Vaccine Card -->
        <?php while ($row = mysqli_fetch_assoc($result)) {

            if($row['vaccine_type'] == 'Oral'){
                $icon = 'droplet';
                $bg_class = 'bg-blue-50';
                $text_class = 'text-blue-600';
                $text2_class = 'text-blue-700';
                $bg_hover_class = 'bg-blue-600';
                $border_class = 'border-blue-50';
                $shadow = 'shadow-blue-500/10';
            }elseif($row['vaccine_type'] == 'Injection'){
                $icon = 'syringe';
                $bg_class = 'bg-red-50';
                $text_class = 'text-red-600';
                $text2_class = 'text-red-700';
                $bg_hover_class = 'bg-red-600';
                $border_class = 'border-red-50';
                $shadow = 'shadow-red-500/10';
            }elseif($row['vaccine_type'] == 'Nasal'){
                $icon = 'spray-can';
                $bg_class = 'bg-purple-50';
                $text_class = 'text-purple-600';
                $text2_class = 'text-purple-700';
                $bg_hover_class = 'bg-purple-600';
                $border_class = 'border-purple-50';
                $shadow = 'shadow-purple-500/10';
            }
            ?>
        <div>
            <a href="parents_book_appointment.php?id=<?php echo $row['hospital_id']?>&vc=<?php echo $row['vaccine_id']?>" class="group relative bg-white rounded-3xl p-6 border border-gray-100 shadow-sm hover:shadow-xl hover:<?php echo $shadow ?> hover:-translate-y-1 transition-all duration-300 overflow-hidden block">
            <!-- Background Decoration -->
            <div class="absolute -right-6 -top-6 w-32 h-32 <?php echo $bg_class ?> rounded-full group-hover:scale-150 transition-transform duration-500 ease-out"></div>

            <div class="relative z-10">
                <div class="flex justify-between items-start mb-4">
                    <div class="w-14 h-14 <?php echo $bg_class ?>  <?php echo  $text_class?> rounded-2xl flex items-center justify-center group-hover:<?php echo $bg_hover_class ?> group-hover:text-white transition-colors duration-300 shadow-sm">
                        <i data-lucide="<?php echo $icon ?>" class="w-7 h-7"></i>
                    </div>
                    <span class="<?php echo $bg_class?> <?php echo $text2_class ?> text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wide border border-blue-100"><?php echo $row['vaccine_type']?></span>
                </div>

                <h3 class="text-xl font-bold text-slate-900 mb-1 group-hover:<?php echo $text_class ?> transition-colors"><?php echo $row['vaccine_name']?></h3>
                <p class="text-xs text-gray-400 font-medium mb-4">Target age: <?php echo $row['target_age']?></p>

                <p class="text-gray-500 text-sm leading-relaxed mb-6 line-clamp-2">
                    <?php echo $row['batch_info']?>
                </p>
                
                <?php
                $sql_hospital = "Select hospital_name FROM hospitals WHERE hospital_id = '$row[hospital_id]' ";
                $result_hospital = mysqli_query($conn, $sql_hospital);
                $hospital_row = mysqli_fetch_assoc($result_hospital)
                ?>

                <div class="flex items-center justify-between pt-4 border-t border-gray-50 group-hover:<?php echo $border_class ?> transition-colors">
                    <span class="text-[10px] text-gray-400 font-bold">Available in <?php echo $hospital_row['hospital_name']?></span>
                    <span class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 group-hover:<?php echo $bg_hover_class?> group-hover:text-white transition-all duration-300">
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </span>
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
                <i data-lucide="syringe" class="w-10 h-10 mx-auto mb-4"></i>
                <div class="font-bold"> No Vaccine found</div>
                <div class="text-xs mt-1">Try adjusting your filters or search query.</div>
            </div>
        </div>
        <?php }?>
</div>
<?php
include 'partials/inc_footer.php';
?>