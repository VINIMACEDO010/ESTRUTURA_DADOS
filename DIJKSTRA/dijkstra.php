<?php
// Passo 1: Montagem do grafo das cidades e suas respectivas distâncias
$cidadesGrafo = [
    'POUSO REDONDO' => [
        'TAIÓ' => 30,
        'IMBUIA' => 85,
        'SALETE' => 40,
        'IBIRAMA' => 180,
        'BRAÇO DO TROMBUDO' => 60
    ],
    'AURORA' => [
        'IBIRAMA' => 160
    ],
    'TAIÓ' => [
        'POUSO REDONDO' => 30
    ],
    'LONTRAS' => [
        'ITUPORANGA' => 120
    ],
    'BRAÇO DO TROMBUDO' => [
        'IBIRAMA' => 180,
        'DONA EMMA' => 165,
        'ITUPORANGA' => 90,
        'POUSO REDONDO' => 60,
        'TROMBUDO CENTRAL' => 15,
        'AGROLÂNDIA' => 15
    ],
    'IMBUIA' => [
        'POUSO REDONDO' => 85
    ],
    'AGROLÂNDIA' => [
        'IBIRAMA' => 180,
        'BRAÇO DO TROMBUDO' => 30
    ],
    'IBIRAMA' => [
        'AGROLÂNDIA' => 180,
        'AURORA' => 160,
        'DONA EMMA' => 66,
        'BRAÇO DO TROMBUDO' => 180,
        'POUSO REDONDO' => 180
    ],
    'DONA EMMA' => [
        'RIO DO CAMPO' => 121,
        'ITUPORANGA' => 105,
        'BRAÇO DO TROMBUDO' => 165,
        'IBIRAMA' => 66
    ],
    'SALETE' => [
        'POUSO REDONDO' => 40,
        'CHAPADÃO DO LAGEADO' => 250
    ],
    'CHAPADÃO DO LAGEADO' => [
        'SALETE' => 250,
        'PRESIDENTE GETULIO' => 160,
        'TROMBUDO CENTRAL' => 225
    ],
    'RIO DO CAMPO' => [
        'DONA EMMA' => 143,
        'ITUPORANGA' => 345,
        'TROMBUDO CENTRAL' => 150
    ],
    'TROMBUDO CENTRAL' => [
        'RIO DO CAMPO' => 150,
        'BRAÇO DO TROMBUDO' => 15,
        'CHAPADÃO DO LAGEADO' => 225
    ],
    'PRESIDENTE GETULIO' => [
        'CHAPADÃO DO LAGEADO' => 160
    ],
    'ITUPORANGA' => [
        'DONA EMMA' => 105,
        'RIO DO CAMPO' => 345,
        'LONTRAS' => 120,
        'BRAÇO DO TROMBUDO' => 90
    ]
];

// Passo 2: Implementação da função de Dijkstra
function calcularCaminhoMaisCurto($cidadesGrafo, $cidadeOrigem, $cidadeDestino) {
    // Verifica se a cidade de origem e a cidade de destino estão no grafo
    if (!isset($cidadesGrafo[$cidadeOrigem]) || !isset($cidadesGrafo[$cidadeDestino])) {
        return ['distanciaTotal' => INF, 'caminho' => []];
    }

    // Inicializa as distâncias, o caminho anterior e a fila de prioridades
    $distanciaCidade = [];
    $cidadeAnterior = [];
    $filaDeProcessamento = [];

    // Definindo as distâncias iniciais
    foreach ($cidadesGrafo as $cidadeAtual => $vizinhos) {
        if ($cidadeAtual == $cidadeOrigem) {
            $distanciaCidade[$cidadeAtual] = 0;
            $filaDeProcessamento[$cidadeAtual] = 0;
        } else {
            $distanciaCidade[$cidadeAtual] = INF;
            $filaDeProcessamento[$cidadeAtual] = INF;
        }
        $cidadeAnterior[$cidadeAtual] = null;
    }

    // Loop principal para encontrar o caminho mais curto
    while (!empty($filaDeProcessamento)) {
        // Seleciona a cidade com a menor distância
        $cidadeMaisProxima = array_search(min($filaDeProcessamento), $filaDeProcessamento);

        // Se chegamos ao destino, saímos do loop
        if ($cidadeMaisProxima === $cidadeDestino) {
            break;
        }

        // Processa os vizinhos da cidade atual
        foreach ($cidadesGrafo[$cidadeMaisProxima] as $vizinho => $custo) {
            $novaDistancia = $distanciaCidade[$cidadeMaisProxima] + $custo;

            // Se encontramos um caminho mais curto, atualizamos as distâncias e o caminho
            if ($novaDistancia < $distanciaCidade[$vizinho]) {
                $distanciaCidade[$vizinho] = $novaDistancia;
                $cidadeAnterior[$vizinho] = $cidadeMaisProxima;
                $filaDeProcessamento[$vizinho] = $novaDistancia;
            }
        }

        // Remove a cidade já processada da fila
        unset($filaDeProcessamento[$cidadeMaisProxima]);
    }

    // Reconstrução do caminho mais curto
    $caminhoMaisCurto = [];
    $cidadeAtual = $cidadeDestino;
    while (isset($cidadeAnterior[$cidadeAtual]) && $cidadeAnterior[$cidadeAtual] !== null) {
        array_unshift($caminhoMaisCurto, $cidadeAtual);
        $cidadeAtual = $cidadeAnterior[$cidadeAtual];
    }

    // Inclui a cidade de origem no caminho, se o destino foi alcançado
    if ($distanciaCidade[$cidadeDestino] != INF) {
        array_unshift($caminhoMaisCurto, $cidadeOrigem);
    }

    // Retorna a distância total e o caminho encontrado
    return [
        'distanciaTotal' => $distanciaCidade[$cidadeDestino],
        'caminho' => $caminhoMaisCurto
    ];
}

// Passo 3: Interface de entrada e saída de dados via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtém as cidades de origem e destino enviadas pelo formulário
    $cidadeOrigem = $_POST['cidadeOrigem'] ?? 'POUSO REDONDO';
    $cidadeDestino = $_POST['cidadeDestino'] ?? 'ITUPORANGA';

    // Calcula o caminho mais curto entre as cidades de origem e destino
    $resultado = calcularCaminhoMaisCurto($cidadesGrafo, $cidadeOrigem, $cidadeDestino);

    // Exibe os resultados na tela
    echo "CUSTO TOTAL DE {$cidadeOrigem} A {$cidadeDestino}: " . number_format($resultado['distanciaTotal'], 0) . "<br>";
    echo "CAMINHO: " . implode(" -> ", $resultado['caminho']) . "<br>";
} else {
    echo "Método de requisição inválido.";
}
?>
