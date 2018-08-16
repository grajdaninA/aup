<?php

require 'common.php';
$view = new View();
if (isset ($_POST['mode']) && $_POST['mode'] == 'add'){
    $objects['queryFactory']->addSnmpObject($_POST);
    $template = 'ok';
} else {
    $template = 'addobject';
}
$page = $view->getTemplate($template);
$page = $view->parseTemplate($page, $lang);
$view->display($page);

?>
