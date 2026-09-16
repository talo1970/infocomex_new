<?php

    use Livewire\Attributes\Computed;
    use Livewire\Component;
    use Spatie\Permission\Models\Role;

    new class extends Component {

        public $asunto;
        public $mensaje;
        public $rolId;

        public function enviar()
        {
            $this->validate([
                'asunto' => ['required'],
                'mensaje' => ['required'],
                'rolId' => ['required', 'exists:roles,id'],
            ]);
            $rol = \Spatie\Permission\Models\Role::findOrFail($this->rolId);
            $usuarios = \App\Models\User::role($rol->name)->get();

            $usuarios->each(function ($usuario) {
                $usuario->notify(new \App\Notifications\SistemaNotification(
                    asunto: $this->asunto,
                    mensaje: $this->mensaje,
                    enviadoPor: auth()->id(),
                ));
            });

            session()->flash('notification', 'Notificación enviada a ' . $usuarios->count() . ' usuarios.');

            $this->reset(['asunto', 'mensaje', 'rolId']);

            $this->dispatch('refreshComponent');
            $this->dispatch('modal-close', name: 'notificacion-enviar-modal');
        }

        #[Computed]
        public function roles()
        {
            return Role::select('id', 'name')->get();
        }
    };
?>

<div>
    <flux:modal name="notificacion-enviar-modal" class="min-w-[40rem]">
        <form wire:submit="enviar" class="space-y-6">
            <div>
                <flux:heading class="font-bold" size="lg">{{ __('Enviar notificación') }}</flux:heading>
            </div>

            <flux:input wire:model="asunto" label="Asunto" placeholder="Ej.: Reunión de coordinación" />

            <flux:textarea rows="4" wire:model="mensaje" label="Mensaje" placeholder="Ingrese texto" />

            <flux:select wire:model.live="rolId" label="Destino (rol)">
                <flux:select.option value="-">Seleccionar rol</flux:select.option>
                @foreach($this->roles as $rol)
                    <flux:select.option value="{{ $rol->id }}" wire:key="{{ $rol->id }}">{{ $rol->name }}</flux:select.option>
                @endforeach
            </flux:select>

            <div class="flex justify-end pt-4">
                <flux:spacer />
                <flux:modal.close>
                    <flux:button variant="ghost" class="cursor-pointer">Cancelar</flux:button>
                </flux:modal.close>
                <flux:button type="submit" variant="primary" class="cursor-pointer ms-2">
                    {{ __('Enviar') }}
                </flux:button>
            </div>
        </form>
    </flux:modal>
</div>
