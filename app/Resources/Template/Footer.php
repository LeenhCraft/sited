<!-- Footer: Start -->
<footer class="landing-footer bg-body footer-text">
    <div class="footer-top position-relative overflow-hidden z-1">
        <img
            src="/assets/img/front-pages/backgrounds/footer-bg.png"
            alt="footer bg"
            class="footer-bg banner-bg-img z-n1" />
        <div class="container">
            <div class="row gx-0 gy-6 g-lg-10">
                <div class="col-lg-6">
                    <a href="landing-page.html" class="app-brand-link mb-6">
                        <span class="app-brand-logo demo">
                            <span class="text-primary">
                                <img src="/img/logo.png" alt="Sited" style="width: 50px;">
                            </span>
                        </span>
                        <span class="app-brand-text demo text-white fw-bold ms-2 ps-1">
                            <?php
                            echo $_ENV['APP_NAME'];
                            ?>
                        </span>
                    </a>
                    <p class="footer-text footer-logo-description mb-6">
                        Para cualquier información puedes solicitar apoyo escribiéndonos:
                    </p>
                </div>
                <div class="col-lg-2 col-sm-6">
                    <h6 class="footer-title mb-6">Contactanos</h6>
                    <ul class="list-unstyled">
                        <li class="mb-4">
                            <span class="footer-text">Av.Grau, Moyobamba, San Martin</span>
                        </li>
                        <li class="mb-4">
                            <span class="footer-text">info@example.com</span>
                        </li>
                        <li class="mb-4">
                            <span class="footer-text">+ 01 234 567 88</span>
                        </li>
                        <li class="mb-4">
                            <span class="footer-text">+ 01 234 567 89</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- Footer: End -->

<script>
    const base_url = "<?php echo base_url(); ?>";
</script>

<!-- Core JS -->
<!-- build:js assets/vendor/js/theme.js  -->

<script src="/js/jquery.min.js"></script>
<script src="/js/popper.min.js"></script>
<script src="/js/jquery.dataTables.min.js"></script>
<script src="/js/dataTables.bootstrap.min.js"></script>

<script src="/assets/vendor/libs/popper/popper.js"></script>
<script src="/assets/vendor/js/bootstrap.js"></script>
<script src="/assets/vendor/libs/@algolia/autocomplete-js.js"></script>
<script src="/node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
<script src="/assets/vendor/libs/pickr/pickr.js"></script>

<!-- endbuild -->

<!-- Vendors JS -->
<script src="/assets/vendor/libs/nouislider/nouislider.js"></script>
<script src="/assets/vendor/libs/swiper/swiper.js"></script>

<!-- Main JS -->

<script src="/assets/js/front-main.js"></script>

<!-- Page JS -->
<!-- <script src="/assets/js/front-page-landing.js"></script> -->

<script>
    const Toast = Swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        showCloseButton: true,
        timer: 5000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener("mouseenter", Swal.stopTimer);
            toast.addEventListener("mouseleave", Swal.resumeTimer);
        },
    });
</script>

<?php
if (isset($data['js']) && !empty($data['js'])) {
    for ($i = 0; $i < count($data['js']); $i++) {
        echo '<script src="' . $data['js'][$i] . '"></script>' . PHP_EOL;
    }
}
?>

</body>

</html>