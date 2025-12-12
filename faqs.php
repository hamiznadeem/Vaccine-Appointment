 <?php
 $title = "FAQ's";
    include 'partials/inc_header.php';
 ?>
 <!-- Main Content -->
    <div class="pt-40 pb-20 px-6 md:px-20 max-w-[1000px] mx-auto flex-grow w-full fade-in">
        
        <div class="text-center mb-12">
            <span class="text-primary font-bold text-sm tracking-widest uppercase mb-2 block">Support</span>
            <h1 class="text-4xl md:text-5xl font-bold text-darkblue mb-4">Frequently Asked Questions</h1>
            <p class="text-gray-500 max-w-2xl mx-auto">
                Find answers to common questions about vaccine appointments, schedules, and medical information.
            </p>
        </div>

        <!-- Search Bar -->
        <div class="max-w-xl mx-auto mb-16 relative">
            <input type="text" placeholder="Search for a question..." class="w-full py-4 pl-12 pr-6 rounded-2xl border border-gray-200 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 shadow-sm transition">
            <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 w-5 h-5"></i>
        </div>

        <div class="grid grid-cols-1 gap-6">
            
            <!-- Category: General -->
            <div class="mb-4">
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <i data-lucide="zap" class="w-5 h-5 text-primary"></i> General
                </h3>
                
                <div class="space-y-4">
                    <!-- FAQ Item 1 -->
                    <div class="accordion-item bg-white rounded-2xl p-6 border border-gray-100 cursor-pointer shadow-sm hover:shadow-md transition duration-300" onclick="toggleAccordion(this)">
                        <div class="flex justify-between items-center">
                            <h4 class="font-bold text-gray-800 text-lg">Is the vaccination free of cost?</h4>
                            <i data-lucide="chevron-down" class="chevron-icon w-5 h-5 text-gray-400 transition-transform duration-300"></i>
                        </div>
                        <div class="accordion-content">
                            <p class="text-gray-500 text-sm leading-relaxed mt-4 pt-4 border-t border-gray-100">
                                Yes, the government provides vaccines free of charge at all public health centers. However, some private hospitals may charge a service fee for administration.
                            </p>
                        </div>
                    </div>

                    <!-- FAQ Item 2 -->
                    <div class="accordion-item bg-white rounded-2xl p-6 border border-gray-100 cursor-pointer shadow-sm hover:shadow-md transition duration-300" onclick="toggleAccordion(this)">
                        <div class="flex justify-between items-center">
                            <h4 class="font-bold text-gray-800 text-lg">What documents do I need to bring?</h4>
                            <i data-lucide="chevron-down" class="chevron-icon w-5 h-5 text-gray-400 transition-transform duration-300"></i>
                        </div>
                        <div class="accordion-content">
                            <p class="text-gray-500 text-sm leading-relaxed mt-4 pt-4 border-t border-gray-100">
                                You need to bring a valid ID card (KTP/Passport) and your booking confirmation (digital or printed). If it is your second dose, please bring your vaccination card.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Category: Booking -->
            <div class="mt-8 mb-4">
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <i data-lucide="calendar" class="w-5 h-5 text-primary"></i> Booking & Schedule
                </h3>

                <div class="space-y-4">
                    <!-- FAQ Item 3 -->
                    <div class="accordion-item bg-white rounded-2xl p-6 border border-gray-100 cursor-pointer shadow-sm hover:shadow-md transition duration-300" onclick="toggleAccordion(this)">
                        <div class="flex justify-between items-center">
                            <h4 class="font-bold text-gray-800 text-lg">Can I reschedule my appointment?</h4>
                            <i data-lucide="chevron-down" class="chevron-icon w-5 h-5 text-gray-400 transition-transform duration-300"></i>
                        </div>
                        <div class="accordion-content">
                            <p class="text-gray-500 text-sm leading-relaxed mt-4 pt-4 border-t border-gray-100">
                                Yes, you can reschedule up to 24 hours before your slot time. Go to "My Booking" page, select your active appointment, and click on "Reschedule".
                            </p>
                        </div>
                    </div>

                    <!-- FAQ Item 4 -->
                    <div class="accordion-item bg-white rounded-2xl p-6 border border-gray-100 cursor-pointer shadow-sm hover:shadow-md transition duration-300" onclick="toggleAccordion(this)">
                        <div class="flex justify-between items-center">
                            <h4 class="font-bold text-gray-800 text-lg">How do I know if my booking is confirmed?</h4>
                            <i data-lucide="chevron-down" class="chevron-icon w-5 h-5 text-gray-400 transition-transform duration-300"></i>
                        </div>
                        <div class="accordion-content">
                            <p class="text-gray-500 text-sm leading-relaxed mt-4 pt-4 border-t border-gray-100">
                                Once you complete the booking process, you will receive an SMS and an Email with your appointment details and a QR code.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Category: Medical -->
            <div class="mt-8">
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <i data-lucide="flask-conical" class="w-5 h-5 text-primary"></i> Medical Info
                </h3>

                <div class="space-y-4">
                    <!-- FAQ Item 5 -->
                    <div class="accordion-item bg-white rounded-2xl p-6 border border-gray-100 cursor-pointer shadow-sm hover:shadow-md transition duration-300" onclick="toggleAccordion(this)">
                        <div class="flex justify-between items-center">
                            <h4 class="font-bold text-gray-800 text-lg">Are there side effects?</h4>
                            <i data-lucide="chevron-down" class="chevron-icon w-5 h-5 text-gray-400 transition-transform duration-300"></i>
                        </div>
                        <div class="accordion-content">
                            <p class="text-gray-500 text-sm leading-relaxed mt-4 pt-4 border-t border-gray-100">
                                Mild side effects like sore arm, fatigue, or mild fever are common and usually go away within 1-2 days. Serious side effects are extremely rare.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Still have questions card -->
        <div class="mt-20 bg-primary/5 rounded-[2.5rem] p-10 text-center border border-primary/20">
            <h3 class="text-2xl font-bold text-darkblue mb-3">Still have questions?</h3>
            <p class="text-gray-500 mb-8 max-w-lg mx-auto">Can't find the answer you're looking for? Please chat to our friendly team.</p>
            <button class="bg-primary text-white px-8 py-3.5 rounded-xl font-bold shadow-lg shadow-primary/30 hover:bg-cyan-500 transition">
                Get in Touch
            </button>
        </div>
    </div>

<?php include 'partials/inc_footer.php'; ?>