<?php 
	
	include_once 'classes/Database.class.php';
	include_once 'classes/Pagination.class.php';
	$db = new Database();
	#$pagination = new Pagination();

	$leadsource = $db->select()->from('campaign')->paginate(5);
	$db->setPaginationUrl('http://localhost/vipinclasses/test.php');
	$links = $db->createLinks();
	#echo '<pre>';die(print_r($db));

	echo $links;
	
?>	

<div style="clear:left;"></div>

<table>
	<tr>
		<th>Lead source</th>
		<th>Lead Ref</th>
	</tr>
	<?php foreach($leadsource as $l):?>
	<tr>
		<td><?= $l->campaign_name;?></td>
		<td><?= $l->campaign_ref;?></td>
	</tr>
	<?php endforeach;?>
</table>

<style type="text/css">
	table tr td{
		border:solid 1px #ccc;
	}
	table tr th{
		border: solid 1px #ccc;
		font-weight: 900;
	}

	li{
		display:inline-block;
		width:20px;
		float:left;
	}
</style>