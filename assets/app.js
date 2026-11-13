(function(){
var $=function(s,c){return (c||document).querySelector(s)},$$=function(s,c){return [].slice.call((c||document).querySelectorAll(s))};
var PART=['de','da','do','das','dos','e'];
function titulo(v){return v.replace(/\s+/g,' ').replace(/^\s/,'').toLowerCase().split(' ').map(function(w,i){return (i>0&&PART.indexOf(w)>-1)?w:w.charAt(0).toUpperCase()+w.slice(1)}).join(' ')}
function fone(v){var d=v.replace(/\D/g,'');if(d.length>11&&d.indexOf('55')===0)d=d.slice(2);d=d.slice(0,11);if(d.length<3)return d.length?'('+d:'';if(d.length<7)return '('+d.slice(0,2)+') '+d.slice(2);if(d.length<11)return '('+d.slice(0,2)+') '+d.slice(2,6)+'-'+d.slice(6);return '('+d.slice(0,2)+') '+d.slice(2,7)+'-'+d.slice(7)}
function email(v){return v.replace(/\s+/g,'').toLowerCase()}
var F={titulo:titulo,fone:fone,email:email};
$$('[data-f]').forEach(function(el){el.addEventListener('input',function(){var fim=el.selectionStart===el.value.length,n=F[el.dataset.f](el.value);if(n!==el.value){var p=el.selectionStart;el.value=n;if(!fim)try{el.setSelectionRange(p,p)}catch(e){}}dica(el)});el.addEventListener('blur',function(){el.value=el.value.trim();dica(el,true)})});
function dica(el,fim){var d=$('[data-dica="'+el.dataset.f+'"]');if(!d)return;d.className='dica';d.textContent='';var v=el.value;if(!v)return;
 if(el.dataset.f==='fone'){var n=v.replace(/\D/g,'').length;if(n===10||n===11){d.textContent='Número completo ✓';d.className='dica ok'}else if(fim)d.textContent='Confira o número: faltam dígitos (DDD + número).'}
 if(el.dataset.f==='email'){if(/^[^@\s]+@[^@\s]+\.[a-z]{2,}$/.test(v)){d.textContent='E-mail válido ✓';d.className='dica ok'}else if(fim)d.textContent='Confira o e-mail: parece incompleto.'}}
$$('[data-olho]').forEach(function(b){b.onclick=function(){var i=b.parentNode.querySelector('input');i.type=i.type==='password'?'text':'password'}});

/* fila offline */
var K='pdleads_fila';
function fila(){try{return JSON.parse(localStorage.getItem(K)||'[]')}catch(e){return []}}
function grava(f){try{localStorage.setItem(K,JSON.stringify(f))}catch(e){}pinta()}
function pinta(){var n=fila().length,o=$('#offline');if(!o)return;o.hidden=navigator.onLine&&!n;$('#fila').textContent=n?n+' na fila.':''}
function post(dados){var b=new URLSearchParams(dados);b.set('csrf',window.CSRF);return fetch('index.php',{method:'POST',headers:{'X-Requested-With':'fetch'},body:b,credentials:'same-origin'}).then(function(r){return r.json().then(function(j){if(j.csrf)window.CSRF=j.csrf;return {s:r.status,j:j}})})}
function agora(){var d=new Date(),z=function(n){return (n<10?'0':'')+n};return d.getFullYear()+'-'+z(d.getMonth()+1)+'-'+z(d.getDate())+' '+z(d.getHours())+':'+z(d.getMinutes())+':'+z(d.getSeconds())}
var enviando=false;
function sobe(){var f=fila();if(!f.length||enviando||!navigator.onLine)return;enviando=true;
 post(f[0]).then(function(r){enviando=false;if(r.j.ok||r.s===422){f.shift();grava(f);sobe()}else if(r.s===403){sobe()}else if(r.s===401){var o=$('#offline');if(o){o.hidden=false;o.innerHTML='Há '+f.length+' lead(s) guardado(s) neste aparelho. <a href="?p=login">Entre de novo</a> para enviar.'}}}).catch(function(){enviando=false})}
window.addEventListener('online',function(){pinta();sobe()});window.addEventListener('offline',pinta);pinta();sobe();setInterval(sobe,20000);

var fl=$('#formlead');
if(fl&&fl.id.value==='0'){fl.addEventListener('submit',function(ev){ev.preventDefault();var d={};new FormData(fl).forEach(function(v,k){d[k]=v});
 if(!(d.nome||d.whatsapp||d.email||d.empresa||d.tipo_negocio||d.observacao)){alerta('Preencha pelo menos um campo.');return}
 var bt=$('button.bt',fl);bt.disabled=true;
 function fim(off){bt.disabled=false;fl.reset();$$('.dica',fl).forEach(function(x){x.textContent=''});var s=$('#salvo');s.textContent=off?'✓ Guardado no aparelho (sem internet)':'✓ Lead salvo';s.hidden=false;setTimeout(function(){s.hidden=true},2200);fl.nome.focus();window.scrollTo(0,0)}
 function guarda(){d.quando=agora();var f=fila();f.push(d);grava(f);fim(true)}
 if(!navigator.onLine){guarda();return}
 post(d).then(function(r){if(r.j.ok){var m=$('#meus'),h=$('#hoje');if(m){m.textContent=r.j.meus;h.textContent=(+h.textContent)+1}fim(false)}else if(r.s===403){post(d).then(function(r2){r2.j.ok?fim(false):guarda()}).catch(guarda)}else if(r.s===401){guarda();location.href='?p=login'}else{bt.disabled=false;alerta('Não foi possível salvar. Tente de novo.')}}).catch(guarda)})}
function alerta(t){var s=$('#salvo');if(!s)return;s.textContent=t;s.style.background='#C62828';s.hidden=false;setTimeout(function(){s.hidden=true;s.style.background=''},2600)}

$$('[data-contatado]').forEach(function(c){c.addEventListener('change',function(){post({acao:'contatado',id:c.dataset.contatado,v:c.checked?1:''});c.closest('.lead').classList.toggle('feito',c.checked)})});
$$('[data-zap]').forEach(function(a){a.addEventListener('click',function(){var c=$('[data-contatado="'+a.dataset.zap+'"]');if(c&&!c.checked){c.checked=true;c.dispatchEvent(new Event('change'))}})});
var dlg=$('#dlg');$$('[data-excluir]').forEach(function(b){b.onclick=function(){dlg.querySelector('[name=id]').value=b.dataset.excluir;dlg.querySelector('h3 span').textContent=b.dataset.nome;dlg.querySelector('[name=confirma]').value='';dlg.showModal()}});
$$('[data-fechar]').forEach(function(b){b.onclick=function(){b.closest('dialog').close()}});
$$('[data-copia]').forEach(function(b){b.onclick=function(){var t=document.getElementById(b.dataset.copia).innerText,o=b.textContent;(navigator.clipboard?navigator.clipboard.writeText(t):Promise.reject()).then(function(){b.textContent='Copiado ✓'}).catch(function(){var a=document.createElement('textarea');a.value=t;document.body.appendChild(a);a.select();document.execCommand('copy');a.remove();b.textContent='Copiado ✓'});setTimeout(function(){b.textContent=o},1800)}});
if('serviceWorker' in navigator)navigator.serviceWorker.register('sw.js');
})();
