<x-mail::message>
# {{ $pago->estado === 'aprobado' ? 'Tu pasaje está confirmado' : 'Recibimos tu compra' }}

Hola {{ $comprador->first_name ?: $comprador->name }},

@if ($pago->estado === 'aprobado')
Tu pago fue aprobado y tu pasaje ya está emitido.
@else
Registramos tu compra. El pasaje quedará confirmado cuando acreditemos la transferencia.
@endif

<x-mail::panel>
**Código de pasaje:** {{ $pago->referencia }}
</x-mail::panel>

<x-mail::table>
| Detalle | Información |
| :--- | :--- |
| Pasajero | {{ $pago->nombre_pasajero }} |
| DNI | {{ $pago->dni_pasajero }} |
| Recorrido | {{ $pago->origen }} → {{ $pago->destino }} |
| Fecha de compra | {{ $pago->created_at->format('d/m/Y H:i') }} |
| Método de pago | {{ $pago->metodo_pago_etiqueta }} |
| Estado | {{ $pago->estado_etiqueta }} |
| Total | $ {{ number_format($pago->monto, 0, ',', '.') }} |
</x-mail::table>

<x-mail::button :url="route('pagos.show', $pago)">
Ver mi pasaje
</x-mail::button>

@if ($pago->estado !== 'aprobado')
Para completar el pago por transferencia, comunicate con atención al cliente y mencioná la referencia **{{ $pago->referencia }}**.
@endif

Gracias por viajar con DAWN.

Este correo corresponde a una compra de demostración: no se realizó ningún cobro real.
</x-mail::message>
