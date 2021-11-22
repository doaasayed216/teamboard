{{$activity->user_id != auth()->user()->id ? $activity->user->name : 'You'}} completed '{{$activity->subject->title}}'
