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

$sql = "SELECT id_periodo_docente, pd.id_docente, u.cod_usuario, u.nombre, u.apellido, d.especializacion 
        FROM docentes AS d
        INNER JOIN periodos_docentes AS pd ON d.id_docente = pd.id_docente
        INNER JOIN usuarios AS u ON d.id_usuario = u.id_usuario
        WHERE estado = 1
        AND id_periodo = $selector1Value
        AND horas_asignadas > 0;";
        


$result = $conn->query($sql);

$options = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $value = $row['id_periodo_docente'].','.$row['id_docente'];
        unset($row["id_periodo_docente"]);
        unset($row["id_docente"]);
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
