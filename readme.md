# Symphony Slider Field #

Version: 0.2
Release date: 16-12-2011
Author: Giel Berkers  
Website:  [www.gielberkers.com](http://www.gielberkers.com)  
E-mail: <info@gielberkers.com>

### Contributors ###

[DesignerMonkey](https://github.com/designermonkey), for sorting out the filtering functions

---

## Description ##

This field adds a slider with the following functionality:

* Define a minimum and a maximum range
* Allow to select a range (from - to)
* Incremental values

### Filtering ###

Users can filter using the 'to' range operator as defined in the Symphony docs. They could use '{$param-1} to {$param-2}',
users can filter using '1-10' or similar, using the '-' between numbers. Another way of filtering is by using 'greater than' or
'smaller than' statements. For example: 'greater than 40'. Combining is als possible: 'greater than 40 + smaller than 60'.

Also, users can just enter one number either
via a parameter, or directly. Fallback paramaters work as default '{$param:10}' for example.

Results are returned in the following ways:

* If the **single entered number** is **within** the **defined range** (if range has been selected) then results will be returned.
* If the **single entered number** is **equal to** the **defined value** (if range has **not** been selected) then results will be returned.
* If the **range entered** is **within** the **defined range**, results will be returned.
* If the **defined range** is **within** the **range entered**, results will be returned.
