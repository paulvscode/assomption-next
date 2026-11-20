// Public site: menus and click-to-load map. No tracking, no dependencies.
(function () {
  "use strict";

  // Desktop dropdowns: open on hover (CSS) and also on click / keyboard.
  var dropdowns = document.querySelectorAll("[data-dropdown]");
  function closeAll(except) {
    dropdowns.forEach(function (d) {
      if (d !== except) {
        d.classList.remove("is-open");
        d.querySelector("button").setAttribute("aria-expanded", "false");
      }
    });
  }
  dropdowns.forEach(function (d) {
    var btn = d.querySelector("button");
    btn.addEventListener("click", function () {
      var open = !d.classList.contains("is-open");
      closeAll(d);
      d.classList.toggle("is-open", open);
      btn.setAttribute("aria-expanded", open ? "true" : "false");
    });
  });
  document.addEventListener("click", function (e) {
    if (!e.target.closest("[data-dropdown]")) closeAll(null);
  });
  document.addEventListener("keydown", function (e) {
    if (e.key === "Escape") {
      closeAll(null);
      closeMobile();
    }
  });

  // Mobile menu
  var toggle = document.querySelector("[data-mobile-toggle]");
  var panel = document.getElementById("mobile-menu");
  function closeMobile() {
    if (panel && !panel.hidden) {
      panel.hidden = true;
      toggle.setAttribute("aria-expanded", "false");
      document.body.style.overflow = "";
    }
  }
  if (toggle && panel) {
    toggle.addEventListener("click", function () {
      var open = panel.hidden;
      panel.hidden = !open;
      toggle.setAttribute("aria-expanded", open ? "true" : "false");
      document.body.style.overflow = open ? "hidden" : "";
    });
  }

  // Map: only contacts OpenStreetMap after the visitor asks for it.
  document.querySelectorAll("[data-map]").forEach(function (box) {
    var btn = box.querySelector("[data-map-load]");
    if (!btn) return;
    btn.addEventListener("click", function () {
      btn.disabled = true;
      var q = box.getAttribute("data-query");
      fetch("https://nominatim.openstreetmap.org/search?format=json&limit=1&q=" + encodeURIComponent(q))
        .then(function (r) { return r.json(); })
        .then(function (res) {
          if (!res.length) throw new Error("not found");
          var lat = parseFloat(res[0].lat), lon = parseFloat(res[0].lon), d = 0.004;
          var bbox = [lon - d, lat - d / 2, lon + d, lat + d / 2].join(",");
          var iframe = document.createElement("iframe");
          iframe.src = "https://www.openstreetmap.org/export/embed.html?bbox=" + bbox + "&layer=mapnik&marker=" + lat + "," + lon;
          iframe.title = q;
          iframe.loading = "lazy";
          iframe.style.cssText = "border:0;display:block;width:100%;height:400px";
          box.replaceChildren(iframe);
        })
        .catch(function () {
          window.open("https://www.openstreetmap.org/search?query=" + encodeURIComponent(q), "_blank", "noopener");
          btn.disabled = false;
        });
    });
  });
})();
