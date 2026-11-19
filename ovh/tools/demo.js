// Static demo of the admin (GitHub Pages): everything is clickable, nothing is saved.
(function () {
  "use strict";
  var BASE = window.DEMO_BASE || "";

  function toast(text) {
    var t = document.getElementById("demo-toast");
    if (!t) {
      t = document.createElement("div");
      t.id = "demo-toast";
      t.setAttribute("role", "status");
      t.style.cssText = "position:fixed;left:50%;bottom:24px;transform:translateX(-50%);z-index:9999;max-width:min(560px,calc(100vw - 32px));" +
        "background:#17724A;color:#fff;padding:14px 20px;border-radius:12px;box-shadow:0 10px 30px rgba(0,0,0,.25);font:600 15px/1.45 Nunito,system-ui,sans-serif;text-align:center";
      document.body.appendChild(t);
    }
    t.textContent = text;
    t.style.display = "block";
    clearTimeout(t._h);
    t._h = setTimeout(function () { t.style.display = "none"; }, 4200);
  }

  // Banner
  document.addEventListener("DOMContentLoaded", function () {
    var b = document.createElement("div");
    b.style.cssText = "position:fixed;left:16px;bottom:16px;z-index:9998;max-width:calc(100vw - 32px);background:#FFF5D6;color:#5C4400;border:1px solid #F0D98A;border-radius:999px;padding:8px 16px;box-shadow:0 4px 14px rgba(0,0,0,.12);font:600 13px/1.4 Nunito,system-ui,sans-serif";
    b.innerHTML = "Démo : rien n’est enregistré · <a href='" + BASE + "/fr/' style='color:inherit;text-decoration:underline'>Voir le site</a>";
    document.body.appendChild(b);
    // The demo cannot remember changes, so never block navigation.
    window.onbeforeunload = null;
  });
  window.addEventListener("beforeunload", function (e) { e.stopImmediatePropagation(); }, true);

  function handle(form, submitter) {
    var action = (submitter && submitter.getAttribute("formaction")) || form.getAttribute("action") || location.pathname;
    if (/\/admin\/connexion/.test(action)) { location.href = BASE + "/admin/"; return; }
    if (/\/admin\/deconnexion/.test(action)) { location.href = BASE + "/admin/connexion/"; return; }
    if (/\/apercu\//.test(action)) {
      toast("Démo : l’aperçu ouvre normalement la page avec vos modifications, sans les publier.");
      return;
    }
    if ((form.getAttribute("method") || "get").toLowerCase() === "get") {
      toast("Démo : la recherche et les filtres fonctionnent sur le vrai site.");
      return;
    }
    document.querySelectorAll(".is-busy").forEach(function (b) { b.classList.remove("is-busy"); if (b.lastChild) b.lastChild.textContent = "Enregistrer"; });
    var state = document.querySelector("[data-save-state]");
    if (state) state.textContent = "";
    toast("✓ Démo : sur le vrai site, ce serait enregistré et visible immédiatement.");
  }

  // Let the admin script do its work, then stop the real submission.
  window.addEventListener("submit", function (e) {
    var waiting = e.defaultPrevented; // e.g. a confirmation dialog is open
    e.preventDefault();
    if (!waiting) handle(e.target, e.submitter);
  });
  HTMLFormElement.prototype.submit = function () { handle(this, null); };

  // API calls
  var realFetch = window.fetch.bind(window);
  window.fetch = function (url, opts) {
    url = String(url);
    if (url.indexOf("/admin/api/medias?") === 0) {
      var q = (url.match(/[?&]q=([^&]*)/) || [])[1];
      var kind = (url.match(/[?&]kind=([^&]*)/) || [])[1];
      return realFetch(BASE + "/admin/api/medias.json").then(function (r) { return r.json(); }).then(function (d) {
        var items = d.items.filter(function (i) {
          return (!kind || i.kind === kind) && (!q || i.name.toLowerCase().indexOf(decodeURIComponent(q).toLowerCase()) >= 0);
        });
        return new Response(JSON.stringify({ items: items }), { headers: { "Content-Type": "application/json" } });
      });
    }
    if (url.indexOf("/admin/api/") === 0) {
      return Promise.resolve(new Response('{"ok":true}', { headers: { "Content-Type": "application/json" } }));
    }
    return realFetch(url, opts);
  };

  // Uploads: show the chosen file locally, without sending it anywhere.
  var open = XMLHttpRequest.prototype.open, send = XMLHttpRequest.prototype.send;
  XMLHttpRequest.prototype.open = function (m, url) { this._demo = /\/admin\/api\/upload/.test(url); return open.apply(this, arguments); };
  XMLHttpRequest.prototype.send = function (body) {
    if (!this._demo) return send.apply(this, arguments);
    var xhr = this, file = body.get("file"), isImg = /^image\//.test(file.type);
    var url = URL.createObjectURL(file);
    var size = file.size > 1048576 ? (file.size / 1048576).toFixed(1) + " Mo" : Math.round(file.size / 1024) + " Ko";
    var name = file.name.replace(/[<>&"]/g, "");
    var item = {
      id: "demo" + Date.now(), kind: isImg ? "image" : "file", name: name, url: url, thumb: url, size: size, alt: "",
      badge: isImg ? "IMG" : "PDF",
      preview: isImg
        ? '<img src="' + url + '" alt=""><div class="media-meta"><strong>' + name + "</strong><span>" + size + " · démo, non enregistré</span></div>"
        : '<div class="file-chip"><span class="file-badge">DOC</span><div class="media-meta"><strong>' + name + "</strong><span>" + size + " · démo, non enregistré</span></div></div>"
    };
    Object.defineProperty(xhr, "responseText", { value: JSON.stringify({ item: item }) });
    setTimeout(function () {
      if (xhr.upload && xhr.upload.onprogress) xhr.upload.onprogress({ lengthComputable: true, loaded: 1, total: 1 });
      if (xhr.onload) xhr.onload();
    }, 400);
  };
})();
