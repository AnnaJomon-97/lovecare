<!-- START FOOTER -->
<footer class="footer_dark">

    <div class="footer_top">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="widget">
                        <h6 class="widget_title">Contact info</h6>
                        <ul class="contact_info contact_info_light">
                            <li>
                                <i class="ti-location-pin"></i>
                                <p>Fshop</p>
                            </li>
                            <li>
                                <i class="ti-email"></i>
                                <a href="mailto:info@sitename.com">info@fshop.com</a>
                            </li>
                            <li>
                                <i class="ti-mobile"></i>
                                <p>+ 457 789 789 65</p>
                            </li>
                        </ul>
                    </div>
                </div>
                <?php 

                $mnu_cats = $db->get('item_categories');
                $mun_subcats = $db->get('item_subcategories');
                $ar_munsubcats = [];
                foreach ($mun_subcats as $mnu_sbcrow){
                    $cat_id = $mnu_sbcrow['category_id'];
                    $ar_munsubcats[$cat_id][] = $mnu_sbcrow;
                }
                foreach ( $mnu_cats as $mnu_catrow){
                    $cat_id = $mnu_catrow['id'];
                    $mnusubcat2 = isset($ar_munsubcats[$cat_id]) ? $ar_munsubcats[$cat_id] :[];
                ?>
                <div class="col-lg-2 col-md-3 col-sm-6">
                    <div class="widget">
                        <h6 class="widget_title"><?= $mnu_catrow['name'] ?></h6>
                        <ul class="widget_links">
                            <?php foreach ($mnusubcat2 as $mnu_sbc2){?>
                            <li><a href="items.php?sbc=<?= $mnu_sbc2['id'] ?>"><?= $mnu_sbc2['name'] ?></a></li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            <?php } ?>
               
            </div>
        </div>
    </div>
	
    <div class="bottom_footer border-top-tran">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-md-0 text-center text-md-left">© 2020 All Rights Reserved</p>
                </div>
                <div class="col-md-6">
                    <ul class="footer_payment text-center text-lg-right">
                        <li><a href="#"><img src="<?= base_url('assets/web/images/visa.png') ?> " alt="visa"></a></li>
                        <li><a href="#"><img src="<?= base_url('assets/web/images/master_card.png') ?> " alt="visa"></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- END FOOTER -->

<a href="#" class="scrollup" style="display: none;"><i class="ion-ios-arrow-up"></i></a> 
<!-- Latest jQuery --> 
<script src="<?= base_url('assets/web/js/jquery-1.12.4.min.js') ?>"></script> 
<!-- popper min js -->
<script src="<?= base_url('assets/web/js/popper.min.js') ?>"></script>
<!-- Latest compiled and minified Bootstrap --> 
<script src="<?= base_url('assets/web/bootstrap/js/bootstrap.min.js') ?>"></script> 
<!-- owl-carousel min js  --> 
<script src="<?= base_url('assets/web/owlcarousel/js/owl.carousel.min.js') ?>"></script> 
<!-- magnific-popup min js  --> 
<script src="<?= base_url('assets/web/js/magnific-popup.min.js') ?>"></script> 
<!-- waypoints min js  --> 
<script src="<?= base_url('assets/web/js/waypoints.min.js') ?>"></script> 
<!-- parallax js  --> 
<script src="<?= base_url('assets/web/js/parallax.js') ?>"></script> 
<!-- countdown js  --> 
<script src="<?= base_url('assets/web/js/jquery.countdown.min.js') ?>"></script> 
<!-- imagesloaded js --> 
<script src="<?= base_url('assets/web/js/imagesloaded.pkgd.min.js') ?>"></script>
<!-- isotope min js --> 
<script src="<?= base_url('assets/web/js/isotope.min.js') ?>"></script>
<!-- jquery.dd.min js -->
<script src="<?= base_url('assets/web/js/jquery.dd.min.js') ?>"></script>
<!-- slick js -->
<script src="<?= base_url('assets/web/js/slick.min.js') ?>"></script>
<!-- elevatezoom js -->
<script src="<?= base_url('assets/web/js/jquery.elevatezoom.js') ?>"></script>
<!-- scripts js --> 
<script src="<?= base_url('assets/web/js/scripts.js') ?>"></script>

</body>
</html>