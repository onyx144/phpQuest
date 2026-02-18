<?php
function renderCyberBreadcrumbs(array $items): string
{
    if (empty($items)) return '';

    ob_start();
    ?>

    <nav class="cyber-breadcrumbs">

        <?php 
        $total = count($items);
        $i = 0;

        foreach ($items as $item):

            $isFirst = $i === 0;
            $isLast  = $i === ($total - 1);

            $text   = $item['text'] ?? '';
            $data   = $item['data'] ?? [];
            $url    = $item['url'] ?? '#';

            $dataAttrs = '';
            foreach ($data as $key => $value) {
                $dataAttrs .= ' data-' . htmlspecialchars($key) . '="' . htmlspecialchars($value) . '"';
            }
        ?>

            <div class="breadcrumb-node <?= $isLast ? 'active' : '' ?>" <?= $dataAttrs ?>>

                <?php if (!$isLast): ?>
                    <a href="<?= htmlspecialchars($url) ?>" class="breadcrumb-link">
                        <span class="breadcrumb-link-inner">
                            <?php if ($isFirst): ?>
                                <span class="status-dot"></span>
                            <?php endif; ?>
                            <span class="breadcrumb-text"><?= htmlspecialchars($text) ?></span>
                        </span>
                        <svg 
    class="breadcrumb-bg <?= !$isFirst ? 'breadcrumb-bg-middle' : 'breadcrumb-bg-start' ?>" 
    viewBox="0 0 140 40" 
    preserveAspectRatio="none" 
    aria-hidden="true" 
    width="100%" 
    height="100%"
>
                            <?php if ($isFirst): ?>
                                <path d="M0 10 L10 0 H110 L120 10 V30 L110 40 H10 L0 30 Z"
                                      fill="none" stroke="#00f0ff" stroke-width="1"/>
                            <?php else: ?>
                                <path  d="M0 0 H90 L100 10 V40 H10 L0 30 Z"
                                      fill="none" stroke="#00f0ff" stroke-width="1"
                                      stroke-dasharray="5 3"/>
                            <?php endif; ?>
                        </svg>
                    </a>
                <?php else: ?>
                    <div class="breadcrumb-link">
                        <span class="breadcrumb-link-inner">
                            <span class="breadcrumb-text"><?= htmlspecialchars($text) ?></span>
                        </span>
                        <svg class="breadcrumb-bg" viewBox="0 0 140 40" preserveAspectRatio="none" aria-hidden="true" width="100%" height="100%">
                            <path d="M0 0 H140 V30 L130 40 H0 Z"
                                  fill="none" stroke="#00f0ff" stroke-width="1.5"/>
                            <line x1="10" y1="36" x2="40" y2="36"
                                  stroke="#00f0ff" stroke-width="3"/>
                        </svg>
                    </div>
                <?php endif; ?>

            </div>

            <?php if (!$isLast): ?>
                <div class="breadcrumb-separator">
                    <svg width="30" height="40" viewBox="0 0 30 40" fill="none">
                        <path d="M10 10L20 20L10 30" stroke="#00f0ff" stroke-width="2"/>
                        <path d="M18 10L28 20L18 30" stroke="#00f0ff" stroke-width="1" opacity="0.4"/>
                    </svg>
                </div>
            <?php endif; ?>

        <?php $i++; endforeach; ?>

    </nav>

    <?php
    return ob_get_clean();
}


