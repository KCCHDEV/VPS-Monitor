<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>VPS System Monitor</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        <style>
            :root {
                --bg-primary: #0f1419;
                --bg-secondary: #1a1f29;
                --bg-tertiary: #252b36;
                --text-primary: #ffffff;
                --text-secondary: #94a3b8;
                --text-muted: #64748b;
                --accent-primary: #06b6d4;
                --accent-secondary: #8b5cf6;
                --success: #10b981;
                --warning: #f59e0b;
                --danger: #ef4444;
                --border-color: #334155;
                --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
                --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
                --gradient-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                --gradient-secondary: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
                --gradient-tertiary: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            }

            * {
                box-sizing: border-box;
            }

            body {
                font-family: 'Inter', sans-serif;
                background: var(--bg-primary);
                color: var(--text-primary);
                line-height: 1.6;
                overflow-x: hidden;
            }

            .navbar-brand {
                font-weight: 700;
                font-size: 1.5rem;
                background: var(--gradient-primary);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
            }

            .status-indicator {
                width: 12px;
                height: 12px;
                border-radius: 50%;
                background: var(--success);
                display: inline-block;
                margin-right: 8px;
                animation: pulse 2s infinite;
            }

            @keyframes pulse {
                0% { opacity: 1; }
                50% { opacity: 0.5; }
                100% { opacity: 1; }
            }

            .modern-card {
                background: var(--bg-secondary);
                border: 1px solid var(--border-color);
                border-radius: 12px;
                box-shadow: var(--shadow);
                transition: all 0.3s ease;
                overflow: hidden;
                position: relative;
            }

            .modern-card:hover {
                transform: translateY(-2px);
                box-shadow: var(--shadow-lg);
                border-color: var(--accent-primary);
            }

            .modern-card::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                height: 3px;
                background: var(--gradient-primary);
            }

            .card-header-modern {
                background: transparent;
                border-bottom: 1px solid var(--border-color);
                padding: 1.25rem 1.5rem;
                font-weight: 600;
                font-size: 0.95rem;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                color: var(--text-primary);
                display: flex;
                align-items: center;
                gap: 0.75rem;
            }

            .metric-value {
                font-size: 1.75rem;
                font-weight: 700;
                color: var(--text-primary);
                margin: 0;
            }

            .metric-label {
                font-size: 0.875rem;
                color: var(--text-secondary);
                margin: 0;
                text-transform: uppercase;
                letter-spacing: 0.05em;
            }

            .metric-item {
                background: var(--bg-tertiary);
                border: none !important;
                padding: 1rem 1.5rem;
                border-radius: 8px;
                margin-bottom: 0.5rem;
                transition: all 0.2s ease;
            }

            .metric-item:hover {
                background: rgba(6, 182, 212, 0.1);
                transform: translateX(4px);
            }

            .metric-item:last-child {
                margin-bottom: 0;
            }

            .usage-bar {
                height: 6px;
                background: var(--bg-primary);
                border-radius: 3px;
                overflow: hidden;
                margin-top: 0.5rem;
                position: relative;
            }

            .usage-fill {
                height: 100%;
                background: var(--gradient-tertiary);
                border-radius: 3px;
                transition: width 0.8s ease;
                position: relative;
            }

            .usage-fill::after {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
                animation: shimmer 2s infinite;
            }

            @keyframes shimmer {
                0% { transform: translateX(-100%); }
                100% { transform: translateX(100%); }
            }

            .chart-container {
                background: var(--bg-secondary);
                border: 1px solid var(--border-color);
                border-radius: 12px;
                padding: 1.5rem;
                margin-bottom: 2rem;
                box-shadow: var(--shadow);
            }

            .alert-modern {
                border: none;
                border-radius: 8px;
                padding: 1rem 1.5rem;
                margin-bottom: 1.5rem;
                background: rgba(6, 182, 212, 0.1);
                border-left: 4px solid var(--accent-primary);
                color: var(--text-primary);
            }

            .info-badge {
                background: var(--gradient-secondary);
                color: white;
                padding: 0.5rem 1rem;
                border-radius: 20px;
                font-size: 0.875rem;
                font-weight: 500;
                display: inline-block;
                margin-bottom: 1rem;
            }

            .network-item {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 0.75rem 0;
                border-bottom: 1px solid var(--border-color);
            }

            .network-item:last-child {
                border-bottom: none;
            }

            .network-label {
                font-weight: 500;
                color: var(--text-secondary);
            }

            .network-value {
                font-weight: 600;
                color: var(--text-primary);
                text-align: right;
            }

            .cpu-core {
                background: var(--bg-tertiary);
                padding: 0.75rem;
                border-radius: 6px;
                margin-bottom: 0.5rem;
                font-size: 0.85rem;
                line-height: 1.4;
            }

            .cpu-core-name {
                font-weight: 600;
                color: var(--accent-primary);
                margin-bottom: 0.25rem;
            }

            .loading-spinner {
                display: inline-block;
                width: 20px;
                height: 20px;
                border: 3px solid rgba(255,255,255,.3);
                border-radius: 50%;
                border-top-color: var(--accent-primary);
                animation: spin 1s ease-in-out infinite;
            }

            @keyframes spin {
                to { transform: rotate(360deg); }
            }

            .fade-in {
                animation: fadeIn 0.6s ease-in;
            }

            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(20px); }
                to { opacity: 1; transform: translateY(0); }
            }

            .theme-toggle {
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 1000;
                background: var(--bg-secondary);
                border: 1px solid var(--border-color);
                border-radius: 50%;
                width: 50px;
                height: 50px;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                transition: all 0.3s ease;
                box-shadow: var(--shadow);
            }

            .theme-toggle:hover {
                transform: scale(1.1);
                box-shadow: var(--shadow-lg);
            }

            .performance-indicator {
                position: relative;
                overflow: hidden;
            }

            .performance-indicator::after {
                content: '';
                position: absolute;
                top: 0;
                left: -100%;
                width: 100%;
                height: 100%;
                background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
                animation: sweep 3s infinite;
            }

            @keyframes sweep {
                0% { left: -100%; }
                100% { left: 100%; }
            }

            .metric-trend {
                font-size: 0.75rem;
                color: var(--text-muted);
                display: flex;
                align-items: center;
                gap: 0.25rem;
                margin-top: 0.25rem;
            }

            .trend-up { color: var(--danger); }
            .trend-down { color: var(--success); }
            .trend-stable { color: var(--text-secondary); }

            @media (max-width: 768px) {
                .metric-value {
                    font-size: 1.5rem;
                }
                
                .modern-card {
                    margin-bottom: 1rem;
                }
                
                .chart-container {
                    padding: 1rem;
                }

                .theme-toggle {
                    top: 10px;
                    right: 10px;
                    width: 40px;
                    height: 40px;
                }

                .navbar-brand {
                    font-size: 1.2rem;
                }

                .card-header-modern {
                    padding: 1rem;
                    font-size: 0.9rem;
                }
            }

            @media (max-width: 576px) {
                .info-badge {
                    font-size: 0.8rem;
                    padding: 0.4rem 0.8rem;
                }

                .alert-modern {
                    padding: 0.8rem 1rem;
                    font-size: 0.9rem;
                }
            }
        </style>
    </head>

    <body>
        <nav class="navbar navbar-expand-lg" style="background: var(--bg-secondary); border-bottom: 1px solid var(--border-color);">
            <div class="container-fluid">
                <span class="navbar-brand">
                    <i class="fas fa-server me-2"></i>
                    VPS System Monitor
                </span>
                <div class="d-flex align-items-center">
                    <span class="status-indicator"></span>
                    <span class="text-success me-3">Online</span>
                    <span class="badge bg-secondary">
                        <i class="fas fa-sync-alt me-1"></i>
                        <span id="refresh_time"></span>s
                    </span>
                </div>
            </div>
        </nav>

        <div class="container-fluid py-4">
            <div class="row mb-4">
                <div class="col">
                    <div class="alert-modern fade-in">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Live Monitoring:</strong> System metrics refresh automatically every <span id="refresh_time_text"></span> seconds.
                    </div>
                    <div class="info-badge fade-in" id="general_info">
                        <i class="fas fa-clock me-2"></i>
                        Loading system information...
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col">
                    <div class="chart-container fade-in">
                        <h5 class="mb-3">
                            <i class="fas fa-chart-line me-2 text-primary"></i>
                            Real-time Performance Metrics
                        </h5>
                        <section id="mainchart"></section>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-lg-3 col-md-6">
                    <div class="modern-card fade-in" id="ram">
                        <div class="card-header-modern">
                            <i class="fas fa-memory text-info"></i>
                            Memory Usage
                        </div>
                        <div class="p-3">
                            <div class="metric-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="metric-label">Used</span>
                                    <span class="usage text-danger fw-bold">0 GB</span>
                                </div>
                                <div class="usage-bar">
                                    <div class="usage-fill" style="width: 0%" id="ram-usage-bar"></div>
                                </div>
                            </div>
                            <div class="metric-item">
                                <div class="d-flex justify-content-between">
                                    <span class="metric-label">Total</span>
                                    <span class="total">0 GB</span>
                                </div>
                            </div>
                            <div class="metric-item">
                                <div class="d-flex justify-content-between">
                                    <span class="metric-label">Available</span>
                                    <span class="free text-success">0 GB</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="modern-card fade-in" id="hdd">
                        <div class="card-header-modern">
                            <i class="fas fa-hdd text-warning"></i>
                            Storage Usage
                        </div>
                        <div class="p-3">
                            <div class="metric-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="metric-label">Used</span>
                                    <span class="usage text-danger fw-bold">0 GB</span>
                                </div>
                                <div class="usage-bar">
                                    <div class="usage-fill" style="width: 0%" id="hdd-usage-bar"></div>
                                </div>
                            </div>
                            <div class="metric-item">
                                <div class="d-flex justify-content-between">
                                    <span class="metric-label">Total</span>
                                    <span class="total">0 GB</span>
                                </div>
                            </div>
                            <div class="metric-item">
                                <div class="d-flex justify-content-between">
                                    <span class="metric-label">Available</span>
                                    <span class="free text-success">0 GB</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="modern-card fade-in" id="network">
                        <div class="card-header-modern">
                            <i class="fas fa-network-wired text-success"></i>
                            Network Activity
                        </div>
                        <div class="p-3">
                            <div class="network-item">
                                <span class="network-label">
                                    <i class="fas fa-download me-2"></i>Received
                                </span>
                                <div class="network-value rec">0 bytes</div>
                            </div>
                            <div class="network-item">
                                <span class="network-label">
                                    <i class="fas fa-upload me-2"></i>Transmitted
                                </span>
                                <div class="network-value sent">0 bytes</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="modern-card fade-in">
                        <div class="card-header-modern">
                            <i class="fas fa-tachometer-alt text-primary"></i>
                            System Overview
                        </div>
                        <div class="p-3">
                            <div id="gaugeUsage"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col">
                    <div class="modern-card fade-in" id="cpu">
                        <div class="card-header-modern">
                            <i class="fas fa-microchip text-danger"></i>
                            CPU Information
                        </div>
                        <div class="p-3">
                            <div class="cpu-cores-container"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://code.highcharts.com/stock/highstock.js"></script>
        <script src="https://code.highcharts.com/highcharts-more.js"></script>
        <script src="https://code.highcharts.com/modules/solid-gauge.js"></script>
        <script type="text/javascript" src="script.js"></script>
    </body>
</html>