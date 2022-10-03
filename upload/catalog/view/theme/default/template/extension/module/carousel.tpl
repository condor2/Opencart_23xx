<div class="carousel swiper-viewport">
  <div id="carousel<?php echo $module; ?>" class="swiper-container">
    <div class="swiper-wrapper">
      <?php foreach ($banners as $banner) { ?>
        <div class="swiper-slide text-center">
		  <?php if ($banner['link']) { ?>
            <a href="<?php echo $banner['link']; ?>"><img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" class="img-responsive" /></a>
		  <?php } else { ?>
            <img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" class="img-responsive" />
		  <?php } ?>
        </div>
      <?php } ?>
        </div>
    </div>
  <div class="swiper-pagination carousel<?php echo $module; ?>"></div>
  <div class="swiper-pager">
    <div class="swiper-button-next"></div>
    <div class="swiper-button-prev"></div>
  </div>
</div>
<script type="text/javascript"><!--
$('#carousel<?php echo $module; ?>').swiper({
	mode: 'horizontal',
	slidesPerView: 5,
	pagination: '.carousel<?php echo $module; ?>',
	paginationClickable: true,
	nextButton: '.carousel .swiper-button-next',
    prevButton: '.carousel .swiper-button-prev',
	autoplay: $('#carousel<?php echo $module; ?> img').length > 5 ? 2500 : 0,
	loop: true
});
--></script>