<?php

    use Illuminate\Notifications\DatabaseNotification;
    use Illuminate\Support\Str;
    use Livewire\Attributes\Computed;
    use Livewire\Component;
    use Livewire\WithPagination;
    use Spatie\Permission\Models\Role;

    new class extends Component {

        use WithPagination;

        public        $leidaFiltro   = 'all';
        public        $tipoFiltro    = 'all';
        public        $usuarioFiltro = 'all';
        public string $search        = '';
        public        $perPage       = 10;

        protected $listeners = [ 'refreshComponent' => '$refresh' ];

        #[Computed]
        public function notificaciones()
        {
            $query = DatabaseNotification::query()->with('notifiable');

            if ($this->search) {
                $query->where('data', 'like', "%{$this->search}%");
            }

            if ($this->usuarioFiltro !== 'all') {
                $query->where('notifiable_id', $this->usuarioFiltro);
            }

            if ($this->tipoFiltro !== 'all') {
                $query->where('data->tipo', $this->tipoFiltro);
            }

            $this->applyLeidaFilter($query);

            return $query->latest()->paginate($this->perPage);
        }

        private function applyLeidaFilter($query)
        {
            if ($this->leidaFiltro === 'leidas') {
                $query->whereNotNull('read_at');
            }

            if ($this->leidaFiltro === 'no_leidas') {
                $query->whereNull('read_at');
            }
        }

        #[Computed]
        public function estadisticas()
        {
            $base = DatabaseNotification::query();

            return [
                'total' => (clone $base)->count(),
                'noLeidas' => (clone $base)->whereNull('read_at')->count(),
                'leidas' => (clone $base)->whereNotNull('read_at')->count(),
                'porTipo' => (clone $base)
                    ->selectRaw('type, count(*) as total')
                    ->groupBy('type')
                    ->get()
                    ->mapWithKeys(fn ($fila) => [class_basename($fila->type) => $fila->total]),
            ];
        }

        public function updatedSearch(): void
        {
            $this->resetPage();
        }

        public function marcarLeida($id): void
        {
            DatabaseNotification::findOrFail($id)?->markAsRead();
        }

        public function marcarNoLeida($id): void
        {
            DatabaseNotification::findOrFail($id)?->markAsUnread();
        }

        public function eliminar($id): void
        {
            DatabaseNotification::findOrFail($id)->delete();
        }

        #[Computed]
        public function usuarios()
        {
            return \App\Models\User::select('id', 'name')->get();
        }

        #[Computed]
        public function roles()
        {
            return Role::select('id', 'name')->get();
        }

        public function colorTipo(?string $tipo): string
        {
            return match ($tipo) {
                'creada' => 'green',
                'fecha_cambio' => 'amber',
                'sistema' => 'indigo',
                default => 'zinc',
            };
        }
    };
?>

<div class="space-y-4">
    <div class="-mb-4">
        <livewire:pages::admin.notificaciones.enviar />
    </div>

    <div class="relative mb-4 w-full bg-gradient-to-r from-indigo-50 to-gray-300">
        <flux:heading size="xl" level="1" class="ml-2">
            {{ __('Notificaciones') }}
        </flux:heading>

        <flux:subheading size="lg" class="mb-4 ml-2 flex justify-between">{{ __('Administración de Notificaciones') }}
            <flux:modal.trigger name="notificacion-enviar-modal">
                <flux:button size="sm" icon="paper-airplane" variant="primary" class="cursor-pointer">
                    {{ __('Enviar notificación') }}
                </flux:button>
            </flux:modal.trigger>
        </flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <flux:card>
            <flux:heading size="sm">{{ __('Total') }}</flux:heading>
            <flux:subheading>{{ $this->estadisticas['total'] }}</flux:subheading>
        </flux:card>
        <flux:card>
            <flux:heading size="sm" class="text-red-600">{{ __('No leídas') }}</flux:heading>
            <flux:subheading>{{ $this->estadisticas['noLeidas'] }}</flux:subheading>
        </flux:card>
        <flux:card>
            <flux:heading size="sm">{{ __('Leídas') }}</flux:heading>
            <flux:subheading>{{ $this->estadisticas['leidas'] }}</flux:subheading>
        </flux:card>
        <flux:card>
            <flux:heading size="sm">{{ __('Por tipo') }}</flux:heading>
            <div class="flex gap-2 flex-wrap">
                @forelse($this->estadisticas['porTipo'] as $tipo => $total)
                    <flux:badge size="sm" color="indigo">{{ $tipo }}: {{ $total }}</flux:badge>
                @empty
                    <flux:subheading>—</flux:subheading>
                @endforelse
            </div>
        </flux:card>
    </div>

    <div class="flex justify-between items-center flex-wrap gap-2">
        <flux:input wire:model.live.debounce.300ms="search" placeholder="Buscar mensaje..." class="w-full sm:w-64" />

        <div class="flex gap-2 flex-wrap">
            <flux:select searchable wire:model.live="usuarioFiltro" class="max-w-[16rem]">
                <flux:select.option value="all">Todos los usuarios</flux:select.option>
                @foreach ($this->usuarios as $usuario)
                    <flux:select.option value="{{ $usuario->id }}" wire:key="{{ $usuario->id }}">{{ $usuario->name }}</flux:select.option>
                @endforeach
            </flux:select>

            <flux:select searchable wire:model.live="tipoFiltro">
                <flux:select.option value="all">Todos los tipos</flux:select.option>
                <flux:select.option value="creada">Creada</flux:select.option>
                <flux:select.option value="fecha_cambio">Cambio de fecha</flux:select.option>
                <flux:select.option value="sistema">Sistema</flux:select.option>
            </flux:select>

            <flux:select searchable wire:model.live="leidaFiltro">
                <flux:select.option value="all">Leídas y no leídas</flux:select.option>
                <flux:select.option value="leidas">Leídas</flux:select.option>
                <flux:select.option value="no_leidas">No leídas</flux:select.option>
            </flux:select>
        </div>
    </div>

    <flux:table>
        <flux:table.columns class="bg-indigo-100 text-center">
            <flux:table.column>&ensp;Usuario</flux:table.column>
            <flux:table.column>Tipo</flux:table.column>
            <flux:table.column>Mensaje</flux:table.column>
            <flux:table.column>Fecha</flux:table.column>
            <flux:table.column>Estado</flux:table.column>
            <flux:table.column>Acciones</flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @forelse($this->notificaciones as $notificacion)
                <flux:table.row :key="$notificacion->id" class="hover:bg-gray-100">
                    <flux:table.cell class="m-8">
                        &ensp;{{ $notificacion->notifiable?->name ?? '-' }}
                    </flux:table.cell>

                    <flux:table.cell class="text-center">
                        <flux:badge size="sm" color="{{ $this->colorTipo($notificacion->data['tipo'] ?? '') }}">
                            {{ Str::upper($notificacion->data['tipo'] ?? 'otro') }}
                        </flux:badge>
                    </flux:table.cell>

                    <flux:table.cell class="max-w-[24rem] truncate">
                        {{ $notificacion->data['mensaje'] ?? '-' }}
                        @if(($notificacion->data['tipo'] ?? '') === 'fecha_cambio')
                            @foreach(($notificacion->data['cambios'] ?? []) as $campo => $cambio)
                                <p class="text-xs text-zinc-500">{{ $campo }}: {{ $cambio['anterior'] }} ➜ {{ $cambio['nueva'] }}</p>
                            @endforeach
                        @endif
                    </flux:table.cell>

                    <flux:table.cell class="whitespace-nowrap">
                        {{ $notificacion->created_at?->format('d/m/Y H:i') }}
                    </flux:table.cell>

                    <flux:table.cell class="text-center">
                        <flux:badge size="sm" color="{{ $notificacion->read_at ? 'green' : 'red' }}">
                            {{ $notificacion->read_at ? __('Leída') : __('No leída') }}
                        </flux:badge>
                    </flux:table.cell>

                    <flux:table.cell class="text-center w-24">
                        @if($notificacion->read_at)
                            <flux:tooltip content="Marcar no leída">
                                <flux:badge color="amber" as="button"
                                            wire:click="marcarNoLeida('{{ $notificacion->id }}')"
                                            icon="envelope" class="cursor-pointer">
                                </flux:badge>
                            </flux:tooltip>
                        @else
                            <flux:tooltip content="Marcar leída">
                                <flux:badge color="emerald" as="button"
                                            wire:click="marcarLeida('{{ $notificacion->id }}')"
                                            icon="check" class="cursor-pointer">
                                </flux:badge>
                            </flux:tooltip>
                        @endif

                        <flux:tooltip content="Eliminar">
                            <flux:badge color="red" as="button"
                                        wire:click="eliminar('{{ $notificacion->id }}')"
                                        wire:confirm="¿Eliminar esta notificación?"
                                        icon="trash" class="cursor-pointer">
                            </flux:badge>
                        </flux:tooltip>
                        &ensp;
                    </flux:table.cell>
                </flux:table.row>
            @empty
                <flux:table.row>
                    <flux:table.cell colspan="6" class="text-center text-zinc-500">
                        {{ __('Sin notificaciones') }}
                    </flux:table.cell>
                </flux:table.row>
            @endforelse
        </flux:table.rows>
    </flux:table>
    {{ $this->notificaciones->links() }}

</div>