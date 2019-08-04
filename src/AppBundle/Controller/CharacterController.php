<?php

namespace AppBundle\Controller;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CharacterController extends Controller
{

    const BASE_URL = 'https://gateway.marvel.com:443/';
    const PUBLIC_KEY = '4f4eec12283e8b1b442220dce9e38ac2';
    const PRIVATE_KEY = 'cf303a1a252223ba32b9ca92d7cfc9e643d94a0d';
    const FIRST_OFFSET = 100;
    const CHARACTERS_PER_PAGE = 4;
    const NUMBER_OF_CHARACTERS_TO_GET = 20;

    /**
     * @var array
     */
    private $query_params;

    /**
     * @Route("/", defaults={"page"=1})
     * @Route("/list/{page}", defaults={"page"=1}, name="homepage")
     */
    public function indexAction($page)
    {
        $error = null;
        $heroes = array();
        $maxPages = ceil(self::NUMBER_OF_CHARACTERS_TO_GET / self::CHARACTERS_PER_PAGE);
        try{
        $client = $this->initClient();
        $offset = $page == 1 ? self::FIRST_OFFSET : self::FIRST_OFFSET + ($page-1) * self::CHARACTERS_PER_PAGE;

        $limit = self::CHARACTERS_PER_PAGE;
        // Pour ne pas dépasser 20 prsonnages en total pendant la pagination
        if($offset + self::CHARACTERS_PER_PAGE > self::FIRST_OFFSET + self::NUMBER_OF_CHARACTERS_TO_GET){
            $difference = ($offset + self::CHARACTERS_PER_PAGE) - (self::FIRST_OFFSET + self::NUMBER_OF_CHARACTERS_TO_GET) ;
            $limit = self::CHARACTERS_PER_PAGE - $difference;
        }

        $params = [
            'offset' => $offset,
            'limit' => $limit
        ];

        $this->query_params = array_merge($params,$this->query_params);

        $query = http_build_query($this->query_params);

        $response = $client->request('GET', 'v1/public/characters?'.$query);
        $results = json_decode($response->getBody());
        $heroes = $results->data->results;

        }catch (GuzzleException $e) {
            $error = $e->getMessage();
        }

        return $this->render('default/index.html.twig', [
            'page' => $page,
            'maxPages' => $maxPages,
            'error' => $error,
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
        $error = null;
        $hero = null;
        try{
            $client = $this->initClient();

            $query = http_build_query($this->query_params);

            $response = $client->request('GET', 'v1/public/characters/'. $id . '?' .$query);
            $result = json_decode($response->getBody());
            $hero = $result->data->results[0];

        }catch (GuzzleException $e) {
            $error = $e->getMessage();
        }

        return $this->render('default/character.html.twig', [
            'error' => $error,
            'hero' => $hero
        ]);
    }

    /**
     * @return Client
     */
    public function initClient(){
        $client = new Client(['base_uri' => self::BASE_URL]);

        $ts = time();
        $public_key = self::PUBLIC_KEY;
        $private_key = self::PRIVATE_KEY;
        $hash = md5($ts . $private_key . $public_key);

        $this->query_params = [
            'apikey' => $public_key,
            'ts' => $ts,
            'hash' => $hash
        ];

        return $client;
    }
}
