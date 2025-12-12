<?php
$title = 'Appointment Successful';
include 'partials/inc_header.php';

// Check Login & Role
if (!isset($_SESSION['isLogin'])) {
    header('Location: login.php');
    exit;
} elseif ($_SESSION['user_role'] != 4) { // 4 = Parent
    header('Location: index.php');
    exit;
}

?>

<div class="min-h-[80vh] flex items-center justify-center px-6 py-12 pt-36">
    
    <div class="bg-white p-8 md:p-12 rounded-[2.5rem] shadow-2xl shadow-blue-900/10 border border-gray-100 max-w-md w-full text-center fade-in">
        
        <div class="mb-8 relative">
            <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto animate-bounce-slow">
                <i data-lucide="check" class="w-10 h-10 text-green-600 stroke-[3px]"></i>
            </div>
            <div class="absolute top-0 right-[30%] w-3 h-3 bg-blue-400 rounded-full animate-ping"></div>
            <div class="absolute bottom-2 left-[30%] w-2 h-2 bg-yellow-400 rounded-full"></div>
        </div>

        <h1 class="text-3xl font-bold text-gray-900 mb-3">Booking Confirmed!</h1>
        <p class="text-gray-500 text-sm leading-relaxed mb-8">
            Your vaccination appointment has been successfully scheduled. Please reach the hospital 15 minutes before your time slot.
        </p>

        <div class="space-y-3">
            <a href="parents_appointments.php" class="block w-full py-4 bg-darkblue text-white rounded-2xl font-bold shadow-lg shadow-blue-900/20 hover:bg-blue-900 transition transform hover:-translate-y-1">
                View My Appointments
            </a>

            <a href="index.php" class="block w-full py-4 bg-gray-50 text-gray-600 rounded-2xl font-bold hover:bg-gray-100 transition border border-gray-200">
                Back to Home
            </a>
        </div>

    </div>
</div>

<style>
    .fade-in {
        animation: fadeIn 0.5s ease-out forwards;
    }
    .animate-bounce-slow {
        animation: bounce 2s infinite;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<?php
include 'partials/inc_footer.php';
?>