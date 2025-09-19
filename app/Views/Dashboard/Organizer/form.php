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
        <div class="card-body">
            <?php if (! empty($user->stripe_account_id) && (bool)$user->stripe_account_is_completed): ?>
                <div class="form-floating">
                    <div class="alert alert-success my-3">
                        <h5 class="alert-heading">
                            Sua conta na Stripe está criada e pronta para receber os repasses.
                            <br>
                            <a href="<?php echo route_to('dashboard.events'); ?>" class="mt-3 btn btn-sm btn-primary">Criar meus eventos</a>
                            <a target="_blank" href="<?php echo route_to('dashboard.organizer.panel'); ?>" class="mt-3 btn btn-sm btn-dark">Acessar minha conta na Stripe</a>
                        </h5>
                    </div>
                </div>
            <?php else: ?>

                <?php echo form_open(
                    action: route_to('dashboard.organizer.create.account'),
                    attributes: ['id' => 'form']
                ); ?>

                <?php if (empty($user->stripe_account_id)): ?>
                    <div class="row">
                        <div class="col-md-12 mt-3">
                            <button type="submit" id="btnSubmit" class="btn btn-dark">Criar minha conta de organizador na Stripe</button>
                        </div>
                    </div>
                <?php endif; ?>

                <?php echo form_close(); ?>

            <?php endif; ?>

            <?php if (! empty($user->stripe_account_id) && ! (bool)$user->stripe_account_is_completed): ?>

                <div class="alert alert-warning my-3">
                    Sua conta está criada, mas ainda tem pendências que precisam da sua atenção.
                    Caso já tenha resolvdo, aguarde uns instantes e clique no botão abaixo para verificar a situação.
                </div>

                <?php echo form_open(
                    action: route_to('dashboard.organizer.check.account'),
                    attributes: ['id' => 'form'],
                    hidden: ['_method' => 'PUT']
                ); ?>

                <div class="row">
                    <div class="col-md-12 mt-3">
                        <button type="submit" id="btnCheck" class="btn btn-danger">Verificar minha conta</button>
                    </div>
                </div>

                <?php echo form_close(); ?>

            <?php endif; ?>
        </div>
    </div>

</div>

<?php echo $this->endSection(); ?>

<?php echo $this->section('js'); ?>

<?php echo $this->endSection(); ?>