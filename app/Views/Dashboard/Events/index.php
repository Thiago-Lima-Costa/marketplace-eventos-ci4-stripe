<?php echo $this->extend('Layouts/dashboard'); ?>

<?php echo $this->section('title'); ?>
<?php echo $title ?? ''; ?>
<?php echo $this->endSection(); ?>

<?php echo $this->section('css'); ?>

<?php echo $this->endSection(); ?>

<?php echo $this->section('content'); ?>

<div class="container-fluid px-4">

    <h1 class="mt-4"><?php echo $title; ?></h1>

    <div class="card shadow-lg mt-3">
        <div class="card-header">
            <a href="<?php echo route_to('dashboard.events.new'); ?>" class="btn btn-success float-end ms-2"><i class="fas fa-plus"></i> Novo</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Data de início</th>
                            <th>Data de término</th>
                            <th>Criado</th>
                            <th>Atualizado</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php foreach ($events as $event): ?>

                            <tr>
                                <td>
                                    <div class="d-flex px-2 py-1">
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="mb-0 text-sm"><?php echo $event->name; ?></h6>
                                            <p class="text-xs text-secondary mb-0"> <?php echo $event->code; ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <?php echo $event->start_date; ?>
                                </td>
                                <td>
                                    <?php echo $event->end_date; ?>
                                </td>
                                <td>
                                    <?php echo $event->created_at; ?>
                                </td>
                                <td>
                                    <?php echo $event->updated_at; ?>
                                </td>
                                <td>
                                    <a href="<?php echo route_to('dashboard.events.show', $event->code); ?>" class="btn btn-sm btn-dark">Detalhes</a>
                                </td>
                            </tr>

                        <?php endforeach; ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<?php echo $this->endSection(); ?>

<?php echo $this->section('js'); ?>

<?php echo $this->endSection(); ?>