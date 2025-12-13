        window.addEventListener('load', function() {
    setTimeout(function() {
        document.body.classList.add('loaded');
    }, 2000);
});

document.addEventListener('DOMContentLoaded', function () {
            // Initialize Icons
            lucide.createIcons();

            // Mobile Menu Logic
            const mobileMenu = document.getElementById('mobile-menu');

            window.openMenu = function () {
                mobileMenu.classList.remove('hidden');
                mobileMenu.classList.add('flex');
                document.body.style.overflow = 'hidden';
                lucide.createIcons();
            }

            window.closeMenu = function () {
                mobileMenu.classList.add('hidden');
                mobileMenu.classList.remove('flex');
                document.body.style.overflow = 'auto';
            }

        });

        /* ------FAQs page js---------*/
        // Accordion Logic
        function toggleAccordion(element) {
            // Close other items (Optional: Remove this loop if you want multiple open at once)
            const allItems = document.querySelectorAll('.accordion-item');
            allItems.forEach(item => {
                if (item !== element) {
                    item.classList.remove('active');
                }
            });

            // Toggle current item
            element.classList.toggle('active');
        }


          /* -----hospital inventory page styles----- */


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