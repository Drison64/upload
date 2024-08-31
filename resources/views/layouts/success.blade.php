@extends("layouts.app")

@section("style")
    @parent
    .animate-checkmark {
    animation: checkmark-draw 0.5s ease-in-out forwards;
    }

    .animate-circle {
    animation: circle-draw 0.5s ease-in-out forwards;
    }

    @keyframes checkmark-draw {
    0% {
    stroke-dashoffset: 100;
    }

    100% {
    stroke-dashoffset: 0;
    }
    }

    @keyframes circle-draw {
    0% {
    stroke-dashoffset: 126;
    }

    100% {
    stroke-dashoffset: 0;
    }
    }
@endsection

<div class="flex flex-col items-center justify-center min-h-full">
    <svg class="w-24 h-24 {{ !isset($color) ? "text-green-500" : $color }}" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
        <circle cx="24" cy="24" r="20" stroke="currentColor" stroke-width="2" class="animate-circle" stroke-dasharray="126" stroke-dashoffset="126" />
        <path d="M16 24l5 5L32 17" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="animate-checkmark" stroke-dasharray="100" stroke-dashoffset="100" />
    </svg>
    <p class="mt-4 text-lg font-medium text-gray-700 select-none">{{ !isset($status) ? "Action succeeded" : $status }}</p>
</div>


