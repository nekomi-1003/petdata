<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");

include 'connection-pdo.php'; 

class PetDataHandler {

    public function getPets() {
        global $conn; 
        $response = [];

        try {
            $sql = "SELECT p.PetID, p.Name as petName, p.DateOfBirth, o.Name as ownerName, s.SpeciesName as species, b.BreedName as breed
                    FROM pets p
                    INNER JOIN owners o ON p.OwnerID = o.OwnerID
                    INNER JOIN species s ON p.SpeciesID = s.SpeciesID
                    INNER JOIN breeds b ON p.BreedID = b.BreedID";
            
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $pets = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($pets) {
                foreach ($pets as &$pet) {
                    $pet['DateOfBirth'] = date('Y-m-d', strtotime($pet['DateOfBirth']));
                }
                
                $response = ['success' => true, 'data' => $pets];
            } else {
                $response = ['success' => false, 'message' => 'No data found'];
            }
        } catch (PDOException $e) {
            $response = ['success' => false, 'error' => $e->getMessage()];
        }

        return json_encode($response);
    }
}

$handler = new PetDataHandler();
echo $handler->getPets();
?>
