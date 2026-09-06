const $=id=>document.getElementById(id);
const btn=$("countryBtn"),panel=$("countryPanel"),search=$("countrySearch"),list=$("countryList");
let selected=window.COUNTRIES.find(c=>c.code==="+225")||window.COUNTRIES[0];

function renderCountries(q=""){
 const query=q.trim().toLowerCase();
 list.innerHTML="";
 window.COUNTRIES.filter(c=>(c.name+" "+c.code).toLowerCase().includes(query)).forEach(c=>{
   const el=document.createElement("button"); el.className="countryItem";
   el.innerHTML=`<span>${c.flag}</span><span>${c.name}</span><span class="code">${c.code}</span>`;
   el.onclick=()=>{selected=c;$("selectedCountry").textContent=c.flag+" "+c.name;$("selectedCode").textContent=c.code;$("prefix").textContent=c.code;panel.classList.add("hidden");$("number").focus()};
   list.appendChild(el);
 });
 if(!list.children.length)list.innerHTML='<div style="padding:14px;color:#777;font-size:12px">Aucun pays trouvé.</div>';
}
btn.onclick=()=>{panel.classList.toggle("hidden");if(!panel.classList.contains("hidden")){renderCountries(search.value);search.focus()}};
search.oninput=()=>renderCountries(search.value);
document.addEventListener("click",e=>{if(!panel.contains(e.target)&&!btn.contains(e.target))panel.classList.add("hidden")});
renderCountries();

$("checkBtn").onclick=async()=>{
 const raw=$("number").value.trim().replace(/[^\d]/g,"");
 const prefix=selected.code.replace("+","");
 if(!raw){$("status").textContent="Entre un numéro."; $("status").className="status error";return}
 if(raw.startsWith(prefix)&&raw.length>8){ /* accepts full international number */ }
 const full="+"+prefix+raw.replace(/^0(?=\d)/,"");
 $("checkBtn").disabled=true;$("status").textContent="Vérification en cours…";$("status").className="status";
 $("result").className="result hidden";
 try{
   const r=await fetch("check.php?number="+encodeURIComponent(full),{cache:"no-store"});
   const d=await r.json();
   if(!r.ok||d.ok!==true)throw new Error(d.message||"API indisponible");
   $("result").className="result "+(d.banned?"banned":"live");
   $("icon").textContent=d.banned?"☠":"✓";
   $("title").textContent=d.banned?"NUMÉRO BANNI":"NUMÉRO NON BANNI";
   $("message").textContent=d.message;
   $("checked").textContent="Numéro vérifié : "+full;
   $("status").textContent="Réponse reçue depuis l’API."; $("status").className="status ok";
 }catch(e){$("status").textContent="Vérification impossible : "+e.message;$("status").className="status error"}
 finally{$("checkBtn").disabled=false}
};
$("number").addEventListener("keydown",e=>{if(e.key==="Enter")$("checkBtn").click()});
