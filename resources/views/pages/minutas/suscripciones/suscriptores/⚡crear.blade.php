<?php

use Livewire\Component;

new class extends Component
{



public salvarSuscriptor()
{
    // tener idea de grabar suscriptor sin tener la minuta

    $archivoAttributes = [
    'id'        => null,
    'nombre'    => $this->archivo->getClientOriginalName(),
    'inicio'      => $this->archivo->store('mensajes-archivos', 'public'),
    'vencimiento' => $this->archivo->getClientOriginalExtension(),
    'dias'    => $this->archivo->getClientOriginalName(),
    'importe'    => $this->archivo->getClientOriginalName(),
    'periodo'    => $this->archivo->getClientOriginalName(),
    ];

    una ves que lo quiere grabar va a index de suscriptores para actualizar la tabla
            $this->dispatch('uploadedArchivo', archivo: $archivoAttributes)->to(CrearMensaje::class);

}



};
?>

<div>
    alta
</div>
