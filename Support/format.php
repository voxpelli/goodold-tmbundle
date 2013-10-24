<?php
function goNormalizeIndentation($indentation) {
	$softTabs = (getenv('TM_SOFT_TABS') == 'YES');
	$tabSize = intval(getenv('TM_TAB_SIZE'));
	$indentLevel = floor(strlen($indentation[0]) / 2);
	$oddIndentation = strlen($indentation[0]) % 2 !== 0;
	return ($softTabs ? str_pad('', $tabSize * $indentLevel) : str_pad('', $indentLevel, "\t")) . ($oddIndentation ? ' ' : '');
}

function goFormatVarExport($string, $input = null) {
	$result = preg_replace_callback('/^([ ]+)/im', 'goNormalizeIndentation', preg_replace(array(
		'/=>\s+array\(/im',
		'/array\(\s+\)/im',
	), array(
		'=> array(',
		'array()',
	), str_replace('array (', 'array(', $string)));

	if ($input) {
		$matches = array();
		preg_match('/^(\s*)[^\s]/', $input, $matches);
		if (isset($matches[1])) {
			$lines = explode("\n", $result);
			foreach ($lines as $key => $value) {
				$lines[$key] = $matches[1] . $lines[$key];
			}
			$result = implode("\n", $lines);
		}
	}

	return $result;
}
