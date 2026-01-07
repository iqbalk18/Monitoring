@extends('layouts.app')

@section('title', 'Settings - Bali International Hospital')

@section('content')
<!-- Page Header -->
<div class="flex-between mb-4" style="flex-wrap: wrap; gap: 1rem;">
    <div>
        <h2 class="section-title">User Management</h2>
        <p class="section-desc">Manage registered users and their access in the system.</p>
    </div>
    <div class="d-flex align-items-center" style="gap: 0.5rem;">
        <span class="badge-shadcn badge-shadcn-secondary">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            {{ $user['username'] }} ({{ $user['role'] }})
        </span>
        <button type="button" class="btn-shadcn btn-shadcn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" x2="19" y1="8" y2="14"/><line x1="22" x2="16" y1="11" y2="11"/></svg>
            Add User
        </button>
    </div>
</div>

<!-- Alerts -->
<div id="alertContainer">
    @if(session('success'))
    <div class="alert-shadcn alert-shadcn-success" role="alert">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
        <div>
            <div class="alert-title">Success</div>
            <div class="alert-description">{{ session('success') }}</div>
        </div>
    </div>
    @endif
    @if($errors->has('addUser'))
    <div class="alert-shadcn alert-shadcn-destructive" role="alert">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" x2="9" y1="9" y2="15"/><line x1="9" x2="15" y1="9" y2="15"/></svg>
        <div>
            <div class="alert-title">Error</div>
            <div class="alert-description">{{ $errors->first('addUser') }}</div>
        </div>
    </div>
    @endif
    @if($errors->any() && !$errors->has('addUser'))
    <div class="alert-shadcn alert-shadcn-destructive" role="alert">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" x2="9" y1="9" y2="15"/><line x1="9" x2="15" y1="9" y2="15"/></svg>
        <div>
            <div class="alert-title">Validation Error</div>
            <ul class="alert-description mb-0 ps-3">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif
</div>

<!-- User Table -->
<div class="card-shadcn">
    <div class="card-shadcn-header flex-between">
        <div class="d-flex align-items-center" style="gap: 0.75rem;">
            <h3 class="card-shadcn-title mb-0">Registered Users</h3>
            <span class="badge-shadcn badge-shadcn-secondary">{{ count($users) }} users</span>
        </div>
    </div>
    <div class="card-shadcn-body" style="padding: 0;">
        <div class="table-container-shadcn" style="border: none; border-radius: 0;">
            <table class="table-shadcn" style="width: 100%;">
                <thead>
                    <tr>
                        <th style="width: 60px;">#</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Created At</th>
                        <th>Updated</th>
                        <th style="text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $index => $u)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <div class="d-flex align-items-center" style="gap: 0.5rem;">
                                <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--muted); display: flex; align-items: center; justify-content: center;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--muted-foreground);"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                </div>
                                <span style="font-weight: 500;">{{ $u->username }}</span>
                            </div>
                        </td>
                        <td>
                            @if($u->role === 'ADMIN')
                                <span class="badge-shadcn badge-shadcn-primary">{{ $u->role }}</span>
                            @else
                                <span class="badge-shadcn badge-shadcn-secondary">{{ $u->role }}</span>
                            @endif
                        </td>
                        <td>{{ $u->created_at->format('d M Y H:i') }}</td>
                        <td>{{ $u->updated_at->format('d M Y H:i') }}</td>
                        <td style="text-align: center;">
                            <div class="d-flex align-items-center justify-content-center" style="gap: 0.375rem;">
                                <button type="button" class="btn-shadcn btn-shadcn-outline btn-shadcn-sm" data-bs-toggle="modal" data-bs-target="#changePasswordModal" data-id="{{ $u->id }}" data-username="{{ $u->username }}" title="Change Password">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21 2-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0 3 3L22 7l-3-3m-3.5 3.5L19 4"/></svg>
                                    Password
                                </button>
                                <form method="POST" action="{{ route('settings.deleteUser', $u->id) }}" class="d-inline-block delete-user-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-shadcn btn-shadcn-destructive btn-shadcn-sm btn-delete" title="Delete User">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center" style="padding: 3rem;">
                            <div style="color: var(--muted-foreground);">
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" style="opacity: 0.5; margin-bottom: 1rem;"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="17" x2="22" y1="11" y2="11"/></svg>
                                <p class="mb-0" style="font-size: 0.875rem;">No registered users found</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal: Add User -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-content-shadcn">
            <div class="modal-header-shadcn">
                <h5 class="modal-title">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 0.5rem;"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" x2="19" y1="8" y2="14"/><line x1="22" x2="16" y1="11" y2="11"/></svg>
                    Add New User
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('settings.addUser') }}">
                @csrf
                <div class="modal-body-shadcn">
                    <div class="mb-3">
                        <label for="username" class="form-label-shadcn">Username</label>
                        <input type="text" name="username" id="username" class="form-control-shadcn" placeholder="Enter username" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label-shadcn">Password</label>
                        <input type="password" name="password" id="password" class="form-control-shadcn" placeholder="Enter password" required>
                    </div>
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label-shadcn">Confirm Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control-shadcn" placeholder="Confirm password" required>
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label-shadcn">Role</label>
                        <select name="role" id="role" class="form-select-shadcn" required>
                            <option value="">-- Select Role --</option>
                            <option value="USER">User</option>
                            <option value="ADMIN">Admin</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer-shadcn">
                    <button type="button" class="btn-shadcn btn-shadcn-outline" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-shadcn btn-shadcn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" x2="19" y1="8" y2="14"/><line x1="22" x2="16" y1="11" y2="11"/></svg>
                        Add User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Change Password -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-content-shadcn">
            <div class="modal-header-shadcn">
                <h5 class="modal-title" id="changePasswordModalLabel">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 0.5rem;"><path d="m21 2-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0 3 3L22 7l-3-3m-3.5 3.5L19 4"/></svg>
                    Change Password
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="changePasswordForm" method="POST" action="#">
                @csrf
                <div class="modal-body-shadcn">
                    <div class="mb-3">
                        <label for="new_password" class="form-label-shadcn">New Password</label>
                        <input type="password" name="password" id="new_password" class="form-control-shadcn" placeholder="Enter new password" required minlength="6">
                    </div>
                    <div class="mb-3">
                        <label for="new_password_confirmation" class="form-label-shadcn">Confirm New Password</label>
                        <input type="password" name="password_confirmation" id="new_password_confirmation" class="form-control-shadcn" placeholder="Confirm new password" required minlength="6">
                    </div>
                </div>
                <div class="modal-footer-shadcn">
                    <button type="button" class="btn-shadcn btn-shadcn-outline" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-shadcn btn-shadcn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                        Save Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const changeModal = document.getElementById('changePasswordModal');
    const changeForm = document.getElementById('changePasswordForm');
    const changeTitle = document.getElementById('changePasswordModalLabel');

    changeModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const userId = button.getAttribute('data-id');
        const username = button.getAttribute('data-username') || 'User';

        changeForm.action = "{{ url('/settings/change-password') }}/" + userId;
        // Update title with SVG icon
        changeTitle.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 0.5rem;"><path d="m21 2-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0 3 3L22 7l-3-3m-3.5 3.5L19 4"/></svg>Change Password - ' + username;
        changeForm.reset();
    });

    document.querySelectorAll('.btn-delete').forEach(function(btn){
        btn.addEventListener('click', function(e){
            e.preventDefault();
            const form = this.closest('form');
            const username = this.closest('tr').querySelector('td:nth-child(2)').innerText.trim();
            if (confirm('Delete user "' + username + '"? This action cannot be undone.')) {
                form.submit();
            }
        });
    });
});
</script>
@endpush
