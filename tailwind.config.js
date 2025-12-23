/** @type {import('tailwindcss').Config} */
module.exports = {
  mode: 'jit',
  content: [
    "./views/**/*.php",
    "./view/**/*.php", 
    "./admin/**/*.php",
    "./*.php",
    "./src/**/*.{html,js,php}",
    "./controller/**/*.php" 
  ],
  safelist: [
    'mt-0','mt-1','mt-2','mt-3','mt-4','mt-5','mt-6',
    'pb-0','pb-1','pb-2','pb-3','pb-4','pb-5','pb-6',
    'gap-0','gap-1','gap-2','gap-3','gap-4','gap-5','gap-6',
  ],
  theme: {
    extend: {
      colors: {
        primary: {
          50: '#eff6ff',
          100: '#dbeafe',
          200: '#bfdbfe',
          300: '#93c5fd',
          400: '#60a5fa',
          500: '#3b82f6',
          600: '#2563eb',
          700: '#1d4ed8',
          800: '#1e40af',
          900: '#1e3a8a',
        },
        accent: {
          50: '#fef7ee',
          100: '#fdedd4',
          200: '#fbd7a9',
          300: '#f8bb72',
          400: '#f5953a',
          500: '#f2751a',
          600: '#e35a10',
          700: '#bc4210',
          800: '#953514',
          900: '#782e14',
        },
        cyber: {
          'neon-blue': '#00f3ff',
          'neon-green': '#00ff41',
          'neon-pink': '#ff0080',
          'dark-bg': '#0a0a0a',
          'panel-bg': '#1a1a1a',
          'border': '#333333',
        }
      },
      fontFamily: {
        'cyber': ['Orbitron', 'monospace'],
        'mono': ['Courier New', 'monospace'],
      },
      animation: {
        'cyber-pulse': 'cyber-pulse 2s infinite',
        'cyber-glow': 'cyber-glow 3s ease-in-out infinite alternate',
        'fade-in-up': 'fade-in-up 0.5s ease-out',
        'glitch': 'glitch 0.3s infinite',
      },
      keyframes: {
        'cyber-pulse': {
          '0%, 100%': { 
            opacity: '1',
            boxShadow: '0 0 5px #00f3ff, 0 0 10px #00f3ff, 0 0 15px #00f3ff'
          },
          '50%': { 
            opacity: '0.7',
            boxShadow: '0 0 2px #00f3ff, 0 0 5px #00f3ff, 0 0 8px #00f3ff'
          }
        },
        'cyber-glow': {
          '0%': { 
            textShadow: '0 0 5px #00f3ff, 0 0 10px #00f3ff, 0 0 15px #00f3ff'
          },
          '100%': { 
            textShadow: '0 0 10px #00f3ff, 0 0 20px #00f3ff, 0 0 30px #00f3ff'
          }
        },
        'fade-in-up': {
          '0%': {
            opacity: '0',
            transform: 'translateY(20px)'
          },
          '100%': {
            opacity: '1',
            transform: 'translateY(0)'
          }
        },
        'glitch': {
          '0%, 100%': { transform: 'translate(0)' },
          '20%': { transform: 'translate(-2px, 2px)' },
          '40%': { transform: 'translate(-2px, -2px)' },
          '60%': { transform: 'translate(2px, 2px)' },
          '80%': { transform: 'translate(2px, -2px)' }
        }
      },
      backgroundImage: {
        'cyber-gradient': 'linear-gradient(45deg, #0a0a0a 0%, #1a1a1a 50%, #0a0a0a 100%)',
        'neon-gradient': 'linear-gradient(90deg, #00f3ff, #00ff41, #ff0080)',
      },
      boxShadow: {
        'cyber': '0 0 10px rgba(0, 243, 255, 0.5), inset 0 0 10px rgba(0, 243, 255, 0.1)',
        'neon': '0 0 20px rgba(0, 243, 255, 0.8)',
      }
    },
  },
  plugins: [],
} 