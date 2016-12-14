<?php
/**
 * Display single product reviews (comments)
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.3.2
 */
global $woocommerce, $product;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

?>
<?php if ( comments_open() ) : ?>
<div id="reviews"><?php
	echo '<div class="row"><div id="comments" class="large-12 columns">';
	if ( get_option('woocommerce_enable_review_rating') == 'yes' ) {
		$count = $product->get_rating_count();

		if ( $count > 0 ) {
			$average = $product->get_average_rating();
			echo '<div itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">';
			echo '<h2>'.sprintf( _n('<strong>%s review</strong> for %s', '<strong>%s reviews</strong> for %s', $count, 'altotheme'), '<span itemprop="ratingCount" class="count">'.$count.'</span>', wptexturize($post->post_title) ).'</h2>';
			echo '</div>';
		} else {
			echo '<h2>'.esc_html__( 'Reviews', 'altotheme' ).'</h2>';
		}
	} else {
		echo '<span>'.esc_html__( 'Reviews', 'altotheme' ).'</span>';
	}

	$title_reply = '';
    echo '<hr/>';
    
	if ( have_comments() ) :
		echo '<ol class="commentlist">';
		wp_list_comments( array( 'callback' => 'woocommerce_comments' ) );
		echo '</ol>';
		if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
			<div class="navigation">
				<?php
					$allowed_html = array(
		                'span' => array('class' => array())
		            );
				?>
                <div class="nav-previous"><?php previous_comments_link(wp_kses(__( '<span class="meta-nav">&larr;</span> Previous', 'altotheme' ), $allowed_html) ); ?></div>
				<div class="nav-next"><?php next_comments_link( wp_kses(__( 'Next <span class="meta-nav">&rarr;</span>', 'altotheme' ), $allowed_html) ); ?></div>
			</div>
		<?php endif;
		$title_reply = esc_html__( 'Add a review', 'altotheme' ).' &ldquo;'.$post->post_title.'&rdquo;';
	else :
		$title_reply = esc_html__( 'Be the first to review', 'altotheme' ).' &ldquo;'.$post->post_title.'&rdquo;';
	endif;
	$commenter = wp_get_current_commenter();
	
    //if ( have_comments() ) :
    //    echo '</div><div id="add_review" class="large-5 columns"><div class="inner">';
    //else :
        echo '</div><div id="add_review" class="large-12 columns"><div class="inner">';
    //endif;

	$comment_form = array(
		'title_reply' => $title_reply,
		'comment_notes_before' => '',
		'comment_notes_after' => '',
		'fields' => array(
			'author' => '<p class="comment-form-author">' . '<label for="author">' . esc_html__( 'Name', 'altotheme' ) . '</label> ' . '<span class="required">*</span>' . '<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" aria-required="true" /></p>',
			'email'  => '<p class="comment-form-email"><label for="email">' . esc_html__( 'Email', 'altotheme' ) . '</label> ' . '<span class="required">*</span>' . '<input id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30" aria-required="true" /></p>',
		),
		'label_submit' => esc_html__( 'Submit', 'altotheme' ),
		'logged_in_as' => '',
		'comment_field' => ''
	);

	if ( get_option('woocommerce_enable_review_rating') == 'yes' ) {
		$comment_form['comment_field'] = '<p class="comment-form-rating"><label for="rating">' . esc_html__( 'Your Rating', 'altotheme' ) .'</label>
        <select name="rating" id="rating">
			<option value="">'.esc_html__( 'Rate&hellip;', 'altotheme' ).'</option>
			<option value="5">'.esc_html__( 'Perfect', 'altotheme' ).'</option>
			<option value="4">'.esc_html__( 'Good', 'altotheme' ).'</option>
			<option value="3">'.esc_html__( 'Average', 'altotheme' ).'</option>
			<option value="2">'.esc_html__( 'Not that bad', 'altotheme' ).'</option>
			<option value="1">'.esc_html__( 'Very Poor', 'altotheme' ).'</option>
		</select></p>';

	}
    //$commentid = 'comment_'.rand();
	$comment_form['comment_field'] .= '<p class="comment-form-comment"><label for="comment">' . esc_html__( 'Your Review', 'altotheme' ) . '</label><textarea id="comment" name="comment" cols="45" rows="22" aria-required="true"></textarea></p>'; //. wp_nonce_field('comment_rating', $commentid, $commentid);

	comment_form( apply_filters( 'woocommerce_product_review_comment_form_args', $comment_form ) );
	echo '</div></div>';
    ?></div></div>
<?php endif; ?>