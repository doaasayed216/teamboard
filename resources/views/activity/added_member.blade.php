@if(auth()->user()->id == $activity->user_id)
    You added {{$activity->subject->name}}
    @elseif(auth()->user()->id == $activity->subject->id)
        {{$activity->user->name}} added you
    @else
        {{$activity->user->name}} added {{$activity->subject->name}}
@endif
