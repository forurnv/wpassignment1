!function(t){"use strict";t.fn.conditional=function(i){var a=t.extend({data:"conditional",value:"conditional-value",displayOnEnabled:"conditional-display"},i);return this.each(function(){if(void 0===t(this).data(a.data))return!0;var i,n,e,h;t(this).on("change",function(){switch(i=t(this).data(a.data).split(","),void 0===(n=t(this).data(a.displayOnEnabled))&&(n=!0),e=void 0===(e=t(this).data(a.value))?"":String(e).split(","),h=!1,t(this).attr("type")){case"checkbox":h=n?t(this).is(":checked"):!t(this).is(":checked");break;default:h=n?e.length>0?-1!=e.indexOf(String(t(this).val())):""!==t(this).val()&&"0"!==t(this).val():e.length>0?-1==e.indexOf(String(t(this).val())):""===t(this).val()||"0"===t(this).val();break}for(var s=0;s<i.length;s++){var d;d=t("#"+i[s]).length>0?t("#"+i[s]):t("."+i[s],t(this).parent()),h?t(d).fadeIn(300):t(d).fadeOut(300)}}),t(this).trigger("change")}),this}}(jQuery);