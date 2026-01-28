<?php defined('GD_ACCESS') or die('You can not access the file directly!'); ?>
<div class="section_main_bg">
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1920 1080" width="100%" height="100%" preserveAspectRatio="xMidYMid slice">
  <defs>
    <radialGradient id="bg-vignette" cx="50%" cy="50%" r="70%">
      <stop offset="0%" style="stop-color:#0a0f14;stop-opacity:0.9" />
      <stop offset="100%" style="stop-color:#020305;stop-opacity:1" />
    </radialGradient>

    <filter id="red-pulse-glow" x="-50%" y="-50%" width="200%" height="200%">
      <feGaussianBlur stdDeviation="5" result="blur" />
        <feColorMatrix in="blur" type="matrix" values="1 0 0 0 0.1  0 0 0 0 0  0 0 0 0 0  0 0 0 1 0" result="redBlur" />
      <feBlend in="SourceGraphic" in2="redBlur" mode="screen" />
    </filter>

    <pattern id="hex-grid" width="100" height="173.2" patternUnits="userSpaceOnUse">
      <path d="M50 0 L100 28.86 L100 86.6 L50 115.46 L0 86.6 L0 28.86 Z" fill="none" stroke="#00f3ff" stroke-width="1" opacity="0.15"/>
    </pattern>
    
    <pattern id="scanlines-bg" patternUnits="userSpaceOnUse" width="2" height="4">
      <rect width="2" height="1" fill="#000" opacity="0.2"/>
    </pattern>
  </defs>

  <style>
    /* Animations */

    /* 1. Медленный дрейф сетки */
    @keyframes gridDrift {
      0% { transform: translateY(0px); }
      100% { transform: translateY(-173.2px); } /* Двигаем ровно на высоту одного тайла для бесшовности */
    }

    /* 2. Пульсация красных узлов */
    @keyframes redPulse {
        0%, 100% { opacity: 0.2; transform: scale(1); }
        50% { opacity: 0.8; transform: scale(1.3); }
    }

    /* Применение анимаций */
    .grid-layer {
      animation: gridDrift 20s linear infinite;
    }

    .red-node {
        transform-origin: center;
        animation: redPulse 6s ease-in-out infinite alternate;
    }
    /* Небольшие задержки для рассинхрона пульсации */
    .rn-1 { animation-delay: 0s; }
    .rn-2 { animation-delay: 2s; }
    .rn-3 { animation-delay: 4s; }

  </style>

  <rect width="100%" height="100%" fill="#05080a" />
  <rect width="100%" height="100%" fill="url(#bg-vignette)" opacity="0.8" />

  <rect x="-100" y="-200" width="2200" height="1500" fill="url(#hex-grid)" class="grid-layer" />

  <g filter="url(#red-pulse-glow)">
      <circle cx="450" cy="300" r="8" fill="#ff003c" class="red-node rn-1" />
      <circle cx="1250" cy="750" r="12" fill="#ff003c" class="red-node rn-2" />
      <circle cx="1650" cy="200" r="6" fill="#ff003c" class="red-node rn-3" />
      <circle cx="250" cy="850" r="10" fill="#ff003c" class="red-node rn-2" opacity="0.6"/>
  </g>

  <rect width="100%" height="100%" fill="url(#scanlines-bg)" opacity="0.3" pointer-events="none"/>

</svg>
</div>