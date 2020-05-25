<?php

$etn_speaker_designation        = get_post_meta(get_the_id(), 'etn_speaker_designation', true);
$etn_speaker_summary            = get_post_meta(get_the_id(), 'etn_speaker_summery', true);
$etn_speaker_company_logo       = get_post_meta(get_the_id(), 'etn_speaker_company_logo', true);
$etn_speaker_website_url        = get_post_meta(get_the_id(), 'etn_speaker_website_url', true);
$etn_speaker_website_email      = get_post_meta(get_the_id(), 'etn_speaker_website_email', true);
$etn_speaker_socials            = get_post_meta(get_the_id(), 'etn_speaker_socials', true);
$logo                           = wp_get_attachment_image_src($etn_speaker_company_logo);

?>
<div class="etn-single-speaker-wrapper">
	<div class="etn-row">
		<div class="etn-col-lg-5">
			<div class="etn-speaker-info">
				<?php if (has_post_thumbnail()) : ?>
					<div class="etn-speaker-thumb">
						<img src="<?php echo get_the_post_thumbnail_url(); ?>" height="150" width="150" />
					</div>
				<?php endif; ?>
				<h3 class="etn-title etn-speaker-name"> <?php echo esc_html(get_the_title()); ?> </h3>
				<p class="etn-speaker-designation"><?php echo esc_html($etn_speaker_designation); ?></p>
				<p class="etn-speaker-desc"> <?php echo esc_html($etn_speaker_summary); ?></p>
				<div class="etn-social">
					<?php if (is_array($etn_speaker_socials)) : ?>
						<?php foreach ($etn_speaker_socials as $social) : ?>
							<?php $etn_social_class = 'etn-' . str_replace('fab fa-', '', $social['icon']); ?>
							<a href="<?php echo esc_url($social['etn_social_url']); ?>" target="_blank" class="<?php echo esc_attr($etn_social_class); ?>"> <i class="<?php echo esc_attr($social['icon']); ?>"></i> </a>
						<?php endforeach; ?>
					<?php endif; ?>
				</div>
			</div>
		</div>
		<div class="etn-col-lg-7">


			<div class="etn-schedule-wrap">
				<?php
				global $wpdb;
				$speaker_id = get_the_id();
				$orgs = $wpdb->get_results("SELECT * FROM $wpdb->postmeta WHERE meta_key = 'etn_schedule_topics' AND  meta_value LIKE '%$speaker_id%'", ARRAY_A);
				foreach ($orgs as $org) {
					$etn_schedule_meta_value = unserialize($org['meta_value']);
					foreach ($etn_schedule_meta_value as $single_meta) {
						if (in_array($speaker_id,$single_meta["etn_shedule_speaker"])) {
							?>
								<div class="etn-single-schedule-item etn-row">
									<div class="etn-schedule-info etn-col-lg-4">
										<span class="etn-schedule-time"><?php echo  esc_html(date('h:i a', strtotime($single_meta["etn_shedule_start_time"]))) . " - " .  esc_html(date('h:i a', strtotime($single_meta["etn_shedule_end_time"]))); ?></span>
										<span class="etn-schedule-location"><i class="fas fa-map-marker-alt"></i><?php echo esc_html($single_meta["etn_shedule_room"]); ?></span>
									</div>
									<div class="etn-schedule-content etn-col-lg-8">
										<h4 class="etn-title"><?php echo esc_html($single_meta["etn_schedule_topic"]); ?></h4>
										<p><?php echo esc_html(trim($single_meta["etn_shedule_objective"])); ?></p>
									</div>
								</div>
							<?php
						}
					}
				}
				?>
			</div>

		</div>
	</div>
</div>