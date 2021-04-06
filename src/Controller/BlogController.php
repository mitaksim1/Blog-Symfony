<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use App\Entity\Article;
use App\Repository\ArticleRepository;

class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="blog")
     */
    public function index(ArticleRepository $repo): Response
    {
        $articles = $repo->findAll();

        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
            'articles' => $articles
        ]);
    }

    /**
     * @Route("/", name="home")
     */
    public function home()
    {
       
        return $this->render('blog/home.html.twig', [
            'title' => "Bienvenue ici les amis !",
            'age' => 31
        ]);
    }
    
    // 3 pilliers pour une page : une fonction, une route, une réponse (affichage/redirection)
    /**
     * @Route("/blog/new", name="blog_create")
     */
    public function create(Request $request) 
    {
        // Avant de créer le form, on instancie la classe Article
        $article = new Article();

        // Pour créer un form on utilise la fonction createFormBuilder de Symfony qui demande comme paramètre juste l'entité à laquelle on fait référence
        // $form est un objet
        $form = $this->createFormBuilder($article)
        // Pour que ça marche, on doit lui préciser mes champs qu'il doit traiter
        // attr : tableau d'attribut html que je veux passer
                    ->add('title', TextType::class, [
                        'attr' => [
                            'placeholder' => "Titre de l'article"
                        ]
                    ])
                    // Si je veux, je peut préciser le type de l'input que je veux
                    ->add('content', TextareaType::class, [
                        'attr' => [
                            'placeholder' => "Contenu de l'article"
                        ]
                    ])
                    ->add('image', TextType::class, [
                        'attr' => [
                            'placeholder' => "Image de l'article"
                        ]
                    ])
                    ->getForm(); // après avoir renseigné les champs à construire, on lui demande de créer le form avec cette méthode

        // On passe à Twig non pas l'objet $form entier, mais juste une de ses méthodes (createView()) qui va s'occuper d'afficher les données du form
        return $this->render('blog/create.html.twig', [
            // pour éviter toute ambigüite avec le form() de Twig on va nommer la variable formArticle
            'formArticle' => $form->createView()
        ]);
    }

    /**
     *@route("/blog/{id}", name="blog_show")
     */
    public function show(Article $article)
    {

        return $this->render('blog/show.html.twig', [
            'article' => $article
        ]);
    }

}

// Injection de dépendances : Symfony peut nous fournir les éléments dont on a besoin
// HttpFoundationRequest : c'est la classe qui permet d'analyser / manipuler la requête HTTP (Request $request)
// ParameterBag : Un objet qui renferme les données passées par le POST / GET, si requête en POST ragrader dans l'onglet request, si en GET regarder
// redirectToRoute : fonction qui permet de créer une redirection vers une autre route
// Twig: fonction form() : permet de'afficher simplement un formulaire Symfony
