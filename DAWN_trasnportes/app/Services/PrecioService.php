<?php

namespace App\Services;

class PrecioService
{
    /**
     * Base Tarifaria de Aplicación oficial para agosto de 2026, expresada en pesos
     * por pasajero-kilómetro por la Subsecretaría de Transporte Automotor.
     */
    private const TARIFA_REFERENCIA_POR_KM = 132.4058;

    private const TARIFA_MINIMA = 2500.0;

    private const VELOCIDAD_PROMEDIO_KMH = 75;

    private const MINUTOS_BASE = 20;

    /**
     * @var array<string, array{etiqueta: string, factor: float}>
     */
    private const VEHICULOS = [
        'minibus' => [
            'etiqueta' => 'Minibús',
            'factor' => 0.85,
        ],
        'piso-elevado' => [
            'etiqueta' => 'Piso elevado',
            'factor' => 1.0,
        ],
        'doble-piso' => [
            'etiqueta' => 'Ómnibus de doble piso',
            'factor' => 1.15,
        ],
    ];

    /**
     * @return array<string, array{etiqueta: string, factor: float}>
     */
    public function tiposVehiculo(): array
    {
        return self::VEHICULOS;
    }

    public function tarifaReferenciaPorKm(): float
    {
        return self::TARIFA_REFERENCIA_POR_KM;
    }

    public function etiquetaVehiculo(string $tipoVehiculo): ?string
    {
        return self::VEHICULOS[$tipoVehiculo]['etiqueta'] ?? null;
    }

    public function estimar(int $distanciaKm, string $tipoVehiculo): ?float
    {
        if ($distanciaKm <= 0 || ! isset(self::VEHICULOS[$tipoVehiculo])) {
            return null;
        }

        $precio = $distanciaKm * self::TARIFA_REFERENCIA_POR_KM * self::VEHICULOS[$tipoVehiculo]['factor'];
        $precio = max($precio, self::TARIFA_MINIMA);

        return round($precio / 100) * 100;
    }

    public function duracionMinutos(int $distanciaKm): int
    {
        if ($distanciaKm <= 0) {
            return 0;
        }

        return (int) ceil(($distanciaKm / self::VELOCIDAD_PROMEDIO_KMH) * 60) + self::MINUTOS_BASE;
    }

    public function formatearDuracion(int $minutos): string
    {
        $horas = intdiv($minutos, 60);
        $minutosRestantes = $minutos % 60;

        if ($horas === 0) {
            return "{$minutosRestantes} min";
        }

        if ($minutosRestantes === 0) {
            return "{$horas} h";
        }

        return "{$horas} h {$minutosRestantes} min";
    }
}
