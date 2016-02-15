=== Simple API ===
Contributors: ncxncx11
Donate link: http://example.com/
Tags: API, JSON
Requires at least: 4.0
Tested up to: 4.0
Stable tag: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A simple API plugin based on Wordpress.

== Description ==

For now, this plugin provides four kinds of API:

- `?sa=get_posts`: Get post list, available queries( refer to [wp_query](https://developer.wordpress.org/reference/classes/wp_query/) to check the meanings for every query ):
    - `sa_author`,
	- `sa_author_name`,
	- `sa_author__id`,
	- `sa_author__not_in`,
	- `sa_cat`,
	- `sa_category_name`,
	- `sa_category__and`,
	- `sa_category__in`,
	- `sa_category__not_in`,
	- `sa_tag`,
	- `sa_tag_id`,
	- `sa_tag__and`,
	- `sa_tag__in`,
	- `sa_tag__not_in`,
	- `sa_tag_slug__and`,
	- `sa_tag_slug__in`,
	- `sa_s`,
	- `sa_p`,
	- `sa_name`,
	- `sa_page_id`,
	- `sa_pagename`,
	- `sa_post_parent`,
	- `sa_post_parent__in`,
	- `sa_post_parent__not_in`,
	- `sa_post__in`,
	- `sa_post__not_in`,
	- `sa_post_name__in`,
	- `sa_post_type`,
	- `sa_post_status`,
	- `sa_nopaging`,
	- `sa_posts_per_page`,
	- `sa_posts_per_archive_page`,
	- `sa_offset`,
	- `sa_paged`,
	- `sa_page`,
	- `sa_ignore_sticky_posts`,
	- `sa_order`,
	- `sa_orderby`,
	- `sa_year`,
	- `sa_monthnum`,
	- `sa_w`,
	- `sa_day`,
	- `sa_hour`,
	- `sa_minute`,
	- `sa_second`,
	- `sa_m`,
	- `sa_meta_key`,
	- `sa_meta_value`,
	- `sa_meta_value_num`,
	- `sa_meta_compare`
- `?sa=get_post&sa_post_id={id}`: Get a single post data by post id.
- `?sa=get_page&sa_page_id={id}`: Get a single page data by page id.
- `?sa=get_comments&sa_post_id={id}`: Get comment list from a specified post.
- `?sa=insert_comment&comment_author&comment_author_email&comment_author_url&comment_content&comment_post_ID`: insert a new comment.

== Pagination ==

When calling `get_posts` API, you can get pagination info from the response header:

- `X-Total-Count`: The total number of posts found matching the current query parameters.
- `X-Total-Page`: The total number of pages.
- `X-Page-Size`: Number of post to show per page.

== Changelog ==

= 1.0 =
The initial version.
