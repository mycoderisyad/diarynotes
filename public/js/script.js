document.addEventListener('DOMContentLoaded', function() {

    const hamburger = document.querySelector('.hamburger');
    const sidebar = document.querySelector('.sidebar');
    const overlay = document.querySelector('.overlay');
    const sidebarLinks = document.querySelectorAll('.sidebar-menu a');
    const navbar = document.querySelector('.navbar');

    if (!hamburger || !sidebar || !overlay || !navbar) {
        console.error('Required elements not found');
        return;
    }

    function adjustSidebarPosition() {
        if (navbar && sidebar) {
            const navbarHeight = navbar.offsetHeight;
            sidebar.style.top = `${navbarHeight}px`;
            sidebar.style.height = `calc(100vh - ${navbarHeight}px)`;
        }
    }

    function handleScroll() {
        if (navbar) {
            if (window.scrollY > 50) {
                navbar.style.backgroundColor = '#fbfbfa';
                navbar.style.boxShadow = '0 2px 4px rgba(0,0,0,0.1)'; // Added shadow
            } else {
                navbar.style.backgroundColor = 'transparent';
                navbar.style.boxShadow = 'none';
            }
        }
    }

    function updateDateTime() {
        const dateTimeDisplay = document.querySelector('.datetime-display');
        if (!dateTimeDisplay) return;

        function update() {
            try {
                const now = new Date();
                const jakartaTime = new Intl.DateTimeFormat('en-US', {
                    timeZone: 'Asia/Jakarta',
                    year: 'numeric',
                    month: '2-digit',
                    day: '2-digit',
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                    hour12: false
                }).format(now);

                const [date, time] = jakartaTime.split(', ');
                const [month, day, year] = date.split('/');
                const formattedDateTime = `Current Date and Time (Jakarta): ${year}-${month}-${day} ${time}`;
                dateTimeDisplay.textContent = formattedDateTime;
            } catch (error) {
                console.error('Error updating datetime:', error);
            }
        }

        update();
        setInterval(update, 1000);
    }

    function toggleSidebar() {
        sidebar.classList.toggle('active');
        hamburger.classList.toggle('active');
        overlay.classList.toggle('active');
        document.body.style.overflow = sidebar.classList.contains('active') ? 'hidden' : '';
    }

    function closeSidebar() {
        sidebar.classList.remove('active');
        hamburger.classList.remove('active');
        overlay.classList.remove('active');
        document.body.style.overflow = '';
    }

    window.addEventListener('scroll', handleScroll);
    window.addEventListener('resize', adjustSidebarPosition);
    
    hamburger.addEventListener('click', function(e) {
        e.stopPropagation();
        toggleSidebar();
    });

    overlay.addEventListener('click', closeSidebar);

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && sidebar.classList.contains('active')) {
            closeSidebar();
        }
    });

    if (sidebarLinks) {
        sidebarLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                sidebarLinks.forEach(l => l.classList.remove('active'));
                this.classList.add('active');
                
                if (window.innerWidth <= 768) {
                    closeSidebar();
                }
            });
        });
    }

    adjustSidebarPosition();
    updateDateTime();

    if (sidebar) {
        sidebar.style.overflowY = 'auto';
    }
});