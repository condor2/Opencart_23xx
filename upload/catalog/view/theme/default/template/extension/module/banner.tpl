<div class="swiper-viewport">
  <div id="banner<?php echo $module; ?>" class="swiper-container">
    <div class="swiper-wrapper">
      <?php foreach ($banners as $banner) { ?>
        <div class="swiper-slide">
		  <?php if ($banner['link']) { ?>
            <a href="<?php echo $banner['link']; ?>"><img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" class="img-responsive" /></a>
		  <?php } else { ?>
            <img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" class="img-responsive" />
		  <?php } ?>
		</div>
      <?php } ?>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
$('#banner<?php echo $module; ?>').swiper({
	effect: 'fade',
	autoplay: 2500,
    autoplayDisableOnInteraction: false
});
--></script>