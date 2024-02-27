<?php
// Database connection
$servername = "localhost";
$username = "admin";
$password = "admin";
$dbname = "horarios";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch options based on selector1 value
$selector1Value = $_GET['selector1'];

$sql = "SELECT id_horario__aula, id_aula, dia, hora_inicio, hora_fin FROM horarios_aulas AS ha
        INNER JOIN horarios AS a ON ha.id_horario = a.id_horario
        WHERE disponible = 1 AND id_periodo = $selector1Value";


$result = $conn->query($sql);

$options = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $value = $row['id_horario__aula'];
        unset($row["id_horario__aula"]);
        $txt = implode(" | ", $row);
        
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
