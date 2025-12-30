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

    public function consultarDni(string $dni): array
    {
        try {
            $response = Http::withHeaders([
                'Referer' => 'https://apis.net.pe/consulta-dni-api',
                'Authorization' => 'Bearer ' . $this->token,
            ])
            ->timeout(5)
            ->retry(2, 300)
            ->get('https://api.apis.net.pe/v2/reniec/dni', [
                'numero' => $dni
            ]);

            if (!$response->successful()) {
                return [
                    'status' => 'RENIEC_ERROR',
                    'data'   => null,
                ];
            }

            $data = $response->json();

            if (
                isset($data['nombres'],
                      $data['apellidoPaterno'],
                      $data['apellidoMaterno'])
            ) {
                return [
                    'status' => 'OK',
                    'data'   => [
                        'nombres'   => $data['nombres'],
                        'apellidos' => $data['apellidoPaterno'].' '.$data['apellidoMaterno'],
                    ],
                ];
            }

            return [
                'status' => 'NOT_FOUND',
                'data'   => null,
            ];

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            // 🌐 SIN INTERNET
            Log::error('Sin conexión a internet', [
                'dni' => $dni,
                'error' => $e->getMessage(),
            ]);

            return [
                'status' => 'NO_INTERNET',
                'data'   => null,
            ];

        } catch (\Throwable $e) {
            // 💥 Error desconocido
            Log::error('Error RENIEC', [
                'dni' => $dni,
                'error' => $e->getMessage(),
            ]);

            return [
                'status' => 'ERROR',
                'data'   => null,
            ];
        }
    }
}
