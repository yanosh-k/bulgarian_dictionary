# Български тълковен речник за Kindle

Български тълковен речник в .mobi формат подходящ за Kindle устройства. Речника е съставен на основата на базата данни на ["Речко"](https://rechnik.chitanka.info/about).

# Инсталация

1. Вържете вашия Kindle към компютъра чрез USB кабел
2. Отворете папката `documents`, която се намира на устройството
3. Поставете файла `bulgarian_dictionary.mobi` в папката `dictionaries`
4. Разкачете устройството от компютъра

При правилна инсталация речника ще се появи в списъка с налични речници в менюто `Menu > Settings > Device Options > Language and Dictionaries > Dictionaries`.

# Как мога сам да генерирам речника от изходната база данни на "Речко"?

1. Изтегляте dump от базата данни на речника от [тук](https://rechnik.chitanka.info/db.sql.gz)
2. Импортирате в локална mysql база данни
3. Създавате файл в Stardict формат (`<header>\t<definition>`, вижте bulgarian_dictionary.txt за пример). Аз използвам функцията за експорт на [HeidiSQL](http://www.heidisql.com/), като заявката с коят извличам данните е следната:
```
SELECT
    word.name
    , IF (
        word.synonyms IS NOT NULL AND word.synonyms != ''
        , CONCAT(REPLACE(word.meaning, "\n", "<br />"), "<br />Синоними: ", REPLACE(word.synonyms, "\n", ", "))
        , REPLACE(word.meaning, "\n", "<br />")
    )
FROM
    word
WHERE
    word.meaning IS NOT NULL
    AND word.meaning != ''
```
4. Конвертирате Stardict файла в .opf формат. Използвайте скрипта `convertors/tab2opf.py`. Оригиналният файл е изтеглен от [тук](http://www.klokan.cz/projects/stardict-lingea/tab2opf.py). Версията, която е качена локално изисква Python 2.
За да конвертирате, използвайте следната команда:
```
python tab2opf.py -utf bulgarian_dictionary.txt
```
5. Изтеглете програмта [KindlePreview](http://www.amazon.com/gp/feature.html?docId=1000765261).
6. Отворете .opf файла с KindlePreview, което автоматично ще генерира .mobi файл.
7. Инсталирайте генерирания .mobi файл според инструкциите.