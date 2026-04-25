<?php

if (!function_exists('renderAutocompleteSelectComponent')) {
    /**
     * Универсальный autocomplete-select компонент.
     *
     * $config = [
     *   'id' => 'unique-id',
     *   'hidden_class' => 'hidden-select-class',
     *   'wrapper_class' => 'custom-wrapper-class',
     *   'placeholder' => 'Search...',
     *   'selected_value' => '1',
     *   'options' => [
     *      ['value' => '1', 'label' => '+1 USA']
     *   ]
     * ]
     */
    function renderAutocompleteSelectComponent($config = [])
    {
        static $componentScriptPrinted = false;

        $id = !empty($config['id']) ? $config['id'] : 'autocomplete-select-' . uniqid();
        $hiddenClass = !empty($config['hidden_class']) ? $config['hidden_class'] : 'autocomplete-select-hidden';
        $wrapperClass = !empty($config['wrapper_class']) ? $config['wrapper_class'] : '';
        $placeholder = isset($config['placeholder']) ? $config['placeholder'] : '';
        $selectedValue = isset($config['selected_value']) ? (string) $config['selected_value'] : '';
        $options = !empty($config['options']) && is_array($config['options']) ? $config['options'] : [];

        $selectedLabel = '';
        $optionsHtml = '';
        $hiddenOptionsHtml = '<option value=""></option>';

        foreach ($options as $item) {
            $value = isset($item['value']) ? (string) $item['value'] : '';
            $label = isset($item['label']) ? (string) $item['label'] : '';
            $safeValue = htmlspecialchars($value, ENT_QUOTES);
            $safeLabel = htmlspecialchars($label, ENT_QUOTES);
            $isSelected = ($selectedValue !== '' && $selectedValue === $value);

            if ($isSelected) {
                $selectedLabel = $label;
            }

            $optionsHtml .= '<li class="autocomplete_select_option" data-value="' . $safeValue . '">' . $safeLabel . '</li>';
            $hiddenOptionsHtml .= '<option value="' . $safeValue . '"' . ($isSelected ? ' selected="selected"' : '') . '>' . $safeLabel . '</option>';
        }

        $safePlaceholder = htmlspecialchars($placeholder, ENT_QUOTES);
        $safeSelectedLabel = htmlspecialchars($selectedLabel, ENT_QUOTES);
        $safeHiddenClass = htmlspecialchars($hiddenClass, ENT_QUOTES);
        $safeWrapperClass = htmlspecialchars($wrapperClass, ENT_QUOTES);
        $safeId = htmlspecialchars($id, ENT_QUOTES);

        $html = '<div id="' . $safeId . '" class="autocomplete_select_component ' . $safeWrapperClass . '">
                    <input type="text" autocomplete="off" class="autocomplete_select_input" placeholder="' . $safePlaceholder . '" value="' . $safeSelectedLabel . '">
                    <div class="autocomplete_select_dropdown">
                        <ul class="autocomplete_select_list">' . $optionsHtml . '</ul>
                    </div>
                    <select class="' . $safeHiddenClass . '" style="display:none;">' . $hiddenOptionsHtml . '</select>
                </div>';

        if (!$componentScriptPrinted) {
            $html .= '<script>
                (function($) {
                    if (window.initAutocompleteSelectComponent) {
                        return;
                    }

                    window.initAutocompleteSelectComponent = function(rootNode, config) {
                        var $root = $(rootNode);
                        if (!$root.length || $root.data("autocomplete-initialized")) {
                            return;
                        }
                        $root.data("autocomplete-initialized", true);

                        var $input = $root.find(".autocomplete_select_input");
                        var $dropdown = $root.find(".autocomplete_select_dropdown");
                        var $options = $root.find(".autocomplete_select_option");
                        var $hidden = $root.find("select");
                        var onSelect = config && typeof config.onSelect === "function" ? config.onSelect : null;

                        function syncInputWithValue() {
                            var currentValue = String($hidden.val() || "");
                            var $activeOption = $options.filter(function() {
                                return String($(this).attr("data-value")) === currentValue;
                            }).first();

                            if ($activeOption.length) {
                                $input.val($.trim($activeOption.text()));
                            }
                        }

                        function applyFilter() {
                            var query = $.trim($input.val()).toLowerCase();
                            var visibleCount = 0;

                            $options.each(function() {
                                var $item = $(this);
                                var text = $.trim($item.text()).toLowerCase();
                                var match = text.indexOf(query) !== -1;
                                $item.toggle(match);
                                if (match) {
                                    visibleCount++;
                                }
                            });

                            $dropdown.toggle(visibleCount > 0);
                        }

                        $input.on("focus click", function() {
                            $options.show();
                            $dropdown.show();
                            applyFilter();
                        });

                        $input.on("input", applyFilter);

                        $options.on("click", function() {
                            var $item = $(this);
                            var value = String($item.attr("data-value") || "");
                            var text = $.trim($item.text());

                            $input.val(text);
                            $hidden.val(value).trigger("change");
                            $dropdown.hide();

                            if (onSelect) {
                                onSelect(value, text, $root);
                            }
                        });

                        $hidden.on("change", function() {
                            syncInputWithValue();
                            $dropdown.hide();
                        });

                        $(document).on("click", function(e) {
                            if (!$root.is(e.target) && $root.has(e.target).length === 0) {
                                $dropdown.hide();
                            }
                        });

                        syncInputWithValue();
                    };
                })(jQuery);
            </script>';
            $componentScriptPrinted = true;
        }

        return $html;
    }
}
