<?php
/**
 * Shoptimizer child theme functions
 *
 * @package shoptimizer
 */
// 先从URL上去除Category

// add_filter('category_link','remove_category_link');
// add_filter('term_link','remove_category_link');

// function remove_category_link($link){
// 	return str_replace('/collections/','/',$link);
// }

// // 重写下去除后的规则让去除后访问仍然访问category

// add_action('init','rewrite_category_link');

// function rewrite_category_link(){
// 	//所有链接是所有分类访问的链接，而不是其他链接
// 	$terms = get_terms(array(
// 		'taxonomy' => 'collections',
// 		'hide_empty' => false
// 	));
// 	foreach($terms as $term){
// 		add_rewrite_rule('^'.$term->slug.'/?$','index.php?collections='.$term->slug,'top');
// 	}
// 	flush_rewrite_rules(false);
// }

//移除头部用户头像
// add_action('shoptimizer_header', 'remove_header_account');
// function remove_header_account() {
//     remove_action( 'shoptimizer_header', 'shoptimizer_secondary_navigation', 30 );
// }
//移除头部导航右侧购物车按钮
 add_action('shoptimizer_navigation', 'remove_header_account');
 function remove_header_account() {
    remove_action( 'shoptimizer_navigation', 'shoptimizer_header_cart', 60 );
 }

