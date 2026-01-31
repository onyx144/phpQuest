//Animation for Aegis Logo
document.addEventListener("DOMContentLoaded", function() {
  const shieldContainer = document.getElementById('ShieldContainer');
  const textGroup = document.getElementById('TextGroup');

  if (shieldContainer && textGroup) {
    setTimeout(() => {
        shieldContainer.classList.remove('booting-up');
        shieldContainer.classList.add('booted');
        
        textGroup.classList.remove('booting-up');
        textGroup.classList.add('booted');
    }, 500);
  }

  // Error popup animation on main_logo click
  const mainLogo = document.querySelector('.main_logo');
  if (mainLogo) {
    mainLogo.addEventListener('click', function() {
      triggerErrorSequence();
    });
  }
});

// Error popup sequence
function triggerErrorSequence() {
  const alarmAudio = new Audio;
  alarmAudio.src = '/music/alarm.mp3';
  alarmAudio.play();

  // Выключить аудио через 20 секунд
  setTimeout(() => {
    alarmAudio.pause();
    alarmAudio.currentTime = 0;
  }, 10000);
  
  // Show error popups
  showErrorPopups();
  
  // After popups appear, start disappearing sequence
  setTimeout(() => {
    hideErrorPopups();
    hideGameContent();
    fillRedBackground();
  }, 6500);
}

function playAlarm() {
  const el = document.getElementById('alarmAudio');
  if (!el) return;
  try {
    el.currentTime = 0;
  } catch (_) {}
  const p = el.play();
  if (p && typeof p.catch === 'function') {
    p.catch(() => {
      // autoplay restrictions or other issues - ignore silently
    });
  }
}

// Show 4 error popups with animation
function showErrorPopups() {
  const container = document.getElementById('errorPopupContainer');
  if (!container) return;

  container.style.display = 'block';
  
  const popups = container.querySelectorAll('.error-popup');
  popups.forEach((popup, index) => {
    popup.style.animation = 'none';
    setTimeout(() => {
      const isThird = popup.classList.contains('error-popup-3');
      const isFourth = popup.classList.contains('error-popup-4');
      const animName = isThird ? 'errorPopupAppear3' : (isFourth ? 'errorPopupAppear4' : 'errorPopupAppear');
      popup.style.animation = `${animName} 0.9s cubic-bezier(0.34, 1.56, 0.64, 1) ${index * 0.35}s forwards`;
    }, 10);
  });
}

// Hide error popups with glitch effect
function hideErrorPopups() {
  const container = document.getElementById('errorPopupContainer');
  if (!container) return;

  const popups = container.querySelectorAll('.error-popup');
  popups.forEach((popup, index) => {
    setTimeout(() => {
      const isThird = popup.classList.contains('error-popup-3');
      const isFourth = popup.classList.contains('error-popup-4');
      const glitchAnim = isThird ? 'errorPopupGlitch3' : (isFourth ? 'errorPopupGlitch4' : 'errorPopupGlitch');
      const disappearAnim = isThird ? 'errorPopupDisappear3' : (isFourth ? 'errorPopupDisappear4' : 'errorPopupDisappear');
      
      popup.style.animation = `${glitchAnim} 1.4s ease-in-out forwards`;
      setTimeout(() => {
        popup.style.animation = `${disappearAnim} 1s ease-in forwards`;
      }, 1100);
    }, index * 250);
  });
}

// Hide game content with animation
function hideGameContent() {
  const gameContent = document.querySelector('.container_big.content_game');
  if (gameContent) {
    setTimeout(() => {
      gameContent.style.animation = 'contentGlitch 1s ease-in-out forwards';
      setTimeout(() => {
        gameContent.style.animation = 'contentDisappear 0.8s ease-in forwards';
      }, 800);
    }, 500);
  }
}

// Fill background with red from center
function fillRedBackground() {
  const redBg = document.getElementById('redBg');
  const normalBg = document.getElementById('normalBg');
  
  if (redBg && normalBg) {
    setTimeout(() => {
      redBg.classList.add('active');
      // Hide normal background gradually
      normalBg.style.opacity = '0';
      normalBg.style.transition = 'opacity 1s ease-out';
      // Start eye animation after red background fills
      setTimeout(() => {
        showEyeAnimation();
      }, 2000);
    }, 1000);
  }
}

// Show eye animation
function showEyeAnimation() {
  const eyeContainer = document.getElementById('eyeContainer');
  if (eyeContainer) {
    // force restart opening animation every time
    eyeContainer.classList.remove('active');
    // reflow
    void eyeContainer.offsetWidth;
    eyeContainer.classList.add('active');
  }
}

// Add CSS animations dynamically
const style = document.createElement('style');
style.textContent = `
  /* Make main_logo clickable */
  .main_logo {
    cursor: pointer;
    transition: opacity 0.3s ease;
  }

  .main_logo:hover {
    opacity: 0.8;
  }

  /* Error popup container */
  .error-popup-container {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 10000;
    pointer-events: none;
    display: none;
  }

  /* === BIG 4-PANEL ERROR POPUPS (override previous smaller layout) === */
  .error-popup-container {
    display: none;
    pointer-events: none;
  }

  .error-popup {
    position: fixed;
    inset: auto;
    width: 50vw;
    height: 50vh;
    border-radius: 0;
    padding: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 18px;
    background: radial-gradient(circle at 50% 50%, rgba(255, 0, 60, 0.22), rgba(26, 0, 2, 0.9));
    border: 2px solid rgba(255, 0, 60, 0.85);
    box-shadow: 0 0 60px rgba(255, 0, 60, 0.45), inset 0 0 30px rgba(255, 0, 60, 0.15);
    backdrop-filter: blur(6px);
    opacity: 0;
    pointer-events: none;
    transform: scale(0.92);
  }

  .error-popup-1 { top: 0; left: 0; }
  .error-popup-2 { top: 0; left: 50vw; }
  .error-popup-3 { top: 50vh; left: 0; transform-origin: center; }
  .error-popup-4 { top: 50vh; left: 50vw; transform-origin: center; }

  .error-icon svg {
    width: 120px;
    height: 120px;
  }

  .error-text {
    font-size: clamp(48px, 5vw, 96px);
    letter-spacing: 12px;
  }

  @keyframes errorPopupAppear {
    0% { opacity: 0; transform: scale(0.85) translateY(-40px); filter: blur(14px); }
    60% { opacity: 1; transform: scale(1.03) translateY(0); filter: blur(0); }
    100% { opacity: 1; transform: scale(1) translateY(0); filter: blur(0); }
  }

  @keyframes errorPopupAppear3 {
    0% { opacity: 0; transform: scale(0.85) translateY(40px); filter: blur(14px); }
    60% { opacity: 1; transform: scale(1.03) translateY(0); filter: blur(0); }
    100% { opacity: 1; transform: scale(1) translateY(0); filter: blur(0); }
  }

  @keyframes errorPopupAppear4 {
    0% { opacity: 0; transform: scale(0.85) translate(40px, 40px); filter: blur(14px); }
    60% { opacity: 1; transform: scale(1.03) translate(0, 0); filter: blur(0); }
    100% { opacity: 1; transform: scale(1) translate(0, 0); filter: blur(0); }
  }

  @keyframes errorPopupGlitch4 {
    0%, 100% { transform: translate(0) scale(1); filter: hue-rotate(0deg); }
    20% { transform: translate(6px, -4px) scale(1.01); filter: hue-rotate(120deg) brightness(1.2); }
    40% { transform: translate(-6px, 4px) scale(0.99); filter: hue-rotate(240deg) brightness(0.9); }
    60% { transform: translate(4px, 6px) scale(1.02); filter: hue-rotate(360deg) brightness(1.3); }
    80% { transform: translate(-4px, -6px) scale(0.98); filter: hue-rotate(90deg) brightness(1.05); }
  }

  @keyframes errorPopupDisappear {
    0% { opacity: 1; transform: scale(1); filter: blur(0); }
    60% { opacity: 1; transform: scale(1.06); filter: blur(2px); }
    100% { opacity: 0; transform: scale(0.7); filter: blur(18px); }
  }

  @keyframes errorPopupDisappear3 {
    0% { opacity: 1; transform: scale(1); filter: blur(0); }
    60% { opacity: 1; transform: scale(1.06); filter: blur(2px); }
    100% { opacity: 0; transform: scale(0.7); filter: blur(18px); }
  }

  @keyframes errorPopupDisappear4 {
    0% { opacity: 1; transform: scale(1); filter: blur(0); }
    60% { opacity: 1; transform: scale(1.06); filter: blur(2px); }
    100% { opacity: 0; transform: scale(0.7); filter: blur(18px); }
  }

  .error-popup {
    position: absolute;
    background: linear-gradient(135deg, rgba(255, 0, 60, 0.15), rgba(74, 0, 5, 0.25));
    border: 2px solid #ff003c;
    border-radius: 8px;
    padding: 30px 50px;
    box-shadow: 0 0 30px rgba(255, 0, 60, 0.6), inset 0 0 20px rgba(255, 0, 60, 0.2);
    backdrop-filter: blur(5px);
    opacity: 0;
    transform: scale(0.5) translateY(-50px);
    pointer-events: auto;
  }

  .error-popup-1 {
    top: 20%;
    left: 10%;
  }

  .error-popup-2 {
    top: 50%;
    right: 15%;
  }

  .error-popup-3 {
    bottom: 20%;
    left: 50%;
    transform: translateX(-50%) scale(0.5) translateY(50px);
    transform-origin: center;
  }

  .error-icon {
    text-align: center;
    margin-bottom: 15px;
    filter: drop-shadow(0 0 10px rgba(255, 0, 60, 0.8));
  }

  .error-text {
    color: #ff003c;
    font-family: 'Courier New', monospace;
    font-size: 32px;
    font-weight: bold;
    text-align: center;
    text-shadow: 0 0 10px rgba(255, 0, 60, 0.8), 0 0 20px rgba(255, 0, 60, 0.5);
    letter-spacing: 5px;
  }

  /* Popup appear animation */
  @keyframes errorPopupAppear {
    0% {
      opacity: 0;
      transform: scale(0.3) translateY(-100px);
      filter: blur(10px);
    }
    50% {
      transform: scale(1.1) translateY(0);
    }
    100% {
      opacity: 1;
      transform: scale(1) translateY(0);
      filter: blur(0);
    }
  }

  .error-popup-3.error-popup {
    transform-origin: center;
  }

  @keyframes errorPopupAppear {
    0% {
      opacity: 0;
      transform: scale(0.3) translateY(-100px);
      filter: blur(10px);
    }
    50% {
      transform: scale(1.1) translateY(0);
    }
    100% {
      opacity: 1;
      transform: scale(1) translateY(0);
      filter: blur(0);
    }
  }

  .error-popup-3 {
    transform-origin: center;
  }

  .error-popup-3.error-popup {
    animation-name: errorPopupAppear3;
  }

  @keyframes errorPopupAppear3 {
    0% {
      opacity: 0;
      transform: translateX(-50%) scale(0.3) translateY(100px);
      filter: blur(10px);
    }
    50% {
      transform: translateX(-50%) scale(1.1) translateY(0);
    }
    100% {
      opacity: 1;
      transform: translateX(-50%) scale(1) translateY(0);
      filter: blur(0);
    }
  }

  /* Glitch effect */
  @keyframes errorPopupGlitch {
    0%, 100% {
      transform: translate(0);
      filter: hue-rotate(0deg);
    }
    10% {
      transform: translate(-2px, 2px);
      filter: hue-rotate(90deg);
    }
    20% {
      transform: translate(2px, -2px);
      filter: hue-rotate(180deg);
    }
    30% {
      transform: translate(-2px, -2px);
      filter: hue-rotate(270deg);
    }
    40% {
      transform: translate(2px, 2px);
      filter: hue-rotate(360deg);
    }
    50% {
      transform: translate(-2px, 2px) scale(1.05);
      filter: hue-rotate(90deg) brightness(1.5);
    }
    60% {
      transform: translate(2px, -2px) scale(0.95);
      filter: hue-rotate(180deg) brightness(0.8);
    }
    70% {
      transform: translate(-2px, -2px) scale(1.1);
      filter: hue-rotate(270deg) brightness(1.3);
    }
    80% {
      transform: translate(2px, 2px) scale(0.9);
      filter: hue-rotate(360deg) brightness(0.9);
    }
    90% {
      transform: translate(-2px, 2px) scale(1.05);
      filter: hue-rotate(90deg) brightness(1.2);
    }
  }

  @keyframes errorPopupGlitch3 {
    0%, 100% {
      transform: translateX(-50%) translate(0);
      filter: hue-rotate(0deg);
    }
    10% {
      transform: translateX(-50%) translate(-2px, 2px);
      filter: hue-rotate(90deg);
    }
    20% {
      transform: translateX(-50%) translate(2px, -2px);
      filter: hue-rotate(180deg);
    }
    30% {
      transform: translateX(-50%) translate(-2px, -2px);
      filter: hue-rotate(270deg);
    }
    40% {
      transform: translateX(-50%) translate(2px, 2px);
      filter: hue-rotate(360deg);
    }
    50% {
      transform: translateX(-50%) translate(-2px, 2px) scale(1.05);
      filter: hue-rotate(90deg) brightness(1.5);
    }
    60% {
      transform: translateX(-50%) translate(2px, -2px) scale(0.95);
      filter: hue-rotate(180deg) brightness(0.8);
    }
    70% {
      transform: translateX(-50%) translate(-2px, -2px) scale(1.1);
      filter: hue-rotate(270deg) brightness(1.3);
    }
    80% {
      transform: translateX(-50%) translate(2px, 2px) scale(0.9);
      filter: hue-rotate(360deg) brightness(0.9);
    }
    90% {
      transform: translateX(-50%) translate(-2px, 2px) scale(1.05);
      filter: hue-rotate(90deg) brightness(1.2);
    }
  }

  /* Popup disappear animation */
  @keyframes errorPopupDisappear {
    0% {
      opacity: 1;
      transform: scale(1) rotate(0deg);
      filter: blur(0);
    }
    50% {
      transform: scale(1.2) rotate(5deg);
      filter: blur(2px);
    }
    100% {
      opacity: 0;
      transform: scale(0.3) rotate(15deg) translateY(-100px);
      filter: blur(10px);
    }
  }

  .error-popup-3.error-popup {
    animation-name: errorPopupDisappear3;
  }

  @keyframes errorPopupDisappear3 {
    0% {
      opacity: 1;
      transform: translateX(-50%) scale(1) rotate(0deg);
      filter: blur(0);
    }
    50% {
      transform: translateX(-50%) scale(1.2) rotate(5deg);
      filter: blur(2px);
    }
    100% {
      opacity: 0;
      transform: translateX(-50%) scale(0.3) rotate(15deg) translateY(100px);
      filter: blur(10px);
    }
  }

  /* Content glitch and disappear */
  @keyframes contentGlitch {
    0%, 100% {
      transform: translate(0);
      filter: brightness(1);
    }
    10% {
      transform: translate(-3px, 3px);
      filter: brightness(1.2) hue-rotate(90deg);
    }
    20% {
      transform: translate(3px, -3px);
      filter: brightness(0.8) hue-rotate(180deg);
    }
    30% {
      transform: translate(-3px, -3px);
      filter: brightness(1.1) hue-rotate(270deg);
    }
    40% {
      transform: translate(3px, 3px);
      filter: brightness(0.9) hue-rotate(360deg);
    }
    50% {
      transform: translate(-3px, 3px) scale(1.02);
      filter: brightness(1.3) hue-rotate(90deg);
    }
    60% {
      transform: translate(3px, -3px) scale(0.98);
      filter: brightness(0.7) hue-rotate(180deg);
    }
    70% {
      transform: translate(-3px, -3px) scale(1.05);
      filter: brightness(1.2) hue-rotate(270deg);
    }
    80% {
      transform: translate(3px, 3px) scale(0.95);
      filter: brightness(0.8) hue-rotate(360deg);
    }
  }

  @keyframes contentDisappear {
    0% {
      opacity: 1;
      transform: scale(1);
      filter: blur(0);
    }
    50% {
      transform: scale(0.95);
      filter: blur(3px);
    }
    100% {
      opacity: 0;
      transform: scale(0.8);
      filter: blur(10px);
    }
  }
`;

document.head.appendChild(style);
