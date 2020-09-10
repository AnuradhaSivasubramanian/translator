<?php

namespace App\Repository;

use App\Entity\TranslationKey;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TranslationKey|null find($id, $lockMode = null, $lockVersion = null)
 * @method TranslationKey|null findOneBy(array $criteria, array $orderBy = null)
 * @method TranslationKey[]    findAll()
 * @method TranslationKey[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TranslationKeyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TranslationKey::class);
    }


    /**
     * @param string $value
     * @return array|null
     */
    public function findKeysByValue(string $value): ?array
    {

        return $this->createQueryBuilder('k')
            ->leftJoin('k.translationMessages', 'messages')
            ->where('k.text_key LIKE :value or messages.message LIKE :value ')
            ->setParameter('value', '%' . $value . '%')
            ->getQuery()->getResult();
    }

    /**
     * @param string $value
     * @return array|null
     */
    public function findKeysInADomain(string $value): ?array
    {

        return $this->createQueryBuilder('k')
            ->join('k.domains', 'domains')
            ->where('domains.domain_name = :value ')
            ->setParameter('value',$value )
            ->getQuery()->getResult();
    }

    /**
     * @param string $value
     * @param string $domain
     * @return int|mixed|string
     */
    public function FindKeyByValueInADomain(string $value, string $domain): ?array
    {
        return $this->createQueryBuilder('k')
            ->leftJoin('k.translationMessages', 'messages')
            ->join('k.domains', 'domains')
            ->where('(k.text_key LIKE :value or messages.message LIKE :value) and domains.domain_name = :domain ')
            ->setParameter('value',  '%' . $value . '%')
            ->setParameter('domain', $domain)
            ->getQuery()->getResult();
    }

    /**
     * @param string $domain
     * @return array|null
     */
    public function FindKeysInADomainWithEmptyMessages(string $domain): ?array
    {
        return $this->createQueryBuilder('k')
            ->leftJoin('k.translationMessages', 'messages')
            ->join('k.domains', 'domains')
            ->where(' messages.message = :value and domains.domain_name = :domain ')
            ->setParameter('value',  '')
            ->setParameter('domain', $domain)
            ->getQuery()->getResult();
    }

    /**
     * @return array|null
     */
    public function FindKeysWithEmptyMessages(): ?array
    {
        return $this->createQueryBuilder('k')
            ->leftJoin('k.translationMessages', 'messages')
            ->where(' messages.message = :value ')
            ->setParameter('value',  '')
            ->getQuery()->getResult();
    }







    // /**
    //  * @return TranslationKey[] Returns an array of TranslationKey objects
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
    public function findOneBySomeField($value): ?TranslationKey
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
