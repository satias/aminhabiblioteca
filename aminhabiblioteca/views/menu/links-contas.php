<?php
$links_contas = [
    [
        "icon" => "account_circle",
        "label" => $perfil,
        "url" => get_link("perfil"),
        "related" => []
    ],
    [
        "icon" => "fact_check",
        "label" => $requisicoes,
        "url" => get_link("requisicoes"),
        "related" => []
    ],
    [
        "icon" => "pending_actions",
        "label" => $reservas,
        "url" => get_link("reservas"),
        "related" => []
    ],
    [
        "icon" => "bookmark",
        "label" => $favoritos,
        "url" => get_link("favoritos"),
        "related" => []
    ],
    [
        "icon" => "report",
        "label" => $multas,
        "url" => get_link("multas"),
        "related" => []
    ],
    [
        "icon" => "help",
        "label" => $suporte,
        "url" => get_link("suporte"),
        "related" => [get_link("detalhesticket"),get_link("criarticket")]
    ]
];
?>