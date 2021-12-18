<?xml version="1.0"?>
<package version="2.0" xmlns="http://www.idpf.org/2007/opf" unique-identifier="uid">
  <metadata>
	<meta name="cover" content="my-cover-image" />
	<dc-metadata>
		<dc:Identifier id="uid">bulgarian_dictionary</dc:Identifier>
		<dc:title>Bulgarian Dictionary</dc:title>
		<dc:creator opf:role="aut">Yanosh Kunsh</dc:creator>
		<dc:language>BG</dc:language>
	</dc-metadata>
    <x-metadata>
      <DictionaryInLanguage>bg</DictionaryInLanguage>
      <DictionaryOutLanguage>bg</DictionaryOutLanguage>
      <DefaultLookupIndex>headword</DefaultLookupIndex>
    </x-metadata>
  </metadata>
  
  <manifest>
	<item id="cover" href="cover.html" media-type="application/xhtml+xml" />
	<?php for ($i = 0; $i < $manifestItemsCount; ++$i): ?>
		<item id="dictionary<?= $i; ?>" href="bulgarian_dictionary<?= $i; ?>.html" media-type="application/xhtml+xml" />
	<?php endfor; ?>
  </manifest>
  
  <spine>
	<itemref idref="cover" />
	<?php for ($i = 0; $i < $manifestItemsCount; ++$i): ?>
		<itemref idref="dictionary<?= $i; ?>" />
	<?php endfor; ?>
  </spine>
  
  <guide>
	<?php for ($i = 0; $i < $manifestItemsCount; ++$i): ?>
		<reference type="index" title="Search Word" href=""bulgarian_dictionary<?= $i; ?>.html"/>
	<?php endfor; ?>
  </guide>

</package>