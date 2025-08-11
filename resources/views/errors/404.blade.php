<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    animation: {
                        'glitch-1': 'glitch-1 2s infinite linear alternate-reverse',
                        'glitch-2': 'glitch-2 3s infinite linear alternate-reverse',
                        'grid-move': 'grid-move 20s linear infinite',
                        'float': 'float 6s ease-in-out infinite',
                        'ripple': 'ripple 0.6s linear',
                        'loading': 'loading 2s ease-in-out infinite',
                        'fadeInUp': 'fadeInUp 1s ease forwards',
                        'typing': 'typing 0.05s steps(1, end)',
                    }
                }
            }
        }
    </script>
    <style>
        @keyframes glitch-1 {
            0% { transform: translate(0); }
            20% { transform: translate(-2px, 2px); }
            40% { transform: translate(-2px, -2px); }
            60% { transform: translate(2px, 2px); }
            80% { transform: translate(2px, -2px); }
            100% { transform: translate(0); }
        }

        @keyframes glitch-2 {
            0% { transform: translate(0); }
            20% { transform: translate(2px, -2px); }
            40% { transform: translate(2px, 2px); }
            60% { transform: translate(-2px, -2px); }
            80% { transform: translate(-2px, 2px); }
            100% { transform: translate(0); }
        }

        @keyframes grid-move {
            0% { transform: translate(0, 0); }
            100% { transform: translate(50px, 50px); }
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes ripple {
            to { transform: scale(4); opacity: 0; }
        }

        @keyframes loading {
            0% { left: -100%; }
            50% { left: 100%; }
            100% { left: 100%; }
        }

        @keyframes typing {
            from { width: 0; }
            to { width: 100%; }
        }

        body {
            cursor: none;
        }

        .cursor {
            mix-blend-mode: difference;
        }

        .grid-bg {
            background-image:
                linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px);
            background-size: 50px 50px;
        }

        .error-code::before,
        .error-code::after {
            content: '404';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        .error-code::before {
            animation: glitch-1 2s infinite linear alternate-reverse;
            color: #fff;
            z-index: -1;
        }

        .error-code::after {
            animation: glitch-2 3s infinite linear alternate-reverse;
            color: #fff;
            z-index: -2;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: #fff;
            transition: all 0.3s ease;
        }

        .btn:hover::before {
            left: 0;
        }

        .quote::before,
        .quote::after {
            content: '"';
            font-size: 2em;
            position: absolute;
            top: -10px;
            opacity: 0.3;
            font-family: Georgia, serif;
        }

        .quote::before {
            left: 0;
        }

        .quote::after {
            right: 0;
        }

        .loading-bar::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: #fff;
            animation: loading 2s ease-in-out infinite;
        }

        .typing-text {
            overflow: hidden;
            white-space: nowrap;
            border-right: 2px solid #fff;
        }

        .typing-cursor {
            animation: blink 1s infinite;
        }

        @keyframes blink {
            0%, 50% { opacity: 1; }
            51%, 100% { opacity: 0; }
        }
    </style>
</head>
<body class="overflow-x-hidden text-white bg-black">
    <div class="fixed z-50 w-5 h-5 transition-transform duration-100 bg-white rounded-full pointer-events-none cursor"></div>
    <div class="fixed inset-0 grid-bg animate-grid-move"></div>

    <div class="relative z-10 flex flex-col items-center justify-center min-h-screen">
        <!-- 404 Number with glitch effect -->
        <div class="error-code text-8xl md:text-9xl lg:text-[12rem] font-black tracking-tighter relative mb-8 select-none">
            404
        </div>

        <!-- Message -->
        <div class="mb-12 text-xl font-light tracking-widest text-center opacity-0 md:text-2xl lg:text-3xl"
             style="animation: fadeInUp 1s ease forwards 0.5s;">
            OOPS! HALAMAN YANG ANDA CARI<br>
            TELAH TERSESAT DI DIMENSI LAIN
        </div>

        <!-- Inspirational Quote -->
        <div class="max-w-2xl mx-4 mb-12 text-center transition-opacity duration-300 opacity-0 quote-container"
             style="animation: fadeInUp 1s ease forwards 0.8s;">
            <div class="relative px-8 mb-2 text-lg italic font-light leading-relaxed quote md:text-xl lg:text-2xl typing-text">
                <!-- Quote text will be inserted here -->
            </div>
            <div class="text-sm font-normal tracking-widest quote-author opacity-70">
                <!-- Author will be inserted here -->
            </div>
        </div>

        <!-- Buttons -->
        <div class="flex flex-col gap-4 opacity-0 md:flex-row md:gap-8"
             style="animation: fadeInUp 1s ease forwards 1s;">
            <a href="#" class="btn relative px-8 py-4 border-2 border-white text-white font-medium tracking-widest cursor-pointer overflow-hidden transition-all duration-300 hover:text-black hover:-translate-y-0.5 hover:shadow-lg hover:shadow-white/10" onclick="goBack()">
                <span class="relative z-10">KEMBALI</span>
            </a>
            <a href="/" class="btn relative px-8 py-4 border-2 border-white text-white font-medium tracking-widest cursor-pointer overflow-hidden transition-all duration-300 hover:text-black hover:-translate-y-0.5 hover:shadow-lg hover:shadow-white/10">
                <span class="relative z-10">BERANDA</span>
            </a>
        </div>
    </div>

    <!-- Loading bar -->
    <div class="loading-bar fixed bottom-12 left-1/2 transform -translate-x-1/2 w-48 h-0.5 bg-white/10 overflow-hidden">
    </div>

    <script>
        // Array of inspirational quotes in Indonesian
        const quotes = [
            {
                text: "Kadang kita harus tersesat untuk menemukan jalan yang benar",
                author: "Rumi"
            },
            {
                text: "Setiap jalan buntu adalah awal dari petualangan baru",
                author: "Paulo Coelho"
            },
            {
                text: "Yang hilang hari ini, mungkin ditemukan esok hari",
                author: "Fiersa Besari"
            },
            {
                text: "Halaman kosong adalah kanvas untuk cerita baru",
                author: "Tere Liye"
            },
            {
                text: "Kesalahan adalah guru terbaik dalam hidup",
                author: "Albert Einstein"
            },
            {
                text: "Jalan yang tidak pernah dilalui sering membawa kejutan terindah",
                author: "Andrea Hirata"
            }
        ];

        let currentQuoteIndex = 0;
        let typingTimeout;
        let isTyping = false;

        // Custom cursor
        const cursor = document.querySelector('.cursor');
        const interactiveElements = document.querySelectorAll('.btn, a');

        document.addEventListener('mousemove', (e) => {
            cursor.style.left = e.clientX + 'px';
            cursor.style.top = e.clientY + 'px';
        });

        interactiveElements.forEach(el => {
            el.addEventListener('mouseenter', () => {
                cursor.classList.add('scale-150');
            });
            el.addEventListener('mouseleave', () => {
                cursor.classList.remove('scale-150');
            });
        });

        // Typing effect function
        function typeText(element, text, speed = 50) {
            return new Promise((resolve) => {
                element.textContent = '';
                element.style.borderRight = '2px solid #fff';
                let i = 0;

                function type() {
                    if (i < text.length) {
                        element.textContent += text.charAt(i);
                        i++;
                        typingTimeout = setTimeout(type, speed);
                    } else {
                        element.style.borderRight = 'none';
                        resolve();
                    }
                }
                type();
            });
        }

        // Erase text with typing effect
        function eraseText(element, speed = 30) {
            return new Promise((resolve) => {
                element.style.borderRight = '2px solid #fff';
                const text = element.textContent;
                let i = text.length;

                function erase() {
                    if (i > 0) {
                        element.textContent = text.substring(0, i - 1);
                        i--;
                        typingTimeout = setTimeout(erase, speed);
                    } else {
                        resolve();
                    }
                }
                erase();
            });
        }

        // Display quote with typing effect
        async function displayQuoteWithTyping(quoteData) {
            if (isTyping) return;
            isTyping = true;

            const quoteElement = document.querySelector('.quote');
            const authorElement = document.querySelector('.quote-author');

            // Erase current text
            if (quoteElement.textContent) {
                await eraseText(quoteElement, 20);
                await new Promise(resolve => setTimeout(resolve, 200));
            }

            // Type new quote
            await typeText(quoteElement, quoteData.text, 40);
            await new Promise(resolve => setTimeout(resolve, 100));

            // Update author instantly
            authorElement.textContent = `— ${quoteData.author}`;

            isTyping = false;
        }

        // Display initial quote
        function displayInitialQuote() {
            const randomIndex = Math.floor(Math.random() * quotes.length);
            currentQuoteIndex = randomIndex;
            displayQuoteWithTyping(quotes[randomIndex]);
        }

        // Change quote with typing effect
        function changeQuote() {
            if (isTyping) return;

            currentQuoteIndex = (currentQuoteIndex + 1) % quotes.length;
            displayQuoteWithTyping(quotes[currentQuoteIndex]);
        }

        // Initialize
        displayInitialQuote();

        // Change quote every 8 seconds
        setInterval(changeQuote, 8000);

        // Create floating particles
        function createParticle() {
            const particle = document.createElement('div');
            particle.className = 'fixed bg-white rounded-full pointer-events-none opacity-10';

            const size = Math.random() * 4 + 2;
            particle.style.width = size + 'px';
            particle.style.height = size + 'px';
            particle.style.left = Math.random() * 100 + '%';
            particle.style.top = Math.random() * 100 + '%';
            particle.style.animation = `float ${Math.random() * 3 + 3}s ease-in-out infinite`;
            particle.style.animationDelay = Math.random() * 2 + 's';

            document.body.appendChild(particle);

            setTimeout(() => {
                particle.remove();
            }, 6000);
        }

        // Generate particles periodically
        setInterval(createParticle, 500);

        // Go back function
        function goBack() {
            if (window.history.length > 1) {
                window.history.back();
            } else {
                window.location.href = '/';
            }
        }

        // Add click ripple effect
        document.addEventListener('click', (e) => {
            const ripple = document.createElement('div');
            ripple.className = 'fixed rounded-full bg-white/30 transform scale-0 pointer-events-none z-40';
            ripple.style.left = (e.clientX - 25) + 'px';
            ripple.style.top = (e.clientY - 25) + 'px';
            ripple.style.width = '50px';
            ripple.style.height = '50px';
            ripple.style.animation = 'ripple 0.6s linear';

            document.body.appendChild(ripple);

            setTimeout(() => {
                ripple.remove();
            }, 600);
        });

        // Random glitch effect on 404
        const errorCode = document.querySelector('.error-code');
        setInterval(() => {
            if (Math.random() < 0.1) {
                errorCode.style.textShadow = '2px 0 #fff, -2px 0 #fff';
                setTimeout(() => {
                    errorCode.style.textShadow = 'none';
                }, 100);
            }
        }, 1000);
    </script>
</body>
</html>
