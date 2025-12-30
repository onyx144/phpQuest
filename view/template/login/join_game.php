<?php
	defined('GD_ACCESS') or die('You can not access the file directly!');
	require_once(ROOT . '/view/blocks/header.php');
?>
<!-- Правильный viewport для мобильных устройств -->
<script>
	// Переопределяем viewport для мобильных устройств - выполняется немедленно
	(function() {
		function setMobileViewport() {
			var viewport = document.querySelector('meta[name="viewport"]');
			if (viewport) {
				viewport.setAttribute('content', 'width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes');
			} else {
				var meta = document.createElement('meta');
				meta.name = 'viewport';
				meta.content = 'width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes';
				document.getElementsByTagName('head')[0].appendChild(meta);
			}
		}
		
		// Проверяем сразу и при загрузке
		if (window.innerWidth <= 768 || /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
			setMobileViewport();
		}
		
		// Также при загрузке DOM
		if (document.readyState === 'loading') {
			document.addEventListener('DOMContentLoaded', setMobileViewport);
		}
	})();
</script>
<style>
	.hexagon-background-container {
		position: fixed;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		z-index: 0;
		background: #001221;
		pointer-events: none; /* Не блокирует клики */
	}
	 
	
	.hexagon-background-container canvas {
		display: block;
		width: 100%;
		height: 100%; /* Высота будет установлена через JavaScript */
		position: fixed;
		top: 0;
		left: 0;
		opacity: 0.3; /* Такая же прозрачность как на мобильных */
	}
	
	.join-game-content {
		position: relative;
		z-index: 1;
		min-height: 100vh;
		width: 100%;
		background-color: transparent;
	}
	
	/* Кнопки должны быть выше canvas */
	.cyber-button {
		position: relative;
		z-index: 10 !important;
		background-color: #000;
	}
	.active {
		background-color: #00f3ff;
		color: #0a0a0a;
	}
	
	/* Контейнер с кнопками действий */
	.flex.flex-col.sm\:flex-row.gap-4.justify-center.mb-16 {
		position: relative;
		z-index: 10 !important;
	}
	
	/* На мобильных устройствах - нормальный размер контента */
	@media (max-width: 768px) {
		* {
			box-sizing: border-box;
		}
		
		html {
			width: 100% !important;
			max-width: 100% !important;
			overflow-x: hidden !important;
			-webkit-text-size-adjust: 100%;
			-ms-text-size-adjust: 100%;
		}
		
		body {
			width: 100% !important;
			max-width: 100vw !important;
			overflow-x: hidden !important;
			margin: 0 !important;
			padding: 0 !important;
		}
		
		#main {
			width: 100% !important;
			max-width: 100% !important;
			overflow-x: hidden !important;
		}
		
		.hexagon-background-container {
			position: fixed;
			height: 100vh;
			height: 100dvh;
			overflow: hidden;
			width: 100% !important;
			max-width: 100% !important;
		}
		
		.hexagon-background-container canvas {
			position: fixed;
			top: 0;
			left: 0;
			width: 100vw !important;
			max-width: 100vw !important;
			height: 100vh;
			height: 100dvh;
			opacity: 0.3;
			object-fit: cover;
		}
		
		.join-game-content {
			position: relative;
			width: 100% !important;
			max-width: 100% !important;
			min-height: 100vh;
			min-height: 100dvh;
			background-color: rgba(0, 18, 33, 0.75);
			padding: 0 !important;
			margin: 0 !important;
			box-sizing: border-box;
		}
		
		/* Убираем ограничения max-width на мобильных */
		.join-game-content * {
			max-width: 100% !important;
		}
		
		.join-game-content .max-w-4xl,
		.join-game-content .max-w-6xl,
		.join-game-content .max-w-2xl,
		.join-game-content .max-w-md,
		.join-game-content .mobile-full-width {
			max-width: 100% !important;
			width: 100% !important;
			margin-left: 0 !important;
			margin-right: 0 !important;
		}
		
		/* Убираем mx-auto на мобильных */
		.join-game-content .mx-auto {
			margin-left: 0 !important;
			margin-right: 0 !important;
		}
		
		/* Убираем лишние отступы на мобильных */
		.join-game-content section {
			padding-left: 1rem !important;
			padding-right: 1rem !important;
			padding-top: 2rem !important;
			padding-bottom: 2rem !important;
			width: 100% !important;
			max-width: 100% !important;
			box-sizing: border-box;
		}
		
		/* Нормальный размер текста на мобильных */
		.join-game-content h1 {
			font-size: 2.5rem !important;
			line-height: 1.2 !important;
		}
		
		.join-game-content h2 {
			font-size: 1.5rem !important;
			line-height: 1.3 !important;
		}
		
		.join-game-content p {
			font-size: 1rem !important;
		}
		
		/* Кнопки нормального размера */
		.join-game-content .cyber-button {
			font-size: 1rem !important;
			padding: 0.75rem 1.5rem !important;
		}
	}
	
	/* На очень маленьких экранах */
	@media (max-width: 480px) {
		.hexagon-background-container canvas {
			opacity: 0.15;
		}
		
		.join-game-content {
			background-color: rgba(0, 18, 33, 0.9);
		}
		
		.join-game-content h1 {
			font-size: 2rem !important;
		}
		
		.join-game-content h2 {
			font-size: 1.25rem !important;
		}
	}
</style>
<div class="hexagon-background-container">
	<canvas id="hexCanvas"></canvas>
</div>
<div class="join-game-content min-h-screen relative" style="background-color: transparent;">
	<!-- Language Switcher -->
	<div class="fixed top-4 right-4 z-50 flex gap-2">
				<a href="?lang=uk" class="cyber-button px-4 py-2 text-sm <?php echo $currentLang === 'uk' ? 'active' : ''; ?>">
					🇺🇦 UK
				</a>
				<a href="?lang=en" class="cyber-button px-4 py-2 text-sm <?php echo $currentLang === 'en' ? 'active' : ''; ?>">
					🇬🇧 EN
				</a>
	</div>

	<!-- Hero Section -->
	<section class="min-h-screen flex items-center justify-center px-4 py-8 md:py-16">
		<div class="text-center max-w-4xl mx-auto w-full mobile-full-width">
			<!-- Game Title -->
			
			<div class="mb-8 mt-0 sm:mt-0 mt-[30px] sm:mt-0">
				<h1 class="text-6xl md:text-8xl font-bold mb-4 animate-cyber-glow">
					<span class="bg-gradient-to-r from-[#00f3ff] to-[#00ff41] bg-clip-text text-transparent">
						<?php echo $t('game_title'); ?>
					</span>
				</h1>
				<h2 class="text-2xl md:text-4xl font-semibold text-[#00f3ff] mb-2">
					<?php echo $t('game_subtitle'); ?>
				</h2>
				<p class="text-lg text-gray-400 max-w-2xl mx-auto">
					<?php echo $t('game_description'); ?>
				</p>
			</div>
            
			<!-- Action Buttons -->
			<div class="flex flex-col sm:flex-row gap-4 justify-center mb-16 relative z-10">
				<a href="/control-system" class="cyber-button text-lg px-8 py-4 h-auto inline-flex items-center justify-center gap-2 relative z-10">
					<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
					</svg>
					<?php echo $t('join_mission'); ?>
				</a>
				<a href="#" onclick="alert('<?php echo $t('buy_game'); ?>'); return false;" class="cyber-button text-lg px-8 py-4 h-auto inline-flex items-center justify-center gap-2 border-[#00f3ff]/50 relative z-10">
					<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
					</svg>
					<?php echo $t('buy_game'); ?>
				</a>
			</div>

			<!-- Game Description -->
			<div class="cyber-panel mb-12">
				<div class="p-8">
					<h3 class="text-2xl font-bold mb-6 text-[#00f3ff]"><?php echo $t('about_game'); ?></h3>
					<div class="grid md:grid-cols-2 gap-8 text-left">
						<div>
							<h4 class="text-lg font-semibold mb-3 text-[#00ff41]"><?php echo $t('features'); ?></h4>
							<ul class="space-y-2 text-gray-400">
								<li><?php echo $t('feature_1'); ?></li>
								<li><?php echo $t('feature_2'); ?></li>
								<li><?php echo $t('feature_3'); ?></li>
								<li><?php echo $t('feature_4'); ?></li>
								<li><?php echo $t('feature_5'); ?></li>
							</ul>
						</div>
						<div>
							<h4 class="text-lg font-semibold mb-3 text-[#00ff41]"><?php echo $t('gameplay'); ?></h4>
							<ul class="space-y-2 text-gray-400">
								<li><?php echo $t('gameplay_1'); ?></li>
								<li><?php echo $t('gameplay_2'); ?></li>
								<li><?php echo $t('gameplay_3'); ?></li>
								<li><?php echo $t('gameplay_4'); ?></li>
								<li><?php echo $t('gameplay_5'); ?></li>
							</ul>
						</div>
					</div>
				</div>
			</div>

			<!-- Game Video -->
			<div class="cyber-panel mb-16">
				<div class="p-8">
					<h3 class="text-2xl font-bold mb-6 text-[#00f3ff]"><?php echo $t('game_trailer'); ?></h3>
					<div class="aspect-video bg-[#1a1a1a]/20 rounded-lg flex items-center justify-center border-2 border-dashed border-[#333333]">
						<div class="text-center">
							<svg class="h-16 w-16 text-[#00f3ff] mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
							</svg>
							<p class="text-gray-400"><?php echo $t('video_placeholder'); ?></p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- Player Matching Section -->
	<section class="py-16 px-4">
		<div class="max-w-6xl mx-auto">
			<div class="text-center mb-12">
				<h3 class="text-3xl font-bold mb-4 text-[#00f3ff]"><?php echo $t('find_partners'); ?></h3>
				<p class="text-lg text-gray-400 max-w-2xl mx-auto mb-8">
					<?php echo $t('find_partners_desc'); ?>
				</p>
				
				<button onclick="document.getElementById('playerFormModal').classList.remove('hidden')" class="cyber-button">
					<svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
					</svg>
					<?php echo $t('add_contacts'); ?>
				</button>
			</div>

			<!-- Player Form Modal -->
			<div id="playerFormModal" class="hidden fixed inset-0 bg-black/80 z-50 flex items-center justify-center p-4">
				<div class="cyber-panel max-w-md w-full">
					<div class="p-6">
						<div class="flex justify-between items-center mb-4">
							<h4 class="text-xl font-bold text-[#00f3ff]"><?php echo $t('agent_registration'); ?></h4>
							<button onclick="document.getElementById('playerFormModal').classList.add('hidden')" class="text-gray-400 hover:text-[#00f3ff]">
								<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
								</svg>
							</button>
						</div>
						<p class="text-sm text-gray-400 mb-6"><?php echo $t('agent_registration_desc'); ?></p>
						
						<form id="playerForm" class="space-y-4">
							<div>
								<label class="block text-sm font-medium text-gray-300 mb-2"><?php echo $t('agent_name'); ?> *</label>
								<input type="text" id="agentName" required class="cyber-input w-full" placeholder="<?php echo $t('agent_name_placeholder'); ?>">
							</div>
							
							<div class="grid grid-cols-2 gap-4">
								<div>
									<label class="block text-sm font-medium text-gray-300 mb-2"><?php echo $t('telegram'); ?></label>
									<input type="text" id="telegram" class="cyber-input w-full" placeholder="<?php echo $t('telegram_placeholder'); ?>">
								</div>
								<div>
									<label class="block text-sm font-medium text-gray-300 mb-2"><?php echo $t('discord'); ?></label>
									<input type="text" id="discord" class="cyber-input w-full" placeholder="<?php echo $t('discord_placeholder'); ?>">
								</div>
							</div>
							
							<div class="grid grid-cols-2 gap-4">
								<div>
									<label class="block text-sm font-medium text-gray-300 mb-2"><?php echo $t('whatsapp'); ?></label>
									<input type="text" id="whatsapp" class="cyber-input w-full" placeholder="<?php echo $t('whatsapp_placeholder'); ?>">
								</div>
								<div>
									<label class="block text-sm font-medium text-gray-300 mb-2"><?php echo $t('facebook'); ?></label>
									<input type="text" id="facebook" class="cyber-input w-full" placeholder="<?php echo $t('facebook_placeholder'); ?>">
								</div>
							</div>
							
							<div>
								<label class="block text-sm font-medium text-gray-300 mb-2"><?php echo $t('preferred_time'); ?></label>
								<select id="preferredTime" class="cyber-input w-full">
									<option value=""><?php echo $t('select_time'); ?></option>
									<option value="morning"><?php echo $t('time_morning'); ?></option>
									<option value="afternoon"><?php echo $t('time_afternoon'); ?></option>
									<option value="evening"><?php echo $t('time_evening'); ?></option>
									<option value="night"><?php echo $t('time_night'); ?></option>
									<option value="weekends"><?php echo $t('time_weekends'); ?></option>
									<option value="anytime"><?php echo $t('time_anytime'); ?></option>
								</select>
							</div>
							
							<div class="flex gap-2 pt-4">
								<button type="button" onclick="document.getElementById('playerFormModal').classList.add('hidden')" class="flex-1 cyber-button border-[#333333] hover:bg-[#1a1a1a]">
									<?php echo $t('cancel'); ?>
								</button>
								<button type="submit" class="flex-1 cyber-button">
									<?php echo $t('add'); ?>
								</button>
							</div>
						</form>
					</div>
				</div>
			</div>

			<!-- Players Table -->
			<div class="cyber-panel">
				<div class="p-8">
					<div class="flex items-center gap-2 mb-6">
						<svg class="h-5 w-5 text-[#00f3ff]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
						</svg>
						<h4 class="text-xl font-semibold text-[#00f3ff]"><?php echo $t('active_agents'); ?></h4>
					</div>
					
					<div id="playersList" class="grid gap-4">
						<!-- Players will be loaded here via JavaScript -->
					</div>
					
					<div id="noPlayers" class="text-center py-8 hidden">
						<svg class="h-12 w-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
						</svg>
						<p class="text-gray-400"><?php echo $t('no_agents'); ?></p>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>

<script>
// Handle form submission
document.getElementById('playerForm')?.addEventListener('submit', function(e) {
	e.preventDefault();
	
	const name = document.getElementById('agentName').value;
	if (!name) {
		alert('<?php echo $t('error_name_required'); ?>');
		return;
	}
	
	// Here you would send data to server via AJAX
	// For now, just show success message
	alert('<?php echo $t('success_added'); ?>: <?php echo $t('success_added_desc'); ?>');
	
	// Reset form and close modal
	this.reset();
	document.getElementById('playerFormModal').classList.add('hidden');
	
	// In real implementation, reload players list
});

// Sample players data (in real app, this would come from server)
const samplePlayers = [
	{
		id: 1,
		name: "Agent Phoenix",
		contacts: [
			{ type: 'telegram', value: '@agent_phoenix', url: 'https://t.me/agent_phoenix' },
			{ type: 'discord', value: 'Phoenix#1234', url: 'https://discord.com/users/phoenix1234' }
		],
		joinedAt: '2024-01-15',
		preferredTime: '<?php echo $t('time_evening'); ?>'
	},
	{
		id: 2,
		name: "Ghost Recon",
		contacts: [
			{ type: 'whatsapp', value: '+7 900 123-45-67', url: 'https://wa.me/79001234567' },
			{ type: 'telegram', value: '@ghost_recon', url: 'https://t.me/ghost_recon' }
		],
		joinedAt: '2024-01-10',
		preferredTime: '<?php echo $t('time_weekends'); ?>'
	}
];

function getContactIcon(type) {
	const icons = {
		telegram: '📱',
		whatsapp: '💬',
		viber: '💜',
		discord: '🎮',
		facebook: '📘'
	};
	return icons[type] || '📞';
}

function renderPlayers() {
	const container = document.getElementById('playersList');
	const noPlayers = document.getElementById('noPlayers');
	
	if (!container) return;
	
	if (samplePlayers.length === 0) {
		container.innerHTML = '';
		if (noPlayers) noPlayers.classList.remove('hidden');
		return;
	}
	
	if (noPlayers) noPlayers.classList.add('hidden');
	
	container.innerHTML = samplePlayers.map(player => `
		<div class="bg-[#1a1a1a]/20 border border-[#333333]/50 rounded-lg p-4">
			<div class="flex items-center justify-between flex-wrap gap-4">
				<div class="flex items-center gap-4">
					<div class="p-3 rounded-lg bg-[#00f3ff]/20 border border-[#00f3ff]/30">
						<svg class="h-5 w-5 text-[#00f3ff]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
						</svg>
					</div>
					<div>
						<h5 class="font-semibold text-white">${player.name}</h5>
						<p class="text-sm text-gray-400">${player.preferredTime}</p>
						<p class="text-xs text-gray-500"><?php echo $t('joined_at'); ?>: ${new Date(player.joinedAt).toLocaleDateString()}</p>
					</div>
				</div>
				
				<div class="flex items-center gap-2 flex-wrap">
					${player.contacts.map(contact => `
						<a href="${contact.url}" target="_blank" class="cyber-button text-xs px-3 py-1.5 gap-2 border-[#00f3ff]/30 hover:bg-[#00f3ff]/20">
							<span>${getContactIcon(contact.type)}</span>
							<span>${contact.value}</span>
							<svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
							</svg>
						</a>
					`).join('')}
				</div>
			</div>
		</div>
	`).join('');
}

// Render players on page load
document.addEventListener('DOMContentLoaded', renderPlayers);
</script>

<script src="/view/js/hexagon-background.js?v=<?php echo isset($random) ? $random : time(); ?>"></script>

<?php
require_once(ROOT . '/view/blocks/footer.php');
?>
