<?php
$links_staff = [
    [
        "icon" => "person_search",
        "label" => $procuti,
        "url" => get_link("procurarutilizador"),
        "related" => []
    ],
    [
        "icon" => "auto_stories",
        "label" => $gerlivr,
        "url" => get_link("gerirlivros"),
        "related" => [get_link("gerirlivro"),get_link("detalheslivro")]
    ],
    [
        "icon" => "account_box",
        "label" => $gerauto,
        "url" => get_link("gerirautores"),
        "related" => [get_link("detalhesautor"),get_link("gerirautor")]
    ],
    [
        "icon" => "help_center",
        "label" => $gertick,
        "url" => get_link("gerirtickets"),
        "related" => [get_link("detalhesticketstaff")]
    ],
    [
        "icon" => "lists",
        "label" => $listarequisicoes,
        "url" => get_link("listarequisicoes"),
        "related" => []
    ],
    [
        "icon" => "data_alert",
        "label" => $listamultas,
        "url" => get_link("listamultas"),
        "related" => []
    ]
];
?>