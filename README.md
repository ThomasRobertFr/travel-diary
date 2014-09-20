# Travel diary

This is a really simple web app for travel diary. To use it, you simply have to add your trip notes in the data folder.

![demo](https://raw.github.com/ThomasRobertFr/travel-diary/gh-pages/demo.png)

## How to use

In the data folder, add one folder per trip. See the `13-demo` folder for an example.

In each folder, you shoud have:

* `images` folder for images
* `infos.json` for metadata
* `text.html` for the content
* `cover.jpg` for the cover picture

### `images` folder

Put your images in this folder. You can create folders in it if you want. For each image, a thumbnail
should be available with the same name and same path but in the folder `thumb`.

For example for the image `day1/image1.jpg` stored in `images folder`, you must have the thumbnail
`thumb/day1/image1.jpg`.

I recommand thumbnails with an height of 200px.

### `infos.json`

A JSON file that will contain the metadata of your trip:

```json
{
	"titre" :		"Trip title",
	"date_debut":	"28-10-2013",
	"date_fin":		"02-11-2013",
	"avec" :		"Person 1, Person 2, Person 3",
	"lieu" :		"Your location"
}
```

### `text.html`

A pseudo-HTML file that will contain the description of your trip, with text and images legend.

This file will contain multiple `<block>` blocks each one describing a day for example.

Each block will containing a `<text>` block that will contain you HTML text for this block, and an
`images` block containing the name and legend of each picture to display for this block.

An image is described like this: `<img src="{path}" title="{legend}" />` where `{path}` is the relative
path to the image starting from the `images` repository.

For example `<img src="day1/image1.jpg" title="My first picture" />`

Here is a complete example of `<block>`:

```html
<block title="First day">
	<text>
		<p><strong>Pellentesque habitant morbi tristique</strong> senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. <em>Aenean ultricies mi vitae est.</em> Mauris placerat eleifend leo. Quisque sit amet est et sapien ullamcorper pharetra. Vestibulum erat wisi, condimentum sed, <code>commodo vitae</code>, ornare sit amet, wisi. Aenean fermentum, elit eget tincidunt condimentum, eros ipsum rutrum orci, sagittis tempus lacus enim ac dui. <a href="#">Donec non enim</a> in turpis pulvinar facilisis. Ut felis.</p>

		<ol>
		<li>Lorem ipsum dolor sit amet, consectetuer adipiscing elit.</li>
		<li>Aliquam tincidunt mauris eu risus.</li>
		</ol>
	</text>
	<images>
		<img src="day1/placeholder1.gif" title="Picture 1" />
		<img src="day1/placeholder2.gif" title="Picture 2" />
		<img src="day1/placeholder3.gif" title="Picture 3" />
		<img src="day1/placeholder4.gif" title="Picture 4" />
		<img src="day1/placeholder5.gif" title="Picture 5" />
	</images>
</block>
```
### `cover.jpg`

A JPEG image that will be used as cover picture on top of your trip story.

## GPLv3 License

Copyright (C) 2013 Thomas Robert (http://thomas-robert.fr).

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.