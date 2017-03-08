
//
// Authcache debug functionality
//

/**
 * Display debug info, depending on phase
 */
Authcache.debug = function(ajaxData) {

  if (!ajaxData) {

    legend = (jQuery.cookie('drupal_user')) ? " (logged in: "+jQuery.cookie('drupal_user')+')' : '';

    //if (Authcache.isEnabled) {
    //simg: changed to authcache.info to help make more debug info available more often
    if (Authcache.info) {
      // Get seconds page was last cached, using Unix Epoch (GMT/UTC timestamp)
      utc = (new Date()).toUTCString(); // Client's time
      utcTimestamp = Date.parse(utc) / 1000; // Convert to seconds

      Authcache.info["(page_age)"] =  Math.round(utcTimestamp - Authcache.info.cache_time) + " seconds";
      jQuery("#authcache-info").html("<strong>This page was cached " + Authcache.info["(page_age)"] + " ago.</strong>");
      if (utcTimestamp - Authcache.info.cache_time < -10) {
        jQuery("#authcache-info").append("<div style=\"font-size:85%\">Your computer's <a href=\"http://tycho.usno.navy.mil/cgi-bin/timer.pl\">time</a> may be off.</div>");
      }
      
      var cache_render_time = jQuery.cookie("cache_render");
      jQuery.cookie("cache_render",null); 
      var alert_color = '#F7F7F7';
      if (!jQuery.cookie('authcache_debug')) {
        Authcache.info.cache_render = 'This is your first site visit and the debug cookie has just been set.';
      }
      else if(cache_render_time && !isNaN(cache_render_time)) { // numeric?
        Authcache.info.cache_render = cache_render_time + " ms"
        Authcache.info.cache_render += " (" + Math.round((Authcache.info.page_render - cache_render_time) / cache_render_time * 100).toString().replace(/(\d+)(\d{3})/, '$1' + ',' + '$2') + "% increase)"
        if (cache_render_time < 30) alert_color = 'green';
        else if (cache_render_time < 100) alert_color = 'orange';
        else if (cache_render_time > 100) alert_color = 'red';
      }
      else if(!cache_render_time) {
        Authcache.info.cache_render = 'cache miss';
        var alert_color = 'red';
      }
      else {
        Authcache.info.cache_render = "n/a (try a different browser?)";
        var alert_color = 'red';
      }
      //if (jQuery.cookie('authcache_compression')) {
        //Authcache.info.compression = jQuery.cookie('authcache_compression');
      //}
      
      //Authcache.info.page_render += " ms"; //simg: why was this here?

      debugInfo = Authcache.debugFieldset("Authcache.info"+legend, Authcache.info);
      
      if (Authcache.isEnabled) {
        debugInfo += '<a href="#" onclick="return Authcache.debugDisable();">Disable caching for this browser session</a>';
      } else {
        if (jQuery.cookie("nocache")) {
          debugInfo += '<a href="#" onclick="return Authcache.debugEnable();">Enable caching for this browser session</a>';
        }
      }
    }
    else {
      if (JSON.stringify(Authcache.info) == "{}") Authcache.info = "Authcache.info JSON is empty. @see _authcache_shutdown_save_page()";
      //Authcache.info = "Authcache.info JSON is empty. @see _authcache_shutdown_save_page()";
      debugInfo = Authcache.debugFieldset("Authcache prevented caching", {"NO_CACHE" : "Page not cached.", "INFO" : Authcache.info });
    }
    
    
    jQuery("body").prepend("<div id='authcachedbg'><div id='authcache_status_indicator' style='background:" + alert_color + "'></div><b><a href='#' id='authcachehide'>Authcache Debug</a></b><div id='authcachedebug' style='display:none;'>"+debugInfo+"</div></div>");
    jQuery("#authcachehide").click(function() {jQuery("#authcachedebug").toggle(); return false; })

    Authcache.debugTimer();
  }
  else {
    //ajaxLink = '<a href="'+Drupal.settings.basePath+'ajax_authcache.php?'+jQuery.param(Authcache.ajax)+'">Authcache.ajax (sent)</a>';
    ajaxLink = 'Request:';
    legend = "Authcache.ajaxRequest #" + (++Authcache.ajax_count);
    if (typeof ajaxData.sent.max_age != "undefined") legend += " (Cached for " + ajaxData.sent.max_age + " seconds -- see max_age)";
    debugInfo = 
      "<fieldset><legend><b>" + legend + "</b></legend>" + Authcache.debugFieldset(ajaxLink, ajaxData.sent) +
      Authcache.debugFieldset("Response:", ajaxData.received) +
      "</fieldset>";

    jQuery("#authcachedebug").append(debugInfo);
    Authcache.debugTimer();
  }
}

/**
 * Disable caching by setting cookie
 */
Authcache.debugDisable = function() {
  if (confirm("Are you sure? (You can renable caching by closing and reopening your browser.)")) {
    jQuery.cookie('nocache', 1);
    location.reload(true);
    //setTimeout("location.reload(true)", 1000);
  }
  return false;
}

/**
 * Disable caching by setting cookie
 */
Authcache.debugEnable = function() {
  jQuery.cookie('nocache', null, {path:'/'});
  location.reload(true);
  //setTimeout("location.reload(true)", 1000);
  return false;
}

/**
 * Display total JavaScript execution time for this file (including Ajax)
 */
Authcache.debugTimer = function() {
  timeMs = new Date().getTime() - Authcache.timeStart;
  jQuery("#authcachedebug").append("HTML/JavaScript time: "+timeMs+" ms <hr size=1>");
}

/**
 * Helper function (renders HTML fieldset)
 */
Authcache.debugFieldset = function(title, jsonData) {
  fieldset = '<div style="clear:both;"></div><fieldset style="float:left;min-width:240px;"><legend>'+title+'</legend>';
  for (key in jsonData) {
    fieldset += "<b>"+key+"</b>: "+JSON.stringify(jsonData[key]).replace(/</g, '&lt;') +'<br>';
  }
  fieldset += '</fieldset><div style="clear:both;">';
  return fieldset;
}

/**
 * JSON to String
 * http://www.JSON.org/js.html
 */
if(!this.JSON){JSON={};}
(function(){function f(n){return n<10?'0'+n:n;}
if(typeof Date.prototype.toJSON!=='function'){Date.prototype.toJSON=function(key){return this.getUTCFullYear()+'-'+
f(this.getUTCMonth()+1)+'-'+
f(this.getUTCDate())+'T'+
f(this.getUTCHours())+':'+
f(this.getUTCMinutes())+':'+
f(this.getUTCSeconds())+'Z';};String.prototype.toJSON=Number.prototype.toJSON=Boolean.prototype.toJSON=function(key){return this.valueOf();};}
var cx=/[\u0000\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g,escapable=/[\\\"\x00-\x1f\x7f-\x9f\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g,gap,indent,meta={'\b':'\\b','\t':'\\t','\n':'\\n','\f':'\\f','\r':'\\r','"':'\\"','\\':'\\\\'},rep;function quote(string){escapable.lastIndex=0;return escapable.test(string)?'"'+string.replace(escapable,function(a){var c=meta[a];return typeof c==='string'?c:'\\u'+('0000'+a.charCodeAt(0).toString(16)).slice(-4);})+'"':'"'+string+'"';}
function str(key,holder){var i,k,v,length,mind=gap,partial,value=holder[key];if(value&&typeof value==='object'&&typeof value.toJSON==='function'){value=value.toJSON(key);}
if(typeof rep==='function'){value=rep.call(holder,key,value);}
switch(typeof value){case'string':return quote(value);case'number':return isFinite(value)?String(value):'null';case'boolean':case'null':return String(value);case'object':if(!value){return'null';}
gap+=indent;partial=[];if(Object.prototype.toString.apply(value)==='[object Array]'){length=value.length;for(i=0;i<length;i+=1){partial[i]=str(i,value)||'null';}
v=partial.length===0?'[]':gap?'[\n'+gap+
partial.join(',\n'+gap)+'\n'+
mind+']':'['+partial.join(',')+']';gap=mind;return v;}
if(rep&&typeof rep==='object'){length=rep.length;for(i=0;i<length;i+=1){k=rep[i];if(typeof k==='string'){v=str(k,value);if(v){partial.push(quote(k)+(gap?': ':':')+v);}}}}else{for(k in value){if(Object.hasOwnProperty.call(value,k)){v=str(k,value);if(v){partial.push(quote(k)+(gap?': ':':')+v);}}}}
v=partial.length===0?'{}':gap?'{\n'+gap+partial.join(',\n'+gap)+'\n'+
mind+'}':'<br>{'+partial.join(',<br>')+'}';gap=mind;return v;}}
if(typeof JSON.stringify!=='function'){JSON.stringify=function(value,replacer,space){var i;gap='';indent='';if(typeof space==='number'){for(i=0;i<space;i+=1){indent+=' ';}}else if(typeof space==='string'){indent=space;}
rep=replacer;if(replacer&&typeof replacer!=='function'&&(typeof replacer!=='object'||typeof replacer.length!=='number')){throw new Error('JSON.stringify');}
return str('',{'':value});};}
if(typeof JSON.parse!=='function'){JSON.parse=function(text,reviver){var j;function walk(holder,key){var k,v,value=holder[key];if(value&&typeof value==='object'){for(k in value){if(Object.hasOwnProperty.call(value,k)){v=walk(value,k);if(v!==undefined){value[k]=v;}else{delete value[k];}}}}
return reviver.call(holder,key,value);}
cx.lastIndex=0;if(cx.test(text)){text=text.replace(cx,function(a){return'\\u'+
('0000'+a.charCodeAt(0).toString(16)).slice(-4);});}
if(/^[\],:{}\s]*jQuery/.test(text.replace(/\\(?:["\\\/bfnrt]|u[0-9a-fA-F]{4})/g,'@').replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g,']').replace(/(?:^|:|,)(?:\s*\[)+/g,''))){j=eval('('+text+')');return typeof reviver==='function'?walk({'':j},''):j;}
throw new SyntaxError('JSON.parse');};}})();
