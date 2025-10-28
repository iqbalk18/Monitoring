@extends('layouts.auth')
@section('title', 'Settings - BIH')

@section('body')
<div class="container-fluid min-vh-100 d-flex flex-column bg-light">
    {{-- HEADER --}}
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm py-3 px-4">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <img src="{{ asset('images/bih_logo.png') }}" alt="BIH Logo" height="50" class="me-3">
                <h5 class="fw-bold text-dark mb-0">Bali International Hospital</h5>
            </div>

            <div class="d-flex align-items-center">
                <div class="me-4 text-end">
                    <span class="fw-semibold">User : {{ $user['username'] }}</span><br>
                    <small class="text-muted">Role : ({{ $user['role'] }})</small>
                </div>

                <form method="GET" action="{{ route('dashboard') }}"class="me-2">
                    @csrf
                    <button type="submit" class="btn btn-outline-primary btn-sm px-3">Home</button>
                </form>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-sm px-3">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    {{-- MAIN --}}
    <main class="flex-grow-1 py-5 px-4 mt-5">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="fw-bold text-dark mb-1">User Management 👥</h4>
                    <p class="text-muted mb-0">List of all registered users in the BIH system.</p>
                </div>
                <button class="btn btn-primary btn-sm px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#addUserModal">
                    <i class="bi bi-person-plus me-1"></i> Add User
                </button>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($errors->has('addUser'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ $errors->first('addUser') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">#</th>
                                    <th>Username</th>
                                    <th>Role</th>
                                    <th>Created At</th>
                                    <th>Updated</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $index => $u)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td class="fw-semibold text-dark">{{ $u->username }}</td>
                                        <td>
                                            <span class="badge bg-{{ $u->role === 'ADMIN' ? 'primary' : 'secondary' }}">
                                                {{ $u->role }}
                                            </span>
                                        </td>
                                        <td>{{ $u->created_at->format('d M Y H:i') }}</td>
                                        <td>{{ $u->updated_at->format('d M Y H:i') }}</td>
                                        <td class="text-center">
                                            {{-- Change password (opens single modal) --}}
                                            <button
                                                type="button"
                                                class="btn btn-outline-primary btn-sm me-1"
                                                data-bs-toggle="modal"
                                                data-bs-target="#changePasswordModal"
                                                data-id="{{ $u->id }}"
                                                data-username="{{ $u->username }}"
                                            >
                                                <i class="bi bi-key"></i> Change Password
                                            </button>

                                            {{-- Delete (form with DELETE) --}}
                                            <form method="POST" action="{{ route('settings.deleteUser', $u->id) }}" class="d-inline-block delete-user-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm btn-delete">
                                                    <i class="bi bi-trash"></i> Delete User
                                                </button>
                                            </form>
                                        </td>
                                    </tr>   
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-3">There are no registered users yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

    {{-- FOOTER --}}
    <footer class="text-center text-muted py-3 border-top bg-white mt-4">
        <small>© {{ date('Y') }} Bali International Hospital — IT Department</small>
    </footer>
</div>

{{-- MODAL ADD USER --}}
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content rounded-4 shadow">
          <div class="modal-header border-0">
              <h5 class="modal-title fw-bold" id="addUserModalLabel">Add New User</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body px-4 pb-4">
              <form method="POST" action="{{ route('settings.addUser') }}">
                  @csrf
                  <div class="mb-3">
                      <label class="form-label">Username</label>
                      <input type="text" name="username" class="form-control" required>
                  </div>
                  <div class="mb-3">
                      <label class="form-label">Password</label>
                      <input type="password" name="password" class="form-control" required>
                  </div>
                  <div class="mb-3">
                      <label class="form-label">Confirm Password</label>
                      <input type="password" name="password_confirmation" class="form-control" required>
                  </div>
                  <div class="mb-3">
                      <label class="form-label">Role</label>
                      <select name="role" class="form-select" required>
                          <option value="">-- Select Role --</option>
                          <option value="USER">User</option>
                          <option value="ADMIN">Admin</option>
                      </select>
                  </div>
                  <div class="text-end">
                      <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Cancel</button>
                      <button type="submit" class="btn btn-primary">Add User</button>
                  </div>
              </form>
          </div>
      </div>
  </div>
</div>

{{-- MODAL CHANGE PASSWORD (single, dynamic) --}}
<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content rounded-4 shadow">
          <div class="modal-header border-0">
              <h5 class="modal-title fw-bold" id="changePasswordModalLabel">Change Password</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body px-4 pb-4">
              {{-- action will be set dynamically with JS --}}
              <form id="changePasswordForm" method="POST" action="#">
    @csrf
    @method('POST')
    <div class="mb-3">
        <label class="form-label">New Password</label>
        <input type="password" name="password" class="form-control" required minlength="6">
    </div>
    <div class="mb-3">
        <label class="form-label">Confirm New Password</label>
        <input type="password" name="password_confirmation" class="form-control" required minlength="6">
    </div>
    <div class="text-end">
        <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">Save</button>
    </div>
</form>

          </div>
      </div>
  </div>
</div>

{{-- STYLE TAMBAHAN --}}
<style>
    main { margin-top: 90px; }

    .card { transition: all 0.25s ease; border-radius: 1rem !important; }
    .card:hover { transform: translateY(-3px); box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08); }

    .table thead th { font-weight: 600; font-size: 0.9rem; }
    .table td { vertical-align: middle; font-size: 0.9rem; }

    .badge { font-size: 0.75rem; padding: 6px 10px; border-radius: 10px; }
</style>

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
        changeTitle.textContent = 'Change Password - ' + username;
        changeForm.reset();
    });

    document.querySelectorAll('.btn-delete').forEach(function(btn){
        btn.addEventListener('click', function(e){
            e.preventDefault();
            const form = this.closest('form');
            const username = this.closest('tr').querySelector('td:nth-child(2)').innerText.trim();
            if (confirm('Delete user "' + username + '"?')) {
                form.submit();
            }
        });
    });
});
</script>
@endpush

{{-- If layout doesn't render @stack('scripts') make sure to include below bootstrap bundle: --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection
