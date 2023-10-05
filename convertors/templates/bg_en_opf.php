<?xml version="1.0"?><!DOCTYPE package SYSTEM "oeb1.ent">
<package unique-identifier="uid" xmlns:dc="Dublin Core">
    <metadata>
        <dc-metadata>
            <dc:Identifier id="uid">bg_en_dictionary</dc:Identifier>
            <dc:Title><h2>Bulgarian to English Dictionary</h2></dc:Title>
            <dc:Creator>Yanosh Kunsh</dc:Creator>
            <dc:Language>bg-bg</dc:Language>
        </dc-metadata>
        <x-metadata>
            <DictionaryInLanguage>bg-bg</DictionaryInLanguage>
            <DictionaryOutLanguage>en-us</DictionaryOutLanguage>
        </x-metadata>
    </metadata>

    <manifest>
        <item id="cover" href="cover.html" media-type="application/xhtml+xml" />
        <?php for ($i = 0; $i < $manifestItemsCount; ++$i):?>
            <item id="dictionary<?= $i;?>" href="bg_en_dictionary<?= $i;?>.html" media-type="text/x-oeb1-document" />
        <?php endfor;?>
    </manifest>

    <spine>
        <itemref idref="cover" />
        <?php for ($i = 0; $i < $manifestItemsCount; ++$i):?>
            <itemref idref="dictionary<?= $i;?>" />
        <?php endfor;?>
    </spine>

    <tours />

    <guide>
        <reference type="search" title="Dictionary Search" onclick="index_search()"/>
    </guide>
</package>