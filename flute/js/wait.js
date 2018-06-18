<!-- preloader -->
    <style type="text/css">
        .preloader_bg { position: fixed; background: rgba(15,15,15,1); width: 100%; height: 100%; top: 0; left: 0; z-index: 200; }
        .preloader_content { position: fixed; left: 50%; top: 50%; transform: translate(-50%,-50%); z-index: 201; font-size: 14px; }
        .preloader_content span { display: block; margin: auto; text-align: center; text-transform: uppercase; color: rgba(225,225,225,1);}
    </style>
    <script type="text/javascript">
    $(function(){
        $('.preloader_bg, .preloader_content').fadeIn(0);
        $(window).load(function(){
            $('.preloader_bg').delay(250).fadeOut(1500);
            $('.preloader_content').delay(250).fadeOut(750);
        });
    });
    </script>
    <div class="preloader_bg"></div>
    <div class="preloader_content">
        <span>Идет загрузка...<br>Подождите...</span>
    </div>
    <noscript>
        <style>
            html, body { opacity: 1 !important; }
            .preloader_bg, .preloader_content { display: none !important; }
        </style>
    </noscript>
    <!-- /preloader -->