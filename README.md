## Zdrava.online readme
[![Build Status](https://ci.mpolr.ru/api/badges/mpolr/zdrava.online/status.svg)](https://ci.mpolr.ru/mpolr/zdrava.online)

## Установка

1. ```cp .env.example .env```
2. ```composer install```
3. ```php artisan key:generate```
4. ```php artisan migrate```
5. ```npm install```
6. ```npm run build```
7. ```php artisan livewire:publish --assets```

## Шаги которые нужно выполнить после разворачивания проекта:

- Создать символическую ссылку `public/storage` на папку `storage/app/public`: из контейнера php выполнить команду `php artisan storage:link`


## Полезные ссылки / то что используется в проекте

- Простая система [лайков и дизлайков](https://dev.to/bdelespierre/how-to-implement-a-simple-like-system-with-laravel-lfe/comments) и [ещё интересный гайд по лайкам](https://rappasoft.com/blog/building-a-like-button-component-in-laravel-livewire)
- [Руководства по Ролям и Правам в Laravel](https://laravel.demiart.ru/guide-to-roles-and-permissions/)
- [Laravel Spatial](https://github.com/asanikovich/laravel-spatial) для работы с координатами в БД


- [FIT File Viewer](https://www.fitfileviewer.com/) для отладки FIT-файлов
- [Don't kill my app!](https://dontkillmyapp.com/) руководство как отключить энергосбережение в Android

### License
```Apache License, Version 2.0 and the Commons Clause Restriction```
