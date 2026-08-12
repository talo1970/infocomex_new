<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:header container class="border-b border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.toggle class="lg:hidden mr-2" icon="bars-2" inset="left" />

            <x-app-logo href="{{ route('dashboard') }}" wire:navigate />

            <flux:navbar class="-mb-px max-lg:hidden">
                <flux:navbar.item icon="layout-grid" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>
                    {{ __('Dashboard') }}
                </flux:navbar.item>

                <flux:dropdown>
                    <flux:button icon:trailing="chevron-down">Administración</flux:button>

                    <flux:menu>
                        <flux:menu.item :href="route('entidades.index')" :current="request()->routeIs('entidades.index')" wire:navigate>Entidades</flux:menu.item>
                        <flux:menu.item :href="route('entidades.index')" :current="request()->routeIs('entidades.index')" wire:navigate>Estados Constr</flux:menu.item>
                        <flux:menu.item :href="route('codicion.index')" :current="request()->routeIs('codicion.index')" wire:navigate>Cond. de ventas</flux:menu.item>
                        <flux:menu.item :href="route('codicion.index')" :current="request()->routeIs('codicion.index')" wire:navigate>Tipos Documentos</flux:menu.item>
                        <flux:menu.item :href="route('codicion.index')" :current="request()->routeIs('codicion.index')" wire:navigate>Valor</flux:menu.item>
                        <flux:menu.item :href="route('monedas.index')" :current="request()->routeIs('monedas.index')" wire:navigate>Monedas</flux:menu.item>
                        <flux:menu.item :href="route('referencias.index')" :current="request()->routeIs('referencias.index')" wire:navigate>Referencias</flux:menu.item>
                        <flux:menu.item :href="route('cotizaciones.index')" :current="request()->routeIs('cotizaciones.index')" wire:navigate>Cotización diaria</flux:menu.item>
                        <flux:menu.item :href="route('productos.index')" :current="request()->routeIs('productos.index')" wire:navigate>Productos</flux:menu.item>
                        <flux:menu.item :href="route('honorarios.index')" :current="request()->routeIs('honorarios.index')" wire:navigate>Honorarios de Productos</flux:menu.item>
                    </flux:menu>
                </flux:dropdown>

                <flux:dropdown>
                    <flux:button icon:trailing="chevron-down">Operaciones Diarias</flux:button>

                    <flux:menu>
                        <flux:menu.item :href="route('minutas.boletos.index')" :current="request()->routeIs('minutas.boletos.index')" wire:navigate>Minuta Cambio</flux:menu.item>
                        <flux:menu.item :href="route('minutas.6401.index')" :current="request()->routeIs('minutas.6401.index')" wire:navigate>Minuta Com. 6401</flux:menu.item>
                        <flux:menu.item :href="route('minutas.formularios', ['producto' => 9])" :current="request()->routeIs('minutas.formularios')" wire:navigate>Minuta Precio Transferencia</flux:menu.item>
                        <flux:menu.item :href="route('minutas.formularios', ['producto' => 10])" :current="request()->routeIs('minutas.formularios')" wire:navigate>Minuta Formulario 2668</flux:menu.item>
                        <flux:menu.item :href="route('minutas.formularios', ['producto' => 11])" :current="request()->routeIs('minutas.formularios')" wire:navigate>Minuta Formulario 2672</flux:menu.item>
                        <flux:menu.item :href="route('minutas.suscripciones', ['producto' => 6])" :current="request()->routeIs('minutas.suscripciones')" wire:navigate>Minuta Suscripciones</flux:menu.item>
                        <flux:menu.item :href="route('minutas.suscripciones', ['producto' => 7])" :current="request()->routeIs('minutas.suscripciones')" wire:navigate>Minuta Suscripciones BCRA</flux:menu.item>

                    </flux:menu>
                </flux:dropdown>



            </flux:navbar>




            <flux:spacer />

            <flux:navbar class="me-1.5 space-x-0.5 rtl:space-x-reverse py-0!">
                {{--
                <flux:tooltip :content="__('Search')" position="bottom">
                    <flux:navbar.item class="!h-10 [&>div>svg]:size-5" icon="magnifying-glass" href="#" :label="__('Search')" />
                </flux:tooltip>
                <flux:tooltip :content="__('Repository')" position="bottom">
                    <flux:navbar.item
                        class="h-10 max-lg:hidden [&>div>svg]:size-5"
                        icon="folder-git-2"
                        href="https://github.com/laravel/livewire-starter-kit"
                        target="_blank"
                        :label="__('Repository')"

                    />
                </flux:tooltip>
                <flux:tooltip :content="__('Documentation')" position="bottom">
                    <flux:navbar.item
                        class="h-10 max-lg:hidden [&>div>svg]:size-5"
                        icon="book-open-text"
                        href="https://laravel.com/docs/starter-kits#livewire"
                        target="_blank"
                        :label="__('Documentation')"
                    />
                </flux:tooltip>
                --}}
            </flux:navbar>

            <x-desktop-user-menu />
        </flux:header>

        <!-- Mobile Menu -->
        <flux:sidebar collapsible="mobile" sticky class="lg:hidden border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.header>
                <x-app-logo :sidebar="true" href="{{ route('dashboard') }}" wire:navigate />
                <flux:sidebar.collapse class="in-data-flux-sidebar-on-desktop:not-in-data-flux-sidebar-collapsed-desktop:-mr-2" />
            </flux:sidebar.header>

            <flux:sidebar.nav>
                <flux:sidebar.group :heading="__('Platform')">
                    <flux:sidebar.item icon="layout-grid" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>
                        {{ __('Dashboard')  }}
                    </flux:sidebar.item>
                </flux:sidebar.group>
            </flux:sidebar.nav>

            <flux:spacer />

            <flux:sidebar.nav>
                <flux:sidebar.item icon="folder-git-2" href="https://github.com/laravel/livewire-starter-kit" target="_blank">
                    {{ __('Repository') }}
                </flux:sidebar.item>
                <flux:sidebar.item icon="book-open-text" href="https://laravel.com/docs/starter-kits#livewire" target="_blank">
                    {{ __('Documentation') }}
                </flux:sidebar.item>
            </flux:sidebar.nav>
        </flux:sidebar>

        {{ $slot }}

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
    </body>
</html>
