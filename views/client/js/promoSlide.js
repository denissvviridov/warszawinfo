export function promoSlide() {


    const slides = document.querySelectorAll('.promo_slide');

    if (slides.length > 0) {
        let currentSlide = 0;

        function showSlide(index) {
            slides.forEach(slide => slide.style.opacity = '0');
            slides[index].style.opacity = '1';
        }

        function nextSlide() {
            currentSlide = (currentSlide + 1) % slides.length;
            showSlide(currentSlide);
        }

        setInterval(nextSlide, 5000); // Перелистывание каждые 5 секунд
        showSlide(currentSlide);
    }


}