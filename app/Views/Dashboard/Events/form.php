<?php echo $this->extend('Layouts/dashboard'); ?>

<?php echo $this->section('title'); ?>
<?php echo $title; ?>
<?php echo $this->endSection(); ?>

<?php echo $this->section('css'); ?>

<style>
    .step {
        display: none !important;
    }

    .step.active {
        display: block !important;
    }

    .sector-summary {
        margin-bottom: 15px;
    }

    .sector-table {
        margin-top: 20px;
    }

    .sector-table td,
    .sector-table th {
        padding: 8px 12px;
        text-align: center;
    }

    .sector-summary {
        margin-bottom: 20px;
    }
</style>

<?php echo $this->endSection(); ?>

<?php echo $this->section('content'); ?>


<div class="container-fluid">

    <div class="card mt-5 shadow-lg">
        <div class="card-header">
            <h5 class="card-title mb-2"><?php echo $title; ?></h5>
            <a href="<?php echo route_to('dashboard.events'); ?>" class="btn btn-outline-secondary"><i class="fas fa-angle-double-left"></i> Listar eventos</a>
        </div>
        <div class="card-body">
            <?php echo form_open_multipart(action: $route, attributes: ['id' => 'event-form'], hidden: $hidden ?? []); ?>

            <!-- Etapa 1: Informações Gerais -->
            <div class="step active" id="step-1">
                <h4>Informações Gerais</h4>

                <div class="row">

                    <div class="mb-3">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="name" name="name" placeholder="Nome do evento" required>
                            <label for="name" class="form-label">Nome do Evento</label>
                            <div class="invalid-feedback">O nome do evento é obrigatório.</div>
                        </div>
                    </div>

                    <div class="mb-3 col-md-6">
                        <div class="form-floating">
                            <input type="datetime-local" class="form-control" id="start_date" name="start_date" required>
                            <label for="start_date" class="form-label">Data e Hora de início do Evento</label>
                            <div class="invalid-feedback">A data e hora do evento são obrigatórias.</div>
                        </div>
                    </div>

                    <div class="mb-3 col-md-6">
                        <div class="form-floating">
                            <input type="datetime-local" class="form-control" id="end_date" name="end_date" required>
                            <label for="end_date" class="form-label">Data e Hora do término do Evento</label>
                            <div class="invalid-feedback">A data e hora do evento são obrigatórias.</div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-floating">
                            <input type="text" class="form-control" placeholder="Endereço do evento" id="location" name="location" required>
                            <label for="location" class="form-label">Local do Evento</label>
                            <div class="invalid-feedback">O local do evento é obrigatório.</div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-floating">
                            <input type="file" class="form-control" required id="imagem" accept="image/*" name="image">
                            <label for="image" class="form-label">Imagem do Evento</label>
                            <div class="invalid-feedback">A imagem do evento é obrigatório.</div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-floating">
                            <textarea class="form-control" placeholder="Descrição do evento" id="description" name="description" rows="5" required></textarea>
                            <label for="description" class="form-label">Descrição do Evento</label>
                            <div class="invalid-feedback">A descrição do evento é obrigatória.</div>
                        </div>
                    </div>

                    <div class="text-center mt-3">
                        <button type="button" class="btn btn-dark" id="next-1">Próxima Etapa</button>
                    </div>

                </div>
            </div>

            <!-- Etapa 2: Configuração dos Setores -->
            <div class="step" id="step-2">
                <h4>Configuração de Setores</h4>
                <button type="button" class="btn badge btn-primary" id="add-sector-btn">Adicionar Setor</button>
                <div id="sectors-container">
                    <!-- Setores serão adicionados aqui -->
                </div>

                <div id="errorsList" class="mt-4">
                    <!-- Lista de erros será exibida aqui -->
                </div>

                <!-- Nesse mesmo DOM temos formulário de comentários e precisamos atualizar o token -->
                <input type="hidden" id="csrf_input" autocomplete="off" name="<?php echo csrf_token(); ?>" value="<?php echo csrf_hash(); ?>">



                <div class="text-center mt-3">
                    <button type="button" class="btn btn-secondary" id="prev-2">Anterior</button>
                    <button type="submit" class="btn btn-success" id="btnSubmit">Publicar Evento</button>
                </div>
            </div>

            <?php echo form_close(); ?>
        </div>
    </div>

</div>

<?php echo $this->endSection(); ?>


<?php echo $this->section('js'); ?>

<script>
    // Contador para setores
    let sectorIndex = 1;

    // Função global para calcular o total de cadeiras em um setor
    function updateTotalSeats(rowsInput, seatsInput, totalSeatsDisplay) {
        // Obtém o valor de número de filas e cadeiras por fila; se vazio, usa 0
        const rows = parseInt(rowsInput.value) || 0;
        const seatsPerRow = parseInt(seatsInput.value) || 0;

        // Atualiza o conteúdo do elemento de exibição com o total calculado
        totalSeatsDisplay.textContent = rows * seatsPerRow;
    }

    // Método que adiciona um setor ao DOM
    function addSector() {
        let sectorHTML = `
            <div class="sector-container mt-3" id="sector-${sectorIndex}">
                <hr>
                <h5>Setor ${sectorIndex}</h5>

                <div class="row">
                    <div class="mb-3 col-md-4">
                        <label for="sector_name_${sectorIndex}" class="form-label">Nome do Setor</label>
                        <input type="text" class="form-control" placeholder="Nome do setor" id="sector_name_${sectorIndex}" name="sector_names[]" required>
                        <div class="invalid-feedback">O nome do setor é obrigatório.</div>
                    </div>

                    <div class="mb-3 col-md-4">
                        <label for="rows_count_${sectorIndex}" class="form-label">Número de filas</label>
                        <input type="number" class="form-control" placeholder="Nº de filas" id="rows_count_${sectorIndex}" name="rows_count[]" min="1" required>
                        <div class="invalid-feedback">O número de filas é obrigatório.</div>
                    </div>

                    <div class="mb-3 col-md-4">
                        <label for="seats_count_${sectorIndex}" class="form-label">Número de Cadeiras por fila</label>
                        <input type="number" class="form-control" placeholder="Nº de assentos por fila" id="seats_count_${sectorIndex}" name="seats_count[]" min="1" required>
                        <div class="invalid-feedback">O número de cadeiras por fila é obrigatório.</div>
                    </div>

                    <div class="mb-3 col-md-6">
                        <label for="ticket_price" class="form-label">Preço do Ingresso Integral</label>
                        <input type="text" class="form-control price_formatted" placeholder="Preço do Ingresso Integral" id="ticket_price_${sectorIndex}" name="sector_ticket_price[]" required>
                        <div class="invalid-feedback">O preço do ingresso integral é obrigatório.</div>
                    </div>

                    <div class="mb-3 col-md-6">
                        <label for="discounted_price" class="form-label">Preço Meia-Entrada</label>
                        <input type="text" class="form-control price_formatted" readonly disabled placeholder="Preço Meia-Entrada" id="discounted_price_${sectorIndex}" name="sector_discounted_price[]" required>
                        <div class="invalid-feedback">O preço da meia-entrada é obrigatório.</div>
                    </div>

                    <div class="mb-3 col-md-12">
                        <p>Total de cadeiras: <strong class="badge bg-dark" id="total_seats_${sectorIndex}">0</strong></p>
                    </div>
                </div>

                <button type="button" class="btn badge btn-sm btn-danger" onclick="removeSector(${sectorIndex})">Remover Setor</button>
            </div>
        `;

        // Adicionar o setor na tela
        document.getElementById('sectors-container').insertAdjacentHTML('afterbegin', sectorHTML);

        // Aplicar o formato de moeda (função que já existe)
        applyCurrencyFormat();

        // Obter referências aos campos e ao elemento de exibição do total
        const rowsInput = document.getElementById(`rows_count_${sectorIndex}`);
        const seatsInput = document.getElementById(`seats_count_${sectorIndex}`);
        const totalSeatsDisplay = document.getElementById(`total_seats_${sectorIndex}`);
        const ticketPriceInput = document.getElementById(`ticket_price_${sectorIndex}`);
        const discountedPriceInput = document.getElementById(`discounted_price_${sectorIndex}`);

        // Atualizar o total de cadeiras ao mudar os valores
        rowsInput.addEventListener('input', () => updateTotalSeats(rowsInput, seatsInput, totalSeatsDisplay));
        seatsInput.addEventListener('input', () => updateTotalSeats(rowsInput, seatsInput, totalSeatsDisplay));


// CORRIGIR CÁLCULO DE PREÇO DA MEIA ENTRADA

        // Adiciona um listener de evento para o campo de "Preço do Ingresso Integral" que será ativado a cada alteração no valor (input)
        ticketPriceInput.addEventListener('input', () => {

            // Converte o valor do campo "Preço do Ingresso Integral" para um número float, removendo o símbolo "R$" e substituindo vírgula por ponto
            const fullPrice = parseFloat(ticketPriceInput.value.replace('R$', '').replace(',', '.'));

            // Verifica se o valor inserido é um número válido
            if (!isNaN(fullPrice)) {

                // Calcula o preço da meia-entrada, dividindo o preço integral por 2
                const discountedPrice = fullPrice / 2;

                // Formata o valor da meia-entrada para o formato "R$ xx,xx", com duas casas decimais
                discountedPriceInput.value = `R$ ${discountedPrice.toFixed(2).replace('.', ',')}`;
            } else {

                // Se o valor inserido não for válido, define o valor de "Meia-Entrada" como "R$ 0,00"
                discountedPriceInput.value = 'R$ 0,00';
            }
        });


        // Incrementar o índice do setor
        sectorIndex++;
    }

    // Função para remover um setor
    function removeSector(index) {
        document.getElementById(`sector-${index}`).remove();
    }

    // Função de validação para as etapas
    function validateStep(stepIndex) {
        // Obtém o elemento da etapa com base no índice fornecido
        const step = document.getElementById(`step-${stepIndex}`);

        // Seleciona todos os inputs e textareas obrigatórios dentro da etapa
        const inputs = step.querySelectorAll('input[required], textarea[required]');

        // Variável que determinará se a etapa é válida ou não
        let valid = true;

        // Itera sobre cada input/textarea da etapa
        inputs.forEach(input => {

            // Se o valor do input estiver vazio, adiciona a classe 'is-invalid' (para indicar erro)
            // Caso contrário, remove a classe 'is-invalid' (para indicar que está válido)
            if (!input.value) {
                input.classList.add('is-invalid'); // Adiciona erro se estiver vazio
                valid = false; // Marca a etapa como inválida
            } else {
                input.classList.remove('is-invalid'); // Remove o erro caso tenha valor
            }
        });

        // Retorna se a etapa é válida ou não
        return valid;
    }

    // Função para validar as datas de início e término
    document.addEventListener('input', (event) => {
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');
        const btnNext = document.getElementById('next-1');

        // Converter valores para objetos Date
        const startDate = new Date(startDateInput.value);
        const endDate = new Date(endDateInput.value);
        const currentDate = new Date();

        // Zerar horas para comparar apenas datas (sem horário)
        currentDate.setHours(0, 0, 0, 0);
        startDate.setHours(0, 0, 0, 0);
        endDate.setHours(0, 0, 0, 0);

        let errorMessage = '';

        // 1️⃣ Validação: A data de início deve ser no futuro
        if (startDate <= currentDate) {
            errorMessage = 'A data de início deve ser futura.';
        }

        // 2️⃣ Validação: A data de início não pode ser maior que a data de término
        if (startDate > endDate) {
            errorMessage = 'A data de início não pode ser maior que a data de término.';
        }

        // 3️⃣ Exibir mensagem de erro, se houver
        if (errorMessage) {

            btnNext.disabled = true; // Bloqueia botão de avançar

            Toastify({
                text: errorMessage,
                close: true,
                gravity: "bottom",
                position: 'left',
                backgroundColor: '#dc3454'
            }).showToast();

        } else {

            btnNext.disabled = false; // Permite avançar
        }
    });



    // Ação ao clicar no botão "Próxima Etapa"
    document.getElementById('next-1').addEventListener('click', () => {
        if (validateStep(1)) {
            document.getElementById('step-1').classList.remove('active');
            document.getElementById('step-2').classList.add('active');

            // adicionar um setor
            addSector();
        }
    });

    // Ação ao clicar no botão "Anterior"
    document.getElementById('prev-2').addEventListener('click', () => {
        document.getElementById('step-2').classList.remove('active');
        document.getElementById('step-1').classList.add('active');
    });

    // Ação ao clicar no botão "Adicionar Setor"
    document.getElementById('add-sector-btn').addEventListener('click', addSector);


    // Função para submeter o formulário
    document.getElementById('event-form').addEventListener('submit', async (event) => {
        event.preventDefault();

        // adicionar a confirmação
        if (!confirm('Tem certeza que deseja salvar este evento?')) {
            return;
        }

        // Desabilitar o botão de salvar
        const btnSubmit = document.getElementById('btnSubmit');
        btnSubmit.disabled = true;
        btnSubmit.textContent = 'Por favor aguarde...';

        // Limpa erros anteriores
        document.getElementById('errorsList').innerHTML = '';

        // Obter o token CSRF
        let csrfInput = document.querySelector("#csrf_input");

        // Criar os dados do formulário
        const formData = new FormData(event.target);

        try {
            const response = await fetch(event.target.action, {
                method: event.target.method,
                body: formData
            });

            const data = await response.json();

            if (!response.ok) {
                if (response.status === 400) {
                    handleValidationErrors(data, csrfInput);
                    return;
                }
                throw new Error(`Erro na requisição: ${response.status}`);
            }

            // Temos sucesso?
            if (!data.success) {
                throw new Error(data.message || "Erro desconhecido");
            }

            // Redireciona 
            window.location.href = data.redirect;


        } catch (error) {
            console.error('Erro ao salvar o evento:', error.message);
            Toastify({
                text: error.message || 'Erro ao salvar o evento!',
                duration: 10000,
                close: true,
                gravity: "bottom",
                position: 'left',
                backgroundColor: '#dc3454'
            }).showToast();
        } finally {
            // Habilitar o botão de salvar
            btnSubmit.disabled = false;
            btnSubmit.textContent = 'Salvar Evento';
        }
    });

    /**
     * Exibe mensagens de erro de validação retornadas pelo servidor.
     */
    function handleValidationErrors(data, csrfInput) {
        csrfInput.value = data.token;
        const errorsList = document.getElementById('errorsList');
        errorsList.innerHTML = ''; // Limpa erros anteriores

        if (data.errors) {
            Object.values(data.errors).forEach(message => {
                const p = document.createElement('p');
                const li = document.createElement('li');
                li.className = 'text-danger bg-danger p-3 badge text-white';
                li.style.fontSize = "14px";
                li.textContent = message;
                p.appendChild(li);
                errorsList.appendChild(p);
            });
        }
    }


    document.addEventListener('DOMContentLoaded', () => {
        // Seleciona todos os setores renderizados quando estivemos editando
        const sectors = document.querySelectorAll('.sector-container');

        sectors.forEach(sector => {
            // Obtém o índice do setor a partir do ID
            const sectorIndex = sector.id.split('-')[1];

            // Seleciona os elementos de entrada de filas, cadeiras e o total
            const rowsInput = document.getElementById(`rows_count_${sectorIndex}`);
            const seatsInput = document.getElementById(`seats_count_${sectorIndex}`);
            const totalSeatsDisplay = document.getElementById(`total_seats_${sectorIndex}`);

            if (rowsInput && seatsInput && totalSeatsDisplay) {
                // Adiciona eventos para calcular o total quando os valores mudarem
                rowsInput.addEventListener('input', () => updateTotalSeats(rowsInput, seatsInput, totalSeatsDisplay));
                seatsInput.addEventListener('input', () => updateTotalSeats(rowsInput, seatsInput, totalSeatsDisplay));
            }
        });
    });
</script>


<?php echo $this->endSection(); ?>