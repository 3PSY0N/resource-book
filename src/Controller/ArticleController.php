<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use DateInterval;
use Exception;
use Knp\Component\Pager\PaginatorInterface;
use Psr\Cache\InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/article')]
class ArticleController extends AbstractController
{
    public const MAX_PER_PAGE = 12;

    /**
     * @throws InvalidArgumentException
     */
    #[Route('/', name: 'app_article_index', methods: ['GET'])]
    public function index(Request $request, ArticleRepository $articleRepository, CategoryRepository $categoryRepository, PaginatorInterface $paginator): Response
    {
        $cache = new FilesystemAdapter();
        $cachedContent = $cache->getItem('article_list_adm');

        if (!$cachedContent->isHit()) {
            $articles = $articleRepository->findBy([], ['createdAt' => 'DESC']);
            $articlesData = [];

            foreach ($articles as $article) {
                $categories = $categoryRepository->findByArticle($article);
                $articlesData[] = [
                    'article' => $article,
                    'categories' => $categories,
                ];
            }

            $cachedContent->set($articlesData);

            $cacheTtl = new DateInterval('P1D');
            $cachedContent->expiresAfter($cacheTtl);
            $cache->save($cachedContent);
        }
        $articles = $cachedContent->get();

//        $articles = $articleRepository->findBy([], ['createdAt' => 'DESC']);

        $paginatedArticles = $paginator->paginate(
            $articles,
            $request->query->getInt('page', 1),
            self::MAX_PER_PAGE
        );

        return $this->render('article/index.html.twig', [
            'articles' => $paginatedArticles,
        ]);
    }

    #[Route('/new', name: 'app_article_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ArticleRepository $articleRepository, LoggerInterface $logger): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

//            dd(Uuid::uuid7()->toString());
            do {
                try {
                    $article->setUid(Uuid::uuid7()->toString());
                    $articleRepository->save($article, true);
                    break;
                } catch (Exception $e) {
                    $logger->error($e->getMessage());
                    continue;
                }
            } while (true);

            return $this->redirectToRoute('app_article_show', ['slug' => $article->getSlug()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('article/new.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    /**
     * @throws InvalidArgumentException
     */
    #[Route('/{slug}', name: 'app_article_show', methods: ['GET'])]
    public function show($slug, ArticleRepository $articleRepository, CategoryRepository $categoryRepository): Response
    {
        $cache = new FilesystemAdapter();
        $cachedContent = $cache->getItem('article_'.$slug);

        if (!$cachedContent->isHit()) {
            $article = $articleRepository->findOneBySlug($slug);
            $categories = $categoryRepository->findByArticle($article);

            $cachedContent->set([
                'article' => $article,
                'categories' => $categories,
            ]);

            $cacheTtl = new DateInterval('P1D');
            $cachedContent->expiresAfter($cacheTtl);
            $cache->save($cachedContent);
        }

        $article = $cachedContent->get();

        return $this->render('article/show.html.twig', [
            'article' => $article['article'],
            'categories' => $article['categories']
        ]);
    }

    #[Route('/{id}/edit', name: 'app_article_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Article $article, ArticleRepository $articleRepository, CategoryRepository $categoryRepository): Response
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $articleRepository->save($article, true);

            return $this->redirectToRoute('app_article_show', ['slug' => $article->getSlug()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('article/edit.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_article_delete', methods: ['POST'])]
    public function delete(Request $request, Article $article, ArticleRepository $articleRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $articleRepository->remove($article, true);
        }

        return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
    }

//    #[Route('/{id}/edit/category', name: 'app_article_edit_categories', methods: ['POST'])]
//    public function editCategories(Request $request, CategoryRepository $categoryRepository, Article $article, ArticleRepository $articleRepository): JsonResponse
//    {
//        $bodyRequest = $request->getContent();
//        $selectedItems = array_values(json_decode($bodyRequest, true)['selectedItems']);
//        $categoryItems = $categoryRepository->findBy(['name' => $selectedItems], ['name' => 'desc']);
//
//        $articleRepository->removeCategoriesFromArticle($article);
//        $articleRepository->addCategoriesToProduct($article, $categoryItems);
//        return $this->json($article, 200, [], ['groups' => 'post:article']);
//    }
}
