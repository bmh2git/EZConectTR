<?php
class Ez_Paginator
{
	public static function renderHTML($p)
	{
		/*
		 * url, resultsPerPage, total, currentPage
		*/
	
		$p['currentPage'] = isset($p['currentPage']) ? intval($p['currentPage']) : 1;
		$totalPages = $p['total']/$p['resultsPerPage'] + 1 ;
	
	
		if($totalPages > 2)
		{
			?>
						<div class='row text-right'>
							<ul class="pagination">
								<?php
								$step = 2; 
								for ($n =1; $n <= $totalPages; $n++)
								{
								?>
									
									<?php
									
									if($totalPages > $step*2 + 1)
									{
										$continue = false;
										
										//blocul de start
										if($n == 1 && $p['currentPage'] >= $step*2)
										{
											?>
											<li><a href="<?php echo $p['url']?>/page/<?php echo $n?>"><?php echo $n?></a></li>
											<li><a href="#">...</a></li>
											<?php 
										}
										//blocul din mijloc
										if($p['currentPage'] + $step < $n ||  $p['currentPage'] - $step > $n)
										{
											$continue = true;
										}
										//blocul de end
										if($n >= $totalPages-1 && $p['currentPage']  < $n - $step )
										{
										
											?>
											<li><a href="#">...</a></li>
											<li><a href="<?php echo $p['url']?>/page/<?php echo $n?>"><?php echo $n?></a></li>
											
											<?php 
										}
										if($continue) continue;
									}
					
									?>							
									<li <?php if($n==$p['currentPage']){?>class='active'<?php }?>><a href="<?php echo $p['url']?>/page/<?php echo $n?>"><?php echo $n?></a></li>
								<?php 
								}
								?>
							</ul>
						</div>
						<?php 
					}	
						
		}
}