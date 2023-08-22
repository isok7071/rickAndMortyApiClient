<?php

namespace App\Backend\Controllers;

use App\Backend\Helpers\LogHelper;
use App\Backend\Models\RickAndMortyApi;


/**
 * Контроллер для обработки запросов на API
 */
class ApiController
{

    public function __construct(
        private RickAndMortyApi $api = new RickAndMortyApi())
    {
    }

    public function __call($name, $arguments)
    {
        throw new \Exception('Method doesen`t exist', 400);
    }

    /**
     * Возвращает локации по id
     *
     * @param array $params массив параметров
     * @return array|null
     */
    public function getLocationsByIds(array|int|string $params): ?array
    {
        if (isset($params['id'])) {
            try {
                $locations = $this->api->getLocationsByIds($params['id']);
                return $locations;
            } catch (\Exception $e) {
                $logger = new LogHelper();
                $logger->log->error('An error occurred: ' . $e->getMessage());
                return null;
            }
        }
        return null;
    }

    /**
     * Возвращает количество персонажей на локации по её id
     *
     * @param array $params массив параметров
     * @return array|null
     */
    public function getLocationCharactersCount(array|int|string $params): ?array
    {
        if (isset($params['id']) && !is_array($params['id'])) {
            try {
                $count = $this->api->getLocationCharactersCount($params['id']);
                return $count;
            } catch (\Exception $e) {
                $logger = new LogHelper();
                $logger->log->error('An error occurred: ' . $e->getMessage());
                return null;
            }
        }
        return null;
    }

    /**
     * Возвращает эпизод по id
     *
     * @param array $params массив параметров
     * @return array|null
     */
    public function getEpisodeById(array|int|string $params): ?array
    {
        if (isset($params['id']) && !is_array($params['id'])) {
            try {
                $episode = $this->api->getEpisodeById($params['id']);
                return $episode;
            } catch (\Exception $e) {
                $logger = new LogHelper();
                $logger->log->error('An error occurred: ' . $e->getMessage());
                return null;
            }
        }
        return null;
    }

    /**
     * Возвращает массив персонажей по id эпизода
     *
     * @param array $params массив с параметрами
     * @return array|null
     */
    public function getCharactersByEpisodeId(array|int|string $params): ?array
    {
        if (isset($params['id']) && !is_array($params['id'])) {
            try {
                $episode = $this->api->getCharactersByEpisodeId($params['id']);
                return $episode;
            } catch (\Exception $e) {
                $logger = new LogHelper();
                $logger->log->error('An error occurred: ' . $e->getMessage());
                return null;
            }
        }
        return null;
    }

}