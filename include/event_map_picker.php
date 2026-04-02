<?php
/**
 * Leaflet map: click or drag pin to fill latitude / longitude (Addis Ababa default).
 * Expects inputs name="latitude" and name="longtitude" (DB spelling).
 */
if (!defined('AWRAEVENT_MAP_PICKER_LOADED')) {
  define('AWRAEVENT_MAP_PICKER_LOADED', true);
?>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<?php } ?>
<div class="form-group col-12 mb-3">
	<label class="form-label">Map — set venue location</label>
	<p class="text-muted small mb-2">Map opens on <strong>Addis Ababa</strong>. Click the map or drag the pin; latitude and longitude update automatically. You can still type coordinates above if you prefer.</p>
	<div id="event-location-map" style="height:340px;width:100%;border-radius:8px;border:1px solid #dee2e6;z-index:10;position:relative;"></div>
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

		var defLat = 9.0320;
		var defLng = 38.7469;
		var lat = parseFloat(String(latEl.value).replace(',', '.'));
		var lng = parseFloat(String(lngEl.value).replace(',', '.'));
		if (!isFinite(lat) || !isFinite(lng)) {
			lat = defLat;
			lng = defLng;
			latEl.value = lat.toFixed(6);
			lngEl.value = lng.toFixed(6);
		}

		var map = L.map(mapEl).setView([lat, lng], 13);
		L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
			attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
		}).addTo(map);
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
				map.setView([la, ln], map.getZoom());
			}
		}

		latEl.addEventListener('change', applyFromInputs);
		lngEl.addEventListener('change', applyFromInputs);
	});
})();
</script>
