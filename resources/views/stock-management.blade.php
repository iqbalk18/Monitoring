<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Stock Management - Bali International Hospital</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <style>
    body {
      background: linear-gradient(135deg, #5f5f5fff, #eef3f9);
      font-family: 'Segoe UI', sans-serif;
      min-height: 100vh;
    }

    .navbar {
      background-color: #fff;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    }

    .navbar-brand span {
      color: #292828ff !important;
      font-weight: 600;
      letter-spacing: 0.5px;
    }

    .container {
      margin-top: 30px;
      margin-bottom: 30px;
    }

    .card {
      border: none;
      border-radius: 15px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
      background: #fff;
    }

    .card-header {
      background: linear-gradient(135deg, #004e89, #0066b3);
      color: white;
      border-radius: 15px 15px 0 0 !important;
      padding: 20px;
    }

    .btn-action {
      margin: 5px;
      border-radius: 8px;
      padding: 10px 20px;
      font-weight: 500;
      transition: all 0.3s ease;
    }

    .btn-action:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .table-container {
      margin-top: 20px;
      background: white;
      border-radius: 10px;
      padding: 20px;
    }

    .modal-content {
      border-radius: 15px;
      border: none;
    }

    .modal-header {
      background: linear-gradient(135deg, #004e89, #0066b3);
      color: white;
      border-radius: 15px 15px 0 0;
    }

    .modal-header .btn-close {
      filter: brightness(0) invert(1);
    }

    .footer {
      text-align: center;
      color: #999;
      font-size: 14px;
      padding: 20px 0;
    }

    .footer span {
      color: #004e89;
      font-weight: 500;
    }

    .alert {
      border-radius: 10px;
      margin-bottom: 20px;
    }
  </style>
</head>

<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center" href="#">
        <img src="{{ asset('images/bih_logo.png') }}" alt="BIH Logo" style="height: 40px;" class="me-2">
        <span class="fw-semibold">Bali International Hospital</span>
      </a>
      <div class="d-flex ms-auto">
        <form method="GET" action="{{ route('dashboard') }}" class="me-2">
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

  <!-- Main Content -->
  <div class="container">
    <!-- Card Utama -->
    <div class="card">
      <div class="card-header">
        <h4 class="mb-0">📦 Stock Management</h4>
      </div>
      <div class="card-body">
        <!-- Alert Container -->
        <div id="alertContainer">
          @if(session('success'))
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            ✅ {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
          @endif
          @if(session('error'))
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            ❌ {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
          @endif
          @if($errors->any())
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
              @foreach($errors->all() as $error)
              <li>❌ {{ $error }}</li>
              @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
          @endif
        </div>

        <!-- Button Actions -->
        <div class="d-flex flex-wrap">
          <button type="button" class="btn btn-primary btn-action" data-bs-toggle="modal" data-bs-target="#modalImportExcel">
            📁 Import File Excel
          </button>
          <button type="button" class="btn btn-success btn-action" data-bs-toggle="modal" data-bs-target="#modalKalkulasi">
            🧮 kalkulasi
          </button>
          <button type="button" class="btn btn-info btn-action" data-bs-toggle="modal" data-bs-target="#modalDownloadJson">
            📄 Download Json
          </button>
          <button type="button" class="btn btn-secondary btn-action" data-bs-toggle="modal" data-bs-target="#modalSaveManual">
            💾 Save Data Manual
          </button>
        </div>

        <!-- Table Stock -->
        <div class="table-container">
          <h5 class="mb-3">Table stock</h5>
          <div class="table-responsive">
            <table id="stockTable" class="table table-striped table-hover">
              <thead class="table-dark">
                <tr>
                  <th>ID</th>
                  <th>Material Doc</th>
                  <th>Movement Type</th>
                  <th>Indicator</th>
                  <th>Material</th>
                  <th>Description</th>
                  <th>SLoc</th>
                  <th>Batch</th>
                  <th>Qty</th>
                  <th>UOM</th>
                  <th>Currency</th>
                  <th>Amount</th>
                  <th>Created At</th>
                </tr>
              </thead>
              <tbody>
                @foreach($stocks as $stock)
                <tr>
                  <td>{{ $stock->id }}</td>
                  <td>{{ $stock->materialDocument }}</td>
                  <td>{{ $stock->movementType }}</td>
                  <td>
                    <span class="badge bg-{{ $stock->indicator == 'SAP' ? 'primary' : 'success' }}">
                      {{ $stock->indicator }}
                    </span>
                  </td>
                  <td>{{ $stock->material }}</td>
                  <td>{{ $stock->map }}</td>
                  <td>{{ $stock->sloc }}</td>
                  <td>{{ $stock->batch }}</td>
                  <td>{{ number_format($stock->qty) }}</td>
                  <td>{{ $stock->uom }}</td>
                  <td>{{ $stock->currency }}</td>
                  <td>{{ number_format($stock->amountInLocalCurrency ?? 0, 2) }}</td>
                  <td>{{ $stock->created_at->format('d/m/Y H:i') }}</td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal 1: Import File Excel ke StockSAP/StockTCINCItmLcBt -->
  <div class="modal fade" id="modalImportExcel" tabindex="-1" aria-labelledby="modalImportExcelLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalImportExcelLabel">📁 Import File Excel</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="{{ route('import') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="modal-body">
            <div class="alert alert-info">
              <small>
                <strong>Step 1:</strong> Import file Excel ke tabel StockSAP atau StockTCINC_ItmLcBt terlebih dahulu.
              </small>
            </div>
            <div class="mb-3">
              <label for="import_type" class="form-label">Jenis Import</label>
              <select name="import_type" class="form-select" id="import_type" required>
                <option value="">-- Pilih Jenis Import --</option>
                <option value="sap">SAP (StockSAP)</option>
                <option value="trakcare">TrakCare (StockTCINC_ItmLcBt)</option>
              </select>
            </div>
            <div class="mb-3">
              <label for="file" class="form-label">Choose File Excel (.xlsx, .xls, .csv)</label>
              <input type="file" name="file" class="form-control" id="file" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary">
              <span class="spinner-border spinner-border-sm d-none" id="spinnerImportExcel" role="status" aria-hidden="true"></span>
              🚀 Import File
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal Kalkulasi -->
  <div class="modal fade" id="modalKalkulasi" tabindex="-1" aria-labelledby="modalKalkulasiLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalKalkulasiLabel">🧮 Kalkulasi Stock</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="formKalkulasi">
            <div class="mb-3">
              <label for="period_date" class="form-label">Pilih Tanggal Period</label>
              <input type="date" class="form-control" id="period_date" name="period_date" required>
            </div>
          </form>
          <hr>
          <div id="kalkulasiResult">
            <p class="text-muted">Pilih tanggal period dan klik tombol "Hitung" untuk melakukan kalkulasi stock.</p>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
          <button type="button" class="btn btn-success" id="btnKalkulasi">
            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
            Hitung
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Save Data Manual -->
  <div class="modal fade" id="modalSaveManual" tabindex="-1" aria-labelledby="modalSaveManualLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalSaveManualLabel">💾 Save Data Manual</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="{{ route('save_manual') }}" method="POST">
            @csrf
            <div class="mb-3">
              <label for="materialDocument" class="form-label">Material Document</label>
              <input type="text" name="materialDocument" class="form-control" required>
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
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
              <button type="submit" class="btn btn-success">Save</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Download JSON by Material Document -->
  <div class="modal fade" id="modalDownloadJson" tabindex="-1" aria-labelledby="modalDownloadJsonLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalDownloadJsonLabel">📄 Download JSON</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="formDownloadJson">
            <div class="mb-3">
              <label for="formstock_id" class="form-label">Pilih Material Document</label>
              <select class="form-select" id="formstock_id" name="formstock_id" required>
                <option value="">-- Pilih Material Document --</option>
                @foreach($formStocks ?? [] as $formStock)
                  <option value="{{ $formStock->id }}" data-material-doc="{{ $formStock->materialDocument }}">
                    {{ $formStock->materialDocument }} 
                    @if($formStock->materialDocumentYear)
                      ({{ $formStock->materialDocumentYear }})
                    @endif
                  </option>
                @endforeach
              </select>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="button" class="btn btn-info" id="btnDownloadJsonByMaterialDoc">
            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
            Download JSON
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Footer -->
  <footer class="footer">
    © {{ date('Y') }} <span>Bali International Hospital</span> — Developed by IT Department
  </footer>

  <!-- Scripts -->
  <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

  <script>
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    $(document).ready(function() {
      $('#stockTable').DataTable({
        order: [
          [0, 'desc']
        ],
        pageLength: 25,
        language: {
          search: "Cari:",
          lengthMenu: "Tampilkan _MENU_ data per halaman",
          info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
          infoEmpty: "Tidak ada data",
          infoFiltered: "(difilter dari _MAX_ total data)",
          paginate: {
            first: "Pertama",
            last: "Terakhir",
            next: "Selanjutnya",
            previous: "Sebelumnya"
          }
        }
      });
    });

    function showAlert(message, type = 'success') {
      const alertHtml = `
                <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    ${type === 'success' ? '✅' : '❌'} ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `;
      $('#alertContainer').html(alertHtml);

      setTimeout(() => {
        $('.alert').fadeOut();
      }, 5000);
    }


    $('#btnKalkulasi').click(function() {
      const btn = $(this);
      const spinner = btn.find('.spinner-border');
      const periodDate = $('#period_date').val();

      if (!periodDate) {
        $('#kalkulasiResult').html(`
          <div class="alert alert-warning">
            <strong>⚠️ Perhatian!</strong> Silakan pilih tanggal period terlebih dahulu.
          </div>
        `);
        return;
      }

      btn.prop('disabled', true);
      spinner.removeClass('d-none');

      $.ajax({
        url: '{{ route("stock-management.kalkulasi") }}',
        method: 'POST',
        data: {
          period_date: periodDate
        },
        success: function(response) {
           $('#kalkulasiResult').html(`
                        <div class="alert alert-success">
                            <h6><strong>✅ ${response.message}</strong></h6>
                            <div class="alert alert-info mt-2 mb-2">
                                <small><strong>📅 Period Date:</strong> ${response.data.period_date}</small>
                            </div>
                            <hr>
                            <h6>📊 Hasil Kalkulasi:</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <ul class="mb-0">
                                        <li><strong>Data SAP:</strong> ${response.data.total_sap_records.toLocaleString()}</li>
                                        <li><strong>Data TrakCare:</strong> ${response.data.total_tc_records.toLocaleString()}</li>
                                        <li><strong>Data Diproses:</strong> ${response.data.total_processed.toLocaleString()}</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <ul class="mb-0">
                                        <li><strong>Plus (P):</strong> <span class="badge bg-success">${response.data.plus_indicator.toLocaleString()}</span></li>
                                        <li><strong>Minus (M):</strong> <span class="badge bg-danger">${response.data.minus_indicator.toLocaleString()}</span></li>
                                        <li><strong>Skipped (Qty=0):</strong> <span class="badge bg-secondary">${response.data.skipped_zero.toLocaleString()}</span></li>
                                    </ul>
                                </div>
                            </div>
                            <hr>
                            <small class="text-muted">
                                <strong>Keterangan:</strong><br>
                                • <strong>Plus (P):</strong> SAP Qty > TrakCare Qty (Stock lebih di SAP)<br>
                                • <strong>Minus (M):</strong> SAP Qty < TrakCare Qty (Stock kurang di SAP)<br>
                                • <strong>Skipped:</strong> Tidak ada selisih (SAP Qty = TrakCare Qty)
                            </small>
                        </div>
                    `);
         },
        error: function(xhr) {
          const message = xhr.responseJSON?.message || 'Terjadi kesalahan saat kalkulasi';
          $('#kalkulasiResult').html(`
                        <div class="alert alert-danger">
                            ${message}
                        </div>
                    `);
        },
        complete: function() {
          btn.prop('disabled', false);
          spinner.addClass('d-none');
        }
      });
    });

    $('#btnDownloadJsonByMaterialDoc').click(function() {
      const btn = $(this);
      const spinner = btn.find('.spinner-border');
      const formstockId = $('#formstock_id').val();
      const materialDoc = $('#formstock_id option:selected').data('material-doc');

      if (!formstockId || !materialDoc) {
        showAlert('Silakan pilih Material Document terlebih dahulu!', 'warning');
        return;
      }

      btn.prop('disabled', true);
      spinner.removeClass('d-none');

      const form = $('<form>', {
        'method': 'POST',
        'action': '{{ route("stock-management.download-json") }}'
      });

      form.append($('<input>', {
        'type': 'hidden',
        'name': '_token',
        'value': '{{ csrf_token() }}'
      }));

      form.append($('<input>', {
        'type': 'hidden',
        'name': 'materialDocument',
        'value': materialDoc
      }));

      $('body').append(form);
      form.submit();
      form.remove();

      setTimeout(function() {
        $('#modalDownloadJson').modal('hide');
        btn.prop('disabled', false);
        spinner.addClass('d-none');
        $('#formDownloadJson')[0].reset();
        showAlert('File JSON sedang diunduh...', 'info');
      }, 500);
    });

    $('#modalDownloadJson').on('hidden.bs.modal', function() {
      $('#formDownloadJson')[0].reset();
      $('#btnDownloadJsonByMaterialDoc').prop('disabled', false);
      $('#btnDownloadJsonByMaterialDoc .spinner-border').addClass('d-none');
    });

    $('#modalKalkulasi').on('hidden.bs.modal', function() {
      $('#kalkulasiResult').html('<p class="text-muted">Pilih tanggal period dan klik tombol "Hitung" untuk melakukan kalkulasi stock.</p>');
      $('#formKalkulasi')[0].reset();
    });

    $('#modalKalkulasi').on('shown.bs.modal', function() {
      if (!$('#period_date').val()) {
        const today = new Date().toISOString().split('T')[0];
        $('#period_date').val(today);
      }
    });

    $('#modalImportExcel form').on('submit', function() {
      $('#spinnerImportExcel').removeClass('d-none');
      $(this).find('button[type="submit"]').prop('disabled', true);
    });
  </script>
</body>

</html>