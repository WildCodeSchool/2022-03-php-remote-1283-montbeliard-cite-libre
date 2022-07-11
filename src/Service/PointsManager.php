<?php

namespace App\Service;

use App\Entity\Card;
use App\Entity\Game;
use App\Entity\CardWon;
use App\Entity\CardApocalypse;
use App\Repository\GameRepository;
use App\Repository\CardWonRepository;
use App\Repository\CardApocalypseRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class PointsManager
{
    public function __construct(
        private RequestStack $requestStack,
        private GameRepository $gameRepository,
        private CardWonRepository $cardWonRepository,
        private CategoryRepository $categoryRepository,
        private CardApocalypseRepository $cardApoRepository,
    ) {
    }

    private function lostPointsByTypePoints(array $rules, int &$points, Game $game): void
    {
        if (!$rules['exception']) {
            $points -= $rules['value'];
        } else {
            if (!$this->cardWonRepository->findBy(['id' => $rules['exception'], 'game' => $game])) {
                $points -= $rules['value'];
            }
        }
    }

    public function lostPoints(CardApocalypse $cardApo, Game $game): void
    {
        //Récupère le score en session
        $points = $game->getScore();
        $rules = $cardApo->getRule();
        //Retrait des points
        if ($rules['type'] === 'points') {
            // S'il n'y pas d'exception ex:(carte banquier)
            $this->lostPointsByTypePoints($rules, $points, $game);
        } else {
            //Retrait des cartes
            if ($rules['category'] === 'artisan' || $rules['category'] === 'marchand') {
                $category = $this->categoryRepository->findBy(['name' => $rules['category']])[0];
                // dd($category);
            } else {
                $categories = ['Artisan', 'Marchand'];
                $category = $this->categoryRepository->findBy(['name' => $categories[array_rand($categories)]])[0];
            }
            //Retire les dernières cartes gagnées selon la catégorie
            $removedCard = $this->cardWonRepository->withdrawTheLastCards($rules['value'], $category, $game);
            foreach ($removedCard as $key => $card) {
                if ($card->getCard()->getRule()['association']) {
                    //Retire les points lié à l'association de carte si le joueur la possède
                    if (
                        $this->cardWonRepository->find(
                            $card->getCard()->getRule()['association']
                        ) || in_array(
                            $card,
                            $removedCard
                        )
                    ) {
                        unset($removedCard[$key]);
                        $points -= $card->getCard()->getCredit();
                    }
                }
                //Si la famille est complète, on retire les points
                $this->checkingFamily('-', $points, $card->getCard(), $game);
                //Puis on retire la carte de la table cardWon
                $this->cardWonRepository->remove($card, true);
                $points -= 10;
            }
        }
        $game->setScore($points);
        $this->gameRepository->add($game, true);
        $this->requestStack->getSession()->set('game', $game);
    }

    private function getCardApoWhenConstraint(Card $card): ?CardApocalypse
    {
        $cardApo = $this->cardApoRepository->selectRandom();
        if ($card->getName() == 'Le boucher' || $card->getName() == 'Le poissonnier') {
            $cardApo = $this->cardApoRepository->findBy(['name' => "Les ordures s'amoncellent"])[0];
        } elseif ($card->getName() == 'Le tanneur') {
            $cardApo = $this->cardApoRepository->selectRandomByName("Épidémie de peste");
        }
        return $cardApo;
    }

    public function pointsWon(array $cards, Game $game): void
    {
        //Récupère le score en session
        $points = $game->getScore();

        foreach ($cards as $card) {
            $cardWon = new CardWon();
            $cardWon->setCard($card);
            $cardWon->setGame($game);
            $rules = $card->getRule();
            //S'il y a une association
            if (!empty($rules['association'])) {
                if ($this->cardWonRepository->findBy(['card' => $rules['association'], 'game' => $game])) {
                    $points += $card->getCredit();
                }
            }
            //S'il y a une contrainte
            if (!empty($rules['constraint'])) {
                $cardApo = $this->getCardApoWhenConstraint($card);
                $points += $card->getCredit();
                $this->lostPoints($cardApo, $game);
            }
            //S'il n'y a pas de contrainte et pas d'association
            if (empty($rules['association']) && empty($rules['constraint'])) {
                $points += $card->getCredit();
            }
            //Vérifie les familles
            $this->checkingFamily('+', $points, $card, $game);
            //Ajoute la carte à la table cardWon
            $this->cardWonRepository->add($cardWon, true);
            //Ajoute 10 pts par carte gagnée
            $points += 10;
        }
        //Mise à jour du score en BDD et en session
        $game->setScore($points);
        $this->gameRepository->add($game, true);
        $this->requestStack->getSession()->set('game', $game);
    }

    private function checkingFamily(string $operator, int &$points, Card $card, Game $game): void
    {
        if ($operator == '+') {
            $families = $this->cardWonRepository->findByFamily($card->getFamily(), $game);
            if (count($families) == 4) {
                $points += 10;
            } else {
                $families = $this->cardWonRepository->findByFamily($card->getFamily(), $game);
                if (count($families) == 4) {
                    $points -= 10;
                }
            }
        }
    }
}
