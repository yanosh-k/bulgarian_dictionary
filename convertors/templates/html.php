<?xml version="1.0" encoding="utf-8"?>
<html xmlns:idx="www.mobipocket.com" xmlns:mbp="www.mobipocket.com" xmlns:xlink="http://www.w3.org/1999/xlink">
    <body>
    <mbp:frameset>

<?php foreach ($words as $wordItem): ?>
	<idx:entry name="headword" scriptable="yes">
		<a id="<?= $wordItem['id']; ?>"></a>
		<h2>
			<idx:orth value="<?= $wordItem['base_word']; ?>"><?= $wordItem['base_word']; ?>

				<?php if ($wordItem['derivative_forms']): ?>
					
					<idx:infl>
	<?php foreach ($wordItem['derivative_forms'] as $derivativeForm): ?>
	<idx:iform value="<?= $derivativeForm; ?>"></idx:iform>
	<?php endforeach; ?>
					</idx:infl>
				<?php endif; ?>
				
			</idx:orth>
		</h2>
		<?= $wordItem['meaning']; ?>

	</idx:entry>
<?php endforeach; ?>

    </mbp:frameset>
</body>
</html>