<?php

if (isset($_GET["id"])) {
    $category = CategoryData::getById($_GET["id"]);

    $nuevo_estado = ($category->is_active == 1) ? 0 : 1;

    $category->updateActive($nuevo_estado);

}

Core::redir("./index.php?view=categories");
