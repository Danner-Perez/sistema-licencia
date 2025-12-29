<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ReniecService
{
    protected string $token;

    public function __construct()
    {
        $this->token = config('services.reniec.token');
    }

    public function consultarDni(string $dni): ?array
    {
        $response = Http::withHeaders([
            'Referer' => 'https://apis.net.pe/consulta-dni-api',
            'Authorization' => 'Bearer ' . $this->token,
        ])
        ->timeout(10)
        ->get('https://api.apis.net.pe/v2/reniec/dni', [
            'numero' => $dni
        ]);

        if (!$response->successful()) {
            return null;
        }

        $data = $response->json();

        if (
            isset($data['nombres'],
                  $data['apellidoPaterno'],
                  $data['apellidoMaterno'])
        ) {
            return [
                'nombres'   => $data['nombres'],
                'apellidos' => $data['apellidoPaterno'] . ' ' . $data['apellidoMaterno'],
            ];
        }

        return null;
    }
}
