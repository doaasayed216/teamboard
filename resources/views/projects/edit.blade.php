<x-app-layout>
    <form method="post" action="/projects/{{$project->id}}" class="bg-white lg:w-1/2 mx-auto p-8 rounded-lg mt-5 shadow-lg">
        <h1 class="text-center text-xl font-bold text-gray-500 mb-5">Edit Project</h1>
        @csrf
        @method('patch')
        <div class="mb-5">
            <label for="title" class="block text-gray-500 mb-2">Title</label>
            <input type="text" id="title" name="title" placeholder="Project Title"  value="{{$project->title}}"
                   class="w-full rounded border-gray-300" required>
        </div>

        <div class="mb-5">
            <label for="description" class="block text-gray-500 mb-2">Description</label>
            <textarea  id="description" name="description" placeholder="Project Description..." rows="5"
                       class="w-full rounded border-gray-300" required>{{$project->description}}</textarea>
        </div>

        <button class="rounded-lg text-white shadow text-sm py-2 px-5 bg-blue-300 hover:bg-blue-400">Update</button>
    </form>
</x-app-layout>
