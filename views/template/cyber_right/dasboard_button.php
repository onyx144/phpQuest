<?php
$sections = [
    ['id' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'dashboard_dashboard'],
    ['id' => 'calls', 'label' => 'Calls', 'icon' => 'dashboard_calls'],
    ['id' => 'files', 'label' => 'Files', 'icon' => 'dashboard_files'],
    ['id' => 'databases', 'label' => 'Databases', 'icon' => 'dashboard_databases'],
    ['id' => 'tools', 'label' => 'Tools', 'icon' => 'dashboard_tools'],
];

$activeSection = $_GET['section'] ?? 'dashboard'; // активный таб
?>

<div class="dashboard_list flex gap-2 flex-wrap">
  <?php foreach ($sections as $section): ?>
    <?php
      $isActive = $activeSection === $section['id'];
      
      $iconClass = "h-4 w-4"; // Tailwind utility
    ?>
<div class="dashboard_item <?= $isActive ? 'dashboard_item_active' : '' ?>" data-dashboard="<?= $section['id'] ?>">
<button class="gap-2 transition-all duration-300 inline-flex items-center px-4 py-2 rounded-md font-medium border"
    >      
    <?php echo $this->svg[$section['icon']]; ?>

      <span class="font-medium"><?= htmlspecialchars($section['label']) ?></span>
       
    </button>
    </div>
  <?php endforeach; ?>
</div>
