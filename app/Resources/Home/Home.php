<?php header_web('Template.Header', $data); ?>
<!-- Sections:Start -->
<div data-bs-spy="scroll" class="scrollspy-example">
    <!-- Hero: Start -->
    <section id="hero-animation">
        <div id="landingHero" class="section-py landing-hero position-relative">
            <img
                src="/assets/img/front-pages/backgrounds/hero-bg.png"
                alt="hero background"
                class="position-absolute top-0 start-50 translate-middle-x object-fit-cover w-100 h-100"
                data-speed="1" />
            <div class="container">
                <div class="row">
                    <div class="col-12 col-md-6 p-md-5">
                        <h1>SISTEMA PREDICTIVO</h1>
                        <p>Este en un sistema de identificación de tendencias para la diabetes denominado "SITED"</p>
                        </p>
                    </div>
                    <div class="col-12 col-md-6 p-md-5">
                        <div class="swiper" id="swiper-with-progress">
                            <div class="swiper-wrapper">
                                <div class="swiper-slide" style="background-image: url(/images/Hero/DIABETES-MELLITUS-2022-M.jpg)">
                                    Slide 1
                                </div>
                                <div class="swiper-slide" style="background-image: url(/images/Hero/herramintas-cuidado-diabetes-mellitus-redes.jpg)">
                                    Slide 2
                                </div>
                                <div class="swiper-slide" style="background-image: url(/images/Hero/DIABETES-MELLITUS-2022-M.jpg)">
                                    Slide 3
                                </div>
                                <div class="swiper-slide" style="background-image: url(/images/Hero/herramintas-cuidado-diabetes-mellitus-redes.jpg)">
                                    Slide 4
                                </div>
                                <div class="swiper-slide" style="background-image: url(/images/Hero/DIABETES-MELLITUS-2022-M.jpg)">
                                    Slide 5
                                </div>
                            </div>
                            <div class="swiper-pagination"></div>
                            <div class="swiper-button-next swiper-button-white custom-icon"></div>
                            <div class="swiper-button-prev swiper-button-white custom-icon"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Hero: End -->
</div>
<!-- / Sections:End -->

<section id="landingCTA" class="section-py landing-cta position-relative p-lg-0 pb-0">
    <div class="container">
        <div class="row align-items-center gy-12">
            <div class="col-lg-6 text-start text-sm-center text-lg-start">
                <h3 class="cta-title text-primary fw-bold mb-1">¿Listo para iniciar?</h3>
                <h5 class="text-body mb-8">
                    Realiza el test y descubre si tienes tendencia a la diabetes
                </h5>
                <a href="/sited/test" class="btn btn-lg btn-primary">Realizar Test</a>
            </div>
            <div class="col-lg-6 pt-lg-12 text-center text-lg-end">
                <img
                    src="../../assets/img/front-pages/landing-page/cta-dashboard.png"
                    alt="cta dashboard"
                    class="img-fluid mt-lg-4" />
            </div>
        </div>
    </div>
</section>
<?php footer_web('Template.Footer', $data); ?>