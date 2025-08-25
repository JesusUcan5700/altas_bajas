<?php
// Test AJAX directo para debug

$url = 'http://localhost/altas_bajas/frontend/web/index.php?r=site/editar';
$data = 'action=cargar_equipos&categoria=nobreak';

$options = array(
    'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => $data
    )
);

$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);

echo "Respuesta del servidor:\n";
echo $result;
echo "\n\n";

// También probemos con computo
$data = 'action=cargar_equipos&categoria=computo';
$options['http']['content'] = $data;
$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);

echo "Respuesta para computo:\n";
echo $result;
?>
