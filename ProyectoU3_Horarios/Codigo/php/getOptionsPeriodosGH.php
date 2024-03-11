<?php
include 'config.php';

// Realizar la consulta SQL
$query = "SELECT CONCAT(id_periodo,',',nombre_periodo) AS periodo FROM periodos
           ORDER BY id_periodo ASC;";

$result = mysqli_query($link, $query);

$options = array();

// Recorrer los resultados y crear opciones para el select
while ($row = mysqli_fetch_assoc($result)) {
    $options[] = $row['periodo'];

}

// Devolver las opciones en formato JSON
echo json_encode($options);

mysqli_close($link);
?>
