// 1. تأثير الآلة الكاتبة (Typewriter Effect)
const typingText = document.querySelector('.typing-text');
const roles = ["Full-Stack Web Developer", "QA Tester", "Problem Solver"];
let roleIndex = 0;
let charIndex = 0;
let isDeleting = false;

function typeEffect() {
    const currentRole = roles[roleIndex];
    
    if (isDeleting) {
        typingText.textContent = currentRole.substring(0, charIndex - 1);
        charIndex--;
    } else {
        typingText.textContent = currentRole.substring(0, charIndex + 1);
        charIndex++;
    }

    // سرعة الكتابة والمسح
    let typeSpeed = isDeleting ? 50 : 100;

    if (!isDeleting && charIndex === currentRole.length) {
        // التوقف قليلاً عند اكتمال الكلمة
        typeSpeed = 2000;
        isDeleting = true;
    } else if (isDeleting && charIndex === 0) {
        isDeleting = false;
        // الانتقال للكلمة التالية
        roleIndex = (roleIndex + 1) % roles.length;
        typeSpeed = 500;
    }

    setTimeout(typeEffect, typeSpeed);
}

// 2. تأثير شريط التنقل عند التمرير (Navbar Scroll Effect)
const navbar = document.querySelector('nav');

window.addEventListener('scroll', () => {
    if (window.scrollY > 50) {
        navbar.classList.add('scrolled');
    } else {
        navbar.classList.remove('scrolled');
    }
});

// تشغيل تأثير الكتابة عند تحميل الصفحة
document.addEventListener("DOMContentLoaded", () => {
    if(typingText) setTimeout(typeEffect, 1000);
});

// التحقق من نجاح إرسال الرسالة من خلال الرابط
const urlParams = new URLSearchParams(window.location.search);
if (urlParams.get('status') === 'success') {
    alert("Thank you! Your message has been sent successfully.");
    // تنظيف الرابط بعد إظهار الرسالة
    window.history.replaceState(null, null, window.location.pathname);
}