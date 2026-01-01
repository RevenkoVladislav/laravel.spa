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
- Likes, Repost and comments system.
- Following System: Mechanism for subscribing to other users.
- Feed displaying posts from subscriptions.
- Ability to view a list of liked posts.
- System for comment replies with animated scroll to the parent comment.

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

<div><p>Followed users</p>
<img width="509" height="687" alt="подписки" src="https://github.com/user-attachments/assets/697f41c3-3282-4e7c-b756-fcaaeb3082fc" />
</div>

---

<div><p>Feed Posts</p>
<img width="569" height="756" alt="посты подписчиков" src="https://github.com/user-attachments/assets/83f2be5a-b9b2-4c11-aea0-adcb337d3f39" />
</div>

---

<div><p>Liked Posts</p>
<img width="619" height="790" alt="лайкнутые посты" src="https://github.com/user-attachments/assets/cc9518c6-a1c8-483b-899a-0c978751d5e5" />
</div>

---

<div><p>Comments</p>
<img width="625" height="885" alt="комментарии" src="https://github.com/user-attachments/assets/7c3c0622-d0ae-408f-b19a-312079a9e553" />
</div>

---

<div><p>Repost</p>
<img width="571" height="723" alt="repost" src="https://github.com/user-attachments/assets/b95f78b6-79dc-4794-b617-9f1a11cd050c" />
</div>

