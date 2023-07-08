<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Article>
 *
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    public function save(Article $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Article $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function search(string $filter): array
    {

        if ($filter === '*') {
            return $this->findAll();
        }

        return $this->createQueryBuilder('u')
            ->where('u.title LIKE :search')
            ->orWhere('u.content LIKE :search')
            ->setParameter('search', '%' . $filter . '%')
            ->getQuery()
            ->getResult();
    }

    public function addCategoriesToProduct(Article $article, array $categories): void
    {
        foreach ($categories as $category) {
            // Vérifie si la catégorie n'a pas déjà été ajoutée au produit
            if (!$article->getCategories()->contains($category)) {
                // Ajoute la catégorie au produit
                $article->addCategory($category);
            }
        }

        // Enregistre les modifications dans la base de données
        $this->getEntityManager()->flush();
    }

    public function removeCategoriesFromArticle(Article $article): void
    {
        // Supprime toutes les catégories associées à l'article
        $article->getCategories()->clear();

        // Enregistre les modifications dans la base de données
        $this->getEntityManager()->flush();
    }

//    /**
//     * @return Article[] Returns an array of Article objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Article
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
