<?php

namespace App\Service;

use App\Repository\GameRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class PointsManager
{
    public function __construct(
        private RequestStack $requestStack,
        private GameRepository $gameRipository
        )
    {
        $this->requestStack = $requestStack;
        $this->gameRipository = $gameRipository;
    }

    public function calcuatePoints(array $cards): void
    {
        $game =  $this->requestStack->getSession()->get('game');
        $points = $game->getScore();
        foreach ($cards as $card) {
            $rules = $card->getRule();
            if ($card->getFamily() === 'Apocalypse') {
                dd($rules);
                if ($rules['type'] === 'points') {
                    $points -= $rules['value'];
                } elseif ($rules['type'] === 'artisant') {
                    # enlève les cartes artisant
                    $points -= $rules['value'] * 10;
                } elseif ($rules['type'] === 'marchand') {
                    # enlève les cartes marchand
                    $points -= $rules['value'] * 10;
                } elseif ($rules['type'] === 'commerçant') {
                    # enlève 2 cartes artisant ou marchand
                    $points -= $rules['value'] * 10;
                }
            } else {
                if (!empty($rules['association'])) {
                    #vérifier si la carte associer est bien dans le classeur
                    dd('bonus');
                }

                if (!empty($rules['constraint'])) {
                    dd('malus');
                    # requéte apocalypse
                }

                if (empty($rules['association']) && empty($rules['constraint'])) {
                    $points += $card->getCredit();
                }
                $points += 10;
            }
        }
    $game->setScore($points);
    }
}
