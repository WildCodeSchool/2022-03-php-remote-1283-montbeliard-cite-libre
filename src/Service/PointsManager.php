<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\RequestStack;

class PointsManager
{
    private RequestStack $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function calcuatePoints(array $cards): void
    {
        $session = $this->requestStack->getSession();
        $points =  $session->get('points');
        foreach ($cards as $card) {
            $rules = json_decode($card->getRule(), true);
            if ($card->getFamily() === 'Apocalypse') {
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
                } elseif (!empty($rules['constraint'])) {
                    # requéte apocalypse
                }
                $points += 10;
            }
        }
        $session->set('points', $points);
    }
}
