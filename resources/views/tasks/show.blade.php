<x-app-layout>
    <div class="bg-white lg:w-4/5 mx-auto p-8 pb-1 rounded-lg mt-3 shadow-lg">
        <form method="post" action="/projects/{{$task->project_id}}/tasks/{{$task->id}}">
            @csrf
            @method('delete')
            <div class="flex justify-between items-center">
                <input type="text" name="title" value="{{$task->title, 15}}" class="w-1/2 text-xl bg-gray-100 rounded lg:w-3/4 border-none font-bold text-gray-500 mb-5 truncate">
                <button class="rounded-lg text-white shadow text-sm py-2 px-5 bg-red-500 hover:bg-red-600 mb-5">
                    <i class="fas fa-trash mr-2"></i>Delete
                </button>
            </div>
        </form>
        <div class="lg:flex">
            <div class="mb-5 lg:w-3/5 lg:mr-5">
                <form method="post" action="/projects/{{$task->project_id}}/tasks/{{$task->id}}" >
                    @csrf
                    @method('patch')
                    <input type="hidden" name="title" value="{{$task->title}}">
                    <div class="mb-5">
                        <label class="mb-2 text-gray-500 block">Description</label>
                        <textarea name="description" rows="5" class="w-full rounded-lg border-gray-300">{{old('description', $task->description)}}</textarea>
                    </div>
                    <div class="mb-5">
                        <label class="mb-2 block text-gray-500 mr-2"><i class="far fa-clock mr-2"></i>Due to</label>
                        <input type="datetime-local" name="due_to" value="{{$task->due_to ? date('Y-m-d\TH:i', strtotime($task->due_to)) : ''}}" class="w-full {{$task->due_to ? (date('Y-m-d\TH:i', strtotime($task->due_to)) > now() ? 'bg-green-100' : 'bg-red-100') : ''}}">
                    </div>

                    <label class="text-gray-500 mb-2 block">Assign to</label>
                    <div class="flex flex-col h-32 overflow-y-scroll mb-5 border border-gray-300 rounded p-2">
                        <div class="p-2">
                            <input type="checkbox" name="member_id[]" value="{{$task->project->owner_id}}" {{$task->assigned_to->contains($task->project->owner) ? 'checked': ''}} class="mr-1">{{$task->project->owner->name}}
                        </div>
                        @foreach($task->project->members as $member)
                            <div class="p-2">
                                <input type="checkbox" name="member_id[]" value="{{$member->id}}" {{$task->assigned_to->contains($member) ? 'checked': ''}} class="mr-1">{{$member->name}}
                            </div>
                        @endforeach
                    </div>
                    <button class="rounded-lg text-white shadow text-sm py-2 px-5 bg-blue-400 hover:bg-blue-500">Save</button>
                </form>
            </div>
            <div class="lg:w-2/5">
                <form method="post" action="/projects/{{$task->project_id}}/tasks/{{$task->id}}/comments" class="mb-5">
                    @csrf
                    <input type="hidden" name="user_id" value="{{auth()->id()}}">
                    <label class="text-gray-500 mb-2 block">Add Comment</label>
                    <textarea name="body" rows="5" class="rounded-lg border-gray-300 w-full mb-2" placeholder="Participate now..."></textarea>
                    <button class="rounded-lg float-right text-white shadow text-sm py-2 px-5 bg-blue-400 hover:bg-blue-500">Add</button>
                </form>
                <div class="mt-10">
                    <h3 class="text-gray-500">Comments</h3>
                    <div class="w-full h-52 overflow-y-scroll pt-4 rounded">
                        @foreach($task->comments as $comment)
                            <div class="bg-gray-100 mb-2 p-3">
                                <div class="flex flex-col">
                                    <div class="flex">
                                        <div class="mr-2">
                                            <img src="https://i.pravatar.cc/32?img={{$comment->user_id}}" style="aspect-ratio: 1/1;" class="rounded-full" title="{{$comment->user->name}}">
                                        </div>
                                        <div>
                                            <h1>{{$comment->user->name}}</h1>
                                            <p class="text-sm text-gray-500">{{$comment->created_at->diffForHumans(null, true)}}</p>
                                        </div>
                                    </div>
                                    <div class="ml-10">
                                        <p>{{$comment->body}}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>


    </div>

</x-app-layout>
