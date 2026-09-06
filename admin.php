<?php
declare(strict_types=1);
session_start();
$hash='$2y$12$5/MGLau5OLMKlE.QXVVdGeKqMw8MAhVorVLFstxm0DbyDmxSC8cU2';
$file=__DIR__.'/settings.json';
$settings=['banned_text'=>'⟦━ 𝗡𝗨𝗠É𝗥𝗢 ━ 𝗕𝗔𝗡𝗡𝗜 ━━⟧☠︎ 𝗖𝗘 𝗡𝗨𝗠É𝗥𝗢 𝗔 É𝗧É 𝗕𝗔𝗡𝗡𝗜⟦━ 𝗕𝗬 ━ 𓆩𝗝𝗘𝗦𝗧𝗘𝗥 𝗗𝗔𝗥𝗞 ²²⁵ཽ〆⃝ ━━⟧','live_text'=>'CONTINUE DE COURIR INSECTES MAIS JE T’AURAI'];
if(is_file($file)){ $x=json_decode((string)file_get_contents($file),true);if(is_array($x))$settings=array_merge($settings,$x); }
$error='';$saved=false;
if(isset($_GET['logout'])){session_destroy();header('Location: admin.php');exit;}
if($_SERVER['REQUEST_METHOD']==='POST'&&isset($_POST['login'])){if(password_verify((string)$_POST['password'],$hash))$_SESSION['admin']=true;else$error='Mot de passe incorrect.';}
if(!empty($_SESSION['admin'])&&$_SERVER['REQUEST_METHOD']==='POST'&&isset($_POST['save'])){
 $a=trim((string)($_POST['live_text']??''));$b=trim((string)($_POST['banned_text']??''));
 if($a===''||$b==='')$error='Les deux textes sont obligatoires.';
 else{file_put_contents($file,json_encode(['live_text'=>$a,'banned_text'=>$b],JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT),LOCK_EX);$settings=['live_text'=>$a,'banned_text'=>$b];$saved=true;}
}
?>
<!doctype html><html lang="fr"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Admin — Jester Dark</title><link rel="stylesheet" href="style.css"></head>
<body><main>
<section class="hero"><div class="pill"><i></i> ADMIN • PROTECTED</div><h1>CONTROL<br><em>PANEL</em></h1><p>Personnalisation des réponses du checker.</p></section>
<?php if(empty($_SESSION['admin'])): ?>
<section class="checker"><div class="field"><label>MOT DE PASSE</label><input id="pw" name="password" type="password" placeholder="••••••••••••" form="login" required style="width:100%;height:55px;background:#060608;border:1px solid #30303a;border-radius:14px;color:#fff;padding:0 14px;font-size:16px;outline:0"></div>
<form id="login" method="post"><input type="hidden" name="login" value="1"><button class="check">ACCÉDER AU PANNEAU ↗</button></form>
<?php if($error): ?><div class="status error"><?=htmlspecialchars($error)?></div><?php endif; ?>
<p style="color:#777;font-size:12px;margin-top:18px">Accès perdu ? <a href="https://t.me/Jester_dark" target="_blank" rel="noopener" style="color:#fff">t.me/Jester_dark</a></p>
</section>
<?php else: ?>
<section class="checker"><form method="post">
<input type="hidden" name="save" value="1">
<div class="field"><label>🟢 TEXTE — NUMÉRO NON BANNI</label><textarea name="live_text" required style="width:100%;min-height:130px;background:#060608;border:1px solid #30303a;border-radius:14px;color:#fff;padding:14px;font:inherit;resize:vertical"><?=htmlspecialchars($settings['live_text'])?></textarea></div>
<div class="field"><label>🔴 TEXTE — NUMÉRO BANNI</label><textarea name="banned_text" required style="width:100%;min-height:160px;background:#060608;border:1px solid #30303a;border-radius:14px;color:#fff;padding:14px;font:inherit;resize:vertical"><?=htmlspecialchars($settings['banned_text'])?></textarea></div>
<button class="check">SAUVEGARDER ↗</button></form>
<?php if($saved): ?><div class="status ok">✓ Modifications enregistrées.</div><?php endif; ?><?php if($error): ?><div class="status error"><?=htmlspecialchars($error)?></div><?php endif; ?>
<p><a href="admin.php?logout=1" style="color:#aaa">Se déconnecter</a></p></section>
<?php endif; ?>
<footer><a href="https://t.me/Jester_dark" target="_blank" rel="noopener">t.me/Jester_dark</a><p>⟂ · © Ban𓆩𝗝𝗘𝗦𝗧𝗘𝗥 𝗗𝗔𝗥𝗞 ²²⁵ཽ · ⟂</p></footer>
</main></body></html>
