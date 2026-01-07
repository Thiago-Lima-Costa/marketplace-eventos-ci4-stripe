<?php echo $this->extend('Layouts/dashboard'); ?>

<?php echo $this->section('title'); ?>
<?php echo $title; ?>
<?php echo $this->endSection(); ?>

<?php echo $this->section('css'); ?>
<style>

</style>
<?php echo $this->endSection(); ?>

<?php echo $this->section('content'); ?>


<div class="container-fluid">

    <div class="card mt-5 shadow-lg">
        <div class="card-header">
            <h5 class="card-title mb-2"><?php echo $title; ?></h5>
            <a href="<?php echo route_to('dashboard.orders'); ?>" class="btn btn-outline-secondary"><i class="fas fa-angle-double-left"></i> Listar meus pedidos</a>
        </div>
        <div class="card-body">

            <div class="row">
                <div class="col-md-12">
                    <p><strong>Código do pedido:</strong> <?php echo $order->code ?></p>
                    <p><strong>Valor:</strong> <?php echo $order->total() ?></p>
                    <p><strong>Status:</strong> <?php echo $order->status() ?></p>
                </div>

                <div class="col-md-12">
                    <?php if(! $order->isPaid()): ?>
                        <div class="alert alert-warning">
                            <p><strong>Atenção:</strong> Você poderá imprimir os ingressos quando o pedido estiver pago</p>
                        </div>
                    <?php else: ?>
                         <a target="_blank" href="<?php echo route_to('dashboard.orders.print', $order->code); ?>" class="btn btn-primary">Imprimir ingressos</a>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>

</div>

<?php echo $this->endSection(); ?>


<?php echo $this->section('js'); ?>

<?php echo $this->endSection(); ?>