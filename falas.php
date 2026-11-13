<?php
function cartao($emoji, $titulo, $tempo, $html, $aberto = false) {
  echo '<details class="cartao"' . ($aberto ? ' open' : '') . '><summary><i>' . $emoji . '</i><span><b>' . $titulo . '</b>' . ($tempo ? '<small>' . $tempo . '</small>' : '') . '</span><em></em></summary><div class="corpo">' . $html . '</div></details>';
}
?>
<h1>O que falar</h1>
<p class="sub">Fonte oficial da Perfeitto Digital, aprovada pelo Edgard em 13/11/2026. Toque em um cartão para abrir. Toque de novo para fechar.</p>

<section class="bordao"><small>Bordão oficial</small><p>Inteligência artificial qualquer um vende.<br><b>A gente entrega funcionando.</b></p></section>

<div class="acoes"><button type="button" class="bt claro" id="abrirtudo">Abrir tudo</button><button type="button" class="bt claro" id="fechartudo">Fechar tudo</button></div>

<h2 class="grupo">📌 O que a gente resolve</h2>
<?php cartao('🔥', 'As três dores', 'Para reconhecer na conversa', '<ol class="dores"><li><b>Atendimento sufocado.</b> Cliente esperando resposta.</li><li><b>Venda que se perde</b> no meio do caminho.</li><li><b>Tudo passa pelo dono,</b> que fica sem tempo.</li></ol>', true); ?>

<h2 class="grupo">🎯 O que a gente faz</h2>
<?php
cartao('1️⃣', 'Uma frase', '5 segundos', '<p class="dizer">A gente faz pequenas empresas venderem mais e atenderem melhor, sem depender do dono para tudo.</p><p class="dizer">Usamos inteligência artificial, e a implantação pode ficar com a gente.</p>');
cartao('2️⃣', 'Resposta curta', '15 segundos', '<p class="dizer">Sabe aquele negócio em que tudo passa pelo dono? Cliente esperando no WhatsApp, venda que se perde, dono sem tempo.</p><p class="dizer">A gente resolve isso. Entendemos como a empresa funciona, organizamos o processo e implantamos inteligência artificial e automação.</p><p class="dizer">E você não precisa se virar sozinho com a ferramenta. Se quiser, a gente faz com você ou faz por você.</p>');
cartao('3️⃣', 'Resposta completa', '30 segundos', '<p class="nota">Fale a resposta curta e emende com este final:</p><p class="dizer">Eu vivi isso na minha empresa de eventos, que tem mais de 15 anos. Resolvi lá primeiro. Hoje levo o mesmo método para outros negócios.</p><p class="dizer">E no seu, o que mais depende de você hoje?</p><p class="nota">A pergunta do final devolve a conversa e abre o cadastro do lead.</p>');
cartao('💬', 'Se perguntarem: o que vocês vendem?', '15 segundos', '<p class="dizer">Vendemos a solução funcionando dentro do seu negócio. Diagnóstico, método e as ferramentas certas.</p><p class="dizer">Você escolhe o quanto quer que a gente faça: a gente faz por você, faz com você ou acompanha você.</p><p class="dizer">E se preferir só a ferramenta para usar por conta própria, também tem.</p><p class="nota">Sem preço no estande. Preço só depois do diagnóstico.</p>');
?>

<h2 class="grupo">🎤 Entrevista</h2>
<?php
cartao('🎙', 'Fala da entrevista', 'Cerca de 40 segundos', '<p class="dizer">Todo mundo está vendendo inteligência artificial. Pouca gente está implantando. O pequeno empresário compra a ferramenta, não tem tempo de aprender, e ela fica parada.</p><p class="dizer">Eu sei porque eu era esse empresário. Tenho uma empresa de eventos há mais de 15 anos e vivia sufocado. WhatsApp lotado, venda perdida, sem tempo para nada. Resolvi isso dentro do meu negócio, com inteligência artificial aplicada de forma simples.</p><p class="dizer">Daí nasceu a Perfeitto Digital. A gente entra na empresa, entende como ela funciona e implanta. Com o dono ou pelo dono. Para vender mais, atender melhor e depender menos dele.</p><p class="dizer forte">Inteligência artificial qualquer um vende. A gente entrega funcionando.</p>');
cartao('⚡', 'Versão curta da entrevista', '15 segundos', '<p class="dizer">Todo mundo vende inteligência artificial. A gente implanta.</p><p class="dizer">Entra na pequena empresa, entende o negócio e deixa funcionando, para ela vender mais, atender melhor e depender menos do dono.</p><p class="dizer forte">Inteligência artificial qualquer um vende. A gente entrega funcionando.</p>');
?>

<h2 class="grupo">🧭 Soluções, na ordem do funil</h2>
<?php
cartao('🪜', 'Os seis produtos', 'Do primeiro passo ao serviço principal', '<ol class="funil"><li><b>Método NPV, Não Perca Vendas</b><span>A porta de entrada. Método guiado para recuperar as vendas esquecidas no WhatsApp.</span></li><li><b>WhaBoost</b><span>O sistema que organiza leads, retornos e o funil de vendas no WhatsApp.</span></li><li><b>Perfeitto IA</b><span>O funcionário de inteligência artificial que atende no WhatsApp.</span></li><li><b>OrçaZap</b><span>Orçamento rápido pelo WhatsApp, pensado para quem trabalha com eventos.</span></li><li><b>WePoupe</b><span>Organização financeira pelo WhatsApp.</span></li><li><b>Método Perfeitto</b><span>O serviço principal. Inteligência, processo e automação dentro da empresa.</span></li></ol>');
cartao('🤝', 'Como o cliente contrata', 'A escolha é sempre dele', '<ul class="modos"><li><b>Faço por Você</b><span>A Perfeitto Digital assume a execução.</span></li><li><b>Faço com Você</b><span>A gente estrutura e implanta junto com a equipe do cliente.</span></li><li><b>Acompanho Você</b><span>O cliente executa, com a nossa orientação.</span></li><li><b>Sistema com implantação</b><span>O cliente compra o sistema e a gente deixa configurado e rodando.</span></li><li><b>Só o sistema</b><span>O cliente compra e usa por conta própria.</span></li></ul><p class="nota">Tudo o que vendemos pode ser implantado por nós, se o cliente quiser. Ou ele compra e usa sozinho. O nosso foco é entregar funcionando.</p>');
cartao('🗣', 'Frase para explicar o caminho', '10 segundos', '<p class="dizer">Começa pequeno, descobrindo onde perde venda. Depois organiza com sistema. E quando quiser transformar a operação, a gente entra junto.</p>');
?>

<h2 class="grupo">⚠️ Cuidados</h2>
<?php cartao('🛑', 'O que não falar', 'Leia uma vez antes de começar', '<ul><li>Não citar resultado de cliente da Perfeitto Digital. A prova é o nosso próprio negócio.</li><li>"Pouca gente está implantando" é opinião. Sem número.</li><li>Formação do Edgard: estudou Sistemas de Informação na PUC Minas.</li><li>Mais de 15 anos é a Perfeitto Produções. A Perfeitto Digital tem cerca de 3 anos.</li><li>Sem preço no estande.</li></ul>'); ?>

<p class="sub"><a href="?p=roteiro">Ver o roteiro de abordagem</a> · <a href="?p=material">Voltar ao material</a></p>
<script>
(function(){var d=document.querySelectorAll('details.cartao');
document.getElementById('abrirtudo').onclick=function(){d.forEach(function(x){x.open=true})};
document.getElementById('fechartudo').onclick=function(){d.forEach(function(x){x.open=false})};})();
</script>
