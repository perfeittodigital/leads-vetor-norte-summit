<?php
function cartao($emoji, $titulo, $tempo, $html, $aberto = false) {
  echo '<details class="cartao"' . ($aberto ? ' open' : '') . '><summary><i>' . $emoji . '</i><span><b>' . $titulo . '</b>' . ($tempo ? '<small>' . $tempo . '</small>' : '') . '</span><em></em></summary><div class="corpo"><div class="ferr"><button type="button" class="fb ouvir">🔊 Ouvir</button><button type="button" class="fb copiarfala">📋 Copiar</button></div>' . $html . '</div></details>';
}
?>
<h1>O que falar</h1>
<p class="sub">Fonte oficial da Perfeitto Digital, aprovada pelo Edgard em 13/11/2026. Toque em um cartão para abrir. Dentro dele, use <b>Ouvir</b> para escutar a fala e <b>Copiar</b> para levar o texto.</p>

<section class="bordao"><small>⭐ Bordão oficial</small><p>Inteligência artificial qualquer um vende.<br><b>A gente entrega funcionando.</b></p><div class="ferr"><button type="button" class="fb ouvir" data-texto="Inteligência artificial qualquer um vende. A gente entrega funcionando.">🔊 Ouvir</button><button type="button" class="fb copiarfala" data-texto="Inteligência artificial qualquer um vende. A gente entrega funcionando.">📋 Copiar</button></div></section>

<div class="busca"><input type="search" id="buscafala" placeholder="🔎 Pesquisar nesta página" autocomplete="off" aria-label="Pesquisar nesta página"><p id="buscavazio" hidden>Nada encontrado. Tente outra palavra.</p></div>
<div class="acoes"><button type="button" class="bt claro" id="abrirtudo">Abrir tudo</button><button type="button" class="bt claro" id="fechartudo">Fechar tudo</button></div>

<section class="grupofala"><h2 class="grupo">📌 O que a gente resolve</h2>
<?php cartao('🔥', 'As três dores', 'Para reconhecer na conversa', '<ul class="dores"><li>😮‍💨 <b>Atendimento sufocado.</b> Cliente esperando resposta.</li><li>💸 <b>Venda que se perde</b> no meio do caminho.</li><li>⏳ <b>Tudo passa pelo dono,</b> que fica sem tempo.</li></ul>', true); ?>
</section>

<section class="grupofala"><h2 class="grupo">🎯 O que a gente faz</h2>
<?php
cartao('1️⃣', 'Uma frase', '5 segundos', '<p class="dizer">A gente faz pequenas empresas <b>venderem mais e atenderem melhor</b>, sem <b>depender do dono</b> para tudo.</p><p class="dizer">Usamos <b>inteligência artificial</b>, e a implantação <b>pode ficar com a gente</b>.</p>');
cartao('2️⃣', 'Resposta curta', '15 segundos', '<p class="dizer">Sabe aquele negócio em que <b>tudo passa pelo dono</b>? Cliente esperando no WhatsApp, venda que se perde, dono sem tempo.</p><p class="dizer"><b>A gente resolve isso.</b> Entendemos como a empresa funciona, organizamos o processo e implantamos inteligência artificial e automação.</p><p class="dizer">E você <b>não precisa se virar sozinho</b> com a ferramenta. Se quiser, a gente <b>faz com você ou faz por você</b>.</p>');
cartao('3️⃣', 'Resposta completa', '30 segundos', '<p class="nota">👉 Fale a resposta curta e emende com este final:</p><p class="dizer"><b>Eu vivi isso</b> na minha empresa de eventos, que tem <b>mais de 15 anos</b>. Resolvi lá primeiro. Hoje levo o mesmo método para outros negócios.</p><p class="dizer">E no seu, <b>o que mais depende de você hoje?</b></p><p class="nota">💡 A pergunta do final devolve a conversa e abre o cadastro do lead.</p>');
cartao('💬', 'Se perguntarem: o que vocês vendem?', '15 segundos', '<p class="dizer">Vendemos <b>a solução funcionando</b> dentro do seu negócio. Diagnóstico, método e as ferramentas certas.</p><p class="dizer"><b>Você escolhe</b> o quanto quer que a gente faça: a gente <b>faz por você, faz com você ou acompanha você</b>.</p><p class="dizer">E se preferir <b>só a ferramenta</b> para usar por conta própria, também tem.</p><p class="nota">🚫 Sem preço no estande. Preço só depois do diagnóstico.</p>');
?>
</section>

<section class="grupofala"><h2 class="grupo">🎤 Entrevista</h2>
<?php
cartao('🎙', 'Fala da entrevista', 'Cerca de 40 segundos', '<p class="dizer"><b>Todo mundo está vendendo inteligência artificial. Pouca gente está implantando.</b> O pequeno empresário compra a ferramenta, não tem tempo de aprender, e ela fica parada.</p><p class="dizer">Eu sei porque <b>eu era esse empresário</b>. Tenho uma empresa de eventos há <b>mais de 15 anos</b> e vivia sufocado. WhatsApp lotado, venda perdida, sem tempo para nada. <b>Resolvi isso dentro do meu negócio</b>, com inteligência artificial aplicada de forma simples.</p><p class="dizer">Daí nasceu a <b>Perfeitto Digital</b>. A gente entra na empresa, entende como ela funciona e implanta. <b>Com o dono ou pelo dono.</b> Para vender mais, atender melhor e depender menos dele.</p><p class="dizer forte">Inteligência artificial qualquer um vende. A gente entrega funcionando.</p>');
cartao('⚡', 'Versão curta da entrevista', '15 segundos', '<p class="dizer"><b>Todo mundo vende inteligência artificial. A gente implanta.</b></p><p class="dizer">Entra na pequena empresa, entende o negócio e <b>deixa funcionando</b>, para ela vender mais, atender melhor e depender menos do dono.</p><p class="dizer forte">Inteligência artificial qualquer um vende. A gente entrega funcionando.</p>');
?>
</section>

<section class="grupofala"><h2 class="grupo">🧭 Soluções, na ordem do funil</h2>
<?php
cartao('🪜', 'Os seis produtos', 'Do primeiro passo ao serviço principal', '<ol class="funil"><li><b>🚪 Método NPV, Não Perca Vendas</b><span>A <b>porta de entrada</b>. Método guiado para recuperar as vendas esquecidas no WhatsApp.</span></li><li><b>📈 WhaBoost</b><span>O sistema que <b>organiza leads, retornos e o funil</b> de vendas no WhatsApp.</span></li><li><b>🤖 Perfeitto IA</b><span>O <b>funcionário de inteligência artificial</b> que atende no WhatsApp.</span></li><li><b>🧾 OrçaZap</b><span><b>Orçamento rápido</b> pelo WhatsApp, pensado para quem trabalha com eventos.</span></li><li><b>💰 WePoupe</b><span><b>Organização financeira</b> pelo WhatsApp.</span></li><li><b>🏆 Método Perfeitto</b><span>O <b>serviço principal</b>. Inteligência, processo e automação dentro da empresa.</span></li></ol>');
cartao('🤝', 'Como o cliente contrata', 'A escolha é sempre dele', '<ul class="modos"><li><b>🛠 Faço por Você</b><span>A Perfeitto Digital <b>assume a execução</b>.</span></li><li><b>🤝 Faço com Você</b><span>A gente estrutura e <b>implanta junto</b> com a equipe do cliente.</span></li><li><b>🧭 Acompanho Você</b><span>O cliente executa, <b>com a nossa orientação</b>.</span></li><li><b>⚙️ Sistema com implantação</b><span>O cliente compra o sistema e a gente deixa <b>configurado e rodando</b>.</span></li><li><b>📦 Só o sistema</b><span>O cliente compra e <b>usa por conta própria</b>.</span></li></ul><p class="nota">💡 Tudo o que vendemos <b>pode ser implantado por nós</b>, se o cliente quiser. Ou ele compra e usa sozinho. O nosso foco é entregar funcionando.</p>');
cartao('🗣', 'Frase para explicar o caminho', '10 segundos', '<p class="dizer"><b>Começa pequeno</b>, descobrindo onde perde venda. Depois <b>organiza com sistema</b>. E quando quiser transformar a operação, <b>a gente entra junto</b>.</p>');
?>
</section>

<section class="grupofala"><h2 class="grupo">⚠️ Cuidados</h2>
<?php cartao('🛑', 'O que não falar', 'Leia uma vez antes de começar', '<ul class="dores"><li>🚫 <b>Não citar resultado de cliente</b> da Perfeitto Digital. A prova é o nosso próprio negócio.</li><li>💭 "Pouca gente está implantando" é <b>opinião</b>. Sem número.</li><li>🎓 Formação do Edgard: <b>estudou</b> Sistemas de Informação na PUC Minas.</li><li>📅 <b>Mais de 15 anos</b> é a Perfeitto Produções. A Perfeitto Digital tem <b>cerca de 3 anos</b>.</li><li>💲 <b>Sem preço</b> no estande.</li></ul>'); ?>
</section>

<p class="sub"><a href="?p=roteiro">Ver o roteiro de abordagem</a> · <a href="?p=material">Voltar ao material</a></p>
<script>
(function(){
var cards=[].slice.call(document.querySelectorAll('details.cartao')),grupos=[].slice.call(document.querySelectorAll('.grupofala'));
function norm(s){return s.toLowerCase().normalize('NFD').replace(/[̀-ͯ]/g,'')}
function limpa(s){return s.replace(/[\u{1F000}-\u{1FFFF}\u{2190}-\u{2BFF}\u{FE0F}\u{200D}\u{20E3}]/gu,'').replace(/[ \t]+/g,' ').replace(/ *\n */g,'\n').trim()}
function texto(b){
  if(b.dataset.texto)return b.dataset.texto;
  var c=b.closest('.corpo'),d=c.querySelectorAll('.dizer'),out=[];
  if(d.length){d.forEach(function(x){out.push(x.innerText)})}
  else{c.querySelectorAll('li').forEach(function(x){out.push(x.innerText.replace(/\n+/g,'. '))})}
  return limpa(out.join('\n'));
}
document.getElementById('abrirtudo').onclick=function(){cards.forEach(function(x){if(!x.hidden)x.open=true})};
document.getElementById('fechartudo').onclick=function(){cards.forEach(function(x){x.open=false})};
var falando=null,S=window.speechSynthesis;
function parar(){if(S)S.cancel();if(falando){falando.textContent='🔊 Ouvir';falando.classList.remove('on');falando=null}}
document.addEventListener('click',function(e){
  var b=e.target.closest('.fb');if(!b)return;
  if(b.classList.contains('copiarfala')){
    var t=texto(b),ok=function(){var o=b.textContent;b.textContent='✅ Copiado';setTimeout(function(){b.textContent='📋 Copiar'},1600)};
    if(navigator.clipboard&&navigator.clipboard.writeText){navigator.clipboard.writeText(t).then(ok,function(){velho(t);ok()})}else{velho(t);ok()}
  }else{
    if(!S||!window.SpeechSynthesisUtterance){b.textContent='Sem áudio neste aparelho';return}
    if(falando===b){parar();return}
    parar();
    var u=new SpeechSynthesisUtterance(texto(b));u.lang='pt-BR';u.rate=.95;
    var v=S.getVoices().filter(function(x){return /^pt[-_]BR/i.test(x.lang)})[0];if(v)u.voice=v;
    u.onend=u.onerror=function(){if(falando===b){b.textContent='🔊 Ouvir';b.classList.remove('on');falando=null}};
    falando=b;b.textContent='⏹ Parar';b.classList.add('on');S.speak(u);
  }
});
function velho(t){var a=document.createElement('textarea');a.value=t;a.style.position='fixed';a.style.opacity='0';document.body.appendChild(a);a.select();try{document.execCommand('copy')}catch(e){}a.remove()}
if(S)S.getVoices();
window.addEventListener('pagehide',parar);
var inp=document.getElementById('buscafala'),vazio=document.getElementById('buscavazio'),antes=null;
inp.addEventListener('input',function(){
  var q=norm(inp.value.trim()),n=0;
  if(q&&antes===null)antes=cards.map(function(c){return c.open});
  cards.forEach(function(c,i){
    var bate=!q||norm(c.textContent).indexOf(q)>-1;c.hidden=!bate;
    if(q){c.open=bate}else if(antes){c.open=antes[i]}
    if(bate)n++;
  });
  if(!q)antes=null;
  grupos.forEach(function(g){g.hidden=!g.querySelector('details.cartao:not([hidden])')});
  vazio.hidden=n>0;
});
})();
</script>
