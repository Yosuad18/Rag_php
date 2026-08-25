<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=bricolage-grotesque:400,500,600,700|dm-sans:400,500,600,700" rel="stylesheet" />
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'DM Sans', system-ui, sans-serif; background: #F8FAF9; color: #1A2E1A; }

        .hero {
            position: relative;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background: linear-gradient(135deg, #022c22 0%, #064e3b 30%, #047857 60%, #059669 100%);
        }

        .hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse 80% 60% at 20% 80%, rgba(16, 185, 129, 0.4) 0%, transparent 70%),
                radial-gradient(ellipse 60% 50% at 80% 20%, rgba(52, 211, 153, 0.3) 0%, transparent 60%),
                radial-gradient(ellipse 50% 40% at 50% 50%, rgba(110, 231, 183, 0.15) 0%, transparent 50%);
            animation: meshShift 12s ease-in-out infinite alternate;
        }

        @keyframes meshShift {
            0% { transform: scale(1) rotate(0deg); }
            50% { transform: scale(1.05) rotate(1deg); }
            100% { transform: scale(1) rotate(-1deg); }
        }

        .hero-content {
            position: relative;
            z-index: 1;
            text-align: center;
            padding: 2rem;
            max-width: 800px;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.375rem 1rem;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 9999px;
            color: #a7f3d0;
            font-size: 0.8125rem;
            font-weight: 500;
            letter-spacing: 0.02em;
            margin-bottom: 1.5rem;
            backdrop-filter: blur(8px);
        }

        .hero-badge svg { width: 14px; height: 14px; }

        .hero h1 {
            font-family: 'Bricolage Grotesque', system-ui, sans-serif;
            font-size: clamp(2.75rem, 7vw, 5rem);
            font-weight: 700;
            color: #ffffff;
            line-height: 1.05;
            letter-spacing: -0.03em;
            margin-bottom: 1.25rem;
        }

        .hero h1 span {
            display: block;
            background: linear-gradient(135deg, #6ee7b7, #34d399);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero p {
            font-size: clamp(1rem, 2.5vw, 1.25rem);
            color: #a7f3d0;
            max-width: 540px;
            margin: 0 auto 2.5rem;
            line-height: 1.6;
        }

        .hero-actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.875rem 2rem;
            background: #ffffff;
            color: #064e3b;
            font-family: 'Bricolage Grotesque', system-ui, sans-serif;
            font-weight: 600;
            font-size: 1rem;
            border-radius: 0.5rem;
            text-decoration: none;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
        }

        .btn-primary:hover {
            background: #ecfdf5;
            transform: translateY(-1px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }

        .btn-secondary {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.875rem 2rem;
            background: transparent;
            color: #ffffff;
            font-family: 'Bricolage Grotesque', system-ui, sans-serif;
            font-weight: 600;
            font-size: 1rem;
            border-radius: 0.5rem;
            text-decoration: none;
            border: 1px solid rgba(255, 255, 255, 0.25);
            transition: all 0.2s;
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.4);
        }

        .features {
            padding: 5rem 2rem;
            max-width: 1100px;
            margin: 0 auto;
        }

        .features-header {
            text-align: center;
            margin-bottom: 3.5rem;
        }

        .features-header h2 {
            font-family: 'Bricolage Grotesque', system-ui, sans-serif;
            font-size: 2rem;
            font-weight: 700;
            color: #1A2E1A;
            margin-bottom: 0.75rem;
        }

        .features-header p {
            color: #6b7280;
            font-size: 1.125rem;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .feature-card {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 0.75rem;
            padding: 2rem;
            transition: all 0.25s;
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .feature-card:hover {
            border-color: #a7f3d0;
            box-shadow: 0 4px 20px rgba(5, 150, 105, 0.08);
            transform: translateY(-2px);
        }

        .feature-icon {
            width: 48px;
            height: 48px;
            border-radius: 0.625rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.25rem;
        }

        .feature-icon svg { width: 24px; height: 24px; }

        .feature-card h3 {
            font-family: 'Bricolage Grotesque', system-ui, sans-serif;
            font-size: 1.125rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #1A2E1A;
        }

        .feature-card p {
            font-size: 0.875rem;
            color: #6b7280;
            line-height: 1.6;
        }

        .stats {
            background: #ffffff;
            border-top: 1px solid #e5e7eb;
            border-bottom: 1px solid #e5e7eb;
            padding: 3.5rem 2rem;
        }

        .stats-grid {
            max-width: 900px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 2rem;
            text-align: center;
        }

        .stat-item h3 {
            font-family: 'Bricolage Grotesque', system-ui, sans-serif;
            font-size: 2.25rem;
            font-weight: 700;
            color: #059669;
            line-height: 1;
            margin-bottom: 0.375rem;
        }

        .stat-item p {
            font-size: 0.875rem;
            color: #6b7280;
            font-weight: 500;
        }

        footer {
            padding: 2.5rem 2rem;
            text-align: center;
            color: #9ca3af;
            font-size: 0.8125rem;
        }

        footer a {
            color: #059669;
            text-decoration: none;
            font-weight: 500;
        }

        footer a:hover { text-decoration: underline; }

        @media (prefers-reduced-motion: reduce) {
            .hero::before { animation: none; }
            .feature-card:hover, .btn-primary:hover { transform: none; }
        }
    </style>
</head>
<body>
    <section class="hero">
        <div class="hero-content">
            <div class="hero-badge">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/></svg>
                MongoDB Atlas &middot; Laravel &middot; OpenAI
            </div>
            <h1>
                {{ config('app.name') }}
                <span>Gestiona tu negocio</span>
            </h1>
            <p>Controla productos, usuarios, repartidores y pagos desde un solo lugar. Busca productos con inteligencia artificial.</p>
            <div class="hero-actions">
                <a href="{{ route('chat.index') }}" class="btn-primary">
                    Abrir chat
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </a>
                <a href="{{ route('productos.index') }}" class="btn-secondary">
                    Ver productos
                </a>
            </div>
        </div>
    </section>

    <section class="stats">
        <div class="stats-grid">
            <div class="stat-item">
                <h3>5</h3>
                <p>Modulos disponibles</p>
            </div>
            <div class="stat-item">
                <h3>AI</h3>
                <p>Busqueda inteligente</p>
            </div>
            <div class="stat-item">
                <h3>NoSQL</h3>
                <p>Base de datos MongoDB</p>
            </div>
            <div class="stat-item">
                <h3>Real</h3>
                <p>Tiempos reales</p>
            </div>
        </div>
    </section>

    <section class="features">
        <div class="features-header">
            <h2>Todo lo que necesitas</h2>
            <p>Herramientas disenadas para operar sin fricciones.</p>
        </div>
        <div class="features-grid">
            <a href="{{ route('chat.index') }}" class="feature-card">
                <div class="feature-icon" style="background: #ecfdf5;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                </div>
                <h3>Chat con Productos</h3>
                <p>Busca productos por descripcion, precio o caracteristicas usando lenguaje natural. La IA encuentra lo que necesitas.</p>
            </a>
            <a href="{{ route('productos.index') }}" class="feature-card">
                <div class="feature-icon" style="background: #f0fdf4;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                </div>
                <h3>Productos</h3>
                <p>Administra tu catalogo completo: nombre, precio, stock y estado. Todo en una tabla clara y funcional.</p>
            </a>
            <a href="{{ route('usuarios.index') }}" class="feature-card">
                <div class="feature-icon" style="background: #ecfdf5;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                </div>
                <h3>Usuarios</h3>
                <p>Gestiona los usuarios del sistema con sus datos de contacto y estado de cuenta.</p>
            </a>
            <a href="{{ route('repartidores.index') }}" class="feature-card">
                <div class="feature-icon" style="background: #f0fdf4;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                </div>
                <h3>Repartidores</h3>
                <p>Controla tu flota de repartidores: contactos, vehiculos y estado de disponibilidad.</p>
            </a>
            <a href="{{ route('pagos.index') }}" class="feature-card">
                <div class="feature-icon" style="background: #ecfdf5;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                </div>
                <h3>Pagos</h3>
                <p>Registra y consulta pagos con montos, fechas y estados. Seguimiento de cada transaccion.</p>
            </a>
            <div class="feature-card" style="cursor: default;">
                <div class="feature-icon" style="background: #f0fdf4;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2"><path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/></svg>
                </div>
                <h3>MongoDB Atlas</h3>
                <p>Busqueda vectorial con embeddings. Encuentra productos semanticamente similares a cualquier consulta.</p>
            </div>
        </div>
    </section>

    <footer>
        <p>{{ config('app.name') }} &copy; {{ date('Y') }} &middot; <a href="{{ url('/') }}">Ir al panel</a></p>
    </footer>
</body>
</html>
