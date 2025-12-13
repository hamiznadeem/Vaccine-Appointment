    <!-- Footer Section -->
    <footer class="bg-darkblue text-white pt-20 pb-10 rounded-t-[3rem]">
        <!-- ... existing footer code ... -->
        <div
            class="max-w-[1400px] mx-auto px-6 md:px-20 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 border-b border-gray-700 pb-12">
            <div>
                <div class="text-2xl font-bold tracking-wide mb-6">Vaccining</div>
                <p class="text-gray-400 text-sm leading-relaxed mb-6">
                    Our goal is to help the world became a better place with health and vaccination.
                </p>
                <div class="flex gap-4">
                    <a href="#"
                        class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center hover:bg-primary transition text-white">
                        <i data-lucide="facebook" class="w-5 h-5"></i>
                    </a>
                    <a href="#"
                        class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center hover:bg-primary transition text-white">
                        <i data-lucide="twitter" class="w-5 h-5"></i>
                    </a>
                    <a href="#"
                        class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center hover:bg-primary transition text-white">
                        <i data-lucide="instagram" class="w-5 h-5"></i>
                    </a>
                </div>
            </div>
            <div>
                <h4 class="font-bold text-lg mb-6">Menu</h4>
                <?php if (isset($_SESSION['isLogin']) && $_SESSION['isLogin'] === true) {

                if ($user_role == 4) { ?>
                <ul class="space-y-4 text-gray-400 text-sm">
                    <li><a href="index.php" class="<?php echo $current_page === 'index.php' ? 'underline text-primary hover:text-primary transition' : 'text-white hover:text-primary transition'; ?>" <?php if ($current_page === 'index.php') echo 'aria-current="page"'; ?>>Home</a></li>
                    <li><a href="parents_profile.php" class="<?php echo $current_page === 'parents_profile.php' ? 'underline text-primary hover:text-primary transition' : 'text-white hover:text-primary transition'; ?>" <?php if ($current_page === 'parents_profile.php') echo 'aria-current="page"'; ?>>Profile</a></li>
                    <li><a href="parents_hospitals.php" class="<?php echo $current_page === 'parents_hospitals.php' ? 'underline text-primary hover:text-primary transition' : 'text-white hover:text-primary transition'; ?>" <?php if ($current_page === 'parents_hospitals.php') echo 'aria-current="page"'; ?>>Hospitals</a></li>
                    <li><a href="parents_vaccines.php" class="<?php echo $current_page === 'parents_vaccines.php' ? 'underline text-primary hover:text-primary transition' : 'text-white hover:text-primary transition'; ?>" <?php if ($current_page === 'parents_vaccines.php') echo 'aria-current="page"'; ?>>Vaccines</a></li>
                    <li><a href="parents_appointments.php" class="<?php echo $current_page === 'parents_appointments.php' ? 'underline text-primary hover:text-primary transition' : 'text-white hover:text-primary transition'; ?>" <?php if ($current_page === 'parents_appointments.php') echo 'aria-current="page"'; ?>>Appointments</a></li>
                </ul>
                <?php } elseif ($_SESSION['user_role'] == 3) { ?>
                <ul class="space-y-4 text-gray-400 text-sm">
                    <li><a href="index.php" class="<?php echo $current_page === 'index.php' ? 'underline text-primary hover:text-primary transition' : 'text-white hover:text-primary transition'; ?>" <?php if ($current_page === 'index.php') echo 'aria-current="page"'; ?>>Home</a></li>
                    <li><a href="hospital_profile.php" class="<?php echo $current_page === 'hospital_profile.php' ? 'underline text-primary hover:text-primary transition' : 'text-white hover:text-primary transition'; ?>" <?php if ($current_page === 'hospital_profile.php') echo 'aria-current="page"'; ?>>Profile</a></li>
                    <li><a href="hospital_inven.php" class="<?php echo $current_page === 'hospital_inven.php' ? 'underline text-primary hover:text-primary transition' : 'text-white hover:text-primary transition'; ?>" <?php if ($current_page === 'hospital_inven.php') echo 'aria-current="page"'; ?>>Inventory</a></li>
                    <li><a href="hospital_appointment.php" class="<?php echo $current_page === 'hospital_appointment.php' ? 'underline text-primary hover:text-primary transition' : 'text-white hover:text-primary transition'; ?>" <?php if ($current_page === 'hospital_appointment.php') echo 'aria-current="page"'; ?>>Appointment</a></li>
                </ul>
                <?php }
            } else { ?>
                <!-- Sign In / Login) -->
                <ul class="space-y-4 text-gray-400 text-sm">
                    <li><a href="index.php" class="<?php echo $current_page === 'index.php' ? 'underline text-primary hover:text-primary transition' : 'text-white hover:text-primary transition'; ?>" <?php if ($current_page === 'index.php') echo 'aria-current="page"'; ?>>Home</a></li>
                    <li><a href="login.php" class="<?php echo $current_page === 'login.php' ? 'underline text-primary hover:text-primary transition' : 'text-white hover:text-primary transition'; ?>" <?php if ($current_page === 'login.php') echo 'aria-current="page"'; ?>>Login</a></li>
                    <li><a href="register.php" class="<?php echo $current_page === 'register.php' ? 'underline text-primary hover:text-primary transition' : 'text-white hover:text-primary transition'; ?>" <?php if ($current_page === 'register.php') echo 'aria-current="page"'; ?>>Register</a></li>
                </ul>
            <?php } ?>
            </div>
            <div>
                <h4 class="font-bold text-lg mb-6">Support</h4>
                <ul class="space-y-4 text-gray-400 text-sm">
                    <li><a href="contact.php" class="<?php echo $current_page === 'contact.php' ? 'underline text-primary hover:text-primary transition' : 'text-white hover:text-primary transition'; ?>" <?php if ($current_page === 'contact.php') echo 'aria-current="page"'; ?>>Contact Us</a></li>
                    <li><a href="faqs.php" class="<?php echo $current_page === 'faqs.php' ? 'underline text-primary hover:text-primary transition' : 'text-white hover:text-primary transition'; ?>" <?php if ($current_page === 'faqs.php') echo 'aria-current="page"'; ?>>FAQs</a></li>
                    <li><a href="privacy&policy.php" class="<?php echo $current_page === 'privacy&policy.php' ? 'underline text-primary hover:text-primary transition' : 'text-white hover:text-primary transition'; ?>" <?php if ($current_page === 'privacy&policy.php') echo 'aria-current="page"'; ?>>Privacy Policy</a></li>
                    <li><a href="terms&condition.php" class="<?php echo $current_page === 'terms&condition.php' ? 'underline text-primary hover:text-primary transition' : 'text-white hover:text-primary transition'; ?>" <?php if ($current_page === 'terms&condition.php') echo 'aria-current="page"'; ?>>Terms of Service</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold text-lg mb-6">Contact Us</h4>
                <ul class="space-y-4 text-gray-400 text-sm">
                    <li class="flex items-start gap-3">
                        <i data-lucide="phone" class="w-5 h-5 text-primary"></i>
                        <span>+1 234 567 890</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i data-lucide="mail" class="w-5 h-5 text-primary"></i>
                        <span>support@vaccining.com</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i data-lucide="map-pin" class="w-5 h-5 text-primary"></i>
                        <span>123 Health Street, NY</span>
                    </li>
                </ul>
            </div>
        </div>
        <div class="max-w-[1400px] mx-auto px-6 md:px-20 pt-8 text-center text-gray-500 text-sm">
            &copy; 2024 Vaccining. All rights reserved.
        </div>
    </footer>

    <!-- main js files -->
    <script src="./asset/js/app.js?v=1.1"></script>
    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
</body>
</html>