@extends('layouts.app')
@section('title')
    {{ Str::limit($project->title, 15, '...') }}
@endsection
@section('content')
@include('projects._activities')
    <div class="px-3">
        <div class="row mb-3">
            <div class="col-md-5">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-white">
                        <li class="breadcrumb-item"><a href="{{ route('project.all') }}">my projects</a></li>
                        <li class="breadcrumb-item active" aria-current="page">
                            {{ Str::limit($project->title, 25, '...') }}</li>
                    </ol>
                </nav>
                <div class="">
                    @if ($project->members->contains(auth()->id()))
                        <div class="mb-2">
                            <span class="text-dark">You are invited to this Project </span>
                        </div>
                        <div class="mb-3">
                            <span class="text-dark">Owner: <img src="{{ getAvatarUrl($project->user->email) }}"
                                    class="rounded" style="width: 25px;height:25px" title="{{ $project->user->email }}"
                                    alt="{{ $project->user->email }}" /></span>
                        </div>
                    @endif
                    <div class="mb-3">
                        <span class="text-dark">Members: </span>
                        @forelse ($project->members->except(auth()->id())->take(10) as $member)
                            <img src="{{ getAvatarUrl($project->email) }}" class="rounded" style="width: 25px;height:25px"
                                title="{{ $member->email }}" alt="{{ $member->email }}'s avatar" />
                        @empty
                            <span class="text-secondary py-1 px-2"> No members invited yet</span>
                        @endforelse
                    </div>
                </div>
            </div>
            <div class="col-md-7 ">
                <div class=" project-desc-container mb-2">
                    <div class="shadow bg-white py-4 bd-callout bd-callout-info pl-2 rounded"
                        style="border-left:6px solid #5bc0de;min-height: 100%;">
                        <h4 id="dealing-with-specificity" class=""><a href="{{ $project->path() }}"
                                class="text-decoration-none text-dark">{{ $project->title }}</a></h4>
                        <p class="text-muted">
                            {{ $project->description }}
                        </p>
                        @can('update', $project)
                            <button type="button" class="btn " data-toggle="modal" data-target="#editModal">
                                <img src="{{ asset('images/edit.svg') }}" alt="edit icon">
                            </button>
                        @endcan
                        @can('invite', $project)
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#inviteModal">
                                Invite Users
                            </button>
                        @endcan
                        
                        {{-- start edit project --}}
                        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Edit Project</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        @php
                                            $buttonText = 'edit';
                                        @endphp
                                        <form method="POST" action="{{ route('project.update', $project) }}">
                                            @method('PATCH')
                                            @include('projects._form')
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- end edit project --}}

                        {{-- start invite users to project --}}
                        <div class="modal fade" id="inviteModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title text-info" id="exampleModalLabel">Invite users and start
                                            collaborating</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form action="{{ route('project.invite', $project) }}" method="POST">
                                        <div class="modal-body">
                                            @csrf
                                            <div class="form-group">
                                                <label for="invitee" class="text-secondary">Email of the Invitee</label>
                                                <input type="text" name="email" id="invitee" class="form-control"
                                                    placeholder="person@example.com">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Invite</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        {{-- end invite users to project --}}

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="tasks-container">
        <h3 class="text-muted text-center">Tasks</h3>
        @if ($errors->any())
            @foreach ($errors->all() as $item)
                <p class="alert alert-danger">{{ $item }}</p>
            @endforeach
        @endif
        @forelse ($project->tasks as $task)
            <div class="card shadow border-right-0 border-top-0 border-bottom-0 mb-2 border-primary ">
                <div class="card-body ">
                    <form action="{{ route('task.update', [$project, $task]) }}" method="POST">
                        @method('patch')
                        @csrf
                        @if ($task->status)
                            <del>
                        @endif
                        <div class="d-flex align-items-center pl-2 ">
                            <input type="text" class="form-control border-0 " value="{{ $task->body }}" name="body"
                                placeholder="add new task ...">
                            <input type="checkbox" onChange="this.form.submit()" class="form-check-input" @if ($task->status) checked @endif name="status">
                        </div>
                        @if ($task->status)
                            </del>
                        @endif

                    </form>
                </div>
            </div>
        @empty
            <p class="alert alert-warning my-3">no tasks found yet</p>
        @endforelse
        <div class="card shadow border-right-0 border-top-0 border-bottom-0 mb-2 border-primary">
            <div class="card-body">
                <form action="{{ route('task.create', $project) }}" method="POST">
                    @csrf
                    <input type="text" class="form-control" name="body" placeholder="add new task ...">
                </form>
            </div>
        </div>
        <h4 class="text-muted mt-4 mb-3">General Notes</h4>
        <form method="POST" action="{{ route('project.update.notes', $project) }}">
            @csrf
            @method('patch')
            <textarea name="notes" cols="30" rows="3" class="form-control" placeholder="@if ($project->notes == null) Add notes about your tasks @endif" >{{ $project->notes }}</textarea>
            <button class="btn btn-info text-light mt-2 px-3" type="submit">save</button>
        </form>
    </div>
    </div>

@endsection
