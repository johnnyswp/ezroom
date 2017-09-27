/* Set the defaults for DataTables initialisation */
$.extend(true, $.fn.dataTable.defaults, {
  "sDom": "<'row'<'col-lg-6'f><'col-lg-6 text-right'l>r>t<'row'<'col-lg-6 align-lg-left align-xs-center'i><'col-lg-6  align-lg-right align-xs-center 'p>>",
  "sPaginationType": "bs_normal",
   "sScrollX": "auto",
  "oLanguage": {
    "sLengthMenu": "records per page _MENU_ ",
     "sSearch":"Arama..."
  },
  "fnInitComplete": function (oSettings, json) {
    var currentId = $(this).attr('id');
    if (currentId) {
 
      var thisLength = $('#' + currentId + '_length');
      var thisLengthLabel = $('#' + currentId + '_length label');
      var thisLengthSelect = $('#' + currentId + '_length label select');
 
      var thisFilter = $('#' + currentId + '_filter');
      var thisFilterLabel = $('#' + currentId + '_filter label');
      var thisFilterInput = $('#' + currentId + '_filter label input');



      // Re-arrange the records selection for a form-horizontal layout
      thisLength.addClass('form-group row');
     // thisLengthLabel.addClass('control-label col-xs-12 col-sm-7 col-md-6').attr('for', currentId + '_length_select').css('text-align', 'left');
      thisLengthSelect.addClass('form-control selectpicker show-menu-arrow show-tick').attr({'id':currentId + '_length_select','data-width':"100px",'data-style':"btn-default"});
      thisLengthSelect.prependTo(thisLength).wrap('<div class="col-md-6" />');
	 if(jQuery().selectpicker) {
    			thisLengthSelect.selectpicker();
	 }
	 thisLengthLabel.remove();
	 
      // Re-arrange the search input for a form-horizontal layout
      thisFilter.addClass('form-group row');
    //  thisFilterLabel.addClass('control-label col-xs-4 col-sm-3 col-md-3').attr('for', currentId + '_filter_input');   
      thisFilterInput.addClass('form-control').attr({ 'id' : currentId + '_filter_input' ,'placeholder':oSettings.oLanguage.sSearch });
	 var icongroup=$(' <div class="input-icon"><i class="ico fa fa-search"></i></div>').append(thisFilterInput);
      icongroup.appendTo(thisFilter).wrap('<div class="col-md-12 " />');
	 thisFilterLabel.remove();
    }
  }
});
 
$.extend($.fn.dataTableExt.oStdClasses, {
  "sWrapper": "dataTables_wrapper form-inline",
  
});
 
/* API method to get paging information */
$.fn.dataTableExt.oApi.fnPagingInfo = function (oSettings) {
  return {
    "iStart": oSettings._iDisplayStart,
    "iEnd": oSettings.fnDisplayEnd(),
    "iLength": oSettings._iDisplayLength,
    "iTotal": oSettings.fnRecordsTotal(),
    "iFilteredTotal": oSettings.fnRecordsDisplay(),
    "iPage": oSettings._iDisplayLength === -1 ? 0 : Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
    "iTotalPages": oSettings._iDisplayLength === -1 ? 0 : Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
  };
};
 
 
/* Bootstrap style pagination control */
$.extend( $.fn.dataTableExt.oPagination, {
	"bs_normal": {
		"fnInit": function( oSettings, nPaging, fnDraw ) {
			var oLang = oSettings.oLanguage.oPaginate;
			var fnClickHandler = function ( e ) {
				e.preventDefault();
				if ( oSettings.oApi._fnPageChange(oSettings, e.data.action) ) {
					fnDraw( oSettings );
				}
			};
			$(nPaging).append(
				'<ul class="pagination">'+
					'<li class="prev disabled"><a href="javascript:void(0)"><span class="glyphicon glyphicon-chevron-left"></span></a></li>'+
					'<li class="next disabled"><a href="javascript:void(0)"><span class="glyphicon glyphicon-chevron-right"></span></a></li>'+
				'</ul>'
			);
			var els = $('a', nPaging);
			$(els[0]).bind( 'click.DT', { action: "previous" }, fnClickHandler );
			$(els[1]).bind( 'click.DT', { action: "next" }, fnClickHandler );
		},
		"fnUpdate": function ( oSettings, fnDraw ) {
			var iListLength = 5;
			var oPaging = oSettings.oInstance.fnPagingInfo();
			var an = oSettings.aanFeatures.p;
			var i, ien, j, sClass, iStart, iEnd, iHalf=Math.floor(iListLength/2);
			if ( oPaging.iTotalPages < iListLength) {
				iStart = 1;
				iEnd = oPaging.iTotalPages;
			}
			else if ( oPaging.iPage <= iHalf ) {
				iStart = 1;
				iEnd = iListLength;
			} else if ( oPaging.iPage >= (oPaging.iTotalPages-iHalf) ) {
				iStart = oPaging.iTotalPages - iListLength + 1;
				iEnd = oPaging.iTotalPages;
			} else {
				iStart = oPaging.iPage - iHalf + 1;
				iEnd = iStart + iListLength - 1;
			}
			for ( i=0, ien=an.length ; i<ien ; i++ ) {
				$('li:gt(0)', an[i]).filter(':not(:last)').remove();
				for ( j=iStart ; j<=iEnd ; j++ ) {
					sClass = (j==oPaging.iPage+1) ? 'class="active"' : '';
					$('<li '+sClass+'><a href="javascript:void(0)">'+j+'</a></li>')
						.insertBefore( $('li:last', an[i])[0] )
						.bind('click', function (e) {
							e.preventDefault();
							oSettings._iDisplayStart = (parseInt($('a', this).text(),10)-1) * oPaging.iLength;
							fnDraw( oSettings );
						} );
				}
				if ( oPaging.iPage === 0 ) {
					$('li:first', an[i]).addClass('disabled');
				} else {
					$('li:first', an[i]).removeClass('disabled');
				}

				if ( oPaging.iPage === oPaging.iTotalPages-1 || oPaging.iTotalPages === 0 ) {
					$('li:last', an[i]).addClass('disabled');
				} else {
					$('li:last', an[i]).removeClass('disabled');
				}
			}
		}
	},	
	"bs_two_button": {
		"fnInit": function ( oSettings, nPaging, fnCallbackDraw )
		{
			var oLang = oSettings.oLanguage.oPaginate;
			var oClasses = oSettings.oClasses;
			var fnClickHandler = function ( e ) {
				if ( oSettings.oApi._fnPageChange( oSettings, e.data.action ) )
				{
					fnCallbackDraw( oSettings );
				}
			};
			var sAppend = '<ul class="pagination">'+
				'<li class="prev"><a href="javascript:void(0)" class="'+oSettings.oClasses.sPagePrevDisabled+'" tabindex="'+oSettings.iTabIndex+'" role="button"><span class="glyphicon glyphicon-chevron-left"></span>&nbsp;'+oLang.sPrevious+'</a></li>'+
				'<li class="next"><a href="javascript:void(0)" class="'+oSettings.oClasses.sPageNextDisabled+'" tabindex="'+oSettings.iTabIndex+'" role="button">'+oLang.sNext+'&nbsp;<span class="glyphicon glyphicon-chevron-right"></span></a></li>'+
				'</ul>';
			$(nPaging).append( sAppend );
			var els = $('a', nPaging);
			var nPrevious = els[0],
				nNext = els[1];
			oSettings.oApi._fnBindAction( nPrevious, {action: "previous"}, fnClickHandler );
			oSettings.oApi._fnBindAction( nNext,     {action: "next"},     fnClickHandler );
			if ( !oSettings.aanFeatures.p )
			{
				nPaging.id = oSettings.sTableId+'_paginate';
				nPrevious.id = oSettings.sTableId+'_previous';
				nNext.id = oSettings.sTableId+'_next';
				nPrevious.setAttribute('aria-controls', oSettings.sTableId);
				nNext.setAttribute('aria-controls', oSettings.sTableId);
			}
		},
		"fnUpdate": function ( oSettings, fnCallbackDraw )
		{
			if ( !oSettings.aanFeatures.p )
			{
				return;
			}
			var oPaging = oSettings.oInstance.fnPagingInfo();
			var oClasses = oSettings.oClasses;
			var an = oSettings.aanFeatures.p;
			var nNode;
			for ( var i=0, iLen=an.length ; i<iLen ; i++ )
			{
				if ( oPaging.iPage === 0 ) {
					$('li:first', an[i]).addClass('disabled');
				} else {
					$('li:first', an[i]).removeClass('disabled');
				}

				if ( oPaging.iPage === oPaging.iTotalPages-1 || oPaging.iTotalPages === 0 ) {
					$('li:last', an[i]).addClass('disabled');
				} else {
					$('li:last', an[i]).removeClass('disabled');
				}
			}
		}
	},
	"bs_four_button": {
		"fnInit": function ( oSettings, nPaging, fnCallbackDraw )
			{
				var oLang = oSettings.oLanguage.oPaginate;
				var oClasses = oSettings.oClasses;
				var fnClickHandler = function ( e ) {
					if ( oSettings.oApi._fnPageChange( oSettings, e.data.action ) )
					{
						fnCallbackDraw( oSettings );
					}
				};
				$(nPaging).append(
					'<ul class="pagination">'+
					'<li class="disabled"><a href="javascript:void(0)" tabindex="'+oSettings.iTabIndex+'" class="'+oClasses.sPageButton+" "+oClasses.sPageFirst+'"><span class="glyphicon glyphicon-backward"></span>&nbsp;'+oLang.sFirst+'</a></li>'+
					'<li class="disabled"><a href="javascript:void(0)" tabindex="'+oSettings.iTabIndex+'" class="'+oClasses.sPageButton+" "+oClasses.sPagePrevious+'"><span class="glyphicon glyphicon-chevron-left"></span>&nbsp;'+oLang.sPrevious+'</a></li>'+
					'<li><a href="javascript:void(0)" tabindex="'+oSettings.iTabIndex+'" class="'+oClasses.sPageButton+" "+oClasses.sPageNext+'">'+oLang.sNext+'&nbsp;<span class="glyphicon glyphicon-chevron-right"></span></a></li>'+
					'<li><a href="javascript:void(0)" tabindex="'+oSettings.iTabIndex+'" class="'+oClasses.sPageButton+" "+oClasses.sPageLast+'">'+oLang.sLast+'&nbsp;<span class="glyphicon glyphicon-forward"></span></a></li>'+
					'</ul>'
				);
				var els = $('a', nPaging);
				var nFirst = els[0],
					nPrev = els[1],
					nNext = els[2],
					nLast = els[3];
				oSettings.oApi._fnBindAction( nFirst, {action: "first"},    fnClickHandler );
				oSettings.oApi._fnBindAction( nPrev,  {action: "previous"}, fnClickHandler );
				oSettings.oApi._fnBindAction( nNext,  {action: "next"},     fnClickHandler );
				oSettings.oApi._fnBindAction( nLast,  {action: "last"},     fnClickHandler );
				if ( !oSettings.aanFeatures.p )
				{
					nPaging.id = oSettings.sTableId+'_paginate';
					nFirst.id =oSettings.sTableId+'_first';
					nPrev.id =oSettings.sTableId+'_previous';
					nNext.id =oSettings.sTableId+'_next';
					nLast.id =oSettings.sTableId+'_last';
				}
			},
		"fnUpdate": function ( oSettings, fnCallbackDraw )
			{
				if ( !oSettings.aanFeatures.p )
				{
					return;
				}
				var oPaging = oSettings.oInstance.fnPagingInfo();
				var oClasses = oSettings.oClasses;
				var an = oSettings.aanFeatures.p;
				var nNode;
				for ( var i=0, iLen=an.length ; i<iLen ; i++ )
				{
					if ( oPaging.iPage === 0 ) {
						$('li:eq(0)', an[i]).addClass('disabled');
						$('li:eq(1)', an[i]).addClass('disabled');
					} else {
						$('li:eq(0)', an[i]).removeClass('disabled');
						$('li:eq(1)', an[i]).removeClass('disabled');
					}

					if ( oPaging.iPage === oPaging.iTotalPages-1 || oPaging.iTotalPages === 0 ) {
						$('li:eq(2)', an[i]).addClass('disabled');
						$('li:eq(3)', an[i]).addClass('disabled');
					} else {
						$('li:eq(2)', an[i]).removeClass('disabled');
						$('li:eq(3)', an[i]).removeClass('disabled');
					}
				}
			}
	},
	"bs_full": {
		"fnInit": function ( oSettings, nPaging, fnCallbackDraw )
			{
				var oLang = oSettings.oLanguage.oPaginate;
				var oClasses = oSettings.oClasses;
				var fnClickHandler = function ( e ) {
					if ( oSettings.oApi._fnPageChange( oSettings, e.data.action ) )
					{
						fnCallbackDraw( oSettings );
					}
				};
				$(nPaging).append(
					'<ul class="pagination">'+
					'<li class="disabled"><a href="javascript:void(0)" tabindex="'+oSettings.iTabIndex+'" class="'+oClasses.sPageButton+" "+oClasses.sPageFirst+'">'+oLang.sFirst+'</a></li>'+
					'<li class="disabled"><a href="javascript:void(0)" tabindex="'+oSettings.iTabIndex+'" class="'+oClasses.sPageButton+" "+oClasses.sPagePrevious+'">'+oLang.sPrevious+'</a></li>'+
					'<li><a href="javascript:void(0)" tabindex="'+oSettings.iTabIndex+'" class="'+oClasses.sPageButton+" "+oClasses.sPageNext+'">'+oLang.sNext+'</a></li>'+
					'<li><a href="javascript:void(0)" tabindex="'+oSettings.iTabIndex+'" class="'+oClasses.sPageButton+" "+oClasses.sPageLast+'">'+oLang.sLast+'</a></li>'+
					'</ul>'
				);
				var els = $('a', nPaging);
				var nFirst = els[0],
					nPrev = els[1],
					nNext = els[2],
					nLast = els[3];
				oSettings.oApi._fnBindAction( nFirst, {action: "first"},    fnClickHandler );
				oSettings.oApi._fnBindAction( nPrev,  {action: "previous"}, fnClickHandler );
				oSettings.oApi._fnBindAction( nNext,  {action: "next"},     fnClickHandler );
				oSettings.oApi._fnBindAction( nLast,  {action: "last"},     fnClickHandler );
				if ( !oSettings.aanFeatures.p )
				{
					nPaging.id = oSettings.sTableId+'_paginate';
					nFirst.id =oSettings.sTableId+'_first';
					nPrev.id =oSettings.sTableId+'_previous';
					nNext.id =oSettings.sTableId+'_next';
					nLast.id =oSettings.sTableId+'_last';
				}
			},
		"fnUpdate": function ( oSettings, fnCallbackDraw )
			{
				if ( !oSettings.aanFeatures.p )
				{
					return;
				}
				var oPaging = oSettings.oInstance.fnPagingInfo();
				var iPageCount = $.fn.dataTableExt.oPagination.iFullNumbersShowPages;
				var iPageCountHalf = Math.floor(iPageCount / 2);
				var iPages = Math.ceil((oSettings.fnRecordsDisplay()) / oSettings._iDisplayLength);
				var iCurrentPage = Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength) + 1;
				var sList = "";
				var iStartButton, iEndButton, i, iLen;
				var oClasses = oSettings.oClasses;
				var anButtons, anStatic, nPaginateList, nNode;
				var an = oSettings.aanFeatures.p;
				var fnBind = function (j) {
					oSettings.oApi._fnBindAction( this, {"page": j+iStartButton-1}, function(e) {
						oSettings.oApi._fnPageChange( oSettings, e.data.page );
						fnCallbackDraw( oSettings );
						e.preventDefault();
					} );
				};
				if ( oSettings._iDisplayLength === -1 )
				{
					iStartButton = 1;
					iEndButton = 1;
					iCurrentPage = 1;
				}
				else if (iPages < iPageCount)
				{
					iStartButton = 1;
					iEndButton = iPages;
				}
				else if (iCurrentPage <= iPageCountHalf)
				{
					iStartButton = 1;
					iEndButton = iPageCount;
				}
				else if (iCurrentPage >= (iPages - iPageCountHalf))
				{
					iStartButton = iPages - iPageCount + 1;
					iEndButton = iPages;
				}
				else
				{
					iStartButton = iCurrentPage - Math.ceil(iPageCount / 2) + 1;
					iEndButton = iStartButton + iPageCount - 1;
				}
				for ( i=iStartButton ; i<=iEndButton ; i++ )
				{
					sList += (iCurrentPage !== i) ?
						'<li><a tabindex="'+oSettings.iTabIndex+'">'+oSettings.fnFormatNumber(i)+'</a></li>' :
						'<li class="active"><a tabindex="'+oSettings.iTabIndex+'">'+oSettings.fnFormatNumber(i)+'</a></li>';
				}
				for ( i=0, iLen=an.length ; i<iLen ; i++ )
				{
					nNode = an[i];
					if ( !nNode.hasChildNodes() )
					{
						continue;
					}
					$('li:gt(1)', an[i]).filter(':not(li:eq(-2))').filter(':not(li:eq(-1))').remove();
					if ( oPaging.iPage === 0 ) {
						$('li:eq(0)', an[i]).addClass('disabled');
						$('li:eq(1)', an[i]).addClass('disabled');
					} else {
						$('li:eq(0)', an[i]).removeClass('disabled');
						$('li:eq(1)', an[i]).removeClass('disabled');
					}
					if ( oPaging.iPage === oPaging.iTotalPages-1 || oPaging.iTotalPages === 0 ) {
						$('li:eq(-1)', an[i]).addClass('disabled');
						$('li:eq(-2)', an[i]).addClass('disabled');
					} else {
						$('li:eq(-1)', an[i]).removeClass('disabled');
						$('li:eq(-2)', an[i]).removeClass('disabled');
					}
					$(sList)
						.insertBefore('li:eq(-2)', an[i])
						.bind('click', function (e) {
							e.preventDefault();
							oSettings._iDisplayStart = (parseInt($('a', this).text(),10)-1) * oPaging.iLength;
							fnCallbackDraw( oSettings );
						});
				}
			}
	}	
} );
 
 
/*
* TableTools Bootstrap compatibility
* Required TableTools 2.1+
*/
if ($.fn.DataTable.TableTools) {
  // Set the classes that TableTools uses to something suitable for Bootstrap
  // Set the classes that TableTools uses to something suitable for Bootstrap
  $.extend(true, $.fn.DataTable.TableTools.classes, {
    "container": "DTTT btn-group",
    "buttons": {
      "normal": "btn btn-default",
      "disabled": "disabled"
    },
    "collection": {
      "container": "DTTT_dropdown dropdown-menu",
      "buttons": {
        "normal": "",
        "disabled": "disabled"
      }
    },
    "print": {
      "info": "DTTT_print_info modal"
    },
    "select": {
      "row": "active"
    }
  });
 
  // Have the collection use a bootstrap compatible dropdown
  $.extend(true, $.fn.DataTable.TableTools.DEFAULTS.oTags, {
    "collection": {
      "container": "ul",
      "button": "li",
      "liner": "a"
    }
  });
}
 
// Moved to the bottom.
if ($.fn.DataTable.defaults) {
  $.extend($.fn.dataTable.defaults, {
    'bAutoWidth': false,
    'aLengthMenu': [[10, 25, 50, 100], [10, 25, 50, 100]],
    'iDisplayLength': 10,
    "bFilter": true
  });
}