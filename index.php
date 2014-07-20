<?php
/**
 * Plugin Name: bbPress Replies to WordPress Comments
 * Plugin URI: http://github.com/moeloubani/bbpress-replies-to-comments
 * Description: Will convert replies on bbPress forums to comments on the forum post type (topic post type).
 * Version: 1.0
 * Author: Moe Loubani
 * Author URI: http://www.moeloubani.com
 */

function ml_replies_to_comments() {
    $repliesArgs = array(
        'post_type' => 'reply',
        'posts_per_page' => -1,

    );

    $repliesQuery = new WP_Query($repliesArgs);

    if ($repliesQuery->have_posts()) {
        while ($repliesQuery->have_posts()) {
            $repliesQuery->the_post();
            $custom_meta = get_post_custom();

            $data = array(
                'comment_post_ID' => $custom_meta['_bbp_topic_id'][0],
                'comment_author' => get_the_author_meta('user_login'),
                'comment_author_email' => get_the_author_meta('user_email'),
                'comment_content' => get_the_content(),
                'comment_type' => 'comment',
                'user_id' => $post->post_author,
                'comment_author_IP' => $custom_meta['_bbp_author_ip'][0],
                'comment_agent' => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.10) Gecko/2009042316 Firefox/3.0.10 (.NET CLR 3.5.30729)',
                'comment_date' => $post->post_date,
                'comment_approved' => 1,
            );

            if (isset($_GET['repliestocomments']) && $_GET['repliestocomments'] == 1) {
                wp_insert_comment($data, $custom_meta['_bbp_topic_id'][0]);
            }

        }
    }
}

add_action('wp_footer', 'ml_replies_to_comments');

function ml_bbpress_replies_menu_page() {
    add_menu_page('bbPress to Comments', 'bbPress to Comments', 'administrator', __FILE__, 'ml_bbpress_to_comments');
}

add_action('admin_menu', 'ml_bbpress_replies_menu_page');

function ml_bbpress_to_comments() {
    ?>
    <div class="wrap">
        <h2>bbPress Replies to Comments</h2>
        <p><a href="<?php echo plugins_url('/index.php?repliestocomments=1', __FILE__); ?>">Click here to begin import. Do this only once or you will have duplicate comments! Remove the plugin after using it.</a></p>
    </div>
<?php
}
