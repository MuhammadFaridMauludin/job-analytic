<div class="px-4">
    <div class="text-white font-semibold text-sm">
        {{
            str(request()->route()?->getName() ?? 'dashboard')
                ->afterLast('.')
                ->replace('-', ' ')
                ->title()
        }}
    </div>
</div>