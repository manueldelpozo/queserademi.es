# Video Destacado #

Contributors: airtonvancin
Tags: video,videos,featured,post,page,post type,youtube,add
Donate link: https://pagseguro.uol.com.br/checkout/v2/donation.html?currency=BRL&receiverEmail=chapolinsk@hotmail.com
Requires at least: 3.0
Tested up to: 4.4.1
Stable tag: 1.3.0

Insert a video posted to Youtube for posts, pages and custom post types

## Description ##
Insert a video posted to Youtube for posts, pages and custom post types

## Installation ##
1. Unzip the file to the plugins folder of the '/wp-content/plugins/' directory inside of WordPress
2. Keep the directory structure of the file, all extracted files should exist in '/wp-content/plugins/video-destacado/'

### Enter the following code inside the loop ###
<code><?php video_destacado(); ?></code>
or
<code><?php echo get_video_destacado(); ?></code>

### Ex: ###
<code>
<?php

// The Query
query_posts( $args );

// The Loop
while ( have_posts() ) : the_post();

    video_destacado();

endwhile;

// Reset Query
wp_reset_query();

or

// The Query
query_posts( $args );

// The Loop
while ( have_posts() ) : the_post();

    echo get_video_destacado();

endwhile;

// Reset Query
wp_reset_query();

?>
</code>



## Frequently Asked Questions ##

### How to display the video in the post? ###
Insert the following code inside the loop
<code><?php video_destacado(); ?></code>
or
<code><?php echo get_video_destacado(); ?></code>

### You can suggest modifications and ideas for this plugin? ###

Sure you can, go to this link [Featured Video] (https://github.com/airton/)


## Screenshots ##
1. As will be shown in the video featured admin
2. Settings page

## Changelog ##

### 1.4 ###
Added translation
- pt_BR
- es_ES

### 1.3 ###
Add settings to best practices in the plugin

### 1.2 ###

### 1.1 ###
Add function get_video_destacado();

### 1.0 ###
Add page settings
Settings > Vídeo Destacado

### 0.2 ###
Add width and height of the player

### 0.1 ###
Highlighted in the video display post, page and custom post types.


## Upgrade Notice ##
### 1.3 ###
Add settings to best practices in the plugin

### 1.1 ###
Add function get_video_destacado();

### 1.0 ###
Add page settings
Settings > Vídeo Destacado

### 0.2 ###
Add width and height of the player

### 0.1 ###
Highlighted in the video display post, page and custom post types.
