<?php

namespace App\Repository;

use App\Entity\TranslationMessage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TranslationMessage|null find($id, $lockMode = null, $lockVersion = null)
 * @method TranslationMessage|null findOneBy(array $criteria, array $orderBy = null)
 * @method TranslationMessage[]    findAll()
 * @method TranslationMessage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TranslationMessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TranslationMessage::class);
    }


    /**
     * @param string $value
     * @return array|null
     */
    public function findMessageByText(string $value): ?array
    {



        $entityManager = $this->getEntityManager();

        return $entityManager->createQuery('SELECT m FROM App\Entity\TranslationMessage m
        WHERE m.message LIKE :value
        ')
            ->setParameter('value', '%'.$value.'%')
            ->execute();
    }
    // /**
    //  * @return TranslationMessage[] Returns an array of TranslationMessage objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TranslationMessage
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
