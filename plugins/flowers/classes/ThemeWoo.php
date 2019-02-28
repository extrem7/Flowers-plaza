<?php

require_once "Walker_Catalog.php";

class ThemeWoo
{
    public function __construct()
    {
        add_action('after_setup_theme', function () {
            add_theme_support('woocommerce');
        });
        add_action('init', function () {
            remove_action('wp_footer', array(WC()->structured_data, 'output_structured_data'), 10);
            remove_action('woocommerce_email_order_details', array(WC()->structured_data, 'output_email_structured_data'), 30);
        });
        add_action('wp_enqueue_scripts', function () {
            remove_action('wp_head', array($GLOBALS['woocommerce'], 'generator'));
            if (function_exists('is_woocommerce')) {
                if (!is_woocommerce() && !is_cart() && !is_checkout()) {
                    wp_dequeue_style('woocommerce_frontend_styles');
                    wp_dequeue_style('woocommerce_fancybox_styles');
                    wp_dequeue_style('woocommerce_chosen_styles');
                    wp_dequeue_style('woocommerce_prettyPhoto_css');
                    wp_dequeue_script('wc_price_slider');
                    wp_dequeue_script('wc-single-product');
                    wp_dequeue_script('wc-add-to-cart');
                    wp_dequeue_script('wc-cart-fragments');
                    wp_dequeue_script('wc-checkout');
                    wp_dequeue_script('wc-add-to-cart-variation');
                    wp_dequeue_script('wc-single-product');
                    wp_dequeue_script('wc-cart');
                    wp_dequeue_script('wc-chosen');
                    wp_dequeue_script('woocommerce');
                    wp_dequeue_script('prettyPhoto');
                    wp_dequeue_script('prettyPhoto-init');
                    wp_dequeue_script('jquery-blockui');
                    wp_dequeue_script('jquery-placeholder');
                    wp_dequeue_script('fancybox');
                    wp_dequeue_script('jqueryui');
                }
            }
        }, 99);
        add_action('template_redirect', function () {
            if (!is_checkout()) {
                add_filter('woocommerce_enqueue_styles', '__return_empty_array');
            }
        });
        add_filter('woocommerce_breadcrumb_defaults', function ($defaults) {
            $defaults['wrap_before'] = '<nav class="woocommerce-breadcrumb container">';
            $defaults['wrap_after'] = '</nav>';
            $defaults['before'] = '';
            $defaults['after'] = '';
            $defaults['delimiter'] = '<span class="breadcrumb-separator">/</span>';
            return $defaults;
        });

        remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
        remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

        add_filter('woocommerce_currency_symbol', function ($currency_symbol, $currency) {

            switch ($currency) {
                case 'RUB':
                    $currency_symbol = 'руб.';
                    break;
                case 'UAH':
                    $currency_symbol = 'грн.';
                    break;
            }

            return $currency_symbol;
        }, 10, 2);

        add_filter('wp_list_categories', function ($output, $args) {

            if (is_single()) {
                global $post;

                $terms = get_the_terms($post->ID, $args['taxonomy']);
                foreach ($terms as $term)
                    if (preg_match('#cat-item-' . $term->term_id . '#', $output))
                        $output = str_replace('cat-item-' . $term->term_id, 'cat-item-' . $term->term_id . ' current-cat', $output);
            }

            return $output;
        }, 10, 2);

        add_action('wp_ajax_add_to_cart', [$this, 'addToCart']);
        add_action('wp_ajax_nopriv_add_to_cart', [$this, 'addToCart']);
        // add_action('wp_ajax_create_order', [$this, 'createOrder']);
        // add_action('wp_ajax_nopriv_create_order', [$this, 'createOrder']);
        $this->cart();
        $this->checkout();
        $this->perPageSorting();
        //  $this->customFields();
    }

    private function cart()
    {
        add_action('woocommerce_before_checkout_form', function () {
            remove_action('woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10);
        }, 9);
        add_action('woocommerce_add_to_cart', function () {
            global $addedToCart;
            $addedToCart = true;
        }, 10, 3);
        add_action('template_redirect', function () {
            if (isset($_POST['remove-all']) && $_POST['remove-all']) {
                WC()->cart->empty_cart();
                wp_redirect(home_url(), 301);
            }
        });
    }

    private function checkout()
    {
        add_filter('woocommerce_add_error', function ($error) {
            if (strpos($error, 'Поле ') !== false) {
                $error = str_replace("Поле ", "", $error);
            }
            if (strpos($error, 'Оплата ') !== false) {
                $error = str_replace("Оплата ", "", $error);
            }
            return $error;
        });
        add_filter('woocommerce_checkout_fields', function ($fields) {
            $fields['billing']['billing_address_1']['required'] = false;
            $fields['billing']['billing_country']['required'] = false;
            $fields['billing']['billing_city']['required'] = false;
            $fields['billing']['billing_postcode']['required'] = false;
            $fields['billing']['billing_address_2']['required'] = false;
            $fields['billing']['billing_state']['required'] = false;
            $fields['billing']['billing_email']['required'] = false;
            $fields['order']['order_comments']['type'] = 'text';
            $fields['billing']['billing_postcode']['label'] = 'Квартира';
            $fields['billing']['billing_state']['label'] = 'Корпус';
            unset($fields['billing']['billing_last_name']);
            //unset($fields['billing']['billing_company']);
            //unset( $fields['billing']['billing_postcode'] );
            //unset( $fields['billing']['billing_state'] );
            //unset( $fields['billing']['billing_email'] );
            //unset($fields['billing']['billing_country']);
            //unset($fields['billing']['billing_address_2']);
            //unset($fields['billing']['billing_state']);
            return $fields;
        });
        add_filter('default_checkout_billing_country', function () {
            return 'RU'; // country code
        });
        function change_default_checkout_state()
        {
            return 'Московская'; // state code
        }

        add_action('woocommerce_before_checkout_form', function () {
            remove_action('woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10);
        }, 9);
    }

    private function perPageSorting()
    {
        global $limit;
        $limit = 12;
        if (!isset($_COOKIE['perpage'])) {
            setcookie('perpage', $limit, time() + (10 * 365 * 24 * 60 * 60), '/');
        } else {
            $limit = $_COOKIE['perpage'];
        }
        if (isset($_POST['perpage'])) {
            setcookie('perpage', $_POST['perpage'], time() + (10 * 365 * 24 * 60 * 60), '/');
            global $paged;
            $paged = 1;
            $limit = $_POST['perpage'];
        }
        add_action('pre_get_posts', function ($query) {
            global $limit;
            if ($query->is_tax && $query->is_main_query()) {
                $query->set('posts_per_page', $limit);
            }
        });
    }

    public function addToCart()
    {
        global $post, $qty;
        $product_id = apply_filters('woocommerce_add_to_cart_product_id', absint($_POST['id']));
        $qty = absint($_POST['qty']);
        $response = [];

        if (WC()->cart->add_to_cart($product_id, $qty)) {
            do_action('woocommerce_ajax_added_to_cart', $product_id);

            $response['status'] = 'ok';

            $response['notice'] = '<div class="woocommerce-message">';
            $response['notice'] .= wc_add_to_cart_message($product_id, false, true);
            $response['notice'] .= '</div>';

            ob_start();
            woocommerce_mini_cart();
            $response['cart'] = ob_get_contents();
            ob_end_clean();
            $post = get_post($product_id);
            ob_start();
            get_template_part('views/modal');
            $response['modal'] = '<div class="modal fade" id="cart-added">';
            $response['modal'] .= ob_get_contents();
            $response['modal'] .= '</div>';
            ob_end_clean();
            wp_reset_query();
        } else {
            $response['status'] = 'error';
        }
        echo json_encode($response);
        die();
    }

    public function paginationText()
    {
        global $wp_query;

        $pages = $wp_query->max_num_pages;
        $all = $wp_query->found_posts;
        if ($all !== 0) {
            $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
            $limit = $wp_query->get('posts_per_page');
            $from = ($paged - 1) * $limit + 1;
            $to = $paged * $limit;
            if ($wp_query->post_count !== $limit) {
                $to = ($paged - 1) * $limit;
                $to += $wp_query->post_count;
            }

            return "Показано с $from по $to из $all (всего страниц - $pages)";
        }
        return false;
    }

    public function parentCategories()
    {
        return get_terms('product_cat', [
            'parent' => 0,
        ]);
    }

    public function priceFilter()
    {
        $prices = $this->minMaxPrice();
        $min = $prices->min_price;
        $max = $prices->max_price;

        $min_price = isset($_GET['min_price']) ? wc_clean(wp_unslash($_GET['min_price'])) : apply_filters('woocommerce_price_filter_widget_min_amount', $min);
        $max_price = isset($_GET['max_price']) ? wc_clean(wp_unslash($_GET['max_price'])) : apply_filters('woocommerce_price_filter_widget_max_amount', $max);
        echo "<div class=\"price-inputs\">";
        echo "от <input type=\"number\" name=\"min_price\" value=\"$min_price\" data-min=\"$min\" id=\"price-from\">";
        echo "до <input type=\"number\" name=\"max_price\" value=\"$max_price\" data-max=\"$max\" id=\"price-to\"> руб.";
        echo "</div>";
        echo "<div id=\"slider-range\" class=\"mb-3\"></div>";
    }

    public function minMaxPrice()
    {
        global $wpdb;
        $category = get_queried_object();

        $categoryQuery = new WP_Query([
            'tax_query' => [
                [
                    'taxonomy' => 'product_cat',
                    'terms' => $category->term_id,
                    'field' => 'id',
                    'include_children' => true,
                    'operator' => 'IN'
                ]
            ],
            'meta_query' => [
                [
                    'key' => '_stock_status',
                    'value' => 'instock'
                ]
            ]
        ]);
        $args = $categoryQuery->query_vars;
        $tax_query = isset($args['tax_query']) ? $args['tax_query'] : array();
        $meta_query = isset($args['meta_query']) ? $args['meta_query'] : array();

        if (!is_post_type_archive('product') && !empty($args['taxonomy']) && !empty($args['term'])) {
            $tax_query[] = array(
                'taxonomy' => $args['taxonomy'],
                'terms' => array($args['term']),
                'field' => 'slug',
            );
        }

        foreach ($meta_query + $tax_query as $key => $query) {
            if (!empty($query['price_filter']) || !empty($query['rating_filter'])) {
                unset($meta_query[$key]);
            }
        }

        $meta_query = new WP_Meta_Query($meta_query);
        $tax_query = new WP_Tax_Query($tax_query);

        $meta_query_sql = $meta_query->get_sql('post', $wpdb->posts, 'ID');
        $tax_query_sql = $tax_query->get_sql($wpdb->posts, 'ID');

        $sql = "SELECT min( FLOOR( price_meta.meta_value ) ) as min_price, max( CEILING( price_meta.meta_value ) ) as max_price FROM {$wpdb->posts} ";
        $sql .= " LEFT JOIN {$wpdb->postmeta} as price_meta ON {$wpdb->posts}.ID = price_meta.post_id " . $tax_query_sql['join'] . $meta_query_sql['join'];
        $sql .= " 	WHERE {$wpdb->posts}.post_type IN ('" . implode("','", array_map('esc_sql', apply_filters('woocommerce_price_filter_post_type', array('product')))) . "')
			AND {$wpdb->posts}.post_status = 'publish'
			AND price_meta.meta_key IN ('" . implode("','", array_map('esc_sql', apply_filters('woocommerce_price_filter_meta_keys', array('_price')))) . "')
			AND price_meta.meta_value > '' ";
        $sql .= $tax_query_sql['where'] . $meta_query_sql['where'];

        $search = WC_Query::get_main_search_query_sql();
        if ($search) {
            $sql .= ' AND ' . $search;
        }

        return $wpdb->get_row($sql); // WPCS: unprepared SQL ok.
    }

    public function attributes()
    {
        $attributes = $this::currentAttributes();
        $currentAttributes = $attributes[0];
        $attributeCount = $attributes[1];
        $chosenAttributes = WC_Query::get_layered_nav_chosen_attributes();

        foreach ($currentAttributes as $key => $terms) {
            $attribute = get_taxonomy($key);
            if ($attribute) {
                $attrName = $attribute->labels->singular_name;
                $attrSlug = preg_replace('/pa/', 'filter', $attribute->name);
                $attrQueryType = preg_replace('/pa/', 'query_type', $attribute->name);
                $result = '';
                if (isset($_REQUEST[$attrSlug])) {
                    $result = $_REQUEST[$attrSlug];
                }
                echo "<div class='filter-block'>";
                echo "<span class='title'>$attrName:</span>";
                echo "<input type='hidden' class='result' name='$attrSlug' value='$result'>";
                echo "<input type='hidden' class='queryType' name='$attrQueryType' value='or'>";
                $options = [];
                foreach ($terms as $term) {
                    $term = get_term($term, $key);
                    $termValue = urldecode($term->slug);
                    $termName = $term->name;
                    $termId = $term->term_id;
                    $termChecked = '';
                    $count = $attributeCount[$key][$termId];
                    if (isset($chosenAttributes[$attribute->name]) && !empty($chosenAttributes[$attribute->name]['terms'])) {
                        $termChecked = in_array(mb_strtolower(urlencode_deep($termValue)), $chosenAttributes[$attribute->name]['terms']) ? 'checked' : '';
                    }
                    $options[] = ['termId' => $termId, 'termValue' => $termValue, 'termChecked' => $termChecked, 'termName' => $termName, 'count' => $count];
                }
                $options = sort_by_key($options, 'termName');
                $o = 0;
                foreach ($options as $option) {
                    $termValue = $option['termValue'];
                    $termName = $option['termName'];
                    $termId = $option['termId'];
                    $termChecked = $option['termChecked'];
                    $count = $option['count'];
                    if ($o == 2) {
                        echo "<div class=\"hidden\">";
                    }
                    echo "<input type='checkbox' class='custom-control-input' id='$termId' value='$termValue' $termChecked>";
                    echo " <label for='$termId'>$termName</label>";
                    $o++;
                }
                if ($o >= 3) {
                    echo "</div>";
                    echo "<a href=\"#\" class=\"show-more active\">Показать все параметры</a>";
                }
                echo "</div>";
            }
        }
    }

    public static function currentAttributes($posts = null)
    {

        if (is_null($posts)) {
            $category = get_queried_object();

            $posts = get_posts([
                'post_type' => 'product',
                'post_status' => 'publish',
                'tax_query' => [
                    [
                        'taxonomy' => 'product_cat',
                        'field' => 'id',
                        'terms' => $category->term_id,
                        'operator' => 'IN'
                    ]
                ],
                'meta_query' => [
                    [
                        'key' => '_stock_status',
                        'value' => 'instock'
                    ]
                ]
            ]);
        }

        $currentAttributes = [];
        $uniqueAttributes = [];

        foreach ($posts as $post) {
            $attributes = wc_get_product($post->ID)->get_attributes();

            foreach ($attributes as $attr) {
                $key = $attr->get_name();

                if (!in_array($key, $uniqueAttributes) && $attr->is_taxonomy()) {
                    array_push($uniqueAttributes, $key);
                }
            }
        }
        $currentAttributes = array_fill_keys($uniqueAttributes, []);
        $attributeCount = $currentAttributes;

        foreach ($posts as $post) {
            $attrs = wc_get_product($post->ID)->get_attributes();
            foreach ($attrs as $attr) {
                if ($attr->is_taxonomy()) {
                    $key = $attr->get_name();
                    foreach ($attr->get_options() as $option) {
                        if (!in_array($option, $currentAttributes[$key])) {
                            array_push($currentAttributes[$key], $option);
                        }
                        $attributeCount[$key][$option]++;
                    }
                }
            }
        }


        return [$currentAttributes, $attributeCount];
    }

    public function featuredProducts($limit, $category)
    {
        $query = new WP_Query([
            'post_type' => 'product',
            'post_status' => 'publish',
            'posts_per_page' => $limit,
            'tax_query' => [
                'relation' => 'AND',
                [
                    'taxonomy' => 'product_visibility',
                    'field' => 'name',
                    'terms' => 'featured',
                    'operator' => 'IN'
                ],
                [
                    'taxonomy' => 'product_cat',
                    'terms' => $category,
                    'field' => 'id',
                    'include_children' => true,
                    'operator' => 'IN'
                ]
            ]
        ]);

        return $query;
    }
}