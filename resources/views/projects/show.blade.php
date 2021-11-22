<x-app-layout>
    <header class="mb-2 py-4">
        <div class="lg:flex justify-between w-full items-center">
            <p class="text-sm text-gray-500 w-2/5 whitespace-nowrap font-normal truncate mb-2 lg:-mb-2"><a href="/projects">My Projects</a> / {{Illuminate\Support\Str::limit($project->title, 10)}}</p>
            <div class="flex items-center">
                <div class="flex -space-x-2">
                    <img src="https://i.pravatar.cc/35?img={{$project->owner->id}}" width="35" height="35" class="rounded-full">
                    @foreach($project->members as $member)
                        <img src="https://i.pravatar.cc/35?img={{$member->id}}" width="35" height="35" class="rounded-full">
                    @endforeach
                </div>
                @can('manage', $project)
                <a href="/projects/{{$project->id}}/edit" class="rounded-lg min-w-max text-white shadow text-sm ml-4 py-2 px-5 bg-blue-400 hover:bg-blue-500">Edit project</a>
                @endcan
            </div>
        </div>
    </header>

    <div class="lg:flex justify-between -mx-3">
        <div class="lg:w-1/2 px-3 h-1/2">
                @foreach($project->tasks as $task)
                <div class="flex bg-white hover:bg-blue-100 rounded p-5 shadow mb-3 justify-between">
                    <div class="w-1/2">
                        <a href="/projects/{{$project->id}}/tasks/{{$task->id}}"><h2 class="truncate {{$task->completed ? 'text-gray-500 line-through' : ''}}">{{$task->title}}</h2></a>
                    </div>
                    <div class="flex items-center">
                        <div class="flex -space-x-2 mr-2">
                            @foreach($task->assigned_to as $member)
                                <img src="https://i.pravatar.cc/35?img={{$member->id}}" width="30" height="30" class="rounded-full">
                            @endforeach
                        </div>
                        <form method="post" action="/projects/{{$project->id}}/tasks/{{$task->id}}">
                            @csrf
                            @method('PATCH')
                            <div class="flex">
                                <input type="hidden" name="title" value="{{$task->title}}">
                                <input type="checkbox" name="completed" onchange="this.form.submit()" {{$task->completed ? 'checked' : ''}} {{!$task->assigned_to->contains(auth()->user())  ? 'disabled': ''}}>
                            </div>
                        </form>
                    </div>

                </div>
                @endforeach
                @can('manage', $project)
                <form method="post" action="/projects/{{$project->id}}/tasks">
                    @csrf
                    <input type="text" name="title" placeholder="Add new Task" class="w-full p-5 bg-white mb-3 rounded border-none">
                </form>
                @endcan
        </div>
        @can('manage', $project)
        <div class="lg:1/4 px-3 mb-5">
            <div class="flex flex-col bg-white p-5 rounded shadow-sm">
                <h3 class="font-normal text-xl border-l-4 border-blue-400 pl-4 -ml-5 py-4 mb-3">Invite a User</h3>
                <form method="post" action="/projects/{{$project->id}}/invitations">
                    @csrf
                    <input type="email" name="email" placeholder="Email Address" required class="rounded border-gray-300 mb-3 w-full">
                    <button class="rounded-lg text-white shadow text-sm py-2 px-5 mt-3 bg-blue-400 hover:bg-blue-500">Send
                        <i class="fas fa-paper-plane ml-1"></i>
                    </button>
                    @error('email')
                        <p class="text-sm text-red-500 mt-2">{{$message}}</p>
                    @enderror
                </form>
            </div>
        </div>
        @endcan
        <div class="lg:w-1/4 px-3">
        <div class="flex flex-col bg-white p-5 rounded-lg shadow-sm">
            <h2 class="font-normal text-xl pb-2 border-b-2 border-blue-400 mb-3">Recent Activities</h2>
            <ul class="text-sm mb-5">
                @foreach($activities as $activity)
                    <li class="{{$loop->last ? '' : 'mb-1 border-b border-gray-300'}} py-1">
                        @include('activity.' . $activity->type)
                        <span class="text-gray-500">{{$activity->created_at->diffForHumans(null, true)}}</span>
                    </li>
                @endforeach
            </ul>
            {{$activities->links()}}
        </div>
        </div>
    </div>
</x-app-layout>
