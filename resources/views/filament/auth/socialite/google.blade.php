{{-- <x-filament::button icon="heroicon-m-sparkles" outlined :href="route('socialite.redirect', 'google')" tag="a" color="gray">
    Sign in with Google
</x-filament::button> --}}

<div class="mt-4">
    <form action="{{ route('socialite.redirect', ['provider' => 'google']) }}" method="GET">
        <x-filament::button type="submit" icon="heroicon-m-sparkles" outlined color="gray" class="w-full">
            Sign in with Google
        </x-filament::button>
    </form>
</div>
