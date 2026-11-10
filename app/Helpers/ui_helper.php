<?php

/**
 * Shared UI helpers.
 *
 * The button classes live here so the Button component and native controls
 * (tabs, pagination, table sort) all use exactly the same design tokens.
 */

if (! function_exists('ui_button')) {
    /**
     * Render the shared button component in an isolated data scope.
     *
     * CodeIgniter merges view data across nested renders, so reading the
     * component options from the ambient scope would let page variables such
     * as $id or $name leak into every button. Wrapping the options in a single
     * key keeps the component predictable.
     *
     * @param array<string, mixed> $options
     */
    function ui_button(array $options = []): string
    {
        return view('components/ui/button', ['uiButton' => $options], ['saveData' => false]);
    }
}

if (! function_exists('button_class')) {
    /**
     * Build the class list for a button, mirroring design/src/components/ui/Button.tsx.
     */
    function button_class(string $variant = 'primary', string $size = 'md', string $extra = '', string $align = 'center'): string
    {
        $variants = [
            'primary' => 'bg-gold-gradient text-charcoal shadow-sm hover:brightness-110 hover:shadow-gold-glow',
            'outline' => 'border border-gold text-gold-deep bg-transparent hover:bg-gold/10',
            'outlineDark' => 'border border-gold/80 text-gold bg-transparent hover:bg-gold/10 hover:border-gold',
            'outlineLight' => 'border border-white/70 text-white bg-transparent hover:bg-white/10',
            'outlineCharcoal' => 'border border-charcoal/70 text-charcoal bg-transparent hover:bg-charcoal/10',
            'ghost' => 'text-charcoal/70 hover:bg-ivory-dark hover:text-charcoal',
            'ghostSubtle' => 'text-charcoal/40 hover:bg-ivory hover:text-charcoal',
            'ghostDark' => 'text-white/65 hover:bg-white/10 hover:text-white',
            'ghostDanger' => 'text-crimson hover:bg-crimson/10 hover:text-crimson-dark',
            'ghostCurrent' => 'text-current opacity-70 hover:bg-black/5 hover:opacity-100',
            'danger' => 'bg-crimson text-white hover:bg-crimson-dark',
            'dark' => 'bg-charcoal text-white hover:bg-charcoal-soft',
            'light' => 'bg-white text-charcoal border border-line hover:border-gold/60 hover:bg-ivory',
            'whatsapp' => 'bg-wa text-white shadow-sm hover:brightness-110',
        ];

        $sizes = [
            // The design uses h-9 (36px) for sm; we use h-10 (40px) to keep
            // touch targets at the required 40-44px minimum.
            'sm' => 'h-10 px-3.5 text-xs gap-1.5',
            'md' => 'h-11 px-5 text-sm gap-2',
            'lg' => 'h-12 px-6 text-[15px] gap-2',
            'none' => '',
            'icon-sm' => 'h-10 w-10 p-0',
            'icon-md' => 'h-11 w-11 p-0',
            'icon-lg' => 'h-12 w-12 p-0',
        ];

        $aligns = [
            'start' => 'justify-start',
            'center' => 'justify-center',
            'end' => 'justify-end',
            'between' => 'justify-between',
        ];

        $base = 'inline-flex items-center ' . ($aligns[$align] ?? $aligns['center']) . ' whitespace-nowrap rounded-lg font-medium '
            . 'transition-[background-color,color,box-shadow,transform,filter,border-color] duration-150 ease-out '
            . 'active:scale-[0.98] disabled:pointer-events-none disabled:opacity-50 '
            . 'focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-gold/30';

        return trim($base . ' '
            . ($variants[$variant] ?? $variants['primary']) . ' '
            . ($sizes[$size] ?? $sizes['md']) . ' '
            . $extra);
    }
}

if (! function_exists('button_icon_only_class')) {
    /**
     * Square sizing for icon-only buttons.
     *
     * @deprecated Use button_class($variant, 'icon-sm|icon-md|icon-lg') instead.
     */
    function button_icon_only_class(string $size = 'md'): string
    {
        return match ($size) {
            'sm' => 'h-10 w-10 p-0',
            'lg' => 'h-12 w-12 p-0',
            default => 'h-11 w-11 p-0',
        };
    }
}
