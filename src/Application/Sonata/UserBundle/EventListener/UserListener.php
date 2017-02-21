<?php

namespace Application\Sonata\UserBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Mapping as ORM;

use Application\Sonata\UserBundle\Entity\User;

class UserListener
{

    public function prePersist(LifecycleEventArgs $args)
    {
        // $entity = $args->getObject();
        // $entityManager = $args->getObjectManager();

        // // perhaps you only want to act on some "Product" entity
        // if ($entity instanceof User) {
        //     // do something with the Product
        //     $this->handleEvent($entity, $entityManager);
        // }
    }

    /**
     * @ORM\PreUpdate
     */
    public function preUpdate(User $user, PreUpdateEventArgs $args)
    {

        // $entity = $args->getEntity();
        // $entityManager = $args->getObjectManager();

        // dump($entity->getQuestions(), $args->getNewValue('questions'));die();
        // if ($args->hasChangedField( 'questions' )) {
        //     dump($entity->getQuestions(), $args->getNewValue('questions'));die();
        // }

    }

    public function handleEvent($user, $em) {
        dump($user);
        die('Handling!');
    }

}

