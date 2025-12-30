<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ReniecService
{
    protected string $token;

    public function __construct()
    {
        $this->token = config('services.reniec.token');
    }

    public function consultarDni(string $dni): ?array
    {
        try {
            $response = Http::withHeaders([
                'Referer' => 'https://apis.net.pe/consulta-dni-api',
                'Authorization' => 'Bearer ' . $this->token,
            ])
            ->timeout(5)              // ⏱ evita cuelgues
            ->retry(2, 300)           // 🔁 reintenta 2 veces
            ->get('https://api.apis.net.pe/v2/reniec/dni', [
                'numero' => $dni
            ]);

            if (!$response->successful()) {
                Log::warning('RENIEC error HTTP', [
                    'dni' => $dni,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
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

        } catch (\Throwable $e) {
            // 💥 Error grave: timeout, DNS, SSL, etc.
            Log::error('RENIEC caído', [
                'dni' => $dni,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }
}
