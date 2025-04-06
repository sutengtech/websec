@extends('layouts.master')
@section('title', auth()->user()->hasRole('Employee') ? 'Customers' : 'Users')
@section('content')
<div class="row align-items-center mt-4 mb-4">
    <div class="col">
        <h2 class="fw-bold text-primary mb-0">
            <i class="bi bi-people me-2"></i>{{ auth()->user()->hasRole('Employee') ? 'Customers' : 'Users' }}
        </h2>
    </div>
    @can('admin_users')
    <div class="col-auto">
        <a href="{{ route('users_add') }}" class="btn btn-primary rounded-pill shadow-sm">
            <i class="bi bi-person-plus me-1"></i> Add User
        </a>
    </div>
    @endcan
</div>

<div class="card shadow-sm border-0 rounded-3 mb-4">
    <div class="card-body p-3">
        <form id="searchForm" action="{{ route('users') }}" method="GET">
            <div class="row g-2 align-items-center">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-end-0">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input name="keywords" type="text" class="form-control border-start-0" 
                               placeholder="Search by name or email" value="{{ request()->keywords }}" />
                    </div>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary rounded-pill px-4">
                        <i class="bi bi-funnel me-1"></i> Filter
                    </button>
                </div>
                <div class="col-auto">
                    <a href="{{ route('users') }}" class="btn btn-outline-secondary rounded-pill">
                        <i class="bi bi-x-circle me-1"></i> Reset
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm border-0 rounded-3">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="bg-light">
          <tr>
            <th scope="col" class="ps-4" style="width: 60px;">#</th>
            <th scope="col">Name</th>
            <th scope="col">Email</th>
            <th scope="col">Roles</th>
            @if(auth()->user()->hasRole('Employee') || auth()->user()->hasRole('Admin'))
            <th scope="col">Credit</th>
            @endif
            <th scope="col" class="text-end pe-4">Actions</th>
          </tr>
        </thead>
        <tbody>
        @foreach($users as $user)
        <tr>
          <td class="ps-4 fw-medium text-secondary">{{$user->id}}</td>
          <td>
            <div class="d-flex align-items-center">
              <div class="avatar-circle bg-light-primary text-primary me-2">
                {{ strtoupper(substr($user->name, 0, 1)) }}
              </div>
              <span class="fw-medium">{{$user->name}}</span>
            </div>
          </td>
          <td>
            <div class="d-flex align-items-center">
              <i class="bi bi-envelope text-muted me-2 small"></i>
              {{$user->email}}
            </div>
          </td>
          <td>
            @foreach($user->roles as $role)
              <span class="badge rounded-pill {{ $role->name == 'Admin' ? 'bg-danger' : ($role->name == 'Employee' ? 'bg-warning text-dark' : 'bg-info text-dark') }} me-1">
                <i class="bi {{ $role->name == 'Admin' ? 'bi-shield-lock' : ($role->name == 'Employee' ? 'bi-person-badge' : 'bi-person') }} me-1 small"></i>
                {{$role->name}}
              </span>
            @endforeach
          </td>
          @if(auth()->user()->hasRole('Employee') || auth()->user()->hasRole('Admin'))
          <td>
            @if($user->hasRole('Customer'))
              <div class="d-flex align-items-center">
                <span class="fw-medium {{ $user->credit > 0 ? 'text-success' : 'text-muted' }}">${{ number_format($user->credit, 2) }}</span>
                @can('manage_customer_credit')
                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill ms-2" data-bs-toggle="modal" data-bs-target="#creditModal{{ $user->id }}">
                  <i class="bi bi-plus-circle me-1"></i> Add
                </button>
                @endcan
              </div>
            @else
              <span class="text-muted fst-italic">N/A</span>
            @endif
          </td>
          @endif
          <td class="text-end pe-4">
            <div class="btn-group">
              @can('edit_users')
              <a class="btn btn-sm btn-outline-primary" href='{{route('users_edit', [$user->id])}}' title="Edit User">
                <i class="bi bi-pencil"></i>
              </a>
              @endcan
              @can('admin_users')
              <a class="btn btn-sm btn-outline-secondary" href='{{route('edit_password', [$user->id])}}' title="Change Password">
                <i class="bi bi-key"></i>
              </a>
              @endcan
              @can('delete_users')
              <a class="btn btn-sm btn-outline-danger" href="javascript:void(0);" onclick="showDeleteConfirmation('{{$user->id}}', '{{$user->name}}', '{{route('users_delete', [$user->id])}}')" title="Delete User">
                <i class="bi bi-trash"></i>
              </a>
              @endcan
            </div>
          </td>
        </tr>
        @endforeach
        </tbody>
      </table>
    </div>
  </div>
  
  @if(count($users) == 0)
  <div class="text-center p-5">
    <div class="text-muted mb-3">
      <i class="bi bi-people fs-1"></i>
    </div>
    <h5 class="text-muted">No users found</h5>
    <p class="text-muted small">Try adjusting your search criteria</p>
  </div>
  @endif
</div>

<!-- Credit Update Modals -->
@can('manage_customer_credit')
    @foreach($users as $user)
        @if($user->hasRole('Customer'))
        <div class="modal fade" id="creditModal{{ $user->id }}" tabindex="-1" aria-labelledby="creditModalLabel{{ $user->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow">
                    <form action="{{ route('add_credit', ['user' => $user->id]) }}" method="POST">
                        @csrf
                        <div class="modal-header border-bottom-0 pb-0">
                            <h5 class="modal-title fw-bold" id="creditModalLabel{{ $user->id }}">
                                <i class="bi bi-wallet2 text-primary me-2"></i>Add Credit
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body pt-0">
                            <p class="text-muted mb-4">Adding credit to <span class="fw-medium">{{ $user->name }}</span>'s account</p>
                            
                            <div class="mb-4">
                                <label for="creditAmount{{ $user->id }}" class="form-label fw-medium">Amount to Add</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-currency-dollar"></i></span>
                                    <button type="button" class="btn btn-light border" onclick="decrementCredit('creditAmount{{ $user->id }}')">
                                        <i class="bi bi-dash"></i>
                                    </button>
                                    <input type="number" class="form-control text-center border-start-0 border-end-0" id="creditAmount{{ $user->id }}" 
                                           name="amount" min="0.01" step="any" value="50" required
                                           data-current-credit="{{ $user->credit }}">
                                    <button type="button" class="btn btn-light border" onclick="incrementCredit('creditAmount{{ $user->id }}')">
                                        <i class="bi bi-plus"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="card bg-light-info border-0 mb-4">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <span class="text-muted">Current Balance</span>
                                            <h5 class="mb-0">${{ number_format($user->credit, 2) }}</h5>
                                        </div>
                                        <div class="text-end">
                                            <span class="text-muted">New Balance</span>
                                            <h5 class="mb-0 text-primary" id="newBalance{{ $user->id }}">${{ number_format($user->credit + 50, 2) }}</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Quick amount buttons -->
                            <div class="text-muted small mb-2">Quick amounts:</div>
                            <div class="d-flex flex-wrap gap-2">
                                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill" onclick="setAmount('creditAmount{{ $user->id }}', 10)">$10</button>
                                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill" onclick="setAmount('creditAmount{{ $user->id }}', 20)">$20</button>
                                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill" onclick="setAmount('creditAmount{{ $user->id }}', 50)">$50</button>
                                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill" onclick="setAmount('creditAmount{{ $user->id }}', 100)">$100</button>
                                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill" onclick="setAmount('creditAmount{{ $user->id }}', 200)">$200</button>
                            </div>
                        </div>
                        <div class="modal-footer border-top-0 pt-1">
                            <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary rounded-pill px-4">
                                <i class="bi bi-check2 me-1"></i> Add Credit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif
    @endforeach
@endcan

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light border-bottom-0">
                <h5 class="modal-title fw-bold" id="deleteConfirmationModalLabel">
                    <i class="bi bi-question-circle text-primary me-2"></i>
                    Confirm User Removal
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-0">
                <p class="mb-1">Would you like to remove user <span id="deleteUserName" class="fw-medium"></span> from the system?</p>
                <p class="text-muted small">You can add them back later if needed.</p>
            </div>
            <div class="modal-footer border-top-0">
                <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">
                    <i class="bi bi-x me-1"></i> Cancel
                </button>
                <a href="#" id="confirmDeleteBtn" class="btn btn-outline-danger rounded-pill">
                    <i class="bi bi-trash me-1"></i> Remove User
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    .avatar-circle {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        font-weight: 500;
    }
    
    .bg-light-primary {
        background-color: rgba(13, 110, 253, 0.15);
    }
    
    .bg-light-info {
        background-color: rgba(13, 202, 240, 0.1);
    }
</style>

<script>
    // JavaScript functions for credit amount handling
    document.addEventListener('DOMContentLoaded', function() {
        // Set up listeners for all credit amount inputs
        const creditInputs = document.querySelectorAll('[id^="creditAmount"]');
        creditInputs.forEach(input => {
            input.addEventListener('input', function() {
                updateBalance(this.id);
            });
            
            // Initialize balance calculation
            updateBalance(input.id);
        });
    });
    
    function updateBalance(inputId) {
        const userId = inputId.replace('creditAmount', '');
        const input = document.getElementById(inputId);
        const currentBalance = parseFloat(input.getAttribute('data-current-credit')) || 0;
        const additionalCredit = parseFloat(input.value) || 0;
        const newTotal = currentBalance + additionalCredit;
        
        document.getElementById('newBalance' + userId).textContent = '$' + newTotal.toFixed(2);
    }
    
    function incrementCredit(inputId) {
        const input = document.getElementById(inputId);
        input.value = (parseFloat(input.value) || 0) + 10;
        updateBalance(inputId);
    }
    
    function decrementCredit(inputId) {
        const input = document.getElementById(inputId);
        let newValue = (parseFloat(input.value) || 0) - 10;
        input.value = newValue <= 0 ? 0.01 : newValue.toFixed(2);
        updateBalance(inputId);
    }
    
    function setAmount(inputId, amount) {
        document.getElementById(inputId).value = amount;
        updateBalance(inputId);
    }
    
    // Delete confirmation with modal
    function showDeleteConfirmation(userId, userName, deleteUrl) {
        // Set user name in modal
        document.getElementById('deleteUserName').textContent = userName;
        
        // Set delete button href
        const deleteBtn = document.getElementById('confirmDeleteBtn');
        deleteBtn.setAttribute('href', deleteUrl);
        
        // Show the modal
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteConfirmationModal'));
        deleteModal.show();
    }
</script>

@endsection
