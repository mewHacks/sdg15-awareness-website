document.addEventListener("DOMContentLoaded", function() {
    const ctaButton = document.querySelector(".cta");
    ctaButton.addEventListener("click", function() {
        window.scrollTo({
            top: document.querySelector("#resources").offsetTop,
            behavior: 'smooth'
        });
    });
});
