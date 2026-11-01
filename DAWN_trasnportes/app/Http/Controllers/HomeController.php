<?php

namespace App\Http\Controllers;

use App\Models\Ciudad;
use App\Models\Ruta;
use App\Models\ViajeRecomendado;
use App\Services\DistanciaService;
use App\Services\PrecioService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(Request $request, DistanciaService $distanciaService, PrecioService $precioService): View|RedirectResponse
    {
        $ciudades = Ciudad::with('provincia')
            ->orderBy('nombre_ciudad')
            ->get();

        $tiposVehiculo = $precioService->tiposVehiculo();
        $tarifaReferencia = $precioService->tarifaReferenciaPorKm();

        $origenSeleccionado = null;
        $destinoSeleccionado = null;
        $distancia = null;
        $rutaExistente = null;
        $busquedaRealizada = false;
        $mensajeDistancia = null;
        $mensajeResumen = null;
        $resumenDisponible = false;
        $precioEstimado = null;
        $duracion = null;
        $tipoVehiculoEtiqueta = null;
        $fechaViaje = null;

        $fechaInput = $request->string('date')->toString();
        $tipoVehiculoInput = $request->string('vehicle')->toString();
        $tipoVehiculo = isset($tiposVehiculo[$tipoVehiculoInput]) ? $tipoVehiculoInput : null;

        if ($fechaInput !== '') {
            try {
                $fechaViaje = Carbon::createFromFormat('!Y-m-d', $fechaInput);
            } catch (\Throwable) {
                $fechaViaje = null;
            }

            if ($fechaViaje === null || $fechaViaje->format('Y-m-d') !== $fechaInput) {
                $fechaViaje = null;
                $mensajeResumen = 'La fecha de viaje ingresada no es válida.';
            } elseif ($fechaViaje->lt(today())) {
                $fechaViaje = null;
                $mensajeResumen = 'La fecha de viaje no puede ser anterior a hoy.';
            }
        }

        if ($tipoVehiculoInput !== '' && $tipoVehiculo === null) {
            $mensajeResumen = 'Seleccioná un tipo de vehículo válido.';
        }

        $origenId = $request->integer('origin');
        $destinoId = $request->integer('destination');

        if ($origenId > 0 || $destinoId > 0) {
            $busquedaRealizada = true;

            $origenSeleccionado = $origenId > 0
                ? Ciudad::with('provincia')->find($origenId)
                : null;
            $destinoSeleccionado = $destinoId > 0
                ? Ciudad::with('provincia')->find($destinoId)
                : null;

            if ($origenSeleccionado === null || $destinoSeleccionado === null) {
                $mensajeDistancia = 'Seleccioná una ciudad de origen y una de destino válidas.';
            } elseif ($origenSeleccionado->id_ciudad === $destinoSeleccionado->id_ciudad) {
                $mensajeDistancia = 'La ciudad de origen y la de destino deben ser distintas.';
            } else {
                if (! $request->user() && $fechaViaje !== null && $tipoVehiculo !== null && $mensajeResumen === null) {
                    return redirect()->guest(route('login'));
                }

                $distancia = $distanciaService->calcular($origenSeleccionado, $destinoSeleccionado);

                if ($distancia === null) {
                    $mensajeDistancia = 'No se pudo calcular la distancia para esa ruta.';
                } else {
                    $rutaExistente = Ruta::query()
                        ->where('id_ciudad_partida', $origenSeleccionado->id_ciudad)
                        ->where('id_ciudad_llegada', $destinoSeleccionado->id_ciudad)
                        ->first();

                    if ($mensajeResumen === null) {
                        if ($fechaViaje === null || $tipoVehiculo === null) {
                            $mensajeResumen = 'Seleccioná la fecha y el tipo de vehículo para ver el resumen y las opciones de pago.';
                        } else {
                            $precioEstimado = $precioService->estimar($distancia, $tipoVehiculo);
                            $duracion = $precioService->formatearDuracion(
                                $precioService->duracionMinutos($distancia)
                            );
                            $tipoVehiculoEtiqueta = $precioService->etiquetaVehiculo($tipoVehiculo);
                            $resumenDisponible = true;
                        }
                    }
                }
            }
        }

        $viajesRecomendados = ViajeRecomendado::with('viaje.ciudadPartida', 'viaje.ciudadLlegada')
            ->where('activo', true)
            ->orderBy('orden')
            ->orderBy('id')
            ->get();

        $offers = $viajesRecomendados->map(function (ViajeRecomendado $item) {
            $ruta = $item->ruta;
            if (! $ruta && $item->viaje) {
                $origen = $item->viaje->ciudadPartida?->nombre_ciudad;
                $destino = $item->viaje->ciudadLlegada?->nombre_ciudad;
                if ($origen && $destino) {
                    $ruta = $origen.' - '.$destino;
                }
            }

            $precio = (float) ($item->precio ?? 0);
            if ($item->descuento_porcentaje > 0) {
                $precioFinal = $precio * (1 - $item->descuento_porcentaje / 100);
                $descuento = $item->descuento_porcentaje.'% OFF';
                $precioMostrar = '$'.number_format($precioFinal, 0, ',', '.');
            } else {
                $descuento = '';
                $precioMostrar = '$'.number_format($precio, 0, ',', '.');
            }

            $vehiculo = $item->vehiculo ?: ($item->viaje?->vehiculo?->tipo ?? '');

            return [
                'id' => $item->viaje_id ?: $item->id,
                'route' => $ruta ?: ($item->titulo ?: 'Viaje recomendado'),
                'discount' => $descuento ?: ' ',
                'price' => $precioMostrar,
                'vehicle' => $vehiculo,
                'image' => $item->imagen ? asset('storage/'.$item->imagen) : null,
                'titulo' => $item->titulo,
            ];
        })->all();

        if (empty($offers)) {
            $offers = [
                ['id' => 1, 'route' => 'Leandro N. Alem - Posadas', 'discount' => '15% OFF', 'price' => '$8.500', 'vehicle' => 'Minibús', 'image' => null, 'titulo' => null],
                ['id' => 2, 'route' => 'Leandro N. Alem - Córdoba', 'discount' => '20% OFF', 'price' => '$45.000', 'vehicle' => 'Ómnibus de doble piso', 'image' => null, 'titulo' => null],
                ['id' => 3, 'route' => 'Leandro N. Alem - Buenos Aires', 'discount' => '10% OFF', 'price' => '$55.000', 'vehicle' => 'Piso elevado', 'image' => null, 'titulo' => null],
            ];
        }

        return view('home', compact(
            'ciudades',
            'tiposVehiculo',
            'tarifaReferencia',
            'origenSeleccionado',
            'destinoSeleccionado',
            'distancia',
            'rutaExistente',
            'busquedaRealizada',
            'mensajeDistancia',
            'mensajeResumen',
            'resumenDisponible',
            'precioEstimado',
            'duracion',
            'tipoVehiculo',
            'tipoVehiculoEtiqueta',
            'fechaInput',
            'fechaViaje',
            'offers',
        ));
    }
}
