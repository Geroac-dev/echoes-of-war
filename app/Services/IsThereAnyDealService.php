<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class IsThereAnyDealService
{
    protected $base = 'https://api.isthereanydeal.com/v01/';
    protected $key;

    public function __construct()
    {
        $this->key = config('services.itad.key');
    }

    private function request($endpoint, $params = [])
    {
        return Http::get($this->base . $endpoint, array_merge([
            'key' => $this->key
        ], $params))->json();
    }

    // Obtener juegos del usuario (poseídos y wishlist)
    public function getUserGames($steamId)
    {
        return $this->request('user/info/', [
            'shops' => 'steam',
            'id' => $steamId
        ]);
    }

    // Buscar juego
    public function search($query)
    {
        return $this->request('search/search/', [
            'q' => $query,
        ]);
    }

    // Precios y ofertas actuales
    public function getPrices($plain)
    {
        return $this->request('game/prices/', [
            'plains' => $plain,
            'country' => 'MX',
        ]);
    }

    // Historial de precios
    public function getHistory($plain)
    {
        return $this->request('game/history/', [
            'plains' => $plain,
            'country' => 'MX',
        ]);
    }
}
