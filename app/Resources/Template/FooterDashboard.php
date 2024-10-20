</div>
<!-- / Content -->
<div class="content-backdrop fade"></div>
</div>
<!-- Content wrapper -->
</div>
<!-- / Layout page -->
</div>
<!-- Overlay -->
<div class="layout-overlay layout-menu-toggle"></div>
</div>
<!-- / Layout wrapper -->
<script>
    const base_url = "<?php echo base_url(); ?>";
</script>
<!-- Core JS -->
<script src="/js/jquery.min.js"></script>
<script src="/js/popper.min.js"></script>
<script src="/js/bootstrap.min.js"></script>

<script src="/js/menu.js"></script>
<!-- Main JS -->
<script src="/js/main.js"></script>
<?php
if (isset($data['js']) && !empty($data['js'])) {
    for ($i = 0; $i < count($data['js']); $i++) {
        echo '<script src="' . $data['js'][$i] . '"></script>';
    }
}
?>
</body>

</html>