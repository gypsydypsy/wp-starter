<?php

defined('ABSPATH') or exit('Please don&rsquo;t call the plugin directly. Thanks :)');

function seopress_print_section_info_bot()
{
    $docs = function_exists('seopress_get_docs_links') ? seopress_get_docs_links() : ''; ?>
    <div class="sp-section-header">
        <h2>
            <?php esc_html_e('Scan', 'wp-seopress-pro'); ?>
        </h2>
    </div>
    <p><?php esc_html_e('The bot scans links in your content to find errors (404...). We limit this search by default to the last 100 posts/pages/custom post types.', 'wp-seopress-pro'); ?>

    <p>
        <?php esc_html_e('You can increase this value in the settings tab.', 'wp-seopress-pro'); ?>

        <a class="seopress-help" href="<?php echo esc_url($docs['bot']); ?>" target="_blank">
            <?php esc_html_e('Check our guide', 'wp-seopress-pro'); ?>
        </a>
        <span class="seopress-help dashicons dashicons-external"></span>
    </p>

    <a href="<?php echo esc_url( admin_url('edit.php?post_type=seopress_bot') ); ?>" class="btn btnTertiary">
        <?php esc_html_e('View scan results', 'wp-seopress-pro'); ?>
    </a>

<?php
}

function seopress_print_section_info_bot_settings()
{ ?>
    <div class="sp-section-header">
        <h2>
            <?php esc_html_e('Settings', 'wp-seopress-pro'); ?>
        </h2>
    </div>
    <p>
        <?php esc_html_e('Edit your broken links settings.', 'wp-seopress-pro'); ?>
    </p>

<?php
}
