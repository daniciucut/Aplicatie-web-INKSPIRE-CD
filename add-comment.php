<?php

// Includem fișierul de conectare la baza de date (folosind require_once pentru a evita includerile multiple)
require_once __DIR__ . '/config/database.php';

if (isset($_POST['submit'])) {
    // Preluăm și curățăm datele
    $post_id = filter_var($_POST['post_id'], FILTER_SANITIZE_NUMBER_INT);
    $user_name = trim($_POST['user_name']);
    $comment = trim($_POST['comment']);

    // Verificăm dacă toate câmpurile sunt completate
    if (!empty($post_id) && !empty($user_name) && !empty($comment)) {
        // Pregătim interogarea folosind prepared statement
        $stmt = $connection->prepare("INSERT INTO comments (post_id, user_name, comment) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $post_id, $user_name, $comment);

        if ($stmt->execute()) {
            // Redirecționăm înapoi la pagina postării pentru a vedea comentariul adăugat
            header("Location: post.php?id=" . $post_id);
            exit();
        } else {
            echo "Eroare la salvarea comentariului.";
        }
    } else {
        echo "Toate câmpurile sunt obligatorii!";
    }
}
?>
