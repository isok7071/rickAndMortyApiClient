<?php

namespace App\Backend\Models;

use GuzzleHttp\Client;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Request;

/**
 * Модель для получения данных с api rickandmortyapi
 */
class RickAndMortyApi
{
    private $client;
    private const BASE_URI = 'https://rickandmortyapi.com/api/';
    private const LOCATION_URI = 'location/';
    private const EPISODE_URI = 'episode/';
    private const TIMEOUT = 5;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => self::BASE_URI,
            'timeout' => self::TIMEOUT,
        ]);
    }

    /**
     * Возвращает массив локаций по id
     *
     * @param integer|array $locationIds может быть как массивом, так и int
     * @return array
     */
    public function getLocationsByIds(int|array $locationIds): array
    {
        $responses = [];
        if (is_array($locationIds)) {
            $requests = [];
            foreach ($locationIds as $locationId) {
                $requests[] = new Request('GET', self::LOCATION_URI . $locationId);
            }

            $responses = Pool::batch(
                $this->client,
                $requests,
                [
                    'concurrency' => 15,
                ]
            );

            $result = [];

            foreach ($responses as $response) {
                $responseData = json_decode($response->getBody(), true);
                $result[] = [
                    'id' => $responseData['id'],
                    'name' => $responseData['name'],
                    'type' => $responseData['type'],
                    'dimension' => $responseData['dimension'],
                    'residents' => $responseData['residents'],
                ];
            }
            return $result;
        } else {
            $response = $this->client->get(self::LOCATION_URI . $locationIds);
            $responseData = json_decode($response->getBody(), true);
            $responses = [
                'id' => $responseData['id'],
                'name' => $responseData['name'],
                'type' => $responseData['type'],
                'dimension' => $responseData['dimension'],
                'residents' => $responseData['residents'],
            ];
        }
        return $responses;
    }


    /**
     * Возвращает массив с количеством персонажей на локации 
     *
     * @param integer $id id локации
     * @return array
     */
    public function getLocationCharactersCount(int $id): array
    {
        $locationCharacters = $this->getLocationsByIds($id)['residents'];
        return ['count' => count($locationCharacters)];
    }

    /**
     * Возвращает информацию об эпизоде по ID
     *
     * @param integer $id id эпизода
     * @return array
     */
    public function getEpisodeById(int $id): array
    {
        $response = $this->client->get(self::EPISODE_URI . $id);
        $responseData = json_decode($response->getBody(), true);
        $responses = [];
        $responses = [
            'id' => $responseData['id'],
            'name' => $responseData['name'],
            'characters' => $responseData['characters'],
        ];
        return $responses;
    }


    /**
     * Возвращает массив с информацией о персонажах в эпизоде по его id
     *
     * @param integer $id id эпизода
     * @return array
     */
    public function getCharactersByEpisodeId(int $id): array
    {
        $episodeCharacters = $this->getEpisodeById($id)['characters'];
        $requests = [];

        foreach ($episodeCharacters as $link) {
            $requests[] = new Request('GET', $link);
        }

        $responses = Pool::batch(
            $this->client,
            $requests,
            [
                'concurrency' => 15,
            ]
        );

        $result = [];

        foreach ($responses as $response) {
            $result[] = json_decode($response->getBody(), true);
        }
        return $result;

    }
}