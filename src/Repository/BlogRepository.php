<?php

namespace App\Repository;

use App\Entity\Blog;
use ContainerC0pytlH\getBlogRepositoryService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Blog|null find($id, $lockMode = null, $lockVersion = null)
 * @method Blog|null findOneBy(array $criteria, array $orderBy = null)
 * @method Blog[]    findAll()
 * @method Blog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BlogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Blog::class);
    }

    public function searchForKeyWords($words){
        $blogIds = [];
        $conn = $this->getEntityManager()
            ->getConnection();
        $sql = "SELECT DISTINCT id FROM `blog` where content like :words order by publish_date desc";
        $stmt = $conn->prepare($sql);
        $stmt->execute(array('words' => "%".$words."%"));
        $result = $stmt->fetchAll();
        if(!empty($result)){
            foreach ($result as $id){
                array_push($blogIds,$id['id']);
            }
        }
        return $this->findBy(['id' => $blogIds],["publishDate" => "desc"]);
    }



    /*
    public function findOneBySomeField($value): ?Blog
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
