<?php

//Mosse del pokemon
class Move {
    public $name;
    public $damage;

    public function __construct($name, $damage) {
        $this->name = $name;
        $this->damage = $damage;
    }
}
//Pokemon
class Pokemon {
    public $name;
    public $hp;
    public $moves;

    public function __construct($name, $hp, $moves) {
        $this->name = $name;
        $this->hp = $hp;
        $this->moves = $moves;
    }

    public function resetHP() {
        // reimposta gli HP a un valore predefinito (es. 100)
        $this->hp = 100;
    }

    public function isAlive() {
        return $this->hp > 0;
    }

    public function getMove($moveName) {
        foreach ($this->moves as $move) {
            if ($move->name === $moveName) {
                return $move;
            }
        }
        return null; // Restituisce null se la mossa non è trovata
    }

    public function takeDamage($damage) {
        $this->hp -= $damage;
        if ($this->hp < 0) {
            $this->hp = 0; // Assicura che gli HP non diventino negativi
        }
    }
}

//Array in cui le mosse vengono associate al pokemon
function getPokemon($name) {
    switch ($name) {
        case 'pikachu':
            $moves = [new Move('Fulmine', 20), new Move('Attacco Rapido', 10)];
            return new Pokemon('Pikachu', 100, $moves);
        case 'charmander':
            $moves = [new Move('Lanciafiamme', 20), new Move('Graffio', 10)];
            return new Pokemon('Charmander', 100, $moves);
        case 'bulbasaur':
            $moves = [new Move('Frustata', 20), new Move('Azione', 10)];
            return new Pokemon('Bulbasaur', 100, $moves);
        case 'squirtle':
            $moves = [new Move('Pistolacqua', 20), new Move('Morso', 10)];
            return new Pokemon('Squirtle', 100, $moves);
        default:
            return null;
    }
}
?>
