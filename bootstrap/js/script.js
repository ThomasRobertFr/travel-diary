// show image and enable fancybox onclick of preview
function show_image(image_el) {

	var image = $(image_el);
	var preview = $(image_el).parents('.carnet-day').find(".preview");
	preview.html(
		'<p><a href="' + image.attr('href') + '" target="_blank" title="' + image.attr('data-title') + '" rel="' + image.attr('rel') + '"><img src="' + image.attr('href') + '" alt="' + image.attr('data-title') + '" /></a></p><p class="caption">'+ image.attr('data-title') + '</p>'
	);

	preview.find("a").click(function() {
		
		if (/^gallery-([0-9]+)-([0-9]+)$/.test(image.attr('rel'))) {

			$.fancybox.open(
				images_js[RegExp.$1],
				{
					padding : 0,
					index : RegExp.$2,
					helpers: {
						title : {
							type : 'over'
						},
						thumbs	: {
							width	: 50,
							height	: 50,
							source : function(item) {
								return item.href.replace('/images/', '/images/thumb/');
							}
						}
					}
				}
			);
		}

		return false;
	});

	return false;
}

// click => show image
/*$(".images .thumbnail").each(function() {
	$(this).click(function() {
		return show_image(this);
	});
})*/


// auto-show first images
$(".images").each(function() {
	show_image($(this).find("a:first"));
});

// justified galleries
$(".images").justifiedGallery({
	'justifyLastRow' : false,
	'sizeRangeSuffixes' : {
		'lt100' : '',
		'lt240' : '',
		'lt320' : '',
		'lt500' : '',
		'lt640' : '',
		'lt1024': ''
	},
	'rowHeight' : 120,
	'onComplete': function(gal) {
		$(gal).find("a").each(function() {
			$(this).click(function() {
				return show_image(this);
			});
		});
	}
});
