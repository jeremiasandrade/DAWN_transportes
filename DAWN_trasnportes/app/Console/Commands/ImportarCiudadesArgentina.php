<?php

namespace App\Console\Commands;

use App\Models\Ciudad;
use App\Models\Provincia;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

#[Signature('ciudades:importar {--max=5000 : Cantidad máxima de localidades a importar}')]
#[Description('Importa las localidades de Argentina con sus coordenadas desde la API Georef.')]
class ImportarCiudadesArgentina extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Consultando la API Georef...');

        $response = Http::timeout((int) config('services.georef.timeout', 60))
            ->retry(3, 500)
            ->get(rtrim((string) config('services.georef.base_url'), '/').'/localidades', [
                'max' => (int) $this->option('max'),
                'campos' => 'nombre,provincia.nombre,departamento.nombre,centroide',
            ]);

        if ($response->failed()) {
            $this->error('No se pudo consultar la API Georef.');

            return self::FAILURE;
        }

        $localidades = $response->json('localidades', []);

        if ($localidades === []) {
            $this->warn('La API no devolvió localidades.');

            return self::FAILURE;
        }

        $creadas = 0;
        $actualizadas = 0;

        DB::transaction(function () use ($localidades, &$creadas, &$actualizadas): void {
            foreach ($localidades as $localidad) {
                $provinciaNombre = data_get($localidad, 'provincia.nombre');

                if (blank($provinciaNombre)) {
                    continue;
                }

                $provincia = Provincia::firstOrCreate([
                    'nombre_provincia' => $provinciaNombre,
                ]);

                $departamento = data_get($localidad, 'departamento.nombre');
                $datos = [
                    'departamento' => $departamento,
                    'latitud' => data_get($localidad, 'centroide.lat'),
                    'longitud' => data_get($localidad, 'centroide.lon'),
                ];

                $ciudad = Ciudad::query()
                    ->where('nombre_ciudad', $localidad['nombre'])
                    ->where('provincia_id', $provincia->id_provincia)
                    ->where(function ($query) use ($departamento): void {
                        $query->whereNull('departamento');

                        if (filled($departamento)) {
                            $query->orWhere('departamento', $departamento);
                        }
                    })
                    ->first();

                if ($ciudad !== null) {
                    $ciudad->update($datos);
                    $actualizadas++;

                    continue;
                }

                Ciudad::create([
                    'nombre_ciudad' => $localidad['nombre'],
                    'provincia_id' => $provincia->id_provincia,
                    ...$datos,
                ]);
                $creadas++;
            }
        });

        $this->info("Localidades creadas: {$creadas}. Actualizadas: {$actualizadas}.");

        return self::SUCCESS;
    }
}
