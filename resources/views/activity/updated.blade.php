@if(count($activity->changes['before']) == 2)
    @if(array_key_first($activity->changes['before']) == 'title')
        {{auth()->user()->id == $activity->user_id ? 'You' : $activity->user->name}} changed the project title from '{{$activity->changes['before']['title']}}' to '{{$activity->changes['after']['title']}}'
        @elseif(array_key_first($activity->changes['before']) == 'description')
        {{auth()->user()->id == $activity->user_id ? 'You' : $activity->user->name}} changed the project description
    @endif
@else
    {{auth()->user()->id == $activity->user_id ? 'You' : $activity->user->name}} updated the project details
@endif
