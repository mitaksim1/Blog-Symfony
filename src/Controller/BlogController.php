<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\ArticleType;
use App\Form\CommentType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
            'title' => "Bienvenue ici les amis !"
        ]);
    }
    
    // 3 pilliers pour une page : une fonction, une route, une réponse (affichage/redirection)
    /**
     * @Route("/blog/new", name="blog_create")
     * @route("/blog/{id}/edit", name="blog_edit")
     */
    // paramConverter : convertit un paramètre en une entité $id = Article
    // Comme on a deux routes maintenant, il faut préciser que l'article peut être null (dans le cas où on est dans la route /blog/new)
    public function form(Article $article = null, Request $request, EntityManagerInterface $manager) 
    {
        // Si on a pas d'article, on veut la possibilité d'en créer un
        if (!$article) {
            $article = new Article();
        }
    
        // Pour créer un form on utilise la fonction createFormBuilder de Symfony qui demande comme paramètre juste l'entité à laquelle on fait référence
        // $form est un objet
        // $form = $this->createFormBuilder($article)
        // // Pour que ça marche, on doit lui préciser mes champs qu'il doit traiter
        // // attr : tableau d'attribut html que je veux passer
        //             ->add('title')
        //             // Si je veux, je peut préciser le type de l'input que je veux
        //             ->add('content')
        //             ->add('image')
        //             ->getForm(); // après avoir renseigné les champs à construire, on lui demande de créer le form avec cette méthode

        // Deuxième méthode pour la création d'un form grâce à make:form
        // Une fois que le form sera crée automaqtiquement par Symfony, on aura qu'à l'appeler comme suit
        // En premier paramètre on passe le fichier form et en deuxième paramètre l'entité auquel il est lié
        $form = $this->createForm(ArticleType::class, $article);

        // La méthode handleRequest contenue dans la classe Request va traiter les données reçues
        $form->handleRequest($request);

        // Une fois que l'on a vérifié que les données sont bien renseignées et valides, on peut les enregistrer
        if ($form->isSubmitted() && $form->isValid()) {
            // Je vérifie si l'article existe déjà (s'il a un id ça veut dire qu'il existe), si ce n'est pas le cas on ajoute la date de création
            if(!$article->getId()) {
                // Il nous faut la date de création de l'article, on peut le setter ici
                $article->setCreatedAt(new \DateTime());
            }
           

            // On a toutes les informations, on peut persister les données et les flush
            $manager->persist($article);
            $manager->flush();

            // Une fois que tout est fait, on sera redirigé vers la page de l'article crée.
            return $this->redirectToRoute('blog_show', ['id' => $article->getId() ]);
        }

        // On passe à Twig non pas l'objet $form entier, mais juste une de ses méthodes (createView()) qui va s'occuper d'afficher les données du form
        return $this->render('blog/create.html.twig', [
            // pour éviter toute ambigüite avec le form() de Twig on va nommer la variable formArticle
            'formArticle' => $form->createView(),
            // On a le même bouton si on est la page edit ou new pour changer ça
            'editMode' => $article->getId() !== null
        ]);
    }

    /**
     *@route("/blog/{id}", name="blog_show")
     */
    // Ne pas oublier que pour récupérer les informations d'un formulaire on a besoin de l'objet Request
    // On aura besoin de EntityManagerInterface $manager pour sauvegarder les données reçues dans la bdd
    public function show(Article $article, Request $request, EntityManagerInterface $manager)
    {
        // Pour pouvoir relier les commentaires avec le formulaire, je dois instancier la classe Comment
        $comment = new Comment();
        // Création du formulaire pour laisser un commentaire dans la page de l'article
        $form = $this->createForm(CommentType::class, $comment);

        // Récupérer les informations passés au formulaires grâce à l'objet Request
        $form->handleRequest($request);

        // Vérifier toujours si les données sont bien reçues et si elles sont valides
        if ($form->isSubmitted() && $form->isValid()) {
            // Avant que le commentaire soit sauvegardé dans la bdd, on veut setter sa date et l'article auquel il est relié
            $comment->setCreatedAt(new \DateTime())
                    ->setArticle($article);

            // Demander à $manager de les sauvegarder
            $manager->persist($comment);
            $manager->flush();

            // On redirige la page vers la page de l'article en question
            return $this->redirectToRoute('blog_show', ['id' => $article->getId()]);
        }
        
        return $this->render('blog/show.html.twig', [
            'article' => $article,
            'commentForm' => $form->createView()
        ]);
    }

}

// Injection de dépendances : Symfony peut nous fournir les éléments dont on a besoin
// HttpFoundationRequest : c'est la classe qui permet d'analyser / manipuler la requête HTTP (Request $request)
// ParameterBag : Un objet qui renferme les données passées par le POST / GET, si requête en POST ragrader dans l'onglet request, si en GET regarder
// redirectToRoute : fonction qui permet de créer une redirection vers une autre route
// Twig: fonction form() : permet de'afficher simplement un formulaire Symfony
// Twig : les templates : créer des templates de forms pour décider leur affichage
// Contraintes Symfony : nous permettent de soumettre des données à des contraintes
// Les scripts fixtures : permettent de remplir les tables avec des fausses données
