@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 focus:border-[#0078B7] focus:ring-0 rounded-md shadow-sm']) }}>
