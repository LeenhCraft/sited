<!DOCTYPE html>
<html
    lang="es"
    class="layout-navbar-fixed layout-wide"
    dir="ltr"
    data-skin="default"
    data-assets-path="/assets/"
    data-template="front-pages"
    data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title><?php echo $data['titulo_web'] ?? $_ENV["APP_NAME"]; ?></title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/favicon.ico" />

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="/css/boxicons.css" />
    <script src="https://kit.fontawesome.com/b4dffa1b79.js" crossorigin="anonymous"></script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />

    <link rel="stylesheet" href="/assets/vendor/fonts/iconify-icons.css" />

    <!-- Core CSS -->
    <!-- build:css assets/vendor/css/theme.css  -->

    <link rel="stylesheet" href="/assets/vendor/libs/pickr/pickr-themes.css" />

    <link rel="stylesheet" href="/assets/vendor/css/core.css" />
    <link rel="stylesheet" href="/assets/css/demo.css" />

    <link rel="stylesheet" href="/assets/vendor/css/pages/front-page.css" />

    <!-- Vendors CSS -->

    <!-- endbuild -->

    <link rel="stylesheet" href="/assets/vendor/libs/nouislider/nouislider.css" />
    <link rel="stylesheet" href="/assets/vendor/libs/swiper/swiper.css" />

    <!-- Page CSS -->

    <link rel="stylesheet" href="/assets/vendor/css/pages/front-page-landing.css" />

    <!-- Helpers -->
    <script src="/assets/vendor/js/helpers.js"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->

    <!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
    <script src="/assets/vendor/js/template-customizer.js"></script>

    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->

    <script src="/assets/js/front-config.js"></script>

    <?php
    if (isset($data['css']) && !empty($data['css'])) {
        for ($i = 0; $i < count($data['css']); $i++) {
            echo '<link rel="stylesheet" type="text/css" href="' . $data['css'][$i] . '">' . PHP_EOL;
        }
    }
    ?>
</head>

<body>
    <!-- Navbar: Start -->
    <nav class="layout-navbar shadow-none py-0">
        <div class="container">
            <div class="navbar navbar-expand-lg landing-navbar px-3 px-md-8">
                <!-- Menu logo wrapper: Start -->
                <div class="navbar-brand app-brand demo d-flex py-0 me-4 me-xl-8">
                    <!-- Mobile menu toggle: Start-->
                    <button
                        class="navbar-toggler border-0 px-0 me-4"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent"
                        aria-controls="navbarSupportedContent"
                        aria-expanded="false"
                        aria-label="Toggle navigation">
                        <i class="icon-base bx bx-menu icon-lg align-middle text-heading fw-medium"></i>
                    </button>
                    <!-- Mobile menu toggle: End-->
                    <a href="/" class="app-brand-link">
                        <span class="app-brand-logo demo">
                            <span class="text-primary">
                                <img src="/img/logo.png" alt="<?php echo $_ENV["APP_NAME"]; ?>" style="width: 40px;">
                            </span>
                        </span>
                        <span class="app-brand-text demo menu-text fw-bold ms-2 ps-1 text-capitalize">
                            <?php
                            echo $_ENV["APP_NAME"];
                            ?>
                        </span>
                    </a>
                </div>
                <!-- Menu logo wrapper: End -->
                <!-- Menu wrapper: Start -->
                <div class="collapse navbar-collapse landing-nav-menu" id="navbarSupportedContent">
                    <button
                        class="navbar-toggler border-0 text-heading position-absolute end-0 top-0 scaleX-n1-rtl p-2"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent"
                        aria-controls="navbarSupportedContent"
                        aria-expanded="false"
                        aria-label="Toggle navigation">
                        <i class="icon-base bx bx-x icon-lg"></i>
                    </button>
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link fw-medium" aria-current="page" href="/">Inicio</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fw-medium" href="#">Nosotros</a>
                        </li>
                        <li class="nav-item mega-dropdown">
                            <a
                                href="javascript:void(0);"
                                class="nav-link dropdown-toggle navbar-ex-14-mega-dropdown mega-dropdown fw-medium"
                                aria-expanded="false"
                                data-bs-toggle="mega-dropdown"
                                data-trigger="hover">
                                <span data-i18n="Pages">Paginas</span>
                            </a>
                            <div class="dropdown-menu p-4 p-xl-8">
                                <div class="row gy-4">
                                    <div class="col-12 col-lg">
                                        <div class="h6 d-flex align-items-center mb-3 mb-lg-4">
                                            <div class="avatar flex-shrink-0 me-3">
                                                <span class="avatar-initial rounded bg-label-primary"><i class="icon-base bx bx-grid-alt"></i></span>
                                            </div>
                                            <span class="ps-1">Other</span>
                                        </div>
                                        <ul class="nav flex-column">
                                            <li class="nav-item">
                                                <a class="nav-link mega-dropdown-link" href="pricing-page.html">
                                                    <i class="icon-base bx bx-radio-circle me-1"></i>
                                                    <span data-i18n="Pricing">Pricing</span>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link mega-dropdown-link" href="payment-page.html">
                                                    <i class="icon-base bx bx-radio-circle me-1"></i>
                                                    <span data-i18n="Payment">Payment</span>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link mega-dropdown-link" href="checkout-page.html">
                                                    <i class="icon-base bx bx-radio-circle me-1"></i>
                                                    <span data-i18n="Checkout">Checkout</span>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link mega-dropdown-link" href="help-center-landing.html">
                                                    <i class="icon-base bx bx-radio-circle me-1"></i>
                                                    <span data-i18n="Help Center">Help Center</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-12 col-lg">
                                        <div class="h6 d-flex align-items-center mb-3 mb-lg-4">
                                            <div class="avatar flex-shrink-0 me-3">
                                                <span class="avatar-initial rounded bg-label-primary"><i class="icon-base bx bx-lock-open icon-lg"></i></span>
                                            </div>
                                            <span class="ps-1">Auth Demo</span>
                                        </div>
                                        <ul class="nav flex-column">
                                            <li class="nav-item">
                                                <a
                                                    class="nav-link mega-dropdown-link"
                                                    href="../vertical-menu-template/auth-login-basic.html"
                                                    target="_blank">
                                                    <i class="icon-base bx bx-radio-circle me-1"></i>
                                                    Login (Basic)
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a
                                                    class="nav-link mega-dropdown-link"
                                                    href="../vertical-menu-template/auth-login-cover.html"
                                                    target="_blank">
                                                    <i class="icon-base bx bx-radio-circle me-1"></i>
                                                    Login (Cover)
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a
                                                    class="nav-link mega-dropdown-link"
                                                    href="../vertical-menu-template/auth-register-basic.html"
                                                    target="_blank">
                                                    <i class="icon-base bx bx-radio-circle me-1"></i>
                                                    Register (Basic)
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a
                                                    class="nav-link mega-dropdown-link"
                                                    href="../vertical-menu-template/auth-register-cover.html"
                                                    target="_blank">
                                                    <i class="icon-base bx bx-radio-circle me-1"></i>
                                                    Register (Cover)
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a
                                                    class="nav-link mega-dropdown-link"
                                                    href="../vertical-menu-template/auth-register-multisteps.html"
                                                    target="_blank">
                                                    <i class="icon-base bx bx-radio-circle me-1"></i>
                                                    Register (Multi-steps)
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a
                                                    class="nav-link mega-dropdown-link"
                                                    href="../vertical-menu-template/auth-forgot-password-basic.html"
                                                    target="_blank">
                                                    <i class="icon-base bx bx-radio-circle me-1"></i>
                                                    Forgot Password (Basic)
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a
                                                    class="nav-link mega-dropdown-link"
                                                    href="../vertical-menu-template/auth-forgot-password-cover.html"
                                                    target="_blank">
                                                    <i class="icon-base bx bx-radio-circle me-1"></i>
                                                    Forgot Password (Cover)
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a
                                                    class="nav-link mega-dropdown-link"
                                                    href="../vertical-menu-template/auth-reset-password-basic.html"
                                                    target="_blank">
                                                    <i class="icon-base bx bx-radio-circle me-1"></i>
                                                    Reset Password (Basic)
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a
                                                    class="nav-link mega-dropdown-link"
                                                    href="../vertical-menu-template/auth-reset-password-cover.html"
                                                    target="_blank">
                                                    <i class="icon-base bx bx-radio-circle me-1"></i>
                                                    Reset Password (Cover)
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-12 col-lg">
                                        <div class="h6 d-flex align-items-center mb-3 mb-lg-4">
                                            <div class="avatar flex-shrink-0 me-3">
                                                <span class="avatar-initial rounded bg-label-primary"><i class="icon-base bx bx-image-alt icon-lg"></i></span>
                                            </div>
                                            <span class="ps-1">Other</span>
                                        </div>
                                        <ul class="nav flex-column">
                                            <li class="nav-item">
                                                <a
                                                    class="nav-link mega-dropdown-link"
                                                    href="../vertical-menu-template/pages-misc-error.html"
                                                    target="_blank">
                                                    <i class="icon-base bx bx-radio-circle me-1"></i>
                                                    Error
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a
                                                    class="nav-link mega-dropdown-link"
                                                    href="../vertical-menu-template/pages-misc-under-maintenance.html"
                                                    target="_blank">
                                                    <i class="icon-base bx bx-radio-circle me-1"></i>
                                                    Under Maintenance
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a
                                                    class="nav-link mega-dropdown-link"
                                                    href="../vertical-menu-template/pages-misc-comingsoon.html"
                                                    target="_blank">
                                                    <i class="icon-base bx bx-radio-circle me-1"></i>
                                                    Coming Soon
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a
                                                    class="nav-link mega-dropdown-link"
                                                    href="../vertical-menu-template/pages-misc-not-authorized.html"
                                                    target="_blank">
                                                    <i class="icon-base bx bx-radio-circle me-1"></i>
                                                    Not Authorized
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a
                                                    class="nav-link mega-dropdown-link"
                                                    href="../vertical-menu-template/auth-verify-email-basic.html"
                                                    target="_blank">
                                                    <i class="icon-base bx bx-radio-circle me-1"></i>
                                                    Verify Email (Basic)
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a
                                                    class="nav-link mega-dropdown-link"
                                                    href="../vertical-menu-template/auth-verify-email-cover.html"
                                                    target="_blank">
                                                    <i class="icon-base bx bx-radio-circle me-1"></i>
                                                    Verify Email (Cover)
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a
                                                    class="nav-link mega-dropdown-link"
                                                    href="../vertical-menu-template/auth-two-steps-basic.html"
                                                    target="_blank">
                                                    <i class="icon-base bx bx-radio-circle me-1"></i>
                                                    Two Steps (Basic)
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a
                                                    class="nav-link mega-dropdown-link"
                                                    href="../vertical-menu-template/auth-two-steps-cover.html"
                                                    target="_blank">
                                                    <i class="icon-base bx bx-radio-circle me-1"></i>
                                                    Two Steps (Cover)
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-lg-4 d-none d-lg-block">
                                        <div class="bg-body nav-img-col p-2">
                                            <img
                                                src="/assets/img/front-pages/misc/nav-item-col-img.png"
                                                alt="nav item col image"
                                                class="w-100" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fw-medium" href="/admin" target="_blank">Admin</a>
                        </li>
                    </ul>
                </div>
                <div class="landing-menu-overlay d-lg-none"></div>
                <!-- Menu wrapper: End -->
                <!-- Toolbar: Start -->
                <ul class="navbar-nav flex-row align-items-center ms-auto">
                    <!-- Style Switcher -->
                    <li class="nav-item dropdown-style-switcher dropdown me-2 me-xl-0">
                        <a
                            class="nav-link dropdown-toggle hide-arrow"
                            id="nav-theme"
                            href="javascript:void(0);"
                            data-bs-toggle="dropdown">
                            <i class="icon-base bx bx-sun icon-lg theme-icon-active"></i>
                            <span class="d-none ms-2" id="nav-theme-text">Cambiar Tema</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="nav-theme-text">
                            <li>
                                <button
                                    type="button"
                                    class="dropdown-item align-items-center active"
                                    data-bs-theme-value="light"
                                    aria-pressed="false">
                                    <span><i class="icon-base bx bx-sun icon-md me-3" data-icon="sun"></i>Claro</span>
                                </button>
                            </li>
                            <li>
                                <button
                                    type="button"
                                    class="dropdown-item align-items-center"
                                    data-bs-theme-value="dark"
                                    aria-pressed="true">
                                    <span><i class="icon-base bx bx-moon icon-md me-3" data-icon="moon"></i>Oscuro</span>
                                </button>
                            </li>
                            <li>
                                <button
                                    type="button"
                                    class="dropdown-item align-items-center"
                                    data-bs-theme-value="system"
                                    aria-pressed="false">
                                    <span><i class="icon-base bx bx-desktop icon-md me-3" data-icon="desktop"></i>Igual que el sistema</span>
                                </button>
                            </li>
                        </ul>
                    </li>
                    <!-- / Style Switcher-->

                    <!-- navbar button: Start -->
                    <li>
                        <a href="/iniciar-sesion" class="btn btn-primary">
                            <span class="tf-icons icon-base bx bx-log-in-circle scaleX-n1-rtl me-md-1"></span>
                            <span class="d-none d-md-block">Iniciar Sesión</span>
                        </a>
                    </li>
                    <!-- navbar button: End -->
                </ul>
                <!-- Toolbar: End -->
            </div>
        </div>
    </nav>
    <!-- Navbar: End -->