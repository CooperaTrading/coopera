<div class="border-b border-gray-200 mb-6 bg-white px-4 pt-4 rounded-t-xl">
    <nav class="-mb-px flex gap-6" aria-label="Tabs">
        <a href="{{ route('admin.quote-requests.index') }}"
            class="inline-flex items-center gap-2 py-3 px-1 border-b-2 font-medium text-sm transition {{ request()->routeIs('admin.quote-requests.*') ? 'border-indigo-600 text-indigo-600 font-bold' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
            <span>📨</span> Quote Requests
        </a>
        <a href="{{ route('admin.proforma-templates.index') }}"
            class="inline-flex items-center gap-2 py-3 px-1 border-b-2 font-medium text-sm transition {{ request()->routeIs('admin.proforma-templates.*') ? 'border-indigo-600 text-indigo-600 font-bold' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
            <span>🎛️</span> Proforma Template Builder
        </a>
    </nav>
</div>
