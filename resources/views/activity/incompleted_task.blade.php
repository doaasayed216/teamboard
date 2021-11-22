{{$activity->user_id != auth()->user()->id ? $activity->user->name : 'You'}} incompleted '{{$activity->subject->title}}'
