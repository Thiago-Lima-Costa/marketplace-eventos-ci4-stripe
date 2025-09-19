// Função para formatar o valor como moeda
function formatCurrency(value) {
    // Verifica se o valor é nulo ou indefinido
    if (value == null || value.trim() === '') {
        return '';
    }

    // Verifica se o valor é do tipo número e converte para string se necessário
    if (typeof value === 'number') {
        value = value.toString();
    }

    // Remove todos os caracteres não numéricos
    value = value.replace(/\D/g, '');
    // Divide o valor por 100 e fixa em duas casas decimais
    value = (value / 100).toFixed(2) + '';
    // Substitui o ponto decimal por uma vírgula
    value = value.replace('.', ',');
    // Adiciona pontos como separadores de milhares
    value = value.replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
    // Retorna o valor formatado com o símbolo R$
    return `R$ ${value}`;
}

// Função para aplicar a formatação nos inputs
function applyCurrencyFormat() {
    // Seleciona todos os inputs com a classe price_formatted
    const inputs = document.querySelectorAll('.price_formatted');

    inputs.forEach(input => {
        // Aplica a formatação quando a página carrega
        input.value = formatCurrency(input.value);

        // Aplica a formatação enquanto o usuário digita
        input.addEventListener('input', () => {
            // Formata o valor atual do input
            input.value = formatCurrency(input.value);

            // Move o cursor para o final do input
            input.setSelectionRange(input.value.length, input.value.length);
        });
    });
}

// Função para formatar um CPF (000.000.000-00)
function formatCPF(value) {
    value = value.replace(/\D/g, ""); // Remove caracteres não numéricos
    if (value.length > 11) {
        value = value.slice(0, 11) // Limita a 11 dígitos
    }

    return value
        .replace(/^(\d{3})(\d)/, "$1.$2")
        .replace(/^(\d{3})\.(\d{3})(\d)/, "$1.$2.$3")
        .replace(/^(\d{3})\.(\d{3})\.(\d{3})(\d)/, "$1.$2.$3-$4");
}

// Função para formatar telefone no padrão (XX) XXXXX-XXXX
function formatPhone(value) {
    value = value.replace(/\D/g, ""); // Remove caracteres não numéricos
    if (value.length > 11) value = value.slice(0, 11); // Limita a 11 dígitos

    return value
        .replace(/^(\d{2})(\d)/, "($1) $2") // Adiciona parênteses no DDD
        .replace(/(\d{5})(\d)/, "$1-$2"); // Adiciona o hífen após os 5 primeiros dígitos
}