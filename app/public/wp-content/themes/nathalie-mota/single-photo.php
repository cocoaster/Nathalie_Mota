<?php
/*
Template Name: Single Photo
*/

get_header(); ?>

<div id="photo-details" style="display: flex; height: calc(100vh - 118px);">
    <!-- Bloc de gauche (informations) -->
    <div class="photo-info" style="width: 50%; padding: 20px; display: flex; flex-direction: column;">
        <h1><?php the_title(); ?></h1>
        <p><?php echo __('Référence : ', 'nathalie-mota') . esc_html(get_post_meta(get_the_ID(), '_photo_reference', true)); ?></p>
        <p><?php echo __('Catégorie : ', 'nathalie-mota') . strip_tags(get_the_term_list(get_the_ID(), 'category', '', ', ', '')); ?></p>
        <p><?php echo __('Format : ', 'nathalie-mota') . strip_tags(get_the_term_list(get_the_ID(), 'format', '', ', ', '')); ?></p>
        <p><?php echo __('Type : ', 'nathalie-mota') . esc_html(get_post_meta(get_the_ID(), 'Type', true)); ?></p>
        <p><?php echo __('Année : ', 'nathalie-mota') . date('Y', strtotime(get_post_meta(get_the_ID(), '_photo_date', true))); ?></p>
    </div>

    <!-- Bloc de droite (image) -->
    <div class="photo-image" style="width: 50%; display: flex; justify-content: center; align-items: center;">
        <?php
        if (has_post_thumbnail()) {
            the_post_thumbnail('large', array('style' => 'max-width: 100%; max-height: 100%; object-fit: contain;'));
        } else {
            echo __('No image', 'nathalie-mota');
        }
        ?>
    </div>
</div>

<!-- Bloc inférieur pour les interactions -->
<div id="photo-interactions" style="height: 118px; display: flex; justify-content: space-between; align-items: center; padding: 20px;">
    <div class="contact-link">
        <a href="#contact-modal" class="open-contact-modal" data-photo-reference="<?php echo esc_attr(get_post_meta(get_the_ID(), '_photo_reference', true)); ?>"><?php _e('Contact', 'nathalie-mota'); ?></a>
    </div>
    <div class="navigation-links" style="position: relative;">
        <?php
        $prev_post = get_previous_post();
        if ($prev_post) {
            $prev_thumbnail = get_the_post_thumbnail_url($prev_post->ID, 'thumbnail');
            echo '<a href="' . get_permalink($prev_post->ID) . '" class="prev" data-thumbnail="' . $prev_thumbnail . '">← ' . __('Précédente', 'nathalie-mota') . '</a>';
        }

        $next_post = get_next_post();
        if ($next_post) {
            $next_thumbnail = get_the_post_thumbnail_url($next_post->ID, 'thumbnail');
            echo '<a href="' . get_permalink($next_post->ID) . '" class="next" data-thumbnail="' . $next_thumbnail . '">' . __('Suivante', 'nathalie-mota') . ' →</a>';
        }
        ?>
        <div id="thumbnail-preview" style="position: absolute; bottom: 40px; right: 0; display: none;">
            <img id="thumbnail-image" src="" alt="Thumbnail" style="width: 100px; height: auto; box-shadow: 0px 0px 6px rgba(0,0,0,0.5);">
        </div>
    </div>
</div>

<?php get_footer(); ?>