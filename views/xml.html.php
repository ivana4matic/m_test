<?php
include_once 'index.html.php';
?>
<h1>SHOW ITEMS FROM XML</h1>
<?php 

if (count($papers > 0)) {
	foreach ($papers as $item) {
		?>
		<div>
			<div><b>DOI:</b> <?php echo $item['doi']; ?></div>
			<div><b>Identifier:</b> <?php if(isset($item['identifier'])) {echo $item['identifier'];}; ?></div>
			<div><b>Title:</b> <?php echo $item['title']; ?></div>
			<div><b>Abstract:</b> <?php echo $item['abstract']; ?></div>
		</div>
		<br><br><br>
		<?php
	}
}
?>