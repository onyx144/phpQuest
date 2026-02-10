<?php


trait Tabs
{
    public function renderTabTitles(array $steps)
    {
        if (empty($steps)) {
            return '';
        }

        $lastIndex = count($steps) - 1;
        $html = '<nav class="cyber-breadcrumbs dashboard_tab_titles">';

        foreach ($steps as $index => $item) {
            $text = isset($item['text']) ? htmlspecialchars((string) $item['text']) : '';
            $step = isset($item['step']) ? $item['step'] : ('tab' . ($index + 1));
            $url = isset($item['url']) ? $item['url'] : '#';
            $active = array_key_exists('active', $item) ? (bool) $item['active'] : ($index === $lastIndex);
            $showDot = !empty($item['show_dot']) || $index === 0;

            if ($index > 0) {
                $html .= $this->renderTabTitleSeparator();
            }

            $html .= '<div class="breadcrumb-node' . ($active ? ' active' : '') . '" data-tab="' . htmlspecialchars($step) . '">';

            $ajaxStep = isset($item['ajax_step']) ? $item['ajax_step'] : '';
            $database = isset($item['database']) ? $item['database'] : '';
            $isClickable = !$active && $ajaxStep !== '' && $database !== '';
            $linkClass = 'breadcrumb-link' . ($isClickable ? ' dashboard_tab_title_can_click' : '');
            $linkAttrs = $isClickable ? ' data-step="' . htmlspecialchars($ajaxStep) . '" data-database="' . htmlspecialchars($database) . '" href="javascript:void(0)"' : ' href="' . htmlspecialchars($url) . '"';

            if ($active) {
                $html .= '<div class="breadcrumb-link">';
                if ($showDot) {
                    $html .= '<div class="status-dot"></div>';
                }
                $html .= $text;
                $html .= $this->renderTabTitleSvgBg('active');
                $html .= '</div>';
            } else {
                $html .= '<a class="' . $linkClass . '"' . $linkAttrs . '>';
                if ($showDot) {
                    $html .= '<div class="status-dot"></div>';
                }
                $html .= $text;
                $html .= $this->renderTabTitleSvgBg($index === 0 ? 'first' : 'middle');
                $html .= '</a>';
            }

            $html .= '</div>';
        }

        $html .= '</nav>';
        return $html;
    }

    protected function renderTabTitleSeparator()
    {
        return '<div class="breadcrumb-separator">'
            . '<svg width="30" height="40" viewBox="0 0 30 40" fill="none">'
            . '<path d="M10 10L20 20L10 30" stroke="var(--cyber-blue, #00f0ff)" stroke-width="2" stroke-linecap="square"/>'
            . '<path d="M18 10L28 20L18 30" stroke="var(--cyber-blue, #00f0ff)" stroke-width="1" opacity="0.4"/>'
            . '</svg>'
            . '</div>';
    }

    protected function renderTabTitleSvgBg($variant = 'middle')
    {
        $stroke = 'var(--cyber-blue, #00f0ff)';
        switch ($variant) {
            case 'first':
                return '<svg class="breadcrumb-bg" viewBox="0 0 120 40" preserveAspectRatio="none">'
                    . '<path d="M0 10 L10 0 H110 L120 10 V30 L110 40 H10 L0 30 Z" fill="none" stroke="' . $stroke . '" stroke-width="1"/>'
                    . '</svg>';
            case 'active':
                return '<svg class="breadcrumb-bg" viewBox="0 0 140 40" preserveAspectRatio="none">'
                    . '<path d="M0 0 H140 V30 L130 40 H0 Z" fill="none" stroke="' . $stroke . '" stroke-width="1.5"/>'
                    . '<line x1="10" y1="36" x2="40" y2="36" stroke="' . $stroke . '" stroke-width="3" />'
                    . '</svg>';
            case 'middle':
            default:
                return '<svg class="breadcrumb-bg" viewBox="0 0 100 40" preserveAspectRatio="none">'
                    . '<path d="M0 0 H90 L100 10 V40 H10 L0 30 Z" fill="none" stroke="' . $stroke . '" stroke-width="1" stroke-dasharray="5 3"/>'
                    . '</svg>';
        }
    }
}
