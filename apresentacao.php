<?php
$auto = !empty($_GET['auto']);
function img(string $n): string { return file_exists(__DIR__ . "/img/$n.jpg") ? "img/$n.jpg" : 'img/equipe.jpg'; }
$slides = [
 ['t' => 'capa', 'sel' => 'Vetor Norte Summit 2026', 'h' => 'Inteligência artificial para o pequeno negócio <em>vender mais e ganhar tempo.</em>', 'p' => 'Simples, acessível e no WhatsApp que você já usa.', 'img' => img('04-equipe-painel')],
 ['t' => 'dores', 'h' => 'Você se reconhece?', 'itens' => [['💬', 'WhatsApp lotado', 'Cliente esperando resposta e venda ficando para trás.'], ['⏰', 'Falta de tempo', 'O dia acaba e o importante não foi feito.'], ['🧍', 'Tudo depende de você', 'Se o dono para, o negócio para.'], ['📄', 'Orçamento que demora', 'Quando fica pronto, o cliente já fechou com outro.']], 'img' => img('01-lojista')],
 ['t' => 'txt', 'sel' => 'Quem somos', 'h' => 'A gente também é <em>pequeno empresário.</em>', 'li' => ['Perfeitto Produções: mais de 15 anos de eventos', 'Perfeitto Digital: tecnologia e IA há cerca de 3 anos', 'Um negócio enxuto, com a IA fazendo o trabalho repetido', 'Aplicamos antes de ensinar'], 'img' => img('05-consultoria')],
 ['t' => 'txt', 'sel' => 'O que a IA faz por você', 'h' => 'Menos tarefa repetida. <em>Mais vida e mais venda.</em>', 'li' => ['Mais tempo para o que importa', 'Mais vendas, sem cliente esquecido', 'Negócio organizado e mais saudável', 'Menos estresse no dia a dia'], 'img' => img('03-salao')],
 ['t' => 'prod', 'sel' => 'Para quem perde venda no WhatsApp', 'h' => 'WhaBoost', 'p' => 'Seu WhatsApp organizado para vender mais.', 'li' => ['Todos os contatos e conversas em ordem', 'Lembrete para dar retorno na hora certa', 'Cada venda em andamento à vista'], 'img' => img('06-celular-chat')],
 ['t' => 'prod', 'sel' => 'Para quem não consegue responder todo mundo', 'h' => 'Perfeitto IA', 'p' => 'Seu funcionário de IA no WhatsApp.', 'li' => ['Atende seus clientes a qualquer hora', 'Responde do seu jeito, com as suas informações', 'Você entra na conversa quando quiser'], 'img' => img('01-lojista')],
 ['t' => 'prod', 'sel' => 'Para quem demora no orçamento', 'h' => 'OrçaZap', 'p' => 'Orçamento profissional a partir de um áudio.', 'li' => ['Fale ou escreva o que o cliente pediu', 'Sai um orçamento pronto para enviar', 'Você é avisado quando o cliente abre'], 'img' => img('02-oficina')],
 ['t' => 'prod', 'sel' => 'Para quem quer alguém ao lado', 'h' => 'Método Perfeitto', 'p' => 'Inteligência, processo e automação, nessa ordem.', 'li' => ['Acompanho você', 'Faço com você', 'Faço por você'], 'img' => img('05-consultoria')],
 ['t' => 'fim', 'sel' => 'Convite', 'h' => 'Diagnóstico gratuito <em>de 15 minutos.</em>', 'p' => 'A gente mostra onde a inteligência artificial pode devolver tempo e vendas ao seu negócio. Pelo WhatsApp, sem compromisso.', 'img' => img('04-equipe-painel')],
];
?><!doctype html><html lang="pt-BR"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="robots" content="noindex">
<title>Apresentação · Perfeitto Digital</title><link rel="stylesheet" href="assets/style.css?v=<?= V ?>"><link rel="stylesheet" href="assets/slides.css?v=<?= V ?>"></head>
<body class="deckbody">
<div class="deck" id="deck" data-auto="<?= $auto ? 1 : 0 ?>">
<?php foreach ($slides as $i => $s): ?>
<section class="sl sl-<?= $s['t'] ?><?= $i === 0 ? ' on' : '' ?>">
  <i class="fx f1"></i><i class="fx f2"></i><i class="fx f3"></i><i class="canto c1"></i><i class="canto c2"></i>
  <img class="logo" src="assets/logo-preta.png" alt="Perfeitto Digital">
  <div class="divisor"><b></b><s></s><s></s><b></b></div>
  <div class="txt">
    <?php if (!empty($s['sel'])): ?><span class="selo"><?= e($s['sel']) ?></span><?php endif; ?>
    <h1><?= $s['h'] ?></h1>
    <?php if (!empty($s['p'])): ?><p><?= e($s['p']) ?></p><?php endif; ?>
    <?php if (!empty($s['li'])): ?><ul><?php foreach ($s['li'] as $l) echo '<li>' . e($l) . '</li>'; ?></ul><?php endif; ?>
    <?php if (!empty($s['itens'])): ?><div class="dores"><?php foreach ($s['itens'] as $d): ?><div><i><?= $d[0] ?></i><b><?= e($d[1]) ?></b><span><?= e($d[2]) ?></span></div><?php endforeach; ?></div><?php endif; ?>
    <?php if ($s['t'] === 'fim'): ?><div class="qrbox"><img src="assets/qr-whatsapp.svg" alt="QR code do WhatsApp"><div><b>Aponte a câmera</b><span>ou fale com a nossa equipe aqui no estande</span></div></div><?php endif; ?>
  </div>
  <div class="foto" style="background-image:url(<?= e($s['img']) ?>)"></div><div class="barra"></div>
  <footer>perfeittodigital.com.br · @perfeittodigital</footer>
</section>
<?php endforeach; ?>
<div class="ctl"><button id="ant" aria-label="Anterior">‹</button><span id="num">1 / <?= count($slides) ?></span><button id="prox" aria-label="Próximo">›</button><button id="tela" aria-label="Tela cheia">⛶</button><a href="?p=material" aria-label="Fechar">✕</a></div>
</div>
<script>
(function(){var s=[].slice.call(document.querySelectorAll('.sl')),i=0,d=document.getElementById('deck'),t;
function ir(n){s[i].classList.remove('on');i=(n+s.length)%s.length;s[i].classList.add('on');document.getElementById('num').textContent=(i+1)+' / '+s.length;}
function auto(){clearInterval(t);if(d.dataset.auto==='1')t=setInterval(function(){ir(i+1)},9000);}
document.getElementById('prox').onclick=function(){ir(i+1);auto()};document.getElementById('ant').onclick=function(){ir(i-1);auto()};
document.getElementById('tela').onclick=function(){document.fullscreenElement?document.exitFullscreen():document.documentElement.requestFullscreen();};
document.addEventListener('keydown',function(e){if(['ArrowRight','PageDown',' ','Enter'].indexOf(e.key)>-1){ir(i+1);auto()}if(['ArrowLeft','PageUp'].indexOf(e.key)>-1){ir(i-1);auto()}if(e.key==='a'){d.dataset.auto=d.dataset.auto==='1'?'0':'1';auto()}if(e.key==='f')document.getElementById('tela').click();});
var x0=null;d.addEventListener('touchstart',function(e){x0=e.touches[0].clientX});d.addEventListener('touchend',function(e){if(x0===null)return;var dx=e.changedTouches[0].clientX-x0;if(Math.abs(dx)>50){ir(i+(dx<0?1:-1));auto()}x0=null});
var m;function mostra(){d.classList.add('mexe');clearTimeout(m);m=setTimeout(function(){d.classList.remove('mexe')},2500)}d.addEventListener('mousemove',mostra);d.addEventListener('touchstart',mostra);
auto();})();
</script></body></html>
