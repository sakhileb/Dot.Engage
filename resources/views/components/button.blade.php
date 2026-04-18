<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-5 py-2.5 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest disabled:opacity-50 transition ease-in-out duration-150 focus:outline-none focus:ring-2 focus:ring-offset-2']) }} style="background-color: #1B2878;" onmouseover="this.style.backgroundColor='#253399'" onmouseout="this.style.backgroundColor='#1B2878'" onfocus="this.style.outlineColor='#F5C200'">
    {{ $slot }}
</button>
