<?php
/*
 * Created on 7 mai 2009
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
require ('fichierConf.class.php');
$form_name='admin_rsx';
$ban_head='no';
$no_error='YES';
require_once("header.php");
if (!($_SESSION["lvluser"] == SADMIN or $_SESSION['TRUE_LVL'] == SADMIN))
	die("FORBIDDEN");
echo "<br><br><br>";	
if ($_SESSION['ipdiscover_methode'] != 'local.php'){
	echo "<font color=red><b>".$l->g(929)."<br>".$l->g(930)."</b></font>";	
	require_once($_SESSION['FOOTER_HTML']);
	die();
}
//if( $_SESSION["lvluser"]!=LADMIN && $_SESSION["lvluser"]!=SADMIN  )
//	die("FORBIDDEN");
if (isset($protectedGet['value'])){
	$title=$l->g(931);
	$netid=mysql_escape_string($protectedGet['value']);
	$protectedPost["ADD_IP"]=$netid;
	if (!isset($protectedPost["RSX_NAME"])){
		$sql="select NAME,ID,MASK from subnet where netid='".$netid."'";
		$res=mysql_query($sql, $_SESSION["readServer"]) or die(mysql_error($_SESSION["readServer"]));
		$row=mysql_fetch_object($res);
		$protectedPost["RSX_NAME"]=xml_decode($row->NAME);
		$protectedPost["ID_NAME"]=xml_decode($row->ID);
		$protectedPost["ADD_SX_RSX"]=xml_decode($row->MASK);
	}
	$tab_typ_champ[2]['INPUT_TYPE']=3;

}
else{
$title=$l->g(303);
$tab_typ_champ[2]['INPUT_TYPE']=0;
}

if (isset($protectedPost['Reset_modif_x'])){
	echo "<script>";
	echo "self.close();</script>";	
}

if (isset($protectedPost['Valid_modif_x'])){
	print_r($protectedPost);
	if (trim($protectedPost['ADD_IP']) == '')
	$ERROR=$l->g(932);	
	if (trim($protectedPost['RSX_NAME']) == '')
	$ERROR=$l->g(933);	
	if (trim($protectedPost['ID_NAME']) == '')
	$ERROR=$l->g(934);
	if (trim($protectedPost['ADD_SX_RSX']) == '')
	$ERROR=$l->g(935);
	if (!isset($ERROR)){
		//$post=escape_string($protectedPost);
		$sql_verif="select NETID from subnet where netid='".$protectedPost['ADD_IP']."'";
		$res_verif=mysql_query($sql_verif, $_SESSION["readServer"]) or die(mysql_error($_SESSION["readServer"]));
		$row_verif=mysql_fetch_object($res_verif);
		if (isset($row_verif->NETID)){
			$sql="update subnet set name='".$protectedPost['RSX_NAME']."', id='".$protectedPost['ID_NAME']."', MASK='".$protectedPost['ADD_SX_RSX']."'
				where netid = '".$protectedPost['ADD_IP']."'";			
		}else{	
			$sql="insert into subnet (netid,name,id,mask) VALUES ('".$protectedPost['ADD_IP']."','".$protectedPost['RSX_NAME']."',
					'".$protectedPost['ID_NAME']."','".$protectedPost['ADD_SX_RSX']."')";
		}
		//echo $sql;
		mysql_query($sql, $_SESSION["writeServer"]) or die(mysql_error($_SESSION["writeServer"]));
		//suppression du cache pour prendre en compte la modif
		unset($_SESSION['DATA_CACHE']['IPDISCOVER']);
		echo "<script>";
		echo "window.opener.document.forms['ipdiscover'].submit();";
		echo "self.close();</script>";
	}
	else
	echo "<center><font color=red size=3><b>".$ERROR."</b></font></center>";
	
	
}

$tab_typ_champ[0]['DEFAULT_VALUE']=$protectedPost['RSX_NAME'];
$tab_typ_champ[0]['INPUT_NAME']="RSX_NAME";
$tab_typ_champ[0]['CONFIG']['SIZE']=60;
$tab_typ_champ[0]['CONFIG']['MAXLENGTH']=255;
$tab_typ_champ[0]['INPUT_TYPE']=0;
$tab_name[0]=$l->g(304).": ";
$tab_typ_champ[1]['DEFAULT_VALUE']=$protectedPost['ID_NAME'];
$tab_typ_champ[1]['INPUT_NAME']="ID_NAME";
$tab_typ_champ[1]['INPUT_TYPE']=0;
$tab_name[1]=$_SESSION["ipdiscover_id"].":";
$tab_typ_champ[2]['DEFAULT_VALUE']=$protectedPost['ADD_IP'];
$tab_typ_champ[2]['INPUT_NAME']="ADD_IP";
$tab_name[2]=$l->g(34).": ";
$tab_typ_champ[3]['DEFAULT_VALUE']=$protectedPost['ADD_SX_RSX'];
$tab_typ_champ[3]['INPUT_NAME']="ADD_SX_RSX";
$tab_typ_champ[3]['INPUT_TYPE']=0;
$tab_name[3]=$l->g(208).": ";
$tab_hidden['NETID']=$netid;
tab_modif_values($tab_name,$tab_typ_champ,$tab_hidden,$title,$comment="");
	
require_once($_SESSION['FOOTER_HTML']);
?>
