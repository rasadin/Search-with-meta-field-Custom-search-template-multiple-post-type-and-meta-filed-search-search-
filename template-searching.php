<?php
/**
 * Template Name: Search Management
 */
get_header();
?>

<!-- https://wordpress.stackexchange.com/questions/229003/filter-by-title-content-and-meta-key-at-the-same-time -->


<!-- Start Search Form -->
<form role="search" method="get" class="search-form" action="<?php echo home_url('/test-search/'); ?>">
  <label>
    <span class="screen-reader-text">Search for:</span>
    <input type="search" class="search-field" placeholder="Search Here" value="" name="search" required>
  </label>
  <input type="submit" class="search-submit" value="Search">
</form>
<!-- End Search Form -->



<?php
if (isset($_GET['search'])) {

    $search_keyword = $_GET['search'];
    


// start this part search in titles, descriptions
    $q1 = get_posts(array(
        'post_type' => array('products','page','post'), //custom post types name
        'post_status' => 'publish',
        'posts_per_page' => '-1',
        's' => $search_keyword,
    ));
// end this part search in titles, descriptions



// start this part search in meta field
    $q2 = get_posts(array(
        'post_type' => 'products', //custom post types name
        'post_status' => 'publish',
        'posts_per_page' => '1',
        'meta_query' => array(
            'relation' => 'OR',
            array(
                'key' => 'product_header_sub_text', //meta field key 
                'value' => $search_keyword,
                'compare' => 'LIKE',
            ),
            array(
                'key' => 'product_right_img_subtext', //meta field key 
                'value' => $search_keyword,
                'compare' => 'LIKE',
            ),
            array(
                'key' => 'product_image_right_content', //meta field key 
                'value' => $search_keyword,
                'compare' => 'LIKE',
            ),
            array(
                'key' => 'product_description_features', //meta field key 
                'value' => $search_keyword,
                'compare' => 'LIKE',
            ),
            array(
                'key' => 'product_features_part', //meta field key 
                'value' => $search_keyword,
                'compare' => 'LIKE',
            ),
            array(
                'key' => 'product_suitable_missions', //meta field key 
                'value' => $search_keyword,
                'compare' => 'LIKE',
            ),
            array(
                'key' => 'product_standard_features_text_content', //meta field key 
                'value' => $search_keyword,
                'compare' => 'LIKE',
            ),
            array(
                'key' => 'product_standard_features_list', //meta field key 
                'value' => $search_keyword,
                'compare' => 'LIKE',
            ),
            array(
                'key' => 'product_inquiry_content', //meta field key 
                'value' => $search_keyword,
                'compare' => 'LIKE',
            ),
            array(
                'key' => 'product_form_footer_text', //meta field key 
                'value' => $search_keyword,
                'compare' => 'LIKE',
            ),
        ),
    ));
// end this part search in meta field



    $merged = array_merge($q1, $q2);
    $post_ids = array();
    foreach ($merged as $item) {
        $post_ids[] = $item->ID;
    }
    $unique = array_unique($post_ids);
    if (!$unique) {
        $unique = array('0');
    }
    $args = array(
        'post_type' => array('products','page','post'), //custom post type name
        // 'posts_per_page' => '1',
        'posts_per_page' =>  get_option('posts_per_page'),
        'post__in' => $unique,
        'paged' => get_query_var('paged'),
    );
    $wp_query = new WP_Query($args);

    $loop = new WP_Query($args);
    if ($loop->have_posts()):
        while ($loop->have_posts()): $loop->the_post(); ?>

        <header class="entry-header search-result-title">
		    <h2 class="entry-title sr-ti"><a href="<?php esc_url( the_permalink() ) ?>" rel="bookmark">  <?php  the_title(); ?></a></h2>
		</header>

        <?php echo "</br>";
        endwhile;

        echo "<nav class=\"sw-pagination\">";
        $big = 999999999; // need an unlikely integer
        echo paginate_links(array(
            'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
            'format' => '?paged=%#%',
            'current' => max(1, get_query_var('paged')),
            'total' => $loop->max_num_pages,
        ));
        echo "</nav>";
    endif;
}
wp_reset_query();

get_footer();
?>
