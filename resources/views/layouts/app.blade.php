<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Campus Lost & Found Tracker</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8ec 100%);
            min-height: 100vh;
            line-height: 1.6;
        }
        
        /* Navigation Bar */
        .navbar {
            background: linear-gradient(135deg, #2c3e50 0%, #1a252f 100%);
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .navbar h1 {
            font-size: 24px;
            font-weight: 600;
        }
        
        .navbar-links {
            display: flex;
            gap: 10px;
        }
        
        .navbar a {
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 25px;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        
        .navbar a:hover {
            background-color: #3498db;
            transform: translateY(-2px);
        }
        
        /* Container */
        .container {
            max-width: 1300px;
            margin: 30px auto;
            padding: 0 20px;
        }
        
        /* Cards */
        .card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            padding: 25px;
            margin-bottom: 25px;
        }
        
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .card h2 {
            color: #2c3e50;
            font-size: 22px;
            border-left: 4px solid #3498db;
            padding-left: 15px;
        }
        
        /* Search Card */
        .search-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 25px;
        }
        
        .search-row {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        
        .search-input-group {
            display: flex;
            gap: 10px;
        }
        
        .search-input {
            flex: 1;
            padding: 15px 20px;
            border: none;
            border-radius: 30px;
            font-size: 16px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .search-input:focus {
            outline: none;
            box-shadow: 0 4px 20px rgba(0,0,0,0.2);
        }
        
        .search-btn {
            padding: 15px 30px;
            border-radius: 30px;
            font-weight: 600;
        }
        
        .filter-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        .btn-filter {
            background: rgba(255,255,255,0.2);
            color: white;
            padding: 10px 20px;
            border-radius: 25px;
            text-decoration: none;
            transition: all 0.3s ease;
            font-weight: 500;
            border: 2px solid rgba(255,255,255,0.3);
        }
        
        .btn-filter:hover, .btn-filter.active {
            background: white;
            color: #667eea;
            transform: translateY(-2px);
        }
        
        /* Statistics Cards */
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 25px;
        }
        
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            display: flex;
            align-items: center;
            gap: 20px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            transition: transform 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-icon {
            font-size: 40px;
        }
        
        .stat-info h3 {
            font-size: 32px;
            font-weight: 700;
            line-height: 1;
        }
        
        .stat-info p {
            color: #7f8c8d;
            font-size: 14px;
            margin-top: 5px;
        }
        
        .stat-lost .stat-info h3 { color: #e74c3c; }
        .stat-found .stat-info h3 { color: #27ae60; }
        .stat-claimed .stat-info h3 { color: #9b59b6; }
        .stat-total .stat-info h3 { color: #3498db; }
        
        /* Items Grid */
        .items-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        
        .item-card {
            background: #fafbfc;
            border-radius: 12px;
            padding: 20px;
            border: 1px solid #eee;
            transition: all 0.3s ease;
        }
        
        .item-card:hover {
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            transform: translateY(-3px);
        }
        
        .item-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }
        
        .item-date {
            color: #95a5a6;
            font-size: 12px;
        }
        
        .item-name {
            font-size: 18px;
            color: #2c3e50;
            margin-bottom: 8px;
            font-weight: 600;
        }
        
        .item-description {
            color: #7f8c8d;
            font-size: 14px;
            margin-bottom: 15px;
            line-height: 1.5;
        }
        
        .item-details {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 15px;
        }
        
        .detail {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 8px;
            font-size: 14px;
            color: #555;
        }
        
        .detail:last-child {
            margin-bottom: 0;
        }
        
        .detail-icon {
            font-size: 16px;
        }
        
        .item-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        /* Status Badges */
        .status-badge {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .status-lost {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: white;
        }
        
        .status-found {
            background: linear-gradient(135deg, #27ae60 0%, #1e8449 100%);
            color: white;
        }
        
        .status-claimed {
            background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%);
            color: white;
        }
        
        /* Buttons */
        .btn {
            display: inline-block;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(52, 152, 219, 0.4);
        }
        
        .btn-success {
            background: linear-gradient(135deg, #27ae60 0%, #1e8449 100%);
            color: white;
        }
        
        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(39, 174, 96, 0.4);
        }
        
        .btn-claim {
            background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%);
            color: white;
            padding: 8px 16px;
            font-size: 13px;
        }
        
        .btn-claim:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(155, 89, 182, 0.4);
        }
        
        .btn-delete {
            background: #f8f9fa;
            color: #e74c3c;
            padding: 8px 16px;
            font-size: 13px;
            border: 1px solid #eee;
        }
        
        .btn-delete:hover {
            background: #e74c3c;
            color: white;
        }
        
        .claimed-text {
            color: #9b59b6;
            font-weight: 600;
            font-size: 13px;
        }
        
        /* Alerts */
        .alert {
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-weight: 500;
        }
        
        .alert-success {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            color: #155724;
            border-left: 4px solid #27ae60;
        }
        
        .alert-danger {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            color: #721c24;
            border-left: 4px solid #e74c3c;
        }
        
        /* Filter Info */
        .filter-info {
            background: #f8f9fa;
            padding: 12px 20px;
            border-radius: 8px;
            margin-bottom: 15px;
            font-size: 14px;
            color: #555;
        }
        
        .filter-tag {
            background: #667eea;
            color: white;
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .clear-filters {
            color: #e74c3c;
            margin-left: 15px;
            font-weight: 500;
        }
        
        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }
        
        .empty-icon {
            font-size: 60px;
            margin-bottom: 20px;
        }
        
        .empty-state h3 {
            color: #2c3e50;
            margin-bottom: 10px;
        }
        
        .empty-state p {
            color: #7f8c8d;
            margin-bottom: 25px;
        }
        
        /* Form Styles */
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #2c3e50;
        }
        
        .form-control {
            width: 100%;
            padding: 14px 18px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 15px;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 0 4px rgba(52, 152, 219, 0.1);
        }
        
        textarea.form-control {
            min-height: 120px;
            resize: vertical;
        }
        
        select.form-control {
            cursor: pointer;
        }
        
        .error-message {
            color: #e74c3c;
            font-size: 13px;
            margin-top: 5px;
            display: block;
        }
        
        /* Footer */
        footer {
            text-align: center;
            padding: 25px;
            background: linear-gradient(135deg, #2c3e50 0%, #1a252f 100%);
            color: white;
            margin-top: 50px;
        }
        
        footer p {
            opacity: 0.9;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }
            
            .search-input-group {
                flex-direction: column;
            }
            
            .filter-buttons {
                justify-content: center;
            }
            
            .items-grid {
                grid-template-columns: 1fr;
            }
            
            .card-header {
                flex-direction: column;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <h1>🔍 Campus Lost & Found</h1>
        <div class="navbar-links">
            <a href="{{ route('items.index') }}">🏠 Home</a>
            <a href="{{ route('items.create') }}">📝 Report Item</a>
        </div>
    </nav>
    
    <div class="container">
        @yield('content')
    </div>
    
    <footer>
        <p>&copy; {{ date('Y') }} Campus Lost & Found Tracker | Developed by Kamwanga Rahiim</p>
    </footer>
</body>
</html>
