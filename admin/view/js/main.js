// Cookie
var WFCookie = {
    get: function(e) {
        var t = document.cookie.match(new RegExp("(?:^|; )" + e.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, "\\$1") + "=([^;]*)"));
        return t ? decodeURIComponent(t[1]) : null
    },
    set: function(e, t, n) {
        null == n && (n = {}),
        (n = Object.assign({}, {
            path: "/"
        }, n)).expires instanceof Date && (n.expires = n.expires.toUTCString());
        var i = encodeURIComponent(e) + "=" + encodeURIComponent(t);
        for (var r in n)
            if (!1 !== n.hasOwnProperty(r)) {
                i += "; " + r;
                var o = n[r];
                !0 !== o && (i += "=" + o)
            }
        document.cookie = i
    },
    remove: function(e) {
        this.set(e, "", {
            "max-age": -1
        })
    }
};
"undefined" != typeof module && void 0 !== module.exports && (module.exports = WFCookie);

$(function() {
	// отображаем содержимое
	$('body').css('display','block');

	// отобразить вводимый пароль
	$('.wf-loginform-toggle-pass').click(function(){
		if ($(this).hasClass('wf-loginform-view-pass')) {
			$(this).prev().attr('type','text');
			$(this).removeClass('wf-loginform-view-pass').addClass('wf-loginform-hidden-pass');
		} else {
			$(this).prev().attr('type','password');
			$(this).addClass('wf-loginform-view-pass').removeClass('wf-loginform-hidden-pass');
		}
	});

	// datepicker - генерация нового кода игры
	if ($(".wf-addsale-datepicker").length) {
	    // $(".wf-addsale-datepicker").datepicker({
	    $(".wf-addsale-datepicker").datetimepicker({
	        // dateFormat: "dd.mm.yy",
	        format:'d.m.Y H:i:s',
	        // formatDate:'d.m.Y',
	        // dayNamesShort: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
	        // dayNamesMin: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
	        // monthNames: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
	        // changeMonth: true,
	        // changeYear: true,
	        //showAnim: "clip",
	        // showAnim: "",
	        timepicker: true,
	        step: 1,
	        minDate: 0
	    });
	}

	// добавление продажи игры. Меняем цену во второй валюте при указании первой
	$('input[name="add-sale-price-usd"]').keyup(function(){
		var number = $.trim($(this).val());
		if (isNaN(number) || number == '') {
			number = 0;
		}
		number = parseFloat(number);

		var numberNorway = (number * $('.wf-save-gamecode').attr('data-norway-crone')).toFixed(2);

		$('input[name="add-sale-price-nok"]').val(numberNorway);
	});
	$('input[name="add-sale-price-nok"]').keyup(function(){
		var number = $.trim($(this).val());
		if (isNaN(number) || number == '') {
			number = 0;
		}
		number = parseFloat(number);

		var numberUsd = (number / $('.wf-save-gamecode').attr('data-norway-crone')).toFixed(2);

		$('input[name="add-sale-price-usd"]').val(numberUsd);
	});

	// запрещаем отправку форму добавления продажи игры
	$('.wf-form-add-sale').submit(function(){
		return false;
	});

	// Change number of games. Add sale page
	$('input[name="add-sale-number-games"]').keyup(function(){
		var numberGames = $.trim($(this).val());

		if (numberGames == '') {
			numberGames = 0;
		}

		numberGames = parseInt(numberGames, 10);

		if (numberGames > 10) {
			numberGames = 10;
		} else if (numberGames < 1) { 
			numberGames = 1;
		}

		for (let index = 1; index <= 10; index++) {
			$('[name="add-sale-code-' + index + '"]').css('display', (index	 <= numberGames ? 'block' : 'none'));
		}
	});
	$('input[name="add-sale-number-games"]').mouseup(function(){
		var numberGames = $.trim($(this).val());

		if (numberGames == '') {
			numberGames = 0;
		}

		numberGames = parseInt(numberGames, 10);

		if (numberGames > 10) {
			numberGames = 10;
		} else if (numberGames < 1) { 
			numberGames = 1;
		}

		for (let index = 1; index <= 10; index++) {
			$('[name="add-sale-code-' + index + '"]').css('display', (index	 <= numberGames ? 'block' : 'none'));
		}
	});

	// добавляем новый код игры
	$('.wf-save-gamecode').click(function(){
		var code = $.trim($('input[name="add-sale-code-1"]').val());

		if (code.length < 5 || code.length > 30) {
			printAlert('error', 'Error', 'Code length must be from 5 to 30 symbols');
		} else {
			viewPreloader();

			var date = $('input[name="add-sale-date"]').val();
			// var gamename = $.trim($('input[name="add-sale-game"]').val());
			var gamenameId = $('select[name="add-sale-gamename-id"]').val();
			var priceUsd = $.trim($('input[name="add-sale-price-usd"]').val());
			var priceNok = $.trim($('input[name="add-sale-price-nok"]').val());
			var sourceId = $('select[name="add-sale-source-id"]').val();

			var formData = new FormData();
			formData.append('op', 'saveGamecode');
			formData.append('date', date);
			// formData.append('gamename', gamename);
			formData.append('gamename_id', gamenameId);
			formData.append('price_usd', priceUsd);
			formData.append('price_nok', priceNok);
			formData.append('source_id', sourceId);
			formData.append('code', code);
			formData.append('code2', ($('input[name="add-sale-code-2"]').css('display') == 'block' ? $.trim($('input[name="add-sale-code-2"]').val()) : ''));
			formData.append('code3', ($('input[name="add-sale-code-3"]').css('display') == 'block' ? $.trim($('input[name="add-sale-code-3"]').val()) : ''));
			formData.append('code4', ($('input[name="add-sale-code-4"]').css('display') == 'block' ? $.trim($('input[name="add-sale-code-4"]').val()) : ''));
			formData.append('code5', ($('input[name="add-sale-code-5"]').css('display') == 'block' ? $.trim($('input[name="add-sale-code-5"]').val()) : ''));
			formData.append('code6', ($('input[name="add-sale-code-6"]').css('display') == 'block' ? $.trim($('input[name="add-sale-code-6"]').val()) : ''));
			formData.append('code7', ($('input[name="add-sale-code-7"]').css('display') == 'block' ? $.trim($('input[name="add-sale-code-7"]').val()) : ''));
			formData.append('code8', ($('input[name="add-sale-code-8"]').css('display') == 'block' ? $.trim($('input[name="add-sale-code-8"]').val()) : ''));
			formData.append('code9', ($('input[name="add-sale-code-9"]').css('display') == 'block' ? $.trim($('input[name="add-sale-code-9"]').val()) : ''));
			formData.append('code10', ($('input[name="add-sale-code-10"]').css('display') == 'block' ? $.trim($('input[name="add-sale-code-10"]').val()) : ''));
			formData.append('source_code', $.trim($('input[name="add-sale-source-code"]').val()));
			formData.append('client_email', $.trim($('input[name="add-sale-client-email"]').val()));

			$.ajax({
                url: "/admin/ajax/ajax.php",
                type: "POST",
                dataType: "json",
                cache: false,
                contentType: false,
                processData: false,
                data: formData,
                success: function (data) {
                    if (data.error) {
                        hidePreloader();
                        printAlert('error', 'Error', data.error);
                    } else if (data.success) {
                        Swal.fire({
			                icon: 'success',
			                title: 'Successfully added',
			                html: 'Response from API: ' + data.success,
			                onClose: () => {
			                    window.location.href = '/sales';
			                }
			            });
                    }
                },
                error: function (jqXHR) {
                    hidePreloader();
                    printAlert('error', 'Error', 'Unexpected error');
                }
            });
		}
	});

	// удалить продажу игры
	$('.wf-remove-sale').click(function(){
		var _this = $(this);

        Swal.fire({
            title: 'Are You Sure?',
            // text: '',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes',
            cancelButtonText: 'No',
        }).then((result) => {
            if (result.value) {
                var formData = new FormData();
                formData.append('op', 'removeSale');
                formData.append('code', _this.closest('tr').find('.wf-saletable-teamcode').html());

                $.ajax({
                    url: "/admin/ajax/ajax.php",
                    type: "POST",
                    dataType: "json",
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: formData,
                    success: function (data) {
                        if (data.error) {
                            printAlert('error', 'Error', data.error);
                        } else if (data.success) {
                            _this.closest('tr').remove();
                        }
                    },
                    error: function (jqXHR) {
                        printAlert('error', 'Error', 'Unexpected error');
                    }
                });
            }
        }); 
	});

	// продажи по к-ву. Изменение года
	$('select[name="wf-total-sales-change-year"]').change(function(){
		window.location.href = '/total-sales?year=' + $(this).val();
	});

	// продажи по стоимости. Изменение года
	$('select[name="wf-total-sales-sum-change-year"]').change(function(){
		window.location.href = '/totalsales-sum?year=' + $(this).val() + '&currency=' + $('select[name="wf-total-sales-sum-change-currency"]').val();
	});

	// продажи по стоимости. Изменение валюты
	$('select[name="wf-total-sales-sum-change-currency"]').change(function(){
		window.location.href = '/totalsales-sum?year=' + $('select[name="wf-total-sales-sum-change-year"]').val() + '&currency=' + $(this).val();
	});

	// добавление пользователя. Изменение роли
	$('select[name="add-user-role-id"]').change(function(){
		if ($(this).val() == 2) {
			$('select[name="add-user-source-id"]').val(0).prop('disabled', true);
		} else if ($(this).val() == 3) {
			$('select[name="add-user-source-id"]').prop('disabled', false);
		}
	});

	// запрещаем отправку форму добавления пользователя
	$('.wf-form-add-user').submit(function(){
		return false;
	});

	// добавить нового пользователя
	$('.wf-save-add-user').click(function(){
		viewPreloader();

		var login = $('input[name="add-user-login"]').val();
		var password = $('input[name="add-user-password"]').val();
		var roleId = $('select[name="add-user-role-id"]').val();
		var sourceId = 0;
		if (roleId == 3) {
			sourceId = $('select[name="add-user-source-id"]').val();
		}
		var status = $('#add-user-status').is(":checked") ? 1 : 0;

		var formData = new FormData();
		formData.append('op', 'addUser');
		formData.append('login', login);
		formData.append('password', password);
		formData.append('role_id', roleId);
		formData.append('source_id', sourceId);
		formData.append('status', status);

		$.ajax({
            url: "/admin/ajax/ajax.php",
            type: "POST",
            dataType: "json",
            cache: false,
            contentType: false,
            processData: false,
            data: formData,
            success: function (data) {
                if (data.error) {
                    hidePreloader();
                    printAlert('error', 'Error', data.error);
                } else if (data.success) {
                    Swal.fire({
		                icon: 'success',
		                title: 'Successfully added',
		                html: '',
		                onClose: () => {
		                    window.location.href = '/users';
		                }
		            });
                }
            },
            error: function (jqXHR) {
                hidePreloader();
                printAlert('error', 'Error', 'Unexpected error');
            }
        });
	});

	// редактирование пользователя. Изменение роли
	$('select[name="edit-user-role-id"]').change(function(){
		if ($(this).val() == 2) {
			$('select[name="edit-user-source-id"]').val(0).prop('disabled', true);
		} else if ($(this).val() == 3) {
			$('select[name="edit-user-source-id"]').prop('disabled', false);
		}
	});

	// запрещаем отправку форму редактировании пользователя
	$('.wf-form-edit-user').submit(function(){
		return false;
	});

	// редактирование пользователя
	$('.wf-save-edit-user').click(function(){
		viewPreloader();

		var login = $('input[name="edit-user-login"]').val();
		var password = $('input[name="edit-user-password"]').val();
		var roleId = $('select[name="edit-user-role-id"]').val();
		var sourceId = 0;
		if (roleId == 3) {
			sourceId = $('select[name="edit-user-source-id"]').val();
		}
		var status = $('#edit-user-status').is(":checked") ? 1 : 0;

		var formData = new FormData();
		formData.append('op', 'editUser');
		formData.append('login', login);
		formData.append('password', password);
		formData.append('role_id', roleId);
		formData.append('source_id', sourceId);
		formData.append('status', status);
		formData.append('userId', $('.wf-save-edit-user').attr('data-user-id'));

		$.ajax({
            url: "/admin/ajax/ajax.php",
            type: "POST",
            dataType: "json",
            cache: false,
            contentType: false,
            processData: false,
            data: formData,
            success: function (data) {
                if (data.error) {
                    hidePreloader();
                    printAlert('error', 'Error', data.error);
                } else if (data.success) {
                    Swal.fire({
		                icon: 'success',
		                title: 'Successfully edited',
		                html: '',
		                onClose: () => {
		                    window.location.href = '/users';
		                }
		            });
                }
            },
            error: function (jqXHR) {
                hidePreloader();
                printAlert('error', 'Error', 'Unexpected error');
            }
        });
	});

	// запрещаем отправку форму настройки
	$('.wf-form-settings').submit(function(){
		return false;
	});

	// сохранить настройки
	$('.wf-save-settings').click(function(){
		viewPreloader();

		var formData = new FormData();
		formData.append('op', 'editSettings');
		formData.append('norway_crone', $('.wf-form-settings .form-group[data-id="2"] input').val());

		$.ajax({
            url: "/admin/ajax/ajax.php",
            type: "POST",
            dataType: "json",
            cache: false,
            contentType: false,
            processData: false,
            data: formData,
            success: function (data) {
                if (data.error) {
                    hidePreloader();
                    printAlert('error', 'Error', data.error);
                } else if (data.success) {
                    Swal.fire({
		                icon: 'success',
		                title: 'Successfully edited',
		                html: '',
		                onClose: () => {
		                    location.reload();
		                }
		            });
                }
            },
            error: function (jqXHR) {
                hidePreloader();
                printAlert('error', 'Error', 'Unexpected error');
            }
        });
	});

	// запрещаем отправку формы добавления источника
	$('.wf-form-add-source').submit(function(){
		return false;
	});

	// добавить источник
	$('.wf-save-add-source').click(function(){
		viewPreloader();

		var formData = new FormData();
		formData.append('op', 'addSource');
		formData.append('source_name', $('input[name="add-source-name"]').val());

		$.ajax({
            url: "/admin/ajax/ajax.php",
            type: "POST",
            dataType: "json",
            cache: false,
            contentType: false,
            processData: false,
            data: formData,
            success: function (data) {
                if (data.error) {
                    hidePreloader();
                    printAlert('error', 'Error', data.error);
                } else if (data.success) {
                    Swal.fire({
		                icon: 'success',
		                title: 'Successfully added',
		                html: '',
		                onClose: () => {
		                    window.location.href = '/settings';
		                }
		            });
                }
            },
            error: function (jqXHR) {
                hidePreloader();
                printAlert('error', 'Error', 'Unexpected error');
            }
        });
	});

	// запрещаем отправку формы редактирования источника
	$('.wf-form-edit-source').submit(function(){
		return false;
	});

	// редактированить источник
	$('.wf-save-edit-source').click(function(){
		viewPreloader();

		var formData = new FormData();
		formData.append('op', 'editSource');
		formData.append('source_name', $('input[name="edit-source-name"]').val());
		formData.append('source_id', $('.wf-save-edit-source').attr('data-source-id'));

		$.ajax({
            url: "/admin/ajax/ajax.php",
            type: "POST",
            dataType: "json",
            cache: false,
            contentType: false,
            processData: false,
            data: formData,
            success: function (data) {
                if (data.error) {
                    hidePreloader();
                    printAlert('error', 'Error', data.error);
                } else if (data.success) {
                    Swal.fire({
		                icon: 'success',
		                title: 'Successfully edited',
		                html: '',
		                onClose: () => {
		                    window.location.href = '/settings';
		                }
		            });
                }
            },
            error: function (jqXHR) {
                hidePreloader();
                printAlert('error', 'Error', 'Unexpected error');
            }
        });
	});

	// удалить источник
	$('.wf-remove-source').click(function(){
		var _this = $(this);

        Swal.fire({
            title: 'Are You Sure?',
            text: 'All associated codes, games and sales will also be removed',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes',
            cancelButtonText: 'No',
        }).then((result) => {
            if (result.value) {
                var formData = new FormData();
                formData.append('op', 'removeSource');
                formData.append('source_id', _this.closest('tr').attr('data-id'));

                $.ajax({
                    url: "/admin/ajax/ajax.php",
                    type: "POST",
                    dataType: "json",
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: formData,
                    success: function (data) {
                        if (data.error) {
                            printAlert('error', 'Error', data.error);
                        } else if (data.success) {
                            _this.closest('tr').remove();
                        }
                    },
                    error: function (jqXHR) {
                        printAlert('error', 'Error', 'Unexpected error');
                    }
                });
            }
        }); 
	});

	// запрещаем отправку формы добавления названия игры
	$('.wf-form-add-gamename').submit(function(){
		return false;
	});

	// добавить название игры
	$('.wf-save-add-gamename').click(function(){
		viewPreloader();

		var formData = new FormData();
		formData.append('op', 'addGamename');
		formData.append('game_name', $('input[name="add-game-name"]').val());

		$.ajax({
            url: "/admin/ajax/ajax.php",
            type: "POST",
            dataType: "json",
            cache: false,
            contentType: false,
            processData: false,
            data: formData,
            success: function (data) {
                if (data.error) {
                    hidePreloader();
                    printAlert('error', 'Error', data.error);
                } else if (data.success) {
                    Swal.fire({
		                icon: 'success',
		                title: 'Successfully added',
		                html: '',
		                onClose: () => {
		                    window.location.href = '/settings';
		                }
		            });
                }
            },
            error: function (jqXHR) {
                hidePreloader();
                printAlert('error', 'Error', 'Unexpected error');
            }
        });
	});

	// запрещаем отправку формы редактирования названия игры
	$('.wf-form-edit-gamename').submit(function(){
		return false;
	});

	// редактированить название игры
	$('.wf-save-edit-gamename').click(function(){
		viewPreloader();

		var formData = new FormData();
		formData.append('op', 'editGamename');
		formData.append('game_name', $('input[name="edit-game-name"]').val());
		formData.append('gamename_id', $('.wf-save-edit-gamename').attr('data-gamename-id'));

		$.ajax({
            url: "/admin/ajax/ajax.php",
            type: "POST",
            dataType: "json",
            cache: false,
            contentType: false,
            processData: false,
            data: formData,
            success: function (data) {
                if (data.error) {
                    hidePreloader();
                    printAlert('error', 'Error', data.error);
                } else if (data.success) {
                    Swal.fire({
		                icon: 'success',
		                title: 'Successfully edited',
		                html: '',
		                onClose: () => {
		                    window.location.href = '/settings';
		                }
		            });
                }
            },
            error: function (jqXHR) {
                hidePreloader();
                printAlert('error', 'Error', 'Unexpected error');
            }
        });
	});

	// удалить название игры
	$('.wf-remove-gamename').click(function(){
		var _this = $(this);

        Swal.fire({
            title: 'Are You Sure?',
            text: 'All associated codes, games and sales will also be removed',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes',
            cancelButtonText: 'No',
        }).then((result) => {
            if (result.value) {
                var formData = new FormData();
                formData.append('op', 'removeGamename');
                formData.append('gamename_id', _this.closest('tr').attr('data-id'));

                $.ajax({
                    url: "/admin/ajax/ajax.php",
                    type: "POST",
                    dataType: "json",
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: formData,
                    success: function (data) {
                        if (data.error) {
                            printAlert('error', 'Error', data.error);
                        } else if (data.success) {
                            _this.closest('tr').remove();
                        }
                    },
                    error: function (jqXHR) {
                        printAlert('error', 'Error', 'Unexpected error');
                    }
                });
            }
        }); 
	});

	// удалить пользователя
	$('.wf-remove-user').click(function(){
		var _this = $(this);

        Swal.fire({
            title: 'Are You Sure?',
            // text: 'All associated codes, games and sales will also be removed',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes',
            cancelButtonText: 'No',
        }).then((result) => {
            if (result.value) {
                var formData = new FormData();
                formData.append('op', 'removeUser');
                formData.append('remove_user_id', _this.closest('tr').attr('data-id'));

                $.ajax({
                    url: "/admin/ajax/ajax.php",
                    type: "POST",
                    dataType: "json",
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: formData,
                    success: function (data) {
                        if (data.error) {
                            printAlert('error', 'Error', data.error);
                        } else if (data.success) {
                            location.reload();
                        }
                    },
                    error: function (jqXHR) {
                        printAlert('error', 'Error', 'Unexpected error');
                    }
                });
            }
        }); 
	});

	// View history sales
	$('.sales_view_history').click(function(){
		var currentTab = WFCookie.get('admin_sales_tab');

		if (currentTab === null) {
			var newTab = 'history';
		} else {
			if (currentTab == 'actual') {
				var newTab = 'history';
			} else {
				var newTab = 'actual';
			}
		}

		var date = new Date(Date.now() + 24 * 60 * 60 * 1000); // 24 hours
        var options = { expires: date };
        WFCookie.set('admin_sales_tab', newTab, options);

        // window.location.href = '/sales';
        location.reload();
	});

	// Sales table. Send cliem email
	$('.wf-saletable-send-email').click(function(){
		var _this = $(this);

		Swal.fire({
			title: 'Send to client email',
	        html:
	            '<div class="row">' +
	            '<div class="col-12">' +
	            '<div class="form-group">' +
	            '<label class="form-label">Client email (dont required)</label>' +
	            '<input type="text" class="form-control wf_popup_client_email" value="' + _this.closest('tr').find('.wf-saletable-client-email').val() + '" autocomplete="off">' +
	            '<input type="hidden" class="form-control wf_popup_game_code" value="' + _this.closest('tr').find('.wf-saletable-teamcode').text() + '" autocomplete="off">' +
	            '</div>' +
	            '</div>' +
	            '</div>',
	        focusConfirm: false,
	        showConfirmButton: true,
	        showCancelButton: true,
	        confirmButtonText: 'Send',
	        cancelButtonText: 'Cancel',
	        showDenyButton: false
		}).then((result) => {
			if (result.isConfirmed) {
				viewPreloader();

				var formData = new FormData();
				formData.append('op', 'sendClientEmail');
				formData.append('code', $('.wf_popup_game_code').val());
				formData.append('client_email', $('.wf_popup_client_email').val());

				$.ajax({
	                url: "/admin/ajax/ajax.php",
	                type: "POST",
	                dataType: "json",
	                cache: false,
	                contentType: false,
	                processData: false,
	                data: formData,
	                success: function (data) {
                        hidePreloader();

	                    if (data.error) {
	                        printAlert('error', 'Error', data.error);
	                    } else if (data.success) {
	                        printAlert('success', 'Success', data.success);
	                    }
	                },
	                error: function (jqXHR) {
	                    hidePreloader();

	                    printAlert('error', 'Error', 'Unexpected error');
	                }
	            });
			}
		});
	});

	// Games. Change order or sort
	$('#games_order_field, #games_sort_field').change(function(){
		var order = $('#games_order_field').val();
		var sort = $('#games_sort_field').val();

		window.location.href = '/games?order=' + order + '&sort=' + sort;
	});
});

// вызов предупреждения. Эмуляция алерта
function printAlert(typeAlert, titleAlert, textAlert) {
    Swal.fire({
        icon: typeAlert,
        title: titleAlert,
        text: textAlert
    });
}
function printAlertHtml(typeAlert, titleAlert, textAlert) {
    Swal.fire({
        icon: typeAlert,
        title: titleAlert,
        html: textAlert
    });
}

function viewPreloader() {
    $('#preloader').css('display', 'block');
}
function viewPreloaderWithText(text) {
    $('#preloader span').html(text);
    $('#preloader').css('display', 'block');
}

function hidePreloader() {
    $('#preloader span').html('');
    $('#preloader').css('display', 'none');
}