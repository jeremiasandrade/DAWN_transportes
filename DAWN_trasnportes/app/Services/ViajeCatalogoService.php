<?php

namespace App\Services;

class ViajeCatalogoService
{
    private const ORIGEN = 'Leandro N. Alem';

    /**
     * @var array<string, array<string, mixed>>
     */
    private const VIAJES = [
        '1' => [
            'destination' => 'Posadas',
            'description' => 'La capital de la provincia de Misiones te espera con su hermosa costanera a orillas del río Paraná, su rica historia jesuítica y una oferta gastronómica inigualable. Ideal para una escapada de fin de semana.',
            'precio' => 8500,
            'company' => 'Dawn Transportes VIP',
            'vehicle' => 'Minibús Ejecutivo',
            'distance' => '90 km',
            'departure' => '08:30 AM',
            'arrival' => '10:00 AM',
            'availability' => '12 boletos',
        ],
        '2' => [
            'destination' => 'Córdoba',
            'description' => 'Descubrí Córdoba, su arquitectura histórica, sus paseos urbanos y la energía de una de las ciudades más emblemáticas del país.',
            'precio' => 45000,
            'company' => 'Dawn Transportes VIP',
            'vehicle' => 'Ómnibus de doble piso',
            'distance' => '1.180 km',
            'departure' => '08:30 AM',
            'arrival' => '10:00 PM',
            'availability' => '12 boletos',
        ],
        '3' => [
            'destination' => 'Buenos Aires',
            'description' => 'Viví Buenos Aires, una ciudad llena de cultura, gastronomía, espacios verdes y rincones para descubrir en cada visita.',
            'precio' => 55000,
            'company' => 'Dawn Transportes VIP',
            'vehicle' => 'Ómnibus de piso elevado',
            'distance' => '1.050 km',
            'departure' => '08:30 AM',
            'arrival' => '08:30 PM',
            'availability' => '12 boletos',
        ],
        '4' => [
            'destination' => 'Corrientes',
            'description' => 'Conocé Corrientes, sus playas sobre el Paraná, su centro histórico y la música que acompaña cada rincón de la ciudad.',
            'precio' => 12000,
            'company' => 'Dawn Transportes VIP',
            'vehicle' => 'Minibús Ejecutivo',
            'distance' => '150 km',
            'departure' => '09:00 AM',
            'arrival' => '11:30 AM',
            'availability' => '12 boletos',
        ],
        '5' => [
            'destination' => 'Resistencia',
            'description' => 'Descubrí Resistencia, la capital chaqueña reconocida por sus esculturas al aire libre, parques y propuestas culturales.',
            'precio' => 18500,
            'company' => 'Dawn Transportes VIP',
            'vehicle' => 'Ómnibus de doble piso',
            'distance' => '280 km',
            'departure' => '08:30 AM',
            'arrival' => '01:00 PM',
            'availability' => '12 boletos',
        ],
        '6' => [
            'destination' => 'Formosa',
            'description' => 'Visitá Formosa y disfrutá de sus espacios verdes, la costanera y la naturaleza característica del litoral argentino.',
            'precio' => 22000,
            'company' => 'Dawn Transportes VIP',
            'vehicle' => 'Ómnibus de piso elevado',
            'distance' => '390 km',
            'departure' => '08:00 AM',
            'arrival' => '03:00 PM',
            'availability' => '12 boletos',
        ],
        '7' => [
            'destination' => 'Tucumán',
            'description' => 'Explorá San Miguel de Tucumán, su patrimonio histórico, sus plazas y los paisajes verdes que rodean la ciudad.',
            'precio' => 39000,
            'company' => 'Dawn Transportes VIP',
            'vehicle' => 'Ómnibus de doble piso',
            'distance' => '850 km',
            'departure' => '07:30 AM',
            'arrival' => '09:00 PM',
            'availability' => '12 boletos',
        ],
        '8' => [
            'destination' => 'Salta',
            'description' => 'Salta combina arquitectura colonial, cocina regional y paisajes andinos para una experiencia inolvidable.',
            'precio' => 46000,
            'company' => 'Dawn Transportes VIP',
            'vehicle' => 'Ómnibus de piso elevado',
            'distance' => '1.050 km',
            'departure' => '07:00 AM',
            'arrival' => '11:00 PM',
            'availability' => '12 boletos',
        ],
        '9' => [
            'destination' => 'Mendoza',
            'description' => 'Mendoza te espera con sus bodegas, su gastronomía y la imponente vista de la cordillera de los Andes.',
            'precio' => 58000,
            'company' => 'Dawn Transportes VIP',
            'vehicle' => 'Ómnibus de doble piso',
            'distance' => '1.450 km',
            'departure' => '06:30 AM',
            'arrival' => '08:30 AM del día siguiente',
            'availability' => '12 boletos',
        ],
    ];

    /**
     * @return array<string, array<string, mixed>>
     */
    public function todos(): array
    {
        return collect(self::VIAJES)
            ->map(fn (array $viaje, int|string $id): array => $this->formatearViaje($viaje, (string) $id))
            ->all();
    }

    /**
     * @return array<string, mixed>|null
     */
    public function buscar(string|int $id): ?array
    {
        $id = (string) $id;

        if (! isset(self::VIAJES[$id])) {
            return null;
        }

        return $this->formatearViaje(self::VIAJES[$id], $id);
    }

    /**
     * @return list<string>
     */
    public function ids(): array
    {
        return array_map('strval', array_keys(self::VIAJES));
    }

    /**
     * @param  array<string, mixed>  $viaje
     * @return array<string, mixed>
     */
    private function formatearViaje(array $viaje, string $id): array
    {
        return [
            ...$viaje,
            'id' => $id,
            'origin' => self::ORIGEN,
            'price' => '$'.number_format($viaje['precio'], 0, ',', '.'),
        ];
    }
}
