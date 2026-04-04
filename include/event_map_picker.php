<?php
/**
 * Leaflet map: Ethiopia-wide view, place search (Nominatim), quick cities, pin → lat/long.
 * Field names: latitude, longtitude (DB spelling).
 */
if (!defined('AWRAEVENT_MAP_PICKER_LOADED')) {
  define('AWRAEVENT_MAP_PICKER_LOADED', true);
?>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<?php } ?>
<div class="form-group col-12 mb-3">
	<label class="form-label">Map — venue in Ethiopia</label>
	<p class="text-muted small mb-2">Centered on <strong>Ethiopia</strong>. Search for a place, pick a quick city, or click / drag the pin. Coordinates update the fields above.</p>
	<div class="small text-danger mb-1" id="event-map-search-msg" style="display:none;"></div>
	<div class="row g-2 mb-2">
		<div class="col-md-8">
			<div class="input-group input-group-sm">
				<input type="text" class="form-control" id="event-map-search-q" placeholder="Search in Ethiopia (venue, street, city…)" autocomplete="off">
				<button class="btn btn-primary" type="button" id="event-map-search-btn">Search</button>
			</div>
		</div>
		<div class="col-md-4">
			<select class="form-select form-select-sm" id="event-map-search-results" title="Pick a search result" style="display:none;">
				<option value="">— Results —</option>
			</select>
		</div>
	</div>
	<div class="mb-2 d-flex flex-wrap gap-1">
		<span class="small text-muted align-self-center me-1">Quick:</span>
		<button type="button" class="btn btn-sm btn-outline-secondary py-0 px-2 event-map-quick" data-lat="9.0320" data-lng="38.7469" data-z="13">Addis Ababa</button>
		<button type="button" class="btn btn-sm btn-outline-secondary py-0 px-2 event-map-quick" data-lat="9.6009" data-lng="41.8501" data-z="12">Dire Dawa</button>
		<button type="button" class="btn btn-sm btn-outline-secondary py-0 px-2 event-map-quick" data-lat="11.5936" data-lng="37.3908" data-z="12">Bahir Dar</button>
		<button type="button" class="btn btn-sm btn-outline-secondary py-0 px-2 event-map-quick" data-lat="7.0621" data-lng="38.4760" data-z="12">Hawassa</button>
		<button type="button" class="btn btn-sm btn-outline-secondary py-0 px-2 event-map-quick" data-lat="13.4967" data-lng="39.4753" data-z="11">Mekelle</button>
		<button type="button" class="btn btn-sm btn-outline-secondary py-0 px-2 event-map-quick" data-lat="8.9806" data-lng="38.7578" data-z="12">Bishoftu</button>
	</div>
	<div id="event-location-map" style="height:380px;width:100%;border-radius:8px;border:1px solid #dee2e6;z-index:10;position:relative;"></div>
	<p class="text-muted small mt-1 mb-0">Geocoding uses OpenStreetMap Nominatim (Ethiopia only). Use moderately; prefer quick cities or fine-tune by dragging the pin.</p>
</div>
<script>
(function () {
	function ready(fn) {
		if (document.readyState !== 'loading') { fn(); }
		else { document.addEventListener('DOMContentLoaded', fn); }
	}

	ready(function () {
		var latEl = document.querySelector('input[name="latitude"]');
		var lngEl = document.querySelector('input[name="longtitude"]');
		var mapEl = document.getElementById('event-location-map');
		if (!latEl || !lngEl || !mapEl || typeof L === 'undefined') { return; }

		// Ethiopia overview (not single-city zoom)
		var ethCenter = [9.145, 40.4897];
		var ethZoom = 6;

		var defLat = 9.0320;
		var defLng = 38.7469;
		var lat = parseFloat(String(latEl.value).replace(',', '.'));
		var lng = parseFloat(String(lngEl.value).replace(',', '.'));
		var hadCoords = isFinite(lat) && isFinite(lng);
		if (!hadCoords) {
			lat = defLat;
			lng = defLng;
			latEl.value = lat.toFixed(6);
			lngEl.value = lng.toFixed(6);
		}

		var map = L.map(mapEl);
		L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
			attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
		}).addTo(map);

		if (hadCoords) {
			map.setView([lat, lng], 13);
		} else {
			map.setView(ethCenter, ethZoom);
		}

		setTimeout(function () { map.invalidateSize(); }, 400);

		var marker = L.marker([lat, lng], { draggable: true }).addTo(map);

		function syncInputs(ll) {
			latEl.value = ll.lat.toFixed(6);
			lngEl.value = ll.lng.toFixed(6);
		}

		marker.on('dragend', function (e) {
			syncInputs(e.target.getLatLng());
		});

		map.on('click', function (e) {
			marker.setLatLng(e.latlng);
			syncInputs(e.latlng);
		});

		function applyFromInputs() {
			var la = parseFloat(String(latEl.value).replace(',', '.'));
			var ln = parseFloat(String(lngEl.value).replace(',', '.'));
			if (isFinite(la) && isFinite(ln)) {
				marker.setLatLng([la, ln]);
				map.setView([la, ln], Math.max(map.getZoom(), 13));
			}
		}

		latEl.addEventListener('change', applyFromInputs);
		lngEl.addEventListener('change', applyFromInputs);

		document.querySelectorAll('.event-map-quick').forEach(function (btn) {
			btn.addEventListener('click', function () {
				var la = parseFloat(btn.getAttribute('data-lat'));
				var ln = parseFloat(btn.getAttribute('data-lng'));
				var z = parseInt(btn.getAttribute('data-z'), 10) || 12;
				if (!isFinite(la) || !isFinite(ln)) { return; }
				marker.setLatLng([la, ln]);
				map.setView([la, ln], z);
				syncInputs({ lat: la, lng: ln });
			});
		});

		var qEl = document.getElementById('event-map-search-q');
		var btnEl = document.getElementById('event-map-search-btn');
		var selEl = document.getElementById('event-map-search-results');

		function setSearchResults(items) {
			if (!selEl) { return; }
			selEl.innerHTML = '<option value="">— Pick a result —</option>';
			if (!items || !items.length) {
				selEl.style.display = 'none';
				return;
			}
			items.forEach(function (it, i) {
				var o = document.createElement('option');
				o.value = String(i);
				o.textContent = it.display_name.substring(0, 80) + (it.display_name.length > 80 ? '…' : '');
				selEl.appendChild(o);
			});
			selEl.style.display = '';
			selEl._items = items;
		}

		function goToItem(it) {
			var la = parseFloat(it.lat);
			var ln = parseFloat(it.lon);
			if (!isFinite(la) || !isFinite(ln)) { return; }
			marker.setLatLng([la, ln]);
			map.setView([la, ln], 15);
			syncInputs({ lat: la, lng: ln });
		}

		if (selEl) {
			selEl.addEventListener('change', function () {
				var idx = selEl.value;
				if (idx === '' || !selEl._items) { return; }
				goToItem(selEl._items[parseInt(idx, 10)]);
			});
		}

		var msgEl = document.getElementById('event-map-search-msg');
		function mapMsg(text, show) {
			if (!msgEl) { return; }
			if (show) {
				msgEl.textContent = text;
				msgEl.style.display = '';
			} else {
				msgEl.textContent = '';
				msgEl.style.display = 'none';
			}
		}

		function runSearch() {
			mapMsg('', false);
			var q = (qEl && qEl.value) ? qEl.value.trim() : '';
			if (!q) { return; }
			var url = 'https://nominatim.openstreetmap.org/search?format=json&limit=8&countrycodes=et&q=' + encodeURIComponent(q);
			btnEl.disabled = true;
			fetch(url, { headers: { 'Accept-Language': 'en' } })
				.then(function (r) { return r.json(); })
				.then(function (data) {
					btnEl.disabled = false;
					if (!data || !data.length) {
						setSearchResults([]);
						mapMsg('No places found. Try another keyword or use a quick city.', true);
						return;
					}
					mapMsg('', false);
					setSearchResults(data);
					goToItem(data[0]);
				})
				.catch(function () {
					btnEl.disabled = false;
					mapMsg('Search failed. Check your network or try again shortly.', true);
				});
		}

		if (btnEl) { btnEl.addEventListener('click', runSearch); }
		if (qEl) {
			qEl.addEventListener('keydown', function (ev) {
				if (ev.key === 'Enter') {
					ev.preventDefault();
					runSearch();
				}
			});
		}
	});
})();
</script>
