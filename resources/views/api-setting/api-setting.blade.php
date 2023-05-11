<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('ApiData') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("Данные апи") }}
                </div>
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 dark:text-gray-400">
                    <tr class="bg-gray-50">
                        <th scope="col" class="px-6 py-5">#</th>
                        <th scope="col" class="px-6 py-5">url</th>
                        <th scope="col" class="px-6 py-5">key</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                        <th scope="row"
                            class="px-6 py-5 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                            {{ $apiSetting->id }}
                        </th>
                        <td class="px-6 py-5">{{ $apiSetting->url }}</td>
                        <td class="px-6 py-5">{{ $apiSetting->key }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
