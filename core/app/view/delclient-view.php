<?php
if (isset($_GET["id"])) {
    $id = $_GET["id"];
    PersonData::deleteById($id); // Llamada al método de eliminación

Core::redir("./index.php?view=clients");
}

?>