<?php
require_once "classes/ThemeBase.php";
require_once "classes/ThemeWoo.php";

class Theme extends ThemeBase
{
    public $woo;

    public $geo;

    public function __construct()
    {
        parent::__construct();
        add_action('init', function () {
            //$this->registerTaxonomies();
            //$this->registerPostTypes();
        });
        add_action('plugins_loaded', function () {
            $this->woo = new ThemeWoo();
        });

        //add_action('wp_ajax_action', [$this, 'method']);
        //add_action('wp_ajax_nopriv_action', [$this, 'method']);
    }

    public function mail()
    {
        date_default_timezone_set('Europe/Moscow');

        $headers = "From: Auto2Credit <admin@" . $_SERVER['SERVER_NAME'] . ">\r\n";
        $headers .= 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-Type: text/html; charset=utf-8' . "\r\n";

        $subject = 'Запись на Auto2Credit';

        $fields = [];

        $name = null;
        $phone = null;
        $email = null;
        $city = null;

        if (isset($_POST['name']) && $_POST['name']) {
            $name = $_POST['name'];
        }
        if (isset($_POST['tel']) && $_POST['tel']) {
            $phone = $_POST['tel'];
        }
        if (isset($_POST['email']) && $_POST['email']) {
            $email = $_POST['email'];
        }
        if (isset($_POST['city']) && $_POST['city']) {
            $city = $_POST['city'];
        }

        $fields['Дата заполнения'] = date('d.m.Y');
        $fields['Время заполнения'] = date('G:i');
        $fields['Имя клиента'] = $name ? $name : '-';
        $fields['Город клиента'] = $city ? $city : '-';
        $fields['Телефон клиента'] = $phone ? $phone : '-';
        $fields['Email клиента'] = $email ? $email : '-';

        $message = "<html><head></head><body><table border=\"1\" cellpadding=\"7\" cellspacing=\"0\" bordercolor=\"#CCCCCC\">";
        foreach ($fields as $key => $field) {
            $message .= "<tr><td>$key</td><td>$field</td></tr>";
        }
        $message .= "</table><br><p>Данное сообщение сгенерировано автоматически. Пожалуйста, не отвечайте на него.</p>";
        $message .= "</body></html>";

        $mail = mail("alex.roox5@gmail.com , extrem7ipad@gmail.com", $subject, $message, $headers);

        if ($mail) {
            echo json_encode(['status' => 'ok']);
        } else {
            echo json_encode(['status' => 'error']);
        }
    }

    public function views()
    {
        return new class
        {
            public function sales()
            {
                get_template_part('views/sales');
            }

            public function advantages()
            {
                get_template_part('views/advantages');
            }
        };
    }

    public function servicesQuery()
    {
        return new WP_Query([
            'post_type' => 'page',
            'posts_per_page' => -1,
            'post_parent' => 83,
            'order' => 'ASC',
            'orderby' => 'menu_order'
        ]);
    }

    private function registerPostTypes()
    {
        register_post_type('',
            ['label' => null,
                'labels' => [
                    'name' => 'Номера', // основное название для типа записи
                    'singular_name' => 'Номера', // название для одной записи этого типа
                    'add_new' => 'Добавить номер', // для добавления новой записи
                    'add_new_item' => 'Добавление номера', // заголовка у вновь создаваемой записи в админ-панели.
                    'edit_item' => 'Редактирование номера', // для редактирования типа записи
                    'new_item' => '', // текст новой записи
                    'view_item' => 'Смотреть номер', // для просмотра записи этого типа.
                    'search_items' => 'Искать номера', // для поиска по этим типам записи
                    'not_found' => 'Не найдено', // если в результате поиска ничего не было найдено
                    'not_found_in_trash' => 'Не найдено в корзине', // если не было найдено в корзине
                    'menu_name' => 'Номера', // название меню
                ],
                'public' => true,
                'menu_position' => 3,
                'menu_icon' => 'dashicons-admin-home',
                'supports' => array('title', 'editor', 'custom-fields', 'thumbnail'),
                'has_archive' => true,
                'rewrite' => ['slug' => ''],]);
    }

    private function registerTaxonomies()
    {
        register_taxonomy('gallery_cat',
            ['post'],
            ['label' => '',
                'labels' => [
                    'name' => 'Категории фото',
                    'singular_name' => 'Категории фото',
                    'search_items' => 'Искать Категорию фото',
                    'all_items' => 'Новая Категория фото',
                    'view_item ' => 'Смотреть Категорию фото',
                    'parent_item' => 'Родитель Категории фото',
                    'parent_item_colon' => 'Родитель Категории фото:',
                    'edit_item' => 'Редактировать Категорию фото',
                    'update_item' => 'Обновить Категорию фото',
                    'add_new_item' => 'Добавить новую Категорию фото',
                    'new_item_name' => 'Категории фото',
                    'menu_name' => 'Категории фото'],
                'public' => true,
                'meta_box_cb' => false,]);
    }

}