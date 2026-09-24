<?php

use Livewire\Component;

new class extends Component
{


    private function esVendedor(): bool
    {
        return auth()->user()->hasRole('vendedor');
    }



};
?>

<div>
    {{-- It is quality rather than quantity that matters. - Lucius Annaeus Seneca --}}
</div>
