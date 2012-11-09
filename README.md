kirby-imgrd
===========

A server based solution to create a gridview for images in Kirby. This plugin will create a Google+ style image grid from any images collection. It will create thumbnails so that portrait and landscape oriented photos will all be shown in their original proportions, but every image in a row will have the same height and each row will have the same overall width.

## Requirements
You need to have the [Thumb plugin](https://github.com/bastianallgeier/kirbycms-extensions/tree/master/plugins/thumb) installed in your Kirby system.

## Installation and Usage

**THIS HAS CHANGED!**

Just copy the `imgrd.php` into your `site/plugins/` folder. To use the plugin, you have to call the `imgrd` function in your template. It will return an imgrd object. The options are now given as an array, see the default array below.

```php
$default = array(
    'width' => 450,
    'margin' => 10,
    'imagesPerRow' => 3,
    'cropLast' => false,
    'rowsPerPage' => 0
  );

imgrd($page->images(), $options)
```

Note that `imagesPerRow` is not an exact number but rather an average number of images per row. So if the value is set to three, there will fit in two landscape, or two portrait and one landscape or four portrait oriented images. `cropLast` is optional. Setting it to *true* will force the last image to fit in to the row so that the row has the full width even if there aren't enough images left to complete it.

**NEW** There's now a possibility to create pages of your gallery. This could be useful if you don't want to load all images at once. It can be used for infinite scrolling with the jQuery `load()`function for example. You could of course use the inbuilt Kirby pagination, but this will lead to less satisfying results as you might have only one image in your last row. The imgrd pagination makes sure that every row is exactly filled. Setting `rowsPerPage` to zero will display all images.

An example template for the standard Kirby theme could be:

```php
<?php snippet('header') ?>
<?php snippet('menu') ?>
<?php snippet('submenu') ?>

<section class="content">

  <article>
    <h1><?php echo html($page->title()) ?></h1>
    <div id="grid" class="cf">
    <?php
      $grid = imgrd($page->images(), array('rowsPerPage' => 3));
      $grid->show();
    ?>
  	</div>
  </article>

</section>

<?php snippet('footer') ?>
```

### Methods

**hasNext()** returns true if there's another page.  
**hasPrevious()** returns true if there's a previous page.

**getNextURL()** returns the URL to the next page if there's one.  
**getPreviousURL()** returns the URL to the next page if there's one.

**countPages** returns the number of pages.

**show()** prints the grid. This method call is **compulsory**.

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
