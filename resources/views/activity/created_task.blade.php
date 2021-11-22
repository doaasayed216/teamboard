{{$activity->user_id != auth()->user()->id ? $activity->user->name : 'You'}} added '{{Illuminate\Support\Str::limit($activity->subject->title, 10)}}'
