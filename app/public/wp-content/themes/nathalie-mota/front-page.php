<?php
/*
Template Name: Front Page
*/

get_header(); ?>

<div id="main-content">
    <div class="hero">
        <?php if (get_theme_mod('hero_image')) : ?>
            <img src="<?php echo esc_url(get_theme_mod('hero_image')); ?>" alt="Hero Image">
        <?php endif; ?>
        <div class="hero-text">
            <h1 class="rotated-word" data-word="PHOTOGRAPHE EVENT"></h1>
        </div>
    </div>
    <div id="filters">
        <div id="left-filters">
            <div class="filter-group custom-select" id="category-select">
                <select id="category-filter">
                    <option value=""><?php _e('CATÉGORIES', 'nathalie-mota'); ?></option>
                    <?php
                    $categories = get_terms(array(
                        'taxonomy' => 'category',
                        'hide_empty' => false,
                    ));
                    foreach ($categories as $category) {
                        if ($category->slug != 'general') {
                            echo '<option value="' . esc_attr($category->slug) . '">' . esc_html($category->name) . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="filter-group custom-select" id="format-select">
                <select id="format-filter">
                    <option value=""><?php _e('FORMATS', 'nathalie-mota'); ?></option>
                    <?php
                    $formats = get_terms(array('taxonomy' => 'format', 'hide_empty' => false));
                    foreach ($formats as $format) {
                        echo '<option value="' . esc_attr($format->slug) . '">' . esc_html($format->name) . '</option>';
                    }
                    ?>
                </select>
            </div>
        </div>
        <div id="right-filter">
            <div class="filter-group custom-select" id="order-select">
                <select id="order-filter">
                    <option value=""><?php _e('TRIER PAR', 'nathalie-mota'); ?></option>
                    <option value="DESC"><?php _e('Les plus récentes', 'nathalie-mota'); ?></option>
                    <option value="ASC"><?php _e('Les plus anciennes', 'nathalie-mota'); ?></option>
                </select>
            </div>
        </div>
    </div>
    <div id="photo-list"></div>
    <button id="load-more"><?php _e('Charger plus', 'nathalie-mota'); ?></button>

    <!-- Inclure la lightbox -->
    <?php get_template_part('template-parts/lightbox'); ?>

</div>

<?php get_footer(); ?>
