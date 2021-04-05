
(function(){
	var toggles = document.querySelectorAll('[data-jcifi-event-toggle]');
	if ( toggles.length > 0 ) {
		for (var i = 0; i < toggles.length; i++) {
			jcifiToggle(toggles[i]);
		}
	}
})();

function jcifiToggle(element) {
	var toggle = element,
		toggleElement = document.getElementById(
			toggle.getAttribute('aria-controls')
		),
		toggleClose = toggleElement.querySelector('[data-jcifi-event-toggle-close]');

	var _open = function(event) {
		toggle.setAttribute('aria-expanded', 'true');
		toggle.innerText = toggle.getAttribute('data-text-close');
		toggleElement.hidden = false;
		toggleElement.classList.add('open');
	}

	var _close = function(event) {
		toggle.setAttribute('aria-expanded', 'false');
		toggle.innerText = toggle.getAttribute('data-text-open');
		toggleElement.hidden = true;
		toggleElement.classList.remove('open');
		toggle.focus();
	}

	var _click = function(event) {
		if ( 'true' === toggle.getAttribute('aria-expanded') ) {
			_close(event);
		} else {
			_open(event);
		}
	}

	toggle.addEventListener('click', _click);

	if ( toggleClose ) {
		toggleClose.addEventListener('click', _close);
	}

	return {
		click: _click,
		open: _open,
		close: _close,
	};
}
