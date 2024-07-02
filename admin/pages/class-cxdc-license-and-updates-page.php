<?php

class Webmasterpro_License_And_Updates
{

    private $plugin_name;
    private $plugin_version;
    private $plugin_slug;
    private $plugin_author;

    public function __construct($name, $version, $slug, $author)
    {
        $this->plugin_name = $name;
        $this->plugin_version = $version;
        $this->plugin_slug = $slug;
        $this->plugin_author = $author;
    }

    // Function to get the latest version from GitHub
    private function get_latest_version_from_github()
    {
        // Get options for repo owner, repo name, and branch/tag
        $repo_owner = get_option('cxdc_webmaster_pro_plugin_repo_owner');
        $repo_name = get_option('cxdc_webmaster_pro_plugin_repo_name');
        $branch_or_tag = get_option('cxdc_webmaster_pro_plugin_repo_tagname');
        $file_path = get_option('cxdc_webmaster_pro_plugin_repo_version_file');

        // Construct the GitHub URL
        $github_url = "https://raw.githubusercontent.com/$repo_owner/$repo_name/$branch_or_tag/$file_path";
        // Fetch the content from the GitHub URL
        $response = wp_remote_get($github_url);

        // Check for errors in the response
        if (is_wp_error($response)) {
            error_log('GitHub API request failed: ' . $response->get_error_message());
            return false;
        }

        // Retrieve and decode the response body
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        // Check if the version is set in the response data
        if (isset($data['version'])) {
            return $data['version'];
        }

        // Log an error if the version is not found
        error_log('Version not found in the GitHub response.');
        return false;
    }

    // Function to compare versions
    public function compare_versions()
    {
        $current_version = $this->plugin_version;
        $latest_version = $this->get_latest_version_from_github();

        if ($latest_version && version_compare($latest_version, $current_version, '>')) {
            return array(
                'has_update' => true,
                'latest_version' => $latest_version,
                'current_version' => $current_version
            );
        }

        return array(
            'has_update' => false,
            'current_version' => $current_version,
            'latest_version' => $latest_version // To ensure consistency
        );
    }

    // Callback function to display content for Updates & Licenses page
    public function cxdc_webmaster_pro_license_and_updates_page()
    {
        $update_info = $this->compare_versions();
        $current_version = isset($update_info['current_version']) ? $update_info['current_version'] : 'Unknown';
        $has_update = $update_info['has_update'];
        $latest_version = isset($update_info['latest_version']) ? $update_info['latest_version'] : 'Unknown';
?>
        <div class="webmasterpro-wrap">
            <div class="container">
                <div class="card">
                    <div class="card-header">
                        <h1>Webmasterpro Updates & Licenses</h1>
                        <p>Welcome to the Webmasterpro Updates & Licenses page! Keep your plugin secure and feature-rich by staying up-to-date with the latest version.</p>
                    </div>
                    <div class="webmasterpro-content">
                        <div class="webmasterpro-update-info child_card">
                            <h3>Update Plugin</h3>
                            <p>Welcome to the Webmasterpro Updates & Licenses page! Keep your plugin secure and feature-rich by staying up-to-date with the latest version.</p>
                            <p>Plugin Version: <?php echo esc_html($current_version); ?></p>
                            <?php if ($has_update) : ?>
                                <p class="webmasterpro-update-info-message" style="color: red; background-color: #f8d7da;">New Version Available: <?php echo esc_html($latest_version); ?></p>
                                <p>Click the button below to update the plugin to the latest version.</p>
                                <form method="post">
                                    <button type="submit" name="update_plugin" class="button button-primary">Update Plugin to Latest Version</button>
                                </form>
                            <?php else : ?>
                                <div class="webmasterpro-update-info-message">You are using the latest version of the plugin.</div>
                            <?php endif; ?>
                        </div>
                        <hr>
                        <div class="webmasterpro-license-info">
                            <h2>Licenses & Agreements</h2>
                            <h3>End User License Agreement (EULA)</h3>
                            <p>This End User License Agreement ("EULA") governs your use of the Webmasterpro plugin ("the Software"). By using the Software, you agree to the terms outlined below.</p>
                            <ol>
                                <li><strong>License Grant:</strong> You are granted a non-exclusive, non-transferable license to use the Webmasterpro plugin, provided you have a valid and active license. This allows you to install and use the plugin on your website(s) in accordance with the license type purchased.</li>
                                <li><strong>Ownership:</strong> The Webmasterpro plugin is the intellectual property of Webmasterpro. This EULA does not transfer any ownership rights. You are provided a license to use the plugin under the terms specified.</li>
                                <li><strong>Restrictions:</strong> You may not redistribute, sell, lease, or sublicense the Webmasterpro plugin without explicit permission from Webmasterpro. You are also prohibited from reverse engineering or attempting to derive the source code of the plugin, except where such activity is expressly permitted by applicable law.</li>
                                <li><strong>Third-Party Components:</strong> Webmasterpro may include third-party libraries and components licensed under the GPL. These components are used in accordance with their respective licenses and do not affect the proprietary nature of the Webmasterpro plugin itself.</li>
                                <li><strong>Support and Updates:</strong> With a valid license, you are entitled to receive support and updates for the duration of your license term. Webmasterpro reserves the right to limit or discontinue support for any reason, including end of life of the plugin.</li>
                                <li><strong>Warranty Disclaimer:</strong> The Webmasterpro plugin is provided "as is" without any warranties of any kind. Webmasterpro disclaims all warranties, whether express or implied, including but not limited to implied warranties of merchantability, fitness for a particular purpose, and non-infringement.</li>
                                <li><strong>Limitation of Liability:</strong> In no event shall Webmasterpro be liable for any damages arising from the use or inability to use the plugin, including but not limited to direct, indirect, incidental, special, or consequential damages, even if Webmasterpro has been advised of the possibility of such damages.</li>
                                <li><strong>Indemnification:</strong> You agree to indemnify and hold harmless Webmasterpro from any claims, damages, losses, liabilities, and expenses arising out of your use of the Webmasterpro plugin.</li>
                                <li><strong>Governing Law:</strong> This EULA shall be governed by and construed in accordance with the laws of India. Any disputes arising under this EULA shall be subject to the exclusive jurisdiction of the courts in India for Indian residents. For users outside India, this EULA shall be governed by the laws applicable in their respective jurisdictions, and any disputes shall be subject to the exclusive jurisdiction of the courts in their jurisdiction.</li>
                                <li><strong>Entire Agreement:</strong> This EULA constitutes the entire agreement between you and Webmasterpro regarding the use of the plugin and supersedes all prior agreements and understandings, whether written or oral, relating to the subject matter hereof.</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php
    }

    // Check if update button is clicked
    public function handle_update_request()
    {
        if (isset($_POST['update_plugin'])) {
            $update_result = $this->custom_update_functionality();

            if ($update_result === true) {
                add_action('admin_notices', array($this, 'update_success_notice'));
                error_log('Plugin updated successfully.');
            } else {
                add_action('admin_notices', array($this, 'update_failed_notice'));
                error_log('Plugin update failed.');
            }
        }
    }

    // Custom function to perform update functionality
    private function custom_update_functionality()
    {
        $repo_owner = get_option('cxdc_webmaster_pro_plugin_repo_owner');
        $repo_name = get_option('cxdc_webmaster_pro_plugin_repo_name');
        $branch_or_tag = get_option('cxdc_webmaster_pro_plugin_repo_tagname');
        $download_url = "https://github.com/{$repo_owner}/{$repo_name}/archive/refs/tags/{$branch_or_tag}.zip";
        $plugin_temp_zip = WP_PLUGIN_DIR . '/' . $this->plugin_slug . '-temp.zip';

        if (!is_writable(WP_PLUGIN_DIR)) {
            error_log('The plugin directory is not writable.');
            return false;
        }

        $response = wp_remote_get($download_url, array('timeout' => 30));

        if (is_wp_error($response)) {
            error_log('Failed to download the plugin ZIP file from GitHub. Error: ' . $response->get_error_message());
            return false;
        }

        $response_code = wp_remote_retrieve_response_code($response);

        if ($response_code !== 200) {
            error_log("Failed to download the plugin ZIP file from GitHub. HTTP Response Code: {$response_code}");
            return false;
        }

        $file_saved = file_put_contents($plugin_temp_zip, wp_remote_retrieve_body($response));

        if ($file_saved === false) {
            error_log('Failed to save the plugin ZIP file to the plugin directory.');
            return false;
        }

        if (!function_exists('WP_Filesystem')) {
            require_once ABSPATH . 'wp-admin/includes/file.php';
        }

        WP_Filesystem();

        global $wp_filesystem;

        if (!$wp_filesystem->exists($plugin_temp_zip)) {
            error_log('The downloaded ZIP file does not exist.');
            return false;
        }

        $unzip_result = unzip_file($plugin_temp_zip, WP_PLUGIN_DIR);

        if (is_wp_error($unzip_result)) {
            error_log('Failed to extract the plugin ZIP file. Error: ' . $unzip_result->get_error_message());
            unlink($plugin_temp_zip);
            return false;
        }

        if (!$wp_filesystem->delete($plugin_temp_zip)) {
            error_log('Failed to delete the temporary plugin ZIP file.');
        }

        if (!$wp_filesystem->exists(WP_PLUGIN_DIR . '/' . $this->plugin_slug)) {
            return false;
        }

        return true;
    }
    // Notice for successful plugin update
    public function update_success_notice()
    {
    ?>
        <div class="notice notice-success is-dismissible">
            <p>Plugin updated successfully!</p>
        </div>
    <?php
    }

    // Notice for failed plugin update
    public function update_failed_notice()
    {
    ?>
        <div class="notice notice-error is-dismissible">
            <p>Failed to update plugin. Please try again later.</p>
        </div>
<?php
    }
} // Assuming your class file is included properly
$webmasterpro_license_and_updates = new Webmasterpro_License_And_Updates(
    WEBMASTERPRO_PLUGIN_NAME,
    WEBMASTERPRO_VERSION,
    WEBMASTERPRO_PLUGIN_BASENAME,
    WEBMASTERPRO_PLUGIN_URL
);
