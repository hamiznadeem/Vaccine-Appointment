<?php
$title = 'Vaccining';
include 'partials/inc_header.php';

$sql = "SELECT * FROM hospitals LIMIT 9";
$result = mysqli_query($conn, $sql);

?>
<style>
    /* --- Swiper Custom Styling --- */
    .swiper {
        width: 100%;
        padding-bottom: 50px !important;
        padding-top: 50px !important;
        overflow: hidden !important;
    }

    .vaccine-card {
        transition: all 0.4s ease-out;
        background-color: white;
        border: 1px solid #f3f4f6;
        transform: scale(0.9);
        opacity: 0.8;
    }

    /* Styles specifically for the Vaccine Swiper (Zoom Effect) */
    .vaccineSwiper .vaccine-card h3 {
        color: #111827;
    }

    .vaccineSwiper .vaccine-card p {
        color: #6B7280;
    }

    .vaccineSwiper .vaccine-icon-box {
        background-color: #EFF6FF;
        color: #17C2EC;
    }

    .vaccineSwiper .swiper-slide-active .vaccine-card {
        background-color: #0B153C;
        transform: scale(1.1);
        opacity: 1;
        border-color: #0B153C;
    }

    .vaccineSwiper .swiper-slide-active .vaccine-card h3 {
        color: white !important;
    }

    .vaccineSwiper .swiper-slide-active .vaccine-card p {
        color: #9CA3AF !important;
    }

    .vaccineSwiper .swiper-slide-active .vaccine-icon-box {
        background-color: rgba(23, 194, 236, 0.2);
        color: #17C2EC;
    }

    /* Styles for Location Swiper (Simple Slide, No Zoom) */
    .locationSwiper {
        padding-top: 20px !important;
        /* Less padding as no zoom needed */
    }


    .swiper-pagination-bullet {
        background: #cbd5e1;
        opacity: 1;
    }

    .swiper-pagination-bullet-active {
        background: #17C2EC;
        width: 20px;
        border-radius: 5px;
        transition: all 0.3s;
    }
</style>


<!-- Main Grid Layout -->
<div class="relative w-full">

    <!-- Background Split -->
    <div
        class="absolute top-0 left-0 w-full h-[650px] md:h-[750px] lg:h-[850px] bg-darkblue rounded-br-[3rem] md:rounded-br-[5rem] z-0 overflow-hidden">
        <div
            class="absolute -top-24 -left-24 w-64 md:w-96 h-64 md:h-96 bg-primary/20 rounded-full blur-[80px] md:blur-[100px]">
        </div>
        <div
            class="absolute bottom-0 right-0 w-[400px] md:w-[600px] h-[400px] md:h-[600px] bg-blue-600/10 rounded-full blur-[80px] md:blur-[100px]">
        </div>
        <svg class="absolute top-1/4 left-0 w-full opacity-10 pointer-events-none" viewBox="0 0 1440 320">
            <path fill="none" stroke="#17C2EC" stroke-width="1.5"
                d="M0,160L48,176C96,192,192,224,288,224C384,224,480,192,576,165.3C672,139,768,117,864,128C960,139,1056,181,1152,186.7C1248,192,1344,160,1392,144L1440,128">
            </path>
            <path fill="none" stroke="#17C2EC" stroke-width="1.5"
                d="M0,192L48,197.3C96,203,192,213,288,202.7C384,192,480,160,576,149.3C672,139,768,149,864,165.3C960,181,1056,203,1152,208C1248,213,1344,203,1392,197.3L1440,192"
                style="opacity: 0.5"></path>
        </svg>
    </div>

    <!-- Content Container -->
    <div
        class="relative z-10 max-w-[1400px] mx-auto pt-32 md:pt-20 px-6 md:px-20 grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 min-h-[650px] md:min-h-[750px] lg:min-h-[850px]">

        <!-- LEFT COLUMN: Hero Text -->
        <div class="lg:col-span-5 flex flex-col justify-start pt-4 lg:pt-16 order-1 lg:order-1">
            <span
                class="text-primary font-semibold tracking-wide mb-4 flex items-center gap-2 text-sm md:text-base">
                Free Government Immunization Drive
            </span>
            <h1
                class="text-4xl md:text-5xl lg:text-[3.5rem] font-bold text-white leading-[1.2] lg:leading-[1.15] mb-6">
                Find Free Vaccine <br />
                Camps Near You <br />
                <span class="text-white">Easily</span>
            </h1>
            <p class="text-gray-400 text-sm md:text-[15px] leading-relaxed mb-8 md:mb-10 max-w-md font-light">
                We connect parents with hospitals providing free government vaccines. Book a slot to avoid long lines and ensure your child gets vaccinated on time.
            </p>
            <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 4) { ?>
                <div class="flex items-center space-x-4 md:space-x-5">
                    <a href="parents_hospitals.php"
                        class="px-5 md:px-8 py-3 md:py-4 bg-primary text-white font-bold rounded-2xl shadow-xl shadow-primary/30 hover:bg-cyan-400 transition transform hover:-translate-y-1 text-sm md:text-base">
                        Find Free Camp
                    </a>
                </div>
            <?php } ?>
        </div>

        <!-- RIGHT COLUMN -->
        <div class="hidden lg:col-span-7 relative h-[650px] lg:flex justify-center items-center perspective-1000 order-2 lg:order-2 mt-0">

            <div class="relative">
                <!-- Glow -->
                <div class="w-96 h-96 bg-white/10 rounded-full absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 blur-3xl animate-pulse"></div>

                <!-- Cute Central Card -->
                <div class="relative bg-white/95 backdrop-blur-md p-8 rounded-[3rem] shadow-2xl border-4 border-white/50 w-80 text-center transform hover:scale-105 transition duration-500">
                    <!-- Icon -->
                    <div class="w-32 h-32 bg-blue-100 rounded-full mx-auto mb-6 flex items-center justify-center border-4 border-white shadow-md relative">
                        <span class="text-xl"><i data-lucide="baby" class="w-24 h-24 text-primary"></i></span>
                        <!-- Shield Badge -->
                        <div class="absolute -bottom-2 -right-2 bg-green-500 text-white p-2 rounded-full border-4 border-white shadow-sm">
                            <i data-lucide="shield-check" class="w-6 h-6"></i>
                        </div>
                    </div>

                    <!-- Text -->
                    <h3 class="text-2xl font-bold text-gray-800 mb-1">Safe Child</h3>
                    <p class="text-sm text-gray-500 font-medium mb-6">Fully Protected</p>

                    <!-- Status Bar -->
                    <div class="bg-gray-100 rounded-full h-4 w-full mb-2 overflow-hidden border border-gray-200">
                        <div class="bg-gradient-to-r from-blue-400 to-primary h-full w-3/4 rounded-full shadow-sm"></div>
                    </div>
                    <div class="flex justify-between text-xs font-bold text-gray-400 px-1">
                        <span>0%</span>
                        <span class="text-primary">75% Complete</span>
                        <span>100%</span>
                    </div>

                    <!-- Floating Decors -->
                    <div class="absolute -top-6 -left-6 w-12 h-12 bg-yellow-300 rounded-full flex items-center justify-center text-xl shadow-lg animate-bounce delay-100 border-2 border-white">⭐</div>
                    <div class="absolute top-10 -right-10 w-12 h-12 bg-pink-300 rounded-full flex items-center justify-center text-xl shadow-lg animate-bounce delay-300 border-2 border-white">❤️</div>
                    <div class="absolute -bottom-4 left-10 w-10 h-10 bg-cyan-300 rounded-full flex items-center justify-center text-lg shadow-lg animate-bounce delay-500 border-2 border-white">💉</div>
                </div>
            </div>

        </div>
    </div>

    <!-- HOW IT WORKS SECTION -->
    <div class="relative max-w-[1400px] mx-auto px-6 md:px-20 pt-16 lg:pt-20">
        <div class="text-center mb-16">
            <span class="text-primary font-bold text-xs md:text-sm uppercase tracking-wider">Simple Process</span>
            <h2 class="text-2xl md:text-3xl lg:text-4xl font-bold text-gray-900 mt-2">How It Works</h2>
            <p class="text-gray-500 mt-4 max-w-2xl mx-auto">Skip the queue at government camps in 4 easy steps.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 relative">
            <!-- Connecting Line -->
            <div class="hidden lg:block absolute top-12 left-0 w-full h-0.5 bg-dashed border-t-2 border-gray-200 -z-10 transform -translate-y-1/2"></div>

            <!-- Step 1 -->
            <div class="bg-white p-6 rounded-[2rem] shadow-lg border border-gray-100 relative group hover:-translate-y-2 transition duration-300 text-center">
                <div class="w-16 md:w-20 h-16 md:h-20 bg-blue-50 text-primary rounded-full flex items-center justify-center mx-auto mb-6 shadow-sm group-hover:bg-primary group-hover:text-white transition-colors duration-300 relative z-10">
                    <i data-lucide="tent" class="w-7 md:w-8 h-7 md:h-8"></i>
                </div>
                <div class="absolute -top-3 -right-3 w-8 h-8 bg-darkblue text-white rounded-full flex items-center justify-center font-bold text-sm shadow-md">1</div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Find Camp</h3>
                <p class="text-sm text-gray-500 leading-relaxed">Search for hospitals running active free government vaccination drives.</p>
            </div>

            <!-- Step 2 -->
            <div class="bg-white p-6 rounded-[2rem] shadow-lg border border-gray-100 relative group hover:-translate-y-2 transition duration-300 text-center">
                <div class="w-16 md:w-20 h-16 md:h-20 bg-purple-50 text-purple-500 rounded-full flex items-center justify-center mx-auto mb-6 shadow-sm group-hover:bg-purple-500 group-hover:text-white transition-colors duration-300 relative z-10">
                    <i data-lucide="baby" class="w-7 md:w-8 h-7 md:h-8"></i>
                </div>
                <div class="absolute -top-3 -right-3 w-8 h-8 bg-darkblue text-white rounded-full flex items-center justify-center font-bold text-sm shadow-md">2</div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Check Vaccine</h3>
                <p class="text-sm text-gray-500 leading-relaxed">See which government vaccines (Polio, Measles, etc.) are available.</p>
            </div>

            <!-- Step 3 -->
            <div class="bg-white p-6 rounded-[2rem] shadow-lg border border-gray-100 relative group hover:-translate-y-2 transition duration-300 text-center">
                <div class="w-16 md:w-20 h-16 md:h-20 bg-orange-50 text-orange-500 rounded-full flex items-center justify-center mx-auto mb-6 shadow-sm group-hover:bg-orange-500 group-hover:text-white transition-colors duration-300 relative z-10">
                    <i data-lucide="ticket" class="w-7 md:w-8 h-7 md:h-8"></i>
                </div>
                <div class="absolute -top-3 -right-3 w-8 h-8 bg-darkblue text-white rounded-full flex items-center justify-center font-bold text-sm shadow-md">3</div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Book Free Slot</h3>
                <p class="text-sm text-gray-500 leading-relaxed">Reserve a time slot for free. No charges, just convenience.</p>
            </div>

            <!-- Step 4 -->
            <div class="bg-white p-6 rounded-[2rem] shadow-lg border border-gray-100 relative group hover:-translate-y-2 transition duration-300 text-center">
                <div class="w-16 md:w-20 h-16 md:h-20 bg-green-50 text-green-500 rounded-full flex items-center justify-center mx-auto mb-6 shadow-sm group-hover:bg-green-500 group-hover:text-white transition-colors duration-300 relative z-10">
                    <i data-lucide="check-circle-2" class="w-7 md:w-8 h-7 md:h-8"></i>
                </div>
                <div class="absolute -top-3 -right-3 w-8 h-8 bg-darkblue text-white rounded-full flex items-center justify-center font-bold text-sm shadow-md">4</div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Visit Hospital</h3>
                <p class="text-sm text-gray-500 leading-relaxed">Show your booking at the hospital and get your child vaccinated.</p>
            </div>
        </div>
    </div>

    <!-- LOWER SECTION  -->
    <div id="features"
        class="relative max-w-[1400px] mx-auto px-6 md:px-20 pb-20 mt-16 lg:mt-24 grid grid-cols-1 lg:grid-cols-12 gap-16 lg:gap-24">

        <!-- Left Side: Vaccine Variants  -->
        <div class="lg:col-span-7 pt-0 lg:pt-10">
            <div class="mb-8 md:mb-10">
                <span class="text-primary font-bold text-xs md:text-sm uppercase tracking-wider">Free Vaccines Available</span>
                <h2 class="text-2xl md:text-3xl lg:text-4xl font-bold text-gray-900 mt-2">Government Provided</h2>
            </div>

            <!-- Swiper Container: Vaccine -->
            <div class="swiper vaccineSwiper h-80 md:h-96 lg:h-[430px]">
                <div class="swiper-wrapper">

                    <!-- Slide 1: Polio -->
                    <div class="swiper-slide">
                        <div class="vaccine-card p-6 rounded-[2rem] shadow-sm flex flex-col justify-between h-64 md:h-72 lg:h-80">
                            <div>
                                <div
                                    class="vaccine-icon-box w-12 h-12 rounded-2xl flex items-center justify-center mb-6">
                                    <i data-lucide="shield" class="w-6 h-6"></i>
                                </div>
                                <h3 class="font-bold text-lg mb-3">Polio (OPV)</h3>
                                <p class="text-xs leading-relaxed mb-6 font-medium">
                                    Free Oral Polio Vaccine drops provided by the government.
                                </p>
                            </div>
                            <a href="#"
                                class="text-primary text-xs font-bold flex items-center gap-1 transition">Find Camp
                                <i data-lucide="arrow-right" class="w-3 h-3"></i></a>
                        </div>
                    </div>

                    <!-- Slide 2: BCG -->
                    <div class="swiper-slide">
                        <div class="vaccine-card p-6 rounded-[2rem] shadow-sm flex flex-col justify-between h-64 md:h-72 lg:h-80">
                            <div>
                                <div
                                    class="vaccine-icon-box w-12 h-12 rounded-2xl flex items-center justify-center mb-6">
                                    <i data-lucide="cross" class="w-6 h-6"></i>
                                </div>
                                <h3 class="font-bold text-lg mb-3">BCG Vaccine</h3>
                                <p class="text-xs leading-relaxed mb-6 font-medium">
                                    Essential TB protection for newborns available at all centers.
                                </p>
                            </div>
                            <a href="#"
                                class="text-primary text-xs font-bold flex items-center gap-1 transition">Find Camp
                                <i data-lucide="arrow-right" class="w-3 h-3"></i></a>
                        </div>
                    </div>

                    <!-- Slide 3: Measles -->
                    <div class="swiper-slide">
                        <div class="vaccine-card p-6 rounded-[2rem] shadow-sm flex flex-col justify-between h-64 md:h-72 lg:h-80">
                            <div>
                                <div
                                    class="vaccine-icon-box w-12 h-12 rounded-2xl flex items-center justify-center mb-6">
                                    <i data-lucide="thermometer" class="w-6 h-6"></i>
                                </div>
                                <h3 class="font-bold text-lg mb-3">Measles</h3>
                                <p class="text-xs leading-relaxed mb-6 font-medium">
                                    Measles-Rubella (MR) vaccine is free for children 9-15 months.
                                </p>
                            </div>
                            <a href="#"
                                class="text-primary text-xs font-bold flex items-center gap-1 transition">Find Camp
                                <i data-lucide="arrow-right" class="w-3 h-3"></i></a>
                        </div>
                    </div>

                    <!-- Slide 4: Pentavalent -->
                    <div class="swiper-slide">
                        <div class="vaccine-card p-6 rounded-[2rem] shadow-sm flex flex-col justify-between h-64 md:h-72 lg:h-80">
                            <div>
                                <div
                                    class="vaccine-icon-box w-12 h-12 rounded-2xl flex items-center justify-center mb-6">
                                    <i data-lucide="activity" class="w-6 h-6"></i>
                                </div>
                                <h3 class="font-bold text-lg mb-3">Pentavalent</h3>
                                <p class="text-xs leading-relaxed mb-6 font-medium">
                                    5-in-1 protection shot available at government camps.
                                </p>
                            </div>
                            <a href="#"
                                class="text-primary text-xs font-bold flex items-center gap-1 transition">Find Camp
                                <i data-lucide="arrow-right" class="w-3 h-3"></i></a>
                        </div>
                    </div>

                    <!-- Slide 5: Typhoid -->
                    <div class="swiper-slide">
                        <div class="vaccine-card p-6 rounded-[2rem] shadow-sm flex flex-col justify-between h-64 md:h-72 lg:h-80">
                            <div>
                                <div
                                    class="vaccine-icon-box w-12 h-12 rounded-2xl flex items-center justify-center mb-6">
                                    <i data-lucide="droplet" class="w-6 h-6"></i>
                                </div>
                                <h3 class="font-bold text-lg mb-3">Typhoid (TCV)</h3>
                                <p class="text-xs leading-relaxed mb-6 font-medium">
                                    New Conjugate Vaccine available freely in select campaigns.
                                </p>
                            </div>
                            <a href="#"
                                class="text-primary text-xs font-bold flex items-center gap-1 transition">Find Camp
                                <i data-lucide="arrow-right" class="w-3 h-3"></i></a>
                        </div>
                    </div>

                </div>
                <!-- Pagination Element -->
                <div class="swiper-pagination"></div>
            </div>
        </div>

        <!-- Right Side: Features List -->
        <div class="lg:col-span-5 lg:pl-10 pt-8 lg:pt-10">
            <div class="mb-8 md:mb-10">
                <span class="text-primary font-bold text-xs md:text-sm uppercase tracking-wider">Benefits</span>
                <h2 class="text-2xl md:text-3xl lg:text-4xl font-bold text-gray-900 mt-2 leading-tight">Why Book With Us?</h2>
            </div>
            <div class="space-y-6 md:space-y-8">
                <div
                    class="flex gap-4 md:gap-6 group hover:bg-white hover:p-4 hover:rounded-2xl hover:shadow-lg transition-all duration-300 -mx-4 p-4 md:p-0">
                    <div
                        class="w-12 md:w-14 h-12 md:h-14 rounded-2xl bg-blue-50 flex items-center justify-center text-primary flex-shrink-0 group-hover:bg-primary group-hover:text-white transition shadow-sm">
                        <i data-lucide="clock" class="w-5 md:w-6 h-5 md:h-6"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900 text-base md:text-lg">No Waiting Lines</h4>
                        <p class="text-sm text-gray-500 mt-1 max-w-xs leading-relaxed">Your booked slot ensures you don't have to wait in long queues at the camp.</p>
                    </div>
                </div>
                <div
                    class="flex gap-4 md:gap-6 group hover:bg-white hover:p-4 hover:rounded-2xl hover:shadow-lg transition-all duration-300 -mx-4 p-4 md:p-0">
                    <div
                        class="w-12 md:w-14 h-12 md:h-14 rounded-2xl bg-blue-50 flex items-center justify-center text-primary flex-shrink-0 group-hover:bg-primary group-hover:text-white transition shadow-sm">
                        <i data-lucide="check-circle" class="w-5 md:w-6 h-5 md:h-6"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900 text-base md:text-lg">Verified Govt Camps</h4>
                        <p class="text-sm text-gray-500 mt-1 max-w-xs leading-relaxed">We only list official hospitals and centers authorized by the government.</p>
                    </div>
                </div>
                <div
                    class="flex gap-4 md:gap-6 group hover:bg-white hover:p-4 hover:rounded-2xl hover:shadow-lg transition-all duration-300 -mx-4 p-4 md:p-0">
                    <div
                        class="w-12 md:w-14 h-12 md:h-14 rounded-2xl bg-blue-50 flex items-center justify-center text-primary flex-shrink-0 group-hover:bg-primary group-hover:text-white transition shadow-sm">
                        <i data-lucide="file-text" class="w-5 md:w-6 h-5 md:h-6"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900 text-base md:text-lg">Digital Tracking</h4>
                        <p class="text-sm text-gray-500 mt-1 max-w-xs leading-relaxed">Keep a digital history of which government vaccines your child has received.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- LOCATIONS SECTION (Bottom) -->
    <div id="schedule" class="max-w-[1400px] mx-auto px-6 md:px-20 pb-20">
        <div class="flex justify-between items-end mb-8 md:mb-10">
            <div>
                <span class="text-primary font-bold text-xs md:text-sm uppercase tracking-wider">Active Drives</span>
                <h2 class="text-2xl md:text-3xl lg:text-4xl font-bold text-gray-900 mt-2">Active Free Vaccine Camps</h2>
            </div>
            <div class="flex gap-2 md:gap-3">
                <button
                    class="loc-prev w-9 md:w-10 h-9 md:h-10 rounded-full bg-primary flex items-center justify-center text-white shadow-lg shadow-primary/30 hover:bg-cyan-400 transition cursor-pointer"><i
                        data-lucide="chevron-left" class="w-4 md:w-5 h-4 md:h-5"></i></button>
                <button
                    class="loc-next w-9 md:w-10 h-9 md:h-10 rounded-full bg-primary flex items-center justify-center text-white shadow-lg shadow-primary/30 hover:bg-cyan-400 transition cursor-pointer"><i
                        data-lucide="chevron-right" class="w-4 md:w-5 h-4 md:h-5"></i></button>
            </div>
        </div>

        <!-- Location Swiper Container -->
        <div class="swiper locationSwiper h-[420px] md:h-[450px] lg:h-[450px]">
            <div class="swiper-wrapper">
                <?php
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                ?>
                        <!--hospital card -->
                        <div class="swiper-slide">
                            <a href="parents_book_appointment.php?id=<?php echo $row['hospital_id'] ?>" class="block bg-white p-4 rounded-[2rem] shadow-lg shadow-gray-100/50 border border-gray-100 transition hover:-translate-y-2 h-full">
                                <div class="h-48 flex items-center justify-center rounded-[1.5rem] overflow-hidden bg-slate-200 relative mb-5">
                                    <?php if (!$row['hospital_img'] == null) { ?>
                                        <img src="./asset/images/<?php echo $row['hospital_img'] ?>" alt="Hospital Image" class="w-full h-full object-cover">
                                    <?php } else { ?>
                                        <i data-lucide="hospital" class="w-16 h-16 text-primary"></i>
                                    <?php } ?>
                                    <div class="absolute bottom-4 left-4 bg-green-500 text-white text-[10px] font-bold px-2 py-1 rounded-md shadow-md">Free Camp Active</div>
                                </div>
                                <div class="px-2 pb-2">
                                    <h4 class="font-bold text-xl text-gray-800"><?php echo $row['hospital_name'] ?></h4>
                                    <p class="text-xs flex items-center gap-1 text-gray-400 mt-1 font-medium"><i data-lucide="map-pin" class="w-3 h-3 text-primary"></i><?php echo $row['hospital_address'] ?></p>
                                    <div class="mt-5 flex justify-between items-center">
                                        <button class="bg-darkblue text-white text-xs px-6 py-3 rounded-xl font-bold shadow-lg shadow-blue-900/20 hover:bg-blue-900 transition">Book Free Slot</button>
                                        <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-primary hover:bg-primary hover:text-white transition shadow-sm border border-blue-50">
                                            <i data-lucide="shield-check" class="w-5 h-5"></i>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php } ?>
                    <!-- View More -->
                    <div class="swiper-slide">
                        <a href="parents_hospitals.php" class="block bg-white p-4 rounded-[2rem] shadow-lg shadow-gray-100/50 border border-gray-100 transition hover:-translate-y-2 h-full">
                            <div
                                class="bg-gradient-to-br from-primary to-cyan-400 p-8 rounded-[2rem] shadow-xl h-full flex flex-col justify-center items-center text-center text-white cursor-pointer group hover:shadow-2xl transition">
                                <div
                                    class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center mb-6 group-hover:scale-110 transition duration-300 backdrop-blur-sm">
                                    <i data-lucide="arrow-right" class="w-10 h-10"></i>
                                </div>
                                <h3 class="text-3xl font-bold mb-2">View All Camps</h3>
                                <p class="text-white/80 font-medium text-sm">See all active free vaccination drives</p>
                            </div>
                        </a>
                    </div>
                <?php } else { ?>
                    <div>
                        <div colspan="6" class="px-6 py-12 text-center text-gray-400">
                            <i data-lucide="hospital" class="w-10 h-10 mx-auto mb-4"></i>
                            <div class="font-bold"> No hospital found</div>
                            <div class="text-xs mt-1">Try adjusting your filters or search query.</div>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <!-- Pagination for Location -->
            <div class="swiper-pagination"></div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    // 1. VACCINE SWIPER (Zoom Effect, Loop)
    var vaccineSwiper = new Swiper(".vaccineSwiper", {
        slidesPerView: 1.2,
        spaceBetween: 20,
        centeredSlides: true,
        loop: true,
        grabCursor: true,
        autoplay: {
            delay: 3000,
            disableOnInteraction: false,
        },
        breakpoints: {
            768: {
                slidesPerView: 3,
                spaceBetween: 30,
            },
        },
        pagination: {
            el: ".vaccineSwiper .swiper-pagination", // Specific selector
            clickable: true,
        },
    });

    // 2. LOCATION SWIPER (No Zoom, No Loop, 3 Slides Desktop)
    var locationSwiper = new Swiper(".locationSwiper", {
        slidesPerView: 1.2, // Mobile: Peek next card
        spaceBetween: 20,
        loop: false, // Loop disabled as requested
        grabCursor: true,
        navigation: {
            nextEl: ".loc-next",
            prevEl: ".loc-prev",
        },
        breakpoints: {
            768: {
                slidesPerView: 3, // Desktop: 3 cards
                spaceBetween: 30,
            },
        },
        pagination: {
            el: ".locationSwiper .swiper-pagination",
            clickable: true,
        },
    });
</script>
<?php include 'partials/inc_footer.php'; ?>