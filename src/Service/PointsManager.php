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

    public function calcuatePoints(int $points): void
    {
        $session = $this->requestStack->getSession();
        $session->set('points', $session->get('points') + $points);
    }
}
