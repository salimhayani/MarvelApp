<?php

namespace AppBundle\Controller;

use AppBundle\Consumer\MarvelConsumer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CharacterController extends Controller
{

    /**
     * @Route("/", defaults={"page"=1})
     * @Route("/list/{page}", defaults={"page"=1}, name="homepage")
     */
    public function indexAction($page)
    {
        $marvelConsumer = new MarvelConsumer();
        $heroes = $marvelConsumer->getCharacterList($page);

        return $this->render('default/index.html.twig', [
            'page' => $page,
            'maxPages' => $marvelConsumer->getMaxPages(),
            'error' => $marvelConsumer->getError(),
            'heroes' => $heroes,
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }

    /**
     * @Route("/heroDetails/{id}", name="heroDetails", requirements={"page"="\d+"})
     * @param $id
     * @return Response
     */
    public function detailsAction($id)
    {
        $marvelConsumer = new MarvelConsumer();
        $character = $marvelConsumer->getCharacterDetails($id);

        return $this->render('default/character.html.twig', [
            'error' => $marvelConsumer->getDetailsError(),
            'hero' => $character
        ]);
    }

}
