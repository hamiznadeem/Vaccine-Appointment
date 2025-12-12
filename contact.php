<?php
$title = 'Contact Us';
    include 'partials/inc_header.php';
    
    // Database Connection File Include karein (Agar header mein nahi hai to)
    // include 'db_connection.php'; 
    // $conn variable assume kiya ja raha hai

    $message = "";

    if(isset($_POST['btn_submit'])) {
        // Data sanitization to prevent SQL Injection
        $sender_name = mysqli_real_escape_string($conn, $_POST['sender_name']);
        $sender_email = mysqli_real_escape_string($conn, $_POST['sender_email']);
        $subject = mysqli_real_escape_string($conn, $_POST['subject']);
        $inquiry_message = mysqli_real_escape_string($conn, $_POST['inquiry_message']);
        
        // Status default 'pending' set kiya hai
        $status = 'pending';

        // Insert Query
        $sql = "INSERT INTO inquiries (sender_name, sender_email, subject, inquiry_message, inquiry_status) 
                VALUES ('$sender_name', '$sender_email', '$subject', '$inquiry_message', '$status')";

        if(mysqli_query($conn, $sql)) {
            $message = 'We have Received your Message we will contact as soon as possible';  
        }
    }
?>

<div class="pt-40 pb-20 px-6 md:px-20 max-w-[1400px] mx-auto flex-grow w-full fade-in">
        
        <div class="text-center mb-16">
            <span class="text-primary font-bold text-sm tracking-widest uppercase mb-2 block">Get in Touch</span>
            <h1 class="text-4xl md:text-5xl font-bold text-darkblue mb-4">Contact Us</h1>
            <p class="text-gray-500 max-w-2xl mx-auto">
                Have questions about your vaccination schedule or need support? We are here to help you 24/7.
            </p>
        </div>

        <?php if ($message) { ?>
            <div
                class="flex items-center gap-2 mt-2 mb-5 p-3 py-4 rounded-xl text-sm font-bold bg-primary text-white">
                <i data-lucide="circle-check" class="w-4 h-4 text-white"> </i>
                <?php echo $message; ?>
            </div>
            <?php } ?>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
            
            <div class="lg:col-span-4 space-y-6">
                
                <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm hover:shadow-md transition group">
                    <div class="w-12 h-12 rounded-xl bg-blue-50 text-primary flex items-center justify-center mb-4 group-hover:scale-110 transition">
                        <i data-lucide="mail" class="w-6 h-6"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800">Email Us</h3>
                    <p class="text-sm text-gray-400 mb-2">For general inquiries and support.</p>
                    <a href="mailto:support@vaccining.com" class="text-primary font-bold text-sm hover:underline">support@vaccining.com</a>
                </div>

                <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm hover:shadow-md transition group">
                    <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-500 flex items-center justify-center mb-4 group-hover:scale-110 transition">
                        <i data-lucide="phone" class="w-6 h-6"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800">Call Us</h3>
                    <p class="text-sm text-gray-400 mb-2">Mon-Fri from 8am to 5pm.</p>
                    <a href="tel:+1234567890" class="text-purple-600 font-bold text-sm hover:underline">+1 (234) 567 890</a>
                </div>

                <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm hover:shadow-md transition group">
                    <div class="w-12 h-12 rounded-xl bg-orange-50 text-orange-500 flex items-center justify-center mb-4 group-hover:scale-110 transition">
                        <i data-lucide="map-pin" class="w-6 h-6"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800">Visit Us</h3>
                    <p class="text-sm text-gray-400 mb-2">Come say hello at our office HQ.</p>
                    <span class="text-slate-700 font-bold text-sm block">123 Health Street, New York, USA</span>
                </div>

            </div>

            <div class="lg:col-span-8">
                <div class="bg-white rounded-[2.5rem] p-8 md:p-10 shadow-lg border border-gray-100 h-full relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-primary/5 rounded-full blur-3xl -mr-20 -mt-20"></div>

                    <h2 class="text-2xl font-bold text-slate-800 mb-6 relative z-10">Send us a Message</h2>
                    
                    <form action="" method="POST" class="space-y-6 relative z-10">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label for="name" class="text-sm font-bold text-slate-700 ml-1">Full Name</label>
                                <input type="text" id="name" name="sender_name" placeholder="John Doe" required class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition text-sm">
                            </div>
                            <div class="space-y-2">
                                <label for="email" class="text-sm font-bold text-slate-700 ml-1">Email Address</label>
                                <input type="email" id="email" name="sender_email" placeholder="john@example.com" required class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition text-sm">
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label for="subject" class="text-sm font-bold text-slate-700 ml-1">Subject</label>
                            <select id="subject" name="subject" class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition text-sm text-gray-500">
                                <option value="General Inquiry">General Inquiry</option>
                                <option value="Booking Issue">Booking Issue</option>
                                <option value="Feedback">Feedback</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label for="message" class="text-sm font-bold text-slate-700 ml-1">Message</label>
                            <textarea id="message" name="inquiry_message" rows="5" placeholder="How can we help you?" required class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition text-sm resize-none"></textarea>
                        </div>

                        <button type="submit" name="btn_submit" class="w-full py-4 bg-darkblue text-white rounded-2xl font-bold shadow-lg shadow-blue-900/20 hover:bg-blue-900 transition flex items-center justify-center gap-2">
                            Send Message <i data-lucide="send" class="w-4 h-4"></i>
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
    <?php include 'partials/inc_footer.php' ?>