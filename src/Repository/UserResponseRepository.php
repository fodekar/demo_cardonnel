<?php

namespace App\Repository;

use App\Entity\UserResponse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserResponse>
 *
 * @method UserResponse|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserResponse|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserResponse[]    findAll()
 * @method UserResponse[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserResponseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserResponse::class);
    }

    public function add(UserResponse $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(UserResponse $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAllByQuestion($uuid)
    {
        $sql = "SELECT 
                    ur.content,
                    ur.created_at,
                    u.username
                FROM user_response ur 
                    LEFT JOIN question q ON (q.id = ur.question_id)
                    LEFT JOIN user u ON (u.id = ur.user_id)
                WHERE 
                    q.uuid = '$uuid'";

        $em = $this->getEntityManager()->getConnection();
        $statement = $em->query($sql);

        return $statement->fetchAll();
    }
}
