<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");

class PetDataHandler {

    function insertPetData($json) {
        include 'connection-pdo.php';

        $json = json_decode($json, true);
        $owner = $json['owner'];
        $speciesId = $json['speciesId'];
        $breed = $json['breed'];
        $pet = $json['pet'];

        try {
            $conn->beginTransaction();
            $sql = "INSERT INTO owners (Name, contactDetails, address) VALUES (:ownerName, :contactDetails, :address)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':ownerName', $owner['name']);
            $stmt->bindParam(':contactDetails', $owner['contactDetails']);
            $stmt->bindParam(':address', $owner['address']);
            $stmt->execute();
            $ownerId = $conn->lastInsertId();

            $sql = "INSERT INTO breeds (breedName, speciesID) VALUES (:breedName, :speciesID)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':breedName', $breed['name']);
            $stmt->bindParam(':speciesID', $speciesId);
            $stmt->execute();
            $breedId = $conn->lastInsertId();

            $sql = "INSERT INTO pets (Name, SpeciesID, BreedID, DateOfBirth, OwnerID) VALUES (:name, :speciesID, :breedID, :dateOfBirth, :ownerID)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':name', $pet['name']);
            $stmt->bindParam(':speciesID', $speciesId);
            $stmt->bindParam(':breedID', $breedId);
            $stmt->bindParam(':dateOfBirth', $pet['dateOfBirth']);
            $stmt->bindParam(':ownerID', $ownerId);
            $stmt->execute();

            $conn->commit();
            return json_encode(['success' => true, 'message' => 'Data inserted successfully']);

        } catch (PDOException $e) {
            $conn->rollBack();
            return json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $operation = isset($_POST['operation']) ? $_POST['operation'] : '';
    $json = isset($_POST['json']) ? $_POST['json'] : '';

    $handler = new PetDataHandler();
    switch ($operation) {
        case "insertPetData":
            echo $handler->insertPetData($json);
            break;
        default:
            echo json_encode(['success' => false, 'message' => 'Invalid operation']);
            break;
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
