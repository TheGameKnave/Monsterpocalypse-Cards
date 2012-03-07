
<?php 




$abilityOrder = array(
	"english" =>	array("actions","advantages","triggers","skills","reactions","misc"),
	"japanese" =>	array("アクション","アドバンテージ","トリガ","スキル","リアクション","misc"),
	"french" => array("actions","avantages","d&eacute;clencheurs","comp&eacute;tences","r&eacute;actions","divers"),
	"german" =>	array("aktionen","vorteile","ausl&ouml;ser","fertigkeiten","reaktionen","versch"),
);

$statLanguage = array(
	"Speed" =>	array(
		"english" =>	"Speed",
		"japanese" =>	"スピード",
		"french" => "Vitesse",
		"german" =>	"Geschw.",
	),
	"Defense" =>	array(
		"english" =>	"Defense",
		"japanese" =>	"ディフェンス",
		"french" => "D&eacute;fense",
		"german" =>	"Verteid.",
	),
	"Energy" =>	array(
		"english" =>	"Energy",
		"japanese" =>	"エナジー",
		"french" => "Energie",
		"german" =>	"Energie",
	),
	"Brawl" =>	array(
		"english" =>	"Brawl",
		"japanese" =>	"ブロウル",
		"french" => "Lutte",
		"german" =>	"Nahk.",
	),
	"Blast" =>	array(
		"english" =>	"Blast",
		"japanese" =>	"ブラスト",
		"french" => "Tir",
		"german" =>	"Fernk.",
	),
	"Power" =>	array(
		"english" =>	"Power",
		"japanese" =>	"パワー",
		"french" => "Puissance",
		"german" =>	"Power",
	),
	"Health" =>	array(
		"english" =>	"Health",
		"japanese" =>	"ヘルス",
		"french" => "Vie",
		"german" =>	"Gesundh.",
	),
	"Alpha" =>	array(
		"english" =>	"Alpha",
		"japanese" =>	"アルファ",
		"french" => "Alpha",
		"german" =>	"Alpha",
	),
	"Morpher" =>	array(
		"english" =>	"Morpher",
		"japanese" =>	"モーファー",
		"french" => "Morpheur",
		"german" =>	"Morpher",
	),
	"Ultra" =>	array(
		"english" =>	"Ultra",
		"japanese" =>	"ウルトラ",
		"french" => "Ultra",
		"german" =>	"Ultra",
	),
	"Mega" =>	array(
		"english" =>	"Mega",
		"japanese" =>	"メガ",
		"french" => "M&eacute;ga",
		"german" =>	"Mega",
	),
	"Quantum" =>	array(
		"english" =>	"Quantum",
		"japanese" =>	"クオンタム",
		"french" => "Quantum",
		"german" =>	"Quantum",
	),
	"Elite" =>	array(
		"english" =>	"Elite",
		"japanese" =>	"エリート",
		"french" => "Elite",
		"german" =>	"Elite",
	),
	"Grunt" =>	array(
		"english" =>	"Grunt",
		"japanese" =>	"グラント",
		"french" => "Masse",
		"german" =>	"Normal",
	),
);
$see_rules = "See rules for full details";

function magic($arg,$src){
			$t = preg_split('//', $arg, -1);
			$preg = "";
			foreach($t as $l){
				$preg .= $l.'.*';
			}
			$preg = substr($preg,2,(strlen($preg)-6));
			$preg = "/".$preg."/";
			if( preg_match($preg, $src) ){return true;}else{return false;}
}





function display($figure){

$image = uscore($figure["name"]);
if($figure["form"]){$image=$image."_".uscore($figure["form"]);}elseif($figure["class"]){$image=$image."_".uscore($figure["class"]);}
print("<img src=\"assets/cards/".uscore($figure["set"])."/".$image.".jpg\" width=\"780\" height=\"1081\"/>");

}

function build($s,$format){
         if($s["type"] != "map" && $s["type"] != "reference"){
    	      if($format==="card"){
    		card($s);
    	      }elseif($format==="insert"){
    		insert_front($s);
    	      }
         }
}


function card($figure){

global $abilityOrder,$abilities,$language,$see_rules;



$image = uscore($figure["name"]);
if($figure["form"]){$image=$image."_".uscore($figure["form"]);}elseif($figure["class"]){$image=$image."_".uscore($figure["class"]);}
if(isset($figure['form']) && $figure['form'] != strtolower("alpha")){$form = ucfirst($figure['form']);}
if(strtolower($figure['type'])=="monster" && strtolower($figure['form']) != "mega" && strtolower($figure['form']) != "ultra" && strtolower($figure['form']) != "quantum" && strtolower($figure['form']) != "alpha"){$figure['form']="morpher";}

if(!$figure['faction']){$figure['faction']="neutral";}

if(!isset($figure['class'])){$figure['class']="cost";}
if(strtolower($figure['class'])=="elite"){$classElite=" red";}

ksort($figure['abilities']);

print("<div class='card' style=\"background:url('assets/backgrounds/".uscore($figure['faction']).".jpg');\">

	<p class='title".$classElite."'>".str_replace("'","&rsquo;",$figure['name']));
if(isset($figure['form'])){print("<span class='".$figure['form']."'>".str_replace("'","&rsquo;",$form)."</span>");}
print("</p>
	<div class='content' style=\"background:url('assets/figures/".uscore($figure['faction'])."/".str_replace("'","",$image).".png') no-repeat;\">
		<div class='stats'>
			<table cellspacing='0' cellpadding='0' border='0'>");
if(uscore($figure['type'])!="building"){statRow("speed",$figure['speed']);}
statRow("defense",uscore($figure['defense']));
if(uscore($figure['type'])=="building"){statRow("energy",$figure['energy']);}
statRow("brawl",uscore($figure['brawl']),$figure['brawlBoost']);
statRow("blast",uscore($figure['blast']),$figure['blastBoost'],$figure['blastRange']);
if(uscore($figure['type'])=="monster"){
	statRow("power",uscore($figure['power']),$figure['powerBoost']);
	statRow("health",$figure['health']);
	if(!isset($figure['hyper']) || $figure['hyper'] == ""){$figure['hyper'] = 0;}
	statRow($figure['form'],$figure['hyper']);
}
if(uscore($figure['type'])=="unit" || uscore($figure['type'])=="character"){statRow("cost",$figure['cost'],"0","0",$figure['class']);}
print("
			</table>
		</div>
		<div class='abilities'>");

$short = 0;
foreach($figure['abilities'] as $k => $v){if(strpos($k,"short")){$short++;}}
$count = count($figure['abilities']);
ksort($figure['abilities']);
$j=0;
foreach($abilityOrder['english'] as $category){
	$i=1;
	if($category == "triggers"){
        	$trigger_list = array ("brawl","blast","power");
        	foreach($trigger_list as $trig){
			foreach($figure['abilities'] as $ability => $attributes){
				if( array_key_exists(uscore(trim($ability)),$abilities[$category]) && in_array($trig,$attributes) ){
					if($i){
						print("
							<p class='category'>".$abilityOrder[$language][$j]."</p>");
					}
					
					$co="";
					if(array_key_exists("color",$attributes)){$co = $attributes["color"];}
					$tr="";
					if(array_key_exists("trigger",$attributes)){$tr = $attributes["trigger"];}
					ability($category,uscore(trim($ability)),$co,$tr,"",$short,$count);
					$i=0;
				}
			}
        	}
        }
	else{
		foreach($figure['abilities'] as $ability => $attributes){
			if(array_key_exists(uscore(trim($ability)),$abilities[$category])){
				if($i){
						$a = "
						<p class='category'>";
						$b = "";
						$c = "</p>";
						if($category !== "misc"){
							$b = $abilityOrder[$language][$j];
						}
						print($a.$b.$c);
				}
				$co="";
				if(array_key_exists("color",$attributes)){$co = $attributes["color"];}
				$tr="";
				if(array_key_exists("trigger",$attributes)){$tr = $attributes["trigger"];}
				ability($category,uscore(trim($ability)),$co,$tr,"",$short,$count);
				$i=0;
			}
		}
	}
	$j++;
}
if($short >= 3){print("<p class='footnote'><span class='asterisk'>*</span> ".$see_rules."</p>");}

print("
			
		</div>
		<div class='attributes'>");

if(uscore($figure['type']) != "building"){
	print("
			<object data='assets/icons/energy_".uscore($figure['energyType']).".svg'></object>");
}

if(uscore($figure['type']) == "building"){
	print("
			<object data='assets/icons/hazard_".uscore($figure['hazard']).".svg'></object>");
}


if(uscore($figure['faction']) != "neutral"){
	print("
			<object data='assets/icons/agenda_".uscore($figure['agenda']).".svg'></object>
			<object data='assets/icons/faction_".uscore($figure['faction']).".svg'></object>");
}
if(uscore($figure['type']) == "building"){
	$building="building";
	if(uscore($figure['faction']) != "neutral"){$building="installation";}
	print("
			<object data='assets/icons/building_".$building.".svg'></object>");
}

$set = uscore($figure['set']);
if($figure['setColor']){$setColor .= "?color=".$figure['setColor'];}else{$setColor="";}
if($figure['rarity'] > 3){$setHeight = " style=\"height:30px;\"";}
print("
			<div class='set'>
				<object data='assets/icons/set_".$set.".svg".$setColor."'></object>
				<div class='rarity'>");

for($i=$figure['rarity'];$i--;$i>1){print("<object data='assets/icons/stats_boost.svg'></object>");}

print("</div>
			</div>
			<div class='clear'></div>
		</div>
		<div class='clear'></div>
	</div>
</div>");
}


function insert_front($figure){
  global $abilityOrder,$abilities,$language,$see_rules;

  $image = uscore($figure["name"]);
  if($figure["form"]){$image=$image."_".uscore($figure["form"]);}elseif($figure["class"]){$image=$image."_".uscore($figure["class"]);}
  if(isset($figure['form']) && $figure['form'] != strtolower("alpha")){$form = ucfirst($figure['form']);}
  if(strtolower($figure['type'])=="monster" && strtolower($figure['form']) != "mega" && strtolower($figure['form']) != "ultra" && strtolower($figure['form']) != "quantum" && strtolower($figure['form']) != "alpha"){$figure['form']="morpher";}

  if(!$figure['faction']){$figure['faction']="neutral";}

  if(!isset($figure['class'])){$figure['class']="cost";}
  if(strtolower($figure['type']) == "unit" || strtolower($figure['type']) == "character" || strtolower($figure['form']) == "morpher"){
  	$size = "small";
  }else{
  	$size = "large";
  }
  if(strtolower($figure['type']) == "character" || strtolower($figure['form']) == "morpher"){
  	$br1 = "<br>(";
	$br2 = ")";
  }else{
  	$br1 = " ";
  	$br2 = "";
  }
  $titleSize="";
  ksort($figure['abilities']);
  if((uscore($figure["type"]) === "monster" || uscore($figure["type"]) === "building") && strlen($figure["name"]) >= 19){$titleSize = " long_title";}
  if(uscore($figure["type"]) === "unit" && strlen($figure["name"]) >= 18){$titleSize = " long_title";}
  if(uscore($figure["type"]) === "monster" && strlen($form) >= 18){$formSize = " long_title";}else{$formSize="";}
 print("<div class='insert_front ".$size." ".$figure['form']."'>
	  <div class='title ".strtolower($figure['class']).$titleSize."'>");
	if(uscore($figure['type'])==="monster" || true){
		print("<span class='".$figure['form'].$formSize."'>".str_replace("'","&rsquo;",$form)."</span> ".$br1.str_replace("'","&rsquo;",$figure['name']).$br2);
	}elseif(uscore($figure['type'])==="character"){
		print(str_replace("'","&rsquo;",$figure['name'])."<span class='".$figure['form']."'>".$br1.str_replace("'","&rsquo;",$form).$br2."</span>");
	}else{
		print(str_replace("'","&rsquo;",$figure['name']));
	}
  
  print("</div>
		<div class='stats'>
			<table cellspacing='0' cellpadding='0' border='0'>");
	
  if(uscore($figure['type'])!="building"){statRow("speed",$figure['speed']);}
  statRow("defense",uscore($figure['defense']));
  if(uscore($figure['type'])=="building"){statRow("energy",$figure['energy']);}
  statRow("brawl",uscore($figure['brawl']),$figure['brawlBoost']);
  statRow("blast",uscore($figure['blast']),$figure['blastBoost'],$figure['blastRange']);
  if(uscore($figure['type'])=="monster"){
  	statRow("power",uscore($figure['power']),$figure['powerBoost']);
  }
  if(uscore($figure['type'])=="monster" && uscore($figure['form'])!="morpher"){
  	statRow("health",$figure['health']);
  }
  print("
  			</table>
  		</div>
  		<div class='abilities'>");
	
  $short = 0;
  foreach($figure['abilities'] as $k => $v){if(strpos($k,"short")){$short++;}}
  $count = count($figure['abilities']);
  ksort($figure['abilities']);
  $j=0;
  foreach($abilityOrder['english'] as $category){
  	if($category == "triggers"){
          	$trigger_list = array ("brawl","blast","power");
          	foreach($trigger_list as $trig){
  			foreach($figure['abilities'] as $ability => $attributes){
  				if( array_key_exists(uscore(trim($ability)),$abilities[$category]) && in_array($trig,$attributes) ){

  					$co="";
  					if(array_key_exists("color",$attributes)){$co = $attributes["color"];}
  					$tr="";
  					if(array_key_exists("trigger",$attributes)){$tr = $attributes["trigger"];}
  					ability($category,uscore(trim($ability)),$co,$tr,"",$short,$count);
  				}
  			}
          	}
          }
  	elseif($category != "misc"){
  		foreach($figure['abilities'] as $ability => $attributes){
  			if(array_key_exists(uscore(trim($ability)),$abilities[$category])){
  				$co="";
  				if(array_key_exists("color",$attributes)){$co = $attributes["color"];}
  				$tr="";
  				if(array_key_exists("trigger",$attributes)){$tr = $attributes["trigger"];}
  				ability($category,uscore(trim($ability)),$co,$tr,"",$short,$count);
  			}
  		}
  	}
  	$j++;
  }

  print("

  		</div>
  		<div class='attributes'>");
	

  if(uscore($figure['type']) != "building"){
  	print("
  			<object data='assets/icons/energy_".uscore($figure['energyType']).".svg'></object>");
  }

  if(uscore($figure['type']) == "building"){
  	print("
  			<object data='assets/icons/hazard_".uscore($figure['hazard']).".svg'></object>");
  }


  if(uscore($figure['faction']) != "neutral"){
  	print("
  			<object data='assets/icons/agenda_".uscore($figure['agenda']).".svg'></object>
  			<object data='assets/icons/faction_".uscore($figure['faction']).".svg'></object>");
  }
  if(uscore($figure['type']) == "building"){
  	$building="building";
  	if(uscore($figure['faction']) != "neutral"){$building="installation";}
  	print("
  			<object data='assets/icons/building_".$building.".svg'></object>");
  }
  if(uscore($figure['type']) == "monster" && uscore($figure['form']) != "alpha" && uscore($figure['form']) != "morpher"){
    if($figure['quantum']){$color="?color=red";}else{$color="";}
    print("<div class=\"hyper\"><object data=\"assets/icons/stats_hyper.svg".$color."\"></object><p>".$figure['hyper']."</p></div>");
  }
  if(uscore($figure['type']) == "monster" && uscore($figure['form']) == "morpher"){
    print("<div class=\"hyper\"><object data=\"assets/icons/stats_health.svg\"></object><p>".$figure['health']."</p></div>");
  }
  if(uscore($figure['type'])=="unit" || uscore($figure['type'])=="character"){
  	print("<div class=\"cost\"><object data=\"assets/icons/stats_cost.svg\"></object><p>".$figure['cost']."</p></div>");
  }
  print("	</div>
  	<div class=\"notch\">&nbsp;</div>
  	<img src=\"assets/figures/".uscore($figure['faction'])."/".str_replace("'","",$image).".png\">
  </div>");

}





function str_replace_count($find,$replace,$subject,$count)
{
   $subjectnew = $subject;
   $pos = strpos($subject,$find);
   if ($pos !== FALSE)
   {
     while ($pos !== FALSE)
     {
         $nC = $nC + 1;
         $temp = substr($subjectnew,$pos+strlen($find));
         $subjectnew = substr($subjectnew,0,$pos) . $replace . $temp;
         if ($nC >= $count)
         {
           break;
         }
         $pos = strpos($subjectnew,$find);
     } // closes the while loop
   } // closes the if
   return $subjectnew;
}


function ability($category,$title,$color="",$trigger="",$text="",$short=0,$count=0){
	global $abilities,$figure,$language,$see_rules,$format;
		if($color!=""){$color=color($color);$color="?color=".$color;}
		if($trigger=="" && $category=="trigger"){$trigger="blast";}
		if($trigger!=""){$trigger="_".$trigger;}
		if($language === 'english'){
			foreach($abilities[$category][$title] as $test => $recent){
				if($recent){
					$text = $recent;
					break;
				}
			}
		}else{
			$text = $abilities[$category][$title][$language];
		}
		$style="";
		if(strlen($text) > 200){$style = "line-height:24px;";}
		
		if($language === 'english'){
			$icon_replacement = array(
				"SPD" => "stats_speed",
				"DEF" => "stats_defense",
				"Energy" => "stats_energy",
				"Brawl" => "stats_brawl",
				"Blast" => "stats_blast",
				"Health" => "stats_health",
				"Hyper" => "stats_hyper",
				"Cost" => "stats_cost",
				"Mechanical" => "energy_mechanical",
				"Nature" => "energy_nature",
				"Occult" => "energy_occult",
				"Biotech" => "energy_biotech",
				"Cosmic" => "energy_cosmic",
				"Elemental" => "energy_elemental",
				"Radioactive" => "energy_radioactive",
				"Protector" => "agenda_protectors",
				"Destroyer" => "agenda_destroyers",
				"Invader" => "agenda_invaders",
				"Collaborator" => "agenda_collaborators",
				"Fiends" => "agenda_fiends",
				"Radical" => "agenda_radicals",
				"Chemical" => "hazard_chemical_spill",
				"Fire" => "hazard_fire",
				"Hellfont" => "hazard_hellfont",
				"Radiation" => "hazard_radiation",
				"Rubble" => "hazard_rubble",
				"Cover" => "terrain_cover",
				"Impassable" => "terrain_impassable",
			);
	
			$text = str_replace("super strikes","@@@@@",$text);
			$text = str_replace_count("strikes","strikes <span class='nowrap'><object data=\"assets/icons/dice_strike.svg\"></object></span>",$text,1);
			$text = str_replace("@@@@@","super strikes",$text);
			$text = str_replace_count("super strikes","super strikes <span class='nowrap'><object data=\"assets/icons/dice_super_strike.svg\"></object></span>",$text,1);
			$text = str_replace_count("rough terrain","rough <object data=\"assets/icons/terrain_rough.svg\"></object> terrain",$text,1);
			$text = str_replace_count("power attack","power <object data=\"assets/icons/stats_power.svg\"></object> attack",$text,1);
			$text = str_replace_count("and power","and power <object data=\"assets/icons/stats_power.svg\"></object>",$text,1);

			$text = str_replace("'","&rsquo;",$text);
			$text = str_replace("-","&#8209;",$text);
			$text = str_replace("A&#8209;D","<span class=\"a-die die\">A</span>&#8209;D",$text);
			$text = str_replace("B&#8209;D","<span class=\"b-die die\">B</span>&#8209;D",$text);
			$text = str_replace("P&#8209;D","<span class=\"p-die die\">P</span>&#8209;D",$text);

		}elseif($language === 'japanese'){
			$icon_replacement = array(
				"スピード" => "stats_speed",
				"ディフェンス" => "stats_defense",
				"エナジー" => "stats_energy",
				"ブロウル" => "stats_brawl",
				"ブラスト" => "stats_blast",
				"ヘルス" => "stats_health",
				"ハイパー" => "stats_hyper",
				"コスト" => "stats_cost",
				"メカニカル" => "energy_mechanical",
				"ネイチャー" => "energy_nature",
				"オカルト" => "energy_occult",
				"バイオテック" => "energy_biotech",
				"コズミック" => "energy_cosmic",
				"エレメンタル" => "energy_elemental",
				"レディオアクティブ" => "energy_radioactive",
				"プロテクター" => "agenda_protectors",
				"デストロイヤー" => "agenda_destroyers",
				"インベイダー" => "agenda_invaders",
				"コラボレイター" => "agenda_collaborators",
				"フィーンド" => "agenda_fiends",
				"ラディカル" => "agenda_radicals",
				"ケミカル" => "hazard_chemical_spill",
				"ファイア" => "hazard_fire",
				"ヘルフォント" => "hazard_hellfont",
				"ラディエーション" => "hazard_radiation",
				"ラブル" => "hazard_rubble",
				"カバー" => "terrain_cover",
				"インポシブル" => "terrain_impassable",
			);
	
			$text = str_replace("スーパーストライク","@@@@@",$text);
			$text = str_replace_count("ストライク","ストライク <span class='nowrap'><object data=\"assets/icons/dice_strike.svg\"></object></span>",$text,1);
			$text = str_replace("@@@@@","スーパーストライク",$text);
			$text = str_replace_count("スーパーストライク","スーパーストライク <span class='nowrap'><object data=\"assets/icons/dice_super_strike.svg\"></object></span>",$text,1);
			$text = str_replace_count("ラフテレイン","ラフテレイン <object data=\"assets/icons/terrain_rough.svg\"></object>",$text,1);
			$text = str_replace_count("パワーアタック","パワーアタック <object data=\"assets/icons/stats_power.svg\"></object>",$text,1);

			$text = str_replace("'","&rsquo;",$text);
			$text = str_replace("-","&#8209;",$text);
			$text = str_replace("アクションダイス","アクション<span class=\"a-die die\">A</span>ダイス",$text);
			$text = str_replace("ブーストダイス","ブースト<span class=\"b-die die\">B</span>ダイス",$text);
			$text = str_replace("パワーダイス","パワー<span class=\"p-die die\">P</span>ダイス",$text);

			$see_rules = "詳細はルールブックをご覧ください";


		}elseif($language === 'french'){
			if($count > 4 || strlen($text) > 150){$style = "line-height:22px;font-size:21px;";}
  			$icon_replacement = array(
  				"VIT" => "stats_speed",
  				"DEF" => "stats_defense",
  				"Energie" => "stats_energy",
  				"Lutte" => "stats_brawl",
  				" Tir" => "stats_blast",
  				"Vie" => "stats_health",
  				"Hyper" => "stats_hyper",
  				"Co&ucirc;t" => "stats_cost",
  				"M&eacute;canique" => "energy_mechanical",
  				"Nature" => "energy_nature",
  				"Occulte" => "energy_occult",
  				"Biotech" => "energy_biotech",
  				"Cosmique" => "energy_cosmic",
  				"El&eacute;mentaire" => "energy_elemental",
  				"Radioactive" => "energy_radioactive",
  				"Radioactif" => "energy_radioactive",
  				"Protecteur" => "agenda_protectors",
  				"Destructeur" => "agenda_destroyers",
  				"Envahisseur" => "agenda_invaders",
  				"Collaborateur" => "agenda_collaborators",
  				"S&eacute;ide" => "agenda_fiends",
  				"Radical" => "agenda_radicals",
  				"Chimique" => "hazard_chemical_spill",
  				"Feu" => "hazard_fire",
  				"Abyssale" => "hazard_hellfont",
  				"Radiation" => "hazard_radiation",
  				"Ruines" => "hazard_rubble",
  				"Infranchissable" => "terrain_impassable",
  			);

			$text = str_replace_count("difficile","difficile <span class='nowrap'><object data=\"assets/icons/terrain_rough.svg\"></object></span>",$text,1);

			$text = str_replace("d&eacute;couvert","@@@@@",$text);
			$text = str_replace_count("couvert","couvert <span class='nowrap'><object data=\"assets/icons/terrain_cover.svg\"></object></span>",$text,1);
			$text = str_replace("@@@@@","d&eacute;couvert",$text);

			$text = str_replace("super frappes","@@@@@",$text);
			$text = str_replace("frappes","#####",$text);
			$text = str_replace("super frappe","$$$$$",$text);
			$text = str_replace_count("frappe","frappe <span class='nowrap'><object data=\"assets/icons/dice_strike.svg\"></object></span>",$text,1);
			$text = str_replace("$$$$$","super frappe",$text);
			$text = str_replace_count("super frappe","super frappe <span class='nowrap'><object data=\"assets/icons/dice_super_strike.svg\"></object></span>",$text,1);
			$text = str_replace("#####","frappes",$text);
			$text = str_replace_count("frappes","frappes <span class='nowrap'><object data=\"assets/icons/dice_strike.svg\"></object></span>",$text,1);
			$text = str_replace("@@@@@","super frappes",$text);
			$text = str_replace_count("super frappes","super frappes <span class='nowrap'><object data=\"assets/icons/dice_super_strike.svg\"></object></span>",$text,1);
			$text = str_replace_count("attaques de puissance","attaques de puissance <object data=\"assets/icons/stats_power.svg\"></object>",$text,1);
			$text = str_replace_count("attaque de puissance","attaque de puissance <object data=\"assets/icons/stats_power.svg\"></object>",$text,1);
			$text = str_replace_count("et en puissance","et en puissance <object data=\"assets/icons/stats_power.svg\"></object>",$text,1);

			$text = str_replace("'","&rsquo;",$text);
			$text = str_replace("-","&#8209;",$text);
			$text = str_replace("D&eacute;&#8209;A","D&eacute;&#8209;<span class=\"a-die die\">A</span>",$text);
			$text = str_replace("D&eacute;&#8209;B","D&eacute;&#8209;<span class=\"b-die die\">B</span>",$text);
			$text = str_replace("D&eacute;&#8209;P","D&eacute;&#8209;<span class=\"p-die die\">P</span>",$text);
			$text = str_replace("d&eacute;&#8209;A","d&eacute;&#8209;<span class=\"a-die die\">A</span>",$text);
			$text = str_replace("d&eacute;&#8209;B","d&eacute;&#8209;<span class=\"b-die die\">B</span>",$text);
			$text = str_replace("d&eacute;&#8209;P","d&eacute;&#8209;<span class=\"p-die die\">P</span>",$text);
			$text = str_replace("D&eacute;s&#8209;A","D&eacute;s&#8209;<span class=\"a-die die\">A</span>",$text);
			$text = str_replace("D&eacute;s&#8209;B","D&eacute;s&#8209;<span class=\"b-die die\">B</span>",$text);
			$text = str_replace("D&eacute;s&#8209;P","D&eacute;s&#8209;<span class=\"p-die die\">P</span>",$text);
			$text = str_replace("d&eacute;s&#8209;A","d&eacute;&#8209;<span class=\"a-die die\">A</span>",$text);
			$text = str_replace("d&eacute;s&#8209;B","d&eacute;&#8209;<span class=\"b-die die\">B</span>",$text);
			$text = str_replace("d&eacute;s&#8209;P","d&eacute;&#8209;<span class=\"p-die die\">P</span>",$text);
			
			$see_rules = "Consulter les r&egrave;gles pour tous les d&eacute;tails";

		}elseif($language === 'german'){
			if($count > 4 || strlen($text) > 150){$style = "line-height:22px;font-size:21px;";}
			$icon_replacement = array(
				"GSW" => "stats_speed",
				"Energie" => "stats_energy",
				"Gesundheit" => "stats_health",
				"Hyperform" => "stats_hyper",
				"Hyperphase" => "stats_hyper",
				"Kosten" => "stats_cost",
				"Biotech" => "energy_biotech",
				"Kosmisch" => "energy_cosmic",
				"Elementar" => "energy_elemental",
				"Deckung" => "terrain_cover",
				"Unpassierbares" => "terrain_impassable",
			);

			$text = str_replace_count("Radioaktive Figuren","Radioaktive <span class='nowrap'><object data=\"assets/icons/energy_radioactive.svg\"></object></span> Figuren",$text,1);
			$text = str_replace_count("Radioaktives Monster","Radioaktives <span class='nowrap'><object data=\"assets/icons/energy_radioactive.svg\"></object></span> Monster",$text,1);
			$text = str_replace_count("Natur-Monster","Natur-Monster <span class='nowrap'><object data=\"assets/icons/energy_nature.svg\"></object></span>",$text,1);
			$text = str_replace_count("Okkult-Einheit","Okkult-Einheit <span class='nowrap'><object data=\"assets/icons/energy_occult.svg\"></object></span>",$text,1);
			$text = str_replace_count("Besch&uuml;tzer-Monster","Besch&uuml;tzer-Monster <span class='nowrap'><object data=\"assets/icons/agenda_protectors.svg\"></object></span>",$text,1);
			$text = str_replace_count("Besch&uuml;tzer-Einheiten","Besch&uuml;tzer-Einheiten <span class='nowrap'><object data=\"assets/icons/agenda_protectors.svg\"></object></span>",$text,1);
			$text = str_replace_count("Zerst&ouml;rer-Monster","Zerst&ouml;rer-Monster <span class='nowrap'><object data=\"assets/icons/agenda_destroyers.svg\"></object></span>",$text,1);
			$text = str_replace_count("Invasoren-Monster","Invasoren-Monster <span class='nowrap'><object data=\"assets/icons/agenda_invaders.svg\"></object></span>",$text,1);
			$text = str_replace_count("Invasoren-Einheiten","Invasoren-Einheiten <span class='nowrap'><object data=\"assets/icons/agenda_invaders.svg\"></object></span>",$text,1);
			$text = str_replace_count("Kollaborateure-Monster","Kollaborateure-Monster <span class='nowrap'><object data=\"assets/icons/agenda_collaborators.svg\"></object></span>",$text,1);
			$text = str_replace_count("Kollaborateure-Einheit","Kollaborateure-Einheit <span class='nowrap'><object data=\"assets/icons/agenda_collaborators.svg\"></object></span>",$text,1);
			$text = str_replace_count("Unholde-Monster","Unholde-Monster <span class='nowrap'><object data=\"assets/icons/agenda_fiends.svg\"></object></span>",$text,1);
			$text = str_replace_count("Radikale-Monster","Radikale-Monster <span class='nowrap'><object data=\"assets/icons/agenda_radicals.svg\"></object></span>",$text,1);
			$text = str_replace_count("Chemie-Gefahrenquelle","Chemie-Gefahrenquelle <span class='nowrap'><object data=\"assets/icons/hazard_chemical_spill.svg\"></object></span>",$text,1);
			$text = str_replace_count("H&ouml;llentor-Gefahrenquelle","H&ouml;llentor-Gefahrenquelle <span class='nowrap'><object data=\"assets/icons/hazard_hellfont.svg\"></object></span>",$text,1);
			$text = str_replace_count("Strahlung-Gefahrenquelle","Strahlung-Gefahrenquelle <span class='nowrap'><object data=\"assets/icons/hazard_radiation.svg\"></object></span>",$text,1);
			$text = str_replace_count("Mechanische Monster","Mechanische <span class='nowrap'><object data=\"assets/icons/energy_mechanical.svg\"></object></span> Monster",$text,1);
			$text = str_replace_count("Mechanischem Monster","Mechanischem <span class='nowrap'><object data=\"assets/icons/energy_mechanical.svg\"></object></span> Monster",$text,1);


			$text = str_replace("Feuer-Gefahrenquellen","@@@@@",$text);
			$text = str_replace_count("Feuer-Gefahrenquelle","Feuer-Gefahrenquelle <span class='nowrap'><object data=\"assets/icons/hazard_fire.svg\"></object></span>",$text,1);
			$text = str_replace("@@@@@","Feuer-Gefahrenquellen",$text);
			$text = str_replace_count("Feuer-Gefahrenquellen","Feuer-Gefahrenquellen <span class='nowrap'><object data=\"assets/icons/hazard_fire.svg\"></object></span>",$text,1);


			$text = str_replace("Power-Angriffe","@@@@@",$text);
			$text = str_replace("Power-Angriffs","#####",$text);
			$text = str_replace_count("Power-Angriff","Power-Angriff <span class='nowrap'><object data=\"assets/icons/stats_power.svg\"></object></span>",$text,1);
			$text = str_replace("@@@@@","Power-Angriffe",$text);
			$text = str_replace_count("Power-Angriffe","Power-Angriffe <span class='nowrap'><object data=\"assets/icons/stats_power.svg\"></object></span>",$text,1);
			$text = str_replace("#####","Power-Angriffs",$text);
			$text = str_replace_count("Power-Angriffs","Power-Angriffs <span class='nowrap'><object data=\"assets/icons/stats_power.svg\"></object></span>",$text,1);
			$text = str_replace_count("Power B","Power <span class='nowrap'><object data=\"assets/icons/stats_power.svg\"></object></span> B",$text,1);

			$text = str_replace("Nahkampf-Angriff","#####",$text);
			$text = str_replace("Fernkampf-Angriff","$$$$$",$text);
			$text = str_replace("Fernkampf-R","%%%%%",$text);
			$text = str_replace_count("Nahkampf","Nahkampf <span class='nowrap'><object data=\"assets/icons/stats_brawl.svg\"></object></span>",$text,1);
			$text = str_replace_count("Fernkampf","Fernkampf <span class='nowrap'><object data=\"assets/icons/stats_blast.svg\"></object></span>",$text,1);
			$text = str_replace("#####","Nahkampf-Angriff",$text);
			$text = str_replace("$$$$$","Fernkampf-Angriff",$text);
			$text = str_replace("%%%%%","Fernkampf-R",$text);
			$text = str_replace("-Angriffen","^^^^^",$text);
			$text = str_replace("-Angriffe","@@@@@",$text);
			$text = str_replace_count("Nahkampf-Angriff","Nahkampf-Angriff <span class='nowrap'><object data=\"assets/icons/stats_brawl.svg\"></object></span>",$text,1);
			$text = str_replace_count("Fernkampf-Angriff","Fernkampf-Angriff <span class='nowrap'><object data=\"assets/icons/stats_blast.svg\"></object></span>",$text,1);
			$text = str_replace("@@@@@","-Angriffe",$text);
			$text = str_replace_count("Nahkampf-Angriffe","Nahkampf-Angriffe <span class='nowrap'><object data=\"assets/icons/stats_brawl.svg\"></object></span>",$text,1);
			$text = str_replace_count("Fernkampf-Angriffe","Fernkampf-Angriffe <span class='nowrap'><object data=\"assets/icons/stats_blast.svg\"></object></span>",$text,1);
			$text = str_replace("^^^^^","-Angriffen",$text);
			$text = str_replace_count("Nahkampf-Angriffen","Nahkampf-Angriffen <span class='nowrap'><object data=\"assets/icons/stats_brawl.svg\"></object></span>",$text,1);
			$text = str_replace_count("Fernkampf-Angriffen","Fernkampf-Angriffen <span class='nowrap'><object data=\"assets/icons/stats_blast.svg\"></object></span>",$text,1);
			$text = str_replace_count("Fernkampf-Reichweite","Fernkampf-Reichweite <span class='nowrap'><object data=\"assets/icons/stats_blast.svg\"></object></span>",$text,1);

			$text = str_replace("Schuttpl&auml;ttchen","@@@@@",$text);
			$text = str_replace_count("Schutt","Schutt <span class='nowrap'><object data=\"assets/icons/hazard_rubble.svg\"></object></span>",$text,1);
			$text = str_replace("@@@@@","Schuttpl&auml;ttchen",$text);
			$text = str_replace_count("Schuttpl&auml;ttchen","Schuttpl&auml;ttchen <span class='nowrap'><object data=\"assets/icons/hazard_rubble.svg\"></object></span>",$text,1);

			$text = str_replace("Supertreffer","@@@@@",$text);
			$text = str_replace_count("Treffer","Treffer <span class='nowrap'><object data=\"assets/icons/dice_strike.svg\"></object></span>",$text,1);
			$text = str_replace("@@@@@","Supertreffer",$text);
			$text = str_replace_count("Supertreffer","Supertreffer <span class='nowrap'><object data=\"assets/icons/dice_super_strike.svg\"></object></span>",$text,1);

			$text = str_replace_count("unwegsames Gel&auml;nde","unwegsames <object data=\"assets/icons/terrain_rough.svg\"></object> Gel&auml;nde",$text,1);
	 
			$text = str_replace_count("VER","VER <span class='nowrap'><object data=\"assets/icons/stats_defense.svg\"></object></span>",$text,1);
			$text = str_replace("'","&rsquo;",$text);
			$text = str_replace("-","&#8209;",$text);
			$text = str_replace("A&#8209;W","<span class=\"a-die die\">A</span>&#8209;W",$text);
			$text = str_replace("B&#8209;W","<span class=\"b-die die\">B</span>&#8209;W",$text);
			$text = str_replace("P&#8209;W","<span class=\"p-die die\">P</span>&#8209;W",$text);
			
			$see_rules = "Siehe Regeln f&uuml;r weitere Informationen";
		}

		if(strpos($title,"_short")){$s=1;}
		if($s && $short < 3){$text .= " <i>(".$see_rules.")</i>";}
		foreach($icon_replacement as $k => $v){
			$text = str_replace_count(strtolower($k),strtolower($k)." <span class='nowrap'><object data=\"assets/icons/".$v.".svg\"></object></span>",$text,1);
			$text = str_replace_count($k,$k." <span class='nowrap'><object data=\"assets/icons/".$v.".svg\"></object></span>",$text,1);
		}
		$ability_replacement = array(array());
		foreach($abilities as $c => $a){
			foreach($a as $t => $x){
					$ability_replacement[$c][ucwords(str_replace("_"," ",$t))] = $c."/".$c."_".$t;
			}
		}
		$text = str_replace("</object></span>.","</object>.</span>",$text);
		$text = str_replace("</object></span>,","</object>,</span>",$text);
		if($s){$title=substr($title,0,strpos($title,"_short"));}
		
    foreach($ability_replacement as $c => $a){
     foreach($a as $t => $x){
       if(strpos($x,"trigger") === false){
         if($t != ucwords(str_replace("_"," ",$title))){
           $text = str_replace_count($t,$t." <object data=\"assets/abilities/".$x.".svg\"></object>",$text,1);
         }
       }
     }
    }
         if($format == "card" || $format == ""){
  		print("			<div class=\"ability\">
  				<div class=\"icon\"><object data=\"assets/abilities/".$category."/".$category."_".$title.$trigger.".svg".$color."\"></object></div>
  				<p class=\"text ".$title."\" style=\"".$style."\"><strong>");
  		if($s && $short >= 3){$title .= "*";}
  		print(str_replace("_"," ",$title)."</strong>&mdash;".$text."</p>
  				<div class=\"clear\"></div>
  			</div>");
  	}elseif($format == "insert"){
  	  print("		<object data=\"assets/abilities/".$category."/".$category."_".$title.$trigger.".svg".$color."\"></object>");
  	}
		$trigger="";
}

function icons($category,$title,$color="",$trigger=""){
	global $abilities;
		if($trigger=="" && $category=="trigger"){$trigger="blast";}
		if($trigger!=""){$trigger="_".$trigger;}

		print("<div class=\"icon_holder\"><object data=\"assets/abilities/".$category."/".$category."_".$title.$trigger.".svg\"></object></div>");
}

function color($color){
	if(isset($color)){
		if($color=="red"){$color="ed1c24";}
		if($color=="blue"){$color="1c75bc";}
		if($color=="green"){$color="00a651";}
		if($color=="white"){$color="fff";}
		if($color=="black"){$color="000";}
	}
	return $color;
}


function uscore($string){return strtolower(str_replace(" ","_",$string));}



function statRow($stat,$value="",$boost=null,$range="short",$cost=""){
	global $s,$statLanguage,$language,$format;
	$stat = strtolower($stat);
	$stat2 = $stat;
	$boostColor = color('blue');
	if($stat == "ultra" || $stat == "mega" || $stat == "quantum" || $stat == "alpha" || $stat == "morpher"){$stat="hyper";}
	$color="";
	if($s['quantum'] && in_array($stat,$s['quantum'])){$color="?color=red";}
	if($stat2=="cost"){$stat2=$cost;}
	print("
			 <tr class='stat".ucfirst($stat)."'>
			  <td class='statValue'>");
	if($value!==""){
		print("<div><object data='assets/icons/stats_".$stat.".svg".$color."'></object><p>".$value."</p></div></td>
			  <td class='statName'>".$statLanguage[ucname($stat2)][$language]."</td>
			 </tr>");
	}else{
		print("</td>
			  <td class='statName'></td>
			 </tr>");
	}
	if($stat=="blast"){
		if($s['quantum'] && in_array("blastRange",$s['quantum'])){
			$rangeColor = color('red');
		}else{
			$rangeColor = color('black');
		}
		print("
			 <tr class='statBlast'>
			  <td class='statRange'><object data='assets/icons/stats_range_".strtolower($range).".svg?color=".$rangeColor."'></object></td>
			 </tr>");
	}
	if($stat=="brawl" || $stat=="blast" || $stat=="power"){
		if($boost!==null){if($s['quantum'] && in_array($stat."Boost",$s['quantum'])){$color=" style=\"color:#".color('red')."\"";}$boost="<object data=\"assets/icons/stats_boost.svg?color=".$boostColor."\"></object>".$boost;}
		print("
			 <tr class='stat".ucfirst($stat)."'>
			  <td class='statBoost' ".$color.">".$boost."</td>
			 </tr>");
	}
}


function ucname($string) {
    $string =ucwords($string);

    foreach (array('-',) as $delimiter) {
      if (strpos($string, $delimiter)!==false) {
        $string =implode($delimiter, array_map('ucfirst', explode($delimiter, $string)));
      }
    }
    return $string;
}


//array of directories
function dir_list($d){
       foreach(array_diff(scandir($d),array('.','..')) as $f)if(is_dir($d.'/'.$f))$l[]=$f;
       return $l;
} 

?>
