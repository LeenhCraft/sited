<!DOCTYPE html>
<html
    lang="es"
    class="light-style customizer-hide"
    dir="ltr"
    data-theme="theme-default"
    data-assets-path="/assets/"
    data-template="vertical-menu-template-free">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $data['titulo_web'] ?? $_ENV["APP_NAME"]; ?></title>
    <!-- Fonts -->
    <link rel="stylesheet" href="/css/appfonts.css">
    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="/css/boxicons.css" />
    <script src="https://kit.fontawesome.com/b4dffa1b79.js" crossorigin="anonymous"></script>
    <!-- Core CSS -->
    <link rel="stylesheet" href="/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="/css/custom.css" />
    <!-- Vendors CSS -->
    <link rel="stylesheet" href="/css/perfect-scrollbar.css" />
    <!-- Helpers -->
    <script src="/js/helpers.js"></script>
    <!-- Other -->
    <script src="/js/config.js"></script>
    <link rel="stylesheet" href="/css/demo.css">
    <link rel="stylesheet" href="/css/datatables.bootstrap5.css">
    <?php
    if (isset($data['css']) && !empty($data['css'])) {
        for ($i = 0; $i < count($data['css']); $i++) {
            echo '<link rel="stylesheet" type="text/css" href="' . $data['css'][$i] . '">' . PHP_EOL;
        }
    }
    ?>
</head>

<body>
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
                <div class="app-brand demo">
                    <a href="/admin" class="app-brand-link">
                        <span class=" demo menu-text fw-bolder ms-2 app-header__logo">Laboratorio</span>
                    </a>
                </div>

                <div class="menu-inner-shadow"></div>

                <ul class="menu-inner py-1">
                    <li class="menu-item ">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="menu-icon tf-icons bx bx-lock-open-alt"></i>
                            <div data-i18n="Layouts" class="text-capitalize">Maestras</div>
                        </a>

                        <ul class="menu-sub">
                            <li class="menu-item ">
                                <a href="/admin/menus" class="menu-link">
                                    <div data-i18n="Menús" class="d-flex justify-content-between align-items-center">
                                        <i class="menu-icon tf-icons bx bx-menu"></i>
                                        <span>
                                            Menús </span>
                                    </div>
                                </a>
                            </li>
                            <li class="menu-item ">
                                <a href="/admin/permisos" class="menu-link">
                                    <div data-i18n="Permisos" class="d-flex justify-content-between align-items-center">
                                        <i class="menu-icon tf-icons bx bx-key"></i>
                                        <span>
                                            Permisos </span>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </aside>
            <div class="layout-page">
                <!-- Navbar -->
                <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">
                    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
                        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                            <i class="bx bx-menu bx-sm"></i>
                        </a>
                    </div>
                    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
                        <div class="w-100">
                            <div class="navbar-nav align-items-center w-100">
                                <div class="nav-item d-flex align-items-center w-100">
                                    <a class="w-100 border-0 app-header__logo text-dark text-start text-break user-select-none">
                                        <span>Bienvenido</span> <?php echo $nombre['nombre'] ?? "UNDEFINED"; ?>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <ul class="navbar-nav flex-row align-items-center ms-auto">
                            <!-- User -->
                            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                                    <div class="avatar avatar-online">
                                        <img src="/img/placeholder-150x150.png" alt class="w-px-40 h-auto rounded-circle" />
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="avatar avatar-online">
                                                        <img src="/img/placeholder-150x150.png" alt class="w-px-40 h-auto rounded-circle" />
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <span class="fw-semibold d-block"><?php echo $nombre['nombre'] ?? "UNDEFINED"; ?></span>
                                                    <small class="text-muted"><?php echo $nombre['rol'] ?? "UNDEFINED"; ?></small>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <div class="dropdown-divider"></div>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <i class="bx bx-user me-2"></i>
                                            <span class="align-middle">Mi cuenta</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <i class="bx bx-cog me-2"></i>
                                            <span class="align-middle">Configuración</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="/admin/logout">
                                            <i class="bx bx-power-off me-2"></i>
                                            <span class="align-middle">Cerrar y Salir</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <!--/ User -->
                        </ul>
                    </div>
                </nav>
                <!-- / Navbar -->
                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->
                    <div class="container-xxl flex-grow-1 container-p-y">