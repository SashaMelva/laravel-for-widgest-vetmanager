<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('ProfileClient') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg"
                 style="display: grid; grid-template-columns: 1fr 2fr;">
                <div class="text-gray-900">
                    <p class="p-6">{{ __("Профиль клиента") }}</p>
                    <div class="p-6">
                        @if(!is_null($client))
                            <p>{{ $client->lastName }}</p>
                            <p>{{ $client->firstName }}</p>
                            <p>{{ $client->middleName }}</p>
                        @endif
                    </div>
                    <div class="p-6" style="display: flex; justify-content: space-between;">
                        <a class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                           style="background-color: rgb(30 41 59);"
                           href="{{ route('clients.index') }}">back</a>
                    </div>
                </div>
                <div class="p-6">
                    <div class="mt-1 mb-4">
                        @if(!is_null($client))
                            <a class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                               style="background-color: rgb(30 41 59);"
                               href="{{route('pets.view.create',  $client->id)}}"
                               hidden="hidden">{{ __('Add Pet') }}</a>
                        @endif
                    </div>
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 dark:text-gray-400">
                            <tr class="bg-gray-50">
                                <th scope="col" class="px-6 py-5">#</th>
                                <th scope="col" class="px-6 py-5">Кличка</th>
                                <th scope="col" class="px-6 py-5">Тип питомца</th>
                                <th scope="col" class="px-6 py-5">Порода</th>
                                <th scope="col" class="px-6 py-5">Edit</th>
                                <th scope="col" class="px-6 py-5">Delete</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(!is_null($pets))
                                @foreach($pets as $pet)
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                        <th scope="row"
                                            class="px-6 py-5 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                                            {{ $pet->id }}
                                        </th>
                                        <td class="px-6 py-5">{{ $pet->alias }}</td>
                                        <td class="px-6 py-5">{{ $pet->type->title }}</td>
                                        <td class="px-6 py-5">{{ $pet->breed->title }}</td>
                                        <td class="px-6 py-5">
                                            <a href="{{ route('pets.edit', $pet->id) }}">Edit</a>
                                        </td>
                                        <td class="px-6 py-5">
                                            <form method="POST" action="{{ route('pets.destroy', $pet->id)}}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                                        style="background-color: rgb(220 38 38)">
                                                    Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
