<div class="row">

    <?php foreach($seats as $seat): ?>

        <div class="col-4 mb-2">
            <div class="card shadow-lg">
                <div class="card-body">
                    <p><?php echo $seat->event; ?></p>
                    <p><?php echo $seat->event_date; ?></p>
                    <p>Setor: <?php echo $seat->sector; ?></p>
                    <p>Fila: <?php echo $seat->row; ?></p>
                    <p>Assento: <?php echo $seat->number(); ?></p>
                    <p>Tipo de entrada: <?php echo $seat->type(); ?></p>
                    <p>Preço: <?php echo show_price($seat->price); ?></p>
                    <p>Data da reserva: <?php echo $seat->created_at; ?></p>
                    <p>Reservada até: <?php echo $seat->expire_at; ?></p>

                    <?php if($showDeleteButton): ?>

                        <?php echo form_open(
                            action: route_to('cart.destroy', $seat->id),
                            attributes: [
                                'class' => 'd-inline-block',
                                'onsubmit' => "return confirm('Tem certeza?)"
                            ],
                            hidden: ['_method' => 'DELETE']
                        ) ?>

                            <button type="submit" class="btn btn-sm btn-danger">
                                <i class="bi bi-trash2-fill"></i>
                            </button>

                        <?php echo form_close(); ?>

                    <?php endif; ?>
                </div>
            </div>
        </div>

    <?php endforeach; ?>

    <div class="col-md-12 mt-3">
        <span class="fw-bold fs-4">
            <?php echo show_price(array_sum(array_column($seats, 'price'))); ?>
        </span>
    </div>

</div>
