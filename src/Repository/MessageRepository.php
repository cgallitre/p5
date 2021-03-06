<?php

namespace App\Repository;

use App\Entity\Message;
use App\Entity\MessageSearch;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Message|null find($id, $lockMode = null, $lockVersion = null)
 * @method Message|null findOneBy(array $criteria, array $orderBy = null)
 * @method Message[]    findAll()
 * @method Message[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Message::class);
    }

    public function findAllQuery(MessageSearch $search)
    {
        $query = $this  ->createQueryBuilder('m')
                        ->join('m.project', 'p')
                        ->join('m.author', 'u')
                        ->orderBy('m.createdAt', 'DESC')
                        ->andWhere('p.finished = :finished')
                        ->setParameter('finished', false);

        if ($search->getKeyword())
        {
            $query = $query->andWhere('m.content LIKE :keyword OR m.title LIKE :keyword OR u.lastName LIKE :keyword')
                           ->setParameter('keyword', "%{$search->getKeyword()}%");
        }

        if ($search->getProject())
        {
            $query = $query->andWhere('m.project = :project')
                           ->setParameter('project', $search->getProject());
        }
        
        if ($search->getType())
        {
            $query = $query->andWhere('m.type = :type')
                           ->setParameter('type', $search->getType());
        }

        return $query->getQuery();
    }

    // /**
    //  * @return Message[] Returns an array of Message objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Message
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
