<?php


namespace AppBundle\Consumer;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use stdClass;

class MarvelConsumer
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
    * @var Client
     */
    private $client;

    /**
     * @var string
     */
    private $error;

    /**
     * @var string
     */
    private $detailsError;


    public function __construct(){
        $this->client = new Client(['base_uri' => self::BASE_URL]);

        $ts = time();
        $public_key = self::PUBLIC_KEY;
        $private_key = self::PRIVATE_KEY;
        $hash = md5($ts . $private_key . $public_key);

        $this->query_params = [
            'apikey' => $public_key,
            'ts' => $ts,
            'hash' => $hash
        ];
    }


    /**
     * @return float
     */
    public function getMaxPages(){
        return ceil(self::NUMBER_OF_CHARACTERS_TO_GET / self::CHARACTERS_PER_PAGE);
    }

    /**
     * @param $page
     * @return stdClass|null
     */
    public function getCharacterList($page){
        try{
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

            $response = $this->client->request('GET', 'v1/public/characters?'.$query);
            $results = json_decode($response->getBody());
            return $results->data->results;

        }catch (GuzzleException $e) {
            $this->error = $e->getMessage();
            return null;
        }
    }

    /**
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

    public function getCharacterDetails($id)
    {
        try{
            $query = http_build_query($this->query_params);

            $response = $this->client->request('GET', 'v1/public/characters/'. $id . '?' .$query);
            $result = json_decode($response->getBody());
            return $result->data->results[0];

        }catch (GuzzleException $e) {
            $this->detailsError = $e->getMessage();
            return null;
        }
    }

    /**
     * @return string
     */
    public function getDetailsError()
    {
        return $this->detailsError;
    }

}