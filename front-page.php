<?php get_header(); ?>
<main class="l-content">
<div class="tab">
  <button class="tablinks active" onclick="openCity(event, 'Upcoming')">Kommande aktiviteter</button>
  <button class="tablinks" onclick="openCity(event, 'Ongoing')">Återkommande aktiviteter</button>
</div>


<div id="Upcoming" class="tabcontent">
<?php 
	// Show the selected frontpage content.
	$temp = $wp_query; $wp_query= null;
	$args = array('posts_per_page=5', '&paged='.$paged, 'meta_key' => 'startdatum', 'orderby' => 'meta_value', 'order' => 'ASC', 'post_type' => 'aktiviteter', 'meta_query' => array(
		array(
			'key'     => 'engangsforetelse_eller_aterkommande_aktivitet',
			'value'   => 'engangsforeteelse',
			'compare' => 'EXISTS',
		),
	),);
	$wp_query = new WP_Query($args); 
	$varcheckweek = 0;
	$varcheckmonth = 0;
	$varcheckelse = 0;
	$posts = get_posts( $args );
	$dateToday = new DateTime(date(Y.m.d));
	$weekToday = $dateToday->format("W");
	$varcheck = 0;


	while ($wp_query->have_posts()) : $wp_query->the_post(); 
	$eventTypeForThisFront = get_field('engangsforetelse_eller_aterkommande_aktivitet');
	$thisEndDate = get_field('slutdatum');
	$startDate = strtotime(get_field('startdatum'));
    $todaysDate = date(Y.m.d);
	$dateTest = new DateTime(get_field('startdatum'));
	$weekTest = $dateTest->format("W");
	$thisStartDate = get_field('startdatum');

    if($eventTypeForThisFront !== "aterkommande" && $thisStartDate >= $todaysDate) :  ?>
    	<?php  
    	$thisEndDate =strtotime($thisEndDate);
    	

    	if ($thisStartDate <= strtotime('30 days') && $varcheckmonth == 0) {
    		if ($weekToday == $weekTest && $varcheckweek == 0) {
    		?> 	
    		<h2>Den här veckan:</h2>

    		<?php $varcheckweek++;
    		}
    		elseif($weekToday !== $weekTest){
    			?>
    			<h2>Kommande månaden:</h2>
    			<?php	
    			$varcheckmonth++;
    		}
    	}
    	if(strtotime($thisStartDate) > strtotime('30 days') && $varcheckelse == 0)
    	{
    		if($varcheckmonth > 0 || $varcheckweek > 0){
    		$varcheckelse++;
    	?>
    	<h2>Ännu senare: </h2>
    	<?php } }
    	?>
    	<div class = frontEvent>
	    	<div class="frontEvent-date">
		    	<?php				

		    		if($thisEndDate && $startDate === $thisEndDate): ?>
						<p>
							<?php echo date_i18n("j", $startDate);?>
							<?php echo strtoupper(date_i18n("M", $startDate));?>
							<br>
							<?php echo date_i18n("D", $startDate);?>
						</p>
					<?php
						else:
					?>
						<p>
							<?php echo date_i18n("j", $startDate);?>-<?php echo date_i18n("j", $thisEndDate);?>
							<?php 
								if(date_i18n("M", $startDate) !== date_i18n("M", $thisEndDate)):
									echo strtoupper(date_i18n("M", $startDate));?>-<?php echo strtoupper(date_i18n("M", $thisEndDate));
								else:
									echo strtoupper(date_i18n("M", $startDate));
								endif;
							?>
							<br>
							<?php echo date_i18n("D", $startDate);?>-<?php echo date_i18n("D", $thisEndDate);?>
						</p>
					<?php
						endif;
					?>
			</div>
			<div class ="frontEvent-content">
				<h2>
					<a href="<?php the_permalink(); ?>" title="Read more">
						<?php the_title(); ?>
					</a>
				</h2>
				<?php 
					if (strtotime($thisStartDate) < strtotime('30 days'))
					{
						if($weekToday == $weekTest) 
						{
						
							echo ("Den här veckan!");
						}
						else{
							echo ("Inom en månad!");
						}
					}
					?>

				<?php the_excerpt(); ?>
				
			</div>
			<button class="frontEvent-readMore" onclick="location.href='<?php the_permalink() ?>';">Mer info</button>
		</div>
		<hr>
	<?php 
	$varcheck++;
	endif;
	endwhile;
	if($varcheck == 0) :
		echo "Det finns tyvärr inga inplanerade aktiviteter för tillfället. <br/> Följ vårat nyhetsbrev (?) för att få uppdateringar när vi lägger upp nya aktiviteter!";
	endif;
	if ($paged > 1) :
	?>
		<nav id="nav-posts">
			<div class="prev"><?php next_posts_link('&laquo; Previous Posts'); ?></div>
			<div class="next"><?php previous_posts_link('Newer Posts &raquo;'); ?></div>
		</nav>

	<?php else : ?>
		<nav id="nav-posts">
			<div class="prev"><?php next_posts_link('&laquo; Previous Posts'); ?></div>
		</nav>
	<?php endif; ?>
</div>
<div id="Ongoing" class="tabcontent">

        <?php
        $temp = $wp_query; $wp_query= null;
        $args = array('posts_per_page=5', '&paged='.$paged, 'meta_key' => 'startdatum', 'orderby' => 'meta_value', 'order' => 'ASC', 'post_type' => 'aktiviteter');
        $wp_query = new WP_Query($args); 
        $varCheck = 0;


        while ($wp_query->have_posts()) : 
            $wp_query->the_post(); 
       	 	$strtdate = get_field('startdatum');
       	 	$endDate = get_field('slutdatum');
            $eventTypeForThis = get_field('engangsforetelse_eller_aterkommande_aktivitet');
            if($eventTypeForThis == "aterkommande" && $strtdate <= $todaysDate && $endDate >= $todaysDate) :  ?>

            <h2>
                <a href="<?php the_permalink(); ?>" title="Read more">
                    <?php the_title(); ?>
                </a>
            </h2>
            <?php the_excerpt(); ?>
            <button class="buttonReadMore" onclick="location.href='<?php the_permalink() ?>';">Mer info</button>
            <hr>

            <?php 
            $varCheck++;
            endif;

        endwhile;

        if ($paged > 1) :
            ?>
            <nav id="nav-posts">
                <div class="prev"><?php next_posts_link('&laquo; Previous Posts'); ?></div>
                <div class="next"><?php previous_posts_link('Newer Posts &raquo;'); ?></div>
            </nav>
        <?php endif; 
            if ($varCheck == 0) :
                echo "These are not the events you're looking for.";
                $varCheck = 0;
            endif;
          ?>

</div>

</main>
<?php wp_reset_postdata();?>
<?php get_footer(); ?>
