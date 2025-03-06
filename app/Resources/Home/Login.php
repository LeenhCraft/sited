<!doctype html>

<html
    lang="en"
    class="layout-wide customizer-hide"
    dir="ltr"
    data-skin="default"
    data-assets-path="/assets/"
    data-template="vertical-menu-template"
    data-bs-theme="light">

<head>
    <meta charset="utf-8" />
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Iniciar Sesión</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/favicon.ico" />

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

    <!-- Vendors CSS -->

    <link rel="stylesheet" href="/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <!-- endbuild -->

    <!-- Vendor -->
    <link rel="stylesheet" href="/assets/vendor/libs/@form-validation/form-validation.css" />

    <!-- Page CSS -->
    <!-- Page -->
    <link rel="stylesheet" href="/assets/vendor/css/pages/page-auth.css" />

    <!-- Helpers -->
    <script src="/assets/vendor/js/helpers.js"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->

    <!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
    <script src="/assets/vendor/js/template-customizer.js"></script>

    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->

    <script src="/assets/js/config.js"></script>
</head>

<body>
    <!-- Content -->

    <div class="authentication-wrapper authentication-cover">
        <!-- Logo -->
        <a href="/" class="app-brand auth-cover-brand gap-2">
            <span class="app-brand-logo demo">
                <span class="text-primary">
                    <img src="/img/logo.png" alt="<?php echo $_ENV["APP_NAME"]; ?>" style="width: 40px;">
                </span>
            </span>
            <span class="app-brand-text demo text-heading fw-bold">
                <?php
                echo $_ENV['APP_NAME'];
                ?>
            </span>
        </a>
        <!-- /Logo -->
        <div class="authentication-inner row m-0">
            <!-- /Left Text -->
            <div class="d-none d-lg-flex col-lg-7 col-xl-8 align-items-center p-5">
                <div class="w-100 d-flex justify-content-center">
                    <img
                        src="/assets/img/illustrations/boy-with-rocket-light.png"
                        class="img-fluid"
                        alt="Login image"
                        width="700"
                        data-app-dark-img="illustrations/boy-with-rocket-dark.png"
                        data-app-light-img="illustrations/boy-with-rocket-light.png" />
                </div>
            </div>
            <!-- /Left Text -->

            <!-- Login -->
            <div class="d-flex col-12 col-lg-5 col-xl-4 align-items-center authentication-bg p-sm-12 p-6">
                <div class="w-px-400 mx-auto mt-sm-12 mt-8">
                    <h4 class="mb-1">Bienvenido a Sited! 👋</h4>
                    <p class="mb-6">Inicia sesión en tu cuenta y comienza la aventura</p>

                    <form id="formAuthentication" class="mb-6" action="index.html" method="GET">
                        <div class="mb-6 form-control-validation">
                            <label for="email" class="form-label">Correo o usuario</label>
                            <input
                                type="text"
                                class="form-control"
                                id="email"
                                name="email-username"
                                placeholder="Ingrese su correo o usuario"
                                autofocus />
                        </div>
                        <div class="form-password-toggle form-control-validation">
                            <label class="form-label" for="password">Contraseña</label>
                            <div class="input-group input-group-merge">
                                <input
                                    type="password"
                                    id="password"
                                    class="form-control"
                                    name="password"
                                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                    aria-describedby="password" />
                                <span class="input-group-text cursor-pointer"><i class="icon-base bx bx-hide"></i></span>
                            </div>
                        </div>
                        <div class="my-7">
                            <div class="d-flex justify-content-between">
                                <div class="form-check mb-0">
                                    <input class="form-check-input" type="checkbox" id="remember-me" />
                                    <label class="form-check-label" for="remember-me">Recuerdame</label>
                                </div>
                                <a href="auth-forgot-password-cover.html">
                                    <p class="mb-0">¿Olvidaste tu contraseña?</p>
                                </a>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary d-grid w-100">Iniciar Sesión</button>
                    </form>

                    <p class="text-center">
                        <span>¿Eres nuevo en nuestra plataforma?</span>
                        <a href="/registrarse">
                            <span>Crea una cuenta</span>
                        </a>
                    </p>
                </div>
            </div>
            <!-- /Login -->
        </div>
    </div>

    <!-- / Content -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/theme.js  -->

    <script src="/assets/vendor/libs/jquery/jquery.js"></script>

    <script src="/assets/vendor/libs/popper/popper.js"></script>
    <script src="/assets/vendor/js/bootstrap.js"></script>
    <script src="/assets/vendor/libs/@algolia/autocomplete-js.js"></script>

    <script src="/assets/vendor/libs/pickr/pickr.js"></script>

    <script src="/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

    <script src="/assets/vendor/libs/hammer/hammer.js"></script>

    <script src="/assets/vendor/libs/i18n/i18n.js"></script>

    <script src="/assets/vendor/js/menu.js"></script>

    <script src="/node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
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

    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="/assets/vendor/libs/@form-validation/popular.js"></script>
    <script src="/assets/vendor/libs/@form-validation/bootstrap5.js"></script>
    <script src="/assets/vendor/libs/@form-validation/auto-focus.js"></script>

    <!-- Main JS -->

    <script src="/assets/js/main.js"></script>

    <!-- Page JS -->
    <?php
    if (isset($data['js']) && !empty($data['js'])) {
        for ($i = 0; $i < count($data['js']); $i++) {
            echo '<script src="' . $data['js'][$i] . '"></script>' . PHP_EOL;
        }
    }
    ?>
</body>

</html>