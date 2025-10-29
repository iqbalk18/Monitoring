<!DOCTYPE html>
<html lang="en">
<head>
    <title>Home - Bali International Hospital</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #5f5f5fff, #eef3f9);
            font-family: 'Segoe UI', sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .navbar {
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }

        .navbar-brand span{
            color: #292828ff !important;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .btn-outline-light {
            border-color: #ffffffcc;
        }

        .welcome-section {
            text-align: center;
            padding: 80px 20px 40px;
        }

        .welcome-section h2 {
            font-weight: 600;
            color: #004e89;
        }

        .welcome-section p {
            color: #6c757d;
            font-size: 16px;
        }

        .card-menu {
            border: none;
            border-radius: 1rem;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            background: #fff;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .card-menu:hover {
            transform: translateY(-6px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        }

        .card-icon {
            font-size: 42px;
            color: #004e89;
        }

        .card-title {
            color: #004e89;
            font-weight: 600;
        }

        .footer {
            text-align: center;
            color: #999;
            font-size: 14px;
            margin-top: auto;
            padding: 30px 0;
        }

        .footer span {
            color: #004e89;
            font-weight: 500;
        }

        @media (max-width: 992px) {
            .welcome-section {
                padding: 60px 15px 20px;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <img src="{{ asset('images/bih_logo.png') }}" alt="BIH Logo" style="height: 40px;" class="me-2">
                <span class="fw-semibold text-white">Bali International Hospital</span>
            </a>
            <div class="d-flex ms-auto">
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

<div class="container mt-5">

    {{-- Card Utama --}}
    <div class="card shadow-lg border-0">
        <div class="card-header bg-light text-black">
            <h4 class="mb-0">📂 Import Data Adjustment</h4>
        </div>
        <div class="card-body">

            @if(session('success'))
                <div class="alert alert-success">
                    ✅ {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">
                    ❌ {{ session('error') }}
                </div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>❌ {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <h5>Import File Excel</h5>
            <form action="{{ route('import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="file" class="form-label">Choose File Excel (.xlsx, .xls, .csv)</label>
                    <input type="file" name="file" class="form-control" id="file" required>
                </div>
                <button type="submit" class="btn btn-outline-primary btn-sm px-3">🚀 Import Data</button>
                <a href="{{ url('download-json') }}" class="btn btn-outline-primary btn-sm px-3">📥 Download JSON</a>
            </form>

            <hr>

            <h5>Save Data Manual</h5>
            <form action="{{ route('save_manual') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="materialDocument" class="form-label">Material Document</label>
                    <input type="text" name="materialDocument" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="materialDocumentYear" class="form-label">Material Document Year</label>
                    <input type="text" name="materialDocumentYear" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="plant" class="form-label">Plant</label>
                    <input type="text" name="plant" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="documentDate" class="form-label">Document Date</label>
                    <input type="date" name="documentDate" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="postingDate" class="form-label">Posting Date</label>
                    <input type="date" name="postingDate" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="goodMovementText" class="form-label">Good Movement Text</label>
                    <input type="text" name="goodMovementText" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="vendor" class="form-label">Vendor</label>
                    <input type="text" name="vendor" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="purchaseOrder" class="form-label">Purchase Order</label>
                    <input type="text" name="purchaseOrder" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="reservation" class="form-label">Reservation</label>
                    <input type="text" name="reservation" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="outboundDelivery" class="form-label">Outbound Delivery</label>
                    <input type="text" name="outboundDelivery" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="sapTransactionDate" class="form-label">SAP Transaction Date</label>
                    <input type="date" name="sapTransactionDate" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="sapTransactionTime" class="form-label">SAP Transaction Time</label>
                    <input type="time" name="sapTransactionTime" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="user" class="form-label">User</label>
                    <input type="text" name="user" class="form-control">
                </div>
                <button type="submit" class="btn btn-outline-success btn-sm px-3">Save</button>
            </form>

        </div>
    </div>

</div>
<footer class="footer">
    © {{ date('Y') }} <span>Bali International Hospital</span> — Developed by IT Department
</footer>
</body>
</html>
