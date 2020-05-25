<?php

$etn_speaker_count = $settings["etn_speaker_count"];
$etn_speaker_col = $settings["etn_speaker_col"];
$etn_speaker_order = $settings["etn_speaker_order"];

$args = array(
    'posts_per_page'      => $etn_speaker_count,
    'orderby'          => 'post_date',
    'order' => $etn_speaker_order,
    'post_type'        => 'etn-speaker',
    'post_status'      => 'publish',
    'suppress_filters' => false,
);
if (is_array($settings['speakers_category']) && count($settings['speakers_category'])) {
    $args['tax_query'] = array(
        array(
            'taxonomy' => 'etn_speaker_category',
            'terms'    => $settings['speakers_category'],
            'field' => 'id',
            'include_children' => true,
            'operator' => 'IN'
        ),
    );
};
$speakers_query = new \WP_Query($args);
?>
<?php if ($speakers_query->have_posts()) { ?>
    <div class='etn-row etn-speaker-wrapper'>
        <?php
        while ($speakers_query->have_posts()) {
            $speakers_query->the_post();
            $etn_speaker_designation = get_post_meta(get_the_ID(), 'etn_speaker_designation', true);
            $social = get_post_meta(get_the_ID(), 'etn_speaker_socials', true);
        ?>
            <div class="etn-col-lg-<?php echo esc_attr($etn_speaker_col); ?> etn-col-md-6">
                <div class="etn-speaker-item">
                    <div class="etn-speaker-thumb">
                        <?php if (has_post_thumbnail()) { ?>
                            <a href="<?php echo esc_url(get_the_permalink()); ?>">
                                <?php the_post_thumbnail(); ?>
                            </a>
                        <?php } ?>
                        <div class="etn-speakers-social">
                            <?php if (is_array($social)) { ?>
                                <?php foreach ($social as $social_value) {  ?>
                                    <a href="<?php echo esc_url($social_value["etn_social_url"]); ?>" title="<?php echo esc_attr($social_value["etn_social_title"]); ?>">
                                        <i class="<?php echo esc_attr($social_value["icon"]); ?>"></i>
                                    </a>
                                <?php  } ?>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="etn-speaker-content">
                        <h3 class="etn-title etn-speaker-title"><a href="<?php echo esc_url(get_the_permalink()); ?>"> <?php echo esc_html(get_the_title()); ?></a> </h3>
                        <p>
                            <?php echo esc_html($etn_speaker_designation); ?>
                        </p>
                    </div>
                </div>
            </div>
        <?php
        }
        ?>
    </div>
<?php }
wp_reset_query();

?>