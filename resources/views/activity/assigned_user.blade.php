@foreach($activity->subject->assigned_to as $user)
    {{$activity->user_id != auth()->user()->id ? $activity->user->name : 'You'}} assigned '{{Illuminate\Support\Str::limit($activity->subject->title, 10)}}' to {{$user->name}} <br>
@endforeach

