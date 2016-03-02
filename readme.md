# Symphony CMS : Slider Field #

A slider with a configurable value range (minimum – maximum, e.g. `0`–`100`) that can either be used with a single handle (select a single value, e.g. `50`) or with two handles (select a value range, e.g. `40`–`50`).


## 1. Installation

1. Upload the `/slider` folder in this archive to your Symphony `/extensions` folder.
2. Go to the **System > Extensions** in your Symphony admin area.
2. Enable the extension by selecting the '**Slider Field**', choose '**Enable**' from the '**With Selected…**' menu, then click '**Apply**'.
3. You can now add the '**Slider**' field to your sections.


## 2. Configuration ##

### Parameters

* **Minimum value** * : Determines the lower end of the sliders number range.
* **Maximum value** * : Determines the upper end of the sliders number range.
* **Start value** * : Determines the initial value of the slider and therefore the position of the (first) handle. <sup><a id="#a2.1" href="#f2.1">1</a></sup>
* **Incremental value** : Determines the amount/interval the slider changes on movement. <sup><a id="#a2.2" href="#f2.2">2</a></sup>
* **Enable range mode** : Determines whether the slider will use one or two handles. <sup><a id="#a2.3" href="#f2.3">3</a></sup>


<sup>
	<b>*</b> Mandatory parameters.<br/>
	<b id="f2.1">1)</b> It goes without saying that this value should be in the range defined by the first two configuration parameters.<br/>
	<b id="f2.2">2)</b> The full specified value range of the slider (minimum – maximum) should be evenly divisible by this value. Default is `0`.<br/>
	<b id="f2.3">3)</b> One handle allows for selecting and storing a single value. Two handles allow for selecting and storing a range of values.
</sup>


### Limitations ###

* The field currently only supports _natural numbers_ (`0`,`1`,`2`,`3`,…) as values for '**Minimum value**', '**Maximum value**', '**Start value** and '**Incremental value**'.
* There is no parameter to set a default '**End value**' when using the slider in _range mode_. The predefined range will consist of the '**Start value**' and a second value that's automatically calculated by adding the '**Incremental value**' to the '**Start value**'.


## 3. Filtering ##

### Filter Modes

1. **is**
2. **less than** <sup><a id="#a3.1" href="#f3.1">1</a></sup>
4. **greater than**
6. **between**

<sup>
	<b id="f3.1">1)</b> Version 0.2 uses the term "**smaller than**" instead. This syntax is marked as **deprecated** and won't be supported in future major releases of this extension.
</sup>

### Filtering Syntax

Documentation and examples regarding the filtering syntax can be found in the extension's [github wiki](https://github.com/twiro/slider/wiki/filtering#2-filtering-syntax).


### Filtering Logic

Entries filtered by slider field will be returned as result …

* ... if the entry's slider defines a **single value** and a **single filter value** is **equal** to that value.
* ... if the entry's slider defines a **single value** that lies **within** a **range of filter values**.
* ... if the entry's slider defines a **value range** and a **single filter value** is **within** that range.
* ... if the entry's slider defines a **value range** that lies **within** a **range of filter values**.
