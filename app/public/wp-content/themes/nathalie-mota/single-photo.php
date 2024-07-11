<?php
/*
Template Name: Single Photo
*/

get_header(); ?>
<div class="single-photo-container">
    <div id="photo-details" class="photo-details">
        <!-- Bloc de gauche (informations) -->
        <div class="photo-fields-info">
            <h1 class="field"><?php the_title(); ?></h1>
            <p class="field"><?php echo __('Référence : ', 'nathalie-mota') . esc_html(get_post_meta(get_the_ID(), '_photo_reference', true)); ?></p>
            <p class="field"><?php echo __('Catégorie : ', 'nathalie-mota') . strip_tags(get_the_term_list(get_the_ID(), 'category', '', ', ', '')); ?></p>
            <p class="field"><?php echo __('Format : ', 'nathalie-mota') . strip_tags(get_the_term_list(get_the_ID(), 'format', '', ', ', '')); ?></p>
            <p class="field"><?php echo __('Type : ', 'nathalie-mota') . esc_html(get_post_meta(get_the_ID(), 'Type', true)); ?></p>
            <p class="field"><?php echo __('Année : ', 'nathalie-mota') . date('Y', strtotime(get_post_meta(get_the_ID(), '_photo_date', true))); ?></p>
        </div>

        <!-- Bloc de droite (image) --> 
        <?php
        if (has_post_thumbnail()) {
            the_post_thumbnail('large', array('class' => 'photo-thumbnail'));
        } else {
            echo __('No image', 'nathalie-mota');
        }
        ?>  
    </div>

    <!-- Bloc inférieur contact et suivant - précédent -->

    <div id="photo-interactions" class="photo-interactions">
        <div class="contact-link">
            <p id="contact-request"><?php _e('Cette photo vous intéresse ?', 'nathalie-mota'); ?></p>
            <a href="#contact-modal" class="open-contact-modal" data-photo-reference="<?php echo esc_attr(get_post_meta(get_the_ID(), '_photo_reference', true)); ?>"><?php _e('Contact', 'nathalie-mota'); ?></a>
            </div>
            
        <div class="navigation-links">
            <?php
            $prev_post = get_previous_post();
            if ($prev_post) {
                $prev_thumbnail = get_the_post_thumbnail_url($prev_post->ID, 'thumbnail');
                echo '<a href="' . get_permalink($prev_post->ID) . '" class="prev" data-thumbnail="' . $prev_thumbnail . '">← ' . __('', 'nathalie-mota') . '</a>';
            }

            $next_post = get_next_post();
            if ($next_post) {
                $next_thumbnail = get_the_post_thumbnail_url($next_post->ID, 'thumbnail');
                echo '<a href="' . get_permalink($next_post->ID) . '" class="next" data-thumbnail="' . $next_thumbnail . '">' . __('', 'nathalie-mota') . ' →</a>';
            }
            ?>
            <div id="thumbnail-preview">
                <img id="thumbnail-image" src="" alt="Thumbnail">
            </div>
        </div>  
    </div>

    <!-- Section "Vous aimerez aussi" -->
    <div class="bottom-section">
    <!-- Texte "Vous aimerez aussi" -->
        <div class="related-section">
            <p><?php _e('VOUS AIMEREZ AUSSI', 'nathalie-mota'); ?></p>
        </div>

        <!-- Photos similaires -->
        <div id="related-photos" class="related-photos">
            <?php
            $categories = get_the_terms(get_the_ID(), 'category');
            $category_ids = array();
            if ($categories) {
                foreach ($categories as $category) {
                    $category_ids[] = $category->term_id;
                }
            }

            $related_args = array(
                'post_type' => 'photo',
                'posts_per_page' => 2,
                'post__not_in' => array(get_the_ID()),
                'tax_query' => array(
                    array(
                        'taxonomy' => 'category',
                        'field' => 'term_id',
                        'terms' => $category_ids,
                    ),
                ),
            );

            $related_photos = new WP_Query($related_args);

            if ($related_photos->have_posts()) :
                
                while ($related_photos->have_posts()) : $related_photos->the_post();
                $image_url = wp_get_attachment_url(get_post_thumbnail_id());
                $reference = get_post_meta(get_the_ID(), '_photo_reference', true);
                $categories = get_the_terms(get_the_ID(), 'category');
                $category_names = wp_list_pluck($categories, 'name');
                ?>
                <div class="photo-item" 
                    data-full-image="<?php echo esc_attr($image_url); ?>" 
                    data-reference="<?php echo esc_attr($reference); ?>" 
                    data-category="<?php echo esc_attr(implode(', ', $category_names)); ?>">
                    <a href="<?php the_permalink(); ?>" class="photo-link">
                        <?php if (has_post_thumbnail()) : ?>
                            <?php the_post_thumbnail('large'); ?>
                        <?php else : ?>
                            <?php _e('No image', 'nathalie-mota'); ?>
                        <?php endif; ?>
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
                echo '<p>' . __('No related photos found', 'nathalie-mota') . '</p>';
            endif;
            ?>
        </div>
    </div>
</div>

<!-- Inclure la lightbox -->
<?php get_template_part('template-parts/lightbox'); ?>

<?php get_footer(); ?>


