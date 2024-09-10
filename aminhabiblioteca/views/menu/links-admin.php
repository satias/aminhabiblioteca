<?php
$links_admin = [
    [
        "icon" => "person_search",
        "label" => $procont,
        "url" => get_link("procurarconta"),
        "related" => []
    ],
    [
        "icon" => "auto_delete",
        "label" => $listdel,
        "url" => get_link("listapagar"),
        "related" => [] 
    ],
    [
        "icon" => "help_center",
        "label" => $gertick,
        "url" => get_link("gerirtickets"),
        "related" => [get_link("detalhesticketstaff")]
    ]
];
?>

