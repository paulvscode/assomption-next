// Administration du site — interactions. Plain JavaScript, no dependencies.
(function () {
  "use strict";

  var $ = function (sel, root) { return (root || document).querySelector(sel); };
  var $$ = function (sel, root) { return Array.prototype.slice.call((root || document).querySelectorAll(sel)); };
  var csrfMeta = $('meta[name="csrf"]');
  var CSRF = csrfMeta ? csrfMeta.content : "";
  var MAX_MB = parseInt(($('meta[name="max-upload"]') || {}).content || "20", 10);
  var uid = 0;

  function post(url, data) {
    var body = new FormData();
    Object.keys(data || {}).forEach(function (k) { body.append(k, data[k]); });
    return fetch(url, { method: "POST", body: body, headers: { "X-CSRF-Token": CSRF }, credentials: "same-origin" })
      .then(function (r) { return r.json(); });
  }

  // ── Generic UI ────────────────────────────────────────────────────────
  var menuBtn = $("[data-menu-toggle]");
  if (menuBtn) {
    menuBtn.addEventListener("click", function () {
      var open = !$("#sidebar").classList.contains("is-open");
      $("#sidebar").classList.toggle("is-open", open);
      document.body.classList.toggle("menu-open", open);
      menuBtn.setAttribute("aria-expanded", String(open));
    });
    document.addEventListener("click", function (e) {
      if (document.body.classList.contains("menu-open") && !e.target.closest("#sidebar") && !e.target.closest("[data-menu-toggle]")) menuBtn.click();
    });
  }

  document.addEventListener("click", function (e) {
    var dismiss = e.target.closest("[data-dismiss]");
    if (dismiss) dismiss.closest(".flash").remove();

    var show = e.target.closest("[data-show-password]");
    if (show) {
      var input = document.getElementById(show.getAttribute("data-show-password"));
      var visible = input.type === "password";
      input.type = visible ? "text" : "password";
      show.setAttribute("aria-pressed", String(visible));
      show.lastChild.textContent = visible ? "Masquer" : "Afficher";
    }

    var copy = e.target.closest("[data-copy]");
    if (copy) {
      var text = copy.getAttribute("data-copy");
      var done = function () {
        var old = copy.innerHTML;
        copy.textContent = "Copié ✓";
        setTimeout(function () { copy.innerHTML = old; }, 1600);
      };
      if (navigator.clipboard) navigator.clipboard.writeText(text).then(done);
      else { window.prompt("Copiez ce texte :", text); }
    }
  });

  // Switch labels follow their state.
  function initToggles(root) {
    $$("[data-toggle]", root).forEach(function (cb) {
      var label = cb.closest(".switch").querySelector(".switch-text");
      cb.addEventListener("change", function () {
        label.textContent = cb.checked ? label.getAttribute("data-on") : label.getAttribute("data-off");
      });
    });
  }

  // Character counters.
  function initCounters(root) {
    $$("[data-count]", root).forEach(function (el) {
      if (el._counter) return;
      var max = parseInt(el.getAttribute("maxlength"), 10);
      var out = document.createElement("span");
      out.className = "char-count";
      el.insertAdjacentElement("afterend", out);
      el._counter = out;
      var update = function () {
        var n = el.value.length;
        out.textContent = n + " / " + max + " caractères";
        out.classList.toggle("is-over", n >= max);
      };
      el.addEventListener("input", update);
      update();
    });
  }

  // Colour input shows its value.
  $$('input[type="color"]').forEach(function (c) {
    var code = c.parentNode.querySelector("[data-color-value]");
    c.addEventListener("input", function () { if (code) code.textContent = c.value.toUpperCase(); });
  });

  // Password strength.
  $$("[data-strength]").forEach(function (input) {
    var meter = input.closest(".field").querySelector("[data-strength-meter]");
    input.addEventListener("input", function () {
      var v = input.value, score = 0;
      if (v.length >= 10) score++;
      if (v.length >= 14) score++;
      if (/[A-Z]/.test(v) && /[a-z]/.test(v)) score++;
      if (/\d/.test(v)) score++;
      if (/[^A-Za-z0-9]/.test(v) || / /.test(v)) score++;
      var levels = [["Trop court", "#B42318", 15], ["Faible", "#B42318", 30], ["Moyen", "#8A5A00", 55], ["Bon", "#17724A", 80], ["Très bon", "#17724A", 100], ["Excellent", "#17724A", 100]];
      var l = v.length < 10 ? levels[0] : levels[score];
      meter.hidden = v.length === 0;
      meter.style.setProperty("--w", l[2] + "%");
      meter.style.setProperty("--c", l[1]);
      meter.querySelector("small").textContent = l[0];
    });
  });

  // ── Confirm dialog ────────────────────────────────────────────────────
  var confirmModal = $("#confirm-modal");
  function askConfirm(text, okLabel) {
    return new Promise(function (resolve) {
      if (!confirmModal || !confirmModal.showModal) { resolve(window.confirm(text)); return; }
      $("[data-confirm-text]", confirmModal).textContent = text;
      $("[data-confirm-ok]", confirmModal).textContent = okLabel || "Confirmer";
      var finish = function (v) {
        confirmModal.close();
        $("[data-confirm-ok]", confirmModal).onclick = null;
        $("[data-confirm-cancel]", confirmModal).onclick = null;
        resolve(v);
      };
      $("[data-confirm-ok]", confirmModal).onclick = function () { finish(true); };
      $("[data-confirm-cancel]", confirmModal).onclick = function () { finish(false); };
      confirmModal.onclose = function () { resolve(false); };
      confirmModal.showModal();
      $("[data-confirm-cancel]", confirmModal).focus();
    });
  }
  $$("form[data-confirm]").forEach(function (form) {
    form.addEventListener("submit", function (e) {
      if (form._confirmed) return;
      e.preventDefault();
      askConfirm(form.getAttribute("data-confirm"), form.getAttribute("data-confirm-ok")).then(function (ok) {
        if (ok) { form._confirmed = true; form.submit(); }
      });
    });
  });

  // ── Rich text editor ──────────────────────────────────────────────────
  var linkModal = $("#link-modal");
  function initRich(root) {
    $$("[data-rich]", root).forEach(function (box) {
      if (box._rich) return;
      box._rich = true;
      var area = $(".rich-area", box);
      var store = $("textarea", box);
      var sync = function () {
        var html = area.innerHTML;
        if (area.textContent.trim() === "" && !/<li|<img/i.test(html)) html = "";
        store.value = html;
      };
      area.addEventListener("input", sync);
      area.addEventListener("blur", sync);

      // Paste: keep the words, drop Word/website formatting.
      area.addEventListener("paste", function (e) {
        var data = e.clipboardData;
        if (!data) return;
        e.preventDefault();
        var text = data.getData("text/plain");
        var paras = text.replace(/\r/g, "").split(/\n{2,}/);
        var html = paras.map(function (p) {
          var lines = p.split("\n").map(escapeHtml);
          var isList = lines.length > 1 && lines.every(function (l) { return /^\s*([-•*·]|\d+[.)])\s+/.test(l); });
          if (isList) return "<ul>" + lines.map(function (l) { return "<li>" + l.replace(/^\s*([-•*·]|\d+[.)])\s+/, "") + "</li>"; }).join("") + "</ul>";
          return "<p>" + lines.join("<br>") + "</p>";
        }).join("");
        document.execCommand("insertHTML", false, paras.length === 1 && paras[0].indexOf("\n") < 0 ? escapeHtml(text) : html);
        sync();
      });

      // Enter in an empty editor should create paragraphs, not divs.
      area.addEventListener("focus", function () {
        try { document.execCommand("defaultParagraphSeparator", false, "p"); } catch (err) { /* old browsers */ }
      });

      $$("[data-cmd]", box).forEach(function (btn) {
        btn.addEventListener("mousedown", function (e) { e.preventDefault(); }); // keep the selection
        btn.addEventListener("click", function () {
          var cmd = btn.getAttribute("data-cmd");
          area.focus();
          if (cmd === "link") { openLink(area, sync); return; }
          if (cmd.indexOf("formatBlock:") === 0) {
            var tag = cmd.split(":")[1];
            var current = currentBlock(area);
            document.execCommand("formatBlock", false, current === tag && tag !== "p" ? "p" : tag);
          } else if (cmd === "removeFormat") {
            document.execCommand("removeFormat");
            document.execCommand("formatBlock", false, "p");
          } else {
            document.execCommand(cmd, false, null);
          }
          sync();
          refreshToolbar(box, area);
        });
      });
      area.addEventListener("keyup", function () { refreshToolbar(box, area); });
      area.addEventListener("mouseup", function () { refreshToolbar(box, area); });
    });
  }
  function currentBlock(area) {
    var sel = window.getSelection();
    if (!sel.rangeCount) return "";
    var n = sel.anchorNode;
    while (n && n !== area) {
      if (n.nodeType === 1 && /^(P|H2|H3|BLOCKQUOTE|LI)$/.test(n.nodeName)) return n.nodeName.toLowerCase();
      n = n.parentNode;
    }
    return "";
  }
  function refreshToolbar(box, area) {
    var block = currentBlock(area);
    $$("[data-cmd]", box).forEach(function (b) {
      var cmd = b.getAttribute("data-cmd"), active = false;
      if (cmd.indexOf("formatBlock:") === 0) active = cmd.split(":")[1] === block;
      else if (/^(bold|italic|underline|insertUnorderedList|insertOrderedList)$/.test(cmd)) {
        try { active = document.queryCommandState(cmd); } catch (e) { active = false; }
      }
      b.classList.toggle("is-active", active);
    });
  }
  function openLink(area, sync) {
    var sel = window.getSelection();
    if (!sel.rangeCount || sel.isCollapsed) {
      alertBox("Sélectionnez d’abord, avec la souris, les mots à transformer en lien, puis cliquez sur « Lien ».");
      return;
    }
    var range = sel.getRangeAt(0).cloneRange();
    var input = $("[data-link-url]", linkModal);
    input.value = "";
    linkModal.showModal();
    input.focus();
    var form = $("[data-link-form]", linkModal);
    var cancel = $("[data-link-cancel]", linkModal);
    cancel.onclick = function () { linkModal.close(); };
    form.onsubmit = function (e) {
      e.preventDefault();
      var url = input.value.trim();
      linkModal.close();
      if (!url) return;
      if (/^[^@\s]+@[^@\s]+\.[a-z]{2,}$/i.test(url)) url = "mailto:" + url;
      else if (/^\+?[\d\s.]{8,}$/.test(url)) url = "tel:" + url.replace(/[\s.]/g, "");
      else if (!/^(https?:|mailto:|tel:|\/|#)/i.test(url)) url = "https://" + url;
      area.focus();
      sel.removeAllRanges();
      sel.addRange(range);
      document.execCommand("createLink", false, url);
      sync();
    };
  }
  function alertBox(text) {
    if (!confirmModal) { window.alert(text); return; }
    $("[data-confirm-text]", confirmModal).textContent = text;
    $("[data-confirm-title]", confirmModal).textContent = "Petite astuce";
    var ok = $("[data-confirm-ok]", confirmModal), cancel = $("[data-confirm-cancel]", confirmModal);
    ok.textContent = "J’ai compris";
    ok.className = "btn btn-primary";
    cancel.hidden = true;
    ok.onclick = function () { confirmModal.close(); };
    confirmModal.onclose = function () {
      cancel.hidden = false; ok.className = "btn btn-danger";
      $("[data-confirm-title]", confirmModal).textContent = "Êtes-vous sûr(e) ?";
    };
    confirmModal.showModal();
  }
  function escapeHtml(s) {
    return s.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;");
  }

  // ── Repeaters ─────────────────────────────────────────────────────────
  function initRepeaters(root) {
    $$("[data-repeater]", root).forEach(function (rep) {
      if (rep._init) return;
      rep._init = true;
      var list = $("[data-items]", rep);
      var tpl = $("template[data-template]", rep);
      // Long lists start folded so the page stays readable.
      var items = $$(":scope > [data-item]", list);
      if (items.length > 4) items.forEach(function (it) { it.classList.add("is-collapsed"); });

      $("[data-add]", rep).addEventListener("click", function () {
        var token = "n" + (++uid);
        var html = tpl.innerHTML.replace(/__i__/g, token);
        var holder = document.createElement("div");
        holder.innerHTML = html;
        var item = holder.firstElementChild;
        list.appendChild(item);
        item.classList.add("is-new");
        initAll(item);
        var lang = document.body.classList.contains("lang-en") ? "en" : "fr";
        $$(".loc-pane", item).forEach(function (p) { p.hidden = p.getAttribute("data-lang") !== lang; });
        refreshItems(rep);
        markDirty(rep);
        var first = $("input:not([type=hidden]), textarea, .rich-area", item);
        if (first) first.focus();
        item.scrollIntoView({ behavior: "smooth", block: "center" });
      });
      list.addEventListener("click", function (e) {
        var item = e.target.closest("[data-item]");
        if (!item || item.parentNode !== list) return;
        var move = e.target.closest("[data-move]");
        var remove = e.target.closest("[data-remove]");
        if (move) {
          var dir = parseInt(move.getAttribute("data-move"), 10);
          var sib = dir < 0 ? item.previousElementSibling : item.nextElementSibling;
          if (sib) {
            list.insertBefore(item, dir < 0 ? sib : sib.nextElementSibling);
            refreshItems(rep);
            markDirty(rep);
            move.focus();
          }
        } else if (remove) {
          var hasContent = $$("input:not([type=hidden]), textarea", item).some(function (i) { return i.value.trim() !== ""; })
            || $$("[data-media-value]", item).some(function (i) { return i.value !== ""; });
          var label = rep.getAttribute("data-item-label").toLowerCase();
          var go = function () { item.remove(); refreshItems(rep); markDirty(rep); };
          if (!hasContent) go();
          else askConfirm("Supprimer ce bloc (« " + label + " ») et son contenu ? Vous pourrez encore annuler en quittant la page sans enregistrer.", "Supprimer").then(function (ok) { if (ok) go(); });
        } else if (e.target.closest(".item-head") && !e.target.closest("button")) {
          item.classList.toggle("is-collapsed");
        }
      });
      list.addEventListener("input", function (e) {
        var item = e.target.closest("[data-item]");
        if (item) updateSummary(item);
      });
      refreshItems(rep);
    });
  }
  function refreshItems(rep) {
    var items = $$(":scope > [data-item]", $("[data-items]", rep));
    items.forEach(function (it, i) {
      $("[data-number]", it).textContent = i + 1;
      $('[data-move="-1"]', it).disabled = i === 0;
      $('[data-move="1"]', it).disabled = i === items.length - 1;
    });
  }
  function updateSummary(item) {
    var first = $(".item-body input[type=text], .item-body textarea", item);
    var s = $("[data-summary]", item);
    if (first && s) s.textContent = first.value.length > 70 ? first.value.slice(0, 70) + "…" : first.value;
  }
  // Give every row its final position before the form is sent.
  function renumberRepeaters(form) {
    $$("[data-repeater]", form).forEach(function (rep) {
      var base = rep.getAttribute("data-name");
      if (base.indexOf("__i__") >= 0 || /\[n\d+\]/.test(base)) return; // nested template: handled by parent
      var esc = base.replace(/[.*+?^${}()|[\]\\]/g, "\\$&");
      var re = new RegExp("^" + esc + "\\[[^\\]]+\\]");
      $$(":scope > [data-item]", $("[data-items]", rep)).forEach(function (item, i) {
        $$("[name]", item).forEach(function (el) {
          el.name = el.name.replace(re, base + "[" + i + "]");
        });
      });
    });
  }

  // ── Tables ────────────────────────────────────────────────────────────
  function initTables(root) {
    $$("[data-table]", root).forEach(function (t) {
      if (t._init) return;
      t._init = true;
      var tbody = $("tbody", t);
      var cols = function () { return $$("td:not(.row-tools)", tbody.rows[0]).length; };
      var delBtn = function () {
        return '<td class="row-tools"><button type="button" data-row-del title="Supprimer cette ligne" aria-label="Supprimer cette ligne">✕</button></td>';
      };
      $("[data-row-add]", t).addEventListener("click", function () {
        var tr = document.createElement("tr");
        var html = "";
        for (var i = 0; i < cols(); i++) html += '<td><input type="text" aria-label="Cellule"></td>';
        tr.innerHTML = html + delBtn();
        tbody.appendChild(tr);
        $("input", tr).focus();
        markDirty(t);
      });
      $("[data-col-add]", t).addEventListener("click", function () {
        $$("tr", tbody).forEach(function (tr, r) {
          var td = document.createElement("td");
          td.innerHTML = '<input type="text" aria-label="' + (r === 0 ? "Titre de colonne" : "Cellule") + '">';
          tr.insertBefore(td, $(".row-tools", tr));
        });
        markDirty(t);
      });
      $("[data-col-del]", t).addEventListener("click", function () {
        if (cols() <= 1) return;
        var go = function () {
          $$("tr", tbody).forEach(function (tr) {
            var cells = $$("td:not(.row-tools)", tr);
            cells[cells.length - 1].remove();
          });
          markDirty(t);
        };
        var filled = $$("tr", tbody).some(function (tr) { var c = $$("td:not(.row-tools) input", tr); return c[c.length - 1].value.trim() !== ""; });
        if (filled) askConfirm("Retirer la dernière colonne et son contenu ?", "Retirer").then(function (ok) { if (ok) go(); });
        else go();
      });
      tbody.addEventListener("click", function (e) {
        var del = e.target.closest("[data-row-del]");
        if (!del) return;
        var tr = del.closest("tr");
        if (tbody.rows.length <= 1) return;
        var filled = $$("input", tr).some(function (i) { return i.value.trim() !== ""; });
        var go = function () { tr.remove(); if (!tbody.rows[0].classList.contains("head-row")) tbody.rows[0].classList.add("head-row"); markDirty(t); };
        if (filled) askConfirm("Supprimer cette ligne du tableau ?", "Supprimer").then(function (ok) { if (ok) go(); });
        else go();
      });
    });
  }
  function nameTables(form) {
    $$("[data-table]", form).forEach(function (t) {
      var base = t.getAttribute("data-name");
      $$("tbody tr", t).forEach(function (tr, r) {
        $$("td:not(.row-tools) input", tr).forEach(function (input, c) {
          input.name = base + "[" + r + "][" + c + "]";
        });
      });
    });
  }

  // ── Media picker ──────────────────────────────────────────────────────
  var modal = $("#media-modal");
  var picker = { kind: "image", multiple: false, onPick: null };
  function openPicker(kind, multiple, onPick) {
    picker = { kind: kind, multiple: multiple, onPick: onPick };
    $("#media-modal-title").textContent = kind === "image" ? (multiple ? "Ajouter des photos" : "Choisir une image") : "Choisir un fichier";
    $("[data-drop-title]", modal).textContent = kind === "image" ? (multiple ? "Envoyer des photos depuis l’ordinateur" : "Envoyer une image depuis l’ordinateur") : "Envoyer un fichier depuis l’ordinateur";
    $("[data-drop-hint]", modal).textContent = "Cliquez ici, ou glissez-déposez " + (multiple ? "les fichiers" : "le fichier") + " dans ce cadre. " + MAX_MB + " Mo maximum.";
    var input = $("[data-upload-input]", modal);
    input.accept = kind === "image" ? "image/jpeg,image/png,image/webp,image/gif" : ".pdf,.doc,.docx,.xls,.xlsx,.odt,.ods,image/*";
    input.multiple = multiple;
    $("[data-upload-error]", modal).hidden = true;
    $("[data-picker-search]", modal).value = "";
    loadLibrary("");
    modal.showModal();
  }
  function loadLibrary(q) {
    var grid = $("[data-picker-grid]", modal);
    fetch("/admin/api/medias?kind=" + (picker.kind === "image" ? "image" : "") + "&q=" + encodeURIComponent(q), { credentials: "same-origin" })
      .then(function (r) { return r.json(); })
      .then(function (res) {
        if (res.error) { grid.innerHTML = '<p class="field-error">' + escapeHtml(res.error) + "</p>"; return; }
        if (!res.items.length) { grid.innerHTML = '<p class="muted">Aucun fichier' + (q ? " trouvé." : " pour le moment : envoyez-en un ci-dessus.") + "</p>"; return; }
        grid.innerHTML = "";
        res.items.forEach(function (item) {
          var b = document.createElement("button");
          b.type = "button";
          b.className = "picker-item";
          b.innerHTML = (item.kind === "image" ? '<img src="' + item.thumb + '" alt="" loading="lazy">' : '<div class="file-badge">' + escapeHtml(item.badge) + "</div>") + "<span>" + escapeHtml(item.name) + "</span>";
          b.title = item.name;
          b.addEventListener("click", function () { pick([item]); });
          grid.appendChild(b);
        });
      })
      .catch(function () { grid.innerHTML = '<p class="field-error">La liste des fichiers n’a pas pu être chargée.</p>'; });
  }
  function pick(items) {
    modal.close();
    if (picker.onPick) picker.onPick(items);
  }
  function uploadFiles(files) {
    var list = Array.prototype.slice.call(files);
    if (!list.length) return;
    var err = $("[data-upload-error]", modal);
    var bar = $("[data-upload-progress]", modal);
    err.hidden = true;
    var tooBig = list.filter(function (f) { return f.size > MAX_MB * 1024 * 1024; });
    if (tooBig.length) {
      err.textContent = "« " + tooBig[0].name + " » est trop lourd (" + (tooBig[0].size / 1048576).toFixed(1) + " Mo). Maximum : " + MAX_MB + " Mo. Astuce : pour une photo, envoyez-la-vous par e-mail en taille « moyenne » ; pour un PDF, utilisez un compresseur en ligne.";
      err.hidden = false;
      return;
    }
    var done = [], i = 0;
    bar.hidden = false;
    var next = function () {
      if (i >= list.length) { bar.hidden = true; if (done.length) pick(done); return; }
      var f = list[i++];
      var fd = new FormData();
      fd.append("file", f);
      fd.append("kind", picker.kind);
      fd.append("_csrf", CSRF);
      var xhr = new XMLHttpRequest();
      xhr.open("POST", "/admin/api/upload");
      xhr.upload.onprogress = function (e) {
        if (e.lengthComputable) $("span", bar).style.width = Math.round(((i - 1) + e.loaded / e.total) / list.length * 100) + "%";
      };
      xhr.onload = function () {
        var res;
        try { res = JSON.parse(xhr.responseText); } catch (e2) { res = { error: "Le fichier n’a pas pu être envoyé (il est peut-être trop lourd)." }; }
        if (res.item) done.push(res.item);
        else { err.textContent = f.name + " : " + (res.error || "erreur inconnue"); err.hidden = false; }
        next();
      };
      xhr.onerror = function () { err.textContent = "La connexion a été interrompue. Réessayez."; err.hidden = false; bar.hidden = true; };
      xhr.send(fd);
    };
    next();
  }
  if (modal) {
    $("[data-modal-close]", modal).addEventListener("click", function () { modal.close(); });
    $("[data-upload-input]", modal).addEventListener("change", function () { uploadFiles(this.files); this.value = ""; });
    var t;
    $("[data-picker-search]", modal).addEventListener("input", function () {
      var q = this.value;
      clearTimeout(t);
      t = setTimeout(function () { loadLibrary(q); }, 250);
    });
  }
  $$("[data-dropzone]").forEach(function (z) {
    ["dragenter", "dragover"].forEach(function (ev) { z.addEventListener(ev, function (e) { e.preventDefault(); z.classList.add("is-over"); }); });
    ["dragleave", "drop"].forEach(function (ev) { z.addEventListener(ev, function (e) { e.preventDefault(); z.classList.remove("is-over"); }); });
    z.addEventListener("drop", function (e) {
      var input = $("input[type=file]", z);
      if (input.hasAttribute("data-upload-input")) uploadFiles(e.dataTransfer.files);
      else { input.files = e.dataTransfer.files; input.dispatchEvent(new Event("change")); }
    });
  });
  $$("[data-auto-submit]").forEach(function (input) {
    input.addEventListener("change", function () {
      if (!input.files.length) return;
      var form = input.closest("form");
      $("strong", form).textContent = "Envoi en cours… merci de patienter";
      form.submit();
    });
  });

  function initMediaInputs(root) {
    $$("[data-media-input]", root).forEach(function (box) {
      if (box._init) return;
      box._init = true;
      var kind = box.getAttribute("data-kind");
      var value = $("[data-media-value]", box);
      var preview = $("[data-media-preview]", box);
      var clear = $("[data-media-clear]", box);
      var label = $("[data-pick-label]", box);
      $("[data-media-pick]", box).addEventListener("click", function () {
        openPicker(kind, false, function (items) {
          var it = items[0];
          value.value = it.id;
          preview.innerHTML = it.preview;
          clear.hidden = false;
          label.textContent = kind === "image" ? "Changer l’image" : "Changer le fichier";
          initAlt(preview);
          markDirty(box);
        });
      });
      clear.addEventListener("click", function () {
        value.value = "";
        preview.innerHTML = '<div class="media-empty"><span>' + (kind === "image" ? "Aucune image" : "Aucun fichier") + "</span></div>";
        clear.hidden = true;
        label.textContent = kind === "image" ? "Choisir une image" : "Choisir un fichier";
        markDirty(box);
      });
    });
    $$("[data-gallery]", root).forEach(function (g) {
      if (g._init) return;
      g._init = true;
      var list = $("[data-gallery-list]", g);
      var name = g.getAttribute("data-name");
      var max = parseInt(g.getAttribute("data-max"), 10);
      $("[data-gallery-add]", g).addEventListener("click", function () {
        openPicker("image", true, function (items) {
          items.forEach(function (it) {
            if (list.children.length >= max) return;
            var li = document.createElement("li");
            li.className = "gallery-item";
            li.innerHTML = '<input type="hidden" name="' + escapeHtml(name) + '" value="' + it.id + '"><img src="' + it.thumb + '" alt="">' +
              '<div class="gallery-tools"><button type="button" data-g-move="-1" aria-label="Déplacer avant">‹</button><button type="button" data-g-remove aria-label="Retirer cette photo">✕</button><button type="button" data-g-move="1" aria-label="Déplacer après">›</button></div>';
            list.appendChild(li);
          });
          markDirty(g);
        });
      });
      list.addEventListener("click", function (e) {
        var li = e.target.closest(".gallery-item");
        if (!li) return;
        if (e.target.closest("[data-g-remove]")) { li.remove(); markDirty(g); }
        var mv = e.target.closest("[data-g-move]");
        if (mv) {
          var d = parseInt(mv.getAttribute("data-g-move"), 10);
          var sib = d < 0 ? li.previousElementSibling : li.nextElementSibling;
          if (sib) { list.insertBefore(li, d < 0 ? sib : sib.nextElementSibling); markDirty(g); }
        }
      });
    });
  }
  // Image descriptions are saved as soon as they are typed.
  function initAlt(root) {
    $$("[data-alt-for]", root).forEach(function (input) {
      if (input._init) return;
      input._init = true;
      input.addEventListener("change", function () {
        post("/admin/api/medias/" + input.getAttribute("data-alt-for") + "/alt", { alt: input.value }).then(function () {
          input.style.borderColor = "#17724A";
          setTimeout(function () { input.style.borderColor = ""; }, 1200);
        });
      });
    });
  }

  // ── Language switch ───────────────────────────────────────────────────
  var editor = $("[data-editor]");
  function setLang(lang) {
    document.body.classList.toggle("lang-en", lang === "en");
    $$(".loc-pane").forEach(function (p) { p.hidden = p.getAttribute("data-lang") !== lang; });
    $$(".lang-switch [data-lang]").forEach(function (b) { b.setAttribute("aria-pressed", String(b.getAttribute("data-lang") === lang)); });
    var help = $("[data-lang-help-en]");
    if (help) help.hidden = lang !== "en";
    var input = $("[data-lang-input]");
    if (input) input.value = lang;
    // Point labels at the visible input.
    $$("label[data-for-base]").forEach(function (l) {
      var id = l.getAttribute("data-for-base") + "-" + lang;
      if (document.getElementById(id)) l.htmlFor = id;
    });
    try { sessionStorage.setItem("admin-lang", lang); } catch (e) { /* private mode */ }
    countMissing();
  }
  function paneEmpty(pane) {
    var rich = $(".rich-area", pane);
    if (rich) return rich.textContent.trim() === "";
    var el = $("input[type=text], textarea", pane);
    return el ? el.value.trim() === "" : false;
  }
  function countMissing() {
    var badge = $("[data-en-missing]");
    if (!badge) return;
    var n = 0;
    $$(".field.is-loc").forEach(function (f) {
      if (f.closest("template")) return;
      var fr = $('.loc-pane[data-lang="fr"]', f), en = $('.loc-pane[data-lang="en"]', f);
      if (fr && en && !$("[data-table]", f) && !paneEmpty(fr) && paneEmpty(en)) n++;
    });
    badge.hidden = n === 0;
    badge.textContent = n + " à traduire";
  }
  if (editor) {
    $$(".lang-switch [data-lang]").forEach(function (b) {
      b.addEventListener("click", function () { setLang(b.getAttribute("data-lang")); });
    });
    var saved = null;
    try { saved = sessionStorage.getItem("admin-lang"); } catch (e) { /* ignore */ }
    if (saved === "en" && $(".lang-switch")) setLang("en"); else countMissing();
    editor.addEventListener("input", function () { clearTimeout(editor._cm); editor._cm = setTimeout(countMissing, 400); });
  }

  // ── Unsaved changes ───────────────────────────────────────────────────
  var dirty = false;
  function markDirty(el) {
    var form = el.closest ? el.closest("[data-track-changes]") : null;
    if (!form) return;
    dirty = true;
    var state = $("[data-save-state]", form);
    if (state) state.textContent = "Modifications non enregistrées";
  }
  $$("[data-track-changes]").forEach(function (form) {
    form.addEventListener("input", function (e) { markDirty(e.target); });
    form.addEventListener("change", function (e) { markDirty(e.target); });
    form.addEventListener("submit", function (e) {
      $$("[data-rich]", form).forEach(function (box) { $("textarea", box).value = $("textarea", box).value; $(".rich-area", box).dispatchEvent(new Event("blur")); });
      renumberRepeaters(form);
      nameTables(form);
      var isPreview = e.submitter && e.submitter.hasAttribute("data-preview");
      if (!isPreview) {
        dirty = false;
        $$("[data-save]", form).forEach(function (b) { b.classList.add("is-busy"); b.lastChild.textContent = "Enregistrement…"; });
      }
    });
  });
  window.addEventListener("beforeunload", function (e) {
    if (dirty) { e.preventDefault(); e.returnValue = ""; }
  });
  document.addEventListener("keydown", function (e) {
    if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === "s") {
      var save = $("[data-save]");
      if (save) { e.preventDefault(); save.click(); }
    }
  });
  // Keep the session alive while a form is open.
  if ($("[data-track-changes]")) {
    setInterval(function () { fetch("/admin/api/ping", { credentials: "same-origin" }); }, 10 * 60 * 1000);
  }

  // Errors: bring the summary into view.
  var summary = $("[data-error-summary]");
  if (summary) { summary.focus(); }

  function initAll(root) {
    initToggles(root);
    initCounters(root);
    initRich(root);
    initRepeaters(root);
    initTables(root);
    initMediaInputs(root);
    initAlt(root);
  }
  initAll(document);
})();
