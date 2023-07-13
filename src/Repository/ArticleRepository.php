<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Article>
 *
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method Article|null findOneBySlug($slug)
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

    public function search(string $filter, string $orderBy = 'ASC'): Query
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.createdAt', $orderBy)
            ->where('a.title LIKE :search')
            ->orWhere('a.content LIKE :search')
            ->orWhere('a.headingContent LIKE :search')
            ->setParameter('search', '%' . $filter . '%')
            ->getQuery();
    }

    public function getArticlesWithTags(string $orderBy = 'ASC'): Query
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.createdAt', $orderBy)
            ->leftJoin('a.tags', 't')
            ->addSelect('t')
            ->getQuery();
    }

    public function getArticlesByTag(string $slug, string $orderBy = 'ASC'): Query
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.createdAt', $orderBy)
            ->leftJoin('a.tags', 't')
            ->where('t.slug = :slug')
            ->setParameter('slug', $slug)
            ->getQuery();
    }
}
