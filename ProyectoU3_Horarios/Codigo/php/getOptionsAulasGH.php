<?php
include 'config.php';

// Realizar la consulta SQL
$query = "SELECT CONCAT(id_aula,',',cod_aula) AS aula FROM aulas;";

$result = mysqli_query($link, $query);

$options = array();

// Recorrer los resultados y crear opciones para el select
while ($row = mysqli_fetch_assoc($result)) {
    $options[] = $row['aula'];
}

// Devolver las opciones en formato JSON
echo json_encode($options);

mysqli_close($link);
?>
