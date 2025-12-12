<?php
ob_start();
include 'admin_partials/inc_header.php';

// messages
$message = '';
$messageType = '';


// Handle GET actions: delete
if (isset($_GET['action'], $_GET['id'])) {
    $action = $_GET['action'];
    $id = intval($_GET['id']);
    if ($id > 0) {
        if ($action === 'status' && isset($_GET['status'])) {
            $iq_status = mysqli_real_escape_string($conn, trim($_GET['status'] ?? ''));
            $sql = "UPDATE inquiries SET inquiry_status='{$iq_status}' WHERE inquiry_id={$id} LIMIT 1";
            mysqli_query($conn, $sql);
            header('Location:' . $_SERVER['PHP_SELF']);
            exit;
        }
        if ($action === 'delete') {
            $sql = "DELETE FROM inquiries WHERE inquiry_id={$id} LIMIT 1";
            mysqli_query($conn, $sql);
            header('Location:' . $_SERVER['PHP_SELF']);
            exit;
        }
    }
}

// Get filter parameters
$status = isset($_GET['status']) ? $_GET['status'] : 'all';
$q = isset($_GET['q']) ? trim($_GET['q']) : '';

// Build WHERE clause
$where = [];
if ($status !== 'all') {
    $safe_status = mysqli_real_escape_string($conn, $status);
    $where[] = "inquiry_status = '{$safe_status}'";
}
if ($q !== '') {
    $safe_q = mysqli_real_escape_string($conn, $q);
    $where[] = "(inquiry_id LIKE '%{$safe_q}%' OR sender_name LIKE '%{$safe_q}%' OR sender_email LIKE '%{$safe_q}%' OR subject LIKE '%{$safe_q}%')";
}

$where_sql = '';
if (count($where) > 0) $where_sql = 'WHERE ' . implode(' AND ', $where);

// Query database
$sql = "SELECT * FROM inquiries {$where_sql} ORDER BY inquiry_id DESC";
$result = mysqli_query($conn, $sql);
?>
<style>
    /* Status Colors */
        .status-new { background-color: #DBEAFE; color: #1D4ED8; } /* Blue */
        .status-progress { background-color: #FEF3C7; color: #D97706; } /* Yellow */
        .status-solved { background-color: #D1FAE5; color: #059669; } /* Green */
</style>

<!-- Main Content -->
        <main class="relative lg:ml-64 pt-6 lg:pt-10 pb-10 min-h-screen bg-slate-50">
            
            <!-- Navbar -->
            <div class="sticky top-6 z-30 px-6 lg:px-10 mb-8 hidden lg:block">
                <div class="bg-white/80 backdrop-blur-xl border border-white/60 rounded-full px-6 py-3 shadow-sm flex justify-between items-center">
                    <h2 class="text-slate-800 font-bold text-lg pl-2">User Inquiries</h2>
                    
                    <div class="flex items-center gap-6 pr-1">
                        <button class="relative text-slate-500 hover:text-primary transition">
                            <i data-lucide="bell" class="w-5 h-5"></i>
                            <span class="absolute -top-1 -right-1 w-2.5 h-2.5 bg-red-500 rounded-full border-2 border-white"></span>
                        </button>
                        
                        <div class="flex items-center gap-3 pl-6 border-l border-gray-200">
                    <div class="text-right hidden md:block">
                        <div class="text-xs font-bold text-slate-800"><?php echo $_SESSION['admin_name']; ?></div>
                        <div class="text-[10px] text-slate-500 uppercase"><?php echo $_SESSION['user_name']; ?></div>
                    </div>
                    <div class="w-9 h-9 rounded-full bg-darkblue text-white flex items-center justify-center font-bold text-sm shadow-md">
                        <?php echo strtoupper(substr($_SESSION['admin_name'], 0, 2)); ?>
                    </div>
                </div>
                    </div>
                </div>
            </div>

            <div class="px-6 lg:px-10 pb-10 space-y-6 fade-in">

            <!-- Action Bar & Tabs -->
                <div class="flex flex-col xl:flex-row justify-between items-start xl:items-center gap-6">
                    
                    <!-- Tabs -->
                    <div class="bg-white p-1 no-scrollbar rounded-xl border border-gray-200 flex overflow-x-auto w-full xl:w-auto">
                        <a href="inquiries.php?status=all" class="px-6 py-2 <?php echo $status==='all' ? 'bg-darkblue text-white' : 'bg-white text-gray-500 hover:text-primary hover:bg-gray-50'; ?> rounded-lg text-sm font-bold transition whitespace-nowrap">All Inquiries</a>
                        <a href="inquiries.php?status=pending" class="px-6 py-2 <?php echo $status==='pending' ? 'bg-darkblue text-white' : 'bg-white text-gray-500 hover:text-primary hover:bg-gray-50'; ?> rounded-lg text-sm font-bold transition whitespace-nowrap">Pending</a>
                        <a href="inquiries.php?status=working" class="px-6 py-2 <?php echo $status==='working' ? 'bg-darkblue text-white' : 'bg-white text-gray-500 hover:text-primary hover:bg-gray-50'; ?> rounded-lg text-sm font-bold transition whitespace-nowrap">Working</a>
                        <a href="inquiries.php?status=solved" class="px-6 py-2 <?php echo $status==='solved' ? 'bg-darkblue text-white' : 'bg-white text-gray-500 hover:text-primary hover:bg-gray-50'; ?> rounded-lg text-sm font-bold transition whitespace-nowrap">Solved</a>
                    </div>

                    <!-- Search & Filter -->
                    <form method="GET" action="inquiries.php" class="flex flex-col sm:flex-row gap-3 w-full xl:w-auto">
                        <input type="hidden" name="status" value="<?php echo htmlspecialchars($status); ?>">
                        <div class="relative w-full sm:w-64">
                            <input type="text" name="q" value="<?php echo htmlspecialchars($q); ?>" placeholder="Search inquiry..." class="w-full pl-10 pr-4 py-2.5 bg-white border border-gray-200 rounded-xl shadow-sm focus:outline-none focus:border-primary transition text-sm">
                            <i data-lucide="search" class="absolute left-3 top-3 w-4 h-4 text-gray-400"></i>
                        </div>
                        <button type="submit" class="px-4 py-2.5 bg-darkblue text-white rounded-xl text-sm font-bold hover:bg-blue-800 transition shadow-md flex items-center justify-center gap-2 whitespace-nowrap">
                            <i data-lucide="Search" class="w-4 h-4"></i> Search
                        </button>
                    </form>
                </div>

                <?php if ($message){ ?>
                    <div class="p-4">
                        <div class="<?php echo $messageType == 'success' ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700'; ?> rounded-xl p-3 text-sm font-semibold">
                            <?php echo htmlspecialchars($message); ?>
                        </div>
                    </div>
                <?php }; ?>

                <!-- Messages List -->
                <div class="grid grid-cols-1 gap-4">

                    <?php
                    if ($result && mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $inq_status = $row['inquiry_status'];
                            $statusClass = 'status-' . ($inq_status === 'pending' ? 'new' : ($inq_status === 'working' ? 'progress' : 'solved'));
                    ?>
                    <!-- Message Card -->
                    <div class="bg-white p-6 rounded-[1.5rem] shadow-sm border border-gray-100 hover:shadow-md transition relative group">
                        <div class="absolute top-6 right-6">
                            <span class="<?php echo $statusClass; ?> px-3 py-1 rounded-lg text-xs font-bold uppercase tracking-wide"><?php echo htmlspecialchars($inq_status); ?></span>
                        </div>
                        
                        <div class="flex flex-col md:flex-row gap-6">
                            <!-- User Info -->
                            <div class="md:w-1/4 flex flex-col gap-1 border-b md:border-b-0 md:border-r border-gray-100 pb-4 md:pb-0">
                                <h4 class="font-bold text-slate-800"><?php echo htmlspecialchars($row['sender_name']); ?></h4>
                                <p class="text-xs text-gray-400"><?php echo htmlspecialchars($row['sender_email']); ?></p>
                                <div class="flex items-center gap-1 mt-2 text-xs text-gray-400">
                                    <i data-lucide="clock" class="w-3 h-3"></i> <?php echo date('M d, h:i A', strtotime($row['create_date'])); ?>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="md:w-3/4 flex flex-col justify-between">
                                <div>
                                    <h5 class="text-sm font-bold text-darkblue mb-2">Subject: <?php echo htmlspecialchars($row['subject']); ?></h5>
                                    <p class="text-sm text-gray-500 leading-relaxed mb-4">
                                        <?php echo htmlspecialchars($row['inquiry_message']); ?>
                                    </p>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex flex-wrap gap-3 mt-4 pt-4 border-t border-gray-50">
                                    <?php if ($inq_status == 'pending') { ?>
                                            <a href="?action=status&status=working&id=<?php echo $row['inquiry_id']; ?>"  class="px-4 py-2 bg-orange-50 text-orange-600 border border-orange-100 rounded-lg text-xs font-bold hover:bg-orange-100 transition flex items-center gap-2">
                                                <i data-lucide="loader" class="w-3 h-3"></i> Working on it
                                            </a>
                                            <a href="?action=status&status=solved&id=<?php echo $row['inquiry_id']; ?>" class="px-4 py-2 bg-green-50 text-green-600 border border-green-100 rounded-lg text-xs font-bold hover:bg-green-100 transition flex items-center gap-2">
                                                <i data-lucide="check-circle" class="w-3 h-3"></i> Mark Solved
                                            </a>
                                    <?php }elseif($inq_status == 'working'){ ?>
                                        <button disabled href="?action=status&status=working&id=<?php echo $row['inquiry_id']; ?>"  class="px-4 py-2 bg-gray-50/5 text-orange-600/50 border border-orange-100 rounded-lg text-xs font-bold  transition flex items-center gap-2 cursor-not-allowed">
                                                <i data-lucide="loader" class="w-3 h-3"></i> Working on it
                                            </button>
                                            <a href="?action=status&status=solved&id=<?php echo $row['inquiry_id']; ?>" class="px-4 py-2 bg-green-50 text-green-600 border border-green-100 rounded-lg text-xs font-bold hover:bg-green-100 transition flex items-center gap-2">
                                                <i data-lucide="check-circle" class="w-3 h-3"></i> Mark Solved
                                            </a>
                                            <?php }else{ ?>
                                            <button disabled class="px-4 py-2 bg-green-100 text-green-600 border border-green-100 rounded-lg text-xs font-bold transition flex items-center gap-2">
                                                <i data-lucide="check-circle" class="w-3 h-3"></i> Solved
                                            </button>
                                    <?php } ?>
                                    <a href="?action=delete&id=<?php echo $row['inquiry_id']; ?>" class="px-4 py-2 bg-red-50 text-red-600 border border-red-100 rounded-lg text-xs font-bold hover:bg-red-100 transition flex items-center gap-2 ml-auto" onclick="return confirm('Delete this inquiry?')">
                                        <i data-lucide="trash-2" class="w-3 h-3"></i> Delete
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                        }
                    } else {
                    ?>
                    <!-- Empty State -->
                    <div class="bg-white p-12 rounded-[1.5rem] shadow-sm border border-gray-100 text-center">
                        <i data-lucide="inbox" class="w-12 h-12 mx-auto mb-4 text-gray-300"></i>
                        <div class="font-bold text-gray-600 mb-1">No inquiries found</div>
                        <div class="text-xs text-gray-400">Try adjusting your filters or search query.</div>
                    </div>
                    <?php } ?>

                </div>

                </div>

            </div>
        </main>

<script>
    function toggleDropdown(button) {
        const dropdown = button.nextElementSibling;
        if (dropdown && dropdown.classList.contains('dropdown-menu')) {
            dropdown.classList.toggle('hidden');
        }
    }

    // Close dropdowns when clicking outside
    document.addEventListener('click', function(event) {
        if (!event.target.closest('.dropdown-container')) {
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                menu.classList.add('hidden');
            });
        }
    });

    // Handle form submissions with proper status mapping
    document.querySelectorAll('form[method="POST"]').forEach(form => {
        form.addEventListener('submit', function(e) {
            const button = this.querySelector('button[type="submit"]');
            if (button) {
                button.disabled = true;
                button.innerHTML = '<i data-lucide="loader" class="w-3 h-3 animate-spin"></i> Processing...';
            }
        });
    });
</script>

<?php
ob_end_flush();
include 'admin_partials/inc_footer.php';
?>