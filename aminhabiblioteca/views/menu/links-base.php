<?php
$links_base = [
    [
        "icon" => "home",
        "label" => $dashboard,
        "url" => get_link(""),
        "related" => [] 
    ],
    [
        "icon" => "menu_book",
        "label" => $livros,
        "url" => get_link("livros"),
        "related" => [get_link("livro")] 
    ],
    [
        "icon" => "group",
        "label" => $autores,
        "url" => get_link("autores"),
        "related" => [get_link("autor")] 
    ]
];
?>