<?php
/**
 * Loop item template
 */
?>
<figure class="lastudio-banner lastudio-effect-<?php $this->__html( 'animation_effect', '%s' ); ?>"><?php
	$target = $this->__get_html( 'banner_link_target', ' target="%s"' );
		echo '<div class="lastudio-banner__overlay"></div>';
		echo $this->__get_banner_image();
		echo '<figcaption class="lastudio-banner__content">';
			echo '<div class="lastudio-banner__content-wrap">';
				$title_tag = $this->__get_html( 'banner_title_html_tag', '%s' );

				$this->__html( 'banner_title', '<' . $title_tag  . ' class="lastudio-banner__title">%s</' . $title_tag  . '>' );
				$this->__html( 'banner_text', '<div class="lastudio-banner__text">%s</div>' );
			echo '</div>';
            $this->__html( 'banner_link', '<a href="%s" class="lastudio-banner__link"' . $target . '>' );
            $this->__html( 'banner_title', '%s' );
            $this->__html( 'banner_link', '</a>' );
		echo '</figcaption>';
?></figure>