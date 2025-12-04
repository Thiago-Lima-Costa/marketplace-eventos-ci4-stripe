<?php echo $this->extend('Layouts/main'); ?>

<?php echo $this->section('title'); ?>
<?php echo $title; ?>
<?php echo $this->endSection(); ?>

<?php echo $this->section('css'); ?>

<style>
    /** Para estilizar os assentos escolhidos pelo user logado */
    .seat-session-reserved {
        color: #000;
        background-color: #82d616;
        border-color: #82d616;
    }

    .event-image-detail {
        width: 100%;
        max-width: 500px;
        /* Define um limite máximo para evitar que fique muito grande */
        display: block;
        /* Remove possíveis margens extras */
        margin: 0 auto;
        /* Centraliza a imagem horizontalmente */
    }
</style>

<?php echo $this->endSection(); ?>

<?php echo $this->section('content'); ?>


<div class="container-fluid">
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-2"><?php echo $title; ?></h5>
                    <a href="<?php echo route_to('home'); ?>" class="btn btn-outline-secondary me-2"><i class="fas fa-angle-double-left"></i> Listar eventos</a>
                    <a href="<?php echo route_to('cart'); ?>" class="btn btn-success"><i class="fas fa-shopping-cart"></i> Carrinho de compras</a>
                </div>
                <div class="card-body">

                    <ul class="nav nav-tabs mb-3" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link me-2 active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">Informações gerais</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Escolher assentos</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab" tabindex="0">

                            <div class="row">
                                <div class="col-md-6">
                                    <h5><?php echo $event->name; ?></h5>
                                    <p>Data início: <?php echo $event->start_date; ?></p>
                                    <p>Data término: <?php echo $event->end_date; ?></p>
                                    <p>Endereço: <?php echo $event->location; ?></p>
                                    <p><?php echo $event->description; ?></p>
                                </div>

                                <div class="col-md-6 text-center">
                                    <?php echo $event->image(); ?>
                                </div>

                            </div>

                        </div>

                        <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab" tabindex="0">
                            <div id="structureContainer">
                                <!-- Renderizaremos os assentos aqui com javascript -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Modal para exibir as confirmações do assento selecionado ou removido -->
<div class="modal fade" id="seatModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <select id="ticketTypeSelect" class="form-control"></select>
                </div>
                <p id="modalMessage"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="confirmButton"></button>
            </div>
        </div>
    </div>
</div>

<?php echo $this->endSection(); ?>


<?php echo $this->section('js'); ?>

<script>
    const monitorSeatSelection = () => {
        // Algumas constantes
        const URL_LOGIN = '<?php echo route_to('login'); ?>';
        const API_URL_ACTION = '<?php echo route_to('api.seats.action'); ?>';

        document.querySelectorAll(".btn-seat").forEach(button => {
            button.addEventListener("click", function() {
                const seatCode = this.getAttribute("data-seat");

                if (!seatCode) {
                    Toastify({
                        text: "Não foi possível obter o código do assento.",
                        duration: 3000,
                        close: true,
                        gravity: "bottom",
                        position: 'right',
                        backgroundColor: '#dc3454'
                    }).showToast();
                    return;
                }

                // Verifica se o está com a classe "seat-session-reserved"
                // o que indica que o assento está reservado
                const isReserved = this.classList.contains("seat-session-reserved");

                // Obtém referências ao modal e seus elementos
                const seatModal = new bootstrap.Modal(document.getElementById("seatModal"));
                const modalTitle = document.getElementById("modalTitle");
                const modalMessage = document.getElementById("modalMessage");
                const confirmButton = document.getElementById("confirmButton");
                const ticketTypeSelect = document.getElementById("ticketTypeSelect");

                if (!seatModal || !modalTitle || !modalMessage || !confirmButton || !ticketTypeSelect) {
                    console.error("Erro: Elementos do modal não encontrados.");
                    return;
                }

                // Define o conteúdo do modal
                modalTitle.textContent = isReserved ? "Remover assento?" : "Escolha o tipo de entrada";
                modalMessage.textContent = isReserved ?
                    "Você deseja remover este assento da sua seleção?" :
                    ''; // string vazia, pois o label do dropdown de opções é auto explicativo
                confirmButton.textContent = isReserved ? "Remover" : "Escolher";

                // Mostra ou esconde o select de tipos de ingresso
                isReserved ? ticketTypeSelect.classList.add("d-none") : ticketTypeSelect.classList.remove("d-none");

                // Limpa e popula o select de tipos de ingresso
                ticketTypeSelect.innerHTML = "";

                const fixedTicketTypes = {
                    "full": "Quero entrada Inteira",
                    "half": "Quero Meia entrada"
                };

                Object.entries(fixedTicketTypes).forEach(([key, value]) => {
                    const option = document.createElement("option");
                    option.value = key; // Usa a chave como valor (ex: "full", "half")
                    option.textContent = value; // Usa o valor como texto (ex: "Inteira", "Meia")
                    ticketTypeSelect.appendChild(option);
                });

                // Remove event listeners anteriores para evitar múltiplas requisições
                confirmButton.replaceWith(confirmButton.cloneNode(true));
                const newConfirmButton = document.getElementById("confirmButton");

                // Evento de clique no botão de confirmação do modal
                newConfirmButton.addEventListener("click", function() {

                    // Obtem o tipo de ingresso escolhido no dropdown do modal
                    const selectedTicketType = ticketTypeSelect.value;

                    fetch(API_URL_ACTION, {
                            method: 'POST',
                            headers: {
                                "Content-Type": "application/json"
                            },
                            body: JSON.stringify({
                                seat_code: seatCode,
                                seat_type: selectedTicketType // Envia o tipo de ingresso escolhido
                            })
                        })
                        .then(response => {
                            if (response.status >= 400 && response.status <= 404) {
                                throw new Error(`Erro ${response.status}: ${response.statusText}`);
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (!data.success) {
                                throw new Error(data.message || "Erro na operação");
                            }

                            if (!data.is_logged_in) {
                                Toastify({
                                    text: 'Você precisa estar logado para continuar. Redirecionando para a página de login...',
                                    close: true,
                                    gravity: "top",
                                    position: 'left',
                                }).showToast();

                                setTimeout(() => {
                                    window.location.href = URL_LOGIN;
                                }, 3000);
                                return;
                            }

                            if (isReserved) {

                                // remove a classe "seat-session-reserved" do botão
                                button.classList.remove("seat-session-reserved");

                                // adiciona a classe "btn-dark" ao botão
                                button.classList.add("btn-dark", "text-white");

                                // define os atributos do botão
                                button.setAttribute('title', 'Disponível');
                                button.setAttribute('data-bs-original-title', 'Disponível');
                            } else {

                                // adiciona as classes "seat-session-reserved" e "btn-dark" ao botão
                                button.classList.add("seat-session-reserved", "btn-dark");

                                // define os atributos do botão
                                button.setAttribute('title', 'Você reservou este assento');
                                button.setAttribute('data-bs-original-title', 'Você reservou este assento');
                            }

                            Toastify({
                                text: "Sucesso!",
                                close: true,
                                gravity: "top",
                                position: 'right',
                                backgroundColor: '#4fbe87'
                            }).showToast();

                            seatModal.hide();
                        })
                        .catch(error => {
                            console.error("Erro ao processar o assento:", error);
                            Toastify({
                                text: error.message || "Ocorreu um erro ao processar o assento.",
                                close: true,
                                gravity: "bottom",
                                position: 'right',
                                backgroundColor: '#dc3454'
                            }).showToast();
                        }).finally(() => {
                            // exibe o dropdown novamente
                            ticketTypeSelect.classList.remove("d-none");
                        });
                });

                // Exibe o modal
                seatModal.show();
            });
        });
    }

    const getEventLayout = async () => {
        try {

            const apiUrl = "<?php echo route_to('api.events.layout', $event->code); ?>";

            // Fazendo a requisição para a API
            const response = await fetch(apiUrl);

            // Se a resposta não for bem-sucedida, lança um erro
            if (!response.ok) {
                throw new Error(`Erro: ${response.status} - ${response.statusText}`);
            }

            // Convertendo a resposta para JSON
            const data = await response.json();

            document.getElementById("structureContainer").innerHTML = data.structure;

            // inicializa o monitoramento de seleção de assentos
            monitorSeatSelection();

        } catch (error) {
            // Captura qualquer erro ocorrido durante o fetch
            console.error("Erro ao buscar dados:", error.message);

            Toastify({
                text: "Ocorreu um erro ao buscar o layout do evento.",
                duration: 10000,
                close: true,
                gravity: "bottom",
                position: 'right',
                backgroundColor: '#dc3454'
            }).showToast();
        }
    }



    document.addEventListener("DOMContentLoaded", function() {

        // recuperando o layout do evento
        getEventLayout();
    });
</script>


<?php echo $this->endSection(); ?>