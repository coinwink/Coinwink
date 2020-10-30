<?php get_header(); ?>

<div style="position:relative;max-width:800px;margin:0 auto;" class="outer-buttons">

    <div style="position:absolute;top:20px;right:15px;padding-left:2px;width:26px;">
        <a href="<?php echo site_url(); ?>">
            <svg id="icon-home" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 588.83 524.89">
                <title>Home</title>
                <?php echo file_get_contents(get_stylesheet_directory_uri() . "/img/icon-home.svg"); ?>
            </svg>
        </a>
    </div>

</div>


<div style="text-align:center;height:120px;">
    <div style="height:27px;"></div>
    <div id="logo" style="width:44px;-webkit-filter: drop-shadow(0 1px 1px rgba(0,0,0,.7));filter: drop-shadow(0 1px 1px rgba(0,0,0,.7));">
        <a href="<?php echo site_url(); ?>">
            <img src="https://coinwink.com/img/coinwink-crypto-alerts-logo.png" width="44" alt="Coinwink Crypto Alerts">
        </a>
    </div>
	<div id="txtlogo"><a href="<?php echo site_url(); ?>">Coinwink</a></div>
</div>


<div class="container" id="account">


    <header>
        <h2 class="text-header" style="color:white;">404 Error</h2>
    </header>

    <div class="content">

        <div style="font-size:18px;line-height:140%;">
        
            <div style="height:21px;"></div>

            This page doesn't exist.
            
            <div style="height:21px;"></div>

        </div>

    </div>


</div>


<div style="height:10px;"></div>

<?php echo do_shortcode('[footer_shortcode]'); ?>


</body>
</html>