<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");

class Species {
    function getSpecies() {
        include 'connection-pdo.php'; 
        
        try {
            $sql = "SELECT SpeciesID, SpeciesName FROM species ORDER BY SpeciesName";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return json_encode($result);
        } catch (PDOException $e) {
            return json_encode(['error' => $e->getMessage()]);
        } finally {
            unset($conn);
            unset($stmt);
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $operation = isset($_GET['operation']) ? $_GET['operation'] : '';
    $json = isset($_GET['json']) ? $_GET['json'] : '';
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $operation = isset($_POST['operation']) ? $_POST['operation'] : '';
    $json = isset($_POST['json']) ? $_POST['json'] : '';
}

$species = new Species();
switch ($operation) {
    case "getSpecies":
        echo $species->getSpecies();
        break;
    default:
        echo json_encode(['error' => 'Invalid operation']);
        break;
}
?>
