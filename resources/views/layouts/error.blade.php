@extends("layouts.app")

@section("style")
    .animate-circle {
    animation: circle-draw-reverse 0.5s ease-in-out forwards;
    }

    .animate-error-x {
    animation: error-x-draw 0.5s ease-in-out forwards;
    }

    @keyframes circle-draw-reverse {
    0% {
    stroke-dashoffset: 126;
    }

    100% {
    stroke-dashoffset: 0;
    }
    }

    @keyframes error-x-draw {
    0% {
    stroke-dashoffset: 50;
    }

    100% {
    stroke-dashoffset: 0;
    }
    }
@endsection

<body class="flex flex-col items-center justify-center min-h-full">
    <svg class="w-24 h-24 {{ !isset($color) ? "text-red-500" : $color }}" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
        <circle cx="24" cy="24" r="20" stroke="currentColor" stroke-width="2" class="animate-circle" stroke-dasharray="126" stroke-dashoffset="126" />
        <path d="M18 18l12 12M30 18l-12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="animate-error-x" stroke-dasharray="50" stroke-dashoffset="50" />
    </svg>
    <p class="mt-4 text-lg font-medium text-gray-700">{{ !isset($status) ? "Error succeeded" : $status }}</p>
</body>

