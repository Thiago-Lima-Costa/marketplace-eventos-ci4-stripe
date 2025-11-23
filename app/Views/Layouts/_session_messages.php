<?php if (session()->has('success')) : ?>

<script>
    Toastify({
        text: "<?php echo session('success'); ?>",
        duration: 10000,
        close: true,
        gravity: "top",
        position: 'right',
        backgroundColor: '#4fbe87'
    }).showToast();
</script>

<?php endif; ?>


<?php if (session()->has('info')) : ?>

<script>
    Toastify({
        text: "<?php echo session('info'); ?>",
        duration: 10000,
        close: true,
        gravity: "top",
        position: 'left',
    }).showToast();
</script>

<?php endif; ?>



<?php if (session()->has('danger')) : ?>

<script>
    Toastify({
        text: "<?php echo session('danger'); ?>",
        duration: 10000,
        close: true,
        gravity: "bottom",
        position: 'right',
        backgroundColor: '#dc3454'
    }).showToast();
</script>

<?php endif; ?>



<?php if (session()->has('error')) : ?>

<script>
    Toastify({
        text: "<?php echo session('error'); ?>",
        duration: 10000,
        close: true,
        gravity: "bottom",
        position: 'right',
        backgroundColor: '#dc3454'
    }).showToast();
</script>

<?php endif; ?>