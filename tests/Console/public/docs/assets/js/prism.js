/* PrismJS 1.20.0
https://prismjs.com/download.html#themes=prism-okaidia&languages=markup+css+clike+javascript+diff+json+markup-templating+php+php-extras+plsql+sql+xml-doc+yaml&plugins=line-highlight+normalize-whitespace+toolbar+copy-to-clipboard+diff-highlight */
var _self = "undefined" != typeof window ? window : "undefined" != typeof WorkerGlobalScope && self instanceof WorkerGlobalScope ? self : {},
    Prism = function (u) {
  var c = /\blang(?:uage)?-([\w-]+)\b/i,
      n = 0,
      M = {
    manual: u.Prism && u.Prism.manual,
    disableWorkerMessageHandler: u.Prism && u.Prism.disableWorkerMessageHandler,
    util: {
      encode: function e(n) {
        return n instanceof W ? new W(n.type, e(n.content), n.alias) : Array.isArray(n) ? n.map(e) : n.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/\u00a0/g, " ");
      },
      type: function type(e) {
        return Object.prototype.toString.call(e).slice(8, -1);
      },
      objId: function objId(e) {
        return e.__id || Object.defineProperty(e, "__id", {
          value: ++n
        }), e.__id;
      },
      clone: function t(e, r) {
        var a, n;

        switch (r = r || {}, M.util.type(e)) {
          case "Object":
            if (n = M.util.objId(e), r[n]) return r[n];

            for (var i in a = {}, r[n] = a, e) {
              e.hasOwnProperty(i) && (a[i] = t(e[i], r));
            }

            return a;

          case "Array":
            return n = M.util.objId(e), r[n] ? r[n] : (a = [], r[n] = a, e.forEach(function (e, n) {
              a[n] = t(e, r);
            }), a);

          default:
            return e;
        }
      },
      getLanguage: function getLanguage(e) {
        for (; e && !c.test(e.className);) {
          e = e.parentElement;
        }

        return e ? (e.className.match(c) || [, "none"])[1].toLowerCase() : "none";
      },
      currentScript: function currentScript() {
        if ("undefined" == typeof document) return null;
        if ("currentScript" in document) return document.currentScript;

        try {
          throw new Error();
        } catch (e) {
          var n = (/at [^(\r\n]*\((.*):.+:.+\)$/i.exec(e.stack) || [])[1];

          if (n) {
            var t = document.getElementsByTagName("script");

            for (var r in t) {
              if (t[r].src == n) return t[r];
            }
          }

          return null;
        }
      },
      isActive: function isActive(e, n, t) {
        for (var r = "no-" + n; e;) {
          var a = e.classList;
          if (a.contains(n)) return !0;
          if (a.contains(r)) return !1;
          e = e.parentElement;
        }

        return !!t;
      }
    },
    languages: {
      extend: function extend(e, n) {
        var t = M.util.clone(M.languages[e]);

        for (var r in n) {
          t[r] = n[r];
        }

        return t;
      },
      insertBefore: function insertBefore(t, e, n, r) {
        var a = (r = r || M.languages)[t],
            i = {};

        for (var l in a) {
          if (a.hasOwnProperty(l)) {
            if (l == e) for (var o in n) {
              n.hasOwnProperty(o) && (i[o] = n[o]);
            }
            n.hasOwnProperty(l) || (i[l] = a[l]);
          }
        }

        var s = r[t];
        return r[t] = i, M.languages.DFS(M.languages, function (e, n) {
          n === s && e != t && (this[e] = i);
        }), i;
      },
      DFS: function e(n, t, r, a) {
        a = a || {};
        var i = M.util.objId;

        for (var l in n) {
          if (n.hasOwnProperty(l)) {
            t.call(n, l, n[l], r || l);
            var o = n[l],
                s = M.util.type(o);
            "Object" !== s || a[i(o)] ? "Array" !== s || a[i(o)] || (a[i(o)] = !0, e(o, t, l, a)) : (a[i(o)] = !0, e(o, t, null, a));
          }
        }
      }
    },
    plugins: {},
    highlightAll: function highlightAll(e, n) {
      M.highlightAllUnder(document, e, n);
    },
    highlightAllUnder: function highlightAllUnder(e, n, t) {
      var r = {
        callback: t,
        container: e,
        selector: 'code[class*="language-"], [class*="language-"] code, code[class*="lang-"], [class*="lang-"] code'
      };
      M.hooks.run("before-highlightall", r), r.elements = Array.prototype.slice.apply(r.container.querySelectorAll(r.selector)), M.hooks.run("before-all-elements-highlight", r);

      for (var a, i = 0; a = r.elements[i++];) {
        M.highlightElement(a, !0 === n, r.callback);
      }
    },
    highlightElement: function highlightElement(e, n, t) {
      var r = M.util.getLanguage(e),
          a = M.languages[r];
      e.className = e.className.replace(c, "").replace(/\s+/g, " ") + " language-" + r;
      var i = e.parentElement;
      i && "pre" === i.nodeName.toLowerCase() && (i.className = i.className.replace(c, "").replace(/\s+/g, " ") + " language-" + r);
      var l = {
        element: e,
        language: r,
        grammar: a,
        code: e.textContent
      };

      function o(e) {
        l.highlightedCode = e, M.hooks.run("before-insert", l), l.element.innerHTML = l.highlightedCode, M.hooks.run("after-highlight", l), M.hooks.run("complete", l), t && t.call(l.element);
      }

      if (M.hooks.run("before-sanity-check", l), !l.code) return M.hooks.run("complete", l), void (t && t.call(l.element));
      if (M.hooks.run("before-highlight", l), l.grammar) {
        if (n && u.Worker) {
          var s = new Worker(M.filename);
          s.onmessage = function (e) {
            o(e.data);
          }, s.postMessage(JSON.stringify({
            language: l.language,
            code: l.code,
            immediateClose: !0
          }));
        } else o(M.highlight(l.code, l.grammar, l.language));
      } else o(M.util.encode(l.code));
    },
    highlight: function highlight(e, n, t) {
      var r = {
        code: e,
        grammar: n,
        language: t
      };
      return M.hooks.run("before-tokenize", r), r.tokens = M.tokenize(r.code, r.grammar), M.hooks.run("after-tokenize", r), W.stringify(M.util.encode(r.tokens), r.language);
    },
    tokenize: function tokenize(e, n) {
      var t = n.rest;

      if (t) {
        for (var r in t) {
          n[r] = t[r];
        }

        delete n.rest;
      }

      var a = new i();
      return I(a, a.head, e), function e(n, t, r, a, i, l) {
        for (var o in r) {
          if (r.hasOwnProperty(o) && r[o]) {
            var s = r[o];
            s = Array.isArray(s) ? s : [s];

            for (var u = 0; u < s.length; ++u) {
              if (l && l.cause == o + "," + u) return;
              var c = s[u],
                  g = c.inside,
                  f = !!c.lookbehind,
                  h = !!c.greedy,
                  d = 0,
                  v = c.alias;

              if (h && !c.pattern.global) {
                var p = c.pattern.toString().match(/[imsuy]*$/)[0];
                c.pattern = RegExp(c.pattern.source, p + "g");
              }

              for (var m = c.pattern || c, y = a.next, k = i; y !== t.tail && !(l && k >= l.reach); k += y.value.length, y = y.next) {
                var b = y.value;
                if (t.length > n.length) return;

                if (!(b instanceof W)) {
                  var x = 1;

                  if (h && y != t.tail.prev) {
                    m.lastIndex = k;
                    var w = m.exec(n);
                    if (!w) break;
                    var A = w.index + (f && w[1] ? w[1].length : 0),
                        P = w.index + w[0].length,
                        S = k;

                    for (S += y.value.length; S <= A;) {
                      y = y.next, S += y.value.length;
                    }

                    if (S -= y.value.length, k = S, y.value instanceof W) continue;

                    for (var E = y; E !== t.tail && (S < P || "string" == typeof E.value); E = E.next) {
                      x++, S += E.value.length;
                    }

                    x--, b = n.slice(k, S), w.index -= k;
                  } else {
                    m.lastIndex = 0;
                    var w = m.exec(b);
                  }

                  if (w) {
                    f && (d = w[1] ? w[1].length : 0);
                    var A = w.index + d,
                        O = w[0].slice(d),
                        P = A + O.length,
                        L = b.slice(0, A),
                        N = b.slice(P),
                        j = k + b.length;
                    l && j > l.reach && (l.reach = j);
                    var C = y.prev;
                    L && (C = I(t, C, L), k += L.length), z(t, C, x);

                    var _ = new W(o, g ? M.tokenize(O, g) : O, v, O);

                    y = I(t, C, _), N && I(t, y, N), 1 < x && e(n, t, r, y.prev, k, {
                      cause: o + "," + u,
                      reach: j
                    });
                  }
                }
              }
            }
          }
        }
      }(e, a, n, a.head, 0), function (e) {
        var n = [],
            t = e.head.next;

        for (; t !== e.tail;) {
          n.push(t.value), t = t.next;
        }

        return n;
      }(a);
    },
    hooks: {
      all: {},
      add: function add(e, n) {
        var t = M.hooks.all;
        t[e] = t[e] || [], t[e].push(n);
      },
      run: function run(e, n) {
        var t = M.hooks.all[e];
        if (t && t.length) for (var r, a = 0; r = t[a++];) {
          r(n);
        }
      }
    },
    Token: W
  };

  function W(e, n, t, r) {
    this.type = e, this.content = n, this.alias = t, this.length = 0 | (r || "").length;
  }

  function i() {
    var e = {
      value: null,
      prev: null,
      next: null
    },
        n = {
      value: null,
      prev: e,
      next: null
    };
    e.next = n, this.head = e, this.tail = n, this.length = 0;
  }

  function I(e, n, t) {
    var r = n.next,
        a = {
      value: t,
      prev: n,
      next: r
    };
    return n.next = a, r.prev = a, e.length++, a;
  }

  function z(e, n, t) {
    for (var r = n.next, a = 0; a < t && r !== e.tail; a++) {
      r = r.next;
    }

    (n.next = r).prev = n, e.length -= a;
  }

  if (u.Prism = M, W.stringify = function n(e, t) {
    if ("string" == typeof e) return e;

    if (Array.isArray(e)) {
      var r = "";
      return e.forEach(function (e) {
        r += n(e, t);
      }), r;
    }

    var a = {
      type: e.type,
      content: n(e.content, t),
      tag: "span",
      classes: ["token", e.type],
      attributes: {},
      language: t
    },
        i = e.alias;
    i && (Array.isArray(i) ? Array.prototype.push.apply(a.classes, i) : a.classes.push(i)), M.hooks.run("wrap", a);
    var l = "";

    for (var o in a.attributes) {
      l += " " + o + '="' + (a.attributes[o] || "").replace(/"/g, "&quot;") + '"';
    }

    return "<" + a.tag + ' class="' + a.classes.join(" ") + '"' + l + ">" + a.content + "</" + a.tag + ">";
  }, !u.document) return u.addEventListener && (M.disableWorkerMessageHandler || u.addEventListener("message", function (e) {
    var n = JSON.parse(e.data),
        t = n.language,
        r = n.code,
        a = n.immediateClose;
    u.postMessage(M.highlight(r, M.languages[t], t)), a && u.close();
  }, !1)), M;
  var e = M.util.currentScript();

  function t() {
    M.manual || M.highlightAll();
  }

  if (e && (M.filename = e.src, e.hasAttribute("data-manual") && (M.manual = !0)), !M.manual) {
    var r = document.readyState;
    "loading" === r || "interactive" === r && e && e.defer ? document.addEventListener("DOMContentLoaded", t) : window.requestAnimationFrame ? window.requestAnimationFrame(t) : window.setTimeout(t, 16);
  }

  return M;
}(_self);

"undefined" != typeof module && module.exports && (module.exports = Prism), "undefined" != typeof global && (global.Prism = Prism);
Prism.languages.markup = {
  comment: /<!--[\s\S]*?-->/,
  prolog: /<\?[\s\S]+?\?>/,
  doctype: {
    pattern: /<!DOCTYPE(?:[^>"'[\]]|"[^"]*"|'[^']*')+(?:\[(?:[^<"'\]]|"[^"]*"|'[^']*'|<(?!!--)|<!--(?:[^-]|-(?!->))*-->)*\]\s*)?>/i,
    greedy: !0,
    inside: {
      "internal-subset": {
        pattern: /(\[)[\s\S]+(?=\]>$)/,
        lookbehind: !0,
        greedy: !0,
        inside: null
      },
      string: {
        pattern: /"[^"]*"|'[^']*'/,
        greedy: !0
      },
      punctuation: /^<!|>$|[[\]]/,
      "doctype-tag": /^DOCTYPE/,
      name: /[^\s<>'"]+/
    }
  },
  cdata: /<!\[CDATA\[[\s\S]*?]]>/i,
  tag: {
    pattern: /<\/?(?!\d)[^\s>\/=$<%]+(?:\s(?:\s*[^\s>\/=]+(?:\s*=\s*(?:"[^"]*"|'[^']*'|[^\s'">=]+(?=[\s>]))|(?=[\s/>])))+)?\s*\/?>/,
    greedy: !0,
    inside: {
      tag: {
        pattern: /^<\/?[^\s>\/]+/,
        inside: {
          punctuation: /^<\/?/,
          namespace: /^[^\s>\/:]+:/
        }
      },
      "attr-value": {
        pattern: /=\s*(?:"[^"]*"|'[^']*'|[^\s'">=]+)/,
        inside: {
          punctuation: [{
            pattern: /^=/,
            alias: "attr-equals"
          }, /"|'/]
        }
      },
      punctuation: /\/?>/,
      "attr-name": {
        pattern: /[^\s>\/]+/,
        inside: {
          namespace: /^[^\s>\/:]+:/
        }
      }
    }
  },
  entity: [{
    pattern: /&[\da-z]{1,8};/i,
    alias: "named-entity"
  }, /&#x?[\da-f]{1,8};/i]
}, Prism.languages.markup.tag.inside["attr-value"].inside.entity = Prism.languages.markup.entity, Prism.languages.markup.doctype.inside["internal-subset"].inside = Prism.languages.markup, Prism.hooks.add("wrap", function (a) {
  "entity" === a.type && (a.attributes.title = a.content.replace(/&amp;/, "&"));
}), Object.defineProperty(Prism.languages.markup.tag, "addInlined", {
  value: function value(a, e) {
    var s = {};
    s["language-" + e] = {
      pattern: /(^<!\[CDATA\[)[\s\S]+?(?=\]\]>$)/i,
      lookbehind: !0,
      inside: Prism.languages[e]
    }, s.cdata = /^<!\[CDATA\[|\]\]>$/i;
    var n = {
      "included-cdata": {
        pattern: /<!\[CDATA\[[\s\S]*?\]\]>/i,
        inside: s
      }
    };
    n["language-" + e] = {
      pattern: /[\s\S]+/,
      inside: Prism.languages[e]
    };
    var t = {};
    t[a] = {
      pattern: RegExp("(<__[^]*?>)(?:<!\\[CDATA\\[(?:[^\\]]|\\](?!\\]>))*\\]\\]>|(?!<!\\[CDATA\\[)[^])*?(?=</__>)".replace(/__/g, function () {
        return a;
      }), "i"),
      lookbehind: !0,
      greedy: !0,
      inside: n
    }, Prism.languages.insertBefore("markup", "cdata", t);
  }
}), Prism.languages.html = Prism.languages.markup, Prism.languages.mathml = Prism.languages.markup, Prism.languages.svg = Prism.languages.markup, Prism.languages.xml = Prism.languages.extend("markup", {}), Prism.languages.ssml = Prism.languages.xml, Prism.languages.atom = Prism.languages.xml, Prism.languages.rss = Prism.languages.xml;
!function (e) {
  var s = /("|')(?:\\(?:\r\n|[\s\S])|(?!\1)[^\\\r\n])*\1/;
  e.languages.css = {
    comment: /\/\*[\s\S]*?\*\//,
    atrule: {
      pattern: /@[\w-]+[\s\S]*?(?:;|(?=\s*\{))/,
      inside: {
        rule: /^@[\w-]+/,
        "selector-function-argument": {
          pattern: /(\bselector\s*\((?!\s*\))\s*)(?:[^()]|\((?:[^()]|\([^()]*\))*\))+?(?=\s*\))/,
          lookbehind: !0,
          alias: "selector"
        },
        keyword: {
          pattern: /(^|[^\w-])(?:and|not|only|or)(?![\w-])/,
          lookbehind: !0
        }
      }
    },
    url: {
      pattern: RegExp("\\burl\\((?:" + s.source + "|(?:[^\\\\\r\n()\"']|\\\\[^])*)\\)", "i"),
      greedy: !0,
      inside: {
        "function": /^url/i,
        punctuation: /^\(|\)$/,
        string: {
          pattern: RegExp("^" + s.source + "$"),
          alias: "url"
        }
      }
    },
    selector: RegExp("[^{}\\s](?:[^{};\"']|" + s.source + ")*?(?=\\s*\\{)"),
    string: {
      pattern: s,
      greedy: !0
    },
    property: /[-_a-z\xA0-\uFFFF][-\w\xA0-\uFFFF]*(?=\s*:)/i,
    important: /!important\b/i,
    "function": /[-a-z0-9]+(?=\()/i,
    punctuation: /[(){};:,]/
  }, e.languages.css.atrule.inside.rest = e.languages.css;
  var t = e.languages.markup;
  t && (t.tag.addInlined("style", "css"), e.languages.insertBefore("inside", "attr-value", {
    "style-attr": {
      pattern: /\s*style=("|')(?:\\[\s\S]|(?!\1)[^\\])*\1/i,
      inside: {
        "attr-name": {
          pattern: /^\s*style/i,
          inside: t.tag.inside
        },
        punctuation: /^\s*=\s*['"]|['"]\s*$/,
        "attr-value": {
          pattern: /.+/i,
          inside: e.languages.css
        }
      },
      alias: "language-css"
    }
  }, t.tag));
}(Prism);
Prism.languages.clike = {
  comment: [{
    pattern: /(^|[^\\])\/\*[\s\S]*?(?:\*\/|$)/,
    lookbehind: !0
  }, {
    pattern: /(^|[^\\:])\/\/.*/,
    lookbehind: !0,
    greedy: !0
  }],
  string: {
    pattern: /(["'])(?:\\(?:\r\n|[\s\S])|(?!\1)[^\\\r\n])*\1/,
    greedy: !0
  },
  "class-name": {
    pattern: /(\b(?:class|interface|extends|implements|trait|instanceof|new)\s+|\bcatch\s+\()[\w.\\]+/i,
    lookbehind: !0,
    inside: {
      punctuation: /[.\\]/
    }
  },
  keyword: /\b(?:if|else|while|do|for|return|in|instanceof|function|new|try|throw|catch|finally|null|break|continue)\b/,
  "boolean": /\b(?:true|false)\b/,
  "function": /\w+(?=\()/,
  number: /\b0x[\da-f]+\b|(?:\b\d+\.?\d*|\B\.\d+)(?:e[+-]?\d+)?/i,
  operator: /[<>]=?|[!=]=?=?|--?|\+\+?|&&?|\|\|?|[?*/~^%]/,
  punctuation: /[{}[\];(),.:]/
};
Prism.languages.javascript = Prism.languages.extend("clike", {
  "class-name": [Prism.languages.clike["class-name"], {
    pattern: /(^|[^$\w\xA0-\uFFFF])[_$A-Z\xA0-\uFFFF][$\w\xA0-\uFFFF]*(?=\.(?:prototype|constructor))/,
    lookbehind: !0
  }],
  keyword: [{
    pattern: /((?:^|})\s*)(?:catch|finally)\b/,
    lookbehind: !0
  }, {
    pattern: /(^|[^.]|\.\.\.\s*)\b(?:as|async(?=\s*(?:function\b|\(|[$\w\xA0-\uFFFF]|$))|await|break|case|class|const|continue|debugger|default|delete|do|else|enum|export|extends|for|from|function|(?:get|set)(?=\s*[\[$\w\xA0-\uFFFF])|if|implements|import|in|instanceof|interface|let|new|null|of|package|private|protected|public|return|static|super|switch|this|throw|try|typeof|undefined|var|void|while|with|yield)\b/,
    lookbehind: !0
  }],
  number: /\b(?:(?:0[xX](?:[\dA-Fa-f](?:_[\dA-Fa-f])?)+|0[bB](?:[01](?:_[01])?)+|0[oO](?:[0-7](?:_[0-7])?)+)n?|(?:\d(?:_\d)?)+n|NaN|Infinity)\b|(?:\b(?:\d(?:_\d)?)+\.?(?:\d(?:_\d)?)*|\B\.(?:\d(?:_\d)?)+)(?:[Ee][+-]?(?:\d(?:_\d)?)+)?/,
  "function": /#?[_$a-zA-Z\xA0-\uFFFF][$\w\xA0-\uFFFF]*(?=\s*(?:\.\s*(?:apply|bind|call)\s*)?\()/,
  operator: /--|\+\+|\*\*=?|=>|&&=?|\|\|=?|[!=]==|<<=?|>>>?=?|[-+*/%&|^!=<>]=?|\.{3}|\?\?=?|\?\.?|[~:]/
}), Prism.languages.javascript["class-name"][0].pattern = /(\b(?:class|interface|extends|implements|instanceof|new)\s+)[\w.\\]+/, Prism.languages.insertBefore("javascript", "keyword", {
  regex: {
    pattern: /((?:^|[^$\w\xA0-\uFFFF."'\])\s]|\b(?:return|yield))\s*)\/(?:\[(?:[^\]\\\r\n]|\\.)*]|\\.|[^/\\\[\r\n])+\/[gimyus]{0,6}(?=(?:\s|\/\*(?:[^*]|\*(?!\/))*\*\/)*(?:$|[\r\n,.;:})\]]|\/\/))/,
    lookbehind: !0,
    greedy: !0
  },
  "function-variable": {
    pattern: /#?[_$a-zA-Z\xA0-\uFFFF][$\w\xA0-\uFFFF]*(?=\s*[=:]\s*(?:async\s*)?(?:\bfunction\b|(?:\((?:[^()]|\([^()]*\))*\)|[_$a-zA-Z\xA0-\uFFFF][$\w\xA0-\uFFFF]*)\s*=>))/,
    alias: "function"
  },
  parameter: [{
    pattern: /(function(?:\s+[_$A-Za-z\xA0-\uFFFF][$\w\xA0-\uFFFF]*)?\s*\(\s*)(?!\s)(?:[^()]|\([^()]*\))+?(?=\s*\))/,
    lookbehind: !0,
    inside: Prism.languages.javascript
  }, {
    pattern: /[_$a-z\xA0-\uFFFF][$\w\xA0-\uFFFF]*(?=\s*=>)/i,
    inside: Prism.languages.javascript
  }, {
    pattern: /(\(\s*)(?!\s)(?:[^()]|\([^()]*\))+?(?=\s*\)\s*=>)/,
    lookbehind: !0,
    inside: Prism.languages.javascript
  }, {
    pattern: /((?:\b|\s|^)(?!(?:as|async|await|break|case|catch|class|const|continue|debugger|default|delete|do|else|enum|export|extends|finally|for|from|function|get|if|implements|import|in|instanceof|interface|let|new|null|of|package|private|protected|public|return|set|static|super|switch|this|throw|try|typeof|undefined|var|void|while|with|yield)(?![$\w\xA0-\uFFFF]))(?:[_$A-Za-z\xA0-\uFFFF][$\w\xA0-\uFFFF]*\s*)\(\s*|\]\s*\(\s*)(?!\s)(?:[^()]|\([^()]*\))+?(?=\s*\)\s*\{)/,
    lookbehind: !0,
    inside: Prism.languages.javascript
  }],
  constant: /\b[A-Z](?:[A-Z_]|\dx?)*\b/
}), Prism.languages.insertBefore("javascript", "string", {
  "template-string": {
    pattern: /`(?:\\[\s\S]|\${(?:[^{}]|{(?:[^{}]|{[^}]*})*})+}|(?!\${)[^\\`])*`/,
    greedy: !0,
    inside: {
      "template-punctuation": {
        pattern: /^`|`$/,
        alias: "string"
      },
      interpolation: {
        pattern: /((?:^|[^\\])(?:\\{2})*)\${(?:[^{}]|{(?:[^{}]|{[^}]*})*})+}/,
        lookbehind: !0,
        inside: {
          "interpolation-punctuation": {
            pattern: /^\${|}$/,
            alias: "punctuation"
          },
          rest: Prism.languages.javascript
        }
      },
      string: /[\s\S]+/
    }
  }
}), Prism.languages.markup && Prism.languages.markup.tag.addInlined("script", "javascript"), Prism.languages.js = Prism.languages.javascript;
!function (i) {
  i.languages.diff = {
    coord: [/^(?:\*{3}|-{3}|\+{3}).*$/m, /^@@.*@@$/m, /^\d+.*$/m]
  };
  var r = {
    "deleted-sign": "-",
    "deleted-arrow": "<",
    "inserted-sign": "+",
    "inserted-arrow": ">",
    unchanged: " ",
    diff: "!"
  };
  Object.keys(r).forEach(function (e) {
    var n = r[e],
        a = [];
    /^\w+$/.test(e) || a.push(/\w+/.exec(e)[0]), "diff" === e && a.push("bold"), i.languages.diff[e] = {
      pattern: RegExp("^(?:[" + n + "].*(?:\r\n?|\n|(?![\\s\\S])))+", "m"),
      alias: a,
      inside: {
        line: {
          pattern: /(.)(?=[\s\S]).*(?:\r\n?|\n)?/,
          lookbehind: !0
        },
        prefix: {
          pattern: /[\s\S]/,
          alias: /\w+/.exec(e)[0]
        }
      }
    };
  }), Object.defineProperty(i.languages.diff, "PREFIXES", {
    value: r
  });
}(Prism);
Prism.languages.json = {
  property: {
    pattern: /"(?:\\.|[^\\"\r\n])*"(?=\s*:)/,
    greedy: !0
  },
  string: {
    pattern: /"(?:\\.|[^\\"\r\n])*"(?!\s*:)/,
    greedy: !0
  },
  comment: {
    pattern: /\/\/.*|\/\*[\s\S]*?(?:\*\/|$)/,
    greedy: !0
  },
  number: /-?\b\d+(?:\.\d+)?(?:e[+-]?\d+)?\b/i,
  punctuation: /[{}[\],]/,
  operator: /:/,
  "boolean": /\b(?:true|false)\b/,
  "null": {
    pattern: /\bnull\b/,
    alias: "keyword"
  }
}, Prism.languages.webmanifest = Prism.languages.json;
!function (h) {
  function v(e, n) {
    return "___" + e.toUpperCase() + n + "___";
  }

  Object.defineProperties(h.languages["markup-templating"] = {}, {
    buildPlaceholders: {
      value: function value(a, r, e, o) {
        if (a.language === r) {
          var c = a.tokenStack = [];
          a.code = a.code.replace(e, function (e) {
            if ("function" == typeof o && !o(e)) return e;

            for (var n, t = c.length; -1 !== a.code.indexOf(n = v(r, t));) {
              ++t;
            }

            return c[t] = e, n;
          }), a.grammar = h.languages.markup;
        }
      }
    },
    tokenizePlaceholders: {
      value: function value(p, k) {
        if (p.language === k && p.tokenStack) {
          p.grammar = h.languages[k];
          var m = 0,
              d = Object.keys(p.tokenStack);
          !function e(n) {
            for (var t = 0; t < n.length && !(m >= d.length); t++) {
              var a = n[t];

              if ("string" == typeof a || a.content && "string" == typeof a.content) {
                var r = d[m],
                    o = p.tokenStack[r],
                    c = "string" == typeof a ? a : a.content,
                    i = v(k, r),
                    u = c.indexOf(i);

                if (-1 < u) {
                  ++m;
                  var g = c.substring(0, u),
                      l = new h.Token(k, h.tokenize(o, p.grammar), "language-" + k, o),
                      s = c.substring(u + i.length),
                      f = [];
                  g && f.push.apply(f, e([g])), f.push(l), s && f.push.apply(f, e([s])), "string" == typeof a ? n.splice.apply(n, [t, 1].concat(f)) : a.content = f;
                }
              } else a.content && e(a.content);
            }

            return n;
          }(p.tokens);
        }
      }
    }
  });
}(Prism);
!function (n) {
  n.languages.php = n.languages.extend("clike", {
    keyword: /\b(?:__halt_compiler|abstract|and|array|as|break|callable|case|catch|class|clone|const|continue|declare|default|die|do|echo|else|elseif|empty|enddeclare|endfor|endforeach|endif|endswitch|endwhile|eval|exit|extends|final|finally|for|foreach|function|global|goto|if|implements|include|include_once|instanceof|insteadof|interface|isset|list|namespace|new|or|parent|print|private|protected|public|require|require_once|return|static|switch|throw|trait|try|unset|use|var|while|xor|yield)\b/i,
    "boolean": {
      pattern: /\b(?:false|true)\b/i,
      alias: "constant"
    },
    constant: [/\b[A-Z_][A-Z0-9_]*\b/, /\b(?:null)\b/i],
    comment: {
      pattern: /(^|[^\\])(?:\/\*[\s\S]*?\*\/|\/\/.*)/,
      lookbehind: !0
    }
  }), n.languages.insertBefore("php", "string", {
    "shell-comment": {
      pattern: /(^|[^\\])#.*/,
      lookbehind: !0,
      alias: "comment"
    }
  }), n.languages.insertBefore("php", "comment", {
    delimiter: {
      pattern: /\?>$|^<\?(?:php(?=\s)|=)?/i,
      alias: "important"
    }
  }), n.languages.insertBefore("php", "keyword", {
    variable: /\$+(?:\w+\b|(?={))/i,
    "package": {
      pattern: /(\\|namespace\s+|use\s+)[\w\\]+/,
      lookbehind: !0,
      inside: {
        punctuation: /\\/
      }
    }
  }), n.languages.insertBefore("php", "operator", {
    property: {
      pattern: /(->)[\w]+/,
      lookbehind: !0
    }
  });
  var e = {
    pattern: /{\$(?:{(?:{[^{}]+}|[^{}]+)}|[^{}])+}|(^|[^\\{])\$+(?:\w+(?:\[[^\r\n\[\]]+\]|->\w+)*)/,
    lookbehind: !0,
    inside: n.languages.php
  };
  n.languages.insertBefore("php", "string", {
    "nowdoc-string": {
      pattern: /<<<'([^']+)'[\r\n](?:.*[\r\n])*?\1;/,
      greedy: !0,
      alias: "string",
      inside: {
        delimiter: {
          pattern: /^<<<'[^']+'|[a-z_]\w*;$/i,
          alias: "symbol",
          inside: {
            punctuation: /^<<<'?|[';]$/
          }
        }
      }
    },
    "heredoc-string": {
      pattern: /<<<(?:"([^"]+)"[\r\n](?:.*[\r\n])*?\1;|([a-z_]\w*)[\r\n](?:.*[\r\n])*?\2;)/i,
      greedy: !0,
      alias: "string",
      inside: {
        delimiter: {
          pattern: /^<<<(?:"[^"]+"|[a-z_]\w*)|[a-z_]\w*;$/i,
          alias: "symbol",
          inside: {
            punctuation: /^<<<"?|[";]$/
          }
        },
        interpolation: e
      }
    },
    "single-quoted-string": {
      pattern: /'(?:\\[\s\S]|[^\\'])*'/,
      greedy: !0,
      alias: "string"
    },
    "double-quoted-string": {
      pattern: /"(?:\\[\s\S]|[^\\"])*"/,
      greedy: !0,
      alias: "string",
      inside: {
        interpolation: e
      }
    }
  }), delete n.languages.php.string, n.hooks.add("before-tokenize", function (e) {
    if (/<\?/.test(e.code)) {
      n.languages["markup-templating"].buildPlaceholders(e, "php", /<\?(?:[^"'/#]|\/(?![*/])|("|')(?:\\[\s\S]|(?!\1)[^\\])*\1|(?:\/\/|#)(?:[^?\n\r]|\?(?!>))*(?=$|\?>|[\r\n])|\/\*[\s\S]*?(?:\*\/|$))*?(?:\?>|$)/gi);
    }
  }), n.hooks.add("after-tokenize", function (e) {
    n.languages["markup-templating"].tokenizePlaceholders(e, "php");
  });
}(Prism);
Prism.languages.insertBefore("php", "variable", {
  "this": /\$this\b/,
  global: /\$(?:_(?:SERVER|GET|POST|FILES|REQUEST|SESSION|ENV|COOKIE)|GLOBALS|HTTP_RAW_POST_DATA|argc|argv|php_errormsg|http_response_header)\b/,
  scope: {
    pattern: /\b[\w\\]+::/,
    inside: {
      keyword: /static|self|parent/,
      punctuation: /::|\\/
    }
  }
});
Prism.languages.sql = {
  comment: {
    pattern: /(^|[^\\])(?:\/\*[\s\S]*?\*\/|(?:--|\/\/|#).*)/,
    lookbehind: !0
  },
  variable: [{
    pattern: /@(["'`])(?:\\[\s\S]|(?!\1)[^\\])+\1/,
    greedy: !0
  }, /@[\w.$]+/],
  string: {
    pattern: /(^|[^@\\])("|')(?:\\[\s\S]|(?!\2)[^\\]|\2\2)*\2/,
    greedy: !0,
    lookbehind: !0
  },
  "function": /\b(?:AVG|COUNT|FIRST|FORMAT|LAST|LCASE|LEN|MAX|MID|MIN|MOD|NOW|ROUND|SUM|UCASE)(?=\s*\()/i,
  keyword: /\b(?:ACTION|ADD|AFTER|ALGORITHM|ALL|ALTER|ANALYZE|ANY|APPLY|AS|ASC|AUTHORIZATION|AUTO_INCREMENT|BACKUP|BDB|BEGIN|BERKELEYDB|BIGINT|BINARY|BIT|BLOB|BOOL|BOOLEAN|BREAK|BROWSE|BTREE|BULK|BY|CALL|CASCADED?|CASE|CHAIN|CHAR(?:ACTER|SET)?|CHECK(?:POINT)?|CLOSE|CLUSTERED|COALESCE|COLLATE|COLUMNS?|COMMENT|COMMIT(?:TED)?|COMPUTE|CONNECT|CONSISTENT|CONSTRAINT|CONTAINS(?:TABLE)?|CONTINUE|CONVERT|CREATE|CROSS|CURRENT(?:_DATE|_TIME|_TIMESTAMP|_USER)?|CURSOR|CYCLE|DATA(?:BASES?)?|DATE(?:TIME)?|DAY|DBCC|DEALLOCATE|DEC|DECIMAL|DECLARE|DEFAULT|DEFINER|DELAYED|DELETE|DELIMITERS?|DENY|DESC|DESCRIBE|DETERMINISTIC|DISABLE|DISCARD|DISK|DISTINCT|DISTINCTROW|DISTRIBUTED|DO|DOUBLE|DROP|DUMMY|DUMP(?:FILE)?|DUPLICATE|ELSE(?:IF)?|ENABLE|ENCLOSED|END|ENGINE|ENUM|ERRLVL|ERRORS|ESCAPED?|EXCEPT|EXEC(?:UTE)?|EXISTS|EXIT|EXPLAIN|EXTENDED|FETCH|FIELDS|FILE|FILLFACTOR|FIRST|FIXED|FLOAT|FOLLOWING|FOR(?: EACH ROW)?|FORCE|FOREIGN|FREETEXT(?:TABLE)?|FROM|FULL|FUNCTION|GEOMETRY(?:COLLECTION)?|GLOBAL|GOTO|GRANT|GROUP|HANDLER|HASH|HAVING|HOLDLOCK|HOUR|IDENTITY(?:_INSERT|COL)?|IF|IGNORE|IMPORT|INDEX|INFILE|INNER|INNODB|INOUT|INSERT|INT|INTEGER|INTERSECT|INTERVAL|INTO|INVOKER|ISOLATION|ITERATE|JOIN|KEYS?|KILL|LANGUAGE|LAST|LEAVE|LEFT|LEVEL|LIMIT|LINENO|LINES|LINESTRING|LOAD|LOCAL|LOCK|LONG(?:BLOB|TEXT)|LOOP|MATCH(?:ED)?|MEDIUM(?:BLOB|INT|TEXT)|MERGE|MIDDLEINT|MINUTE|MODE|MODIFIES|MODIFY|MONTH|MULTI(?:LINESTRING|POINT|POLYGON)|NATIONAL|NATURAL|NCHAR|NEXT|NO|NONCLUSTERED|NULLIF|NUMERIC|OFF?|OFFSETS?|ON|OPEN(?:DATASOURCE|QUERY|ROWSET)?|OPTIMIZE|OPTION(?:ALLY)?|ORDER|OUT(?:ER|FILE)?|OVER|PARTIAL|PARTITION|PERCENT|PIVOT|PLAN|POINT|POLYGON|PRECEDING|PRECISION|PREPARE|PREV|PRIMARY|PRINT|PRIVILEGES|PROC(?:EDURE)?|PUBLIC|PURGE|QUICK|RAISERROR|READS?|REAL|RECONFIGURE|REFERENCES|RELEASE|RENAME|REPEAT(?:ABLE)?|REPLACE|REPLICATION|REQUIRE|RESIGNAL|RESTORE|RESTRICT|RETURN(?:S|ING)?|REVOKE|RIGHT|ROLLBACK|ROUTINE|ROW(?:COUNT|GUIDCOL|S)?|RTREE|RULE|SAVE(?:POINT)?|SCHEMA|SECOND|SELECT|SERIAL(?:IZABLE)?|SESSION(?:_USER)?|SET(?:USER)?|SHARE|SHOW|SHUTDOWN|SIMPLE|SMALLINT|SNAPSHOT|SOME|SONAME|SQL|START(?:ING)?|STATISTICS|STATUS|STRIPED|SYSTEM_USER|TABLES?|TABLESPACE|TEMP(?:ORARY|TABLE)?|TERMINATED|TEXT(?:SIZE)?|THEN|TIME(?:STAMP)?|TINY(?:BLOB|INT|TEXT)|TOP?|TRAN(?:SACTIONS?)?|TRIGGER|TRUNCATE|TSEQUAL|TYPES?|UNBOUNDED|UNCOMMITTED|UNDEFINED|UNION|UNIQUE|UNLOCK|UNPIVOT|UNSIGNED|UPDATE(?:TEXT)?|USAGE|USE|USER|USING|VALUES?|VAR(?:BINARY|CHAR|CHARACTER|YING)|VIEW|WAITFOR|WARNINGS|WHEN|WHERE|WHILE|WITH(?: ROLLUP|IN)?|WORK|WRITE(?:TEXT)?|YEAR)\b/i,
  "boolean": /\b(?:TRUE|FALSE|NULL)\b/i,
  number: /\b0x[\da-f]+\b|\b\d+\.?\d*|\B\.\d+\b/i,
  operator: /[-+*\/=%^~]|&&?|\|\|?|!=?|<(?:=>?|<|>)?|>[>=]?|\b(?:AND|BETWEEN|IN|LIKE|NOT|OR|IS|DIV|REGEXP|RLIKE|SOUNDS LIKE|XOR)\b/i,
  punctuation: /[;[\]()`,.]/
};
!function (E) {
  var A = E.languages.plsql = E.languages.extend("sql", {
    comment: [/\/\*[\s\S]*?\*\//, /--.*/]
  }),
      T = A.keyword;
  Array.isArray(T) || (T = A.keyword = [T]), T.unshift(/\b(?:ACCESS|AGENT|AGGREGATE|ARRAY|ARROW|AT|ATTRIBUTE|AUDIT|AUTHID|BFILE_BASE|BLOB_BASE|BLOCK|BODY|BOTH|BOUND|BYTE|CALLING|CHAR_BASE|CHARSET(?:FORM|ID)|CLOB_BASE|COLAUTH|COLLECT|CLUSTERS?|COMPILED|COMPRESS|CONSTANT|CONSTRUCTOR|CONTEXT|CRASH|CUSTOMDATUM|DANGLING|DATE_BASE|DEFINE|DETERMINISTIC|DURATION|ELEMENT|EMPTY|EXCEPTIONS?|EXCLUSIVE|EXTERNAL|FINAL|FORALL|FORM|FOUND|GENERAL|HEAP|HIDDEN|IDENTIFIED|IMMEDIATE|INCLUDING|INCREMENT|INDICATOR|INDEXES|INDICES|INFINITE|INITIAL|ISOPEN|INSTANTIABLE|INTERFACE|INVALIDATE|JAVA|LARGE|LEADING|LENGTH|LIBRARY|LIKE[24C]|LIMITED|LONG|LOOP|MAP|MAXEXTENTS|MAXLEN|MEMBER|MINUS|MLSLABEL|MULTISET|NAME|NAN|NATIVE|NEW|NOAUDIT|NOCOMPRESS|NOCOPY|NOTFOUND|NOWAIT|NUMBER(?:_BASE)?|OBJECT|OCI(?:COLL|DATE|DATETIME|DURATION|INTERVAL|LOBLOCATOR|NUMBER|RAW|REF|REFCURSOR|ROWID|STRING|TYPE)|OFFLINE|ONLINE|ONLY|OPAQUE|OPERATOR|ORACLE|ORADATA|ORGANIZATION|ORL(?:ANY|VARY)|OTHERS|OVERLAPS|OVERRIDING|PACKAGE|PARALLEL_ENABLE|PARAMETERS?|PASCAL|PCTFREE|PIPE(?:LINED)?|PRAGMA|PRIOR|PRIVATE|RAISE|RANGE|RAW|RECORD|REF|REFERENCE|REM|REMAINDER|RESULT|RESOURCE|RETURNING|REVERSE|ROW(?:ID|NUM|TYPE)|SAMPLE|SB[124]|SEGMENT|SELF|SEPARATE|SEQUENCE|SHORT|SIZE(?:_T)?|SPARSE|SQL(?:CODE|DATA|NAME|STATE)|STANDARD|STATIC|STDDEV|STORED|STRING|STRUCT|STYLE|SUBMULTISET|SUBPARTITION|SUBSTITUTABLE|SUBTYPE|SUCCESSFUL|SYNONYM|SYSDATE|TABAUTH|TDO|THE|TIMEZONE_(?:ABBR|HOUR|MINUTE|REGION)|TRAILING|TRANSAC(?:TIONAL)?|TRUSTED|UB[124]|UID|UNDER|UNTRUSTED|VALIDATE|VALIST|VARCHAR2|VARIABLE|VARIANCE|VARRAY|VIEWS|VOID|WHENEVER|WRAPPED|ZONE)\b/i);
  var R = A.operator;
  Array.isArray(R) || (R = A.operator = [R]), R.unshift(/:=/);
}(Prism);
!function (n) {
  function a(a, e) {
    n.languages[a] && n.languages.insertBefore(a, "comment", {
      "doc-comment": e
    });
  }

  var e = n.languages.markup.tag,
      t = {
    pattern: /\/\/\/.*/,
    greedy: !0,
    alias: "comment",
    inside: {
      tag: e
    }
  },
      g = {
    pattern: /'''.*/,
    greedy: !0,
    alias: "comment",
    inside: {
      tag: e
    }
  };
  a("csharp", t), a("fsharp", t), a("vbnet", g);
}(Prism);
!function (n) {
  var t = /[*&][^\s[\]{},]+/,
      e = /!(?:<[\w\-%#;/?:@&=+$,.!~*'()[\]]+>|(?:[a-zA-Z\d-]*!)?[\w\-%#;/?:@&=+$.~*'()]+)?/,
      r = "(?:" + e.source + "(?:[ \t]+" + t.source + ")?|" + t.source + "(?:[ \t]+" + e.source + ")?)";

  function a(n, t) {
    t = (t || "").replace(/m/g, "") + "m";
    var e = "([:\\-,[{]\\s*(?:\\s<<prop>>[ \t]+)?)(?:<<value>>)(?=[ \t]*(?:$|,|]|}|\\s*#))".replace(/<<prop>>/g, function () {
      return r;
    }).replace(/<<value>>/g, function () {
      return n;
    });
    return RegExp(e, t);
  }

  n.languages.yaml = {
    scalar: {
      pattern: RegExp("([\\-:]\\s*(?:\\s<<prop>>[ \t]+)?[|>])[ \t]*(?:((?:\r?\n|\r)[ \t]+)[^\r\n]+(?:\\2[^\r\n]+)*)".replace(/<<prop>>/g, function () {
        return r;
      })),
      lookbehind: !0,
      alias: "string"
    },
    comment: /#.*/,
    key: {
      pattern: RegExp("((?:^|[:\\-,[{\r\n?])[ \t]*(?:<<prop>>[ \t]+)?)[^\r\n{[\\]},#\\s]+?(?=\\s*:\\s)".replace(/<<prop>>/g, function () {
        return r;
      })),
      lookbehind: !0,
      alias: "atrule"
    },
    directive: {
      pattern: /(^[ \t]*)%.+/m,
      lookbehind: !0,
      alias: "important"
    },
    datetime: {
      pattern: a("\\d{4}-\\d\\d?-\\d\\d?(?:[tT]|[ \t]+)\\d\\d?:\\d{2}:\\d{2}(?:\\.\\d*)?[ \t]*(?:Z|[-+]\\d\\d?(?::\\d{2})?)?|\\d{4}-\\d{2}-\\d{2}|\\d\\d?:\\d{2}(?::\\d{2}(?:\\.\\d*)?)?"),
      lookbehind: !0,
      alias: "number"
    },
    "boolean": {
      pattern: a("true|false", "i"),
      lookbehind: !0,
      alias: "important"
    },
    "null": {
      pattern: a("null|~", "i"),
      lookbehind: !0,
      alias: "important"
    },
    string: {
      pattern: a("(\"|')(?:(?!\\2)[^\\\\\r\n]|\\\\.)*\\2"),
      lookbehind: !0,
      greedy: !0
    },
    number: {
      pattern: a("[+-]?(?:0x[\\da-f]+|0o[0-7]+|(?:\\d+\\.?\\d*|\\.?\\d+)(?:e[+-]?\\d+)?|\\.inf|\\.nan)", "i"),
      lookbehind: !0
    },
    tag: e,
    important: t,
    punctuation: /---|[:[\]{}\-,|>?]|\.\.\./
  }, n.languages.yml = n.languages.yaml;
}(Prism);
!function () {
  if ("undefined" != typeof self && self.Prism && self.document && document.querySelector) {
    var t,
        s = function s() {
      if (void 0 === t) {
        var e = document.createElement("div");
        e.style.fontSize = "13px", e.style.lineHeight = "1.5", e.style.padding = "0", e.style.border = "0", e.innerHTML = "&nbsp;<br />&nbsp;", document.body.appendChild(e), t = 38 === e.offsetHeight, document.body.removeChild(e);
      }

      return t;
    },
        l = !0,
        a = 0;

    Prism.hooks.add("before-sanity-check", function (e) {
      var t = e.element.parentNode,
          n = t && t.getAttribute("data-line");

      if (t && n && /pre/i.test(t.nodeName)) {
        var i = 0;
        g(".line-highlight", t).forEach(function (e) {
          i += e.textContent.length, e.parentNode.removeChild(e);
        }), i && /^( \n)+$/.test(e.code.slice(-i)) && (e.code = e.code.slice(0, -i));
      }
    }), Prism.hooks.add("complete", function e(t) {
      var n = t.element.parentNode,
          i = n && n.getAttribute("data-line");

      if (n && i && /pre/i.test(n.nodeName)) {
        clearTimeout(a);
        var r = Prism.plugins.lineNumbers,
            o = t.plugins && t.plugins.lineNumbers;
        if (b(n, "line-numbers") && r && !o) Prism.hooks.add("line-numbers", e);else u(n, i)(), a = setTimeout(c, 1);
      }
    }), window.addEventListener("hashchange", c), window.addEventListener("resize", function () {
      g("pre[data-line]").map(function (e) {
        return u(e);
      }).forEach(v);
    });
  }

  function g(e, t) {
    return Array.prototype.slice.call((t || document).querySelectorAll(e));
  }

  function b(e, t) {
    return t = " " + t + " ", -1 < (" " + e.className + " ").replace(/[\n\t]/g, " ").indexOf(t);
  }

  function v(e) {
    e();
  }

  function u(u, e, c) {
    var t = (e = "string" == typeof e ? e : u.getAttribute("data-line")).replace(/\s+/g, "").split(",").filter(Boolean),
        d = +u.getAttribute("data-line-offset") || 0,
        f = (s() ? parseInt : parseFloat)(getComputedStyle(u).lineHeight),
        m = b(u, "line-numbers"),
        p = m ? u : u.querySelector("code") || u,
        h = [];
    t.forEach(function (e) {
      var t = e.split("-"),
          n = +t[0],
          i = +t[1] || n,
          r = u.querySelector('.line-highlight[data-range="' + e + '"]') || document.createElement("div");

      if (h.push(function () {
        r.setAttribute("aria-hidden", "true"), r.setAttribute("data-range", e), r.className = (c || "") + " line-highlight";
      }), m && Prism.plugins.lineNumbers) {
        var o = Prism.plugins.lineNumbers.getLine(u, n),
            a = Prism.plugins.lineNumbers.getLine(u, i);

        if (o) {
          var s = o.offsetTop + "px";
          h.push(function () {
            r.style.top = s;
          });
        }

        if (a) {
          var l = a.offsetTop - o.offsetTop + a.offsetHeight + "px";
          h.push(function () {
            r.style.height = l;
          });
        }
      } else h.push(function () {
        r.setAttribute("data-start", n), n < i && r.setAttribute("data-end", i), r.style.top = (n - d - 1) * f + "px", r.textContent = new Array(i - n + 2).join(" \n");
      });

      h.push(function () {
        p.appendChild(r);
      });
    });
    var i = u.id;

    if (m && i) {
      for (var n = "linkable-line-numbers", r = !1, o = u; o;) {
        if (b(o, n)) {
          r = !0;
          break;
        }

        o = o.parentElement;
      }

      if (r) {
        b(u, n) || h.push(function () {
          u.className = (u.className + " " + n).trim();
        });
        var a = parseInt(u.getAttribute("data-start") || "1");
        g(".line-numbers-rows > span", u).forEach(function (e, t) {
          var n = t + a;

          e.onclick = function () {
            var e = i + "." + n;
            l = !1, location.hash = e, setTimeout(function () {
              l = !0;
            }, 1);
          };
        });
      }
    }

    return function () {
      h.forEach(v);
    };
  }

  function c() {
    var e = location.hash.slice(1);
    g(".temporary.line-highlight").forEach(function (e) {
      e.parentNode.removeChild(e);
    });
    var t = (e.match(/\.([\d,-]+)$/) || [, ""])[1];

    if (t && !document.getElementById(e)) {
      var n = e.slice(0, e.lastIndexOf(".")),
          i = document.getElementById(n);
      if (i) i.hasAttribute("data-line") || i.setAttribute("data-line", ""), u(i, t, "temporary ")(), l && document.querySelector(".temporary.line-highlight").scrollIntoView();
    }
  }
}();
!function () {
  var i = Object.assign || function (e, n) {
    for (var t in n) {
      n.hasOwnProperty(t) && (e[t] = n[t]);
    }

    return e;
  };

  function e(e) {
    this.defaults = i({}, e);
  }

  function s(e) {
    for (var n = 0, t = 0; t < e.length; ++t) {
      e.charCodeAt(t) == "\t".charCodeAt(0) && (n += 3);
    }

    return e.length + n;
  }

  e.prototype = {
    setDefaults: function setDefaults(e) {
      this.defaults = i(this.defaults, e);
    },
    normalize: function normalize(e, n) {
      for (var t in n = i(this.defaults, n)) {
        var r = t.replace(/-(\w)/g, function (e, n) {
          return n.toUpperCase();
        });
        "normalize" !== t && "setDefaults" !== r && n[t] && this[r] && (e = this[r].call(this, e, n[t]));
      }

      return e;
    },
    leftTrim: function leftTrim(e) {
      return e.replace(/^\s+/, "");
    },
    rightTrim: function rightTrim(e) {
      return e.replace(/\s+$/, "");
    },
    tabsToSpaces: function tabsToSpaces(e, n) {
      return n = 0 | n || 4, e.replace(/\t/g, new Array(++n).join(" "));
    },
    spacesToTabs: function spacesToTabs(e, n) {
      return n = 0 | n || 4, e.replace(RegExp(" {" + n + "}", "g"), "\t");
    },
    removeTrailing: function removeTrailing(e) {
      return e.replace(/\s*?$/gm, "");
    },
    removeInitialLineFeed: function removeInitialLineFeed(e) {
      return e.replace(/^(?:\r?\n|\r)/, "");
    },
    removeIndent: function removeIndent(e) {
      var n = e.match(/^[^\S\n\r]*(?=\S)/gm);
      return n && n[0].length ? (n.sort(function (e, n) {
        return e.length - n.length;
      }), n[0].length ? e.replace(RegExp("^" + n[0], "gm"), "") : e) : e;
    },
    indent: function indent(e, n) {
      return e.replace(/^[^\S\n\r]*(?=\S)/gm, new Array(++n).join("\t") + "$&");
    },
    breakLines: function breakLines(e, n) {
      n = !0 === n ? 80 : 0 | n || 80;

      for (var t = e.split("\n"), r = 0; r < t.length; ++r) {
        if (!(s(t[r]) <= n)) {
          for (var i = t[r].split(/(\s+)/g), o = 0, a = 0; a < i.length; ++a) {
            var l = s(i[a]);
            n < (o += l) && (i[a] = "\n" + i[a], o = l);
          }

          t[r] = i.join("");
        }
      }

      return t.join("\n");
    }
  }, "undefined" != typeof module && module.exports && (module.exports = e), "undefined" != typeof Prism && (Prism.plugins.NormalizeWhitespace = new e({
    "remove-trailing": !0,
    "remove-indent": !0,
    "left-trim": !0,
    "right-trim": !0
  }), Prism.hooks.add("before-sanity-check", function (e) {
    var n = Prism.plugins.NormalizeWhitespace;
    if ((!e.settings || !1 !== e.settings["whitespace-normalization"]) && Prism.util.isActive(e.element, "whitespace-normalization", !0)) if (e.element && e.element.parentNode || !e.code) {
      var t = e.element.parentNode;

      if (e.code && t && "pre" === t.nodeName.toLowerCase()) {
        for (var r = t.childNodes, i = "", o = "", a = !1, l = 0; l < r.length; ++l) {
          var s = r[l];
          s == e.element ? a = !0 : "#text" === s.nodeName && (a ? o += s.nodeValue : i += s.nodeValue, t.removeChild(s), --l);
        }

        if (e.element.children.length && Prism.plugins.KeepMarkup) {
          var c = i + e.element.innerHTML + o;
          e.element.innerHTML = n.normalize(c, e.settings), e.code = e.element.textContent;
        } else e.code = i + e.code + o, e.code = n.normalize(e.code, e.settings);
      }
    } else e.code = n.normalize(e.code, e.settings);
  }));
}();
!function () {
  if ("undefined" != typeof self && self.Prism && self.document) {
    var i = [],
        l = {},
        c = function c() {};

    Prism.plugins.toolbar = {};

    var e = Prism.plugins.toolbar.registerButton = function (e, n) {
      var t;
      t = "function" == typeof n ? n : function (e) {
        var t;
        return "function" == typeof n.onClick ? ((t = document.createElement("button")).type = "button", t.addEventListener("click", function () {
          n.onClick.call(this, e);
        })) : "string" == typeof n.url ? (t = document.createElement("a")).href = n.url : t = document.createElement("span"), n.className && t.classList.add(n.className), t.textContent = n.text, t;
      }, e in l ? console.warn('There is a button with the key "' + e + '" registered already.') : i.push(l[e] = t);
    },
        t = Prism.plugins.toolbar.hook = function (a) {
      var e = a.element.parentNode;

      if (e && /pre/i.test(e.nodeName) && !e.parentNode.classList.contains("code-toolbar")) {
        var t = document.createElement("div");
        t.classList.add("code-toolbar"), e.parentNode.insertBefore(t, e), t.appendChild(e);
        var r = document.createElement("div");
        r.classList.add("toolbar");

        var n = i,
            o = function (e) {
          for (; e;) {
            var t = e.getAttribute("data-toolbar-order");
            if (null != t) return (t = t.trim()).length ? t.split(/\s*,\s*/g) : [];
            e = e.parentElement;
          }
        }(a.element);

        o && (n = o.map(function (e) {
          return l[e] || c;
        })), n.forEach(function (e) {
          var t = e(a);

          if (t) {
            var n = document.createElement("div");
            n.classList.add("toolbar-item"), n.appendChild(t), r.appendChild(n);
          }
        }), t.appendChild(r);
      }
    };

    e("label", function (e) {
      var t = e.element.parentNode;

      if (t && /pre/i.test(t.nodeName) && t.hasAttribute("data-label")) {
        var n,
            a,
            r = t.getAttribute("data-label");

        try {
          a = document.querySelector("template#" + r);
        } catch (e) {}

        return a ? n = a.content : (t.hasAttribute("data-url") ? (n = document.createElement("a")).href = t.getAttribute("data-url") : n = document.createElement("span"), n.textContent = r), n;
      }
    }), Prism.hooks.add("complete", t);
  }
}();
!function () {
  if ("undefined" != typeof self && self.Prism && self.document) if (Prism.plugins.toolbar) {
    var i = window.ClipboardJS || void 0;
    i || "function" != typeof require || (i = require("clipboard"));
    var c = [];

    if (!i) {
      var o = document.createElement("script"),
          t = document.querySelector("head");
      o.onload = function () {
        if (i = window.ClipboardJS) for (; c.length;) {
          c.pop()();
        }
      }, o.src = "https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.0/clipboard.min.js", t.appendChild(o);
    }

    Prism.plugins.toolbar.registerButton("copy-to-clipboard", function (o) {
      var t = document.createElement("button");
      t.textContent = "Copy";
      var e = o.element;
      return i ? n() : c.push(n), t;

      function n() {
        var o = new i(t, {
          text: function text() {
            return e.textContent;
          }
        });
        o.on("success", function () {
          t.textContent = "Copied!", r();
        }), o.on("error", function () {
          t.textContent = "Press Ctrl+C to copy", r();
        });
      }

      function r() {
        setTimeout(function () {
          t.textContent = "Copy";
        }, 5e3);
      }
    });
  } else console.warn("Copy to Clipboard plugin loaded before Toolbar plugin.");
}();
!function () {
  if ("undefined" != typeof Prism && Prism.languages.diff) {
    var o = /diff-([\w-]+)/i,
        m = /<\/?(?!\d)[^\s>\/=$<%]+(?:\s(?:\s*[^\s>\/=]+(?:\s*=\s*(?:"[^"]*"|'[^']*'|[^\s'">=]+(?=[\s>]))|(?=[\s/>])))+)?\s*\/?>/gi,
        c = RegExp("(?:__|[^\r\n<])*(?:\r\n?|\n|(?:__|[^\r\n<])(?![^\r\n]))".replace(/__/g, function () {
      return m.source;
    }), "gi"),
        d = Prism.languages.diff.PREFIXES;
    Prism.hooks.add("before-sanity-check", function (e) {
      var a = e.language;
      o.test(a) && !e.grammar && (e.grammar = Prism.languages[a] = Prism.languages.diff);
    }), Prism.hooks.add("before-tokenize", function (e) {
      var a = e.language;
      o.test(a) && !Prism.languages[a] && (Prism.languages[a] = Prism.languages.diff);
    }), Prism.hooks.add("wrap", function (e) {
      var a, s;

      if ("diff" !== e.language) {
        var n = o.exec(e.language);
        if (!n) return;
        a = n[1], s = Prism.languages[a];
      }

      if (e.type in d) {
        var r,
            i = e.content.replace(m, "").replace(/&lt;/g, "<").replace(/&amp;/g, "&"),
            g = i.replace(/(^|[\r\n])./g, "$1");
        r = s ? Prism.highlight(g, s, a) : Prism.util.encode(g);
        var f,
            t = new Prism.Token("prefix", d[e.type], [/\w+/.exec(e.type)[0]]),
            u = Prism.Token.stringify(t, e.language),
            l = [];

        for (c.lastIndex = 0; f = c.exec(r);) {
          l.push(u + f[0]);
        }

        /(?:^|[\r\n]).$/.test(i) && l.push(u), e.content = l.join(""), s && e.classes.push("language-" + a);
      }
    });
  }
}();
