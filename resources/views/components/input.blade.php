@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-slate-300 rounded-lg shadow-sm transition duration-150 ease-in-out focus:outline-none']) !!} onfocus="this.style.borderColor='#F5C200'; this.style.boxShadow='0 0 0 3px rgba(245,194,0,0.2)';" onblur="this.style.borderColor='#cbd5e1'; this.style.boxShadow='none';">
