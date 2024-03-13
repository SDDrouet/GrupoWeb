<?php
include 'config.php';

// Fetch options based on selector1 value
$id_periodo = $_GET['id_periodo'];

// Realizar la consulta SQL
$query = "SELECT  d.id_docente, CONCAT(u.nombre, ' ',u.apellido, ' | ', u.cod_usuario) AS nombre 
            FROM docentes AS d
            INNER JOIN periodos_docentes AS pd ON d.id_docente = pd.id_docente
            INNER JOIN usuarios AS u ON d.id_usuario = u.id_usuario
            WHERE id_periodo = $id_periodo;";

$result = mysqli_query($link, $query);
$id_docente = array();
$nombre = array();

// Recorrer los resultados y crear opciones para el select
while ($row = mysqli_fetch_assoc($result)) {
    $id_docente[] = $row['id_docente'];
    $nombre[] = $row['nombre'];
}

// Crear un array asociativo con los arrays de cursos y horarios
$response = array(
    'id_docente' => $id_docente,
    'nombre' => $nombre
);

// Devolver las opciones en formato JSON
echo json_encode($response);

mysqli_close($link);
?>
