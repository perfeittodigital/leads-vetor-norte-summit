<?php
require __DIR__ . '/lib.php';
$p = preg_replace('/[^a-z]/', '', (string)($_GET['p'] ?? ''));
$u = eu();
$tipos = ['Comércio / Loja', 'Serviços', 'Alimentação', 'Beleza e Estética', 'Saúde', 'Educação', 'Eventos', 'Indústria', 'Tecnologia', 'Autônomo / Profissional Liberal', 'Outro'];

/* ---------- ações ---------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $a = (string)($_POST['acao'] ?? '');
    if ($a === 'login') {
        if (bloqueado()) ir('login', 'Muitas tentativas. Aguarde 10 minutos e tente de novo.');
        $st = db()->prepare('SELECT * FROM usuarios WHERE usuario=? AND ativo=1');
        $st->execute([mb_strtolower(trim((string)($_POST['usuario'] ?? '')))]);
        $x = $st->fetch();
        if ($x && password_verify((string)($_POST['senha'] ?? ''), $x['senha'])) {
            session_regenerate_id(true);
            $_SESSION['uid'] = $x['id'];
            ir($x['trocar_senha'] ? 'senha' : 'novo');
        }
        db()->prepare('INSERT INTO tentativas (ip, quando) VALUES (?,?)')->execute([ip(), time()]);
        ir('login', 'Usuário ou senha incorretos.');
    }
    if (!$u) { if (ajax()) json_out(['ok' => false, 'erro' => 'login'], 401); ir('login'); }
    if (!csrf_ok()) { if (ajax()) json_out(['ok' => false, 'erro' => 'csrf', 'csrf' => csrf()], 403); ir('novo', 'Sessão expirada. Tente de novo.'); }

    if ($a === 'senha') {
        $n = (string)($_POST['nova'] ?? '');
        if (mb_strlen($n) < 6) ir('senha', 'A senha precisa de pelo menos 6 caracteres.');
        if ($n !== (string)($_POST['nova2'] ?? '')) ir('senha', 'As duas senhas não são iguais.');
        db()->prepare('UPDATE usuarios SET senha=?, trocar_senha=0 WHERE id=?')->execute([password_hash($n, PASSWORD_BCRYPT), $u['id']]);
        ir('novo', 'Senha alterada.');
    }
    if ($a === 'lead') {
        $id = (int)($_POST['id'] ?? 0);
        $d = [titulo((string)($_POST['nome'] ?? '')), fone((string)($_POST['whatsapp'] ?? '')), mb_strtolower(preg_replace('/\s+/', '', (string)($_POST['email'] ?? ''))), titulo((string)($_POST['empresa'] ?? '')), trim((string)($_POST['tipo_negocio'] ?? '')), trim((string)($_POST['observacao'] ?? '')), in_array($_POST['interesse'] ?? '', ['quente', 'morno', 'frio'], true) ? $_POST['interesse'] : ''];
        if (implode('', array_slice($d, 0, 6)) === '') { if (ajax()) json_out(['ok' => false, 'erro' => 'vazio'], 422); ir('novo', 'Preencha pelo menos um campo.'); }
        if ($id) {
            $st = db()->prepare('SELECT usuario_id FROM leads WHERE id=?'); $st->execute([$id]); $dono = $st->fetchColumn();
            if ($dono === false || (!gestor() && (int)$dono !== (int)$u['id'])) ir('leads', 'Você só pode editar os seus leads.');
            db()->prepare('UPDATE leads SET nome=?, whatsapp=?, email=?, empresa=?, tipo_negocio=?, observacao=?, interesse=?, atualizado_em=? WHERE id=?')->execute([...$d, date('Y-m-d H:i:s'), $id]);
            ir('leads', 'Lead atualizado.');
        }
        $quando = date('Y-m-d H:i:s');
        if (!empty($_POST['quando']) && preg_match('/^\d{4}-\d\d-\d\d \d\d:\d\d:\d\d$/', (string)$_POST['quando'])) $quando = (string)$_POST['quando'];
        db()->prepare('INSERT INTO leads (nome, whatsapp, email, empresa, tipo_negocio, observacao, interesse, evento, usuario_id, criado_em) VALUES (?,?,?,?,?,?,?,?,?,?)')->execute([...$d, EVENTO, $u['id'], $quando]);
        $st = db()->prepare('SELECT COUNT(*) FROM leads WHERE usuario_id=?'); $st->execute([$u['id']]);
        if (ajax()) json_out(['ok' => true, 'meus' => (int)$st->fetchColumn()]);
        ir('novo', 'Lead salvo.');
    }
    if ($a === 'contatado') {
        db()->prepare('UPDATE leads SET contatado=?, atualizado_em=? WHERE id=?')->execute([(int)!empty($_POST['v']), date('Y-m-d H:i:s'), (int)($_POST['id'] ?? 0)]);
        if (ajax()) json_out(['ok' => true]);
        ir('leads');
    }
    if ($a === 'excluirlead' && gestor()) {
        if (mb_strtolower(trim((string)($_POST['confirma'] ?? ''))) !== 'excluir') ir('leads', 'Para excluir, digite a palavra excluir.');
        db()->prepare('DELETE FROM leads WHERE id=?')->execute([(int)($_POST['id'] ?? 0)]);
        ir('leads', 'Lead excluído.');
    }
    if ($a === 'usuario' && gestor()) {
        $nome = titulo((string)($_POST['nome'] ?? '')); $us = mb_strtolower(preg_replace('/[^a-zA-Z0-9._-]/', '', (string)($_POST['usuario'] ?? ''))); $se = (string)($_POST['senha'] ?? '');
        $pa = in_array($_POST['papel'] ?? '', ['usuario', 'gestor', 'admin'], true) ? $_POST['papel'] : 'usuario';
        if ($pa === 'admin' && !admin()) $pa = 'usuario';
        if ($nome === '' || $us === '' || mb_strlen($se) < 6) ir('usuarios', 'Informe nome, usuário e uma senha com 6 caracteres ou mais.');
        try { db()->prepare('INSERT INTO usuarios (nome, usuario, senha, papel, criado_em) VALUES (?,?,?,?,?)')->execute([$nome, $us, password_hash($se, PASSWORD_BCRYPT), $pa, date('Y-m-d H:i:s')]); }
        catch (Throwable $t) { ir('usuarios', 'Esse nome de usuário já existe.'); }
        ir('usuarios', 'Usuário criado. No primeiro acesso ele troca a senha.');
    }
    if ($a === 'usuarioestado' && gestor()) {
        $id = (int)($_POST['id'] ?? 0);
        $st = db()->prepare('SELECT * FROM usuarios WHERE id=?'); $st->execute([$id]); $x = $st->fetch();
        if (!$x || $id === (int)$u['id'] || ($x['papel'] === 'admin' && !admin())) ir('usuarios', 'Ação não permitida.');
        if (empty($_POST['alternar'])) {
            if (mb_strlen((string)($_POST['novasenha'] ?? '')) < 6) ir('usuarios', 'Digite uma senha provisória com 6 caracteres ou mais.');
            db()->prepare('UPDATE usuarios SET senha=?, trocar_senha=1 WHERE id=?')->execute([password_hash((string)$_POST['novasenha'], PASSWORD_BCRYPT), $id]);
            ir('usuarios', 'Senha redefinida.');
        }
        db()->prepare('UPDATE usuarios SET ativo=? WHERE id=?')->execute([$x['ativo'] ? 0 : 1, $id]);
        ir('usuarios', $x['ativo'] ? 'Usuário desativado.' : 'Usuário reativado.');
    }
    if ($a === 'material' && gestor()) {
        $t = trim((string)($_POST['titulo'] ?? '')); $l = trim((string)($_POST['link'] ?? ''));
        if ($t === '' || !preg_match('#^https?://#i', $l)) ir('material', 'Informe o título e um link começando com https://');
        db()->prepare('INSERT INTO materiais (titulo, descricao, link, usuario_id, criado_em) VALUES (?,?,?,?,?)')->execute([$t, trim((string)($_POST['descricao'] ?? '')), $l, $u['id'], date('Y-m-d H:i:s')]);
        ir('material', 'Material adicionado.');
    }
    if ($a === 'excluirmaterial' && gestor()) { db()->prepare('DELETE FROM materiais WHERE id=?')->execute([(int)($_POST['id'] ?? 0)]); ir('material', 'Material removido.'); }
    ir('novo');
}

if ($p === 'sair') { $_SESSION = []; session_destroy(); header('Location: ?p=login'); exit; }
if (!$u) $p = 'login';
elseif ($p === '' || $p === 'login') $p = 'novo';
if ($u && $u['trocar_senha'] && $p !== 'senha') $p = 'senha';

if ($p === 'csv' && gestor()) {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="leads-vetor-norte-summit-' . date('Ymd-Hi') . '.csv"');
    $o = fopen('php://output', 'w'); fwrite($o, "\xEF\xBB\xBF");
    fputcsv($o, ['Data', 'Nome', 'WhatsApp', 'E-mail', 'Empresa', 'Tipo de negócio', 'Interesse', 'Observação', 'Contatado', 'Cadastrado por'], ';');
    foreach (db()->query('SELECT l.*, us.nome AS quem FROM leads l JOIN usuarios us ON us.id=l.usuario_id ORDER BY l.criado_em') as $l) fputcsv($o, [$l['criado_em'], $l['nome'], $l['whatsapp'], $l['email'], $l['empresa'], $l['tipo_negocio'], $l['interesse'], $l['observacao'], $l['contatado'] ? 'sim' : 'não', $l['quem']], ';');
    exit;
}
if ($p === 'apresentacao') { require __DIR__ . '/apresentacao.php'; exit; }

$msg = $_SESSION['msg'] ?? ''; unset($_SESSION['msg']);
$titulos = ['login' => 'Entrar', 'novo' => 'Novo lead', 'leads' => 'Leads', 'painel' => 'Painel', 'material' => 'Material', 'roteiro' => 'Roteiro', 'guia' => 'Guia do estande', 'usuarios' => 'Usuários', 'senha' => 'Trocar senha', 'editar' => 'Editar lead'];
if (!isset($titulos[$p])) $p = 'novo';
?><!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<meta name="robots" content="noindex, nofollow">
<meta name="theme-color" content="#1B1F24">
<title><?= e($titulos[$p]) ?> · Leads Perfeitto Digital</title>
<link rel="manifest" href="manifest.json">
<link rel="icon" href="assets/icone-192.png">
<link rel="apple-touch-icon" href="assets/icone-192.png">
<link rel="stylesheet" href="assets/style.css?v=<?= V ?>">
</head>
<body class="p-<?= e($p) ?>">
<?php if ($p === 'login'): ?>
<main class="login">
  <i class="canto c1"></i><i class="canto c2"></i>
  <form method="post" class="cartao">
    <img class="logo" src="assets/logo-preta.png" alt="Perfeitto Digital">
    <div class="divisor"><b></b><s></s><s></s><b></b></div>
    <h1>Leads do <?= e(EVENTO) ?></h1>
    <?php if ($msg): ?><p class="aviso"><?= e($msg) ?></p><?php endif; ?>
    <input type="hidden" name="acao" value="login">
    <label>Usuário<input name="usuario" autocomplete="username" autocapitalize="none" required></label>
    <label>Senha<span class="olho"><input name="senha" type="password" autocomplete="current-password" required><button type="button" data-olho aria-label="Mostrar senha">👁</button></span></label>
    <button class="bt">Entrar</button>
    <p class="rod">Uso interno da equipe Perfeitto Digital</p>
  </form>
</main>
<?php else: ?>
<header class="topo">
  <i class="canto c1"></i>
  <a href="?p=novo"><img class="logo" src="assets/logo-preta.png" alt="Perfeitto Digital"></a>
  <div class="eu"><span><?= e(explode(' ', $u['nome'])[0]) ?></span><a href="?p=sair" class="sair">Sair</a></div>
</header>
<main class="pagina">
<div id="offline" class="aviso" hidden>Sem internet. Os leads ficam guardados neste aparelho e sobem sozinhos quando a conexão voltar. <b id="fila"></b></div>
<?php if ($msg): ?><p class="aviso ok"><?= e($msg) ?></p><?php endif; ?>

<?php if ($p === 'senha'): ?>
  <h1>Crie a sua senha</h1>
  <p class="sub">É o seu primeiro acesso. Escolha uma senha só sua, com 6 caracteres ou mais.</p>
  <form method="post" class="cartao"><input type="hidden" name="csrf" value="<?= csrf() ?>"><input type="hidden" name="acao" value="senha">
    <label>Nova senha<input name="nova" type="password" minlength="6" required></label>
    <label>Repita a senha<input name="nova2" type="password" minlength="6" required></label>
    <button class="bt">Salvar senha</button></form>

<?php elseif ($p === 'novo' || $p === 'editar'):
  $l = ['id' => 0, 'nome' => '', 'whatsapp' => '', 'email' => '', 'empresa' => '', 'tipo_negocio' => '', 'observacao' => '', 'interesse' => ''];
  if ($p === 'editar') { $st = db()->prepare('SELECT * FROM leads WHERE id=?'); $st->execute([(int)($_GET['id'] ?? 0)]); $l = $st->fetch() ?: $l; }
  $st = db()->prepare("SELECT COUNT(*) FROM leads WHERE usuario_id=?"); $st->execute([$u['id']]); $meus = (int)$st->fetchColumn();
  $st = db()->prepare("SELECT COUNT(*) FROM leads WHERE usuario_id=? AND date(criado_em)=date('now','localtime')"); $st->execute([$u['id']]); $hoje = (int)$st->fetchColumn(); ?>
  <?php if ($p === 'novo'): ?>
  <div class="placar"><div><b id="meus"><?= $meus ?></b><span>meus leads</span></div><div><b id="hoje"><?= $hoje ?></b><span>hoje</span></div><a href="?p=roteiro" class="bt claro">Ver roteiro</a></div>
  <?php endif; ?>
  <h1><?= $p === 'novo' ? 'Novo lead' : 'Editar lead' ?></h1>
  <p class="sub">Nenhum campo é obrigatório. O sistema arruma a escrita enquanto você digita.</p>
  <form method="post" class="cartao" id="formlead" autocomplete="off">
    <input type="hidden" name="csrf" value="<?= csrf() ?>"><input type="hidden" name="acao" value="lead"><input type="hidden" name="id" value="<?= (int)$l['id'] ?>">
    <label>Nome<input name="nome" data-f="titulo" value="<?= e($l['nome']) ?>" placeholder="Maria Souza" autocapitalize="words"></label>
    <label>WhatsApp<input name="whatsapp" data-f="fone" inputmode="tel" value="<?= e($l['whatsapp']) ?>" placeholder="(31) 98888-7777"><small class="dica" data-dica="fone"></small></label>
    <label>E-mail<input name="email" data-f="email" inputmode="email" autocapitalize="none" value="<?= e($l['email']) ?>" placeholder="maria@empresa.com.br"><small class="dica" data-dica="email"></small></label>
    <label>Nome da empresa<input name="empresa" data-f="titulo" value="<?= e($l['empresa']) ?>" placeholder="Padaria Bom Dia"></label>
    <label>Tipo de negócio<input name="tipo_negocio" list="tipos" value="<?= e($l['tipo_negocio']) ?>" placeholder="Escolha ou escreva"><datalist id="tipos"><?php foreach ($tipos as $t) echo '<option value="' . e($t) . '">'; ?></datalist></label>
    <div class="campo"><span>Interesse</span><div class="chips"><?php foreach (['quente' => '🔥 Quente', 'morno' => '🙂 Morno', 'frio' => '❄️ Frio'] as $k => $r): ?><label><input type="radio" name="interesse" value="<?= $k ?>" <?= $l['interesse'] === $k ? 'checked' : '' ?>><i><?= $r ?></i></label><?php endforeach; ?></div></div>
    <label>Observação (a dor que a pessoa contou)<textarea name="observacao" rows="2" placeholder="Perde venda no WhatsApp, demora no orçamento..."><?= e($l['observacao']) ?></textarea></label>
    <button class="bt"><?= $p === 'novo' ? 'Salvar lead' : 'Salvar alterações' ?></button>
  </form>
  <div id="salvo" class="salvo" hidden>✓ Lead salvo</div>

<?php elseif ($p === 'leads'):
  $q = trim((string)($_GET['q'] ?? '')); $de = (int)($_GET['de'] ?? 0); $ord = (string)($_GET['ord'] ?? 'recentes'); $so = (string)($_GET['so'] ?? '');
  $ordens = ['recentes' => 'l.criado_em DESC', 'antigos' => 'l.criado_em ASC', 'nome' => 'l.nome COLLATE NOCASE ASC', 'nomez' => 'l.nome COLLATE NOCASE DESC', 'empresa' => 'l.empresa COLLATE NOCASE ASC'];
  $w = ['1=1']; $par = [];
  if ($q !== '') { $w[] = '(l.nome LIKE ? OR l.empresa LIKE ? OR l.whatsapp LIKE ? OR l.email LIKE ? OR l.tipo_negocio LIKE ?)'; array_push($par, "%$q%", "%$q%", "%$q%", "%$q%", "%$q%"); }
  if ($de) { $w[] = 'l.usuario_id=?'; $par[] = $de; }
  if ($so === 'pendentes') $w[] = 'l.contatado=0'; if ($so === 'contatados') $w[] = 'l.contatado=1'; if (in_array($so, ['quente', 'morno', 'frio'], true)) { $w[] = 'l.interesse=?'; $par[] = $so; }
  $st = db()->prepare('SELECT l.*, us.nome AS quem FROM leads l JOIN usuarios us ON us.id=l.usuario_id WHERE ' . implode(' AND ', $w) . ' ORDER BY ' . ($ordens[$ord] ?? $ordens['recentes']) . ' LIMIT 1000'); $st->execute($par); $leads = $st->fetchAll();
  $usuarios = db()->query('SELECT id, nome FROM usuarios ORDER BY nome')->fetchAll(); ?>
  <h1>Leads <small>(<?= count($leads) ?>)</small></h1>
  <form class="filtros" method="get"><input type="hidden" name="p" value="leads">
    <input name="q" value="<?= e($q) ?>" placeholder="Buscar nome, empresa, telefone">
    <select name="de"><option value="0">Todos da equipe</option><?php foreach ($usuarios as $x): ?><option value="<?= $x['id'] ?>" <?= $de === (int)$x['id'] ? 'selected' : '' ?>><?= e($x['nome']) ?></option><?php endforeach; ?></select>
    <select name="so"><?php foreach (['' => 'Todos', 'pendentes' => 'A contatar', 'contatados' => 'Já contatados', 'quente' => 'Quentes', 'morno' => 'Mornos', 'frio' => 'Frios'] as $k => $r): ?><option value="<?= $k ?>" <?= $so === $k ? 'selected' : '' ?>><?= $r ?></option><?php endforeach; ?></select>
    <select name="ord"><?php foreach (['recentes' => 'Mais recentes', 'antigos' => 'Mais antigos', 'nome' => 'Nome A a Z', 'nomez' => 'Nome Z a A', 'empresa' => 'Empresa A a Z'] as $k => $r): ?><option value="<?= $k ?>" <?= $ord === $k ? 'selected' : '' ?>><?= $r ?></option><?php endforeach; ?></select>
    <button class="bt claro">Filtrar</button><?php if (gestor()): ?><a class="bt claro" href="?p=csv">Baixar planilha</a><?php endif; ?>
  </form>
  <?php if (!$leads): ?><p class="vazio">Nenhum lead por aqui ainda.</p><?php endif; ?>
  <div class="lista">
  <?php foreach ($leads as $l): $z = zap($l, $u['nome']); ?>
    <article class="lead <?= $l['contatado'] ? 'feito' : '' ?>">
      <div class="cab"><h3><?= e($l['nome'] ?: 'Sem nome') ?> <?= ['quente' => '🔥', 'morno' => '🙂', 'frio' => '❄️'][$l['interesse']] ?? '' ?></h3><time><?= date('d/m H:i', strtotime($l['criado_em'])) ?></time></div>
      <p class="emp"><?= e(trim($l['empresa'] . ($l['tipo_negocio'] ? ' · ' . $l['tipo_negocio'] : ''), ' ·')) ?></p>
      <p class="con"><?= e($l['whatsapp']) ?><?= $l['email'] ? ' · ' . e($l['email']) : '' ?></p>
      <?php if ($l['observacao']): ?><p class="obs"><?= e($l['observacao']) ?></p><?php endif; ?>
      <div class="pe"><span>por <?= e(explode(' ', $l['quem'])[0]) ?></span>
        <label class="feitoc"><input type="checkbox" data-contatado="<?= $l['id'] ?>" <?= $l['contatado'] ? 'checked' : '' ?>> contatado</label>
        <?php if (gestor() || (int)$l['usuario_id'] === (int)$u['id']): ?><a href="?p=editar&id=<?= $l['id'] ?>">Editar</a><?php endif; ?>
        <?php if (gestor()): ?><button type="button" class="link" data-excluir="<?= $l['id'] ?>" data-nome="<?= e($l['nome'] ?: 'este lead') ?>">Excluir</button><?php endif; ?>
        <?php if ($z): ?><a class="bt zap" target="_blank" rel="noopener" href="<?= e($z) ?>" data-zap="<?= $l['id'] ?>">WhatsApp</a><?php endif; ?>
      </div>
    </article>
  <?php endforeach; ?>
  </div>
  <dialog id="dlg"><form method="post"><input type="hidden" name="csrf" value="<?= csrf() ?>"><input type="hidden" name="acao" value="excluirlead"><input type="hidden" name="id">
    <h3>Excluir <span></span>?</h3><p>Essa ação não tem volta. Para confirmar, digite a palavra <b>excluir</b>.</p><input name="confirma" autocomplete="off" autocapitalize="none">
    <div class="acoes"><button type="button" class="bt claro" data-fechar>Cancelar</button><button class="bt perigo">Excluir</button></div></form></dialog>

<?php elseif ($p === 'painel'):
  $tot = (int)db()->query('SELECT COUNT(*) FROM leads')->fetchColumn();
  $hj = (int)db()->query("SELECT COUNT(*) FROM leads WHERE date(criado_em)=date('now','localtime')")->fetchColumn();
  $ct = (int)db()->query('SELECT COUNT(*) FROM leads WHERE contatado=1')->fetchColumn();
  $qt = (int)db()->query("SELECT COUNT(*) FROM leads WHERE interesse='quente'")->fetchColumn();
  $porU = db()->query("SELECT us.id, us.nome, COUNT(l.id) n, SUM(CASE WHEN date(l.criado_em)=date('now','localtime') THEN 1 ELSE 0 END) h, SUM(CASE WHEN l.contatado=1 THEN 1 ELSE 0 END) c FROM usuarios us LEFT JOIN leads l ON l.usuario_id=us.id WHERE us.ativo=1 GROUP BY us.id ORDER BY n DESC, us.nome")->fetchAll();
  $porT = db()->query("SELECT COALESCE(NULLIF(tipo_negocio,''),'Não informado') t, COUNT(*) n FROM leads GROUP BY t ORDER BY n DESC LIMIT 8")->fetchAll();
  $porD = db()->query("SELECT date(criado_em) d, COUNT(*) n FROM leads GROUP BY d ORDER BY d")->fetchAll();
  $porH = db()->query("SELECT strftime('%H', criado_em) h, COUNT(*) n FROM leads WHERE date(criado_em)=date('now','localtime') GROUP BY h ORDER BY h")->fetchAll();
  $mx = max(1, ...array_column($porU, 'n')); $mt = max(1, ...(array_column($porT, 'n') ?: [1])); $mh = max(1, ...(array_column($porH, 'n') ?: [1]));
  $meu = null; foreach ($porU as $i => $x) if ((int)$x['id'] === (int)$u['id']) { $meu = $x; $pos = $i + 1; } ?>
  <h1>Painel</h1>
  <section class="meu"><div><span>Seu progresso, <?= e(explode(' ', $u['nome'])[0]) ?></span><b><?= (int)($meu['n'] ?? 0) ?> leads</b><small><?= (int)($meu['h'] ?? 0) ?> hoje · <?= (int)($meu['c'] ?? 0) ?> contatados · <?= $pos ?? 1 ?>º na equipe</small></div><a class="bt" href="?p=novo">+ Novo lead</a></section>
  <div class="cards"><div><b><?= $tot ?></b><span>leads no total</span></div><div><b><?= $hj ?></b><span>hoje</span></div><div><b><?= $qt ?></b><span>quentes</span></div><div><b><?= $ct ?></b><span>contatados</span></div></div>
  <section class="bloco"><h2>Por pessoa</h2><?php foreach ($porU as $x): ?><div class="barra"><span><?= e(explode(' ', $x['nome'])[0]) ?></span><i><em style="width:<?= round($x['n'] / $mx * 100) ?>%"></em></i><b><?= (int)$x['n'] ?></b></div><?php endforeach; ?></section>
  <section class="bloco"><h2>Por tipo de negócio</h2><?php if (!$porT): ?><p class="vazio">Sem dados ainda.</p><?php endif; foreach ($porT as $x): ?><div class="barra"><span><?= e($x['t']) ?></span><i><em style="width:<?= round($x['n'] / $mt * 100) ?>%"></em></i><b><?= (int)$x['n'] ?></b></div><?php endforeach; ?></section>
  <section class="bloco"><h2>Hoje, por hora</h2><?php if (!$porH): ?><p class="vazio">Nenhum lead hoje ainda.</p><?php else: ?><div class="colunas"><?php foreach ($porH as $x): ?><div><b><?= (int)$x['n'] ?></b><i style="height:<?= max(6, round($x['n'] / $mh * 100)) ?>%"></i><span><?= e($x['h']) ?>h</span></div><?php endforeach; ?></div><?php endif; ?></section>
  <section class="bloco"><h2>Por dia</h2><?php if (!$porD): ?><p class="vazio">Sem dados ainda.</p><?php endif; foreach ($porD as $x): ?><div class="barra"><span><?= date('d/m', strtotime($x['d'])) ?></span><i><em style="width:<?= round($x['n'] / max(1, $tot) * 100) ?>%"></em></i><b><?= (int)$x['n'] ?></b></div><?php endforeach; ?></section>

<?php elseif ($p === 'material'):
  $mats = db()->query('SELECT * FROM materiais ORDER BY id DESC')->fetchAll();
  $vids = [['perfeitto-digital-quem-somos.mp4', 'Vídeo 1 · Quem somos', 'Apresentação da empresa, sem áudio, para rodar em repetição no notebook.'], ['perfeitto-digital-ia-no-dia-a-dia.mp4', 'Vídeo 2 · IA no dia a dia do pequeno negócio', 'Da dor à solução, passando pelos produtos. Sem áudio.']]; ?>
  <h1>Material do evento</h1>
  <p class="sub">Tudo o que a equipe usa no estande. Abra antes do evento para ficar disponível mesmo com internet fraca.</p>
  <div class="grade">
    <a class="mat" href="?p=roteiro"><b>🗣 Roteiro de abordagem</b><span>O que falar, em 5 momentos, com respostas para objeções.</span></a>
    <a class="mat" href="?p=apresentacao" target="_blank"><b>🖥 Apresentação em tela cheia</b><span>Slides para mostrar no notebook. Setas para navegar.</span></a>
    <a class="mat" href="?p=apresentacao&auto=1" target="_blank"><b>🔁 Apresentação automática</b><span>Roda sozinha quando ninguém está no estande.</span></a>
    <a class="mat" href="?p=guia"><b>📋 Guia do estande</b><span>O que levar, como se comportar, fotos e rotina dos dois dias.</span></a>
    <a class="mat" href="assets/banner-vetor-norte-summit.pdf" target="_blank"><b>🖼 Banner do evento</b><span>Arte aprovada, 71 x 91 cm, em PDF.</span></a>
    <a class="mat" href="assets/qr-whatsapp.svg" target="_blank"><b>📱 QR code do WhatsApp</b><span>Leva ao WhatsApp da Perfeitto Digital com mensagem pronta.</span></a>
  </div>
  <h2>Vídeos para o notebook</h2>
  <div class="grade">
  <?php foreach ($vids as $v): if (!file_exists(__DIR__ . '/videos/' . $v[0])) continue; ?>
    <div class="mat video"><video src="videos/<?= $v[0] ?>" controls loop muted playsinline preload="metadata"></video><b><?= $v[1] ?></b><span><?= $v[2] ?></span><a class="bt claro" href="videos/<?= $v[0] ?>" download>Baixar vídeo</a></div>
  <?php endforeach; ?>
  </div>
  <p class="sub">Dica: baixe os dois vídeos no notebook e abra em tela cheia com repetição ligada. Assim eles rodam sem internet.</p>
  <h2>Links da equipe</h2>
  <?php if (!$mats): ?><p class="vazio">Nenhum link adicionado.</p><?php endif; ?>
  <div class="grade"><?php foreach ($mats as $m): ?><div class="mat"><a href="<?= e($m['link']) ?>" target="_blank" rel="noopener"><b>🔗 <?= e($m['titulo']) ?></b></a><span><?= e($m['descricao']) ?></span><?php if (gestor()): ?><form method="post"><input type="hidden" name="csrf" value="<?= csrf() ?>"><input type="hidden" name="acao" value="excluirmaterial"><input type="hidden" name="id" value="<?= $m['id'] ?>"><button class="link">Remover</button></form><?php endif; ?></div><?php endforeach; ?></div>
  <?php if (gestor()): ?><form method="post" class="cartao"><h3>Adicionar link</h3><input type="hidden" name="csrf" value="<?= csrf() ?>"><input type="hidden" name="acao" value="material">
    <label>Título<input name="titulo" required></label><label>Link<input name="link" placeholder="https://" required></label><label>Descrição<input name="descricao"></label><button class="bt">Adicionar</button></form><?php endif; ?>

<?php elseif ($p === 'roteiro'): require __DIR__ . '/roteiro.php';
elseif ($p === 'guia'): require __DIR__ . '/guia.php';

elseif ($p === 'usuarios'):
  if (!gestor()) { echo '<p class="vazio">Só gestores cadastram usuários.</p>'; } else {
  $us = db()->query('SELECT u.*, (SELECT COUNT(*) FROM leads l WHERE l.usuario_id=u.id) n FROM usuarios u ORDER BY u.ativo DESC, u.nome')->fetchAll(); ?>
  <h1>Usuários</h1>
  <div class="lista"><?php foreach ($us as $x): ?><article class="lead <?= $x['ativo'] ? '' : 'feito' ?>"><div class="cab"><h3><?= e($x['nome']) ?></h3><time><?= ['admin' => 'Administrador', 'gestor' => 'Gestor', 'usuario' => 'Usuário'][$x['papel']] ?></time></div><p class="con">login: <?= e($x['usuario']) ?> · <?= (int)$x['n'] ?> leads<?= $x['ativo'] ? '' : ' · desativado' ?></p>
    <?php if ((int)$x['id'] !== (int)$u['id'] && ($x['papel'] !== 'admin' || admin())): ?><form method="post" class="pe"><input type="hidden" name="csrf" value="<?= csrf() ?>"><input type="hidden" name="acao" value="usuarioestado"><input type="hidden" name="id" value="<?= $x['id'] ?>"><input name="novasenha" placeholder="Nova senha provisória" autocomplete="off"><button class="bt claro">Redefinir senha</button><button class="link" name="alternar" value="1" onclick="this.form.novasenha.value=''"><?= $x['ativo'] ? 'Desativar' : 'Reativar' ?></button></form><?php endif; ?></article><?php endforeach; ?></div>
  <form method="post" class="cartao"><h3>Novo usuário</h3><input type="hidden" name="csrf" value="<?= csrf() ?>"><input type="hidden" name="acao" value="usuario">
    <label>Nome<input name="nome" data-f="titulo" required></label><label>Usuário para entrar (sem espaço)<input name="usuario" autocapitalize="none" required></label><label>Senha provisória<input name="senha" minlength="6" required></label>
    <label>Perfil<select name="papel"><option value="usuario">Usuário: cadastra e vê leads</option><option value="gestor">Gestor: também cadastra usuários e materiais</option><?php if (admin()): ?><option value="admin">Administrador</option><?php endif; ?></select></label><button class="bt">Criar usuário</button></form>
<?php } endif; ?>
</main>
<nav class="menu">
  <a href="?p=novo" class="<?= $p === 'novo' ? 'on' : '' ?>"><i>＋</i>Novo</a>
  <a href="?p=leads" class="<?= $p === 'leads' ? 'on' : '' ?>"><i>☰</i>Leads</a>
  <a href="?p=painel" class="<?= $p === 'painel' ? 'on' : '' ?>"><i>▤</i>Painel</a>
  <a href="?p=material" class="<?= in_array($p, ['material', 'roteiro', 'guia']) ? 'on' : '' ?>"><i>▶</i>Material</a>
  <?php if (gestor()): ?><a href="?p=usuarios" class="<?= $p === 'usuarios' ? 'on' : '' ?>"><i>☺</i>Equipe</a><?php endif; ?>
</nav>
<footer class="rodape">Perfeitto Digital · <?= e(EVENTO) ?> 2026</footer>
<?php endif; ?>
<script>window.CSRF=<?= json_encode($u ? csrf() : '') ?>;</script>
<script src="assets/app.js?v=<?= V ?>"></script>
</body>
</html>
