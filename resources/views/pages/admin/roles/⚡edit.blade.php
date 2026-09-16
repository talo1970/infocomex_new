<?php

    use Livewire\Attributes\Computed;
    use Livewire\Attributes\On;
    use Livewire\Component;

    new class extends Component {
        public $role;
        public $name;
        public $guard_name = "web";
        //public $permissions;

        public $selected_permissions = [];


        #[On('editar-rol-modal')]
        public function rolEditar($rol): void
        {
            $this->role       = \Spatie\Permission\Models\Role::find($rol);
            $this->name       = $this->role->name;
            $this->guard_name = $this->role->guard_name;

            //$this->permissions          = \Spatie\Permission\Models\Permission::all();

            $this->selected_permissions = $this->role->permissions->pluck('id')->toArray();
            //dd($this->selected_permissions);
        }

        #[Computed]
        public function permissions()
        {
            return \Spatie\Permission\Models\Permission::all();
        }

        public function updatedName()
        {
            $this->role->name = $this->name;
        }

        public function updateRole()
        {
            $this->validate([ 'name' => 'required|unique:roles,name,' . $this->role->id ]);

            //(new ActualizarPermisosDeRolAction())->execute($this->selected_permissions, $this->role);
//            $this->role->syncPermissions($this->selected_permissions);
            $this->role->save();

            return redirect(route('admin.roles_permissions.roles.index'))->with('success', 'El rol fue actualizado exitosamente');
        }

        public function cancel(): void
        {
            $this->modal('edit-roles-modal')->close();
        }

    };
?>

<div>
    <flux:modal name="edit-roles-modal" class="min-w-[64rem]">
        <form wire:submit="grabarRole" class="space-y-6">
            <div>
                <flux:heading class="font-bold" size="lg">Editar Rol</flux:heading>
            </div>

            <div class="text-center">
                <flux:badge color="indigo">
                    <div class="md:table-cell whitespace-normal">
                        <p class="line-clamp-2">
                            <flux:heading class="font-bold text-xl"
                                          size="lg">Editar Rol: {{ $this->name }}</flux:heading>
                        </p>
                    </div>
                </flux:badge>
            </div>

            <flux:card>
                <flux:checkbox.group wire:model.live="selected_permissions" label="Permisos" class="flex flex-wrap gap-4 *:gap-x-2">
                    @foreach($this->permissions as $permission)
                        <flux:checkbox label="{{ $permission->name }}"
                                       value="{{ $permission->id }}"
                                       {{--:disabled="$permission->id == in_array($permission->id, $this->selected_permissions)"--}}
                                    />
                        @endforeach
                </flux:checkbox.group>
            </flux:card>

            {{-- buttones--}}
            <div class="flex justify-end pt-4">
                <flux:spacer />
                <flux:modal.close>
                    <flux:button wire:click="cancel()" variant="ghost" wireclass="cursor-pointer">Cancelar</flux:button>
                </flux:modal.close>
                <flux:button type="submit" variant="primary" class="cursor-pointer ms-2">
                    Grabar rol
                </flux:button>
            </div>
        </form>

    </flux:modal>
</div>
