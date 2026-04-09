// common.js - Simple common JavaScript for all pages

// Mobile Menu Toggle
function initMobileMenu() {
    const mobileToggle = document.querySelector('.mobile-toggle');
    const navMenu = document.querySelector('.nav-menu');
    
    if (mobileToggle && navMenu) {
        mobileToggle.addEventListener('click', function() {
            navMenu.classList.toggle('active');
        });
        
        // Close menu when clicking links
        document.querySelectorAll('.nav-menu a').forEach(link => {
            link.addEventListener('click', function() {
                navMenu.classList.remove('active');
            });
        });
    }
}

// Handle anchor links (only for same-page anchors)
function initAnchorLinks() {
    document.querySelectorAll('a[href^="#"]').forEach(link => {
        link.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            
            // Only handle if it's a same-page anchor AND not an external page link
            if (href.startsWith('#') && !href.includes('.html')) {
                e.preventDefault();
                
                const targetId = href;
                if (targetId === '#') return;
                
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    // Smooth scroll to target
                    window.scrollTo({
                        top: targetElement.offsetTop - 80,
                        behavior: 'smooth'
                    });
                }
            }
            // If it's a link to another page (like index.html#booking), let it work normally
        });
    });
}

// Room card hover effects
function initRoomEffects() {
    const roomCards = document.querySelectorAll('.room-card');
    
    roomCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            const img = this.querySelector('.room-image img');
            if (img) {
                img.style.transform = 'scale(1.1)';
            }
        });
        
        card.addEventListener('mouseleave', function() {
            const img = this.querySelector('.room-image img');
            if (img) {
                img.style.transform = 'scale(1)';
            }
        });
    });
}

// Initialize everything when page loads
document.addEventListener('DOMContentLoaded', function() {
    initMobileMenu();
    initAnchorLinks();
    initRoomEffects();
    
    // Simple header scroll effect
    let lastScroll = 0;
    window.addEventListener('scroll', function() {
        const currentScroll = window.pageYOffset;
        const header = document.querySelector('header');
        
        if (header) {
            if (currentScroll > 100) {
                if (currentScroll > lastScroll) {
                    header.style.transform = 'translateY(-100%)';
                } else {
                    header.style.transform = 'translateY(0)';
                }
            } else {
                header.style.transform = 'translateY(0)';
            }
            
            lastScroll = currentScroll;
        }
    });
});