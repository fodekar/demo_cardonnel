<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User as UserEntity;

class User
{
    private $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    public function create($username)
    {
        $output = null;

        if ($username) {
            $user = $this->getRepository()->findOneByUsername($username);

            if (!$user) {
                $user = new UserEntity();
                $user->setUsername($username);

                $this->em->persist($user);
                $this->em->flush();
            }

            $output = $user;
        }

        return $output;
    }

    protected function getRepository()
    {
        return $this->em->getRepository(UserEntity::class);
    }
}
