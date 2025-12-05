<?php

use App\Cells\CartSeatsCell;

 echo $this->extend('Layouts/main'); ?>

<?php echo $this->section('title'); ?>
<?php echo $title; ?>
<?php echo $this->endSection(); ?>

<?php echo $this->section('css'); ?>


<?php echo $this->endSection(); ?>

<?php echo $this->section('content'); ?>


<div class="container-fluid mb-4">
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-2"><?php echo $title; ?></h5>
                    <a href="<?php echo route_to('home'); ?>" class="btn btn-outline-secondary me-2"><i class="fas fa-angle-double-left"></i> Listar eventos</a>
                </div>
                <div class="card-body">

                    <?php if(empty($seats)): ?>

                        <div class="alert alert-info text-center">
                            <p>Você ainda não reservou nenhum assento</p>
                        </div>

                    <?php else: ?>

                        <?php echo view_cell(
                            library: CartSeatsCell::class, 
                            params: ['seats' => $seats, 'showDeleteButton' => true]
                        ); ?>

                        <div class="col-md-12 mt-3">
                            <a href="<?php echo route_to('checkout'); ?>" class="btn btn-primary">Ir para tela de pagamento</a>
                        </div>
                    
                    <?php endif; ?>
                   
                </div>
            </div>
        </div>
    </div>

</div>


<?php echo $this->endSection(); ?>


<?php echo $this->section('js'); ?>


<?php echo $this->endSection(); ?>