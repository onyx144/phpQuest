<?php defined('GD_ACCESS') or die('You can not access the file directly!'); ?>
<div class="section_main_bg">
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1920 1080" width="100%" height="100%" preserveAspectRatio="xMidYMid slice" id="mainBgSvg">
  <defs>
    <radialGradient id="bg-vignette" cx="50%" cy="50%" r="70%">
      <stop offset="0%" style="stop-color:#0a0f14;stop-opacity:0.9" />
      <stop offset="100%" style="stop-color:#020305;stop-opacity:1" />
    </radialGradient>

    <radialGradient id="bg-vignette-red" cx="50%" cy="50%" r="75%">
      <stop offset="0%" style="stop-color:#4a0005;stop-opacity:0.9" />
      <stop offset="100%" style="stop-color:#1a0002;stop-opacity:1" />
    </radialGradient>

    <filter id="red-pulse-glow" x="-50%" y="-50%" width="200%" height="200%">
      <feGaussianBlur stdDeviation="5" result="blur" />
        <feColorMatrix in="blur" type="matrix" values="1 0 0 0 0.1  0 0 0 0 0  0 0 0 0 0  0 0 0 1 0" result="redBlur" />
      <feBlend in="SourceGraphic" in2="redBlur" mode="screen" />
    </filter>

    <filter id="white-glow">
      <feGaussianBlur stdDeviation="3" result="coloredBlur" />
      <feMerge>
        <feMergeNode in="coloredBlur" />
        <feMergeNode in="SourceGraphic" />
      </feMerge>
    </filter>

    <pattern id="hex-grid" width="100" height="173.2" patternUnits="userSpaceOnUse">
      <path d="M50 0 L100 28.86 L100 86.6 L50 115.46 L0 86.6 L0 28.86 Z" fill="none" stroke="#00f3ff" stroke-width="1" opacity="0.15"/>
    </pattern>

    <pattern id="hex-grid-red" width="100" height="173.2" patternUnits="userSpaceOnUse">
      <path d="M50 0 L100 28.86 L100 86.6 L50 115.46 L0 86.6 L0 28.86 Z" fill="none" stroke="#ff003c" stroke-width="1.5" opacity="0.3"/>
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
      100% { transform: translateY(-173.2px); }
    }

    /* 2. Пульсация красных узлов */
    @keyframes redPulse {
        0%, 100% { opacity: 0.2; transform: scale(1); }
        50% { opacity: 0.8; transform: scale(1.3); }
    }

    /* Red background fill animation */
    @keyframes redFill {
      0% { clip-path: circle(0% at 50% 50%); }
      100% { clip-path: circle(100% at 50% 50%); }
    }

    /* Eye animations */
    @keyframes drawLines {
      0% { stroke-dashoffset: 1500; opacity: 0; }
      10% { opacity: 1; }
      100% { stroke-dashoffset: 0; opacity: 1;}
    }

    @keyframes lidOpenTop {
      0% { transform: translateY(0); }
      100% { transform: translateY(-120px) scale(1.1); opacity: 0; }
    }

    @keyframes lidOpenBottom {
      0% { transform: translateY(0); }
      100% { transform: translateY(120px) scale(1.1); opacity: 0; }
    }

    @keyframes irisSpin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }

    /* Применение анимаций */
    .grid-layer {
      animation: gridDrift 20s linear infinite;
    }

    .grid-layer-red {
      animation: gridDrift 15s linear infinite;
    }

    .red-node {
        transform-origin: center;
        animation: redPulse 6s ease-in-out infinite alternate;
    }
    .rn-1 { animation-delay: 0s; }
    .rn-2 { animation-delay: 2s; }
    .rn-3 { animation-delay: 4s; }

    /* Red background overlay - hidden by default */
    .red-bg-overlay {
      opacity: 0;
      clip-path: circle(0% at 50% 50%);
      pointer-events: none;
    }

    .red-bg-overlay.active {
      opacity: 1;
      animation: redFill 2s ease-out forwards;
      pointer-events: auto;
    }

    /* Eye container - hidden by default */
    .eye-container {
      display: none;
    }

    .eye-container.active {
      display: block;
    }

    /* IMPORTANT: animations start only when .eye-container becomes .active */
    .eye-part {
      fill: none;
      stroke: #ffffff;
      stroke-width: 4;
      stroke-linecap: round;
      stroke-dasharray: 1500;
      stroke-dashoffset: 1500;
      opacity: 0;
    }

    .eye-container.active .eye-part {
      animation: drawLines 3s cubic-bezier(0.25, 0.46, 0.45, 0.94) forwards;
    }

    .eye-lid-top {
      transform-origin: 960px 540px;
    }

    .eye-container.active .eye-lid-top {
      animation: drawLines 1s ease-out forwards, lidOpenTop 2.5s cubic-bezier(0.4, 0.0, 0.2, 1) 0.5s forwards;
    }

    .eye-lid-bottom {
      transform-origin: 960px 540px;
    }

    .eye-container.active .eye-lid-bottom {
      animation: drawLines 1s ease-out forwards, lidOpenBottom 2.5s cubic-bezier(0.4, 0.0, 0.2, 1) 0.5s forwards;
    }

    .spinning-iris {
      transform-origin: 960px 540px;
      animation: none;
    }

    .eye-container.active .spinning-iris {
      /* starts AFTER the opening phase, like in test.html */
      animation: irisSpin 8s linear infinite 3s;
    }

    .iris-dashed {
      stroke-width: 3;
      stroke-dasharray: 40 60 !important;
    }

    .eye-container.active .iris-dashed {
      animation: drawLines 2.5s ease-out forwards !important;
    }

    .iris-dashed-inner {
      stroke-width: 5;
      stroke-dasharray: 20 30 !important;
    }

    .eye-container.active .iris-dashed-inner {
      animation: drawLines 2s ease-out forwards !important;
    }

  </style>

  <!-- Normal background -->
  <g id="normalBg">
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
  </g>

  <!-- Red background overlay - hidden by default -->
  <g id="redBg" class="red-bg-overlay">
    <rect width="100%" height="100%" fill="#1a0002" />
    <rect width="100%" height="100%" fill="url(#bg-vignette-red)" />
    <rect x="-100" y="-200" width="2200" height="1500" fill="url(#hex-grid-red)" class="grid-layer-red" />
    <rect width="100%" height="100%" fill="url(#scanlines-bg)" pointer-events="none"/>
  </g>

  <!-- Eye animation - hidden by default -->
  <g id="eyeContainer" class="eye-container" filter="url(#white-glow)">
    <g class="spinning-iris">
      <circle cx="960" cy="540" r="140" class="eye-part iris-dashed" />
      <circle cx="960" cy="540" r="80" class="eye-part iris-dashed-inner" />
      <circle cx="960" cy="540" r="30" fill="#ffffff" opacity="0">
        <animate attributeName="opacity" from="0" to="1" begin="2.5s" dur="0.5s" fill="freeze" />
      </circle>
    </g>
    <circle cx="960" cy="540" r="200" class="eye-part" stroke-width="6" />
    <path d="M 760,540 Q 960,340 1160,540" class="eye-part eye-lid-top" />
    <path d="M 760,540 Q 960,740 1160,540" class="eye-part eye-lid-bottom" />
  </g>

</svg>
</div>