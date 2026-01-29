@extends('layouts.app')

@section('title', 'Dashboard - Bali International Hospital')

@section('content')
    <style>
        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, #0066b3 0%, #004080 50%, #002855 100%);
            border-radius: var(--radius-lg);
            padding: 3rem 2rem;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            border-radius: 50%;
        }

        .hero-section::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -10%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.08) 0%, transparent 70%);
            border-radius: 50%;
        }

        .hero-content {
            position: relative;
            z-index: 1;
        }

        .hero-greeting {
            font-size: 0.875rem;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }

        .hero-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 0.5rem;
            letter-spacing: -0.025em;
        }

        .hero-subtitle {
            font-size: 1rem;
            color: rgba(255, 255, 255, 0.9);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            padding: 0.375rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.8125rem;
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .hero-badge-dot {
            width: 8px;
            height: 8px;
            background: #22c55e;
            border-radius: 50%;
            animation: pulse 2s infinite;
        }

        .hero-badge-role {
            background: rgba(255, 255, 255, 0.2);
            color: #ffffff;
            font-weight: 600;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }
        }

        /* Quick Stats */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
            margin-bottom: 2rem;
        }

        @media (max-width: 1200px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 576px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .hero-title {
                font-size: 1.75rem;
            }
        }

        .stat-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 0.75rem 1rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.2s ease;
        }

        .stat-card:hover {
            border-color: var(--ring);
            box-shadow: var(--shadow-md);
        }

        .stat-icon {
            width: 38px;
            height: 38px;
            border-radius: var(--radius);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
        }

        .stat-icon-blue {
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        }

        .stat-icon-green {
            background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
        }

        .stat-icon-amber {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        }

        .stat-icon-purple {
            background: linear-gradient(135deg, #f3e8ff 0%, #e9d5ff 100%);
        }

        .stat-content h5 {
            font-size: 0.7rem;
            font-weight: 500;
            color: var(--muted-foreground);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.1rem;
        }

        .stat-content p {
            font-size: 1rem;
            font-weight: 700;
            color: var(--foreground);
            margin: 0;
            line-height: 1.2;
        }

        .stat-value-success {
            color: #22c55e !important;
        }

        .stat-value-truncate {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 150px;
        }

        /* Section Header */
        .section-heading {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1.25rem;
        }

        .section-heading h3 {
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--foreground);
            margin: 0;
        }

        .section-heading-icon {
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Dashboard Cards Grid */
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }

        @media (max-width: 768px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
            }
        }

        .dashboard-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 1.5rem;
            display: flex;
            gap: 1.25rem;
            text-decoration: none;
            color: inherit;
            transition: all 0.25s ease;
            position: relative;
            overflow: hidden;
        }

        .dashboard-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--card-accent) 0%, transparent 100%);
            opacity: 0;
            transition: opacity 0.25s ease;
        }

        .dashboard-card:hover {
            border-color: var(--ring);
            box-shadow: var(--shadow-lg);
            transform: translateY(-4px);
            color: inherit;
        }

        .dashboard-card:hover::before {
            opacity: 1;
        }

        .dashboard-card-icon {
            width: 56px;
            height: 56px;
            border-radius: var(--radius);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            flex-shrink: 0;
            transition: transform 0.25s ease;
        }

        .dashboard-card:hover .dashboard-card-icon {
            transform: scale(1.1);
        }

        .dashboard-card-icon-stock {
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        }

        .dashboard-card-icon-monitoring {
            background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
        }

        .dashboard-card-icon-pricing {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        }

        .dashboard-card-icon-log {
            background: linear-gradient(135deg, #f3e8ff 0%, #e9d5ff 100%);
        }

        .dashboard-card-icon-settings {
            background: linear-gradient(135deg, #f4f4f5 0%, #e4e4e7 100%);
        }

        .dashboard-card-content {
            flex: 1;
            min-width: 0;
        }

        .dashboard-card-title {
            font-size: 1rem;
            font-weight: 600;
            color: var(--foreground);
            margin-bottom: 0.375rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .dashboard-card-desc {
            font-size: 0.875rem;
            color: var(--muted-foreground);
            margin: 0;
            line-height: 1.5;
        }

        .dashboard-card-arrow {
            position: absolute;
            right: 1.5rem;
            top: 50%;
            transform: translateY(-50%) translateX(-8px);
            opacity: 0;
            color: var(--muted-foreground);
            transition: all 0.25s ease;
        }

        .dashboard-card:hover .dashboard-card-arrow {
            opacity: 1;
            transform: translateY(-50%) translateX(0);
        }

        /* Button as card */
        button.dashboard-card {
            width: 100%;
            text-align: left;
            cursor: pointer;
        }

        /* Date/Time Display */
        .datetime-display {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.8125rem;
            color: rgba(255, 255, 255, 0.7);
            margin-top: 1rem;
        }

        .datetime-display svg {
            width: 16px;
            height: 16px;
        }

        /* Info Card */
        .info-card {
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
            border: 1px solid #93c5fd;
            border-radius: var(--radius-lg);
            padding: 1.25rem 1.5rem;
            margin-top: 2rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .info-card-icon {
            width: 40px;
            height: 40px;
            background: #3b82f6;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            flex-shrink: 0;
        }

        .info-card-content {
            flex: 1;
        }

        .info-card-content p {
            margin: 0;
            font-size: 0.875rem;
            color: #1e40af;
        }

        .info-card-content strong {
            font-weight: 600;
        }

        /* Loading state for Data Monitoring button */
        .dashboard-card.loading {
            pointer-events: none;
            opacity: 0.7;
        }

        .dashboard-card.loading .dashboard-card-icon {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }
    </style>

    <!-- Error Messages -->
    @if($errors->any())
        <div class="alert-shadcn alert-shadcn-destructive mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10" />
                <line x1="15" x2="9" y1="9" y2="15" />
                <line x1="9" x2="15" y1="9" y2="15" />
            </svg>
            <div>
                <div class="alert-title">Error</div>
                <ul class="alert-description mb-0 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    @if(session('success'))
        <div class="alert-shadcn alert-shadcn-success mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                <polyline points="22 4 12 14.01 9 11.01" />
            </svg>
            <div class="alert-description">{{ session('success') }}</div>
        </div>
    @endif

    <!-- Hero Section -->
    <div class="hero-section">
        <div class="hero-content">
            <p class="hero-greeting">Welcome back</p>
            <h1 class="hero-title">{{ $user['username'] }}</h1>
            <div class="hero-subtitle">
                <span class="hero-badge">
                    <span class="hero-badge-dot"></span>
                    Online
                </span>
                <span class="hero-badge hero-badge-role">
                    {{ implode(', ', user_roles_list($user)) }}
                </span>
            </div>
            <div class="datetime-display">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span id="currentDateTime"></span>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon stat-icon-blue">📊</div>
            <div class="stat-content">
                <h5>Today's Date</h5>
                <p id="todayDate">-</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon stat-icon-green">👤</div>
            <div class="stat-content">
                <h5>User Roles</h5>
                <p>{{ implode(', ', user_roles_list($user)) ?: '—' }}</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon stat-icon-amber">⚡</div>
            <div class="stat-content">
                <h5>System Status</h5>
                <p class="stat-value-success">Active</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon stat-icon-purple">🏥</div>
            <div class="stat-content">
                <h5>Logged in as</h5>
                <p class="stat-value-truncate">{{ $user['username'] }}</p>
            </div>
        </div>
    </div>

    <!-- Dashboard Cards Section -->
    <div class="section-heading">
        <div class="section-heading-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="3" width="7" height="7" />
                <rect x="14" y="3" width="7" height="7" />
                <rect x="14" y="14" width="7" height="7" />
                <rect x="3" y="14" width="7" height="7" />
            </svg>
        </div>
        <h3>Quick Access</h3>
    </div>

    <div class="dashboard-grid">
        @if(user_can_data_monitoring($user, 'adjustment_stock'))
            <!-- Adjustment Stock -->
            <a href="{{ route('stock-management.index') }}" class="dashboard-card" style="--card-accent: #3b82f6;">
                <div class="dashboard-card-icon dashboard-card-icon-stock">📦</div>
                <div class="dashboard-card-content">
                    <h4 class="dashboard-card-title">Adjustment Stock</h4>
                    <p class="dashboard-card-desc">Adjustment stock between SAP & TrakCare systems.</p>
                </div>
                <div class="dashboard-card-arrow">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 12h14" />
                        <path d="m12 5 7 7-7 7" />
                    </svg>
                </div>
            </a>
        @endif

        @if(user_can_data_monitoring($user, 'data_monitoring'))
            <!-- Data Monitoring (form POST) -->
            <form method="POST" action="{{ url('/loginmdw') }}" id="formDataMonitoring" style="margin: 0;">
                @csrf
                <button type="submit" class="dashboard-card" style="--card-accent: #22c55e;" id="btnDataMonitoring">
                    <div class="dashboard-card-icon dashboard-card-icon-monitoring">📊</div>
                    <div class="dashboard-card-content">
                        <h4 class="dashboard-card-title">Data Monitoring</h4>
                        <p class="dashboard-card-desc">Data Monitoring between TrakCare & SAP systems.</p>
                    </div>
                    <div class="dashboard-card-arrow">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 12h14" />
                            <path d="m12 5 7 7-7 7" />
                        </svg>
                    </div>
                </button>
            </form>
        @endif

        @if(user_has_role($user, 'PRICE_APPROVER'))
            <!-- Price Approvals -->
            <a href="{{ route('price-submissions.index') }}" class="dashboard-card" style="--card-accent: #8b5cf6;">
                <div class="dashboard-card-icon" style="background-color: #f3e8ff; color: #8b5cf6;">✅</div>
                <div class="dashboard-card-content">
                    <h4 class="dashboard-card-title">Price Approvals</h4>
                    <p class="dashboard-card-desc">
                        @if(isset($pendingApprovals) && $pendingApprovals > 0)
                            <span style="color: #d946ef; font-weight: bold;">{{ $pendingApprovals }} Pending Approvals</span>
                        @else
                            No pending approvals.
                        @endif
                    </p>
                </div>
                <div class="dashboard-card-arrow">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 12h14" />
                        <path d="m12 5 7 7-7 7" />
                    </svg>
                    </div>
                    </a>
        @endif
            @if(user_has_role($user, 'PRICE_ENTRY'))
                <!-- My Submissions -->
                <a href="{{ route('price-submissions.index') }}" class="dashboard-card" style="--card-accent: #3b82f6;">
                    <div class="dashboard-card-icon" style="background-color: #eff6ff; color: #3b82f6;">📝</div>
                    <div class="dashboard-card-content">
                        <h4 class="dashboard-card-title">My Submissions</h4>
                        <p class="dashboard-card-desc">View status of your submitted price changes.</p>
                    </div>
                    <div class="dashboard-card-arrow">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 12h14" />
                            <path d="m12 5 7 7-7 7" />
                        </svg>
                    </div>
                </a>
            @endif

                @if(user_can_data_monitoring($user, 'list_item_pricing'))
                <!-- List Item & Pricing -->
                <a href="{{ url('/arc-itm-mast') }}" class="dashboard-card" style="--card-accent: #f59e0b;">
                    <div class="dashboard-card-icon dashboard-card-icon-pricing">💰</div>
                    <div class="dashboard-card-content">
                        <h4 class="dashboard-card-title">List Item & Pricing</h4>
                        <p class="dashboard-card-desc">Input and manage pricing for item price.</p>
                    </div>
                    <div class="dashboard-card-arrow">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 12h14" />
                            <path d="m12 5 7 7-7 7" />
                        </svg>
                    </div>
                </a>
                @endif

                @if(user_can_data_monitoring($user, 'activity-log'))
                    <!-- Log -->
                    <a href="#" class="dashboard-card" style="--card-accent: #a855f7;">
                        <div class="dashboard-card-icon dashboard-card-icon-log">🧾</div>
                        <div class="dashboard-card-content">
                            <h4 class="dashboard-card-title">Activity Log</h4>
                            <p class="dashboard-card-desc">View log activity in this application.</p>
                        </div>
                        <div class="dashboard-card-arrow">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M5 12h14" />
                                <path d="m12 5 7 7-7 7" />
                            </svg>
                        </div>
                    </a>
                @endif

                <!-- Settings (ADMIN only) -->
                @if(user_has_role($user, 'ADMIN'))
                    <!-- Doctors Fee -->
                    <a href="{{ route('doctors-fee.index') }}" class="dashboard-card" style="--card-accent: #10b981;">
                        <div class="dashboard-card-icon" style="background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%); color: #059669;">
                            🩺
                        </div>
                        <div class="dashboard-card-content">
                            <h4 class="dashboard-card-title">Doctors Fee</h4>
                            <p class="dashboard-card-desc">View and manage doctor fees and calculations.</p>
                        </div>
                        <div class="dashboard-card-arrow">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M5 12h14" />
                                <path d="m12 5 7 7-7 7" />
                            </svg>
                        </div>
                    </a>

                    <a href="{{ route('settings') }}" class="dashboard-card" style="--card-accent: #71717a;">
                        <div class="dashboard-card-icon dashboard-card-icon-settings">⚙️</div>
                        <div class="dashboard-card-content">
                            <h4 class="dashboard-card-title">Settings</h4>
                            <p class="dashboard-card-desc">Configuration system & manage user access.</p>
                        </div>
                        <div class="dashboard-card-arrow">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M5 12h14" />
                                <path d="m12 5 7 7-7 7" />
                            </svg>
                        </div>
                    </a>
                @endif
            </div>



            <script>
                // Update date/time display
                function updateDateTime() {
                    const now = new Date();
                    const options = {
                        weekday: 'long',
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    };
                    document.getElementById('currentDateTime').textContent = now.toLocaleDateString('en-US', options);

                    const dateOptions = { month: 'short', day: 'numeric' };
                    document.getElementById('todayDate').textContent = now.toLocaleDateString('en-US', dateOptions);
                }

                updateDateTime();
                setInterval(updateDateTime, 60000); // Update every minute

                // Show loading state when clicking Data Monitoring
                const btnDataMonitoring = document.getElementById('btnDataMonitoring');
                if (btnDataMonitoring) {
                    btnDataMonitoring.addEventListener('click', function () {
                        this.classList.add('loading');
                        this.querySelector('.dashboard-card-icon').textContent = '⏳';
                        this.querySelector('.dashboard-card-title').textContent = 'Connecting...';
                        this.querySelector('.dashboard-card-desc').textContent = 'Please wait, connecting to server...';
                    });
                }
            </script>
@endsection