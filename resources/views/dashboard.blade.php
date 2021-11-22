<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <header class="mb-2 py-4">
        <div class="flex justify-between w-full items-center">
            <p class="text-sm text-gray-500 font-normal">My Projects</p>
            <a href="/projects/create" class="rounded-lg text-white shadow text-sm ml-4 py-2 px-5 bg-blue-400 hover:bg-blue-500">New Project</a>
        </div>
    </header>

    <div class="py-10">
        <div class="lg:flex lg:flex-wrap -mx-3">
            @forelse($projects as $project)
                <div class="lg:w-1/3 px-3 pb-6">
                    @include('projects.card')
                </div>
            @empty
                <div class="p-6 bg-white border-b border-gray-200 w-full">
                    No Projects Yet!
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
