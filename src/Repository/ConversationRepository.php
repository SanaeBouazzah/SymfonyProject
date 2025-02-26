<?php

namespace App\Repository;

use App\Entity\Conversation;
use App\Entity\User;
use App\Controller\ConversationController;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Conversation>
 */
class ConversationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Conversation::class);
    }

    public function findByUsers(User $sender, User $recipient): ?Conversation
    {
           return $this->createQueryBuilder('c')
               ->Where(':sender MEMBER OF c.user')
               ->andWhere(':recipient MEMBER OF c.user')
               ->setParameter('sender', $sender)
               ->setParameter('recipient', $recipient)
               ->getQuery()
               ->getOneOrNullResult();
    }

    //    /**
    //     * @return Conversation[] Returns an array of Conversation objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

       public function save(Conversation $conversation): void
       {
           $this->getEntityManager()->persist($conversation);
           $this->getEntityManager()->flush();
       }
}
