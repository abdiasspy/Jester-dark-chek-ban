<?php
declare(strict_types=1);
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

$number=trim($_GET['number']??'');
if(!preg_match('/^\+\d{7,20}$/',$number)){http_response_code(400);echo json_encode(['ok'=>false,'message'=>'Numéro invalide.']);exit;}

$api='https://banchek-by-awais.kesug.com/bancheck.php?number='.rawurlencode($number);
$ch=curl_init($api);
curl_setopt_array($ch,[CURLOPT_RETURNTRANSFER=>true,CURLOPT_FOLLOWLOCATION=>true,CURLOPT_CONNECTTIMEOUT=>8,CURLOPT_TIMEOUT=>15,CURLOPT_USERAGENT=>'JesterDark-BanChecker/2.0']);
$body=curl_exec($ch);$err=curl_error($ch);$http=(int)curl_getinfo($ch,CURLINFO_HTTP_CODE);curl_close($ch);

if($body===false||$err||$http<200||$http>=300){http_response_code(502);echo json_encode(['ok'=>false,'message'=>'L’API ne répond pas correctement.']);exit;}
$data=json_decode((string)$body,true);
if(!is_array($data)){http_response_code(502);echo json_encode(['ok'=>false,'message'=>'Format API inconnu : aucun statut fiable.']);exit;}

$banned=null;
foreach(['banned','ban','is_banned','isBanned'] as $k){if(array_key_exists($k,$data)&&is_bool($data[$k])){$banned=$data[$k];break;}}
if($banned===null&&isset($data['status'])&&is_string($data['status'])){
 $s=strtolower(trim($data['status']));
 if(in_array($s,['banned','ban','blocked'],true))$banned=true;
 if(in_array($s,['not_banned','unbanned','active','alive','ok'],true))$banned=false;
}
if($banned===null){http_response_code(502);echo json_encode(['ok'=>false,'message'=>'Réponse API non reconnue. Aucun résultat inventé.']);exit;}

$defaults=[
'banned_text'=>'⟦━ 𝗡𝗨𝗠É𝗥𝗢 ━ 𝗕𝗔𝗡𝗡𝗜 ━━⟧☠︎ 𝗖𝗘 𝗡𝗨𝗠É𝗥𝗢 𝗔 É𝗧É 𝗕𝗔𝗡𝗡𝗜⟦━ 𝗕𝗬 ━ 𓆩𝗝𝗘𝗦𝗧𝗘𝗥 𝗗𝗔𝗥𝗞 ²²⁵ཽ〆⃝ ━━⟧',
'live_text'=>'CONTINUE DE COURIR INSECTES MAIS JE T’AURAI'
];
$settingsFile=__DIR__.'/settings.json';
if(is_file($settingsFile)){ $saved=json_decode((string)file_get_contents($settingsFile),true); if(is_array($saved))$defaults=array_merge($defaults,$saved); }
echo json_encode(['ok'=>true,'banned'=>$banned,'message'=>$banned?$defaults['banned_text']:$defaults['live_text']],JSON_UNESCAPED_UNICODE);
