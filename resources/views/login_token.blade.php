<!DOCTYPE html>
<html>
<head>
    <title>Login Manual Token - Cerebro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card mx-auto" style="max-width: 500px;">
        <div class="card-body">
            <h4 class="card-title mb-4 text-center">Login dengan Token Manual</h4>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ url('/manual-login') }}">
                @csrf
                <div class="mb-3">
                    <label>Token (dari Postman)</label>
                    <textarea name="token" class="form-control" rows="4" required>{{ old('token') }}</textarea>
                </div>

                <div class="mb-3">
                    <label>Nama User (Opsional)</label>
                    <input type="text" name="user_name" class="form-control" value="{{ old('user_name') }}">
                </div>

                <div class="mb-3">
                    <label>Sales Org (Opsional)</label>
                    <input type="text" name="sales_org" class="form-control" value="{{ old('sales_org') }}">
                </div>

                <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>

            <div class="text-center mt-3">
                <a href="{{ route('login') }}" class="btn btn-link">Kembali ke Login API</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>
