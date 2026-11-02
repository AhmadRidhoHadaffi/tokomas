export default {
  content: [
  './index.html',
  './src/**/*.{js,ts,jsx,tsx}'
],
  theme: {
    extend: {
      colors: {
        gold: {
          DEFAULT: '#D4AF37',
          dark: '#B8860B',
          deep: '#7A5A06',
          light: '#F3E6BD',
        },
        crimson: {
          DEFAULT: '#8B0000',
          dark: '#6A0000',
        },
        charcoal: {
          DEFAULT: '#1C1C1C',
          soft: '#2A2A2A',
        },
        ivory: {
          DEFAULT: '#FDF8F0',
          dark: '#F6EDDD',
        },
        wa: '#25D366',
        sidebar: '#111827',
        line: '#E5E7EB',
      },
      fontFamily: {
        serif: ['"Playfair Display"', 'Georgia', 'serif'],
        sans: ['Poppins', 'system-ui', 'sans-serif'],
      },
      backgroundImage: {
        'gold-gradient': 'linear-gradient(135deg, #D4AF37 0%, #B8860B 100%)',
      },
      boxShadow: {
        'gold-glow': '0 18px 50px -12px rgba(212, 175, 55, 0.55)',
        card: '0 1px 2px rgba(28, 28, 28, 0.04), 0 4px 16px -6px rgba(28, 28, 28, 0.08)',
      },
    },
  },
}
