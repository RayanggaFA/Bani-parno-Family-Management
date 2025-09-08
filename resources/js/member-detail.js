// resources/js/member-detail.js - CREATE NEW FILE

document.addEventListener('DOMContentLoaded', function() {
    // Animate cards on load
    const cards = document.querySelectorAll('.card-animate');
    cards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.1}s`;
    });

    // Profile photo hover effect
    const profilePhoto = document.querySelector('.profile-card img');
    if (profilePhoto) {
        profilePhoto.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.05) rotate(2deg)';
            this.style.transition = 'all 0.3s ease';
        });
        
        profilePhoto.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1) rotate(0deg)';
        });
    }

    // Contact items click to copy
    const phoneElement = document.querySelector('[data-phone]');
    const emailElement = document.querySelector('[data-email]');
    
    if (phoneElement) {
        phoneElement.addEventListener('click', function() {
            copyToClipboard(this.textContent);
            showToast('Nomor telepon disalin!');
        });
    }
    
    if (emailElement) {
        emailElement.addEventListener('click', function() {
            copyToClipboard(this.textContent);
            showToast('Email disalin!');
        });
    }

    // Smooth scroll for anchor links
    const anchorLinks = document.querySelectorAll('a[href^="#"]');
    anchorLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
});

// Utility functions
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        console.log('Text copied to clipboard');
    });
}

function showToast(message) {
    // Create toast element
    const toast = document.createElement('div');
    toast.className = 'fixed bottom-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50 transform translate-y-full opacity-0 transition-all duration-300';
    toast.textContent = message;
    document.body.appendChild(toast);
    
    // Show toast
    setTimeout(() => {
        toast.style.transform = 'translateY(0)';
        toast.style.opacity = '1';
    }, 100);
    
    // Hide toast
    setTimeout(() => {
        toast.style.transform = 'translateY(full)';
        toast.style.opacity = '0';
        setTimeout(() => {
            document.body.removeChild(toast);
        }, 300);
    }, 3000);
}