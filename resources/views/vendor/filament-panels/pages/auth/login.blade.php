<x-filament-panels::page.simple>
    {{-- @if (filament()->hasRegistration())
    <x-slot name="subheading">
        {{ __('filament-panels::pages/auth/login.actions.register.before') }}

        {{ $this->registerAction }}
    </x-slot>
    @endif

    {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::AUTH_LOGIN_FORM_BEFORE,
    scopes: $this->getRenderHookScopes()) }}

    <x-filament-panels::form id="form" wire:submit="authenticate">
        {{ $this->form }}

        <x-filament-panels::form.actions :actions="$this->getCachedFormActions()"
            :full-width="$this->hasFullWidthFormActions()" />
    </x-filament-panels::form>

    {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::AUTH_LOGIN_FORM_AFTER,
    scopes: $this->getRenderHookScopes()) }} --}}
    <div class="mt-4">
        <form action="{{ route('socialite.redirect', ['provider' => 'google']) }}" method="GET">
            <x-filament::button type="submit" icon="heroicon-m-sparkles"
                class="w-full py-3 bg-pink-500 text-white hover:bg-pink-600">
                Sign in with Google
            </x-filament::button>
        </form>
    </div>

</x-filament-panels::page.simple>