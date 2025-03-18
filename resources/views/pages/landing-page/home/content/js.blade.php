<!-- jqueryUI -->
<script src="https://code.jquery.com/ui/1.14.1/jquery-ui.js"></script>
<script type="text/javascript">
    $(document).ready(function() {

        // Setting Height Di Section 3 bagian Kiri
        const h = document.getElementById('right-our-facilities').scrollHeight * 1.2 + 'px';
        document.getElementById('left-our-facilities').style.cssText = `height:${h}`


        // Select picker on change
        $('.selectpicker').select2();
        $('.selectpicker').on('change', function() {
            $('input[name=zone]').val($(this).val())
        });
    });
</script>

<script>
    if (document.getElementById('splide-gallery')) {
        var splide = new Splide('#splide-gallery', {
            type: 'slide',
            perPage: 3,
            perMove: 1,
            gap: '20px',
            padding: '4rem',
            pagination: false
        });
        splide.mount();
    }



    if (document.getElementById('splide-our-popular-properties')) {
        var splide = new Splide('#splide-our-popular-properties ', {
            type: 'slide',
            perPage: 3,
            perMove: 1,
            pagination: true
        });
        splide.mount();
    }
</script>


<!-- SLIDESHOW VR -->
<script>
    $num = $('.my-card').length;
    $even = $num / 2;
    $odd = ($num + 1) / 2;

    if ($num % 2 == 0) {
        $('.my-card:nth-child(' + $even + ')').addClass('active');
        $('.my-card:nth-child(' + $even + ')').prev().addClass('prev');
        $('.my-card:nth-child(' + $even + ')').next().addClass('next');
        $('.my-card:nth-child(' + $even + ')').next().next().addClass('next');
    } else {
        $('.my-card:nth-child(' + $odd + ')').addClass('active');
        $('.my-card:nth-child(' + $odd + ')').prev().addClass('prev');
        $('.my-card:nth-child(' + $odd + ')').next().addClass('next');
        $('.my-card:nth-child(' + $odd + ')').next().next().addClass('next');
    }

    const cards = document.querySelectorAll('.my-card');
    let currentIndex = 0;


    const updateCards = () => {
        cards.forEach((card, index) => {
            card.classList.remove('active', 'next', 'prev');
            if (index === currentIndex) {
                card.classList.add('active');
            } else if (index === (currentIndex + 1) % cards.length) {
                card.classList.add('next');
            } else if (index === (currentIndex + 2) % cards.length) {
                card.classList.add('next');
            }
        });
    };

    // Initialize the carousel
    updateCards();


    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.my-card');
        const carousel = document.querySelector('.card-carousel'); // Carousel container
        const nextButton = document.getElementById('btn-next-slide');
        const prevButton = document.getElementById('btn-prev-slide');

        let activeIndex = 0;

        const updateCardss = () => {
            cards.forEach((card, index) => {
                card.classList.remove('active', 'prev', 'next');

                console.log(activeIndex, (cards.length - 1));
                if (index === activeIndex) {
                    card.classList.add('active');
                }
                // else if (activeIndex <= (cards.length - 1)) {
                if (activeIndex <= (cards.length - 1) && index === (activeIndex + 1) % cards
                    .length) {
                    card.classList.add('next');
                } else if (activeIndex <= (cards.length - 3) && index === (activeIndex + 2) % cards
                    .length) {
                    card.classList.add('next');
                }
                // }
            });
        };

        const animateCarousel = (direction) => {
            const activeCard = document.querySelector('.active'); // Active card
            const slideWidth = activeCard.offsetWidth; // Width of active card

            if (direction === 'next') {
                $(carousel).stop(false, true).animate({
                    left: `-=${slideWidth}` // Move carousel left for "next"
                });
            } else if (direction === 'prev') {
                $(carousel).stop(false, true).animate({
                    left: `+=${slideWidth}` // Move carousel right for "prev"
                });
            }
        };

        nextButton.addEventListener('click', () => {
            if (activeIndex < (cards.length - 1)) {
                activeIndex = (activeIndex + 1) % cards.length;
                animateCarousel('next'); // Animate carousel for next
                updateCardss();
            }
            console.log(activeIndex, 'OKE1');
        });

        prevButton.addEventListener('click', () => {
            if (activeIndex !== 0) {
                activeIndex = (activeIndex - 1 + cards.length) % cards.length;
                animateCarousel('prev'); // Animate carousel for prev
                updateCardss();
                console.log(activeIndex, 'OKE2');
            } else {
                console.log(activeIndex, 'OKE');
            }
        });
    });
</script>
<script>
    $(document).ready(function() {
        // alert('ready')
        const formSearch = $('form[data-filter=search]');
        const doesntHaveBuilding = ['industial-land', 'container-yard'];
        // Button tab conten
        $('button.tab').on('click', function() {
            // alert($(this).attr('data-tab'));
            const valueTab = $(this).attr('data-tab');
            $('input[name=category]').val(valueTab);

            if (doesntHaveBuilding.includes(valueTab)) {
                $('.building-area-slider').addClass('hidden');
                $('input[name=building-area-range]').val('');
            } else {
                $('.building-area-slider').removeClass('hidden');
            }
        })

        // Initial slide
        $('.range-slider').each(function() {
            let slider = $(this);
            let valueDisplayId = "#".concat(slider.attr('id'), '-value');

            slider.slider({
                range: true,
                min: 0,
                max: 3200,
                values: [0, 3200],
                slide: function(event, ui) {

                    //set to input elem
                    const inputName = slider.attr('id');
                    const inputElem = $(`input[name=${inputName}]`);
                    inputElem.val(`${ui.values[0]}-${ui.values[1]}`);

                    // update ui
                    $(valueDisplayId).text(String(ui.values[0]).concat(' m² - ', ui.values[
                        1], ' m²'))
                }
            })
        })
    })
</script>
