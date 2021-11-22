<x-app-layout>
    <form method="post" action="/projects" class="bg-white lg:w-1/2 mx-auto p-8 rounded-lg mt-5 shadow-lg">
        <h1 class="text-center text-xl font-bold text-gray-500 mb-5">Create Project</h1>
        @csrf
        <div class="mb-5">
            <label for="title" class="block text-gray-500 mb-2">Title</label>
            <input type="text" id="title" name="title" placeholder="Project Title" class="w-full rounded border-gray-300" required>
        </div>

        <div class="mb-5">
            <label for="description" class="block text-gray-500 mb-2">Description</label>
            <textarea  id="description" name="description" placeholder="Project Description..." rows="5" class="w-full rounded border-gray-300" required></textarea>
        </div>

        <button class="rounded-lg text-white shadow text-sm py-2 px-5 bg-blue-400 hover:bg-blue-500">Submit</button>
    </form>
</x-app-layout>

