<?php
/*
Plugin Name: My Custom Code
Description: Custom code to use with the Datafeedr plugins. Don't delete me!
License: GPL v3

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
*/


/**
* Add color attribute.
*
* The attribute "Color" with a slug of "color" must already exist here:
* WordPress Admin Area > Products > Attributes.
*/
add_filter( 'dfrpswc_filter_attribute_value', 'mycode_add_color_attribute', 20, 6 );
function mycode_add_color_attribute( $value, $attribute, $post, $product, $set, $action ) {
if ( $attribute == 'pa_color') {
if ( isset( $product['color'] ) ) {
$value = $product['color'];
}
}
return $value;
} 


/**
* Add size attribute.
*
* The attribute "Size" with a slug of "size" must already exist here:
* WordPress Admin Area > Products > Attributes.
*/
add_filter( 'dfrpswc_filter_attribute_value', 'mycode_add_size_attribute', 20, 6 );
function mycode_add_size_attribute( $value, $attribute, $post, $product, $set, $action ) {
if ( $attribute == 'pa_size') {
if ( isset( $product['size'] ) ) {
$value = $product['size'];
}
}
return $value;
} 


/**
* Add size attribute.
*
* The attribute "Brand" with a slug of "brand" must already exist here:
* WordPress Admin Area > Products > Attributes.
*/
add_filter( 'dfrpswc_filter_attribute_value', 'mycode_add_brand_attribute', 20, 6 );
function mycode_add_brand_attribute( $value, $attribute, $post, $product, $set, $action ) {
if ( $attribute == 'pa_brand') {
if ( isset( $product['brand'] ) ) {
$value = $product['brand'];
}
}
return $value;
} 








