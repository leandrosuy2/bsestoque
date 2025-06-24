<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - BSEstoque</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Navbar -->
    <nav class="bg-white border-b border-gray-200 fixed w-full z-30 top-0 left-0 shadow-sm h-16 flex items-center">
        <div class="flex justify-between items-center w-full max-w-full px-4">
            <div class="flex items-center gap-3">
                <!-- Botão do menu mobile -->
                <button id="sidebarToggle" class="md:hidden p-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <span class="bg-gray-800 p-2 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="white" class="w-7 h-7">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5V6.375A2.625 2.625 0 015.625 3.75h12.75A2.625 2.625 0 0121 6.375V7.5M3 7.5v10.125A2.625 2.625 0 005.625 20.25h12.75A2.625 2.625 0 0021 17.625V7.5M3 7.5h18M7.5 11.25h9" />
                    </svg>
                </span>
                <span class="text-xl font-bold text-gray-800 tracking-tight">BSEstoque</span>
            </div>
            <div class="flex items-center gap-4">
                <span class="text-gray-700 font-medium hidden sm:block">Olá, <span class="font-semibold">Usuário</span></span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 bg-gray-800 text-white rounded hover:bg-gray-900 transition text-sm font-medium">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H7a2 2 0 01-2-2V7a2 2 0 012-2h4a2 2 0 012 2v1"/></svg>
                        Sair
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Overlay para mobile -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 md:hidden hidden transition-opacity duration-300 opacity-0" onclick="toggleSidebar()"></div>

    <!-- Sidebar fixa -->
    <aside id="sidebar" class="fixed left-0 z-50 md:z-20 bg-white border-r border-gray-200 flex-col transition-transform duration-300 ease-in-out transform -translate-x-full md:translate-x-0 shadow-lg md:shadow-none top-0 h-full md:top-16 md:h-[calc(100vh-4rem)] w-64">
        <nav class="flex-1 px-4 py-6 space-y-1 h-full max-h-[calc(100vh-4rem)] overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100">
            <a href="/dashboard" class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium transition {{ request()->routeIs('dashboard') ? 'bg-gray-100 text-gray-900 font-bold' : 'text-gray-700 hover:bg-gray-100' }}">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M13 5v6h6"/></svg>
                Dashboard
            </a>

            <!-- Seção de Estoque -->
            <div class="pt-4">
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Estoque</h3>
                <a href="/products" class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium transition {{ request()->routeIs('products.*') ? 'bg-gray-100 text-gray-900 font-bold' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 13V7a2 2 0 00-2-2H6a2 2 0 00-2 2v6m16 0v6a2 2 0 01-2 2H6a2 2 0 01-2-2v-6m16 0H4"/></svg>
                    Produtos
                </a>
                <a href="/categories" class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium transition {{ request()->routeIs('categories.*') ? 'bg-gray-100 text-gray-900 font-bold' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                    Categorias
                </a>
                <a href="/stock_movements" class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium transition {{ request()->routeIs('stock_movements.*') ? 'bg-gray-100 text-gray-900 font-bold' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H7m6 4v1a2 2 0 01-2 2H7a2 2 0 01-2-2V7a2 2 0 012-2h4a2 2 0 012 2v1"/></svg>
                    Movimentações
                </a>
            </div>

            <!-- Seção Financeira -->
            <div class="pt-4">
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Financeiro</h3>
                <a href="/payables" class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium transition {{ request()->routeIs('payables.*') ? 'bg-gray-100 text-gray-900 font-bold' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/></svg>
                    Contas a Pagar
                </a>
                <a href="/receivables" class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium transition {{ request()->routeIs('receivables.*') ? 'bg-gray-100 text-gray-900 font-bold' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/></svg>
                    Contas a Receber
                </a>
                <a href="/financial-reports" class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium transition {{ request()->routeIs('financial-reports.*') ? 'bg-gray-100 text-gray-900 font-bold' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    Relatórios Financeiros
                </a>
            </div>

            <!-- Seção Administrativa -->
            <div class="pt-4">
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Administrativo</h3>
                <a href="/employees" class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium transition {{ request()->routeIs('employees.*') ? 'bg-gray-100 text-gray-900 font-bold' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Funcionários
                </a>
                <a href="/reports" class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium transition {{ request()->routeIs('reports.*') ? 'bg-gray-100 text-gray-900 font-bold' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 17v-2a4 4 0 014-4h10a4 4 0 014 4v2M16 3.13a4 4 0 010 7.75M8 3.13a4 4 0 000 7.75"/></svg>
                    Relatórios de Estoque
                </a>
                <!-- Submenu RH / Departamento Pessoal -->
                <button type="button" id="rhMenuBtn" class="flex items-center w-full gap-3 px-3 py-2 rounded-lg font-medium transition text-gray-700 hover:bg-gray-100 focus:outline-none" onclick="toggleRHMenu()">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-7a4 4 0 11-8 0 4 4 0 018 0zm6 4a4 4 0 10-8 0 4 4 0 008 0z" />
                    </svg>
                    RH / Departamento Pessoal
                    <svg id="rhMenuChevron" class="w-4 h-4 ml-auto transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div id="rhSubMenu" class="space-y-1 pl-8 py-1 hidden">
                    <a href="/timeclocks" class="flex items-center gap-2 px-2 py-1 rounded font-medium transition text-sm {{ request()->routeIs('timeclocks.*') ? 'bg-gray-100 text-gray-900 font-bold' : 'text-gray-600 hover:bg-gray-100' }}">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2"/></svg>
                        Ponto
                    </a>
                    <a href="/payrolls" class="flex items-center gap-2 px-2 py-1 rounded font-medium transition text-sm {{ request()->routeIs('payrolls.*') ? 'bg-gray-100 text-gray-900 font-bold' : 'text-gray-600 hover:bg-gray-100' }}">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/></svg>
                        Folha de Pagamento
                    </a>
                    <a href="/vacations" class="flex items-center gap-2 px-2 py-1 rounded font-medium transition text-sm {{ request()->routeIs('vacations.*') ? 'bg-gray-100 text-gray-900 font-bold' : 'text-gray-600 hover:bg-gray-100' }}">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 17l4 4 4-4m-4-5v9"/></svg>
                        Férias
                    </a>
                    <a href="/leaves" class="flex items-center gap-2 px-2 py-1 rounded font-medium transition text-sm {{ request()->routeIs('leaves.*') ? 'bg-gray-100 text-gray-900 font-bold' : 'text-gray-600 hover:bg-gray-100' }}">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-6a2 2 0 012-2h2a2 2 0 012 2v6m-6 0a2 2 0 002 2h2a2 2 0 002-2"/></svg>
                        Licenças
                    </a>
                    <a href="/benefits" class="flex items-center gap-2 px-2 py-1 rounded font-medium transition text-sm {{ request()->routeIs('benefits.*') ? 'bg-gray-100 text-gray-900 font-bold' : 'text-gray-600 hover:bg-gray-100' }}">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/></svg>
                        Benefícios
                    </a>
                    <a href="/payslips" class="flex items-center gap-2 px-2 py-1 rounded font-medium transition text-sm {{ request()->routeIs('payslips.*') ? 'bg-gray-100 text-gray-900 font-bold' : 'text-gray-600 hover:bg-gray-100' }}">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-6a2 2 0 012-2h2a2 2 0 012 2v6m-6 0a2 2 0 002 2h2a2 2 0 002-2"/></svg>
                        Holerites
                    </a>
                </div>
            </div>
        </nav>
    </aside>

    <!-- Conteúdo principal -->
    <div class="md:pl-64 pt-16">
        <main class="p-4 md:p-8 bg-gray-50 min-h-screen">
            @yield('content')
        </main>
    </div>

    <script>
        // Função para alternar a sidebar
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');

            if (sidebar.classList.contains('-translate-x-full')) {
                // Abrir sidebar
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
                setTimeout(() => {
                    overlay.classList.add('opacity-100');
                }, 10);
                document.body.style.overflow = 'hidden';
            } else {
                // Fechar sidebar
                sidebar.classList.add('-translate-x-full');
                overlay.classList.remove('opacity-100');
                setTimeout(() => {
                    overlay.classList.add('hidden');
                }, 300);
                document.body.style.overflow = 'auto';
            }
        }

        // Event listener para o botão do menu
        document.getElementById('sidebarToggle').addEventListener('click', toggleSidebar);

        // Fechar sidebar ao clicar em um link (mobile)
        document.querySelectorAll('#sidebar a').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth < 768) {
                    setTimeout(() => {
                        toggleSidebar();
                    }, 100);
                }
            });
        });

        // Fechar sidebar ao redimensionar a tela para desktop
        window.addEventListener('resize', () => {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');

            if (window.innerWidth >= 768) {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.add('hidden');
                overlay.classList.remove('opacity-100');
                document.body.style.overflow = 'auto';
            } else {
                sidebar.classList.add('-translate-x-full');
            }
        });

        // Fechar sidebar ao pressionar ESC
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                const sidebar = document.getElementById('sidebar');
                if (!sidebar.classList.contains('-translate-x-full')) {
                    toggleSidebar();
                }
            }
        });

        function toggleRHMenu() {
            const submenu = document.getElementById('rhSubMenu');
            const chevron = document.getElementById('rhMenuChevron');
            submenu.classList.toggle('hidden');
            chevron.classList.toggle('rotate-180');
        }
        // Abrir automaticamente se estiver em rota de RH
        if ([
            'timeclocks.*','payrolls.*','vacations.*','leaves.*','benefits.*','payslips.*'
        ].some(r => window.location.pathname.includes(r.split('.')[0]))) {
            document.getElementById('rhSubMenu').classList.remove('hidden');
            document.getElementById('rhMenuChevron').classList.add('rotate-180');
        }
    </script>
</body>
</html>
