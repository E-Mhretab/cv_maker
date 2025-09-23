<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('CV Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Welkom bij je CV Dashboard</h3>
                    <p class="mb-6">Hier kun je je CV beheren, nieuwe CV's maken en bestaande CV's bewerken.</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="bg-blue-50 p-6 rounded-lg border border-blue-200">
                            <h4 class="font-semibold text-blue-800 mb-2">Nieuwe CV maken</h4>
                            <p class="text-blue-600 mb-4">Start met een nieuwe CV en kies uit verschillende professionele templates.</p>
                            <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                                CV maken
                            </button>
                        </div>
                        
                        <div class="bg-green-50 p-6 rounded-lg border border-green-200">
                            <h4 class="font-semibold text-green-800 mb-2">Mijn CV's</h4>
                            <p class="text-green-600 mb-4">Bekijk en beheer al je gemaakte CV's op één plek.</p>
                            <button class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                                Bekijk CV's
                            </button>
                        </div>
                        
                        <div class="bg-purple-50 p-6 rounded-lg border border-purple-200">
                            <h4 class="font-semibold text-purple-800 mb-2">Templates</h4>
                            <p class="text-purple-600 mb-4">Bekijk alle beschikbare CV templates en kies je favoriet.</p>
                            <button class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700">
                                Templates
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
