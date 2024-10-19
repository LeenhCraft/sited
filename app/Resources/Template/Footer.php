<script>
    const base_url = "<?php echo base_url(); ?>";
</script>
<?php
if (isset($data['js']) && !empty($data['js'])) {
    for ($i = 0; $i < count($data['js']); $i++) {
        echo '<script src="' . $data['js'][$i] . '"></script>';
    }
}
?>
</body>

</html>