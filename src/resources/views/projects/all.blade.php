@extends('layouts.app')
@section('title')
    Projects
@endsection
@section('content')
    @if ($errors->any())
        @foreach ($errors->all() as $item)
            <p class="alert alert-danger">{{ $item }}</p>
        @endforeach
    @endif
    <div class="d-flex justify-content-between align-items-center w-100 mb-3">
        <h4 class="text-muted">My Projects</h4>
        {{-- <a href="{{ route('project.create') }}" class="btn btn-info text-light">New Project</a> --}}
        <button type="button" class="btn btn-info text-light" data-toggle="modal" data-target="#addNewProjectModal">
            New Project
        </button>
        
            
        <!-- Modal -->
        <div class="modal fade" id="addNewProjectModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Let's add new Thing</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @php
                            $project = new \App\Models\Project();
                            $buttonText = 'create';
                        @endphp
                        <form method="POST" class="" action="{{ route('project.store') }}">
                            @include('projects._form')
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
    @if ($projects->isEmpty())
        <p class="text-center fs-1 my-4 text-danger" style="font-size: 2rem">There is no PROJECTS yet</p>
    @else
        <div class="row">
            <div class="col-md-10 row">
                @forelse($projects as $project )
                    <div class="col-md-4  mb-2">
                        <div class="shadow h-100 bg-white py-3 bd-callout bd-callout-info pl-2 rounded"
                            style="border-left:6px solid #5bc0de">
                            <h4 id="dealing-with-specificity" class=""><a href="{{ $project->path() }}"
                                    class="text-decoration-none text-dark">{{ Str::limit($project->title, 25, '...') }}</a>
                            </h4>
                            <p class="text-muted">
                                {{ Str::limit($project->description, 250, '...') }}
                            </p>
                            @can('destroy', $project)
                                <div class="w-100 text-right">
                                    <!-- Button trigger modal -->
                                    <button type="button" class="btn" data-toggle="modal" data-target="#exampleModal">
                                        <img class="" src="{{ asset('images/delete.svg') }}" alt="delete icon">
                                    </button>
                                    <!-- Modal -->
                                    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                                        aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Delete Project</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    Are you sure you want to delete this project with all of its data ?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">cancel</button>
                                                    <form method="POST" action="{{ route('project.delete', $project->id) }}">
                                                        <button type="submit" class="btn btn-danger">Delete</button>
                                                        @csrf
                                                        @method('delete')
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endcan
                        </div>
                    </div>
                @empty
                    <h2 class="alert alert-warning">Let's add new projects and start working</h2>
    @endforelse
    </div>
    <div class="col-md-2">
        @include('projects._activities ')
    </div>
    </div>
    <div class="mt-4 d-flex justify-content-center align-items-center">{{ $projects->links() }}</div>
    @endif


@endsection
@section('js')
<script>
</script>
@endsection
