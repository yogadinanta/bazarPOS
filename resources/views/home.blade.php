{{-- resources/views/welcome.blade.php --}}

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bazar POS</title>

    {{-- Tailwind CSS CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Font Awesome CDN --}}
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />

    <style>
        body {
            background-color: #f5f5f5;
            background-image:
                radial-gradient(circle at 25% 25%, rgba(0, 0, 0, 0.02) 2%, transparent 0),
                radial-gradient(circle at 75% 75%, rgba(0, 0, 0, 0.02) 2%, transparent 0);
            background-size: 60px 60px;
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center p-5">

    <div class="w-full max-w-6xl bg-white rounded-[40px] shadow-xl py-16 px-10 relative overflow-hidden">

        {{-- Pattern Background --}}
        <div class="absolute inset-0 opacity-5">
            <svg width="100%" height="100%">
                <defs>
                    <pattern id="pattern"
                        x="0" y="0"
                        width="80" height="80"
                        patternUnits="userSpaceOnUse">

                        <path d="M0 40 Q20 0 40 40 T80 40"
                            fill="none"
                            stroke="#000"
                            stroke-width="1" />
                    </pattern>
                </defs>

                <rect width="100%" height="100%" fill="url(#pattern)" />
            </svg>
        </div>

        {{-- Content --}}
        <div class="relative z-10">

            {{-- Logo --}}
            <div class="flex justify-center">
                <div class="w-36 h-36 bg-red-500 rounded-full flex flex-col items-center justify-center shadow-lg">

                    <i class="fa-solid fa-utensils text-white text-5xl mb-2"></i>

                    <h1 class="text-white text-3xl font-bold">
                        Bazar
                    </h1>
                </div>
            </div>

            {{-- Title --}}
            <div class="text-center mt-10">
                <h2 class="text-5xl font-extrabold text-black">
                    WELCOME TO BAZAR POS
                </h2>
            </div>

            {{-- Menu --}}
            <div class="flex flex-col md:flex-row items-center justify-center gap-10 mt-16">

                {{-- Dine In --}}
               <a href="{{ route('pos.index') }}" class="group flex flex-col items-center">

    {{-- ICON --}}
    <img
        src="{{ asset('assets/images/dinein.svg') }}"
        alt="Dine In"
        class="w-52 h-52 object-contain
               group-hover:scale-110
               transition duration-300"
    >

    {{-- TITLE --}}
    <h3 class="text-center text-4xl font-bold mt-4">
        Dine-In
    </h3>

</a>

                {{-- Take Away --}}
               <a href="{{ route('pos.index') }}" class="group flex flex-col items-center">

    {{-- ICON --}}
    <img
        src="{{ asset('assets/images/takeaway.svg') }}"
        alt="Dine In"
        class="w-52 h-52 object-contain
               group-hover:scale-110
               transition duration-300"
    >

    {{-- TITLE --}}
    <h3 class="text-center text-4xl font-bold mt-4">
        Take Away
    </h3>

</a>

            </div>

        </div>

    </div>

</body>

</html>