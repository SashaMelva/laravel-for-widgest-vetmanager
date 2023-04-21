<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Homepage') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("Фильтр поиска пользователей") }}
                </div>
                <div style="display: flex; justify-content: space-between; padding: 0 1.5rem 1.5rem 1.5rem;">
                    <div class="mt-2">
                        <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Фамилия</label>
                        <input id="" name="" type="" required
                               class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    </div>
                    <div class="mt-2">
                        <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Имя</label>
                        <input id="" name="" type="" required
                               class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    </div>
                    <div class="mt-2">
                        <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Отчество</label>
                        <input id="" name="" type="" required
                               class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    @if (session()->has('status'))
                        <div class="flex justify-center items-center">

                            <p class="ml-3 text-sm font-bold text-green-600">{{ session()->get('status') }}</p>
                        </div>
                    @endif
                    <div class="mt-1 mb-4">
                        <a class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                           style="background-color: rgb(30 41 59);"
                           href="{{route('add-client')}}">{{ __('Add User') }}</a>
                    </div>
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 dark:text-gray-400">
                            <tr class="bg-gray-50">
                                <th scope="col" class="px-6 py-5">#</th>
                                <th scope="col" class="px-6 py-5">Фамилия Имя Отчество</th>
                                <th scope="col" class="px-6 py-5">Edit</th>
                                <th scope="col" class="px-6 py-5">Delete</th>
                                <th scope="col" class="px-6 py-5">Profile</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th scope="row"
                                    class="px-6 py-5 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                                    1
                                </th>
                                <td class="px-6 py-5"> Иванив Виктор Сегреевич</td>
                                <td class="px-6 py-5">
                                    <a href="">Edit</a>
                                </td>
                                <td class="px-6 py-5">
                                    <a class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                       style="background-color: rgb(220 38 38);"
                                       href="#">Delete</a>
                                </td>
                                <td class="px-6 py-5">
                                    <a class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                       style="background-color: rgb(22 163 74);"
                                       href="{{route('profile-client')}}">watch</a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
