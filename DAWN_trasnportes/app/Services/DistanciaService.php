<?php

namespace App\Services;

use App\Models\Ciudad;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class DistanciaService
{
    /**
     * Devuelve la distancia real por ruta manejando OSRM, en kilómetros.
     */
    public function calcular(Ciudad $origen, Ciudad $destino): ?int
    {
        if ($origen->id_ciudad === $destino->id_ciudad) {
            return 0;
        }

        if (! $origen->tieneCoordenadas() || ! $destino->tieneCoordenadas()) {
            return null;
        }

        $cacheKey = sprintf(
            'distancia:%s:%s',
            $origen->id_ciudad,
            $destino->id_ciudad
        );

        return Cache::remember($cacheKey, now()->addDay(), function () use ($origen, $destino): ?int {
            $url = sprintf(
                '%s/route/v1/driving/%F,%F;%F,%F',
                rtrim((string) config('services.osrm.base_url'), '/'),
                $origen->longitud,
                $origen->latitud,
                $destino->longitud,
                $destino->latitud
            );

            try {
                $response = Http::timeout((int) config('services.osrm.timeout', 15))
                    ->retry(2, 250)
                    ->withHeaders(['User-Agent' => (string) config('services.osrm.user_agent')])
                    ->get($url, [
                        'overview' => 'false',
                        'alternatives' => 'false',
                        'steps' => 'false',
                    ]);

                if ($response->failed()) {
                    Log::warning('No se pudo calcular la distancia con OSRM.', [
                        'origen' => $origen->id_ciudad,
                        'destino' => $destino->id_ciudad,
                        'status' => $response->status(),
                    ]);

                    return null;
                }

                $metros = data_get($response->json(), 'routes.0.distance');

                return is_numeric($metros) ? (int) round($metros / 1000) : null;
            } catch (Throwable $exception) {
                Log::warning('Error al calcular la distancia con OSRM.', [
                    'origen' => $origen->id_ciudad,
                    'destino' => $destino->id_ciudad,
                    'message' => $exception->getMessage(),
                ]);

                return null;
            }
        });
    }
}
