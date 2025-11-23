<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title><?php echo $this->renderSection('title'); ?></title>
        <!-- Favicon-->
        <link rel="icon" type="image/x-icon" href="<?php echo base_url('assets_main') ?>favicon.ico" />
        <!-- Bootstrap icons-->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastify-js/1.6.1/toastify.min.css" integrity="sha512-UiKdzM5DL+I+2YFxK+7TDedVyVm7HMp/bN85NeWMJNYortoll+Nd6PU9ZDrZiaOsdarOyk9egQm6LOJZi36L2g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="<?php echo base_url('assets_main/') ?>css/styles.css" rel="stylesheet" />

        <?php echo $this->renderSection('css'); ?>

        <style>
            .min-height-view {
                min-height: calc(100vh - (64px + 137px));
            }
        </style>
    </head>
    <body>
        <!-- Responsive navbar-->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container px-lg-5">
                <a class="navbar-brand" href="<?php echo route_to('home') ?>">Ingressos</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item"><a class="nav-link" href="<?php echo route_to('dashboard.events') ?>">Criar meus eventos</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?php echo route_to('dashboard') ?>">Dashboard</a></li>

                        <?php if(auth()->loggedIn()): ?>
                            <li class="nav-item"><a class="nav-link" href="<?php echo route_to('logout') ?>">Sair</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>
        
        <!-- Page Content-->
        <section class="pt-4">
            <div class="container px-lg-5">

                <div class="min-height-view">
                    <?php echo $this->renderSection('content'); ?>
                </div>
         
            </div>
        </section>
        <!-- Footer-->
        <footer class="py-5 bg-dark">
            <div class="container"><p class="m-0 text-center text-white">Copyright &copy; Your Website <?php echo date('Y') ?></p></div>
        </footer>
        <!-- Bootstrap core JS-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Core theme JS-->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastify-js/1.6.1/toastify.min.js" integrity="sha512-79j1YQOJuI8mLseq9icSQKT6bLlLtWknKwj1OpJZMdPt2pFBry3vQTt+NZuJw7NSd1pHhZlu0s12Ngqfa371EA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="<?php echo base_url('assets_main/') ?>js/scripts.js"></script>

        <?php echo $this->renderSection('js'); ?>
    </body>
</html>
