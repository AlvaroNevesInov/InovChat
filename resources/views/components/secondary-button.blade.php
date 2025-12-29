<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2 bg-white border border-ash-200 rounded-lg font-medium text-sm text-ash-700 hover:bg-ash-50 hover:border-ash-300 focus:outline-none focus:ring-2 focus:ring-campfire-400 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
