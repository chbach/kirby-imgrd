kirby-imgrd
===========

A server based solution to create a gridview for images in Kirby. This plugin will create a Google+ style image grid from any images collection. It will create thumbnails so that portrait and landscape oriented photos will all be shown in their original proportions, but every image in a row will have the same height and each row will have the same overall width.

## Requirements
You need to have the [Thumb plugin](https://github.com/bastianallgeier/kirbycms-extensions/tree/master/plugins/thumb) installed in your Kirby system.

## Installation and Usage
Just copy the `imgrd.php` into your `site/plugins/` folder. To use the plugin, you have to call the `imgrd` function in your template. The parameters are as follows:

```php
imgrd($images, $gridWidth, $margin, $images_per_row)
```

Note that `$images_per_row` is not an exact number but rather an average number of images per row. So if the value is set to three, there will fit in two landscape, or two portrait and one landscape or four portrait oriented images.

An example template for the standard Kirby theme could be:

```php
<?php snippet('header') ?>
<?php snippet('menu') ?>
<?php snippet('submenu') ?>

<section class="content">

  <article>
    <h1><?php echo html($page->title()) ?></h1>
    <div id="grid" class="cf">
    <?php imgrd($page->images(), 450, 10, 3); ?>
  	</div>
  </article>

</section>
```

### Stylesheets for the Grid
To get a nice grid, I would recommend to set the width of `#grid` to the desired width **plus** margin and set a negative margin-left, just like this:

```CSS
#grid {
  margin-left: -10px;
  width: 460px;
}

#grid img {
  float:left;
  margin: 0 0 10px 10px;
}
```

Naturally, you have to clear the content, e.g. with clearfix:

```CSS
.cf:before,
.cf:after {
    content: " ";
    display: table;
}

.cf:after {
    clear: both;
}
```

## Output
The plugin will create links to the original with the class `fancybox` and the relation `gallery`. As a result, you can use the jQuery [FancyBox](http://fancyapps.com/fancybox/) to create a nice gallery.

## License and Contact
You can use the code and alter it to your needs as you like. Feel free to fork the repo and send pull requests. Don't claim it as your own, please!

Follow me [@chrstphbach](https://twitter.com/chrstphbach) for updates. I would love to see some projects that make use of my plugin!
