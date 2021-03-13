<?php
/**
 * Loop item template
 */
?>
<div class="lastudio-carousel__item<?php echo $this->__loop_item(array('item_css_class'), ' %s') ?>">
    <div class="lastudio-carousel__item-inner">
        <figure class="lastudio-banner lastudio-effect-<?php echo esc_attr($this->get_settings_for_display('animation_effect')); ?>"><?php
            $target = $this->__loop_item(array('item_link_target'), ' target="%s"');
            echo '<div class="lastudio-banner__overlay"></div>';
            echo $this->get_advanced_carousel_img('lastudio-banner__img');
            echo '<figcaption class="lastudio-banner__content">';
            echo '<div class="lastudio-banner__content-wrap">';
            echo $this->__loop_item(array('item_title'), '<h5 class="lastudio-banner__title">%s</h5>');
            echo $this->__loop_item(array('item_text'), '<div class="lastudio-banner__text">%s</div>');
            echo '</div>';
            echo $this->__loop_item(array('item_link'), '<a href="%s" class="lastudio-banner__link"' . $target . '>');
            echo $this->__loop_item(array('item_title'), '%s');
            echo $this->__loop_item(array('item_link'), '</a>');
            echo '</figcaption>';
        ?></figure>
    </div>
</div>