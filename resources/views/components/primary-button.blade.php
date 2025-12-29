<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-campfire-500 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-campfire-600 focus:bg-campfire-600 active:bg-campfire-700 focus:outline-none focus:ring-2 focus:ring-campfire-400 focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
