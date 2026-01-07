<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Impressão de Ingressos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .ticket {
            width: 350px;
            border: 2px dashed #000;
            padding: 20px;
            margin: 10px auto;
            background: #fff;
            text-align: center;
            font-family: 'Arial', sans-serif;
            page-break-inside: avoid;
            /* Evita que um ingresso seja cortado no meio */
        }

        .print-area {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .print-btn {
            display: block;
            margin: 20px auto;
        }

        .event-date {
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="print-area">

            <?php foreach ($order->seats as $seat): ?>

                <div class="ticket">
                    <div class="event-name"><?php echo $seat->booking_data->event; ?></div>
                    <div class="event-date">
                        <p><strong>Apresentação</strong></p>
                        <?php echo $seat->booking_data->event_date; ?>
                    </div>
                    <div class="ticket-details">
                        <p><strong>Setor:</strong> <?php echo $seat->booking_data->sector; ?></p>
                        <p><strong>Fila:</strong> <?php echo $seat->booking_data->row; ?></p>
                        <p><strong>Assento:</strong> <?php echo $seat->booking_data->number; ?></p>
                        <p><strong>Tipo de ingresso:</strong> <?php echo $seat->booking_data->type; ?></p>
                    </div>

                    <div style="font-size: 12px; margin-top: 10px; color: #888">
                        Apresente este ingresso na entrada do evento.
                    </div>
                </div>

            <?php endforeach; ?>
        </div>
    </div>
</body>

</html>