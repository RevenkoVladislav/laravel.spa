### Laravel - SPA

This is an educational project: a Single Page Application (SPA) built with Laravel and Vue.js. It features social media elements, allowing users to post articles, send messages, and follow other users.

![Laravel](https://img.shields.io/badge/laravel-%23FF2D20.svg?style=for-the-badge&logo=laravel&logoColor=white)
![Vue.js](https://img.shields.io/badge/vuejs-%2335495e.svg?style=for-the-badge&logo=vuedotjs&logoColor=%234FC08D)
![Vite](https://img.shields.io/badge/vite-%23646CFF.svg?style=for-the-badge&logo=vite&logoColor=white)
![TailwindCSS](https://img.shields.io/badge/tailwindcss-%2338B2AC.svg?style=for-the-badge&logo=tailwind-css&logoColor=white)
![MySQL](https://img.shields.io/badge/mysql-4479A1.svg?style=for-the-badge&logo=mysql&logoColor=white)
![Postman](https://img.shields.io/badge/Postman-FF6C37?style=for-the-badge&logo=postman&logoColor=white)

---
### Install and settings

```
git clone https://github.com/RevenkoVladislav/laravel.spa
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm install
npm vite
php artisan serve
php artisan schedule:work
```
---
### Current features

- Register and Login components
- Cookie sanctum auth
- Ability to create posts with images.
- Console command for manual storage cleanup and automated hourly scheduling.

```
php artisan images:clear-unused
//for automated
php artisan schedule:work
```
---
### Technologies used

- laravel 12
- Vue.js
- Vue router
- Pinia
- Mysqq
- Postman
- Vite
---
### Screenshots

<div><p>Sign In Page:</p>
<img width="930" height="622" alt="sign in" src="https://github.com/user-attachments/assets/ab869acf-e29d-4845-ab8d-b51517430a8b" />
</div>

---

<div><p>Creating posts:</p>
<img width="594" height="515" alt="post" src="https://github.com/user-attachments/assets/b059fcee-7f1c-424a-a4c3-e1356e95ceba" />
</div>

---

<div><p>Posts with errors:</p>
<img width="519" height="895" alt="post with errors" src="https://github.com/user-attachments/assets/b8e3c62f-ab35-4f6c-ba33-2f705dcdc55c" />
</div>

---

<div><p>Published posts:</p>
<img width="542" height="745" alt="published" src="https://github.com/user-attachments/assets/c5861ccc-95d1-4d1d-b13b-2d846a2ebe94" />
</div>

---
