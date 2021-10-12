<?php
    function charger_tpl($page)
    {
        $page = 'vue/' . $page . '.tpl';
        if (file_exists($page))
                require $page;
        else
                require 'vue/404.tpl';
    }

    $page = isset($_GET['p']) ? $_GET['p'] : 'accueil';
    
    charger_tpl($page);
?>