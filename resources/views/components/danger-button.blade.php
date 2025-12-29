<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-ember-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-ember-700 active:bg-ember-800 focus:outline-none focus:ring-2 focus:ring-ember-400 focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
