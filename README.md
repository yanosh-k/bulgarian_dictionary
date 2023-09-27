# Български тълковен речник за Kindle

Български тълковен речник в .mobi формат подходящ за Kindle устройства. Речника е съставен на основата, на базата данни, на ["Речко"](https://rechnik.chitanka.info/about).

# Първоначална инсталация

1. Вържете вашия Kindle към компютъра, чрез USB кабел.
2. Отворете папката `documents`, която се намира на устройството.
3. Поставете файла `bulgarian_dictionary.mobi` в папката `dictionaries`.
4. Разкачете устройството от компютъра.
5. Изберете речника като речник по подразбиране, от настройките на вашия Kindle.

При правилна инсталация речника ще се появи в списъка с налични речници в менюто `Menu > Settings > Device Options > Language and Dictionaries > Dictionaries`.

# Повторно инсталиране

Методът за първоначална инсталация може да не сработи при някои устройства, когато качвате нова версия на речника. За да избегнете проблеми при обновяване, моля следвайте следните стъпки:

1. Вържете вашия Kindle към компютъра, чрез USB кабел.
2. Отворете папката `documents`, която се намира на устройството.
3. Изтрийте папката `bulgarian_dictionary.sdr`, която се намира там, както и файла `bulgarian_dictionary.mobi`.
4. Разкачете устройството от компютъра.
5. Проверете, че маркирането на думи, при четене, не дава възможност за избор, на Bulgarain Dictionary, като възможен речник.
6. Следвайте стъпките от секция "Първоначална инсталация".


# Screenshots

* При успешна инсталация речника ще се появи по следния начин в списъка с речници:
![Успешна инсталация](https://raw.githubusercontent.com/yanosh-k/bulgarian_dictionary/master/screenshots/screen1.png)

* При избиране на дума резултатите изглеждат по следния начин:
![Избрана дума](https://raw.githubusercontent.com/yanosh-k/bulgarian_dictionary/master/screenshots/screen3.png)
![Избрана дума](https://raw.githubusercontent.com/yanosh-k/bulgarian_dictionary/master/screenshots/screen4.png)

# Contributors

* ["dannywinrow"](https://github.com/dannywinrow) - Fixed the limit on MySQL's `GROUP_CONCAT` function using `group_concat_max_len`. Updated SQL to removed words containing spaces in the `derivative_form`, as those are not used in word matching.

# Как мога сам да генерирам речника от изходната база данни на "Речко"?

Трябва да имате инсталиран ["PHP"](https://www.php.net/), ["MySQL"](https://www.mysql.com/), ["Git"](https://git-scm.com/), както и да притежавате основни познания за изпълнение на команди през терминал.

1. Изтеглете последната версия на базата данни на ["Речко"](https://rechnik.chitanka.info/db.sql.gz) и разархивирайте. Архивът може да бъде разархивиран с приложението ["7-zip"](https://www.7-zip.org/download.html) или под GNU/Linux, с командата `gunzip db.sql.gz`. Изходният файл ще е с големина около 730 MB.
2. Импортирайте разархивирания `.sql` файл в локална инстанция на MySQL/MariaDB: `mysql -u user -p -h localhost rechko < db.sql`. В тази примерна команда, името на базата данни е `rechko`. Параметърът `-p` казва нa mysql, че трябва интерактивно да изиска парола.
3. Клонирайте проекта, локално, при вас: `git clone git@github.com:yanosh-k/bulgarian_dictionary.git`
4. Влезте в онсовната директория на проекта и изпълненето командата `php convertors/db_to_jsonl.php -h localhost -u YOUR_USER -p 'YOUR_PASSWORD' -d rechko`, като замените `YOUR_USER` и `YOUR_PASSWORD` с вашите данни. Това ще генерира файла `bulgarian_dictionary.jsonl`, който съдържа структурираната информация за всички думи налични в речника. Всеки един ред, във файл, е отделна дума, например: `{"base_word": "абсолют", "id": "29556", "meaning": "Вечната, неизменна, безкрайна първопричина на вселената, която е синоним на Бог.", "derivative_forms": ["абсолют", "абсолюта", "абсолюти", "абсолютите","абсолютът"]}`. Файлът `bulgarian_dictionary.jsonl` е междинен файл, който може да бъде използван от други приложения и за това генерирането на `.opf` файл не е директно.
5. Изпълнете командата `php convertors/jsonl_to_opf.php -i bulgarian_dictionary.jsonl`. Това ще генерира `.ofp` речниците от вече генерирания `bulgarian_dictionary.jsonl`. Те ще бъдат записани в `opf` директорията.
6. Изпълнете командата `convertors/kindlegen.exe opf/bulgarian_dictionary.opf`. Може да замените `kindlegen.exe` с `kindlegen`, ако сте под GNU/Linux. Тази команда ще създаде файла `bulgarian_dictionary.mobi`, в папка `opf`. Можете да използвате генерирания файл, като следвате инструкциите за инсталация.

*Версиите на `kindlegen` са изтеглени от https://archive.org/details/kindlegen2.9. Архивът, който се намира там е сравнен с версията от https://aur.archlinux.org/packages/kindlege, които считам за надеждни.*