<?php
/**
 * Template part for displaying a gallery of photos
 *
 * @package Nathalie_Mota
 */

$photos = $args['photos'];

if ($photos->have_posts()) :
    while ($photos->have_posts()) : $photos->the_post();
        $image_url = wp_get_attachment_url(get_post_thumbnail_id());
        $medium_large_url = get_the_post_thumbnail_url(get_the_ID(), 'medium_large');
        ?>
        <div class="photo-item" data-full-image="<?php echo esc_url($image_url); ?>" data-title="<?php the_title(); ?>">
            <a href="<?php the_permalink(); ?>" class="photo-link">
                <?php
                if (has_post_thumbnail()) {
                    the_post_thumbnail('medium_large');
                } else {
                    echo __('No image', 'nathalie-mota');
                }
                ?>
            </a>
            <div class="photo-overlay">
                <a href="<?php the_permalink(); ?>" class="icon eye"><i class="fa fa-eye"></i></a>
                <span class="icon fullscreen" data-photo-id="<?php the_ID(); ?>"><i class="fa fa-expand"></i></span>
            </div>
        </div>
        <?php
    endwhile;
    wp_reset_postdata();
else :
    echo '<p>' . __('No photos found', 'nathalie-mota') . '</p>';
endif;
?>
