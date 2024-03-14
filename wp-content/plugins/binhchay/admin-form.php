<?php
class Admin_Form
{
    const ID = 'config-seo';

    public function init()
    {
        add_action('admin_menu', array($this, 'add_menu_pages'), 1);
        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
        add_action('wp_ajax_save_category', array($this, 'save_category'));
        add_action('wp_ajax_save_general', array($this, 'save_general'));
        add_action('wp_ajax_save_top_category', array($this, 'save_top_category'));
        add_action('wp_ajax_search_post', array($this, 'search_post'));
        add_action('wp_ajax_add_top_game', array($this, 'add_top_game'));
        add_action('wp_ajax_delete_top_game', array($this, 'delete_top_game'));
    }

    public function get_id()
    {
        return self::ID;
    }

    public function admin_enqueue_scripts($hook_suffix)
    {
        if (strpos($hook_suffix, $this->get_id()) === false) {
            return;
        }

        wp_enqueue_media();

        wp_enqueue_style('config-admin-form-bs', 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css', BINHCHAY_ADMIN_VERSION);
        wp_enqueue_script(
            'config-admin-form-bs',
            'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js',
            array('jquery'),
            BINHCHAY_ADMIN_VERSION,
            true
        );

        wp_enqueue_script(
            'config-admin-form-bs',
            'https://code.jquery.com/jquery-3.7.1.slim.js'
        );

        echo '
        <style>
            .button-submit {
                border: 1px solid black !important;
            }

            .post-title {
                font-weight: bold !important;
                font-size: 19px !important;
            }

            #alert-post {
                display: none;
            }
        </style>';
    }

    function add_menu_pages()
    {
        add_menu_page('For SEO', 'For SEO', 10, $this->get_id() . '_general', array(&$this, 'load_view_general'), plugins_url('binhchay/images/icon.png'));
        add_submenu_page($this->get_id() . '_general', 'General', 'General', 10,  $this->get_id() . '_general', array(&$this, 'load_view_general'));
        add_submenu_page($this->get_id() . '_general', 'Set Category', 'Set Category', 10,  $this->get_id() . '_set_category', array(&$this, 'load_view_set_category'));
        add_submenu_page($this->get_id() . '_general', 'Top game for category', 'Top game for category', 10,  $this->get_id() . '_top_game_category', array(&$this, 'load_view_top_game_category'));
    }

    public function load_view_general()
    {
        $h1Homepage = get_option('h1_homepage');
        $descriptionHomepage = get_option('description_homepage');
        $topCategory = get_option('top_category_homepage');
        if(empty($topCategory)) {
            $arrTopCategory = array();
        } else {
            $arrTopCategory = json_decode(json_encode(json_decode($topCategory)), true);
        }
        
        $nonce = wp_create_nonce("get_game_nonce");
        $link = admin_url('admin-ajax.php');
        $categories = get_categories();

        echo '<script>
            let dataGeneral = {
                h1: "",
                description: "",
            };';
        if ($descriptionHomepage) {
            echo 'dataGeneral.description = `' . html_entity_decode($descriptionHomepage) . '`;';
        }
        echo '</script>';
        echo '<script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>';
        echo '<div class="container mt-5">';
        echo "<div class='alert' role='alert' id='alert-post'></div>";
        echo '<h3>Setup H1 in homepage</h3>';
        if ($h1Homepage) {
            echo '<input class="form-control" value="' . $h1Homepage . '" id="h1-home-page">';
        } else {
            echo '<input class="form-control" id="h1-home-page">';
        }
        echo '<h3 class="mt-4">Setup Description in homepage</h3>';
        echo '<span style="margin-top: 10px;">
        <textarea name="description_homepage">' . $descriptionHomepage . '</textarea>
        </span>';
        echo '<script>
        let area_description_homepage = CKEDITOR.replace("description_homepage");
        area_description_homepage.on("change", function(evt) {
            dataGeneral.description = String(evt.editor.getData());
        });
        </script>';
        echo '<button class="btn btn-primary mt-4" type="button" id="save-general">Save</button>';
        echo '<div class="mt-5">
        <h3>Setup top categories</h3>
        <div class="row">';

        foreach ($categories as $category) {

            
            echo '<div class="col-2 mt-2"><h4 style="float: left">' . $category->name . '</h4>
            <span id="area-top-' . $this->slugify($category->name) . '">';
            if (array_key_exists($this->slugify($category->name), $arrTopCategory)) {
                if ($arrTopCategory[$this->slugify($category->name)] == 'true') {
                    echo '<button class="btn btn-danger" style="float: right; width: 36.55px" id="top-' . $this->slugify($category->name) . '" status="false">x</button>';
                } else {
                    echo '<button class="btn btn-success" style="float: right; width: 36.55px" id="top-' . $this->slugify($category->name) . '" status="true">+</button>';
                }
            } else {
                echo '<button class="btn btn-success" style="float: right; width: 36.55px" id="top-' . $this->slugify($category->name) . '" status="true">+</button>';
            }

            echo '</span>
            </div>
            <script>
            jQuery("#top-' . $this->slugify($category->name) . '").on("click", function(e) {
                let status = jQuery("#top-' . $this->slugify($category->name) . '").attr("status");

                jQuery.post("' . $link . '", 
                {
                    "action": "save_top_category",
                    "data": {' . str_replace('-', '_', $this->slugify($category->name)) . ': status},
                    "nonce": "' . $nonce . '"
                }, 
                function(response) {
                    if(status == "true") {
                        jQuery("#top-' . $this->slugify($category->name) . '").attr("status", "false");
                        jQuery("#top-' . $this->slugify($category->name) . '").html("x");
                        jQuery("#top-' . $this->slugify($category->name) . '").removeClass("btn-success");
                        jQuery("#top-' . $this->slugify($category->name) . '").addClass("btn-danger");
                    } else {
                        jQuery("#top-' . $this->slugify($category->name) . '").attr("status", "true");
                        jQuery("#top-' . $this->slugify($category->name) . '").html("+");
                        jQuery("#top-' . $this->slugify($category->name) . '").removeClass("btn-danger");
                        jQuery("#top-' . $this->slugify($category->name) . '").addClass("btn-success");
                    }
                });
            });
            </script>';
        }
        echo '</div>';
        echo '</div>';
        echo '</div>';

        echo '<script>
        jQuery(document).ready( function() {
            jQuery("#save-general").on("click", function(e) {
                e.preventDefault();
                dataGeneral.h1 = jQuery("#h1-home-page").val();
        
                jQuery.post("' . $link . '", 
                    {
                        "action": "save_general",
                        "data": dataGeneral,
                        "nonce": "' . $nonce . '"
                    }, 
                    function(response) {
                        if(response == "failed") {
                            let alert = document.getElementById("alert-post");
                            if(alert.classList.contains("alert-success")) {
                                alert.classList.remove("alert-success");
                            }
                            alert.classList.add("alert-danger");
                            alert.style.display = "block";
                            alert.innerHTML = "Save failed! H1 or Description is empty.";
                        } else {
                            let alert = document.getElementById("alert-post");
                            if(alert.classList.contains("alert-danger")) {
                                alert.classList.remove("alert-danger");
                            }
                            alert.classList.add("alert-success");
                            alert.style.display = "block";
                            alert.innerHTML = "Save successfully!";
                        }
                    }
                );
            });
        });
        </script>';
    }

    public function load_view_set_category()
    {
        $categories = get_categories();
        $custom_categories = $this->getCustomCategory();
        $nonce = wp_create_nonce("get_game_nonce");
        $link = admin_url('admin-ajax.php');

        echo '<script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>';
        echo '<script>
            let listCategory = ' . json_encode($categories) . ';
            let array = Object.keys(listCategory);
            let dataCategory = {};
        </script>';
        echo '<div class="container mt-5">';
        echo "<div class='alert' role='alert' id='alert-post'></div>";
        echo '<ul class="list-group ul-post">';

        foreach ($categories as $category) {
            foreach ($custom_categories as $cate) {
                if ($category->term_id == $cate->category_id) {
                    $category->title = $cate->title;
                    $category->content = $cate->content;
                }
            }

            echo '<li class="list-group-item item-post">
                <div>
                <span class="post-title">' . $category->name . '</span>
                </div>
                <span>
                <p class="mt-2 mb-2">Title</p>
                <input class="form-control" type="text" value="' . $category->title . '" id="title-' . $category->slug . '">
                </span>
                <span>
                <p class="mt-2 mb-2">Description</p>
                <textarea name="' . $category->slug . '">' . $category->content . '</textarea>
                </span>
                <script>
                let area_' . str_replace('-', '_', $category->slug) . ' = CKEDITOR.replace("' . $category->slug . '");
                dataCategory.' . str_replace('-', '_', $category->slug) . ' = {
                    content: "",
                    title: "",
                };
                area_' . str_replace('-', '_', $category->slug) . '.on("change", function(evt) {
                    dataCategory.' . str_replace('-', '_', $category->slug) . '.content = String(evt.editor.getData());
                });
                
                jQuery("#title-' . $category->slug . '").on("change", function() {
                    dataCategory.' . str_replace('-', '_', $category->slug) . '.title = jQuery("#title-' . $category->slug . '").val();
                });';

            if (isset($category->content)) {
                echo 'dataCategory.' . str_replace('-', '_', $category->slug) . '.content = `' . html_entity_decode($category->content) . '`;';
            }

            echo '</script></li>';
        }

        echo '</ul>';
        echo '<button class="btn btn-success mt-4" type="button" id="save-category">Save</button>';
        echo '</div>';

        echo '<script>
        jQuery(document).ready( function() {
            jQuery("#save-category").on("click", function(e) {
                e.preventDefault();
        
                jQuery.post("' . $link . '", 
                    {
                        "action": "save_category",
                        "data": dataCategory,
                        "nonce": "' . $nonce . '"
                    }, 
                    function(response) {
                        document.documentElement.scrollTop = 0;
                        setInterval(function () {
                            let alert = document.getElementById("alert-post");
                            alert.classList.add("alert-success");
                            alert.style.display = "block";
                            alert.innerHTML = "Save successfully!";
                        }, 3000);
                    }
                );
            });
        });
        </script>';
    }

    public function load_view_top_game_category()
    {
        $categories = get_categories();
        $topGame = $this->getTopGameByCategory();
        $nonce = wp_create_nonce("get_game_nonce");
        $link = admin_url('admin-ajax.php');
        $arrGame = array();
        add_thickbox();

        foreach ($topGame as $game) {
            $getCate = get_category($game->category_id);
            $arrGame[$getCate->slug] = $game;
        }

        echo '<div class="container mt-5"><div class="row">';
        echo '<h2 class="mb-4">Setup top game for category</h2>';
        foreach ($categories as $category) {
            echo '<div class="col-4">';
            echo '<h5>' . $category->name . '</h5>';
            echo '<a href="#TB_inline?height=550&inlineId=modal-window-id-' . str_replace('_', '-', $category->slug) . '" class="btn btn-success thickbox">Add game</a>';
            echo '<div id="modal-window-id-' . str_replace('_', '-', $category->slug) . '" style="display:none;">';
            echo '<h4>List game ' . ucfirst(str_replace('_', '-', $category->slug)) . '</h4>';
            echo '<input type="text" class="form-control" aria-label="Search list" aria-describedby="inputGroup-sizing-default" placeholder="Enter post url" data-id="' . $category->term_id . '" id="search-post-list-' . str_replace('_', '-', $category->slug) . '">';
            echo '<div id="text-box-result-' . str_replace('_', '-', $category->slug) . '" class="d-flex justify-content-between mt-3"></div>';
            echo '<ul class="list-group mt-4" id="list-top-game-' . str_replace('_', '-', $category->slug) . '">';
            if (array_key_exists($category->slug, $arrGame)) {
                if($arrGame[$category->slug]->game != '') {
                    $explode = explode(',', $arrGame[$category->slug]->game);
                    foreach ($explode as $game_id) {
                        echo '<li class="list-group-item d-flex justify-content-between" id="item-top-game-' . str_replace('_', '-', $category->slug) . '-' . $game_id . '"><span>' . get_permalink($game_id) . '</span><button class="btn btn-danger" data-name-cate="' . str_replace('_', '-', $category->slug) . '" data-id-game="' . $game_id . '" data-category-id="' . $category->term_id . '" onclick="deleteGame(this)">x</button></li>';
                    }
                }
            }
            echo '</ul>';
            echo '</div></div>';

            echo '<script>
            jQuery("#search-post-list-' . str_replace('_', '-', $category->slug) . '").on("change", function() {
                let url = jQuery(this).val();
                let id = jQuery(this).attr("data-id");
                let data = {
                    "url": url,
                    "id": id
                };
                jQuery.post("' . $link . '", 
                {
                    "action": "search_post",
                    "data": data,
                    "nonce": "' . $nonce . '"
                }, 
                function(response) {
                    if(response == "notfound") {
                        jQuery("#text-box-result-' . str_replace('_', '-', $category->slug) . '").empty();
                        jQuery("#text-box-result-' . str_replace('_', '-', $category->slug) . '").append("<h4>No results found!</h4>");
                    } else if(response == "exists") {
                        jQuery("#text-box-result-' . str_replace('_', '-', $category->slug) . '").empty();
                        jQuery("#text-box-result-' . str_replace('_', '-', $category->slug) . '").append("<h4>Game exist in list!</h4>");
                    } else {
                        let data = JSON.parse(response);
                        let str = `<p>` + data.url + `</p><button class="btn btn-success" id="save-top-game-' . str_replace('_', '-', $category->slug) . '" data-name-cate="' . str_replace('_', '-', $category->slug) . '" data-category-id="' . $category->term_id . '" data-id="` + data.id + `" data-url="` + data.url + `" onclick="addGame(this)">Add</button>`;
                        jQuery("#text-box-result-' . str_replace('_', '-', $category->slug) . '").empty();
                        jQuery("#text-box-result-' . str_replace('_', '-', $category->slug) . '").append(str);
                    }
                });
            });

            function addGame(button) {
                let id = button.getAttribute("data-id");
                let category_id = button.getAttribute("data-category-id");
                let url = button.getAttribute("data-url");
                let cate = button.getAttribute("data-name-cate");
                data = {
                    "id": id,
                    "category_id": category_id
                };

                jQuery.post("' . $link . '", 
                {
                    "action": "add_top_game",
                    "data": data,
                    "nonce": "' . $nonce . '"
                }, 
                function(response) {
                    let li = `<li class="list-group-item d-flex justify-content-between" id="item-top-game-`+ cate + `-` + id + `"><span>` + url + `</span><button class="btn btn-danger" data-name-cate="` + cate + `" data-id-game="` + id + `" data-category-id="` + category_id + `" onclick="deleteGame(this)">x</button></li>`;
                    let idUL = "#list-top-game-" + cate;
                    jQuery(idUL).append(li);
                });
            }

            function deleteGame(button) {
                let id = button.getAttribute("data-id-game");
                let category_id = button.getAttribute("data-category-id");
                let cate = button.getAttribute("data-name-cate");

                let data = {
                    id: id,
                    category_id: category_id
                };
                jQuery.post("' . $link . '", 
                {
                    "action": "delete_top_game",
                    "data": data,
                    "nonce": "' . $nonce . '"
                }, 
                function(response) {
                    let idUL = "#item-top-game-" + cate + "-" + id;
                    jQuery(idUL).remove();
                });
            }
            </script>';
        }

        echo '</div></div>';
        echo '<script>

        jQuery(document).ready( function() {
            jQuery("#save-category").on("click", function(e) {
                e.preventDefault();
        
                jQuery.post("' . $link . '", 
                    {
                        "action": "save_category",
                        "data": dataCategory,
                        "nonce": "' . $nonce . '"
                    }, 
                    function(response) {
                        document.documentElement.scrollTop = 0;
                        setInterval(function () {
                            let alert = document.getElementById("alert-post");
                            alert.classList.add("alert-success");
                            alert.style.display = "block";
                            alert.innerHTML = "Save successfully!";
                        }, 3000);
                    }
                );
            });
        });
        </script>';
    }

    public function getCustomCategory()
    {
        global $wpdb;
        $result = $wpdb->get_results("SELECT * FROM wp_category_custom");

        return $result;
    }

    public function getTopGameByCategory()
    {
        global $wpdb;
        $result = $wpdb->get_results("SELECT * FROM wp_top_game_category");

        return $result;
    }

    public function save_category()
    {
        if (!wp_verify_nonce($_REQUEST['nonce'], "get_game_nonce")) {
            exit("Please don't fucking hack this API");
        }

        global $wpdb;
        $data = $_REQUEST['data'];

        foreach ($data as $key => $val) {
            $slug = str_replace("_", "-", $key);
            $getCategory = get_category_by_slug($slug);
            $queryGet = "SELECT * FROM " . $wpdb->prefix . 'category_custom WHERE category_id = "' . $getCategory->term_id . '"';
            $result = $wpdb->query($queryGet);
            if ($result == 0) {
                $query = 'INSERT INTO ' . $wpdb->prefix . 'category_custom (`category_id`, `content`, `title`) VALUES ';
                $query .= ' ("' . $getCategory->term_id . '", "' . htmlentities($val['content']) . '", "' . $val['title'] . '")';
            } else {
                $query = 'UPDATE ' . $wpdb->prefix . 'category_custom';
                $query .= ' SET `content` = "' . htmlentities($val['content']) . '", `title` = "' . $val['title'] . '" WHERE category_id = "' . $getCategory->term_id . '"';
            }

            $wpdb->query($query);
        }

        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $result = json_encode($result);
            echo $result;
        } else {
            header("Location: " . $_SERVER["HTTP_REFERER"]);
        }
    }

    public function save_general()
    {
        if (!wp_verify_nonce($_REQUEST['nonce'], "get_game_nonce")) {
            exit("Please don't fucking hack this API");
        }

        $data = $_REQUEST['data'];
        if (empty($data['h1']) || empty($data['description'])) {
            echo 'failed';
            die;
        }

        $h1Homepage = get_option('h1_homepage');
        $descriptionHomepage = get_option('description_homepage');

        if ($h1Homepage == false) {
            add_option('h1_homepage', $data['h1']);
        } else {
            update_option('h1_homepage', $data['h1']);
        }

        if ($descriptionHomepage == false) {
            add_option('description_homepage', $data['description']);
        } else {
            update_option('description_homepage', $data['description']);
        }

        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            echo 'success';
        } else {
            header("Location: " . $_SERVER["HTTP_REFERER"]);
        }
    }

    public function save_top_category()
    {
        if (!wp_verify_nonce($_REQUEST['nonce'], "get_game_nonce")) {
            exit("Please don't fucking hack this API");
        }

        $data = $_REQUEST['data'];

        if (empty($data)) {
            echo 'failed';
            die;
        }

        $topCategory = get_option('top_category_homepage');
        if ($topCategory == false) {
            $data = json_encode($data);
            add_option('top_category_homepage', $data);
        } else {
            $arrTopCategory = json_decode(json_encode(json_decode($topCategory)), true);
            $merge = array_merge($arrTopCategory, $data);
            update_option('top_category_homepage', json_encode($merge));
        }

        echo 'success';
    }

    public function search_post()
    {
        if (!wp_verify_nonce($_REQUEST['nonce'], "get_game_nonce")) {
            exit("Please don't fucking hack this API");
        }

        $data = $_REQUEST['data'];
        global $wpdb;

        if (empty($data['id']) || empty($data['url'])) {
            echo 'failed';
            die;
        }

        $getPost = url_to_postid($data['url']);
        if ($getPost == 0) {
            echo 'notfound';
            die;
        } else {
            $queryGet = "SELECT * FROM " . $wpdb->prefix . 'top_game_category WHERE category_id = "' . $data['id'] . '"';
            $result = $wpdb->get_results($queryGet);
            if (empty($result)) {
                $dataResponse = [
                    'url' => $data['url'],
                    'id' => $getPost
                ];
            } else {
                $game = $result[0]->game;
                $explode = explode(',', $game);
                if (in_array($getPost, $explode)) {
                    echo 'exists';
                    die;
                } else {
                    $dataResponse = [
                        'url' => $data['url'],
                        'id' => $getPost
                    ];
                }
            }

            $response = json_encode($dataResponse);
            echo $response;
            die;
        }
    }

    public function add_top_game()
    {
        if (!wp_verify_nonce($_REQUEST['nonce'], "get_game_nonce")) {
            exit("Please don't fucking hack this API");
        }

        global $wpdb;
        $data = $_REQUEST['data'];

        if (empty($data)) {
            echo 'failed';
            die;
        }

        $queryGet = "SELECT * FROM " . $wpdb->prefix . 'top_game_category WHERE category_id = "' . $data['category_id'] . '"';
        $result = $wpdb->get_results($queryGet);
        if (empty($result)) {
            $dataSave = $data['id'];
            $query = 'INSERT INTO ' . $wpdb->prefix . 'top_game_category (`category_id`, `game`) VALUES ';
            $query .= ' ("' . $data['category_id'] . '", "' . $dataSave . '")';
        } else {
            $game = $result[0]->game;
            if($game == '') {
                $explode = array();
                $explode[] = $data['id'];
            } else {
                $explode = explode(',', $game);
                $explode[] = $data['id'];
            }
            
            $dataSave = implode(',', $explode);
            $query = 'UPDATE ' . $wpdb->prefix . 'top_game_category';
            $query .= ' SET `category_id` = "' . $data['category_id'] . '", `game` = "' . $dataSave . '" WHERE category_id = "' . $data['category_id'] . '"';
        }

        $wpdb->query($query);

        echo 'success';
        die;
    }

    public function delete_top_game()
    {
        if (!wp_verify_nonce($_REQUEST['nonce'], "get_game_nonce")) {
            exit("Please don't fucking hack this API");
        }

        global $wpdb;
        $data = $_REQUEST['data'];

        if (empty($data)) {
            echo 'failed';
            die;
        }

        $queryGet = "SELECT * FROM " . $wpdb->prefix . 'top_game_category WHERE category_id = "' . $data['category_id'] . '"';
        $result = $wpdb->get_results($queryGet);
        $game = $result[0]->game;
        $explode = explode(',', $game);
        $newGame = array();
        foreach ($explode as $record) {
            if ($record == $data['id']) {
                continue;
            }
            $newGame[] = $record;
        }

        $dataSave = implode(',', $newGame);
        $query = 'UPDATE ' . $wpdb->prefix . 'top_game_category';
        $query .= ' SET `category_id` = "' . $data['category_id'] . '", `game` = "' . $dataSave . '" WHERE category_id = "' . $data['category_id'] . '"';
        $wpdb->query($query);

        echo 'success';
        die;
    }

    public function slugify($text, string $divider = '-')
    {
        $text = preg_replace('~[^\pL\d]+~u', $divider, $text);
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        $text = trim($text, $divider);
        $text = preg_replace('~-+~', $divider, $text);
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }
}
