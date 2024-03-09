<?php
// Database connection
$servername = "localhost";
$username = "admin";
$password = "admin";
$dbname = "PROYECTO_14768";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch options based on selector1 value
$selector1Value = $_GET['selector1'];

$sql = "SELECT c.id_curso,
        CONCAT(m.nombre_materia, ' | ', c.nrc) AS nombre_curso
        FROM cursos c
        JOIN materias m ON m.id_materia = c.id_materia
        WHERE c.periodos_id_periodo = $selector1Value
        ORDER by id_curso DESC;";
        


$result = $conn->query($sql);

$options = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $value = $row['id_curso'];
        unset($row["id_curso"]);
        $txt = $row["nombre_curso"];
        
        $options[] = array(
            'texto' => $txt,
            'valor' => $value
        );
    }
}

// Return options as JSON
echo json_encode($options);

$conn->close();
?>
