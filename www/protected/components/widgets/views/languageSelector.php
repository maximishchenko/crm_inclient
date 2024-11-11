<a class="active <?php echo $currentLang; ?>" href="#"><?php echo $currentLang; ?></a>
<div class="sub">
	<div class="arrow"></div>
	<ul>
		<?php
		foreach ($languages as $key => $lang) {
			if ($key != $currentLang) {
				echo '<li>' . CHtml::link('<img src="/images/' . $key . '.png" alt=""/>' . $lang,
						$this->getOwner()->createMultilanguageReturnUrl($key)) . '</li>';
			}
		}
		?>
	</ul>
</div>