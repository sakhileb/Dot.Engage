@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm']) }} style="color: #1B2878;">
    {{ $value ?? $slot }}
</label>
