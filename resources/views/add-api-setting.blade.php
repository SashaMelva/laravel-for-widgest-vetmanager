<x-guest-layout>
    <form method="POST" action="{{ route('add-api-setting-post') }}">
        @csrf

        <div>
            <x-input-label for="domainName" :value="__('Domain Name')"/>
            <x-text-input id="domainName" class="block mt-1 w-full" type="text" name="domainName" :value="old('name')"
                          required autofocus autocomplete="name"/>
            <x-input-error :messages="$errors->get('domainName')" class="mt-2"/>
        </div>

        <div class="mt-4">
            <x-input-label for="apiKey" :value="__('Api Key')"/>

            <x-text-input id="apiKey" class="block mt-1 w-full"
                          type="text"
                          name="apiKey"
                          required autocomplete="new-password"/>

            <x-input-error :messages="$errors->get('apiKey')" class="mt-2"/>
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
               href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ml-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
