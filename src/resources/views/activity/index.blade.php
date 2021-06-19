@extends('layouts.app')
@section('title')
    Activities 
@endsection
@section('content')
    <table class="table table-dark table-hover">
        <thead>
            <tr>
            <th scope="col">#</th>
            <th scope="col">Description</th>
            <th scope="col">Created_by</th>
            <th scope="col">Time</th>
            <th scope="col">Data Before</th>
            <th scope="col">Data After</th>
            <th scope="col">View</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($activities as $activity)
            <tr>
                <th scope="row">{{ $loop->index }}</th>
                <td>{{ $activity->description }}</td>
                <td>{{ $activity->getOwner->name}}</td>
                <td>{{ $activity->created_at->toFormattedDateString()}}</td>
                <td>
                    @empty($activity->data['before'])
                        No data given 
                    @else
                    <ul class=" list-group">
                        @forelse( $activity->data['before'] as $item)
                            <li class="list-group-item-dark list-group-item">{{ $item }}</li> 
                        @endforeach
                    </ul>
                    @endempty
                </td>
                <td>
                    @empty($activity->data['after'])
                        No data given 
                    @else
                    <ul class="group">
                        @forelse( $activity->data['after'] as $item)
                            <li class="list-group-item-dark list-group-item">{{ $item }}</li> 
                        @endforeach
                    </ul>
                    @endempty
                </td>
                <td>
                    @if ($activity->activitable_type=== "App\Models\Project")
                        <a href="{{ route('project.show',$activity->activitable_id) }}" class="text-decoration-none">
                    @elseif ($activity->activitable_type=== "App\Models\Task")
                        <a href="{{ route('project.show',$activity->activitable->project) }}" class="text-decoration-none">
                    @endif
                    <img src="{{ asset('images/view.svg') }}" alt="view icon"></a>
                    @if ($activity->activitable_type=== "App\Models\Project")
                        </a>
                    @elseif ($activity->activitable_type=== "App\Models\Task")
                        </a>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{-- {!! $activities->render() !!} --}}
@endsection