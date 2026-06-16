/* === AUTOCOMPLETE SELECT COMPONENT === */
(function($) {
	if (window.initAutocompleteSelectComponent) {
		return;
	}

	window.initAutocompleteSelectComponent = function(rootNode, config) {
		var $root = $(rootNode);
		if (!$root.length || $root.data('autocomplete-initialized')) {
			return;
		}
		$root.data('autocomplete-initialized', true);

		var $input = $root.find('.autocomplete_select_input');
		var $dropdown = $root.find('.autocomplete_select_dropdown');
		var $options = $root.find('.autocomplete_select_option');
		var $hidden = $root.find('select');
		var onSelect = config && typeof config.onSelect === 'function' ? config.onSelect : null;

		function syncInputWithValue() {
			var currentValue = String($hidden.val() || '');
			var $activeOption = $options.filter(function() {
				return String($(this).attr('data-value')) === currentValue;
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
				var match = query === '' || text.indexOf(query) !== -1;
				$item.toggle(match);
				if (match) {
					visibleCount++;
				}
			});

			$dropdown.toggle(visibleCount > 0);
		}

		$input.on('focus click', function() {
			$options.show();
			$dropdown.show();
			applyFilter();
		});

		$input.on('input', applyFilter);

		$options.on('click', function() {
			var $item = $(this);
			var value = String($item.attr('data-value') || '');
			var text = $.trim($item.text());

			$input.val(text);
			$hidden.val(value).trigger('change');
			$dropdown.hide();

			if (onSelect) {
				onSelect(value, text, $root);
			}
		});

		$hidden.on('change', function() {
			syncInputWithValue();
			$dropdown.hide();
		});

		$(document).on('click.autocompleteSelect' + $root.attr('id'), function(e) {
			if (!$root.is(e.target) && $root.has(e.target).length === 0) {
				$dropdown.hide();
			}
		});

		syncInputWithValue();
	};
})(jQuery);
