<?php if($_GET["sivu"] == "status") { ?>
<script src="<?php echo URL; ?>js/jquery-3.2.1.min.js"></script>
<script src="<?php echo URL; ?>js/datatables.min.js"></script>
<script>
$(document).ready(function() {
    $('#status_table').DataTable({ "order": [[ 4, "desc" ]] });
});
</script>
<?php } else { ?>
<script src="<?php echo URL; ?>js/app.min.js"></script>
<?php } ?>

</body>
</html>
