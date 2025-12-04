<?php echo $this->extend('Layouts/main'); ?>

<?php echo $this->section('title'); ?>
<?php echo $title ?? ''; ?>
<?php echo $this->endSection(); ?>

<?php echo $this->section('css'); ?>

<style>.img-event {
    width: 100%;
    height: 200px;
    object-fit: contain;
    background-color: #f5f5f5;
}

</style>

<?php echo $this->endSection(); ?>

<?php echo $this->section('content'); ?>

<?php if (empty($events)): ?>

    <!-- Header-->
    <header class="py-5">
        <div class="container px-lg-5">
            <div class="p-4 p-lg-5 bg-light rounded-3 text-center">
                <div class="m-4 m-lg-5">
                    <h1 class="display-5 fw-bold">Não temos eventos disponíveis!</h1>
                    <p class="fs-4">Bootstrap utility classes are used to create this jumbotron since the old component has been removed from the framework. Why create custom CSS when you can use utilities?</p>
                    <a class="btn btn-dark btn-lg" href="<?php echo route_to('dashboard.events') ?>">Criar meu evento agora</a>
                </div>
            </div>
        </div>
    </header>

<?php else: ?>

    <!-- Page Features-->
    <div class="row gx-lg-5">

        <?php foreach($events as $event): ?>

        <div class="col-xl-4 col-lg-5 col-md-7 mx-auto">
            <div class="card bg-light border-0 h-100">
                <div class="card-body text-center p-4 p-lg-5 pt-0 pt-lg-0">
                    <?php echo $event->image(class: 'img-event card-img-top img-fluid'); ?>
                    <h2 class="fs-4 fw-bold"><?php echo $event->name; ?></h2>
                    <p class="mb-0"><?php echo ellipsize($event->description, 100); ?></p>
                    <a class="btn btn-primary mt-3" href="<?php echo route_to('events.show', $event->code) ?>">Mais detalhes</a>
                </div>
            </div>
        </div>

        <?php endforeach; ?>


    </div>

<?php endif; ?>




<?php echo $this->endSection(); ?>

<?php echo $this->section('js'); ?>

<?php echo $this->endSection(); ?>