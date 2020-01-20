<!DOCTYPE html>
<html lang="es">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	
	<title>AXTI Sistemas</title>
 
	<style>
	section>div	{clear:both;}
	.group		{overflow:hidden;padding:2px;}
	section .group:nth-child(odd) {background:#e7f5ff;}
	.directory	{font-weight:bold;}
	.name		{float:left;width:450px;overflow:hidden;}
	.mime		{float:left;margin-left:10px;}
	.size		{float:right;}
	.bold		{font-weight:bold;}
	footer		{text-align:center;margin-top:20px;}
	</style>
</head>
 
<body>
<?php
// obtenemos la ruta a revisar, y la ruta anterior para volver...
if($_GET["path"])
{
	$path=$_GET["path"];
	$back=implode("/",explode("/",$_GET["path"],-2));
	if($back)
		$back.="/*";
	else
		$back="*";
}else{
	$path="*";
}
?>
<header>
	<h1>AXTI Sistemas</h1>
</header>
<nav>
	<h2><?php echo $path?></h2>
</nav>

<section>
	<?php
	// si no estamos en la raiz, permitimos volver hacia atras.
	if($path!="*")
		echo "<div class='bold group'><a href='?path=".$back."'>...</a></div>";

	// devuelve el tipo mime de su extension
	$finfo1 = finfo_open(FILEINFO_MIME_TYPE);
	// devuelve la codificacion mime del fichero
	$finfo2 = finfo_open(FILEINFO_MIME_ENCODING);
	
	$folder=0;
	$file=0;
	# recorremos todos los archivos de la carpeta.
	foreach (glob($path) as $filename)
	{
		$fileMime=finfo_file($finfo1, $filename);
		$fileEncoding=finfo_file($finfo2, $filename);
		if($fileMime=="directory")
		{
			$folder+=1;
			// mostramos la carpeta y permitimos pulsar sobre la misma.
			echo "<div class='directory group'>
				<a href='?path=".$filename."/*' class='name'>".end(explode("/",$filename))."</a>
				<div class='mime'>(".$fileEncoding.")</div>
			</div>";
		}else{
			$file+=1;
			// mostramos la informacion del archivo y permitimos pulsar para descargar.
			echo "<div class='group'>
				<a href='$filename' class='name'>".end(explode("/",$filename))."</a>
				<div class='size'>".number_format(filesize($filename)/1048576, 2)." Mb</div>
				<div class='mime'>".$fileMime." (".$fileEncoding.")</div>
			</div>";
		}
	}
	
	finfo_close($finfo1);
	finfo_close($finfo2);
	?>
	<footer>
		<?php echo $folder?> carpeta/s y <?php echo $file?> archivo/s<br /><br />
		Solo para <strong>uso interno</strong> de <a href='https://www.axti.net'>AXTI Sistemas</a>
	</footer>
</section>
</body>
</html>
