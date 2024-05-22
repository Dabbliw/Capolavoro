<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Battle</title>
    <style>

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            text-align: center;
        }

        .battle-container {
            position: relative;
            width: 900px;
            height: 500px;
            margin: auto;
            background-image: url('https://external-content.duckduckgo.com/iu/?u=https%3A%2F%2Ftse3.mm.bing.net%2Fth%3Fid%3DOIP.fB20Xkct-KQCSk0-hj1HxQHaEc%26pid%3DApi&f=1&ipt=e9ade0c6c7b36c82d9a44985d0707ae53eeaf7f421e3a74b1ca5d2af1ebf5f0b&ipo=images');
            background-size: cover;
            background-position: center;
        }

        .pokemon {
            position: absolute;
            bottom: 3%;
            left: 30%;
            transform: translateX(-50%);
            width: 150px;
            height: auto;
        }

        .enemy-pokemon {
            position: absolute;
            bottom: 30%;
            left: 70%;
            transform: translateX(-50%);
            width: 150px;
            height: auto;
        }

        .battle-info {
            margin-top: 20px;
        }

        .enemy-hp {
            position: absolute;
            top: 40px;
            right: 10px;
            background-color: white;
            padding: 5px;
            border-radius: 5px;
        }

        .player-hp {
            position: absolute;
            bottom: 10px;
            left: 10px;
            background-color: white;
            padding: 5px;
            border-radius: 5px;
        }

        .pokemon-name {
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }

        .enemy-info {
            position: absolute;
            top: 10px;
            right: 56px;
            background-color: white;
            padding: 5px;
            border-radius: 5px;
        }

        .player-info {
            position: absolute;
            bottom: 40px;
            left: 10px;
            background-color: white;
            padding: 5px;
            border-radius: 5px;
        }
        .large-image {
            width: 500px;
            height: 300px;
        }

    </style>
</head>
<body>
<?php
session_start();
require 'pokemon.php'; 

// Inizializza $battleOutcome come stringa vuota
$battleOutcome = "";

// Controlla se l'utente ha selezionato un Pokémon
if (!isset($_SESSION['selectedPokemon'])) {
    header("Location: index.php");
    exit();
}

// Controlla se è iniziata una nuova battaglia o se il giocatore ha scelto di rigiocare
if (!isset($_SESSION['playerPokemon']) || !isset($_SESSION['enemyPokemon']) || isset($_POST['restart'])) {
    // Recupera il Pokémon del giocatore dalla sessione
    $playerPokemon = getPokemon($_SESSION['selectedPokemon']);
    $playerPokemon->resetHP(); // Reimposta gli HP del Pokémon del giocatore

    // Crea un Pokémon nemico casuale
    $enemyPokemon = getPokemon(array_rand(['pikachu' => 1, 'charmander' => 1, 'bulbasaur' => 1, 'squirtle' => 1]));
    $enemyPokemon->resetHP(); // Reimposta gli HP del Pokémon nemico

    // Salva i Pokémon nella sessione
    $_SESSION['playerPokemon'] = serialize($playerPokemon);
    $_SESSION['enemyPokemon'] = serialize($enemyPokemon);
}

// Ricarica i Pokémon dalla sessione
$playerPokemon = unserialize($_SESSION['playerPokemon']);
$enemyPokemon = unserialize($_SESSION['enemyPokemon']);

// Array associativo che mappa i nomi dei Pokémon ai link delle immagini degli sprite (player 1)
$playerPokemonSprites = [
    'Pikachu' => '<a href="https://pokemondb.net/pokedex/pikachu"><img src="https://img.pokemondb.net/sprites/black-white/anim/back-normal/pikachu.gif" alt="Pikachu" height="140" width="140"></a>',
    'Charmander' => '<a href="https://pokemondb.net/pokedex/charmander"><img src="https://img.pokemondb.net/sprites/black-white/anim/back-normal/charmander.gif" alt="Charmander" height="140" width="140"></a>',
    'Bulbasaur' => '<a href="https://pokemondb.net/pokedex/bulbasaur"><img src="https://img.pokemondb.net/sprites/black-white/anim/back-normal/bulbasaur.gif" alt="Bulbasaur" height="140" width="140"></a>',
    'Squirtle' => '<a href="https://pokemondb.net/pokedex/squirtle"><img src="https://img.pokemondb.net/sprites/black-white/anim/back-normal/squirtle.gif" alt="Squirtle" height="140" width="140"></a>'
];

// Array associativo che mappa i nomi dei Pokémon ai link delle immagini degli sprite (avversario)
$enemyPokemonSprites = [
    'Pikachu' => '<a href="https://pokemondb.net/pokedex/pikachu"><img src="https://img.pokemondb.net/sprites/black-white/anim/normal/pikachu.gif" alt="Pikachu" height="140" width="140"></a>',
    'Charmander' => '<a href="https://pokemondb.net/pokedex/charmander"><img src="https://img.pokemondb.net/sprites/black-white/anim/normal/charmander.gif" alt="Charmander" height="140" width="140"></a>',
    'Bulbasaur' => '<a href="https://pokemondb.net/pokedex/bulbasaur"><img src="https://img.pokemondb.net/sprites/black-white/anim/normal/bulbasaur.gif" alt="Bulbasaur" height="140" width="140"></a>',
    'Squirtle' => '<a href="https://pokemondb.net/pokedex/squirtle"><img src="https://img.pokemondb.net/sprites/black-white/anim/normal/squirtle.gif" alt="Squirtle" height="140" width="140"></a>'
];

// Usa il nome del Pokémon per ottenere il link del file dello sprite
$playerSprite = isset($playerPokemon) ? $playerPokemonSprites[$playerPokemon->name] : '';
$enemySprite = isset($enemyPokemon) ? $enemyPokemonSprites[$enemyPokemon->name] : '';

// Aggiungi messaggi di debug
error_log("Player Sprite URL: " . $playerSprite);
error_log("Enemy Sprite URL: " . $enemySprite);

// Funzione per visualizzare le opzioni di mossa
function displayMoveOptions($pokemon) {
    $options = '';
    foreach ($pokemon->moves as $move) {
        $options .= "<button type='submit' name='move' value='{$move->name}'>{$move->name}</button><br>";
    }
    return $options;
}

// Se il giocatore ha inviato una mossa
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['move'])) {
    // Recupera i Pokémon dalla sessione
    $playerPokemon = unserialize($_SESSION['playerPokemon']);
    $enemyPokemon = unserialize($_SESSION['enemyPokemon']);

    // Esegue la logica del combattimento
    $playerMove = $playerPokemon->getMove($_POST['move']);
    $enemyMove = $enemyPokemon->moves[array_rand($enemyPokemon->moves)];

    if ($playerMove) {
        $enemyPokemon->takeDamage($playerMove->damage);
    }

    if ($enemyPokemon->isAlive()) {
        $playerPokemon->takeDamage($enemyMove->damage);
    }

    // Salva i Pokémon aggiornati nella sessione
    $_SESSION['playerPokemon'] = serialize($playerPokemon);
    $_SESSION['enemyPokemon'] = serialize($enemyPokemon);

    // Controlla se il giocatore ha vinto
    if (!$enemyPokemon->isAlive()) {
        $battleOutcome = "win";
    }
    // Controlla se il giocatore ha perso
    if (!$playerPokemon->isAlive()) {
        $battleOutcome = "lose";
    }
}

// Ricarica i Pokémon della sessione
$playerPokemon = unserialize($_SESSION['playerPokemon']);
$enemyPokemon = unserialize($_SESSION['enemyPokemon']);
?>
<h1>Battle</h1>
<div class="battle-container">
    <div class="battle-field">
        <!--Sprites dei pokemon-->
        <div class="large-image">
            <div class="pokemon"><?php echo $playerSprite; ?></div>
            <div class="enemy-pokemon"><?php echo $enemySprite; ?></div>
        </div>      
    </div>
    <!--Hp di entrambi i pokemon-->
    <div class="enemy-hp">Enemy HP: <?php echo $enemyPokemon->hp; ?></div>
    <div class="enemy-info"><?php echo $enemyPokemon->name; ?></div>
    <div class="player-hp">Your HP: <?php echo $playerPokemon->hp; ?></div>
    <div class="player-info"><?php echo $playerPokemon->name; ?></div>
</div>
<?php if ($playerPokemon->isAlive() && $enemyPokemon->isAlive()) { ?>
    <form action="battle.php" method="POST">
        <h3>Choose your move:</h3>
        <?php echo displayMoveOptions($playerPokemon); ?>
    </form>
<?php } else { ?>
    <h2> 
        <!--Messaggi di fine battaglia-->
        <?php
        if ($battleOutcome === "win") {
            echo "Hai Vinto!";
        } elseif ($battleOutcome === "lose") {
            echo "Hai Perso!";
        }
        ?>
    </h2>

    <form action="battle.php" method="POST">
        <input type="hidden" name="restart" value="true">
        <button type="submit">Play Again</button>
    </form>
    <form action="index.php" method="POST">
        <input type="hidden" name="restart" value="true">
        <button type="submit">Select Pokémon</button>
    </form>
<?php } ?>
</body>
</html>
