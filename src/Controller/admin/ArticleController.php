<?php

namespace App\Controller\admin;

use App\Entity\Article;
use App\Entity\Tag;
use App\Enums\FlashTypeEnum;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use App\Repository\TagRepository;
use Knp\Component\Pager\PaginatorInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/article')]
class ArticleController extends AbstractController
{
    public const MAX_PER_PAGE = 10;

    #[Route('/', name: 'admin_article_index', methods: ['GET'])]
    public function index(
        Request            $request,
        ArticleRepository  $articleRepository,
        PaginatorInterface $paginator
    ): Response
    {
        $paginatedArticles = $paginator->paginate(
            $articleRepository->getArticlesWithTags('DESC'),
            $request->query->getInt('page', 1),
            self::MAX_PER_PAGE
        );

        return $this->render('admin/article/index.html.twig', [
            'articles' => $paginatedArticles
        ]);
    }

    #[Route('/new', name: 'admin_article_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ArticleRepository $articleRepository, TagRepository $tagRepository): Response
    {
        $article = new Article();

        $form = $this->createForm(ArticleType::class, $article);
        $form->add('saveAndAdd', SubmitType::class, ['label' => 'Save & add more']);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /**  @var Tag[] $newTags */
            $newTags = $form->get('newTag')->getData();

            foreach ($newTags as $newTag) {
                $existingTag = $tagRepository->findOneBy(['name' => $newTag->getName()]);

                if ($existingTag) {
                    $this->addFlash(FlashTypeEnum::ERROR->name, sprintf('Tag %s already exists', $newTag->getName()));
                } else {
                    $article->addTag($newTag);
                }
            }

            $article->setUid(Uuid::uuid7()->toString());
            $articleRepository->save($article, true);

            $nextAction = $form->get('saveAndAdd')->isClicked()
                ? 'admin_article_new'
                : 'admin_article_index';

            return $this->redirectToRoute($nextAction);
        }

        return $this->render('admin/article/new.html.twig', [
            'article' => $article,
            'form'    => $form,
        ]);
    }

    #[Route('/{slug}', name: 'admin_article_show', methods: ['GET'])]
    public function show(string $slug, ArticleRepository $articleRepository): Response
    {
        return $this->render('admin/article/show.html.twig', [
            'article' => $articleRepository->findOneBySlug($slug)
        ]);
    }

    #[Route('/{uid}/edit', name: 'admin_article_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Article $article, ArticleRepository $articleRepository, TagRepository $tagRepository): Response
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /**  @var Tag[] $newTags */
            $newTags = $form->get('newTag')->getData();

            foreach ($newTags as $newTag) {
                $existingTag = $tagRepository->findOneBy(['name' => $newTag->getName()]);

                if ($existingTag) {
                    $this->addFlash(FlashTypeEnum::ERROR->name, sprintf('Tag %s already exists', $newTag->getName()));
                } else {
                    $article->addTag($newTag);
                }
            }

            $articleRepository->save($article, true);

            return $this->redirectToRoute('admin_article_edit', ['uid' => $article->getUid()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/article/edit.html.twig', [
            'article' => $article,
            'form'    => $form,
        ]);
    }

    #[Route('/{uid}/delete', name: 'admin_article_delete', methods: ['POST'])]
    public function delete(Request $request, Article $article, ArticleRepository $articleRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $article->getUid(), (string)$request->request->get('_token'))) {
            $articleRepository->remove($article, true);
        }

        return $this->redirectToRoute('admin_article_index', [], Response::HTTP_SEE_OTHER);
    }
}
