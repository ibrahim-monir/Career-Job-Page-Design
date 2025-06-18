<?php get_header(); ?>

<style>
    .career-container {
        display: grid;
        grid-template-columns: 300px 1fr;
        gap: 30px;
        padding: 40px 20px;
    }

    .career-sidebar {
        background: #f9f9f9;
        padding: 20px;
        border: 1px solid #ddd;
    }

    #career-filter .filter-sub-title{
        margin-bottom: 10px;
    }

    .career-main {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }
    #career-results{
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
    }

    .career-item {
    border: 1px solid #ddd;
    padding: 20px;
    background-color: #f9f9f9;
    min-height: 275px;
    display: grid;
    align-content: space-between;
}

.career-item:hover{
    box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;
}

    .career-search {
        margin-bottom: 20px;
    }
    .career-item h2 {
    font-size: 15px;
}

.job-details {
    display: flex;
    flex-wrap: wrap;
    font-size: 0.9rem;
    color: #666;
}

.job-details p {
    margin-right: 20px;
}

.career-excerpt {
    margin-top: 10px;
}

.apply-button {
    margin: 15px 0 10px 0;
}

.apply-button a {
    background-color: #000;
    color: #fff;
    padding: 10px 15px;
    border-radius: 5px;
    text-decoration: none;
}
@media only screen and (max-width: 719px) {
    .career-container {
        display: block;
        padding: 40px 20px;
    }
    .career-search {
    margin-top: 20px;
    margin-bottom: 0;
}
.career-item {
    min-height: 300px;
}
.job-details {
    display: block;
}
form#career-filter label {
    font-size: 14px;
}
}
</style>

<div class="career-container">
    <!-- Sidebar Filter -->
    <div class="career-sidebar">
        <h3 style="border-bottom: 1px solid #ededed; margin-bottom: 20px;">Filter Careers</h3>
        <form id="career-filter">
            <!-- Job Type -->
            <label class="filter-sub-title"><strong>Job Type</strong></label><br>
            <?php
            $types = get_terms(['taxonomy' => 'career_type', 'hide_empty' => false]);
            foreach ($types as $type) {
                echo "<label><input type='checkbox' name='career_type[]' value='{$type->slug}'> {$type->name}</label><br>";
            }
            ?>

            <!-- Location -->
            <br><label><strong>Location</strong></label><br>
            <?php
            $locations = get_terms(['taxonomy' => 'career_location', 'hide_empty' => false]);
            foreach ($locations as $location) {
                echo "<label><input type='checkbox' name='career_location[]' value='{$location->slug}'> {$location->name}</label><br>";
            }
            ?>

            <!-- Hidden field for AJAX -->
            <input type="hidden" name="action" value="filter_careers">
        </form>
    </div>

    <!-- Main Content -->
    <div class="career-main">
        <div class="career-search">
            <input type="text" id="career-search-input" placeholder="Search jobs..." style="width:100%;padding:10px;border:1px solid #ccc;">
        </div>

        <!-- Job Listings -->
    <div id="career-results">
        <?php
        if (have_posts()) :
            while (have_posts()) : the_post();
        ?>
            <!-- Wrapper Container for Grid Layout -->
<div class="career-grid">
    <div class="career-item">
        <!-- Job Title -->
        <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>

        <!-- Additional Job Details -->
        <div class="job-details">
            <p class="location"><i class="fas fa-map-marker-alt"></i> <?php echo get_the_term_list(get_the_ID(), 'career_location', '', ', ', ''); ?></p>
            <p class="job-type"><i class="fas fa-briefcase"></i> <?php echo get_the_term_list(get_the_ID(), 'career_type', '', ', ', ''); ?></p>
            <?php
            $deadline = get_post_meta(get_the_ID(), 'job_deadline', true);
            if ($deadline) {
                echo "<p><strong>Deadline:</strong> $deadline</p>";
            }
            ?>
        </div>

        <!-- Job Excerpt -->
        <div class="career-excerpt">
            <p><?php echo wp_trim_words(get_the_excerpt(), 20); ?></p>
        </div>

        <!-- Apply Button -->
        <div class="apply-button">
            <a href="<?php the_permalink(); ?>">Apply Now</a>
        </div>
    </div>
</div>

        <?php endwhile;
        else :
            echo '<p>No careers found.</p>';
        endif;
        ?>
    </div>
</div>

<script>
jQuery(function($) {
    function fetchCareers() {
        var formData = $('#career-filter').serialize();
        formData += '&search=' + $('#career-search-input').val();

        $.ajax({
            url: '<?php echo admin_url("admin-ajax.php"); ?>',
            data: formData,
            type: 'POST',
            success: function(response) {
                $('#career-results').html(response);
            }
        });
    }

    $('#career-filter input').on('change', fetchCareers);
    $('#career-search-input').on('input', fetchCareers);
});
</script>

<?php get_footer(); ?>
