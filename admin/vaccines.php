<?php
ob_start();
include 'admin_partials/inc_header.php';

// messages
$message = '';
$messageType = '';

// Handle POST: update vaccine
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_vaccine'])) {
    $id = intval($_POST['vaccine_id'] ?? 0);
    $name = mysqli_real_escape_string($conn, trim($_POST['vaccine_name'] ?? ''));
    $batch_no = mysqli_real_escape_string($conn, trim($_POST['batch_no'] ?? ''));
    $doses = intval($_POST['doses'] ?? 0);
    $vaccine_type = mysqli_real_escape_string($conn, trim($_POST['vaccine_type'] ?? ''));
    $target_age = mysqli_real_escape_string($conn, trim($_POST['target_age'] ?? ''));
    $m_date = mysqli_real_escape_string($conn, trim($_POST['m_date'] ?? ''));
    $exp_date = mysqli_real_escape_string($conn, trim($_POST['exp_date'] ?? ''));
    $stock_status = mysqli_real_escape_string($conn, trim($_POST['stock_status'] ?? 'out'));
    $batch_info = mysqli_real_escape_string($conn, trim($_POST['batch_info'] ?? ''));

    if ($id <= 0 || $name === '' || $batch_no === '' || $doses <= 0 || $vaccine_type === '' || $target_age <= 0) {
        $message = 'Please fill all required fields.';
        $messageType = 'error';
    } else {
        $sqlu = "UPDATE vaccines SET vaccine_name='{$name}', batch_no='{$batch_no}', doses={$doses}, vaccine_type='{$vaccine_type}', target_age='{$target_age}', m_date='{$m_date}', exp_date='{$exp_date}', stock_status='{$stock_status}', batch_info='{$batch_info}' WHERE vaccine_id={$id} LIMIT 1";
        if (mysqli_query($conn, $sqlu)) {
            $message = 'Vaccine updated successfully.';
            $messageType = 'success';
        } else {
            $message = 'DB Error: ' . mysqli_error($conn);
            $messageType = 'error';
        }
    }
    header('Location:' . $_SERVER['PHP_SELF']);
    exit;
}

// Handle GET actions: delete, disable/enable
if (isset($_GET['action'], $_GET['id'])) {
    $action = $_GET['action'];
    $id = intval($_GET['id']);
    if ($id > 0) {
        if ($action === 'delete') {
            $sql = "DELETE FROM vaccines WHERE vaccine_id={$id} LIMIT 1";
            mysqli_query($conn, $sql);
            header('Location:' . $_SERVER['PHP_SELF']);
            exit;
        } elseif ($action === 'disable') {
            $sql = "UPDATE vaccines SET stock_status='out' WHERE vaccine_id={$id} LIMIT 1";
            mysqli_query($conn, $sql);
            header('Location:' . $_SERVER['PHP_SELF']);
            exit;
        } elseif ($action === 'enable') {
            $sql = "UPDATE vaccines SET stock_status='high' WHERE vaccine_id={$id} LIMIT 1";
            mysqli_query($conn, $sql);
            header('Location:' . $_SERVER['PHP_SELF']);
            exit;
        }
    }
}

// If editing, fetch record to prefill
$edit_vaccine = null;
if (isset($_GET['edit_id'])) {
    $eid = intval($_GET['edit_id']);
    if ($eid > 0) {
        $res = mysqli_query($conn, "SELECT * FROM vaccines WHERE vaccine_id={$eid} LIMIT 1");
        if ($res && mysqli_num_rows($res) > 0) $edit_vaccine = mysqli_fetch_assoc($res);
    }
}

$status = isset($_GET['status']) ? $_GET['status'] : 'all';
$q = isset($_GET['q']) ? trim($_GET['q']) : '';

$where = [];
if ($status === 'available') {
    $where[] = "stock_status IN ('high', 'low')";
} elseif ($status === 'unavailable') {
    $where[] = "stock_status = 'out'";
}
if ($q !== '') {
    $safe_q = mysqli_real_escape_string($conn, $q);
    $where[] = "(vaccine_id LIKE '%{$safe_q}%' OR vaccine_name LIKE '%{$safe_q}%' OR batch_no LIKE '%{$safe_q}%' OR vaccine_type LIKE '%{$safe_q}%')";
}

$where_sql = '';
if (count($where) > 0) $where_sql = 'WHERE ' . implode(' AND ', $where);

$sql = "SELECT * FROM vaccines {$where_sql} ORDER BY vaccine_id DESC";
$result = mysqli_query($conn, $sql);
?>
<style>
     /* Status Badges */
        .badge-available { background-color: #D1FAE5; color: #059669; } /* Green */
        .badge-unavailable { background-color: #FEE2E2; color: #DC2626; } /* Red */

        /* Dropdown Animation */
        .dropdown-menu {
            transform-origin: top right;
            transition: transform 0.1s ease-out, opacity 0.1s ease-out;
            transform: scale(0.95);
            opacity: 0;
            pointer-events: none;
        }
        .dropdown-menu.show {
            transform: scale(1);
            opacity: 1;
            pointer-events: auto;
        }
</style>

 <!-- Main Content -->
        <main class="relative lg:ml-64 pt-6 lg:pt-10 pb-10 min-h-screen bg-slate-50">
            
            <!-- Navbar -->
            <div class="sticky top-6 z-30 px-6 lg:px-10 mb-8 hidden lg:block">
                <div class="bg-white/80 backdrop-blur-xl border border-white/60 rounded-full px-6 py-3 shadow-sm flex justify-between items-center">
                    <h2 class="text-slate-800 font-bold text-lg pl-2">Vaccines</h2>
                    
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
                        <a href="vaccines.php?status=all" class="px-6 py-2 <?php echo $status==='all' ? 'bg-darkblue text-white' : 'bg-white text-gray-500 hover:text-primary hover:bg-gray-50'; ?> rounded-lg text-sm font-bold transition whitespace-nowrap">All Vaccines</a>
                        <a href="vaccines.php?status=available" class="px-6 py-2 <?php echo $status==='available' ? 'bg-darkblue text-white' : 'bg-white text-gray-500 hover:text-primary hover:bg-gray-50'; ?> rounded-lg text-sm font-bold transition whitespace-nowrap">Available</a>
                        <a href="vaccines.php?status=unavailable" class="px-6 py-2 <?php echo $status==='unavailable' ? 'bg-darkblue text-white' : 'bg-white text-gray-500 hover:text-primary hover:bg-gray-50'; ?> rounded-lg text-sm font-bold transition whitespace-nowrap">Unavailable</a>
                    </div>

                    <!-- Search & Filter -->
                    <form method="GET" action="vaccines.php" class="flex flex-col sm:flex-row gap-3 w-full xl:w-auto">
                        <input type="hidden" name="status" value="<?php echo htmlspecialchars($status); ?>">
                        <div class="relative w-full sm:w-64">
                            <input type="text" name="q" value="<?php echo htmlspecialchars($q); ?>" placeholder="Search vaccine name..." class="w-full pl-10 pr-4 py-2.5 bg-white border border-gray-200 rounded-xl shadow-sm focus:outline-none focus:border-primary transition text-sm">
                            <i data-lucide="search" class="absolute left-3 top-3 w-4 h-4 text-gray-400"></i>
                        </div>
                        <button type="submit" name="search" class="px-4 py-2.5 bg-darkblue text-white rounded-xl text-sm font-bold hover:bg-blue-800 transition shadow-md flex items-center justify-center gap-2 whitespace-nowrap">
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

                <!-- Edit Vaccine Form -->
                <?php if ($edit_vaccine){ ?>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
                        <h3 class="font-bold text-lg mb-4">Edit Vaccine (ID: <?php echo $edit_vaccine['vaccine_id']; ?>)</h3>
                        <form method="POST" action="vaccines.php" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <input type="hidden" name="vaccine_id" value="<?php echo intval($edit_vaccine['vaccine_id']); ?>">
                            <div>
                                <label class="text-xs font-bold text-gray-600">Vaccine Name</label>
                                <input name="vaccine_name" value="<?php echo htmlspecialchars($edit_vaccine['vaccine_name']); ?>" class="w-full mt-1 p-2 border rounded-lg text-sm" required>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-gray-600">Batch No</label>
                                <input name="batch_no" value="<?php echo htmlspecialchars($edit_vaccine['batch_no']); ?>" class="w-full mt-1 p-2 border rounded-lg text-sm" required>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-gray-600">Doses Required</label>
                                <input type="number" name="doses" value="<?php echo intval($edit_vaccine['doses']); ?>" class="w-full mt-1 p-2 border rounded-lg text-sm" required>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-gray-600">Vaccine Type</label>
                                <select name="vaccine_type" class="w-full mt-1 p-2 border rounded-lg text-sm" required>
                                    <option value="">Select Type</option>
                                        <option value="Oral " <?php echo (strcasecmp($edit_vaccine['vaccine_type'] ?? '', 'Oral') === 0) ? 'selected' : ''; ?>>Oral</option>
                                        <option value="Injection" <?php echo (strcasecmp($edit_vaccine['vaccine_type'] ?? '', 'Injection') === 0) ? 'selected' : ''; ?>>Injection</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-gray-600">Target Age (months)</label>
                                <input type="text" name="target_age" value="<?php echo intval($edit_vaccine['target_age']); ?>" min="0" class="w-full mt-1 p-2 border rounded-lg text-sm" required>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-gray-600">Manufacture Date</label>
                                <input type="date" name="m_date" value="<?php echo htmlspecialchars($edit_vaccine['m_date']); ?>" class="w-full mt-1 p-2 border rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="text-xs font-bold text-gray-600">Expiry Date</label>
                                <input type="date" name="exp_date" value="<?php echo htmlspecialchars($edit_vaccine['exp_date']); ?>" class="w-full mt-1 p-2 border rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="text-xs font-bold text-gray-600">Stock Status</label>
                                <select name="stock_status" class="w-full mt-1 p-2 border rounded-lg text-sm">
                                    <option value="high" <?php echo ($edit_vaccine['stock_status'] === 'high') ? 'selected' : ''; ?>>High</option>
                                    <option value="low" <?php echo ($edit_vaccine['stock_status'] === 'low') ? 'selected' : ''; ?>>Low</option>
                                    <option value="out" <?php echo ($edit_vaccine['stock_status'] === 'out') ? 'selected' : ''; ?>>Out</option>
                                </select>
                            </div>
                            <div class="md:col-span-2">
                                <label class="text-xs font-bold text-gray-600">Batch Info</label>
                                <textarea name="batch_info" rows="2" class="w-full mt-1 p-2 border rounded-lg text-sm"><?php echo htmlspecialchars($edit_vaccine['batch_info']); ?></textarea>
                            </div>
                            <div class="md:col-span-2 flex gap-2 mt-2">
                                <button type="submit" name="edit_vaccine" class="px-4 py-2 bg-primary text-white rounded-lg font-bold">Save Changes</button>
                                <a href="vaccines.php" class="px-4 py-2 border rounded-lg">Cancel</a>
                            </div>
                        </form>
                    </div>
                <?php }; ?>

                
                <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-gray-600">
                            <thead class="bg-gray-50 text-gray-800 font-bold uppercase text-xs tracking-wider">
                                <tr>
                                    <th class="px-6 py-4 pl-8">Vaccine Name</th>
                                    <th class="px-6 py-4">Type</th>
                                    <th class="px-6 py-4">Doses Required</th>
                                    <th class="px-6 py-4">Target Age</th>
                                    <th class="px-6 py-4">Hospital</th>
                                    <th class="px-6 py-4">Status</th>
                                    <th class="px-6 py-4 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <?php
                                if ($result && mysqli_num_rows($result) > 0) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $isAvailable = ($row['stock_status'] !== 'out');
                                        $statusBadgeClass = $isAvailable ? 'badge-available' : 'badge-unavailable';
                                        $statusText = $isAvailable ? 'Available' : 'Unavailable';
                                ?>
                                <tr class="hover:bg-blue-50/50 transition group <?php echo !$isAvailable ? 'bg-red-50/20' : ''; ?>">
                                    <td class="px-6 py-4 pl-8">
                                        <div class="flex items-center gap-3">
                                            <?php
                                                // choose icon based on vaccine_type (case-insensitive)
                                                $vt = $row['vaccine_type'] ?? '';
                                                if (strcasecmp($vt, 'Oral') === 0) {
                                                    $icon = 'droplet';
                                                    $class = 'bg-blue-50 text-blue-600';
                                                } elseif (strcasecmp($vt, 'Injection') === 0) {
                                                    $icon = 'syringe';
                                                    $class = 'bg-red-50 text-red-600';
                                                } else {
                                                    $icon = 'shield';
                                                }
                                                ?>
                                            <div class="w-10 h-10 rounded-lg <?php echo $isAvailable ?  $class : 'bg-gray-100 text-gray-500 opacity-60'; ?> flex items-center justify-center">
                                                <i data-lucide="<?php echo $icon; ?>" class="w-5 h-5"></i>
                                            </div>
                                            <span class="font-bold text-slate-800"><?php echo htmlspecialchars($row['vaccine_name']); ?></span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-xs font-medium"><?php echo htmlspecialchars($row['vaccine_type']); ?></td>
                                    <td class="px-6 py-4 text-xs"><?php echo intval($row['doses']); ?> Dose(s)</td>
                                    <td class="px-6 py-4 text-xs"><?php echo htmlspecialchars($row['target_age']); ?></td>
                                    <?php
                                    $sql_hos = " SELECT hospital_name FROM hospitals WHERE hospital_id = '$row[hospital_id]' " ;
                                    $result_hos = mysqli_query($conn, $sql_hos);
                                    $row_hos = mysqli_fetch_assoc($result_hos);
                                    ?>

                                    <td class="px-6 py-4 text-xs"><?php echo htmlspecialchars($row_hos['hospital_name']); ?></td>
                                    <td class="px-6 py-4"><span class="<?php echo $statusBadgeClass; ?> px-3 py-1 rounded-full text-xs font-bold"><?php echo $statusText; ?></span></td>
                                    <td class="px-6 py-4 text-center relative">
                                        <button onclick="toggleDropdown(this)" class="p-2 hover:bg-gray-100 rounded-lg text-gray-500 transition focus:outline-none">
                                            <i data-lucide="more-vertical" class="w-4 h-4"></i>
                                        </button>
                                        <div class="dropdown-menu absolute right-8 top-8 w-36 bg-white rounded-xl shadow-xl border border-gray-100 z-10 overflow-hidden">
                                            <a href="?edit_id=<?php echo $row['vaccine_id']; ?>" class="block px-4 py-2 text-left text-xs font-medium text-gray-700 hover:bg-gray-50 hover:text-primary flex items-center gap-2">
                                                <i data-lucide="edit" class="w-3 h-3"></i> Edit
                                            </a>
                                            <?php if ($isAvailable) { ?>
                                            <a href="?action=disable&id=<?php echo $row['vaccine_id']; ?>" class="block px-4 py-2 text-left text-xs font-medium text-red-500 hover:bg-red-50 flex items-center gap-2">
                                                <i data-lucide="power" class="w-3 h-3"></i> Disable
                                            </a>
                                            <?php } else { ?>
                                            <a href="?action=enable&id=<?php echo $row['vaccine_id']; ?>" class="block px-4 py-2 text-left text-xs font-medium text-green-600 hover:bg-green-50 flex items-center gap-2">
                                                <i data-lucide="check-circle" class="w-3 h-3"></i> Enable
                                            </a>
                                            <?php } ?>
                                            <a href="?action=delete&id=<?php echo $row['vaccine_id']; ?>" class="block px-4 py-2 text-left text-xs font-medium text-red-500 hover:bg-red-50 flex items-center gap-2" onclick="return confirm('Delete this vaccine?')">
                                                <i data-lucide="trash-2" class="w-3 h-3"></i> Remove
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php
                                    }
                                } else {
                                ?>
                                <!-- Fallback row -->
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                        <i data-lucide="syringe" class="w-10 h-10 mx-auto mb-4"></i>
                                        <div class="font-bold">No vaccines found.</div>
                                        <div class="text-xs mt-1">Try adjusting your filters or search query.</div>
                                    </td>
                                </tr>
                                <?php } ?>

                            </tbody>
                        </table>
                    </div>
                    
                </div>

            </div>
        </main>

        <script>
            // Toggle Dropdown
            function toggleDropdown(btn) {
                // Close all other dropdowns
                const allDropdowns = document.querySelectorAll('.dropdown-menu');
                allDropdowns.forEach(d => {
                    if (d !== btn.nextElementSibling) d.classList.remove('show');
                });

                // Toggle current
                const dropdown = btn.nextElementSibling;
                dropdown.classList.toggle('show');
            }

            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!e.target.closest('td.relative')) {
                    document.querySelectorAll('.dropdown-menu').forEach(d => d.classList.remove('show'));
                }
            });
        </script>

<?php
include 'admin_partials/inc_footer.php';
ob_end_flush();
?>