/*!
Bootstrap integration for DataTables' Responsive
©2015-2016 SpryMedia Ltd - datatables.net/license
*/
(function (c) {
  "function" === typeof define && define.amd
    ? define(["jquery", "datatables.net-bs", "datatables.net-responsive"], function (a) {
        return c(a, window, document);
      })
    : "object" === typeof exports
    ? (module.exports = function (a, b) {
        a || (a = window);
        if (!b || !b.fn.dataTable) b = require("datatables.net-bs")(a, b).$;
        b.fn.dataTable.Responsive || require("datatables.net-responsive")(a, b);
        return c(b, a, a.document);
      })
    : c(jQuery, window, document);
})(function (c) {
  var a = c.fn.dataTable,
    b = a.Responsive.display,
    g = b.modal,
    e = c(
      '<div class="modal fade dtr-bs-modal" role="dialog"><div class="modal-dialog" role="document"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button></div><div class="modal-body"/></div></div></div>'
    );
  b.modal = function (a) {
    return function (b, d, f) {
      if (c.fn.modal) {
        if (!d) {
          if (a && a.header) {
            var d = e.find("div.modal-header"),
              h = d.find("button").detach();
            d.empty()
              .append('<h4 class="modal-title">' + a.header(b) + "</h4>")
              .prepend(h);
          }
          e.find("div.modal-body").empty().append(f());
          e.appendTo("body").modal();
        }
      } else g(b, d, f);
    };
  };
  return a.Responsive;
});

// Datatables Bootstrap Pagination Integration
jQuery.fn.dataTableExt.oApi.fnPagingInfo=function(e){return{iStart:e._iDisplayStart,iEnd:e.fnDisplayEnd(),iLength:e._iDisplayLength,iTotal:e.fnRecordsTotal(),iFilteredTotal:e.fnRecordsDisplay(),iPage:Math.ceil(e._iDisplayStart/e._iDisplayLength),iTotalPages:Math.ceil(e.fnRecordsDisplay()/e._iDisplayLength)}},jQuery.extend(jQuery.fn.dataTableExt.oPagination,{bootstrap:{fnInit:function(e,t,n){var i=e.oLanguage.oPaginate,r=function(t){t.preventDefault(),e.oApi._fnPageChange(e,t.data.action)&&n(e)};jQuery(t).append('<ul class="pagination pagination-sm remove-margin"><li class="prev disabled"><a href="javascript:void(0)"><i class="fa fa-chevron-left"></i> '+i.sPrevious+"</a></li>"+'<li class="next disabled"><a href="javascript:void(0)">'+i.sNext+' <i class="fa fa-chevron-right"></i></a></li>'+"</ul>");var o=jQuery("a",t);jQuery(o[0]).bind("click.DT",{action:"previous"},r),jQuery(o[1]).bind("click.DT",{action:"next"},r)},fnUpdate:function(e,t){var n,i,r,o,a,s=5,l=e.oInstance.fnPagingInfo(),c=e.aanFeatures.p,u=Math.floor(s/2);for(l.iTotalPages<s?(o=1,a=l.iTotalPages):l.iPage<=u?(o=1,a=s):l.iPage>=l.iTotalPages-u?(o=l.iTotalPages-s+1,a=l.iTotalPages):(o=l.iPage-u+1,a=o+s-1),n=0,iLen=c.length;iLen>n;n++){for(jQuery("li:gt(0)",c[n]).filter(":not(:last)").remove(),i=o;a>=i;i++)r=i===l.iPage+1?'class="active"':"",jQuery("<li "+r+'><a href="javascript:void(0)">'+i+"</a></li>").insertBefore(jQuery("li:last",c[n])[0]).bind("click",function(n){n.preventDefault(),e._iDisplayStart=(parseInt(jQuery("a",this).text(),10)-1)*l.iLength,t(e)});0===l.iPage?jQuery("li:first",c[n]).addClass("disabled"):jQuery("li:first",c[n]).removeClass("disabled"),l.iPage===l.iTotalPages-1||0===l.iTotalPages?jQuery("li:last",c[n]).addClass("disabled"):jQuery("li:last",c[n]).removeClass("disabled")}}}});

//datatable
var dtIntegration = function() {
  $.extend(true, $.fn.dataTable.defaults, {
    "sDom": "<'row'<'col-6 col-5'l><'col-6 col-7'f>r>t<'row'<'col-5 hidden-xs'i><'col-7 col-12 clearfix'p>>",
    "sPaginationType": "bootstrap",
    "oLanguage": {
      "sLengthMenu": "_MENU_",
      "sSearch": "<div class=\"input-group\">_INPUT_<span class=\"input-group-text\"><i class=\"fa fa-search\"></i></span></div>",
      "sInfo": "<strong>_START_</strong>-<strong>_END_</strong> of <strong>_TOTAL_</strong>",
      "oPaginate": {
        "sPrevious": "",
        "sNext": ""
      }
    }
  });
  $.extend($.fn.dataTableExt.oStdClasses, {
    "sWrapper": "dataTables_wrapper form-inline",
    "sFilterInput": "form-control",
    "sLengthSelect": "form-select"
  });
};
