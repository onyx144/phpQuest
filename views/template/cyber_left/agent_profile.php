<?php
    defined('GD_ACCESS') or die('You can not access the file directly!');
?>

<div class="cyber-panel text-center max-w-md mx-auto animate-fade-in-up">
      <div class="relative mb-6">
        <div class="relative w-48 h-60 mx-auto rounded-lg overflow-hidden border-2 border-cyber-neon-blue glow-effect">
          <img 
            src="/images/agent_profile.jpg" 
            alt="Agent Alison Floyd"
            class="w-full h-full object-cover"
          />
          <div class="absolute inset-0 bg-gradient-to-t from-cyber-neon-blue/20 to-transparent"></div>
        </div>
        <div class="absolute -top-2 -right-2 w-8 h-8 bg-cyber-neon-blue rounded-full flex items-center justify-center animate-cyber-pulse">
          <i class="fas fa-shield-alt h-4 w-4 text-cyber-dark-bg"></i>
        </div>
      </div>

      <!-- Agent Info -->
      <div class="space-y-4">
        <h2 class="text-3xl font-bold neon-text animate-cyber-glow">
          Agent Alison Floyd
        </h2>
        
        <div class="cyber-card">
          <div class="cyber-grid grid-cols-3 gap-4 text-center">
            <div>
              <i class="fas fa-bolt h-6 w-6 text-cyber-neon-green mx-auto mb-2"></i>
              <div class="text-sm text-gray-400">Status</div>
              <div class="font-bold text-cyber-neon-green">Active</div>
            </div>
            <div>
              <i class="fas fa-star h-6 w-6 text-cyber-neon-blue mx-auto mb-2"></i>
              <div class="text-sm text-gray-400">Rank</div>
              <div class="font-bold text-cyber-neon-blue">Elite</div>
            </div>
            <div>
              <i class="fas fa-shield-alt h-6 w-6 text-white mx-auto mb-2"></i>
              <div class="text-sm text-gray-400">Clearance</div>
              <div class="font-bold text-white">Level 9</div>
            </div>
          </div>
        </div>

        <div class="bg-gradient-to-r from-cyber-neon-blue/20 to-cyber-neon-green/20 rounded-lg p-4 border border-cyber-neon-blue/30">
          <h3 class="text-lg font-bold mb-2 text-cyber-neon-blue">Current Team</h3>
          <p class="text-xl font-mono neon-text"><?= isset($teamName) ? $teamName : 'Alpha Team' ?></p>
        </div>

        <div class="text-sm text-gray-400 bg-cyber-panel-bg/20 rounded p-3">
          <p class="italic">
            "Специализируется на киберразведке и инфильтрации. 
            Многолетний опыт работы в секретных операциях."
          </p>
        </div>
      </div>
    </div>