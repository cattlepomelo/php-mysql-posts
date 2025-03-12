<?php
// Konfigurācijas dati datubāzes savienojumam
$servername = "localhost";
$username = "bebra";  // mainiet, ja nepieciešams
$password = "password"; // mainiet, ja nepieciešams
$dbname = "blog_12032025";

try {
    // Izveidot savienojumu ar PDO
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Kļūda: " . $e->getMessage();
    die(); // Ja savienojums neizdodas, izbeigt izpildi
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Pārbaudām, vai ir saņemti visi nepieciešamie dati
    if (isset($_POST['title']) && isset($_POST['author']) && isset($_POST['content'])) {
        // Iegūstam datus no formas
        $title = htmlspecialchars($_POST['title']);
        $author = htmlspecialchars($_POST['author']);
        $content = htmlspecialchars($_POST['content']);
        $created_at = date('Y-m-d H:i:s');  // Izveidojam pašreizējo datumu un laiku

        try {
            // SQL vaicājums, lai ievietotu jaunu rakstu
            $sql = "INSERT INTO posts (title, author, content, created_at) VALUES (:title, :author, :content, :created_at)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':author', $author);
            $stmt->bindParam(':content', $content);
            $stmt->bindParam(':created_at', $created_at);

            // Izpildām vaicājumu
            $stmt->execute();

            echo "<p>Jauns raksts tika pievienots veiksmīgi!</p>";
        } catch (PDOException $e) {
            echo "Kļūda: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pievienot Jaunu Rakstu</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 50%;
            margin: 0 auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        input, textarea {
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        button {
            padding: 10px 20px;
            background-color: #2c3e50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Pievienot Jaunu Rakstu</h1>
        
        <form action="new-post.php" method="POST">
            <label for="title">Raksta nosaukums:</label>
            <input type="text" name="title" id="title" required>

            <label for="author">Autors:</label>
            <input type="text" name="author" id="author" required>

            <label for="content">Saturs:</label>
            <textarea name="content" id="content" rows="5" required></textarea>

            <button type="submit">Ievietot rakstu</button>
        </form>
    </div>
</body>
</html>
