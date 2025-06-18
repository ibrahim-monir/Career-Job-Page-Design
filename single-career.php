<?php
get_header();
if (have_posts()) :
    while (have_posts()) : the_post();
?>

        <div class="career-single" style="padding: 50px 0;">

            <!-- Feature Section -->
            <div class="career-feature-section" style="margin-bottom: 40px;">
                <?php if (has_post_thumbnail()) : ?>
                    <div class="career-image" style="text-align: center;">
                        <?php the_post_thumbnail('full'); ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Content Section with Sidebar -->
            <div class="career-content-section" style="display: flex; flex-wrap: wrap; gap: 30px;">

                <!-- Left Main Content -->
                <div class="career-main-content" style="flex: 1 1 65%; min-width: 300px;">
                    <!-- Job Title -->
                    <h1 class="career-title" style="font-size: 2rem; margin-bottom: 15px;">
                        <?php the_title(); ?>
                    </h1>

                    <!-- Job Info -->
                    <div class="career-info" style="display: flex; flex-wrap: wrap; font-size: 1rem; color: #666; margin-bottom: 20px;">
                        <p style="margin-right: 20px;"><i class="fas fa-map-marker-alt"></i> <strong><?php echo get_the_term_list(get_the_ID(), 'career_location', '', ', ', ''); ?></strong></p>
                        <p style="margin-right: 20px;"><i class="fas fa-briefcase"></i> <strong><?php echo get_the_term_list(get_the_ID(), 'career_type', '', ', ', ''); ?></strong></p>
                        <?php
                        $deadline = get_post_meta(get_the_ID(), 'job_deadline', true);
                        if ($deadline) {
                            echo "<p><strong>Deadline:</strong> $deadline</p>";
                        }
                        ?>
                    </div>

                    <!-- Job Description -->
                    <div class="career-details" style="font-size: 1rem; color: #333;">
                        <?php the_content(); ?>
                    </div>
                </div>

                <!-- Right Sidebar -->
                <div class="career-sidebar" style="flex: 1 1 30%; min-width: 280px; position:sticky; top: 220px; align-self: flex-start;">
                    <div style="background: #f9f9f9; padding: 20px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                        <h2 style="font-size: 26px !important; margin-bottom: 15px; border-bottom: 2px solid #ddd; padding-bottom: 10px;">Similar Jobs</h2>
                        <ul style="list-style: none; padding: 0; margin: 0;">
                            <?php
                            $terms = wp_get_post_terms(get_the_ID(), 'career_type', array('fields' => 'ids'));
                            $args = array(
                                'post_type' => 'career',
                                'posts_per_page' => 5,
                                'post__not_in' => array(get_the_ID()),
                                'tax_query' => array(
                                    array(
                                        'taxonomy' => 'career_type',
                                        'field'    => 'term_id',
                                        'terms'    => $terms,
                                    ),
                                ),
                            );
                            $similar_jobs = new WP_Query($args);
                            if ($similar_jobs->have_posts()) :
                                while ($similar_jobs->have_posts()) : $similar_jobs->the_post(); ?>
                                    <li style="margin-bottom: 20px; padding: 10px; border: 1px solid #e0e0e0; border-radius: 5px; transition: all 0.3s ease;">
                                        <a href="<?php the_permalink(); ?>" style="display: block; color: #333; font-weight: 500; text-decoration: none; font-size: 24px; padding-bottom: 10px;">
                                            <?php the_title(); ?>
                                        </a>
                                        <div class="job-details" style="display: flex; justify-content: space-between;">
                                            <p class="location"><i class="fas fa-map-marker-alt"></i> <?php echo get_the_term_list(get_the_ID(), 'career_location', '', ', ', ''); ?></p>
                                            <p class="job-type"><i class="fas fa-briefcase"></i> <?php echo get_the_term_list(get_the_ID(), 'career_type', '', ', ', ''); ?></p>
                                            <?php
                                            $deadline = get_post_meta(get_the_ID(), 'job_deadline', true);
                                            if ($deadline) {
                                                echo "<p><strong>Deadline:</strong> $deadline</p>";
                                            }
                                            ?>
                                        </div>
                                    </li>
                            <?php endwhile;
                                wp_reset_postdata();
                            else :
                                echo '<li>No similar jobs found.</li>';
                            endif;
                            ?>
                        </ul>
                    </div>
                </div>


            </div> <!-- End Content Section -->

        </div> <!-- End Career Single -->

<?php
    endwhile;
else :
    echo '<p>No career post found.</p>';
endif;
get_footer();
?>