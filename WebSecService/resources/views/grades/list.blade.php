@extends('layouts.master')
@section('title', 'List Grades')
@section('content')
@php
    use Illuminate\Support\Str;
@endphp

<div class="row my-4">
    <div class="col col-10">
        <h1>Grades</h1>
    </div>
    <div class="col col-2">
        @can('edit_exgrades')
        <a href="{{route('grades_edit')}}" class="btn btn-success form-control">Add Grade</a>
        @endcan
        <a href="{{route('grades_edit')}}" class="btn btn-success form-control">Add Grade</a>
    </div>
</div>
<form>
    <div class="row mb-4">
        <div class="col col-sm-6">
            <input name="keywords" type="text"  class="form-control" placeholder="Search Keywords" value="{{ request()->keywords }}" />
        </div>
        <div class="col col-sm-2">
            <select name="order_by" class="form-select">
                <option value="" {{ request()->order_by==""?"selected":"" }} disabled>Order By</option>
                <option value="courses.name" {{ request()->order_by=="courses.name"?"selected":"" }}>Course Name</option>
                <option value="users.name" {{ request()->order_by=="users.name"?"selected":"" }}>Student Name</option>
            </select>
        </div>
        <div class="col col-sm-2">
            <select name="order_direction" class="form-select">
                <option value="" {{ request()->order_direction==""?"selected":"" }} disabled>Order Direction</option>
                <option value="ASC" {{ request()->order_direction=="ASC"?"selected":"" }}>ASC</option>
                <option value="DESC" {{ request()->order_direction=="DESC"?"selected":"" }}>DESC</option>
            </select>
        </div>
        <div class="col col-sm-1">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
        <div class="col col-sm-1">
            <button type="reset" class="btn btn-danger">Reset</button>
        </div>
    </div>
</form>


<div class="card mt-2">
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Student</th>
                    <th scope="col">Course</th>
                    <th scope="col">Grade</th>
                    @can('show_exgrades')
                    <th scope="col">Appeal Status</th>
                    @endcan
                    <th scope="col">Freezed</th>
                    <th scope="col">Actions</th>
                    <th scope="col">Freezed</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            @foreach($grades as $grade)
            <tr>
                <td scope="col">{{$grade->user->name}}</td>
                <td scope="col">{{$grade->course->name}}</td>
                <td scope="col">{{$grade->degree}} / {{$grade->course->max_degree}}</td>
                @can('show_exgrades')
                <td scope="col">
                    @if($grade->appeal_status == 'none')
                        <span class="badge bg-secondary">No Appeal</span>
                    @elseif($grade->appeal_status == 'pending')
                        <span class="badge bg-warning">Pending</span>
                        @if($grade->appeal_reason)
                            <br><small class="text-muted">Reason: {{Str::limit($grade->appeal_reason, 50)}}</small>
                        @endif
                        @if($grade->appealed_at)
                            <br><small class="text-muted">Submitted: {{$grade->appealed_at->format('M d, Y H:i')}}</small>
                        @endif
                    @elseif($grade->appeal_status == 'approved')
                        <span class="badge bg-success">Approved</span>
                        @if($grade->appeal_responded_at)
                            <br><small class="text-muted">Approved on: {{$grade->appeal_responded_at->format('M d, Y H:i')}}</small>
                        @endif
                        @if($grade->appeal_response)
                            <br><small class="text-muted">Response: {{Str::limit($grade->appeal_response, 50)}}</small>
                        @endif
                    @elseif($grade->appeal_status == 'rejected')
                        <span class="badge bg-danger">Rejected</span>
                        @if($grade->appeal_responded_at)
                            <br><small class="text-muted">Rejected on: {{$grade->appeal_responded_at->format('M d, Y H:i')}}</small>
                        @endif
                        @if($grade->appeal_response)
                            <br><small class="text-muted">Response: {{Str::limit($grade->appeal_response, 50)}}</small>
                        @endif
                    @elseif($grade->appeal_status == 'closed')
                        <span class="badge bg-info">Appeal Closed</span>
                        <br><small class="text-muted">Grade was modified</small>
                    @endif
                </td>
                @endcan
                <td scope="col">{{$grade->freezed ? 'Yes' : 'No'}}</td>
                <td scope="col">
                    <div class="row mb-2">
                        @can('edit_exgrades')
                        <div class="col col-4">
                            <a href="{{route('grades_edit', $grade->id)}}" class="btn btn-success btn-sm">Edit</a>
                        </div>
                        @endcan
                        @can('delete_exgrades')
                        <div class="col col-4">
                            <a href="{{route('grades_delete', $grade->id)}}" class="btn btn-danger btn-sm">Delete</a>
                        </div>
                        @endcan
                        
                        <!-- Appeal Button for Students -->
                        @can('submit_grade_appeal')
                        @if($grade->user_id == auth()->id() && $grade->appeal_status == 'none')
                        <div class="col col-4">
                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#appealModal{{$grade->id}}">
                                Appeal
                            </button>
                        </div>
                        @endif
                        @endcan
                        
                        <!-- Appeal Response Button for Teachers/Managers -->
                        @can('respond_to_grade_appeal')
                        @if($grade->appeal_status == 'pending')
                        <div class="col col-4">
                            <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#respondModal{{$grade->id}}">
                                Respond
                            </button>
                        </div>
                        @endif
                        @endcan
                <td scope="col">
                    <div class="row mb-2">
                        <div class="col col-4">
                            <a href="{{route('grades_edit', $grade->id)}}" class="btn btn-success form-control">Edit</a>
                        </div>
                        <div class="col col-4">
                            <a href="{{route('grades_delete', $grade->id)}}" class="btn btn-danger form-control">Delete</a>
                        </div>
                    </div>
                </td>
            </tr>
            @endforeach
        </table>
    </div>
</div>

<!-- Appeal Modals for Students -->
@foreach($grades as $grade)
    @can('submit_grade_appeal')
    @if($grade->user_id == auth()->id() && $grade->appeal_status == 'none')
    <div class="modal fade" id="appealModal{{$grade->id}}" tabindex="-1" aria-labelledby="appealModalLabel{{$grade->id}}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="appealModalLabel{{$grade->id}}">Appeal Grade</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{route('grades_appeal_submit', $grade->id)}}" method="post">
                    @csrf
                    <div class="modal-body">
                        <p><strong>Course:</strong> {{$grade->course->name}}</p>
                        <p><strong>Current Grade:</strong> {{$grade->degree}} / {{$grade->course->max_degree}}</p>
                        <div class="mb-3">
                            <label for="appeal_reason{{$grade->id}}" class="form-label">Reason for Appeal</label>
                            <textarea class="form-control" id="appeal_reason{{$grade->id}}" name="appeal_reason" rows="4" required placeholder="Please explain why you are appealing this grade..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-warning">Submit Appeal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
    @endcan
@endforeach

<!-- Appeal Response Modals for Teachers/Managers -->
@foreach($grades as $grade)
    @can('respond_to_grade_appeal')
    @if($grade->appeal_status == 'pending')
    <div class="modal fade" id="respondModal{{$grade->id}}" tabindex="-1" aria-labelledby="respondModalLabel{{$grade->id}}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="respondModalLabel{{$grade->id}}">Respond to Appeal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{route('grades_appeal_respond', $grade->id)}}" method="post">
                    @csrf
                    <div class="modal-body">
                        <p><strong>Student:</strong> {{$grade->user->name}}</p>
                        <p><strong>Course:</strong> {{$grade->course->name}}</p>
                        <p><strong>Current Grade:</strong> {{$grade->degree}} / {{$grade->course->max_degree}}</p>
                        <p><strong>Appeal Reason:</strong></p>
                        <div class="alert alert-info">{{$grade->appeal_reason}}</div>
                        <p><strong>Appealed on:</strong> {{$grade->appealed_at->format('M d, Y H:i')}}</p>
                        
                        <div class="mb-3">
                            <label for="appeal_decision{{$grade->id}}" class="form-label">Decision</label>
                            <select class="form-select" id="appeal_decision{{$grade->id}}" name="appeal_decision" required>
                                <option value="">Select Decision</option>
                                <option value="approved">Approve Appeal</option>
                                <option value="rejected">Reject Appeal</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="appeal_response{{$grade->id}}" class="form-label">Response</label>
                            <textarea class="form-control" id="appeal_response{{$grade->id}}" name="appeal_response" rows="4" required placeholder="Please provide your response to this appeal..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Submit Response</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
    @endcan
@endforeach


@endsection