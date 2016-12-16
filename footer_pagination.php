<!-- atur halaman tabel -->
<nav>
	<ul class="pagination">
		<li><a href="user.php?r=<?php echo $_GET['r']; ?>&mod=<?php echo $_GET['mod']; ?>&page=<?php echo $page-1; ?>" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a> </li>
	<?php 
		for($x=1;$x<=$halaman;$x++)
		{
			?>
				<li><a href="user.php?r=<?php echo $_GET['r']; ?>&mod=<?php echo $_GET['mod']; ?>&page=<?php echo $x; ?>"	><?php echo $x; ?><span class="sr-only">(current)</span></a></li>
			<?php 
		}					
	?>
		<li><a href="user.php?r=<?php echo $_GET['r']; ?>&mod=<?php echo $_GET['mod']; ?>&page=<?php echo $page+1; ?>" aria-label="Next"><span aria-hidden="true">&raquo;</span></a> </li>
	</ul>
</nav>	