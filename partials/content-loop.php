<div id="<?php echo 'posts-'.get_the_ID(); ?>" class="item pgafu-medium-4 pgafu-columns filtr-item">
    <div class="pgafu-post-grid">
        <div class="pgafu-post-grid-content">
            <div class="pgafu-post-image-bg">
                <a href="<?php the_permalink();?>" title="<?php the_title(); ?>">
                    <?php the_post_thumbnail( 'full' ); ?>                    
                </a>
            </div>
            <h3 class="pgafu-post-title">
                <a href="<?php the_permalink();?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
            </h3>
            <div class="pgafu-post-categories">
                <?php 
                    $cat = 'category';
                    $postcats = get_the_terms($post->ID, $cat);
                    if($postcats) {
                        foreach ( $postcats as $term ) {
                            $term_link = get_term_link( $term );
                            echo '<a href="' . esc_url( $term_link ) . '">'.$term->name.'</a>';
                        }
                    }
                ?>
            </div>
			<div class="post-excerpt">
				<?php the_excerpt(); ?>
			</div>
            <input type="hidden" id="paged" class="" value="1">
        </div>
    </div>
</div>