# Български тълковен речник за Kindle

Български тълковен речник в .mobi формат подходящ за Kindle устройства. Речника е съставен на основата на базата данни на ["Речко"](https://rechnik.chitanka.info/about).

# Инсталация

1. Вържете вашия Kindle към компютъра чрез USB кабел
2. Отворете папката `documents`, която се намира на устройството
3. Поставете файла `bulgarian_dictionary.mobi` в папката `dictionaries`
4. Разкачете устройството от компютъра

При правилна инсталация речника ще се появи в списъка с налични речници в менюто `Menu > Settings > Device Options > Language and Dictionaries > Dictionaries`.

# Screenshots

* При успешна инсаталция речника ще се появи по следния начин в списъка с речници:
![Успешна инсталация](https://raw.githubusercontent.com/yanosh-k/bulgarian_dictionary/master/screenshots/screen1.png)

* При избиране на дума резлутатите изглежат по следния начин:
![Избрана дума](https://raw.githubusercontent.com/yanosh-k/bulgarian_dictionary/master/screenshots/screen3.png)
![Избрана дума](https://raw.githubusercontent.com/yanosh-k/bulgarian_dictionary/master/screenshots/screen4.png)


# Как мога сам да генерирам речника от изходната база данни на "Речко"?

1. Изтеглете последната версия на базата данни на ["Речко"](https://rechnik.chitanka.info/db.sql.gz).
2. Импортирайте в локална инстанция на MySQL/MariaDB: `mysql -u user -p -h localhost rechko < db.sql`
3. Влезте в основната директория на проекта и изпълненте командата `php convertors/tab_generator.php -h localhost -p your_pass -u user -d rechko`. Това ще генерира файла `bulgarian_dictionary.txt`.
4. Влезте в папка `opf` и изпълнете командата `python ../convertors/tab2opf.py -utf ../bulgarian_dictionary.txt`. Това ще генерира `.ofp` речниците.
5. Влезте в папката `convertors` и изпълнетете командата `kindlegen.exe ../opf/bulgarian_dictionary.opf -c1 -o bulgarian_dictionary.mobi -verbose`. Това ще съсздаде файла bulgarian_dictionary.mobi в папка opf.