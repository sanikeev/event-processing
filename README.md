##Балансировка обработки событий

Ссылка на задание - https://docs.google.com/document/d/1JgfTI-rB_0fkQhYEc6lEw6qX71-ulIeUDpoRYZPEU4Q/edit

###Установка

1. Клонируйте проект
2. Перейдите в папку проекта ```cd event-processing/```
3. Из папки проекта выполните в консоли ```docker-compose up -d```
4. ВАЖНО: Дождитесь пока запустятся демоны, проверить статус можно запустив ```docker-compose logs supervisor```

###Запуск и проверка работы

1. Выполните скрипт генерации событий ```docker-compose exec php php bin/console event:generate --accnum=1000```
2. Результат работы смотрите в файлах папки ```result/```

###Что хотел еще добавить

1. Симфони мессенджер не подходит для этой задачи, пробовал его прикрутить оказалось он не поддерживает динамическое создание очередей
2. В файле ```.env``` в параметре ```APP_MAXPROCS``` задается кол-во обработчиков, маршрутизация формируется динамически при изменении кол-ва (для этого нужно пересобрать контейнеры)
3. Возможно что-то сделал неэффективно в скрипте генерации, тут не было цели оптимизировать его
4. Не стал делать тесты тк по факту команда генерации событий это и есть функциональный тест