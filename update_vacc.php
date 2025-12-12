<?php
include 'partials/inc_header.php';
?>
  <div class="pt-32 pb-20 px-6 md:px-20 max-w-[1400px] mx-auto flex-grow w-full fade-in">
        <!-- Breadcrumb -->
            <div class="flex items-center gap-2 text-sm text-gray-400 mb-6">
                <a href="index.php" class="hover:text-primary">home</a>
                <i data-lucide="chevron-right" class="w-4 h-4"></i>
                <a href="hospital_inven.php" class="hover:text-primary">Inventory</a>
                <i data-lucide="chevron-right" class="w-4 h-4"></i>
                <span class="text-gray-800 font-medium">Update Stock</span>
            </div>

            <div class="bg-white rounded-[2.5rem] p-8 md:p-10 shadow-lg border border-gray-100 max-w-4xl mx-auto">
                
                <div class="flex items-center gap-4 mb-8 pb-8 border-b border-gray-100">
                    <div class="w-16 h-16 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center shadow-sm">
                        <i data-lucide="shield" class="w-8 h-8"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-darkblue">Update Vaccine Stock</h1>
                        <p class="text-gray-500 text-sm">Enter details to update inventory for <strong class="text-slate-700">Polio (OPV)</strong></p>
                    </div>
                </div>

                <form class="space-y-8">
                    
                    <!-- Basic Info -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                            <i data-lucide="info" class="w-5 h-5 text-primary"></i> Basic Information
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2 tracking-wider">Vaccine Name</label>
                                <input type="text" value="Polio (OPV)" class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-bold text-slate-600 focus:outline-none cursor-not-allowed" disabled>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2 tracking-wider">Batch Number</label>
                                <input type="text" placeholder="e.g. BATCH-2024-A1" class="w-full px-5 py-4 bg-white border border-gray-200 rounded-2xl text-sm font-mono text-slate-800 focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition">
                            </div>
                        </div>
                    </div>

                    <!-- Stock Details -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                            <i data-lucide="package" class="w-5 h-5 text-primary"></i> Stock Details
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2 tracking-wider">Quantity (Doses)</label>
                                <input type="number" placeholder="0" class="w-full px-5 py-4 bg-white border border-gray-200 rounded-2xl text-sm font-bold text-slate-800 focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2 tracking-wider">Manufacturing Date</label>
                                <input type="date" class="w-full px-5 py-4 bg-white border border-gray-200 rounded-2xl text-sm font-medium text-slate-600 focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2 tracking-wider">Expiry Date</label>
                                <input type="date" class="w-full px-5 py-4 bg-white border border-gray-200 rounded-2xl text-sm font-medium text-slate-600 focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition">
                            </div>
                        </div>
                    </div>

                    <!-- Supplier Info -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                            <i data-lucide="truck" class="w-5 h-5 text-primary"></i> Supplier Info
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2 tracking-wider">Supplier Name</label>
                                <input type="text" placeholder="e.g. National Health Services" class="w-full px-5 py-4 bg-white border border-gray-200 rounded-2xl text-sm font-medium text-slate-800 focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2 tracking-wider">Stock Status</label>
                                <select class="w-full px-5 py-4 bg-white border border-gray-200 rounded-2xl text-sm font-medium text-slate-600 focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition appearance-none">
                                    <option value="high">In Stock (High)</option>
                                    <option value="low">Low Stock</option>
                                    <option value="out">Out of Stock</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2 tracking-wider">Additional Notes</label>
                        <textarea rows="4" placeholder="Any specific notes about this batch..." class="w-full px-5 py-4 bg-white border border-gray-200 rounded-2xl text-sm font-medium text-slate-600 focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition resize-none"></textarea>
                    </div>

                    <!-- Actions -->
                    <div class="pt-6 flex flex-col sm:flex-row gap-4 border-t border-gray-100">
                        <a href="hospital_inven.php" class="w-full sm:w-auto px-8 py-4 border border-gray-200 text-gray-500 rounded-2xl text-sm font-bold hover:bg-gray-50 transition text-center">Cancel</a>
                        <button type="submit" class="w-full sm:w-auto px-8 py-4 bg-primary text-white rounded-2xl text-sm font-bold shadow-lg shadow-blue-900/20 hover:bg-cyan-500 transition flex items-center justify-center gap-2 ml-auto">
                            <i data-lucide="save" class="w-4 h-4"></i> Save & Update Stock
                        </button>
                    </div>

                </form>

            </div>
    </div>
<?php
include 'partials/inc_footer.php';
?>