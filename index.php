<?php
require_once 'config.php';
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // Read operation (fetch movie)
        $stmt = $pdo->query('SELECT * FROM movie');
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($result);
        break;
    
    case 'POST':
        // Create operation (add a new movie)
        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['name']) || empty($data['genre']) || empty($data['release_date'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing required data']);
            exit();
        }

        $name = $data['name'];
        $genre = $data['genre'];
        $release_date = $data['release_date'];
        
        $stmt = $pdo->prepare('INSERT INTO movie (name, genre, release_date) VALUES (?, ?, ?)');
        $stmt->execute([$name, $genre, $release_date]);
        
        echo json_encode(['message' => 'Movie added successfully']);
        break;
        
    case 'PUT':
        // Update operation (edit a movie)
        $data = json_decode(file_get_contents('php://input'), true);

        $id = $data['id'];
        $name = $data['name'];
        $genre = $data['genre'];
        $release_date = $data['release_date'];
        
        $stmt = $pdo->prepare('UPDATE movie SET name=?, genre=?, release_date=? WHERE id=?');
        $stmt->execute([$name, $genre, $release_date, $id]);
        
        echo json_encode(['message' => 'Movie updated successfully']);
        break;
        
    case 'DELETE':
        // Delete operation (remove a movie)
        $id = $_GET['id'];
        
        $stmt = $pdo->prepare('DELETE FROM movie WHERE id=?');
        $stmt->execute([$id]);
        
        echo json_encode(['message' => 'Movie deleted successfully']);
        break;

        default:

        // Invalid method
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        break;

}
?>