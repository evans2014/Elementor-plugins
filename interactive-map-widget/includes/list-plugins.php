<style>
    /* Ocultar aviso de WP */
    .notice:not(.onepage),
    .button-primary.woocommerce-save-button {
        display: none;
    }

    /* Ocultar botón de pestaña por defecto de WooCommerce */
    .container {
        font-family: "Avenir Next Font", sans-serif;
        max-width: 600px;
        margin: 20px;
        padding: 20px;
        padding-left: 32px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        background: white;
    }
    
    .container p {
        max-width: 74ch;
    }

    .form-group {
        margin-bottom: 20px;
    }

    label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }

    select {
        width: 100%;
        padding: 8px;
        margin-bottom: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }

    button {
        background-color: #007bff;
        color: white;
        padding: 10px 15px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    button:hover {
        background-color: #0056b3;
    }

    button:disabled:not(#btn-restore) {
        background-color: #0056b3;
    }
    #btn-restore {
        margin-left: 1.2em;
        color: #007bff;
        background: white;
        border: 1px solid #007bff;
        padding: 10px 15px;
        border-radius: 5px;
        cursor: pointer;
    }
    #btn-restore:disabled,
    #btn-restore:hover {
        background-color: #efefef;
    }

    p {
        margin-bottom: 20px;
    }

    @keyframes spinner {
        to {
            transform: rotate(360deg);
        }
    }

    #spinner::before,
    #spinner2::before {
        display: inline-block;
        content: '';
        box-sizing: border-box;
        position: relative;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        border: 2px solid #ccc;
        border-top-color: #333;
        animation: spinner .6s linear infinite;
        visibility: hidden;
        margin-left: 10px;
        translate: 0px 4px;
    }

    #btn-overwrite:disabled+#spinner::before {
        visibility: visible;
    }
    #btn-restore:disabled+#spinner2::before {
        visibility: visible;
    }

    .onepage.notice {
        position: relative;
        max-width: 600px;
        margin: 20px;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
    }

    .onepage.notice p {
        margin: 0;
        flex-grow: 1;
    }

    .onepage.notice.success {
        background-color: #dff0d8;
        border-color: #d6e9c6;
        color: #3c763d;
    }

    .onepage.notice.error {
        background-color: #f2dede;
        border-color: #ebccd1;
        color: #a94442;
    }

    .close-btn {
        cursor: pointer;
        position: absolute;
        top: 10px;
        right: 10px;
        font-size: 20px;
    }

    .onepage__tab-container {
        font-family: Arial, sans-serif;
        font-family: "Avenir Next Font", sans-serif;
    }

    .onepage__tab-nav {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        background: #DCDCDE;
        border-radius: 5px;
        border-bottom-left-radius: 0px;
        border-bottom-right-radius: 0px;
        font-weight: 700;
        height: 46px;
        border-bottom: 1px solid hsl(240 1% 90% / 1);
        background: hsl(0, 0.00%, 98.00%);
    }

    .onepage__tab-nav img {
        height: 36px;
        object-fit: cover;
        width: 31px;
        object-position: left;
        margin-inline: 32px;
        margin-top: 4px;
        margin-left: 24px;
    }

    .onepage__tab-item {
        padding: 10px 32px;
        margin-right: 8px;
        cursor: pointer;
        transition: background-color 0.3s ease;
        color: hsl(0 0% 38% / 1);
        margin-bottom: 0px !important;
        align-content: center;  
    }

    .onepage__tab-item span {
        transform: translate(0px, 1.5px) !important;
        display: inline-block;
    }

    .onepage__tab-item:first-child {
        border-top-left-radius: 5px;
    }

    .onepage__tab-item:hover,
    .onepage__tab-item.active {
        color: hsl(0 0% 27% / 1);
    }

    .onepage__tab-item.active {
        box-shadow: 0 -4px 0 0 #fe4d38 inset;
    }

    .onepage__tab-content {
        display: none;
        margin-top: 0px;
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 5px;
        border-top: 0px;
        border-top-left-radius: 0px;
        border-top-right-radius: 0px;
    }

    .onepage__tab-content#tab3 {
        padding: 0px;
    }

    #wpcontent :has(.onepage__tab-content.active#tab3) {
        background: white;
    }

    .onepage__tab-content.active {
        display: block;
    }

    .container,
    .nahiro-box {
        max-width: 1100px;
    }

    .nahiro-box {
        font-family: "Avenir Next Font", sans-serif;
        margin: 0 auto;
        border-radius: 15px;
        padding: 1rem;
        margin-top: 1rem;
        margin-bottom: 1rem;
        box-shadow: 0px 1px 2px rgb(23 57 97 / 30%);
    }

    .nahiro-box h4 {
        font-weight: 800;
        font-size: 1.5rem !important;
        line-height: normal;
    }

    .nahiro-box-content {
        display: flex; 
        justify-content: center;
    }

    .nahiro-box-img {
        max-width: 100%;
    }

    .t-center {
        text-align: center;
    }

    #onepage_license_key {
        padding-block: 5px !important;
        padding-inline: 20px;
        min-width: 30ch;
        font-size: 1.35em;
        margin-right: 8px;
        border-radius: 12px;
    }

    input.button-primary.btn-license {
        padding-block: 3.75px !important;
        padding-inline: 18px !important;        
        font-size: 1.35em !important;
        border-radius: 12px !important;
        translate: 0px -1px;
    }

    .nh_green_badge {
        background: #0bac67;
        border-radius: 15px;
        color: white;
        font-family: sans-serif;
        padding: 2px 12px;
    }

    .nh_license_title {
        margin-bottom: 32px;
        font-family: "Avenir Next Font";
    }

    .nh_license_dates {
        margin-bottom: 16px;
        display: flex;
        align-items: center;
    }

    .nh_license_dates .dashicons {
        width: 25px;
        height: 23px;
        font-size: 24px;
    }

    .grid-container {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        column-gap: 1rem;
        padding: 1rem;
        padding: 1rem 0;
    }

    @media screen and (min-width: 1660px) {
        .grid-container {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media screen and (min-width: 2410px) {
        .grid-container {
            grid-template-columns: repeat(4, 1fr);
        }
    }

    .nahiro-box {
        font-family: "Avenir Next Font", sans-serif;
        background-color: #ffffff;
        border-radius: 15px;
        padding: 1rem;
        box-shadow: 0px 1px 2px rgba(23, 57, 97, 0.3);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .nahiro-box .addon-title {
        font-size: 1.1rem;
        font-weight: 500;
        margin-bottom: 1rem;
    }

    .nahiro-box a.addon-action {
        display: inline-block;
        text-align: center;
        background-color: #0073aa;
        color: #ffffff;
        padding: 6px 14px;
        border-radius: 5px;
        text-decoration: none;
        transition: background-color 0.3s ease;
        cursor: pointer;
        font-size: 14px;
        letter-spacing: 0.15px;
        background-color: hsl(233, 58%,37%);
        background-color: #ff350c;
        border: 1px solid  #ff350c;
        transition: all .3s;
        font-weight: bold;




    }

    .nahiro-box a.addon-action:hover {
        background-color: #005177;
        border: 1px solid  #ff350c;
        background-color: white;
        color: #ff350c;


    }

    /* .nahiro-box a.addon-action.pro {
        background-color: hsl(233, 58%,37%);
    }

    .nahiro-box a.addon-action.pro:hover {
        background-color:  hsl(233, 81%,20%);
    } */
    .nahiro-box a.addon-action.Deactivate {
        background-color:hsl(354, 70.50%, 53.50%);
    }

    .nahiro-box a.addon-action.Deactivate:hover {
        background-color:hsl(354, 70.50%, 37.50%);
    }

    .nh_license_title {
        display: flex;
        align-items: center;
        gap: 16px;
        justify-content: center;
    }

    .nh_license_row:first-child {
        border-top: 1px solid lightgray;
        margin-top: 32px;
    }

    .nh_license_row:last-child {
        margin-bottom: 32px;
    }

    .nh_license_row {
        border-bottom: 1px solid lightgray;
        padding-block: 19px;
    }

    .nh_license_row .license-number {
        font-family: monospace;
    }

    .nh_license_row span {
        font-size: 14px;
    }

    .nh_license_row span:first-child {
        position: relative;
        display: inline-block;
        width: 125px;
        margin-right: 16px;
    }

    .nh_license_row span:first-child::after {
        content: ':';
        position: absolute;
        right: 0;
    }

    #wpcontent {
        padding-left: 0 !important;
    }

    .onepage__tab-content {
        border: 0px;
    }

    .nahiro-box.aside {
        padding-block: 16px;
        padding-bottom: 27px;
    }

    .nahiro-box {
        padding: 38px;
        border: 1px solid hsl(0 0% 95% / 1);
    }

    .nahiro-box .addon-title {
        font-size: 1rem;
        font-weight: 700;
        padding-top: 6px;
    }

    .nahiro-box .nh-container {
        display: flex;
        gap: 32px;
    }

    .nahiro-box .addon-img {
        height: 80px;
        width: 96px;
        object-fit: cover;
        border-radius: 8px;
        /* border: 1px solid lightgray; */
    }

    .nahiro-box a.addon-page {
        font-size: 15px;
        margin-left: 16px;
        text-underline-offset: 2px;
    }

    .nahiro-box a.addon-page:hover {
        color: hsl(207 68% 11% / 1);
    }

    .nahiro-box a.addon-action.Activate ~ .addon-page {
        pointer-events: none;
        color: hsl(0 0% 72% / 1);
    }

    .nahiro-box .support-text {
        bottom: 0;
    }

    .nahiro-box .nh-desc {
        font-size: 16px;
        font-family: "Avenir Next Font";
        font-weight: 500;
    }

    .nh-plugins {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 8px;
        transform: translateX(-2%);
        font-weight: 900;
    }

    .nh-plugins .dashicons {
        width: 25px;
        height: 25px;
        font-size: 24px;
    }

    .nahiro-box.free .addon-img {
        object-fit: contain;
    }
    .nahiro-box.free .addon-title {
        display: -webkit-box;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 1;
        overflow: hidden;
    }
    .nahiro-box.pro .addon-img {
        object-fit: contain;
    }

    principal {
        display: grid;
        grid-template-columns: repeat(4, 9.75fr) 11fr;
        grid-template-rows: 1fr;
        grid-column-gap: 35px;
        grid-row-gap: 0px;
    }

    plugins ~ div {
        grid-area: 1 / 5 / 2 / 6;
    }

    plugins {
        grid-area: 1 / 1 / 2 / 5;
    }

    @media screen and (max-width: 800px) {
        principal {
            display: block !important;
        }

        .grid-container {
            grid-template-columns: 1fr;
        }

        .nahiro-box {
            min-width: 100px !important;
        }
    }
    @media screen and (min-width: 2120px) {
        .nahiro-box {
            min-width: 400px ;
        }
    }
    .support-text{
        font-weight: bold;
    }
    .support-text :is(a){
        font-weight: 900;
    }
</style>

<div class="onepage__tab-container">
    <!-- NAV TABS -->
    <ul class="onepage__tab-nav">
        <img src="<?php echo plugin_dir_url(__DIR__)?>assets/img/logo-h.png" alt="">
        <li class="onepage__tab-item active" data-tab="tab1"><i class="dashicons dashicons-admin-tools"></i> <span><?php _e('Plugins', 'interactive-map-widget'); ?></span></li>
    </ul>
    <!-- END NAV TABS -->
    <!-- Settings Content -->
    <div class="onepage__tab-content active" id="tab1">
        <principal>


            <plugins>
                <!-- grid container -->
                <h1 style="text-align: center; margin-top: 64px;" class="nh-plugins"> 
                    <span class="dashicons dashicons-wordpress-alt"></span>
                    <span><?php echo __('Free Plugins', 'interactive-map-widget') ?> </span>
                </h1>
                <div class="grid-container">
                                    
                        <?php 
                        $plugins = [
                            [
                                'name' => 'Store Locations Map',
                                'slug' => 'store-locations-map',
                                'file' => 'store-locations-map/store-locations-map.php',
                                'img' => 'https://ps.w.org/store-locations-map/assets/icon-256x256.png',
                                'custom_style' => ''
                            ],
                            [
                                'name' => 'Videos on Admin Dashboard',
                                'slug' => 'videos-on-admin-dashboard',
                                'file' => 'videos-on-admin-dashboard/video-on-admin-dashboard.php',
                                'img' => 'https://ps.w.org/videos-on-admin-dashboard/assets/icon-256x256.png',
                                'custom_style' => 'width: 80px;'
                            ]
                        ];

                        foreach ($plugins as $plugin) {
                            $is_active = is_plugin_active($plugin['file']);
                            $action = $is_active ? 'deactivate' : 'activate';
                            $label = $is_active ? __('Deactivate', 'interactive-map-widget') : __('Activate', 'interactive-map-widget');
                            $url = wp_nonce_url(
                                add_query_arg(
                                    [
                                        'action' => $action,
                                        'plugin' => $plugin['file']
                                    ],
                                    admin_url('plugins.php')
                                ),
                                $action . '-plugin_' . $plugin['file']
                            );
                            $install_url = wp_nonce_url(
                                add_query_arg(
                                    [
                                        'action' => 'install-plugin',
                                        'plugin' => $plugin['slug']
                                    ],
                                    admin_url('update.php')
                                ),
                                'install-plugin_' . $plugin['slug']
                            );
                            $details_url = admin_url('plugin-install.php?tab=plugin-information&plugin=' . $plugin['slug'] . '&TB_iframe=true&width=600&height=550');
                            ?>
                            <div class="nahiro-box free" style="filter:grayscale(0)">
                                <div class="nh-container">
                                    <div class="col1">
                                        <img class="addon-img" src="<?php echo esc_url($plugin['img']); ?>" style="<?php echo esc_attr($plugin['custom_style']); ?>">
                                    </div>
                                    <div class="col2">
                                        <div class="addon-title"><?php echo esc_html($plugin['name']); ?></div>
                                        <?php if ($is_active): ?>
                                            <a class="addon-action Deactivate" href="<?php echo esc_url($url); ?>"><?php echo $label; ?></a>
                                        <?php elseif (file_exists(WP_PLUGIN_DIR . '/' . $plugin['file'])): ?>
                                            <a class="addon-action Activate" href="<?php echo esc_url($url); ?>"><?php echo $label; ?></a>
                                        <?php else: ?>
                                            <a class="addon-action Install" href="<?php echo esc_url($install_url); ?>"><?php echo __('Install', 'interactive-map-widget'); ?></a>
                                        <?php endif; ?>
                                        <a class="addon-page thickbox open-plugin-details-modal" href="<?php echo esc_url($details_url); ?>"><?php echo __('Details', 'interactive-map-widget'); ?></a>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                        ?>

                        
                    <?php //endforeach; ?>
                </div>
            
                <!-- PRO PLUGINS -->
                <h1 style="text-align: center; margin-top: 64px;" class="nh-plugins"> 
                    <span class="dashicons dashicons-cart"></span><span><?php echo __('Pro Plugins', 'interactive-map-widget') ?> </span>
                </h1>

                <div class="grid-container">
                    <?php
                        $plugins = [
                            [
                                'name' => __('Interactive Map for Elementor Pro', 'interactive-map-widget'),
                                'img' => plugin_dir_url(__DIR__) . 'assets/img/pro/imap.png',
                                'url' => __('https://nahiro.net/en/interactive-map-for-elementor-pro/', 'interactive-map-widget')
                            ],
                            [
                                'name' => __('Wordpress Store Locator', 'interactive-map-widget'),
                                'img' => plugin_dir_url(__DIR__) . 'assets/img/pro/smap.png',
                                'url' => __('https://nahiro.net/en/wordpress-store-locator/', 'interactive-map-widget')
                            ],
                            [
                                'name' => __('Videos on Admin Dashboard Pro', 'interactive-map-widget'),
                                'img' => plugin_dir_url(__DIR__) . 'assets/img/pro/video.png',
                                'url' => __('https://nahiro.net/en/videos-on-admin-dashboard/', 'interactive-map-widget')
                            ],
                            [
                                'name' => __('One Page Checkout Pro', 'interactive-map-widget'),
                                'img' => plugin_dir_url(__DIR__) . 'assets/img/pro/chk.png',
                                'url' => __('https://nahiro.net/en/elementor-woocommerce-one-page-checkout/', 'interactive-map-widget')
                            ]
                        ];
                    ?>
                    <?php foreach ($plugins as $plugin): ?>
                        <div class="nahiro-box pro" style="filter:grayscale(0)">
                            <div class="nh-container">
                                <div class="col1">
                                    <img class="addon-img" src="<?php echo $plugin['img']; //url?>" style="border:0px solid;height:92px;XXfilter: hue-rotate(233deg) saturate(0.9);">
                                </div>
                                <div class="col2">
                                    <div class="addon-title"><?php echo $plugin['name'];?></div>
                                    <a class="addon-action pro" href="<?php echo $plugin['url'];?>"><?php _e('More information','') ?></a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
            </plugins>




            <!-- ASIDE -->
            <div class="nahiro-box aside" style="display: block; background-color: #fff; min-width: 324px; margin-top: 60px;    max-height: fit-content;">
                <div style="margin-left: 1rem; margin-right: 25px; position:relative">
                
                    <h4 class="nh_license_title">
                        <!-- <span class="dashicons dashicons-admin-network"></span>  -->
                        <?php _e('WordPress Support Berlin', 'interactive-map-widget'); ?>
                    </h4>

                    <?php 
                        $format = 'd-m-Y';
                        if($lang === 'es' || $lang == 'en'){
                            $format = 'Y-m-d';
                        }
                    ?>
                    <p class="nh-desc">
                        <?php echo __("Do you have issues with your WordPress website? In an emergency, we are here to assist you and help with error resolution, customization, expansions, troubleshooting, maintenance, optimization, or programming—professionally and exactly according to your needs.




","interactive-map-widget")?>
                    </p>
                    
                    <p class="support-text"><?php _e('Having any trouble? Don’t worry as you can reach out to our expert Support team any time.', 'interactive-map-widget'); ?> 
                    <?php _e('<a href="https://nahiro.net/en/#formulare">Contact us</a>', 'interactive-map-widget'); ?> </p>
                </div>

                <div style="display: flex; justify-content: center" >
                    <!-- <img src="<?php echo plugin_dir_url(__DIR__)?>/assets/img/plugin-dev.png" class="nahiro-box-img" height="180"> -->
                </div>

            <!-- end nahiro-box  -->
            </div>

            
            

        </principal>

        <!--box2-->
        <div class="container nahiro-box" style="display:none">
            <h4 class="t-center"><?php _e('Enable One Page Checkout', 'interactive-map-widget'); ?></h4>
            <div class="nahiro-box-content">
                <img style="width: 25%" src="" class="voad_pro_img">
            </div>
            <h4 style="text-align: center; font-weight: 500;"><?php _e('Create unlimited videos and widgets on the dashboard.', 'interactive-map-widget'); ?></h4>
        </div>
        <!-- for appending notices -->
        <div id="notices-container"></div>
    </div>
    <!-- Content 2 -->
    <div class="onepage__tab-content" id="tab2"><?php _e('Content tab 2', 'interactive-map-widget'); ?></div>
    <!-- ABOUT NAHIRO Content -->
    <!-- <div class="onepage__tab-content" id="tab3"><?php require_once plugin_dir_path(__FILE__) . 'about-nahironet.php';?></div> -->
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var tabs = document.querySelectorAll('.onepage__tab-item');
        var contents = document.querySelectorAll('.onepage__tab-content');

        tabs.forEach(tab => {
            tab.addEventListener('click', function () {
                var target = document.querySelector('#' + this.getAttribute('data-tab'));

                // Eliminar la clase activa de todas las pestañas y contenidos
                tabs.forEach(t => t.classList.remove('active'));
                contents.forEach(c => c.classList.remove('active'));

                // Añadir la clase activa a la pestaña clicada y al contenido correspondiente
                tab.classList.add('active');
                target.classList.add('active');
            });
        });

        // Verificar si hay una pestaña almacenada en localStorage y activarla
        // var savedTab = localStorage.getItem('currentTab');
        // if (savedTab) {
        //     document.querySelector('.tab-item[data-tab="' + savedTab + '"]').click();
        // } else {
        //     // Por defecto a la primera pestaña si no hay pestaña guardada
        //     tabs[0].click();
        // }
    });
</script>
<?php
//}
?>
