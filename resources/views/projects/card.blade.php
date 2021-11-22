<div class="flex flex-col bg-white p-5 rounded shadow-sm overflow-hidden" style="height: 200px">
    <div class="flex w-full items-center justify-between">
        <h3 class="font-normal w-3/4 text-xl border-l-4 py-4 -ml-5 mb-3 pl-4 border-blue-400">
            <a href="/projects/{{$project->id}}" class="w-1/4 whitespace-nowrap overflow-hidden">{{Illuminate\Support\Str::limit($project->title, 10)}}</a>
        </h3>
{{--        <div class="mb-3 bg-red-500 flex sm:bg-black md:bg-yellow-300 lg:bg-blue-700 xl:bg-pink-500">--}}
{{--            @foreach($project->members as $member)--}}
{{--                <img src="https://i.pravatar.cc/30?img={{$member->id}}" class="rounded-full mr-2" alt="{{$member->name}}" title="{{$member->name}}">--}}
{{--            @endforeach--}}
{{--        </div>--}}
    </div>


    <div class="flex-1 text-gray-500">
        {{Illuminate\Support\Str::limit($project->description, 50)}}
    </div>

    @can('manage', $project)
    <form method="post" action="/projects/{{$project->id}}">
        @csrf
        @method('delete')
        <button class="float-right text-sm text-gray-500">Delete</button>
    </form>
    @endcan
</div>
