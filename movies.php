<?php
/** Template Name: Movies */
 

get_header(); ?>
<script>
    jQuery(function($){
	$('#filter').submit(function(){
		var filter = $('#filter');
		$.ajax({
			url:filter.attr('action'),
			data:filter.serialize(), // form data
			type:filter.attr('method'), // POST
			beforeSend:function(xhr){
				filter.find('button').text('Processing...'); // changing the button label
			},
			success:function(data){
				filter.find('button').text('Apply filter'); // changing the button label back
				$('#response').html(data); // insert data
			}
		});
		return false;
	});
});
</script>
<div class="wrap">
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
        <h1>Search Movies</h1>

        
        <form action="<?php echo site_url() ?>/wp-admin/admin-ajax.php" method="POST" id="filter">
	<?php
		if( $terms = get_terms( array('post_type' => 'movies', 'taxonomy' => 'category', 'orderby' => 'name' ) ) ) : 

			echo '<select name="categoryfilter"><option value="">Select Movie category...</option>';
			foreach ( $terms as $term ) :
				echo '<option value="' . $term->term_id . '">' . $term->name . '</option>'; // ID of the category as the value of an option
			endforeach;
            echo '</select>';
            
		endif;
	?>
        <select name="filter_movie_rating_select_field" id="filter_movie_rating_select_field">
            <option value="1">1 Star</option>
            <option value="2">2 Star</option>
            <option value="3">3 Star</option>
            <option value="4">4 Star</option>
            <option value="5">5 Star</option>
        </select>
        
	<button>Apply filter</button>
	<input type="hidden" name="action" value="myfilter">
</form>
<br>
<br>

<div id="response"></div>

			
		</main><!-- #main -->
	</div><!-- #primary -->
</div><!-- .wrap -->

<?php
get_footer();
