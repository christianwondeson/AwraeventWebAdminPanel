/**
 * Admin forms → include/event.php (JSON). Replaces legacy tbl_validate.data handlers.
 * Uses Bootstrap 5 toasts + status toggles (.drop).
 */
(function ($) {
	'use strict';

	function escapeHtml(s) {
		if (!s) return '';
		var d = document.createElement('div');
		d.textContent = s;
		return d.innerHTML;
	}

	function toastContainer() {
		var c = document.getElementById('awra-toast-container');
		if (!c) {
			c = document.createElement('div');
			c.id = 'awra-toast-container';
			c.className = 'toast-container position-fixed top-0 end-0 p-3';
			c.style.zIndex = '10850';
			document.body.appendChild(c);
		}
		return c;
	}

	function showToast(title, body, success) {
		if (typeof bootstrap === 'undefined' || !bootstrap.Toast) {
			alert((title || '') + (body ? '\n' + body : ''));
			return;
		}
		var c = toastContainer();
		var el = document.createElement('div');
		el.className = 'toast shadow';
		el.setAttribute('role', 'alert');
		var headClass = success ? 'success' : 'danger';
		el.innerHTML =
			'<div class="toast-header bg-' +
			headClass +
			' text-white">' +
			'<i class="fa fa-' +
			(success ? 'check-circle' : 'exclamation-circle') +
			' me-2"></i>' +
			'<strong class="me-auto">' +
			escapeHtml(title || (success ? 'Success' : 'Something went wrong')) +
			'</strong>' +
			'<button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button></div>' +
			'<div class="toast-body">' +
			escapeHtml(body || '') +
			'</div>';
		c.appendChild(el);
		var t = bootstrap.Toast.getOrCreateInstance(el, { autohide: true, delay: success ? 3800 : 7000 });
		t.show();
		el.addEventListener('hidden.bs.toast', function () {
			el.remove();
		});
	}

	function postAdmin(data, isFormData) {
		var url = window.AWRAEVENT_ADMIN_POST_URL || '/include/event.php';
		var opts = {
			url: url,
			type: 'POST',
			dataType: 'json',
		};
		if (isFormData) {
			opts.data = data;
			opts.processData = false;
			opts.contentType = false;
		} else {
			opts.data = data;
		}
		return $.ajax(opts);
	}

	function handleJsonResponse(res, redirectDelayMs) {
		if (!res || typeof res !== 'object') {
			showToast('Server response', 'Unexpected empty or invalid JSON.', false);
			return;
		}
		var ok = res.Result === 'true';
		var title = res.title || (ok ? 'Saved' : 'Could not save');
		var msg = res.message || '';
		if (ok && !msg) {
			msg = 'Your update was applied.';
		}
		showToast(title, msg, ok);
		var go = res.action || null;
		if (ok) {
			setTimeout(function () {
				if (go) {
					window.location.href = go;
				} else {
					window.location.reload();
				}
			}, redirectDelayMs == null ? 1100 : redirectDelayMs);
		} else if (go) {
			setTimeout(function () {
				window.location.href = go;
			}, 2200);
		}
	}

	$(document).on('submit', '.content-body form', function (e) {
		var form = e.target;
		var method = (form.getAttribute('method') || 'get').toLowerCase();
		if (method !== 'post') {
			return;
		}
		if (form.getAttribute('data-admin-ajax') === '0') {
			return;
		}
		if (!form.querySelector('input[name="type"],select[name="type"]')) {
			return;
		}

		e.preventDefault();

		var fd = new FormData(form);
		postAdmin(fd, true)
			.done(function (res) {
				handleJsonResponse(res, 1100);
			})
			.fail(function (xhr) {
				var msg = 'HTTP ' + xhr.status;
				var raw = xhr.responseText || '';
				try {
					var j = JSON.parse(raw);
					if (j && j.title) {
						showToast(j.title, j.message || '', false);
						return;
					}
				} catch (ignore) {
					if (raw.indexOf('<') === 0) {
						msg = 'Server returned HTML instead of JSON. Check PHP errors or include/event.php URL.';
					} else if (raw.length) {
						msg = raw.substring(0, 280);
					}
				}
				showToast('Request failed', msg, false);
			});

		return false;
	});

	$(document).on('click', 'a.drop', function (e) {
		e.preventDefault();
		var $a = $(this);
		var type = $a.data('type');
		if (!type) {
			return;
		}
		postAdmin({
			type: type,
			id: $a.data('id'),
			status: $a.data('status'),
			coll_type: $a.data('collType') || $a.attr('data-coll-type') || 'user',
		}, false)
			.done(function (res) {
				handleJsonResponse(res, 800);
			})
			.fail(function (xhr) {
				showToast('Request failed', 'HTTP ' + xhr.status, false);
			});
	});
})(jQuery);
