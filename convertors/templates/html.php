<html 
	xmlns:math="http://exslt.org/math" xmlns:svg="http://www.w3.org/2000/svg"
	xmlns:tl="https://kindlegen.s3.amazonaws.com/AmazonKindlePublishingGuidelines.pdf"
	xmlns:saxon="http://saxon.sf.net/" xmlns:xs="http://www.w3.org/2001/XMLSchema"
	xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
	xmlns:cx="https://kindlegen.s3.amazonaws.com/AmazonKindlePublishingGuidelines.pdf"
	xmlns:dc="http://purl.org/dc/elements/1.1/"
	xmlns:mbp="https://kindlegen.s3.amazonaws.com/AmazonKindlePublishingGuidelines.pdf"
	xmlns:mmc="https://kindlegen.s3.amazonaws.com/AmazonKindlePublishingGuidelines.pdf"
	xmlns:idx="https://kindlegen.s3.amazonaws.com/AmazonKindlePublishingGuidelines.pdf">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	</head>

	<body>
		<mbp:frameset>
		
			<?php foreach($words as $wordItem): ?>
				<idx:entry name="headword" scriptable="yes">
					<a id="<?= $wordItem['id']; ?>"></a>
						<idx:orth value="<?= $wordItem['base_word']; ?>"><h2><?= $wordItem['base_word']; ?></h2>
							<?php if ($wordItem['derivative_forms']): ?>
								<idx:infl>
									<?php foreach ($wordItem['derivative_forms'] as $derivativeForm): ?>
										<idx:iform value="<?= $derivativeForm; ?>"></idx:iform>
									<?php endforeach; ?>
								</idx:infl>
							<?php endif; ?>
						</idx:orth>
					    <?= $wordItem['meaning']; ?>
				</idx:entry>
				<hr/>
			<?php endforeach; ?>
		
		</mbp:frameset>
	</body>
</html> 