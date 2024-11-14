<?php 

namespace App\EventListener;

use App\Entity\Vehicule;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;

#[AsEntityListener(event: Events::preUpdate, method: 'preUpdate', entity: Vehicule::class)]
class VehiculeListener
{
    public function preUpdate(Vehicule $vehicule, PreUpdateEventArgs $event): void
    {
        $vehicule->setModifiedAt();
    }

}