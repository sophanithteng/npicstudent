function initCarouselLogic() {
    const track = document.querySelector('.carousel-track');
    const slides = Array.from(track.children);
    const nextButton = document.querySelector('.next');
    const prevButton = document.querySelector('.prev');
    
    if (!track || !nextButton || !prevButton) return;

    let currentIndex = 0;

    const updateSlide = (index) => {
        track.style.transform = `translateX(-${index * 100}%)`;
    };

    let autoSlide = setInterval(() => {
        currentIndex = (currentIndex + 1) % slides.length;
        updateSlide(currentIndex);
    }, 5000);

    const resetAuto = () => {
        clearInterval(autoSlide);
        autoSlide = setInterval(() => {
            currentIndex = (currentIndex + 1) % slides.length;
            updateSlide(currentIndex);
        }, 5000);
    };

    nextButton.addEventListener('click', () => {
        resetAuto();
        currentIndex = (currentIndex + 1) % slides.length;
        updateSlide(currentIndex);
    });

    prevButton.addEventListener('click', () => {
        resetAuto();
        currentIndex = (currentIndex - 1 + slides.length) % slides.length;
        updateSlide(currentIndex);
    });
}