{{$activity->user_id != auth()->user()->id ? $activity->user->name : 'You'}} created '{{Illuminate\Support\Str::limit($activity->project->title, 20)}}'
