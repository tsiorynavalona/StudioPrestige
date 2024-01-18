<?php

namespace App\EventSubscriber;

use App\Repository\CategoriesPhotosRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Twig\Environment;

class CategoriesInTwigEventSubscriber implements EventSubscriberInterface
{

    private $twig;
    private $categories; 

    public function __construct(Environment $twig, CategoriesPhotosRepository $categories) {
        $this->twig = $twig;
        $this->categories = $categories;
    }

    public function onControllerEvent(ControllerEvent $event): void
    {
        $qb = $this->categories->createQueryBuilder('c')
                    ->andWhere('c.label != :val')
                    ->setParameter('val', 'photographe')
                    ->innerJoin('c.photos', 'p')
                    ->getQuery()
                    ->getResult();

        $this->twig->addGlobal('categoriesList', $qb);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ControllerEvent::class => 'onControllerEvent',
        ];
    }
}
