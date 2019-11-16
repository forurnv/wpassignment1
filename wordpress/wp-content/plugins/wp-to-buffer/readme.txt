=== Post to Social Media - WordPress to Buffer ===
Contributors: n7studios,wpzinc
Donate link: https://www.wpzinc.com/plugins/wordpress-to-buffer-pro
Tags: auto publish, auto post, social media automation, social media scheduling, buffer, bufferapp, buffer app, buffer my post, buffer old post, buffer post, post to buffer, promote old posts, promote posts, promote custom posts, promote selected posts, share posts, bulk share posts, share old posts, social, media, sharing, social media, social sharing, schedule, auto post, auto publish, publish, facebook, facebook post, facebook selected posts, facebook plugin, auto facebook post, post facebook, post to facebook, twitter, twitter post, tweet post twitter selected posts, tweet selected posts twitter plugin, auto twitter post, auto tweet post post twitter, post to twitter, linkedin, linkedin post, linkedin selected posts, linkedin plugin, auto linkedin post, post linkedin, post to linkedin, google, google post, google selected posts, google plugin, auto google post, post google, post to google, pinterest, pinterest post, pinterest selected posts, pinterest plugin, auto pinterest post, post pinterest, post to pinterest, best wordpress social plugin, best wordpress social sharing plugin, best social plugin, best social sharing plugin, best facebook social plugin, best twitter social plugin, best linkedin social plugin, best pinterest social plugin, best google+ social plugin, instagram, pinterest
Requires at least: 3.6
Tested up to: 5.2.3
Requires PHP: 5.6
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Automatically share WordPress Pages, Posts or Custom Post Types to Facebook, Twitter and LinkedIn using your Buffer (buffer.com) account.

== Description ==

WordPress to Buffer is a plugin for WordPress that auto posts your Posts, Pages and/or Custom Post Types to your Buffer (buffer.com) account for scheduled publishing to Facebook, Twitter and LinkedIn.

Don't have a Buffer account?  [Sign up for free](https://buffer.com)

Our [API](https://www.wpzinc.com/documentation/wordpress-buffer-pro/data/) connects your website to [Buffer](https://buffer.com). An account with Buffer is required.

> #### WordPress to Buffer Pro
> <a href="https://www.wpzinc.com/plugins/wordpress-to-buffer-pro/" rel="friend" title="WordPress to Buffer Pro - Publish to Facebook, Twitter, LinkedIn, Instagram and Pinterest">WordPress to Buffer Pro</a> provides additional functionality:<br />
>
> - **Instagram and Pinterest Support**<br />Post to Instagram (Reminders for Personal Profiles, Direct Posting to Business Profiles) and Pinterest Boards<br />
> - **Multiple, Customisable Status Messages**<br />Each Post Type and Social Network can have multiple, unique status message and settings<br />
> - **Separate Options per Social Network**<br />Define different statuses for each Post Type and Social Network<br />
> - **Dynamic Status Tags**<br />Dynamically build status updates with data from the Post, Author, Custom Fields, The Events Calendar, WooCommerce, Yoast and All-In-One SEO Pack<br />
> - **Shortcode Support**<br />Use shortcodes in status updates<br />
> - **Schedule Statuses**<br />Each status update can be added to the start/end of your Buffer queue, posted immediately or scheduled at a specific time<br />
> - **Full Image Control**<br />Choose to display WordPress Featured Images with your status updates<br />
> - **Conditional Publishing**<br />Only send status(es) to Buffer based on Post Author(s), Taxonomy Term(s) and/or Custom Field Values<br />
> - **Override Settings on Individual Posts**<br />Each Post can have its own Buffer settings<br />
> - **Repost Old Posts**<br />Automatically Revive Old Posts that haven't been updated in a while, choosing the number of days, weeks or years to re-share content on social media.<br />
> - **Bulk Publish Old Posts**<br />Publish evergreen WordPress content and revive old posts with the Bulk Publish option<br />
> - **The Events Calendar Plugin Support**<br />Schedule Posts to Buffer based on your Event's Start or End date, as well as display Event-specific details in your status updates<br />
> - **WooCommerce Support**<br />Display Product-specific details in your status updates<br />
> - **Per-Post Settings**<br />Override Settings on Individual Posts: Each Post can have its own Buffer settings<br />
> - **Full Image Control**<br />Choose to display the WordPress Featured Image with your status updates, or define up to 4 custom images for each Post.<br />
> - **WP-Cron and WP-CLI Compatible**<br />Optionally enable WP-Cron to send status updates via Cron, speeding up UI performance and/or choose to use WP-CLI for reposting old posts<br />
> - **Support**<br />Access to one on one email support<br />
> - **Documentation**<br />Detailed documentation on how to install and configure the plugin<br />
> - **Updates**<br />Receive one click update notifications, right within your WordPress Adminstration panel<br />
> - **Seamless Upgrade**<br />Retain all current settings when upgrading to Pro<br />
>
> [Upgrade to WordPress to Buffer Pro](https://www.wpzinc.com/plugins/wordpress-to-buffer-pro/)

[youtube https://www.youtube.com/watch?v=PgXYH-f95Ow]

= Support =

We will do our best to provide support through the WordPress forums. However, please understand that this is a free plugin, 
so support will be limited. Please read this article on <a href="http://www.wpbeginner.com/beginners-guide/how-to-properly-ask-for-wordpress-support-and-get-it/">how to properly ask for WordPress support and get it</a>.

If you require one to one email support, please consider <a href="http://www.wpzinc.com/plugins/wordpress-to-buffer-pro" rel="friend">upgrading to the Pro version</a>.

= Data =

We connect directly to your Buffer (buffer.com) account, via their API, to:
- Fetch your social media profile names and IDs, 
- Send your WordPress Posts to one or more of your social media profiles.  The profiles and content sent will depend on the plugin settings you have configured.

We connect to our own [API](https://www.wpzinc.com/documentation/wordpress-to-buffer-pro/data/) to pass the following requests through to Buffer:
- Connect our Plugin to Buffer, when you click the Authorize button (this obtains an access token from Buffer, once you have approved authorization)

Both of these are done via our own API, to ensure that no secret data (such as oAuth client secret keys) are included in this Plugin's code or made public.

We **never** store any information on our web site or API during this process.

= WP Zinc =
We produce free and premium WordPress Plugins that supercharge your site, by increasing user engagement, boost site visitor numbers
and keep your WordPress web sites secure.

Find out more about us at <a href="https://www.wpzinc.com" rel="friend" title="Premium WordPress Plugins">wpzinc.com</a>

== Installation ==

1. Upload the `wp-to-buffer` folder to the `/wp-content/plugins/` directory
2. Active the WordPress to Buffer plugin through the 'Plugins' menu in WordPress
3. Configure the plugin by going to the `WordPress to Buffer` menu that appears in your admin menu

== Frequently Asked Questions ==



== Screenshots ==

1. Settings Screen when Plugin is first installed.
2. Settings Screen when Buffer is authorized.
3. Settings Screen showing available options for Posts.
4. Post-level Logging.

== Changelog == 

= 3.5.4 =
* Added: Status: Tags: Content and Excerpt Tag options with Word or Character Limits
* Added: Gutenberg: Better detection to check if Gutenberg is enabled
* Added: Gutenberg: Better detection to check if Post Content contains Gutenberg Block Markup
* Fix: Status: Removed loading of unused tags.js dependency for performance
* Fix: Status: Buffer API Error: HTTP Code 400. #1011 - You do not have permission to post to any of the profile_id's provided, which would occur when a profile has been disconnected from Buffer
* Fix: Status: {content} would return blank on WordPress 5.1.x or older

= 3.5.3 =
* Added: Status: Textarea will automatically expand based on the length of the status text. Fixes issues for some iOS devices where textarea scrolling would not work
* Fix: Status: {content} and {excerpt} tags always return the full content / excerpt, which can then be limited using word / character limits
* Fix: Publish: Add checks to prevent duplicate statuses being sent when a Page Builder (Elementor) fires wp_update_post multiple times when publishing
* Fix: Status: Strip additional unwanted newlines produced by Gutenberg when using {content}
* Fix: Status: Convert <br> and <br /> in Post Content to newlines when using {content}
* Fix: Status: Trim Post Content when using {content}

= 3.5.2 =
* Added: Settings: Display notice if the Buffer account does not have any social media profiles attached to it
* Fix: Publish: Display errors and log if authentication fails, or profiles cannot be fetched

= 3.5.1 =
* Fix: Settings: Status: Display warning if a timezone in WordPress or Buffer is not a valid timezone, instead of throwing a fatal error

= 3.5.0 =
* Fix: Network Activation: Fatal error

= 3.4.9 =
* Added: Status: Secondary level tabbed UI for Profile actions (Publish, Update)
* Added: Settings: Post Type: Profile: Display warning with instructions when the WordPress Timezone and Buffer Profile Timezone do not match
* Added: Settings: Warning if the max_input_vars PHP setting might be too low for the Plugin's settings to successfully be saved
* Fix: Status: Documentation Tab Link

= 3.4.8 =
* Added: New Installations: Automatically enable Publish and Update Statuses on Posts
* Added: Plugin Activation: Enable Logging by default
* Added: Authorize with Buffer: Once authorized, automatically enable scheduling to social media profiles marked as "share by default" on Buffer
* Added: Status: Option to limit the number of characters output on a Template Tag
* Fix: Log: Output dates according to WordPress' installation date locale formatting
* Fix: Log: Split data into more table columns for easier reading
* Fix: Status: Don't attempt publishing to any existing linked Google+ Accounts, as Google+ no longer exists.
* Fix: Publish: Improved performance when sending several statuses for a single Post.
* Fix: Publish: Display errors on Post Edit screen if status(es) failed to send to Buffer

= 3.4.7 =
* Fix: Menu Icon size preserved when Gravity Forms no conflict mode is set to on
* Fix: Display White Menu Icon unless the User is using WordPress' Light Admin Color Scheme, in which case display the Dark Menu Icon

= 3.4.6 =
* Added: Profiles: Fetch Twitter Usernames from Twitter API instead of Buffer API (which no longer provides this information), as required by Buffer and Twitter's Development Policies effective Feb. 19th 2019.
* Fix: Publish: Removed global $post reference, which caused some installations to fetch the wrong Post to send to Buffer

= 3.4.5 =
* Added: Status: Featured Image: Option to choose between using OpenGraph image (clicking image links to URL) and using image, not linked to URL.  See Docs: https://www.wpzinc.com/documentation/wordpress-buffer-pro/featured-image-settings/
* Fix: Compatibility when using multiple WP Zinc Plugins
* Fix: Minified all CSS and JS for performance

= 3.4.4 =
* Fix: Multisite: Network Activation: Ensure activation routines automatically run on all existing sites
* Fix: Multisite: Network Activation: Ensure activation routines automatically run created on new sites created after Network Activation of Plugin
* Fix: Multisite: Site Activation: Ensure activation routines automatically run

= 3.4.3 =
* Added: Settings: Header UI enhancements
* Fix: PHP warning on count() when trying to fetch an excerpt for a Post
* Fix: Settings: Only load settings for the displayed screen, for better performance
* Fix: Settings: Save settings more efficiently, for better performance

= 3.4.2 =
* Fix: Settings: Changed Authentication Tab Icon
* Fix: Settings and Status Settings: UI Enhancements for mobile compatibility
* Fix: {title} would sometimes result in HTML encoded characters on Facebook

= 3.4.1 =
* Fix: Status: Apply WordPress default filters to Post Title, Excerpt and Content. Ensures third party Plugins e.g. qtranslate can process content and remove shortcodes

= 3.4.0 =
* Added: Gutenberg: Support for Custom Field Tags when Custom Fields / Meta are registered as a meta box outside of the Gutenberg editor.
* Added: REST API: Support for Custom Field Tags when Posts are created or updated via the REST API with Custom Field / Meta data.

= 3.3.9 =
* Added: Gutenberg Support
* Added: Settings and Status Settings: UI Enhancements to allow for a larger number of connected social media profiles
* Added: Status: Tag: Post ID option
* Fix: Removed unused datepicker dependency
* Fix: CRON Scheduled Posts: Don't rely on wp_get_current_user() for User Access settings, as it's not always available
* Added: Status: Support for Shortcode processing on Status Text

= 3.3.8 =
* Fix: Publish: Ensure Post has fully saved (including all Custom Fields / ACF / Yoast data etc) before sending status to Buffer
* Fix: Publish: Removed duplicate do_action() call on save_post to prevent some third party plugins running routines twice
* Fix: Log: Report 'Plugin: Request Sent' and 'Created At' datetime using WordPress configured date time zone.
* Fix: Profiles: Serve social media profile images over SSL to avoid mixed content warning messages
* Fix: Settings: Changed WordPress standard .nav-tab-active class to .wpzinc-nav-tab-active, to prevent third party plugins greedily trying to control our UI.

= 3.3.7 =
* Fix: Publish: Only consider publishing statuses to Buffer on supported Post Types (resolves issues with Advanced Custom Fields Free Version saving Fields).

= 3.3.6 =
* Fix: Call to member function get_error_message() on null when attempting to fetch Buffer User Profile.

= 3.3.5 =
* Added: Don't initialize plugin if the Pro version is installed; prevents 500 internal server errors when users wrongly attempt to run both Free + Pro versions at the same time

= 3.3.4 =
* Added: Changed select2 to selectize to improve performance
* Fix: Code refactor to improve performance
* Fix: Removed jQuery Tooltipster, as it's not used
* Fix: Moved Log Meta Box into own view file
* Fix: Log: Clear Log functionality
* Fix: Log: Sanitize Post ID for exporting and clearing Post logs
* Fix: Log: Store Profile Name in Log, so an undefined offset error isn't thrown when showing a Log for a Profile that was previously enabled, but is now disabled

= 3.3.3 =
* Added: Filter for defining max timeout on Buffer API requests (default: 10 seconds)
* Added: Re-authorize option when Plugin's access is revoked by a user via their buffer.com account
* Fix: Some cURL timeouts, despite statuses going through to Buffer
* Fix: Menu Icon size preserved when Gravity Forms no conflict mode is set to on
* Fix: Use 'thumbnail' WordPress image size for Buffer thumbnail, instead of 'small'

= 3.3.2 =
* Added: Settings: Notice if Plugin is not authorized with Buffer
* Added: Settings: UI indicator for each Post Type denoting if enabled
* Added: Install: Enable on Post Publish by default for new installations
* Fix: Settings: DatePicker UI conflict with Advanced Custom Fields

= 3.3.1 =
* Added: Improved UI
* Fix: Define CURLOPT_RESOLVE on fallback PHP cURL requests, when wp_remote_get() / wp_remote_post() fails, to prevent DNS name lookup errors.
* Fix: Uncaught TypeError: Illegal constructor in admin-min.js for clipboard.js functionality

= 3.3.0 =
* Fix: Clarified Pinterest + Instagram support; added tested with WordPress 4.8.1 flag

= 3.2.9 =
* Fix: Set priority of 1 on wp_loaded for register_publish_hooks, to ensure Publish Hooks are fired on imports and some third party Plugins

= 3.2.8 =
* Added: Simplified authentication process with Buffer. No need to enter Access Tokens or Register Apps any more!
* Fix: Publish / Update: Fallback PHP cURL requests when wp_remote_get() / wp_remote_post() fail and WP_DEBUG enabled. May resolve 'undefined' errors on buffer.com and occasional timeouts.
* Fix: Posts: Log: Include Buffer API error code in output for easier debugging

= 3.2.7 =
* Fix: Always attach Featured Image to status if available (per 3.0.5 and below)

= 3.2.6 =
* Fix: Undefined variable errors

= 3.2.5 =
* Fix: Only display Review Helper for Super Admin and Admin

= 3.2.4 =
* Added: Review Helper to check if the user needs help
* Updated: Dashboard Submodule

= 3.2.3 =
* Fix: Removed "Shorten Twitter status to 140 characters" in 3.2.1; too many bugs. Users will need to revert back to ensuring their Twitter statuses are short to avoid Buffer API errors.

= 3.2.2 =
* Added: User-Agent to wp_remote_get and wp_remote_post on Buffer API calls, to potentially resolve timeout connection issues for one edge case.
* Fix: Conditionally load sortable and datepicker listeners to avoid JS errors

= 3.2.1 =
* Added: Version bump to match Pro version, using same core codebase and UI for basic features. Fixes several oustanding bugs.
* Added: Tooltips to Profile Tabs, to show the profile service and name
* Added: Contextual Documentation links in Tabs
* Fix: Shorten Twitter status to 140 characters, excluding first URL, to prevent 400 errors from Buffer when a Twitter status message is too long.
* Fix: Removed unused image library code

= 3.0.5 =
* Fix: Changed branding from WP Cube to WP Zinc

= 3.0.4 =
* Fix: Removed var_dump()

= 3.0.3 =
* Fix: Changed from publish_post to transition_post_status for better detection of Post/Page Publish/Update
* Fix: Removed sslverify = false on wp_remote_* requests

= 3.0.2 =
* Fix: Dashboard Feed URL

= 3.0.1 =
* Fix: Shorten links

= 3.0 =
* Fix: WordPress 4.2 compatibility
* Fix: Better security on form submissions

= 2.3.6 =
* Fix: &hellip; HTML character code appearing on Facebook + Google+ status updates when no excerpt defined on a Post

= 2.3.5 =
* Fix: Removed logging

= 2.3.4 =
* Fix: Double posts in Buffer when a scheduled Post goes live.

= 2.3.3 =
* Dropped html_entity_decode and apply_filters on Post Title - causing too many issues.

= 2.3.2 =
* Fix: Settings tabs not working / all settings panels displaying at once
* Added translation support and .pot file 

= 2.3.1 =
* Fix: Issue with characters in the title being HTML encoded

= 2.3 =
* Fix: Uses get_the_title() when generating status updates for social networks
* Fix: Check that at least one social media profile has been chosen before trying to update via the API

= 2.2.1 =
* Fix: Prevent double posting when Posts with category filtering are enabled, and a Post is added via third party apps using the XML RPC API
* Fix: Pages can be posted to Buffer via XML RPC API

= 2.2 =
* Fix: Twitter Images attached to tweets
* Fix: Featured Images on Facebook

= 2.1.8 =
* Fix: Stops URLs and images being stripped from some updates to LinkedIn

= 2.1.7 =
* Fix: Removed unused addPublishActions function

= 2.1.6 =
* Fix: Dashboard widget
* Fix: Some Posts not adding to Buffer due to meta key check

= 2.1.5 =
* Fix: Don't show success message when Post/Page not posted to Buffer
* Fix: Removed Post to Buffer meta box, which wasn't honouring settings / causing double postings
* Settings: changed to tabbed interface

= 2.1.4 =
* Fix: Dashboard: PHP fatal error

= 2.1.3 =
* Fix: Posts with an image no longer show the image link, but instead show the Page / Post URL

= 2.1.2 =
* Fix: Donation Form

= 2.1.1 =
* Fix: Some assets missing from SVN checkin on 2.1

= 2.1 =
* Fix: 'Creating default object from empty value' warning
* Fix: {excerpt} tag working on Pages and Custom Post Types that do not have an Excerpt field
* Fix: Capabilities for add_menu_page
* Fix: Check for page $_GET variable

= 2.0.1 =
* Fix: Removed console.log messages
* Fix: Added Google+ icon for Buffer accounts linked to Google+ Pages

= 2.0 =
* Fix: admin_enqueue_scripts used to prevent 3.6+ JS errors
* Fix: Force older versions of WP to Buffer to upgrade to 2.x branch.
* Fix: Check for Buffer accounts before outputting settings (avoids invalid argument errors).
* Enhancement: Validation of access token to prevent several errors.
* Enhancement: Add callback URL value (not required, but avoids user confusion).
* Enhancement: Check the access token pasted into the settings field is potentially valid (avoids questions asking why the plugin doesn't work,
because the user hasn't carefully checked the access token).

= 1.1 =
* Enhancement: Removed spaces from categories in hashtags (thanks, Douglas!)
* Fix: "Error creating default object from empty value" message.
* Enhancement: Added Featured Image when posting to Buffer, if available.
* Fix: Simplified authentication process using Access Token. Fixes many common oAuth issues.

= 1.03 =
* Fix: Publish hooks now based on settings instead of registered post types, to ensure they hook early enough to work on custom post types.

= 1.02 =
* Fix: Scheduled Posts now post to Buffer on scheduled publication.

= 1.01 =
* SSL verification fix for Buffer API authentication.

= 1.0 =
* First release.

== Upgrade Notice ==

