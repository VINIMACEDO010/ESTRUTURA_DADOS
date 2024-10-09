<?php

$graph = [
    'POUSO REDONDO' => [
        'TAIÓ' => 30,
        'IMBUIA' => 85,
        'SALETE' => 40,
        'IBIRAMA' => 180,
        'BRAÇO DO TROMBUDO' => 60
    ],
    'AURORA' => [
        'IBIRAMA' => 160,
    ],
    'TAIÓ' => [
        'POUSO REDONDO' => 30
    ],
    'LONTRAS' => [
        'ITUPORANGA' => 120,
    ],
    'BRAÇO DO TROMBUDO' => [
        'IBIRAMA' => 180,
        'DONA EMMA' => 165,
        'ITUPORANGA' => 90,
        'POUSO REDONDO' => 60,
        'TROMBUDO CENTRAL' => 15,
        'AGROLÂNDIA' => 15,
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
        'CHAPADÃO DO LAGEADO' => 150
    ],
    'CHAPADÃO DO LAGEADO' => [
        'SALETE' => 150,
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


function dijkstra($graph, $start, $end) {
    // Verifica se o início e o fim estão no grafo
    if (!isset($graph[$start]) || !isset($graph[$end])) {
        return ['distance' => INF, 'path' => []];
    }
    
    // Array para armazenar a distância mínima de $start para cada cidade
    $dist = [];
    // Array para armazenar o caminho mais curto conhecido até agora
    $previous = [];
    // Fila de prioridades com as cidades a serem exploradas
    $queue = [];
    
    foreach ($graph as $vertex => $neighbors) {
        if ($vertex == $start) {
            $dist[$vertex] = 0;
            $queue[$vertex] = 0;
        } else {
            $dist[$vertex] = INF; 
            $queue[$vertex] = INF;
        }
        $previous[$vertex] = null;
    }

    while (!empty($queue)) {
        
        $minVertex = array_search(min($queue), $queue);
        if ($minVertex === $end) {
            break; 
        }

        foreach ($graph[$minVertex] as $neighbor => $cost) {
            $alt = $dist[$minVertex] + $cost;
            if ($alt < $dist[$neighbor]) { // Encontramos um caminho melhor
                $dist[$neighbor] = $alt;
                $previous[$neighbor] = $minVertex;
                $queue[$neighbor] = $alt;
            }
        }
        unset($queue[$minVertex]); // Remover o nó já processado
    }


    $path = [];
    $u = $end;
    while (isset($previous[$u]) && $previous[$u] !== null) {
        array_unshift($path, $u);
        $u = $previous[$u];
    }
    if ($dist[$end] != INF) {
        array_unshift($path, $start);
    }

    return ['distance' => $dist[$end], 'path' => $path];
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $start = $_POST['start'] ?? 'POUSO REDONDO';
    $end = $_POST['end'] ?? 'ITUPORANGA';

    $result = dijkstra($graph, $start, $end);

    echo "CUSTO TOTAL DE {$start} A {$end}: " . number_format($result['distance'], 0) . "<br>";
    echo "CAMINHO: " . implode(" -> ", $result['path']) . "<br>";
    } else {
        echo "Método de requisição inválido.";
    }
    ?>
