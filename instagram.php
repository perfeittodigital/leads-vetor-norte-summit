<?php
$tags = "\n\n#perfeittodigital #inteligenciaartificial #pequenosnegocios #automacao #whatsappbusiness #vendas #lagoasanta #belohorizonte";
$posts = [
 ['post-01-apresentacao', 'Post 1 · Apresentação', "Chegamos. 👋\n\nA Perfeitto Digital usa inteligência artificial para o pequeno negócio vender mais e ganhar tempo. Sem complicação e no WhatsApp que você já usa.\n\nSegue a gente para ver como isso funciona na prática."],
 ['post-02-voce-se-reconhece', 'Post 2 · Você se reconhece?', "WhatsApp lotado, falta de tempo, tudo dependendo de você, orçamento que demora.\n\nSe você se reconheceu em pelo menos um, este perfil é para você. Qual desses mais pesa no seu dia? Conta aqui nos comentários."],
 ['post-03-quem-somos', 'Post 3 · Quem somos', "A gente também é pequeno empresário.\n\nTrabalhamos com eventos há mais de 15 anos e sentimos na pele o que é ter tudo dependendo do dono. Por isso, tudo o que oferecemos, usamos primeiro no nosso próprio negócio.\n\nA gente aplica antes de ensinar."],
 ['post-04-o-que-a-ia-faz', 'Post 4 · O que a IA faz por você', "Inteligência artificial não é coisa de empresa grande.\n\nÉ ter mais tempo para o que importa, não esquecer nenhum cliente e viver o negócio com menos estresse.\n\nQuer saber por onde começar? Chama a gente no link da bio."],
 ['post-05-whaboost', 'Post 5 · WhaBoost', "Quantas vendas ficaram esquecidas no meio das conversas?\n\nO WhaBoost organiza o seu WhatsApp: contatos em ordem, lembrete para dar retorno e cada venda em andamento à vista.\n\nChama no link da bio e veja funcionando."],
 ['post-06-perfeitto-ia', 'Post 6 · Perfeitto IA', "Cliente chamou às 22h47. Quem respondeu?\n\nCom o Perfeitto IA, o seu WhatsApp ganha um funcionário de inteligência artificial que atende a qualquer hora, do seu jeito. E você entra na conversa quando quiser.\n\nChama no link da bio."],
 ['post-07-orcazap', 'Post 7 · OrçaZap', "Orçamento que demora perde cliente.\n\nCom o OrçaZap, você fala o que o cliente pediu e o orçamento sai pronto, profissional, direto no WhatsApp. E ainda avisa quando o cliente abre.\n\nChama no link da bio."],
 ['post-08-metodo-perfeitto', 'Post 8 · Método Perfeitto', "Não sabe por onde começar? A gente vai junto.\n\nNo Método Perfeitto, primeiro entendemos o seu negócio, depois organizamos o processo e só então colocamos a tecnologia certa.\n\nAcompanho você, faço com você ou faço por você."],
 ['post-09-vetor-norte-summit', 'Post 9 · Vetor Norte Summit (publicar hoje)', "Estamos no Vetor Norte Summit! 📍\n\nSexta e sábado, 13 e 14 de novembro, das 9h às 21h, em Lagoa Santa. Passe na nossa bancada e descubra o que a inteligência artificial pode fazer pelo seu negócio.\n\nTe esperamos lá."],
 ['post-10-diagnostico-gratuito', 'Post 10 · Diagnóstico gratuito', "Um convite para você. 💙\n\nDiagnóstico gratuito de 15 minutos, pelo WhatsApp: a gente mostra onde a inteligência artificial pode devolver tempo e vendas ao seu negócio. Sem compromisso.\n\nChama no link da bio."],
];
$perfil = [['avatar-instagram', 'Foto de perfil do Instagram'], ['avatar-whatsapp', 'Foto de perfil do WhatsApp'], ['avatar-escuro-alternativo', 'Foto de perfil, versão escura'], ['capa-destaque-solucoes', 'Capa de destaque · Soluções'], ['capa-destaque-quem-somos', 'Capa de destaque · Quem somos'], ['capa-destaque-diagnostico', 'Capa de destaque · Diagnóstico'], ['capa-destaque-eventos', 'Capa de destaque · Eventos'], ['capa-destaque-dicas', 'Capa de destaque · Dicas']];
$stories = [['story-quem-somos.mp4', 'Story e Reels · Quem somos'], ['story-ia-no-dia-a-dia.mp4', 'Story e Reels · IA no dia a dia']];
?>
<h1>Instagram e WhatsApp</h1>
<p class="sub">Material pronto para publicar, no padrão da marca. Toque em baixar, publique e copie a legenda.</p>
<section class="bloco destaque"><h2>Bio sugerida</h2>
<p class="fala" id="bio">IA e automação para o pequeno negócio vender mais e ganhar tempo ⚡<br>📲 WhaBoost · Perfeitto IA · OrçaZap<br>🤝 A gente aplica antes de ensinar<br>📍 Lagoa Santa e BH<br>👇 Diagnóstico gratuito de 15 min</p>
<button class="bt claro" data-copia="bio">Copiar bio</button></section>
<h2>Vídeos verticais (Story e Reels)</h2>
<div class="grade"><?php foreach ($stories as $v): if (!file_exists(__DIR__ . '/videos/' . $v[0])) continue; ?><div class="mat video"><video src="videos/<?= $v[0] ?>" controls muted playsinline preload="metadata" style="aspect-ratio:9/16;max-height:420px;margin:0 auto"></video><b><?= $v[1] ?></b><a class="bt claro" href="videos/<?= $v[0] ?>" download>Baixar vídeo</a></div><?php endforeach; ?></div>
<h2>Posts do feed (1080 x 1350)</h2>
<p class="sub">Ordem sugerida de publicação: do 1 ao 10. O post 9 é do evento e pode sair hoje.</p>
<div class="grade"><?php foreach ($posts as $i => $x): ?><div class="mat"><img src="insta/<?= $x[0] ?>.jpg" alt="<?= e($x[1]) ?>" loading="lazy" style="width:100%;border-radius:8px"><b><?= e($x[1]) ?></b><p class="fala" id="leg<?= $i ?>" style="white-space:pre-line;font-size:13.5px"><?= e($x[2] . $tags) ?></p><div style="display:flex;gap:8px;flex-wrap:wrap"><a class="bt claro" href="insta/<?= $x[0] ?>.jpg" download>Baixar imagem</a><button class="bt claro" data-copia="leg<?= $i ?>">Copiar legenda</button></div></div><?php endforeach; ?></div>
<h2>Perfil e destaques</h2>
<div class="grade"><?php foreach ($perfil as $x): ?><div class="mat"><img src="insta/<?= $x[0] ?>.jpg" alt="<?= e($x[1]) ?>" loading="lazy" style="width:100%;max-width:240px;border-radius:8px"><b><?= e($x[1]) ?></b><a class="bt claro" href="insta/<?= $x[0] ?>.jpg" download>Baixar imagem</a></div><?php endforeach; ?></div>
