<?php

    use Livewire\Attributes\On;
    use Livewire\Component;
    use App\Models\User;

    new class extends Component {

        public User $user;

        public $name;
        public $cuil;
        public $email;
        public $rol;

        public $nombreRol = [];
        public $nombreEquipo = [];
        public $equipoResponsable = [];

        public $extra;
        public $judicial;


        #[On('mostrarUsuario')]
        public function mostrarUsuario($user = null): void
        {
            $this->reset();
//            $this->user = User::with('roles')->select('id', 'name', 'cuil', 'email')->find($user);
            //dd($this->user );
            $this->user = User::find($user);

            // roles del usuario
            foreach ($this->user->roles as $rol)
            {
                $this->nombreRol[] = $rol->name;
            }
            //dd($this->user );
            //$this->user = $user;
            $this->name = $this->user->name;
            $this->cuil = $this->user->cuil;
            $this->email = $this->user->email;
        }

        public function cancel(): void
        {
            $this->modal('show-usuario-modal')->close();
        }
    };
?>

<div>
    <flux:modal name="show-usuario-modal" class="min-w-[64rem]">
        <flux:card>
                <div
                    class="pl-3 pr-3 bg-white dark:bg-gray-800 dark:text-gray-400 overflow-hidden shadow-xl sm:rounded-lg mb-4">
                    <div class="px-2 py-2">
                        <div class="flex w-full flex-col space-y-8 px-0 py-2">
                            <div class="flex w-full flex-row items-start space-x-4 text-left">
                                <div
                                    class="w-full rounded-sm border border-indigo-400 bg-indigo-100 px-4 py-2 text-indigo-700 text-center font-bold dark:border-indigo-500 dark:bg-indigo-300">
                                    Información del usuario
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="md:col-span-1 flex justify-star">
                        <div class="w-2/5 px-4 sm:px-0">
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                Datos personales del Usuario
                            </p>
                        </div>
                        <div class="sm:px-0">
                            {{-- nombre --}}
                            <div class="flex flex-row justify-items-stretch">
                                <div class="flex flex-col w-full text-left">
                                    <div class="flex items-start mt-0.5 text-sm text-gray-600 dark:text-gray-400 sm:mr-6">
                                        <div class="mr-2">
                                            <flux:icon.user w=4 h=10 color="gray-600" />
                                        </div>
                                        <strong>
                                            <span class="mb-4 w-2/3 text-2xl"> {{ $this->name }} </span>
                                        </strong>
                                    </div>
                                </div>
                            </div>
                            {{-- cuil --}}
                            <div class="flex flex-row justify-items-stretch">
                                <div class="flex flex-col w-full text-left">
                                    <div class="flex items-start mt-0.5 text-sm text-gray-600 dark:text-gray-400 sm:mr-6">
                                        <div class="mr-2">
                                            <flux:icon.identification w=4 h=5 color="gray-600" />
                                        </div>
                                        <strong>
                                            <span class="w-2/3">
                                                {{App\Helpers\CuitHelper::format($this->cuil)}}
                                            </span>
                                        </strong>
                                    </div>
                                </div>
                            </div>
                            {{-- Email --}}
                            <div class="flex flex-row justify-items-stretch">
                                <div class="flex flex-col w-full text-left">
                                    <div class="flex items-start mt-0.5 text-sm text-gray-600 dark:text-gray-400 sm:mr-6">
                                        <div class="mr-2">
                                            <flux:icon.at-symbol w=4 h=5 color="gray-600" />
                                        </div>
                                        <strong>
                                            <span class="w-2/3">
                                                {{ $this->email }}
                                            </span>
                                        </strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <flux:separator class="my-4"/>

                    <div class="md:col-span-1 flex justify-star">
                        <div class="w-2/5 px-4 sm:px-0">
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                Información de Roles y permisos del Usuario
                            </p>
                        </div>

                        <div class="px-4 sm:px-0">
                            {{--Roles título--}}
                            <div class="flex flex-row justify-items-stretch">
                                <div class="flex flex-col w-full text-left">
                                    <div class="flex items-start mt-0.5 text-sm text-gray-600 dark:text-gray-400 sm:mr-6">
                                        <div class="w-1/4 pt-2 text-sky-700 text-left font-bold">
                                            Roles
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{--roles detalle--}}
                            <div class="flex flex-row justify-items-stretch">
                                <div class="flex flex-col w-full text-left">
                                    <div class="flex items-start mt-0.5 text-sm text-gray-600 dark:text-gray-400 sm:mr-6">
                                        <div class="grid grid-cols-4">
                                            @foreach($this->nombreRol as $nombre)
                                                <div class="flex items-start">
                                                    <label class="inline-flex items-center my-1 mr-6 text-sm">
                                                        <span class="ml-2 ">{{ $nombre }}</span>
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-end px-4 py-1 bg-gray-50 dark:bg-gray-800 text-right sm:px-6 shadow-sm sm:rounded-bl-md sm:rounded-br-md">
                    <div class="flex flex-row items-center justify-end px-4 my-2">
                        <x-button wire:click="cancel()">Aceptar</x-button>
                    </div>
                </div>
            </flux:card>
    </flux:modal>
</div>
