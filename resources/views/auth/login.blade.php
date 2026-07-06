<x-layouts.app>
    <x-slot name="title">Giriş Yap - Öğrenci Takip & Koçluk Sistemi</x-slot>

    <!-- Custom CSS styles for the new premium design -->
    <style>
        .login-wrapper {
            display: grid;
            grid-template-columns: 1fr;
            min-height: 100vh;
            background-color: #0f172a;
            color: #f8fafc;
            overflow: hidden;
        }

        @media (min-width: 1024px) {
            .login-wrapper {
                grid-template-columns: 1.15fr 0.85fr;
            }
        }

        /* Left Panel Styles */
        .brand-panel {
            position: relative;
            display: none;
            flex-direction: column;
            justify-content: space-between;
            padding: 4rem;
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #0f172a 100%);
            overflow: hidden;
            border-right: 1px solid rgba(255, 255, 255, 0.08);
        }

        @media (min-width: 1024px) {
            .brand-panel {
                display: flex;
            }
        }

        /* Grid overlay */
        .brand-panel::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: radial-gradient(rgba(255, 255, 255, 0.09) 1px, transparent 1px);
            background-size: 24px 24px;
            pointer-events: none;
        }

        /* Glowing Orbs */
        .orb-1 {
            position: absolute;
            top: 20%;
            left: 30%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.3) 0%, rgba(0, 0, 0, 0) 70%);
            filter: blur(40px);
            animation: float-orb 8s ease-in-out infinite alternate;
        }

        .orb-2 {
            position: absolute;
            bottom: 15%;
            right: 20%;
            width: 350px;
            height: 350px;
            background: radial-gradient(circle, rgba(14, 165, 233, 0.25) 0%, rgba(0, 0, 0, 0) 70%);
            filter: blur(50px);
            animation: float-orb 12s ease-in-out infinite alternate-reverse;
        }

        @keyframes float-orb {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(30px, -20px) scale(1.1); }
        }

        /* Floating mock UI cards */
        .glass-card {
            background: rgba(255, 255, 255, 0.04);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 1rem;
            padding: 1.5rem;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
        }

        .glass-card:hover {
            transform: translateY(-5px);
            border-color: rgba(255, 255, 255, 0.2);
            background: rgba(255, 255, 255, 0.07);
        }

        /* Right Panel Styles */
        .form-panel {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2.5rem 1.5rem;
            background-color: #0b0f19;
            position: relative;
        }

        .form-container {
            width: 100%;
            max-width: 440px;
        }

        /* Input Controls */
        .input-group {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .input-label {
            display: block;
            font-size: 0.9rem;
            font-weight: 600;
            color: #f1f5f9;
            margin-bottom: 0.5rem;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            pointer-events: none;
            transition: color 0.2s;
            width: 1.35rem;
            height: 1.35rem;
        }

        .custom-input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.75rem;
            background-color: #1e293b;
            border: 1.5px solid #475569;
            border-radius: 0.75rem;
            color: #f8fafc;
            font-size: 0.95rem;
            transition: all 0.2s ease;
            box-sizing: border-box;
        }

        .custom-input::placeholder {
            color: #94a3b8;
            opacity: 1;
        }

        .custom-input:focus {
            outline: none;
            border-color: #818cf8;
            box-shadow: 0 0 0 3px rgba(129, 140, 248, 0.35);
            background-color: #1e293b;
        }

        .custom-input:focus + .input-icon {
            color: #818cf8;
        }

        /* Password visibility toggle */
        .password-toggle {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            cursor: pointer;
            background: none;
            border: none;
            padding: 0;
            display: flex;
            align-items: center;
        }

        .password-toggle:hover {
            color: #f8fafc;
        }

        /* Demo Fill Selector */
        .demo-badge-container {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }

        .demo-badge {
            background-color: #1e293b;
            border: 1.5px solid #475569;
            color: #f1f5f9;
            padding: 0.5rem 0.85rem;
            border-radius: 0.5rem;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 0.35rem;
        }

        .demo-badge:hover {
            background-color: #312e81;
            border-color: #818cf8;
            color: #f8fafc;
            transform: scale(1.03);
        }

        /* Gradient Button */
        .btn-gradient {
            width: 100%;
            padding: 0.85rem;
            background: linear-gradient(135deg, #6366f1 0%, #0ea5e9 100%);
            border: none;
            border-radius: 0.75rem;
            color: white;
            font-weight: 700;
            font-size: 1.05rem;
            cursor: pointer;
            transition: all 0.25s ease;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
        }

        .btn-gradient:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(99, 102, 241, 0.45);
            opacity: 0.95;
        }

        .btn-gradient:active {
            transform: translateY(1px);
        }

        /* Features List */
        .features-list {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            margin-top: 2rem;
            z-index: 10;
        }

        .feature-item {
            display: flex;
            gap: 1.25rem;
            align-items: flex-start;
        }

        .feature-icon-wrapper {
            background: rgba(99, 102, 241, 0.2);
            border: 1.5px solid rgba(99, 102, 241, 0.6);
            border-radius: 0.65rem;
            padding: 0.6rem;
            color: #a5b4fc;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .feature-text h4 {
            font-weight: 700;
            color: #f8fafc;
            margin: 0 0 0.35rem 0;
            font-size: 1.05rem;
        }

        .feature-text p {
            color: #cbd5e1;
            margin: 0;
            font-size: 0.9rem;
            line-height: 1.45;
        }

        /* Custom Checkbox */
        .checkbox-container {
            display: flex;
            align-items: center;
            cursor: pointer;
            user-select: none;
            font-size: 0.9rem;
            font-weight: 500;
            color: #cbd5e1;
        }

        .checkbox-container input {
            position: absolute;
            opacity: 0;
            cursor: pointer;
            height: 0;
            width: 0;
        }

        .checkmark {
            height: 1.25rem;
            width: 1.25rem;
            background-color: #1e293b;
            border: 1.5px solid #475569;
            border-radius: 0.35rem;
            margin-right: 0.5rem;
            position: relative;
            transition: all 0.2s;
        }

        .checkbox-container:hover input ~ .checkmark {
            border-color: #818cf8;
        }

        .checkbox-container input:checked ~ .checkmark {
            background-color: #6366f1;
            border-color: #6366f1;
        }

        .checkmark:after {
            content: "";
            position: absolute;
            display: none;
        }

        .checkbox-container input:checked ~ .checkmark:after {
            display: block;
        }

        .checkbox-container .checkmark:after {
            left: 6px;
            top: 2px;
            width: 4px;
            height: 8px;
            border: solid white;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
        }
    </style>

    <div class="login-wrapper">
        <!-- Left Panel: Branding & Features -->
        <div class="brand-panel">
            <!-- Orbs -->
            <div class="orb-1"></div>
            <div class="orb-2"></div>

            <!-- Top Brand -->
            <div style="z-index: 10; display: flex; align-items: center; gap: 0.75rem;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 2.25rem; height: 2.25rem; color: #818cf8;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.62 48.62 0 0112 20.904a48.62 48.62 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 018.918 5.842 50.45 50.45 0 00-2.658.814m-15.482 0a50.503 50.503 0 0115.482 0M12 20.904V18" />
                </svg>
                <span style="font-size: 1.5rem; font-weight: 800; background: linear-gradient(to right, #ffffff, #93c5fd); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                    Öğrenci Takip & Koçluk
                </span>
            </div>

            <!-- Middle Mock UI & Testimonial -->
            <div style="z-index: 10; margin: 3rem 0;">
                <h1 style="font-size: 2.5rem; font-weight: 800; line-height: 1.2; margin-bottom: 1.5rem;">
                    Geleceğini Birlikte <br>
                    <span style="background: linear-gradient(to right, #818cf8, #38bdf8); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Planlayalım.</span>
                </h1>
                <p style="color: #cbd5e1; font-size: 1.1rem; max-width: 480px; margin-bottom: 2.5rem; line-height: 1.6;">
                    Öğrenci gelişimini adım adım izleyin, hedeflere ulaşmayı kolaylaştırın ve koçluk sürecini tek bir ekrandan yönetin.
                </p>

                <!-- Features list -->
                <div class="features-list">
                    <div class="feature-item">
                        <div class="feature-icon-wrapper">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" style="width: 1.35rem; height: 1.35rem;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                            </svg>
                        </div>
                        <div class="feature-text">
                            <h4>Kişiselleştirilmiş Çalışma Şablonları</h4>
                            <p>Öğrencilere sözel, sayısal veya özel ders paketlerini tek tıkla atayın.</p>
                        </div>
                    </div>

                    <div class="feature-item">
                        <div class="feature-icon-wrapper">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" style="width: 1.35rem; height: 1.35rem;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 107.5 7.5h-7.5V6z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0013.5 3v7.5z" />
                            </svg>
                        </div>
                        <div class="feature-text">
                            <h4>Otomatik Net & Soru Çözüm Analizleri</h4>
                            <p>Deneme sonuçları girildiğinde netler otomatik hesaplanır ve grafiklere dökülür.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer info -->
            <div style="z-index: 10; font-size: 0.85rem; color: #94a3b8;">
                © {{ date('Y') }} Öğrenci Takip Sistemi. Tüm hakları saklıdır.
            </div>
        </div>

        <!-- Right Panel: Login Form -->
        <div class="form-panel">
            <div class="form-container">
                <!-- Mobile Logo -->
                <div class="lg:hidden text-center mb-8 flex justify-center items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 2rem; height: 2rem; color: #818cf8;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.62 48.62 0 0112 20.904a48.62 48.62 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 018.918 5.842 50.45 50.45 0 00-2.658.814m-15.482 0a50.503 50.503 0 0115.482 0M12 20.904V18" />
                    </svg>
                    <span style="font-size: 1.25rem; font-weight: 800; color: #f8fafc;">
                        Öğrenci Takip Sistemi
                    </span>
                </div>

                <div style="margin-bottom: 2rem;">
                    <h2 style="font-size: 1.75rem; font-weight: 700; color: #f8fafc; margin-bottom: 0.5rem;">
                        Tekrar Hoş Geldiniz!
                    </h2>
                    <p style="color: #cbd5e1; font-size: 0.95rem; line-height: 1.5;">
                        Sisteme erişmek için bilgilerinizi doldurun veya hızlı demo hesapları kullanın.
                    </p>
                </div>

                <!-- Livewire / Controller errors display -->
                @if ($errors->any())
                    <div style="background-color: rgba(239, 68, 68, 0.15); border: 1.5px solid rgba(239, 68, 68, 0.35); color: #fca5a5; padding: 1rem; border-radius: 0.75rem; margin-bottom: 1.5rem; font-size: 0.875rem;">
                        <ul style="list-style-type: disc; margin: 0; padding-left: 1.25rem;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Demo Accounts Fast Selector -->
                <div style="margin-bottom: 1.5rem;">
                    <label class="input-label" style="font-weight: 700;">Hızlı Giriş Seçenekleri (Demo):</label>
                    <div class="demo-badge-container">
                        <button type="button" class="demo-badge" onclick="fillCredentials('admin@ogrenci.com', 'password')">
                            🔒 Admin
                        </button>
                        <button type="button" class="demo-badge" onclick="fillCredentials('coach1@ogrenci.com', 'password')">
                            💼 Koç
                        </button>
                        <button type="button" class="demo-badge" onclick="fillCredentials('student1@ogrenci.com', 'password')">
                            🎓 Öğrenci
                        </button>
                    </div>
                </div>

                <form method="POST" action="{{ route('login') }}" style="display: flex; flex-direction: column; gap: 0.25rem;">
                    @csrf

                    <!-- Email Input -->
                    <div class="input-group">
                        <label for="email" class="input-label">E-posta Adresi</label>
                        <div class="input-wrapper">
                            <input 
                                id="email" 
                                name="email" 
                                type="email" 
                                autocomplete="email" 
                                required 
                                value="{{ old('email') }}"
                                class="custom-input"
                                placeholder="ornek@email.com"
                            >
                            <!-- Mail Icon -->
                            <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0l-7.5-4.615a2.25 2.25 0 01-1.07-1.916V6.75" />
                            </svg>
                        </div>
                    </div>

                    <!-- Password Input -->
                    <div class="input-group">
                        <label for="password" class="input-label">Şifre</label>
                        <div class="input-wrapper">
                            <input 
                                id="password" 
                                name="password" 
                                type="password" 
                                autocomplete="current-password" 
                                required 
                                class="custom-input"
                                placeholder="••••••••"
                            >
                            <!-- Lock Icon -->
                            <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                            </svg>
                            <!-- Password Toggle Button -->
                            <button type="button" class="password-toggle" onclick="togglePasswordVisibility()">
                                <svg id="eye-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 1.35rem; height: 1.35rem;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Remember Me -->
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                        <label class="checkbox-container">
                            <input id="remember" name="remember" type="checkbox">
                            <span class="checkmark"></span>
                            Beni hatırla
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <div style="margin-top: 0.5rem;">
                        <button type="submit" class="btn-gradient">
                            Sistem Girişi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- JS Helper Functions -->
    <script>
        function fillCredentials(email, password) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = password;
            
            // Add subtle click highlight to input fields
            const inputs = [document.getElementById('email'), document.getElementById('password')];
            inputs.forEach(input => {
                input.style.transform = 'scale(1.02)';
                setTimeout(() => {
                    input.style.transform = 'scale(1)';
                }, 200);
            });
        }

        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                // Change eye icon to "slashed eye"
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                `;
            } else {
                passwordInput.type = 'password';
                // Reset to standard eye icon
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                `;
            }
        }
    </script>
</x-layouts.app>
