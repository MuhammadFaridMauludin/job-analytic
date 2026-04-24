<div class="p-4 border-t border-gray-700">
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-full bg-gray-700 flex items-center justify-center text-white text-sm border border-white/20">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </div>

        <div class="flex-1">
            <div class="text-sm text-white font-medium">
                {{ auth()->user()->name }}
            </div>
            <div class="text-xs text-gray-400">
                {{ auth()->user()->email }}
            </div>
        </div>
    </div>

    {{-- <form method="POST" action="{{ route('filament.admin.auth.logout') }}" class="mt-3">
        @csrf
        <button class="w-full text-left text-sm text-red-400 hover:text-red-300">
            Logout
        </button>
    </form> --}}
</div>