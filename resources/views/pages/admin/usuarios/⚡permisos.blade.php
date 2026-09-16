<?php

    use App\Models\User;
    use Livewire\Attributes\Computed;
    use Livewire\Attributes\On;
    use Livewire\Component;
    use Spatie\Permission\Models\Permission;
    use Spatie\Permission\Models\Role;

    new class extends Component {
        public User $user;
        public      $cuil;
        public      $name;
        //public $roles;
        //public $permisos;
        public $selected_roles    = [];
        public $selected_permisos = [];
        public $selected_permisions = [];

        #[On('permisosUsuario')]
        public function permisosUsuario(User $user = null): void
        {
            $this->user = $user;
            $this->cuil = $this->user->cuil;
            $this->name = $this->user->name;

            // $this->roles    = Role::select('name', 'id')->get();
            //$this->permisos = Permission::select('name', 'id')->get();
            foreach ($user->roles as $rol) {
                $this->selected_roles[] = $rol->id;
            }
            foreach ($user->permissions as $permiso) {
                $this->selected_permisos[] = $permiso->id;
            }

            $this->selected_permisions = $user->getAllPermissions();
           /*foreach ($user->getAllPermissions() as $permission) {
                //dump($permission->name);
                $this->selected_permisions[] = $permission->name;
            }
*/
        }

        #[Computed]
        public function roles()
        {
            return Role::select('name', 'id')->get();
        }

       #[Computed]
        public function permisos()
        {
            return Permission::select('name', 'id')->get();
        }

        public function cancel(): void
        {
            $this->reset();
            $this->modal('permisos-usuario-modal')->close();
            $this->dispatch('refreshComponent')->to('pages::admin.usuarios.index');

        }

         /*
              #[Computed]
              public function permissions()
              {
                  return $this->user->getAllPermissions();
              }
      */
        public function guardar()
        {
            // $this->validar();
            $this->user->roles()->sync(array_values($this->selected_roles));
            $this->user->permissions()->sync(array_values($this->selected_permisos));
            $this->modal('permisos-usuario-modal')->close();
            $this->dispatch('refreshComponent')->to('pages::admin.usuarios.index');
        }

    };
?>

<div>
    <flux:modal name="permisos-usuario-modal" class="min-w-[64rem]">
        <div class="bg-white dark:bg-gray-800 dark:text-gray-400 overflow-hidden shadow-xl sm:rounded-lg mb-4">
            <div class="px-4 py-2">

                <div class="flex w-full flex-col space-y-2 px-4 py-2">
                    <div class="flex w-full flex-row items-start space-x-4 text-left">
                        <div
                            class="w-full rounded-sm border border-green-400 bg-green-100 px-4 py-1 text-green-700 text-center font-bold dark:border-green-500 dark:bg-green-300">
                            Editar Roles y Permisos del usuario
                        </div>
                    </div>
                    <div class="flex w-full flex-row items-start space-x-4 text-left">
                        <div
                            class="w-full rounded-sm border border-sky-400 bg-sky-100 px-4 py-1 text-sky-700 text-center font-bold dark:border-sky-500 dark:bg-sky-300">
                            {{$this->cuil}} - {{$this->name}}
                        </div>
                    </div>

                    {{--Roles título--}}
                    <div class="flex flex-row justify-items-stretch">
                        <div class="flex flex-col w-full text-left">
                            <div class="flex items-start mt-0.5 text-sm text-gray-600 dark:text-gray-400 sm:mr-6">
                                <div class="w-1/4 pt-2 text-sky-700 text-left font-bold">
                                    Roles Permisos
                                </div>
                            </div>
                        </div>
                    </div>
                    {{--roles detalle--}}
                    <div class="space-x-4 pt-3 pb-1 px-4 bg-gray-50 rounded-xl">
                        <fieldset>
                            <div class="grid grid-cols-5">
                                @foreach($this->roles as $rol)
                                    <div class="flex items-start">
                                        <div>
                                            <label class="inline-flex items-center my-1 mr-6 text-sm">
                                                <input wire:model="selected_roles" type="checkbox"
                                                       class="w-4 h-4 form-checkbox dark:bg-gray-500 dark:text-gray-800"
                                                       value="{{ $rol->id }}"
                                                       @if(old('selected_roles') !== null)
                                                           @if(in_array($rol->id, old('selected_roles'))) checked @endif
                                                    @endif >
                                                <span class="ml-2 ">{{ $rol->name }}</span>
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </fieldset>
                        @error('selected_roles') <span
                            class="text-xs text-red-700 error">{{ $message }}</span> @enderror
                    </div>
                    {{--Permisos título--}}
                    <div class="flex flex-row justify-items-stretch">
                        <div class="flex flex-col w-full text-left">
                            <div class="flex items-start mt-0.5 text-sm text-gray-600 dark:text-gray-400 sm:mr-6">
                                <div class="w-1/4 pt-2 text-sky-700 text-left font-bold">
                                    Permisos
                                </div>
                            </div>
                        </div>
                    </div>
                    {{--permisos detalle--}}
                    <div class="space-x-4 pt-3 pb-4 px-4 bg-gray-50 rounded-xl">
                        <fieldset>
                            <div class="grid grid-cols-5">
                                @foreach($this->permisos as $permiso)
                                    <div class="flex items-start">
                                        <div>
                                            <label class="inline-flex items-center my-1 mr-6 text-sm">
                                                <input wire:model="selected_permisos" type="checkbox"
                                                       class="w-4 h-4 form-checkbox dark:bg-gray-500 dark:text-gray-800"
                                                       value="{{ $permiso->id }}"
                                                       @if(old('selected_permisos') !== null)
                                                           @if(in_array($permiso->id, old('selected_permisos'))) checked @endif
                                                    @endif >
                                                <span class="ml-2 ">{{ $permiso->name }}</span>
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </fieldset>
                        @error('selected_permisos') <span
                            class="text-xs text-red-700 error">{{ $message }}</span> @enderror
                    </div>
                    <div class="text-left">
                    <span
                        class="block px-4 py-2 my-4 text-pjn-700 dark:bg-pjn-700 dark:text-pjn-100 dark:rounded-sm">Permisos asignados</span>

                        <div class="flex flex-row justify-items-stretch">
                            <div class="flex flex-col w-full text-left">
                                <div class="flex items-start mt-0.5 text-sm text-gray-600 dark:text-gray-400 sm:mr-6">
                                    <div class="grid grid-cols-4">
                                        @foreach($this->selected_permisions as $permission)
                                            <div class="flex items-start">
                                                <label class="inline-flex items-center my-2 mr-6 text-sm">
                                                    <flux:badge color="red" size="sm" inset="top bottom">
                                                        <span class="ml-2 ">{{ $permission->descripcion }}</span>
                                                    </flux:badge>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>






{{--
                        <div class="flex flex-wrap justify-start px-4 mb-4 text-sm">
                            <div class="grid grid-cols-6">
                                  @foreach($this->selected_permisions as $permission)
                                    <div class="flex items-start ">
                                        <flux:badge color="gray" size="sm" inset="top bottom">
                                          <span class="ml-3 ">{{ $permission->descripcion }}</span>
                                        </flux:badge>
                                      </div>
                                  @endforeach
                            </div>
                        </div>
                        --}}
                    </div>

                </div>

                <div
                    class="flex items-center justify-end px-4 py-3 bg-gray-50 dark:bg-gray-800 text-right sm:px-6 shadow-sm sm:rounded-bl-md sm:rounded-br-md">
                    <div class="flex flex-row items-center justify-end space-x-4 px-4 mb-2">
                        <!-- wire:loading.attr="disabled" wire:target="guardar"-->
                        <x-button wire:click="guardar()">Guardar</x-button>
                        <x-button wire:click="cancel()">Cancelar</x-button>
                    </div>
                </div>

            </div>
        </div>
    </flux:modal>

</div>
