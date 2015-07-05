<?php
defined('security') or die('Direct Access to this location is not allowed.');

interface RGenerator {
	public function generate(Channel $channel);
	public function generatorName();
}
?>