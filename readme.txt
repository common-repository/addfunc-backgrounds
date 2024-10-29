
=== AddFunc Backgrounds ===

Contributors: AddFunc,joerhoney
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=7AF7P3TFKQ2C2
Tags: backgrounds, layered background, multi-layer background, fixed background, parallax background, video background
Requires at least: 3.0.1
Tested up to: 5.1
Stable tag: 1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Create backgrounds as a content type. Select your backgrounds for any page. Many options and multiple layers available with only a few clicks.

== Description ==

AddFunc Backgrounds adds a Backgrounds content type your WordPress website. The Backgrounds editor makes it easy to create multi-layered backgrounds for your website, which you can then select for use on any Page or Post. Add an image or a video from your Media Library, add a color or gradient, control positioning and how/whether to repeat or not, do it again on another layer or few and even make it all a parallax!*

A Background consists of up to 9 optional layers, which are:
    *   Body — Represents and affects the `<body>` tag.
    *   Window — Represents the window or viewport, as well as the "above the fold" area.
    *   Wall — Represents the remainder of the page or the "below the fold" area.
    *   Video — A layer for adding a video to the background.
    *   Image — Basic layers, primarily for adding images (although they have other optional uses).
    *   Overlay — Overlays all other layers.

All layers support:
    *   Image (except for the Video layer)
    *   Gradient (overwritten by the image if set)
    *   Color
    *   Opacity (except for the Body layer)
    *   Fill (a.k.a. background-size — how the image fills the layer)
    *   Repeat (whether or not and which direction to repeat the image)
    *   Scrolling (a.k.a. background-attachment — whether image is to remain fixed to the screen or stick to the page when scrolling)
    *   X/Y Position (how the image is to align horizontally and vertically)
    *   Raw HTML

Layers that support the parallax feature are:
    *   Image layers
    *   Video layer

**The Body Layer/Settings)**

This is affectively the `<body>` tag. Features applied here will modify the `<body>` tag of your page/post. Please note the Parallax feature does modify the `<body>` with some CSS in order to make the parallax effect work, though you shouldn't be able to see these modifications (if you can, you may need to forgo the parallax feature in such a case, unless you know how to fix it using CSS).

**The Window Layer/Settings**

This layer adds an element covering the window/viewport area "above the fold" — the area you first see when the page loads. By default it matches the height of the window/viewport. This height can be adjust in cases where a fixed bar or menu runs across the top or bottom of your website, or for any other specialized case scenario. The Window layer never drops below this height setting (or the window/viewport height by default).

**The Wall Layer**

This layer adds an element wrapping the remainder of the page after the Window layer — "below the fold." The Wall respects the Window height, hence will begin wherever the Window ends.

**Image Layers**

These 4 are the most basic and include the most features. Each one can be easily converted to an `<object>`, making it more suitable for an SVG and other specialized content. When Parallax is on, Image layers instantly have their own depth, which can be adjusted with the Z Position field.

**The Video Layer**

The Video layer allows you to add a background video. Though this is a specialized layer, it does allow most of the same features as an Image layer, even parallax! The Alternate Source field allows you to upload an additional video format to support a wider range of browsers. As a background video should, this layer automatically plays, loops and mutes your video.

**The Overlay**

The Overlay covers the entire background from the top of the page to the bottom. It is best used for semitransparent backgrounds, so as to allow underlying layers to show through.

*Note: Due to the nature of a CSS parallax, some use cases may be limited or unworkable. Fortunately, the Parallax feature can be turned on or off with the flip of a switch. Also, the CSS parallax technique is currently not supported on iOS devices. The fallback for this of a static background is automatic.

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. Create Backgrounds as needed using the 'Add New' button/option.
4. From any page or post, use the Background metabox, so select any of the Backgrounds you've created.

== Frequently Asked Questions ==

= Why doesn't the gradient work when I have and image selected? Why does the image display and not the gradient? =

In CSS, a gradient is considered an image, so only one of these can be displayed on any given layer. If you wish to overlap an image and a gradient, try adding them to different layers.

= Why doesn't the Parallax setting work on the Body, Window, Wall or Overlay layers? =

These layers each have a special purpose and would be unworkable (even disastrous) as a CSS parallax layer.

The Body layer is the `<body>` of your web page. If this were used for a CSS parallax layer itself, it would break the entire page. It has to house all of the other layers of the parallax. The `<body>` element is the reason this CSS parallax scrolling effect works.

The purpose of the Window is to cover the top "above the fold" area of the page. The purpose of the Wall is to cover the remaining "below the fold" area of the page. The purpose of the Overlay is to cover the entire page. Therefore, these would not make sense as parallax layers. Further, if they used as parallax layers, they would not be able to serve their nomenclatural purpose.

Using the 4 Image layers and the Video layer provide a lot of dimension to the parallax. It is recommended to use only these for this effect, as adding too many layers can slow down page interaction response time and degrade the user experience.

= Why is the parallax effect all CSS and not Javascript? =

Much research and experimentation was done to determine the optimum method for a parallax effect. Though there are pros and cons to each, we chose the CSS method for it's performance purposes. The CSS method utilizes graphic card acceleration, which in most cases will provide the most ideal user experience.

= Will there ever be a JavaScript option/fallback for the parallax effect? =

This is in consideration.

= Why is my video muted? =

Videos are muted because the purpose of a background video is for it to display as a background. This can distract visitors from your content enough as it is, which is probably why smartphones will not autoplay your video unless it is muted.

== Screenshots ==

1. Build your Background with multiple layers.

2. Select your Background on any Post, Page or other public post type (custom or otherwise).

== Changelog ==

= 1 =
28 Nov 2018

*   Removes the metabox from post types which are not public.
*   Uploaded to the WordPress Plugin Directory.

= 0.10 =
26 Nov 2018

*   Adds support for shortcodes in the Raw HTML field of each layer.
*   Makes minor tweaks to improve admin UI.
*   Submitted to the WordPress Repository.

= 0.9 =
15 Nov 2018

*   Adds preset Gradient swatches.
*   Adds arrows to X/Y Position control box.

= 0.8 =
14 Nov 2018

*   Adds clear buttons (Xs) to admin editing page, to remove field values of:
    -   Color
    -   Gradient
    -   Image
    -   Opacity

= 0.7 =
13 Nov 2018

*   Adds visual queues to admin Background editing page:
    -   Color displays in layer pane.
    -   Gradient displays in layer pane.
    -   Image displays in layer pane (overwrites gradient).
    -   Layer pane background image reflects Fill setting.
    -   Layer pane background image reflects Repeat setting.
    -   Layer pane background image reflects Background Scrolling setting.
    -   Layer pane background image reflects X/Y Position setting.

= 0.6 =
1 Nov 2018

*   Adds a color picker.

= 0.5.7 =
30 Oct 2018

*   Minor tweaks and fixes.
*   Adds wrappers to objects and videos to be utilized for better CSS control.

= 0.5.6 =
25 Oct 2018

*   Adds Opacity setting to each layer (except the `body`).
*   Corrects CSS dependencies:
    -   Match Height of Page: overwrites _Parallax_ and _On Scroll, Stick to Screen_ (per layer).
    -   Parallax: ignores _On Scroll, Stick to Screen_ (affects entire page).
    -   On Scroll, Stick to Screen: therefore requires both _Match Height of Page_ and _Parallax_ to be off.
    -   This is because CSS doesn't support otherwise (or not reliably). We don't want to allow the options that CSS won't support.
*   Adjusts admin UI to reflect the above CSS dependencies and handle some unclarities.
*   Fixes several issues found, mostly conflicting settings and CSS.

= 0.5.5 =
18 Oct 2018

*   Adds name (slug) of Background to `body` element class list.
*   Adds custom class field for the `body` element to add more classes.
*   Separates two blocks of code into their own files for better organization.
    -   All admin CSS is now in: /css/admin.css
    -   All Metabox code is now in: /metaboxes.php

= 0.5.4 =
26 Sep 2018

*   Adds parallax support detection to disable parallax on iOS and non-parallax-supporting devices:
    -   iOS looses inertial scrolling with all of these CSS properties at play for the parallax effect.
    -   Lose of inertial scrolling is a deal-killer, so its best to disable parallax for iOS instead.
    -   Support for "-webkit-overflow-scrolling: touch" is used to identify iOS.
    -   Additionally, support for "perspective: 1px" is checked, to disable parallax where that is not supported.
*   Improves Background edit page:
    -   Hides the Z Position field if Parallax is turned off (so its hidden by default).

= 0.5.3 =
24 Sep 2018

*   Adds Parallax to General Settings
    -   Parallax requires a lot of CSS, which affects a lot of things, so it really needs to be all or nothing.
*   Adds 'translateZ' defaults to Video layer and Image Layers 1-4, so parallax can be flipped on easily.

= 0.5.2 =
? Sep 2018

*   Adds Media Library to new Backgrounds (otherwise, they had to be saved once in order for the Media Library to load).

= 0.5.1 =
17 Sep 2018

*   Applies fix made in 0.3.1.

= 0.5 =
13 Sep 2018

*   Removes Alt Attribute field. Object elements don't use the alt attribute, so this option doesn't make sense.
*   Fixes Media Library pop-up, which wasn't working on new Backgrounds.
*   Adds object option to layers which were missing it.

= 0.4 =
10 Sep 2018

*   Removes content area.
*   Improves expanding boxes with a more WordPress-native look and feel.
*   Adds Raw HTML option to each layer. =O

= 0.3.1 =
17 Sep 2018

*   Fixes check for $post->ID (WordPress was throwing an error on 404 pages and possibly other non-post pages).

= 0.3 =
06 Sep 2018

*   Got a whole bunch of $#!+ work'n. Pretty much everything except for the Content field.
*   Fixes a ton of PHP errors, mostly due to undefined variables.
    -   Adds array to keep variables organized and make it easier to declare all needed variables.
*   Adds the standard set of controls to all layers, except where not applicable:
    -   'aFBg___On' = Show/hide controls
    -   'aFBg___Im' = Image URL (for the video layer it's $aFBgVideo)
    -   'aFBg___Gr' = Gradient rule
    -   'aFBg___Co' = Background color
    -   'aFBg___Fx' = Fixed (background scrolling)
    -   'aFBg___Fl' = Fill (cover, contain, etc.)
    -   'aFBg___Rp' = Repeat
    -   'aFBg___XY' = Horizontal and vertical positioning
    -   'aFBg___Ht' = Whether to conform to height of window or page
    -   'aFBg___Sn' = Whether to stick to page or screen on scroll
    -   'aFBg____Z' = Distance (for scrolling parallax effect)
    -   'aFBg___Ob' = Whether or not to output layer as an <object>
    -   'aFBg___Al' = Alt attribute text (alternative video source for video layer)

= 0.2 =
-- Aug 2018

*   More comprehensive Background Edit page using CSS, visual queues and better layout.
*   Removes certain obsolete options, as better generated automatically.
*   Adds more user-friendly options.
*   Random list of some of the changes in this version:
    -   Adds control for positioning element/background along X and Y axis.
    -   Adds toggle switches for contrast and better visual comprehension.
    -   Adds more expanding/contracting areas, to hide unused features.
    -   Adds "Match Height of:" option to certain layers.
    -   Adds "On Scroll, Stick to:" option to certain layers.
    -   Generalizes gradient field, so all gradient types are possible.
    -   Adds Z axis field (parallax) to Video layer.
    -   Adds Overlay layer output.

= 0.1 =
18 Apr 2018

*   Cleaner Background Edit page metabox using some CSS.
*   Adds Window Wall

= 0.0.3 =
17 Apr 2018

*   Cleaner code, as well as Background Edit page metabox layout.
*   Got some gradients working for the most part.

= 0.0.2 =
16 Apr 2018

*   Cleaner code, making it more concise.
*   Lots more features.

= 0.0.1 =
29 Mar 2018

*   Adds more awesome.
*   At the point of making the options do stuff.
*   Last thing: got video type pulled from video file name for <video> tag `type` attribute.

= 0 =
28 Mar 2018

*   Adds all kinds of awesome.
*   Got to the point of adding "Background Type" options to the metabox on the Background Edit page.
