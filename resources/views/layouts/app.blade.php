<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'منصة تتبع مشاريع التخرج') | GradTrack</title>
    
    <!-- Google Fonts: Cairo & Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800;900&family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- FontAwesome for modern icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        :root {
            --bg-body: #0b1120;
            --bg-surface: #131c31;
            --bg-surface-elevated: #1a2540;
            --bg-card: rgba(22, 33, 58, 0.75);
            --border-color: rgba(255, 255, 255, 0.08);
            --border-hover: rgba(56, 189, 248, 0.3);
            
            --primary: #38bdf8;
            --primary-hover: #0ea5e9;
            --primary-glow: rgba(56, 189, 248, 0.25);
            
            --secondary: #818cf8;
            --accent: #f43f5e;
            --success: #10b981;
            --warning: #f59e0b;
            --purple: #a855f7;
            
            --text-primary: #f8fafc;
            --text-secondary: #94a3b8;
            --text-muted: #64748b;
            
            --radius-sm: 8px;
            --radius-md: 14px;
            --radius-lg: 20px;
            --shadow-card: 0 10px 30px -5px rgba(0, 0, 0, 0.5), 0 0 1px 1px rgba(255, 255, 255, 0.05);
            --transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Cairo', sans-serif;
            background-color: var(--bg-body);
            color: var(--text-primary);
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background-image: 
                radial-gradient(circle at 15% 20%, rgba(56, 189, 248, 0.08) 0%, transparent 40%),
                radial-gradient(circle at 85% 80%, rgba(129, 140, 248, 0.08) 0%, transparent 40%),
                radial-gradient(circle at 50% 50%, rgba(15, 23, 42, 0.5) 0%, transparent 100%);
            background-attachment: fixed;
        }

        /* Top Navigation */
        .navbar {
            background: rgba(19, 28, 49, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--border-color);
            position: sticky;
            top: 0;
            z-index: 50;
            padding: 0.85rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .brand-container {
            display: flex;
            align-items: center;
            gap: 1rem;
            text-decoration: none;
        }

        .brand-logo {
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, #0ea5e9, #6366f1);
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.35rem;
            box-shadow: 0 0 20px var(--primary-glow);
        }

        .brand-text h1 {
            font-size: 1.25rem;
            font-weight: 800;
            background: linear-gradient(to left, #ffffff, #93c5fd);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: -0.5px;
        }

        .brand-text span {
            font-size: 0.75rem;
            color: var(--text-secondary);
            display: block;
            margin-top: -3px;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 1.25rem;
            list-style: none;
        }

        .nav-link {
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.95rem;
            font-weight: 600;
            padding: 0.5rem 0.9rem;
            border-radius: var(--radius-sm);
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .nav-link:hover, .nav-link.active {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--border-color);
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .role-badge {
            font-size: 0.75rem;
            font-weight: 700;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            text-transform: uppercase;
        }

        .role-badge.supervisor {
            background: rgba(168, 85, 247, 0.2);
            color: #d8b4fe;
            border: 1px solid rgba(168, 85, 247, 0.4);
        }

        .role-badge.student {
            background: rgba(56, 189, 248, 0.2);
            color: #7dd3fc;
            border: 1px solid rgba(56, 189, 248, 0.4);
        }

        .role-badge.admin {
            background: rgba(16, 185, 129, 0.2);
            color: #6ee7b7;
            border: 1px solid rgba(16, 185, 129, 0.4);
        }

        .quick-switch-dropdown {
            position: relative;
            display: inline-block;
        }

        .btn {
            font-family: inherit;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.6rem 1.25rem;
            font-size: 0.95rem;
            font-weight: 600;
            border-radius: var(--radius-sm);
            cursor: pointer;
            text-decoration: none;
            transition: var(--transition);
            border: 1px solid transparent;
        }

        .btn-primary {
            background: linear-gradient(135deg, #0ea5e9, #3b82f6);
            color: white;
            box-shadow: 0 4px 14px var(--primary-glow);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px var(--primary-glow);
        }

        .btn-outline {
            background: rgba(255, 255, 255, 0.04);
            border-color: var(--border-color);
            color: var(--text-primary);
        }

        .btn-outline:hover {
            background: rgba(255, 255, 255, 0.08);
            border-color: var(--primary);
        }

        .btn-sm {
            padding: 0.35rem 0.75rem;
            font-size: 0.85rem;
        }

        /* Container */
        .container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 2rem 1.5rem;
            width: 100%;
            flex: 1;
        }

        /* Flash messages */
        .alert {
            padding: 1rem 1.25rem;
            border-radius: var(--radius-sm);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.85rem;
            font-weight: 500;
            animation: fadeIn 0.3s ease;
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.15);
            border: 1px solid rgba(16, 185, 129, 0.4);
            color: #6ee7b7;
        }

        .alert-error {
            background: rgba(244, 63, 94, 0.15);
            border: 1px solid rgba(244, 63, 94, 0.4);
            color: #fda4af;
        }

        .alert-info {
            background: rgba(56, 189, 248, 0.15);
            border: 1px solid rgba(56, 189, 248, 0.4);
            color: #7dd3fc;
        }

        /* Cards */
        .card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            padding: 1.5rem;
            backdrop-filter: blur(12px);
            box-shadow: var(--shadow-card);
            transition: var(--transition);
        }

        .card:hover {
            border-color: var(--border-hover);
        }

        /* Status Badges */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.8rem;
            font-weight: 700;
            padding: 0.35rem 0.85rem;
            border-radius: 9999px;
        }

        .status-approved {
            background: rgba(16, 185, 129, 0.15);
            color: #34d399;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .status-submitted {
            background: rgba(56, 189, 248, 0.15);
            color: #38bdf8;
            border: 1px solid rgba(56, 189, 248, 0.3);
        }

        .status-needs_revision {
            background: rgba(245, 158, 11, 0.15);
            color: #fbbf24;
            border: 1px solid rgba(245, 158, 11, 0.3);
        }

        .status-pending {
            background: rgba(148, 163, 184, 0.15);
            color: #94a3b8;
            border: 1px solid rgba(148, 163, 184, 0.3);
        }

        .status-rejected {
            background: rgba(244, 63, 94, 0.15);
            color: #fb7185;
            border: 1px solid rgba(244, 63, 94, 0.3);
        }

        /* Footer */
        .footer {
            background: var(--bg-surface);
            border-top: 1px solid var(--border-color);
            padding: 2rem;
            margin-top: auto;
            text-align: center;
            font-size: 0.9rem;
            color: var(--text-muted);
        }

        .footer-credits {
            display: flex;
            justify-content: center;
            gap: 2rem;
            flex-wrap: wrap;
            margin-top: 0.75rem;
            font-size: 0.85rem;
            color: var(--text-secondary);
        }

        .badge-academic {
            background: rgba(255, 255, 255, 0.05);
            padding: 0.25rem 0.75rem;
            border-radius: var(--radius-sm);
            border: 1px solid var(--border-color);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 768px) {
            .navbar {
                padding: 0.75rem 1rem;
                flex-direction: column;
                gap: 1rem;
            }
            .nav-links {
                flex-wrap: wrap;
                justify-content: center;
            }
            .container {
                padding: 1rem;
            }
        }
    </style>
    @stack('styles')
</head>
<body>

    <!-- Header / Navbar -->
    <header class="navbar">
        <a href="{{ route('dashboard') }}" class="brand-container">
            <div class="brand-logo">
                <i class="fa-solid fa-diagram-project"></i>
            </div>
            <div class="brand-text">
                <h1>GradTrack</h1>
                <span>منصة إدارة مشاريع التخرج والهندسة البرمجية</span>
            </div>
        </a>

        @auth
        <nav>
            <ul class="nav-links">
                <li>
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="fa-solid fa-chart-pie"></i> لوحة التحكم
                    </a>
                </li>
                <li>
                    <a href="{{ route('edge-cases.index') }}" class="nav-link {{ request()->routeIs('edge-cases.index') ? 'active' : '' }}" style="color: #38bdf8;">
                        <i class="fa-solid fa-shield-halved"></i> معمل حالات الحافة (Edge Cases)
                    </a>
                </li>
            </ul>
        </nav>

        <div class="user-menu">
            <!-- Quick Demo User Switcher (For live evaluation defense) -->
            <div style="display: flex; align-items: center; gap: 0.5rem; background: rgba(0,0,0,0.3); padding: 0.3rem 0.6rem; border-radius: var(--radius-sm); border: 1px solid var(--border-color);">
                <span style="font-size: 0.75rem; color: var(--text-muted);"><i class="fa-solid fa-users-viewfinder"></i> تبديل الحساب للمناقشة:</span>
                <a href="{{ route('quick-login', 1) }}" class="btn btn-outline btn-sm" title="المهندس ساهر الهمداني" style="{{ auth()->id() == 1 ? 'border-color: #a855f7; color: #d8b4fe;' : '' }}">
                    م. ساهر (مشرف)
                </a>
                <a href="{{ route('quick-login', 2) }}" class="btn btn-outline btn-sm" title="إلياس جمال الساده" style="{{ auth()->id() == 2 ? 'border-color: #38bdf8; color: #7dd3fc;' : '' }}">
                    إلياس (طالب)
                </a>
            </div>

            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <div style="text-align: left;">
                    <div style="font-weight: 700; font-size: 0.9rem;">{{ auth()->user()->name }}</div>
                    <span class="role-badge {{ auth()->user()->role }}">
                        {{ auth()->user()->role === 'supervisor' ? 'مشرف أكاديمي' : (auth()->user()->role === 'admin' ? 'منسق القسم' : 'طالب') }}
                    </span>
                </div>

                <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-outline btn-sm" title="تسجيل الخروج" style="color: #f43f5e; border-color: rgba(244, 63, 94, 0.3);">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    </button>
                </form>
            </div>
        </div>
        @else
        <div>
            <a href="{{ route('login') }}" class="btn btn-primary">
                <i class="fa-solid fa-lock"></i> تسجيل الدخول
            </a>
        </div>
        @endauth
    </header>

    <!-- Main Container -->
    <main class="container">
        <!-- Flash Messages -->
        @if(session('success'))
            <div class="alert alert-success">
                <i class="fa-solid fa-circle-check"></i>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                <i class="fa-solid fa-circle-exclamation"></i>
                <div>{{ session('error') }}</div>
            </div>
        @endif

        @if(session('info'))
            <div class="alert alert-info">
                <i class="fa-solid fa-circle-info"></i>
                <div>{{ session('info') }}</div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer with academic and supervisor credits -->
    <footer class="footer">
        <div>
            <strong>مشروع منصة GradTrack</strong> — مادة هندسة البرمجيات (العملي)
        </div>
        <div class="footer-credits">
            <span class="badge-academic"><i class="fa-solid fa-user-tie"></i> إشراف: <strong>المهندس ساهر الهمداني</strong></span>
            <span class="badge-academic"><i class="fa-solid fa-user-graduate"></i> إعداد الطالب: <strong>إلياس جمال الساده</strong></span>
            <span class="badge-academic"><i class="fa-solid fa-calendar"></i> العام الجامعي: <strong>2026 / 2027</strong></span>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
