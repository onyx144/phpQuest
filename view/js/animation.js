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
});
