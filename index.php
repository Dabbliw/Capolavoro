<?php
session_start();
require 'pokemon.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pokemon'])) {
    $_SESSION['selectedPokemon'] = $_POST['pokemon'];
    header("Location: battle.php");
    exit();
}

if (isset($_POST['restart']) && $_POST['restart'] === 'true') {
    // Elimina i dati della sessione relativi alla battaglia precedente
    unset($_SESSION['playerPokemon']);
    unset($_SESSION['enemyPokemon']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gioco Pokemon</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Benvenuto al mio gioco Pokemon!</h1>
    <form action="index.php" method="POST">
        <label for="pokemon">Scegli il tuo Pokémon:</label>
        <select name="pokemon" id="pokemon">
            <option value="pikachu">Pikachu</option>
            <option value="charmander">Charmander</option>
            <option value="bulbasaur">Bulbasaur</option>
            <option value="squirtle">Squirtle</option>
        </select>
        <button type="submit">Inizia</button>
    </form>
</body>
</html>
