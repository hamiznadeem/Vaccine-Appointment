    </div>

    <script>
        // Initialize Icons
        lucide.createIcons();

        // Mobile Sidebar Logic
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');

        function toggleSidebar() {
            if (sidebar.classList.contains('-translate-x-full')) {
                // Open Sidebar
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
                document.body.style.overflow = 'hidden'; // Prevent scroll
            } else {
                // Close Sidebar
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
                document.body.style.overflow = 'auto'; // Enable scroll
            }
        }

            // Toggle Dropdown
        function toggleDropdown(btn) {
            const allDropdowns = document.querySelectorAll('.dropdown-menu');
            allDropdowns.forEach(d => {
                if (d !== btn.nextElementSibling) d.classList.remove('show');
            });
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
</body>
</html>