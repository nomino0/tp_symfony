<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Form\ArticleType;

class IndexController extends AbstractController
{
    #[Route('/', 'acticle_list')]
    public function home(EntityManagerInterface $em)
    {
        $articles = $em->getRepository(Article::class)->findAll();
        return $this->render('articles/index.html.twig', ['articles' => $articles]);
    }

    #[Route('/article/new', name: 'new_article', methods: ["GET", "POST"])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article = $form->getData();

            $em->getRepository(Article::class);
            $em->persist($article);
            $em->flush();

            return $this->redirectToRoute('acticle_list');
        }

        return $this->render('articles/new.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/article/save', 'addArticle')]
    public function save(EntityManagerInterface $em): Response
    {
        $article = new Article();
        $article->setNom('Article 1');
        $article->setPrix(1000);

        $em->getRepository(Article::class);
        $em->persist($article);
        $em->flush();

        return new Response('Article enregisté avec id' . $article->getId());
    }

    #[Route('/article/{id}', 'article_show')]
    public function show($id, EntityManagerInterface $em)
    {
        $article = $em->getRepository(Article::class)->find($id);
        return $this->render('articles/show.html.twig', array('article' => $article));
    }

    #[Route('/article/edit/{id}', name: 'edit_article', methods: ["GET", "POST"])]
    public function edit(Request $request, $id, EntityManagerInterface $em): Response
    {
        $article = new Article();
        $article = $em->getRepository(Article::class)->find($id);
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isvalid()) {
            $em->flush();
            return $this->redirectToRoute('acticle_list');
        }
        return $this->render('articles/edit.html.twig', ['form' => $form->createView()]);
    }


    #[Route('/article/delete/{id}', 'delete_article', methods: ["GET"])]
    public function delete(Request $request, $id, EntityManagerInterface $em): Response
    {
        $article = $em->getRepository(Article::class)->find($id);
        $em->remove($article);
        $em->flush();
        $response = new Response();
        $response->send();
        return $this->redirectToRoute('acticle_list');
    }


    #[Route('/accueuil', 'homepage1')]

    public function home1(): Response
    {
        return $this->render('index.html.twig');
    }


    #[Route('/accueuil/{name}', name: 'homepage2')]

    public function home2($name): Response
    {
        return $this->render('index1.html.twig', ['prenom' => $name]);
    }
}
