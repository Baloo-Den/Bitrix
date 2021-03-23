<?php
$xml = simplexml_load_file("http://radostipark.pr-ff.ru/test_map/xml/data.xml"); 
$ravn = iconv("Windows-1251", "UTF-8", "равнина");
echo '<?xml version="1.0" encoding="utf-8"?>
<data>
	<dp4>
		<t>Детская площадка</t>
		<img>./test_map/dp.jpg</img>
	</dp4>
	<sp2>
		<t>Спортивная площадка</t>
		<img>./test_map/sp.jpg</img>
	</sp2>
	<sp3>
		<t>Спортивная площадка</t>
		<img>./test_map/sp.jpg</img>
	</sp3>
	<sp4>
		<t>Спортивная площадка</t>
		<img>./test_map/sp.jpg</img>
	</sp4>';
$i = 244; 
while ($i < 531)
{
	$xmlNum = $i-1; 
	$xml_element = $xml->d->$xmlNum; 
	echo "
			<d>
				<n>$i</n>
				<s>$xml_element->s</s>
				<c>$xml_element->c</c>
				<cn>$xml_element->cn</cn>
				<cnd>$xml_element->cnd</cnd>
				<st>$xml_element->st</st>
				<sz>20x30</sz>
				<rf>$ravn</rf>
				<a>http://www.google.com/</a>
				<cnt>$xml_element->cnt</cnt>
			</d>\n";
	$i++;
}
echo '</data>';
?>
