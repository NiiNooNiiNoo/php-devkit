/* JS prototype helper lib by xSplit */
Object.prototype.keys = function(){ return Object.keys(this); }
Object.prototype.name = function(){ return this.constructor.name; }
Object.prototype.obj = function(){ return Object.prototype.toString.call(this); }
Object.prototype.is = function(x){ return this.obj() === '[object '+x+']'; }
Object.prototype.toJSONString = function(){ return JSON.stringify(this); }
Object.prototype.isArray = function(){ return this.is('Array'); }
Object.prototype.toArray = function(){ return Array.prototype.slice.apply(this); }
Object.prototype.dump = function(){ console.log("Console Dumper\nType: "+typeof(this)+"\nObject: "+this.obj()+"\ntoString: "+this+"\nJSON: "+this.toJSONString()); }
Object.prototype.destroy = function(){ delete this; }
Object.prototype.merge = function(x){ for(var v=0;v< x.keys().length;v++) this[x.keys()[v]] = x[x.keys()[v]]; return this; }
Array.prototype.add = function(c){ this.push(c); return this; }
Array.prototype.countValue = function(c){ var count = 0; for(var v=0;v<this.length;v++) if(this[v]===c) count++; return count; }
Array.prototype.getValue = function (c,i){
    i = i?i:1;
    var x = false;
    for(var v=0;v<i;v++){
        if(v===this.countValue(c)){ x=false; break; }
        x = this.indexOf(c, x + 1);
    }
    return x?x:false;
}
Array.prototype.set = function(k,c){ if(k) this[k] = c; return this; }
Array.prototype.remove = function(c,o) {
    o = o?o:1;
    for (var v=0,t=0;v<this.length;v++){
        if (this[v] === c){ this.unset(this.indexOf(c)); t++,v--; if(t===o)break; }
    }
    return this;
}
Array.prototype.unset = function(k){ this.splice(k,1); return this; }
Array.prototype.has = String.prototype.has = function(c){ return (Boolean)(~this.indexOf(c)); }
Array.prototype.removeAll = function(c){ while(this.has(c)) this.remove(c); return this; }
Array.prototype.merge = function(c){ for (var v=0;v<c.length;v++) this.add(c[v]); return this; }
Array.prototype.unique = function(){
    var done = [];
    for(var v=0;v<this.length;v++) {
        if (!done.has(this[v])) done.add(this[v]); else { this.remove(this[v]); v--; }
    }
    return this;
}
Array.prototype.diff = function(c){ for(var v=0;v<c.length;v++) this.removeAll(c[v]); return this; }
Array.prototype.each = function(f){ for(var v=0;v<this.length;v++) f(v,this[v]); return this; }
Array.prototype.sum = function(){ var count = 0; for(var v=0;v<this.length;v++) count+=this[v]; return count; }
Array.range = function(n){ var r = []; while(r.length<n) r[r.length] = r.length+1; return r; }
String.base64charset = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
String.prototype.encodeBase64 = function(){
    var r = "", i = 0, c1,c2,c3,e1,e2,e3,e4;
    while(i < this.length) {
        c1 = this.charCodeAt(i++), c2 = this.charCodeAt(i++), c3 = this.charCodeAt(i++);
        e1 = c1 >> 2, e2 = ((c1 & 3) << 4) | (c2 >> 4), e3 = ((c2 & 15) << 2) | (c3 >> 6), e4 = c3 & 63;
        if(isNaN(c2)) e3 = e4 = 64; else if(isNaN(c3)) e4 = 64;
        r += String.base64charset.charAt(e1) + String.base64charset.charAt(e2) + String.base64charset.charAt(e3) + String.base64charset.charAt(e4);
    }
    return r;
}
String.prototype.decodeBase64 = function(){
    var r = "", i = 0, c1,c2,c3,e1,e2,e3,e4;
    while (i < this.length) {
        e1 = String.base64charset.indexOf(this.charAt(i++)), e2 = String.base64charset.indexOf(this.charAt(i++)), e3 = String.base64charset.indexOf(this.charAt(i++)), e4 = String.base64charset.indexOf(this.charAt(i++));
        c1 = (e1 << 2) | (e2 >> 4), c2 = ((e2 & 15) << 4) | (e3 >> 2), c3 = ((e3 & 3) << 6) | e4;
        r += String.fromCharCode(c1);
        if (e3 != 64) r += String.fromCharCode(c2);
        if (e4 != 64) r += String.fromCharCode(c3);
    }
    return r;
}
String.prototype.toJSONObject = function(){ return JSON.parse(this); }
String.prototype.urlEncode = function(){ return encodeURIComponent(this); }
String.prototype.urlDecode = function(){ return decodeURIComponent(this); }
Math.randomNumber = function(n,m){ return Math.floor(Math.random()*(m-n))+n; }
Math.randomString = function(n){
    var b = String.base64charset.substr(0,62), r = "";
    for(var i=0;i<n;i++) r+=b[Math.randomNumber(0,62)];
    return r;
}
Math.isNumber = function(n){ return n.is('Number'); }
Math.isPair = function(n){ return n%2===0; }
Math.isDouble = function(n){ return n%1!==0 && this.isNumber(n); }
Math.isInt = function(n){ return this.isNumber(n) && !this.isDouble(n); }
document.loadScript = function(s){
    var d = document.createElement('script');
    d.src = s;
    document.getElementsByTagName('head')[0].appendChild(d);
}
document.setCookie = function(n,v,e,p,d,ss){
    if(!n) return false;
    var s = n.toString().urlEncode()+'='+ v.toString().urlEncode();
    if(e) if(e.is('Date')) s+=';expires='+e.toUTCString();
    if(p) s+=';path='+p;
    if(d) s+=';domain='+d;
    if(ss) s+=';secure';
    document.cookie = s;
    return true;
}
document.getCookie = function(n){ return document.cookie.has(n+'=')?document.cookie.split(n+'=')[1].split(';')[0].urlDecode():false; }
document.removeCookie = function(n){  return this.getCookie(n)?this.setCookie(n,'',new Date('1970')):false; }
navigator.isMobile = function(){ return navigator.userAgent.match(/iPhone|iPad|iPod|Android|BlackBerry|Opera Mini|IEMobile/i);}
window.getMonitorSize = function(){ return {width:window.outerWidth,height:window.outerHeight}; }
window.jopen = window.open;
window.windows = [];
window.open = function(u,n,p){ var w = window.jopen(u,n,p); this.windows.add([w,u]); }
window.getWinsByUrl = function(u){ return this.windows.filter(function(w){ return w[1]===u; }); }
